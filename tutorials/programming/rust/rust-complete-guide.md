# The Rust Programming Language: A Comprehensive Textbook Guide for Apple Silicon macOS

Welcome to the ultimate learning guide for the Rust programming language (2021/2024 Edition) on macOS (ARM64 Apple Silicon)! This textbook is structured specifically to take you from a complete beginner (zero programming experience) to an advanced Rust systems developer capable of building fast, memory-safe, concurrent, and reliable applications. Rather than treating Rust as just a compiler with strict rules, this guide emphasizes practical rustacean idioms: ownership and borrowing rules, error handling without null/exceptions, zero-cost abstractions, pattern matching, traits, Cargo workflows, and async concurrency.

> [!NOTE]
> **How to read this book:** Each section starts with a clear explanation of core Rust concepts, followed by concrete, runnable code examples. Every example is tested for `cargo` on macOS ARM64 using standard Rust edition tools.

> [!TIP]
> **Interactive Learning:** You can test any Rust code snippet live in your browser using the official [Rust Playground (play.rust-lang.org)](https://play.rust-lang.org/).

## Repository Structure

This book is paired with a topic-based repository layout:

- `variables_and_types/` for primitives, mutability, tuple destructuring, and shadowing
- `ownership_and_borrowing/` for move semantics, references (`&T`, `&mut T`), and slice views
- `structs_and_enums/` for modeling domain data, pattern matching with `match`, and `if let`
- `error_handling/` for `Result<T, E>`, `Option<T>`, panic recovery, and the `?` operator
- `traits_and_generics/` for zero-cost polymorphism, trait bounds, and generic parameters
- `concurrency_and_tokio/` for multithreading with `Arc`/`Mutex` and asynchronous Rust with Tokio

## Quick Start: Learn Rust by Building Things

1. Install Rust via `rustup` on macOS:
   ```bash
   curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh
   ```
2. Verify Cargo and compiler installation:
   ```bash
   cargo --version
   rustc --version
   ```
3. Initialize a new binary package:
   ```bash
   cargo new hello_rust
   cd hello_rust
   ```
4. Write your first program in `src/main.rs`:
   ```rust
   fn main() {
       println!("Hello, Rust on Apple Silicon ARM64!");
   }
   ```
5. Compile and run:
   ```bash
   cargo run
   ```

Rust fundamentals to remember early:
- Variables are **immutable by default**. Use `let mut` when you need to mutate a value.
- Ownership rules prevent memory bugs at compile time: each value has exactly one owner at a time.
- Standard error handling relies on `Option` and `Result` types rather than exceptions or null pointers.
- Pattern matching (`match`) enforces exhaustive checking at compile time.

## Core Language Essentials to Learn Early

- Immutable (`let`) vs Mutable (`let mut`) variables and shadowing
- Primitive scalar types (`i32`, `f64`, `bool`, `char`) and compound types (`tuples`, `arrays`)
- Ownership, moves, references (`&`), mutable references (`&mut`), and lifetimes (`'a`)
- Structs (`struct`), Tuples, and Methods (`impl`)
- Enums (`enum`), `Option<T>`, and pattern matching with `match`
- Error handling with `Result<T, E>` and `?` operator
- Iterators (`iter()`, `map()`, `filter()`, `collect()`)
- Crates, modules (`mod`), and Cargo package management

## Must-Learn-Before-Building Checklist

Before building real-world projects, ensure you can:

- Create, build, and run projects with `cargo`
- Explain why a value is moved vs borrowed
- Create mutable references without triggering compile-time borrow checker conflicts
- Define structs and write methods inside an `impl` block
- Handle optional values using `Option` and failing operations using `Result`
- Work with standard collections (`Vec<T>`, `HashMap<K, V>`)

## Why This Matters

Rust gives you C/C++ level hardware control and execution speed while guaranteeing compile-time memory safety, eliminating data races, double-frees, null dereferences, and buffer overflows.

## Suggested Learning Path

- **Fundamentals**: Variables, primitive types, functions, control flow.
- **Ownership Core**: Deep dive into move semantics, borrowing, and slices.
- **Data Modeling**: Structs, Enums, Traits, and Pattern Matching.
- **Error Handling & Collections**: `Result`, `Option`, `Vec`, `HashMap`, custom errors.
- **Systems & Concurrency**: Channels (`mpsc`), `Arc<Mutex<T>>`, and Tokio async tasks.

## Practice Exercises

1. Write a function that counts vowels in a string slice `&str`.
2. Build a struct `Rectangle` with methods `area()` and `can_hold(&self, other: &Rectangle)`.
3. Create a CLI program that parses command line arguments and reads a file.
4. Implement a custom trait `Summarizable` for `Article` and `Tweet` structs.
5. Create a concurrent counter using `Arc<Mutex<u32>>` across 5 OS threads.

---

## Textbook Chapters & Runnable Examples

### Chapter 1: Variables, Mutability, and Primitive Types

```rust
// 01_variables.rs
fn main() {
    // Immutable variable
    let x = 5;
    println!("Immutable x: {}", x);
    
    // Mutable variable
    let mut y = 10;
    println!("Initial y: {}", y);
    y += 5;
    println!("Mutated y: {}", y);
    
    // Shadowing
    let z = 5;
    let z = z + 1; // Shadowed z is now 6
    let z = z * 2; // Shadowed z is now 12
    println!("Shadowed z: {}", z);
    
    // Scalar & Compound types
    let price: f64 = 99.99;
    let is_active: bool = true;
    let tuple: (&str, i32) = ("Rust", 2024);
    let (lang, year) = tuple;
    
    println!("Language: {} {}, Price: {}, Active: {}", lang, year, price, is_active);
}
```

### Chapter 2: Ownership, Borrowing, and References

```rust
// 02_ownership.rs
fn main() {
    // Move Semantics (Heap Ownership Transfer)
    let s1 = String::from("Hello Rust");
    let s2 = s1; // s1 is moved to s2; s1 is no longer valid!
    // println!("{}", s1); // Compile error!
    println!("s2 owns the string: {}", s2);
    
    // Borrowing via Immutable References (&T)
    let length = calculate_length(&s2);
    println!("Length of '{}' is {}", s2, length);
    
    // Borrowing via Mutable References (&mut T)
    let mut greeting = String::from("Hello");
    append_world(&mut greeting);
    println!("Mutated string: {}", greeting);
}

fn calculate_length(s: &String) -> usize {
    s.len()
}

fn append_world(s: &mut String) {
    s.push_str(", World!");
}
```

### Chapter 3: Structs, Methods, and Enums with Pattern Matching

```rust
// 03_structs_enums.rs
#[derive(Debug)]
struct User {
    username: String,
    email: String,
    sign_in_count: u64,
    active: bool,
}

impl User {
    fn new(username: &str, email: &str) -> Self {
        Self {
            username: username.to_string(),
            email: email.to_string(),
            sign_in_count: 1,
            active: true,
        }
    }

    fn display(&self) {
        println!("User {} ({}) - Active: {}", self.username, self.email, self.active);
    }
}

#[derive(Debug)]
enum Command {
    Quit,
    Move { x: i32, y: i32 },
    Write(String),
}

fn execute_command(cmd: Command) {
    match cmd {
        Command::Quit => println!("Quitting application."),
        Command::Move { x, y } => println!("Moving cursor to ({}, {})", x, y),
        Command::Write(text) => println!("Writing text: {}", text),
    }
}

fn main() {
    let user = User::new("ada_lovelace", "ada@rust.org");
    user.display();
    
    execute_command(Command::Move { x: 10, y: 25 });
    execute_command(Command::Write(String::from("Rust pattern matching!")));
}
```

### Chapter 4: Idiomatic Error Handling (Result, Option, and `?`)

```rust
// 04_error_handling.rs
use std::fs::File;
use std::io::{self, Read};

fn read_file_content(path: &str) -> Result<String, io::Error> {
    let mut file = File::open(path)?; // '?' propagates error if failed
    let mut content = String::new();
    file.read_to_string(&mut content)?;
    Ok(content)
}

fn find_square_root(num: f64) -> Option<f64> {
    if num < 0.0 {
        None
    } else {
        Some(num.sqrt())
    }
}

fn main() {
    match find_square_root(16.0) {
        Some(val) => println!("Square root: {}", val),
        None => println!("Cannot compute square root of negative number!"),
    }
    
    match read_file_content("nonexistent.txt") {
        Ok(text) => println!("File content: {}", text),
        Err(e) => println!("Handled file read failure cleanly: {}", e),
    }
}
```

### Chapter 5: Traits and Generic Programming

```rust
// 05_traits.rs
pub trait Summary {
    fn summarize(&self) -> String;
}

pub struct NewsArticle {
    pub headline: String,
    pub author: String,
}

impl Summary for NewsArticle {
    fn summarize(&self) -> String {
        format!("'{}' by {}", self.headline, self.author)
    }
}

pub fn notify<T: Summary>(item: &T) {
    println!("Breaking News: {}", item.summarize());
}

fn main() {
    let article = NewsArticle {
        headline: String::from("Rust 2024 Edition Released"),
        author: String::from("Ferris"),
    };
    
    notify(&article);
}
```

### Chapter 6: Thread-Safe Concurrency with Arc and Mutex

```rust
// 06_concurrency.rs
use std::sync::{Arc, Mutex};
use std::thread;

fn main() {
    // Atomic Reference Counter (Arc) wrapped Mutex for multi-thread access
    let counter = Arc::new(Mutex::new(0));
    let mut handles = vec![];

    for _ in 0..5 {
        let counter_clone = Arc::clone(&counter);
        let handle = thread::spawn(move || {
            let mut num = counter_clone.lock().unwrap();
            *num += 1;
        });
        handles.push(handle);
    }

    for handle in handles {
        handle.join().unwrap();
    }

    println!("Final Counter Result: {}", *counter.lock().unwrap());
}
```

---

## Your First Project: A Command-Line To-Do Manager CLI

```rust
// todo_cli.rs
use std::io::{self, Write};

#[derive(Debug)]
struct Task {
    id: usize,
    description: String,
    completed: bool,
}

struct TodoList {
    tasks: Vec<Task>,
}

impl TodoList {
    fn new() -> Self {
        Self { tasks: Vec::new() }
    }

    fn add_task(&mut self, desc: String) {
        let id = self.tasks.len() + 1;
        self.tasks.push(Task {
            id,
            description: desc,
            completed: false,
        });
        println!("Task #{} added.", id);
    }

    fn list_tasks(&self) {
        println!("\n--- Your Tasks ---");
        if self.tasks.is_empty() {
            println!("No tasks yet.");
            return;
        }
        for task in &self.tasks {
            let status = if task.completed { "[x]" } else { "[ ]" };
            println!("{} #{}: {}", status, task.id, task.description);
        }
    }
}

fn main() {
    let mut todo = TodoList::new();
    todo.add_task(String::from("Learn Rust Edition 2024"));
    todo.add_task(String::from("Build Apple Silicon macOS CLI app"));
    
    todo.list_tasks();
}
```

---

## Quick Reference & Guidelines for macOS ARM64

### Essential Cargo Commands

```bash
# Check code without compiling binary
cargo check

# Build debug binary
cargo build

# Build optimized release binary for macOS ARM64
cargo build --release

# Run unit and integration tests
cargo test
```

### Common Rust Mistakes to Avoid

1. **Attempting to use moved values**: Use references (`&`) when ownership does not need to transfer.
2. **Multiple mutable borrows**: Rust allows only *one* `&mut T` reference to data in a given scope.
3. **Unnecessary `.clone()` calls**: Avoid cloning large heap strings/vectors when borrowing suffices.
4. **Using `.unwrap()` indiscriminately**: Use pattern matching or `?` to handle errors gracefully in production code.

---

## Chapter 7: Everyday Copy-and-Paste Rust One-Liners & Production Snippets

```rust
// 1. Efficient Line-by-Line File Reader using BufReader
use std::fs::File;
use std::io::{BufRead, BufReader, Result};

fn read_lines_from_file(filename: &str) -> Result<Vec<String>> {
    let file = File::open(filename)?;
    let reader = BufReader::new(file);
    reader.lines().collect()
}

// 2. Multithreaded Execution with Thread Spawning & JoinHandles
use std::thread;

fn run_parallel_workers() {
    let handles: Vec<_> = (0..4)
        .map(|i| {
            thread::spawn(move || {
                format!("Worker {} completed", i)
            })
        })
        .collect();

    for handle in handles {
        if let Ok(result) = handle.join() {
            println!("{}", result);
        }
    }
}

// 3. Custom Error Enum with Display Implementation
use std::fmt;

#[derive(Debug)]
pub enum AppError {
    NotFound(String),
    PermissionDenied,
    IoError(std::io::Error),
}

impl fmt::Display for AppError {
    fn fmt(&self, f: &mut fmt::Formatter<'_>) -> fmt::Result {
        match self {
            AppError::NotFound(item) => write!(f, "Item not found: {}", item),
            AppError::PermissionDenied => write!(f, "Access denied"),
            AppError::IoError(e) => write!(f, "IO Error: {}", e),
        }
    }
}

impl std::error::Error for AppError {}

// 4. Safe Shared State Across Threads with Arc<Mutex<T>>
use std::sync::{Arc, Mutex};

fn shared_counter() {
    let counter = Arc::new(Mutex::new(0));
    let mut handles = vec![];

    for _ in 0..10 {
        let counter_clone = Arc::clone(&counter);
        let handle = thread::spawn(move || {
            let mut num = counter_clone.lock().unwrap();
            *num += 1;
        });
        handles.push(handle);
    }

    for handle in handles {
        handle.join().unwrap();
    }

    println!("Final Shared Count: {}", *counter.lock().unwrap());
}

// 5. Measure Function Execution Duration
use std::time::Instant;

fn measure_execution<F, R>(func: F) -> (R, std::time::Duration)
where
    F: FnOnce() -> R,
{
    let start = Instant::now();
    let result = func();
    (result, start.elapsed())
}
```