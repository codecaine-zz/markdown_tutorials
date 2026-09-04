# JNV (Interactive jq Filter) JSON Navigator Guide

`jnv` is an interactive JSON navigator and live query builder written in Rust. Powered by `jq` filter syntax, `jnv` lets you compose complex `jq` queries with real-time live previewing, automatic key auto-completion, and syntax validation as you type.

---

## 📚 Table of Contents

1. [Overview & Why Use `jnv`?](#overview--why-use-jnv)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Basic Usage & Terminal Interface](#basic-usage--terminal-interface)
4. [Auto-Completion & Live Query Previewing](#auto-completion--live-query-previewing)
5. [Interactive Keyboard Navigation](#interactive-keyboard-navigation)
6. [Exporting Query Results to Clipboard or stdout](#exporting-query-results-to-clipboard-or-stdout)
7. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
8. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `jnv`?

Constructing non-trivial `jq` expressions (like `.items[] | select(.status == "active") | {id, name}`) typically involves tedious trial-and-error in the terminal. `jnv` eliminates the guesswork by providing:

- **Live Preview Window**: Updates the filtered JSON result instantly with every keystroke.
- **Intelligent Auto-Completion**: Suggests available object properties and array paths based on the actual input payload.
- **Syntax Hints & Error Detection**: Highlights malformed expressions immediately.

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install jnv
```

### 2. Install via Cargo

```bash
cargo install jnv
```

### 3. Verify Installation

```bash
jnv --version
```

---

## 🚀 Basic Usage & Terminal Interface

Pipe JSON data or pass a JSON file directly into `jnv`:

```bash
# Open an existing JSON file
jnv package.json

# Pipe from curl or APIs
curl -s https://api.github.com/users/octocat/repos | jnv

# Pipe from kubectl / docker
docker inspect my-container | jnv
```

### The Interface Layout:

```text
Filter: .[0].owner. |
--------------------------------------------------------------------------------
Suggestions: [ login, id, node_id, avatar_url, html_url, type ]
--------------------------------------------------------------------------------
{
  "login": "octocat",
  "id": 583231,
  "type": "User"
}
```

---

## ⚡ Auto-Completion & Live Query Previewing

1. Start typing a dot `.` in the query input bar.
2. `jnv` presents a popup list of all matching object keys found in the current JSON schema.
3. Press `Tab` or `Ctrl + n` to cycle through suggested properties.
4. Chain functions like `| map(...)`, `| select(...)`, and `| keys`.

---

## ⌨️ Interactive Keyboard Navigation

| Key | Action |
| :--- | :--- |
| `Tab` / `Ctrl + n` | Select next suggestion |
| `Shift + Tab` / `Ctrl + p` | Select previous suggestion |
| `Enter` | Complete query and output result to terminal / stdout |
| `Ctrl + c` / `Esc` | Cancel and exit `jnv` |
| `Ctrl + u` | Clear the filter query line |
| `▲` / `▼` | Scroll through the preview window |

---

## 📋 Exporting Query Results to Clipboard or stdout

Once you have refined your `jq` filter in `jnv`:

* **Print to stdout (`Enter`)**: Pressing `Enter` exits `jnv` and outputs the filtered JSON to standard output, making it easy to pipe to other terminal utilities.
* **Copy Output to Clipboard**: Pipe the `jnv` output directly to `pbcopy` on macOS:
  ```bash
  cat data.json | jnv | pbcopy
  ```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Interactive Query** | `cat data.json \| jnv` |
| **Query API endpoint** | `curl -s <url> \| jnv` |
| **Pass file directly** | `jnv file.json` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# JNV shortcuts
alias jq-live='jnv'
alias api-filter='curl -s "$@" | jnv'
```

---

## 🗑️ Uninstallation

To remove `jnv`:

```bash
brew uninstall jnv
```
