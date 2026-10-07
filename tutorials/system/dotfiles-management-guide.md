# Dotfiles Management & Synchronization Guide: Stow, Chezmoi & Bare Git

Managing personal dotfiles (`.zshrc`, `.tmux.conf`, `~/.config/nvim`, etc.) across multiple machines (macOS, Linux, WSL) requires an automated, reproducible strategy that protects private credentials while enabling rapid bootstrapping on new hardware.

---

## 📚 Table of Contents

1. [Dotfiles Methodology Comparison](#1-dotfiles-methodology-comparison)
2. [Method 1: GNU Stow (Symlink Farm Manager)](#2-method-1-gnu-stow-symlink-farm-manager)
3. [Method 2: Chezmoi (Modern Multi-Machine & Secret Management)](#3-method-2-chezmoi-modern-multi-machine--secret-management)
4. [Method 3: The Bare Git Repository Pattern](#4-method-3-the-bare-git-repository-pattern)
5. [Managing Secrets Without Leaking to Git](#5-managing-secrets-without-leaking-to-git)
6. [Universal Automated Bootstrap Script (`bootstrap.sh`)](#6-universal-automated-bootstrap-script-bootstrapsh)

---

## 1. Dotfiles Methodology Comparison

| Feature | GNU Stow | Chezmoi | Bare Git Repo |
| :--- | :--- | :--- | :--- |
| **Complexity** | Low | Medium | Very Low |
| **Dependencies** | Perl / `stow` binary | Single Go binary | Native Git |
| **Mechanism** | Symlinks into `$HOME` | Generates files from repo | Directly tracks `$HOME` files |
| **Secret Management** | None (External manual) | Built-in (Age, 1Password, Bitwarden) | None |
| **Templating** | None | Go text/template engine | None |
| **Multi-OS Handling** | Separate directories | OS-conditional templates | Git branches |

---

## 2. Method 1: GNU Stow (Symlink Farm Manager)

GNU Stow creates symlinks pointing from your home directory to a centralized git repository folder.

### Installation

```bash
# macOS
brew install stow

# Ubuntu / Debian
sudo apt install stow
```

### Directory Structure

Structure your repository into modular package folders matching `$HOME` paths:

```text
~/dotfiles/
├── git/
│   └── .gitconfig
├── zsh/
│   └── .zshrc
├── nvim/
│   └── .config/
│       └── nvim/
│           └── init.lua
└── tmux/
    └── .tmux.conf
```

### Deploying & Managing Links

```bash
cd ~/dotfiles

# Symlink individual package to $HOME
stow zsh

# Symlink all packages
stow -t ~ git zsh nvim tmux

# Restow (re-evaluate symlinks after restructuring)
stow -R -t ~ nvim

# Unstow (cleanly remove symlinks without deleting original files)
stow -D -t ~ tmux
```

---

## 3. Method 2: Chezmoi (Modern Multi-Machine & Secret Management)

Chezmoi is a purpose-built dotfiles manager written in Go. Instead of symlinks, it manages source files and renders target files directly into `$HOME`, supporting OS branching and encrypted secrets.

### Installation

```bash
# macOS
brew install chezmoi

# Linux one-line installer
sh -c "$(curl -fsLS get.chezmoi.io)"
```

### Quickstart

```bash
# Initialize chezmoi with git repository
chezmoi init

# Add an existing file to management
chezmoi add ~/.zshrc
chezmoi add ~/.config/ghostty/config

# Edit managed files in your configured editor
chezmoi edit ~/.zshrc

# Preview changes before applying
chezmoi diff

# Apply changes to your home directory
chezmoi apply
```

### OS-Specific Templates with Chezmoi

Rename a file with `.tmpl` suffix (e.g., `dot_zshrc.tmpl`):

```bash
chezmoi chattr +template ~/.zshrc
```

Inside `dot_zshrc.tmpl`:

```bash
# Universal aliases
alias g="git"

{{ if eq .chezmoi.os "darwin" -}}
# macOS Specific
eval "$(/opt/homebrew/bin/brew shellenv)"
alias flushdns="sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder"
{{ else if eq .chezmoi.os "linux" -}}
# Linux Specific
alias pbcopy="xclip -selection clipboard"
alias pbpaste="xclip -selection clipboard -o"
{{ end -}}
```

---

## 4. Method 3: The Bare Git Repository Pattern

The Bare Git pattern requires **zero external utilities**-only native `git`. It turns your entire `$HOME` directory into a working tree for a hidden bare git directory (`~/.cfg`).

### Setup on Machine 1

```bash
# Initialize bare git repository in home directory
git init --bare $HOME/.cfg

# Define alias for git commands targeting home
alias config='/usr/bin/git --git-dir=$HOME/.cfg/ --work-tree=$HOME'

# Hide untracked files from 'config status'
config config --local status.showUntrackedFiles no

# Add your config files
config add .zshrc
config add .config/nvim/
config commit -m "Initialize dotfiles"

# Push to your private GitHub repo
config remote add origin git@github.com:username/dotfiles.git
config branch -M main
config push -u origin main
```

### Cloning onto a New Machine

```bash
# Add alias to current shell
alias config='/usr/bin/git --git-dir=$HOME/.cfg/ --work-tree=$HOME'

# Clone bare repository into ~/.cfg
git clone --bare git@github.com:username/dotfiles.git $HOME/.cfg

# Checkout files into $HOME
config checkout

# If conflicts exist with existing default files, backup and re-checkout:
mkdir -p .dotfiles-backup && \
config checkout 2>&1 | egrep "\s+\." | awk {'print $1'} | \
xargs -I{} mv {} .dotfiles-backup/{}
config checkout

# Disable untracked file listing
config config --local status.showUntrackedFiles no
```

---

## 5. Managing Secrets Without Leaking to Git

Never commit plaintext API tokens, private SSH keys, or passwords.

### Pattern 1: Separate `*.local` Uncommitted Configs

In your tracked `.zshrc`:

```bash
# Load tracked settings
export EDITOR="nvim"

# Load local, gitignored private overrides if present
if [ -f "$HOME/.zshrc.local" ]; then
    source "$HOME/.zshrc.local"
fi
```

Add to `.gitignore`:

```text
*.local
*.secret
```

### Pattern 2: Encrypting Secrets with Age & Chezmoi

```bash
# Install age encryption tool
brew install age

# Configure chezmoi to use age in ~/.config/chezmoi/chezmoi.toml
encryption = "age"
[age]
    identity = "~/.config/chezmoi/key.txt"
    recipient = "age1..."
```

Add encrypted sensitive files:

```bash
chezmoi add --encrypt ~/.ssh/id_ed25519
```

---

## 6. Universal Automated Bootstrap Script (`bootstrap.sh`)

A clean bootstrap script lets you set up a fresh Mac or Linux VM in one command:

```bash
#!/usr/bin/env bash
set -euo pipefail

echo "=== Bootstrapping Development Workstation ==="

# 1. Detect OS
OS="$(uname -s)"
case "${OS}" in
    Darwin)
        echo "Detected macOS"
        if ! command -v brew >/dev/null 2>&1; then
            echo "Installing Homebrew..."
            /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
            eval "$(/opt/homebrew/bin/brew shellenv)"
        fi
        PACKAGES=(git neovim tmux zsh ripgrep fzf bat eza starship stow)
        brew install "${PACKAGES[@]}"
        ;;
    Linux)
        echo "Detected Linux"
        sudo apt update
        PACKAGES=(git neovim tmux zsh ripgrep fzf bat stow curl)
        sudo apt install -y "${PACKAGES[@]}"
        ;;
    *)
        echo "Unsupported OS: ${OS}"
        exit 1
        ;;
esac

# 2. Deploy dotfiles with Stow
DOTFILES_DIR="$HOME/dotfiles"
if [ ! -d "$DOTFILES_DIR" ]; then
    echo "Cloning dotfiles..."
    git clone https://github.com/username/dotfiles.git "$DOTFILES_DIR"
fi

cd "$DOTFILES_DIR"
stow -t "$HOME" git zsh tmux nvim

echo "=== Setup Complete! Open a new terminal. ==="
```
