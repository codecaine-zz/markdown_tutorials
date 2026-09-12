# Bettercap (Network Security & MITM Framework) Complete Guide

`bettercap` is the Swiss Army knife for 802.11, BLE, cellular, and Ethernet networks reconnaissance and Man-In-The-Middle (MITM) security auditing. Written in Go, `bettercap` provides security researchers and penetration testers with an extensible, modular terminal framework and interactive web UI for packet manipulation, ARP spoofing, credential harvesting, and wireless auditing.

---

## 📚 Table of Contents

1. [Overview & Core Architecture](#overview-core-architecture)
2. [Homebrew Installation & macOS Permissions](#homebrew-installation-macos-permissions)
3. [Interactive Session & Module Architecture](#interactive-session-module-architecture)
4. [Local Network Discovery (`net.recon`)](#local-network-discovery-netrecon)
5. [ARP Spoofing & Traffic Interception (`arp.spoof`)](#arp-spoofing-traffic-interception-arpspoof)
6. [DNS Spoofing & Redirection (`dns.spoof`)](#dns-spoofing-redirection-dnsspoof)
7. [HTTP / HTTPS Sniffing & Credential Harvesting](#http-https-sniffing-credential-harvesting)
8. [Automating with Caplets (`.cap`)](#automating-with-caplets-cap)
9. [Running the Interactive Web UI](#running-the-interactive-web-ui)
10. [Legal Notice & Responsible Use](#legal-notice-responsible-use)
11. [Everyday Cheat Sheet & Useful Aliases](#everyday-cheat-sheet-useful-aliases)
12. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Core Architecture

`bettercap` replaces legacy, fragmented tools (such as Ettercap, Arpspoof, and MITMProxy) with an all-in-one Go event-loop architecture:

- **Modular Design**: Components (modules) can be enabled, configured, and disabled at runtime.
- **Event-Driven**: Asynchronous event bus captures packets, connections, and discovered credentials in real time.
- **Protocols Supported**: Ethernet, Wi-Fi (802.11 monitor mode/deauth), Bluetooth Low Energy (BLE), HID, and Software-Defined Radio (SDR).
- **Caplets**: Scriptable execution files (similar to shell scripts) for repeatable security audits.

---

## ⚙️ Homebrew Installation & macOS Permissions

### 1. Install via Homebrew

```bash
brew install bettercap
```

### 2. Verify Installation

```bash
which bettercap
bettercap -version
```

Output:
```text
/opt/homebrew/bin/bettercap
bettercap v2.41.7
```

### 3. macOS Network Interface Permissions

Because `bettercap` operates at Layer 2 (raw socket sniffing and packet injection), it requires `sudo` privileges and access to your network interfaces (e.g. `en0` for Wi-Fi):

```bash
# List available network interfaces
ifconfig -l
```

---

## 🖥️ Interactive Session & Module Architecture

Launch an interactive session on your active network interface:

```bash
sudo bettercap -iface en0
```

### Navigating the Interactive Prompt

Inside the session:

```text
192.168.1.0/24 > 192.168.1.50 >> help
```

- **`help`**: Displays running modules and available commands.
- **`help <module>`**: View detailed documentation and options for a specific module (e.g. `help net.recon`).
- **`active`**: Show only currently running modules.
- **`clear`**: Clear terminal screen.
- **`exit`** or `quit`: Terminate bettercap cleanly and restore network state.

---

## 📡 Local Network Discovery (`net.recon`)

Enable automated background scanning to identify all connected IP addresses, MAC addresses, and vendors on your LAN:

```text
# Start host discovery
net.recon on

# Display live host table
net.show
```

### Sample Host Table Output:
```text
┌─────────────────┬───────────────────┬───────────────┬─────────────────────────┬────────┐
│ IP              │ MAC               │ Name          │ Vendor                  │ Seen   │
├─────────────────┼───────────────────┼───────────────┼─────────────────────────┼────────┤
│ 192.168.1.1     │ 00:11:32:4f:8a:12 │ gateway.local │ Netgear                 │ 2s ago │
│ 192.168.1.105   │ 3c:22:fb:14:89:a0 │ workstation   │ Apple, Inc.             │ 1s ago │
│ 192.168.1.140   │ 70:ee:50:88:22:90 │ lab-server    │ Dell Inc.               │ 4s ago │
└─────────────────┴───────────────────┴───────────────┴─────────────────────────┴────────┘
```

Stop network discovery:
```text
net.recon off
```

---

## 🔀 ARP Spoofing & Traffic Interception (`arp.spoof`)

> [!CAUTION]
> Execute ARP spoofing audits strictly inside authorized penetration testing environments or isolated lab networks.

Configure and launch two-way ARP cache poisoning:

```text
# Target a specific host
set arp.spoof.targets 192.168.1.140

# Enable bidirectional spoofing (both target and gateway)
set arp.spoof.fullduplex true

# Enable the ARP spoofer
arp.spoof on
```

When you quit `bettercap`, it automatically sends gratuitous ARP packets to restore target ARP tables back to their original state.

---

## 🌐 DNS Spoofing & Redirection (`dns.spoof`)

Redirect domain lookups on intercepted targets to your audit server:

```text
# Domains to hijack (supports wildcards)
set dns.spoof.domains lab.internal, *.test.local

# IP address to reply with
set dns.spoof.address 192.168.1.50

# Activate DNS spoofing
dns.spoof on
```

---

## 🕵️ HTTP / HTTPS Sniffing & Credential Harvesting

Listen for plaintext authentication headers, URLs, cookies, and HTTP traffic:

```text
# Enable packet sniffer
set net.sniff.verbose true
net.sniff on

# Enable HTTP proxy interceptor
http.proxy on
```

---

## 📜 Automating with Caplets (`.cap`)

Caplets are scripts that automatically configure modules and settings upon launch.

Create `audit.cap`:

```text
# audit.cap - Basic LAN recon caplet
net.recon on
set ticker.period 5
set ticker.commands "clear; net.show"
ticker on
```

Run the caplet:

```bash
sudo bettercap -iface en0 -caplet audit.cap
```

---

## 🌐 Running the Interactive Web UI

`bettercap` includes a modern web-based graphical interface:

```bash
# Launch with the official UI caplet
sudo bettercap -iface en0 -caplet http-ui
```

Open your browser to `http://127.0.0.1:80/` and sign in with the default credentials (`user` / `pass`, configured in `ui.cap`).

---

## ⚖️ Legal Notice & Responsible Use

`bettercap` is designed for authorized security assessments, academic network research, and infrastructure hardening. Intercepting or tampering with network traffic on networks without explicit, documented authorization is illegal.

---

## 📋 Everyday Cheat Sheet & Useful Aliases

### Quick Commands

| Task | Command in Bettercap |
| :--- | :--- |
| **Start Recon** | `net.recon on` |
| **Show Host Table** | `net.show` |
| **Set Spoof Target** | `set arp.spoof.targets <IP>` |
| **Enable ARP Spoof** | `arp.spoof on` |
| **Sniff Packets** | `net.sniff on` |
| **Start Web UI** | `http.server on` |
| **Exit Cleanly** | `q` or `exit` |

---

## 🗑️ Uninstallation

```bash
brew uninstall bettercap
```
