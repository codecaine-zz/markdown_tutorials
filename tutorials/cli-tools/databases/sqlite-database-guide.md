# SQLite CLI Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial teaches you how to use the SQLite CLI (Command Line Interface) with best practices, even if you're not a programmer. We'll cover installation, basic commands, and practical examples.

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

### 2. Install SQLite Using Brew

```bash
# Install SQLite
brew install sqlite
```

🔗 [SQLite Installation via Brew](https://formulae.brew.sh/formula/sqlite)

---

## 🚀 Using SQLite CLI

### 1. Create/Open a Database

```bash
# Create or open a database file
sqlite3 mydatabase.db
```

You'll see a prompt like `sqlite>` — that means you're connected!

🔗 [SQLite CLI Documentation](https://sqlite.org/cli.html)

---

## 🔤 Basic Commands

### 1. Show Help

```sql
-- Show help
.help
```

---

### 2. Create a Table

```sql
-- Create a users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE,
    age INTEGER
);
```

---

### 3. Insert Data

```sql
-- Insert a user
INSERT INTO users (name, email, age) VALUES ('Alice', 'alice@example.com', 30);
```

---

### 4. Query Data

```sql
-- Get all users
SELECT * FROM users;
```

Expected output:
```
1|Alice|alice@example.com|30
```

---

### 5. Update Data

```sql
-- Update Alice's age
UPDATE users SET age = 31 WHERE name = 'Alice';
```

---

### 6. Delete Data

```sql
-- Delete Alice
DELETE FROM users WHERE name = 'Alice';
```

---

## 📦 Working with Tables

### 1. Show Tables

```sql
-- List all tables
.tables
```

---

### 2. Describe Table Structure

```sql
-- Show table schema
.schema users
```

---

### 3. Add a New Column

```sql
-- Add a phone column
ALTER TABLE users ADD COLUMN phone TEXT;
```

---

### 4. Drop a Table

```sql
-- Delete the table
DROP TABLE users;
```

---

## 🧪 Best Practices

### 1. Use Transactions for Multiple Operations

```sql
-- Begin transaction
BEGIN;

-- Multiple operations
INSERT INTO users (name, email) VALUES ('Bob', 'bob@example.com');
UPDATE users SET age = 25 WHERE name = 'Bob';

-- Commit changes
COMMIT;
```

🔗 [SQLite Transactions](https://sqlite.org/lang_transaction.html)

---

### 2. Use Indexes for Faster Queries

```sql
-- Create index on email column
CREATE INDEX idx_email ON users(email);
```

🔗 [SQLite Indexes](https://sqlite.org/lang_createindex.html)

---

### 3. Backup Your Database

```bash
# Backup database
sqlite3 mydatabase.db .backup mydatabase_backup.db
```

---

## 🛠️ VSCode Setup Tips

### 1. Install SQLite Viewer Extension

In VSCode:
- Go to Extensions (`Cmd + Shift + X`)
- Search: **SQLite Viewer**
- Install it for visual database browsing

---

### 2. Create a `.sql` Script File

Create a file named `setup.sql` and paste your commands:

```sql
-- setup.sql
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    price REAL
);

INSERT INTO products (name, price) VALUES ('Laptop', 999.99);
SELECT * FROM products;
```

Run it from terminal:

```bash
sqlite3 mydatabase.db < setup.sql
```

🔗 [VSCode SQLite Extension](https://marketplace.visualstudio.com/items?itemName=qwtel.sqlite-viewer)

---

## 🧹 Useful CLI Commands

### 1. Exit SQLite

```sql
-- Exit SQLite
.quit
```

### 2. Show Headers in Output

```sql
-- Enable headers
.headers on

-- Enable column mode
.mode column

-- Now select shows nice table
SELECT * FROM users;
```

### 3. Export Data to CSV

```sql
-- Set output mode to CSV
.mode csv

-- Output to file
.output users.csv

-- Export data
SELECT * FROM users;

-- Reset output
.output stdout
```

---

## 📚 Further Reading

- [Official SQLite CLI Docs](https://sqlite.org/cli.html)
- [SQLite Language Reference](https://sqlite.org/lang.html)
- [SQLite Best Practices](https://sqlite.org/np1queryprob.html)

---

## ✅ Summary

| Task | Command |
|------|---------|
| Open Database | `sqlite3 mydatabase.db` |
| Create Table | `CREATE TABLE ...` |
| Insert Data | `INSERT INTO ...` |
| Query Data | `SELECT * FROM ...` |
| Update Data | `UPDATE ... SET ...` |
| Delete Data | `DELETE FROM ...` |
| Show Tables | `.tables` |
| Exit CLI | `.quit` |

You're now ready to use SQLite CLI confidently! Practice these commands daily and build small projects to reinforce learning.