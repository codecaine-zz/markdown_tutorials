# HTTPie API Testing & HTTP Client Guide

`httpie` (command: `http` or `https`) is a human-friendly command-line HTTP client. Designed for effortless API testing and debugging, it features expressive syntax, automatic JSON colorization, syntax highlighting, session management, and simple file uploads.

---

## 📚 Table of Contents

1. [Overview & Features](#overview--features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [GET Requests & Query Parameters](#get-requests--query-parameters)
4. [POST, PUT & JSON Payloads](#post-put--json-payloads)
5. [Headers, Bearer Tokens & Basic Auth](#headers-bearer-tokens--basic-auth)
6. [Form Submissions & File Uploads](#form-submissions--file-uploads)
7. [Sessions & Cookie Persistence](#sessions--cookie-persistence)
8. [File Downloads & Response Filtering](#file-downloads--response-filtering)
9. [Offline Preview & TLS Options](#offline-preview--tls-options)
10. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **Intuitive Syntax**: Simple key-value notation replaces cumbersome `--header` and `-d` flags.
- **Default JSON**: Requests automatically set `Content-Type: application/json` and parse responses.
- **Colorized Formatting**: Auto-formats and color-highlights headers and JSON bodies.
- **Session Support**: Remembers headers and cookies across multiple CLI invocations.

---

## ⚙️ Installation via Homebrew

```bash
# Install HTTPie via Homebrew on macOS
brew install httpie

# Verify installation
http --version
```

---

## 🚀 GET Requests & Query Parameters

### 1. Simple GET Request
```bash
# Send GET request (GET is default method)
http pie.dev/get
```

### 2. Add URL Query Parameters (`==`)
Use double equals (`==`) to append URL query parameters automatically.

```bash
# Sends: GET https://pie.dev/get?search=cli+tools&page=2
http GET pie.dev/get search=="cli tools" page==2
```

---

## 🌐 POST, PUT & JSON Payloads

`httpie` sends JSON by default when key-value arguments are supplied.

### 1. Send JSON Payload (`=` for string, `:=` for raw JSON types)
```bash
# Send JSON object with strings, numbers, booleans, and arrays
http POST pie.dev/post \
  name="Alice" \
  role="developer" \
  age:=30 \
  active:=true \
  skills:='["rust", "python", "go"]'
```

### 2. Send JSON Payload from Local File or Stdin
```bash
# Pipe JSON file into httpie
http POST pie.dev/post < payload.json

# Pipe output from jq or cat
cat data.json | http POST pie.dev/post
```

### 3. PUT & PATCH Requests
```bash
# PUT request
http PUT pie.dev/put id:=42 status="updated"

# PATCH request
http PATCH pie.dev/patch status="archived"
```

---

## 🔒 Headers, Bearer Tokens & Basic Auth

### 1. Custom HTTP Headers (`Header:Value`)
```bash
# Pass custom headers
http GET pie.dev/headers \
  "User-Agent: MyApp/2.0" \
  "X-API-Key: secret_key_123"
```

### 2. Authentication Shortcuts (`-a` / `--auth`)
```bash
# Bearer Token Authentication (-A bearer -a TOKEN)
http GET pie.dev/headers -A bearer -a YOUR_ACCESS_TOKEN_HERE

# Basic Authentication (-a user:password)
http GET pie.dev/basic-auth/admin/secret123 -a admin:secret123
```

---

## 📤 Form Submissions & File Uploads

### 1. Submit URL-Encoded Web Form Data (`-f` / `--form`)
```bash
# Sends Content-Type: application/x-www-form-urlencoded
http -f POST pie.dev/post username="john_doe" password="secretpassword"
```

### 2. Upload File with Form Fields (`field@/path/to/file`)
```bash
# Upload document file along with text form fields
http -f POST pie.dev/post \
  title="Project Proposal" \
  document@~/Documents/proposal.pdf
```

---

## 💾 Sessions & Cookie Persistence

Maintain persistent login state, headers, and cookies across multiple commands without re-authenticating.

```bash
# 1. Login and save session state into 'admin_session'
http --session=admin_session -f POST https://example.com/login username="admin" password="secret"

# 2. Re-use session for subsequent requests (cookies & headers automatically attached)
http --session=admin_session https://example.com/dashboard

# 3. View saved session details
http --session=admin_session https://example.com/api/user-profile
```

---

## 📥 File Downloads & Response Filtering

### 1. Download File with Progress Bar (`-d` / `--download`)
```bash
# Download and automatically name file from Content-Disposition header
http --download https://pie.dev/image/png

# Download and save to explicit output path (-o)
http --download https://pie.dev/image/png -o my_logo.png
```

### 2. Filter Output Components (`-h` headers, `-b` body, `-v` verbose)
```bash
# Show HTTP headers only (-h)
http -h pie.dev/get

# Show response body only (-b)
http -b pie.dev/get

# Show full request AND response headers + body (-v / --verbose)
http -v POST pie.dev/post name="Alice"
```

---

## 🧪 Offline Preview & TLS Options

### 1. Offline Request Preview (`--offline`)
Construct and inspect the outgoing HTTP request without sending it over the network.

```bash
# Print HTTP request structure without executing network call
http --offline POST pie.dev/post name="Alice" "Authorization: Bearer 123"
```

### 2. Bypass SSL Certificate Verification (`--verify=no`)
```bash
# Bypass self-signed TLS/SSL certificate warnings
http --verify=no https://self-signed.local
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Simple GET | `http pie.dev/get` |
| GET with query params | `http GET pie.dev/get search==cli page==1` |
| POST JSON payload | `http POST pie.dev/post name="Alice" age:=30 active:=true` |
| Bearer token auth | `http GET pie.dev/headers -A bearer -a TOKEN` |
| Basic auth | `http GET pie.dev/auth -a user:pass` |
| URL-encoded form POST | `http -f POST pie.dev/post user="john" pass="secret"` |
| Upload file via form | `http -f POST pie.dev/post file@/path/to/doc.pdf` |
| Download file | `http --download "URL" -o file.ext` |
| Print headers only | `http -h "URL"` |
| Persistent session | `http --session=mysession "URL"` |
| Offline request preview | `http --offline POST "URL" name="Alice"` |