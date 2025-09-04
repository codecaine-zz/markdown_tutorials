# zsh-vi-mode: Vim-style editing in zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Normal/Insert mode basics](#4-beginner-normalinsert-mode-basics)
5. [Intermediate: Word motions, deletes, and edits](#5-intermediate-word-motions-deletes-and-edits)
6. [Advanced: Custom keymaps and widgets](#6-advanced-custom-keymaps-and-widgets)
7. [Troubleshooting](#7-troubleshooting)
8. [Uninstall](#8-uninstall)

-----

### 1\. Overview

`zsh-vi-mode` enables Vim’s modal editing in zsh prompts: navigate with hjkl, edit with objects/motions, and switch modes like in Vim.

### 2\. Install

```bash
brew install zsh-vi-mode
```

### 3\. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-vi-mode/zsh-vi-mode.plugin.zsh
```

Optional: Show mode indicator in your prompt (Powerlevel10k supports vi mode); or add a minimal indicator:

```zsh
function zle-keymap-select() { RPS1="[% NORMAL ]"; [[ $KEYMAP == vicmd ]] || RPS1="[% INSERT ]"; zle reset-prompt }
zle -N zle-keymap-select
```

### 4\. Beginner: Normal/Insert mode basics

- Press Esc to enter Normal mode; i to go back to Insert.
- Move with h j k l, 0, $, w, b, e.
- Edit: x (delete char), u (undo), Ctrl+r (redo).

Example:

```text
echo hello world␣  # press Esc, then bdw, type 'universe' → Enter
→ echo hello universe
```

### 5\. Intermediate: Word motions, deletes, and edits

- Change a word: `ciw`
- Delete to end: `d$`
- Yank/paste: `yy`, `p`

### 6\. Advanced: Custom keymaps and widgets

Map a custom widget:

```zsh
my-cd-up() { cd ..; zle reset-prompt }
zle -N my-cd-up
bindkey -M vicmd 'gu' my-cd-up
```

### 7\. Troubleshooting

- If keybindings don’t work, ensure no conflicting `bindkey -v` earlier.
- Some terminals require Esc delay tuning for responsiveness.

### 8\. Uninstall

```bash
brew uninstall zsh-vi-mode
```
