# macOS Tiling Window Managers (AeroSpace & Yabai) Guide

Tiling window managers organize your application windows automatically into non-overlapping grids, splits, and columns. For developers moving from Linux i3/bspwm or seeking keyboard-driven productivity on macOS, **AeroSpace** and **yabai** deliver powerful tiling window management.

---

## 📚 Table of Contents

1. [Overview & Why Use Tiling on macOS?](#overview--why-use-tiling-on-macos)
2. [AeroSpace vs Yabai Comparison](#aerospace-vs-yabai-comparison)
3. [Installing & Configuring AeroSpace (Recommended)](#installing--configuring-aerospace-recommended)
4. [AeroSpace Workspaces & Keybindings](#aerospace-workspaces--keybindings)
5. [Installing & Configuring Yabai + Skhd](#installing--configuring-yabai--skhd)
6. [Visual Accents: JankyBorders](#visual-accents-jankyborders)
7. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
8. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use Tiling on macOS?

Default macOS window management requires constantly dragging, resizing, minimizing, and organizing windows with the trackpad. Tiling window managers provide:

- **Automatic Space Partitioning**: Opening a new terminal or browser split-tiles the screen instantly.
- **Keyboard-Driven Workspaces**: Jump to virtual desktop workspaces (`Alt + 1..9` or `Alt + a..z`) with zero animation latency.
- **Zero Distractions**: No overlapping, lost, or hidden background windows.

---

## ⚖️ AeroSpace vs Yabai Comparison

| Feature | AeroSpace | Yabai |
| :--- | :--- | :--- |
| **System Integrity Protection (SIP)** | Fully works with SIP **ENABLED** | Full features require disabling SIP |
| **Layout Tree** | i3-like tree structure | Binary Space Partitioning (BSP) |
| **Virtual Workspaces** | Native custom workspaces (no macOS Space limits) | Emulates macOS Mission Control Spaces |
| **Hotkey Daemon** | Built-in keybinding engine | Requires separate `skhd` daemon |
| **Setup Complexity** | Single config file (`aerospace.toml`) | Multi-service setup |

---

## 🚀 Installing & Configuring AeroSpace (Recommended)

AeroSpace is an i3-inspired tiling window manager for macOS that requires **zero SIP modification**.

### 1. Install via Homebrew

```bash
brew tap nikitabobko/tap
brew install --cask aerospace
```

### 2. Grant macOS Accessibility Permissions

Open **System Settings > Privacy & Security > Accessibility** and enable **AeroSpace**.

### 3. Initialize Default Configuration

Create the configuration file at `~/.aerospace.toml`:

```bash
touch ~/.aerospace.toml
```

### 4. Example `~/.aerospace.toml` Configuration:

```toml
# Use Alt (Option) as leader modifier
accordion-padding = 30
default-root-container-layout = 'tiles'
default-root-container-orientation = 'horizontal'

[gaps]
inner.horizontal = 8
inner.vertical = 8
outer.left = 10
outer.bottom = 10
outer.top = 10
outer.right = 10

# Main Mode Keybindings
[mode.main.binding]
# Focus Navigation
alt-h = 'focus left'
alt-j = 'focus down'
alt-k = 'focus up'
alt-l = 'focus right'

# Move Windows
alt-shift-h = 'move left'
alt-shift-j = 'move down'
alt-shift-k = 'move up'
alt-shift-l = 'move right'

# Workspace Switching (1 through 5)
alt-1 = 'workspace 1'
alt-2 = 'workspace 2'
alt-3 = 'workspace 3'
alt-4 = 'workspace 4'
alt-5 = 'workspace 5'

# Move window to workspace
alt-shift-1 = 'move-node-to-workspace 1'
alt-shift-2 = 'move-node-to-workspace 2'
alt-shift-3 = 'move-node-to-workspace 3'

# Toggle Layouts
alt-slash = 'layout tiles horizontal vertical'
alt-comma = 'layout accordion horizontal vertical'
alt-f = 'fullscreen'
alt-shift-space = 'layout floating tiling'
```

---

## ⌨️ AeroSpace Workspaces & Keybindings

With the configuration above, manage your entire desktop entirely via the keyboard:

- `Alt + 1` / `2` / `3` : Jump instantly to Workspace 1, 2, or 3.
- `Alt + h` / `j` / `k` / `l` : Focus adjacent windows (vim directional keys).
- `Alt + Shift + h` / `j` / `k` / `l` : Swap/move windows.
- `Alt + f` : Toggle fullscreen mode for the active application.
- `Alt + Shift + Space` : Float utility windows (e.g. 1Password, Calculator).

---

## 🪟 Installing & Configuring Yabai + Skhd

If you prefer BSP tree layouts and have SIP configured:

```bash
# Install yabai window manager and skhd hotkey daemon
brew install koekeishiya/formulae/yabai
brew install koekeishiya/formulae/skhd

# Start background services
brew services start yabai
brew services start skhd
```

---

## 🎨 Visual Accents: JankyBorders

Add colored borders around the currently focused active window:

```bash
# Install JankyBorders
brew tap FelixKratz/formulae
brew install borders

# Start service with modern gradient active borders
brew services start borders
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Goal | Shortcut |
| :--- | :--- |
| **Switch Workspace** | `Alt + 1..9` |
| **Move Window to Workspace** | `Alt + Shift + 1..9` |
| **Focus Left / Down / Up / Right** | `Alt + h / j / k / l` |
| **Move Left / Down / Up / Right** | `Alt + Shift + h / j / k / l` |
| **Toggle Fullscreen** | `Alt + f` |
| **Toggle Floating Mode** | `Alt + Shift + Space` |
| **Reload Config** | `aerospace reload-config` |

---

## 🗑️ Uninstallation

To remove AeroSpace:

```bash
brew uninstall --cask aerospace
```
