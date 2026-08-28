# Zellij Modern Terminal Multiplexer Guide

`zellij` is a modern, feature-packed terminal workspace and multiplexer written in Rust. Designed as an intuitive, user-friendly alternative to `tmux` and `screen`, `zellij` provides interactive pane layouts, tabs, floating windows, built-in session resurrection, customizable status bars, and WebAssembly plugin support without requiring complex config files out of the box.

---

## 📚 Table of Contents

1. [Overview & `tmux` vs `zellij` Comparison](#overview--tmux-vs-zellij-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Starting Zellij & Interface Overview](#starting-zellij--interface-overview)
4. [Modes & Keyboard Navigation (Pane, Tab, Resize, Scroll)](#modes--keyboard-navigation-pane-tab-resize-scroll)
5. [Floating Panes & Stacked Layouts](#floating-panes--stacked-layouts)
6. [Session Management & Resurrection](#session-management--resurrection)
7. [Custom Layouts (KDL Format)](#custom-layouts-kdl-format)
8. [Configuration & Theme Customization](#configuration--theme-customization)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `tmux` vs `zellij` Comparison

Unlike `tmux`, which requires memorizing obscure modifier combos (`Ctrl+b` followed by `%`, `"`, or `[`), `zellij` shows an interactive keybinding helper bar directly at the bottom of the screen.

| Feature | Classic `tmux` | `zellij` |
| :--- | :--- | :--- |
| **Language** | C | Rust |
| **On-Screen Key Hints** | None | Built-in contextual footer bar |
| **Floating Panes** | Complex popups | Native first-class floating windows |
| **Session Resurrection** | Requires third-party plugins (`tmux-resurrect`) | Built-in native session restore |
| **Layouts System** | Shell script wrappers | Declarative KDL layout files |
| **Plugins** | Shell scripts | WebAssembly (Wasm) sandbox |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install zellij
```

### 2. Install via Cargo

```bash
cargo install --locked zellij
```

### 3. Verify Installation

```bash
zellij --version
```

---

## 🚀 Starting Zellij & Interface Overview

Launch a fresh Zellij workspace:

```bash
# Start default session
zellij

# Start or attach to a named session
zellij attach --create my-workspace
```

### The Interface Layout:

- **Top Bar**: Shows active tabs and session name.
- **Center**: Active terminal panes (tiled or floating).
- **Bottom Bar**: Contextual keyboard shortcut bar that adapts to your current mode (e.g. `LOCKED`, `PANE`, `TAB`, `RESIZE`, `SCROLL`, `SESSION`).

---

## ⌨️ Modes & Keyboard Navigation

Zellij uses modal navigation. Press `Ctrl + <mode_key>` to enter a mode, perform actions, and press `Esc` or `Enter` to return to default:

### Core Modes:
- `Ctrl + p` : **PANE** mode (create, split, close, float panes)
- `Ctrl + t` : **TAB** mode (new tab, rename, switch tabs)
- `Ctrl + n` : **RESIZE** mode (grow, shrink panes)
- `Ctrl + s` : **SCROLL** mode (scrollback search, edit buffer)
- `Ctrl + o` : **SESSION** mode (detach, switch sessions)
- `Ctrl + g` : **LOCKED** mode (pass all keystrokes directly to the inner app like Neovim)

### Common Quick Shortcuts (from normal mode):
- `Alt + n` : New pane
- `Alt + h` / `j` / `k` / `l` or `Alt + Arrow` : Move focus between panes
- `Alt + [` / `Alt + ]` : Switch to previous / next tab
- `Alt + f` : Toggle floating mode for current pane

---

## 🪟 Floating Panes & Stacked Layouts

### 1. Toggle Floating Window

Press `Ctrl + p` then `w` (or `Alt + f`) to detach the current pane into a centered floating window.

### 2. Move & Resize Floating Windows

- Press `Ctrl + p` then `e` to embed the floating pane back into the tile grid.
- Press `Ctrl + n` then `+` / `-` to expand or shrink floating window dimensions.

---

## 💾 Session Management & Resurrection

Zellij automatically saves session layouts so you can detach and restore them even after system restarts.

```bash
# List all active and resurrectable sessions
zellij list-sessions

# Attach to an existing session
zellij attach dev-project

# Kill a specific session
zellij kill-session dev-project

# Delete all inactive / dead sessions
zellij delete-all-sessions
```

---

## 📐 Custom Layouts (KDL Format)

Create custom multi-pane development environments using declarative KDL layout files.

Save as `~/.config/zellij/layouts/dev.kdl`:

```kdl
layout {
    pane split_direction="vertical" {
        pane size="65%" name="Editor" command="nvim"
        pane split_direction="horizontal" {
            pane size="50%" name="Server" command="npm" {
                args "run" "dev"
            }
            pane size="50%" name="Terminal"
        }
    }
    pane size=1 borderless=true {
        plugin location="zellij:compact-bar"
    }
}
```

Launch with your custom layout:

```bash
zellij --layout dev
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Goal | Shortcut / Command |
| :--- | :--- |
| **New Pane** | `Alt + n` |
| **Close Pane** | `Ctrl + p` then `x` |
| **Toggle Floating** | `Alt + f` |
| **New Tab** | `Ctrl + t` then `n` |
| **Rename Tab** | `Ctrl + t` then `r` |
| **Detach Session** | `Ctrl + o` then `d` |
| **List Sessions** | `zellij list-sessions` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Zellij shortcuts
alias zj='zellij'
alias za='zellij attach --create'
alias zl='zellij list-sessions'
alias zk='zellij kill-session'
```

---

## 🗑️ Uninstallation

To remove `zellij`:

```bash
brew uninstall zellij
```
