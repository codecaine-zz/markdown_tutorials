# Database Indexing & Query Tuning Guide: PostgreSQL, MySQL & SQLite

Database indexes are specialized data structures that enable the query engine to locate rows in $O(\log N)$ time rather than performing an exhaustive $O(N)$ sequential table scan. Proper indexing and execution plan inspection are the single most impactful performance levers in database engineering.

---

## 📚 Table of Contents

1. [Index Data Structures: B-Tree, Hash, GIN & BRIN](#1-index-data-structures-b-tree-hash-gin--brin)
2. [Composite Indexes & The Leftmost Prefix Rule](#2-composite-indexes--the-leftmost-prefix-rule)
3. [Covering Indexes & Index-Only Scans (`INCLUDE`)](#3-covering-indexes--index-only-scans-include)
4. [Partial & Expression-Based Functional Indexes](#4-partial--expression-based-functional-indexes)
5. [Reading Query Execution Plans: `EXPLAIN (ANALYZE, BUFFERS)`](#5-reading-query-execution-plans-explain-analyze-buffers)
6. [Common Query Anti-Patterns That Invalidate Indexes](#6-common-query-anti-patterns-that-invalidate-indexes)
7. [Postgres vs. MySQL vs. SQLite Tuning Cheatsheet](#7-postgres-vs-mysql-vs-sqlite-tuning-cheatsheet)

---

## 1. Index Data Structures: B-Tree, Hash, GIN & BRIN

| Index Type | Underlying Structure | Optimal For | Supported In |
| :--- | :--- | :--- | :--- |
| **B-Tree** (Default) | Self-balancing search tree | Equality (`=`), Range (`<`, `>`, `BETWEEN`), Sorting (`ORDER BY`) | Postgres, MySQL, SQLite |
| **Hash** | Bucket-based hash map | Exact equality only (`=`) | Postgres, MySQL (Memory) |
| **GIN** (Generalized Inverted) | Inverted index mapping elements to rows | Arrays, JSONB keys, Full-Text search, Tags | Postgres |
| **BRIN** (Block Range) | Min/max values per disk block range | Massive, append-only time-series tables (billions of rows) | Postgres |

### Creating Specialized Indexes in PostgreSQL

```sql
-- Standard B-Tree
CREATE INDEX idx_users_email ON users(email);

-- GIN Index for querying JSONB documents or array tags
CREATE INDEX idx_products_tags ON products USING GIN (tags);
CREATE INDEX idx_orders_metadata ON orders USING GIN (metadata jsonb_path_ops);

-- BRIN Index for append-only logs (takes 99% less disk space than B-tree)
CREATE INDEX idx_logs_timestamp ON access_logs USING BRIN (created_at);
```

---

## 2. Composite Indexes & The Leftmost Prefix Rule

A composite (multi-column) index orders records first by column 1, then by column 2, and so on.

```sql
CREATE INDEX idx_users_country_city_age ON users(country, city, age);
```

### The Leftmost Prefix Rule

The index can only accelerate queries that filter on a contiguous leading prefix of the indexed columns:

| Query Condition | Uses Index? | Explanation |
| :--- | :--- | :--- |
| `WHERE country = 'US'` | **Yes** | Uses 1st column |
| `WHERE country = 'US' AND city = 'NYC'` | **Yes** | Uses 1st & 2nd columns |
| `WHERE country = 'US' AND city = 'NYC' AND age > 21` | **Yes** | Uses all 3 columns |
| `WHERE city = 'NYC'` | **NO** | Skipped leftmost prefix (`country`) |
| `WHERE country = 'US' AND age > 21` | **Partial** | Uses `country` to filter; `age` is checked manually |

---

## 3. Covering Indexes & Index-Only Scans (`INCLUDE`)

In a standard index scan, the database visits the index to find matching row pointers, then performs a random disk seek to the table (the heap) to retrieve the rest of the columns.

An **Index-Only Scan** avoids touching the table completely by packing all requested columns into the index leaf nodes using `INCLUDE`:

```sql
-- Standard composite index (keys sorted in B-tree)
CREATE INDEX idx_users_lookup ON users(email) INCLUDE (first_name, last_name);
```

When running:

```sql
SELECT first_name, last_name FROM users WHERE email = 'alice@example.com';
```

The database satisfies the query directly from the index in cache without a single table heap fetch.

---

## 4. Partial & Expression-Based Functional Indexes

### Partial Indexes (Filtered)

If a table has 10,000,000 orders but only 5,000 are `status = 'pending'`, indexing all 10 million rows wastes RAM and slows down inserts.

```sql
-- Index only the active subset
CREATE INDEX idx_orders_pending ON orders(id, customer_id) 
WHERE status = 'pending';
```

This index is tiny, lives permanently in memory cache, and speeds up worker queue queries instantly.

### Expression-Based / Functional Indexes

Indexes are not used when functions mutate the column:

```sql
-- Query fails to use standard idx_users_email:
SELECT * FROM users WHERE LOWER(email) = 'user@example.com';

-- Solution: Create an index on the expression itself:
CREATE INDEX idx_users_lower_email ON users(LOWER(email));
```

---

## 5. Reading Query Execution Plans: `EXPLAIN (ANALYZE, BUFFERS)`

Always inspect query plans before and after adding indexes:

```sql
EXPLAIN (ANALYZE, BUFFERS, VERBOSE)
SELECT * FROM users WHERE age > 30 ORDER BY created_at DESC LIMIT 50;
```

### Understanding the Key Plan Nodes

1. **`Seq Scan` (Sequential Scan)**: Full table scan. Scans every disk block on the table. Acceptable for small tables (< 1,000 rows); catastrophic on multi-gigabyte tables.
2. **`Index Scan`**: Traverses the B-tree to find matching pointers, then fetches each row from table storage.
3. **`Bitmap Index Scan` + `Bitmap Heap Scan`**: Collects matching pointers in memory, sorts them by physical disk location, and reads heap pages sequentially. Great for multiple `OR` conditions.
4. **`Buffers: shared hit=42 read=0`**:
   - `hit`: Block was already in RAM buffer pool (microseconds).
   - `read`: Block had to be retrieved from physical SSD/disk (milliseconds).

---

## 6. Common Query Anti-Patterns That Invalidate Indexes

### Anti-Pattern 1: Leading Wildcard in `LIKE`

```sql
-- CANNOT use B-tree index (must scan everything):
SELECT * FROM products WHERE sku LIKE '%99';

-- CAN use B-tree index (index prefix search):
SELECT * FROM products WHERE sku LIKE 'PROD-99%';
```
*Fix for wildcards*: Use a PostgreSQL `pg_trgm` (trigram) GIN index.

### Anti-Pattern 2: Implicit Type Coercion

```sql
-- If phone_number is VARCHAR, but query passes integer:
SELECT * FROM customers WHERE phone_number = 123456789;
```
The database internally rewrites this to `WHERE CAST(phone_number AS INTEGER) = 123456789`, breaking index lookup!

### Anti-Pattern 3: Math / Operations on Column

```sql
-- Bad: Function on indexed column
SELECT * FROM subscriptions WHERE end_date - INTERVAL '7 days' < NOW();

-- Good: Move operations to the constant side
SELECT * FROM subscriptions WHERE end_date < NOW() + INTERVAL '7 days';
```

---

## 7. Postgres vs. MySQL vs. SQLite Tuning Cheatsheet

```sql
-- PostgreSQL: Find missing indexes by table scan counts
SELECT relname, seq_scan, seq_tup_read, idx_scan 
FROM pg_stat_user_tables 
WHERE seq_scan > 100 
ORDER BY seq_tup_read DESC;

-- PostgreSQL: Find unused indexes consuming disk space
SELECT schemaname, relname, indexrelname, idx_scan 
FROM pg_stat_user_indexes 
WHERE idx_scan = 0;

-- MySQL: Explain statement
EXPLAIN FORMAT=TREE SELECT * FROM users WHERE email = 'test@example.com';

-- SQLite: Explain query plan
EXPLAIN QUERY PLAN SELECT * FROM users WHERE email = 'test@example.com';
```
