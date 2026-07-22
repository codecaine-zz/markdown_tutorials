# Nano Terminal Text Editor Guide

`GNU nano` is a lightweight, intuitive command-line text editor designed for quick code edits, configuration tweaks, and git commit messages. Unlike modal editors like Vim or Neovim, `nano` features a straightforward model where text is edited directly and keybindings are displayed at the bottom of the screen.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview-prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Essential Keybindings](#basic-usage-essential-keybindings)
4. [Search & Replace Operations](#search-replace-operations)
5. [Line Numbers, Soft Wrapping & Mouse Support](#line-numbers-soft-wrapping-mouse-support)
6. [Multi-File Editing (Buffers)](#multi-file-editing-buffers)
7. [Customizing `~/.nanorc` & Syntax Highlighting](#customizing-nanorc-syntax-highlighting)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

While macOS includes a default system `nano` binary at `/usr/bin/nano`, installing updated GNU Nano via Homebrew provides modern syntax highlighting definitions, soft word wrapping, smooth scrolling, and multi-level undo/redo.

---

## ⚙️ Installation via Homebrew

```bash
# Install latest GNU Nano via Homebrew on macOS
brew install nano

# Verify version
nano --version
```

---

## 🚀 Basic Usage & Essential Keybindings

Open a file in `nano`:
```bash
# Open or create a file
nano config.json

# Open file directly at line 45
nano +45 app.py
```

### Essential Navigation & Editing Shortcuts

In `nano` documentation, `^` represents the **Control key (`Ctrl`)** and `M-` represents the **Meta key (`Option` or `Alt` on macOS)**.

| Keybinding | Action |
| --- | --- |
| `Ctrl+O` | **WriteOut**: Save changes to file |
| `Ctrl+X` | **Exit**: Close editor (prompts to save if modified) |
| `Ctrl+K` | **Cut**: Cut current line into clipboard buffer |
| `Ctrl+U` | **Uncut**: Paste text from clipboard buffer |
| `Ctrl+W` | **Where Is**: Search for text |
| `Ctrl+\` | **Replace**: Search and replace text |
| `Ctrl+_` | **Go To Line**: Jump to specific line and column number |
| `Alt+A` | **Set Mark**: Start text selection mode |
| `Alt+U` | **Undo**: Undo last text operation |
| `Alt+E` | **Redo**: Redo last undone operation |

---

## 🔍 Search & Replace Operations

### 1. Search for Text (`Ctrl+W`)
1. Press `Ctrl+W`
2. Type search string and press `Enter`
3. Press `Alt+W` to jump to the next matching instance

### 2. Search & Replace (`Ctrl+\`)
1. Press `Ctrl+\`
2. Enter target text to search for $\rightarrow$ Press `Enter`
3. Enter replacement text $\rightarrow$ Press `Enter`
4. Press `Y` to replace current instance, `N` to skip, or `A` to replace all

---

## ⚙️ Line Numbers, Soft Wrapping & Mouse Support

Launch `nano` with flags for enhanced coding comfort:

```bash
# Open file with line numbers (-N), soft word wrap (-$), and mouse support (-m)
nano -N -$ -m app.py
```

- `-N` / `--linenumbers`: Show line numbers on left margin.
- `-$` / `--softwrap`: Wrap long lines smoothly across lines.
- `-m` / `--mouse`: Enable mouse clicking to position cursor and scroll.

---

## 📁 Multi-File Editing (Buffers)

Open multiple files simultaneously and cycle between them:

```bash
# Open multiple files into separate buffers
nano -F main.py utils.py config.py
```

- Switch to Next Buffer: `Alt+.` (or `Alt+>`)
- Switch to Previous Buffer: `Alt+,` (or `Alt+<`)

---

## 🎨 Customizing `~/.nanorc` & Syntax Highlighting

Create a persistent configuration file at `~/.nanorc` to automatically enable line numbers, mouse support, auto-indentation, and syntax highlighting for languages like Python, JavaScript, HTML, C, Go, and Shell scripts.

```bash
cat << 'EOF' > ~/.nanorc
## Display line numbers
set linenumbers

## Enable soft word wrapping
set softwrap

## Enable mouse support (cursor positioning & scrolling)
set mouse

## Auto-indent new lines to match preceding indentation
set autoindent

## Convert typed tabs to spaces (4 spaces per tab)
set tabstospaces
set tabsize 4

## Preserve cut buffer across multiple cuts
set cutfromcursor

## Show constant line and column cursor position indicator
set constantshow

## Enable multi-level undo/redo
set undo

## Include all Homebrew syntax highlighting files
include "/opt/homebrew/share/nano/*.nanorc"
EOF
```

---

## 📋 Cheat Sheet Summary

| Task | Shortcut / Flag |
| --- | --- |
| Save File | `Ctrl+O` |
| Exit Editor | `Ctrl+X` |
| Cut Line | `Ctrl+K` |
| Paste Line | `Ctrl+U` |
| Search Text | `Ctrl+W` |
| Replace Text | `Ctrl+\` |
| Jump to Line Number | `Ctrl+_` |
| Set Selection Mark | `Alt+A` |
| Open with Line Numbers | `nano -N file.py` |
| Enable Mouse Mode | `nano -m file.py` |
