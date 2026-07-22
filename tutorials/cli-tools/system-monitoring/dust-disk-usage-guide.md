# Dust (du Replacement) Disk Usage Guide

`dust` (`du-dust`) is a modern, intuitive, graphical command-line disk space analyzer written in Rust. Designed to replace traditional `du`, `dust` presents folder sizes as interactive, ASCII-bar visual trees, making it instantly clear which files or subdirectories consume the most disk space.

---

## 📚 Table of Contents

1. [Overview & `du` vs `dust` Comparison](#overview-du-vs-dust-comparison)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Terminal Output](#basic-usage-terminal-output)
4. [Depth Control & Appending Limits](#depth-control-appending-limits)
5. [Filtering by File Type & Extensions](#filtering-by-file-type-extensions)
6. [Sorting & Color Options](#sorting-color-options)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & `du` vs `dust` Comparison

Comparing traditional `du` with `dust`:

- **Traditional `du -sh *`**: Outputs unsorted, wall-of-text list requiring manual sorting (`du -sh * | sort -h`).
- **`dust`**: Automatically sorts subfolders by size, renders colored horizontal visual bar charts, and highlights massive folders.

---

## ⚙️ Installation via Homebrew

```bash
# Install dust on macOS
brew install dust

# Verify installation
dust --version
```

---

## 🚀 Basic Usage & Terminal Output

Run `dust` inside any directory to inspect disk utilization:

```bash
# Inspect current directory
dust

# Inspect specific folder path
dust /Users/username/Projects
```

Sample Terminal Output:

```text
540M   ┌─ node_modules
320M   ├─ dist
 12M   ├─ src
 85M   ├─ .git
957M ┌─ my-web-app
```

---

## 🎛️ Depth Control & Appending Limits

Restrict directory recursion depth so output fits cleanly on small terminal viewports.

```bash
# Limit visual depth to 1 level (immediate children only)
dust -d 1

# Limit visual depth to 2 levels
dust -d 2 ~/Downloads

# Show top 15 largest items only
dust -n 15
```

---

## 🎯 Filtering by File Type & Extensions

Isolate specific file types (e.g. video files, node_modules, log files).

```bash
# Show disk space used only by .mp4 and .mkv files (-e)
dust -e mp4 -e mkv ~/Movies

# Exclude specific directories (-X)
dust -X node_modules -X .git

# Show file sizes instead of combining into directory totals (-f)
dust -f ~/Documents
```

---

## 🎨 Sorting & Color Options

```bash
# Reverse sort order (smallest to largest)
dust -r

# Display plain monochrome output (no ANSI colors)
dust -c

# Ignore hidden files and dot-folders (-i)
dust -i
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Default visual disk analyzer | `dust` |
| Limit depth to 1 level | `dust -d 1` |
| Show top 10 items | `dust -n 10` |
| Inspect specific path | `dust /path/to/folder` |
| Filter by file extension | `dust -e zip -e iso` |
| Exclude node_modules | `dust -X node_modules` |
| Show individual files | `dust -f` |
