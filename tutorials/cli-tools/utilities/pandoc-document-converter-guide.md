# Pandoc Document Converter Guide

`pandoc` is the universal document converter—a "Swiss-army knife" for converting text between over 40 markup formats, including **Markdown, HTML, PDF, Microsoft Word (.docx), EPUB ebooks, LaTeX, Jupyter Notebooks (.ipynb), and presentation slides**.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview--prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Supported Document Formats](#supported-document-formats)
4. [Basic Conversion Commands](#basic-conversion-commands)
5. [Markdown to PDF Conversion Engines](#markdown-to-pdf-conversion-engines)
6. [YAML Metadata & Templates](#yaml-metadata--templates)
7. [💡 Practical Real-World Examples](#-practical-real-world-examples)
8. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

`pandoc` parses document structures into a clean intermediate abstract syntax tree (AST) and serializes it into the target format.

- **Standalone Output (`-s`)**: Generates complete HTML/PDF files including headers, CSS, and footers.
- **Table of Contents (`--toc`)**: Automatically extracts headings into a TOC.

---

## ⚙️ Installation via Homebrew

```bash
# Install pandoc on macOS
brew install pandoc librsvg

# Optional: Install PDF engine tools
brew install --cask wkhtmltopdf

# Verify installation
pandoc --version
```

---

## 📁 Supported Document Formats

| Source Format | Target Formats | Common Command Flag |
| --- | --- | --- |
| **Markdown (`.md`)** | HTML, PDF, DOCX, EPUB, LaTeX | `pandoc -s input.md -o output.html` |
| **Microsoft Word (`.docx`)**| Markdown, HTML, PDF | `pandoc input.docx -o output.md` |
| **HTML (`.html`)** | Markdown, DOCX | `pandoc input.html -o output.md` |
| **LaTeX (`.tex`)** | Markdown, PDF | `pandoc input.tex -o output.md` |

---

## 🚀 Basic Conversion Commands

### 1. Markdown to HTML (`.html`)
```bash
# Generate standalone HTML document with Table of Contents
pandoc -s --toc input.md -o output.html

# Include custom external CSS stylesheet
pandoc -s -c styles.css input.md -o output.html
```

### 2. Markdown to Microsoft Word (`.docx`)
```bash
# Convert Markdown to Word document
pandoc input.md -o output.docx
```

### 3. Markdown to EPUB Ebook (`.epub`)
```bash
# Convert Markdown to EPUB ebook with cover image
pandoc input.md --epub-cover-image=cover.jpg -o book.epub
```

---

## 📄 Markdown to PDF Conversion Engines

Generating PDF documents requires specifying a rendering engine (`--pdf-engine`).

### 1. Using `wkhtmltopdf` (HTML/CSS Based - Recommended)
```bash
# Render PDF using HTML+CSS layout
pandoc input.md --pdf-engine=wkhtmltopdf -o output.pdf
```

### 2. Using `XeLaTeX` (Academic / Math Papers)
```bash
# Render PDF via XeLaTeX (requires MacTeX or BasicTeX)
pandoc input.md --pdf-engine=xelatex --toc -o paper.pdf
```

---

## 📝 YAML Metadata & Templates

Pass title, author, date, and document variables via a YAML metadata block at the top of your Markdown file:

```markdown
---
title: "Technical System Architecture"
author: "Engineering Team"
date: "2026-07-22"
toc: true
toc-depth: 2
geometry: margin=1in
fontsize: 11pt
---

# 1. Introduction
System overview text goes here...
```

Run conversion command:
```bash
pandoc -s architecture.md -o architecture.pdf
```

---

## 💡 Practical Real-World Examples

### Example 1: Creating a Styled HTML Documentation Page
```bash
pandoc -s \
  --toc \
  --metadata title="API Reference Documentation" \
  -c https://cdn.jsdelivr.net/npm/water.css@2/out/water.css \
  api-guide.md \
  -o api-guide.html
```

### Example 2: Batch Converting `.docx` Files to Markdown
```bash
#!/bin/bash
# Convert all Word documents in current folder to Markdown
for doc in *.docx; do
  if [ -f "$doc" ]; then
    base_name="${doc%.*}"
    echo "Converting $doc -> ${base_name}.md..."
    pandoc "$doc" -t markdown -o "${base_name}.md"
  fi
done
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Markdown to HTML | `pandoc -s -o file.html file.md` |
| Markdown to Word | `pandoc -o file.docx file.md` |
| Markdown to PDF | `pandoc --pdf-engine=wkhtmltopdf -o file.pdf file.md` |
| Word to Markdown | `pandoc -o file.md file.docx` |
| Include Table of Contents | `pandoc -s --toc -o file.html file.md` |
| Apply Custom CSS | `pandoc -s -c style.css -o file.html file.md` |