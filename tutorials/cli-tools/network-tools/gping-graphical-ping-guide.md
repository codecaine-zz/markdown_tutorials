# gping Graphical Ping & Latency Visualizer Guide

`gping` is a command-line utility that provides "`ping`, but with a graph". It renders a real-time, graphical visualization of network latency directly inside your terminal, allowing developers, network engineers, and sysadmins to instantly spot latency spikes, packet drops, and internet connection jitter.

---

## 📚 Table of Contents

1. [Overview & Features](#overview--features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Ping & Multi-Host Comparison](#basic-ping--multi-host-comparison)
4. [IPv4 vs IPv6 Latency Monitoring](#ipv4-vs-ipv6-latency-monitoring)
5. [Graphing Command Execution Time (`--cmd`)](#graphing-command-execution-time---cmd)
6. [Graph Buffer & Custom Update Intervals](#graph-buffer--custom-update-intervals)
7. [Practical Use Cases & Cheat Sheet](#practical-use-cases--cheat-sheet)

---

## 🔍 Overview & Features

- **Visual Graphs**: Real-time ASCII line graph of response times instead of scrolling text.
- **Multi-Host Plotting**: Plot multiple endpoints on a single graph with color-coded legends.
- **Command Benchmarking**: Measure and graph the execution time of any shell command (e.g. `curl`, `dig`, database queries).
- **Cross-Platform & Lightweight**: Built in Rust for maximum performance and minimal footprint.

---

## ⚙️ Installation via Homebrew

```bash
# Install gping via Homebrew on macOS
brew install gping

# Verify installation
gping --version
```

---

## 🚀 Basic Ping & Multi-Host Comparison

### 1. Ping Single Endpoint
```bash
# Graph ping latency to Google
gping google.com
```

### 2. Compare Multiple Endpoints Side-by-Side
Provide multiple hostnames or IP addresses to overlay their latency curves on the same graph with color keys.

```bash
# Compare DNS resolver latency (Cloudflare vs Google vs Quad9)
gping 1.1.1.1 8.8.8.8 9.9.9.9

# Compare global service latency
gping google.com github.com amazon.com
```

**Example Terminal Display:**
```text
gping (3 hosts)
Press 'q' to quit

50ms ┤
    │
40ms ┤               ╭──╮
    │               │  │
30ms ┤               │ b│      ╭──╮
    │               ╰──╯      │ c│
20ms ┤╭──╮     ╭──╮     ╭──╮   ╰──╯    ╭──╮
    ││ a│     │ a│     │ a│           │ a│
10ms ┤│  │╭──╮ │  │╭──╮ │  │           │  │
    │╰──╯│ b│╭╯  ││  │╭╯  │           ╰──╯
 0ms ┼────┴──┴───┴───┴───┴───────────────────────────────────────

[a] 1.1.1.1:   min 12.1ms, max 18.4ms, avg 14.3ms
[b] 8.8.8.8:   min 18.9ms, max 32.5ms, avg 21.0ms
[c] 9.9.9.9:   min 28.1ms, max 30.2ms, avg 29.5ms
```

---

## 🌐 IPv4 vs IPv6 Latency Monitoring

Force `gping` to test specifically IPv4 (`-4`) or IPv6 (`-6`) routes to identify routing issues.

```bash
# Force IPv4 resolution
gping -4 google.com

# Force IPv6 resolution
gping -6 google.com
```

---

## ⏱️ Graphing Command Execution Time (`--cmd`)

A powerful feature of `gping` is graphing the total execution time (in seconds) of arbitrary shell commands using `--cmd`.

### 1. Graph HTTP API Response Time via `curl`
```bash
# Plot HTTP response time to GitHub API
gping --cmd 'curl -s -o /dev/null -w "%{time_total}" https://api.github.com'
```

### 2. Graph DNS Resolution Lookup Time via `doggo` or `dig`
```bash
# Plot DNS lookup response speed over time
gping --cmd 'dig +short example.com @1.1.1.1'
```

---

## 🎛️ Graph Buffer & Custom Update Intervals

### 1. Adjust History Buffer Length (`-b` / `--buffer`)
Control how many seconds of historical latency data are retained on screen.

```bash
# Keep 5 minutes (300 seconds) of latency history on graph
gping -b 300 cloudflare.com
```

### 2. Adjust Ping Update Interval (`-n` / `--watch-interval`)
Change how frequently ICMP pings are dispatched (default is 1 second).

```bash
# High-frequency monitoring (ping every 0.2 seconds)
gping -n 0.2 1.1.1.1
```

---

## 📋 Practical Use Cases & Cheat Sheet

| Task | Command |
| --- | --- |
| Ping single host | `gping example.com` |
| Compare 3 DNS servers | `gping 1.1.1.1 8.8.8.8 9.9.9.9` |
| Force IPv4 ping | `gping -4 google.com` |
| Force IPv6 ping | `gping -6 google.com` |
| Graph API HTTP latency | `gping --cmd 'curl -s -o /dev/null -w "%{time_total}" "URL"'` |
| Keep 5 min history buffer | `gping -b 300 example.com` |
| Fast 200ms ping interval | `gping -n 0.2 1.1.1.1` |