# wrk (High-Performance HTTP Benchmark) Complete Guide

`wrk` is a modern, high-performance HTTP benchmarking tool capable of generating massive request loads when run on a single multi-core CPU. By combining an event-driven asynchronous architecture (utilizing `epoll` on Linux and `kqueue` on macOS) with multithreaded design, `wrk` can stress-test web servers, microservices, and reverse proxies far beyond the limits of traditional tools like ApacheBench (`ab`).

---

## 📚 Table of Contents

1. [Overview & wrk vs ab Architecture](#overview-wrk-vs-ab-architecture)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Benchmarking Run](#basic-benchmarking-run)
4. [Tuning Threads, Connections, and Duration](#tuning-threads-connections-and-duration)
5. [Detailed Latency Distribution (--latency)](#detailed-latency-distribution-latency)
6. [Adding Custom Headers & HTTP Payloads](#adding-custom-headers-http-payloads)
7. [Scripting Complex Workflows with Lua](#scripting-complex-workflows-with-lua)
8. [Benchmarking Best Practices](#benchmarking-best-practices)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & wrk vs ab Architecture <a id="overview-wrk-vs-ab-architecture"></a>

Traditional tools like ApacheBench (`ab`) create blocking threads or suffer from single-threaded bottlenecks where the benchmarking client maxes out its own CPU before saturating the target server.

| Feature | `wrk` | `ab` (ApacheBench) | `hey` / `vegeta` |
| :--- | :--- | :--- | :--- |
| **Engine** | Multithreaded + Event loop (`kqueue`/`epoll`) | Single-threaded | Goroutines |
| **Concurrency Scale** | 100,000+ connections | ~1,000 connections | ~10,000 connections |
| **Scriptability** | Built-in Lua engine | None | Limited |
| **Resource Footprint** | Extremely low C binary | Low | Moderate Go runtime |

---

## ⚙️ Homebrew Installation & Verification <a id="homebrew-installation-verification"></a>

### 1. Install via Homebrew

```bash
brew install wrk
```

### 2. Verify Installation

```bash
which wrk
wrk -v
```

Output:
```text
/opt/homebrew/bin/wrk
wrk 4.2.0 [kqueue] Copyright (C) 2012 Will Glozer
```

---

## 🚀 Basic Benchmarking Run <a id="basic-benchmarking-run"></a>

Run a 10-second benchmark against a local or remote target:

```bash
wrk -t2 -c50 -d10s https://httpbin.org/get
```

### Anatomy of Benchmark Results

```text
Running 10s test @ https://httpbin.org/get
  2 threads and 50 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    85.42ms   18.12ms 240.50ms   74.20%
    Req/Sec   284.10     45.20   380.00     68.50%
  5620 requests in 10.05s, 1.82MB read
Requests/sec:    559.20
Transfer/sec:    185.20KB
```

### Metric Definitions
- **Latency (Avg/Max)**: Time required for the server to process and return requests.
- **Req/Sec**: Average requests handled per thread per second.
- **Requests/sec (559.20)**: Aggregate throughput achieved across all threads.
- **Transfer/sec**: Network throughput bandwidth.

---

## ⚙️ Tuning Threads, Connections, and Duration <a id="tuning-threads-connections-and-duration"></a>

- **`-t, --threads`**: Number of worker threads. Set to the number of physical or logical CPU cores on your machine (e.g. `-t4` or `-t8`).
- **`-c, --connections`**: Total number of concurrent HTTP connections maintained across all threads.
- **`-d, --duration`**: Duration of the test (e.g. `10s`, `2m`, `1h`).
- **`--timeout`**: Socket timeout duration (e.g. `2s`).

```bash
# 8 worker threads, 400 concurrent connections, 30-second duration
wrk -t8 -c400 -d30s --timeout 2s http://localhost:8080/api/v1/health
```

> [!TIP]
> A common rule of thumb: set `--threads` to the number of physical CPU cores on your client machine, and `--connections` to the desired level of concurrency (connections are divided equally among threads).

---

## 📊 Detailed Latency Distribution (`--latency`) <a id="detailed-latency-distribution-latency"></a>

By default, `wrk` only outputs average and standard deviation. Adding `--latency` outputs the complete percentile distribution (50th, 75th, 90th, 99th, 99.999th percentiles):

```bash
wrk -t4 -c100 -d20s --latency http://localhost:3000/
```

### Detailed Percentile Output:
```text
  Latency Distribution
     50%    4.12ms
     75%    6.85ms
     90%   12.40ms
     99%   28.50ms
```
This is essential for detecting long-tail latency spikes (p99/p99.9).

---

## 🏷️ Adding Custom Headers & HTTP Payloads <a id="adding-custom-headers-http-payloads"></a>

Pass custom HTTP request headers with `-H`:

```bash
# Benchmark with JSON Content-Type and Bearer Authentication
wrk -t4 -c50 -d15s \
  -H "Authorization: Bearer test_token_xyz" \
  -H "Content-Type: application/json" \
  -H "Accept-Encoding: gzip" \
  http://localhost:8080/secure/data
```

---

## 📜 Scripting Complex Workflows with Lua <a id="scripting-complex-workflows-with-lua"></a>

`wrk` embeds LuaJIT to allow dynamic request payloads, custom HTTP methods (POST, PUT), randomized query params, and custom response validation.

### 1. HTTP POST Request with JSON Body

Create `post.lua`:

```lua
-- post.lua: Send JSON payload in HTTP POST
wrk.method = "POST"
wrk.headers["Content-Type"] = "application/json"
wrk.body = '{"user_id": 1024, "action": "benchmark_event"}'
```

Execute `wrk` with the script:

```bash
wrk -t4 -c50 -d10s -s post.lua http://localhost:8080/api/events
```

### 2. Dynamic Unique Requests per Worker

Create `dynamic.lua`:

```lua
-- dynamic.lua: Generate randomized request paths
request = function()
    local id = math.random(1, 100000)
    local path = "/items/" .. id
    return wrk.format("GET", path)
end
```

Run:
```bash
wrk -t4 -c100 -d30s -s dynamic.lua http://localhost:8080
```

---

## 💡 Benchmarking Best Practices <a id="benchmarking-best-practices"></a>

1. **Never Run Over Loaded WiFi**: Measure against `localhost` or over hardwired Gigabit/10G Ethernet to eliminate wireless jitter.
2. **Warm Up the Server**: Execute a brief 5-second test before the official run to allow JIT compilers, database connection pools, and caches to warm up.
3. **Monitor Client CPU**: If the machine running `wrk` reaches 100% CPU utilization, you are benchmarking your client, not your server.
4. **Raise File Descriptor Limits**:
   ```bash
   # Avoid 'Too many open files' socket errors
   ulimit -n 65535
   ```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases <a id="everyday-cheat-sheet-useful-shell-aliases"></a>

```bash
# Quick 10s health benchmark
alias wrk-fast='wrk -t4 -c50 -d10s --latency'

# Heavy 1-minute load test
alias wrk-heavy='wrk -t8 -c500 -d60s --latency'
```

### Quick Reference

| Task | Command |
| :--- | :--- |
| **Quick Benchmark** | `wrk -t2 -c20 -d10s http://localhost:8080/` |
| **Detailed Latencies** | `wrk -t4 -c100 -d30s --latency http://localhost:8080/` |
| **Custom Headers** | `wrk -t2 -c10 -d10s -H "Auth: key" http://localhost/` |
| **POST Request with Lua** | `wrk -t4 -c50 -d15s -s post.lua http://localhost/` |
| **Long Endurance Test** | `wrk -t8 -c200 -d10m http://localhost/` |

---

## 🗑️ Uninstallation <a id="uninstallation"></a>

```bash
brew uninstall wrk
```
