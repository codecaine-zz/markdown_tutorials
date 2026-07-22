# Helix: Post-Modern Modal Text Editor

## Table of Contents

1. [What is `helix`?](#1-what-is-helix)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Core Philosophy: Selection-First Modal Editing](#4-core-philosophy-selection-first-modal-editing)
5. [Basic Editing Commands & Keybindings](#5-basic-editing-commands-keybindings)
6. [LSP & Syntax Highlighting Out-of-the-Box](#6-lsp-syntax-highlighting-out-of-the-box)
7. [Configuring Helix (`config.toml`)](#7-configuring-helix-configtoml)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `helix`?

`helix` (`hx`) is a post-modern modal text editor written in Rust. Inspired by Neovim and Kakoune, `helix` comes with built-in Language Server Protocol (LSP) support, Tree-sitter syntax highlighting, fuzzy file searching, auto-complete, and multi-cursor editing out of the box with zero plugin configuration required.

#### Key Advantages
* **Zero Plugin Setup Needed:** Pre-configured LSP, syntax highlighting, formatting, and file picker work out of the box.
* **Selection-First Editing:** Unlike Vim (`action -> movement`), Helix uses Kakoune's `selection -> action` paradigm, showing visual feedback before executing an action.
* **Pure Rust Performance:** Instant startup time on macOS Apple Silicon.

---

### 2. Prerequisites

Verify Homebrew on your ARM Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `helix` via Homebrew:

```bash
brew install helix
```

Verify installation:

```bash
which hx
hx --version
```

**Expected Output:**
```text
/opt/homebrew/bin/hx
helix 24.x (or latest)
```

---

### 4. Core Philosophy: Selection-First Modal Editing

In Vim, to delete a word, you type `dw` (delete -> word).
In Helix, you select the word first, then operate: `w` (select word) -> `d` (delete).

This visual feedback loop prevents accidental edits because you always see what text is targeted before executing a command.

---

### 5. Basic Editing Commands & Keybindings

To open a file or directory with Helix:

```bash
hx filename.txt
hx .
```

#### Modes in Helix
* **Normal Mode (`Esc`):** Navigate and select text.
* **Insert Mode (`i` / `a`):** Type text.
* **Select Mode (`v`):** Extend visual selection.
* **Space Mode (`Space`):** Open global action menu (file picker, buffer list, LSP diagnostics).

#### Navigation & Selection Controls

| Key | Action |
|---|---|
| `h` / `j` / `k` / `l` | Move left / down / up / right |
| `w` | Select forward word |
| `b` | Select backward word |
| `x` | Select entire current line |
| `d` | Delete selection |
| `y` | Yank (copy) selection |
| `p` | Paste selection |
| `u` / `U` | Undo / Redo |
| `Space` + `f` | Open interactive file picker (like `fzf`) |
| `Space` + `b` | Open active buffers picker |
| `:` | Open command bar (`:w` save, `:q` quit) |

---

### 6. LSP & Syntax Highlighting Out-of-the-Box

To check built-in LSP language support and missing language servers on your system:

```bash
hx --health
```

**Example Output:**
```text
Language      Syntax      LSP                 Formatter
python        ✓           pyright (installed) ruff
rust          ✓           rust-analyzer       rustfmt
typescript    ✓           typescript-language-server
```

#### LSP Keybindings inside Helix
* `g` `d`: Go to Definition
* `g` `r`: Find References
* `r`: Rename Symbol
* `K`: Hover Documentation popup
* `Space` + `a`: Code Actions / Quick Fixes

---

### 7. Configuring Helix (`config.toml`)

Helix configuration is stored at `~/.config/helix/config.toml`:

```bash
mkdir -p ~/.config/helix
touch ~/.config/helix/config.toml
```

Example configuration file:

```toml
theme = "catppuccin_mocha"

[editor]
line-number = "relative"
mouse = true
cursorline = true
bufferline = "multiple"

[editor.cursor-shape]
insert = "bar"
normal = "block"
select = "underline"

[editor.file-picker]
hidden = false

[editor.indent-guides]
render = true
```

---

### 8. Uninstallation

```bash
brew uninstall helix
rm -rf ~/.config/helix
```
