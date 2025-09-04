# Homebrew Tutorials

This section contains comprehensive tutorials for various tools and applications that can be installed via Homebrew on macOS.

## Table of Contents

1. [CLI Tools](#-cli-tools-cli-tools)
2. [Development Tools](#-development-tools-development-tools)
3. [Security Tools](#-security-tools-security-tools)
4. [System Guides](#-system-guides-system-guides)
5. [Programming Languages](#-programming-languages-programming-languages)
6. [Installation Prerequisites](#installation-prerequisites)
7. [Tutorial Format](#tutorial-format)
8. [Contributing](#contributing)

## Directory Structure

### 📁 CLI Tools (`cli-tools/`)
Command-line utilities for text processing, file operations, and general system tasks:
- bat.md — Enhanced cat with syntax highlighting
- curl.md — HTTP client for API testing and web requests
- fd-find.md — Fast alternative to find
- gawk.md — Advanced text processing (GNU awk)
- grep.md — Pattern matching and text searching
- jq.md — JSON processor and query tool
- sed.md — Stream editor for text transformation
- wget.md — File downloading utility
- eza.md — Modern ls replacement
- fzf.md — Terminal fuzzy finder
- zoxide.md — Smarter cd
- micro.md — Friendly terminal editor
- tmux.md — Terminal multiplexer
- tldr.md — Example-driven help pages
- aria2.md — High-speed downloads
- yt-dlp.md — Video/audio downloader
- dog.md — Modern DNS client

### 🛠️ Development Tools (`development-tools/`)
Tools for software development, media processing, and development workflows:
- **docker.md** - Container platform setup and usage
- **ffmpeg.md** - Multimedia processing and conversion
- **ghostscript.md** - PostScript and PDF processing
- **git.md** - Version control system
- **imagemagick.md** - Image manipulation and conversion
- **ollama.md** - Local AI model management
- **openssl.md** - Cryptography and SSL/TLS toolkit

### 🔒 Security Tools (`security-tools/`)
Network security, penetration testing, and security auditing tools:
- **metasploit.md** - Penetration testing framework
- **nmap.md** - Network scanning and security auditing

### 🖥️ System Guides (`system-guides/`)
macOS system configuration, shortcuts, and productivity tips:
- **keyboard-shortcuts.md** - macOS keyboard shortcuts and hotkeys
- **macbook-tips.md** - MacBook Pro optimization and tips
- **terminal.md** - Terminal usage and configuration

### 💻 Programming Languages (`programming-languages/`)
Documentation for programming languages and their toolchains:
- **vlang.md** - V programming language documentation

## Installation Prerequisites

All tutorials assume you have Homebrew installed. If not, install it first:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

## Tutorial Format

Each tutorial includes:
- Installation instructions via Homebrew
- Basic usage examples
- Advanced configuration options
- Practical use cases and examples
- Troubleshooting tips

## Contributing

When adding new tutorials:
- Use clear, descriptive filenames (lowercase with hyphens)
- Place files in the appropriate category folder
- Include installation and basic usage examples
- Test all code examples before submitting
