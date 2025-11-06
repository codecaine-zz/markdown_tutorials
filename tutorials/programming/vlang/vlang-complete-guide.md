# Complete V Language Guide: From Basics to Advanced Concepts

V is a modern, fast, and simple programming language designed for maintainability, safety, and performance. This comprehensive tutorial will guide you through the entire V language ecosystem, from basic syntax to advanced features and best practices.

## Table of Contents

1. [Introduction to V](#introduction-to-v)
2. [Installation and Setup](#installation-and-setup)
3. [Basic Syntax and Data Types](#basic-syntax-and-data-types)
4. [Variables and Constants](#variables-and-constants)
5. [Functions](#functions)
6. [Control Flow](#control-flow)
7. [Data Structures](#data-structures)
8. [Object-Oriented Programming](#object-oriented-programming)
9. [Memory Management](#memory-management)
10. [Error Handling](#error-handling)
11. [Modules and Packages](#modules-and-packages)
12. [Concurrency](#concurrency)
13. [Standard Library](#standard-library)
14. [File I/O Operations](#file-io-operations)
15. [Network Programming](#network-programming)
16. [Testing and Debugging](#testing-and-debugging)
17. [Best Practices](#best-practices)

## Introduction to V {#introduction-to-v}

### What is V?

V is a compiled programming language that emphasizes:
- **Safety**: No null, no undefined behavior, no variable shadowing
- **Speed**: V compiles to native machine code and runs fast
- **Simplicity**: Clean, readable syntax inspired by Go, Rust, and Swift
- **Zero dependencies**: The entire V distribution is under 1MB
- **C/C++ interop**: Easy integration with existing C/C++ codebases

### Key Features

- **Compile-time memory safety**: No memory leaks, no double-free errors
- **Immutable variables by default**: Variables are immutable unless declared with `mut`
- **No global variables**: Encourages better code organization
- **Generics**: Type-safe generic programming
- **Option/Result types**: Elegant error handling without exceptions
- **Concurrency**: Lightweight threads and channels

[Official V Website](https://vlang.io/) | [GitHub Repository](https://github.com/vlang/v)

## Installation and Setup {#installation-and-setup}

### Installing V on Different Platforms

#### macOS (with Homebrew)
```bash
# Install V using Homebrew
brew install vlang

# Verify installation
v version

# Expected output: V 0.x.x (commit hash)
```

#### macOS/Linux (from source)
```bash
# Clone the V repository
git clone https://github.com/vlang/v
cd v

# Build V (this creates the v executable)
make

# Add V to your PATH
sudo ./v symlink

# Verify installation
v version
```

#### Windows
```powershell
# Download the latest release from GitHub
# https://github.com/vlang/v/releases

# Extract to C:\v (or your preferred location)
# Add C:\v to your PATH environment variable

# Verify installation
v version
```

### Development Environment Setup

#### VS Code Configuration
```bash
# Install the V extension for VS Code
code --install-extension vlang.vscode-vlang
```

Create a workspace configuration:

**`.vscode/settings.json`**
```json
{
    "files.associations": {
        "*.v": "v"
    },
    "editor.formatOnSave": true,
    "editor.tabSize": 4,
    "editor.insertSpaces": false,
    "v.format.enable": true,
    "v.format.flag": []
}
```

**`.vscode/tasks.json`**
```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "Run V Program",
            "type": "shell",
            "command": "v",
            "args": ["run", "${file}"],
            "group": {
                "kind": "build",
                "isDefault": true
            },
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "shared",
                "clear": true
            },
            "problemMatcher": []
        },
        {
            "label": "Build V Program",
            "type": "shell",
            "command": "v",
            "args": ["${file}"],
            "group": "build",
            "presentation": {
                "echo": true,
                "reveal": "always"
            }
        },
        {
            "label": "Test V Program",
            "type": "shell",
            "command": "v",
            "args": ["test", "${workspaceFolder}"],
            "group": "test"
        }
    ]
}
```

[Official Installation Guide](https://github.com/vlang/v/blob/master/doc/docs.md#installing-v)

## Basic Syntax and Data Types {#basic-syntax-and-data-types}

### Hello World - Your First V Program

```v
// hello.v
fn main() {
    println('Hello, World!')
    println('Welcome to V programming!')
}
```

**Running the program:**
```bash
# Run directly
v run hello.v

# Or compile and run
v hello.v
./hello
```

### Comments

```v
// Single-line comment

/*
   Multi-line comment
   Can span multiple lines
*/

fn main() {
    // This is a comment explaining the code
    println('Hello!') // End-of-line comment
    
    /*
    This is a multi-line comment
    that explains complex logic
    */
    println('V programming is fun!')
}
```

### Basic Data Types

V has several built-in primitive types:

```v
fn main() {
    // Integer types
    small_int i8 = 127          // 8-bit signed integer (-128 to 127)
    unsigned_small u8 = 255     // 8-bit unsigned integer (0 to 255)
    regular_int i16 = 32767     // 16-bit signed integer
    unsigned_int u16 = 65535    // 16-bit unsigned integer
    normal_int int = 2147483647 // 32-bit signed integer (default)
    big_int i64 = 9223372036854775807 // 64-bit signed integer
    
    // Floating-point types
    small_float f32 = 3.14159   // 32-bit floating point
    double_float f64 = 2.718281828 // 64-bit floating point (default)
    
    // Boolean type
    is_v_awesome bool = true    // Boolean: true or false
    is_slow bool = false
    
    // String type
    message string = 'Hello, V!' // UTF-8 strings
    multiline_string := 'Line 1
Line 2
Line 3'
    
    // Character type (rune)
    letter rune = `A`           // Unicode code point (32-bit)
    emoji rune = `😊`
    
    // Print all values
    println('Integer: $normal_int')
    println('Float: $double_float')
    println('Boolean: $is_v_awesome')
    println('String: $message')
    println('Character: $letter')
    println('Emoji: $emoji')
}
```

### Type Inference and Explicit Typing

```v
fn main() {
    // Type inference (recommended)
    name := 'Alice'           // inferred as string
    age := 25                 // inferred as int
    height := 5.8            // inferred as f64
    is_student := true       // inferred as bool
    
    // Explicit typing (when needed)
    var_int int = 42
    var_float f32 = 3.14
    var_string string = 'Hello'
    var_bool bool = false
    
    // Type conversion
    int_val := 42
    float_val := f64(int_val)    // Convert int to f64
    string_val := int_val.str()  // Convert int to string
    
    println('Name: $name (${typeof(name).name})')
    println('Age: $age (${typeof(age).name})')
    println('Height: $height (${typeof(height).name})')
    println('Converted float: $float_val')
    println('Converted string: $string_val')
}
```

[Variables Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#variables)

## Variables and Constants {#variables-and-constants}

### Variable Declaration and Mutability

In V, variables are **immutable by default** - this prevents many common bugs:

```v
fn main() {
    // Immutable variables (cannot be changed after assignment)
    name := 'John Doe'
    birth_year := 1990
    
    // This would cause a compilation error:
    // name = 'Jane Doe'  // Error: cannot assign to immutable variable
    
    // Mutable variables (can be changed)
    mut age := 25
    mut balance := 1000.50
    mut is_active := true
    
    // Modifying mutable variables
    age = 26
    balance += 250.0
    is_active = false
    
    println('Name: $name')
    println('Birth Year: $birth_year')
    println('Age: $age')
    println('Balance: $$balance')  // $$ to print literal $
    println('Is Active: $is_active')
}
```

### Variable Scope

```v
fn main() {
    global_var := 'I am global'
    
    if true {
        local_var := 'I am local'
        println(global_var)  // Can access global_var
        println(local_var)   // Can access local_var
    }
    
    // println(local_var)  // Error: local_var not in scope
    
    // Block scope
    {
        block_var := 'I am in a block'
        println(block_var)
    }
    // println(block_var)  // Error: block_var not in scope
    
    // Loop scope
    for i := 0; i < 3; i++ {
        loop_var := 'Loop iteration $i'
        println(loop_var)
    }
    // println(loop_var)  // Error: loop_var not in scope
    // println(i)         // Error: i not in scope
}
```

### Constants

```v
// Module-level constants (global constants)
const (
    pi = 3.14159265359
    app_name = 'My V Application'
    max_users = 1000
    default_config = Config{
        timeout: 30
        retry_count: 3
    }
)

struct Config {
    timeout int
    retry_count int
}

fn main() {
    // Function-level constants
    const local_constant = 'This is local'
    
    // Using constants
    radius := 5.0
    area := pi * radius * radius
    
    println('App: $app_name')
    println('Max Users: $max_users')
    println('Circle Area: $area')
    println('Local: $local_constant')
    println('Timeout: ${default_config.timeout}s')
}

// Constants can be used in other functions too
fn calculate_circumference(radius f64) f64 {
    return 2 * pi * radius
}
```

### Advanced Variable Features

```v
fn main() {
    // Multiple variable assignment
    mut a, mut b := 10, 20
    println('a: $a, b: $b')
    
    // Swapping variables
    a, b = b, a
    println('After swap - a: $a, b: $b')
    
    // Underscore for unused values
    result, _ := divide_with_remainder(17, 3)
    println('Result: $result')
    
    // Array destructuring
    coordinates := [10, 20, 30]
    x, y, z := coordinates[0], coordinates[1], coordinates[2]
    println('Coordinates: ($x, $y, $z)')
    
    // Struct field access
    person := Person{
        name: 'Alice'
        age: 30
    }
    
    person_name := person.name
    person_age := person.age
    println('Person: $person_name, Age: $person_age')
}

struct Person {
    name string
    age int
}

fn divide_with_remainder(dividend, divisor int) (int, int) {
    return dividend / divisor, dividend % divisor
}
```

### String Interpolation and Operations

```v
fn main() {
    name := 'V Language'
    version := 0.4
    is_stable := false
    
    // String interpolation
    message := 'Welcome to $name version $version!'
    println(message)
    
    // Expression interpolation
    status := 'Status: ${if is_stable { "Stable" } else { "Development" }}'
    println(status)
    
    // Multi-line strings
    poem := 'Roses are red,
Violets are blue,
V is fast,
And readable too!'
    println(poem)
    
    // Raw strings (no interpolation)
    raw_string := r'This $name will not be interpolated'
    println(raw_string)
    
    // String operations
    text := 'Hello, World!'
    println('Length: ${text.len}')
    println('Upper: ${text.to_upper()}')
    println('Lower: ${text.to_lower()}')
    println('Contains "World": ${text.contains("World")}')
    println('Starts with "Hello": ${text.starts_with("Hello")}')
    println('Ends with "!": ${text.ends_with("!")}')
    
    // String slicing
    substr := text[0..5]  // "Hello"
    println('Substring: $substr')
    
    // String concatenation
    greeting := 'Hello, '
    target := 'V programmer'
    full_greeting := greeting + target + '!'
    println(full_greeting)
}
```

### Memory Safety Features

```v
fn main() {
    // No null pointers - use Option types instead
    maybe_value := get_optional_value(true)
    
    if value := maybe_value {
        println('Got value: $value')
    } else {
        println('No value received')
    }
    
    // No undefined behavior
    mut array := [1, 2, 3, 4, 5]
    
    // Safe array access
    if index := safe_get_index(array, 2) {
        println('Element at index 2: $index')
    }
    
    // Bounds checking prevents buffer overflows
    // array[10] = 100  // This would panic with bounds check
    
    // No memory leaks - automatic memory management
    create_temporary_data()  // Memory automatically cleaned up
}

fn get_optional_value(should_return bool) ?int {
    if should_return {
        return 42
    }
    return none
}

fn safe_get_index(arr []int, index int) ?int {
    if index >= 0 && index < arr.len {
        return arr[index]
    }
    return none
}

fn create_temporary_data() {
    temp_data := [1, 2, 3, 4, 5]
    println('Temporary data: $temp_data')
    // Memory automatically freed when function exits
}
```

## Functions {#functions}

Functions in V are first-class citizens and the primary way to organize code. V's function syntax is clean and supports advanced features like multiple return values, optional parameters, and generics.

### Basic Function Definition

```v
fn main() {
    // Calling functions
    say_hello('World')
    say_hello('V Programming')
    
    // Using return values
    result := add_numbers(15, 25)
    println('15 + 25 = $result')
    
    // Multiple return values
    quotient, remainder := divide_with_remainder(17, 3)
    println('17 ÷ 3 = $quotient remainder $remainder')
    
    // Ignoring return values with underscore
    _, rem := divide_with_remainder(20, 6)
    println('Remainder only: $rem')
}

// Simple function with no return value
fn say_hello(name string) {
    println('Hello, $name!')
}

// Function with single return value
fn add_numbers(a int, b int) int {
    return a + b
}

// Shorthand syntax for parameters of same type
fn multiply(x, y int) int {
    return x * y
}

// Function with multiple return values
fn divide_with_remainder(dividend, divisor int) (int, int) {
    return dividend / divisor, dividend % divisor
}
```

### Advanced Function Features

```v
fn main() {
    // Default parameters
    greet_person('Alice')                    // Uses default greeting
    greet_person('Bob', 'Hi')               // Custom greeting
    greet_person('Charlie', 'Hey', '!!!')   // Custom greeting and punctuation
    
    // Variadic functions (variable number of arguments)
    sum1 := sum_all(1, 2, 3)
    sum2 := sum_all(10, 20, 30, 40, 50)
    println('Sum 1: $sum1')
    println('Sum 2: $sum2')
    
    // Passing arrays to variadic functions
    numbers := [1, 2, 3, 4, 5]
    sum3 := sum_all(...numbers)  // Spread operator
    println('Sum 3: $sum3')
    
    // Higher-order functions
    result1 := apply_operation(5, 3, add_numbers)
    result2 := apply_operation(8, 2, multiply)
    println('5 + 3 = $result1')
    println('8 × 2 = $result2')
    
    // Anonymous functions (closures)
    double := fn (x int) int {
        return x * 2
    }
    
    result3 := apply_operation(7, 0, fn (a, _ int) int {
        return double(a)
    })
    println('7 doubled = $result3')
}

// Function with default parameters
fn greet_person(name string, greeting string = 'Hello', punctuation string = '!') {
    println('$greeting, $name$punctuation')
}

// Variadic function (accepts variable number of arguments)
fn sum_all(numbers ...int) int {
    mut total := 0
    for num in numbers {
        total += num
    }
    return total
}

// Higher-order function (takes another function as parameter)
fn apply_operation(a, b int, operation fn(int, int) int) int {
    return operation(a, b)
}
```

### Function Overloading with Generics

```v
fn main() {
    // Generic functions work with different types
    int_max := max(10, 5)
    float_max := max(3.14, 2.71)
    string_max := max('apple', 'banana')  // Lexicographical comparison
    
    println('Max int: $int_max')
    println('Max float: $float_max')
    println('Max string: $string_max')
    
    // Generic array operations
    int_array := [1, 2, 3, 4, 5]
    string_array := ['hello', 'world', 'V', 'programming']
    
    first_int := first_element(int_array) or { 0 }
    first_string := first_element(string_array) or { '' }
    
    println('First int: $first_int')
    println('First string: $first_string')
    
    // Working with different container types
    int_list := [10, 20, 30]
    float_list := [1.1, 2.2, 3.3]
    
    print_all(int_list)
    print_all(float_list)
}

// Generic function - works with any comparable type
fn max<T>(a, b T) T {
    return if a > b { a } else { b }
}

// Generic function with Option return type
fn first_element<T>(arr []T) ?T {
    if arr.len == 0 {
        return none
    }
    return arr[0]
}

// Generic function that prints all elements
fn print_all<T>(items []T) {
    print('Items: ')
    for item in items {
        print('$item ')
    }
    println('')
}
```

### Function Types and First-Class Functions

```v
fn main() {
    // Functions as variables
    mut operation fn(int, int) int = add_numbers
    result1 := operation(5, 3)
    println('Addition: $result1')
    
    // Change the function
    operation = multiply
    result2 := operation(5, 3)
    println('Multiplication: $result2')
    
    // Array of functions
    operations := [
        fn (a, b int) int { return a + b },
        fn (a, b int) int { return a - b },
        fn (a, b int) int { return a * b },
        fn (a, b int) int { return a / b },
    ]
    
    x, y := 20, 4
    op_names := ['Addition', 'Subtraction', 'Multiplication', 'Division']
    
    for i, op in operations {
        result := op(x, y)
        println('$op_names[i]: $x and $y = $result')
    }
    
    // Function factory pattern
    multiplier_by_3 := create_multiplier(3)
    multiplier_by_5 := create_multiplier(5)
    
    println('7 × 3 = ${multiplier_by_3(7)}')
    println('7 × 5 = ${multiplier_by_5(7)}')
}

// Function that returns another function
fn create_multiplier(factor int) fn(int) int {
    return fn [factor] (x int) int {
        return x * factor
    }
}
```

### Method Functions (Associated with Types)

```v
struct Person {
    name string
    age int
mut:
    email string
}

// Method with immutable receiver
fn (p Person) introduce() {
    println('Hi, I am $p.name and I am $p.age years old.')
}

// Method with mutable receiver
fn (mut p Person) have_birthday() {
    p.age++
    println('Happy birthday! $p.name is now $p.age years old.')
}

// Method that returns a value
fn (p Person) is_adult() bool {
    return p.age >= 18
}

// Static method (doesn't need an instance)
fn Person.create_default() Person {
    return Person{
        name: 'Unknown'
        age: 0
        email: 'unknown@example.com'
    }
}

fn main() {
    mut person := Person{
        name: 'Alice'
        age: 17
        email: 'alice@example.com'
    }
    
    // Calling methods
    person.introduce()
    println('Is adult: ${person.is_adult()}')
    
    // Calling method that modifies the struct
    person.have_birthday()
    println('Is adult now: ${person.is_adult()}')
    
    // Using static method
    default_person := Person.create_default()
    default_person.introduce()
}
```

### Error Handling in Functions

```v
import os

fn main() {
    // Functions that might fail return Option or Result types
    
    // Option type example
    result1 := safe_divide(10, 2)
    if value := result1 {
        println('10 ÷ 2 = $value')
    } else {
        println('Division failed')
    }
    
    result2 := safe_divide(10, 0)
    if value := result2 {
        println('10 ÷ 0 = $value')
    } else {
        println('Cannot divide by zero')
    }
    
    // Result type with custom error
    file_result := read_config_file('config.txt')
    match file_result {
        Ok(content) { println('Config content: $content') }
        Err(error) { println('Error reading config: $error') }
    }
    
    // Or with simplified syntax
    content := read_config_file('config.txt') or {
        println('Failed to read config, using defaults')
        'default_config=true'
    }
    println('Using config: $content')
}

// Function returning Option type
fn safe_divide(a, b f64) ?f64 {
    if b == 0 {
        return none
    }
    return a / b
}

// Function returning Result type
fn read_config_file(path string) !string {
    content := os.read_file(path) or {
        return error('Could not read file: $path')
    }
    if content.len == 0 {
        return error('File is empty')
    }
    return content
}

// Alternative error handling with propagation
fn process_data(filename string) ![]string {
    content := read_config_file(filename)!  // Propagate error if any
    lines := content.split('\n')
    
    if lines.len == 0 {
        return error('No data to process')
    }
    
    return lines
}
```

### Function Documentation and Attributes

```v
// Function with comprehensive documentation
// calculate_compound_interest calculates compound interest
// based on principal amount, annual interest rate, compounding frequency, and time period.
//
// Parameters:
//   principal: The initial amount of money (must be positive)
//   rate: Annual interest rate as a decimal (e.g., 0.05 for 5%)
//   compounds_per_year: Number of times interest is compounded per year
//   years: Number of years (must be positive)
//
// Returns:
//   The final amount after compound interest, or an error if inputs are invalid
//
// Example:
//   amount := calculate_compound_interest(1000, 0.05, 12, 10) or { panic(err) }
//   println('Final amount: $$amount')
fn calculate_compound_interest(principal, rate f64, compounds_per_year, years int) !f64 {
    if principal <= 0 {
        return error('Principal must be positive')
    }
    if rate < 0 {
        return error('Interest rate cannot be negative')
    }
    if compounds_per_year <= 0 {
        return error('Compounding frequency must be positive')
    }
    if years <= 0 {
        return error('Time period must be positive')
    }
    
    import math
    
    // A = P(1 + r/n)^(nt)
    base := 1 + (rate / f64(compounds_per_year))
    exponent := f64(compounds_per_year * years)
    amount := principal * math.pow(base, exponent)
    
    return amount
}

// Function with attributes
[inline]
fn fast_add(a, b int) int {
    return a + b
}

[deprecated: 'Use new_function instead']
fn old_function() {
    println('This function is deprecated')
}

[unsafe]
fn unsafe_operation(ptr voidptr) {
    // Unsafe operations go here
    println('Performing unsafe operation')
}

fn main() {
    // Using documented function
    amount := calculate_compound_interest(1000.0, 0.05, 12, 10) or {
        println('Error: $err')
        return
    }
    println('Compound interest result: $$amount')
    
    // Using inline function
    sum := fast_add(10, 20)
    println('Fast add result: $sum')
}
```

### Performance Considerations

```v
import time

fn main() {
    // Measure function performance
    start := time.now()
    result := fibonacci_recursive(35)
    recursive_time := time.now().sub(start)
    
    start2 := time.now()
    result2 := fibonacci_iterative(35)
    iterative_time := time.now().sub(start2)
    
    println('Recursive result: $result (took ${recursive_time.milliseconds()}ms)')
    println('Iterative result: $result2 (took ${iterative_time.milliseconds()}ms)')
    
    // Demonstrate inline functions
    start3 := time.now()
    for i := 0; i < 1000000; i++ {
        _ = inline_square(i)
    }
    inline_time := time.now().sub(start3)
    
    start4 := time.now()
    for i := 0; i < 1000000; i++ {
        _ = regular_square(i)
    }
    regular_time := time.now().sub(start4)
    
    println('Inline function time: ${inline_time.milliseconds()}ms')
    println('Regular function time: ${regular_time.milliseconds()}ms')
}

// Recursive fibonacci (inefficient)
fn fibonacci_recursive(n int) int {
    if n <= 1 {
        return n
    }
    return fibonacci_recursive(n - 1) + fibonacci_recursive(n - 2)
}

// Iterative fibonacci (efficient)
fn fibonacci_iterative(n int) int {
    if n <= 1 {
        return n
    }
    
    mut a, mut b := 0, 1
    for i := 2; i <= n; i++ {
        a, b = b, a + b
    }
    return b
}

[inline]
fn inline_square(x int) int {
    return x * x
}

fn regular_square(x int) int {
    return x * x
}
```

[Functions Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#functions)

## Control Flow {#control-flow}

V provides intuitive and powerful control flow constructs that make code readable and maintainable. Unlike many languages, V doesn't require parentheses around conditions, making the syntax cleaner.

### Conditional Statements

#### Basic If-Else Statements

```v
fn main() {
    age := 25
    
    // Simple if statement
    if age >= 18 {
        println('You are an adult')
    }
    
    // If-else statement
    if age >= 65 {
        println('You are a senior citizen')
    } else {
        println('You are not a senior citizen')
    }
    
    // If-else if-else chain
    if age < 13 {
        println('You are a child')
    } else if age < 20 {
        println('You are a teenager')
    } else if age < 65 {
        println('You are an adult')
    } else {
        println('You are a senior citizen')
    }
    
    // If expressions (can return values)
    status := if age >= 18 { 'adult' } else { 'minor' }
    println('Status: $status')
    
    // Complex conditions
    has_license := true
    has_car := false
    
    if age >= 16 && has_license {
        if has_car {
            println('You can drive your own car')
        } else {
            println('You can drive but need to borrow a car')
        }
    } else {
        println('You cannot drive yet')
    }
}
```

#### Advanced Conditional Patterns

```v
fn main() {
    // If with optional unwrapping
    maybe_number := get_number(true)
    
    if number := maybe_number {
        println('Got number: $number')
        println('Double: ${number * 2}')
    } else {
        println('No number received')
    }
    
    // Multiple conditions
    temperature := 25
    humidity := 60
    
    weather_comment := if temperature > 30 && humidity > 70 {
        'Hot and humid - stay hydrated!'
    } else if temperature > 30 {
        'Hot but dry - perfect weather!'
    } else if temperature < 10 {
        'Cold - wear warm clothes!'
    } else {
        'Pleasant weather!'
    }
    
    println('Weather: $weather_comment')
    
    // Logical operators
    is_weekend := true
    is_sunny := false
    has_plans := true
    
    if is_weekend || (!is_sunny && has_plans) {
        println('Good day to stay indoors')
    }
    
    // Negation
    is_raining := false
    if !is_raining {
        println('Good day for a walk')
    }
}

fn get_number(should_return bool) ?int {
    return if should_return { 42 } else { none }
}
```

### Loops

#### For Loops

```v
fn main() {
    // Traditional C-style for loop
    println('Counting up:')
    for i := 0; i < 5; i++ {
        println('Count: $i')
    }
    
    // Counting down
    println('Counting down:')
    for i := 5; i > 0; i-- {
        println('Count: $i')
    }
    
    // Custom step
    println('Even numbers:')
    for i := 0; i <= 10; i += 2 {
        println('Even: $i')
    }
    
    // For loop with array iteration
    fruits := ['apple', 'banana', 'orange', 'grape']
    
    println('Fruits:')
    for fruit in fruits {
        println('- $fruit')
    }
    
    // For loop with index and value
    println('Fruits with index:')
    for i, fruit in fruits {
        println('$i: $fruit')
    }
    
    // For loop with maps
    scores := {
        'Alice': 95
        'Bob': 87
        'Charlie': 92
    }
    
    println('Scores:')
    for name, score in scores {
        println('$name: $score')
    }
    
    // For loop with string iteration (runes)
    text := 'Hello'
    for char in text {
        println('Character: $char')
    }
}
```

#### While Loops and Infinite Loops

```v
fn main() {
    // While loop
    mut count := 0
    while count < 5 {
        println('While count: $count')
        count++
    }
    
    // Infinite loop with break
    mut counter := 0
    for {
        counter++
        if counter > 3 {
            break
        }
        println('Infinite loop iteration: $counter')
    }
    
    // Continue statement
    println('Odd numbers only:')
    for i := 0; i < 10; i++ {
        if i % 2 == 0 {
            continue  // Skip even numbers
        }
        println('Odd: $i')
    }
    
    // Nested loops with labeled break
    outer: for i := 0; i < 3; i++ {
        for j := 0; j < 3; j++ {
            if i == 1 && j == 1 {
                break outer  // Break out of both loops
            }
            println('i: $i, j: $j')
        }
    }
}
```

#### Advanced Loop Patterns

```v
fn main() {
    // Loop with range function
    println('Range loop:')
    for num in 1..6 {  // 1 to 5 (exclusive end)
        println('Range: $num')
    }
    
    // Processing arrays with conditions
    numbers := [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    
    mut even_sum := 0
    mut odd_sum := 0
    
    for num in numbers {
        if num % 2 == 0 {
            even_sum += num
        } else {
            odd_sum += num
        }
    }
    
    println('Sum of evens: $even_sum')
    println('Sum of odds: $odd_sum')
    
    // Finding elements
    target := 7
    mut found_index := -1
    
    for i, num in numbers {
        if num == target {
            found_index = i
            break
        }
    }
    
    if found_index != -1 {
        println('Found $target at index $found_index')
    } else {
        println('$target not found')
    }
    
    // Processing with early termination
    large_numbers := [100, 200, 300, 5, 400, 500]
    
    for i, num in large_numbers {
        println('Processing: $num')
        if num < 50 {
            println('Found small number $num at index $i, stopping')
            break
        }
    }
}
```

### Match Statements

Match statements in V are more powerful than traditional switch statements and support pattern matching.

#### Basic Match Statements

```v
fn main() {
    // Basic match statement
    day := 'Monday'
    
    match day {
        'Monday' => println('Start of the work week!')
        'Tuesday', 'Wednesday', 'Thursday' => println('Midweek grind')
        'Friday' => println('TGIF!')
        'Saturday', 'Sunday' => println('Weekend time!')
        else => println('Unknown day')
    }
    
    // Match with expressions
    grade := 'B'
    points := match grade {
        'A' => 4.0
        'B' => 3.0
        'C' => 2.0
        'D' => 1.0
        'F' => 0.0
        else => -1.0
    }
    println('Grade $grade is worth $points points')
    
    // Match with ranges
    score := 85
    letter_grade := match score {
        90..100 => 'A'
        80..89 => 'B'
        70..79 => 'C'
        60..69 => 'D'
        else => 'F'
    }
    println('Score $score is grade $letter_grade')
}
```

#### Advanced Match Patterns

```v
enum Color {
    red
    green
    blue
    rgb(r int, g int, b int)
}

enum Shape {
    circle(radius f64)
    rectangle(width f64, height f64)
    triangle(base f64, height f64)
}

fn main() {
    // Match with enums
    color := Color.rgb(255, 128, 0)
    
    match color {
        .red => println('Pure red')
        .green => println('Pure green')
        .blue => println('Pure blue')
        .rgb(r, g, b) => println('RGB color: ($r, $g, $b)')
    }
    
    // Match with complex enum patterns
    shapes := [
        Shape.circle(5.0),
        Shape.rectangle(4.0, 6.0),
        Shape.triangle(3.0, 4.0),
    ]
    
    for shape in shapes {
        area := match shape {
            .circle(radius) => 3.14159 * radius * radius
            .rectangle(width, height) => width * height
            .triangle(base, height) => 0.5 * base * height
        }
        
        description := match shape {
            .circle(radius) => 'Circle with radius $radius'
            .rectangle(width, height) => 'Rectangle ${width}×${height}'
            .triangle(base, height) => 'Triangle with base $base, height $height'
        }
        
        println('$description has area: $area')
    }
    
    // Match with conditions
    number := 15
    
    match number {
        n if n < 0 => println('Negative number: $n')
        n if n == 0 => println('Zero')
        n if n % 2 == 0 => println('Even positive number: $n')
        n if n % 3 == 0 => println('Odd number divisible by 3: $n')
        else => println('Other positive number: $number')
    }
}
```

#### Match with Option and Result Types

```v
fn main() {
    // Match with Option types
    maybe_value := get_optional_value(true)
    
    match maybe_value {
        some(value) => println('Got value: $value')
        none => println('No value')
    }
    
    // More complex Option matching
    numbers := [some(1), none, some(3), some(4), none]
    
    mut sum := 0
    mut count := 0
    
    for opt_num in numbers {
        match opt_num {
            some(num) => {
                sum += num
                count++
            }
            none => {
                println('Skipping none value')
            }
        }
    }
    
    if count > 0 {
        average := f64(sum) / f64(count)
        println('Average of $count numbers: $average')
    }
    
    // Match with Result types
    operations := [
        divide_safe(10, 2),
        divide_safe(8, 0),
        divide_safe(15, 3),
    ]
    
    for i, result in operations {
        match result {
            Ok(value) => println('Operation $i succeeded: $value')
            Err(error) => println('Operation $i failed: $error')
        }
    }
}

fn get_optional_value(should_return bool) ?int {
    return if should_return { 42 } else { none }
}

fn divide_safe(a, b f64) !f64 {
    if b == 0 {
        return error('Division by zero')
    }
    return a / b
}
```

### Control Flow Best Practices

```v
fn main() {
    // Use early returns to reduce nesting
    process_user_data('alice@example.com', 25)
    process_user_data('', 30)
    process_user_data('bob@example.com', -5)
    
    // Use match instead of long if-else chains
    http_status := 404
    handle_http_status(http_status)
    
    // Combine conditions logically
    user_permissions := ['read', 'write']
    resource := 'document.txt'
    action := 'write'
    
    if can_perform_action(user_permissions, resource, action) {
        println('Action permitted')
    } else {
        println('Action denied')
    }
}

fn process_user_data(email string, age int) {
    // Early return pattern - reduces nesting
    if email == '' {
        println('Error: Email is required')
        return
    }
    
    if age < 0 {
        println('Error: Age cannot be negative')
        return
    }
    
    if age < 13 {
        println('Error: User must be at least 13 years old')
        return
    }
    
    // Main logic here - no deep nesting
    println('Processing user: $email, age: $age')
    println('User data is valid!')
}

fn handle_http_status(status int) {
    message := match status {
        200 => 'OK'
        201 => 'Created'
        400 => 'Bad Request'
        401 => 'Unauthorized'
        403 => 'Forbidden'
        404 => 'Not Found'
        500 => 'Internal Server Error'
        else => 'Unknown Status'
    }
    
    println('HTTP $status: $message')
}

fn can_perform_action(permissions []string, resource string, action string) bool {
    // Complex condition broken down for readability
    has_permission := action in permissions
    is_valid_resource := resource.len > 0
    is_valid_action := action in ['read', 'write', 'delete', 'execute']
    
    return has_permission && is_valid_resource && is_valid_action
}
```

[Control Flow Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#control-flow)

## Data Structures {#data-structures}

V provides efficient and safe data structures that are easy to use while maintaining performance. All data structures in V are memory-safe and bounds-checked by default.

### Arrays

Arrays in V are bounds-checked and memory-safe. They can be fixed-size or dynamic.

#### Basic Array Operations

```v
fn main() {
    // Fixed-size arrays
    mut numbers := [1, 2, 3, 4, 5]
    println('Array: $numbers')
    println('First element: $numbers[0]')
    println('Last element: $numbers[${numbers.len - 1}]')
    println('Array length: ${numbers.len}')
    
    // Modifying arrays
    numbers[0] = 10
    numbers[4] = 50
    println('Modified array: $numbers')
    
    // Array initialization with specific size
    mut zeros := []int{len: 5}  // [0, 0, 0, 0, 0]
    mut ones := []int{len: 3, init: 1}  // [1, 1, 1]
    mut sequence := []int{len: 5, init: index}  // [0, 1, 2, 3, 4]
    
    println('Zeros: $zeros')
    println('Ones: $ones')
    println('Sequence: $sequence')
    
    // Array with specific capacity
    mut capacity_array := []int{cap: 10}
    println('Array capacity: ${capacity_array.cap}')
    println('Array length: ${capacity_array.len}')
}
```

#### Dynamic Arrays (Slices)

```v
fn main() {
    // Creating dynamic arrays
    mut fruits := []string{}
    mut numbers := []int{}
    
    // Adding elements
    fruits << 'apple'
    fruits << 'banana'
    fruits << 'orange'
    
    numbers << 1
    numbers << 2
    numbers << 3
    
    println('Fruits: $fruits')
    println('Numbers: $numbers')
    
    // Adding multiple elements
    fruits << ['grape', 'kivi', 'mango']
    numbers << [4, 5, 6, 7, 8, 9, 10]
    
    println('More fruits: $fruits')
    println('More numbers: $numbers')
    
    // Removing elements
    fruits.delete(1)  // Remove 'banana'
    println('After deletion: $fruits')
    
    // Inserting elements
    fruits.insert(1, 'cherry')
    println('After insertion: $fruits')
    
    // Prepending elements
    fruits.prepend('strawberry')
    println('After prepend: $fruits')
    
    // Clearing array
    mut temp := [1, 2, 3, 4, 5]
    println('Before clear: $temp')
    temp.clear()
    println('After clear: $temp')
}
```

#### Array Slicing and Operations

```v
fn main() {
    numbers := [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
    
    // Basic slicing
    first_five := numbers[0..5]      // [0, 1, 2, 3, 4]
    last_five := numbers[5..]        // [5, 6, 7, 8, 9]
    middle_three := numbers[3..6]    // [3, 4, 5]
    
    println('Original: $numbers')
    println('First five: $first_five')
    println('Last five: $last_five')
    println('Middle three: $middle_three')
    
    // Advanced slicing
    every_second := numbers[..10:2]  // [0, 2, 4, 6, 8]
    reverse_slice := numbers[8..3:-1] // [8, 7, 6, 5, 4]
    
    println('Every second: $every_second')
    println('Reverse slice: $reverse_slice')
    
    // Array methods
    text_array := ['hello', 'world', 'V', 'programming']
    
    // Finding elements
    if 'V' in text_array {
        println('Found V in array')
    }
    
    // Joining array elements
    joined := text_array.join(' ')
    println('Joined: $joined')
    
    // Sorting arrays
    mut unsorted := [5, 2, 8, 1, 9, 3]
    unsorted.sort()
    println('Sorted: $unsorted')
    
    // Custom sorting
    mut words := ['banana', 'apple', 'cherry', 'date']
    words.sort_with_compare(fn (a &string, b &string) int {
        return if a.len < b.len { -1 } else if a.len > b.len { 1 } else { 0 }
    })
    println('Sorted by length: $words')
}
```

#### Multi-dimensional Arrays

```v
fn main() {
    // 2D array
    mut matrix := [][]int{len: 3, init: []int{len: 3}}
    
    // Fill matrix with values
    for i in 0..3 {
        for j in 0..3 {
            matrix[i][j] = i * 3 + j + 1
        }
    }
    
    // Print matrix
    println('3x3 Matrix:')
    for row in matrix {
        println(row)
    }
    
    // Initialize 2D array directly
    tic_tac_toe := [
        ['X', 'O', 'X'],
        ['O', 'X', 'O'],
        ['O', 'X', 'X']
    ]
    
    println('Tic-tac-toe board:')
    for row in tic_tac_toe {
        println(row.join(' | '))
    }
    
    // 3D array example
    cube := [][][]int{len: 2, init: [][]int{len: 2, init: []int{len: 2}}}
    
    // Access 3D array elements
    cube[0][0][0] = 1
    cube[1][1][1] = 8
    
    println('3D cube element [0][0][0]: ${cube[0][0][0]}')
    println('3D cube element [1][1][1]: ${cube[1][1][1]}')
}
```

### Maps (Hash Tables)

Maps in V are efficient key-value data structures with type safety.

#### Basic Map Operations

```v
fn main() {
    // Creating maps
    mut scores := map[string]int{}
    mut ages := {'Alice': 25, 'Bob': 30, 'Charlie': 35}
    
    // Adding values
    scores['Alice'] = 95
    scores['Bob'] = 87
    scores['Charlie'] = 92
    scores['Diana'] = 88
    
    println('Scores: $scores')
    println('Ages: $ages')
    
    // Accessing values
    alice_score := scores['Alice']
    println("Alice's score: $alice_score")
    
    // Check if key exists
    if 'Eve' in scores {
        println("Eve's score: ${scores['Eve']}")
    } else {
        println('Eve not found in scores')
    }
    
    // Safe access with or-block
    eve_score := scores['Eve'] or { 0 }
    println("Eve's score (with default): $eve_score")
    
    // Deleting keys
    scores.delete('Bob')
    println('After deleting Bob: $scores')
    
    // Map length and emptiness
    println('Number of scores: ${scores.len}')
    println('Is scores empty: ${scores.len == 0}')
}
```

#### Advanced Map Usage

```v
struct Person {
    name string
    age int
    email string
}

fn main() {
    // Map with struct values
    mut people := map[string]Person{}
    
    people['alice'] = Person{
        name: 'Alice Johnson'
        age: 28
        email: 'alice@example.com'
    }
    
    people['bob'] = Person{
        name: 'Bob Smith'
        age: 32
        email: 'bob@example.com'
    }
    
    // Accessing struct fields in maps
    if alice := people['alice'] {
        println('Alice: ${alice.name}, ${alice.age} years old')
    }
    
    // Iterating over maps
    println('All people:')
    for key, person in people {
        println('Key: $key, Name: ${person.name}, Age: ${person.age}')
    }
    
    // Map with array values
    mut groups := map[string][]string{}
    groups['developers'] = ['Alice', 'Bob', 'Charlie']
    groups['designers'] = ['Diana', 'Eve']
    groups['managers'] = ['Frank']
    
    println('Groups:')
    for group_name, members in groups {
        println('$group_name: ${members.join(", ")}')
    }
    
    // Nested maps
    mut config := map[string]map[string]string{}
    config['database'] = {
        'host': 'localhost'
        'port': '5432'
        'name': 'myapp'
    }
    config['server'] = {
        'host': '0.0.0.0'
        'port': '8080'
    }
    
    println('Database host: ${config["database"]["host"]}')
    println('Server port: ${config["server"]["port"]}')
}
```

### Sets (Unique Collections)

V doesn't have a built-in set type, but you can implement set-like behavior using maps.

```v
fn main() {
    // Implementing a set using map[T]bool
    mut unique_numbers := map[int]bool{}
    numbers := [1, 2, 3, 2, 4, 3, 5, 1, 6]
    
    // Add elements to set
    for num in numbers {
        unique_numbers[num] = true
    }
    
    println('Original numbers: $numbers')
    println('Unique numbers: ${unique_numbers.keys()}')
    
    // Set operations
    set_a := map[string]bool{
        'apple': true
        'banana': true
        'cherry': true
    }
    
    set_b := map[string]bool{
        'banana': true
        'cherry': true
        'date': true
        'elderberry': true
    }
    
    // Union
    mut union_set := map[string]bool{}
    for key in set_a.keys() {
        union_set[key] = true
    }
    for key in set_b.keys() {
        union_set[key] = true
    }
    
    // Intersection
    mut intersection_set := map[string]bool{}
    for key in set_a.keys() {
        if key in set_b {
            intersection_set[key] = true
        }
    }
    
    // Difference
    mut difference_set := map[string]bool{}
    for key in set_a.keys() {
        if key !in set_b {
            difference_set[key] = true
        }
    }
    
    println('Set A: ${set_a.keys()}')
    println('Set B: ${set_b.keys()}')
    println('Union: ${union_set.keys()}')
    println('Intersection: ${intersection_set.keys()}')
    println('Difference (A - B): ${difference_set.keys()}')
}
```

### Custom Set Implementation

```v
struct Set<T> {
mut:
    data map[T]bool
}

fn Set.new<T>() Set<T> {
    return Set<T>{
        data: map[T]bool{}
    }
}

fn (mut s Set<T>) add(item T) {
    s.data[item] = true
}

fn (mut s Set<T>) remove(item T) {
    s.data.delete(item)
}

fn (s Set<T>) contains(item T) bool {
    return item in s.data
}

fn (s Set<T>) size() int {
    return s.data.len
}

fn (s Set<T>) items() []T {
    return s.data.keys()
}

fn (s Set<T>) union(other Set<T>) Set<T> {
    mut result := Set.new<T>()
    for item in s.items() {
        result.add(item)
    }
    for item in other.items() {
        result.add(item)
    }
    return result
}

fn (s Set<T>) intersection(other Set<T>) Set<T> {
    mut result := Set.new<T>()
    for item in s.items() {
        if other.contains(item) {
            result.add(item)
        }
    }
    return result
}

fn main() {
    mut set1 := Set.new<int>()
    mut set2 := Set.new<int>()
    
    // Add elements
    set1.add(1)
    set1.add(2)
    set1.add(3)
    
    set2.add(2)
    set2.add(3)
    set2.add(4)
    
    println('Set 1: ${set1.items()}')
    println('Set 2: ${set2.items()}')
    println('Set 1 size: ${set1.size()}')
    
    // Set operations
    union_result := set1.union(set2)
    intersection_result := set1.intersection(set2)
    
    println('Union: ${union_result.items()}')
    println('Intersection: ${intersection_result.items()}')
    
    // Check membership
    println('Set 1 contains 2: ${set1.contains(2)}')
    println('Set 1 contains 5: ${set1.contains(5)}')
    
    // Remove element
    set1.remove(1)
    println('Set 1 after removing 1: ${set1.items()}')
}
```

### String Operations

```v
fn main() {
    // String basics
    text := 'Hello, V Programming!'
    
    // String properties
    println('Text: $text')
    println('Length: ${text.len}')
    println('Is empty: ${text.len == 0}')
    
    // Case operations
    println('Uppercase: ${text.to_upper()}')
    println('Lowercase: ${text.to_lower()}')
    println('Title case: ${text.title()}')
    
    // String searching
    println('Contains "V": ${text.contains("V")}')
    println('Starts with "Hello": ${text.starts_with("Hello")}')
    println('Ends with "!": ${text.ends_with("!")}')
    
    // Finding positions
    pos := text.index('V') or { -1 }
    println('Position of "V": $pos')
    
    last_pos := text.last_index('o') or { -1 }
    println('Last position of "o": $last_pos')
    
    // String slicing
    greeting := text[0..5]        // "Hello"
    subject := text[7..18]        // "V Programming"
    
    println('Greeting: $greeting')
    println('Subject: $subject')
    
    // String splitting and joining
    words := text.split(' ')
    println('Words: $words')
    
    rejoined := words.join(' | ')
    println('Rejoined: $rejoined')
    
    // String replacement
    replaced := text.replace('V', 'V Language')
    println('Replaced: $replaced')
    
    // String trimming
    messy_text := '  Hello, World!  \n\t'
    cleaned := messy_text.trim_space()
    println('Original: "$messy_text"')
    println('Cleaned: "$cleaned"')
    
    // String repetition
    repeated := 'V'.repeat(5)
    println('Repeated: $repeated')
    
    // String comparison
    str1 := 'apple'
    str2 := 'banana'
    str3 := 'apple'
    
    println('$str1 == $str3: ${str1 == str3}')
    println('$str1 < $str2: ${str1 < str2}')  // Lexicographical comparison
}
```

[Arrays Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#arrays)
[Maps Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#maps)

## Object-Oriented Programming {#object-oriented-programming}

V supports object-oriented programming through structs, methods, and interfaces. While V doesn't have classes, its struct system provides similar functionality with better performance and memory safety.

### Structs - Custom Data Types

Structs are the foundation of object-oriented programming in V. They group related data and behavior together.

#### Basic Struct Definition and Usage

```v
// Basic struct definition
struct Person {
    name string
    age int
    email string
mut:
    is_active bool
    balance f64
}

fn main() {
    // Creating struct instances
    person1 := Person{
        name: 'Alice Johnson'
        age: 28
        email: 'alice@example.com'
        is_active: true
        balance: 1500.75
    }
    
    // Partial initialization (other fields get zero values)
    person2 := Person{
        name: 'Bob Smith'
        age: 35
        is_active: false
    }
    
    println('Person 1: ${person1.name}, Age: ${person1.age}')
    println('Person 2: ${person2.name}, Balance: $${person2.balance}')
    
    // Modifying mutable fields
    mut person3 := person1  // Create a mutable copy
    person3.is_active = false
    person3.balance += 250.00
    
    println('Updated balance: $${person3.balance}')
    
    // Struct field access
    if person3.is_active {
        println('${person3.name} is active')
    } else {
        println('${person3.name} is inactive')
    }
}
```

#### Advanced Struct Features

```v
// Struct with different access levels
struct BankAccount {
    account_number string    // immutable, public
    owner_name string       // immutable, public
mut:
    balance f64            // mutable, public
    is_active bool         // mutable, public
__global:
    total_accounts int     // global mutable (rarely used)
}

// Struct with embedded fields
struct Address {
    street string
    city string
    zip_code string
    country string
}

struct Employee {
    Person                 // Embedded struct (composition)
    employee_id string
    department string
    salary f64
    address Address
}

fn main() {
    // Using embedded structs
    employee := Employee{
        Person: Person{
            name: 'Charlie Brown'
            age: 30
            email: 'charlie@company.com'
            is_active: true
            balance: 0.0
        }
        employee_id: 'EMP001'
        department: 'Engineering'
        salary: 75000.0
        address: Address{
            street: '123 Main St'
            city: 'Tech City'
            zip_code: '12345'
            country: 'USA'
        }
    }
    
    // Accessing embedded fields directly
    println('Employee: ${employee.name}')        // From embedded Person
    println('Email: ${employee.email}')          // From embedded Person
    println('ID: ${employee.employee_id}')       // Direct field
    println('Department: ${employee.department}') // Direct field
    println('Address: ${employee.address.street}, ${employee.address.city}')
    
    // Bank account example
    mut account := BankAccount{
        account_number: 'ACC123456'
        owner_name: 'Alice Johnson'
        balance: 1000.0
        is_active: true
    }
    
    println('Account: ${account.account_number}, Balance: $${account.balance}')
}
```

### Methods

Methods in V are functions associated with specific types. They can have immutable or mutable receivers.

#### Instance Methods

```v
struct Circle {
    radius f64
mut:
    center_x f64
    center_y f64
}

struct Rectangle {
    width f64
    height f64
}

// Immutable receiver methods (cannot modify the struct)
fn (c Circle) area() f64 {
    return 3.14159 * c.radius * c.radius
}

fn (c Circle) circumference() f64 {
    return 2 * 3.14159 * c.radius
}

fn (r Rectangle) area() f64 {
    return r.width * r.height
}

fn (r Rectangle) perimeter() f64 {
    return 2 * (r.width + r.height)
}

// Mutable receiver methods (can modify the struct)
fn (mut c Circle) move_to(x, y f64) {
    c.center_x = x
    c.center_y = y
}

fn (mut c Circle) scale(factor f64) {
    c.radius *= factor
}

// Method with return value and side effects
fn (mut c Circle) grow_and_get_area(growth f64) f64 {
    c.radius += growth
    return c.area()
}

fn main() {
    // Using immutable methods
    circle := Circle{
        radius: 5.0
        center_x: 0.0
        center_y: 0.0
    }
    
    rectangle := Rectangle{
        width: 10.0
        height: 6.0
    }
    
    println('Circle area: ${circle.area():.2f}')
    println('Circle circumference: ${circle.circumference():.2f}')
    println('Rectangle area: ${rectangle.area():.2f}')
    println('Rectangle perimeter: ${rectangle.perimeter():.2f}')
    
    // Using mutable methods
    mut moving_circle := circle
    println('Original position: (${moving_circle.center_x}, ${moving_circle.center_y})')
    
    moving_circle.move_to(10.0, 20.0)
    println('New position: (${moving_circle.center_x}, ${moving_circle.center_y})')
    
    original_area := moving_circle.area()
    new_area := moving_circle.grow_and_get_area(2.0)
    println('Original area: ${original_area:.2f}')
    println('New area after growing: ${new_area:.2f}')
}
```

#### Static Methods and Constructors

```v
struct Color {
    r u8
    g u8
    b u8
}

// Static methods (constructors)
fn Color.new(r, g, b u8) Color {
    return Color{r, g, b}
}

fn Color.red() Color {
    return Color{255, 0, 0}
}

fn Color.green() Color {
    return Color{0, 255, 0}
}

fn Color.blue() Color {
    return Color{0, 0, 255}
}

fn Color.from_hex(hex string) ?Color {
    if hex.len != 7 || !hex.starts_with('#') {
        return error('Invalid hex color format')
    }
    
    // Simple hex parsing (in real code, use proper hex conversion)
    return Color{255, 128, 64}  // Placeholder
}

// Instance methods
fn (c Color) to_hex() string {
    return '#${c.r:02x}${c.g:02x}${c.b:02x}'
}

fn (c Color) brightness() f64 {
    return f64(c.r + c.g + c.b) / (3.0 * 255.0)
}

fn main() {
    // Using static methods (constructors)
    custom_color := Color.new(128, 64, 192)
    red_color := Color.red()
    green_color := Color.green()
    
    println('Custom color: ${custom_color.to_hex()}')
    println('Red color: ${red_color.to_hex()}')
    println('Green color: ${green_color.to_hex()}')
    
    // Using methods with error handling
    hex_color := Color.from_hex('#FF8040') or {
        println('Failed to parse hex color')
        Color.new(0, 0, 0)
    }
    
    println('Hex color: ${hex_color.to_hex()}')
    println('Brightness: ${hex_color.brightness():.2f}')
}
```

### Interfaces

Interfaces in V define a contract that types can implement. They enable polymorphism and flexible design.

#### Basic Interface Definition

```v
// Interface definition
interface Shape {
    area() f64
    perimeter() f64
}

// Implementing the interface with different structs
struct Circle {
    radius f64
}

struct Rectangle {
    width f64
    height f64
}

struct Triangle {
    base f64
    height f64
    side_a f64
    side_b f64
    side_c f64
}

// Circle implements Shape interface
fn (c Circle) area() f64 {
    return 3.14159 * c.radius * c.radius
}

fn (c Circle) perimeter() f64 {
    return 2 * 3.14159 * c.radius
}

// Rectangle implements Shape interface
fn (r Rectangle) area() f64 {
    return r.width * r.height
}

fn (r Rectangle) perimeter() f64 {
    return 2 * (r.width + r.height)
}

// Triangle implements Shape interface
fn (t Triangle) area() f64 {
    return 0.5 * t.base * t.height
}

fn (t Triangle) perimeter() f64 {
    return t.side_a + t.side_b + t.side_c
}

// Function that works with any Shape
fn print_shape_info(shape Shape) {
    println('Area: ${shape.area():.2f}')
    println('Perimeter: ${shape.perimeter():.2f}')
}

fn calculate_total_area(shapes []Shape) f64 {
    mut total := 0.0
    for shape in shapes {
        total += shape.area()
    }
    return total
}

fn main() {
    // Create different shapes
    circle := Circle{radius: 5.0}
    rectangle := Rectangle{width: 10.0, height: 6.0}
    triangle := Triangle{
        base: 8.0
        height: 6.0
        side_a: 8.0
        side_b: 10.0
        side_c: 6.0
    }
    
    // Use polymorphism
    println('Circle:')
    print_shape_info(circle)
    
    println('Rectangle:')
    print_shape_info(rectangle)
    
    println('Triangle:')
    print_shape_info(triangle)
    
    // Array of shapes (polymorphic collection)
    shapes := [Shape(circle), Shape(rectangle), Shape(triangle)]
    total_area := calculate_total_area(shapes)
    println('Total area of all shapes: ${total_area:.2f}')
}
```

#### Advanced Interface Usage

```v
// Multiple interfaces
interface Drawable {
    draw()
}

interface Movable {
    move(dx f64, dy f64)
    get_position() (f64, f64)
}

interface Scalable {
    scale(factor f64)
}

// Combined interface
interface GameEntity {
    Drawable
    Movable
    update(dt f64)
}

// Struct implementing multiple interfaces
struct Player {
mut:
    x f64
    y f64
    health int
    size f64
}

fn (p Player) draw() {
    println('Drawing player at (${p.x:.1f}, ${p.y:.1f}) with size ${p.size:.1f}')
}

fn (mut p Player) move(dx, dy f64) {
    p.x += dx
    p.y += dy
}

fn (p Player) get_position() (f64, f64) {
    return p.x, p.y
}

fn (mut p Player) scale(factor f64) {
    p.size *= factor
}

fn (mut p Player) update(dt f64) {
    // Update player logic
    if p.health < 100 {
        p.health += int(dt * 10)  // Regenerate health
    }
}

// Generic function using interfaces
fn process_drawable(drawable Drawable) {
    drawable.draw()
}

fn process_movable(mut movable Movable) {
    movable.move(1.0, 1.0)
    x, y := movable.get_position()
    println('New position: (${x:.1f}, ${y:.1f})')
}

fn main() {
    mut player := Player{
        x: 0.0
        y: 0.0
        health: 80
        size: 1.0
    }
    
    println('Initial player:')
    player.draw()
    
    // Use as Drawable
    process_drawable(player)
    
    // Use as Movable
    process_movable(mut player)
    
    // Use as Scalable
    player.scale(1.5)
    player.draw()
    
    // Use as GameEntity
    player.update(1.0)
    println('Player health after update: ${player.health}')
}
```

### Enums and Sum Types

Enums in V are powerful sum types that can hold different kinds of data.

#### Basic Enums

```v
enum Color {
    red
    green
    blue
    yellow
    purple
}

enum Status {
    pending
    processing
    completed
    failed
}

fn main() {
    mut current_color := Color.red
    println('Current color: $current_color')
    
    // Pattern matching with enums
    match current_color {
        .red => println('Stop!')
        .yellow => println('Caution!')
        .green => println('Go!')
        else => println('Unknown traffic signal')
    }
    
    // Enum with methods
    current_color = Color.blue
    println('Is primary color: ${is_primary_color(current_color)}')
    
    // Using enum in collections
    status_history := [Status.pending, Status.processing, Status.completed]
    
    for i, status in status_history {
        println('Step $i: $status')
    }
}

fn is_primary_color(color Color) bool {
    return match color {
        .red, .green, .blue => true
        else => false
    }
}
```

#### Advanced Enums with Data

```v
// Enum with associated data (algebraic data types)
enum Shape {
    circle(radius f64)
    rectangle(width f64, height f64)
    triangle(base f64, height f64)
    point
}

enum Result<T> {
    ok(T)
    error(string)
}

enum Option<T> {
    some(T)
    none
}

fn calculate_area(shape Shape) f64 {
    return match shape {
        .circle(radius) => 3.14159 * radius * radius
        .rectangle(width, height) => width * height
        .triangle(base, height) => 0.5 * base * height
        .point => 0.0
    }
}

fn safe_divide(a, b f64) Result<f64> {
    if b == 0 {
        return Result.error('Division by zero')
    }
    return Result.ok(a / b)
}

fn find_first_even(numbers []int) Option<int> {
    for num in numbers {
        if num % 2 == 0 {
            return Option.some(num)
        }
    }
    return Option.none
}

fn main() {
    // Using enum with data
    shapes := [
        Shape.circle(5.0),
        Shape.rectangle(10.0, 6.0),
        Shape.triangle(8.0, 4.0),
        Shape.point,
    ]
    
    for i, shape in shapes {
        area := calculate_area(shape)
        shape_name := match shape {
            .circle(radius) => 'Circle (r=${radius})'
            .rectangle(w, h) => 'Rectangle (${w}×${h})'
            .triangle(base, height) => 'Triangle (b=${base}, h=${height})'
            .point => 'Point'
        }
        println('Shape $i: $shape_name, Area: ${area:.2f}')
    }
    
    // Using Result type
    results := [
        safe_divide(10, 2),
        safe_divide(8, 0),
        safe_divide(15, 3),
    ]
    
    for i, result in results {
        match result {
            .ok(value) => println('Result $i: ${value:.2f}')
            .error(msg) => println('Result $i: Error - $msg')
        }
    }
    
    // Using Option type
    test_arrays := [
        [1, 3, 5, 7, 9],
        [2, 4, 6, 8],
        [1, 3, 4, 7, 9],
        []int{},
    ]
    
    for i, arr in test_arrays {
        match find_first_even(arr) {
            .some(even) => println('Array $i: First even number is $even')
            .none => println('Array $i: No even numbers found')
        }
    }
}
```

[Structs Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#structs)
[Interfaces Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#interfaces)

### 7. Maps (Key-Value Collections)

```v
// maps.v
fn main() {
    // Creating a map
    mut scores := map[string]int{}
    
    // Adding values
    scores['Alice'] = 95
    scores['Bob'] = 87
    scores['Charlie'] = 92
    
    // Accessing values
    println("Alice's score: $scores['Alice']")
    
    // Check if key exists
    if scores['David'] == 0 {
        println('David has no score recorded')
    }
    
    // Iterating through map
    for name, score in scores {
        println('$name: $score')
    }
    
    // Length of map
    println('Total students: ${scores.len}')
}
```

[Maps Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#maps)

### 8. Error Handling

```v
// error_handling.v
fn main() {
    // Option/Result pattern
    result := divide_safe(10, 2)
    if result is f64 {
        println('Result: $result')
    } else {
        println('Division by zero error')
    }
    
    // Another example with error
    result2 := divide_safe(10, 0)
    if result2 is f64 {
        println('Result: $result2')
    } else {
        println('Division by zero error')
    }
}

fn divide_safe(x, y f64) ?f64 {
    if y == 0 {
        return error('Division by zero')
    }
    return x / y
}
```

[Error Handling Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#error-handling)

### 9. Modules and Imports

```v
// math_utils.v
module math_utils

pub fn add(x, y int) int {
    return x + y
}

pub fn multiply(x, y int) int {
    return x * y
}

fn private_function() {
    println('This function is private to this module')
}
```

```v
// main.v
import math_utils

fn main() {
    result := math_utils.add(5, 3)
    println('5 + 3 = $result')
    
    result2 := math_utils.multiply(4, 7)
    println('4 * 7 = $result2')
}
```

[Modules Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#modules)

### 10. Working with Strings

```v
// strings.v
fn main() {
    // String operations
    greeting := 'Hello'
    name := 'V Language'
    
    // String concatenation
    message := greeting + ', ' + name + '!'
    println(message)
    
    // String interpolation
    println('Welcome to $name')
    
    // String methods
    println('Length: ${message.len}')
    println('Uppercase: ${message.to_upper()}')
    println('Lowercase: ${message.to_lower()}')
    
    // Substrings
    part := message[0..5]
    println('First 5 chars: $part')
    
    // Contains
    if message.contains('V Language') {
        println('Message mentions V Language')
    }
}
```

[Strings Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#strings)

### 11. File I/O

```v
// file_io.v
import os

fn main() {
    // Writing to a file
    mut file := os.create('example.txt') or {
        println('Failed to create file')
        return
    }
    
    file.write_string('Hello from V!\n')
    file.write_string('This is a test file.')
    file.close()
    
    // Reading from a file
    content := os.read_file('example.txt') or {
        println('Failed to read file')
        return
    }
    
    println('File content:')
    println(content)
    
    // Clean up
    os.remove('example.txt')
}
```

[File I/O Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#file-io)

### 12. Concurrency (Goroutines-like)

```v
// concurrency.v
import time

fn main() {
    // Spawn a "thread" (actually a coroutine)
    go calculate_something(5)
    go calculate_something(3)
    
    // Wait a bit to let goroutines finish
    time.sleep(2 * time.second)
    
    println('Main function finished')
}

fn calculate_something(seconds int) {
    println('Starting calculation for $seconds seconds')
    time.sleep(seconds * time.second)
    println('Finished calculation after $seconds seconds')
}
```

[Concurrency Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#concurrency)

## Standard Library Examples

### Working with JSON

```v
// json_example.v
import json

struct User {
    name string
    age  int
    city string
}

fn main() {
    // Create a struct
    user := User{
        name: 'Alice'
        age: 30
        city: 'New York'
    }
    
    // Serialize to JSON
    json_data := json.encode(user)
    println('JSON: $json_data')
    
    // Deserialize from JSON
    user2 := json.decode(User, json_data) or {
        println('Failed to decode JSON')
        return
    }
    
    println('Name: $user2.name, Age: $user2.age, City: $user2.city')
}
```

[JSON Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#json)

### HTTP Client

```v
// http_client.v
import net.http

fn main() {
    // Simple GET request
    resp := http.get('https://httpbin.org/get') or {
        println('Failed to make request')
        return
    }
    
    println('Status: $resp.status_code')
    println('Response: $resp.body')
}
```

[HTTP Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#http-client)

### Working with Time

```v
// time_example.v
import time

fn main() {
    // Current time
    now := time.now()
    println('Current time: $now')
    
    // Format time
    formatted := now.format()
    println('Formatted: $formatted')
    
    // Sleep
    println('Sleeping for 1 second...')
    time.sleep(1 * time.second)
    println('Awake!')
    
    // Measure execution time
    start := time.now()
    time.sleep(500 * time.millisecond)
    end := time.now()
    
    duration := end.sub(start)
    println('Slept for ${duration.milliseconds()} milliseconds')
}
```

[Time Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#time)

## Memory Management {#memory-management}

V provides automatic memory management with compile-time safety checks. Unlike garbage-collected languages, V manages memory at compile time, ensuring both safety and performance.

### Automatic Memory Management

```v
fn main() {
    // Stack allocation (automatic cleanup)
    local_array := [1, 2, 3, 4, 5]
    local_string := 'Hello, V!'
    
    // Memory is automatically freed when variables go out of scope
    {
        scoped_data := [10, 20, 30]
        println('Scoped data: $scoped_data')
    } // scoped_data is automatically freed here
    
    // Heap allocation when needed
    large_array := []int{len: 1000000, init: index}
    println('Large array created with ${large_array.len} elements')
    
    // String operations don't cause memory leaks
    mut result := ''
    for i in 0..1000 {
        result += 'item $i, '
    }
    println('String built with ${result.len} characters')
    
    // All memory is automatically cleaned up at function end
}
```

### References and Borrowing

```v
struct Person {
    name string
    age int
mut:
    email string
}

// Function that borrows a reference (doesn't take ownership)
fn print_person_info(person &Person) {
    println('Name: ${person.name}, Age: ${person.age}')
}

// Function that takes mutable reference
fn update_email(mut person Person) {
    person.email = 'updated@example.com'
}

// Function that takes ownership
fn consume_person(person Person) {
    println('Consuming person: ${person.name}')
    // person is freed at end of function
}

fn main() {
    mut person := Person{
        name: 'Alice'
        age: 30
        email: 'alice@example.com'
    }
    
    // Borrowing (reference) - original still accessible
    print_person_info(&person)
    println('Person still accessible: ${person.name}')
    
    // Mutable borrowing
    update_email(mut person)
    println('Updated email: ${person.email}')
    
    // Moving ownership (person becomes inaccessible after this)
    consume_person(person)
    // println(person.name)  // Error: person was moved
}
```

## Error Handling {#error-handling}

V uses Option and Result types instead of exceptions for error handling, making error cases explicit and compile-time checked.

### Option Types

```v
fn main() {
    // Option types represent values that might not exist
    numbers := [1, 2, 3, 4, 5]
    
    // Safe array access
    first := safe_get(numbers, 0)
    invalid := safe_get(numbers, 10)
    
    // Handle Option with if-unwrap
    if value := first {
        println('First element: $value')
    }
    
    if value := invalid {
        println('Invalid element: $value')
    } else {
        println('No element at index 10')
    }
    
    // Handle Option with match
    match safe_get(numbers, 2) {
        some(val) => println('Element at index 2: $val')
        none => println('No element found')
    }
    
    // Option with or-block (provide default)
    element := safe_get(numbers, 100) or { -1 }
    println('Element with default: $element')
    
    // Chaining Option operations
    result := safe_get(numbers, 3)
        .map(fn (x int) int { return x * 2 })
        .unwrap_or(0)
    println('Chained result: $result')
}

fn safe_get(arr []int, index int) ?int {
    if index < 0 || index >= arr.len {
        return none
    }
    return arr[index]
}
```

### Result Types for Error Handling

```v
import os

fn main() {
    // Result types for operations that can fail
    file_results := [
        read_file_content('existing_file.txt'),
        read_file_content('nonexistent_file.txt'),
    ]
    
    for i, result in file_results {
        match result {
            Ok(content) => println('File $i content: $content')
            Err(error) => println('File $i error: $error')
        }
    }
    
    // Using or-block with Result
    content := read_file_content('config.txt') or {
        println('Using default config due to error: $err')
        'default_config=true'
    }
    println('Config: $content')
    
    // Error propagation
    if processed := process_config_file('settings.txt') {
        println('Processed config: $processed')
    } else {
        println('Failed to process config')
    }
}

fn read_file_content(path string) !string {
    // Simulate file reading
    if path.contains('nonexistent') {
        return error('File not found: $path')
    }
    if path.contains('existing') {
        return 'file content here'
    }
    return 'default content'
}

fn process_config_file(path string) ?[]string {
    content := read_file_content(path) or { return none }
    
    if content.len == 0 {
        return none
    }
    
    return content.split('\n')
}
```

### Custom Error Types

```v
enum ValidationError {
    empty_string
    too_short(min_length int)
    too_long(max_length int)
    invalid_format(expected string)
}

struct User {
    name string
    email string
    age int
}

fn validate_user(user User) !User {
    // Validate name
    if user.name.len == 0 {
        return ValidationError.empty_string
    }
    if user.name.len < 2 {
        return ValidationError.too_short(2)
    }
    if user.name.len > 50 {
        return ValidationError.too_long(50)
    }
    
    // Validate email
    if !user.email.contains('@') {
        return ValidationError.invalid_format('email@domain.com')
    }
    
    // Validate age
    if user.age < 0 || user.age > 150 {
        return error('Age must be between 0 and 150')
    }
    
    return user
}

fn main() {
    test_users := [
        User{name: 'Alice', email: 'alice@example.com', age: 30},
        User{name: '', email: 'bob@example.com', age: 25},
        User{name: 'Charlie', email: 'invalid-email', age: 35},
        User{name: 'Diana', email: 'diana@example.com', age: -5},
    ]
    
    for i, user in test_users {
        match validate_user(user) {
            Ok(valid_user) => {
                println('User $i is valid: ${valid_user.name}')
            }
            Err(ValidationError.empty_string) => {
                println('User $i error: Name cannot be empty')
            }
            Err(ValidationError.too_short(min)) => {
                println('User $i error: Name too short (min: $min)')
            }
            Err(ValidationError.too_long(max)) => {
                println('User $i error: Name too long (max: $max)')
            }
            Err(ValidationError.invalid_format(expected)) => {
                println('User $i error: Invalid format, expected: $expected')
            }
            Err(error) => {
                println('User $i error: $error')
            }
        }
    }
}
```

## Best Practices {#best-practices}

### Code Organization

```v
// main.v - Main application entry point
import os
import json
import net.http
import myapp.models
import myapp.services
import myapp.utils

fn main() {
    config := load_config() or {
        eprintln('Failed to load config: $err')
        exit(1)
    }
    
    app := create_app(config)
    app.run() or {
        eprintln('Application error: $err')
        exit(1)
    }
}

fn load_config() !Config {
    content := os.read_file('config.json')?
    return json.decode(Config, content)
}
```

### Performance Tips

```v
fn main() {
    // Use string builder for concatenating many strings
    mut sb := strings.new_builder(1000)  // Pre-allocate capacity
    for i in 0..100 {
        sb.write_string('Item $i\n')
    }
    result := sb.str()
    
    // Use arrays with known capacity when possible
    mut items := []string{cap: 1000}
    for i in 0..1000 {
        items << 'item $i'
    }
    
    // Prefer immutable data when possible
    readonly_config := Config{
        debug: true
        port: 8080
        host: 'localhost'
    }
    
    // Use references to avoid copying large structs
    process_large_data(&large_dataset)
}

struct Config {
    debug bool
    port int
    host string
}

fn process_large_data(data &[]int) {
    // Process without copying the array
    println('Processing ${data.len} items')
}
```

### Safety Guidelines

```v
fn main() {
    // Always handle errors explicitly
    result := risky_operation() or {
        eprintln('Operation failed: $err')
        return
    }
    
    // Use Option/Result types instead of null values
    user := find_user_by_id(123)
    if u := user {
        println('Found user: ${u.name}')
    } else {
        println('User not found')
    }
    
    // Validate input data
    input := get_user_input()
    validated := validate_input(input) or {
        eprintln('Invalid input: $err')
        return
    }
    
    process_validated_data(validated)
}

fn risky_operation() !string {
    // Simulate an operation that might fail
    return 'success'
}

fn find_user_by_id(id int) ?User {
    // Simulate user lookup
    if id > 0 {
        return User{name: 'User $id', email: 'user$id@example.com', age: 25}
    }
    return none
}

fn validate_input(input string) !string {
    if input.len == 0 {
        return error('Input cannot be empty')
    }
    return input.trim_space()
}

fn get_user_input() string {
    return 'sample input'
}

fn process_validated_data(data string) {
    println('Processing: $data')
}
```

## Building and Running V Programs

### Command Line Tools

```bash
# Run a V program directly
v run main.v

# Compile to executable
v main.v                    # Creates 'main' executable
v -o myapp main.v          # Creates 'myapp' executable

# Production build (optimized)
v -prod main.v

# Cross-compilation
v -os windows main.v       # Compile for Windows
v -os macos main.v         # Compile for macOS
v -os linux main.v         # Compile for Linux

# Check syntax without running
v check main.v

# Format code
v fmt main.v              # Format single file
v fmt .                   # Format all .v files in directory

# Run tests
v test .                  # Run all tests in current directory
v test mymodule_test.v    # Run specific test file

# Create documentation
v doc mymodule            # Generate documentation
```

### Project Structure

```text
myproject/
├── v.mod                 # Module definition
├── main.v               # Main entry point
├── src/
│   ├── models/
│   │   ├── user.v
│   │   └── product.v
│   ├── services/
│   │   ├── auth.v
│   │   └── database.v
│   └── utils/
│       ├── helpers.v
│       └── validators.v
├── tests/
│   ├── user_test.v
│   └── auth_test.v
├── docs/
└── README.md
```

## Conclusion

This comprehensive guide has covered the essential aspects of V programming:

### Key Takeaways

1. **Safety First**: V's design prioritizes memory safety and compile-time error detection
2. **Performance**: Fast compilation and execution with minimal runtime overhead
3. **Simplicity**: Clean, readable syntax that's easy to learn and maintain
4. **Modern Features**: Built-in concurrency, generics, and powerful pattern matching

### What Makes V Special

- **No null pointers**: Option and Result types eliminate null pointer exceptions
- **Immutable by default**: Variables are immutable unless explicitly declared mutable
- **Compile-time memory management**: No garbage collector needed
- **C interop**: Easy integration with existing C libraries
- **Fast compilation**: Compile times measured in milliseconds

### Next Steps

1. **Practice**: Start with small programs and gradually build more complex applications
2. **Explore the standard library**: V has a rich standard library for common tasks
3. **Join the community**: Participate in discussions and contribute to the ecosystem
4. **Build real projects**: Apply V to solve real-world problems

### Resources

- [Official V Documentation](https://github.com/vlang/v/blob/master/doc/docs.md)
- [V Language Website](https://vlang.io/)
- [V GitHub Repository](https://github.com/vlang/v)
- [V Community Discord](https://discord.gg/vlang)
- [V Examples Repository](https://github.com/vlang/v/tree/master/examples)

V is an excellent choice for system programming, web development, game development, and any application where performance and safety are important. Its simple syntax and powerful features make it suitable for both beginners learning programming and experienced developers building production systems.

Happy coding with V! 🚀