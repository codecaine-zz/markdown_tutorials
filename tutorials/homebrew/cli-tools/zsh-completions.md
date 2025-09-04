# zsh-completions: Extra completions for hundreds of tools

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Using completions](#4-beginner-using-completions)
5. [Advanced: fpath ordering and compinit](#5-advanced-fpath-ordering-and-compinit)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`zsh-completions` adds a large set of community-maintained completion definitions.

### 2\. Install

```bash
brew install zsh-completions
```

### 3\. Enable in ~/.zshrc

```zsh
fpath=($(brew --prefix)/share/zsh/site-functions $fpath)
autoload -Uz compinit && compinit
```

### 4\. Beginner: Using completions

Try tab-completing for popular tools (git, kubectl, brew, npm, etc.).

### 5\. Advanced: fpath ordering and compinit

- Ensure Homebrew’s `site-functions` comes before default entries.
- Rebuild compdump after upgrading:

```bash
rm -f ~/.zcompdump* && compinit
```

### 6\. Troubleshooting

- If completions don’t appear, check `echo $fpath` ordering.
- Conflicts with other frameworks (oh-my-zsh) may require removing duplicates.

### 7\. Uninstall

```bash
brew uninstall zsh-completions
```
