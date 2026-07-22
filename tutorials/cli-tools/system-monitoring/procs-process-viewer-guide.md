# procs: A Modern Replacement for ps

## Table of Contents

1. [What is `procs`?](#1-what-is-procs)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Usage & Output](#4-basic-usage-output)
5. [Search & Filtering Features](#5-search-filtering-features)
6. [Tree View Mode](#6-tree-view-mode)
7. [Watch Mode (Live Updating)](#7-watch-mode-live-updating)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `procs`?

`procs` is a modern, colored process viewer written in Rust, designed as a feature-rich replacement for the standard Unix `ps` utility. It provides syntax highlighting, automatic column alignment, keyword searching, process tree visualization, and integration with Docker container IDs.

#### Key Improvements over `ps aux`
* **Color-Coded Output:** Visually differentiates usernames, process IDs, memory usage, CPU usage, and binary paths.
* **Human-Readable Units:** Displays RAM in MB/GB instead of raw page bytes.
* **Tree View:** Visualizes parent-child process relationships natively.
* **Keyword Filtering:** Instant process lookup without piping to `grep`.

---

### 2. Prerequisites

Verify Homebrew on your ARM Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `procs` via Homebrew:

```bash
brew install procs
```

Verify installation:

```bash
which procs
procs --version
```

**Expected Output:**
```text
/opt/homebrew/bin/procs
procs 0.14.x (or latest)
```

---

### 4. Basic Usage & Output

Run `procs` with no arguments to list all active system processes:

```bash
procs
```

**Example Output:**

```text
 Pid  │ User      │ TTY │ CPU   │ Mem      │ Start            │ Command
──────┼───────────┼─────┼───────┼──────────┼──────────────────┼─────────────────────────
 1280 │ codecaine │ s000│  0.0% │ 14.5 MiB │ 14:02:10 (01:12) │ /bin/zsh
 4921 │ codecaine │ s000│  2.4% │ 180.2MiB │ 14:15:30 (00:45) │ node /src/server.js
 8102 │ root      │ -   │  0.1% │ 45.0 MiB │ 09:00:15 (06:20) │ /usr/libexec/syspolicyd
```

---

### 5. Search & Filtering Features

`procs` accepts keywords directly to search process names, commands, or user IDs without needing `ps aux | grep name`.

#### Example 1: Search by Process Name
```bash
procs node
```

Only lists processes whose command line contains `node`.

#### Example 2: Filter by User
```bash
procs --user codecaine
```

#### Example 3: Sort Processes by Memory or CPU Usage
Sort processes descending by memory consumption:

```bash
procs --sortd mem
```

Sort processes descending by CPU usage:

```bash
procs --sortd cpu
```

---

### 6. Tree View Mode

To display parent and child process hierarchy in a tree format:

```bash
procs --tree
```

**Example Output:**
```text
 Pid  │ User      │ Command
──────┼───────────┼─────────────────────────────────
 1280 │ codecaine │ └─ /bin/zsh
 4921 │ codecaine │    └─ node /src/server.js
 4922 │ codecaine │       └─ node /src/worker.js
```

---

### 7. Watch Mode (Live Updating)

Like `top` or `htop`, `procs` can continuously update process information live:

```bash
procs --watch
```

Press `Ctrl+C` to quit.

---

### 8. Uninstallation

```bash
brew uninstall procs
```
