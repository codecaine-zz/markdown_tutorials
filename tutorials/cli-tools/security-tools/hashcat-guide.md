# Hashcat Password Cracking Guide

> **⚡️ Cracking Passwords with Hashcat on Apple-Silicon Macs – zsh-only, macOS 14+ with latest word-lists**

> This guide is copy‑and‑paste ready for the **default zsh shell** that ships with macOS 14 (and all recent macOS releases).  
> It walks you through installing Homebrew & hashcat, getting the biggest public password dictionaries, creating a few sample hashes, and running a variety of attacks – all on Apple‑Silicon (M1/M2/M3 …).  

---

## 💡 Practical Examples

## 📚 Table of Contents  

1. [Install Homebrew & hashcat (zsh)](#1)  
2. [The biggest word‑lists you can actually download](#2)  
3. [Grab a dictionary (quick‑start list)](#3)  
4. [Create sample hash files](#4)  
5. [Run basic cracking commands](#5-run-basic-cracking-commands)  
6. [More powerful attacks](#6-more-powerful-attacks)  
7. [One‑click demo script (zsh)](#7-oneclick-demo-script-zsh)  
8. [Tips, common errors & Gotchas](#8)  

---  

<a id="1"></a>
## 1️⃣ Install the prerequisites (zsh)

### 1️⃣ Add Homebrew to your **zsh** PATH (only once)

```zsh
# Put this in ~/.zshrc (or ~/.zprofile) and reload the file
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

> Homebrew’s default prefix on Apple‑Silicon is **/opt/homebrew** 【1†L7-L9】.

### 1️⃣ Install Homebrew (if you don’t have it)

```zsh
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

*The installer itself runs under Bash, but you only execute it once.*

### 1️⃣ Install hashcat

```zsh
brew install hashcat
```

### 1️⃣ Verify GPU detection

```zsh
hashcat -I    # shows OpenCL devices; you should see “Apple M1‑GPU”, “M2‑GPU”, etc.
```

If you see a line like *“Device #1: Apple M1 GPU”* you’re ready to go.  

---

<a id="2"></a>
## 2️⃣ The biggest public word‑lists (download URLs)

| # | Word‑list | Approx. Entries / Size | Format | Direct download link | Notes |
|---|-----------|-----------------------|--------|----------------------|-------|
| 1 | **RockYou2021** (compiled from 80 + source lists) | **~82 B** unique entries (≈ 100 GB compressed) | plain‑text `.txt` (gzip/MAGNET) | <https://github.com/ohmybahgosh/RockYou2021.txt#readme> – see “Download Links at Bottom of this page” for magnet/torrent 【9†L19-L28】【9†L106-L115】 | Largest publicly‑available list; ideal for *massive* dictionary attacks (needs a lot of disk space). |
| 2 | **RockYou2024** (10 B common passwords) | **10 B** entries (~45 GB gz) | plain‑text `.txt` | <https://www.kaggle.com/datasets/bwandowando/common-password-list-rockyou2024-txt> (Kaggle) | Slightly newer compilation; good middle ground between size & speed. |
| 3 | **CrackStation** (human‑focused) | **1 .5 B** words (≈ 15 GB) | `crackstation.txt.gz` (gzip) | <https://crackstation.net/crackstation-wordlist-password-cracking-dictionary.htm> – “Download” section 【10†L3-L5】【10†L33-L40】 | Very comprehensive (books, wiki, leaked DBs). |
| 4 | **CrackStation‑human‑only** | **64 M** words (≈ 600 MB) | `crackstation-human-only.txt.gz` | Same page as above (human‑only subset) 【10†L47-L53】 | Faster to load; works well for most “real‑world” passwords. |
| 5 | **rockyou.txt** (original 14 M) | **14.34 M** (≈ 133 MB) | `rockyou.txt.gz` (gzip) | <https://weakpass.com/wordlists/rockyou.txt> – “Download” link 【11†L42-L45】 | Classic baseline; small enough for quick demos. |
| 6 | **SecLists – Passwords** | ~**40 M** total across many sub‑lists | many individual `.txt` files | <https://github.com/danielmiessler/SecLists/tree/master/Passwords> | Great for targeted attacks (e.g., “Leaked‑Databases/rockyou.txt”). |

> **Tip:** You don’t have to download *all* of them. Pick a list that matches the RAM/SSD you have available. Even the 15 GB CrackStation file fits comfortably on a modern SSD, while the 100 GB RockYou2021 is best kept on an external drive if you plan a one‑off massive audit.

---

<a id="3"></a>
## 3️⃣ Quick‑start dictionary (the one used in the demo)

If you just want a ready‑to‑use list for the tutorial, the classic **rockyou.txt** (14 M entries) works fine:

```zsh
mkdir -p ~/hashcat-demo/dicts
cd ~/hashcat-demo/dicts

curl -L https://weakpass.com/wordlists/rockyou.txt.gz -o rockyou.txt.gz
gunzip -k rockyou.txt.gz          # keeps the original .gz in case you need it later
```

*(You can replace `rockyou.txt` with any of the bigger lists from the table above – just adjust the path in later commands.)*

---

<a id="4"></a>
## 4️⃣ Create some sample hash files

```zsh
mkdir -p ~/hashcat-demo/hashes
cd ~/hashcat-demo/hashes

# 1️⃣ MD5 hash of “Secret123”
echo -n "Secret123" | md5 -q > example0.hash   # “-q” prints only the hash

# 2️⃣ NTLM hash of “Secret123”
python3 -c '
import hashlib, binascii
pw = "Secret123".encode("utf-16le")
print(binascii.hexlify(hashlib.new("md4", pw).digest()).decode())
' > example1000.hash

# 3️⃣ Sample WPA/WPA2 capture (hccapx)
curl -L https://hashcat.net/misc/example_files/example.hccapx -o example2500.hccapx
```

| File | Hash mode (`-m`) | Real password |
|------|------------------|---------------|
| `example0.hash` | `0` (MD5) | `Secret123` |
| `example1000.hash` | `1000` (NTLM) | `Secret123` |
| `example2500.hccapx` | `2500` (WPA‑PSK) | `hashcat!` |

---

<a id="5"></a>
## 5️⃣ Run basic cracking commands

All commands assume the demo directories created above. Feel free to replace the paths with your own locations.

### 5️⃣ MD5 (mode 0)

```zsh
hashcat \
  -m 0 \
  -a 0 \
  -w 3 \
  --status \
  ~/hashcat-demo/hashes/example0.hash \
  ~/hashcat-demo/dicts/rockyou.txt
```

| Flag | Meaning |
|------|---------|
| `-m 0` | MD5 |
| `-a 0` | Dictionary attack |
| `-w 3` | High workload (good balance on laptops) |
| `--status` | Live progress line |

> Expected output ends with `3c4b0d832ce6150c26a4548485144b67:Secret123`.

### 5️⃣ NTLM (mode 1000)

```zsh
hashcat -m 1000 -a 0 \
  ~/hashcat-demo/hashes/example1000.hash \
  ~/hashcat-demo/dicts/rockyou.txt
```

> You’ll see `2112a09117c3760303867c268a04be35:Secret123`.

### 5️⃣ WPA‑PSK (mode 2500)

```zsh
hashcat -m 2500 -a 0 \
  ~/hashcat-demo/hashes/example2500.hccapx \
  ~/hashcat-demo/dicts/rockyou.txt
```

> Output will contain a line starting with `WPA*01*…` and the password `hashcat!`.

---

<a id="6"></a>
## 6️⃣ More powerful attacks

| Attack | Example command | What it does |
|--------|----------------|--------------|
| **Hybrid wordlist + mask** (`-a 6`) | ```zsh<br>hashcat -m 0 -a 6 ~/hashcat-demo/hashes/example0.hash ~/hashcat-demo/dicts/rockyou.txt ?d?d?d?d<br>``` | Takes every word from the list and appends four digits (good for `password1234`). |
| **Rule‑based attack** | ```zsh<br>RULES_DIR=$(brew --prefix hashcat)/share/hashcat/rules<br>hashcat -m 0 -a 0 -r "${RULES_DIR}/best64.rule" \\\n  ~/hashcat-demo/hashes/example0.hash \\\n  ~/hashcat-demo/dicts/rockyou.txt<br>``` | Applies the built‑in `best64.rule` (leet‑speak, capitalization, etc.). |
| **Benchmark** | ```zsh<br>hashcat -b -m 0   # MD5 speed test on your GPU/CPU<br>``` | Shows hashes‑per‑second; helps you size larger jobs. |
| **Using a massive list** (e.g., RockYou2021) | ```zsh<br>hashcat -m 0 -a 0 ~/hashcat-demo/hashes/example0.hash /path/to/rockyou2021.txt<br>``` | Same command, just point to the huge list you downloaded in § 2. |

### 6.1 Real-World Common Hash Modes (Copy-and-Paste Cheat Sheet)

Extract hashes first using `john` utilities (e.g. `pdf2john.py`, `zip2john`, `ssh2john.py`), then crack with Hashcat:

```zsh
# 1. PDF File Passwords (-m 10500 / 10600)
# Extract hash: python3 pdf2john.py secret.pdf > pdf.hash
hashcat -m 10500 -a 0 pdf.hash ~/hashcat-demo/dicts/rockyou.txt

# 2. ZIP Archive (-m 13600 WinZip / PKZIP)
# Extract hash: zip2john file.zip > zip.hash (remove filename prefix before running)
hashcat -m 13600 -a 0 zip.hash ~/hashcat-demo/dicts/rockyou.txt

# 3. 7-Zip Archive (-m 11600)
# Extract hash: 7z2john.pl archive.7z > 7z.hash
hashcat -m 11600 -a 0 7z.hash ~/hashcat-demo/dicts/rockyou.txt

# 4. Microsoft Office (2013+ .docx / .xlsx) (-m 9600)
# Extract hash: office2john.py document.docx > docx.hash
hashcat -m 9600 -a 0 docx.hash ~/hashcat-demo/dicts/rockyou.txt

# 5. SSH Private Key Passphrase (-m 22900)
# Extract hash: ssh2john.py ~/.ssh/id_rsa > ssh.hash
hashcat -m 22900 -a 0 ssh.hash ~/hashcat-demo/dicts/rockyou.txt

# 6. Active Directory Kerberoast (krb5tgs -m 13100)
hashcat -m 13100 -a 0 krb5tgs.hash ~/hashcat-demo/dicts/rockyou.txt -r $(brew --prefix hashcat)/share/hashcat/rules/oneruletorule-them-all.rule

# 7. Targeted Mask Attack (-a 3) for "Company2024!" format
# ?u = Uppercase, ?l = Lowercase, ?d = Digit, ?s = Special character
hashcat -m 1000 -a 3 ntlm.hash "?u?l?l?l?l?l?l?d?d?d?d?s"
```

---

<a id="7"></a>
## 7️⃣ One‑click demo script (zsh)

Save the script below as **`run-demo.zsh`**, make it executable, and run it. It does everything from directory creation to a full MD5 crack, using the **zsh** interpreter and automatically locating hashcat’s rule directory.

```zsh
#!/usr/bin/env zsh
# -------------------------------------------------
# Hashcat demo – Apple Silicon, zsh version
# -------------------------------------------------
set -euo pipefail

# ---------- Config ----------
DEMO_DIR="${HOME}/hashcat-demo"
DICT_DIR="${DEMO_DIR}/dicts"
HASH_DIR="${DEMO_DIR}/hashes"
DICT_URL="https://weakpass.com/wordlists/rockyou.txt.gz"
WPA_URL="https://hashcat.net/misc/example_files/example.hccapx"
RULES_DIR="$(brew --prefix hashcat)/share/hashcat/rules"

# ---------- Helper ----------
msg() { echo "\n=== $* ===\n" }

# ---------- 1. Directories ----------
msg "Creating demo directories"
mkdir -p "${DICT_DIR}" "${HASH_DIR}"

# ---------- 2. Download word‑list ----------
if [[ ! -f "${DICT_DIR}/rockyou.txt" ]]; then
  msg "Downloading rockyou.txt (≈ 133 MB)"
  curl -L "${DICT_URL}" -o "${DICT_DIR}/rockyou.txt.gz"
  gunzip -k "${DICT_DIR}/rockyou.txt.gz"
else
  msg "rockyou.txt already present – skipping download"
fi

# ---------- 3. Create sample hashes ----------
msg "Generating sample hash files"
# MD5
echo -n "Secret123" | md5 -q > "${HASH_DIR}/example0.hash"
# NTLM
python3 -c '
import hashlib, binascii
pw = "Secret123".encode("utf-16le")
print(binascii.hexlify(hashlib.new("md4", pw).digest()).decode())
' > "${HASH_DIR}/example1000.hash"
# WPA capture
curl -L "${WPA_URL}" -o "${HASH_DIR}/example2500.hccapx"

# ---------- 4. Run a dictionary crack ----------
msg "Running MD5 crack (dictionary attack)"
hashcat -m 0 -a 0 -w 3 \
  --potfile-path "${DEMO_DIR}/cracked.pot" \
  "${HASH_DIR}/example0.hash" \
  "${DICT_DIR}/rockyou.txt"

msg "Demo finished – cracked passwords stored in ${DEMO_DIR}/cracked.pot"
```

Run it:

```zsh
chmod +x run-demo.zsh
./run-demo.zsh
```

---

<a id="8"></a>
## 8️⃣ Tips & Gotchas (zsh‑friendly)

| Symptom | What it means | Fix (zsh) |
|---------|---------------|-----------|
| `Device #1: Not a native Intel OpenCL runtime.` | Normal on Apple Silicon – hashcat is using Apple’s OpenCL driver. | **Ignore**. |
| `clGetDeviceIDs(): CL_DEVICE_NOT_FOUND` | No GPU detected. | Re‑install hashcat (`brew reinstall hashcat`) and ensure you’re on macOS 14+ (OpenCL support built‑in). |
| `Hash-file …: Separator unmatched` | Incorrect format for the selected `-m`. | Double‑check the file (no stray spaces, proper line break). |
| `No hashes loaded.` | Hash file empty or path wrong. | Use absolute paths (`~/hashcat-demo/...`). |
| `Permission denied` when writing a pot‑file | Destination directory not writable. | Keep pot‑files inside your home folder (`--potfile-path ~/hashcat-demo/cracked.pot`). |
| `bash: command not found` after copying a line that starts with `bash …` | You’re in zsh; the leading `bash` is unnecessary. | Remove the leading `bash` (just run the command itself). |

---

## 🎉 You’re ready!

You now have a **fully‑zsh‑compatible hashcat environment** on your Apple‑Silicon Mac, plus a curated list of the **largest publicly available password dictionaries**.  

- Pick the word‑list that matches your hardware and the size of the job.  
- Remember: **always crack only hashes you own or for which you have written permission**.  

Happy (ethical) cracking! 🚀