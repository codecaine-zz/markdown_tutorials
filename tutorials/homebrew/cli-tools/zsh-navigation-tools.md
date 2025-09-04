# zsh-navigation-tools: FZF-like navigation widgets for zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: n-history, n-cd, n-kill](#4-beginner-n-history-n-cd-n-kill)
5. [Advanced: Customization](#5-advanced-customization)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

`zsh-navigation-tools` provides interactive widgets to browse history, directories, processes, and Git objects.

### 2. Install

```bash
brew install zsh-navigation-tools
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-navigation-tools/zsh-navigation-tools.plugin.zsh
```

### 4. Beginner: n-history, n-cd, n-kill

- `n-history` to browse history
- `n-cd` to change directories interactively
- `n-kill` to pick a process and kill

### 5. Advanced: Customization

- Set environment variables like `NTOOLS_CONFIG` to tweak behavior.
- Rebind widgets with `bindkey` as needed.

### 6. Troubleshooting

- If UI doesn’t render, ensure your terminal supports required capabilities.

### 7. Uninstall

```bash
brew uninstall zsh-navigation-tools
```
