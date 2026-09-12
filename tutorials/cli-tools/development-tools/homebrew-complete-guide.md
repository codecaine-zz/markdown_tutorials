# Homebrew Complete Power-User & Maintenance Guide

`brew` (Homebrew) is the indispensable package manager for macOS and Linux. While most developers know basic `brew install`, mastering Homebrew's advanced features—including **Brewfiles (`brew bundle`)**, **background daemon services (`brew services`)**, **custom taps**, **casks**, and **storage maintenance**—transforms machine setup and environment reproducibility.

---

## 📚 Table of Contents

1. [Overview & Architecture (Formulae vs Casks vs Taps)](#overview-architecture)
2. [Installation & Shell Configuration](#installation-shell-configuration)
3. [Formulae vs GUI Applications (`brew install` vs `brew install --cask`)](#formulae-vs-gui-applications)
4. [Reproducible Machines with `brew bundle` & `Brewfile`](#reproducible-machines-with-brew-bundle-brewfile)
5. [Managing Background Daemons (`brew services`)](#managing-background-daemons-brew-services)
6. [Adding Third-Party Repositories (`brew tap`)](#adding-third-party-repositories-brew-tap)
7. [System Health, Doctor & Storage Cleanup](#system-health-doctor-storage-cleanup)
8. [Pinning Packages & Version Locking](#pinning-packages-version-locking)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Architecture

- **Formula (`brew install <formula>`)**: Command-line tools, libraries, and language runtimes built from source or bottled binaries (e.g. `ripgrep`, `git`, `openssl`).
- **Cask (`brew install --cask <app>`)**: Native macOS GUI applications and fonts (e.g. `visual-studio-code`, `docker`, `iterm2`).
- **Tap (`brew tap <user/repo>`)**: Third-party Git repositories containing extra formulas and casks not in Homebrew core.
- **Bottle**: Pre-compiled binary packages specifically built for your macOS architecture (Apple Silicon ARM64 or Intel x86_64).

---

## ⚙️ Installation & Shell Configuration

### 1. Official Installation Command

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### 2. Configure Shell Environment ($PATH)

On Apple Silicon Macs (M1/M2/M3/M4), Homebrew lives in `/opt/homebrew`:

```bash
# Add Homebrew to ~/.zprofile or ~/.zshrc
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"
```

---

## 📦 Formulae vs GUI Applications

### 1. CLI Tools (Formulae)

```bash
# Search for formulae
brew search fzf

# Install formula
brew install fzf bat eza ripgrep

# View formula info & dependencies
brew info fzf
```

### 2. GUI Applications (Casks)

```bash
# Search for GUI apps
brew search --casks visual-studio-code

# Install GUI apps
brew install --cask visual-studio-code iterm2 raycast docker

# Update GUI apps along with CLI tools
brew upgrade --cask
```

---

## 📄 Reproducible Machines with `brew bundle` & `Brewfile`

Declare your entire machine's software stack in a single `Brewfile` (similar to `package.json` for your Mac):

### 1. Export Current System Packages to `Brewfile`

```bash
# Dumps all taps, formulae, casks, and Mac App Store apps into Brewfile
brew bundle dump --describe --force
```

### 2. Example `Brewfile`:

```ruby
# Taps
tap "homebrew/cask-fonts"
tap "nikitabobko/tap"

# CLI Formulae
brew "git"
brew "neovim"
brew "ripgrep"
brew "starship"
brew "zellij"

# GUI Casks
cask "ghostty"
cask "raycast"
cask "visual-studio-code"
cask "font-jetbrains-mono-nerd-font"

# Mac App Store (via mas-cli)
mas "Xcode", id: 497799835
```

### 3. Restore / Install Entire Setup on a New Mac

```bash
# Installs everything declared in the Brewfile with one command
brew bundle install
```

---

## 🚦 Managing Background Daemons (`brew services`)

Manage background services (PostgreSQL, Redis, Nginx, Docker) without manually dealing with `launchd` plist files:

```bash
# List all services and their status
brew services list

# Start a service in the background (starts on login)
brew services start postgresql@16
brew services start redis

# Restart a service
brew services restart redis

# Stop a service
brew services stop postgresql@16
```

---

## 🚰 Adding Third-Party Repositories (`brew tap`)

Add custom repositories maintained by open-source communities or software vendors:

```bash
# Tap a custom repository
brew tap hashicorp/tap
brew install hashicorp/tap/terraform

# Tap aerospace tiling window manager
brew tap nikitabobko/tap
brew install --cask aerospace

# List active taps
brew tap

# Untap / remove repository
brew untap hashicorp/tap
```

---

## 🧹 System Health, Doctor & Storage Cleanup

Over time, Homebrew accumulates gigabytes of cached download archives and older package versions.

### 1. Diagnose System Issues

```bash
# Checks for broken symlinks, unlinked kegs, and config problems
brew doctor
```

### 2. Reclaim Disk Space (`brew cleanup`)

```bash
# View how much disk space can be reclaimed
brew cleanup -n

# Permanently delete old cached downloads and stale package versions
brew cleanup --prune=all
```

### 3. Remove Unused Dependencies (Autoremove)

```bash
# Uninstalls orphan dependencies that are no longer required by any package
brew autoremove
```

---

## 📌 Pinning Packages & Version Locking

Prevent specific formulas from being upgraded during `brew upgrade`:

```bash
# Pin node to current version
brew pin node

# List all pinned packages
brew list --pinned

# Unpin to allow future upgrades
brew unpin node
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Update package list** | `brew update` |
| **Upgrade all packages** | `brew upgrade` |
| **Install CLI tool** | `brew install <formula>` |
| **Install GUI app** | `brew install --cask <app>` |
| **Search packages** | `brew search <name>` |
| **Cleanup old caches** | `brew cleanup` |
| **Check health** | `brew doctor` |
| **Dump Brewfile** | `brew bundle dump --force` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Homebrew aliases
alias bup='brew update && brew upgrade && brew cleanup'
alias bsearch='brew search'
alias binfo='brew info'
alias bserv='brew services list'
alias bclean='brew cleanup --prune=all && brew autoremove'
```

---

## 🗑️ Uninstallation

To completely uninstall Homebrew from your system:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/uninstall.sh)"
```
