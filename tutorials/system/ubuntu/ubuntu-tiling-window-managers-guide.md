# Ubuntu Tiling Window Managers (i3, Sway and Hyprland) Guide

Tiling window managers organize your application windows automatically into non-overlapping grids, splits, and columns. Unlike traditional desktop environments where you manually drag and resize floating windows, tiling window managers give you full, keyboard-driven productivity with zero wasted screen space.

On Ubuntu, developers have the choice between battle-tested X11 managers (**i3-wm**), drop-in Wayland equivalents (**Sway**), modern fluid Wayland compositors (**Hyprland**), and GNOME-integrated tiling extensions (**Pop Shell** and **Ubuntu Tiling Assistant**).

---

## Table of Contents

1. [Overview and Why Use Tiling on Ubuntu?](#overview-and-why-use-tiling-on-ubuntu)
2. [Tiling Window Managers Comparison](#tiling-window-managers-comparison)
3. [Installing and Configuring i3-wm (X11 Classic)](#installing-and-configuring-i3-wm-x11-classic)
4. [Installing and Configuring Sway (Wayland Native)](#installing-and-configuring-sway-wayland-native)
5. [Modern Dynamic Tiling: Hyprland on Ubuntu](#modern-dynamic-tiling-hyprland-on-ubuntu)
6. [Integrated Option: GNOME Tiling (Pop Shell)](#integrated-option-gnome-tiling-pop-shell)
7. [Application Launchers: Rofi and Wofi](#application-launchers-rofi-and-wofi)
8. [Status Bars: i3status, Polybar and Waybar](#status-bars-i3status-polybar-and-waybar)
9. [Everyday Cheat Sheet and Useful Shell Aliases](#everyday-cheat-sheet-and-useful-shell-aliases)
10. [Session Switching and Uninstallation](#session-switching-and-uninstallation)

---

## Overview and Why Use Tiling on Ubuntu?

Default desktop environments require continuous mouse interaction to arrange, minimize, and locate overlapping windows. Tiling window managers offer:

- **Automatic Space Partitioning**: Spawning a terminal or browser splits the active workspace cleanly into halves or quadrants without manual adjustments.
- **Instant Workspace Switching**: Jump between isolated workspaces (`Mod + 1..9`) instantaneously with zero animation delay.
- **Ultralight Memory Footprint**: i3 and Sway consume around 50–150MB of RAM compared to 1–1.5GB for full desktop environments.
- **Complete Ergonomics**: Your hands never leave the home row of the keyboard.

---

## Tiling Window Managers Comparison

| Feature | i3-wm | Sway | Hyprland | Pop Shell (GNOME) |
| :--- | :--- | :--- | :--- | :--- |
| **Display Protocol** | X11 | Wayland | Wayland | Wayland / X11 |
| **Ubuntu Compatibility** | Native (`apt install i3`) | Native (`apt install sway`) | PPA / Source build | GNOME Shell Extension |
| **Config Parity** | Standard `i3/config` | 100% i3 syntax compatible | Custom `hyprland.conf` | GUI / `dconf` |
| **Animations & Blur** | Requires Picom | Clean, minimal | Smooth bezier animations | GNOME Default |
| **Multi-Monitor Handling** | Static workspace pinning | Dynamic output binding | Fractional scaling per monitor | GNOME Native |
| **Resource Usage** | ~40 MB RAM | ~70 MB RAM | ~150 MB RAM | Uses GNOME Shell RAM |

---

## Installing and Configuring i3-wm (X11 Classic)

i3 is the gold standard for X11 tiling on Ubuntu.

### 1. Install via APT
```bash
sudo apt update && sudo apt install -y \
    i3 \
    i3status \
    i3lock \
    rofi \
    feh \
    picom \
    alacritty \
    brightnessctl \
    pavucontrol
```

### 2. Initialize Configuration Directory
```bash
mkdir -p ~/.config/i3
cp /etc/i3/config ~/.config/i3/config
```

### 3. Example `~/.config/i3/config` Production Setup
```ini
# Use Super (Windows key) as Mod modifier
set $mod Mod4

# Set terminal emulator
set $terminal alacritty

# Use custom font
font pango:DejaVu Sans Mono 10

# Launch terminal
bindsym $mod+Return exec $terminal

# Kill focused window
bindsym $mod+q kill

# Application launcher (Rofi)
bindsym $mod+d exec rofi -show drun -show-icons

# Change focus between split windows
bindsym $mod+h focus left
bindsym $mod+j focus down
bindsym $mod+k focus up
bindsym $mod+l focus right

# Move focused window
bindsym $mod+Shift+h move left
bindsym $mod+Shift+j move down
bindsym $mod+Shift+k move up
bindsym $mod+Shift+l move right

# Split orientation
bindsym $mod+b split h
bindsym $mod+v split v

# Fullscreen toggle
bindsym $mod+f fullscreen toggle

# Toggle tiling / floating
bindsym $mod+Shift+space floating toggle

# Workspaces
bindsym $mod+1 workspace number 1
bindsym $mod+2 workspace number 2
bindsym $mod+3 workspace number 3
bindsym $mod+4 workspace number 4
bindsym $mod+5 workspace number 5

bindsym $mod+Shift+1 move container to workspace number 1
bindsym $mod+Shift+2 move container to workspace number 2
bindsym $mod+Shift+3 move container to workspace number 3
bindsym $mod+Shift+4 move container to workspace number 4
bindsym $mod+Shift+5 move container to workspace number 5

# Window Gaps (inner & outer)
gaps inner 8
gaps outer 4

# Autostart compositor and wallpaper
exec_always --no-startup-id picom -b
exec_always --no-startup-id feh --bg-fill ~/Pictures/wallpaper.jpg

# Audio volume keys (PulseAudio / PipeWire)
bindsym XF86AudioRaiseVolume exec pactl set-sink-volume @DEFAULT_SINK@ +5%
bindsym XF86AudioLowerVolume exec pactl set-sink-volume @DEFAULT_SINK@ -5%
bindsym XF86AudioMute exec pactl set-sink-mute @DEFAULT_SINK@ toggle
```

---

## Installing and Configuring Sway (Wayland Native)

Sway is designed as a drop-in Wayland replacement for i3. It uses the exact same configuration syntax as i3 while offering tear-free rendering and per-monitor fractional scaling.

### 1. Install Sway on Ubuntu
```bash
sudo apt update && sudo apt install -y \
    sway \
    swaylock \
    swayidle \
    waybar \
    wofi \
    grim \
    slurp \
    wl-clipboard \
    mako-notifier
```

### 2. Copy Default Configuration
```bash
mkdir -p ~/.config/sway
cp /etc/sway/config ~/.config/sway/config
```

### 3. Sway-Specific Tweaks (`~/.config/sway/config`)
```ini
# Set status bar to Waybar
bar {
    swaybar_command waybar
}

# Screenshots using grim and slurp (Wayland native)
bindsym Print exec grim -g "$(slurp)" - | wl-copy

# Wallpaper configuration in Sway
output * bg ~/Pictures/wallpaper.jpg fill

# Per-display scaling (e.g. 1.25x scaling on eDP-1)
output eDP-1 scale 1.25
```

---

## Modern Dynamic Tiling: Hyprland on Ubuntu

Hyprland is a high-performance, fluid Wayland compositor that includes dual-directional tiling, smooth physics-based animations, rounded corners, and native GPU blur.

### 1. Recommended Setup on Ubuntu 24.04 LTS
```bash
# Add official/community PPA or build via source dependencies
sudo apt install -y \
    meson \
    ninja-build \
    libwayland-dev \
    wayland-protocols \
    libdrm-dev \
    libgbm-dev \
    libinput-dev \
    libxkbcommon-dev
```

### 2. Keybindings Snippet (`~/.config/hypr/hyprland.conf`)
```ini
$mainMod = SUPER

# Application bindings
bind = $mainMod, RETURN, exec, alacritty
bind = $mainMod, Q, killactive,
bind = $mainMod, M, exit,
bind = $mainMod, E, exec, nautilus
bind = $mainMod, SPACE, exec, wofi --show drun

# Window movement
bind = $mainMod, left, movefocus, l
bind = $mainMod, right, movefocus, r
bind = $mainMod, up, movefocus, u
bind = $mainMod, down, movefocus, d

# Window decoration
decoration {
    rounding = 8
    blur {
        enabled = true
        size = 5
        passes = 2
    }
}
```

---

## Integrated Option: GNOME Tiling (Pop Shell)

If you prefer keeping your standard Ubuntu GNOME desktop with system trays, settings panels, and Bluetooth integration while having full keyboard-driven auto-tiling:

```bash
# Install Pop Shell extension on Ubuntu
sudo apt install -y gnome-shell-extension-prefs git node-typescript

git clone https://github.com/pop-os/shell.git /tmp/pop-shell
cd /tmp/pop-shell
make local-install

# Restart GNOME Shell (or log out and back in)
# Enable Pop Shell via Extensions manager
gnome-extensions enable pop-shell@system76.com
```

**Key Pop Shell Shortcuts:**
- `Super + Y`: Toggle auto-tiling mode on/off
- `Super + Enter`: Enter window management mode (use arrows or `H/J/K/L` to move or resize)
- `Super + Arrow Keys`: Navigate focused windows
- `Super + /`: Quick launcher

---

## Application Launchers: Rofi and Wofi

### Rofi Configuration for i3 (X11)
```bash
mkdir -p ~/.config/rofi
cat << 'EOF' > ~/.config/rofi/config.rasi
configuration {
    modi: "drun,run,window";
    font: "Inter 11";
    show-icons: true;
    terminal: "alacritty";
    drun-match-fields: "name,generic,exec";
}
@theme "/usr/share/rofi/themes/sidebar.rasi"
EOF
```

### Wofi Configuration for Sway / Hyprland (Wayland)
```bash
mkdir -p ~/.config/wofi
cat << 'EOF' > ~/.config/wofi/style.css
window {
    margin: 0px;
    background-color: #1e1e2e;
    border-radius: 10px;
    border: 2px solid #89b4fa;
    font-family: "DejaVu Sans Mono", monospace;
    font-size: 14px;
}
#input {
    margin: 10px;
    border-radius: 6px;
    border: none;
    background-color: #313244;
    color: #cdd6f4;
    padding: 8px 12px;
}
#entry:selected {
    background-color: #89b4fa;
    color: #11111b;
    border-radius: 6px;
}
EOF
```

---

## Status Bars: i3status, Polybar and Waybar

### Minimal `i3status.conf` (`~/.config/i3status/config`)
```ini
general {
    colors = true
    interval = 2
    color_good = "#a6e3a1"
    color_degraded = "#f9e2af"
    color_bad = "#f38ba8"
}

order += "disk /"
order += "wireless _first_"
order += "ethernet _first_"
order += "battery all"
order += "cpu_usage"
order += "memory"
order += "tztime local"

disk "/" { format = "💾 %avail" }
battery all { format = "⚡ %status %percentage (%remaining)" }
cpu_usage { format = "CPU: %usage" }
memory { format = "RAM: %used / %total" }
tztime local { format = "📅 %Y-%m-%d %H:%M:%S" }
```

---

## Everyday Cheat Sheet and Useful Shell Aliases

```bash
# Add to ~/.bashrc or ~/.zshrc for fast tiling window management

# Reload i3 configuration in-place without logging out
alias i3-reload="i3-msg reload"
alias i3-restart="i3-msg restart"

# Reload Sway configuration in-place
alias sway-reload="swaymsg reload"

# Quick config edit shortcuts
alias i3config="nano ~/.config/i3/config"
alias swayconfig="nano ~/.config/sway/config"
alias waybarconfig="nano ~/.config/waybar/config"

# Lock screen manually
alias lock="i3lock -c 000000"
```

### Core Hotkey Reference Table
| Action | i3 / Sway Shortcut | Hyprland Shortcut |
| :--- | :--- | :--- |
| **Open Terminal** | `Mod + Enter` | `Super + Enter` |
| **Open Launcher** | `Mod + D` (Rofi) | `Super + Space` (Wofi) |
| **Close Window** | `Mod + Shift + Q` | `Super + Q` |
| **Split Horizontal** | `Mod + B` (or `Mod + H`) | Automatic BSP |
| **Split Vertical** | `Mod + V` | Automatic BSP |
| **Toggle Floating** | `Mod + Shift + Space` | `Super + V` |
| **Toggle Fullscreen** | `Mod + F` | `Super + F` |
| **Switch Workspace** | `Mod + 1..9` | `Super + 1..9` |
| **Move to Workspace** | `Mod + Shift + 1..9` | `Super + Shift + 1..9` |
| **Lock Screen** | `Mod + Shift + X` | `Super + L` |

---

## Session Switching and Uninstallation

### Switching Sessions at Login
1. Log out of your current session (`Mod + Shift + E` in i3).
2. At the GDM3 login prompt, click your user account.
3. Click the gear icon in the bottom-right corner.
4. Select **i3**, **Sway**, or **Ubuntu on Wayland / Xorg**.
5. Enter your password to log in.

### Clean Removal (if returning to stock GNOME)
```bash
# Remove i3 packages
sudo apt purge -y i3 i3-wm i3status i3lock rofi picom
sudo apt autoremove --purge -y

# Remove Sway packages
sudo apt purge -y sway swaylock swayidle waybar wofi
sudo apt autoremove --purge -y
```
