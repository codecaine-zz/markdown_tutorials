# Complete Python Tutorial (Python 3.12) - Extended Edition with Detailed Best Practices and Gotchas

## Table of Contents
1. [Introduction to Python](#1-introduction-to-python)
2. [Setting Up Python](#2-setting-up-python)
3. [Basic Syntax and Variables](#3-basic-syntax-and-variables)
4. [Data Types](#4-data-types)
5. [Operators](#5-operators)
6. [Control Flow](#6-control-flow)
7. [Functions](#7-functions)
8. [Data Structures](#8-data-structures)
9. [Object-Oriented Programming](#9-object-oriented-programming)
10. [Modules and Packages](#10-modules-and-packages)
11. [File Handling](#11-file-handling)
12. [Error Handling](#12-error-handling)
13. [Advanced Features](#13-advanced-features)
14. [JSON Handling](#14-json-handling)
15. [Best Practices and Gotchas](#15-best-practices-and-gotchas)

---

## 1. Introduction to Python

Python is a high-level, interpreted programming language known for its simplicity and readability.

```python
# Your first Python program
print("Hello, World!")

# Python is dynamically typed
name = "Alice"      # String
age = 25           # Integer
height = 5.8       # Float
is_student = True  # Boolean

print(f"My name is {name}, I'm {age} years old")
```

### Best Practices for Python Introduction

**1. Use meaningful variable names:**
```python
# Good
user_name = "Alice"
total_price = 19.99

# Avoid
x = "Alice"  # What is x?
tp = 19.99   # What is tp?
```

**2. Follow PEP 8 style guide:**
- Use snake_case for variables and functions
- Use PascalCase for classes
- Use 4 spaces for indentation (not tabs)

**3. Write docstrings for modules and functions:**
```python
"""
This module handles user authentication.
"""

def login_user(username, password):
    """
    Authenticate a user with username and password.
    
    Args:
        username (str): The user's username
        password (str): The user's password
        
    Returns:
        bool: True if authentication successful, False otherwise
    """
    # Implementation here
    pass
```

### Common Gotchas for Beginners

**1. Python is case-sensitive:**
```python
# These are different variables!
Name = "Alice"
name = "Bob"
NAME = "Charlie"

print(Name)  # Alice
print(name)  # Bob
print(NAME)  # Charlie
```

**2. Indentation matters in Python:**
```python
# Correct indentation
if True:
    print("This works")

# This will cause IndentationError:
# if True:
# print("This won't work")
```

**3. Variables are references, not containers:**
```python
# Understanding references
list1 = [1, 2, 3]
list2 = list1  # Both point to the same list object
list1.append(4)
print(list2)  # [1, 2, 3, 4] - list2 is affected too!
```

## 2. Setting Up Python

### Best Practices for Environment Setup

**1. Always use virtual environments:**
```bash
# Create virtual environment
python -m venv my_project_env

# Activate on Windows
my_project_env\Scripts\activate

# Activate on macOS/Linux
source my_project_env/bin/activate

# Install packages
pip install requests pandas numpy

# Create requirements.txt
pip freeze > requirements.txt

# Deactivate virtual environment
deactivate
```

**Why use virtual environments?**
- Prevent package conflicts between projects
- Keep system Python clean
- Make projects reproducible
- Easier dependency management

**2. Use a requirements.txt file:**
```txt
# requirements.txt
requests==2.28.1
pandas==1.5.2
numpy==1.24.1
```

**3. Consider using conda for data science projects:**
```bash
# Create conda environment
conda create -n my_data_env python=3.12

# Activate
conda activate my_data_env

# Install packages
conda install pandas numpy matplotlib
```

### Gotchas with Environment Setup

**1. System vs Virtual Environment packages:**
```python
# This might work in system Python but fail in virtual environment
import some_system_package  # ImportError if not installed in venv

# Solution: Always install in your virtual environment
# pip install required_package
```

**2. Python version compatibility:**
```bash
# Check your Python version
python --version

# Use specific version if needed
python3.12 script.py
```

**3. Path issues:**
```bash
# Make sure you're in the right environment
which python  # Shows current Python path
pip list      # Shows installed packages in current environment
```

## 3. Basic Syntax and Variables

### Best Practices for Variables and Syntax

**1. Use descriptive variable names:**
```python
# Good
student_grades = [85, 92, 78, 96]
total_students = len(student_grades)
average_grade = sum(student_grades) / total_students

# Avoid
sg = [85, 92, 78, 96]  # Unclear what sg means
ts = len(sg)           # Unclear what ts means
ag = sum(sg) / ts      # Unclear what ag means
```

**2. Use constants for magic numbers:**
```python
# Good
MAX_RETRY_ATTEMPTS = 3
DEFAULT_TIMEOUT = 30
PI = 3.14159

# Avoid
for i in range(3):  # What is 3?
    # some code with timeout of 30
```

**3. Group related variables:**
```python
# Good - using classes or dataclasses for related data
class DatabaseConfig:
    HOST = "localhost"
    PORT = 5432
    USERNAME = "admin"
    PASSWORD = "secret"

# Or using dataclasses (Python 3.7+)
from dataclasses import dataclass

@dataclass
class Point:
    x: float
    y: float
```

### Detailed Gotchas with Variables

**1. Variable scope and LEGB rule:**
```python
# LEGB Rule: Local, Enclosing, Global, Built-in

# Built-in scope (len, print, etc.)
# Global scope
global_var = "I'm global"

def outer_function():
    # Enclosing scope
    enclosing_var = "I'm enclosing"
    
    def inner_function():
        # Local scope
        local_var = "I'm local"
        print(local_var)      # Local
        print(enclosing_var)  # Enclosing
        print(global_var)     # Global
        print(len("test"))    # Built-in
    
    inner_function()

# Common gotcha - modifying global variables
counter = 0

def increment_counter():
    # This creates a local variable, doesn't modify global!
    counter = counter + 1  # UnboundLocalError!

def correct_increment():
    global counter  # Declare global to modify it
    counter = counter + 1
```

**2. Late binding closures:**
```python
# Gotcha - all functions reference the same variable
functions = []
for i in range(3):
    functions.append(lambda: i)  # All will return 2!

# All functions return the same value
for f in functions:
    print(f())  # Prints: 2, 2, 2

# Solution - capture the value
functions = []
for i in range(3):
    functions.append(lambda x=i: x)  # Capture current value

for f in functions:
    print(f())  # Prints: 0, 1, 2
```

**3. Mutable default arguments:**
```python
# DANGEROUS - never do this!
def add_item(item, items=[]):  # Mutable default argument
    items.append(item)
    return items

# This causes unexpected behavior:
list1 = add_item("apple")
list2 = add_item("banana")
print(list1)  # ['apple', 'banana'] - unexpected!
print(list2)  # ['apple', 'banana'] - same object!

# CORRECT way:
def add_item_safe(item, items=None):
    if items is None:
        items = []  # Create new list each time
    items.append(item)
    return items

list1 = add_item_safe("apple")
list2 = add_item_safe("banana")
print(list1)  # ['apple']
print(list2)  # ['banana']
```

## 4. Data Types

### Best Practices for Data Types

**1. Use appropriate data types for the job:**
```python
# For unique items, use sets (fast membership testing)
allowed_users = {"alice", "bob", "charlie"}
if "alice" in allowed_users:  # O(1) operation
    print("Access granted")

# For key-value pairs, use dictionaries
user_data = {
    "name": "Alice",
    "age": 25,
    "email": "alice@example.com"
}

# For ordered data that might change size, use lists
shopping_cart = ["apple", "banana", "orange"]
shopping_cart.append("grape")
```

**2. Use type hints for better code documentation:**
```python
from typing import List, Dict, Optional, Union

def process_user_data(
    user_ids: List[int],
    user_info: Dict[str, str],
    timeout: Optional[int] = None
) -> Union[str, int]:
    """
    Process user data with type hints.
    
    Args:
        user_ids: List of user identification numbers
        user_info: Dictionary containing user information
        timeout: Optional timeout in seconds
        
    Returns:
        Either success message (str) or error code (int)
    """
    if timeout is None:
        timeout = 30
    
    # Implementation here
    return "Success"
```

**3. Use decimal for financial calculations:**
```python
# DON'T do this for money:
price1 = 0.10
price2 = 0.20
total = price1 + price2
print(total)  # 0.30000000000000004 - precision error!

# DO this for money:
from decimal import Decimal

price1 = Decimal('0.10')
price2 = Decimal('0.20')
total = price1 + price2
print(total)  # 0.30 - correct!
```

### Detailed Gotchas with Data Types

**1. Floating-point precision issues:**
```python
# This is a common gotcha:
print(0.1 + 0.2)  # 0.30000000000000004
print(0.1 + 0.2 == 0.3)  # False!

# Solutions:
# 1. Use decimal module for precise calculations
from decimal import Decimal
print(Decimal('0.1') + Decimal('0.2'))  # 0.3
print(Decimal('0.1') + Decimal('0.2') == Decimal('0.3'))  # True

# 2. Use math.isclose() for comparisons
import math
print(math.isclose(0.1 + 0.2, 0.3))  # True

# 3. Round when displaying
result = 0.1 + 0.2
print(f"{result:.1f}")  # 0.3
```

**2. String mutability confusion:**
```python
# Strings are immutable in Python
text = "Hello"
# text[0] = "h"  # TypeError: 'str' object does not support item assignment

# You need to create a new string:
text = "h" + text[1:]  # "hello"

# Common gotcha with string methods:
original = "Hello World"
modified = original.replace("World", "Python")
print(original)   # "Hello World" - unchanged!
print(modified)   # "Hello Python" - new string
```

**3. Boolean evaluation gotchas:**
```python
# Truthiness can be tricky:
values = [0, "", [], {}, None, False, "0", [1], {"key": "value"}]

for value in values:
    if value:
        print(f"{repr(value)} is truthy")
    else:
        print(f"{repr(value)} is falsy")

# Output:
# 0 is falsy
# '' is falsy
# [] is falsy
# {} is falsy
# None is falsy
# False is falsy
# '0' is truthy  # Gotcha! Non-empty string is truthy
# [1] is truthy
# {'key': 'value'} is truthy

# Be explicit when you need specific checks:
if len(my_list) == 0:  # Explicit empty check
    print("List is empty")

if my_list is None:    # Explicit None check
    print("List is None")
```

**4. Integer overflow (Python 3 handles this well):**
```python
# Python 3 has arbitrary precision integers
# This won't overflow:
big_number = 2 ** 1000
print(big_number)  # Very large number, no overflow

# But be careful with memory:
# huge_number = 2 ** 1000000  # Might consume lots of memory!
```

## 5. Operators

### Best Practices for Operators

**1. Use parentheses for clarity:**
```python
# Good - clear precedence
result = (a + b) * c
condition = (user.is_admin and user.is_active) or user.is_super_user

# Avoid - relies on operator precedence knowledge
result = a + b * c  # Is this (a + b) * c or a + (b * c)?
```

**2. Use chained comparisons for readability:**
```python
# Good
if 18 <= age <= 65:
    print("Working age")

# Avoid
if age >= 18 and age <= 65:
    print("Working age")
```

**3. Use walrus operator (Python 3.8+) for cleaner code:**
```python
# Good - avoids repeated calculations
if (n := len(data)) > 10:
    print(f"List is too long ({n} elements, expected <= 10)")

# Instead of:
n = len(data)
if n > 10:
    print(f"List is too long ({n} elements, expected <= 10)")
```

### Detailed Gotchas with Operators

**1. Floor division with negative numbers:**
```python
# This can be confusing:
print(7 // 3)    # 2 (expected)
print(-7 // 3)   # -3 (not -2!)

# Why? Floor division always rounds down (towards negative infinity)
print(-7 / 3)    # -2.333...
print(-7 // 3)   # -3 (floor of -2.333...)

# If you want truncation towards zero:
print(int(-7 / 3))  # -2
```

**2. Short-circuit evaluation:**
```python
# This is a feature, but can be a gotcha:
def expensive_operation():
    print("Expensive operation called!")
    return True

# The expensive operation won't be called:
result = False and expensive_operation()  # Prints nothing
print(result)  # False

# The expensive operation will be called:
result = True and expensive_operation()   # Prints "Expensive operation called!"
print(result)  # True

# Use this pattern for safe operations:
my_list = [1, 2, 3]
# Safe way to check if list is not empty and first element exists
if my_list and my_list[0] > 0:
    print("First element is positive")

# Dangerous way:
# if my_list[0] > 0 and my_list:  # IndexError if my_list is empty!
```

**3. Identity vs Equality:**
```python
# Identity (is) vs Equality (==)
a = [1, 2, 3]
b = [1, 2, 3]
c = a

print(a == b)  # True (same content)
print(a is b)  # False (different objects)
print(a is c)  # True (same object)

# Common gotcha with small integers:
x = 1000
y = 1000
print(x is y)  # Might be False (implementation dependent)

x = 100
y = 100
print(x is y)  # Usually True (integer caching)

# Always use == for value comparison:
print([1, 2, 3] == [1, 2, 3])  # True
# Use is only for None comparison:
if value is None:  # Correct
    pass

# Avoid:
if value == None:  # Works but not recommended
    pass
```

**4. Operator precedence gotchas:**
```python
# Common confusion:
result = True or False and False
print(result)  # True (not False!)

# Why? 'and' has higher precedence than 'or'
# This is equivalent to:
result = True or (False and False)  # True or False = True

# Be explicit:
result = True or (False and False)  # Clear
# Or:
result = (True or False) and False  # False (different result)
```

## 6. Control Flow

### Best Practices for Control Flow

**1. Use early returns to reduce nesting:**
```python
# Good - early returns
def process_data(data):
    if not data:
        return "No data provided"
    
    if len(data) < 5:
        return "Not enough data"
    
    # Main processing logic here
    return "Data processed successfully"

# Avoid - deep nesting
def process_data_bad(data):
    if data:
        if len(data) >= 5:
            # Main processing logic here
            return "Data processed successfully"
        else:
            return "Not enough data"
    else:
        return "No data provided"
```

**2. Use guard clauses for error conditions:**
```python
def divide_numbers(a, b):
    # Guard clauses
    if not isinstance(a, (int, float)):
        raise TypeError("First argument must be a number")
    
    if not isinstance(b, (int, float)):
        raise TypeError("Second argument must be a number")
    
    if b == 0:
        raise ValueError("Cannot divide by zero")
    
    return a / b
```

**3. Use enumerate() instead of manual indexing:**
```python
# Good
fruits = ["apple", "banana", "cherry"]
for index, fruit in enumerate(fruits):
    print(f"{index}: {fruit}")

# Avoid
for i in range(len(fruits)):
    print(f"{i}: {fruits[i]}")
```

### Detailed Gotchas with Control Flow

**1. Modifying lists during iteration:**
```python
# DANGEROUS - modifying list during iteration
numbers = [1, 2, 3, 4, 5]
# for num in numbers:
#     if num % 2 == 0:
#         numbers.remove(num)  # This can skip elements!

# The problem:
# Iteration: [1, 2, 3, 4, 5]
# Index 0: 1 (odd) - keep
# Index 1: 2 (even) - remove -> [1, 3, 4, 5]
# Index 2: 4 (even) - remove -> [1, 3, 5]  
# Index 3: No element - iteration ends
# Result: [1, 3, 5] - 4 was skipped!

# CORRECT solutions:

# 1. Iterate over a copy:
numbers = [1, 2, 3, 4, 5]
for num in numbers[:]:  # Copy of the list
    if num % 2 == 0:
        numbers.remove(num)

# 2. List comprehension:
numbers = [1, 2, 3, 4, 5]
numbers = [num for num in numbers if num % 2 != 0]

# 3. Filter:
numbers = [1, 2, 3, 4, 5]
numbers = list(filter(lambda x: x % 2 != 0, numbers))
```

**2. Empty sequences and truthiness:**
```python
# Common gotcha with truthiness:
values = [0, "", [], {}, None, False]

# These are all falsy:
for value in values:
    if not value:
        print(f"{repr(value)} is falsy")

# But be careful with what you're checking:
def process_list(my_list):
    # This might not work as expected:
    if not my_list:  # True for [], None, or [0, 0, 0]!
        return "Empty or falsy"
    
    # Be explicit if you mean empty:
    if len(my_list) == 0:
        return "Actually empty"
    
    # Or check for None specifically:
    if my_list is None:
        return "List is None"
```

**3. For-else and while-else clauses:**
```python
# This is a lesser-known feature that can be confusing:

# For-else: else executes if loop completes normally (no break)
numbers = [1, 3, 5, 7, 9]
for num in numbers:
    if num % 2 == 0:
        print(f"Found even number: {num}")
        break
else:
    print("No even numbers found")  # This will print

# While-else: same concept
count = 0
while count < 3:
    if count == 5:  # This condition will never be true
        break
    print(count)
    count += 1
else:
    print("Loop completed normally")  # This will print

# While-else with break:
count = 0
while count < 10:
    if count == 3:
        break
    print(count)
    count += 1
else:
    print("This won't print")  # Won't execute due to break
```

**4. Exception handling in loops:**
```python
# Gotcha: exceptions break out of loops
numbers = [1, 2, 0, 4, 5]

# This will stop at the first error:
# for num in numbers:
#     print(10 / num)  # ZeroDivisionError when num is 0

# Better approach:
for num in numbers:
    try:
        result = 10 / num
        print(result)
    except ZeroDivisionError:
        print("Cannot divide by zero")
        continue  # Continue with next iteration
```

## 7. Functions

### Best Practices for Functions

**1. Follow the Single Responsibility Principle:**
```python
# Good - each function has one clear purpose
def calculate_area(length, width):
    """Calculate rectangle area."""
    return length * width

def validate_dimensions(length, width):
    """Validate that dimensions are positive."""
    if length <= 0 or width <= 0:
        raise ValueError("Dimensions must be positive")
    return True

def create_rectangle(length, width):
    """Create rectangle with validation."""
    validate_dimensions(length, width)
    area = calculate_area(length, width)
    return {"length": length, "width": width, "area": area}
```

**2. Use keyword arguments for better readability:**
```python
# Good - clear what each parameter means
def create_user(name, email, age, is_active=True, role="user"):
    return {"name": name, "email": email, "age": age, 
            "is_active": is_active, "role": role}

# Usage:
user = create_user(
    name="Alice",
    email="alice@example.com",
    age=25,
    role="admin"
)

# Avoid - unclear parameter order
# user = create_user("Alice", "alice@example.com", 25, True, "admin")
```

**3. Use *args and **kwargs for flexible APIs:**
```python
def flexible_function(required_param, *args, optional_param=None, **kwargs):
    """
    Flexible function that accepts various arguments.
    
    Args:
        required_param: A required parameter
        *args: Variable positional arguments
        optional_param: An optional parameter
        **kwargs: Variable keyword arguments
    """
    print(f"Required: {required_param}")
    print(f"Args: {args}")
    print(f"Optional: {optional_param}")
    print(f"Kwargs: {kwargs}")

# Usage:
flexible_function(
    "required",
    "arg1", "arg2", "arg3",
    optional_param="optional",
    key1="value1",
    key2="value2"
)
```

**4. Document functions properly:**
```python
def calculate_compound_interest(principal, rate, time, compound_frequency=1):
    """
    Calculate compound interest.
    
    Formula: A = P(1 + r/n)^(nt)
    
    Args:
        principal (float): Initial amount of money
        rate (float): Annual interest rate (as decimal, e.g., 0.05 for 5%)
        time (float): Time in years
        compound_frequency (int, optional): Times interest is compounded per year.
                                           Defaults to 1 (annually).
    
    Returns:
        float: Final amount after compound interest
        
    Raises:
        ValueError: If any parameter is negative
        
    Example:
        >>> calculate_compound_interest(1000, 0.05, 2)
        1102.5
    """
    if principal < 0 or rate < 0 or time < 0:
        raise ValueError("All parameters must be non-negative")
    
    amount = principal * (1 + rate/compound_frequency) ** (compound_frequency * time)
    return round(amount, 2)
```

### Detailed Gotchas with Functions

**1. Mutable default arguments (already covered but worth emphasizing):**
```python
# THE CLASSIC GOTCHA:
def add_item_bad(item, items=[]):  # NEVER do this!
    items.append(item)
    return items

# The problem:
list1 = add_item_bad("a")
list2 = add_item_bad("b")
print(list1)  # ['a', 'b'] - unexpected shared state!
print(list2)  # ['a', 'b'] - same object!

# THE CORRECT WAY:
def add_item_good(item, items=None):
    if items is None:
        items = []  # Create new list each time
    items.append(item)
    return items

list1 = add_item_good("a")
list2 = add_item_good("b")
print(list1)  # ['a']
print(list2)  # ['b']
```

**2. Late binding closures in loops:**
```python
# GOTCHA - all functions reference the same variable
functions = []
for i in range(3):
    functions.append(lambda: i)  # All will return 2!

# All functions return the same value
for f in functions:
    print(f())  # Prints: 2, 2, 2

# SOLUTION 1 - capture the value in default parameter:
functions = []
for i in range(3):
    functions.append(lambda x=i: x)  # Capture current value

for f in functions:
    print(f())  # Prints: 0, 1, 2

# SOLUTION 2 - use closure properly:
functions = []
for i in range(3):
    functions.append((lambda x: lambda: x)(i))  # Immediate invocation

for f in functions:
    print(f())  # Prints: 0, 1, 2
```

**3. Argument unpacking gotchas:**
```python
def example_function(a, b, c):
    return a + b + c

# Gotcha with *args:
args = [1, 2, 3]
result = example_function(*args)  # Works: 1 + 2 + 3 = 6

# But what if wrong number of arguments?
# args = [1, 2]  # Too few
# result = example_function(*args)  # TypeError!

# Gotcha with **kwargs:
kwargs = {"a": 1, "b": 2, "c": 3}
result = example_function(**kwargs)  # Works

# But what if missing or extra keys?
# kwargs = {"a": 1, "b": 2}  # Missing 'c'
# result = example_function(**kwargs)  # TypeError!

# kwargs = {"a": 1, "b": 2, "c": 3, "d": 4}  # Extra 'd'
# result = example_function(**kwargs)  # TypeError!
```

**4. Return value gotchas:**
```python
# Gotcha - returning mutable objects:
def get_default_list():
    return []  # Returning same list object reference?

# Actually, this is fine because each call creates a new list:
list1 = get_default_list()
list2 = get_default_list()
list1.append("item")
print(list1)  # ['item']
print(list2)  # [] - separate objects

# But this is problematic:
_default_list = []  # Global mutable object

def get_shared_list():
    return _default_list  # Returning reference to same object

list1 = get_shared_list()
list2 = get_shared_list()
list1.append("item")
print(list1)  # ['item']
print(list2)  # ['item'] - shared state!
```

**5. Recursion gotchas:**
```python
# Gotcha - recursion without proper base case:
def factorial_bad(n):
    return n * factorial_bad(n - 1)  # No base case!
# factorial_bad(5)  # RecursionError: maximum recursion depth exceeded

# Gotcha - inefficient recursion:
def fibonacci_bad(n):
    if n <= 1:
        return n
    return fibonacci_bad(n-1) + fibonacci_bad(n-2)
# fibonacci_bad(35)  # Very slow!

# Better approach - memoization:
from functools import lru_cache

@lru_cache(maxsize=None)
def fibonacci_good(n):
    if n <= 1:
        return n
    return fibonacci_good(n-1) + fibonacci_good(n-2)
# fibonacci_good(35)  # Much faster!
```

## 8. Data Structures

### Best Practices for Data Structures

**1. Choose the right data structure for the job:**
```python
# For unique items with fast membership testing: use sets
allowed_users = {"alice", "bob", "charlie"}
if "alice" in allowed_users:  # O(1) operation
    print("Access granted")

# For key-value pairs: use dictionaries
user_data = {
    "name": "Alice",
    "age": 25,
    "email": "alice@example.com"
}

# For ordered data that might change size: use lists
shopping_cart = ["apple", "banana", "orange"]
shopping_cart.append("grape")

# For fixed-size, immutable sequences: use tuples
coordinates = (10, 20)  # Coordinates won't change
rgb_color = (255, 128, 0)  # Color values are fixed
```

**2. Use list comprehensions for simple transformations:**
```python
# Good - readable and efficient
numbers = [1, 2, 3, 4, 5]
squares = [x**2 for x in numbers]
even_squares = [x**2 for x in numbers if x % 2 == 0]

# Avoid - more verbose
squares = []
for x in numbers:
    squares.append(x**2)

# But don't make list comprehensions too complex:
# Good:
filtered_data = [x for x in data if x > 0]

# Too complex - use regular loop:
# complex_result = [expensive_function(x) for x in data if condition1(x) and condition2(x) and ...]
```

**3. Use collections module for specialized data structures:**
```python
from collections import defaultdict, Counter, deque, namedtuple

# defaultdict for automatic default values:
word_count = defaultdict(int)
for word in ["apple", "banana", "apple", "cherry", "apple"]:
    word_count[word] += 1

# Counter for counting hashable objects:
from collections import Counter
text = "hello world"
char_count = Counter(text)
print(char_count)  # Counter({'l': 3, 'o': 2, 'h': 1, 'e': 1, ' ': 1, 'w': 1, 'r': 1, 'd': 1})

# deque for efficient append/pop from both ends:
from collections import deque
queue = deque([1, 2, 3])
queue.appendleft(0)  # Add to left: [0, 1, 2, 3]
queue.append(4)      # Add to right: [0, 1, 2, 3, 4]
first = queue.popleft()  # Remove from left: 0
last = queue.pop()       # Remove from right: 4

# namedtuple for lightweight classes:
from collections import namedtuple
Point = namedtuple('Point', ['x', 'y'])
p1 = Point(1, 2)
print(p1.x, p1.y)  # 1 2
```

### Detailed Gotchas with Data Structures

**1. List copying vs. reference:**
```python
# Shallow copy issues:
original = [[1, 2], [3, 4]]
copy1 = original           # Reference to same object
copy2 = original.copy()    # Shallow copy
copy3 = original[:]        # Shallow copy
copy4 = list(original)     # Shallow copy

# Modifying nested elements affects shallow copies:
original[0][0] = 999
print(copy2[0][0])  # 999 - affected by shallow copy!
print(copy3[0][0])  # 999 - affected by shallow copy!

# Deep copy for nested structures:
import copy
original = [[1, 2], [3, 4]]
deep_copy = copy.deepcopy(original)
original[0][0] = 888
print(deep_copy[0][0])  # Still 1 - not affected

# When shallow copy is sufficient:
simple_list = [1, 2, 3, 4, 5]
shallow_copy = simple_list.copy()
simple_list[0] = 999
print(shallow_copy[0])  # Still 1 - not affected (immutable elements)
```

**2. Dictionary key existence checking:**
```python
# THE RIGHT WAYS to check if key exists:
person = {"name": "Alice", "age": 25}

# Method 1: 'in' operator (recommended)
if "name" in person:
    print(person["name"])

# Method 2: get() with default
name = person.get("name", "Unknown")
print(name)  # Alice

# Method 3: get() with None check
if person.get("name") is not None:
    print(person["name"])

# THE WRONG WAYS that cause problems:

# Dangerous way - KeyError if key doesn't exist:
# print(person["email"])  # KeyError: 'email'

# Even more dangerous - checking truthiness of value:
# if person.get("age"):  # This is False for age=0!
#     print("Age exists")  # Won't print if age is 0!

# Safe access patterns:
def get_user_info(user_dict, key, default="Not provided"):
    """Safely get user info with default value."""
    return user_dict.get(key, default)

email = get_user_info(person, "email")
age = get_user_info(person, "age", 0)  # Default 0 instead of "Not provided"
```

**3. Set operations gotchas:**
```python
# Set membership testing is very fast:
allowed_users = {"alice", "bob", "charlie"}  # O(1) lookup
if "alice" in allowed_users:  # Very fast even for large sets
    print("Access granted")

# But sets are unordered:
my_set = {3, 1, 4, 1, 5, 9, 2, 6}
print(my_set)  # {1, 2, 3, 4, 5, 6, 9} - order may vary

# Set operations:
set1 = {1, 2, 3, 4, 5}
set2 = {4, 5, 6, 7, 8}

print(set1 & set2)  # {4, 5} - intersection
print(set1 | set2)  # {1, 2, 3, 4, 5, 6, 7, 8} - union
print(set1 - set2)  # {1, 2, 3} - difference
print(set1 ^ set2)  # {1, 2, 3, 6, 7, 8} - symmetric difference

# Gotcha - sets can only contain hashable items:
# my_set = {[1, 2], [3, 4]}  # TypeError: unhashable type: 'list'
# But this works:
my_set = {(1, 2), (3, 4)}  # Tuples are hashable
```

**4. Tuple gotchas:**
```python
# Single item tuple requires comma:
single_item_list = [1]      # List with one item
single_item_tuple = (1,)    # Tuple with one item
not_a_tuple = (1)           # Just parentheses around integer

print(type(single_item_list))   # <class 'list'>
print(type(single_item_tuple))  # <class 'tuple'>
print(type(not_a_tuple))        # <class 'int'>

# Tuple unpacking gotchas:
coordinates = (10, 20, 30)

# This works:
x, y, z = coordinates

# This causes ValueError:
# x, y = coordinates  # too many values to unpack

# Use * for variable number of items:
x, *rest = coordinates
print(x)     # 10
print(rest)  # [20, 30]

*start, z = coordinates
print(start) # [10, 20]
print(z)     # 30
```

**5. List slicing gotchas:**
```python
numbers = [0, 1, 2, 3, 4, 5]

# Slicing never raises IndexError:
print(numbers[10:20])  # [] - empty list, no error

# Negative indices:
print(numbers[-1])     # 5 - last element
print(numbers[-2])     # 4 - second to last

# Reversed slicing:
print(numbers[::-1])   # [5, 4, 3, 2, 1, 0] - reversed

# Slice assignment:
numbers[1:3] = [99, 88, 77]  # Replace 2 elements with 3
print(numbers)  # [0, 99, 88, 77, 3, 4, 5]

# Gotcha - slice assignment changes list length:
original = [1, 2, 3]
original[1:2] = [99, 88]  # Replace 1 element with 2
print(original)  # [1, 99, 88, 3]
```

## 9. Object-Oriented Programming

### Best Practices for OOP

**1. Follow the SOLID principles:**
```python
# Single Responsibility Principle - each class has one reason to change
class EmailValidator:
    def validate(self, email):
        return "@" in email and "." in email

class User:
    def __init__(self, name, email):
        self.name = name
        self.email = email
        self.validator = EmailValidator()
    
    def is_valid_email(self):
        return self.validator.validate(self.email)

# Open/Closed Principle - open for extension, closed for modification
class Shape:
    def area(self):
        raise NotImplementedError

class Rectangle(Shape):
    def __init__(self, width, height):
        self.width = width
        self.height = height
    
    def area(self):
        return self.width * self.height

class Circle(Shape):
    def __init__(self, radius):
        self.radius = radius
    
    def area(self):
        return 3.14159 * self.radius ** 2
```

**2. Use properties for controlled access:**
```python
class Temperature:
    def __init__(self, celsius=0):
        self._celsius = celsius
    
    @property
    def celsius(self):
        """Get temperature in Celsius."""
        return self._celsius
    
    @celsius.setter
    def celsius(self, value):
        """Set temperature in Celsius with validation."""
        if value < -273.15:
            raise ValueError("Temperature cannot be below absolute zero")
        self._celsius = value
    
    @property
    def fahrenheit(self):
        """Get temperature in Fahrenheit."""
        return self._celsius * 9/5 + 32
    
    @fahrenheit.setter
    def fahrenheit(self, value):
        """Set temperature via Fahrenheit."""
        self.celsius = (value - 32) * 5/9

# Usage:
temp = Temperature(25)
print(temp.celsius)     # 25
print(temp.fahrenheit)  # 77.0
temp.fahrenheit = 86
print(temp.celsius)     # 30.0
```

**3. Use dataclasses for simple data containers (Python 3.7+):**
```python
from dataclasses import dataclass, field
from typing import List

@dataclass
class Product:
    name: str
    price: float
    tags: List[str] = field(default_factory=list)
    in_stock: bool = True
    
    def add_tag(self, tag: str):
        self.tags.append(tag)
    
    @property
    def discount_price(self):
        return self.price * 0.9

# Usage:
product = Product("Laptop", 999.99, ["electronics", "computer"])
product.add_tag("sale")
print(product)  # Product(name='Laptop', price=999.99, tags=['electronics', 'computer', 'sale'], in_stock=True)
```

**4. Use __slots__ for memory optimization:**
```python
# Without __slots__ - each instance has __dict__
class RegularPoint:
    def __init__(self, x, y):
        self.x = x
        self.y = y

# With __slots__ - saves memory for many instances
class OptimizedPoint:
    __slots__ = ['x', 'y']
    
    def __init__(self, x, y):
        self.x = x
        self.y = y

# Benefits of __slots__:
# 1. Memory savings
# 2. Faster attribute access
# 3. Prevents creation of __dict__
# Limitations:
# 1. Can't add new attributes dynamically
# 2. No __dict__ attribute
# 3. Can complicate inheritance
```

### Detailed Gotchas with OOP

**1. `self` parameter gotchas:**
```python
class MyClass:
    def __init__(self, value):
        self.value = value
    
    def get_value(self):  # Must include self
        return self.value
    
    # Common mistake:
    # def get_value():  # Missing self - will cause TypeError!
    #     return self.value

# Gotcha - calling methods on class vs instance:
class Counter:
    def __init__(self):
        self.count = 0
    
    def increment(self):
        self.count += 1
        return self.count

# Correct way:
counter = Counter()
print(counter.increment())  # 1

# Wrong way:
# print(Counter.increment())  # TypeError: increment() missing 1 required positional argument: 'self'
```

**2. Class vs Instance variables:**
```python
class Dog:
    species = "Canis familiaris"  # Class variable
    tricks = []                   # DANGEROUS! Class variable shared by all instances
    
    def __init__(self, name):
        self.name = name          # Instance variable
    
    def add_trick(self, trick):
        self.tricks.append(trick)  # Modifying class variable!

# The problem:
buddy = Dog("Buddy")
max = Dog("Max")
buddy.add_trick("sit")
max.add_trick("roll over")
print(buddy.tricks)  # ['sit', 'roll over'] - unexpected!
print(max.tricks)    # ['sit', 'roll over'] - same list!

# The correct way:
class DogCorrect:
    species = "Canis familiaris"  # Class variable
    
    def __init__(self, name):
        self.name = name
        self.tricks = []          # Instance variable
    
    def add_trick(self, trick):
        self.tricks.append(trick)  # Modifying instance variable

buddy = DogCorrect("Buddy")
max = DogCorrect("Max")
buddy.add_trick("sit")
max.add_trick("roll over")
print(buddy.tricks)  # ['sit']
print(max.tricks)    # ['roll over']
```

**3. Inheritance gotchas:**
```python
# Gotcha - method resolution order (MRO):
class A:
    def method(self):
        print("A")

class B(A):
    def method(self):
        print("B")

class C(A):
    def method(self):
        print("C")

class D(B, C):
    pass

# Which method will be called?
d = D()
d.method()  # "B" - follows MRO: D -> B -> C -> A

# Check MRO:
print(D.__mro__)  # (<class '__main__.D'>, <class '__main__.B'>, <class '__main__.C'>, <class '__main__.A'>, <class 'object'>)

# Gotcha - calling parent methods:
class Parent:
    def __init__(self, name):
        self.name = name
    
    def greet(self):
        return f"Hello, I'm {self.name}"

class Child(Parent):
    def __init__(self, name, age):
        # Correct way:
        super().__init__(name)  # Call parent constructor
        self.age = age
        
        # Wrong way - doesn't call parent constructor:
        # Parent.__init__(name)  # This is wrong!
    
    def greet(self):
        # Call parent method:
        parent_greeting = super().greet()
        return f"{parent_greeting} and I'm {self.age} years old"
```

**4. Magic method gotchas:**
```python
class Number:
    def __init__(self, value):
        self.value = value
    
    def __eq__(self, other):
        if not isinstance(other, Number):
            return False
        return self.value == other.value
    
    def __lt__(self, other):
        if not isinstance(other, Number):
            return NotImplemented  # Important!
        return self.value < other.value
    
    def __add__(self, other):
        if not isinstance(other, Number):
            return NotImplemented  # Important!
        return Number(self.value + other.value)

# The importance of NotImplemented:
num1 = Number(5)
num2 = Number(3)

# This works:
print(num1 > num2)  # True (Python tries __lt__ with reversed arguments)

# If we returned False instead of NotImplemented:
class BadNumber:
    def __init__(self, value):
        self.value = value
    
    def __lt__(self, other):
        if not isinstance(other, BadNumber):
            return False  # Wrong!
        return self.value < other.value

bad1 = BadNumber(5)
bad2 = BadNumber(3)
print(bad1 > bad2)  # False - wrong result!
```

## 10. Modules and Packages

### Best Practices for Modules and Packages

**1. Organize code with proper module structure:**
```python
# math_utils.py - Good module structure
"""
Math utilities for common calculations.
"""

import math
from typing import List, Union

# Module-level constants
PI = 3.14159
E = 2.71828

# Module-level functions
def factorial(n: int) -> int:
    """Calculate factorial of n."""
    if n < 0:
        raise ValueError("Factorial not defined for negative numbers")
    if n == 0 or n == 1:
        return 1
    return n * factorial(n - 1)

def is_prime(n: int) -> bool:
    """Check if number is prime."""
    if n < 2:
        return False
    for i in range(2, int(math.sqrt(n)) + 1):
        if n % i == 0:
            return False
    return True

# Module-level class
class Calculator:
    """Simple calculator class."""
    
    def __init__(self):
        self.history = []
    
    def add(self, a: Union[int, float], b: Union[int, float]) -> Union[int, float]:
        result = a + b
        self.history.append(f"{a} + {b} = {result}")
        return result

# Module-level guard for direct execution
if __name__ == "__main__":
    # Code that runs when module is executed directly
    print("Math utilities module")
    print(f"Factorial of 5: {factorial(5)}")
    print(f"Is 17 prime? {is_prime(17)}")
```

**2. Use proper package structure:**
```
my_project/
├── __init__.py
├── main.py
├── utils/
│   ├── __init__.py
│   ├── math_utils.py
│   └── string_utils.py
├── models/
│   ├── __init__.py
│   └── user.py
└── tests/
    ├── __init__.py
    └── test_math_utils.py
```

**3. Use relative imports within packages:**
```python
# In my_project/utils/math_utils.py
from .string_utils import format_number  # Relative import
from ..models.user import User           # Relative import from parent

# In my_project/main.py
from utils.math_utils import Calculator  # Absolute import
from .utils.string_utils import clean_text  # Relative import
```

**4. Use __all__ to control public API:**
```python
# math_operations.py
"""
Math operations with controlled public API.
"""

__all__ = ['add', 'multiply', 'power']  # Only these are imported with "from module import *"

def add(a, b):
    """Add two numbers."""
    return a + b

def multiply(a, b):
    """Multiply two numbers."""
    return a * b

def power(base, exponent):
    """Raise base to exponent."""
    return base ** exponent

def _private_helper():  # Not in __all__
    """Private helper function."""
    pass
```

### Detailed Gotchas with Modules and Packages

**1. Circular import gotchas:**
```python
# file1.py
from file2 import function2  # This imports file2

def function1():
    return "Function 1"

function2()  # This might cause issues

# file2.py
from file1 import function1  # This imports file1 - circular import!

def function2():
    return function1() + " and Function 2"

# Solutions:
# 1. Restructure code to avoid circular dependencies
# 2. Use import inside functions:
def function2():
    from file1 import function1  # Import only when needed
    return function1() + " and Function 2"

# 3. Use importlib for dynamic imports:
import importlib

def function2():
    file1 = importlib.import_module('file1')
    return file1.function1() + " and Function 2"
```

**2. Module execution gotchas:**
```python
# my_module.py
print("Module is being imported/executed")

def my_function():
    print("Function called")

if __name__ == "__main__":
    print("Module is being run directly")
    my_function()
else:
    print("Module is being imported")

# When you run: python my_module.py
# Output: 
# Module is being imported/executed
# Module is being run directly
# Function called

# When you import: import my_module
# Output:
# Module is being imported/executed
# Module is being imported
```

**3. Import path gotchas:**
```python
# Directory structure:
# project/
#   main.py
#   utils/
#     __init__.py
#     helper.py

# In main.py - these work:
import utils.helper
from utils import helper
from utils.helper import some_function

# In utils/helper.py - relative imports:
from . import some_other_module  # Same package
from ..main import some_function  # Parent package

# Gotcha - sys.path manipulation:
import sys
import os

# Add current directory to path:
sys.path.append(os.path.dirname(__file__))

# But this can cause issues with virtual environments
# Better approach - use proper package structure
```

**4. Module caching gotchas:**
```python
# Python caches imported modules
import my_module

# Modify my_module.py file...

# This still uses the cached version:
import my_module  # No reload!

# To reload during development:
import importlib
importlib.reload(my_module)  # Forces reload

# But don't use reload() in production code!
```

## 11. File Handling

### Best Practices for File Handling

**1. Always use context managers (with statements):**
```python
# Good - automatic file closing
with open('data.txt', 'r') as file:
    content = file.read()
    # File automatically closed even if exception occurs

# Avoid - manual file handling
file = open('data.txt', 'r')
content = file.read()
file.close()  # Might not be called if exception occurs
```

**2. Handle different encodings properly:**
```python
# Specify encoding explicitly
with open('data.txt', 'r', encoding='utf-8') as file:
    content = file.read()

# For binary files:
with open('image.jpg', 'rb') as file:
    binary_data = file.read()

# Writing with encoding:
with open('output.txt', 'w', encoding='utf-8') as file:
    file.write("Hello, 世界!")  # Unicode characters
```

**3. Use pathlib for modern path handling (Python 3.4+):**
```python
from pathlib import Path

# Modern way with pathlib:
data_dir = Path('data')
file_path = data_dir / 'input.txt'

# Check if file exists:
if file_path.exists():
    with file_path.open('r') as file:
        content = file.read()

# Create directories:
output_dir = Path('output')
output_dir.mkdir(exist_ok=True)  # Don't raise error if exists

# Iterate over files:
for txt_file in Path('.').glob('*.txt'):
    print(txt_file.name)
```

**4. Handle large files efficiently:**
```python
# For large files - read line by line:
with open('large_file.txt', 'r') as file:
    for line in file:
        process_line(line.strip())

# Or read in chunks:
def read_in_chunks(file_path, chunk_size=1024):
    with open(file_path, 'r') as file:
        while True:
            chunk = file.read(chunk_size)
            if not chunk:
                break
            yield chunk

for chunk in read_in_chunks('large_file.txt'):
    process_chunk(chunk)
```

### Detailed Gotchas with File Handling

**1. File closing gotchas:**
```python
# Gotcha - file not closed on exception:
try:
    file = open('data.txt', 'r')
    data = file.read()
    risky_operation()  # If this raises exception...
    file.close()       # This won't be called!
except Exception as e:
    print(f"Error: {e}")
    # File remains open!

# Correct way with context manager:
try:
    with open('data.txt', 'r') as file:
        data = file.read()
        risky_operation()  # If this raises exception...
        # File automatically closed!
except Exception as e:
    print(f"Error: {e}")
```

**2. Text vs Binary mode gotchas:**
```python
# Text mode (default) - automatic newline conversion:
with open('data.txt', 'w') as file:
    file.write('Hello\nWorld')  # \n converted to system newline

# Binary mode - no conversion:
with open('data.bin', 'wb') as file:
    file.write(b'Hello\nWorld')  # Exact bytes written

# Gotcha - reading binary as text:
# with open('image.jpg', 'r') as file:  # Wrong!
#     data = file.read()  # UnicodeDecodeError!

# Correct:
with open('image.jpg', 'rb') as file:  # Binary mode
    data = file.read()  # Works fine
```

**3. File path gotchas:**
```python
import os

# Gotcha - platform-specific path separators:
# Windows: 'data\\file.txt'
# Unix: 'data/file.txt'

# Wrong way:
# file_path = 'data\\file.txt'  # Only works on Windows

# Better way:
file_path = os.path.join('data', 'file.txt')  # Works on all platforms

# Best way (Python 3.4+):
from pathlib import Path
file_path = Path('data') / 'file.txt'  # Modern and readable

# Gotcha - current working directory:
import os
print(os.getcwd())  # Current working directory
# File operations are relative to this directory!

# Gotcha - file permissions:
# On Unix systems:
# os.chmod('file.txt', 0o755)  # rwxr-xr-x
```

**4. File existence and permissions gotchas:**
```python
import os
from pathlib import Path

# Gotcha - race conditions:
# if os.path.exists('file.txt'):  # File might be deleted here!
#     with open('file.txt', 'r') as file:  # FileNotFoundError!

# Better approach - handle exception:
try:
    with open('file.txt', 'r') as file:
        content = file.read()
except FileNotFoundError:
    print("File not found")

# Or use pathlib:
file_path = Path('file.txt')
if file_path.exists():
    content = file_path.read_text()

# Check permissions:
if os.access('file.txt', os.R_OK):
    print("Can read file")

if os.access('file.txt', os.W_OK):
    print("Can write file")

# But note: access() can be unreliable due to ACLs
# Better to try the operation and handle exceptions
```

## 12. Error Handling

### Best Practices for Error Handling

**1. Be specific with exception handling:**
```python
# Good - handle specific exceptions
try:
    with open('data.txt', 'r') as file:
        data = file.read()
        number = int(data)
except FileNotFoundError:
    print("Data file not found")
except ValueError:
    print("Data is not a valid number")
except Exception as e:
    print(f"Unexpected error: {e}")

# Avoid - catching all exceptions blindly
# try:
#     # some code
# except:  # Don't do this!
#     pass  # Silently ignore all errors
```

**2. Use custom exceptions for application-specific errors:**
```python
class InsufficientFundsError(Exception):
    """Raised when account has insufficient funds."""
    
    def __init__(self, balance, amount):
        self.balance = balance
        self.amount = amount
        super().__init__(f"Insufficient funds: ${balance} < ${amount}")

class Account:
    def __init__(self, balance):
        self.balance = balance
    
    def withdraw(self, amount):
        if amount > self.balance:
            raise InsufficientFundsError(self.balance, amount)
        self.balance -= amount
        return self.balance

# Usage:
account = Account(100)
try:
    account.withdraw(150)
except InsufficientFundsError as e:
    print(f"Transaction failed: {e}")
    print(f"Available balance: ${e.balance}")
```

**3. Log errors appropriately:**
```python
import logging

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)

logger = logging.getLogger(__name__)

def process_data(data):
    try:
        # Process data
        result = complex_calculation(data)
        logger.info(f"Successfully processed {len(data)} items")
        return result
    except ValueError as e:
        logger.error(f"Invalid data format: {e}")
        raise  # Re-raise if needed
    except Exception as e:
        logger.critical(f"Unexpected error in process_data: {e}", exc_info=True)
        raise
```

**4. Use finally for cleanup:**
```python
def process_file(filename):
    file = None
    temp_data = []
    
    try:
        file = open(filename, 'r')
        # Process file
        for line in file:
            temp_data.append(process_line(line))
        return temp_data
    except FileNotFoundError:
        print(f"File {filename} not found")
        return None
    except Exception as e:
        print(f"Error processing file: {e}")
        return None
    finally:
        # Cleanup code - always executes
        if file:
            file.close()
        # Clean up temp data
        temp_data.clear()
        print("Cleanup completed")
```

### Detailed Gotchas with Error Handling

**1. Exception handling order gotchas:**
```python
# Gotcha - order matters in exception handling:
try:
    risky_operation()
except Exception as e:  # This catches ALL exceptions
    print("General error")
except ValueError as e:  # This will NEVER be reached!
    print("Value error")  # Unreachable code!

# Correct order - most specific first:
try:
    risky_operation()
except ValueError as e:  # Handle specific exceptions first
    print("Value error")
except FileNotFoundError as e:
    print("File not found")
except Exception as e:   # Handle general exceptions last
    print("General error")
```

**2. Exception chaining gotchas:**
```python
# Gotcha - losing original exception information:
def process_data(data):
    try:
        return int(data)
    except ValueError:
        # This loses the original exception context:
        raise RuntimeError("Data processing failed")

# Better - preserve exception chain:
def process_data_better(data):
    try:
        return int(data)
    except ValueError as e:
        # This preserves the original exception:
        raise RuntimeError("Data processing failed") from e

# Or re-raise with new context:
def process_data_best(data):
    try:
        return int(data)
    except ValueError:
        # Add context without chaining:
        raise RuntimeError(f"Failed to process data: {data}")
```

**3. Finally clause gotchas:**
```python
# Gotcha - return in try/finally:
def example():
    try:
        return "try"
    finally:
        return "finally"  # This overrides the try return!

print(example())  # "finally"

# Gotcha - exception in finally:
def example2():
    try:
        raise ValueError("try exception")
    finally:
        raise RuntimeError("finally exception")  # This masks the try exception!

# example2()  # Raises RuntimeError, not ValueError!

# Gotcha - return in except/finally:
def example3():
    try:
        raise ValueError("error")
    except ValueError:
        return "except"
    finally:
        return "finally"  # Overrides except return!

print(example3())  # "finally"
```

**4. Silent exception swallowing gotchas:**
```python
# VERY BAD - silent exception swallowing:
def bad_function():
    try:
        risky_operation()
    except:  # Catches everything, including SystemExit, KeyboardInterrupt!
        pass  # Completely ignores all errors!

# Also bad:
def also_bad():
    try:
        risky_operation()
    except Exception:  # Better than bare except, but still problematic
        pass  # Ignores all exceptions silently

# Better approaches:
def better_function():
    try:
        risky_operation()
    except SpecificException as e:
        logger.error(f"Expected error: {e}")
        # Handle the specific case appropriately

def good_function():
    try:
        risky_operation()
    except Exception as e:
        logger.exception("Unexpected error occurred")
        raise  # Re-raise to not hide the error

# If you must ignore an exception:
def acceptable_function():
    try:
        risky_operation()
    except ExpectedException:
        # We know this can happen and it's OK
        pass
    except Exception:
        logger.exception("Unexpected error")
        raise  # Don't hide unexpected errors
```

## 13. Advanced Features

### Best Practices for Advanced Features

**1. Use generators for memory efficiency:**
```python
# Good - generator for large datasets
def fibonacci_generator(limit):
    """Generate Fibonacci numbers up to limit."""
    a, b = 0, 1
    while a < limit:
        yield a
        a, b = b, a + b

# Memory efficient - only one value at a time
for num in fibonacci_generator(1000000):
    if num % 2 == 0:
        print(num)

# Compare with list approach (memory intensive):
def fibonacci_list(limit):
    """Return list of Fibonacci numbers up to limit."""
    result = []
    a, b = 0, 1
    while a < limit:
        result.append(a)
        a, b = b, a + b
    return result

# This creates a large list in memory:
# fib_list = fibonacci_list(1000000)  # Memory intensive!
```

**2. Use decorators for cross-cutting concerns:**
```python
import functools
import time
from typing import Callable, Any

def timing_decorator(func: Callable) -> Callable:
    """Decorator to time function execution."""
    @functools.wraps(func)
    def wrapper(*args, **kwargs) -> Any:
        start_time = time.time()
        result = func(*args, **kwargs)
        end_time = time.time()
        print(f"{func.__name__} took {end_time - start_time:.4f} seconds")
        return result
    return wrapper

def retry_decorator(max_attempts: int = 3) -> Callable:
    """Decorator to retry function on exception."""
    def decorator(func: Callable) -> Callable:
        @functools.wraps(func)
        def wrapper(*args, **kwargs) -> Any:
            for attempt in range(max_attempts):
                try:
                    return func(*args, **kwargs)
                except Exception as e:
                    if attempt == max_attempts - 1:
                        raise  # Re-raise on final attempt
                    print(f"Attempt {attempt + 1} failed: {e}. Retrying...")
                    time.sleep(1)
            return None
        return wrapper
    return decorator

# Usage:
@timing_decorator
@retry_decorator(max_attempts=3)
def unreliable_network_call():
    """Simulate unreliable network operation."""
    import random
    if random.random() < 0.7:  # 70% chance of failure
        raise ConnectionError("Network error")
    return "Success"
```

**3. Use context managers for resource management:**
```python
from contextlib import contextmanager
import time

@contextmanager
def timer():
    """Context manager to time code execution."""
    start = time.time()
    try:
        yield
    finally:
        end = time.time()
        print(f"Elapsed time: {end - start:.4f} seconds")

@contextmanager
def database_transaction(db_connection):
    """Context manager for database transactions."""
    transaction = db_connection.begin()
    try:
        yield transaction
        transaction.commit()
    except Exception:
        transaction.rollback()
        raise
    finally:
        transaction.close()

# Usage:
with timer():
    # Time-consuming operation
    time.sleep(1)
    print("Operation completed")

# Database example:
# with database_transaction(db_conn) as tx:
#     tx.execute("INSERT INTO users ...")
#     tx.execute("UPDATE accounts ...")
# If any operation fails, transaction is automatically rolled back
```

**4. Use dataclasses for simple data containers:**
```python
from dataclasses import dataclass, field
from typing import List, Optional
import datetime

@dataclass
class User:
    """User data class with automatic methods."""
    name: str
    email: str
    age: int
    created_at: datetime.datetime = field(default_factory=datetime.datetime.now)
    tags: List[str] = field(default_factory=list)
    is_active: bool = True
    last_login: Optional[datetime.datetime] = None
    
    def add_tag(self, tag: str) -> None:
        """Add a tag to the user."""
        if tag not in self.tags:
            self.tags.append(tag)
    
    @property
    def is_adult(self) -> bool:
        """Check if user is an adult."""
        return self.age >= 18

# Usage:
user = User("Alice", "alice@example.com", 25)
user.add_tag("premium")
print(user)  # Automatic __repr__
print(user.is_adult)  # True
```

### Detailed Gotchas with Advanced Features

**1. Generator gotchas:**
```python
# Gotcha - generators can only be consumed once:
def number_generator():
    for i in range(3):
        yield i

gen = number_generator()
print(list(gen))  # [0, 1, 2]
print(list(gen))  # [] - empty! Generator exhausted

# Solution - create new generator each time:
def get_numbers():
    return (i for i in range(3))  # Generator expression

print(list(get_numbers()))  # [0, 1, 2]
print(list(get_numbers()))  # [0, 1, 2] - fresh generator

# Gotcha - sending values to generators:
def echo_generator():
    while True:
        received = yield
        print(f"Received: {received}")

gen = echo_generator()
next(gen)  # Prime the generator
gen.send("Hello")  # Received: Hello
gen.send("World")  # Received: World

# Gotcha - exception handling in generators:
def fragile_generator():
    try:
        yield 1
        yield 2
        raise ValueError("Oops!")
        yield 3  # Never reached
    except ValueError as e:
        print(f"Caught in generator: {e}")
        yield 4

gen = fragile_generator()
print(next(gen))  # 1
print(next(gen))  # 2
# next(gen)  # ValueError raised
```

**2. Decorator gotchas:**
```python
# Gotcha - function metadata loss:
def simple_decorator(func):
    def wrapper(*args, **kwargs):
        print("Before function")
        result = func(*args, **kwargs)
        print("After function")
        return result
    return wrapper

@simple_decorator
def my_function():
    """This is my function."""
    return "Hello"

print(my_function.__name__)  # wrapper (not my_function!)
print(my_function.__doc__)   # None (not the original docstring!)

# Solution - use functools.wraps:
import functools

def better_decorator(func):
    @functools.wraps(func)  # Preserves metadata
    def wrapper(*args, **kwargs):
        print("Before function")
        result = func(*args, **kwargs)
        print("After function")
        return result
    return wrapper

@better_decorator
def my_function_better():
    """This is my function."""
    return "Hello"

print(my_function_better.__name__)  # my_function_better
print(my_function_better.__doc__)   # This is my function.

# Gotcha - decorator with arguments:
def repeat(times):  # This is called with arguments
    def decorator(func):  # This is the actual decorator
        @functools.wraps(func)
        def wrapper(*args, **kwargs):
            for _ in range(times):
                result = func(*args, **kwargs)
            return result
        return wrapper
    return decorator

@repeat(3)  # This calls repeat(3), which returns the decorator
def greet(name):
    print(f"Hello, {name}!")

greet("Alice")  # Prints "Hello, Alice!" three times
```

**3. Context manager gotchas:**
```python
# Gotcha - exception handling in __exit__:
class BadContextManager:
    def __enter__(self):
        print("Entering context")
        return self
    
    def __exit__(self, exc_type, exc_value, traceback):
        print("Exiting context")
        # If we don't return True, exceptions propagate
        # return True  # This would suppress all exceptions

# Gotcha - resource cleanup:
class ResourceContextManager:
    def __init__(self):
        self.resource = None
    
    def __enter__(self):
        print("Acquiring resource")
        self.resource = "Acquired resource"
        return self.resource
    
    def __exit__(self, exc_type, exc_value, traceback):
        print("Releasing resource")
        self.resource = None
        # Handle exceptions properly
        if exc_type is not None:
            print(f"Exception occurred: {exc_value}")
        return False  # Don't suppress exceptions

# Gotcha - nested context managers:
with ResourceContextManager() as res1:
    with ResourceContextManager() as res2:
        print(f"Using {res1} and {res2}")
        # Both resources automatically cleaned up in reverse order
```

    **4. Metaclass gotchas:**`
``python
    # Often, simpler alternatives exist:
    class SimpleSingleton:
    _instance = None
    
    def __new__(cls):
    if cls._instance is None:
    cls._instance = super().__new__(cls)
    return cls._instance
    
    # Or using a decorator:
    def singleton(cls):
    instances = {}
    def get_instance(*args, **kwargs):
    if cls not in instances:
    instances[cls] = cls(*args, **kwargs)
    return instances[cls]
    return get_instance
    
    @singleton
    class Logger:
    def __init__(self):
    self.logs = []
    ```
    
    ## 14. JSON Handling
    
    ### Best Practices for JSON Handling
    
    **1. Use proper error handling with JSON:**
    ```python
    import json
    from typing import Dict, Any
    
    def load_config(config_file: str) -> Dict[str, Any]:
    """Load configuration from JSON file with proper error handling."""
    try:
    with open(config_file, 'r', encoding='utf-8') as f:
    return json.load(f)
    except FileNotFoundError:
    print(f"Config file {config_file} not found")
    return {}
    except json.JSONDecodeError as e:
    print(f"Invalid JSON in {config_file}: {e}")
    return {}
    except Exception as e:
    print(f"Error loading config: {e}")
    return {}
    
    def save_data(data: Dict[str, Any], filename: str) -> bool:
    """Save data to JSON file with error handling."""
    try:
    with open(filename, 'w', encoding='utf-8') as f:
    json.dump(data, f, indent=2, ensure_ascii=False)
    return True
    except TypeError as e:
    print(f"Data not JSON serializable: {e}")
    return False
    except Exception as e:
    print(f"Error saving data: {e}")
    return False
    ```
    
    **2. Customize JSON serialization for complex objects:**
    ```python
    import json
    from datetime import datetime
    from decimal import Decimal
    from typing import Any
    
    class CustomJSONEncoder(json.JSONEncoder):
    """Custom JSON encoder for special types."""
    
    def default(self, obj):
    if isinstance(obj, datetime):
    return obj.isoformat()
    elif isinstance(obj, Decimal):
    return float(obj)
    elif hasattr(obj, '__dict__'):
    return obj.__dict__
    return super().default(obj)
    
    # Usage:
    data = {
    "name": "Alice",
    "created_at": datetime.now(),
    "balance": Decimal('99.99')
    }
    
    json_string = json.dumps(data, cls=CustomJSONEncoder, indent=2)
    print(json_string)
    ```
    
    **3. Validate JSON structure:**
    ```python
    import json
    from typing import Dict, Any, Optional
    
    def validate_json_structure(data: Dict[str, Any], required_keys: list) -> bool:
    """Validate that JSON data contains required keys."""
    return all(key in data for key in required_keys)
    
    def parse_user_data(json_string: str) -> Optional[Dict[str, Any]]:
    """Parse and validate user data from JSON."""
    try:
    data = json.loads(json_string)
    except json.JSONDecodeError:
    print("Invalid JSON format")
    return None
    
    required_fields = ['name', 'email', 'age']
    if not validate_json_structure(data, required_fields):
    print(f"Missing required fields: {required_fields}")
    return None
    
    # Additional validation
    if not isinstance(data['age'], int) or data['age'] < 0:
    print("Invalid age")
    return None
    
    return data
    
    # Usage:
    user_json = '{"name": "Alice", "email": "alice@example.com", "age": 25}'
    user_data = parse_user_data(user_json)
    if user_data:
    print(f"Valid user: {user_data['name']}")
    ```
    
    ### Detailed Gotchas with JSON Handling
    
    **1. JSON serialization limitations:**
    ```python
    import json
    from datetime import datetime
    from decimal import Decimal
    
    # Gotcha - not all Python objects are JSON serializable:
    data = {
    "name": "Alice",
    "created_at": datetime.now(),  # Not JSON serializable!
    "balance": Decimal('99.99'),   # Not JSON serializable!
    "tags": {"python", "json"}     # Sets are not JSON serializable!
    }
    
    # This will raise TypeError:
    # json.dumps(data)  # TypeError: Object of type datetime is not JSON serializable
    
    # Solutions:
    # 1. Convert to JSON-compatible types:
    data_safe = {
    "name": "Alice",
    "created_at": datetime.now().isoformat(),  # Convert to string
    "balance": float(Decimal('99.99')),        # Convert to float
    "tags": list({"python", "json"})          # Convert set to list
    }
    
    json_string = json.dumps(data_safe)
    print(json_string)
    
    # 2. Use custom encoder (shown above)
    ```
    
    **2. JSON parsing gotchas:**
    ```python
    import json
    
    # Gotcha - JSON is strict about syntax:
    # Invalid JSON examples:
    
    # Single quotes instead of double quotes:
    # json_string = "{'name': 'Alice'}"  # Invalid!
    # json.loads(json_string)  # JSONDecodeError
    
    # Correct:
    json_string = '{"name": "Alice"}'  # Valid
    data = json.loads(json_string)
    
    # Trailing commas:
    # json_string = '{"name": "Alice", "age": 25,}'  # Invalid!
    # json.loads(json_string)  # JSONDecodeError
    
    # Correct:
    json_string = '{"name": "Alice", "age": 25}'  # Valid
    data = json.loads(json_string)
    
    # Gotcha - JSON numbers vs Python numbers:
    json_string = '{"large_number": 123456789012345678901234567890}'
    data = json.loads(json_string)
    print(type(data['large_number']))  # <class 'int'>
        print(data['large_number'])        # 123456789012345678901234567890
        
        # But be careful with precision:
        json_string = '{"precise_number": 0.1}'
        data = json.loads(json_string)
        print(data['precise_number'])  # 0.10000000000000001 (floating point precision)
        ```
        
        **3. Unicode and encoding gotchas:**
        ```python
        import json
        
        # Gotcha - Unicode handling:
        data = {"message": "Hello, 世界!"}
        
        # Ensure proper encoding when writing to file:
        with open('unicode_data.json', 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False)  # Keep Unicode characters
        
        # Reading back:
        with open('unicode_data.json', 'r', encoding='utf-8') as f:
        loaded_data = json.load(f)
        print(loaded_data)  # {'message': 'Hello, 世界!'}
        
        # Gotcha - ASCII escaping:
        json_string = json.dumps(data, ensure_ascii=True)
        print(json_string)  # {"message": "Hello, \\u4e16\\u754c!"}
        
        json_string = json.dumps(data, ensure_ascii=False)
        print(json_string)  # {"message": "Hello, 世界!"}
        ```
        
        ## 15. Best Practices and Gotchas
        
        ### General Best Practices
        
        **1. Follow PEP 8 consistently:**
        ```python
        # Good - follows PEP 8
        def calculate_area(length: float, width: float) -> float:
        """Calculate rectangle area."""
        if length <= 0 or width <= 0:
        raise ValueError("Dimensions must be positive")
        return length * width
        
        class Rectangle:
        """Rectangle class with proper documentation."""
        
        def __init__(self, length: float, width: float):
        self.length = length
        self.width = width
        
        @property
        def area(self) -> float:
        """Calculate area."""
        return self.length * self.width
        
        # Avoid - inconsistent styling
        def calculateArea(length,width): # No spaces, no type hints
        return length*width               # No indentation consistency
        ```
        
        **2. Write comprehensive tests:**
        ```python
        import unittest
        from typing import List
        
        def bubble_sort(arr: List[int]) -> List[int]:
        """Sort array using bubble sort algorithm."""
        arr = arr.copy()  # Don't modify original
        n = len(arr)
        for i in range(n):
        for j in range(0, n - i - 1):
        if arr[j] > arr[j + 1]:
        arr[j], arr[j + 1] = arr[j + 1], arr[j]
        return arr
        
        class TestBubbleSort(unittest.TestCase):
        """Test cases for bubble_sort function."""
        
        def test_empty_list(self):
        """Test sorting empty list."""
        self.assertEqual(bubble_sort([]), [])
        
        def test_single_element(self):
        """Test sorting single element."""
        self.assertEqual(bubble_sort([1]), [1])
        
        def test_sorted_list(self):
        """Test sorting already sorted list."""
        self.assertEqual(bubble_sort([1, 2, 3]), [1, 2, 3])
        
        def test_reverse_sorted(self):
        """Test sorting reverse sorted list."""
        self.assertEqual(bubble_sort([3, 2, 1]), [1, 2, 3])
        
        def test_duplicates(self):
        """Test sorting list with duplicates."""
        self.assertEqual(bubble_sort([3, 1, 2, 1]), [1, 1, 2, 3])
        
        def test_negative_numbers(self):
        """Test sorting negative numbers."""
        self.assertEqual(bubble_sort([-1, -3, -2]), [-3, -2, -1])
        
        if __name__ == '__main__':
        unittest.main()
        ```
        
        **3. Use logging instead of print statements:**
        ```python
        import logging
        import sys
        
        # Configure logging
        logging.basicConfig(
        level=logging.INFO,
        format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        handlers=[
        logging.FileHandler('app.log'),
        logging.StreamHandler(sys.stdout)
        ]
        )
        
        logger = logging.getLogger(__name__)
        
        def process_user_data(user_id: int) -> bool:
        """Process user data with proper logging."""
        logger.info(f"Starting processing for user {user_id}")
        
        try:
        # Simulate data processing
        user_data = fetch_user_data(user_id)
        logger.debug(f"Fetched data: {user_data}")
        
        result = validate_user_data(user_data)
        if result:
        logger.info(f"Successfully processed user {user_id}")
        return True
        else:
        logger.warning(f"Validation failed for user {user_id}")
        return False
        
        except Exception as e:
        logger.error(f"Error processing user {user_id}: {e}", exc_info=True)
        return False
        
        def fetch_user_data(user_id: int) -> dict:
        """Simulate fetching user data."""
        return {"id": user_id, "name": f"User{user_id}"}
        
        def validate_user_data(user_data: dict) -> bool:
        """Simulate user data validation."""
        return user_data.get("id", 0) > 0
        ```
        
        ### Common Python Gotchas Summary
        
        **1. Mutable default arguments:**
        ```python
        # NEVER do this:
        def add_item_bad(item, items=[]):  # Dangerous!
        items.append(item)
        return items
        
        # ALWAYS do this:
        def add_item_good(item, items=None):
        if items is None:
        items = []
        items.append(item)
        return items
        ```
        
        **2. Late binding closures:**
        ```python
        # Problem:
        functions = []
        for i in range(3):
        functions.append(lambda: i)  # All return 2!
        
        # Solution:
        functions = []
        for i in range(3):
        functions.append(lambda x=i: x)  # Capture value
        ```
        
        **3. Modifying lists during iteration:**
        ```python
        # Problem:
        numbers = [1, 2, 3, 4, 5]
        # for num in numbers:
        #     if num % 2 == 0:
        #         numbers.remove(num)  # Skips elements!
        
        # Solutions:
        # 1. Iterate over copy:
        for num in numbers[:]:
        if num % 2 == 0:
        numbers.remove(num)
        
        # 2. List comprehension:
        numbers = [num for num in numbers if num % 2 != 0]
        
        # 3. Filter:
        numbers = list(filter(lambda x: x % 2 != 0, numbers))
        ```
        
        **4. Identity vs equality:**
        ```python
        # Use == for value comparison:
        [1, 2, 3] == [1, 2, 3]  # True
        
        # Use is only for None:
        value is None  # Correct
        # value == None  # Works but not recommended
        ```
        
        **5. Exception handling order:**
        ```python
        # Correct order - most specific first:
        try:
        risky_operation()
        except ValueError:      # Handle specific exceptions first
        handle_value_error()
        except FileNotFoundError: # Handle specific exceptions first
        handle_file_error()
        except Exception:       # Handle general exceptions last
        handle_general_error()
        ```
        
        ### Performance Tips
        
        **1. Use appropriate data structures:**
        ```python
        # For membership testing, use sets:
        allowed_users = {"alice", "bob", "charlie"}
        if "alice" in allowed_users:  # O(1)
        pass
        
        # Instead of lists:
        allowed_users = ["alice", "bob", "charlie"]
        if "alice" in allowed_users:  # O(n) - slower for large lists
        pass
        ```
        
        **2. Use list comprehensions:**
        ```python
        # Faster and more readable:
        squares = [x**2 for x in range(1000)]
        
        # Instead of:
        squares = []
        for x in range(1000):
        squares.append(x**2)
        ```
        
        **3. Use generator expressions for large datasets:**
        ```python
        # Memory efficient:
        sum_of_squares = sum(x**2 for x in range(1000000))
        
        # Instead of creating large list:
        # squares = [x**2 for x in range(1000000)]  # Uses lots of memory
        # sum_of_squares = sum(squares)
        ```
        
        ### Security Best Practices
        
        **1. Avoid eval() and exec():**
        ```python
        # NEVER do this with untrusted input:
        # user_input = "import os; os.system('rm -rf /')"  # Malicious code!
        # eval(user_input)  # Dangerous!
        
        # Use safer alternatives:
        import ast
        
        def safe_eval(expression):
        """Safely evaluate mathematical expressions."""
        try:
        # Only allows literals and basic operations
        return ast.literal_eval(expression)
        except (ValueError, SyntaxError):
        raise ValueError("Invalid expression")
        ```
        
        **2. Handle sensitive data properly:**
        ```python
        import os
        import getpass
        from typing import Optional
        
        def get_database_password() -> Optional[str]:
        """Get database password securely."""
        # Don't hardcode passwords:
        # password = "secret123"  # Never do this!
        
        # Use environment variables:
        password = os.getenv('DB_PASSWORD')
        if password:
        return password
        
        # Or prompt user:
        return getpass.getpass("Enter database password: ")
        
        # For storing secrets, consider using libraries like python-dotenv
        # or proper secret management systems
        ```
        
        ### Conclusion
        
        This comprehensive Python tutorial covers the essential concepts, best practices, and common gotchas you'll encounter as a Python developer. Remember:
        
        1. **Always write clean, readable code** - Python's philosophy is "readability counts"
        2. **Use proper error handling** - anticipate and handle exceptions gracefully
        3. **Follow Python conventions** - PEP 8, PEP 257, and community best practices
        4. **Test your code** - write unit tests to ensure reliability
        5. **Profile and optimize** - only optimize when you have evidence of performance issues
        
        Python is a powerful and flexible language, but with great power comes great responsibility. By following these guidelines and being aware of the common pitfalls, you'll write better, more maintainable Python code.
        