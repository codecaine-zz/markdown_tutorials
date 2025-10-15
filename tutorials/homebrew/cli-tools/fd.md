**Tutorial – Installing `fd` with Homebrew and using it like a modern `find`**

---

## 1️⃣  What is **fd**?

`fd` (also called **fd‑find**) is a fast, user‑friendly alternative to the classic `find` command.  
*It is written in Rust, respects `.gitignore` by default, and has a concise syntax*【6†L58-L66】.

---

## 2️⃣  Prerequisites – Homebrew must be on your system

* macOS ≥ 10.13 or any Linux distribution supported by Homebrew.  
* If you don’t have Homebrew yet, run:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

(You only need to do this once.)

---

## 3️⃣  Installing **fd** with Homebrew

```bash
brew update                # make sure the formula list is current
brew install fd           # <‑‑ the actual install command
```

The article shows the exact command you need【6†L41-L45】.  
After the installation finishes you can verify it:

```bash
fd --version               # → fd 10.2.0 (current stable version)【5†L27-L30】
which fd                    # → /opt/homebrew/bin/fd (or /usr/local/bin/fd)
man fd                      # open the manual page
```

If you want the very latest code instead of the released bottle, use the *HEAD* version:

```bash
brew install fd --HEAD
```

---

## 4️⃣  Basic `fd` usage

| Goal | `fd` command | Equivalent classic `find` |
|------|--------------|---------------------------|
| Find a file named **myfile.txt** anywhere under the current directory | `fd myfile.txt` | `find . -iname 'myfile.txt'` |
| Search inside a specific directory | `fd myfile.txt /tmp/project` | `find /tmp/project -iname 'myfile.txt'` |
| List only directories whose name contains **src** | `fd -t d src` | `find . -type d -iname '*src*'` |

`fd` automatically walks sub‑directories, respects `.gitignore`, and is case‑insensitive unless you ask for case‑sensitivity (`-s`).

---

## 5️⃣  Frequently‑used flags (with examples)

> The flag list is taken from the article “Pro Terminal Commands: Use fd on macOS to Find Files”【6†L112-L130】.

| Flag | What it does | Example |
|------|--------------|---------|
| `-e EXT` | Search only files with the given **extension** (no leading dot) | `fd -e md` – finds all Markdown files |
| `-E PATTERN` | Exclude paths that match *PATTERN* | `fd -E node_modules` |
| `--changed-newer-than DATE|DURATION` | Keep results newer than a date or a relative duration (e.g. `10h`, `1d`) | `fd --changed-newer-than 2d` |
| `--changed-older-than DATE|DURATION` | Keep results older than the given time | `fd --changed-older-than "2024-01-01"` |
| `-t TYPE` | Limit by file **type** (`f` file, `d` directory, `x` executable, `l` symlink, `e` empty) | `fd -t d src` |
| `-p` | Search the **full pathname** (not just the basename) | `fd -p 'src/.*\.rs$'` |
| `-s` | Force **case‑sensitive** matching | `fd -s Makefile` |
| `-H` | Include **hidden** files (`.` prefixed) | `fd -H .env` |
| `-L` | Follow **symlinks** when recursing | `fd -L .git` |
| `-x CMD` | **Execute** a command on each match (similar to `find -exec`) | `fd -e zip -x unzip` |

### 5.1 Executing commands with placeholders

`fd` provides several placeholders that are substituted with the matched path:

| Placeholder | Expansion |
|-------------|-----------|
| `{}` | Full relative path (`dir/file.txt`) |
| `{/}` | Basename only (`file.txt`) |
| `{//}` | Parent directory (`dir`) |
| `{.}` | Path without extension (`dir/file`) |
| `{/.}` | Basename without extension (`file`) |

**Example – Convert every `.flac` to `.opus` with `ffmpeg`:**

```bash
fd -e flac -x ffmpeg -i {} -c:a libopus {.}.opus
```

The `{}` is replaced by the full `.flac` filename, and `{.}` becomes the same name without the extension【6†L98-L104】.

---

## 6️⃣  Combining **fd** with other Unix tools

Because `fd` prints one result per line, it works nicely with `xargs`, `parallel`, or in shell pipelines.

```bash
# Delete all *.tmp files older than 7 days
fd -e tmp --changed-older-than 7d -X rm -f

# Open every *.pdf in the current tree with Preview
fd -e pdf -x open -a Preview {}

# Count lines of all *.go files
fd -e go -0 | xargs -0 wc -l
```

(`-0` makes `fd` output NUL‑terminated strings, safe for filenames with spaces.)

---

## 7️⃣  Managing the **fd** package with Homebrew

| Task | Homebrew command | What it does |
|------|------------------|--------------|
| Show detailed info (version, bottle, dependencies) | `brew info fd` | Gives the current stable version (10.2.0)【5†L27-L30】 |
| Upgrade to the latest released version | `brew upgrade fd` | Pulls the newest bottle (or builds from source) |
| Reinstall (useful if the binary got corrupted) | `brew reinstall fd` | Removes and installs again |
| Uninstall | `brew uninstall fd` | Removes the binary and its cellar |
| List all installed formulae (including fd) | `brew list` | Shows fd among other packages |
| Clean old versions & caches | `brew cleanup` | Frees disk space after upgrades |
| Diagnose problems | `brew doctor` | Checks for environment issues that could affect fd |

> **Note:** `fd` conflicts with the older `fdclone` formula, so Homebrew will refuse to install both at the same time【5†L39-L41】.

---

## 8️⃣  Common pitfalls & how to fix them

| Symptom | Cause | Fix |
|---------|-------|-----|
| `fd: command not found` after install | Homebrew’s `bin` directory not in `$PATH` | Add `export PATH="/opt/homebrew/bin:$PATH"` (Apple Silicon) or `export PATH="/usr/local/bin:$PATH"` (Intel) to `~/.zshrc`/`~/.bashrc`. |
| `fd` returns *no results* when you expect matches | By default it ignores files listed in `.gitignore` | Use `fd -H` (show hidden) or `fd --no-ignore` to bypass the ignore rules. |
| Conflict with `fdclone` | Another formula installed the same binary name | `brew uninstall fdclone` then reinstall `fd`. |
| Need a newer version than the bottle provides | Homebrew’s bottle is older than upstream | Install the HEAD version: `brew install fd --HEAD`. |

If you run into strange behavior, run `brew doctor` and follow its suggestions.

---

## 9️⃣  Recap – One‑liner to get you started

```bash
# Install (or upgrade) fd, then find all *.txt files modified within the last day
brew install fd && fd -e txt --changed-newer-than 1d
```

You now have a powerful, speedy replacement for `find` at your fingertips, installed and fully managed by Homebrew. Happy searching!