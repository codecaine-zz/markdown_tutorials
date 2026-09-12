# Watchexec File Watcher & Automation Guide

`watchexec` is a modern, cross-platform file watcher and command execution utility written in Rust. It monitors files and directories for modifications, creations, and deletions, instantly running or restarting commands in response (a superior alternative to `nodemon`, `fswatch`, and `entr`).

---

## 📚 Table of Contents

1. [Overview & Why Use `watchexec`?](#overview-why-use-watchexec)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Basic Usage & Watching Directories](#basic-usage-watching-directories)
4. [Filtering by Extension & Ignore Rules (`-e` / `-i`)](#filtering-by-extension-ignore-rules)
5. [Process Management: Restarts & Signals (`-r` / `-s`)](#process-management-restarts-signals--r--s)
6. [Screen Clearing & Debouncing (`-c` / `-d`)](#screen-clearing-debouncing--c--d)
7. [Real-World Development Workflows](#real-world-development-workflows)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `watchexec`?

Unlike language-specific watchers (e.g. `nodemon` for Node.js or `cargo-watch` for Rust), `watchexec` is language-agnostic and universally fast across any project.

| Feature | `nodemon` | `entr` | `watchexec` |
| :--- | :--- | :--- | :--- |
| **Language Support** | Node.js focused | Generic Unix | Universal (Rust engine) |
| **`.gitignore` Support** | Manual config | None | Automatic default |
| **Process Restarts** | Yes | Limited | Full signal control (`SIGTERM`, `SIGKILL`) |
| **Debounce Control** | Basic | None | Configurable millisecond debounce |
| **Cross-Platform** | Node-dependent | Unix only | macOS, Linux, Windows |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install watchexec
```

### 2. Install via Cargo

```bash
cargo install watchexec-cli
```

### 3. Verify Installation

```bash
watchexec --version
```

---

## 🚀 Basic Usage & Watching Directories

Run any command when files change:

```bash
# Re-run unit tests when any file changes
watchexec npm test

# Re-compile Go binary
watchexec go build .

# Run Python script on changes
watchexec python3 main.py
```

---

## 🎯 Filtering by Extension & Ignore Rules

### 1. Watch Specific File Extensions (`-e` / `--exts`)

Only trigger commands when specific file formats are saved:

```bash
# Watch only TypeScript and JavaScript files
watchexec -e ts,js npm test

# Watch Rust and TOML files
watchexec -e rs,toml cargo check
```

### 2. Watch Specific Paths (`-w` / `--watch`)

```bash
# Watch only the src/ and config/ directories
watchexec -w src -w config npm run build
```

### 3. Ignore Files and Folders (`-i` / `--ignore`)

`watchexec` respects your project's `.gitignore` by default. You can specify additional ignores:

```bash
# Ignore log files and generated docs
watchexec -i "*.log" -i docs/ python3 app.py
```

---

## 🔄 Process Management: Restarts & Signals (`-r` / `-s`)

For long-running servers and background processes, use restart mode (`-r`) to terminate the old process before spawning a new one:

```bash
# Automatically restart development web server on code changes
watchexec -r -e js,ts,html node server.js

# Send custom signal before killing (e.g. SIGHUP for nginx or daemons)
watchexec -s SIGHUP nginx -s reload
```

---

## 🧹 Screen Clearing & Debouncing (`-c` / `-d`)

### 1. Clear Screen on Every Run (`-c` / `--clear`)

Keeps terminal uncluttered so the latest command output is always at the top:

```bash
watchexec -c pytest
```

### 2. Debounce Delay (`-d` / `--debounce`)

Prevent multi-trigger events caused by IDE auto-saves or git operations:

```bash
# Wait 500ms after the last file modification before executing
watchexec -d 500ms make build
```

---

## 🛠️ Real-World Development Workflows

### 1. Auto-Recompile C/C++ or Go Binaries

```bash
watchexec -c -e c,h,cpp "make clean && make -j8"
```

### 2. Auto-Format Markdown Documentation

```bash
watchexec -e md prettier --write "**/*.md"
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Watch & Run** | `watchexec <command>` |
| **Watch with Clear Screen** | `watchexec -c <command>` |
| **Filter by Extension** | `watchexec -e rs,toml <command>` |
| **Restart Long-Running Server** | `watchexec -r -e js node app.js` |
| **Watch Specific Folder** | `watchexec -w src <command>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Watchexec shortcuts
alias watchrun='watchexec -c'
alias dev-node='watchexec -r -c -e js,ts,json node'
alias dev-py='watchexec -r -c -e py python3'
```

---

## 🗑️ Uninstallation

To remove `watchexec`:

```bash
brew uninstall watchexec
```
