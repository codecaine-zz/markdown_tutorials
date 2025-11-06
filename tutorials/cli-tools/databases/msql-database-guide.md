# MySQL CLI Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial teaches you how to use the MySQL CLI (Command Line Interface) with best practices, even if you're not a programmer. We'll cover installation, basic commands, and practical examples.

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

### 2. Install MySQL Using Brew

```bash
# Install MySQL
brew install mysql
```

🔗 [MySQL Installation via Brew](https://formulae.brew.sh/formula/mysql)

---

### 3. Start MySQL Server

```bash
# Start MySQL service
brew services start mysql
```

To verify it's running:

```bash
# Check MySQL status
brew services list | grep mysql
```

---

### 4. Secure MySQL Installation

```bash
# Run security script (recommended)
mysql_secure_installation
```

Follow the prompts to set root password and security options.

🔗 [MySQL Installation Guide](https://dev.mysql.com/doc/refman/8.0/en/installing.html)

---

## 🚀 Using MySQL CLI

### 1. Connect to MySQL as Root

```bash
# Connect to MySQL
mysql -u root -p
```

Enter the password you set during installation.

You'll see a prompt like `mysql>` — that means you're connected!

🔗 [MySQL CLI Documentation](https://dev.mysql.com/doc/refman/8.0/en/mysql.html)

---

## 🔤 Basic Commands

### 1. Show Databases

```sql
-- Show all databases
SHOW DATABASES;
```

---

### 2. Create a Database

```sql
-- Create a new database
CREATE DATABASE myapp;
```

---

### 3. Use a Database

```sql
-- Switch to the database
USE myapp;
```

---

### 4. Create a Table

```sql
-- Create a users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    age INT,
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
+----+-------+-------------------+------+---------------------+
| id | name  | email             | age  | created_at          |
+----+-------+-------------------+------+---------------------+
|  1 | Alice | alice@example.com |   30 | 2023-01-01 12:00:00 |
+----+-------+-------------------+------+---------------------+
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
SHOW TABLES;
```

---

### 2. Describe Table Structure

```sql
-- Show table structure
DESCRIBE users;
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
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password123';
```

---

### 2. Grant Permissions

```sql
-- Grant all privileges on myapp database
GRANT ALL PRIVILEGES ON myapp.* TO 'newuser'@'localhost';
```

---

### 3. Apply Permissions

```sql
-- Refresh privileges
FLUSH PRIVILEGES;
```

---

### 4. Connect as New User

```bash
# Exit current session
EXIT;

# Connect as new user
mysql -u newuser -p
```

---

## 🧪 Best Practices

### 1. Use Prepared Statements (in applications)

```sql
-- Example of safe query (use in application code)
PREPARE stmt FROM 'SELECT * FROM users WHERE id = ?';
SET @id = 1;
EXECUTE stmt USING @id;
DEALLOCATE PREPARE stmt;
```

🔗 [MySQL Prepared Statements](https://dev.mysql.com/doc/refman/8.0/en/sql-prepared-statements.html)

---

### 2. Use Indexes for Performance

```sql
-- Create index on email column
CREATE INDEX idx_email ON users(email);
```

🔗 [MySQL Indexes](https://dev.mysql.com/doc/refman/8.0/en/optimization-indexes.html)

---

### 3. Backup Your Database

```bash
# Backup database (from terminal, not MySQL CLI)
mysqldump -u root -p myapp > myapp_backup.sql
```

---

### 4. Restore Database

```bash
# Restore database (from terminal)
mysql -u root -p myapp < myapp_backup.sql
```

---

## 🛠️ VSCode Setup Tips

### 1. Install MySQL Extension

In VSCode:
- Go to Extensions (`Cmd + Shift + X`)
- Search: **MySQL**
- Install **MySQL** by cweijan

---

### 2. Create a `.sql` Script File

Create a file named `setup.sql` and paste your commands:

```sql
-- setup.sql
USE myapp;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price) VALUES ('Laptop', 999.99);
SELECT * FROM products;
```

Run it from terminal:

```bash
mysql -u root -p < setup.sql
```

🔗 [VSCode MySQL Extension](https://marketplace.visualstudio.com/items?itemName=cweijan.vscode-mysql-client2)

---

## 🧹 Useful CLI Commands

### 1. Exit MySQL

```sql
-- Exit MySQL
EXIT;
```

Or:
```sql
QUIT;
```

---

### 2. Show Current Database

```sql
-- Show current database
SELECT DATABASE();
```

---

### 3. Show MySQL Version

```sql
-- Show version
SELECT VERSION();
```

---

### 4. Import SQL File

```bash
# Import SQL file (from terminal)
mysql -u root -p myapp < data.sql
```

---

## 📚 Further Reading

- [Official MySQL CLI Docs](https://dev.mysql.com/doc/refman/8.0/en/mysql.html)
- [MySQL SQL Statement Syntax](https://dev.mysql.com/doc/refman/8.0/en/sql-statements.html)
- [MySQL Security Best Practices](https://dev.mysql.com/doc/refman/8.0/en/security-guidelines.html)

---

## ✅ Summary

| Task | Command |
|------|---------|
| Start MySQL | `brew services start mysql` |
| Connect CLI | `mysql -u root -p` |
| Show Databases | `SHOW DATABASES;` |
| Create Database | `CREATE DATABASE myapp;` |
| Use Database | `USE myapp;` |
| Create Table | `CREATE TABLE ...` |
| Insert Data | `INSERT INTO ...` |
| Query Data | `SELECT * FROM ...` |
| Update Data | `UPDATE ... SET ...` |
| Delete Data | `DELETE FROM ...` |
| Show Tables | `SHOW TABLES;` |
| Exit CLI | `EXIT;` |

You're now ready to use MySQL CLI confidently! Practice these commands daily and build small projects to reinforce learning.