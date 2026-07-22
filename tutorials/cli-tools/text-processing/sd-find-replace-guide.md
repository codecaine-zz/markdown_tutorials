# sd (Intuitive Find & Replace) Guide

`sd` is an intuitive, high-performance find-and-replace command-line tool. Designed as a modern replacement for `sed`, `sd` uses standard regex syntax (JavaScript/Python regex format), modifies files in-place by default without needing `sed -i ""` quirks on macOS, and runs significantly faster.

---

## 📚 Table of Contents

1. [Overview & `sd` vs `sed` Comparison](#overview--sd-vs-sed-comparison)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic String Replacement](#basic-string-replacement)
4. [In-Place File Replacements](#in-place-file-replacements)
5. [Regex Capture Groups & Variables](#regex-capture-groups--variables)
6. [Literal Mode (`-s`) & Multi-Line Mode (`-m`)](#literal-mode--s--multi-line-mode--m)
7. [💡 Practical Real-World Examples](#-practical-real-world-examples)
8. [Pipeline Integration with `fd` & `find`](#pipeline-integration-with-fd--find)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & `sd` vs `sed` Comparison

Comparing `sd` with traditional `sed`:

| Feature | `sed` (BSD macOS) | `sd` |
| --- | --- | --- |
| **In-place Syntax** | `sed -i "" 's/old/new/g' file` | `sd 'old' 'new' file` |
| **Regex Engine** | POSIX ERE/BRE (escaping issues) | PCRE2 / Rust Regex (Standard `\d`, `\w`) |
| **Capture Groups** | `\1`, `\2` (or `\\1`) | `$1`, `$2` |
| **Speed** | Moderate | Ultra-Fast (SIMD accelerated) |

---

## ⚙️ Installation via Homebrew

```bash
# Install sd via Homebrew on macOS
brew install sd

# Verify installation
sd --version
```

---

## 🚀 Basic String Replacement

By default, `sd` matches patterns globally across all occurrences without requiring a `/g` flag.

```bash
# Replace 'foo' with 'bar' in piped stdin text
echo "foo bar foo" | sd "foo" "bar"
# Output: bar bar bar

# Replace digits with 'X'
echo "Order 12345 confirmed" | sd "\d+" "XXXXX"
# Output: Order XXXXX confirmed
```

---

## 📝 In-Place File Replacements

`sd` edits files in place safely.

```bash
# Replace all instances of 'http://' with 'https://' in config.json
sd "http://" "https://" config.json

# Modify multiple files in place
sd "v1/api" "v2/api" src/*.js
```

---

## 🎛️ Regex Capture Groups & Variables

Use `$1`, `$2`, `$3` in the replacement string to refer to captured regex groups.

```bash
# Swap 'First Last' names into 'Last, First'
echo "John Doe" | sd "(\w+)\s+(\w+)" "$2, $1"
# Output: Doe, John

# Reformat dates from YYYY-MM-DD to DD/MM/YYYY
echo "Event on 2025-12-31" | sd "(\d{4})-(\d{2})-(\d{2})" "$3/$2/$1"
# Output: Event on 31/12/2025

# Wrap JSON keys in quotes
echo "name: Alice" | sd "(\w+):" "\"$1\":"
# Output: "name": Alice
```

---

## 🔤 Literal Mode (`-s`) & Multi-Line Mode (`-m`)

### 1. Literal Mode (`-s` / `--string-mode`)
Disables regex interpretation so special characters like `[`, `]`, `*`, `$`, `.` are matched strictly as plain text.

```bash
# Replace literal '$var[0]' without escaping regex symbols
sd -s '$var[0]' '$item' script.php
```

### 2. Multi-Line Mode (`-m` / `--flags m`)
Allows matching across newline boundaries.

```bash
# Match multi-line XML tags
sd -m "<note>.*?</note>" "<note>CLEARED</note>" document.xml
```

---

## 💡 Practical Real-World Examples

### Example 1: Mass Updating Environment Variables
```bash
# Replace localhost database host with production endpoint in .env
sd "DB_HOST=localhost" "DB_HOST=db.internal.company.com" .env
```

### Example 2: Renaming React / JS Import Paths
```bash
# Update component imports across src directory
sd "import Button from '\./Button';" "import { Button } from '@ui/components';" src/*.tsx
```

---

## 🔗 Pipeline Integration with `fd` & `find`

Combine `fd` or `find` with `sd` to perform bulk replacements across entire project codebases.

```bash
# Use fd to find all Python files and replace 'python2' with 'python3'
fd -e py -x sd "python2" "python3" {}

# Update deprecation notices across all Markdown docs
fd -e md -x sd "v1.0-deprecated" "v2.0-stable" {}
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Pipe replacement | `echo "text" \| sd "old" "new"` |
| File in-place | `sd "old" "new" file.txt` |
| Regex capture group | `sd "(\w+) (\w+)" "$2 $1" file.txt` |
| Literal string match | `sd -s "$100" "$200" file.txt` |
| Bulk replace with `fd` | `fd -e js -x sd "old" "new" {}` |