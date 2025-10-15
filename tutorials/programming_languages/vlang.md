**⚡️ Updated V Tutorial (based on the current master documentation – V ≈ 0.5‑dev)**  
*All new code snippets are taken from the latest V docs (see citations).*

---  

## 📚 Table of Contents  
1. [What’s new? – Quick version glance](#1-whats-new)  
2. [Getting V (the official way)](#2-getting-v)  
3. [Basic Syntax & Variables – modern idioms](#3-basic-syntax)  
4. [Data Types – enums, sum‑types & option/result](#4-data-types)  
5. [Operators & Expressions – safety first](#5-operators)  
6. [Control Flow – `match`, `for … in`, early returns](#6-control-flow)  
7. [Functions – option/result, multiple return values, generics](#7-functions)  
8. [Structs, Methods & Interfaces – embedded structs, traits](#8-structs)  
9. [Modules & Packages – `v.mod`, imports & conditional compilation](#9-modules)  
10. [Error handling – the unified `?` / `!` model](#10-error‑handling)  
11. [Memory Management – ARC, manual, cycles](#11-memory)  
12. [Concurrency – goroutines, channels, `select`](#12-concurrency)  
13. [Advanced Features – generics, compile‑time pseudo‑variables, attributes](#13‑advanced)  
14. [Best‑Practice checklist & common gotchas](#14‑best‑practice)  

---  

## 1️⃣ What’s new? – Quick version glance <a name="1-whats-new"></a>

| Feature | Old (V 0.4) | New (master) | Docs |
|---|---|---|---|
| **Option / Result types** | Only `?T` (optional) | Separate `?T` (optional) **and** `!T` (error‑result) | 【9†L528-L560】 |
| **Enums** | Simple `enum` | Enum methods, `from`/`str()`, custom values, sum‑type syntax | 【7†L16-L31】【7†L68-L86】 |
| **Conditional compilation** | `@if … ?` not documented | `$if FLAG ? { … }` and attribute `@[if FLAG ?]` | 【12†L34-L43】 |
| **Compile‑time pseudo‑variables** | Not exposed | `@FN`, `@LINE`, `@BUILD_DATE`, … | 【12†L48-L63】 |
| **Generics** | Limited examples | Full generic functions & structs (e.g. `Stack[T]`) | (see section 7) |
| **Attributes** | `@[deprecated]` only | `@[inline]`, `@[params]`, `@[if …]` etc. | (see section 13) |
| **Result propagation** | `or {}` only | `!` suffix on call – automatic propagation | 【9†L629-L637】 |
| **Multiple return values with error** | Not possible | `fn foo() !(int, string)` | 【9†L614-L622】 |

---  

## 2️⃣ Getting V (the official way) <a name="2-getting-v"></a>

```bash
# Clone the repo (shallow copy, ~10 MB)
git clone --depth 1 https://github.com/vlang/v
cd v

# Build (Linux/macOS/BSD/WSL)
make            # Windows → `make.bat`
./v version    # should print something like: v 0.5.0-dev (e0d8e7b)

# Install a handy wrapper (adds `v` to your $PATH)
sudo ./v symlink
```

*The README emphasises these steps – they are still the recommended way*【4†L75-L95】.  

**Tip:** keep V up‑to‑date with `git pull && make`.  

---  

## 3️⃣ Basic Syntax & Variables – modern idioms <a name="3-basic-syntax"></a>

```v
fn main() {
    // Immutable bindings (default) – preferred
    name := 'Bob'          // string inferred
    age  := 42             // int inferred

    // Mutable when you really need it
    mut counter := 0
    counter++              // OK

    // Explicit type (useful for API boundaries)
    var height f64 = 1.78

    // String interpolation with `$` (no `%` needed)
    println('Hello $name, you are $age years old')
}
```

*Notes*  

* `mut` is required for any reassignment.  
* `const` can be used for compile‑time constants (`const pi = 3.14159`).  

---  

## 4️⃣ Data Types – enums, sum‑types & option/result <a name="4-data-types"></a>

### 4.1 Enums (with methods, custom values & conversion)

```v
enum Color as u8 {
    red          // 0
    green = 5   // value 5
    blue        // value 6 (auto‑increment)
}

mut c := Color.red
c = .green                 // short syntax
println(c)                 // prints `green`
println(int(c))            // → 5

// Enum methods
fn (c Color) is_primary() bool {
    return c in [.red, .blue]
}
println(c.is_primary())   // true

// From string / int (returns ?Color)
col := Color.from('blue')!   // panic if not found
println(col.str())           // `"blue"`
```

*All of the above are shown in the docs*【7†L16-L31】【7†L68-L86】.  

### 4.2 Sum‑types (tagged unions)

```v
type Shape = Circle | Rectangle

struct Circle {
    radius f64
}
fn (c Circle) area() f64 { return math.pi * c.radius * c.radius }

struct Rectangle {
    w f64
    h f64
}
fn (r Rectangle) area() f64 { return r.w * r.h }

fn total_area(s Shape) f64 {
    match s {
        Circle    { return s.area() }
        Rectangle { return s.area() }
    }
}
```

Sum‑types let you model data that can be one of several structs – a powerful alternative to class hierarchies.

### 4.3 Option & Result Types (the core of error handling)

```v
struct User { id int; name string }

// Option – may be `none`
fn find_user(id int) ?User {
    if id == 0 { return none }
    return User{id, 'Bob'}
}

// Result – may be an error string
fn load_file(path string) !string {
    if !os.exists(path) { return error('file not found') }
    return os.read_file(path)!
}
```

The docs explain the two families and the `or {}` handling*【9†L528-L560】.  

---  

## 5️⃣ Operators & Expressions – safety first <a name="5-operators"></a>

| Operator | Example | Comment |
|---|---|---|
| Arithmetic | `a + b * c` – use parentheses for clarity | |
| Range | `for i in 0 .. 10 {}` – half‑open, `..=` inclusive | |
| Bitwise | `x & y`, `x << 1` | |
| Nil‑safe (option) | `user?.name or { 'guest' }` | auto‑unwrap or fallback |
| Result propagation | `data := load_file('a.txt')!` – propagates error upward |【9†L629-L637】 |

---  

## 6️⃣ Control Flow – `match`, `for … in`, early returns <a name="6-control-flow"></a>

```v
// Early return – reduces nesting
fn process(nums []int) string {
    if nums.len == 0 { return 'empty' }
    if nums.len < 3 { return 'too few' }
    // main logic here …
    return 'ok'
}

// Pattern matching with `match`
fn describe(color Color) string {
    match color {
        .red   { return 'stop' }
        .green { return 'go' }
        .blue  { return 'calm' }
    }
}

// Looping over index & value
fruits := ['apple', 'pear', 'plum']
for i, f in fruits {
    println('$i: $f')
}
```

`match` must be exhaustive (or have an `else`) – the compiler enforces this.

---  

## 7️⃣ Functions – option/result, multiple returns, generics <a name="7-functions"></a>

### 7.1 Option / Result propagation

```v
fn fetch_json(url string) !map[string]string {
    resp := http.get(url)!               // propagates error
    return json.decode(resp.body)!
}
```

The `!` suffix after a call automatically returns the error to the caller – see the docs*【9†L629-L637】.

### 7.2 Multiple return values *with* error

```v
fn split_pair(v int) !(int, int) {
    if v < 0 { return error('negative') }
    return v / 2, v - v/2
}

a, b := split_pair(7)!   // error propagates if needed
println('a=$a b=$b')
```

(Shown in the docs*【9†L614-L622】).

### 7.3 Generic function & container

```v
// Generic max
fn max[T comparable](a T, b T) T {
    if a > b { return a }
    return b
}

// Generic stack
struct Stack[T] {
mut:
    items []T
}
fn (mut s Stack[T]) push(v T) { s.items << v }
fn (mut s Stack[T]) pop() ?T {
    if s.items.len == 0 { return none }
    return s.items.pop()
}

mut is := Stack[int]{}
is.push(10); is.push(20)
println(is.pop()?)   // 20
```

Generics are now first‑class and fully type‑checked at compile time.

---  

## 8️⃣ Structs, Methods & Interfaces – embedded structs & traits <a name="8-structs"></a>

```v
// Embedded struct – inheritance‑like field promotion
struct Point { x int; y int }
struct Circle {
    Point        // embeds Point
    radius f64
}
c := Circle{
    Point: Point{10, 20}
    radius: 5
}
println(c.x)          // 10 – promoted field
println(c.point())    // if we add a method on Point, Circle gets it

// Interface (trait) example
interface Shape {
    area() f64
}
fn (c Circle) area() f64 { return math.pi * c.radius * c.radius }
fn (r Rectangle) area() f64 { return r.w * r.h }

fn total_area(shapes []Shape) f64 {
    mut sum := 0.0
    for s in shapes { sum += s.area() }
    return sum
}
```

---  

## 9️⃣ Modules & Packages – `v.mod`, imports & conditional compilation <a name="9-modules"></a>

### 9.1 Standard layout

```
my_app/
├── v.mod               # module metadata
├── main.v
├── src/
│   ├── utils/
│   │   └─ math.v
│   └─ models/
│       └─ user.v
└── tests/
    └─ utils_test.v
```

### 9.2 Import styles

```v
import os               // std lib
import net.http as http // alias
import github.com/vlang/vweb   // third‑party
import .utils.math      // relative import (same module)
```

### 9.3 Conditional compilation (new syntax)

```v
@[if debug ?]                 // attribute version
fn log_debug(s string) { eprintln(s) }

fn main() {
    $if as_client ? {          // compile‑time flag version
        println('Running as client')
    }
    $if as_server ? {
        println('Running as server')
    }
}
```

Running with `v -d as_client main.v` builds a client‑only binary. See the docs*【12†L34-L43】.  

### 9.4 Compile‑time pseudo‑variables

```v
fn where_am_i() {
    println('Function: @FN, line: @LINE, file: @FILE')
}
```

These placeholders are replaced at compile time – useful for logging or version info*【12†L48-L63】.

---  

## 🔟 Error handling – the unified `?` / `!` model <a name="10-error-handling"></a>

```v
fn read_config(path string) ?map[string]string {
    txt := os.read_file(path) or { return none }   // propagate ‘none’
    return json.decode(txt) or { return none }
}

fn main() {
    cfg := read_config('config.json') or {
        eprintln('Could not load config: $err')
        exit(1)
    }
    println('Config loaded')
}
```

*Key points*  

* **Option (`?T`)** → “value may be missing”.  
* **Result (`!T`)** → “operation may fail with a string error”.  
* `or { … }` handles both; inside the block `err` holds the message.  
* `!` can be appended to a *call* to automatically propagate the error upward – see example in section 7.1.  

---  

## 1️⃣1️⃣ Memory Management – ARC, manual & cycles <a name="11-memory"></a>

V uses **automatic reference counting (ARC)** plus a cycle detector. You normally don’t free anything.

```v
struct Node {
    value int
    next  &Node   // reference; ARC will free when no longer reachable
}
```

If you need **manual control** (rare, performance‑critical code):

```v
import C

struct Manual {
    ptr voidptr
}
fn (mut m Manual) free() {
    C.free(m.ptr)
}
```

(See the “manual memory” part of the README)【4†L38-L41】.

---  

## 1️⃣2️⃣ Concurrency – goroutines, channels & `select` <a name="12-concurrency"></a>

```v
import time, sync

fn worker(id int, out chan int) {
    time.sleep(500 * time.millisecond)
    out <- id * 10
}

fn main() {
    ch := chan int{cap: 5}
    go worker(1, ch)
    go worker(2, ch)

    // Non‑blocking select with timeout
    select {
        v := <-ch { println('got $v') }
        200 * time.millisecond { println('timeout') }
    }
}
```

`select` works like Go’s counterpart and is useful for multiplexing multiple channels.

---  

## 1️⃣3️⃣ Advanced Features – generics, compile‑time values & attributes <a name="13-advanced"></a>

### 13.1 Compile‑time constants & pseudo‑variables

```v
const pi = 3.14159
const circle_area = pi * 10 * 10   // evaluated at compile time

fn version_info() string {
    return 'Built on @BUILD_DATE at @BUILD_TIME (hash @VHASH)'
}
```

*See pseudo‑variables list*【12†L48-L63】.

### 13.2 Attributes

```v
@[deprecated: 'use new_greeting() instead']
fn old_greeting() string { return 'hi' }

@[inline] // hint the compiler to inline
fn fast_mul(a, b int) int { return a * b }

@[if debug ?] // only compiled when `-d debug` is passed
fn debug_log(s string) { eprintln(s) }
```

Attributes let you control compilation, optimisation, and deprecation notices.

### 13.3 Function types & duck‑typing (still works)

```v
type Filter = fn (string) string

fn upper(s string) string { return s.to_upper() }

fn apply(s string, f Filter) string {
    return f(s)      // any function with matching signature works
}
println(apply('hello', upper))
```

(See function‑type section in the docs)【7†L132-L150】.

---  

## 1️⃣4️⃣ Best‑Practice Checklist & Common Gotchas <a name="14-best-practice"></a>

| ✅ Practice | Why it matters |
|---|---|
| **Immutable by default** – use `mut` only when needed | Guarantees thread‑safety & easier reasoning |
| **Explicit error handling** (`?` / `!`) | Forces you to consider failure cases; no silent exceptions |
| **Prefer `enum`/`sum‑type` over magic numbers** | Compile‑time exhaustiveness checking prevents bugs |
| **Use generics** for reusable containers (`Stack[T]`, `max[T]`) | Zero‑runtime cost, full static typing |
| **Write `match` exhaustively** (or include `else`) | Compiler will warn you when new enum cases appear |
| **Keep a flat module hierarchy** – one module per folder | Improves compile time and makes imports obvious |
| **Leverage compile‑time flags** (`$if …`) for optional features | No runtime overhead for dead code |
| **Test with `v test ./...`** – the built‑in test runner integrates with `testing` lib | Guarantees code stays correct as V evolves |
| **Avoid `unsafe` unless absolutely required** – manual `free` can break ARC | Keeps memory safety guarantees intact |

### Frequent Gotchas (updated)

| Gotcha | Fix |
|---|---|
| **`?` vs `!`** – confusing which to use | Use `?` when a value may be absent (`none`); use `!` when the function can fail with an error string. |
| **Shadowing mutable vars** – `mut x := 1` inside a block creates a new `x` | Keep an eye on scopes; use descriptive names. |
| **Returning a slice of a local array** – the array goes out of scope | Return a **copy** (`numbers.clone()`) or allocate on the heap (`[]int{len: …}`). |
| **Channel dead‑lock** – forgetting to close or read all values | Always either close the channel or use a buffered channel with a matching consumer. |
| **Using `or {}` without `err`** – you lose the error message | Write `or { eprintln(err); return }`. The variable `err` is automatically injected. |
| **`@` pseudo‑variables inside strings** – need no escaping | `println('At @LINE of @FN')` works directly. |

---  

## 🎉 Conclusion

This rewrite upgrades the original “V 0.4” tutorial to reflect the **current V master** (≈ 0.5‑dev) – covering new language constructs, better error handling, generics, compile‑time features, and up‑to‑date best practices. By following the checklist and the examples above, you’ll write **safe, fast, and idiomatic V code** that compiles today and will continue to compile when V reaches its 1.0 freeze.

Happy V programming! 🚀  