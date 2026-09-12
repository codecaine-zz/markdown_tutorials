# Aria2 Multi-Source Download Manager Guide

`aria2` (`aria2c`) is a ultra-fast, lightweight, multi-protocol, multi-source command-line download utility. It supports **HTTP/HTTPS, FTP, SFTP, BitTorrent, and Metalink**, utilizing multi-connection segmented downloads to max out available internet bandwidth.

---

## 📚 Table of Contents

1. [Overview & Features](#overview-features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Multi-Connection Segmented Downloads](#multi-connection-segmented-downloads)
4. [Downloading Multiple Files & Input Lists](#downloading-multiple-files-input-lists)
5. [Headers, Auth & Multi-Mirror Downloads](#headers-auth-multi-mirror-downloads)
6. [BitTorrent & Magnet Links](#bittorrent-magnet-links)
7. [Resuming & Speed Limits](#resuming-speed-limits)
8. [RPC Server Daemon & WebUI Setup](#rpc-server-daemon-webui-setup)
9. [Configuration File (`aria2.conf`)](#configuration-file-aria2conf)
10. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **Segmented Downloading**: Downloads a single file from multiple connection streams simultaneously.
- **Multi-Protocol & Multi-Mirror**: Pulls segments for the same file from multiple HTTP, FTP, and BitTorrent mirrors simultaneously.
- **Lightweight**: Minimal CPU and memory usage (typically under 10MB RAM).
- **JSON-RPC Interface**: Can run in daemon mode to power web interfaces like AriaNg or WebUI-Aria2.

---

## ⚙️ Installation via Homebrew

```bash
# Install aria2 via Homebrew on macOS
brew install aria2

# Verify installation
aria2c --version
```

---

## 🚀 Multi-Connection Segmented Downloads

Accelerate single file downloads by splitting the file into multiple streams (`-s`) and opening maximum connections per server (`-x`) with minimum split size (`-k`).

```bash
# High-speed download with 16 connections and 1MB segment split size
aria2c -s 16 -x 16 -k 1M https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso

# Save download to custom output directory (-d) and custom filename (-o)
aria2c -s 16 -x 16 -d ~/Downloads -o "ubuntu.iso" https://releases.ubuntu.com/22.04/ubuntu-22.04.5-desktop-amd64.iso
```

---

## 📥 Downloading Multiple Files & Input Lists

### 1. Pass Multiple URLs on CLI
```bash
# Download two files in parallel
aria2c https://example.com/file1.zip https://example.com/file2.zip
```

### 2. Read URLs from Text File (`-i`)
Create `download-list.txt`:
```text
https://example.com/video1.mp4
  out=video1.mp4
  dir=/Users/username/Movies
https://example.com/video2.mp4
  out=video2.mp4
  dir=/Users/username/Movies
```

Execute batch download with 4 parallel download slots (`-j 4`):
```bash
aria2c -i download-list.txt -j 4
```

---

## 🔒 Headers, Auth & Multi-Mirror Downloads

### 1. Download with HTTP Authorization Headers
```bash
# Pass Bearer token authentication header
aria2c --header="Authorization: Bearer YOUR_TOKEN_HERE" https://api.example.com/protected-file.zip
```

### 2. Download a Single File from Multiple Mirror Servers
```bash
# Fetch segments for the SAME file simultaneously from HTTP and FTP locations
aria2c "http://mirror1.example.com/file.iso" "ftp://mirror2.example.com/file.iso"
```

---

## 🧲 BitTorrent & Magnet Links

### 1. Download via `.torrent` File
```bash
# Download torrent contents to current directory
aria2c sample.torrent

# Download specific files inside torrent (e.g. files #1 and #3)
aria2c --select-file=1,3 sample.torrent
```

### 2. Download via Magnet Link
```bash
# Download directly from Magnet URL
aria2c "magnet:?xt=urn:btih:209c61014e2e792941e4b30c3325c8e66cb7a1b7&dn=Ubuntu"
```

### 3. Limit Seeding Upload Speed & Ratio
```bash
# Stop seeding after download finishes (--seed-time=0)
aria2c --seed-time=0 sample.torrent

# Limit upload seeding speed to 50KB/s
aria2c --max-upload-limit=50K sample.torrent
```

---

## ⏯️ Resuming & Speed Limits

```bash
# Resume an interrupted download (-c / --continue)
aria2c -c -s 16 -x 16 https://example.com/large-file.zip

# Throttle overall download speed to 2MB/s
aria2c --max-overall-download-limit=2M https://example.com/file.zip
```

---

## 🌐 RPC Server Daemon & WebUI Setup

Run `aria2c` as a background RPC server to control downloads remotely or connect to frontends like AriaNg.

```bash
# Start aria2 RPC server with secret token listening on port 6800
aria2c --enable-rpc --rpc-listen-all=true --rpc-allow-origin-all --rpc-secret="MySuperSecretToken" --daemon

# Test RPC endpoint with curl
curl http://localhost:6800/jsonrpc -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":"q1","method":"aria2.tellActive","params":["token:MySuperSecretToken"]}'
```

---

## ⚙️ Configuration File (`aria2.conf`)

Save persistent default settings in `~/.config/aria2/aria2.conf`.

```bash
mkdir -p ~/.config/aria2
cat << 'EOF' > ~/.config/aria2/aria2.conf
# Download directory
dir=${HOME}/Downloads

# Resume incomplete downloads
continue=true

# Connection limits
max-connection-per-server=16
min-split-size=1M
split=16
max-concurrent-downloads=5

# Disk cache to reduce SSD wear
disk-cache=64M
file-allocation=falloc

# Session management
input-file=${HOME}/.config/aria2/aria2.session
save-session=${HOME}/.config/aria2/aria2.session
save-session-interval=60
EOF

# Ensure session file exists
touch ~/.config/aria2/aria2.session
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Max speed segmented download | `aria2c -s 16 -x 16 -k 1M "URL"` |
| Save to specific folder & name | `aria2c -d ~/Downloads -o "file.iso" "URL"` |
| Resume interrupted download | `aria2c -c "URL"` |
| Download from text list | `aria2c -i download-list.txt -j 4` |
| Download Magnet link | `aria2c "magnet:?xt=..."` |
| Download specific torrent file | `aria2c --select-file=1 file.torrent` |
| Stop seeding after download | `aria2c --seed-time=0 file.torrent` |
| Limit download speed | `aria2c --max-overall-download-limit=2M "URL"` |
| Start RPC server daemon | `aria2c --enable-rpc --rpc-secret=SECRET --daemon` |
