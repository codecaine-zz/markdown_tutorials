# fizsh: Friendly Interactive Zsh

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Make default shell (optional)](#3-make-default-shell-optional)
4. [Beginner: What’s different from zsh](#4-beginner-whats-different-from-zsh)
5. [Advanced: Using zsh plugins with fizsh](#5-advanced-using-zsh-plugins-with-fizsh)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

`fizsh` is a user-friendly wrapper around zsh that provides helpful defaults and prompts. It’s zsh underneath, so most zsh plugins and configs work.

### 2. Install

```bash
brew install fizsh
```

Run it:

```bash
fizsh
```

### 3. Make default shell (optional)

```bash
FIZSH_PATH="$(brew --prefix)/bin/fizsh"
grep -qx "$FIZSH_PATH" /etc/shells || echo "$FIZSH_PATH" | sudo tee -a /etc/shells
chsh -s "$FIZSH_PATH"
```

### 4. Beginner: What’s different from zsh

- Friendly greeting and defaults
- Works with your existing `~/.zshrc` in most cases

Example prompt (varies):

```text
[fizsh] user@host ~/project (main) >
```

### 5. Advanced: Using zsh plugins with fizsh

Since fizsh runs zsh, you can source plugins the same way in `~/.zshrc`. Validate `$ZSH_VERSION` and ensure plugin paths exist.

### 6. Troubleshooting

- If configs don’t load, check which startup files fizsh reads (`~/.fizsh/rc` vs `~/.zshrc`).
- Reuse your zsh config and iterate.

### 7. Uninstall

```bash
brew uninstall fizsh
```
