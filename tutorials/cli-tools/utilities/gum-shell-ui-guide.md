# Gum Shell UI Component Guide

`gum` is a tool for building interactive, terminal UI-driven shell scripts with minimal code. Created by Charm, `gum` provides composable CLI primitives like **inputs, pickers, confirmations, multi-line text editors, spinners, borders, and styled cards**.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview-prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Core UI Primitives](#core-ui-primitives)
   - [Interactive Inputs (`gum input`)](#1-interactive-inputs-gum-input)
   - [Select Pickers (`gum choose`)](#2-select-pickers-gum-choose)
   - [Fuzzy Search Filter (`gum filter`)](#3-fuzzy-search-filter-gum-filter)
   - [Yes/No Confirmations (`gum confirm`)](#4-yesno-confirmations-gum-confirm)
   - [Multi-line Text Editor (`gum write`)](#5-multi-line-text-editor-gum-write)
   - [Spinners & Task Progress (`gum spin`)](#6-spinners-task-progress-gum-spin)
   - [Styling & Borders (`gum style`)](#7-styling-borders-gum-style)
4. [💡 Practical Real-World Example: Interactive Git Commit Script](#practical-real-world-example-interactive-git-commit-script)
5. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

`gum` requires zero framework boilerplate. Each `gum` sub-command outputs text directly to `stdout` or returns standard exit status codes (`0` for success/Yes, `1` for cancellation/No), allowing seamless integration into shell scripts.

---

## ⚙️ Installation via Homebrew

```bash
# Install gum via Homebrew on macOS
brew install gum

# Verify installation
gum --version
```

---

## 🚀 Core UI Primitives

### 1. Interactive Inputs (`gum input`)
Prompt the user for single-line text or secret passwords.

```bash
# Single line input with placeholder text
name=$(gum input --placeholder "Enter service name...")

# Password input (characters hidden)
password=$(gum input --password --placeholder "Enter database secret...")
```

---

### 2. Select Pickers (`gum choose`)
Present a single-select or multi-select list.

```bash
# Single choice menu
environment=$(gum choose "Development" "Staging" "Production")

# Multi-select menu (spaces toggle items, enter confirms)
services=$(gum choose --no-limit "API Gateway" "Auth Microservice" "Worker Queue")
```

---

### 3. Fuzzy Search Filter (`gum filter`)
Allow users to search through long lists interactively using fuzzy matching.

```bash
# Select file interactively
file=$(ls -1 | gum filter --placeholder "Search file to edit...")

# Select git branch interactively
branch=$(git branch -a | gum filter --placeholder "Select branch...")
```

---

### 4. Yes/No Confirmations (`gum confirm`)
Ask confirmation questions returning exit status code `0` (Yes) or `1` (No).

```bash
if gum confirm "Deploy changes to Production?"; then
  echo "Deploying..."
else
  echo "Deployment aborted."
  exit 1
fi
```

---

### 5. Multi-line Text Editor (`gum write`)
Prompt for multi-line text input (e.g. PR description, commit body).

```bash
body=$(gum write --placeholder "Enter detailed release notes (Ctrl+D to finish)...")
```

---

### 6. Spinners & Task Progress (`gum spin`)
Show a animated terminal spinner while running background shell commands.

```bash
# Run npm build with a spinner
gum spin --spinner dot --title "Building production assets..." -- npm run build

# Run database migration with a spinner
gum spin --spinner pulse --title "Running database migrations..." -- php artisan migrate
```

---

### 7. Styling & Borders (`gum style`)
Render styled boxes, colors, padding, and text alignment.

```bash
# Render styled double border box with magenta background
gum style \
  --foreground 212 \
  --border-foreground 212 \
  --border double \
  --align center \
  --width 50 \
  --margin "1 2" \
  --padding "1 2" \
  "🚀 DEPLOYMENT SUCCESSFUL"
```

---

## 💡 Practical Real-World Example: Interactive Git Commit Script

Save this script as `gcommit.sh` and make it executable (`chmod +x gcommit.sh`):

```bash
#!/bin/bash
# Interactive Git Commit Builder using Gum

# Step 1: Select Commit Type
TYPE=$(gum choose "feat" "fix" "docs" "style" "refactor" "test" "chore")

# Step 2: Input Scope (Optional)
SCOPE=$(gum input --placeholder "Scope (e.g. auth, api, ui - optional)")
if [ -n "$SCOPE" ]; then
  SCOPE="($SCOPE)"
fi

# Step 3: Input Short Summary
SUMMARY=$(gum input --placeholder "Write a short imperative summary...")

# Step 4: Input Detailed Body (Optional)
BODY=$(gum write --placeholder "Detailed commit description (Ctrl+D to complete)...")

# Combine Commit Title
COMMIT_TITLE="${TYPE}${SCOPE}: ${SUMMARY}"

# Render Preview Box
gum style \
  --border rounded \
  --padding "1 2" \
  --border-foreground 212 \
  "COMMIT PREVIEW:" "$COMMIT_TITLE" "" "$BODY"

# Step 5: Confirm & Execute Commit
if gum confirm "Commit changes with this message?"; then
  if [ -n "$BODY" ]; then
    git commit -m "$COMMIT_TITLE" -m "$BODY"
  else
    git commit -m "$COMMIT_TITLE"
  fi
  gum style --foreground 82 "✔ Commit successfully created!"
else
  gum style --foreground 196 "✖ Commit canceled."
fi
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Text Input | `gum input --placeholder "text"` |
| Select Menu | `gum choose "Option1" "Option2"` |
| Fuzzy Filter List | `gum filter` |
| Confirmation Prompt | `gum confirm "Proceed?"` |
| Multiline Editor | `gum write` |
| Loading Spinner | `gum spin --title "Loading..." -- command` |
| Styled Card Box | `gum style --border rounded "Text"` |