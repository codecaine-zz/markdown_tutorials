# THC-Hydra (Network Logon Cracker) Complete Guide

`hydra` (THC-Hydra) is a legendary, multi-threaded network logon cracker designed by van Hauser and David Maciejak. Supporting over 50 protocols—including SSH, FTP, HTTP(S) forms, MySQL, Redis, SMB, and VNC—`hydra` is a standard auditing tool for security consultants to benchmark password strength and identify exposed administrative credentials.

---

## 📚 Table of Contents

1. [Overview & Supported Protocols](#overview-supported-protocols)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Core Syntax & Basic Attack Patterns](#core-syntax-basic-attack-patterns)
4. [Cracking SSH & FTP Services](#cracking-ssh-ftp-services)
5. [Database Services (MySQL, Redis, PostgreSQL)](#database-services-mysql-redis-postgresql)
6. [Web Forms & HTTP Authentication (`http-post-form`)](#web-forms-http-authentication-http-post-form)
7. [Concurrency, Timing & Throttling (`-t`, `-W`)](#concurrency-timing-throttling--t--w)
8. [Saving Output & Resuming Sessions](#saving-output-resuming-sessions)
9. [Defensive Mitigation & Account Lockout](#defensive-mitigation-account-lockout)
10. [Everyday Cheat Sheet & Useful Aliases](#everyday-cheat-sheet-useful-aliases)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Supported Protocols

`hydra` is designed to be fast, reliable, and protocol-flexible:

- **Protocol Coverage**: SSH, FTP, HTTP/HTTPS Basic/Digest, HTTP POST/GET Web Forms, MySQL, Redis, SMB, SNMP, VNC, Telnet, IMAP, POP3, SMTP, LDAP, and RTSP.
- **Credential Combinations**: Supports single login + password dictionary, user dictionary + single password, matrix dictionary attack, and colon-separated combo files (`user:pass`).
- **Safety Flags**: Exit immediately upon finding a valid pair (`-f`), limit task threads to prevent Denial of Service (`-t`), and set response timeouts (`-w`).

---

## ⚙️ Homebrew Installation & Verification

### 1. Install via Homebrew

```bash
brew install hydra
```

### 2. Verify Installation

```bash
which hydra
hydra -h | head -n 5
```

Output:
```text
/opt/homebrew/bin/hydra
Hydra v9.7 (c) 2023 by van Hauser/THC & David Maciejak
```

---

## 🔑 Core Syntax & Basic Attack Patterns

The generalized syntax for `hydra`:

```bash
hydra [credentials] [options] service://<target>[:port]
```

### Credential Flags

| Flag | Purpose | Example |
| :--- | :--- | :--- |
| `-l <user>` | Single username | `-l admin` |
| `-L <file>` | Dictionary file of usernames | `-L users.txt` |
| `-p <pass>` | Single password | `-p Password123!` |
| `-P <file>` | Dictionary file of passwords | `-P rockyou.txt` |
| `-C <file>` | Colon-separated combo list (`user:pass`) | `-C credentials.txt` |
| `-f` | Exit as soon as the first valid pair is found | `-f` |
| `-V` / `-v` | Verbose / show attempt progress | `-V` |

---

## 🔐 Cracking SSH & FTP Services

### 1. SSH Audit

Targeting an SSH server on port 22:

```bash
# Single known user with password list; stop on first match
hydra -l ubuntu -P /path/to/passwords.txt -t 4 -f -V ssh://192.168.1.100

# Multiple users and custom SSH port
hydra -L users.txt -P passwords.txt -s 2222 -t 4 ssh://192.168.1.100
```

> [!NOTE]
> SSH daemons frequently drop connections if too many threads connect simultaneously. Keep `-t` between `4` and `8` for SSH.

### 2. FTP Audit

Targeting an FTP daemon:

```bash
hydra -L users.txt -P passwords.txt -t 16 -f ftp://192.168.1.50
```

---

## 🗄️ Database Services (MySQL, Redis, PostgreSQL)

### 1. MySQL

Audit MySQL root or service accounts:

```bash
# Test root account against dictionary
hydra -l root -P passwords.txt -t 8 -f mysql://192.168.1.200:3306
```

### 2. Redis

Redis traditionally requires only a password without a username:

```bash
# Redis AUTH authentication
hydra -P passwords.txt -t 16 -f redis://192.168.1.200:6379
```

---

## 🌐 Web Forms & HTTP Authentication (`http-post-form`)

Audit web application login forms by defining:
1. The URL path
2. The POST body parameters with `^USER^` and `^PASS^` placeholders
3. The failure condition string returned by the web app (e.g. `Invalid credentials` or `F=incorrect`)

### Syntax:
```bash
hydra -l admin -P passwords.txt 192.168.1.10 \
  http-post-form "/login.php:username=^USER^&password=^PASS^:F=Invalid username or password"
```

### Form String Breakdown:
- `/login.php`: Action path handling the request.
- `username=^USER^&password=^PASS^`: Form field names submitted.
- `F=Invalid username or password`: Indicates failed authentication if this string appears in the response body.

---

## ⚡ Concurrency, Timing & Throttling (`-t`, `-W`)

Prevent crashing target services or triggering rate-limiting WAFs:

```bash
# Set number of concurrent tasks (default is 16)
hydra -t 4 ...

# Wait between connections (wait 2 seconds between attempts)
hydra -W 2 ...

# Socket timeout in seconds (default is 32s)
hydra -w 10 ...
```

---

## 💾 Saving Output & Resuming Sessions

### 1. Save Discovered Credentials

Export cracked credentials to a designated text file:

```bash
hydra -l admin -P passwords.txt -o found_credentials.txt ssh://192.168.1.100
```

### 2. Restoring Interrupted Sessions

If an audit is canceled or disconnected, `hydra` creates a `hydra.restore` file in the current directory:

```bash
# Resume previous session
hydra -R
```

---

## 🛡️ Defensive Mitigation & Account Lockout

Audit results should inform hardening best practices:
1. **Enforce Rate Limiting & Fail2ban**: Automatically ban IP addresses after 3–5 failed authentication attempts.
2. **Disable Password Authentication**: Use SSH Public Key authentication (`PubkeyAuthentication yes`) and disable passwords entirely.
3. **Implement Multi-Factor Authentication (MFA)**: Protect administrative portals with TOTP/FIDO2 keys.
4. **Use Strong Passphrase Policies**: Minimum length > 16 characters to defeat dictionary attacks.

---

## 📋 Everyday Cheat Sheet & Useful Aliases

### Quick Command Reference

| Protocol | Command Template |
| :--- | :--- |
| **SSH** | `hydra -l user -P pass.txt -t 4 -f ssh://<host>` |
| **FTP** | `hydra -L users.txt -P pass.txt -t 16 ftp://<host>` |
| **MySQL** | `hydra -l root -P pass.txt -t 8 mysql://<host>` |
| **Redis** | `hydra -P pass.txt -t 16 redis://<host>` |
| **HTTP Basic** | `hydra -l admin -P pass.txt http-get://<host>/admin` |
| **HTTP Post Form** | `hydra -l admin -P pass.txt <host> http-post-form "/login:u=^USER^&p=^PASS^:F=failed"` |
| **Resume Session**| `hydra -R` |

---

## 🗑️ Uninstallation

```bash
brew uninstall hydra
```
