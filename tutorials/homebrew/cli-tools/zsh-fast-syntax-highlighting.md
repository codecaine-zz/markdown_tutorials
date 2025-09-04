# zsh-fast-syntax-highlighting: Fast syntax colors for zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Basic highlighting](#4-beginner-basic-highlighting)
5. [Advanced: Performance and themes](#5-advanced-performance-and-themes)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`zsh-fast-syntax-highlighting` provides blazing-fast syntax highlighting for your zsh command line. It colorizes commands, options, paths, and flags as you type.

### 2\. Install

```bash
brew install zsh-fast-syntax-highlighting
```

Alternative (plugin manager):

```zsh
# antidote
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load z-shell/F-Sy-H
```

### 3\. Enable in ~/.zshrc

Source it at the end of your zsh config (after autosuggestions):

```zsh
# Syntax highlighting (fast)
source $(brew --prefix)/share/zsh-fast-syntax-highlighting/fast-syntax-highlighting.plugin.zsh
```

Reload:

```bash
exec zsh -l
```

### 4\. Beginner: Basic highlighting

Type commands and see live colors:

```text
eza -lah --git  # options in one color, command in another; errors show red underline
git commit -m "feat: init"  # strings highlighted
./script.sh  # executable path highlighted
```

### 5\. Advanced: Performance and themes

- Keep this plugin last among UI tools to avoid conflicts.
- Combine with `zsh-autosuggestions` and `zsh-completions` for a rich UX.
- To tweak styles, export `FAST_HIGHLIGHT_STYLES` in your env; see the project docs for keys.

### 6\. Troubleshooting

- If colors don’t show, ensure this is sourced after `zsh-autosuggestions`.
- On errors about `autoload`, make sure you’re running a recent zsh (5.8+).

### 7\. Uninstall

```bash
brew uninstall zsh-fast-syntax-highlighting
```
