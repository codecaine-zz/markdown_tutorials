## Table of Contents

1. [Basic Navigation Shortcuts](#basic-navigation-shortcuts)
2. [App Switching & Window Management](#app-switching--window-management)
3. [Files (Nautilus) File Manager Shortcuts](#files-nautilus-file-manager-shortcuts)
4. [Text Editing & Readline Shortcuts](#text-editing--readline-shortcuts)
5. [System-Level & Session Shortcuts](#system-level--session-shortcuts)
6. [Terminal & Developer Tools Shortcuts](#terminal--developer-tools-shortcuts)
7. [Accessibility & Display Shortcuts](#accessibility--display-shortcuts)
8. [Web Browsing & Productivity Apps](#web-browsing--productivity-apps)
9. [Programmatic Shortcut Configuration with Gsettings](#programmatic-shortcut-configuration-with-gsettings)
10. [Troubleshooting & Conflict Resolution](#troubleshooting--conflict-resolution)

---

## Basic Navigation Shortcuts

### Core Desktop Navigation
```bash
# Super Key (Windows Key) - Open Activities Overview & Search
Super

# Show All Installed Applications (App Grid)
Super + A

# Open Quick Settings Panel (Wi-Fi, Bluetooth, Power, Volume)
Super + S

# Open Notification Center & Calendar Tray
Super + V

# Open Run Command Dialog (Quick launcher for commands/binaries)
Alt + F2

# Open Default Terminal Emulator (GNOME Terminal / Console)
Ctrl + Alt + T

# Hide All Windows / Show Desktop
Super + D

# Show Active Keyboard Shortcut Overlay (in many GNOME apps)
Ctrl + ?  (or Ctrl + Shift + /)
```

### Virtual Workspaces Navigation
```bash
# Switch to Workspace Above / Left
Super + PageUp  (or Ctrl + Alt + Left / Up)

# Switch to Workspace Below / Right
Super + PageDown  (or Ctrl + Alt + Right / Down)

# Move Current Focused Window to Workspace Above
Super + Shift + PageUp

# Move Current Focused Window to Workspace Below
Super + Shift + PageDown

# Open Overview with Workspace Carousel
Super  # Then use mouse scroll or horizontal 3-finger swipe
```

---

## App Switching & Window Management

### Application Switching
```bash
# Switch between open applications across current workspace
Alt + Tab

# Switch backwards in application list
Alt + Shift + Tab

# Switch between multiple windows of the SAME application
Alt + ` (backtick / tilde)

# Switch between open windows across all workspaces
Super + Tab
```

### Window Snapping & Tiling Quick Actions
```bash
# Tile window to left half of screen
Super + Left Arrow

# Tile window to right half of screen
Super + Right Arrow

# Maximize active window to fill screen
Super + Up Arrow  (or Alt + F10)

# Restore / Unmaximize or Minimize active window
Super + Down Arrow

# Hide / Minimize current window to dock
Super + H

# Close current active window
Alt + F4  (or Ctrl + Q in modern GTK4 apps)

# Move active window across multiple monitors
Super + Shift + Left Arrow   # Move to left display
Super + Shift + Right Arrow  # Move to right display

# Move window using keyboard and mouse anywhere (holding Alt or Super)
Super + Left Click + Drag    # Move window without grabbing titlebar
Super + Middle Click + Drag  # Resize window easily
```

---

## Files (Nautilus) File Manager Shortcuts

### Navigation & Views
```bash
# Toggle display of hidden files and dotfiles (e.g. .bashrc, .git)
Ctrl + H

# Focus location bar to manually type or paste a directory path
Ctrl + L

# Open a new tab in current folder
Ctrl + T

# Open a new file manager window
Ctrl + N

# Close current tab
Ctrl + W

# Navigate back to previous directory
Alt + Left Arrow  (or Backspace)

# Navigate forward
Alt + Right Arrow

# Navigate up to parent directory
Alt + Up Arrow

# Search files and contents in current folder
Ctrl + F
```

### File Operations
```bash
# View Properties of selected file/folder
Alt + Enter

# Rename selected file (or batch rename multiple files)
F2

# Move selected file to Trash
Delete

# Permanently delete selected file (bypasses Trash folder)
Shift + Delete

# Bookmark current folder in sidebar
Ctrl + D

# Create a new directory / folder
Ctrl + Shift + N

# Select all items
Ctrl + A

# Invert current selection
Ctrl + Shift + I
```

---

## Text Editing & Readline Shortcuts

These universal Bash, Zsh, and GNU Readline shortcuts work across GNOME Terminal, standard text editors, and CLI shells:

### Line Navigation & Deletion
```bash
# Jump cursor to the beginning of the line
Ctrl + A  (or Home)

# Jump cursor to the end of the line
Ctrl + E  (or End)

# Jump backward one word
Alt + B  (or Ctrl + Left Arrow)

# Jump forward one word
Alt + F  (or Ctrl + Right Arrow)

# Delete from cursor to beginning of the line
Ctrl + U

# Delete from cursor to end of the line
Ctrl + K

# Delete previous word (before cursor)
Ctrl + W

# Delete next word (after cursor)
Alt + D

# Yank (paste) previously deleted/killed text
Ctrl + Y
```

### Shell Control & History Navigation
```bash
# Reverse search through shell command history
Ctrl + R

# Forward search through history (after reverse search)
Ctrl + S

# Clear terminal screen (preserves scrollback)
Ctrl + L

# Abort / Cancel current command or operation
Ctrl + C

# Suspend foreground process (send to background with bg / fg)
Ctrl + Z

# Send EOF (End-of-File) / Exit current shell
Ctrl + D
```

---

## System-Level & Session Shortcuts

### Security & Power
```bash
# Lock the screen immediately
Super + L

# Open Power Off / Restart / Log Out dialog
Ctrl + Alt + Delete

# Force restart X11 display server (if enabled in keyboard settings)
Ctrl + Alt + Backspace

# Switch to virtual Linux TTY consoles
Ctrl + Alt + F3   # TTY3 console login
Ctrl + Alt + F4   # TTY4 console login
Ctrl + Alt + F2   # Return to graphical desktop session (GDM / GNOME)
```

### Screenshots & Screen Recording
```bash
# Open interactive GNOME Screenshot & Screen Recording UI
PrintScreen

# Capture entire screen instantly to ~/Pictures/Screenshots
Shift + PrintScreen

# Capture active window only
Alt + PrintScreen

# Start / Stop interactive screen recording
Ctrl + Alt + Shift + R
```

### Emergency Linux Magic SysRq Recovery
When the desktop completely freezes without crashing the kernel, use the Linux Magic SysRq sequence (**R-E-I-S-U-B**):
```bash
# Hold down Alt + SysRq (PrintScreen) and press keys sequentially with 2s pauses:
# R: Unraw - Take keyboard control back from X/Wayland
# E: Terminate - Send SIGTERM to all processes
# I: Kill - Send SIGKILL to stubborn processes
# S: Sync - Flush all cached memory data to storage drives
# U: Unmount - Remount all filesystems as Read-Only (prevents corruption)
# B: Boot - Immediately reboot system safely
Alt + SysRq + R -> E -> I -> S -> U -> B
```

---

## Terminal & Developer Tools Shortcuts

### GNOME Terminal / Console Hotkeys
```bash
# Copy highlighted text (standard Ctrl+C sends interrupt)
Ctrl + Shift + C

# Paste clipboard content into terminal
Ctrl + Shift + V

# Open a new terminal tab
Ctrl + Shift + T

# Close current terminal tab
Ctrl + Shift + W

# Open a completely new terminal window
Ctrl + Shift + N

# Switch between tabs in terminal
Ctrl + PageUp    # Previous tab
Ctrl + PageDown  # Next tab
Alt + 1..9       # Jump directly to tab number 1 through 9

# Find text in terminal scrollback buffer
Ctrl + Shift + F

# Zoom in / Increase font size
Ctrl + + (or Ctrl + =)

# Zoom out / Decrease font size
Ctrl + -

# Reset font size to default
Ctrl + 0
```

---

## Accessibility & Display Shortcuts

```bash
# Toggle Orca Screen Reader on/off
Super + Alt + S

# Toggle Desktop Screen Magnifier (Zoom)
Super + Alt + 8

# Zoom in magnifier
Super + Alt + =

# Zoom out magnifier
Super + Alt + -

# Invert screen colors (High Contrast accessibility)
Super + Alt + C
```

---

## Web Browsing & Productivity Apps

### Web Browsers (Firefox, Chrome, Brave)
```bash
# Open new tab
Ctrl + T

# Open new window
Ctrl + N

# Open new private / incognito window
Ctrl + Shift + P  (Firefox)
Ctrl + Shift + N  (Chrome)

# Reopen last closed tab
Ctrl + Shift + T

# Focus address bar
Ctrl + L  (or F6)

# Hard refresh (bypass cache)
Ctrl + F5  (or Ctrl + Shift + R)

# Open Developer Tools / Inspector
F12  (or Ctrl + Shift + I)

# Open JavaScript Console directly
Ctrl + Shift + J
```

---

## Programmatic Shortcut Configuration with Gsettings

You can inspect, export, and programmatically bind custom keyboard shortcuts in Ubuntu using `gsettings`:

```bash
# List all default window management shortcuts
gsettings list-recursively org.gnome.desktop.wm.keybindings

# Create a custom shortcut to launch VS Code with Super + C
# 1. Define custom binding path
CUSTOM_KEY="/org/gnome/settings-daemon/plugins/media-keys/custom-keybindings/custom0/"

# 2. Register the custom binding in the binding list
gsettings set org.gnome.settings-daemon.plugins.media-keys custom-keybindings \
    "['$CUSTOM_KEY']"

# 3. Configure shortcut name, command, and keybinding
gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:$CUSTOM_KEY name "Visual Studio Code"
gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:$CUSTOM_KEY command "code"
gsettings set org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:$CUSTOM_KEY binding "<Super>c"

# Verify configured shortcut
gsettings get org.gnome.settings-daemon.plugins.media-keys.custom-keybinding:$CUSTOM_KEY binding
```

---

## Troubleshooting & Conflict Resolution

### Resetting Shortcuts to Default
```bash
# Reset all window management keybindings to Ubuntu factory defaults
gsettings reset-recursively org.gnome.desktop.wm.keybindings

# Reset media and launcher keys
gsettings reset-recursively org.gnome.settings-daemon.plugins.media-keys

# Check if a specific shortcut is bound to multiple actions
gsettings list-recursively | grep -i "<Super>space"
```

### Modifying the Super / Modifier Key
```bash
# If Super key conflict exists with gaming or virtual machines:
# Set Overlay key to empty to prevent Super key triggering overview
gsettings set org.gnome.mutter overlay-key ''

# Restore Super key to open Overview
gsettings set org.gnome.mutter overlay-key 'Super_L'
```
