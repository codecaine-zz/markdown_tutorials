# SQLite with Homebrew: Quick Guide

## Table of Contents

1. [Installation and Setup](#installation-and-setup-with-homebrew)
2. [`sqlite3` Command Examples](#sqlite3-command-examples)
  - [Create a Database & Interactive Shell](#1-create-a-database--enter-interactive-shell)
  - [Create a Table and Insert Data](#2-interactive-create-a-table-and-insert-data)
  - [Query Data and Exit](#3-interactive-query-data-and-exit)
  - [Non-Interactive Querying](#4-non-interactive-querying-for-scripts)
  - [Inspect Schema](#5-inspecting-a-database-schema)
  - [Import From File](#6-importing-data-from-a-file)

Note: macOS includes a version of SQLite by default, but the Homebrew version is typically more up-to-date. To ensure you're using the version you just installed, you may need to add it to your path.

### Installation and Setup with Homebrew

```sh
brew install sqlite
```

After installation, add Homebrew's `sqlite` to your path to ensure it's used instead of the system version. For `zsh` (the default on modern macOS):

```sh
# Add sqlite to your path
echo 'export PATH="/opt/homebrew/opt/sqlite/bin:$PATH"' >> ~/.zshrc

# Apply the changes to your current terminal session
source ~/.zshrc
```

-----

### `sqlite3` Command Examples

The examples below show how to create a database, define a table, add data, and run queries, both interactively and from the command line for scripting.

#### 1\. Create a Database & Enter Interactive Shell

This command creates a new database file (if it doesn't exist) and opens the SQLite interactive shell.

**Command:**

```sh
sqlite3 my_app.db
```

**Console Output:**
You are now inside the SQLite shell, ready to enter SQL commands.

```
SQLite version 3.45.2 2024-05-28 15:33:53
Enter ".help" for usage hints.
sqlite>
```

**Result:**
A new file, `my_app.db`, is created in your current directory.

#### 2\. Interactive: Create a Table and Insert Data

Inside the shell, you can execute standard SQL commands. SQL commands end with a semicolon (`;`).

**Commands (type these into the `sqlite>` prompt):**

```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT UNIQUE
);

INSERT INTO users (name, email) VALUES ('Alice', 'alice@example.com');
INSERT INTO users (name, email) VALUES ('Bob', 'bob@example.com');
```

**Result:**
A table named `users` is created, and two rows are inserted. The shell gives no output if a command is successful.

#### 3\. Interactive: Query Data and Exit

Use `SELECT` to retrieve data. You can also use special "dot-commands" (like `.headers on` or `.exit`) to control the shell itself.

**Commands (type these into the `sqlite>` prompt):**

```sql
.headers on
SELECT * FROM users;
.exit
```

**Console Output:**
The `.headers on` command adds a header row to the query output. `.exit` closes the shell and returns you to your terminal.

```
sqlite> .headers on
sqlite> SELECT * FROM users;
id|name|email
1|Alice|alice@example.com
2|Bob|bob@example.com
sqlite> .exit
```

#### 4\. Non-Interactive Querying (For Scripts)

You can pass an SQL query directly to `sqlite3` as an argument. This is perfect for use in shell scripts.

**Command:**

```sh
sqlite3 my_app.db "SELECT name FROM users WHERE id = 1;"
```

**Output:**

```
Alice
```

#### 5\. Inspecting a Database Schema

You can use dot-commands non-interactively to quickly inspect a database's structure.

**Command to list tables:**

```sh
sqlite3 my_app.db ".tables"
```

**Output:**

```
users
```

**Command to view a table's schema:**

```sh
sqlite3 my_app.db ".schema users"
```

**Output:**

```
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT UNIQUE
);
```

#### 6\. Importing Data From a File

You can execute a script of SQL commands from a file. First, create a file named `more_data.sql` with this content:

```sql
-- more_data.sql
INSERT INTO users (name, email) VALUES ('Charlie', 'charlie@example.com');
```

**Command:**

```sh
sqlite3 my_app.db < more_data.sql
```

**Result:**
The `INSERT` command from the file is executed. You can verify this by running a query:

```sh
sqlite3 my_app.db "SELECT * FROM users;"
```

**Output:**

```
1|Alice|alice@example.com
2|Bob|bob@example.com
3|Charlie|charlie@example.com
```