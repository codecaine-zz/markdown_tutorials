# PostgreSQL CLI Guide for Beginners & Developers

A comprehensive, practical guide to using the PostgreSQL command-line client (`psql`), database administration tools (`pg_dump`, `pg_restore`), advanced SQL features, and real-world scripting recipes on macOS.

---

## Table of Contents

1. [Prerequisites](#1-prerequisites)
2. [Installation & Service Management](#2-installation-service-management)
3. [Connecting with `psql`](#3-connecting-with-psql)
4. [Essential Meta-Commands (`\l`, `\c`, `\dt`, `\d`)](#4-essential-meta-commands-l-c-dt-d)
5. [Core DDL & CRUD SQL Operations](#5-core-ddl-crud-sql-operations)
6. [Non-Interactive CLI Execution & Formatting](#6-non-interactive-cli-execution-formatting)
7. [JSONB Data Manipulation](#7-jsonb-data-manipulation)
8. [Advanced SQL: CTEs & Window Functions](#8-advanced-sql-ctes-window-functions)
9. [Performance Tuning & Query Profiling (`EXPLAIN ANALYZE`)](#9-performance-tuning-query-profiling-explain-analyze)
10. [User Management & Security](#10-user-management-security)
11. [Database Monitoring & Session Management](#11-database-monitoring-session-management)
12. [Backups & Restores (`pg_dump` & `pg_restore`)](#12-backups-restores-pg_dump-pg_restore)
13. [VSCode Integration & Script Automation](#13-vscode-integration-script-automation)
14. [Command Quick Reference](#14-command-quick-reference)

---

### 1. Prerequisites

Verify Homebrew on your Apple Silicon Mac:

```bash
brew --version
```

---

### 2. Installation & Service Management

Install PostgreSQL server and CLI utilities via Homebrew:

```bash
brew install postgresql@16
```

#### Manage PostgreSQL Background Daemon (`brew services`)
```bash
# Start service
brew services start postgresql@16

# Check status
brew services list | grep postgresql

# Stop or restart service
brew services stop postgresql@16
brew services restart postgresql@16
```

---

### 3. Connecting with `psql`

Connect to local PostgreSQL server:

```bash
# Connect to default 'postgres' database
psql -U postgres
```

#### Connect to a Specific Database & Host
```bash
psql -U myuser -h localhost -p 5432 -d myapp_db
```

#### Pass Password via Environment Variable (Scripting)
```bash
PGPASSWORD="mysecretpassword" psql -U myuser -d myapp_db
```

---

### 4. Essential Meta-Commands (`\l`, `\c`, `\dt`, `\d`)

Meta-commands begin with a backslash `\` inside the `psql` interactive prompt:

| Meta-Command | Description |
| :--- | :--- |
| `\l` | List all databases |
| `\c dbname` | Switch/connect to specified database |
| `\dt` | List all tables in current database |
| `\d tablename` | Describe columns, data types, and indexes of a table |
| `\dn` | List all schemas |
| `\df` | List functions |
| `\du` | List users and assigned roles |
| `\timing` | Toggle query execution timer |
| `\q` | Quit / exit `psql` |

---

### 5. Core DDL & CRUD SQL Operations

#### 1. Create Database & Connect
```sql
CREATE DATABASE myapp_db;
\c myapp_db
```

#### 2. Create Table with Auto-Incrementing Primary Key & Constraints
```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    age INTEGER CHECK (age >= 18),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
```

#### 3. Insert Records & Return Inserted IDs
```sql
INSERT INTO users (name, email, age) 
VALUES ('Alice Smith', 'alice@example.com', 29),
       ('Bob Jones', 'bob@example.com', 34)
RETURNING id, created_at;
```

#### 4. Query Records
```sql
SELECT id, name, email, age, created_at 
FROM users 
WHERE age > 25 
ORDER BY id DESC;
```

#### 5. Update & Delete Data
```sql
UPDATE users SET age = 30 WHERE email = 'alice@example.com';

DELETE FROM users WHERE email = 'bob@example.com';
```

---

### 6. Non-Interactive CLI Execution & Formatting

Run SQL statements directly from terminal without entering interactive prompt:

#### 1. Run Single Query (`-c`)
```bash
psql -d myapp_db -c "SELECT COUNT(*) FROM users;"
```

#### 2. Export Query Results to CSV (`--csv` or `-A -F`)
```bash
psql -d myapp_db --csv -c "SELECT id, name, email FROM users;" > users_export.csv
```

#### 3. Unaligned / Raw Output for Shell Scripting (`-t -A`)
```bash
# -t hides headers/footers, -A removes column padding
USER_COUNT=$(psql -d myapp_db -t -A -c "SELECT COUNT(*) FROM users;")
echo "Total users registered: $USER_COUNT"
```

#### 4. Output Query to HTML Table (`-H`)
```bash
psql -d myapp_db -H -c "SELECT name, email FROM users;" > users.html
```

---

### 7. JSONB Data Manipulation

PostgreSQL features native, high-performance binary JSON (`JSONB`):

```sql
-- Create table with JSONB document column
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    attributes JSONB NOT NULL
);

-- Insert JSON document
INSERT INTO user_profiles (user_id, attributes) 
VALUES (1, '{"theme": "dark", "notifications": true, "skills": ["postgres", "sql"]}');

-- Query JSON fields using operator ->>
SELECT user_id, attributes->>'theme' AS theme 
FROM user_profiles 
WHERE attributes->>'theme' = 'dark';

-- Update specific JSON field using jsonb_set
UPDATE user_profiles 
SET attributes = jsonb_set(attributes, '{theme}', '"light"') 
WHERE user_id = 1;
```

---

### 8. Advanced SQL: CTEs & Window Functions

#### 1. Common Table Expressions (CTEs)
```sql
WITH high_earners AS (
    SELECT id, name, age 
    FROM users 
    WHERE age >= 30
)
SELECT name, age FROM high_earners ORDER BY age DESC;
```

#### 2. Window Functions (`ROW_NUMBER() OVER`)
```sql
SELECT name, age, 
       ROW_NUMBER() OVER (ORDER BY age DESC) AS age_rank
FROM users;
```

---

### 9. Performance Tuning & Query Profiling (`EXPLAIN ANALYZE`)

#### Inspect Execution Plan & Timing
```sql
EXPLAIN ANALYZE 
SELECT * FROM users WHERE email = 'alice@example.com';
```

#### Create Index Concurrently (Non-Blocking)
```sql
CREATE INDEX CONCURRENTLY idx_users_email ON users(email);
```

#### Check Index Usage & Size
```sql
SELECT relname AS table_name, pg_size_pretty(pg_relation_size(relid)) AS table_size 
FROM pg_stat_user_tables;
```

---

### 10. User Management & Security

#### Create User with Encrypted Password
```sql
CREATE USER app_user WITH ENCRYPTED PASSWORD 'SecurePassword123!';
```

#### Grant Privileges
```sql
GRANT ALL PRIVILEGES ON DATABASE myapp_db TO app_user;
GRANT ALL ON SCHEMA public TO app_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO app_user;
```

---

### 11. Database Monitoring & Session Management

#### View Active Connections
```sql
SELECT pid, usename, client_addr, state, query 
FROM pg_stat_activity 
WHERE state != 'idle';
```

#### Terminate Stuck or Long-Running Backend Process
```sql
-- Terminate query using process ID (pid)
SELECT pg_terminate_backend(12345);
```

---

### 12. Backups & Restores (`pg_dump` & `pg_restore`)

#### Export Plain SQL Text Dump
```bash
pg_dump -U postgres -d myapp_db > myapp_backup.sql
```

#### Restore Plain SQL Text Dump
```bash
psql -U postgres -d myapp_db < myapp_backup.sql
```

#### Export Custom Binary Archive (`-F c` for Compress & Fast Restore)
```bash
pg_dump -U postgres -F c myapp_db > myapp_backup.dump
```

#### Restore Custom Binary Dump (`pg_restore`)
```bash
pg_restore -U postgres -d myapp_db -v myapp_backup.dump
```

---

### 13. VSCode Integration & Script Automation

#### Execute SQL File via Terminal
```bash
psql -U postgres -d myapp_db -f setup.sql
```

---

### 14. Command Quick Reference

| Action | CLI Command |
| :--- | :--- |
| Start PostgreSQL | `brew services start postgresql@16` |
| Connect CLI | `psql postgres` |
| Show Databases | `\l` |
| Connect to Database | `\c myapp_db` |
| Show Tables | `\dt` |
| Describe Table | `\d tablename` |
| Export CSV | `psql -d myapp_db --csv -c "SELECT..." > out.csv` |
| Backup Database | `pg_dump -F c myapp_db > backup.dump` |
| Exit `psql` | `\q` |

---

🔗 [Official PostgreSQL Documentation](https://www.postgresql.org/docs/)