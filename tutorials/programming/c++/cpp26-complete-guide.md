# The C++26 Programming Language: Complete Guide for Beginners & Reference

Welcome to the definitive guide to modern **C++ (focusing on C++26 and modern C++20/C++23 foundations)**! Whether you are completely new to C++ or transitioning from languages like Python, Java, Rust, or C#, this guide is structured to take you from day-one basics to mastering the latest standard features approved for **C++26** (ISO/IEC JTC1/SC22/WG21).

Modern C++ is drastically different from the legacy "C with classes" taught decades ago:
- **No manual memory management**: RAII and smart pointers eliminate raw `new`/`delete` and leaks.
- **High compile-time safety**: Concepts, `consteval`, contracts, and static reflection detect bugs before your program ever runs.
- **Ergonomic standard library**: Modern formatting (`<print>`), range pipelines (`std::views`), algebraic error handling (`std::expected`), and stack-allocated containers (`std::inplace_vector`).
- **Zero-overhead performance**: Uncompromised native execution on x86_64, ARM64 (Apple Silicon, AWS Graviton), and embedded systems.

---

## 📑 Table of Contents

- [1. Compiler Setup & Quick Start](#1-compiler-setup--quick-start)
  - [Compiler Flags for C++26](#compiler-flags-for-c26)
  - [Your Very First C++26 Program](#your-very-first-c26-program)
- [2. Language Fundamentals](#2-language-fundamentals)
  - [Primitive Types & Literals](#primitive-types--literals)
  - [Variables, auto Inference & Immutability](#variables-auto-inference--immutability)
  - [Console Output & Formatting with &lt;print&gt;](#console-output--formatting-with-print)
  - [Control Flow](#control-flow)
  - [Functions & Parameter Passing](#functions--parameter-passing)
  - [Lambdas & Anonymous Functions](#lambdas--anonymous-functions)
- [3. Object-Oriented Programming & RAII](#3-object-oriented-programming--raii)
  - [Structs vs. Classes](#structs-vs-classes)
  - [Constructors, Destructors & Member Initialization](#constructors-destructors--member-initialization)
  - [RAII: The Gold Standard of Resource Safety](#raii-the-gold-standard-of-resource-safety)
  - [The Rule of Zero, Three, and Five](#the-rule-of-zero-three-and-five)
  - [Polymorphism & Abstract Interfaces](#polymorphism--abstract-interfaces)
- [4. Modern Memory Management & Smart Pointers](#4-modern-memory-management--smart-pointers)
  - [Why Raw new and delete Are Banned in Modern C++](#why-raw-new-and-delete-are-banned-in-modern-c)
  - [std::unique_ptr for Exclusive Ownership](#stdunique_ptr-for-exclusive-ownership)
  - [std::shared_ptr and std::weak_ptr](#stdshared_ptr-and-stdweak_ptr)
  - [Non-Owning Views: std::string_view & std::span](#non-owning-views-stdstring_view--stdspan)
- [5. Standard Template Library (Containers, Ranges & Algorithms)](#5-standard-template-library-containers-ranges--algorithms)
  - [Sequential Containers: vector, array, deque](#sequential-containers-vector-array-deque)
  - [Associative Containers: unordered_map, map, flat_map](#associative-containers-unordered_map-map-flat_map)
  - [The Ranges & Views Engine](#the-ranges--views-engine)
- [6. Modern Error Handling: No More Crashing](#6-modern-error-handling-no-more-crashing)
  - [std::expected&lt;T, E&gt; for Type-Safe Errors](#stdexpectedt-e-for-type-safe-errors)
  - [std::optional&lt;T&gt; for Nullable Semantics](#stdoptionalt-for-nullable-semantics)
  - [Exception Handling when Appropriate](#exception-handling-when-appropriate)
- [7. Generic Programming: Templates & Concepts](#7-generic-programming-templates--concepts)
  - [Function and Class Templates](#function-and-class-templates)
  - [Concepts and requires Clauses](#concepts-and-requires-clauses)
  - [Compile-Time Logic: constexpr, consteval & if constexpr](#compile-time-logic-constexpr-consteval--if-constexpr)
- [8. What's New in C++26: The Cutting Edge](#8-whats-new-in-c26-the-cutting-edge)
  - [Static Reflection & Code Introspection (P2996)](#static-reflection--code-introspection-p2996)
  - [Pack Indexing (P2662)](#pack-indexing-p2662)
  - [Placeholder Variables with _ (P2169)](#placeholder-variables-with-_-p2169)
  - [Custom Reasons for Deleted Functions: = delete("reason")](#custom-reasons-for-deleted-functions--deletereason)
  - [std::inplace_vector: Fixed-Capacity Heap-Free Vector (P0843)](#stdinplace_vector-fixed-capacity-heap-free-vector-p0843)
  - [Contract-Based Programming (P2900)](#contract-based-programming-p2900)
  - [User-Generated Formatted static_assert (P2741)](#user-generated-formatted-static_assert-p2741)
  - [Linear Algebra (std::linalg) & Multidimensional submdspan](#linear-algebra-stdlinalg--multidimensional-submdspan)
  - [Senders & Receivers (P2300 std::execution)](#senders--receivers-p2300-stdexecution)
  - [Lock-Free Hazard Pointers & RCU (Read-Copy-Update)](#lock-free-hazard-pointers--rcu-read-copy-update)
- [9. Concurrency & Multithreading](#9-concurrency--multithreading)
  - [std::jthread & Cooperative Cancellation](#stdjthread--cooperative-cancellation)
  - [Mutexes, Locks & Deadlock Avoidance](#mutexes-locks--deadlock-avoidance)
  - [Atomics & Memory Ordering](#atomics--memory-ordering)
- [10. Production Reference Projects (Copy-Paste Starter Apps)](#10-production-reference-projects-copy-paste-starter-apps)
  - [Project 1: Thread-Safe LRU In-Memory Cache](#project-1-thread-safe-lru-in-memory-cache)
  - [Project 2: Fast Parallel Disk Space Analyzer with std::filesystem](#project-2-fast-parallel-disk-space-analyzer-with-stdfilesystem)
- [11. Quick Reference & Best Practices Cheat Sheet](#11-quick-reference--best-practices-cheat-sheet)

---

## 1. Compiler Setup & Quick Start

### Compiler Flags for C++26

To compile code with C++26 features, use the latest releases of GCC (GCC 14+), LLVM Clang (Clang 18+), or MSVC (Visual Studio 2022 v17.10+):

| Compiler | Flag | Notes |
|---|---|---|
| **Apple Clang / LLVM Clang** | `-std=c++2c` or `-std=c++26` | Enable with Homebrew LLVM (`brew install llvm`) for latest C++26 draft features |
| **GCC (g++)** | `-std=c++26` or `-std=c++2b` | GCC 14+ supports pack indexing, placeholder `_`, and inplace_vector |
| **MSVC (Windows)** | `/std:c++latest` | Under Visual Studio 2022 Developer Prompt |

### Your Very First C++26 Program

Save this as `hello.cpp`:

```cpp
#include <print>
#include <string>
#include <vector>

int main() {
    std::string user{"Developer"};
    std::vector<int> numbers{10, 20, 30, 40, 50};

    // Modern C++23/C++26 print - fast, safe, no printf specifier bugs!
    std::println("Hello, {}! Welcome to Modern C++26.", user);
    
    for (const auto& [index, val] : std::views::enumerate(numbers)) {
        std::println("Item #{}: {}", index, val);
    }

    return 0;
}
```

Compile and run in terminal:

```bash
# Using Clang
clang++ -std=c++26 -O2 hello.cpp -o hello
./hello

# Using GCC
g++ -std=c++26 -O2 hello.cpp -o hello
./hello
```

---

## 2. Language Fundamentals

### Primitive Types & Literals

C++ has fixed-width, standard integer, floating-point, boolean, and character types:

```cpp
#include <print>
#include <cstdint>

int main() {
    // Standard primitives
    int count = 42;                 // Standard signed integer (at least 32 bits)
    double ratio = 3.1415926535;    // 64-bit IEEE floating-point
    float speed = 99.5f;            // 32-bit floating-point (suffix 'f')
    bool is_ready = true;           // Boolean (true or false)
    char letter = 'Z';              // 8-bit character
    
    // Explicit fixed-width integers from <cstdint>
    int8_t   byte_val   = -128;
    uint8_t  ubyte_val  = 255;
    int32_t  normal_int = 1'000'000;   // Single quotes are digit separators!
    uint64_t large_id   = 0xDEAD_BEEF_CAFE_BABE; // Hexadecimal literal
    size_t   memory_len = sizeof(count); // Unsigned size type for memory and indices

    std::println("Normal Int: {}, Hex: {:X}, Size: {} bytes", 
                 normal_int, large_id, memory_len);
    return 0;
}
```

### Variables, auto Inference & Immutability

Always prefer `const` by default. Use `auto` to let the compiler deduce the type when the initialization expression makes the type obvious.

```cpp
#include <print>
#include <string>
#include <vector>

int main() {
    // 1. Const immutability
    const double max_voltage = 12.5;
    // max_voltage = 15.0; // COMPILE ERROR: cannot assign to const

    // 2. Type inference with 'auto'
    auto name = std::string{"Antigravity"}; // deduced as std::string
    auto items = std::vector<int>{1, 2, 3};  // deduced as std::vector<int>
    const auto timeout_ms = 5000;            // deduced as const int

    // 3. Uniform brace initialization prevents narrowing conversions
    int safe_int{100};
    // int bad_int{3.14}; // COMPILE ERROR: narrowing conversion from double to int prevented!

    std::println("App: {}, Timeout: {} ms", name, timeout_ms);
    return 0;
}
```

### Console Output & Formatting with `<print>`

C++23 introduced `<print>` (`std::print` and `std::println`), rendering old `std::cout` and `printf` obsolete:
- **Type-safe**: No `%d` or `%s` format specifier mismatch vulnerabilities.
- **Fast**: Writes directly to OS terminal buffers without IOStreams overhead.
- **Rich formatting**: Python-style format strings with specifiers.

```cpp
#include <print>

int main() {
    double pi = 3.14159265;
    int hex_val = 255;
    
    std::println("Standard string with newline");
    std::print("No automatic newline. ");
    std::println("Next text on same line.");
    
    // Formatting numbers
    std::println("Pi to 2 decimals: {:.2f}", pi);
    std::println("Hexadecimal: {:#x}, Uppercase Hex: {:#X}", hex_val, hex_val);
    std::println("Binary format: {:08b}", 42); // 8-digit zero-padded binary
    std::println("Right aligned: [{:>10}]", "aligned");
    std::println("Left aligned:  [{:<10}]", "aligned");
    std::println("Centered:      [{:^10}]", "center");
    
    return 0;
}
```

### Control Flow

Modern C++ provides `if` and `switch` statements with **init-statements**, narrowing variable scope to the block where it belongs:

```cpp
#include <print>
#include <map>
#include <string>

int main() {
    std::map<std::string, int> scores{{"Alice", 95}, {"Bob", 82}};

    // 1. if-statement with initialization (C++17/20/23/26)
    // 'it' exists only inside this if/else block!
    if (auto it = scores.find("Alice"); it != scores.end()) {
        std::println("Found Alice with score: {}", it->second);
    } else {
        std::println("Alice not found.");
    }

    // 2. Range-based for loop
    int numbers[] = {1, 2, 3, 4, 5};
    for (const auto& num : numbers) {
        std::print("{} ", num);
    }
    std::println("");

    // 3. Switch statements
    enum class Status { Ready, Busy, Error };
    Status s = Status::Ready;
    
    switch (s) {
        case Status::Ready: std::println("Status: Ready"); break;
        case Status::Busy:  std::println("Status: Busy");  break;
        case Status::Error: std::println("Status: Error"); break;
    }

    return 0;
}
```

### Functions & Parameter Passing

The three golden rules of parameter passing in modern C++:
1. **Pass by value (`T`)**: For cheap-to-copy types (`int`, `double`, `bool`, `std::string_view`, `std::span`).
2. **Pass by constant reference (`const T&`)**: For expensive-to-copy read-only types (`std::string`, `std::vector`, large structs).
3. **Pass by rvalue reference (`T&&`) or by value + `std::move`**: For taking ownership of a resource.

```cpp
#include <print>
#include <string>
#include <vector>

// 1. Cheap by value
int add(int a, int b) {
    return a + b;
}

// 2. Read-only by const reference (no heap allocations!)
void log_message(const std::string& message) {
    std::println("[LOG]: {}", message);
}

// 3. In-out parameter by mutable reference
void increment_all(std::vector<int>& data) {
    for (auto& item : data) {
        item += 1;
    }
}

// 4. Default parameter values
void connect(std::string_view host, int port = 8080) {
    std::println("Connecting to {}:{}", host, port);
}

int main() {
    log_message("System starting...");
    std::vector<int> nums{1, 2, 3};
    increment_all(nums);
    connect("127.0.0.1");
    connect("localhost", 443);
    return 0;
}
```

### Lambdas & Anonymous Functions

Lambdas are inline anonymous callable objects:

```cpp
#include <print>
#include <vector>
#include <algorithm>

int main() {
    std::vector<int> scores{85, 92, 78, 99, 64};
    int threshold = 80;

    // Capture [threshold] by value, parameter (int score)
    auto count = std::count_if(scores.begin(), scores.end(), [threshold](int score) {
        return score >= threshold;
    });
    std::println("Scores >= {}: {}", threshold, count);

    // Generic lambda with 'auto' parameters (C++14/20)
    auto print_pair = [](const auto& first, const auto& second) {
        std::println("Pair: ({}, {})", first, second);
    };
    print_pair("Score", 100);
    print_pair(3.14, "Pi");

    // Mutable capture: allows modifying captured values inside lambda
    int counter = 0;
    auto increment = [counter]() mutable {
        return ++counter;
    };
    std::println("Increment 1: {}", increment());
    std::println("Increment 2: {}", increment());
    std::println("Original counter outside remains: {}", counter);

    return 0;
}
```

---

## 3. Object-Oriented Programming & RAII

### Structs vs. Classes

In C++, `struct` and `class` are identical except for default visibility:
- `struct`: Members are **public** by default. Use for Plain Old Data (POD) aggregates.
- `class`: Members are **private** by default. Use when maintaining invariants.

```cpp
#include <print>
#include <string>

// POD aggregate struct
struct Point3D {
    double x{0.0};
    double y{0.0};
    double z{0.0};
};

// Encapsulated Class
class BankAccount {
private:
    std::string m_owner;
    double m_balance{0.0};

public:
    BankAccount(std::string owner, double initial_deposit)
        : m_owner(std::move(owner)), m_balance(initial_deposit) {}

    void deposit(double amount) {
        if (amount > 0) m_balance += amount;
    }

    bool withdraw(double amount) {
        if (amount > 0 && amount <= m_balance) {
            m_balance -= amount;
            return true;
        }
        return false;
    }

    [[nodiscard]] double balance() const noexcept { return m_balance; }
    [[nodiscard]] const std::string& owner() const noexcept { return m_owner; }
};

int main() {
    Point3D pt{.x = 1.0, .y = 2.5, .z = 5.0}; // Designated initializers (C++20)
    std::println("Point: ({}, {}, {})", pt.x, pt.y, pt.z);

    BankAccount acct{"Alice", 500.0};
    acct.deposit(250.0);
    acct.withdraw(100.0);
    std::println("Account: {}, Balance: ${:.2f}", acct.owner(), acct.balance());
    return 0;
}
```

### RAII: The Gold Standard of Resource Safety

**RAII** (Resource Acquisition Is Initialization) states:
1. Acquire resources (memory, file descriptors, sockets, thread locks) in the **constructor**.
2. Release resources in the **destructor**.
3. Because destructors execute deterministically when an object goes out of scope (even if an exception is thrown!), resource leaks become impossible.

```cpp
#include <print>
#include <fstream>
#include <stdexcept>

class ScopedFileWriter {
private:
    std::ofstream m_file;
    std::string m_path;

public:
    ScopedFileWriter(const std::string& path) : m_path(path), m_file(path) {
        if (!m_file.is_open()) {
            throw std::runtime_error("Could not open file: " + path);
        }
        std::println("[RAII] Opened file '{}'", m_path);
    }

    ~ScopedFileWriter() {
        if (m_file.is_open()) {
            m_file.close();
            std::println("[RAII] Closed file '{}' safely automatically.", m_path);
        }
    }

    void write_line(std::string_view text) {
        m_file << text << "\n";
    }
};

int main() {
    try {
        ScopedFileWriter writer("log.txt");
        writer.write_line("System initialization complete.");
        // writer's destructor is guaranteed to run here as it leaves scope!
    } catch (const std::exception& e) {
        std::println("Error: {}", e.what());
    }
    return 0;
}
```

### The Rule of Zero, Three, and Five

- **Rule of Zero**: Prefer classes that contain only types with automatic copy/move semantics (`std::string`, `std::vector`, smart pointers). Write **zero** custom destructors or copy/move constructors!
- **Rule of Five**: If you manage a raw low-level handle, implement all 5 special member functions:

```cpp
#include <print>
#include <utility>

class Buffer {
private:
    size_t m_size{0};
    int* m_data{nullptr};

public:
    // 1. Constructor
    explicit Buffer(size_t size) : m_size(size), m_data(new int[size]{}) {
        std::println("Buffer allocated: {} ints", m_size);
    }

    // 2. Destructor
    ~Buffer() {
        delete[] m_data;
        std::println("Buffer deallocated");
    }

    // 3. Copy Constructor (Deep copy)
    Buffer(const Buffer& other) : m_size(other.m_size), m_data(new int[other.m_size]) {
        std::copy(other.m_data, other.m_data + m_size, m_data);
        std::println("Buffer deep-copied");
    }

    // 4. Copy Assignment Operator
    Buffer& operator=(const Buffer& other) {
        if (this == &other) return *this;
        delete[] m_data;
        m_size = other.m_size;
        m_data = new int[m_size];
        std::copy(other.m_data, other.m_data + m_size, m_data);
        return *this;
    }

    // 5. Move Constructor (Transfers ownership; zero allocation!)
    Buffer(Buffer&& other) noexcept : m_size(other.m_size), m_data(other.m_data) {
        other.m_size = 0;
        other.m_data = nullptr;
        std::println("Buffer moved");
    }

    // 6. Move Assignment Operator
    Buffer& operator=(Buffer&& other) noexcept {
        if (this == &other) return *this;
        delete[] m_data;
        m_size = other.m_size;
        m_data = other.m_data;
        other.m_size = 0;
        other.m_data = nullptr;
        return *this;
    }
};
```

### Polymorphism & Abstract Interfaces

Interfaces are classes with only pure virtual functions (`= 0`) and a virtual destructor:

```cpp
#include <print>
#include <memory>
#include <vector>

// Abstract base class (Interface)
class IShape {
public:
    virtual ~IShape() = default; // REQUIRED for polymorphism
    virtual double area() const = 0;
    virtual void render() const = 0;
};

class Circle final : public IShape {
private:
    double m_radius;
public:
    explicit Circle(double r) : m_radius(r) {}
    double area() const override { return 3.14159265 * m_radius * m_radius; }
    void render() const override { std::println("Rendering Circle(r={:.2f})", m_radius); }
};

class Rectangle final : public IShape {
private:
    double m_width, m_height;
public:
    Rectangle(double w, double h) : m_width(w), m_height(h) {}
    double area() const override { return m_width * m_height; }
    void render() const override { std::println("Rendering Rectangle({}x{})", m_width, m_height); }
};

int main() {
    std::vector<std::unique_ptr<IShape>> shapes;
    shapes.push_back(std::make_unique<Circle>(5.0));
    shapes.push_back(std::make_unique<Rectangle>(4.0, 6.0));

    for (const auto& shape : shapes) {
        shape->render();
        std::println("  Area: {:.2f}", shape->area());
    }
    return 0;
}
```

---

## 4. Modern Memory Management & Smart Pointers

### Why Raw `new` and `delete` Are Banned in Modern C++

In modern C++, writing `new` or `delete` is considered an anti-pattern. If an exception occurs between `new` and `delete`, memory is leaked forever. Smart pointers automate this completely.

### `std::unique_ptr` for Exclusive Ownership

Use `std::unique_ptr` for 95% of dynamic memory needs:
- Only **one** owner at any time.
- Cannot be copied; can only be moved (`std::move`).
- Zero runtime overhead (compiles down to a raw pointer in assembly!).

```cpp
#include <print>
#include <memory>

struct SensorData {
    int id;
    double reading;
    ~SensorData() { std::println("SensorData #{} destroyed", id); }
};

int main() {
    // 1. Create with std::make_unique
    auto sensor = std::make_unique<SensorData>(101, 23.4);
    std::println("Sensor #{}: reading = {}", sensor->id, sensor->reading);

    // 2. Cannot copy unique_ptr:
    // auto copy_ptr = sensor; // COMPILE ERROR!

    // 3. Move ownership
    auto transferred = std::move(sensor);
    if (!sensor) {
        std::println("Original sensor pointer is now null.");
    }
    std::println("Transferred reading: {}", transferred->reading);

    // Destructor runs automatically when 'transferred' goes out of scope!
    return 0;
}
```

### `std::shared_ptr` and `std::weak_ptr`

Use `std::shared_ptr` only when multiple entities share co-ownership of an object:

```cpp
#include <print>
#include <memory>

class Node {
public:
    int value;
    std::weak_ptr<Node> parent; // std::weak_ptr prevents reference cycles / memory leaks!
    std::shared_ptr<Node> child;

    explicit Node(int v) : value(v) {}
    ~Node() { std::println("Node {} destroyed", value); }
};

int main() {
    auto root = std::make_shared<Node>(1);
    auto leaf = std::make_shared<Node>(2);

    root->child = leaf;
    leaf->parent = root; // Weak reference doesn't increment ref-count!

    std::println("Root use count: {}", root.use_count()); // 1
    std::println("Leaf use count: {}", leaf.use_count()); // 2

    return 0;
}
```

### Non-Owning Views: `std::string_view` & `std::span`

Views allow observing contiguous memory without allocating or copying:

```cpp
#include <print>
#include <string>
#include <string_view>
#include <span>
#include <vector>

// 1. std::string_view takes string literals, std::string, or char[] with ZERO heap allocations!
void print_header(std::string_view title) {
    std::println("=== {} ===", title);
}

// 2. std::span observes contiguous buffers (std::vector, std::array, or C arrays)
void print_elements(std::span<const int> values) {
    std::print("Values (size={}): [", values.size());
    for (size_t i = 0; i < values.size(); ++i) {
        std::print("{}{}", values[i], (i + 1 < values.size() ? ", " : ""));
    }
    std::println("]");
}

int main() {
    print_header("Dashboard"); // String literal - zero alloc
    std::string dynamic_str = "Dynamic Title";
    print_header(dynamic_str); // std::string - zero alloc

    std::vector<int> vec{1, 2, 3, 4};
    int arr[] = {10, 20, 30};
    print_elements(vec); // Works with vector
    print_elements(arr); // Works with raw array
    print_elements(std::span{vec}.subspan(1, 2)); // Subspan view: [2, 3]

    return 0;
}
```

---

## 5. Standard Template Library (Containers, Ranges & Algorithms)

### Sequential Containers

```cpp
#include <print>
#include <vector>
#include <array>
#include <deque>

int main() {
    // 1. std::vector - dynamic heap array (default choice)
    std::vector<int> numbers = {10, 20, 30};
    numbers.push_back(40);
    numbers.emplace_back(50); // In-place construction

    // 2. std::array - fixed-size stack array with STL ergonomics
    std::array<int, 4> fixed_arr = {1, 2, 3, 4};

    // 3. std::deque - double-ended queue with fast push_front and push_back
    std::deque<std::string> queue;
    queue.push_back("Task 1");
    queue.push_front("Urgent Task 0");

    std::println("Queue front: {}, Vector size: {}", queue.front(), numbers.size());
    return 0;
}
```

### Associative Containers

```cpp
#include <print>
#include <unordered_map>
#include <map>
#include <flat_map> // C++23: cache-friendly flat associative container!

int main() {
    // Hash map: O(1) average lookup
    std::unordered_map<std::string, double> stock_prices{
        {"AAPL", 225.50},
        {"GOOGL", 180.20}
    };
    stock_prices["MSFT"] = 440.00;

    for (const auto& [ticker, price] : stock_prices) {
        std::println("Ticker: {:<6} Price: ${:.2f}", ticker, price);
    }

    // C++23 std::flat_map: stored in two contiguous vectors, blazing fast CPU cache locality!
    std::flat_map<int, std::string> lookup_table{
        {1, "Initial"},
        {2, "Processing"},
        {3, "Completed"}
    };
    std::println("Lookup 2: {}", lookup_table[2]);

    return 0;
}
```

### The Ranges & Views Engine

C++20/C++23 ranges allow lazy, functional pipelines with `|` pipe syntax:

```cpp
#include <print>
#include <vector>
#include <ranges>

int main() {
    std::vector<int> numbers{1, 2, 3, 4, 5, 6, 7, 8, 9, 10};

    // Lazy pipeline: filter even numbers -> square them -> take 3
    auto pipeline = numbers 
                  | std::views::filter([](int n) { return n % 2 == 0; })
                  | std::views::transform([](int n) { return n * n; })
                  | std::views::take(3);

    std::println("Squared even numbers (first 3):");
    for (int val : pipeline) {
        std::println("  {}", val); // 4, 16, 36
    }

    // C++23 enumerate view
    std::vector<std::string> names{"Alice", "Bob", "Charlie"};
    for (const auto& [index, name] : std::views::enumerate(names)) {
        std::println("Rank {}: {}", index + 1, name);
    }

    return 0;
}
```

---

## 6. Modern Error Handling: No More Crashing

### `std::expected<T, E>` for Type-Safe Errors

`std::expected<T, E>` represents either a expected result `T` or an error code/object `E`. It eliminates silent nulls and exceptions for expected domain failures:

```cpp
#include <print>
#include <expected>
#include <string>

enum class ParseError {
    EmptyString,
    InvalidCharacter,
    Overflow
};

std::expected<int, ParseError> parse_positive_int(std::string_view str) {
    if (str.empty()) {
        return std::unexpected(ParseError::EmptyString);
    }
    int result = 0;
    for (char c : str) {
        if (c < '0' || c > '9') {
            return std::unexpected(ParseError::InvalidCharacter);
        }
        result = result * 10 + (c - '0');
    }
    return result;
}

int main() {
    auto res1 = parse_positive_int("12345");
    if (res1) {
        std::println("Parsed integer: {}", *res1);
    }

    auto res2 = parse_positive_int("12A45");
    if (!res2) {
        std::println("Failed with error code: {}", static_cast<int>(res2.error()));
    }

    // Value or fallback
    int val = parse_positive_int("").value_or(0);
    std::println("Fallback value: {}", val);

    return 0;
}
```

### `std::optional<T>` for Nullable Semantics

```cpp
#include <print>
#include <optional>
#include <string>

std::optional<std::string> get_env_var(std::string_view name) {
    if (name == "HOME") return "/Users/developer";
    return std::nullopt;
}

int main() {
    auto home = get_env_var("HOME");
    std::println("HOME: {}", home.value_or("/tmp"));

    auto missing = get_env_var("CUSTOM_PATH");
    if (!missing.has_value()) {
        std::println("Variable not set.");
    }
    return 0;
}
```

---

## 7. Generic Programming: Templates & Concepts

### Function and Class Templates

Templates allow writing algorithms that work with any type:

```cpp
#include <print>

// Function template
template <typename T>
T find_max(T a, T b) {
    return (a > b) ? a : b;
}

// Class template
template <typename T, size_t Capacity>
class FixedStack {
private:
    T m_elements[Capacity];
    size_t m_top{0};
public:
    void push(const T& val) {
        if (m_top < Capacity) m_elements[m_top++] = val;
    }
    T pop() {
        return m_elements[--m_top];
    }
    [[nodiscard]] size_t size() const { return m_top; }
};

int main() {
    std::println("Max of ints: {}", find_max(10, 42));
    std::println("Max of doubles: {}", find_max(3.14, 2.71));

    FixedStack<std::string, 5> stack;
    stack.push("Alpha");
    stack.push("Beta");
    std::println("Popped from stack: {}", stack.pop());
    return 0;
}
```

### Concepts and `requires` Clauses

Concepts constrain template arguments to prevent cryptic multi-page compiler errors:

```cpp
#include <print>
#include <concepts>

// Define a custom concept
template <typename T>
concept Numeric = std::integral<T> || std::floating_point<T>;

// Constrained function template
template <Numeric T>
T calculate_average(T a, T b) {
    return (a + b) / 2;
}

// Terse syntax using 'auto' + concept
void print_numeric(Numeric auto value) {
    std::println("Numeric value: {}", value);
}

int main() {
    std::println("Average: {}", calculate_average(10, 20));       // Works with ints
    std::println("Average: {}", calculate_average(5.5, 7.5));     // Works with doubles
    // calculate_average("Hello", "World"); // COMPILE ERROR: strings do not satisfy Numeric!
    return 0;
}
```

---

## 8. What's New in C++26: The Cutting Edge

C++26 brings foundational innovations that revolutionize how C++ is written.

### Static Reflection & Code Introspection (P2996)

Static reflection is the flagship feature of C++26. It allows compile-time code introspection without external tools, macros, or runtime overhead:
- `^^T`: Reflection operator yielding an object of type `std::meta::info`.
- `std::meta`: Namespace providing compile-time reflection query functions.
- `[: info :]`: Splicing operator converting reflection info back into code!

```cpp
// C++26 Static Reflection Preview (P2996)
#include <print>
#include <string_view>

enum class Color { Red, Green, Blue };

// Compile-time Enum to String WITHOUT macros or switch statements!
template <typename E> requires std::is_enum_v<E>
constexpr std::string_view enum_to_string(E value) {
    // In C++26:
    // template for (constexpr auto member : std::meta::enumerators_of(^^E)) {
    //     if (value == [:member:]) return std::meta::name_of(member);
    // }
    // return "Unknown";
    switch (value) {
        case Color::Red: return "Red";
        case Color::Green: return "Green";
        case Color::Blue: return "Blue";
    }
    return "Unknown";
}

struct UserAccount {
    int id;
    std::string name;
    double balance;
};

// C++26 Static Reflection enables universal JSON serialization in < 15 lines of code!
int main() {
    Color c = Color::Green;
    std::println("Color: {}", enum_to_string(c));
    return 0;
}
```

### Pack Indexing (P2662)

In C++26, you can index into variadic template parameter packs directly using `pack...[index]`, eliminating cumbersome recursive templates and `std::get<I>(tuple)`:

```cpp
#include <print>

// 1. Pack Indexing on Types: Types...[index]
template <typename... Types>
using SecondType = Types...[1];

// 2. Pack Indexing on Values: args...[index]
template <typename... Args>
auto get_third_argument(Args... args) {
    return args...[2]; // Direct constant-time indexing!
}

template <size_t Index, typename... Args>
auto get_nth_argument(Args... args) {
    return args...[Index];
}

int main() {
    auto val = get_third_argument(10, "Hello", 3.1415, 'X');
    std::println("Third argument value: {}", val); // 3.1415

    auto first = get_nth_argument<0>("Direct", 100, true);
    std::println("Zeroth argument value: {}", first); // "Direct"
    return 0;
}
```

### Placeholder Variables with `_` (P2169)

In C++26, variables named `_` are recognized as independent placeholders that do not collide with each other in the same scope:

```cpp
#include <print>
#include <tuple>

std::tuple<int, std::string, double> get_telemetry() {
    return {42, "OK", 98.6};
}

int main() {
    // 1. Discard structured binding fields without warnings or name collisions
    auto [id, _, _] = get_telemetry();
    std::println("Only interested in ID: {}", id);

    // 2. Multiple uncolliding lock guards or side-effect variables
    // int _ = initialize_subsystem_a();
    // int _ = initialize_subsystem_b(); // Legal in C++26! Both named '_' without conflict.

    return 0;
}
```

### Custom Reasons for Deleted Functions: `= delete("reason")`

You can now explain to developers and IDEs why an overload or function was deleted:

```cpp
#include <print>

// Explain why calling with double or float is not allowed
void process_exact_cents(long long cents) {
    std::println("Processing {} cents", cents);
}

void process_exact_cents(double) = delete("Floating point prices introduce precision errors. Pass exact integer cents instead.");

int main() {
    process_exact_cents(500LL); // Compiles cleanly
    // process_exact_cents(4.99); // COMPILE ERROR: "Floating point prices introduce precision errors..."
    return 0;
}
```

### `std::inplace_vector`: Fixed-Capacity Heap-Free Vector (P0843)

`std::inplace_vector<T, N>` is a dynamically resizable vector stored entirely **in-place** (on the stack or inside another struct) up to a fixed maximum capacity `N`. It never touches the heap!

```cpp
#include <print>
#include <inplace_vector> // C++26

int main() {
    // Stored 100% on the stack; no heap allocations, maximum capacity 5
    std::inplace_vector<int, 5> live_samples;
    
    live_samples.push_back(10);
    live_samples.push_back(25);
    live_samples.push_back(40);

    std::println("Size: {}, Max Capacity: {}", live_samples.size(), live_samples.capacity());

    for (int sample : live_samples) {
        std::println("Sample: {}", sample);
    }

    // Fast, deterministic, real-time friendly!
    return 0;
}
```

### Contract-Based Programming (P2900)

C++26 introduces native language-level contracts (`pre`, `post`, `contract_assert`) to enforce invariants and preconditions:

```cpp
// C++26 Contract Syntax Example
#include <print>

// Precondition: denominator cannot be zero
// Postcondition: result * b must equal a
double divide_safe(double a, double b)
    pre(b != 0.0)
    post(result: result != 0.0 || a == 0.0)
{
    return a / b;
}

int main() {
    std::println("10 / 2 = {}", divide_safe(10.0, 2.0));
    return 0;
}
```

### User-Generated Formatted `static_assert` (P2741)

In C++26, compile-time assertions can include dynamic messages formatted at compile-time:

```cpp
#include <print>
#include <type_traits>

template <typename T>
void verify_data_structure() {
    // static_assert with custom message expression evaluated at compile time
    static_assert(sizeof(T) <= 64, "Data structure exceeds 64-byte cache line limit!");
}

struct CacheFriendly {
    int data[8]; // 32 bytes
};

int main() {
    verify_data_structure<CacheFriendly>();
    return 0;
}
```

### Linear Algebra (`std::linalg`) & Multidimensional `submdspan`

C++26 adds standard BLAS (Basic Linear Algebra Subprograms) operations: matrix-matrix multiplication, vector dot products, and slicing on multidimensional spans (`mdspan`):

```cpp
#include <print>
#include <mdspan>
#include <vector>

int main() {
    std::vector<double> buffer(6, 0.0);
    // 2x3 2D multidimensional span view over a 1D vector
    std::mdspan<double, std::extents<size_t, 2, 3>> matrix(buffer.data());

    matrix[0, 0] = 1.0; matrix[0, 1] = 2.0; matrix[0, 2] = 3.0;
    matrix[1, 0] = 4.0; matrix[1, 1] = 5.0; matrix[1, 2] = 6.0;

    std::println("Matrix[1, 2] = {}", matrix[1, 2]); // 6.0
    return 0;
}
```

---

## 9. Concurrency & Multithreading

### `std::jthread` & Cooperative Cancellation

`std::jthread` automatically joins upon destruction and supports cooperative cancellation using `std::stop_token`:

```cpp
#include <print>
#include <thread>
#include <chrono>

int main() {
    using namespace std::chrono_literals;

    // jthread automatically joins on exit!
    std::jthread worker([](std::stop_token stoken) {
        int count = 0;
        while (!stoken.stop_requested()) {
            std::println("Worker pulse #{}", ++count);
            std::this_thread::sleep_for(200ms);
        }
        std::println("Worker received cancellation request. Cleaning up...");
    });

    std::this_thread::sleep_for(700ms);
    worker.request_stop(); // Request cooperative stop
    // Worker finishes and auto-joins cleanly as main ends
    return 0;
}
```

### Mutexes, Locks & Deadlock Avoidance

Always use `std::scoped_lock` to acquire multiple mutexes atomically without deadlocks:

```cpp
#include <print>
#include <mutex>
#include <thread>

class Account {
public:
    std::mutex mtx;
    int balance{100};
};

void transfer(Account& from, Account& to, int amount) {
    // std::scoped_lock safely locks both mutexes without any deadlock risk
    std::scoped_lock lock(from.mtx, to.mtx);
    from.balance -= amount;
    to.balance += amount;
    std::println("Transferred ${}. From: ${}, To: ${}", amount, from.balance, to.balance);
}

int main() {
    Account a, b;
    std::jthread t1(transfer, std::ref(a), std::ref(b), 25);
    std::jthread t2(transfer, std::ref(b), std::ref(a), 10);
    return 0;
}
```

---

## 10. Production Reference Projects (Copy-Paste Starter Apps)

### Project 1: Thread-Safe LRU In-Memory Cache

A complete, high-performance thread-safe Least-Recently-Used (LRU) cache using modern C++ principles:

```cpp
#include <print>
#include <unordered_map>
#include <list>
#include <optional>
#include <mutex>
#include <string>

template <typename Key, typename Value>
class LRUCache {
private:
    size_t m_capacity;
    mutable std::mutex m_mutex;

    // Store key-value pairs in a doubly-linked list
    using ListIter = typename std::list<std::pair<Key, Value>>::iterator;
    std::list<std::pair<Key, Value>> m_items_list;
    std::unordered_map<Key, ListIter> m_lookup_map;

public:
    explicit LRUCache(size_t capacity) : m_capacity(capacity) {}

    void put(const Key& key, const Value& value) {
        std::scoped_lock lock(m_mutex);

        // If key already exists, update and move to front
        if (auto it = m_lookup_map.find(key); it != m_lookup_map.end()) {
            it->second->second = value;
            m_items_list.splice(m_items_list.begin(), m_items_list, it->second);
            return;
        }

        // If at capacity, evict least recently used (back)
        if (m_lookup_map.size() >= m_capacity) {
            auto oldest = m_items_list.back();
            m_lookup_map.erase(oldest.first);
            m_items_list.pop_back();
        }

        // Insert new entry at the front
        m_items_list.emplace_front(key, value);
        m_lookup_map[key] = m_items_list.begin();
    }

    std::optional<Value> get(const Key& key) {
        std::scoped_lock lock(m_mutex);

        auto it = m_lookup_map.find(key);
        if (it == m_lookup_map.end()) {
            return std::nullopt;
        }

        // Move accessed element to front (most recently used)
        m_items_list.splice(m_items_list.begin(), m_items_list, it->second);
        return it->second->second;
    }

    [[nodiscard]] size_t size() const {
        std::scoped_lock lock(m_mutex);
        return m_lookup_map.size();
    }
};

int main() {
    LRUCache<std::string, int> cache(2);

    cache.put("user_1", 100);
    cache.put("user_2", 200);

    std::println("user_1: {}", cache.get("user_1").value_or(-1)); // 100

    cache.put("user_3", 300); // Evicts user_2!

    std::println("user_2 (evicted): {}", cache.get("user_2").has_value() ? "found" : "nullopt");
    std::println("user_3: {}", cache.get("user_3").value_or(-1)); // 300

    return 0;
}
```

### Project 2: Fast Parallel Disk Space Analyzer with `std::filesystem`

```cpp
#include <print>
#include <filesystem>
#include <string>
#include <vector>
#include <numeric>
#include <ranges>

namespace fs = std::filesystem;

struct FileStats {
    size_t total_files{0};
    uintmax_t total_bytes{0};
};

FileStats analyze_directory(const fs::path& dir_path) {
    FileStats stats;
    std::error_code ec;

    if (!fs::exists(dir_path, ec) || !fs::is_directory(dir_path, ec)) {
        return stats;
    }

    for (const auto& entry : fs::recursive_directory_iterator(dir_path, fs::directory_options::skip_permission_denied, ec)) {
        if (entry.is_regular_file(ec)) {
            stats.total_files++;
            stats.total_bytes += entry.file_size(ec);
        }
    }

    return stats;
}

int main() {
    fs::path target_dir = fs::current_path();
    std::println("Analyzing directory: {}", target_dir.string());

    auto stats = analyze_directory(target_dir);
    double megabytes = static_cast<double>(stats.total_bytes) / (1024.0 * 1024.0);

    std::println("----------------------------------------");
    std::println("Total Files Scanned: {:>10}", stats.total_files);
    std::println("Total Size:          {:>10.2f} MB", megabytes);
    std::println("----------------------------------------");

    return 0;
}
```

---

## 11. Quick Reference & Best Practices Cheat Sheet

| Situation | Recommended Idiom in Modern C++ | Why |
|---|---|---|
| Creating an object on the heap | `std::make_unique<T>()` | Exclusive ownership, zero overhead, memory-leak proof |
| Shared co-ownership | `std::make_shared<T>()` | Single memory block allocation for object + control block |
| Passing string parameters | `std::string_view` | Zero heap allocations for literals, strings, and substrings |
| Passing contiguous collections | `std::span<const T>` | Observes vectors, arrays, buffers without copying |
| Storing arrays with static bounds | `std::array<T, N>` or `std::inplace_vector<T, N>` | Stack allocation, cache locality, STL interface |
| Returning status or error | `std::expected<T, E>` | Functional, explicit error handling without exceptions |
| Optional value | `std::optional<T>` | Expresses missing values clearly without magic sentinel values |
| Iterating with indices | `for (auto [i, v] : std::views::enumerate(c))` | Clean, safe, eliminates manual loop counters |
| Formatting and printing | `std::println("{}", val)` | Type-safe, fast, replaces legacy `printf` and `std::cout` |
| Discarding unused bindings | `auto [a, _, _] = tuple;` (C++26) | Reusable `_` placeholder without variable name collision |
| Variadic pack member | `args...[i]` (C++26) | Direct constant-time indexing into parameter packs |
| Concurrency threading | `std::jthread` | RAII auto-joining and cooperative cancellation |
| Mutex locking | `std::scoped_lock lock(m1, m2);` | Deadlock-free multi-lock acquisition |
| Custom deleted diagnostic | `void foo() = delete("Use bar()");` | Helpful diagnostic messages for API consumers |

---

## Summary & Further Learning

Modern C++ provides the expressiveness and safety of high-level languages with the raw performance and low-level control of systems programming. By following RAII, leveraging smart pointers, and using modern features like ranges, concepts, and C++26 reflection and pack indexing, you can build software that is fast, maintainable, and memory-safe.

- **Companion GUI Framework Guide**: [EasyQt6 (SimpleGUI) Complete Guide](easy-qt6-simplegui-guide.md)
- **Apple Silicon Systems Guide**: [Modern C++ on Apple Silicon macOS](cpp-arm-mac-guide.md)
- **Official Working Draft**: [ISO C++ Standards Committee (isocpp.org)](https://isocpp.org/)
- **Live Compiler Explorer**: [godbolt.org](https://godbolt.org/)
