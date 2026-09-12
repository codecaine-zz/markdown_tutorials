# Ouch Painless Compression & Decompression Guide

`ouch` is a modern, unified command-line compression and decompression utility written in Rust. It replaces the complex, hard-to-remember syntax of legacy tools like `tar -xvzf`, `unzip`, `gunzip`, `7z`, and `unrar` with an intuitive, universal interface that infers archive formats automatically from file extensions.

---

## 📚 Table of Contents

1. [Overview & `tar` vs `ouch` Comparison](#overview-tar-vs-ouch-comparison)
2. [Supported Archive Formats](#supported-archive-formats)
3. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
4. [Decompressing Archives (`ouch decompress` / `ouch d`)](#decompressing-archives-ouch-decompress-ouch-d)
5. [Creating & Compressing Archives (`ouch compress` / `ouch c`)](#creating-compressing-archives-ouch-compress-ouch-c)
6. [Listing Archive Contents (`ouch list` / `ouch l`)](#listing-archive-contents-ouch-list-ouch-l)
7. [Advanced Flags: Gitignore, Passwords & Multithreading](#advanced-flags-gitignore-passwords-multithreading)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `tar` vs `ouch` Comparison

Traditional Unix compression requires memorizing dozens of obscure single-letter flags across different tools (`tar -czvf`, `tar -jxvf`, `unzip -q`, `7z x`). `ouch` standardizes all formats into three core subcommands: `compress`, `decompress`, and `list`.

| Operation | Traditional Command | `ouch` Command |
| :--- | :--- | :--- |
| **Extract `.tar.gz`** | `tar -xzvf archive.tar.gz` | `ouch d archive.tar.gz` |
| **Extract `.tar.bz2`** | `tar -xjvf archive.tar.bz2` | `ouch d archive.tar.bz2` |
| **Extract `.zip`** | `unzip archive.zip` | `ouch d archive.zip` |
| **Extract `.7z`** | `7z x archive.7z` | `ouch d archive.7z` |
| **Create `.tar.gz`** | `tar -czvf archive.tar.gz folder/` | `ouch c folder/ archive.tar.gz` |
| **Create `.zip`** | `zip -r archive.zip folder/` | `ouch c folder/ archive.zip` |
| **List archive contents** | `tar -tf archive.tar.gz` | `ouch l archive.tar.gz` |

---

## 📦 Supported Archive Formats

`ouch` seamlessly handles single-compression files, multi-archive containers, and nested formats:

- **Archives**: `.tar`, `.zip`, `.7z`, `.rar` (decompression only)
- **Compression algorithms**: `.gz` (Gzip), `.bz` / `.bz2` / `.bz3` (Bzip), `.xz` / `.lzma`, `.zst` (Zstandard), `.lz4`, `.sz` (Snappy), `.br` (Brotli)
- **Combinations**: `.tar.gz`, `.tar.bz2`, `.tar.xz`, `.tar.zst`, `.tar.lz4`, `.tgz`, `.tbz2`, `.txz`

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew (macOS & Linux)

```bash
brew install ouch
```

### 2. Install via Cargo (Rust Toolchain)

```bash
cargo install ouch
```

### 3. Verify Installation

```bash
ouch --version
```

---

## 🚀 Decompressing Archives (`ouch decompress` / `ouch d`)

Decompressing is straightforward: simply pass one or more archive files. `ouch` detects the compression algorithm and unpacks the files.

### 1. Decompress a Single File

```bash
# Decompress into current directory
ouch decompress project.tar.gz
# Or use the short alias:
ouch d project.zip
```

### 2. Decompress Multiple Archives Simultaneously

```bash
# Unpack all zip and tar files in one command
ouch d dataset1.tar.xz dataset2.zip logs.7z
```

### 3. Decompress into a Specific Output Folder (`--dir`)

```bash
# Extract contents directly into a target folder
ouch d archive.tar.gz --dir /path/to/extracted/
```

### 4. Smart Conflict & Clutter Prevention

If an archive contains multiple top-level files rather than a single enclosing folder, `ouch` warns you or organizes the extraction to keep your working directory clean.

---

## 🗜️ Creating & Compressing Archives (`ouch compress` / `ouch c`)

To compress files, specify the files or folders first, and provide the destination archive name at the end.

### 1. Create a `.zip` Archive

```bash
# Compress a single directory
ouch compress my_project/ my_project.zip
# Short alias:
ouch c my_project/ my_project.zip
```

### 2. Create Modern High-Speed `.tar.zst` (Zstandard) Archives

```bash
# Create an ultra-fast Zstandard compressed archive
ouch c large_dataset/ dataset.tar.zst
```

### 3. Combine Multiple Files and Folders into an Archive

```bash
# Pack multiple distinct items into a single archive
ouch c notes.md images/ data.csv backup.tar.gz
```

---

## 📜 Listing Archive Contents (`ouch list` / `ouch l`)

Inspect the files inside an archive without unpacking them to disk:

```bash
# List contents of an archive
ouch list archive.tar.gz
# Short alias:
ouch l backup.zip
```

### Example Output:

```text
archive.tar.gz
├── README.md (1.4 KB)
├── src/
│   ├── main.rs (4.2 KB)
│   └── lib.rs (8.1 KB)
└── Cargo.toml (520 B)
```

---

## 🛡️ Advanced Flags: Gitignore, Passwords & Multithreading

### 1. Respect `.gitignore` When Compressing (`-g` / `--gitignore`)

Exclude files matched by your `.gitignore` file (e.g. `node_modules`, build artifacts, cache files):

```bash
ouch c -g my_repo/ repo_clean_backup.zip
```

### 2. Ignore Hidden Files (`-H` / `--hidden`)

```bash
# Do not include dotfiles or hidden folders (.DS_Store, .git, etc.)
ouch c -H src/ source_code.tar.gz
```

### 3. Encrypted & Password-Protected Archives (`-p` / `--password`)

```bash
# Extract an encrypted zip or 7z archive
ouch d -p "SecretPassword123" confidential.7z
```

### 4. Multithreaded Compression (`-c` / `--threads`)

Control the number of CPU threads used for multi-core compression algorithms like Zstandard, XZ, and Bzip:

```bash
# Compress using 8 CPU cores
ouch c -c 8 database_dump.sql database_dump.sql.zst
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Extract any archive** | `ouch d <archive>` |
| **Extract to specific folder** | `ouch d <archive> --dir <folder>` |
| **Extract multiple archives** | `ouch d *.zip *.tar.gz` |
| **Create `.zip`** | `ouch c <folder> <name>.zip` |
| **Create `.tar.gz`** | `ouch c <folder> <name>.tar.gz` |
| **Create `.tar.zst`** | `ouch c <folder> <name>.tar.zst` |
| **List archive files** | `ouch l <archive>` |
| **Skip Git-ignored files** | `ouch c -g <folder> <name>.zip` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Ouch shortcuts
alias unarchive='ouch d'
alias archive='ouch c'
alias lsar='ouch l'
alias zip-git='ouch c -g'
```

---

## 🗑️ Uninstallation

To remove `ouch`:

```bash
# Homebrew
brew uninstall ouch

# Cargo
cargo uninstall ouch
```
