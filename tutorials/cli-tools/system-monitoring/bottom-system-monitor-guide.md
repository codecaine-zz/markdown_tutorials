# Bottom (btm) System Monitor Guide

`bottom` (`btm`) is a highly customizable, graphical process and system monitor for the terminal, written in Rust. It provides real-time ASCII/Unicode charts for CPU utilization, memory and swap usage, disk I/O metrics, network traffic, temperatures, and interactive process management.

---

## 📚 Table of Contents

1. [Overview & Comparison (`htop` / `top` vs `btm`)](#overview-comparison-htop-top-vs-btm)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Keyboard Shortcuts](#basic-usage-keyboard-shortcuts)
4. [Process Filtering & Sorting](#process-filtering-sorting)
5. [Terminating & Signals (`SIGTERM` / `SIGKILL`)](#terminating-signals-sigterm-sigkill)
6. [Custom Configuration (`bottom.toml`)](#custom-configuration-bottomtoml)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Comparison (`htop` / `top` vs `btm`)

Comparing `top`/`htop` with `bottom`:

- **`top` / `htop`**: Traditional process list layout with fixed text output.
- **`bottom` (`btm`)**: Modern grid dashboard featuring dynamic sparkline graphs for CPU, RAM, Network bandwidth, Disk space, and an interactive process search engine.

---

## ⚙️ Installation via Homebrew

```bash
# Install bottom on macOS
brew install bottom

# Verify installation
btm --version
```

---

## 🚀 Basic Usage & Keyboard Shortcuts

Launch `bottom` using the `btm` binary:

```bash
btm
```

### Essential Navigation Keys

| Shortcut | Action |
| --- | --- |
| `Tab` / `Shift+Tab` | Cycle focus between dashboard widgets (CPU, RAM, Processes, Disk, Network) |
| `f` | Freeze/unfreeze data updating |
| `m` | Sort process table by Memory usage |
| `c` | Sort process table by CPU usage |
| `p` | Sort process table by PID |
| `n` | Sort process table by Name |
| `e` | Expand active widget to full-screen mode |
| `?` | Open interactive Help menu |
| `q` or `Ctrl+C` | Quit Bottom |

---

## 🎯 Process Filtering & Sorting

1. Press `Tab` until the **Processes Widget** is highlighted (or press `e` to expand it).
2. Type `/` to enter filter mode.
3. Type a process name (e.g. `node`, `python`, `chrome`).
4. Press `Esc` to exit search mode while keeping the filter active.

---

## ☠️ Terminating & Signals (`SIGTERM` / `SIGKILL`)

Kill runaway processes directly inside `bottom`:

1. Highlight the target process in the Process list using `j`/`k` or Up/Down arrow keys.
2. Press `dd` or `F9` (or `k`).
3. Select termination signal:
   - `15` (`SIGTERM`): Graceful shutdown request.
   - `9` (`SIGKILL`): Forceful immediate termination.

---

## ⚙️ Custom Configuration (`bottom.toml`)

Customize widget layouts, refresh intervals, and color schemes via `~/.config/bottom/bottom.toml`.

Create configuration directory:
```bash
mkdir -p ~/.config/bottom
```

Create `~/.config/bottom/bottom.toml`:

```toml
# Refresh rate in milliseconds (default: 1000ms)
rate = 1000

# Temperature units (celsius, fahrenheit)
temperature_type = "celsius"

# Default to expanded process widget
default_widget_type = "proc"

# Color theme customization
[colors]
table_header_color = "LightBlue"
all_cpu_color = "LightMagenta"
ram_color = "LightGreen"
swap_color = "LightYellow"
rx_color = "Cyan"
tx_color = "Green"

# Process widget settings
[processes]
real_memory = true
show_memory_as_values = true
```

---

## 📋 Cheat Sheet Summary

| Task | Command / Shortcut |
| --- | --- |
| Launch Bottom | `btm` |
| Filter Processes | `/` then type name |
| Sort by Memory | `m` |
| Sort by CPU | `c` |
| Expand Widget | `e` |
| Kill Process | Highlight + `dd` |
| Basic Mode (no charts) | `btm -b` |
| Custom Config Path | `~/.config/bottom/bottom.toml` |
