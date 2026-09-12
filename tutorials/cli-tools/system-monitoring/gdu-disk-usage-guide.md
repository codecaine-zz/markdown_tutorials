# GDU (gdu-go) Fast Disk Usage Analyzer Complete Guide

`gdu` (**Go Disk Usage**) is an ultra-fast, multi-threaded disk usage analyzer with an interactive terminal user interface (TUI) written in Go. Specially designed and optimized for modern SSDs and NVMe drives, `gdu` crawls directory trees concurrently across all available CPU cores—delivering scanning speeds many times faster than legacy single-threaded tools like `du` or `ncdu`.

When installed via **Homebrew**, the binary is purposefully installed and linked as **`gdu-go`** to prevent severe naming collisions with GNU Coreutils' `gdu` (GNU `du`). This guide provides a complete, production-ready tutorial for installing `gdu` with Homebrew, understanding the binary conflict, using `gdu-go` across all workflows, and mastering every feature from interactive TUI navigation to scripting, database exports, and embedded web visualization.

---

## 📚 Table of Contents

1. [Why `gdu-go`? The Homebrew Conflict Explained](#why-gdu-go-the-homebrew-conflict-explained)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Conflict-Free Shell Configuration](#conflict-free-shell-configuration)
4. [macOS Permissions: Full Disk Access](#macos-permissions-full-disk-access)
5. [Interactive TUI Navigation & Keybindings](#interactive-tui-navigation-keybindings)
6. [Mounted Disks & Storage Overview (`-d`)](#mounted-disks-storage-overview--d)
7. [Archive Browsing (`--archive-browsing`)](#archive-browsing---archive-browsing)
8. [Scanning Options, Exclusions & Boundary Controls](#scanning-options-exclusions-boundary-controls)
9. [Time-Based Filtering & Log Analysis](#time-based-filtering-log-analysis)
10. [Non-Interactive CLI Mode & Scripting](#non-interactive-cli-mode-scripting)
11. [Database Persistence & JSON Reports](#database-persistence-json-reports)
12. [Embedded Web UI Mode (`--web`)](#embedded-web-ui-mode---web)
13. [Configuration File Management (`~/.gdu.yaml`)](#configuration-file-management-gduyaml)
14. [Real-World Developer Cleanup Recipes](#real-world-developer-cleanup-recipes)
15. [Everyday Cheat Sheet & Tool Comparison](#everyday-cheat-sheet-tool-comparison)
16. [Uninstallation & Housekeeping](#uninstallation-housekeeping)

---

## ⚠️ Why `gdu-go`? The Homebrew Conflict Explained

When you install the `gdu` package via Homebrew (`brew install gdu`), the formula creates the binary under the name **`gdu-go`**, rather than `gdu`. 

### The Root Cause: GNU Coreutils Naming Convention

On macOS and BSD systems, the GNU Coreutils suite (provided by Homebrew's `coreutils` formula) installs GNU versions of standard UNIX commands with a `g` prefix to avoid clashing with the operating system's native BSD utilities:

| BSD / macOS Default | GNU Coreutils Command | Description |
| :--- | :--- | :--- |
| `ls` | `gls` | GNU List Directory Contents |
| `cp` | `gcp` | GNU Copy |
| `tar` | `gtar` | GNU Tape Archive |
| `sed` | `gsed` | GNU Stream Editor |
| `date` | `gdate` | GNU Date utility |
| **`du`** | **`gdu`** | **GNU Disk Usage (`du`)** |

Because **`gdu`** already stands for **GNU `du`** in the UNIX ecosystem, having Go Disk Usage also claim the command name `gdu` causes severe naming conflicts:
- Scripts, Makefiles, or build tools that invoke GNU `du` as `gdu` would accidentally execute Go Disk Usage instead.
- If Go Disk Usage claimed `gdu`, installing or linking `coreutils` would cause Homebrew link errors (`brew link` collision).

### Homebrew's Upstream Decision

Homebrew's official formula definition for `gdu` explicitly handles this:

```ruby
system "go", "build", *std_go_args(ldflags:, output: bin/"gdu-go"), "./cmd/gdu"
man1.install "gdu.1" => "gdu-go.1"
```

Homebrew outputs this explicit caveat upon installation:
> **Caveat:** To avoid a conflict with `coreutils`, `gdu` has been installed as `gdu-go`.

### Diagnosing Your Environment

You can verify which binaries exist on your path:

```bash
# Check where gdu-go is installed
which gdu-go

# Check if a conflicting gdu binary exists (from coreutils or manual install)
which gdu
```

- On **Apple Silicon Macs** (M1/M2/M3/M4): `/opt/homebrew/bin/gdu-go`
- On **Intel Macs**: `/usr/local/bin/gdu-go`
- On **Linuxbrew**: `/home/linuxbrew/.linuxbrew/bin/gdu-go`

> [!IMPORTANT]
> To prevent breaking GNU `coreutils` or encountering "command not found" errors, you should use **`gdu-go`** as the official command name in all your terminal workflows, scripts, and automation.

---

## ⚙️ Homebrew Installation & Verification

### 1. Install `gdu` via Homebrew

Ensure your Homebrew formula index is updated, then install `gdu`:

```bash
# Update Homebrew indices
brew update

# Install Go Disk Usage
brew install gdu
```

### 2. Inspect Package Metadata

Confirm the formula details and the caveat message:

```bash
brew info gdu
```

Example output:
```text
==> gdu: stable 5.37.0 (bottled), HEAD
Disk usage analyzer with console interface written in Go
https://github.com/dundee/gdu
==> Caveats
To avoid a conflict with `coreutils`, `gdu` has been installed as `gdu-go`.
```

### 3. Verify Version and Execution

Run `gdu-go -v` to ensure the Go runtime binary executed properly:

```bash
gdu-go -v
```

Output:
```text
Version:     v5.37.0
Built time:  2026-08-18 00:47:38 UTC
Built user:  brew
```

### 4. Read the Built-in Manpage

Homebrew also renames the manual page to `gdu-go.1`:

```bash
man gdu-go
```

---

## 🐚 Conflict-Free Shell Configuration

Depending on whether you have GNU `coreutils` installed, choose the shell configuration approach that matches your setup.

### Option 1: Use `gdu-go` Directly (Recommended)

Using `gdu-go` directly guarantees zero conflicts with GNU Coreutils, other scripts, or remote servers:

```bash
gdu-go ~
```

### Option 2: Safe Conditional Alias (Only If `gdu` is Unused)

If you do **not** use GNU `coreutils` and want the shorter three-letter command `gdu`, add a safe check to your shell configuration (`~/.zshrc` for macOS Zsh or `~/.bashrc` for Bash):

```bash
# Add this snippet to ~/.zshrc or ~/.bashrc
if ! command -v gdu >/dev/null 2>&1 && command -v gdu-go >/dev/null 2>&1; then
    alias gdu='gdu-go'
fi
```

This prevents overriding `gdu` if `coreutils` is ever installed later. Apply the changes:

```bash
source ~/.zshrc   # or: source ~/.bashrc
```

### Option 3: Shell Autocompletion for `gdu-go`

If you use `zsh` or `bash`, you can set up autocompletion for `gdu-go` flags:

```bash
# For Zsh (macOS default):
mkdir -p ~/.zsh/completion
gdu-go --help > /dev/null # verify execution
```

---

## 🔒 macOS Permissions: Full Disk Access

On macOS, scanning system-level paths like `/Library`, `/System/Volumes/Data`, or user directories like `~/Library/Application Support` and `~/Library/Mail` will result in `Operation not permitted` errors due to Apple's **System Integrity Protection (SIP)** and privacy sandboxing.

To give `gdu-go` permission to inspect your entire drive:

1. Open **System Settings** on your Mac.
2. Navigate to **Privacy & Security** > **Full Disk Access**.
3. Click the **`+`** icon (enter your macOS password / Touch ID).
4. Add your terminal emulator:
   - **Terminal** (`/System/Applications/Utilities/Terminal.app`)
   - **iTerm2** (`/Applications/iTerm.app`)
   - **Ghostty** / **Alacritty** / **Kitty**
5. Restart your terminal emulator.

Now `gdu-go` can read all directories when run as root:

```bash
# Scan full system root without permissions errors
sudo gdu-go -x /
```

> [!NOTE]
> The `-x` flag prevents `gdu-go` from traversing into external drives, Time Machine snapshots, network mounts, or read-only APFS system snapshots.

---

## 🖥️ Interactive TUI Navigation & Keybindings

Launch `gdu-go` on any target folder to enter the full-screen terminal interface:

```bash
# Scan current directory
gdu-go

# Scan user home directory
gdu-go ~

# Scan a specific development directory
gdu-go ~/Projects
```

### The Terminal UI Layout

```text
   gdu 5.37.0 ~ Use arrow keys to navigate, ? for help
--- /Users/username/Projects ----------------------------------
   4.2 GiB [##########] /docker-volumes
   1.8 GiB [####      ] /node-apps
 850.2 MiB [#         ] /rust-builds
 110.0 MiB [          ] /go-services
  12.4 MiB [          ]  docker-compose.yml
   1.2 MiB [          ]  package-lock.json

 Total disk usage: 6.9 GiB  Apparent size: 7.4 GiB  Items: 32,410
```

### Visual Size Indicators

- **`[##########]`**: Relative bar showing the percentage of disk space occupied by the item relative to the largest sibling in the same folder.
- **Color Coding**: Large items display in bold red or yellow, medium items in cyan, and small files in gray/white.
- **Footer**: Displays total real disk usage, apparent size, and total file/folder item count.

### Keyboard Shortcuts Reference Table

| Key | Context | Action |
| :--- | :--- | :--- |
| `▲` / `k` | Navigation | Move selection up |
| `▼` / `j` | Navigation | Move selection down |
| `Enter` / `►` / `l` | Navigation | Open directory or enter archive |
| `◄` / `h` | Navigation | Return to parent directory |
| `g` / `Home` | Navigation | Jump to the very top item |
| `G` / `End` | Navigation | Jump to the bottom item |
| `d` | File Action | **Delete** selected file/folder (prompts confirmation) |
| `D` | File Action | **Delete immediately** without confirmation (use caution!) |
| `e` | File Action | **Empty** directory or truncate file to 0 bytes |
| `v` | File Action | **View** file contents in built-in pager |
| `i` | Inspection | Open **Item Details** modal (path, size, perms, mtime) |
| `s` | Subshell | Spawn a subshell inside the selected directory |
| `s` (in sort) | Sorting | Sort by **Size** (toggle descending / ascending) |
| `n` | Sorting | Sort by **Name** (alphabetical) |
| `c` | Sorting | Sort by **Item Count** |
| `m` | Sorting | Sort by **mtime** (last modification time) |
| `a` | Display | Toggle between **Actual Disk Usage** and **Apparent Size** |
| `B` | Display | Toggle **Progress Bar** meter column |
| `C` | Display | Toggle **Item Count** column |
| `M` | Display | Toggle **Last Modified Time** column |
| `r` | Rescan | Rescan the current directory from disk |
| `?` | Help | Show interactive help modal |
| `q` | Exit | Quit `gdu-go` |

### Enabling Mouse Support

`gdu-go` supports clicking and scrolling in mouse-enabled terminals:

```bash
gdu-go --mouse ~/Downloads
```

### Safe / Read-Only Modes

To safely inspect servers or sensitive folders without risking accidental deletion:

```bash
# Disable file and directory deletion shortcuts
gdu-go --no-delete /var/log

# Completely lock down: disable deletion, viewing files, and spawning shells
gdu-go --no-delete --no-view-file --no-spawn-shell /
```

---

## 💽 Mounted Disks & Storage Overview (`-d`)

`gdu-go` features a built-in mounted disk browser that serves as an interactive, live replacement for `df -h`:

```bash
gdu-go -d
```

### Example Mounted Disks Interface

```text
--- Mounted Disks ----------------------------------------------------
Device           Type   Size       Used       Free       Mount point
/dev/disk3s1s1   apfs   465.6 GiB  240.5 GiB  225.1 GiB  /
/dev/disk3s5     apfs   465.6 GiB  185.2 GiB  225.1 GiB  /System/Volumes/Data
/dev/disk4s1     exfat    1.8 TiB  650.0 GiB    1.1 TiB  /Volumes/SamsungT7
```

**Workflow:**
1. Run `gdu-go -d`.
2. Use `▲` / `▼` to highlight any internal partition or connected external drive.
3. Press `Enter` to start an immediate deep-dive scan on that specific mount point.

---

## 📦 Archive Browsing (`--archive-browsing`)

One of `gdu-go`'s standout capabilities is inspecting compressed archives directly inside the TUI without manual extraction.

```bash
gdu-go --archive-browsing ~/Downloads
```

### Supported Archive Formats
- `.zip`
- `.tar`
- `.tar.gz` / `.tgz`
- `.tar.bz2` / `.tbz2`
- `.tar.xz` / `.txz`
- `.jar`

When you highlight a supported archive in the TUI and press `Enter` or `►`, `gdu-go` opens the archive as if it were a normal folder, displaying file sizes and directory structures inside the compressed payload.

---

## 🎯 Scanning Options, Exclusions & Boundary Controls

### 1. Ignore Specific Directories (`-i`)

Exclude specific paths from being indexed (defaults to system virtual filesystems `/proc,/dev,/sys,/run`):

```bash
# Ignore macOS Spotlight and Trash folders
gdu-go -i .Trash,.Spotlight-V100 ~/

# Scan root while ignoring virtual mounts
sudo gdu-go -i /dev,/proc,/sys,/System/Volumes/VM -x /
```

### 2. Ignore Path Patterns (`-I`)

Use comma-separated substring patterns to skip heavy build or dependency folders:

```bash
gdu-go -I "node_modules,.git,vendor,target,dist,build" ~/Projects
```

### 3. Exclude Patterns via File (`-X`)

Store your exclusion rules in an ignore file (e.g. `.gduignore`):

```text
# ~/.gduignore
node_modules
.git
.cache
DerivedData
*.iso
*.dmg
```

Run with:

```bash
gdu-go -X ~/.gduignore ~
```

### 4. Filter by File Extensions (`-E` and `-T`)

```bash
# Exclude large media and disk image files from the scan
gdu-go -E "mp4,mkv,iso,dmg,tar" ~/Downloads

# Only include specific file types (e.g. log and temporary files)
gdu-go -T "log,tmp,bak" /var/log
```

### 5. Ignore Hidden Files and Folders (`-H`)

Skip dotfiles and hidden folders (such as `.npm`, `.cache`, `.rustup`):

```bash
gdu-go -H ~/
```

### 6. Stay Within Filesystem Boundaries (`-x`)

Prevent `gdu-go` from traversing into attached USB keys, SMB network shares, or APFS snapshot mounts:

```bash
sudo gdu-go -x /
```

### 7. Following Symlinks (`-L`)

By default, `gdu-go` does not count symlinked file targets to avoid duplicate tallying. Use `-L` to calculate target file sizes:

```bash
gdu-go -L ~/my-links
```

---

## ⏳ Time-Based Filtering & Log Analysis

`gdu-go` allows you to analyze disk consumption based on file modification times (`mtime`).

### 1. Identify Stale Files (`--min-age`)

Find files that haven't been modified in a long time (e.g. older than 30 days or 1 year):

```bash
# Find disk space consumed by files unmodified for at least 30 days
gdu-go -n --min-age 30d /var/log

# Scan user downloads older than 6 months
gdu-go -n --min-age 6mo ~/Downloads
```

### 2. Identify Recently Modified Files (`--max-age`)

Find files modified within a recent window:

```bash
# Scan files created or modified in the last 24 hours
gdu-go -n --max-age 24h ~/Projects

# Scan items touched within the past 7 days
gdu-go -n --max-age 7d /tmp
```

### 3. Absolute Date Ranges (`--since` and `--until`)

Filter files using calendar dates (`YYYY-MM-DD`) or ISO-8601 timestamps:

```bash
# Find files modified since January 1st, 2026
gdu-go -n --since 2026-01-01 ~/Documents

# Find files modified between two dates
gdu-go -n --since 2025-01-01 --until 2025-12-31 ~/Archive
```

---

## 🤖 Non-Interactive CLI Mode & Scripting

For headless servers, automation scripts, and CI/CD pipelines, `gdu-go` provides a robust non-interactive mode (`-n`).

### 1. Directory Usage Summary (`-s`)

Get the total disk usage of a directory cleanly:

```bash
gdu-go -n -s ~/Projects
```

Output:
```text
8.4 GiB /Users/username/Projects
```

### 2. Top N Largest Files or Folders (`-t`)

List the top space consumers in a directory:

```bash
# Top 10 largest items in ~/Downloads
gdu-go -n -t 10 ~/Downloads
```

Sample output:
```text
 3.2 GiB  Xcode_16.xip
 1.8 GiB  Ubuntu_24.04.iso
 750 MiB  Dataset_2026.tar.gz
 420 MiB  Docker.dmg
```

### 3. Limiting Directory Depth (`--depth`)

Control how many levels of subdirectories to inspect in non-interactive mode:

```bash
gdu-go -n --depth 2 ~/Projects
```

### 4. Decimal SI Prefixes (`--si`)

Display sizes in standard decimal units (1 kB = 1,000 bytes; MB, GB) instead of binary units (KiB, MiB, GiB):

```bash
gdu-go -n -s --si ~/Projects
```

### 5. Raw Byte Output for Machine Parsing (`--no-prefix`)

Emit raw byte integers without units for shell piping, `awk`, or database ingestion:

```bash
gdu-go -n -s --no-prefix ~/Projects
```

### 6. Automated Health Check Script Example

Here is a shell script using `gdu-go` to alert if user storage exceeds a threshold:

```bash
#!/usr/bin/env bash
# disk-alert.sh: Check folder usage using gdu-go
TARGET_DIR="${1:-$HOME}"
MAX_GIB=50

# Get usage in GiB (non-interactive, no progress bar)
USAGE_RAW=$(gdu-go -n -s --no-prefix -p "$TARGET_DIR" 2>/dev/null | awk '{print $1}')
USAGE_GIB=$(( USAGE_RAW / 1024 / 1024 / 1024 ))

echo "Checked: $TARGET_DIR | Usage: ${USAGE_GIB} GiB"

if [ "$USAGE_GIB" -gt "$MAX_GIB" ]; then
    echo "WARNING: $TARGET_DIR exceeds limit of ${MAX_GIB} GiB!"
    echo "Top 5 culprits:"
    gdu-go -n -t 5 -p "$TARGET_DIR"
fi
```

---

## 💾 Database Persistence & JSON Reports

Rather than rescanning massive millions-of-file trees repeatedly, `gdu-go` can persist scans into embedded databases (SQLite or BadgerDB) or JSON files.

### 1. Storing Scans into an SQLite Database (`-D`)

```bash
# Scan and save the snapshot into an SQLite database
gdu-go -D disk_scan.sqlite ~/Projects
```

### 2. Re-Opening an Existing Database (`-r`)

Re-open and browse a saved snapshot instantly without touching the actual filesystem:

```bash
# Read directly from storage
gdu-go -r -D disk_scan.sqlite
```

### 3. Exporting to JSON (`-o`)

Generate an export file for external auditing, reporting, or archiving:

```bash
# Export full scan to JSON
gdu-go -o /tmp/audit_report.json ~/Downloads

# Export with specific attributes only (name, apparent size, disk size, mtime)
gdu-go -o /tmp/audit_report.json --output-attrs name,asize,dsize,mtime ~/Downloads
```

### 4. Viewing an Exported JSON File (`-f`)

Load and browse a JSON scan in the interactive TUI:

```bash
gdu-go -f /tmp/audit_report.json
```

---

## 🌐 Embedded Web UI Mode (`--web`)

`gdu-go` includes a built-in HTTP server that serves an interactive web dashboard with visual breakdown charts:

```bash
# Launch web interface and automatically open in your default browser
gdu-go --web ~/
```

### Custom Port & Headless Server Binding

To serve the web dashboard on a headless machine or remote server without attempting to open a local desktop browser:

```bash
# Listen on all interfaces on port 8080 without opening browser
gdu-go --web --web-listen 0.0.0.0:8080 --web-open=false /var/www
```

Navigate to `http://<server-ip>:8080` in any web browser to view interactive visual charts of your filesystem.

---

## ⚙️ Configuration File Management (`~/.gdu.yaml`)

You can save your favorite flags permanently in a configuration file so `gdu-go` always defaults to your preferred settings.

### 1. Generate Configuration Automatically

Configure your preferred settings and write them directly:

```bash
gdu-go --mouse --archive-browsing -I "node_modules,.git" --write-config
```

This creates or updates `$HOME/.gdu.yaml`.

### 2. Example `~/.gdu.yaml` Syntax

You can edit `~/.gdu.yaml` directly:

```yaml
# ~/.gdu.yaml
no-color: false
mouse: true
archive-browsing: true
show-relative-size: true
show-item-count: true
ignore-dirs-pattern:
  - "node_modules"
  - ".git"
  - "DerivedData"
  - ".cache"
```

### 3. Specify a Custom Config File

```bash
gdu-go --config-file ~/configs/gdu-server.yaml /data
```

---

## 🧹 Real-World Developer Cleanup Recipes

Here is how to use `gdu-go` to reclaim tens or hundreds of gigabytes from common developer storage hogs on macOS:

### 1. Cleaning Xcode DerivedData & Simulator Caches

Xcode commonly accumulates 40–100 GB of build caches:

```bash
# Inspect Xcode caches
gdu-go ~/Library/Developer/Xcode/DerivedData

# Inspect Simulator runtimes and caches
gdu-go ~/Library/Developer/CoreSimulator/Caches
```
*Navigate to the largest folders and press `d` to delete.*

### 2. Cleaning Orphaned Docker Volumes & Virtual Disk

Docker on macOS stores its virtual drive in a massive disk image:

```bash
# Inspect Docker VM storage on macOS
gdu-go ~/Library/Containers/com.docker.docker/Data/vms
```

To clean Docker data from your terminal:
```bash
docker system prune -a --volumes
```

### 3. Hunting Down Giant `node_modules` Across All Projects

Find where heavy JavaScript projects are hiding:

```bash
# Scan your development workspace displaying top folders
gdu-go -n -t 15 ~/Projects
```

### 4. Clearing Homebrew Download Caches

Homebrew stores downloaded bottled binaries and source tarballs:

```bash
# Inspect Homebrew cache size
gdu-go $(brew --cache)

# Purge all stale downloaded formula archives
brew cleanup -s
```

---

## 📋 Everyday Cheat Sheet & Tool Comparison

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Scan Current Directory** | `gdu-go` |
| **Scan User Home Directory** | `gdu-go ~` |
| **Scan Root Filesystem (Safe)** | `sudo gdu-go -x /` |
| **Browse Mounted Disks (`df` replacement)** | `gdu-go -d` |
| **Enable Mouse Support** | `gdu-go --mouse /path` |
| **Browse Inside Compressed Archives** | `gdu-go --archive-browsing /path` |
| **Ignore Git & Node Modules** | `gdu-go -I "node_modules,.git"` |
| **Safe Read-Only Mode** | `gdu-go --no-delete /path` |
| **Non-Interactive Summary** | `gdu-go -n -s /path` |
| **Top 10 Largest Items** | `gdu-go -n -t 10 /path` |
| **Filter Files Older than 30 Days** | `gdu-go -n --min-age 30d /path` |
| **Save Scan to SQLite Database** | `gdu-go -D scan.sqlite /path` |
| **Re-Open Saved SQLite Scan** | `gdu-go -r -D scan.sqlite` |
| **Export Scan to JSON** | `gdu-go -o scan.json /path` |
| **Browse Saved JSON Report** | `gdu-go -f scan.json` |
| **Launch Browser Web UI** | `gdu-go --web /path` |

### Tool Comparison Matrix

| Feature | `gdu-go` | `ncdu` | `dust` | Traditional `du` | GNU `gdu` (coreutils) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Package** | `brew install gdu` | `brew install ncdu` | `brew install dust` | Built-in macOS | `brew install coreutils` |
| **Binary Name** | **`gdu-go`** | `ncdu` | `dust` | `du` | `gdu` |
| **Language** | Go (concurrent) | C / Zig (sequential) | Rust | C (POSIX) | C (GNU) |
| **Multi-Core SSD Parallelism** | **Yes** | No | Yes | No | No |
| **Interactive TUI** | **Yes** | Yes | No (CLI tree) | No | No |
| **Mounted Disks View (`-d`)** | **Yes** | No | No | No | No |
| **Archive Browsing** | **Yes** (zip/tar) | No | No | No | No |
| **Embedded Web UI** | **Yes** (`--web`) | No | No | No | No |
| **Database Caching** | **Yes** (SQLite/Badger) | No | No | No | No |

---

## 🗑️ Uninstallation & Housekeeping

If you ever need to remove `gdu-go` and clean up configuration files:

```bash
# 1. Uninstall the formula via Homebrew
brew uninstall gdu

# 2. Clean up any leftover Homebrew download caches
brew cleanup

# 3. Remove configuration files (optional)
rm -f ~/.gdu.yaml

# 4. Remove any generated database snapshots
rm -f ~/disk_scan.sqlite
```
