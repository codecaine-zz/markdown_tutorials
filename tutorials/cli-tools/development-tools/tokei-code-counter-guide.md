# tokei: Blazingly Fast Code Statistics & Line Counter

## Table of Contents

1. [What is `tokei`?](#1-what-is-tokei)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Usage & Output](#4-basic-usage-output)
5. [Filtering Languages & Excluding Directories](#5-filtering-languages-excluding-directories)
6. [Advanced Features & Output Formats](#6-advanced-features-output-formats)
7. [Uninstallation](#7-uninstallation)

---

### 1. What is `tokei`?

`tokei` (clock/time in Japanese) is a fast, lightweight code statistics CLI utility written in Rust. It counts lines of code (LOC), comments, and blank lines across hundreds of programming languages in milliseconds, ignoring files listed in `.gitignore`.

#### Why use `tokei` over `cloc` or `wc`?
* **High Speed:** Processes hundreds of thousands of lines of code in seconds on Apple Silicon.
* **Accurate Parsing:** Distinguishes between executable code lines, inline comments, multi-line block comments, and blank spaces.
* **Multi-Language Detection:** Automatically categorizes polyglot projects into separate language rows.

---

### 2. Prerequisites

Verify Homebrew installation:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `tokei` via Homebrew:

```bash
brew install tokei
```

Verify installation:

```bash
which tokei
tokei --version
```

**Expected Output:**
```text
/opt/homebrew/bin/tokei
tokei 12.x.x
```

---

### 4. Basic Usage & Output

To analyze the project in your current working directory:

```bash
tokei
```

**Example Output:**

```text
===============================================================================
 Language            Files        Lines         Code     Comments       Blanks
===============================================================================
 JavaScript              5          450          320           50           80
 Markdown                3          210          180            0           30
 Python                 12         1420          980          240          200
 TypeScript              8          890          670          110          110
 YAML                    2           65           60            0            5
===============================================================================
 Total                  30         3035         2210          400          425
===============================================================================
```

---

### 5. Filtering Languages & Excluding Directories

#### Analyze Specific Languages Only (`-e` / `--type`)
To inspect only Python and Rust files:

```bash
tokei -t Python,Rust
```

#### Exclude Folders (`-e` / `--exclude`)
Exclude heavy build outputs or vendor folders (`node_modules`, `vendor`, `dist`):

```bash
tokei --exclude node_modules --exclude dist
```

#### Analyze Specific Directory Path
```bash
tokei src/
```

---

### 6. Advanced Features & Output Formats

#### Include Individual File Breakdowns (`-f` / `--files`)
To view detailed statistics for every file in the directory:

```bash
tokei --files
```

**Example Output:**
```text
-------------------------------------------------------------------------------
 Language            Files        Lines         Code     Comments       Blanks
-------------------------------------------------------------------------------
 Python
  src/app.py                        150          110           20           20
  src/utils.py                       80           60           10           10
-------------------------------------------------------------------------------
```

#### Output JSON / YAML for CI/CD Pipelines (`-o`)
Generate JSON metrics for programmatic reporting or benchmark tracking:

```bash
tokei -o json
```

**Example JSON snippet:**
```json
{
  "JavaScript": {
    "blanks": 80,
    "code": 320,
    "comments": 50,
    "lines": 450
  }
}
```

---

### 7. Uninstallation

```bash
brew uninstall tokei
```
