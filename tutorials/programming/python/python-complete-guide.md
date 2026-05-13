# Complete Python Tutorial: From Basics to Standard Library (Python 3.14)

This comprehensive tutorial will guide you through the entire Python language, from basic syntax to using the powerful standard library, based on the official Python 3.14 documentation.

## Table of Contents
1. [Python Basics](#python-basics)
2. [Variables and Data Types](#variables-and-data-types)
3. [Control Structures](#control-structures)
4. [Functions](#functions)
5. [Advanced Type Hints (PEP 695)](#advanced-type-hints)
6. [Data Structures](#data-structures)
7. [Modules and Packages](#modules-and-packages)
8. [File Operations](#file-operations)
9. [Standard Library Overview](#standard-library-overview)
10. [Essential Standard Library Modules](#essential-standard-library-modules)
11. [Best Practices](#best-practices)
12. [Python 3.14 New Features](#python-314-new-features)

## Python Basics {#python-basics}

### What is Python?

Python is an easy-to-learn, powerful programming language with efficient high-level data structures and a simple but effective approach to object-oriented programming. Python 3.14 continues to improve performance, type safety, and developer experience.

### Installing Python 3.14

1. Visit [python.org](https://www.python.org/)
2. Download Python 3.14 for your operating system
3. Install with default settings (make sure to add Python to PATH)
4. Verify installation: `python --version`

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

# Run with optimization flag (Python 3.14 improvement)
python -O script.py
```

## Variables and Data Types {#variables-and-data-types}

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

# Type annotations (without enforcement)
name: str = "Alice"
age: int = 25
height: float = 5.7
```

### Data Types

```python
# Numbers
integer_num = 42
float_num = 3.14
complex_num = 3 + 4j

# Strings with f-strings (improved in 3.14)
single_quote = 'Hello'
double_quote = "World"
multiline = """This is a
multiline string"""

# Enhanced f-string formatting (Python 3.14)
name = "Alice"
score = 95.5
print(f"Student: {name}, Score: {score:.1f}%")

# Booleans
is_true = True
is_false = False

# None type (represents absence of value)
empty_value = None

# Type checking with enhanced error messages (Python 3.14)
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

# Type narrowing with isinstance (improved error reporting in 3.14)
def process_value(value):
    if isinstance(value, int):
        return value * 2
    elif isinstance(value, str):
        return value.upper()
    else:
        return None
```

## Control Structures {#control-structures}

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

# Nested conditions with improved error messages
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

# Loop through list with enumerate
for index, fruit in enumerate(fruits):
    print(f"{index}: {fruit}")

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

# For-else statement (executes else if loop completes without break)
for i in range(5):
    if i == 10:
        break
else:
    print("Loop completed without break")
```

### Pattern Matching (Python 3.10+, Enhanced in 3.14)

```python
# Match statements with improved type narrowing
def handle_http_status(status):
    match status:
        case 200:
            return "OK"
        case 404:
            return "Not Found"
        case 500:
            return "Internal Server Error"
        case 400 | 401 | 403:  # Multiple patterns
            return "Client Error"
        case _:  # Default case
            return "Unknown status"

# More complex pattern matching with guards
def process_point(point):
    match point:
        case (0, 0):
            print("Origin")
        case (0, y):
            print(f"Y={y}")
        case (x, 0):
            print(f"X={x}")
        case (x, y) if x == y:
            print(f"Diagonal point: X={x}, Y={y}")
        case (x, y):
            print(f"X={x}, Y={y}")
        case _:
            raise ValueError("Not a point")

# Pattern matching with custom types
class Point:
    def __init__(self, x, y):
        self.x = x
        self.y = y

def locate_point(pt):
    match pt:
        case Point(x=0, y=0):
            print("At origin")
        case Point(x=0, y=y):
            print(f"On Y-axis at {y}")
        case Point(x=x, y=0):
            print(f"On X-axis at {x}")
        case Point(x=x, y=y):
            print(f"At ({x}, {y})")
```

## Functions {#functions}

Functions are the building blocks of Python programs. They allow you to encapsulate code into reusable blocks, making your programs more organized, maintainable, and easier to debug.

### Basic Function Definition

```python
# Simple function with no parameters
def say_hello():
    """A simple function that prints a greeting."""
    print("Hello, World!")

say_hello()  # Hello, World!

# Function with a single parameter
def greet(name):
    """Greet a person by name."""
    return f"Hello, {name}!"

message = greet("Alice")
print(message)  # Hello, Alice!

# Function with multiple parameters
def add_numbers(a, b):
    """Add two numbers and return the result."""
    return a + b

result = add_numbers(5, 3)
print(result)  # 8
```

### Function Parameters and Arguments

#### Positional Arguments
```python
def describe_person(name, age, city):
    """Describe a person using positional arguments."""
    return f"{name} is {age} years old and lives in {city}"

# Arguments must be passed in order
description = describe_person("Alice", 25, "New York")
print(description)  # Alice is 25 years old and lives in New York
```

#### Keyword Arguments
```python
def create_user(name, email, age=None, active=True):
    """Create a user with keyword arguments."""
    user = {
        'name': name,
        'email': email,
        'age': age,
        'active': active
    }
    return user

# Using keyword arguments (order doesn't matter)
user1 = create_user(name="Bob", email="bob@example.com", age=30)
user2 = create_user(email="alice@example.com", name="Alice", active=False)

print(user1)  # {'name': 'Bob', 'email': 'bob@example.com', 'age': 30, 'active': True}
print(user2)  # {'name': 'Alice', 'email': 'alice@example.com', 'age': None, 'active': False}
```

#### Default Parameters
```python
def greet_with_style(name, greeting="Hello", punctuation="!"):
    """Greet with customizable style."""
    return f"{greeting}, {name}{punctuation}"

print(greet_with_style("Alice"))                           # Hello, Alice!
print(greet_with_style("Bob", "Hi"))                       # Hi, Bob!
print(greet_with_style("Charlie", "Hey", "?"))             # Hey, Charlie?
print(greet_with_style("Diana", punctuation="!!!"))        # Hello, Diana!!!

# IMPORTANT: Mutable default arguments (common pitfall)
def add_item_bad(item, target_list=[]):  # DON'T DO THIS
    """BAD: Mutable default argument."""
    target_list.append(item)
    return target_list

# Better approach
def add_item_good(item, target_list=None):
    """GOOD: Use None and create new list inside function."""
    if target_list is None:
        target_list = []
    target_list.append(item)
    return target_list

# The correct way
list3 = add_item_good("apple")
list4 = add_item_good("banana")
print(list3)  # ['apple']
print(list4)  # ['banana']
```

### Variable-Length Arguments

#### *args - Variable Positional Arguments
```python
def sum_all(*args):
    """Sum all positional arguments."""
    print(f"Arguments received: {args}")  # args is a tuple
    return sum(args)

print(sum_all(1, 2, 3))           # 6
print(sum_all(1, 2, 3, 4, 5))     # 15
print(sum_all())                  # 0

def print_info(required_arg, *optional_args):
    """Function with required and optional arguments."""
    print(f"Required: {required_arg}")
    print(f"Optional arguments: {optional_args}")

print_info("Must have this")                    # Required: Must have this, Optional: ()
print_info("Must have this", "extra1", "extra2")  # Required: Must have this, Optional: ('extra1', 'extra2')

# Unpacking arguments with *
numbers = [1, 2, 3, 4, 5]
total = sum_all(*numbers)  # Unpacks the list
print(total)  # 15
```

#### **kwargs - Variable Keyword Arguments
```python
def create_profile(**kwargs):
    """Create a user profile from keyword arguments."""
    print(f"Keyword arguments received: {kwargs}")  # kwargs is a dictionary
    
    profile = {}
    for key, value in kwargs.items():
        profile[key] = value
    
    return profile

# Using **kwargs
profile1 = create_profile(name="Alice", age=25, city="New York")
profile2 = create_profile(name="Bob", occupation="Engineer", languages=["Python", "JavaScript"])

print(profile1)  # {'name': 'Alice', 'age': 25, 'city': 'New York'}
print(profile2)  # {'name': 'Bob', 'occupation': 'Engineer', 'languages': ['Python', 'JavaScript']}

# Unpacking dictionaries with **
user_data = {"name": "Charlie", "email": "charlie@example.com", "age": 28}
profile3 = create_profile(**user_data)
print(profile3)  # {'name': 'Charlie', 'email': 'charlie@example.com', 'age': 28}
```

#### Combining *args and **kwargs
```python
def flexible_function(required, *args, **kwargs):
    """Function that accepts all types of arguments."""
    print(f"Required argument: {required}")
    print(f"Positional arguments: {args}")
    print(f"Keyword arguments: {kwargs}")

flexible_function("must have", 1, 2, 3, name="Alice", age=25)
# Required argument: must have
# Positional arguments: (1, 2, 3)
# Keyword arguments: {'name': 'Alice', 'age': 25}

def advanced_function(a, b=10, *args, **kwargs):
    """More complex parameter combination."""
    print(f"a: {a}")
    print(f"b: {b}")
    print(f"args: {args}")
    print(f"kwargs: {kwargs}")

advanced_function(1, 2, 3, 4, 5, x=100, y=200)
# a: 1
# b: 2
# args: (3, 4, 5)
# kwargs: {'x': 100, 'y': 200}
```

### Function Decorators

#### Basic Decorators
```python
def my_decorator(func):
    """A simple decorator that adds functionality to a function."""
    def wrapper(*args, **kwargs):
        print(f"Before calling {func.__name__}")
        result = func(*args, **kwargs)
        print(f"After calling {func.__name__}")
        return result
    return wrapper

# Using decorator with @ syntax
@my_decorator
def say_hello(name):
    print(f"Hello, {name}!")
    return f"Greeted {name}"

result = say_hello("Alice")
# Before calling say_hello
# Hello, Alice!
# After calling say_hello
print(result)  # Greeted Alice
```

#### Practical Decorator Examples
```python
import time
import functools

def timer(func):
    """Decorator to measure function execution time."""
    @functools.wraps(func)  # Preserves original function metadata
    def wrapper(*args, **kwargs):
        start_time = time.perf_counter()  # More precise than time.time()
        result = func(*args, **kwargs)
        end_time = time.perf_counter()
        print(f"{func.__name__} took {end_time - start_time:.4f} seconds")
        return result
    return wrapper

def validate_types(*expected_types):
    """Decorator to validate function argument types."""
    def decorator(func):
        @functools.wraps(func)
        def wrapper(*args, **kwargs):
            # Check positional arguments
            for i, (arg, expected_type) in enumerate(zip(args, expected_types)):
                if not isinstance(arg, expected_type):
                    raise TypeError(f"Argument {i+1} must be {expected_type.__name__}, got {type(arg).__name__}")
            return func(*args, **kwargs)
        return wrapper
    return decorator

def retry(max_attempts=3):
    """Decorator to retry function execution on failure."""
    def decorator(func):
        @functools.wraps(func)
        def wrapper(*args, **kwargs):
            for attempt in range(max_attempts):
                try:
                    return func(*args, **kwargs)
                except Exception as e:
                    if attempt == max_attempts - 1:
                        raise e
                    print(f"Attempt {attempt + 1} failed: {e}. Retrying...")
        return wrapper
    return decorator

# Using decorators
@timer
@validate_types(int, int)
def add_numbers(a, b):
    """Add two integers."""
    time.sleep(0.1)  # Simulate some work
    return a + b

@retry(max_attempts=3)
def unreliable_function():
    """Function that might fail."""
    import random
    if random.random() < 0.7:  # 70% chance of failure
        raise Exception("Random failure!")
    return "Success!"

# Test the decorated functions
try:
    result = add_numbers(5, 3)
    print(f"Result: {result}")
    
    success = unreliable_function()
    print(success)
except Exception as e:
    print(f"Error: {e}")
```

### Lambda Functions (Anonymous Functions)

```python
# Basic lambda functions
square = lambda x: x ** 2
add = lambda x, y: x + y
is_even = lambda n: n % 2 == 0

print(square(5))      # 25
print(add(3, 4))      # 7
print(is_even(6))     # True

# Lambda with conditional expression
max_value = lambda a, b: a if a > b else b
print(max_value(10, 5))  # 10

# Using lambdas with built-in functions
numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

# Map: apply function to each element
squared = list(map(lambda x: x ** 2, numbers))
print(squared)  # [1, 4, 9, 16, 25, 36, 49, 64, 81, 100]

# Filter: keep elements that match condition
evens = list(filter(lambda x: x % 2 == 0, numbers))
print(evens)  # [2, 4, 6, 8, 10]

# Sorted with custom key
students = [
    {'name': 'Alice', 'grade': 85},
    {'name': 'Bob', 'grade': 90},
    {'name': 'Charlie', 'grade': 78}
]

# Sort by grade (descending)
sorted_students = sorted(students, key=lambda s: s['grade'], reverse=True)
print(sorted_students)
# [{'name': 'Bob', 'grade': 90}, {'name': 'Alice', 'grade': 85}, {'name': 'Charlie', 'grade': 78}]
```

### Function Documentation and Type Hints

```python
from typing import List, Dict, Optional, Union, Callable, Tuple, Any
import math

def calculate_statistics(numbers: List[Union[int, float]]) -> Dict[str, float]:
    """
    Calculate basic statistics for a list of numbers.
    
    Args:
        numbers: List of numeric values (int or float)
    
    Returns:
        Dictionary containing statistical measures:
        - mean: Arithmetic mean
        - median: Middle value
        - std_dev: Standard deviation
        - min_val: Minimum value
        - max_val: Maximum value
    
    Raises:
        ValueError: If the input list is empty
        TypeError: If the input contains non-numeric values
    
    Examples:
        >>> stats = calculate_statistics([1, 2, 3, 4, 5])
        >>> stats['mean']
        3.0
        >>> stats['median']
        3.0
    """
    if not numbers:
        raise ValueError("Cannot calculate statistics for empty list")
    
    if not all(isinstance(x, (int, float)) for x in numbers):
        raise TypeError("All elements must be numeric")
    
    n = len(numbers)
    mean = sum(numbers) / n
    
    # Calculate median
    sorted_nums = sorted(numbers)
    if n % 2 == 0:
        median = (sorted_nums[n//2 - 1] + sorted_nums[n//2]) / 2
    else:
        median = sorted_nums[n//2]
    
    # Calculate standard deviation
    variance = sum((x - mean) ** 2 for x in numbers) / n
    std_dev = math.sqrt(variance)
    
    return {
        'mean': mean,
        'median': median,
        'std_dev': std_dev,
        'min_val': min(numbers),
        'max_val': max(numbers)
    }

# Example usage with type hints
try:
    numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    stats = calculate_statistics(numbers)
    print(f"Statistics: {stats}")
    
except (ValueError, TypeError) as e:
    print(f"Error: {e}")
```

## Advanced Type Hints (PEP 695) {#advanced-type-hints}

Python 3.14 introduces powerful new syntax for type parameters and type aliases using the `type` statement.

### Type Aliases

```python
# Old way (still works)
from typing import TypeAlias
Coordinate: TypeAlias = tuple[int, int]

# New way with PEP 695 (Python 3.12+, improved in 3.14)
type Coordinate = tuple[int, int]
type UserID = int
type Email = str

def get_location() -> Coordinate:
    return (10, 20)

def create_user(user_id: UserID, email: Email) -> dict:
    return {"id": user_id, "email": email}
```

### Generic Type Parameters (PEP 695)

```python
# Define generic types with type parameters
type Stack[T] = list[T]
type Pair[T, U] = tuple[T, U]

# Generic functions with the new syntax
def first[T](items: list[T]) -> T:
    """Get the first item from a list."""
    if not items:
        raise IndexError("List is empty")
    return items[0]

def pair_map[T, U](items: list[T], func: callable[[T], U]) -> Pair[T, U]:
    """Create a pair of original and transformed items."""
    return (items[0], func(items[0]))

# Generic classes with the new syntax
class Container[T]:
    """A generic container for storing values."""
    def __init__(self, value: T):
        self.value = value
    
    def get(self) -> T:
        return self.value
    
    def set(self, value: T) -> None:
        self.value = value

# Usage
numbers = [1, 2, 3, 4, 5]
print(first(numbers))  # 1

strings = ["hello", "world"]
print(first(strings))  # "hello"

container = Container[int](42)
print(container.get())  # 42

container.set(100)
print(container.get())  # 100
```

### Constrained Type Parameters

```python
# Type parameters with constraints (Python 3.14)
type Numeric = int | float
type Stringifiable = str | int | float

def process_numeric[T: int | float](value: T) -> T:
    """Process numeric values with type constraint."""
    return value * 2

# This works
print(process_numeric(5))      # 10
print(process_numeric(2.5))    # 5.0

# Type parameters with bounds
class Comparable[T: int | float | str]:
    """Generic class with bounded type parameter."""
    def __init__(self, value: T):
        self.value = value
    
    def compare(self, other: T) -> bool:
        return self.value < other
```

## Data Structures {#data-structures}

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

# List comprehensions
squares = [x**2 for x in range(10)]
print(squares)  # [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]

# With conditions
even_squares = [x**2 for x in range(10) if x % 2 == 0]
print(even_squares)  # [0, 4, 16, 36, 64]

# Nested comprehensions
matrix = [[i*j for j in range(3)] for i in range(3)]
print(matrix)  # [[0, 0, 0], [0, 1, 2], [0, 2, 4]]
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

# Tuple methods
my_tuple = (1, 2, 3, 2, 1)
print(my_tuple.count(2))  # 1
print(my_tuple.index(2))  # 1
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
print(person.get("email", "not provided"))  # not provided (default)

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

# Merging dictionaries (Python 3.9+)
dict1 = {"a": 1, "b": 2}
dict2 = {"c": 3, "d": 4}
merged = dict1 | dict2  # {a: 1, b: 2, c: 3, d: 4}

# Update merge (Python 3.9+)
dict1 |= {"e": 5}  # dict1 now includes e: 5
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

# Set comprehensions
a = {x for x in 'abracadabra' if x not in 'abc'}
print(a)  # {'r', 'd'}

# Check subset and superset
set_a = {1, 2}
set_b = {1, 2, 3, 4}
print(set_a.issubset(set_b))    # True
print(set_b.issuperset(set_a))  # True
```

## Modules and Packages {#modules-and-packages}

### Creating and Using Modules

```python
# fibo.py - A custom module
# Fibonacci numbers module

def fib(n):
    """Write Fibonacci series up to n."""
    a, b = 0, 1
    while a < n:
        print(a, end=' ')
        a, b = b, a+b
    print()

def fib2(n):
    """Return Fibonacci series up to n."""
    result = []
    a, b = 0, 1
    while a < n:
        result.append(a)
        a, b = b, a+b
    return result
```

```python
# main.py - Using the custom module
import fibo

fibo.fib(1000)
result = fibo.fib2(100)
print(result)

# Alternative import methods
from fibo import fib, fib2
fib(500)

from fibo import *
fib(500)

import fibo as f
f.fib(500)
```

## File Operations {#file-operations}

### Reading Files

```python
# Basic file reading with context manager
try:
    with open("example.txt", "r", encoding="utf-8") as file:
        content = file.read()
        print(content)
except FileNotFoundError:
    print("File not found")

# Reading line by line
with open("example.txt", "r", encoding="utf-8") as file:
    for line in file:
        print(line.strip())  # strip() removes newline characters

# Reading all lines into a list
with open("example.txt", "r", encoding="utf-8") as file:
    lines = file.readlines()
    print(lines)
```

### Writing Files

```python
# Writing to files
with open("output.txt", "w", encoding="utf-8") as file:
    file.write("Hello, World!\n")
    file.write("This is a new line")

# Writing multiple lines
lines = ["Line 1\n", "Line 2\n", "Line 3\n"]
with open("output.txt", "w", encoding="utf-8") as file:
    file.writelines(lines)

# Appending to files
with open("output.txt", "a", encoding="utf-8") as file:
    file.write("\nThis line is appended")

# Writing with context manager (recommended)
def write_data_to_file(filename, data):
    """Write data to a file safely."""
    try:
        with open(filename, "w", encoding="utf-8") as file:
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

# Using pathlib (modern approach - recommended)
current_path = Path(".")
print(current_path.resolve())  # Absolute path

# Creating paths
file_path = Path("data") / "input.txt"
print(file_path)  # data/input.txt

# Check if path exists
if file_path.exists():
    print("Path exists")

# File methods
with open("workfile", "w", encoding="utf-8") as f:
    f.write("This is a test\n")
    value = ('the answer', 42)
    s = str(value)
    f.write(s)

# Reading with different methods
with open("workfile", encoding="utf-8") as f:
    # Read entire file
    content = f.read()
    
    # Read line by line
    f.seek(0)  # Go back to beginning
    line = f.readline()
    
    # Read all lines
    f.seek(0)
    lines = f.readlines()
```

## Standard Library Overview {#standard-library-overview}

Python's standard library is extensive and provides powerful functionality without requiring external installations. These modules are built into Python and can be used immediately.

### Key Modules Categories

1. **Core Modules**: Basic functionality (os, sys, datetime)
2. **Data Types**: Advanced data structures (collections, json, xml)
3. **File and I/O**: File operations (io, pathlib)
4. **Networking**: Internet protocols (http, socket)
5. **Database**: SQL database access (sqlite3)
6. **Mathematics**: Mathematical operations (math, random, statistics)
7. **System**: System-specific interfaces (platform, subprocess)
8. **Text Processing**: String operations (re, textwrap)

## Essential Standard Library Modules {#essential-standard-library-modules}

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
print(os.environ.get("HOME", "home_not_set"))
print(os.environ.get("PATH"))

# System information
print(f"Operating system: {os.name}")
```

### 2. `sys` Module - System-specific Parameters

```python
import sys

# Get Python version
print(f"Python version: {sys.version}")
print(f"Version info: {sys.version_info}")

# Get command line arguments
print(f"Command line arguments: {sys.argv}")

# Get path to Python executable
print(f"Python executable: {sys.executable}")

# Get system max recursion limit
print(f"Recursion limit: {sys.getrecursionlimit()}")

# Module count
print(f"Module count: {len(sys.modules)}")

# Platform information
print(f"Platform: {sys.platform}")
```

### 3. `datetime` Module - Date and Time Operations

```python
from datetime import datetime, date, time, timedelta
import calendar

# Current date and time
now = datetime.now()
print(f"Current datetime: {now}")

# Creating datetime objects
specific_date = datetime(2025, 12, 25, 14, 30, 0)
print(f"Specific date: {specific_date}")

# Date only
today = date.today()
print(f"Today's date: {today}")

# Time only
current_time = time(14, 30, 45)
print(f"Current time: {current_time}")

# Formatting dates (improved in Python 3.14)
formatted = now.strftime("%Y-%m-%d %H:%M:%S")
print(f"Formatted date: {formatted}")

# Parsing dates
date_string = "2025-12-25"
parsed_date = datetime.strptime(date_string, "%Y-%m-%d")
print(f"Parsed date: {parsed_date}")

# Date arithmetic
future_date = now + timedelta(days=30)
past_date = now - timedelta(weeks=2)

print(f"30 days from now: {future_date}")
print(f"2 weeks ago: {past_date}")

# Calendar operations
print(f"Days in December 2025: {calendar.monthrange(2025, 12)[1]}")
```

### 4. `collections` Module - Advanced Data Structures

```python
from collections import Counter, defaultdict, namedtuple, deque, OrderedDict

# Counter - Count elements in iterable
text = "hello world"
char_count = Counter(text)
print(f"Character count: {char_count}")
print(f"Most common characters: {char_count.most_common(3)}")

# defaultdict - Default values for missing keys
dd = defaultdict(list)
dd['key1'].append('value1')
dd['key2'].append('value2')
print(f"Default dict: {dict(dd)}")

# namedtuple - Create tuple-like objects with named fields
Point = namedtuple('Point', ['x', 'y', 'z'])
p1 = Point(1, 2, 3)
p2 = Point(3, 4, 5)

print(f"Point p1: {p1}")
print(f"Point p1 x-coordinate: {p1.x}")

# deque - Double-ended queue with efficient operations
dq = deque([1, 2, 3])
dq.appendleft(0)    # Add to left
dq.append(4)        # Add to right
print(f"Deque: {list(dq)}")

dq.popleft()        # Remove from left
dq.pop()            # Remove from right
print(f"Deque after removals: {list(dq)}")

# OrderedDict - Dictionary that maintains insertion order (Python 3.7+ dicts are ordered by default)
od = OrderedDict([('first', 1), ('second', 2), ('third', 3)])
print(f"Ordered dict: {od}")
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
with open("data.json", "w", encoding="utf-8") as file:
    json.dump(data, file, indent=2)

# Reading from file
with open("data.json", "r", encoding="utf-8") as file:
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
import math

# Generate random numbers
print(f"Random float: {random.random()}")  # Between 0.0 and 1.0
print(f"Random integer: {random.randint(1, 10)}")  # Between 1 and 10
print(f"Random float in range: {random.uniform(1.5, 3.5)}")

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
print(f"Random with seed 42: {random.random()}")
random.seed(42)
print(f"Same seed produces same result: {random.random()}")
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
if re.search(r'\d{4}', "Year 2025"):
    print("Found year pattern")

# Replace text
new_text = re.sub(r'\d{4}', 'XXXX', "The year is 2025")
print(f"Replaced text: {new_text}")

# Splitting with regex
sentence = "Hello, world! How are you?"
words = re.split(r'[,\s!?]+', sentence)
print(f"Split words: {words}")

# Pattern groups
date_pattern = r'(\d{2})/(\d{2})/(\d{4})'
date_text = "Today is 12/25/2025"
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
print(f"Tau (2π): {math.tau}")

# Rounding functions
print(f"Ceiling of 3.2: {math.ceil(3.2)}")
print(f"Floor of 3.2: {math.floor(3.2)}")

# Factorial and combinations
print(f"Factorial of 5: {math.factorial(5)}")
print(f"Combinations (5 choose 2): {math.comb(5, 2)}")
print(f"Permutations (5 P 2): {math.perm(5, 2)}")

# Hyperbolic functions
print(f"Hyperbolic sine of 1: {math.sinh(1)}")

# GCD and LCM
print(f"GCD of 48 and 18: {math.gcd(48, 18)}")
print(f"LCM of 12 and 18: {math.lcm(12, 18)}")
```

### 9. `statistics` Module - Statistical Functions (Python 3.4+)

```python
import statistics

# Basic statistics
data = [1, 2, 3, 4, 5, 100]

# Mean (average)
print(f"Mean: {statistics.mean(data)}")  # 19.166...

# Median (middle value)
print(f"Median: {statistics.median(data)}")  # 3.5

# Mode (most frequent value)
try:
    print(f"Mode: {statistics.mode([1, 1, 2, 3, 3, 3])}")  # 3
except statistics.StatisticsError:
    print("No unique mode found")

# Standard deviation
print(f"Standard deviation: {statistics.stdev(data)}")

# Quantiles (Python 3.8+)
print(f"Quantiles: {statistics.quantiles(data, n=4)}")  # quartiles
```

### 10. `sqlite3` Module - SQLite Database Access

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
```

### 11. `pathlib` Module - Object-Oriented File System Paths

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
```

### 12. `logging` Module - Logging System

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
```

### 13. `unittest` Module - Unit Testing Framework

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

# Running tests
if __name__ == '__main__':
    unittest.main(verbosity=2)
```

## Python 3.14 New Features {#python-314-new-features}

### Per-Interpreter GIL (Global Interpreter Lock)

Python 3.14 introduces per-interpreter GIL support, allowing true parallelism with multiple Python interpreters.

```python
# This is experimental in Python 3.14
# Per-interpreter GIL allows running multiple Python interpreters
# without the Global Interpreter Lock blocking between them

# Note: This requires building Python with specific flags
# Traditional threading still exists with the GIL

import threading
import time

def worker(name):
    for i in range(3):
        print(f"{name}: {i}")
        time.sleep(0.1)

# Create threads
threads = []
for i in range(2):
    t = threading.Thread(target=worker, args=(f"Thread-{i}",))
    threads.append(t)
    t.start()

# Wait for all threads to complete
for t in threads:
    t.join()

print("All threads completed")
```

### Enhanced Error Messages

Python 3.14 provides more detailed and helpful error messages with suggestions.

```python
# Example: Improved AttributeError messages
class Person:
    def __init__(self, name):
        self.name = name

p = Person("Alice")

# In Python 3.14, this error message is more helpful:
try:
    print(p.nam)  # Typo: should be p.name
except AttributeError as e:
    print(f"Error: {e}")
    # Python 3.14 suggests: Did you mean 'name'?

# Example: Improved TypeError messages
def greet(name: str) -> str:
    return f"Hello, {name}!"

try:
    greet(123)  # Type mismatch
except TypeError as e:
    print(f"Error: {e}")
    # More helpful in Python 3.14
```

### Improved Performance

Python 3.14 includes various performance improvements:

```python
import time

# Adaptive specialization makes code faster
def fibonacci(n):
    """Fibonacci function - runs faster in Python 3.14."""
    if n < 2:
        return n
    return fibonacci(n-1) + fibonacci(n-2)

# Measure performance
start = time.perf_counter()
result = fibonacci(30)
end = time.perf_counter()

print(f"Result: {result}")
print(f"Time taken: {end - start:.4f} seconds")
print("(Runs significantly faster in Python 3.14 with adaptive specialization)")
```

### New `sys.monitoring` API

Python 3.14 enhances the monitoring capabilities for profiling and debugging:

```python
import sys

# Access monitoring information
print(f"Python version: {sys.version_info}")
print(f"Implementation: {sys.implementation}")

# Check if monitoring is available
if hasattr(sys, 'monitoring'):
    print("Monitoring API is available")
    # Can be used for advanced profiling
```

### Improved Type Checking with TypeVar Bounds

```python
from typing import TypeVar

# TypeVar with better constraints (Python 3.14)
T = TypeVar('T', int, float, str)  # Constrain to specific types

def process[T: int | float | str](value: T) -> T:
    """Process value constrained to numeric or string types."""
    if isinstance(value, (int, float)):
        return value * 2
    else:
        return value.upper()

print(process(5))        # 10
print(process(2.5))      # 5.0
print(process("hello"))  # HELLO
```

### New Standard Library Enhancements

Python 3.14 adds several enhancements to standard library modules:

```python
# Enhanced pathlib with more convenience methods
from pathlib import Path

# New methods for file operations
path = Path("example.txt")

# Check if path is relative
print(f"Is relative: {path.is_relative_to(Path('.'))}")

# More efficient operations
# (specific implementations available in Python 3.14)

# Enhanced statistics module
import statistics

data = [1, 2, 2, 3, 3, 3, 4, 4, 4, 4]

# Better handling of multimodal data
print(f"Data: {data}")
try:
    mode = statistics.mode(data)
    print(f"Mode: {mode}")
except statistics.StatisticsError as e:
    print(f"Multiple modes or no mode: {e}")
```

## Best Practices and Tips {#best-practices}

### 1. Use Type Hints (PEP 484, Enhanced in 3.14)

```python
from typing import List, Dict, Optional, Union

# Good: Use type hints
def calculate_average(scores: List[float]) -> float:
    """Calculate average of scores."""
    if not scores:
        return 0.0
    return sum(scores) / len(scores)

# Use PEP 695 style (Python 3.12+)
type Score = float
type Scores = list[Score]

def calculate_average_new(scores: Scores) -> Score:
    """Calculate average using new type syntax."""
    if not scores:
        return 0.0
    return sum(scores) / len(scores)
```

### 2. Use the Standard Library First

```python
# Instead of writing your own implementations, use the standard library
import os
import json
from pathlib import Path
from datetime import datetime
from collections import Counter, defaultdict

# Use pathlib for path operations (modern approach)
config_path = Path.home() / ".config" / "app.conf"

# Use datetime for date/time
now = datetime.now()

# Use collections for data structures
word_counts = Counter(["apple", "banana", "apple"])
```

### 3. Handle Exceptions Properly

```python
# Bad: Too broad exception handling
def bad_file_read(filename):
    try:
        with open(filename) as f:
            return f.read()
    except:  # Never do this!
        return None

# Good: Specific exception handling
def good_file_read(filename):
    try:
        with open(filename, 'r', encoding='utf-8') as f:
            return f.read()
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

### 4. Use Context Managers

```python
# Always use context managers for resource management
with open("file.txt", "r", encoding="utf-8") as f:
    content = f.read()
# File is automatically closed

# For database connections
import sqlite3
with sqlite3.connect("database.db") as conn:
    cursor = conn.cursor()
    # Operations here
# Connection is automatically closed
```

### 5. Follow PEP 8 Style Guide

```python
# Good: Follow PEP 8
variable_name = "snake_case_for_variables"
CONSTANT_VALUE = "UPPER_CASE_FOR_CONSTANTS"

def function_name(arg1: str, arg2: int) -> str:
    """Function with proper documentation."""
    return f"{arg1}: {arg2}"

class ClassName:
    """Class with proper documentation."""
    pass

# Good: Proper indentation (4 spaces)
if True:
    print("Proper indentation")

# Good: Line length (maximum 79 characters recommended)
long_string = ("This is a very long string that should be broken "
               "into multiple lines for readability")
```

### 6. Write Comprehensive Documentation

```python
def calculate_statistics(numbers: list[float]) -> dict[str, float]:
    """
    Calculate basic statistics for a list of numbers.
    
    This function computes mean, median, standard deviation,
    minimum, and maximum values for a given list of numbers.
    
    Args:
        numbers: List of numeric values (int or float)
    
    Returns:
        Dictionary containing statistical measures:
        - mean: Arithmetic mean
        - median: Middle value
        - std_dev: Standard deviation
        - min_val: Minimum value
        - max_val: Maximum value
    
    Raises:
        ValueError: If the input list is empty
        TypeError: If the input contains non-numeric values
    
    Examples:
        >>> stats = calculate_statistics([1, 2, 3, 4, 5])
        >>> stats['mean']
        3.0
        >>> stats['median']
        3.0
        >>> stats['std_dev']  # doctest: +ELLIPSIS
        1.41...
    """
    if not numbers:
        raise ValueError("Cannot calculate statistics for empty list")
    
    import math
    
    if not all(isinstance(x, (int, float)) for x in numbers):
        raise TypeError("All elements must be numeric")
    
    n = len(numbers)
    mean = sum(numbers) / n
    
    # Calculate median
    sorted_nums = sorted(numbers)
    if n % 2 == 0:
        median = (sorted_nums[n//2 - 1] + sorted_nums[n//2]) / 2
    else:
        median = sorted_nums[n//2]
    
    # Calculate standard deviation
    variance = sum((x - mean) ** 2 for x in numbers) / n
    std_dev = math.sqrt(variance)
    
    return {
        'mean': mean,
        'median': median,
        'std_dev': std_dev,
        'min_val': min(numbers),
        'max_val': max(numbers)
    }
```

### 7. Use Comprehensions for Clarity

```python
# List comprehension (clear and efficient)
squares = [x**2 for x in range(10)]

# Dictionary comprehension
user_ages = {name: age for name, age in [("Alice", 25), ("Bob", 30)]}

# Set comprehension
unique_chars = {c for c in "hello"}

# Generator expression (memory efficient for large data)
sum_of_squares = sum(x**2 for x in range(1000000))
```

### 8. Leverage Python's Built-in Functions

```python
# Use built-in functions instead of loops
data = [3, 1, 4, 1, 5, 9, 2, 6]

# Good: Use built-ins
minimum = min(data)
maximum = max(data)
total = sum(data)
count = len(data)
sorted_data = sorted(data)

# Good: Use enumerate for indexed iteration
for index, value in enumerate(data):
    print(f"{index}: {value}")

# Good: Use zip for parallel iteration
names = ["Alice", "Bob", "Charlie"]
ages = [25, 30, 35]
for name, age in zip(names, ages):
    print(f"{name} is {age} years old")
```

## Conclusion

This comprehensive tutorial covered:

1. **Python Fundamentals**: Syntax, variables, data types, and control structures
2. **Functions**: Definition, parameters, decorators, and advanced techniques
3. **Advanced Type Hints**: PEP 695 type parameters for better code clarity
4. **Data Structures**: Lists, tuples, dictionaries, and sets
5. **Modules and Packages**: Creating and using reusable code
6. **File Operations**: Reading, writing, and path handling
7. **Standard Library**: Extensive built-in modules for practical programming
8. **Python 3.14 Features**: Latest enhancements including per-interpreter GIL, better error messages, and performance improvements

### Key Takeaways

- **Use Type Hints**: Improve code clarity and catch errors early with PEP 695 syntax
- **Use the Standard Library**: Most functionality you need is already available
- **Follow PEP 8**: Maintain consistency and readability in your code
- **Handle Exceptions Properly**: Be specific about what exceptions you catch
- **Use Context Managers**: Always manage resources properly
- **Write Documentation**: Clear docstrings help you and other developers
- **Leverage Python's Built-ins**: Use built-in functions and comprehensions for clean, efficient code
- **Stay Updated**: Python 3.14 brings performance improvements and new features to explore

### Resources

For official documentation and tutorials, visit:
- [Python Official Documentation](https://docs.python.org/3.14/)
- [PEP Index](https://www.python.org/dev/peps/)
- [Python Enhancement Proposals](https://peps.python.org/)

By mastering these concepts and best practices, you'll be well-equipped to write efficient, maintainable, and professional Python code!
