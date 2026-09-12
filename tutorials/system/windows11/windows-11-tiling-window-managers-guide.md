# Windows 11 Tiling Window Managers (GlazeWM, Komorebi & FancyZones) Guide

Tiling window managers organize your application windows automatically into non-overlapping grids, splits, and columns. For developers transitioning from Linux (i3/bspwm/Hyprland) or macOS (AeroSpace/Yabai) to Windows 11, dynamic tiling window managers bring complete keyboard-driven ergonomics and zero window management latency to Windows.

On Windows 11, developers have options ranging from native zone-based grid snapping (**Microsoft PowerToys FancyZones**) to full dynamic, automatic tiling window managers (**GlazeWM** and **Komorebi**).

---

## 📚 Table of Contents

1. [Overview & Why Use Tiling on Windows 11?](#overview--why-use-tiling-on-windows-11)
2. [Tiling Window Managers Comparison](#tiling-window-managers-comparison)
3. [Installing & Configuring GlazeWM (Recommended)](#installing--configuring-glazewm-recommended)
4. [GlazeWM Workspaces & Keybindings Configuration](#glazewm-workspaces--keybindings-configuration)
5. [Advanced Dynamic Tiling: Komorebi](#advanced-dynamic-tiling-komorebi)
6. [Zone-Based Tiling: PowerToys FancyZones](#zone-based-tiling-powertoys-fancyzones)
7. [Status Bars for Windows: Zebar & Yasb](#status-bars-for-windows-zebar--yasb)
8. [Everyday Cheat Sheet & PowerShell Aliases](#everyday-cheat-sheet--powershell-aliases)
9. [Autostart Setup & Clean Uninstallation](#autostart-setup--clean-uninstallation)

---

## 🔍 Overview & Why Use Tiling on Windows 11?

While Windows 11 includes native Snap Layouts (`Win + Z`), traditional window management still requires manual dragging, resizing, and positioning with the mouse. Tiling window managers offer:

- **Automatic Space Partitioning**: Spawning a new Windows Terminal, editor, or browser immediately divides the active screen space cleanly.
- **Keyboard-Driven Workspaces**: Jump between virtual workspaces (`Alt + 1..9`) instantly with zero animation delay.
- **Ultrawide & Multi-Monitor Mastery**: Eliminates wasted screen space and makes ultrawide monitors vastly more productive.
- **Hands on the Home Row**: Manage window placement, splits, and focus without reaching for the mouse.

---

## ⚖️ Tiling Window Managers Comparison

| Feature | GlazeWM | Komorebi | PowerToys FancyZones |
| :--- | :--- | :--- | :--- |
| **Tiling Engine** | Dynamic (i3/bspwm tree) | Dynamic (BSP / Columns) | Static Grid Zones |
| **Language** | C# / .NET | Rust | C++ |
| **Configuration** | YAML (`config.yaml`) | JSON (`komorebi.json`) | GUI in PowerToys |
| **Hotkey Engine** | Built-in keybinding engine | Uses `whkd` daemon | Win + Arrow snapping |
| **Virtual Workspaces** | Independent custom workspaces | Multi-monitor workspaces | Uses standard Windows Desktops |
| **Window Borders** | Colored active borders | Built-in colored accents | Zone outline accents |
| **Complexity** | Low / Plug-and-play | Moderate | Very Easy |

---

## 🚀 Installing & Configuring GlazeWM (Recommended)

**GlazeWM** is an i3-inspired dynamic tiling window manager designed specifically for Windows 10 and 11.

### 1. Install via Winget
```powershell
# Install GlazeWM using Windows Package Manager
winget install --id GlazeWM.GlazeWM -e

# Or install via Scoop
scoop bucket add extras
scoop install glazewm
```

### 2. Locate Configuration Directory
GlazeWM stores its configuration file at:
`%USERPROFILE%\.glzr\glazewm\config.yaml`

Create or edit the configuration using PowerShell:
```powershell
notepad "$HOME\.glzr\glazewm\config.yaml"
```

---

## ⚙️ GlazeWM Workspaces & Keybindings Configuration

Below is a battle-tested, developer-ready `config.yaml` configuration using `Alt` as the primary modifier key:

```yaml
general:
  # Gaps between windows and monitor edges (in pixels)
  inner_gaps: 8px
  outer_gaps: 12px

  # Cursor follow focus behavior
  cursor_follows_focus: false

# Window border styling
borders:
  active:
    color: "#89b4fa"
    thickness: 2px
  inactive:
    color: "#313244"
    thickness: 1px

# Define custom virtual workspaces
workspaces:
  - name: "1"
    display_name: "1: Dev"
  - name: "2"
    display_name: "2: Web"
  - name: "3"
    display_name: "3: Term"
  - name: "4"
    display_name: "4: Chat"
  - name: "5"
    display_name: "5: Media"

# Application window rules (Floating exceptions)
window_rules:
  # Float Windows dialogs and system tools
  - command: "set-floating"
    match:
      - window_process: { equals: "Taskmgr.exe" }
      - window_process: { equals: "SnippingTool.exe" }
      - window_title: { contains: "Settings" }

# Keybindings (Alt is modifier key)
keybindings:
  # Focus movement (Vim navigation)
  - command: "focus --direction left"
    bindings: ["alt+h", "alt+left"]
  - command: "focus --direction right"
    bindings: ["alt+l", "alt+right"]
  - command: "focus --direction up"
    bindings: ["alt+k", "alt+up"]
  - command: "focus --direction down"
    bindings: ["alt+j", "alt+down"]

  # Window swapping / movement
  - command: "move --direction left"
    bindings: ["alt+shift+h", "alt+shift+left"]
  - command: "move --direction right"
    bindings: ["alt+shift+l", "alt+shift+right"]
  - command: "move --direction up"
    bindings: ["alt+shift+k", "alt+shift+up"]
  - command: "move --direction down"
    bindings: ["alt+shift+j", "alt+shift+down"]

  # Window state management
  - command: "close"
    bindings: ["alt+shift+q"]
  - command: "toggle-floating"
    bindings: ["alt+shift+space"]
  - command: "toggle-fullscreen"
    bindings: ["alt+f"]

  # Split direction toggles
  - command: "toggle-tiling-direction"
    bindings: ["alt+v"]

  # Launch Terminal and Applications
  - command: "exec wt.exe"
    bindings: ["alt+enter"]

  # Workspace navigation
  - command: "focus --workspace 1"
    bindings: ["alt+1"]
  - command: "focus --workspace 2"
    bindings: ["alt+2"]
  - command: "focus --workspace 3"
    bindings: ["alt+3"]
  - command: "focus --workspace 4"
    bindings: ["alt+4"]
  - command: "focus --workspace 5"
    bindings: ["alt+5"]

  # Move focused window to workspace
  - command: "move --workspace 1"
    bindings: ["alt+shift+1"]
  - command: "move --workspace 2"
    bindings: ["alt+shift+2"]
  - command: "move --workspace 3"
    bindings: ["alt+shift+3"]
  - command: "move --workspace 4"
    bindings: ["alt+shift+4"]
  - command: "move --workspace 5"
    bindings: ["alt+shift+5"]

  # Reload GlazeWM config in-place
  - command: "reload-config"
    bindings: ["alt+shift+r"]
```

---

## 🦀 Advanced Dynamic Tiling: Komorebi

**Komorebi** is a dynamic tiling window manager written in Rust, featuring deep customization and ultra-low latency:

### 1. Install Komorebi and WHKD
```powershell
# Install via Winget
winget install --id LGUG2Z.komorebi -e
winget install --id LGUG2Z.whkd -e

# Start Komorebi background daemon
komorebic start --whkd
```

### 2. Komorebi Quick Control
```powershell
# Stop tiling daemon
komorebic stop

# Toggle tiling on/off dynamically
komorebic toggle-tiling

# Change active tiling layout (BSP, Columns, Rows, Vertical Stack)
komorebic change-layout bsp
komorebic change-layout columns
```

---

## 📐 Zone-Based Tiling: PowerToys FancyZones

If you want a GUI-configured tiling workflow that works natively with Windows 11 animations:

1. Install Microsoft PowerToys:
   ```powershell
   winget install --id Microsoft.PowerToys -e
   ```
2. Open **PowerToys Settings -> FancyZones**.
3. Press `Win + Shift + ` ` (backtick) to open the **Zone Editor**.
4. Choose or create a layout (e.g. 3-column split or grid with 8px gaps).
5. **Usage**:
   - Hold `Shift` while dragging any application window to snap it into a zone.
   - Use `Win + Ctrl + Alt + Arrow Keys` to jump windows between custom zones.

---

## 📊 Status Bars for Windows: Zebar & Yasb

To complete your keyboard-driven tiling setup with a minimal status bar displaying CPU, RAM, battery, active workspace, and network:

### Zebar (from the creators of GlazeWM)
```powershell
# Install Zebar via Winget
winget install --id GlazeWM.Zebar -e
```
Zebar uses web technologies (HTML, CSS, JavaScript) to render beautiful status bars that sync directly with GlazeWM's active workspace states.

---

## ⚡ Everyday Cheat Sheet & PowerShell Aliases

Add these productivity aliases to your PowerShell `$PROFILE`:

```powershell
# GlazeWM management aliases
function reload-wm { glazewm.exe reload-config }
function edit-wm { notepad "$HOME\.glzr\glazewm\config.yaml" }
function edit-bar { notepad "$HOME\.glzr\zebar\settings.json" }

# Restart GlazeWM
function restart-wm {
    Stop-Process -Name "glazewm" -Force -ErrorAction SilentlyContinue
    Start-Process "glazewm.exe"
}
```

### Core Hotkey Reference Table (GlazeWM Default Setup)
| Action | Hotkey |
| :--- | :--- |
| **Open Windows Terminal** | `Alt + Enter` |
| **Close Active Window** | `Alt + Shift + Q` |
| **Focus Left / Down / Up / Right** | `Alt + H / J / K / L` (or Arrow keys) |
| **Move Window Left / Down / Up / Right** | `Alt + Shift + H / J / K / L` |
| **Toggle Split Orientation** | `Alt + V` |
| **Toggle Floating / Tiling** | `Alt + Shift + Space` |
| **Toggle Fullscreen** | `Alt + F` |
| **Switch Workspace 1..5** | `Alt + 1..5` |
| **Move Window to Workspace 1..5** | `Alt + Shift + 1..5` |
| **Reload GlazeWM Config** | `Alt + Shift + R` |

---

## 🔄 Autostart Setup & Clean Uninstallation

### Enable Autostart on Windows Login
Create a shortcut to GlazeWM in your Windows Startup folder:
```powershell
$shortcutPath = "$env:APPDATA\Microsoft\Windows\Start Menu\Programs\Startup\GlazeWM.lnk"
$targetPath = (Get-Command glazewm.exe).Source

$WScriptShell = New-Object -ComObject WScript.Shell
$Shortcut = $WScriptShell.CreateShortcut($shortcutPath)
$Shortcut.TargetPath = $targetPath
$Shortcut.Save()
```

### Clean Uninstallation
```powershell
# Stop running instance
Stop-Process -Name "glazewm" -Force -ErrorAction SilentlyContinue

# Remove startup shortcut
Remove-Item -Path "$env:APPDATA\Microsoft\Windows\Start Menu\Programs\Startup\GlazeWM.lnk" -Force -ErrorAction SilentlyContinue

# Uninstall via Winget
winget uninstall --id GlazeWM.GlazeWM -e

# Remove config files
Remove-Item -Path "$HOME\.glzr" -Recurse -Force -ErrorAction SilentlyContinue
```
