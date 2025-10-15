# Git Installation and Configuration Tutorial

Here are complete tutorial code examples for installing Git using Homebrew on macOS:

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installing Git with Homebrew](#installing-git-with-homebrew)
3. [Post-Installation Configuration](#post-installation-configuration)
4. [Verification Commands](#verification-commands)
5. [Troubleshooting Examples](#troubleshooting-examples)
6. [Advanced Configuration Examples](#advanced-configuration-examples)
7. [Complete Installation Script](#complete-installation-script)

## Prerequisites

First, ensure you have Homebrew installed:
```bash
# Check if Homebrew is installed
brew --version

# If not installed, install Homebrew first
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

## Installing Git with Homebrew

### Basic Installation
```bash
# Install Git
brew install git

# Verify installation
git --version
```

### Complete Setup Example
```bash
#!/bin/bash

echo "Starting Git installation with Homebrew..."

# Check if Homebrew is installed
if ! command -v brew &> /dev/null; then
    echo "Homebrew not found. Installing Homebrew..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
fi

# Update Homebrew
echo "Updating Homebrew..."
brew update

# Install Git
echo "Installing Git..."
brew install git

# Verify installation
echo "Verifying Git installation..."
git --version

# Check if Git is properly installed in PATH
which git

echo "Git installation complete!"
```

## Post-Installation Configuration

### Configure Git User Settings
```bash
# Set your name (replace with your actual name)
git config --global user.name "Your Name"

# Set your email (replace with your actual email)
git config --global user.email "your.email@example.com"

# Verify configuration
git config --global --list
```

### Configure Git Preferences
```bash
# Set default editor (optional, replace with your preferred editor)
git config --global core.editor "nano"  # or "vim", "code", etc.

# Enable color output
git config --global color.ui auto

# Set default branch name (Git 2.28+)
git config --global init.defaultBranch main

# Configure alias for common commands
git config --global alias.st status
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
```

## Verification Commands

### Test Git Installation
```bash
# Check Git version
git --version

# Check if Git is in PATH
which git

# Verify Git configuration
git config --list

# Test basic Git functionality
git help
```

## Troubleshooting Examples

### If Installation Fails
```bash
# Clean Homebrew cache and reinstall
brew cleanup
brew update
brew install git

# Check for conflicts or issues
brew doctor

# Install specific version if needed (if available)
brew install git@2.39  # Example: installing specific version
```

### If PATH Issues Occur
```bash
# Add Homebrew to PATH (if needed)
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc

# Reload shell configuration
source ~/.zshrc

# Verify Git is now accessible
which git
```

## Advanced Configuration Examples

### SSH Key Setup (after Git installation)
```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "your.email@example.com"

# Start ssh-agent in background
eval "$(ssh-agent -s)"

# Add your SSH private key to the ssh-agent
ssh-add ~/.ssh/id_rsa

# Copy public key to clipboard (macOS)
pbcopy < ~/.ssh/id_rsa.pub

echo "Now add this key to your GitHub/GitLab account"
```

### Git Aliases and Customization
```bash
# Create useful aliases
git config --global alias.lg "log --color --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit"

git config --global alias.stash-all "stash save 'auto-stash: $(date)'"

git config --global alias.undo "reset --soft HEAD~1"

# Configure Git to use specific merge tool
git config --global merge.tool vimdiff

# Set up autocrlf for Windows compatibility (if needed)
git config --global core.autocrlf true  # For Windows
git config --global core.autocrlf false # For Mac/Linux
```

## Complete Installation Script

```bash
#!/bin/bash

# Comprehensive Git installation script with Homebrew
set -e  # Exit on any error

echo "=== Git Installation Script ==="

# Check if running on macOS
if [[ "$OSTYPE" != "darwin"* ]]; then
    echo "Error: This script is designed for macOS only."
    exit 1
fi

# Install Homebrew if not present
if ! command -v brew &> /dev/null; then
    echo "Installing Homebrew..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
fi

# Update Homebrew and install Git
echo "Updating Homebrew..."
brew update

echo "Installing Git..."
brew install git

# Verify installation
echo "Verifying installation..."
if command -v git &> /dev/null; then
    echo "Git version: $(git --version)"
    echo "Git location: $(which git)"
else
    echo "Error: Git installation failed"
    exit 1
fi

# Configure basic settings (customizable)
echo "Configuring Git..."
read -p "Enter your name for Git commits: " username
read -p "Enter your email for Git commits: " useremail

git config --global user.name "$username"
git config --global user.email "$useremail"

echo "Git is now installed and configured!"
echo ""
echo "Next steps:"
echo "- Configure SSH keys if needed"
echo "- Set up your preferred editor with 'git config --global core.editor'"
echo "- Explore Git documentation: 'git help' or 'man git'"
```

## Usage Examples After Installation

```bash
# Basic Git operations (after installation)
mkdir my-project
cd my-project
git init
git add .
git commit -m "Initial commit"

# Clone a repository
git clone https://github.com/user/repo.git

# Check status
git status

# View commit history
git log --oneline

# Create and switch branches
git branch feature-branch
git checkout feature-branch

# Merge branches (example)
git checkout main
git merge feature-branch
```

This comprehensive tutorial covers everything from basic installation to advanced configuration, ensuring you have a fully functional Git setup on macOS using Homebrew.

