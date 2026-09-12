# John the Ripper (Jumbo) — Complete Password Cracking & Forensics Guide

A complete, production-grade guide to **John the Ripper (Jumbo Edition)** (`john`), the legendary CPU/GPU offline password auditing and forensic credential recovery framework, specializing in encrypted archives, multi-format hashes, embedded cryptosystems, and intelligent wordlist mutation rules.

---

## 📑 Table of Contents

- [1. Overview & John vs Hashcat Comparison](#1-overview-john-vs-hashcat-comparison)
  - [Core Philosophy: Why Jumbo Excels at Archives & Obscure Formats](#core-philosophy-why-jumbo-excels-at-archives-obscure-formats)
  - [Architecture: Single Core, OpenMP Multi-Threading & GPU](#architecture-single-core-openmp-multi-threading-gpu)
- [2. Installation & Environment Setup](#2-installation-environment-setup)
  - [Installing John Jumbo via Homebrew (macOS)](#installing-john-jumbo-via-homebrew-macos)
  - [Linux Package Installation](#linux-package-installation)
  - [Verifying John & Checking OpenMP Acceleration](#verifying-john-checking-openmp-acceleration)
- [3. Primary Cracking Modes](#3-primary-cracking-modes)
  - [Single Crack Mode (Username Mangling)](#single-crack-mode-username-mangling)
  - [Wordlist Dictionary Mode](#wordlist-dictionary-mode)
  - [Incremental (Markov Brute-Force) Mode](#incremental-markov-brute-force-mode)
  - [External & Custom Mask Modes](#external-custom-mask-modes)
- [4. The *2john Extraction Toolchain](#4-the-2john-extraction-toolchain)
  - [Archive Extraction (zip2john, rar2john, 7z2john)](#archive-extraction-zip2john-rar2john-7z2john)
  - [SSH, PGP & Key Extraction (ssh2john, gpg2john)](#ssh-pgp-key-extraction-ssh2john-gpg2john)
  - [Document & Password Manager Extraction (pdf2john, keepass2john)](#document-password-manager-extraction-pdf2john-keepass2john)
- [5. Wordlist Mutation Rules & Rule Building](#5-wordlist-mutation-rules-rule-building)
  - [Standard Rule Sets: Jumbo, KoreLogic, Best64](#standard-rule-sets-jumbo-korelogic-best64)
  - [Writing Custom Rules in john.conf](#writing-custom-rules-in-johnconf)
- [6. Session Management, Resuming & Potfile Handling](#6-session-management-resuming-potfile-handling)
  - [Named Sessions & Background Checkpoints](#named-sessions-background-checkpoints)
  - [Resuming Interrupted Cracking Tasks](#resuming-interrupted-cracking-tasks)
  - [Inspecting the Potfile & Showing Results](#inspecting-the-potfile-showing-results)
- [7. Benchmark Testing & Performance Tuning](#7-benchmark-testing-performance-tuning)
  - [Running Format Benchmarks (john --test)](#running-format-benchmarks-john---test)
  - [Tuning Forking & OpenMP Thread Concurrency](#tuning-forking-openmp-thread-concurrency)
- [8. Practical Real-World Workflows](#8-practical-real-world-workflows)
  - [Workflow 1: Recovering an Encrypted Zip/7z Archive](#workflow-1-recovering-an-encrypted-zip7z-archive)
  - [Workflow 2: Auditing /etc/shadow with unshadow](#workflow-2-auditing-etcshadow-with-unshadow)
  - [Workflow 3: Cracking an Encrypted SSH Private Key](#workflow-3-cracking-an-encrypted-ssh-private-key)
- [9. Quick Reference Cheat Sheet & FAQ](#9-quick-reference-cheat-sheet-faq)
  - [CLI Commands Summary Table](#cli-commands-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & John vs Hashcat Comparison

### Core Philosophy: Why Jumbo Excels at Archives & Obscure Formats

While **Hashcat** is designed for sheer brute-force compute speed across GPUs for uniform, unsalted or fast hashes (like MD5, NTLM, and SHA-256), **John the Ripper (Jumbo Edition)** is the Swiss Army knife of cryptographic format support:
1. **Unrivaled Format Diversity**: John supports hundreds of obscure, complex, and nested container formats that Hashcat does not parse natively.
2. **The `*2john` Toolchain**: A vast ecosystem of extraction utilities that parse binary headers from `.zip`, `.rar`, `.7z`, `.pdf`, `.kdbx`, `.gpg`, SSH keys, macOS keychain dumps, and Microsoft Office documents into crackable hash strings.
3. **Complex Mutation Rule Engine**: John includes an expressive pattern engine capable of multi-pass syllable mangling, leetspeak substitutions, and contextual dictionary permutations.

```
┌──────────────────────────────────────────────────────────────┐
│                    The Modern Cracking Split                 │
├──────────────────────────────┬───────────────────────────────┤
│    Hashcat (GPU Sovereign)   │   John the Ripper (Jumbo)     │
├──────────────────────────────┼───────────────────────────────┤
│ • Billions of hashes/sec     │ • Tens of thousands/sec       │
│ • Raw NTLM, MD5, SHA1/256    │ • Encrypted Archives (ZIP/RAR)│
│ • OpenCL & CUDA optimized    │ • CPU Multi-Thread & SIMD     │
│ • Fixed mask lengths         │ • Password managers (KeePass) │
│ • Flat hash lists            │ • Complex Unix /etc/shadow    │
└──────────────────────────────┴───────────────────────────────┘
```

### Architecture: Single Core, OpenMP Multi-Threading & GPU

John Jumbo automatically detects available CPU features (AVX2, AVX-512, ARM NEON on Apple Silicon) and uses OpenMP to scale across all logical cores. When GPU support is configured, John leverages OpenCL kernels for formats that benefit from massive parallelization.

---

## 2. Installation & Environment Setup

### Installing John Jumbo via Homebrew (macOS)

Homebrew provides the pre-compiled, highly optimized Jumbo release:

```bash
# Install John Jumbo via Homebrew
brew install john-jumbo

# Confirm binary path and version
which john
john --version
```

The homebrew package installs the primary `john` binary along with the `*2john` toolchain symlinked in `/opt/homebrew/share/john/` or `/opt/homebrew/bin/`.

### Linux Package Installation

On Debian, Ubuntu, and Kali Linux:

```bash
# Ubuntu / Debian
sudo apt-get update
sudo apt-get install -y john

# On Kali Linux (Jumbo is installed by default)
sudo apt-get install -y john-data
```

### Verifying John & Checking OpenMP Acceleration

Run the help command to verify active SIMD vectorization and OpenMP support:

```bash
john
```

Output should display SIMD architecture (such as `AVX2` or `ASIMD` on Apple Silicon) and OpenMP thread count:

```text
John the Ripper 1.9.0-jumbo-1 [darwin-arm64-omp]
Loaded 1 OpenMP thread count
```

---

## 3. Primary Cracking Modes

### Single Crack Mode (Username Mangling)

Single crack mode (`--single`) takes information embedded in the hash target itself (such as account usernames, full names, and home directories in Unix `/etc/passwd`) and applies rapid mangling rules. It is exceptionally fast and catches trivial passwords immediately:

```bash
john --single shadow_hashes.txt
```

### Wordlist Dictionary Mode

Standard dictionary attack feeding candidate words from a text wordlist:

```bash
john --wordlist=/opt/homebrew/share/john/password.lst target_hashes.txt

# Using custom rockyou.txt
john --wordlist=/usr/share/wordlists/rockyou.txt target_hashes.txt
```

### Incremental (Markov Brute-Force) Mode

Incremental mode generates candidate strings based on character transition probabilities:

```bash
# Full incremental mode across all printable ASCII characters
john --incremental target_hashes.txt

# Incremental digits only (useful for PINs)
john --incremental=Digits target_hashes.txt

# Incremental lowercase alphanumeric characters
john --incremental=Alpha target_hashes.txt
```

### External & Custom Mask Modes

Similar to Hashcat mask attacks, John allows explicit character masks:

```bash
# Crack a 4-digit PIN (?d = digit)
john --mask='?d?d?d?d' target_hashes.txt

# Capital word followed by 4 digits and 1 symbol (?u = upper, ?l = lower, ?s = symbol)
john --mask='?u?l?l?l?d?d?d?d?s' target_hashes.txt
```

---

## 4. The *2john Extraction Toolchain

The core superpower of John Jumbo is its collection of utilities that extract hash signatures from binary files.

### Archive Extraction (zip2john, rar2john, 7z2john)

Extract password hashes from encrypted archives:

```bash
# ZIP archives (legacy ZipCrypto and WinZip AES)
zip2john secret_backup.zip > zip.hash

# RAR archives (RAR3 and RAR5 formats)
rar2john confidential.rar > rar.hash

# 7-Zip archives
7z2john backup.7z > 7z.hash
```

Once extracted, feed the resulting `.hash` file directly to John:

```bash
john --wordlist=rockyou.txt zip.hash
```

### SSH, PGP & Key Extraction (ssh2john, gpg2john)

Extract passphrases protecting private keys:

```bash
# Encrypted OpenSSH or RSA private key
ssh2john id_rsa > ssh_key.hash
john --wordlist=rockyou.txt ssh_key.hash

# GPG / PGP secret keyring
gpg2john secret.key > gpg.hash
john --wordlist=rockyou.txt gpg.hash
```

### Document & Password Manager Extraction (pdf2john, keepass2john)

```bash
# Password-protected PDF document
pdf2john financial_statement.pdf > pdf.hash
john --wordlist=rockyou.txt pdf.hash

# KeePass database (KDBX 3 and KDBX 4)
keepass2john PersonalVault.kdbx > keepass.hash
john --wordlist=rockyou.txt keepass.hash
```

---

## 5. Wordlist Mutation Rules & Rule Building

### Standard Rule Sets: Jumbo, KoreLogic, Best64

Applying rules transforms words in your dictionary (e.g. `password` becomes `P@ssw0rd!2024`):

```bash
# Use standard Jumbo rules
john --wordlist=rockyou.txt --rules=Jumbo target.hash

# Use high-probability Best64 rules
john --wordlist=rockyou.txt --rules=Best64 target.hash

# Use KoreLogic rules for enterprise password patterns
john --wordlist=rockyou.txt --rules=KoreLogic target.hash
```

### Writing Custom Rules in john.conf

You can define custom rule sections in `john.conf` (located at `/opt/homebrew/etc/john/john.conf` or `/etc/john/john.conf`):

```ini
[List.Rules:CompanySpecial]
# Capitalize first letter and append current year
c $2 $0 $2 $4
# Append exclamation mark and current year
$! $2 $0 $2 $4
# Leetspeak substitution: e -> 3, a -> @, o -> 0
so0 sa@ se3
```

Execute your custom rule:

```bash
john --wordlist=company_keywords.txt --rules=CompanySpecial target.hash
```

---

## 6. Session Management, Resuming & Potfile Handling

### Named Sessions & Background Checkpoints

When running long-duration audits, assign session names to prevent state corruption:

```bash
# Start a named cracking session
john --session=archive_audit --wordlist=rockyou.txt --rules=Jumbo zip.hash
```

### Resuming Interrupted Cracking Tasks

If a cracking run was paused with `Ctrl+C` or interrupted by a system restart:

```bash
# Resume the named session from the last checkpoint
john --resume=archive_audit
```

To view session status while it is actively running, press **Spacebar** in the terminal running John.

### Inspecting the Potfile & Showing Results

When John successfully recovers a plaintext password, it records the result into `~/.john/john.pot`. Display recovered credentials:

```bash
# Display cracked credentials for target hash file
john --show zip.hash

# Display cracked credentials including format details
john --show --format=PKZIP zip.hash
```

---

## 7. Benchmark Testing & Performance Tuning

### Running Format Benchmarks (john --test)

Benchmark all supported formats on your hardware:

```bash
# Quick benchmark across common formats
john --test

# Benchmark a specific hash format (e.g. SHA-512 crypt or ZIP)
john --test --format=sha512crypt
john --test --format=PKZIP
```

### Tuning Forking & OpenMP Thread Concurrency

By default, John uses OpenMP threads. On multi-socket systems or high-core processors, process forking provides higher throughput:

```bash
# Fork into 8 concurrent child processes
john --fork=8 --wordlist=rockyou.txt target.hash
```

---

## 8. Practical Real-World Workflows

### Workflow 1: Recovering an Encrypted Zip/7z Archive

```bash
# 1. Extract hash from encrypted archive
zip2john client_backup.zip > client.hash

# 2. Inspect extracted hash format
head -n 2 client.hash

# 3. Launch dictionary attack with wordlist rules
john --session=zip_task --wordlist=rockyou.txt --rules=Jumbo client.hash

# 4. Show recovered password
john --show client.hash
```

### Workflow 2: Auditing /etc/shadow with unshadow

When auditing Linux servers, `/etc/passwd` contains usernames and `/etc/shadow` contains hashes. Combine them using `unshadow`:

```bash
# 1. Combine passwd and shadow files
unshadow /etc/passwd /etc/shadow > combined.shadow

# 2. Run Single mode first for quick wins
john --single combined.shadow

# 3. Follow with wordlist and Jumbo rules
john --wordlist=rockyou.txt --rules=Jumbo combined.shadow

# 4. View cracked accounts
john --show combined.shadow
```

### Workflow 3: Cracking an Encrypted SSH Private Key

```bash
# 1. Extract hash from encrypted OpenSSH key
ssh2john ~/.ssh/id_rsa > id_rsa.hash

# 2. Run dictionary attack
john --wordlist=rockyou.txt id_rsa.hash

# 3. View the decrypted passphrase
john --show id_rsa.hash
```

---

## 9. Quick Reference Cheat Sheet & FAQ

### CLI Commands Summary Table

| Action | Command Syntax |
| :--- | :--- |
| **Basic Wordlist Attack** | `john --wordlist=dict.txt hashes.txt` |
| **Apply Wordlist Rules** | `john --wordlist=dict.txt --rules=Jumbo hashes.txt` |
| **Single Crack Mode** | `john --single hashes.txt` |
| **Incremental Brute-Force** | `john --incremental hashes.txt` |
| **Custom Mask Attack** | `john --mask='?u?l?l?l?d?d?d?d' hashes.txt` |
| **Start Named Session** | `john --session=my_session --wordlist=dict.txt hashes.txt` |
| **Resume Paused Session** | `john --resume=my_session` |
| **Show Cracked Passwords**| `john --show hashes.txt` |
| **Benchmark Format** | `john --test --format=format_name` |
| **Extract Zip Hash** | `zip2john file.zip > file.hash` |
| **Extract SSH Key Hash** | `ssh2john id_rsa > key.hash` |
| **Extract PDF Hash** | `pdf2john doc.pdf > doc.hash` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **No hashes loaded**: If John prints `No password hashes loaded (see FAQ)`, verify that the file actually contains John-compatible hash lines, or explicitly declare the format using `--format=format_name`.

> [!TIP]
> **Status Check During Run**: You do not need to cancel a job to check progress. Press the **Spacebar** or **Enter** key in your terminal to see current candidate word, elapsed time, and cracking speed.

> [!IMPORTANT]
> **Potfile Location**: John stores all cracked credentials in `~/.john/john.pot`. If you need to re-test an already cracked hash for demonstration or benchmarking, clear the entry from `john.pot` or pass `--pot=temporary.pot`.
