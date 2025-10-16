# 📘 Official‑style **MySQL** Guide for macOS (Homebrew — 2025 edition)

> **What’s new?**  
> The cheat‑sheet below now lists **all five core DML statements** (CREATE, INSERT, SELECT, UPDATE, DELETE) with a single‑line copy‑paste version for each, linked to the official MySQL reference manual.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1‑prereqs)  
2. [Install MySQL (brew)](#2‑install)  
3. [Where Homebrew puts MySQL](#3‑locations)  
4. [Start / Stop / Restart the server (brew services)](#4‑service)  
5. [Connect with `mysql` – interactive & one‑liners](#5‑client)  
6. **[SQL cheat‑sheet – CREATE / INSERT / SELECT / UPDATE / DELETE](#6‑cheatsheet)** ← **copy‑paste ready**  
7. [Import & export (`mysqldump`, `mysqlimport`)](#7‑import‑export)  
8. [Persistence & backups (binary logs, logical dump, MySQL Shell dump)](#8‑backups)  
9. [Security – root password, users/roles, `my.cnf` & `pg_hba`‑style auth](#9‑security)  
10. [Maintenance – `OPTIMIZE TABLE`, `ANALYZE TABLE`, `CHECK TABLE`, `mysql_upgrade`](#10‑maintenance)  
11. [Common pitfalls & troubleshooting](#11‑mistakes)  
12. [Uninstall / clean‑up](#12‑uninstall)  

---  

<a name="1‑prereqs"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version           # should print 4.x or later
# If missing → install it (official script)
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Homebrew is the **officially supported** way to install MySQL on macOS 【1†L5-L9】.  

*Official reference*: <https://brew.sh>  

---  

<a name="2‑install"></a>
## 2️⃣ Install MySQL (brew)  

```bash
# MySQL 8.0 is the current stable series
brew install mysql@8.0
```

The formula page shows the exact install command and the current stable version 【15†L6-L13】.  

*Official formula*: <https://formulae.brew.sh/formula/mysql@8.0>  

---  

<a name="3‑locations"></a>
## 3️⃣ Where Homebrew puts MySQL  

| Item | Apple silicon (`/opt/homebrew`) | Intel (`/usr/local`) | How to obtain |
|------|--------------------------------|----------------------|----------------|
| Binaries (`mysql`, `mysqld`, `mysqladmin`, …) | `/opt/homebrew/opt/mysql@8.0/bin/` | `/usr/local/opt/mysql@8.0/bin/` | `$(brew --prefix mysql@8.0)/bin` |
| Data directory (default cluster) | `$HOMEBREW_PREFIX/var/mysql@8.0` | same | `$(brew --prefix)/var/mysql@8.0` |
| Config file (`my.cnf`) | `$HOMEBREW_PREFIX/etc/my.cnf` | same | |
| Launch‑d plist (service) | `$HOMEBREW_PREFIX/opt/mysql@8.0/homebrew.mxcl.mysql@8.0.plist` | same | |

Homebrew creates a **default data directory** when the formula is linked 【15†L81-L85】.  

---  

<a name="4‑service"></a>
## 4️⃣ Start / Stop / Restart the server (brew services)

| Action | Command | Expected output |
|--------|---------|-----------------|
| **Start** (launchd) | `brew services start mysql@8.0` | “Successfully started `mysql@8.0`” |
| **Stop** | `brew services stop mysql@8.0` | Clean shutdown |
| **Restart** | `brew services restart mysql@8.0` | Stop → start |
| **Status** | `brew services list` | Shows `mysql@8.0` → `started` or `stopped` |
| **Run manually (foreground)** | `mysqld_safe --datadir=$(brew --prefix)/var/mysql@8.0` | Logs to console, abort with **Ctrl‑C** |

These exact commands are demonstrated in the DataCamp tutorial 【21†L118-L150】.  

---  

<a name="5‑client"></a>
## 5️⃣ Connect with `mysql` – interactive & one‑liners  

```bash
# Connect as root (default password is empty until you run mysql_secure_installation)
mysql -u root

# Connect to a specific database
mysql -u root -p mydb   # -p forces password prompt
```

**Useful meta‑commands** (type `\?` inside `mysql>` to list them). Official docs list them 【17†L69-L84】:

| Meta‑command | What it does |
|--------------|--------------|
| `\h` or `help` | show help |
| `\q` | quit |
| `\c` | clear current query |
| `\t` | toggle table output format |
| `\G` | display result vertically |
| `\s` | show session status |
| `\u db_name` | switch database |
| `\R` | re‑read the `my.cnf` file |
| `\! cmd` | run a shell command |

---  

<a name="6‑cheatsheet"></a>
## 6️⃣ **SQL cheat‑sheet – CREATE / INSERT / SELECT / UPDATE / DELETE**  

> Every block is ready‑to‑copy‑paste.  The syntax follows the MySQL Reference Manual (see links).  

### 6.1 CREATE DATABASE / SCHEMA / TABLE  

```sql
-- Create a new database
CREATE DATABASE IF NOT EXISTS shop;

-- Create a schema (same as database) – optional
CREATE SCHEMA IF NOT EXISTS inventory;

-- Table with an auto‑increment primary key
CREATE TABLE inventory.products (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(255) NOT NULL,
    price   DECIMAL(10,2) NOT NULL,
    stock   INT DEFAULT 0,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/creating-tables.html> 【19†L60-L70】  

### 6.2 INSERT  

```sql
INSERT INTO inventory.products (name,price,stock)
VALUES ('Widget', 19.99, 100),
       ('Gadget', 29.95,  50);
```

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/insert.html> 【21†L47-L55】  

### 6.3 SELECT  

```sql
-- All rows
SELECT * FROM inventory.products;

-- Filtered, ordered, limited
SELECT id, name, price
FROM inventory.products
WHERE price > 20
ORDER BY price DESC
LIMIT 10;
```

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/select.html> 【23†L60-L84】  

### 6.4 UPDATE  

```sql
UPDATE inventory.products
SET stock = stock + 10,
    price = price * 1.05
WHERE name = 'Widget';
```

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/update.html> 【25†L60-L78】  

### 6.5 DELETE  

```sql
DELETE FROM inventory.products
WHERE created < NOW() - INTERVAL 1 YEAR;
```

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/delete.html> 【27†L71-L78】  

### 6.6 One‑liner from the shell (INSERT + RETURNING‑style)

```bash
mysql -u root -e "INSERT INTO inventory.products (name,price) VALUES ('Demo',9.99);
               SELECT LAST_INSERT_ID();"
```

MySQL doesn’t have a true `RETURNING` clause (available from 8.0.19 with `RETURNING` for DML – see the manual for details).  

---  

<a name="7‑import‑export"></a>
## 7️⃣ Import & export  

| Goal | Command | Official docs |
|------|---------|---------------|
| **Logical dump** (SQL file) | `mysqldump -u root -p shop > shop.sql` | <https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html> 【29†L9-L15】 |
| **Restore dump** | `mysql -u root -p < shop.sql` | same |
| **Import CSV/TSV quickly** | `mysqlimport -u root --local --fields-terminated-by=',' shop /path/to/products.csv` | <https://dev.mysql.com/doc/refman/8.0/en/mysqlimport.html> 【31†L45-L53】 |
| **Parallel dump with MySQL Shell** | `mysqlsh --uri root@localhost --sql -e "util.dumpInstance('dumpDir')"` | MySQL Shell docs (official) |
| **Export query to CSV** | `mysql -u root -e "SELECT * FROM inventory.products INTO OUTFILE '/tmp/products.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n';"` | <https://dev.mysql.com/doc/refman/8.0/en/select-into.html> |

---  

<a name="8‑backups"></a>
## 8️⃣ Persistence & backups  

* **Binary logs** (`mysql-bin.*`) – enable with `log-bin` in `my.cnf` for point‑in‑time recovery (PITR).  
* **Physical backup** – `mysqlbackup` (Enterprise) or open‑source `xtrabackup`.  
* **Logical backup** – `mysqldump` (see §7).  
* **MySQL Shell dump utilities** – `util.dumpInstance()` for fast, parallel, and cloud‑ready dumps (see MySQL Shell docs).  

*Reference*: <https://dev.mysql.com/doc/refman/8.0/en/backup-methods.html>  

---  

<a name="9‑security"></a>
## 9️⃣ Security  

| Task | Command / File | Official guide |
|------|----------------|----------------|
| **Secure installation** (root password, remove test DB) | `mysql_secure_installation` (interactive) | <https://dev.mysql.com/doc/refman/8.0/en/mysql-secure-installation.html> |
| **Create a new user** | `CREATE USER 'app'@'localhost' IDENTIFIED BY 'StrongPass!';` | <https://dev.mysql.com/doc/refman/8.0/en/create-user.html> |
| **Grant privileges** | `GRANT SELECT,INSERT,UPDATE,DELETE ON shop.* TO 'app'@'localhost';` | <https://dev.mysql.com/doc/refman/8.0/en/grant.html> |
| **Configure auth plugins** (e.g., `caching_sha2_password`) | Set in `my.cnf` under `[mysqld]` → `default_authentication_plugin=caching_sha2_password` | <https://dev.mysql.com/doc/refman/8.0/en/pluggable-authentication.html> |
| **Restrict network access** | In `my.cnf` bind to `127.0.0.1` or use a firewall (`pfctl`). | <https://dev.mysql.com/doc/refman/8.0/en/server-options.html#option_mysqld_bind-address> |
| **Reload config without restart** | `mysqladmin reload -u root -p` | <https://dev.mysql.com/doc/refman/8.0/en/mysqladmin.html#option_mysqladmin_reload> |

---  

<a name="10‑maintenance"></a>
## 1️⃣0️⃣ Maintenance  

| Operation | Command | When to use | Docs |
|----------|---------|------------|------|
| **Optimize / defragment tables** | `OPTIMIZE TABLE inventory.products;` | After massive deletes / updates | <https://dev.mysql.com/doc/refman/8.0/en/optimize-table.html> |
| **Analyze statistics** | `ANALYZE TABLE inventory.products;` | After bulk loads | <https://dev.mysql.com/doc/refman/8.0/en/analyze-table.html> |
| **Check integrity** | `CHECK TABLE inventory.products;` | Before backups | <https://dev.mysql.com/doc/refman/8.0/en/check-table.html> |
| **Upgrade to new major version** | `brew upgrade mysql@8.0` then `mysql_upgrade -u root -p` | After Homebrew version bump | <https://dev.mysql.com/doc/refman/8.0/en/mysql-upgrade.html> |
| **Flush logs / rotate binary log** | `PURGE BINARY LOGS BEFORE DATE_SUB(NOW(), INTERVAL 7 DAY);` | Keep log size bounded | <https://dev.mysql.com/doc/refman/8.0/en/flush.html> |

---  

<a name="11‑mistakes"></a>
## ⚠️ 1️⃣1️⃣ Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `Can't connect to MySQL server on '127.0.0.1' (61)` | Server not started (`brew services list`) | `brew services start mysql@8.0` |
| `ERROR 1045 (28000): Access denied` | Root password not set or wrong | Run `mysql_secure_installation` or reset via `ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpwd';` |
| `mysqld: InnoDB: Unable to lock ./ibdata1` | Another MySQL instance using the same data directory | Stop the other instance or adjust `datadir` |
| `mysqldump: Got error: 1049: Unknown database 'mydb'` | Wrong database name / case‑sensitivity on macOS (filesystem is case‑insensitive by default but MySQL treats names as case‑sensitive on Linux) | Verify spelling, or set `lower_case_table_names=1` in `my.cnf` |
| `binary log is disabled` when you try PITR | `log-bin` not enabled in `my.cnf` | Add `log-bin=mysql-bin` and restart service |

*General troubleshooting*: <https://dev.mysql.com/doc/refman/8.0/en/troubleshooting.html>  

---  

<a name="12‑uninstall"></a>
## 1️⃣2️⃣ Uninstall / clean‑up  

```bash
# Stop the service first
brew services stop mysql@8.0

# Remove the formula
brew uninstall mysql@8.0

# Optional: delete the data directory (careful!)
rm -rf $(brew --prefix)/var/mysql@8.0
```

If you later want to reinstall, simply run the install command again – the data directory will be re‑initialized.

*Official uninstall docs*: <https://docs.brew.sh/Manpage#uninstall>  

---  

# 🎉 All set!  

You now have a **complete, copy‑paste‑ready reference** for installing MySQL via Homebrew on macOS, locating its files, managing the daemon, using the `mysql` client, a full DML cheat‑sheet, import/export, backup strategies, security hardening, routine maintenance and common troubleshooting steps – each linked to the official MySQL documentation. Happy querying! 🚀  