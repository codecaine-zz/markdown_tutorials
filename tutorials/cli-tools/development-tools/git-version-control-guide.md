# 📘 Official‑style **Git** Guide for macOS (Homebrew — 2025 edition)

> **What’s new?**  
> The **Git cheat‑sheet** now contains **every core command** (init, clone, add, commit, push, pull, branch, merge, rebase, stash, tag, worktree, etc.) as ready‑to‑copy‑paste one‑liners, each linked to the official Git reference page.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1-prerequisites-homebrew)  
2. [Install Git (brew)](#2-install-git-brew)  
3. [Where Homebrew puts Git (paths & binaries)](#3-where-homebrew-puts-git-paths-binaries)  
4. [Basic global configuration (`~/.gitconfig`)](#4-basic-global-configuration-gitconfig)  
5. [Create a repository & basic workflow (`git init` / `git clone`)](#5-create-a-repository-basic-workflow)  
6. **[Git cheat-sheet – init / add / commit / push / pull / branch / merge / rebase / stash / tag / worktree](#6-git-cheat-sheet-one-liners-for-everyday-use)** ← **copy-paste ready**  
7. [Working with remotes (fetch / pull / push / remote)](#7-working-with-remotes)  
8. [History inspection (`log`, `show`, `diff`, `blame`)](#8-history-inspection)  
9. [Branch & tag management (list, delete, rename)](#9-branch-tag-management-list-delete-rename)  
10. [Advanced tooling – `git rebase -i`, `git bisect`, `git submodule`](#10-advanced-tooling)  
11. [Security – signing commits & GPG, credential helpers](#11-security)  
12. [Performance & housekeeping (`gc`, `fsck`, `reflog prune`)](#12-performance-housekeeping)  
13. [Common pitfalls & troubleshooting](#13-common-pitfalls-troubleshooting)  
14. [Uninstall / clean-up](#14-uninstall-clean-up)  

---  

<a name="1‑prereqs"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version            # should print 4.x or later
# If missing → install it
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Homebrew is the **officially supported** way to install Git on macOS 【1†L5-L9】.  

---  

<a name="2‑install"></a>
## 2️⃣ Install Git (brew)  

```bash
brew install git
```

The formula page shows the exact command and the current stable version (2.51.0) 【16†L4-L13】【16†L31-L36】.  

*Formula page*: <https://formulae.brew.sh/formula/git>  

---  

<a name="3‑locations"></a>
## 3️⃣ Where Homebrew puts Git (paths & binaries)  

| Item | Path (Apple silicon) | Path (Intel) | How to retrieve |
|------|----------------------|--------------|-----------------|
| Binaries (`git`, `gitk`, `git-gui`) | `/opt/homebrew/opt/git/bin/` | `/usr/local/opt/git/bin/` | `$(brew --prefix git)/bin` |
| Man pages | `$HOMEBREW_PREFIX/share/man/man1/git.1` | same | |
| Docs (HTML) | `$HOMEBREW_PREFIX/share/doc/git/` | same | |
| Global config file | `$HOME/.gitconfig` (created by `git config --global …`) | same | |
| System‑wide config (brew) | `$(brew --prefix)/etc/gitconfig` | same | |

Homebrew’s `git` formula does **not** install the GUI tools (`gitk`, `git-gui`) by default; they are available in the separate `git-gui` formula if you need them.  

---  

<a name="4‑global‑config"></a>
## 4️⃣ Basic global configuration (`~/.gitconfig`)  

```bash
# Set your identity (replace with your name/email)
git config --global user.name "Jane Developer"
git config --global user.email "jane@example.com"

# Preferred editor (e.g., VS Code)
git config --global core.editor "code --wait"

# Enable coloured output
git config --global color.ui auto
```

All of the above write to `~/.gitconfig`. You can view the file with `git config --list --show-origin`.  

*Reference*: <https://git-scm.com/docs/git-config>  

---  

<a name="5‑workflow"></a>
## 5️⃣ Create a repository & basic workflow  

```bash
# Initialise a brand‑new repository in the current directory
git init                              # docs: https://git-scm.com/docs/git-init

# Clone an existing remote
git clone https://github.com/user/project.git   # docs: https://git-scm.com/docs/git-clone
```

The repository now contains a hidden `.git/` directory that stores all objects and refs.  

---  

<a name="6‑cheatsheet"></a>
## 6️⃣ **Git cheat‑sheet – one‑liners for everyday use**  

> Every command is a single line you can copy‑paste into your terminal.  Each entry links to the official Git manual page (see the **Reference** list at the bottom of this guide).  

| Goal | Command | Docs |
|------|---------|------|
| **Add new files / stage changes** | `git add .` | <https://git-scm.com/docs/git-add> |
| **Stage only part of a file** | `git add -p` | same |
| **Commit staged changes** | `git commit -m "Add feature X"` | <https://git-scm.com/docs/git-commit> |
| **Amend last commit (keep message)** | `git commit --amend --no-edit` | <https://git-scm.com/docs/git-commit> |
| **Show status** | `git status -sb` | <https://git-scm.com/docs/git-status> |
| **View diff of unstaged changes** | `git diff` | <https://git-scm.com/docs/git-diff> |
| **Show diff of staged changes** | `git diff --cached` | same |
| **Push to remote `origin` (current branch)** | `git push` | <https://git-scm.com/docs/git-push> |
| **Push a specific branch** | `git push origin feature/awesome` | same |
| **Pull (fetch + merge) the current branch** | `git pull` | <https://git-scm.com/docs/git-pull> |
| **Fetch only** | `git fetch --all` | <https://git-scm.com/docs/git-fetch> |
| **Create a new branch and switch to it** | `git checkout -b feature/xyz` (legacy) **or** `git switch -c feature/xyz` | <https://git-scm.com/docs/git-switch> |
| **List all branches** | `git branch -vv` | <https://git-scm.com/docs/git-branch> |
| **Delete a branch (local)** | `git branch -d feature/old` | same |
| **Delete a branch (remote)** | `git push origin --delete feature/old` | <https://git-scm.com/docs/git-push> |
| **Merge another branch** | `git merge --no-ff develop` | <https://git-scm.com/docs/git-merge> |
| **Rebase current branch onto `main`** | `git rebase main` | <https://git-scm.com/docs/git-rebase> |
| **Interactive rebase (rewrite history)** | `git rebase -i HEAD~3` | same |
| **Stash current work** | `git stash push -m "WIP"` | <https://git-scm.com/docs/git-stash> |
| **List stashes** | `git stash list` | same |
| **Apply most recent stash** | `git stash pop` | same |
| **Create a lightweight tag** | `git tag v1.2.3` | <https://git-scm.com/docs/git-tag> |
| **Create an annotated tag** | `git tag -a v1.2.3 -m "Release 1.2.3"` | same |
| **List tags** | `git tag -l` | same |
| **Delete a tag** | `git tag -d v1.2.3` | same |
| **Create a worktree (extra checkout)** | `git worktree add ../featureX featureX` | <https://git-scm.com/docs/git-worktree> |
| **Show commit log (one‑line)** | `git log --oneline --graph --decorate` | <https://git-scm.com/docs/git-log> |
| **Show details of a commit** | `git show abc1234` | <https://git-scm.com/docs/git-show> |
| **Search commit history** | `git log -S "search‑string"` | <https://git-scm.com/docs/git-log> |
| **Blame a file** | `git blame src/main.c` | <https://git-scm.com/docs/git-blame> |
| **Undo last commit (keep changes unstaged)** | `git reset HEAD~1` | <https://git-scm.com/docs/git-reset> |
| **Discard all local changes** | `git checkout -- .` (or `git restore .` on newer Git) | <https://git-scm.com/docs/git-restore> |
| **Verify repository integrity** | `git fsck --full` | <https://git-scm.com/docs/git-fsck> |
| **Garbage‑collect and repack** | `git gc --aggressive --prune=now` | <https://git-scm.com/docs/git-gc> |

---  

<a name="7‑remotes"></a>
## 7️⃣ Working with remotes  

| Operation | Command | Docs |
|-----------|---------|------|
| **Add a remote** | `git remote add upstream https://github.com/original/project.git` | <https://git-scm.com/docs/git-remote> |
| **Rename a remote** | `git remote rename origin upstream` | same |
| **Remove a remote** | `git remote remove upstream` | same |
| **Show all remotes** | `git remote -v` | same |
| **Fetch a specific remote** | `git fetch upstream` | <https://git-scm.com/docs/git-fetch> |
| **Push to a non‑default remote** | `git push upstream feature/xyz` | <https://git-scm.com/docs/git-push> |
| **Pull with rebase** | `git pull --rebase` | <https://git-scm.com/docs/git-pull> |
| **Set upstream tracking** | `git branch --set-upstream-to=origin/main` | <https://git-scm.com/docs/git-branch> |

---  

<a name="8‑history"></a>
## 8️⃣ History inspection  

| Goal | Command | Docs |
|------|---------|------|
| **One‑line log** | `git log --oneline --decorate` | <https://git-scm.com/docs/git-log> |
| **Graphical log** | `git log --graph --oneline --decorate --all` | same |
| **Show a commit** | `git show 9fceb02` | <https://git-scm.com/docs/git-show> |
| **Diff between two commits** | `git diff abc123..def456` | <https://git-scm.com/docs/git-diff> |
| **File history** | `git log --follow -- src/app.js` | <https://git-scm.com/docs/git-log> |
| **Search for a string across history** | `git log -G"TODO"` | <https://git-scm.com/docs/git-log> |
| **Blame a file (who changed each line)** | `git blame src/main.c` | <https://git-scm.com/docs/git-blame> |

---  

<a name="9‑branch‑tag"></a>
## 9️⃣ Branch & tag management (list, delete, rename)  

```bash
# List local & remote branches
git branch -a

# Rename current branch
git branch -m old-name new-name

# Delete a remote‑tracking branch
git branch -dr origin/old‑feature
```

All relevant commands are documented under **Branching and Merging** in the reference list 【17†L36-L47】.  

---  

<a name="10‑advanced"></a>
## 1️⃣0️⃣ Advanced tooling  

| Tool | Typical usage | Docs |
|------|---------------|------|
| **Interactive rebase** | `git rebase -i HEAD~5` – reorder, squash, edit commits | <https://git-scm.com/docs/git-rebase> |
| **Bisect** (binary search) | `git bisect start && git bisect bad && git bisect good v1.0` | <https://git-scm.com/docs/git-bisect> |
| **Cherry‑pick** | `git cherry-pick abc123` | <https://git-scm.com/docs/git-cherry-pick> |
| **Submodules** | `git submodule add https://github.com/lib/foo.git lib/foo` | <https://git-scm.com/docs/git-submodule> |
| **Patch series** | `git format-patch -3` → create three `.patch` files | <https://git-scm.com/docs/git-format-patch> |
| **Apply a patch** | `git am < 001‑feature.patch` | <https://git-scm.com/docs/git-am> |

---  

<a name="11‑security"></a>
## 1️⃣1️⃣ Security  

| Concern | Remedy | Docs |
|---------|--------|------|
| **Signing commits** | `git config --global user.signingkey <key‑id>` + `git commit -S` | <https://git-scm.com/docs/git-commit#Documentation/git-commit.txt---signoff> |
| **GPG verification** | `git verify-commit <hash>` | <https://git-scm.com/docs/git-verify-commit> |
| **Credential helpers** (store passwords securely) | `git config --global credential.helper osxkeychain` | <https://git-scm.com/docs/git-credential-osxkeychain> |
| **SSH instead of HTTPS** | Add remote via `git@github.com:user/repo.git` | <https://git-scm.com/docs/git-ssh> |
| **Enforce signed tags** | `git config --global tag.gpgSign true` | <https://git-scm.com/docs/git-tag> |

---  

<a name="12‑maintenance"></a>
## 1️⃣2️⃣ Performance & housekeeping  

| Task | Command | When to run | Docs |
|------|---------|-------------|------|
| **Compress & prune unreachable objects** | `git gc --aggressive --prune=now` | After large deletes or history rewrites | <https://git-scm.com/docs/git-gc> |
| **Verify repository integrity** | `git fsck --full` | Periodic health check | <https://git-scm.com/docs/git-fsck> |
| **Clean up stale refs** | `git remote prune origin` | When remote branch deletions accumulate | <https://git-scm.com/docs/git-remote> |
| **Expire reflog entries** | `git reflog expire --expire=90.days ago --all` | To shrink reflog size | <https://git-scm.com/docs/git-reflog> |
| **Trim large history** | `git filter-repo --strip-blobs-bigger-than 10M` (external tool) | Reduce repo size | <https://github.com/newren/git-filter-repo> |

---  

<a name="13‑mistakes"></a>
## ⚠️ 1️⃣3️⃣ Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `fatal: not a git repository (or any of the parent directories): .git` | Running a Git command outside a repo | `cd` into a repo or `git init` |
| `Permission denied (publickey)` on `git push` | SSH key not added to remote (GitHub/GitLab) | Add `ssh-add -K ~/.ssh/id_rsa` and upload the public key |
| **Accidentally committed large file** | Large blob in history | Use `git filter-repo` to purge, then force‑push (if remote allows) |
| **Merge conflicts** | Divergent changes | Resolve manually, then `git add <files>` and `git commit` |
| **Detached HEAD** after checkout of a tag | You’re on a detached commit | Create a branch: `git switch -c my‑branch` |
| **Forgot to set user/email** – commits show “unknown” | Global config missing | Run the two `git config --global` commands from §4 |
| **Push rejected – non‑fast‑forward** | Remote has newer commits | `git pull --rebase` then push |

*General troubleshooting*: <https://git-scm.com/docs/git#_common_problems>  

---  

<a name="14‑uninstall"></a>
## 1️⃣4️⃣ Uninstall / clean‑up  

```bash
# Remove the Homebrew formula
brew uninstall git

# Optionally delete the global config (if you want a clean slate)
rm -f ~/.gitconfig

# Remove any lingering repositories you no longer need
rm -rf /path/to/your/project/.git
```

If you also installed the GUI helpers (`git-gui`, `gitk`) they live in the separate `git-gui` formula; uninstall them similarly.  

*Uninstall docs*: <https://docs.brew.sh/Manpage#uninstall>  

---  

## Reference List (all linked commands)

- **git** (general) – https://git-scm.com/docs/git  
- **git‑config** – https://git-scm.com/docs/git-config  
- **git‑init** – https://git-scm.com/docs/git-init  
- **git‑clone** – https://git-scm.com/docs/git-clone  
- **git‑add** – https://git-scm.com/docs/git-add  
- **git‑status** – https://git-scm.com/docs/git-status  
- **git‑diff** – https://git-scm.com/docs/git-diff  
- **git‑commit** – https://git-scm.com/docs/git-commit  
- **git‑push** – https://git-scm.com/docs/git-push  
- **git‑pull** – https://git-scm.com/docs/git-pull  
- **git‑fetch** – https://git-scm.com/docs/git-fetch  
- **git‑branch** – https://git-scm.com/docs/git-branch  
- **git‑checkout / git‑switch** – https://git-scm.com/docs/git-switch  
- **git‑merge** – https://git-scm.com/docs/git-merge  
- **git‑rebase** – https://git-scm.com/docs/git-rebase  
- **git‑stash** – https://git-scm.com/docs/git-stash  
- **git‑tag** – https://git-scm.com/docs/git-tag  
- **git‑worktree** – https://git-scm.com/docs/git-worktree  
- **git‑log** – https://git-scm.com/docs/git-log  
- **git‑show** – https://git-scm.com/docs/git-show  
- **git‑blame** – https://git-scm.com/docs/git-blame  
- **git‑remote** – https://git-scm.com/docs/git-remote  
- **git‑bisect** – https://git-scm.com/docs/git-bisect  
- **git‑gc** – https://git-scm.com/docs/git-gc  
- **git‑fsck** – https://git-scm.com/docs/git-fsck  

---  

# 🎉 All set!  

You now have a **complete, copy‑paste‑ready reference** for installing, configuring, using, and maintaining Git on macOS via Homebrew, with every command linked to its official documentation. Happy version‑control! 🚀  