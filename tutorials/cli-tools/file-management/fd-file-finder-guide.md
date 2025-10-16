# 🗂️ **Complete Guide – Installing `fd` on an Apple‑Silicon (ARM) Mac with Homebrew**  
*(styled like the official Docker guide you referenced)*  

> **What’s fd?**  
> `fd` is a simple, fast, and user‑friendly alternative to GNU `find`. It defaults to a smart‑case regular‑expression search, skips hidden files and `.gitignore` patterns, and prints colour‑coded results.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1‑prereqs)  
2. [Install `fd` via Homebrew](#2‑install)  
3. [Verify the installation & locate the binaries](#3‑verify)  
4. [Where Homebrew puts `fd` on Apple Silicon](#4‑locations)  
5. [Shell‑completion setup (bash, zsh, fish)](#5‑completion)  
6. [Basic usage – copy‑paste one‑liners](#6‑cheatsheet)  
7. [Advanced options you’ll reach for often](#7‑advanced)  
8. [Keeping `fd` up‑to‑date](#8‑upgrade)  
9. [Uninstall / clean‑up](#9‑uninstall)  
10. [Common pitfalls & troubleshooting](#10‑troubleshoot)  

---  

<a name="1‑prereqs"></a>
## 1️⃣  Prerequisites – Homebrew  

```bash
# Verify Homebrew version (needs v4+)
brew --version
```

If the command is missing, install Homebrew **once** (default prefix **/opt/homebrew** for Apple Silicon) 【14†L190-L192】:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

> Homebrew’s default prefix on M1/M2 Macs is `/opt/homebrew`; using the default guarantees you get the pre‑compiled binary “bottles” for `fd` 【14†L190-L192】.  

---  

<a name="2‑install"></a>
## 2️⃣  Install `fd` via Homebrew  

| What you get | Command | Result |
|--------------|---------|--------|
| **fd** – the executable only (no GUI) | `brew install fd` | Installs the latest stable version 10.3.0 【18†L4-L9】【18†L34-L35】 |
| **Build from source** (fallback) | `brew install --build-from-source fd` | Compiles locally – only needed on very old macOS releases. |

Homebrew supplies a **pre‑built bottle for Apple Silicon** (macOS Sequoia, Sonoma, Ventura) 【18†L20-L24】, so the install is instant.  

---  

<a name="3‑verify"></a>
## 3️⃣  Verify the installation & locate the binary  

```bash
fd --version          # → 10.3.0
which fd              # → /opt/homebrew/bin/fd
fd -h                 # concise help
fd --help             # full help page
```

If you see the version and the path under **/opt/homebrew**, you’re ready to roll.  

---  

<a name="4‑locations"></a>
## 4️⃣  Where Homebrew puts `fd` on Apple Silicon  

| Item | Path (Apple Silicon) | How to retrieve |
|------|----------------------|-----------------|
| **Executable** | `/opt/homebrew/opt/fd/bin/fd` (symlinked to `/opt/homebrew/bin/fd`) | `$(brew --prefix fd)/bin/fd` |
| **Man page** | `/opt/homebrew/share/man/man1/fd.1` | `man fd` |
| **Bash completions** | `$(brew --prefix)/etc/bash_completion.d/fd` | `brew completions` |
| **Zsh completions** | `$(brew --prefix)/share/zsh/site-functions/_fd` | automatically loaded by `zsh` |
| **Fish completions** | `$(brew --prefix)/share/fish/vendor_completions.d/fd.fish` | auto‑loaded by `fish` |
| **Configuration files** | None required; optional `.fdignore` / `.gitignore` are read from the searched directory. | — |

*All of the above live under the Homebrew prefix `/opt/homebrew` on Apple Silicon.*  

---  

<a name="5‑completion"></a>
## 5️⃣  Shell‑completion setup  

Homebrew installs the completion scripts automatically. You only need to ensure the completion directories are on your `$FPATH`/`$PATH` (they are by default).

```bash
# Bash (if you use bash-completion@2)
[[ -r "$(brew --prefix)/etc/profile.d/bash_completion.sh" ]] && . "$(brew --prefix)/etc/profile.d/bash_completion.sh"

# Zsh (most macOS zsh setups already source completions)
type _fd   # should show a function

# Fish – nothing to do, Fish reads completions from the directory automatically
```

If completions don’t appear, reload the shell or source the file directly:

```bash
source "$(brew --prefix)/etc/bash_completion.d/fd"
```

---  

<a name="6‑cheatsheet"></a>
## 6️⃣  **Basic usage – copy‑paste one‑liners**  

| Goal | Command | Explanation |
|------|---------|-------------|
| Find any file containing **foo** in the current tree | `fd foo` | Regex search (case‑insensitive by default) |
| Search only *.txt* files | `fd -e txt` | `-e/--extension` limits to the given extension 【19†L80-L87】 |
| Match a literal string (no regex) | `fd -F "my‑file.txt"` | `-F/--fixed-strings` treats the pattern as plain text |
| Search with a glob pattern | `fd -g "*.md"` | `-g/--glob` uses shell‑style globs 【19†L98-L102】 |
| Include hidden files | `fd -H secret` | `-H/--hidden` disables hidden‑file exclusion 【19†L110-L114】 |
| Ignore .gitignore (search everything) | `fd -u "TODO"` | `-u/--unrestricted` = `-H -I` 【19†L132-L136】 |
| Search a specific directory | `fd pattern /path/to/dir` | Provide one or more start paths |
| Show only directories | `fd -t d src/` | `-t/--type d` (d = directory) |
| Execute a command for each match (parallel) | `fd -x rm {} \;` | `-x/--exec` runs a command per result 【19†L152-L154】 |
| Print full path (instead of relative) | `fd -p pattern` | `-p/--full-path` matches against the whole path 【19†L136-L142】 |
| Show a tree view of depth 2 | `fd -d 2 . | tree -C` | Combine with `tree` for a visual hierarchy |
| Colour‑disable for scripts | `fd --color=never pattern` | Force plain output (useful when piping) |
| Show help | `fd -h` | Concise usage summary |
| Show exhaustive help | `fd --help` | Full manual‑page‑style help |

All commands behave identically on Intel Macs; only the binary location changes.  

---  

<a name="7‑advanced"></a>
## 7️⃣  **Advanced options you’ll reach for often**  

| Option | Example | What it does |
|--------|---------|--------------|
| `-I / --no-ignore` | `fd -I Cargo.toml` | Search **ignoring** all `.gitignore` / `.fdignore` rules. |
| `-p / --full-path` | `fd -p -g '**/test_*.rs'` | Match against the **entire path** (great with `**`). |
| `-x / --exec` | `fd -x echo "found: {}"` | Run a command **for each match** (parallel by default). |
| `-X / --exec-batch` | `fd -X tar -czf hits.tar.gz {}` | Run a command **once** with all matches as arguments. |
| `-t / --type` | `fd -t f -e rs src/` | Limit to *file* (`f`), *directory* (`d`), *symlink* (`l`), *executable* (`x`). |
| `--max-depth N` | `fd -d 2 .` | Stop recursing after **N** directory levels. |
| `--exclude PATTERN` | `fd --exclude node_modules` | Skip any path matching the pattern. |
| `--color=always|never|auto` | `fd --color=never` | Control colourised output (useful in scripts). |
| `--changed` | `fd --changed` | Show only files changed since the last git commit (uses git). |
| `--changed-within 2d` | `fd --changed-within 2d` | Files changed within the last *2 days*. |
| `--search-path PATH` | `fd -g "*.go" --search-path $GOPATH/src` | Override the search root without changing the working directory. |
| `--exec sh -c 'mv {} /tmp/old/'` | `fd -e log -x sh -c 'mv {} /tmp/old/'` | Complex shell pipelines for each result. |

Run `fd --help` to see the entire option set.  

---  

<a name="8‑upgrade"></a>
## 8️⃣  Keep `fd` up‑to‑date  

```bash
brew update               # refresh formula list
brew upgrade fd          # upgrade just fd
# or upgrade everything
brew upgrade
```

Homebrew will replace the old binary in `/opt/homebrew/opt/fd` with the new one, preserving completions.  

---  

<a name="9‑uninstall"></a>
## 9️⃣  Uninstall / clean‑up  

```bash
# Remove the formula
brew uninstall fd

# (Optional) purge leftover caches & logs
brew cleanup fd
```

If you ever need to wipe *all* Homebrew data (including other formulae) you can run the Homebrew uninstall script, but for `fd` alone the two commands above are sufficient.  

---  

<a name="10‑troubleshoot"></a>
## ⚠️ 10️⃣  Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `command not found: fd` after install | `$PATH` does not include `/opt/homebrew/bin` (rare on fresh shells) | Add `export PATH="/opt/homebrew/bin:$PATH"` to `~/.zshrc` / `~/.bash_profile`. |
| `fd: command not found` in a script run with `sudo` | `sudo` clears the environment and drops Homebrew’s prefix | Run `sudo -E` to preserve env, or avoid `sudo` (fd never needs root). |
| Completion doesn’t work in Bash | Bash‑completion not loaded (Homebrew’s `bash_completion@2` missing) | `brew install bash-completion@2` and source `/opt/homebrew/etc/bash_completion.d/fd`. |
| `fd` returns **no results** when files exist | Hidden files or ignored patterns may be excluded | Add `-H` to include hidden files, or `-u` to ignore `.gitignore`. |
| “`fd` is a symbolic link to a non‑existent file” after macOS upgrade | Homebrew prefix moved (rare) | Reinstall: `brew reinstall fd`. |
| Build fails on very old macOS | No pre‑built bottle for that version | Use `brew install --build-from-source fd`. |

For deeper diagnostics, run `brew doctor` and check the Homebrew logs (`$HOME/Library/Logs/Homebrew/fd`).  

---  

## 📚  Reference list (official sources)

| Topic | Link |
|-------|------|
| Homebrew formula page for `fd` | <https://formulae.brew.sh/formula/fd> 【18†L4-L9】 |
| Apple‑Silicon bottle support (✓) | <https://formulae.brew.sh/formula/fd> 【18†L20-L24】 |
| Homebrew default prefix on Apple Silicon | <https://docs.brew.sh/FAQ#why-does-homebrew-say-sudo-is-bad> 【14†L190-L192】 |
| `fd` README – usage & options | <https://github.com/sharkdp/fd/blob/master/README.md> 【19†L80-L102】【19†L110-L118】【19†L132-L138】 |
| Shell‑completion docs (Homebrew) | <https://docs.brew.sh/Shell-Completion> |
| Updating / cleaning Homebrew | <https://docs.brew.sh/FAQ#upgrading-and-cleaning> |
| Uninstalling a Homebrew formula | <https://docs.brew.sh/Manpage#uninstall> |

---  

### 🎉 You’re all set!  

Run `fd --help` to see the full option list, then start hunting files faster than ever on your M‑series Mac. 🚀  