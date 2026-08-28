# RIP2 (rm-improved) Safe File Deletion Guide

`rip2` (often run as the `rip` command) is a modern, blazing-fast, and safe alternative to the traditional Unix `rm` command. Written in Rust, it prevents accidental and catastrophic data loss by sending deleted files to a temporary "graveyard" directory with instant undo and recovery capabilities.

---

## 📚 Table of Contents

1. [Overview & `rm` vs `rip2` Comparison](#overview--rm-vs-rip2-comparison)
2. [Prerequisites & Installation](#prerequisites--installation)
3. [Core Concepts: Graveyard & Lifecycle](#core-concepts-graveyard--lifecycle)
4. [Basic File Deletion & Usage](#basic-file-deletion--usage)
5. [Recovering Files with Unbury (`-u`)](#recovering-files-with-unbury--u)
6. [Inspecting Past Deletions with Séance (`-s`)](#inspecting-past-deletions-with-séance--s)
7. [Permanently Decomposing the Graveyard (`-d`)](#permanently-decomposing-the-graveyard--d)
8. [Configuration & Custom Graveyards](#configuration--custom-graveyards)
9. [Hands-On Recovery Walkthrough](#hands-on-recovery-walkthrough)
10. [Aliasing `rm` & Everyday Cheat Sheet](#aliasing-rm--everyday-cheat-sheet)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `rm` vs `rip2` Comparison

Standard Unix `rm` permanently unlinks files immediately, offering no safety net if you mistype a path or wildcard pattern (such as `rm -rf /` or `rm -rf * .ext`). `rip2` provides an ergonomic, lightning-fast replacement.

| Feature | `rip2` (`rip`) | Traditional `rm` | macOS `trash` |
| :--- | :--- | :--- | :--- |
| **Language** | Rust (modern, fast) | C (POSIX standard) | Swift / AppleScript |
| **Data Safety** | Safe (moves to graveyard) | Destructive (permanent) | Safe (moves to OS Trash) |
| **CLI Undo / Restore** | Built-in (`rip -u`) | None | Manual Finder / `mv` |
| **Directory Séance** | Built-in (`rip -s`) | None | None |
| **Recursive Folders** | Automatic (no `-r` needed) | Requires `-r` / `-rf` | Automatic |
| **Cross-Platform** | macOS, Linux, BSD | Unix/Linux/macOS | macOS-focused |

---

## ⚙️ Prerequisites & Installation

### 1. Install via Homebrew (macOS & Linux)

The recommended installation method is using [Homebrew](https://brew.sh/):

```bash
brew install rip2
```

### 2. Install via Cargo (Rust Toolchain)

Alternatively, install directly from crates.io using Cargo:

```bash
cargo install rip2
```

### 3. Verify Installation

Check that the `rip` binary is installed and operational:

```bash
rip --version
```

---

## ⚰️ Core Concepts: Graveyard & Lifecycle

`rip2` uses intuitive cemetery-themed terminology:

- **Burying (`rip <file>`)**: Deletes files or directories by moving them into a designated **Graveyard** directory.
- **Graveyard**: The staging folder where deleted files are stored temporarily.
- **Séance (`rip -s`)**: Lists recently buried files and records associated with the current working directory.
- **Unburying (`rip -u`)**: Restores buried files back to their original paths.
- **Decomposing (`rip -d`)**: Permanently cleans and deletes everything in the graveyard to free disk space.

---

## 🚀 Basic File Deletion & Usage

### 1. Delete a Single File

Unlike `rm`, `rip` deletes single files cleanly and silently:

```bash
rip old_notes.txt
```

### 2. Delete Directories (No `-r` Flag Needed!)

With traditional `rm`, deleting a directory requires `rm -r` or `rm -rf`. With `rip`, you simply pass the directory path:

```bash
# Deletes the entire directory tree safely
rip build_artifacts/
```

### 3. Delete Multiple Files or Use Wildcards

```bash
# Delete multiple specific items
rip debug.log test.png temp_folder/

# Delete all .tmp files in the directory
rip *.tmp
```

### 4. Inspect Before Deleting (`-i` / `--inspect`)

Use the `-i` flag to print detailed metadata about the files (size, path, permissions) before burying them:

```bash
rip -i build/ package-lock.json
```

---

## 🔄 Recovering Files with Unbury (`-u`)

If you accidentally delete a file, `rip2` allows you to restore it immediately without digging through GUI trash cans.

### 1. Restore the Most Recently Deleted File

Running `rip -u` without arguments automatically unburies the last item deleted:

```bash
rip -u
```

### 2. Restore Specific Files by Name

Specify the exact filename or multiple filenames to restore:

```bash
# Restore specific file
rip -u important_document.pdf

# Restore multiple items
rip -u config.json assets/
```

---

## 🔮 Inspecting Past Deletions with Séance (`-s`)

Want to see what files were deleted from the current folder? Run a **séance**:

```bash
rip -s
```

### Example Output:

```text
--- Graveyard for /Users/username/Projects/my-app ---
• index.ts (buried at 2026-08-28 16:40:12) -> /Users/username/Projects/my-app/index.ts
• schema.sql (buried at 2026-08-28 16:42:05) -> /Users/username/Projects/my-app/schema.sql
• temp_cache/ (buried at 2026-08-28 16:45:30) -> /Users/username/Projects/my-app/temp_cache
```

You can then selectively restore any file shown in the séance using `rip -u <filename>`.

---

## 🧹 Permanently Decomposing the Graveyard (`-d`)

When you are confident that you no longer need any files residing in the graveyard, purge it permanently:

```bash
# Decomposes (permanently empties) the graveyard
rip -d
```

> [!WARNING]
> Running `rip -d` is permanent and cannot be undone. All files in the graveyard will be permanently erased.

---

## 🛠️ Configuration & Custom Graveyards

### 1. View Graveyard Location

To see where `rip2` stores buried files:

```bash
rip graveyard
```

### 2. Custom Graveyard Directory

You can override the default graveyard location using the `GRAVEYARD` environment variable or the `--graveyard` option:

```bash
# Set a custom graveyard in your ~/.zshrc or ~/.bashrc
export GRAVEYARD="$HOME/.local/share/graveyard"

# Or pass explicitly per command
rip --graveyard /tmp/custom_graveyard old_file.txt
```

### 3. Generate Shell Completions

Generate auto-completions for fast Tab-completion in your preferred shell:

```bash
# Zsh completions
rip completions zsh > ~/.zfunc/_rip

# Bash completions
rip completions bash > /usr/local/etc/bash_completion.d/rip

# Fish completions
rip completions fish > ~/.config/fish/completions/rip.fish
```

---

## 🧪 Hands-On Recovery Walkthrough

Let's test deleting and recovering files in a sandbox directory:

### Step 1: Create Dummy Files

```bash
mkdir -p ~/rip_demo && cd ~/rip_demo
echo "Critical project notes" > notes.txt
echo "Temporary logs" > error.log
```

### Step 2: Delete Files with `rip`

```bash
rip notes.txt error.log
```

Verify the files are removed:

```bash
ls -la
# Total 0 (files are gone from directory)
```

### Step 3: Run a Séance to View Buried Items

```bash
rip -s
```

Output confirms both files are resting in the graveyard.

### Step 4: Unbury and Restore

```bash
# Restore notes.txt
rip -u notes.txt

# Verify restoration
cat notes.txt
# Output: Critical project notes
```

---

## 📋 Aliasing `rm` & Everyday Cheat Sheet

### Pro-Tip: Safe `rm` Alias

Replace `rm` with `rip` in your `~/.zshrc` or `~/.bashrc` to protect yourself against accidental terminal mistakes:

```bash
# Safe file removal aliases
alias rm='rip'
alias rmi='rip -i'
alias rmu='rip -u'
alias rms='rip -s'
alias rm-empty='rip -d'
```

### Quick Command Reference

| Action | Command |
| :--- | :--- |
| **Delete file or directory** | `rip <file_or_dir>` |
| **Inspect before deleting** | `rip -i <file_or_dir>` |
| **Restore last deleted file** | `rip -u` |
| **Restore specific file** | `rip -u <filename>` |
| **List deleted files in directory** | `rip -s` |
| **Permanently empty graveyard** | `rip -d` |
| **Print graveyard storage path** | `rip graveyard` |
| **Delete with custom graveyard** | `rip --graveyard /path/to/grave <file>` |

---

## 🗑️ Uninstallation

To remove `rip2`:

```bash
# If installed via Homebrew
brew uninstall rip2

# If installed via Cargo
cargo uninstall rip2
```
