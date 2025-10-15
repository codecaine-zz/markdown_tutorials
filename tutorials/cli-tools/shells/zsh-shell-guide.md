# zsh: Modern Shell on macOS with Homebrew

## Table of Contents

1. [What is zsh?](#1-what-is-zsh)
2. [Prerequisites](#2-prerequisites)
3. [Install zsh with Homebrew](#3-install-zsh-with-homebrew)
4. [Make zsh your default shell](#4-make-zsh-your-default-shell)
5. [Essential plugins via Homebrew](#5-essential-plugins-via-homebrew)
6. [Prompt theming with Powerlevel10k](#6-prompt-theming-with-powerlevel10k)
7. [Beginner: A clean, robust ~/.zshrc](#7-beginner-a-clean-robust-zshrc)
8. [Intermediate: Completions, history, keybinds, and fzf](#8-intermediate-completions-history-keybinds-and-fzf)
9. [Advanced: Plugin manager (antidote), direnv, and performance](#9-advanced-plugin-manager-antidote-direnv-and-performance)
10. [Troubleshooting](#10-troubleshooting)
11. [Uninstall](#11-uninstall)

-----

### 1. What is zsh?

zsh is a powerful, user-friendly shell with better interactive features than bash: smarter completion, globbing, prompts, and plugins. macOS uses zsh by default; installing a newer zsh with Homebrew gives you up-to-date features.

### 2. Prerequisites

- Homebrew installed
- Terminal app (iTerm2 recommended, but Terminal.app works fine)

Verify:

```bash
brew --version
zsh --version
```

Example output:

```text
Homebrew 4.3.0
zsh 5.9 (x86_64-apple-darwin23.0)
```

### 3. Install zsh with Homebrew

```bash
brew install zsh zsh-completions zsh-autosuggestions zsh-syntax-highlighting
```

Check the brew-installed path:

```bash
brew --prefix zsh
```

Example output:

```text
/opt/homebrew/opt/zsh
```

### 4. Make zsh your default shell

Add the brewed zsh to the system shells list and switch to it.

```bash
ZSH_PATH="$(brew --prefix)/bin/zsh"
grep -qx "$ZSH_PATH" /etc/shells || echo "$ZSH_PATH" | sudo tee -a /etc/shells
chsh -s "$ZSH_PATH"
```

Verify:

```bash
echo $SHELL
which zsh
```

Expected output (paths may vary):

```text
/opt/homebrew/bin/zsh
/opt/homebrew/bin/zsh
```

### 5. Essential plugins via Homebrew

- zsh-completions: extra completions for many tools
- zsh-autosuggestions: greyed suggestions as you type
- zsh-syntax-highlighting: colors commands, flags, errors

Enable in your `~/.zshrc` (order matters: suggestions before highlighting):

```zsh
# --- Plugins (Homebrew) ---
fpath=($(brew --prefix)/share/zsh/site-functions $fpath)

# Autosuggestions
source $(brew --prefix)/share/zsh-autosuggestions/zsh-autosuggestions.zsh

# Syntax highlighting (must be last)
source $(brew --prefix)/share/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh
```

Open a new shell or run `source ~/.zshrc`.

### 6. Prompt theming with Powerlevel10k

Install:

```bash
brew install powerlevel10k
```

Activate in `~/.zshrc`:

```zsh
# --- Prompt ---
source $(brew --prefix)/opt/powerlevel10k/powerlevel10k.zsh-theme
[[ ! -f ~/.p10k.zsh ]] || source ~/.p10k.zsh
```

First start triggers a guided config. To re-run:

```bash
p10k configure
```

Sample prompt preview:

```text
┌─( main) ─ (~/project) ─ ( v18.19.1) ─ ( 3) ─ ( 14:21)
└> 
```

### 7. Beginner: A clean, robust ~/.zshrc

Copy this minimal config to get a great baseline:

```zsh
# Path
export PATH="$(brew --prefix)/bin:$(brew --prefix)/sbin:$PATH"

# History
HISTFILE=~/.zsh_history
HISTSIZE=100000
SAVEHIST=100000
setopt HIST_IGNORE_DUPS HIST_IGNORE_ALL_DUPS SHARE_HISTORY

# Completion
autoload -Uz compinit && compinit
zstyle ':completion:*' menu select
zstyle ':completion:*' matcher-list 'm:{a-zA-Z}={A-Za-z}'

# Prompt (Powerlevel10k)
source $(brew --prefix)/opt/powerlevel10k/powerlevel10k.zsh-theme
[[ ! -f ~/.p10k.zsh ]] || source ~/.p10k.zsh

# Plugins
fpath=($(brew --prefix)/share/zsh/site-functions $fpath)
source $(brew --prefix)/share/zsh-autosuggestions/zsh-autosuggestions.zsh
source $(brew --prefix)/share/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh
```

Quick test:

```bash
exec zsh -l
echo $ZSH_VERSION
```

Example output:

```text
5.9
```

### 8. Intermediate: Completions, history, keybinds, and fzf

- Smarter completion and case-insensitive matching (already in the sample)
- Vi key bindings
- fzf integration for directories and history

Add to `~/.zshrc`:

```zsh
# Vi mode
bindkey -v

# fzf (requires: brew install fzf; then run the install script once)
[[ -f "$(brew --prefix)/opt/fzf/shell/key-bindings.zsh" ]] && \
  source "$(brew --prefix)/opt/fzf/shell/key-bindings.zsh"

# Example custom widget: Ctrl+G -> cd into a git repo under ~/code
fzf-cd-git() {
  local dir
  dir=$(fd -t d ".git$" ~/code -H | sed 's!/.git$!!' | fzf --prompt="repos> ") || return
  cd "$dir" || return
}
zle -N fzf-cd-git
bindkey '^G' fzf-cd-git
```

Try pressing Ctrl+R for fzf history or Ctrl+T for files. Example interaction:

```text
> npm run dev
  git status
  brew install zsh

3/1421
> dev
```

### 9. Advanced: Plugin manager (antidote), direnv, and performance

- Antidote is a fast, simple plugin manager for zsh.

Install and configure:

```bash
brew install antidote direnv
```

In `~/.zshrc`:

```zsh
# Antidote
source $(brew --prefix antidote)/share/antidote/antidote.zsh
antidote load <<'PLUGINS'
zsh-users/zsh-autosuggestions
zsh-users/zsh-syntax-highlighting
zsh-users/zsh-completions
romkatv/powerlevel10k
PLUGINS

# direnv
eval "$(direnv hook zsh)"
```

Performance tips:

- Defer heavy tooling init until needed.
- Prefer a manager (antidote) over sourcing many plugins manually.
- Audit startup time:

```bash
zmodload zsh/zprof && zprof
```

### 10. Troubleshooting

- Syntax highlighting must be sourced last; otherwise, colors may break.
- If Ctrl+R uses Atuin instead of fzf, ensure fzf bindings are sourced after Atuin in `~/.zshrc`.
- If `chsh` doesn’t stick, log out and back in, then re-check `echo $SHELL`.

### 11. Uninstall

```bash
brew uninstall powerlevel10k zsh-autosuggestions zsh-syntax-highlighting zsh-completions zsh
```

Optionally restore your default shell (Apple’s zsh path may differ):

```bash
sudo sed -i '' '/\/opt\/homebrew\/bin\/zsh/d' /etc/shells
chsh -s /bin/zsh
```
