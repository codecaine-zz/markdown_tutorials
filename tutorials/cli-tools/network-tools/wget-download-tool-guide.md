# Complete Wget Download Utility Guide

`wget` is a free, robust non-interactive network downloader. It supports HTTP, HTTPS, and FTP protocols, background execution, recursive website mirroring, file filtering, and automatic resumption of interrupted downloads even over unstable connection speeds.

---

## 📚 Table of Contents

1. [Installation via Homebrew](#installation-via-homebrew)
2. [Essential Copy & Paste One-Liners](#essential-copy--paste-one-liners)
3. [Website Mirroring & Offline Archiving](#website-mirroring--offline-archiving)
4. [Batch Downloading & Rate Throttling](#batch-downloading--rate-throttling)
5. [Authentication, Headers & Cookies](#authentication-headers--cookies)
6. [File Filtering & Pattern Matching](#file-filtering--pattern-matching)
7. [SSL/TLS, Proxies & Troubleshooting](#ssltls-proxies--troubleshooting)
8. [Practical Automation Scripts](#practical-automation-scripts)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## ⚙️ Installation via Homebrew

```bash
# Install wget via Homebrew on macOS
brew install wget

# Verify installation
wget --version
```

---

## 🚀 Essential Copy & Paste One-Liners

### 1. Simple Single File Download
```bash
# Download file to current working directory
wget https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso
```

### 2. Save with Custom Output Name (`-O`)
```bash
# Download and rename target file
wget -O ubuntu-installer.iso https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso
```

### 3. Save to Specific Directory (`-P`)
```bash
# Save download directly into ~/Downloads directory
wget -P ~/Downloads https://example.com/archive.zip
```

### 4. Resume an Interrupted Download (`-c`)
```bash
# Continue partially downloaded file where it left off
wget -c https://example.com/large-video.mp4
```

### 5. Run Download in Background (`-b`)
```bash
# Launch download in background (logs output to wget-log)
wget -b https://example.com/huge-dataset.tar.gz

# Check background download progress
tail -f wget-log
```

---

## 🌐 Website Mirroring & Offline Archiving

### 1. Mirror Entire Website for Offline Viewing
```bash
# Download full site, convert links for offline viewing, fetch images/CSS, stay in directory
wget --mirror --convert-links --adjust-extension --page-requisites --no-parent https://example.com/docs/
```

### 2. Spider Website to Check for 404 Broken Links (`--spider`)
```bash
# Scan links without saving files to disk
wget --spider -r -l 2 -nd -nv https://example.com/
```

### 3. Download Single Web Page with Images & CSS
```bash
# Download page and all requisite assets (images, CSS, JS) for offline reading
wget -p -k https://example.com/blog/article-1.html
```

---

## 📥 Batch Downloading & Rate Throttling

### 1. Download URLs from List File (`-i`)
Create `urls.txt`:
```text
https://example.com/file1.pdf
https://example.com/file2.pdf
https://example.com/file3.pdf
```

Execute download batch:
```bash
wget -i urls.txt -P ~/Documents/
```

### 2. Parallel Downloads using `xargs`
```bash
# Run 4 parallel wget download workers
cat urls.txt | xargs -n 1 -P 4 wget -q -P ./downloads/
```

### 3. Throttle Bandwidth Speed (`--limit-rate`)
```bash
# Limit download speed to 500 KB/s to prevent clogging network
wget --limit-rate=500k https://example.com/large-archive.zip
```

---

## 🔒 Authentication, Headers & Cookies

### 1. Basic HTTP Authentication
```bash
# Authenticate with username and password
wget --user="admin" --password="secretpassword" https://example.com/protected/report.pdf
```

### 2. Custom User-Agent & Headers
```bash
# Spoof Chrome User-Agent header
wget --user-agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36" \
     --header="Accept-Language: en-US,en;q=0.9" \
     https://example.com/data.json
```

### 3. Cookie Storage & Session Persistence
```bash
# Save login session cookies to file
wget --post-data="user=john&pass=secret" --save-cookies=cookies.txt --keep-session-cookies https://example.com/login

# Use saved cookies for subsequent requests
wget --load-cookies=cookies.txt https://example.com/dashboard/export.csv
```

---

## 🎯 File Filtering & Pattern Matching

### 1. Download Specific File Types Only (`-A` / `--accept`)
```bash
# Recursively download only PDF files up to 2 levels deep
wget -r -l 2 -A pdf -nd https://example.com/documents/
```

### 2. Exclude Specific Extensions (`-R` / `--reject`)
```bash
# Download site assets but exclude zip and video files
wget -r -R zip,mp4,avi,mov https://example.com/media/
```

### 3. Exclude Specific Directory Paths (`-X`)
```bash
# Exclude /admin and /private directories during recursive download
wget -r -X /admin,/private https://example.com/
```

---

## 🛡️ SSL/TLS, Proxies & Troubleshooting

### 1. Bypass Invalid SSL Certificate Errors
```bash
# Ignore self-signed or expired certificate warnings
wget --no-check-certificate https://self-signed.internal.local/data.tar.gz
```

### 2. Route Downloads Through HTTP / SOCKS Proxy
```bash
# Download using HTTP proxy server
wget -e http_proxy=http://127.0.0.1:8080 https://httpbin.org/ip
```

### 3. Retry Logic & Connection Timeouts
```bash
# Retry up to 5 times with a 10 second timeout per attempt
wget --tries=5 --timeout=10 https://example.com/flaky-endpoint.zip
```

---

## 🛠️ Practical Automation Scripts

### 1. Daily Backup Downloader Script
```bash
#!/bin/bash
set -euo pipefail

BACKUP_DIR="${HOME}/backups/$(date +%Y-%m-%d)"
mkdir -p "$BACKUP_DIR"

echo "Starting daily backup download..."
wget -c -q --show-progress \
     -P "$BACKUP_DIR" \
     --tries=3 \
     --timeout=30 \
     https://example.com/daily-db-dump.tar.gz

echo "✅ Backup successfully downloaded to $BACKUP_DIR"
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Download single file | `wget "URL"` |
| Save as custom name | `wget -O "newname.ext" "URL"` |
| Resume interrupted download | `wget -c "URL"` |
| Download in background | `wget -b "URL"` |
| Download list of URLs | `wget -i urls.txt` |
| Limit download speed | `wget --limit-rate=500k "URL"` |
| Mirror site for offline reading | `wget --mirror --convert-links --page-requisites "URL"` |
| Download only PDFs | `wget -r -l 1 -A pdf -nd "URL"` |
| Ignore SSL errors | `wget --no-check-certificate "URL"` |
| Basic Auth download | `wget --user=U --password=P "URL"` |
