# Rust Programming Tutorial for Beginners

## Table of Contents
1. [Introduction to Rust](#introduction-to-rust)
2. [Installation and Setup](#installation-and-setup)
3. [Basic Syntax](#basic-syntax)
4. [Variables and Mutability](#variables-and-mutability)
5. [Data Types](#data-types)
6. [Functions](#functions)
7. [Control Flow](#control-flow)
8. [Ownership and Borrowing](#ownership-and-borrowing)
9. [Structs](#structs)
10. [Enums](#enums)
11. [Collections](#collections)
12. [Error Handling](#error-handling)
13. [Modules and Crates](#modules-and-crates)
14. [Testing](#testing)
15. [Standard Library Features](#standard-library-features)

## Introduction to Rust

Rust is a systems programming language that runs blazingly fast, prevents segfaults, and guarantees thread safety. It's designed for performance and safety, especially safe concurrency.

## Installation and Setup

First, install Rust using rustup:
```bash
curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh
```

Create a new project:
```bash
cargo new rust_tutorial
cd rust_tutorial
```

## Basic Syntax

Let's start with the classic "Hello, World!" program:

```rust
// main.rs
fn main() {
    // Print text to the console
    println!("Hello, World!");
}
```

Run with:
```bash
cargo run
```

## Variables and Mutability

In Rust, variables are immutable by default. Use `mut` to make them mutable.

```rust
fn main() {
    // Immutable variable
    let x = 5;
    println!("The value of x is: {}", x);
    
    // This would cause a compile error:
    // x = 6;
    
    // Mutable variable
    let mut y = 10;
    println!("The value of y is: {}", y);
    y = 15; // This is allowed
    println!("The value of y is now: {}", y);
    
    // Constants (always immutable)
    const MAX_POINTS: u32 = 100_000;
    println!("Max points: {}", MAX_POINTS);
    
    // Shadowing - redeclaring a variable with the same name
    let z = 5;
    let z = z + 1; // z is now 6
    let z = z * 2; // z is now 12
    println!("The value of z is: {}", z);
}
```

## Data Types

Rust has two data type subsets: scalar and compound.

### Scalar Types

```rust
fn main() {
    // Integers
    let a: i32 = 98_222; // 32-bit signed integer
    let b: u32 = 98_222; // 32-bit unsigned integer
    let c: i64 = 98_222; // 64-bit signed integer
    
    // Floating-point numbers
    let x = 2.0; // f64 by default
    let y: f32 = 3.0; // 32-bit float
    
    // Boolean
    let t = true;
    let f: bool = false;
    
    // Characters (Unicode scalar values)
    let c = 'z';
    let z = 'ℤ';
    let heart_eyed_cat = '😻';
    
    println!("a: {}, b: {}, c: {}", a, b, c);
    println!("x: {}, y: {}", x, y);
    println!("t: {}, f: {}", t, f);
    println!("Characters: {}, {}, {}", c, z, heart_eyed_cat);
}
```

### Compound Types

```rust
fn main() {
    // Tuples - fixed size collection of different types
    let tup: (i32, f64, u8) = (500, 6.4, 1);
    let (x, y, z) = tup; // Destructuring
    println!("Tuple values: {}, {}, {}", x, y, z);
    println!("First element: {}", tup.0); // Access by index
    
    // Arrays - fixed size collection of same type
    let a = [1, 2, 3, 4, 5];
    let b: [i32; 5] = [1, 2, 3, 4, 5]; // Type annotation: [type; size]
    let c = [3; 5]; // Initialize array with 5 elements of value 3
    
    println!("First element of a: {}", a[0]);
    println!("Second element of b: {}", b[1]);
    println!("Array c: {:?}", c); // Debug print for arrays
}
```

## Functions

```rust
fn main() {
    println!("Hello from main!");
    another_function();
    print_number(5);
    let result = add(3, 7);
    println!("3 + 7 = {}", result);
    
    let result2 = plus_one(10);
    println!("10 + 1 = {}", result2);
}

// Function with no parameters
fn another_function() {
    println!("Another function.");
}

// Function with parameters
fn print_number(x: i32) {
    println!("The number is: {}", x);
}

// Function with return value
fn add(x: i32, y: i32) -> i32 {
    x + y // No semicolon means this is returned
}

// Alternative return syntax
fn plus_one(x: i32) -> i32 {
    return x + 1; // Explicit return
}
```

## Control Flow

### If Expressions

```rust
fn main() {
    let number = 3;
    
    // Basic if statement
    if number < 5 {
        println!("condition was true");
    } else {
        println!("condition was false");
    }
    
    // Multiple conditions
    let number = 6;
    if number % 4 == 0 {
        println!("number is divisible by 4");
    } else if number % 3 == 0 {
        println!("number is divisible by 3");
    } else if number % 2 == 0 {
        println!("number is divisible by 2");
    } else {
        println!("number is not divisible by 4, 3, or 2");
    }
    
    // If as an expression
    let condition = true;
    let number = if condition { 5 } else { 6 };
    println!("The value of number is: {}", number);
}
```

### Loops

```rust
fn main() {
    // Loop forever (until break)
    let mut counter = 0;
    let result = loop {
        counter += 1;
        if counter == 10 {
            break counter * 2; // Return value from loop
        }
    };
    println!("Result: {}", result);
    
    // While loop
    let mut number = 3;
    while number != 0 {
        println!("{}!", number);
        number -= 1;
    }
    println!("LIFTOFF!!!");
    
    // For loop with range
    for number in (1..4).rev() {
        println!("{}!", number);
    }
    println!("LIFTOFF!!!");
    
    // For loop with array
    let a = [10, 20, 30, 40, 50];
    for element in a.iter() {
        println!("the value is: {}", element);
    }
}
```

## Ownership and Borrowing

This is one of Rust's most unique features.

```rust
fn main() {
    // Ownership example
    let s1 = String::from("hello");
    let s2 = s1; // s1 is moved to s2, s1 is no longer valid
    
    // This would cause a compile error:
    // println!("{}, world!", s1);
    
    println!("{}, world!", s2);
    
    // Cloning to create a deep copy
    let s1 = String::from("hello");
    let s2 = s1.clone();
    println!("s1 = {}, s2 = {}", s1, s2);
    
    // Borrowing with references
    let s1 = String::from("hello");
    let len = calculate_length(&s1); // &s1 creates a reference
    println!("The length of '{}' is {}.", s1, len);
    
    // Mutable references
    let mut s = String::from("hello");
    change(&mut s);
    println!("{}", s);
    
    // Multiple mutable references (only one allowed at a time)
    let mut s = String::from("hello");
    {
        let r1 = &mut s;
        println!("{}", r1);
    } // r1 goes out of scope here
    
    let r2 = &mut s; // This is allowed now
    println!("{}", r2);
}

fn calculate_length(s: &String) -> usize {
    s.len() // s is a reference, so it doesn't take ownership
} // s goes out of scope but nothing happens since it doesn't own the data

fn change(s: &mut String) {
    s.push_str(", world");
}
```

## Structs

```rust
// Define a struct
struct User {
    username: String,
    email: String,
    sign_in_count: u64,
    active: bool,
}

// Tuple structs
struct Color(i32, i32, i32);
struct Point(i32, i32, i32);

fn main() {
    // Create an instance of a struct
    let user1 = User {
        email: String::from("someone@example.com"),
        username: String::from("someusername123"),
        active: true,
        sign_in_count: 1,
    };
    
    // Access fields
    println!("User email: {}", user1.email);
    
    // Create mutable instance
    let mut user2 = User {
        email: String::from("another@example.com"),
        username: String::from("anotherusername567"),
        active: true,
        sign_in_count: 1,
    };
    
    user2.email = String::from("newemail@example.com");
    println!("Updated email: {}", user2.email);
    
    // Create instance using function
    let user3 = build_user(
        String::from("user3@example.com"),
        String::from("user3name"),
    );
    
    // Struct update syntax
    let user4 = User {
        email: String::from("user4@example.com"),
        username: String::from("user4name"),
        ..user1 // Fill remaining fields with user1's values
    };
    
    // Tuple structs
    let black = Color(0, 0, 0);
    let origin = Point(0, 0, 0);
    
    println!("Black color: {}, {}, {}", black.0, black.1, black.2);
    println!("Origin point: {}, {}, {}", origin.0, origin.1, origin.2);
    
    // Example with methods
    let rect1 = Rectangle {
        width: 30,
        height: 50,
    };
    
    println!(
        "The area of the rectangle is {} square pixels.",
        rect1.area()
    );
}

// Function to create User instance
fn build_user(email: String, username: String) -> User {
    User {
        email,
        username,
        active: true,
        sign_in_count: 1,
    }
}

// Define another struct for method example
struct Rectangle {
    width: u32,
    height: u32,
}

// Implementation block for Rectangle
impl Rectangle {
    // Method (first parameter is always &self)
    fn area(&self) -> u32 {
        self.width * self.height
    }
    
    // Method with parameters
    fn can_hold(&self, other: &Rectangle) -> bool {
        self.width > other.width && self.height > other.height
    }
    
    // Associated function (no self parameter)
    fn square(size: u32) -> Rectangle {
        Rectangle {
            width: size,
            height: size,
        }
    }
}
```

## Enums

```rust
// Basic enum
enum IpAddrKind {
    V4,
    V6,
}

// Enum with data
enum IpAddr {
    V4(String),
    V6(String),
}

// More complex enum with different data types
enum Message {
    Quit,
    Move { x: i32, y: i32 },
    Write(String),
    ChangeColor(i32, i32, i32),
}

// Implement methods on enums
impl Message {
    fn call(&self) {
        // Method body would be defined here
    }
}

fn main() {
    // Using basic enum
    let four = IpAddrKind::V4;
    let six = IpAddrKind::V6;
    
    // Using enum with data
    let home = IpAddr::V4(String::from("127.0.0.1"));
    let loopback = IpAddr::V6(String::from("::1"));
    
    // Using complex enum
    let m = Message::Write(String::from("hello"));
    m.call();
    
    // Option enum (built-in enum for handling null values)
    let some_number = Some(5);
    let some_string = Some("a string");
    let absent_number: Option<i32> = None;
    
    println!("{:?}", some_number);
    println!("{:?}", some_string);
    println!("{:?}", absent_number);
    
    // Match expression with enum
    let coin = Coin::Penny;
    println!("Value in cents: {}", value_in_cents(coin));
}

// Another enum example
enum Coin {
    Penny,
    Nickel,
    Dime,
    Quarter,
}

fn value_in_cents(coin: Coin) -> u8 {
    match coin {
        Coin::Penny => 1,
        Coin::Nickel => 5,
        Coin::Dime => 10,
        Coin::Quarter => 25,
    }
}
```

## Collections

### Vectors

```rust
fn main() {
    // Creating vectors
    let v: Vec<i32> = Vec::new(); // Empty vector with type annotation
    let v = vec![1, 2, 3]; // Macro to create vector with values
    
    // Updating vectors
    let mut v = Vec::new();
    v.push(5);
    v.push(6);
    v.push(7);
    v.push(8);
    
    // Reading elements
    let v = vec![1, 2, 3, 4, 5];
    
    let third: &i32 = &v[2]; // Will panic if index is out of bounds
    println!("The third element is {}", third);
    
    match v.get(2) {
        Some(third) => println!("The third element is {}", third),
        None => println!("There is no third element."),
    }
    
    // Iterating over vectors
    let v = vec![100, 32, 57];
    for i in &v {
        println!("{}", i);
    }
    
    // Iterating over mutable references
    let mut v = vec![100, 32, 57];
    for i in &mut v {
        *i += 50;
    }
    println!("{:?}", v);
    
    // Using enums to store different types in a vector
    enum SpreadsheetCell {
        Int(i32),
        Float(f64),
        Text(String),
    }
    
    let row = vec![
        SpreadsheetCell::Int(3),
        SpreadsheetCell::Text(String::from("blue")),
        SpreadsheetCell::Float(10.12),
    ];
    
    for cell in &row {
        match cell {
            SpreadsheetCell::Int(i) => println!("Int: {}", i),
            SpreadsheetCell::Float(f) => println!("Float: {}", f),
            SpreadsheetCell::Text(s) => println!("Text: {}", s),
        }
    }
}
```

### Strings

```rust
fn main() {
    // Creating strings
    let mut s = String::new(); // Empty string
    let data = "initial contents";
    let s = data.to_string(); // Convert string literal to String
    let s = String::from("initial contents"); // Another way
    
    // Updating strings
    let mut s = String::from("foo");
    s.push_str("bar"); // Append string slice
    println!("{}", s);
    
    let mut s = String::from("lo");
    s.push('l'); // Append single character
    println!("{}", s);
    
    // Concatenation
    let s1 = String::from("Hello, ");
    let s2 = String::from("world!");
    let s3 = s1 + &s2; // s1 is moved and can no longer be used
    println!("{}", s3);
    
    // Multiple concatenation with format!
    let s1 = String::from("tic");
    let s2 = String::from("tac");
    let s3 = String::from("toe");
    
    let s = format!("{}-{}-{}", s1, s2, s3);
    println!("{}", s);
    
    // Indexing strings (not directly supported due to UTF-8)
    let hello = String::from("Здравствуйте");
    
    // This would cause a compile error:
    // let s = &hello[0];
    
    // Slicing strings (be careful with UTF-8)
    let hello = "Здравствуйте";
    let s = &hello[0..4]; // First 4 bytes
    println!("{}", s);
    
    // Iterating over strings
    for c in "नमस्ते".chars() {
        println!("{}", c);
    }
    
    for b in "नमस्ते".bytes() {
        println!("{}", b);
    }
}
```

### Hash Maps

```rust
use std::collections::HashMap;

fn main() {
    // Creating hash maps
    let mut scores = HashMap::new();
    scores.insert(String::from("Blue"), 10);
    scores.insert(String::from("Yellow"), 50);
    
    // Creating from vectors
    let teams = vec![String::from("Blue"), String::from("Yellow")];
    let initial_scores = vec![10, 50];
    
    let scores: HashMap<_, _> = teams.iter().zip(initial_scores.iter()).collect();
    
    // Accessing values
    let mut scores = HashMap::new();
    scores.insert(String::from("Blue"), 10);
    scores.insert(String::from("Yellow"), 50);
    
    let team_name = String::from("Blue");
    let score = scores.get(&team_name);
    
    match score {
        Some(s) => println!("Score: {}", s),
        None => println!("Team not found"),
    }
    
    // Iterating over hash maps
    for (key, value) in &scores {
        println!("{}: {}", key, value);
    }
    
    // Updating values
    let mut scores = HashMap::new();
    scores.insert(String::from("Blue"), 10);
    
    // Overwriting a value
    scores.insert(String::from("Blue"), 25);
    println!("{:?}", scores);
    
    // Only inserting if key has no value
    scores.entry(String::from("Yellow")).or_insert(50);
    scores.entry(String::from("Blue")).or_insert(50);
    println!("{:?}", scores);
    
    // Updating based on old value
    let text = "hello world wonderful world";
    let mut map = HashMap::new();
    
    for word in text.split_whitespace() {
        let count = map.entry(word).or_insert(0);
        *count += 1;
    }
    
    println!("{:?}", map);
}
```

## Error Handling

```rust
use std::fs::File;
use std::io::ErrorKind;

fn main() {
    // Unrecoverable errors with panic!
    // panic!("crash and burn");
    
    // Recoverable errors with Result
    let f = File::open("hello.txt");
    
    let f = match f {
        Ok(file) => file,
        Err(error) => match error.kind() {
            ErrorKind::NotFound => match File::create("hello.txt") {
                Ok(fc) => fc,
                Err(e) => panic!("Problem creating the file: {:?}", e),
            },
            other_error => {
                panic!("Problem opening the file: {:?}", other_error)
            }
        },
    };
    
    // Shortcuts for panic on error: unwrap and expect
    // let f = File::open("hello.txt").unwrap();
    // let f = File::open("hello.txt").expect("Failed to open hello.txt");
    
    // Propagating errors with ?
    let result = read_username_from_file();
    println!("{:?}", result);
}

fn read_username_from_file() -> Result<String, std::io::Error> {
    let mut s = String::new();
    
    File::open("hello.txt")?.read_to_string(&mut s)?;
    
    Ok(s)
}

// Alternative implementation using fs::read_to_string
use std::fs;
use std::io;

fn read_username_from_file_short() -> Result<String, io::Error> {
    fs::read_to_string("hello.txt")
}
```

## Modules and Crates

```rust
// main.rs
mod sound {
    pub mod instrument {
        pub fn guitar() {
            println!("Playing guitar!");
        }
        
        fn private_function() {
            println!("This is private");
        }
    }
    
    pub mod voice {
        pub fn sing() {
            println!("Singing!");
        }
    }
}

// Using items from modules
use sound::instrument;
use sound::voice::sing;

fn main() {
    // Absolute path
    crate::sound::instrument::guitar();
    
    // Relative path
    sound::instrument::guitar();
    
    // Using imported items
    instrument::guitar();
    sing();
    
    // Using nested paths
    use sound::{instrument::guitar, voice::sing};
    guitar();
    sing();
    
    // Using glob operator
    use sound::instrument::*;
    guitar(); // This works because of the glob import
}
```

## Testing

```rust
// lib.rs
pub fn add_two(a: i32) -> i32 {
    a + 2
}

pub fn greeting(name: &str) -> String {
    format!("Hello {}!", name)
}

pub struct Guess {
    value: i32,
}

impl Guess {
    pub fn new(value: i32) -> Guess {
        if value < 1 || value > 100 {
            panic!("Guess value must be between 1 and 100, got {}.", value);
        }
        
        Guess { value }
    }
}

#[cfg(test)]
mod tests {
    use super::*; // Import everything from parent module
    
    #[test]
    fn exploration() {
        assert_eq!(2 + 2, 4);
    }
    
    #[test]
    fn another() {
        panic!("Make this test fail");
    }
    
    #[test]
    fn it_adds_two() {
        assert_eq!(4, add_two(2));
    }
    
    #[test]
    fn greeting_contains_name() {
        let result = greeting("Carol");
        assert!(
            result.contains("Carol"),
            "Greeting did not contain name, value was `{}`",
            result
        );
    }
    
    #[test]
    #[should_panic]
    fn greater_than_100() {
        Guess::new(200);
    }
    
    #[test]
    #[should_panic(expected = "Guess value must be between 1 and 100")]
    fn greater_than_100_detailed() {
        Guess::new(200);
    }
}
```

Run tests with:
```bash
cargo test
```

## Standard Library Features

### Iterators

```rust
fn main() {
    let v1 = vec![1, 2, 3];
    
    // Creating an iterator
    let v1_iter = v1.iter();
    
    // Using iterator with for loop
    for val in v1_iter {
        println!("Got: {}", val);
    }
    
    // Using iterator methods
    let v1 = vec![1, 2, 3];
    let v2: Vec<i32> = v1.iter().map(|x| x + 1).collect();
    println!("{:?}", v2);
    
    // Filter example
    let v1: Vec<i32> = vec![1, 2, 3, 4, 5, 6];
    let v2: Vec<i32> = v1.into_iter().filter(|x| x % 2 == 0).collect();
    println!("{:?}", v2);
}
```

### Closures

```rust
fn main() {
    // Defining a closure
    let add_one = |x: i32| x + 1;
    println!("5 + 1 = {}", add_one(5));
    
    // Closure with multiple parameters
    let add = |x, y| x + y;
    println!("3 + 7 = {}", add(3, 7));
    
    // Closure that captures environment
    let x = 4;
    let equal_to_x = |z| z == x;
    println!("{}", equal_to_x(4));
    
    // Using closures with iterators
    let v1 = vec![1, 2, 3];
    let v2: Vec<_> = v1.iter().map(|x| x + 1).collect();
    println!("{:?}", v2);
}
```

### Smart Pointers

```rust
use std::boxed::Box;

fn main() {
    // Box - heap allocated pointer
    let b = Box::new(5);
    println!("b = {}", b);
    
    // Recursive types with Box
    enum List {
        Cons(i32, Box<List>),
        Nil,
    }
    
    use List::{Cons, Nil};
    
    let list = Cons(1, 
        Box::new(Cons(2, 
            Box::new(Cons(3, 
                Box::new(Nil))))));
    
    // Rc - Reference counted pointer (for multiple ownership)
    use std::rc::Rc;
    
    enum ListRc {
        ConsRc(i32, Rc<ListRc>),
        NilRc,
    }
    
    use ListRc::{ConsRc, NilRc};
    
    let a = Rc::new(ConsRc(5, Rc::new(ConsRc(10, Rc::new(NilRc)))));
    println!("count after creating a = {}", Rc::strong_count(&a));
    
    let _b = ConsRc(3, Rc::clone(&a));
    println!("count after creating b = {}", Rc::strong_count(&a));
    
    {
        let _c = ConsRc(4, Rc::clone(&a));
        println!("count after creating c = {}", Rc::strong_count(&a));
    }
    
    println!("count after c goes out of scope = {}", Rc::strong_count(&a));
}
```

### Concurrency

```rust
use std::thread;
use std::time::Duration;

fn main() {
    // Creating threads
    let handle = thread::spawn(|| {
        for i in 1..10 {
            println!("hi number {} from the spawned thread!", i);
            thread::sleep(Duration::from_millis(1));
        }
    });
    
    for i in 1..5 {
        println!("hi number {} from the main thread!", i);
        thread::sleep(Duration::from_millis(1));
    }
    
    handle.join().unwrap();
    
    // Moving values into threads
    let v = vec![1, 2, 3];
    
    let handle = thread::spawn(move || {
        println!("Here's a vector: {:?}", v);
    });
    
    handle.join().unwrap();
}
```

### Channels for Message Passing

```rust
use std::sync::mpsc;
use std::thread;
use std::time::Duration;

fn main() {
    // Creating a channel
    let (tx, rx) = mpsc::channel();
    
    thread::spawn(move || {
        let val = String::from("hi");
        tx.send(val).unwrap();
        // println!("val is {}", val); // This would cause a compile error
    });
    
    let received = rx.recv().unwrap();
    println!("Got: {}", received);
    
    // Sending multiple values
    let (tx, rx) = mpsc::channel();
    
    thread::spawn(move || {
        let vals = vec![
            String::from("hi"),
            String::from("from"),
            String::from("the"),
            String::from("thread"),
        ];
        
        for val in vals {
            tx.send(val).unwrap();
            thread::sleep(Duration::from_secs(1));
        }
    });
    
    for received in rx {
        println!("Got: {}", received);
    }
    
    // Multiple producers
    let (tx, rx) = mpsc::channel();
    let tx1 = tx.clone();
    
    thread::spawn(move || {
        let vals = vec![
            String::from("hi"),
            String::from("from"),
            String::from("the"),
            String::from("thread"),
        ];
        
        for val in vals {
            tx1.send(val).unwrap();
            thread::sleep(Duration::from_secs(1));
        }
    });
    
    thread::spawn(move || {
        let vals = vec![
            String::from("more"),
            String::from("messages"),
            String::from("for"),
            String::from("you"),
        ];
        
        for val in vals {
            tx.send(val).unwrap();
            thread::sleep(Duration::from_secs(1));
        }
    });
    
    for received in rx {
        println!("Got: {}", received);
    }
}
```

This tutorial covers the core concepts of Rust programming. Each section includes runnable code examples that you can copy, paste, and experiment with. The comments explain what each part does, making it accessible even for those new to programming.

To practice, create a new Rust project with `cargo new rust_practice` and try modifying these examples or combining concepts from different sections. The Rust compiler is very helpful with error messages, so don't be afraid to experiment and see what happens!