# Doggo Modern DNS Client Guide

`doggo` is a modern, human-friendly command-line DNS client written in Go. Designed to replace legacy utilities like `dig`, `nslookup`, and `host`, `doggo` features colorful tabular output, native support for modern secure DNS protocols (**DoH**, **DoT**, **DoQ**, and **DNSCrypt**), reverse lookups, and JSON output formatting.

---

## 📚 Table of Contents

1. [Overview & `dig` vs `doggo` Comparison](#overview--dig-vs-doggo-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Basic DNS Queries](#basic-dns-queries)
4. [Querying Specific Record Types (A, MX, TXT, CNAME, etc.)](#querying-specific-record-types-a-mx-txt-cname-etc)
5. [Using Custom & Secure Resolvers (DoH / DoT / DoQ)](#using-custom--secure-resolvers-doh--dot--doq)
6. [Reverse DNS Lookups (`--reverse`)](#reverse-dns-lookups---reverse)
7. [JSON Output & Scripting Automation](#json-output--scripting-automation)
8. [Interactive TUI Mode](#interactive-tui-mode)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `dig` vs `doggo` Comparison

While `dig` outputs verbose, cluttered raw DNS packets with complex flag structures (`+short`, `+noall`, `+answer`), `doggo` defaults to clean, readable tables with colorized TTL and status codes.

| Feature | Legacy `dig` | `doggo` |
| :--- | :--- | :--- |
| **Output Style** | Verbose BIND format | Clean colored tables |
| **Modern Protocols** | UDP / TCP only (DoH via extra tools) | Built-in DoH, DoT, DoQ, DNSCrypt |
| **Resolver Aliases** | IP addresses only | Short names (`@cloudflare`, `@google`) |
| **Structured Output** | None (requires parsing) | Native JSON (`--json`) |
| **Interactive Mode** | None | Built-in interactive browser |
| **Reverse DNS** | `dig -x <IP>` | `doggo --reverse <IP>` |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install doggo
```

### 2. Install via Go

```bash
go install github.com/mr-karan/doggo/cmd/doggo@latest
```

### 3. Verify Installation

```bash
doggo --version
```

---

## 🚀 Basic DNS Queries

Run a query against standard system resolvers:

```bash
# Query A records for a domain
doggo example.com

# Query multiple domains at once
doggo github.com google.com cloudflare.com
```

### Example Terminal Output:

```text
NAME            TYPE    CLASS   TTL     ADDRESS                 NAMESERVER
example.com.    A       IN      3600s   93.184.216.34           1.1.1.1:53
```

---

## 🔍 Querying Specific Record Types

Specify the DNS record type as an argument:

```bash
# Query Mail Exchange (MX) records
doggo MX github.com

# Query Text (TXT) records (SPF, DKIM, verification tokens)
doggo TXT google.com

# Query Canonical Name (CNAME)
doggo CNAME www.wikipedia.org

# Query Name Servers (NS)
doggo NS debian.org

# Query All Supported Records (ANY)
doggo ANY cloudflare.com
```

---

## 🔒 Using Custom & Secure Resolvers (DoH / DoT / DoQ)

Query specific public DNS providers using their built-in aliases or exact URLs:

### 1. Provider Aliases (`@cloudflare`, `@google`, `@quad9`, `@adguard`)

```bash
# Query using Cloudflare DNS
doggo example.com @cloudflare

# Query using Google DNS
doggo example.com @google

# Query using Quad9
doggo example.com @quad9
```

### 2. DNS-over-HTTPS (DoH)

Send encrypted DNS requests via HTTPS to bypass local ISP inspection:

```bash
# Query via Cloudflare DoH
doggo example.com @https://cloudflare-dns.com/dns-query

# Query via Google DoH
doggo example.com @https://dns.google/dns-query
```

### 3. DNS-over-TLS (DoT) & DNS-over-QUIC (DoQ)

```bash
# Query via DNS-over-TLS
doggo example.com @tls://1.1.1.1:853

# Query via DNS-over-QUIC
doggo example.com @quic://dns.adguard.com:853
```

---

## 🔄 Reverse DNS Lookups (`--reverse` / `-x`)

Find the domain name associated with an IP address:

```bash
# Reverse lookup IPv4
doggo --reverse 8.8.8.8

# Reverse lookup IPv6
doggo --reverse 2606:4700:4700::1111
```

---

## 🤖 JSON Output & Scripting Automation

Pipe structured DNS response objects directly into `jq` for automation:

```bash
# Export full JSON response
doggo example.com --json

# Extract only IP addresses with jq
doggo example.com --json | jq -r '.responses[].answers[].address'
```

---

## 🖥️ Interactive TUI Mode

Launch the interactive DNS explorer to search and switch between record types and resolvers on the fly:

```bash
# Start interactive query shell
doggo --interactive
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Lookup Domain** | `doggo <domain>` |
| **Lookup MX records** | `doggo MX <domain>` |
| **Lookup TXT records** | `doggo TXT <domain>` |
| **Reverse DNS** | `doggo -x <IP>` |
| **Query via Cloudflare** | `doggo <domain> @cloudflare` |
| **Query via DoH** | `doggo <domain> @https://cloudflare-dns.com/dns-query` |
| **JSON Output** | `doggo <domain> --json` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Modern DNS aliases
alias dig='doggo'
alias nslookup='doggo'
alias doh='doggo @cloudflare'
alias rlookup='doggo --reverse'
```

---

## 🗑️ Uninstallation

To remove `doggo`:

```bash
brew uninstall doggo
```
