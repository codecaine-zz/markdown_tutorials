# DuckDB: In-Process Analytical SQL Database

## Table of Contents

1. [What is `duckdb`?](#1-what-is-duckdb)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Interactive CLI Usage](#4-basic-interactive-cli-usage)
5. [Querying CSV, Parquet & Multi-File Globs](#5-querying-csv-parquet--multi-file-globs)
6. [JSON & Nested Data Operations](#6-json--nested-data-operations)
7. [Terminal Scripting & Pipeline Integration](#7-terminal-scripting--pipeline-integration)
8. [Exporting & Format Conversion Recipes](#8-exporting--format-conversion-recipes)
9. [Extensions & External Database Attachment](#9-extensions--external-database-attachment)
10. [Query Profiling (`EXPLAIN ANALYZE`)](#10-query-profiling-explain-analyze)
11. [Persistent Database Files](#11-persistent-database-files)
12. [Uninstallation](#12-uninstallation)

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

### 5. Querying CSV, Parquet & Multi-File Globs

`duckdb` allows executing SQL directly on external data files without importing them into tables.

#### 1. Querying Single & Globbed CSV Files
```bash
# Query a single CSV file
duckdb -c "SELECT department, AVG(salary) AS avg_sal FROM 'employees.csv' GROUP BY department;"

# Query all CSV files matching a glob pattern
duckdb -c "SELECT * FROM read_csv_auto('logs/*.csv') WHERE status = 500;"
```

#### 2. Querying Parquet Files
```bash
duckdb -c "SELECT department, COUNT(*) FROM 'data.parquet' WHERE age > 30 GROUP BY department;"
```

#### 3. Querying Remote HTTP/S3 Files Directly
`duckdb` can fetch remote HTTP data directly:

```sql
SELECT count(*) FROM 'https://shell.duckdb.org/test/data/lineitem.parquet';
```

---

### 6. JSON & Nested Data Operations

`duckdb` natively parses complex, nested JSON objects and arrays:

#### 1. Querying JSON Files Directly
```bash
duckdb -c "SELECT * FROM read_json_auto('users.json');"
```

#### 2. Unnesting JSON Arrays & Structs
```sql
-- Unnest array elements into separate table rows
SELECT id, UNNEST(tags) AS tag FROM 'posts.json';

-- Extract nested fields from structs
SELECT user.name, user.address.city FROM 'profiles.json';
```

---

### 7. Terminal Scripting & Pipeline Integration

Integrate DuckDB into Unix shell pipelines:

#### 1. Standard Output Formatting Options (`-csv`, `-json`, `-markdown`)
```bash
# Output as Markdown Table
duckdb -markdown -c "SELECT name, salary FROM 'employees.csv';"

# Output as JSON Array
duckdb -json -c "SELECT name, salary FROM 'employees.csv';"
```

#### 2. Pipe Data into DuckDB (`/dev/stdin`)
```bash
cat data.json | duckdb -c "SELECT * FROM read_json_auto('/dev/stdin') WHERE active = true;"
```

---

### 8. Exporting & Format Conversion Recipes

`duckdb` makes file conversions between formats effortless:

#### 1. Convert CSV to Compressed Parquet
```bash
duckdb -c "COPY (SELECT * FROM 'employees.csv') TO 'output.parquet' (FORMAT PARQUET, COMPRESSION SNAPPY);"
```

#### 2. Export Query Results to JSON
```bash
duckdb -c "COPY (SELECT * FROM 'employees.csv' WHERE salary > 100000) TO 'high_earners.json' (ARRAY true);"
```

#### 3. Export Parquet to CSV
```bash
duckdb -c "COPY (SELECT * FROM 'data.parquet') TO 'data.csv' (HEADER, DELIMITER ',');"
```

---

### 9. Extensions & External Database Attachment

DuckDB can attach and query external SQLite and PostgreSQL databases directly:

#### 1. Install & Load Extensions
```sql
INSTALL postgres;
LOAD postgres;
```

#### 2. Attach External SQLite Database
```sql
INSTALL sqlite;
LOAD sqlite;
ATTACH 'existing_app.db' AS sqlite_db (TYPE SQLITE);

-- Query SQLite tables seamlessly inside DuckDB
SELECT * FROM sqlite_db.users LIMIT 10;
```

---

### 10. Query Profiling (`EXPLAIN ANALYZE`)

Inspect execution plans and memory/vector usage:

```bash
duckdb -c "EXPLAIN ANALYZE SELECT department, AVG(salary) FROM 'employees.csv' GROUP BY department;"
```

---

### 11. Persistent Database Files

To save tables to a persistent database file on disk, pass a file path argument when launching `duckdb`:

```bash
duckdb my_analytics.db
```

SQL statements executed inside this database session will persist across terminal restarts.

---

### 12. Uninstallation

```bash
brew uninstall duckdb
```
