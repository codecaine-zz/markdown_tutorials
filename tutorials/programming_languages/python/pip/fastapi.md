# FastAPI — A Practical, Copy‑Paste Friendly Tutorial

A beginner‑friendly, end‑to‑end guide to building clean, production‑ready APIs with FastAPI. Includes a complete table of contents, best practices, and reusable snippets you can copy and paste.

---

## Table of Contents

- Introduction
  - What is FastAPI?
  - Why use FastAPI?
  - Prerequisites
- Setup
  - Install and run
  - Project layout (recommended)
- Your first API
  - Hello World
  - Path and query parameters
  - Request/response models (Pydantic v2)
- Organizing your app
  - Routers (modular endpoints)
  - Settings and configuration (pydantic‑settings)
  - Dependency injection (FastAPI Depends)
  - Middleware and CORS
- CRUD example (in‑memory)
  - Schemas (create/update/read)
  - Endpoints
  - Pagination helper (reusable)
- Error handling and responses
  - HTTPException + custom exceptions
  - Status codes and response_model
- Background tasks
- Auth quickstart (API key + JWT)
- Testing with pytest
- Extras
  - WebSockets
  - Serving static files
  - Dockerfile (production image)
  - Production tips
- Reusable snippets (copy/paste)
- Troubleshooting

---

## Introduction

### What is FastAPI?

FastAPI is a modern, high‑performance Python web framework for building APIs, powered by type hints and Pydantic for data validation. It’s async‑first, easy to test, and auto‑documents your API with OpenAPI/Swagger.

### Why use FastAPI?

- Very fast (Starlette + uvicorn under the hood)
- Type‑driven validation with Pydantic v2
- Automatic interactive docs: Swagger UI and ReDoc
- Batteries included: dependency injection, background tasks, WebSockets, and more

### Prerequisites

- Python 3.10+
- Comfortable with basic Python types and functions
- Optional: virtual environments, pytest basics

---

## Setup

### Install and run

Use a virtual environment and install the “standard” extra (includes uvicorn and useful deps):

```bash
# macOS / zsh
python3 -m venv .venv
source .venv/bin/activate
pip install --upgrade pip
pip install "fastapi[standard]" pydantic-settings python-jose[cryptography] pytest
```

Run a FastAPI app with uvicorn:

```bash
uvicorn app.main:app --reload --port 8000
```

Visit the docs at:

- Swagger UI: <http://127.0.0.1:8000/docs>
- ReDoc: <http://127.0.0.1:8000/redoc>

### Project layout (recommended)

Keep things small, explicit, and composable.

```text
app/
  main.py
  api/
    __init__.py
    routes/
      __init__.py
      items.py
  core/
    __init__.py
    config.py   # settings
    security.py # auth helpers
  models/
    __init__.py
    schemas.py  # pydantic models
static/
.tests/
```

---

## Your first API

### Hello World (app/main.py)

```python
# app/main.py
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.api.routes import items
from app.core.config import settings

app = FastAPI(
    title=settings.PROJECT_NAME,
    version=settings.VERSION,
    docs_url="/docs",
    redoc_url="/redoc",
)

# CORS (configure origins in settings)
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
async def root():
    return {"message": "Hello FastAPI"}

# Mount routers
app.include_router(items.router, prefix="/api")
```

### Path and query parameters

```python
from fastapi import APIRouter, Query

router = APIRouter()

@router.get("/echo/{name}")
async def echo(name: str, shout: bool = Query(False)):
    msg = name.upper() if shout else name
    return {"echo": msg}
```

### Request/response models (Pydantic v2)

```python
from pydantic import BaseModel, Field
from typing import Optional

class User(BaseModel):
    id: int | None = None
    email: str = Field(pattern=r"^.+@.+\..+$")
    full_name: Optional[str] = None
```

---

## Organizing your app

### Routers (modular endpoints)

Create `app/api/routes/items.py` and register it in `main.py`.

```python
# app/api/routes/items.py
from fastapi import APIRouter

router = APIRouter(prefix="/items", tags=["items"])

@router.get("/")
async def list_items():
    return [
        {"id": 1, "name": "Keyboard", "price": 59.9},
        {"id": 2, "name": "Mouse", "price": 29.9},
    ]
```

### Settings and configuration (pydantic‑settings)

```python
# app/core/config.py
from functools import lru_cache
from pydantic_settings import BaseSettings, SettingsConfigDict

class Settings(BaseSettings):
    PROJECT_NAME: str = "FastAPI Tutorial"
    VERSION: str = "0.1.0"
    DEBUG: bool = True

    # CORS
    CORS_ORIGINS: list[str] = [
        "http://localhost:3000",
        "http://localhost:5173",
        "http://127.0.0.1:5173",
    ]

    # Security
    API_KEY: str = "changeme"
    JWT_SECRET: str = "dev-secret-change"
    JWT_ALGORITHM: str = "HS256"

    model_config = SettingsConfigDict(env_file=".env")

@lru_cache
def get_settings() -> Settings:
    return Settings()

# Convenience module-level instance
settings = get_settings()
```

### Dependency injection (Depends)

```python
from fastapi import Depends, HTTPException, status
from fastapi.security import APIKeyHeader
from app.core.config import settings

api_key_header = APIKeyHeader(name="X-API-Key", auto_error=False)

def require_api_key(key: str | None = Depends(api_key_header)):
    # In DEBUG, let everything through; tighten in prod.
    if settings.DEBUG:
        return True
    if not key or key != settings.API_KEY:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid API key")
    return True
```

Use it on protected routes:

```python
@router.post("/items", dependencies=[Depends(require_api_key)])
async def create_item():
    ...
```

### Middleware and CORS

Already added in `main.py`. Configure origins via `settings.CORS_ORIGINS`.

---

## CRUD example (in‑memory)

### Schemas (create/update/read)

```python
# app/models/schemas.py
from typing import Optional
from pydantic import BaseModel, Field

class ItemBase(BaseModel):
    name: str = Field(min_length=1, max_length=50)
    price: float = Field(ge=0)
    description: Optional[str] = None
    tags: list[str] = []

class ItemCreate(ItemBase):
    pass

class ItemUpdate(BaseModel):
    name: Optional[str] = None
    price: Optional[float] = None
    description: Optional[str] = None
    tags: Optional[list[str]] = None

class Item(ItemBase):
    id: int
```

### Endpoints (app/api/routes/items.py)

```python
# app/api/routes/items.py
from fastapi import APIRouter, Depends, HTTPException, Query, status, BackgroundTasks
from app.models.schemas import Item, ItemCreate, ItemUpdate
from app.core.security import require_api_key

router = APIRouter(prefix="/items", tags=["items"])

# Simple in-memory store for demo purposes
_DB: dict[int, Item] = {}
_NEXT_ID = 1


def paginate(limit: int = Query(10, ge=1, le=100), offset: int = Query(0, ge=0)) -> tuple[int, int]:
    return limit, offset


def _send_create_email(item: Item):
    # placeholder: send email/notify/log
    print(f"Created item: {item.name} (#{item.id})")


@router.get("/", response_model=list[Item])
async def list_items(p: tuple[int, int] = Depends(paginate)):
    limit, offset = p
    items = list(_DB.values())
    return items[offset : offset + limit]


@router.get("/{item_id}", response_model=Item)
async def get_item(item_id: int):
    item = _DB.get(item_id)
    if not item:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")
    return item


@router.post("/", status_code=status.HTTP_201_CREATED, response_model=Item, dependencies=[Depends(require_api_key)])
async def create_item(payload: ItemCreate, tasks: BackgroundTasks):
    global _NEXT_ID
    item = Item(id=_NEXT_ID, **payload.model_dump())
    _DB[_NEXT_ID] = item
    _NEXT_ID += 1
    tasks.add_task(_send_create_email, item)
    return item


@router.patch("/{item_id}", response_model=Item, dependencies=[Depends(require_api_key)])
async def update_item(item_id: int, payload: ItemUpdate):
    item = _DB.get(item_id)
    if not item:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")

    updates = payload.model_dump(exclude_unset=True)
    updated = item.model_copy(update=updates)
    _DB[item_id] = updated
    return updated


@router.delete("/{item_id}", status_code=status.HTTP_204_NO_CONTENT, dependencies=[Depends(require_api_key)])
async def delete_item(item_id: int):
    if item_id in _DB:
        del _DB[item_id]
        return
    raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")
```

### Pagination helper (reusable)

See the `paginate` dependency above. You can reuse it across routers to keep pagination consistent.

---

## Error handling and responses

### HTTPException + custom exceptions

```python
from fastapi import HTTPException, status

raise HTTPException(status_code=status.HTTP_400_BAD_REQUEST, detail="Bad input")
```

Custom exception + handler:

```python
from fastapi import Request
from fastapi.responses import JSONResponse

class OutOfStock(Exception):
    def __init__(self, item_id: int):
        self.item_id = item_id

@app.exception_handler(OutOfStock)
async def out_of_stock_handler(_: Request, exc: OutOfStock):
    return JSONResponse(status_code=409, content={"error": f"Item {exc.item_id} is out of stock"})
```

### Status codes and response_model

- Use `status` constants (e.g., `status.HTTP_201_CREATED`).
- Always set `response_model` to control output shape and hide internal fields.

---

## Background tasks

Use `BackgroundTasks` to run small async‑ish side jobs after returning a response.

```python
from fastapi import BackgroundTasks

@router.post("/notify")
async def notify(tasks: BackgroundTasks):
    tasks.add_task(lambda: print("Notify!"))
    return {"queued": True}
```

---

## Auth quickstart (API key + JWT)

### API key (simple)

Used above via `X-API-Key` header and `require_api_key` dependency.

### JWT (minimal)

```python
# app/core/security.py
from datetime import datetime, timedelta
from typing import Optional

from fastapi import Depends, HTTPException, status
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer
from jose import JWTError, jwt
from pydantic import BaseModel

from app.core.config import settings

bearer = HTTPBearer(auto_error=False)

class TokenData(BaseModel):
    sub: str
    exp: Optional[int] = None


def create_access_token(subject: str, expires_minutes: int = 60) -> str:
    to_encode = {
        "sub": subject,
        "exp": datetime.utcnow() + timedelta(minutes=expires_minutes),
    }
    return jwt.encode(to_encode, settings.JWT_SECRET, algorithm=settings.JWT_ALGORITHM)


def decode_access_token(token: str) -> TokenData:
    try:
        payload = jwt.decode(token, settings.JWT_SECRET, algorithms=[settings.JWT_ALGORITHM])
        return TokenData(sub=payload["sub"], exp=payload.get("exp"))
    except (JWTError, KeyError):
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid token")


def get_current_user(creds: HTTPAuthorizationCredentials | None = Depends(bearer)):
    if not creds:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Not authenticated")
    data = decode_access_token(creds.credentials)
    return {"username": data.sub}
```

Login endpoint example:

```python
@router.post("/login")
async def login(username: str, password: str):
    # Replace with real user lookup/verification
    if username == "admin" and password == "admin":
        return {"access_token": create_access_token(subject=username), "token_type": "bearer"}
    raise HTTPException(status_code=401, detail="Invalid credentials")
```

Protect endpoints with `Depends(get_current_user)`.

---

## Testing with pytest

Install pytest (already in earlier pip install). Example tests:

```python
# tests/test_items.py
from fastapi.testclient import TestClient
from app.main import app

client = TestClient(app)

def test_root():
    res = client.get("/")
    assert res.status_code == 200
    assert res.json()["message"].startswith("Hello")


def test_create_list_cycle(monkeypatch):
    # Allow write operations in test without API key
    from app.core import config
    monkeypatch.setattr(config.settings, "DEBUG", True)

    payload = {"name": "Book", "price": 10.5}
    res = client.post("/api/items/", json=payload)
    assert res.status_code == 201, res.text

    res = client.get("/api/items/")
    assert res.status_code == 200
    items = res.json()
    assert any(i["name"] == "Book" for i in items)
```

Run tests:

```bash
pytest -q
```

---

## Extras

### WebSockets

```python
# app/main.py (add)
from fastapi import WebSocket

@app.websocket("/ws")
async def ws_endpoint(ws: WebSocket):
    await ws.accept()
    await ws.send_text("Connected")
    try:
        while True:
            msg = await ws.receive_text()
            await ws.send_text(f"echo: {msg}")
    except Exception:
        await ws.close()
```

### Serving static files

```python
# app/main.py (add)
from fastapi.staticfiles import StaticFiles
app.mount("/static", StaticFiles(directory="static"), name="static")
```

### Dockerfile (production image)

```dockerfile
# Dockerfile
FROM python:3.12-slim
ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1

WORKDIR /app
COPY pyproject.toml requirements.txt* /app/
RUN pip install --no-cache-dir --upgrade pip \
 && if [ -f requirements.txt ]; then pip install --no-cache-dir -r requirements.txt; fi
# Or: pip install "fastapi[standard]" gunicorn

COPY . /app

EXPOSE 8000
CMD ["bash", "-lc", "gunicorn -k uvicorn.workers.UvicornWorker -w 2 -b 0.0.0.0:8000 app.main:app"]
```

### Production tips

- Put secrets in environment variables (.env for local only)
- Set `DEBUG=False` in production
- Use a process manager (gunicorn) with multiple workers
- Add request logging and structured logs (e.g., loguru)
- Enable CORS only for known origins
- Health check endpoint (e.g., `/healthz`)

---

## Reusable snippets (copy/paste)

### Settings (env‑driven)

```python
from functools import lru_cache
from pydantic_settings import BaseSettings, SettingsConfigDict

class Settings(BaseSettings):
    PROJECT_NAME: str = "My API"
    VERSION: str = "0.1.0"
    DEBUG: bool = False
    CORS_ORIGINS: list[str] = []
    API_KEY: str = ""

    model_config = SettingsConfigDict(env_file=".env")

@lru_cache
def get_settings() -> Settings:
    return Settings()
```

### Pagination dependency

```python
from fastapi import Query

def paginate(limit: int = Query(10, ge=1, le=100), offset: int = Query(0, ge=0)) -> tuple[int, int]:
    return limit, offset
```

### API key guard

```python
from fastapi import Depends, HTTPException, status
from fastapi.security import APIKeyHeader

api_key_header = APIKeyHeader(name="X-API-Key", auto_error=False)

def require_api_key(key: str | None = Depends(api_key_header)):
    if not key or key != "expected-key":
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid API key")
    return True
```

### JWT helpers

```python
from datetime import datetime, timedelta
from typing import Optional
from jose import jwt, JWTError
from pydantic import BaseModel

SECRET = "change-me"
ALG = "HS256"

class TokenData(BaseModel):
    sub: str
    exp: Optional[int] = None

def create_access_token(subject: str, minutes: int = 60) -> str:
    payload = {"sub": subject, "exp": datetime.utcnow() + timedelta(minutes=minutes)}
    return jwt.encode(payload, SECRET, algorithm=ALG)

def decode_access_token(token: str) -> TokenData:
    try:
        data = jwt.decode(token, SECRET, algorithms=[ALG])
        return TokenData(sub=data["sub"], exp=data.get("exp"))
    except (JWTError, KeyError) as exc:
        raise ValueError("invalid token") from exc
```

### Common responses

```python
from fastapi import status

CREATED = {"status_code": status.HTTP_201_CREATED}
NO_CONTENT = {"status_code": status.HTTP_204_NO_CONTENT}
```

---

## Troubleshooting

- ImportError: No module named 'app' — ensure you’re running from the project root and your `PYTHONPATH` allows `app` package, or use `python -m uvicorn app.main:app ...`.
- Validation error — check your Pydantic model fields and types. For partial updates, use `exclude_unset=True`.
- CORS blocked — verify `allow_origins` contains your frontend origin exactly (scheme + host + port).
- 401 Unauthorized — ensure you send the required headers (e.g., `X-API-Key` or `Authorization: Bearer <token>`).

---

Happy building! Open the docs at `/docs` and try the endpoints interactively.
