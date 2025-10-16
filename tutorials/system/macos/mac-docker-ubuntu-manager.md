# 🐧 Ubuntu Docker Manager for macOS ARM

**A professional-grade, Bun-powered toolkit for managing persistent Ubuntu containers on Apple Silicon Macs**

> Transform your macOS into a powerful Linux development environment with zero hassle!

## 📍 Repository

**GitHub Repository**: [https://github.com/codecaine-zz/bun_ubuntu_docker_manager_for_arm_mac.git](https://github.com/codecaine-zz/bun_ubuntu_docker_manager_for_arm_mac.git)

---

## 📋 Table of Contents

- [✨ Features](#-features)
- [� Prerequisites Installation](#-prerequisites-installation)
- [�🚀 Quick Start](#-quick-start)
- [📖 Commands Reference](#-commands-reference)
- [⚙️ Configuration](#️-configuration)
- [💾 Data Persistence](#-data-persistence)
- [🔧 Advanced Usage](#-advanced-usage)
- [🏃‍♂️ Real-World Examples](#️-real-world-examples)
- [🐛 Troubleshooting](#-troubleshooting)
- [📁 Project Structure](#-project-structure)
- [🚀 What's New](#-whats-new)

---

## ✨ Features

### 🎯 Core Capabilities

- **Persistent Ubuntu Environment**: Data survives container restarts
- **Pre-installed Development Stack**: Python 3.14, Node.js LTS, Bun, V language
- **macOS ARM Optimized**: Perfect for Apple Silicon Macs
- **Professional Management**: 14+ commands for complete lifecycle management
- **Zero Configuration**: Works out of the box with sensible defaults

### 🛠 Advanced Features

- **Live Monitoring**: Real-time status updates with `--watch` mode
- **Intelligent Error Handling**: Helpful guidance and auto-recovery
- **Configuration Management**: Customize container names, paths, and behavior
- **Backup & Restore**: Complete data and container state backups
- **Remote Execution**: Run commands inside containers from host
- **Resource Management**: Built-in cleanup and optimization tools

### 🎨 Developer Experience

- **Rich Help System**: Interactive `--help` on every command
- **Debug Mode**: Detailed execution information for troubleshooting
- **Dry Run Mode**: Test commands safely before execution
- **Automation Ready**: Exit codes and compact output for scripts
- **Professional Logging**: Timestamped, colored, leveled output

---

## � Prerequisites Installation

### Installing on ARM Mac (Apple Silicon)

#### 1. Install Homebrew (if not already installed)

```bash
# Install Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Add Homebrew to your PATH (for Apple Silicon Macs)
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zshrc
source ~/.zshrc

# Verify installation
brew --version
```

#### 2. Install Docker Desktop

##### Option A: Using Homebrew (Recommended)

```bash
# Install Docker Desktop via Homebrew
brew install --cask docker

# Start Docker Desktop application
open /Applications/Docker.app
```

##### Option B: Manual Installation

1. Download Docker Desktop for Mac (Apple Chip) from [docker.com](https://www.docker.com/products/docker-desktop/)
2. Install the `.dmg` file
3. Launch Docker Desktop from Applications

##### Verify Docker Installation

```bash
# Wait for Docker to start, then verify
docker --version    # Should show Docker version 20.0+
docker run hello-world  # Test Docker functionality
```

#### 3. Install Bun

##### Option A: Using Homebrew

```bash
# Install Bun via Homebrew
brew install bun

# Verify installation
bun --version      # Should show Bun 1.0+
```

##### Option B: Using Curl

```bash
# Install Bun directly
curl -fsSL https://bun.sh/install | bash

# Add to PATH (if needed)
echo 'export PATH="$HOME/.bun/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc

# Verify installation
bun --version
```

#### 4. Verify All Prerequisites

```bash
# Verify all tools are installed correctly
echo "=== System Check ==="
uname -m              # Should show 'arm64' for Apple Silicon
brew --version        # Homebrew version
docker --version      # Docker version 20.0+
bun --version         # Bun 1.0+
echo "=== All set! 🎉 ==="
```

---

## �🚀 Quick Start

### Final Prerequisites Check

```bash
# Required
✅ macOS (Apple Silicon recommended)
✅ Docker Desktop installed and running
✅ Bun runtime installed

# Quick verification
docker --version    # Docker version 20.0+
bun --version      # Bun 1.0+
```

### Installation

```bash
# Clone the repository
git clone https://github.com/codecaine-zz/bun_ubuntu_docker_manager_for_arm_mac.git
cd bun_ubuntu_docker_manager_for_arm_mac

# Initialize Bun project (if starting fresh)
bun init

# Install dependencies
bun install

# Verify installation
bun run --help
```

### First Use (30 seconds)

```bash
# 1. Create your Ubuntu environment
bun run setup

# 2. Start and enter the container
bun run start

# 3. Inside container - verify everything works
python3 --version  # Python 3.14
node --version     # Node.js LTS
bun --version      # Bun latest
v --version        # V language

# 4. Your persistent workspace
cd /data           # All your files persist here
```

---

## 📖 Commands Reference

### 📦 Core Commands

**`bun run setup`** - Create and setup Ubuntu container with development tools
```bash
bun run setup                    # Default setup
bun run setup --path /custom    # Custom data directory
CONTAINER_NAME=dev bun run setup # Custom container name
```

**`bun run start`** - Start existing Ubuntu container and enter bash shell
```bash
bun run start                    # Start and attach
bun run start --detach          # Start in background
bun run start --attach          # Attach to already running container
```

**`bun run stop`** - Stop running Ubuntu container gracefully
```bash
bun run stop                     # Graceful stop
bun run stop --timeout=30       # Custom timeout
bun run stop --force            # Force stop
```

**`bun run status`** - Check container status and system information
```bash
bun run status                   # Detailed status
bun run status --compact         # Brief status
bun run status --watch           # Live monitoring
```

**`bun run delete`** - Delete container and optionally remove data
```bash
bun run delete                   # Interactive deletion
bun run delete --force          # Skip confirmations
bun run delete --keep-data       # Keep data directory
```

### 🔧 Advanced Commands

**`bun run logs`** - View container logs
```bash
bun run logs                     # Show recent logs
bun run logs --follow            # Follow logs live
bun run logs --tail=100          # Show last 100 lines
```

**`bun run exec`** - Execute commands in container
```bash
bun run exec "python3 --version"
bun run exec "cd /data && ls -la"
bun run exec "npm install express"
```

**`bun run restart`** - Restart container with options
```bash
bun run restart                  # Standard restart
bun run restart --force          # Force restart
bun run restart --timeout=60     # Custom timeout
```

**`bun run backup`** - Backup data directory and container
```bash
bun run backup backup.tar.gz     # Data only
bun run backup --include-container # Full backup
```

**`bun run config`** - Manage configuration
```bash
bun run config show             # Show settings
bun run config set containerName dev
bun run config set dataPath /custom
bun run config toggle autoStart
```

---

## ⚙️ Configuration

### Configuration System

```bash
# View current configuration
bun run config show

# Sample Output:
# Container Name: ubuntu
# Data Path: /Users/codecaine/ubuntu
# Auto Start: false
# Volume Mounts: []
```

### Environment Variables

```bash
# Container customization
CONTAINER_NAME=development bun run setup
DATA_PATH=/custom/workspace bun run start

# Behavior modifiers
DRY_RUN=1 bun run restart     # Test mode
FORCE=1 bun run cleanup       # Skip confirmations
DEBUG=1 bun run status        # Debug info
```

---

## 💾 Data Persistence

### How It Works
```
Your Mac (Host)              Ubuntu Container
===============              =================
~/ubuntu/                    /data/
├── projects/           →    ├── projects/
├── scripts/            →    ├── scripts/
└── config/             →    └── config/
```

### Best Practices
```bash
# Work in the persistent directory
cd /data

# Organize your projects
mkdir -p /data/projects/web-app
mkdir -p /data/scripts/automation

# Regular backups
bun run backup daily-$(date +%Y%m%d).tar.gz
```

---

## 🔧 Advanced Usage

### Development Workflows

**Morning Startup Routine**
```bash
# Check system health
bun run status --watch &

# Start development environment
bun run start

# Inside container
cd /data/current-project
python3 -m venv venv
source venv/bin/activate
```

**Remote Development**
```bash
# Execute commands without entering container
bun run exec "cd /data/web-app && npm start" &
bun run exec "cd /data/api && python3 app.py" &

# Monitor logs
bun run logs --follow
```

### 📦 Managing Linux Packages (APT)

**Installing Additional Packages**
```bash
# Enter your Ubuntu container
bun run start

# Inside container - update package lists first
sudo apt update

# Install single package
sudo apt install htop

# Install multiple packages
sudo apt install git curl wget vim nano tree

# Install with auto-confirmation (useful for scripts)
sudo apt install -y build-essential python3-pip nodejs npm

# Install specific version
sudo apt install python3.11=3.11.0-1

# Search for packages
apt search mysql
apt search "text editor"
```

**Installing Development Tools**
```bash
# Essential development packages
sudo apt install -y \
    build-essential \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg \
    lsb-release

# Database tools
sudo apt install -y mysql-client postgresql-client sqlite3

# Network and system tools
sudo apt install -y \
    net-tools \
    htop \
    neofetch \
    tmux \
    screen \
    jq \
    unzip
```

**Removing Packages**
```bash
# Remove package (keep configuration files)
sudo apt remove package-name

# Remove package and configuration files
sudo apt purge package-name

# Remove package and unused dependencies
sudo apt autoremove package-name

# Remove all unused packages and dependencies
sudo apt autoremove

# Clean package cache
sudo apt autoclean
sudo apt clean
```

**Package Management from Host (Remote Execution)**
```bash
# Install packages without entering container
bun run exec "sudo apt update && sudo apt install -y htop neofetch"

# Remove packages remotely
bun run exec "sudo apt remove --purge old-package"

# Check installed packages
bun run exec "apt list --installed | grep python"

# Update all packages
bun run exec "sudo apt update && sudo apt upgrade -y"
```

**Advanced Package Management**
```bash
# Hold/unhold packages (prevent updates)
sudo apt-mark hold package-name
sudo apt-mark unhold package-name

# Show package information
apt show package-name
apt info package-name

# List all installed packages
apt list --installed

# List upgradeable packages
apt list --upgradeable

# Check package dependencies
apt depends package-name
apt rdepends package-name

# Fix broken packages
sudo apt --fix-broken install
sudo dpkg --configure -a
```

**Adding External Repositories**
```bash
# Add PPA (Personal Package Archive)
sudo add-apt-repository ppa:deadsnakes/ppa
sudo apt update

# Add GPG key and repository manually
wget -qO - https://example.com/key.gpg | sudo apt-key add -
echo "deb https://example.com/repo focal main" | sudo tee /etc/apt/sources.list.d/example.list
sudo apt update

# Install from .deb file
wget https://example.com/package.deb
sudo dpkg -i package.deb
sudo apt --fix-broken install  # Fix dependencies if needed
```

**Package Installation Best Practices**
```bash
# Always update before installing
sudo apt update

# Use -y flag for automated scripts
sudo apt install -y package-name

# Install recommended packages too
sudo apt install --install-recommends package-name

# Simulate installation (dry run)
sudo apt install --dry-run package-name

# Install from specific repository
sudo apt install -t focal-backports package-name
```

**Creating Custom Package Installation Scripts**
```bash
# Create installation script in your data directory
cat > /data/setup-dev-tools.sh << 'EOF'
#!/bin/bash
echo "Installing development tools..."
sudo apt update
sudo apt install -y \
    git \
    curl \
    wget \
    vim \
    htop \
    tree \
    jq \
    build-essential \
    python3-pip \
    nodejs \
    npm

echo "Development tools installed successfully!"
EOF

# Make executable and run
chmod +x /data/setup-dev-tools.sh
/data/setup-dev-tools.sh

# Or run from host
bun run exec "chmod +x /data/setup-dev-tools.sh && /data/setup-dev-tools.sh"
```

---

## 🏃‍♂️ Real-World Examples

### Working with Custom Data Paths

```bash
# IMPORTANT: Data paths are set during container creation, not at start time

# To use custom data paths, specify during setup:
bun run setup --path $HOME/ubuntu-dev
bun run setup --path /Volumes/MyDrive/ubuntu-workspace
bun run setup --path $HOME/projects/my-app/container-data

# Once created, simply start the container:
bun run start

# To change paths for existing container, you must recreate:
bun run delete --keep-data
bun run setup --path /new/path
```

### Python Development

```bash
# Start your Ubuntu container
bun run start
cd /data
mkdir my-python-app && cd my-python-app

# Inside container
python3 -m venv venv
source venv/bin/activate
pip install flask requests pandas

# Run from host
bun run exec "cd /data/my-python-app && source venv/bin/activate && python app.py"
```

### Node.js Development

```bash
# Setup Node.js project
bun run exec "cd /data && npx create-react-app my-react-app"
bun run exec "cd /data/my-react-app && npm install"

# Development server
bun run exec "cd /data/my-react-app && npm start" &
```

---

## 🐛 Troubleshooting

### Common Issues

**Container Won't Start**
```bash
# Check Docker is running
docker info

# View detailed error
DEBUG=1 bun run start

# Recreate container
bun run delete --keep-data
bun run setup
```

**Need to Change Data Path**
```bash
# Data paths cannot be changed after container creation
# To change data path, you must recreate the container:

# 1. Delete container (optionally keep data)
bun run delete --keep-data

# 2. Create new container with desired path
bun run setup --path /new/desired/path

# 3. Migrate old data if needed
cp -r ~/old-ubuntu-path/* ~/new-ubuntu-path/
```

**Permission Issues**
```bash
# Fix data directory permissions
sudo chown -R $(id -u):$(id -g) ~/ubuntu

# Check container user
bun run exec "id && ls -la /data"
```

### Emergency Recovery

```bash
# Complete reset (backup first!)
bun run backup emergency-backup.tar.gz --include-container
docker stop ubuntu 2>/dev/null || true
docker rm ubuntu 2>/dev/null || true
docker system prune -f
bun run setup
```

---

## 📁 Project Structure

```
bun_auto_linux_bsd_docker_for_mac_arm/
├── index.ts                    # Main command router
├── package.json               # Bun scripts and dependencies
├── tsconfig.json              # TypeScript configuration
├── README.md                  # This comprehensive guide
├── scripts/
│   ├── shared/
│   │   ├── utils.ts           # Enhanced utilities & error handling
│   │   └── config.ts          # Configuration management system
│   ├── install-ubuntu-docker-persistent.ts  # Container setup
│   ├── start-ubuntu.ts        # Container startup
│   ├── stop-ubuntu.ts         # Container shutdown
│   ├── delete-ubuntu.ts       # Container deletion
│   ├── attach-ubuntu.ts       # Container attachment
│   ├── enhanced-status.ts     # Detailed status reporting
│   └── advanced-commands.ts   # Advanced feature implementations
```

---

## 🚀 What's New

This manager has evolved from a basic container tool into a **professional-grade development platform**!

### Major Enhancements

| Before | After |
|--------|-------|
| 6 basic commands | 14+ advanced commands with options |
| Generic error messages | Intelligent error handling with solutions |
| Simple text output | Rich, colored, timestamped logging |
| Fixed configuration | Full configuration management system |
| Basic status check | Comprehensive system monitoring |

### New Features

- ✅ Advanced log viewing (`logs --follow --tail=100`)
- ✅ Remote command execution (`exec "python --version"`)
- ✅ Smart container restart (`restart --force --timeout=30`)
- ✅ Complete backup solution (`backup --include-container`)
- ✅ Resource cleanup (`cleanup --all`)
- ✅ Configuration management (`config set/get/toggle`)

### Zero Breaking Changes

All existing commands work exactly the same - just enhanced with more features!

---

## 🎉 Ready to Go!

Your Ubuntu Docker Manager is now a **professional-grade container management platform**!

### Next Steps

1. **Explore**: Try `bun run --help` to see all features
2. **Configure**: Run `bun run config show` to customize settings  
3. **Monitor**: Use `bun run status --watch` for live monitoring
4. **Automate**: Integrate into your CI/CD pipelines

### Pro Tips

- Use `bun run status --compact` for quick health checks
- Set up `bun run backup` as a cron job for automatic backups
- Use `DEBUG=1` when troubleshooting issues
- Create shell aliases for frequently used commands

---

**Built with ❤️ using [Bun](https://bun.com) for blazing-fast container management on macOS**

*Transform your Mac into the ultimate Linux development platform! 🐧✨*