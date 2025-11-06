# `sd` CLI Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial teaches you how to use the `sd` command-line tool - a modern, intuitive find-and-replace utility that's simpler than `sed`. We'll cover installation, basic commands, and practical examples.

---

## 🧰 Prerequisites

### 1. Install Homebrew (if not installed)
Homebrew is a package manager for macOS that makes installing software easy.

```bash
# Install Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

🔗 [Homebrew Documentation](https://brew.sh/)

---

### 2. Install `sd` Using Brew

```bash
# Install sd
brew install sd
```

🔗 [`sd` Installation via Brew](https://formulae.brew.sh/formula/sd)

---

## 🚀 Using `sd` CLI

### 1. Basic Find and Replace in a String

```bash
# Replace text in a string
echo "Hello world" | sd "world" "universe"
```

Output:
```
Hello universe
```

---

### 2. Replace Text in a File

```bash
# Replace text in a file (creates a backup)
sd "old_text" "new_text" file.txt
```

This modifies the file in place. To create a backup before modifying:

```bash
# Replace with backup
sd -p ".bak" "old_text" "new_text" file.txt
```

---

## 🔤 Basic Commands

### 1. Simple Text Replacement

```bash
# Replace all occurrences of a word
echo "The cat sat on the cat" | sd "cat" "dog"
```

Output:
```
The dog sat on the dog
```

---

### 2. Using Regular Expressions

```bash
# Replace digits with 'X'
echo "My phone is 123-456-7890" | sd "\d" "X"
```

Output:
```
My phone is XXX-XXX-XXXX
```

---

### 3. Case-Insensitive Replacement

```bash
# Case-insensitive replacement
echo "Hello World" | sd -f "(?i)world" "universe"
```

Output:
```
Hello universe
```

---

### 4. Replace Multiple Files

```bash
# Replace text in multiple files
sd "old_text" "new_text" file1.txt file2.txt file3.txt
```

---

## 📦 Advanced Features

### 1. Capture Groups

```bash
# Use capture groups to reorganize text
echo "John Doe, 30" | sd "(\w+) (\w+), (\d+)" "Age: $3, Name: $1 $2"
```

Output:
```
Age: 30, Name: John Doe
```

---

### 2. Multi-line Replacement

```bash
# Replace across multiple lines
sd '(?s)start(.*)end' 'start-replacement-end' file.txt
```

---

### 3. Replace with File Contents

```bash
# Replace a pattern with contents from another file
sd "PATTERN" "$(cat replacement.txt)" file.txt
```

---

### 4. Preview Changes Without Applying

```bash
# Preview changes without modifying files
sd -n "old_text" "new_text" file.txt
```

---

## 🛠️ VSCode Integration Tips

### 1. Using `sd` in VSCode Terminal

You can use `sd` directly in VSCode's integrated terminal:
- Open VSCode terminal (`Ctrl + `` ` or `View > Terminal`)
- Run `sd` commands as shown above

### 2. Create a Script for Complex Replacements

Create a file named `replace.sh`:

```bash
#!/bin/bash
# replace.sh - Batch replacement script

# Replace version numbers
sd "version: 1\.0" "version: 1.1" config.yaml

# Update copyright year
sd "Copyright 2023" "Copyright 2024" *.md

# Replace deprecated function calls
sd "oldFunction\(" "newFunction(" *.js
```

Make it executable and run:

```bash
chmod +x replace.sh
./replace.sh
```

---

## 🧹 Useful CLI Options

### 1. Help Command

```bash
# Show help
sd --help
```

---

### 2. Verbose Mode

```bash
# Show what files are being processed
sd -v "old_text" "new_text" *.txt
```

---

### 3. Limit Replacements

```bash
# Replace only the first occurrence
sd -c 1 "pattern" "replacement" file.txt
```

---

### 4. Fixed Strings (No Regex)

```bash
# Treat pattern as literal string, not regex
sd -s "special[chars]" "replacement" file.txt
```

---

## 📚 Common Use Cases

### 1. Update Configuration Files

```bash
# Update URL in config
sd "http://old-domain.com" "https://new-domain.com" config.json
```

---

### 2. Refactor Code

```bash
# Rename a variable across files
sd "oldVariableName" "newVariableName" *.js
```

---

### 3. Update Version Numbers

```bash
# Update version in package files
sd '"version": "1\.0\.0"' '"version": "1.0.1"' package.json
```

---

### 4. Clean Up Text Files

```bash
# Remove extra whitespace
sd "\s+" " " file.txt

# Remove empty lines
sd "^\s*\n" "" file.txt
```

---

## ✅ Summary

| Task | Command |
|------|---------|
| Install | `brew install sd` |
| Simple Replace | `sd "find" "replace" file.txt` |
| Pipe Replace | `echo "text" | sd "find" "replace"` |
| Regex Replace | `sd "\d+" "number" file.txt` |
| Case-insensitive | `sd -f "(?i)word" "replacement" file.txt` |
| Preview Changes | `sd -n "find" "replace" file.txt` |
| Backup Before Replace | `sd -p ".bak" "find" "replace" file.txt` |
| Multiple Files | `sd "find" "replace" *.txt` |

You're now ready to use `sd` confidently for all your find-and-replace needs! It's especially useful for batch text processing, refactoring code, and updating configuration files.