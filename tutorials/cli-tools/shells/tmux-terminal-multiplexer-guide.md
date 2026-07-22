# Tmux Terminal Multiplexer Guide

`tmux` is a terminal multiplexer that enables you to create, manage, and persist multiple terminal sessions from a single window. Even if your SSH connection drops or terminal crashes, sessions running in `tmux` stay alive on the background host.

---

## 📚 Table of Contents

1. [Overview & Benefits](#overview--benefits)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Session Management](#session-management)
4. [Windows & Panes Operations](#windows--panes-operations)
5. [Keybindings Reference (Prefix: `Ctrl+b`)](#keybindings-reference-prefix-ctrlb)
6. [Copy Mode & Vim Keybindings](#copy-mode--vim-keybindings)
7. [Customizing Configuration (`~/.tmux.conf`)](#customizing-configuration-tmuxconf)
8. [Tmux Plugin Manager (TPM) & Persistence](#tmux-plugin-manager-tpm--persistence)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Benefits

- **Persistence**: Long-running builds or downloads continue even if your shell disconnects.
- **Organization**: Group work into named sessions, windows (tabs), and split panes (tiled views).
- **Customizability**: Full mouse support, custom colors, status bars, and automated layout setups.

---

## ⚙️ Installation via Homebrew

```bash
# Install tmux on macOS
brew install tmux

# Verify installation
tmux -V
```

---

## 🚀 Session Management

### 1. Start & Name Sessions
```bash
# Start a new un-named session
tmux

# Start a named session (Recommended)
tmux new -s dev-environment
```

### 2. List & Attach Sessions
```bash
# List all active background tmux sessions
tmux ls

# Attach to a specific session by name
tmux attach -t dev-environment

# Attach to the last active session
tmux a
```

### 3. Detach & Kill Sessions
```bash
# Detach from current session back to shell: Press Ctrl+b then d

# Kill a specific session
tmux kill-session -t dev-environment

# Kill all background sessions
tmux kill-server
```

---

## 🪟 Windows & Panes Operations

- **Session**: A collection of windows managed by tmux.
- **Window**: A full-screen view within a session (like a browser tab).
- **Pane**: A rectangular division inside a single window (split screen).

### Split Screen Workflow
1. Split current window horizontally: `Ctrl+b "`
2. Split current window vertically: `Ctrl+b %`
3. Switch active pane: `Ctrl+b` + `Arrow Keys`
4. Toggle pane zoom (full screen): `Ctrl+b z`

---

## 🎹 Keybindings Reference (Prefix: `Ctrl+b`)

All shortcuts require pressing the prefix key combo `Ctrl+b` first, followed by the shortcut character.

| Action | Shortcut |
| --- | --- |
| **Detach Session** | `Ctrl+b d` |
| **Create Window** | `Ctrl+b c` |
| **Rename Window** | `Ctrl+b ,` |
| **Next / Previous Window** | `Ctrl+b n` / `Ctrl+b p` |
| **Switch Window by Number** | `Ctrl+b 0..9` |
| **Split Vertically (`\|`)** | `Ctrl+b %` |
| **Split Horizontally (`-`)** | `Ctrl+b "` |
| **Toggle Pane Zoom** | `Ctrl+b z` |
| **Toggle Pane Numbers** | `Ctrl+b q` |
| **Kill Current Pane** | `Ctrl+b x` |
| **Kill Current Window** | `Ctrl+b &` |

---

## 📋 Copy Mode & Vim Keybindings

Scroll back through history and copy text directly using Vim shortcuts.

1. Enter Copy Mode: `Ctrl+b [`
2. Move cursor using Vim keys (`h`, `j`, `k`, `l`, `Ctrl+u`, `Ctrl+d`)
3. Begin text selection: Press `v` or `Space`
4. Copy selected text: Press `y` or `Enter`
5. Paste text: `Ctrl+b ]`

---

## ⚙️ Customizing Configuration (`~/.tmux.conf`)

Create a customized `~/.tmux.conf` for mouse scrolling, 256-color support, Vim navigation, and custom pane splitting shortcuts.

```bash
cat << 'EOF' > ~/.tmux.conf
# Enable mouse support (scrolling, clicking tabs/panes)
set -g mouse on

# Increase scrollback history limit
set -g history-limit 50000

# Start window and pane indexing at 1 (instead of 0)
set -g base-index 1
setw -g pane-base-index 1

# Re-number windows automatically when one is closed
set -g renumber-windows on

# Use Vim keybindings in Copy mode
setw -g mode-keys vi

# Split panes using | and -
bind | split-window -h -c "#{pane_current_path}"
bind - split-window -v -c "#{pane_current_path}"
unbind '"'
unbind %

# Switch panes using Vim direction keys (no prefix needed with Alt)
bind -n M-h select-pane -L
bind -n M-l select-pane -R
bind -n M-k select-pane -U
bind -n M-j select-pane -D

# Status Bar Styling
set -g status-style bg=default,fg=colour4
set -g status-left-length 40
set -g status-right "#[fg=yellow]%Y-%m-%d %H:%M"
EOF
```

Reload config in active tmux session:
```bash
tmux source-file ~/.tmux.conf
```

---

## 🔌 Tmux Plugin Manager (TPM) & Persistence

Automate session saving and restoration across system reboots using `tmux-resurrect` and `tmux-continuum`.

### 1. Install TPM
```bash
git clone https://github.com/tmux-plugins/tpm ~/.tmux/plugins/tpm
```

### 2. Add Plugins to `~/.tmux.conf`
```tmux
# Add at the bottom of ~/.tmux.conf
set -g @plugin 'tmux-plugins/tpm'
set -g @plugin 'tmux-plugins/tmux-sensible'
set -g @plugin 'tmux-plugins/tmux-resurrect'
set -g @plugin 'tmux-plugins/tmux-continuum'

# Enable automatic restore on tmux start
set -g @continuum-restore 'on'

# Initialize TMUX plugin manager (keep this line at the very bottom)
run '~/.tmux/plugins/tpm/tpm'
```

### 3. Install Plugins
Inside `tmux`, press `Ctrl+b` followed by `Shift+i` (`I`) to install plugins.

---

## 📋 Cheat Sheet Summary

| Task | Command / Shortcut |
| --- | --- |
| Create named session | `tmux new -s <name>` |
| Attach to session | `tmux attach -t <name>` |
| List sessions | `tmux ls` |
| Detach session | `Ctrl+b d` |
| Vertical split | `Ctrl+b %` (or `Ctrl+b \|` if configured) |
| Horizontal split | `Ctrl+b "` (or `Ctrl+b -` if configured) |
| Zoom pane | `Ctrl+b z` |
| Save session state | `Ctrl+b Ctrl+s` (with resurrect) |
| Restore session state | `Ctrl+b Ctrl+r` (with resurrect) |
