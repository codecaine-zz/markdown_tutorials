## Table of Contents
1. [Hardware Optimization](#hardware-optimization)
2. [System Preferences & GNOME Settings](#system-preferences--gnome-settings)
3. [Performance Tweaks](#performance-tweaks)
4. [Battery Life Management](#battery-life-management)
5. [Keyboard and Input Tips](#keyboard-and-input-tips)
6. [Software & Package Optimization](#software--package-optimization)
7. [Developer Tools & Terminal Commands](#developer-tools--terminal-commands)
8. [Productivity Hacks](#productivity-hacks)
9. [Troubleshooting and Maintenance](#troubleshooting-and-maintenance)

---

## Hardware Optimization

### Thermal Management
```bash
# Install hardware temperature sensors and fan diagnostic utilities
sudo apt update && sudo apt install -y lm-sensors fancontrol thermald

# Detect available sensors on motherboard and CPU
sudo sensors-detect --auto

# View real-time temperature readings for CPU cores, GPU, and NVMe drives
sensors

# Watch CPU frequencies and temperatures update every 2 seconds
watch -n 2 "sensors | grep -E '(Core|temp|Package)'"

# Ensure Intel/AMD Thermal Daemon is enabled and running
sudo systemctl enable --now thermald
sudo systemctl status thermald
```

### Display Settings Optimization
```bash
# Check current display server in use (Wayland or X11)
echo $XDG_SESSION_TYPE

# List connected monitors, resolutions, and supported refresh rates (X11)
xrandr --query

# Set display resolution and max refresh rate (e.g., 144Hz on DP-1)
xrandr --output DP-1 --mode 2560x1440 --rate 144.00 --primary

# Enable fractional scaling support in GNOME on Wayland (125%, 150%, 175%)
gsettings set org.gnome.mutter experimental-features "['scale-monitor-framebuffer']"

# Enable fractional scaling for X11 sessions (if using Xorg)
gsettings set org.gnome.mutter experimental-features "['x11-randr-fractional-scaling']"

# Query active display properties via GNOME Mutter
gsettings get org.gnome.desktop.interface text-scaling-factor
```

### Storage Optimization
```bash
# Check filesystem disk space and inode consumption
df -hT

# Run manual SSD TRIM to reclaim unallocated blocks
sudo fstrim -av

# Verify that the systemd weekly TRIM timer is active
sudo systemctl status fstrim.timer

# Check current I/O scheduler for NVMe or SATA SSDs
cat /sys/block/nvme0n1/queue/scheduler
cat /sys/block/sda/queue/scheduler

# Check SMART health status of primary drive (requires smartmontools)
sudo apt install -y smartmontools
sudo smartctl -H /dev/nvme0n1

# Clean systemd journal logs older than 7 days or larger than 500MB
sudo journalctl --vacuum-time=7d
sudo journalctl --vacuum-size=500M
```

---

## System Preferences & GNOME Settings

### Energy & Power State Settings
```bash
# Install power-profiles-daemon for GNOME integrated power modes
sudo apt install -y power-profiles-daemon

# Check currently active power profile (performance, balanced, power-saver)
powerprofilesctl get

# Set power profile via terminal
powerprofilesctl set performance   # For heavy compilation / dev work
powerprofilesctl set balanced      # Standard default
powerprofilesctl set power-saver   # Maximize battery life

# Configure automatic screen blanking timeout (in seconds: 300 = 5 mins, 0 = never)
gsettings set org.gnome.desktop.session idle-delay 300

# Configure automatic suspend when on battery (in seconds: 1200 = 20 mins)
gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-battery-timeout 1200
gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-battery-type 'suspend'
```

### Keyboard & Typing Preferences
```bash
# Enable ultra-fast keyboard key repeat rate (delay in ms, repeat rate in Hz)
gsettings set org.gnome.desktop.peripherals.keyboard delay 200
gsettings set org.gnome.desktop.peripherals.keyboard repeat-interval 20

# Check active keyboard configuration
gsettings get org.gnome.desktop.peripherals.keyboard delay
gsettings get org.gnome.desktop.peripherals.keyboard repeat-interval

# Swap Caps Lock with Escape (popular for Vim developers)
gsettings set org.gnome.desktop.input-sources xkb-options "['caps:escape']"

# Or swap Caps Lock with Control
gsettings set org.gnome.desktop.input-sources xkb-options "['caps:ctrl_modifier']"

# Reset keyboard options back to system default
gsettings reset org.gnome.desktop.input-sources xkb-options
```

### UI, Animations & Dark Mode Preferences
```bash
# Enable system-wide dark mode
gsettings set org.gnome.desktop.interface color-scheme 'prefer-dark'
gsettings set org.gnome.desktop.interface gtk-theme 'Yaru-dark'

# Disable GNOME animations for instantaneous window opening and navigation
gsettings set org.gnome.desktop.interface enable-animations false

# Re-enable GNOME animations
gsettings set org.gnome.desktop.interface enable-animations true

# Show battery percentage in the top status bar
gsettings set org.gnome.desktop.interface show-battery-percentage true

# Show weekday and seconds in the top panel clock
gsettings set org.gnome.desktop.interface clock-show-weekday true
gsettings set org.gnome.desktop.interface clock-show-seconds false
```

---

## Performance Tweaks

### Memory Management & Swappiness
```bash
# Check current memory and swap allocation in megabytes
free -h

# Check current swappiness (default is typically 60)
cat /proc/sys/vm/swappiness

# Set swappiness to 10 for desktop responsiveness (avoids aggressive disk swapping)
sudo sysctl vm.swappiness=10

# Persist swappiness setting across reboots
echo "vm.swappiness=10" | sudo tee -a /etc/sysctl.d/99-performance.conf

# Adjust inode cache pressure (default: 100; lower values retain directory cache in RAM)
echo "vm.vfs_cache_pressure=50" | sudo tee -a /etc/sysctl.d/99-performance.conf
sudo sysctl -p /etc/sysctl.d/99-performance.conf

# Set up compressed in-RAM swap (ZRAM) for faster multitasking
sudo apt install -y zram-tools
echo -e "ALGO=zstd\nPERCENT=50" | sudo tee /etc/default/zramswap
sudo systemctl restart zramswap
zramctl
```

### CPU Performance Optimization
```bash
# Install cpufrequtils to inspect and control CPU scaling governors
sudo apt install -y cpufrequtils linux-tools-common linux-tools-generic

# Check available CPU governors (e.g. performance, powersave, ondemand, schedutil)
cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_available_governors

# View active governor across all CPU cores
cat /sys/devices/system/cpu/cpu*/cpufreq/scaling_governor | sort | uniq -c

# Force all cores to performance governor (instant maximum clock speeds)
sudo cpupower frequency-set -g performance

# Switch back to adaptive powersave / schedutil governor
sudo cpupower frequency-set -g powersave

# Monitor real-time core clock speeds
watch -n 1 "grep \"^[c]pu MHz\" /proc/cpuinfo"
```

### Kernel Sysctl Optimizations for Developers
```bash
# Increase file watcher limits (essential for Webpack, Vite, IDEs, and Docker)
sudo tee /etc/sysctl.d/60-developer-limits.conf << 'EOF'
# Increase max inotify file watchers
fs.inotify.max_user_watches=524288
fs.inotify.max_user_instances=1024

# Increase file descriptor limit
fs.file-max=2097152

# Network throughput buffers
net.core.somaxconn=4096
net.ipv4.tcp_max_syn_backlog=4096
EOF

# Apply sysctl parameters immediately
sudo sysctl --system
```

---

## Battery Life Management

### Laptop Power Management with TLP
```bash
# Install TLP and TLP-RDW for advanced power management
sudo apt install -y tlp tlp-rdw

# Mask standard power-profiles-daemon to prevent conflicts with TLP
sudo systemctl mask power-profiles-daemon
sudo systemctl enable --now tlp

# Check full TLP power and battery status
sudo tlp-stat -s
sudo tlp-stat -b   # Detailed battery info, wear level, cycles

# Start TLP in battery mode immediately (for testing)
sudo tlp bat

# Start TLP in AC power mode immediately
sudo tlp ac
```

### Real-Time Power Consumption Diagnostics
```bash
# Install PowerTOP to measure power draw in Watts
sudo apt install -y powertop

# Calibrate PowerTOP measurement (run once on battery; takes ~4 minutes)
sudo powertop --calibrate

# Run PowerTOP auto-tune to apply recommended power-saving parameters to devices
sudo powertop --auto-tune

# Generate HTML report of current power consumers
sudo powertop --html=power-report.html
```

### Battery Charging Thresholds (Extend Battery Lifespan)
```bash
# On ThinkPad laptops (stops charging at 80% to prevent degradation)
echo 75 | sudo tee /sys/class/power_supply/BAT0/charge_control_start_threshold
echo 80 | sudo tee /sys/class/power_supply/BAT0/charge_control_end_threshold

# On ASUS laptops
echo 80 | sudo tee /sys/class/power_supply/BAT0/charge_control_end_threshold

# Verify configured thresholds
cat /sys/class/power_supply/BAT*/charge_control_*
```

### Prevent Sleep During Long Builds or Background Tasks
```bash
# Inhibit system sleep while running a command (similar to macOS caffeinate)
systemd-inhibit --what=idle:sleep:shutdown --who="Developer" --why="Compiling Project" bun run build

# Inhibit sleep indefinitely until Ctrl+C is pressed
systemd-inhibit --what=sleep sleep infinity
```

---

## Keyboard and Input Tips

### Touchpad & Mouse Tuning
```bash
# Enable natural scrolling (touchpad moves content in finger direction)
gsettings set org.gnome.desktop.peripherals.touchpad natural-scroll true

# Enable tap-to-click on touchpad
gsettings set org.gnome.desktop.peripherals.touchpad tap-to-click true

# Configure touchpad two-finger right click and three-finger middle click
gsettings set org.gnome.desktop.peripherals.touchpad click-method 'fingers'

# Set mouse acceleration profile ('flat' disables acceleration for precision)
gsettings set org.gnome.desktop.peripherals.mouse accel-profile 'flat'
gsettings set org.gnome.desktop.peripherals.mouse speed 0.2
```

### Multi-Touch Gestures (Wayland & X11)
```bash
# On Wayland (default on Ubuntu 22.04 / 24.04):
# - 3 fingers swipe up: Activities Overview
# - 3 fingers swipe left/right: Switch workspaces
# - 4 fingers swipe left/right: Switch applications

# On X11 sessions: install Touchegg for multi-touch gesture support
sudo add-apt-repository ppa:touchegg/stable -y
sudo apt update && sudo apt install -y touchegg
sudo systemctl enable --now touchegg
```

---

## Software & Package Optimization

### APT Package Maintenance & Cache Cleaning
```bash
# Update package lists and upgrade all system packages
sudo apt update && sudo apt full-upgrade -y

# Remove downloaded .deb archives from /var/cache/apt/archives/
sudo apt clean

# Remove packages that can no longer be downloaded
sudo apt autoclean

# Purge unused orphaned dependencies and their configuration files
sudo apt autoremove --purge -y

# Find and purge residual configuration files left over from uninstalled packages
dpkg -l | grep '^rc' | awk '{print $2}' | xargs -r sudo dpkg --purge
```

### PPA Repository Auditing & Management
```bash
# Install ppa-purge to safely roll back PPAs to standard Ubuntu repositories
sudo apt install -y ppa-purge

# Safely revert a PPA and downgrade its packages to stock Ubuntu versions
sudo ppa-purge ppa:example/ppa-name

# List all configured third-party APT repositories
ls -la /etc/apt/sources.list.d/
```

### Snap & Flatpak Maintenance
```bash
# List installed Snaps and revision numbers
snap list

# Set Snap retention limit to 2 versions (frees gigabytes of disk space)
sudo snap set system refresh.retain=2

# Clean old disabled Snap package revisions
sudo bash -c 'set -eu; snap list --all | awk '\''/disabled/{print $1, $3}'\'' | while read name rev; do snap remove "$name" --revision="$rev"; done'

# If using Flatpak: remove unused runtimes and dependencies
flatpak uninstall --unused -y
```

### Startup Applications & Systemd Services Audit
```bash
# Analyze boot time spent in kernel vs userspace
systemd-analyze

# List services taking the longest time to start during boot
systemd-analyze blame | head -15

# View the critical boot chain
systemd-analyze critical-chain

# Disable unnecessary background services (examples)
sudo systemctl disable cups-browsed.service  # Network printer browsing
sudo systemctl disable bluetooth.service     # If not using Bluetooth
```

---

## Developer Tools & Terminal Commands

### Core Development Environment Setup
```bash
# Install essential compilers, headers, and version control
sudo apt update && sudo apt install -y \
    build-essential \
    curl \
    wget \
    git \
    pkg-config \
    libssl-dev \
    zlib1g-dev \
    htop \
    btop \
    jq \
    tree

# Install modern high-performance CLI utilities
sudo apt install -y ripgrep fd-find bat

# Alias fd-find and batcat to standard modern names
mkdir -p ~/.local/bin
ln -sf $(which fdfind) ~/.local/bin/fd
ln -sf $(which batcat) ~/.local/bin/bat
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
```

### System & Network Monitoring for Developers
```bash
# Real-time resource monitor with btop
btop

# Monitor per-process disk I/O usage (requires root)
sudo iotop -o -P

# Inspect active network sockets, ports, and listening processes
ss -tulpn

# Trace system calls of a running process (e.g. PID 1234)
sudo strace -p 1234 -e trace=openat,read,write,network

# Measure page faults, branch mispredictions, and CPU cycles
perf stat ls -la
```

### Git Optimization
```bash
# Enable parallel index preload for instant status on huge repos
git config --global core.preloadindex true

# Enable commit-graph and file system monitoring
git config --global core.commitGraph true
git config --global gc.writeCommitGraph true

# Cache credentials in secure memory for 8 hours (28800s)
git config --global credential.helper 'cache --timeout=28800'

# Optimize garbage collection
git gc --aggressive --prune=now
```

---

## Productivity Hacks

### Terminal Aliases & Shell Customization
```bash
# Add everyday productivity aliases to ~/.bashrc or ~/.zshrc
cat << 'EOF' >> ~/.bashrc

# Navigation and Listing
alias ..="cd .."
alias ...="cd ../.."
alias ll="ls -lah --color=auto --group-directories-first"

# System Maintenance
alias update="sudo apt update && sudo apt upgrade -y"
alias cleanup="sudo apt autoremove --purge -y && sudo apt clean"

# Developer shortcuts
alias gs="git status -sb"
alias gl="git log --oneline --graph --decorate -n 15"
alias ports="ss -tulpn | grep LISTEN"

# Fast IP lookup
alias myip="curl -s ifconfig.me && echo"

# Memory and CPU quick check
alias memtop="ps aux --sort=-%mem | head -11"
alias cputop="ps aux --sort=-%cpu | head -11"
EOF

source ~/.bashrc
```

### Automated Backup Script with Rsync
```bash
# Create local automated backup script at ~/.local/bin/ubuntu-backup.sh
cat << 'EOF' > ~/.local/bin/ubuntu-backup.sh
#!/usr/bin/env bash
set -euo pipefail

BACKUP_DEST="${1:-/mnt/backup/home_backup}"
LOG_FILE="/tmp/ubuntu_backup.log"

echo "=== Backup started at $(date) ===" | tee -a "$LOG_FILE"
mkdir -p "$BACKUP_DEST"

rsync -aAXvh --delete \
    --exclude='.cache' \
    --exclude='node_modules' \
    --exclude='.local/share/Trash' \
    --exclude='*.iso' \
    "$HOME/" "$BACKUP_DEST/" 2>&1 | tee -a "$LOG_FILE"

echo "=== Backup completed at $(date) ===" | tee -a "$LOG_FILE"
EOF

chmod +x ~/.local/bin/ubuntu-backup.sh
```

---

## Troubleshooting and Maintenance

### Fixing Broken Packages & APT Lockups
```bash
# Configure any partially configured packages
sudo dpkg --configure -a

# Fix broken dependencies
sudo apt install -f

# Kill stuck apt/dpkg processes if locks are held
sudo killall apt apt-get dpkg 2>/dev/null || true
sudo rm -f /var/lib/apt/lists/lock
sudo rm -f /var/cache/apt/archives/lock
sudo rm -f /var/lib/dpkg/lock*
sudo dpkg --configure -a
```

### Graphics Drivers & Display Troubleshooting
```bash
# Detect recommended proprietary or open-source graphics drivers
ubuntu-drivers devices

# Install recommended proprietary driver automatically
sudo ubuntu-drivers autoinstall

# Verify NVIDIA driver status (if running NVIDIA GPU)
nvidia-smi

# Check kernel graphics driver currently in use
lspci -k | grep -EA3 'VGA|3D'
```

### System Log Diagnostics
```bash
# View priority errors in current boot journal (levels: emerg, alert, crit, err)
journalctl -p 3 -xb

# Stream live system log messages with timestamp
journalctl -f

# Check kernel ring buffer for hardware/driver crash messages
sudo dmesg -T --level=err,warn | tail -n 25
```

### One-Click System Health & Cleanup Script
```bash
#!/usr/bin/env bash
# ubuntu-maintenance.sh - Run periodically or during maintenance windows
set -euo pipefail

echo "==> Cleaning APT package cache..."
sudo apt clean
sudo apt autoclean
sudo apt autoremove --purge -y

echo "==> Vacuuming systemd journal logs to 200M..."
sudo journalctl --vacuum-size=200M

echo "==> Trimming SSD storage..."
sudo fstrim -av

echo "==> Clearing old thumbnail caches..."
rm -rf ~/.cache/thumbnails/*

echo "==> System maintenance complete!"
```

---

### Best Practices Summary
- **Keep backups current**: Maintain off-disk backups before editing `/etc/sysctl.d/` or installing kernel/driver updates.
- **Prefer native packages**: Use APT or official PPA/deb repositories for performance-critical dev tools and Docker.
- **Inspect boot times**: Run `systemd-analyze blame` after adding new services to prevent startup bottlenecks.
