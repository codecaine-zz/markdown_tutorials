# Viddy Modern Watch Command Guide

`viddy` is a modern, feature-rich replacement for the classic Unix `watch` command. Written in Go, it periodically executes a command and displays its output, adding powerful modern enhancements like **time-machine history navigation**, **live diff highlighting**, **in-buffer search**, and **instant pausing**.

---

## 📚 Table of Contents

1. [Overview & `watch` vs `viddy` Comparison](#overview-watch-vs-viddy-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Basic Usage & Terminal Display](#basic-usage-terminal-display)
4. [Time-Machine History & Rewind Mode](#time-machine-history-rewind-mode)
5. [Diff Highlighting & Precise Execution](#diff-highlighting-precise-execution)
6. [Interactive Keyboard Controls](#interactive-keyboard-controls)
7. [Custom Intervals & Shell Configurations](#custom-intervals-shell-configurations)
8. [Real-World Monitoring Scenarios](#real-world-monitoring-scenarios)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `watch` vs `viddy` Comparison

While standard `watch` simply overwrites the terminal on every interval, `viddy` keeps a sliding history buffer of all previous runs.

| Feature | Classic `watch` | `viddy` |
| :--- | :--- | :--- |
| **History / Rewind** | None (instant overwrite) | Full time-machine rewind |
| **Search in Buffer** | None | Regex / Text search (`/`) |
| **Diff Highlighting** | Basic | Character & line level diffs |
| **Pause Execution** | None (must terminate) | Pause with `Spacebar` |
| **Sub-second Intervals** | Limited | Native (`-n 500ms`, `-n 0.2s`) |
| **Pager Navigation** | None | Full vim-like scrolling |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install viddy
```

### 2. Install via Go

```bash
go install github.com/sachaos/viddy@latest
```

### 3. Verify Installation

```bash
viddy --version
```

---

## 🚀 Basic Usage & Terminal Display

Run any terminal command through `viddy`:

```bash
# Watch files in current directory updated every 2 seconds
viddy ls -la

# Watch git status changes
viddy git status

# Watch listening ports
viddy lsof -iTCP -sTCP:LISTEN
```

---

## ⏳ Time-Machine History & Rewind Mode

One of `viddy`'s most powerful capabilities is the ability to travel back in time to inspect earlier command outputs:

1. **Pause/Freeze Time**: Press `Space` or `Shift + Tab` to pause live updates.
2. **Step Backward/Forward**: Use `▲` / `▼` or `j` / `k` to step between historical snapshots.
3. **Timeline Slider**: A visual timeline at the top shows which point in time you are currently examining.
4. **Resume Live Stream**: Press `Space` again to resume live execution.

---

## 🎯 Diff Highlighting & Precise Execution

### 1. Highlight Changes (`-d` / `--differences`)

Highlight characters and words that changed since the last execution:

```bash
# Highlight changing process metrics
viddy -d ps aux --sort=-%cpu

# Highlight network interface traffic
viddy -d netstat -i
```

### 2. Precise Interval Timing (`-p` / `--precise`)

Standard `watch` introduces drift over time because the execution time of the command gets added to the sleep delay. `viddy -p` maintains precise timing intervals regardless of command duration.

```bash
viddy -p -n 1s curl -s https://api.github.com/zen
```

---

## ⌨️ Interactive Keyboard Controls

Inside the `viddy` interactive viewport:

| Key | Action |
| :--- | :--- |
| `Space` | Pause / resume live execution |
| `d` | Toggle diff mode on / off |
| `j` / `k` | Scroll history backward / forward |
| `g` / `G` | Jump to oldest / latest history snapshot |
| `/` | Search within output buffer |
| `n` / `N` | Jump to next / previous search result |
| `t` | Toggle header / timeline visibility |
| `q` | Quit `viddy` |

---

## ⏱️ Custom Intervals & Shell Configurations

### 1. Set Custom Polling Intervals (`-n` / `--interval`)

Specify human-readable durations (milliseconds, seconds, minutes):

```bash
# Poll every 500 milliseconds
viddy -n 500ms docker ps

# Poll every 10 seconds
viddy -n 10s df -h
```

### 2. Shell Command Execution with Piping

To run pipelines or shell aliases, quote the command or pass it to your shell:

```bash
viddy "kubectl get pods | grep -v Completed"
```

---

## 🌐 Real-World Monitoring Scenarios

### 1. Monitoring Kubernetes Pod Status & Restarts

```bash
viddy -d -n 2s "kubectl get pods -A"
```

### 2. Monitoring Disk Space During Large Downloads / Compilations

```bash
viddy -d -n 1s "df -h | grep -E '(Filesystem|/System/Volumes/Data)'"
```

### 3. Monitoring Database Queries or Connections

```bash
viddy -d -n 1s "mysql -u root -e 'SHOW PROCESSLIST;'"
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Watch Command** | `viddy <command>` |
| **Watch with Diffs** | `viddy -d <command>` |
| **Fast 500ms Watch** | `viddy -n 500ms <command>` |
| **Precise Interval** | `viddy -p -n 2s <command>` |
| **Watch Pipelines** | `viddy "<cmd1> \| <cmd2>"` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Replace classic watch with viddy
alias watch='viddy'
alias watch-diff='viddy -d'
alias watch-fast='viddy -n 500ms -d'
```

---

## 🗑️ Uninstallation

To remove `viddy`:

```bash
brew uninstall viddy
```
