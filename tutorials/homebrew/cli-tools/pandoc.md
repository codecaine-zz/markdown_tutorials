# 1.  What is Pandoc?

Pandoc is a “Swiss‑army knife” for converting between over 45 markup and word‑processing formats (Markdown, LaTeX, DOCX, ePub, HTML, man pages, etc.).  
It runs on macOS, Linux, and Windows and is heavily scriptable from the command line.

> **Why use it?**  
> - One command converts to multiple targets.  
> - You can mix styles, templates, and filters without leaving the shell.  
> - It is fully open source and community‑maintained.

---

## 2.  Prerequisites

### 2.1  Install Homebrew (macOS & Linux)

If you don’t have Homebrew, install it by pasting the following into a terminal:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

*For more details see <https://brew.sh/>.*

---

## 3.  Installing Pandoc

```bash
brew install pandoc
```

*The Homebrew formula shows the same command*【1†L4-L8】.

After installation:

```bash
pandoc --version
```

You should see something like:

```
pandoc 3.8
Compiled with pandoc-types 1.22.0, cmark 0.29.0
```

---

## 4.  Basic Conversion

All conversions are invoked with the pattern  

```
pandoc [options] -o output_file input_file
```

| Example | Command | Result |
|---------|---------|--------|
| Markdown → HTML fragment | `pandoc sample.md -o sample-fragment.html` | `sample-fragment.html` |
| Markdown → PDF (via XeLaTeX) | `pandoc sample.md --pdf-engine=xelatex -o sample.pdf` | `sample.pdf` |

*The first example mimics the official demo*【3†L18-L22】.

> **Tip:** Use `-s` (stand‑alone) when you need a complete document (with `\documentclass` for LaTeX or a full `<html>` block for HTML).  
>  
> `pandoc -s sample.md -o sample.pdf`

---

## 5.  Common Use‑Case Conversions

Below is a quick‑reference list of the most frequent conversions.  
Feel free to copy & paste into your terminal.

| Input | Target | Command |
|-------|--------|---------|
| Markdown → HTML | `pandoc -s -o out.html in.md` |
| Markdown → DOCX | `pandoc -o out.docx in.md` |
| Markdown → ePub | `pandoc -o out.epub in.md` |
| DOCX → Markdown | `pandoc -o out.md in.docx` |
| LaTeX → Markdown | `pandoc -s -o out.md in.tex` |
| Markdown → PDF (Table of contents) | `pandoc --toc --pdf-engine=xelatex -o out.pdf in.md` |
| Markdown → Beamer (slides) | `pandoc -t beamer -s in.md -o slides.pdf` |

---

## 6.  Enhancing Output with Options

### 6.1  Stand‑alone and Table of Contents

```bash
pandoc -s --toc -o mydoc.pdf mydoc.md
```

### 6.2  CSS, Headers, and Footers (for HTML)

```bash
pandoc -s --toc -c style.css -H header.html -F footer.html mydoc.md -o mydoc.html
```

*See the official demo for the same pattern*【3†L30-L35】.

### 6.3  Custom PDF Layout

```bash
pandoc -N \
  -V geometry=margin=1.2in \
  -V mainfont="Palatino" \
  -V sansfont="Helvetica" \
  -V monofont="Menlo" \
  -V fontsize=12pt \
  -V version=2.0 \
  --include-in-header fancyheaders.tex \
  --pdf-engine=lualatex \
  --toc \
  -o fancy.pdf \
  source.md
```

**Explanation**

- `-N` → number sections.  
- `-V` → LaTeX variables that the template can use.  
- `--include-in-header` → adds raw LaTeX to `<head>`.  
- `--pdf-engine` chooses the LaTeX engine (XeLaTeX, LuaLaTeX, or pdflatex).

---

## 7.  Metadata & YAML Front Matter

Pandoc can ingest metadata directly at the start of a document using YAML.  

```markdown
---
title: “My Great Document”
author: “Jane Doe”
date: 2025-10-15
toc: true
---
```

Or via the command line:

```bash
pandoc -M title="My Great Document" -M author="Jane Doe" -M toc=true -o out.pdf in.md
```

For large metadata files, place them in a separate `.yaml` file:

```bash
pandoc --metadata-file meta.yaml -o out.pdf in.md
```

---

## 8.  Templates

Pandoc ships with generic templates; you can override them with your own.  

```bash
pandoc -t markdown -o out.md in.docx --template=mydoctemplate.tex
```

To see the default templates:

```bash
pandoc --verbose
```

*(The default template files accompany the Pandoc source; you can copy them for editing.)*

---

## 9.  Filters & Extensions

Pandoc supports Lua filters (`--lua-filter`) and other extensions.  
A **citation filter** (`citeproc`) is built‑in, but you can add custom ones.

> **Example: add numbers to footnotes using Lua.**

Create `footnote.lua`:

```lua
-- footnote.lua
function Footnote(el)
  el.number = nil
  return el
end
```

Run:

```bash
pandoc -o out.pdf in.md --lua-filter footnote.lua
```

> **Tip:** Install more filters via Homebrew if needed:

```bash
brew install pandoc-crossref   # for cross‑references
brew install pandoc-include   # for including external files
```

---

## 10.  Batch Conversion Scripts

### 10.1  Bash: Convert all Markdown files in a folder to PDF

```bash
#!/usr/bin/env bash
mkdir -p pdf
for md in *.md; do
  base=${md%.md}
  pandoc -s --toc --pdf-engine=xelatex -o pdf/"$base".pdf "$md"
done
```

```bash
chmod +x convert_all.sh
./convert_all.sh
```

### 10.2  Makefile: Document pipeline

```makefile
SRCS := $(wildcard *.md)
PDFS := $(SRCS:.md=.pdf)

.PHONY: all clean

all: $(PDFS)

%.pdf: %.md
        pandoc -s --toc --pdf-engine=xelatex -o "$@" "$<"

clean:
        rm -f $(PDFS)
```

Run `make` and `make clean`.

---

## 11.  Common Pitfalls and Fixes

| Symptom | Likely Cause | Fix |
|---------|--------------|-----|
| PDF contains garbled text or missing fonts | Missing LaTeX engine | `brew install --cask mactex` (macOS) or `sudo apt install texlive-full` (Linux). |
| “Error: No engine specified” | Not specifying `--pdf-engine` | Add `--pdf-engine=xelatex` (or `lualatex`/`pdflatex`). |
| “Unknown metadata field” | Using metadata names not recognized | Use correct YAML keys (`title`, `author`, `date`, `toc`). |
| “Cannot find filter” | Filter not installed | `brew install pandoc-crossref` etc., or ensure filters are on `$PATH`. |

---

## 12.  Wrap‑up

You’ve just:

1. Installed Pandoc with Homebrew.  
2. Learned how to convert between many formats.  
3. Taught Pandoc to apply templates, metadata, and filters.  
4. Built simple scripts to automate bulk conversion.

**Next steps**

- Explore the full list of command‑line options:  
  `pandoc --help` or <https://pandoc.org/MANUAL.html>.
- Look at the official “Demos” page for more advanced examples: <https://pandoc.org/demos.html>【3†L0-L31】.
- Dive into the Lua filter documentation: <https://pandoc.org/lua-filters.html>.
- Add Pandoc to your static‑site generator workflow (Hugo, Jekyll, etc.) for perfect Markdown → HTML conversion.

Enjoy converting your documents with the power of one command!