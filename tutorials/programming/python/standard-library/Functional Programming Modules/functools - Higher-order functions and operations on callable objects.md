# functools - Higher-order functions and operations on callable objects

## Overview

The `functools` module provides utilities for working with callable objects (functions, methods, etc.). It includes decorators and higher-order functions that help create optimized, reusable, and well-documented code.

## Table of Contents

1. [lru_cache - Result Caching](#lru_cache---result-caching)
2. [partial - Partial Function Application](#partial---partial-function-application)
3. [wraps - Preserve Function Metadata](#wraps---preserve-function-metadata)
4. [reduce - Cumulative Operations](#reduce---cumulative-operations)
5. [total_ordering - Comparison Methods](#total_ordering---comparison-methods)
6. [cmp_to_key - Comparison Conversion](#cmp_to_key---comparison-conversion)

---

## lru_cache - Result Caching

### Example 1: Basic LRU Cache

```python
from functools import lru_cache
import time

@lru_cache(maxsize=128)
def fibonacci(n):
    """Calculate fibonacci number - cached for performance"""
    if n < 2:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)

# First call - computes the value
start = time.time()
result1 = fibonacci(30)
time1 = time.time() - start

# Second call - uses cached result (much faster!)
start = time.time()
result2 = fibonacci(30)
time2 = time.time() - start

print(f'Fibonacci(30) = {result1}')
print(f'First call: {time1:.4f} seconds')
print(f'Cached call: {time2:.4f} seconds (speedup: {time1/time2:.0f}x)')

# View cache statistics
print(f'\nCache info: {fibonacci.cache_info()}')
# Output: CacheInfo(hits=..., misses=..., maxsize=128, currsize=...)

# Clear cache if needed
fibonacci.cache_clear()
```

### Example 2: LRU Cache with maxsize

```python
from functools import lru_cache

@lru_cache(maxsize=32)  # Keep only 32 most recent results
def expensive_operation(n):
    print(f'Computing for {n}...')
    return n * n

# These will be cached
for i in range(5):
    expensive_operation(i)

# Access cached results (no "Computing..." messages)
for i in range(5):
    expensive_operation(i)

print(f'Cache info: {expensive_operation.cache_info()}')

# Cache with unlimited size (be careful!)
@lru_cache(maxsize=None)
def unbounded_cache(x):
    return x ** 2
```

### Example 3: Cache with unhashable types

```python
from functools import lru_cache

# LRU cache only works with hashable arguments
@lru_cache(maxsize=128)
def process_data(data_tuple):
    """Process tuple of data"""
    return sum(data_tuple)

# Works with tuples (hashable)
result = process_data((1, 2, 3, 4, 5))
print(f'Result: {result}')

# This would fail with lists (not hashable):
# result = process_data([1, 2, 3, 4, 5])  # TypeError: unhashable type

# Solution: convert list to tuple first
result = process_data(tuple([1, 2, 3, 4, 5]))
print(f'Result: {result}')
```

---

## partial - Partial Function Application

### Example 4: Basic partial Application

```python
from functools import partial

def multiply(x, y):
    """Multiply two numbers"""
    return x * y

# Create new function with first argument fixed
multiply_by_5 = partial(multiply, 5)

# Use the partial function
result1 = multiply_by_5(4)      # 5 * 4 = 20
result2 = multiply_by_5(10)     # 5 * 10 = 50

print(f'5 * 4 = {result1}')
print(f'5 * 10 = {result2}')

# Another example
add = lambda x, y: x + y
add_10 = partial(add, 10)

print(f'10 + 5 = {add_10(5)}')
```

### Example 5: Partial with Multiple Fixed Arguments

```python
from functools import partial

def process_data(data, multiplier, offset, operation='add'):
    """Process data with multiplier and offset"""
    if operation == 'add':
        return [x * multiplier + offset for x in data]
    elif operation == 'multiply':
        return [x * multiplier + offset for x in data]

# Create specialized versions
double_plus_10 = partial(process_data, multiplier=2, offset=10)

# Use the partial function
result = double_plus_10([1, 2, 3, 4])
print(f'Result: {result}')

# Works well with built-in functions
from functools import partial

# Create a custom print function
print_header = partial(print, '===', sep='', end='')
print_header()
print('Data Section')
print_header()
```

### Example 6: Partial with map and filter

```python
from functools import partial

def multiply(x, y):
    return x * y

def power(base, exponent):
    return base ** exponent

# Use partial with map
multiply_by_3 = partial(multiply, 3)
numbers = [1, 2, 3, 4, 5]
results = list(map(multiply_by_3, numbers))
print(f'Multiply by 3: {results}')  # [3, 6, 9, 12, 15]

# Use partial with higher-order functions
square_all = partial(map, partial(power, exponent=2))
results = list(square_all(numbers))
print(f'Squared: {results}')  # [1, 4, 9, 16, 25]
```

---

## wraps - Preserve Function Metadata

### Example 7: Decorators Without wraps (Problem)

```python
def my_decorator(func):
    def wrapper(*args, **kwargs):
        """Wrapper function"""
        print('Before calling function')
        return func(*args, **kwargs)
    return wrapper

@my_decorator
def greet(name):
    """Greet someone"""
    return f'Hello, {name}!'

# Problem: metadata is lost
print(f'Function name: {greet.__name__}')      # wrapper (not greet!)
print(f'Docstring: {greet.__doc__}')           # Wrapper function (not original!)
print(f'Module: {greet.__module__}')
```

### Example 8: Using functools.wraps

```python
from functools import wraps

def my_decorator(func):
    @wraps(func)  # Preserves metadata
    def wrapper(*args, **kwargs):
        """Wrapper function"""
        print('Before calling function')
        return func(*args, **kwargs)
    return wrapper

@my_decorator
def greet(name):
    """Greet someone by name"""
    return f'Hello, {name}!'

# Now metadata is preserved!
print(f'Function name: {greet.__name__}')      # greet (correct!)
print(f'Docstring: {greet.__doc__}')           # Greet someone by name (correct!)

result = greet('Alice')
print(result)
```

### Example 9: Decorator with Arguments

```python
from functools import wraps

def repeat(times):
    """Decorator that repeats function execution"""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            results = []
            for _ in range(times):
                result = func(*args, **kwargs)
                results.append(result)
            return results
        return wrapper
    return decorator

@repeat(times=3)
def say_hello(name):
    """Say hello to someone"""
    return f'Hello, {name}!'

# Call decorated function
result = say_hello('Bob')
print(result)
# Output: ['Hello, Bob!', 'Hello, Bob!', 'Hello, Bob!']

print(f'Function name: {say_hello.__name__}')  # say_hello (preserved)
```

---

## reduce - Cumulative Operations

### Example 10: Basic reduce

```python
from functools import reduce

# Sum all numbers
numbers = [1, 2, 3, 4, 5]
total = reduce(lambda x, y: x + y, numbers)
print(f'Sum: {total}')  # 15

# Multiply all numbers
product = reduce(lambda x, y: x * y, numbers)
print(f'Product: {product}')  # 120

# Find maximum
maximum = reduce(lambda x, y: x if x > y else y, numbers)
print(f'Maximum: {maximum}')  # 5

# String concatenation
words = ['Hello', ' ', 'World']
sentence = reduce(lambda x, y: x + y, words)
print(f'Sentence: {sentence}')  # Hello World
```

### Example 11: reduce with Operators

```python
from functools import reduce
import operator

numbers = [1, 2, 3, 4, 5]

# Using operator module (more efficient and readable)
total = reduce(operator.add, numbers)
print(f'Sum: {total}')  # 15

product = reduce(operator.mul, numbers)
print(f'Product: {product}')  # 120

# Find max
maximum = reduce(max, numbers)
print(f'Maximum: {maximum}')  # 5

# Find min
minimum = reduce(min, numbers)
print(f'Minimum: {minimum}')  # 1
```

### Example 12: reduce with Initial Value

```python
from functools import reduce
import operator

numbers = [1, 2, 3, 4, 5]

# Sum with initial value of 10
total = reduce(operator.add, numbers, 10)
print(f'Sum with initial 10: {total}')  # 25 (10 + 1 + 2 + 3 + 4 + 5)

# Multiply with initial value of 2
product = reduce(operator.mul, numbers, 2)
print(f'Product with initial 2: {product}')  # 240 (2 * 1 * 2 * 3 * 4 * 5)

# Initial value useful for handling empty sequences
empty_list = []
result = reduce(operator.add, empty_list, 0)
print(f'Sum of empty list: {result}')  # 0 (doesn't raise error)
```

---

## total_ordering - Comparison Methods

### Example 13: Class Without total_ordering

```python
class Student:
    def __init__(self, name, grade):
        self.name = name
        self.grade = grade
    
    def __eq__(self, other):
        return self.grade == other.grade
    
    def __lt__(self, other):
        return self.grade < other.grade

# Only __eq__ and __lt__ defined, but other comparisons don't work:
student1 = Student('Alice', 90)
student2 = Student('Bob', 85)

print(student1 == student2)  # Works
print(student1 < student2)   # Works
# print(student1 > student2)   # Would be TypeError!
```

### Example 14: Using total_ordering

```python
from functools import total_ordering

@total_ordering
class Student:
    def __init__(self, name, grade):
        self.name = name
        self.grade = grade
    
    def __eq__(self, other):
        return self.grade == other.grade
    
    def __lt__(self, other):
        return self.grade < other.grade
    
    def __repr__(self):
        return f'Student({self.name}, {self.grade})'

students = [
    Student('Alice', 90),
    Student('Bob', 85),
    Student('Charlie', 95),
    Student('Diana', 88)
]

# All comparison methods now work!
print(f'Alice < Bob: {students[0] < students[1]}')
print(f'Alice > Bob: {students[0] > students[1]}')
print(f'Alice <= Bob: {students[0] <= students[1]}')
print(f'Alice >= Bob: {students[0] >= students[1]}')

# Can sort and compare
sorted_students = sorted(students)
print(f'Sorted: {sorted_students}')
# Output: [Student(Bob, 85), Student(Diana, 88), Student(Alice, 90), Student(Charlie, 95)]
```

---

## cmp_to_key - Comparison Conversion

### Example 15: Using cmp_to_key

```python
from functools import cmp_to_key

def compare_strings_by_length(str1, str2):
    """Compare two strings by their length"""
    if len(str1) < len(str2):
        return -1
    elif len(str1) > len(str2):
        return 1
    else:
        return 0

words = ['apple', 'pie', 'python', 'code', 'hello']

# Convert comparison function to key function
key_func = cmp_to_key(compare_strings_by_length)

# Sort using the comparison function
sorted_words = sorted(words, key=key_func)
print(f'Sorted by length: {sorted_words}')
# Output: ['pie', 'code', 'apple', 'hello', 'python']

# Custom comparison for complex sorting
def compare_descending(x, y):
    """Compare in descending order"""
    if x > y:
        return -1
    elif x < y:
        return 1
    else:
        return 0

numbers = [3, 1, 4, 1, 5, 9, 2, 6]
sorted_numbers = sorted(numbers, key=cmp_to_key(compare_descending))
print(f'Sorted descending: {sorted_numbers}')
# Output: [9, 6, 5, 4, 3, 2, 1, 1]
```

---

## Practical Example: Memoized Fibonacci

```python
from functools import lru_cache, wraps
import time

@lru_cache(maxsize=256)
def fibonacci(n):
    """
    Calculate fibonacci number efficiently using memoization.
    
    Args:
        n: The position in the fibonacci sequence
    
    Returns:
        The nth fibonacci number
    """
    if n < 2:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)

def measure_time(func):
    """Decorator to measure function execution time"""
    @wraps(func)
    def wrapper(*args, **kwargs):
        start = time.time()
        result = func(*args, **kwargs)
        elapsed = time.time() - start
        print(f'{func.__name__} took {elapsed:.6f} seconds')
        return result
    return wrapper

@measure_time
def calculate_fibonacci(n):
    return fibonacci(n)

# Calculate without cache first (slow)
fibonacci.cache_clear()
result = calculate_fibonacci(35)
print(f'Fibonacci(35) = {result}')

# Calculate with cache (fast)
result = calculate_fibonacci(35)
print(f'Fibonacci(35) = {result}')

# Show cache statistics
print(f'\nCache statistics:')
print(f'  Hits: {fibonacci.cache_info().hits}')
print(f'  Misses: {fibonacci.cache_info().misses}')
print(f'  Size: {fibonacci.cache_info().currsize}')
print(f'  Max size: {fibonacci.cache_info().maxsize}')
```

---

## Documentation References

- [Official Python functools Documentation](https://docs.python.org/3/library/functools.html)
- [lru_cache Documentation](https://docs.python.org/3/library/functools.html#functools.lru_cache)
- [partial Documentation](https://docs.python.org/3/library/functools.html#functools.partial)
- [wraps Documentation](https://docs.python.org/3/library/functools.html#functools.wraps)
- [reduce Documentation](https://docs.python.org/3/library/functools.html#functools.reduce)
- [total_ordering Documentation](https://docs.python.org/3/library/functools.html#functools.total_ordering)
