# Medusa — Fast, Modular Network Login Brute-Forcer Guide

A complete, production-grade guide to **Medusa** (`medusa`), a high-speed, parallelized, modular network authentication cracker designed for penetration testers and system administrators, featuring thread stability and fine-grained session rate-limiting.

---

## 📑 Table of Contents

- [1. Overview & Medusa vs Hydra Comparison](#1-overview-medusa-vs-hydra-comparison)
  - [Design Philosophy: Modular Architecture & Thread Stability](#design-philosophy-modular-architecture-thread-stability)
  - [When to Choose Medusa Over Hydra](#when-to-choose-medusa-over-hydra)
- [2. Installation & Module Verification](#2-installation-module-verification)
  - [Installing Medusa via Homebrew (macOS)](#installing-medusa-via-homebrew-macos)
  - [Linux Package Installation](#linux-package-installation)
  - [Listing Available Authentication Modules](#listing-available-authentication-modules)
- [3. CLI Flags & Operational Parameters](#3-cli-flags-operational-parameters)
  - [Host, User & Password Selection Options](#host-user-password-selection-options)
  - [Concurrency, Threading & Timeout Modifiers](#concurrency-threading-timeout-modifiers)
  - [Throttling & Delay Options to Evade Lockouts](#throttling-delay-options-to-evade-lockouts)
- [4. Service Authentication Recipes](#4-service-authentication-recipes)
  - [SSH Remote Shell Brute-Force](#ssh-remote-shell-brute-force)
  - [FTP & Telnet Authentication](#ftp-telnet-authentication)
  - [HTTP / HTTPS Web Basic & Digest Auth](#http-https-web-basic-digest-auth)
  - [SMB & Windows Domain Authentication](#smb-windows-domain-authentication)
  - [Database Servers (MySQL & PostgreSQL)](#database-servers-mysql-postgresql)
- [5. Advanced Auditing Patterns](#5-advanced-auditing-patterns)
  - [Auditing Multiple Targets from Subnet Lists](#auditing-multiple-targets-from-subnet-lists)
  - [User-First vs Password-First Testing Order](#user-first-vs-password-first-testing-order)
  - [Output Logging & Reporting Results](#output-logging-reporting-results)
- [6. Defensive Hardening & Detection](#6-defensive-hardening-detection)
  - [Fail2ban & IP Rate Limiting](#fail2ban-ip-rate-limiting)
  - [Account Lockout Policies & Multi-Factor Auth](#account-lockout-policies-multi-factor-auth)
- [7. Quick Reference Cheat Sheet & FAQ](#7-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Medusa vs Hydra Comparison

### Design Philosophy: Modular Architecture & Thread Stability

**Medusa** is an open-source, parallelized, multi-threaded network login brute-forcer created to test weak authentication credentials across network services.

While **THC-Hydra** is famously broad with over 50 supported protocols, **Medusa** focuses on:
1. **Thread Stability**: Core engine design strictly isolates dynamic module execution (`.mod` shared libraries), preventing segmentation faults during high-concurrency scans.
2. **Session Throttling**: Granular control over connection delays, preventing the cracker from overwhelming brittle embedded devices or dropping TCP packets.
3. **Flexible Combination Testing**: Native ability to iterate user-first or password-first (spraying) to navigate enterprise account lockout policies.

```
┌─────────────────────────────────────────────────────────────┐
│                     Medusa Core Engine                      │
│                                                             │
│       ┌─────────────── Host / Target Pool ──────────────┐   │
│       │                                                 │   │
│       ▼                                                 ▼   │
│ ┌───────────┐ ┌───────────┐ ┌───────────┐ ┌───────────┐     │
│ │ Thread #1 │ │ Thread #2 │ │ Thread #3 │ │ Thread #4 │     │
│ └─────┬─────┘ └─────┬─────┘ └─────┬─────┘ └─────┬─────┘     │
│       │             │             │             │           │
│       ▼             ▼             ▼             ▼           │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │             Dynamic Protocol Modules (*.mod)            │ │
│ │  ssh.mod • ftp.mod • http.mod • smb.mod • mysql.mod     │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### When to Choose Medusa Over Hydra

- Choose **Medusa** when testing IoT devices, embedded routers, or legacy network equipment that crash or drop packets when exposed to Hydra's aggressive thread pools.
- Choose **Medusa** when performing password spraying where a single fixed password is tested across all users with strict pause intervals between attempts.
- Choose **Hydra** when auditing exotic protocols (such as Cisco AAA, Asterisk, RDP, or Oracle DB) that lack Medusa modules.

---

## 2. Installation & Module Verification

### Installing Medusa via Homebrew (macOS)

Install Medusa and its OpenSSL/SSH cryptographic dependencies via Homebrew:

```bash
# Install Medusa
brew install medusa

# Verify binary installation
which medusa
medusa -V
```

### Linux Package Installation

On Debian, Ubuntu, or Kali Linux:

```bash
# Install via apt
sudo apt-get update
sudo apt-get install -y medusa
```

### Listing Available Authentication Modules

Medusa utilizes dynamically loadable `.mod` libraries. Verify installed modules:

```bash
# List all available protocol modules
medusa -d
```

Example module output:
```text
Available Modules:
  + cvs.mod
  + ftp.mod
  + http.mod
  + imap.mod
  + mssql.mod
  + mysql.mod
  + nntp.mod
  + pcanywhere.mod
  + pop3.mod
  + postgres.mod
  + rdp.mod
  + rexec.mod
  + rlogin.mod
  + rsh.mod
  + smbnt.mod
  + smtp.mod
  + snmp.mod
  + ssh.mod
  + telnet.mod
  + vnc.mod
  + web-form.mod
```

---

## 3. CLI Flags & Operational Parameters

### Host, User & Password Selection Options

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-h` | `TARGET` | Single target host (IP or hostname). |
| `-H` | `FILE` | Path to text file containing target hosts (one per line). |
| `-u` | `USER` | Single username to test. |
| `-U` | `FILE` | Path to text file containing usernames. |
| `-p` | `PASSWORD`| Single password to test. |
| `-P` | `FILE` | Path to text file containing passwords (wordlist). |
| `-C` | `FILE` | Combo file containing `username:password` pairs per line. |
| `-M` | `MODULE` | Name of protocol module to execute (e.g. `ssh`, `ftp`, `http`). |
| `-m` | `PARAM` | Module-specific options (e.g. URI path or authentication type). |

### Concurrency, Threading & Timeout Modifiers

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-t` | `NUM` | Total number of concurrent login tests (global thread limit). |
| `-T` | `NUM` | Maximum number of concurrent target hosts to test simultaneously. |
| `-r` | `SEC` | Retry delay in seconds if a network socket fails or times out. |
| `-R` | `NUM` | Maximum retry attempts per connection. |

### Throttling & Delay Options to Evade Lockouts

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-s` | None | Enable SSL / TLS encryption for the connection. |
| `-e` | `[n/s/ns]` | Additional checks: `n` (null password), `s` (password same as user). |
| `-f` | None | Stop scanning a target host after the first valid credential found. |
| `-F` | None | Stop scanning globally across all hosts after any valid credential found. |
| `-v` | `1-6` | Verbosity level (default: `5`, displays errors and successes). |

---

## 4. Service Authentication Recipes

### SSH Remote Shell Brute-Force

Audit SSH authentication on port 22:

```bash
# Single user with wordlist, stop on first valid credential
medusa -h 192.168.1.50 -u root -P /usr/share/wordlists/rockyou.txt -M ssh -f

# Multiple users and multiple passwords with thread limit
medusa -h 192.168.1.50 -U users.txt -P passwords.txt -M ssh -t 4
```

### FTP & Telnet Authentication

```bash
# FTP login audit with SSL disabled
medusa -h 192.168.1.10 -u admin -P passwords.txt -M ftp -f

# Telnet login audit on legacy network switches
medusa -h 192.168.1.1 -u admin -P passwords.txt -M telnet -t 2
```

### HTTP / HTTPS Web Basic & Digest Auth

```bash
# HTTP Basic Authentication on /admin directory
medusa -h 192.168.1.100 -u admin -P passwords.txt -M http -m DIR:/admin/ -f

# HTTPS (SSL enabled) Basic Auth
medusa -h 192.168.1.100 -s -u admin -P passwords.txt -M http -m DIR:/dashboard/ -f
```

### SMB & Windows Domain Authentication

Test credentials against Windows SMB file sharing (ports 139 / 445):

```bash
# Local account SMB authentication
medusa -h 192.168.1.120 -u Administrator -P passwords.txt -M smbnt -f

# Specifying Windows Domain via module options
medusa -h 192.168.1.120 -u Administrator -P passwords.txt -M smbnt -m DOMAIN:CORP -f
```

### Database Servers (MySQL & PostgreSQL)

```bash
# MySQL database authentication
medusa -h 192.168.1.200 -u root -P passwords.txt -M mysql -f

# PostgreSQL database authentication
medusa -h 192.168.1.200 -u postgres -P passwords.txt -M postgres -f
```

---

## 5. Advanced Auditing Patterns

### Auditing Multiple Targets from Subnet Lists

```bash
# Audit an entire host list across port 22
medusa -H live_hosts.txt -u admin -P passwords.txt -M ssh -T 5 -t 10 -f
```

### User-First vs Password-First Testing Order

By default, crackers test all passwords against user 1, then all passwords against user 2. If a lockout threshold is set to 5 failed attempts, the account locks immediately.

**Password Spraying Pattern**: Test 1 known password against 500 usernames:

```bash
medusa -H domain_controllers.txt -U enterprise_users.txt -p 'Autumn2024!' -M smbnt
```

### Output Logging & Reporting Results

Send clean results to output files for client audit reports:

```bash
# Log clean audit findings to file
medusa -h 192.168.1.50 -u admin -P passwords.txt -M ssh -O audit_results.txt
```

---

## 6. Defensive Hardening & Detection

### Fail2ban & IP Rate Limiting

Automatically block IPs generating repeated authentication failures within short time windows:
- Configure jail rules in `/etc/fail2ban/jail.local` for SSH, FTP, and Web authentication.
- Implement progressive ban times (e.g. 10 minutes for first offense, 24 hours for repeated attempts).

### Account Lockout Policies & Multi-Factor Auth

- Enforce progressive lockout intervals (e.g. 5 failed attempts locks the account for 15 minutes).
- Enforce hardware tokens (FIDO2 / U2F) or time-based OTPs (TOTP) to render password-only brute-forcing useless.
- Disable password authentication for SSH in `/etc/ssh/sshd_config` (`PasswordAuthentication no`).

---

## 7. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Action | Command Syntax |
| :--- | :--- |
| **List Available Modules** | `medusa -d` |
| **SSH Audit (Single User)** | `medusa -h TARGET -u USER -P WORDLIST -M ssh -f` |
| **SSH Audit (Multi-User)** | `medusa -h TARGET -U USERS -P WORDLIST -M ssh -t 4` |
| **FTP Audit** | `medusa -h TARGET -u USER -P WORDLIST -M ftp -f` |
| **HTTP Basic Auth** | `medusa -h TARGET -u USER -P WORDLIST -M http -m DIR:/path/` |
| **HTTPS Basic Auth (SSL)** | `medusa -h TARGET -s -u USER -P WORDLIST -M http -m DIR:/path/` |
| **SMB / Active Directory** | `medusa -h TARGET -u USER -P WORDLIST -M smbnt -m DOMAIN:NAME` |
| **MySQL Server** | `medusa -h TARGET -u root -P WORDLIST -M mysql -f` |
| **Stop on First Find** | Append `-f` (single host) or `-F` (all hosts) |
| **Write Output to File** | Append `-O results.txt` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **Module not found**: If `medusa -M ssh` errors with `Module not found`, ensure dynamic modules are located in `/usr/lib/medusa/modules/` or pass explicit path via `-m PATH:/path/to/modules`.

> [!TIP]
> **Connection Throttling**: If targets start dropping connections or returning false negatives, reduce total threads using `-t 2` or `-t 1` and increase retry timeout with `-r 5`.

> [!IMPORTANT]
> **Authorized Testing Only**: Only execute network authentication tools against hosts, IP addresses, and applications you own or have received formal, written authorization to audit.
