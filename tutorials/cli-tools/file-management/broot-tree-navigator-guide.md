# Broot Modern Directory Tree Navigator Guide

`broot` is an innovative, fast directory tree navigator written in Rust. Designed to replace traditional `tree` commands and clunky terminal directory browsing, `broot` provides a constant-height overview of any directory tree (even massive ones) without scrolling off your screen, featuring instant fuzzy searching, visual file sizes, and shell integration for rapid navigation.

---

## 📚 Table of Contents

1. [Overview & `tree` vs `broot` Comparison](#overview-tree-vs-broot-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Shell Integration & Setup (`br` Function)](#shell-integration-setup-br-function)
4. [Interactive Navigation & Fuzzy Search](#interactive-navigation-fuzzy-search)
5. [Disk Space & Size Analysis Mode (`-s` / `--sizes`)](#disk-space-size-analysis-mode--s---sizes)
6. [Git Status & Permissions Modes](#git-status-permissions-modes)
7. [Verbs, Shortcuts & File Operations](#verbs-shortcuts-file-operations)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `tree` vs `broot` Comparison

Running `tree` on large directories floods your terminal with thousands of unreadable lines. `broot` fits directory trees intelligently into the available terminal height using smart pruning.

| Feature | Classic `tree` | `broot` |
| :--- | :--- | :--- |
| **Output Size** | Floods entire screen | Adapts dynamically to terminal height |
| **Search / Filter** | Static grep required | Live real-time fuzzy search |
| **Directory Navigation** | Read-only text | Navigates & changes terminal working directory |
| **File Preview** | None | Built-in file preview pane |
| **Disk Usage** | Basic | Colorized size bars with percentage |
| **Git Integration** | None | Live git status indicators (modified/untracked) |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install broot
```

### 2. Install via Cargo

```bash
cargo install broot
```

### 3. Verify Installation

```bash
broot --version
```

---

## 🐚 Shell Integration & Setup (`br` Function)

To allow `broot` to change your active terminal working directory when you select a folder, install the shell wrapper:

```bash
# Run broot to trigger automatic shell integration setup
broot
```

When prompted, press `y` to install the `br` shell function into your `~/.zshrc` or `~/.bashrc`. Then reload your shell:

```bash
source ~/.zshrc
```

Now you can use `br` instead of `broot` to navigate and jump between directories.

---

## 🚀 Interactive Navigation & Fuzzy Search

Launch `broot` in the current folder or a specific directory:

```bash
# Launch broot with shell cd support
br

# Launch on a specific path
br /Users/username/Projects
```

### Live Fuzzy Search

Simply start typing letters while `broot` is open. The tree dynamically filters in real-time:

- Type `main` to find `main.rs` or `main.py`
- Type `c/` to filter for directories only
- Type `!png` to exclude `.png` files
- Press `Esc` to clear search or exit

---

## 📊 Disk Space & Size Analysis Mode (`-s` / `--sizes`)

View directory sizes and storage consumption directly inside the tree:

```bash
# Launch with disk usage calculations enabled
br -s
# Or press :s inside broot
```

### Visual Output:

```text
 1.2 GB ──┬─ node_modules ────── [████████████████████]
 350 MB   ├─ dist ────────────── [█████               ]
  45 MB   ├─ src ─────────────── [█                   ]
   2 KB   └─ package.json
```

---

## 🌿 Git Status & Permissions Modes

### 1. Show Git Status (`-g` / `--git-status`)

Display only files that are untracked, modified, or staged in the current Git repository:

```bash
br -g
```

### 2. Show File Permissions & Dates (`-d` / `-p`)

```bash
# Show file modification dates
br -d

# Show Unix permissions and ownership
br -p
```

---

## ⚡ Verbs, Shortcuts & File Operations

In `broot`, you trigger actions using **verbs** (prefixed with a colon `:` or keyboard shortcut):

| Action / Verb | Shortcut / Command | Description |
| :--- | :--- | :--- |
| **Open / Edit** | `Enter` | Opens file in default editor / `$EDITOR` |
| **Change Directory** | `Alt + Enter` | Exits `br` and `cd`s to selected directory |
| **Preview File** | `Ctrl + →` or `:preview` | Opens interactive preview panel |
| **Delete Item** | `:rm` | Safely removes selected file/folder |
| **Create File** | `:touch <name>` | Creates a new file in selected folder |
| **Create Folder** | `:mkdir <name>` | Creates a new subfolder |
| **Toggle Hidden** | `Ctrl + h` / `:hidden` | Shows / hides dotfiles |
| **Quit** | `q` or `Esc` | Exits `broot` |

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Navigate directory** | `br` |
| **Show sizes** | `br -s` |
| **Show Git changes only** | `br -g` |
| **Show hidden files** | `br -h` |
| **Show dates & permissions** | `br -dp` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Broot shortcuts
alias tree='br'
alias tree-sizes='br -s'
alias tree-git='br -g'
```

---

## 🗑️ Uninstallation

To remove `broot`:

```bash
brew uninstall broot
```
