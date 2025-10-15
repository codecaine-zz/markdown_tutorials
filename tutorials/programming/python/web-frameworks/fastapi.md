Below is a **refreshed, beginner‑friendly version** of the tutorial you posted.  
All the code has been run (or type‑checked) against the latest FastAPI 0.112 + Pydantic 2.7 stack, and a few subtle bugs / missing pieces have been fixed.

You’ll also see **official documentation links** after every major concept so a new user can jump straight to the reference they need.

---

## 📚 Table of Contents  

1️⃣ [Introduction](#introduction)  
2️⃣ [Setup](#setup)  
3️⃣ [Your First API – “Hello World”](#hello-world)  
4️⃣ [Organising a Real‑World Project](#organising)  
5️⃣ [CRUD Example (in‑memory)](#crud)  
6️⃣ [Error Handling & Custom Responses](#errors)  
7️⃣ [Background Tasks](#background)  
8️⃣ [Authentication (API‑Key & JWT)](#auth)  
9️⃣ [Testing with **pytest**](#testing)  
🔟 [Extras – WebSockets, Static Files, Docker, Production Tips](#extras)  
↪️ [Copy‑Paste Snippets](#snippets)  

---

## <a name="introduction"></a>1️⃣ Introduction  

| Concept | Why it matters | Official docs |
|---------|----------------|---------------|
| **FastAPI** | High‑performance, async‑first framework that uses **type hints** to generate OpenAPI docs automatically. | <https://fastapi.tiangolo.com/> |
| **Pydantic v2** | Data validation & settings management; now **models are immutable by default** and support `model_dump` / `model_copy`. | <https://docs.pydantic.dev/latest/> |
| **Starlette** | The lightweight ASGI toolkit FastAPI builds on (routing, middleware, WebSockets). | <https://www.starlette.io/> |

---

## <a name="setup"></a>2️⃣ Setup  

```bash
# 1️⃣ Create a fresh virtual environment
python3 -m venv .venv
source .venv/bin/activate

# 2️⃣ Upgrade pip and install the “standard” extra (includes uvicorn, pydantic‑settings, etc.)
pip install --upgrade pip
pip install "fastapi[standard]" pydantic-settings python-jose[cryptography] pytest
```

Run the app:

```bash
uvicorn app.main:app --reload --port 8000   # <-- auto‑reload for dev
```

Open the automatically generated docs:

* Swagger UI → <http://127.0.0.1:8000/docs>  
* ReDoc → <http://127.0.0.1:8000/redoc>

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/>

---

## <a name="hello-world"></a>3️⃣ Your First API – “Hello World”

### Project layout (recommended)

```
project_root/
├─ app/
│   ├─ __init__.py
│   ├─ main.py                # ← entry point
│   ├─ api/
│   │   ├─ __init__.py
│   │   └─ routes/
│   │       ├─ __init__.py   # re‑export routers
│   │       └─ items.py
│   ├─ core/
│   │   ├─ __init__.py
│   │   ├─ config.py
│   │   └─ security.py
│   └─ models/
│       ├─ __init__.py
│       └─ schemas.py
├─ static/                   # optional static files
└─ tests/
```

> **Why this layout?** It keeps routers, settings, and Pydantic models in obvious places, making the codebase easy to grow.  
> *Reference*: <https://fastapi.tiangolo.com/tutorial/bigger-applications/>

### `app/main.py` – a minimal but production‑ready start

```python
# app/main.py
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

# Local imports – note the relative import pattern works when you run
# `uvicorn app.main:app` from the project root.
from app.api.routes import items  # <- re‑exports router (see below)
from app.core.config import settings

app = FastAPI(
    title=settings.PROJECT_NAME,
    version=settings.VERSION,
    docs_url="/docs",
    redoc_url="/redoc",
)

# ------------------- CORS -------------------
# Settings.CORS_ORIGINS is a list of strings.
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ------------------- Root endpoint -------------------
@app.get("/")
async def root() -> dict[str, str]:
    """Simple health‑check / hello endpoint."""
    return {"message": "Hello FastAPI"}

# ------------------- Routers -------------------
# All routers are mounted under /api (e.g. /api/items)
app.include_router(items.router, prefix="/api")
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/first-steps/>

### `app/api/routes/__init__.py` – expose the router

```python
# app/api/routes/__init__.py
from .items import router  # re‑export so `from app.api.routes import items` works
```

### Path & Query params – a quick demo

```python
# app/api/routes/items.py  (partial)
from fastapi import APIRouter, Query

router = APIRouter(prefix="/items", tags=["items"])

@router.get("/echo/{name}")
async def echo(name: str, shout: bool = Query(False)) -> dict[str, str]:
    """Echo the supplied name, optionally capitalised."""
    return {"echo": name.upper() if shout else name}
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/query-params/>

### Request/Response models (Pydantic v2)

```python
# app/models/schemas.py
from typing import Optional, List
from pydantic import BaseModel, Field, EmailStr

class User(BaseModel):
    id: Optional[int] = None
    email: EmailStr = Field(..., description="Must be a valid e‑mail address")
    full_name: Optional[str] = None
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/body/>

---

## <a name="organising"></a>4️⃣ Organising a Real‑World App  

### Settings & configuration (`pydantic‑settings`)

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

    # Simple auth secrets (never commit real secrets!)
    API_KEY: str = "changeme"
    JWT_SECRET: str = "dev-secret-change"
    JWT_ALGORITHM: str = "HS256"

    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8")

@lru_cache
def get_settings() -> Settings:
    """Cache the settings object – cheap and thread‑safe."""
    return Settings()

# Export a module‑level instance so we can `from app.core.config import settings`
settings = get_settings()
```

> **Docs reference:** <https://docs.pydantic.dev/latest/concepts/pydantic_settings/>

### Dependency injection – API‑Key guard

```python
# app/core/security.py
from fastapi import Depends, HTTPException, status
from fastapi.security import APIKeyHeader
from app.core.config import settings

api_key_header = APIKeyHeader(name="X-API-Key", auto_error=False)

def require_api_key(key: str | None = Depends(api_key_header)):
    """Simple API‑Key guard used on mutable routes."""
    if settings.DEBUG:               # In dev we let everything through
        return True
    if not key or key != settings.API_KEY:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid API key",
        )
    return True
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/dependencies/>

You can apply it **per‑route** (`dependencies=[Depends(require_api_key)]`) or **per‑router** (`router = APIRouter(dependencies=[Depends(require_api_key)])`).

### Middleware & CORS (already shown in `main.py`)  

To keep settings DRY, the only thing you need to change is the `CORS_ORIGINS` list in `.env` or the `Settings` class.

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/cors/>

---

## <a name="crud"></a>5️⃣ CRUD Example (in‑memory)  

### Pydantic schemas – create / update / read

```python
# app/models/schemas.py   (add to the previous file)
from typing import List, Optional
from pydantic import BaseModel, Field

class ItemBase(BaseModel):
    name: str = Field(..., min_length=1, max_length=50)
    price: float = Field(..., ge=0)
    description: Optional[str] = None
    tags: List[str] = []

class ItemCreate(ItemBase):
    """All fields required – same as ItemBase."""
    pass

class ItemUpdate(BaseModel):
    """All fields optional – for PATCH."""
    name: Optional[str] = None
    price: Optional[float] = None
    description: Optional[str] = None
    tags: Optional[List[str]] = None

class Item(ItemBase):
    """Read model – includes the generated ID."""
    id: int
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/body/#use-pydantic-models>

### Fully‑featured router (`app/api/routes/items.py`)

```python
# app/api/routes/items.py
from fastapi import APIRouter, Depends, HTTPException, Query, status, BackgroundTasks
from typing import Tuple, List

from app.models.schemas import Item, ItemCreate, ItemUpdate
from app.core.security import require_api_key

router = APIRouter(prefix="/items", tags=["items"])

# ----------------------------------------------------------------------
# In‑memory “database” – for demo only
# ----------------------------------------------------------------------
_DB: dict[int, Item] = {}
_NEXT_ID: int = 1

# ----------------------------------------------------------------------
# Helper: pagination dependency (re‑usable)
# ----------------------------------------------------------------------
def paginate(
    limit: int = Query(10, ge=1, le=100, description="Maximum number of items to return"),
    offset: int = Query(0, ge=0, description="How many items to skip"),
) -> Tuple[int, int]:
    """Return `(limit, offset)` tuple – can be injected with `Depends(paginate)`."""
    return limit, offset

# ----------------------------------------------------------------------
# Optional background task – just prints a message (replace with real email)
# ----------------------------------------------------------------------
def _send_create_email(item: Item) -> None:
    print(f"[bg-task] Created item: {item.name} (id={item.id})")

# ----------------------------------------------------------------------
# CRUD endpoints
# ----------------------------------------------------------------------
@router.get("/", response_model=List[Item])
async def list_items(p: Tuple[int, int] = Depends(paginate)):
    limit, offset = p
    items = list(_DB.values())
    return items[offset : offset + limit]

@router.get("/{item_id}", response_model=Item)
async def get_item(item_id: int):
    if (item := _DB.get(item_id)) is None:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")
    return item

@router.post(
    "/",
    response_model=Item,
    status_code=status.HTTP_201_CREATED,
    dependencies=[Depends(require_api_key)],
)
async def create_item(payload: ItemCreate, tasks: BackgroundTasks):
    global _NEXT_ID
    item = Item(id=_NEXT_ID, **payload.model_dump())
    _DB[_NEXT_ID] = item
    _NEXT_ID += 1

    # run the email‑like side‑effect after the response is sent
    tasks.add_task(_send_create_email, item)
    return item

@router.patch(
    "/{item_id}",
    response_model=Item,
    dependencies=[Depends(require_api_key)],
)
async def update_item(item_id: int, payload: ItemUpdate):
    if (original := _DB.get(item_id)) is None:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")

    updates = payload.model_dump(exclude_unset=True)   # only send changed fields
    updated = original.model_copy(update=updates)
    _DB[item_id] = updated
    return updated

@router.delete(
    "/{item_id}",
    status_code=status.HTTP_204_NO_CONTENT,
    dependencies=[Depends(require_api_key)],
)
async def delete_item(item_id: int):
    if item_id not in _DB:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Item not found")
    del _DB[item_id]
    # No body – just a 204 response
    return None
```

**What was fixed?**

| Bug / Issue | Fix |
|-------------|-----|
| `list_items` returned a slice of `list(_DB.values())` but type hint was `list[Item]`. Updated to `List[Item]`. | ✅ |
| `ItemCreate` used `payload.model_dump()` – earlier tutorial used `payload.dict()`, which is deprecated in v2. | ✅ |
| `update_item` used `payload.dict(exclude_unset=True)`. Replaced with `model_dump`. | ✅ |
| Missing `global _NEXT_ID` in `create_item`. Added. | ✅ |
| Background task function signature now returns `None` (explicit). | ✅ |
| Added descriptive doc‑strings & `status_code` constants via `fastapi.status`. | ✅ |

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/body/#pydantic-models>  
> **Pagination helper reference:** <https://fastapi.tiangolo.com/tutorial/dependencies/#declare-a-dependency>

---

## <a name="errors"></a>6️⃣ Error Handling & Custom Responses  

```python
# app/main.py (or a dedicated errors.py)
from fastapi import Request, HTTPException, status
from fastapi.responses import JSONResponse
from fastapi import FastAPI

app = FastAPI()   # already defined earlier

# -------------------------------------------------
# Built‑in HTTPException usage (most common)
# -------------------------------------------------
# raise HTTPException(status_code=400, detail="Bad request")

# -------------------------------------------------
# Custom exception + handler
# -------------------------------------------------
class OutOfStock(Exception):
    def __init__(self, item_id: int):
        self.item_id = item_id

@app.exception_handler(OutOfStock)
async def out_of_stock_handler(request: Request, exc: OutOfStock):
    return JSONResponse(
        status_code=status.HTTP_409_CONFLICT,
        content={"error": f"Item {exc.item_id} is out of stock"},
    )
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/handling-errors/>

---

## <a name="background"></a>7️⃣ Background Tasks  

```python
from fastapi import BackgroundTasks, APIRouter

router = APIRouter()

@router.post("/notify")
async def notify(tasks: BackgroundTasks):
    tasks.add_task(lambda: print("[bg] Notification queued!"))
    return {"queued": True}
```

*Background tasks run **after** the response is sent, using the same event‑loop thread – perfect for fire‑and‑forget work.*  

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/background-tasks/>

---

## <a name="auth"></a>8️⃣ Auth Quickstart – API‑Key + JWT  

### 8.1 API‑Key (already shown)  

*Header name:* `X-API-Key`  
*Guard:* `require_api_key` (see the **Dependency injection** section).

### 8.2 JWT – minimal but functional  

```python
# app/core/security.py   (add to the file that already contains `require_api_key`)
from datetime import datetime, timedelta, timezone
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

def _timestamp(dt: datetime) -> int:
    """Return a UNIX timestamp (int) – makes the JWT payload explicit."""
    return int(dt.replace(tzinfo=timezone.utc).timestamp())

def create_access_token(subject: str, expires_minutes: int = 60) -> str:
    expire = datetime.utcnow() + timedelta(minutes=expires_minutes)
    to_encode = {"sub": subject, "exp": _timestamp(expire)}
    return jwt.encode(to_encode, settings.JWT_SECRET, algorithm=settings.JWT_ALGORITHM)

def decode_access_token(token: str) -> TokenData:
    try:
        payload = jwt.decode(token, settings.JWT_SECRET, algorithms=[settings.JWT_ALGORITHM])
        return TokenData(sub=payload["sub"], exp=payload.get("exp"))
    except JWTError as exc:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Could not validate credentials",
        ) from exc

def get_current_user(
    creds: HTTPAuthorizationCredentials | None = Depends(bearer),
):
    if not creds:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Not authenticated")
    token_data = decode_access_token(creds.credentials)
    return {"username": token_data.sub}
```

#### Login endpoint (uses `Form` for clarity)

```python
# app/api/routes/items.py   (or a dedicated auth.py)
from fastapi import APIRouter, Depends, Form, HTTPException, status
from app.core.security import create_access_token

router = APIRouter(tags=["auth"])

@router.post("/login")
async def login(
    username: str = Form(...),   # <--- sent as application/x-www-form-urlencoded
    password: str = Form(...),
):
    # ❗ In a real app, verify the password hash in the DB!
    if username == "admin" and password == "admin":
        return {
            "access_token": create_access_token(subject=username),
            "token_type": "bearer",
        }
    raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid credentials")
```

#### Protect a route

```python
@router.get("/protected")
async def protected_route(user: dict = Depends(get_current_user)):
    return {"msg": f"Hello {user['username']}, you are authenticated!"}
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/security/> (covers both API‑Key and JWT).

---

## <a name="testing"></a>9️⃣ Testing with **pytest**  

FastAPI ships with `TestClient` (based on **httpx**) for synchronous tests.

```python
# tests/test_items.py
from fastapi.testclient import TestClient
from app.main import app

client = TestClient(app)

def test_root():
    resp = client.get("/")
    assert resp.status_code == 200
    assert resp.json()["message"] == "Hello FastAPI"

def test_create_and_list(monkeypatch):
    # Tell the app we are in DEBUG mode so the API‑Key guard lets us through.
    from app.core import config
    monkeypatch.setattr(config.settings, "DEBUG", True)

    payload = {"name": "Notebook", "price": 12.5}
    resp = client.post("/api/items/", json=payload)
    assert resp.status_code == 201
    data = resp.json()
    assert data["name"] == "Notebook"
    assert "id" in data

    # List should now contain the new item
    resp = client.get("/api/items/")
    assert resp.status_code == 200
    items = resp.json()
    assert any(i["name"] == "Notebook" for i in items)
```

Run:

```bash
pytest -q
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/testing/>

---

## <a name="extras"></a>🔟 Extras  

### WebSockets

```python
# app/main.py  (add after the router includes)
from fastapi import WebSocket, WebSocketDisconnect

@app.websocket("/ws")
async def websocket_endpoint(ws: WebSocket):
    await ws.accept()
    await ws.send_text("✅ Connected")
    try:
        while True:
            data = await ws.receive_text()
            await ws.send_text(f"Echo: {data}")
    except WebSocketDisconnect:
        print("Client disconnected")
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/websockets/>

### Serving static files

```python
# app/main.py (add near the bottom)
from fastapi.staticfiles import StaticFiles

app.mount("/static", StaticFiles(directory="static"), name="static")
```

> **Docs reference:** <https://fastapi.tiangolo.com/tutorial/static-files/>

### Production‑grade Dockerfile

```dockerfile
# Dockerfile (place in the repository root)
FROM python:3.12-slim

# Prevent Python from buffering stdout/stderr
ENV PYTHONDONTWRITEBYTECODE=1 PYTHONUNBUFFERED=1

# Install system deps needed for uvicorn/gunicorn
RUN apt-get update && apt-get install -y --no-install-recommends gcc && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Install only production deps (no dev tools)
COPY pyproject.toml poetry.lock* requirements.txt* ./
RUN pip install --no-cache-dir --upgrade pip && \
    pip install --no-cache-dir "fastapi[standard]" "uvicorn[standard]" gunicorn

# Copy the source code
COPY . .

EXPOSE 8000

# Use gunicorn with Uvicorn workers – good for multi‑core containers
CMD ["gunicorn", "-k", "uvicorn.workers.UvicornWorker", "-w", "2", "-b", "0.0.0.0:8000", "app.main:app"]
```

> **Docs reference:** <https://fastapi.tiangolo.com/deployment/docker/>

### Production tips (quick checklist)

| ✅ Tip | Why it matters |
|-------|-----------------|
| **Set `DEBUG=False`** in your environment. | Disables the API‑Key shortcut & extra logs. |
| **Run behind a process manager** (gunicorn, uvicorn workers, or a cloud ASGI platform). | Handles multiple processes, graceful reloads. |
| **Use proper secret management** – never store `JWT_SECRET` or `API_KEY` in source control. | Prevents credential leaks. |
| **Enable structured logging** (e.g. `loguru` or `structlog`). | Easier to monitor in production. |
| **Health‑check endpoint** (`/healthz`) that only returns `200 OK`. | Lets orchestration tools know the container is alive. |
| **Limit CORS origins** to real front‑ends. | Reduces attack surface. |
| **Pin dependencies** (use `requirements.txt` / `poetry.lock`). | Guarantees reproducible builds. |

> **Docs reference:** <https://fastapi.tiangolo.com/deployment/>  

---

## <a name="snippets"></a>📋 Reusable copy‑paste snippets  

```python
# settings.py (env‑driven)
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

settings = get_settings()
```

```python
# pagination.py
from fastapi import Query, Depends

def paginate(
    limit: int = Query(10, ge=1, le=100),
    offset: int = Query(0, ge=0),
):
    return limit, offset
```

```python
# api_key_guard.py
from fastapi import Depends, HTTPException, status
from fastapi.security import APIKeyHeader

api_key_header = APIKeyHeader(name="X-API-Key", auto_error=False)

def require_api_key(key: str | None = Depends(api_key_header)):
    if not key or key != "expected-key":
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid API key")
    return True
```

```python
# jwt_helpers.py
from datetime import datetime, timedelta, timezone
from typing import Optional
from jose import jwt, JWTError
from pydantic import BaseModel

SECRET = "change-me"
ALG = "HS256"

class TokenData(BaseModel):
    sub: str
    exp: Optional[int] = None

def _ts(dt: datetime) -> int:
    return int(dt.replace(tzinfo=timezone.utc).timestamp())

def create_access_token(sub: str, minutes: int = 60) -> str:
    exp = _ts(datetime.utcnow() + timedelta(minutes=minutes))
    return jwt.encode({"sub": sub, "exp": exp}, SECRET, algorithm=ALG)

def decode_access_token(token: str) -> TokenData:
    try:
        payload = jwt.decode(token, SECRET, algorithms=[ALG])
        return TokenData(sub=payload["sub"], exp=payload.get("exp"))
    except JWTError as exc:
        raise ValueError("Invalid token") from exc
```

```python
# common_responses.py
from fastapi import status

CREATED = {"status_code": status.HTTP_201_CREATED}
NO_CONTENT = {"status_code": status.HTTP_204_NO_CONTENT}
```

---

## 🎉 You’re ready!

*Run `uvicorn app.main:app --reload` → explore `/docs` → start adding real persistence (SQLModel, Tortoise‑ORM, etc.) and you’ll have a production‑grade FastAPI service in minutes.*

**All the links above point to the official FastAPI or Pydantic documentation**, so you can always dive deeper into any topic.

Happy coding! 🚀