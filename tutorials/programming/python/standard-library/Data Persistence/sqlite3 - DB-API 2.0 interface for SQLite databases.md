# sqlite3 - DB-API 2.0 interface for SQLite databases

The `sqlite3` module provides a lightweight, disk-based or in-memory database interface that conforms to the DB-API 2.0 specification. It allows Python programs to interact with SQLite databases using standard SQL.

Below are comprehensive, self-contained, and correct code examples demonstrating various database operations.

## Table of Contents

1. [Example 1: Basic Connection and CRUD Operations](#example-1-basic-connection-and-crud-operations)
2. [Example 2: Safe Parameterized Queries](#example-2-safe-parameterized-queries)
3. [Example 3: Using Connection and Transaction Context Managers](#example-3-using-connection-and-transaction-context-managers)
4. [Example 4: Database Joins and Aggregations](#example-4-database-joins-and-aggregations)
5. [Example 5: Robust Exception Handling and Resource Cleanup](#example-5-robust-exception-handling-and-resource-cleanup)

---

### Example 1: Basic Connection and CRUD Operations

This example demonstrates how to connect to an in-memory database (which resides in RAM and is deleted when the program exits), create a table, insert records, select records, update records, and delete records.

```python
import sqlite3

# Connect to an in-memory database (use a file path like 'my_database.db' for persistent storage)
conn = sqlite3.connect(':memory:')
cursor = conn.cursor()

# 1. Create a table
cursor.execute('''
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        age INTEGER
    )
''')

# 2. Insert records
cursor.execute("INSERT INTO users (name, age) VALUES ('Alice', 30)")
cursor.execute("INSERT INTO users (name, age) VALUES ('Bob', 25)")
conn.commit()  # Save changes to the database

# 3. Retrieve and print records
cursor.execute("SELECT id, name, age FROM users")
print("All Users:")
for row in cursor.fetchall():
    print(f"ID: {row[0]}, Name: {row[1]}, Age: {row[2]}")

# 4. Update a record
cursor.execute("UPDATE users SET age = ? WHERE name = ?", (31, 'Alice'))
conn.commit()

# 5. Delete a record
cursor.execute("DELETE FROM users WHERE name = ?", ('Bob',))
conn.commit()

# 6. Retrieve records again
cursor.execute("SELECT id, name, age FROM users")
print("\nUsers after Update & Delete:")
for row in cursor.fetchall():
    print(f"ID: {row[0]}, Name: {row[1]}, Age: {row[2]}")

# Clean up resources
cursor.close()
conn.close()
```

### Example 2: Safe Parameterized Queries

> [!WARNING]
> Never use string formatting (like `f"SELECT * FROM users WHERE name = '{user_input}'"`) to build SQL queries. This makes your application vulnerable to SQL injection attacks. Always use parameterized queries (with placeholders like `?` or named placeholders like `:name`).

```python
import sqlite3

conn = sqlite3.connect(':memory:')
cursor = conn.cursor()
cursor.execute("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, age INTEGER)")

# Position-based parameters (using ? placeholders and a tuple)
cursor.execute("INSERT INTO users (name, age) VALUES (?, ?)", ("Alice", 30))

# Named parameters (using :name placeholders and a dictionary)
cursor.execute(
    "INSERT INTO users (name, age) VALUES (:name, :age)",
    {"name": "Bob", "age": 25}
)
conn.commit()

# Querying using parameters
search_name = "Alice"
cursor.execute("SELECT * FROM users WHERE name = ?", (search_name,))
user = cursor.fetchone()
print("Search Result:", user)

cursor.close()
conn.close()
```

### Example 3: Using Connection and Transaction Context Managers

A `sqlite3` connection object can be used as a context manager. It automatically commits the transaction if no errors occur, or rolls back the transaction if an exception is raised inside the block.

> [!NOTE]
> The connection context manager handles **transactions** (commits/rollbacks), but does not automatically close the connection or cursors. You must still close the cursor and connection explicitly, or use `contextlib.closing`.

```python
import sqlite3
from contextlib import closing

# Use closing to ensure connection and cursor are closed when the block exits
with closing(sqlite3.connect(':memory:')) as conn:
    with conn:  # Transaction context manager
        # If an error happens here, the entire transaction is rolled back
        cursor = conn.cursor()
        cursor.execute("CREATE TABLE accounts (owner TEXT, balance INTEGER)")
        cursor.execute("INSERT INTO accounts VALUES ('Alice', 1000)")
        cursor.execute("INSERT INTO accounts VALUES ('Bob', 500)")
        
    # Start another transaction using conn
    with conn:
        cursor = conn.cursor()
        # Transfer money from Alice to Bob
        cursor.execute("UPDATE accounts SET balance = balance - 100 WHERE owner = 'Alice'")
        cursor.execute("UPDATE accounts SET balance = balance + 100 WHERE owner = 'Bob'")
        
    # Verify balances
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM accounts")
    print("Balances:", cursor.fetchall())
```

### Example 4: Database Joins and Aggregations

This example creates two tables (`users` and `orders`), establishes a foreign key relationship, and performs queries using SQL JOIN, GROUP BY, and aggregate functions (like `SUM` and `COUNT`).

```python
import sqlite3

conn = sqlite3.connect(':memory:')
cursor = conn.cursor()

# Enable foreign key support
cursor.execute("PRAGMA foreign_keys = ON")

# Create tables
cursor.execute('''
    CREATE TABLE users (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL
    )
''')
cursor.execute('''
    CREATE TABLE orders (
        order_id INTEGER PRIMARY KEY,
        user_id INTEGER,
        amount REAL,
        FOREIGN KEY (user_id) REFERENCES users (id)
    )
''')

# Insert sample data
cursor.executemany("INSERT INTO users VALUES (?, ?)", [(1, 'Alice'), (2, 'Bob')])
cursor.executemany("INSERT INTO orders VALUES (?, ?, ?)", [
    (101, 1, 99.50),
    (102, 1, 24.99),
    (103, 2, 150.00)
])
conn.commit()

# Perform an INNER JOIN and aggregate query
cursor.execute('''
    SELECT u.name, COUNT(o.order_id) AS total_orders, SUM(o.amount) AS total_spent
    FROM users u
    JOIN orders o ON u.id = o.user_id
    GROUP BY u.id
''')

print("Spending Summary:")
for row in cursor.fetchall():
    print(f"User: {row[0]} | Orders: {row[1]} | Total Spent: ${row[2]:.2f}")

cursor.close()
conn.close()
```

### Example 5: Robust Exception Handling and Resource Cleanup

This example demonstrates how to write bulletproof database interaction code that gracefully handles exceptions, logs errors, and guarantees that database resources are closed without raising secondary `NameErrors` (which occur if you attempt to call `close()` on variables that were never initialized).

```python
import sqlite3
import logging

logging.basicConfig(level=logging.INFO)

conn = None
cursor = None

try:
    # Attempt connection
    conn = sqlite3.connect(':memory:')
    cursor = conn.cursor()
    
    # Intentionally trigger an error by selecting from a nonexistent table
    cursor.execute("SELECT * FROM nonexistent_table")
    
except sqlite3.Error as e:
    logging.error(f"A SQLite error occurred: {e}")
except Exception as e:
    logging.error(f"An unexpected error occurred: {e}")
finally:
    # Safely close the cursor and connection only if they were initialized
    if cursor is not None:
        try:
            cursor.close()
        except sqlite3.Error as e:
            logging.error(f"Error closing cursor: {e}")
            
    if conn is not None:
        try:
            conn.close()
            logging.info("Database connection closed safely.")
        except sqlite3.Error as e:
            logging.error(f"Error closing connection: {e}")
```

These examples cover the fundamental aspects of interacting with SQLite databases using the standard library `sqlite3` module. They are fully functional and ready to be run.
