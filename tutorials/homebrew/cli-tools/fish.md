# fish: Friendly Interactive Shell with Homebrew

## Table of Contents

1. [What is fish?](#1-what-is-fish)
2. [Install with Homebrew](#2-install-with-homebrew)
3. [Make fish your default shell](#3-make-fish-your-default-shell)
4. [Basics: config.fish, aliases, and variables](#4-basics-configfish-aliases-and-variables)
5. [Completions and prompt](#5-completions-and-prompt)
6. [Beginner examples](#6-beginner-examples)
7. [Intermediate: functions, abbreviations, and fzf](#7-intermediate-functions-abbreviations-and-fzf)
8. [Advanced: plugin manager, direnv, and performance](#8-advanced-plugin-manager-direnv-and-performance)
9. [Troubleshooting](#9-troubleshooting)
10. [Uninstall](#10-uninstall)

-----

### 1. What is fish?

fish is a user-friendly shell with smart suggestions, autosuggestions, syntax highlighting, and great defaults—no need for complex configuration.

### 2. Install with Homebrew

```bash
brew install fish
```

Check path:

```bash
brew --prefix fish
```

Example:

```text
/opt/homebrew/opt/fish
```

### 3. Make fish your default shell

```bash
FISH_PATH="$(brew --prefix)/bin/fish"
grep -qx "$FISH_PATH" /etc/shells || echo "$FISH_PATH" | sudo tee -a /etc/shells
chsh -s "$FISH_PATH"
```

Verify:

```bash
echo $SHELL
fish --version
```

### 4. Basics: config.fish, aliases, and variables

fish keeps config in `~/.config/fish/config.fish`.

Create it:

```fish
# ~/.config/fish/config.fish
set -gx PATH (brew --prefix)/bin (brew --prefix)/sbin $PATH

# Colorful, sane defaults
set -g fish_greeting ""

# Aliases (use functions for args)
alias ll='eza -lah --git'
alias gs='git status'

# Environment
set -gx EDITOR micro
```

Reload:

```bash
exec fish -l
```

### 5. Completions and prompt

fish ships rich completions. To add more from Homebrew packages, ensure `$PATH` is correct; fish auto-discovers completions under share directories.

Prompt: fish includes the "fish_default" theme. For a fancier prompt, install Starship or Tide.

```bash
brew install starship
```

Add to `config.fish`:

```fish
starship init fish | source
```

### 6. Beginner examples

- Autosuggestions: start typing a previous command; press right-arrow to accept.
- Globbing and wildcard examples:

```fish
ls **/*.md
grep -R "brew install" tutorials/**.md
```

Output excerpt:

```text
tutorials/homebrew/cli-tools/fzf.md
tutorials/homebrew/cli-tools/jq.md
...
```

### 7. Intermediate: functions, abbreviations, and fzf

Create a function with completion:

```fish
function mkcd --description 'make directory and cd into it'
  test (count $argv) -eq 0; and echo 'usage: mkcd <dir>' >&2; and return 1
  mkdir -p $argv[1]; and cd $argv[1]
end
functions -c mkcd mkcd --save
```

Abbreviations auto-expand as you type:

```fish
abbr -a gs 'git status'
abbr -a gco 'git checkout'
```

fzf integration:

```bash
brew install fzf fd
"$(brew --prefix)"/opt/fzf/install
```

Connect keybindings in `config.fish`:

```fish
set -l fzf_dir (brew --prefix)/opt/fzf
source $fzf_dir/shell/key-bindings.fish
```

Example interaction (history search):

```text
> brew install fish
  git status
  npm run dev

3/1021
> brew
```

### 8. Advanced: plugin manager, direnv, and performance

Popular manager: fisher

```bash
brew install fisher direnv
fish -c 'curl -sL https://git.io/fisher | source && fisher install jorgebucaran/fisher'
```

Install useful plugins:

```fish
fisher install jorgebucaran/nvm.fish jethrokuan/z rafaelrinaldi/pure
```

Enable direnv:

```fish
direnv hook fish | source
```

Performance tips:

- Minimize heavy prompts; Starship is fast.
- Avoid starting nvm/sdkman on every shell; lazy-load when needed.

### 9. Troubleshooting

- If `chsh` fails, log out/in and retry.
- Ensure `(brew --prefix)/bin` is in `PATH`.
- For slow startup, comment out plugins to bisect.

### 10. Uninstall

```bash
brew uninstall starship fish
sudo sed -i '' '/\/opt\/homebrew\/bin\/fish/d' /etc/shells
chsh -s /bin/zsh
```
