# tldr & Tealdeer Command Helper Guide

`tldr` provides simplified, community-driven, example-focused man pages for command-line tools. Instead of wading through dense manual pages, `tldr` gives you practical, everyday examples immediately.

---

## 📚 Table of Contents

1. [Overview & Comparison](#overview--comparison)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Essential Commands](#basic-usage--essential-commands)
4. [Tealdeer (Rust Client) Setup & Config](#tealdeer-rust-client-setup--config)
5. [Platform-Specific Searches](#platform-specific-searches)
6. [Updating Offline Page Cache](#updating-offline-page-cache)
7. [FZF Fuzzy Integration](#fzf-fuzzy-integration)
8. [Custom Pages & Aliases](#custom-pages--aliases)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Comparison

Standard `man` pages are exhaustive reference manuals. `tldr` complements them by showing 5-8 common real-world examples:

- **`tldr` (C / Python client)**: Standard client provided by the tldr project.
- **`tealdeer` (`tldr` in Rust)**: Ultra-fast Rust implementation with automatic caching, custom colors, and compact outputs.

---

## ⚙️ Installation via Homebrew

### Standard `tldr` Client
```bash
# Install tldr via Homebrew on macOS
brew install tldr

# Verify installation
tldr --version
```

### High-Performance Rust Client (`tealdeer`)
```bash
# Install tealdeer (executable named tldr)
brew install tealdeer

# Check version
tldr --version
```

---

## 🚀 Basic Usage & Essential Commands

### 1. View Practical Command Examples
```bash
# Show common usage for tar
tldr tar

# Show examples for ripgrep (rg)
tldr rg

# Show examples for ffmpeg
tldr ffmpeg
```

### 2. Search for Commands by Keyword
```bash
# Search pages containing a keyword
tldr -s archive

# Search for network tools
tldr -s "http request"
```

---

## ⚡ Tealdeer (Rust Client) Setup & Config

Tealdeer (`tldr` built in Rust) allows extensive color and formatting customizations via `~/.config/tealdeer/config.toml`.

### 1. Generate Default Configuration
```bash
mkdir -p ~/.config/tealdeer
```

Create `~/.config/tealdeer/config.toml`:
```toml
[style]
description.color = "yellow"
command_name.color = "green"
example_text.color = "blue"
example_code.color = "cyan"
example_variable.bold = true
example_variable.color = "underline"

[updates]
auto_update = true
auto_update_interval_hours = 168
```

### 2. Quiet Updates & Auto-Update
```bash
# Render compact output
tldr --quiet ffmpeg
```

---

## 💻 Platform-Specific Searches

Commands often differ between macOS (BSD tools) and Linux (GNU tools).

```bash
# Force macOS specific tldr page
tldr -p macos sed

# Force Linux specific page
tldr -p linux sed

# Search SunOS / FreeBSD pages
tldr -p freebsd ls
```

---

## 🔄 Updating Offline Page Cache

`tldr` downloads pages locally for offline use. Update the cache regularly to receive new command definitions.

```bash
# Update offline cache using standard tldr
tldr --update

# Update cache using tealdeer
tldr --update

# Clear cache
tldr --clear-cache
```

---

## 🎯 FZF Fuzzy Integration

Combine `tldr` with `fzf` for an interactive, searchable command cheat sheet directly in your terminal.

```bash
# Interactive tldr selector with live preview
tldr --list | fzf --preview 'tldr {}' --preview-window=right:70% | xargs tldr
```

Add this interactive shell function to your `~/.zshrc` or `~/.bash_profile`:

```bash
# Add to ~/.zshrc
htldr() {
  local cmd
  cmd=$(tldr --list | fzf --preview 'tldr {}' --preview-window=right:70%)
  if [[ -n "$cmd" ]]; then
    tldr "$cmd"
  fi
}
```

---

## 📝 Custom Pages & Aliases

You can add custom private `tldr` pages for internal team scripts or personal CLI workflows.

### 1. Create Local Page Directory
```bash
mkdir -p ~/.local/share/tldr/pages/custom
```

### 2. Create `mytool.md`
```markdown
# mytool

> Custom deployment tool for production services.

- Deploy to staging environment:
  `mytool deploy --env staging --branch main`

- View production logs:
  `mytool logs --env prod --tail 100`
```

### 3. Display Custom Page
```bash
tldr mytool
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Install via Brew | `brew install tealdeer` |
| View command examples | `tldr <command>` |
| Force macOS page | `tldr -p macos <command>` |
| Search by keyword | `tldr -s <query>` |
| Update cache | `tldr --update` |
| Render raw markdown | `tldr --markdown <command>` |
| Interactive FZF lookup | `tldr --list \| fzf \| xargs tldr` |
