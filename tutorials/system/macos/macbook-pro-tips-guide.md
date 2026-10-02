# MacBook Pro Tips & Performance Optimization Guide

A comprehensive, production-grade guide to optimizing, configuring, and maintaining macOS on MacBook Pro systems (Apple Silicon M1/M2/M3/M4 and Intel). Covers low-level hardware diagnostics, macOS defaults customization, memory and thermal management, battery longevity, developer toolchains, modern CLI utilities, and automated system maintenance.

---

## Table of Contents
1. [Hardware Optimization](#hardware-optimization)
2. [System Preferences](#system-preferences)
3. [Performance Tweaks](#performance-tweaks)
4. [Battery Life Management](#battery-life-management)
5. [Keyboard and Input Tips](#keyboard-and-input-tips)
6. [Software Optimization](#software-optimization)
7. [Developer Tools & Terminal Commands](#developer-tools-terminal-commands)
8. [Productivity Hacks](#productivity-hacks)
9. [Troubleshooting and Maintenance](#troubleshooting-and-maintenance)

---

## Hardware Optimization

### Thermal Management & Hardware Diagnostics
```bash
# Query detailed Apple Silicon / Intel hardware specifications
system_profiler SPHardwareDataType

# Query CPU core layout: total cores, physical cores, logical cores
sysctl -n hw.ncpu hw.physicalcpu hw.logicalcpu

# On Apple Silicon (M1/M2/M3/M4): inspect performance vs efficiency core counts
sysctl -n hw.perflevel0.physicalcpu  # Performance cores (P-cores)
sysctl -n hw.perflevel1.physicalcpu  # Efficiency cores (E-cores)

# Real-time power metrics, thermal sensors, and core frequencies (requires admin)
sudo powermetrics --samplers thermal,cpu_power,gpu_power -i 1000 -n 1

# Continuous thermal pressure and SMC sensor monitoring (sampled every 2s)
sudo powermetrics --samplers smc,thermal -i 2000

# Check CPU thermal throttling level (0 = normal, >0 = thermal throttling active)
sysctl machdep.cpu.thermal_level

# Inspect active thermal pressure state via macOS thermal subsystem
sudo pmset -g therm

# Check real-time CPU temperature via third-party open-source CLI (if installed)
# Install via Homebrew: brew install osx-cpu-temp
osx-cpu-temp
```

### Display Settings & External Monitors
```bash
# Query all connected displays, active resolutions, color depth, and refresh rates
system_profiler SPDisplaysDataType | grep -E "(Display Type|Resolution|UI Looks like|Mirror|Online|Main Display|Refresh Rate)"

# Check if ProMotion (120Hz) or high refresh rate is active on internal Retina screen
system_profiler SPDisplaysDataType | grep -i "120"

# Adjust internal display brightness programmatically via CLI
# Install utility via Homebrew: brew install brightness
brightness 0.85   # Set display to 85% brightness
brightness -l     # List all attached displays and current brightness levels

# Force graphics switching mode on dual-GPU Intel MacBook Pros (AMD Radeon + Intel Iris):
sudo pmset -a gpuswitch 0   # Force integrated GPU (battery conservation)
sudo pmset -a gpuswitch 1   # Force discrete GPU (high performance rendering)
sudo pmset -a gpuswitch 2   # Enable dynamic automatic graphics switching (default)

# Clamshell mode configuration (prevent sleeping when lid is closed with external monitor and AC power)
sudo pmset -a disablesleep 0   # Normal sleep behavior
sudo pmset -a lidwake 1        # Wake system automatically upon opening the lid

# Manage resolution and layout for multi-monitor setups via displayplacer
# Install via Homebrew: brew install displayplacer
displayplacer list             # List screen IDs, available resolutions, and coordinates
```

### Storage Optimization & APFS Maintenance
```bash
# Inspect all mounted filesystems, storage usage, and mount points
df -h

# Check APFS containers, partition scheme, and volume health
diskutil apfs list

# Query internal NVMe/SSD drive info, SMART status, and TRIM support
diskutil info / | grep -E "(Solid State|TRIM|SMART|Protocol|Volume Name|Free Space)"

# List all APFS Time Machine local snapshots consuming disk space
tmutil listlocalsnapshots /

# Delete a specific local snapshot to reclaim disk space immediately
# Replace with snapshot date returned by listlocalsnapshots (e.g. 2026-10-02-140000)
sudo tmutil deletelocalsnapshots 2026-10-02-140000

# Thin local snapshots automatically to purge up to 20GB (20000000000 bytes) of purgeable space
sudo tmutil thinlocalsnapshots / 20000000000 4

# Visualize disk hog directories using modern Rust tools
# Install via Homebrew: brew install dust duf
dust -d 2 ~           # Visual inverted tree showing top storage consumers
duf                   # Beautiful tabular layout of disk usage and device types

# Reclaim gigabytes from developer caches
rm -rf ~/Library/Developer/Xcode/DerivedData/*       # Xcode build caches
xcrun simctl erase all                              # Erase all iOS Simulator runtimes
rm -rf ~/Library/Caches/CocoaPods                   # CocoaPods cache
rm -rf ~/.npm/_cacache ~/.bun/install/cache         # Node and Bun package caches

# Spotlight indexing management:
sudo mdutil -s /       # Check Spotlight indexing status on boot volume
sudo mdutil -a -i off  # Temporarily disable Spotlight indexing across all volumes
sudo mdutil -a -i on   # Re-enable Spotlight indexing
sudo mdutil -E /       # Erase and rebuild Spotlight search index on boot volume
```

---

## System Preferences

### Energy Saver & Power Management Profiles
```bash
# View active power configuration across Battery (-b), AC Charger (-c), and UPS (-u)
pmset -g custom

# Configure battery mode optimization (aggressive power saving)
sudo pmset -b displaysleep 5    # Screen turns off after 5 minutes of inactivity
sudo pmset -b sleep 10          # System sleeps after 10 minutes
sudo pmset -b disksleep 10      # Spin down idle disks after 10 minutes
sudo pmset -b tcpkeepalive 0    # Disable network polling during sleep (prevents sleep drain)

# Configure AC Charger mode optimization (maximum responsiveness)
sudo pmset -c displaysleep 15   # Screen turns off after 15 minutes
sudo pmset -c sleep 30          # System sleeps after 30 minutes
sudo pmset -c disksleep 10      # Hard disk idle sleep
sudo pmset -c tcpkeepalive 1    # Keep network connections alive during standby on AC

# Deep sleep & Standby configuration (extends battery shelf-life)
# standbydelayhigh: seconds before transitioning to deep standby when battery > 50%
# standbydelaylow: seconds before transitioning when battery <= 50%
sudo pmset -a standbydelayhigh 3600
sudo pmset -a standbydelaylow 1800
sudo pmset -a hibernatemode 3   # Safe sleep: RAM contents saved to disk, RAM powered (default)

# Disable Wake-on-Network (WOMP) to prevent sleep wakeups
sudo pmset -a womp 0
```

### Keyboard, Typing & Trackpad Preferences
```bash
# Disable press-and-hold character accent menu to enable rapid key repeat
defaults write -g ApplePressAndHoldEnabled -bool false

# Set lightning-fast keyboard repeat rate (1 = fastest usable in UI, default is 2)
defaults write -g KeyRepeat -int 1

# Set shorter delay until key repeat starts (15 = 225ms, default is 30 = 450ms)
defaults write -g InitialKeyRepeat -int 15

# Enable Full Keyboard Access (navigate all UI dialogs, modals, and buttons with Tab)
defaults write -g AppleKeyboardUIMode -int 2

# Use F1, F2, etc. keys as standard function keys rather than media controls
defaults write -g com.apple.keyboard.fnState -bool true

# Disable intrusive automatic text substitution for developer terminals
defaults write -g NSAutomaticSpellingCorrectionEnabled -bool false
defaults write -g NSAutomaticCapitalizationEnabled -bool false
defaults write -g NSAutomaticPeriodSubstitutionEnabled -bool false
defaults write -g NSAutomaticQuoteSubstitutionEnabled -bool false
defaults write -g NSAutomaticDashSubstitutionEnabled -bool false

# Enable Trackpad Tap-to-Click for current user and login screen
defaults write com.apple.AppleMultitouchTrackpad Clicking -bool true
defaults write com.apple.driver.AppleBluetoothMultitouch.trackpad Clicking -bool true
defaults -currentHost write -g com.apple.mouse.tapBehavior -int 1

# Enable Three-Finger Drag gesture on Trackpad
defaults write com.apple.AppleMultitouchTrackpad TrackpadThreeFingerDrag -bool true
defaults write com.apple.driver.AppleBluetoothMultitouch.trackpad TrackpadThreeFingerDrag -bool true

# Remap Caps Lock to Escape at the kernel level without third-party tools
# Source: 0x700000039 (Caps Lock), Destination: 0x700000029 (Escape)
hidutil property --set '{"UserKeyMapping":[{"HIDKeyboardModifierMappingSrc":0x700000039,"HIDKeyboardModifierMappingDst":0x700000029}]}'

# Reset all custom hidutil key mappings back to default
hidutil property --set '{"UserKeyMapping":[]}'
```

### Finder, Dock & UI Animation Preferences
```bash
# Eliminate Dock opening delay and speed up slide-in animation
defaults write com.apple.dock autohide -bool true
defaults write com.apple.dock autohide-delay -float 0
defaults write com.apple.dock autohide-time-modifier -float 0.35
defaults write com.apple.dock show-recents -bool false
defaults write com.apple.dock tilesize -int 44
killall Dock

# Show all hidden dotfiles (.gitignore, .env, .zshrc) in Finder by default
defaults write com.apple.finder AppleShowAllFiles -bool true

# Display breadcrumb path bar and status bar at bottom of Finder windows
defaults write com.apple.finder ShowPathbar -bool true
defaults write com.apple.finder ShowStatusBar -bool true

# Search the current folder by default instead of the entire Mac
defaults write com.apple.finder FXDefaultSearchScope -string "SCcf"

# Disable file extension modification warning ("Are you sure you want to change the extension?")
defaults write com.apple.finder FXEnableExtensionChangeWarning -bool false

# Default Finder view style to List View (Nlsv) or Column View (clmv)
defaults write com.apple.finder FXPreferredViewStyle -string "Nlsv"
killall Finder

# Save screenshots to a dedicated directory instead of cluttering Desktop
mkdir -p ~/Pictures/Screenshots
defaults write com.apple.screencapture location -string "$HOME/Pictures/Screenshots"
defaults write com.apple.screencapture type -string "png"
defaults write com.apple.screencapture disable-shadow -bool true
killall SystemUIServer

# Speed up UI resize animations
defaults write -g NSWindowResizeTime -float 0.001
defaults write -g NSAutomaticWindowAnimationsEnabled -bool false
```

---

## Performance Tweaks

### Memory Management & Pressure Diagnostics
```bash
# Check memory allocation and paging statistics in real-time
vm_stat 1

# Convert vm_stat page allocations into Megabytes (Apple Silicon 16KB / Intel 4KB pages)
vm_stat | perl -ne '/page size of (\d+)/ and $size=$1; /(.*?):\s+(\d+)/ and printf "%-30s %10.2f MB\n", $1, ($2*$size)/(1024*1024);'

# Check system memory pressure state (Normal, Warn, Critical)
memory_pressure

# Check dynamic swap file usage on disk
sysctl vm.swapusage

# Inspect top 15 RAM-consuming processes sorted by resident memory (RSS)
ps aux -m | head -16

# Purge inactive memory and disk read caches (clears standby memory)
sudo purge
```

### CPU Performance Optimization & Process Scheduling
```bash
# Monitor system-wide CPU utilization, load averages, and interrupts
iostat -c 1 5

# Inspect processes consuming highest CPU in real-time
top -F -R -o cpu -n 15

# Query CPU frequency and throttling status
pmset -g therm

# Apple Silicon Quality of Service (QoS): assign background tasks to Efficiency cores
# taskpolicy utility/background prevents background builds from ramping up fans
taskpolicy -c utility bun run build
taskpolicy -b git gc --aggressive

# Renice an existing heavy process by PID to lower priority (+10) or higher priority (-10)
sudo renice -n 10 -p <PID>

# Disable Night Shift during color-critical photo/video editing or frontend work
sudo pmset -a nightshift 0
```

### Disk & Network Performance Tools
```bash
# Measure disk I/O throughput in transfers per second (tps) and MB/s
iostat -d 1 5

# Test sequential write performance to internal NVMe drive (writes 1GB file)
dd if=/dev/zero of=/tmp/disk_speed_test.bin bs=1m count=1024 conv=sync
rm -f /tmp/disk_speed_test.bin

# Test network bandwidth and latency using native macOS speed test utility
networkQuality -v

# Inspect active TCP listening sockets, local ports, and associated process IDs
lsof -iTCP -sTCP:LISTEN -P -n

# Real-time network interface packet transfer statistics
netstat -i 1

# Flush local DNS cache and restart mDNSResponder
sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder
```

### Kernel Sysctl Limits for Developers
```bash
# Check current system-wide maximum open files limit
sysctl kern.maxfiles kern.maxfilesperproc

# Increase file descriptor limit for heavy build tools (Vite, Webpack, Docker, Rust compiler)
sudo sysctl -w kern.maxfiles=524288
sudo sysctl -w kern.maxfilesperproc=262144

# Set user-level maximum open files in the current shell session
ulimit -n 65536
```

---

## Battery Life Management

### Battery Health Monitoring & Diagnostics
```bash
# Check battery health, cycle count, and maximum design capacity
system_profiler SPPowerDataType | grep -E "(Cycle Count|Condition|Maximum Capacity|Full Charge Capacity|State of Charge)"

# Detailed battery hardware status from IORegistry (amperage, temperature, voltage)
ioreg -r -c AppleSmartBattery | grep -E "(CycleCount|MaxCapacity|CurrentCapacity|IsCharging|AppleRawMaxCapacity|DesignCapacity|Temperature|InstantAmperage)"

# Quick battery status overview (percentage, charging state, remaining runtime)
pmset -g batt

# Inspect CPU, thermal, and battery discharge wattage in real-time (requires admin)
sudo powermetrics --samplers battery,cpu_power -i 2000 -n 3
```

### Low Power Mode & Power Optimization
```bash
# Enable macOS Low Power Mode via terminal (Apple Silicon & modern Intel)
sudo pmset -a lowpowermode 1   # Enable on both Battery and AC Power
sudo pmset -a lowpowermode 0   # Disable Low Power Mode

# Configure Low Power Mode to trigger ONLY when running on Battery
sudo pmset -b lowpowermode 1
sudo pmset -c lowpowermode 0

# Verify active Low Power Mode state
pmset -g | grep lowpowermode

# Lower display brightness programmatically when on battery
brightness 0.60

# Reduce keyboard backlight timeout to 30 seconds of inactivity
defaults write com.apple.keyboard.backlight idleDimTime -int 30
```

### Sleep Diagnostics & Assertions Auditing
```bash
# Inspect all active power assertions (find which app is preventing your Mac from sleeping)
pmset -g assertions

# Filter specifically for processes holding "PreventUserIdleSystemSleep" assertions
pmset -g assertions | grep -E "(PreventUserIdleSystemSleep|PreventSystemSleep|NoDisplaySleepAssertion)"

# View historical power logs: sleep events, wake causes, and dark wake entries
pmset -g log | grep -E "(Wake from|Entering Sleep|Sleep failure|DarkWake)" | tail -25

# Identify apps flagged by the system for high background energy consumption
pmset -g log | grep -i "High Energy" | tail -15
```

### Advanced Sleep Prevention with Caffeinate
```bash
# Prevent system from idle sleeping indefinitely (press Ctrl+C to cancel)
caffeinate -i

# Prevent display from sleeping while reading documentation
caffeinate -d

# Prevent system from sleeping for a specific duration (e.g. 2 hours = 7200s)
caffeinate -i -t 7200 &

# Keep system awake while a long build, test, or deployment runs
caffeinate -i -s bun run build

# Keep Mac awake until a specific process (by PID) finishes running
caffeinate -i -w 12345
```

---

## Keyboard and Input Tips

### Advanced Keyboard Shortcuts & Navigation
```bash
# Create custom application menu shortcuts programmatically via defaults
# Format: @ = Command, $ = Shift, ~ = Option, ^ = Control
defaults write -g NSUserKeyEquivalents '{
    "Save As..." = "@$s";
    "Export as PDF..." = "@$e";
    "Select All" = "@a";
}'

# Mission Control & Spaces navigation:
# Control + Up Arrow: Mission Control (all windows)
# Control + Down Arrow: Application Windows (Exposé)
# Control + Left / Right Arrow: Switch virtual desktops / fullscreen spaces

# Spotlight Search & Quick Calculations:
# Command + Space: Open Spotlight (type equations like 250*1.15 or currency conversions like 100 USD in EUR)
```

### Hardware Key Remapping via hidutil
```bash
# Remap Caps Lock (0x700000039) to Control (0x7000000E0) for developer ergonomics
hidutil property --set '{"UserKeyMapping":[{"HIDKeyboardModifierMappingSrc":0x700000039,"HIDKeyboardModifierMappingDst":0x7000000E0}]}'

# Remap Caps Lock (0x700000039) to Escape (0x700000029) for Vim / modal editing
hidutil property --set '{"UserKeyMapping":[{"HIDKeyboardModifierMappingSrc":0x700000039,"HIDKeyboardModifierMappingDst":0x700000029}]}'

# Verify currently active hidutil remappings
hidutil property --get "UserKeyMapping"

# Clear all custom key remappings
hidutil property --set '{"UserKeyMapping":[]}'
```

### Touch Bar Management (for Intel & 13" M1/M2 MacBook Pro)
```bash
# Restart the Touch Bar agent and Control Strip if it freezes or becomes unresponsive
sudo pkill "Touch Bar agent"
sudo killall "ControlStrip"

# Reset Touch Bar preferences to system factory defaults
sudo defaults delete /Library/Preferences/com.apple.touchbar.agent.plist

# Force Touch Bar to expand Control Strip by default
defaults write com.apple.touchbar.agent PresentationModeGlobal -string "full"
```

### Accessibility & Pointer Control
```bash
# Increase mouse tracking and cursor acceleration speed
defaults write -g com.apple.mouse.scaling 3.0

# Increase trackpad tracking speed (range 0.0 to 5.0)
defaults write -g com.apple.trackpad.scaling 2.5

# Enable Zoom via Control + Trackpad two-finger scroll
defaults write com.apple.universalaccess closeViewScrollWheelToggle -bool true
defaults write com.apple.universalaccess HIDScrollZoomModifierMask -int 262144
```

---

## Software Optimization

### Homebrew Package Management & Maintenance
```bash
# Update Homebrew formulae, upgrade packages, and purge obsolete versions
brew update && brew upgrade

# Clean cached downloads, old bottles, and outdated versions
brew cleanup -s

# Remove orphaned dependencies that are no longer required by any formula
brew autoremove

# Audit Homebrew installation for configuration issues and broken symlinks
brew doctor

# List top-level packages (leaves) not installed as dependencies of other tools
brew leaves

# Inspect dependency hierarchy of a specific installed formula
brew deps --tree bat

# Export all installed formulae, casks, and App Store apps to a reproducible Brewfile
brew bundle dump --file=~/.Brewfile --force

# Install or sync all tools from an existing Brewfile
brew bundle --file=~/.Brewfile

# Manage background services managed by Homebrew
brew services list
brew services restart redis
brew services stop postgresql@16
```

### Complete Application Uninstallation
```bash
# List all running GUI applications with memory consumption
ps aux | grep -E "(App|app)" | head -15

# Force-quit an unresponsive application
killall -9 "Safari"

# Completely remove an application and its associated residual data
# Replace 'TargetApp' with the actual application name:
APP_NAME="TargetApp"
BUNDLE_ID="com.example.targetapp"

rm -rf "/Applications/${APP_NAME}.app"
rm -rf "$HOME/Library/Application Support/${APP_NAME}"
rm -rf "$HOME/Library/Caches/${BUNDLE_ID}"
rm -rf "$HOME/Library/Preferences/${BUNDLE_ID}.plist"
rm -rf "$HOME/Library/Saved Application State/${BUNDLE_ID}.savedState"

# Remove quarantine attribute from downloaded applications blocked by Gatekeeper
xattr -d com.apple.quarantine /Applications/TargetApp.app
```

### Launch Agents & Daemons Management
```bash
# List user-level LaunchAgents currently loaded
launchctl list | grep -v com.apple

# Inspect system and user LaunchAgent directories
ls -la ~/Library/LaunchAgents/
ls -la /Library/LaunchAgents/
ls -la /Library/LaunchDaemons/

# Load and bootstrap a custom user service
launchctl bootstrap gui/$(id -u) ~/Library/LaunchAgents/com.user.customjob.plist

# Unload and stop an active user service
launchctl bootout gui/$(id -u)/com.user.customjob

# Check for official macOS software and firmware updates via CLI
softwareupdate --list

# Download and install all recommended security updates without restarting
softwareupdate -i -r
```

---

## Developer Tools & Terminal Commands

### Modern High-Performance CLI Toolchain
```bash
# Install modern, memory-safe CLI alternatives written in Rust and Go
brew install eza bat ripgrep fd zoxide sd rm-improved btop duf dust git-delta tldr curlie xh ouch hyperfine procs
```

| Legacy Tool | Modern Alternative | Key Advantage on macOS |
| :--- | :--- | :--- |
| `ls` | **eza** | Git integration, file icons, extended attributes, colorized output |
| `cat` | **bat** | Syntax highlighting, line numbering, git modifications, automatic paging |
| `grep` | **ripgrep (`rg`)** | Multi-threaded regex, auto-skips `.gitignore` and binary files |
| `find` | **fd** | Intuitive glob/regex patterns, ignores hidden and git directories |
| `cd` | **zoxide (`z`)** | Frecency-based directory jumping with fuzzy matching |
| `top` / `htop` | **btop** | Terminal UI with CPU, GPU, memory, disks, and network graphs |
| `df` | **duf** | Modern tabular disk usage with visual capacity bars |
| `du` | **dust** | Graphical tree view highlighting largest disk consumers |
| `diff` | **delta** | Side-by-side syntax-highlighted git diffs |
| `rm` | **rip** | Safe deletion that moves files to a recoverable graveyard |

```bash
# Everyday usage recipes for modern tools:
eza -lah --icons --git                   # List all files with icons and Git status
bat --style=numbers,changes file.py      # View file with line numbers and Git change bars
rg -i "error" src/                       # Search for regex across codebase respecting .gitignore
fd -e md                                 # Find all markdown files recursively
z myproject                              # Jump directly to nested ~/Development/Projects/myproject
btop                                     # Launch interactive system monitor
duf                                      # Quick disk usage overview
dust -d 2                                # View disk hogs in current directory
```

### Apple Silicon ARM64 vs Intel x86_64 Architecture
```bash
# Check architecture of a binary (arm64, x86_64, or Universal Mach-O)
file $(which node)
lipo -info $(which git)

# Execute an individual command under Rosetta 2 x86_64 translation
arch -x86_64 /usr/bin/uname -m

# Launch an x86_64 Zsh subshell for legacy toolchains
arch -x86_64 /bin/zsh

# Query active Command Line Tools SDK path
xcrun --show-sdk-path

# Compile native ARM64 C programs with optimal Apple Silicon flags
clang -std=c23 -O3 -arch arm64 -Wall -Wextra main.c -o main

# Run statistical micro-benchmarks with hyperfine
hyperfine --warmup 3 'bun run test' 'npm run test'
```

### Git Optimization for macOS
```bash
# Configure macOS native Keychain credential helper
git config --global credential.helper osxkeychain

# Prevent Unicode decomposition issues on APFS filesystems (e.g. accented characters)
git config --global core.precomposeUnicode true

# Enable native filesystem monitoring for instant git status on large repositories
git config --global core.fsmonitor true
git config --global core.untrackedCache true

# Configure git delta as default pager for syntax-highlighted diffs
git config --global core.pager "delta"
git config --global interactive.diffFilter "delta --color-only"
git config --global delta.navigate true
git config --global delta.light false

# Optimize repository object database and prune dead objects
git gc --aggressive --prune=now
```

---

## Productivity Hacks

### Terminal Customization & Shell Configuration (`~/.zshrc`)
```bash
# Add everyday productivity aliases to ~/.zshrc
cat << 'EOF' >> ~/.zshrc

# Modern CLI replacements
if command -v eza &>/dev/null; then
  alias ls="eza --icons"
  alias ll="eza -lah --icons --git"
  alias tree="eza --tree --icons"
fi

if command -v bat &>/dev/null; then
  alias cat="bat --paging=never"
fi

# Directory navigation
alias ..="cd .."
alias ...="cd ../.."
alias ....="cd ../../.."

# macOS Clipboard shortcuts (strip formatting, copy files)
alias c="pbcopy"
alias p="pbpaste"
alias copypath="pwd | pbcopy"

# Quick Look file inspection from terminal
ql() { qlmanage -p "$@" >/dev/null 2>&1 & }

# Quick reveal in Finder
reveal() { open -R "$1"; }

# System diagnostics aliases
alias ports="lsof -iTCP -sTCP:LISTEN -P -n"
alias memtop="ps aux -m | head -15"
alias cputop="top -F -R -o cpu -n 15"
alias flushdns="sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder; echo 'DNS flushed.'"

EOF

source ~/.zshrc
```

### macOS Clipboard & File Integration (`pbcopy` & `pbpaste`)
```bash
# Copy terminal command output directly to clipboard
git status | pbcopy

# Copy your SSH public key to clipboard without opening editors
pbcopy < ~/.ssh/id_ed25519.pub

# Paste clipboard text directly into a new file
pbpaste > snippet.txt

# Pipe clipboard content through jq or grep
pbpaste | jq .

# Open current directory in Finder
open .

# Open file or directory in Visual Studio Code
open -a "Visual Studio Code" .

# Reveal a specific file in Finder
open -R /path/to/nested/file.txt

# Inspect image, video, or PDF using Quick Look from CLI
qlmanage -p diagram.png >/dev/null 2>&1 &
```

### Workflow Automation with Apple Shortcuts & AppleScript
```bash
# List all available shortcuts in the macOS Shortcuts app
shortcuts list

# Run an Apple Shortcut workflow from terminal
shortcuts run "Optimize Images"

# Display a native macOS notification banner via AppleScript
osascript -e 'display notification "Build completed successfully!" with title "Compiler" sound name "Glass"'

# Activate and bring specific applications to front
osascript -e 'tell application "Visual Studio Code" to activate'

# Empty Trash from terminal without interactive prompts
osascript -e 'tell application "Finder" to empty trash'
```

---

## Troubleshooting and Maintenance

### System Diagnostics & Unified Logging
```bash
# Stream live system log messages with error filtering
log stream --predicate 'eventMessage contains "error"' --level error

# Inspect kernel logs over the last 30 minutes
log show --predicate 'process == "kernel"' --last 30m

# Search for crash logs and diagnostic reports
ls -la ~/Library/Logs/DiagnosticReports/

# Check system daemon errors in /var/log/system.log
tail -n 100 /var/log/system.log | grep -i error

# Check for unsigned or unverified applications
sudo spctl --status
```

### Subsystem Restarts (Fixing Glitches Without Full Reboot)
```bash
# Fix sound distortion, crackle, or missing audio devices
sudo killall coreaudiod

# Restart Bluetooth daemon if devices fail to connect
sudo pkill bluetoothd

# Restart Control Center and Status Menu bar items
killall ControlCenter SystemUIServer

# Restart Finder and Dock
killall Finder Dock

# Flush DNS resolver cache
sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder
```

### Comprehensive Maintenance & Optimization Script
Save this script as `~/bin/macos_maintenance.sh`, make it executable with `chmod +x ~/bin/macos_maintenance.sh`, and run it periodically to keep your Mac fast and clean.

```bash
#!/usr/bin/env bash
# macOS Maintenance & Performance Optimization Script
set -euo pipefail

echo "=========================================="
echo "    Starting macOS System Maintenance     "
echo "=========================================="
echo "Timestamp: $(date)"

# 1. Purge user application caches
echo "[1/7] Cleaning user application caches..."
rm -rf ~/Library/Caches/* 2>/dev/null || true
rm -rf /private/var/folders/*/*/*/*/com.apple.LaunchServices* 2>/dev/null || true

# 2. Thin APFS local snapshots to recover purgeable storage
echo "[2/7] Purging local APFS snapshots..."
if command -v tmutil &>/dev/null; then
    sudo tmutil thinlocalsnapshots / 10000000000 4 2>/dev/null || true
fi

# 3. Clean old diagnostic reports and crash logs
echo "[3/7] Cleaning diagnostic logs older than 14 days..."
find ~/Library/Logs/DiagnosticReports -type f -mtime +14 -delete 2>/dev/null || true

# 4. Rebuild LaunchServices database (fixes duplicate 'Open With' entries)
echo "[4/7] Rebuilding Launch Services registry..."
/System/Library/Frameworks/CoreServices.framework/Frameworks/LaunchServices.framework/Support/lsregister \
    -kill -r -domain local -domain system -domain user 2>/dev/null || true

# 5. Flush DNS resolver cache
echo "[5/7] Flushing DNS cache..."
sudo dscacheutil -flushcache
sudo killall -HUP mDNSResponder 2>/dev/null || true

# 6. Homebrew maintenance (if installed)
if command -v brew &>/dev/null; then
    echo "[6/7] Running Homebrew cleanup and health check..."
    brew update
    brew cleanup -s
    brew autoremove
    brew doctor || true
else
    echo "[6/7] Homebrew not detected, skipping."
fi

# 7. System health and battery overview
echo "[7/7] System Health Summary:"
echo "--- Storage ---"
df -h / | awk 'NR==1 || NR==2'
echo "--- Battery ---"
pmset -g batt
echo "--- Memory Pressure ---"
memory_pressure || true

echo "=========================================="
echo "    Maintenance Completed Successfully    "
echo "=========================================="
```

---

This comprehensive guide covers advanced macOS optimization, performance monitoring, battery preservation, developer ergonomics, and troubleshooting. Each section provides practical commands and examples tested for Apple Silicon (M-series) and Intel Macs while maintaining system stability and security.

### Best Practices Checklist:
- **Always verify commands** before running system-wide modifications.
- **Do not blindly delete system files** outside user cache directories (`~/Library/Caches`).
- **Use `taskpolicy`** for background compile tasks to keep system cool and responsive.
- **Keep Homebrew and packages updated** with `brew update && brew upgrade && brew cleanup`.
- **Run the maintenance script** monthly or after major software updates.
