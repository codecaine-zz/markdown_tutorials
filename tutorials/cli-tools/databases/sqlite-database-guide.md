# 📘 Official‑style **SQLite** Guide for macOS (Homebrew — 2025 edition)

> **TL;DR**  
> ```bash
> # Install
> brew install sqlite                     # keg‑only formula
> # Run
> /usr/local/opt/sqlite/bin/sqlite3 my.db   # create/open a DB
> ```  
> The guide below expands that one‑liner into a full‑stack workflow: install, locate files, use the CLI, common SQL “set‑and‑get” one‑liners, import/export, backup, security, maintenance and uninstall. Every code block is linked to the official documentation that explains it.

---

## Table of Contents
1. [Prerequisites – Homebrew](#1-prerequisites)  
2. [Install SQLite (brew)](#2-install-sqlite)  
3. [Where Homebrew puts SQLite](#3-locations)  
4. [Running the CLI – interactive & one‑liners](#4-cli)  
5. **[SQL cheat‑sheet – create / insert / select / update / delete](#5-cheatsheet)** ← **copy‑paste ready**  
6. [Import & export (CSV, dump, .backup)](#6-import-export)  
7. [File‑based persistence, backups & snapshots](#7-persistence)  
8. [Security – file permissions & optional encryption extensions](#8-security)  
9. [Maintenance – vacuum, integrity‑check, schema migrations](#9-maintenance)  
10. [Common pitfalls & troubleshooting](#10-mistakes)  
11. [Uninstall / cleanup](#11-uninstall)  

---

<a name="1-prerequisites"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version               # should print 4.x or later
# If missing → install it (official script)
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

*Homebrew is the **officially supported** way to get SQLite on macOS* – see the Homebrew docs【1†L5-L9】.  

**Official reference:**  
- Homebrew Installation Guide → <https://brew.sh>  
- Homebrew FAQ – “What does keg‑only mean?” → <https://docs.brew.sh/FAQ#what-does-keg-only-mean>

---

<a name="2-install-sqlite"></a>
## 2️⃣ Install SQLite  

```bash
brew install sqlite
```

The formula is **keg‑only** (it is not symlinked into `/usr/local/bin` because macOS already ships an older SQLite).  

**Official reference:**  
- SQLite formula page → <https://formulae.brew.sh/formula/sqlite> (install command shown)  
- Formula source (shows `keg_only :provided_by_macos`) → <https://github.com/Homebrew/homebrew-core/blob/HEAD/Formula/s/sqlite.rb>

---

<a name="3-locations"></a>
## 3️⃣ Where Homebrew puts SQLite  

| Item | Apple silicon (`/opt/homebrew`) | Intel (`/usr/local`) | Reference |
|------|--------------------------------|----------------------|-----------|
| Binary | `/opt/homebrew/opt/sqlite/bin/sqlite3` | `/usr/local/opt/sqlite/bin/sqlite3` | `$(brew --prefix sqlite)/bin/sqlite3` |
| Include files | `…/opt/sqlite/include` | `…/opt/sqlite/include` | |
| Libraries | `…/opt/sqlite/lib` | `…/opt/sqlite/lib` | |
| pkg‑config file | `…/opt/sqlite/lib/pkgconfig/sqlite3.pc` | same | |

Because it is keg‑only you must call the full path (or add the opt directory to `$PATH`). StackOverflow answer shows the exact location【9†L80-L91】.

**How‑to add it to your shell (official docs):**  

```bash
export PATH="$(brew --prefix sqlite)/bin:$PATH"
```

*Reference:* <https://docs.brew.sh/FAQ#what-does-keg-only-mean>

---

<a name="4-cli"></a>
## 4️⃣ Running the CLI – interactive & one‑liners  

```bash
# Open (or create) a database file named demo.db
sqlite3 demo.db                     # drops you into the sqlite> prompt
```

*One‑liner from your shell (no REPL)*  

```bash
sqlite3 demo.db "SELECT datetime('now');"
```

**Built‑in dot‑commands (official SQLite CLI docs):**

| Command | What it does | Docs |
|---------|--------------|------|
| `.tables` | List all tables | <https://sqlite.org/cli.html#dot_commands> |
| `.schema [TABLE]` | Show CREATE statements (all or single table) | same |
| `.help` | Show all dot‑commands | same |
| `.quit` | Exit REPL | same |
| `.backup DB FILE` | Binary backup while DB is live | <https://sqlite.org/cli.html#dot_backup> |
| `.dump` | Dump whole DB as SQL text | same |
| `.mode csv` | Switch output/import mode to CSV | same |
| `.import FILE TABLE` | Import CSV data (see §6) | same |

---

<a name="5-cheatsheet"></a>
## 5️⃣ **SQL cheat‑sheet – “set & get” one‑liners**  

> All examples assume you are already inside the `sqlite>` prompt. Replace `mydb.db` / `mytable` / column names as you wish. The syntax follows the SQLite Language Reference 【7†L107-L115】.

### 5.1 Create a table  

```sql
CREATE TABLE IF NOT EXISTS users (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    name    TEXT    NOT NULL,
    email   TEXT    UNIQUE,
    age     INTEGER,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

*Reference:* <https://sqlite.org/lang_createtable.html>

### 5.2 Insert a row  

```sql
INSERT INTO users (name,email,age) VALUES ('Alice','alice@example.com',30);
-- or replace on conflict
INSERT OR REPLACE INTO users (id,name,email,age) VALUES (1,'Bob','bob@example.com',25);
```

*Reference:* <https://sqlite.org/lang_insert.html>

### 5.3 Select / read  

```sql
SELECT * FROM users;                       -- all columns
SELECT name, email FROM users WHERE age>20;
SELECT COUNT(*) FROM users;                -- aggregate
SELECT name FROM users ORDER BY created DESC LIMIT 5;
```

*Reference:* <https://sqlite.org/lang_select.html>

### 5.4 Update  

```sql
UPDATE users SET age = age + 1 WHERE name = 'Alice';
```

*Reference:* <https://sqlite.org/lang_update.html>

### 5.5 Delete  

```sql
DELETE FROM users WHERE id = 42;
```

*Reference:* <https://sqlite.org/lang_delete.html>

### 5.6 Simple “set‑and‑get” one‑liner from the **shell**  

```bash
sqlite3 mydb.db "INSERT INTO kv (key,value) VALUES ('greeting','Hello');
                SELECT value FROM kv WHERE key='greeting';"
```

*Reference:* <https://sqlite.org/cli.html#command_line_parameters>

---

<a name="6-import-export"></a>
## 6️⃣ Import & Export (CSV, dump, .backup)

### 6.1 CSV import (via dot‑commands)  

```sql
.mode csv
.import /full/path/to/file.csv mytable   -- creates table automatically or appends
```

*Official guide* – see SQLite CLI “Importing CSV” section 【13†L38-L44】.  

To skip a header row (common CSV files):

```sql
.import -skip 1 /full/path/to/file.csv mytable
```

*Reference:* <https://sqlite.org/cli.html#dot_import>

### 6.2 CSV export  

```sql
.mode csv
.headers on
.output mydata.csv
SELECT * FROM mytable;
.output stdout   -- reset to console
```

*Reference:* <https://sqlite.org/cli.html#dot_output>

### 6.3 SQL dump  

```bash
sqlite3 mydb.db ".dump" > mydb.sql
```

*Reference:* <https://sqlite.org/cli.html#dot_dump>

### 6.4 Binary backup (online, safe)  

```sql
.backup main /tmp/mydb.backup   -- same as the .backup dot‑command 【10†L1-L3】
```

*Reference:* <https://sqlite.org/cli.html#dot_backup>

---

<a name="7-persistence"></a>
## 7️⃣ File‑based persistence, backups & snapshots  

* SQLite stores **all data in a single file** (`*.db`).  
* The file uses **Write‑Ahead Logging (WAL)** or the classic rollback journal – both ACID‑compliant.  
* **Backup strategies**  
  * **Cold copy** – stop all writers & `cp mydb.db /backup/`.  
  * **Live backup** – use the `.backup` dot‑command (see §6).  
  * **APFS snapshot** – `tmutil localsnapshots` while DB is live (macOS native).  

*Reference:* <https://sqlite.org/lockingv3.html> (file‑format & durability)

---

<a name="8-security"></a>
## 8️⃣ Security – file permissions & optional encryption  

| Concern | Remedy | Docs |
|---------|--------|------|
| Unauthorized read/write | `chmod 600 mydb.db` (owner‑only) | <https://sqlite.org/security.html> |
| Data‑at‑rest encryption | Compile with `-DSQLITE_HAS_CODEC` or use **SQLCipher** (`brew install sqlcipher`). | <https://www.zetetic.net/sqlcipher/> |
| Application‑level access control | Store secrets outside DB; use SQLite‑based access control via user‑defined functions. | <https://sqlite.org/c3ref/create_function.html> |

---

<a name="9-maintenance"></a>
## 9️⃣ Maintenance – vacuum, integrity‑check, migrations  

```sql
VACUUM;                         -- rebuilds the DB, reclaims free space
PRAGMA integrity_check;         -- verify file consistency
PRAGMA foreign_keys = ON;       -- enforce FK constraints (default OFF)
```

*Reference:* <https://sqlite.org/lang_vacuum.html> and <https://sqlite.org/pragma.html#pragma_integrity_check>

**Schema migrations** are usually performed with a series of `ALTER TABLE …` statements or by creating a new table, copying data, dropping the old one – the pattern described in the SQLite “ALTER TABLE” docs <https://sqlite.org/lang_altertable.html>.

---

<a name="10-mistakes"></a>
## 🔟 Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `sqlite3: command not found` | System’s older SQLite is found first; Homebrew’s keg‑only binary not in `$PATH`. | Add `$(brew --prefix sqlite)/bin` to `$PATH` or use the full path (see §3). |
| “Database is locked” errors | A stray `sqlite3` process still holding a lock. | Close all clients, or (if safe) delete the `*-journal` / `*-wal` files. |
| Missing extensions (e.g., JSON) | System SQLite lacks compile‑time options. | Use Homebrew’s version (includes `JSON1`, `FTS5`, etc.) – see the formula’s `ENV.append "CPPFLAGS"` block 【5†L41-L55】. |
| Import fails on first row | Header line being treated as data. | Use `.import -skip 1 …` or delete the header before import. |

*References:*  
- Homebrew “keg‑only” FAQ <https://docs.brew.sh/FAQ#what-does-keg-only-mean>  
- SQLite locking documentation <https://sqlite.org/lockingv3.html>  

---

<a name="11-uninstall"></a>
## 1️⃣1️⃣ Uninstall / cleanup  

```bash
brew uninstall sqlite                # removes the formula
# Optionally purge residual files
rm -rf $(brew --cellar sqlite)
```

If you added the opt directory to `$PATH`, remember to remove that line from your shell init files (`~/.zshrc`, `~/.bash_profile`, …).

*Reference:* <https://docs.brew.sh/Manpage#uninstall>

---

# 🎉 You’re ready!

You now have a **complete, copy‑paste‑ready reference** for installing, locating, using, importing, backing up, securing and maintaining SQLite on macOS via Homebrew, with every command linked to its official documentation. Happy querying! 🚀  