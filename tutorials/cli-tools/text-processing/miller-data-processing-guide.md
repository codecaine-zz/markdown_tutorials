# Miller (mlr) Data Processing Complete Guide

`miller` (`mlr`) is a high-performance, multi-format command-line data processing tool designed for name-indexed data. Often described as **`awk` + `sed` + `cut` + `join` + `sort` for CSV, TSV, JSON, and JSON Lines**, `miller` understands header rows, column names, and nested data structures out of the box—eliminating the brittle positional counting required by classic UNIX text tools.

---

## 📚 Table of Contents

1. [Overview & Supported Data Formats](#overview-supported-data-formats)
2. [Homebrew Installation & Binary Name (`mlr`)](#homebrew-installation-binary-name-mlr)
3. [Format Conversion (CSV to JSON, TSV, Markdown)](#format-conversion-csv-to-json-tsv-markdown)
4. [Essential Verbs (`cut`, `filter`, `sort`, `put`)](#essential-verbs-cut-filter-sort-put)
5. [Aggregations & Statistical Summaries (`stats1`)](#aggregations-statistical-summaries-stats1)
6. [Calculated Fields & Expressions with `put`](#calculated-fields-expressions-with-put)
7. [Relational Operations: Joining Datasets (`join`)](#relational-operations-joining-datasets-join)
8. [Working with JSON and JSON Lines (JSONL)](#working-with-json-and-json-lines-jsonl)
9. [Chaining Multiple Verbs (`then`)](#chaining-multiple-verbs-then)
10. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Supported Data Formats

While traditional UNIX tools like `cut -d, -f3` fail when columns change order or data contains embedded commas, `miller` addresses records by column name:

| Format Flag | Data Format | Common Use Case |
| :--- | :--- | :--- |
| `--icsv / --ocsv` | CSV (Comma-Separated) | Excel spreadsheets, database exports |
| `--itsv / --otsv` | TSV (Tab-Separated) | Bioinformatics, log streams |
| `--ijson / --ojson` | JSON Array / Map | REST APIs, config files |
| `--ijsonl / --ojsonl` | JSON Lines (NDJSON) | High-volume log pipelines |
| `--opprint` | Pretty-Printed Table | Terminal reading and alignment |
| `--omd` | GitHub Markdown Table | Documentation and reporting |

---

## ⚙️ Homebrew Installation & Binary Name (`mlr`)

### 1. Install via Homebrew

The formula is `miller`, but the command executable is **`mlr`**:

```bash
brew install miller
```

### 2. Verify Installation

```bash
which mlr
mlr --version
```

Output:
```text
/opt/homebrew/bin/mlr
mlr 6.21.0
```

---

## 🔄 Format Conversion (CSV to JSON, TSV, Markdown)

Convert effortlessly between tabular and structured formats:

### Sample CSV Dataset (`sales.csv`)

```csv
id,product,category,price,quantity
1,Laptop,Electronics,1200.00,4
2,Keyboard,Electronics,75.50,12
3,Desk,Furniture,250.00,2
4,Monitor,Electronics,350.00,5
```

### Conversions

```bash
# Convert CSV to pretty-printed terminal table
mlr --icsv --opprint cat sales.csv

# Convert CSV directly to Markdown table
mlr --icsv --omd cat sales.csv

# Convert CSV to JSON
mlr --icsv --ojson cat sales.csv

# Convert CSV to JSON Lines (JSONL)
mlr --icsv --ojsonl cat sales.csv
```

### Markdown Output Example:
```markdown
| id | product | category | price | quantity |
| --- | --- | --- | --- | --- |
| 1 | Laptop | Electronics | 1200.00 | 4 |
| 2 | Keyboard | Electronics | 75.50 | 12 |
| 3 | Desk | Furniture | 250.00 | 2 |
| 4 | Monitor | Electronics | 350.00 | 5 |
```

---

## ✂️ Essential Verbs (`cut`, `filter`, `sort`, `put`)

Miller uses named **verbs** to transform data streams:

### 1. Extract Specific Columns (`cut`)

```bash
# Keep only product and price
mlr --csv cut -f product,price sales.csv

# Exclude the id column
mlr --csv cut -x -f id sales.csv
```

### 2. Filter Rows by Condition (`filter`)

```bash
# Filter products where price is greater than 100
mlr --csv filter '$price > 100' sales.csv

# Filter by string match
mlr --csv filter '$category == "Electronics"' sales.csv
```

### 3. Sort Records (`sort`)

```bash
# Sort by price descending (-nr means numeric reverse)
mlr --csv sort -nr price sales.csv

# Multi-column sort: category alphabetical, price descending
mlr --csv sort -f category -nr price sales.csv
```

---

## 📊 Aggregations & Statistical Summaries (`stats1`)

Calculate counts, sums, averages, and standard deviations grouped by category:

```bash
mlr --csv stats1 -a count,sum,mean -f price -g category sales.csv
```

Output:
```text
category,price_count,price_sum,price_mean
Electronics,3,1625.500000,541.833333
Furniture,1,250.000000,250.000000
```

---

## 🧮 Calculated Fields & Expressions with `put`

Add computed columns using full arithmetic syntax:

```bash
# Calculate total line revenue (price * quantity)
mlr --csv put '$total = $price * $quantity' sales.csv
```

Output:
```csv
id,product,category,price,quantity,total
1,Laptop,Electronics,1200.00,4,4800
2,Keyboard,Electronics,75.50,12,906
3,Desk,Furniture,250.00,2,500
4,Monitor,Electronics,350.00,5,1750
```

---

## 🔗 Relational Operations: Joining Datasets (`join`)

Join two files together using a shared key column:

```bash
# Left join sales.csv with suppliers.csv on 'product'
mlr --csv join -u -j product -f sales.csv suppliers.csv
```

---

## 📦 Working with JSON and JSON Lines (JSONL)

`miller` seamlessly processes nested JSON documents:

```bash
# Flatten nested JSON objects into tabular CSV
mlr --ijson --ocsv unflatten sample.json

# Filter JSON Lines stream
cat logs.jsonl | mlr --jsonl filter '$status_code >= 500'
```

---

## ⛓️ Chaining Multiple Verbs (`then`)

One of Miller's greatest powers is chaining multiple data operations within a single process using `then`:

```bash
# 1. Filter category -> 2. Compute total -> 3. Sort by total -> 4. Pretty print
mlr --csv --opprint \
  filter '$category == "Electronics"' \
  then put '$total = $price * $quantity' \
  then sort -nr total \
  then cut -f product,quantity,total \
  sales.csv
```

Output:
```text
product  quantity total
Laptop   4        4800
Monitor  5        1750
Keyboard 12       906
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Recommended Aliases (`~/.zshrc`)

```bash
# Pretty-print any CSV in terminal
alias csv-view='mlr --icsv --opprint cat'

# Convert CSV to Markdown table
alias csv2md='mlr --icsv --omd cat'

# Convert CSV to JSON
alias csv2json='mlr --icsv --ojson cat'
```

### Quick Commands Table

| Task | Command |
| :--- | :--- |
| **Pretty-Print CSV** | `mlr --icsv --opprint cat file.csv` |
| **Filter Rows** | `mlr --csv filter '$age > 30' file.csv` |
| **Select Columns** | `mlr --csv cut -f name,email file.csv` |
| **Sort Descending** | `mlr --csv sort -nr price file.csv` |
| **Group Statistics**| `mlr --csv stats1 -a sum,mean -f amount -g dept file.csv` |
| **Compute New Field**| `mlr --csv put '$total = $qty * $price' file.csv` |
| **CSV to Markdown** | `mlr --icsv --omd cat file.csv` |
| **CSV to JSON** | `mlr --icsv --ojson cat file.csv` |

---

## 🗑️ Uninstallation

```bash
brew uninstall miller
```
