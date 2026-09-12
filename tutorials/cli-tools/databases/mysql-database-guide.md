# MySQL: Relational Database Management System CLI Guide

## Table of Contents

1. [What is `mysql`?](#1-what-is-mysql)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Service Management (`brew services`)](#4-service-management-brew-services)
5. [Basic Connection & Security Setup](#5-basic-connection-security-setup)
6. [Non-Interactive CLI Execution & Scripting](#6-non-interactive-cli-execution-scripting)
7. [Database & Table Operations](#7-database-table-operations)
8. [CRUD SQL Examples & Output](#8-crud-sql-examples-output)
9. [Native JSON Data Operations](#9-native-json-data-operations)
10. [Query Performance & Process Management](#10-query-performance-process-management)
11. [User Management & Privileges](#11-user-management-privileges)
12. [Backups & Restores (`mysqldump`)](#12-backups-restores-mysqldump)
13. [Uninstallation](#13-uninstallation)

---

### 1. What is `mysql`?

`mysql` is the command-line client for MySQL, one of the world's most popular open-source relational database management systems (RDBMS). It allows developers and data administrators to create databases, manage tables, run SQL queries, tune index performance, and administer user privileges directly from the terminal.

#### Key Features
* **ACID-Compliant Transactions:** Full support for the InnoDB transactional engine.
* **Structured Data Organization:** Schema-enforced tables with primary/foreign key relationships.
* **CLI Utility Suite:** Includes `mysql`, `mysqldump`, and `mysqladmin`.

---

### 2. Prerequisites

Verify Homebrew on your Apple Silicon (ARM) Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install MySQL server and client using Homebrew:

```bash
brew install mysql
```

Verify binary path and version:

```bash
which mysql
mysql --version
```

**Expected Output:**
```text
/opt/homebrew/bin/mysql
mysql  Ver 8.4.x (or latest) for macos14 on arm64
```

---

### 4. Service Management (`brew services`)

On macOS, manage the MySQL background daemon (`mysqld`) using `brew services`:

#### Start MySQL Background Service
```bash
brew services start mysql
```

#### Check Service Status
```bash
brew services list | grep mysql
```

#### Stop or Restart MySQL Service
```bash
brew services stop mysql
brew services restart mysql
```

---

### 5. Basic Connection & Security Setup

#### Secure Initial Installation
Run the security configuration wizard to set a root password and remove test accounts:

```bash
mysql_secure_installation
```

#### Connect as Root User
```bash
mysql -u root -p
```

Type the root password when prompted. You will enter the interactive `mysql>` shell.

---

### 6. Non-Interactive CLI Execution & Scripting

Run SQL commands directly from terminal shell scripts without entering interactive mode:

#### 1. Run Single Query (`-e`)
```bash
mysql -u root -p -e "SHOW DATABASES;"
```

#### 2. Export Query to Tab-Separated / Batch Format (`--batch`)
```bash
mysql -u root -p --batch -e "SELECT username, email FROM myapp_db.users;" > users.tsv
```

#### 3. Export Query to HTML Table (`--html`)
```bash
mysql -u root -p --html -e "SELECT * FROM myapp_db.users;" > report.html
```

#### 4. Export Query to XML Format (`--xml`)
```bash
mysql -u root -p --xml -e "SELECT * FROM myapp_db.users;" > report.xml
```

#### 5. Execute SQL Script File
```bash
mysql -u root -p myapp_db < schema.sql
```

---

### 7. Database & Table Operations

Execute these commands inside the `mysql>` prompt:

#### 1. Show Existing Databases
```sql
SHOW DATABASES;
```

#### 2. Create and Select a Database
```sql
CREATE DATABASE myapp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE myapp_db;
```

#### 3. Create a Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    age INT DEFAULT 18,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

#### 4. Inspect Table Schema
```sql
DESCRIBE users;
```

---

### 8. CRUD SQL Examples & Output

#### Create (Insert Rows)
```sql
INSERT INTO users (username, email, age) 
VALUES ('alice_dev', 'alice@example.com', 28),
       ('bob_admin', 'bob@example.com', 34);
```

#### Read (Select Queries)
```sql
SELECT id, username, email, age, created_at 
FROM users 
WHERE age >= 25 
ORDER BY id ASC;
```

**Example Output:**
```text
+----+-----------+-------------------+-----+---------------------+
| id | username  | email             | age | created_at          |
+----+-----------+-------------------+-----+---------------------+
|  1 | alice_dev | alice@example.com |  28 | 2026-07-22 14:00:00 |
|  2 | bob_admin | bob@example.com   |  34 | 2026-07-22 14:00:00 |
+----+-----------+-------------------+-----+---------------------+
2 rows in set (0.00 sec)
```

#### Update Rows
```sql
UPDATE users SET age = 29 WHERE username = 'alice_dev';
```

#### Delete Rows
```sql
DELETE FROM users WHERE username = 'bob_admin';
```

---

### 9. Native JSON Data Operations

MySQL 8.0+ includes native JSON support:

```sql
-- Create table with JSON column
CREATE TABLE user_settings (
    user_id INT PRIMARY KEY,
    preferences JSON NOT NULL
);

-- Insert JSON object
INSERT INTO user_settings (user_id, preferences) 
VALUES (1, JSON_OBJECT('theme', 'dark', 'notifications', true));

-- Extract JSON values with inline operator ->>
SELECT user_id, preferences->>'$.theme' AS theme 
FROM user_settings 
WHERE preferences->>'$.theme' = 'dark';
```

---

### 10. Query Performance & Process Management

#### Inspect Execution Plan (`EXPLAIN`)
```sql
EXPLAIN FORMAT=TREE SELECT * FROM users WHERE email = 'alice@example.com';
```

#### Inspect Table Indexes
```sql
SHOW INDEX FROM users;
```

#### View Running Queries & Process List
```sql
SHOW PROCESSLIST;
```

#### Kill a Hung Connection / Query Thread
```sql
KILL 42;
```

---

### 11. User Management & Privileges

#### Create a New Database User
```sql
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
```

#### Grant Privileges on `myapp_db`
```sql
GRANT ALL PRIVILEGES ON myapp_db.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

#### View Granted User Privileges
```sql
SHOW GRANTS FOR 'app_user'@'localhost';
```

---

### 12. Backups & Restores (`mysqldump`)

Run these commands directly in your Mac terminal shell:

#### Export Database to SQL Dump File (Production-Safe Transactional Dump)
```bash
mysqldump -u root -p --single-transaction --routines --triggers myapp_db > myapp_backup.sql
```

#### Export All Databases
```bash
mysqldump -u root -p --all-databases > full_server_backup.sql
```

#### Restore Database from Dump File
```bash
mysql -u root -p myapp_db < myapp_backup.sql
```

---

### 13. Uninstallation

```bash
brew services stop mysql
brew uninstall mysql
rm -rf /opt/homebrew/var/mysql
```