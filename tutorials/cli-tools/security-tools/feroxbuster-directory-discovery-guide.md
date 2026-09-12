# Feroxbuster (Fast Recursive Content Discovery) Complete Guide

`feroxbuster` is a high-speed, multi-threaded recursive web application directory and file brute-forcing tool written in Rust. Designed for penetration testers and red teams, `feroxbuster` automatically discovers hidden endpoints, unlinked assets, backup files, and administrative panels by combining fast asynchronous HTTP requests with intelligent recursive directory tree traversal.

---

## 📚 Table of Contents

1. [Overview & Key Advantages](#overview-key-advantages)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Web Directory Fuzzing](#basic-web-directory-fuzzing)
4. [Recursive Scanning Control](#recursive-scanning-control)
5. [Fuzzing File Extensions (`-x`)](#fuzzing-file-extensions--x)
6. [Filtering Status Codes, Sizes, and Words](#filtering-status-codes-sizes-and-words)
7. [Tuning Threads, Rate Limits & Parallelism](#tuning-threads-rate-limits-parallelism)
8. [Proxy Integration (Burp Suite & ZAP)](#proxy-integration-burp-suite-zap)
9. [Extracting Links from Page Responses](#extracting-links-from-page-responses)
10. [Resuming Interrupted Scans (`--resume-from`)](#resuming-interrupted-scans---resume-from)
11. [Everyday Cheat Sheet & Useful Aliases](#everyday-cheat-sheet-useful-aliases)
12. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Key Advantages

Compared to Python-based (Dirsearch) or Go-based (Gobuster/FFUF) alternatives:

- **Automatic Recursion**: As soon as a valid directory (e.g. `200 OK` or `301 Moved`) is found, a new scanning job is spawned automatically without manual intervention.
- **Rust Concurrency**: High performance utilizing `tokio` asynchronous green threads.
- **Built-in Link Extraction**: Automatically parses HTML and JavaScript files in responses to find new endpoints.
- **State Persistence**: Saves scan states to disk so long running assessments can be resumed if interrupted.

---

## ⚙️ Homebrew Installation & Verification

### 1. Install via Homebrew

```bash
brew install feroxbuster
```

### 2. Verify Installation

```bash
which feroxbuster
feroxbuster -V
```

Output:
```text
/opt/homebrew/bin/feroxbuster
feroxbuster 2.13.1
```

---

## 🚀 Basic Web Directory Fuzzing

Launch a directory search against a target web server:

```bash
feroxbuster -u https://example.com -w /path/to/wordlist.txt
```

> [!TIP]
> Popular wordlists like `SecLists` (`Discovery/Web-Content/raft-medium-directories.txt`) work seamlessly with `feroxbuster`.

### Sample Terminal Output

```text
 200      GET      124l      320w     4821c https://example.com/login
 301      GET        9l       28w      310c https://example.com/admin => https://example.com/admin/
 200      GET       45l      112w     1840c https://example.com/admin/dashboard
 403      GET       10l       30w      280c https://example.com/server-status
```

Columns:
1. **HTTP Status Code** (`200`, `301`, `403`)
2. **Method** (`GET`)
3. **Lines (`l`)**, **Words (`w`)**, **Characters / Content-Length (`c`)**
4. **Discovered URL** and redirect destination.

---

## 🔄 Recursive Scanning Control

By default, `feroxbuster` recurses into every newly discovered directory:

### 1. Limit Maximum Recursion Depth (`-d`)

```bash
# Only scan root and one level of subdirectories
feroxbuster -u https://example.com -w wordlist.txt -d 2
```

### 2. Disable Recursion Entirely (`-n` / `--no-recursion`)

```bash
# Scan only the top-level path
feroxbuster -u https://example.com -w wordlist.txt -n
```

---

## 📄 Fuzzing File Extensions (`-x`)

Append file extensions to every entry in the wordlist:

```bash
# Look for PHP, HTML, and text files
feroxbuster -u https://example.com -w wordlist.txt -x php,html,txt,json

# Hunt for backup and configuration files
feroxbuster -u https://example.com -w wordlist.txt -x bak,old,env,config,yml
```

---

## 🎯 Filtering Status Codes, Sizes, and Words

Clean up false positives from wildcards or custom 404 pages:

### 1. Filter Specific Status Codes (`-C` / `--filter-status`)

```bash
# Exclude 404 Not Found and 403 Forbidden
feroxbuster -u https://example.com -w wordlist.txt -C 404,403
```

### 2. Filter by Response Size (`-S` / `--filter-size`)

```bash
# Ignore false positives returning exactly 1520 bytes
feroxbuster -u https://example.com -w wordlist.txt -S 1520
```

### 3. Filter by Word Count (`-W` / `--filter-words`)

```bash
# Ignore error pages containing 42 words
feroxbuster -u https://example.com -w wordlist.txt -W 42
```

---

## ⚡ Tuning Threads, Rate Limits & Parallelism

Balance speed and server impact:

```bash
# 50 concurrent worker threads
feroxbuster -u https://example.com -w wordlist.txt -t 50

# Rate limit to 200 requests per second (avoid WAF blocking)
feroxbuster -u https://example.com -w wordlist.txt --rate-limit 200
```

---

## 🛡️ Proxy Integration (Burp Suite & ZAP)

Route all fuzzing requests through an interception proxy to inspect findings in your proxy history:

```bash
# Built-in shortcut for Burp Suite (routes to 127.0.0.1:8080 and sets insecure TLS)
feroxbuster -u https://example.com -w wordlist.txt --burp

# Route to custom proxy
feroxbuster -u https://example.com -w wordlist.txt -p http://127.0.0.1:8080 -k
```

---

## 🔗 Extracting Links from Page Responses

By default, `feroxbuster` parses response bodies for new candidate endpoints. To customize this behavior:

```bash
# Disable automated link extraction from HTML/JS
feroxbuster -u https://example.com -w wordlist.txt --dont-extract-links
```

---

## 💾 Resuming Interrupted Scans (`--resume-from`)

`feroxbuster` saves `.state` checkpoint files in the current working directory during scans:

```bash
# Resume an aborted scan from state file
feroxbuster --resume-from ferox-1698200000.state
```

---

## 📋 Everyday Cheat Sheet & Useful Aliases

### Recommended Aliases (`~/.zshrc`)

```bash
# Fast non-recursive scan with standard extensions
alias fbx-fast='feroxbuster -n -x php,html,js,json -t 50'

# Deep recursive scan through Burp Proxy
alias fbx-burp='feroxbuster --burp -x php,txt'
```

### Quick Commands Table

| Task | Command |
| :--- | :--- |
| **Standard Scan** | `feroxbuster -u https://target.com -w words.txt` |
| **Fuzz Extensions** | `feroxbuster -u https://target.com -w words.txt -x php,html` |
| **Non-Recursive** | `feroxbuster -u https://target.com -w words.txt -n` |
| **Limit Depth** | `feroxbuster -u https://target.com -w words.txt -d 2` |
| **Filter Status Codes** | `feroxbuster -u https://target.com -w words.txt -C 404,403` |
| **Rate Limit Requests** | `feroxbuster -u https://target.com -w words.txt --rate-limit 100` |
| **Route to Burp** | `feroxbuster -u https://target.com -w words.txt --burp` |
| **Resume State** | `feroxbuster --resume-from scan.state` |

---

## 🗑️ Uninstallation

```bash
brew uninstall feroxbuster
```
