# Bandwhich Terminal Bandwidth Monitor Guide

`bandwhich` (formerly known as `what`) is a modern, terminal-based network utilization monitor written in Rust. Designed as an all-in-one replacement for legacy tools like `iftop`, `nload`, and `nethogs`, `bandwhich` sniffs network packets to display real-time bandwidth consumption grouped by **Process**, **Connection**, and **Remote Address**.

---

## 📚 Table of Contents

1. [Overview & `iftop` / `nethogs` vs `bandwhich` Comparison](#overview-iftop-nethogs-vs-bandwhich-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Running `bandwhich` & Terminal TUI Layout](#running-bandwhich-terminal-tui-layout)
4. [Interactive Keyboard Controls](#interactive-keyboard-controls)
5. [Filtering by Interface & Process](#filtering-by-interface-process)
6. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
7. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `iftop` / `nethogs` vs `bandwhich` Comparison

In the past, you needed `iftop` to view remote hosts and `nethogs` to identify which process was consuming bandwidth. `bandwhich` correlates both simultaneously in real-time.

| Feature | `iftop` | `nethogs` | `bandwhich` |
| :--- | :--- | :--- | :--- |
| **Language** | C | C++ | Rust |
| **Process Breakdown** | No | Yes | Yes (Process Name & PID) |
| **Remote Host / IP Breakdown** | Yes | No | Yes |
| **Connection Socket Breakdown** | Limited | No | Yes (IP:Port pairs) |
| **Reverse DNS** | Slow | Basic | Asynchronous & Non-blocking |
| **Total Transfer Accumulation** | No | No | Yes (`-t` mode) |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install bandwhich
```

### 2. Install via Cargo

```bash
cargo install bandwhich
```

### 3. Verify Installation

```bash
bandwhich --version
```

> [!NOTE]
> Capturing live network packet headers requires administrative privileges (`sudo bandwhich`).

---

## 🚀 Running `bandwhich` & Terminal TUI Layout

Launch `bandwhich` with elevated privileges:

```bash
sudo bandwhich
```

### Visual 3-Pane Interface:

```text
┌─ Processes ──────────── Upload ── Download ┐┌─ Connections ───────────── Upload ── Download ┐
│ spotify (PID 4821)     12 KB/s    850 KB/s ││ 192.168.1.50:5231 -> 35.186.224.25:443 12K  850K│
│ chrome (PID 1902)       4 KB/s    120 KB/s ││ 192.168.1.50:5412 -> 140.82.121.4:443   4K  120K│
│ curl (PID 88201)       80 KB/s    5.2 MB/s │└───────────────────────────────────────────────┘
└────────────────────────────────────────────┘┌─ Remote Addresses ──────── Upload ── Download ┐
                                              │ audio-ak-spotify.com        12 KB/s   850 KB/s │
                                              │ github.com                   4 KB/s   120 KB/s │
                                              └────────────────────────────────────────────────┘
```

---

## 🧭 Interactive Keyboard Controls

| Key | Action |
| :--- | :--- |
| `Tab` | Cycle focus between Process, Connection, and Remote Address panes |
| `▲` / `▼` or `k` / `j` | Scroll up and down inside the active pane |
| `Space` | Pause / freeze bandwidth counters |
| `d` | Toggle DNS reverse resolution |
| `t` | Toggle between transfer rate (e.g. `KB/s`) and total data transferred (e.g. `MB`) |
| `p` | Toggle process information pane |
| `c` | Toggle connection socket pane |
| `a` | Toggle remote address pane |
| `q` | Quit `bandwhich` |

---

## 🎯 Filtering by Interface & Process

### 1. Listen on a Specific Network Interface (`-i` / `--interface`)

```bash
# Monitor Wi-Fi interface (e.g. en0 on macOS)
sudo bandwhich -i en0

# Monitor Ethernet / Docker virtual bridge
sudo bandwhich -i bridge0
```

### 2. Process-Only or Raw IP Mode (`-r` / `--no-dns`)

```bash
# Disable DNS resolution to show raw IPs and reduce overhead
sudo bandwhich -r

# Display total cumulative bytes transferred instead of current throughput
sudo bandwhich -t
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Monitor all bandwidth** | `sudo bandwhich` |
| **Monitor Wi-Fi (en0)** | `sudo bandwhich -i en0` |
| **Show raw IPs (no DNS)** | `sudo bandwhich -r` |
| **Show total bytes accumulated** | `sudo bandwhich -t` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Bandwhich shortcuts
alias bandwidth='sudo bandwhich'
alias netmon='sudo bandwhich'
alias iftop='sudo bandwhich'
alias nethogs='sudo bandwhich'
```

---

## 🗑️ Uninstallation

To remove `bandwhich`:

```bash
brew uninstall bandwhich
```
