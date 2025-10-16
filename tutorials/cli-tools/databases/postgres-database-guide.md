# 📘 Official‑style **PostgreSQL** Guide for macOS (Homebrew — 2025 edition)

> **What’s new?**  
> The cheat‑sheet now includes the **full “CREATE / INSERT / SELECT / UPDATE / DELETE”** command set (direct links to the official SQL‑Commands reference) and a quick **psql meta‑command** reference.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1-prereqs)  
2. [Install PostgreSQL (brew)](#2-install)  
3. [Where Homebrew puts everything](#3-locations)  
4. [Start / Stop / Restart the server (brew services)](#4-service)  
5. [Connect with `psql` – interactive & one‑liners](#5-psql)  
6. **[SQL cheat‑sheet – CREATE / INSERT / SELECT / UPDATE / DELETE](#6-cheatsheet)** ← **copy‑paste ready**  
7. [Import / Export (`\copy`, `pg_dump`, `pg_restore`)](#7-import-export)  
8. [Persistence & backups (WAL, base‑backup, pgBackRest)](#8-backups)  
9. [Security – roles, passwords, `pg_hba.conf`](#9-security)  
10. [Maintenance – VACUUM, ANALYZE, REINDEX, pg_upgrade](#10-maintenance)  
11. [Common pitfalls & troubleshooting](#11-mistakes)  
12. [Uninstall / clean‑up](#12-uninstall)  

---  

<a name="1-prereqs"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version            # should print 4.x or later
# If you don’t have Homebrew yet → install it
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Homebrew is the **officially supported** way to install PostgreSQL on macOS 【1†L5-L9】.  

*Official docs*: <https://brew.sh>

---  

<a name="2-install"></a>
## 2️⃣ Install PostgreSQL (brew)

```bash
# Install the latest stable series (14 as of 2025)
brew install postgresql@14
# (or pick a newer series – 15/16/17 – see the formula list)
```

The formula page shows the install command and the current stable version 【15†L6-L13】.  

**Optional – add to PATH immediately** (recommended for the current shell):

```bash
echo 'export PATH="/usr/local/opt/postgresql@14/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

*Official reference*: <https://formulae.brew.sh/formula/postgresql@14>

---  

<a name="3-locations"></a>
## 3️⃣ Where Homebrew puts everything  

| Item | Path (Apple silicon) | Path (Intel) | How to retrieve |
|------|---------------------|--------------|-----------------|
| Binary (`psql`, `postgres`, `createdb`, …) | `/opt/homebrew/opt/postgresql@14/bin/` | `/usr/local/opt/postgresql@14/bin/` | `$(brew --prefix postgresql@14)/bin` |
| Data directory (cluster) | `$HOMEBREW_PREFIX/var/postgresql@14` | same | `$(brew --prefix)/var/postgresql@14` |
| Config files (`postgresql.conf`, `pg_hba.conf`) | `$HOMEBREW_PREFIX/var/postgresql@14` | same | |
| Homebrew Services plist | `$HOMEBREW_PREFIX/opt/postgresql@14/homebrew.mxcl.postgresql@14.plist` | same | |

Homebrew creates a **default database cluster** with `initdb` in the `var` directory 【5†L81-L85】.  

---  

<a name="4-service"></a>
## 4️⃣ Start / Stop / Restart the server (brew services)

| Action | Command | What you’ll see |
|--------|---------|-----------------|
| Start the server (runs as a `launchd` service) | `brew services start postgresql@14` | “Successfully started `postgresql@14`” |
| Stop the server | `brew services stop postgresql@14` | Service stopped cleanly |
| Restart (useful after `postgresql.conf` edits) | `brew services restart postgresql@14` | Stops then starts again |
| List status of all services | `brew services list` | Shows `postgresql@14` → `started` or `stopped` |
| Run manually (foreground, good for debugging) | `postgres -D $(brew --prefix)/var/postgresql@14` | Logs to console, stop with **Ctrl‑C** |

The DataCamp tutorial demonstrates these exact commands 【21†L118-L150】.  

---  

<a name="5-psql"></a>
## 5️⃣ Connect with `psql` – interactive & one‑liners  

```bash
# Connect to the default “postgres” DB as the current OS user
psql postgres
```

*One‑liner* (useful in scripts):

```bash
psql -d mydb -c "SELECT now();"
```

**Common psql meta‑commands** (all documented in the official psql manual 【17†L69-L84】):

| Meta‑command | What it does |
|--------------|--------------|
| `\l` | List all databases |
| `\c dbname` | Connect to another database |
| `\dt` | List tables (alias for `\d` with table filter) 【24†L2-L4】 |
| `\du` | List roles/users |
| `\dn` | List schemas |
| `\dt+` | Show tables with size & description |
| `\x` | Toggle expanded output (good for wide rows) |
| `\q` | Quit the REPL |

---  

<a name="6-cheatsheet"></a>
## 6️⃣ **SQL cheat‑sheet – CREATE / INSERT / SELECT / UPDATE / DELETE**  

> Every block is ready‑to‑copy‑paste.  The official description of each command lives in the PostgreSQL **SQL‑Commands** reference 【23†L69-L77】.

### 6.1️⃣ CREATE DATABASE / SCHEMA / TABLE  

```sql
-- Create a new database (run as a superuser)
CREATE DATABASE myapp;

-- Create a schema inside the current DB
CREATE SCHEMA IF NOT EXISTS inventory;

-- Create a table with an auto‑incrementing PK
CREATE TABLE inventory.products (
    id      SERIAL PRIMARY KEY,
    name    TEXT    NOT NULL,
    price   NUMERIC(10,2) NOT NULL,
    in_stock BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMPTZ DEFAULT now()
);
```

*Reference*: <https://www.postgresql.org/docs/current/sql-createtable.html>

### 6.2️⃣ INSERT  

```sql
INSERT INTO inventory.products (name, price, in_stock)
VALUES ('Widget', 19.99, TRUE),
       ('Gadget', 29.95, FALSE);
```

*Reference*: <https://www.postgresql.org/docs/current/sql-insert.html>

### 6.3️⃣ SELECT (basic reads)  

```sql
-- All rows
SELECT * FROM inventory.products;

-- Filter + ordering
SELECT id, name, price
FROM inventory.products
WHERE price > 20
ORDER BY price DESC
LIMIT 10;
```

*Reference*: <https://www.postgresql.org/docs/current/sql-select.html>

### 6.4️⃣ UPDATE  

```sql
UPDATE inventory.products
SET price = price * 1.10,        -- 10 % price increase
    in_stock = FALSE
WHERE name = 'Widget';
```

*Reference*: <https://www.postgresql.org/docs/current/sql-update.html>

### 6.5️⃣ DELETE  

```sql
DELETE FROM inventory.products
WHERE created_at < now() - interval '1 year';
```

*Reference*: <https://www.postgresql.org/docs/current/sql-delete.html>

### 6.6️⃣ One‑liner “set‑and‑get” example (via `psql -c`)  

```bash
psql -d myapp -c "INSERT INTO inventory.products (name,price) VALUES ('Demo',9.99) RETURNING id;"
```

The `RETURNING` clause gives you the newly‑generated primary key in a single round‑trip.

---  

<a name="7-import-export"></a>
## 7️⃣ Import / Export  

| Goal | Command | Docs |
|------|---------|------|
| **Export entire DB** (custom format) | `pg_dump -Fc -f myapp.dump myapp` | <https://www.postgresql.org/docs/current/app-pgdump.html> |
| **Restore** | `pg_restore -d myapp -C myapp.dump` | <https://www.postgresql.org/docs/current/app-pgrestore.html> |
| **SQL dump (plain text)** | `pg_dump -f myapp.sql myapp` | same |
| **Copy CSV into a table** (`\copy` works from psql) | `psql -d myapp -c "\copy inventory.products FROM 'products.csv' CSV HEADER"` | <https://www.postgresql.org/docs/current/app-psql.html#APP-PSQL-META-COMMANDS> |
| **Export query to CSV** | `psql -d myapp -c "\copy (SELECT * FROM inventory.products) TO 'out.csv' CSV HEADER"` | same |
| **Logical replication base‑backup** (pg_basebackup) | `pg_basebackup -D /tmp/backup -F tar -z -P -U replicator` | <https://www.postgresql.org/docs/current/app-pgbasebackup.html> |

---  

<a name="8-backups"></a>
## 8️⃣ Persistence & backups  

* PostgreSQL writes **WAL (Write‑Ahead Log)** files under `pg_wal/`. They are the foundation for point‑in‑time recovery (PITR).  
* **Base backup** – a copy of the data directory taken while WAL streaming is active (see `pg_basebackup` above).  
* **pgBackRest** or **Barman** are popular open‑source backup managers; they can be installed via Homebrew (`brew install pgbackrest`).  

*Official reference*: <https://www.postgresql.org/docs/current/continuous-archiving.html>

---  

<a name="9-security"></a>
## 9️⃣ Security – roles, passwords, `pg_hba.conf`  

| Task | Command / File edit | Official docs |
|------|--------------------|---------------|
| **Create a superuser** | `createuser --superuser myadmin` (run after service started) | <https://www.postgresql.org/docs/current/app-createuser.html> |
| **Create a role with password** | `CREATE ROLE web_user LOGIN PASSWORD 's3cr3t';` | <https://www.postgresql.org/docs/current/sql-createrole.html> |
| **Restrict connections** – edit `pg_hba.conf` (located in `$HOMEBREW_PREFIX/var/postgresql@14/pg_hba.conf`) | Add `host    all    all    127.0.0.1/32    md5` | <https://www.postgresql.org/docs/current/auth-pg-hba-conf.html> |
| **Force SSL** | In `postgresql.conf` set `ssl = on` and provide cert/key files | <https://www.postgresql.org/docs/current/ssl-tcp.html> |
| **Reload config** after changes | `pg_ctl reload -D $(brew --prefix)/var/postgresql@14` or `brew services restart postgresql@14` | <https://www.postgresql.org/docs/current/server-start.html> |

---  

<a name="10-maintenance"></a>
## 1️⃣0️⃣ Maintenance – VACUUM, ANALYZE, REINDEX, Upgrade  

```sql
-- Reclaim space & freeze old rows
VACUUM (FULL, VERBOSE);

-- Update planner statistics
ANALYZE;

-- Rebuild corrupted indexes (or after major changes)
REINDEX DATABASE myapp;
```

*Reference*: <https://www.postgresql.org/docs/current/sql-vacuum.html>  

**Version upgrade** (e.g., 14 → 15) – use `pg_upgrade` (binary‑compatible if data layout unchanged) or `pg_dumpall` & restore. Homebrew provides the `pg_upgrade` utility automatically when you install the new version.

---  

<a name="11-mistakes"></a>
## ⚠️ 1️⃣1️⃣ Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `psql: could not connect to server: No such file or directory` | Server not running (`brew services list`) | `brew services start postgresql@14` |
| “permission denied for relation X” | Role lacks `SELECT/INSERT` privileges | `GRANT SELECT,INSERT ON table X TO my_user;` |
| “FATAL: role … does not exist” | Role was never created (Homebrew creates only `postgres` role) | `createuser my_user` |
| “could not open file …: No such file or directory” during backup | Wrong data‑directory path – Homebrew stores under `var/postgresql@14` | Verify `PGDATA=$(brew --prefix)/var/postgresql@14` |
| “FATAL: SSL error: decryption failed” | Mismatched `ssl` settings in `postgresql.conf` and client | Ensure both sides have matching `sslmode` (e.g., `require`) |

*Sources*: Homebrew service docs 【21†L118-L150】, PostgreSQL official troubleshooting page <https://www.postgresql.org/docs/current/troubleshooting.html>.

---  

<a name="12-uninstall"></a>
## 1️⃣2️⃣ Uninstall / clean‑up  

```bash
brew uninstall postgresql@14               # removes the formula
brew services stop postgresql@14          # ensure the daemon is stopped
rm -rf $(brew --prefix)/var/postgresql@14   # delete the data cluster (optional)
```

If you want to keep the data for a later reinstall, **don’t delete** the `var/postgresql@14` directory – just stop the service.

*Official reference*: <https://docs.brew.sh/Manpage#uninstall>

---  

# 🎉 All set!  

You now have a **complete, copy‑paste‑ready reference** for installing, locating, running, querying, importing/exporting, backing up, securing and maintaining PostgreSQL on macOS via Homebrew, with every command linked to its official documentation. Happy data‑engineering! 🚀  