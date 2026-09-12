## Table of Contents
1. [Hardware & Thermal Optimization](#hardware--thermal-optimization)
2. [Display, Resolution & Graphics Settings](#display-resolution--graphics-settings)
3. [System Performance & Registry Tweaks](#system-performance--registry-tweaks)
4. [Memory, Swap & Storage Optimization](#memory-swap--storage-optimization)
5. [Battery Life & Power Management](#battery-life--power-management)
6. [Windows Package Management (Winget)](#windows-package-management-winget)
7. [Developer Environment & CLI Optimization](#developer-environment--cli-optimization)
8. [Productivity Hacks & PowerToys](#productivity-hacks--powertoys)
9. [Troubleshooting & System Repair](#troubleshooting--system-repair)

---

## Hardware & Thermal Optimization

### Thermal Management & Hardware Diagnostics
```powershell
# Run PowerShell as Administrator to query system and hardware specs

# Get detailed processor architecture and core counts
Get-CimInstance Win32_Processor | Select-Object Name, NumberOfCores, NumberOfLogicalProcessors, MaxClockSpeed

# Inspect CPU and Motherboard temperature sensors (if supported by WMI/ACPI)
Get-CimInstance -Namespace "root/wmi" -ClassName MSAcpi_ThermalZoneTemperature -ErrorAction SilentlyContinue | 
    ForEach-Object { "$([math]::Round(($_.CurrentTemperature - 2732) / 10, 1)) °C" }

# Monitor system processes consuming highest CPU in real-time
Get-Process | Sort-Object CPU -Descending | Select-Object -First 15 Name, CPU, WorkingSet64
```

### Storage Drive Health & SSD Optimization
```powershell
# Query physical drive model, media type (SSD/NVMe/HDD), and health status
Get-PhysicalDisk | Select-Object DeviceId, FriendlyName, MediaType, HealthStatus, OperationalStatus

# Run manual SSD TRIM / Defrag optimization across all fixed volumes
Optimize-Volume -DriveLetter C -ReTrim -Verbose

# Enable Windows Storage Sense to automatically purge temporary files and old downloads
Set-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\StorageSense\Parameters\StoragePolicy" `
    -Name "01" -Value 1

# Clean Windows Temp folders and delivery optimization cache via PowerShell
Remove-Item -Path "$env:TEMP\*" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item -Path "C:\Windows\Temp\*" -Recurse -Force -ErrorAction SilentlyContinue
```

---

## Display, Resolution & Graphics Settings

### GPU & Refresh Rate Configuration
```powershell
# Query active display adapters, VRAM, and driver versions
Get-CimInstance Win32_VideoController | Select-Object Name, DriverVersion, AdapterRAM

# Check current screen resolution and refresh rate
Get-CimInstance -ClassName Win32_VideoController | 
    Select-Object Name, CurrentHorizontalResolution, CurrentVerticalResolution, CurrentRefreshRate

# Launch modern Windows 11 Graphics Settings to configure Per-App GPU Preference
# (Force High-Performance discrete GPU for IDEs, Docker, or compilers)
Start-Process "ms-settings:display-advancedgraphics"

# Enable Windows 11 Variable Refresh Rate (VRR) & Hardware Accelerated GPU Scheduling (HAGS)
# Registry path: HKLM\SYSTEM\CurrentControlSet\Control\GraphicsDrivers
Set-ItemProperty -Path "HKLM:\SYSTEM\CurrentControlSet\Control\GraphicsDrivers" `
    -Name "HwSchMode" -Value 2 -Type DWord
```

---

## System Performance & Registry Tweaks

### Telemetry Reduction & Background Services Optimization
```powershell
# Disable Connected User Experiences and Telemetry service
Stop-Service -Name "DiagTrack" -ErrorAction SilentlyContinue
Set-Service -Name "DiagTrack" -StartupType Disabled

# Disable Windows Consumer Features (Prevents auto-installing suggested apps/games)
New-Item -Path "HKLM:\SOFTWARE\Policies\Microsoft\Windows\CloudContent" -Force | Out-Null
Set-ItemProperty -Path "HKLM:\SOFTWARE\Policies\Microsoft\Windows\CloudContent" `
    -Name "DisableWindowsConsumerFeatures" -Value 1 -Type DWord

# Disable Bing Web Search results inside Windows 11 Start Menu
New-Item -Path "HKCU:\Software\Policies\Microsoft\Windows\Explorer" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Policies\Microsoft\Windows\Explorer" `
    -Name "DisableSearchBoxSuggestions" -Value 1 -Type DWord
```

### Visual Effects & UI Responsiveness
```powershell
# Reduce menu show delay from 400ms to 20ms for instant contextual menus
Set-ItemProperty -Path "HKCU:\Control Panel\Desktop" -Name "MenuShowDelay" -Value "20"

# Open Classic Visual Effects dialog to choose "Adjust for best performance"
Start-Process "SystemPropertiesPerformance.exe"

# Make File Explorer open to "This PC" instead of "Home / Quick Access"
Set-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\Explorer\Advanced" `
    -Name "LaunchTo" -Value 1 -Type DWord

# Show file extensions for known file types (Essential for developers)
Set-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\Explorer\Advanced" `
    -Name "HideFileExt" -Value 0 -Type DWord

# Show hidden and system files in Explorer
Set-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\Explorer\Advanced" `
    -Name "Hidden" -Value 1 -Type DWord
```

---

## Memory, Swap & Storage Optimization

### RAM Management & Standby List
```powershell
# Check total installed physical RAM vs available memory
Get-CimInstance Win32_OperatingSystem | 
    Select-Object @{Name="TotalRAM_GB";Expression={[math]::Round($_.TotalVisibleMemorySize/1MB, 2)}}, `
                  @{Name="FreeRAM_GB";Expression={[math]::Round($_.FreePhysicalMemory/1MB, 2)}}

# Configure Windows Paging File (Pagefile) size
# Recommended for dev machines with >= 32GB RAM: Set custom initial 4GB, max 16GB
$sys = Get-CimInstance Win32_ComputerSystem -EnableAllPrivileges
$sys.AutomaticManagedPagefile = $False
$sys | Set-CimInstance
```

---

## Battery Life & Power Management

### Power Schemes & Ultimate Performance Plan
```powershell
# List all available power plans on system
powercfg /list

# Unlock Windows 11 "Ultimate Performance" power plan (Maximizes CPU performance)
powercfg -duplicatescheme e9a42b02-d5df-448d-aa00-03f14749eb61

# Set active power scheme to Ultimate Performance (copy GUID returned from above)
# powercfg /setactive <GUID>

# Set display timeout on battery (5 minutes) and AC (15 minutes)
powercfg /change monitor-timeout-dc 5
powercfg /change monitor-timeout-ac 15

# Set computer sleep timeout on battery (15 minutes) and AC (never)
powercfg /change standby-timeout-dc 15
powercfg /change standby-timeout-ac 0
```

### Detailed Battery Health & Sleep Diagnostics
```powershell
# Generate battery health report HTML (Calculates actual capacity vs design capacity)
powercfg /batteryreport /output "$HOME\battery-report.html"
Start-Process "$HOME\battery-report.html"

# Generate Sleep Study report to identify apps draining battery during sleep/modern standby
powercfg /sleepstudy /output "$HOME\sleepstudy-report.html"

# Find device or application preventing Windows from sleeping
powercfg /requests
```

---

## Windows Package Management (Winget)

Windows Package Manager (`winget`) is Microsoft's official CLI package manager, equivalent to Homebrew on macOS or APT on Ubuntu:

```powershell
# Verify winget is available
winget --version

# Search for applications and developer packages
winget search "Visual Studio Code"
winget search "Git.Git"

# Install core developer stack non-interactively
winget install --id Microsoft.VisualStudioCode -e --accept-package-agreements --accept-source-agreements
winget install --id Git.Git -e --accept-package-agreements --accept-source-agreements
winget install --id Microsoft.WindowsTerminal -e
winget install --id Microsoft.PowerToys -e
winget install --id Docker.DockerDesktop -e
winget install --id Oven-sh.Bun -e

# List all installed packages that have available updates
winget upgrade

# Upgrade all installed applications in a single command
winget upgrade --all --include-unknown
```

---

## Developer Environment & CLI Optimization

### Enable Windows Developer Mode
```powershell
# Enable Developer Mode (Allows creating symlinks without administrator privileges)
Set-ItemProperty -Path "HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\AppModelUnlock" `
    -Name "AllowDevelopmentWithoutDevLicense" -Value 1 -Type DWord
```

### Modern PowerShell 7 (pwsh) & Profile Setup
```powershell
# Install modern cross-platform PowerShell 7
winget install --id Microsoft.PowerShell -e

# Create or open custom PowerShell Profile
if (!(Test-Path -Path $PROFILE)) {
    New-Item -ItemType File -Path $PROFILE -Force
}
notepad $PROFILE
```

**Recommended `$PROFILE` configuration:**
```powershell
# Everyday navigation aliases
Set-Alias -Name ll -Value Get-ChildItem
function .. { Set-Location .. }
function ... { Set-Location ..\.. }

# Fast Git shortcuts
function gs { git status -sb }
function gl { git log --oneline --graph --decorate -n 15 }

# Show active listening TCP ports
function ports { Get-NetTCPConnection -State Listen | Select-Object LocalPort, OwningProcess }

# Fast public IP lookup
function myip { (Invoke-RestMethod -Uri "https://ifconfig.me/ip").Trim() }
```

### Git for Windows Performance Tuning
```powershell
# Enable filesystem cache to speed up git status on large Windows repos
git config --global core.fscache true

# Preload index in parallel
git config --global core.preloadindex true

# Configure autocrlf to prevent newline conversion headaches
git config --global core.autocrlf false

# Cache credentials in Windows Credential Manager
git config --global credential.helper manager
```

---

## Productivity Hacks & PowerToys

Install **Microsoft PowerToys** (`winget install Microsoft.PowerToys -e`) for developer productivity utilities:

- **PowerToys Run (`Alt + Space`)**: Instant spotlight-style search for apps, open windows, files, and math calculations.
- **FancyZones (`Shift + Drag`)**: Custom tiling grid layouts across ultrawide and multi-monitor setups.
- **Always on Top (`Win + Ctrl + T`)**: Pin any active window (terminal, debugger, reference doc) to float above all others.
- **Text Extractor (`Win + Shift + T`)**: OCR tool to copy text from images, videos, or dialog boxes.
- **Color Picker (`Win + Shift + C`)**: Instant HEX/RGB screen color inspection.
- **Keyboard Manager**: Remap problematic keys or create custom system-wide keybindings.

---

## Troubleshooting & System Repair

### System File Checker & Component Store Repair
Run these commands in an elevated Administrator PowerShell prompt if Windows behaves unstably:

```powershell
# Step 1: Scan and repair corrupt Windows system files
sfc /scannow

# Step 2: Check health of the Windows Component Store image
DISM /Online /Cleanup-Image /CheckHealth

# Step 3: Scan the image for component store corruption
DISM /Online /Cleanup-Image /ScanHealth

# Step 4: Restore and repair corrupted Windows image files using Windows Update
DISM /Online /Cleanup-Image /RestoreHealth
```

### Network Stack & DNS Reset
```powershell
# Flush DNS resolver cache
Clear-DnsClientCache

# Release and renew DHCP leases
ipconfig /release
ipconfig /renew

# Reset Winsock catalog and TCP/IP stack (requires reboot)
netsh winsock reset
netsh int ip reset
```

### Restart Windows Graphics Driver Pipeline
When the screen freezes or displays graphical artifacts:
```powershell
# Keyboard shortcut to restart graphics subsystem without rebooting:
# Press: Win + Ctrl + Shift + B
# The screen will flicker and beep, reloading the GPU display driver.
```
