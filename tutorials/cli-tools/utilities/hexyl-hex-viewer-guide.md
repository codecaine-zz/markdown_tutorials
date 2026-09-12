# Hexyl Colored Command-Line Hex Viewer Guide

`hexyl` is a modern, fast command-line hex viewer written in Rust. Designed to replace legacy hex viewers like `hexdump`, `xxd`, and `od`, `hexyl` uses terminal colors to visually categorize and distinguish different byte types (NULL bytes, ASCII characters, control codes, and binary data).

---

## 📚 Table of Contents

1. [Overview & `xxd` / `hexdump` vs `hexyl` Comparison](#overview-xxd-hexdump-vs-hexyl-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Basic Usage & Byte Color Categories](#basic-usage-byte-color-categories)
4. [Range Slicing: Length, Offsets & Squeezing (`-n` / `-r` / `-v`)](#range-slicing-length-offsets-squeezing)
5. [Display Customizations: Formats, Groups & Columns](#display-customizations-formats-groups-columns)
6. [Inspecting Binary Payloads & Shell Piping](#inspecting-binary-payloads-shell-piping)
7. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
8. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `xxd` / `hexdump` vs `hexyl` Comparison

Standard hex viewers display monochromatic walls of hexadecimal numbers where identifying NULL bytes, printable strings, or headers requires careful manual inspection. `hexyl` highlights bytes based on their ASCII and binary categories.

| Feature | Legacy `xxd` / `hexdump` | `hexyl` |
| :--- | :--- | :--- |
| **Color Highlights** | None (Monochrome) | Colorized by byte category |
| **Byte Groups** | Fixed 16-byte | Configurable grouping (8, 16, etc.) |
| **Paging** | Requires `less` | Auto-detected pager integration |
| **Performance** | C | High-speed Rust |
| **Format Display** | Hex only | Hex, Octal, Decimal, Binary |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install hexyl
```

### 2. Install via Cargo

```bash
cargo install hexyl
```

### 3. Verify Installation

```bash
hexyl --version
```

---

## 🎨 Basic Usage & Byte Color Categories

Run `hexyl` on any binary, executable, image, or raw data file:

```bash
# Inspect a compiled binary or image
hexyl /bin/ls
hexyl logo.png
```

### Color Palette Meaning:

- **Dim Gray (`00`)**: NULL bytes
- **Cyan / Green (`20`-`7E`)**: Printable ASCII characters (readable text, header strings)
- **Yellow / Magenta (`01`-`1F`)**: ASCII control characters (`\n`, `\t`, `\r`, `NUL`)
- **Purple / Red (`80`-`FF`)**: Non-ASCII / Extended binary bytes

---

## ✂️ Range Slicing: Length, Offsets & Squeezing

### 1. Limit Number of Bytes to Inspect (`-n` / `--length`)

```bash
# Inspect the first 128 bytes (magic bytes / header)
hexyl -n 128 firmware.bin

# Inspect 1KB of data
hexyl -n 1KiB disk_dump.img
```

### 2. Specify Starting Offset (`-r` / `--range`)

```bash
# Read 64 bytes starting at offset 0x200 (512 bytes)
hexyl -r 512:+64 executable.wasm

# Inspect bytes between offset 1000 and 1500
hexyl -r 1000:1500 memory.raw
```

### 3. Show Repeated / Duplicate Lines (`-v` / `--no-squeeze`)

By default, `hexyl` condenses identical repeating lines with an asterisk (`*`). Use `-v` to show all bytes:

```bash
hexyl -v -n 256 zeros.bin
```

---

## 🎛️ Display Customizations: Formats, Groups & Columns

### 1. Change Display Base Format (`--base`)

```bash
# View in Octal instead of Hexadecimal
hexyl --base octal test.bin

# View in Decimal
hexyl --base decimal test.bin

# View in Binary (bits)
hexyl --base binary test.bin
```

### 2. Change Bytes per Group & Columns (`--group-size` / `-c`)

```bash
# Display 8 bytes per line
hexyl -c 8 data.bin

# Group by 4 bytes (e.g. 32-bit words)
hexyl --group-size 4 firmware.hex
```

---

## 🛠️ Inspecting Binary Payloads & Shell Piping

`hexyl` seamlessly reads from standard input pipelines:

```bash
# Inspect magic header bytes of an image from curl
curl -sL https://placehold.co/100x100.png | hexyl -n 32

# Inspect compiled WASM or SQLite header bytes
sqlite3 test.db ".dump" | head -n 5 | hexyl
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Inspect file** | `hexyl <file>` |
| **Inspect first 64 bytes** | `hexyl -n 64 <file>` |
| **Inspect range** | `hexyl -r <start>:<end> <file>` |
| **Binary bit view** | `hexyl --base binary <file>` |
| **Do not squeeze duplicates** | `hexyl -v <file>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Hexyl shortcuts
alias hd='hexyl'
alias hex-header='hexyl -n 64'
alias hex-all='hexyl -v'
```

---

## 🗑️ Uninstallation

To remove `hexyl`:

```bash
brew uninstall hexyl
```
