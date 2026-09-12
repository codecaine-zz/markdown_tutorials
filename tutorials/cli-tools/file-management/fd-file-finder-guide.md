# fd: Fast & Intuitive CLI File Finder Guide

## Table of Contents

1. [What is `fd`?](#1-what-is-fd)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Search by Extension (`-e` / `--extension`)](#4-search-by-extension--e---extension)
5. [Common Search & Filtering Options](#5-common-search-filtering-options)
   - [Search by File Type (`-t` / `--type`)](#search-by-file-type--t---type)
   - [Search by File Size (`-S` / `--size`)](#search-by-file-size--s---size)
   - [Search by Modification Time (`--changed-within` / `--changed-before`)](#search-by-modification-time---changed-within---changed-before)
   - [Control Search Depth (`-d` / `--max-depth`)](#control-search-depth--d---max-depth)
   - [Hidden & Ignored Files (`-H`, `-I`, `-u`)](#hidden-ignored-files--h--i--u)
6. [Executing Commands on Found Files (`-x` vs `-X`)](#6-executing-commands-on-found-files--x-vs--x)
   - [Single File Execution (`-x` / `--exec`)](#single-file-execution--x---exec)
   - [Batch File Execution (`-X` / `--exec-batch`)](#batch-file-execution--x---exec-batch)
7. [Real-World Copy & Paste Recipes](#7-real-world-copy-paste-recipes)
   - [Delete `node_modules` or `.DS_Store` Files](#delete-node_modules-or-ds_store-files)
   - [Find and Remove Empty Files / Folders](#find-and-remove-empty-files-folders)
   - [Bulk Rename File Extensions](#bulk-rename-file-extensions)
   - [Batch Search & Replace Text inside Files (`fd` + `sed`)](#batch-search-replace-text-inside-files-fd-sed)
   - [Set Correct Permissions for Files & Directories](#set-correct-permissions-for-files-directories)
   - [Find Broken Symlinks](#find-broken-symlinks)
   - [Interactive Search with `fzf`](#interactive-search-with-fzf)
8. [macOS Finder & `mdfind` CLI Search Commands](#8-macos-finder-mdfind-cli-search-commands)
   - [Search by Extension with Spotlight (`mdfind`)](#search-by-extension-with-spotlight-mdfind)
   - [Opening Search Results in Program of Choice (`open -a`)](#opening-search-results-in-program-of-choice-open--a)
9. [Comparison: `fd` vs `find` vs `mdfind`](#9-comparison-fd-vs-find-vs-mdfind)
10. [Uninstallation](#10-uninstallation)

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

### 5. Common Search & Filtering Options

`fd` provides flags to filter results by type, size, modification date, and directory depth.

#### Search by File Type (`-t` / `--type`)

Filter results by file system type:

```bash
# Find directories only (-t d)
fd -t d build

# Find regular files only (-t f)
fd -t f config

# Find symbolic links (-t l)
fd -t l

# Find executable files (-t x)
fd -t x

# Find empty files or directories (-t e)
fd -t e
```

#### Search by File Size (`-S` / `--size`)

Filter files based on size (`+` for larger than, `-` for smaller than):

```bash
# Find files larger than 100 Megabytes
fd -S +100M

# Find files smaller than 10 Kilobytes
fd -S -10k

# Find large files (>500M) and display file metadata (-l for detailed list)
fd -S +500M -l
```

#### Search by Modification Time (`--changed-within` / `--changed-before`)

Filter files modified within a specific timeframe:

```bash
# Find files modified within the last 24 hours
fd --changed-within 24h

# Find files modified within the last 7 days
fd --changed-within 7d

# Find files modified before 2 weeks ago
fd --changed-before 2w
```

#### Control Search Depth (`-d` / `--max-depth`)

Limit how deep `fd` traverses directory levels:

```bash
# Search only in current directory (depth 1)
fd -d 1 -e md

# Limit search depth to 2 subdirectories
fd -d 2 config
```

#### Hidden & Ignored Files (`-H`, `-I`, `-u`)

By default, `fd` ignores hidden files (`.dotfiles`) and pattern entries in `.gitignore`. Use these flags to expand search scope:

```bash
# Include hidden files (-H / --hidden)
fd -H config

# Include files matching .gitignore (-I / --no-ignore)
fd -I build.log

# Fully unrestricted search (-u / --unrestricted, includes hidden + ignored)
fd -u secret.key
```

---

### 6. Executing Commands on Found Files (`-x` vs `-X`)

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

### 7. Real-World Copy & Paste Recipes

Here are ready-to-use one-liners for common day-to-day developer and system maintenance tasks.

#### Delete `node_modules` or `.DS_Store` Files

Clean up unwanted directories or macOS system metadata files:

```bash
# Find and remove all node_modules directories
fd -H -t d '^node_modules$' -X rm -rf

# Find and delete all .DS_Store files across project subdirectories
fd -H -t f '^\.DS_Store$' -X rm -f
```

#### Find and Remove Empty Files / Folders

Locate zero-byte files or empty folders and clean them up:

```bash
# Find and delete all empty files
fd -t f -t e -X rm -f

# Find and delete all empty directories
fd -t d -t e -X rmdir
```

#### Bulk Rename File Extensions

Rename files matching an extension across an entire directory tree without bash loops:

```bash
# Rename all .jpeg files to .jpg
fd -e jpeg -x mv {} {.}.jpg

# Change all .txt files to .md
fd -e txt -x mv {} {.}.md
```

#### Batch Search & Replace Text inside Files (`fd` + `sed`)

Combine `fd` with macOS `sed` to find and replace text across all project files:

```bash
# Replace 'http://localhost:3000' with 'https://api.example.com' in all .env or .js files
fd -e env -e js -X sed -i '' 's|http://localhost:3000|https://api.example.com|g'
```

#### Set Correct Permissions for Files & Directories

Apply standard POSIX permissions recursively across folders and files separately:

```bash
# Set directory permissions to 755 (rwxr-xr-x)
fd -t d -X chmod 755

# Set file permissions to 644 (rw-r--r--)
fd -t f -X chmod 644
```

#### Find Broken Symlinks

Identify invalid symbolic links pointing to non-existent files:

```bash
# List all broken symlinks
fd -t l --broken

# Remove all broken symlinks
fd -t l --broken -X rm
```

#### Interactive Search with `fzf`

Pipe `fd` search output into `fzf` for fuzzy interactive selection:

```bash
# Interactively search files and open selected file in VS Code
code $(fd -t f | fzf)

# Interactively change directory using fd and fzf
cd $(fd -t d | fzf)
```

---

### 8. macOS Finder & `mdfind` CLI Search Commands

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

### 9. Comparison: `fd` vs `find` vs `mdfind`

| Feature | `fd` | `find` | `mdfind` (macOS Spotlight) |
|---|---|---|---|
| **Syntax for Extension** | `fd -e pdf` | `find . -name "*.pdf"` | `mdfind "kMDItemFSName == '*.pdf'"` |
| **Single Exec** | `fd -e md -x cat {}` | `find . -name "*.md" -exec cat {} \;` | `mdfind "kMDItemFSName == '*.md'" \| xargs -n1 cat` |
| **Batch Exec** | `fd -e md -X open -a Preview` | `find . -name "*.md" -exec open -a Preview {} +` | `mdfind "kMDItemFSName == '*.md'" \| xargs open -a Preview` |
| **Ignores `.gitignore`** | Yes (Default) | No | No |
| **Colorized Output** | Yes | No | No |

---

### 10. Uninstallation

```bash
brew uninstall fd
```