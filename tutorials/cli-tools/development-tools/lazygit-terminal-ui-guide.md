# Lazygit Terminal UI Guide

`lazygit` is a simple, high-performance terminal UI for `git` commands, written in Go with `gocui`. It provides an intuitive, keyboard-driven panel layout for staging hunk-by-hunk edits, resolving merge conflicts, performing interactive rebases, managing branches, and viewing commit logs without needing complex Git CLI syntax.

---

## 📚 Table of Contents

1. [Overview & Interface Layout](#overview-interface-layout)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Keyboard Navigation & Panels](#keyboard-navigation-panels)
4. [Interactive Staging & Hunk Splitting](#interactive-staging-hunk-splitting)
5. [Branching, Stashing & Merging](#branching-stashing-merging)
6. [Interactive Rebasing & Commit Squashing](#interactive-rebasing-commit-squashing)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Interface Layout

`lazygit` divides your terminal into 5 interactive panels:

1. **Files Panel**: Staged / unstaged files and working tree status.
2. **Branches Panel**: Local branches, remotes, and tags.
3. **Commits Panel**: Commit history log and reflog.
4. **Stash Panel**: Stashed code changes.
5. **Main View Panel**: Live diffs, code previews, and merge conflict resolution editor.

---

## ⚙️ Installation via Homebrew

```bash
# Install lazygit on macOS
brew install lazygit

# Verify installation
lazygit --version
```

---

## 🎹 Keyboard Navigation & Panels

Launch `lazygit` inside any git repository:

```bash
lazygit
```

### Navigation Keys

| Shortcut | Action |
| --- | --- |
| `1`..`5` | Jump directly to Panel 1..5 |
| `h` / `l` or `←` / `→` | Switch active panel |
| `j` / `k` or `↑` / `↓` | Navigate items inside active panel |
| `Space` | Toggle Stage / Unstage selected file or hunk |
| `a` | Stage ALL files |
| `c` | Open Commit Message dialog |
| `P` | Push changes to remote (`git push`) |
| `p` | Pull changes from remote (`git pull`) |
| `?` | Show interactive keybindings menu |
| `q` | Quit Lazygit |

---

## ✂️ Interactive Staging & Hunk Splitting

Instead of staging entire files, `lazygit` allows you to select specific lines or hunks within a file:

1. Highlight a file in the **Files Panel** (Panel 1).
2. Press `Enter` to focus the **Main Diff Panel**.
3. Use `j`/`k` to highlight specific lines or code blocks.
4. Press `Space` to stage only the selected lines!

---

## 🌿 Branching, Stashing & Merging

- **Create New Branch**: Focus **Branches Panel** (Panel 2) $\rightarrow$ Press `n` $\rightarrow$ Type branch name.
- **Checkout Branch**: Focus branch $\rightarrow$ Press `Space`.
- **Stash Changes**: Focus **Files Panel** $\rightarrow$ Press `s` $\rightarrow$ Enter stash message.
- **Merge Branch**: Highlight target branch in Panel 2 $\rightarrow$ Press `m`.

---

## 🔀 Interactive Rebasing & Commit Squashing

Simplify complex `git rebase -i` operations visually:

1. Focus the **Commits Panel** (Panel 3).
2. Highlight a historical commit and press `e` to edit, or `s` to squash into the preceding commit.
3. Press `r` to reword a commit message.
4. Press `d` to drop/delete a commit entirely.

---

## 📋 Cheat Sheet Summary

| Task | Shortcut |
| --- | --- |
| Stage / Unstage File | `Space` |
| Stage All Files | `a` |
| Commit Changes | `c` |
| Push / Pull | `P` / `p` |
| Create Branch | `n` (in Branches panel) |
| Checkout Branch | `Space` (in Branches panel) |
| Squash Commit | `s` (in Commits panel) |
| Edit Commit Msg | `r` (in Commits panel) |
| Show Help Menu | `?` |
