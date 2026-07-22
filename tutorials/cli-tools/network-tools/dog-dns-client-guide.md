# Dog & Doggo DNS Lookup Client Guide

`dog` and `doggo` are modern, user-friendly command-line DNS lookup clients designed as colorful, intuitive alternatives to traditional `dig`. They support colorful table outputs, JSON formatting for automation, and encrypted DNS transports including **DNS-over-HTTPS (DoH)**, **DNS-over-TLS (DoT)**, and **DNS-over-QUIC (DoQ)**.

---

## 📚 Table of Contents

1. [Overview & Comparison (`dig` vs `doggo`)](#overview--comparison-dig-vs-doggo)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic & Special DNS Record Queries](#basic--special-dns-record-queries)
4. [Querying Public & Custom Resolvers](#querying-public--custom-resolvers)
5. [Encrypted DNS: DoH (HTTPS), DoT (TLS) & DoQ (QUIC)](#encrypted-dns-doh-https-dot-tls--doq-quic)
6. [JSON Output & Shell Integration (`jq`)](#json-output--shell-integration-jq)
7. [Reverse IP Lookups & Network Diagnostics](#reverse-ip-lookups--network-diagnostics)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Comparison (`dig` vs `doggo`)

Traditional `dig` outputs verbose, unformatted text designed for 1990s BIND servers. `doggo` formats DNS responses cleanly into color-coded tables:

- **`dog`**: Rust-based CLI DNS client featuring colored output and DoH support.
- **`doggo`**: Go-based active continuation featuring support for DoH, DoT, DoQ, NDP, interactive web UI, and JSON formatting.

---

## ⚙️ Installation via Homebrew

```bash
# Install doggo (recommended active tool)
brew install doggo

# Or install dog
brew install dog

# Verify installation
doggo --version
```

---

## 🚀 Basic & Special DNS Record Queries

### 1. Query A (IPv4) & AAAA (IPv6) Records
```bash
# Query IPv4 address (A record)
doggo example.com

# Query IPv6 address (AAAA record)
doggo example.com AAAA
```

### 2. Query Mail, SPF & Security Records (MX, TXT, CAA, SOA)
```bash
# Query Mail Exchanger (MX) records
doggo github.com MX

# Query TXT records (SPF, DMARC, DKIM verifications)
doggo google.com TXT

# Query DMARC policy record specifically
doggo _dmarc.google.com TXT

# Query CAA (Certification Authority Authorization) records
doggo apple.com CAA

# Query Start of Authority (SOA) record
doggo wikipedia.org SOA
```

### 3. Query Name Servers (NS), CNAMEs & SRV Records
```bash
# Query Name Servers for domain
doggo apple.com NS

# Query Canonical Name (CNAME)
doggo www.github.com CNAME

# Query Service (SRV) records (e.g. Minecraft or VoIP services)
doggo _minecraft._tcp.hypixel.net SRV
```

---

## 🌐 Querying Public & Custom Resolvers

Override system DNS settings to query specific public resolvers or test local DNS resolution.

```bash
# Query via Cloudflare (1.1.1.1)
doggo example.com @1.1.1.1

# Query via Google (8.8.8.8)
doggo example.com @8.8.8.8

# Query via Quad9 (9.9.9.9)
doggo example.com @9.9.9.9

# Query via AdGuard DNS (94.140.14.14)
doggo example.com @94.140.14.14
```

---

## 🔒 Encrypted DNS: DoH (HTTPS), DoT (TLS) & DoQ (QUIC)

Bypass local ISP monitoring and test encrypted DNS resolution endpoints.

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

# Query via Quad9 DoT
doggo example.com @tls://dns.quad9.net
```

### 3. DNS-over-QUIC (DoQ)
```bash
# Query via AdGuard DNS over QUIC
doggo example.com @quic://dns.adguard.com
```

---

## 📊 JSON Output & Shell Integration (`jq`)

Generate structured JSON outputs for automation scripts and parse specific records using `jq`.

### 1. Extract IP Addresses to Shell Variable (`--short`)
```bash
# Get raw IP output suitable for shell scripts
IP=$(doggo example.com --short | head -n 1)
echo "Resolved IP: $IP"
```

### 2. Parse Structured JSON with `jq`
```bash
# Export full response to JSON
doggo example.com --json | jq .

# Extract only answer IP addresses from JSON
doggo example.com --json | jq -r '.responses[0].answers[].address'

# Extract MX preference and exchange target
doggo github.com MX --json | jq -r '.responses[0].answers[] | "\(.mx.preference) \(.mx.target)"'
```

---

## 🔎 Reverse IP Lookups & Network Diagnostics

### 1. Reverse IP Lookup (PTR Record)
```bash
# Perform PTR reverse lookup for an IPv4 address
doggo 8.8.8.8 PTR

# Reverse lookup for Cloudflare IP
doggo 1.1.1.1 PTR
```

### 2. Compare DNS Propagation Across Resolvers
```bash
# Compare DNS resolution across Cloudflare, Google, and Quad9
doggo example.com @1.1.1.1 @8.8.8.8 @9.9.9.9
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Query IPv4 (A) record | `doggo example.com` |
| Query IPv6 (AAAA) record | `doggo example.com AAAA` |
| Query MX mail servers | `doggo example.com MX` |
| Query TXT / SPF / DMARC | `doggo _dmarc.example.com TXT` |
| Specific DNS resolver | `doggo example.com @1.1.1.1` |
| Query via DoH (HTTPS) | `doggo example.com @https://cloudflare-dns.com/dns-query` |
| Query via DoT (TLS) | `doggo example.com @tls://1.1.1.1` |
| Reverse IP lookup (PTR) | `doggo 8.8.8.8 PTR` |
| Output raw IP for script | `doggo example.com --short` |
| Export JSON for `jq` | `doggo example.com --json \| jq .` |
