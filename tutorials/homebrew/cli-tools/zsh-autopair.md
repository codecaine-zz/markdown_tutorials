# zsh-autopair: Auto-close quotes and brackets in zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Basic pairing](#4-beginner-basic-pairing)
5. [Troubleshooting](#5-troubleshooting)
6. [Uninstall](#6-uninstall)

-----

### 1. Overview

`zsh-autopair` auto-inserts closing quotes/brackets while typing in the shell, reducing syntax errors.

### 2. Install

```bash
brew install zsh-autopair
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-autopair/autopair.zsh
```

### 4. Beginner: Basic pairing

Type `echo "hello` and the closing quote is inserted automatically.

### 5. Troubleshooting

- If pairing misbehaves, check for conflicting keybindings or widgets.

### 6. Uninstall

```bash
brew uninstall zsh-autopair
```
