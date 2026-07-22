# Fastfetch System Info Guide

`fastfetch` is an ultra-fast, highly customizable command-line system information tool written in C. It serves as the modern, active replacement for the archived `neofetch` utility, executing **10x to 100x faster** while offering complete support for Apple Silicon hardware detection (M1/M2/M3/M4 chips) on macOS.

---

## 📚 Table of Contents

1. [Overview & Neofetch Comparison](#overview-neofetch-comparison)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Usage & Terminal Output](#basic-usage-terminal-output)
4. [Custom Logos & ASCII Art](#custom-logos-ascii-art)
5. [Configuration File Setup (`config.jsonc`)](#configuration-file-setup-configjsonc)
6. [Preset Themes & Formats](#preset-themes-formats)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Neofetch Comparison

Why `fastfetch` is preferred over `neofetch`:

- **Speed**: Written in optimized C instead of bash scripts, rendering instantly (< 10ms).
- **Active Maintenance**: Updated continuously with support for new macOS versions, GPUs, and ARM SOCs.
- **Structured JSONC Config**: Configured via JSON with comments (`config.jsonc`), enabling clean module selection and styling.

---

## ⚙️ Installation via Homebrew

```bash
# Install fastfetch on macOS
brew install fastfetch

# Verify installation
fastfetch --version
```

---

## 🚀 Basic Usage & Terminal Output

Run `fastfetch` directly without flags:

```bash
fastfetch
```

It instantly displays system information alongside the colorful Apple logo:

```text
       .:'      yourname@macbook-pro
    __ :'__     --------------------
 .':  `  :`.    OS: macOS 15.0 Sequoia arm64
:  .  :  .  :   Host: MacBook Pro (16-inch, M4 Max, 2025)
:  _  :  _  :   Kernel: Darwin 24.5.0
 `:  `  :  :'   Uptime: 2 days, 4 hours
   `.__.'       Packages: 185 (brew)
                Shell: zsh 5.9
                Resolution: 3456x2234 @ 120Hz
                DE: Aqua
                WM: Quartz Compositor
                Terminal: Ghostty
                CPU: Apple M4 Max (16 cores)
                GPU: Apple M4 Max (40-core GPU)
                Memory: 18.42 GiB / 48.00 GiB (38%)
                Disk (/): 142.10 GiB / 994.66 GiB (14%)
```

---

## 🎨 Custom Logos & ASCII Art

```bash
# Display built-in system logos (e.g. Arch, Ubuntu, macOS, retro Apple)
fastfetch --logo apple
fastfetch --logo macos

# Display custom image using iTerm2 / Kitty inline image protocol
fastfetch --logo ~/Pictures/avatar.png --logo-type iterm

# Print available built-in logo names
fastfetch --list-logos
```

---

## ⚙️ Configuration File Setup (`config.jsonc`)

Generate a default JSONC configuration file to customize modules, colors, and layouts.

```bash
# Generate default config at ~/.config/fastfetch/config.jsonc
fastfetch --gen-config
```

Edit `~/.config/fastfetch/config.jsonc`:

```jsonc
{
  "$schema": "https://github.com/fastfetch-cli/fastfetch/raw/dev/doc/json_schema.json",
  "logo": {
    "padding": {
      "top": 1,
      "left": 2
    }
  },
  "modules": [
    "title",
    "separator",
    "os",
    "host",
    "kernel",
    "uptime",
    "packages",
    "shell",
    "display",
    "terminal",
    "cpu",
    "gpu",
    "memory",
    "disk",
    "break",
    "colors"
  ]
}
```

---

## 🎭 Preset Themes & Formats

`fastfetch` includes built-in presets for small terminals or minimalist layouts.

```bash
# Use compact / small preset
fastfetch --config small

# Use dense / detailed preset
fastfetch --config detailed

# Print list of available built-in presets
fastfetch --list-presets
```

Add `fastfetch` to your `~/.zshrc` to show a clean system summary upon opening a new terminal window:

```bash
# Add to bottom of ~/.zshrc
if command -v fastfetch &> /dev/null; then
  fastfetch --config small
fi
```

## Everyday Copy-and-Paste `fastfetch` Snippets & Shell Integration

```bash
# 1. Output system information as JSON for scripts or custom dashboards
fastfetch --format json

# 2. Display fastfetch with retro Apple ASCII logo
fastfetch --logo apple

# 3. Display fastfetch with custom inline image logo in iTerm2 or WezTerm
fastfetch --logo ~/Pictures/wallpaper.png --logo-type iterm --logo-width 30

# 4. Run fastfetch showing only CPU, Memory, and Disk metrics
fastfetch --structure CPU:Memory:Disk

# 5. Clean shell greeting snippet for ~/.zshrc or ~/.bashrc
cat << 'EOF' >> ~/.zshrc

# Terminal startup greeting with fastfetch
if command -v fastfetch &> /dev/null; then
  fastfetch --config small
fi
EOF

# 6. Neofetch compatibility alias
alias neofetch='fastfetch'
alias sysinfo='fastfetch --config detailed'
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Default System Summary | `fastfetch` |
| Small Compact View | `fastfetch --config small` |
| Generate Config File | `fastfetch --gen-config` |
| Custom Image Logo | `fastfetch --logo image.png --logo-type iterm` |
| List Available Logos | `fastfetch --list-logos` |
| List Built-in Presets | `fastfetch --list-presets` |

