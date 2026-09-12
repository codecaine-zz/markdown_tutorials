# xh: Fast, Ultra-Friendly HTTP Client in Rust

`xh` is a friendly, blazing-fast command-line HTTP client written in Rust. It adopts HTTPie's intuitive syntax design while delivering **up to 10x faster execution speeds**, lower memory consumption, native HTTP/2 and HTTP/3 support, automatic syntax highlighting, and instantaneous `curl` command translation.

---

## 📚 Table of Contents

1. [Overview & Features](#overview-features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [GET Requests & Query Parameters](#get-requests-query-parameters)
4. [POST, PUT & JSON Payloads](#post-put-json-payloads)
5. [Headers, Bearer Tokens & Basic Auth](#headers-bearer-tokens-basic-auth)
6. [Form Submissions & File Uploads](#form-submissions-file-uploads)
7. [Translating `xh` to `curl` Commands](#translating-xh-to-curl-commands)
8. [Downloading Files (`wget` mode)](#downloading-files-wget-mode)
9. [Sessions, Proxies & TLS Options](#sessions-proxies-tls-options)
10. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **10x Execution Speed**: Native Rust binary for near-instant startup vs. Python-based HTTPie.
- **Expressive Syntax**: Simple key-value syntax for headers (`H:V`), query params (`q==v`), and JSON (`k=v` or `k:=v`).
- **Curl Translator (`--curl`)**: Print equivalent `curl` commands with `--curl`.
- **Modern Networking**: Native HTTP/2, HTTP/3, and SOCKS5 proxy support.

---

## ⚙️ Installation via Homebrew

```bash
# Install xh via Homebrew on macOS
brew install xh

# Verify installation
xh --version
```

---

## 🚀 GET Requests & Query Parameters

### 1. Send Simple GET Request
```bash
# GET is default method when no data items are supplied
xh https://httpbin.org/get
```

### 2. Append URL Query Parameters (`==`)
Use double equals (`==`) to append URL query parameters automatically.

```bash
# Sends: GET https://httpbin.org/get?search=rust+cli&page=1
xh GET https://httpbin.org/get search=="rust cli" page==1
```

---

## 🌐 POST, PUT & JSON Payloads

`xh` automatically constructs JSON payloads when key-value arguments are supplied.

### 1. Send Typed JSON Payloads (`=` string, `:=` raw JSON/numbers/booleans)
```bash
# Send JSON payload with string, integer, boolean, and array values
xh POST https://httpbin.org/post \
  name="Alice" \
  role="developer" \
  age:=30 \
  active:=true \
  languages:='["rust", "go", "python"]'
```

### 2. Pipe JSON Input into `xh`
```bash
# Pipe local JSON file into request body
xh POST https://httpbin.org/post < payload.json

# Pipe output from jq or cat
cat data.json | xh POST https://httpbin.org/post
```

### 3. PUT & PATCH Requests
```bash
# Send PUT request
xh PUT https://httpbin.org/put id:=42 status="updated"

# Send PATCH request
xh PATCH https://httpbin.org/patch status="archived"
```

---

## 🔒 Headers, Bearer Tokens & Basic Auth

### 1. Custom HTTP Headers (`Header:Value`)
```bash
# Pass custom headers
xh https://httpbin.org/headers \
  "User-Agent: MyApp/1.0" \
  "X-API-Key: secret_token_999"
```

### 2. Authentication Shortcuts (`-a` / `-A`)
```bash
# Bearer Token Authentication (-A bearer -a TOKEN)
xh https://httpbin.org/headers -A bearer -a YOUR_ACCESS_TOKEN_HERE

# Basic Authentication (-a user:pass)
xh -a admin:password123 https://httpbin.org/basic-auth/admin/password123
```

---

## 📤 Form Submissions & File Uploads

### 1. URL-Encoded Form Submission (`-f` / `--form`)
```bash
# Sends Content-Type: application/x-www-form-urlencoded
xh -f POST https://httpbin.org/post username="john_doe" password="secretpassword"
```

### 2. Upload Files with Form Fields (`field@/path/to/file`)
```bash
# Upload document file along with text form metadata
xh -f POST https://httpbin.org/post \
  title="Monthly Financial Report" \
  document@~/Documents/report.pdf
```

---

## 🔄 Translating `xh` to `curl` Commands

Pass `--curl` to output the exact, equivalent `curl` command without executing the request.

```bash
# Generate equivalent curl command
xh POST https://httpbin.org/post name="Alice" age:=30 -A bearer -a MY_TOKEN --curl
```

**Output:**
```bash
curl -X POST --header "Content-Type: application/json" --header "Authorization: Bearer MY_TOKEN" --data '{"age":30,"name":"Alice"}' https://httpbin.org/post
```

---

## 📥 Downloading Files (`wget` mode)

### 1. Download File with Progress Bar (`-d` / `--download`)
```bash
# Download and save with remote filename from Content-Disposition header
xh -d https://httpbin.org/image/png

# Save download to custom output path (-o)
xh -d https://httpbin.org/image/png -o logo.png
```

### 2. Response Component Filtering (`-h` headers, `-b` body, `-v` verbose)
```bash
# Print response headers only (-h)
xh -h https://httpbin.org/get

# Print response body only (-b)
xh -b https://httpbin.org/get

# Print request AND response headers + body (-v / --verbose)
xh -v POST https://httpbin.org/post name="Alice"
```

---

## 🛡️ Sessions, Proxies & TLS Options

### 1. Persistent Sessions across CLI Invocations
```bash
# Save session state (cookies & headers) into 'user_session'
xh --session=user_session -f POST https://example.com/login user="admin" pass="secret"

# Use session in subsequent API requests
xh --session=user_session https://example.com/dashboard
```

### 2. Route Through HTTP / SOCKS5 Proxy & Bypass TLS Errors
```bash
# Route request through SOCKS5 proxy
xh --proxy=socks5://127.0.0.1:1080 https://httpbin.org/ip

# Ignore self-signed SSL certificate warnings
xh --insecure https://self-signed.local
```

### 3. Offline Request Inspection (`--offline`)
```bash
# Render outgoing request without making actual network connection
xh --offline POST https://httpbin.org/post name="Alice" "X-Custom: 123"
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Simple GET | `xh https://httpbin.org/get` |
| GET with query params | `xh GET https://httpbin.org/get search=="rust" page==1` |
| POST JSON payload | `xh POST https://httpbin.org/post name="Alice" age:=30 active:=true` |
| Bearer token auth | `xh https://httpbin.org/headers -A bearer -a TOKEN` |
| Basic auth | `xh -a user:pass https://httpbin.org/auth` |
| Form POST | `xh -f POST https://httpbin.org/post user="john" pass="secret"` |
| Upload file | `xh -f POST https://httpbin.org/post doc@~/report.pdf` |
| Translate to curl | `xh POST https://httpbin.org/post name="Alice" --curl` |
| Download file | `xh -d https://httpbin.org/image/png -o logo.png` |
| Output headers only | `xh -h https://httpbin.org/get` |
| Ignore SSL errors | `xh --insecure https://self-signed.local` |
