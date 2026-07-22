# xh: Friendly, Fast HTTP Client written in Rust

## Table of Contents

1. [What is `xh`?](#1-what-is-xh)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Syntax & GET Requests](#4-basic-syntax--get-requests)
5. [POST Requests & JSON Payloads](#5-post-requests--json-payloads)
6. [Downloading Files (`wget` mode)](#6-downloading-files-wget-mode)
7. [Headers, Authentication & Cookies](#7-headers-authentication--cookies)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `xh`?

`xh` is a friendly, fast tool for sending HTTP requests from your terminal, written in Rust. It borrows HTTPie's intuitive syntax design while providing significantly faster execution speeds, lower resource consumption, native HTTP/2 & HTTP/3 support, syntax highlighting, and `curl` command translation.

#### Key Features
* **10x Faster Execution:** Native Rust binary vs. Python-based HTTPie.
* **Colorful Formatted Output:** Automatic JSON colorization and syntax highlighting.
* **Curl Translation:** Convert any `xh` command directly into `curl` syntax using `--curl`.

---

### 2. Prerequisites

Verify Homebrew on your ARM Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `xh` via Homebrew:

```bash
brew install xh
```

Verify installation:

```bash
which xh
xh --version
```

**Expected Output:**
```text
/opt/homebrew/bin/xh
xh 0.22.x (or latest)
```

---

### 4. Basic Syntax & GET Requests

The basic syntax format is: `xh [METHOD] URL [REQUEST_ITEM ...]`

If no method is specified, `xh` defaults to `GET` (or `POST` if data items are present).

#### Send a GET Request
```bash
xh https://httpbin.org/get
```

**Example Output:**

```text
HTTP/1.1 200 OK
Content-Length: 308
Content-Type: application/json
Date: Wed, 22 Jul 2026 18:30:00 GMT
Server: gunicorn/19.9.0

{
    "args": {},
    "headers": {
        "Accept": "*/*",
        "Accept-Encoding": "gzip, deflate, br",
        "Host": "httpbin.org",
        "User-Agent": "xh/0.22.0"
    },
    "origin": "136.24.10.12",
    "url": "https://httpbin.org/get"
}
```

---

### 5. POST Requests & JSON Payloads

`xh` automatically constructs JSON payloads when key-value pairs are separated by `=` (string) or `:=` (raw JSON/booleans/numbers).

#### Send a JSON POST Request
```bash
xh POST https://httpbin.org/post name="Alice" role="developer" age:=30 active:=true
```

* `name="Alice"`: String field
* `age:=30`: Integer number
* `active:=true`: Boolean boolean

#### Send Form-Encoded Data (`-f` / `--form`)
```bash
xh -f POST https://httpbin.org/post username="admin" password="secret_password"
```

---

### 6. Downloading Files (`wget` mode)

To download a remote file with a progress bar:

```bash
xh -d https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso
```

Or save with a custom output filename (`-o`):

```bash
xh https://httpbin.org/image/png -o test_image.png
```

---

### 7. Headers, Authentication & Cookies

#### Custom Headers (`Header:Value`)
```bash
xh https://api.github.com/user "Authorization: Bearer YOUR_TOKEN_HERE" "User-Agent: MyApp/1.0"
```

#### Basic Authentication (`-a` / `--auth`)
```bash
xh -a admin:password123 https://httpbin.org/basic-auth/admin/password123
```

#### Translate `xh` Command to `curl` Syntax (`--curl`)
If you want to copy a working API command into a shell script using `curl`:

```bash
xh POST https://httpbin.org/post name="Alice" --curl
```

**Output:**
```bash
curl -X POST --header "Content-Type: application/json" --data '{"name":"Alice"}' https://httpbin.org/post
```

---

### 8. Uninstallation

```bash
brew uninstall xh
```
