# Tree Directory Viewer Guide

`tree` is a recursive directory listing program that produces a depth-indented listing of files and directories in your terminal. It is invaluable for understanding project layouts, generating documentation, and inspecting folder hierarchies.

---

## 📚 Table of Contents

1. [Overview & Installation](#overview-installation)
2. [Basic Usage & Depth Control](#basic-usage-depth-control)
3. [Hidden Files & Directory Filtering](#hidden-files-directory-filtering)
4. [File Permissions & Size Displays](#file-permissions-size-displays)
5. [Pattern Matching & Excluding Folders](#pattern-matching-excluding-folders)
6. [Gitignore Integration](#gitignore-integration)
7. [HTML & JSON Output Export](#html-json-output-export)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Installation

### Install via Homebrew on macOS
```bash
# Install tree on macOS
brew install tree

# Verify installation
tree --version
```

---

## 🚀 Basic Usage & Depth Control

```bash
# Show complete recursive directory tree
tree

# Limit recursion depth to 1 level (immediate children only)
tree -L 1

# Limit recursion depth to 2 levels
tree -L 2 /Users/username/Projects
```

---

## 📁 Hidden Files & Directory Filtering

### 1. Include Hidden Files (`-a`)
```bash
# Show hidden files and dotfiles (.env, .gitignore, .github)
tree -a -L 2
```

### 2. Directories Only (`-d`)
```bash
# List directory structures only (suppress file listings)
tree -d
```

---

## 📊 File Permissions & Size Displays

### 1. Human-Readable File Sizes (`-h`)
```bash
# Display file size next to each item (e.g. 4.2K, 12M)
tree -h -L 2
```

### 2. File Permissions & Ownership (`-p` / `-u` / `-g`)
```bash
# Show file permissions, owner username, and group
tree -p -u -g -h -L 2
```

### 3. Print Full Path Prefix (`-f`)
```bash
# Print relative or absolute path prefix for each file
tree -f -L 2
```

---

## 🎯 Pattern Matching & Excluding Folders

### 1. Exclude Folders or Wildcards (`-I`)
Exclude massive vendor folders like `node_modules`, `.git`, or build outputs:

```bash
# Exclude node_modules, .git, and dist folders
tree -I "node_modules|.git|dist|vendor" -L 3
```

### 2. Include Matching Wildcard Patterns (`-P`)
```bash
# Show directory tree containing only TypeScript and JSON files
tree -P "*.ts|*.json" --prune
```

---

## 🐙 Gitignore Integration

Automatically respect `.gitignore` rules so untracked node_modules or build artifacts do not clutter your directory output.

```bash
# Ignore files listed in .gitignore
tree --gitignore

# Combine gitignore filtering with hidden files
tree -a --gitignore -L 3
```

---

## 📄 HTML & JSON Output Export

Export directory structure diagrams directly into HTML or JSON for web documentation or programmatic analysis.

### 1. Export as HTML Page (`-H`)
```bash
# Generate clean interactive HTML file tree with clickable links
tree -H . -o project_structure.html
```

### 2. Export as JSON (`-J`)
```bash
# Output directory hierarchy as JSON
tree -J -L 2
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Default tree view | `tree` |
| Max depth 2 levels | `tree -L 2` |
| Include hidden dotfiles | `tree -a` |
| Directories only | `tree -d` |
| Human-readable sizes | `tree -h` |
| Ignore node_modules & git | `tree -I "node_modules\|.git"` |
| Respect `.gitignore` | `tree --gitignore` |
| Export HTML diagram | `tree -H . -o output.html` |
| Export JSON | `tree -J` |