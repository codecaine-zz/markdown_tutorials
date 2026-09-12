# 🐳 Windows 11 WSL2 & Docker Development Environment Guide

**A comprehensive, production-grade guide for building, optimizing, and managing high-performance Linux and Docker development environments on Windows 11.**

With **WSL2 (Windows Subsystem for Linux 2)**, Windows 11 runs a real Linux kernel alongside Windows. Combined with Docker, developers get the best of both worlds: the full Windows desktop ecosystem alongside bare-metal Linux toolchains, Bash scripts, Docker containers, and zero-compromise I/O speeds.

---

## 📋 Table of Contents

1. [✨ Modern Windows 11 Architecture (WSL2 + Docker)](#modern-windows-11-architecture-wsl2--docker)
2. [🚀 Installing & Configuring WSL2 Ubuntu 24.04](#installing--configuring-wsl2-ubuntu-2404)
3. [⚙️ Tuning `.wslconfig` (Preventing `vmmem` RAM Exhaustion)](#tuning-wslconfig-preventing-vmmem-ram-exhaustion)
4. [🐳 Docker Setup: Docker Desktop vs Native Docker in WSL2](#docker-setup-docker-desktop-vs-native-docker-in-wsl2)
5. [⚡ The Golden Rule of WSL2 Performance (Filesystem Speeds)](#the-golden-rule-of-wsl2-performance-filesystem-speeds)
6. [📦 Complete Multi-Container Development Stack](#complete-multi-container-development-stack)
7. [🛠 WSL2 & Docker Management Toolkit (`wsl-dev.ps1`)](#wsl2--docker-management-toolkit-wsl-devps1)
8. [💾 Data Persistence, Volume Management & Backups](#data-persistence-volume-management--backups)
9. [🐛 Troubleshooting, Clock Drift & Port Conflicts](#troubleshooting-clock-drift--port-conflicts)
10. [🧹 Maintenance & WSL2 Disk Compaction](#maintenance--wsl2-disk-compaction)

---

## ✨ Modern Windows 11 Architecture (WSL2 + Docker)

Traditional Windows virtualization relied on heavyweight VMs with fixed RAM allocations. WSL2 uses a dynamic lightweight utility VM running a custom Microsoft Linux kernel:

- **True Linux ABI Compatibility**: Run native Linux binaries without emulation.
- **Dynamic Memory Allocation**: RAM is granted to WSL2 on-demand and reclaimed by Windows 11.
- **Seamless Localhost Networking**: Services running inside WSL2 or Docker are directly reachable at `localhost:<port>` from Windows 11 browsers.
- **VS Code Remote Integration**: Edit files with native Windows GUI while language servers and debuggers run inside Linux.

---

## 🚀 Installing & Configuring WSL2 Ubuntu 24.04

### 1. Install WSL2 and Ubuntu via PowerShell
Open an elevated Administrator PowerShell prompt:

```powershell
# Install WSL2 with latest Ubuntu LTS distribution
wsl --install -d Ubuntu-24.04

# Update WSL kernel to latest release
wsl --update

# Verify default WSL version is 2
wsl -l -v
```

### 2. Enable Systemd Support in Ubuntu
Ubuntu inside WSL2 supports native `systemd` for service management (crucial for Docker, databases, and cron).

Inside your Ubuntu terminal (`wsl`):
```bash
sudo tee /etc/wsl.conf << 'EOF'
[boot]
systemd=true

[network]
generateResolvConf=true

[interop]
enabled=true
appendWindowsPath=true
EOF
```

Restart WSL from PowerShell to apply systemd:
```powershell
wsl --shutdown
wsl
```

---

## ⚙️ Tuning `.wslconfig` (Preventing `vmmem` RAM Exhaustion)

By default, WSL2 can consume up to 50% (or in older versions, 80%) of your total host RAM. You must create a `%USERPROFILE%\.wslconfig` file in Windows to enforce strict resource limits:

Open PowerShell and create `.wslconfig`:
```powershell
notepad "$HOME\.wslconfig"
```

Paste this optimized configuration:
```ini
[wsl2]
# Limit VM memory to prevent Windows from freezing (e.g., 8GB or 12GB on a 16GB/32GB host)
memory=8GB

# Allocate CPU cores (typically half or all logical cores)
processors=6

# Set swap file size (prevents OOM kill during heavy builds)
swap=4GB

# Modern Windows 11 WSL2 Feature: Automatically return cached memory back to Windows
autoMemoryReclaim=gradual

# Enable localhost forwarding
localhostForwarding=true

# Nested virtualization (allows running Docker/KVM inside WSL2)
nestedVirtualization=true

[experimental]
# Automatically compact shrinking vhdx disk image
autoProxy=true
sparseVhd=true
```

Apply changes:
```powershell
wsl --shutdown
```

---

## 🐳 Docker Setup: Docker Desktop vs Native Docker in WSL2

You have two supported methods for running Docker on Windows 11:

### Option A: Docker Desktop (GUI & Official Extension Ecosystem)
1. Install Docker Desktop via Winget:
   ```powershell
   winget install --id Docker.DockerDesktop -e
   ```
2. Open Docker Desktop Settings:
   - Check **Use the WSL 2 based engine**.
   - Under **Resources -> WSL Integration**, toggle on your **Ubuntu-24.04** distro.
3. Docker is now accessible from both Windows Terminal and your Ubuntu shell.

### Option B: Native Docker CE Inside WSL2 (Free, Lightweight, Headless)
If you want to avoid Docker Desktop licensing or desire a purely headless, lightweight Docker setup, install native Docker CE directly inside your Ubuntu WSL2 instance:

```bash
# Inside WSL2 Ubuntu:
sudo apt update && sudo apt install -y ca-certificates curl gnupg

# Add Docker GPG key & repo
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Enable Docker service via systemd
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
```

---

## ⚡ The Golden Rule of WSL2 Performance (Filesystem Speeds)

> [!IMPORTANT]
> **NEVER** store active development projects or run `git`, `npm install`, or `docker build` inside `/mnt/c/...`!
>
> Crossing the Windows/Linux 9P filesystem bridge incurs a **10x to 20x performance penalty** due to NTFS metadata translation.

- ❌ **Slow**: `/mnt/c/Users/YourName/Projects/my-app`
- ✅ **Ultra-Fast (Native Linux ext4)**: `/home/username/projects/my-app`

### Accessing Linux Files from Windows
You can easily access your native Linux files in Windows File Explorer:
- Run `explorer.exe .` from any folder inside WSL2.
- Or open File Explorer and navigate to `\\wsl$\Ubuntu-24.04\home\...`.

---

## 📦 Complete Multi-Container Development Stack

A full-stack development template (`docker-compose.yml`) for Bun/Node.js, PostgreSQL, and Redis:

```yaml
services:
  app:
    image: oven/bun:latest
    container_name: wsl_dev_app
    restart: unless-stopped
    working_dir: /app
    volumes:
      - ./:/app
      - node_modules_data:/app/node_modules
    ports:
      - "3000:3000"
    environment:
      - NODE_ENV=development
      - DATABASE_URL=postgresql://postgres:secret@postgres:5432/wsl_db
      - REDIS_URL=redis://redis:6379
    command: bun run --hot src/index.ts
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_healthy

  postgres:
    image: postgres:16-alpine
    container_name: wsl_postgres
    restart: unless-stopped
    environment:
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: secret
      POSTGRES_DB: wsl_db
    ports:
      - "5432:5432"
    volumes:
      - wsl_pg_data:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 5s
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: wsl_redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    volumes:
      - wsl_redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 5s
      timeout: 3s
      retries: 5

volumes:
  wsl_pg_data:
  wsl_redis_data:
  node_modules_data:
```

---

## 🛠 WSL2 & Docker Management Toolkit (`wsl-dev.ps1`)

Save this PowerShell script to `$HOME\bin\wsl-dev.ps1` to manage your WSL environment from Windows:

```powershell
param (
    [Parameter(Position=0)]
    [ValidateSet("status", "shutdown", "reboot", "compact", "backup", "help")]
    [string]$Action = "status"
)

switch ($Action) {
    "status" {
        Write-Host "==> WSL2 Distributions:" -ForegroundColor Cyan
        wsl -l -v
        Write-Host "`n==> vmmem Memory Usage:" -ForegroundColor Cyan
        Get-Process -Name "vmmem*" -ErrorAction SilentlyContinue | 
            Select-Object Name, @{Name="RAM_MB";Expression={[math]::Round($_.WorkingSet64/1MB, 2)}}
    }
    "shutdown" {
        Write-Host "==> Shutting down WSL2 virtual machine..." -ForegroundColor Yellow
        wsl --shutdown
        Write-Host "WSL2 terminated." -ForegroundColor Green
    }
    "reboot" {
        Write-Host "==> Rebooting WSL2..." -ForegroundColor Yellow
        wsl --shutdown
        wsl -e echo "WSL2 restarted."
    }
    "backup" {
        $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
        $backupFile = "$HOME\wsl_backup_ubuntu_$timestamp.tar"
        Write-Host "==> Exporting Ubuntu WSL2 distro to $backupFile..." -ForegroundColor Cyan
        wsl --export Ubuntu-24.04 $backupFile
        Write-Host "Backup completed!" -ForegroundColor Green
    }
    Default {
        Write-Host "Usage: wsl-dev [status | shutdown | reboot | compact | backup]"
    }
}
```

---

## 💾 Data Persistence, Volume Management & Backups

### Exporting & Backing Up an Entire WSL2 Distro
To create an immutable snapshot before major experiments or OS upgrades:

```powershell
# Shut down WSL instances
wsl --shutdown

# Export entire distribution to a tarball archive
wsl --export Ubuntu-24.04 C:\Backups\ubuntu-24-04-snapshot.tar

# Restore distribution if needed:
# wsl --import Ubuntu-Restored C:\WSL\Ubuntu C:\Backups\ubuntu-24-04-snapshot.tar
```

---

## 🐛 Troubleshooting, Clock Drift & Port Conflicts

### 1. Clock Drift Bug (Time Desync on Sleep/Wake)
When your laptop sleeps, the WSL2 hardware clock can fall behind, causing Git SSL authentication failures and TLS errors:

```bash
# Sync WSL2 clock with hardware clock
sudo hwclock -s

# Or restart time-sync daemon
sudo systemctl restart systemd-timesyncd
```

### 2. Localhost Port Binding Issues
If a container port mapped to `3000` is unreachable from a Windows browser at `http://localhost:3000`:
```powershell
# In Windows PowerShell, check if Fast Startup or Hyper-V reserved the port:
netsh interface ipv4 show excludedportrange protocol=tcp

# Fast workaround: find WSL2 internal IP and connect directly
wsl hostname -I
# Then browse to: http://<WSL_IP>:3000
```

---

## 🧹 Maintenance & WSL2 Disk Compaction

WSL2 virtual hard disks (`ext4.vhdx`) grow as you pull Docker images, but do not shrink automatically when containers are deleted unless using `sparseVhd`:

```powershell
# 1. Clean Docker objects inside WSL
wsl -e docker system prune -a --volumes -f

# 2. Shut down WSL
wsl --shutdown

# 3. Compact VHDX using diskpart (Windows)
# Locate ext4.vhdx under:
# %LOCALAPPDATA%\Packages\CanonicalGroupLimited...\LocalState\ext4.vhdx
$vhdx = (Get-ChildItem -Path "$env:LOCALAPPDATA\Packages" -Filter "ext4.vhdx" -Recurse).FullName

diskpart
# Inside DISKPART prompt:
# select vdisk file="<PATH_TO_EXT4.VHDX>"
# attach vdisk readonly
# compact vdisk
# detach vdisk
# exit
```
