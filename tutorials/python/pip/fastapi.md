# Complete FastAPI Tutorial: From Zero to Hero

This comprehensive guide will teach you everything you need to know about FastAPI, including installation, basic concepts, advanced features, and best practices.

## Table of Contents
1. [Installation and Setup](#installation-and-setup)
2. [Basic FastAPI Concepts](#basic-fastapi-concepts)
3. [Routing and HTTP Methods](#routing-and-http-methods)
4. [Request Handling with Pydantic Models](#request-handling-with-pydantic-models)
5. [Database Integration with SQLAlchemy](#database-integration-with-sqlalchemy)
6. [Authentication and Security](#authentication-and-security)
7. [Error Handling and Validation](#error-handling-and-validation)
8. [Advanced Features](#advanced-features)
9. [Testing FastAPI Applications](#testing-fastapi-applications)
10. [Testing Improvements and Bug Fixes](#testing-improvements-and-bug-fixes)

## Installation and Setup

First, let's set up your development environment:

```python
# requirements.txt
fastapi==0.104.1
uvicorn[standard]==0.24.0
sqlalchemy==2.0.23
pydantic==2.5.0
python-jose==1.7.0
passlib==1.7.4
python-multipart==0.0.6
```

```bash
# Install dependencies using pip
pip install -r requirements.txt

# Or install individually:
pip install fastapi uvicorn sqlalchemy pydantic python-jose passlib python-multipart
```

## Basic FastAPI Concepts

Let's start with the simplest FastAPI application:

```python
from fastapi import FastAPI, HTTPException, status
from typing import Optional, List
import uvicorn

# Create a FastAPI instance
app = FastAPI(
    title="My First API",
    description="A simple API using FastAPI",
    version="1.0.0"
)

# Basic route with GET method
@app.get("/")
async def root():
    """
    Root endpoint that returns a welcome message.
    
    Returns:
        dict: A JSON response with a welcome message
    """
    return {"message": "Welcome to FastAPI!"}

# Route with path parameter
@app.get("/items/{item_id}")
async def read_item(item_id: int, q: Optional[str] = None):
    """
    Read an item by ID.
    
    Args:
        item_id (int): The ID of the item to retrieve
        q (Optional[str]): Optional query parameter
        
    Returns:
        dict: Item data with its ID and optional query string
    """
    return {"item_id": item_id, "q": q}

# Route with multiple path parameters
@app.get("/users/{user_id}/items/{item_id}")
async def read_user_item(user_id: int, item_id: int):
    """
    Read an item for a specific user.
    
    Args:
        user_id (int): The ID of the user
        item_id (int): The ID of the item
        
    Returns:
        dict: User and item IDs
    """
    return {"user_id": user_id, "item_id": item_id}

# Run the application
if __name__ == "__main__":
    # Start the server with uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8000)
```

## Routing and HTTP Methods

FastAPI supports all standard HTTP methods:

```python
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional, List

app = FastAPI()

# Pydantic model for request/response data
class Item(BaseModel):
    name: str
    description: Optional[str] = None
    price: float
    tax: Optional[float] = None

class ItemUpdate(Item):
    id: int  # Additional field for update operations

# GET - Retrieve all items
@app.get("/items", response_model=List[Item])
async def get_items():
    """
    Get all available items.
    
    Returns:
        List[Item]: List of all items
    """
    return [
        Item(name="Laptop", description="High-performance laptop", price=999.99, tax=8.5),
        Item(name="Mouse", description="Wireless mouse", price=29.99, tax=2.5)
    ]

# GET - Retrieve a single item by ID
@app.get("/items/{item_id}", response_model=Item)
async def get_item(item_id: int):
    """
    Get a specific item by its ID.
    
    Args:
        item_id (int): The ID of the item to retrieve
        
    Returns:
        Item: The requested item
        
    Raises:
        HTTPException: If item not found
    """
    # In a real app, this would query a database
    items = [
        {"id": 1, "name": "Laptop", "description": "High-performance laptop", "price": 999.99, "tax": 8.5},
        {"id": 2, "name": "Mouse", "description": "Wireless mouse", "price": 29.99, "tax": 2.5}
    ]
    
    for item in items:
        if item["id"] == item_id:
            return Item(**item)
    
    raise HTTPException(
        status_code=404,
        detail=f"Item with ID {item_id} not found"
    )

# POST - Create a new item
@app.post("/items", response_model=Item)
async def create_item(item: Item):
    """
    Create a new item.
    
    Args:
        item (Item): The item data to create
        
    Returns:
        Item: The created item with generated ID
    """
    # In a real app, this would save to database and return the created record
    # For demo purposes, we'll just return what was sent plus some default values
    
    return Item(
        name=item.name,
        description=item.description,
        price=item.price,
        tax=item.tax
    )

# PUT - Update an existing item
@app.put("/items/{item_id}", response_model=Item)
async def update_item(item_id: int, updated_item: ItemUpdate):
    """
    Update an existing item.
    
    Args:
        item_id (int): The ID of the item to update
        updated_item (ItemUpdate): The updated item data
        
    Returns:
        Item: The updated item
    """
    # In a real app, this would update in database
    return Item(
        name=updated_item.name,
        description=updated_item.description,
        price=updated_item.price,
        tax=updated_item.tax
    )

# DELETE - Delete an item
@app.delete("/items/{item_id}")
async def delete_item(item_id: int):
    """
    Delete an item by ID.
    
    Args:
        item_id (int): The ID of the item to delete
        
    Returns:
        dict: Success message
    """
    # In a real app, this would remove from database
    return {"message": f"Item {item_id} deleted successfully"}
```

## Request Handling with Pydantic Models

Pydantic models are essential for data validation and serialization:

```python
from fastapi import FastAPI, Query, Path, Body
from pydantic import BaseModel, Field, validator
from typing import Optional, List

app = FastAPI()

# Complex Pydantic model with validation
class User(BaseModel):
    id: int = Field(..., ge=1, description="User ID (must be positive)")
    username: str = Field(..., min_length=3, max_length=50)
    email: str = Field(..., description="User's email address")
    full_name: Optional[str] = Field(None, min_length=2)
    
    # Custom validator for email format
    @validator('email')
    def validate_email(cls, v):
        if '@' not in v:
            raise ValueError('Invalid email format')
        return v
    
    class Config:
        schema_extra = {
            "example": {
                "id": 1,
                "username": "john_doe",
                "email": "john@example.com",
                "full_name": "John Doe"
            }
        }

# Model for creating a user (without ID)
class UserCreate(BaseModel):
    username: str = Field(..., min_length=3, max_length=50)
    email: str
    full_name: Optional[str] = None

# Response model with additional fields
class UserResponse(User):
    created_at: str = "2023-01-01T00:00:00Z"
    
    class Config:
        orm_mode = True  # Enable ORM mode for database integration

# Query parameters with validation and defaults
@app.get("/users", response_model=List[User])
async def get_users(
    skip: int = Query(0, ge=0, description="Number of users to skip"),
    limit: int = Query(100, le=1000, description="Maximum number of users to return")
):
    """
    Get a list of users with pagination.
    
    Args:
        skip (int): Number of records to skip
        limit (int): Maximum number of records to return
        
    Returns:
        List[User]: Paginated list of users
    """
    # This would typically query a database
    return [
        User(id=1, username="john", email="john@example.com", full_name="John Doe"),
        User(id=2, username="jane", email="jane@example.com", full_name="Jane Smith")
    ]

# Path parameter validation with constraints
@app.get("/users/{user_id}", response_model=User)
async def get_user(
    user_id: int = Path(..., ge=1, description="User ID (must be positive)")
):
    """
    Get a specific user by ID.
    
    Args:
        user_id (int): The ID of the user to retrieve
        
    Returns:
        User: The requested user
    """
    return User(
        id=user_id,
        username=f"user_{user_id}",
        email=f"user{user_id}@example.com",
        full_name=f"User {user_id}"
    )

# Body parameter with validation
@app.post("/users", response_model=UserResponse)
async def create_user(user: UserCreate = Body(..., embed=True)):
    """
    Create a new user.
    
    Args:
        user (UserCreate): User data to create
        
    Returns:
        UserResponse: Created user information
    """
    # In real app, save to database and return created record
    return UserResponse(
        id=123,  # This would be the actual ID from DB
        username=user.username,
        email=user.email,
        full_name=user.full_name,
        created_at="2023-01-01T00:00:00Z"
    )

# Multiple body parameters with validation
@app.put("/users/{user_id}/profile")
async def update_user_profile(
    user_id: int = Path(..., ge=1),
    full_name: str = Body(..., min_length=2),
    bio: Optional[str] = Body(None)
):
    """
    Update a user's profile information.
    
    Args:
        user_id (int): The ID of the user
        full_name (str): User's full name
        bio (Optional[str]): User's biography
        
    Returns:
        dict: Success message with updated data
    """
    return {
        "message": f"Profile for user {user_id} updated successfully",
        "full_name": full_name,
        "bio": bio
    }
```

## Database Integration with SQLAlchemy

Here's how to integrate FastAPI with a database:

```python
from fastapi import FastAPI, Depends, HTTPException, status
from sqlalchemy import create_engine, Column, Integer, String, Float
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, Session
from pydantic import BaseModel
from typing import List

# Database setup
DATABASE_URL = "sqlite:///./test.db"  # Using SQLite for demo purposes

engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

app = FastAPI()

# Database model
class ItemModel(Base):
    __tablename__ = "items"
    
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(50), index=True)
    description = Column(String(200))
    price = Column(Float)
    tax = Column(Float)

# Create tables
Base.metadata.create_all(bind=engine)

# Pydantic models for API
class ItemBase(BaseModel):
    name: str
    description: str
    price: float
    tax: float

class ItemCreate(ItemBase):
    pass

class Item(ItemBase):
    id: int
    
    class Config:
        orm_mode = True

# Dependency to get database session
def get_db():
    """
    Database session dependency.
    
    Yields:
        Session: SQLAlchemy database session
    """
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# API routes with database integration
@app.post("/items/", response_model=Item)
async def create_item(item: ItemCreate, db: Session = Depends(get_db)):
    """
    Create a new item in the database.
    
    Args:
        item (ItemCreate): The item data to create
        db (Session): Database session
        
    Returns:
        Item: Created item with ID
    """
    db_item = ItemModel(**item.dict())
    db.add(db_item)
    db.commit()
    db.refresh(db_item)
    return db_item

@app.get("/items/", response_model=List[Item])
async def read_items(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    """
    Get all items from the database.
    
    Args:
        skip (int): Number of records to skip
        limit (int): Maximum number of records to return
        db (Session): Database session
        
    Returns:
        List[Item]: List of items
    """
    items = db.query(ItemModel).offset(skip).limit(limit).all()
    return items

@app.get("/items/{item_id}", response_model=Item)
async def read_item(item_id: int, db: Session = Depends(get_db)):
    """
    Get a specific item by ID.
    
    Args:
        item_id (int): The ID of the item to retrieve
        db (Session): Database session
        
    Returns:
        Item: The requested item
        
    Raises:
        HTTPException: If item not found
    """
    db_item = db.query(ItemModel).filter(ItemModel.id == item_id).first()
    if not db_item:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Item with ID {item_id} not found"
        )
    return db_item

@app.put("/items/{item_id}", response_model=Item)
async def update_item(item_id: int, item: ItemCreate, db: Session = Depends(get_db)):
    """
    Update an existing item.
    
    Args:
        item_id (int): The ID of the item to update
        item (ItemCreate): Updated item data
        db (Session): Database session
        
    Returns:
        Item: Updated item
        
    Raises:
        HTTPException: If item not found
    """
    db_item = db.query(ItemModel).filter(ItemModel.id == item_id).first()
    if not db_item:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Item with ID {item_id} not found"
        )
    
    # Update the item
    for key, value in item.dict().items():
        setattr(db_item, key, value)
    
    db.commit()
    db.refresh(db_item)
    return db_item

@app.delete("/items/{item_id}")
async def delete_item(item_id: int, db: Session = Depends(get_db)):
    """
    Delete an item by ID.
    
    Args:
        item_id (int): The ID of the item to delete
        db (Session): Database session
        
    Returns:
        dict: Success message
        
    Raises:
        HTTPException: If item not found
    """
    db_item = db.query(ItemModel).filter(ItemModel.id == item_id).first()
    if not db_item:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Item with ID {item_id} not found"
        )
    
    db.delete(db_item)
    db.commit()
    return {"message": f"Item {item_id} deleted successfully"}
```

## Authentication and Security

Let's implement JWT authentication:

```python
from datetime import datetime, timedelta
from typing import Optional
from fastapi import FastAPI, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from jose import JWTError, jwt
from passlib.context import CryptContext
from pydantic import BaseModel

# Security configuration
SECRET_KEY = "your-secret-key-here"  # In production, use environment variables
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30

app = FastAPI()

# Password hashing
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")

# Pydantic models
class User(BaseModel):
    username: str
    email: Optional[str] = None
    full_name: Optional[str] = None
    disabled: Optional[bool] = None

class UserInDB(User):
    hashed_password: str

class Token(BaseModel):
    access_token: str
    token_type: str

class TokenData(BaseModel):
    username: Optional[str] = None

# Mock database (in production, use a real database)
fake_users_db = {
    "johndoe": {
        "username": "johndoe",
        "full_name": "John Doe",
        "email": "john@example.com",
        "hashed_password": "$2b$12$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW",  # 'secret'
        "disabled": False,
    }
}

def verify_password(plain_password, hashed_password):
    """Verify a plain password against a hashed password."""
    return pwd_context.verify(plain_password, hashed_password)

def get_password_hash(password):
    """Hash a password."""
    return pwd_context.hash(password)

def get_user(db, username: str):
    """Get user from database."""
    if username in db:
        user_dict = db[username]
        return UserInDB(**user_dict)

def authenticate_user(db, username: str, password: str):
    """Authenticate a user."""
    user = get_user(db, username)
    if not user:
        return False
    if not verify_password(password, user.hashed_password):
        return False
    return user

def create_access_token(data: dict, expires_delta: Optional[timedelta] = None):
    """Create an access token."""
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.utcnow() + expires_delta
    else:
        expire = datetime.utcnow() + timedelta(minutes=15)
    
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt

# Authentication endpoints
@app.post("/token", response_model=Token)
async def login_for_access_token(form_data: OAuth2PasswordRequestForm = Depends()):
    """
    Login endpoint to get access token.
    
    Args:
        form_data (OAuth2PasswordRequestForm): Login credentials
        
    Returns:
        Token: Access token and token type
    """
    user = authenticate_user(fake_users_db, form_data.username, form_data.password)
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect username or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        data={"sub": user.username}, expires_delta=access_token_expires
    )
    
    return {"access_token": access_token, "token_type": "bearer"}

# Protected endpoint example
@app.get("/users/me")
async def read_users_me(current_user: User = Depends(get_current_active_user)):
    """
    Get current user information.
    
    Args:
        current_user (User): Currently authenticated user
        
    Returns:
        User: Current user's information
    """
    return current_user

# Dependency to get current active user
def get_current_user(token: str = Depends(oauth2_scheme)):
    """Get the currently logged-in user from token."""
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username: str = payload.get("sub")
        if username is None:
            raise credentials_exception
        token_data = TokenData(username=username)
    except JWTError:
        raise credentials_exception
    
    user = get_user(fake_users_db, username=token_data.username)
    if user is None:
        raise credentials_exception
    return user

def get_current_active_user(current_user: User = Depends(get_current_user)):
    """Get the currently active logged-in user."""
    if current_user.disabled:
        raise HTTPException(status_code=400, detail="Inactive user")
    return current_user
```

## Error Handling and Validation

FastAPI provides excellent error handling capabilities:

```python
from fastapi import FastAPI, HTTPException, Request, status
from fastapi.exceptions import RequestValidationError
from fastapi.responses import JSONResponse
from pydantic import BaseModel, ValidationError
import logging

app = FastAPI()

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Custom error handlers
@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    """Handle request validation errors."""
    logger.error(f"Validation error: {exc}")
    
    return JSONResponse(
        status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
        content={
            "message": "Validation failed",
            "details": str(exc)
        }
    )

@app.exception_handler(HTTPException)
async def http_exception_handler(request: Request, exc: HTTPException):
    """Handle HTTP exceptions."""
    logger.error(f"HTTP error {exc.status_code}: {exc.detail}")
    
    return JSONResponse(
        status_code=exc.status_code,
        content={
            "message": exc.detail
        }
    )

# Example models with validation
class Product(BaseModel):
    id: int = None  # ID can be optional for creation
    name: str = ""
    description: str = ""
    price: float = 0.0
    
    class Config:
        schema_extra = {
            "example": {
                "name": "Laptop",
                "description": "High-performance laptop",
                "price": 999.99
            }
        }

# Custom validation example
class ProductCreate(Product):
    # Validate that name is not empty and price is positive
    @classmethod
    def validate_name(cls, value: str) -> str:
        if not value or len(value.strip()) == 0:
            raise ValueError("Name cannot be empty")
        return value
    
    @classmethod
    def validate_price(cls, value: float) -> float:
        if value < 0:
            raise ValueError("Price must be positive")
        return value

# Routes with proper error handling
@app.get("/products/{product_id}")
async def get_product(product_id: int):
    """
    Get a product by ID.
    
    Args:
        product_id (int): Product ID to retrieve
        
    Returns:
        dict: Product information or appropriate error
        
    Raises:
        HTTPException: If product not found
    """
    # Simulate database lookup
    if product_id < 1:
        raise HTTPException(
            status_code=400,
            detail="Product ID must be positive"
        )
    
    if product_id > 1000:
        raise HTTPException(
            status_code=404,
            detail="Product not found"
        )
    
    return {
        "id": product_id,
        "name": f"Product {product_id}",
        "description": f"This is product number {product_id}",
        "price": 99.99
    }

@app.post("/products")
async def create_product(product: ProductCreate):
    """
    Create a new product.
    
    Args:
        product (ProductCreate): Product data to create
        
    Returns:
        dict: Created product information
        
    Raises:
        HTTPException: If validation fails
    """
    try:
        # Validate the model
        validated_product = ProductCreate(**product.dict())
        
        # Simulate saving to database
        return {
            "id": 123,  # This would be actual ID from DB
            "name": validated_product.name,
            "description": validated_product.description,
            "price": validated_product.price
        }
    except ValidationError as e:
        raise HTTPException(
            status_code=400,
            detail=f"Validation error: {str(e)}"
        )

@app.put("/products/{product_id}")
async def update_product(product_id: int, product: Product):
    """
    Update a product.
    
    Args:
        product_id (int): ID of the product to update
        product (Product): Updated product data
        
    Returns:
        dict: Updated product information
        
    Raises:
        HTTPException: If validation fails or product not found
    """
    # Validate that we're updating an existing product
    if product_id < 1:
        raise HTTPException(
            status_code=400,
            detail="Product ID must be positive"
        )
    
    return {
        "id": product_id,
        "name": product.name,
        "description": product.description,
        "price": product.price
    }

# Custom error for business logic
@app.get("/health")
async def health_check():
    """
    Health check endpoint.
    
    Returns:
        dict: Health status information
        
    Raises:
        HTTPException: If service is unhealthy
    """
    try:
        # Simulate some operation that might fail
        import random
        if random.random() < 0.1:  # 10% chance of failure
            raise Exception("Database connection failed")
        
        return {"status": "healthy", "timestamp": datetime.now().isoformat()}
    except Exception as e:
        logger.error(f"Health check failed: {str(e)}")
        raise HTTPException(
            status_code=503,
            detail="Service unavailable"
        )
```

## Advanced Features

Here are some advanced FastAPI features:

```python
from fastapi import FastAPI, Depends, BackgroundTasks, Header, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import HTMLResponse
import asyncio
import time

app = FastAPI(
    title="Advanced FastAPI Features",
    description="Demonstrating advanced FastAPI capabilities"
)

# CORS middleware configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, specify exact origins
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Dependency injection with multiple dependencies
async def get_header_value(x_token: str = Header(...)):
    """Get X-Token header value."""
    return x_token

async def get_query_param(q: str = None):
    """Get query parameter q."""
    return q

@app.get("/dependencies")
async def dependency_test(
    token: str = Depends(get_header_value),
    query_param: str = Depends(get_query_param)
):
    """
    Test multiple dependencies.
    
    Args:
        token (str): X-Token header value
        query_param (str): Query parameter
        
    Returns:
        dict: Dependencies information
    """
    return {
        "token": token,
        "query": query_param,
        "timestamp": time.time()
    }

# Background tasks example
def send_email(email: str, subject: str, body: str):
    """Simulate sending an email."""
    print(f"Sending email to {email}: {subject}")
    time.sleep(2)  # Simulate network delay
    print("Email sent successfully")

@app.post("/send-email")
async def send_email_endpoint(
    background_tasks: BackgroundTasks,
    email: str,
    subject: str = "Default Subject",
    body: str = ""
):
    """
    Send email in background.
    
    Args:
        background_tasks (BackgroundTasks): FastAPI background tasks
        email (str): Recipient email address
        subject (str): Email subject
        body (str): Email body
        
    Returns:
        dict: Status message
    """
    background_tasks.add_task(send_email, email, subject, body)
    return {"message": "Email will be sent in background"}

# Request/response model for custom responses
@app.get("/custom-response", response_class=HTMLResponse)
async def custom_html_response():
    """Return custom HTML response."""
    html_content = """
    <html>
        <head>
            <title>FastAPI Custom Response</title>
        </head>
        <body>
            <h1>Welcome to FastAPI!</h1>
            <p>This is a custom HTML response.</p>
        </body>
    </html>
    """
    return html_content

# Dependency that can be reused
def get_db_connection():
    """Simulate database connection."""
    print("Connecting to database...")
    time.sleep(0.5)  # Simulate connection delay
    return {"status": "connected"}

@app.get("/database")
async def access_database(db = Depends(get_db_connection)):
    """
    Test database dependency.
    
    Args:
        db (dict): Database connection information
        
    Returns:
        dict: Database status
    """
    return {"message": f"Database {db['status']}"}

# API versioning example
@app.get("/api/v1/users")
async def get_users_v1():
    """Get users - v1 endpoint."""
    return {"version": "v1", "users": ["user1", "user2"]}

@app.get("/api/v2/users")
async def get_users_v2():
    """Get users - v2 endpoint."""
    return {"version": "v2", "users": ["user1", "user2", "user3"]}

# Rate limiting example (simplified)
from collections import defaultdict
import time

rate_limits = defaultdict(list)

@app.get("/limited")
async def limited_endpoint():
    """
    Endpoint with rate limiting.
    
    Returns:
        dict: Response with ratelimit info
    """
    current_time = time.time()
    
    # Remove old requests (older than 60 seconds)
    rate_limits["endpoint"].append(current_time)
    rate_limits["endpoint"] = [t for t in rate_limits["endpoint"] if current_time - t < 60]
    
    request_count = len(rate_limits["endpoint"])
    
    if request_count > 10:
        raise HTTPException(
            status_code=429,
            detail="Too many requests. Rate limit exceeded."
        )
    
    return {
        "message": f"Request successful",
        "requests_in_last_minute": request_count
    }

# Async operations example
@app.get("/async-example")
async def async_example():
    """
    Example of asynchronous operation.
    
    Returns:
        dict: Results from async operations
    """
    # Simulate multiple async calls
    tasks = [
        asyncio.sleep(0.1),
        asyncio.sleep(0.2),
        asyncio.sleep(0.3)
    ]
    
    await asyncio.gather(*tasks)
    
    return {
        "message": "All async operations completed",
        "timestamp": time.time()
    }
```

## Testing FastAPI Applications

Here's how to test your FastAPI applications:

```python
# test_main.py - Unit tests for the API

import pytest
from fastapi.testclient import TestClient
from main import app  # Import your FastAPI app

client = TestClient(app)

def test_read_root():
    """Test root endpoint."""
    response = client.get("/")
    assert response.status_code == 200
    assert response.json() == {"message": "Welcome to FastAPI!"}

def test_get_items():
    """Test getting all items."""
    response = client.get("/items")
    assert response.status_code == 200
    data = response.json()
    assert isinstance(data, list)

def test_create_item():
    """Test creating a new item."""
    item_data = {
        "name": "Test Item",
        "description": "A test item for API testing",
        "price": 99.99,
        "tax": 8.5
    }
    
    response = client.post("/items", json=item_data)
    assert response.status_code == 200
    data = response.json()
    assert data["name"] == "Test Item"
    assert data["price"] == 99.99

def test_get_single_item():
    """Test getting a specific item."""
    response = client.get("/items/1")
    assert response.status_code == 200
    data = response.json()
    assert "id" in data

def test_update_item():
    """Test updating an item."""
    update_data = {
        "name": "Updated Item",
        "price": 149.99
    }
    
    response = client.put("/items/1", json=update_data)
    assert response.status_code == 200
    data = response.json()
    assert data["name"] == "Updated Item"

def test_delete_item():
    """Test deleting an item."""
    response = client.delete("/items/1")
    assert response.status_code == 200

def test_authentication():
    """Test authentication endpoints."""
    login_data = {
        "username": "testuser",
        "password": "testpass"
    }
    
    # Test login
    response = client.post("/auth/login", data=login_data)
    assert response.status_code == 200
    token = response.json()["access_token"]
    
    # Test protected endpoint
    headers = {"Authorization": f"Bearer {token}"}
    response = client.get("/protected", headers=headers)
    assert response.status_code == 200

def test_validation_errors():
    """Test input validation."""
    # Test with invalid data
    invalid_item = {
        "name": "",  # Empty name should fail validation
        "price": -10  # Negative price should fail
    }
    
    response = client.post("/items", json=invalid_item)
    assert response.status_code == 422  # Validation error
    
    # Check error details
    error_data = response.json()
    assert "detail" in error_data

# Database testing with fixtures
@pytest.fixture
def test_db():
    """Create a test database."""
    from database import Base, engine, SessionLocal
    
    # Create test tables
    Base.metadata.create_all(bind=engine)
    
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
        # Clean up test data
        Base.metadata.drop_all(bind=engine)

def test_database_operations(test_db):
    """Test database CRUD operations."""
    from models import Item
    
    # Create test item
    test_item = Item(name="DB Test Item", price=29.99)
    test_db.add(test_item)
    test_db.commit()
    test_db.refresh(test_item)
    
    # Test item was created
    assert test_item.id is not None
    assert test_item.name == "DB Test Item"
    
    # Test querying
    queried_item = test_db.query(Item).filter(Item.id == test_item.id).first()
    assert queried_item is not None
    assert queried_item.name == "DB Test Item"

# Performance testing
def test_performance():
    """Test API performance."""
    import time
    
    start_time = time.time()
    response = client.get("/items")
    end_time = time.time()
    
    # Response should be fast (under 1 second)
    assert (end_time - start_time) < 1.0
    assert response.status_code == 200

# Run tests with coverage
# pytest --cov=main test_main.py
```

## Testing Improvements and Bug Fixes

During the development of comprehensive FastAPI tests, several common issues and bugs were identified and resolved. Here's a detailed breakdown of the problems encountered and their solutions:

### 🐛 Common Testing Issues and Fixes

#### 1. Multiple File Upload Testing Bug
**Issue:** When testing multiple file uploads, the common pattern using a dictionary with lists doesn't work correctly:

```python
# ❌ INCORRECT - This will fail
files = {
    "files": [
        ("file1.txt", io.BytesIO(b"content1"), "text/plain"),
        ("file2.txt", io.BytesIO(b"content2"), "text/plain")
    ]
}
response = client.post("/uploadfiles/", files=files)
# Results in 400 Bad Request
```

**Solution:** Use a list of tuples with the same key name:

```python
# ✅ CORRECT - This works properly
files = [
    ("files", ("file1.txt", io.BytesIO(b"content1"), "text/plain")),
    ("files", ("file2.txt", io.BytesIO(b"content2"), "text/plain"))
]
response = client.post("/uploadfiles/", files=files)
# Results in 200 OK
```

#### 2. Pydantic Configuration Warning Fix
**Issue:** When using Pydantic v2 with FastAPI, you may encounter warnings about deprecated configuration:

```
UserWarning: Valid config keys have changed in V2:
* 'schema_extra' has been renamed to 'json_schema_extra'
```

**Solution:** Update Pydantic model configurations for v2 compatibility:

```python
# ❌ OLD (Pydantic v1 style)
class Item(BaseModel):
    name: str
    price: float
    
    class Config:
        schema_extra = {
            "example": {
                "name": "Laptop",
                "price": 999.99
            }
        }

# ✅ NEW (Pydantic v2 style)
class Item(BaseModel):
    name: str
    price: float
    
    class Config:
        json_schema_extra = {
            "example": {
                "name": "Laptop",
                "price": 999.99
            }
        }

# ✅ BETTER (Pydantic v2 model_config style)
class Item(BaseModel):
    name: str
    price: float
    
    model_config = {
        "json_schema_extra": {
            "example": {
                "name": "Laptop",
                "price": 999.99
            }
        }
    }
```

#### 3. Dependency Installation Issues
**Issue:** Missing required dependencies can cause test failures, especially for file uploads and advanced features.

**Solution:** Ensure all required packages are installed:

```bash
# Core FastAPI dependencies
pip install fastapi uvicorn

# For file uploads and form data
pip install python-multipart

# For testing
pip install pytest httpx

# For authentication features
pip install python-jose[cryptography] passlib[bcrypt]

# Complete requirements.txt
cat > requirements.txt << EOF
fastapi>=0.68.0
uvicorn>=0.15.0
pydantic>=2.0.0
python-multipart>=0.0.6
pytest>=6.0.0
httpx>=0.24.0
python-jose[cryptography]>=3.3.0
passlib[bcrypt]>=1.7.4
EOF
```

#### 4. TestClient Import and Usage Fixes
**Issue:** Incorrect TestClient usage can lead to test failures or unrealistic test scenarios.

```python
# ❌ INCORRECT - Missing proper imports or setup
from fastapi.testclient import TestClient
client = TestClient(app)
# May fail if app is not properly configured
```

**Solution:** Proper TestClient setup with error handling:

```python
# ✅ CORRECT - Proper TestClient usage
import pytest
from fastapi import FastAPI
from fastapi.testclient import TestClient

def test_api_functionality():
    """Test with proper error handling and setup."""
    try:
        app = FastAPI()
        
        @app.get("/test")
        def test_endpoint():
            return {"message": "test"}
        
        client = TestClient(app)
        response = client.get("/test")
        
        assert response.status_code == 200
        assert response.json() == {"message": "test"}
        
    except ImportError:
        pytest.skip("FastAPI not available")
```

#### 5. Authentication Testing Patterns
**Issue:** Authentication testing often fails due to incorrect header formatting or credential handling.

```python
# ❌ COMMON MISTAKES
# Wrong header format
headers = {"Authorization": "testtoken"}  # Missing "Bearer "

# Wrong auth tuple usage  
response = client.get("/protected", auth="user:pass")  # Should be tuple

# Missing security dependencies
# Not testing both success and failure cases
```

**Solution:** Proper authentication testing patterns:

```python
# ✅ CORRECT PATTERNS

def test_bearer_token_auth():
    """Test Bearer token authentication."""
    # Correct header format
    headers = {"Authorization": "Bearer valid-token-123"}
    response = client.get("/protected", headers=headers)
    assert response.status_code == 200

def test_basic_auth():
    """Test Basic authentication."""
    # Correct auth tuple format
    response = client.get("/protected", auth=("username", "password"))
    assert response.status_code == 200
    
    # Test failure case
    response = client.get("/protected", auth=("wrong", "credentials"))
    assert response.status_code == 401

def test_missing_auth():
    """Test endpoints without authentication."""
    response = client.get("/protected")
    # FastAPI returns 403 for missing Bearer token
    # or 401 for missing Basic auth
    assert response.status_code in [401, 403]
```

### 🧪 Comprehensive Testing Patterns

Based on the bug fixes above, here are the recommended testing patterns:

```python
import pytest
import io
from fastapi import FastAPI, File, UploadFile, Depends, HTTPException, status
from fastapi.testclient import TestClient
from fastapi.security import HTTPBasic, HTTPBearer, HTTPBasicCredentials, HTTPAuthorizationCredentials
from pydantic import BaseModel

class TestFastAPIComprehensive:
    """Comprehensive FastAPI testing with all common patterns."""
    
    def test_complete_crud_operations(self):
        """Test complete CRUD operations with proper error handling."""
        try:
            app = FastAPI()
            items_db = {}
            
            class Item(BaseModel):
                name: str
                price: float
            
            @app.post("/items/")
            def create_item(item: Item):
                item_id = len(items_db) + 1
                items_db[item_id] = item.dict()
                return {"id": item_id, **item.dict()}
            
            @app.get("/items/{item_id}")
            def get_item(item_id: int):
                if item_id not in items_db:
                    raise HTTPException(status_code=404, detail="Item not found")
                return {"id": item_id, **items_db[item_id]}
            
            client = TestClient(app)
            
            # Test CREATE
            item_data = {"name": "Test Item", "price": 29.99}
            response = client.post("/items/", json=item_data)
            assert response.status_code == 200
            created_item = response.json()
            assert created_item["name"] == "Test Item"
            item_id = created_item["id"]
            
            # Test READ
            response = client.get(f"/items/{item_id}")
            assert response.status_code == 200
            assert response.json()["name"] == "Test Item"
            
            # Test 404 error
            response = client.get("/items/999")
            assert response.status_code == 404
            
        except ImportError:
            pytest.skip("FastAPI not available")
    
    def test_file_uploads_correctly(self):
        """Test file uploads with correct patterns."""
        try:
            app = FastAPI()
            
            @app.post("/upload-single/")
            async def upload_single(file: UploadFile = File(...)):
                contents = await file.read()
                return {"filename": file.filename, "size": len(contents)}
            
            @app.post("/upload-multiple/")
            async def upload_multiple(files: List[UploadFile] = File(...)):
                results = []
                for file in files:
                    contents = await file.read()
                    results.append({"filename": file.filename, "size": len(contents)})
                return {"files": results}
            
            client = TestClient(app)
            
            # Single file upload
            files = {"file": ("test.txt", io.BytesIO(b"test content"), "text/plain")}
            response = client.post("/upload-single/", files=files)
            assert response.status_code == 200
            
            # Multiple file upload (CORRECT PATTERN)
            files = [
                ("files", ("file1.txt", io.BytesIO(b"content1"), "text/plain")),
                ("files", ("file2.txt", io.BytesIO(b"content2"), "text/plain"))
            ]
            response = client.post("/upload-multiple/", files=files)
            assert response.status_code == 200
            assert len(response.json()["files"]) == 2
            
        except ImportError:
            pytest.skip("FastAPI not available")
```

### 🔧 Testing Environment Setup

To avoid common setup issues, use this testing environment configuration:

```python
# conftest.py - Pytest configuration
import pytest
from fastapi.testclient import TestClient

@pytest.fixture
def client():
    """Create a test client for FastAPI app."""
    from main import app  # Import your app
    return TestClient(app)

@pytest.fixture(scope="session")
def test_db():
    """Create a test database session."""
    # Setup test database
    # Yield db session
    # Cleanup after tests
    pass

# pytest.ini
[tool:pytest]
testpaths = tests
python_files = test_*.py
python_functions = test_*
python_classes = Test*
addopts = -v --tb=short
```

### ✅ Testing Checklist

Use this checklist to avoid common testing issues:

- [ ] All required dependencies installed (`pip install fastapi uvicorn python-multipart pytest httpx`)
- [ ] TestClient properly imported and configured
- [ ] File upload tests use correct tuple format for multiple files
- [ ] Authentication tests cover both success and failure cases
- [ ] Error cases are tested (404, 401, 422, etc.)
- [ ] Pydantic models use v2-compatible configuration
- [ ] Async endpoints are properly tested with `async def` and `await`
- [ ] Database tests use proper fixtures and cleanup
- [ ] All imports are wrapped in try-except with pytest.skip()

### 📋 Quick Reference - Common Bug Fixes

| Issue | Wrong Way ❌ | Right Way ✅ |
|-------|-------------|-------------|
| Multiple file uploads | `{"files": [("f1.txt", data)]}` | `[("files", ("f1.txt", data))]` |
| Pydantic v2 config | `schema_extra = {...}` | `json_schema_extra = {...}` |
| Bearer token auth | `{"Authorization": "token"}` | `{"Authorization": "Bearer token"}` |
| Basic auth testing | `auth="user:pass"` | `auth=("user", "pass")` |
| Missing dependencies | Skip installation checks | `pip install python-multipart` |

### 🎯 Real-World Testing Examples

For complete working examples of all these bug fixes and patterns, see the comprehensive test suite:
- **test_fastapi_functionality.py** - 16 test methods covering all scenarios
- **fastapi_demo.py** - Working demo application with all features
- **run_fastapi_tests.py** - Automated test runner with dependency checking

These files demonstrate production-ready testing patterns and include all the bug fixes mentioned above.

## Deployment and Production

### Docker Deployment

```dockerfile
# Dockerfile
FROM python:3.11-slim

WORKDIR /app

# Copy requirements first for better caching
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

# Expose port
EXPOSE 8000

# Run the application
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000"]
```

```yaml
# docker-compose.yml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8000:8000"
    environment:
      - DATABASE_URL=postgresql://user:password@db:5432/fastapi_db
      - SECRET_KEY=your-secret-key-here
    depends_on:
      - db
    volumes:
      - ./app:/app
    restart: unless-stopped

  db:
    image: postgres:13
    environment:
      - POSTGRES_USER=user
      - POSTGRES_PASSWORD=password
      - POSTGRES_DB=fastapi_db
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    restart: unless-stopped

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    restart: unless-stopped

volumes:
  postgres_data:
```

### Production Configuration

```python
# config.py - Production configuration
import os
from typing import Optional
from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    # Database
    database_url: str = "sqlite:///./test.db"
    
    # Security
    secret_key: str = "your-secret-key-change-this-in-production"
    algorithm: str = "HS256"
    access_token_expire_minutes: int = 30
    
    # CORS
    allowed_hosts: list[str] = ["localhost", "127.0.0.1"]
    
    # Redis
    redis_url: str = "redis://localhost:6379"
    
    # Logging
    log_level: str = "INFO"
    
    # API Configuration
    api_v1_prefix: str = "/api/v1"
    title: str = "FastAPI Production App"
    description: str = "Production FastAPI Application"
    version: str = "1.0.0"
    
    # Rate limiting
    rate_limit_per_minute: int = 60
    
    # File uploads
    max_file_size: int = 10 * 1024 * 1024  # 10MB
    allowed_file_types: list[str] = ["image/jpeg", "image/png", "application/pdf"]
    
    class Config:
        env_file = ".env"

# Load settings
settings = Settings()

# Production FastAPI app configuration
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.middleware.trustedhost import TrustedHostMiddleware
import logging

# Configure logging
logging.basicConfig(
    level=getattr(logging, settings.log_level),
    format="%(asctime)s - %(name)s - %(levelname)s - %(message)s"
)

# Create production app
app = FastAPI(
    title=settings.title,
    description=settings.description,
    version=settings.version,
    openapi_url=f"{settings.api_v1_prefix}/openapi.json",
    docs_url=f"{settings.api_v1_prefix}/docs",
    redoc_url=f"{settings.api_v1_prefix}/redoc"
)

# Add security middleware
app.add_middleware(
    TrustedHostMiddleware, 
    allowed_hosts=settings.allowed_hosts
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.allowed_hosts,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
```

### Health Checks and Monitoring

```python
# health.py - Health check endpoints
from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from database import get_db
import redis
from config import settings
import time

router = APIRouter()

@router.get("/health")
async def health_check():
    """Basic health check endpoint."""
    return {
        "status": "healthy",
        "timestamp": time.time(),
        "service": "FastAPI Application"
    }

@router.get("/health/detailed")
async def detailed_health_check(db: Session = Depends(get_db)):
    """Detailed health check including dependencies."""
    health_status = {
        "status": "healthy",
        "timestamp": time.time(),
        "checks": {}
    }
    
    # Database check
    try:
        db.execute("SELECT 1")
        health_status["checks"]["database"] = "healthy"
    except Exception as e:
        health_status["checks"]["database"] = f"unhealthy: {str(e)}"
        health_status["status"] = "unhealthy"
    
    # Redis check (if using Redis)
    try:
        r = redis.from_url(settings.redis_url)
        r.ping()
        health_status["checks"]["redis"] = "healthy"
    except Exception as e:
        health_status["checks"]["redis"] = f"unhealthy: {str(e)}"
        health_status["status"] = "unhealthy"
    
    # Memory check
    import psutil
    memory_percent = psutil.virtual_memory().percent
    health_status["checks"]["memory"] = f"{memory_percent:.1f}% used"
    
    if memory_percent > 90:
        health_status["status"] = "degraded"
    
    return health_status

@router.get("/metrics")
async def get_metrics():
    """Application metrics for monitoring."""
    import psutil
    
    return {
        "memory_usage": psutil.virtual_memory().percent,
        "cpu_usage": psutil.cpu_percent(),
        "disk_usage": psutil.disk_usage('/').percent,
        "uptime": time.time() - psutil.boot_time()
    }
```

### Rate Limiting and Security

```python
# rate_limit.py - Rate limiting implementation
from fastapi import Request, HTTPException, status
from fastapi.responses import JSONResponse
import redis
import time
from config import settings

class RateLimiter:
    def __init__(self, redis_url: str = settings.redis_url):
        self.redis_client = redis.from_url(redis_url)
    
    async def __call__(self, request: Request):
        # Get client IP
        client_ip = request.client.host
        
        # Create rate limit key
        current_minute = int(time.time() // 60)
        key = f"rate_limit:{client_ip}:{current_minute}"
        
        try:
            # Get current count
            current = self.redis_client.get(key)
            current = int(current) if current else 0
            
            if current >= settings.rate_limit_per_minute:
                raise HTTPException(
                    status_code=status.HTTP_429_TOO_MANY_REQUESTS,
                    detail="Rate limit exceeded. Try again later."
                )
            
            # Increment counter
            pipe = self.redis_client.pipeline()
            pipe.incr(key)
            pipe.expire(key, 60)  # Expire after 1 minute
            pipe.execute()
            
        except redis.RedisError:
            # If Redis is down, allow the request
            pass

# Security headers middleware
from fastapi import FastAPI
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.responses import Response

class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request, call_next):
        response = await call_next(request)
        
        # Add security headers
        response.headers["X-Content-Type-Options"] = "nosniff"
        response.headers["X-Frame-Options"] = "DENY"
        response.headers["X-XSS-Protection"] = "1; mode=block"
        response.headers["Strict-Transport-Security"] = "max-age=31536000; includeSubDomains"
        response.headers["Content-Security-Policy"] = "default-src 'self'"
        
        return response
```

## Best Practices and Performance Tips

### 1. Database Connection Management

```python
# database.py - Optimized database configuration
from sqlalchemy import create_engine, event
from sqlalchemy.orm import sessionmaker, Session
from sqlalchemy.pool import QueuePool
from config import settings

# Create engine with connection pooling
engine = create_engine(
    settings.database_url,
    poolclass=QueuePool,
    pool_size=20,          # Number of connections to maintain
    max_overflow=30,       # Additional connections when needed
    pool_pre_ping=True,    # Verify connections before use
    pool_recycle=3600,     # Recycle connections every hour
    echo=False             # Set to True for debugging
)

# Add connection event listeners
@event.listens_for(engine, "connect")
def set_sqlite_pragma(dbapi_connection, connection_record):
    """Set SQLite pragmas for better performance."""
    if 'sqlite' in settings.database_url:
        cursor = dbapi_connection.cursor()
        cursor.execute("PRAGMA foreign_keys=ON")
        cursor.execute("PRAGMA journal_mode=WAL")
        cursor.execute("PRAGMA synchronous=NORMAL")
        cursor.execute("PRAGMA cache_size=10000")
        cursor.close()

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Dependency for database sessions
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

### 2. Caching Strategy

```python
# cache.py - Redis caching implementation
import redis
import json
import pickle
from functools import wraps
from typing import Any, Optional
from config import settings

class CacheManager:
    def __init__(self):
        self.redis_client = redis.from_url(settings.redis_url)
    
    def get(self, key: str) -> Optional[Any]:
        """Get value from cache."""
        try:
            data = self.redis_client.get(key)
            return pickle.loads(data) if data else None
        except Exception:
            return None
    
    def set(self, key: str, value: Any, ttl: int = 3600) -> bool:
        """Set value in cache with TTL."""
        try:
            data = pickle.dumps(value)
            return self.redis_client.setex(key, ttl, data)
        except Exception:
            return False
    
    def delete(self, key: str) -> bool:
        """Delete key from cache."""
        try:
            return bool(self.redis_client.delete(key))
        except Exception:
            return False

cache = CacheManager()

def cached(ttl: int = 3600):
    """Decorator for caching function results."""
    def decorator(func):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            # Create cache key from function name and arguments
            cache_key = f"cache:{func.__name__}:{hash(str(args) + str(kwargs))}"
            
            # Try to get from cache
            result = cache.get(cache_key)
            if result is not None:
                return result
            
            # Execute function and cache result
            result = await func(*args, **kwargs)
            cache.set(cache_key, result, ttl)
            
            return result
        return wrapper
    return decorator

# Usage example
@cached(ttl=1800)  # Cache for 30 minutes
async def get_expensive_data(user_id: int):
    # Simulate expensive operation
    await asyncio.sleep(2)
    return {"user_id": user_id, "expensive_data": "result"}
```

### 3. Background Tasks and Job Queues

```python
# tasks.py - Background task processing
from fastapi import BackgroundTasks
from celery import Celery
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from config import settings

# Celery configuration for heavy background tasks
celery_app = Celery(
    "fastapi_tasks",
    broker=settings.redis_url,
    backend=settings.redis_url
)

@celery_app.task
def send_email_task(to_email: str, subject: str, body: str):
    """Send email using Celery task."""
    try:
        # Configure your SMTP settings
        smtp_server = "smtp.gmail.com"
        smtp_port = 587
        smtp_username = "your-email@gmail.com"
        smtp_password = "your-app-password"
        
        msg = MIMEMultipart()
        msg['From'] = smtp_username
        msg['To'] = to_email
        msg['Subject'] = subject
        
        msg.attach(MIMEText(body, 'plain'))
        
        server = smtplib.SMTP(smtp_server, smtp_port)
        server.starttls()
        server.login(smtp_username, smtp_password)
        text = msg.as_string()
        server.sendmail(smtp_username, to_email, text)
        server.quit()
        
        return "Email sent successfully"
    except Exception as e:
        return f"Failed to send email: {str(e)}"

# FastAPI background tasks for lighter operations
def write_log(message: str):
    """Simple background task to write logs."""
    with open("app.log", "a") as log_file:
        log_file.write(f"{time.time()}: {message}\n")

@app.post("/send-notification/")
async def send_notification(
    email: str, 
    message: str, 
    background_tasks: BackgroundTasks
):
    """Send notification with background task."""
    # Add background task
    background_tasks.add_task(write_log, f"Notification sent to {email}")
    
    # For heavy tasks, use Celery
    send_email_task.delay(email, "Notification", message)
    
    return {"message": "Notification queued successfully"}
```

### 4. API Versioning

```python
# versioning.py - API versioning strategy
from fastapi import APIRouter, FastAPI

# Version 1 API
v1_router = APIRouter(prefix="/api/v1")

@v1_router.get("/items")
async def get_items_v1():
    return {"version": "1.0", "items": ["item1", "item2"]}

# Version 2 API with improvements
v2_router = APIRouter(prefix="/api/v2")

@v2_router.get("/items")
async def get_items_v2():
    return {
        "version": "2.0",
        "items": [
            {"id": 1, "name": "item1", "created_at": "2024-01-01"},
            {"id": 2, "name": "item2", "created_at": "2024-01-02"}
        ],
        "total": 2,
        "page": 1
    }

# Include routers in main app
app.include_router(v1_router, tags=["API v1"])
app.include_router(v2_router, tags=["API v2"])
```

### 5. Error Handling and Logging

```python
# error_handling.py - Comprehensive error handling
import logging
import traceback
from fastapi import Request, HTTPException
from fastapi.responses import JSONResponse
from fastapi.exceptions import RequestValidationError
from starlette.exceptions import HTTPException as StarletteHTTPException

# Configure structured logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class APIException(Exception):
    """Custom API exception."""
    def __init__(self, status_code: int, detail: str, error_code: str = None):
        self.status_code = status_code
        self.detail = detail
        self.error_code = error_code

@app.exception_handler(APIException)
async def api_exception_handler(request: Request, exc: APIException):
    """Handle custom API exceptions."""
    logger.error(f"API Exception: {exc.detail}")
    return JSONResponse(
        status_code=exc.status_code,
        content={
            "error": {
                "code": exc.error_code,
                "message": exc.detail,
                "timestamp": time.time()
            }
        }
    )

@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    """Handle validation errors."""
    logger.warning(f"Validation error: {exc.errors()}")
    return JSONResponse(
        status_code=422,
        content={
            "error": {
                "code": "VALIDATION_ERROR",
                "message": "Input validation failed",
                "details": exc.errors()
            }
        }
    )

@app.exception_handler(Exception)
async def general_exception_handler(request: Request, exc: Exception):
    """Handle unexpected exceptions."""
    logger.error(f"Unexpected error: {str(exc)}")
    logger.error(traceback.format_exc())
    
    return JSONResponse(
        status_code=500,
        content={
            "error": {
                "code": "INTERNAL_SERVER_ERROR",
                "message": "An unexpected error occurred"
            }
        }
    )
```

## Summary and Key Takeaways

FastAPI is a powerful, modern web framework that makes building APIs fast and efficient. Here are the key points covered in this tutorial:

### Key Features

- **Fast**: High performance, comparable to NodeJS and Go
- **Easy**: Intuitive to learn and use
- **Robust**: Production-ready code with automatic interactive documentation
- **Standards-based**: Based on OpenAPI and JSON Schema

### Core Concepts Covered

1. **Basic Setup**: Installation and first API
2. **Routing**: HTTP methods, path parameters, query parameters
3. **Request Handling**: Pydantic models for data validation
4. **Database Integration**: SQLAlchemy ORM with PostgreSQL/SQLite
5. **Authentication**: JWT tokens, OAuth2, dependency injection
6. **Error Handling**: Custom exceptions and validation
7. **Advanced Features**: WebSockets, file uploads, background tasks
8. **Testing**: Unit tests with pytest and TestClient
9. **Deployment**: Docker, production configuration, monitoring

### Best Practices

- Use Pydantic models for data validation
- Implement proper error handling and logging
- Use dependency injection for database sessions and authentication
- Cache frequently accessed data with Redis
- Implement rate limiting for API protection
- Use background tasks for heavy operations
- Write comprehensive tests
- Monitor application health and performance
- Follow RESTful API design principles
- Version your APIs properly

### Production Considerations

- Use environment variables for configuration
- Implement proper logging and monitoring
- Set up health checks and metrics
- Use connection pooling for databases
- Implement caching strategies
- Add security middleware and headers
- Use background task queues for heavy operations
- Plan for scaling with load balancers

### Next Steps

- Explore FastAPI's advanced features like GraphQL integration
- Learn about microservices architecture with FastAPI
- Implement real-time features with WebSockets
- Study deployment strategies for different cloud providers
- Contribute to the FastAPI community and ecosystem

FastAPI continues to evolve with regular updates and new features. Stay updated with the [official documentation](https://fastapi.tiangolo.com/) and the vibrant community.

### Useful Resources

- [FastAPI Documentation](https://fastapi.tiangolo.com/)
- [Pydantic Documentation](https://pydantic-docs.helpmanual.io/)
- [SQLAlchemy Documentation](https://docs.sqlalchemy.org/)
- [Uvicorn Documentation](https://www.uvicorn.org/)
- [FastAPI GitHub Repository](https://github.com/tiangolo/fastapi)

---

*This comprehensive tutorial provides everything you need to get started with FastAPI and build production-ready APIs. Practice with the examples and adapt them to your specific use cases.*
