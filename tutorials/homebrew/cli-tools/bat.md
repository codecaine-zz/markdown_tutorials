# Complete Tutorial: Installing and Using `bat` with Homebrew

## Table of Contents

1. [Installing `bat` using Homebrew](#1-installing-bat-using-homebrew)
2. [Basic Usage Examples](#2-basic-usage-examples)
3. [Advanced Configuration Examples](#3-advanced-configuration-examples)
4. [Integration Examples](#4-integration-examples)
5. [Complete Working Example Script](#5-complete-working-example-script)
6. [Troubleshooting Examples](#6-troubleshooting-examples)
7. [Complete Installation Verification Script](#7-complete-installation-verification-script)

## 1. Installing `bat` using Homebrew

```bash
# Install bat using Homebrew (if not already installed)
brew install bat

# Or if you want to install from HEAD (latest development version)
brew install --HEAD bat
```

## 2. Basic Usage Examples

### View file contents with syntax highlighting
```bash
# Simple file viewing
bat README.md

# View multiple files at once
bat src/*.js

# View file with line numbers and colored output
bat --number-lines --color=always app.py
```

### File type detection and syntax highlighting
```bash
# Force specific language (useful for files without extensions)
bat --language=javascript script.js

# Show all supported languages
bat --list-languages

# View file with auto-detection of language from extension
bat document.txt
```

## 3. Advanced Configuration Examples

### Create a custom bat configuration file
```bash
# Create the config directory if it doesn't exist
mkdir -p ~/.config/bat

# Create a basic configuration file
cat > ~/.config/bat/config << 'EOF'
--theme=GitHub
--language=auto
--paging=never
--color=always
--decorations=always
EOF

# Verify the config works
bat --config-file ~/.config/bat/config README.md
```

### Configure bat with custom themes and settings
```bash
# List all available themes
bat --list-themes

# Use a dark theme (like "OneHalfDark")
bat --theme="OneHalfDark" main.cpp

# Disable line numbers but keep syntax highlighting
bat --no-line-numbers script.js

# Show file headers with full paths
bat --file-name="/full/path/to/file.txt" config.ini

# Set custom tab width
bat --tabs=4 source.py
```

## 4. Integration Examples

### Using bat in shell scripts
```bash
#!/bin/bash
# Example script that uses bat for better output formatting

echo "=== Project Structure ==="
bat --file-name="Project Structure" --language=shell <(find . -type f | head -20)

echo ""
echo "=== Recent Changes (last 5 commits) ==="
bat --language=git-commit < <(git log --oneline -5)
```

### Using bat with grep and other tools
```bash
# Search for patterns in files and display results with syntax highlighting
grep -r "function" src/ | xargs bat

# Use bat to highlight grep output (requires custom function)
highlight_grep() {
    local pattern=$1
    local file=$2
    
    if [ -f "$file" ]; then
        echo "=== $file ==="
        grep --color=always "$pattern" "$file" | bat --language=text
    fi
}

# Usage: highlight_grep "TODO" main.py
```

## 5. Complete Working Example Script

```bash
#!/bin/bash
# Complete example script demonstrating various bat features

echo "Installing bat if not already installed..."
brew install bat > /dev/null 2>&1 || echo "bat already installed"

echo ""
echo "Creating sample files for demonstration..."

# Create a Python file with syntax
cat > example.py << 'EOF'
#!/usr/bin/env python3
"""
Example Python script demonstrating various features.
"""

import os
import sys

class DataProcessor:
    def __init__(self):
        self.data = []
        
    def add_item(self, item):
        """Add an item to the data."""
        if isinstance(item, dict):
            self.data.append(item)
            
    def process_data(self):
        """Process the collected data."""
        return [item.get('name', 'Unknown') for item in self.data]

# Main execution
if __name__ == "__main__":
    processor = DataProcessor()
    
    # Sample data
    sample_data = [
        {'id': 1, 'name': 'Alice', 'age': 30},
        {'id': 2, 'name': 'Bob', 'age': 25}
    ]
    
    for item in sample_data:
        processor.add_item(item)
        
    results = processor.process_data()
    print("Processed data:", results)
EOF

# Create a JavaScript file
cat > example.js << 'EOF'
// Example JavaScript code with comments and syntax highlighting
function calculateTotal(items) {
  let total = 0;
  
  items.forEach(item => {
    if (item.price && item.quantity) {
      total += item.price * item.quantity;
    }
  });
  
  return total.toFixed(2);
}

const products = [
  { name: 'Laptop', price: 999.99, quantity: 1 },
  { name: 'Mouse', price: 25.50, quantity: 2 }
];

console.log('Total:', calculateTotal(products));
EOF

echo ""
echo "=== Displaying Python file with bat ==="
bat example.py --theme="OneHalfDark" --language=python

echo ""
echo "=== Displaying JavaScript file with bat ==="
bat example.js --theme="GitHub" --language=javascript

echo ""
echo "=== Showing line numbers and custom tab width ==="
bat example.py --number-lines --tabs=2 --color=always

# Cleanup
rm -f example.py example.js

echo ""
echo "Installation and usage complete!"
```

## 6. Troubleshooting Examples

### Fix common issues
```bash
# If bat doesn't work, check installation
brew doctor

# Reinstall if needed
brew uninstall bat
brew install bat

# Check bat version
bat --version

# Clear cache if there are display issues
bat cache --build

# View help for more options
bat --help
```

### Environment variable setup (optional)
```bash
# Add to your ~/.bashrc or ~/.zshrc
export BAT_THEME="OneHalfDark"
export BAT_PAGER="less -R"

# Reload shell configuration
source ~/.bashrc  # or source ~/.zshrc

# Use with aliases for common operations
alias bcat='bat --color=always'
alias bls='bat --language=shell'
```

## 7. Complete Installation Verification Script

```bash
#!/bin/bash
# Verify bat installation and basic functionality

echo "=== Bat Installation Verification ==="

# Check if bat is installed
if command -v bat &> /dev/null; then
    echo "✓ bat is installed"
    echo "Version: $(bat --version)"
else
    echo "✗ bat is not installed"
    exit 1
fi

# Create test file and verify display
echo "This is a test file" > test.txt
echo "Test content for verification" >> test.txt

echo ""
echo "=== Displaying test file ==="
bat test.txt --color=always

# Test with syntax highlighting (if available)
cat > test.py << 'EOF'
def hello_world():
    print("Hello, World!")
    
if __name__ == "__main__":
    hello_world()
EOF

echo ""
echo "=== Python file display ==="
bat test.py --language=python

# Cleanup
rm -f test.txt test.py

echo ""
echo "=== Verification Complete ==="
echo "✓ bat installation is working correctly"
```

This complete tutorial provides:

1. **Installation**: How to install `bat` using Homebrew
2. **Basic Usage**: Simple file viewing with syntax highlighting
3. **Advanced Features**: Configuration, themes, language detection
4. **Integration Examples**: Using in scripts and with other tools
5. **Complete Working Examples**: Actual code that demonstrates functionality
6. **Troubleshooting**: Common issues and solutions
7. **Verification Script**: Complete installation verification

The examples cover practical use cases from basic file viewing to advanced configuration, making it suitable for both beginners and experienced users.

