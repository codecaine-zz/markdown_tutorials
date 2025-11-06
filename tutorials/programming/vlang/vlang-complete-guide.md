# V Language Tutorial for Beginners with Official Documentation Links

I'll create a comprehensive tutorial to help you learn V programming language quickly with links to official documentation for each concept.

## Installation on ARM Mac with Homebrew

```bash
# Install V using Homebrew
brew install vlang

# Verify installation
v version

# Install VS Code extension for V
# In VS Code, search for "V" extension by vlang or install manually
```

[Official Installation Guide](https://github.com/vlang/v/blob/master/doc/docs.md#installing-v)

## Basic Syntax Examples

### 1. Hello World

```v
// main.v
fn main() {
    println('Hello, World!')
}
```

[Functions Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#functions)

To run:
```bash
v run main.v
```

### 2. Variables and Data Types

```v
// variables.v
fn main() {
    // Immutable variables (default)
    name := 'V Language'
    age := 5
    is_fast := true
    
    // Mutable variables
    mut count := 0
    count = 10
    
    // Explicit typing
    height f32 = 5.9
    
    println('Name: $name')
    println('Age: $age')
    println('Is Fast: $is_fast')
    println('Count: $count')
    println('Height: $height')
}
```

[Variables Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#variables)

### 3. Functions

```v
// functions.v
fn main() {
    say_hello('World')
    
    result := add(5, 3)
    println('5 + 3 = $result')
    
    // Multiple return values
    quotient, remainder := divide(10, 3)
    println('10 / 3 = $quotient remainder $remainder')
}

fn say_hello(name string) {
    println('Hello, $name!')
}

fn add(x int, y int) int {
    return x + y
}

// Shorthand syntax for simple functions
fn multiply(x, y int) int {
    return x * y
}

fn divide(dividend, divisor int) (int, int) {
    return dividend / divisor, dividend % divisor
}
```

[Functions Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#functions)

### 4. Control Flow

```v
// control_flow.v
fn main() {
    // If statements
    age := 20
    if age >= 18 {
        println('You are an adult')
    } else if age >= 13 {
        println('You are a teenager')
    } else {
        println('You are a child')
    }
    
    // For loops
    println('Counting to 5:')
    for i := 0; i < 5; i++ {
        println(i)
    }
    
    // For loop with range (array)
    numbers := [1, 2, 3, 4, 5]
    println('Array elements:')
    for num in numbers {
        println(num)
    }
    
    // Match (like switch but more powerful)
    day := 'Monday'
    match day {
        'Monday' => println('Start of work week')
        'Friday' => println('End of work week')
        else => println('Mid-week day')
    }
}
```

[Control Flow Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#control-flow)

### 5. Arrays and Slices

```v
// arrays_slices.v
fn main() {
    // Fixed-size arrays
    mut numbers := [1, 2, 3, 4, 5]
    println('First element: $numbers[0]')
    println('Array length: ${numbers.len}')
    
    // Modifying array
    numbers[0] = 10
    println('Modified first element: $numbers[0]')
    
    // Slices (dynamic arrays)
    mut dynamic_list := []int{}
    dynamic_list << 1  // Append
    dynamic_list << 2
    dynamic_list << 3
    
    println('Slice: $dynamic_list')
    println('Slice length: ${dynamic_list.len}')
    
    // Slice from array
    subset := numbers[1..3]  // Elements from index 1 to 2
    println('Subset: $subset')
}
```

[Arrays Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#arrays)

### 6. Structs (Custom Data Types)

```v
// structs.v
struct Person {
    name string
    age  int
mut:
    is_happy bool
}

fn main() {
    // Creating a struct instance
    mut person := Person{
        name: 'Alice'
        age: 30
        is_happy: true
    }
    
    println('Name: $person.name')
    println('Age: $person.age')
    
    // Modifying mutable fields
    person.is_happy = false
    println('Is happy: $person.is_happy')
    
    // Using methods with structs
    person.greet()
    person.have_birthday()
    println('New age: $person.age')
}

// Method for Person struct
fn (p Person) greet() {
    println('Hello, my name is $p.name')
}

// Method that modifies the struct
fn (mut p Person) have_birthday() {
    p.age++
}
```

[Structs Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#structs)

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

## VS Code Configuration

Create these files in your project root:

### .vscode/settings.json
```json
{
    "files.associations": {
        "*.v": "v"
    },
    "editor.formatOnSave": true
}
```

[VS Code V Extension](https://marketplace.visualstudio.com/items?itemName=vlang.vscode-vlang)

### .vscode/tasks.json
```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "run v program",
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
                "panel": "shared"
            }
        }
    ]
}
```

## Quick Reference Summary

```v
// quick_reference.v
fn main() {
    // Variables
    immutable_var := 'value'  // Cannot change
    mut mutable_var := 'value'  // Can change
    
    // Basic types
    age int = 25
    price f64 = 99.99
    is_active bool = true
    name string = 'V'
    
    // Arrays
    fixed_array := [1, 2, 3]  // Fixed size
    dynamic_array := [1, 2, 3].clone()  // Actually dynamic
    
    // Maps
    scores := map[string]int{
        'Alice': 95
        'Bob': 87
    }
    
    // Structs
    struct Point {
        x int
        y int
    }
    
    // Functions
    fn add(x, y int) int {
        return x + y
    }
    
    // Error handling
    fn might_fail() ?int {
        if true {
            return 42
        } else {
            return error('Something went wrong')
        }
    }
}
```

[Language Reference](https://github.com/vlang/v/blob/master/doc/docs.md#language-reference)

## Building and Running

```bash
# Run a V program
v run main.v

# Compile to executable
v main.v
./main

# Compile with optimizations
v -prod main.v

# Check for errors without running
v check main.v
```

[Compilation Documentation](https://github.com/vlang/v/blob/master/doc/docs.md#compiling)

This tutorial covers the essential concepts of V programming language with practical examples and links to official documentation. Start with the basic examples and gradually work through more complex concepts. V's syntax is designed to be readable and concise, making it great for both beginners and experienced programmers.