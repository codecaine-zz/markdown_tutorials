# Swift Complete Modern Language & Concurrency Guide

`Swift` is a modern, fast, type-safe, and memory-safe compiled programming language developed by Apple and open-source contributors. Optimized for Apple Silicon (ARM64) and modern systems, Swift powers iOS, iPadOS, macOS, watchOS, visionOS, and increasingly Linux and server-side runtimes.

With modern Swift (Swift 5.9 through Swift 6), the language features compile-time data race safety, native structured concurrency (`async`/`await`, `Task`, `actor`), protocol-oriented design, value semantics, and a zero-dependency Swift Package Manager (SPM).

---

## 📚 Table of Contents

1. [Overview & Modern Swift Philosophy](#1-overview--modern-swift-philosophy)
2. [Prerequisites, Installation & Tooling](#2-prerequisites-installation--tooling)
3. [Swift Package Manager (SPM) Quickstart](#3-swift-package-manager-spm-quickstart)
4. [Language Fundamentals & Type System](#4-language-fundamentals--type-system)
5. [Optionals & Safe Unwrapping](#5-optionals--safe-unwrapping)
6. [Collections & Functional Transformations](#6-collections--functional-transformations)
7. [Structs vs. Classes (Value vs. Reference Types) & ARC](#7-structs-vs-classes-value-vs-reference-types--arc)
8. [Enums & Pattern Matching Mastery](#8-enums--pattern-matching-mastery)
9. [Protocols, Extensions & Generics](#9-protocols-extensions--generics)
10. [Error Handling & Typed Throws](#10-error-handling--typed-throws)
11. [Modern Structured Concurrency (Async/Await & Actors)](#11-modern-structured-concurrency-asyncawait--actors)
12. [Codable: JSON Parsing & Serialization](#12-codable-json-parsing--serialization)
13. [Building Fast CLI Tools with Swift](#13-building-fast-cli-tools-with-swift)
14. [Everyday Production Cheat Sheet & Idiomatic Snippets](#14-everyday-production-cheat-sheet--idiomatic-snippets)

---

## 1. Overview & Modern Swift Philosophy

Modern Swift was engineered from the ground up to solve historic pitfalls of C and Objective-C:

- **Safety by Default**: Variables must be initialized before use; array bounds are checked; integer overflows trap; memory management is automatic.
- **Value Semantics First**: Structs and Enums are value types with built-in **Copy-on-Write (CoW)**, preventing unintended shared state mutations.
- **Zero-Cost Abstractions**: Generics and Protocol-oriented code are specialized at compile time to run at native assembly speeds.
- **Complete Data Race Safety**: Modern Swift guarantees that concurrent code cannot cause data races through compile-time actor isolation and Sendable checking.

> [!NOTE]
> On macOS Apple Silicon, Swift compiles to native AArch64 machine code with direct access to hardware acceleration (Accelerate framework, Metal, and Apple Neural Engine).

---

## 2. Prerequisites, Installation & Tooling

### macOS Setup

Swift is bundled with Xcode and Xcode Command Line Tools.

```bash
# Install Command Line Tools
xcode-select --install

# Verify compiler and version
swift --version
swiftc --version
```

### Swift REPL (Interactive Shell)

Test one-liners and algorithms instantly in the terminal:

```bash
# Launch interactive REPL
swift repl

# Inside REPL:
1> let greeting = "Hello, Swift!"
2> print(greeting.uppercased())
HELLO, SWIFT!
3> :quit
```

### Running Swift as a Script

Create a standalone `.swift` script and execute it directly:

```bash
cat << 'EOF' > hello.swift
import Foundation

let platform = ProcessInfo.processInfo.operatingSystemVersionString
print("Running natively on \(platform)")
EOF

swift hello.swift
```

---

## 3. Swift Package Manager (SPM) Quickstart

Swift Package Manager is the official tool for managing code distribution and dependency resolution across macOS and Linux.

### Initialize an Executable Project

```bash
mkdir swift-service && cd swift-service
swift package init --type executable --name SwiftService
```

Directory structure generated:

```text
swift-service/
├── Package.swift        # Project manifest & dependencies
└── Sources/
    └── main.swift       # Entry point
```

### Inspect `Package.swift`

```swift
// swift-tools-version: 5.9
import PackageDescription

let package = Package(
    name: "SwiftService",
    platforms: [
        .macOS(.v13)
    ],
    dependencies: [
        // External dependencies added here:
        // .package(url: "https://github.com/apple/swift-argument-parser.git", from: "1.3.0")
    ],
    targets: [
        .executableTarget(
            name: "SwiftService",
            dependencies: []
        )
    ]
)
```

### Essential SPM Commands

```bash
# Compile and run immediately in debug mode
swift run

# Run with custom arguments
swift run SwiftService --help

# Run unit tests
swift test

# Build optimized release binary
swift build -c release

# Binary output location:
# .build/release/SwiftService
```

---

## 4. Language Fundamentals & Type System

### Variable Declaration & Mutability

Use `let` for immutable constants (preferred default) and `var` only when mutation is necessary.

```swift
// Immutable constant
let maximumLoginAttempts = 5

// Mutable variable
var currentAttempt = 0
currentAttempt += 1

// Explicit type annotations vs Type inference
let language: String = "Swift"
let releaseYear = 2014          // Inferred as Int
let targetFPS: Double = 120.0    // Inferred as Double
let isUniversal: Bool = true
```

### String Interpolation & Multiline Strings

```swift
let user = "Alice"
let score = 98.5

// String interpolation with \(...)
let summary = "Player \(user) scored \(score)%"

// Multiline strings
let configJSON = """
{
    "service": "api-gateway",
    "port": 8080,
    "active": true
}
"""
```

### Control Flow (`guard`, `if`, `switch`)

Swift's `guard` statement enforces early exits, keeping code flat and avoiding deep nesting.

```swift
func validateInput(username: String, age: Int) -> Bool {
    guard !username.trimmingCharacters(in: .whitespaces).isEmpty else {
        print("Error: Username cannot be blank.")
        return false
    }

    guard age >= 18 else {
        print("Error: User must be 18 or older.")
        return false
    }

    return true
}
```

---

## 5. Optionals & Safe Unwrapping

Swift has no `null` or `NULL` pointer bugs. Missing values are represented as an `Optional<Wrapped>` type (syntax: `Type?`).

```swift
var serverResponseCode: Int? = 404
serverResponseCode = nil // Valid: now contains no value
```

### 4 Idiomatic Ways to Unwrap Optionals

```swift
let optionalName: String? = "Codecaine"

// 1. Optional Binding (if let)
if let name = optionalName {
    print("Welcome back, \(name)")
}

// 2. Early Exit (guard let) - keeps unwrapped variable in outer scope
func greetUser(name: String?) {
    guard let name else {
        print("Hello, Anonymous!")
        return
    }
    print("Hello, \(name)!")
}

// 3. Nil-Coalescing Operator (??) with default fallback
let displayName = optionalName ?? "Guest"

// 4. Optional Chaining (?.)
struct Server {
    var host: String?
}
struct Cluster {
    var primary: Server?
}

let cluster = Cluster(primary: Server(host: "node-01.local"))
let hostname = cluster.primary?.host?.uppercased() ?? "OFFLINE"
```

> [!WARNING]
> Avoid force unwrapping (`optionalValue!`). If the optional is `nil` at runtime, the application will immediately crash with a fatal error.

---

## 6. Collections & Functional Transformations

Swift provides three primary generic collection types: `Array<Element>`, `Set<Element>`, and `Dictionary<Key, Value>`.

```swift
// Arrays (ordered, indexed)
var tools = ["ripgrep", "bat", "fzf"]
tools.append("eza")

// Sets (unordered, unique elements conforming to Hashable)
var ports: Set<Int> = [80, 443, 8080, 80] // Duplicate 80 is removed

// Dictionaries (key-value pairs)
var headers: [String: String] = [
    "Content-Type": "application/json",
    "Accept": "*/*"
]
```

### Higher-Order Functional Transformations

```swift
let numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

// map: transform elements
let doubled = numbers.map { $0 * 2 }

// filter: select elements matching predicate
let evens = numbers.filter { $0 % 2 == 0 }

// reduce: aggregate into a single value
let sum = numbers.reduce(0, +)

// compactMap: filter out nils automatically
let stringInputs = ["42", "invalid", "100", "zero"]
let validIntegers = stringInputs.compactMap { Int($0) } // [42, 100]

// flatMap: transform and flatten nested arrays
let matrix = [[1, 2], [3, 4], [5, 6]]
let flatList = matrix.flatMap { $0 } // [1, 2, 3, 4, 5, 6]
```

---

## 7. Structs vs. Classes (Value vs. Reference Types) & ARC

Understanding the difference between value types and reference types is fundamental to idiomatic Swift.

| Characteristic | Struct (Value Type) | Class (Reference Type) |
| :--- | :--- | :--- |
| **Storage** | Stack or inlined inside container | Heap allocated |
| **Assignment** | Copies data (Copy-on-Write) | Copies reference pointer |
| **Inheritance** | Protocols only (No class inheritance) | Single inheritance |
| **Memory Mgmt** | Automatic lifecycle | Automatic Reference Counting (ARC) |
| **Thread Safety** | Inherently safer across threads | Requires synchronization / Actors |

```swift
// Value Type: Recommended for data models
struct SystemMetric {
    var cpuUsage: Double
    var memoryFreeMB: Int

    mutating func spike() {
        cpuUsage = 100.0
    }
}

var m1 = SystemMetric(cpuUsage: 14.5, memoryFreeMB: 8192)
var m2 = m1 // Independent copy!
m2.cpuUsage = 95.0

print(m1.cpuUsage) // Still 14.5
print(m2.cpuUsage) // 95.0
```

### Preventing Memory Retain Cycles in Classes with `weak`

```swift
class Device {
    let name: String
    weak var owner: UserProfile? // weak reference prevents retain cycles

    init(name: String) {
        self.name = name
    }
    
    deinit {
        print("\(name) was deallocated")
    }
}

class UserProfile {
    let username: String
    var device: Device?

    init(username: String) {
        self.username = username
    }
    
    deinit {
        print("User \(username) was deallocated")
    }
}
```

---

## 8. Enums & Pattern Matching Mastery

Swift enums are first-class types capable of storing **Associated Values**, raw values, computed properties, and methods.

```swift
enum NetworkState {
    case idle
    case connecting(endpoint: String, retryCount: Int)
    case connected(latencyMs: Double)
    case failed(errorDescription: String)

    var isConnected: Bool {
        if case .connected = self { return true }
        return false
    }
}

let state: NetworkState = .connecting(endpoint: "api.cloud.internal", retryCount: 2)

// Exhaustive pattern matching
switch state {
case .idle:
    print("Standing by.")
case .connecting(let endpoint, let retries) where retries > 1:
    print("Warning: Retrying connection to \(endpoint) (attempt \(retries))")
case .connecting(let endpoint, _):
    print("Connecting to \(endpoint)...")
case .connected(let latency):
    print("Connected! Latency: \(latency)ms")
case .failed(let reason):
    print("Fatal network error: \(reason)")
}
```

---

## 9. Protocols, Extensions & Generics

Swift is a **Protocol-Oriented Language**. Instead of deep class inheritance hierarchies, functionality is composed using protocols and extensions.

```swift
// Define a Protocol
protocol Serializable {
    func serialize() -> String
}

protocol IdentifiableEntity {
    var id: UUID { get }
}

// Struct conforming to protocols
struct ServiceConfig: Serializable, IdentifiableEntity {
    let id: UUID = UUID()
    let host: String
    let port: Int

    func serialize() -> String {
        return "\(host):\(port)"
    }
}

// Protocol Extension: Provide default implementation
extension Serializable {
    func printSummary() {
        print("Payload: [\(serialize())]")
    }
}

// Generics with Protocol Constraints
func exportEntities<T: Serializable & IdentifiableEntity>(_ items: [T]) {
    for item in items {
        print("Entity \(item.id.uuidString): \(item.serialize())")
    }
}
```

---

## 10. Error Handling & Typed Throws

Swift treats error handling as a first-class control flow mechanism using types conforming to `Error`.

```swift
enum StorageError: Error, LocalizedError {
    case fileNotFound(path: String)
    case insufficientDiskSpace(requiredMB: Int)
    case unauthorized

    var errorDescription: String? {
        switch self {
        case .fileNotFound(let path):
            return "File does not exist at: \(path)"
        case .insufficientDiskSpace(let mb):
            return "Requires at least \(mb)MB free space"
        case .unauthorized:
            return "Access denied"
        }
    }
}

func readConfigFile(path: String) throws -> String {
    guard path.hasPrefix("/safe/") else {
        throw StorageError.unauthorized
    }
    // Perform simulated read
    return "CONFIG_DATA_OK"
}

// Invoking throwing code
do {
    let content = try readConfigFile(path: "/etc/passwd")
    print(content)
} catch StorageError.unauthorized {
    print("Security Alert: Blocked unauthorized config read.")
} catch {
    print("Unexpected error: \(error.localizedDescription)")
}
```

---

## 11. Modern Structured Concurrency (Async/Await & Actors)

Modern Swift deprecates completion-handler callback pyramids in favor of compiler-checked structured concurrency.

### `async` / `await` Basics

```swift
func fetchRemoteStatus(url: URL) async throws -> (statusCode: Int, payload: String) {
    let (data, response) = try await URLSession.shared.data(from: url)
    let httpResponse = response as? HTTPURLResponse
    let code = httpResponse?.statusCode ?? 500
    let text = String(decoding: data, as: UTF8.self)
    return (code, text)
}
```

### Concurrent Tasks with `TaskGroup`

Run multiple network requests in parallel without race conditions:

```swift
func fetchAllEndpoints(urls: [URL]) async -> [String] {
    await withTaskGroup(of: String?.self) { group in
        for url in urls {
            group.addTask {
                do {
                    let (data, _) = try await URLSession.shared.data(from: url)
                    return String(decoding: data, as: UTF8.self)
                } catch {
                    return nil
                }
            }
        }

        var results: [String] = []
        for await result in group {
            if let result {
                results.append(result)
            }
        }
        return results
    }
}
```

### Thread-Safe Shared State with `actor`

Actors protect their mutable state from concurrent access, guaranteeing data-race freedom at compile time.

```swift
actor RequestRateLimiter {
    private var requestCount: Int = 0
    private let limit: Int

    init(limit: Int) {
        self.limit = limit
    }

    func increment() -> Bool {
        if requestCount < limit {
            requestCount += 1
            return true
        }
        return false
    }

    func reset() {
        requestCount = 0
    }
}

// Accessing an actor requires 'await'
Task {
    let limiter = RequestRateLimiter(limit: 100)
    let allowed = await limiter.increment()
    print("Request allowed: \(allowed)")
}
```

---

## 12. Codable: JSON Parsing & Serialization

The `Codable` protocol (`Encodable & Decodable`) provides automated, type-safe serialization.

```swift
import Foundation

struct UserAccount: Codable {
    let id: Int
    let username: String
    let email: String
    let isActive: Bool
    let registeredAt: Date

    // Custom coding keys for snake_case mapping
    enum CodingKeys: String, CodingKey {
        case id
        case username
        case email
        case isActive = "is_active"
        case registeredAt = "registered_at"
    }
}

let jsonRaw = """
{
    "id": 101,
    "username": "coder_pro",
    "email": "dev@apple.com",
    "is_active": true,
    "registered_at": 1700000000
}
""".data(using: .utf8)!

// Decode JSON
let decoder = JSONDecoder()
decoder.dateDecodingStrategy = .secondsSince1970

do {
    let account = try decoder.decode(UserAccount.self, from: jsonRaw)
    print("Successfully decoded account: \(account.username), Active: \(account.isActive)")
} catch {
    print("JSON Decode failed: \(error)")
}
```

---

## 13. Building Fast CLI Tools with Swift

Swift makes creating native, standalone command-line binaries fast and straightforward.

### Minimal CLI Template with Argument Handling

```swift
// Sources/main.swift
import Foundation

guard CommandLine.arguments.count > 1 else {
    print("Usage: \(CommandLine.arguments[0]) <filename>")
    exit(1)
}

let targetFile = CommandLine.arguments[1]
let fileManager = FileManager.default

if fileManager.fileExists(atPath: targetFile) {
    do {
        let attributes = try fileManager.attributesOfItem(atPath: targetFile)
        let fileSize = attributes[.size] as? Int64 ?? 0
        print("File: \(targetFile) (\(fileSize) bytes)")
    } catch {
        fputs("Error reading attributes: \(error)\n", stderr)
        exit(1)
    }
} else {
    fputs("Error: File '\(targetFile)' does not exist.\n", stderr)
    exit(1)
}
```

---

## 14. Everyday Production Cheat Sheet & Idiomatic Snippets

### 1. Measure Execution Benchmark

```swift
import Foundation

func measureBlock(label: String, block: () -> Void) {
    let start = DispatchTime.now()
    block()
    let end = DispatchTime.now()
    let nanoseconds = end.uptimeNanoseconds - start.uptimeNanoseconds
    let milliseconds = Double(nanoseconds) / 1_000_000.0
    print("[\(label)] Executed in: \(String(format: "%.3f", milliseconds)) ms")
}
```

### 2. Read and Write Plaintext Files

```swift
import Foundation

func writeToFile(text: String, destinationPath: String) throws {
    let url = URL(fileURLWithPath: destinationPath)
    try text.write(to: url, atomically: true, encoding: .utf8)
}

func readFromFile(sourcePath: String) throws -> String {
    let url = URL(fileURLWithPath: sourcePath)
    return try String(contentsOf: url, encoding: .utf8)
}
```

### 3. Run a Subprocess Command and Capture Output

```swift
import Foundation

@discardableResult
func runShellCommand(_ command: String) -> (exitCode: Int32, output: String) {
    let task = Process()
    let pipe = Pipe()

    task.standardOutput = pipe
    task.standardError = pipe
    task.arguments = ["-c", command]
    task.executableURL = URL(fileURLWithPath: "/bin/zsh")

    do {
        try task.run()
        task.waitUntilExit()

        let data = pipe.fileHandleForReading.readDataToEndOfFile()
        let output = String(decoding: data, as: UTF8.self)
        return (task.terminationStatus, output)
    } catch {
        return (-1, error.localizedDescription)
    }
}

// Example usage:
// let (code, out) = runShellCommand("uname -m")
// print("Architecture: \(out)")
```

### 4. Efficient Thread-Safe Singleton Pattern

```swift
final class AppEnvironment {
    static let shared = AppEnvironment()
    
    let runtimeID = UUID()
    private(set) var startupTimestamp = Date()

    private init() {
        // Enforces private initialization
    }
}
```
