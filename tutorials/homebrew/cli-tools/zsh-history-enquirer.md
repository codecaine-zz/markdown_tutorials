# zsh-history-enquirer: Interactive history search for zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Basic usage](#4-beginner-basic-usage)
5. [Advanced: Widgets and keybindings](#5-advanced-widgets-and-keybindings)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`zsh-history-enquirer` adds an interactive, incremental search UI for shell history, similar to fzf/atuin but built as a zle widget.

### 2\. Install

```bash
brew install zsh-history-enquirer
```

### 3\. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-history-enquirer/history-enquirer.plugin.zsh
bindkey '^R' history-enquirer-widget
```

### 4\. Beginner: Basic usage

Press Ctrl+R and start typing; use arrow keys to select and Enter to accept.

### 5\. Advanced: Widgets and keybindings

Map to another key if Ctrl+R is used by fzf or atuin:

```zsh
bindkey '^S' history-enquirer-widget
```

### 6\. Troubleshooting

- Conflicts with other history search tools—ensure only one is bound to Ctrl+R.
- Slow with huge histories? Prune or rotate history periodically.

### 7\. Uninstall

```bash
brew uninstall zsh-history-enquirer
```
