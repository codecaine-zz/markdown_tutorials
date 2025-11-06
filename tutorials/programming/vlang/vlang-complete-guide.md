# Complete Tutorial on Using V Language and Its Standard Library

## Table of Contents
1. [Introduction to V Language](#introduction-to-v-language)
2. [Installing V](#installing-v)
3. [Basic Syntax](#basic-syntax)
4. [Functions](#functions)
5. [Data Types](#data-types)
6. [Control Structures](#control-structures)
7. [Structs](#structs)
8. [Modules and Imports](#modules-and-imports)
9. [Error Handling](#error-handling)
10. [Standard Library Overview](#standard-library-overview)
11. [File I/O](#file-io)
12. [Networking](#networking)
13. [JSON Handling](#json-handling)
14. [Arrays and Maps](#arrays-and-maps)
15. [Concurrency](#concurrency)
16. [Testing](#testing)
17. [Conclusion](#conclusion)

## Introduction to V Language

V is a simple, fast, safe, compiled general-purpose programming language for developing maintainable software. It's similar to Go in syntax but includes modern features like option types, immutability by default, and built-in error handling.

Key features of V:
- Compiles quickly to native binaries
- Memory-safe with optional garbage collection
- Simple syntax with only a few keywords
- Cross-platform support
- Direct compilation to C, JavaScript, or machine code

## Installing V

### Installing from Source
```bash
git clone https://github.com/vlang/v
cd v
make
```

### Weekly Releases
You can also download pre-built binaries from [V GitHub releases](https://github.com/vlang/v/releases).

### Adding to PATH
After installation, you can add V to your PATH:
```bash
# On Linux/Mac
sudo ./v symlink

# On Windows (run as Administrator)
.\v.exe symlink
```

### Updating V
To update V to the latest version:
```bash
v up
```

## Basic Syntax

Let's start with the classic "Hello, World!" program:

```v
fn main() {
    println('Hello, World!')
}
```

### Variables
In V, variables are immutable by default:

```v
fn main() {
    name := 'Bob' // Immutable variable
    mut age := 25 // Mutable variable
    age = 26      // This is allowed because age is mutable
    // name = 'Alice' // This would cause a compilation error
}
```

### Constants
Constants are defined with `const`:

```v
const pi = 3.14159
const (
    app_name = 'MyApp'
    version = '1.0'
)
```

## Functions

Functions in V are defined with the `fn` keyword:

```v
fn greet(name string) string {
    return 'Hello, $name!'
}

fn main() {
    message := greet('Alice')
    println(message)
}
```

### Multiple Return Values
V supports multiple return values:

```v
fn divide(a f64, b f64) (f64, bool) {
    if b == 0 {
        return 0, false
    }
    return a / b, true
}

fn main() {
    result, success := divide(10, 2)
    if success {
        println('Result: $result')
    } else {
        println('Division by zero!')
    }
}
```

## Data Types

V has several built-in data types:

```v
fn main() {
    // Integer types
    age := 25         // int
    byte_val := byte(10)
    i64_val := i64(1000000)
    
    // Float types
    price := 99.99    // f64
    f32_val := f32(3.14)
    
    // String
    name := 'Alice'
    
    // Boolean
    is_active := true
    
    // Rune (Unicode code point)
    letter := `A`
}
```

## Control Structures

### If Statements
```v
fn main() {
    age := 18
    
    if age >= 18 {
        println('You are an adult')
    } else if age >= 13 {
        println('You are a teenager')
    } else {
        println('You are a child')
    }
}
```

### For Loops
V has only one loop keyword: `for`:

```v
fn main() {
    // Traditional for loop
    for i := 0; i < 5; i++ {
        println('$i')
    }
    
    // While loop
    mut i := 0
    for i < 5 {
        println('$i')
        i++
    }
    
    // Infinite loop
    // for {
    //     // This loop runs forever
    // }
    
    // Array iteration
    numbers := [1, 2, 3, 4, 5]
    for num in numbers {
        println('$num')
    }
    
    // Index and value
    for idx, num in numbers {
        println('$idx: $num')
    }
}
```

## Structs

Structs in V are similar to those in other languages:

```v
struct Person {
    name string
    age  int
mut:
    is_adult bool
}

fn main() {
    // Creating a struct instance
    mut person := Person{
        name: 'Alice'
        age: 30
    }
    
    // Modifying mutable fields
    person.is_adult = true
    
    println('Name: $person.name')
    println('Age: $person.age')
    println('Is adult: $person.is_adult')
}
```

### Methods
Methods are functions with a receiver:

```v
struct Rectangle {
    width  f64
    height f64
}

fn (r Rectangle) area() f64 {
    return r.width * r.height
}

fn (mut r Rectangle) scale(factor f64) {
    r.width *= factor
    r.height *= factor
}

fn main() {
    mut rect := Rectangle{width: 10, height: 5}
    println('Area: $rect.area()')
    
    rect.scale(2)
    println('Scaled area: $rect.area()')
}
```

## Modules and Imports

V uses modules for organizing code. To import a module:

```v
import os
import time

fn main() {
    // Using functions from imported modules
    current_time := time.now()
    println(current_time)
    
    // Get current working directory
    cwd := os.getwd() or { panic(err) }
    println('Current directory: $cwd')
}
```

### Creating Your Own Modules
Create a file `mymodule.v`:

```v
module mymodule

pub fn hello(name string) string {
    return 'Hello, $name!'
}
```

Use it in your main file:

```v
import mymodule

fn main() {
    message := mymodule.hello('World')
    println(message)
}
```

## Error Handling

V uses an explicit approach to error handling with Option/Result types:

```v
import os

fn main() {
    // Reading a file that might not exist
    text := os.read_file('nonexistent.txt') or {
        println('Failed to read file: $err')
        return
    }
    
    println(text)
}
```

### Custom Error Handling
```v
fn divide(a f64, b f64) ?f64 {
    if b == 0 {
        return error('Division by zero')
    }
    return a / b
}

fn main() {
    result := divide(10, 0) or {
        println('Error: $err')
        return
    }
    
    println('Result: $result')
}
```

## Standard Library Overview

V's standard library (vlib) provides modules for common tasks. Some key modules include:

- `os`: Operating system functions
- `time`: Time and date operations
- `net.http`: HTTP client and server
- `json`: JSON encoding/decoding
- `arrays`: Array utilities
- `strings`: String manipulation
- `math`: Mathematical functions

You can explore the full standard library documentation at [modules.vlang.io](https://modules.vlang.io/).

## File I/O

### Reading Files
```v
import os

fn main() {
    // Read entire file
    content := os.read_file('example.txt') or {
        println('Failed to read file: $err')
        return
    }
    
    println(content)
}
```

### Writing Files
```v
import os

fn main() {
    data := 'Hello, File!'
    os.write_file('output.txt', data) or {
        println('Failed to write file: $err')
        return
    }
    
    println('File written successfully')
}
```

### Working with Paths
```v
import os

fn main() {
    path := '/home/user/documents/file.txt'
    
    dir := os.dir(path)
    file := os.file(path)
    ext := os.ext(path)
    
    println('Directory: $dir')
    println('File: $file')
    println('Extension: $ext')
}
```

## Networking

### HTTP Client
```v
import net.http

fn main() {
    resp := http.get('https://httpbin.org/get') or {
        println('Failed to make request: $err')
        return
    }
    
    println('Status: $resp.status_code')
    println('Body: $resp.body')
}
```

### HTTP Server
```v
import net.http

fn main() {
    mut server := http.Server{
        handler: http.HandlerFunc(route_handler)
    }
    
    println('Server running on http://localhost:8080')
    server.listen(':8080') or {
        println('Failed to start server: $err')
    }
}

fn route_handler(mut ctx http.Context) {
    ctx.response.body = 'Hello, World!'
}
```

## JSON Handling

### Parsing JSON
```v
import json

struct User {
    name string
    age  int
}

fn main() {
    data := '{"name": "Alice", "age": 30}'
    
    user := json.decode(User, data) or {
        println('Failed to parse JSON: $err')
        return
    }
    
    println('Name: $user.name')
    println('Age: $user.age')
}
```

### Generating JSON
```v
import json

struct User {
    name string
    age  int
}

fn main() {
    user := User{
        name: 'Bob'
        age: 25
    }
    
    json_data := json.encode(user)
    println(json_data) // {"name":"Bob","age":25}
}
```

## Arrays and Maps

### Arrays
```v
fn main() {
    // Creating arrays
    mut numbers := [1, 2, 3, 4, 5]
    empty_array := []int{}
    
    // Adding elements
    numbers << 6 // Append
    
    // Accessing elements
    first := numbers[0]
    last := numbers[numbers.len - 1]
    
    // Iterating
    for num in numbers {
        println(num)
    }
}
```

### Maps
```v
fn main() {
    // Creating maps
    mut ages := {
        'Alice': 30
        'Bob': 25
    }
    
    // Adding elements
    ages['Charlie'] = 35
    
    // Accessing elements
    alice_age := ages['Alice'] or {
        println('Alice not found')
        0
    }
    
    // Checking if key exists
    if ages['David'] ? {
        println('David exists')
    } else {
        println('David does not exist')
    }
    
    // Iterating
    for name, age in ages {
        println('$name is $age years old')
    }
}
```

## Concurrency

V uses the actor model for concurrency with the `chan` type:

```v
import time

fn worker(id int, jobs chan int, results chan int) {
    for job in jobs {
        println('Worker $id processing job $job')
        time.sleep(1 * time.second)
        results <- job * 2
    }
}

fn main() {
    mut jobs := chan int{cap: 5}
    mut results := chan int{cap: 5}
    
    // Start workers
    for i in 0..3 {
        go worker(i, jobs, results)
    }
    
    // Send jobs
    for j in 1..6 {
        jobs <- j
    }
    
    // Close jobs channel
    close(jobs)
    
    // Collect results
    mut result_sum := 0
    for _ in 0..5 {
        result := <-results
        result_sum += result
    }
    
    println('Sum of results: $result_sum')
}
```

## Testing

V has built-in testing support. Test functions start with `test_`:

```v
// main.v
fn add(a int, b int) int {
    return a + b
}

fn main() {
    println(add(2, 3))
}

// main_test.v
fn test_add() {
    assert add(2, 3) == 5
    assert add(-1, 1) == 0
}
```

Run tests with:
```bash
v test .
```

## Conclusion

V is a modern programming language that combines the simplicity of Go with safety features and performance. Its clean syntax, explicit error handling, and powerful standard library make it an excellent choice for systems programming, web development, and more.

Key takeaways:
1. V emphasizes safety with immutability by default and explicit error handling
2. The standard library provides comprehensive modules for common tasks
3. V compiles quickly to native binaries with minimal dependencies
4. The language is designed for readability and maintainability

To continue learning V:
1. Read the official documentation at [docs.vlang.io](https://docs.vlang.io/)
2. Explore the standard library at [modules.vlang.io](https://modules.vlang.io/)
3. Join the V community on Discord for support and discussions
4. Try building small projects to practice the concepts learned

As V continues to evolve, it's becoming increasingly powerful while maintaining its focus on simplicity and safety. Whether you're building system tools, web services, or desktop applications, V provides an excellent foundation for modern software development.