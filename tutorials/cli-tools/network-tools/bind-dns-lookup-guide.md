# BIND DNS Toolkit (dig, host, nslookup, delv) Complete Guide

The **BIND (Berkeley Internet Name Domain)** suite is the industry standard software foundation for the Internet's Domain Name System (DNS). When installed via Homebrew (`brew install bind`), it delivers the latest, fully featured versions of essential DNS diagnosis client utilities: **`dig`** (Domain Information Groper), **`host`**, **`nslookup`**, and **`delv`** (DNSSEC validation tool).

---

## 📚 Table of Contents

1. [Overview of the BIND Utilities](#overview-of-the-bind-utilities)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Mastering `dig` (Domain Information Groper)](#mastering-dig-domain-information-groper)
4. [Querying Specific DNS Record Types](#querying-specific-dns-record-types)
5. [Querying Custom DNS Nameservers](#querying-custom-dns-nameservers)
6. [Short & Script-Friendly Output (`+short`)](#short-script-friendly-output-short)
7. [Tracing DNS Delegation Hierarchies (`+trace`)](#tracing-dns-delegation-hierarchies-trace)
8. [Reverse DNS Lookups (`-x`)](#reverse-dns-lookups--x)
9. [DNSSEC Validation with `delv`](#dnssec-validation-with-delv)
10. [Quick Lookups with `host`](#quick-lookups-with-host)
11. [Interactive Queries with `nslookup`](#interactive-queries-with-nslookup)
12. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
13. [Uninstallation](#uninstallation)

---

## 🔍 Overview of the BIND Utilities

- **`dig`**: The most powerful and flexible DNS troubleshooting tool, displaying comprehensive query headers, TTLs, and authoritative response sections.
- **`host`**: A clean, concise utility for rapid hostname-to-IP and IP-to-hostname translations.
- **`nslookup`**: The classic interactive and non-interactive DNS query tool.
- **`delv`**: Modern DNSSEC verification utility that tests cryptographic signature chains from root trust anchors.

---

## ⚙️ Homebrew Installation & Verification

### 1. Install BIND via Homebrew

```bash
brew install bind
```

### 2. Verify Utility Versions

```bash
dig -v
host -V
delv -v
```

Output:
```text
DiG 9.20.27
host 9.20.27
delv 9.20.27
```

---

## 🚀 Mastering `dig` (Domain Information Groper)

Perform a basic lookup:

```bash
dig example.com
```

### Anatomy of a `dig` Response

```text
; <<>> DiG 9.20.27 <<>> example.com
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 48123
;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 1

;; OPT PSEUDOSECTION:
; EDNS: version: 0, flags:; udp: 1232
;; QUESTION SECTION:
;example.com.			IN	A

;; ANSWER SECTION:
example.com.		86400	IN	A	93.184.216.34

;; Query time: 24 msec
;; SERVER: 1.1.1.1#53(1.1.1.1) (UDP)
;; WHEN: Sat Sep 12 22:20:00 CDT 2026
;; MSG SIZE  rcvd: 56
```

### Key Sections:
- **`status: NOERROR`**: Request succeeded (`NXDOMAIN` means domain does not exist; `SERVFAIL` means resolution failed).
- **`flags: qr rd ra`**: Query Response (`qr`), Recursion Desired (`rd`), Recursion Available (`ra`).
- **`ANSWER SECTION`**: Contains record name, remaining **TTL** (86400s), class (`IN`), type (`A`), and target IP (`93.184.216.34`).
- **`Query time`**: Latency to receive response (24ms).

---

## 🎯 Querying Specific DNS Record Types

Specify the record type after the domain:

```bash
# IPv4 Address (A)
dig example.com A

# IPv6 Address (AAAA)
dig example.com AAAA

# Mail Exchange Servers (MX)
dig github.com MX

# Authoritative Nameservers (NS)
dig example.com NS

# Text / SPF / Verification Records (TXT)
dig google.com TXT

# Canonical Name / Aliases (CNAME)
dig www.github.com CNAME

# Start of Authority (SOA)
dig example.com SOA

# Check All Records (ANY)
dig example.com ANY
```

---

## 🌐 Querying Custom DNS Nameservers

Use `@<server>` to query specific upstream DNS resolvers, bypassing your local router:

```bash
# Query Cloudflare DNS (1.1.1.1)
dig @1.1.1.1 example.com

# Query Google Public DNS (8.8.8.8)
dig @8.8.8.8 example.com

# Query Quad9 DNS (9.9.9.9)
dig @9.9.9.9 example.com

# Query an Authoritative Nameserver directly
dig @ns1.cloudflare.com example.com
```

---

## ⚡ Short & Script-Friendly Output (`+short`)

Strip query headers, comments, and statistics for clean piping into scripts:

```bash
# Get only the IP address
dig example.com +short

# Output: 93.184.216.34
```

Filter nameservers:
```bash
dig github.com NS +short
```

---

## 🧭 Tracing DNS Delegation Hierarchies (`+trace`)

Trace the full resolution path starting from the Root (`.`) servers down to the authoritative nameserver:

```bash
dig example.com +trace
```

Output shows every hop:
1. Queries Root Nameservers (`[a-m].root-servers.net.`)
2. Follows referral to TLD Nameservers (`.com`)
3. Follows referral to Authoritative Nameservers (`ns.example.com`)
4. Prints final resolved IP address.

---

## 🔄 Reverse DNS Lookups (`-x`)

Translate an IP address back to its corresponding Pointer Record (PTR / Reverse DNS):

```bash
# Reverse lookup for Cloudflare DNS
dig -x 1.1.1.1 +short
# Output: one.one.one.one.

# Reverse lookup for Google DNS
dig -x 8.8.8.8 +short
# Output: dns.google.
```

---

## 🔐 DNSSEC Validation with `delv`

`delv` (Domain Encryption and Lookup Validator) performs end-to-end DNSSEC cryptographic validation using built-in root trust anchors:

```bash
# Validate DNSSEC chain for a signed domain
delv @1.1.1.1 cloudflare.com
```

Output:
```text
; fully validated
cloudflare.com.		300	IN	A	104.16.132.229
cloudflare.com.		300	IN	A	104.16.133.229
cloudflare.com.		300	IN	RRSIG	A 13 2 300 ...
```
If validation succeeds, `delv` outputs `; fully validated`.

---

## 💡 Quick Lookups with `host`

For fast, human-friendly command line checks:

```bash
# Standard lookup
host example.com
# Output: example.com has address 93.184.216.34

# Reverse IP lookup
host 93.184.216.34

# Query specific record type
host -t MX github.com

# Query specific nameserver
host example.com 1.1.1.1
```

---

## 💬 Interactive Queries with `nslookup`

Run queries interactively:

```bash
nslookup
> set type=mx
> google.com
> server 1.1.1.1
> example.com
> exit
```

Or single-line non-interactive:
```bash
nslookup -type=TXT _dmarc.google.com 8.8.8.8
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Recommended Aliases (`~/.zshrc`)

```bash
# Fast IP extraction
alias my-dns='dig +short'

# Trace complete DNS path
alias dig-trace='dig +trace +nodnssec'

# Reverse lookup
alias rlookup='dig +short -x'
```

### Quick Command Table

| Task | Command |
| :--- | :--- |
| **Lookup IPv4 (A)** | `dig example.com A` |
| **Lookup IPv6 (AAAA)** | `dig example.com AAAA` |
| **Lookup MX Records** | `dig example.com MX +short` |
| **Lookup TXT / SPF** | `dig example.com TXT +short` |
| **Query Specific Resolver** | `dig @1.1.1.1 example.com` |
| **Clean IP Output** | `dig example.com +short` |
| **Trace DNS Hierarchy** | `dig example.com +trace` |
| **Reverse IP Lookup** | `dig -x 8.8.8.8 +short` |
| **Validate DNSSEC** | `delv @1.1.1.1 domain.com` |
| **Simple Host Check** | `host example.com` |

---

## 🗑️ Uninstallation

```bash
brew uninstall bind
```
