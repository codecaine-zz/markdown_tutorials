# zsh-history-substring-search: Search history for matching substrings

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Up/Down substring search](#4-beginner-updown-substring-search)
5. [Intermediate: Case sensitivity and styles](#5-intermediate-case-sensitivity-and-styles)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

Use the up/down keys to cycle through history entries that contain the current buffer as a substring.

### 2. Install

```bash
brew install zsh-history-substring-search
```

Or via plugin manager:

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load zsh-users/zsh-history-substring-search
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-history-substring-search/zsh-history-substring-search.zsh

# Bind keys (zle)
bindkey '^[[A' history-substring-search-up
bindkey '^[[B' history-substring-search-down
```

### 4. Beginner: Up/Down substring search

Type a few letters, then press Up/Down to cycle matching commands.

Example:

```text
brew i▮  # press Up → shows prior commands that contain "brew i"
```

### 5. Intermediate: Case sensitivity and styles

```zsh
HISTORY_SUBSTRING_SEARCH_ENSURE_UNIQUE=1
HISTORY_SUBSTRING_SEARCH_FUZZY=1
```

### 6. Troubleshooting

- Ensure bindings don’t conflict with other history widgets (e.g., fzf Ctrl+R).
- Terminal may send different escape codes; check with `showkey -a`.

### 7. Uninstall

```bash
brew uninstall zsh-history-substring-search
```
