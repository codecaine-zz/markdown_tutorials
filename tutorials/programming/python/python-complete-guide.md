# Complete Python Tutorial: From Basics to Standard Library

This comprehensive tutorial will guide you through the entire Python language, from basic syntax to using the powerful standard library.

## Table of Contents
1. [Python Basics](#python-basics)
2. [Variables and Data Types](#variables-and-data-types)
3. [Control Structures](#control-structures)
4. [Functions](#functions)
5. [Data Structures](#data-structures)
6. [File Operations](#file-operations)
7. [Standard Library Overview](#standard-library-overview)
8. [Essential Standard Library Modules](#essential-standard-library-modules)

## Python Basics

### What is Python?

Python is a high-level, interpreted programming language known for its simplicity and readability. It's widely used for web development, data science, automation, and more.

### Installing Python

1. Visit [python.org](https://www.python.org/)
2. Download the latest version for your operating system
3. Install with default settings (make sure to add Python to PATH)

### First Python Program

```python
# This is a simple Python program
print("Hello, World!")
```

### Running Python Code

```bash
# Run from command line
python script.py

# Or use the interactive interpreter
python
```

## Variables and Data Types

### Variable Assignment

```python
# Python is dynamically typed
name = "Alice"        # String
age = 25             # Integer
height = 5.7         # Float
is_student = True    # Boolean

# Variable naming rules
# - Must start with letter or underscore
# - Can contain letters, numbers, underscores
# - Case sensitive
# - Cannot use reserved keywords

user_name = "Bob"
age_20 = 20
```

### Data Types

```python
# Numbers
integer_num = 42
float_num = 3.14
complex_num = 3 + 4j

# Strings
single_quote = 'Hello'
double_quote = "World"
multiline = """This is a
multiline string"""

# Booleans
is_true = True
is_false = False

# None type (represents absence of value)
empty_value = None

# Type checking
print(type(42))        # <class 'int'>
print(type("hello"))   # <class 'str'>
```

### Type Conversion

```python
# Converting between types
number_str = "123"
number_int = int(number_str)      # 123
number_float = float(number_str)  # 123.0

# Converting to string
number = 42
str_number = str(number)  # "42"

# Converting to boolean
bool_true = bool(1)      # True
bool_false = bool(0)     # False
```

## Control Structures

### Conditional Statements

```python
# Basic if statement
age = 18
if age >= 18:
    print("You are an adult")
else:
    print("You are a minor")

# elif statements
score = 85
if score >= 90:
    grade = "A"
elif score >= 80:
    grade = "B"
elif score >= 70:
    grade = "C"
else:
    grade = "F"

print(f"Grade: {grade}")

# Nested conditions
is_raining = True
is_sunny = False

if is_raining:
    if is_sunny:
        print("It's both raining and sunny")
    else:
        print("It's raining")
else:
    if is_sunny:
        print("It's sunny")
    else:
        print("It's cloudy")
```

### Loops

```python
# For loops
fruits = ["apple", "banana", "orange"]

# Loop through list
for fruit in fruits:
    print(f"I like {fruit}")

# Loop with range
for i in range(5):
    print(i)  # Prints 0, 1, 2, 3, 4

# Loop with start, stop, step
for i in range(2, 10, 2):
    print(i)  # Prints 2, 4, 6, 8

# While loops
count = 0
while count < 5:
    print(f"Count: {count}")
    count += 1

# Loop control statements
for i in range(10):
    if i == 3:
        continue  # Skip iteration
    if i == 7:
        break     # Exit loop
    print(i)      # Prints 0, 1, 2, 4, 5, 6
```

### List Comprehensions

```python
# Basic list comprehension
squares = [x**2 for x in range(10)]
print(squares)  # [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]

# With conditions
even_squares = [x**2 for x in range(10) if x % 2 == 0]
print(even_squares)  # [0, 4, 16, 36, 64]

# Nested comprehensions
matrix = [[i*j for j in range(3)] for i in range(3)]
print(matrix)  # [[0, 0, 0], [0, 1, 2], [0, 2, 4]]
```

## Functions

### Defining Functions

```python
# Basic function
def greet(name):
    return f"Hello, {name}!"

print(greet("Alice"))  # Hello, Alice!

# Function with multiple parameters
def add_numbers(a, b):
    return a + b

result = add_numbers(5, 3)  # 8

# Function with default parameters
def greet_with_default(name="World"):
    return f"Hello, {name}!"

print(greet_with_default())      # Hello, World!
print(greet_with_default("Bob")) # Hello, Bob!

# Function with variable arguments
def sum_all(*numbers):
    return sum(numbers)

print(sum_all(1, 2, 3, 4))  # 10

def create_profile(**info):
    return info

profile = create_profile(name="Alice", age=25, city="New York")
print(profile)  # {'name': 'Alice', 'age': 25, 'city': 'New York'}
```

### Advanced Function Features

```python
# Lambda functions (anonymous functions)
square = lambda x: x**2
print(square(5))  # 25

# Higher-order functions
numbers = [1, 2, 3, 4, 5]
doubled = list(map(lambda x: x*2, numbers))
print(doubled)  # [2, 4, 6, 8, 10]

# Filter function
evens = list(filter(lambda x: x % 2 == 0, numbers))
print(evens)  # [2, 4]

# Function with docstrings
def calculate_area(length, width):
    """
    Calculate the area of a rectangle.
    
    Args:
        length (float): The length of the rectangle
        width (float): The width of the rectangle
    
    Returns:
        float: The area of the rectangle
    
    Example:
        >>> calculate_area(5, 3)
        15
    """
    return length * width

# Function scope
global_var = "I'm global"

def access_global():
    print(global_var)  # Can access global variable

def modify_global():
    global global_var
    global_var = "Modified globally"

access_global()  # I'm global
modify_global()
print(global_var)  # Modified globally
```

## Data Structures

### Lists

```python
# Creating lists
fruits = ["apple", "banana", "orange"]
numbers = [1, 2, 3, 4, 5]
mixed = [1, "hello", 3.14, True]

# List operations
print(fruits[0])        # apple (first element)
print(fruits[-1])       # orange (last element)
print(fruits[1:3])      # ['banana', 'orange'] (slice)

# Modifying lists
fruits.append("grape")      # Add to end
fruits.insert(1, "kiwi")    # Insert at index 1
fruits.remove("banana")     # Remove by value
removed = fruits.pop()      # Remove and return last element

# List methods
print(len(fruits))          # Length of list
print("apple" in fruits)    # Check if element exists

# Sorting and reversing
numbers.sort()              # Sort ascending
numbers.sort(reverse=True)  # Sort descending
numbers.reverse()           # Reverse order

# List methods example
shopping_list = ["milk", "bread", "eggs"]
shopping_list.extend(["cheese", "butter"])
print(shopping_list)  # ['milk', 'bread', 'eggs', 'cheese', 'butter']
```

### Tuples

```python
# Creating tuples
coordinates = (10, 20)
colors = ("red", "green", "blue")

# Tuples are immutable
# coordinates[0] = 15  # This would raise an error

# Tuple unpacking
x, y = coordinates
print(f"x: {x}, y: {y}")  # x: 10, y: 20

# Tuple with one element (note the comma)
single_tuple = (42,)
print(single_tuple)  # (42,)

# Using tuples as dictionary keys
locations = {
    (0, 0): "origin",
    (1, 1): "point A"
}
```

### Dictionaries

```python
# Creating dictionaries
person = {
    "name": "Alice",
    "age": 25,
    "city": "New York"
}

# Accessing values
print(person["name"])  # Alice
print(person.get("age"))  # 25

# Adding and modifying
person["email"] = "alice@example.com"
person["age"] = 26

# Dictionary methods
print(person.keys())    # dict_keys(['name', 'age', 'city', 'email'])
print(person.values())  # dict_values(['Alice', 26, 'New York', 'alice@example.com'])
print(person.items())   # dict_items([('name', 'Alice'), ('age', 26), ...])

# Iterating through dictionaries
for key, value in person.items():
    print(f"{key}: {value}")

# Dictionary comprehension
squares_dict = {x: x**2 for x in range(5)}
print(squares_dict)  # {0: 0, 1: 1, 2: 4, 3: 9, 4: 16}
```

### Sets

```python
# Creating sets
numbers = {1, 2, 3, 4, 5}
fruits = {"apple", "banana", "orange"}

# Set operations
set1 = {1, 2, 3}
set2 = {3, 4, 5}

# Union
union_result = set1.union(set2)        # {1, 2, 3, 4, 5}
union_result = set1 | set2             # Alternative syntax

# Intersection
intersection_result = set1.intersection(set2)  # {3}
intersection_result = set1 & set2             # Alternative syntax

# Difference
difference_result = set1.difference(set2)  # {1, 2}
difference_result = set1 - set2           # Alternative syntax

# Set methods
numbers.add(6)          # Add element
numbers.remove(1)       # Remove element (raises KeyError if not found)
numbers.discard(10)     # Remove element (no error if not found)

# Set operations example
unique_chars = set("hello")
print(unique_chars)  # {'h', 'e', 'l', 'o'}
```

## File Operations

### Reading Files

```python
# Basic file reading
try:
    with open("example.txt", "r") as file:
        content = file.read()
        print(content)
except FileNotFoundError:
    print("File not found")

# Reading line by line
with open("example.txt", "r") as file:
    for line in file:
        print(line.strip())  # strip() removes newline characters

# Reading all lines into a list
with open("example.txt", "r") as file:
    lines = file.readlines()
    print(lines)

# Reading with encoding
with open("example.txt", "r", encoding="utf-8") as file:
    content = file.read()
```

### Writing Files

```python
# Writing to files
with open("output.txt", "w") as file:
    file.write("Hello, World!\n")
    file.write("This is a new line")

# Writing multiple lines
lines = ["Line 1\n", "Line 2\n", "Line 3\n"]
with open("output.txt", "w") as file:
    file.writelines(lines)

# Appending to files
with open("output.txt", "a") as file:
    file.write("\nThis line is appended")

# Writing with context manager (recommended)
def write_data_to_file(filename, data):
    """Write data to a file safely."""
    try:
        with open(filename, "w") as file:
            file.write(data)
        print(f"Data written to {filename}")
    except Exception as e:
        print(f"Error writing to file: {e}")

write_data_to_file("test.txt", "Sample data")
```

### Working with File Paths

```python
import os
from pathlib import Path

# Using os module for path operations
current_dir = os.getcwd()  # Get current directory
print(current_dir)

# Join paths (cross-platform)
file_path = os.path.join("folder", "file.txt")
print(file_path)  # folder/file.txt (on Unix) or folder\file.txt (on Windows)

# Check if file exists
if os.path.exists("example.txt"):
    print("File exists")

# Get file information
file_stats = os.stat("example.txt")
print(f"File size: {file_stats.st_size} bytes")

# Using pathlib (modern approach)
current_path = Path(".")
print(current_path.resolve())  # Absolute path

# Creating paths
file_path = Path("data") / "input.txt"
print(file_path)  # data/input.txt

# Check if path exists
if file_path.exists():
    print("Path exists")
```

## Standard Library Overview

Python's standard library is a collection of modules that provide extensive functionality without requiring external installations. These modules are built into Python and can be used immediately.

### Key Modules Categories

1. **Core Modules**: Basic functionality (os, sys, datetime)
2. **Data Types**: Advanced data structures (collections, json, xml)
3. **File and I/O**: File operations (io, pathlib)
4. **Networking**: Internet protocols (http, socket)
5. **Database**: SQL database access (sqlite3)
6. **Mathematics**: Mathematical operations (math, random)
7. **System**: System-specific interfaces (platform, subprocess)

## Essential Standard Library Modules

### 1. `os` Module - Operating System Interface

```python
import os

# Working with directories
current_dir = os.getcwd()  # Get current working directory
print(f"Current directory: {current_dir}")

# Create directory
os.makedirs("new_folder", exist_ok=True)

# List directory contents
files = os.listdir(".")
print(f"Files in current directory: {files}")

# File and directory operations
file_path = "test.txt"
if os.path.exists(file_path):
    print("File exists")
    print(f"File size: {os.path.getsize(file_path)} bytes")

# Environment variables
print(os.environ.get("HOME"))  # Get home directory (Unix/Mac)
print(os.environ.get("USERPROFILE"))  # Get user profile (Windows)

# System information
print(f"Operating system: {os.name}")
print(f"System platform: {os.sys.platform}")
```

### 2. `sys` Module - System-specific Parameters

```python
import sys

# Get Python version
print(f"Python version: {sys.version}")

# Get command line arguments
print(f"Command line arguments: {sys.argv}")

# Get path to Python executable
print(f"Python executable: {sys.executable}")

# Get system max recursion limit
print(f"Recursion limit: {sys.getrecursionlimit()}")

# Get memory usage (approximate)
print(f"Module count: {len(sys.modules)}")

# Exit program
# sys.exit(0)  # Exit with success code
```

### 3. `datetime` Module - Date and Time Operations

```python
from datetime import datetime, date, time, timedelta
import calendar

# Current date and time
now = datetime.now()
print(f"Current datetime: {now}")

# Creating datetime objects
specific_date = datetime(2023, 12, 25, 14, 30, 0)
print(f"Specific date: {specific_date}")

# Date only
today = date.today()
print(f"Today's date: {today}")

# Time only
current_time = time(14, 30, 45)
print(f"Current time: {current_time}")

# Formatting dates
formatted = now.strftime("%Y-%m-%d %H:%M:%S")
print(f"Formatted date: {formatted}")

# Parsing dates
date_string = "2023-12-25"
parsed_date = datetime.strptime(date_string, "%Y-%m-%d")
print(f"Parsed date: {parsed_date}")

# Date arithmetic
future_date = now + timedelta(days=30)
past_date = now - timedelta(weeks=2)

print(f"30 days from now: {future_date}")
print(f"2 weeks ago: {past_date}")

# Calendar operations
print(f"Days in December 2023: {calendar.monthrange(2023, 12)[1]}")
```

### 4. `collections` Module - Advanced Data Structures

```python
from collections import Counter, defaultdict, namedtuple, deque

# Counter - Count elements in iterable
text = "hello world"
char_count = Counter(text)
print(f"Character count: {char_count}")
print(f"Most common character: {char_count.most_common(1)}")

# defaultdict - Default values for missing keys
dd = defaultdict(list)
dd['key1'].append('value1')
dd['key2'].append('value2')
print(f"Default dict: {dict(dd)}")

# namedtuple - Create tuple-like objects with named fields
Point = namedtuple('Point', ['x', 'y'])
p1 = Point(1, 2)
p2 = Point(3, 4)

print(f"Point p1: {p1}")
print(f"Point p2 x-coordinate: {p2.x}")

# deque - Double-ended queue with efficient operations
dq = deque([1, 2, 3])
dq.appendleft(0)    # Add to left
dq.append(4)        # Add to right
print(f"Deque: {list(dq)}")

dq.popleft()        # Remove from left
dq.pop()            # Remove from right
print(f"Deque after removals: {list(dq)}")
```

### 5. `json` Module - JSON Data Handling

```python
import json

# JSON data structure (JavaScript Object Notation)
data = {
    "name": "Alice",
    "age": 25,
    "city": "New York",
    "hobbies": ["reading", "swimming", "coding"]
}

# Convert Python object to JSON string
json_string = json.dumps(data, indent=2)
print("JSON string:")
print(json_string)

# Convert JSON string to Python object
parsed_data = json.loads(json_string)
print(f"Parsed data: {parsed_data}")

# Working with JSON files
# Writing to file
with open("data.json", "w") as file:
    json.dump(data, file, indent=2)

# Reading from file
with open("data.json", "r") as file:
    loaded_data = json.load(file)
    print(f"Loaded data: {loaded_data}")

# Handling JSON errors
try:
    invalid_json = '{"name": "Alice", "age":}'
    json.loads(invalid_json)
except json.JSONDecodeError as e:
    print(f"JSON parsing error: {e}")
```

### 6. `random` Module - Random Number Generation

```python
import random

# Generate random numbers
print(f"Random float: {random.random()}")  # Between 0.0 and 1.0
print(f"Random integer: {random.randint(1, 10)}")  # Between 1 and 10

# Random choice from sequence
colors = ["red", "green", "blue", "yellow"]
print(f"Random color: {random.choice(colors)}")

# Random sample without replacement
sample = random.sample(range(1, 50), 6)
print(f"Random sample: {sample}")

# Shuffle a list
numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
random.shuffle(numbers)
print(f"Shuffled numbers: {numbers}")

# Setting seed for reproducible results
random.seed(42)
print(f"Random with seed: {random.random()}")

# Using random with different distributions
import math

# Normal distribution (using Box-Muller transform approximation)
def random_normal(mean=0, std_dev=1):
    # Simple approximation using sum of uniform distributions
    u = random.uniform(0, 1)
    v = random.uniform(0, 1)
    z = math.sqrt(-2 * math.log(u)) * math.cos(2 * math.pi * v)
    return mean + std_dev * z

print(f"Normal distribution sample: {random_normal()}")
```

### 7. `re` Module - Regular Expressions

```python
import re

# Basic pattern matching
text = "Contact us at info@example.com or support@company.org"

# Find email addresses
email_pattern = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'
emails = re.findall(email_pattern, text)
print(f"Found emails: {emails}")

# Search for pattern
if re.search(r'\d{4}', "Year 2023"):
    print("Found year pattern")

# Replace text
new_text = re.sub(r'\d{4}', 'XXXX', "The year is 2023")
print(f"Replaced text: {new_text}")

# Splitting with regex
sentence = "Hello, world! How are you?"
words = re.split(r'[,\s!?]+', sentence)
print(f"Split words: {words}")

# More complex patterns
# Phone number pattern
phone_pattern = r'\b(\d{3})[-.]?(\d{3})[-.]?(\d{4})\b'
phone_text = "Call me at 123-456-7890 or 123.456.7890"
formatted_phones = re.sub(phone_pattern, r'(\1) \2-\3', phone_text)
print(f"Formatted phones: {formatted_phones}")

# Pattern groups
date_pattern = r'(\d{2})/(\d{2})/(\d{4})'
date_text = "Today is 12/25/2023"
match = re.search(date_pattern, date_text)
if match:
    print(f"Full match: {match.group(0)}")
    print(f"Month: {match.group(1)}")
    print(f"Day: {match.group(2)}")
    print(f"Year: {match.group(3)}")

# Case insensitive matching
text_case = "Hello WORLD"
case_insensitive = re.search(r'hello', text_case, re.IGNORECASE)
if case_insensitive:
    print("Found match ignoring case")
```

### 8. `math` Module - Mathematical Functions

```python
import math

# Basic mathematical operations
print(f"Square root of 16: {math.sqrt(16)}")
print(f"Power of 2^3: {math.pow(2, 3)}")
print(f"Absolute value of -5: {math.fabs(-5)}")

# Trigonometric functions (arguments in radians)
print(f"Sin of 0: {math.sin(0)}")
print(f"Cos of 0: {math.cos(0)}")
print(f"Tan of 0: {math.tan(0)}")

# Converting between radians and degrees
angle_degrees = 90
angle_radians = math.radians(angle_degrees)
print(f"{angle_degrees} degrees = {angle_radians} radians")

# Logarithmic functions
print(f"Natural log of 10: {math.log(10)}")
print(f"Log base 10 of 100: {math.log10(100)}")

# Constants
print(f"Pi value: {math.pi}")
print(f"e value: {math.e}")

# Rounding functions
print(f"Ceiling of 3.2: {math.ceil(3.2)}")
print(f"Floor of 3.2: {math.floor(3.2)}")
print(f"Round of 3.5: {round(3.5)}")  # Built-in function

# Factorial and combinations
print(f"Factorial of 5: {math.factorial(5)}")
print(f"Combinations (5 choose 2): {math.comb(5, 2)}")

# Hyperbolic functions
print(f"Hyperbolic sine of 1: {math.sinh(1)}")
```

### 9. `urllib` Module - URL Handling

```python
import urllib.request
import urllib.parse
from urllib.error import URLError, HTTPError

# Basic URL operations
url = "https://httpbin.org/get"

try:
    # Fetching URL content
    response = urllib.request.urlopen(url)
    data = response.read()
    print(f"Status code: {response.getcode()}")
    
    # Parse URL components
    parsed_url = urllib.parse.urlparse(url)
    print(f"Protocol: {parsed_url.scheme}")
    print(f"Hostname: {parsed_url.hostname}")
    print(f"Path: {parsed_url.path}")
    
    # URL encoding
    query_params = {'name': 'John Doe', 'city': 'New York'}
    encoded_params = urllib.parse.urlencode(query_params)
    print(f"Encoded params: {encoded_params}")
    
    # Building URLs
    base_url = "https://api.example.com"
    endpoint = "/users"
    full_url = urllib.parse.urljoin(base_url, endpoint)
    print(f"Full URL: {full_url}")
    
except HTTPError as e:
    print(f"HTTP Error: {e.code} - {e.reason}")
except URLError as e:
    print(f"URL Error: {e.reason}")

# URL encoding and decoding examples
original_text = "Hello World! How are you?"
encoded_text = urllib.parse.quote(original_text)
print(f"Encoded: {encoded_text}")

decoded_text = urllib.parse.unquote(encoded_text)
print(f"Decoded: {decoded_text}")
```

### 10. `subprocess` Module - Spawning Child Processes

```python
import subprocess
import sys

# Basic subprocess execution
try:
    # Run command and capture output
    result = subprocess.run(['ls', '-l'], capture_output=True, text=True, check=True)
    print("Command output:")
    print(result.stdout)
    
except subprocess.CalledProcessError as e:
    print(f"Command failed with return code {e.returncode}")
    print(f"Error output: {e.stderr}")

# Running Python scripts
python_script = "example.py"
try:
    # Run another Python script
    result = subprocess.run([sys.executable, python_script], 
                          capture_output=True, text=True, check=True)
    print(f"Script output: {result.stdout}")
except FileNotFoundError:
    print("Script file not found")

# Piping between processes
try:
    # Example: list files and count them
    ls_process = subprocess.Popen(['ls'], stdout=subprocess.PIPE)
    wc_process = subprocess.Popen(['wc', '-l'], stdin=ls_process.stdout, stdout=subprocess.PIPE)
    ls_process.stdout.close()
    output = wc_process.communicate()[0]
    print(f"Line count: {output.decode().strip()}")
except Exception as e:
    print(f"Error in subprocess: {e}")

# Running shell commands
result = subprocess.run('echo "Hello from shell"', 
                       shell=True, capture_output=True, text=True)
print(f"Shell command output: {result.stdout.strip()}")
```

### 11. `sqlite3` Module - SQLite Database Access

```python
import sqlite3
from datetime import datetime

# Connect to database (creates file if it doesn't exist)
conn = sqlite3.connect('example.db')
cursor = conn.cursor()

# Create table
cursor.execute('''
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
''')

# Insert data
users_data = [
    ('Alice Smith', 'alice@example.com'),
    ('Bob Johnson', 'bob@example.com'),
    ('Charlie Brown', 'charlie@example.com')
]

cursor.executemany('INSERT INTO users (name, email) VALUES (?, ?)', users_data)

# Commit changes
conn.commit()

# Query data
cursor.execute('SELECT * FROM users')
all_users = cursor.fetchall()
print("All users:")
for user in all_users:
    print(f"ID: {user[0]}, Name: {user[1]}, Email: {user[2]}")

# Query with conditions
cursor.execute('SELECT name FROM users WHERE email LIKE ?', ('%example.com',))
filtered_users = cursor.fetchall()
print(f"\nUsers with example.com emails: {filtered_users}")

# Update data
cursor.execute('UPDATE users SET name = ? WHERE email = ?', ('Alice Jones', 'alice@example.com'))
conn.commit()

# Delete data
cursor.execute('DELETE FROM users WHERE name = ?', ('Charlie Brown',))
conn.commit()

# Close connection
conn.close()

# Using context manager (recommended)
def create_sample_db():
    """Create a sample database with example data."""
    try:
        with sqlite3.connect('sample.db') as conn:
            cursor = conn.cursor()
            
            # Create table
            cursor.execute('''
                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY,
                    name TEXT NOT NULL,
                    price REAL,
                    category TEXT
                )
            ''')
            
            # Insert sample data
            products = [
                (1, 'Laptop', 999.99, 'Electronics'),
                (2, 'Book', 19.99, 'Education'),
                (3, 'Coffee Mug', 12.50, 'Kitchen')
            ]
            
            cursor.executemany('INSERT INTO products VALUES (?, ?, ?, ?)', products)
            
            # Query all products
            cursor.execute('SELECT * FROM products')
            products_list = cursor.fetchall()
            
            print("Sample products:")
            for product in products_list:
                print(f"ID: {product[0]}, Name: {product[1]}, Price: ${product[2]}")
                
    except sqlite3.Error as e:
        print(f"Database error: {e}")

create_sample_db()
```

### 12. `pathlib` Module - Object-Oriented File System Paths

```python
from pathlib import Path
import os

# Creating Path objects
current_dir = Path(".")
home_dir = Path.home()
print(f"Current directory: {current_dir}")
print(f"Home directory: {home_dir}")

# Path operations
file_path = Path("data") / "input.txt"
print(f"File path: {file_path}")

# Check if path exists
if file_path.exists():
    print("File exists")
else:
    print("File does not exist")

# Path components
print(f"File name: {file_path.name}")
print(f"File stem: {file_path.stem}")
print(f"File suffix: {file_path.suffix}")

# Working with directories
data_dir = Path("data")
if not data_dir.exists():
    data_dir.mkdir(parents=True, exist_ok=True)

# Listing directory contents
for item in data_dir.iterdir():
    if item.is_file():
        print(f"File: {item.name}")
    elif item.is_dir():
        print(f"Directory: {item.name}")

# Glob patterns
print("\nAll .txt files:")
for txt_file in data_dir.glob("*.txt"):
    print(f"  {txt_file}")

print("\nAll files recursively:")
for file in data_dir.rglob("*"):
    if file.is_file():
        print(f"  {file}")

# Path manipulation
new_path = Path("data") / "backup" / "file.txt"
print(f"New path: {new_path}")

# File operations with pathlib
test_file = Path("test.txt")
if not test_file.exists():
    # Create file with content
    test_file.write_text("Hello, pathlib!")
    
    # Read file content
    content = test_file.read_text()
    print(f"File content: {content}")
    
    # Get file stats
    stat = test_file.stat()
    print(f"File size: {stat.st_size} bytes")

# Safe file operations
backup_path = Path("backup.txt")
if test_file.exists():
    # Copy file using pathlib
    backup_path.write_text(test_file.read_text())
    print("File backed up successfully")
```

### 13. `logging` Module - Logging System

```python
import logging
from datetime import datetime

# Basic logging setup
logging.basicConfig(
    level=logging.DEBUG,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('app.log'),
        logging.StreamHandler()
    ]
)

# Different log levels
logging.debug("This is a debug message")
logging.info("Application started successfully")
logging.warning("This is a warning message")
logging.error("An error occurred")
logging.critical("Critical system failure")

# Creating custom logger
logger = logging.getLogger('my_app')
logger.setLevel(logging.DEBUG)

# Custom formatter
formatter = logging.Formatter(
    '%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)

# File handler
file_handler = logging.FileHandler('custom_app.log')
file_handler.setLevel(logging.INFO)
file_handler.setFormatter(formatter)

# Console handler
console_handler = logging.StreamHandler()
console_handler.setLevel(logging.DEBUG)
console_handler.setFormatter(formatter)

# Add handlers to logger
logger.addHandler(file_handler)
logger.addHandler(console_handler)

# Using custom logger
logger.info("Custom logger initialized")
logger.debug("Debug information")
logger.warning("Warning about something")

# Logging exceptions
try:
    result = 10 / 0
except ZeroDivisionError as e:
    logger.error("Division by zero error", exc_info=True)

# Advanced logging configuration
def setup_advanced_logging():
    """Setup advanced logging configuration."""
    
    # Create logger with custom name
    log = logging.getLogger('advanced_app')
    log.setLevel(logging.DEBUG)
    
    # Create formatters
    detailed_formatter = logging.Formatter(
        '%(asctime)s - %(name)s - %(levelname)s - %(filename)s:%(lineno)d - %(message)s'
    )
    
    simple_formatter = logging.Formatter('%(levelname)s: %(message)s')
    
    # Console handler
    console_handler = logging.StreamHandler()
    console_handler.setLevel(logging.INFO)
    console_handler.setFormatter(simple_formatter)
    
    # File handler for detailed logs
    file_handler = logging.FileHandler('detailed_app.log')
    file_handler.setLevel(logging.DEBUG)
    file_handler.setFormatter(detailed_formatter)
    
    # Add handlers
    log.addHandler(console_handler)
    log.addHandler(file_handler)
    
    return log

# Use the advanced logger
advanced_logger = setup_advanced_logging()
advanced_logger.info("Advanced logging configured")
advanced_logger.debug("Debug message from advanced logger")
```

### 14. `unittest` Module - Unit Testing Framework

```python
import unittest
import math

# Simple function to test
def add_numbers(a, b):
    return a + b

def is_prime(n):
    if n < 2:
        return False
    for i in range(2, int(math.sqrt(n)) + 1):
        if n % i == 0:
            return False
    return True

# Create test class
class TestMathFunctions(unittest.TestCase):
    
    def test_add_numbers(self):
        """Test addition function."""
        self.assertEqual(add_numbers(2, 3), 5)
        self.assertEqual(add_numbers(-1, 1), 0)
        self.assertEqual(add_numbers(0, 0), 0)
    
    def test_is_prime(self):
        """Test prime number function."""
        self.assertTrue(is_prime(2))
        self.assertTrue(is_prime(17))
        self.assertFalse(is_prime(4))
        self.assertFalse(is_prime(1))
    
    def test_prime_edge_cases(self):
        """Test edge cases for prime function."""
        self.assertFalse(is_prime(0))
        self.assertFalse(is_prime(-1))
        self.assertTrue(is_prime(2))

# More comprehensive test example
class TestStringOperations(unittest.TestCase):
    
    def setUp(self):
        """Set up test fixtures before each test method."""
        self.test_string = "Hello, World!"
    
    def tearDown(self):
        """Clean up after each test method."""
        self.test_string = None
    
    def test_string_length(self):
        """Test string length calculation."""
        self.assertEqual(len(self.test_string), 13)
    
    def test_string_uppercase(self):
        """Test string uppercase conversion."""
        self.assertEqual(self.test_string.upper(), "HELLO, WORLD!")
    
    def test_string_contains(self):
        """Test string contains operation."""
        self.assertIn("World", self.test_string)
    
    def test_string_split(self):
        """Test string splitting."""
        parts = self.test_string.split(", ")
        self.assertEqual(len(parts), 2)
        self.assertEqual(parts[0], "Hello")
        self.assertEqual(parts[1], "World!")

# Test with parameterized inputs
class TestMathOperations(unittest.TestCase):
    
    def test_addition(self):
        """Test addition with various inputs."""
        test_cases = [
            (1, 2, 3),
            (0, 0, 0),
            (-1, 1, 0),
            (10, -5, 5)
        ]
        
        for a, b, expected in test_cases:
            with self.subTest(a=a, b=b):
                self.assertEqual(add_numbers(a, b), expected)

# Running tests
if __name__ == '__main__':
    # Run all tests
    unittest.main(verbosity=2)
    
    # Or run specific test methods
    # suite = unittest.TestSuite()
    # suite.addTest(TestMathFunctions('test_add_numbers'))
    # runner = unittest.TextTestRunner(verbosity=2)
    # runner.run(suite)
```

### 15. `configparser` Module - Configuration File Handling

```python
import configparser
from pathlib import Path

# Create a configuration file
config_content = """
[DEFAULT]
server = localhost
port = 8080

[DATABASE]
host = db.example.com
port = 5432
username = admin
password = secret

[LOGGING]
level = INFO
file = app.log
max_size = 1048576
"""

# Write configuration file
config_file = Path("config.ini")
config_file.write_text(config_content)

# Read configuration file
config = configparser.ConfigParser()
config.read("config.ini")

# Access configuration values
print("DEFAULT section:")
print(f"Server: {config['DEFAULT']['server']}")
print(f"Port: {config['DEFAULT']['port']}")

print("\nDATABASE section:")
print(f"Host: {config['DATABASE']['host']}")
print(f"Port: {config['DATABASE']['port']}")
print(f"Username: {config['DATABASE']['username']}")

# Access with default values
db_port = config.get('DATABASE', 'port', fallback='3306')
print(f"Database port (with fallback): {db_port}")

# Check if section exists
if 'LOGGING' in config:
    print(f"Logging level: {config['LOGGING']['level']}")

# Create new configuration
new_config = configparser.ConfigParser()
new_config['APP'] = {
    'name': 'MyApp',
    'version': '1.0.0',
    'debug': 'True'
}

new_config['DATABASE'] = {
    'host': 'localhost',
    'port': '5432'
}

# Write new configuration to file
with open('new_config.ini', 'w') as configfile:
    new_config.write(configfile)

# Read and parse the new configuration
new_config_read = configparser.ConfigParser()
new_config_read.read('new_config.ini')

print("\nNew configuration:")
for section in new_config_read.sections():
    print(f"[{section}]")
    for key, value in new_config_read[section].items():
        print(f"  {key} = {value}")
```

## Complete Example: Simple Web Scraper

Here's a complete example combining various concepts:

```python
import requests
import json
import sqlite3
from datetime import datetime
from pathlib import Path
import logging

# Set up logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

class SimpleWebScraper:
    """A simple web scraper example."""
    
    def __init__(self, db_path="scraped_data.db"):
        self.db_path = db_path
        self.setup_database()
    
    def setup_database(self):
        """Set up SQLite database."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS scraped_pages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                url TEXT UNIQUE,
                title TEXT,
                content TEXT,
                scraped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def scrape_page(self, url):
        """Scrape a web page and store the data."""
        try:
            # In real application, you would use requests here
            # For this example, we'll simulate scraping
            
            # Simulated data
            title = "Sample Page Title"
            content = "This is sample content from the scraped page."
            
            # Store in database
            conn = sqlite3.connect(self.db_path)
            cursor = conn.cursor()
            
            cursor.execute('''
                INSERT OR REPLACE INTO scraped_pages (url, title, content)
                VALUES (?, ?, ?)
            ''', (url, title, content))
            
            conn.commit()
            conn.close()
            
            logging.info(f"Scraped page: {url}")
            return True
            
        except Exception as e:
            logging.error(f"Error scraping {url}: {e}")
            return False
    
    def get_scraped_pages(self):
        """Retrieve all scraped pages."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM scraped_pages ORDER BY scraped_at DESC')
        pages = cursor.fetchall()
        
        conn.close()
        return pages

# Example usage
def main():
    """Main function demonstrating various concepts."""
    
    # Create scraper instance
    scraper = SimpleWebScraper()
    
    # Scrape some pages (simulated)
    urls = [
        "https://example.com/page1",
        "https://example.com/page2",
        "https://example.com/page3"
    ]
    
    # Scrape each page
    for url in urls:
        success = scraper.scrape_page(url)
        if success:
            print(f"Successfully scraped: {url}")
        else:
            print(f"Failed to scrape: {url}")
    
    # Retrieve and display results
    pages = scraper.get_scraped_pages()
    print("\nScraped pages:")
    for page in pages:
        print(f"ID: {page[0]}, URL: {page[1]}, Title: {page[2]}")

if __name__ == "__main__":
    main()
```

## Best Practices and Tips

### 1. Use the Standard Library First
```python
# Instead of writing your own file operations, use the standard library
import os
import json

# Use pathlib for path operations
from pathlib import Path

# Use datetime for date/time operations
from datetime import datetime

# Use collections for advanced data structures
from collections import Counter, defaultdict
```

### 2. Handle Exceptions Properly
```python
import os

def safe_file_operation(filename):
    """Safely handle file operations with proper error handling."""
    try:
        with open(filename, 'r') as file:
            content = file.read()
            return content
    except FileNotFoundError:
        print(f"File {filename} not found")
        return None
    except PermissionError:
        print(f"Permission denied for {filename}")
        return None
    except Exception as e:
        print(f"Unexpected error: {e}")
        return None
```

### 3. Use Context Managers
```python
# Always use context managers for resource management
with open("file.txt", "r") as f:
    content = f.read()
# File is automatically closed

# For database connections
import sqlite3
with sqlite3.connect("database.db") as conn:
    cursor = conn.cursor()
    # Operations here
# Connection is automatically closed
```

### 4. Write Documentation
```python
def calculate_average(numbers):
    """
    Calculate the average of a list of numbers.
    
    Args:
        numbers (list): List of numeric values
        
    Returns:
        float: Average value, or 0 if list is empty
        
    Raises:
        TypeError: If input is not a list or contains non-numeric values
    """
    if not isinstance(numbers, list):
        raise TypeError("Input must be a list")
    
    if not numbers:
        return 0
    
    try:
        return sum(numbers) / len(numbers)
    except TypeError:
        raise TypeError("All elements must be numeric")
```

## Conclusion

This comprehensive tutorial covered:

1. **Basic Python Syntax**: Variables, data types, and control structures
2. **Advanced Data Structures**: Lists, tuples, dictionaries, and sets
3. **Functions**: Defining, using, and advanced function features
4. **File Operations**: Reading, writing, and path handling
5. **Standard Library Modules**: Key modules for practical programming

The Python standard library provides extensive functionality that makes Python a powerful and productive language. By mastering these core concepts and standard library modules, you'll be well-equipped to tackle a wide variety of programming tasks.

Remember to:
- Always use the standard library when available (it's more reliable and efficient)
- Follow Python's style guide (PEP 8)
- Use context managers for resource management
- Handle exceptions appropriately
- Write clear, documented code

This foundation will serve you well as you continue to explore more advanced Python topics and frameworks.