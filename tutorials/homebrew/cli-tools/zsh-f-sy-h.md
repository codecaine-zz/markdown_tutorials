# zsh-f-sy-h: Alias for Fast Syntax Highlighting (F-Sy-H)

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Note on naming](#3-note-on-naming)
4. [Enable in ~/.zshrc](#4-enable-in-zshrc)
5. [Uninstall](#5-uninstall)

-----

### 1. Overview

Some distributions or listings abbreviate Fast Syntax Highlighting as `zsh-f-sy-h`. It refers to the same project as `zsh-fast-syntax-highlighting` / F-Sy-H.

### 2. Install

Use the main package/formula:

```bash
brew install zsh-fast-syntax-highlighting
```

### 3. Note on naming

The upstream repository is `z-shell/F-Sy-H`. If your environment references `zsh-f-sy-h`, map it to this package.

### 4. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-fast-syntax-highlighting/fast-syntax-highlighting.plugin.zsh
```

### 5. Uninstall

```bash
brew uninstall zsh-fast-syntax-highlighting
```
