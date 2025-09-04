# zsh-autocomplete: As-you-type completions for zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Menu and history completions](#4-beginner-menu-and-history-completions)
5. [Intermediate: Keybindings and styles](#5-intermediate-keybindings-and-styles)
6. [Advanced: Performance tips](#6-advanced-performance-tips)
7. [Troubleshooting](#7-troubleshooting)
8. [Uninstall](#8-uninstall)

-----

### 1. Overview

`zsh-autocomplete` provides fast, context-aware completions as you type, with menus and history suggestions.

### 2. Install

Homebrew (if available):

```bash
brew install zsh-autocomplete
```

Otherwise via plugin manager (antidote):

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load marlonrichert/zsh-autocomplete
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-autocomplete/zsh-autocomplete.plugin.zsh
```

Reload:

```bash
exec zsh -l
```

### 4. Beginner: Menu and history completions

Start typing a command or path to see inline suggestions and a completion menu.

Example:

```text
cd src/compo▮  # menu lists matching directories as you type
```

### 5. Intermediate: Keybindings and styles

Common bindings (may vary by config):

- Tab: accept completion
- Shift+Tab: cycle backwards
- Ctrl+Space: toggle menu

### 6. Advanced: Performance tips

- Keep this plugin early in your sourcing order, before heavy themes.
- Disable slow completions for rarely used commands.

### 7. Troubleshooting

- If completions flicker or lag, try disabling other completion frameworks or configure `compinit` first.

### 8. Uninstall

```bash
brew uninstall zsh-autocomplete
```
