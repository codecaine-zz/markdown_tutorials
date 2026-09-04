# Curlie (Curl + HTTPie) Modern API Client Guide

`curlie` is a modern, developer-friendly frontend for `curl` written in Go. It blends the intuitive, human-friendly syntax and beautiful syntax-highlighted output of **HTTPie** with the unrivaled speed, reliability, and full feature suite of **cURL**.

---

## 📚 Table of Contents

1. [Overview & `curl` vs `httpie` vs `curlie`](#overview--curl-vs-httpie-vs-curlie)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Basic HTTP Requests (GET, POST, PUT, DELETE)](#basic-http-requests-get-post-put-delete)
4. [Request Headers & Authentication](#request-headers--authentication)
5. [Sending JSON Payloads & Form Data](#sending-json-payloads--form-data)
6. [Combining `curl` Flags with `curlie`](#combining-curl-flags-with-curlie)
7. [Output Formatting & Silent Flags](#output-formatting--silent-flags)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `curl` vs `httpie` vs `curlie`

While `httpie` has a great user experience, it is written in Python (slower startup time) and lacks many advanced network features. `curl` has all features, but its syntax for JSON and headers is cumbersome. `curlie` provides the best of both worlds.

| Feature | `curl` | `httpie` | `curlie` |
| :--- | :--- | :--- | :--- |
| **Engine** | libcurl (C) | Python Requests | Go + system `curl` |
| **Startup Speed** | Instant | Noticeable lag | Instant |
| **JSON Syntax** | Verbose (`-H "Content-Type..." -d '{...}'`) | Simple (`key=value`) | Simple (`key=value`) |
| **cURL Compatibility** | 100% | 0% (incompatible flags) | 100% (supports all curl flags) |
| **Colorized JSON Output** | Requires `jq` piping | Built-in | Built-in |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install curlie
```

### 2. Install via Go

```bash
go install github.com/rs/curlie@latest
```

### 3. Verify Installation

```bash
curlie --version
```

---

## 🚀 Basic HTTP Requests

`curlie` automatically formats the response headers and pretty-prints JSON bodies:

### 1. Simple GET Request

```bash
# Simple GET request
curlie httpbin.org/get

# Explicit GET with query parameters
curlie GET httpbin.org/get page==1 search=="modern cli"
```

### 2. Simple POST Request

```bash
curlie POST httpbin.org/post name="Antigravity" status="active"
```

### 3. PUT / PATCH / DELETE Requests

```bash
# PUT request
curlie PUT httpbin.org/put id:=101 title="Updated Tutorial"

# DELETE request
curlie DELETE httpbin.org/delete
```

---

## 🔑 Request Headers & Authentication

### 1. Adding Custom Headers (`Header:Value`)

```bash
curlie httpbin.org/headers Authorization:"Bearer my_api_token" Accept:"application/json"
```

### 2. Basic Authentication (`-a` / `--auth`)

```bash
curlie -a username:password httpbin.org/basic-auth/username/password
```

---

## 📦 Sending JSON Payloads & Form Data

### 1. JSON Types Syntax

- String fields: `key=value`
- Raw types (Booleans, Integers, Floats, Objects): `key:=value`

```bash
curlie POST api.example.com/users \
  name="Jane Doe" \
  age:=28 \
  is_admin:=false \
  tags:='["developer", "macos"]'
```

### 2. URL-Encoded Form Data (`-f` / `--form`)

```bash
curlie -f POST httpbin.org/post username="johndoe" password="secretpassword"
```

---

## 🛠️ Combining `curl` Flags with `curlie`

Because `curlie` is a wrapper around `curl`, any flag supported by `curl` can be passed directly:

```bash
# Follow redirects (-L)
curlie -L github.com

# Ignore SSL certificate errors (-k)
curlie -k https://localhost:8443/api

# Specify custom proxy (--proxy)
curlie --proxy socks5://127.0.0.1:9050 httpbin.org/ip

# Rate-limit download speed (--limit-rate)
curlie --limit-rate 500k https://example.com/large-file.zip
```

---

## 🎨 Output Formatting & Silent Flags

Control terminal output formatting, pretty-printing, and silent modes:

```bash
# Pretty-print JSON responses automatically (default behavior)
curlie https://api.github.com/users/octocat

# Silent mode: hide progress and non-essential output (-s)
curlie -s https://api.github.com/users/octocat

# Print only response headers (-i / --include)
curlie -i https://httpbin.org/get

# Print response status and headers without body (-I / --head)
curlie -I https://httpbin.org/get
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **GET Request** | `curlie <url>` |
| **GET with Query Params** | `curlie <url> param==value` |
| **POST JSON Data** | `curlie POST <url> field=val num:=42` |
| **POST Form Data** | `curlie -f POST <url> user=name pass=123` |
| **Add Header** | `curlie <url> Header:Value` |
| **Bearer Token** | `curlie <url> Authorization:"Bearer <token>"` |
| **Follow Redirects** | `curlie -L <url>` |
| **Headers Only** | `curlie -I <url>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Aliasing curlie
alias http='curlie'
alias get='curlie GET'
alias post='curlie POST'
```

---

## 🗑️ Uninstallation

To remove `curlie`:

```bash
brew uninstall curlie
```
