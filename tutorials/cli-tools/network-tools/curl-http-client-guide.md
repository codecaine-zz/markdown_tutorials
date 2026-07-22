# cURL HTTP & API Client Guide

`curl` (Client URL) is the universal, industry-standard command-line tool for transferring data over protocols such as HTTP, HTTPS, FTP, SFTP, and SMTP. It is widely used by developers and sysadmins for API testing, file downloads, web scraping, and automation scripts.

---

## 📚 Table of Contents

1. [Installation via Homebrew](#installation-via-homebrew)
2. [Essential Copy & Paste One-Liners](#essential-copy--paste-one-liners)
3. [HTTP Methods & JSON Payloads](#http-methods--json-payloads)
4. [Headers, Authentication & Cookies](#headers-authentication--cookies)
5. [File Uploads & Multipart Form Data](#file-uploads--multipart-form-data)
6. [Response Handling, Inspection & Performance Timing](#response-handling-inspection--performance-timing)
7. [SSL/TLS, Proxies & Speed Control](#ssltls-proxies--speed-control)
8. [Timeout & Retry Mechanisms](#timeout--retry-mechanisms)
9. [Automation Scripts & Workflows](#automation-scripts--workflows)
10. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## ⚙️ Installation via Homebrew

```bash
# Install curl via Homebrew on macOS
brew install curl

# Verify installation and supported protocols
curl --version
```

---

## 🚀 Essential Copy & Paste One-Liners

### 1. Download File with Remote Filename
```bash
# Download and save using the remote filename (-O)
curl -O https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso

# Follow redirects (-L) and save using remote filename
curl -LO https://github.com/cli/cli/releases/latest/download/gh_2.40.0_macOS_amd64.zip
```

### 2. Save Output to Specific Local File Name
```bash
# Save to custom filename (-o)
curl -o my-document.pdf https://example.com/files/report_v2_final.pdf
```

### 3. Silent Execution with Error Reporting
```bash
# Silent mode (-s) but show error messages if request fails (-S)
curl -sS https://httpbin.org/get -o output.json
```

### 4. Resume an Interrupted Download
```bash
# Resume download at byte offset where it left off (-C -)
curl -C - -O https://example.com/large-archive.zip
```

---

## 🌐 HTTP Methods & JSON Payloads

### 1. GET Requests with Query Parameters
```bash
# Basic GET request
curl https://httpbin.org/get

# GET request with query parameters (URL encoded)
curl -G --data-urlencode "query=curl tutorial" --data-urlencode "page=1" https://httpbin.org/get
```

### 2. POST Request with JSON Data
```bash
# Inline JSON payload with Content-Type header
curl -X POST https://httpbin.org/post \
  -H "Content-Type: application/json" \
  -d '{"name": "Alice", "role": "developer", "active": true}'

# POST JSON from a local file
curl -X POST https://httpbin.org/post \
  -H "Content-Type: application/json" \
  -d @payload.json
```

### 3. POST JSON using Heredoc in Shell Scripts
```bash
curl -X POST https://httpbin.org/post \
  -H "Content-Type: application/json" \
  -d @- << 'EOF'
{
  "user": "john_doe",
  "email": "john@example.com",
  "tags": ["admin", "developer"]
}
EOF
```

### 4. PUT, PATCH & DELETE Requests
```bash
# PUT request to update resource
curl -X PUT https://httpbin.org/put \
  -H "Content-Type: application/json" \
  -d '{"id": 42, "status": "active"}'

# PATCH request to modify field
curl -X PATCH https://httpbin.org/patch \
  -H "Content-Type: application/json" \
  -d '{"status": "archived"}'

# DELETE request
curl -X DELETE https://httpbin.org/delete
```

---

## 🔒 Headers, Authentication & Cookies

### 1. Custom HTTP Headers & User-Agents
```bash
# Send custom headers (e.g. API keys or User-Agent spoofing)
curl https://httpbin.org/headers \
  -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)" \
  -H "X-Custom-Header: my-app-value"
```

### 2. Authentication Methods
```bash
# Bearer Token (JWT / OAuth2)
curl https://api.example.com/v1/user \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN_HERE"

# Basic Authentication (username:password)
curl -u "admin:secret123" https://httpbin.org/basic-auth/admin/secret123

# Custom API Key Header
curl https://api.example.com/data \
  -H "X-API-Key: secret_api_key_xyz987"
```

### 3. Cookies & Session Persistence
```bash
# Save response cookies to file (-c / --cookie-jar)
curl -c cookies.txt -d "username=admin&password=secret" https://example.com/login

# Send saved cookies in subsequent request (-b / --cookie)
curl -b cookies.txt https://example.com/dashboard
```

---

## 📤 File Uploads & Multipart Form Data

### 1. Upload File with Form Fields (`-F`)
```bash
# Upload single file with additional form metadata
curl -X POST https://httpbin.org/post \
  -F "file=@/path/to/document.pdf" \
  -F "description=Monthly financial report" \
  -F "category=finance"
```

### 2. Upload File with Custom MIME Type & Filename
```bash
# Override filename and mime-type sent to server
curl -X POST https://httpbin.org/post \
  -F "file=@local_image.png;filename=avatar.png;type=image/png"
```

---

## 📊 Response Handling, Inspection & Performance Timing

### 1. Extract HTTP Status Code Only
```bash
# Print HTTP status code (e.g. 200, 404, 500)
curl -s -o /dev/null -w "%{http_code}\n" https://httpbin.org/status/200
```

### 2. Inspect Response Headers Only
```bash
# Fetch headers only without body (-I)
curl -sI https://httpbin.org/get

# Dump response headers to text file (-D)
curl -s -D headers.txt https://httpbin.org/get -o body.json
```

### 3. Pretty Print JSON with `jq`
```bash
# Pipe response directly to jq
curl -s https://httpbin.org/json | jq .
```

### 4. Measure Detailed Connection Performance Metrics
```bash
# Formatted execution timing breakdown
curl -s -w "\n--- Performance Metrics ---\nDNS Lookup:       %{time_namelookup}s\nConnect Time:     %{time_connect}s\nTLS Handshake:    %{time_appconnect}s\nTTFB:             %{time_starttransfer}s\nTotal Time:       %{time_total}s\n" \
  -o /dev/null https://httpbin.org/get
```

---

## 🛡️ SSL/TLS, Proxies & Speed Control

### 1. Ignore SSL Certificate Errors (Insecure Mode)
```bash
# Bypass self-signed or invalid SSL certificate warnings (-k / --insecure)
curl -k https://self-signed.internal-network.local
```

### 2. Route Requests Through Proxy (HTTP / SOCKS5)
```bash
# Route through HTTP proxy
curl -x http://proxy.example.com:8080 https://httpbin.org/ip

# Route through SOCKS5 proxy (DNS resolved via proxy)
curl -x socks5h://127.0.0.1:1080 https://httpbin.org/ip
```

### 3. Throttle Download Bandwidth Speed
```bash
# Limit download speed to 500 KB/s
curl --limit-rate 500k -O https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso
```

---

## ⏱️ Timeout & Retry Mechanisms

```bash
# Set connection timeout (5 seconds) and total timeout (15 seconds)
curl --connect-timeout 5 --max-time 15 https://httpbin.org/delay/2

# Retry failed requests up to 3 times with exponential backoff delay
curl --retry 3 --retry-delay 2 --retry-max-time 30 https://httpbin.org/status/503
```

---

## 🛠️ Automation Scripts & Workflows

### 1. API Token Exchange & Data Retrieval Script
```bash
#!/bin/bash
set -euo pipefail

# 1. Fetch Bearer token from OAuth endpoint
TOKEN=$(curl -s -X POST https://httpbin.org/post \
  -H "Content-Type: application/json" \
  -d '{"client_id":"app123","client_secret":"secret456"}' | jq -r '.json.client_id')

echo "Acquired Token: $TOKEN"

# 2. Use token in authenticated API call
curl -s -H "Authorization: Bearer $TOKEN" https://httpbin.org/get | jq .
```

### 2. Website Health Check & Alert Script
```bash
#!/bin/bash
TARGET_URL="https://httpbin.org/status/200"
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 "$TARGET_URL")

if [ "$HTTP_STATUS" -eq 200 ]; then
  echo "✅ [$(date)] $TARGET_URL is UP (Status: 200)"
else
  echo "❌ [$(date)] $TARGET_URL is DOWN! Status code: $HTTP_STATUS"
fi
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Download file with remote name | `curl -LO "URL"` |
| Resume broken download | `curl -C - -O "URL"` |
| POST JSON payload | `curl -X POST -H "Content-Type: application/json" -d '{"k":"v"}' "URL"` |
| Bearer token request | `curl -H "Authorization: Bearer TOKEN" "URL"` |
| Upload file via multipart | `curl -F "file=@path/to/file.png" "URL"` |
| Save response cookies | `curl -c cookies.txt "URL"` |
| Output status code only | `curl -s -o /dev/null -w "%{http_code}\n" "URL"` |
| Pretty print JSON response | `curl -s "URL" \| jq .` |
| Bypass SSL warnings | `curl -k "URL"` |
| Retry on failure | `curl --retry 3 --retry-delay 2 "URL"` |
