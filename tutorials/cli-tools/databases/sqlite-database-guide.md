# SQLite: Self-Contained Serverless SQL Database CLI Guide

## Table of Contents

1. [What is `sqlite`?](#1-what-is-sqlite)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Creating & Opening Databases](#4-creating-opening-databases)
5. [Essential Dot Commands](#5-essential-dot-commands)
6. [Non-Interactive CLI & Bash Automation](#6-non-interactive-cli-bash-automation)
7. [Practical SQL Basics & Formatted Output](#7-practical-sql-basics-formatted-output)
8. [Advanced SQL Patterns (Upsert, CTEs, Window Functions & JSON)](#8-advanced-sql-patterns-upsert-ctes-window-functions-json)
9. [Full-Text Search (FTS5)](#9-full-text-search-fts5)
10. [Performance Optimization & PRAGMAs (WAL Mode)](#10-performance-optimization-pragmas-wal-mode)
11. [Working with Multiple Databases (`ATTACH DATABASE`)](#11-working-with-multiple-databases-attach-database)
12. [Importing & Exporting Data (CSV, JSON, SQL Scripts)](#12-importing-exporting-data-csv-json-sql-scripts)
13. [Database Backup, Restoring & Vacuuming](#13-database-backup-restoring-vacuuming)
14. [Uninstallation](#14-uninstallation)

---

### 1. What is `sqlite`?

`sqlite` (accessed via the `sqlite3` command-line utility) is a self-contained, serverless, zero-configuration, transactional SQL database engine. Unlike traditional database systems (MySQL, PostgreSQL) that run as background server daemons, SQLite reads and writes directly to ordinary disk files.

#### Key Features
* **Zero Configuration:** No server daemon process to start, stop, or configure.
* **Single-File Storage:** The entire database (tables, indexes, triggers, data) is stored in a single cross-platform disk file.
* **Ubiquitous & Lightweight:** Embedded in browsers, mobile operating systems (iOS/Android), and CLI applications worldwide.

---

### 2. Prerequisites

Verify Homebrew on your Apple Silicon (ARM) Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

While macOS includes a default system version of SQLite, installing the latest release via Homebrew provides modern features, extended extension support, and updated CLI tools:

```bash
brew install sqlite
```

#### Add Homebrew SQLite to your Shell PATH (`~/.zshrc`)
Because macOS ships with a built-in SQLite, add Homebrew's binary path to the front of your `PATH`:

```bash
echo 'export PATH="/opt/homebrew/opt/sqlite/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

Verify binary path and version:

```bash
which sqlite3
sqlite3 --version
```

**Expected Output:**
```text
/opt/homebrew/opt/sqlite/bin/sqlite3
3.45.x (or latest)
```

---

### 4. Creating & Opening Databases

Launch `sqlite3` by specifying a database filename:

#### 1. Open a Persistent Disk Database
```bash
sqlite3 company.db
```

If `company.db` does not exist, SQLite creates it automatically upon saving data.

#### 2. Open an In-Memory Temporary Database
To test queries without writing to disk:

```bash
sqlite3 :memory:
```

---

### 5. Essential Dot Commands

SQLite CLI uses special commands starting with a dot (`.`) to control display formatting, list tables, and manage settings.

| Command | Action |
|---|---|
| `.help` | Display list of all dot commands |
| `.tables` | List all tables in current database |
| `.schema [table]` | Display SQL `CREATE TABLE` statement for tables |
| `.mode [mode]` | Set output format (`column`, `json`, `csv`, `box`, `markdown`, `html`) |
| `.headers on\|off` | Display column header titles in query outputs |
| `.show` | Show current SQLite configuration settings |
| `.output [filename]` | Redirect query output to a specified file (use `.output stdout` to reset) |
| `.read [file.sql]` | Execute SQL statements from an external script file |
| `.quit` / `.exit` | Exit the `sqlite3` prompt |

---

### 6. Non-Interactive CLI & Bash Automation

You can run SQLite queries directly from terminal one-liners or inside shell scripts without opening an interactive session.

#### 1. Single Command Execution
```bash
sqlite3 company.db "SELECT name, salary FROM employees WHERE salary > 90000;"
```

#### 2. Output Query Directly as Formatted JSON
```bash
sqlite3 -json company.db "SELECT id, name, department FROM employees;"
```

#### 3. Pipe CSV Output to Files or Other Tools
```bash
sqlite3 -header -csv company.db "SELECT * FROM employees;" > export_employees.csv
```

#### 4. Execute Multi-Line SQL via Heredoc in Scripts
```bash
sqlite3 app_data.db <<EOF
CREATE TABLE IF NOT EXISTS logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    level TEXT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO logs (level, message) VALUES ('INFO', 'System started'), ('ERROR', 'Connection failed');
SELECT * FROM logs;
EOF
```

---

### 7. Practical SQL Basics & Formatted Output

Configure readable output formatting inside the interactive `sqlite>` shell:

```sql
.headers on
.mode column
```

#### Create a Table
```sql
CREATE TABLE employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    department TEXT NOT NULL,
    salary REAL NOT NULL,
    hired_date TEXT DEFAULT CURRENT_DATE
);
```

#### Insert Data
```sql
INSERT INTO employees (name, department, salary) 
VALUES ('Alice Smith', 'Engineering', 125000.00),
       ('Bob Jones', 'Marketing', 88000.00),
       ('Charlie Brown', 'Engineering', 130000.00),
       ('Diana Prince', 'Product', 115000.00);
```

#### Query Data
```sql
SELECT id, name, department, salary, hired_date 
FROM employees 
WHERE salary >= 90000 
ORDER BY salary DESC;
```

**Example Output:**
```text
id  name           department   salary    hired_date
--  -------------  -----------  --------  ----------
3   Charlie Brown  Engineering  130000.0  2026-07-22
1   Alice Smith    Engineering  125000.0  2026-07-22
4   Diana Prince   Product      115000.0  2026-07-22
```

#### Update & Delete
```sql
UPDATE employees SET salary = 95000.00 WHERE name = 'Bob Jones';
DELETE FROM employees WHERE id = 2;
```

---

### 8. Advanced SQL Patterns (Upsert, CTEs, Window Functions & JSON)

#### 1. Upsert (`ON CONFLICT DO UPDATE`)
Idempotent insert or update pattern based on unique constraints:

```sql
CREATE TABLE users (
    email TEXT PRIMARY KEY,
    username TEXT NOT NULL,
    login_count INTEGER DEFAULT 1,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- First insertion
INSERT INTO users (email, username) VALUES ('alice@example.com', 'alice');

-- Upsert: update login_count and last_login on collision
INSERT INTO users (email, username, login_count) 
VALUES ('alice@example.com', 'alice', 1)
ON CONFLICT(email) DO UPDATE SET 
    login_count = login_count + 1,
    last_login = CURRENT_TIMESTAMP;
```

#### 2. Common Table Expressions (CTE) & Recursive Series
Generate a sequence of dates or numbers without external loops:

```sql
-- Generate date series for the next 7 days
WITH RECURSIVE dates(date_val) AS (
    SELECT DATE('now')
    UNION ALL
    SELECT DATE(date_val, '+1 day')
    FROM dates
    LIMIT 7
)
SELECT date_val FROM dates;
```

#### 3. Window Functions (`ROW_NUMBER` & Ranking)
Rank salaries within each department:

```sql
SELECT 
    name, 
    department, 
    salary,
    RANK() OVER (PARTITION BY department ORDER BY salary DESC) as dept_rank
FROM employees;
```

#### 4. Native JSON Extraction (`json_extract` & `->` / `->>`)
SQLite supports storing and querying raw JSON documents natively:

```sql
CREATE TABLE event_logs (
    id INTEGER PRIMARY KEY,
    payload JSON
);

INSERT INTO event_logs (payload) VALUES 
('{"user": "alice", "action": "click", "metadata": {"page": "/home", "browser": "Chrome"}}'),
('{"user": "bob", "action": "purchase", "metadata": {"page": "/cart", "browser": "Safari"}}');

-- Extract JSON values using operators (->> extracts unquoted scalar values)
SELECT 
    payload->>'$.user' AS username,
    payload->>'$.action' AS action,
    payload->>'$.metadata.browser' AS browser
FROM event_logs
WHERE payload->>'$.action' = 'purchase';
```

---

### 9. Full-Text Search (FTS5)

SQLite includes built-in fast full-text search capability using FTS5 virtual tables.

#### Create and Populate an FTS5 Table
```sql
CREATE VIRTUAL TABLE articles USING fts5(title, content);

INSERT INTO articles (title, content) VALUES
('SQLite Guide', 'SQLite is a lightweight, serverless SQL database engine.'),
('PostgreSQL Tutorial', 'PostgreSQL is a powerful open-source object-relational database.'),
('CLI Tools', 'Command-line utilities make automation efficient and fast.');
```

#### Run Full-Text Search Queries
```sql
-- Search for matching keywords
SELECT title, snippet(articles, 1, '[', ']', '...', 10) AS match 
FROM articles 
WHERE articles MATCH 'database OR lightweight';
```

**Example Output:**
```text
title         match
------------  --------------------------------------------------
SQLite Guide  SQLite is a [lightweight], serverless SQL [database] engine.
```

---

### 10. Performance Optimization & PRAGMAs (WAL Mode)

PRAGMA statements modify SQLite engine behaviors and optimizations.

#### 1. Enable Write-Ahead Logging (WAL) Mode
WAL mode significantly improves read/write concurrency and write performance:

```sql
PRAGMA journal_mode = WAL;
PRAGMA synchronous = NORMAL;
```

#### 2. Enforce Foreign Key Constraints
Foreign key enforcement is disabled by default for backward compatibility; enable it per session:

```sql
PRAGMA foreign_keys = ON;
```

#### 3. Check Database Integrity & Schema Version
```sql
PRAGMA integrity_check;
PRAGMA user_version;
```

---

### 11. Working with Multiple Databases (`ATTACH DATABASE`)

You can attach multiple database files into a single session and join tables across them.

```sql
-- Inside sqlite shell: attach another database file as alias 'analytics'
ATTACH DATABASE 'metrics.db' AS analytics;

-- Query across attached database tables
SELECT e.name, a.visits 
FROM main.employees e 
JOIN analytics.user_visits a ON e.id = a.user_id;

-- Detach when complete
DETACH DATABASE analytics;
```

---

### 12. Importing & Exporting Data (CSV, JSON, SQL Scripts)

#### Export Query Results to CSV
Inside `sqlite3`:

```sql
.mode csv
.output employees_export.csv
SELECT * FROM employees;
.output stdout
.mode column
```

#### Export Query Results to JSON
```sql
.mode json
.output employees.json
SELECT * FROM employees;
.output stdout
.mode column
```

#### Import CSV into a Table
Create a sample CSV file (`team.csv`):

```csv
id,name,role
101,Dave,Architect
102,Eve,Designer
```

Import inside `sqlite3`:

```sql
.mode csv
.import --skip 1 team.csv team_members
```

Verify imported table:

```sql
.mode column
SELECT * FROM team_members;
```

---

### 13. Database Backup, Restoring & Vacuuming

#### Backup Database to an SQL Script File
From the macOS terminal command line:

```bash
sqlite3 company.db .dump > company_backup.sql
```

#### Online Live Backup Command
Back up a running database safely using the `.backup` dot command:

```bash
sqlite3 company.db ".backup 'company_live_backup.db'"
```

#### Restore Database from SQL Script File
```bash
sqlite3 company_restored.db < company_backup.sql
```

#### Reclaim Disk Space (`VACUUM`)
After deleting rows or tables, run `VACUUM` inside the `sqlite>` shell to defragment the disk file and shrink file size:

```sql
VACUUM;
```

---

### 14. Uninstallation

```bash
brew uninstall sqlite
```