# V Programming Language Tutorial: From Basics to Standard Library

This tutorial will guide you through learning V programming language from scratch to using its standard library. We'll cover everything with clear explanations and examples that even non-programmers can understand.

## Table of Contents
1. [Introduction to V Language](#introduction)
2. [Installation](#installation)
3. [Basic Syntax](#basic-syntax)
4. [Data Types](#data-types)
5. [Control Flow](#control-flow)
6. [Functions](#functions)
7. [Structs and Methods](#structs-and-methods)
8. [Modules and Imports](#modules-and-imports)
9. [Error Handling](#error-handling)
10. [Standard Library Overview](#standard-library)
11. [File I/O](#file-io)
12. [Networking](#networking)
13. [JSON Handling](#json-handling)
14. [Best Practices](#best-practices)

## Introduction to V Language {#introduction}

V is a simple, fast, safe, compiled language for developing maintainable software. It's similar to Go but with additional features and safety mechanisms.

Key features of V:
- Compiles to native machine code
- Zero-cost memory management (no garbage collector by default)
- Built-in error handling
- Immutable by default
- Thread-safe
- Cross-platform

Think of V as a modern version of C with built-in safety features that prevent common programming errors. It's designed to be easy to learn and use while still being powerful enough for complex applications.

## Installation {#installation}

Installing V is straightforward. The best way is to install it from source:

### Linux, macOS, Windows, *BSD, Solaris, WSL, etc.

1. First, make sure you have `git` installed on your system:
   - On Ubuntu/Debian: `sudo apt install git build-essential make`
   - On macOS: Install Xcode command line tools with `xcode-select --install`
   - On Windows: Install Git from https://git-scm.com/

2. Open your terminal/command prompt and run:
```bash
git clone --depth=1 https://github.com/vlang/v
cd v
make
```

3. That's it! You should now have a V executable in the `v` directory.

4. To make V available system-wide, run:
```bash
sudo ./v symlink
```
On Windows, run the command prompt as administrator and execute `v symlink`.

5. Test your installation:
```bash
v run examples/hello_world.v
```

You should see "Hello, World!" printed to your terminal.

## Basic Syntax {#basic-syntax}

Let's start with a simple "Hello, World!" program to understand V's basic syntax:

```v
// This is a comment in V
// Comments start with // and are ignored by the compiler

fn main() {
    // The main function is the entry point of every V program
    println('Hello, World!')
}
```

Key syntax elements:
- Functions are declared with `fn` keyword
- `main()` is the special function where your program starts
- Curly braces `{}` define code blocks
- `println()` is a built-in function that prints text followed by a new line
- Strings are enclosed in single quotes `'`
- Statements end with a newline (no semicolon required)

Let's look at variables:

```v
fn main() {
    // Declaring a variable with explicit type
    name := 'V Language'
    
    // V can infer types automatically
    age := 5  // This will be an integer
    
    // You can also explicitly declare types
    mut count := 0  // mut means the variable can be changed
    
    // Changing a mutable variable
    count = 10
    
    // Printing variables
    println('Name: $name')  // String interpolation with $
    println('Age: $age')
    println('Count: $count')
}
```

Key points about variables:
- `:=` is used to declare and initialize variables
- `mut` keyword makes a variable mutable (changeable)
- By default, variables are immutable (cannot be changed)
- `$variable` is used for string interpolation
- V has type inference, so you don't always need to specify types

## Data Types {#data-types}

V has several built-in data types:

```v
fn main() {
    // Numbers
    age := 25         // int (integer)
    height := 5.9     // f64 (64-bit floating point)
    weight := 70.5f32 // f32 (32-bit floating point)
    
    // Strings
    name := 'John'
    greeting := 'Hello, $name!'
    
    // Booleans
    is_student := true
    is_working := false
    
    // Arrays (fixed size)
    numbers := [1, 2, 3, 4, 5]
    names := ['Alice', 'Bob', 'Charlie']
    
    // Slices (dynamic arrays)
    mut dynamic_numbers := [1, 2, 3]
    dynamic_numbers << 4  // Add element to slice
    
    // Maps (key-value pairs)
    ages := {
        'Alice': 25
        'Bob': 30
        'Charlie': 35
    }
    
    println('Name: $name, Age: $age')
    println('Numbers: $numbers')
    println('Ages: $ages')
}
```

Important data type concepts:
- Arrays have fixed sizes defined at compile time
- Slices are dynamic arrays that can grow or shrink
- `<<` operator adds elements to slices
- Maps store key-value pairs for quick lookup

## Control Flow {#control-flow}

Control flow structures help your program make decisions and repeat actions:

```v
fn main() {
    // If statements
    age := 18
    
    if age >= 18 {
        println('You are an adult')
    } else if age >= 13 {
        println('You are a teenager')
    } else {
        println('You are a child')
    }
    
    // For loops
    // Loop through numbers
    for i := 0; i < 5; i++ {
        println('Number: $i')
    }
    
    // Loop through array elements
    fruits := ['apple', 'banana', 'orange']
    for fruit in fruits {
        println('Fruit: $fruit')
    }
    
    // Loop with index
    for i, fruit in fruits {
        println('Fruit $i: $fruit')
    }
    
    // While loop
    mut counter := 0
    while counter < 3 {
        println('Counter: $counter')
        counter++
    }
    
    // Match (similar to switch)
    day := 'Monday'
    match day {
        'Monday' => println('Start of the work week')
        'Friday' => println('End of the work week')
        else => println('Mid-week day')
    }
}
```

Control flow key points:
- `if/else` statements make decisions
- `for` loops repeat actions
- `while` loops continue while a condition is true
- `match` compares a value against multiple possibilities

## Functions {#functions}

Functions are reusable blocks of code that perform specific tasks:

```v
// Simple function with no parameters
fn greet() {
    println('Hello!')
}

// Function with parameters
fn greet_person(name string) {
    println('Hello, $name!')
}

// Function that returns a value
fn add(x int, y int) int {
    return x + y
}

// Function with multiple return values
fn divide(dividend int, divisor int) (int, int) {
    quotient := dividend / divisor
    remainder := dividend % divisor
    return quotient, remainder
}

fn main() {
    greet()
    greet_person('Alice')
    
    result := add(5, 3)
    println('5 + 3 = $result')
    
    // Multiple return values
    quotient, remainder := divide(10, 3)
    println('10 / 3 = $quotient remainder $remainder')
    
    // Ignoring one return value with underscore
    q, _ := divide(15, 4)
    println('Quotient: $q')
}
```

Function key points:
- Functions are declared with `fn` keyword
- Parameters are specified as `name type`
- Return type is specified after parameters
- Multiple values can be returned using `(type1, type2)`
- Use `_` to ignore return values you don't need

## Structs and Methods {#structs-and-methods}

Structs are custom data types that group related data together:

```v
// Define a struct (like a blueprint for data)
struct Person {
    name string
    age  int
}

// Methods are functions associated with a struct
fn (p Person) greet() {
    println('Hi, I am $p.name and I am $p.age years old')
}

// Method that can modify the struct (receiver is mutable)
fn (mut p Person) have_birthday() {
    p.age++
}

// Function that creates a new Person
fn new_person(name string, age int) Person {
    return Person{name: name, age: age}
}

fn main() {
    // Create a new Person
    mut person := new_person('Alice', 25)
    
    // Call methods
    person.greet()
    
    // Have a birthday (modifies the person)
    person.have_birthday()
    
    // Check the new age
    person.greet()
    
    // Direct access to fields
    println('Name: $person.name')
    println('Age: $person.age')
}
```

Struct and method key points:
- Structs group related data together
- Methods are functions associated with structs
- `(p Person)` is a receiver - it connects the method to the struct
- `mut` in receiver allows the method to modify the struct
- Struct fields are accessed with dot notation `person.name`

## Modules and Imports {#modules-and-imports}

V organizes code into modules for better structure and reusability:

```v
// math_utils.v - A custom module
module math_utils

// Public function (starts with capital letter)
pub fn add(x int, y int) int {
    return x + y
}

// Private function (starts with lowercase letter)
fn multiply(x int, y int) int {
    return x * y
}

// Public function that uses private function
pub fn calculate_area(length int, width int) int {
    return multiply(length, width)
}
```

```v
// main.v - Using the custom module
import math_utils

fn main() {
    result := math_utils.add(5, 3)
    println('5 + 3 = $result')
    
    area := math_utils.calculate_area(10, 5)
    println('Area: $area')
    
    // Using standard library modules
    import os
    import time
    
    // Get current directory
    current_dir := os.getwd() or { '' }
    println('Current directory: $current_dir')
    
    // Get current time
    now := time.now()
    println('Current time: $now')
}
```

Module key points:
- Each file is a module
- `pub` keyword makes functions/structs accessible from other modules
- Import modules with `import module_name`
- Access imported functions with `module_name.function_name()`
- Standard library modules are imported the same way

## Error Handling {#error-handling}

V has a built-in error handling system that makes errors explicit and hard to ignore:

```v
import os

// Function that can return an error
fn read_file_content(filename string) ?string {
    // The ? means this function might return an error
    contents := os.read_file(filename) or {
        // This block runs if there's an error
        println('Error reading file: $err')
        return error('Failed to read file: $err')
    }
    return contents
}

// Function that handles the error
fn process_file(filename string) {
    contents := read_file_content(filename) or {
        // Handle the error from our function
        println('Could not process file: $err')
        return
    }
    
    // If we get here, we have the file contents
    println('File contents: $contents')
}

fn main() {
    // Try to read a file that doesn't exist
    process_file('nonexistent.txt')
    
    // Create a file and read it
    mut file := os.create('test.txt') or {
        println('Could not create file: $err')
        return
    }
    file.write_string('Hello, V!') or {
        println('Could not write to file: $err')
        return
    }
    file.close()
    
    // Now read it
    process_file('test.txt')
}
```

Error handling key points:
- Functions that can fail have `?` before their return type
- `or {}` blocks handle errors
- `err` is a special variable containing the error message
- `return error('message')` creates a new error
- Errors must be handled explicitly - they can't be ignored

## Standard Library Overview {#standard-library}

V comes with a comprehensive standard library that provides common functionality:

```v
// Importing standard library modules
import os       // File system operations
import time     // Time and date functions
import net.http // HTTP client
import json     // JSON encoding/decoding
import math     // Mathematical functions

fn main() {
    // OS module examples
    current_dir := os.getwd() or { '' }
    println('Current directory: $current_dir')
    
    // Check if file exists
    exists := os.file_exists('main.v')
    println('main.v exists: $exists')
    
    // Time module examples
    now := time.now()
    println('Current time: $now')
    println('Formatted time: $now.format()')
    
    // Math module examples
    println('Square root of 16: ${math.sqrt(16)}')
    println('Pi: $math.pi')
    
    // Array functions
    numbers := [3, 1, 4, 1, 5, 9, 2, 6]
    println('Original: $numbers')
    
    // Sort the array
    numbers.sort()
    println('Sorted: $numbers')
    
    // Find maximum value
    max := numbers.max()
    println('Maximum: $max')
}
```

Key standard library modules:
- `os`: File system operations, environment variables
- `time`: Date/time handling
- `net.http`: HTTP client for web requests
- `json`: JSON encoding and decoding
- `math`: Mathematical functions
- `strings`: String manipulation
- `arrays`: Array utilities

## File I/O {#file-io}

Working with files is common in most programs:

```v
import os

// Reading a file
fn read_file(filename string) ?string {
    contents := os.read_file(filename) or {
        return error('Failed to read file: $err')
    }
    return contents
}

// Writing to a file
fn write_file(filename string, content string) ? {
    mut file := os.create(filename) or {
        return error('Failed to create file: $err')
    }
    defer file.close() // Ensures file is closed even if error occurs
    
    file.write_string(content) or {
        return error('Failed to write to file: $err')
    }
    return
}

// Append to a file
fn append_to_file(filename string, content string) ? {
    mut file := os.open_file_append(filename) or {
        return error('Failed to open file: $err')
    }
    defer file.close()
    
    file.write_string(content) or {
        return error('Failed to write to file: $err')
    }
    return
}

fn main() {
    filename := 'example.txt'
    
    // Write initial content
    write_file(filename, 'Hello, V!\nThis is line 2.') or {
        println('Error: $err')
        return
    }
    
    // Read the file
    content := read_file(filename) or {
        println('Error: $err')
        return
    }
    println('File content:\n$content')
    
    // Append to the file
    append_to_file(filename, '\nThis is appended content.') or {
        println('Error: $err')
        return
    }
    
    // Read again to see appended content
    content = read_file(filename) or {
        println('Error: $err')
        return
    }
    println('Updated content:\n$content')
    
    // List files in current directory
    files := os.ls('.') or {
        println('Error listing files: $err')
        return
    }
    println('Files in current directory: $files')
}
```

File I/O key points:
- `os.read_file()` reads entire file content
- `os.create()` creates a new file for writing
- `defer` ensures cleanup code runs (like closing files)
- `or {}` blocks handle errors from file operations
- `os.ls()` lists directory contents

## Networking {#networking}

V makes networking straightforward with its standard library:

```v
import net.http
import time

// Simple HTTP GET request
fn fetch_webpage(url string) ?string {
    resp := http.get(url) or {
        return error('Failed to fetch $url: $err')
    }
    return resp.body
}

// HTTP POST request with JSON data
fn post_json_data(url string, json_data string) ?string {
    headers := {
        'Content-Type': 'application/json'
    }
    
    resp := http.post(url, json_data, headers) or {
        return error('Failed to POST to $url: $err')
    }
    return resp.body
}

fn main() {
    // Fetch a webpage
    content := fetch_webpage('https://httpbin.org/get') or {
        println('Error: $err')
        return
    }
    println('Fetched content length: $content.len')
    
    // Simple web server example (commented out as it would block)
    /*
    import vweb
    
    app := vweb.new_app()
    app.handle_func('/', fn (req vweb.Request) vweb.Response {
        return vweb.response('Hello from V web server!')
    })
    
    println('Server starting on port 8080...')
    app.run(8080)
    */
}
```

Networking key points:
- `net.http.get()` makes HTTP GET requests
- `net.http.post()` makes HTTP POST requests
- Headers can be passed as maps
- Responses contain status, headers, and body
- Web servers can be built with the `vweb` module

## JSON Handling {#json-handling}

JSON is widely used for data exchange. V makes JSON handling easy:

```v
import json

// Define structs that match JSON structure
struct User {
    name          string
    age           int
    email         string
    is_registered bool
}

struct ApiResponse {
    users []User
    total int
}

// Convert JSON string to struct
fn parse_user(json_str string) ?User {
    user := json.decode(User, json_str) or {
        return error('Failed to parse JSON: $err')
    }
    return user
}

// Convert struct to JSON string
fn user_to_json(user User) string {
    return json.encode(user)
}

fn main() {
    // JSON string representing a user
    json_data := '{"name":"Alice","age":25,"email":"alice@example.com","is_registered":true}'
    
    // Parse JSON into struct
    user := parse_user(json_data) or {
        println('Error parsing user: $err')
        return
    }
    
    println('Parsed user:')
    println('Name: $user.name')
    println('Age: $user.age')
    println('Email: $user.email')
    println('Registered: $user.is_registered')
    
    // Convert struct back to JSON
    json_output := user_to_json(user)
    println('JSON output: $json_output')
    
    // Working with arrays of objects
    users_json := '[{"name":"Alice","age":25},{"name":"Bob","age":30}]'
    
    users := json.decode([]User, users_json) or {
        println('Error parsing users: $err')
        return
    }
    
    println('Parsed ${users.len} users:')
    for user in users {
        println('- $user.name, age $user.age')
    }
}
```

JSON handling key points:
- Define structs that match your JSON structure
- `json.decode()` converts JSON strings to structs
- `json.encode()` converts structs to JSON strings
- Struct field names should match JSON keys (case-sensitive)
- Arrays are handled with `[]Type` syntax

## Best Practices {#best-practices}

Here are important best practices for writing clean, maintainable V code:

```v
// 1. Use descriptive names
// Good:
fn calculate_circle_area(radius f64) f64 {
    return 3.14159 * radius * radius
}

// Avoid:
fn calc(r f64) f64 {
    return 3.14159 * r * r
}

// 2. Keep functions small and focused
// Good: Each function does one thing
fn validate_email(email string) bool {
    return email.contains('@') && email.contains('.')
}

fn send_welcome_email(user_email string) ? {
    if !validate_email(user_email) {
        return error('Invalid email address')
    }
    // Send email logic here
    return
}

// 3. Handle errors explicitly
fn safe_divide(dividend int, divisor int) ?int {
    if divisor == 0 {
        return error('Division by zero')
    }
    return dividend / divisor
}

// 4. Use immutable variables by default
fn process_data(data []int) []int {
    // Create new array instead of modifying input
    mut result := []int{}
    for value in data {
        if value > 0 {
            result << value * 2
        }
    }
    return result
}

// 5. Add comments for complex logic
fn complex_calculation(input int) int {
    // Apply business rule: only process positive numbers
    if input <= 0 {
        return 0
    }
    
    // Apply transformation based on size
    if input > 100 {
        return input * 3  // Large numbers get tripled
    } else {
        return input * 2  // Small numbers get doubled
    }
}

// 6. Use structs to group related data
struct BankAccount {
    owner     string
    balance   f64
    account_number int
}

fn (mut acc BankAccount) deposit(amount f64) ? {
    if amount <= 0 {
        return error('Deposit amount must be positive')
    }
    acc.balance += amount
    return
}

fn (mut acc BankAccount) withdraw(amount f64) ? {
    if amount <= 0 {
        return error('Withdrawal amount must be positive')
    }
    if amount > acc.balance {
        return error('Insufficient funds')
    }
    acc.balance -= amount
    return
}

fn main() {
    // Example usage following best practices
    mut account := BankAccount{
        owner: 'John Doe'
        balance: 1000.0
        account_number: 12345
    }
    
    account.deposit(500.0) or {
        println('Deposit failed: $err')
        return
    }
    
    account.withdraw(200.0) or {
        println('Withdrawal failed: $err')
        return
    }
    
    println('Account balance: $account.balance')
}
```

Best practices summary:
1. Use descriptive variable and function names
2. Keep functions small and focused on one task
3. Handle errors explicitly with `or {}` blocks
4. Use immutable variables by default (avoid `mut` when possible)
5. Add comments to explain complex logic
6. Group related data in structs
7. Validate inputs and handle edge cases
8. Follow consistent formatting (use `v fmt`)

## Conclusion

This tutorial covered the fundamentals of V programming language, from basic syntax to advanced features like error handling and standard library usage. V combines the performance of compiled languages with the safety features and ease of use that make programming more enjoyable and less error-prone.

Key takeaways:
- V is designed to be simple, fast, and safe
- Error handling is explicit and hard to ignore
- The standard library provides comprehensive functionality
- Best practices emphasize readability and maintainability
- V is suitable for a wide range of applications from system programming to web development

To continue learning V:
1. Explore the official documentation at https://github.com/vlang/v/blob/master/doc/docs.md
2. Try the examples in the `examples/` directory of the V repository
3. Join the V community on Discord for support and discussions
4. Build small projects to practice what you've learned

Remember that programming is a skill that improves with practice. Start with simple programs and gradually work your way up to more complex applications. Happy coding with V!