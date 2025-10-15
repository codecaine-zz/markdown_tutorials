# nano: Lightweight Terminal Text Editor

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Beginner: Basics and keybindings](#3-beginner-basics-and-keybindings)
4. [Intermediate: Search/replace, multiple buffers, and syntax highlighting](#4-intermediate-searchreplace-multiple-buffers-and-syntax-highlighting)
5. [Advanced: Configuration, softwrap, backup/undo, and external tools](#5-advanced-configuration-softwrap-backupundo-and-external-tools)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`nano` is a simple, friendly terminal editor—perfect for quick edits, git commit messages, and config files. With a few tweaks, it supports syntax highlighting, soft wrapping, mouse, and more.

### 2\. Install

```bash
brew install nano
```

Check version:

```bash
nano --version
```

Example output:

```text
GNU nano, version 8.0
 (C) 1999-2024 Free Software Foundation, Inc.
```

### 3\. Beginner: Basics and keybindings

Open a file:

```bash
nano notes.txt
```

Common keys (Control = Ctrl, Meta = Alt/Option):

- Ctrl+O: Write Out (save)
- Ctrl+X: Exit
- Ctrl+K: Cut line; Ctrl+U: Paste
- Ctrl+G: Help
- Ctrl+W: Where Is (search)

Example session:

```text
Welcome to nano\n
^G Get Help  ^O Write Out  ^W Where Is  ^K Cut      ^T Execute  ^C Location
^X Exit      ^R Read File  ^\ Replace   ^U Paste    ^J Justify  ^_ Go To Line
```

### 4\. Intermediate: Search/replace, multiple buffers, and syntax highlighting

- Search and replace:

  1. Ctrl+\ to Replace
  2. Type search term → Enter
  3. Type replacement → Enter
  4. Confirm per match (Y/N/A)

- Multiple buffers (open files):

```bash
nano file1.txt file2.txt
```

Switch buffers:

- Alt+< and Alt+> to cycle previous/next buffer

- Syntax highlighting: many `.nanorc` files ship with nano. Enable system defaults in your `~/.nanorc`:

```bash
echo 'include "/opt/homebrew/share/nano/*.nanorc"' >> ~/.nanorc
```

Open a Python file and see colors:

```bash
nano script.py
```

### 5\. Advanced: Configuration, softwrap, backup/undo, and external tools

Sample `~/.nanorc` with quality-of-life improvements:

```nanorc
## Include Homebrew syntax files
include "/opt/homebrew/share/nano/*.nanorc"

## Soft wrap long lines
set softwrap

## Line numbers on the left
set linenumbers

## Mouse support
set mouse

## Show cursor position
set constantshow

## Tabs and whitespace
set tabsize 4
set tabstospaces

## Backups & undo
set backup
set backupdir "~/.local/share/nano/backups"
set historylog

## Search defaults
set casesensitive

## Smooth scrolling
set smooth
```

Create backup directory:

```bash
mkdir -p ~/.local/share/nano/backups
```

Use external formatting tools (example with `fmt`):

- Select lines, then Ctrl+J (Justify) to reflow, or configure a formatter:

```bash
brew install fmt
```

Git commit with nano as editor:

```bash
git config --global core.editor "nano"
```

### 6\. Troubleshooting

- If Option key doesn’t send Meta in Terminal, enable “Use Option as Meta key” in preferences.
- Ensure the Homebrew `nano` is used: `which nano` should show `/opt/homebrew/bin/nano`.
- If syntax colors don’t appear, verify `~/.nanorc` `include` path matches your Homebrew prefix.

### 7\. Uninstall

```bash
brew uninstall nano
```
