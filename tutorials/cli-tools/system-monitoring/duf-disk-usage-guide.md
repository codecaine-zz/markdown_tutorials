# Duf Modern Disk Usage & Free Utility Guide

`duf` (Disk Usage/Free) is a modern, feature-packed command-line utility for viewing mounted filesystem disk usage. Written in Go, it serves as a colorful, intuitive replacement for traditional `df`.

---

## 📚 Table of Contents

1. [Overview & Features](#overview-features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Output Layout](#basic-usage-output-layout)
4. [Device & Filesystem Filtering](#device-filesystem-filtering)
5. [Sorting & Column Customization](#sorting-column-customization)
6. [Themes & Terminal Display Options](#themes-terminal-display-options)
7. [JSON Export & Automation (`jq`)](#json-export-automation-jq)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **Visual Progress Bars**: Displays colored progress bars for disk usage percentage.
- **Auto-Grouping**: Automatically categorizes mounts into local storage, network shares, fuse mounts, and special devices.
- **Smart Formatting**: Dynamically scales byte units (GB, TB) and auto-detects terminal width.
- **JSON Export**: Provides structured JSON outputs for system scripts and monitoring dashboards.

---

## ⚙️ Installation via Homebrew

```bash
# Install duf on macOS
brew install duf

# Verify installation
duf --version
```

---

## 🚀 Basic Usage & Output Layout

```bash
# View disk usage for all mounted volumes
duf

# Check disk usage for a specific path or volume
duf /
duf /System/Volumes/Data
```

---

## 🎯 Device & Filesystem Filtering

Filter out virtual filesystems (devfs, autofs) or restrict output to specific categories.

### 1. Filter by Devices (`--only` / `--hide`)
```bash
# Show local physical disks only (APFS, HFS+, ext4)
duf --only local

# Show network mounts only (SMB, NFS)
duf --only network

# Hide specific filesystems (e.g. devfs, autofs)
duf --hide-fs devfs,autofs,tmpfs
```

### 2. Filter by Mount Points (`--only-mp` / `--hide-mp`)
```bash
# Show root and home directory mounts only
duf --only-mp /,/System/Volumes/Data

# Hide specific mount paths
duf --hide-mp /Volumes/TimeMachine
```

---

## 📊 Sorting & Column Customization

### 1. Sort Mounts (`--sort`)
Sort output by `mountpoint`, `size`, `used`, `avail`, `usage`, or `filesystem`:

```bash
# Sort filesystems by total capacity (descending)
duf --sort size

# Sort by percentage used
duf --sort usage
```

### 2. Customize Columns (`--output`)
Specify which columns to display:

```bash
# Display only filesystem, size, usage, and mount point
duf --output filesystem,size,usage,mountpoint
```

---

## 🎨 Themes & Terminal Display Options

```bash
# Force dark color theme
duf --theme dark

# Force light color theme (for light terminal backgrounds)
duf --theme light

# Disable color output (plain text mode)
duf --theme highlight

# Set explicit terminal width
duf --width 120
```

---

## 🤖 JSON Export & Automation (`jq`)

Generate structured JSON data to check available disk space programmatically.

```bash
# Raw JSON output
duf --json

# Extract mount point and free space in GB using jq
duf --json | jq -r '.[] | "\(.mountpoint): \(.avail / 1073741824 | round) GB available"'
```

Example shell warning script if root usage exceeds 85%:

```bash
#!/bin/bash
usage=$(duf --json / | jq -r '.[0].usage')
usage_percent=$(python3 -c "print(int(${usage} * 100))")

if [ "$usage_percent" -gt 85 ]; then
  echo "WARNING: Low disk space on /! Usage at ${usage_percent}%"
fi
```

## Everyday Copy-and-Paste `duf` Snippets & Shell Integration

```bash
# 1. Quick overview of physical disks sorted by highest percentage used
duf --only local --sort usage

# 2. Display filesystem name, size, used, available, and mount point only
duf --output filesystem,size,used,avail,mountpoint

# 3. Export structured JSON disk space report to a file
duf --json > /tmp/disk_report.json

# 4. Check available storage on root directory in gigabytes using jq
duf --json / | jq -r '.[0] | "\(.mountpoint): \((.avail / 1073741824) | round) GB free"'

# 5. Shell alias overrides for df replacement (Add to ~/.zshrc)
alias df='duf'
alias df-local='duf --only local'
alias df-sort='duf --sort size'

# 6. Automated Bash Disk Warning Script (Cron compatible)
cat << 'EOF' > ~/check_disk.sh
#!/bin/bash
threshold=85
usage=$(duf --json / | jq -r '.[0].usage * 100 | round')

if [ "$usage" -ge "$threshold" ]; then
    echo "CRITICAL: Root disk partition usage is at ${usage}%!" | mail -s "Disk Space Alert" admin@example.com
fi
EOF
chmod +x ~/check_disk.sh
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Default Overview | `duf` |
| Local Disks Only | `duf --only local` |
| Sort by Capacity | `duf --sort size` |
| Hide System Mounts | `duf --hide-fs devfs,autofs` |
| Light Theme Mode | `duf --theme light` |
| Specific Path Usage | `duf /System/Volumes/Data` |
| Output JSON | `duf --json` |
