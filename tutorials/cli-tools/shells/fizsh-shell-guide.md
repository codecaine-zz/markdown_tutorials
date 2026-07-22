# Fizsh (Friendly Interactive Zsh) Guide

`fizsh` (Friendly Interactive Zsh) is a lightweight frontend wrapper around the Z shell (`zsh`). It brings `fish`-like interactive features—such as syntax highlighting, auto-suggestions, fuzzy history searching, and prompt formatting—to standard Zsh environments.

---

## 📚 Table of Contents

1. [Overview & How Fizsh Works](#overview--how-fizsh-works)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Key Interactive Features](#key-interactive-features)
4. [💡 Practical Examples & Configuration](#-practical-examples--configuration)
5. [Fizsh vs Native Zsh + Plugins](#fizsh-vs-native-zsh--plugins)
6. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & How Fizsh Works

`fizsh` operates as a Zsh shell wrapper script that automatically pre-configures:
- **`zsh-autosuggestions`**: Suggests historical command completions as grey text.
- **`zsh-syntax-highlighting`**: Colorizes valid shell commands green and invalid commands red.
- **History Search**: Up/Down arrow keys search history filtered by current typed prefix.

---

## ⚙️ Installation via Homebrew

```bash
# Install fizsh via Homebrew on macOS
brew install fizsh

# Start an interactive fizsh session
fizsh

# Check version
fizsh --version
```

---

## 🚀 Key Interactive Features

- **Inline Auto-Suggestions**: Start typing a command and press Right Arrow (`→`) or `Ctrl+F` to accept the suggestion.
- **History Prefix Matching**: Type `git` and press `Up Arrow` to scroll only through previous `git` commands.
- **Directory History Stack**: Use `cd -` or `popd` to navigate directory history stacks.

---

## 💡 Practical Examples & Configuration

Fizsh stores its configuration file at `~/.fizsh/fizshrc`.

### 1. Customizing `~/.fizsh/fizshrc`
```bash
mkdir -p ~/.fizsh
cat << 'EOF' > ~/.fizsh/fizshrc
# Load system aliases
alias ll='ls -la'
alias gs='git status'

# Set environment variables
export EDITOR=nvim
export PATH="/opt/homebrew/bin:$PATH"

# Enable directory stack duplication protection
setopt AUTO_PUSHD PUSHD_IGNORE_DUPS
EOF
```

### 2. Loading Zsh Plugins into Fizsh
Because `fizsh` runs Zsh under the hood, standard `.zshrc` plugins (e.g. `zsh-completions`) can be sourced inside `~/.fizsh/fizshrc`:

```bash
# Source Homebrew installed zsh-completions
if [ -d "/opt/homebrew/share/zsh-completions" ]; then
  fpath=(/opt/homebrew/share/zsh-completions $fpath)
fi
```

---

## ⚖️ Fizsh vs Native Zsh + Plugins

While `fizsh` offers instant setup, modern macOS users often configure native `zsh` directly using standalone plugins for better performance and Starship prompt compatibility:

```bash
# Native Zsh alternative (recommended for modern macOS)
brew install zsh-autosuggestions zsh-syntax-highlighting starship
```

Add to `~/.zshrc`:
```bash
source /opt/homebrew/share/zsh-autosuggestions/zsh-autosuggestions.zsh
source /opt/homebrew/share/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh
eval "$(starship init zsh)"
```

---

## 📋 Cheat Sheet Summary

| Task | Command / Shortcut |
| --- | --- |
| Start Fizsh | `fizsh` |
| Accept Suggestion | Right Arrow `→` or `Ctrl+F` |
| History Prefix Search | Type prefix + `Up Arrow` / `Down Arrow` |
| Config File Path | `~/.fizsh/fizshrc` |
| Exit Shell | `exit` |
