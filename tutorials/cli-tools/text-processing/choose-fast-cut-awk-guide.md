# Choose Fast Cut & Awk Alternative Guide

`choose` (distributed on Homebrew as `choose-rust`) is a human-friendly, high-performance command-line utility written in Rust. It is designed as an intuitive, modern replacement for `cut` and repetitive `awk '{print $...}'` one-liners, using simple Python-style slicing syntax (`[start:end:step]`) and 0-indexed fields.

---

## 📚 Table of Contents

1. [Overview & `cut` / `awk` vs `choose` Comparison](#overview-cut-awk-vs-choose-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Basic Usage & Field Selection](#basic-usage-field-selection)
4. [Python-Style Slices & Negative Indices](#python-style-slices-negative-indices)
5. [Custom Field Separators & Output Formatting](#custom-field-separators-output-formatting)
6. [Character Mode vs Field Mode](#character-mode-vs-field-mode--c---character-mode)
7. [Real-World Pipeline Examples](#real-world-pipeline-examples)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `cut` / `awk` vs `choose` Comparison

Classic `cut` fails when fields have variable whitespace, and `awk` syntax is notoriously verbose for simple column extractions. `choose` eliminates both pain points.

| Task | Traditional `cut` / `awk` | Modern `choose` |
| :--- | :--- | :--- |
| **First Column** | `awk '{print $1}'` | `choose 0` |
| **First and Third Column** | `awk '{print $1, $3}'` | `choose 0 2` |
| **Last Column** | `awk '{print $NF}'` | `choose -1` |
| **Columns 2 to 5** | `awk '{for(i=2;i<=5;++i)print $i}'` | `choose 1:5` |
| **Every Other Column** | Complex `awk` loop | `choose ::2` |
| **Extract by CSV Comma** | `cut -d',' -f1,3` | `choose -f ',' 0 2` |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install choose-rust
```

### 2. Install via Cargo

```bash
cargo install choose
```

### 3. Verify Installation

```bash
choose --version
```

---

## 🚀 Basic Usage & Field Selection

`choose` operates on standard input or files using zero-based indexing (`0` is the first item):

```bash
# Print first field (0-indexed)
echo "apple banana cherry date" | choose 0
# Output: apple

# Print first and third fields
echo "apple banana cherry date" | choose 0 2
# Output: apple cherry

# Print multiple fields in any order
echo "apple banana cherry date" | choose 3 0 1
# Output: date apple banana
```

---

## ✂️ Python-Style Slices & Negative Indices

`choose` supports Python slice notation `[start:stop:step]` directly on the command line:

### 1. Range of Columns (`start:stop`)

```bash
# Print columns from index 1 up to 3 (exclusive)
echo "zero one two three four five" | choose 1:4
# Output: one two three

# Print all columns from index 2 onwards
echo "zero one two three four five" | choose 2:
# Output: two three four five

# Print all columns up to index 3
echo "zero one two three four five" | choose :3
# Output: zero one two
```

### 2. Negative Indices (Extract from the End)

Extract values relative to the end of the line without counting total columns:

```bash
# Print the very last column
echo "2026-08-28 16:45:00 user123 /home/user/app.log" | choose -1
# Output: /home/user/app.log

# Print the second-to-last column
echo "alpha beta gamma delta epsilon" | choose -2
# Output: delta

# Print the last 3 columns
echo "alpha beta gamma delta epsilon" | choose -3:
# Output: gamma delta epsilon
```

### 3. Stepping (`start:stop:step`)

```bash
# Print every 2nd column
echo "0 1 2 3 4 5 6 7 8 9" | choose ::2
# Output: 0 2 4 6 8

# Reverse all fields on a line
echo "first second third fourth" | choose ::-1
# Output: fourth third second first
```

---

## 🎯 Custom Field Separators & Output Formatting

### 1. Input Field Separator (`-f` / `-F`)

By default, `choose` treats consecutive whitespace (spaces, tabs) as a single delimiter. Use `-f` to split by commas, colons, pipes, or custom strings:

```bash
# Parse /etc/passwd usernames and home directories (colon-separated)
cat /etc/passwd | choose -f ':' 0 5

# Parse CSV data
echo "John,Doe,30,Developer,San Francisco" | choose -f ',' 0 3
# Output: John Developer
```

### 2. Output Field Separator (`-o` / `--output-field-separator`)

Reformat the output delimiter with `-o`:

```bash
# Convert space-delimited text into comma-separated values
echo "ID Name Role Status" | choose -o ',' 0:
# Output: ID,Name,Role,Status
```

---

## 🔡 Character Mode vs Field Mode (`-c` / `--character-mode`)

Use `-c` to slice strings by character position instead of whitespace words:

```bash
# Extract characters 0 to 4
echo "ANTIGRAVITY" | choose -c 0:4
# Output: ANTI

# Extract last 4 characters
echo "ANTIGRAVITY" | choose -c -4:
# Output: VITY
```

---

## 🛠️ Real-World Pipeline Examples

### 1. Extract Process Names and PIDs from `ps`

```bash
# Get PID and Command from ps output
ps aux | choose 1 10:
```

### 2. Extract Docker Container IDs and Image Names

```bash
docker ps | choose 0 1
```

### 3. Extract HTTP Status Codes from Web Server Logs

```bash
cat access.log | choose 8
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Goal | Command |
| :--- | :--- |
| **First column** | `choose 0` |
| **Last column** | `choose -1` |
| **Columns 1 to 4** | `choose 0:4` |
| **Last 2 columns** | `choose -2:` |
| **CSV Columns 1 & 3** | `choose -f ',' 0 2` |
| **Character slice (0 to 8)** | `choose -c 0:8` |
| **Reverse columns** | `choose ::-1` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Choose shortcuts
alias col='choose'
alias csv-col='choose -f ,'
alias last-col='choose -1'
```

---

## 🗑️ Uninstallation

To remove `choose`:

```bash
brew uninstall choose-rust
```
