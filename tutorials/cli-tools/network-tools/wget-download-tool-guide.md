# Complete Wget Tutorial: Everything You Need to Know

Wget is a powerful command-line utility for downloading files from the web. This comprehensive tutorial covers everything from basic usage to advanced features.

## Table of Contents

1. [Basic Installation](#basic-installation)
2. [Getting Started](#getting-started)
3. [Downloading Files](#downloading-files)
4. [Downloading Websites](#downloading-websites)
5. [Advanced Download Options](#advanced-download-options)
6. [Authentication and Credentials](#authentication-and-credentials)
7. [Scheduling Downloads](#scheduling-downloads)
8. [Resume Downloads](#resume-downloads)
9. [HTTP Headers and Cookies](#http-headers-and-cookies)
10. [File Filtering and Patterns](#file-filtering-and-patterns)

## Basic Installation

### On Linux (Ubuntu/Debian):
```bash
sudo apt update
sudo apt install wget
```

### On macOS:
```bash
brew install wget
```

### On Windows:
Download from https://eternalwindows.org/wget.html or use Windows Subsystem for Linux (WSL)

## Getting Started

### Basic Download Command:
```bash
# Download a single file
wget https://example.com/file.pdf

# Download with custom filename
wget -O mydocument.pdf https://example.com/document.pdf

# Download with verbose output
wget -v https://example.com/file.zip
```

### Version Information:
```bash
# Check wget version
wget --version

# Get help information
wget --help
```

## Downloading Files

### Simple File Downloads:
```bash
# Basic download
wget https://httpbin.org/json

# Download with custom name
wget -O data.json https://httpbin.org/json

# Download multiple files at once
wget https://example.com/file1.txt https://example.com/file2.txt

# Download from a list of URLs (one URL per line in file)
wget -i urls.txt
```

### File Types and Extensions:
```bash
# Download HTML page
wget https://httpbin.org/html

# Download image
wget https://httpbin.org/image/png

# Download audio file
wget https://example.com/audio.mp3

# Download video (you can also save as .mp4, etc.)
wget -O video.mp4 https://example.com/video.webm
```

### Verbose and Quiet Modes:
```bash
# Verbose output with details
wget -v https://httpbin.org/json

# Silent mode (no output)
wget -q https://httpbin.org/json

# Progress bar (default behavior)
wget --progress=bar https://example.com/largefile.zip

# No progress bar
wget --progress=no https://example.com/file.zip
```

## Downloading Websites

### Mirror Entire Website:
```bash
# Download entire website recursively
wget -r https://example.com/

# Download with specific depth (limit recursion level)
wget -r -l 2 https://example.com/

# Download only HTML and CSS files
wget -r -l 1 --accept=html,css https://example.com/

# Download without creating directory structure
wget -r -np -nH https://example.com/
```

### Website Mirroring Options:
```bash
# Mirror website with all resources
wget -m https://example.com/

# Mirror website with specific file types only
wget -m --accept=html,css,js,png,jpg https://example.com/

# Download without saving to disk (useful for testing)
wget -r --spider https://example.com/

# Download with time-stamping and timestamping
wget -m --timestamping https://example.com/
```

### Advanced Website Mirroring:
```bash
# Mirror with specific parameters
wget -r \
     -l 3 \
     --no-parent \
     --no-cookies \
     --no-cache \
     --user-agent="Mozilla/5.0" \
     https://example.com/

# Download website but exclude certain directories
wget -r -l 2 --exclude-directories=/private,/admin https://example.com/
```

## Advanced Download Options

### Download with Retry Logic:
```bash
# Retry failed downloads (default is 3)
wget --tries=5 https://example.com/file.zip

# Set delay between retries (in seconds)
wget --tries=3 --wait=10 https://example.com/file.zip

# Use exponential backoff for retries
wget --tries=5 --random-wait https://example.com/file.zip
```

### Download Size Limits:
```bash
# Limit download size to 1MB
wget --limit-rate=1m https://example.com/largefile.zip

# Maximum file size (0 = unlimited)
wget --max-size=50m https://example.com/file.zip

# Minimum file size for download
wget --min-size=1k https://example.com/file.zip
```

### Download Rate Control:
```bash
# Limit bandwidth usage to 10KB/s
wget --limit-rate=10k https://example.com/largefile.zip

# Limit rate with progress display
wget --limit-rate=50k --progress=bar https://example.com/file.zip

# Resume interrupted downloads automatically
wget -c https://example.com/partialfile.zip
```

### Custom HTTP Headers:
```bash
# Add custom headers
wget \
  --header="User-Agent: MyCustomBot/1.0" \
  --header="Accept-Language: en-US,en;q=0.9" \
  https://httpbin.org/headers

# Download with specific Accept header for API calls
wget \
  --header="Accept: application/json" \
  --header="Content-Type: application/json" \
  https://api.example.com/data.json
```

## Authentication and Credentials

### Basic Authentication:
```bash
# With username and password in URL
wget https://username:password@secure.example.com/file.zip

# Using user/pass parameters
wget --user=username --password=password https://example.com/protectedfile.pdf

# Download with authentication header (for API)
wget \
  --header="Authorization: Bearer mytoken123" \
  https://api.example.com/data.json
```

### Cookie Management:
```bash
# Use cookies from file
wget --load-cookies cookies.txt https://example.com/

# Save cookies to file for future use
wget --save-cookies cookies.txt https://example.com/login

# Set cookie manually
wget \
  --header="Cookie: sessionid=abc123; user=john" \
  https://secure.example.com/protected-page.html

# Download using Firefox cookies (requires cookie conversion)
wget --load-cookies ~/.mozilla/firefox/profile/cookies.sqlite https://example.com/
```

### Form Submission and Login:
```bash
# Submit form data (for login)
wget \
  --post-data="username=admin&password=secret" \
  --keep-session-cookies \
  https://example.com/login

# Download after logging in
wget \
  --post-data="username=user&password=pass" \
  --save-cookies cookies.txt \
  https://example.com/login

wget \
  --load-cookies cookies.txt \
  https://example.com/protected-content.html
```

## Scheduling Downloads

### Delayed Downloads:
```bash
# Wait before downloading (in seconds)
wget --wait=60 https://example.com/file.zip

# Random wait between downloads (1-5 minutes)
wget --random-wait https://example.com/

# Download at specific time
at 23:00 wget https://example.com/daily_backup.zip

# Schedule recurring download with cron
# Add to crontab: 0 2 * * * wget -O /backup/data_$(date +\%Y\%m\%d).tar.gz https://example.com/data.tar.gz
```

### Download Queue Management:
```bash
# Create download queue in file (one URL per line)
cat > download_queue.txt << EOF
https://example.com/file1.zip
https://example.com/file2.pdf
https://example.com/file3.mp4
EOF

# Process queue with delay between downloads
wget -i download_queue.txt --wait=5

# Download from queue but stop on error
wget -i download_queue.txt --continue --tries=1
```

## Resume Downloads

### Resuming Partial Downloads:
```bash
# Resume interrupted download (auto-detects partial file)
wget -c https://example.com/largefile.zip

# Force resume even if complete file exists
wget -c --force-directories https://example.com/file.zip

# Check if download can be resumed
wget --continue https://example.com/file.zip

# Download with resume and retry on failure
wget -c --tries=3 https://example.com/largefile.zip
```

### Partial File Management:
```bash
# Resume from specific position (manual)
wget --start-position=1024000 https://example.com/bigfile.zip

# Continue download where it left off
wget -c --no-clobber https://example.com/file.zip

# Download and check file integrity before resume
wget -c --content-disposition https://example.com/file.zip
```

## HTTP Headers and Cookies

### Advanced Header Management:
```bash
# Add multiple headers at once
wget \
  --header="User-Agent: Mozilla/5.0" \
  --header="Accept: text/html,application/xhtml+xml" \
  --header="Accept-Language: en-US,en;q=0.9" \
  https://example.com/

# Download with referer header (to bypass some restrictions)
wget \
  --referer="https://www.google.com/" \
  https://example.com/protected-file.zip

# Set connection headers
wget \
  --header="Connection: keep-alive" \
  --header="Cache-Control: no-cache" \
  https://example.com/data.json
```

### Session and Cookie Handling:
```bash
# Save session cookies to file
wget --save-cookies cookies.txt https://example.com/login

# Use saved session for subsequent requests
wget --load-cookies cookies.txt https://example.com/dashboard.html

# Combine save/load cookie operations
wget \
  --post-data="username=user&password=pass" \
  --save-cookies cookies.txt \
  https://example.com/login

wget \
  --load-cookies cookies.txt \
  --header="User-Agent: MyBot/1.0" \
  https://example.com/secure-page.html
```

## Practical Examples and Scripts

### Complete Download Script:
```bash
#!/bin/bash
# Advanced download script with error handling

DOWNLOAD_DIR="/home/user/downloads"
LOG_FILE="$DOWNLOAD_DIR/download.log"

mkdir -p "$DOWNLOAD_DIR"

download_with_retry() {
    local url=$1
    local output_file=$2
    local retries=3
    
    for i in $(seq 1 $retries); do
        echo "$(date): Attempt $i to download $url" >> "$LOG_FILE"
        
        wget \
            -P "$DOWNLOAD_DIR" \
            --progress=bar \
            --continue \
            --tries=3 \
            --timeout=30 \
            --user-agent="Mozilla/5.0 (compatible; DownloadBot/1.0)" \
            "$url" 2>>"$LOG_FILE"
            
        if [ $? -eq 0 ]; then
            echo "$(date): Successfully downloaded $output_file" >> "$LOG_FILE"
            return 0
        else
            echo "$(date): Failed download attempt $i for $url" >> "$LOG_FILE"
            sleep 5
        fi
    done
    
    echo "$(date): All attempts failed for $url" >> "$LOG_FILE"
    return 1
}

# Usage examples:
download_with_retry "https://example.com/file.zip" "file.zip"
download_with_retry "https://api.example.com/data.json" "data.json"
```

### Batch Download with Rate Limiting:
```bash
#!/bin/bash

# Batch download function with rate control
batch_download() {
    local file_list="urls.txt"
    local delay=30
    
    while IFS= read -r url; do
        if [ ! -z "$url" ]; then
            echo "Downloading: $url"
            wget --progress=bar --wait=$delay "$url"
        fi
    done < "$file_list"
}

# Usage:
batch_download
```

### Download Manager with Progress Tracking:
```bash
#!/bin/bash

download_with_progress() {
    local url=$1
    local filename=$(basename "$url")
    
    echo "Starting download of $filename..."
    
    wget \
        --progress=bar:force:noscroll \
        --continue \
        -O "$filename" \
        "$url"
        
    if [ $? -eq 0 ]; then
        echo "Download completed: $filename"
    else
        echo "Download failed: $filename"
    fi
}
```

These examples demonstrate advanced wget usage patterns for various download scenarios, including authentication, rate limiting, resume capabilities, and automated batch processing. Each approach can be adapted based on specific requirements and network conditions.

