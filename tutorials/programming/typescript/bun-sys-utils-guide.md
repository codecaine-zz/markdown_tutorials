# Bun System Utilities (`bun_sys_utils`) Complete Guide

`bun_sys_utils` is a modern, high-performance suite of command-line system utilities written in TypeScript and powered by native [Bun](https://bun.sh) APIs. Built following the **Single Responsibility (Doer vs. Coordinator)** pattern and **Feature-First** architecture, it provides drop-in, zero-dependency replacements for everyday Unix and DevOps CLI tools.

Repository: [codecaine-zz/bun_sys_utils](https://github.com/codecaine-zz/bun_sys_utils)

---

## 📚 Table of Contents

1. [Architectural Overview & Design Principles](#1-architectural-overview--design-principles)
2. [Included System Utilities](#2-included-system-utilities)
3. [Tool-by-Tool Usage & Examples](#3-tool-by-tool-usage--examples)
   - [`fd` - Fast & Intuitive File Finder](#1-fd--fast--intuitive-file-finder)
   - [`sd` - Intuitive Find & Replace](#2-sd--intuitive-find--replace)
   - [`rip` - Safe File Removal with Graveyard](#3-rip--safe-file-removal-with-graveyard)
   - [`procs` - Process Viewer & Interactive Manager](#4-procs--process-viewer--interactive-manager)
   - [`watchexec` - File Watcher & Command Runner](#5-watchexec--file-watcher--command-runner)
   - [`tokei` - Fast Code & LOC Counter](#6-tokei--fast-code--loc-counter)
   - [`gdu` / `gdu-go` - Disk Usage Analyzer](#7-gdu--gdu-go--disk-usage-analyzer)
   - [`ipinfo` - IP Geolocation & Network Details](#8-ipinfo--ip-geolocation--network-details)
   - [`subfinder` - Passive Subdomain Discovery](#9-subfinder--passive-subdomain-discovery)
   - [`doggo` - Human-Friendly DNS Client](#10-doggo--human-friendly-dns-client)
4. [Unified Hub Architecture](#4-unified-hub-architecture)
5. [Shell Autocompletions (Zsh, Bash, Fish)](#5-shell-autocompletions-zsh-bash-fish)
6. [Zero-Dependency Standalone Binary Compilation](#6-zero-dependency-standalone-binary-compilation)

---

## 1. Architectural Overview & Design Principles

`bun_sys_utils` is structured around clean design principles:

- **Native Bun APIs**: Relies directly on `Bun.file()`, `Bun.write()`, `Bun.spawn()`, `Bun.serve()`, and native microsecond filesystem APIs.
- **Doer vs. Coordinator Pattern**:
  - **Doer Functions**: Pure business logic with zero console I/O, receiving explicit arguments and returning typed results.
  - **Coordinator Functions**: CLI lifecycle managers handling argument parsing, user prompts, formatting, error codes, and terminal output.
- **Dual Interface**: Every utility operates both as a standalone CLI tool and as an importable programmatic TypeScript module.

---

## 2. Included System Utilities

| Tool | Replaces / Equivalent | Description |
| :--- | :--- | :--- |
| **`fd`** | `find` | Simple, colorized, user-friendly alternative to `find` |
| **`sd`** | `sed` | Intuitive search and replace with regex capture groups |
| **`rip`** | `rm` | Safe deletion utility with recoverable graveyard and undo |
| **`procs`** | `ps`, `top` | Modern process viewer with tree views and interactive TUI |
| **`watchexec`** | `nodemon`, `chokidar` | Filesystem watcher that executes commands on file change |
| **`tokei`** | `cloc`, `scc` | Blazing-fast lines-of-code and comment counter |
| **`gdu`** | `du`, `ncdu` | Interactive terminal disk usage explorer and analyzer |
| **`ipinfo`** | `curl ipinfo.io` | IP geolocation, ASN, network interfaces, and CIDR subnet calculator |
| **`subfinder`**| Subdomain enumeration | Passive subdomain discovery via security cert logs & APIs |
| **`doggo`** | `dig`, `drill` | Modern DNS client with colorized tables and DNS-over-HTTPS (DoH) |

---

## 3. Tool-by-Tool Usage & Examples

### 1. `fd` - Fast & Intuitive File Finder

```bash
# Find files by name pattern in current directory
bun run fd "server" .

# Case-insensitive search
bun run fd -i "readme" .

# Search only for directories (-t d) or files (-t f)
bun run fd -t d "models" src/
bun run fd -t f "config" .

# Filter by file extension
bun run fd -e ts -e json .

# Include hidden and gitignored files
bun run fd -H -I "secret" .

# Limit search depth
bun run fd -d 2 "package.json" .

# Execute command on each matching file ({} placeholder)
bun run fd -e ts --exec bun test {}
```

### 2. `sd` - Intuitive Find & Replace

```bash
# Simple in-place string replacement in a file
bun run sd "localhost:8080" "api.production.com" config.json

# Regex replacement across multiple files
bun run sd "const (\w+) = require\('(\w+)'\)" "import $1 from '$2'" src/**/*.js

# Dry run mode (preview diff without modifying files on disk)
bun run sd -p "old_version" "new_version" package.json

# Read from stdin and output to stdout
echo "hello world" | bun run sd "world" "bun"
```

### 3. `rip` - Safe File Removal with Graveyard

```bash
# Safely remove files or directories (moves to ~/.local/share/graveyard)
bun run rip temp.log
bun run rip -r old_build_dir/

# Inspect graveyard history and deleted files
bun run rip --seance

# Instantly restore / undelete a removed file
bun run rip --unbury temp.log

# Prune graveyard items older than 14 days
bun run rip --prune 14

# Permanently purge all items from the graveyard
bun run rip --decompose
```

### 4. `procs` - Process Viewer & Interactive Manager

```bash
# View formatted process table
bun run procs

# Filter by keyword, command, or PID
bun run procs bun

# Inspect active listening TCP ports per process
bun run procs -P

# Hierarchical process tree view
bun run procs --tree

# Sort by CPU, Memory, or PID
bun run procs --sort-cpu
bun run procs --sort-mem
bun run procs --sort-pid

# Terminate process by PID directly from CLI
bun run procs -k 12345 --signal SIGTERM

# Launch Interactive TUI Process Explorer:
bun run procs -i
# Keyboard controls:
#   ↑/↓ or k/j      Navigate process rows
#   / or f          Filter processes live
#   c / m / p / u   Sort by CPU, Memory, PID, or User
#   Enter / d       Detailed process modal (ports, child PIDs)
#   x / K           Terminate process (SIGTERM)
#   9 / X           Force kill process (SIGKILL)
#   q / Ctrl+C      Exit TUI
```

### 5. `watchexec` - File Watcher & Command Runner

```bash
# Run unit tests whenever TypeScript files are modified
bun run watchexec -e ts -- bun test

# Watch specific directory with screen clear (-c) and 200ms debounce
bun run watchexec -w src -c -d 200 -- bun run index.ts

# Filter files by pattern
bun run watchexec -f "*router*" -- bun test

# Postpone execution until the first file modification occurs
bun run watchexec --postpone -e ts -- bun run build

# Ignore specific build directories
bun run watchexec -i dist,build -e ts,json -- bun run build
```

### 6. `tokei` - Fast Code & LOC Counter

```bash
# Count lines of code in current directory
bun run tokei .

# Sort by code lines, files, comments, or blank lines
bun run tokei --sort code .
bun run tokei --sort files .

# Display per-file breakdown under each language
bun run tokei --files .

# Output as GitHub-flavored Markdown table (ideal for PR summaries)
bun run tokei -m .

# Output stats as structured JSON
bun run tokei -j .

# Exclude folders or patterns
bun run tokei -e "node_modules,dist" .
```

### 7. `gdu` / `gdu-go` - Disk Usage Analyzer

```bash
# Launch interactive TUI disk usage explorer
bun run gdu .
# TUI Navigation:
#   ↑/↓ or k/j      Select file / folder
#   Enter / →       Drill into folder
#   Backspace / ←   Navigate up to parent
#   d / Delete      Delete selected file/folder (with confirmation)
#   s               Cycle sort: Size → Name → Count → Mtime
#   / or f          Filter items live
#   q               Exit TUI

# Non-interactive summary report
bun run gdu -n .

# Show relative visual bars and item counts
bun run gdu -B -C .

# Filter out files smaller than threshold
bun run gdu -m 10M .

# View all mounted disks and available space
bun run gdu -d
```

### 8. `ipinfo` - IP Geolocation & Network Details

```bash
# Look up current public IP details
bun run ipinfo

# Look up specific IP address
bun run ipinfo 8.8.8.8

# Extract single field (e.g. org, city, country, loc)
bun run ipinfo 1.1.1.1 -f org

# Calculate IPv4 CIDR subnet details (broadcast, netmask, usable hosts)
bun run ipinfo 192.168.1.0/24

# Inspect local machine network interfaces and assigned IPs
bun run ipinfo --local

# Output as JSON or CSV
bun run ipinfo 8.8.8.8 -j
bun run ipinfo 8.8.8.8 -c
```

### 9. `subfinder` - Passive Subdomain Discovery

```bash
# Discover subdomains passively using public certificate logs
bun run subfinder -d example.com

# Active verification (resolves live IPs via DNS)
bun run subfinder -d example.com -active

# HTTP/HTTPS probe (checks HTTP status codes and extracts HTML page titles)
bun run subfinder -d example.com -probe

# Probe specific open ports (e.g. 80, 443, 8080, 8443)
bun run subfinder -d example.com --ports 80,443,8080

# Filter out wildcard DNS subdomains
bun run subfinder -d example.com --wildcard

# Silent output (subdomains only, ideal for piping to other tools)
bun run subfinder -d example.com -silent

# Output to JSON or save to file
bun run subfinder -d example.com -json -o results.json
```

### 10. `doggo` - Human-Friendly DNS Client

```bash
# Standard DNS lookup
bun run doggo example.com

# Specific record types (A, AAAA, MX, TXT, CNAME, NS, SOA, CAA, PTR, SRV)
bun run doggo example.com MX
bun run doggo example.com TXT

# Query all common record types in parallel
bun run doggo example.com --all

# Query custom nameserver (@nameserver syntax)
bun run doggo example.com @1.1.1.1

# DNS-over-HTTPS (DoH) via Cloudflare or Google
bun run doggo example.com --doh
bun run doggo example.com --doh --doh-url https://dns.google/resolve

# Reverse DNS PTR lookup for an IP
bun run doggo 8.8.8.8 -x

# Short output (equivalent to dig +short)
bun run doggo example.com --short
```

---

## 4. Unified Hub Architecture

Any tool can also be invoked through the central entry point:

```bash
bun run index.ts fd "pattern" .
bun run index.ts procs --tree
bun run index.ts tokei .
bun run index.ts gdu -B -C .
bun run index.ts ipinfo 8.8.8.8
bun run index.ts subfinder -d example.com -silent
bun run index.ts doggo example.com MX @1.1.1.1
```

---

## 5. Shell Autocompletions

Generate native autocompletion scripts for `zsh`, `bash`, or `fish`:

```bash
# Zsh
bun run index.ts completions doggo zsh > ~/.zfunc/_doggo

# Bash
bun run index.ts completions procs bash > ~/.bash_completion.d/procs

# Fish
bun run index.ts completions gdu fish > ~/.config/fish/completions/gdu.fish
```

---

## 6. Zero-Dependency Standalone Binary Compilation

Compile any utility into a standalone native binary with zero runtime requirements:

```bash
# Compile all 10 utilities and the unified hub into ./dist/
bun run build

# Standalone execution (no Node.js or Bun needed on target machine!)
./dist/doggo example.com MX @1.1.1.1
./dist/gdu -B -C .
./dist/procs --tree
./dist/fd ".*\.ts$" src/
```
