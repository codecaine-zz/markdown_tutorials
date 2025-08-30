# fd - Fast File Search Tutorial

Here are code examples and usage patterns for `fd` installed via Homebrew:

## Basic Installation

```bash
# Install fd using Homebrew
brew install fd

# Verify installation
fd --version
```

## Common Usage Examples

### Find files by name pattern
```bash
# Find all .txt files
fd .txt

# Find files starting with "config"
fd "^config"

# Find files ending with ".js"
fd "\\.js$"
```

### Search in specific directories
```bash
# Search only in current directory
fd -d 1 "*.py"

# Search in a specific folder
fd "pattern" /path/to/directory

# Search recursively from current directory
fd -R "pattern"
```

### Filter by file type and other criteria
```bash
# Find only files (not directories)
fd -f "pattern"

# Find only directories
fd -d "pattern"

# Case insensitive search
fd -i "pattern"

# Exclude hidden files
fd -H "pattern"
```

### Advanced examples with options

```bash
# Find files modified in last 7 days
fd --type f --changed-within 7d

# Find large files (>10MB)
fd --type f --size +10M

# Limit results to 5 matches
fd -l 5 "pattern"

# Show full paths with colors
fd -p -c always "pattern"
```

## Practical Examples

### Finding configuration files
```bash
# Find all config files in home directory
fd -d 2 "\\.conf$" ~/

# Find .env files
fd ".env" --type f
```

### Code file searching
```bash
# Find JavaScript files with specific content
fd -e js "function" src/

# Find Python test files
fd "test_.*\\.py"
```

## Installation Verification

```bash
# Check if fd is installed
which fd

# View help
fd --help

# See version info
fd --version
```

The `fd` tool is a faster alternative to `find` with a cleaner, more intuitive syntax and better default behavior.

