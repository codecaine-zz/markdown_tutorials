# Trippy (Modern MTR & Traceroute) Network Guide

`trippy` (invoked as `trip`) is a modern, interactive network diagnostic utility written in Rust. Designed to replace legacy `traceroute`, `ping`, and `mtr`, `trippy` provides real-time packet loss tracking, latency histograms, autonomous system (AS) lookups, GeoIP data, and multi-path routing visualization in an elegant terminal UI.

---

## 📚 Table of Contents

1. [Overview & `traceroute` vs `trippy` Comparison](#overview-traceroute-vs-trippy-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Basic Tracing & Running `trip`](#basic-tracing-running-trip)
4. [Tracing Protocols: ICMP, UDP & TCP](#tracing-protocols-icmp-udp-tcp)
5. [Interactive TUI Navigation & Visual Charts](#interactive-tui-navigation-visual-charts)
6. [DNS, GeoIP & Autonomous System (AS) Lookups](#dns-geoip-autonomous-system-as-lookups)
7. [Multipath Routing & ECMP Analysis](#multipath-routing-ecmp-analysis)
8. [Exporting Reports: JSON, CSV & Stream Modes](#exporting-reports-json-csv-stream-modes)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `traceroute` vs `trippy` Comparison

Standard `traceroute` is slow, single-shot, and provides minimal latency telemetry. `trippy` runs continuous diagnostics with live stats.

| Feature | Legacy `traceroute` | Classic `mtr` | `trippy` (`trip`) |
| :--- | :--- | :--- | :--- |
| **Language** | C | C | Rust |
| **Interface** | Static text | Basic curses | Modern responsive TUI |
| **Protocols** | UDP / ICMP | ICMP | ICMP, UDP, TCP |
| **Latency Charts** | None | Simple numbers | Live histograms & sparklines |
| **GeoIP / AS Info** | None | Limited | Built-in GeoIP & ASN |
| **Multipath (ECMP)** | No | Limited | Full Multi-Path Tree |
| **Export Formats** | Plain text | Raw / CSV | JSON, CSV, Stream, Silent |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install trippy
```

### 2. Verify Installation

```bash
trip --version
```

> [!NOTE]
> On macOS and Linux, sending raw ICMP or custom TCP/UDP packets requires root permissions (`sudo trip ...`) or configuring packet socket capabilities.

---

## 🚀 Basic Tracing & Running `trip`

To trace network routing to a target host or domain:

```bash
# Trace route to a public domain
sudo trip 1.1.1.1

# Trace route to GitHub
sudo trip github.com
```

### Visual TUI Layout

```text
 Hop  Host                  Loss%   Snt   Recv   Last   Avg    Best   Wrst   StDev   Graph
   1  192.168.1.1           0.0%    42     42    1.2ms  1.4ms  0.9ms  3.2ms  0.4ms  _ █ _ _
   2  10.0.0.1              0.0%    42     42    8.4ms  9.1ms  7.8ms 14.1ms  1.2ms  _ █ ▄ _
   3  lag-10.gw.isp.net     2.4%    42     41   14.2ms 15.0ms 13.1ms 22.0ms  1.8ms  _ █ ▆ _
   4  1.1.1.1               0.0%    42     42   16.5ms 16.8ms 15.4ms 24.5ms  1.1ms  _ ▄ █ _
```

---

## 📡 Tracing Protocols: ICMP, UDP & TCP

Different firewalls and routers treat network protocols differently. `trippy` lets you test with ICMP, UDP, or TCP on custom ports:

### 1. ICMP (Default)

```bash
sudo trip -p icmp google.com
```

### 2. TCP Tracing on Specific Ports (e.g., HTTPS 443 / SSH 22)

Identify firewall drops on specific application ports:

```bash
# Trace HTTPS connectivity
sudo trip -p tcp -t 443 cloudflare.com

# Trace SSH connectivity
sudo trip -p tcp -t 22 remote-server.com
```

### 3. UDP Tracing (DNS / Custom UDP Services)

```bash
sudo trip -p udp -t 53 8.8.8.8
```

---

## ⌨️ Interactive TUI Navigation & Visual Charts

While running inside `trippy`:

| Key | Action |
| :--- | :--- |
| `▲` / `▼` or `k` / `j` | Select a specific hop |
| `Space` | Pause / Freeze tracing data |
| `r` | Reset collected statistics |
| `d` | Toggle DNS resolution mode |
| `a` | Toggle AS (Autonomous System) display |
| `g` | Toggle GeoIP location column |
| `h` | Toggle hop latency histogram |
| `c` | Cycle columns (Packets, Latency, Loss) |
| `q` | Quit `trippy` |

---

## 🌍 DNS, GeoIP & Autonomous System (AS) Lookups

Enable real-time geographical mapping and ASN details:

```bash
# Enable GeoIP and AS resolution
sudo trip --geoip-mmdb-file /path/to/GeoLite2-City.mmdb google.com

# Disable reverse DNS for maximum tracing speed
sudo trip -r=none 1.1.1.1
```

---

## 🔀 Multipath Routing & ECMP Analysis

In modern data centers and backbone networks, traffic is balanced across multiple router paths. `trippy` visualizes Equal-Cost Multi-Path (ECMP) routing:

```bash
# Enable multipath strategy
sudo trip -s multipath 8.8.8.8
```

Press `m` in the TUI to expand and view multiple router nodes operating on the same hop level.

---

## 📊 Exporting Reports: JSON, CSV & Stream Modes

Generate structured telemetry reports for logging or CI/CD network health checks without the TUI:

### 1. JSON Report Export

```bash
# Output network stats as JSON after 10 rounds
sudo trip -m json -c 10 github.com > trip_report.json
```

### 2. CSV Report Export

```bash
# Export spreadsheet-ready CSV
sudo trip -m csv -c 10 google.com > network_metrics.csv
```

### 3. Streaming Mode for Real-Time Logging

```bash
# Stream continuous hop metrics to terminal
sudo trip -m stream 1.1.1.1
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Standard Trace** | `sudo trip <host>` |
| **Trace TCP Port 443** | `sudo trip -p tcp -t 443 <host>` |
| **Fast 100ms Ping Rate** | `sudo trip -i 100ms <host>` |
| **No Reverse DNS** | `sudo trip -r=none <host>` |
| **JSON Report (5 cycles)** | `sudo trip -m json -c 5 <host>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Aliases for Trippy
alias traceroute='sudo trip'
alias mtr='sudo trip'
alias trace-web='sudo trip -p tcp -t 443'
alias trace-fast='sudo trip -i 100ms'
```

---

## 🗑️ Uninstallation

To remove `trippy`:

```bash
brew uninstall trippy
```
