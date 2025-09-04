# zsh-git-prompt: Git status in your zsh prompt

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Default prompt](#4-beginner-default-prompt)
5. [Intermediate: Customize symbols and colors](#5-intermediate-customize-symbols-and-colors)
6. [Advanced: Performance and async](#6-advanced-performance-and-async)
7. [Troubleshooting](#7-troubleshooting)
8. [Uninstall](#8-uninstall)

-----

### 1. Overview

`zsh-git-prompt` adds Git branch and status to your prompt: ahead/behind, staged/unstaged, untracked, stash, etc.

### 2. Install

Prefer Homebrew if available:

```bash
brew install zsh-git-prompt
```

If Homebrew formula isn’t available on your system, use a plugin manager (antidote):

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load olivierverdier/zsh-git-prompt
```

### 3. Enable in ~/.zshrc

```zsh
# Source the plugin
source $(brew --prefix)/opt/zsh-git-prompt/share/zsh-git-prompt/zshrc.sh

# Right prompt is less jittery for async git info
RPROMPT='$(git_super_status)'
```

Reload the shell:

```bash
exec zsh -l
```

### 4. Beginner: Default prompt

Inside a Git repo, you’ll see a status segment like:

```text
main ⇡1 ⇣0 ✚2 ✖1 ⚑1 ⍟1
```

Legend (common):

- ⇡ ahead, ⇣ behind
- ✚ staged, ✖ unstaged
- ⚑ untracked, ⍟ stashed

### 5. Intermediate: Customize symbols and colors

Place before sourcing the plugin:

```zsh
ZSH_THEME_GIT_PROMPT_PREFIX="["
ZSH_THEME_GIT_PROMPT_SUFFIX="]"
ZSH_THEME_GIT_PROMPT_DIRTY="*"
ZSH_THEME_GIT_PROMPT_CLEAN=""
```

### 6. Advanced: Performance and async

- Prefer `RPROMPT` for reduced left-prompt shifting.
- For very large repos, consider a theme with async VCS integration (e.g., Powerlevel10k) and disable heavy status segments.

### 7. Troubleshooting

- If nothing appears, ensure you’re in a Git repo and the plugin is sourced.
- Conflicts with other themes: load this before heavy prompt frameworks, or let the theme handle Git status.

### 8. Uninstall

```bash
brew uninstall zsh-git-prompt
```
