# CeWL — Custom Wordlist Generator via Web Spiders Guide

A complete, production-grade guide to **CeWL** (Custom Word List generator), the Ruby-based reconnaissance utility that spiders target websites, extracts unique keywords, harvests corporate email addresses, and extracts metadata from documents to build targeted dictionaries for password cracking.

---

## 📑 Table of Contents

- [1. Overview & Targeted Wordlist Philosophy](#1-overview-targeted-wordlist-philosophy)
  - [Why Generic Wordlists (rockyou.txt) Fall Short](#why-generic-wordlists-rockyoutxt-fall-short)
  - [The CeWL Advantage: Harvesting Corporate & Target Jargon](#the-cewl-advantage-harvesting-corporate-target-jargon)
- [2. Installation & Ruby Environment Setup](#2-installation-ruby-environment-setup)
  - [Installing via Homebrew (macOS)](#installing-via-homebrew-macos)
  - [Linux Installation (Kali, Ubuntu, Debian)](#linux-installation-kali-ubuntu-debian)
  - [Installing via Ruby Gem & Bundler](#installing-via-ruby-gem-bundler)
- [3. CLI Flags & Spidering Parameters](#3-cli-flags-spidering-parameters)
  - [Depth, Word Length & Output Control](#depth-word-length-output-control)
  - [Handling Authentication & Custom Headers](#handling-authentication-custom-headers)
  - [Proxy & User-Agent Spoofing](#proxy-user-agent-spoofing)
- [4. Advanced Word Harvesting Techniques](#4-advanced-word-harvesting-techniques)
  - [Harvesting Corporate Email Addresses (-e)](#harvesting-corporate-email-addresses--e)
  - [Extracting Author Metadata from PDFs and Office Docs (-a)](#extracting-author-metadata-from-pdfs-and-office-docs--a)
  - [Generating Word Counts & Frequency Lists (-c)](#generating-word-counts-frequency-lists--c)
- [5. Wordlist Mutation & Handoff to Cracking Engines](#5-wordlist-mutation-handoff-to-cracking-engines)
  - [Lowercasing, Sorting & De-duplication](#lowercasing-sorting-de-duplication)
  - [Mutating with Hashcat and John Rules](#mutating-with-hashcat-and-john-rules)
  - [Combining CeWL with Leetspeak & Year Suffixes](#combining-cewl-with-leetspeak-year-suffixes)
- [6. Defensive Countermeasures & Content Obfuscation](#6-defensive-countermeasures-content-obfuscation)
  - [Web Application Firewall (WAF) Rate Limiting](#web-application-firewall-waf-rate-limiting)
  - [Robots.txt & Honeypot Links](#robotstxt-honeypot-links)
- [7. Quick Reference Cheat Sheet & FAQ](#7-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Targeted Wordlist Philosophy

### Why Generic Wordlists (rockyou.txt) Fall Short

Generic wordlists like `rockyou.txt` contain millions of leaked passwords from historical breaches. However, in enterprise environments with strict password complexity policies (e.g. minimum 12 characters, requiring uppercase, numbers, and symbols), standard wordlists often yield low success rates against custom internal passwords.

Employees routinely base their passwords on:
- Company slogans and mission statements (e.g., `Innovation2024!`).
- Product names, internal project codenames, and trademarks.
- Executive names, board members, or office city locations.

### The CeWL Advantage: Harvesting Corporate & Target Jargon

**CeWL** (Custom Word List generator) crawls a target organization's public website up to a specified depth, extracts all readable words, strips HTML tags, filters by minimum length, and exports a clean dictionary tailored to that specific entity.

```
┌─────────────────────────────────────────────────────────────┐
│                       CeWL Toolchain                        │
│                                                             │
│   Target Website ────────> CeWL Web Spider                  │
│  (https://corp.com)        │                                │
│                            ├── HTML Text Content ──────┐    │
│                            ├── PDF / DOCX Metadata ────┼──┐ │
│                            └── Email Addresses ────────┼──┼─┤
│                                                        │  │ │
│                                                        ▼  ▼ ▼
│                                           Target Wordlist   │
│                                           (corp_words.txt)  │
│                                                        │    │
│                                                        ▼    │
│                                           Hashcat / John    │
│                                           (with Rules)      │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Installation & Ruby Environment Setup

### Installing via Homebrew (macOS)

```bash
# Install CeWL via Homebrew
brew install cewl

# Verify binary installation
cewl --help
```

### Linux Installation (Kali, Ubuntu, Debian)

CeWL is pre-installed on Kali Linux. On Ubuntu and Debian systems:

```bash
# Ubuntu / Debian
sudo apt-get update
sudo apt-get install -y cewl
```

### Installing via Ruby Gem & Bundler

If running from source:

```bash
# Clone the official repository
git clone https://github.com/digininja/CeWL.git
cd CeWL

# Install Ruby dependencies
gem install bundler
bundle install

# Run CeWL directly
./cewl.rb --help
```

---

## 3. CLI Flags & Spidering Parameters

### Depth, Word Length & Output Control

| Flag | Parameter | Description |
| :--- | :--- | :--- |
| `-d` | `DEPTH` | Spider crawl depth (default: `2`). |
| `-m` | `MIN_LEN` | Minimum word length to include (default: `3`). Standard: `-m 6` or `-m 8`. |
| `-x` | `MAX_LEN` | Maximum word length to extract. |
| `-w` | `FILE` | Write discovered words to specified output file. |
| `-c` | None | Include count of each word in the output list. |
| `--lowercase` | None | Convert all extracted words to lowercase. |
| `-o` | None | Allow the spider to visit external, off-site links. |

### Handling Authentication & Custom Headers

```bash
# Basic HTTP Authentication
cewl -d 2 -m 6 -w words.txt --auth_type basic --auth_user admin --auth_pass 'secret' https://corp.local/intranet

# Custom HTTP Headers (e.g. Session Token or API Key)
cewl -d 2 -m 6 -w words.txt --header "Cookie: session_id=xyz123" https://corp.local/
```

### Proxy & User-Agent Spoofing

Evade basic bot blocking by spoofing a standard desktop browser User-Agent:

```bash
# Spoof User-Agent and route through an HTTP proxy (e.g. Burp Suite on 8080)
cewl -d 2 -m 6 \
  -u "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36" \
  --proxy_host 127.0.0.1 --proxy_port 8080 \
  -w target_words.txt \
  https://example.com
```

---

## 4. Advanced Word Harvesting Techniques

### Harvesting Corporate Email Addresses (-e)

CeWL can scrape email addresses across the site and export them separately:

```bash
# Crawl site, output words to words.txt and harvested emails to emails.txt
cewl -d 3 -m 6 \
  -w target_dict.txt \
  -e --email_file corp_emails.txt \
  https://example.com
```

Harvested emails can immediately be formatted into username lists for `kerbrute` or `hydra`.

### Extracting Author Metadata from PDFs and Office Docs (-a)

Organizations often host publicly accessible whitepapers, brochures, and slide decks. These documents contain embedded metadata (document author names, software versions, and operating systems):

```bash
# Download files and extract author metadata
cewl -d 2 -m 6 \
  -a --meta_file metadata_words.txt \
  https://example.com
```

### Generating Word Counts & Frequency Lists (-c)

Sort words by how often they appear on the target website:

```bash
# Output words with occurrence counts
cewl -d 2 -m 6 -c https://example.com > word_counts.txt

# Sort in descending order of frequency
sort -nr -k2 word_counts.txt | head -n 25
```

---

## 5. Wordlist Mutation & Handoff to Cracking Engines

### Lowercasing, Sorting & De-duplication

A clean, unique dictionary provides higher cracking throughput:

```bash
# Spider site with lowercase words only
cewl -d 2 -m 6 --lowercase -w raw_words.txt https://example.com

# Sort and remove duplicate entries
sort -u raw_words.txt -o target_dict_clean.txt
```

### Mutating with Hashcat and John Rules

A word like `Acme` extracted by CeWL becomes a cracked password when run through mutation rules:
- `Acme` $\rightarrow$ `Acme2024!`
- `Acme` $\rightarrow$ `Acm3@123`
- `Acme` $\rightarrow$ `Welcome2Acme!`

```bash
# Hashcat dictionary attack with best64 mutation rules
hashcat -m 1000 -a 0 ntlm_hashes.txt target_dict_clean.txt -r /opt/homebrew/share/hashcat/rules/best64.rule

# John the Ripper with Jumbo rules
john --wordlist=target_dict_clean.txt --rules=Jumbo target_hashes.txt
```

### Combining CeWL with Leetspeak & Year Suffixes

Create quick custom mutations using Bash:

```bash
# Append current and previous year suffixes
while read -r word; do
  echo "${word}2023!"
  echo "${word}2024!"
  echo "${word}2025!"
  echo "${word}#1"
done < target_dict_clean.txt > mutated_dict.txt
```

---

## 6. Defensive Countermeasures & Content Obfuscation

### Web Application Firewall (WAF) Rate Limiting

- Configure WAF rules (Cloudflare, AWS WAF, ModSecurity) to rate-limit or captcha IP addresses requesting dozens of pages within seconds.
- Block default or anomalous User-Agents.

### Robots.txt & Honeypot Links

- Embed hidden honeypot links on web pages (e.g. `<a href="/honeypot-trap" style="display:none;">`).
- Automatically ban IP addresses that follow hidden trap links.

---

## 7. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Task | Command Syntax |
| :--- | :--- |
| **Basic Crawl (Depth 2, Min Len 6)** | `cewl -d 2 -m 6 -w dict.txt https://target.com` |
| **Lowercase Words Only** | `cewl -d 2 -m 6 --lowercase -w dict.txt https://target.com` |
| **Harvest Emails to File** | `cewl -d 2 -m 6 -e --email_file emails.txt https://target.com` |
| **Extract Document Metadata**| `cewl -d 2 -m 6 -a --meta_file meta.txt https://target.com` |
| **Include Word Count** | `cewl -d 2 -m 6 -c -w count.txt https://target.com` |
| **Custom User-Agent** | Append `-u "Mozilla/5.0..."` |
| **Route Through Proxy** | Append `--proxy_host 127.0.0.1 --proxy_port 8080` |
| **HTTP Basic Authentication** | Append `--auth_type basic --auth_user user --auth_pass pass` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **JavaScript-Heavy SPAs**: CeWL uses standard HTTP GET requests and does not execute heavy client-side JavaScript (React/Vue/Angular). If pages render entirely via client-side JavaScript, use tools like `feroxbuster` or headless browsers to pre-render HTML before running CeWL.

> [!TIP]
> **Avoid Infinite Crawl Loops**: Keep crawl depth `-d` set to `2` or `3`. Setting depth to `5+` can cause spiders to traverse infinite calendar pagination or massive product catalogs.

> [!IMPORTANT]
> **Bandwidth Etiquette**: Always ensure you have permission before aggressively spidering production websites to avoid degrading server performance or violating acceptable use policies.
