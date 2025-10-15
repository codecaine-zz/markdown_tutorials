## Table of Contents
1. [Introduction to Grep](#introduction-to-grep)
2. [Basic Grep Syntax](#basic-grep-syntax)
3. [Simple Pattern Matching](#simple-pattern-matching)
4. [Regular Expressions in Grep](#regular-expressions-in-grep)
5. [Advanced Options and Flags](#advanced-options-and-flags)
6. [Practical Examples for MacBook M4 Users](#practical-examples-for-macbook-m4-users)
7. [Grep with File Operations](#grep-with-file-operations)
8. [Performance Tips](#performance-tips)

## Introduction to Grep

**Grep** (short for "Global Regular Expression Print") is a powerful command-line utility that searches for patterns in files and displays matching lines. On macOS, grep comes pre-installed, but you might want to use the more advanced **ripgrep** (`rg`) or **ag` (The Silver Searcher) for better performance.

### Why Use Grep?
- Quickly search through large codebases
- Find specific text patterns in files
- Filter output from other commands
- Debug programs and logs

## Basic Grep Syntax

```bash
# Basic syntax
grep [options] pattern [files]

# Simple example - search for "error" in a file
grep "error" logfile.txt

# Search across multiple files
grep "function" *.js

# Search recursively in directories
grep -r "import" src/
```

## Simple Pattern Matching

### Basic Text Search
```bash
# Find lines containing specific text
grep "hello" document.txt

# Case-insensitive search (using -i flag)
grep -i "Hello" document.txt

# Exact word matching (using -w flag)
grep -w "cat" animals.txt

# Show line numbers (using -n flag)
grep -n "error" system.log
```

### Multiple Files and Directories
```bash
# Search in multiple files
grep "pattern" file1.txt file2.txt file3.txt

# Search all .txt files in current directory
grep "search_term" *.txt

# Search recursively (use -r or -R)
grep -r "function" src/

# Search without recursion (use -d skip)
grep -d skip "pattern" dir/
```

## Regular Expressions in Grep

### Basic Regex Patterns
```bash
# Lines starting with "Error"
grep "^Error" log.txt

# Lines ending with ".log"
grep "\.log$" log.txt

# Lines containing exactly 5 digits
grep "[0-9]\{5\}" numbers.txt

# Lines containing word boundaries (using -w)
grep -w "cat" animals.txt
```

### Advanced Regex Examples
```bash
# Find lines with email patterns
grep -E "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" contacts.txt

# Find lines with phone numbers (various formats)
grep -E "(\+?1[-.\s]?)?\(?([0-9]{3})\)?[-.\s]?([0-9]{3})[-.\s]?([0-9]{4})" phonebook.txt

# Lines containing URLs
grep -E "https?://[^\s]+" webpage.html

# Find duplicate lines (using grep with sort)
sort file.txt | uniq -d
```

## Advanced Options and Flags

### Output Formatting
```bash
# Show only matching part (using -o flag)
grep -o "error\|warning" log.txt

# Invert match (show non-matching lines)
grep -v "skip" config.txt

# Count matches (use -c flag)
grep -c "pattern" file.txt

# Show context around matches
grep -C 3 "error" log.txt    # Show 3 lines before/after
grep -B 2 "error" log.txt    # Show 2 lines before
grep -A 2 "error" log.txt    # Show 2 lines after
```

### File and Pattern Options
```bash
# Search in specific file types only
grep --include="*.py" "function" src/

# Exclude certain files or directories
grep --exclude="*.tmp" --exclude-dir=".git" "pattern" project/

# Case-insensitive with word boundaries
grep -iw "class" *.java

# Suppress error messages (use -s flag)
grep -s "pattern" non_existent_file.txt
```

## Practical Examples for MacBook M4 Users

### 1. Searching Code Files
```bash
# Find all JavaScript functions named "calculate"
grep -r "^function calculate\|^const calculate\|^let calculate" src/

# Search for React component imports in a project
grep -r "import.*Component" src/ --include="*.jsx"

# Find all console.log statements (useful for debugging)
grep -r "console\.log(" src/ --include="*.js"

# Search Swift code for specific patterns
grep -r "func [a-zA-Z0-9_]*(" src/ --include="*.swift"
```

### 2. System Log Analysis
```bash
# Find error messages in system logs (use with sudo)
sudo grep -i "error\|failed" /var/log/system.log

# Search for network connection issues
grep -i "connection.*timeout\|network.*lost" network.log

# Look for specific user activity in logs
grep "user_id_123456" auth.log

# Find recent failures (last 100 lines of log)
tail -n 100 system.log | grep -i "failure"
```

### 3. Development Environment Setup
```bash
# Check if Node.js is installed and find version
which node && node --version

# Search for package.json dependencies that match a pattern
grep -A2 -B2 "lodash\|express" package.json

# Find all environment variables set to specific values
grep -r "DATABASE_URL=" .env

# Search git log for commit messages containing specific words
git log --oneline | grep -i "bugfix\|feature"
```

### 4. File System Operations
```bash
# Find files with specific extensions and contents
find . -name "*.md" -exec grep -l "TODO" {} \;

# Search for hidden files containing a pattern (useful for macOS)
find . -type f -name ".*" -exec grep -l "pattern" {} \;

# Find duplicate file names in current directory
ls | sort | uniq -d

# Search in all subdirectories for files with specific content
find . -type f -exec grep -l "configuration" {} \;
```

## Grep with File Operations

### Combining Grep with Other Commands
```bash
# Pipe output to other commands
grep "error" log.txt | wc -l

# Search and sort results
grep "pattern" file.txt | sort

# Find unique matches only
grep -o "[a-zA-Z0-9]*@[a-zA-Z0-9.]*" contacts.txt | sort | uniq

# Search and display line numbers with context
grep -nC 2 "critical" system.log

# Combine grep with sed for text processing
grep "pattern" file.txt | sed 's/pattern/replacement/g'
```

### Using Grep to Filter Command Output
```bash
# List all processes containing specific word
ps aux | grep python

# Find files modified in last 24 hours that contain a pattern
find . -type f -mtime -1 -exec grep "pattern" {} \;

# Search for running services by name
launchctl list | grep "com.apple"

# Check disk usage of directories matching a pattern
df -h | grep "/Volumes"
```

## Performance Tips

### Optimizing Grep Usage on MacBook M4

```bash
# Use faster alternatives for large datasets (install via Homebrew)
# brew install ripgrep
rg "pattern" file.txt    # Much faster than grep

# For multiple searches, use -f flag to read patterns from a file
grep -f search_terms.txt data_file.txt

# Limit search depth in recursive directories
grep -r --max-depth=2 "pattern" project/

# Use binary files exclusion for text-only search
grep --exclude="*.bin" --exclude="*.jpg" "pattern" *

# Search with case-insensitive and line number output (common combo)
grep -in "error\|warning" log.txt

# For very large files, use head/tail to limit scope
head -n 1000 bigfile.log | grep "pattern"
```

### Memory and Speed Optimization Examples
```bash
# Efficient search in large files with limited context
grep -m 5 "error" large_log.txt    # Stop after first 5 matches

# Use fixed strings when regex not needed (faster)
grep -F "literal_string" file.txt

# Search multiple patterns efficiently
grep -E "(pattern1|pattern2|pattern3)" file.txt

# Use grep with zcat for compressed files
zcat large.log.gz | grep "error"
```

## Common Grep Patterns for MacBook M4 Users

### 1. Development Workflow
```bash
# Find all TODO comments in codebase
grep -r "TODO\|FIXME" src/ --include="*.js" --include="*.ts"

# Search for specific error handling patterns
grep -r "catch.*Error\|error.*handler" src/

# Find configuration files with specific settings
find . -name "*.yml\|*.yaml\|*.json" -exec grep -l "debug.*true" {} \;
```

### 2. System Administration
```bash
# Check user login history (macOS)
last | grep username

# Search for security events in system logs
sudo grep -i "failed\|unauthorized" /var/log/system.log

# Find all running applications by name
ps aux | grep "ApplicationName"

# Look for specific network connections
netstat -an | grep LISTEN | grep 8080
```

### 3. Quick Debugging Examples
```bash
# Search current directory for a specific function call with context
grep -nC3 "functionCall()" .

# Find all files that might contain sensitive information (use carefully)
grep -r -i "password\|secret\|token" . --include="*.conf"

# Quick search in last 100 lines of log file
tail -n 100 /var/log/system.log | grep "error"
```

This comprehensive guide provides practical examples and patterns that MacBook M4 users can apply to various development, system administration, and troubleshooting scenarios. The examples are designed to be efficient and take advantage of the performance characteristics of modern macOS systems while being compatible with standard Unix/Linux tools.

