## ✅ Summary of What You Have

You've covered:
1. **Basic `sed` syntax**
2. **Regular expressions in `sed`**
3. **Editing files with `-i`, backup options (`-i.bak`)**
4. **Pattern matching, substitution, and replacement**
5. **Practical examples** like formatting text, processing logs, working with code
6. **macOS-specific tips**, such as handling paths, Xcode projects, system logs
7. **Debugging techniques** and safe testing practices
8. **Useful one-liners for daily terminal use**

This is a great resource — especially if you're teaching others or building documentation.

## 🛠 Suggestions to Improve This Guide

### 1. **Add a Table of Contents**
To help readers navigate easily:
```markdown
# Table of Contents
1. [Basic Syntax](#basic-syntax)
2. [Pattern Matching & Substitution](#pattern-matching--substitution)
3. [File Editing with `-i`](#file-editing-with--i)
4. [Real-World Examples (MacOS)](#real-world-examples-for-macos-development)
5. [Debugging Tips](#debugging-and-testing-tips)
6. [Useful One-Liners](#useful-one-liners-for-macos-terminal)
```

### 2. **Clarify macOS vs Linux Differences**
Some `sed` commands differ slightly between macOS and GNU `sed`. For example:
```bash
# On macOS, this needs a backup extension or it fails silently:
sed -i '' 's/old/new/g' file.txt

# On Linux (GNU sed), you can do:
sed -i 's/old/new/g' file.txt
```

You might want to add a note like:
> ⚠️ On macOS, always use `-i ''` when editing files in place to avoid creating backup files.

### 3. **Add Visual Examples for Beginners**
Include small diagrams or inline sample output where appropriate:
```bash
# Before:
echo "hello world" | sed 's/world/there/'
# After:
hello there
```

### 4. **Group Similar Commands Together**
Organize examples under clear subheadings like:
- `sed` for text formatting
- `sed` for log analysis
- `sed` in code automation

This makes it easier to find specific use cases.

### 5. **Include Common Pitfalls & Fixes**
Example:
```bash
# Problem: Unescaped special characters cause errors
echo "test.*" | sed 's/./X/'   # Wrong!

# Fix: Escape regex metacharacters properly
echo "test.*" | sed 's/\./X/g'
```

### 6. **Optional: Add Quick Reference Sheet**
A compact reference table at the end:
| Command | Description |
|--------|-------------|
| `s/pattern/replacement/` | Substitute first match |
| `s///g` | Global substitution |
| `sed -i '' 's/a/b/g' file.txt` | Edit in place (macOS) |
| `sed -n '/pattern/p'` | Print only matching lines |

## 🧠 Bonus: Interactive Learning Tip

Encourage readers to:
- Use [https://regex101.com](https://regex101.com) to test regex patterns
- Try `echo "test string" | sed 's/old/new/g'` in Terminal before applying to real files
- Run `man sed` for full documentation

## 📝 Final Thoughts

You've created a **comprehensive and practical guide** to `sed` on macOS. With just a few tweaks (like adding TOC, clarifying differences between systems, and grouping examples), this becomes an excellent learning tool for developers or sysadmins working in Unix-like environments.

Would you like me to:
- Convert your content into a Markdown document with formatting?
- Turn it into a PDF or HTML version?
- Create a cheat sheet from it?

Let me know how I can help further!
date: 2025-08-30 00:23:14
category: CLI Tools

## What is `sed`?

`sed` stands for **Stream Editor**. It's a powerful command-line tool that reads input line by line, applies transformations, and outputs the results. On macOS, you're using GNU `sed` which has more features than traditional BSD sed.

## Installation and Basic Setup

macOS comes with `sed` pre-installed (usually from BSD). For advanced features, install GNU `sed`:

```bash
# Install via Homebrew
brew install gnu-sed

# Use gsed instead of sed for GNU version
gsed --version
```

## 1. Basic Syntax and Options

### Basic Usage:
```bash
sed [options] 'command' file.txt
```

### Common Options:
```bash
# -i: Edit files in-place (be careful!)
# -n: Suppress default output
# -e: Add script commands
# -f: Read script from file

# Example with multiple options:
sed -i.bak 's/old/new/g' myfile.txt  # Backup and edit in-place
```

## 2. Basic Substitution Commands

### Simple Replace:
```bash
# Replace first occurrence only
echo "Hello world hello" | sed 's/hello/hi/'

# Replace all occurrences
echo "hello world hello" | sed 's/hello/hi/g'

# Case insensitive replacement
echo "Hello WORLD Hello" | sed 's/hello/hi/i'
```

### Using Different Delimiters:
```bash
# Using different delimiters to avoid escaping slashes
echo "/usr/local/bin" | sed 's|/usr/local|/opt|g'
echo "/path/to/file" | sed 's#/path/to#~/Documents#g'

# Mixed delimiter example
sed 's@/usr/local@/opt@g' file.txt
```

## 3. Advanced Substitution Features

### Using Capture Groups:
```bash
# Swap first and last name
echo "John Doe" | sed 's/\(.*\) \(.*\)/\2 \1/'

# Reformat phone numbers: (123) 456-7890 → 123.456.7890
echo "(123) 456-7890" | sed 's/([^)]*) *-\([^.]*\)\.\([^.]*\)/\1.\2/'

# Extract date format: MM/DD/YYYY → YYYY-MM-DD
echo "12/25/2023" | sed 's/\([0-9]\{2\}\)\/\([0-9]\{2\}\)\/\([0-9]\{4\}\)/\3-\1-\2/'
```

### Using `&` to Reference Matched Text:
```bash
# Double the matched text
echo "hello" | sed 's/\(.\)/\1\1/g'

# Add prefix/suffix to matching text
echo "apple banana cherry" | sed 's/\w\+/[\0]/g'
```

### Using Backreferences:
```bash
# Convert snake_case to camelCase
echo "this_is_a_test" | sed 's/_\([a-z]\)/\U\1/g'

# Add quotes around words with numbers
echo "file123.txt file456.log" | sed 's/\w\+\([0-9]\+\)\.\w\+/"&"/g'
```

## 4. Addressing Lines

### Specific Line Numbers:
```bash
# Replace only line 5
sed '5s/old/new/' file.txt

# Replace from line 3 to end of file
sed '3,$s/old/new/g' file.txt

# Replace first 3 lines
sed '1,3s/old/new/g' file.txt

# Replace specific range with condition
sed '/pattern/,/end_pattern/s/old/new/g' file.txt
```

### Using Regular Expressions for Line Selection:
```bash
# Replace in lines matching pattern
sed '/ERROR/s/error/warning/' log.txt

# Replace first match of pattern in each line
sed '/pattern/s/old/new/' file.txt

# Replace all matches but only on certain lines (lines 1,3,5)
sed '1~2s/old/new/g' file.txt  # Every other line starting from 1
```

## 5. Line Manipulation Commands

### Insert and Append:
```bash
# Insert text before pattern
echo "line1" | sed '/line1/i\new inserted line'

# Append text after pattern  
echo "line1" | sed '/line1/a\new appended line'

# Add line at beginning of file
sed '1i\First line added' file.txt

# Add line at end of file (append)
sed '$a\Last line added' file.txt
```

### Delete Lines:
```bash
# Delete lines matching pattern
sed '/pattern/d' file.txt

# Delete specific line numbers
sed '5d' file.txt  # Delete line 5

# Delete range of lines
sed '1,3d' file.txt  # Delete first 3 lines

# Delete empty lines
sed '/^$/d' file.txt

# Delete lines with no content (whitespace only)
sed '/^\s*$/d' file.txt
```

### Replace Lines:
```bash
# Replace entire line matching pattern
sed '/pattern/c\new line text' file.txt

# Replace specific line number
sed '3c\replaced line' file.txt

# Replace and keep old content if needed (using hold space)
sed '/pattern/{h;d;};x;/pattern/!p' file.txt
```

## 6. Advanced Features with Hold Space and Pattern Space

### Using Hold Space:
```bash
# Swap first two lines
sed '1{h;d;};2{x;p;g;}' file.txt

# Duplicate last line at beginning
sed '$x;$p;$G' file.txt

# Print line count in front of each line
sed '=' file.txt | sed 'N;s/\n/ /'
```

### Multiple Commands:
```bash
# Chain commands with semicolon or newlines
echo "hello world" | sed 's/hello/hi/; s/world/universe/'

# Using -e flag for multiple expressions
echo "apple banana cherry" | sed -e 's/apple/orange/' -e 's/banana/grape/'
```

## 7. Practical Examples

### Text Formatting:
```bash
# Convert tabs to spaces (4 spaces)
sed 's/\t/    /g' file.txt

# Remove trailing whitespace
sed 's/[[:space:]]*$//' file.txt

# Normalize multiple spaces to single space
echo "hello   world    test" | sed 's/ \+/ /g'

# Convert mixed case to title case
echo "HELLO WORLD" | sed 's/\b\([a-z]\)/\U\1/g'
```

### File Processing:
```bash
# Remove comments from config file (lines starting with #)
sed '/^#/d' config.ini

# Extract only email addresses from file
grep -oE '\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b' file.txt | sed 's/.*\(.*@.*\).*/\1/'

# Replace environment variables in template files
sed 's/\${\([^}]*\)}/\1/g' template.conf

# Format JSON-like structures (basic)
echo '{"name":"john","age":30}' | sed 's/,/,\n/g'
```

### Code Modification:
```bash
# Convert JavaScript function declarations to arrow functions (simple case)
sed 's/function \([a-zA-Z0-9_]*\)(\([^)]*\))/const \1 = (\2) =>/' script.js

# Remove unnecessary semicolons in code
sed 's/;$//' file.js

# Add line numbers to source files
sed '=' filename | sed 'N;s/\n/\t/'
```

## 8. Real-World Examples for MacBook Development

### Processing Log Files:
```bash
# Extract only error lines with timestamps
grep -E '\d{4}-\d{2}-\d{2}.*ERROR' app.log | sed 's/.*ERROR//'

# Filter and format Apache logs
sed '/GET /!d; s/.*GET \(.*\) HTTP.*$/\1/' access.log

# Show only unique IP addresses from log
awk '{print $1}' access.log | sort -u
```

### Working with macOS-specific files:
```bash
# Convert paths for macOS (if needed)
sed 's|/Users/[^/]*/|~/|g' file.txt

# Format Xcode project file entries
sed '/\.xcodeproj/d; s/.*path = \(.*\);/\1/' project.pbxproj

# Process system logs to extract user information
log show --last 5m | sed 's/.*User: //'
```

## 9. Debugging and Testing Tips

### Safe Testing:
```bash
# Always test on a copy first
cp important_file.txt important_file_backup.txt
sed -i.bak 's/old/new/g' important_file.txt

# Use -n flag to suppress output for inspection
sed -n '/pattern/p' file.txt  # Print only matching lines

# Test patterns without changing files using echo
echo "test string" | sed 's/test/found/'
```

### Complex Patterns:
```bash
# Match words with specific endings
sed '/\.\(txt\|log\)$/d' file_list.txt

# Replace multiple similar strings efficiently
sed -e 's/old1/new1/g' -e 's/old2/new2/g' file.txt

# Handle special characters in patterns
echo "test.c" | sed 's/\./\\./g'
```

## 10. Useful One-liners for macOS Terminal

```bash
# Count lines and words in all files
find . -type f -exec wc -l {} + | tail -n 1

# Show file sizes sorted by largest first
ls -la | grep "^-" | sort -k5 -nr

# Find duplicate lines in a file
sort file.txt | uniq -d

# Replace all occurrences of a string with another (recursive)
find . -type f -exec sed -i '' 's/old/new/g' {} +

# Convert CSV to tab-separated values
sed 's/,/\t/g' data.csv

# Extract first column from tab-delimited file
cut -f1 file.txt

# Find files modified in last 24 hours
find . -type f -mtime -1

# Show current git status with short format
git status --short

# List all running processes (macOS)
ps aux | grep python3
```

These examples provide a comprehensive introduction to using sed on macOS, covering basic syntax through advanced techniques. Always remember to test your commands on copies of important files and use the -n flag for safer experimentation!

