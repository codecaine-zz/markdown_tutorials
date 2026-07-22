# Dog & Doggo DNS Client Guide

`dog` and `doggo` are modern, user-friendly command-line DNS lookup clients designed as colorful, intuitive alternatives to `dig`. They support colorful table outputs, JSON formatting for automation, and secure DNS transports including **DNS-over-HTTPS (DoH)** and **DNS-over-TLS (DoT)**.

---

## 📚 Table of Contents

1. [Overview & Comparison (`dig` vs `dog` / `doggo`)](#overview-comparison-dig-vs-dog-doggo)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic DNS Record Queries](#basic-dns-record-queries)
4. [Querying Custom DNS Resolvers](#querying-custom-dns-resolvers)
5. [Secure DNS: DoH (HTTPS) & DoT (TLS)](#secure-dns-doh-https-dot-tls)
6. [JSON Output & Scripting (`jq`)](#json-output-scripting-jq)
7. [Reverse Lookups & Common Diagnostics](#reverse-lookups-common-diagnostics)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Comparison (`dig` vs `dog` / `doggo`)

Traditional `dig` outputs verbose, unformatted text designed for 1990s BIND servers. `dog` and `doggo` format DNS responses cleanly:

- **`dog`**: Rust-based CLI DNS client featuring colored output and DoH support.
- **`doggo`**: Go-based active continuation of `dog` with enhanced protocol support (DoH, DoT, DoQ), NDP, and interactive search modes.

---

## ⚙️ Installation via Homebrew

```bash
# Install doggo (modern recommended tool)
brew install doggo

# Or install dog
brew install dog

# Verify installation
doggo --version
```

---

## 🚀 Basic DNS Record Queries

### 1. Query A (IPv4) & AAAA (IPv6) Records
```bash
# Query IPv4 address (A record)
doggo example.com

# Query IPv6 address (AAAA record)
doggo example.com AAAA
```

### 2. Query Mail & Verification Records (MX & TXT)
```bash
# Query Mail Exchanger (MX) records
doggo github.com MX

# Query TXT records (SPF, DKIM, DMARC verifications)
doggo google.com TXT
```

### 3. Query Name Servers (NS) & CNAMEs
```bash
# Query Name Servers for domain
doggo apple.com NS

# Query Canonical Name (CNAME)
doggo www.github.com CNAME
```

---

## 🌐 Querying Custom DNS Resolvers

Override your system DNS settings to query specific public resolvers (Cloudflare, Google, Quad9) or custom internal DNS servers.

```bash
# Query via Cloudflare (1.1.1.1)
doggo example.com @1.1.1.1

# Query via Google (8.8.8.8)
doggo example.com @8.8.8.8

# Query via Quad9 (9.9.9.9)
doggo example.com @9.9.9.9
```

---

## 🔒 Secure DNS: DoH (HTTPS) & DoT (TLS)

Bypass local ISP DNS monitoring and query encrypted DNS endpoints securely.

### 1. DNS-over-HTTPS (DoH)
```bash
# Query via Cloudflare DoH endpoint
doggo example.com @https://cloudflare-dns.com/dns-query

# Query via Google DoH endpoint
doggo example.com @https://dns.google/dns-query
```

### 2. DNS-over-TLS (DoT)
```bash
# Query via Cloudflare DoT (port 853)
doggo example.com @tls://1.1.1.1
```

---

## 📊 JSON Output & Scripting (`jq`)

Generate structured JSON outputs for CLI pipelines and parse specific attributes using `jq`.

```bash
# Export response to JSON format
doggo example.com --json | jq .

# Extract only IP addresses from query response
doggo example.com --json | jq -r '.responses[0].answers[].address'
```

---

## 🔎 Reverse Lookups & Common Diagnostics

### 1. Reverse IP Lookup (PTR)
```bash
# Perform PTR reverse lookup for an IP address
doggo 8.8.8.8 PTR
```

### 2. Inspect Short Raw Output
```bash
# Output IP addresses only (clean for shell variables)
doggo example.com --short
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Query A record | `doggo example.com` |
| Query MX record | `doggo example.com MX` |
| Query TXT record | `doggo example.com TXT` |
| Specific DNS server | `doggo example.com @1.1.1.1` |
| Encrypted DoH query | `doggo example.com @https://cloudflare-dns.com/dns-query` |
| Reverse IP lookup | `doggo 8.8.8.8 PTR` |
| Output raw JSON | `doggo example.com --json` |
