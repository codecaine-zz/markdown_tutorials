## Table of Contents
1. [Introduction to Nmap](#introduction-to-nmap)
2. [Installation](#installation)
3. [Basic Syntax and Options](#basic-syntax-and-options)
4. [Host Discovery](#host-discovery)
5. [Port Scanning Techniques](#port-scanning-techniques)
6. [Service Detection](#service-detection)
7. [OS Detection](#os-detection)
8. [Advanced Scanning Methods](#advanced-scanning-methods)
9. [Scripting Engine (NSE)](#scripting-engine-nse)
10. [Output and Reporting](#output-and-reporting)
11. [Security Considerations](#security-considerations)
12. [Real-world Examples](#real-world-examples)

## Introduction to Nmap

Nmap (Network Mapper) is a powerful open-source network discovery and security auditing tool. It can be used for various purposes including:
- Network inventory
- Security auditing
- Vulnerability scanning
- Service detection
- Operating system detection

### Key Features:
- Port scanning capabilities
- OS fingerprinting
- Scriptable interaction with the network
- Flexible output formats
- Stealth techniques

## Installation

### Linux (Ubuntu/Debian):
```bash
sudo apt update
sudo apt install nmap
```

### Linux (CentOS/RHEL/Fedora):
```bash
sudo yum install nmap
# or for newer versions:
sudo dnf install nmap
```

### macOS:
```bash
brew install nmap
```

### Windows:
Download from: https://nmap.org/download.html

## Basic Syntax and Options

The basic syntax of Nmap is:

```bash
nmap [Scan Type] [Options] [Target]
```

### Common Command-Line Options:

| Option | Description |
|--------|-------------|
| `-v` | Verbose output |
| `-vv` | More verbose output |
| `-h` | Help menu |
| `--version` | Version information |
| `-oN filename` | Normal output to file |
| `-oX filename` | XML output to file |

## Host Discovery

### Ping Scan
```bash
# Simple ping scan (no port scanning)
nmap -sn 192.168.1.0/24

# Ping scan with verbose output
nmap -sn -v 192.168.1.0/24

# Scan specific hosts
nmap -sn google.com github.com
```

### Host Discovery Techniques
```bash
# TCP ACK ping scan (stealthy)
nmap -PA 192.168.1.0/24

# UDP ping scan (uses DNS query)
nmap -PU 192.168.1.0/24

# ICMP echo ping scan (default)
nmap -PE 192.168.1.0/24

# ARP ping scan (only for local network)
nmap -PR 192.168.1.0/24
```

## Port Scanning Techniques

### TCP Connect Scan
```bash
# Basic TCP connect scan (default)
nmap 192.168.1.1

# Verbose output with TCP connect scan
nmap -v 192.168.1.1

# Specify specific ports
nmap -p 22,80,443 192.168.1.1
```

### SYN Scan (Stealth)
```bash
# TCP SYN scan (stealthy)
nmap -sS 192.168.1.1

# Scan specific ports with SYN scan
nmap -sS -p 22-80,443 192.168.1.1

# Combine with verbose output
nmap -sS -v -p 22,80,443 192.168.1.1
```

### UDP Scan
```bash
# UDP scan (slower but comprehensive)
nmap -sU 192.168.1.1

# Combine with TCP SYN scan
nmap -sSU 192.168.1.1

# Scan specific UDP ports
nmap -sU -p 53,161,137 192.168.1.1
```

### FIN Scan
```bash
# FIN scan (stealthy)
nmap -sF 192.168.1.1

# Null scan (no flags set)
nmap -sN 192.168.1.1

# Xmas scan (FIN, URG, PSH flags set)
nmap -sX 192.168.1.1
```

### ACK Scan
```bash
# ACK scan for firewall testing
nmap -sA 192.168.1.1

# Use with specific port range
nmap -sA -p 22-443 192.168.1.1
```

## Service Detection

### Basic Service Detection
```bash
# Enable service detection (default)
nmap -sV 192.168.1.1

# Combine with verbose output and service version detection
nmap -sV -v 192.168.1.1

# Specify port range for service detection
nmap -sV -p 20-443 192.168.1.1
```

### Detailed Service Detection
```bash
# Aggressive service detection (-A flag)
nmap -A 192.168.1.1

# Include OS detection with service detection
nmap -A 192.168.1.0/24

# Verbose aggressive scan
nmap -A -v 192.168.1.1
```

## OS Detection

### Basic OS Detection
```bash
# Enable OS detection (default)
nmap -O 192.168.1.1

# Combine with service detection and verbose output
nmap -A 192.168.1.1

# Check if host is up before OS detection
nmap -O --host-timeout 30s 192.168.1.1
```

### Advanced OS Detection Options
```bash
# Force OS detection (even with firewall)
nmap -O --osscan-guess 192.168.1.1

# OS detection with specific scan types
nmap -O -sS 192.168.1.1

# Multiple OS detection methods
nmap -O --fuzzy 192.168.1.0/24
```

## Advanced Scanning Methods

### Timing Options
```bash
# Fast scan (-T4)
nmap -T4 192.168.1.0/24

# Paranoid timing (slowest, stealthiest)
nmap -T0 192.168.1.0/24

# Sneaky timing
nmap -T1 192.168.1.0/24

# Normal timing (default)
nmap -T3 192.168.1.0/24

# Aggressive timing
nmap -T5 192.168.1.0/24
```

### Firewall Bypass Techniques
```bash
# Fragment packets to bypass firewalls
nmap -f 192.168.1.1

# Randomize scan order (stealthy)
nmap --randomize-hosts 192.168.1.0/24

# Scan with delays between packets
nmap --scan-delay 5s 192.168.1.1

# Send multiple packets to same port
nmap -g 53 -p 80,443 192.168.1.1
```

### Limiting Scanning Scope
```bash
# Scan only one host at a time
nmap --max-hosts 1 192.168.1.0/24

# Limit number of ports scanned per host
nmap --max-parallelism 5 -p 22,80,443 192.168.1.0/24

# Skip the port scan entirely (host discovery only)
nmap -sn --port-ratio 0 192.168.1.0/24
```

## Scripting Engine (NSE)

### Basic NSE Usage
```bash
# List all available scripts
ls /usr/share/nmap/scripts/

# Run a specific script on target
nmap -sV --script http-enum 192.168.1.1

# Run multiple scripts
nmap -sV --script "http-*" 192.168.1.1

# Script with custom arguments
nmap --script http-headers --script-args http.useragent="MyAgent" 192.168.1.1
```

### Common NSE Categories
```bash
# DNS scripts
nmap -sV --script dns-* 192.168.1.0/24

# SMB scripts
nmap -sV --script smb-* 192.168.1.0/24

# FTP scripts
nmap -sV --script ftp-* 192.168.1.0/24

# HTTP scripts
nmap -sV --script http-* 192.168.1.0/24

# Vulnerability detection
nmap -sV --script vuln 192.168.1.0/24
```

### Script Categories and Examples
```bash
# Security-related scripts (vulscan)
nmap -sV --script vulners 192.168.1.0/24

# SNMP scripts
nmap -sU -p 161 --script snmp-* 192.168.1.0/24

# NTP scripts
nmap -sU -p 123 --script ntp-* 192.168.1.0/24

# MySQL scripts
nmap -sV --script mysql-* 192.168.1.0/24

# PostgreSQL scripts
nmap -sV --script pgsql-* 192.168.1.0/24
```

## Practical Examples and Workflows

### Comprehensive Network Scan
```bash
# Full network reconnaissance scan
nmap -A -T4 -v 192.168.1.0/24

# Save output to file with all information
nmap -A -T4 --stats-every 30s -oN full_scan.txt 192.168.1.0/24

# Scan with detailed output including scripts
nmap -A -T4 --script-args http.useragent="Mozilla/5.0" 192.168.1.0/24

# Scan specific hosts and ports
nmap -A -p 22,80,443,3389 192.168.1.10-50
```

### Stealth Scanning
```bash
# Stealthy scan with random delays
nmap --randomize-hosts -T1 --scan-delay 3s -A 192.168.1.0/24

# Fragmented packets to avoid IDS detection
nmap -f --mtu 32 -A 192.168.1.0/24

# Scan with limited parallelism and delays
nmap --max-parallelism 3 --scan-delay 5s -T2 -A 192.168.1.0/24
```

### Targeted Vulnerability Assessment
```bash
# Quick vulnerability scan on specific ports
nmap -sV --script vulners -p 22,80,443,3389 192.168.1.0/24

# Web application security assessment
nmap -sV --script "http-*" --script-args http.useragent="Mozilla/5.0" 192.168.1.0/24

# SMB vulnerability check
nmap -sU -p 137,138 --script smb-vuln-* 192.168.1.0/24

# FTP security assessment
nmap -sV --script ftp-anon,ftp-libopie 192.168.1.0/24
```

### Automation Script Example
```bash
#!/bin/bash
# automated_scan.sh

TARGET=$1
OUTPUT="scan_results_$(date +%Y%m%d_%H%M%S)"

echo "Starting comprehensive scan of $TARGET"
nmap -A -T4 --stats-every 30s \
     -oN ${OUTPUT}.txt \
     -oX ${OUTPUT}.xml \
     $TARGET

echo "Scan complete. Results saved to ${OUTPUT}.{txt,xml}"
```

## Command Summary and Best Practices

### Common Scan Patterns
```bash
# Quick host discovery (ping sweep)
nmap -sn 192.168.1.0/24

# Service version detection
nmap -sV 192.168.1.0/24

# Aggressive scan with all features
nmap -A 192.168.1.0/24

# UDP scan for common ports
nmap -sU -p 53,123,161 192.168.1.0/24

# Stealth TCP SYN scan
nmap -sS -T2 192.168.1.0/24
```

### Best Practices for Nmap Usage
- Use appropriate timing templates (-T0-5) based on network conditions
- Combine multiple scanning techniques for comprehensive results
- Save output to files using appropriate formats (Nmap XML, normal text)
- Be respectful of target networks and follow legal guidelines
- Regularly update nmap for the latest security checks and features

This guide provides a comprehensive overview of Nmap usage with practical examples. Start with basic scans and gradually progress to more advanced techniques based on your specific needs and network conditions.

```bash
# Test your setup with a quick scan
nmap -sn localhost
```

### Tips for Effective Usage:
1. **Start Simple**: Begin with basic port scanning before moving to complex attacks.
2. **Respect Networks**: Always obtain permission before scanning any target.
3. **Save Results**: Use proper output formats (XML, normal) for documentation and analysis.
4. **Use Scripts Wisely**: NSE scripts can be powerful but may slow down scans significantly.
5. **Monitor Performance**: Watch for network impact during large-scale scans.

The above examples demonstrate various use cases from basic reconnaissance to comprehensive security assessments using nmap's extensive feature set.
```

