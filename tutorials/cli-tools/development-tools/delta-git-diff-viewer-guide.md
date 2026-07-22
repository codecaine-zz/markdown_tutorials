# Delta Git Diff Viewer Guide

`delta` (`git-delta`) is a syntax-highlighting pager for `git`, `diff`, and `grep` output. Written in Rust, `delta` transforms cryptic monochrome git terminal diffs into beautifully formatted side-by-side or unified code views with word-level diff highlighting, syntax colors, line numbers, and navigation shortcuts.

---

## 📚 Table of Contents

1. [Overview & Features](#overview-features)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Configuring Git Integration (`.gitconfig`)](#configuring-git-integration-gitconfig)
4. [Side-by-Side vs Unified Diff Views](#side-by-side-vs-unified-diff-views)
5. [Custom Themes & Color Schemes](#custom-themes-color-schemes)
6. [Using Delta with `diff` & `grep`](#using-delta-with-diff-grep)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Features

- **Word-level Diffing**: Highlights exact character edits within modified code lines.
- **Syntax Highlighting**: Uses Tree-sitter & Bat syntax definitions for 100+ programming languages.
- **Side-by-Side View**: Renders side-by-side diff comparisons directly in terminal viewports.
- **Git Integration**: Works transparently with `git diff`, `git log -p`, `git show`, and `git blame`.

---

## ⚙️ Installation via Homebrew

```bash
# Install git-delta on macOS
brew install git-delta

# Verify installation
delta --version
```

---

## ⚙️ Configuring Git Integration (`.gitconfig`)

Configure Git to automatically route all diffs, logs, and blame outputs through `delta`.

Run CLI setup commands:

```bash
# Set delta as default git pager
git config --global core.pager "delta"

# Enable interactive line numbers and syntax highlighting
git config --global interactive.singleKey true
git config --global delta.navigate true
git config --global delta.line-numbers true
git config --global delta.side-by-side true
```

Or edit `~/.gitconfig` directly:

```ini
[core]
    pager = delta

[interactive]
    singleKey = true

[delta]
    navigate = true    # use n and N to jump between diff files
    line-numbers = true
    side-by-side = true
    syntax-theme = Dracula

[merge]
    conflictstyle = zdiff3
```

---

## 🔀 Side-by-Side vs Unified Diff Views

Toggle between side-by-side split screen diffs and unified inline diffs:

```bash
# Force side-by-side diff view
git diff --side-by-side

# Force unified inline diff view
git diff --no-side-by-side
```

When viewing diffs inside `delta`, press `n` to jump to the next changed file and `N` to jump to the previous changed file.

---

## 🎨 Custom Themes & Color Schemes

`delta` includes built-in syntax themes (e.g., `Dracula`, `Nord`, `GitHub`, `Monokai`, `Solarized-Dark`).

```bash
# List available syntax themes
delta --list-syntax-themes

# Pass temporary theme flag
git diff | delta --syntax-theme "Nord"
```

---

## 🛠️ Using Delta with `diff` & `grep`

Use `delta` to compare plain text files outside of Git repositories.

```bash
# Compare two independent files with side-by-side diffing
delta file1.py file2.py

# Pipe plain unified diff output through delta
diff -u old.txt new.txt | delta
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Default Git Diff | `git diff` (uses delta automatically) |
| Git Show Commit | `git show <commit-hash>` |
| Side-by-Side Mode | `git diff --side-by-side` |
| Jump between Files | Press `n` / `N` inside pager |
| List Themes | `delta --list-syntax-themes` |
| File Comparison | `delta fileA.js fileB.js` |
