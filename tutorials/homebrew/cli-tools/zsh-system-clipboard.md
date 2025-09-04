# zsh-system-clipboard: Copy/paste integration with system clipboard

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Keybindings](#4-beginner-keybindings)
5. [Advanced: tmux and remote sessions](#5-advanced-tmux-and-remote-sessions)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`zsh-system-clipboard` syncs zsh’s kill/yank (cut/copy/paste) with your OS clipboard using pbcopy/pbpaste on macOS.

### 2\. Install

```bash
brew install zsh-system-clipboard
```

### 3\. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-system-clipboard/zsh-system-clipboard.zsh
```

### 4\. Beginner: Keybindings

- Ctrl+k: kill to end (copied to system clipboard)
- Ctrl+y: yank from system clipboard

Example:

```text
echo hello world▮  # Ctrl+k, then Ctrl+y → line restored via system clipboard
```

### 5\. Advanced: tmux and remote sessions

- With tmux, ensure `set -g set-clipboard on` to forward to the system clipboard.
- Remote SSH sessions may require OSC52 escape sequences or `mosh` for clipboard sync.

### 6\. Troubleshooting

- Verify `pbcopy/pbpaste` exist on macOS.
- Other clipboard plugins can conflict; load only one.

### 7\. Uninstall

```bash
brew uninstall zsh-system-clipboard
```
