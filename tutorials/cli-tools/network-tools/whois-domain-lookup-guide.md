# WHOIS (Domain & IP Directory Service) Complete Guide

`whois` is a foundational command-line directory lookup utility used to query databases maintained by Regional Internet Registries (RIRs) and domain name registrars. It provides critical registration metadata for domain names, IP address allocations, Autonomous System Numbers (ASNs), and organizational contact details.

---

## 📚 Table of Contents

1. [Overview & How WHOIS Works](#overview-how-whois-works)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Domain Lookups](#basic-domain-lookups)
4. [Querying Regional Internet Registries (ARIN, RIPE, APNIC)](#querying-regional-internet-registries-arin-ripe-apnic)
5. [Querying Specific WHOIS Host Servers (`-h`)](#querying-specific-whois-host-servers--h)
6. [Parsing Expiration Dates & Status Codes](#parsing-expiration-dates-status-codes)
7. [Automating WHOIS in Shell Scripts](#automating-whois-in-shell-scripts)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & How WHOIS Works

When you perform a `whois` lookup, your client connects to TCP port 43 of a Network Information Center (NIC). 

- **IANA Root Server**: Queries typically start by querying IANA (`whois.iana.org`) to determine which registrar or registry manages the Top-Level Domain (TLD) or IP block.
- **Referral Chasing**: The client automatically follows referrals to the authoritative registrar (e.g. Verisign for `.com`, Nominet for `.uk`).
- **Data Provided**:
  - Domain Registrar (e.g., Cloudflare, Namecheap, GoDaddy).
  - Registration, Modification, and Expiration dates.
  - Authoritative Name Servers (`NS`).
  - Domain Status Codes (e.g., `clientTransferProhibited`, `clientHold`).
  - IP network blocks (CIDR) and netname.

---

## ⚙️ Homebrew Installation & Verification

While macOS includes a default BSD `whois`, installing via Homebrew ensures you have the updated client with modern RIR referral support:

```bash
brew install whois
```

Verify binary path:
```bash
which whois
```

Output:
```text
/opt/homebrew/bin/whois
```

---

## 🌐 Basic Domain Lookups

Querying any top-level domain:

```bash
whois github.com
```

### Sample Output Breakdown

```text
Domain Name: GITHUB.COM
Registry Domain ID: 1264983250_DOMAIN_COM-VRSN
Registrar WHOIS Server: whois.markmonitor.com
Registrar URL: http://www.markmonitor.com
Updated Date: 2026-02-02T18:00:00Z
Creation Date: 2007-10-09T18:20:50Z
Registry Expiry Date: 2028-10-09T18:20:50Z
Registrar: MarkMonitor Inc.
Registrar IANA ID: 292
Domain Status: clientDeleteProhibited
Domain Status: clientTransferProhibited
Domain Status: clientUpdateProhibited
Name Server: DNS1.P08.NSONE.NET
Name Server: DNS2.P08.NSONE.NET
DNSSEC: unsigned
```

---

## 🏢 Querying Regional Internet Registries (ARIN, RIPE, APNIC)

To inspect IP allocations and network owners, query the specific RIR responsible for the region:

| Flag | Registry | Region Covered |
| :--- | :--- | :--- |
| `-a` | **ARIN** | North America, parts of Caribbean |
| `-r` | **RIPE NCC** | Europe, Middle East, Central Asia |
| `-A` | **APNIC** | East Asia, Pacific, Australia |
| `-R` | **Russia** | RIPN database |

### Examples

```bash
# Query North American IP allocation via ARIN
whois -a 142.250.190.46

# Query European IP block via RIPE
whois -r 193.0.6.139
```

---

## 🎯 Querying Specific WHOIS Host Servers (`-h`)

When registrar privacy shields or thick registries hide details on default servers, query the registrar server directly using `-h`:

```bash
# Query Verisign directly for .com/.net domains
whois -h whois.verisign-grs.com example.com

# Query PIR for .org domains
whois -h whois.pir.org eff.org

# Query UK registry (Nominet)
whois -h whois.nic.uk bbc.co.uk
```

---

## ⏳ Parsing Expiration Dates & Status Codes

### 1. Extract Expiration Date in Terminal

Extract the expiration date using `grep` and `awk`:

```bash
whois github.com | grep -iE 'Expiry Date|Expiration Date' | head -n 1
```

### 2. Understanding EPP Domain Status Codes

- **`clientTransferProhibited`**: Registrar lock preventing unauthorized domain transfers (recommended security posture).
- **`clientDeleteProhibited`**: Prevents accidental or malicious deletion.
- **`serverHold`**: Suspended by the registry (often for payment or policy violation).
- **`redemptionPeriod`**: Expired domain in grace period before public deletion.

---

## 🤖 Automating WHOIS in Shell Scripts

Here is an automated shell function that calculates days remaining before a domain expires:

```bash
domain_expiry() {
    local domain="$1"
    local exp_raw
    exp_raw=$(whois "$domain" | grep -iE "Expiry Date|Expiration Date|registry expiry" | head -n 1 | awk '{print $NF}')
    
    if [ -z "$exp_raw" ]; then
        echo "Could not parse expiration date for $domain"
        return 1
    fi
    echo "Domain: $domain expires on: $exp_raw"
}
```

Usage:
```bash
domain_expiry example.com
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Recommended Aliases (`~/.zshrc`)

```bash
# Query domain nameservers only
alias whois-ns='whois | grep -i "Name Server"'

# Check domain expiration quickly
alias whois-exp='whois | grep -iE "Expiry Date|Expiration Date"'
```

### Command Reference

| Task | Command |
| :--- | :--- |
| **Lookup Domain** | `whois example.com` |
| **Lookup IP Allocation** | `whois 1.1.1.1` |
| **Query ARIN Registry** | `whois -a 8.8.8.8` |
| **Query RIPE Registry** | `whois -r 193.0.0.1` |
| **Specify WHOIS Server** | `whois -h whois.verisign-grs.com example.com` |
| **Extract Registrar** | `whois example.com \| grep -i "Registrar:"` |
| **Extract Nameservers** | `whois example.com \| grep -i "Name Server:"` |

---

## 🗑️ Uninstallation

```bash
brew uninstall whois
```
