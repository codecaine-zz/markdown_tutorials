# macOS Power CLI & Launchd Administration Guide

macOS is built upon the XNU hybrid kernel and BSD userland. Behind the Aqua graphical interface lies an enterprise-grade Unix command-line foundation. Mastering `defaults`, `launchd`, `pmset`, `networksetup`, and APFS snapshot tooling unlocks deep automation and system tuning.

---

## 📚 Table of Contents

1. [Hidden System Preferences via `defaults write`](#1-hidden-system-preferences-via-defaults-write)
2. [Launchd Deep Dive: Modern Background Automation](#2-launchd-deep-dive-modern-background-automation)
   - [LaunchAgents vs. LaunchDaemons](#launchagents-vs-launchdaemons)
   - [Writing a Custom LaunchAgent Property List (`.plist`)](#writing-a-custom-launchagent-property-list-plist)
   - [Modern `launchctl` Syntax (`bootstrap`, `bootout`, `kickstart`)](#modern-launchctl-syntax-bootstrap-bootout-kickstart)
3. [Power Management & Battery Tuning (`pmset` & `caffeinate`)](#3-power-management--battery-tuning-pmset--caffeinate)
4. [Hardware & Network Administration (`networksetup` & `scutil`)](#4-hardware--network-administration-networksetup--scutil)
5. [APFS Snapshots & Disk Management (`tmutil` & `diskutil`)](#5-apfs-snapshots--disk-management-tmutil--diskutil)
6. [Native Automation Utilities (`sips`, `screencapture`, `textutil`)](#6-native-automation-utilities-sips-screencapture-textutil)

---

## 1. Hidden System Preferences via `defaults write`

The `defaults` command interacts directly with the `cfprefsd` daemon to manage macOS property lists (`~/Library/Preferences`).

```bash
# --- Finder Enhancements ---
# Always show file extensions
defaults write NSGlobalDomain AppleShowAllExtensions -bool true

# Show hidden files by default
defaults write com.apple.finder AppleShowAllFiles -bool true

# Display full POSIX path in Finder window titlebar
defaults write com.apple.finder _FXShowPosixPathInTitle -bool true

# Keep folders on top when sorting by name
defaults write com.apple.finder _FXSortFoldersFirst -bool true

# Disable the warning before emptying the Trash
defaults write com.apple.finder WarnOnEmptyTrash -bool false

# --- Dock & Window Manager Tuning ---
# Remove Dock show/hide delay and speed up animation
defaults write com.apple.dock autohide-delay -float 0
defaults write com.apple.dock autohide-time-modifier -float 0.2

# Group windows by application in Mission Control
defaults write com.apple.dock expose-group-apps -bool true

# --- Screenshots Configuration ---
# Save screenshots to dedicated directory
mkdir -p ~/Pictures/Screenshots
defaults write com.apple.screencapture location -string "~/Pictures/Screenshots"

# Save as PNG without drop shadows
defaults write com.apple.screencapture type -string "png"
defaults write com.apple.screencapture disable-shadow -bool true

# Restart affected services to apply
killall Finder
killall Dock
killall SystemUIServer
```

---

## 2. Launchd Deep Dive: Modern Background Automation

`launchd` is macOS's unified init and daemon manager (the Apple equivalent of Linux's `systemd`). It supersedes `cron`, `init`, and `inetd`.

### LaunchAgents vs. LaunchDaemons

| Type | Path | Run Context | Use Case |
| :--- | :--- | :--- | :--- |
| **User LaunchAgents** | `~/Library/LaunchAgents` | Logged-in user session | Developer scripts, background watchers |
| **System LaunchAgents** | `/Library/LaunchAgents` | Runs per user at login | Machine-wide user tools |
| **System LaunchDaemons**| `/Library/LaunchDaemons`| `root` (Independent of UI login)| System services, background servers |

### Writing a Custom LaunchAgent Property List (`.plist`)

Create an automated daily database backup agent:

`~/Library/LaunchAgents/com.user.dbbackup.plist`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.user.dbbackup</string>

    <key>ProgramArguments</key>
    <array>
        <string>/bin/zsh</string>
        <string>-c</string>
        <string>/usr/local/bin/backup-postgres.sh</string>
    </array>

    <!-- Run every day at 03:00 AM -->
    <key>StartCalendarInterval</key>
    <dict>
        <key>Hour</key>
        <integer>3</integer>
        <key>Minute</key>
        <integer>0</integer>
    </dict>

    <key>StandardOutPath</key>
    <string>/tmp/dbbackup.stdout.log</string>
    <key>StandardErrorPath</key>
    <string>/tmp/dbbackup.stderr.log</string>

    <key>RunAtLoad</key>
    <false/>
</dict>
</plist>
```

### Modern `launchctl` Syntax

Avoid legacy commands (`load`/`unload`). Modern macOS uses domain targets (`gui/<uid>`):

```bash
# Get current User Domain Target
USER_DOMAIN="gui/$(id -u)"

# 1. Bootstrap (register and enable) service
launchctl bootstrap "$USER_DOMAIN" ~/Library/LaunchAgents/com.user.dbbackup.plist

# 2. Trigger immediate test run
launchctl kickstart -k "$USER_DOMAIN/com.user.dbbackup"

# 3. Check service status and last exit code
launchctl print "$USER_DOMAIN/com.user.dbbackup"

# 4. Bootout (disable and unregister) service
launchctl bootout "$USER_DOMAIN/com.user.dbbackup"
```

---

## 3. Power Management & Battery Tuning (`pmset` & `caffeinate`)

Control sleep states, display dimming, and thermal profiles via `pmset`:

```bash
# View active power management settings
pmset -g

# View what is currently preventing system sleep
pmset -g assertions

# Set display sleep to 15 min on battery (-b) and 60 min on wall charger (-c)
sudo pmset -b displaysleep 15
sudo pmset -c displaysleep 60

# Prevent sleep while compiling or running a script using caffeinate
# -d prevents display sleep; -s prevents system sleep; -i prevents idle sleep
caffeinate -dis ./long-running-build.sh

# Keep Mac awake for exactly 2 hours (7200 seconds)
caffeinate -t 7200 &
```

---

## 4. Hardware & Network Administration (`networksetup` & `scutil`)

### Manage Network Interfaces

```bash
# List all active network hardware ports
networksetup -listallhardwareports

# Set custom DNS servers on Wi-Fi (Cloudflare + Google)
sudo networksetup -setdnsservers Wi-Fi 1.1.1.1 1.0.0.1 8.8.8.8

# Flush local DNS resolver cache on macOS
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder

# Toggle Wi-Fi power off and on
networksetup -setairportpower en0 off
networksetup -setairportpower en0 on
```

### Dynamic Configuration Store (`scutil`)

```bash
# Change hostnames cleanly
sudo scutil --set ComputerName "Workstation-M3"
sudo scutil --set HostName "workstation-m3.local"
sudo scutil --set LocalHostName "Workstation-M3"

# Inspect DNS resolution routing table
scutil --dns
```

---

## 5. APFS Snapshots & Disk Management (`tmutil` & `diskutil`)

Apple File System (APFS) supports instantaneous, copy-on-write read-only point-in-time snapshots of the file system.

```bash
# Create an instantaneous local APFS snapshot before risky OS updates or package installs
tmutil localsnapshot

# List existing APFS snapshots
tmutil listlocalsnapshots /

# Mount a specific snapshot as read-only to inspect or recover files
mkdir -p /tmp/snapshot-mount
mount_apfs -s "com.apple.TimeMachine.2026-10-02-120000.local" / /tmp/snapshot-mount

# Delete a specific snapshot
tmutil deletelocalsnapshots 2026-10-02-120000
```

---

## 6. Native Automation Utilities (`sips`, `screencapture`, `textutil`)

macOS includes powerful built-in media manipulation tools that eliminate dependencies on external packages:

### Batch Image Resizing with `sips` (Scriptable Image Processing System)

```bash
# Resize image to max 1200px width while preserving aspect ratio
sips --resampleWidth 1200 input.png --out output.png

# Convert image format (PNG to WebP or JPEG)
sips -s format jpeg input.png --out output.jpg

# Batch resize all PNGs in a directory
sips --resampleWidth 800 *.png
```

### High-Resolution Automated Screen Capture

```bash
# Capture full screen silently after 3 second delay
screencapture -T 3 -x ~/Desktop/capture.png

# Interactively select a window and copy image directly to clipboard
screencapture -i -c
```
