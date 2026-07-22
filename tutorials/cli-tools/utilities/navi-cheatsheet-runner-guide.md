# navi: Interactive Command-Line Cheatsheet Runner

## Table of Contents

1. [What is `navi`?](#1-what-is-navi)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Usage & Interactive Search](#4-basic-usage--interactive-search)
5. [Writing Custom Cheatsheets (`.cheat` files)](#5-writing-custom-cheatsheets-cheat-files)
6. [Importing Community Cheatsheet Repositories](#6-importing-community-cheatsheet-repositories)
7. [Shell Widget Integration (`Ctrl+G`)](#7-shell-widget-integration-ctrlg)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `navi`?

`navi` is an interactive CLI cheatsheet tool written in Rust. It lets you browse through command-line snippets, search for operations using fuzzy matching (`fzf`), fill in argument prompts interactively, and execute complex shell commands without having to remember long terminal flags.

#### Key Benefits
* **Interactive Argument Filling:** Prompts you step-by-step to fill in variable parameters (like docker container IDs, git branch names, or IP addresses).
* **Fuzzy Filtering:** Instantly search through hundreds of cheat codes by typing keywords.
* **Custom Cheatsheet Libraries:** Store your personal workflow scripts and team snippets in simple markdown `.cheat` files.

---

### 2. Prerequisites

Verify Homebrew and `fzf` on your Apple Silicon Mac:

```bash
brew --version
```

Recommended dependency for optimal fuzzy searching:

```bash
brew install fzf
```

---

### 3. Installation on ARM macOS

Install `navi` via Homebrew:

```bash
brew install navi
```

Verify binary installation:

```bash
which navi
navi --version
```

**Expected Output:**
```text
/opt/homebrew/bin/navi
navi 2.x.x
```

---

### 4. Basic Usage & Interactive Search

Launch `navi` in interactive search mode:

```bash
navi
```

#### Interactive Search UI
`navi` opens an interactive fuzzy finder window listing pre-loaded or downloaded cheatsheets:

```text
┌─ Cheatsheets ────────────────────────────────────────────────────────────────┐
│ docker: Stop running container                                               │
│ git: Rebase current branch onto main                                         │
│ ffmpeg: Compress MP4 video file                                              │
└──────────────────────────────────────────────────────────────────────────────┘
> docker
```

1. Type a keyword (e.g., `git rebase`).
2. Highlight the command and press `Enter`.
3. If the command has variable placeholders (e.g., `<branch>`), `navi` will prompt you to type or select the value interactively before running the command.

---

### 5. Writing Custom Cheatsheets (`.cheat` files)

You can write your own cheatsheets using plain syntax.

Create your custom cheatsheet directory:

```bash
mkdir -p ~/.config/navi/cheats
touch ~/.config/navi/cheats/custom.cheat
```

#### Syntax of a `.cheat` file:
* Lines starting with `%` define tags.
* Lines starting with `#` define descriptions.
* Lines starting with `$` define dynamic variable choices using shell commands.

Add the following example to `~/.config/navi/cheats/custom.cheat`:

```bash
% docker, container, dev

# Stop a running container
docker stop <container_id>

# Remove all stopped containers
docker container prune -f

$ container_id: docker ps --format "{{.ID}} \t {{.Names}}" --- --headers 1 --column 1
```

Now, when you run `navi` and pick "Stop a running container", `navi` automatically runs `docker ps` in the background and presents a dropdown list of running container IDs for you to select!

---

### 6. Importing Community Cheatsheet Repositories

`navi` allows importing curated community cheatsheets:

```bash
navi repo browse
```

Or add specific GitHub cheat repositories directly:

```bash
navi repo add denisidoro/cheats
```

---

### 7. Shell Widget Integration (`Ctrl+G`)

Integrate `navi` directly into your Zsh prompt on macOS so pressing `Ctrl+G` opens `navi` and inserts the chosen command directly onto your shell prompt line.

Add to `~/.zshrc`:

```bash
eval "$(navi widget zsh)"
```

Apply the changes:

```bash
source ~/.zshrc
```

Now, while typing a command in Zsh, press `Ctrl+G` to select a snippet from `navi`.

---

### 8. Uninstallation

1. Remove `eval "$(navi widget zsh)"` from `~/.zshrc`.
2. Uninstall `navi`:
   ```bash
   brew uninstall navi
   rm -rf ~/.config/navi
   ```
