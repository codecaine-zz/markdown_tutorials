# Aria2 Multi-Source Download Manager Guide

`aria2` (`aria2c`) is a high-speed, lightweight, multi-protocol, multi-source command-line download utility. It supports **HTTP/HTTPS, FTP, SFTP, BitTorrent, and Metalink**, utilizing multi-connection segmented downloads to max out available internet bandwidth.

---

## 📚 Table of Contents

1. [Overview & Features](#overview--features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Multi-Connection Segmented Downloads](#multi-connection-segmented-downloads)
4. [Downloading Multiple Files & Input Lists](#downloading-multiple-files--input-lists)
5. [BitTorrent & Magnet Links](#bittorrent--magnet-links)
6. [Resuming & Speed Limits](#resuming--speed-limits)
7. [RPC Server Daemon Setup](#rpc-server-daemon-setup)
8. [Configuration File (`aria2.conf`)](#configuration-file-aria2conf)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **Segmented Downloading**: Downloads a single file from multiple connection streams simultaneously.
- **Multi-Protocol Support**: Pulls segments for the same file from both HTTP and FTP/BitTorrent simultaneously.
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

Accelerate single file downloads by splitting the file into multiple streams (`-s`) and opening maximum connections per server (`-x`).

```bash
# Single file download with 16 connections and 16 segments
aria2c -s 16 -x 16 https://example.com/large-archive.zip

# Save file with custom output filename (-o)
aria2c -s 16 -x 16 -o "my-ubuntu.iso" https://releases.ubuntu.com/22.04/ubuntu-22.04-desktop-amd64.iso
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
https://example.com/video2.mp4
  out=video2.mp4
```

Execute batch download:
```bash
aria2c -i download-list.txt -j 4
```

---

## 🧲 BitTorrent & Magnet Links

### 1. Download via `.torrent` File
```bash
# Download torrent contents to current directory
aria2c sample.torrent

# Limit upload seeding speed to 50KB/s
aria2c --max-upload-limit=50K sample.torrent
```

### 2. Download via Magnet Link
```bash
# Download directly from Magnet URL
aria2c "magnet:?xt=urn:btih:209c61014e2e792941e4b30c3325c8e66cb7a1b7&dn=Ubuntu"
```

---

## ⏯️ Resuming & Speed Limits

```bash
# Resume an interrupted download (-c)
aria2c -c -s 16 -x 16 https://example.com/large-file.zip

# Throttle overall download speed to 2MB/s
aria2c --max-overall-download-limit=2M https://example.com/file.zip
```

---

## 🌐 RPC Server Daemon Setup

Run `aria2c` as a background RPC server to control downloads from browser extensions or web frontends (e.g. AriaNg).

```bash
# Start aria2 RPC server listening on port 6800
aria2c --enable-rpc --rpc-listen-all=true --rpc-allow-origin-all --daemon
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
min-split-size=5M
split=16

# Disk cache to reduce SSD writes
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
| Fast Multi-stream download | `aria2c -s 16 -x 16 "URL"` |
| Resume download | `aria2c -c "URL"` |
| Rename output file | `aria2c -o "newname.ext" "URL"` |
| Download from text list | `aria2c -i download-list.txt` |
| Download Magnet link | `aria2c "magnet:?xt=..."` |
| Limit download speed | `aria2c --max-overall-download-limit=2M "URL"` |
