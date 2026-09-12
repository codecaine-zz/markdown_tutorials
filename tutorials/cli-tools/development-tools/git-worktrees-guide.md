# Git Worktrees Complete Multi-Branch Workflow Guide

`git worktree` is a built-in, underutilized superpower of Git that allows you to checkout and work on multiple branches simultaneously in separate linked directory paths. With worktrees, you no longer need to `git stash`, abandon uncommitted changes, or wait for re-installations when urgently switching branches.

---

## 📚 Table of Contents

1. [Overview & Why Use Git Worktrees?](#overview-why-use-git-worktrees)
2. [How Worktrees Work Under the Hood](#how-worktrees-work-under-the-hood)
3. [Creating Your First Worktree (`git worktree add`)](#creating-your-first-worktree-git-worktree-add)
4. [Listing & Inspecting Worktrees (`git worktree list`)](#listing-inspecting-worktrees-git-worktree-list)
5. [Switching Between Worktrees Effortlessly](#switching-between-worktrees-effortlessly)
6. [Removing & Cleaning Up Worktrees (`git worktree remove` / `prune`)](#removing-cleaning-up-worktrees)
7. [Recommended Directory Structure (Bare Repo Pattern)](#recommended-directory-structure-bare-repo-pattern)
8. [Real-World Development Scenarios](#real-world-development-scenarios)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)

---

## 🔍 Overview & Why Use Git Worktrees?

When you are deep in a feature branch with modified files and your team requests an urgent hotfix or code review:

- **Traditional Workflow**: You must run `git stash`, switch branches (`git checkout main`), run tests, fix the bug, switch back (`git checkout feature-x`), and run `git stash pop` (often causing merge conflicts or forcing slow dependency rebuilds).
- **Worktree Workflow**: You spawn a new directory for `hotfix` with one command, make your changes, commit, push, and delete the folder—leaving your original feature branch completely untouched.

| Feature | `git checkout` / `stash` | `git worktree` |
| :--- | :--- | :--- |
| **Simultaneous Branches** | 1 branch at a time | Multiple branches at the same time |
| **Uncommitted Changes** | Must stash or commit | Isolated in its own folder |
| **Local Dev Servers** | Must restart on switch | Run multiple branches on different ports |
| **Disk Space** | Clones entire history if duplicated | Shares the same single `.git` history |

---

## ⚙️ How Worktrees Work Under the Hood

All linked worktrees share the exact same underlying Git database (`.git` object store, commit history, remotes, and reflog). No extra disk space is consumed by duplicate Git history.

```text
/my-project/ (main worktree)
├── .git/ (Single source of truth)
│   └── worktrees/
│       ├── hotfix-101/
│       └── review-pr-42/
├── src/
└── ...

/my-project-hotfix/ (linked worktree)
├── .git (Pointer file pointing back to main .git)
└── src/ (Working tree for hotfix branch)
```

---

## 🚀 Creating Your First Worktree (`git worktree add`)

### 1. Create a Worktree for an Existing Branch

```bash
# Add worktree in a sibling folder for existing branch 'staging'
git worktree add ../my-project-staging staging
```

### 2. Create a Worktree with a Brand New Branch (`-b`)

```bash
# Create a new branch 'feature-auth' based on 'main' in a new folder
git worktree add -b feature-auth ../my-project-auth main
```

---

## 📋 Listing & Inspecting Worktrees (`git worktree list`)

View all active linked worktree directories and their checked-out commits/branches:

```bash
git worktree list
```

### Example Output:

```text
/Users/username/Projects/my-project          a1b2c3d [main]
/Users/username/Projects/my-project-staging  e4f5g6h [staging]
/Users/username/Projects/my-project-auth     7i8j9k0 [feature-auth]
```

---

## 🔄 Switching Between Worktrees Effortlessly

Because worktrees are independent working directories on your filesystem, you switch between branches simply by changing directories:

```bash
# Jump to the hotfix worktree
cd ../hotfix

# Work in your terminal, run tests, or edit files
git status

# Jump back to your primary feature branch
cd ../feature-auth
```

You can also use tools like `zoxide` (`z hotfix`) or separate terminal tabs to keep multiple branches open and running side-by-side without any stash or checkout overhead.

---

## 🧹 Removing & Cleaning Up Worktrees

When you are done with a feature or PR:

### 1. Remove Worktree Safely

```bash
# Deletes the folder and unlinks the worktree in Git
git worktree remove ../my-project-auth
```

### 2. Prune Dead References

If you deleted a worktree folder manually (e.g. `rm -rf`), prune the stale tracking metadata:

```bash
git worktree prune
```

---

## 🏗️ Recommended Directory Structure (Bare Repo Pattern)

For professional power users, clone projects as a **bare repository** and treat all branches as sibling worktrees:

```bash
# 1. Clone repo as bare
git clone --bare git@github.com:org/app.git .bare

# 2. Point root directory to the bare repo
echo "gitdir: ./.bare" > .git

# 3. Create worktrees for main and features
git worktree add main
git worktree add feature-login
```

Your directory structure looks clean and organized:

```text
my-app/
├── .bare/
├── .git
├── main/
├── feature-login/
└── hotfix-v1.2/
```

---

## 🛠️ Real-World Development Scenarios

### 1. Reviewing a Teammate's PR Locally

```bash
# Fetch and checkout PR in an isolated folder
git fetch origin pull/123/head:pr-123
git worktree add ../review-pr-123 pr-123

# Test and run the code
cd ../review-pr-123
npm test

# Done reviewing? Clean up in one step
cd ../my-project
git worktree remove ../review-pr-123
```

### 2. Comparing UI Builds Side-by-Side

Run two dev servers simultaneously (e.g., `main` on port 3000 and `redesign` on port 3001) without code interference.

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Goal | Command |
| :--- | :--- |
| **New worktree (new branch)** | `git worktree add -b <branch> <path> <base>` |
| **New worktree (existing)** | `git worktree add <path> <branch>` |
| **List all worktrees** | `git worktree list` |
| **Remove worktree** | `git worktree remove <path>` |
| **Lock worktree (prevent prune)** | `git worktree lock <path>` |
| **Prune stale worktrees** | `git worktree prune` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Git worktree aliases
alias gwa='git worktree add'
alias gwl='git worktree list'
alias gwr='git worktree remove'
alias gwp='git worktree prune'
```
