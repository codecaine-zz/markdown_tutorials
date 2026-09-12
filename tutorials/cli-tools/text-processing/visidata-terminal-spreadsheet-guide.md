# VisiData (vd) — Interactive Terminal Spreadsheet & Data Multitool Guide

A complete, production-grade guide to **VisiData** (`vd`), the terminal spreadsheet and exploratory data analysis powerhouse capable of loading, browsing, sorting, filtering, aggregating, pivoting, and transforming million-row datasets across **CSV**, **TSV**, **JSON**, **SQLite**, **Parquet**, and **Excel** files in milliseconds.

---

## 📑 Table of Contents

- [1. Overview & The VisiData Philosophy](#1-overview-the-visidata-philosophy)
  - [Terminal Speed vs GUI Spreadsheets (Excel / Pandas)](#terminal-speed-vs-gui-spreadsheets-excel-pandas)
  - [Zero-Delay Lazy Loading for Million-Row Datasets](#zero-delay-lazy-loading-for-million-row-datasets)
- [2. Installation & Verification](#2-installation-verification)
  - [Installing via Homebrew (macOS & Linux)](#installing-via-homebrew-macos-linux)
  - [Installing via pipx or pip (Python)](#installing-via-pipx-or-pip-python)
  - [Verifying Binary Installation](#verifying-binary-installation)
- [3. Supported Formats & Opening Data](#3-supported-formats-opening-data)
  - [Opening CSV, TSV & Delimited Text](#opening-csv-tsv-delimited-text)
  - [Inspecting SQLite Databases & Tables](#inspecting-sqlite-databases-tables)
  - [Loading JSON, JSONL & Parquet Files](#loading-json-jsonl-parquet-files)
- [4. Core Navigation & Sheet Management](#4-core-navigation-sheet-management)
  - [Vim Movement & Column Resizing](#vim-movement-column-resizing)
  - [The Sheets Sheet (Shift+S)](#the-sheets-sheet-shifts)
  - [The Columns Sheet (Shift+C)](#the-columns-sheet-shiftc)
- [5. Sorting, Filtering & Type Inference](#5-sorting-filtering-type-inference)
  - [Setting Data Types (#, %, $, @, ~)](#setting-data-types)
  - [Sorting Columns in Ascending & Descending Order ([, ])](#sorting-columns-in-ascending--descending-order--)
  - [Filtering Rows with Regular Expressions (", ,)](#filtering-rows-with-regular-expressions)
- [6. Data Aggregation, Frequency Tables & Pivots](#6-data-aggregation-frequency-tables-pivots)
  - [Instant Frequency Analysis (Shift+F)](#instant-frequency-analysis-shiftf)
  - [Statistical Summaries (Sum, Mean, Min, Max)](#statistical-summaries-sum-mean-min-max)
  - [Multi-Dimensional Pivot Tables](#multi-dimensional-pivot-tables)
- [7. Transforming, Editing & Exporting Data](#7-transforming-editing-exporting-data)
  - [Adding Computed Columns with Python Expressions (=)](#adding-computed-columns-with-python-expressions)
  - [Saving & Exporting Cleaned Files (Ctrl+S)](#saving-exporting-cleaned-files-ctrls)
- [8. Practical Real-World Workflows](#8-practical-real-world-workflows)
  - [Workflow 1: Auditing a 5-Million Row Web Server Access Log](#workflow-1-auditing-a-5-million-row-web-server-access-log)
  - [Workflow 2: Joining Two Datasets on a Common Key](#workflow-2-joining-two-datasets-on-a-common-key)
  - [Workflow 3: Exploring an SQLite Database File Interactively](#workflow-3-exploring-an-sqlite-database-file-interactively)
- [9. Quick Reference Cheat Sheet & FAQ](#9-quick-reference-cheat-sheet-faq)
  - [Keybindings Summary Table](#keybindings-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & The VisiData Philosophy

### Terminal Speed vs GUI Spreadsheets (Excel / Pandas)

Opening a 2 GB CSV or JSONL file in Microsoft Excel or Google Sheets frequently causes memory exhaustion, freezing, or arbitrary row truncations (Excel limits files to 1,048,576 rows). While Jupyter notebooks and Pandas can handle large files, writing boilerplate code just to inspect column names, distinct counts, or distributions slows down exploratory workflows.

**VisiData (`vd`)** provides an instant, interactive terminal TUI:
- **Instant Launch**: Starts in under 50ms, rendering the first screen of data immediately while lazy-loading remaining rows asynchronously in the background.
- **Keyboard-Driven Velocity**: Pure Vim-inspired ergonomics (`h`, `j`, `k`, `l`, search with `/`, sort with `[` and `]`).
- **Data Multitool**: Inspects, cleans, subsets, transforms, and exports data across 30+ formats without writing scripts.

```
┌─────────────────────────────────────────────────────────────┐
│                          VisiData                           │
├─────────────────────────────────────────────────────────────┤
│  id  │ name           │ department  │  salary  │ active     │
│   1  │ Alice Johnson  │ Engineering │  145000  │ True       │
│   2  │ Bob Smith      │ Marketing   │   95000  │ True       │
│   3  │ Carol Davis    │ Design      │  110000  │ False      │
│   4  │ David Evans    │ Engineering │  160000  │ True       │
├─────────────────────────────────────────────────────────────┤
│ 4 rows (1 filtered) | active sheet: employees.csv           │
└─────────────────────────────────────────────────────────────┘
```

### Zero-Delay Lazy Loading for Million-Row Datasets

VisiData utilizes a streaming execution pipeline. When you open a 10 GB file:
- It maps the file stream immediately without loading everything into memory.
- You can navigate the top rows, sort, and inspect schemas while the background thread completes streaming.

---

## 2. Installation & Verification

### Installing via Homebrew (macOS & Linux)

```bash
# Install VisiData via Homebrew
brew install visidata

# Verify installation
which vd
vd --version
```

### Installing via pipx or pip (Python)

VisiData is a Python application and can be installed via `pipx` or `pip`:

```bash
# Install via pipx (isolated dependencies)
pipx install visidata

# Install optional format handlers (Excel, Parquet, YAML)
pipx inject visidata openpyxl pyarrow pyyaml
```

### Verifying Binary Installation

Open an inline test dataset:

```bash
echo -e "id,name,role\n1,Alice,Admin\n2,Bob,User" | vd
```

Press **`q`** to exit VisiData at any time.

---

## 3. Supported Formats & Opening Data

### Opening CSV, TSV & Delimited Text

```bash
# Open CSV file (auto-detects delimiter, header row, and types)
vd employees.csv

# Open tab-separated file
vd sales.tsv
```

### Inspecting SQLite Databases & Tables

When opening an SQLite database, VisiData displays the **Table Index**:

```bash
# Open SQLite database
vd production.sqlite
```

- Navigate the table list.
- Press **Enter** on any table to open its rows in a new sheet.
- Press **`q`** to return back to the table index sheet.

### Loading JSON, JSONL & Parquet Files

```bash
# Open line-delimited JSON
vd access_logs.jsonl

# Open high-performance Apache Parquet columnar file
vd telemetry.parquet
```

---

## 4. Core Navigation & Sheet Management

### Vim Movement & Column Resizing

| Key | Movement / Action |
| :--- | :--- |
| `h` / `j` / `k` / `l` | Move Left / Down / Up / Right across cells. |
| `gh` / `gl` | Jump to the first / last column. |
| `g0` / `ge` | Jump to the first / last row. |
| `Ctrl + d` / `Ctrl + u` | Scroll down / up half a page. |
| `_` (Underscore) | Resize active column to fit its widest content. |
| `-` (Hyphen) | Hide (delete from view) current column. |
| `gv` | Unhide all hidden columns. |

### The Sheets Sheet (Shift+S)

VisiData organizes multi-table data into stacked sheets (like browser tabs).
- Press **`Shift + S`** (`S`): Opens the **Sheets Sheet** displaying all open files, queries, and frequency distributions.
- Navigate to any sheet and press **Enter** to switch to it.
- Press **`d`** on a sheet row to close that sheet.

### The Columns Sheet (Shift+C)

- Press **`Shift + C`** (`C`): Opens the **Columns Sheet**, showing column names, data types, null counts, and width.
- Rename columns, change types, or adjust decimal precision in bulk from this sheet.

---

## 5. Sorting, Filtering & Type Inference

### Setting Data Types (#, %, $, @, ~)

By default, data is loaded as text. Set column data types to enable numeric sorting and aggregation:

| Key | Type Assigned | Format Description |
| :--- | :--- | :--- |
| `#` | **Integer** | Whole numbers (`1234`). |
| `%` | **Float** | Decimal numbers (`12.34`). |
| `$` | **Currency** | Currency formatted values (`$1,234.50`). |
| `@` | **Date / Time** | ISO dates and timestamps. |
| `~` | **String** | Reset column to plain text string. |

### Sorting Columns in Ascending & Descending Order ([, ])

Position cursor on any column:
- Press **`[`**: Sort ascending (A-Z, 0-9).
- Press **`]`**: Sort descending (Z-A, 9-0).
- Press **`g[`** or **`g]`**: Multi-column sort.

### Filtering Rows with Regular Expressions (", ,)

- Press **`"`** (Quote): Enter a regular expression to select rows matching the query in the current column (e.g. `^Engineering$`).
- Press **`g"`**: Search regex across **all columns**.
- Selected rows are highlighted.
- Press **`"`** then press **Enter** on selected rows, or press **`'`** to open a new sheet containing **only the selected matching rows**!
- Press **`,`** (Comma): Select rows where the current column matches the active cell value.
- Press **`u`**: Unselect all rows.

---

## 6. Data Aggregation, Frequency Tables & Pivots

### Instant Frequency Analysis (Shift+F)

Position cursor on any categorical column (e.g. `department` or `http_status_code`):
- Press **`Shift + F`** (`F`): Instantly generates a **Frequency Distribution Sheet**.

The frequency sheet displays:
- Unique category values.
- Count of occurrences.
- Percentage of total rows.
- Visual ascii bar chart.

### Statistical Summaries (Sum, Mean, Min, Max)

Position cursor on a numeric column (after setting type with `#` or `%`):
- Press **`+`**: Choose an aggregator function:
  - `sum`: Total sum of column.
  - `mean` / `avg`: Arithmetic average.
  - `min` / `max`: Lower and upper bounds.
  - `distinct`: Count of unique values.

Summary aggregates appear in the bottom status line.

### Multi-Dimensional Pivot Tables

1. Set one or more columns as row keys by pressing **`!`** (Toggle Key Column).
2. Position cursor on a numeric metric column and set an aggregator with **`+`** (e.g. `sum`).
3. Position cursor on the category column to pivot across columns and press **`W`** (Pivot).
4. VisiData generates a full pivot table sheet in seconds.

---

## 7. Transforming, Editing & Exporting Data

### Adding Computed Columns with Python Expressions (=)

- Press **`=`**: Enter a Python expression to create a new derived column.
  - Example: `salary * 1.10` (Computes 10% raise).
  - Example: `name.upper()` (Converts text to uppercase).
  - Example: `first_name + " " + last_name` (String concatenation).

### Saving & Exporting Cleaned Files (Ctrl+S)

Save the active sheet, filtered subset, or transformed data back to disk:

```text
Ctrl + S
```

VisiData prompts for a filename:
- Save as CSV: `cleaned_data.csv`
- Save as JSONL: `output.jsonl`
- Save as SQLite: `database.sqlite` (creates table named after active sheet)
- Save as Parquet: `output.parquet`

---

## 8. Practical Real-World Workflows

### Workflow 1: Auditing a 5-Million Row Web Server Access Log

```bash
# 1. Open massive access log file
vd access.log
```

1. Navigate to the `status_code` column and press `#` (set as integer).
2. Press `Shift + F` to view HTTP response code breakdown (see percentage of 200, 404, 500 errors).
3. On row `500`, press `Enter` to drill down into a dedicated sheet showing only the 500 server error requests!

### Workflow 2: Joining Two Datasets on a Common Key

```bash
# Open multiple datasets simultaneously
vd customers.csv orders.csv
```

1. In `customers.csv`, position on `customer_id` and press `!` (mark as key).
2. Press `Shift + S`, switch to `orders.csv`.
3. Position on `customer_id` and press `!` (mark as key).
4. Press `&` (Join): Select join type (`inner`, `left`, `full`).
5. VisiData creates a merged sheet with linked customer details for every order!

### Workflow 3: Exploring an SQLite Database File Interactively

```bash
vd /var/lib/docker/volumes/app_data/db.sqlite
```

1. View all tables in the database.
2. Filter for tables with row counts $> 1000$.
3. Inspect schema, sort by creation dates, and export query subsets to CSV.

---

## 9. Quick Reference Cheat Sheet & FAQ

### Keybindings Summary Table

| Action | Keybinding |
| :--- | :--- |
| **Move Cell** | `h` / `j` / `k` / `l` |
| **Auto-Resize Column** | `_` (Underscore) |
| **Hide Column** | `-` (Hyphen) |
| **Set Type (Int / Float / Date)** | `#` / `%` / `@` |
| **Sort Ascending / Descending** | `[` / `]` |
| **Filter by Value** | `,` (Comma) |
| **Filter by Regex** | `"` (Quote) |
| **Frequency Table** | `Shift + F` (`F`) |
| **Sheets List** | `Shift + S` (`S`) |
| **Columns List** | `Shift + C` (`C`) |
| **Add Computed Column** | `=` |
| **Save / Export Sheet** | `Ctrl + S` |
| **Help Screen** | `Ctrl + H` |
| **Quit Active Sheet / Exit** | `q` / `gq` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **Excel (.xlsx) or Parquet fails to open**: Install required Python parser packages:
> ```bash
> pip install openpyxl pyarrow
> ```

> [!TIP]
> **Undo Changes**: If you accidentally hide a column or sort unexpectedly, press `Shift + U` to open the command undo log, or `gv` to unhide columns.
