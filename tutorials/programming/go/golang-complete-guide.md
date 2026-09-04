# Go (Golang) Complete Language & Concurrency Guide

`Go` (Golang) is an open-source, statically typed, compiled programming language designed at Google for simplicity, high performance, and effortless concurrency. Powering modern cloud infrastructure (Docker, Kubernetes, Terraform, Prometheus), Go combines the execution speed of C with high developer productivity.

---

## 📚 Table of Contents

1. [Overview & Go Design Philosophy](#overview--go-design-philosophy)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Modules & Project Setup (`go mod init`)](#modules--project-setup-go-mod-init)
4. [Syntax Fundamentals: Variables, Control Flow & Functions](#syntax-fundamentals-variables-control-flow--functions)
5. [Structs, Methods & Interfaces](#structs-methods--interfaces)
6. [Generics & Type Parameters](#generics--type-parameters)
7. [Mastering Concurrency: Goroutines & Channels](#mastering-concurrency-goroutines--channels)
8. [Sync Package: WaitGroups & Mutexes](#sync-package-waitgroups--mutexes)
9. [Error Handling & Custom Errors](#error-handling--custom-errors)
10. [Building & Cross-Compiling Standalone Binaries](#building--cross-compiling-standalone-binaries)
11. [Everyday Cheat Sheet & Useful Commands](#everyday-cheat-sheet--useful-commands)

---

## 🔍 Overview & Go Design Philosophy

Go prioritizes clean syntax, fast compilation, safety, and built-in concurrency primitives.

- **Fast Compilation**: Compiles directly to machine code in seconds.
- **Single Self-Contained Binary**: Deploys as a single static executable with zero external runtime dependencies.
- **First-Class Concurrency**: Goroutines cost only ~2KB of memory (allowing millions of concurrent routines per process).
- **Standard Library**: Rich built-in packages for HTTP, JSON, cryptography, and IO.

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install go
```

### 2. Verify Installation & Environment

```bash
go version
go env GOPATH GOROOT
```

---

## 🚀 Modules & Project Setup (`go mod init`)

Initialize a modern Go module:

```bash
mkdir my-go-service && cd my-go-service
go mod init github.com/username/my-go-service
```

### Add External Packages:

```bash
# Add popular router (e.g. chi)
go get github.com/go-chi/chi/v5

# Clean and synchronize go.mod and go.sum
go mod tidy
```

---

## 📝 Syntax Fundamentals

### `main.go`:

```go
package main

import (
	"fmt"
)

func main() {
	// Variable declaration with type inference
	message := "Hello, Antigravity!"
	count := 42
	isValid := true

	fmt.Printf("%s | Count: %d | Valid: %t\n", message, count, isValid)

	// Slices & Maps
	languages := []string{"Go", "Rust", "TypeScript"}
	languages = append(languages, "Python")

	scores := map[string]int{
		"Alice": 95,
		"Bob":   88,
	}

	for name, score := range scores {
		fmt.Printf("%s scored %d\n", name, score)
	}
}
```

---

## 🧩 Structs, Methods & Interfaces

Go uses implicit interfaces and composition instead of classical inheritance:

```go
package main

import (
	"fmt"
	"math"
)

// Define Interface
type Shape interface {
	Area() float64
}

// Define Struct
type Circle struct {
	Radius float64
}

type Rectangle struct {
	Width, Height float64
}

// Implement methods on Circle
func (c Circle) Area() float64 {
	return math.Pi * c.Radius * c.Radius
}

// Implement methods on Rectangle
func (r Rectangle) Area() float64 {
	return r.Width * r.Height
}

// Polymorphic function accepting interface
func PrintArea(s Shape) {
	fmt.Printf("Shape Area: %.2f\n", s.Area())
}
```

---

## 🧬 Generics & Type Parameters

Write reusable, type-safe functions and data structures across multiple types:

```go
package main

import "fmt"

// Map transforms a slice of type T to type R
func Map[T any, R any](slice []T, f func(T) R) []R {
	result := make([]R, len(slice))
	for i, v := range slice {
		result[i] = f(v)
	}
	return result
}

func main() {
	numbers := []int{1, 2, 3, 4}
	doubled := Map(numbers, func(n int) int { return n * 2 })
	fmt.Println(doubled) // [2 4 6 8]
}
```

---

## ⚡ Mastering Concurrency: Goroutines & Channels

### 1. Spawning Goroutines

Prefix any function call with `go` to execute it concurrently in a lightweight thread:

```go
package main

import (
	"fmt"
	"time"
)

func worker(id int, ch chan string) {
	time.Sleep(100 * time.Millisecond)
	ch <- fmt.Sprintf("Worker %d completed task", id)
}

func main() {
	// Create buffered channel
	ch := make(chan string, 3)

	// Spawn 3 concurrent workers
	for i := 1; i <= 3; i++ {
		go worker(i, ch)
	}

	// Receive results
	for i := 1; i <= 3; i++ {
		result := <-ch
		fmt.Println(result)
	}
}
```

---

## 🔒 Sync Package: WaitGroups & Mutexes

Coordinate multiple goroutines safely without channels:

```go
package main

import (
	"fmt"
	"sync"
)

func main() {
	var wg sync.WaitGroup
	var mu sync.Mutex
	counter := 0

	for i := 0; i < 100; i++ {
		wg.Add(1)
		go func() {
			defer wg.Done()
			mu.Lock()
			counter++
			mu.Unlock()
		}()
	}

	wg.Wait()
	fmt.Printf("Final Thread-Safe Counter: %d\n", counter) // Output: 100
}
```

---

## ⚠️ Error Handling & Custom Errors

Idiomatic Go error handling and custom error types:

```go
package main

import (
	"errors"
	"fmt"
)

type NotFoundError struct {
	Resource string
	ID       string
}

func (e *NotFoundError) Error() string {
	return fmt.Sprintf("%s with ID '%s' not found", e.Resource, e.ID)
}

func FindUser(id string) (string, error) {
	if id == "" {
		return "", errors.New("empty user ID")
	}
	if id != "1" {
		return "", &NotFoundError{Resource: "User", ID: id}
	}
	return "Alice", nil
}
```

---

## 🏗️ Building & Cross-Compiling Standalone Binaries

Go includes built-in cross-compilation for any OS and architecture without external toolchains:

```bash
# Build for current machine (e.g. macOS ARM64)
go build -o myapp .

# Cross-compile for Linux x86_64 server (Ubuntu, Debian, RHEL)
GOOS=linux GOARCH=amd64 go build -ldflags="-s -w" -o myapp-linux-amd64 .

# Cross-compile for Windows 64-bit
GOOS=windows GOARCH=amd64 go build -o myapp.exe .
```

---

## 📋 Everyday Cheat Sheet & Useful Commands

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Run code** | `go run main.go` |
| **Build binary** | `go build -o app .` |
| **Tidy dependencies** | `go mod tidy` |
| **Run tests** | `go test ./...` |
| **Test coverage** | `go test -cover ./...` |
| **Format code** | `go fmt ./...` |
| **Vet / Lint code** | `go vet ./...` |
| **Install CLI tool** | `go install <package>@latest` |
