# Ripgrep (rg) Ultra-Fast Search Guide

`ripgrep` (`rg`) is an ultra-fast line-oriented search tool that recursively searches your current directory for regex patterns. Written in Rust, it respects `.gitignore` rules automatically and outperforms tools like `grep`, `ack`, and `ag`.

---

## 📚 Table of Contents

1. [Overview & Performance Benefits](#overview-performance-benefits)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Searching & Smart Case](#basic-searching-smart-case)
4. [File Type Filtering (`-t` / `-T`)](#file-type-filtering--t--t)
5. [Regex, Multiline & Replacement Options](#regex-multiline-replacement-options)
6. [Context Control & Match Limits](#context-control-match-limits)
7. [FZF Interactive Integration](#fzf-interactive-integration)
8. [Configuration File (`RIPGREP_CONFIG_PATH`)](#configuration-file-ripgrep_config_path)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Performance Benefits

- **Gitignore Respect**: Skips hidden files, binary files, and `.gitignore` matches by default.
- **Parallel Scanning**: Uses lock-free parallel directory walking.
- **PCRE2 & SIMD**: Hardware-accelerated regex matching.

---

## ⚙️ Installation via Homebrew

```bash
# Install ripgrep on macOS
brew install ripgrep

# Verify installation
rg --version
```

---

## 🚀 Basic Searching & Smart Case

### 1. Basic Recursive Search
```bash
# Search for keyword 'auth' recursively across all un-ignored files
rg "auth"

# Fixed string match (treat pattern as literal string, not regex)
rg -F "user.getName()"
```

### 2. Smart Case & Case Insensitivity
```bash
# Smart case (-S): case-insensitive if pattern is all lowercase, case-sensitive if uppercase exists
rg -S "user"

# Force case-insensitive match (-i)
rg -i "token"
```

---

## 🎯 File Type Filtering (`-t` / `-T`)

Limit searches to specific programming languages or file types without using complex shell find pipelines.

```bash
# Search only Python files (-t py)
rg "import os" -t py

# Search only JavaScript / TypeScript files (-t js -t ts)
rg "async function" -t js -t ts

# Exclude HTML files (-T html)
rg "styles" -T html

# List all built-in supported file types
rg --type-list
```

---

## 🎛️ Regex, Multiline & Replacement Options

### 1. Multiline Search (`-U`)
Search for patterns spanning across line breaks:

```bash
# Match multi-line blocks where try is followed by catch
rg -U "try \{[\s\S]*?\} catch" -t js
```

### 2. Replace Output Matches (`-r`)
Preview regex string substitutions in terminal output without mutating files:

```bash
# Replace http:// with https:// in matched output preview
rg "http://example.com" -r "https://example.com"
```

---

## 📋 Context Control & Match Limits

Show lines surrounding matching results to understand function contexts.

```bash
# Show 2 lines before and 2 lines after match (-C 2)
rg "handleError" -C 2 -t py

# Show 3 lines after match (-A 3)
rg "function connect" -A 3

# Show 3 lines before match (-B 3)
rg "return res.status" -B 3

# Display matching file paths only (-l)
rg -l "TODO:"
```

---

## 🎯 FZF Interactive Integration

Pair `ripgrep` with `fzf` to create an interactive fuzzy line finder.

```bash
# Interactive ripgrep with fzf preview
rg --line-number --no-heading --color=always "." | fzf --ansi --preview 'bat --style=numbers --color=always --highlight-line {2} {1}'
```

Add to `~/.zshrc`:

```bash
# Interactive file content search function
fif() {
  if [ ! "$#" -gt 0 ]; then echo "Need a search term."; return 1; fi
  rg --files-with-matches --no-messages "$1" | fzf --preview "rg --ignore-case --pretty --context 5 '$1' {}"
}
```

---

## ⚙️ Configuration File (`RIPGREP_CONFIG_PATH`)

Configure default search preferences via `~/.ripgreprc`.

```bash
cat << 'EOF' > ~/.ripgreprc
--smart-case
--colors=path:fg:magenta
--colors=line:fg:green
--colors=match:fg:yellow
--colors=match:style:bold
--type-add
web:*.{html,css,js,ts,jsx,tsx}*
EOF

# Add environment variable to ~/.zshrc
echo 'export RIPGREP_CONFIG_PATH="$HOME/.ripgreprc"' >> ~/.zshrc
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Recursive search | `rg "pattern"` |
| Literal string search | `rg -F "literal.string"` |
| Case insensitive | `rg -i "pattern"` |
| Filter by file type | `rg "pattern" -t py` |
| Exclude file type | `rg "pattern" -T html` |
| Show 2 context lines | `rg "pattern" -C 2` |
| Multiline search | `rg -U "pattern\nsecond"` |
| File paths only | `rg -l "pattern"` |