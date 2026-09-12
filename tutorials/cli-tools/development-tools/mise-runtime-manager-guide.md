# Mise (Polyglot Runtime & Tool Version Manager) Guide

`mise` (formerly `rtx` / `mise-en-place`) is a blazing-fast, polyglot development environment and tool version manager written in Rust. Designed to replace slow, fragmented version managers like `asdf`, `nvm`, `pyenv`, `rbenv`, and `gvm`, `mise` allows you to manage runtimes (Node, Python, Go, Rust, Ruby, Java), environment variables, and project tasks seamlessly in one tool.

---

## 📚 Table of Contents

1. [Overview & `asdf` / `nvm` vs `mise` Comparison](#overview-asdf-nvm-vs-mise-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Shell Activation & Setup](#shell-activation-setup)
4. [Installing & Managing Runtimes (Node, Python, Go, etc.)](#installing-managing-runtimes)
5. [Configuring Project Tools (`mise.toml`)](#configuring-project-tools-misetoml)
6. [Managing Environment Variables (`[env]` Section)](#managing-environment-variables-env-section)
7. [Running Project Tasks (`[tasks]` Section)](#running-project-tasks-tasks-section)
8. [Compatibility with `.tool-versions` & Legacy Configs](#compatibility-with-tool-versions-legacy-configs)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `asdf` / `nvm` vs `mise` Comparison

Traditional tools like `nvm` slow down terminal startup times (adding 200–500ms per shell tab), and `asdf` requires slow shims. `mise` injects paths directly into `$PATH` with zero latency.

| Feature | Legacy (`nvm`, `pyenv`, `asdf`) | `mise` |
| :--- | :--- | :--- |
| **Language** | Shell / Bash / C | Rust |
| **Startup Overhead** | 200ms–800ms per shell session | ~4ms (imperceptible) |
| **Shims** | Required (slow binary interception) | Direct PATH modifications (no shims) |
| **Multi-Language** | Fragmented (separate tool per language) | Unified (Node, Python, Go, Rust, etc.) |
| **Environment Variables** | Requires `direnv` | Built-in native `.env` / `[env]` support |
| **Task Runner** | Requires `make` / `npm run` | Built-in task runner (`mise run`) |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install mise
```

### 2. Verify Installation

```bash
mise --version
```

---

## 🐚 Shell Activation & Setup

Add the automatic activation hook to your shell profile so runtime paths switch automatically when you `cd` into project folders:

### For Zsh (`~/.zshrc`):
```bash
echo 'eval "$(mise activate zsh)"' >> ~/.zshrc
source ~/.zshrc
```

### For Bash (`~/.bashrc`):
```bash
echo 'eval "$(mise activate bash)"' >> ~/.bashrc
source ~/.bashrc
```

### For Fish (`~/.config/fish/config.fish`):
```fish
echo 'mise activate fish | source' >> ~/.config/fish/config.fish
```

---

## 🚀 Installing & Managing Runtimes

### 1. Install Global Default Tools

```bash
# Install latest LTS Node.js and set as global default
mise use -g node@lts

# Install specific Python version
mise use -g python@3.12

# Install Go and Rust
mise use -g go@latest
mise use -g rust@latest
```

### 2. Install Project-Specific Tool Versions

When run inside a project folder, `mise use` pins the tool locally in `mise.toml`:

```bash
# Pin Node 20 and Python 3.11 for this specific directory
mise use node@20
mise use python@3.11
```

### 3. List Installed and Available Tools

```bash
# View active versions in current directory
mise current

# List all installed versions
mise ls

# List all versions available for install
mise ls-remote node
```

---

## 📄 Configuring Project Tools (`mise.toml`)

`mise` uses a clear, declarative `mise.toml` configuration file in the project root:

```toml
[tools]
node = "20.12.0"
python = "3.11.8"
go = "1.22"
terraform = "1.7.5"

[env]
NODE_ENV = "development"
PORT = "8080"
DATABASE_URL = "postgres://localhost:5432/dev_db"

[tasks.build]
description = "Build application"
run = "npm run build"

[tasks.test]
description = "Run tests"
run = "npm test"
```

When any team member `cd`s into this directory and runs `mise install`, all exact compiler and runtime versions are installed automatically.

---

## 🏃 Running Project Tasks (`mise run`)

`mise` includes a built-in task runner (replaces npm scripts / Makefiles):

```bash
# List all defined tasks
mise tasks

# Run the build task
mise run build

# Run task with arguments
mise run test --watch
```

---

## 🔄 Compatibility with `.tool-versions` & Legacy Configs

`mise` automatically reads legacy version files:
- `.nvmrc` / `.node-version` (for Node.js)
- `.python-version` (for Python)
- `.go-version` (for Go)
- `.ruby-version` (for Ruby)
- `.tool-versions` (for `asdf`)

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Set local version** | `mise use <tool>@<version>` |
| **Set global version** | `mise use -g <tool>@<version>` |
| **Install project tools** | `mise install` |
| **View active tools** | `mise current` |
| **Update all tools** | `mise upgrade` |
| **Run task** | `mise run <task>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Mise shortcuts
alias m='mise'
alias mi='mise install'
alias mu='mise use'
alias mr='mise run'
alias mc='mise current'
```

---

## 🗑️ Uninstallation

To remove `mise`:

```bash
brew uninstall mise
```
