# Python Utils Library – Complete Guide 📚

**A beginner-friendly, copy-paste-ready collection of Python helper functions**

This guide shows you how to use a single Python file (`utils.py`) that contains helpful tools for common programming tasks. No complicated setup required – just copy what you need!

---

## 📋 Table of Contents

1. [Getting Started](#getting-started)
2. [String Tools](#string-tools) – Text manipulation
3. [Number Tools](#number-tools) – Math operations
4. [Dictionary Tools](#dictionary-tools) – Working with data
5. [Set Tools](#set-tools) – Unique collections
6. [Collection Tools](#collection-tools) – Lists and groups
7. [File Path Tools](#file-path-tools) – Managing folders and files
8. [File Operations](#file-operations) – Reading and writing files
9. [Database Tools](#database-tools) – SQLite database helpers

---

## 🚀 Getting Started {#getting-started}

### What is this?

Think of this as a toolbox full of pre-made solutions for common tasks like:
- Converting text formats (e.g., "Hello World" → "hello_world")
- Reading/writing files (JSON, CSV, text files)
- Working with databases
- Managing file paths and folders

### How to use it?

**Option 1: Import everything**
```python
import utils

# Use any tool with the "utils." prefix
result = utils.StringHelper.slugify("My Blog Post")
```

**Option 2: Import only what you need** (recommended)
```python
from utils import StringHelper, FileHelper

# Now use them directly
result = StringHelper.slugify("My Blog Post")
data = FileHelper.read_json("config.json")
```

---

## 📝 String Tools {#string-tools}

**What it does:** Helps you manipulate and transform text.

### Available Tools

| Tool | What it does | Example Input | Example Output |
|------|-------------|---------------|----------------|
| `slugify()` | Makes text URL-friendly | "Hello World!" | "hello-world" |
| `to_snake_case()` | Converts to snake_case | "MyClassName" | "my_class_name" |
| `snake_to_camel()` | Converts to CamelCase | "my_variable" | "MyVariable" |
| `is_palindrome()` | Checks if text reads same backwards | "racecar" | True |
| `truncate()` | Shortens long text | "Very long text..." | "Very long..." |
| `remove_accents()` | Removes accents from letters | "Café" | "Cafe" |

### 📖 Copy-Paste Examples

#### Example 1: Create URL-friendly slugs for blog posts

**Problem:** You have blog titles with spaces and special characters.  
**Solution:** Convert them to clean URLs.

```python
from utils import StringHelper

# Your blog title
title = "10 Tips for Python Programming!"

# Make it URL-friendly
url_slug = StringHelper.slugify(title)

print(f"URL: https://myblog.com/{url_slug}")
# Output: https://myblog.com/10-tips-for-python-programming
```

#### Example 2: Convert API data to Python style

**Problem:** API returns data with camelCase but Python uses snake_case.  
**Solution:** Convert all keys automatically.

```python
from utils import StringHelper

# Data from an API (uses camelCase)
api_data = {
    "userName": "john_doe",
    "userEmail": "john@example.com",
    "isActive": True
}

# Convert keys to snake_case
python_data = {
    StringHelper.to_snake_case(key): value 
    for key, value in api_data.items()
}

print(python_data)
# Output: {'user_name': 'john_doe', 'user_email': 'john@example.com', 'is_active': True}
```

#### Example 3: Shorten text for previews

**Problem:** You need to show a short preview of a long article.  
**Solution:** Truncate it smartly.

```python
from utils import StringHelper

article = "This is a very long article about Python programming that needs to be shortened for display."

# Shorten to 40 characters
preview = StringHelper.truncate(article, 40)

print(preview)
# Output: "This is a very long article about..."
```

#### Example 4: Check if a phrase is a palindrome

**Problem:** Verify if text reads the same forwards and backwards.  
**Solution:** Use the palindrome checker.

```python
from utils import StringHelper

phrases = [
    "A man a plan a canal Panama",  # Famous palindrome
    "hello world",                  # Not a palindrome
    "racecar"                       # Simple palindrome
]

for phrase in phrases:
    if StringHelper.is_palindrome(phrase):
        print(f"✓ '{phrase}' is a palindrome!")
    else:
        print(f"✗ '{phrase}' is NOT a palindrome")
```

---

## 🔢 Number Tools {#number-tools}

**What it does:** Helps with mathematical operations and number checks.

### Available Tools

| Tool | What it does | Example |
|------|-------------|---------|
| `is_prime()` | Checks if number is prime | `is_prime(17)` → True |
| `is_even()` | Checks if number is even | `is_even(4)` → True |
| `is_odd()` | Checks if number is odd | `is_odd(5)` → True |
| `clamp()` | Keeps number within range | `clamp(150, 0, 100)` → 100 |
| `gcd()` | Greatest common divisor | `gcd(12, 8)` → 4 |
| `lcm()` | Least common multiple | `lcm(4, 6)` → 12 |
| `factorial()` | Calculates factorial | `factorial(5)` → 120 |

### 📖 Copy-Paste Examples

#### Example 1: Validate sensor readings

**Problem:** Sensors sometimes give values outside valid range.  
**Solution:** Clamp them to acceptable limits.

```python
from utils import NumberHelper

# Raw sensor readings (some out of range)
readings = [15.5, -5.0, 110.0, 42.0, 98.5]

# Keep values between 0 and 100
valid_readings = [
    NumberHelper.clamp(reading, 0, 100) 
    for reading in readings
]

print("Original:", readings)
print("Clamped:", valid_readings)
# Output:
# Original: [15.5, -5.0, 110.0, 42.0, 98.5]
# Clamped: [15.5, 0, 100, 42.0, 98.5]
```

#### Example 2: Separate even and odd numbers

**Problem:** Split a list of numbers into even and odd groups.  
**Solution:** Use the even/odd checkers.

```python
from utils import NumberHelper

numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

# Separate into even and odd
evens = [n for n in numbers if NumberHelper.is_even(n)]
odds = [n for n in numbers if NumberHelper.is_odd(n)]

print("Even numbers:", evens)  # [2, 4, 6, 8, 10]
print("Odd numbers:", odds)    # [1, 3, 5, 7, 9]
```

#### Example 3: Find when two repeating tasks coincide

**Problem:** Task A repeats every 12 minutes, Task B every 15 minutes.  
**Solution:** Find when they happen at the same time.

```python
from utils import NumberHelper

task_a_interval = 12  # minutes
task_b_interval = 15  # minutes

# Find when they coincide
coincide_time = NumberHelper.lcm(task_a_interval, task_b_interval)

print(f"Tasks will coincide every {coincide_time} minutes")
# Output: Tasks will coincide every 60 minutes
```

#### Example 4: Simplify a fraction

**Problem:** Reduce a fraction to its simplest form.  
**Solution:** Use the greatest common divisor.

```python
from utils import NumberHelper

numerator = 24
denominator = 36

# Find the GCD
common_divisor = NumberHelper.gcd(numerator, denominator)

# Simplify the fraction
simplified_num = numerator // common_divisor
simplified_den = denominator // common_divisor

print(f"{numerator}/{denominator} = {simplified_num}/{simplified_den}")
# Output: 24/36 = 2/3
```

---

## 📦 Dictionary Tools {#dictionary-tools}

**What it does:** Makes working with dictionaries (key-value pairs) easier.

### Available Tools

| Tool | What it does | When to use |
|------|-------------|-------------|
| `merge()` | Combines two dictionaries | Merging settings |
| `deep_merge()` | Combines nested dictionaries | Complex configurations |
| `flatten()` | Flattens nested structure | Creating env variables |
| `get_nested()` | Gets value from nested dict | Accessing deep data |
| `filter_none()` | Removes None values | Cleaning form data |

### 📖 Copy-Paste Examples

#### Example 1: Merge configuration settings

**Problem:** You have default settings and user preferences to combine.  
**Solution:** Merge them (user settings win).

```python
from utils import DictHelper

# Default application settings
defaults = {
    "theme": "light",
    "font_size": 12,
    "auto_save": True
}

# User preferences (override defaults)
user_prefs = {
    "theme": "dark",
    "font_size": 14
}

# Merge them together
final_settings = DictHelper.merge(defaults, user_prefs)

print(final_settings)
# Output: {'theme': 'dark', 'font_size': 14, 'auto_save': True}
```

#### Example 2: Access nested data from an API

**Problem:** API returns deeply nested JSON data.  
**Solution:** Extract values easily without lots of brackets.

```python
from utils import DictHelper

# Complex API response
response = {
    "user": {
        "profile": {
            "name": "Alice",
            "settings": {
                "theme": "dark",
                "notifications": True
            }
        }
    }
}

# Get nested value - Method 1: Using path list
theme = DictHelper.get_nested(response, ["user", "profile", "settings", "theme"])
print(f"Theme: {theme}")  # Output: Theme: dark

# Get nested value - Method 2: Using dot notation (easier!)
theme = DictHelper.deep_get(response, "user.profile.settings.theme")
print(f"Theme: {theme}")  # Output: Theme: dark
```

#### Example 3: Clean up form data

**Problem:** Form has optional fields that might be None.  
**Solution:** Remove all None values before saving.

```python
from utils import DictHelper

# Form data with some empty fields
form_data = {
    "username": "alice",
    "email": "alice@example.com",
    "phone": None,           # User didn't provide
    "address": None,         # User didn't provide
    "age": 25
}

# Remove None values
clean_data = DictHelper.filter_none(form_data)

print(clean_data)
# Output: {'username': 'alice', 'email': 'alice@example.com', 'age': 25}
```

#### Example 4: Flatten config for environment variables

**Problem:** Need to convert nested config to flat env variables.  
**Solution:** Flatten the structure.

```python
from utils import DictHelper

# Nested configuration
config = {
    "app": {
        "name": "MyApp",
        "database": {
            "host": "localhost",
            "port": 5432
        }
    }
}

# Flatten with underscores
flat = DictHelper.flatten(config, sep="_")

# Convert to environment variable format
env_vars = {key.upper(): str(value) for key, value in flat.items()}

print(env_vars)
# Output:
# {
#   'APP_NAME': 'MyApp',
#   'APP_DATABASE_HOST': 'localhost',
#   'APP_DATABASE_PORT': '5432'
# }
```

---

## 🗂️ Collection Tools {#collection-tools}

**What it does:** Helps organize and process lists and groups of items.

### Available Tools

| Tool | What it does | Example Use |
|------|-------------|-------------|
| `chunk()` | Splits list into smaller pieces | Processing 1000 items in batches of 50 |
| `flatten()` | Combines nested lists | Merging multiple result lists |
| `group_by()` | Groups items by category | Organizing products by type |
| `count_occurrences()` | Counts how many of each item | Counting page views |
| `unique_everseen()` | Removes duplicates | Cleaning tag lists |

### 📖 Copy-Paste Examples

#### Example 1: Process items in batches

**Problem:** You have 1000 user IDs and need to process 25 at a time.  
**Solution:** Split them into chunks.

```python
from utils import CollectionHelper

# Large list of user IDs
user_ids = list(range(1, 101))  # 100 users for this example

# Split into batches of 25
batches = CollectionHelper.chunk(user_ids, 25)

print(f"Total batches: {len(batches)}")

for i, batch in enumerate(batches, 1):
    print(f"Batch {i}: {len(batch)} users (IDs {batch[0]} to {batch[-1]})")
    # Here you would process this batch
    # send_emails(batch)
```

#### Example 2: Count page views

**Problem:** Track which pages are most popular on your website.  
**Solution:** Count occurrences.

```python
from utils import CollectionHelper

# List of page visits (from log file)
page_views = [
    "home", "about", "home", "products", 
    "home", "about", "contact", "home"
]

# Count how many times each page was viewed
view_counts = CollectionHelper.count_occurrences(page_views)

# Sort by most popular
print("📊 Page Statistics:")
for page, count in sorted(view_counts.items(), key=lambda x: x[1], reverse=True):
    print(f"  {page}: {count} views")

# Output:
# 📊 Page Statistics:
#   home: 4 views
#   about: 2 views
#   products: 1 views
#   contact: 1 views
```

#### Example 3: Group products by category

**Problem:** Organize products into categories for display.  
**Solution:** Use group_by.

```python
from utils import CollectionHelper

# List of products
products = [
    {"name": "Laptop", "category": "Electronics", "price": 1200},
    {"name": "Mouse", "category": "Electronics", "price": 25},
    {"name": "Desk", "category": "Furniture", "price": 300},
    {"name": "Chair", "category": "Furniture", "price": 150},
    {"name": "Monitor", "category": "Electronics", "price": 400},
]

# Group by category
by_category = CollectionHelper.group_by(
    products, 
    lambda p: p["category"]  # Group by the "category" field
)

# Display grouped products
for category, items in by_category.items():
    print(f"\n{category}:")
    for item in items:
        print(f"  - {item['name']}: ${item['price']}")

# Output:
# Electronics:
#   - Laptop: $1200
#   - Mouse: $25
#   - Monitor: $400
# Furniture:
#   - Desk: $300
#   - Chair: $150
```

#### Example 4: Remove duplicate tags

**Problem:** User added duplicate tags to a blog post.  
**Solution:** Keep unique tags in order.

```python
from utils import CollectionHelper

# Tags with duplicates
tags = ["python", "web", "python", "api", "web", "database", "python"]

# Get unique tags while preserving order
unique_tags = list(CollectionHelper.unique_everseen(tags))

print("Original tags:", tags)
print("Unique tags:", unique_tags)
# Output:
# Original tags: ['python', 'web', 'python', 'api', 'web', 'database', 'python']
# Unique tags: ['python', 'web', 'api', 'database']
```

---

## 📁 File Path Tools {#file-path-tools}

**What it does:** Helps manage folders and file paths safely across different operating systems.

### Available Tools

| Tool | What it does | Example |
|------|-------------|---------|
| `ensure_dir()` | Creates folder if needed | Make "data/exports" folder |
| `exists()` | Checks if file/folder exists | Check if "config.json" exists |
| `is_file()` | Checks if path is a file | Verify it's a file |
| `is_dir()` | Checks if path is a folder | Verify it's a folder |
| `list_files()` | Lists all files in folder | Find all .txt files |

### 📖 Copy-Paste Examples

#### Example 1: Set up project folders

**Problem:** Your app needs specific folders to exist before running.  
**Solution:** Create them automatically.

```python
from utils import PathHelper

# Define the project structure
project_name = "my_app"
folders_needed = [
    "data",           # For data files
    "data/raw",       # For raw data
    "data/processed", # For processed data
    "logs",           # For log files
    "exports",        # For exported files
]

# Create all folders
print("Setting up project folders...")
for folder in folders_needed:
    full_path = PathHelper.join(project_name, folder)
    PathHelper.ensure_dir(full_path)
    print(f"  ✓ {full_path}")

print("\n✅ Project structure ready!")
```

#### Example 2: Check if config file exists

**Problem:** Load config if it exists, create default if not.  
**Solution:** Check before trying to read.

```python
from utils import PathHelper, FileHelper

config_file = "config.json"

if PathHelper.exists(config_file):
    print("📂 Loading existing configuration...")
    config = FileHelper.read_json(config_file)
else:
    print("📝 Creating default configuration...")
    default_config = {
        "app_name": "My App",
        "version": "1.0.0",
        "debug": False
    }
    FileHelper.write_json(config_file, default_config)
    config = default_config

print(f"Configuration: {config}")
```

#### Example 3: Find all Python files in project

**Problem:** List all Python files for code review.  
**Solution:** Search recursively.

```python
from utils import PathHelper

project_folder = "my_project"

# Find all Python files (including in subfolders)
python_files = PathHelper.list_files(
    project_folder,
    recurse=True,      # Look in subfolders too
    pattern="*.py"     # Only Python files
)

print(f"Found {len(python_files)} Python files:")
for file in python_files:
    print(f"  📄 {file}")
```

---

## 💾 File Operations {#file-operations}

**What it does:** Read and write different file formats easily.

### Supported Formats

| Format | Read Function | Write Function | Common Use |
|--------|--------------|----------------|------------|
| Text | `read_text()` | `write_text()` | Logs, notes |
| JSON | `read_json()` | `write_json()` | Settings, API data |
| CSV | `read_csv()` | `write_csv()` | Spreadsheet data |

### 📖 Copy-Paste Examples

#### Example 1: Save and load application settings (JSON)

**Problem:** Store user preferences between sessions.  
**Solution:** Use JSON format.

```python
from utils import FileHelper, PathHelper

settings_file = "user_settings.json"

# Save settings
user_settings = {
    "username": "alice",
    "theme": "dark",
    "font_size": 14,
    "notifications": True
}

FileHelper.write_json(settings_file, user_settings)
print("✅ Settings saved!")

# Load settings later
loaded_settings = FileHelper.read_json(settings_file)
print(f"Loaded settings: {loaded_settings}")
```

#### Example 2: Export data to CSV for Excel

**Problem:** Export sales data to Excel-compatible format.  
**Solution:** Write CSV file.

```python
from utils import FileHelper

# Sales data to export
sales_data = [
    {"date": "2024-01-01", "product": "Laptop", "amount": 1200, "qty": 2},
    {"date": "2024-01-02", "product": "Mouse", "amount": 25, "qty": 10},
    {"date": "2024-01-03", "product": "Keyboard", "amount": 75, "qty": 5},
]

# Export to CSV
FileHelper.write_csv(
    "sales_report.csv",
    sales_data,
    fieldnames=["date", "product", "amount", "qty"]  # Column order
)

print("✅ Sales report exported to sales_report.csv")
print("📊 You can now open it in Excel!")
```

#### Example 3: Keep an application log

**Problem:** Track what your application does.  
**Solution:** Append to a log file.

```python
from utils import FileHelper
from datetime import datetime

log_file = "app.log"

def log_message(message):
    """Add a timestamped message to the log."""
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    log_entry = f"[{timestamp}] {message}\n"
    FileHelper.append_text(log_file, log_entry)

# Use it in your application
log_message("Application started")
log_message("User logged in: alice")
log_message("Data processing completed")
log_message("Application stopped")

# Read the log
print("📜 Application Log:")
print(FileHelper.read_text(log_file))
```

---

## 🗄️ Database Tools {#database-tools}

**What it does:** Simple SQLite database operations without writing SQL.

### Why SQLite?
- **No setup needed** – works out of the box
- **Self-contained** – database is just a file
- **Perfect for** – small apps, prototypes, local storage
- **Free forever** – no licensing needed

### Available Tools

| Tool | What it does | Example Use |
|------|-------------|-------------|
| `insert()` | Add one record | Save new user |
| `insert_many()` | Add multiple records | Import data |
| `select()` | Get records | Load user list |
| `update()` | Modify records | Update email |
| `delete()` | Remove records | Delete old logs |
| `count()` | Count records | Get total users |

### 📖 Copy-Paste Examples

#### Example 1: Create a contact book database

**Problem:** Store and retrieve contacts.  
**Solution:** Simple database with SQLite.

```python
from utils import SQLiteHelper, PathHelper

# Create database file
db_file = "contacts.db"

# Step 1: Create the contacts table
schema = """
    CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        phone TEXT,
        email TEXT,
        notes TEXT
    );
"""

SQLiteHelper.execute_script(db_file, schema)
print("✅ Database created!")

# Step 2: Add contacts
print("\n📝 Adding contacts...")

SQLiteHelper.insert(db_file, "contacts", {
    "name": "Alice Smith",
    "phone": "555-0101",
    "email": "alice@example.com",
    "notes": "Friend from work"
})

SQLiteHelper.insert(db_file, "contacts", {
    "name": "Bob Jones",
    "phone": "555-0102",
    "email": "bob@example.com",
    "notes": "College roommate"
})

print("✅ Contacts added!")

# Step 3: View all contacts
print("\n📖 Contact List:")
contacts = SQLiteHelper.select(db_file, "contacts", order_by="name ASC")

for contact in contacts:
    print(f"\n  Name: {contact['name']}")
    print(f"  Phone: {contact['phone']}")
    print(f"  Email: {contact['email']}")
    if contact['notes']:
        print(f"  Notes: {contact['notes']}")
```

#### Example 2: Track expenses

**Problem:** Keep track of monthly expenses.  
**Solution:** Store in a database and query by category.

```python
from utils import SQLiteHelper

db_file = "expenses.db"

# Setup database
schema = """
    CREATE TABLE IF NOT EXISTS expenses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        date TEXT NOT NULL,
        category TEXT NOT NULL,
        amount REAL NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
"""

SQLiteHelper.execute_script(db_file, schema)

# Add some expenses
expenses_to_add = [
    {"date": "2024-01-15", "category": "Food", "amount": 45.50, "description": "Groceries"},
    {"date": "2024-01-16", "category": "Transport", "amount": 12.00, "description": "Bus fare"},
    {"date": "2024-01-17", "category": "Food", "amount": 25.00, "description": "Restaurant"},
    {"date": "2024-01-18", "category": "Entertainment", "amount": 50.00, "description": "Movie tickets"},
    {"date": "2024-01-19", "category": "Food", "amount": 38.75, "description": "Groceries"},
]

SQLiteHelper.insert_many(db_file, "expenses", expenses_to_add)
print("✅ Expenses recorded!")

# Get total by category
query = """
    SELECT 
        category,
        COUNT(*) as count,
        SUM(amount) as total
    FROM expenses
    GROUP BY category
    ORDER BY total DESC
"""

summary = SQLiteHelper.execute(db_file, query)

print("\n💰 Expense Summary:")
for row in summary:
    print(f"  {row['category']}: ${row['total']:.2f} ({row['count']} transactions)")

# Output:
# 💰 Expense Summary:
#   Food: $109.25 (3 transactions)
#   Entertainment: $50.00 (1 transactions)
#   Transport: $12.00 (1 transactions)
```

#### Example 3: Simple to-do list app

**Problem:** Create a to-do list that saves between sessions.  
**Solution:** Use SQLite for persistence.

```python
from utils import SQLiteHelper

db_file = "todos.db"

# Setup database
schema = """
    CREATE TABLE IF NOT EXISTS todos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        task TEXT NOT NULL,
        completed BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
"""

SQLiteHelper.execute_script(db_file, schema)

def add_task(task_description):
    """Add a new task."""
    task_id = SQLiteHelper.insert(db_file, "todos", {
        "task": task_description,
        "completed": 0
    })
    print(f"✅ Task added (ID: {task_id})")

def complete_task(task_id):
    """Mark a task as completed."""
    count = SQLiteHelper.update(
        db_file,
        "todos",
        {"completed": 1},
        where="id = ?",
        where_params=(task_id,)
    )
    if count > 0:
        print(f"✓ Task {task_id} marked complete!")

def show_tasks():
    """Display all tasks."""
    tasks = SQLiteHelper.select(
        db_file,
        "todos",
        order_by="created_at DESC"
    )
    
    print("\n📋 To-Do List:")
    if not tasks:
        print("  (No tasks yet)")
        return
    
    for task in tasks:
        status = "✓" if task["completed"] else "☐"
        print(f"  {status} [{task['id']}] {task['task']}")

# Use the to-do list
add_task("Buy groceries")
add_task("Write report")
add_task("Call dentist")

show_tasks()

# Complete a task
complete_task(1)

show_tasks()
```

#### Example 4: Search and filter data

**Problem:** Find specific records that match criteria.  
**Solution:** Use the select function with WHERE conditions.

```python
from utils import SQLiteHelper

db_file = "products.db"

# Find all electronics under $500
affordable_electronics = SQLiteHelper.select(
    db_file,
    "products",
    columns="name, price, stock",
    where="category = ? AND price < ?",
    where_params=("Electronics", 500),
    order_by="price ASC"
)

print("💻 Affordable Electronics:")
for product in affordable_electronics:
    print(f"  {product['name']}: ${product['price']} ({product['stock']} in stock)")

# Count how many products in each category
total_by_category = SQLiteHelper.execute(
    db_file,
    """
        SELECT category, COUNT(*) as total
        FROM products
        GROUP BY category
    """
)

print("\n📊 Products by Category:")
for row in total_by_category:
    print(f"  {row['category']}: {row['total']} products")
```

---

## 🎯 Quick Reference Card

**Copy this for easy lookup:**

```python
# STRING OPERATIONS
from utils import StringHelper
StringHelper.slugify("Hello World")        # → "hello-world"
StringHelper.to_snake_case("MyClass")      # → "my_class"
StringHelper.truncate("Long text...", 20)  # → "Long text..."

# NUMBER OPERATIONS
from utils import NumberHelper
NumberHelper.is_prime(17)                  # → True
NumberHelper.clamp(150, 0, 100)            # → 100
NumberHelper.lcm(12, 15)                   # → 60

# DICTIONARY OPERATIONS
from utils import DictHelper
DictHelper.merge({"a": 1}, {"b": 2})       # → {"a": 1, "b": 2}
DictHelper.get_nested({"a": {"b": 2}}, ["a", "b"])  # → 2

# COLLECTION OPERATIONS
from utils import CollectionHelper
CollectionHelper.chunk(range(10), 3)       # → [[0, 1, 2], [3, 4, 5], [6, 7, 8], [9]]
CollectionHelper.count_occurrences(["a", "b", "a"])  # → {"a": 2, "b": 1}

# FILE OPERATIONS
from utils import FileHelper
FileHelper.write_json("data.json", {"key": "value"})
data = FileHelper.read_json("data.json")
FileHelper.write_csv("data.csv", [{"col1": "val1"}])

# PATH OPERATIONS
from utils import PathHelper
PathHelper.ensure_dir("data/exports")      # Creates folder
PathHelper.exists("file.txt")              # Checks if exists
files = PathHelper.list_files("folder")    # Lists all files

# DATABASE OPERATIONS
from utils import SQLiteHelper
SQLiteHelper.insert(db, "users", {"name": "Alice"})
users = SQLiteHelper.select(db, "users")
SQLiteHelper.update(db, "users", {"name": "Bob"}, "id = ?", (1,))
```

---

## 💡 Tips for Beginners

1. **Start Small** – Pick one helper and try it. Don't try to learn everything at once.

2. **Copy-Paste is OK** – All examples are designed to be copied and modified for your needs.

3. **Read Error Messages** – If something doesn't work, Python's error messages usually tell you what's wrong.

4. **Test First** – Try examples with small test data before using with real data.

5. **Use Comments** – Add comments to remember what code does:
   ```python
   # This converts the blog title to a URL-friendly format
   slug = StringHelper.slugify(title)
   ```

6. **One Step at a Time** – Break complex tasks into smaller steps.

---

## 🆘 Common Issues & Solutions

### Issue: "ModuleNotFoundError: No module named 'utils'"

**Problem:** Python can't find the utils.py file.  
**Solution:** Make sure `utils.py` is in the same folder as your script.

### Issue: "FileNotFoundError: [Errno 2] No such file"

**Problem:** Trying to read a file that doesn't exist.  
**Solution:** Check the file path and use `PathHelper.exists()` first:
```python
if PathHelper.exists("myfile.txt"):
    content = FileHelper.read_text("myfile.txt")
else:
    print("File doesn't exist!")
```

### Issue: "sqlite3.OperationalError: no such table"

**Problem:** Trying to use a database table that wasn't created.  
**Solution:** Run the CREATE TABLE statement first (see database examples).

### Issue: "TypeError: ... got an unexpected keyword argument"

**Problem:** Wrong parameter name or extra parameter.  
**Solution:** Check the example and make sure parameter names match exactly.

---

## ✅ What You Learned

After reading this guide, you can:

- ✓ Manipulate text (slugs, case conversion, truncation)
- ✓ Perform number operations (validation, clamping, math)
- ✓ Work with dictionaries (merge, access nested data)
- ✓ Process collections (group, count, chunk)
- ✓ Manage files and folders safely
- ✓ Read and write JSON, CSV, and text files
- ✓ Use a SQLite database without writing SQL

---

## 🚀 Next Steps

1. **Try the examples** – Copy one example and run it
2. **Modify for your needs** – Change the data to match your project
3. **Combine multiple tools** – Use StringHelper + FileHelper together
4. **Build something** – Create a small project using these tools

**Remember:** Every expert was once a beginner. Keep practicing! 🎉