# PostgreSQL CLI Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial teaches you how to use the PostgreSQL CLI (psql) with best practices, even if you're not a programmer. We'll cover installation, basic commands, and practical examples.

---

## 🧰 Prerequisites

### 1. Install Homebrew (if not installed)
Homebrew is a package manager for macOS that makes installing software easy.

```bash
# Install Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

🔗 [Homebrew Documentation](https://brew.sh/)

---

### 2. Install PostgreSQL Using Brew

```bash
# Install PostgreSQL
brew install postgresql
```

🔗 [PostgreSQL Installation via Brew](https://formulae.brew.sh/formula/postgresql)

---

### 3. Start PostgreSQL Server

```bash
# Start PostgreSQL service
brew services start postgresql
```

To verify it's running:

```bash
# Check PostgreSQL status
brew services list | grep postgresql
```

---

### 4. Create a Default Database for Your User

PostgreSQL automatically creates a database named after your system user. You can verify this:

```bash
# Connect to default database
psql postgres
```

You'll see a prompt like `postgres=#` — that means you're connected!

🔗 [PostgreSQL Documentation](https://www.postgresql.org/docs/)

---

## 🚀 Using PostgreSQL CLI (psql)

### 1. Connect to PostgreSQL

```bash
# Connect to PostgreSQL
psql postgres
```

No password is required by default for local development.

You'll see a prompt like `postgres=#` — that means you're connected!

🔗 [psql Documentation](https://www.postgresql.org/docs/current/app-psql.html)

---

## 🔤 Basic Commands

### 1. Show Databases

```sql
-- Show all databases
\l
```

Or:
```sql
-- Alternative SQL command
SELECT datname FROM pg_database;
```

---

### 2. Create a Database

```sql
-- Create a new database
CREATE DATABASE myapp;
```

---

### 3. Connect to a Database

```sql
-- Switch to the database
\c myapp
```

---

### 4. Create a Table

```sql
-- Create a users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    age INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 5. Insert Data

```sql
-- Insert a user
INSERT INTO users (name, email, age) VALUES ('Alice', 'alice@example.com', 30);
```

---

### 6. Query Data

```sql
-- Get all users
SELECT * FROM users;
```

Expected output:
```
 id | name  |       email       | age |         created_at         
----+-------+-------------------+-----+----------------------------
  1 | Alice | alice@example.com |  30 | 2023-01-01 12:00:00.000000
(1 row)
```

---

### 7. Update Data

```sql
-- Update Alice's age
UPDATE users SET age = 31 WHERE name = 'Alice';
```

---

### 8. Delete Data

```sql
-- Delete Alice
DELETE FROM users WHERE name = 'Alice';
```

---

## 📦 Working with Tables

### 1. Show Tables

```sql
-- List all tables
\dt
```

Or:
```sql
-- Alternative SQL command
SELECT tablename FROM pg_tables WHERE schemaname = 'public';
```

---

### 2. Describe Table Structure

```sql
-- Show table structure
\d users
```

Or:
```sql
-- Alternative SQL command
SELECT column_name, data_type, is_nullable 
FROM information_schema.columns 
WHERE table_name = 'users';
```

---

### 3. Add a New Column

```sql
-- Add a phone column
ALTER TABLE users ADD COLUMN phone VARCHAR(20);
```

---

### 4. Drop a Table

```sql
-- Delete the table
DROP TABLE users;
```

---

## 👤 User Management

### 1. Create a New User

```sql
-- Create a new user
CREATE USER newuser WITH PASSWORD 'password123';
```

---

### 2. Grant Permissions

```sql
-- Grant all privileges on myapp database
GRANT ALL PRIVILEGES ON DATABASE myapp TO newuser;
```

Note: You may also need to grant schema permissions:
```sql
GRANT ALL ON SCHEMA public TO newuser;
GRANT ALL ON ALL TABLES IN SCHEMA public TO newuser;
```

---

### 3. Connect as New User

```bash
# Exit current session
\q

# Connect as new user
psql -U newuser -d myapp -h localhost
```

You'll be prompted for the password.

---

## 🧪 Best Practices

### 1. Use Parameterized Queries (in applications)

In application code, always use parameterized queries to prevent SQL injection:

```sql
-- Example of safe query (use in application code)
PREPARE stmt AS SELECT * FROM users WHERE id = $1;
EXECUTE stmt(1);
DEALLOCATE stmt;
```

🔗 [PostgreSQL Prepared Statements](https://www.postgresql.org/docs/current/sql-prepare.html)

---

### 2. Use Indexes for Performance

```sql
-- Create index on email column
CREATE INDEX idx_email ON users(email);
```

🔗 [PostgreSQL Indexes](https://www.postgresql.org/docs/current/indexes.html)

---

### 3. Backup Your Database

```bash
# Backup database (from terminal, not psql CLI)
pg_dump -U your_username -W -F t myapp > myapp_backup.tar
```

---

### 4. Restore Database

```bash
# Restore database (from terminal)
pg_restore -U your_username -d myapp myapp_backup.tar
```

---

## 🛠️ VSCode Setup Tips

### 1. Install PostgreSQL Extension

In VSCode:
- Go to Extensions (`Cmd + Shift + X`)
- Search: **PostgreSQL**
- Install **PostgreSQL** by Chris Kolkman

---

### 2. Create a `.sql` Script File

Create a file named `setup.sql` and paste your commands:

```sql
-- setup.sql
\c myapp;

CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price) VALUES ('Laptop', 999.99);
SELECT * FROM products;
```

Run it from terminal:

```bash
psql -U your_username -f setup.sql
```

🔗 [VSCode PostgreSQL Extension](https://marketplace.visualstudio.com/items?itemName=ckolkman.vscode-postgres)

---

## 🧹 Useful CLI Commands

### 1. Exit psql

```sql
-- Exit psql
\q
```

---

### 2. Show Current Database

```sql
-- Show current database
SELECT current_database();
```

---

### 3. Show PostgreSQL Version

```sql
-- Show version
SELECT version();
```

---

### 4. Import SQL File

```bash
# Import SQL file (from terminal)
psql -U your_username -d myapp -f data.sql
```

---

## 📚 Further Reading

- [Official PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [PostgreSQL SQL Syntax](https://www.postgresql.org/docs/current/sql.html)
- [PostgreSQL Security Best Practices](https://www.postgresql.org/docs/current/runtime-config-connection.html)

---

## ✅ Summary

| Task | Command |
|------|---------|
| Start PostgreSQL | `brew services start postgresql` |
| Connect CLI | `psql postgres` |
| Show Databases | `\l` |
| Create Database | `CREATE DATABASE myapp;` |
| Connect to Database | `\c myapp` |
| Create Table | `CREATE TABLE ...` |
| Insert Data | `INSERT INTO ...` |
| Query Data | `SELECT * FROM ...` |
| Update Data | `UPDATE ... SET ...` |
| Delete Data | `DELETE FROM ...` |
| Show Tables | `\dt` |
| Exit CLI | `\q` |

You're now ready to use PostgreSQL CLI confidently! Practice these commands daily and build small projects to reinforce learning.