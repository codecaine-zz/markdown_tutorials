# zsh-syntax-highlighting: Syntax colors for zsh commands

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Basic highlighting](#4-beginner-basic-highlighting)
5. [Advanced: Ordering and styles](#5-advanced-ordering-and-styles)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`zsh-syntax-highlighting` colorizes commands, options, and errors as you type.

### 2\. Install

```bash
brew install zsh-syntax-highlighting
```

### 3\. Enable in ~/.zshrc

Source it last among your interactive plugins:

```zsh
source $(brew --prefix)/share/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh
```

### 4\. Beginner: Basic highlighting

Type an invalid command and see it marked as an error; valid commands are colored.

### 5\. Advanced: Ordering and styles

- Source after `zsh-autosuggestions` for correct coloring.
- Customize styles via `ZSH_HIGHLIGHT_STYLES` variables.

### 6\. Troubleshooting

- If nothing changes, ensure your terminal theme supports the colors.
- Conflicts with other highlighters: use one highlighter at a time or disable overlaps.

### 7\. Uninstall

```bash
brew uninstall zsh-syntax-highlighting
```
