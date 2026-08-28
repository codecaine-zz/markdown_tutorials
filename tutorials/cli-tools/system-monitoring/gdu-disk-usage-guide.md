# GDU (Go Disk Usage) Fast Analyzer Guide

`gdu` (**Go Disk Usage**) is an ultra-fast, multi-threaded disk usage analyzer with a console user interface written in Go. Specially optimized for modern SSDs and NVMe drives, `gdu` leverages parallel processing across multiple CPU cores to scan massive directory trees significantly faster than traditional tools like `du` or `ncdu`.

---

## 📚 Table of Contents

1. [Overview & Key Features](#overview--key-features)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Running `gdu` & Interactive TUI Interface](#running-gdu--interactive-tui-interface)
4. [Interactive Navigation & Keyboard Shortcuts](#interactive-navigation--keyboard-shortcuts)
5. [Scanning Options & Filtering](#scanning-options--filtering)
6. [Mounted Disks & Storage Overview](#mounted-disks--storage-overview)
7. [Non-Interactive CLI Mode & Scripting](#non-interactive-cli-mode--scripting)
8. [Embedded Web UI Mode](#embedded-web-ui-mode)
9. [Interactive Cleanup Walkthrough](#interactive-cleanup-walkthrough)
10. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Key Features

| Feature | `gdu` | `ncdu` | Traditional `du` |
| :--- | :--- | :--- | :--- |
| **Language** | Go (concurrent) | C / Zig (single-threaded) | C (single-threaded) |
| **Performance** | Multi-threaded (optimized for SSDs) | Sequential | Sequential |
| **Interface** | TUI + Web UI + CLI | TUI | Text output only |
| **Mounted Disks View** | Yes (`-d`) | No | No (`df` required) |
| **Archive Browsing** | Yes (tar/zip) | No | No |
| **Export / Import** | JSON / Database | JSON / GZ | Plain text |

- **Multi-Core Parallelism**: Automatically utilizes all available CPU cores to crawl filesystems concurrently.
- **Interactive TUI**: Intuitive ncurses-style terminal interface with live visual bar meters and instant navigation.
- **Read-Only / Safe Mode**: Protection against accidental file deletion in production or sensitive environments.
- **Direct Disk / Mounts Inspection**: View all mounted physical drives and partitions at a glance.
- **Embedded Web GUI**: Spin up a browser-based visualization with a single flag (`--web`).
- **Archive Inspection**: Inspect contents of `.tar`, `.tar.gz`, and `.zip` archives without extracting them.

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Verify Homebrew Installation

Ensure you have [Homebrew](https://brew.sh/) installed on your macOS or Linux system:

```bash
brew --version
```

### 2. Install `gdu`

Install `gdu` using Homebrew:

```bash
brew install gdu
```

### 3. Handle Binary Name Caveat (`gdu` vs `gdu-go`)

> [!NOTE]
> On macOS, Homebrew may link the executable as `gdu-go` to prevent naming conflicts with GNU Coreutils' `gdu` binary.

Verify which command is available:

```bash
# Check for gdu or gdu-go
which gdu || which gdu-go
```

If Homebrew installed it as `gdu-go`, add an alias to your `~/.zshrc` (or `~/.bashrc`) so you can type `gdu` naturally:

```bash
# Add alias to your shell configuration
echo "alias gdu='gdu-go'" >> ~/.zshrc

# Reload shell configuration
source ~/.zshrc
```

Verify the version:

```bash
gdu --version
# or: gdu-go --version
```

---

## 🚀 Running `gdu` & Interactive TUI Interface

Launch `gdu` on the current directory or target a specific folder:

```bash
# Scan current working directory
gdu

# Scan a user's home folder
gdu ~

# Scan the system root (use sudo to analyze system directories)
sudo gdu /

# Scan a specific project folder
gdu /Users/username/Projects
```

### Visual Layout

When `gdu` finishes scanning, it renders an interactive file tree sorted by size:

```text
   gdu 5.37.0 ~ Use arrow keys to navigate, ? for help
--- /Users/username/Projects ----------------------------------
   5.2 GiB [##########] /docker-volumes
   2.1 GiB [####      ] /node-applications
 980.5 MiB [#         ] /ml-datasets
 120.4 MiB [          ] /go-microservices
  45.2 MiB [          ] /documentation
   1.5 MiB [          ]  docker-compose.yml
 250.0 KiB [          ]  package-lock.json

 Total disk usage: 8.4 GiB  Apparent size: 9.1 GiB  Items: 48,920
```

---

## ⌨️ Interactive Navigation & Keyboard Shortcuts

Inside the interactive TUI, navigate and manage items with these keybindings:

### Navigation
- `▲` / `k` : Move selection up
- `▼` / `j` : Move selection down
- `Enter` / `►` / `l` : Open directory or view archive contents
- `◄` / `h` : Go back to parent directory
- `g` / `Home` : Jump to the top item
- `G` / `End` : Jump to the bottom item

### File Operations
- `d` : **Delete** selected file or folder (prompts for confirmation)
- `D` : **Delete without confirmation** (use with extreme caution)
- `e` : **Empty** directory or truncate file to 0 bytes
- `v` : **View** text file contents in built-in pager
- `i` : **Information** modal with full path, size, apparent size, permissions, and item count
- `s` : Spawn a shell in the selected directory

### Sorting & Display Modes
- `s` : Sort by **size** (descending / ascending)
- `n` : Sort by **name** (alphabetical)
- `c` : Sort by **item count**
- `m` : Sort by **mtime** (last modified time)
- `a` : Toggle between **disk usage** and **apparent size**
- `B` : Toggle **relative percentage bar** display
- `C` : Toggle showing directory **item counts**
- `M` : Toggle showing directory **mtime**
- `r` : **Rescan** current directory

### General
- `?` : Display the interactive **Help** screen
- `q` : **Quit** `gdu`

---

## 🎯 Scanning Options & Filtering

Tailor the scanning process to ignore unwanted folders, skip remote filesystems, or bypass hidden directories.

### 1. Ignore Specific Directories or Patterns (`-i` / `-I`)

```bash
# Ignore standard temporary/virtual system paths
gdu -i /proc,/dev,/sys,/run /

# Ignore development artifact folders using comma-separated patterns
gdu -I node_modules,.git,vendor,dist ~
```

### 2. Ignore Hidden Files and Folders (`-H`)

```bash
# Skip dotfiles and dotdirectories (e.g. .cache, .npm, .git)
gdu -H ~/
```

### 3. Exclude by File Extension (`-E`)

```bash
# Ignore video and archive files during scan
gdu -E mp4,mkv,zip,tar.gz ~/Downloads
```

### 4. Do Not Cross Filesystem Boundaries (`-x`)

```bash
# Stay within the root filesystem; do not traverse external disks or network mounts
sudo gdu -x /
```

### 5. Safe Read-Only Mode (`--no-delete`)

```bash
# Disable file deletion shortcuts in the TUI to prevent accidental data loss
gdu --no-delete /var/log
```

---

## 💽 Mounted Disks & Storage Overview

`gdu` includes a built-in mounted disk viewer that works like an interactive `df -h`:

```bash
# Show list of all mounted storage devices, partitions, and external drives
gdu -d
```

### Example Disks View:

```text
--- Mounted Disks ---------------------------------------------
Device           Type   Size      Used      Free      Mount point
/dev/disk3s1s1   apfs   460.4 GiB 240.2 GiB 220.2 GiB /
/dev/disk3s5     apfs   460.4 GiB 180.1 GiB 220.2 GiB /System/Volumes/Data
/dev/disk4s1     exfat  1.8 TiB   620.5 GiB 1.2 TiB   /Volumes/ExternalSSD
```

Select any drive from the list and press `Enter` to instantly start scanning its contents.

---

## 🤖 Non-Interactive CLI Mode & Scripting

Run `gdu` in headless environments, CI/CD pipelines, or scripts without launching the TUI.

### 1. Summary of Directory Usage (`-n` / `-s`)

```bash
# Print summary of directory size in non-interactive mode
gdu -n -s ~/Projects
```

### 2. List Top N Largest Files/Folders (`-t`)

```bash
# Output top 10 largest items in ~/Downloads
gdu -n -t 10 ~/Downloads
```

### 3. Filter by Modification Date (`--max-age` / `--min-age`)

```bash
# Find space taken by files older than 30 days
gdu -n --min-age 30d /var/log

# Scan files modified within the last 7 days
gdu -n --max-age 7d ~/Projects
```

### 4. Export & Import Scan Reports (`-o` / `-f`)

```bash
# Export scan data to a JSON file for analysis
gdu -o ~/disk_analysis.json /var/www

# Re-open and browse a saved report instantly without rescanning
gdu -f ~/disk_analysis.json
```

---

## 🌐 Embedded Web UI Mode

`gdu` can spin up a local web server with an interactive web GUI for visual browsing in your default browser:

```bash
# Start web UI and open automatically in browser
gdu --web ~/

# Start web UI on a custom port without auto-opening browser
gdu --web --web-listen localhost:8080 --web-open=false /data
```

Once started, open `http://localhost:8080` in your web browser to explore interactive charts and storage breakdowns.

---

## 🧹 Interactive Cleanup Walkthrough

Step-by-step example for cleaning up massive files in your home directory:

1. **Launch `gdu` on your home folder:**
   ```bash
   gdu ~
   ```
2. **Identify heavy folders:**
   Use `▼` (`j`) to navigate down to the largest directory (e.g. `~/Downloads` or `~/Library/Caches`).
3. **Drill into the directory:**
   Press `Enter` or `►` (`l`) to open the folder.
4. **Inspect details:**
   Highlight a suspect `.dmg` or `.iso` file and press `i` to see its file size and timestamps.
5. **Delete the file:**
   Press `d`. A confirmation dialog will appear:
   ```text
   Are you sure you want to delete "/Users/username/Downloads/installer.iso"? (y/N)
   ```
   Press `y` to confirm deletion. `gdu` deletes the file and recalculates directory totals in real-time.
6. **Exit:**
   Press `q` when finished.

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Scan Current Dir** | `gdu` |
| **Scan Specific Path** | `gdu /path/to/dir` |
| **View Mounted Disks** | `gdu -d` |
| **Ignore Git & Node Modules** | `gdu -I node_modules,.git` |
| **Read-Only Inspection** | `gdu --no-delete /` |
| **Top 10 Largest Items** | `gdu -n -t 10 /path` |
| **Export to JSON** | `gdu -o report.json /path` |
| **View Exported JSON** | `gdu -f report.json` |
| **Launch Web UI** | `gdu --web /path` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Compatibility alias for Homebrew gdu-go installation
alias gdu='gdu-go'

# Quick disk usage commands
alias gdu-fast='gdu-go -I node_modules,.git,.cache'
alias gdu-disks='gdu-go -d'
alias gdu-safe='gdu-go --no-delete'
alias gdu-root='sudo gdu-go -x /'
alias gdu-top='gdu-go -n -t 10'
```

---

## 🗑️ Uninstallation

To remove `gdu` via Homebrew:

```bash
# Uninstall gdu
brew uninstall gdu

# Clean up any leftover Homebrew cache
brew cleanup
```
