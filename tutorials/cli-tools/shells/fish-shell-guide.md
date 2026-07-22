# Fish Shell (Friendly Interactive Shell) Guide

`fish` (Friendly Interactive Shell) is a modern, user-centric command-line shell for macOS and Linux. It comes out-of-the-box with **auto-suggestions based on history, syntax highlighting, man-page completion generation, and tab completion without needing complex zsh/bash configurations**.

---

## 📚 Table of Contents

1. [Overview & Key Features](#overview-key-features)
2. [Installation & Setting Default Shell](#installation-setting-default-shell)
3. [Environment Variables (`set -gx` / `set -U`)](#environment-variables-set--gx-set--u)
4. [Abbreviations (`abbr`) vs Aliases](#abbreviations-abbr-vs-aliases)
5. [Writing Custom Fish Functions](#writing-custom-fish-functions)
6. [Starship Prompt & Colors](#starship-prompt-colors)
7. [Fisher Plugin Manager](#fisher-plugin-manager)
8. [Configuration File (`config.fish`)](#configuration-file-configfish)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Key Features

- **Auto-Suggestions**: Suggests command completions inline as you type based on your execution history (press Right-Arrow `→` to accept).
- **Tab Completion**: Tab completion parses command `--help` and `man` pages automatically.
- **Clean Syntax**: Clean control flow syntax (`if`, `for`, `switch`) replacing cryptic bash syntax.

---

## ⚙️ Installation & Setting Default Shell

```bash
# Install fish via Homebrew on macOS
brew install fish

# Verify installation
fish --version

# Add fish to allowed system shells in /etc/shells
echo "$(brew --prefix)/bin/fish" | sudo tee -a /etc/shells

# Set fish as default login shell for current user
chsh -s "$(brew --prefix)/bin/fish"
```

---

## 🌐 Environment Variables (`set -gx` / `set -U`)

In `fish`, variables are set using the `set` command instead of `export VAR=val`.

### 1. Global Exported Variables (`set -gx`)
```fish
# Export PATH and EDITOR variables globally
set -gx PATH /opt/homebrew/bin /opt/homebrew/sbin $PATH
set -gx EDITOR neovim
```

### 2. Universal Variables (`set -U`)
Universal variables are saved across shell restarts and synced across all running terminal sessions automatically without needing to edit config files!

```fish
# Set universal variable (persisted across restarts)
set -U GOPATH $HOME/go
```

---

## ⚡ Abbreviations (`abbr`) vs Aliases

Fish introduces **Abbreviations** (`abbr`). When you type an abbreviation and hit `Space` or `Enter`, it expands into the full target command on your screen, keeping your command history clean and explicit.

```fish
# Create abbreviations in fish
abbr -a g git
abbr -a gs git status
abbr -a gc git commit -m
abbr -a gp git push
abbr -a l eza -la --git
```

---

## 🧩 Writing Custom Fish Functions

Custom functions in `fish` are stored as individual `.fish` files in `~/.config/fish/functions/` and are lazily loaded on demand.

Create `~/.config/fish/functions/mkcd.fish`:
```fish
function mkcd --description "Create a directory and enter it"
    mkdir -p $argv[1]
    and cd $argv[1]
end
```

Create `~/.config/fish/functions/take.fish`:
```fish
function take
    mkdir -p $argv; and cd $argv[-1]
end
```

---

## 🎨 Starship Prompt & Colors

Pair `fish` with the `starship` cross-shell prompt for a fast, informative prompt.

```bash
# Install starship prompt
brew install starship
```

Configure interactive web color picker for fish:
```fish
# Open web-based GUI to customize fish colors and prompt
fish_config
```

---

## 🔌 Fisher Plugin Manager

`fisher` is the premier plugin manager for fish shell.

### 1. Install Fisher
```fish
curl -sL https://git.io/fisher | source && fisher install jorgebucaran/fisher
```

### 2. Useful Fisher Plugins
```fish
# Install FZF integration for fish
fisher install jethrokuan/fzf

# Install Z-like directory navigation for fish
fisher install jethrokuan/z

# Install nvm node manager wrapper for fish
fisher install jorgebucaran/nvm.fish
```

---

## ⚙️ Configuration File (`config.fish`)

Create a clean `~/.config/fish/config.fish` file:

```bash
mkdir -p ~/.config/fish
cat << 'EOF' > ~/.config/fish/config.fish
# Disable greeting text
set -g fish_greeting ""

# Homebrew environment paths
if test -d /opt/homebrew/bin
    fish_add_path /opt/homebrew/bin
    fish_add_path /opt/homebrew/sbin
end

# Default Editor
set -gx EDITOR nvim

# Abbreviations
abbr -a g git
abbr -a gs git status
abbr -a gd git diff
abbr -a ll eza -la --git --icons

# Initialize Starship prompt if installed
if type -q starship
    starship init fish | source
end
EOF
```

Reload fish configuration:
```fish
source ~/.config/fish/config.fish
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Set Exported Var | `set -gx VAR value` |
| Set Universal Var | `set -U VAR value` |
| Add Path | `fish_add_path /path/to/bin` |
| Add Abbreviation | `abbr -a gs "git status"` |
| Launch GUI Config | `fish_config` |
| Install Fisher | `curl -sL https://git.io/fisher \| source ...` |
| Reload Configuration| `source ~/.config/fish/config.fish` |
