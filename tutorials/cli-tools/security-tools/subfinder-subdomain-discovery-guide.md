# Subfinder (Passive Subdomain Discovery) Complete Guide

`subfinder` is a fast, passive subdomain enumeration tool written in Go by ProjectDiscovery. Designed for penetration testers, bug bounty hunters, and reconnaissance specialists, `subfinder` aggregates subdomains by querying dozens of passive online data feeds, search engines, threat intelligence APIs, and Certificate Transparency (CT) logs without sending a single probe to the target domain's infrastructure.

---

## 📚 Table of Contents

1. [Overview & Passive Reconnaissance Model](#overview-passive-reconnaissance-model)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Subdomain Enumeration (`-d`)](#basic-subdomain-enumeration--d)
4. [Configuring API Keys (`provider-config.yaml`)](#configuring-api-keys-provider-configyaml)
5. [Bulk Domain Enumeration (`-dL`)](#bulk-domain-enumeration--dl)
6. [Filtering & Selecting Specific Sources](#filtering-selecting-specific-sources)
7. [Silent & Script-Friendly Output (`-silent`)](#silent-script-friendly-output--silent)
8. [Piping into DNS Resolvers & Port Scanners](#piping-into-dns-resolvers-port-scanners)
9. [JSONL Output & Data Ingestion (`-oJ`)](#jsonl-output-data-ingestion--oj)
10. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Passive Reconnaissance Model

Traditional DNS brute-forcing requires sending thousands of DNS queries directly to authoritative nameservers, which generates noise in security logs. `subfinder` utilizes **passive discovery**:
- **Zero Direct Traffic**: Queries third-party aggregators (Crt.sh, AlienVault, Shodan, SecurityTrails, Wayback Machine) rather than the target's servers.
- **Speed**: Returns thousands of subdomains in seconds via parallel Go routines.
- **Coverage**: Combines free public sources with authenticated enterprise APIs.

---

## ⚙️ Homebrew Installation & Verification

### 1. Install via Homebrew

```bash
brew install subfinder
```

### 2. Verify Installation

```bash
which subfinder
subfinder -version
```

Output:
```text
/opt/homebrew/bin/subfinder
v2.16.0
```

---

## 🚀 Basic Subdomain Enumeration (`-d`)

Enumerate subdomains for a single root domain:

```bash
subfinder -d github.com
```

### Sample Terminal Output

```text
               __    _____           __           
   _______  __/ /_  / __(_)___  ____/ /__  _____
  / ___/ / / / __ \/ /_/ / __ \/ __  / _ \/ ___/
 (__  ) /_/ / /_/ / __/ / / / / /_/ /  __/ /    
/____/\__,_/_.___/_/ /_/_/ /_/\__,_/\___/_/     

[INF] Enumerating subdomains for github.com
api.github.com
assets-cdn.github.com
gist.github.com
raw.github.com
status.github.com
training.github.com
[INF] Found 68 subdomains for github.com in 3 seconds 120 milliseconds
```

---

## 🔑 Configuring API Keys (`provider-config.yaml`)

While `subfinder` queries dozens of sources for free, adding API keys unlocks significantly higher yields from services like GitHub, Shodan, Censys, VirusTotal, and SecurityTrails.

### Configuration File Location

On macOS:
```bash
~/.config/subfinder/provider-config.yaml
# or: ~/Library/Application Support/subfinder/provider-config.yaml
```

### Example `provider-config.yaml`

```yaml
# Add your free or commercial API tokens
shodan:
  - YOUR_SHODAN_API_KEY
virustotal:
  - YOUR_VIRUSTOTAL_API_KEY
securitytrails:
  - YOUR_SECURITYTRAILS_API_KEY
github:
  - ghp_YOUR_GITHUB_PERSONAL_ACCESS_TOKEN
```

Once configured, `subfinder` automatically incorporates authenticated data providers.

---

## 📂 Bulk Domain Enumeration (`-dL`)

Enumerate subdomains across multiple targets listed in a text file:

```bash
cat << 'EOF' > targets.txt
example.com
hackerone.com
tesla.com
EOF

subfinder -dL targets.txt -o all_subdomains.txt
```

---

## 🎯 Filtering & Selecting Specific Sources

### 1. List Available Data Sources (`-ls`)

```bash
subfinder -ls
```

### 2. Query Specific Sources Only (`-s`)

```bash
# Query only Certificate Transparency logs and Wayback Machine
subfinder -d example.com -s crtsh,waybackarchive
```

### 3. Exclude Slow or Unwanted Sources (`-es`)

```bash
subfinder -d example.com -es alienvault
```

---

## 🤫 Silent & Script-Friendly Output (`-silent`)

Suppress the ASCII banner, timing indicators, and logs to output only clean domain strings:

```bash
subfinder -d example.com -silent
```

Output:
```text
admin.example.com
api.example.com
mail.example.com
vpn.example.com
```

---

## 🔗 Piping into DNS Resolvers & Port Scanners

`subfinder` is designed to be the foundational first step in UNIX pipeline reconnaissance:

```bash
# 1. Passive discovery -> 2. Active DNS resolution -> 3. HTTP probing
subfinder -d example.com -silent | dnsx -silent | httpx -silent -status-code -title
```

---

## 📊 JSONL Output & Data Ingestion (`-oJ`)

Export results in JSON Lines format for processing with `jq`, Python, or Elasticsearch:

```bash
subfinder -d example.com -silent -oJ -o subdomains.json
```

Sample JSON Line:
```json
{"host":"api.example.com","input":"example.com","sources":["crtsh","shodan"]}
```

Extract hosts with `jq`:
```bash
cat subdomains.json | jq -r '.host'
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Recommended Aliases (`~/.zshrc`)

```bash
# Clean, bannerless subdomain enumeration
alias subfind='subfinder -silent -d'

# Thorough discovery with all sources
alias subfind-all='subfinder -all -d'
```

### Command Reference

| Task | Command |
| :--- | :--- |
| **Scan Single Domain** | `subfinder -d example.com` |
| **Save Results to File**| `subfinder -d example.com -o subs.txt` |
| **Silent Output** | `subfinder -d example.com -silent` |
| **Scan Domain List** | `subfinder -dL domains.txt -o out.txt` |
| **Query All Sources** | `subfinder -d example.com -all` |
| **JSONL Output** | `subfinder -d example.com -oJ -o out.json` |
| **List Data Sources** | `subfinder -ls` |

---

## 🗑️ Uninstallation

```bash
brew uninstall subfinder
```
