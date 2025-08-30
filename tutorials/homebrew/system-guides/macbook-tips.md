## Table of Contents
1. [Hardware Optimization](#hardware-optimization)
2. [System Preferences](#system-preferences)
3. [Performance Tweaks](#performance-tweaks)
4. [Battery Life Management](#battery-life-management)
5. [Keyboard and Input Tips](#keyboard-and-input-tips)
6. [Software Optimization](#software-optimization)
7. [Developer Tools & Terminal Commands](#developer-tools--terminal-commands)
8. [Productivity Hacks](#productivity-hacks)
9. [Troubleshooting Solutions](#troubleshooting-solutions)

## Hardware Optimization

### Thermal Management
```bash
# Check temperature sensors (requires admin privileges)
sudo powermetrics --samplers smc -i 1

# Monitor CPU and GPU temperatures
osx-cpu-temp
```

### Display Settings Optimization
```bash
# Adjust display brightness programmatically
brightness 0.8  # Set to 80%

# Check current screen resolution
system_profiler SPDisplaysDataType | grep Resolution

# Enable or disable automatic graphics switching
sudo pmset -a gpuswitch 1  # Force discrete GPU (if needed)
```

### Storage Optimization
```bash
# Check storage usage
df -h

# Clean up system caches
sudo rm -rf ~/Library/Caches/*
sudo rm -rf /private/var/folders/*/

# Optimize spotlight indexing
sudo mdutil -a -i off  # Disable Spotlight temporarily (use with caution)
sudo mdutil -a -i on   # Re-enable Spotlight
```

## System Preferences

### Energy Saver Settings
```bash
# Configure energy saver settings via terminal
defaults write com.apple.PowerManagement "Power State" -dict-add "Battery Power" -dict \
    "Display Sleep Timer" 10 \
    "System Sleep Timer" 30 \
    "Processor Speed" 1

# Set automatic graphics switching
sudo pmset -a gpuswitch 2  # Automatic switching (default)
```

### Keyboard Settings
```bash
# Enable key repeat for faster typing
defaults write -g ApplePressAndHoldEnabled -bool false

# Adjust keyboard repeat rate (0-3, where 3 is fastest)
defaults write -g KeyRepeat -int 1

# Set delay until repeat (0-2, where 2 is longest)
defaults write -g InitialKeyRepeat -int 15
```

### Display Preferences
```bash
# Enable hires display mode
sudo defaults write /Library/Preferences/com.apple.windowserver.plist DisplayResolutionEnabled -bool YES

# Adjust screen refresh rate for external displays
system_profiler SPDisplaysDataType | grep "Display Type"
```

## Performance Tweaks

### Memory Management
```bash
# Monitor memory usage
vm_stat 1

# Force memory clean (be careful!)
sudo purge

# Check active processes consuming RAM
top -l 1 | head -20

# Adjust swap file size for better performance
sudo sysctl vm.swapusage
```

### CPU Performance Optimization
```bash
# Monitor CPU usage in real-time
iostat -c 1

# View detailed system information including processor info
sysctl hw.model hw.ncpu machdep.cpu.brand_string

# Enable/disable energy saving mode for development work
sudo pmset -a nightshift 0  # Disable Night Shift when developing
```

### Disk Performance Tools
```bash
# Check disk I/O performance
iostat -d 1 5

# Monitor network activity
netstat -i

# Use Activity Monitor via command line (if needed)
sudo opensnoop-by-uid $(id -u)
```

## Battery Life Management

### Battery Health Monitoring
```bash
# Check battery status and cycle count
system_profiler SPPowerDataType | grep "Cycle Count"

# View detailed battery information
pmset -g batt

# Monitor power consumption over time (requires admin)
sudo powermetrics --samplers cpu,thermal,battery -i 2 -n 5
```

### Battery Optimization Commands
```bash
# Reduce display brightness to preserve battery
brightness 0.6

# Disable unnecessary background processes for better battery life
defaults write com.apple.ActivityMonitor OpenMainWindow -bool false

# Set automatic sleep timer (in minutes)
sudo pmset -a sleep 10

# Disable keyboard backlight when not needed
sudo defaults write /Library/Preferences/com.apple.iokit.BridgeOSUserPreferences.plist KeyboardBacklightOn -bool NO
```

### Advanced Battery Management
```bash
# Check if battery is calibrated properly
system_profiler SPPowerDataType | grep "Battery Capacity"

# Reset SMC (for better power management)
sudo pmset -a smsc 1

# Monitor for high energy usage apps
sudo pmset -g log | grep "High Energy Usage"
```

## Keyboard and Input Tips

### Advanced Keyboard Shortcuts
```bash
# Create custom keyboard shortcuts programmatically
defaults write -g NSUserKeyEquivalents '{
    "Save As..." = "@$s";
    "Open..." = "@o";
}'
```

### Touch Bar Customization
```bash
# Reset touch bar to default (if needed)
sudo defaults delete /Library/Preferences/com.apple.touchbar.agent.plist

# Show or hide the Touch Bar in applications
defaults write com.apple.touchbar.agent PresentationModeGlobal -string "full"
```

### Accessibility Features
```bash
# Enable keyboard navigation for accessibility
defaults write com.apple.universalaccess closeViewScrollWheelToggle -bool true

# Enable voice control (if enabled)
defaults write com.apple.VoiceControlClient VoiceControlEnabled -bool YES

# Adjust cursor speed and tracking
defaults write -g com.apple.mouse.scaling 3.0
```

## Software Optimization

### App Management
```bash
# List all running applications with memory usage
ps aux | grep -E "(App|app)" | head -10

# Kill specific application (use carefully)
killall "Safari"

# Clear application caches for specific app
rm -rf ~/Library/Caches/com.apple.Safari/
```

### Launch Agent Optimization
```bash
# List all launch agents and daemons
launchctl list | grep -E "(com|org)" | head -20

# Disable unnecessary startup items (example)
sudo defaults write /Library/Preferences/com.apple.loginwindow StartupItems -dict-add "AdobeUpdate" -string "Disabled"

# Check for app updates via terminal
softwareupdate --list
```

### System Services Management
```bash
# List all system services that start automatically
ls -la ~/Library/LaunchAgents/

# Disable specific services (example)
sudo launchctl unload /System/Library/LaunchDaemons/com.apple.AirPortBaseStationAgent.plist

# Re-enable service later if needed
sudo launchctl load /System/Library/LaunchDaemons/com.apple.AirPortBaseStationAgent.plist
```

## Developer Tools & Terminal Commands

### Development Environment Setup
```bash
# Install developer tools (if not installed)
xcode-select --install

# Create optimized development directory structure
mkdir -p ~/Development/{Projects,Tools,Versions}

# Set up custom shell environment variables
echo 'export PATH="$HOME/Development/Tools:$PATH"' >> ~/.zshrc

# Update system and packages automatically
brew update && brew upgrade
```

### Performance Monitoring for Developers
```bash
# Monitor CPU usage of specific processes (example: Node.js)
top -o cpu | grep node

# Check network bandwidth in real-time
iftop -i en0

# Monitor file system activity during development
sudo dtrace -n 'syscall::open*:entry { printf("%s opened %s\n", execname, copyinstr(arg0)); }'

# Track memory leaks using Instruments (via command line)
instruments -t "Leaks" -l 1000 -D ~/Documents/leaks.tracetemplate
```

### Git Optimization
```bash
# Configure git for better performance on large repositories
git config --global core.precomposeUnicode true

# Enable git compression optimization
git config --global core.autocrlf false

# Set up efficient git garbage collection schedule
git gc --aggressive --prune=now
```

## Productivity Hacks

### Terminal Customization
```bash
# Create custom aliases for frequently used commands
echo 'alias ll="ls -la"' >> ~/.zshrc
echo 'alias gs="git status"' >> ~/.zshrc

# Set up a performance monitoring alias
echo 'alias monitor="iostat -c 1 && echo "--- Memory ---" && vm_stat 1 | head -5"' >> ~/.zshrc

# Custom prompt showing battery and memory usage
PS1='[\u@\h \W] Battery: $(pmset -g batt | grep -o "\d*%" | cut -d% -f1)% RAM: $(vm_stat | grep "Pages free" | awk "{print \$3}")\n\$ '
```

### Workflow Automation
```bash
# Create automated backup script (save as ~/bin/backup.sh)
#!/bin/bash
echo "Starting backup at $(date)" >> /tmp/backup.log
rsync -avz --delete ~/Documents/ user@backup-server:/backups/documents/
echo "Backup completed at $(date)" >> /tmp/backup.log

# Make it executable and add to crontab for automation
chmod +x ~/bin/backup.sh
crontab -e  # Add: 0 2 * * * ~/bin/backup.sh
```

### Screen Management Shortcuts
```bash
# Create a script to quickly switch between screens or applications
#!/bin/bash
osascript -e 'tell application "System Events" to tell process "Safari" to activate'
osascript -e 'tell application "System Events" to tell process "Visual Studio Code" to activate'
```

## Troubleshooting and Maintenance

### System Health Monitoring
```bash
# Check system logs for errors (recent 100 lines)
tail -n 100 /var/log/system.log | grep -i error

# Monitor disk space usage
df -h

# Check memory usage in real-time
vm_stat

# Look for suspicious processes using high CPU
top -o cpu | head -20

# Check for malware or security issues
sudo find /Applications -name "*.app" -exec xattr {} \;
```

### Performance Optimization Script
```bash
#!/bin/bash
echo "Starting system optimization..."

# Clear caches and temporary files
sudo rm -rf ~/Library/Caches/*
sudo rm -rf /private/var/folders/*/*/C/com.apple.LaunchServices*
sudo periodic daily weekly monthly

# Optimize launch services database
sudo lsregister -kill -r -domain local -domain system -domain user

echo "Optimization completed."
```

### Battery Life Optimization
```bash
# Check battery health and optimization status
system_profiler SPPowerDataType | grep "Cycle Count"

# Set power management settings for better efficiency
pmset -g custom  # Shows current settings

# Example: Reduce display sleep time to save battery
sudo pmset displaysleep 5

# Enable automatic graphics switching when on battery (if supported)
sudo pmset -a gpuswitch 1
```

This comprehensive guide covers advanced macOS optimization, performance monitoring, and productivity enhancement techniques. Each section provides practical commands and examples that can be adapted for specific use cases while maintaining system stability.

Remember to:
- Always backup your system before making significant changes
- Test scripts in a safe environment first
- Monitor the effects of optimizations on system performance
- Regularly update software and security patches

For production systems, consider running these optimization routines during maintenance windows when system load is minimal.

