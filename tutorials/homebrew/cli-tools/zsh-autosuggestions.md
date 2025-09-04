# zsh-autosuggestions: Fish-like suggestions for zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Accepting suggestions](#4-beginner-accepting-suggestions)
5. [Intermediate: Keybindings and styles](#5-intermediate-keybindings-and-styles)
6. [Advanced: Performance and ordering](#6-advanced-performance-and-ordering)
7. [Troubleshooting](#7-troubleshooting)
8. [Uninstall](#8-uninstall)

-----

### 1\. Overview

`zsh-autosuggestions` suggests commands from your history as you type—accept them with a keypress.

### 2\. Install

```bash
brew install zsh-autosuggestions
```

Plugin manager alternative (antidote):

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load zsh-users/zsh-autosuggestions
```

### 3\. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-autosuggestions/zsh-autosuggestions.zsh
```

Place this before syntax-highlighting plugins.

### 4\. Beginner: Accepting suggestions

Type part of a previous command; a gray suggestion appears.

- Right arrow: accept whole suggestion
- Alt+f: accept next word (if configured)

### 5\. Intermediate: Keybindings and styles

```zsh
ZSH_AUTOSUGGEST_HIGHLIGHT_STYLE='fg=8'   # dim gray
ZSH_AUTOSUGGEST_STRATEGY=(history completion)
```

### 6\. Advanced: Performance and ordering

Source autosuggestions before syntax highlighting for best results. Avoid mixing multiple suggestion frameworks.

### 7\. Troubleshooting

- If suggestions don’t appear, ensure your `$HISTFILE` and history options are set and plugin is sourced.
- Terminal theme may make suggestions invisible; tweak `HIGHLIGHT_STYLE`.

### 8\. Uninstall

```bash
brew uninstall zsh-autosuggestions
```
