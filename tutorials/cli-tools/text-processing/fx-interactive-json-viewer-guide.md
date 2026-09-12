# FX Interactive Terminal JSON Viewer Guide

`fx` is a modern, high-performance interactive terminal JSON viewer and command-line JSON stream processor. Written in Go, it allows you to explore massive JSON objects with collapsible tree nodes, perform fuzzy searches, extract keys, and apply JavaScript/TypeScript transformations directly on the command line.

---

## 📚 Table of Contents

1. [Overview & Why Use `fx`?](#overview-why-use-fx)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Interactive TUI Mode: Folding & Navigation](#interactive-tui-mode-folding-navigation)
4. [Keyboard Navigation & Search](#keyboard-navigation-search)
5. [Non-Interactive CLI Processing & JavaScript Reducers](#non-interactive-cli-processing-javascript-reducers)
6. [Combining `fx` with `curl` & APIs](#combining-fx-with-curl-apis)
7. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
8. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `fx`?

While `jq` is fantastic for scripted data extractions, exploring unfamiliar JSON responses with hundreds of nested fields is painful with static outputs. `fx` solves this with a live, foldable TUI and JavaScript dot-syntax data exploration.

| Feature | `jq` | `fx` |
| :--- | :--- | :--- |
| **Interactive TUI** | None | Full collapsible tree TUI |
| **Search in Buffer** | None | Real-time fuzzy regex search (`/`) |
| **Transformation Language** | Custom `jq` DSL | Standard JavaScript / TypeScript |
| **Node Folding** | None | Collapse/expand individual keys |
| **Large File Support** | High | Extremely fast streaming parser |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install fx
```

### 2. Verify Installation

```bash
fx --version
```

---

## 🚀 Interactive TUI Mode: Folding & Navigation

Pipe any JSON output or file directly into `fx`:

```bash
# Open an existing JSON file
fx package.json

# Pipe from curl / APIs
curl -s https://api.github.com/repos/wagoodman/dive | fx

# Pipe from Docker inspect
docker inspect my-container | fx
```

### Interactive Layout & Tree View:

```text
{
  ▶ "name": "my-awesome-app",
  ▼ "dependencies": {
      "express": "^4.19.2",
      "react": "^18.3.1"
    },
  ▶ "scripts": { ... },
  ▶ "devDependencies": { ... }
}
```

---

## ⌨️ Keyboard Navigation & Search

Inside the interactive `fx` viewer:

| Key | Action |
| :--- | :--- |
| `▲` / `▼` or `k` / `j` | Move selection up and down |
| `Enter` or `Space` | Expand / collapse selected object or array |
| `e` | Expand all nested keys recursively |
| `c` | Collapse all keys |
| `h` / `l` | Collapse / expand current node |
| `/` | Open search bar (fuzzy search / regex) |
| `n` / `N` | Jump to next / previous search match |
| `p` | Copy the path to the selected node (e.g. `.users[0].email`) |
| `y` | Copy the selected JSON value to clipboard |
| `q` or `Esc` | Quit `fx` |

---

## ⚡ Non-Interactive CLI Processing & JavaScript Reducers

You can pass JavaScript functions directly as arguments to `fx` for high-speed command-line transformations:

### 1. Simple Key Extraction (Dot Notation)

```bash
# Extract name property
curl -s https://api.github.com/repos/wagoodman/dive | fx .name
# Output: "dive"

# Extract nested property
curl -s https://api.github.com/repos/wagoodman/dive | fx .owner.login
# Output: "wagoodman"
```

### 2. Array Mapping, Filtering & JS Expressions

```bash
# Map array to extract specific fields
echo '[{"id": 1, "name": "Alice"}, {"id": 2, "name": "Bob"}]' | fx 'x => x.map(u => u.name)'
# Output: ["Alice", "Bob"]

# Filter elements
echo '[10, 25, 30, 5, 80]' | fx 'x => x.filter(n => n > 20)'
# Output: [25, 30, 80]

# Compute sums / aggregations
echo '[10, 20, 30]' | fx 'x => x.reduce((a, b) => a + b, 0)'
# Output: 60
```

---

## 🌐 Combining `fx` with `curl` & APIs

### 1. Inspect GitHub Pull Requests

```bash
curl -s https://api.github.com/repos/facebook/react/pulls | fx
```

### 2. Inspect Kubernetes Configs

```bash
kubectl get deployment my-app -o json | fx
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Interactive View** | `cat data.json \| fx` |
| **Extract Field** | `cat data.json \| fx .key.subkey` |
| **Filter Array** | `cat data.json \| fx 'x => x.filter(...)'` |
| **Map Array** | `cat data.json \| fx 'x => x.map(...)'` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# FX shortcuts
alias json='fx'
alias api-view='curl -s "$@" | fx'
```

---

## 🗑️ Uninstallation

To remove `fx`:

```bash
brew uninstall fx
```
