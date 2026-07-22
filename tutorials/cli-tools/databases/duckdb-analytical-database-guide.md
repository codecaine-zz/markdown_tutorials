# DuckDB: In-Process Analytical SQL Database

## Table of Contents

1. [What is `duckdb`?](#1-what-is-duckdb)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Interactive CLI Usage](#4-basic-interactive-cli-usage)
5. [Querying CSV, Parquet & JSON Directly](#5-querying-csv-parquet-json-directly)
6. [Exporting & Transforming Data](#6-exporting-transforming-data)
7. [Persistent Database Files](#7-persistent-database-files)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `duckdb`?

`duckdb` is an in-process, high-performance analytical SQL database engine often called "the SQLite for data analytics/OLAP". Designed for fast column-oriented queries, zero-dependency embedded execution, and vectorization, `duckdb` allows data engineers and developers to run complex SQL queries directly against CSV, Parquet, and JSON files without setting up a database server.

#### Key Features
* **Columnar Vectorized Execution Engine:** Blazingly fast aggregations on millions of rows.
* **Direct File Querying:** Query Parquet, CSV, and JSON files instantly without prior import.
* **Zero Configuration:** Single standalone executable binary.

---

### 2. Prerequisites

Verify Homebrew on your ARM Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `duckdb` via Homebrew:

```bash
brew install duckdb
```

Verify binary and version:

```bash
which duckdb
duckdb --version
```

**Expected Output:**
```text
/opt/homebrew/bin/duckdb
v1.x.x
```

---

### 4. Basic Interactive CLI Usage

Launch the `duckdb` interactive shell in-memory:

```bash
duckdb
```

#### Running In-Memory SQL Queries
Inside the prompt:

```sql
CREATE TABLE users (id INTEGER, name VARCHAR, age INTEGER);
INSERT INTO users VALUES (1, 'Alice', 29), (2, 'Bob', 35), (3, 'Charlie', 22);

SELECT age, COUNT(*) AS count 
FROM users 
GROUP BY age 
ORDER BY age DESC;
```

**Output:**
```text
┌───────┬───────┐
│  age  │ count │
│ int32 │ int64 │
├───────┼───────┤
│    35 │     1 │
│    29 │     1 │
│    22 │     1 │
└───────┴───────┘
```

Exit the shell: `.exit` or `Ctrl+D`.

---

### 5. Querying CSV, Parquet & JSON Directly

`duckdb` allows executing SQL directly on external data files without importing them into tables.

#### Example 1: Querying a CSV File
Create a sample CSV file:

```bash
echo "name,department,salary" > employees.csv
echo "Alice,Engineering,120000" >> employees.csv
echo "Bob,Marketing,85000" >> employees.csv
echo "Charlie,Engineering,135000" >> employees.csv
```

Run SQL directly from terminal command line:

```bash
duckdb -c "SELECT department, AVG(salary) AS avg_salary FROM 'employees.csv' GROUP BY department;"
```

**Output:**
```text
┌─────────────┬────────────┐
│ department  │ avg_salary │
│   varchar   │   double   │
├─────────────┼────────────┤
│ Engineering │   127500.0 │
│ Marketing   │    85000.0 │
└─────────────┴────────────┘
```

#### Example 2: Querying Parquet Files
```bash
duckdb -c "SELECT * FROM 'data.parquet' WHERE age > 30 LIMIT 5;"
```

#### Example 3: Querying Remote HTTP/S3 Parquet Files
`duckdb` can fetch remote HTTP data directly:

```sql
SELECT count(*) FROM 'https://shell.duckdb.org/test/data/lineitem.parquet';
```

---

### 6. Exporting & Transforming Data

`duckdb` makes file conversions between formats effortless:

#### Convert CSV to Compressed Parquet File
```bash
duckdb -c "COPY (SELECT * FROM 'employees.csv') TO 'output.parquet' (FORMAT PARQUET, COMPRESSION SNAPPY);"
```

#### Export Query Results to JSON
```bash
duckdb -c "COPY (SELECT * FROM 'employees.csv' WHERE salary > 100000) TO 'high_earners.json' (ARRAY true);"
```

---

### 7. Persistent Database Files

To save tables to a persistent database file on disk, pass a file path argument when launching `duckdb`:

```bash
duckdb my_analytics.db
```

SQL statements executed inside this database session will persist across terminal restarts.

---

### 8. Uninstallation

```bash
brew uninstall duckdb
```
