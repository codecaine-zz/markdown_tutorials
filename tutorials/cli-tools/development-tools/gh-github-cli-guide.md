# GitHub CLI (gh) Guide

`gh` is the official GitHub command-line tool. It brings GitHub Pull Requests, Issues, Actions workflows, Gists, and repository management directly into your terminal alongside `git`.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview-prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Authentication (`gh auth`)](#authentication-gh-auth)
4. [Pull Request Operations (`gh pr`)](#pull-request-operations-gh-pr)
5. [Issue Management (`gh issue`)](#issue-management-gh-issue)
6. [Repository & Forking (`gh repo`)](#repository-forking-gh-repo)
7. [GitHub Actions Workflow Monitoring (`gh run` / `gh workflow`)](#github-actions-workflow-monitoring-gh-run-gh-workflow)
8. [Gists & Raw API Integration (`gh gist` / `gh api`)](#gists-raw-api-integration-gh-gist-gh-api)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

GitHub CLI (`gh`) streamlines development workflows by enabling you to review PR diffs, approve code, trigger CI/CD pipelines, and clone repositories without leaving your terminal.

---

## ⚙️ Installation via Homebrew

```bash
# Install gh on macOS
brew install gh

# Verify installation
gh --version
```

---

## 🔐 Authentication (`gh auth`)

Authenticate your CLI session with your GitHub account (supports HTTPS, SSH, and web browser OAuth login).

```bash
# Authenticate interactively via web browser or token
gh auth login

# Check authentication status
gh auth status

# Refresh OAuth scopes or tokens
gh auth refresh -s write:packages
```

---

## 🔀 Pull Request Operations (`gh pr`)

### 1. Create Pull Requests (`gh pr create`)
```bash
# Interactively create a PR for current branch
gh pr create

# Create PR with title, body, and reviewers specified via CLI flags
gh pr create \
  --title "feat: Add user authentication middleware" \
  --body "Implements JWT token validation and session persistence." \
  --reviewer team-leads \
  --assignee "@me"
```

### 2. View, Checkout & Review PRs
```bash
# List open PRs for current repository
gh pr list

# Checkout a PR locally by number or branch name
gh pr checkout 142

# View diff of current or specific PR
gh pr diff 142

# Review and approve a PR from CLI
gh pr review 142 --approve -b "Looks great! LGTM."

# Merge a PR with squash and delete branch
gh pr merge 142 --squash --delete-branch
```

---

## 🐛 Issue Management (`gh issue`)

```bash
# List open issues assigned to you
gh issue list --assignee "@me"

# Create a new issue interactively
gh issue create --title "Bug: Login button unresponsive on Safari" --label "bug"

# View details of a specific issue
gh issue view 89

# Close an issue
gh issue close 89 --comment "Resolved in PR #142"
```

---

## 📦 Repository & Forking (`gh repo`)

```bash
# Clone a repository using owner/repo shorthand
gh repo clone cli/cli

# Create a new repository on GitHub from current directory
gh repo create my-new-app --public --source=. --remote=origin

# Fork a repository to your account and clone it
gh repo fork facebook/react --clone
```

---

## ⚙️ GitHub Actions Workflow Monitoring (`gh run` / `gh workflow`)

Monitor and trigger GitHub Actions CI/CD pipelines directly from your terminal.

```bash
# List recent Actions workflow runs
gh run list

# Watch a active workflow run in real-time with log tailing
gh run watch

# View logs for a failed run
gh run view --log-failed

# Manually trigger a workflow (workflow_dispatch)
gh workflow run deploy.yml -f environment=staging
```

---

## 💡 Gists & Raw API Integration (`gh gist` / `gh api`)

### 1. Manage Gists (`gh gist`)
```bash
# Create a secret Gist from a file
gh gist create script.py --secret -d "Quick python data cleaner"

# List your Gists
gh gist list
```

### 2. Query GitHub REST / GraphQL API (`gh api`)
```bash
# Query authenticated user details via REST API
gh api user

# Query GraphQL API
gh api graphql -f query='
  query {
    viewer {
      login
      repositories(first: 5) {
        nodes { name }
      }
    }
  }
'
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Authenticate CLI | `gh auth login` |
| List Open PRs | `gh pr list` |
| Create PR | `gh pr create` |
| Checkout PR locally | `gh pr checkout <PR-number>` |
| Merge PR | `gh pr merge <PR-number> --squash` |
| Create Issue | `gh issue create` |
| Clone Repo | `gh repo clone <owner>/<repo>` |
| Watch CI Workflow | `gh run watch` |
