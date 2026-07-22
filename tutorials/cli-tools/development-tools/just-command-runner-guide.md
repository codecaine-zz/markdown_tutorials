# Just Command Runner Guide

`just` is a handy, modern command runner alternative to `make`. Written in Rust, `just` provides a clean syntax for defining and organizing project automation tasks inside a `justfile` without the tab-indentation traps, syntax quirks, and target-file illusions of traditional Makefiles.

---

## 📚 Table of Contents

1. [Overview & `just` vs `make` Comparison](#overview-just-vs-make-comparison)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Creating a `justfile`](#creating-a-justfile)
4. [Recipes with Arguments & Variables](#recipes-with-arguments-variables)
5. [Listing & Self-Documenting Recipes](#listing-self-documenting-recipes)
6. [Cross-Language Recipes (Python, Node, Bash)](#cross-language-recipes-python-node-bash)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & `just` vs `make` Comparison

Comparing `make` with `just`:

- **`make`**: Designed as a build tool for compiling C source files into object files. If a file exists with the same name as a recipe target, `make` skips it unless marked `.PHONY`.
- **`just`**: Designed strictly as a command runner for project scripts, workflows, and task automation.

---

## ⚙️ Installation via Homebrew

```bash
# Install just on macOS
brew install just

# Verify installation
just --version
```

---

## 📝 Creating a `justfile`

Create a file named `justfile` in your project root directory:

```just
# Default recipe executed when running `just` without arguments
default:
    @just --list

# Build production bundle
build:
    @echo "Building application..."
    npm run build

# Run unit tests
test:
    @echo "Running unit tests..."
    npm test

# Clean build artifacts
clean:
    rm -rf dist/ build/ *.log
```

Execute recipes:

```bash
# Run default recipe (lists recipes)
just

# Execute 'build' recipe
just build

# Execute 'test' recipe
just test
```

---

## 🎛️ Recipes with Arguments & Variables

### 1. Variables & Global Settings
```just
# Define variables
port := "8080"
env  := "development"

# Run dev server
dev:
    @echo "Starting server on port {{port}} in {{env}} mode..."
    python3 -m http.server {{port}}
```

### 2. Passing Command Line Arguments
```just
# Recipe taking positional parameters with default fallback values
deploy target="staging" branch="main":
    @echo "Deploying branch {{branch}} to {{target}}..."
    ./scripts/deploy.sh --env {{target}} --ref {{branch}}
```

Execute with custom arguments:
```bash
# Deploy using default values (staging, main)
just deploy

# Deploy to production with custom branch
just deploy production v2.1.0
```

---

## 📋 Listing & Self-Documenting Recipes

Comments placed above recipes automatically become user documentation when running `just --list`.

```just
# Run linters and typecheckers
lint:
    npx eslint .
    npx tsc --noEmit

# Format code files using Prettier
format:
    npx prettier --write .
```

List available recipes with descriptions:

```bash
just --list
# Output:
# Available recipes:
#     default
#     build       # Build production bundle
#     clean       # Clean build artifacts
#     dev         # Run dev server
#     deploy target="staging" branch="main"
#     format      # Format code files using Prettier
#     lint        # Run linters and typecheckers
#     test        # Run unit tests
```

---

## 🐍 Cross-Language Recipes (Python, Node, Bash)

`just` allows executing recipes using interpreter shebangs (`#!/usr/bin/env python3`, `#!/usr/bin/env node`, etc.):

```just
# Python inline recipe script
check-health:
    #!/usr/bin/env python3
    import urllib.request
    res = urllib.request.urlopen("http://localhost:8080")
    print(f"Server Status Code: {res.getcode()}")
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| List recipes | `just` or `just --list` |
| Execute recipe | `just <recipe-name>` |
| Pass arguments | `just deploy production main` |
| Initialize `justfile` | `touch justfile` |
| Execute recipe in sub-directory | `just -d /path/to/project <recipe>` |
