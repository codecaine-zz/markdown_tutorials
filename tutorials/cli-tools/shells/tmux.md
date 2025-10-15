# Tmux with Homebrew: Practical Guide

## Table of Contents

1. [Why tmux](#why-tmux)
2. [Install](#install)
3. [Quick Start](#quick-start)
4. [Core Keybindings](#core-keybindings)
5. [Windows and Panes](#windows-and-panes)
6. [Copy Mode](#copy-mode)
7. [Config (~/.tmux.conf)](#config-tmuxconf)
8. [Plugins (TPM)](#plugins-tpm)
9. [Troubleshooting](#troubleshooting)

## Why tmux

Persistent terminal sessions, split panes, multiple windows, detach/attach across SSH.

## Install

```bash
brew install tmux
 tmux -V
```

## Quick Start

```bash
tmux new -s work
# detach: Ctrl+b then d
# list: tmux ls
# attach: tmux attach -t work
```

## Core Keybindings

Prefix is Ctrl+b.

- New window: `c`
- Next/prev window: `n` / `p`
- Split vertical: `%`
- Split horizontal: `"`
- Switch pane: Arrow keys
- Resize: Prefix then hold Alt and arrows (or `:resize-pane -L 5` etc.)
- Rename window: `,`
- Kill pane/window: `x` / `&`

## Windows and Panes

- Create a layout: `Ctrl+b %` then `Ctrl+b "`
- Cycle layouts: `Ctrl+b Space`
- Move pane to new window: `Ctrl+b !`

## Copy Mode

- Enter: `Ctrl+b [`
- Navigate with arrows/Vim keys
- Start selection: Space, copy: Enter
- Paste: `Ctrl+b ]`

## Config (~/.tmux.conf)

```bash
cat > ~/.tmux.conf << 'EOF'
set -g mouse on
set -g history-limit 100000
setw -g mode-keys vi
set -g base-index 1
setw -g pane-base-index 1
bind -n C-h select-pane -L
bind -n C-j select-pane -D
bind -n C-k select-pane -U
bind -n C-l select-pane -R
EOF
```

Reload: `tmux source-file ~/.tmux.conf`.

## Plugins (TPM)

```bash
git clone https://github.com/tmux-plugins/tpm ~/.tmux/plugins/tpm
```

Add to `~/.tmux.conf`:

```tmux
set -g @plugin 'tmux-plugins/tpm'
set -g @plugin 'tmux-plugins/tmux-sensible'
run '~/.tmux/plugins/tpm/tpm'
```

Install inside tmux: `Prefix + I`.

## Troubleshooting

- Term colors: set terminal to use a Nerd Font and `set -g default-terminal "screen-256color"`
- SSH detach/attach: `tmux ls` then `tmux attach -t <name>`
- Keybinding not working: check prefix and conflicts
