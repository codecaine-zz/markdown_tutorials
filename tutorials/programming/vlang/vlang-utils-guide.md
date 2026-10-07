# Vlang Utils (`vlang_utils`) - 40 Production Modules Complete Guide

`vlang_utils` is an enterprise-grade suite of **40 zero-dependency, self-contained utility modules** written in native [V (vlang)](https://vlang.io) for Rapid Application Development (RAD). It eliminates common boilerplate across filesystems, SQLite databases, cryptography, networking, terminal interfaces, concurrency, data structures, and web services.

Every module is designed with **zero external C dependencies**, strict type safety, idiomatic `!` Result error handling, and cross-platform compatibility across **macOS, Linux, and Windows**.

Repository: [codecaine-zz/vlang_utils](https://github.com/codecaine-zz/vlang_utils)

---

## 📚 Table of Contents

1. [Architectural Overview & Engineering Principles](#1-architectural-overview--engineering-principles)
2. [What's New in v2.0.0](#2-whats-new-in-v200)
3. [Installation & Project Integration](#3-installation--project-integration)
4. [Master 40-Module Classification & Directory](#4-master-40-module-classification--directory)
5. [The New v2.0 Modules](#5-the-new-v20-modules)
   - [5.1 `webutils` - Express-Style Web Framework & Template Engine](#51-webutils--express-style-web-framework--template-engine)
   - [5.2 `jsonutils` - RFC 6901 Pointer, RFC 7386 Merge Patch & Diff](#52-jsonutils--rfc-6901-pointer-rfc-7386-merge-patch--diff)
   - [5.3 `markdownutils` - GFM Parser, Anchor Slugs & TOC](#53-markdownutils--gfm-parser-anchor-slugs--toc)
6. [Core Data & Filesystem Modules](#6-core-data--filesystem-modules)
   - [`fileutils`, `sqliteutils`, `tomlutils`, `htmlutils`, `tarutils`, `archiveutils`, `compressutils`]
7. [Strings, Text & SemVer Modules](#7-strings-text--semver-modules)
   - [`strutils`, `regexutils`, `diffutils`, `templateutils`, `semverutils`]
8. [Collections, Data Structures & Math](#8-collections-data-structures--math)
   - [`sliceutils`, `structutils`, `mathutils`, `statutils`, `bitutils`, `graphutils`]
9. [Networking, Security & Identity](#9-networking-security--identity)
   - [`httputils`, `netutils`, `urlutils`, `jwtutils`, `validutils`, `cryptoutils`]
10. [Concurrency, Caching & Flow Control](#10-concurrency-caching--flow-control)
    - [`asyncutils`, `flowutils`, `cacheutils`, `stateutils`, `eventutils`]
11. [System, Terminal & Shell Tools](#11-system-terminal--shell-tools)
    - [`sysutils`, `envutils`, `cliutils`, `colorutils`, `logutils`, `cronutils`, `timeutils`, `mockutils`]
12. [Testing, Benchmarks & Standalone Demo Recipes](#12-testing-benchmarks--standalone-demo-recipes)

---

## 1. Architectural Overview & Engineering Principles

`vlang_utils` follows five core architectural pillars:

- **Standards First**: RFCs and specifications are cited directly in code and verified with published test vectors (RFC 4180 CSV, RFC 4226/6238 HOTP/TOTP, RFC 6901 JSON Pointer, RFC 7386 JSON Merge Patch, RFC 8785 Canonical JSON, SemVer 2.0.0, CommonMark/GFM).
- **Secure by Default**: Cryptographic operations use OS CSPRNG (`crypto.rand`), constant-time byte comparisons (`secure_compare`) to defeat timing attacks, strict `alg` verification in JWTs, automated secret redaction in logging, single-pass HTML entity sanitization, tar-slip / zip-slip traversal blocks, and bounded decompressors against zip/gzip bombs.
- **Predictable Complexity**: Documented and benchmarked algorithmic complexity-$O(1)$ LRU caching via slab-allocated doubly-linked structures, $O(n)$ hash-set operations, $O(ND)$ Myers diffing, and iterative DFS without stack overflow risks.
- **Zero Third-Party C Runtime Dependencies**: Built entirely on top of V's native standard library (`vlib`). Compiles directly to compact, standalone native binaries on all architectures.
- **100% Backward Compatibility**: Public APIs maintain stable function signatures and field structures across minor and major releases.

---

## 2. What's New in v2.0.0

Version 2.0.0 introduces major architectural enhancements and three brand-new foundational modules:

1. **Three New Modules**:
   - `webutils`: Full-featured Express-style web framework with route parameters, wildcards, route groups, signed cookies, sessions, CSRF, CORS, rate limiting, security headers (CSP nonce), static file serving with 304 ETags, multipart uploads, and an EJS-compatible template engine with sandboxed expressions.
   - `jsonutils`: RFC 6901 JSON Pointer (`pointer_get`, `pointer_set`), RFC 7386 JSON Merge Patch (`merge_patch`, `merge_patch_str`), RFC 8785 canonical key-sorted serialization, structural diffing (`diff`), deep equality (`deep_equal`), minification, and dotted-path flattening.
   - `markdownutils`: CommonMark and GitHub Flavored Markdown (GFM) renderer supporting tables, task lists, nested lists, code fences, heading slugs, automated TOC generation, and plain-text preview extraction.
2. **Security Hardening**:
   - `cryptoutils`: OS CSPRNG for tokens, UUID v4/v7, and NanoIDs. Added constant-time `secure_compare`, RFC 6238 TOTP, and ULID generation.
   - `jwtutils`: Algorithm pinning verification (`alg: none` rejection) and audience/issuer policy checks.
   - `tarutils` & `archiveutils`: Path traversal protection blocking **tar-slip** and **zip-slip** vulnerabilities.
   - `compressutils`: Bounded streaming decompressor (`decompress_limited`) preventing decompression bomb memory exhaustion.
   - `sysutils`: Shell-free command execution (`run_command`) eliminating command injection by construction.
3. **Performance & Correctness**:
   - `cacheutils`: Re-engineered LRUCache to true $O(1)$ lookup and eviction using index-linked slabs.
   - `fileutils`: Added RFC 4180 compliant CSV parser, atomic crash-safe file writing (`write_file_atomic`), and SHA-256 file hashing.
   - `flowutils`: Monotonic clock sliding-window rate limiters with amortized $O(1)$ cleanup.

---

## 3. Installation & Project Integration

### Global Installation via V Modules

Clone `vlang_utils` directly into your local `~/.vmodules` directory:

```bash
git clone https://github.com/codecaine-zz/vlang_utils.git ~/.vmodules/vlang_utils
```

Now you can import any module directly in your V scripts and projects:

```v
import vlang_utils.fileutils
import vlang_utils.sqliteutils
import vlang_utils.webutils
import vlang_utils.jsonutils
```

### Local Project Symlink (Submodule / Monorepo)

For repositories like `simple_gg`, `vlang_webview_rad_studio`, and `vlang_simplegui`, the 40 modules are directly accessible as root packages:

```v
import fileutils
import webutils
import jsonutils
import cryptoutils
```

---

## 4. Master 40-Module Classification & Directory

| Domain | Modules | Primary Capabilities |
| :--- | :--- | :--- |
| **Data & Filesystem** | `fileutils`, `sqliteutils`, `jsonutils`, `markdownutils`, `tomlutils`, `htmlutils`, `tarutils`, `archiveutils`, `compressutils` | Atomic files, CSV parsing, SQLite document store, JSON pointer/patch, GFM rendering, safe archives, Zstd/Gzip compression. |
| **Strings & Text** | `strutils`, `regexutils`, `diffutils`, `templateutils`, `semverutils` | Case transformations, Levenshtein, Myers unified diffs, XSS-safe Mustache templates, SemVer 2.0 range matching. |
| **Collections & Math** | `sliceutils`, `structutils`, `mathutils`, `statutils`, `bitutils`, `graphutils` | Set operations, PriorityQueue, Bloom filters, 2D vector geometry, topological DAG sort, Dijkstra shortest paths, Welford stats. |
| **Networking & Web** | `webutils`, `httputils`, `netutils`, `urlutils`, `jwtutils`, `validutils` | Express-style web server, HTTP retry client, port scanner, URL normalization, JWT signing with alg pinning, validator. |
| **Concurrency & Flow** | `asyncutils`, `flowutils`, `cacheutils`, `stateutils`, `eventutils` | Worker pools, parallel map, token bucket, sliding window limiter, O(1) LRU, atomic state persistence, event emitter. |
| **System & Shell** | `sysutils`, `envutils`, `cliutils`, `colorutils`, `logutils`, `cronutils`, `timeutils`, `mockutils` | Hardware telemetry, safe exec, .env loader, CLI tables/spinners, OKLCH color spaces, secret redaction, POSIX cron, synthetic data. |

---

## 5. The New v2.0 Modules

### 5.1 `webutils` - Express-Style Web Framework & Template Engine

`webutils` provides a production-grade web framework modeled after Express and Koa, built with zero third-party dependencies using V's native standard library.

#### Features
- **Route Engine**: Named parameters (`/users/:id`), optional parameters (`/posts/:slug?`), wildcards (`/files/*path`), and route grouping (`app.group('/api/v1')`).
- **Middleware Pipeline**: Security headers (CSP nonce, HSTS, X-Frame-Options), CORS, rate limiting, CSRF protection, sessions, flash messages, request IDs, and gzip compression.
- **Built-in EJS-Compatible Template Engine**: Layouts, partials, sandboxed expressions, and 45+ built-in filters (`| upper`, `| date`, `| truncate`).
- **In-Process Testing**: `app.request(...)` lets you test full HTTP request-response cycles in memory without binding network ports.

#### Production Web App Example

```v
import webutils
import json2

fn main() {
    mut app := webutils.new_app(
        secret: 'super-secret-key-at-least-32-chars-long'
        views_dir: 'views'
    )

    // Middleware stack
    app.use(webutils.security_headers())
    app.use(webutils.cors(origins: ['*']))
    app.use(webutils.rate_limit(max: 100, window_secs: 60))
    app.use(webutils.sessions())

    // In-memory template registration
    app.views.add('layout', '<!DOCTYPE html><html><body><%- body %></body></html>')!
    app.views.add('welcome', '<% layout("layout") -%><h1>Welcome, <%= name | title %>!</h1>')!

    // Static assets
    app.static('/static', './public')

    // REST API route group
    mut api := app.group('/api')
    api.get('/health', fn (mut c webutils.Context) ! {
        c.json({ 'status': 'healthy', 'uptime': 'ok' })!
    })

    // HTML view route
    app.get('/greet/:user', fn (mut c webutils.Context) ! {
        username := c.param('user')
        c.render('welcome', { 'name': json2.Any(username) })!
    })

    // In-process verification test (Supertest-style)
    res := app.request(method: 'GET', path: '/api/health')
    println('Test status: ${res.status}, body: ${res.body}')

    // Start server
    app.listen(8080)
}
```

---

### 5.2 `jsonutils` - RFC 6901 Pointer, RFC 7386 Merge Patch & Diff

`jsonutils` extends V's native `x.json2` with structural manipulation, RFC standards compliance, and diffing.

```v
import jsonutils

fn main() {
    doc_json := '{"user":{"name":"Alice","roles":["admin","editor"],"profile":{"age":30}}}'
    doc := jsonutils.parse(doc_json)!

    // 1. RFC 6901 JSON Pointer Querying
    name := jsonutils.pointer_get(doc, '/user/name')!
    println('User name: ${name.str()}') // "Alice"

    first_role := jsonutils.pointer_get(doc, '/user/roles/0')!
    println('First role: ${first_role.str()}') // "admin"

    // 2. Modifying values via pointer
    mut updated := jsonutils.pointer_set(doc, '/user/profile/age', jsonutils.Any(31))!

    // 3. RFC 7386 JSON Merge Patch
    patch := '{"user":{"profile":{"title":"Lead Architect"},"roles":null}}'
    merged := jsonutils.merge_patch(updated, jsonutils.parse(patch)!)

    // 4. Canonical deterministic serialization (sorted keys for signatures)
    canonical_str := jsonutils.encode_canonical(merged, true)
    println('Canonical: ${canonical_str}')

    // 5. Structural Diffing
    changes := jsonutils.diff(doc, merged)
    for ch in changes {
        println('Change: ${ch.op} at ${ch.path} (old: ${ch.old}, new: ${ch.new})')
    }

    // 6. Flatten nested JSON to dotted keys
    flat_map := jsonutils.flatten(doc)
    println('user.profile.age = ${flat_map["user.profile.age"]}') // "30"
}
```

---

### 5.3 `markdownutils` - GFM Parser, Anchor Slugs & TOC

`markdownutils` turns Markdown into sanitised HTML, extracts document outlines, and generates tables of contents.

```v
import markdownutils

fn main() {
    md := '
# Enterprise Architecture

## Microservices Overview
- [x] Implement API Gateway
- [ ] Setup Service Mesh

## Database Persistence
Refer to [V Documentation](https://vlang.io) for more information.

| Service | Port | Status |
| :--- | :--- | :--- |
| Auth | 8081 | Active |
| Payments | 8082 | Active |
'

    // 1. Render to sanitized HTML with GFM tables and task lists
    html := markdownutils.to_html(md, heading_ids: true, safe_links: true)
    println('HTML Output:\n${html}')

    // 2. Generate Markdown Table of Contents
    toc := markdownutils.toc(md, 2)
    println('TOC:\n${toc}')
    // Output:
    // - [Enterprise Architecture](#enterprise-architecture)
    //   - [Microservices Overview](#microservices-overview)
    //   - [Database Persistence](#database-persistence)

    // 3. Extract Headings Outline
    headings := markdownutils.headings(md)
    for h in headings {
        println('H${h.level}: ${h.text} (slug: #${h.id})')
    }

    // 4. Plain text preview for search indexing or card excerpts
    plain := markdownutils.to_plain_text(md)
    println('Plain text:\n${plain}')
}
```

---

## 6. Core Data & Filesystem Modules

### 6.1 `fileutils`
High-level file operations, atomic writes, RFC 4180 CSV parsing, and MIME detection:

```v
import fileutils

// 1. Crash-safe atomic write (write to temp file + atomic OS rename)
fileutils.write_file_atomic('config.json', '{"status":"ok"}')!

// 2. RFC 4180 CSV reading and writing
fileutils.write_csv('reports.csv', [
    ['id', 'name', 'score'],
    ['1', 'Alice', '98.5'],
    ['2', 'Bob', '91.2'],
], `,`)!

rows := fileutils.read_csv('reports.csv', `,`)!
println('Loaded ${rows.len} CSV records')

// 3. Human-readable file size formatting and parsing
size_text := fileutils.format_size(1048576 * 15) // "15.00 MB"
bytes := fileutils.parse_size('250 MB')!        // 262144000

// 4. SHA-256 Checksum
hash := fileutils.file_hash_sha256('config.json')!
println('SHA256: ${hash}')

// 5. Recursive directory walking
v_files := fileutils.walk_dir_ext('.', '.v')!
```

### 6.2 `sqliteutils`
Ergonomic SQLite wrapper with automated migrations, Key-Value store, and JSON Document Store:

```v
import sqliteutils

mut db := sqliteutils.open_db('production.db')!
defer { sqliteutils.close_db(mut db) or {} }

// 1. Key-Value Store abstraction
sqliteutils.create_kv_table(mut db, 'cache')!
sqliteutils.set_kv(mut db, 'cache', 'session_id', 'sess_982341')!
token := sqliteutils.get_kv_or(mut db, 'cache', 'session_id', 'guest')

// 2. Parameterized Safe Queries (SQL Injection Protected)
db.exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, email TEXT);')!
sqliteutils.exec_params(mut db, 'INSERT INTO users (name, email) VALUES (?1, ?2);', [
    'Alice Smith',
    'alice@example.com'
])!

// 3. Transactions with Automatic Rollback on Failure
sqliteutils.transaction(mut db, fn (mut tx sqliteutils.DB) ! {
    sqliteutils.exec_params(mut tx, 'UPDATE users SET email=?1 WHERE id=?2;', ['new@example.com', '1'])!
})!
```

### 6.3 `tarutils` & `archiveutils` (Path Traversal Hardened)
Create and extract TAR and ZIP archives safely:

```v
import tarutils
import archiveutils

// Safe TAR archive creation & extraction (blocks tar-slip)
tarutils.create_tar('backup.tar', ['src/', 'README.md'])!
tarutils.extract_tar('backup.tar', 'extracted_tar/')!

// Safe ZIP archive creation & extraction (blocks zip-slip)
archiveutils.create_zip('release.zip', ['bin/app', 'assets/'])!
archiveutils.unzip_to_dir('release.zip', 'extracted_zip/')!
```

### 6.4 `compressutils`
High-speed compression supporting Gzip, Zlib, Deflate, and Zstandard (Zstd):

```v
import compressutils

payload := 'High-frequency telemetry stream data '.repeat(1000)

// Zstandard compression
compressed_zstd := compressutils.compress_zstd_string(payload)!
decompressed := compressutils.decompress_zstd_string(compressed_zstd)!
assert decompressed == payload

// Bounded decompression (protection against decompression bombs)
safe_data := compressutils.decompress_limited(compressed_zstd, 1024 * 1024)!
```

---

## 7. Strings, Text & SemVer Modules

### 7.1 `strutils`
String manipulation, case transformations, fuzzy distance, and formatting:

```v
import strutils

// 1. Case Conversions
slug := strutils.slugify('Cloud Computing Architecture 2026!') // "cloud-computing-architecture-2026"
snake := strutils.to_snake_case('UserProfileController')       // "user_profile_controller"
camel := strutils.to_camel_case('api_secret_key')              // "apiSecretKey"
kebab := strutils.to_kebab_case('DatabaseConnectionPool')      // "database-connection-pool"

// 2. Number & Integer formatting
fmt_num := strutils.format_int_commas(12500000) // "12,500,000"
ordinal := strutils.ordinal(23)                 // "23rd"

// 3. Fuzzy Matching & Levenshtein / Jaro-Winkler
dist := strutils.levenshtein('kitten', 'sitting') // 3
jaro := strutils.jaro_winkler('dixon', 'dicksonx') // 0.8133
```

### 7.2 `semverutils`
Strict SemVer 2.0.0 parsing, npm range comparisons, and version sorting:

```v
import semverutils

v1 := semverutils.parse('2.4.1-beta.1+build.123')!
println('Major: ${v1.major}, Minor: ${v1.minor}, Patch: ${v1.patch}')

// Range matching
is_satisfied := semverutils.satisfies('2.4.1', '^2.0.0') // true
is_pre := semverutils.satisfies('1.5.0', '>=1.2.0 <2.0.0') // true

// Sort version tags
mut tags := ['1.10.0', '1.2.0', '2.0.0-rc.1', '1.2.1']
semverutils.sort_versions(mut tags)
// tags == ['1.2.0', '1.2.1', '1.10.0', '2.0.0-rc.1']
```

### 7.3 `diffutils`
Myers $O(ND)$ diffing, word diffs, and unified patch generation:

```v
import diffutils

old_text := "function compute() {\n  return 42;\n}\n"
new_text := "function compute() {\n  // optimized\n  return 100;\n}\n"

// Generate Git-compatible unified diff
patch := diffutils.unified_diff(old_text, new_text, 'compute.js')
print(patch)
```

---

## 8. Collections, Data Structures & Math

### 8.1 `sliceutils`
Functional slicing, frequency maps, chunking, and sliding windows:

```v
import sliceutils

nums := [10, 20, 30, 40, 50, 60]

// 1. Chunking & Sliding Windows
chunks := sliceutils.chunk[int](nums, 2) // [[10, 20], [30, 40], [50, 60]]
windows := sliceutils.window[int](nums, 3, 1) // [[10, 20, 30], [20, 30, 40], ...]

// 2. Frequency counting
freq := sliceutils.frequency(['apple', 'banana', 'apple', 'orange', 'banana', 'apple'])
// freq['apple'] == 3, freq['banana'] == 2

// 3. Fast binary search on sorted slices
sorted := [5, 12, 27, 43, 68, 92]
idx := sliceutils.binary_search[int](sorted, 43) // 3
```

### 8.2 `structutils`
Generic advanced data structures: `PriorityQueue[T]`, `RingBuffer[T]`, `BloomFilter`, and `Trie`:

```v
import structutils

// 1. Priority Queue (MinHeap)
mut pq := structutils.new_priority_queue[string]()
pq.push('low_priority_task', 10)
pq.push('critical_task', 1)
task := pq.pop()! // "critical_task"

// 2. Trie Autocomplete
mut trie := structutils.new_trie()
trie.insert('vlang')
trie.insert('vlang_utils')
trie.insert('vlang_simplegui')
suggestions := trie.autocomplete('vlang_') // ['vlang_utils', 'vlang_simplegui']

// 3. Bloom Filter
mut filter := structutils.new_bloom_filter(1000, 0.01) // 1000 items, 1% false positive
filter.add('visited_url_hash')
assert filter.contains('visited_url_hash') == true
```

### 8.3 `graphutils`
Directed graphs, topological sort (Kahn's algorithm), cycle detection, and BFS/DFS:

```v
import graphutils

mut g := graphutils.new_graph[string]()
g.add_edge('fetch_data', 'clean_data')
g.add_edge('clean_data', 'train_model')
g.add_edge('train_model', 'evaluate_metrics')

// Topological Sort for build/task scheduling
order := g.topological_sort()!
println('Execution Order: ${order}')
// ['fetch_data', 'clean_data', 'train_model', 'evaluate_metrics']

// Cycle detection
assert g.has_cycle() == false
```

---

## 9. Networking, Security & Identity

### 9.1 `cryptoutils`
CSPRNG random tokens, authenticated encryption, TOTP, and constant-time comparison:

```v
import cryptoutils

// 1. CSPRNG Cryptographic Tokens & Identifiers
token := cryptoutils.random_hex(32)     // 64-char secure hex token
uuid  := cryptoutils.uuid_v4()          // "c7a8b4e2-..."
ulid  := cryptoutils.generate_ulid()     // 26-char sortable ULID

// 2. Constant-Time String Comparison (Timing-Attack Safe)
is_valid := cryptoutils.secure_compare('expected_hash_token', 'user_hash_token')

// 3. RFC 6238 Time-Based One-Time Password (TOTP)
totp := cryptoutils.generate_totp('JBSWY3DPEHPK3PXP', 0, 6)!
println('Current 2FA Token: ${totp}')
```

### 9.2 `jwtutils`
JWT signing and verification with algorithm pinning:

```v
import jwtutils
import time

claims := jwtutils.JWTClaims{
    sub: 'usr_84920'
    iss: 'auth_service'
    exp: time.now().unix() + 3600
    custom: { 'role': 'admin', 'tenant': 'us-east' }
}

// Sign with HS256
token := jwtutils.sign_jwt(claims, 'secret-signing-key')!

// Verify with algorithm validation (blocks alg:none attacks)
verified := jwtutils.verify_jwt(token, 'secret-signing-key')!
println('Authenticated user: ${verified.sub}, Role: ${verified.custom['role']}')
```

### 9.3 `httputils`
Ergonomic HTTP client with exponential retries, typed JSON deserialization, and auth headers:

```v
import httputils

struct Release {
    tag_name string
    name     string
}

mut client := httputils.new_client(
    base_url: 'https://api.github.com'
    timeout_ms: 5000
    max_retries: 3
)

client.set_header('User-Agent', 'V-Rad-Client')

// Typed JSON GET request
release := client.get_json[Release]('/repos/codecaine-zz/vlang_utils/releases/latest')!
println('Latest Tag: ${release.tag_name}')
```

---

## 10. Concurrency, Caching & Flow Control

### 10.1 `asyncutils`
Worker pools, parallel mapping, and concurrency limiters:

```v
import asyncutils
import time

// 1. Bounded Worker Pool
mut pool := asyncutils.new_worker_pool(4, 32)!
defer { pool.stop() }

for i in 0 .. 10 {
    pool.submit(fn [i] () {
        println('Executing background job #${i}')
        time.sleep(20 * time.millisecond)
    })!
}
pool.wait_all()

// 2. Parallel Order-Preserving Map
urls := ['page1', 'page2', 'page3', 'page4']
results := asyncutils.parallel_map[string, int](urls, 2, fn (url string) int {
    return url.len
})
// results == [5, 5, 5, 5]
```

### 10.2 `cacheutils`
True $O(1)$ LRU cache and auto-expiring TTL cache:

```v
import cacheutils
import time

// True O(1) LRU Cache with capacity 100
mut lru := cacheutils.new_lru_cache[string, string](100)
lru.put('key1', 'value1')
val := lru.get('key1') or { 'not_found' }

// TTL Cache (entries expire after 30 seconds)
mut ttl := cacheutils.new_ttl_cache[string, string](30 * time.second)
ttl.set('session', 'active_session_data')
```

### 10.3 `flowutils`
Monotonic sliding-window rate limiters, token buckets, and circuit breakers:

```v
import flowutils
import time

// Sliding-window rate limiter: 50 requests per 10-second window
mut limiter := flowutils.new_sliding_window_limiter(50, 10 * time.second)!
if limiter.allow() {
    // Process request
} else {
    println('Rate limit exceeded. Retry in: ${limiter.retry_after_ms()} ms')
}

// Circuit Breaker: Halt requests after 5 consecutive failures for 30s
mut cb := flowutils.new_circuit_breaker(5, 30 * time.second)!
if cb.can_execute() {
    // Call remote service
}
```

---

## 11. System, Terminal & Shell Tools

### 11.1 `sysutils`
Hardware telemetry, memory metrics, CPU count, and safe subprocess execution:

```v
import sysutils

// System Hardware Probing
cpu_cores := sysutils.cpu_count()
mem := sysutils.memory_info()!
println('CPU Cores: ${cpu_cores}, Total RAM: ${mem.total_mb} MB, Free: ${mem.free_mb} MB')

// Safe process execution (shell-free, zero injection risk)
res := sysutils.run_command('git', ['status', '--short'])!
println('Git status:\n${res.stdout}')
```

### 11.2 `cliutils`
Terminal ANSI styling, tables, loading spinners, and interactive prompts:

```v
import cliutils

// Terminal Table
mut table := cliutils.new_table(['Service', 'Host', 'Port', 'Status'])
table.add_row(['Postgres', '10.0.0.1', '5432', 'Online'])
table.add_row(['Redis', '10.0.0.2', '6379', 'Online'])
println(table.render())

// Loading Spinner
mut spinner := cliutils.new_spinner('Deploying microservices...')
spinner.start()
// do work
spinner.stop_and_persist('[SUCCESS]', 'Deployment finished.')
```

---

## 12. Testing, Benchmarks & Standalone Demo Recipes

Every module in `vlang_utils` comes with dedicated unit test suites and standalone runnable demo scripts.

### Running Test Suites

```bash
# Run unit tests across all 40 modules
v test .

# Run test for a specific module
v test jsonutils/
v test webutils/
v test markdownutils/
```

### Running Demo Scripts

All 40 utility modules have dedicated demo scripts in `demos/`:

```bash
# Run all 40 demos sequentially with elapsed timing:
v run demos/run_all_demos.v

# Run individual module demos:
v run demos/demo_webutils.v
v run demos/demo_jsonutils.v
v run demos/demo_markdownutils.v
v run demos/demo_sqliteutils.v
v run demos/demo_cryptoutils.v
v run demos/demo_asyncutils.v
```
