# zsh-you-should-use: Suggest better aliases as you type

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Basic suggestions](#4-beginner-basic-suggestions)
5. [Advanced: Customization and ignore rules](#5-advanced-customization-and-ignore-rules)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

`zsh-you-should-use` warns when you type a long command that matches an existing alias, nudging you to use the alias instead.

### 2. Install

Try Homebrew first:

```bash
brew install zsh-you-should-use
```

If not available, use a plugin manager (antidote):

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load MichaelAquilina/zsh-you-should-use
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-you-should-use/you-should-use.plugin.zsh
```

### 4. Beginner: Basic suggestions

Create an alias and see suggestions:

```zsh
alias gs='git status'
```

Typing `git status` will show a hint similar to:

```text
You should use: gs
```

### 5. Advanced: Customization and ignore rules

```zsh
# Don’t suggest for these commands
export YSU_IGNORE="npm,ls,cd"

# Case-insensitive
export YSU_CASE_SENSITIVE=0
```

### 6. Troubleshooting

- If no hints appear, ensure the plugin is sourced after your alias definitions.
- Conflicts with other pre-exec hooks may suppress messages.

### 7. Uninstall

```bash
brew uninstall zsh-you-should-use
```
