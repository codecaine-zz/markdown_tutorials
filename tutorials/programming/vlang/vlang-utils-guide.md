# Vlang Utils (`vlang_utils`) — 37 Production Modules Complete Guide

`vlang_utils` is a comprehensive suite of 37 ergonomic, production-grade utility modules written in native [V (vlang)](https://vlang.io) for Rapid Application Development (RAD). It eliminates common boilerplate across filesystems, SQLite databases, cryptographic operations, networking, terminal interfaces, and data structures.

Repository: [codecaine-zz/vlang_utils](https://github.com/codecaine-zz/vlang_utils)

---

## 📚 Table of Contents

1. [Architectural Overview & Installation](#1-architectural-overview--installation)
2. [Module Directory & Classification](#2-module-directory--classification)
3. [Core Systems & Data Modules (1–12)](#3-core-systems--data-modules)
   - [`fileutils`, `sqliteutils`, `strutils`, `sliceutils`, `envutils`, `cryptoutils`]
   - [`timeutils`, `httputils`, `cliutils`, `sysutils`, `netutils`, `validutils`]
4. [Advanced Concurrency & Flow Control (13–24)](#4-advanced-concurrency--flow-control)
   - [`stateutils`, `statutils`, `cacheutils`, `structutils`, `semverutils`, `flowutils`]
   - [`templateutils`, `colorutils`, `archiveutils`, `asyncutils`, `regexutils`, `mockutils`]
5. [Specialized Protocols & Math (25–37)](#5-specialized-protocols--math)
   - [`logutils`, `tomlutils`, `htmlutils`, `bitutils`, `compressutils`, `tarutils`]
   - [`mathutils`, `cronutils`, `urlutils`, `jwtutils`, `eventutils`, `diffutils`, `graphutils`]
6. [Testing & Standalone Execution Recipes](#6-testing--standalone-execution-recipes)

---

## 1. Architectural Overview & Installation

All modules are designed with zero external runtime C dependencies, strict type safety, idiomatic `!` Result error handling, and cross-platform compatibility across macOS, Linux, and Windows.

### Importing Modules

Clone into your V modules path or project:

```bash
git clone https://github.com/codecaine-zz/vlang_utils.git ~/.vmodules/vlang_utils
```

In your V application:

```v
import vlang_utils.fileutils
import vlang_utils.sqliteutils
import vlang_utils.cryptoutils
```

---

## 2. Module Directory & Classification

| Category | Modules |
| :--- | :--- |
| **Data & Filesystem** | `fileutils`, `sqliteutils`, `tomlutils`, `htmlutils`, `tarutils`, `archiveutils`, `compressutils` |
| **Strings & Text** | `strutils`, `regexutils`, `diffutils`, `templateutils`, `semverutils` |
| **Collections & Math** | `sliceutils`, `mathutils`, `statutils`, `bitutils`, `graphutils` |
| **Networking & Web** | `httputils`, `netutils`, `urlutils`, `jwtutils`, `validutils` |
| **System & Shell** | `sysutils`, `envutils`, `cliutils`, `colorutils`, `logutils`, `cronutils`, `timeutils` |
| **Async & Flow** | `asyncutils`, `flowutils`, `cacheutils`, `stateutils`, `eventutils` |
| **Security & Dev** | `cryptoutils`, `mockutils`, `structutils` |

---

## 3. Core Systems & Data Modules

### 1. `fileutils`
Ergonomic atomic writes, directory walking, and human-readable file sizes:
```v
import fileutils

// Human readable file size conversion
size_str := fileutils.format_size(1048576) // "1.00 MB"

// Safe atomic file write (writes to temp file then renames)
fileutils.write_file_atomic('config.json', '{"status":"ok"}')!

// Recursive directory search with extension filter
files := fileutils.walk_dir_ext('src', '.v')!
```

### 2. `sqliteutils`
Ergonomic SQLite persistence, KV store, JSON document store, and parameterized queries:
```v
import sqliteutils

mut db := sqliteutils.open('app.db')!
defer { db.close() }

// Execute schema migration
db.exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, email TEXT);')!

// Parameterized safe insert
db.exec_params('INSERT INTO users (name, email) VALUES (?1, ?2);', ['Alice', 'alice@example.com'])!

// Key-Value Store abstraction
mut kv := sqliteutils.new_kv_store('kv.db')!
kv.set('session_token', 'abc-123')!
val := kv.get('session_token') or { 'expired' }
```

### 3. `strutils`
Case conversions, slugification, masking, and Levenshtein distance:
```v
import strutils

snake := strutils.to_snake_case('UserProfileController') // "user_profile_controller"
camel := strutils.to_camel_case('api_secret_key')        // "apiSecretKey"
slug  := strutils.slugify('Modern Web Development (2026)!') // "modern-web-development-2026"
dist  := strutils.levenshtein('kitten', 'sitting')        // 3
```

### 4. `sliceutils`
Generic collection operations (deduplication, chunking, flattening, stats):
```v
import sliceutils

nums := [1, 2, 2, 3, 4, 4, 5]
unique := sliceutils.unique[int](nums) // [1, 2, 3, 4, 5]

chunks := sliceutils.chunk[int]([1, 2, 3, 4, 5], 2) // [[1, 2], [3, 4], [5]]
sum_val := sliceutils.sum[int](nums) // 21
```

### 5. `envutils`
Type-safe environment variable retrieval and `.env` loading:
```v
import envutils

// Automatically loads .env file if present
envutils.load_dotenv('.env')!

port := envutils.get_int('PORT', 8080)
is_debug := envutils.get_bool('DEBUG', false)
db_url := envutils.get_str('DATABASE_URL', 'sqlite://app.db')
```

### 6. `cryptoutils`
Cryptographic hashing, HMAC, Base64URL, and secure UUID generation:
```v
import cryptoutils

hash := cryptoutils.sha256_string('password123')
hmac := cryptoutils.hmac_sha256('payload', 'secret_key')
uuid := cryptoutils.uuid_v4() // e.g. "a1b2c3d4-e5f6-4a7b-8c9d-0e1f2a3b4c5d"
```

### 7. `timeutils`
Human relative time, ISO 8601 formatting, and stopwatches:
```v
import timeutils
import time

past := time.now().add(-2 * time.hour)
relative := timeutils.time_ago(past) // "2 hours ago"

mut sw := timeutils.new_stopwatch()
sw.start()
// do heavy work
println('Duration: ${sw.elapsed_ms()} ms')
```

### 8. `httputils`
Ergonomic HTTP client with generic JSON deserialization and exponential retries:
```v
import httputils

struct Post {
    id int
    title string
}

// Fetch and decode JSON directly into a struct
post := httputils.get_json[Post]('https://jsonplaceholder.typicode.com/posts/1')!
println('Post Title: ${post.title}')
```

### 9. `cliutils`
Terminal ANSI formatting, progress bars, interactive spinners, and tables:
```v
import cliutils

// Terminal Table
mut table := cliutils.new_table(['ID', 'Name', 'Role'])
table.add_row(['1', 'Alice', 'Engineer'])
table.add_row(['2', 'Bob', 'Designer'])
println(table.render())
```

### 10. `sysutils`
System hardware telemetry (CPU cores, RAM usage, load average, disk space):
```v
import sysutils

cpu_cores := sysutils.cpu_count()
mem_info  := sysutils.memory_info()!
println('Total RAM: ${mem_info.total_mb} MB, Free: ${mem_info.free_mb} MB')
```

### 11. `netutils`
Network discovery, local IP, listening ports, and TCP ping:
```v
import netutils

is_up := netutils.tcp_ping('api.github.com', 443, 2000) // true
local_ip := netutils.get_local_ip()!
println('Local IP: ${local_ip}')
```

### 12. `validutils`
High-speed validation rules:
```v
import validutils

is_valid_email := validutils.is_email('user@domain.com') // true
is_valid_ip    := validutils.is_ipv4('192.168.1.1')      // true
is_valid_url   := validutils.is_url('https://bun.sh')    // true
```

---

## 4. Advanced Concurrency & Flow Control

### `flowutils`: Rate Limiting & Circuit Breakers
```v
import flowutils
import time

// Rate Limiter: 10 requests per second
mut limiter := flowutils.new_rate_limiter(10, 1.0)!
if limiter.allow() {
    // Process request
}

// Circuit Breaker (halts requests after 3 consecutive failures)
mut cb := flowutils.new_circuit_breaker(3, 5 * time.second)!
if cb.can_execute() {
    // Execute call to external dependency
}
```

### `asyncutils`: Worker Pools & Parallel Mapping
```v
import asyncutils

// Bounded parallel mapping (2 concurrent workers)
results := asyncutils.parallel_map[int, int]([1, 2, 3, 4], 2, fn (n int) int {
    return n * 10
})
// results == [10, 20, 30, 40]
```

### `cacheutils`: LRU & TTL Caching
```v
import cacheutils
import time

// Auto-expiring cache with 60 second TTL
mut cache := cacheutils.new_ttl_cache[string, string](60 * time.second)
cache.set('session', 'active')
val := cache.get('session') or { 'expired' }
```

---

## 5. Specialized Protocols & Math

### `jwtutils`: Zero-Dependency JWT Signing & Verification
```v
import jwtutils

token := jwtutils.sign_simple_token('user_42', 'secret_key', 3600)!
claims := jwtutils.verify_jwt(token, 'secret_key')!
println('Authenticated Subject: ${claims.sub}')
```

### `cronutils`: 5-Field Cron Scheduling
```v
import cronutils
import time

sched := cronutils.parse_cron('0 0 * * *')!
next_run := sched.next_after(time.now())!
human := cronutils.cron_to_human('0 0 * * *') // "Every day at midnight"
```

### `graphutils`: Topological Sort (DAG)
```v
import graphutils

mut dag := graphutils.new_graph[string]()
dag.add_edge('build', 'test')
dag.add_edge('test', 'deploy')

order := dag.topological_sort()! // ["build", "test", "deploy"]
```

---

## 6. Testing & Standalone Execution Recipes

```bash
# Run all unit tests across all 37 modules
v test .

# Run sequential demo scripts for all modules
v run demos/run_all_demos.v

# Run individual module demo
v run demos/demo_sqliteutils.v
v run demos/demo_jwtutils.v
```
