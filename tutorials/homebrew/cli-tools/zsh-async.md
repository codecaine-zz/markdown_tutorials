# zsh-async: Async job helper for zsh prompts/plugins

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Enable in ~/.zshrc](#3-enable-in-zshrc)
4. [Beginner: Minimal async example](#4-beginner-minimal-async-example)
5. [Advanced: Integrate with prompts](#5-advanced-integrate-with-prompts)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

`zsh-async` runs background jobs without blocking the shell—used by many prompts to fetch Git status asynchronously.

### 2. Install

```bash
brew install zsh-async
```

Or via plugin manager:

```zsh
brew install antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load mafredri/zsh-async
```

### 3. Enable in ~/.zshrc

```zsh
source $(brew --prefix)/share/zsh-async/async.zsh
```

### 4. Beginner: Minimal async example

```zsh
# Define a callback that updates RPROMPT when the job finishes
async_init
async_start_worker myworker -n
async_register_callback myworker mydone

mydone() {
  RPROMPT="[job: $1 status: $3]"; zle && zle reset-prompt
}

# Run a slow command asynchronously
async_job myworker 'sleep 1; echo ok'
```

Expected behavior: prompt remains responsive; after ~1s, the right prompt updates with `ok` and exit code.

### 5. Advanced: Integrate with prompts

- Offload VCS or network-intensive checks to async jobs.
- Debounce frequent triggers to minimize flicker.

### 6. Troubleshooting

- Ensure `zle` is available before calling `reset-prompt` (interactive shells only).
- Avoid starting too many workers; reuse a single worker per feature.

### 7. Uninstall

```bash
brew uninstall zsh-async
```
