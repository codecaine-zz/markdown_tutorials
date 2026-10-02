# Git Advanced Plumbing, Disaster Recovery & Interactive Rebase Guide

Git is fundamentally a content-addressable directed acyclic graph (DAG) storage engine with a Version Control System (VCS) interface layered on top. Understanding Git's internal objects, plumbing commands, and recovery mechanisms transforms cryptic merge disasters into easily resolvable graph manipulations.

---

## 📚 Table of Contents

1. [Git Internals: The Four Core Objects](#1-git-internals-the-four-core-objects)
2. [Disaster Recovery with `git reflog`](#2-disaster-recovery-with-git-reflog)
3. [Mastering Interactive Rebase (`git rebase -i`)](#3-mastering-interactive-rebase-git-rebase--i)
4. [Automated Bug Hunting with `git bisect`](#4-automated-bug-hunting-with-git-bisect)
5. [Patch Staging & Surgical Commits (`git add -p`)](#5-patch-staging--surgical-commits-git-add--p)
6. [Automating Conflict Resolution with `git rerere`](#6-automating-conflict-resolution-with-git-rerere)
7. [Plumbing Commands: Inspecting the Raw Object Database](#7-plumbing-commands-inspecting-the-raw-object-database)
8. [Emergency Recovery Recipes](#8-emergency-recovery-recipes)

---

## 1. Git Internals: The Four Core Objects

All data inside `.git/objects/` is compressed and stored as one of four immutable object types:

1. **Blob**: Stores pure file content (bytes), without filename, timestamps, or directory path.
2. **Tree**: Represents a directory. Maps blob hashes to filenames and file modes (permissions).
3. **Commit**: Contains a pointer to the top-level tree, author/committer metadata, commit message, and parent commit hash(es).
4. **Annotated Tag**: A permanent pointer to a specific commit containing a signature, tagger identity, and message.

```text
Commit (Hash: a1b2c3d)
 └── Parent: (Hash: 9f8e7d6)
 └── Author: Alice
 └── Tree (Hash: e4f5a6b)
      ├── Blob (Hash: 789abc...) -> "src/main.rs"
      └── Tree (Hash: 123def...) -> "assets/"
```

---

## 2. Disaster Recovery with `git reflog`

The **Reference Log (`reflog`)** records every single modification made to `HEAD` in your local repository. Even if you accidentally run `git reset --hard` or delete a branch with unmerged work, the commits still exist in the reflog for at least 30–90 days.

### Scenario: Accidental `git reset --hard`

```bash
# View the linear history of local HEAD movements
git reflog
```

Example output:

```text
7a8b9c0 HEAD@{0}: reset: moving to HEAD~3
1d2e3f4 HEAD@{1}: commit: Add critical database migration
5g6h7i8 HEAD@{2}: commit: Fix authentication bug
```

### Restoring Lost Commits

```bash
# Option A: Reset HEAD back to where it was prior to the botched reset
git reset --hard HEAD@{1}

# Option B: Branch off the lost commit without touching current HEAD
git branch recovered-work 1d2e3f4
```

---

## 3. Mastering Interactive Rebase (`git rebase -i`)

Interactive rebasing lets you rewrite, reorder, combine, and clean up your commit history before submitting a pull request.

```bash
# Rebase the last 5 commits interactively
git rebase -i HEAD~5
```

An editor opens showing the list of commits with instructions:

```text
pick 1a2b3c4 Feat: Add initial API endpoint
pick 2b3c4d5 Fix: Typo in readme
pick 3c4d5e6 Fix: Fix lint error in tests
pick 4d5e6f7 Feat: Add user authentication
pick 5e6f7g8 Chore: Formatting

# Commands:
# p, pick <commit> = use commit
# r, reword <commit> = use commit, but edit the commit message
# e, edit <commit> = use commit, but stop for amending
# s, squash <commit> = use commit, but meld into previous commit
# f, fixup <commit> = like "squash", but discard this commit's log message
# d, drop <commit> = remove commit
# x, exec <command> = run command (the following shell command) using shell
```

### Squashing Typo Fixes into Clean Commits

Edit the file to:

```text
pick 1a2b3c4 Feat: Add initial API endpoint
fixup 2b3c4d5 Fix: Typo in readme
fixup 3c4d5e6 Fix: Fix lint error in tests
pick 4d5e6f7 Feat: Add user authentication
drop 5e6f7g8 Chore: Formatting
```

Save and exit. Git automatically squashes the two fixes into the first commit and deletes the formatting commit!

---

## 4. Automated Bug Hunting with `git bisect`

`git bisect` uses a binary search algorithm through your commit history to pinpoint the exact commit that introduced a bug or regression.

### Manual Bisect

```bash
# Start bisect session
git bisect start

# Tell Git that the current commit is broken
git bisect bad

# Tell Git a known good commit from the past
git bisect good v1.4.0

# Git checks out a midpoint commit. Test your app, then report:
git bisect good   # or: git bisect bad

# Repeat until Git outputs:
# "d9f8e7a is the first bad commit"

# End session and return to starting branch
git bisect reset
```

### Fully Automated Bisect with a Test Script

Let Git run your unit test suite autonomously across hundreds of commits:

```bash
git bisect start HEAD v1.0.0
git bisect run cargo test test_user_authentication
```

Git runs the binary search automatically in seconds and halts on the broken commit.

---

## 5. Patch Staging & Surgical Commits (`git add -p`)

Never commit unrelated changes in a monolithic commit. Use interactive patch mode (`-p`) to stage individual hunks or lines of code:

```bash
git add -p src/server.go
```

Git prompts per hunk:

```text
Stage this hunk [y,n,q,a,d,s,e,?]?
```

- `y`: Stage this hunk
- `n`: Skip this hunk
- `s`: Split the hunk into smaller chunks
- `e`: Manually edit the hunk in your editor
- `q`: Quit interactive mode

---

## 6. Automating Conflict Resolution with `git rerere`

`rerere` stands for **Reuse Recorded Resolution**. When enabled, Git remembers how you resolved a merge conflict. If the same conflict reoccurs later (e.g., during long-lived feature branch rebasing), Git resolves it automatically.

```bash
# Enable rerere globally
git config --global rerere.enabled true

# Optional: Allow rerere to automatically stage resolved files
git config --global rerere.autoupdate true
```

---

## 7. Plumbing Commands: Inspecting the Raw Object Database

Plumbing commands are the low-level building blocks behind high-level porcelain commands (`commit`, `checkout`):

```bash
# Inspect the type of an object hash
git cat-file -t 7a8b9c0

# Print raw content of any object (blob, tree, or commit)
git cat-file -p HEAD

# Print the size in bytes of an object
git cat-file -s HEAD

# Verify repository database integrity and find dangling (orphaned) objects
git fsck --lost-found
```

---

## 8. Emergency Recovery Recipes

### Recovering a Accidentally Deleted Branch

```bash
# 1. Find the commit hash when the branch was deleted
git reflog | grep "checkout: moving from my-feature-branch"

# 2. Recreate branch from the hash
git branch my-feature-branch 3a4b5c6
```

### Undo Last Commit but Keep All Changes in Working Directory

```bash
git reset --soft HEAD~1
```

### Discard All Local Untracked and Modified Files Completely

```bash
git reset --hard HEAD
git clean -fd
```
