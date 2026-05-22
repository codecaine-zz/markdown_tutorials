# array - Efficient arrays of numeric values

The `array` module in Python provides a way to handle homogeneous sequences of basic values (characters, integers, floating-point numbers) efficiently. This is particularly useful when you need to work with large collections of numerical data and need to optimize memory usage.

Here are comprehensive code examples for various functionalities provided by the `array` module:

## Table of Contents

1. [Creating Arrays](#1-creating-arrays)
   - [Example: Create an array of integers](#example-create-an-array-of-integers)
   - [Example: Create an array of floats](#example-create-an-array-of-floats)
2. [Accessing Elements](#2-accessing-elements)
   - [Example: Accessing elements by index](#example-accessing-elements-by-index)
   - [Example: Accessing elements with out of bounds](#example-accessing-elements-with-out-of-bounds)
3. [Modifying Elements](#3-modifying-elements)
   - [Example: Modifying an element by index](#example-modifying-an-element-by-index)
4. [Appending and Extending Elements](#4-appending-and-extending-elements)
   - [Example: Appending a single element](#example-appending-a-single-element)
   - [Example: Extending with multiple elements](#example-extending-with-multiple-elements)
5. [Sorting Arrays](#5-sorting-arrays)
   - [Example: Sorting the array](#example-sorting-the-array)
6. [Converting Arrays to Lists](#6-converting-arrays-to-lists)
   - [Example: Convert array to a list](#example-convert-array-to-a-list)
7. [Concatenating Arrays](#7-concatenating-arrays)
   - [Example: Concatenating two arrays](#example-concatenating-two-arrays)
8. [Reversing an Array](#8-reversing-an-array)
   - [Example: Reversing the array](#example-reversing-the-array)
9. [Indexing with Negative Numbers](#9-indexing-with-negative-numbers)
   - [Example: Accessing elements using negative indices](#example-accessing-elements-using-negative-indices)
10. [Iterating Over Arrays](#10-iterating-over-arrays)
    - [Example: Iterating over an array using a for loop](#example-iterating-over-an-array-using-a-for-loop)
11. [Comparing Arrays](#11-comparing-arrays)
    - [Example: Comparing two arrays for equality](#example-comparing-two-arrays-for-equality)
12. [Arithmetic Operations](#12-arithmetic-operations)
    - [Example: Adding two arrays element-wise](#example-adding-two-arrays-element-wise)
13. [String Representation](#13-string-representation)
    - [Example: Formatting an array as a string](#example-formatting-an-array-as-a-string)

### 1. Creating Arrays

#### Example: Create an array of integers
```python
import array as arr

# Create an array of signed integers ('i' code indicates signed integer of at least 2 bytes)
int_array = arr.array('i', [1, 2, 3, 4, 5])
print("Integer Array:", int_array)
```

#### Example: Create an array of floats
```python
import array as arr

# Create an array of single-precision floats ('f' code indicates float of 4 bytes)
float_array = arr.array('f', [1.1, 2.2, 3.3, 4.4, 5.5])
print("Float Array:", float_array)
```

### 2. Accessing Elements

#### Example: Accessing elements by index
```python
import array as arr

# Access an element from the array
int_array = arr.array('i', [10, 20, 30, 40, 50])
print("Element at index 2:", int_array[2])  # Output: 30
```

#### Example: Accessing elements with out of bounds
```python
import array as arr

try:
    int_array = arr.array('i', [10, 20, 30])
    print("Element at index -1:", int_array[-1])  # Output: 30
    print("Element at index 5:", int_array[5])   # Raises IndexError
except IndexError as e:
    print("Caught error:", e)
```

### 3. Modifying Elements

#### Example: Modifying an element by index
```python
import array as arr

# Modify an element in the array
int_array = arr.array('i', [1, 2, 3, 4, 5])
int_array[2] = 10
print("Modified Integer Array:", int_array)
```

### 4. Appending and Extending Elements

#### Example: Appending a single element
```python
import array as arr

# Append a single element to the array
int_array = arr.array('i', [1, 2, 3])
int_array.append(4)
print("Array after appending 4:", int_array)
```

#### Example: Extending with multiple elements
```python
import array as arr

# Extend the array with another array
int_array = arr.array('i', [1, 2, 3])
other_int_array = arr.array('i', [4, 5, 6])
int_array.extend(other_int_array)
print("Array after extending:", int_array)
```

### 5. Sorting Arrays

#### Example: Sorting the array
> [!NOTE]
> Unlike standard Python lists, the `array` class does not have an in-place `.sort()` method. To sort an array, you use Python's built-in `sorted()` function and convert the resulting sorted list back into an `array`.

```python
import array as arr

# Sort an array
int_array = arr.array('i', [5, 3, 9, 1, 6])
# sorted(int_array) returns a sorted list; we convert it back to an array
sorted_array = arr.array('i', sorted(int_array))
print("Sorted Integer Array:", sorted_array)
```

### 6. Converting Arrays to Lists

#### Example: Convert array to a list
```python
import array as arr

# Convert an array to a list
int_array = arr.array('i', [1, 2, 3])
list_array = int_array.tolist()
print("Array converted to list:", list_array)
```

### 7. Concatenating Arrays

#### Example: Concatenating two arrays
```python
import array as arr

# Concatenate two integer arrays using the + operator
int_array1 = arr.array('i', [1, 2, 3])
int_array2 = arr.array('i', [4, 5, 6])
concatenated_array = int_array1 + int_array2
print("Concatenated Integer Array:", concatenated_array)
```

### 8. Reversing an Array

#### Example: Reversing the array
You can reverse an array using slicing or by calling the in-place `.reverse()` method.

```python
import array as arr

# Option 1: Reversing via slicing (returns a new array)
int_array = arr.array('i', [7, 8, 9])
reversed_array = int_array[::-1]
print("Reversed via slicing:", reversed_array)

# Option 2: In-place reverse
int_array.reverse()
print("Reversed in-place:", int_array)
```

### 9. Indexing with Negative Numbers

#### Example: Accessing elements using negative indices
```python
import array as arr

# Access elements using negative indices
int_array = arr.array('i', [10, 20, 30])
print("Element at index -1:", int_array[-1])  # Output: 30
print("Element at index -2:", int_array[-2])  # Output: 20
```

### 10. Iterating Over Arrays

#### Example: Iterating over an array using a for loop
```python
import array as arr

# Iterate over an integer array
int_array = arr.array('i', [4, 8, 12])
for element in int_array:
    print(element)
```

### 11. Comparing Arrays

#### Example: Comparing two arrays for equality
```python
import array as arr

# Compare two integer arrays for equality
int_array1 = arr.array('i', [1, 2, 3])
int_array2 = arr.array('i', [1, 2, 3])
print("Arrays are equal:", int_array1 == int_array2)  # Output: True
```

### 12. Arithmetic Operations

#### Example: Adding two arrays element-wise
> [!NOTE]
> Using the `+` operator on two arrays performs concatenation, not element-wise addition. To perform element-wise addition, you can use a list comprehension or generator expression combined with `zip()`.

```python
import array as arr

# Add two integer arrays element-wise
int_array1 = arr.array('i', [1, 2, 3])
int_array2 = arr.array('i', [4, 5, 6])

# Use zip to pair up elements and sum them element-wise
result_array = arr.array('i', (x + y for x, y in zip(int_array1, int_array2)))
print("Resulting Array after element-wise addition:", result_array)
```

### 13. String Representation

#### Example: Formatting an array as a string
```python
import array as arr

# Get string representation of the array object
int_array = arr.array('i', [7, 8, 9])
formatted_string = str(int_array)
print("Array formatted as string:", formatted_string)  # Output: array('i', [7, 8, 9])
```

These examples cover the most common functionalities of the `array` module. Each example is well-documented to help you understand how to use each method effectively in your Python programs.
