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

### Example 2: Batch & Bulk Document Conversion

#### Flat Directory Batch Conversion
Convert all Word documents or Markdown files in a single folder:

```bash
# Convert all .docx files in current folder to Markdown
for doc in *.docx; do
  [ -f "$doc" ] || continue
  base_name="${doc%.*}"
  echo "Converting $doc -> ${base_name}.md..."
  pandoc "$doc" -t markdown -o "${base_name}.md"
done

# Convert all .md files in current folder to standalone HTML with TOC
for md in *.md; do
  [ -f "$md" ] || continue
  pandoc -s --toc "$md" -o "${md%.*}.html"
done
```

#### Recursive Documentation Tree Conversion (Preserving Subfolder Hierarchy)
Recursively walk nested documentation folders (e.g. `src_docs/`) and build standalone HTML site files into `dist_site/`, mirroring the exact directory tree:

```bash
#!/bin/bash
# Recursive Markdown Documentation Site Generator

SRC_DIR="src_docs"
DIST_DIR="dist_site"

find "$SRC_DIR" -type f -name "*.md" | while read -r md_file; do
    # Compute relative path inside documentation tree
    rel_path="${md_file#$SRC_DIR/}"
    out_html="$DIST_DIR/${rel_path%.*}.html"

    # Create destination nested folder automatically
    mkdir -p "$(dirname "$out_html")"

    echo "Building document: $md_file -> $out_html"
    pandoc -s --toc \
           -c https://cdn.jsdelivr.net/npm/water.css@2/out/water.css \
           "$md_file" -o "$out_html"
done

echo "Recursive documentation build complete!"
```

#### Parallel Multi-Core Document Build Pipeline
Accelerate building large document sites across CPU cores using `fd` or `xargs`:

```bash
# Parallel Markdown to PDF conversion (4 jobs)
find . -type f -name "*.md" -print0 | xargs -0 -P 4 -I {} sh -c '
    pandoc -s --pdf-engine=wkhtmltopdf "$1" -o "${1%.*}.pdf"
' _ {}

# Fast parallel recursive HTML site generation using `fd`
fd -e md -x pandoc -s --toc {} -o {.}.html
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