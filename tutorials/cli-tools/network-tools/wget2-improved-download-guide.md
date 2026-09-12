# Wget2 Next-Generation Download Utility Guide

`wget2` is the successor to GNU Wget. Designed for modern high-speed internet connections, `wget2` features multi-threaded multi-connection file downloads, HTTP/2 protocol support, OCSP stapling, faster recursive website crawling, and lower CPU overhead than classic `wget`.

---

## 📚 Table of Contents

1. [Overview & Comparison (`wget` vs `wget2`)](#overview-comparison-wget-vs-wget2)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Multi-Threaded High-Speed Downloads](#multi-threaded-high-speed-downloads)
4. [Downloading Multiple URLs & Batch Lists](#downloading-multiple-urls-batch-lists)
5. [High-Speed Website Mirroring & Crawling](#high-speed-website-mirroring-crawling)
6. [Headers, Auth & SSL Configuration](#headers-auth-ssl-configuration)
7. [Configuration File (`~/.wget2rc`)](#configuration-file-wget2rc)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Comparison (`wget` vs `wget2`)

`wget2` addresses classic `wget` performance bottlenecks by adding native parallelism, HTTP/2 support, and multi-stream downloading:

| Feature | Classic `wget` | `wget2` |
| --- | --- | --- |
| **Multi-Threading** | Single connection stream | Multi-threaded parallel connections (`-j`) |
| **HTTP/2 Support** | Experimental / None | Full Native HTTP/2 with multiplexing |
| **Performance** | Single-threaded sequential | Up to 8x-10x faster directory crawling |
| **TLS / SSL** | Standard OpenSSL / GnuTLS | Modern TLS 1.3 & OCSP Stapling |
| **If-Modified-Since** | Basic | Advanced HTTP caching checks |

---

## ⚙️ Installation via Homebrew

```bash
# Install wget2 via Homebrew on macOS
brew install wget2

# Verify installation
wget2 --version
```

---

## 🚀 Multi-Threaded High-Speed Downloads

Accelerate file downloads by spawning multiple parallel thread connections (`-j` / `--max-threads`).

```bash
# High-speed download using 8 parallel thread connections
wget2 -j 8 https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso

# Save download to custom file name (-O)
wget2 -O my_ubuntu.iso https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso

# Save download to specific directory (-P)
wget2 -P ~/Downloads/ https://example.com/archive.zip

# Resume interrupted download (-c)
wget2 -c -j 8 https://example.com/large-video.mp4
```

---

## 📥 Downloading Multiple URLs & Batch Lists

### 1. Pass Multiple URLs on CLI
```bash
# Download 3 files concurrently in parallel
wget2 -j 4 https://example.com/file1.zip https://example.com/file2.zip https://example.com/file3.zip
```

### 2. Read URLs from Text File (`-i`)
Create `urls.txt`:
```text
https://example.com/document1.pdf
https://example.com/document2.pdf
https://example.com/document3.pdf
```

Execute batch download with 8 parallel worker threads:
```bash
wget2 -i urls.txt -j 8 -P ~/Documents/
```

---

## 🌐 High-Speed Website Mirroring & Crawling

Because `wget2` processes links concurrently using multiple threads, website mirroring is significantly faster than classic `wget`.

### 1. Mirror Entire Directory for Offline Viewing
```bash
# Mirror site concurrently, converting links for local offline browser viewing
wget2 --mirror --convert-links --page-requisites --no-parent -j 8 https://example.com/docs/
```

### 2. Recursively Download Specific Extensions Only (`--accept` / `--reject`)
```bash
# Crawl site up to 2 levels deep and download all PDF documents
wget2 -r -l 2 --accept=pdf -nd https://example.com/resources/

# Download site images while excluding GIFs
wget2 -r -l 1 --accept=png,jpg,jpeg --reject=gif https://example.com/gallery/
```

---

## 🔒 Headers, Auth & SSL Configuration

### 1. HTTP Basic Authentication
```bash
# Authenticate with username and password
wget2 --user="admin" --password="secretpassword" https://example.com/protected/data.csv
```

### 2. Custom User-Agent & HTTP Headers
```bash
# Pass custom headers and User-Agent
wget2 --user-agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)" \
      --header="Authorization: Bearer YOUR_TOKEN_HERE" \
      https://api.example.com/export.json
```

### 3. SSL/TLS Certificate Bypassing & Bandwidth Control
```bash
# Bypass self-signed SSL certificate errors
wget2 --no-check-certificate https://self-signed.internal.local/file.tar.gz

# Limit download speed to 1MB/s
wget2 --limit-rate=1M https://example.com/largefile.zip
```

---

## ⚙️ Configuration File (`~/.wget2rc`)

Save persistent configuration defaults in `~/.wget2rc`.

```bash
cat << 'EOF' > ~/.wget2rc
# Default parallel threads
max-threads = 8

# Always resume partial downloads
continue = on

# Default timeouts
timeout = 30
tries = 3

# Display progress bar
progress = bar
EOF
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| High-speed 8-thread download | `wget2 -j 8 "URL"` |
| Save as custom name | `wget2 -O "newname.ext" "URL"` |
| Resume interrupted download | `wget2 -c -j 8 "URL"` |
| Batch download file list | `wget2 -i urls.txt -j 8` |
| Parallel site mirror for offline | `wget2 --mirror --convert-links --page-requisites -j 8 "URL"` |
| Download only PDFs | `wget2 -r -l 1 --accept=pdf -nd "URL"` |
| Ignore SSL errors | `wget2 --no-check-certificate "URL"` |
| Bearer token download | `wget2 --header="Authorization: Bearer TOKEN" "URL"` |
| Throttle bandwidth speed | `wget2 --limit-rate=1M "URL"` |