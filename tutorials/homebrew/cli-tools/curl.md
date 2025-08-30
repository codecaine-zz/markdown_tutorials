## Key Features Covered

**Basic Operations:**
- Simple GET/POST requests
- Header manipulation
- Response handling (status codes, headers, body)

**Advanced Features:**
- Authentication (Bearer tokens, OAuth)
- File uploads with metadata
- Timeout and retry mechanisms
- SSL/TLS debugging and verification

**Automation & Scripting:**
- API integration examples
- Health check monitoring
- Bulk operations
- Debugging with verbose output

## Useful Tips from the Guide

1. **Error Handling:** The monitoring script shows how to implement proper error checking with status codes
2. **Performance Metrics:** Using `--write-out` to capture timing information
3. **Debugging Tools:** `--trace-ascii` and `--verbose` for troubleshooting connections
4. **JSON Processing:** Integration with `jq` for parsing and extracting data

## Additional Recommendations

For even more advanced usage, consider:
- Using `.netrc` files for credential management
- Implementing connection pooling with `--keepalive-time`
- Using `--cookie-jar`/`--cookie` for session management
- Leveraging `--form-string` for form data without file uploads

The examples are practical and can be easily adapted to real-world scenarios. The guide strikes a great balance between explaining concepts clearly while providing actionable code snippets.

Would you like me to elaborate on any specific section or provide additional examples for particular use cases?
date: 2025-08-29 23:51:44

Curl is a powerful command-line tool for transferring data with URLs. This comprehensive tutorial covers everything from basic usage to advanced techniques.

## Table of Contents

1. [Basic Usage](#basic-usage)
2. [HTTP Methods](#http-methods)
3. [Request Headers](#request-headers)
4. [Authentication](#authentication)
5. [Cookies and Sessions](#cookies-and-sessions)
6. [File Uploads](#file-uploads)
7. [Response Handling](#response-handling)
8. [Timeout and Retry Options](#timeout-and-retry-options)
9. [Advanced Features](#advanced-features)
10. [Useful Examples](#useful-examples)

## Basic Usage

### Simple GET Request
```bash
# Basic GET request
curl https://httpbin.org/get

# With verbose output to see headers
curl -v https://httpbin.org/get

# Save response to file
curl -o response.json https://httpbin.org/json

# Silent mode (no progress bar)
curl -s https://httpbin.org/get
```

### Basic Response Analysis
```bash
# Show only HTTP status code
curl -s -o /dev/null -w "%{http_code}\n" https://httpbin.org/get

# Get response headers only
curl -sI https://httpbin.org/get

# Get content length from header
curl -sI -w "%{content_length}\n" https://httpbin.org/get
```

## HTTP Methods

### POST Requests
```bash
# Simple POST with form data
curl -X POST \
  -d "name=John&age=30" \
  https://httpbin.org/post

# POST with JSON data
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{"name":"John","age":30}' \
  https://httpbin.org/post

# POST with raw data from file
curl -X POST \
  -H "Content-Type: application/json" \
  --data-binary @data.json \
  https://httpbin.org/post
```

### PUT and DELETE Requests
```bash
# PUT request
curl -X PUT \
  -H "Content-Type: application/json" \
  -d '{"id":1,"name":"Updated Name"}' \
  https://httpbin.org/put

# DELETE request
curl -X DELETE https://httpbin.org/delete

# PATCH request
curl -X PATCH \
  -H "Content-Type: application/json" \
  -d '{"status":"updated"}' \
  https://httpbin.org/patch
```

## Request Headers

### Setting Custom Headers
```bash
# Set User-Agent header
curl -H "User-Agent: MyApp/1.0" https://httpbin.org/headers

# Set Accept header for JSON response
curl -H "Accept: application/json" https://httpbin.org/get

# Multiple headers
curl -H "Authorization: Bearer token123" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: abc123" \
  https://api.example.com/data

# Set custom header with special characters
curl -H "User-Agent: My App (Version 1.0; +http://example.com/bot)" https://httpbin.org/headers
```

### Header Manipulation
```bash
# Remove a default header (like Accept-Encoding)
curl --header "Accept-Encoding:" https://httpbin.org/get

# Add custom header for API key authentication
curl -H "X-API-Key: your-api-key-here" \
  https://api.example.com/endpoint

# Set referer header
curl -H "Referer: https://example.com" \
  https://httpbin.org/headers
```

## Authentication

### Basic Authentication
```bash
# Username and password in URL (not recommended for production)
curl http://user:password@https://httpbin.org/basic-auth/user/pass

# Using --user option
curl --user user:pass https://httpbin.org/basic-auth/user/pass

# With custom header
curl -H "Authorization: Basic dXNlcjpwYXNz" https://httpbin.org/basic-auth/user/pass
```

### Bearer Token Authentication
```bash
# Bearer token in header
curl -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  https://api.example.com/protected-endpoint

# Get token first, then use it
TOKEN=$(curl -s -u user:pass https://auth.example.com/token | jq -r '.access_token')
curl -H "Authorization: Bearer $TOKEN" https://api.example.com/endpoint
```

### OAuth Authentication
```bash
# OAuth 2.0 with client credentials flow
curl -X POST \
  -d "grant_type=client_credentials&client_id=my_client&client_secret=my_secret" \
  https://oauth.example.com/token

# Use access token in subsequent requests
curl -H "Authorization: Bearer ACCESS_TOKEN_HERE" \
  https://api.example.com/secure-endpoint
```

## Cookies and Sessions

### Cookie Handling
```bash
# Send cookie with request
curl -b "sessionid=abc123; user=john" https://httpbin.org/cookies

# Store cookies in file (for persistent session)
curl -c cookies.txt https://httpbin.org/cookies/set/sessionid/abc123

# Use stored cookies from file
curl -b cookies.txt https://httpbin.org/cookies

# Cookie jar mode (automatically store and use cookies)
curl --cookie-jar cookiejar.txt https://example.com/login

curl --cookie cookiejar.txt https://example.com/dashboard
```

### Session Management
```bash
# Start session and maintain cookies across requests
curl -c session_cookies.txt \
  -d "username=admin&password=secret" \
  https://example.com/login

# Use the same session for next request
curl -b session_cookies.txt https://example.com/protected-page

# Clear cookies from jar file
curl --cookie-jar "" https://example.com/
```

## File Uploads

### Form Data Uploads
```bash
# Simple form upload with multiple fields
curl -F "name=John" \
  -F "email=john@example.com" \
  -F "file=@document.pdf" \
  https://httpbin.org/post

# Upload file from local path
curl -F "upload_file=@/path/to/image.jpg" \
  https://httpbin.org/post

# Upload with custom field name
curl -F "avatar=@profile.png;type=image/png" \
  https://api.example.com/upload
```

### Multipart Form Data
```bash
# Complex multipart upload with different content types
curl -F "file=@data.csv;type=text/csv" \
  -F "description=Sales data" \
  -F "category=financial" \
  https://httpbin.org/post

# Upload multiple files
curl -F "files[]=@file1.txt" \
  -F "files[]=@file2.txt" \
  -F "metadata={\"upload_time\":\"$(date)\"}" \
  https://api.example.com/upload-multiple
```

## Response Handling

### Response Analysis
```bash
# Show HTTP status code only
curl -s -o /dev/null -w "%{http_code}\n" https://httpbin.org/get

# Get all response headers
curl -D headers.txt https://httpbin.org/get

# Get redirect location
curl -L -v https://httpbin.org/redirect-to?url=https%3A%2F%2Fgoogle.com 2>&1 | grep Location

# Show only the body without headers
curl --no-keepalive \
  --header "Accept: application/json" \
  https://httpbin.org/get | jq -r '.url'
```

### Response Formatting
```bash
# Pretty print JSON response (requires jq)
curl -s https://httpbin.org/json | jq '.'

# Extract specific fields from JSON
curl -s https://httpbin.org/json | jq '.slideshow.title'

# Save only the body to file without headers
curl -s -o data.txt https://httpbin.org/get

# Show progress and response time
curl --progress-bar \
  --dump-header response_headers.txt \
  --write-out "Time: %{time_total}s\n" \
  https://httpbin.org/get
```

## Timeout and Retry Options

### Timeouts
```bash
# Set connection timeout (3 seconds)
curl -m 3 https://httpbin.org/delay/5

# Set maximum time for entire operation (10 seconds)
curl --max-time 10 https://httpbin.org/delay/15

# Separate connect and total timeouts
curl --connect-timeout 5 \
  --max-time 20 \
  https://httpbin.org/get
```

### Retry Mechanisms
```bash
# Retry failed requests up to 3 times
curl --retry 3 https://httpbin.org/status/500

# Retry with exponential backoff (1, 2, 4 seconds)
curl --retry 3 \
  --retry-delay 1 \
  --retry-max-time 60 \
  https://httpbin.org/status/500

# Retry on specific HTTP status codes
curl --retry 3 \
  --retry-connrefused \
  --retry-http-status "429,500,503" \
  https://api.example.com/
```

## Advanced Examples

### API Integration Example
```bash
#!/bin/bash
# Complete API interaction example

# Get authentication token
TOKEN=$(curl -s -X POST \
  -d "client_id=app123&client_secret=secret456&grant_type=client_credentials" \
  https://api.example.com/oauth/token | jq -r '.access_token')

# Use token to get user data
curl -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  https://api.example.com/users/123

# Upload file with metadata
curl -F "file=@document.pdf" \
  -F "metadata={\"user_id\":123,\"category\":\"documents\"}" \
  -H "Authorization: Bearer $TOKEN" \
  https://api.example.com/upload
```

### Monitoring Script
```bash
#!/bin/bash
# Health check script

API_URL="https://api.example.com/health"
TIMEOUT=5

response=$(curl -s -w "%{http_code}" \
  --max-time $TIMEOUT \
  $API_URL)

if [ "$response" = "200" ]; then
  echo "$(date): API is healthy"
else
  echo "$(date): API is down. Status code: $(echo $response | tail -c 3)"
fi
```

### Bulk Operations
```bash
#!/bin/bash
# Process multiple URLs in bulk

URLS=(
  "https://api.example.com/users/1"
  "https://api.example.com/users/2"
  "https://api.example.com/users/3"
)

for url in "${URLS[@]}"; do
  echo "Processing $url..."
  curl -s -o /dev/null "$url" &> /dev/null
  if [ $? -eq 0 ]; then
    echo "✓ Success for $url"
  else
    echo "✗ Failed for $url"
  fi
done
```

### Debugging with Verbose Output
```bash
# Verbose debugging output
curl -v https://httpbin.org/get

# Show request and response headers
curl --trace-ascii trace.txt https://httpbin.org/post

# Debug SSL/TLS connections
curl --verbose \
  --cert-status \
  --tlsv1.2 \
  https://example.com/
```

This comprehensive guide covers the most common curl usage patterns, from basic requests to advanced automation scenarios. Each example can be adapted based on your specific requirements and API endpoints.

