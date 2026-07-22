# Micro Terminal Text Editor Guide

**Micro – A Fast, Friendly Terminal‑Editor**  
*(Updated 2025‑10‑16 – includes macOS ARM tips & full plugin workflow)*  

---  

## 1️⃣ What is **micro**?  

Micro is a modern, *terminal‑based* text editor that strives for the “nano‑style” simplicity while offering features that you expect from a full‑featured IDE: syntax highlighting, split panes, multiple cursors, a Lua‑powered plugin system and **default key‑bindings that match most graphical editors** (Ctrl + S, Ctrl + C, etc.).  It is written in Go, distributed as a single static binary and works on macOS, Linux and BSD [2†L4-L7]​.

---

## 2️⃣ Prerequisites  

| Platform | What you need | How to check |
|----------|--------------|--------------|
| macOS (Apple Silicon or Intel) | Homebrew (or the `curl` installer) | `brew --version` |
| Linux (Debian/Ubuntu, Fedora, Arch, etc.) | `curl` or your distro’s package manager | `curl --version` |
| BSD | `pkg` (FreeBSD) or `ports` | `pkg --version` |

> **Tip:** The `curl` installer works everywhere and does not require a package manager: `curl https://getmic.ro | bash` [2†L8-L10].

---

## 3️⃣ Installation  

| Method | Command | Notes |
|--------|---------|-------|
| **Homebrew** (macOS) | `brew install micro` | Official formula, supports Apple‑Silicon bottles [11†L4-L8][11†L21-L27] |
| **APT** (Ubuntu/Debian) | `sudo apt install micro` | Packages are usually a few versions behind upstream. |
| **DNF** (Fedora) | `sudo dnf install micro` | |
| **Pacman** (Arch) | `sudo pacman -S micro` | |
| **Curl (any OS)** | `curl https://getmic.ro | bash` | Installs the latest release to `$HOME/.local/bin`. Add that directory to `$PATH` if needed. |

After installation, verify:

```bash
micro -version   # → e.g. micro 2.0.14
```

---

## 4️⃣ First‑Run – The Interface  

```bash
micro hello.go
```

You’ll see a clean pane with line numbers, a status bar and a *help hint bar*:

```
1 │ package main
2 │
3 │ import "fmt"
4 │
5 │ func main() {
6 │     fmt.Println("Hello")
7 │ }
~                              
"hello.go" 7L, 94B [go]  Help: ctrl-g  Save: ctrl-s  Find: ctrl-f  Quit: ctrl-q  Cmd: ctrl-e
```

* **Top line** – line numbers (toggle with **Ctrl + R**).  
* **Bottom line** – file name, line/byte count, detected filetype and quick‑access hints.

---

## 5️⃣ Essential Key‑Bindings (the “default keys”)  

| Action | Key(s) | Description |
|--------|--------|-------------|
| **File** | **Ctrl + S** – save | `Save` the current buffer [1†L36-L37] |
| | **Ctrl + O** – open | Prompt for a file name [1†L34-L38] |
| | **Ctrl + Q** – quit | Prompt if there are unsaved changes [1†L32-L33] |
| **Navigation** | **Ctrl + R** – toggle line numbers [1†L23-L24] |
| | **Ctrl + L** – go to line | Prompt for a line number |
| | **Ctrl + ← / →** – word‑wise move [1†L66-L69] |
| **Editing** | **Ctrl + C / V / X** – copy / paste / cut [1†L54-L57] |
| | **Ctrl + Z / Y** – undo / redo [1†L58-L60] |
| | **Ctrl + D** – duplicate line [1†L60-L61] |
| | **Ctrl + K** – cut whole line [1†L57-L58] |
| | **Alt + Up / Down** – move line(s) [1†L72-L74] |
| **Search** | **Ctrl + F** – find [1†L25-L27] |
| | **Ctrl + N / P** – next / previous match [1†L26-L27] |
| **Splits & Tabs** | **Ctrl + \\** – vertical split [1†L41-L45] |
| | **Ctrl + ]** – horizontal split [1†L41-L45] |
| | **Ctrl + T** – new tab [1†L46-L51] |
| **Command Bar** | **Ctrl + E** – open command palette [1†L10-L13] |

*You can view the full printable list inside micro with **Ctrl + G** → *Keybindings*.*

---

## 6️⃣ The Command Bar (`Ctrl + E`)  

The command bar works like VS Code’s *Command Palette*. Type a command and press **Enter**.

| Command | Example | What it does |
|--------|---------|--------------|
| `vsplit <file>` | `vsplit notes.md` | Open *notes.md* in a **vertical split** |
| `hsplit <file>` | `hsplit logs.txt` | Open in a **horizontal split** |
| `replace <search> <replace>` | `replace foo bar` | Simple find‑and‑replace |
| `goto <line>` | `goto 42` | Jump to line 42 |
| `save <new‑name>` | `save backup.txt` | Save buffer under a new name |
| `plugin install <name>` | `plugin install fzf` | Install a plugin (see § 9) |

The bar also accepts *shell commands* prefixed with `!` (e.g. `!git status`).

---

## 7️⃣ Interactive Example – Editing Your `~/.gitconfig`

```bash
micro ~/.gitconfig
```

1. **Search** – `Ctrl + F`, type `[user]`, **Enter**.  
2. **Move** – Arrow keys to the `email = …` line.  
3. **Edit** – Simply start typing; micro works in *insert* mode by default.  
4. **Save** – `Ctrl + S`.  
5. **Quit** – `Ctrl + Q`.

You’ve just made a change without ever leaving the terminal!

---

## 8️⃣ Uninstalling  

| Platform | Command |
|----------|---------|
| Homebrew (macOS) | `brew uninstall micro` |
| APT (Ubuntu) | `sudo apt purge micro` |
| Snap (any) | `snap remove micro` |
| Curl install | Delete `$HOME/.local/bin/micro` (or wherever you copied it). |

---

## 9️⃣ Super‑charging micro on macOS ARM (Apple Silicon)  

### 9.1 Plugin Manager Basics  

- List all available plugins:  

  ```bash
  micro -plugin available
  ```

- Install one or many plugins (space‑separated):  

  ```bash
  micro -plugin install filemanager fzf quickfix snippets nordcolors wakatime lsp
  ```

- Verify installation:  

  ```bash
  micro -plugin list
  ```

> There is **no** “`all`” meta‑package; trying `micro -plugin install all` yields *“unknown plugin ‘all’”* (a common source of confusion).

### 9.2 Recommended macOS ARM Stack  

| Tool | Why | Install |
|------|-----|----------|
| **fzf** | Fuzzy file picker (used by `fzf` plugin) | `brew install fzf` |
| **ripgrep** | Fast search for `quickfix` | `brew install ripgrep` |
| **aspell** | Spell‑checking (used by `aspell` plugin) | `brew install aspell` |
| **Language servers** (e.g. `lua-language-server`, `gopls`, `typescript-language-server`) | Enables the `lsp` plugin’s auto‑completion/diagnostics | `brew install lua-language-server gopls typescript-language-server` |
| **WakaTime** | Automatic coding‑time tracking | `brew install wakatime` + add API key in `~/.wakatime.cfg` |

### 9.3 Persisting Settings  

Create `~/.config/micro/settings.json` (micro creates the directory on first run):

```json
{
  "colorscheme": "nordcolors",
  "tabstospaces": true,
  "tabsize": 4,
  "autoindent": true,
  "softwrap": true,
  "clipboard": "external"          // use system clipboard on macOS
}
```

### 9.4 Custom Key‑Bindings  

Add or override shortcuts in `~/.config/micro/bindings.json`:

```json
{
  "Alt-f": "command:fzf",          // fuzzy find
  "Alt-b": "command:filemanager",  // sidebar file tree
  "Alt-q": "command:quickfix",    // diagnostics pane
  "Alt-l": "command:lsp:diagnostics"
}
```

### 9.5 Using Plugins  

1. Open the command bar (`Ctrl + E`).  
2. Type the plugin name (e.g. `fzf`) and hit **Enter** – the plugin’s UI appears.  
3. Many plugins expose extra commands that can also be bound in `bindings.json` (see the plugin’s GitHub README for full list).

### 9.6 Troubleshooting  

| Symptom | Fix |
|---------|-----|
| *“unknown plugin ‘all’”* | Install plugins individually; run `micro -plugin available` to see valid names. |
| Plugin fails to load | Start micro with `micro -debug` – the debug log path is printed; inspect it for “cannot find …” errors. |
| Clipboard doesn’t work | Ensure `clipboard = "external"` in `settings.json` and that `pbcopy`/`pbpaste` are in your `$PATH`. |
| LSP shows no diagnostics | Verify the language‑server binary is installed and reachable (`which gopls`). |

---

## 📚 Quick‑Reference Cheat Sheet  

| Action | Shortcut |
|--------|----------|
| **Save** | `Ctrl + S` |
| **Quit** | `Ctrl + Q` |
| **Open file** | `Ctrl + O` |
| **Command palette** | `Ctrl + E` |
| **Find / Replace** | `Ctrl + F` → `replace …` |
| **Vertical split** | `Ctrl + \` |
| **Horizontal split** | `Ctrl + ]` |
| **New tab** | `Ctrl + T` |
| **Undo / Redo** | `Ctrl + Z` / `Ctrl + Y` |
| **Copy / Cut / Paste** | `Ctrl + C` / `Ctrl + X` / `Ctrl + V` |
| **Duplicate line** | `Ctrl + D` |
| **Move line up/down** | `Alt + ↑ / ↓` |
| **Toggle line numbers** | `Ctrl + R` |
| **Go to line** | `Ctrl + L` |
| **Open file manager (plugin)** | `Alt + B` (after binding) |
| **Fuzzy find (plugin)** | `Alt + F` (after binding) |

---

### 🎉 That’s it!

You now have a **complete, cross‑platform micro workflow**, from installation to daily key‑bindings, to a robust plugin setup tuned for macOS ARM. Feel free to experiment with Lua configuration files (`init.lua`) for even deeper customisation – the editor’s docs are reachable via **Ctrl + G → *Help → *Configuration***. Happy editing!  