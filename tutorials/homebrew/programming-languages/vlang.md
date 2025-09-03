# V Documentation

(See https://modules.vlang.io/ for documentation of V's standard library)
(See also https://docs.vlang.io/introduction.html, which has the same information as this document,
but split in separate pages for each section, for easier reading on mobile devices)

## Introduction

V is a statically typed compiled programming language designed for building maintainable software.

It's similar to Go and its design has also been influenced by Oberon, Rust, Swift,
Kotlin, and Python.

V is a very simple language. Going through this documentation will take you about a weekend,
and by the end of it you will have pretty much learned the entire language.

The language promotes writing simple and clear code with minimal abstraction.

Despite being simple, V gives the developer a lot of power.
Anything you can do in other languages, you can do in V.

## Installing V from source

The best way to get the latest and greatest V, is to install it from source.
It is easy, and it takes only a few seconds:
```bash
git clone --depth=1 https://github.com/vlang/v
cd v
make
```

Note: If you are on windows, outside of WSL, run `make.bat` instead of `make`, in a CMD shell.
Note: On Ubuntu/Debian, you may need to run `sudo apt install git build-essential make` first.

For more details, see the
[Installing V](https://github.com/vlang/v/blob/master/README.md#installing-v-from-source)
section in the README.md.

## Installing V on macOS (Homebrew)

If you use macOS and prefer Homebrew, you can install V with a single command.

Prerequisites: [Homebrew](https://brew.sh) installed

Stable release:

```bash
brew update
brew install vlang
```

Latest development build (from HEAD):

```bash
brew update
brew install --HEAD vlang
```

Upgrade to the latest available version from Homebrew:

```bash
brew update
brew upgrade vlang
```

Verify your installation:

```bash
v version
```

Uninstall:

```bash
brew uninstall vlang
```

Notes:

- Homebrew packages may lag behind the latest source; use `--HEAD` or build from source for cutting-edge features.
- When installed via Homebrew, the `v` binary is already on your PATH; `v symlink` is not required.


## Upgrading V to latest version

If V is already installed on a machine, it can be upgraded to its latest version
by using the V's built-in self-updater.
To do so, run the command `v up`.

## Packaging V for distribution

See the [notes on how to prepare a package for V](packaging_v_for_distributions.md) .

## Getting started

You can let V automatically set up the bare-bones structure of a project for you
by using any of the following commands in a terminal:

- `v init` → adds necessary files to the current folder to make it a V project
- `v new abc` → creates a new project in the new folder `abc`, by default a "hello world" project.
- `v new --web abcd` → creates a new project in the new folder `abcd`, using the vweb template.

## Tutorial learning path (recommended order)

Use this path to learn V step by step. Each item links to the relevant section below.

- Start here
    - [Introduction](#introduction)
    - [Installing V from source](#installing-v-from-source)
    - [Installing V on macOS (Homebrew)](#installing-v-on-macos-homebrew)
    - [Upgrading V to latest version](#upgrading-v-to-latest-version)
    - [Getting started](#getting-started)
    - [Hello world](#hello-world)
    - [Running a project folder](#running-a-project-folder-with-several-files)
    - [Comments](#comments)
- Core language basics
    - [Variables](#variables)
    - [V Data Types](#v-data-types)
        - [Primitive Types](#primitive-types)
        - [Strings](#strings)
        - [Runes](#runes)
        - [Numbers](#numbers)
        - [Arrays](#arrays)
        - [Fixed size arrays](#fixed-size-arrays)
        - [Maps](#maps) → [Map update syntax](#map-update-syntax)
    - [Statements & expressions](#statements--expressions)
        - [If](#if)
        - [Match](#match)
        - [In operator](#in-operator)
        - [For loop](#for-loop)
        - [Defer](#defer)
        - [Goto](#goto)
    - [Functions](#functions)
        - [Hoisting](#hoisting)
        - [Returning Multiple Values](#returning-multiple-values)
- Data modeling
    - [Structs](#structs)
    - [Type Declarations](#type-declarations)
        - [Type aliases](#type-aliases)
        - [Enums](#enums)
        - [Function Types](#function-types)
        - [Interfaces](#interfaces)
        - [Sum types](#sum-types)
        - [Option/Result types & error handling](#optionresult-types-and-error-handling)
        - [Custom error types](#custom-error-types)
        - [Generics](#generics)
- Organization and reuse
    - [Module imports](#module-imports)
    - [Modules](#modules)
    - [Constants](#constants)
    - [References](#references)
    - [Builtin functions](#builtin-functions)
- Advanced functions
    - [Functions 2](#functions-2)
- Hands-on practice and I/O
    - [Quick Start: Practical Examples](#quick-start-practical-examples)
    - [Console Input and Output](#console-input-and-output)
    - [File Input and Output](#file-input-and-output)
    - [SQLite: Secure CRUD with parameterized queries](#sqlite-secure-crud-with-parameterized-queries)
    - [Process Management and Subprocesses](#process-management-and-subprocesses)
    - [Networking and HTTP](#networking-and-http)
    - [JSON Serialization with Struct Arrays](#json-serialization-with-struct-arrays)
- Concurrency and patterns
    - [Concurrency](#concurrency)
    - [Advanced Programming Patterns](#advanced-programming-patterns)
        - [Memory Management and Performance](#memory-management-and-performance)
- Deep dive and integrations
    - [Conditional compilation](#conditional-compilation)
    - [Attributes](#attributes)
    - [Cross compilation](#cross-compilation)
    - [Debugging](#debugging)
    - [V and C](#v-and-c)
    - [Other V Features](#other-v-features)
    - Appendices: [Keywords](#appendix-i-keywords), [Operators](#appendix-ii-operators)

## Table of Contents

<table>
<tr><td width=33% valign=top>

* [Hello world](#hello-world)
* [Running a project folder](#running-a-project-folder-with-several-files)
* [Comments](#comments)
* [Functions](#functions)
    * [Hoisting](#hoisting)
    * [Returning multiple values](#returning-multiple-values)
* [Symbol visibility](#symbol-visibility)
* [Variables](#variables)
    * [Mutable variables](#mutable-variables)
    * [Initialization vs assignment](#initialization-vs-assignment)
    * [Warnings and declaration errors](#warnings-and-declaration-errors)
* [V types](#v-types)
    * [Primitive types](#primitive-types)
    * [Strings](#strings)
    * [Runes](#runes)
    * [Numbers](#numbers)
    * [Arrays](#arrays)
        * [Multidimensional arrays](#multidimensional-arrays)
        * [Array methods](#array-methods)
        * [Array slices](#array-slices)
    * [Fixed size arrays](#fixed-size-arrays)
    * [Maps](#maps)
        * [Map update syntax](#map-update-syntax)

</td><td width=33% valign=top>

* [Module imports](#module-imports)
    * [Selective imports](#selective-imports)
    * [Module hierarchy](#module-hierarchy)
    * [Module import aliasing](#module-import-aliasing)
* [Statements & expressions](#statements--expressions)
    * [If](#if)
        * [`If` expressions](#if-expressions)
        * [`If` unwrapping](#if-unwrapping)
    * [Match](#match)
    * [In operator](#in-operator)
    * [For loop](#for-loop)
    * [Defer](#defer)
    * [Goto](#goto)
* [Structs](#structs)
    * [Heap structs](#heap-structs)
    * [Default field values](#default-field-values)
    * [Required fields](#required-fields)
    * [Short struct literal syntax](#short-struct-literal-syntax)
    * [Struct update syntax](#struct-update-syntax)
    * [Trailing struct literal arguments](#trailing-struct-literal-arguments)
    * [Access modifiers](#access-modifiers)
    * [Anonymous structs](#anonymous-structs)
    * [Static type methods](#static-type-methods)
    * [[noinit] structs](#noinit-structs)
    * [Methods](#methods)
    * [Embedded structs](#embedded-structs)
* [Unions](#unions)

</td><td valign=top>

* [Functions 2](#functions-2)
    * [Immutable function args by default](#immutable-function-args-by-default)
    * [Mutable arguments](#mutable-arguments)
    * [Variable number of arguments](#variable-number-of-arguments)
    * [Anonymous & higher-order functions](#anonymous--higher-order-functions)
    * [Closures](#closures)
    * [Parameter evaluation order](#parameter-evaluation-order)
* [References](#references)
* [Constants](#constants)
    * [Required module prefix](#required-module-prefix)
* [Builtin functions](#builtin-functions)
    * [println](#println)
    * [Printing custom types](#printing-custom-types)
    * [Dumping expressions at runtime](#dumping-expressions-at-runtime)
* [Modules](#modules)
    * [Create modules](#create-modules)
    * [Special considerations for project folders](#special-considerations-for-project-folders)
    * [init functions](#init-functions)
    * [cleanup functions](#cleanup-functions)

</td></tr>
<tr><td width=33% valign=top>

* [Type Declarations](#type-declarations)
    * [Type aliases](#type-aliases)
    * [Enums](#enums)
    * [Function Types](#function-types)
    * [Interfaces](#interfaces)
    * [Sum types](#sum-types)
    * [Option/Result types & error handling](#optionresult-types-and-error-handling)
        * [Handling options/results](#handling-optionsresults)
    * [Custom error types](#custom-error-types)
    * [Generics](#generics)
* [Concurrency](#concurrency)
    * [Spawning Concurrent Tasks](#spawning-concurrent-tasks)
    * [Channels](#channels)
    * [Shared Objects](#shared-objects)
* [JSON](#json)
    * [Decoding JSON](#decoding-json)
    * [Encoding JSON](#encoding-json)
* [Testing](#testing)
    * [Asserts](#asserts)
    * [Asserts with an extra message](#asserts-with-an-extra-message)
    * [Asserts that do not abort your program](#asserts-that-do-not-abort-your-program)
    * [Test files](#test-files)
    * [Running tests](#running-tests)
* [Memory management](#memory-management)
    * [Control](#control)
    * [Stack and Heap](#stack-and-heap)
* [ORM](#orm)
* [Writing documentation](#writing-documentation)
    * [Newlines in Documentation Comments](#newlines-in-documentation-comments)

</td><td width=33% valign=top>

* [Tools](#tools)
    * [v fmt](#v-fmt)
    * [v shader](#v-shader)
    * [Profiling](#profiling)
* [Package Management](#package-management)
    * [Package commands](#package-commands)
    * [Publish package](#publish-package)
* [Advanced Topics](#advanced-topics)
    * [Attributes](#attributes)
    * [Conditional compilation](#conditional-compilation)
        * [Compile time pseudo variables](#compile-time-pseudo-variables)
        * [Compile time reflection](#compile-time-reflection)
        * [Compile time code](#compile-time-code)
        * [Compile time types](#compile-time-types)
        * [Environment specific files](#environment-specific-files)
	* [Debugger](#debugger)
 		* [Call stack](#call-stack)
   		* [Trace](#trace)
    * [Memory-unsafe code](#memory-unsafe-code)
    * [Structs with reference fields](#structs-with-reference-fields)
    * [sizeof and __offsetof](#sizeof-and-__offsetof)
    * [Limited operator overloading](#limited-operator-overloading)
    * [Performance tuning](#performance-tuning)
    * [Atomics](#atomics)
    * [Global Variables](#global-variables)
    * [Static Variables](#static-variables)
    * [Cross compilation](#cross-compilation)
    * [Debugging](#debugging)
        * [C Backend binaries Default](#c-backend-binaries-default)
        * [Native Backend binaries](#native-backend-binaries)
        * [Javascript Backend](#javascript-backend)

</td><td valign=top>

* [V and C](#v-and-c)
    * [Calling C from V](#calling-c-from-v)
    * [Calling V from C](#calling-v-from-c)
    * [Passing C compilation flags](#passing-c-compilation-flags)
    * [#pkgconfig](#pkgconfig)
    * [Including C code](#including-c-code)
    * [C types](#c-types)
    * [C Declarations](#c-declarations)
    * [Export to shared library](#export-to-shared-library)
    * [Translating C to V](#translating-c-to-v)
    * [Working around C issues](#working-around-c-issues)
* [Other V Features](#other-v-features)
    * [Inline assembly](#inline-assembly)
    * [Hot code reloading](#hot-code-reloading)
    * [Cross-platform shell scripts in V](#cross-platform-shell-scripts-in-v)
    * [Vsh scripts with no extension](#vsh-scripts-with-no-extension)
* [Appendices](#appendices)
    * [Keywords](#appendix-i-keywords)
    * [Operators](#appendix-ii-operators)
    * [Other online resources](#other-online-resources)

</td></tr>
</table>

<!--
Note: There are several special keywords, which you can put after the code fences for v:
compile, cgen, live, ignore, failcompile, okfmt, oksyntax, badsyntax, wip, nofmt
For more details, do: `v check-md`
-->

## Hello World

```v
fn main() {
	println('hello world')
}
```

Save this snippet into a file named `hello.v`. Now do: `v run hello.v`.

> That is assuming you have symlinked your V with `v symlink`, as described
[here](https://github.com/vlang/v/blob/master/README.md#symlinking).
> If you haven't yet, you have to type the path to V manually.

Congratulations - you just wrote and executed your first V program!

You can compile a program without execution with `v hello.v`.
See `v help` for all supported commands.

From the example above, you can see that functions are declared with the `fn` keyword.
The return type is specified after the function name.
In this case `main` doesn't return anything, so there is no return type.

As in many other languages (such as C, Go, and Rust), `main` is the entry point of your program.

[`println`](#println) is one of the few [built-in functions](#builtin-functions).
It prints the value passed to it to standard output.

`fn main()` declaration can be skipped in single file programs.
This is useful when writing small programs, "scripts", or just learning the language.
For brevity, `fn main()` will be skipped in this tutorial.

This means that a "hello world" program in V is as simple as

```v
println('hello world')
```

> [!NOTE]
> If you do not explicitly use `fn main() {}`, you need to make sure that all your
> declarations come before any variable assignment statements or top level function calls,
> since V will consider everything after the first assignment/function call as part of your
> implicit main function.

## Running a project folder with several files

Suppose you have a folder with several .v files in it, where one of them
contains your `main()` function, and the other files have other helper
functions. They may be organized by topic, but still *not yet* structured
enough to be their own separate reusable modules, and you want to compile
them all into one program.

In other languages, you would have to use includes or a build system
to enumerate all files, compile them separately to object files,
then link them into one final executable.

In V however, you can compile and run the whole folder of .v files together,
using just `v run .`. Passing parameters also works, so you can
do: `v run . --yourparam some_other_stuff`

The above will first compile your files into a single program (named
after your folder/project), and then it will execute the program with
`--yourparam some_other_stuff` passed to it as CLI parameters.

Your program can then use the CLI parameters like this:

```v
import os

println(os.args)
```

> [!NOTE]
> After a successful run, V will delete the generated executable.
> If you want to keep it, use `v -keepc run .` instead, or just compile
> manually with `v .` .

> [!NOTE]
> Any V compiler flags should be passed *before* the `run` command.
> Everything after the source file/folder, will be passed to the program
> as is - it will not be processed by V.

## Comments

```v
// This is a single line comment.
/*
This is a multiline comment.
   /* It can be nested. */
*/
```

## Functions

Functions are reusable blocks of code that perform specific tasks. They are the building blocks of V programs and help organize code into manageable, logical units.

### Basic Function Syntax

```v
// Our main function - this runs when the program starts
fn main() {
    // Call our custom functions and show the results
    sum := add(77, 33)           // Call add function with two numbers
    difference := subtract(100, 50)  // Call subtract function
    product := multiply(5, 6)         // Call multiply function
    
    // Display the results
    println('77 + 33 = ${sum}')           // 110
    println('100 - 50 = ${difference}')   // 50  
    println('5 × 6 = ${product}')         // 30
}

// Function to add two numbers together
fn add(x int, y int) int {
    return x + y  // Give back the sum
}

// Function to subtract second number from first  
fn subtract(x int, y int) int {
    return x - y  // Give back the difference
}

// Function to multiply two numbers
fn multiply(a int, b int) int {
    return a * b  // Give back the product
}
```

<!-- SQLite section moved to Hands-on practice area below -->


**Function Components Explained:**

- `fn` — This keyword tells V "here comes a function"
- Function name — What we call it (like `add`, `subtract`) — use `snake_case`
- Parameters — The inputs in parentheses `(x int, y int)`
- Return type — What type of value it gives back (like `int`)
- Function body — The code that runs between `{` and `}`
- `return` — Sends a value back to whoever called the function

### Function Parameter Types

The type comes after each parameter's name. V requires explicit types for all parameters:

```v
// Different parameter types
fn greet(name string, age int) string {
    return 'Hello ${name}, you are ${age} years old!'
}

fn calculate_area(width f64, height f64) f64 {
    return width * height
}

fn is_adult(age int) bool {
    return age >= 18
}

fn process_items(items []string, prefix string) []string {
    mut result := []string{}
    for item in items {
        result << prefix + item
    }
    return result
}

// Usage examples
message := greet('Alice', 25)
area := calculate_area(10.5, 8.2)
adult := is_adult(20)
processed := process_items(['apple', 'banana'], 'fruit: ')
```

### Functions Without Return Values

Functions that don't return anything have no return type specified:

```v
fn print_header(title string) {
    println('=' * 50)
    println('  ${title.to_upper()}')
    println('=' * 50)
}

fn log_message(level string, message string) {
    timestamp := '2024-01-15 10:30:00'  // simplified
    println('[${timestamp}] ${level}: ${message}')
}

fn display_menu() {
    println('Main Menu:')
    println('1. New Game')
    println('2. Load Game')
    println('3. Settings')
    println('4. Exit')
}

// Usage
print_header('Welcome to V Programming')
log_message('INFO', 'Application started')
display_menu()
```

### Hoisting

Functions can be used before their declaration. This is called "hoisting":

```v
fn main() {
    // These functions are called before they're defined below
    result := calculate_total(100, 0.08, 15.00)
    println('Total: $${result:.2f}')
    
    display_result('Processing complete')
}

fn calculate_total(subtotal f64, tax_rate f64, shipping f64) f64 {
    return subtotal + (subtotal * tax_rate) + shipping
}

fn display_result(message string) {
    println('✅ ${message}')
}
```

This is true for all declarations in V and eliminates the need for header files or worrying about the order of function declarations.

### Returning Multiple Values

V functions can return multiple values as a tuple:

```v
fn get_name_age() (string, int) {
    return 'Alice', 30
}

fn divide_and_remainder(a int, b int) (int, int) {
    quotient := a / b
    remainder := a % b
    return quotient, remainder
}

fn get_min_max(numbers []int) (int, int) {
    if numbers.len == 0 {
        return 0, 0
    }
    
    mut min := numbers[0]
    mut max := numbers[0]
    
    for num in numbers {
        if num < min {
            min = num
        }
        if num > max {
            max = num
        }
    }
    
    return min, max
}

// Using multiple return values
name, age := get_name_age()
println('Name: ${name}, Age: ${age}')

quotient, remainder := divide_and_remainder(17, 5)
println('17 ÷ 5 = ${quotient} remainder ${remainder}')

// Ignore values using `_`
_, max_value := get_min_max([3, 7, 2, 9, 1])
println('Maximum: ${max_value}')
```

### Real-world Function Examples

**User Authentication System:**
```v
struct User {
    username string
    password string
    is_admin bool
}

fn authenticate_user(username string, password string, users []User) ?User {
    for user in users {
        if user.username == username && user.password == password {
            return user
        }
    }
    return error('Invalid username or password')
}

fn has_permission(user User, required_admin bool) bool {
    if required_admin {
        return user.is_admin
    }
    return true
}

fn create_session_token(user User) string {
    // Simplified token generation
    return 'session_${user.username}_${user.is_admin}'
}

// Usage
users := [
    User{'admin', 'secret123', true},
    User{'user1', 'password1', false},
    User{'user2', 'password2', false},
]

user := authenticate_user('admin', 'secret123', users) or {
    println('Login failed: ${err}')
    return
}

if has_permission(user, true) {
    token := create_session_token(user)
    println('Admin login successful. Token: ${token}')
} else {
    println('Access denied: Admin required')
}
```

**Mathematical Functions Library:**
```v
import math

fn factorial(n int) int {
    if n <= 1 {
        return 1
    }
    return n * factorial(n - 1)
}

fn is_prime(n int) bool {
    if n < 2 {
        return false
    }
    if n == 2 {
        return true
    }
    if n % 2 == 0 {
        return false
    }
    
    for i := 3; i * i <= n; i += 2 {
        if n % i == 0 {
            return false
        }
    }
    return true
}

fn fibonacci(n int) int {
    if n <= 1 {
        return n
    }
    return fibonacci(n - 1) + fibonacci(n - 2)
}

fn gcd(a int, b int) int {
    if b == 0 {
        return a
    }
    return gcd(b, a % b)
}

fn lcm(a int, b int) int {
    return (a * b) / gcd(a, b)
}

// Mathematical calculations
println('5! = ${factorial(5)}')                    // 120
println('Is 17 prime? ${is_prime(17)}')           // true
println('10th Fibonacci: ${fibonacci(10)}')       // 55
println('GCD of 48 and 18: ${gcd(48, 18)}')      // 6
println('LCM of 12 and 15: ${lcm(12, 15)}')      // 60
```

**String Processing Functions:**
```v
// Function to capitalize the first letter of each word (Unicode-safe)
fn capitalize_words(text string) string {
    words := text.split(' ')
    mut out := []string{cap: words.len}
    for word in words {
        if word.len == 0 {
            out << ''
            continue
        }
        rw := word.runes()
        if rw.len == 0 {
            out << ''
            continue
        }
        first := rw[0].str().to_upper()
        rest := if rw.len > 1 { rw[1..].string().to_lower() } else { '' }
        out << first + rest
    }
    return out.join(' ')
}

// Function to count vowels in a text (ASCII vowels)
fn count_vowels(text string) int {
    vowels := 'aeiouAEIOU'
    mut count := 0
    for ch in text.runes() {
        if vowels.contains(ch.str()) {
            count++
        }
    }
    return count
}

// Function to reverse a string (Unicode-safe)
fn reverse_string(text string) string {
    mut r := text.runes()
    mut i := 0
    mut j := r.len - 1
    for i < j {
        r[i], r[j] = r[j], r[i]
        i++
        j--
    }
    return r.string()
}

// Function to check if text reads the same forwards and backwards
fn is_palindrome(text string) bool {
    cleaned := text.to_lower().replace(' ', '')
    return cleaned == reverse_string(cleaned)
}

// Demo: String processing examples
fn demo_strings() {
    original := 'hello world from V'
    println('=== String Processing Demo ===')
    println('Original text: "${original}"')
    println('Capitalized: "${capitalize_words(original)}"')
    println('Vowel count: ${count_vowels(original)}')
    println('Reversed: "${reverse_string(original)}"')

    // Test palindrome with a famous example
    test_phrase := 'A man a plan a canal Panama'
    println('Is "${test_phrase}" a palindrome? ${is_palindrome(test_phrase)}')
}
```

**File Processing Functions:**
```v
import os

// Function to read and parse a configuration file
// Returns a map of key-value pairs, or an error if something goes wrong
fn read_config_file(filename string) ?map[string]string {
    // Step 1: Try to read the file content
    content := os.read_file(filename) or {
        return error('Could not read file: ${filename}')
    }
    
    // Step 2: Create empty map to store configuration
    mut config := map[string]string{}
    
    // Step 3: Split content into lines
    lines := content.split('\n')
    
    // Step 4: Process each line
    for line in lines {
        // Remove extra spaces
        trimmed := line.trim(' ')
        
        // Skip empty lines and comments (lines starting with #)
        if trimmed.len == 0 || trimmed.starts_with('#') {
            continue
        }
        
        // Look for key=value pattern
        if trimmed.contains('=') {
            parts := trimmed.split('=')
            if parts.len == 2 {
                key := parts[0].trim(' ')     // Remove spaces around key
                value := parts[1].trim(' ')   // Remove spaces around value
                config[key] = value
            }
        }
    }
    
    return config
}

// Function to write a log entry to a file
fn write_log_entry(filename string, level string, message string) {
    // Create timestamp (in real app, use time.now())
    timestamp := '2024-01-15 10:30:00'
    
    // Format the log entry
    log_entry := '[${timestamp}] ${level}: ${message}\n'
    
    // Try to write to file
    os.write_file(filename, log_entry) or {
        eprintln('Failed to write to log: ${err}')
        return
    }
    
    println('Log entry written successfully')
}

// Function to create a backup of a file
fn backup_file(original_path string) ?string {
    // Step 1: Check if original file exists
    if !os.exists(original_path) {
        return error('File does not exist: ${original_path}')
    }
    
    // Step 2: Create backup filename
    backup_path := '${original_path}.backup'
    
    // Step 3: Read original file content
    content := os.read_file(original_path) or {
        return error('Could not read original file: ${err}')
    }
    
    // Step 4: Write content to backup file
    os.write_file(backup_path, content) or {
        return error('Could not create backup file: ${err}')
    }
    
    return backup_path
}

// Demo: File processing examples
fn demo_file_processing() {
    println('=== File Processing Demo ===')
    
    // Example 1: Write a log entry
    write_log_entry('app.log', 'INFO', 'Application started successfully')
    
    // Example 2: Try to read a config file
    config := read_config_file('config.txt') or {
        println('Config file not found: ${err}')
        map[string]string{}
    }
    
    if config.len > 0 {
        println('Configuration loaded:')
        for key, value in config {
            println('  ${key} = ${value}')
        }
    }
    
    // Example 3: Backup a file
    backup_result := backup_file('important.txt') or {
        println('Backup failed: ${err}')
        return
    }
    println('Backup created: ${backup_result}')
}
```


### Function Design Best Practices

**1. Single Responsibility:**
```v
// ❌ Bad: Function does too many things
fn process_user_data_badly(data string) {
    // validation, parsing, saving, logging, emailing...
}

// ✅ Good: Each function has one clear purpose
fn validate_user_data(data string) bool { /* ... */ }
fn parse_user_data(data string) User { /* ... */ }
fn save_user(user User) { /* ... */ }
fn log_user_action(action string) { /* ... */ }
```

**2. Clear Naming:**
```v
// ❌ Bad: Unclear function names
fn process(data string) { /* ... */ }
fn calc(x int, y int) int { /* ... */ }

// ✅ Good: Clear, descriptive names
fn validate_email(email string) bool { /* ... */ }
fn calculate_monthly_payment(principal f64, rate f64, months int) f64 { /* ... */ }
```

**3. Parameter Validation:**
```v
fn calculate_circle_area(radius f64) ?f64 {
    if radius < 0 {
        return error('Radius cannot be negative')
    }
    return 3.14159 * radius * radius
}

fn get_array_element(arr []int, index int) ?int {
    if index < 0 || index >= arr.len {
        return error('Index out of bounds')
    }
    return arr[index]
}
```

Just like in Go and C, functions cannot be overloaded. This simplifies the code and improves maintainability and readability.

## Symbol visibility

```v
pub fn public_function() {
}

fn private_function() {
}
```

Functions are private (not exported) by default.
To allow other [modules](#module-imports) to use them, prepend `pub`. The same applies
to [structs](#structs), [constants](#constants) and [types](#type-declarations).

> [!NOTE]
> `pub` can only be used from a named module.
> For information about creating a module, see [Modules](#modules).

## Variables

### A Variable

A [variable](https://en.wikipedia.org/wiki/Variable_(computer_science)) is a "named storage" for data. We can use variables to store user information, calculation results, and other data.

To create a variable in V, use the `:=` operator:

```v
name := 'Bob'
println(name) // Bob
```

Now we can put some data into it and access it using the variable name:

```v
name := 'Bob'
age := 20
large_number := i64(9999999999)
println(name)        // Bob
println(age)         // 20
println(large_number) // 9999999999
```

### Real-life analogy

We can easily grasp the concept of a "variable" if we imagine it as a "box" for data, with a uniquely-named sticker on it.

For instance, the variable `name` can be imagined as a box labelled `"name"` with the value `"Bob"` in it.

We can put any value in the box, and we can also change it:

```v
mut message := 'Hello'
println(message)  // Hello

message = 'World'
println(message)  // World
```

### Variable Declaration Rules

Variables are declared and initialized with `:=`. This is the only way to declare variables in V. This means that variables always have an initial value.

```v
// Step-by-step variable declarations
username := 'alice'          // Create a string variable
user_age := 25              // Create an integer variable  
is_active := true           // Create a boolean variable
balance := 1250.75          // Create a floating-point variable
items := [1, 2, 3, 4, 5]   // Create an array of integers

// Let's see what we created
println('Username: ${username}')       // Username: alice
println('Age: ${user_age}')           // Age: 25
println('Active: ${is_active}')       // Active: true
println('Balance: $${balance}')       // Balance: $1250.75
println('Items: ${items}')            // Items: [1, 2, 3, 4, 5]
```

The variable's type is inferred from the value on the right hand side. To choose a different type, use type conversion: the expression `T(v)` converts the value `v` to the type `T`.

```v
// Type conversion examples (changing one type to another)

// Convert number to string
age := 20
age_as_string := age.str()           // Converts 20 to "20"
println('Age as string: "${age_as_string}"')

// Convert string to number
price := "99.99"
price_as_float := price.f64()        // Converts "99.99" to 99.99
println('Price as number: ${price_as_float}')

// Specify exact number types when needed
large_number := i64(9999999999)      // 64-bit integer
small_number := u8(255)              // 8-bit unsigned integer

println('Large number: ${large_number}')
println('Small number: ${small_number}')

// Important: V is very strict about types for safety
// This prevents many common programming errors
```

### Variable Naming

There are several rules for variable names in V:

1. **Use snake_case**: Variable and function names must use `snake_case` style
2. **No reserved keywords**: Cannot use V keywords like `fn`, `mut`, `const`, etc.
3. **Descriptive names**: Use clear, meaningful names that describe the data

```v
// ✅ Good variable names
user_name := 'John'
total_price := 99.99
is_logged_in := true
max_retry_count := 3

// ❌ Bad variable names  
n := 'John'              // too short, unclear
totalPrice := 99.99      // camelCase not allowed
IsLoggedIn := true       // PascalCase not allowed  
3_count := 3             // cannot start with digit
```

### Multiple Variable Declaration

You can declare multiple variables in one line:

```v
name, age, city := 'Alice', 25, 'New York'
println('${name} is ${age} years old and lives in ${city}')
// Alice is 25 years old and lives in New York
```

### Practical Examples

Here are some common variable usage patterns:

```v
// User information
user_id := 12345
full_name := 'John Smith'
email := 'john.smith@email.com'
registration_date := '2024-01-15'

// Shopping cart
item_count := 3
subtotal := 129.97
tax_rate := 0.08
shipping_cost := 15.00
total := subtotal + (subtotal * tax_rate) + shipping_cost

println('Order Summary:')
println('Items: ${item_count}')
println('Subtotal: $${subtotal:.2f}')
println('Tax: $${subtotal * tax_rate:.2f}')
println('Shipping: $${shipping_cost:.2f}')
println('Total: $${total:.2f}')
```

Unlike most other languages, V only allows defining variables in functions. By default V does not allow **global variables**. See more [details](#global-variables).

For consistency across different code bases, all variable and function names must use the `snake_case` style, as opposed to type names, which must use `PascalCase`.

### Mutable variables

```v
mut age := 20
println(age)  // 20
age = 21
println(age)  // 21
```

To change the value of a variable, use `=`. In V, variables are **immutable by default**. To be able to change the value of the variable, you have to declare it with `mut`.

### Understanding Mutability

**Immutable variables** (default):
```v
name := 'Alice'
score := 100
// name = 'Bob'    // ❌ Error: cannot assign to `name` (not mutable)
// score = 150     // ❌ Error: cannot assign to `score` (not mutable)
```

**Mutable variables** (with `mut`):
```v
mut counter := 0
mut message := 'Loading'
mut items := []string{}

counter = 1           // ✅ OK
message = 'Complete'  // ✅ OK  
items << 'item1'      // ✅ OK
println('Counter: ${counter}, Status: ${message}, Items: ${items}')
```

### Practical Examples with Mutable Variables

**Game Score Tracking:**
```v
mut player_score := 0
mut level := 1
mut lives := 3

// Simulate gameplay
println('Game Started - Score: ${player_score}, Level: ${level}, Lives: ${lives}')

// Player scores points
player_score += 100
println('Points scored! New score: ${player_score}')

// Player completes level
if player_score >= 500 {
    level++
    player_score += 250  // Level completion bonus
    println('Level up! Now at level ${level}, Score: ${player_score}')
}

// Player loses a life
lives--
if lives == 0 {
    println('Game Over! Final score: ${player_score}')
} else {
    println('Lives remaining: ${lives}')
}
```

**Shopping Cart Management:**
```v
mut cart_total := 0.0
mut item_count := 0
mut discount_applied := false

// Add items to cart
fn add_item(mut total f64, mut count int, price f64) {
    total += price
    count++
    println('Added item ($${price:.2f}) - Total: $${total:.2f}, Items: ${count}')
}

add_item(mut cart_total, mut item_count, 29.99)
add_item(mut cart_total, mut item_count, 15.50)
add_item(mut cart_total, mut item_count, 42.00)

// Apply discount for orders over $75
if cart_total > 75.0 && !discount_applied {
    cart_total *= 0.9  // 10% discount
    discount_applied = true
    println('Discount applied! New total: $${cart_total:.2f}')
}
```

**String Building:**
```v
mut html_output := '<html><body>'
mut css_classes := []string{}
mut element_count := 0

// Build HTML content
html_output += '<h1>Welcome</h1>'
css_classes << 'header'
element_count++

html_output += '<p>This is a paragraph.</p>'
css_classes << 'content'
element_count++

html_output += '<footer>Copyright 2024</footer>'
css_classes << 'footer'
element_count++

html_output += '</body></html>'

println('Generated HTML:')
println(html_output)
println('CSS classes used: ${css_classes}')
println('Total elements: ${element_count}')
```

Try compiling a program after removing `mut` from the first line - you'll get a compilation error because immutable variables cannot be changed.

### Initialization vs assignment

Note the (important) difference between `:=` and `=`:

* **`:=`** is used for **declaring and initializing** (creating a new variable)
* **`=`** is used for **assigning** (changing an existing mutable variable's value)

**❌ This will not compile** (variable not declared):
```v failcompile
fn main() {
    age = 21  // Error: variable `age` not declared
}
```

**✅ This works** (proper declaration):
```v
fn main() {
    age := 21  // Declare and initialize
}
```

### Multiple Variable Assignment

The values of multiple variables can be changed in one line. In this way, their values can be swapped without an intermediary variable:

```v
mut a := 0
mut b := 1
println('Before: a=${a}, b=${b}')  // Before: a=0, b=1

a, b = b, a  // Swap values
println('After: a=${a}, b=${b}')   // After: a=1, b=0
```

### Assignment Examples in Practice

**Variable Swapping in Data Processing:**
```v
mut temperatures := [72.5, 68.3, 75.1, 70.2]
println('Original temperatures: ${temperatures}')

// Swap first and last temperatures
mut first := temperatures[0]
mut last := temperatures[temperatures.len - 1]
first, last = last, first

temperatures[0] = first
temperatures[temperatures.len - 1] = last
println('After swapping: ${temperatures}')
```

**User Input Processing:**
```v
import os

// Get user information
username := os.input('Enter username: ')
mut password := os.input('Enter password: ')

// Process the data  
processed_username := username.trim(' ').to_lower()
password = password.trim(' ')  // Clean password (mutable)

if processed_username.len < 3 {
    println('Username too short!')
} else if password.len < 8 {
    println('Password too short!')
} else {
    println('Welcome, ${processed_username}!')
}
```

**Configuration Variables:**
```v
// Application configuration (immutable after setup)
app_name := 'MyApp'
version := '1.0.0'
debug_mode := true

// Runtime state (mutable)
mut current_user := ''
mut session_count := 0  
mut last_login := ''

fn login(username string) {
    // Cannot change app_name, version, debug_mode
    // Can only change mutable variables
    current_user = username    // ✅ OK - mutable
    session_count++           // ✅ OK - mutable  
    last_login = '2024-01-15' // ✅ OK - mutable
    
    if debug_mode {
        println('${app_name} v${version}: ${username} logged in')
        println('Session count: ${session_count}')
    }
}

login('alice')
```

All variables need to be declared in V before use, ensuring type safety and preventing common programming errors.

### Variables Summary

In V, variables are the foundation of data storage and manipulation:

* **Use `:=` for declaration and initialization** (creates new variable)
* **Use `=` for assignment** (changes mutable variable)
* **Variables are immutable by default** - use `mut` for mutable variables  
* **All variables must be initialized** when declared
* **Type is inferred** from the initial value
* **Use `snake_case`** for variable names
* **Explicit type conversion** available with `T(value)` syntax

### Practice Tasks

These exercises will help you master V variables. Each builds on the previous concepts, so try them in order!

#### Task 1: Working with Variables

**Difficulty: Beginner**

Create a simple program that does the following:

1. Create a variable called `name` and set it to `"John"`
2. Create another variable called `admin` 
3. Copy the value from `name` into `admin`
4. Print the value of `admin` (it should show "John")

<details>
<summary>💡 Show Solution</summary>

```v
// Step 1: Create name variable with value "John"
name := 'John'

// Step 2: Create admin variable and copy name's value into it  
admin := name

// Step 3: Display the admin variable's value
println(admin) // This will print: John
```

**What you learned:**
- How to create variables with `:=`
- How to copy values between variables  
- How to display variable values with `println`

</details>

#### Task 2: Mutable Variables Practice

**Difficulty: Beginner**

Build a simple counter program:

1. Start with a counter at 0 (make it mutable!)
2. Add 5 to it three times
3. Print the counter value after each addition
4. Show the final total

<details>
<summary>💡 Show Solution</summary>

```v
// Step 1: Create a mutable counter starting at 0
mut counter := 0
println('Starting counter: ${counter}')  // Shows: 0

// Step 2: Add 5 three times, printing each time
counter += 5  // Same as: counter = counter + 5
println('After first +5: ${counter}')    // Shows: 5

counter += 5  
println('After second +5: ${counter}')   // Shows: 10

counter += 5
println('After third +5: ${counter}')    // Shows: 15

// Step 3: Show final result
println('Final total: ${counter}')       // Shows: 15
```

**What you learned:**
- How to make variables mutable with `mut`
- How to modify variable values with `+=` 
- How to use string interpolation with `${}`

</details>

#### Task 3: Type Conversions

**Difficulty: Beginner+**

Create a shopping calculator:

1. Start with a price as text: `"29.99"`
2. Start with quantity as text: `"3"`  
3. Convert both to numbers
4. Calculate the total cost
5. Display it nicely: `"Total: $89.97"`

<details>
<summary>💡 Show Solution</summary>

```v
// Step 1: Start with text values (like user input)
price_text := '29.99'     // Price as string
quantity_text := '3'      // Quantity as string

println('Price (text): "${price_text}"')
println('Quantity (text): "${quantity_text}"')

// Step 2: Convert text to numbers
price := price_text.f64()        // Convert to floating-point number
quantity := quantity_text.int()  // Convert to integer

println('Price (number): ${price}')
println('Quantity (number): ${quantity}')

// Step 3: Calculate total cost
total := price * f64(quantity)   // Convert quantity to f64 for multiplication

// Step 4: Display result nicely formatted
println('Total cost: $${total:.2f}')  // Shows: Total cost: $89.97
```

**What you learned:**
- How to convert strings to numbers with `.f64()` and `.int()`
- How to do math with different number types
- How to format money values with `:.2f` (2 decimal places)

</details>

```v
price_str := '29.99'
quantity_str := '3'

price := price_str.f64()
quantity := quantity_str.int()

total := price * quantity
println('Total: $${total:.2f}')
```

</details>

#### Task 4: Variable Naming  
**importance: 2**

Which of these variable names are valid in V? Fix the invalid ones:

```v
// Evaluate these:
userName := 'Alice'        // ?
user_name := 'Alice'       // ?
3count := 5                // ?
totalPrice := 99.99        // ?
total_price := 99.99       // ?
is-logged-in := true       // ?
is_logged_in := true       // ?
```

<details>
<summary>Solution</summary>

```v
// userName := 'Alice'        // ❌ Should use snake_case
user_name := 'Alice'       // ✅ Correct
// 3count := 5                // ❌ Cannot start with digit  
count_3 := 5               // ✅ Fixed
// totalPrice := 99.99        // ❌ Should use snake_case
total_price := 99.99       // ✅ Correct
// is-logged-in := true       // ❌ Hyphens not allowed
is_logged_in := true       // ✅ Correct
```

</details>

#### Task 5: Shopping Cart Calculator
**importance: 4**

Create a shopping cart program that:
1. Has mutable variables for: `total_cost`, `item_count`, `discount_rate`
2. Starts with `total_cost = 0.0`, `item_count = 0`, `discount_rate = 0.1`
3. Adds three items with prices: $15.99, $23.50, $8.75
4. For each item: increment count, add to total, show running total
5. Apply discount if total > $40
6. Show final summary with original total, discount amount, and final cost

<details>
<summary>Solution</summary>

```v
mut total_cost := 0.0
mut item_count := 0
discount_rate := 0.1

prices := [15.99, 23.50, 8.75]

println('Shopping Cart:')
for price in prices {
    total_cost += price
    item_count++
    println('Added item $${price:.2f} | Running total: $${total_cost:.2f} | Items: ${item_count}')
}

println('\n--- Summary ---')
println('Original total: $${total_cost:.2f}')
println('Items: ${item_count}')

if total_cost > 40.0 {
    discount_amount := total_cost * discount_rate
    final_cost := total_cost - discount_amount
    println('Discount (${discount_rate * 100}%): -$${discount_amount:.2f}')
    println('Final cost: $${final_cost:.2f}')
} else {
    println('No discount (minimum $40.00 for discount)')
    println('Final cost: $${total_cost:.2f}')
}
```

</details>

---

### Warnings and declaration errors

In development mode the compiler will warn you that you haven't used the variable
(you'll get an "unused variable" warning).
In production mode (enabled by passing the `-prod` flag to v – `v -prod foo.v`)
it will not compile at all (like in Go).
```v
fn main() {
    a := 10
    // warning: unused variable `a`
}
```

To ignore values returned by a function `_` can be used

```v
fn foo() (int, int) {
    return 2, 3
}

fn main() {
    c, _ := foo()
    print(c)
    // no warning about unused variable returned by foo.
}
```

Unlike most languages, variable shadowing is not allowed. Declaring a variable with a name
that is already used in a parent scope will cause a compilation error.

```v failcompile nofmt
fn main() {
    a := 10
    {
        a := 20 // error: redefinition of `a`
    }
}
```

While variable shadowing is not allowed, field shadowing is allowed.

```v
pub struct Dimension {
    width  int = -1
    height int = -1
}

pub struct Test {
    Dimension
    width int = 100
    // height int
}

fn main() {
    test := Test{}
    println('${test.width} ${test.height} ${test.Dimension.width}') // 100 -1 -1
}
```

## Quick Start: Practical Examples

Here are some common, copy-paste ready examples to get you started with V programming:

### Simple Calculator

```v
import os

fn add(x f64, y f64) f64 {
    return x + y
}

fn subtract(x f64, y f64) f64 {
    return x - y
}

fn multiply(x f64, y f64) f64 {
    return x * y
}

fn divide(x f64, y f64) f64 {
    if y == 0 {
        eprintln('Error: Division by zero!')
        exit(1)
    }
    return x / y
}

fn main() {
    println('Simple Calculator')
    println('Enter first number:')
    num1 := os.input('> ').f64()
    
    println('Enter operation (+, -, *, /):')
    operation := os.input('> ')
    
    println('Enter second number:')
    num2 := os.input('> ').f64()
    
    result := match operation {
        '+' { add(num1, num2) }
        '-' { subtract(num1, num2) }
        '*' { multiply(num1, num2) }
        '/' { divide(num1, num2) }
        else {
            eprintln('Invalid operation!')
            exit(1)
        }
    }
    
    println('Result: ${result}')
}
```

### File Reader and Writer

```v
import os

fn main() {
    // Write to a file
    content := 'Hello from V!\nThis is line 2.\nAnd this is line 3.'
    os.write_file('sample.txt', content) or {
        eprintln('Error writing file: ${err}')
        exit(1)
    }
    println('File written successfully!')
    
    // Read from the file
    file_content := os.read_file('sample.txt') or {
        eprintln('Error reading file: ${err}')
        exit(1)
    }
    println('File contents:')
    println(file_content)
    
    // Read file line by line
    lines := os.read_lines('sample.txt') or {
        eprintln('Error reading lines: ${err}')
        exit(1)
    }
    println('\nFile lines:')
    for i, line in lines {
        println('Line ${i + 1}: ${line}')
    }
}
```

### Simple HTTP Request

```v
import net.http

fn main() {
    // Make a GET request
    resp := http.get('https://api.github.com/users/vlang') or {
        eprintln('Error making request: ${err}')
        return
    }
    
    println('Status Code: ${resp.status_code}')
    println('Response Body:')
    println(resp.body)
    
    // Make a POST request
    data := '{"name": "test", "value": "example"}'
    post_resp := http.post_json('https://httpbin.org/post', data) or {
        eprintln('Error making POST request: ${err}')
        return
    }
    
    println('\nPOST Response Status: ${post_resp.status_code}')
    println('POST Response Body:')
    println(post_resp.body)
}
```

### Working with JSON

```v
import json
import os

struct Person {
    name string
    age  int
    city string
}

fn main() {
    // Create a person
    person := Person{
        name: 'John Doe'
        age: 30
        city: 'New York'
    }
    
    // Convert to JSON
    json_str := json.encode(person)
    println('Person as JSON: ${json_str}')
    
    // Save JSON to file
    os.write_file('person.json', json_str) or {
        eprintln('Error saving JSON: ${err}')
        return
    }
    
    // Read JSON from file
    file_content := os.read_file('person.json') or {
        eprintln('Error reading JSON file: ${err}')
        return
    }
    
    // Parse JSON back to struct
    loaded_person := json.decode(Person, file_content) or {
        eprintln('Error parsing JSON: ${err}')
        return
    }
    
    println('Loaded person: ${loaded_person.name}, ${loaded_person.age}, ${loaded_person.city}')
}
```

### Simple Web Server

```v
import vweb
import json

struct App {
    vweb.Context
}

struct Message {
    text string
    time string
}

fn main() {
    mut app := App{}
    vweb.run(app, 8080)
}

['/']
pub fn (mut app App) index() vweb.Result {
    return app.html('<h1>Welcome to V Web Server!</h1><p><a href="/api/hello">API Example</a></p>')
}

['/api/hello']
pub fn (mut app App) hello() vweb.Result {
    message := Message{
        text: 'Hello from V!'
        time: '2024-01-01T12:00:00Z'
    }
    app.set_content_type('application/json')
    return app.json(json.encode(message))
}

['/api/echo'; post]
pub fn (mut app App) echo() vweb.Result {
    body := app.req.data
    app.set_content_type('application/json')
    return app.json('{"received": "${body}"}')
}
```

### Command Line Tool with Arguments

```v
import os

fn print_usage() {
    println('Usage: word_counter [OPTIONS] <filename>')
    println('Count words, lines, and characters in a file')
    println('')
    println('Options:')
    println('  -w, --words     Show word count only')
    println('  -l, --lines     Show line count only') 
    println('  -c, --chars     Show character count only')
    println('  -h, --help      Show this help message')
}

fn count_file_stats(filename string) !(int, int, int) {
    if !os.exists(filename) {
        return error('File "${filename}" does not exist')
    }
    
    content := os.read_file(filename) or {
        return error('Could not read file: ${err}')
    }
    
    lines := content.split('\n').len
    words := content.split(' ').filter(it.trim(' ') != '').len
    chars := content.len
    
    return lines, words, chars
}

fn main() {
    if os.args.len < 2 {
        print_usage()
        exit(1)
    }
    
    mut show_words_only := false
    mut show_lines_only := false  
    mut show_chars_only := false
    mut filename := ''
    
    // Parse command line arguments
    for i := 1; i < os.args.len; i++ {
        arg := os.args[i]
        match arg {
            '-h', '--help' {
                print_usage()
                exit(0)
            }
            '-w', '--words' {
                show_words_only = true
            }
            '-l', '--lines' {
                show_lines_only = true
            }
            '-c', '--chars' {
                show_chars_only = true
            }
            else {
                if arg.starts_with('-') {
                    eprintln('Unknown option: ${arg}')
                    print_usage()
                    exit(1)
                } else {
                    filename = arg
                }
            }
        }
    }
    
    if filename == '' {
        eprintln('Error: No filename provided')
        print_usage()
        exit(1)
    }
    
    // Count file statistics
    lines, words, chars := count_file_stats(filename) or {
        eprintln('Error: ${err}')
        exit(1)
    }
    
    // Display results based on options
    if show_lines_only {
        println('${lines}')
    } else if show_words_only {
        println('${words}')
    } else if show_chars_only {
        println('${chars}')
    } else {
        println('File: ${filename}')
        println('Lines: ${lines}')
        println('Words: ${words}')
        println('Characters: ${chars}')
    }
}
```

## V Data Types

In V, every variable has a type. The type determines what kind of data the variable can hold and what operations can be performed on it. V has a rich type system that includes primitive types, composite types, and user-defined types.

### Overview of V Types

V provides these main categories of types:

1. **Primitive types**: `bool`, `string`, integers, floats
2. **Composite types**: arrays, maps, structs  
3. **Special types**: optionals, results, sum types
4. **Reference types**: pointers, references

### Primitive Types

#### Boolean Type

The `bool` type represents logical values - either `true` or `false`:

```v
is_active := true
is_disabled := false

// Boolean operations
has_permission := is_active && !is_disabled
println('Access granted: ${has_permission}')

// Boolean in conditions
if is_active {
    println('System is running')
} else {
    println('System is stopped')
}
```

**Practical Boolean Examples:**
```v
// User authentication system
mut user_logged_in := false
mut has_admin_rights := false

fn login(username string, password string) bool {
    // Simulate login logic
    if username == 'admin' && password == 'secret123' {
        user_logged_in = true
        has_admin_rights = true
        return true
    }
    return false
}

// Feature flags
enable_dark_mode := true
enable_notifications := false
enable_beta_features := false

if enable_dark_mode {
    println('Applying dark theme...')
}
```

#### Integer Types

V provides several integer types of different sizes:

```v ignore
i8    // 8-bit signed integer (-128 to 127)
i16   // 16-bit signed integer (-32,768 to 32,767)
int   // 32-bit signed integer (-2,147,483,648 to 2,147,483,647)
i64   // 64-bit signed integer (-9,223,372,036,854,775,808 to 9,223,372,036,854,775,807)

u8    // 8-bit unsigned integer (0 to 255)
u16   // 16-bit unsigned integer (0 to 65,535)
u32   // 32-bit unsigned integer (0 to 4,294,967,295)
u64   // 64-bit unsigned integer (0 to 18,446,744,073,709,551,615)

isize // platform-dependent signed integer
usize // platform-dependent unsigned integer
```

> **Note:** Unlike C and Go, `int` is always a 32-bit integer in V.

**Integer Examples:**
```v
// Age and counting
age := 25                    // int (default)
population := i64(7800000000) // Large numbers need explicit typing
user_id := u32(12345)        // Positive IDs can use unsigned

// Mathematical operations
a := 10
b := 3
println('${a} + ${b} = ${a + b}')   // Addition: 13
println('${a} - ${b} = ${a - b}')   // Subtraction: 7
println('${a} * ${b} = ${a * b}')   // Multiplication: 30
println('${a} / ${b} = ${a / b}')   // Integer division: 3
println('${a} % ${b} = ${a % b}')   // Modulus: 1

// Bitwise operations  
x := 12  // Binary: 1100
y := 10  // Binary: 1010
println('${x} & ${y} = ${x & y}')   // AND: 8 (1000)
println('${x} | ${y} = ${x | y}')   // OR: 14 (1110)
println('${x} ^ ${y} = ${x ^ y}')   // XOR: 6 (0110)
```

**Real-world Integer Examples:**
```v
// E-commerce system
product_id := u32(501234)
stock_count := 15
price_cents := 2999  // $29.99 in cents to avoid float precision issues

// User scoring system
base_score := 1000
bonus_points := 250
total_score := base_score + bonus_points

// File size handling
file_size_bytes := i64(1048576)  // 1MB
file_size_kb := file_size_bytes / 1024
file_size_mb := file_size_kb / 1024

println('File size: ${file_size_mb}MB (${file_size_bytes} bytes)')
```

#### Floating Point Types

V provides two floating-point types:

```v ignore  
f32  // 32-bit floating point
f64  // 64-bit floating point (default)
```

**Float Examples:**
```v
// Default float is f64
temperature := 23.5
pi := 3.14159265359

// Explicit f32 (less precision, more memory efficient)
latitude := f32(40.7128)
longitude := f32(-74.0060)

// Scientific notation
speed_of_light := 2.99792458e8  // 299,792,458 m/s
planck_constant := 6.62607015e-34

// Float arithmetic
radius := 5.0
area := pi * radius * radius
circumference := 2.0 * pi * radius

println('Circle with radius ${radius}:')
println('Area: ${area:.2f}')
println('Circumference: ${circumference:.2f}')
```

**Financial Calculations Example:**
```v
// Banking system - be careful with float precision!
balance := 1250.75
deposit := 500.25
withdrawal := 200.50

new_balance := balance + deposit - withdrawal
println('Account balance: $${new_balance:.2f}')

// Interest calculation
interest_rate := 0.035  // 3.5% annual
annual_interest := balance * interest_rate
monthly_interest := annual_interest / 12.0

println('Monthly interest: $${monthly_interest:.2f}')
```

#### Type Conversion and Promotion

V supports automatic type promotion for compatible types, but explicit conversion is often needed:

**Automatic Promotion:**
```v ignore
   i8 → i16 → int → i64
                  ↘     ↘
                    f32 → f64
                  ↗     ↗
   u8 → u16 → u32 → u64 ⬎
      ↘     ↘     ↘      ptr
   i8 → i16 → int → i64 ⬏
```

**Type Conversion Examples:**
```v
// Explicit conversions
age_int := 25
age_float := f64(age_int)        // int to f64
age_string := age_int.str()      // int to string

// String to number conversions
price_str := '29.99'
price := price_str.f64()         // string to f64

quantity_str := '5'
quantity := quantity_str.int()   // string to int

// Handling conversion errors
invalid_number := 'abc'
parsed_num := invalid_number.int() // Returns 0 for invalid strings
println('Parsed: ${parsed_num}')    // Output: 0

// Safe conversion with validation
fn safe_string_to_int(s string) ?int {
    if s.contains_only('0123456789') {
        return s.int()
    }
    return error('Invalid integer: ${s}')
}

result := safe_string_to_int('123') or {
    println('Conversion failed: ${err}')
    -1
}
println('Result: ${result}')  // Output: 123
```

**Practical Type Conversion Example:**
```v
import os

// Configuration file parser
struct Config {
    port        int
    host        string
    debug       bool
    timeout     f64
    max_clients int
}

fn parse_config() Config {
    // Simulate reading from environment or file
    port_str := '8080'
    host := 'localhost'
    debug_str := 'true'
    timeout_str := '30.5'
    clients_str := '100'
    
    return Config{
        port: port_str.int()
        host: host
        debug: debug_str == 'true'
        timeout: timeout_str.f64() 
        max_clients: clients_str.int()
    }
}

config := parse_config()
println('Server config:')
println('  Host: ${config.host}:${config.port}')
println('  Debug mode: ${config.debug}')
println('  Timeout: ${config.timeout}s')
println('  Max clients: ${config.max_clients}')
```

### Strings

Strings in V are one of the most fundamental data types. They represent text data and are heavily used in most programs.

#### String Basics

In V, strings are UTF-8 encoded and **immutable** (read-only) by default:

```v
// Basic string creation
name := 'Alice'
message := 'Hello, World!'
empty_string := ''

// String length (in bytes, not characters!)
greeting := 'Hello 🌎'
println('Bytes: ${greeting.len}')      // 10 bytes (emoji takes 4 bytes)
println('Characters: ${greeting.runes().len}')  // 7 characters

// Both single and double quotes work
single_quoted := 'Hello'
double_quoted := "Hello"  // vfmt will convert to single quotes
```

#### String Immutability

Strings cannot be modified after creation:

```v failcompile
mut text := 'hello'
text[0] = 'H'  // ❌ Error: strings are immutable
```

To "modify" a string, you create a new one:

```v
original := 'hello'
capitalized := original.to_upper()  // Creates new string: "HELLO"
println('Original: ${original}')    // Still "hello"
println('Capitalized: ${capitalized}')
```

#### String Indexing and Slicing

**Indexing** returns bytes (`u8`), not characters:

```v
name := 'Bob'
first_byte := name[0]        // u8(66) - ASCII value of 'B'
first_char := name[0].ascii_str()  // 'B' as string

println('First byte value: ${first_byte}')  // 66
println('First character: ${first_char}')   // B
```

**Slicing** creates new strings:

```v
sentence := 'Hello World'
hello := sentence[0..5]      // 'Hello' 
world := sentence[6..11]     // 'World'
from_6th := sentence[6..]    // 'World'
first_5 := sentence[..5]     // 'Hello'

println('Parts: "${hello}" and "${world}"')
```

#### Unicode and Emojis

For proper Unicode handling, use `runes()`:

```v
text := 'Hi 👋 there! 🌟'
println('Byte length: ${text.len}')           // Counts bytes
println('Character length: ${text.runes().len}')  // Counts Unicode chars

// Access Unicode characters properly
runes := text.runes()
for i, r in runes {
    println('Character ${i}: ${r}')
}
```

#### Escape Sequences

V supports various escape sequences:

```v
// Common escapes
newline := 'Line 1\nLine 2'
tab_separated := 'Name\tAge\tCity'
quotes := 'She said "Hello" to me'
backslash := 'C:\\Users\\Documents'

// Unicode escapes
star := '\u2605'        // ★
smiley := '\u263A'      // ☺
heart := '\u2764'       // ❤

// Hex escapes
hex_a := '\x61'         // 'a' (ASCII 97)
bell := '\x07'          // Bell character

println('Star: ${star}, Smiley: ${smiley}, Heart: ${heart}')
```

#### Raw Strings

Use raw strings when you don't want escape processing:

```v
// Regular string with escapes
regular := 'Path: C:\\Users\\Name\\file.txt\nLine 2'
println(regular)  // Escapes are processed

// Raw string - escapes are literal
raw := r'Path: C:\Users\Name\file.txt\nLine 2'
println(raw)      // \n and \\ appear as-is
```

#### String Interpolation

V provides powerful string interpolation with `${}`:

**Basic Interpolation:**
```v
name := 'Alice'
age := 30
city := 'New York'

message := 'Hello, my name is ${name}, I am ${age} years old and live in ${city}.'
println(message)
```

**Expressions in Interpolation:**
```v
x := 10
y := 20
println('${x} + ${y} = ${x + y}')          // Arithmetic
println('Is x > y? ${x > y}')              // Boolean expression
println('Uppercase name: ${name.to_upper()}')  // Method calls
```

**Format Specifiers:**
```v
price := 29.99
quantity := 3
discount := 0.15

// Number formatting
println('Price: $${price:.2f}')                    // $29.99 (2 decimals)
println('Quantity: ${quantity:03}')               // 003 (3 digits, zero-padded)
println('Discount: ${discount:.1%}')              // 15.0% (percentage)

// Advanced formatting
pi := 3.14159265359
println('Pi: ${pi:.4f}')                          // 3.1416 (4 decimals)
println('Pi scientific: ${pi:.2e}')               // 3.14e+00
```

#### String Methods

V provides many useful string methods:

**Case Conversion:**
```v
text := 'Hello World'
println(text.to_lower())    // 'hello world'
println(text.to_upper())    // 'HELLO WORLD'
println(text.capitalize())  // 'Hello world' (first letter only)
```

**Searching and Testing:**
```v
sentence := 'The quick brown fox jumps over the lazy dog'

// Searching
println(sentence.contains('fox'))           // true
println(sentence.starts_with('The'))       // true  
println(sentence.ends_with('dog'))         // true
println(sentence.index('fox') or { -1 })   // Position of 'fox'

// Testing
email := 'user@example.com'
println(email.contains('@'))               // true

url := 'https://example.com'
println(url.starts_with('https://'))       // true
```

**String Splitting and Joining:**
```v
// Splitting
csv_line := 'Alice,30,Engineer,New York'
parts := csv_line.split(',')
println('Name: ${parts[0]}, Age: ${parts[1]}, Job: ${parts[2]}, City: ${parts[3]}')

// Splitting by whitespace
sentence := 'Hello world from V'
words := sentence.split(' ')
println('Words: ${words}')

// Joining
names := ['Alice', 'Bob', 'Charlie']
name_list := names.join(', ')
println('Names: ${name_list}')  // 'Alice, Bob, Charlie'
```

**Trimming:**
```v
// Remove whitespace
messy := '   Hello World   '
clean := messy.trim(' ')
println('"${clean}"')  // "Hello World"

// Remove specific characters
path := '/home/user/documents/'
clean_path := path.trim('/')
println(clean_path)  // 'home/user/documents'
```

#### Real-world String Examples

**Email Validation:**
```v
fn is_valid_email(email string) bool {
    return email.contains('@') && 
           email.contains('.') && 
           !email.starts_with('@') && 
           !email.ends_with('@')
}

emails := ['user@example.com', 'invalid.email', 'test@test.']
for email in emails {
    if is_valid_email(email) {
        println('✅ ${email} is valid')
    } else {
        println('❌ ${email} is invalid')
    }
}
```

**URL Builder:**
```v
struct URLBuilder {
mut:
    base_url string
    path     string
    params   map[string]string
}

fn (mut u URLBuilder) add_path(path string) URLBuilder {
    u.path += '/${path}'
    return u
}

fn (mut u URLBuilder) add_param(key string, value string) URLBuilder {
    u.params[key] = value
    return u
}

fn (u URLBuilder) build() string {
    mut url := u.base_url + u.path
    if u.params.len > 0 {
        url += '?'
        mut param_parts := []string{}
        for key, value in u.params {
            param_parts << '${key}=${value}'
        }
        url += param_parts.join('&')
    }
    return url
}

// Usage
mut builder := URLBuilder{base_url: 'https://api.example.com'}
final_url := builder.add_path('users').add_path('123')
    .add_param('format', 'json')
    .add_param('fields', 'name,email')
    .build()

println(final_url)  // https://api.example.com/users/123?format=json&fields=name,email
```

**Log Message Formatter:**
```v
import time

enum LogLevel {
    debug
    info
    warning
    error
}

fn format_log(level LogLevel, message string, context map[string]string) string {
    timestamp := time.now().format('2006-01-02 15:04:05')
    level_str := match level {
        .debug { 'DEBUG' }
        .info { 'INFO ' }
        .warning { 'WARN ' } 
        .error { 'ERROR' }
    }
    
    mut log_line := '[${timestamp}] ${level_str}: ${message}'
    
    if context.len > 0 {
        mut context_parts := []string{}
        for key, value in context {
            context_parts << '${key}=${value}'
        }
        log_line += ' | ${context_parts.join(' ')}'
    }
    
    return log_line
}

// Usage
context := {
    'user_id': '12345'
    'action': 'login'
    'ip': '192.168.1.100'
}

log_msg := format_log(.info, 'User successfully logged in', context)
println(log_msg)
// [2024-01-15 14:30:45] INFO : User successfully logged in | user_id=12345 action=login ip=192.168.1.100
```

```v
s := r'hello\nworld' // the `\n` will be preserved as two characters
println(s) // "hello\nworld"
```

Strings can be easily converted to integers:

```v
s := '42'
n := s.int() // 42

// all int literals are supported
assert '0xc3'.int() == 195
assert '0o10'.int() == 8
assert '0b1111_0000_1010'.int() == 3850
assert '-0b1111_0000_1010'.int() == -3850
```

For more advanced `string` processing and conversions, refer to the
[vlib/strconv](https://modules.vlang.io/strconv.html) module.

#### String interpolation

Basic interpolation syntax is pretty simple - use `${` before a variable name and `}` after. The
variable will be converted to a string and embedded into the literal:

```v
name := 'Bob'
println('Hello, ${name}!') // Hello, Bob!
```

It also works with fields: `'age = ${user.age}'`. You may also use more complex expressions:
`'can register = ${user.age > 13}'`.

Format specifiers similar to those in C's `printf()` are also supported. `f`, `g`, `x`, `o`, `b`,
etc. are optional and specify the output format. The compiler takes care of the storage size, so
there is no `hd` or `llu`.

To use a format specifier, follow this pattern:

`${varname:[flags][width][.precision][type]}`

- flags: may be zero or more of the following: `-` to left-align output within the field, `0` to use
  `0` as the padding character instead of the default `space` character.
  > **Note**
  >
  > V does not currently support the use of `'` or `#` as format flags, and V supports but
  > doesn't need `+` to right-align since that's the default.
- width: may be an integer value describing the minimum width of total field to output.
- precision: an integer value preceded by a `.` will guarantee that many digits after the decimal
  point without any insignificant trailing zeros. If displaying insignificant zero's is desired,
  append a `f` specifier to the precision value (see examples below). Applies only to float
  variables and is ignored for integer variables.
- type: `f` and `F` specify the input is a float and should be rendered as such, `e` and `E` specify
  the input is a float and should be rendered as an exponent (partially broken), `g` and `G` specify
  the input is a float--the renderer will use floating point notation for small values and exponent
  notation for large values, `d` specifies the input is an integer and should be rendered in base-10
  digits, `x` and `X` require an integer and will render it as hexadecimal digits, `o` requires an
  integer and will render it as octal digits, `b` requires an integer and will render it as binary
  digits, `s` requires a string (almost never used).

  > **Note**
  >
  > When a numeric type can render alphabetic characters, such as hex strings or special values
  > like `infinity`, the lowercase version of the type forces lowercase alphabetics and the
  > uppercase version forces uppercase alphabetics.

  > **Note**
  >
  > In most cases, it's best to leave the format type empty. Floats will be rendered by
  > default as `g`, integers will be rendered by default as `d`, and `s` is almost always redundant.
  > There are only three cases where specifying a type is recommended:

- format strings are parsed at compile time, so specifying a type can help detect errors then
- format strings default to using lowercase letters for hex digits and the `e` in exponents. Use a
  uppercase type to force the use of uppercase hex digits and an uppercase `E` in exponents.
- format strings are the most convenient way to get hex, binary or octal strings from an integer.

See
[Format Placeholder Specification](https://en.wikipedia.org/wiki/Printf_format_string#Format_placeholder_specification)
for more information.

```v
x := 123.4567
println('[${x:.2}]') // round to two decimal places => [123.46]
println('[${x:10}]') // right-align with spaces on the left => [   123.457]
println('[${int(x):-10}]') // left-align with spaces on the right => [123       ]
println('[${int(x):010}]') // pad with zeros on the left => [0000000123]
println('[${int(x):b}]') // output as binary => [1111011]
println('[${int(x):o}]') // output as octal => [173]
println('[${int(x):X}]') // output as uppercase hex => [7B]

println('[${10.0000:.2}]') // remove insignificant 0s at the end => [10]
println('[${10.0000:.2f}]') // do show the 0s at the end, even though they do not change the number => [10.00]
```

V also has `r` and `R` switches, which will repeat the string the specified amount of times.

```v
println('[${'abc':3r}]') // [abcabcabc]
println('[${'abc':3R}]') // [ABCABCABC]
```

#### String operators

```v
name := 'Bob'
bobby := name + 'by' // + is used to concatenate strings
println(bobby) // "Bobby"
mut s := 'hello '
s += 'world' // `+=` is used to append to a string
println(s) // "hello world"
```

All operators in V must have values of the same type on both sides. You cannot concatenate an
integer to a string:

```v failcompile
age := 10
println('age = ' + age) // not allowed
```

> error: infix expr: cannot use `int` (right expression) as `string`

We have to either convert `age` to a `string`:

```v
age := 11
println('age = ' + age.str())
```

or use string interpolation (preferred):

```v
age := 12
println('age = ${age}')
```

See all methods of [string](https://modules.vlang.io/index.html#string)
and related modules [strings](https://modules.vlang.io/strings.html),
[strconv](https://modules.vlang.io/strconv.html).

### Runes

A `rune` represents a single UTF-32 encoded Unicode character and is an alias for `u32`.
To denote them, use <code>`</code> (backticks) :

```v
rocket := `🚀`
```

A `rune` can be converted to a UTF-8 string by using the `.str()` method.

```v
rocket := `🚀`
assert rocket.str() == '🚀'
```

A `rune` can be converted to UTF-8 bytes by using the `.bytes()` method.

```v
rocket := `🚀`
assert rocket.bytes() == [u8(0xf0), 0x9f, 0x9a, 0x80]
```

Hex, Unicode, and Octal escape sequences also work in a `rune` literal:

```v
assert `\x61` == `a`
assert `\141` == `a`
assert `\u0061` == `a`

// multibyte literals work too
assert `\u2605` == `★`
assert `\u2605`.bytes() == [u8(0xe2), 0x98, 0x85]
assert `\xe2\x98\x85`.bytes() == [u8(0xe2), 0x98, 0x85]
assert `\342\230\205`.bytes() == [u8(0xe2), 0x98, 0x85]
```

Note that `rune` literals use the same escape syntax as strings, but they can only hold one unicode
character. Therefore, if your code does not specify a single Unicode character, you will receive an
error at compile time.

Also remember that strings are indexed as bytes, not runes, so beware:

```v
rocket_string := '🚀'
assert rocket_string[0] != `🚀`
assert 'aloha!'[0] == `a`
```

A string can be converted to runes by the `.runes()` method.

```v
hello := 'Hello World 👋'
hello_runes := hello.runes() // [`H`, `e`, `l`, `l`, `o`, ` `, `W`, `o`, `r`, `l`, `d`, ` `, `👋`]
assert hello_runes.string() == hello
```

### Numbers

```v
a := 123
```

This will assign the value of 123 to `a`. By default `a` will have the
type `int`.

You can also use hexadecimal, binary or octal notation for integer literals:

```v
a := 0x7B
b := 0b01111011
c := 0o173
```

All of these will be assigned the same value, 123. They will all have type
`int`, no matter what notation you used.

V also supports writing numbers with `_` as separator:

```v
num := 1_000_000 // same as 1000000
three := 0b0_11 // same as 0b11
float_num := 3_122.55 // same as 3122.55
hexa := 0xF_F // same as 255
oct := 0o17_3 // same as 0o173
```

If you want a different type of integer, you can use casting:

```v
a := i64(123)
b := u8(42)
c := i16(12345)
```

Assigning floating point numbers works the same way:

```v
f := 1.0
f1 := f64(3.14)
f2 := f32(3.14)
```

If you do not specify the type explicitly, by default float literals
will have the type of `f64`.

Float literals can also be declared as a power of ten:

```v
f0 := 42e1 // 420
f1 := 123e-2 // 1.23
f2 := 456e+2 // 45600
```

### Arrays

An array is an ordered collection of data elements of the same type. Arrays are fundamental data structures that allow you to store multiple values in a single variable and access them by their position (index).

#### Array Basics

Arrays in V are created using square brackets `[]`. Each element can be accessed using an *index* starting from `0`:

```v
// Creating arrays with initial values
numbers := [1, 2, 3, 4, 5]
names := ['Alice', 'Bob', 'Charlie']
temperatures := [20.5, 23.1, 18.9, 25.4]

// Accessing elements
println(numbers[0])     // 1 (first element)
println(names[1])       // 'Bob' (second element)
println(temperatures[2]) // 18.9 (third element)

// Array properties
println('Numbers length: ${numbers.len}')      // 5
println('Names length: ${names.len}')          // 3
println('Last temperature: ${temperatures[temperatures.len - 1]}')  // 25.4
```

#### Mutable Arrays

To modify arrays, declare them as mutable with `mut`:

```v
mut scores := [85, 92, 78, 95, 88]
println('Original scores: ${scores}')

// Modify existing elements
scores[0] = 90  // Change first score
scores[2] = 85  // Change third score
println('Updated scores: ${scores}')

// Add elements using << operator
scores << 97    // Add single element
println('After adding 97: ${scores}')

// Add multiple elements
scores << [89, 91, 93]
println('After adding more scores: ${scores}')
```

#### Array Operations

**Adding Elements:**
```v
mut shopping_list := ['bread', 'milk']
println('Initial list: ${shopping_list}')

// Add single items
shopping_list << 'eggs'
shopping_list << 'butter'
println('After adding items: ${shopping_list}')

// Add multiple items at once
shopping_list << ['cheese', 'apples', 'bananas']
println('Final list: ${shopping_list}')
```

**Checking if Element Exists:**
```v
fruits := ['apple', 'banana', 'orange', 'grape']

// Using 'in' operator
println('apple' in fruits)      // true
println('mango' in fruits)      // false

// Practical use in validation
valid_colors := ['red', 'green', 'blue', 'yellow']
user_choice := 'purple'

if user_choice in valid_colors {
    println('Valid color selected: ${user_choice}')
} else {
    println('Invalid color. Please choose from: ${valid_colors}')
}
```

#### Array Initialization Patterns

**Empty Arrays:**
```v
// Empty arrays with explicit type
mut integers := []int{}
mut strings := []string{}
mut floats := []f64{}

// Add elements later
integers << 1
strings << 'first'
floats << 3.14
```

**Pre-allocated Arrays:**
```v
// Create array with specific length and initial value
mut zeros := []int{len: 5, init: 0}
println(zeros)  // [0, 0, 0, 0, 0]

mut ones := []int{len: 3, init: 1}
println(ones)   // [1, 1, 1]

// Using index for initialization
sequence := []int{len: 5, init: index}
println(sequence)  // [0, 1, 2, 3, 4]

squares := []int{len: 5, init: index * index}
println(squares)   // [0, 1, 4, 9, 16]
```

**Performance Optimized Arrays:**
```v
// Pre-allocate capacity to avoid reallocations
mut large_numbers := []int{cap: 10000}
println('Length: ${large_numbers.len}, Capacity: ${large_numbers.cap}')

// Adding elements won't trigger reallocation until capacity is exceeded
for i in 0 .. 5000 {
    large_numbers << i
}
println('After adding 5000 elements: len=${large_numbers.len}, cap=${large_numbers.cap}')
```

#### Multi-dimensional Arrays

V supports arrays of arrays (matrices):

```v
// 2D array (matrix)
mut matrix := [][]int{}

// Add rows
matrix << [1, 2, 3]
matrix << [4, 5, 6] 
matrix << [7, 8, 9]

println('Matrix:')
for i, row in matrix {
    println('Row ${i}: ${row}')
}

// Access specific element: matrix[row][column]
println('Element at [1][2]: ${matrix[1][2]}')  // 6

// 3D array example
mut cube := [][][]int{}
cube << [[1, 2], [3, 4]]
cube << [[5, 6], [7, 8]]
println('3D array: ${cube}')
```

#### Real-world Array Examples

**Student Grade Management:**
```v
struct Student {
    name string
    grades []int
}

fn calculate_average(grades []int) f64 {
    if grades.len == 0 {
        return 0.0
    }
    mut total := 0
    for grade in grades {
        total += grade
    }
    return f64(total) / f64(grades.len)
}

fn find_highest_grade(grades []int) int {
    if grades.len == 0 {
        return 0
    }
    mut highest := grades[0]
    for grade in grades {
        if grade > highest {
            highest = grade
        }
    }
    return highest
}

students := [
    Student{'Alice', [92, 87, 95, 90]},
    Student{'Bob', [78, 82, 85, 88]},
    Student{'Charlie', [95, 98, 92, 97]},
]

for student in students {
    average := calculate_average(student.grades)
    highest := find_highest_grade(student.grades)
    println('${student.name}: Average = ${average:.1f}, Highest = ${highest}')
}
```

**Inventory Management System:**
```v
struct Product {
    id int
    name string
    price f64
    quantity int
}

mut inventory := []Product{}

// Add products
inventory << Product{1, 'Laptop', 999.99, 10}
inventory << Product{2, 'Mouse', 25.50, 50}
inventory << Product{3, 'Keyboard', 75.00, 25}

fn find_product_by_id(products []Product, id int) ?Product {
    for product in products {
        if product.id == id {
            return product
        }
    }
    return error('Product not found')
}

fn get_total_value(products []Product) f64 {
    mut total := 0.0
    for product in products {
        total += product.price * f64(product.quantity)
    }
    return total
}

fn low_stock_products(products []Product, threshold int) []Product {
    mut low_stock := []Product{}
    for product in products {
        if product.quantity < threshold {
            low_stock << product
        }
    }
    return low_stock
}

// Usage examples
product := find_product_by_id(inventory, 2) or {
    println('Product not found')
    return
}
println('Found: ${product.name} - $${product.price}')

total_value := get_total_value(inventory)
println('Total inventory value: $${total_value:.2f}')

low_items := low_stock_products(inventory, 20)
println('Low stock items: ${low_items.len}')
```

**Data Processing and Statistics:**
```v
// Function to find only even numbers in an array
fn filter_even_numbers(numbers []int) []int {
    mut evens := []int{}  // Create empty array for results
    
    // Check each number
    for num in numbers {
        if num % 2 == 0 {  // If number is even (divisible by 2)
            evens << num   // Add it to our results
        }
    }
    
    return evens
}

// Function to add up all numbers in an array
fn sum_array(numbers []int) int {
    mut total := 0
    
    // Add each number to the total
    for num in numbers {
        total += num
    }
    
    return total
}

// Function to find the smallest and largest numbers
fn find_min_max(numbers []int) (int, int) {
    // Handle empty array case
    if numbers.len == 0 {
        return 0, 0
    }
    
    // Start with first number as both min and max
    mut min := numbers[0]
    mut max := numbers[0]
    
    // Check each number against current min and max
    for num in numbers {
        if num < min {
            min = num  // Found new minimum
        }
        if num > max {
            max = num  // Found new maximum
        }
    }
    
    return min, max
}

// Function to sort numbers from smallest to largest (bubble sort)
fn sort_ascending(mut numbers []int) {
    n := numbers.len
    
    // Bubble sort algorithm (simple but not fastest)
    for i := 0; i < n - 1; i++ {
        for j := 0; j < n - i - 1; j++ {
            // If current number is bigger than next number
            if numbers[j] > numbers[j + 1] {
                // Swap them (put smaller number first)
                numbers[j], numbers[j + 1] = numbers[j + 1], numbers[j]
            }
        }
    }
}

// Demo: Data analysis example
println('=== Data Analysis Demo ===')
data := [15, 3, 8, 22, 7, 31, 4, 18, 12, 26]
println('Original data: ${data}')

// Find even numbers
evens := filter_even_numbers(data)
println('Even numbers: ${evens}')

// Calculate sum
total := sum_array(data)
average := f64(total) / f64(data.len)
println('Sum: ${total}')
println('Average: ${average:.1f}')

// Find range (min to max)
min_val, max_val := find_min_max(data)
println('Smallest: ${min_val}, Largest: ${max_val}')
println('Range: ${min_val} to ${max_val}')

// Sort the data (make a copy first so we don't change original)
mut sorted_data := data.clone()
sort_ascending(mut sorted_data)
println('Sorted data: ${sorted_data}')
```

**Restaurant Menu System with Arrays:**
```v
// Define what a menu item looks like
struct MenuItem {
    id          int     // Unique identifier (1, 2, 3...)
    title       string  // Name of the dish
    description string  // What's in the dish
    price       f64     // Cost in dollars
}

// Function to show the menu to customers
fn display_menu(items []MenuItem) {
    println('=== RESTAURANT MENU ===')
    
    // Show each menu item
    for item in items {
        // Format: "1. Caesar Salad - $12.99"
        println('${item.id}. ${item.title} - $${item.price:.2f}')
        // Show description indented
        println('   ${item.description}')
        println('')  // Empty line for spacing
    }
}

// Function to find a specific menu item by its ID number
fn find_menu_item(items []MenuItem, id int) ?MenuItem {
    // Look through all menu items
    for item in items {
        if item.id == id {
            return item  // Found it!
        }
    }
    // If we get here, the item wasn't found
    return error('Menu item with ID ${id} not found')
}

// Function to calculate the total cost of an order
fn calculate_order_total(items []MenuItem, order_ids []int) f64 {
    mut total := 0.0
    
    // Add up the price of each ordered item
    for id in order_ids {
        // Try to find the menu item
        item := find_menu_item(items, id) or { 
            println('Warning: Menu item ${id} not found, skipping')
            continue  // Skip this item and continue with next
        }
        total += item.price
    }
    
    return total
}

// Demo: Restaurant ordering system
println('=== Restaurant Demo ===')

// Create our restaurant menu
menu := [
    MenuItem{1, 'Caesar Salad', 'Fresh romaine lettuce with parmesan cheese', 12.99},
    MenuItem{2, 'Grilled Salmon', 'Atlantic salmon with herb seasoning', 24.99},
    MenuItem{3, 'Beef Burger', 'Angus beef patty with cheese and fries', 15.99},
    MenuItem{4, 'Pasta Carbonara', 'Creamy pasta with bacon and eggs', 18.99},
    MenuItem{5, 'Chocolate Cake', 'Rich chocolate cake with vanilla ice cream', 8.99},
]

// Show the menu to the customer
display_menu(menu)

// Simulate a customer's order
println('Customer orders items: 2 (Salmon), 3 (Burger), 5 (Cake)')
customer_order := [2, 3, 5]

// Calculate the total
order_total := calculate_order_total(menu, customer_order)
tax := order_total * 0.08        // 8% sales tax
final_total := order_total + tax

println('--- ORDER SUMMARY ---')
println('Subtotal: $${order_total:.2f}')
println('Tax (8%): $${tax:.2f}')
println('Total: $${final_total:.2f}')
```

#### Array Type System

V arrays are strongly typed and homogeneous (all elements must be the same type):

```v
// Valid arrays
integers := [1, 2, 3, 4, 5]                    // []int
strings := ['hello', 'world', 'from', 'V']     // []string
floats := [3.14, 2.71, 1.41]                   // []f64
booleans := [true, false, true, false]         // []bool

// Arrays of complex types
struct Person {
    name string
    age int
}

people := [
    Person{'Alice', 30},
    Person{'Bob', 25},
    Person{'Charlie', 35},
]

// Arrays of arrays
matrix := [[1, 2, 3], [4, 5, 6], [7, 8, 9]]

// Array of functions
operations := [
    fn (x int, y int) int { return x + y },
    fn (x int, y int) int { return x - y },
    fn (x int, y int) int { return x * y },
]

result1 := operations[0](10, 5)  // Addition: 15
result2 := operations[1](10, 5)  // Subtraction: 5
result3 := operations[2](10, 5)  // Multiplication: 50
```

```v
arr := []int{len: 5, init: -1}
// `arr == [-1, -1, -1, -1, -1]`, arr.cap == 5

// Declare an empty array:
users := []int{}
```

Setting the capacity improves performance of pushing elements to the array
as reallocations can be avoided:

```v
mut numbers := []int{cap: 1000}
println(numbers.len) // 0
// Now appending elements won't reallocate
for i in 0 .. 1000 {
	numbers << i
}
```

> [!NOTE]
> The above code uses a [range `for`](#range-for) statement.

You can initialize the array by accessing the `index` variable which gives
the index as shown here:

```v
count := []int{len: 4, init: index}
assert count == [0, 1, 2, 3]

mut square := []int{len: 6, init: index * index}
// square == [0, 1, 4, 9, 16, 25]
```

#### Array Types

An array can be of these types:

| Types        | Example Definition                   |
|--------------|--------------------------------------|
| Number       | `[]int,[]i64`                        |
| String       | `[]string`                           |
| Rune         | `[]rune`                             |
| Boolean      | `[]bool`                             |
| Array        | `[][]int`                            |
| Struct       | `[]MyStructName`                     |
| Channel      | `[]chan f64`                         |
| Function     | `[]MyFunctionType` `[]fn (int) bool` |
| Interface    | `[]MyInterfaceName`                  |
| Sum Type     | `[]MySumTypeName`                    |
| Generic Type | `[]T`                                |
| Map          | `[]map[string]f64`                   |
| Enum         | `[]MyEnumType`                       |
| Alias        | `[]MyAliasTypeName`                  |
| Thread       | `[]thread int`                       |
| Reference    | `[]&f64`                             |
| Shared       | `[]shared MyStructType`              |
| Option       | `[]?f64`                          |

**Example Code:**

This example uses [Structs](#structs) and [Sum Types](#sum-types) to create an array
which can handle different types (e.g. Points, Lines) of data elements.

```v
struct Point {
	x int
	y int
}

struct Line {
	p1 Point
	p2 Point
}

type ObjectSumType = Line | Point

mut object_list := []ObjectSumType{}
object_list << Point{1, 1}
object_list << Line{
	p1: Point{3, 3}
	p2: Point{4, 4}
}
dump(object_list)
/*
object_list: [ObjectSumType(Point{
    x: 1
    y: 1
}), ObjectSumType(Line{
    p1: Point{
        x: 3
        y: 3
    }
    p2: Point{
        x: 4
        y: 4
    }
})]
*/
```

#### Multidimensional Arrays

Arrays can have more than one dimension.

2d array example:

```v
mut a := [][]int{len: 2, init: []int{len: 3}}
a[0][1] = 2
println(a) // [[0, 2, 0], [0, 0, 0]]
```

3d array example:

```v
mut a := [][][]int{len: 2, init: [][]int{len: 3, init: []int{len: 2}}}
a[0][1][1] = 2
println(a) // [[[0, 0], [0, 2], [0, 0]], [[0, 0], [0, 0], [0, 0]]]
```

#### Array methods

All arrays can be easily printed with `println(arr)` and converted to a string
with `s := arr.str()`.

Copying the data from the array is done with `.clone()`:

```v
nums := [1, 2, 3]
nums_copy := nums.clone()
```

Arrays can be efficiently filtered and mapped with the `.filter()` and
`.map()` methods:

```v
nums := [1, 2, 3, 4, 5, 6]
even := nums.filter(it % 2 == 0)
println(even) // [2, 4, 6]
// filter can accept anonymous functions
even_fn := nums.filter(fn (x int) bool {
	return x % 2 == 0
})
println(even_fn)
```

```v
words := ['hello', 'world']
upper := words.map(it.to_upper())
println(upper) // ['HELLO', 'WORLD']
// map can also accept anonymous functions
upper_fn := words.map(fn (w string) string {
	return w.to_upper()
})
println(upper_fn) // ['HELLO', 'WORLD']
```

`it` is a builtin variable which refers to the element currently being
processed in filter/map methods.

Additionally, `.any()` and `.all()` can be used to conveniently test
for elements that satisfy a condition.

```v
nums := [1, 2, 3]
println(nums.any(it == 2)) // true
println(nums.all(it >= 2)) // false
```

There are further built-in methods for arrays:

* `a.repeat(n)` concatenates the array elements `n` times
* `a.insert(i, val)` inserts a new element `val` at index `i` and
  shifts all following elements to the right
* `a.insert(i, [3, 4, 5])` inserts several elements
* `a.prepend(val)` inserts a value at the beginning, equivalent to `a.insert(0, val)`
* `a.prepend(arr)` inserts elements of array `arr` at the beginning
* `a.trim(new_len)` truncates the length (if `new_length < a.len`, otherwise does nothing)
* `a.clear()` empties the array without changing `cap` (equivalent to `a.trim(0)`)
* `a.delete_many(start, size)` removes `size` consecutive elements from index `start`
  &ndash; triggers reallocation
* `a.delete(index)` equivalent to `a.delete_many(index, 1)`
* `a.delete_last()` removes the last element
* `a.first()` equivalent to `a[0]`
* `a.last()` equivalent to `a[a.len - 1]`
* `a.pop()` removes the last element and returns it
* `a.reverse()` makes a new array with the elements of `a` in reverse order
* `a.reverse_in_place()` reverses the order of elements in `a`
* `a.join(joiner)` concatenates an array of strings into one string
  using `joiner` string as a separator

See all methods of [array](https://modules.vlang.io/index.html#array)

See also [vlib/arrays](https://modules.vlang.io/arrays.html).

##### Sorting Arrays

Sorting arrays of all kinds is very simple and intuitive. Special variables `a` and `b`
are used when providing a custom sorting condition.

```v
mut numbers := [1, 3, 2]
numbers.sort() // 1, 2, 3
numbers.sort(a > b) // 3, 2, 1
```

```v
struct User {
	age  int
	name string
}

mut users := [User{21, 'Bob'}, User{20, 'Zarkon'}, User{25, 'Alice'}]
users.sort(a.age < b.age) // sort by User.age int field
users.sort(a.name > b.name) // reverse sort by User.name string field
```

V also supports custom sorting, through the `sort_with_compare` array method.
Which expects a comparing function which will define the sort order.
Useful for sorting on multiple fields at the same time by custom sorting rules.
The code below sorts the array ascending on `name` and descending `age`.

```v
struct User {
	age  int
	name string
}

mut users := [User{21, 'Bob'}, User{65, 'Bob'}, User{25, 'Alice'}]

custom_sort_fn := fn (a &User, b &User) int {
	// return -1 when a comes before b
	// return 0, when both are in same order
	// return 1 when b comes before a
	if a.name == b.name {
		if a.age < b.age {
			return 1
		}
		if a.age > b.age {
			return -1
		}
		return 0
	}
	if a.name < b.name {
		return -1
	} else if a.name > b.name {
		return 1
	}
	return 0
}
users.sort_with_compare(custom_sort_fn)
```

#### Array Slices

A slice is a part of a parent array. Initially it refers to the elements
between two indices separated by a `..` operator. The right-side index must
be greater than or equal to the left side index.

If a right-side index is absent, it is assumed to be the array length. If a
left-side index is absent, it is assumed to be 0.

```v
nums := [0, 10, 20, 30, 40]
println(nums[1..4]) // [10, 20, 30]
println(nums[..4]) // [0, 10, 20, 30]
println(nums[1..]) // [10, 20, 30, 40]
```

In V slices are arrays themselves (they are not distinct types). As a result
all array operations may be performed on them. E.g. they can be pushed onto an
array of the same type:

```v
array_1 := [3, 5, 4, 7, 6]
mut array_2 := [0, 1]
array_2 << array_1[..3]
println(array_2) // `[0, 1, 3, 5, 4]`
```

A slice is always created with the smallest possible capacity `cap == len` (see
[`cap` above](#array-initialization)) no matter what the capacity or length
of the parent array is. As a result it is immediately reallocated and copied to another
memory location when the size increases thus becoming independent from the
parent array (*copy on grow*). In particular pushing elements to a slice
does not alter the parent:

```v
mut a := [0, 1, 2, 3, 4, 5]

// Create a slice, that reuses the *same memory* as the parent array
// initially, without doing a new allocation:
mut b := unsafe { a[2..4] } // the contents of `b`, reuses the memory, used by the contents of `a`.

b[0] = 7 // Note that `b[0]` and `a[2]` refer to *the same element* in memory.
println(a) // `[0, 1, 7, 3, 4, 5]` - changing `b[0]` above, changed `a[2]` too.

// the content of `b` will get reallocated, to have room for the `9` element:
b << 9
// The content of `b`, is now reallocated, and fully independent from the content of `a`.

println(a) // `[0, 1, 7, 3, 4, 5]` - no change, since the content of `b` was reallocated,
// to a larger block, before the appending.

println(b) // `[7, 3, 9]` - the contents of `b`, after the reallocation, and appending of the `9`.
```

Appending to the parent array, may or may not make it independent from its child slices.
The behaviour depends on the *parent's capacity* and is predictable:

```v
mut a := []int{len: 5, cap: 6, init: 2}
mut b := unsafe { a[1..4] } // the contents of `b` uses part of the same memory, that is used by `a` too

a << 3
// still no reallocation of `a`, since `a.len` still fits in `a.cap`
b[2] = 13 // `a[3]` is modified, through the slice `b`.

a << 4
// the content of `a` has been reallocated now, and is independent from `b` (`cap` was exceeded by `len`)
b[1] = 3 // no change in `a`

println(a) // `[2, 2, 2, 13, 2, 3, 4]`
println(b) // `[2, 3, 13]`
```

You can call .clone() on the slice, if you *do* want to have an independent copy right away:

```v
mut a := [0, 1, 2, 3, 4, 5]
mut b := a[2..4].clone()
b[0] = 7 // Note: `b[0]` is NOT referring to `a[2]`, as it would have been, without the `.clone()`
println(a) // [0, 1, 2, 3, 4, 5]
println(b) // [7, 3]
```

##### Slices with negative indexes

V supports array and string slices with negative indexes.
Negative indexing starts from the end of the array towards the start,
for example `-3` is equal to `array.len - 3`.
Negative slices have a different syntax from normal slices, i.e. you need
to add a `gate` between the array name and the square bracket: `a#[..-3]`.
The `gate` specifies that this is a different type of slice and remember that
the result is "locked" inside the array.
The returned slice is always a valid array, though it may be empty:

```v
a := [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
println(a#[-3..]) // [7, 8, 9]
println(a#[-20..]) // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
println(a#[-20..-8]) // [0, 1]
println(a#[..-3]) // [0, 1, 2, 3, 4, 5, 6]

// empty arrays
println(a#[-20..-10]) // []
println(a#[20..10]) // []
println(a#[20..30]) // []
```

#### Array method chaining

You can chain the calls of array methods like `.filter()` and `.map()` and use
the `it` built-in variable to achieve a classic `map/filter` functional paradigm:

```v
// using filter, map and negatives array slices
files := ['pippo.jpg', '01.bmp', '_v.txt', 'img_02.jpg', 'img_01.JPG']
filtered := files.filter(it#[-4..].to_lower() == '.jpg').map(it.to_upper())
// ['PIPPO.JPG', 'IMG_02.JPG', 'IMG_01.JPG']
```

### Fixed size arrays

V also supports arrays with fixed size. Unlike ordinary arrays, their
length is constant. You cannot append elements to them, nor shrink them.
You can only modify their elements in place.

However, access to the elements of fixed size arrays is more efficient,
they need less memory than ordinary arrays, and unlike ordinary arrays,
their data is on the stack, so you may want to use them as buffers if you
do not want additional heap allocations.

Most methods are defined to work on ordinary arrays, not on fixed size arrays.
You can convert a fixed size array to an ordinary array with slicing:

```v
mut fnums := [3]int{} // fnums is a fixed size array with 3 elements.
fnums[0] = 1
fnums[1] = 10
fnums[2] = 100
println(fnums) // => [1, 10, 100]
println(typeof(fnums).name) // => [3]int

fnums2 := [1, 10, 100]! // short init syntax that does the same (the syntax will probably change)

anums := fnums[..] // same as `anums := fnums[0..fnums.len]`
println(anums) // => [1, 10, 100]
println(typeof(anums).name) // => []int
```

Note that slicing will cause the data of the fixed size array to be copied to
the newly created ordinary array.

### Maps

```v
mut m := map[string]int{} // a map with `string` keys and `int` values
m['one'] = 1
m['two'] = 2
println(m['one']) // "1"
println(m['bad_key']) // "0"
println('bad_key' in m) // Use `in` to detect whether such key exists
println(m.keys()) // ['one', 'two']
m.delete('two')
```

Maps can have keys of type string, rune, integer, float or voidptr.

The whole map can be initialized using this short syntax:

```v
numbers := {
	'one': 1
	'two': 2
}
println(numbers)
```

If a key is not found, a zero value is returned by default:

```v
sm := {
	'abc': 'xyz'
}
val := sm['bad_key']
println(val) // ''
```

```v
intm := {
	1: 1234
	2: 5678
}
s := intm[3]
println(s) // 0
```

It's also possible to use an `or {}` block to handle missing keys:

```v
mm := map[string]int{}
val := mm['bad_key'] or { panic('key not found') }
```

You can also check, if a key is present, and get its value, if it was present, in one go:

```v
m := {
	'abc': 'def'
}
if v := m['abc'] {
	println('the map value for that key is: ${v}')
}
```

The same option check applies to arrays:

```v
arr := [1, 2, 3]
large_index := 999
val := arr[large_index] or { panic('out of bounds') }
println(val)
// you can also do this, if you want to *propagate* the access error:
val2 := arr[333]!
println(val2)
```

V also supports nested maps:

```v
mut m := map[string]map[string]int{}
m['greet'] = {
	'Hello': 1
}
m['place'] = {
	'world': 2
}
m['code']['orange'] = 123
print(m)
```

Maps are ordered by insertion, like dictionaries in Python. The order is a
guaranteed language feature. This may change in the future.

See all methods of
[map](https://modules.vlang.io/builtin.html#map)
and
[maps](https://modules.vlang.io/maps.html).

### Map update syntax

As with structs, V lets you initialise a map with an update applied on top of
another map:

```v
const base_map = {
	'a': 4
	'b': 5
}

foo := {
	...base_map
	'b': 88
	'c': 99
}

println(foo) // {'a': 4, 'b': 88, 'c': 99}
```

This is functionally equivalent to cloning the map and updating it, except that
you don't have to declare a mutable variable:

```v failcompile
// same as above (except mutable)
mut foo := base_map.clone()
foo['b'] = 88
foo['c'] = 99
```

## Advanced Maps and JSON Examples

V provides excellent support for maps with various key-value types and JSON serialization. Here are comprehensive examples:

### String to String Maps

```v
import json
import os

fn main() {
    // Basic string to string map
    mut config := map[string]string{}
    config['database_host'] = 'localhost'
    config['database_port'] = '5432'
    config['database_name'] = 'myapp'
    config['api_key'] = 'secret-key-123'
    config['environment'] = 'development'
    
    println('Configuration:')
    for key, value in config {
        println('  ${key}: ${value}')
    }
    
    // Convert map to JSON
    json_config := json.encode(config)
    println('\nJSON Configuration:')
    println(json_config)
    
    // Save to file
    os.write_file('config.json', json_config) or {
        eprintln('Failed to save config: ${err}')
        return
    }
    
    // Load from file
    loaded_json := os.read_file('config.json') or {
        eprintln('Failed to read config: ${err}')
        return
    }
    
    loaded_config := json.decode(map[string]string, loaded_json) or {
        eprintln('Failed to decode config: ${err}')
        return
    }
    
    println('\nLoaded Configuration:')
    for key, value in loaded_config {
        println('  ${key}: ${value}')
    }
}
```

### String to Number Maps

```v
import json
import os

fn main() {
    // String to int map (counters, statistics)
    mut page_views := map[string]int{}
    page_views['/home'] = 1250
    page_views['/about'] = 340
    page_views['/products'] = 890
    page_views['/contact'] = 180
    page_views['/blog'] = 620
    
    println('Page Views:')
    mut total_views := 0
    for page, views in page_views {
        println('  ${page}: ${views}')
        total_views += views
    }
    println('  Total: ${total_views}')
    
    // String to float map (prices, ratings)
    mut product_prices := map[string]f64{}
    product_prices['laptop'] = 1299.99
    product_prices['mouse'] = 29.95
    product_prices['keyboard'] = 89.50
    product_prices['monitor'] = 299.00
    product_prices['webcam'] = 79.99
    
    println('\nProduct Prices:')
    mut total_value := 0.0
    for product, price in product_prices {
        println('  ${product}: $${price:.2f}')
        total_value += price
    }
    println('  Total Value: $${total_value:.2f}')
    
    // Convert to JSON and save
    views_json := json.encode(page_views)
    prices_json := json.encode(product_prices)
    
    // Create combined data structure
    combined_data := {
        'page_views': views_json
        'product_prices': prices_json
    }
    
    combined_json := json.encode(combined_data)
    os.write_file('analytics.json', combined_json) or {
        eprintln('Failed to save analytics: ${err}')
        return
    }
    
    println('\nSaved analytics data to analytics.json')
}
```

### String to Struct Maps

```v
import json
import os

struct User {
    id         int    [json: 'id']
    name       string [json: 'name']
    email      string [json: 'email']
    age        int    [json: 'age']
    active     bool   [json: 'active']
    created_at string [json: 'created_at']
}

struct Product {
    id          int      [json: 'id']
    name        string   [json: 'name']
    price       f64      [json: 'price']
    category    string   [json: 'category']
    in_stock    bool     [json: 'in_stock']
    tags        []string [json: 'tags']
    description string   [json: 'description']
}

struct UserDatabase {
    users map[string]User [json: 'users']
}

struct ProductCatalog {
    products map[string]Product [json: 'products']
}

fn main() {
    // String to User struct map
    mut users := map[string]User{}
    
    users['alice'] = User{
        id:         1
        name:       'Alice Johnson'
        email:      'alice@example.com'
        age:        28
        active:     true
        created_at: '2023-01-15T10:30:00Z'
    }
    
    users['bob'] = User{
        id:         2
        name:       'Bob Smith'
        email:      'bob@example.com'
        age:        34
        active:     false
        created_at: '2023-02-20T14:45:00Z'
    }
    
    users['carol'] = User{
        id:         3
        name:       'Carol Davis'
        email:      'carol@example.com'
        age:        31
        active:     true
        created_at: '2023-03-10T09:15:00Z'
    }
    
    println('Users Database:')
    for username, user in users {
        status := if user.active { 'Active' } else { 'Inactive' }
        println('  ${username}: ${user.name} (${user.age}) - ${status}')
    }
    
    // String to Product struct map
    mut products := map[string]Product{}
    
    products['laptop-pro'] = Product{
        id:          101
        name:        'Professional Laptop'
        price:       1299.99
        category:    'Electronics'
        in_stock:    true
        tags:        ['laptop', 'professional', 'high-performance']
        description: 'High-performance laptop for professionals'
    }
    
    products['wireless-mouse'] = Product{
        id:          102
        name:        'Wireless Mouse'
        price:       29.99
        category:    'Accessories'
        in_stock:    true
        tags:        ['mouse', 'wireless', 'ergonomic']
        description: 'Ergonomic wireless mouse'
    }
    
    products['gaming-keyboard'] = Product{
        id:          103
        name:        'Gaming Keyboard'
        price:       149.99
        category:    'Gaming'
        in_stock:    false
        tags:        ['keyboard', 'gaming', 'mechanical']
        description: 'Mechanical gaming keyboard with RGB lighting'
    }
    
    println('\nProduct Catalog:')
    for sku, product in products {
        stock_status := if product.in_stock { 'In Stock' } else { 'Out of Stock' }
        println('  ${sku}: ${product.name} - $${product.price:.2f} (${stock_status})')
    }
    
    // Create wrapper structs for JSON serialization
    user_db := UserDatabase{
        users: users
    }
    
    product_catalog := ProductCatalog{
        products: products
    }
    
    // Convert to JSON
    users_json := json.encode_pretty(user_db)
    products_json := json.encode_pretty(product_catalog)
    
    // Save to files
    os.write_file('users_database.json', users_json) or {
        eprintln('Failed to save users: ${err}')
        return
    }
    
    os.write_file('product_catalog.json', products_json) or {
        eprintln('Failed to save products: ${err}')
        return
    }
    
    println('\nSaved data to JSON files')
    
    // Load back from JSON
    loaded_users_json := os.read_file('users_database.json') or {
        eprintln('Failed to read users file: ${err}')
        return
    }
    
    loaded_user_db := json.decode(UserDatabase, loaded_users_json) or {
        eprintln('Failed to decode users JSON: ${err}')
        return
    }
    
    println('\nLoaded Users from JSON:')
    for username, user in loaded_user_db.users {
        println('  ${username}: ${user.name} <${user.email}>')
    }
}
```

### String to Map (Nested Maps)

```v
import json
import os

struct ServerStats {
    cpu_usage    f64 [json: 'cpu_usage']
    memory_usage f64 [json: 'memory_usage']
    disk_usage   f64 [json: 'disk_usage']
    uptime       int [json: 'uptime']
}

fn main() {
    // Nested string maps (configuration hierarchy)
    mut config := map[string]map[string]string{}
    
    // Database configuration
    config['database'] = {
        'host':     'localhost'
        'port':     '5432'
        'name':     'myapp_db'
        'username': 'admin'
        'password': 'secret123'
    }
    
    // Redis configuration
    config['redis'] = {
        'host':     'localhost'
        'port':     '6379'
        'password': 'redis_secret'
        'database': '0'
    }
    
    // API configuration
    config['api'] = {
        'host':         'localhost'
        'port':         '8080'
        'version':      'v1'
        'rate_limit':   '1000'
        'cors_origin':  '*'
    }
    
    println('Configuration Hierarchy:')
    for section, settings in config {
        println('  [${section}]')
        for key, value in settings {
            println('    ${key}: ${value}')
        }
        println('')
    }
    
    // String to number maps (nested statistics)
    mut server_metrics := map[string]map[string]f64{}
    
    server_metrics['web-server-01'] = {
        'cpu_usage':    45.2
        'memory_usage': 67.8
        'disk_usage':   23.1
        'network_in':   1024.5
        'network_out':  2048.7
    }
    
    server_metrics['web-server-02'] = {
        'cpu_usage':    32.1
        'memory_usage': 54.3
        'disk_usage':   18.9
        'network_in':   876.3
        'network_out':  1543.2
    }
    
    server_metrics['database-server'] = {
        'cpu_usage':    78.9
        'memory_usage': 89.5
        'disk_usage':   91.2
        'network_in':   3456.7
        'network_out':  4321.8
    }
    
    println('Server Metrics:')
    for server, metrics in server_metrics {
        println('  ${server}:')
        for metric, value in metrics {
            println('    ${metric}: ${value:.1f}%')
        }
        println('')
    }
    
    // Complex nested structure: String to Map[String]Array
    mut user_permissions := map[string]map[string][]string{}
    
    user_permissions['admin'] = {
        'users':    ['create', 'read', 'update', 'delete']
        'products': ['create', 'read', 'update', 'delete']
        'orders':   ['create', 'read', 'update', 'delete', 'refund']
        'reports':  ['read', 'export']
    }
    
    user_permissions['editor'] = {
        'users':    ['read']
        'products': ['create', 'read', 'update']
        'orders':   ['read', 'update']
        'reports':  ['read']
    }
    
    user_permissions['viewer'] = {
        'users':    ['read']
        'products': ['read']
        'orders':   ['read']
        'reports':  ['read']
    }
    
    println('User Permissions:')
    for role, permissions in user_permissions {
        println('  ${role}:')
        for resource, actions in permissions {
            println('    ${resource}: ${actions}')
        }
        println('')
    }
    
    // Convert all to JSON
    config_json := json.encode_pretty(config)
    metrics_json := json.encode_pretty(server_metrics)
    permissions_json := json.encode_pretty(user_permissions)
    
    // Save to files
    os.write_file('app_config.json', config_json) or {
        eprintln('Failed to save config: ${err}')
        return
    }
    
    os.write_file('server_metrics.json', metrics_json) or {
        eprintln('Failed to save metrics: ${err}')
        return
    }
    
    os.write_file('user_permissions.json', permissions_json) or {
        eprintln('Failed to save permissions: ${err}')
        return
    }
    
    println('All data saved to JSON files')
    
    // Load and verify one of the files
    loaded_config_json := os.read_file('app_config.json') or {
        eprintln('Failed to read config file: ${err}')
        return
    }
    
    loaded_config := json.decode(map[string]map[string]string, loaded_config_json) or {
        eprintln('Failed to decode config JSON: ${err}')
        return
    }
    
    println('\nVerification - Loaded Database Config:')
    if db_config := loaded_config['database'] {
        for key, value in db_config {
            println('  ${key}: ${value}')
        }
    }
}
```

### Integer and Other Key Types Maps

```v
import json
import os

struct GameScore {
    player_name string [json: 'player_name']
    score       int    [json: 'score']
    level       int    [json: 'level']
    timestamp   string [json: 'timestamp']
}

struct APIEndpoint {
    path        string   [json: 'path']
    method      string   [json: 'method']
    description string   [json: 'description']
    parameters  []string [json: 'parameters']
}

fn main() {
    // Integer keys to string values (status codes)
    mut http_status_codes := map[int]string{}
    http_status_codes[200] = 'OK'
    http_status_codes[201] = 'Created'
    http_status_codes[400] = 'Bad Request'
    http_status_codes[401] = 'Unauthorized'
    http_status_codes[403] = 'Forbidden'
    http_status_codes[404] = 'Not Found'
    http_status_codes[500] = 'Internal Server Error'
    
    println('HTTP Status Codes:')
    for code, message in http_status_codes {
        println('  ${code}: ${message}')
    }
    
    // Integer keys to struct values (game leaderboard)
    mut leaderboard := map[int]GameScore{}
    leaderboard[1] = GameScore{
        player_name: 'Alice'
        score:       15420
        level:       12
        timestamp:   '2023-09-01T15:30:00Z'
    }
    leaderboard[2] = GameScore{
        player_name: 'Bob'
        score:       14890
        level:       11
        timestamp:   '2023-09-01T14:45:00Z'
    }
    leaderboard[3] = GameScore{
        player_name: 'Carol'
        score:       13750
        level:       10
        timestamp:   '2023-09-01T13:20:00Z'
    }
    
    println('\nGame Leaderboard:')
    for rank, score in leaderboard {
        println('  #${rank}: ${score.player_name} - ${score.score} points (Level ${score.level})')
    }
    
    // Float keys to string values (price ranges)
    mut price_categories := map[f64]string{}
    price_categories[0.0] = 'Free'
    price_categories[0.99] = 'Budget'
    price_categories[9.99] = 'Standard'
    price_categories[49.99] = 'Premium'
    price_categories[99.99] = 'Professional'
    price_categories[199.99] = 'Enterprise'
    
    println('\nPrice Categories:')
    for price, category in price_categories {
        println('  \$${price:.2f}: ${category}')
    }
    
    // Boolean keys (feature flags)
    mut feature_descriptions := map[bool]string{}
    feature_descriptions[true] = 'Feature is enabled'
    feature_descriptions[false] = 'Feature is disabled'
    
    mut app_features := map[string]bool{}
    app_features['dark_mode'] = true
    app_features['notifications'] = true
    app_features['analytics'] = false
    app_features['beta_features'] = false
    
    println('\nApplication Features:')
    for feature, enabled in app_features {
        status := feature_descriptions[enabled]
        println('  ${feature}: ${status}')
    }
    
    // Rune keys (character mappings)
    mut char_codes := map[rune]int{}
    char_codes[`A`] = 65
    char_codes[`B`] = 66
    char_codes[`C`] = 67
    char_codes[`a`] = 97
    char_codes[`b`] = 98
    char_codes[`c`] = 99
    
    println('\nCharacter ASCII Codes:')
    for char, code in char_codes {
        println('  ${char}: ${code}')
    }
    
    // Complex example: Multi-level caching system
    mut cache_stats := map[string]map[string]map[string]int{}
    
    cache_stats['redis'] = {
        'hits': {
            'today':     1250
            'yesterday': 1180
            'this_week': 8750
        }
        'misses': {
            'today':     85
            'yesterday': 92
            'this_week': 640
        }
    }
    
    cache_stats['memcached'] = {
        'hits': {
            'today':     890
            'yesterday': 920
            'this_week': 6400
        }
        'misses': {
            'today':     45
            'yesterday': 38
            'this_week': 320
        }
    }
    
    println('\nCache Statistics:')
    for cache_type, stats in cache_stats {
        println('  ${cache_type}:')
        for stat_type, periods in stats {
            println('    ${stat_type}:')
            for period, count in periods {
                println('      ${period}: ${count}')
            }
        }
        println('')
    }
    
    // Save complex data structures to JSON
    // Note: Maps with non-string keys need special handling for JSON
    
    // Convert leaderboard to JSON-friendly format
    mut leaderboard_json := map[string]GameScore{}
    for rank, score in leaderboard {
        leaderboard_json[rank.str()] = score
    }
    
    // Convert status codes to JSON-friendly format
    mut status_codes_json := map[string]string{}
    for code, message in http_status_codes {
        status_codes_json[code.str()] = message
    }
    
    // Create combined JSON structure
    combined_data := {
        'leaderboard':    json.encode(leaderboard_json)
        'status_codes':   json.encode(status_codes_json)
        'app_features':   json.encode(app_features)
        'cache_stats':    json.encode(cache_stats)
    }
    
    final_json := json.encode_pretty(combined_data)
    os.write_file('complex_maps.json', final_json) or {
        eprintln('Failed to save complex maps: ${err}')
        return
    }
    
    println('Complex map data saved to complex_maps.json')
    
    // Load and verify
    loaded_data := os.read_file('complex_maps.json') or {
        eprintln('Failed to read file: ${err}')
        return
    }
    
    parsed_data := json.decode(map[string]string, loaded_data) or {
        eprintln('Failed to decode JSON: ${err}')
        return
    }
    
    // Parse nested JSON
    if features_json := parsed_data['app_features'] {
        loaded_features := json.decode(map[string]bool, features_json) or {
            eprintln('Failed to decode features: ${err}')
            return
        }
        
        println('\nVerification - Loaded App Features:')
        for feature, enabled in loaded_features {
            status := if enabled { 'Enabled' } else { 'Disabled' }
            println('  ${feature}: ${status}')
        }
    }
}
```

### Real-World Map Applications

```v
import json
import os
import time

struct DatabaseConnection {
    host     string [json: 'host']
    port     int    [json: 'port']
    database string [json: 'database']
    username string [json: 'username']
    password string [json: 'password']
    timeout  int    [json: 'timeout']
}

struct APIRate {
    requests_per_minute int [json: 'requests_per_minute']
    burst_limit         int [json: 'burst_limit']
}

struct ServiceConfig {
    name            string             [json: 'name']
    version         string             [json: 'version']
    databases       map[string]DatabaseConnection [json: 'databases']
    api_rates       map[string]APIRate [json: 'api_rates']
    feature_flags   map[string]bool    [json: 'feature_flags']
    environment_vars map[string]string [json: 'environment_vars']
}

fn create_service_config() ServiceConfig {
    return ServiceConfig{
        name:    'UserService'
        version: '1.2.3'
        databases: {
            'primary': DatabaseConnection{
                host:     'db-primary.internal'
                port:     5432
                database: 'users'
                username: 'app_user'
                password: 'secure_password'
                timeout:  30
            }
            'analytics': DatabaseConnection{
                host:     'db-analytics.internal'
                port:     5432
                database: 'analytics'
                username: 'analytics_user'
                password: 'analytics_password'
                timeout:  60
            }
            'cache': DatabaseConnection{
                host:     'redis.internal'
                port:     6379
                database: 'cache'
                username: ''
                password: 'redis_password'
                timeout:  5
            }
        }
        api_rates: {
            'public':      APIRate{requests_per_minute: 60, burst_limit: 10}
            'authenticated': APIRate{requests_per_minute: 300, burst_limit: 50}
            'premium':     APIRate{requests_per_minute: 1000, burst_limit: 100}
            'admin':       APIRate{requests_per_minute: 5000, burst_limit: 500}
        }
        feature_flags: {
            'new_registration_flow': true
            'email_verification':    true
            'two_factor_auth':       false
            'social_login':          true
            'password_reset':        true
            'account_deletion':      false
        }
        environment_vars: {
            'LOG_LEVEL':      'INFO'
            'MAX_CONNECTIONS': '100'
            'JWT_SECRET':      'your-jwt-secret-key'
            'SMTP_HOST':       'smtp.internal'
            'SMTP_PORT':       '587'
            'CORS_ORIGIN':     'https://app.example.com'
        }
    }
}

fn validate_config(config ServiceConfig) []string {
    mut errors := []string{}
    
    // Validate databases
    for db_name, db_config in config.databases {
        if db_config.host == '' {
            errors << 'Database ${db_name}: host cannot be empty'
        }
        if db_config.port <= 0 || db_config.port > 65535 {
            errors << 'Database ${db_name}: invalid port ${db_config.port}'
        }
        if db_config.database == '' {
            errors << 'Database ${db_name}: database name cannot be empty'
        }
    }
    
    // Validate API rates
    for rate_name, rate_config in config.api_rates {
        if rate_config.requests_per_minute <= 0 {
            errors << 'API rate ${rate_name}: requests_per_minute must be positive'
        }
        if rate_config.burst_limit <= 0 {
            errors << 'API rate ${rate_name}: burst_limit must be positive'
        }
    }
    
    // Validate required environment variables
    required_env_vars := ['LOG_LEVEL', 'MAX_CONNECTIONS', 'JWT_SECRET']
    for required_var in required_env_vars {
        if config.environment_vars[required_var] == '' {
            errors << 'Environment variable ${required_var} is required but not set'
        }
    }
    
    return errors
}

fn analyze_config(config ServiceConfig) {
    println('=== Service Configuration Analysis ===')
    println('Service: ${config.name} v${config.version}')
    
    // Database analysis
    println('\nDatabase Connections:')
    for name, db in config.databases {
        println('  ${name}: ${db.host}:${db.port}/${db.database} (timeout: ${db.timeout}s)')
    }
    
    // API rate analysis
    println('\nAPI Rate Limits:')
    for tier, rate in config.api_rates {
        println('  ${tier}: ${rate.requests_per_minute}/min (burst: ${rate.burst_limit})')
    }
    
    // Feature flags analysis
    enabled_features := config.feature_flags.keys().filter(config.feature_flags[it])
    disabled_features := config.feature_flags.keys().filter(!config.feature_flags[it])
    
    println('\nFeature Flags:')
    println('  Enabled (${enabled_features.len}): ${enabled_features}')
    println('  Disabled (${disabled_features.len}): ${disabled_features}')
    
    // Environment variables
    println('\nEnvironment Variables: ${config.environment_vars.len()} configured')
    sensitive_vars := ['PASSWORD', 'SECRET', 'KEY', 'TOKEN']
    for var_name, var_value in config.environment_vars {
        is_sensitive := sensitive_vars.any(var_name.to_upper().contains(it))
        display_value := if is_sensitive { '[HIDDEN]' } else { var_value }
        println('  ${var_name}: ${display_value}')
    }
}

fn save_and_load_config() ! {
    // Create configuration
    config := create_service_config()
    
    // Validate configuration
    validation_errors := validate_config(config)
    if validation_errors.len > 0 {
        println('Configuration validation failed:')
        for error in validation_errors {
            println('  - ${error}')
        }
        return error('Invalid configuration')
    }
    
    println('Configuration validation passed ✓')
    
    // Analyze configuration
    analyze_config(config)
    
    // Save to JSON
    config_json := json.encode_pretty(config)
    os.write_file('service_config.json', config_json) or {
        return error('Failed to save configuration: ${err}')
    }
    
    println('\nConfiguration saved to service_config.json')
    
    // Load configuration back
    loaded_json := os.read_file('service_config.json') or {
        return error('Failed to read configuration file: ${err}')
    }
    
    loaded_config := json.decode(ServiceConfig, loaded_json) or {
        return error('Failed to decode configuration JSON: ${err}')
    }
    
    // Verify loaded configuration
    println('\n=== Loaded Configuration Verification ===')
    println('Service: ${loaded_config.name} v${loaded_config.version}')
    println('Databases configured: ${loaded_config.databases.len()}')
    println('API rate tiers: ${loaded_config.api_rates.len()}')
    println('Feature flags: ${loaded_config.feature_flags.len()}')
    println('Environment variables: ${loaded_config.environment_vars.len()}')
    
    // Test specific configuration access
    if primary_db := loaded_config.databases['primary'] {
        println('\nPrimary database configuration loaded successfully:')
        println('  Host: ${primary_db.host}')
        println('  Database: ${primary_db.database}')
    }
    
    if public_rate := loaded_config.api_rates['public'] {
        println('\nPublic API rate limit: ${public_rate.requests_per_minute}/min')
    }
}

fn main() {
    save_and_load_config() or {
        eprintln('Error: ${err}')
        exit(1)
    }
}
```

## Module imports

For information about creating a module, see [Modules](#modules).

Modules can be imported using the `import` keyword:

```v
import os

fn main() {
    // read text from stdin
    name := os.input('Enter your name: ')
    println('Hello, ${name}!')
}
```

## Console Input and Output

V provides several ways to handle console input and output through the `os` module:

### Basic Input Examples

```v
import os

fn main() {
    // Simple string input
    name := os.input('Enter your name: ')
    println('Hello, ${name}!')
    
    // Input with validation
    mut age_str := ''
    mut age := 0
    for {
        age_str = os.input('Enter your age: ')
        age = age_str.int()
        if age > 0 {
            break
        }
        println('Please enter a valid age.')
    }
    println('You are ${age} years old.')
}
```

### Reading Different Data Types

```v
import os
import strconv

fn main() {
    // Reading integers
    num_str := os.input('Enter a number: ')
    num := strconv.atoi(num_str) or {
        eprintln('Invalid number: ${err}')
        return
    }
    println('Number: ${num}')
    
    // Reading floats
    float_str := os.input('Enter a decimal: ')
    decimal := strconv.atof64(float_str) or {
        eprintln('Invalid decimal: ${err}')
        return
    }
    println('Decimal: ${decimal}')
    
    // Reading yes/no
    response := os.input('Continue? (y/n): ').to_lower()
    if response in ['y', 'yes'] {
        println('Continuing...')
    } else {
        println('Stopping.')
    }
}
```

### Interactive Menu System

```v
import os

fn show_menu() {
    println('\n=== Main Menu ===')
    println('1. View items')
    println('2. Add item') 
    println('3. Remove item')
    println('4. Exit')
}

fn main() {
    mut items := ['apple', 'banana', 'orange']
    
    for {
        show_menu()
        choice := os.input('Enter your choice: ')
        
        match choice {
            '1' {
                println('\nCurrent items:')
                for i, item in items {
                    println('${i + 1}. ${item}')
                }
            }
            '2' {
                new_item := os.input('Enter item to add: ')
                items << new_item
                println('Added: ${new_item}')
            }
            '3' {
                if items.len == 0 {
                    println('No items to remove.')
                    continue
                }
                println('\nCurrent items:')
                for i, item in items {
                    println('${i + 1}. ${item}')
                }
                index_str := os.input('Enter item number to remove: ')
                index := index_str.int() - 1
                if index >= 0 && index < items.len {
                    removed := items[index]
                    items.delete(index)
                    println('Removed: ${removed}')
                } else {
                    println('Invalid item number.')
                }
            }
            '4' {
                println('Goodbye!')
                break
            }
            else {
                println('Invalid choice. Please try again.')
            }
        }
    }
}
```

### Reading from Standard Input (stdin)

```v
import os
import io

fn main() {
    // Reading line by line from stdin
    println('Enter multiple lines of text (Ctrl+D to end):')
    mut lines := []string{}
    
    for {
        line := os.input('') or {
            break  // EOF reached
        }
        lines << line
    }
    
    println('\nYou entered:')
    for i, line in lines {
        println('${i + 1}: ${line}')
    }
}
```

### Password Input (Hidden)

```v
import os
import term

fn get_password(prompt string) string {
    print(prompt)
    // Hide cursor and input
    term.hide_cursor()
    defer { term.show_cursor() }
    
    mut password := ''
    for {
        ch := term.get_char() or { break }
        if ch == `\n` || ch == `\r` {
            break
        } else if ch == 127 || ch == 8 { // backspace
            if password.len > 0 {
                password = password[..password.len - 1]
                print('\b \b')
            }
        } else if ch >= 32 && ch < 127 {
            password += ch.ascii_str()
            print('*')
        }
    }
    println('')
    return password
}

fn main() {
    username := os.input('Username: ')
    password := get_password('Password: ')
    
    println('Login attempt for user: ${username}')
    // Don't print the actual password for security
    println('Password length: ${password.len} characters')
}
```

## File Input and Output

V provides comprehensive file I/O operations through the `os` module:

### Basic File Operations

```v
import os

fn main() {
    // Writing to a file
    content := 'Hello, World!\nThis is V programming language.'
    os.write_file('example.txt', content) or {
        eprintln('Failed to write file: ${err}')
        return
    }
    
    // Reading entire file
    file_content := os.read_file('example.txt') or {
        eprintln('Failed to read file: ${err}')
        return
    }
    println('File content:')
    println(file_content)
    
    // Check if file exists
    if os.exists('example.txt') {
        println('File exists!')
        
        // Get file size
        file_info := os.stat('example.txt') or {
            eprintln('Failed to get file info: ${err}')
            return
        }
        println('File size: ${file_info.size} bytes')
    }
}
```

## SQLite: Secure CRUD with parameterized queries

This example uses V's built-in sqlite module with parameterized queries to prevent SQL injection. It demonstrates create/read/update/delete and basic best practices.

Prerequisites on macOS with Homebrew: `brew install sqlite` (provides libsqlite3).

```v
import db.sqlite

struct User {
    id         int
    name       string
    email      string
    created_at string
}

fn main() ! {
    // 1) Connect and configure
    mut db := sqlite.connect('example.db')!
    defer {
        db.close() or { eprintln('close db: ${err}') }
    }
    _ = db.busy_timeout(3000) // avoid immediate "database is locked" errors

    // 2) Create table (DDL)
    db.exec('''
        CREATE TABLE IF NOT EXISTS users (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            name        TEXT    NOT NULL,
            email       TEXT    NOT NULL UNIQUE,
            created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
        )
    ''') or { return err }

    // 3) CREATE — parameterized INSERT
    db.exec_param_many('INSERT INTO users(name, email) VALUES(?, ?)', ['Alice', 'alice@example.com']) or {
        return err
    }
    last_id := db.last_insert_rowid()
    println('Inserted user id: ${last_id}')

    // 4) READ — parameterized SELECT
    rows := db.exec_param('SELECT id, name, email, created_at FROM users WHERE email = ?', 'alice@example.com') or {
        return err
    }
    if rows.len > 0 {
        r := rows[0]
        user := User{
            id: r.vals[0].int()
            name: r.vals[1]
            email: r.vals[2]
            created_at: r.vals[3]
        }
        println('Loaded: ${user}')
    }

    // 5) UPDATE — parameterized UPDATE
    db.exec_param_many('UPDATE users SET name = ? WHERE id = ?', ['Alice A.', last_id.str()]) or {
        return err
    }
    println('Updated rows: ${db.get_affected_rows_count()}')

    // 6) DELETE — parameterized DELETE
    db.exec_param_many('DELETE FROM users WHERE id = ?', [last_id.str()]) or {
        return err
    }
    println('Deleted rows: ${db.get_affected_rows_count()}')
}
```

Notes:

- Always use `?` placeholders with `exec_param`/`exec_param_many` to avoid injection.
- Wrap batches in transactions (`db.begin`, `db.commit`) for speed and atomicity.
- Use `busy_timeout`, `journal_mode`, and `synchronization_mode` for workload tuning.

### Reading Files Line by Line

```v
import os

fn main() {
    filename := 'data.txt'
    
    // Create sample file with multiple lines
    lines := ['First line', 'Second line', 'Third line', 'Fourth line']
    os.write_file(filename, lines.join('\n')) or {
        eprintln('Failed to create file: ${err}')
        return
    }
    
    // Read file line by line
    content := os.read_file(filename) or {
        eprintln('Failed to read file: ${err}')
        return
    }
    
    file_lines := content.split_into_lines()
    println('Reading line by line:')
    for i, line in file_lines {
        println('Line ${i + 1}: ${line}')
    }
}
```

### Working with CSV Files

```v
import os

struct Person {
    name string
    age  int
    city string
}

fn main() {
    // Create sample CSV data
    people := [
        Person{'Alice', 30, 'New York'},
        Person{'Bob', 25, 'London'},
        Person{'Charlie', 35, 'Tokyo'},
    ]
    
    // Write to CSV
    mut csv_content := 'Name,Age,City\n'
    for person in people {
        csv_content += '${person.name},${person.age},${person.city}\n'
    }
    
    os.write_file('people.csv', csv_content) or {
        eprintln('Failed to write CSV: ${err}')
        return
    }
    
    // Read from CSV
    file_content := os.read_file('people.csv') or {
        eprintln('Failed to read CSV: ${err}')
        return
    }
    
    lines := file_content.split_into_lines()
    println('CSV Data:')
    for i, line in lines {
        if i == 0 {
            println('Headers: ${line}')
        } else if line.trim_space() != '' {
            fields := line.split(',')
            if fields.len >= 3 {
                println('Person: ${fields[0]}, Age: ${fields[1]}, City: ${fields[2]}')
            }
        }
    }
}
```

### File and Directory Management

```v
import os

fn main() {
    // Create directory
    dir_name := 'test_directory'
    os.mkdir_all(dir_name) or {
        eprintln('Failed to create directory: ${err}')
        return
    }
    println('Directory created: ${dir_name}')
    
    // Create files in directory
    for i in 1..4 {
        filename := '${dir_name}/file${i}.txt'
        content := 'This is file number ${i}'
        os.write_file(filename, content) or {
            eprintln('Failed to create ${filename}: ${err}')
            continue
        }
    }
    
    // List directory contents
    files := os.ls(dir_name) or {
        eprintln('Failed to list directory: ${err}')
        return
    }
    println('\nDirectory contents:')
    for file in files {
        full_path := '${dir_name}/${file}'
        if os.is_file(full_path) {
            size := os.file_size(full_path)
            println('File: ${file} (${size} bytes)')
        } else if os.is_dir(full_path) {
            println('Directory: ${file}')
        }
    }
    
    // Copy a file
    os.cp('${dir_name}/file1.txt', '${dir_name}/file1_copy.txt') or {
        eprintln('Failed to copy file: ${err}')
    }
    
    // Move/rename a file
    os.mv('${dir_name}/file2.txt', '${dir_name}/renamed_file.txt') or {
        eprintln('Failed to move file: ${err}')
    }
    
    // Clean up - remove directory and all contents
    // os.rmdir_all(dir_name) or {
    //     eprintln('Failed to remove directory: ${err}')
    // }
}
```

### Binary File Operations

```v
import os

fn main() {
    // Write binary data
    data := [u8(0xFF), 0xFE, 0xFD, 0xFC, 0xFB]
    os.write_file_array('binary_file.bin', data) or {
        eprintln('Failed to write binary file: ${err}')
        return
    }
    
    // Read binary data
    binary_data := os.read_bytes('binary_file.bin') or {
        eprintln('Failed to read binary file: ${err}')
        return
    }
    
    println('Binary data:')
    for i, byte_val in binary_data {
        println('Byte ${i}: 0x${byte_val:02X}')
    }
    
    // Append to file
    additional_data := [u8(0xFA), 0xF9, 0xF8]
    mut file := os.open_append('binary_file.bin') or {
        eprintln('Failed to open file for append: ${err}')
        return
    }
    file.write(additional_data) or {
        eprintln('Failed to append data: ${err}')
    }
    file.close()
    
    // Read the updated file
    updated_data := os.read_bytes('binary_file.bin') or {
        eprintln('Failed to read updated file: ${err}')
        return
    }
    println('\nUpdated binary data (${updated_data.len} bytes):')
    for i, byte_val in updated_data {
        println('Byte ${i}: 0x${byte_val:02X}')
    }
}
```

This program can use any public definitions from the `os` module, such

## Process Management and Subprocesses

V provides powerful process management capabilities through the `os` module for executing external commands and managing subprocesses:

### Basic Command Execution

```v
import os

fn main() {
    // Execute a simple command
    result := os.execute('ls -la')
    if result.exit_code == 0 {
        println('Command output:')
        println(result.output)
    } else {
        eprintln('Command failed with exit code: ${result.exit_code}')
    }
    
    // Execute with error handling
    result2 := os.execute('nonexistent_command')
    println('Exit code: ${result2.exit_code}')
    if result2.output.len > 0 {
        println('Output: ${result2.output}')
    }
}
```

### Advanced Process Execution

```v
import os

fn main() {
    // Execute command with arguments
    cmd := ['python3', '-c', 'print("Hello from Python!")']
    result := os.execute(cmd.join(' '))
    println('Python output: ${result.output.trim_space()}')
    
    // Execute and capture both stdout and stderr
    result2 := os.execute('ls /nonexistent/path 2>&1')
    println('Command result:')
    println('Exit code: ${result2.exit_code}')
    println('Output: ${result2.output}')
    
    // Execute with timeout (using system utilities)
    timeout_result := os.execute('timeout 5s sleep 10')
    if timeout_result.exit_code == 124 {
        println('Command timed out')
    } else {
        println('Command completed normally')
    }
}
```

### Process Management with os.Process

```v
import os
import time

fn main() {
    // Start a long-running process
    mut proc := os.new_process('ping')
    proc.set_args(['-c', '5', '8.8.8.8'])
    proc.set_redirect_stdio()
    proc.run()
    
    println('Process started with PID: ${proc.pid}')
    
    // Wait for process to complete
    proc.wait()
    
    // Read the output
    output := proc.stdout_read()
    println('Process output:')
    println(output)
    
    println('Process exit code: ${proc.code}')
    proc.close()
}
```

### Interactive Process Communication

```v
import os
import time

fn main() {
    // Start an interactive process (python REPL)
    mut proc := os.new_process('python3')
    proc.set_args(['-i'])
    proc.set_redirect_stdio()
    proc.run()
    
    if proc.pid > 0 {
        println('Started Python process with PID: ${proc.pid}')
        
        // Send commands to the process
        commands := [
            'print("Hello from V!")',
            'x = 42',
            'print(f"The answer is {x}")',
            'exit()',
        ]
        
        for cmd in commands {
            println('Sending: ${cmd}')
            proc.stdin_write('${cmd}\n')
            time.sleep(100 * time.millisecond)
            
            // Try to read output
            output := proc.stdout_read()
            if output.len > 0 {
                println('Output: ${output}')
            }
        }
        
        proc.wait()
        println('Process finished with exit code: ${proc.code}')
        proc.close()
    }
}
```

### Running System Commands Safely

```v
import os

fn run_command_safely(cmd string, args []string) ?string {
    // Validate command exists
    if !os.exists_in_system_path(cmd) {
        return error('Command not found: ${cmd}')
    }
    
    // Build command with proper escaping
    mut full_cmd := cmd
    for arg in args {
        // Basic argument escaping (add quotes if spaces)
        if arg.contains(' ') {
            full_cmd += ' "${arg}"'
        } else {
            full_cmd += ' ${arg}'
        }
    }
    
    result := os.execute(full_cmd)
    if result.exit_code != 0 {
        return error('Command failed with exit code ${result.exit_code}: ${result.output}')
    }
    
    return result.output
}

fn main() {
    // Safe command execution
    output := run_command_safely('echo', ['Hello, World!']) or {
        eprintln('Error: ${err}')
        return
    }
    println('Safe output: ${output.trim_space()}')
    
    // Try with invalid command
    run_command_safely('invalid_cmd', []) or {
        println('Expected error: ${err}')
    }
}
```

### Parallel Process Execution

```v
import os
import sync

fn execute_async(cmd string) string {
    result := os.execute(cmd)
    return 'Command: ${cmd}\nExit code: ${result.exit_code}\nOutput: ${result.output}\n'
}

fn main() {
    commands := [
        'echo "Task 1"',
        'echo "Task 2"',
        'echo "Task 3"',
        'sleep 1 && echo "Task 4 (delayed)"',
    ]
    
    // Execute commands in parallel using threads
    mut threads := []thread string{}
    
    for cmd in commands {
        threads << spawn execute_async(cmd)
    }
    
    // Wait for all threads to complete and collect results
    mut results := []string{}
    for t in threads {
        results << t.wait()
    }
    
    println('All processes completed:')
    for i, result in results {
        println('=== Result ${i + 1} ===')
        println(result)
    }
}
```

### Environment Variables and Process Context

```v
import os

fn main() {
    // Get environment variables
    home_dir := os.getenv('HOME')
    println('Home directory: ${home_dir}')
    
    // Set environment variables for child processes
    os.setenv('MY_CUSTOM_VAR', 'Hello from V!', true)
    
    // Execute command with modified environment
    result := os.execute('env | grep MY_CUSTOM_VAR')
    println('Environment variable in child process:')
    println(result.output)
    
    // Get current working directory
    cwd := os.getwd()
    println('Current working directory: ${cwd}')
    
    // Change directory and execute command
    os.chdir('/tmp') or {
        eprintln('Failed to change directory: ${err}')
        return
    }
    
    pwd_result := os.execute('pwd')
    println('New working directory: ${pwd_result.output.trim_space()}')
    
    // Restore original directory
    os.chdir(cwd) or {
        eprintln('Failed to restore directory: ${err}')
    }
}
```

### Cross-Platform Process Management

```v
import os

fn get_system_info() {
    mut cmd := ''
    mut args := []string{}
    
    // Use different commands based on OS
    $if windows {
        cmd = 'systeminfo'
        args = ['/fo', 'list']
    } $else $if macos {
        cmd = 'system_profiler'
        args = ['SPSoftwareDataType']
    } $else {
        // Linux and other Unix-like systems
        cmd = 'uname'
        args = ['-a']
    }
    
    mut full_cmd := cmd
    for arg in args {
        full_cmd += ' ${arg}'
    }
    
    result := os.execute(full_cmd)
    if result.exit_code == 0 {
        println('System information:')
        println(result.output)
    } else {
        eprintln('Failed to get system information')
    }
}

fn main() {
    get_system_info()
    
    // Get process list (cross-platform)
    mut ps_cmd := ''
    $if windows {
        ps_cmd = 'tasklist /fo table'
    } $else {
        ps_cmd = 'ps aux'
    }
    
    result := os.execute(ps_cmd)
    if result.exit_code == 0 {
        lines := result.output.split_into_lines()
        println('\nRunning processes (first 10):')
        for i, line in lines {
            if i >= 10 { break }
            println(line)
        }
    }
}
```

## Networking and HTTP

V provides excellent support for networking operations including HTTP requests, web servers, and socket programming:

### HTTP Client Operations

```v
import net.http
import json

fn main() {
    // Simple GET request
    resp := http.get('https://api.github.com/users/vlang') or {
        eprintln('Failed to make GET request: ${err}')
        return
    }
    
    println('Status Code: ${resp.status_code}')
    println('Response Body: ${resp.body}')
    
    // GET request with headers
    mut req := http.new_request(.get, 'https://httpbin.org/headers', '')
    req.add_header('User-Agent', 'V-Language-Client/1.0')
    req.add_header('Accept', 'application/json')
    
    response := req.do() or {
        eprintln('Request failed: ${err}')
        return
    }
    
    println('\nHeaders response: ${response.body}')
}
```

### HTTP POST Requests with JSON

```v
import net.http
import json

struct User {
    name  string
    email string
    age   int
}

fn main() {
    // Create user data
    user := User{
        name:  'John Doe'
        email: 'john@example.com'
        age:   30
    }
    
    // Convert to JSON
    json_data := json.encode(user)
    println('Sending JSON: ${json_data}')
    
    // POST request with JSON data
    mut req := http.new_request(.post, 'https://httpbin.org/post', json_data)
    req.add_header('Content-Type', 'application/json')
    req.add_header('Accept', 'application/json')
    
    resp := req.do() or {
        eprintln('POST request failed: ${err}')
        return
    }
    
    println('Response Status: ${resp.status_code}')
    println('Response Body: ${resp.body}')
}
```

### HTTP Web Server

```v
import vweb
import json

struct App {
    vweb.Context
}

struct ApiResponse {
    message string
    status  string
    data    map[string]string
}

fn main() {
    mut app := &App{}
    vweb.run(app, 8080)
}

// Root endpoint
['/']
pub fn (mut app App) index() vweb.Result {
    return app.text('Welcome to V Web Server!')
}

// JSON API endpoint
['/api/info']
pub fn (mut app App) api_info() vweb.Result {
    response := ApiResponse{
        message: 'Server is running'
        status:  'success'
        data:    {'version': '1.0', 'language': 'V'}
    }
    return app.json(response)
}

// POST endpoint to receive data
['/api/data'; post]
pub fn (mut app App) receive_data() vweb.Result {
    body := app.req.data
    println('Received data: ${body}')
    
    response := ApiResponse{
        message: 'Data received successfully'
        status:  'success'
        data:    {'received_bytes': body.len.str()}
    }
    return app.json(response)
}

// GET endpoint with parameters
['/api/user/:id']
pub fn (mut app App) get_user() vweb.Result {
    user_id := app.params['id']
    
    response := ApiResponse{
        message: 'User data retrieved'
        status:  'success'
        data:    {'user_id': user_id, 'name': 'Sample User'}
    }
    return app.json(response)
}
```

### TCP Socket Programming

```v
import net

// TCP Server
fn start_tcp_server() {
    mut server := net.listen_tcp(.ip, ':8081') or {
        eprintln('Failed to start server: ${err}')
        return
    }
    defer { server.close() }
    
    println('TCP Server listening on :8081')
    
    for {
        mut conn := server.accept() or {
            eprintln('Failed to accept connection: ${err}')
            continue
        }
        
        // Handle client in goroutine
        spawn handle_client(mut conn)
    }
}

fn handle_client(mut conn net.TcpConn) {
    defer { conn.close() }
    
    for {
        // Read data from client
        mut buffer := []u8{len: 1024}
        bytes_read := conn.read(mut buffer) or {
            println('Client disconnected')
            break
        }
        
        if bytes_read == 0 {
            break
        }
        
        received := buffer[..bytes_read].bytestr()
        println('Received: ${received}')
        
        // Echo back to client
        response := 'Echo: ${received}'
        conn.write(response.bytes()) or {
            eprintln('Failed to write response: ${err}')
            break
        }
    }
}

// TCP Client
fn tcp_client_example() {
    mut conn := net.dial_tcp('127.0.0.1:8081') or {
        eprintln('Failed to connect: ${err}')
        return
    }
    defer { conn.close() }
    
    // Send data
    message := 'Hello from V client!'
    conn.write(message.bytes()) or {
        eprintln('Failed to send data: ${err}')
        return
    }
    
    // Read response
    mut buffer := []u8{len: 1024}
    bytes_read := conn.read(mut buffer) or {
        eprintln('Failed to read response: ${err}')
        return
    }
    
    response := buffer[..bytes_read].bytestr()
    println('Server response: ${response}')
}

fn main() {
    // Start server in background
    spawn start_tcp_server()
    
    // Give server time to start
    time.sleep(1 * time.second)
    
    // Run client
    tcp_client_example()
}
```

### UDP Socket Programming

```v
import net

// UDP Server
fn udp_server_example() {
    mut socket := net.listen_udp(':8082') or {
        eprintln('Failed to create UDP socket: ${err}')
        return
    }
    defer { socket.close() }
    
    println('UDP Server listening on :8082')
    
    for {
        mut buffer := []u8{len: 1024}
        bytes_read, addr := socket.read_from(mut buffer) or {
            eprintln('Failed to read UDP data: ${err}')
            continue
        }
        
        message := buffer[..bytes_read].bytestr()
        println('Received from ${addr}: ${message}')
        
        // Send response back to sender
        response := 'UDP Echo: ${message}'
        socket.write_to(addr, response.bytes()) or {
            eprintln('Failed to send UDP response: ${err}')
        }
    }
}

// UDP Client
fn udp_client_example() {
    mut socket := net.dial_udp('127.0.0.1:8082') or {
        eprintln('Failed to create UDP client: ${err}')
        return
    }
    defer { socket.close() }
    
    // Send data
    message := 'Hello UDP from V!'
    socket.write(message.bytes()) or {
        eprintln('Failed to send UDP data: ${err}')
        return
    }
    
    // Read response
    mut buffer := []u8{len: 1024}
    bytes_read := socket.read(mut buffer) or {
        eprintln('Failed to read UDP response: ${err}')
        return
    }
    
    response := buffer[..bytes_read].bytestr()
    println('UDP Server response: ${response}')
}

fn main() {
    // Example usage
    println('Choose mode:')
    println('1. Run UDP server')
    println('2. Run UDP client')
    
    // For demo, we'll run both
    spawn udp_server_example()
    
    time.sleep(1 * time.second)
    udp_client_example()
}
```

### HTTP File Download with Progress

```v
import net.http
import os

fn download_file(url string, filename string) {
    println('Downloading ${url}...')
    
    mut req := http.new_request(.get, url, '')
    req.add_header('User-Agent', 'V-Downloader/1.0')
    
    resp := req.do() or {
        eprintln('Download failed: ${err}')
        return
    }
    
    if resp.status_code != 200 {
        eprintln('HTTP Error: ${resp.status_code}')
        return
    }
    
    // Write to file
    os.write_file(filename, resp.body) or {
        eprintln('Failed to write file: ${err}')
        return
    }
    
    println('Downloaded ${resp.body.len} bytes to ${filename}')
}

fn main() {
    download_file('https://httpbin.org/json', 'sample.json')
}
```

### WebSocket Client

```v
import net.websocket
import time

fn main() {
    mut ws := websocket.new_client('wss://echo.websocket.org') or {
        eprintln('Failed to create WebSocket client: ${err}')
        return
    }
    
    // Set up event handlers
    ws.on_open(fn (mut ws websocket.Client) ! {
        println('WebSocket connection opened')
        ws.write_string('Hello WebSocket from V!') or {
            eprintln('Failed to send message: ${err}')
        }
    })
    
    ws.on_message(fn (mut ws websocket.Client, msg &websocket.Message) ! {
        match msg.opcode {
            .text_frame {
                println('Received text: ${msg.payload.bytestr()}')
            }
            .binary_frame {
                println('Received binary data: ${msg.payload.len} bytes')
            }
            else {
                println('Received other message type')
            }
        }
    })
    
    ws.on_error(fn (mut ws websocket.Client, err string) ! {
        eprintln('WebSocket error: ${err}')
    })
    
    ws.on_close(fn (mut ws websocket.Client, code int, reason string) ! {
        println('WebSocket closed: ${code} - ${reason}')
    })
    
    // Connect and start listening
    ws.connect() or {
        eprintln('Failed to connect: ${err}')
        return
    }
    
    // Keep connection alive
    time.sleep(5 * time.second)
    
    // Send another message
    ws.write_string('Another message from V!') or {
        eprintln('Failed to send second message: ${err}')
    }
    
    time.sleep(2 * time.second)
    ws.close(1000, 'Normal closure') or {
        eprintln('Failed to close WebSocket: ${err}')
    }
}
```

## JSON Serialization with Struct Arrays

V provides excellent support for JSON serialization and deserialization with arrays of structs. Here are comprehensive examples:

### Basic Struct Array to JSON

```v
import json
import os

struct Person {
    id       int    [json: 'id']
    name     string [json: 'name']
    email    string [json: 'email']
    age      int    [json: 'age']
    active   bool   [json: 'active']
    salary   f64    [json: 'salary']
}

fn main() {
    // Create array of structs
    people := [
        Person{
            id:     1
            name:   'Alice Johnson'
            email:  'alice@example.com'
            age:    28
            active: true
            salary: 75000.50
        },
        Person{
            id:     2
            name:   'Bob Smith'
            email:  'bob@example.com'
            age:    34
            active: false
            salary: 82000.0
        },
        Person{
            id:     3
            name:   'Carol Davis'
            email:  'carol@example.com'
            age:    31
            active: true
            salary: 69500.75
        },
    ]
    
    // Convert array to JSON
    json_data := json.encode(people)
    println('JSON Array:')
    println(json_data)
    
    // Pretty print JSON
    pretty_json := json.encode_pretty(people)
    println('\nPretty JSON:')
    println(pretty_json)
    
    // Save to file
    os.write_file('people.json', pretty_json) or {
        eprintln('Failed to write JSON file: ${err}')
        return
    }
    println('\nJSON saved to people.json')
}
```

### Loading JSON Array Back to Structs

```v
import json
import os

struct Person {
    id       int    [json: 'id']
    name     string [json: 'name']
    email    string [json: 'email']
    age      int    [json: 'age']
    active   bool   [json: 'active']
    salary   f64    [json: 'salary']
}

fn load_people_from_json(filename string) ?[]Person {
    // Read JSON file
    json_content := os.read_file(filename) or {
        return error('Failed to read file: ${err}')
    }
    
    // Parse JSON array back to struct array
    people := json.decode([]Person, json_content) or {
        return error('Failed to decode JSON: ${err}')
    }
    
    return people
}

fn main() {
    // Load people from JSON file
    people := load_people_from_json('people.json') or {
        eprintln('Error loading people: ${err}')
        return
    }
    
    println('Loaded ${people.len} people from JSON:')
    for i, person in people {
        println('${i + 1}. ${person.name} (${person.age})')
        println('   Email: ${person.email}')
        println('   Active: ${person.active}')
        println('   Salary: \$${person.salary}')
        println('')
    }
    
    // Filter and modify data
    active_people := people.filter(it.active)
    println('Active people: ${active_people.len}')
    
    // Calculate average salary of active people
    if active_people.len > 0 {
        total_salary := active_people.map(it.salary).reduce(fn (acc f64, val f64) f64 {
            return acc + val
        }, 0.0)
        avg_salary := total_salary / active_people.len
        println('Average salary of active people: \$${avg_salary:.2f}')
    }
}
```

### Complex Nested Struct Arrays

```v
import json
import os
import time

struct Address {
    street   string [json: 'street']
    city     string [json: 'city']
    state    string [json: 'state']
    zip_code string [json: 'zip_code']
    country  string [json: 'country']
}

struct Project {
    id          int      [json: 'id']
    name        string   [json: 'name']
    description string   [json: 'description']
    start_date  string   [json: 'start_date']
    end_date    string   [json: 'end_date']
    budget      f64      [json: 'budget']
    status      string   [json: 'status']
}

struct Employee {
    id           int       [json: 'id']
    first_name   string    [json: 'first_name']
    last_name    string    [json: 'last_name']
    email        string    [json: 'email']
    phone        string    [json: 'phone']
    department   string    [json: 'department']
    position     string    [json: 'position']
    hire_date    string    [json: 'hire_date']
    salary       f64       [json: 'salary']
    active       bool      [json: 'active']
    address      Address   [json: 'address']
    projects     []Project [json: 'projects']
    skills       []string  [json: 'skills']
}

struct Company {
    name         string     [json: 'name']
    founded      string     [json: 'founded']
    headquarters Address    [json: 'headquarters']
    employees    []Employee [json: 'employees']
    total_budget f64        [json: 'total_budget']
}

fn create_sample_data() Company {
    return Company{
        name:    'TechCorp Inc.'
        founded: '2015-03-15'
        headquarters: Address{
            street:   '123 Technology Drive'
            city:     'San Francisco'
            state:    'CA'
            zip_code: '94105'
            country:  'USA'
        }
        employees: [
            Employee{
                id:         1
                first_name: 'Alice'
                last_name:  'Johnson'
                email:      'alice.johnson@techcorp.com'
                phone:      '+1-555-0101'
                department: 'Engineering'
                position:   'Senior Developer'
                hire_date:  '2018-06-01'
                salary:     95000.0
                active:     true
                address: Address{
                    street:   '456 Oak Street'
                    city:     'Palo Alto'
                    state:    'CA'
                    zip_code: '94301'
                    country:  'USA'
                }
                projects: [
                    Project{
                        id:          101
                        name:        'Mobile App Redesign'
                        description: 'Complete redesign of company mobile application'
                        start_date:  '2023-01-15'
                        end_date:    '2023-06-30'
                        budget:      150000.0
                        status:      'completed'
                    },
                    Project{
                        id:          102
                        name:        'API Gateway Implementation'
                        description: 'Implement new API gateway for microservices'
                        start_date:  '2023-07-01'
                        end_date:    '2023-12-31'
                        budget:      200000.0
                        status:      'in_progress'
                    },
                ]
                skills: ['JavaScript', 'Python', 'Go', 'Docker', 'Kubernetes']
            },
            Employee{
                id:         2
                first_name: 'Bob'
                last_name:  'Chen'
                email:      'bob.chen@techcorp.com'
                phone:      '+1-555-0102'
                department: 'Design'
                position:   'UX Designer'
                hire_date:  '2019-03-15'
                salary:     78000.0
                active:     true
                address: Address{
                    street:   '789 Design Boulevard'
                    city:     'Mountain View'
                    state:    'CA'
                    zip_code: '94043'
                    country:  'USA'
                }
                projects: [
                    Project{
                        id:          103
                        name:        'User Experience Research'
                        description: 'Comprehensive UX research for new features'
                        start_date:  '2023-04-01'
                        end_date:    '2023-09-30'
                        budget:      75000.0
                        status:      'in_progress'
                    },
                ]
                skills: ['Figma', 'Sketch', 'Adobe XD', 'User Research', 'Prototyping']
            },
            Employee{
                id:         3
                first_name: 'Carol'
                last_name:  'Williams'
                email:      'carol.williams@techcorp.com'
                phone:      '+1-555-0103'
                department: 'Marketing'
                position:   'Marketing Manager'
                hire_date:  '2020-08-10'
                salary:     72000.0
                active:     false
                address: Address{
                    street:   '321 Marketing Way'
                    city:     'San Jose'
                    state:    'CA'
                    zip_code: '95110'
                    country:  'USA'
                }
                projects: []Project{}
                skills: ['Digital Marketing', 'SEO', 'Content Strategy', 'Analytics']
            },
        ]
        total_budget: 425000.0
    }
}

fn save_company_data(company Company, filename string) ! {
    json_data := json.encode_pretty(company)
    os.write_file(filename, json_data) or {
        return error('Failed to write file: ${err}')
    }
    println('Company data saved to ${filename}')
}

fn load_company_data(filename string) ?Company {
    json_content := os.read_file(filename) or {
        return error('Failed to read file: ${err}')
    }
    
    company := json.decode(Company, json_content) or {
        return error('Failed to decode JSON: ${err}')
    }
    
    return company
}

fn analyze_company_data(company Company) {
    println('=== Company Analysis ===')
    println('Company: ${company.name}')
    println('Founded: ${company.founded}')
    println('Headquarters: ${company.headquarters.city}, ${company.headquarters.state}')
    println('Total Employees: ${company.employees.len}')
    
    // Active employees
    active_employees := company.employees.filter(it.active)
    println('Active Employees: ${active_employees.len}')
    
    // Department breakdown
    mut departments := map[string]int{}
    for emp in company.employees {
        departments[emp.department] = departments[emp.department] + 1
    }
    
    println('\nDepartment Breakdown:')
    for dept, count in departments {
        println('  ${dept}: ${count}')
    }
    
    // Salary statistics
    if active_employees.len > 0 {
        salaries := active_employees.map(it.salary)
        total_salary := salaries.reduce(fn (acc f64, val f64) f64 { return acc + val }, 0.0)
        avg_salary := total_salary / active_employees.len
        min_salary := salaries.reduce(fn (acc f64, val f64) f64 { 
            return if val < acc { val } else { acc }
        }, salaries[0])
        max_salary := salaries.reduce(fn (acc f64, val f64) f64 { 
            return if val > acc { val } else { acc }
        }, salaries[0])
        
        println('\nSalary Statistics:')
        println('  Average: \$${avg_salary:.2f}')
        println('  Minimum: \$${min_salary:.2f}')
        println('  Maximum: \$${max_salary:.2f}')
    }
    
    // Project statistics
    mut all_projects := []Project{}
    for emp in company.employees {
        all_projects << emp.projects
    }
    
    println('\nProject Statistics:')
    println('  Total Projects: ${all_projects.len}')
    
    mut status_count := map[string]int{}
    mut total_project_budget := 0.0
    
    for project in all_projects {
        status_count[project.status] = status_count[project.status] + 1
        total_project_budget += project.budget
    }
    
    for status, count in status_count {
        println('  ${status}: ${count}')
    }
    println('  Total Project Budget: \$${total_project_budget:.2f}')
    
    // Skills analysis
    mut all_skills := []string{}
    for emp in company.employees {
        all_skills << emp.skills
    }
    
    mut skill_count := map[string]int{}
    for skill in all_skills {
        skill_count[skill] = skill_count[skill] + 1
    }
    
    println('\nTop Skills:')
    mut skill_pairs := []struct {
        skill string
        count int
    }{}
    
    for skill, count in skill_count {
        skill_pairs << struct {
            skill: skill
            count: count
        }
    }
    
    // Sort by count (manual sorting since we can't use sort_by easily here)
    for i := 0; i < skill_pairs.len - 1; i++ {
        for j := i + 1; j < skill_pairs.len; j++ {
            if skill_pairs[j].count > skill_pairs[i].count {
                temp := skill_pairs[i]
                skill_pairs[i] = skill_pairs[j]
                skill_pairs[j] = temp
            }
        }
    }
    
    for i := 0; i < skill_pairs.len && i < 5; i++ {
        println('  ${skill_pairs[i].skill}: ${skill_pairs[i].count} employees')
    }
}

fn main() {
    // Create sample data
    company := create_sample_data()
    
    // Save to JSON
    save_company_data(company, 'company_data.json') or {
        eprintln('Failed to save company data: ${err}')
        return
    }
    
    // Load from JSON
    loaded_company := load_company_data('company_data.json') or {
        eprintln('Failed to load company data: ${err}')
        return
    }
    
    // Analyze the data
    analyze_company_data(loaded_company)
    
    // Demonstrate filtering and updating
    println('\n=== Data Manipulation ===')
    
    // Find employees by department
    engineers := loaded_company.employees.filter(it.department == 'Engineering')
    println('Engineers: ${engineers.len}')
    for eng in engineers {
        println('  ${eng.first_name} ${eng.last_name} - ${eng.position}')
    }
    
    // Find employees working on active projects
    employees_with_active_projects := loaded_company.employees.filter(
        it.projects.any(it.status == 'in_progress')
    )
    
    println('\nEmployees with active projects:')
    for emp in employees_with_active_projects {
        active_projects := emp.projects.filter(it.status == 'in_progress')
        println('  ${emp.first_name} ${emp.last_name}: ${active_projects.len} active projects')
        for project in active_projects {
            println('    - ${project.name} (\$${project.budget})')
        }
    }
}
```

### JSON Array Processing with Error Handling

```v
import json
import os

struct Product {
    id          int      [json: 'id']
    name        string   [json: 'name']
    description string   [json: 'description']
    price       f64      [json: 'price']
    category    string   [json: 'category']
    in_stock    bool     [json: 'in_stock']
    tags        []string [json: 'tags']
    created_at  string   [json: 'created_at']
    updated_at  string   [json: 'updated_at']
}

struct ProductResponse {
    success  bool      [json: 'success']
    products []Product [json: 'products']
    total    int       [json: 'total']
    message  string    [json: 'message']
}

// Load products with comprehensive error handling
fn load_products_safely(filename string) ?[]Product {
    // Check if file exists
    if !os.exists(filename) {
        return error('Product file does not exist: ${filename}')
    }
    
    // Read file content
    content := os.read_file(filename) or {
        return error('Failed to read product file: ${err}')
    }
    
    if content.trim_space() == '' {
        return error('Product file is empty')
    }
    
    // Try to parse as array first
    products := json.decode([]Product, content) or {
        // If array parsing fails, try as response object
        response := json.decode(ProductResponse, content) or {
            return error('Failed to parse JSON as either product array or response object: ${err}')
        }
        
        if !response.success {
            return error('API response indicates failure: ${response.message}')
        }
        
        return response.products
    }
    
    return products
}

// Save products with backup
fn save_products_with_backup(products []Product, filename string) ! {
    // Create backup if file exists
    if os.exists(filename) {
        backup_name := '${filename}.backup'
        os.cp(filename, backup_name) or {
            eprintln('Warning: Failed to create backup: ${err}')
        }
    }
    
    // Convert to JSON
    json_data := json.encode_pretty(products)
    
    // Save to temporary file first
    temp_filename := '${filename}.tmp'
    os.write_file(temp_filename, json_data) or {
        return error('Failed to write temporary file: ${err}')
    }
    
    // Verify the temporary file by reading it back
    json.decode([]Product, os.read_file(temp_filename) or {
        return error('Failed to read temporary file for verification: ${err}')
    }) or {
        os.rm(temp_filename) or {}
        return error('JSON verification failed: ${err}')
    }
    
    // Move temporary file to final location
    os.mv(temp_filename, filename) or {
        return error('Failed to move temporary file to final location: ${err}')
    }
    
    println('Products saved successfully to ${filename}')
}

// Filter and transform products
fn process_product_data(products []Product) {
    println('=== Product Data Processing ===')
    
    // Filter by category
    electronics := products.filter(it.category == 'Electronics')
    books := products.filter(it.category == 'Books')
    clothing := products.filter(it.category == 'Clothing')
    
    println('Products by category:')
    println('  Electronics: ${electronics.len}')
    println('  Books: ${books.len}')
    println('  Clothing: ${clothing.len}')
    
    // In-stock products
    in_stock_products := products.filter(it.in_stock)
    println('  In stock: ${in_stock_products.len}/${products.len}')
    
    // Price analysis
    if products.len > 0 {
        prices := products.map(it.price)
        total_value := prices.reduce(fn (acc f64, price f64) f64 { return acc + price }, 0.0)
        avg_price := total_value / products.len
        
        min_price := prices.reduce(fn (acc f64, price f64) f64 {
            return if price < acc { price } else { acc }
        }, prices[0])
        
        max_price := prices.reduce(fn (acc f64, price f64) f64 {
            return if price > acc { price } else { acc }
        }, prices[0])
        
        println('\nPrice Analysis:')
        println('  Average price: \$${avg_price:.2f}')
        println('  Minimum price: \$${min_price:.2f}')
        println('  Maximum price: \$${max_price:.2f}')
        println('  Total inventory value: \$${total_value:.2f}')
    }
    
    // Tag analysis
    mut all_tags := []string{}
    for product in products {
        all_tags << product.tags
    }
    
    mut tag_count := map[string]int{}
    for tag in all_tags {
        tag_count[tag] = tag_count[tag] + 1
    }
    
    println('\nMost popular tags:')
    mut tag_pairs := []struct {
        tag   string
        count int
    }{}
    
    for tag, count in tag_count {
        tag_pairs << struct {
            tag:   tag
            count: count
        }
    }
    
    // Simple sorting by count (descending)
    for i := 0; i < tag_pairs.len - 1; i++ {
        for j := i + 1; j < tag_pairs.len; j++ {
            if tag_pairs[j].count > tag_pairs[i].count {
                temp := tag_pairs[i]
                tag_pairs[i] = tag_pairs[j]
                tag_pairs[j] = temp
            }
        }
    }
    
    for i := 0; i < tag_pairs.len && i < 5; i++ {
        println('  ${tag_pairs[i].tag}: ${tag_pairs[i].count}')
    }
}

fn create_sample_products() []Product {
    import time
    now := time.now().format()
    
    return [
        Product{
            id:          1
            name:        'Smartphone Pro Max'
            description: 'Latest flagship smartphone with advanced features'
            price:       999.99
            category:    'Electronics'
            in_stock:    true
            tags:        ['mobile', 'tech', 'flagship', '5G']
            created_at:  now
            updated_at:  now
        },
        Product{
            id:          2
            name:        'Programming with V'
            description: 'Comprehensive guide to V programming language'
            price:       49.99
            category:    'Books'
            in_stock:    true
            tags:        ['programming', 'tutorial', 'v-lang']
            created_at:  now
            updated_at:  now
        },
        Product{
            id:          3
            name:        'Wireless Headphones'
            description: 'Premium noise-canceling wireless headphones'
            price:       299.99
            category:    'Electronics'
            in_stock:    false
            tags:        ['audio', 'wireless', 'noise-canceling']
            created_at:  now
            updated_at:  now
        },
        Product{
            id:          4
            name:        'Cotton T-Shirt'
            description: 'Comfortable 100% cotton t-shirt'
            price:       19.99
            category:    'Clothing'
            in_stock:    true
            tags:        ['cotton', 'casual', 'basic']
            created_at:  now
            updated_at:  now
        },
        Product{
            id:          5
            name:        'Gaming Laptop'
            description: 'High-performance gaming laptop'
            price:       1499.99
            category:    'Electronics'
            in_stock:    true
            tags:        ['gaming', 'laptop', 'performance']
            created_at:  now
            updated_at:  now
        },
    ]
}

fn main() {
    // Create sample data
    products := create_sample_products()
    
    // Save products
    save_products_with_backup(products, 'products.json') or {
        eprintln('Failed to save products: ${err}')
        return
    }
    
    // Load products back
    loaded_products := load_products_safely('products.json') or {
        eprintln('Failed to load products: ${err}')
        return
    }
    
    println('Successfully loaded ${loaded_products.len} products')
    
    // Process and analyze data
    process_product_data(loaded_products)
    
    // Demonstrate updating data
    println('\n=== Updating Product Data ===')
    
    mut updated_products := loaded_products.clone()
    
    // Update price for electronics (10% discount)
    for mut product in updated_products {
        if product.category == 'Electronics' {
            old_price := product.price
            product.price = product.price * 0.9 // 10% discount
            println('Updated ${product.name}: \$${old_price:.2f} -> \$${product.price:.2f}')
        }
    }
    
    // Save updated data
    save_products_with_backup(updated_products, 'products_updated.json') or {
        eprintln('Failed to save updated products: ${err}')
        return
    }
    
    println('\nUpdated products saved to products_updated.json')
}
```

## Advanced Programming Patterns

### Error Handling Best Practices

```v
import os
import net.http

// Custom error types for better error handling
struct FileNotFoundError {
    Error
    path string
}

struct NetworkError {
    Error
    url    string
    status int
}

// Function that can return multiple error types
fn read_config_file(path string) ?map[string]string {
    if !os.exists(path) {
        return FileNotFoundError{
            msg:  'Configuration file not found'
            path: path
        }
    }
    
    content := os.read_file(path) or {
        return error('Failed to read file: ${err}')
    }
    
    mut config := map[string]string{}
    lines := content.split_into_lines()
    
    for line in lines {
        if line.contains('=') {
            parts := line.split('=')
            if parts.len == 2 {
                config[parts[0].trim_space()] = parts[1].trim_space()
            }
        }
    }
    
    return config
}

// Comprehensive error handling example
fn fetch_and_process_data(url string, output_file string) ! {
    // Network operation with custom error handling
    resp := http.get(url) or {
        return NetworkError{
            msg:    'Failed to fetch data from URL'
            url:    url
            status: 0
        }
    }
    
    if resp.status_code != 200 {
        return NetworkError{
            msg:    'HTTP request failed'
            url:    url
            status: resp.status_code
        }
    }
    
    // Process the data
    processed_data := process_response_data(resp.body) or {
        return error('Data processing failed: ${err}')
    }
    
    // Save to file
    os.write_file(output_file, processed_data) or {
        return error('Failed to save processed data: ${err}')
    }
}

fn process_response_data(data string) ?string {
    if data.len == 0 {
        return error('Empty response data')
    }
    
    // Simple processing - add timestamp
    import time
    timestamp := time.now().format()
    return 'Processed at: ${timestamp}\n${data}'
}

fn main() {
    // Example 1: Reading config with error handling
    config := read_config_file('config.txt') or {
        match err {
            FileNotFoundError {
                eprintln('Config error: ${err.msg} at path: ${err.path}')
                eprintln('Creating default config...')
                // Create default config
                default_config := 'host=localhost\nport=8080\n'
                os.write_file('config.txt', default_config) or {
                    eprintln('Failed to create default config: ${err}')
                    return
                }
                map[string]string{}
            }
            else {
                eprintln('Unexpected error: ${err}')
                return
            }
        }
    }
    
    println('Config loaded: ${config}')
    
    // Example 2: Network operation with comprehensive error handling
    fetch_and_process_data('https://httpbin.org/json', 'output.json') or {
        match err {
            NetworkError {
                eprintln('Network error: ${err.msg}')
                eprintln('URL: ${err.url}, Status: ${err.status}')
            }
            else {
                eprintln('Processing error: ${err}')
            }
        }
    }
}
```

### Concurrent Programming with Channels

```v
import time
import sync

// Producer-Consumer pattern
fn producer(ch chan int, items int) {
    for i in 0 .. items {
        println('Producing: ${i}')
        ch <- i
        time.sleep(100 * time.millisecond)
    }
    ch.close()
}

fn consumer(ch chan int, id int) {
    for {
        value := <-ch or {
            println('Consumer ${id}: Channel closed')
            break
        }
        println('Consumer ${id} consumed: ${value}')
        time.sleep(200 * time.millisecond)
    }
}

// Worker pool pattern
struct Job {
    id   int
    task string
}

struct Result {
    job_id int
    output string
    error  string
}

fn worker(id int, jobs <-chan Job, results chan<- Result) {
    for {
        job := <-jobs or {
            println('Worker ${id}: No more jobs')
            break
        }
        
        println('Worker ${id} processing job ${job.id}')
        
        // Simulate work
        time.sleep(time.Duration(100 + (job.id % 5) * 100) * time.millisecond)
        
        // Simulate occasional errors
        if job.id % 7 == 0 {
            results <- Result{
                job_id: job.id
                output: ''
                error:  'Simulated error for job ${job.id}'
            }
        } else {
            results <- Result{
                job_id: job.id
                output: 'Processed: ${job.task}'
                error:  ''
            }
        }
    }
}

fn main() {
    // Example 1: Basic producer-consumer
    println('=== Producer-Consumer Example ===')
    ch := chan int{cap: 5}
    
    spawn producer(ch, 10)
    spawn consumer(ch, 1)
    spawn consumer(ch, 2)
    
    time.sleep(3 * time.second)
    
    // Example 2: Worker pool
    println('\n=== Worker Pool Example ===')
    jobs := chan Job{cap: 100}
    results := chan Result{cap: 100}
    
    // Start workers
    num_workers := 3
    for i in 0 .. num_workers {
        spawn worker(i + 1, jobs, results)
    }
    
    // Send jobs
    num_jobs := 15
    spawn fn [jobs, num_jobs] () {
        for i in 0 .. num_jobs {
            jobs <- Job{
                id:   i + 1
                task: 'Task number ${i + 1}'
            }
        }
        jobs.close()
    }()
    
    // Collect results
    mut completed := 0
    mut errors := 0
    
    for completed < num_jobs {
        result := <-results
        if result.error != '' {
            println('Job ${result.job_id} failed: ${result.error}')
            errors++
        } else {
            println('Job ${result.job_id} completed: ${result.output}')
        }
        completed++
    }
    
    println('All jobs completed. Errors: ${errors}/${num_jobs}')
}
```

### Memory Management and Performance

```v
import benchmark
import math

// Efficient string building
fn build_string_efficient(count int) string {
    mut sb := strings.new_builder(count * 10) // Pre-allocate capacity
    for i in 0 .. count {
        sb.write_string('Item ${i} ')
    }
    return sb.str()
}

// Less efficient string concatenation
fn build_string_inefficient(count int) string {
    mut result := ''
    for i in 0 .. count {
        result += 'Item ${i} ' // Creates new string each time
    }
    return result
}

// Memory-efficient array operations
fn process_large_dataset() {
    println('Processing large dataset...')
    
    // Use array slicing to avoid copying large amounts of data
    data := []int{len: 1_000_000, init: index * 2}
    
    // Process in chunks to reduce memory usage
    chunk_size := 10_000
    mut processed_count := 0
    
    for start := 0; start < data.len; start += chunk_size {
        end := math.min(start + chunk_size, data.len)
        chunk := data[start..end]
        
        // Process chunk
        sum := chunk.reduce(fn (acc int, val int) int {
            return acc + val
        }, 0)
        
        processed_count += chunk.len
        if processed_count % 100_000 == 0 {
            println('Processed ${processed_count} items, chunk sum: ${sum}')
        }
    }
}

// Benchmark example
fn run_benchmarks() {
    println('\n=== Performance Benchmarks ===')
    
    mut bmark := benchmark.new_benchmark()
    
    // Benchmark string building
    bmark.step('Efficient string building')
    efficient_result := build_string_efficient(1000)
    bmark.step('Inefficient string building')
    inefficient_result := build_string_inefficient(1000)
    
    bmark.stop()
    println('Benchmark results:')
    println(bmark.total_message('String building comparison'))
    
    // Verify results are the same
    assert efficient_result == inefficient_result
}

fn main() {
    run_benchmarks()
    process_large_dataset()
}
```

### Database Operations (Generic Pattern)

```v
import db.sqlite

struct User {
    id    int    [primary; sql: serial]
    name  string [nonull]
    email string [unique; nonull]
    age   int
}

struct Database {
    conn sqlite.DB
}

fn (mut db Database) init() ! {
    // Create table if it doesn't exist
    sql db.conn {
        create table User
    }!
}

fn (mut db Database) create_user(user User) !User {
    sql db.conn {
        insert user into User
    }!
    
    // Get the created user with ID
    return sql db.conn {
        select from User where email == user.email limit 1
    }!.first()
}

fn (mut db Database) get_user(id int) ?User {
    users := sql db.conn {
        select from User where id == id limit 1
    } or { return none }
    
    return if users.len > 0 {
        users[0]
    } else {
        none
    }
}

fn (mut db Database) get_users_by_age_range(min_age int, max_age int) []User {
    return sql db.conn {
        select from User where age >= min_age && age <= max_age
    } or { [] }
}

fn (mut db Database) update_user(user User) ! {
    sql db.conn {
        update User set name = user.name, email = user.email, age = user.age where id == user.id
    }!
}

fn (mut db Database) delete_user(id int) ! {
    sql db.conn {
        delete from User where id == id
    }!
}

fn main() {
    mut db := Database{
        conn: sqlite.connect('users.db') or {
            eprintln('Failed to connect to database: ${err}')
            return
        }
    }
    defer { db.conn.close() }
    
    // Initialize database
    db.init() or {
        eprintln('Failed to initialize database: ${err}')
        return
    }
    
    // Create users
    users_data := [
        User{name: 'Alice Johnson', email: 'alice@example.com', age: 28},
        User{name: 'Bob Smith', email: 'bob@example.com', age: 34},
        User{name: 'Charlie Brown', email: 'charlie@example.com', age: 22},
    ]
    
    mut created_users := []User{}
    for user_data in users_data {
        created_user := db.create_user(user_data) or {
            eprintln('Failed to create user ${user_data.name}: ${err}')
            continue
        }
        created_users << created_user
        println('Created user: ${created_user.name} (ID: ${created_user.id})')
    }
    
    // Query users
    young_users := db.get_users_by_age_range(20, 30)
    println('\nUsers aged 20-30:')
    for user in young_users {
        println('  ${user.name} (${user.age} years old)')
    }
    
    // Update a user
    if created_users.len > 0 {
        mut user_to_update := created_users[0]
        user_to_update.age = 29
        db.update_user(user_to_update) or {
            eprintln('Failed to update user: ${err}')
        }
        println('\nUpdated ${user_to_update.name} age to ${user_to_update.age}')
    }
    
    // Get specific user
    if created_users.len > 1 {
        user := db.get_user(created_users[1].id) or {
            eprintln('User not found')
            return
        }
        println('Retrieved user: ${user.name}')
    }
}
```

## Practical Examples and Mini Projects

### Command-Line Tool: File Organizer

```v
import os
import time

struct FileOrganizer {
    source_dir   string
    target_dir   string
    dry_run      bool
    verbose      bool
    file_stats   map[string]int
}

fn (mut organizer FileOrganizer) organize_files() ! {
    if !os.exists(organizer.source_dir) {
        return error('Source directory does not exist: ${organizer.source_dir}')
    }
    
    if !organizer.dry_run {
        os.mkdir_all(organizer.target_dir) or {
            return error('Failed to create target directory: ${err}')
        }
    }
    
    files := os.ls(organizer.source_dir) or {
        return error('Failed to list source directory: ${err}')
    }
    
    for file in files {
        full_path := '${organizer.source_dir}/${file}'
        
        if !os.is_file(full_path) {
            continue
        }
        
        extension := os.file_ext(file).to_lower()
        if extension.starts_with('.') {
            extension = extension[1..]
        }
        
        if extension == '' {
            extension = 'no_extension'
        }
        
        // Count file types
        organizer.file_stats[extension] = organizer.file_stats[extension] + 1
        
        // Create category directory
        category_dir := '${organizer.target_dir}/${extension}'
        if !organizer.dry_run {
            os.mkdir_all(category_dir) or {
                eprintln('Failed to create category directory ${category_dir}: ${err}')
                continue
            }
        }
        
        target_path := '${category_dir}/${file}'
        
        if organizer.verbose || organizer.dry_run {
            action := if organizer.dry_run { '[DRY RUN] Would move' } else { 'Moving' }
            println('${action}: ${full_path} -> ${target_path}')
        }
        
        if !organizer.dry_run {
            os.mv(full_path, target_path) or {
                eprintln('Failed to move ${full_path}: ${err}')
                continue
            }
        }
    }
}

fn (organizer FileOrganizer) print_statistics() {
    println('\nFile Organization Statistics:')
    println('============================')
    
    mut total_files := 0
    for extension, count in organizer.file_stats {
        println('${extension}: ${count} files')
        total_files += count
    }
    println('Total files processed: ${total_files}')
}

fn main() {
    mut source := ''
    mut target := ''
    mut dry_run := false
    mut verbose := false
    
    // Parse command line arguments
    for i, arg in os.args {
        match arg {
            '--source', '-s' {
                if i + 1 < os.args.len {
                    source = os.args[i + 1]
                }
            }
            '--target', '-t' {
                if i + 1 < os.args.len {
                    target = os.args[i + 1]
                }
            }
            '--dry-run' {
                dry_run = true
            }
            '--verbose', '-v' {
                verbose = true
            }
            '--help', '-h' {
                println('File Organizer - Organize files by extension')
                println('Usage: file_organizer --source <dir> --target <dir> [options]')
                println('Options:')
                println('  --source, -s    Source directory')
                println('  --target, -t    Target directory')  
                println('  --dry-run       Show what would be done without actually moving files')
                println('  --verbose, -v   Verbose output')
                println('  --help, -h      Show this help')
                return
            }
            else {}
        }
    }
    
    if source == '' || target == '' {
        eprintln('Error: Both source and target directories must be specified')
        eprintln('Use --help for usage information')
        exit(1)
    }
    
    mut organizer := FileOrganizer{
        source_dir:  source
        target_dir:  target
        dry_run:     dry_run
        verbose:     verbose
        file_stats:  map[string]int{}
    }
    
    println('Starting file organization...')
    if dry_run {
        println('DRY RUN MODE - No files will be moved')
    }
    
    start_time := time.now()
    organizer.organize_files() or {
        eprintln('Error during organization: ${err}')
        exit(1)
    }
    
    duration := time.now() - start_time
    organizer.print_statistics()
    println('Organization completed in ${duration}')
}
```

### Simple Web API Server

```v
import vweb
import json
import time
import crypto.rand
import encoding.base64

struct App {
    vweb.Context
mut:
    tasks []Task
    next_id int
}

struct Task {
    id          int    [json: 'id']
    title       string [json: 'title']
    description string [json: 'description']
    completed   bool   [json: 'completed']
    created_at  string [json: 'created_at']
    updated_at  string [json: 'updated_at']
}

struct CreateTaskRequest {
    title       string
    description string
}

struct UpdateTaskRequest {
    title       string
    description string
    completed   bool
}

struct ApiResponse[T] {
    success bool   [json: 'success']
    data    T      [json: 'data']
    message string [json: 'message']
}

fn main() {
    mut app := &App{
        tasks: []Task{}
        next_id: 1
    }
    
    // Add some sample data
    app.add_sample_tasks()
    
    println('Starting Task API server on http://localhost:8080')
    println('Endpoints:')
    println('  GET    /api/tasks       - List all tasks')
    println('  GET    /api/tasks/:id   - Get specific task')
    println('  POST   /api/tasks       - Create new task')
    println('  PUT    /api/tasks/:id   - Update task')
    println('  DELETE /api/tasks/:id   - Delete task')
    
    vweb.run(app, 8080)
}

fn (mut app App) add_sample_tasks() {
    now := time.now().format()
    app.tasks = [
        Task{
            id:          1
            title:       'Learn V programming'
            description: 'Go through V documentation and examples'
            completed:   false
            created_at:  now
            updated_at:  now
        },
        Task{
            id:          2
            title:       'Build a web API'
            description: 'Create a RESTful API using vweb'
            completed:   true
            created_at:  now
            updated_at:  now
        },
    ]
    app.next_id = 3
}

// Root endpoint with API documentation
['/']
pub fn (mut app App) index() vweb.Result {
    html := '<!DOCTYPE html>
<html>
<head>
    <title>Task API</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .endpoint { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .method { font-weight: bold; color: #0066cc; }
    </style>
</head>
<body>
    <h1>Task API Server</h1>
    <h2>Available Endpoints:</h2>
    <div class="endpoint">
        <span class="method">GET</span> /api/tasks - List all tasks
    </div>
    <div class="endpoint">
        <span class="method">GET</span> /api/tasks/:id - Get specific task
    </div>
    <div class="endpoint">
        <span class="method">POST</span> /api/tasks - Create new task
    </div>
    <div class="endpoint">
        <span class="method">PUT</span> /api/tasks/:id - Update task
    </div>
    <div class="endpoint">
        <span class="method">DELETE</span> /api/tasks/:id - Delete task
    </div>
    <p><strong>Content-Type:</strong> application/json</p>
</body>
</html>'
    return app.html(html)
}

// Get all tasks
['/api/tasks'; get]
pub fn (mut app App) get_tasks() vweb.Result {
    response := ApiResponse[[]Task]{
        success: true
        data:    app.tasks
        message: 'Tasks retrieved successfully'
    }
    return app.json(response)
}

// Get specific task
['/api/tasks/:id'; get]
pub fn (mut app App) get_task() vweb.Result {
    task_id := app.params['id'].int()
    
    for task in app.tasks {
        if task.id == task_id {
            response := ApiResponse[Task]{
                success: true
                data:    task
                message: 'Task found'
            }
            return app.json(response)
        }
    }
    
    app.set_status(404)
    response := ApiResponse[Task]{
        success: false
        data:    Task{}
        message: 'Task not found'
    }
    return app.json(response)
}

// Create new task
['/api/tasks'; post]
pub fn (mut app App) create_task() vweb.Result {
    request := json.decode(CreateTaskRequest, app.req.data) or {
        app.set_status(400)
        response := ApiResponse[Task]{
            success: false
            data:    Task{}
            message: 'Invalid JSON data'
        }
        return app.json(response)
    }
    
    if request.title.trim_space() == '' {
        app.set_status(400)
        response := ApiResponse[Task]{
            success: false
            data:    Task{}
            message: 'Title is required'
        }
        return app.json(response)
    }
    
    now := time.now().format()
    new_task := Task{
        id:          app.next_id
        title:       request.title
        description: request.description
        completed:   false
        created_at:  now
        updated_at:  now
    }
    
    app.tasks << new_task
    app.next_id++
    
    app.set_status(201)
    response := ApiResponse[Task]{
        success: true
        data:    new_task
        message: 'Task created successfully'
    }
    return app.json(response)
}

// Update task
['/api/tasks/:id'; put]
pub fn (mut app App) update_task() vweb.Result {
    task_id := app.params['id'].int()
    
    request := json.decode(UpdateTaskRequest, app.req.data) or {
        app.set_status(400)
        response := ApiResponse[Task]{
            success: false
            data:    Task{}
            message: 'Invalid JSON data'
        }
        return app.json(response)
    }
    
    for i, mut task in app.tasks {
        if task.id == task_id {
            app.tasks[i].title = request.title
            app.tasks[i].description = request.description  
            app.tasks[i].completed = request.completed
            app.tasks[i].updated_at = time.now().format()
            
            response := ApiResponse[Task]{
                success: true
                data:    app.tasks[i]
                message: 'Task updated successfully'
            }
            return app.json(response)
        }
    }
    
    app.set_status(404)
    response := ApiResponse[Task]{
        success: false
        data:    Task{}
        message: 'Task not found'
    }
    return app.json(response)
}

// Delete task
['/api/tasks/:id'; delete]
pub fn (mut app App) delete_task() vweb.Result {
    task_id := app.params['id'].int()
    
    for i, task in app.tasks {
        if task.id == task_id {
            deleted_task := app.tasks[i]
            app.tasks.delete(i)
            
            response := ApiResponse[Task]{
                success: true
                data:    deleted_task
                message: 'Task deleted successfully'
            }
            return app.json(response)
        }
    }
    
    app.set_status(404)
    response := ApiResponse[Task]{
        success: false
        data:    Task{}
        message: 'Task not found'
    }
    return app.json(response)
}
```

### Multi-threaded File Processor

```v
import os
import time
import sync
import crypto.md5
import crypto.sha256

struct FileProcessor {
mut:
    input_dir     string
    output_dir    string
    num_workers   int
    processed     int
    total_files   int
    start_time    time.Time
    mutex         &sync.Mutex = sync.new_mutex()
    wg            &sync.WaitGroup = sync.new_waitgroup()
}

struct ProcessJob {
    file_path   string
    output_path string
}

struct ProcessResult {
    file_path string
    success   bool
    error     string
    file_size i64
    checksum  string
    duration  time.Duration
}

fn (mut processor FileProcessor) process_files() ! {
    if !os.exists(processor.input_dir) {
        return error('Input directory does not exist')
    }
    
    os.mkdir_all(processor.output_dir) or {
        return error('Failed to create output directory: ${err}')
    }
    
    // Get all files to process
    files := processor.get_all_files(processor.input_dir) or {
        return error('Failed to scan input directory: ${err}')
    }
    
    processor.total_files = files.len
    processor.start_time = time.now()
    
    println('Found ${processor.total_files} files to process')
    println('Using ${processor.num_workers} workers')
    
    // Create job and result channels
    jobs := chan ProcessJob{cap: 100}
    results := chan ProcessResult{cap: 100}
    
    // Start workers
    for i in 0 .. processor.num_workers {
        processor.wg.add(1)
        spawn processor.worker(i + 1, jobs, results)
    }
    
    // Start result collector
    spawn processor.collect_results(results)
    
    // Send jobs to workers
    spawn fn [files, jobs, processor] () {
        for file_path in files {
            relative_path := file_path.replace(processor.input_dir + '/', '')
            output_path := '${processor.output_dir}/${relative_path}.processed'
            
            jobs <- ProcessJob{
                file_path:   file_path
                output_path: output_path
            }
        }
        jobs.close()
    }()
    
    // Wait for all workers to complete
    processor.wg.wait()
    results.close()
    
    duration := time.now() - processor.start_time
    println('\nProcessing completed in ${duration}')
    println('Total files processed: ${processor.processed}/${processor.total_files}')
}

fn (processor FileProcessor) get_all_files(dir string) ?[]string {
    mut files := []string{}
    
    entries := os.ls(dir) or { return err }
    
    for entry in entries {
        full_path := '${dir}/${entry}'
        
        if os.is_dir(full_path) {
            // Recursively get files from subdirectories
            sub_files := processor.get_all_files(full_path) or { continue }
            files << sub_files
        } else if os.is_file(full_path) {
            files << full_path
        }
    }
    
    return files
}

fn (mut processor FileProcessor) worker(id int, jobs <-chan ProcessJob, results chan<- ProcessResult) {
    defer { processor.wg.done() }
    
    for {
        job := <-jobs or {
            println('Worker ${id}: No more jobs')
            break
        }
        
        start_time := time.now()
        result := processor.process_file(job)
        result.duration = time.now() - start_time
        
        results <- result
    }
}

fn (processor FileProcessor) process_file(job ProcessJob) ProcessResult {
    // Read input file
    content := os.read_file(job.file_path) or {
        return ProcessResult{
            file_path: job.file_path
            success:   false
            error:     'Failed to read file: ${err}'
        }
    }
    
    // Create output directory if needed
    output_dir := os.dir(job.output_path)
    os.mkdir_all(output_dir) or {
        return ProcessResult{
            file_path: job.file_path
            success:   false
            error:     'Failed to create output directory: ${err}'
        }
    }
    
    // Process content (example: add metadata and compute checksum)
    checksum := sha256.sum(content.bytes()).hex()
    
    processed_content := '// File: ${job.file_path}
// Processed: ${time.now().format()}
// Size: ${content.len} bytes
// SHA256: ${checksum}
// ---

${content}'
    
    // Write processed file
    os.write_file(job.output_path, processed_content) or {
        return ProcessResult{
            file_path: job.file_path
            success:   false
            error:     'Failed to write output file: ${err}'
        }
    }
    
    return ProcessResult{
        file_path: job.file_path
        success:   true
        file_size: content.len
        checksum:  checksum
    }
}

fn (mut processor FileProcessor) collect_results(results <-chan ProcessResult) {
    mut success_count := 0
    mut error_count := 0
    
    for {
        result := <-results or {
            break
        }
        
        processor.mutex.@lock()
        processor.processed++
        
        if result.success {
            success_count++
            if processor.processed % 10 == 0 || processor.processed == processor.total_files {
                progress := f32(processor.processed) / f32(processor.total_files) * 100
                println('Progress: ${progress:.1f}% (${processor.processed}/${processor.total_files})')
            }
        } else {
            error_count++
            eprintln('Error processing ${result.file_path}: ${result.error}')
        }
        processor.mutex.unlock()
    }
    
    println('\nFinal results:')
    println('Successful: ${success_count}')
    println('Errors: ${error_count}')
}

fn main() {
    mut input_dir := './input'
    mut output_dir := './output'
    mut num_workers := 4
    
    // Parse command line arguments
    for i, arg in os.args {
        match arg {
            '--input', '-i' {
                if i + 1 < os.args.len {
                    input_dir = os.args[i + 1]
                }
            }
            '--output', '-o' {
                if i + 1 < os.args.len {
                    output_dir = os.args[i + 1]
                }
            }
            '--workers', '-w' {
                if i + 1 < os.args.len {
                    num_workers = os.args[i + 1].int()
                }
            }
            '--help', '-h' {
                println('File Processor - Process files with multiple workers')
                println('Usage: file_processor [options]')
                println('Options:')
                println('  --input, -i     Input directory (default: ./input)')
                println('  --output, -o    Output directory (default: ./output)')
                println('  --workers, -w   Number of workers (default: 4)')
                println('  --help, -h      Show this help')
                return
            }
            else {}
        }
    }
    
    mut processor := FileProcessor{
        input_dir:   input_dir
        output_dir:  output_dir
        num_workers: num_workers
    }
    
    processor.process_files() or {
        eprintln('Processing failed: ${err}')
        exit(1)
    }
}
```
```
documentation for a list of common modules and their public symbols.

By default, you have to specify the module prefix every time you call an external function.
This may seem verbose at first, but it makes code much more readable
and easier to understand - it's always clear which function from
which module is being called. This is especially useful in large code bases.

Cyclic module imports are not allowed, like in Go.

### Selective imports

You can also import specific functions and types from modules directly:

```v
import os { input }

fn main() {
	// read text from stdin
	name := input('Enter your name: ')
	println('Hello, ${name}!')
}
```

> [!NOTE]
> This will import the module as well. Also, this is not allowed for
> constants - they must always be prefixed.

You can import several specific symbols at once:

```v
import os { input, user_os }

name := input('Enter your name: ')
println('Name: ${name}')
current_os := user_os()
println('Your OS is ${current_os}.')
```
### Module hierarchy

> [!NOTE]
> This section is valid when .v files are not in the project's root directory.

Modules names in .v files, must match the name of their directory.

A .v file `./abc/source.v` must start with `module abc`. All .v files in this directory
belong to the same module `abc`. They should also start with `module abc`.

If you have `abc/def/`, and .v files in both folders, you can `import abc`, but you will have
to `import abc.def` too, to get to the symbols in the subfolder. It is independent.

In `module name` statement, name never repeats directory's hierarchy, but only its directory.
So in `abc/def/source.v` the first line will be `module def`, and not `module abc.def`.

`import module_name` statements must respect file hierarchy, you cannot `import def`, only
`abc.def`

Referring to a module symbol such as a function or const, only needs module name as prefix:

```v ignore
module def

// func is a dummy example function.
pub fn func() {
	println('func')
}
```

can be called like this:

```v ignore
module main

import def

fn main() {
	def.func()
}
```

A function, located in `abc/def/source.v`, is called with `def.func()`, not `abc.def.func()`

This always implies a *single prefix*, whatever sub-module depth. This behavior flattens
modules/sub-modules hierarchy. Should you have two modules with the same name in different
directories, then you should use Module import aliasing (see below).

### Module import aliasing

Any imported module name can be aliased using the `as` keyword:

> [!NOTE]
> This example will not compile unless you have created `mymod/sha256/somename.v`
> (submodule names are determined by their path, not by the names of the .v file(s) in them).

```v failcompile
import crypto.sha256
import mymod.sha256 as mysha256

fn main() {
	v_hash := sha256.sum('hi'.bytes()).hex()
	my_hash := mysha256.sum('hi'.bytes()).hex()
	assert my_hash == v_hash
}
```

You cannot alias an imported function or type.
However, you _can_ redeclare a type.

```v
import time
import math

type MyTime = time.Time

fn (mut t MyTime) century() int {
	return int(1.0 + math.trunc(f64(t.year) * 0.009999794661191))
}

fn main() {
	mut my_time := MyTime{
		year:  2020
		month: 12
		day:   25
	}
	println(time.new(my_time).utc_string())
	println('Century: ${my_time.century()}')
}
```

## Statements & expressions

### If

```v
a := 10
b := 20
if a < b {
	println('${a} < ${b}')
} else if a > b {
	println('${a} > ${b}')
} else {
	println('${a} == ${b}')
}
```

`if` statements are pretty straightforward and similar to most other languages.
Unlike other C-like languages,
there are no parentheses surrounding the condition and the braces are always required.

#### `If` expressions
Unlike C, V does not have a ternary operator, that would allow you to do: `x = c ? 1 : 2` .
Instead, it has a bit more verbose, but also clearer to read, ability to use `if` as an
expression. The direct translation in V of the ternary construct above, assuming `c` is a
boolean condition, would be: `x = if c { 1 } else { 2 }`.

Here is another example:
```v
num := 777
s := if num % 2 == 0 { 'even' } else { 'odd' }
println(s)
// "odd"
```

You can use multiple statements in each of the branches of an `if` expression, followed by a final
value, that will become the value of the entire `if` expression, when it takes that branch:
```v
n := arguments().len
x := if n > 2 {
	dump(arguments())
	42
} else {
	println('something else')
	100
}
dump(x)
```

#### `If` unwrapping
Anywhere you can use `or {}`, you can also use "if unwrapping". This binds the unwrapped value
of an expression to a variable when that expression is not none nor an error.

```v
m := {
	'foo': 'bar'
}

// handle missing keys
if v := m['foo'] {
	println(v) // bar
} else {
	println('not found')
}
```

```v
fn res() !int {
	return 42
}

// functions that return a result type
if v := res() {
	println(v)
}
```

```v
struct User {
	name string
}

arr := [User{'John'}]

// if unwrapping with assignment of a variable
u_name := if v := arr[0] {
	v.name
} else {
	'Unnamed'
}
println(u_name) // John
```

#### Type checks and casts

You can check the current type of a sum type using `is` and its negated form `!is`.

You can do it either in an `if`:

```v cgen
struct Abc {
	val string
}

struct Xyz {
	foo string
}

type Alphabet = Abc | Xyz

x := Alphabet(Abc{'test'}) // sum type
if x is Abc {
	// x is automatically cast to Abc and can be used here
	println(x)
}
if x !is Abc {
	println('Not Abc')
}
```

or using `match`:

```v oksyntax
match x {
	Abc {
		// x is automatically cast to Abc and can be used here
		println(x)
	}
	Xyz {
		// x is automatically cast to Xyz and can be used here
		println(x)
	}
}
```

This works also with struct fields:

```v
struct MyStruct {
	x int
}

struct MyStruct2 {
	y string
}

type MySumType = MyStruct | MyStruct2

struct Abc {
	bar MySumType
}

x := Abc{
	bar: MyStruct{123} // MyStruct will be converted to MySumType type automatically
}
if x.bar is MyStruct {
	// x.bar is automatically cast
	println(x.bar)
} else if x.bar is MyStruct2 {
	new_var := x.bar as MyStruct2
	// ... or you can use `as` to create a type cast an alias manually:
	println(new_var)
}
match x.bar {
	MyStruct {
		// x.bar is automatically cast
		println(x.bar)
	}
	else {}
}
```

Mutable variables can change, and doing a cast would be unsafe.
However, sometimes it's useful to type cast despite mutability.
In such cases the developer must mark the expression with the `mut` keyword
to tell the compiler that they know what they're doing.

It works like this:

```v oksyntax
mut x := MySumType(MyStruct{123})
if mut x is MyStruct {
	// x is cast to MyStruct even if it's mutable
	// without the mut keyword that wouldn't work
	println(x)
}
// same with match
match mut x {
	MyStruct {
		// x is cast to MyStruct even if it's mutable
		// without the mut keyword that wouldn't work
		println(x)
	}
}
```

### Match

```v
os := 'windows'
print('V is running on ')
match os {
	'darwin' { println('macOS.') }
	'linux' { println('Linux.') }
	else { println(os) }
}
```

A match statement is a shorter way to write a sequence of `if - else` statements.
When a matching branch is found, the following statement block will be run.
The else branch will be run when no other branches match.

```v
number := 2
s := match number {
	1 { 'one' }
	2 { 'two' }
	else { 'many' }
}
```

A match statement can also to be used as an `if - else if - else` alternative:

```v
match true {
	2 > 4 { println('if') }
	3 == 4 { println('else if') }
	2 == 2 { println('else if2') }
	else { println('else') }
}
// 'else if2' should be printed
```

or as an `unless` alternative: [unless Ruby](https://www.tutorialspoint.com/ruby/ruby_if_else.htm)

```v
match false {
	2 > 4 { println('if') }
	3 == 4 { println('else if') }
	2 == 2 { println('else if2') }
	else { println('else') }
}
// 'if' should be printed
```

A match expression returns the value of the final expression from the matching branch.

```v
enum Color {
	red
	blue
	green
}

fn is_red_or_blue(c Color) bool {
	return match c {
		.red, .blue { true } // comma can be used to test multiple values
		.green { false }
	}
}
```

A match statement can also be used to branch on the variants of an `enum`
by using the shorthand `.variant_here` syntax. An `else` branch is not allowed
when all the branches are exhaustive.

```v
c := `v`
typ := match c {
	`0`...`9` { 'digit' }
	`A`...`Z` { 'uppercase' }
	`a`...`z` { 'lowercase' }
	else { 'other' }
}
println(typ)
// 'lowercase'
```

A match statement also can match the variant types of a `sumtype`. Note that
in that case, the match is exhaustive, since all variant types are mentioned
explicitly, so there is no need for an `else{}` branch.

```v nofmt
struct Dog {}
struct Cat {}
struct Veasel {}
type Animal = Dog | Cat | Veasel
a := Animal(Veasel{})
match a {
	Dog { println('Bay') }
	Cat { println('Meow') }
	Veasel { println('Vrrrrr-eeee') } // see: https://www.youtube.com/watch?v=qTJEDyj2N0Q
}
```

You can also use ranges as `match` patterns. If the value falls within the range
of a branch, that branch will be executed.

Note that the ranges use `...` (three dots) rather than `..` (two dots). This is
because the range is *inclusive* of the last element, rather than exclusive
(as `..` ranges are). Using `..` in a match branch will throw an error.

```v
const start = 1

const end = 10

c := 2
num := match c {
	start...end {
		1000
	}
	else {
		0
	}
}
println(num)
// 1000
```

Constants can also be used in the range branch expressions.

> [!NOTE]
> `match` as an expression is not usable in `for` loop and `if` statements.

### In operator

`in` allows to check whether an array or a map contains an element.
To do the opposite, use `!in`.

```v
nums := [1, 2, 3]
println(1 in nums) // true
println(4 !in nums) // true
```

> [!NOTE]
> `in` checks if map contains a key, not a value.

```v
m := {
	'one': 1
	'two': 2
}

println('one' in m) // true
println('three' !in m) // true
```

It's also useful for writing boolean expressions that are clearer and more compact:

```v
enum Token {
	plus
	minus
	div
	mult
}

struct Parser {
	token Token
}

parser := Parser{}
if parser.token == .plus || parser.token == .minus || parser.token == .div || parser.token == .mult {
	// ...
}
if parser.token in [.plus, .minus, .div, .mult] {
	// ...
}
```

V optimizes such expressions,
so both `if` statements above produce the same machine code and no arrays are created.

### For loop

V has only one looping keyword: `for`, with several forms.

#### `for`/`in`

This is the most common form. You can use it with an array, map or
numeric range.

##### Array `for`

```v
numbers := [1, 2, 3, 4, 5]
for num in numbers {
	println(num)
}
names := ['Sam', 'Peter']
for i, name in names {
	println('${i}) ${name}')
	// Output: 0) Sam
	//         1) Peter
}
```

The `for value in arr` form is used for going through elements of an array.
If an index is required, an alternative form `for index, value in arr` can be used.

Note that the value is read-only.
If you need to modify the array while looping, you need to declare the element as mutable:

```v
mut numbers := [0, 1, 2]
for mut num in numbers {
	num++
}
println(numbers) // [1, 2, 3]
```

When an identifier is just a single underscore, it is ignored.

##### Custom iterators

Types that implement a `next` method returning an `Option` can be iterated
with a `for` loop.

```v
struct SquareIterator {
	arr []int
mut:
	idx int
}

fn (mut iter SquareIterator) next() ?int {
	if iter.idx >= iter.arr.len {
		return none
	}
	defer {
		iter.idx++
	}
	return iter.arr[iter.idx] * iter.arr[iter.idx]
}

nums := [1, 2, 3, 4, 5]
iter := SquareIterator{
	arr: nums
}
for squared in iter {
	println(squared)
}
```

The code above prints:

```
1
4
9
16
25
```

##### Map `for`

```v
m := {
	'one': 1
	'two': 2
}
for key, value in m {
	println('${key} -> ${value}')
	// Output: one -> 1
	//         two -> 2
}
```

Either key or value can be ignored by using a single underscore as the identifier.

```v
m := {
	'one': 1
	'two': 2
}
// iterate over keys
for key, _ in m {
	println(key)
	// Output: one
	//         two
}
// iterate over values
for _, value in m {
	println(value)
	// Output: 1
	//         2
}
```

##### Range `for`

```v
// Prints '01234'
for i in 0 .. 5 {
	print(i)
}
```

`low..high` means an *exclusive* range, which represents all values
from `low` up to *but not including* `high`.

> [!NOTE]
> This exclusive range notation and zero-based indexing follow principles of
logical consistency and error reduction. As Edsger W. Dijkstra outlines in
'Why Numbering Should Start at Zero'
([EWD831](https://www.cs.utexas.edu/users/EWD/transcriptions/EWD08xx/EWD831.html)),
zero-based indexing aligns the index with the preceding elements in a sequence,
simplifying handling and minimizing errors, especially with adjacent subsequences.
This logical and efficient approach shapes our language design, emphasizing clarity
and reducing confusion in programming.

#### Condition `for`

```v
mut sum := 0
mut i := 0
for i <= 100 {
	sum += i
	i++
}
println(sum) // "5050"
```

This form of the loop is similar to `while` loops in other languages.
The loop will stop iterating once the boolean condition evaluates to false.
Again, there are no parentheses surrounding the condition, and the braces are always required.

#### Bare `for`

```v
mut num := 0
for {
	num += 2
	if num >= 10 {
		break
	}
}
println(num) // "10"
```

The condition can be omitted, resulting in an infinite loop.

#### C `for`

```v
for i := 0; i < 10; i += 2 {
	// Don't print 6
	if i == 6 {
		continue
	}
	println(i)
}
```

Finally, there's the traditional C style `for` loop. It's safer than the `while` form
because with the latter it's easy to forget to update the counter and get
stuck in an infinite loop.

Here `i` doesn't need to be declared with `mut` since it's always going to be mutable by definition.

#### Labelled break & continue

`break` and `continue` control the innermost `for` loop by default.
You can also use `break` and `continue` followed by a label name to refer to an outer `for`
loop:

```v
outer: for i := 4; true; i++ {
	println(i)
	for {
		if i < 7 {
			continue outer
		} else {
			break outer
		}
	}
}
```

The label must immediately precede the outer loop.
The above code prints:

```
4
5
6
7
```

### Defer

A defer statement defers the execution of a block of statements
until the surrounding function returns.

```v
import os

fn read_log() {
	mut ok := false
	mut f := os.open('log.txt') or { panic(err) }
	defer {
		f.close()
	}
	// ...
	if !ok {
		// defer statement will be called here, the file will be closed
		return
	}
	// ...
	// defer statement will be called here, the file will be closed
}
```

If the function returns a value the `defer` block is executed *after* the return
expression is evaluated:

```v
import os

enum State {
	normal
	write_log
	return_error
}

// write log file and return number of bytes written

fn write_log(s State) !int {
	mut f := os.create('log.txt')!
	defer {
		f.close()
	}
	if s == .write_log {
		// `f.close()` will be called after `f.write()` has been
		// executed, but before `write_log()` finally returns the
		// number of bytes written to `main()`
		return f.writeln('This is a log file')
	} else if s == .return_error {
		// the file will be closed after the `error()` function
		// has returned - so the error message will still report
		// it as open
		return error('nothing written; file open: ${f.is_opened}')
	}
	// the file will be closed here, too
	return 0
}

fn main() {
	n := write_log(.return_error) or {
		println('Error: ${err}')
		0
	}
	println('${n} bytes written')
}
```

To access the result of the function inside a `defer` block the `$res()` expression can be used.
`$res()` is only used when a single value is returned, while on multi-return the `$res(idx)`
is parameterized.

```v ignore
fn (mut app App) auth_middleware() bool {
	defer {
		if !$res() {
			app.response.status_code = 401
			app.response.body = 'Unauthorized'
		}
	}
	header := app.get_header('Authorization')
	if header == '' {
		return false
	}
	return true
}

fn (mut app App) auth_with_user_middleware() (bool, string) {
	defer {
		if !$res(0) {
			app.response.status_code = 401
			app.response.body = 'Unauthorized'
		} else {
			app.user = $res(1)
		}
	}
	header := app.get_header('Authorization')
	if header == '' {
		return false, ''
	}
	return true, 'TestUser'
}
```

### Goto

V allows unconditionally jumping to a label with `goto`. The label name must be contained
within the same function as the `goto` statement. A program may `goto` a label outside
or deeper than the current scope. `goto` allows jumping past variable initialization or
jumping back to code that accesses memory that has already been freed, so it requires
`unsafe`.

```v ignore
if x {
	// ...
	if y {
		unsafe {
			goto my_label
		}
	}
	// ...
}
my_label:
```

`goto` should be avoided, particularly when `for` can be used instead.
[Labelled break/continue](#labelled-break--continue) can be used to break out of
a nested loop, and those do not risk violating memory-safety.

## Structs

```v
struct Point {
	x int
	y int
}

mut p := Point{
	x: 10
	y: 20
}
println(p.x) // Struct fields are accessed using a dot
// Alternative literal syntax
p = Point{10, 20}
assert p.x == 10
```

Struct fields can re-use reserved keywords:

```v
struct Employee {
	type string
	name string
}

employee := Employee{
	type: 'FTE'
	name: 'John Doe'
}
println(employee.type)
```

### Heap structs

Structs are allocated on the stack. To allocate a struct on the heap
and get a [reference](#references) to it, use the `&` prefix:

```v
struct Point {
	x int
	y int
}

p := &Point{10, 10}
// References have the same syntax for accessing fields
println(p.x)
```

The type of `p` is `&Point`. It's a [reference](#references) to `Point`.
References are similar to Go pointers and C++ references.

```v
struct Foo {
mut:
	x int
}

fa := Foo{1}
mut a := fa
a.x = 2
assert fa.x == 1
assert a.x == 2

// fb := Foo{ 1 }
// mut b := &fb  // error: `fb` is immutable, cannot have a mutable reference to it
// b.x = 2

mut fc := Foo{1}
mut c := &fc
c.x = 2
assert fc.x == 2
assert c.x == 2
println(fc) // Foo{ x: 2 }
println(c) // &Foo{ x: 2 } // Note `&` prefixed.
```

see also [Stack and Heap](#stack-and-heap)

### Default field values

```v
struct Foo {
	n   int    // n is 0 by default
	s   string // s is '' by default
	a   []int  // a is `[]int{}` by default
	pos int = -1 // custom default value
}
```

All struct fields are zeroed by default during the creation of the struct.
Array and map fields are allocated.
In case of reference value, see [here](#structs-with-reference-fields).

It's also possible to define custom default values.

### Required fields

```v
struct Foo {
	n int @[required]
}
```

You can mark a struct field with the `[required]` [attribute](#attributes), to tell V that
that field must be initialized when creating an instance of that struct.

This example will not compile, since the field `n` isn't explicitly initialized:

```v failcompile
_ = Foo{}
```

<a id='short-struct-initialization-syntax'></a>

### Short struct literal syntax

```v
struct Point {
	x int
	y int
}

mut p := Point{
	x: 10
	y: 20
}
p = Point{
	x: 30
	y: 4
}
assert p.y == 4
//
// array: first element defines type of array
points := [Point{10, 20}, Point{20, 30}, Point{40, 50}]
println(points) // [Point{x: 10, y: 20}, Point{x: 20, y: 30}, Point{x: 40,y: 50}]
```

Omitting the struct name also works for returning a struct literal or passing one
as a function argument.

### Struct update syntax

V makes it easy to return a modified version of an object:

```v
struct User {
	name          string
	age           int
	is_registered bool
}

fn register(u User) User {
	return User{
		...u
		is_registered: true
	}
}

mut user := User{
	name: 'abc'
	age:  23
}
user = register(user)
println(user)
```

### Trailing struct literal arguments

V doesn't have default function arguments or named arguments, for that trailing struct
literal syntax can be used instead:

```v
@[params]
struct ButtonConfig {
	text        string
	is_disabled bool
	width       int = 70
	height      int = 20
}

struct Button {
	text   string
	width  int
	height int
}

fn new_button(c ButtonConfig) &Button {
	return &Button{
		width:  c.width
		height: c.height
		text:   c.text
	}
}

button := new_button(text: 'Click me', width: 100)
// the height is unset, so it's the default value
assert button.height == 20
```

As you can see, both the struct name and braces can be omitted, instead of:

```v oksyntax nofmt
new_button(ButtonConfig{text:'Click me', width:100})
```

This only works for functions that take a struct for the last argument.

> [!NOTE]
> Note the `[params]` tag is used to tell V, that the trailing struct parameter
> can be omitted *entirely*, so that you can write `button := new_button()`.
> Without it, you have to specify *at least* one of the field names, even if it
> has its default value, otherwise the compiler will produce this error message,
> when you call the function with no parameters:
> `error: expected 1 arguments, but got 0`.

### Access modifiers

Struct fields are private and immutable by default (making structs immutable as well).
Their access modifiers can be changed with
`pub` and `mut`. In total, there are 5 possible options:

```v
struct Foo {
	a int // private immutable (default)
mut:
	b int // private mutable
	c int // (you can list multiple fields with the same access modifier)
pub:
	d int // public immutable (readonly)
pub mut:
	e int // public, but mutable only in parent module
__global:
	// (not recommended to use, that's why the 'global' keyword starts with __)
	f int // public and mutable both inside and outside parent module
}
```

Private fields are available only inside the same [module](#modules), any attempt
to directly access them from another module will cause an error during compilation.
Public immutable fields are readonly everywhere.

### Anonymous structs

V supports anonymous structs: structs that don't have to be declared separately
with a struct name.

```v
struct Book {
	author struct {
		name string
		age  int
	}

	title string
}

book := Book{
	author: struct {
		name: 'Samantha Black'
		age:  24
	}
}
assert book.author.name == 'Samantha Black'
assert book.author.age == 24
```

### Static type methods

V now supports static type methods like `User.new()`. These are defined on a struct via
`fn [Type name].[function name]` and allow to organize all functions related to a struct:

```v oksyntax
struct User {}

fn User.new() User {
	return User{}
}

user := User.new()
```

This is an alternative to factory functions like `fn new_user() User {}` and should be used
instead.

> [!NOTE]
> Note, that these are not constructors, but simple functions. V doesn't have constructors or
> classes.

### `[noinit]` structs

V supports `[noinit]` structs, which are structs that cannot be initialised outside the module
they are defined in. They are either meant to be used internally or they can be used externally
through _factory functions_.

For an example, consider the following source in a directory `sample`:

```v oksyntax
module sample

@[noinit]
pub struct Information {
pub:
	data string
}

pub fn new_information(data string) !Information {
	if data.len == 0 || data.len > 100 {
		return error('data must be between 1 and 100 characters')
	}
	return Information{
		data: data
	}
}
```

Note that `new_information` is a _factory_ function. Now when we want to use this struct
outside the module:

```v okfmt
import sample

fn main() {
	// This doesn't work when the [noinit] attribute is present:
	// info := sample.Information{
	// 	data: 'Sample information.'
	// }

	// Use this instead:
	info := sample.new_information('Sample information.')!

	println(info)
}
```

### Methods

```v
struct User {
	age int
}

fn (u User) can_register() bool {
	return u.age > 16
}

user := User{
	age: 10
}
println(user.can_register()) // "false"
user2 := User{
	age: 20
}
println(user2.can_register()) // "true"
```

V doesn't have classes, but you can define methods on types.
A method is a function with a special receiver argument.
The receiver appears in its own argument list between the `fn` keyword and the method name.
Methods must be in the same module as the receiver type.

In this example, the `can_register` method has a receiver of type `User` named `u`.
The convention is not to use receiver names like `self` or `this`,
but a short, preferably one letter long, name.

### Embedded structs

V supports embedded structs.

```v
struct Size {
mut:
	width  int
	height int
}

fn (s &Size) area() int {
	return s.width * s.height
}

struct Button {
	Size
	title string
}
```

With embedding, the struct `Button` will automatically get all the fields and methods from
the struct `Size`, which allows you to do:

```v oksyntax
mut button := Button{
	title:  'Click me'
	height: 2
}

button.width = 3
assert button.area() == 6
assert button.Size.area() == 6
print(button)
```

output :

```
Button{
    Size: Size{
        width: 3
        height: 2
    }
    title: 'Click me'
}
```

Unlike inheritance, you cannot type cast between structs and embedded structs
(the embedding struct can also have its own fields, and it can also embed multiple structs).

If you need to access embedded structs directly, use an explicit reference like `button.Size`.

Conceptually, embedded structs are similar to [mixin](https://en.wikipedia.org/wiki/Mixin)s
in OOP, *NOT* base classes.

You can also initialize an embedded struct:

```v oksyntax
mut button := Button{
	Size: Size{
		width:  3
		height: 2
	}
}
```

or assign values:

```v oksyntax
button.Size = Size{
	width:  4
	height: 5
}
```

If multiple embedded structs have methods or fields with the same name, or if methods or fields
with the same name are defined in the struct, you can call methods or assign to variables in
the embedded struct like `button.Size.area()`.
When you do not specify the embedded struct name, the method of the outermost struct will be
targeted.

## Unions

Just like structs, unions support embedding.

```v
struct Rgba32_Component {
	r u8
	g u8
	b u8
	a u8
}

union Rgba32 {
	Rgba32_Component
	value u32
}

clr1 := Rgba32{
	value: 0x008811FF
}

clr2 := Rgba32{
	Rgba32_Component: Rgba32_Component{
		a: 128
	}
}

sz := sizeof(Rgba32)
unsafe {
	println('Size: ${sz}B,clr1.b: ${clr1.b},clr2.b: ${clr2.b}')
}
```

Output: `Size: 4B, clr1.b: 136, clr2.b: 0`

Union member access must be performed in an `unsafe` block.

> [!NOTE]
> Embedded struct arguments are not necessarily stored in the order listed.

## Functions 2

### Immutable function args by default

In V function arguments are immutable by default, and mutable args have to be
marked on call.

Since there are also no globals, that means that the return values of the functions,
are a function of their arguments only, and their evaluation has no side effects
(unless the function uses I/O).

Function arguments are immutable by default, even when [references](#references) are passed.

> [!NOTE]
> However, V is not a purely functional language.

There is a compiler flag to enable global variables (`-enable-globals`), but this is
intended for low-level applications like kernels and drivers.

### Mutable arguments

It is possible to modify function arguments by declaring them with the keyword `mut`:

```v
struct User {
	name string
mut:
	is_registered bool
}

fn (mut u User) register() {
	u.is_registered = true
}

mut user := User{}
println(user.is_registered) // "false"
user.register()
println(user.is_registered) // "true"
```

In this example, the receiver (which is just the first argument) is explicitly marked as mutable,
so `register()` can change the user object. The same works with non-receiver arguments:

```v
fn multiply_by_2(mut arr []int) {
	for i in 0 .. arr.len {
		arr[i] *= 2
	}
}

mut nums := [1, 2, 3]
multiply_by_2(mut nums)
println(nums)
// "[2, 4, 6]"
```

Note that you have to add `mut` before `nums` when calling this function. This makes
it clear that the function being called will modify the value.

It is preferable to return values instead of modifying arguments,
e.g. `user = register(user)` (or `user.register()`) instead of `register(mut user)`.
Modifying arguments should only be done in performance-critical parts of your application
to reduce allocations and copying.

For this reason V doesn't allow the modification of arguments with primitive types (e.g. integers).
Only more complex types such as arrays and maps may be modified.

### Variable number of arguments
V supports functions that receive an arbitrary, variable amounts of arguments, denoted with the
`...` prefix.
Below, `a ...int` refers to an arbitrary amount of parameters that will be collected
into an array named `a`.

```v
fn sum(a ...int) int {
	mut total := 0
	for x in a {
		total += x
	}
	return total
}

println(sum()) // 0
println(sum(1)) // 1
println(sum(2, 3)) // 5
// using array decomposition
a := [2, 3, 4]
println(sum(...a)) // <-- using prefix ... here. output: 9
b := [5, 6, 7]
println(sum(...b)) // output: 18
```

### Anonymous & higher order functions

```v
fn sqr(n int) int {
	return n * n
}

fn cube(n int) int {
	return n * n * n
}

fn run(value int, op fn (int) int) int {
	return op(value)
}

fn main() {
	// Functions can be passed to other functions
	println(run(5, sqr)) // "25"
	// Anonymous functions can be declared inside other functions:
	double_fn := fn (n int) int {
		return n + n
	}
	println(run(5, double_fn)) // "10"
	// Functions can be passed around without assigning them to variables:
	res := run(5, fn (n int) int {
		return n + n
	})
	println(res) // "10"
	// You can even have an array/map of functions:
	fns := [sqr, cube]
	println(fns[0](10)) // "100"
	fns_map := {
		'sqr':  sqr
		'cube': cube
	}
	println(fns_map['cube'](2)) // "8"
}
```

### Closures

V supports closures too.
This means that anonymous functions can inherit variables from the scope they were created in.
They must do so explicitly by listing all variables that are inherited.

```v oksyntax
my_int := 1
my_closure := fn [my_int] () {
	println(my_int)
}
my_closure() // prints 1
```

Inherited variables are copied when the anonymous function is created.
This means that if the original variable is modified after the creation of the function,
the modification won't be reflected in the function.

```v oksyntax
mut i := 1
func := fn [i] () int {
	return i
}
println(func() == 1) // true
i = 123
println(func() == 1) // still true
```

However, the variable can be modified inside the anonymous function.
The change won't be reflected outside, but will be in the later function calls.

```v oksyntax
fn new_counter() fn () int {
	mut i := 0
	return fn [mut i] () int {
		i++
		return i
	}
}

c := new_counter()
println(c()) // 1
println(c()) // 2
println(c()) // 3
```

If you need the value to be modified outside the function, use a reference.

```v oksyntax
mut i := 0
mut ref := &i
print_counter := fn [ref] () {
	println(*ref)
}

print_counter() // 0
i = 10
print_counter() // 10
```

### Parameter evaluation order

The evaluation order of the parameters of function calls is *NOT* guaranteed.
Take for example the following program:

```v
fn f(a1 int, a2 int, a3 int) {
	dump(a1 + a2 + a3)
}

fn main() {
	f(dump(100), dump(200), dump(300))
}
```

V currently does not guarantee that it will print 100, 200, 300 in that order.
The only guarantee is that 600 (from the body of `f`) will be printed after all of them.

This *may* change in V 1.0 .

## References

```v
struct Foo {}

fn (foo Foo) bar_method() {
	// ...
}

fn bar_function(foo Foo) {
	// ...
}
```

If a function argument is immutable (like `foo` in the examples above)
V can pass it either by value or by reference. The compiler will decide,
and the developer doesn't need to think about it.

You no longer need to remember whether you should pass the struct by value
or by reference.

You can ensure that the struct is always passed by reference by
adding `&`:

```v
struct Foo {
	abc int
}

fn (foo &Foo) bar() {
	println(foo.abc)
}
```

`foo` is still immutable and can't be changed. For that,
`(mut foo Foo)` must be used.

In general, V's references are similar to Go pointers and C++ references.
For example, a generic tree structure definition would look like this:

```v
struct Node[T] {
	val   T
	left  &Node[T]
	right &Node[T]
}
```

To dereference a reference, use the `*` operator, just like in C.

## Constants

```v
const pi = 3.14
const world = '世界'

println(pi)
println(world)
```

Constants are declared with `const`. They can only be defined
at the module level (outside of functions).
Constant values can never be changed. You can also declare a single
constant separately:

```v
const e = 2.71828
```

V constants are more flexible than in most languages. You can assign more complex values:

```v
struct Color {
	r int
	g int
	b int
}

fn rgb(r int, g int, b int) Color {
	return Color{
		r: r
		g: g
		b: b
	}
}

const numbers = [1, 2, 3]
const red = Color{
	r: 255
	g: 0
	b: 0
}
// evaluate function call at compile time*
const blue = rgb(0, 0, 255)

println(numbers)
println(red)
println(blue)
```

\* WIP - for now function calls are evaluated at program start-up

Global variables are not normally allowed, so this can be really useful.

**Modules**

Constants can be made public with `pub const`:

```v oksyntax
module mymodule

pub const golden_ratio = 1.61803

fn calc() {
	println(golden_ratio)
}
```

The `pub` keyword is only allowed before the `const` keyword and cannot be used inside
a `const ( )` block.

Outside from module main all constants need to be prefixed with the module name.

### Required module prefix

When naming constants, `snake_case` must be used. In order to distinguish consts
from local variables, the full path to consts must be specified. For example,
to access the PI const, full `math.pi` name must be used both outside the `math`
module, and inside it. That restriction is relaxed only for the `main` module
(the one containing your `fn main()`), where you can use the unqualified name of
constants defined there, i.e. `numbers`, rather than `main.numbers`.

vfmt takes care of this rule, so you can type `println(pi)` inside the `math` module,
and vfmt will automatically update it to `println(math.pi)`.

<!--
Many people prefer all caps consts: `TOP_CITIES`. This wouldn't work
well in V, because consts are a lot more powerful than in other languages.
They can represent complex structures, and this is used quite often since there
are no globals:

```v oksyntax
println('Top cities: ${top_cities.filter(.usa)}')
```
-->

## Builtin functions

Some functions are builtin like `println`. Here is the complete list:

```v ignore
fn print(s string) // prints anything on stdout
fn println(s string) // prints anything and a newline on stdout

fn eprint(s string) // same as print(), but uses stderr
fn eprintln(s string) // same as println(), but uses stderr

fn exit(code int) // terminates the program with a custom error code
fn panic(s string) // prints a message and backtraces on stderr, and terminates the program with error code 1
fn print_backtrace() // prints backtraces on stderr
```

> [!NOTE]
> Although the `print` functions take a string, V accepts other printable types too.
> See below for details.

There is also a special built-in function called [`dump`](#dumping-expressions-at-runtime).

### println

`println` is a simple yet powerful builtin function, that can print anything:
strings, numbers, arrays, maps, structs.

```v
struct User {
	name string
	age  int
}

println(1) // "1"
println('hi') // "hi"
println([1, 2, 3]) // "[1, 2, 3]"
println(User{ name: 'Bob', age: 20 }) // "User{name:'Bob', age:20}"
```

See also [String interpolation](#string-interpolation).

<a id='custom-print-of-types'></a>

### Printing custom types

If you want to define a custom print value for your type, simply define a
`str() string` method:

```v
struct Color {
	r int
	g int
	b int
}

pub fn (c Color) str() string {
	return '{${c.r}, ${c.g}, ${c.b}}'
}

red := Color{
	r: 255
	g: 0
	b: 0
}
println(red)
```

### Dumping expressions at runtime

You can dump/trace the value of any V expression using `dump(expr)`.
For example, save this code sample as `factorial.v`, then run it with
`v run factorial.v`:

```v
fn factorial(n u32) u32 {
	if dump(n <= 1) {
		return dump(1)
	}
	return dump(n * factorial(n - 1))
}

fn main() {
	println(factorial(5))
}
```

You will get:

```
[factorial.v:2] n <= 1: false
[factorial.v:2] n <= 1: false
[factorial.v:2] n <= 1: false
[factorial.v:2] n <= 1: false
[factorial.v:2] n <= 1: true
[factorial.v:3] 1: 1
[factorial.v:5] n * factorial(n - 1): 2
[factorial.v:5] n * factorial(n - 1): 6
[factorial.v:5] n * factorial(n - 1): 24
[factorial.v:5] n * factorial(n - 1): 120
120
```

Note that `dump(expr)` will trace both the source location,
the expression itself, and the expression value.

## Modules

Every file in the root of a folder is part of the same module.
Simple programs don't need to specify module name, in which case it defaults to 'main'.

See [symbol visibility](#symbol-visibility), [Access modifiers](#access-modifiers).

### Create modules

V is a very modular language. Creating reusable modules is encouraged and is
quite easy to do.
To create a new module, create a directory with your module's name containing
.v files with code:

```shell
cd ~/code/modules
mkdir mymodule
vim mymodule/myfile.v
```

```v failcompile
// myfile.v
module mymodule

// To export a function we have to use `pub`
pub fn say_hi() {
	println('hello from mymodule!')
}
```
All items inside a module can be used between the files of a module regardless of whether or
not they are prefaced with the `pub` keyword.
```v failcompile
// myfile2.v
module mymodule

pub fn say_hi_and_bye() {
	say_hi() // from myfile.v
	println('goodbye from mymodule')
}
```

You can now use `mymodule` in your code:

```v failcompile
import mymodule

fn main() {
	mymodule.say_hi()
	mymodule.say_hi_and_bye()
}
```

* Module names should be short, under 10 characters.
* Module names must use `snake_case`.
* Circular imports are not allowed.
* You can have as many .v files in a module as you want.
* You can create modules anywhere.
* All modules are compiled statically into a single executable.

### Special considerations for project folders

For the top level project folder (the one, compiled with `v .`), and *only*
that folder, you can have several .v files, that may be mentioning different modules
with `module main`, `module abc` etc

This is to ease the prototyping workflow in that folder:
- you can start developing some new project with a single .v file
- split functionality as necessary to different .v files in the same folder
- when that makes logical sense to be further organised, put them into their own directory module.

Note that in ordinary modules, all .v files must start with `module name_of_folder`.

### `init` functions

If you want a module to automatically call some setup/initialization code when it is imported,
you can define a module `init` function:

```v
fn init() {
	// your setup code here ...
}
```

The `init` function cannot be public - it will be called automatically by V, *just once*, no matter
how many times the module was imported in your program. This feature is particularly useful for
initializing a C library.

### `cleanup` functions

If you want a module to automatically call some cleanup/deinitialization code, when your program
ends, you can define a module `cleanup` function:

```v
fn cleanup() {
	// your deinitialisation code here ...
}
```

Just like the `init` function, the `cleanup` function for a module cannot be public - it will be
called automatically, when your program ends, once per module, even if the module was imported
transitively by other modules several times, in the reverse order of the init calls.

## Type Declarations

### Type aliases

To define a new type `NewType` as an alias for `ExistingType`,
do `type NewType = ExistingType`.<br/>
This is a special case of a [sum type](#sum-types) declaration.

### Enums

An enum is a group of constant integer values, each having its own name,
whose values start at 0 and increase by 1 for each name listed.
For example:
```v
enum Color as u8 {
	red   // the default start value is 0
	green // the value is automatically incremented to 1
	blue  // the final value is now 2
}

mut color := Color.red
// V knows that `color` is a `Color`. No need to use `color = Color.green` here.
color = .green
println(color) // "green"
match color {
	.red { println('the color was red') }
	.green { println('the color was green') }
	.blue { println('the color was blue') }
}
println(int(color)) // prints 1
```

The enum type can be any integer type, but can be omitted, if it is `int`: `enum Color {`.

Enum match must be exhaustive or have an `else` branch.
This ensures that if a new enum field is added, it's handled everywhere in the code.

Enum fields can re-use reserved keywords:

```v
enum Color {
	none
	red
	green
	blue
}

color := Color.none
println(color)
```

Integers may be assigned to enum fields.

```v
enum Grocery {
	apple
	orange = 5
	pear
}

g1 := int(Grocery.apple)
g2 := int(Grocery.orange)
g3 := int(Grocery.pear)
println('Grocery IDs: ${g1}, ${g2}, ${g3}')
```

Output: `Grocery IDs: 0, 5, 6`.

Operations are not allowed on enum variables; they must be explicitly cast to `int`.

Enums can have methods, just like structs.

```v
enum Cycle {
	one
	two
	three
}

fn (c Cycle) next() Cycle {
	match c {
		.one {
			return .two
		}
		.two {
			return .three
		}
		.three {
			return .one
		}
	}
}

mut c := Cycle.one
for _ in 0 .. 10 {
	println(c)
	c = c.next()
}
```

Output:

```
one
two
three
one
two
three
one
two
three
one
```

Enums can be created from string or integer value and converted into string

```v
enum Cycle {
	one
	two = 2
	three
}

// Create enum from value
println(Cycle.from(10) or { Cycle.three })
println(Cycle.from('two')!)

// Convert an enum value to a string
println(Cycle.one.str())
```

Output:

```
three
two
one
```

### Function Types

You can use type aliases for naming specific function signatures - for
example:

```v
type Filter = fn (string) string
```

This works like any other type - for example, a function can accept an
argument of a function type:

```v
type Filter = fn (string) string

fn filter(s string, f Filter) string {
	return f(s)
}
```

V has duck-typing, so functions don't need to declare compatibility with
a function type - they just have to be compatible:

```v
fn uppercase(s string) string {
	return s.to_upper()
}

// now `uppercase` can be used everywhere where Filter is expected
```

Compatible functions can also be explicitly cast to a function type:

```v oksyntax
my_filter := Filter(uppercase)
```

The cast here is purely informational - again, duck-typing means that the
resulting type is the same without an explicit cast:

```v oksyntax
my_filter := uppercase
```

You can pass the assigned function as an argument:

```v oksyntax
println(filter('Hello world', my_filter)) // prints `HELLO WORLD`
```

And you could of course have passed it directly as well, without using a
local variable:

```v oksyntax
println(filter('Hello world', uppercase))
```

And this works with anonymous functions as well:

```v oksyntax
println(filter('Hello world', fn (s string) string {
	return s.to_upper()
}))
```

You can see the complete
[example here](https://github.com/vlang/v/tree/master/examples/function_types.v).

### Interfaces

```v
// interface-example.1
struct Dog {
	breed string
}

fn (d Dog) speak() string {
	return 'woof'
}

struct Cat {
	breed string
}

fn (c Cat) speak() string {
	return 'meow'
}

// unlike Go, but like TypeScript, V's interfaces can define both fields and methods.
interface Speaker {
	breed string
	speak() string
}

fn main() {
	dog := Dog{'Leonberger'}
	cat := Cat{'Siamese'}

	mut arr := []Speaker{}
	arr << dog
	arr << cat
	for item in arr {
		println('a ${item.breed} says: ${item.speak()}')
	}
}
```

#### Implement an interface

A type implements an interface by implementing its methods and fields.

An interface can have a `mut:` section. Implementing types will need
to have a `mut` receiver, for methods declared in the `mut:` section
of an interface.

```v
// interface-example.2
module main

interface Foo {
	write(string) string
}

// => the method signature of a type, implementing interface Foo should be:
// `fn (s Type) write(a string) string`

interface Bar {
mut:
	write(string) string
}

// => the method signature of a type, implementing interface Bar should be:
// `fn (mut s Type) write(a string) string`

struct MyStruct {}

// MyStruct implements the interface Foo, but *not* interface Bar
fn (s MyStruct) write(a string) string {
	return a
}

fn main() {
	s1 := MyStruct{}
	fn1(s1)
	// fn2(s1) -> compile error, since MyStruct does not implement Bar
}

fn fn1(s Foo) {
	println(s.write('Foo'))
}

// fn fn2(s Bar) { // does not match
//      println(s.write('Foo'))
// }
```

There is an **optional** `implements` keyword for explicit declaration
of intent, which applies to `struct` declarations.

```v
struct PathError implements IError {
	Error
	path string
}

fn (err PathError) msg() string {
	return 'Failed to open path: ${err.path}'
}

fn try_open(path string) ! {
	return PathError{
		path: path
	}
}

fn main() {
	try_open('/tmp') or { panic(err) }
}
```

#### Casting an interface

We can test the underlying type of an interface using dynamic cast operators.
> [!NOTE]
> Dynamic cast converts variable `s` into a pointer inside the `if` statements in this example:

```v oksyntax
// interface-example.3 (continued from interface-example.1)
interface Something {}

fn announce(s Something) {
	if s is Dog {
		println('a ${s.breed} dog') // `s` is automatically cast to `Dog` (smart cast)
	} else if s is Cat {
		println('a cat speaks ${s.speak()}')
	} else {
		println('something else')
	}
}

fn main() {
	dog := Dog{'Leonberger'}
	cat := Cat{'Siamese'}
	announce(dog)
	announce(cat)
}
```

```v
// interface-example.4
interface IFoo {
	foo()
}

interface IBar {
	bar()
}

// implements only IFoo
struct SFoo {}

fn (sf SFoo) foo() {}

// implements both IFoo and IBar
struct SFooBar {}

fn (sfb SFooBar) foo() {}

fn (sfb SFooBar) bar() {
	dump('This implements IBar')
}

fn main() {
	mut arr := []IFoo{}
	arr << SFoo{}
	arr << SFooBar{}

	for a in arr {
		dump(a)
		// In order to execute instances that implements IBar.
		if a is IBar {
			a.bar()
		}
	}
}
```

For more information, see [Dynamic casts](#dynamic-casts).

#### Interface method definitions

Also unlike Go, an interface can have its own methods, similar to how
structs can have their methods. These 'interface methods' do not have
to be implemented, by structs which implement that interface.
They are just a convenient way to write `i.some_function()` instead of
`some_function(i)`, similar to how struct methods can be looked at, as
a convenience for writing `s.xyz()` instead of `xyz(s)`.

> [!NOTE]
> This feature is NOT a "default implementation" like in C#.

For example, if a struct `cat` is wrapped in an interface `a`, that has
implemented a method with the same name `speak`, as a method implemented by
the struct, and you do `a.speak()`, *only* the interface method is called:

```v
interface Adoptable {}

fn (a Adoptable) speak() string {
	return 'adopt me!'
}

struct Cat {}

fn (c Cat) speak() string {
	return 'meow!'
}

struct Dog {}

fn main() {
	cat := Cat{}
	assert dump(cat.speak()) == 'meow!'

	a := Adoptable(cat)
	assert dump(a.speak()) == 'adopt me!' // call Adoptable's `speak`
	if a is Cat {
		// Inside this `if` however, V knows that `a` is not just any
		// kind of Adoptable, but actually a Cat, so it will use the
		// Cat `speak`, NOT the Adoptable `speak`:
		dump(a.speak()) // meow!
	}

	b := Adoptable(Dog{})
	assert dump(b.speak()) == 'adopt me!' // call Adoptable's `speak`
	// if b is Dog {
	// 	dump(b.speak()) // error: unknown method or field: Dog.speak
	// }
}
```

#### Embedded interface

Interfaces support embedding, just like structs:

```v
pub interface Reader {
mut:
	read(mut buf []u8) ?int
}

pub interface Writer {
mut:
	write(buf []u8) ?int
}

// ReaderWriter embeds both Reader and Writer.
// The effect is the same as copy/pasting all of the
// Reader and all of the Writer methods/fields into
// ReaderWriter.
pub interface ReaderWriter {
	Reader
	Writer
}
```

### Sum types

A sum type instance can hold a value of several different types. Use the `type`
keyword to declare a sum type:

```v
struct Moon {}

struct Mars {}

struct Venus {}

type World = Mars | Moon | Venus

sum := World(Moon{})
assert sum.type_name() == 'Moon'
println(sum)
```

The built-in method `type_name` returns the name of the currently held
type.

With sum types you could build recursive structures and write concise but powerful code on them.

```v
// V's binary tree
struct Empty {}

struct Node {
	value f64
	left  Tree
	right Tree
}

type Tree = Empty | Node

// sum up all node values

fn sum(tree Tree) f64 {
	return match tree {
		Empty { 0 }
		Node { tree.value + sum(tree.left) + sum(tree.right) }
	}
}

fn main() {
	left := Node{0.2, Empty{}, Empty{}}
	right := Node{0.3, Empty{}, Node{0.4, Empty{}, Empty{}}}
	tree := Node{0.5, left, right}
	println(sum(tree)) // 0.2 + 0.3 + 0.4 + 0.5 = 1.4
}
```

#### Dynamic casts

To check whether a sum type instance holds a certain type, use `sum is Type`.
To cast a sum type to one of its variants you can use `sum as Type`:

```v
struct Moon {}

struct Mars {}

struct Venus {}

type World = Mars | Moon | Venus

fn (m Mars) dust_storm() bool {
	return true
}

fn main() {
	mut w := World(Moon{})
	assert w is Moon
	w = Mars{}
	// use `as` to access the Mars instance
	mars := w as Mars
	if mars.dust_storm() {
		println('bad weather!')
	}
}
```

`as` will panic if `w` doesn't hold a `Mars` instance.
A safer way is to use a smart cast.

#### Smart casting

```v oksyntax
if w is Mars {
	assert typeof(w).name == 'Mars'
	if w.dust_storm() {
		println('bad weather!')
	}
}
```

`w` has type `Mars` inside the body of the `if` statement. This is
known as *flow-sensitive typing*.
If `w` is a mutable identifier, it would be unsafe if the compiler smart casts it without a warning.
That's why you have to declare a `mut` before the `is` expression:

```v ignore
if mut w is Mars {
	assert typeof(w).name == 'Mars'
	if w.dust_storm() {
		println('bad weather!')
	}
}
```

Otherwise `w` would keep its original type.
> This works for both simple variables and complex expressions like `user.name`

#### Matching sum types

You can also use `match` to determine the variant:

```v
struct Moon {}

struct Mars {}

struct Venus {}

type World = Mars | Moon | Venus

fn open_parachutes(n int) {
	println(n)
}

fn land(w World) {
	match w {
		Moon {} // no atmosphere
		Mars {
			// light atmosphere
			open_parachutes(3)
		}
		Venus {
			// heavy atmosphere
			open_parachutes(1)
		}
	}
}
```

`match` must have a pattern for each variant or have an `else` branch.

```v ignore
struct Moon {}
struct Mars {}
struct Venus {}

type World = Moon | Mars | Venus

fn (m Moon) moon_walk() {}
fn (m Mars) shiver() {}
fn (v Venus) sweat() {}

fn pass_time(w World) {
    match w {
        // using the shadowed match variable, in this case `w` (smart cast)
        Moon { w.moon_walk() }
        Mars { w.shiver() }
        else {}
    }
}
```

### Option/Result types and error handling

Option types are for types which may represent `none`. Result types may
represent an error returned from a function.

`Option` types are declared by prepending `?` to the type name: `?Type`.
`Result` types use `!`: `!Type`.

```v
struct User {
	id   int
	name string
}

struct Repo {
	users []User
}

fn (r Repo) find_user_by_id(id int) !User {
	for user in r.users {
		if user.id == id {
			// V automatically wraps this into a result or option type
			return user
		}
	}
	return error('User ${id} not found')
}

// A version of the function using an option
fn (r Repo) find_user_by_id2(id int) ?User {
	for user in r.users {
		if user.id == id {
			return user
		}
	}
	return none
}

fn main() {
	repo := Repo{
		users: [User{1, 'Andrew'}, User{2, 'Bob'}, User{10, 'Charles'}]
	}
	user := repo.find_user_by_id(10) or { // Option/Result types must be handled by `or` blocks
		println(err)
		return
	}
	println(user.id) // "10"
	println(user.name) // "Charles"

	user2 := repo.find_user_by_id2(10) or { return }

	// To create an Option var directly:
	my_optional_int := ?int(none)
	my_optional_string := ?string(none)
	my_optional_user := ?User(none)
}
```

V used to combine `Option` and `Result` into one type, now they are separate.

The amount of work required to "upgrade" a function to an option/result function is minimal;
you have to add a `?` or `!` to the return type and return `none` or an error (respectively)
when something goes wrong.

This is the primary mechanism for error handling in V. They are still values, like in Go,
but the advantage is that errors can't be unhandled, and handling them is a lot less verbose.
Unlike other languages, V does not handle exceptions with `throw/try/catch` blocks.

`err` is defined inside an `or` block and is set to the string message passed
to the `error()` function.

```v oksyntax
user := repo.find_user_by_id(7) or {
	println(err) // "User 7 not found"
	return
}
```

#### Options/results when returning multiple values

Only one `Option` or `Result` is allowed to be returned from a function. It is
possible to return multiple values and still signal an error.

```v
fn multi_return(v int) !(int, int) {
	if v < 0 {
		return error('must be positive')
	}
	return v, v * v
}
```

#### Handling options/results

There are four ways of handling an option/result. The first method is to
propagate the error:

```v
import net.http

fn f(url string) !string {
	resp := http.get(url)!
	return resp.body
}
```

`http.get` returns `!http.Response`. Because `!` follows the call, the
error will be propagated to the caller of `f`. When using `?` after a
function call producing an option, the enclosing function must return
an option as well. If error propagation is used in the `main()`
function it will `panic` instead, since the error cannot be propagated
any further.

The body of `f` is essentially a condensed version of:

```v ignore
    resp := http.get(url) or { return err }
    return resp.body
```

The second method is to break from execution early:

```v oksyntax
user := repo.find_user_by_id(7) or { return }
```

Here, you can either call `panic()` or `exit()`, which will stop the execution of the
entire program, or use a control flow statement (`return`, `break`, `continue`, etc)
to break from the current block.

> [!NOTE]
> `break` and `continue` can only be used inside a `for` loop.

V does not have a way to forcibly "unwrap" an option (as other languages do,
for instance Rust's `unwrap()` or Swift's `!`). To do this, use `or { panic(err) }` instead.

The third method is to provide a default value at the end of the `or` block.
In case of an error, that value would be assigned instead,
so it must have the same type as the content of the `Option` being handled.

```v
fn do_something(s string) !string {
	if s == 'foo' {
		return 'foo'
	}
	return error('invalid string')
}

a := do_something('foo') or { 'default' } // a will be 'foo'
b := do_something('bar') or { 'default' } // b will be 'default'
println(a)
println(b)
```

The fourth method is to use `if` unwrapping:

```v
import net.http

if resp := http.get('https://google.com') {
	println(resp.body) // resp is a http.Response, not an option
} else {
	println(err)
}
```

Above, `http.get` returns a `!http.Response`. `resp` is only in scope for the first
`if` branch. `err` is only in scope for the `else` branch.

### Custom error types

V gives you the ability to define custom error types through the `IError` interface.
The interface requires two methods: `msg() string` and `code() int`. Every type that
implements these methods can be used as an error.

When defining a custom error type it is recommended to embed the builtin `Error` default
implementation. This provides an empty default implementation for both required methods,
so you only have to implement what you really need, and may provide additional utility
functions in the future.

```v
struct PathError {
	Error
	path string
}

fn (err PathError) msg() string {
	return 'Failed to open path: ${err.path}'
}

fn try_open(path string) ! {
	// V automatically casts this to IError
	return PathError{
		path: path
	}
}

fn main() {
	try_open('/tmp') or { panic(err) }
}
```

### Generics

```v wip

struct Repo[T] {
    db DB
}

struct User {
	id   int
	name string
}

struct Post {
	id   int
	user_id int
	title string
	body string
}

fn new_repo[T](db DB) Repo[T] {
    return Repo[T]{db: db}
}

// This is a generic function. V will generate it for every type it's used with.
fn (r Repo[T]) find_by_id(id int) ?T {
    table_name := T.name // in this example getting the name of the type gives us the table name
    return r.db.query_one[T]('select * from ${table_name} where id = ?', id)
}

db := new_db()
users_repo := new_repo[User](db) // returns Repo[User]
posts_repo := new_repo[Post](db) // returns Repo[Post]
user := users_repo.find_by_id(1)? // find_by_id[User]
post := posts_repo.find_by_id(1)? // find_by_id[Post]
```

Currently generic function definitions must declare their type parameters, but in
future V will infer generic type parameters from single-letter type names in
runtime parameter types. This is why `find_by_id` can omit `[T]`, because the
receiver argument `r` uses a generic type `T`.

Another example:

```v
fn compare[T](a T, b T) int {
	if a < b {
		return -1
	}
	if a > b {
		return 1
	}
	return 0
}

// compare[int]
println(compare(1, 0)) // Outputs: 1
println(compare(1, 1)) //          0
println(compare(1, 2)) //         -1
// compare[string]
println(compare('1', '0')) // Outputs: 1
println(compare('1', '1')) //          0
println(compare('1', '2')) //         -1
// compare[f64]
println(compare(1.1, 1.0)) // Outputs: 1
println(compare(1.1, 1.1)) //          0
println(compare(1.1, 1.2)) //         -1
```

## Concurrency

### Spawning Concurrent Tasks

V's model of concurrency is similar to Go's.

`go foo()` runs `foo()` concurrently in a lightweight thread managed by the V runtime.

`spawn foo()` runs `foo()` concurrently in a different thread:

```v
import math

fn p(a f64, b f64) { // ordinary function without return value
	c := math.sqrt(a * a + b * b)
	println(c)
}

fn main() {
	spawn p(3, 4)
	// p will be run in parallel thread
	// It can also be written as follows
	// spawn fn (a f64, b f64) {
	// 	c := math.sqrt(a * a + b * b)
	// 	println(c)
	// }(3, 4)
}
```

> [!NOTE]
> Threads rely on the machine's CPU (number of cores/threads).
> Be aware that OS threads spawned with `spawn`
> have limitations in regard to concurrency,
> including resource overhead and scalability issues,
> and might affect performance in cases of high thread count.

Sometimes it is necessary to wait until a parallel thread has finished. This can
be done by assigning a *handle* to the started thread and calling the `wait()` method
to this handle later:

```v
import math

fn p(a f64, b f64) { // ordinary function without return value
	c := math.sqrt(a * a + b * b)
	println(c) // prints `5`
}

fn main() {
	h := spawn p(3, 4)
	// p() runs in parallel thread
	h.wait()
	// p() has definitely finished
}
```

This approach can also be used to get a return value from a function that is run in a
parallel thread. There is no need to modify the function itself to be able to call it
concurrently.

```v
import math { sqrt }

fn get_hypot(a f64, b f64) f64 { //       ordinary function returning a value
	c := sqrt(a * a + b * b)
	return c
}

fn main() {
	g := spawn get_hypot(54.06, 2.08) // spawn thread and get handle to it
	h1 := get_hypot(2.32, 16.74) //   do some other calculation here
	h2 := g.wait() //                 get result from spawned thread
	println('Results: ${h1}, ${h2}') //   prints `Results: 16.9, 54.1`
}
```

If there is a large number of tasks, it might be easier to manage them
using an array of threads.

```v
import time

fn task(id int, duration int) {
	println('task ${id} begin')
	time.sleep(duration * time.millisecond)
	println('task ${id} end')
}

fn main() {
	mut threads := []thread{}
	threads << spawn task(1, 500)
	threads << spawn task(2, 900)
	threads << spawn task(3, 100)
	threads.wait()
	println('done')
}

// Output:
// task 1 begin
// task 2 begin
// task 3 begin
// task 3 end
// task 1 end
// task 2 end
// done
```

Additionally for threads that return the same type, calling `wait()`
on the thread array will return all computed values.

```v
fn expensive_computing(i int) int {
	return i * i
}

fn main() {
	mut threads := []thread int{}
	for i in 1 .. 10 {
		threads << spawn expensive_computing(i)
	}
	// Join all tasks
	r := threads.wait()
	println('All jobs finished: ${r}')
}

// Output: All jobs finished: [1, 4, 9, 16, 25, 36, 49, 64, 81]
```

### Channels

Channels are the preferred way to communicate between threads. They allow threads to exchange data
safely without requiring explicit locking. V's channels are similar to those in Go, enabling you
to push objects into a channel on one end and pop objects from the other.
Channels can be buffered or unbuffered, and you can use the `select` statement to monitor multiple
channels simultaneously.

#### Syntax and Usage

Channels are declared with the type `chan objtype`.
You can optionally specify a buffer length using the `cap` field:

```v
ch := chan int{} // unbuffered - "synchronous"
ch2 := chan f64{cap: 100} // buffered with a capacity of 100
```

Channels do not have to be declared as `mut`. The buffer length is not part of the type but
a field of the individual channel object. Channels can be passed to threads like normal
variables:

```v
import time

fn worker(ch chan int) {
	for i in 0 .. 5 {
		ch <- i // push values into the channel
	}
}

fn clock(ch chan int) {
	for i in 0 .. 5 {
		time.sleep(1 * time.second)
		println('Clock tick')
		ch <- (i + 1000) // push a value into the channel
	}
	ch.close() // close the channel when done
}

fn main() {
	ch := chan int{cap: 5}
	spawn worker(ch)
	spawn clock(ch)
	for {
		value := <-ch or { // receive/pop values from the channel
			println('Channel closed')
			break
		}
		println('Received: ${value}')
	}
}
```

#### Buffered Channels

Buffered channels allow you to push multiple items without blocking,
as long as the buffer is not full:

```v
ch := chan string{cap: 2}
ch <- 'hello'
ch <- 'world'
// ch <- '!' // This would block because the buffer is full

println(<-ch) // "hello"
println(<-ch) // "world"
```

#### Closing Channels

A channel can be closed to indicate that no further objects can be pushed. Any attempt
to do so will then result in a runtime panic (with the exception of `select` and
`try_push()` - see below). Attempts to pop will return immediately if the
associated channel has been closed and the buffer is empty. This situation can be
handled using an `or {}` block (see [Handling options/results](#handling-optionsresults)).

```v wip
ch := chan int{}
ch2 := chan f64{}
// ...
ch.close()
// ...
m := <-ch or {
    println('channel has been closed')
}

// propagate error
y := <-ch2 ?
```

#### Channel Select

The `select` command allows monitoring several channels at the same time
without noticeable CPU load. It consists of a list of possible transfers and associated branches
of statements - similar to the [match](#match) command:

```v
import time

fn main() {
	ch := chan f64{}
	ch2 := chan f64{}
	ch3 := chan f64{}
	mut b := 0.0
	c := 1.0
	// ... setup spawn threads that will send on ch/ch2
	spawn fn (the_channel chan f64) {
		time.sleep(5 * time.millisecond)
		the_channel <- 1.0
	}(ch)
	spawn fn (the_channel chan f64) {
		time.sleep(1 * time.millisecond)
		the_channel <- 1.0
	}(ch2)
	spawn fn (the_channel chan f64) {
		_ := <-the_channel
	}(ch3)

	select {
		a := <-ch {
			// do something with `a`
			eprintln('> a: ${a}')
		}
		b = <-ch2 {
			// do something with predeclared variable `b`
			eprintln('> b: ${b}')
		}
		ch3 <- c {
			// do something if `c` was sent
			time.sleep(5 * time.millisecond)
			eprintln('> c: ${c} was send on channel ch3')
		}
		500 * time.millisecond {
			// do something if no channel has become ready within 0.5s
			eprintln('> more than 0.5s passed without a channel being ready')
		}
	}
	eprintln('> done')
}
```

The timeout branch is optional. If it is absent `select` waits for an unlimited amount of time.
It is also possible to proceed immediately if no channel is ready in the moment `select` is called
by adding an `else { ... }` branch. `else` and `<timeout>` are mutually exclusive.

The `select` command can be used as an *expression* of type `bool`
that becomes `false` if all channels are closed:

```v wip
if select {
    ch <- a {
        // ...
    }
} {
    // channel was open
} else {
    // channel is closed
}
```

#### Special Channel Features

For special purposes there are some builtin fields and methods:

```v
ch := chan int{cap: 2}
println(ch.try_push(42)) // `.success` if pushed, `.not_ready` if full, `.closed` if closed
println(ch.len) // Number of items in the buffer
println(ch.cap) // Buffer capacity
println(ch.closed) // Whether the channel is closed
```

```v
struct Abc {
	x int
}

a := 2.13
ch := chan f64{}
res := ch.try_push(a) // try to perform `ch <- a`
println(res)
l := ch.len // number of elements in queue
c := ch.cap // maximum queue length
is_closed := ch.closed // bool flag - has `ch` been closed
println(l)
println(c)
mut b := Abc{}
ch2 := chan Abc{}
res2 := ch2.try_pop(mut b) // try to perform `b = <-ch2`
```

The `try_push/pop()` methods will return immediately with one of the results
`.success`, `.not_ready` or `.closed` - dependent on whether the object has been transferred or
the reason why not.
Usage of these methods and fields in production is not recommended -
algorithms based on them are often subject to race conditions. Especially `.len` and
`.closed` should not be used to make decisions.
Use `or` branches, error propagation or `select` instead (see [Syntax and Usage](#syntax-and-usage)
and [Channel Select](#channel-select) above).

### Shared Objects

Data can be exchanged between a thread and the calling thread via a shared variable.
Such variables should be created as `shared` and passed to the thread as such, too.
The underlying `struct` contains a hidden *mutex* that allows locking concurrent access
using `rlock` for read-only and `lock` for read/write access.

Note: Shared variables must be structs, arrays or maps.

#### Example of Shared Objects

```v
struct Counter {
mut:
	value int
}

fn (shared counter Counter) increment() {
	lock counter {
		counter.value += 1
		println('Incremented to: ${counter.value}')
	}
}

fn main() {
	shared counter := Counter{}

	spawn counter.increment()
	spawn counter.increment()

	rlock counter {
		println('Final value: ${counter.value}')
	}
}
```

### Difference Between Channels and Shared Objects

**Purpose

```powershell
v install ui
```

Packages can be installed directly from git or mercurial repositories.

```powershell
v install [--once] [--git|--hg] [url]
```

**Example:**

```powershell
v install --git https://github.com/vlang/markdown
```

Sometimes you may want to install the dependencies **ONLY** if those are not installed:

```
v install --once [package]
```

Removing a package with v:

```powershell
v remove [package]
```

**Example:**

```powershell
v remove ui
```

Updating an installed package from [VPM](https://vpm.vlang.io/):

```powershell
v update [package]
```

**Example:**

```powershell
v update ui
```

Or you can update all your packages:

```powershell
v update
```

To see all the packages you have installed, you can use:

```powershell
v list
```

**Example:**

```powershell
> v list
Installed packages:
  markdown
  ui
```

To see all the packages that need updates:

```powershell
v outdated
```

**Example:**

```powershell
> v outdated
Package are up to date.
```

### Publish package

1. Put a `v.mod` file inside the toplevel folder of your package (if you
   created your package with the command `v new mypackage` or `v init`
   you already have a `v.mod` file).

   ```sh
   v new mypackage
   Input your project description: My nice package.
   Input your project version: (0.0.0) 0.0.1
   Input your project license: (MIT)
   Initialising ...
   Complete!
   ```

   Example `v.mod`:
   ```v ignore
   Module {
       name: 'mypackage'
       description: 'My nice package.'
       version: '0.0.1'
       license: 'MIT'
       dependencies: []
   }
   ```

   Minimal file structure:
   ```
   v.mod
   mypackage.v
   ```

   The name of your package should be used with the `module` directive
   at the top of all files in your package. For `mypackage.v`:
   ```v
   module mypackage

   pub fn hello_world() {
       println('Hello World!')
   }
   ```

2. Create a git repository in the folder with the `v.mod` file
   (this is not required if you used `v new` or `v init`):
   ```sh
   git init
   git add .
   git commit -m "INIT"
   ````

3. Create a public repository on github.com.
4. Connect your local repository to the remote repository and push the changes.
5. Add your package to the public V package registry VPM:
   https://vpm.vlang.io/new

   You will have to login with your Github account to register the package.
   **Warning:** _Currently it is not possible to edit your entry after submitting.
   Check your package name and github url twice as this cannot be changed by you later._
6. The final package name is a combination of your github account and
   the package name you provided e.g. `mygithubname.mypackage`.

**Optional:** tag your V package with `vlang` and `vlang-package` on github.com
to allow for a better search experience.

# Advanced Topics

## Attributes

V has several attributes that modify the behavior of functions and structs.

An attribute is a compiler instruction specified inside `[]` right before a
function/struct/enum declaration and applies only to the following declaration.

```v
// @[flag] enables Enum types to be used as bitfields

@[flag]
enum BitField {
	read
	write
	other
}

fn main() {
	assert 1 == int(BitField.read)
	assert 2 == int(BitField.write)
	mut bf := BitField.read
	assert bf.has(.read | .other) // test if *at least one* of the flags is set
	assert !bf.all(.read | .other) // test if *all* of the flags are set
	bf.set(.write | .other)
	assert bf.has(.read | .write | .other)
	assert bf.all(.read | .write | .other)
	bf.toggle(.other)
	assert bf == BitField.read | .write
	assert bf.all(.read | .write)
	assert !bf.has(.other)
	empty := BitField.zero()
	assert empty.is_empty()
	assert !empty.has(.read)
	assert !empty.has(.write)
	assert !empty.has(.other)
	mut full := empty
	full.set_all()
	assert int(full) == 7 // 0x01 + 0x02 + 0x04
	assert full == .read | .write | .other
	mut v := full
	v.clear(.read | .other)
	assert v == .write
	v.clear_all()
	assert v == empty
	assert BitField.read == BitField.from('read')!
	assert BitField.other == BitField.from('other')!
	assert BitField.write == BitField.from(2)!
	assert BitField.zero() == BitField.from('')!
}
```

```v
// @[_allow_multiple_values] allows an enum to have multiple duplicate values.
// Use it carefully, only when you really need it.

@[_allow_multiple_values]
enum ButtonStyle {
	primary   = 1
	secondary = 2
	success   = 3

	blurple = 1
	grey    = 2
	gray    = 2
	green   = 3
}

fn main() {
	assert int(ButtonStyle.primary) == 1
	assert int(ButtonStyle.blurple) == 1

	assert int(ButtonStyle.secondary) == 2
	assert int(ButtonStyle.gray) == 2
	assert int(ButtonStyle.grey) == 2

	assert int(ButtonStyle.success) == 3
	assert int(ButtonStyle.green) == 3

	assert ButtonStyle.primary == ButtonStyle.blurple
	assert ButtonStyle.secondary == ButtonStyle.grey
	assert ButtonStyle.secondary == ButtonStyle.gray
	assert ButtonStyle.success == ButtonStyle.green
}
```

Struct field deprecations:

```v oksyntax
module abc

// Note that only *direct* accesses to Xyz.d in *other modules*, will produce deprecation notices/warnings:
pub struct Xyz {
pub mut:
	a int
	d int @[deprecated: 'use Xyz.a instead'; deprecated_after: '2999-03-01']
	// the tags above, will produce a notice, since the deprecation date is in the far future
}
```

Function/method deprecations:

Functions are deprecated before they are finally removed to give users time to migrate their code.
Adding a date is preferable in most cases. An immediate change, without a deprecation date, may be
used for functions that are found to be conceptually broken and obsoleted by much better
functionality. Other than that setting a date is advisable to grant users a grace period.

Deprecated functions cause warnings, which cause errors if built with `-prod`. To avoid immediate
CI breakage, it is advisable to set a future date, ahead of the date when the code is merged. This
gives people who actively developed V projects, the chance to see the deprecation notice at least
once and fix the uses. Setting a date in the next 30 days, assumes they would have compiled their
projects manually at least once, within that time. For small changes, this should be plenty
of time. For complex changes, this time may need to be longer.

Different V projects and maintainers may reasonably choose different deprecation policies.
Depending on the type and impact of the change, you may want to consult with them first, before
deprecating a function.

```v
// Calling this function will result in a deprecation warning

@[deprecated]
fn old_function() {
}

// It can also display a custom deprecation message

@[deprecated: 'use new_function() instead']
fn legacy_function() {}

// You can also specify a date, after which the function will be
// considered deprecated. Before that date, calls to the function
// will be compiler notices - you will see them, but the compilation
// is not affected. After that date, calls will become warnings,
// so ordinary compiling will still work, but compiling with -prod
// will not (all warnings are treated like errors with -prod).
// 6 months after the deprecation date, calls will be hard
// compiler errors.

@[deprecated: 'use new_function2() instead']
@[deprecated_after: '2021-05-27']
fn legacy_function2() {}
```

```v globals
// This function's calls will be inlined.
@[inline]
fn inlined_function() {
}

// This function's calls will NOT be inlined.
@[noinline]
fn function() {
}

// This function will NOT return to its callers.
// Such functions can be used at the end of or blocks,
// just like exit/1 or panic/1. Such functions can not
// have return types, and should end either in for{}, or
// by calling other `[noreturn]` functions.
@[noreturn]
fn forever() {
	for {}
}

// The following struct must be allocated on the heap. Therefore, it can only be used as a
// reference (`&Window`) or inside another reference (`&OuterStruct{ Window{...} }`).
// See section "Stack and Heap"
@[heap]
struct Window {
}

// Calls to following function must be in unsafe{} blocks.
// Note that the code in the body of `risky_business()` will still be
// checked, unless you also wrap it in `unsafe {}` blocks.
// This is useful, when you want to have an `[unsafe]` function that
// has checks before/after a certain unsafe operation, that will still
// benefit from V's safety features.
@[unsafe]
fn risky_business() {
	// code that will be checked, perhaps checking pre conditions
	unsafe {
		// code that *will not be* checked, like pointer arithmetic,
		// accessing union fields, calling other `[unsafe]` fns, etc...
		// Usually, it is a good idea to try minimizing code wrapped
		// in unsafe{} as much as possible.
		// See also [Memory-unsafe code](#memory-unsafe-code)
	}
	// code that will be checked, perhaps checking post conditions and/or
	// keeping invariants
}

// V's autofree engine will not take care of memory management in this function.
// You will have the responsibility to free memory manually yourself in it.
// Note: it is NOT related to the garbage collector. It will only make the
// -autofree mechanism, ignore the body of that function.
@[manualfree]
fn custom_allocations() {
}

// The memory pointed to by the pointer arguments of this function will not be
// freed by the garbage collector (if in use) before the function returns
// For C interop only.
@[keep_args_alive]
fn C.my_external_function(voidptr, int, voidptr) int

// A @[weak] tag tells the C compiler, that the next declaration will be weak, i.e. when linking,
// if there is another declaration of a symbol with the same name (a 'strong' one), it should be
// used instead, *without linker errors about duplicate symbols*.
// For C interop only.

@[weak]
__global abc = u64(1)

// Tell V, that the following global was defined on the C side,
// thus V will not initialise it, but will just give you access to it.
// For C interop only.

@[c_extern]
__global my_instance C.my_struct
struct C.my_struct {
	a int
	b f64
}

// Tell V that the following struct is defined with `typedef struct` in C.
// For C interop only.
@[typedef]
pub struct C.Foo {}

// Used to add a custom calling convention to a function, available calling convention: stdcall, fastcall and cdecl.
// This list also applies for type aliases (see below).
// For C interop only.
@[callconv: 'stdcall']
fn C.DefWindowProc(hwnd int, msg int, lparam int, wparam int)

// Used to add a custom calling convention to a function type aliases.
// For C interop only.

@[callconv: 'fastcall']
type FastFn = fn (int) bool

// Calls to the following function, will have to use its return value somehow.
// Ignoring it, will emit warnings.
@[must_use]
fn f() int {
	return 42
}

fn g() {
	// just calling `f()` here, will produce a warning
	println(f()) // this is fine, because the return value was used as an argument
}

// Windows only (and obsolete; instead of it, use `-subsystem windows` when compiling)
// Without this attribute all graphical apps will have the following behavior on Windows:
// If run from a console or terminal; keep the terminal open so all (e)println statements can be viewed.
// If run from e.g. Explorer, by double-click; app is opened, but no terminal is opened, and no
// (e)println output can be seen.
// Use it to force-open a terminal to view output in, even if the app is started from Explorer.
// Valid before main() only.
@[console]
fn main() {
}
```

## Conditional compilation

The goal of this feature, is to tell V to *not compile* a function, and all its calls, in the final
executable, if a provided custom flag is not passed.

V will still type check the function and all its calls, *even* if they will not be present in the
final executable, due to the passed -d flags.

In order to see it in action, run the following example with `v run example.v` once,
and then a second time with `v -d trace_logs example.v`:
```v
@[if trace_logs ?]
fn elog(s string) {
	eprintln(s)
}

fn main() {
	elog('some expression: ${2 + 2}') // such calls will not be done *at all*, if `-d trace_logs` is not passed
	println('hi')
	elog('finish')
}
```

Conditional compilation, based on custom flags, can also be used to produce slightly different
executables, which share the majority of the same code, but where some of the logic, is needed
only some of the time, for example a network server/client program can be written like so:
```v ignore
fn act_as_client() { ... }
fn act_as_server() { ... }
fn main() {
	$if as_client ? {
		act_as_client()
	}
	$if as_server ? {
		act_as_server()
	}
}
```
To generate a `client.exe` executable do: `v -d as_client -o client.exe .`
To generate a `server.exe` executable do: `v -d as_server -o server.exe .`

### Compile time pseudo variables

V also gives your code access to a set of pseudo string variables,
that are substituted at compile time:

- `@FN` => replaced with the name of the current V function.
- `@METHOD` => replaced with ReceiverType.MethodName.
- `@MOD` => replaced with the name of the current V module.
- `@STRUCT` => replaced with the name of the current V struct.
- `@FILE` => replaced with the absolute path of the V source file.
- `@LINE` => replaced with the V line number where it appears (as a string).
- `@FILE_LINE` => like `@FILE:@LINE`, but the file part is a relative path.
- `@LOCATION` => file, line and name of the current type + method; suitable for logging.
- `@COLUMN` => replaced with the column where it appears (as a string).
- `@VEXE` => replaced with the path to the V compiler.
- `@VEXEROOT`  => will be substituted with the *folder*,
  where the V executable is (as a string).
- `@VHASH`  => replaced with the shortened commit hash of the V compiler (as a string).
- `@VCURRENTHASH` => Similar to `@VHASH`, but changes when the compiler is
  recompiled on a different commit (after local modifications, or after
  using git bisect etc).
- `@VMOD_FILE` => replaced with the contents of the nearest v.mod file (as a string).
- `@VMODHASH` => is replaced by the shortened commit hash, derived from the .git directory
  next to the nearest v.mod file (as a string).
- `@VMODROOT` => will be substituted with the *folder*,
  where the nearest v.mod file is (as a string).
- `@BUILD_DATE` => replaced with the build date, for example '2024-09-13' .
- `@BUILD_TIME` => replaced with the build time, for example '12:32:07' .
- `@BUILD_TIMESTAMP` => replaced with the build timestamp, for example '1726219885' .
Note: `@BUILD_DATE`, `@BUILD_TIME`, `@BUILD_TIMESTAMP` represent times in the UTC timezone.
By default, they are based on the current time of the compilation/build. They can be overridden
by setting the environment variable `SOURCE_DATE_EPOCH`. That is also useful while making
releases, since you can use the equivalent of this in your build system/script:
`export SOURCE_DATE_EPOCH=$(git log -1 --pretty=%ct) ;` , and then use `@BUILD_DATE` etc.,
inside your program, when you for example print your version information to users.
See also https://reproducible-builds.org/docs/source-date-epoch/ .

The compile time pseudo variables allow you to do the following
example, which is useful while debugging/logging/tracing your code:

```v
eprintln(@LOCATION)
```

Another example, is if you want to embed the version/name from v.mod *inside* your executable:

```v ignore
import v.vmod
vm := vmod.decode( @VMOD_FILE ) or { panic(err) }
eprintln('${vm.name} ${vm.version}\n ${vm.description}')
```

A program that prints its own source code (a quine):
```v
print($embed_file(@FILE).to_string())
```

A program that prints the time when it was built:
```v
import time

println('This program, was compiled at ${time.unix(@BUILD_TIMESTAMP.i64()).format_ss_milli()} .')
```

> [!NOTE]
> you can have arbitrary source code in the file, without problems, since the full file
> will be embedded into the executable, produced by compiling it. Also note that printing
> is done with `print` and not `println`, to not add another new line, missing in the
> source code.

### Compile time reflection

`$` is used as a prefix for compile time (also referred to as 'comptime') operations.

Having built-in JSON support is nice, but V also allows you to create efficient
serializers for any data format. V has compile time `if` and `for` constructs:

#### <h4 id="comptime-fields">.fields</h4>

You can iterate over struct fields using `.fields`, it also works with generic types
(e.g. `T.fields`) and generic arguments (e.g. `param.fields` where `fn gen[T](param T) {`).

```v
struct User {
	name string
	age  int
}

fn main() {
	$for field in User.fields {
		$if field.typ is string {
			println('${field.name} is of type string')
		}
	}
}

// Output:
// name is of type string
```

#### <h4 id="comptime-values">.values</h4>

You can read [Enum](#enums) values and their attributes.

```v
enum Color {
	red   @[RED]  // first attribute
	blue  @[BLUE] // second attribute
}

fn main() {
	$for e in Color.values {
		println(e.name)
		println(e.attrs)
	}
}

// Output:
// red
// ['RED']
// blue
// ['BLUE']
```

#### <h4 id="comptime-attrs">.attributes</h4>

You can read [Struct](#structs) attributes.

```v
@[COLOR]
struct Foo {
	a int
}

fn main() {
	$for e in Foo.attributes {
		println(e)
	}
}

// Output:
// StructAttribute{
//    name: 'COLOR'
//    has_arg: false
//    arg: ''
//    kind: plain
// }
```

#### <h4 id="comptime-variants">.variants</h4>

You can read variant types from [Sum type](#sum-types).

```v
type MySum = int | string

fn main() {
	$for v in MySum.variants {
		$if v.typ is int {
			println('has int type')
		} $else $if v.typ is string {
			println('has string type')
		}
	}
}

// Output:
// has int type
// has string type
```

#### <h4 id="comptime-methods">.methods</h4>

You can retrieve information about struct methods.

```v
struct Foo {
}

fn (f Foo) test() int {
	return 123
}

fn (f Foo) test2() string {
	return 'foo'
}

fn main() {
	foo := Foo{}
	$for m in Foo.methods {
		$if m.return_type is int {
			print('${m.name} returns int: ')
			println(foo.$method())
		} $else $if m.return_type is string {
			print('${m.name} returns string: ')
			println(foo.$method())
		}
	}
}

// Output:
// test returns int: 123
// test2 returns string: foo
```

#### <h4 id="comptime-method-params">.params</h4>

You can retrieve information about struct method params.

```v
struct Test {
}

fn (t Test) foo(arg1 int, arg2 string) {
}

fn main() {
	$for m in Test.methods {
		$for param in m.params {
			println('${typeof(param.typ).name}: ${param.name}')
		}
	}
}

// Output:
// int: arg1
// string: arg2
```

See [`examples/compiletime/reflection.v`](/examples/compiletime/reflection.v)
for a more complete example.

### Compile time code

#### `$if` condition

```v
fn main() {
	// Support for multiple conditions in one branch
	$if ios || android {
		println('Running on a mobile device!')
	}
	$if linux && x64 {
		println('64-bit Linux.')
	}
	// Usage as expression
	os := $if windows { 'Windows' } $else { 'UNIX' }
	println('Using ${os}')
	// $else-$if branches
	$if tinyc {
		println('tinyc')
	} $else $if clang {
		println('clang')
	} $else $if gcc {
		println('gcc')
	} $else {
		println('different compiler')
	}
	$if test {
		println('testing')
	}
	// v -cg ...
	$if debug {
		println('debugging')
	}
	// v -prod ...
	$if prod {
		println('production build')
	}
	// v -d option ...
	$if option ? {
		println('custom option')
	}
}
```

If you want an `if` to be evaluated at compile time it must be prefixed with a `$` sign.
Right now it can be used to detect an OS, compiler, platform or compilation options.
`$if debug` is a special option like `$if windows` or `$if x32`, it's enabled if the program
is compiled with `v -g` or `v -cg`.
If you're using a custom ifdef, then you do need `$if option ? {}` and compile with`v -d option`.
Full list of builtin options:

| OS                             | Compilers        | Platforms                     | Other                                         |
|--------------------------------|------------------|-------------------------------|-----------------------------------------------|
| `windows`, `linux`, `macos`    | `gcc`, `tinyc`   | `amd64`, `arm64`, `aarch64`   | `debug`, `prod`, `test`                       |
| `darwin`, `ios`, `bsd`         | `clang`, `mingw` | `i386`, `arm32`               | `js`, `glibc`, `prealloc`                     |
| `freebsd`, `openbsd`, `netbsd` | `msvc`           | `rv64`, `rv32`, `s390x`       | `no_bounds_checking`, `freestanding`          |
| `android`, `mach`, `dragonfly` | `cplusplus`      | `ppc64le`                     | `no_segfault_handler`, `no_backtrace`         |
| `gnu`, `hpux`, `haiku`, `qnx`  |                  | `x64`, `x32`                  | `no_main`, `fast_math`, `apk`, `threads`      |
| `solaris`, `termux`            |                  | `little_endian`, `big_endian` | `js_node`, `js_browser`, `js_freestanding`    |
| `serenity`, `vinix`, `plan9`   |                  |                               | `interpreter`, `es5`, `profile`, `wasm32`     |
|                                |                  |                               | `wasm32_emscripten`, `wasm32_wasi`            |
|                                |                  |                               | `native`, `autofree`                          |

#### `$embed_file`

```v ignore
import os
fn main() {
	embedded_file := $embed_file('v.png')
	os.write_file('exported.png', embedded_file.to_string())!
}
```

V can embed arbitrary files into the executable with the `$embed_file(<path>)`
compile time call. Paths can be absolute or relative to the source file.

Note that by default, using `$embed_file(file)`, will always embed the whole content
of the file, but you can modify that behaviour by passing: `-d embed_only_metadata`
when compiling your program. In that case, the file will not be embedded. Instead,
it will be loaded *the first time* your program calls `embedded_file.data()` at runtime,
making it easier to change in external editor programs, without needing to recompile
your program.

Embedding a file inside your executable, will increase its size, but
it will make it more self contained and thus easier to distribute.
When that happens (the default), `embedded_file.data()` will cause *no IO*,
and it will always return the same data.

`$embed_file` supports compression of the embedded file when compiling with `-prod`.
Currently only one compression type is supported: `zlib`.

```v ignore
import os
fn main() {
	embedded_file := $embed_file('x.css', .zlib) // compressed using zlib
	os.write_file('exported.css', embedded_file.to_string())!
}
```

Note: compressing binary assets like png or zip files, usually will not gain you much,
and in some cases may even take more space in the final executable, since they are
already compressed.

`$embed_file` returns
[EmbedFileData](https://modules.vlang.io/v.embed_file.html#EmbedFileData)
which could be used to obtain the file contents as `string` or `[]u8`.

#### `$tmpl` for embedding and parsing V template files

V has a simple template language for text and html templates, and they can easily
be embedded via `$tmpl('path/to/template.txt')`:

```v ignore
fn build() string {
	name := 'Peter'
	age := 25
	numbers := [1, 2, 3]
	return $tmpl('1.txt')
}

fn main() {
	println(build())
}
```

1.txt:

```
name: @name

age: @age

numbers: @numbers

@for number in numbers
  @number
@end
```

output:

```
name: Peter

age: 25

numbers: [1, 2, 3]

1
2
3
```

See more [details](https://github.com/vlang/v/blob/master/vlib/v/TEMPLATES.md)

#### `$env`

```v
module main

fn main() {
	compile_time_env := $env('ENV_VAR')
	println(compile_time_env)
}
```

V can bring in values at compile time from environment variables.
`$env('ENV_VAR')` can also be used in top-level `#flag` and `#include` statements:
`#flag linux -I $env('JAVA_HOME')/include`.

#### `$d`

V can bring in values at compile time from `-d ident=value` flag defines, passed on
the command line to the compiler. You can also pass `-d ident`, which will have the
same meaning as passing `-d ident=true`.

To get the value in your code, use: `$d('ident', default)`, where `default`
can be `false` for booleans, `0` or `123` for i64 numbers, `0.0` or `113.0`
for f64 numbers, `'a string'` for strings.

When a flag is not provided via the command line, `$d()` will return the `default`
value provided as the *second* argument.

```v
module main

const my_i64 = $d('my_i64', 1024)

fn main() {
	compile_time_value := $d('my_string', 'V')
	println(compile_time_value)
	println(my_i64)
}
```

Running the above with `v run .` will output:
```
V
1024
```

Running the above with `v -d my_i64=4096 -d my_string="V rocks" run .` will output:
```
V rocks
4096
```

Here is an example of how to use the default values, which have to be *pure* literals:
```v
fn main() {
	val_str := $d('id_str', 'value') // can be changed by providing `-d id_str="my id"`
	val_f64 := $d('id_f64', 42.0) // can be changed by providing `-d id_f64=84.0`
	val_i64 := $d('id_i64', 56) // can be changed by providing `-d id_i64=123`
	val_bool := $d('id_bool', false) // can be changed by providing `-d id_bool=true`
	val_char := $d('id_char', `f`) // can be changed by providing `-d id_char=v`
	println(val_str)
	println(val_f64)
	println(val_i64)
	println(val_bool)
	println(rune(val_char))
}
```

`$d('ident','value')` can also be used in top-level statements like `#flag` and `#include`:
`#flag linux -I $d('my_include','/usr')/include`. The default value for `$d` when used in these
statements should be literal `string`s.

`$d('ident', false)` can also be used inside `$if $d('ident', false) {` statements,
granting you the ability to selectively turn on/off certain sections of code, at compile
time, without modifying your source code, or keeping different versions of it.

#### `$compile_error` and `$compile_warn`

These two comptime functions are very useful for displaying custom errors/warnings during
compile time.

Both receive as their only argument a string literal that contains the message to display:

```v failcompile nofmt
// x.v
module main

$if linux {
    $compile_error('Linux is not supported')
}

fn main() {
}

$ v run x.v
x.v:4:5: error: Linux is not supported
    2 |
    3 | $if linux {
    4 |     $compile_error('Linux is not supported')
      |     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    5 | }
    6 |
```

### Compile time types

Compile time types group multiple types into a general higher-level type. This is useful in
functions with generic parameters, where the input type must have a specific property, for example
the `.len` attribute in arrays.

V supports the following compile time types:

- `$alias` => matches [Type aliases](#type-aliases).
- `$array` => matches [Arrays](#arrays) and [Fixed Size Arrays](#fixed-size-arrays).
- `$array_dynamic` => matches [Arrays](#arrays), but not [Fixed Size Arrays](#fixed-size-arrays).
- `$array_fixed` => matches [Fixed Size Arrays](#fixed-size-arrays), but not [Arrays](#arrays)
- `$enum` => matches [Enums](#enums).
- `$float` => matches `f32`, `f64` and float literals.
- `$function` => matches [Function Types](#function-types).
- `$int` => matches `int`, `i8`, `i16`, `i32`, `i64`, `u8`, `u16`, `u32`, `u64`, `isize`, `usize`
  and integer literals.
- `$interface` => matches [Interfaces](#interfaces).
- `$map` => matches [Maps](#maps).
- `$option` => matches [Option Types](#optionresult-types-and-error-handling).
- `$struct` => matches [Structs](#structs).
- `$sumtype` => matches [Sum Types](#sum-types).
- `$string` => matches [Strings](#strings).

### Environment specific files

If a file has an environment-specific suffix, it will only be compiled for that environment.

- `.js.v` => will be used only by the JS backend. These files can contain JS. code.
- `.c.v` => will be used only by the C backend. These files can contain C. code.
- `.native.v` => will be used only by V's native backend.
- `_nix.c.v` => will be used only on Unix systems (non Windows).
- `_${os}.c.v` => will be used only on the specific `os` system.
  For example, `_windows.c.v` will be used only when compiling on Windows, or with `-os windows`.
- `_default.c.v` => will be used only if there is NOT a more specific platform file.
  For example, if you have both `file_linux.c.v` and `file_default.c.v`,
  and you are compiling for linux, then only `file_linux.c.v` will be used,
  and `file_default.c.v` will be ignored.

Here is a more complete example:

`main.v`:

```v ignore
module main
fn main() { println(message) }
```

`main_default.c.v`:

```v ignore
module main
const message = 'Hello world'
```

`main_linux.c.v`:

```v ignore
module main
const message = 'Hello linux'
```

`main_windows.c.v`:

```v ignore
module main
const message = 'Hello windows'
```

With the example above:

- when you compile for Windows, you will get `Hello windows`
- when you compile for Linux, you will get `Hello linux`
- when you compile for any other platform, you will get the
  non specific `Hello world` message.

- `_d_customflag.v` => will be used *only* if you pass `-d customflag` to V.
  That corresponds to `$if customflag ? {}`, but for a whole file, not just a
  single block. `customflag` should be a snake_case identifier, it can not
  contain arbitrary characters (only lower case latin letters + numbers + `_`).
  > **Note**
  >
  > A combinatorial `_d_customflag_linux.c.v` postfix will not work.
  > If you do need a custom flag file, that has platform dependent code, use the
  > postfix `_d_customflag.v`, and then use platform dependent compile time
  > conditional blocks inside it, i.e. `$if linux {}` etc.

- `_notd_customflag.v` => similar to _d_customflag.v, but will be used
  *only* if you do NOT pass `-d customflag` to V.

See also [Cross Compilation](#cross-compilation).

## Debugger

To use the native *V debugger*, add the `$dbg` statement to your source, where you
want the debugger to be invoked.

```v
fn main() {
	a := 1
	$dbg;
}
```

Running this V code, you will get the debugger REPL break when the execution
reaches the `$dbg` statement.

```
$ v run example.v
Break on [main] main in example.v:3
example.v:3 vdbg>
```

At this point, execution is halted, and the debugger is now available.

To see the available commands, type
?, h or help. (Completion for commands works - Non-Windows only)

```
example.v:3 vdbg> ?
vdbg commands:
  anon?                 check if the current context is anon
  bt                    prints a backtrace
  c, continue           continue debugging
  generic?              check if the current context is generic
  heap                  show heap memory usage
  h, help, ?            show this help
  l, list [lines]       show some lines from current break (default: 3)
  mem, memory           show memory usage
  method?               check if the current context is a method
  m, mod                show current module name
  p, print <var>        prints an variable
  q, quit               exits debugging session in the code
  scope                 show the vars in the current scope
  u, unwatch <var>      unwatches a variable
  w, watch <var>        watches a variable
```

Lets try the `scope` command, to inspect the current scope context.

```
example.v:3 vdbg> scope
a = 1 (int)
```

Cool! We have the variable name, its value and its type name.

What about printing only a variable, not the whole scope?

Just type `p a`.

To watch a variable by its name, use:

`w a` (where `a` is the variable name)

To stop watching the variable (`unwatch` it), use `u a`.

Lets see more one example:

```
fn main() {
	for i := 0; i < 4; i++ {
		$dbg
	}
}
```

Running again, we'll get:
`Break on [main] main in example.v:3`

If we want to read the source code context, we can use the `l` or `list` command.

```
example.v:3 vdbg> l
0001  fn main() {
0002    for i := 0; i < 4; i++ {
0003>           $dbg
0004    }
0005  }
```

The default is read 3 lines before and 3 lines after, but you can
pass a parameter to the command to read more lines, like `l 5`.

Now, lets watch the variable changing on this loop.

```
example.v:3 vdbg> w i
i = 0 (int)
```

To continue to the next breakpoint, type `c` or `continue` command.

```
example.v:3 vdbg> c
Break on [main] main in example.v:3
i = 1 (int)
```

`i` and it's value is automatically printed, because it is in the watch list.

To repeat the last command issued, in this case the `c` command,
just hit the *enter* key.

```
example.v:3 vdbg>
Break on [main] main in example.v:3
i = 2 (int)
example.v:3 vdbg>
Break on [main] main in example.v:3
i = 3 (int)
example.v:3 vdbg>
```

You can also see memory usage with `mem` or `memory` command, and
check if the current context is an anon function (`anon?`), a method (`method?`)
or a generic method (`generic?`) and clear the terminal window (`clear`).

## Call stack

You can also show the current call stack with `v.debug`.

To enable this feature, add the `-d callstack` switch when building or running
your code:

```v
import v.debug

fn test(i int) {
	if i > 9 {
		debug.dump_callstack()
	}
}

fn do_something() {
	for i := 0; i <= 10; i++ {
		test(i)
	}
}

fn main() {
	do_something()
}
```

```
$ v -d callstack run example.v
Backtrace:
--------------------------------------------------
example.v:16   | > main.main
example.v:11   |  > main.do_something
example.v:5    |   > main.test
--------------------------------------------------
```

## Trace

Another feature of `v.debug` is the possibility to add hook functions
before and after each function call.

To enable this feature, add the `-d trace` switch when building or running
your code:

```v
import v.debug

fn main() {
	hook1 := debug.add_before_call(fn (fn_name string) {
		println('> before ${fn_name}')
	})
	hook2 := debug.add_after_call(fn (fn_name string) {
		println('> after ${fn_name}')
	})
	anon := fn () {
		println('call')
	}
	anon()

	// optionally you can remove the hooks:
	debug.remove_before_call(hook1)
	debug.remove_after_call(hook2)
	anon()
}
```

```
$ v -d trace run example.v
> before anon
call
> after anon
call
```

## Memory-unsafe code

Sometimes for efficiency you may want to write low-level code that can potentially
corrupt memory or be vulnerable to security exploits. V supports writing such code,
but not by default.

V requires that any potentially memory-unsafe operations are marked intentionally.
Marking them also indicates to anyone reading the code that there could be
memory-safety violations if there was a mistake.

Examples of potentially memory-unsafe operations are:

* Pointer arithmetic
* Pointer indexing
* Conversion to pointer from an incompatible type
* Calling certain C functions, e.g. `free`, `strlen` and `strncmp`.

To mark potentially memory-unsafe operations, enclose them in an `unsafe` block:

```v wip
// allocate 2 uninitialized bytes & return a reference to them
mut p := unsafe { malloc(2) }
p[0] = `h` // Error: pointer indexing is only allowed in `unsafe` blocks
unsafe {
    p[0] = `h` // OK
    p[1] = `i`
}
p++ // Error: pointer arithmetic is only allowed in `unsafe` blocks
unsafe {
    p++ // OK
}
assert *p == `i`
```

Best practice is to avoid putting memory-safe expressions inside an `unsafe` block,
so that the reason for using `unsafe` is as clear as possible. Generally any code
you think is memory-safe should not be inside an `unsafe` block, so the compiler
can verify it.

If you suspect your program does violate memory-safety, you have a head start on
finding the cause: look at the `unsafe` blocks (and how they interact with
surrounding code).

> [!NOTE]
> This is work in progress.

## Structs with reference fields

Structs with references require explicitly setting the initial value to a
reference value unless the struct already defines its own initial value.

Zero-value references, or nil pointers, will **NOT** be supported in the future,
for now data structures such as Linked Lists or Binary Trees that rely on reference
fields that can use the value `0`, understanding that it is unsafe, and that it can
cause a panic.

```v
struct Node {
	a &Node
	b &Node = unsafe { nil } // Auto-initialized to nil, use with caution!
}

// Reference fields must be initialized unless an initial value is declared.
// Nil is OK but use with caution, it's a nil pointer.
foo := Node{
	a: unsafe { nil }
}
bar := Node{
	a: &foo
}
baz := Node{
	a: unsafe { nil }
	b: unsafe { nil }
}
qux := Node{
	a: &foo
	b: &bar
}
println(baz)
println(qux)
```

## sizeof and __offsetof

* `sizeof(Type)` gives the size of a type in bytes.
* `__offsetof(Struct, field_name)` gives the offset in bytes of a struct field.

```v
struct Foo {
	a int
	b int
}

assert sizeof(Foo) == 8
assert __offsetof(Foo, a) == 0
assert __offsetof(Foo, b) == 4
```

## Limited operator overloading

Operator overloading defines the behavior of certain binary operators for certain types.

```v
struct Vec {
	x int
	y int
}

fn (a Vec) str() string {
	return '{${a.x}, ${a.y}}'
}

fn (a Vec) + (b Vec) Vec {
	return Vec{a.x + b.x, a.y + b.y}
}

fn (a Vec) - (b Vec) Vec {
	return Vec{a.x - b.x, a.y - b.y}
}

fn main() {
	a := Vec{2, 3}
	b := Vec{4, 5}
	mut c := Vec{1, 2}

	println(a + b) // "{6, 8}"
	println(a - b) // "{-2, -2}"
	c += a
	//^^ autogenerated from + overload
	println(c) // "{3, 5}"
}
```

> Operator overloading goes against V's philosophy of simplicity and predictability.
> But since scientific and graphical applications are among V's domains,
> operator overloading is an important feature to have in order to improve readability:
>
> `a.add(b).add(c.mul(d))` is a lot less readable than `a + b + c * d`.

Operator overloading is possible for the following binary operators: `+, -, *, /, %, <, ==`.

### Implicitly generated overloads

- `==` is automatically generated by the compiler, but can be overridden.

- `!=`, `>`, `<=` and `>=` are automatically generated when `==` and `<` are defined.
  They cannot be explicitly overridden.
- Assignment operators (`*=`, `+=`, `/=`, etc) are automatically generated when the corresponding
  operators are defined and the operands are of the same type.
  They cannot be explicitly overridden.

### Restriction

To improve safety and maintainability, operator overloading is limited.

#### Type restrictions

- When overriding `<` and `==`, the return type must be strictly `bool`.
- Both arguments must have the same type (just like with all operators in V).
- Overloaded operators have to return the same type as the argument
  (the exceptions are `<` and `==`).

#### Other restrictions

- Arguments cannot be changed inside overloads.
- Calling other functions inside operator functions is not allowed (**planned**).

## Performance tuning

When compiled with `-prod`, V's generated C code usually performs well. However, in specialized
scenarios, additional compiler flags and attributes can further optimize the executable for
performance, memory usage, or size.

> [!NOTE]
> These are *rarely* needed, and should not be used unless you
> *profile your code*, and then see that there are significant benefits for them.
> To cite GCC's documentation: "Programmers are notoriously bad at predicting
> how their programs actually perform".

| Tuning Operation         | Benefits                        | Drawbacks                                         |
|--------------------------|---------------------------------|---------------------------------------------------|
| `@[inline]`              | Performance                     | Increased executable size                         |
| `@[direct_array_access]` | Performance                     | Safety risks                                      |
| `@[packed]`              | Memory usage                    | Potential performance loss                        |
| `@[minify]`              | Performance, Memory usage       | May break binary serialization/reflection         |
| `_likely_/_unlikely_`    | Performance                     | Risk of negative performance impact               |
| `-fast-math`             | Performance                     | Risk of incorrect mathematical operations results |
| `-d no_segfault_handler` | Compile time, Size              | Loss of segfault trace                            |
| `-cflags -march=native`  | Performance                     | Risk of reduced CPU compatibility                 |
| `-compress`              | Size                            | Harder to debug, extra dependency `upx`           |
| `PGO`                    | Performance, Size               | Usage complexity                                  |

### Tuning operations details

#### `@[inline]`

You can tag functions with `@[inline]`, so the C compiler will try to inline them, which in some
cases, may be beneficial for performance, but may impact the size of your executable.

**When to Use**

- Functions that are called frequently in performance-critical loops.

**When to Avoid**

- Large functions, as it might cause code bloat and actually decrease performance.
- Large functions in `if` expressions - may have negative impact on instructions cache.

#### `@[direct_array_access]`

In functions tagged with `@[direct_array_access]` the compiler will translate array operations
directly into C array operations - omitting bounds checking. This may save a lot of time in a
function that iterates over an array but at the cost of making the function unsafe - unless the
boundaries will be checked by the user.

**When to Use**

- In tight loops that access array elements, where bounds have been manually verified or you are
sure that the access index will be valid.

**When to Avoid**

- Everywhere else.

#### `@[packed]`

The `@[packed]` attribute can be applied to a structure to create an unaligned memory layout,
which decreases the overall memory footprint of the structure. Using the `@[packed]` attribute
may negatively impact performance or even be prohibited on certain CPU architectures.

**When to Use**

- When memory usage is more critical than performance, e.g., in embedded systems.

**When to Avoid**

- On CPU architectures that do not support unaligned memory access or when high-speed memory access
is needed.

#### `@[aligned]`

The `@[aligned]` attribute can be applied to a structure or union to specify a minimum alignment
(in bytes) for variables of that type. Using the `@[aligned]` attribute you can only *increase*
the default alignment. Use `@[packed]` if you want to *decrease* it. The alignment of any struct
or union, should be at least a perfect multiple of the lowest common multiple of the alignments of
all of the members of the struct or union.

Example:
```v
// Each u16 in the `data` field below, takes 2 bytes, and we have 3 of them = 6 bytes.
// The smallest power of 2, bigger than 6 is 8, i.e. with `@[aligned]`, the alignment
// for the entire struct U16s, will be 8:
@[aligned]
struct U16s {
	data [3]u16
}
```
**When to Use**

- Only if the instances of your types, will be used in performance critical sections, or with
specialised machine instructions, that do require a specific alignment to work.

**When to Avoid**

- On CPU architectures, that do not support unaligned memory access. If you are not working on
performance critical algorithms, you do not really need it, since the proper minimum alignment
is CPU specific, and the compiler already usually will choose a good default for you.

> [!NOTE]
> You can leave out the alignment factor, i.e. use just `@[aligned]`, in which case the compiler
> will align a type to the maximum useful alignment for the target machine you are compiling for,
> i.e. the alignment will be the largest alignment which is ever used for any data type on the
> target machine. Doing this can often make copy operations more efficient, because the compiler
> can choose whatever instructions copy the biggest chunks of memory, when performing copies to or
> from the variables which have types that you have aligned this way.

See also ["What Every Programmer Should Know About Memory", by Ulrich Drepper](https://people.freebsd.org/~lstewart/articles/cpumemory.pdf) .

#### `@[minify]`

The `@[minify]` attribute can be added to a struct, allowing the compiler to reorder the fields in
a way that minimizes internal gaps while maintaining alignment. Using the `@[minify]` attribute may
cause issues with binary serialization or reflection. Be mindful of these potential side effects
when using this attribute.

**When to Use**

- When you want to minimize memory usage and you're not using binary serialization or reflection.

**When to Avoid**

- When using binary serialization or reflection, as it may cause unexpected behavior.

#### `_likely_/_unlikely_`

`if _likely_(bool expression) {` - hints to the C compiler, that the passed boolean expression is
very likely to be true, so it can generate assembly code, with less chance of branch misprediction.
In the JS backend, that does nothing.

`if _unlikely_(bool expression) {` is similar to `_likely_(x)`, but it hints that the boolean
expression is highly improbable. In the JS backend, that does nothing.

**When to Use**

- In conditional statements where one branch is clearly more frequently executed than the other.

**When to Avoid**

- When the prediction can be wrong, as it might cause a performance penalty due to branch
misprediction.

**When to Use**

- For production builds where you want to reduce the executable size and improve runtime
performance.

**When to Avoid**

- Where it doesn't work for you.

#### `-fast-math`

This flag enables optimizations that disregard strict compliance with the IEEE standard for
floating-point arithmetic. While this could lead to faster code, it may produce incorrect or
less accurate mathematical results.

The full specter of math operations that `-fast-math` affects can be found
[here](https://clang.llvm.org/docs/UsersManual.html#cmdoption-ffast-math).

**When to Use**

- In applications where performance is more critical than precision, like certain graphics
rendering tasks.

**When to Avoid**

- In applications requiring strict mathematical accuracy, such as scientific simulations or
financial calculations.

#### `-d no_segfault_handler`

Using this flag omits the segfault handler, reducing the executable size and potentially improving
compile time. However, in the case of a segmentation fault, the output will not contain stack trace
information, making debugging more challenging.

**When to Use**

- In small, well-tested utilities where a stack trace is not essential for debugging.

**When to Avoid**

- In large-scale, complex applications where robust debugging is required.

#### `-cflags -march=native`

This flag directs the C compiler to generate instructions optimized for the host CPU. This can
improve performance but will produce an executable incompatible with other/older CPUs.

**When to Use**

- When the software is intended to run only on the build machine or in a controlled environment
with identical hardware.

**When to Avoid**

- When distributing the software to users with potentially older CPUs.

#### `-compress`

This flag executes `upx` to compress the resultant executable, reducing its size by around 50%-70%.
The executable will be uncompressed at runtime, so it will take a bit more time to start.
It will also take extra RAM initially, as the compressed version of the app will be loaded into
memory, and then expanded to another chunk of memory.
Debugging such an application can be a bit harder, if you do not account for it.
Some antivirus programs also use heuristics, that trigger more often for compressed applications.

**When to Use**

- For really tiny environments, where the size of the executable on the file system,
or when deploying is important (docker containers, rescue disks etc).

**When to Avoid**

- When you need to debug the application
- When the app's startup time is extremely important (where 1-2ms can be meaningful for you)
- When you can not afford to allocate more memory during application startup
- When you are deploying an app to users with antivirus software that could misidentify your
app as malicious, just because it decompresses its code at runtime.

#### PGO (Profile-Guided Optimization)

PGO allows the compiler to optimize code based on its behavior during sample runs. This can improve
performance and reduce the size of the output executable, but it adds complexity to the build
process.

**When to Use**

- For performance-critical applications where the added build complexity is justifiable.

**When to Avoid**

- For small, short-lived, or rapidly-changing projects where the added build complexity isn't
justified.

**PGO with Clang**

This is an example bash script you can use to optimize your CLI V program without user interactions.
In most cases, you will need to change this script to make it suitable for your particular program.

```bash
#!/usr/bin/env bash

# Get the full path to the current directory
CUR_DIR=$(pwd)

# Remove existing PGO data
rm -f *.profraw
rm -f default.profdata

# Initial build with PGO instrumentation
v -cc clang -prod -cflags -fprofile-generate -o pgo_gen .

# Run the instrumented executable 10 times
for i in {1..10}; do
    ./pgo_gen
done

# Merge the collected data
llvm-profdata merge -o default.profdata *.profraw

# Compile the optimized version using the PGO data
v -cc clang -prod -cflags "-fprofile-use=${CUR_DIR}/default.profdata" -o optimized_program .

# Remove PGO data and instrumented executable
rm *.profraw
rm pgo_gen
```

## Atomics

V has no special support for atomics, yet, nevertheless it's possible to treat variables as atomics
by [calling C](#v-and-c) functions from V. The standard C11 atomic functions like `atomic_store()`
are usually defined with the help of macros and C compiler magic to provide a kind of
*overloaded C functions*.
Since V does not support overloading functions by intention there are wrapper functions defined in
C headers named `atomic.h` that are part of the V compiler infrastructure.

There are dedicated wrappers for all unsigned integer types and for pointers.
(`u8` is not fully supported on Windows) &ndash; the function names include the type name
as suffix. e.g. `C.atomic_load_ptr()` or `C.atomic_fetch_add_u64()`.

To use these functions the C header for the used OS has to be included and the functions
that are intended to be used have to be declared. Example:

```v globals
$if windows {
	#include "@VEXEROOT/thirdparty/stdatomic/win/atomic.h"
} $else {
	#include "@VEXEROOT/thirdparty/stdatomic/nix/atomic.h"
}

// declare functions we want to use - V does not parse the C header
fn C.atomic_store_u32(&u32, u32)
fn C.atomic_load_u32(&u32) u32
fn C.atomic_compare_exchange_weak_u32(&u32, &u32, u32) bool
fn C.atomic_compare_exchange_strong_u32(&u32, &u32, u32) bool

const num_iterations = 10000000

// see section "Global Variables" below
__global (
	atom u32 // ordinary variable but used as atomic
)

fn change() int {
	mut races_won_by_change := 0
	for {
		mut cmp := u32(17) // addressable value to compare with and to store the found value
		// atomic version of `if atom == 17 { atom = 23 races_won_by_change++ } else { cmp = atom }`
		if C.atomic_compare_exchange_strong_u32(&atom, &cmp, 23) {
			races_won_by_change++
		} else {
			if cmp == 31 {
				break
			}
			cmp = 17 // re-assign because overwritten with value of atom
		}
	}
	return races_won_by_change
}

fn main() {
	C.atomic_store_u32(&atom, 17)
	t := spawn change()
	mut races_won_by_main := 0
	mut cmp17 := u32(17)
	mut cmp23 := u32(23)
	for i in 0 .. num_iterations {
		// atomic version of `if atom == 17 { atom = 23 races_won_by_main++ }`
		if C.atomic_compare_exchange_strong_u32(&atom, &cmp17, 23) {
			races_won_by_main++
		} else {
			cmp17 = 17
		}
		desir := if i == num_iterations - 1 { u32(31) } else { u32(17) }
		// atomic version of `for atom != 23 {} atom = desir`
		for !C.atomic_compare_exchange_weak_u32(&atom, &cmp23, desir) {
			cmp23 = 23
		}
	}
	races_won_by_change := t.wait()
	atom_new := C.atomic_load_u32(&atom)
	println('atom: ${atom_new}, #exchanges: ${races_won_by_main + races_won_by_change}')
	// prints `atom: 31, #exchanges: 10000000`)
	println('races won by\n- `main()`: ${races_won_by_main}\n- `change()`: ${races_won_by_change}')
}
```

In this example both `main()` and the spawned thread `change()` try to replace a value of `17`
in the global `atom` with a value of `23`. The replacement in the opposite direction is
done exactly 10000000 times. The last replacement will be with `31` which makes the spawned
thread finish.

It is not predictable how many replacements occur in which thread, but the sum will always
be 10000000. (With the non-atomic commands from the comments the value will be higher or the program
will hang &ndash; dependent on the compiler optimization used.)

## Global Variables

By default V does not allow global variables. However, in low level applications they have their
place so their usage can be enabled with the compiler flag `-enable-globals`.
Declarations of global variables must be surrounded with a `__global ( ... )`
specification &ndash; as in the example [above](#atomics).

An initializer for global variables must be explicitly converted to the
desired target type. If no initializer is given a default initialization is done.
Some objects like semaphores and mutexes require an explicit initialization *in place*, i.e.
not with a value returned from a function call but with a method call by reference.
A separate `init()` function can be used for this purpose &ndash; it will be called before `main()`:

```v globals
import sync

__global (
	sem   sync.Semaphore // needs initialization in `init()`
	mtx   sync.RwMutex // needs initialization in `init()`
	f1    = f64(34.0625) // explicitly initialized
	shmap shared map[string]f64 // initialized as empty `shared` map
	f2    f64 // initialized to `0.0`
)

fn init() {
	sem.init(0)
	mtx.init()
}
```

Be aware that in multi threaded applications the access to global variables is subject
to race conditions. There are several approaches to deal with these:

- use `shared` types for the variable declarations and use `lock` blocks for access.
  This is most appropriate for larger objects like structs, arrays or maps.
- handle primitive data types as "atomics" using special C-functions (see [above](#atomics)).
- use explicit synchronization primitives like mutexes to control access. The compiler
  cannot really help in this case, so you have to know what you are doing.
- don't care &ndash; this approach is possible but makes only sense if the exact values
  of global variables do not really matter. An example can be found in the `rand` module
  where global variables are used to generate (non cryptographic) pseudo random numbers.
  In this case data races lead to random numbers in different threads becoming somewhat
  correlated, which is acceptable considering the performance penalty that using
  synchronization primitives would represent.

## Static Variables

V also supports *static variables*, which are like *global variables*, but
available only *inside* a single unsafe function (you can look at them as
namespaced globals).

Note: their use is discouraged too, for reasons similar to why globals
are discouraged. The feature is supported to enable translating existing
low level C code into V code, using `v translate`.

Note: the function in which you use a static variable, has to be marked
with @[unsafe]. Also unlike using globals, using static variables, do not
require you to pass the flag `-enable-globals`, because they can only be
read/changed inside a single function, which has full control over the
state stored in them.

Here is a small example of how static variables can be used:
```v
@[unsafe]
fn counter() int {
	mut static x := 42
	// Note: x is initialised to 42, just _once_.
	x++
	return x
}

fn f() int {
	return unsafe { counter() }
}

println(f()) // prints 43
println(f()) // prints 44
println(f()) // prints 45
```

## Cross compilation

Cross compilation is supported for Windows, Linux and FreeBSD.

To cross compile your project simply run:

```shell
v -os windows .
```

or

```shell
v -os linux .
```

or

```shell
v -os freebsd .
```

> [!NOTE]
> Cross-compiling a Windows binary on a Linux machine requires the GNU C compiler for
> MinGW-w64 (targeting Win64) to first be installed.

For Ubuntu/Debian based distributions:

```shell
sudo apt install gcc-mingw-w64-x86-64
```

For Arch based distributions:

```shell
sudo pacman -S mingw-w64-gcc
```

(Cross compiling for macOS is temporarily not possible.)

If you don't have any C dependencies, that's all you need to do. This works even
when compiling GUI apps using the `ui` module or graphical apps using `gg`.

You will need to install Clang, LLD linker, and download a zip file with
libraries and include files for Windows and Linux. V will provide you with a link.

## Debugging

### C Backend binaries (Default)

To debug issues in the generated binary (flag: `-b c`), you can pass these flags:

- `-g` - produces a less optimized executable with more debug information in it.
  V will enforce line numbers from the .v files in the stacktraces, that the
  executable will produce on panic. It is usually better to pass -g, unless
  you are writing low level code, in which case use the next option `-cg`.
- `-cg` - produces a less optimized executable with more debug information in it.
  The executable will use C source line numbers in this case. It is frequently
  used in combination with `-keepc`, so that you can inspect the generated
  C program in case of panic, or so that your debugger (`gdb`, `lldb` etc.)
  can show you the generated C source code.
- `-showcc` - prints the C command that is used to build the program.
- `-show-c-output` - prints the output, that your C compiler produced
  while compiling your program.
- `-keepc` - do not delete the generated C source code file after a successful
  compilation. Also keep using the same file path, so it is more stable,
  and easier to keep opened in an editor/IDE.

For best debugging experience if you are writing a low level wrapper for an existing
C library, you can pass several of these flags at the same time:
`v -keepc -cg -showcc yourprogram.v`, then just run your debugger (gdb/lldb) or IDE
on the produced executable `yourprogram`.

If you just want to inspect the generated C code,
without further compilation, you can also use the `-o` flag (e.g. `-o file.c`).
This will make V produce the `file.c` then stop.

If you want to see the generated C source code for *just* a single C function,
for example `main`, you can use: `-printfn main -o file.c`.

To debug the V executable itself you need to compile from src with `./v -g -o v cmd/v`.

You can debug tests with for example `v -g -keepc prog_test.v`. The `-keepc` flag is needed,
so that the executable is not deleted, after it was created and ran.

To see a detailed list of all flags that V supports,
use `v help`, `v help build` and `v help build-c`.

**Commandline Debugging**

1. compile your binary with debugging info `v -g hello.v`
2. debug with [lldb](https://lldb.llvm.org) or [GDB](https://www.gnu.org/software/gdb/)
   e.g. `lldb hello`

[Troubleshooting (debugging) executables created with V in GDB](https://github.com/vlang/v/wiki/Troubleshooting-(debugging)-executables-created-with-V-in-GDB)

**Visual debugging Setup:**

* [Visual Studio Code](vscode.md)

### Native Backend binaries

Currently there is no debugging support for binaries, created by the
native backend (flag: `-b native`).

### Javascript Backend

To debug the generated Javascript output you can activate source maps:
`v -b js -sourcemap hello.v -o hello.js`

For all supported options check the latest help:
`v help build-js`

## V and C

The basic mapping between C and V types is described in
[C and V Type Interoperability](https://github.com/vlang/v/blob/master/doc/c_and_v_type_interoperability.md).

### Calling C from V

V currently does not have a parser for C code. That means that even
though it allows you to `#include` existing C header and source files,
it will not know anything about the declarations in them. The `#include`
statement will only appear in the generated C code, to be used by the
C compiler backend itself.

**Example of #include**
```v oksyntax
#include <stdio.h>
```
After this statement, V will *not* know anything about the functions and
structs declared in `stdio.h`, but if you try to compile the .v file,
it will add the include in the generated C code, so that if that header file
is missing, you will get a C error (you will not in this specific case, if you
have a proper C compiler setup, since `<stdio.h>` is part of the
standard C library).

To overcome that limitation (that V does not have a C parser), V needs you to
redeclare the C functions and structs, on the V side, in your `.c.v` files.
Note that such redeclarations only need to have enough details about the
functions/structs that you want to use.
Note also that they *do not have* to be complete, unlike the ones in the .h files.

**C. struct redeclarations**
For example, if a struct has 3 fields on the C side, but you want to only
refer to 1 of them, you can declare it like this:

**Example of C struct redeclaration**
```v oksyntax
struct C.NameOfTheStruct {
	a_field int
}
```
Another feature, that is very frequently needed for C interoperability,
is the `@[typedef]` attribute. It is used for marking `C.` structs,
that are defined with `typedef struct SomeName { ..... } TypeName;` in the C headers.

For that case, you will have to write something like this in your .c.v file:
```v oksyntax
@[typedef]
pub struct C.TypeName {
}
```
Note that the name of the `C.` struct in V, is the one *after* the `struct SomeName {...}`.

**C. function redeclarations**
The situation is similar for `C.` functions. If you are going to call just 1 function in a
library, but its .h header declares dozens of them, you will only need to declare that single
function, for example:

**Example of C function redeclaration**
```v oksyntax
fn C.name_of_the_C_function(param1 int, const_param2 &char, param3 f32) f64
```
... and then later, you will be able to call the same way you would V function:
```v oksyntax
f := C.name_of_the_C_function(123, c'here is some C style string', 1.23)
dump(f)
```

**Example of using a C function from stdio, by redeclaring it on the V side**
```v
#include <stdio.h>

// int dprintf(int fd, const char *format, ...)
fn C.dprintf(fd int, const_format &char, ...voidptr) int

value := 12345
x := C.dprintf(0, c'Hello world, value: %d\n', value)
dump(x)
```

If your C backend compiler is properly setup, you should see something like this, when you try
to run it:
```console
#0 10:42:32 /v/examples> v run a.v
Hello world, value: 12345
[a.v:8] x: 26
#0 10:42:33 /v/examples>
```

Note, that the C function redeclarations look very similar to the V ones, with some differences:
1) They lack a body (they are defined on the C side) .
2) Their names start with `C.` .
3) Their names can have capital letters (unlike V ones, that are required to use snake_case) .

Note also the second parameter `const char *format`, which was redeclared as `const_format &char` .
The `const_` prefix in that redeclaration may seem arbitrary, but it is important, if you want
to compile your code with `-cstrict` or thirdparty C static analysis tools. V currently does not
have another way to express that this parameter is a const (this will probably change in V 1.0).

For some C functions, that use variadics (`...`) as parameters, V supports a special syntax for
the parameters - `...voidptr`, that is not available for ordinary V functions (V's variadics are
*required* to have the same exact type). Usually those are functions of the printf/scanf family
i.e for `printf`, `fprintf`, `scanf`, `sscanf`, etc, and other formatting/parsing/logging
functions.

**Example**

```v
#flag freebsd -I/usr/local/include -L/usr/local/lib
#flag -lsqlite3
#include "sqlite3.h"
// See also the example from https://www.sqlite.org/quickstart.html
pub struct C.sqlite3 {
}

pub struct C.sqlite3_stmt {
}

type FnSqlite3Callback = fn (voidptr, int, &&char, &&char) int

fn C.sqlite3_open(&char, &&C.sqlite3) int

fn C.sqlite3_close(&C.sqlite3) int

fn C.sqlite3_column_int(stmt &C.sqlite3_stmt, n int) int

// ... you can also just define the type of parameter and leave out the C. prefix

fn C.sqlite3_prepare_v2(&C.sqlite3, &char, int, &&C.sqlite3_stmt, &&char) int

fn C.sqlite3_step(&C.sqlite3_stmt)

fn C.sqlite3_finalize(&C.sqlite3_stmt)

fn C.sqlite3_exec(db &C.sqlite3, sql &char, cb FnSqlite3Callback, cb_arg voidptr, emsg &&char) int

fn C.sqlite3_free(voidptr)

fn my_callback(arg voidptr, howmany int, cvalues &&char, cnames &&char) int {
	unsafe {
		for i in 0 .. howmany {
			print('| ${cstring_to_vstring(cnames[i])}: ${cstring_to_vstring(cvalues[i]):20} ')
		}
	}
	println('|')
	return 0
}

fn main() {
	db := &C.sqlite3(unsafe { nil }) // this means `sqlite3* db = 0`
	// passing a string literal to a C function call results in a C string, not a V string
	C.sqlite3_open(c'users.db', &db)
	// C.sqlite3_open(db_path.str, &db)
	query := 'select count(*) from users'
	stmt := &C.sqlite3_stmt(unsafe { nil })
	// Note: You can also use the `.str` field of a V string,
	// to get its C style zero terminated representation
	C.sqlite3_prepare_v2(db, &char(query.str), -1, &stmt, 0)
	C.sqlite3_step(stmt)
	nr_users := C.sqlite3_column_int(stmt, 0)
	C.sqlite3_finalize(stmt)
	println('There are ${nr_users} users in the database.')

	error_msg := &char(unsafe { nil })
	query_all_users := 'select * from users'
	rc := C.sqlite3_exec(db, &char(query_all_users.str), my_callback, voidptr(7), &error_msg)
	if rc != C.SQLITE_OK {
		eprintln(unsafe { cstring_to_vstring(error_msg) })
		C.sqlite3_free(error_msg)
	}
	C.sqlite3_close(db)
}
```

### Calling V from C

Since V can compile to C, calling V code from C is very easy, once you know how.

Use `v -o file.c your_file.v` to generate a C file, corresponding to the V code.

More details in [call_v_from_c example](../examples/call_v_from_c).

### Passing C compilation flags

Add `#flag` directives to the top of your V files to provide C compilation flags like:

- `-I` for adding C include files search paths
- `-l` for adding C library names that you want to get linked
- `-L` for adding C library files search paths
- `-D` for setting compile time variables

You can (optionally) use different flags for different targets.
Currently the `linux`, `darwin` , `freebsd`, and `windows` flags are supported.

> [!NOTE]
> Each flag must go on its own line (for now)

```v oksyntax
#flag linux -lsdl2
#flag linux -Ivig
#flag linux -DCIMGUI_DEFINE_ENUMS_AND_STRUCTS=1
#flag linux -DIMGUI_DISABLE_OBSOLETE_FUNCTIONS=1
#flag linux -DIMGUI_IMPL_API=
```

In the console build command, you can use:

* `-cc` to change the default C backend compiler.
* `-cflags` to pass custom flags to the backend C compiler (passed before other C options).
* `-ldflags` to pass custom flags to the backend C linker (passed after every other C option).
* For example: `-cc gcc-9 -cflags -fsanitize=thread`.

You can define a `VFLAGS` environment variable in your terminal to store your `-cc`
and `-cflags` settings, rather than including them in the build command each time.

### #pkgconfig

Add `#pkgconfig` directives to tell the compiler which modules should be used for compiling
and linking using the pkg-config files provided by the respective dependencies.

As long as backticks can't be used in `#flag` and spawning processes is not desirable for security
and portability reasons, V uses its own pkgconfig library that is compatible with the standard
freedesktop one.

If no flags are passed it will add `--cflags` and `--libs` to pkgconfig (not to V).
In other words, both lines below do the same:

```v oksyntax
#pkgconfig r_core
#pkgconfig --cflags --libs r_core
```

The `.pc` files are looked up into a hardcoded list of default pkg-config paths, the user can add
extra paths by using the `PKG_CONFIG_PATH` environment variable. Multiple modules can be passed.

To check the existence of a pkg-config use `$pkgconfig('pkg')` as a compile time "if" condition to
check if a pkg-config exists. If it exists the branch will be created. Use `$else` or `$else $if`
to handle other cases.

```v ignore
$if $pkgconfig('mysqlclient') {
	#pkgconfig mysqlclient
} $else $if $pkgconfig('mariadb') {
	#pkgconfig mariadb
}
```

### Including C code

You can also include C code directly in your V module.
For example, let's say that your C code is located in a folder named 'c' inside your module folder.
Then:

* Put a v.mod file inside the toplevel folder of your module (if you
  created your module with `v new` you already have v.mod file). For example:

```v ignore
Module {
	name: 'mymodule',
	description: 'My nice module wraps a simple C library.',
	version: '0.0.1'
	dependencies: []
}
```

* Add these lines to the top of your module:

```v oksyntax
#flag -I @VMODROOT/c
#flag @VMODROOT/c/implementation.o
#include "header.h"
```

> [!NOTE]
> @VMODROOT will be replaced by V with the *nearest parent folder,
> where there is a v.mod file*.
> Any .v file beside or below the folder where the v.mod file is,
> can use `#flag @VMODROOT/abc` to refer to this folder.
> The @VMODROOT folder is also *prepended* to the module lookup path,
> so you can *import* other modules under your @VMODROOT, by just naming them.

The instructions above will make V look for an compiled .o file in
your module `folder/c/implementation.o`.
If V finds it, the .o file will get linked to the main executable, that used the module.
If it does not find it, V assumes that there is a `@VMODROOT/c/implementation.c` file,
and tries to compile it to a .o file, then will use that.

This allows you to have C code, that is contained in a V module, so that its distribution is easier.
You can see a complete minimal example for using C code in a V wrapper module here:
[project_with_c_code](https://github.com/vlang/v/tree/master/vlib/v/tests/project_with_c_code).
Another example, demonstrating passing structs from C to V and back again:
[interoperate between C to V to C](https://github.com/vlang/v/tree/master/vlib/v/tests/project_with_c_code_2).

### C types

Ordinary zero terminated C strings can be converted to V strings with
`unsafe { &char(cstring).vstring() }` or if you know their length already with
`unsafe { &char(cstring).vstring_with_len(len) }`.

> [!NOTE]
> The `.vstring()` and `.vstring_with_len()` methods do NOT create a copy of the `cstring`,
> so you should NOT free it after calling the method `.vstring()`.
> If you need to make a copy of the C string (some libc APIs like `getenv` pretty much require that,
> since they return pointers to internal libc memory), you can use `cstring_to_vstring(cstring)`.

On Windows, C APIs often return so called `wide` strings (UTF-16 encoding).
These can be converted to V strings with `string_from_wide(&u16(cwidestring))` .

V has these types for easier interoperability with C:

- `voidptr` for C's `void*`,
- `&u8` for C's `byte*` and
- `&char` for C's `char*`.
- `&&char` for C's `char**`

To cast a `voidptr` to a V reference, use `user := &User(user_void_ptr)`.

`voidptr` can also be dereferenced into a V struct through casting: `user := User(user_void_ptr)`.

[an example of a module that calls C code from V](https://github.com/vlang/v/blob/master/vlib/v/tests/project_with_c_code/mod1/wrapper.c.v)

### C Declarations

C identifiers are accessed with the `C` prefix similarly to how module-specific
identifiers are accessed. Functions must be redeclared in V before they can be used.
Any C types may be used behind the `C` prefix, but types must be redeclared in V in
order to access type members.

To redeclare complex types, such as in the following C code:

```c
struct SomeCStruct {
	uint8_t implTraits;
	uint16_t memPoolData;
	union {
		struct {
			void* data;
			size_t size;
		};

		DataView view;
	};
};
```

members of sub-data-structures may be directly declared in the containing struct as below:

```v
pub struct C.SomeCStruct {
	implTraits  u8
	memPoolData u16
	// These members are part of sub data structures that can't currently be represented in V.
	// Declaring them directly like this is sufficient for access.
	// union {
	// struct {
	data voidptr
	size usize
	// }
	view C.DataView
	// }
}
```

The existence of the data members is made known to V, and they may be used without
re-creating the original structure exactly.

Alternatively, you may [embed](#embedded-structs) the sub-data-structures to maintain
a parallel code structure.

### Export to shared library

By default all V functions have the following naming scheme in C: `[module name]__[fn_name]`.

For example, `fn foo() {}` in module `bar` will result in `bar__foo()`.

To use a custom export name, use the `@[export]` attribute:

```
@[export: 'my_custom_c_name']
fn foo() {
}
```

### Translating C to V

V can translate your C code to human readable V code, and generating V wrappers
on top of C libraries.

C2V currently uses Clang's AST to generate V, so to translate a C file to V
you need to have Clang installed on your machine.

Let's create a simple program `test.c` first:

```c
#include "stdio.h"

int main() {
	for (int i = 0; i < 10; i++) {
		printf("hello world\n");
	}
        return 0;
}
```

Run `v translate test.c`, and V will generate `test.v`:

```v
fn main() {
	for i := 0; i < 10; i++ {
		println('hello world')
	}
}
```

To generate a wrapper on top of a C library use this command:

```bash
v translate wrapper c_code/libsodium/src/libsodium
```

This will generate a directory `libsodium` with a V module.

Example of a C2V generated libsodium wrapper:

https://github.com/vlang/libsodium

<br>

When should you translate C code and when should you simply call C code from V?

If you have well-written, well-tested C code,
then of course you can always simply call this C code from V.

Translating it to V gives you several advantages:

- If you plan to develop that code base, you now have everything in one language,
  which is much safer and easier to develop in than C.
- Cross-compilation becomes a lot easier. You don't have to worry about it at all.
- No more build flags and include files either.

### Working around C issues

In some cases, C interop can be extremely difficult.
One of these such cases is when headers conflict with each other.
For example, V needs to include the Windows header libraries in order for your V binaries to work
seamlessly across all platforms.

However, since the Windows header libraries use extremely generic names such as `Rectangle`,
this will cause a conflict if you wish to use C code that also has a name defined as `Rectangle`.

For very specific cases like this, V has `#preinclude` and `#postinclude` directives.

These directives allow things to be configured *before* V adds in its built in libraries,
and *after* all of the V code generation has completed (and thus all of the prototypes,
declarations and definitions are already present).

Example usage:
```v ignore
// This will include before built in libraries are used.
#preinclude "pre_include.h"

// This will include after built in libraries are used.
#include "include.h"

// This will include after all of the V code generation is complete,
// including the one for the main function of the project
#postinclude "post_include.h"
```

An example of what might be included in `pre_include.h`
can be [found here](https://github.com/irishgreencitrus/raylib.v/blob/main/include/pre.h)

The `#postinclude` directive on the other hand is useful for allowing the integration
of frameworks like SDL3 or Sokol, that insist on having callbacks in your code, instead
of behaving like ordinary libraries, and allowing you to decide when to call them.

NOTE: these are advanced features, and will not be necessary outside of very specific cases
with C interop. Other than those, using them could cause more issues than it solves.

Consider using them as a last resort!

## Other V Features

### Inline assembly

<!-- ignore because it doesn't pass fmt test (why?) -->

```v ignore
a := 100
b := 20
mut c := 0
asm amd64 {
    mov eax, a
    add eax, b
    mov c, eax
    ; =r (c) as c // output
    ; r (a) as a // input
      r (b) as b
}
println('a: ${a}') // 100
println('b: ${b}') // 20
println('c: ${c}') // 120
```

For more examples, see
[vlib/v/slow_tests/assembly/asm_test.amd64.v](https://github.com/vlang/v/tree/master/vlib/v/slow_tests/assembly/asm_test.amd64.v)

### Hot code reloading

```v live
module main

import time

@[live]
fn print_message() {
	println('Hello! Modify this message while the program is running.')
}

fn main() {
	for {
		print_message()
		time.sleep(500 * time.millisecond)
	}
}
```

Build this example with `v -live message.v`.

You can also run this example with `v -live run message.v`.
Make sure that in command you use a path to a V's file,
**not** a path to a folder (like `v -live run .`) -
in that case you need to modify content of a folder (add new file, for example),
because changes in *message.v* will have no effect.

Functions that you want to be reloaded must have `@[live]` attribute
before their definition.

Right now it's not possible to modify types while the program is running.

More examples, including a graphical application:
[github.com/vlang/v/tree/master/examples/hot_reload](https://github.com/vlang/v/tree/master/examples/hot_reload).

#### About keeping states in hot reloading functions with v -live run
V's hot code reloading relies on marking the functions that you want to reload with `@[live]`,
then compiling a shared library of these `@[live]` functions, and then
your v program loads that shared library at runtime.

V (with the -live option) starts a new thread, that monitors the source files for changes,
and when it detects modifications, it recompiles the shared library, and reloads it at runtime,
so that new calls to those @[live] functions will be made to the newly loaded library.

It keeps all the accumulated state (from locals outside the @[live] functions,
from heap variables and from globals), allowing to tweak the code in the merged functions quickly.

When there are more substantial changes (to data structures, or to functions that were not marked),
you will have to restart the running app manually.

### Cross-platform shell scripts in V

V can be used as an alternative to Bash to write deployment scripts, build scripts, etc.

The advantage of using V for this, is the simplicity and predictability of the language, and
cross-platform support. "V scripts" run on Unix-like systems, as well as on Windows.

To use V's script mode, save your source file with the `.vsh` file extension.
It will make all functions in the `os` module global (so that you can use `mkdir()` instead
of `os.mkdir()`, for example).

V also knows to compile & run `.vsh` files immediately, so you do not need a separate
step to compile them. V will also recompile an executable, produced by a `.vsh` file,
*only when it is older than the .vsh source file*, i.e. runs after the first one, will
be faster, since there is no need for a re-compilation of a script, that has not been changed.

An example `deploy.vsh`:

```v oksyntax
#!/usr/bin/env -S v

// Note: The shebang line above, associates the .vsh file to V on Unix-like systems,
// so it can be run just by specifying the path to the .vsh file, once it's made
// executable, using `chmod +x deploy.vsh`, i.e. after that chmod command, you can
// run the .vsh script, by just typing its name/path like this: `./deploy.vsh`

// print command then execute it
fn sh(cmd string) {
	println('❯ ${cmd}')
	print(execute_or_exit(cmd).output)
}

// Remove if build/ exits, ignore any errors if it doesn't
rmdir_all('build') or {}

// Create build/, never fails as build/ does not exist
mkdir('build')!

// Move *.v files to build/
result := execute('mv *.v build/')
if result.exit_code != 0 {
	println(result.output)
}

sh('ls')

// Similar to:
// files := ls('.')!
// mut count := 0
// if files.len > 0 {
//     for file in files {
//         if file.ends_with('.v') {
//              mv(file, 'build/') or {
//                  println('err: ${err}')
//                  return
//              }
//         }
//         count++
//     }
// }
// if count == 0 {
//     println('No files')
// }
```

Now you can either compile this like a normal V program and get an executable you can deploy and run
anywhere:
`v -skip-running deploy.vsh && ./deploy`

Or run it like a traditional Bash script:
`v run deploy.vsh` (or simply just `v deploy.vsh`)

On Unix-like platforms, the file can be run directly after making it executable using `chmod +x`:
`./deploy.vsh`

### Vsh scripts with no extension

Whilst V does normally not allow vsh scripts without the designated file extension, there is a way
to circumvent this rule and have a file with a fully custom name and shebang. Whilst this feature
exists it is only recommended for specific usecases like scripts that will be put in the path and
should **not** be used for things like build or deploy scripts. To access this feature start the
file with `#!/usr/bin/env -S v -raw-vsh-tmp-prefix tmp` where `tmp` is the prefix for
the built executable. This will run in crun mode so it will only rebuild if changes to the script
were made and keep the binary as `tmp.<scriptfilename>`. **Caution**: if this filename already
exists the file will be overridden. If you want to rebuild each time and not keep this binary
instead use `#!/usr/bin/env -S v -raw-vsh-tmp-prefix tmp run`.

Note: there is a small shell script `cmd/tools/vrun`, that can be useful for systems, that have an
env program (`/usr/bin/env`), that still does not support an `-S` option (like BusyBox).
See https://github.com/vlang/v/blob/master/cmd/tools/vrun for more details.

# Appendices

## Appendix I: Keywords

V has 45 reserved keywords (3 are literals):

```v ignore
as
asm
assert
atomic
break
const
continue
defer
else
enum
false
fn
for
go
goto
if
implements
import
in
interface
is
isreftype
lock
match
module
mut
none
or
pub
return
rlock
select
shared
sizeof
spawn
static
struct
true
type
typeof
union
unsafe
volatile
__global
__offsetof
```

See also [V Types](#v-types).

## Appendix II: Operators

This lists operators for [primitive types](#primitive-types) only.

```v ignore
+    sum                    integers, floats, strings
-    difference             integers, floats
*    product                integers, floats
/    quotient               integers, floats
%    remainder              integers

~    bitwise NOT            integers
&    bitwise AND            integers
|    bitwise OR             integers
^    bitwise XOR            integers

!    logical NOT            bools
&&   logical AND            bools
||   logical OR             bools
!=   logical XOR            bools

<<   left shift             integer << unsigned integer
>>   right shift            integer >> unsigned integer
>>>  unsigned right shift   integer >> unsigned integer

Precedence    Operator
    5            *  /  %  <<  >> >>> &
    4            +  -  |  ^
    3            ==  !=  <  <=  >  >=
    2            &&
    1            ||

Assignment Operators
+=   -=   *=   /=   %=
&=   |=   ^=
>>=  <<=  >>>=
&&= ||=
```

Note: in V, `assert -10 % 7 == -3` passes. In programming, the sign of the remainder
depends upon the signs of divisor and dividend.

## Other online resources

### [V contributing guide](https://github.com/vlang/v/blob/master/CONTRIBUTING.md)

V would be much less, than what it is today, without the help of all
its contributors. If you like and want to help the V project succeed,
please read that document, choose a task, and dive in!

### [V language documentation](https://docs.vlang.io/introduction.html)
The site has the same information as this document, but split to pages,
for easier reading on mobile devices. Updated automatically on each
commit to the main repository.

### [V standard module documentation](https://modules.vlang.io/)
The site has the documentation of all the modules in V's standard
library (vlib). Updated automatically on each commit to the main
repository.

### [V online playground](https://play.vlang.io/)
The site allows you to enter and edit small V programs, then compile
and run them. Updated automatically on each commit to the main
repository. Use it, to test your ideas, when you do not have access
to a computer or an Android phone, where V has been already installed.

### [Awesome V](https://github.com/vlang/awesome-v)
When you make a cool new project or a library, you can submit it to that
list too. You can also use the list, for ideas about new projects to do
with V.

### [The V language Discord](https://discord.gg/vlang)
This is the place to be, to discuss the V language, learn about latest
developments, quickly get help with issues, witness/participate in
~~epic flame wars~~ constructive criticism exchanges and design decisions.
Join it, and learn more about languages, games, editors, people, Klingons,
Conway's law and the universe.
