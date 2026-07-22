# fd: Fast & Intuitive CLI File Finder Guide

## Table of Contents

1. [What is `fd`?](#1-what-is-fd)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Search by Extension (`-e` / `--extension`)](#4-search-by-extension--e---extension)
5. [Executing Commands on Found Files (`-x` vs `-X`)](#5-executing-commands-on-found-files--x-vs--x)
   - [Single File Execution (`-x` / `--exec`)](#single-file-execution--x---exec)
   - [Batch File Execution (`-X` / `--exec-batch`)](#batch-file-execution--x---exec-batch)
6. [macOS Finder & `mdfind` CLI Search Commands](#6-macos-finder--mdfind-cli-search-commands)
   - [Search by Extension with Spotlight (`mdfind`)](#search-by-extension-with-spotlight-mdfind)
   - [Opening Search Results in Program of Choice (`open -a`)](#opening-search-results-in-program-of-choice-open--a)
7. [Comparison: `fd` vs `find` vs `mdfind`](#7-comparison-fd-vs-find-vs-mdfind)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `fd`?

`fd` is a simple, blazingly fast, and user-friendly alternative to the traditional Unix `find` command, written in Rust. It features intuitive syntax, colorful output, parallel directory traversal, and smart defaults (ignoring hidden files and `.gitignore` patterns out of the box).

---

### 2. Prerequisites

Verify Homebrew installation on your Apple Silicon (ARM) Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `fd` via Homebrew:

```bash
brew install fd
```

Verify binary path and version:

```bash
which fd
fd --version
```

**Expected Output:**
```text
/opt/homebrew/bin/fd
fd 10.x.x (or latest)
```

---

### 4. Search by Extension (`-e` / `--extension`)

The `-e` (or `--extension`) flag lets you locate files strictly by their file extension without requiring regex patterns.

#### 1. Search for a Single Extension
Search for all Markdown (`.md`) files in the current folder tree:

```bash
fd -e md
```

**Example Output:**
```text
README.md
docs/installation.md
tutorials/file-management/fd-file-finder-guide.md
```

#### 2. Search for Multiple Extensions
Find all images (`.png`, `.jpg`, `.jpeg`) at once:

```bash
fd -e png -e jpg -e jpeg
```

#### 3. Search Extension inside Specific Target Directory
Search for Python files (`.py`) inside `src/`:

```bash
fd -e py . src/
```

#### 4. Include Hidden Files or Ignored Extensions (`-H` / `-I`)
Search for `.env` or `.gitignore` files normally hidden from default search:

```bash
fd -H -e env
```

---

### 5. Executing Commands on Found Files (`-x` vs `-X`)

`fd` provides powerful execution flags to run any custom CLI program or macOS application on matching files.

#### Placeholder Reference

| Placeholder | Replaced With | Example Output |
|---|---|---|
| `{}` | Full relative path | `docs/report.pdf` |
| `{/}` | File basename (filename only) | `report.pdf` |
| `{.}` | Path without file extension | `docs/report` |
| `{//}` | Parent directory path | `docs` |

---

### Single File Execution (`-x` / `--exec`)

The `-x` flag executes the target program **individually for each matching file** (spawns one process per file in parallel).

#### Example 1: Print contents of all `.txt` files with `cat`
```bash
fd -e txt -x cat {}
```

#### Example 2: Convert every PNG image to WEBP individually with ImageMagick
```bash
fd -e png -x magick {} {.}.webp
```

* `{}` passes `photo.png`
* `{.}.webp` constructs `photo.webp`

#### Example 3: Open each found markdown file individually in VSCode
```bash
fd -e md -x code {}
```

---

### Batch File Execution (`-X` / `--exec-batch`)

The `-X` flag gathers **all matching files into a single batch list** and passes them to the target program all at once as multiple arguments (spawns only **one** process).

#### Example 1: Open all matched PDF documents at once in macOS Preview app
```bash
fd -e pdf -X open -a Preview
```

This runs: `open -a Preview file1.pdf file2.pdf file3.pdf` simultaneously in a single command.

#### Example 2: Count total line statistics for all JavaScript files using `tokei` or `wc`
```bash
fd -e js -X wc -l
```

#### Example 3: Compress all matched `.log` files into a single tar archive
```bash
fd -e log -X tar -cvzf logs_archive.tar.gz
```

---

### 6. macOS Finder & `mdfind` CLI Search Commands

On macOS, you can also search files by extension using the native Spotlight index (`mdfind`) and launch them in macOS Finder or GUI applications (`open`).

#### Search by Extension with Spotlight (`mdfind`)

##### 1. Search for files with extension using raw Spotlight metadata query:
```bash
mdfind "kMDItemFSName == '*.pdf'"
```

##### 2. Search for extension inside a specific directory:
```bash
mdfind -onlyin ~/Documents "kMDItemFSName == '*.docx'"
```

##### 3. Search by file type kind:
```bash
mdfind "kind:markdown"
```

---

#### Opening Search Results in Program of Choice (`open -a`)

Combine `fd` or `mdfind` with `open -a` to launch matching files in specific macOS applications (e.g., VSCode, Preview, QuickTime Player, Safari).

#### Single File Execution with `open -a` (Process Per File)
Open every matched `.mp4` video in QuickTime Player individually:

```bash
fd -e mp4 -x open -a "QuickTime Player" {}
```

#### Batch File Execution with `open -a` (All Files in One Window)
Open all `.md` files together in Visual Studio Code at once:

```bash
fd -e md -X open -a "Visual Studio Code"
```

Open all `.jpg` images together in Preview:

```bash
fd -e jpg -X open -a Preview
```

---

### 7. Comparison: `fd` vs `find` vs `mdfind`

| Feature | `fd` | `find` | `mdfind` (macOS Spotlight) |
|---|---|---|---|
| **Syntax for Extension** | `fd -e pdf` | `find . -name "*.pdf"` | `mdfind "kMDItemFSName == '*.pdf'"` |
| **Single Exec** | `fd -e md -x cat {}` | `find . -name "*.md" -exec cat {} \;` | `mdfind "kMDItemFSName == '*.md'" \| xargs -n1 cat` |
| **Batch Exec** | `fd -e md -X open -a Preview` | `find . -name "*.md" -exec open -a Preview {} +` | `mdfind "kMDItemFSName == '*.md'" \| xargs open -a Preview` |
| **Ignores `.gitignore`** | Yes (Default) | No | No |
| **Colorized Output** | Yes | No | No |

---

### 8. Uninstallation

```bash
brew uninstall fd
```