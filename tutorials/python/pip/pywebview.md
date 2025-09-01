# PyWebView Desktop Application Tutorial

Here's a complete tutorial showing how to create desktop applications using Python and PyWebView.

## Installation

First, install the required package:

```bash
pip install pywebview
```

## Basic Example - Simple Web App

Let's start with the most basic example:

**basic_app.py**
```python
import webview

# Create a simple window with HTML content
if __name__ == '__main__':
    # Simple HTML string
    html = """
    <!DOCTYPE html>
    <html>
        <head>
            <title>My First PyWebView App</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { color: #3366cc; }
                button { background-color: #4CAF50; border: none; color: white; padding: 15px 32px; 
                        text-align: center; font-size: 16px; margin: 4px 2px; cursor: pointer; }
            </style>
        </head>
        <body>
            <h1>Welcome to PyWebView!</h1>
            <p>This is a simple desktop application created with Python and HTML.</p>
            <button onclick="alert('Hello from Python!')">Click Me</button>
        </body>
    </html>
    """
    
    # Create the window
    webview.create_window('My First App', html=html)
    webview.start()
```

## Advanced Example - Calculator Application

Here's a more complete example with interactive features:

**calculator_app.py**
```python
import webview
import json

class CalculatorAPI:
    def __init__(self):
        self.history = []
    
    def add(self, a, b):
        result = float(a) + float(b)
        self.history.append(f"{a} + {b} = {result}")
        return result
    
    def subtract(self, a, b):
        result = float(a) - float(b)
        self.history.append(f"{a} - {b} = {result}")
        return result
    
    def multiply(self, a, b):
        result = float(a) * float(b)
        self.history.append(f"{a} × {b} = {result}")
        return result
    
    def divide(self, a, b):
        if float(b) == 0:
            return "Error: Division by zero"
        result = float(a) / float(b)
        self.history.append(f"{a} ÷ {b} = {result}")
        return result
    
    def get_history(self):
        return self.history

# Create the calculator HTML interface
calculator_html = """
<!DOCTYPE html>
<html>
<head>
    <title>PyWebView Calculator</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .calculator {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #3366cc;
        }
        .display {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: right;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        button {
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .number { background-color: #f0f0f0; }
        .operator { background-color: #3366cc; color: white; }
        .equals { background-color: #4CAF50; color: white; }
        .clear { background-color: #ff4d4d; color: white; }
        .history {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <h1>PyWebView Calculator</h1>
        <input type="text" id="display" class="display" readonly>
        
        <div class="buttons">
            <button onclick="clearDisplay()" class="clear">C</button>
            <button onclick="appendToDisplay('/')">/</button>
            <button onclick="appendToDisplay('*')">×</button>
            <button onclick="appendToDisplay('-')">-</button>
            
            <button onclick="appendToDisplay('7')" class="number">7</button>
            <button onclick="appendToDisplay('8')" class="number">8</button>
            <button onclick="appendToDisplay('9')" class="number">9</button>
            <button onclick="appendToDisplay('+')" class="operator">+</button>
            
            <button onclick="appendToDisplay('4')" class="number">4</button>
            <button onclick="appendToDisplay('5')" class="number">5</button>
            <button onclick="appendToDisplay('6')" class="number">6</button>
            <button onclick="calculate()" class="equals">=</button>
            
            <button onclick="appendToDisplay('1')" class="number">1</button>
            <button onclick="appendToDisplay('2')" class="number">2</button>
            <button onclick="appendToDisplay('3')" class="number">3</button>
            <button onclick="appendToDisplay('.')">.</button>
            
            <button onclick="appendToDisplay('0')" class="number" style="grid-column: span 2;">0</button>
        </div>
        
        <div id="history" class="history">
            <h3>History:</h3>
            <ul id="historyList"></ul>
        </div>
    </div>

    <script>
        let currentInput = '';
        const display = document.getElementById('display');
        const historyList = document.getElementById('historyList');

        function appendToDisplay(value) {
            if (value === '=') return;
            currentInput += value;
            updateDisplay();
        }

        function clearDisplay() {
            currentInput = '';
            updateDisplay();
        }

        function updateDisplay() {
            display.value = currentInput;
        }

        function calculate() {
            try {
                // Replace × and ÷ with * and / for evaluation
                const expression = currentInput.replace(/×/g, '*').replace(/÷/g, '/');
                const result = eval(expression);
                
                // Update history (this would normally be done via Python API)
                updateHistory(currentInput + ' = ' + result);
                
                currentInput = result.toString();
                updateDisplay();
            } catch (error) {
                display.value = 'Error';
            }
        }

        function updateHistory(entry) {
            const li = document.createElement('li');
            li.textContent = entry;
            historyList.appendChild(li);
            
            // Keep only last 5 entries
            if (historyList.children.length > 5) {
                historyList.removeChild(historyList.firstChild);
            }
        }

        // Initialize the calculator
        clearDisplay();
    </script>
</body>
</html>
"""

def main():
    # Create calculator API instance
    calc_api = CalculatorAPI()
    
    # Create window with HTML and API
    window = webview.create_window(
        'PyWebView Calculator', 
        html=calculator_html,
        js_api=calc_api,
        width=450,
        height=600
    )
    
    # Start the application
    webview.start()

if __name__ == '__main__':
    main()
```

## Complete Example - File Explorer Application

Here's a more complex example that demonstrates file operations:

**file_explorer.py**
```python
import webview
import os
import json
from pathlib import Path

class FileExplorerAPI:
    def __init__(self):
        self.current_path = str(Path.home())
    
    def get_files(self, path=None):
        """Get list of files and directories in a path"""
        if path is None:
            path = self.current_path
        
        try:
            items = []
            for item in os.listdir(path):
                full_path = os.path.join(path, item)
                stat = os.stat(full_path)
                
                file_info = {
                    'name': item,
                    'path': full_path,
                    'is_dir': os.path.isdir(full_path),
                    'size': stat.st_size,
                    'modified': stat.st_mtime
                }
                items.append(file_info)
            
            # Sort directories first, then files
            items.sort(key=lambda x: (not x['is_dir'], x['name'].lower()))
            return items
            
        except Exception as e:
            return [{'name': f'Error: {str(e)}', 'path': '', 'is_dir': False}]
    
    def change_directory(self, path):
        """Change current directory"""
        if os.path.exists(path) and os.path.isdir(path):
            self.current_path = path
            return True
        return False
    
    def get_current_path(self):
        """Get current working directory"""
        return self.current_path
    
    def open_file(self, file_path):
        """Open a file with default application"""
        try:
            if os.name == 'nt':  # Windows
                os.startfile(file_path)
            elif os.name == 'posix':  # macOS/Linux
                os.system(f'xdg-open "{file_path}"')
            return True
        except Exception as e:
            return False

# HTML interface for the file explorer
html_interface = '''
<!DOCTYPE html>
<html>
<head>
    <title>PyWebView File Explorer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #path-bar { background: #f0f0f0; padding: 10px; border-radius: 5px; }
        .file-item { 
            padding: 8px; 
            border-bottom: 1px solid #eee; 
            cursor: pointer;
        }
        .file-item:hover { background-color: #e9e9e9; }
        .directory { color: blue; font-weight: bold; }
        .file { color: black; }
    </style>
</head>
<body>
    <h1>PyWebView File Explorer</h1>
    
    <div id="path-bar">Current Path: <span id="current-path"></span></div>
    
    <div id="files-container">
        <!-- Files will be loaded here -->
    </div>

    <script>
        let currentPath = '';

        function loadFiles(path) {
            window.pywebview.api.get_files(path).then(function(items) {
                displayFiles(items);
                if (path) {
                    currentPath = path;
                } else {
                    window.pywebview.api.get_current_path().then(function(path) {
                        currentPath = path;
                        document.getElementById('current-path').textContent = path;
                    });
                }
            });
        }

        function displayFiles(items) {
            const container = document.getElementById('files-container');
            
            // Add back button for parent directory
            if (currentPath && currentPath !== '/') {
                const backButton = document.createElement('div');
                backButton.className = 'file-item directory';
                backButton.innerHTML = '<strong>.. (Parent Directory)</strong>';
                backButton.onclick = function() {
                    const parentDir = currentPath.substring(0, currentPath.lastIndexOf('/'));
                    loadFiles(parentDir || '/');
                };
                container.appendChild(backButton);
            }

            items.forEach(function(item) {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'file-item';
                
                if (item.is_dir) {
                    itemDiv.className += ' directory';
                    itemDiv.innerHTML = '<strong>' + item.name + '</strong>';
                    itemDiv.onclick = function() {
                        loadFiles(item.path);
                    };
                } else {
                    itemDiv.className += ' file';
                    itemDiv.textContent = item.name;
                    itemDiv.onclick = function() {
                        window.pywebview.api.open_file(item.path).catch(function(e) {
                            alert('Could not open file: ' + e.message);
                        });
                    };
                }
                
                container.appendChild(itemDiv);
            });
        }

        // Load files when page loads
        loadFiles();

        // Listen for API calls from Python
        window.pywebview.api.change_directory = function(path) {
            return new Promise((resolve, reject) => {
                if (window.pywebview.api.change_directory(path)) {
                    loadFiles(path);
                    resolve(true);
                } else {
                    reject(new Error('Could not change directory'));
                }
            });
        };
    </script>
</body>
</html>
'''

def main():
    # Create API instance
    file_api = FileExplorerAPI()
    
    # Create window with the HTML interface and API
    window = webview.create_window(
        'PyWebView File Explorer',
        html=html_interface,
        js_api=file_api,
        width=800,
        height=600
    )
    
    # Start the application
    webview.start()

if __name__ == '__main__':
    main()
```

## Usage Instructions

1. **Install PyWebView**:
   ```bash
   pip install pywebview
   ```

2. **Run any of the examples**:
   ```bash
   python calculator.py
   # or
   python file_explorer.py
   ```

3. **Features demonstrated**:
   - HTML/CSS/JavaScript interface with Python backend integration
   - File system operations (in the explorer example)
   - Interactive UI components with event handling
   - Cross-platform compatibility (Windows, macOS, Linux)

## Key Features

- **JavaScript API**: Access to Python functions from JavaScript using `js_api`
- **Cross-platform**: Works on Windows, macOS, and Linux
- **Native Look & Feel**: Integrates with the native OS windowing system
- **Security**: Sandboxed environment for web content
- **Lightweight**: Minimal dependencies required

Each example demonstrates different aspects of PyWebView's capabilities:
1. The calculator shows basic integration between HTML UI and Python backend
2. The file explorer demonstrates more complex interactions and file operations
3. All examples use the same core principle: HTML interface with Python API access

Choose the example that best fits your needs or modify them to create custom applications!