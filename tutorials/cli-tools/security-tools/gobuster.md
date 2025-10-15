Below is a **complete cheat‑sheet** for using **Gobuster** after you have installed it with Homebrew (`brew install gobuster`).  
It covers the most common modes, the key flags, useful tip‑sets and ready‑to‑run one‑liners that you can copy‑paste into a terminal.

---

## 1. Quick install / upgrade with Homebrew

```bash
# Install
brew install gobuster

# Verify version
gobuster -V
# → gobuster v3.6.0 (or newer)

# Keep it up‑to‑date
brew upgrade gobuster
```

*Homebrew puts the binary in `/usr/local/bin/gobuster` (Intel) or `/opt/homebrew/bin/gobuster` (Apple Silicon) – it’s already in your `$PATH`.*

---

## 2. How Gobuster is organised

| Mode (`-m`) | What it does | Typical wordlist |
|------------|--------------|------------------|
| `dir`      | Brute‑force HTTP(S) directories / files | `dirbuster.txt`, `common.txt` |
| `dns`      | DNS sub‑domain enumeration | `dns.txt`, `subdomains.txt` |
| `vhost`    | Virtual‑host discovery (host header) | same as `dns` |
| `fuzz`     | Generic fuzzing of any URL path or parameter | custom list |
| `s3`       | AWS S3 bucket enumeration (requires AWS creds) | `s3buckets.txt` |
| `help`     | Show help        | N/A |

> **Tip:** Homebrew ships a tiny set of wordlists in `$HOMEBREW_PREFIX/Cellar/gobuster/<ver>/share/gobuster/`.  
> You can also use the famous *SecLists* collection (`/usr/share/seclists/...`) or download your own.

---

## 3. Core flags you’ll use in almost every command

| Flag | Description | Example |
|------|-------------|---------|
| `-u <url>` | Target URL (must include scheme). | `-u https://example.com` |
| `-w <file>` | Wordlist (plain‑text, one entry per line). | `-w /usr/share/seclists/Discovery/Web-Content/common.txt` |
| `-t <int>` | Number of concurrent threads (default 10). | `-t 50` |
| `-o <file>` | Write JSON/CSV output to a file. | `-o scan.txt` |
| `-s <code>` | Only show responses with these HTTP status codes (comma‑separated). | `-s 200,204,301,302,307,403` |
| `-e` | **Enable** extended mode – also show 403/401/500 etc. |
| `-x <ext>` | Force extensions to be appended (comma‑separated). | `-x php,html,txt` |
| `-k` | Skip TLS verification (useful for self‑signed certs). |
| `-p <proxy>` | Use an HTTP proxy (`http://127.0.0.1:8080`). |
| `-r` | Follow redirects (default **off**). |
| `-b <code>` | **Blacklist** these status codes (do not display). |
| `-c` | Show response size in output (default *on*). |
| `-l` | **Log to stdout** in a “live” line‑by‑line fashion (good for piping). |
| `-q` | Quiet mode – only final results, no progress bar. |
| `--delay <ms>` | Millisecond delay between requests (rate‑limit). |
| `--random-agent` | Rotate User‑Agent header automatically. |

---

## 4. Directory / File brute‑forcing (`dir` mode)

### 4.1 Basic scan

```bash
gobuster dir \
  -u https://demo.test \
  -w /usr/share/seclists/Discovery/Web-Content/common.txt \
  -t 40 \
  -x php,html,txt \
  -s 200,204,301,302,307,403 \
  -o demo-dir.txt
```

*What happens*  

- Requests `https://demo.test/<word>` and the three extensions.  
- Shows only “interesting” codes (200, 301, 403 …).  
- Writes a tidy plain‑text log to `demo-dir.txt`.

### 4.2 Show **all** responses (including 403, 401, 500)

```bash
gobuster dir -u https://demo.test -w /usr/share/seclists/Discovery/Web-Content/common.txt -e
```

### 4.3 Skip SSL verification (self‑signed cert)

```bash
gobuster dir -u https://selfsigned.local -w wordlist.txt -k
```

### 4.4 Use a proxy (Burp/OWASP ZAP)

```bash
gobuster dir -u https://target.com -w small.txt -p http://127.0.0.1:8080
```

### 4.5 Rate‑limit to avoid WAF bans

```bash
gobuster dir -u https://target.com -w big.txt --delay 200
```

### 4.6 Export **JSON** for later processing

```bash
gobuster dir -u https://target.com -w wordlist.txt -o out.json -f json
```

*(`-f json` is shorthand for “output format = json”, available from v3.4+.)*

---

## 5. DNS sub‑domain enumeration (`dns` mode)

### 5.1 Simple sub‑domain brute‑force

```bash
gobuster dns \
  -d example.com \
  -w /usr/share/seclists/Discovery/DNS/subdomains-top1million-5000.txt \
  -t 100 \
  -o dns.txt
```

*Result* – each line contains the discovered sub‑domain and its IP address.

### 5.2 Use a **custom resolver** (faster and avoids rate‑limits)

```bash
gobuster dns -d example.com -w subdomains.txt \
  -r 8.8.8.8,1.1.1.1 -t 200 -o dns-resolved.txt
```

### 5.3 Show **only** `A` records (default shows any)

```bash
gobuster dns -d example.com -w list.txt -R A
```

### 5.4 Brute‑force **wildcard‑filtered** results (skip those that always resolve)

```bash
gobuster dns -d example.com -w list.txt -x -a -w /dev/null
```

*(The `-x` flag disables wildcard detection; omit it to let Gobuster auto‑filter.)*

---

## 6. Virtual‑host discovery (`vhost` mode)

*This works like DNS mode but sends an HTTP request with a custom `Host:` header.*

```bash
gobuster vhost \
  -u https://target.com \
  -w /usr/share/seclists/Discovery/DNS/subdomains-top1million-5000.txt \
  -t 150 \
  -o vhost.txt \
  -c
```

*Key points*  

- `-c` prints the **response size** – useful to differentiate real vhosts from the default site.  
- Add `-s 200,302` to only keep “valid” virtual hosts.  
- Use `-k` if the target uses a self‑signed TLS cert.

---

## 7. Generic fuzzing (`fuzz` mode)

You can fuzz **any part** of a URL (path, query string, POST data).  
Example: fuzzing a parameter `id` that expects numeric values.

```bash
gobuster fuzz \
  -u "https://api.example.com/item?id=FUZZ" \
  -w /usr/share/seclists/Discovery/Web-Content/numbers.txt \
  -t 200 \
  -s 200,404 \
  -o fuzz-id.txt
```

`FUZZ` is the placeholder Gobuster replaces with each word from the list.

**Fuzz POST bodies**

```bash
gobuster fuzz \
  -u https://app.example.com/login \
  -X POST \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=admin&password=FUZZ" \
  -w /usr/share/seclists/Passwords/Leaked-Dataset-10M.txt \
  -t 50 -o login-fuzz.txt
```

---

## 8. AWS S3 bucket enumeration (`s3` mode)

> **NOTE**: Requires AWS credentials set in `~/.aws/credentials` or via environment variables.

```bash
gobuster s3 \
  -b mybucketlist.txt \
  -r us-east-1 \
  -o s3-results.txt
```

Options:  

- `-r <region>` – AWS region (defaults to `us-east-1`).  
- `-p` – Use a **profile** name from the AWS config file.

---

## 9. Combining flags for *real‑world* pentest scenarios

### 9.1 “Stealth” directory scan behind a proxy, low‑rate, ignore 403

```bash
gobuster dir \
  -u https://internal-app.local \
  -w /usr/share/seclists/Discovery/Web-Content/raft-large-directories.txt \
  -t 20 \
  --delay 500 \
  -p http://127.0.0.1:8080 \
  -s 200,301,302,307 \
  -b 403 \
  -k \
  -o internal-dir.txt
```

### 9.2 Full‑scale DNS + vhost sweep, output as JSON for automated parsing

```bash
# DNS
gobuster dns -d target.com -w subdomains.txt -t 150 -o dns.json -f json

# VHOST
gobuster vhost -u https://target.com -w subdomains.txt -t 150 -o vhost.json -f json -c
```

You can later `jq` the files:

```bash
jq '.[] | select(.status==200) | .host' vhost.json
```

---

## 10. Handy one‑liners for piping / automation

| Goal | Command |
|------|---------|
| **Only show URLs** (useful for `xargs`) | `gobuster dir -u https://site -w list.txt -s 200 -q | cut -d' ' -f2` |
| **Save only 200‑OK paths** | `gobuster dir ... -s 200 -o - | grep '^/.* 200' > good.txt` |
| **Run Gobuster only if target is up** | `curl -s -o /dev/null -w "%{http_code}" https://target.com | grep -q 200 && gobuster dir ...` |
| **Parallelize two scans** | `(gobuster dir ... > dir.txt) & (gobuster dns ... > dns.txt) & wait` |

---

## 11. Common pitfalls & how to fix them

| Symptom | Cause | Fix |
|---------|-------|-----|
| `TLS handshake timeout` | Target uses a self‑signed cert or corporate intercept | Add `-k` or use a custom CA bundle with `--tls-ca <file>` |
| **Too many 403** responses | WAF or rate‑limiting blocking you | Add `--delay`, lower `-t`, rotate User‑Agents (`--random-agent`) |
| **No output at all** | Wordlist path wrong or empty | Verify with `cat <wordlist>`; use absolute path |
| `invalid host` error in DNS mode | `-d` missing trailing dot or using a non‑existent domain | Use `-d example.com` (no scheme) |
| **Too much noise** (many 404 lines) | Default mode shows everything | Use `-s` to whitelist codes, or `-b` to blacklist 404 |
| **Gobuster stops early** | System runs out of file descriptors (especially with high `-t`) | Increase limits: `ulimit -n 4096` before running |

---

## 12. Quick reference cheat‑sheet (copy‑paste)

```bash
# 1. Directory brute‑force (most common)
gobuster dir -u https://target.com -w /usr/share/seclists/Discovery/Web-Content/common.txt -t 50 -x php,html,js -s 200,301,302,403 -o dir.txt

# 2. DNS sub‑domains
gobuster dns -d target.com -w /usr/share/seclists/Discovery/DNS/subdomains.txt -t 150 -o dns.txt

# 3. Virtual‑host discovery
gobuster vhost -u https://target.com -w /usr/share/seclists/Discovery/DNS/subdomains.txt -t 100 -c -o vhost.txt

# 4. Fuzz a GET parameter
gobuster fuzz -u "https://api.example.com/item?id=FUZZ" -w /usr/share/seclists/Discovery/Web-Content/numbers.txt -t 200 -s 200,404 -o fuzz.txt

# 5. Fuzz a POST field (login bruteforce)
gobuster fuzz -u https://app.example.com/login -X POST -H "Content-Type: application/x-www-form-urlencoded" \
    -d "username=admin&password=FUZZ" \
    -w /usr/share/seclists/Passwords/Leaked-Dataset-10M.txt -t 30 -o login.txt

# 6. Scan behind a proxy, ignore cert errors, slow rate
gobuster dir -u https://internal.local -w list.txt -t 20 --delay 500 -p http://127.0.0.1:8080 -k -s 200,301 -b 403 -o stealth.txt
```

Feel free to edit the paths to match where your wordlists live (e.g., `~/SecLists/...`). All the switches shown above are supported by the version installed via Homebrew (v3+).  

Happy hunting! 🎯🚀  