# 📚 Tutorials Directory Structure

This directory contains comprehensive tutorials organized by category for easy navigation.

## 📂 Directory Organization

### 🛠️ CLI Tools (`cli-tools/`)
Command-line tools and utilities organized by function:

#### **System Monitoring** (`system-monitoring/`)
- `btop.md` - Resource monitor (CPU, memory, processes)
- `cava.md` - Console audio visualizer
- `duf.md` - Disk usage utility
- `ncdu.md` - NCurses disk usage analyzer
- `neofetch.md` - System information display

#### **File Management** (`file-management/`)
- `eza.md` - Modern replacement for `ls`
- `fd.md` - Fast file finder
- `trash.md` - Safe file deletion
- `tree.md` - Directory structure viewer
- `zoxide.md` - Smart directory jumper

#### **Text Processing** (`text-processing/`)
- `bat.md` - Cat with syntax highlighting
- `gawk.md` - GNU AWK text processor
- `grep.md` - Pattern searching
- `jq.md` - JSON processor
- `ripgrep.md` - Fast recursive grep
- `ripgrep-all.md` - Ripgrep for all file types
- `sd.md` - Find and replace

#### **Network Tools** (`network-tools/`)
- `aria2.md` - Download manager
- `curl.md` - Data transfer tool
- `dog.md` - DNS lookup tool
- `gping.md` - Ping with graph
- `httppie.md` - HTTP client
- `wget.md` - File downloader
- `wget2.md` - Next-generation wget

#### **Shells** (`shells/`)
- `atuin.md` - Shell history sync
- `fish.md` - Friendly interactive shell
- `fizsh.md` - Friendly interactive zsh
- `tmux.md` - Terminal multiplexer
- `zsh.md` - Z shell

#### **Editors** (`editors/`)
- `micro.md` - Modern terminal text editor
- `micro-interactive-guide.md` - Interactive micro tutorial
- `neovim.md` - Vim-based text editor

#### **Development Tools** (`development-tools/`)
- `docker.md` - Container platform
- `git.md` - Version control
- `shellcheck.md` - Shell script analyzer

#### **Security Tools** (`security-tools/`)
- `age.md` - File encryption
- `gobuster.md` - Directory/file brute-forcing
- `metasploit.md` - Penetration testing framework
- `nmap.md` - Network scanner
- `openssl.md` - Cryptography toolkit

#### **Multimedia** (`multimedia/`)
- `ffmpeg.md` - Video/audio processing
- `ghostscript.md` - PostScript/PDF interpreter
- `imagemagick.md` - Image manipulation
- `lame.md` - MP3 encoder
- `yt-dlp.md` - YouTube downloader

#### **Utilities** (`utilities/`)
- `ca-certificates.md` - Certificate authority bundle
- `fzf.md` - Fuzzy finder
- `glow.md` - Markdown renderer
- `gum.md` - Shell script UI components
- `hyperfine.md` - Benchmarking tool
- `mas.md` - Mac App Store CLI
- `ollama.md` - Run LLMs locally
- `pandoc.md` - Universal document converter
- `tldr.md` - Simplified man pages
- `zenity.md` - Display GTK dialogs

#### **Databases** (`databases/`)
- `redis.md` - In-memory data store
- `sqlite.md` - Embedded database

---

### 💻 Programming (`programming/`)

Each programming language has its own directory containing the language guide and all related frameworks, packages, and tools.

#### **Python** (`python/`)
Complete Python ecosystem in one place:

- `python-complete-guide.md` - Complete Python tutorial
- `standard-library/` - Python standard library modules (30+ categories)

**Frameworks & Tools:**
- `web-frameworks/` - Django, Flask, FastAPI
- `gui/` - EasyGUI, FreeSimpleGUI, Pygame, PyWebView
- `data-science/` - NumPy, Pandas, Matplotlib, SciPy, Seaborn
- `utilities/` - Pydantic, Cerberus, Jinja2, FFmpeg, Redis, Wand

#### **JavaScript** (`javascript/`)
Complete JavaScript ecosystem in one place:

**Core:**
- `runtimes/` - Bun complete guide

**Frameworks:**
- `bun-express-complete-stack.md` - Bun + Express + databases
- `express-bun-redis.md` - Express with Redis
- `express-bun-sqlite.md` - Express with SQLite
- `neutralinojs.md` - Lightweight desktop apps

**Packages:**
- `packages/cli/` - CLI tools (Boxen, Chalk, Inquirer, Ora, etc.)
- `packages/templating/` - EJS, SweetAlert2
- `packages/utilities/` - Axios, ES-Toolkit

#### **PHP** (`php/`)
- `php-complete-guide.md` - Complete PHP tutorial

#### **Rust** (`rust/`)
- `rust-complete-guide.md` - Complete Rust tutorial

#### **TypeScript** (`typescript/`)
- `typescript-complete-guide.md` - Complete TypeScript tutorial

#### **V Language** (`vlang/`)
- `vlang-complete-guide.md` - Complete V language tutorial

#### **Google Apps Script** (`google-apps-script/`)
- `google-apps-script-gmail-functions.md`
- `google-apps-script-google-docs.md`
- `google-apps-script-google-drive.md`
- `google-apps-script-google-sheets.md`
- `google-apps-script-youtube-api.md`

---

### 🖥️ System (`system/`)

#### **macOS** (`macos/`)
- `keyboard-shortcuts.md` - macOS keyboard shortcuts
- `macbook-tips.md` - MacBook tips and tricks

#### **Terminal** (`terminal/`)
- `terminal.md` - Terminal usage guide

---

### 🔢 Mathematics (`mathematics/`)
- `mental_math.md` - Mental math techniques and tips

---

## 🔍 Quick Find Guide

### Looking for...

**A command-line tool?** → `cli-tools/[category]/`
- File operations → `file-management/`
- System info → `system-monitoring/`
- Text editing → `editors/` or `text-processing/`
- Network tasks → `network-tools/`
- Security/pentesting → `security-tools/`

**Programming language basics?** → `programming/[language]/`
- Python → `programming/python/python-complete-guide.md`
- JavaScript → `programming/javascript/runtimes/`
- PHP, Rust, TypeScript, V → `programming/[language]/[language]-complete-guide.md`

**Language-specific packages/frameworks?** → `programming/[language]/[category]/`
- JavaScript packages → `programming/javascript/packages/`
- Python packages → `programming/python/[web-frameworks|gui|data-science|utilities]/`

**System configuration?** → `system/[os]/`

---

## 📊 Statistics

- **Total Categories**: 50+ directories
- **Total Tutorials**: 116+ markdown files
- **Languages Covered**: JavaScript, Python, PHP, Rust, TypeScript, V, Google Apps Script
- **CLI Tools**: 50+ tools documented
- **Python Packages**: 20+ packages across web, GUI, data science, utilities
- **JavaScript Packages**: 20+ packages across CLI, templating, utilities

---

## 🚀 Recent Updates

**October 2025** - Complete reorganization:

- Moved from flat structure to organized categories
- Renamed files for clarity (e.g., `vlang.md` → `vlang-complete-guide.md`)
- Created logical groupings for CLI tools
- **Consolidated each language into ONE location** with all related content
- Each language directory now contains its guide, packages, frameworks, and tools
- Added subcategories for better navigation

---

## 📝 Contributing

When adding new tutorials:
1. Choose the appropriate category
2. Use descriptive filenames (lowercase, hyphenated)
3. Include proper markdown headers and structure
4. Update this README if adding new categories

---

**Last Updated**: October 15, 2025
