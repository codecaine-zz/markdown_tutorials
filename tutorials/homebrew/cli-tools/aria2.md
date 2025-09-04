# aria2 with Homebrew: High-speed Downloads

## Table of Contents

1. [Overview](#overview)
2. [Install](#install)
3. [Single and Multiple Downloads](#single-and-multiple-downloads)
4. [Parallel, Segmented, and Resume](#parallel-segmented-and-resume)
5. [Torrents and Metalinks](#torrents-and-metalinks)
6. [Config File](#config-file)
7. [Troubleshooting](#troubleshooting)

## Overview

`aria2` is a lightweight, multi-protocol, multi-source download utility that supports HTTP(S), FTP, SFTP, BitTorrent, and Metalink with segmented, parallel downloads.

## Install

```bash
brew install aria2
aria2c --version
```

## Single and Multiple Downloads

```bash
# Single download
aria2c https://example.com/file.iso

# From list of URLs
aria2c -i urls.txt

# Output filename
aria2c -o myfile.iso https://example.com/file.iso
```

## Parallel, Segmented, and Resume

```bash
# Parallel connections per server
aria2c -x 16 https://example.com/big.zip

# Split into segments and continue
aria2c -s 16 -x 16 -c https://example.com/big.zip

# Limit speed
aria2c --max-overall-download-limit=2M URL
```

## Torrents and Metalinks

```bash
# Torrent file
aria2c my.torrent

# Magnet link
aria2c 'magnet:?xt=urn:btih:...'

# Metalink
aria2c file.metalink
```

## Config File

Create `~/.aria2/aria2.conf`:

```bash
mkdir -p ~/.aria2
cat > ~/.aria2/aria2.conf << 'EOF'
continue=true
max-concurrent-downloads=5
split=16
max-connection-per-server=16
min-split-size=1M
summary-interval=5
log-level=notice
EOF
```

Run with session to persist progress:

```bash
aria2c --enable-rpc --rpc-listen-port=6800 --input-file=~/.aria2/aria2.session --save-session=~/.aria2/aria2.session
```

## Troubleshooting

- File not resuming: ensure `-c` set and same URL
- Slow speeds: increase `-s` and `-x`, check server limits
- Too many connections: tune down to avoid bans
