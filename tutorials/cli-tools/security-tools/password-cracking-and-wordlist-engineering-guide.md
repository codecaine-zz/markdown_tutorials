# Modern Password Cracking & Wordlist Engineering Guide

A complete, production-grade guide to the modern credential recovery ecosystem, tool taxonomy, wordlist mutation methodologies, and pattern engineering workflows—covering **Hashcat**, **John the Ripper**, **CeWL**, **Mentalist**, **PACK** (Password Analysis and Cracking Kit), **Impacket**, **Kerbrute**, **Crowbar**, and **Forensic Memory Extraction**.

---

## 📑 Table of Contents

- [1. The Modern Credential Recovery Landscape](#1-the-modern-credential-recovery-landscape)
  - [The Shift from Brute-Force to Pattern Engineering](#the-shift-from-brute-force-to-pattern-engineering)
  - [Operational Taxonomy & Tool Matrix](#operational-taxonomy-tool-matrix)
- [2. Offline Cracking Engines: GPU vs CPU](#2-offline-cracking-engines-gpu-vs-cpu)
  - [Hashcat: GPU Massive Parallelism](#hashcat-gpu-massive-parallelism)
  - [John the Ripper Jumbo: Formats, Archives & Fallbacks](#john-the-ripper-jumbo-formats-archives-fallbacks)
  - [Enterprise Forensics: Passware Kit & Memory Acquisition](#enterprise-forensics-passware-kit-memory-acquisition)
- [3. Online Network Authentication Crackers](#3-online-network-authentication-crackers)
  - [THC-Hydra: Broad Protocol Coverage](#thc-hydra-broad-protocol-coverage)
  - [Medusa: Connection Stability & Session Throttling](#medusa-connection-stability-session-throttling)
  - [Crowbar: Targeted Protocols (RDP NLA, SSH Keys)](#crowbar-targeted-protocols-rdp-nla-ssh-keys)
- [4. Active Directory & Kerberos Reconnaissance](#4-active-directory-kerberos-reconnaissance)
  - [Kerbrute: Lockout-Free Kerberos Pre-Auth Enumeration](#kerbrute-lockout-free-kerberos-pre-auth-enumeration)
  - [Impacket: AS-REP Roasting & Kerberoasting](#impacket-as-rep-roasting-kerberoasting)
- [5. Wordlist & Pattern Engineering](#5-wordlist-pattern-engineering)
  - [CeWL: Target-Specific Dictionary Harvesting](#cewl-target-specific-dictionary-harvesting)
  - [Mentalist: Visual Psychological Mutation Chains](#mentalist-visual-psychological-mutation-chains)
  - [PACK (Password Analysis & Cracking Kit): Statistical Mask Generation](#pack-password-analysis-cracking-kit-statistical-mask-generation)
- [6. Advanced Attack Methodologies & Workflows](#6-advanced-attack-methodologies-workflows)
  - [Phase 1: Target Intelligence & Wordlist Construction](#phase-1-target-intelligence-wordlist-construction)
  - [Phase 2: Hybrid Attacks (Wordlist + Mask)](#phase-2-hybrid-attacks-wordlist-mask)
  - [Phase 3: Breach Analysis with PACK (statsgen & maskgen)](#phase-3-breach-analysis-with-pack-statsgen-maskgen)
  - [Phase 4: Rule Chaining with Hashcat and John](#phase-4-rule-chaining-with-hashcat-and-john)
- [7. Defensive Architecture & Enterprise Hardening](#7-defensive-architecture-enterprise-hardening)
  - [Password Blacklisting & Entra ID Protection](#password-blacklisting-entra-id-protection)
  - [Phishing-Resistant MFA (FIDO2 / WebAuthn)](#phishing-resistant-mfa-fido2-webauthn)
- [8. Quick Reference Cheat Sheet & FAQ](#8-quick-reference-cheat-sheet-faq)
  - [Attack Vectors & Recommended Tool Selection](#attack-vectors-recommended-tool-selection)
  - [Troubleshooting & Performance Tips](#troubleshooting-performance-tips)

---

## 1. The Modern Credential Recovery Landscape

### The Shift from Brute-Force to Pattern Engineering

Pure mathematical brute-forcing (testing every character combination sequentially: `a`, `b`, `c`... `zzzzzz`) is mathematically infeasible for modern 12+ character passphrases. A 14-character alphanumeric password contains over $62^{14} \approx 1.2 \times 10^{25}$ combinations, requiring centuries even on multi-GPU mining rigs.

Modern credential recovery relies on **Pattern Engineering**:
1. **Human Psychology Constraints**: Humans do not choose random strings; they choose pronounceable base words, brand names, or seasonal anchors (e.g. `Spring`, `Autumn`, `Company`).
2. **Predictable Mutation Patterns**: Enterprise complexity requirements (1 uppercase, 1 symbol, 1 number) channel humans into predictable templates:
   $$\text{[Capitalized Word]} + \text{[Year / Suffix]} + \text{[Special Character]}$$
   *(e.g., `Welcome2024!`, `Company#1`, `Summer2023!`)*
3. **Statistical Breach Analysis**: Extracting mask patterns from billions of leaked passwords in historical breach corpora (e.g., RockYou, HaveIBeenPwned).

### Operational Taxonomy & Tool Matrix

| Tool | Primary Category | What It Does Best | Platform | Tutorial Guide |
| :--- | :--- | :--- | :--- | :--- |
| **Hashcat** | Offline GPU Cracking | Raw brute-force, masks, and rule speed across standard hashes | Cross-platform | [Hashcat Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/hashcat-guide.md) |
| **John the Ripper (Jumbo)**| Offline CPU Cracking | Esoteric file formats, archives (ZIP/RAR/7z), embedded crypto | Cross-platform | [John Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/john-the-ripper-guide.md) |
| **THC-Hydra** | Online Network Auth | Fast, parallelized brute-force against 50+ network protocols | macOS / Linux | [Hydra Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/hydra-network-login-cracker-guide.md) |
| **Medusa** | Online Network Auth | Modular, thread-based cracker with connection throttling | macOS / Linux | [Medusa Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/medusa-network-login-cracker-guide.md) |
| **Kerbrute** | Active Directory / Kerberos | Fast user enumeration & password spraying without lockout risks | Cross-platform (Go) | [Kerbrute Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/kerbrute-active-directory-guide.md) |
| **Impacket** | Active Directory Tools | AS-REP Roasting (`GetNPUsers`), Kerberoasting (`GetUserSPNs`), SAM/NTDS | Cross-platform (Python) | [Impacket Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/impacket-active-directory-credentials-guide.md) |
| **Aircrack-ng / Wifite2** | Wi-Fi Auditing | Handshake interception, PMKID harvesting, WPA2/WPA3 auditing | Linux / macOS | [Aircrack Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/aircrack-ng-wifi-security-guide.md) |
| **Crowbar** | Targeted Protocols | Brute-forcing protocols relying on keys/tokens (RDP NLA, SSH keys)| Linux / Python | [Crowbar Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/crowbar-targeted-brute-force-guide.md) |
| **CeWL** | Wordlist Generation | Custom target-specific dictionary generation via website spiders | Cross-platform (Ruby) | [CeWL Guide](file:///Users/codecaine/markdown_tutorials/tutorials/cli-tools/security-tools/cewl-wordlist-generator-guide.md) |
| **Mentalist** | Rule & Mask Building | Visual rule generator based on human password psychology | Cross-platform (Python) | Described below |
| **PACK** | Mask Analytics | Analyzes breach datasets to generate optimal Hashcat masks | Cross-platform (Python) | Described below |
| **Passware Kit** | Enterprise / Forensics | Memory acquisition, BitLocker, FileVault, APFS bypass | Windows / macOS | Described below |

---

## 2. Offline Cracking Engines: GPU vs CPU

### Hashcat: GPU Massive Parallelism

- **Architecture**: Heavily optimized OpenCL and CUDA kernels executing across thousands of GPU shader cores simultaneously.
- **Strengths**: Unsalted hashes (NTLM, MD5, SHA-256) where modern Nvidia RTX 4090 or Apple Silicon GPUs achieve speeds of **billions to tens of billions of guesses per second**.
- **Ideal For**: Domain NTLM dumps (`secretsdump.ntds`), Kerberoast hashes, WPA2/WPA3 PMKID captures.

### John the Ripper Jumbo: Formats, Archives & Fallbacks

- **Architecture**: Multi-threaded SIMD CPU engine (AVX2, AVX-512, ARM NEON) paired with the `*2john` binary extraction suite.
- **Strengths**: Encrypted ZIP (`zip2john`), RAR (`rar2john`), 7-Zip (`7z2john`), KeePass databases (`keepass2john`), SSH keys (`ssh2john`), PDFs (`pdf2john`), and Unix `/etc/shadow`.
- **Ideal For**: Forensic analysis of encrypted archives, password vault databases, and obscure cryptographic containers.

### Enterprise Forensics: Passware Kit & Memory Acquisition

Commercial forensic suites (Passware Kit Forensic, Elcomsoft Distributed Password Recovery):
- **Live Memory Acquisition**: Extracts live encryption keys directly from volatile RAM dumps (`hiberfil.sys`, `.raw`, or crash dumps) to bypass full-disk encryption (BitLocker, FileVault 2, APFS, VeraCrypt) without brute-forcing passwords.
- **Hardware Acceleration**: Distributes workload across dedicated server clusters with automated format recognition.

---

## 3. Online Network Authentication Crackers

### THC-Hydra: Broad Protocol Coverage

- **Scope**: Supports over 50 protocols including SSH, FTP, HTTP forms, MySQL, PostgreSQL, SMB, and Cisco AAA.
- **Best Use**: Rapid parallel brute-forcing against live network endpoints where no hash file is available.

### Medusa: Connection Stability & Session Throttling

- **Scope**: Modular multi-threaded cracker focusing on thread isolation and connection rate limiting.
- **Best Use**: Auditing embedded network hardware, IoT routers, and enterprise services where aggressive thread pools cause socket drops.

### Crowbar: Targeted Protocols (RDP NLA, SSH Keys)

- **Scope**: Leverages genuine protocol clients (FreeRDP, Paramiko) to audit services with complex negotiation.
- **Best Use**: RDP endpoints with Network Level Authentication (NLA) and SSH servers that only accept private key authentication.

---

## 4. Active Directory & Kerberos Reconnaissance

### Kerbrute: Lockout-Free Kerberos Pre-Auth Enumeration

- **Mechanism**: Interacts with the KDC on port 88 using Kerberos `AS-REQ` packets without pre-authentication data.
- **Advantage**: Validates whether usernames exist in Active Directory **without generating failed logon events (Event 4625)**, completely bypassing account lockout thresholds.

### Impacket: AS-REP Roasting & Kerberoasting

- **`GetNPUsers.py`**: Requests TGTs for accounts with pre-auth disabled (`DONT_REQ_PREAUTH`), returning crackable AS-REP hashes without any domain authentication.
- **`GetUserSPNs.py`**: Requests TGS service tickets for accounts with configured SPNs, returning hashes encrypted under service account passwords.

---

## 5. Wordlist & Pattern Engineering

### CeWL: Target-Specific Dictionary Harvesting

Spiders target corporate websites to extract company-specific terminology, product names, executive bios, and industry jargon into a tailored wordlist:

```bash
cewl -d 2 -m 6 -w target_dict.txt https://target.com
```

### Mentalist: Visual Psychological Mutation Chains

**Mentalist** is a graphical and CLI-based rule generator designed to model human password creation habits. Instead of typing complex Hashcat rules manually, Mentalist provides a visual node-based pipeline:

```
[ Base Word ] ──> [ Case Change: Title ] ──> [ Year: 2020-2025 ] ──> [ Symbol: !?@#$ ]
```

Output: A custom dictionary or Hashcat rule chain (`.rule`) generating:
- `Password2024!`
- `Company2023#`
- `Secret2025$`

```bash
# Running Mentalist via Python
git clone https://github.com/sc0tfree/mentalist.git
cd mentalist
python3 mentalist.py
```

### PACK (Password Analysis & Cracking Kit): Statistical Mask Generation

**PACK** is a collection of Python utilities that ingest large breached password lists (e.g. `rockyou.txt` or internal corporate leaks) and perform statistical frequency analysis:

1. **`statsgen.py`**: Analyzes a password list and computes the distribution of password lengths, character sets (alphanumeric, symbols), and mask structures.
2. **`maskgen.py`**: Takes the output of `statsgen` and outputs an optimized Hashcat `.hcmask` file ordered by statistical probability.
3. **`policygen.py`**: Generates masks conforming to specific enterprise password policies (e.g. min 10 chars with at least 1 digit and 1 symbol).

```bash
# Clone PACK repository
git clone https://github.com/trustedsec/pack.git
cd pack

# 1. Analyze breach list to produce statistics
python3 statsgen.py rockyou.txt -o rockyou.stats

# 2. Generate top 50 most statistically probable Hashcat masks
python3 maskgen.py rockyou.stats --targettime 3600 --optindex -q -o optimal_masks.hcmask
```

---

## 6. Advanced Attack Methodologies & Workflows

### Phase 1: Target Intelligence & Wordlist Construction

Combine targeted web scraping with breach base words:

```bash
# 1. Spider target website for corporate jargon
cewl -d 2 -m 6 -w corp_words.txt https://example.com

# 2. Harvest domain usernames using Kerbrute
kerbrute userenum --dc 192.168.1.10 -d corp.local usernames.txt -o valid_users.txt

# 3. Add employee first and last names to candidate wordlist
awk -F'@' '{print $1}' valid_users.txt >> candidate_words.txt
sort -u candidate_words.txt -o base_dictionary.txt
```

### Phase 2: Hybrid Attacks (Wordlist + Mask)

Rather than pure dictionary search, append common suffixes:

```bash
# Hashcat Mode 6: Dictionary + Mask Append (?d?d?s = 2 digits + 1 symbol)
# e.g., "Company" + "24!" -> "Company24!"
hashcat -m 1000 -a 6 ntds_hashes.txt base_dictionary.txt '?d?d?s'
```

### Phase 3: Breach Analysis with PACK (statsgen & maskgen)

```bash
# Generate high-probability masks from cracked internal hashes
python3 statsgen.py cracked_passwords.txt -o internal.stats
python3 maskgen.py internal.stats --minlength 8 --maxlength 14 -o enterprise.hcmask

# Execute mask attack against uncracked hashes
hashcat -m 1000 -a 3 ntds_hashes.txt enterprise.hcmask
```

### Phase 4: Rule Chaining with Hashcat and John

Mutate base dictionaries using production rule sets:

```bash
# Apply Best64 rule mutations
hashcat -m 1000 -a 0 ntds_hashes.txt base_dictionary.txt -r /opt/homebrew/share/hashcat/rules/best64.rule

# Apply John the Ripper Jumbo rules to archive hashes
john --wordlist=base_dictionary.txt --rules=Jumbo backup.hash
```

---

## 7. Defensive Architecture & Enterprise Hardening

### Password Blacklisting & Entra ID Protection

1. **Active Directory Password Protection**: Deploy Azure AD Password Protection (or open-source solutions like `Lithnet Password Protection`) on domain controllers. Rejects any user password containing:
   - The company name or domain name.
   - Seasonal strings (`Summer2024`, `Winter2025`).
   - Words present in known breach corpuses (HaveIBeenPwned).
2. **Increase Minimum Length to 15+ Characters**: Long passphrases exponentially increase brute-force and mask computation times beyond the limits of GPU clusters.

### Phishing-Resistant MFA (FIDO2 / WebAuthn)

- Deploy hardware security keys (YubiKey) enforcing FIDO2 / WebAuthn.
- Completely renders password discovery useless, as authentication requires physical possession of the cryptographic hardware token.

---

## 8. Quick Reference Cheat Sheet & FAQ

### Attack Vectors & Recommended Tool Selection

| Scenario | Primary Recommended Tool | Command / Syntax |
| :--- | :--- | :--- |
| **Dumped Active Directory Hashes**| **Hashcat** | `hashcat -m 1000 -a 0 ntds.hash wordlist.txt -r best64.rule` |
| **Encrypted ZIP or RAR File** | **John the Ripper** | `zip2john file.zip > f.hash && john --wordlist=dict f.hash` |
| **Corporate Wordlist Generation** | **CeWL** | `cewl -d 2 -m 6 -w dict.txt https://target.com` |
| **Active Directory User Enumeration**| **Kerbrute** | `kerbrute userenum --dc DC_IP -d DOMAIN users.txt` |
| **Offline Kerberos Ticket Extraction**| **Impacket** | `impacket-GetUserSPNs -request -dc-ip DC_IP DOMAIN/user:pass` |
| **Network Service (SSH, FTP, Web)**| **THC-Hydra** / **Medusa** | `hydra -l user -P dict.txt ssh://TARGET_IP` |
| **RDP with NLA Authentication** | **Crowbar** | `python3 crowbar.py -b rdp -s IP/32 -u user -C dict.txt` |
| **Wi-Fi Handshake / PMKID** | **Aircrack-ng** / **Wifite**| `aircrack-ng -w dict.txt -b AP_MAC capture.cap` |
| **Optimal Mask Generation** | **PACK** | `python3 maskgen.py target.stats -o custom.hcmask` |

### Troubleshooting & Performance Tips

> [!NOTE]
> **GPU Workload Tuning**: In Hashcat, add `-w 3` or `-w 4` (Workload Profile High/Nightmare) to maximize GPU core utilization and prevent micro-pauses.

> [!TIP]
> **Avoid Inefficient Brute-Force**: Always begin audits with:
> 1. Target-specific dictionary (CeWL) + `best64.rule`.
> 2. Statistical masks generated from breach corpora (PACK).
> 3. Save pure incremental/mask brute-forcing strictly as a final fallback for short lengths ($\le 8$ characters).
