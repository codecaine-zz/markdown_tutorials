# HTTPStat (cURL Latency Visualizer) Complete Guide

`httpstat` is an elegant, visual command-line tool that visualizes `curl` statistics into a clean, human-readable ASCII waterfall diagram. Written in Python and Go, `httpstat` breaks down standard HTTP/HTTPS request timing phases—including DNS lookup, TCP handshake, TLS negotiation, server processing (TTFB), and content transfer—making network latency bottlenecks instantly identifiable.

---

## 📚 Table of Contents

1. [Overview & Key Features](#overview-key-features)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Usage & Output Waterfall](#basic-usage-output-waterfall)
4. [Passing Standard cURL Options](#passing-standard-curl-options)
5. [Customizing Output with Environment Variables](#customizing-output-with-environment-variables)
6. [Analyzing POST Requests & JSON Payloads](#analyzing-post-requests-json-payloads)
7. [Advanced Troubleshooting & Performance Analysis](#advanced-troubleshooting-performance-analysis)
8. [Shell Integration & Everyday Aliases](#shell-integration-everyday-aliases)
9. [Quick Reference Cheat Sheet](#quick-reference-cheat-sheet)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Key Features

When diagnosing slow API endpoints or web applications, standard `curl -w` timing variables require complex formatting strings. `httpstat` acts as an intelligent wrapper around `curl`, executing the request and parsing the connection telemetry into an intuitive timeline diagram.

- **Zero Configuration**: Works out of the box with any HTTP/HTTPS URL.
- **cURL Passthrough**: Seamlessly accepts any standard `curl` flag (`-X`, `-H`, `-d`, `-k`, `-L`, `--resolve`).
- **Timing Breakdown**:
  - `DNS Lookup`: Time spent resolving domain name to an IP address.
  - `TCP Connection`: Time required to establish the 3-way TCP handshake.
  - `TLS Handshake`: Time consumed by SSL/TLS certificate validation and key exchange.
  - `Server Processing`: Time until the first byte arrives (TTFB / Time to First Byte).
  - `Content Transfer`: Duration of downloading the response body.
- **Header Inspection**: Displays remote status codes and HTTP headers in clean colorized blocks.

---

## ⚙️ Homebrew Installation & Verification

### 1. Install `httpstat` via Homebrew

```bash
brew install httpstat
```

### 2. Verify Installation

```bash
which httpstat
httpstat --version
```

Output:
```text
/opt/homebrew/bin/httpstat
httpstat 1.3.2
```

---

## 🚀 Basic Usage & Output Waterfall

Run `httpstat` followed by any URL:

```bash
httpstat https://httpbin.org/get
```

### Visual Waterfall Output

```text
HTTP/2 200 
server: gunicorn/19.9.0
date: Sat, 12 Sep 2026 22:15:00 GMT
content-type: application/json
content-length: 308
access-control-allow-origin: *
access-control-allow-credentials: true

Body stored in: /var/folders/5x/t2_0j0/T/tmp7x7w8q2z

  DNS Lookup   TCP Connection   TLS Handshake   Server Processing   Content Transfer
[   28ms     |     32ms       |     64ms      |       85ms        |       2ms        ]
             |                |               |                   |                  |
    namelookup:28ms           |               |                   |                  |
                        connect:60ms          |                   |                  |
                                    pretransfer:124ms             |                  |
                                                      starttransfer:209ms            |
                                                                                 total:211ms
```

### Reading the Timeline

- **namelookup (28ms)**: Time from start until DNS resolution completed.
- **connect (60ms)**: Cumulative time to complete TCP handshake (`28ms + 32ms`).
- **pretransfer (124ms)**: Cumulative time after TLS negotiation concluded (`60ms + 64ms`).
- **starttransfer (209ms)**: When the server delivered the first byte (TTFB).
- **total (211ms)**: Total round-trip time for the entire request.

---

## 🛠️ Passing Standard cURL Options

Any argument after the URL is passed directly to the underlying `curl` binary:

### 1. Custom HTTP Headers

```bash
httpstat https://api.github.com/user -H "Authorization: Bearer YOUR_TOKEN" -H "Accept: application/vnd.github+json"
```

### 2. Following Redirects (`-L`)

By default, `httpstat` shows the final response. Use `-L` to trace HTTP redirects:

```bash
httpstat https://github.com -L
```

### 3. Custom HTTP Methods & Verbose Mode

```bash
# HEAD request
httpstat https://example.com -I

# Insecure TLS (ignore certificate verification)
httpstat https://self-signed.local -k
```

---

## ⚙️ Customizing Output with Environment Variables

`httpstat` supports environment variables to adjust display behavior:

| Variable | Values | Default | Description |
| :--- | :--- | :--- | :--- |
| `HTTPSTAT_SHOW_BODY` | `true` / `false` | `false` | Print response body to stdout instead of temporary file |
| `HTTPSTAT_SHOW_IP` | `true` / `false` | `true` | Display local and remote IP addresses and ports |
| `HTTPSTAT_SHOW_SPEED` | `true` / `false` | `false` | Display download and upload speeds in visual chart |
| `HTTPSTAT_SAVE_BODY` | `true` / `false` | `true` | Save body to temporary file |
| `HTTPSTAT_CURL_BIN` | path | `curl` | Specify custom path to `curl` binary |

### Examples

```bash
# Print response body directly to terminal
HTTPSTAT_SHOW_BODY=true httpstat https://httpbin.org/ip

# Show download transfer speed in KB/s or MB/s
HTTPSTAT_SHOW_SPEED=true httpstat https://speed.cloudflare.com/__down?bytes=10000000

# Hide remote IP address details
HTTPSTAT_SHOW_IP=false httpstat https://example.com
```

---

## 📦 Analyzing POST Requests & JSON Payloads

Test REST API endpoints with POST payloads:

```bash
# Send JSON data
httpstat https://httpbin.org/post \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"service": "auth", "status": "active"}'
```

---

## 🔬 Advanced Troubleshooting & Performance Analysis

### 1. Pinpointing Slow DNS

If `namelookup` is high (> 100ms):
- Check your local DNS resolver (`/etc/resolv.conf`).
- Compare against public DNS (e.g. `1.1.1.1` or `8.8.8.8`).
- Bypass DNS entirely using `curl`'s `--resolve` flag:
  ```bash
  httpstat https://example.com --resolve example.com:443:93.184.216.34
  ```

### 2. Diagnosing Heavy TLS Handshakes

If `TLS Handshake` takes several hundred milliseconds:
- The server might have complex certificate chains, slow cipher suites, or lack TLS 1.3 / Session Resumption.
- Test forcing TLS 1.3:
  ```bash
  httpstat https://example.com --tlsv1.3
  ```

### 3. Server Processing (TTFB) Bottlenecks

If `Server Processing` dominates the timeline:
- The backend application code, database queries, or server-side rendering is the primary bottleneck, not network transmission.

---

## 🐚 Shell Integration & Everyday Aliases

Add these productivity shortcuts to your `~/.zshrc` or `~/.bashrc`:

```bash
# Quick latency check with download speed
alias hstat='HTTPSTAT_SHOW_SPEED=true httpstat'

# Show full body and speed
alias hstat-v='HTTPSTAT_SHOW_BODY=true HTTPSTAT_SHOW_SPEED=true httpstat'

# Follow redirects by default
alias hstat-l='httpstat -L'
```

---

## 📋 Quick Reference Cheat Sheet

| Task | Command |
| :--- | :--- |
| **Inspect URL Latency** | `httpstat https://example.com` |
| **Follow HTTP Redirects** | `httpstat https://example.com -L` |
| **Include Request Headers** | `httpstat https://api.dev.com -H "Authorization: Bearer key"` |
| **Send JSON POST** | `httpstat https://api.dev.com/data -X POST -d '{"id": 1}'` |
| **Bypass DNS Cache** | `httpstat https://example.com --resolve example.com:443:IP` |
| **Print Body to Terminal** | `HTTPSTAT_SHOW_BODY=true httpstat https://example.com` |
| **Display Transfer Speeds** | `HTTPSTAT_SHOW_SPEED=true httpstat https://example.com` |

---

## 🗑️ Uninstallation

```bash
brew uninstall httpstat
```
