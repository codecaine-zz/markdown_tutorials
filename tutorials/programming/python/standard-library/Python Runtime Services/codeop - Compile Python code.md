# codeop - Compile Python code
## Table of Contents

1. [Example 1: Basic Compilation and Execution](#example-1-basic-compilation-and-execution)
2. [Example 2: Compiling Source Code with Specific Mode](#example-2-compiling-source-code-with-specific-mode)
3. [Example 3: Compiling Source Code into Bytecode Without Executing](#example-3-compiling-source-code-into-bytecode-without-executing)
4. [Example 4: Handling Syntax Errors Gracefully](#example-4-handling-syntax-errors-gracefully)
5. [Example 5: Compiling and Executing Dynamically](#example-5-compiling-and-executing-dynamically)
6. [Example 6: Using `compile_command` with Incomplete Code](#example-6-using-compile_command-with-incomplete-code)
7. [Example 7: Using `compile` for File Operations](#example-7-using-compile-for-file-operations)



The `codeop` module in Python provides tools to compile Python source code. It's particularly useful for situations where you need to handle incomplete code or interactive shell-like environments where code may be entered line by line.

Below are some comprehensive examples that demonstrate various functionalities provided by the `codeop` module:

### Example 1: Basic Compilation and Execution

```python
import codeop

# Define a Python source code string
source_code = "def greet(name):\n    print(f'Hello, {name}!')\n\ngreet('Alice')"

# Compile the source code into a code object
try:
    code_obj = codeop.compile_command(source_code)
    if code_obj is not None:
        # Execute the compiled code
        exec(code_obj)  # Output: Hello, Alice!
except SyntaxError as e:
    print(f"Syntax error: {e}")
```

### Example 2: Compiling Source Code with Specific Mode

```python
import codeop

# Define a Python source code string
source_code = "def add(x, y):\n    return x + y"

# Compile the source code into a code object using 'exec' mode
try:
    code_obj = compile(source_code, '<string>', 'exec')
    
    # Execute the compiled code to define the function
    exec(code_obj)
    
    # Call the function and display output
    result = add(3, 5)
    print(result)  # Output: 8
except SyntaxError as e:
    print(f"Syntax error: {e}")
```

### Example 3: Compiling Source Code into Bytecode Without Executing

```python
import codeop

# Define a Python source code string
source_code = "def multiply(x, y):\n    return x * y"

# Compile the source code into a code object
try:
    code_obj = codeop.compile_command(source_code)
    
    # The result is a code object that can be inspected
    print(f"Code object: {code_obj}")
    print(f"Code object type: {type(code_obj)}")
except SyntaxError as e:
    print(f"Syntax error: {e}")
```

### Example 4: Handling Syntax Errors Gracefully

```python
import codeop

# Define Python source code strings, some with errors
source_codes = [
    "def divide(x, y):\n    return x / y",  # Valid code
    "def broken(\n    pass",  # Incomplete code (returns None)
    "def invalid(:\n    pass",  # Syntax error
]

for i, code in enumerate(source_codes):
    try:
        # Attempt to compile the source code
        code_obj = codeop.compile_command(code)
        if code_obj is None:
            print(f"Code {i+1}: Incomplete code (waiting for more input)")
        else:
            print(f"Code {i+1}: Successfully compiled")
            exec(code_obj)
    except SyntaxError as e:
        print(f"Code {i+1} - Compilation error: {e}")
```

### Example 5: Compiling and Executing Dynamically

```python
import codeop

# Define a function to compile and execute Python source code dynamically
def run_python_code(code):
    try:
        # Compile the source code into a code object
        code_obj = codeop.compile_command(code)
        
        if code_obj is None:
            print("Incomplete code (waiting for more input)")
            return None
        
        # Execute the compiled code object
        namespace = {}
        exec(code_obj, namespace)
        return namespace
    except SyntaxError as e:
        print(f"Compilation error: {e}")
        return None

# Example usage of the run_python_code function
code_to_run = "def square(x):\n    return x * x\n\nresult = square(4)"
namespace = run_python_code(code_to_run)
if namespace:
    print(f"Result: {namespace.get('result')}")  # Output: Result: 16
```

### Example 6: Using `compile_command` with Incomplete Code

```python
import codeop

# Test incomplete vs complete code
incomplete_code = "def factorial(n):\n    if n == 0:\n        return 1"
complete_code = "def factorial(n):\n    if n == 0:\n        return 1\n    return n * factorial(n-1)"

# Try compiling incomplete code
result1 = codeop.compile_command(incomplete_code)
print(f"Incomplete code result: {result1}")  # Output: None (indicating incomplete)

# Try compiling complete code
try:
    result2 = codeop.compile_command(complete_code)
    if result2 is not None:
        print("Complete code compiled successfully")
        exec(result2)
        print(f"5! = {factorial(5)}")  # Output: 5! = 120
except SyntaxError as e:
    print(f"Error: {e}")
```

### Example 7: Using `compile` for File Operations

```python
import codeop

# Define a file path containing Python source code
file_path = 'example.py'

# Example: Create a simple Python file
example_code = """
def hello():
    return "Hello from file!"

message = hello()
print(message)
"""

# Write the example code to a file
with open(file_path, 'w') as f:
    f.write(example_code)

# Read and compile the file content
with open(file_path, 'r') as f:
    file_content = f.read()

try:
    code_obj = compile(file_content, file_path, 'exec')
    print("File compiled successfully")
    # Execute the compiled code
    exec(code_obj)
except SyntaxError as e:
    print(f"Compilation error: {e}")
```

These examples cover a range of use cases for the `codeop` module, including basic compilation and execution, handling syntax errors gracefully, distinguishing between incomplete and complete code, and dynamically compiling and executing Python code.
