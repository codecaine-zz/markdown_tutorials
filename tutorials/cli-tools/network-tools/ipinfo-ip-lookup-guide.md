# IPinfo CLI Complete Guide

`ipinfo` is the official command-line interface for IPinfo.io—the premier IP address intelligence and geolocation API. Written in Go, the `ipinfo` CLI provides lightning-fast IP geolocation lookups, ASN intelligence, CIDR and subnet calculation, bulk IP lookups, log-file IP grepping, and world-map visualization directly in your terminal.

---

## 📚 Table of Contents

1. [Overview & Capabilities](#overview-capabilities)
2. [Homebrew Installation & API Token Setup](#homebrew-installation-api-token-setup)
3. [Looking Up IP Addresses & Domains](#looking-up-ip-addresses-domains)
4. [Inspecting Your Public IP (`myip`)](#inspecting-your-public-ip-myip)
5. [ASN (Autonomous System Number) Exploration](#asn-autonomous-system-number-exploration)
6. [Extracting & Filtering Specific Fields (`-f`)](#extracting-filtering-specific-fields--f)
7. [Subnet & CIDR Calculation Tools](#subnet-cidr-calculation-tools)
8. [Bulk Lookups & Log Summarization](#bulk-lookups-log-summarization)
9. [Filtering IPs from Text Streams (`grepip`)](#filtering-ips-from-text-streams-grepip)
10. [Generating Geographic IP Maps (`map`)](#generating-geographic-ip-maps-map)
11. [Output Formats (JSON, CSV, YAML)](#output-formats-json-csv-yaml)
12. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
13. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Capabilities

While web APIs exist for geolocation, `ipinfo` brings local caching, CIDR manipulation, and batch processing directly to your shell:

- **Single & Bulk Lookups**: Instant geolocation, timezone, reverse DNS hostname, carrier, and ASN data.
- **My IP Details**: Comprehensive details for your current public egress IP (`ipinfo myip`).
- **Subnet Toolkit**: Commands like `prips`, `cidr2range`, `range2cidr`, `splitcidr`, and `calc`.
- **Log Stream Extraction**: Grep and filter IP addresses from server access logs (`grepip`).
- **Map Generation**: Visual geographic heatmaps plotted in your browser with a single command.
- **Format Flexibility**: Output in human-readable colorized tables, raw JSON (`-j`), CSV (`-c`), or YAML (`-y`).

---

## ⚙️ Homebrew Installation & API Token Setup

### 1. Install via Homebrew

```bash
brew install ipinfo-cli
```

### 2. Verify Installation

```bash
which ipinfo
ipinfo version
```

Output:
```text
/opt/homebrew/bin/ipinfo
3.3.2
```

### 3. (Optional) Initialize API Token

`ipinfo` provides up to 50,000 free lookups per month without an API token, but authenticating unlocks higher rate limits, company domains, carrier/mobile detection, and abuse data:

```bash
# Authenticate interactively
ipinfo init

# Or pass via flag or environment variable
export IPINFO_TOKEN="your_token_here"
```

---

## 🌐 Looking Up IP Addresses & Domains

Provide any IPv4 address, IPv6 address, or hostname:

```bash
# Look up Cloudflare DNS
ipinfo 1.1.1.1

# Look up a domain name
ipinfo github.com
```

### Sample Terminal Output

```text
Core
- IP          1.1.1.1
- Anycast     true
- Hostname    one.one.one.one
- City        San Jose
- Region      California
- Country     United States (US)
- Loc         37.3388,-121.8916
- Org         AS13335 Cloudflare, Inc.
- Postal      95113
- Timezone    America/Los_Angeles
```

---

## 📍 Inspecting Your Public IP (`myip`)

Quickly discover your egress IP address, active ISP, and geo-location:

```bash
# Full information table
ipinfo myip

# Get only your raw IP string (useful in shell scripts)
ipinfo myip -f ip
```

---

## 🏢 ASN (Autonomous System Number) Exploration

Investigate entire networks, telecommunication providers, and transit carriers by ASN:

```bash
# Explore Google's backbone (AS15169)
ipinfo AS15169

# Explore Cloudflare (AS13335)
ipinfo as13335
```

### Sample Output:
```text
ASN
- ASN          AS15169
- Name         Google LLC
- Country      United States (US)
- Allocated    2000-03-30
- Registry     arin
- Domain       google.com
- Num IPs      15,482,880
- Type         hosting
```

---

## 🎯 Extracting & Filtering Specific Fields (`-f`)

Extract individual data points for automated bash scripts:

```bash
# Extract country code
ipinfo 8.8.8.8 -f country

# Extract organization and timezone
ipinfo 8.8.8.8 -f org,timezone

# Extract latitude/longitude coordinates
ipinfo 8.8.8.8 -f loc
```

---

## 🧮 Subnet & CIDR Calculation Tools

`ipinfo` includes a full suite of built-in network calculators:

### 1. Print All IPs in a CIDR (`prips`)

```bash
# Enumerate all individual IPs in a /29 subnet
ipinfo prips 192.168.1.0/29
```

Output:
```text
192.168.1.0
192.168.1.1
192.168.1.2
192.168.1.3
192.168.1.4
192.168.1.5
192.168.1.6
192.168.1.7
```

### 2. Convert CIDR to IP Range (`cidr2range`)

```bash
ipinfo cidr2range 10.0.0.0/24
# Output: 10.0.0.0-10.0.0.255
```

### 3. Convert IP Range to CIDR (`range2cidr`)

```bash
ipinfo range2cidr 192.168.1.0 192.168.1.255
# Output: 192.168.1.0/24
```

### 4. Split CIDR into Smaller Subnets (`splitcidr`)

```bash
# Divide a /24 network into four /26 subnets
ipinfo splitcidr 192.168.1.0/24 26
```

---

## 📊 Bulk Lookups & Log Summarization

### 1. Bulk IP Geolocation

Lookup dozens or hundreds of IP addresses in a single API call:

```bash
# Pipe IPs to bulk lookup
cat ips.txt | ipinfo bulk
```

### 2. Summarize Network Statistics (`summarize`)

Analyze a collection of IP addresses to understand geographic and ASN distributions:

```bash
cat << 'EOF' > web_traffic.txt
8.8.8.8
1.1.1.1
93.184.216.34
142.250.190.46
EOF

ipinfo summarize web_traffic.txt
```

The summary output provides:
- Total unique IPs
- Top Countries
- Top ASNs / Providers
- Top Cities
- Anycast IP percentages

---

## 🔍 Filtering IPs from Text Streams (`grepip`)

Extract, filter, and validate IP addresses from arbitrary text files, logs, or command outputs:

```bash
# Grep all valid public IPv4 addresses from an Nginx log
cat /var/log/nginx/access.log | ipinfo grepip --only-public

# Exclude private, loopback, and multicast ranges
ipinfo grepip -o myfile.txt
```

---

## 🗺️ Generating Geographic IP Maps (`map`)

Generate an interactive web map plotting the physical coordinates of a group of IP addresses:

```bash
# Generate map URL from list of IPs
cat web_traffic.txt | ipinfo map
```

`ipinfo` uploads the coordinates and immediately outputs a visual report URL (e.g. `https://ipinfo.io/tools/map/<id>`) that opens in your browser showing pins on a global map.

---

## 📤 Output Formats (JSON, CSV, YAML)

```bash
# Export as raw JSON
ipinfo 8.8.8.8 -j

# Parse JSON directly with jq
ipinfo 8.8.8.8 -j | jq -r '.org'

# Export as CSV table
ipinfo 8.8.8.8 -c

# Export as YAML
ipinfo 8.8.8.8 -y
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Recommended Aliases (`~/.zshrc` or `~/.bashrc`)

```bash
# Get my current public IP
alias myip='ipinfo myip -f ip'

# Quick JSON IP lookup
alias ip-json='ipinfo -j'

# Summarize IPs from stdin
alias ip-sum='ipinfo summarize'
```

### Command Reference

| Task | Command |
| :--- | :--- |
| **Lookup Single IP** | `ipinfo 1.1.1.1` |
| **Inspect Public IP** | `ipinfo myip` |
| **Extract Specific Field** | `ipinfo 8.8.8.8 -f city,region,country` |
| **Lookup ASN Network** | `ipinfo AS15169` |
| **Enumerate Subnet IPs** | `ipinfo prips 10.0.0.0/28` |
| **Convert Range to CIDR** | `ipinfo range2cidr 192.168.0.0 192.168.0.255` |
| **Summarize IP Log** | `cat access.log \| ipinfo summarize` |
| **Generate Global Map** | `cat ip_list.txt \| ipinfo map` |
| **Filter Public IPs from Text** | `cat raw_text.log \| ipinfo grepip --only-public` |
| **JSON Output** | `ipinfo 1.1.1.1 -j` |

---

## 🗑️ Uninstallation

```bash
brew uninstall ipinfo-cli
```
