# Modern Terminal Emulators Guide: Ghostty, WezTerm, Kitty & Alacritty

Modern terminal emulators leverage GPU rendering (Metal on macOS, Vulkan/OpenGL on Linux/Windows), native font ligatures, truecolor support, and scriptable configuration to replace legacy, slow terminal emulators.

---

## 📚 Table of Contents

1. [Terminal Emulator Comparison Matrix](#1-terminal-emulator-comparison-matrix)
2. [Ghostty (Zig + Native UI)](#2-ghostty-zig--native-ui)
3. [WezTerm (Rust + Lua Scripting)](#3-wezterm-rust--lua-scripting)
4. [Kitty (C + Python + Graphics Protocol)](#4-kitty-c--python--graphics-protocol)
5. [Alacritty (Rust + Minimalist YAML/TOML)](#5-alacritty-rust--minimalist-yamltoml)
6. [Font Rendering, Ligatures & Nerd Fonts](#6-font-rendering-ligatures--nerd-fonts)
7. [Terminal Graphics Protocols (Kitty vs Sixel vs iTerm2)](#7-terminal-graphics-protocols)
8. [Benchmarking Terminal Latency](#8-benchmarking-terminal-latency)

---

## 1. Terminal Emulator Comparison Matrix

| Feature | Ghostty | WezTerm | Kitty | Alacritty |
| :--- | :--- | :--- | :--- | :--- |
| **Primary Language** | Zig + Native (Swift/GTK) | Rust | C / Python | Rust |
| **Rendering Backend** | Metal / OpenGL | WebGPU / OpenGL | OpenGL | OpenGL |
| **Config Format** | Flat Key-Value | Lua | Flat Key-Value | TOML |
| **Native Tabs / Splits** | Yes (Native OS tabs) | Yes (Built-in multiplexer) | Yes (Internal layouts) | No (Relies on tmux/zellij) |
| **Ligature Support** | Yes | Yes (Configurable harfbuzz) | Yes | No (Intentional omission) |
| **Graphics Protocol** | Kitty protocol | Kitty, Sixel, iTerm2 | Kitty protocol | None |
| **Cross-Platform** | macOS, Linux | macOS, Linux, Windows | macOS, Linux | macOS, Linux, Windows |

---

## 2. Ghostty (Zig + Native UI)

Ghostty combines native platform UI widgets (AppKit on macOS, GTK on Linux) with a custom GPU-accelerated rendering engine written in Zig.

### Installation

```bash
# macOS via Homebrew
brew install --cask ghostty
```

### Configuration (`~/.config/ghostty/config`)

```ini
# Font configuration
font-family = "JetBrainsMono Nerd Font"
font-size = 14
font-feature = ["calt", "liga"]

# Theme and appearance
theme = catppuccin-mocha
background-opacity = 0.95
background-blur-radius = 20
unfocused-split-opacity = 0.7

# Window behavior
window-padding-x = 12
window-padding-y = 12
macos-titlebar-style = transparent

# Keybindings
keybind = super+t=new_tab
keybind = super+d=new_split:right
keybind = super+shift+d=new_split:down
keybind = super+ctrl+left=goto_split:left
keybind = super+ctrl+right=goto_split:right
```

---

## 3. WezTerm (Rust + Lua Scripting)

WezTerm is fully configurable using Lua, featuring built-in SSH multiplexing, tab bars, and rich status line scripting.

### Installation

```bash
# macOS
brew install --cask wezterm

# Ubuntu / Debian
curl -fsSL https://apt.fury.io/wez/gpg.key | sudo gpg --yes --dearmor -o /etc/apt/keyrings/wezterm.gpg
echo "deb [signed-by=/etc/apt/keyrings/wezterm.gpg] https://apt.fury.io/wez/ * *" | sudo tee /etc/apt/sources.list.d/wezterm.list
sudo apt update && sudo apt install wezterm
```

### Configuration (`~/.config/wezterm/wezterm.lua`)

```lua
local wezterm = require 'wezterm'
local config = wezterm.config_builder()

-- Color scheme and font
config.color_scheme = 'Tokyo Night'
config.font = wezterm.font('JetBrains Mono', { weight = 'Regular', italic = false })
config.font_size = 13.5

-- Window padding and blur
config.window_background_opacity = 0.92
config.macos_window_background_blur = 30
config.window_padding = {
  left = 14,
  right = 14,
  top = 10,
  bottom = 10,
}

-- Tab bar layout
config.use_fancy_tab_bar = false
config.tab_bar_at_bottom = true
config.hide_tab_bar_if_only_one_tab = true

-- Keybindings
config.leader = { key = 'a', mods = 'CTRL', timeout_milliseconds = 1000 }
config.keys = {
  { key = '|', mods = 'LEADER|SHIFT', action = wezterm.action.SplitHorizontal { domain = 'CurrentPaneDomain' } },
  { key = '-', mods = 'LEADER', action = wezterm.action.SplitVertical { domain = 'CurrentPaneDomain' } },
  { key = 'h', mods = 'LEADER', action = wezterm.action.ActivatePaneDirection 'Left' },
  { key = 'l', mods = 'LEADER', action = wezterm.action.ActivatePaneDirection 'Right' },
  { key = 'k', mods = 'LEADER', action = wezterm.action.ActivatePaneDirection 'Up' },
  { key = 'j', mods = 'LEADER', action = wezterm.action.ActivatePaneDirection 'Down' },
}

return config
```

---

## 4. Kitty (C + Python + Graphics Protocol)

Kitty is a blazing-fast terminal emulator designed around offloading rendering entirely to the GPU, pioneering the widely adopted Kitty Terminal Graphics Protocol.

### Installation

```bash
# macOS
brew install --cask kitty

# Linux
curl -L https://sw.kovidgoyal.net/kitty/installer.sh | sh /dev/stdin
```

### Configuration (`~/.config/kitty/kitty.conf`)

```ini
# Fonts
font_family      JetBrains Mono
bold_font        auto
italic_font      auto
font_size        14.0
disable_ligatures never

# Window geometry
window_padding_width 12
hide_window_decorations titlebar-only
background_opacity 0.94
background_blur 32

# Scrollback buffer
scrollback_lines 10000

# Theme
include current-theme.conf

# Keyboard shortcuts
map cmd+t new_tab
map cmd+w close_tab
map cmd+enter new_window
map cmd+shift+l next_layout
```

---

## 5. Alacritty (Rust + Minimalist)

Alacritty focuses purely on raw speed and simplicity, deliberately omitting tabs and splits to be paired with terminal multiplexers like `tmux` or `zellij`.

### Installation

```bash
# macOS
brew install --cask alacritty

# Ubuntu / Debian
sudo apt install alacritty
```

### Configuration (`~/.config/alacritty/alacritty.toml`)

```toml
[window]
padding = { x = 12, y = 12 }
dynamic_padding = true
opacity = 0.95
blur = true
decorations = "Buttonless"

[font]
size = 14.0

[font.normal]
family = "JetBrainsMono Nerd Font"
style = "Regular"

[font.bold]
family = "JetBrainsMono Nerd Font"
style = "Bold"

[cursor]
style = { shape = "Beam", blinking = "On" }

[colors.primary]
background = "#1a1b26"
foreground = "#c0caf5"
```

---

## 6. Font Rendering, Ligatures & Nerd Fonts

Modern terminals require properly patched fonts for glyphs, developer icons, and mathematical symbols.

```bash
# Install JetBrains Mono with Nerd Font icons on macOS
brew install --cask font-jetbrains-mono-nerd-font
brew install --cask font-fira-code-nerd-font
```

### Testing Ligatures

In your terminal editor, type the following sequences:

```text
->   =>   !=   ==   >=   <=   |>   <!--   :=
```

If ligatures are active, these character pairs combine into single cohesive programming glyphs.

---

## 7. Terminal Graphics Protocols

Modern CLI utilities (`yazi`, `viu`, `chafa`, `fastfetch`) render inline images in the terminal using graphics escape codes:

| Protocol | Developer | Supported In | Speed |
| :--- | :--- | :--- | :--- |
| **Kitty Protocol** | Kovid Goyal | Kitty, WezTerm, Ghostty | Extremely Fast (Shared memory / file descriptor) |
| **Sixel** | DEC (Vintage) | WezTerm, Foot, XTerm | Moderate |
| **iTerm2 Protocol** | George Nachman | iTerm2, WezTerm, Ghostty | Fast (Base64 chunked streams) |

Test inline graphics display using `chafa`:

```bash
chafa --format=kitty photo.jpg
```

---

## 8. Benchmarking Terminal Latency

You can verify input-to-render latency and throughput using standard CLI tools:

```bash
# Throughput benchmark: Print 1,000,000 lines
time seq 1 1000000

# High-resolution frame rendering test
cat /dev/urandom | head -c 20000000 | base64
```
