# MySQL: Relational Database Management System CLI Guide

## Table of Contents

1. [What is `mysql`?](#1-what-is-mysql)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Service Management (`brew services`)](#4-service-management-brew-services)
5. [Basic Connection & Security Setup](#5-basic-connection-security-setup)
6. [Database & Table Operations](#6-database-table-operations)
7. [CRUD SQL Examples & Output](#7-crud-sql-examples-output)
8. [User Management & Privileges](#8-user-management-privileges)
9. [Backups & Database Restores (`mysqldump`)](#9-backups-database-restores-mysqldump)
10. [Uninstallation](#10-uninstallation)

---

### 1. What is `mysql`?

`mysql` is the command-line client for MySQL, one of the world's most popular open-source relational database management systems (RDBMS). It allows developers and data administrators to create databases, manage tables, run SQL queries, tune index performance, and administer user privileges directly from the terminal.

#### Key Features
* **ACID-Compliant Transactions:** Full support for InnoDB transactional engine.
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

### 6. Database & Table Operations

Execute these commands inside the `mysql>` prompt:

#### 1. Show Existing Databases
```sql
SHOW DATABASES;
```

#### 2. Create and Select a Database
```sql
CREATE DATABASE myapp_db;
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
);
```

#### 4. Inspect Table Schema
```sql
DESCRIBE users;
```

---

### 7. CRUD SQL Examples & Output

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

### 8. User Management & Privileges

#### Create a New Database User
```sql
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
```

#### Grant Privileges on `myapp_db`
```sql
GRANT ALL PRIVILEGES ON myapp_db.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Connect with the New User (from terminal)
```bash
mysql -u app_user -p myapp_db
```

---

### 9. Backups & Database Restores (`mysqldump`)

Run these commands directly in your Mac terminal (not inside `mysql>` prompt):

#### Export Database to SQL Dump File
```bash
mysqldump -u root -p myapp_db > myapp_backup.sql
```

#### Restore Database from Dump File
```bash
mysql -u root -p myapp_db < myapp_backup.sql
```

---

### 10. Uninstallation

```bash
brew services stop mysql
brew uninstall mysql
rm -rf /opt/homebrew/var/mysql
```