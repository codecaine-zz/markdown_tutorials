# starship: The Minimalist, Blazing-Fast Cross-Shell Prompt

## Table of Contents

1. [What is `starship`?](#1-what-is-starship)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Shell Configuration (`.zshrc`)](#4-shell-configuration-zshrc)
5. [Basic Features & Visual Examples](#5-basic-features--visual-examples)
6. [Configuring Starship (`starship.toml`)](#6-configuring-starship-starshiptoml)
7. [Preset Themes](#7-preset-themes)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `starship`?

`starship` is a fast, customizable, and intelligent cross-shell prompt written in Rust. It works seamlessly across Zsh, Bash, Fish, and PowerShell, providing real-time context about your environment—including current Git status, programming language runtime versions (Node.js, Python, Rust, Go), cloud context (AWS, GCP, Kubernetes), and command execution times.

#### Key Highlights
* **Zero Overhead:** Blazingly fast execution written in pure Rust.
* **Context-Aware:** Displays language and tool details only when relevant files exist in your directory.
* **Nerd Font Glyphs:** Crisp icons for git branches, operating systems, and developer tools.

---

### 2. Prerequisites

1. Ensure Homebrew is installed on your Apple Silicon Mac:
   ```bash
   brew --version
   ```
2. **Nerd Font Recommendation:** To display icons correctly, install a Nerd Font such as JetBrainsMono Nerd Font:
   ```bash
   brew install --cask font-jetbrains-mono-nerd-font
   ```

---

### 3. Installation on ARM macOS

Install `starship` via Homebrew:

```bash
brew install starship
```

Verify binary path and version:

```bash
which starship
starship --version
```

**Expected Output:**
```text
/opt/homebrew/bin/starship
starship 1.x.x
```

---

### 4. Shell Configuration (`.zshrc`)

On macOS, Zsh is the default shell. Add the initialization hook to your Zsh configuration file (`~/.zshrc`):

```bash
echo 'eval "$(starship init zsh)"' >> ~/.zshrc
source ~/.zshrc
```

#### Other Shells (Optional)
* **Fish:** Add `starship init fish | source` to `~/.config/fish/config.fish`
* **Bash:** Add `eval "$(starship init bash)"` to `~/.bashrc`

---

### 5. Basic Features & Visual Examples

Once configured, your command prompt automatically highlights directory status, active git branch, python virtual environment, or Node.js versions.

#### Example 1: Git Repository Prompt Context
Inside a Node.js Git repository:

```text
 🚀 project-root on  main [!] via  v20.11.0 
 ❯ 
```

* `project-root`: Current directory
* ` main`: Active Git branch
* `[!]`: Uncommitted changes
* ` v20.11.0`: Active Node.js runtime version

#### Example 2: Command Execution Time
When a command takes longer than 2 seconds (e.g., `sleep 3`):

```text
 🚀 project-root took 3.2s 
 ❯ 
```

---

### 6. Configuring Starship (`starship.toml`)

Create the configuration folder and file:

```bash
mkdir -p ~/.config
touch ~/.config/starship.toml
```

Here is a clean, practical `~/.config/starship.toml` configuration:

```toml
# Inserts a blank line between shell prompts
add_newline = true

# Character icon at the prompt line
[character]
success_symbol = "[❯](bold green)"
error_symbol = "[❯](bold red)"

# Directory formatting
[directory]
truncation_length = 3
truncate_to_repo = true
style = "bold cyan"

# Git status configuration
[git_branch]
symbol = " "
style = "bold purple"

[git_status]
style = "bold red"
stashed = "📦"
modified = "📝"
untracked = "❓"

# Language Modules (only show when runtime files are present)
[nodejs]
symbol = " "
style = "bold green"

[python]
symbol = " "
style = "yellow"

[rust]
symbol = "🦀 "
style = "bold red"

[aws]
disabled = true
```

---

### 7. Preset Themes

`starship` offers built-in preset configurations that can be applied with a single command:

#### Apply Pastel Powerline Preset:

```bash
starship preset pastel-powerline -o ~/.config/starship.toml
```

#### Apply Tokyo Night Preset:

```bash
starship preset tokyo-night -o ~/.config/starship.toml
```

---

### 8. Uninstallation

1. Remove the startup line `eval "$(starship init zsh)"` from `~/.zshrc`.
2. Uninstall the package:
   ```bash
   brew uninstall starship
   rm -f ~/.config/starship.toml
   ```
