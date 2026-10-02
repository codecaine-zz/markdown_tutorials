# Ubuntu Desktop & Server Tips, Performance Optimization & Bash Master Guide

A comprehensive, production-grade guide to optimizing, configuring, and maintaining Ubuntu systems (Ubuntu 22.04 LTS, 24.04 LTS, and later). Combines core Linux systems engineering, low-level hardware diagnostics, GNOME and Wayland tuning, kernel sysctl performance, power and battery management, complete package administration, the essential SS64 Bash command reference, modern Rust/Go CLI utilities, and automated system maintenance routines.

---

## Table of Contents
1. [Hardware Optimization](#hardware-optimization)
2. [System Preferences and GNOME Settings](#system-preferences-and-gnome-settings)
3. [Performance Tweaks](#performance-tweaks)
4. [Battery Life Management](#battery-life-management)
5. [Keyboard and Input Tips](#keyboard-and-input-tips)
6. [Software and Package Optimization](#software-and-package-optimization)
7. [Developer Tools and Terminal Commands](#developer-tools-and-terminal-commands)
8. [Productivity Hacks](#productivity-hacks)
9. [Troubleshooting and Maintenance](#troubleshooting-and-maintenance)

---

## Hardware Optimization

### Thermal Management & Hardware Diagnostics
```bash
# Install hardware sensors, fan diagnostic utilities, and thermal daemons
sudo apt update && sudo apt install -y lm-sensors fancontrol thermald hwinfo cpufrequtils

# Detect available temperature, voltage, and fan sensors on motherboard and CPU
sudo sensors-detect --auto

# View real-time temperature readings across CPU cores, GPU, and NVMe drives
sensors

# Watch CPU core temperatures, voltages, and thermal throttling in real-time (every 2s)
watch -n 2 "sensors | grep -E '(Core|temp|Package|fan|volt)'"

# Ensure Intel/AMD Thermal Daemon is enabled and running to manage thermals
sudo systemctl enable --now thermald
sudo systemctl status thermald

# Query comprehensive hardware architecture, CPU layout, and motherboard details
lscpu                           # CPU architecture, core counts, threads, cache sizes
sudo lshw -short                # Summary of all installed physical hardware components
sudo dmidecode -t system        # Hardware manufacturer, product model, and serial number
sudo dmidecode -t bios          # BIOS/UEFI version, vendor, and release date
lspci -nnk | grep -EA3 'VGA|3D' # Active GPU PCI devices and kernel driver in use
lsusb -tv                       # Tree view of connected USB controllers and devices
```

### Display Settings, GPU & Wayland / X11 Optimization
```bash
# Verify active display server protocol (wayland or x11)
echo $XDG_SESSION_TYPE

# List connected monitors, supported resolutions, and refresh rates on X11
xrandr --query

# Set primary display, resolution, and high refresh rate (e.g. 144Hz on DisplayPort-1)
xrandr --output DP-1 --mode 2560x1440 --rate 144.00 --primary

# Enable fractional scaling support in GNOME on Wayland (125%, 150%, 175%)
gsettings set org.gnome.mutter experimental-features "['scale-monitor-framebuffer']"

# Enable fractional scaling for X11 sessions (if running Xorg)
gsettings set org.gnome.mutter experimental-features "['x11-randr-fractional-scaling']"

# Query and adjust GNOME interface font scaling factor (ideal for 4K displays)
gsettings get org.gnome.desktop.interface text-scaling-factor
gsettings set org.gnome.desktop.interface text-scaling-factor 1.15

# Enable GNOME Night Light automatically to reduce eye strain
gsettings set org.gnome.settings-daemon.plugins.color night-light-enabled true
gsettings set org.gnome.settings-daemon.plugins.color night-light-schedule-automatic true

# Manage dual-GPU laptops (NVIDIA Optimus + Intel/AMD integrated):
prime-select query               # Check active GPU mode (nvidia, on-demand, intel)
sudo prime-select on-demand      # Use integrated GPU for desktop; offload 3D to NVIDIA
sudo prime-select nvidia         # Force discrete NVIDIA GPU at all times
nvidia-smi                       # Monitor NVIDIA VRAM, temperature, and process utilization
```

### Storage Optimization & Drive Health
```bash
# Check filesystem disk space, filesystem types, and mount points
df -hT

# List block devices with UUIDs, partition layouts, and drive types (SSD vs HDD)
lsblk -o NAME,FSTYPE,SIZE,MOUNTPOINT,UUID,MODEL,ROTA

# Run manual SSD TRIM to reclaim unallocated blocks across all fixed filesystems
sudo fstrim -av

# Verify that the systemd weekly TRIM timer is active
sudo systemctl status fstrim.timer

# Check and set the I/O scheduler for NVMe or SATA SSD drives
cat /sys/block/nvme0n1/queue/scheduler   # Typically [none] or [mq-deadline] for NVMe
echo mq-deadline | sudo tee /sys/block/sda/queue/scheduler  # For SATA SSDs

# Check SMART health status, temperature, and read/write statistics on drives
sudo apt install -y smartmontools nvme-cli
sudo smartctl -H /dev/nvme0n1             # Overall drive health assessment
sudo smartctl -a /dev/sda                # Full SMART diagnostic report for SATA drive
sudo nvme smart-log /dev/nvme0           # Real-time NVMe controller temperature and wear log

# Clean systemd journal logs older than 7 days or exceeding 500MB
sudo journalctl --vacuum-time=7d
sudo journalctl --vacuum-size=500M

# Visualize directory storage consumption using modern CLI utilities
# Install via Homebrew or Cargo: dust, duf
duf                   # Tabular breakdown of filesystems with usage progress bars
dust -d 2 ~           # Visual inverted tree showing top storage consumers
```

---

## System Preferences and GNOME Settings

### Energy & Power State Settings
```bash
# Install power-profiles-daemon for native GNOME power modes integration
sudo apt install -y power-profiles-daemon

# Check currently active power profile (performance, balanced, power-saver)
powerprofilesctl get

# Set power profile via terminal
powerprofilesctl set performance   # High compilation speed / CPU burst
powerprofilesctl set balanced      # Everyday balanced default
powerprofilesctl set power-saver   # Extended battery endurance

# Configure automatic screen blanking timeout (seconds: 300 = 5 mins, 0 = never)
gsettings set org.gnome.desktop.session idle-delay 300

# Configure automatic suspend when on battery (seconds: 1200 = 20 mins)
gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-battery-timeout 1200
gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-battery-type 'suspend'

# Configure laptop lid behavior when docked or connected to AC power
# Edit /etc/systemd/logind.conf:
sudo sed -i 's/#HandleLidSwitchExternalPower=.*/HandleLidSwitchExternalPower=ignore/' /etc/systemd/logind.conf
sudo sed -i 's/#HandleLidSwitchDocked=.*/HandleLidSwitchDocked=ignore/' /etc/systemd/logind.conf
sudo systemctl restart systemd-logind
```

### Keyboard, Typing & Trackpad Preferences
```bash
# Enable ultra-fast keyboard key repeat rate (delay in ms, repeat rate in Hz)
gsettings set org.gnome.desktop.peripherals.keyboard delay 180
gsettings set org.gnome.desktop.peripherals.keyboard repeat-interval 18

# Verify active keyboard delay and repeat rate values
gsettings get org.gnome.desktop.peripherals.keyboard delay
gsettings get org.gnome.desktop.peripherals.keyboard repeat-interval

# Swap Caps Lock with Escape (favored by Vim / Neovim developers)
gsettings set org.gnome.desktop.input-sources xkb-options "['caps:escape']"

# Or swap Caps Lock with Control (popular for Emacs / terminal multiplexers)
gsettings set org.gnome.desktop.input-sources xkb-options "['caps:ctrl_modifier']"

# Reset keyboard modifier options back to stock system default
gsettings reset org.gnome.desktop.input-sources xkb-options

# Enable Touchpad natural scrolling (content follows finger movement)
gsettings set org.gnome.desktop.peripherals.touchpad natural-scroll true

# Enable Tap-to-Click on touchpad
gsettings set org.gnome.desktop.peripherals.touchpad tap-to-click true

# Disable touchpad while typing to prevent accidental cursor jumps
gsettings set org.gnome.desktop.peripherals.touchpad disable-while-typing true

# Set mouse acceleration profile to 'flat' for 1:1 pixel precision without acceleration
gsettings set org.gnome.desktop.peripherals.mouse accel-profile 'flat'
gsettings set org.gnome.desktop.peripherals.mouse speed 0.2
```

### UI Responsiveness, Dark Mode & Ubuntu Dock
```bash
# Enable system-wide dark mode
gsettings set org.gnome.desktop.interface color-scheme 'prefer-dark'
gsettings set org.gnome.desktop.interface gtk-theme 'Yaru-dark'

# Disable GNOME desktop animations for instantaneous window rendering
gsettings set org.gnome.desktop.interface enable-animations false

# Re-enable GNOME animations if smooth transitions are desired
gsettings set org.gnome.desktop.interface enable-animations true

# Show battery percentage in the top status panel
gsettings set org.gnome.desktop.interface show-battery-percentage true

# Show weekday in the top panel clock
gsettings set org.gnome.desktop.interface clock-show-weekday true
gsettings set org.gnome.desktop.interface clock-show-seconds false

# Configure Ubuntu Dock: autohide, smaller icon size, remove trash icon
gsettings set org.gnome.shell.extensions.dash-to-dock dock-fixed false
gsettings set org.gnome.shell.extensions.dash-to-dock autohide true
gsettings set org.gnome.shell.extensions.dash-to-dock dash-max-icon-size 38
gsettings set org.gnome.shell.extensions.dash-to-dock show-trash false
gsettings set org.gnome.shell.extensions.dash-to-dock show-mounts false
```

---

## Performance Tweaks

### Memory Management, Swappiness & ZRAM
```bash
# Check current memory and swap allocation in human-readable units
free -h

# Check current swappiness (default is typically 60)
cat /proc/sys/vm/swappiness

# Set swappiness to 10 for responsive desktop systems (minimizes aggressive disk swapping)
sudo sysctl vm.swappiness=10

# Persist swappiness and VFS cache pressure across reboots
cat << 'EOF' | sudo tee /etc/sysctl.d/99-performance.conf
# Lower swappiness to keep active applications in physical RAM
vm.swappiness=10

# Retain directory and inode caches longer in RAM (default: 100)
vm.vfs_cache_pressure=50

# Ensure fair dirty page writeback
vm.dirty_background_ratio=5
vm.dirty_ratio=10
EOF

sudo sysctl -p /etc/sysctl.d/99-performance.conf

# Set up compressed in-RAM swap (ZRAM with zstd) to double effective memory
sudo apt install -y zram-tools
echo -e "ALGO=zstd\nPERCENT=50" | sudo tee /etc/default/zramswap
sudo systemctl restart zramswap
zramctl

# Flush memory caches and reclaim standby RAM safely
sync && echo 3 | sudo tee /proc/sys/vm/drop_caches

# Inspect systemd out-of-memory daemon (systemd-oomd) status
systemctl status systemd-oomd
```

### CPU Performance Optimization & Process Scheduling
```bash
# Install cpupower tools to inspect and set CPU frequency governors
sudo apt install -y linux-tools-common linux-tools-generic

# Inspect active governor, current clock speeds, and hardware limits
sudo cpupower frequency-info

# Force all CPU cores to maximum performance governor
sudo cpupower frequency-set -g performance

# Switch back to adaptive powersave / schedutil governor for balanced efficiency
sudo cpupower frequency-set -g powersave

# Monitor real-time clock speeds across all physical and logical cores
watch -n 1 "grep \"^[c]pu MHz\" /proc/cpuinfo"

# Schedule a background build with the lowest priority (nice value 19)
nice -n 19 bun run build

# Change priority of an already running process by PID (-20 highest, 19 lowest)
sudo renice -n -5 -p <PID>

# Bind a CPU-intensive command to specific processor cores with taskset
taskset -c 0,1,2,3 ./benchmark_runner
```

### Kernel Sysctl Limits for Developers & Servers
```bash
# Increase file watcher, process descriptor, and socket limits
sudo tee /etc/sysctl.d/60-developer-limits.conf << 'EOF'
# Increase max inotify file watchers (prevents ENOSPC in Vite, Webpack, Docker)
fs.inotify.max_user_watches=524288
fs.inotify.max_user_instances=1024

# System-wide file descriptor limit
fs.file-max=2097152

# Network throughput buffers and socket backlogs
net.core.somaxconn=8192
net.ipv4.tcp_max_syn_backlog=4096
net.core.netdev_max_backlog=16384
net.ipv4.ip_local_port_range=1024 65535

# Enable TCP BBR congestion control algorithm
net.core.default_qdisc=fq
net.ipv4.tcp_congestion_control=bbr
EOF

# Apply sysctl parameters immediately
sudo sysctl --system

# Configure user file limits in /etc/security/limits.conf
cat << 'EOF' | sudo tee -a /etc/security/limits.conf
* soft nofile 65536
* hard nofile 524288
* soft nproc 32768
* hard nproc 65536
EOF
```

### Network Stack & DNS Optimization
```bash
# Check status of NetworkManager and systemd-resolved
networkctl status
resolvectl status

# Flush local systemd-resolved DNS cache
sudo resolvectl flush-caches

# Query DNS response latency for a domain
resolvectl query github.com

# Inspect open network sockets, listening ports, and associated process IDs
ss -tulpn

# Display high-level summary of active TCP, UDP, and raw sockets
ss -s

# View network interface traffic statistics and packet errors
ip -s link
```

---

## Battery Life Management

### Laptop Power Management with TLP
```bash
# Install TLP and TLP-RDW for advanced laptop power management
sudo apt install -y tlp tlp-rdw

# Mask power-profiles-daemon to prevent conflicts with TLP
sudo systemctl mask power-profiles-daemon
sudo systemctl enable --now tlp

# Check full TLP power and battery status (wear level, cycles, discharge wattage)
sudo tlp-stat -s
sudo tlp-stat -b

# Force battery mode immediately for testing
sudo tlp bat

# Force AC power mode immediately
sudo tlp ac
```

### Real-Time Power Consumption Diagnostics with PowerTOP
```bash
# Install PowerTOP to measure power draw in Watts
sudo apt install -y powertop

# Calibrate PowerTOP measurement (run once on battery; takes ~4 minutes)
sudo powertop --calibrate

# Apply recommended power-saving parameters across all connected devices
sudo powertop --auto-tune

# Generate a standalone HTML report detailing top power consumers
sudo powertop --html=power-report.html
```

### Battery Charging Thresholds (Extend Battery Lifespan)
```bash
# Configure charging thresholds to stop charging at 80% (prevents cell wear):

# On Lenovo ThinkPad laptops:
echo 75 | sudo tee /sys/class/power_supply/BAT0/charge_control_start_threshold
echo 80 | sudo tee /sys/class/power_supply/BAT0/charge_control_end_threshold

# On ASUS laptops:
echo 80 | sudo tee /sys/class/power_supply/BAT0/charge_control_end_threshold

# Verify active hardware charging thresholds
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

### Multi-Touch Gestures (Wayland & X11)
```bash
# On Wayland (default on Ubuntu 22.04 / 24.04):
# - 3 fingers swipe up: Activities Overview
# - 3 fingers swipe left/right: Switch virtual workspaces
# - 4 fingers swipe left/right: Switch active applications

# On X11 sessions: install Touchegg for multi-touch gesture support
sudo add-apt-repository ppa:touchegg/stable -y
sudo apt update && sudo apt install -y touchegg
sudo systemctl enable --now touchegg
```

### Essential Desktop Keyboard Shortcuts
| Shortcut | Action |
| :--- | :--- |
| `Super` (Windows Key) | Open GNOME Activities overview / application search |
| `Super + Enter` or `Ctrl + Alt + T` | Open default Terminal |
| `Super + Left / Right Arrow` | Snap window to left or right half of the screen |
| `Super + Up Arrow` | Maximize current window |
| `Super + Down Arrow` | Restore or minimize current window |
| `Super + Shift + Left / Right` | Move window to adjacent monitor |
| `Super + PageUp / PageDown` | Switch between virtual desktop workspaces |
| `Super + L` | Lock the screen |
| `Super + D` | Minimize all windows / Show desktop |
| `Alt + Tab` | Switch between open applications |
| `Alt + ` ` (Backtick)` | Switch between windows of the current application |
| `PrintScreen` | Launch modern interactive screenshot & screen recorder |

---

## Software and Package Optimization

### APT Package Maintenance & Cache Cleaning
```bash
# Update package repositories and perform safe full upgrade
sudo apt update && sudo apt full-upgrade -y

# Clean downloaded package archives (.deb files) from /var/cache/apt/archives/
sudo apt clean

# Remove obsolete packages that can no longer be downloaded
sudo apt autoclean

# Purge orphaned dependencies and their configuration files
sudo apt autoremove --purge -y

# Find and purge residual configuration files left over from uninstalled packages
dpkg -l | grep '^rc' | awk '{print $2}' | xargs -r sudo dpkg --purge

# Search packages with descriptions
apt search <keyword>

# View detailed package metadata, dependencies, and origin repository
apt-cache show <package_name>

# Find which installed package owns a specific file or binary on disk
dpkg -S /usr/bin/curl
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
# List all installed Snaps with revisions and publishers
snap list

# Set Snap retention limit to 2 versions (frees gigabytes of storage)
sudo snap set system refresh.retain=2

# Clean disabled, old Snap package revisions
sudo bash -c 'set -eu; snap list --all | awk '\''/disabled/{print $1, $3}'\'' | while read name rev; do snap remove "$name" --revision="$rev"; done'

# If using Flatpak: remove unused runtimes and cached dependencies
flatpak uninstall --unused -y
```

### Startup Applications & Systemd Services Audit
```bash
# Analyze boot time spent in kernel vs userspace loaders
systemd-analyze

# List services taking the longest time to start during boot
systemd-analyze blame | head -15

# View the critical boot chain and timing dependencies
systemd-analyze critical-chain

# Disable unnecessary background services (examples)
sudo systemctl disable cups-browsed.service  # Network printer discovery
sudo systemctl disable bluetooth.service     # If Bluetooth hardware is not needed
sudo systemctl disable ModemManager.service  # WWAN/Cellular modem service
```

---

## Developer Tools and Terminal Commands

### Core Development Environment Setup
```bash
# Install core compilers, build essentials, headers, and monitoring tools
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
    tree \
    strace \
    ltrace \
    lsof \
    net-tools

# Install modern high-performance CLI utilities
sudo apt install -y ripgrep fd-find bat

# Alias fd-find and batcat to standard modern command names
mkdir -p ~/.local/bin
ln -sf $(which fdfind) ~/.local/bin/fd
ln -sf $(which batcat) ~/.local/bin/bat
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
```

### Modern CLI Alternatives Matrix
```bash
# Modern alternatives written in Rust and Go provide 10x speedups and rich terminal UIs
```
| Legacy Tool | Modern Alternative | Key Advantage on Ubuntu |
| :--- | :--- | :--- |
| `ls` | **eza** | Git integration, file icons, extended attributes, colorized permissions |
| `cat` | **bat** | Syntax highlighting, line numbers, git modifications, automatic pager |
| `grep` | **ripgrep (`rg`)** | Multi-threaded regex, automatically ignores `.gitignore` and binaries |
| `find` | **fd** | Intuitive syntax, regex/glob matching, skips hidden/git folders |
| `cd` | **zoxide (`z`)** | Frecency-based navigation, fuzzy jump to deeply nested directories |
| `top` / `htop` | **btop** | Terminal UI with CPU, GPU, memory, disk I/O, and network graphs |
| `df` | **duf** | Modern tabular layout showing mount points, usage bars, device types |
| `du` | **dust** | Graphical tree view highlighting largest disk consumers |
| `diff` | **delta** | Side-by-side syntax-highlighted git diffs |
| `rm` | **rip** | Safe deletion that moves files to a recoverable graveyard |

```bash
# Everyday modern tool usage recipes:
eza -lah --icons --git                 # List files with icons and git status
bat --style=numbers,changes server.js  # Read file with syntax highlighting
rg -i "error" /var/log/                # Search across logs instantly
fd -e py                               # Find all Python files recursively
z myproject                            # Jump instantly to ~/workspace/myproject
btop                                   # Launch interactive system monitor
```

### SS64 Core Linux & Bash Command Reference
A curated guide to foundational POSIX commands with production-grade examples:

#### 1. Text Processing & Stream Editing (`awk`, `sed`, `grep`, `cut`, `sort`, `uniq`, `tr`, `xargs`)
```bash
# awk: Extract specific columns (e.g. print usernames and shell from /etc/passwd)
awk -F: '{print $1, $7}' /etc/passwd

# awk: Calculate sum of numbers in the second column
awk '{sum += $2} END {print "Total:", sum}' data.txt

# sed: Stream editor find and replace text in-place across files
sed -i 's/OLD_HOST/NEW_HOST/g' config.yaml

# sed: Delete empty lines or commented lines
sed -i '/^$/d; /^[[:space:]]*#/d' config.ini

# grep: Case-insensitive recursive search with line numbers
grep -rnI --exclude-dir={.git,node_modules} "API_KEY" .

# cut & sort & uniq: Count unique IP addresses accessing Nginx web server
cat /var/log/nginx/access.log | cut -d ' ' -f 1 | sort | uniq -c | sort -nr | head -10

# tr: Translate characters (e.g. uppercase to lowercase, or replace newlines with commas)
cat names.txt | tr '[:upper:]' '[:lower:]'
cat list.txt | tr '\n' ','

# xargs: Run commands in parallel using all CPU cores
find . -type f -name "*.png" | xargs -P $(nproc) -I {} optipng {}
```

#### 2. File Ownership, Permissions & Security (`chmod`, `chown`, `chattr`, `setfacl`, `umask`)
```bash
# Standard permissions: 755 for directories, 644 for files (recursively applied safely)
find /var/www/html -type d -exec chmod 755 {} +
find /var/www/html -type f -exec chmod 644 {} +

# Change user and group ownership recursively
sudo chown -R www-data:www-data /var/www/html

# Special bits: SGID (files created inherit parent directory group)
chmod g+s /shared/development/

# Immutable file attribute: prevent file from being modified, deleted, or renamed (even by root!)
sudo chattr +i /etc/resolv.conf
lsattr /etc/resolv.conf
sudo chattr -i /etc/resolv.conf    # Remove immutable flag

# Access Control Lists (ACLs): grant granular access to a specific user without group changes
sudo setfacl -m u:developer:rwx /var/log/custom.log
getfacl /var/log/custom.log
```

#### 3. Process Management & Job Control (`ps`, `kill`, `pkill`, `fuser`, `lsof`, `nohup`, `disown`)
```bash
# List top 10 memory-consuming processes
ps aux --sort=-%mem | head -11

# List top 10 CPU-consuming processes
ps aux --sort=-%cpu | head -11

# Find process ID (PID) by binary name
pgrep -l nginx

# Gracefully terminate or force-kill processes by name
pkill -15 -f "python worker.py"    # SIGTERM (graceful)
pkill -9 -f "stuck_worker"         # SIGKILL (force)

# Find and kill the process holding a specific TCP port (e.g. 8080)
fuser -k 8080/tcp

# List all open files and active network connections for a specific PID
lsof -p 1234

# List which process is using a specific mounted directory or USB drive
lsof +D /media/usb/

# Run long process detached from terminal session (survives SSH disconnect)
nohup python3 long_train.py > train.log 2>&1 &

# Disown a running background job from current shell
disown -h %1
```

#### 4. System Tracing & Diagnostics (`strace`, `ltrace`, `perf`)
```bash
# Trace all system calls made by a running process (inspect file opens and network activity)
sudo strace -p <PID> -e trace=openat,read,write,connect

# Trace dynamic library calls made by a binary
ltrace -c ls

# Profile CPU cycles, instructions per cycle (IPC), and cache misses
perf stat -d ls -laR /usr/include >/dev/null
```

### Git Optimization
```bash
# Enable parallel index preloading for instantaneous status on massive repositories
git config --global core.preloadindex true

# Enable commit-graph and file system monitoring
git config --global core.commitGraph true
git config --global gc.writeCommitGraph true
git config --global core.fsmonitor true
git config --global core.untrackedCache true

# Cache Git credentials in secure memory for 8 hours (28800s)
git config --global credential.helper 'cache --timeout=28800'

# Aggressive garbage collection and object database pruning
git gc --aggressive --prune=now
```

---

## Productivity Hacks

### Terminal Aliases & Shell Customization (`~/.bashrc` & `~/.zshrc`)
```bash
# Add everyday productivity aliases to ~/.bashrc or ~/.zshrc
cat << 'EOF' >> ~/.bashrc

# Navigation
alias ..="cd .."
alias ...="cd ../.."
alias ....="cd ../../.."

# Modern listing replacements
if command -v eza &>/dev/null; then
  alias ls="eza --icons"
  alias ll="eza -lah --icons --git"
  alias tree="eza --tree --icons"
else
  alias ll="ls -lah --color=auto --group-directories-first"
fi

if command -v bat &>/dev/null; then
  alias cat="bat --paging=never"
fi

# System maintenance shortcuts
alias update="sudo apt update && sudo apt full-upgrade -y"
alias cleanup="sudo apt autoremove --purge -y && sudo apt clean"

# Developer shortcuts
alias gs="git status -sb"
alias gl="git log --oneline --graph --decorate -n 15"
alias ports="ss -tulpn | grep LISTEN"
alias myip="curl -s ifconfig.me && echo"

# Memory & CPU inspection
alias memtop="ps aux --sort=-%mem | head -11"
alias cputop="ps aux --sort=-%cpu | head -11"

# Universal Clipboard Aliases (Wayland & X11 compatible)
if [ "$XDG_SESSION_TYPE" = "wayland" ] && command -v wl-copy &>/dev/null; then
  alias c="wl-copy"
  alias p="wl-paste"
elif command -v xclip &>/dev/null; then
  alias c="xclip -selection clipboard"
  alias p="xclip -selection clipboard -o"
fi

# Bash history deduplication and size expansion
export HISTCONTROL=ignoreboth:erasedups
export HISTSIZE=100000
export HISTFILESIZE=200000
shopt -s histappend

EOF

source ~/.bashrc
```

### Universal Clipboard Integration
```bash
# Install clipboard tools for Wayland and X11
sudo apt install -y wl-clipboard xclip

# Copy terminal output to clipboard
git status | c

# Copy public SSH key directly to clipboard
c < ~/.ssh/id_ed25519.pub

# Paste clipboard content directly to file or filter
p > snippet.txt
p | jq .
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

# rsync with archive mode, ACLs, extended attributes, and exclusion patterns
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

# Resolve APT database lock issues if an apt process crashed
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

### UFW Firewall Management
```bash
# Check UFW firewall status and active rules
sudo ufw status verbose

# Enable firewall with default deny incoming and allow outgoing
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp      # Allow SSH
sudo ufw allow 80/tcp      # Allow HTTP
sudo ufw allow 443/tcp     # Allow HTTPS
sudo ufw enable
```

### Subsystem Restarts Without Full Reboot
```bash
# Restart NetworkManager daemon if Wi-Fi or Ethernet drops
sudo systemctl restart NetworkManager

# Restart PipeWire and PulseAudio sound subsystems (fixes distorted or missing audio)
systemctl --user restart pipewire pipewire-pulse wireplumber

# Restart GNOME Display Manager (restarts desktop session; saves unsaved work first!)
sudo systemctl restart gdm3
```

### System Log Diagnostics
```bash
# View priority errors in current boot journal (levels: emerg, alert, crit, err)
journalctl -p 3 -xb

# Stream live system log messages with timestamp
journalctl -f

# Check specific systemd service logs (e.g. docker or nginx)
journalctl -u docker.service -n 50 --no-pager

# Check kernel ring buffer for hardware/driver crash messages
sudo dmesg -T --level=err,warn | tail -n 25
```

### Master System Health & Cleanup Script
Save this script as `~/bin/ubuntu_maintenance.sh`, make it executable with `chmod +x ~/bin/ubuntu_maintenance.sh`, and run it periodically to keep Ubuntu clean, fast, and secure.

```bash
#!/usr/bin/env bash
# ubuntu_maintenance.sh - Automated Ubuntu System Maintenance
set -euo pipefail

echo "=========================================="
echo "    Starting Ubuntu System Maintenance    "
echo "=========================================="
echo "Timestamp: $(date)"

# 1. Clean APT package cache and remove orphaned packages
echo "[1/7] Cleaning APT package cache..."
sudo apt update
sudo apt clean
sudo apt autoclean
sudo apt autoremove --purge -y

# 2. Vacuum systemd journal logs to 200MB
echo "[2/7] Vacuuming systemd journal logs..."
sudo journalctl --vacuum-size=200M

# 3. Run SSD TRIM
echo "[3/7] Trimming SSD storage..."
sudo fstrim -av

# 4. Clear user thumbnail caches
echo "[4/7] Clearing thumbnail caches..."
rm -rf ~/.cache/thumbnails/* 2>/dev/null || true

# 5. Purge disabled Snap revisions (if snapd installed)
if command -v snap &>/dev/null; then
    echo "[5/7] Cleaning disabled Snap revisions..."
    sudo bash -c 'snap list --all 2>/dev/null | awk '\''/disabled/{print $1, $3}'\'' | while read name rev; do snap remove "$name" --revision="$rev" 2>/dev/null || true; done' || true
fi

# 6. Clean unused Flatpak runtimes (if flatpak installed)
if command -v flatpak &>/dev/null; then
    echo "[6/7] Removing unused Flatpak runtimes..."
    flatpak uninstall --unused -y 2>/dev/null || true
fi

# 7. System health summary
echo "[7/7] System Health Summary:"
echo "--- Storage ---"
df -hT / | awk 'NR==1 || NR==2'
echo "--- Memory & Swap ---"
free -h
echo "--- Failed Systemd Units ---"
systemctl --failed --quiet || true

echo "=========================================="
echo "    Maintenance Completed Successfully    "
echo "=========================================="
```

---

### Best Practices Summary
- **Keep backups current**: Maintain off-disk backups before editing `/etc/sysctl.d/` or installing kernel/driver updates.
- **Prefer native packages**: Use APT or official PPA/deb repositories for performance-critical dev tools and Docker.
- **Inspect boot times**: Run `systemd-analyze blame` after adding new services to prevent startup bottlenecks.
- **Tune inotify watchers**: Prevent `ENOSPC` errors during web development by maintaining elevated `fs.inotify.max_user_watches`.
- **Run the maintenance script**: Execute `ubuntu_maintenance.sh` monthly or following major package upgrades.
