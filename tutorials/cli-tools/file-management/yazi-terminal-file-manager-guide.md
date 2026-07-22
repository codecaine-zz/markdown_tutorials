# yazi: Blazingly Fast Terminal File Manager

## Table of Contents

1. [What is `yazi`?](#1-what-is-yazi)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Navigation & Controls](#4-basic-navigation-controls)
5. [Key Features & Practical Examples](#5-key-features-practical-examples)
6. [Image & Media Preview Setup](#6-image-media-preview-setup)
7. [Customizing Configuration (`yazi.toml`)](#7-customizing-configuration-yazitoml)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `yazi`?

`yazi` (meaning "duck" in Chinese) is a modern, blazingly fast terminal file manager written in Rust. Designed around asynchronous I/O, non-blocking disk operations, and built-in image preview capabilities, `yazi` delivers a smooth, browser-like file navigation experience directly inside your terminal emulator.

#### Why use `yazi` instead of traditional file managers?
* **Async Engine:** Heavy tasks like computing folder sizes, indexing directories, or loading thumbnails run seamlessly in the background without freezing the UI.
* **Native Image & Media Preview:** Supports inline high-resolution image and video thumbnail previews using modern terminal protocols (Kitty, Chafa, Ueberzug++).
* **Vim-like Keybindings:** Intuitive `h`, `j`, `k`, `l` navigation and modal editing.
* **Built-in Search & Filter:** Instant fuzzy search powered by `fzf`, `fd`, and `ripgrep`.

---

### 2. Prerequisites

Before installing `yazi`, verify that Homebrew is installed on your Apple Silicon (ARM) Mac:

```bash
brew --version
```

**Expected Output:**
```text
Homebrew 4.x.x
Homebrew/homebrew-core (git revision ...)
```

---

### 3. Installation on ARM macOS

Install `yazi` along with its recommended image preview dependencies (`ffmpegthumbnailer`, `7z`, `jq`, `poppler`, `fd`, `ripgrep`, `fzf`, and `imagemagick`):

```bash
brew install yazi ffmpegthumbnailer 7z jq poppler fd ripgrep fzf imagemagick
```

#### Verify Installation

Check that the ARM64 binary is installed under `/opt/homebrew`:

```bash
which yazi
yazi --version
```

**Expected Output:**
```text
/opt/homebrew/bin/yazi
yazi 0.3.x (or latest)
```

---

### 4. Basic Navigation & Controls

To launch `yazi` in your current working directory:

```bash
yazi
```

#### Core Keybindings

| Key | Action |
|---|---|
| `h` / `Left` | Navigate to parent directory |
| `l` / `Right` / `Enter` | Open selected file or directory |
| `k` / `Up` | Move cursor up |
| `j` / `Down` | Move cursor down |
| `q` | Exit `yazi` |
| `Space` | Select/deselect file |
| `v` | Toggle visual selection mode |
| `/` | Filter files in the current view |
| `z` | Jump to directory using `zoxide` integration |
| `Ctrl + c` | Copy selected files |
| `Ctrl + x` | Cut selected files |
| `Ctrl + v` | Paste files |
| `d` `d` | Move selected files to Trash |

---

### 5. Key Features & Practical Examples

#### Example 1: Searching for Files with `fd` inside Yazi
1. Press `f` inside `yazi` to bring up the file finder prompt.
2. Type a keyword (e.g., `report`).
3. `yazi` instantly narrows down matching files across the folder tree.

```text
┌─ Search: report ─────────────────────────────────────────────────────────────┐
│ > src/reports/annual_2025.pdf                                                │
│   docs/financial_report.xlsx                                                 │
└──────────────────────────────────────────────────────────────────────────────┘
```

#### Example 2: Text Search inside Files (`ripgrep`)
1. Press `s` to activate content search.
2. Enter the string you want to locate (e.g., `DATABASE_URL`).
3. Highlighted matches will display live preview snippets in the right-hand panel.

#### Example 3: Changing Directory on Exit (Shell Wrapper)
By default, quitting `yazi` returns your shell to the original working directory. To update your shell location to whichever folder you navigated to inside `yazi`, add this function to `~/.zshrc`:

```bash
function y() {
	local tmp="$(mktemp -t "yazi-cwd.XXXXXX")"
	yazi "$@" --cwd-file="$tmp"
	if cwd="$(cat -- "$tmp")" && [ -n "$cwd" ] && [ "$cwd" != "$PWD" ]; then
		builtin cd -- "$cwd"
	fi
	rm -f -- "$tmp"
}
```

Now, launch `yazi` by typing `y` instead of `yazi`. When you press `q`, your terminal prompt will be in the folder you selected.

---

### 6. Image & Media Preview Setup

`yazi` supports high-performance graphics protocols. For optimal image preview rendering on macOS:

* **Ghostty / Kitty / WezTerm:** Works automatically out-of-the-box using native terminal graphics protocols.
* **iTerm2:** Turn on iTerm2 inline image support inside `yazi.toml`:

```toml
[plugin]
previewers = [
    { mime = "image/*", run = "image" }
]
```

---

### 7. Customizing Configuration (`yazi.toml`)

Create your personal configuration directory:

```bash
mkdir -p ~/.config/yazi
```

Create `~/.config/yazi/yazi.toml` to customize layout, keybindings, and sorting:

```toml
[manager]
ratio          = [1, 2, 4]
sort_by        = "alphabetical"
sort_sensitive = false
sort_reverse   = false
sort_dir_first = true
show_hidden    = true
show_symlink   = true

[preview]
tab_size        = 4
max_width       = 1000
max_height      = 1000
image_quality   = 85
```

---

### 8. Uninstallation

To remove `yazi` and its optional preview dependencies:

```bash
brew uninstall yazi ffmpegthumbnailer poppler
rm -rf ~/.config/yazi
```
