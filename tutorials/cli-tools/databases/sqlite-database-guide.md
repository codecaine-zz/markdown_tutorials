# SQLite: Self-Contained Serverless SQL Database CLI Guide

## Table of Contents

1. [What is `sqlite`?](#1-what-is-sqlite)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Creating & Opening Databases](#4-creating-opening-databases)
5. [Essential Dot Commands (`.help`, `.mode`, `.schema`)](#5-essential-dot-commands-help-mode-schema)
6. [Practical SQL Examples & Formatted Output](#6-practical-sql-examples-formatted-output)
7. [Importing & Exporting Data (`.import`, `.dump`)](#7-importing-exporting-data-import-dump)
8. [Database Backup & Vacuuming](#8-database-backup-vacuuming)
9. [Uninstallation](#9-uninstallation)

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

You will enter the interactive `sqlite>` prompt.

---

### 5. Essential Dot Commands (`.help`, `.mode`, `.schema`)

SQLite CLI uses special commands starting with a dot (`.`) to control display formatting, list tables, and manage settings.

| Command | Action |
|---|---|
| `.help` | Display list of all dot commands |
| `.tables` | List all tables in current database |
| `.schema [table]` | Display SQL `CREATE TABLE` statement |
| `.mode column` | Format output into aligned columns |
| `.headers on` | Display column header titles in query outputs |
| `.show` | Show current SQLite configuration settings |
| `.quit` / `.exit` | Exit the `sqlite3` prompt |

---

### 6. Practical SQL Examples & Formatted Output

Configure readable column output inside the `sqlite>` shell:

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
       ('Charlie Brown', 'Engineering', 130000.00);
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
```

#### Update & Delete
```sql
UPDATE employees SET salary = 95000.00 WHERE name = 'Bob Jones';
DELETE FROM employees WHERE id = 2;
```

---

### 7. Importing & Exporting Data (`.import`, `.dump`)

#### Export Query Results to a CSV File
Inside the `sqlite>` prompt:

```sql
.mode csv
.output employees_export.csv
SELECT * FROM employees;
.output stdout
.mode column
```

#### Import a CSV File into a New Table
Create a sample CSV file from terminal (`data.csv`):

```text
id,name,role
101,Dave,Architect
102,Eve,Designer
```

Import inside `sqlite3`:

```sql
.mode csv
.import --skip 1 data.csv team_members
```

Verify imported table:

```sql
.mode column
SELECT * FROM team_members;
```

---

### 8. Database Backup & Vacuuming

#### Backup Database to an SQL Script File
From the macOS terminal command line:

```bash
sqlite3 company.db .dump > company_backup.sql
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

### 9. Uninstallation

```bash
brew uninstall sqlite
```