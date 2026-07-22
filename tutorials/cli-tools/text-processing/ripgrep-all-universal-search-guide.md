# Ripgrep-All (rga) Universal Search Guide

`ripgrep-all` (`rga`) is a line-oriented search tool that wraps `ripgrep` to search inside rich document formats, binary archives, and media metadata. It seamlessly extracts text from **PDFs, DOCX documents, EPub ebooks, SQLite databases, ZIP archives, and embedded video subtitles**.

---

## 📚 Table of Contents

1. [Overview & Adapters](#overview--adapters)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Supported File Formats](#supported-file-formats)
4. [Basic Universal Searching](#basic-universal-searching)
5. [PDF & Office Document Search](#pdf--office-document-search)
6. [Searching Inside Archives & SQLite](#searching-inside-archives--sqlite)
7. [FZF Interactive Integration (`rga-fzf`)](#fzf-interactive-integration-rga-fzf)
8. [Advanced Adapter Flags & Caching](#advanced-adapter-flags--caching)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Adapters

`rga` works by passing files through dedicated converter tools (adapters) before feeding the extracted plain text into `ripgrep`:

- **Poppler (`pdftotext`)**: Extracts text from PDF files.
- **Pandoc**: Extracts text from `.docx`, `.epub`, `.odt`, `.html`.
- **Tesseract OCR**: Extracts text from images (`.png`, `.jpg`).
- **FFmpeg**: Extracts embedded subtitles (`.mkv`, `.mp4`, `.srt`).
- **SQLite3**: Converts SQLite database tables to readable SQL dumps.

---

## ⚙️ Installation via Homebrew

```bash
# Install rga alongside converter dependencies
brew install ripgrep-all poppler pandoc ffmpeg tesseract

# Verify installation
rga --version
```

---

## 📁 Supported File Formats

| Category | File Extensions | Required Adapter |
| --- | --- | --- |
| **PDF Documents** | `.pdf` | Poppler (`pdftotext`) |
| **Office Documents** | `.docx`, `.odt`, `.epub` | Pandoc |
| **Archives** | `.zip`, `.tar.gz`, `.tgz` | Built-in Zip/Tar |
| **Databases** | `.sqlite3`, `.db` | SQLite3 CLI |
| **Subtitles & Video** | `.mkv`, `.mp4`, `.vtt`, `.srt` | FFmpeg |
| **Images (OCR)** | `.png`, `.jpg`, `.tiff` | Tesseract OCR |

---

## 🚀 Basic Universal Searching

### 1. Simple Keyword Search Across All File Types
```bash
# Search for 'invoice' across current directory PDFs, DOCX, ZIPs, etc.
rga "invoice"

# Search case-insensitively
rga -i "confidential"
```

### 2. Show File Names Only (`-l`)
```bash
# List matching documents without printing content lines
rga -l "Quarterly Revenue"
```

---

## 📄 PDF & Office Document Search

```bash
# Search inside all PDF files under ~/Documents
rga "Total Balance" ~/Documents --type pdf

# Search inside Word documents (.docx)
rga "Executive Summary" --rga-adapters=pandoc

# Limit search depth
rga "contract agreement" --max-depth 2
```

---

## 📦 Searching Inside Archives & SQLite

### 1. Zip / Tar Archives
`rga` automatically uncompresses and searches files nested inside archives without extracting them to disk.

```bash
# Search log lines inside zipped archive files
rga "FATAL ERROR" backups/logs.zip
```

### 2. SQLite Database Tables
```bash
# Search table values and columns inside SQLite databases
rga "user_email@example.com" data/app.db
```

---

## 🎯 FZF Interactive Integration (`rga-fzf`)

`rga` includes a built-in wrapper `rga-fzf` that pairs `rga` with `fzf` for interactive document searching with live text previews.

```bash
# Launch interactive search for PDFs and documents
rga-fzf
```

Add a custom function to `~/.zshrc` to search and immediately open matching PDFs in macOS Preview:

```bash
# Open matching document from rga search in macOS Preview
rfpdf() {
  local file
  file=$(rga-fzf "$1")
  if [[ -n "$file" ]]; then
    open "$file"
  fi
}
```

---

## ⚡ Advanced Adapter Flags & Caching

To avoid re-extracting text from large PDFs or archives, `rga` caches extracted text in `~/.cache/ripgrep-all`.

```bash
# Disable caching if files change rapidly
rga --rga-no-cache "query"

# List enabled adapters
rga --rga-list-adapters

# Enable Tesseract OCR adapter for scanned PDF images
rga --rga-adapters=+tesseract "scanned text"
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Search all documents | `rga "query"` |
| Search PDF files only | `rga "query" -g "*.pdf"` |
| Case-insensitive search | `rga -i "query"` |
| List files only | `rga -l "query"` |
| Interactive FZF Search | `rga-fzf` |
| Enable OCR search | `rga --rga-adapters=+tesseract "query"` |
| Clear cache | `rm -rf ~/.cache/ripgrep-all` |