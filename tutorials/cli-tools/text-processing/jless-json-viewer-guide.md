# jless — Command-Line Interactive JSON Viewer Guide

A complete, production-grade guide to **jless** (`jless`), a high-performance interactive terminal JSON viewer written in **Rust**, featuring collapsible syntax trees, Vim-style navigation, JSON path search, clipboard copying, and smooth inspection of massive multi-gigabyte JSON files.

---

## 📑 Table of Contents

- [1. Overview & Why jless Replaces less for JSON](#1-overview-why-jless-replaces-less-for-json)
  - [The Problem with less and jq for Large Payloads](#the-problem-with-less-and-jq-for-large-payloads)
  - [The jless Advantage: Tree Folding & Instant Navigation](#the-jless-advantage-tree-folding-instant-navigation)
- [2. Installation & Verification](#2-installation-verification)
  - [Installing via Homebrew (macOS & Linux)](#installing-via-homebrew-macos-linux)
  - [Installing via Cargo (Rust)](#installing-via-cargo-rust)
  - [Verifying Binary Installation](#verifying-binary-installation)
- [3. Core Navigation & Keybindings](#3-core-navigation-keybindings)
  - [Vim-Style Movement (j, k, h, l, gg, G)](#vim-style-movement-j-k-h-l-gg-g)
  - [Expanding and Collapsing Nodes (Space, Enter, c, e)](#expanding-and-collapsing-nodes-space-enter-c-e)
  - [Jump to Sibling & Parent Nodes](#jump-to-sibling-parent-nodes)
- [4. Search & JSON Path Filtering](#4-search-json-path-filtering)
  - [Full-Text Search across Keys and Values (/)](#full-text-search-across-keys-and-values)
  - [JSON Path Search Queries](#json-path-search-queries)
  - [Cycling Search Matches (n, N)](#cycling-search-matches-n-n)
- [5. Clipboard Integration & Data Extraction](#5-clipboard-integration-data-extraction)
  - [Copying JSON Paths to Clipboard (yp)](#copying-json-paths-to-clipboard-yp)
  - [Copying Formatted Node Values (yy)](#copying-formatted-node-values-yy)
  - [Piping Output to External Utilities](#piping-output-to-external-utilities)
- [6. Practical Real-World Workflows](#6-practical-real-world-workflows)
  - [Workflow 1: Inspecting Live REST API Responses with curl/xh](#workflow-1-inspecting-live-rest-api-responses-with-curlxh)
  - [Workflow 2: Exploring Massive AWS CloudTrail Logs](#workflow-2-exploring-massive-aws-cloudtrail-logs)
  - [Workflow 3: Debugging Kubernetes Manifests and Docker Inspect](#workflow-3-debugging-kubernetes-manifests-and-docker-inspect)
- [7. Quick Reference Cheat Sheet & FAQ](#7-quick-reference-cheat-sheet-faq)
  - [Keybindings Summary Table](#keybindings-summary-table)
  - [Troubleshooting & Common Pitfalls](#troubleshooting-common-pitfalls)

---

## 1. Overview & Why jless Replaces less for JSON

### The Problem with less and jq for Large Payloads

Inspecting complex or deeply nested JSON in the terminal has traditionally relied on:
1. `jq . file.json | less`: Syntax highlights the payload, but treats it as raw static text. You cannot collapse 10,000-line arrays, jump between sibling objects, or extract JSON paths interactively.
2. `less file.json`: Has no understanding of JSON syntax trees, object boundaries, or key-value structures.

### The jless Advantage: Tree Folding & Instant Navigation

**jless** parses the input into a structured tree in memory:
- **Interactive Folding**: Expand or collapse arrays and objects with a single keypress.
- **Deep Path Resolution**: View the exact JSON path (e.g. `.data.users[14].email`) displayed in the status bar at all times.
- **Instant Clipboard Copying**: Copy either the value or the exact jq-compatible query path directly to the system clipboard without leaving the viewer.
- **Rust Performance**: Loads multi-megabyte payloads in milliseconds with zero interface lag.

```
┌─────────────────────────────────────────────────────────────┐
│                           jless                             │
├─────────────────────────────────────────────────────────────┤
│ ▼ {                                                         │
│     "status": "success",                                    │
│   ▶ "metadata": { ... } (6 keys collapsed)                  │
│   ▼ "users": [                                              │
│       ▶ { ... } (id: 1, name: "Alice")                      │
│       ▼ {                                                   │
│           "id": 2,                                          │
│           "name": "Bob Smith",                              │
│           "roles": ["admin", "developer"]                   │
│         }                                                   │
│     ]                                                       │
│   }                                                         │
├─────────────────────────────────────────────────────────────┤
│ .users[1].name                                  jless 0.9.0 │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Installation & Verification

### Installing via Homebrew (macOS & Linux)

Install the pre-compiled native binary using Homebrew:

```bash
# Install jless via Homebrew
brew install jless

# Verify installation
which jless
jless --version
```

### Installing via Cargo (Rust)

If you have the Rust toolchain installed:

```bash
cargo install jless
```

### Verifying Binary Installation

Test `jless` on a small JSON string piped via stdin:

```bash
echo '{"hello": "world", "tools": ["jless", "visidata", "fx"]}' | jless
```

---

## 3. Core Navigation & Keybindings

### Vim-Style Movement (j, k, h, l, gg, G)

| Key | Action |
| :--- | :--- |
| `j` / `Down` | Move cursor down one line. |
| `k` / `Up` | Move cursor up one line. |
| `h` / `Left` | Move cursor to parent object or collapse current node. |
| `l` / `Right`| Expand current node or step into child element. |
| `gg` | Jump to the very top of the JSON document. |
| `G` | Jump to the very bottom of the JSON document. |
| `Ctrl + d` | Page down (half window). |
| `Ctrl + u` | Page up (half window). |

### Expanding and Collapsing Nodes (Space, Enter, c, e)

- **`Space`** or **`Enter`**: Toggle expand/collapse state of the selected object or array.
- **`c`**: Collapse all children of the currently focused node.
- **`e`**: Expand all children of the currently focused node.

### Jump to Sibling & Parent Nodes

When navigating huge arrays (e.g. 5,000 array elements):
- **`J`**: Jump directly to the **next sibling** at the same nesting level.
- **`K`**: Jump directly to the **previous sibling** at the same nesting level.
- **`u`**: Jump directly up to the **enclosing parent** node.

---

## 4. Search & JSON Path Filtering

### Full-Text Search across Keys and Values (/)

Press **`/`** to enter search mode, type your query, and press **Enter**:
- Searches both object keys (`"email"`) and primitive values (`"alice@corp.com"`).
- Highlights all matching occurrences across the document.

### JSON Path Search Queries

Prefix search with standard dot notation to filter by exact key path:

```text
/.users[*].id
```

jless filters the tree directly to nodes matching the path.

### Cycling Search Matches (n, N)

- **`n`**: Jump to the next search match (auto-expands collapsed parent nodes to reveal matches).
- **`N`**: Jump to the previous search match.

---

## 5. Clipboard Integration & Data Extraction

### Copying JSON Paths to Clipboard (yp)

When cursor is focused on any field (e.g. `"name": "Alice"`):
- Press **`yp`** (Yank Path): Copies the exact jq-compatible path (e.g. `.data.users[0].name`) to your system clipboard.
- You can immediately paste it into `jq` commands or application code!

### Copying Formatted Node Values (yy)

- Press **`yy`** (Yank Value): Copies the formatted JSON string of the focused element to the system clipboard.
- If focused on a collapsed object with 50 keys, `yy` copies the entire object.

### Piping Output to External Utilities

Pass data seamlessly through Unix pipes:

```bash
# Pipe curl directly into jless
curl -s https://api.github.com/repos/rust-lang/rust | jless

# Inspect Docker container metadata
docker inspect my_container | jless
```

---

## 6. Practical Real-World Workflows

### Workflow 1: Inspecting Live REST API Responses with curl/xh

```bash
# Fetch paginated user list and explore interactively
xh GET https://jsonplaceholder.typicode.com/users | jless
```

- Use `J` and `K` to jump between user records.
- Press `yp` on `.address.geo.lat` to copy the field path.

### Workflow 2: Exploring Massive AWS CloudTrail Logs

CloudTrail and Kubernetes audit logs often contain millions of lines:

```bash
# Open a 250 MB CloudTrail log file
jless cloudtrail_audit.json
```

- Press `c` on root to collapse everything.
- Search for error codes with `/AccessDenied`.
- Cycle through incidents with `n`.

### Workflow 3: Debugging Kubernetes Manifests and Docker Inspect

```bash
# Inspect running container environment and mount points
docker inspect $(docker ps -q) | jless
```

---

## 7. Quick Reference Cheat Sheet & FAQ

### Keybindings Summary Table

| Action | Keybinding |
| :--- | :--- |
| **Move Up / Down** | `k` / `j` |
| **Step In / Step Out** | `l` / `h` |
| **Toggle Expand / Collapse** | `Space` or `Enter` |
| **Collapse All Children** | `c` |
| **Expand All Children** | `e` |
| **Jump to Next Sibling** | `J` (Shift + j) |
| **Jump to Prev Sibling** | `K` (Shift + k) |
| **Jump to Parent** | `u` |
| **Search Keys / Values** | `/query` + `Enter` |
| **Next / Prev Match** | `n` / `N` |
| **Copy jq Path** | `yp` |
| **Copy Node Value** | `yy` |
| **Quit** | `q` |

### Troubleshooting & Common Pitfalls

> [!NOTE]
> **Invalid JSON**: If jless reports a parsing syntax error, pass the file through `jq .` or `jsonlint` first to identify trailing commas or unquoted keys.

> [!TIP]
> **Clipboard Support**: On Linux headless environments, ensure `xclip` or `wl-clipboard` is installed for `yp` and `yy` to access the system clipboard.
