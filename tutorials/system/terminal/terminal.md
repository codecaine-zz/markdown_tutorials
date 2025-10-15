## Table of Contents
1. [Basic Navigation & Commands](#basic-navigation--commands)
2. [File and Directory Operations](#file-and-directory-operations)
3. [Text Processing](#text-processing)
4. [Process Management](#process-management)
5. [Networking & System Information](#networking--system-information)
6. [Advanced Shell Features](#advanced-shell-features)

## Basic Navigation & Commands

### Opening Terminal
```
Keyboard Shortcut: Cmd + Space → Type "Terminal" → Enter
```

### Essential Navigation Commands
```bash
# Navigate to home directory (most important command)
cd ~

# Show current working directory
pwd

# List files in current directory
ls

# List all files including hidden ones
ls -a

# Detailed file listing with permissions, size, and dates
ls -la

# Change directories
cd /path/to/directory
cd ..

# Navigate back to previous directory (very useful!)
cd -
```

### Keyboard Shortcuts for Navigation
```bash
# Ctrl + A: Move to beginning of line
# Ctrl + E: Move to end of line  
# Ctrl + U: Cut from cursor to beginning of line
# Ctrl + K: Cut from cursor to end of line
# Ctrl + W: Delete word before cursor
```

### Clearing Screen and History
```bash
# Clear terminal screen (like pressing 'Clear')
clear

# Clear history and start fresh (not recommended for regular use)
history -c

# View command history with numbers
history

# Search through history using up/down arrows
# Ctrl + R: Reverse search through history
```

## File and Directory Operations

### Creating Files & Directories
```bash
# Create directory
mkdir myproject

# Create multiple directories at once (including nested)
mkdir -p project/{src,docs,tests}

# Create empty file
touch filename.txt

# Create file with content using echo
echo "Hello World" > myfile.txt

# Create file and add content in one command
cat > newfile.txt << EOF
Line 1 of content
Line 2 of content  
Line 3 of content
EOF
```

### Copying, Moving & Deleting Files
```bash
# Copy file or directory (recursive for directories)
cp source.txt destination.txt

# Copy directory recursively with all contents
cp -r source_directory/ destination_directory/

# Move/rename files/directories
mv old_name.txt new_name.txt

# Delete file (be careful!)
rm filename.txt

# Delete directory and everything inside it
rm -rf directory_name/

# Remove empty directories only
rmdir empty_directory/
```

### File Permissions & Attributes
```bash
# View file permissions, owner, size, date/time
ls -l filename.txt

# Change file permissions (e.g., make executable)
chmod 755 script.sh

# Change ownership of files
sudo chown user:group filename.txt

# Make a directory writeable for all users (dangerous!)
chmod 777 directory_name/
```

### File Content Operations
```bash
# View file content (one page at a time)
less filename.txt

# View entire file in terminal (be careful with large files!)
cat filename.txt

# Show last 10 lines of file
tail filename.txt

# Show first 10 lines of file  
head filename.txt

# Quick search within files
grep "pattern" filename.txt

# Search recursively through all subdirectories
grep -r "pattern" .
```

### Keyboard Shortcuts for File Operations
```bash
# Tab: Auto-complete filenames and commands (most important!)
# Ctrl + C: Cancel current command/operation  
# Ctrl + Z: Suspend current process (useful with 'fg' to resume)
# Ctrl + L: Clear screen (like 'clear' command)

# Navigation in less viewer:
# Space: Next page
# b: Previous page  
# /pattern: Search forward
# ?pattern: Search backward
```

## Text Processing

### Advanced File Manipulation
```bash
# Create a sample text file with data
cat > sample.txt << EOF
apple,banana,cherry
date,elderberry,fig
grape,honeydew,jackfruit  
EOF

# Cut specific fields from CSV (1st and 3rd columns)
cut -d',' -f1,3 sample.txt

# Sort lines alphabetically or numerically
sort sample.txt

# Sort by second column (numeric sort)
sort -k2 -n datafile.txt  

# Remove duplicate lines
uniq file.txt

# Count lines, words, and characters
wc sample.txt

# Replace text in files using sed
sed 's/old_text/new_text/g' input.txt > output.txt

# Print only specific lines (first 5 lines)
head -5 filename.txt

# Print from line 10 to end of file  
tail -n +10 filename.txt
```

### Advanced Text Processing with Pipes
```bash
# Chain multiple commands together using pipes
ls -la | grep ".txt" | wc -l

# Find files and display their content in context  
find . -name "*.log" -exec cat {} \; | grep "ERROR"

# Process output through awk for pattern matching
ps aux | awk '{print $2, $11}'  # Show PID and command name

# Count occurrences of words in file
cat filename.txt | tr ' ' '\n' | sort | uniq -c | sort -nr
```

### Editing with Vi/Vim (Built-in Text Editor)
```bash
# Open file in vi editor  
vi filename.txt

# In vi editor:
# i: Enter insert mode
# Esc: Exit insert mode  
# :w: Save changes
# :q: Quit 
# :q!: Quit without saving
```

### Keyboard Shortcuts for Text Processing
```bash
# Ctrl + R: Reverse search in command history (search backwards)
# Ctrl + G: Cancel reverse search (when using Ctrl+R)  
# Ctrl + A/E: Move cursor to start/end of line
# Ctrl + U/K: Cut from cursor to beginning/end of line

# In vi editor:
# dd: Delete current line
# u: Undo last action
# :wq: Save and quit
```

## Process Management

### Viewing Running Processes
```bash
# Show all running processes  
ps aux

# View process tree (hierarchical view)
pstree

# Search for specific processes by name
ps aux | grep python

# See memory usage sorted from highest to lowest
ps aux --sort=-%mem | head -10

# Show CPU usage sorted from highest to lowest  
ps aux --sort=-%cpu | head -10
```

### Managing Processes
```bash
# Kill process by PID (be careful!)
kill 1234

# Force kill a process (SIGKILL)
kill -9 1234

# Graceful shutdown of process (SIGTERM)
kill -15 1234

# Kill all processes with specific name
pkill python

# List and kill by pattern
ps aux | grep "pattern" | awk '{print $2}' | xargs kill
```

### Process Monitoring
```bash
# Monitor system resources in real-time (like Activity Monitor)
top

# Alternative resource monitor (more detailed than top)  
htop

# Watch disk usage in real time  
iostat -x 1

# Monitor network connections
netstat -an | grep LISTEN

# Show current network activity with more detail
ss -tuln
```

### Keyboard Shortcuts for Process Management
```bash
# Ctrl + C: Interrupt current running command (most important!)
# Ctrl + Z: Suspend current process (use 'jobs' to see suspended jobs)
# Ctrl + D: End-of-file signal (exit terminal or close stdin)

# In top/htop:
# k: Kill selected process  
# r: Renice process
# q: Quit program

# Job control in shell:
# jobs: List background jobs
# fg %1: Bring job 1 to foreground
# bg %2: Resume job 2 in background
```

## Networking & System Information

### Network Commands
```bash
# Show network interface configuration (IP addresses)
ifconfig

# Test connection to a server  
ping google.com

# Test if port is open on remote server
telnet example.com 80  

# View routing table
route -n

# Show active connections and listening ports
netstat -tuln

# Display network statistics  
netstat -i

# Get DNS information for domain
nslookup google.com

# Find your public IP address  
curl ifconfig.me

# Test internet speed (requires speedtest-cli)
speedtest-cli --simple
```

### System Information Commands
```bash
# Show system information (CPU, memory, OS version)  
uname -a

# Display memory usage
free -h

# Show disk space usage in human-readable format  
df -h

# List currently mounted filesystems
mount | grep "on / type"

# View system load average
uptime

# Show detailed hardware information  
system_profiler SPHardwareDataType

# Check current user and groups
whoami && groups

# Display time and date with timezone info
date +'%Y-%m-%d %H:%M:%S %Z'
```

### Remote Access & SSH
```bash
# Connect to remote server via SSH (default port 22)
ssh username@hostname

# Connect using specific port  
ssh -p 2222 user@server.com

# Copy file between local and remote machine
scp file.txt user@remote:/path/

# Secure copy with compression
scp -C filename user@host:~/

# Create SSH key pair (for passwordless login)
ssh-keygen -t rsa -b 4096
```

### Firewall & Security Commands
```bash
# Show current firewall rules (macOS)
sudo pfctl -sr

# View listening ports and associated processes  
lsof -iTCP -sTCP:LISTEN

# Scan for open ports on local machine
nmap localhost  

# Check who is logged into system right now  
who

# See login history for user
last username
```

### Keyboard Shortcuts for Networking/Security
```bash
# Ctrl + L: Clear terminal screen (when stuck)
# Ctrl + T: Move cursor to beginning of line 
# Tab completion: Auto-complete commands and filenames
# Alt + F/B: Move forward/backward by word in command line

# In SSH sessions:
# ~.: Escape sequence for SSH commands (e.g., ~C for new connection)

# Quick system shutdown/reboot:
# sudo shutdown now
# sudo reboot  
```

## Essential Tips & Tricks

### Shell Customization
```bash
# Add custom aliases to ~/.bashrc or ~/.zshrc
alias ll='ls -la'
alias grep='grep --color=auto'
alias ..='cd ..'

# Create persistent environment variables  
export MYVAR="value"

# Set terminal prompt color (in ~/.bashrc)
PS1='\[\e[0;32m\]\u@\h:\[\e[0;36m\]\w\$ \[\e[0m\]'

# Make changes take effect
source ~/.bashrc
```

### File and Directory Management
```bash
# Create directory tree structure  
mkdir -p dir1/subdir1/subdir2

# Find files matching pattern (case insensitive)  
find . -iname "*.txt"

# Copy file with timestamp preservation  
cp --preserve=timestamps source.txt target.txt

# Move multiple files into directory  
mv file1.txt file2.txt dir/

# Create symbolic link
ln -s /path/to/original filename

# Remove all files in directory but keep the folder itself
rm -rf /path/to/dir/*
```

### Performance Optimization
```bash
# Clear command history to free memory (use carefully)
history -c && history -w

# Reduce shell startup time by using faster alternatives  
which bash  # Check if zsh or bash is being used

# Use hash for frequently accessed executables 
hash ls

# Monitor disk I/O performance
iostat -x 1

# Optimize disk space with cleanup commands  
sudo apt autoremove && sudo apt autoclean
```

### Common Error Handling
```bash
# Check exit status of last command
echo $?

# Handle errors in bash script  
if [ $? -ne 0 ]; then echo "Command failed"; fi

# Use trap for error handling in scripts 
trap 'echo "Error occurred at line $LINENO"' ERR

# Verify if file exists before operating on it
if [ -f filename.txt ]; then cat filename.txt; fi
```

This comprehensive guide covers essential terminal commands and practices across all major areas of system administration, development workflows, networking, process management, and security. The examples are designed to be practical and immediately useful for both beginners and intermediate users working with Linux/Unix systems or macOS terminals. Remember that many of these commands can have different options depending on your specific operating system (Linux vs macOS), so always check the man pages (`man command`) for detailed information about usage variations.

Would you like me to explain any particular section in more detail, provide additional examples, or show how to customize any of these commands further? The terminal is one of the most powerful tools available for interacting with your computer - mastering these basics will significantly improve productivity!

