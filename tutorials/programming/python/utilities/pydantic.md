# Pydantic v2 — Practical Tutorial (with Table of Contents)

A concise, copy‑pasteable guide to [Pydantic](https://docs.pydantic.dev/latest/) v2 for data validation, parsing, and settings. Examples favor modern v2 APIs (model_dump, validators, TypeAdapter, etc.).

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start](#quick-start)
- [Validation and Parsing](#validation-and-parsing)
- [Field Types and Constraints](#field-types-and-constraints)
- [Validators (field and model)](#validators-field-and-model)
- [Config and Strictness](#config-and-strictness)
- [Serialization (model_dump)](#serialization-model_dump)
- [Nested Models, Lists, and Unions](#nested-models-lists-and-unions)
- [Computed Fields and Default Factories](#computed-fields-and-default-factories)
- [Dataclasses](#dataclasses)
- [Settings (pydantic-settings)](#settings-pydantic-settings)
- [TypeAdapter and Utilities](#typeadapter-and-utilities)
- [JSON Schema](#json-schema)
- [Error Handling](#error-handling)
- [Common Patterns](#common-patterns)
- [v1 → v2 Notes](#v1--v2-notes)
- [Troubleshooting](#troubleshooting)

---

## Overview

Pydantic uses Python type hints to validate and serialize data. In v2, validation is faster and APIs are simplified. Key ideas:

- Define models with typing annotations.
- Invalid input raises `pydantic.ValidationError`.
- Use `model_dump()` / `model_dump_json()` for serialization.
- Use `@field_validator` & `@model_validator` for custom logic.
- `TypeAdapter` validates arbitrary types outside models.

## Install

```bash
pip install pydantic
# optional, for app settings management
pip install pydantic-settings
# optional, for email/url types
pip install "pydantic[email]"
```

## Quick Start

```python
from pydantic import BaseModel, ValidationError

class User(BaseModel):
    id: int
    name: str
    is_active: bool = True

try:
    u = User(id="123", name="Ada")  # id coerced to int
    print(u)
    print(u.model_dump())
except ValidationError as e:
    print(e)
```

## Validation and Parsing

```python
from pydantic import BaseModel, ValidationError

class Product(BaseModel):
    sku: str
    price: float
    tags: list[str] = []

p = Product.model_validate({"sku": "ABC-1", "price": "9.99", "tags": ["sale"]})
print(p.price)  # 9.99

try:
    Product.model_validate({"sku": "X", "price": "oops"})
except ValidationError as e:
    print(e.errors())  # list of error details
```

## Field Types and Constraints

```python
from pydantic import BaseModel
from pydantic import AnyUrl, EmailStr
from typing import Annotated
from pydantic.types import PositiveInt
from pydantic import conint, constr, confloat

# v2 prefers Annotated + constraints
NonEmptyStr = Annotated[str, constr(min_length=1)]
Port = Annotated[int, conint(ge=1, le=65535)]
Percent = Annotated[float, confloat(ge=0.0, le=1.0)]

class Profile(BaseModel):
    email: EmailStr
    website: AnyUrl | None = None
    username: NonEmptyStr
    port: Port = 8080
    success_ratio: Percent = 1.0
    age: PositiveInt
```

## Validators (field and model)

```python
from pydantic import BaseModel, field_validator, model_validator

class Account(BaseModel):
    username: str
    password: str
    confirm_password: str

    @field_validator("username")
    @classmethod
    def username_rules(cls, v: str) -> str:
        if " " in v:
            raise ValueError("username must not contain spaces")
        return v

    @model_validator(mode="after")
    def passwords_match(self):
        if self.password != self.confirm_password:
            raise ValueError("passwords do not match")
        return self
```

## Config and Strictness

```python
from pydantic import BaseModel, ConfigDict

class StrictExample(BaseModel):
    # forbid extra fields; make ints strict
    model_config = ConfigDict(extra="forbid", strict=True)

    count: int

StrictExample.model_validate({"count": 3})
# StrictExample.model_validate({"count": "3"})  # raises: strict int required
# StrictExample.model_validate({"count": 3, "x": 1})  # raises: extra fields
```

## Serialization (model_dump)

```python
from pydantic import BaseModel

class Article(BaseModel):
    id: int
    title: str
    subtitle: str | None = None

art = Article(id=1, title="Hello", subtitle=None)
print(art.model_dump())
print(art.model_dump(exclude_none=True))
print(art.model_dump_json())
```

## Nested Models, Lists, and Unions

```python
from pydantic import BaseModel

class Item(BaseModel):
    name: str
    qty: int

class Order(BaseModel):
    order_id: str
    items: list[Item]

order = Order.model_validate({
    "order_id": "A-1",
    "items": [{"name": "pen", "qty": 2}, {"name": "book", "qty": 1}],
})
print(order.items[0].name)

from typing import Union
class IntOrStrModel(BaseModel):
    value: int | str  # same as Union[int, str]
```

## Computed Fields and Default Factories

```python
from pydantic import BaseModel, computed_field, Field

class Box(BaseModel):
    width: float
    height: float
    color: str = Field(default_factory=lambda: "blue")

    @computed_field
    @property
    def area(self) -> float:
        return self.width * self.height

b = Box(width=3, height=4)
print(b.color, b.area)
```

## Dataclasses

```python
from pydantic.dataclasses import dataclass

@dataclass
class Point:
    x: int
    y: int

p = Point(x="3", y=4)  # validated/coerced
print(p)
```

## Settings (pydantic-settings)

```python
# pip install pydantic-settings
from pydantic_settings import BaseSettings, SettingsConfigDict

class AppSettings(BaseSettings):
    model_config = SettingsConfigDict(env_prefix="APP_", env_file=".env")

    debug: bool = False
    db_url: str
    timeout_s: int = 30

# Environment will override defaults
# .env example:
# APP_DEBUG=true
# APP_DB_URL=postgresql://user:pass@localhost:5432/db
# APP_TIMEOUT_S=60

settings = AppSettings()  # automatically reads environment & .env
print(settings.model_dump())
```

## TypeAdapter and Utilities

```python
from typing import Annotated
from pydantic import TypeAdapter, conlist

# validate a list of positive ints without a model
PositiveIntList = Annotated[list[int], conlist(int, min_length=1)]
adapter = TypeAdapter(PositiveIntList)

ok = adapter.validate_python([1, 2, 3])
print(ok)

# from JSON string
data = adapter.validate_json("[1, 2, 3]")
print(data)
```

## JSON Schema

```python
from pydantic import BaseModel

class User(BaseModel):
    id: int
    name: str

schema = User.model_json_schema()
print(schema["title"], list(schema["properties"].keys()))
```

## Error Handling

```python
from pydantic import BaseModel, ValidationError

class Payload(BaseModel):
    a: int

try:
    Payload.model_validate({"a": "oops"})
except ValidationError as e:
    # readable message
    print(str(e))
    # structured details
    for err in e.errors():
        print(err["loc"], err["msg"], err["type"]) 
```

## Common Patterns

```python
from pydantic import BaseModel, Field
from pydantic import HttpUrl, SecretStr
from enum import Enum
from uuid import UUID, uuid4
from datetime import datetime

class Role(str, Enum):
    user = "user"
    admin = "admin"

class User(BaseModel):
    id: UUID = Field(default_factory=uuid4)
    email: str
    homepage: HttpUrl | None = None
    password: SecretStr
    role: Role = Role.user
    created_at: datetime = Field(default_factory=datetime.utcnow)

u = User(email="a@b.co", password=SecretStr("s3cr3t"))
print(u.model_dump(exclude={"password"}))  # hide secret
```

## v1 → v2 Notes

- model.json() → model_dump_json(); model.dict() → model_dump().
- validators: `@validator` → `@field_validator`, `@root_validator` → `@model_validator`.
- parse_obj/parse_raw → model_validate / TypeAdapter equivalents.
- Config class → `model_config = ConfigDict(...)`.

See official migration guide: [Pydantic v2 Migration](https://docs.pydantic.dev/latest/migration/).

## Troubleshooting

- Extra fields rejected? Set `model_config = ConfigDict(extra="ignore")` or allow.
- Need strict types? Set `strict=True` in `ConfigDict` or use constrained types (e.g., `conint(strict=True)`).
- Time zones: ensure you pass aware datetimes if you need TZ info preserved.
- Large payloads: validate with `TypeAdapter` and pre‑sanitized schemas to speed up.

---

This tutorial targets Pydantic v2 APIs and avoids deprecated patterns. Copy code blocks directly into your projects.
