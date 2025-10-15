# Neutralinojs: Complete Guide from Basics to Advanced

A comprehensive copy/paste guide for building desktop applications with Neutralinojs. Each section includes practical code examples for rapid application development (RAD).

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Basic Application Structure](#2-basic-application-structure)
3. [File System Operations](#3-file-system-operations)
4. [Running Subprocesses](#4-running-subprocesses)
5. [Storage and Configuration](#5-storage-and-configuration)
6. [Window Management](#6-window-management)
7. [Notifications and System Integration](#7-notifications-and-system-integration)
8. [Building Custom Extensions](#8-building-custom-extensions)
9. [Advanced Configuration](#9-advanced-configuration)
10. [Production Build and Distribution](#10-production-build-and-distribution)
11. [Helper Utilities (RAD)](#11-helper-utilities-rad)

---

## 1. Getting Started

### Prerequisites (macOS/Linux/Windows)

- Node.js 16+ (LTS recommended)
- A text editor (VS Code recommended)

```bash
# Check Node.js version
node --version

# Install Neutralino CLI globally
npm install -g @neutralinojs/neu

# Verify installation
neu --version
```

### Creating Your First App

```bash
# Create a new Neutralino app
neu create my-neutralino-app

# Navigate to the project
cd my-neutralino-app

# Run in development mode
neu run

# Stop the app (Ctrl+C in terminal)
```

### Project Structure Overview

```text
my-neutralino-app/
├── neutralino.config.json    # Main configuration
├── resources/               # Frontend files
│   ├── index.html          # Main HTML
│   ├── styles.css          # CSS styles
│   └── js/
│       └── main.js         # JavaScript logic
├── extensions/             # Custom extensions
└── dist/                   # Built binaries
```

---

## 2. Basic Application Structure

### Minimal HTML Template

Replace `resources/index.html`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Neutralino App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>My Neutralino Application</h1>
        <div class="buttons">
            <button id="btn-hello">Say Hello</button>
            <button id="btn-info">System Info</button>
            <button id="btn-close">Close App</button>
        </div>
        <div id="output" class="output"></div>
    </div>
    
    <script src="/js/neutralino.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
```

### Basic CSS Styling

Replace `resources/styles.css`:

```css
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #333;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    min-width: 400px;
    text-align: center;
}

h1 {
    color: #4a5568;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

button {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

button:hover {
    background: #5a6fd8;
    transform: translateY(-1px);
}

.output {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    min-height: 100px;
    text-align: left;
    white-space: pre-wrap;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.85rem;
}
```

### Basic JavaScript Setup

Replace `resources/js/main.js`:

```javascript
// Initialize Neutralino
Neutralino.init();

// Get DOM elements
const btnHello = document.getElementById('btn-hello');
const btnInfo = document.getElementById('btn-info');
const btnClose = document.getElementById('btn-close');
const output = document.getElementById('output');

// Utility functions
function displayOutput(text) {
    output.textContent = text;
}

function appendOutput(text) {
' : '') + text;
    const toAdd = (output.textContent ? '\n' : '') + String(text);
    const maxLen = 20000;
    output.textContent = (output.textContent + toAdd).slice(-maxLen);
}

// Event handlers
btnHello.addEventListener('click', () => {
    displayOutput('Hello from Neutralino! 👋');
});

btnInfo.addEventListener('click', async () => {
    try {
        const osInfo = await Neutralino.os.getEnv('OS') || await Neutralino.os.getEnv('OSTYPE') || 'Unknown';
        const userInfo = await Neutralino.os.getEnv('USER') || await Neutralino.os.getEnv('USERNAME') || 'Unknown';
        
        displayOutput(`System Information:
OS: ${osInfo}
User: ${userInfo}
Platform: ${NL_OS}
Architecture: ${NL_ARCH}
Version: ${NL_VERSION}`);
    } catch (error) {
        displayOutput(`Error: ${error.message}`);
    }
});

btnClose.addEventListener('click', () => {
    Neutralino.app.exit();
});

// Handle window close
Neutralino.events.on('windowClose', () => {
    Neutralino.app.exit();
});
```

---

## 3. File System Operations

### File Operations Example

Add this to your `main.js` after the existing code:

```javascript
// File operations
async function createTestFile() {
    try {
        const content = `Test file created at: ${new Date().toISOString()}`;
        await Neutralino.filesystem.writeFile('./test.txt', content);
        appendOutput('✅ File created successfully');
    } catch (error) {
        appendOutput(`❌ Error creating file: ${error.message}`);
    }
}

async function readTestFile() {
    try {
    const content = await Neutralino.filesystem.readFile('./test.txt');
    const preview = content.length > 3000 ? content.slice(0, 3000) + '\n…(truncated)…' : content;
    appendOutput(`📖 File content: ${preview}`);
    } catch (error) {
        appendOutput(`❌ Error reading file: ${error.message}`);
    }
}

async function listCurrentDirectory() {
    try {
        const entries = await Neutralino.filesystem.readDirectory('./');
        appendOutput('📁 Directory contents:');
        entries.forEach(entry => {
            const icon = entry.type === 'DIRECTORY' ? '📂' : '📄';
            appendOutput(`${icon} ${entry.entry}`);
        });
    } catch (error) {
        appendOutput(`❌ Error listing directory: ${error.message}`);
    }
}

// Add buttons to HTML (add this to your HTML button section):
/*
<button id="btn-create-file">Create File</button>
<button id="btn-read-file">Read File</button>
<button id="btn-list-dir">List Directory</button>
*/

// Add event listeners (add after existing event listeners):
document.getElementById('btn-create-file')?.addEventListener('click', createTestFile);
document.getElementById('btn-read-file')?.addEventListener('click', readTestFile);
document.getElementById('btn-list-dir')?.addEventListener('click', listCurrentDirectory);
```

### File Dialog Operations

```javascript
// File dialog operations
async function openFileDialog() {
    try {
        const files = await Neutralino.os.showOpenDialog('Open File', {
            multiSelections: false,
            filters: [
                { name: 'Text files', extensions: ['txt', 'md'] },
                { name: 'All files', extensions: ['*'] }
            ]
        });
        
        if (files && files.length > 0) {
            const content = await Neutralino.filesystem.readFile(files[0]);
            const preview = content.length > 3000 ? content.slice(0, 3000) + '\n…(truncated)…' : content;
            displayOutput(`📖 Opened file: ${files[0]}\n\nContent:\n${preview}`);
        }
    } catch (error) {
        appendOutput(`❌ Error opening file: ${error.message}`);
    }
}

async function saveFileDialog() {
    try {
        const path = await Neutralino.os.showSaveDialog('Save File', {
            filters: [
                { name: 'Text files', extensions: ['txt'] }
            ]
        });
        
        if (path) {
            const content = `Saved at: ${new Date().toISOString()}
Content from Neutralino app!`;
            await Neutralino.filesystem.writeFile(path, content);
            appendOutput(`💾 File saved to: ${path}`);
        }
    } catch (error) {
        appendOutput(`❌ Error saving file: ${error.message}`);
    }
}

// Add to HTML:
/*
<button id="btn-open-file">Open File</button>
<button id="btn-save-file">Save File</button>
*/

// Add event listeners:
document.getElementById('btn-open-file')?.addEventListener('click', openFileDialog);
document.getElementById('btn-save-file')?.addEventListener('click', saveFileDialog);
```

---

## 4. Running Subprocesses

### Basic Subprocess Execution

```javascript
// Subprocess execution utility (escapes args + allowlist)
function shellEscapeArg(arg) {
    if (arg === undefined || arg === null) return '';
    const s = String(arg);
    if (/[\n\r]/.test(s)) throw new Error('Invalid arg with newline');
    if (NL_OS === 'Windows') {
        return '"' + s.replace(/([\^&|<>\"])\/g, '^$1') + '"';
    }
    return '\'' + s.replace(/'/g, `'"'"'`) + '\'';
}

const ALLOWED_CMDS = new Set(['node','bun','python3','uname','systeminfo','git','brew','ping']);

async function runCommand(command, args = []) {
    try {
        if (!ALLOWED_CMDS.has(command)) throw new Error(`Command not allowed: ${command}`);
        const escapedArgs = (args || []).map(shellEscapeArg).join(' ');
        const full = `${command} ${escapedArgs}`.trim();
        displayOutput(`🔄 Running: ${full}`);
        const result = await Neutralino.os.execCommand(full);
        
        appendOutput(`✅ Exit code: ${result.exitCode}`);
        if (result.stdOut) appendOutput(`📤 Output:
${result.stdOut}`);
        if (result.stdErr) appendOutput(`⚠️ Errors:
${result.stdErr}`);
        
        return result;
    } catch (error) {
        appendOutput(`❌ Command failed: ${error.message}`);
        return null;
    }
}

// Node.js operations
async function runNodeCommand() {
    await runCommand('node', ['--version']);
}

async function runNodeScript() {
    const script = [
        'console.log("Hello from Node.js!")',
        'console.log("Current time:", new Date().toISOString())'
    ].join('; ');
    try {
        await Neutralino.filesystem.writeFile('./temp_node_script.js', script + '\n');
        await runCommand('node', ['./temp_node_script.js']);
    } finally {
        try { await Neutralino.filesystem.removeFile('./temp_node_script.js'); } catch (_) {}
    }
}

// Bun operations
async function runBunCommand() {
    try {
        await runCommand('bun', ['--version']);
    } catch (error) {
        appendOutput('❌ Bun not installed. Install from: https://bun.sh');
    }
}

async function runBunScript() {
    const script = [
        'console.log("Hello from Bun!")',
        'console.log("Platform:", process.platform)'
    ].join('; ');
    try {
        await Neutralino.filesystem.writeFile('./temp_bun_script.js', script + '\n');
        await runCommand('bun', ['./temp_bun_script.js']);
    } catch (error) {
        appendOutput('❌ Bun not available');
    } finally {
        try { await Neutralino.filesystem.removeFile('./temp_bun_script.js'); } catch (_) {}
    }
}

// Python operations
async function runPythonCommand() {
    await runCommand('python3', ['--version']);
}

async function runPythonScript() {
    const pythonCode = `
import sys
import platform
print("Hello from Python!")
print(f"Python version: {sys.version}")
print(f"Platform: {platform.platform()}")
    `.trim();
    
    // Create temporary Python file (safer than inline -c)
    try {
        await Neutralino.filesystem.writeFile('./temp_script.py', pythonCode);
        await runCommand('python3', ['temp_script.py']);
        // Cleanup
        await Neutralino.filesystem.removeFile('./temp_script.py');
    } catch (error) {
        appendOutput(`❌ Python execution failed: ${error.message}`);
    }
}

// System operations (macOS/Linux)
async function runSystemInfo() {
    const isWindows = NL_OS === 'Windows';
    if (isWindows) {
        await runCommand('systeminfo');
    } else {
        await runCommand('uname', ['-a']);
    }
}

async function runGitStatus() {
    await runCommand('git', ['--version']);
    await runCommand('git', ['status']);
}

// Homebrew operations (macOS)
async function runBrewInfo() {
    if (NL_OS === 'Darwin') {
        await runCommand('brew', ['--version']);
        const res = await runCommand('brew', ['list', '--versions']);
        if (res && res.stdOut) {
            const top10 = res.stdOut.split(/\r?\n/).slice(0, 10).join('\n');
            appendOutput(`🍺 Installed (first 10):\n${top10}`);
        }
    } else {
        appendOutput('❌ Homebrew is only available on macOS');
    }
}

// Add buttons to HTML:
/*
<div class="subprocess-section">
    <h3>Subprocess Operations</h3>
    <button id="btn-node-version">Node Version</button>
    <button id="btn-node-script">Run Node Script</button>
    <button id="btn-bun-version">Bun Version</button>
    <button id="btn-bun-script">Run Bun Script</button>
    <button id="btn-python-version">Python Version</button>
    <button id="btn-python-script">Run Python Script</button>
    <button id="btn-system-info">System Info</button>
    <button id="btn-git-status">Git Status</button>
    <button id="btn-brew-info">Homebrew Info</button>
</div>
*/

// Add event listeners:
document.getElementById('btn-node-version')?.addEventListener('click', runNodeCommand);
document.getElementById('btn-node-script')?.addEventListener('click', runNodeScript);
document.getElementById('btn-bun-version')?.addEventListener('click', runBunCommand);
document.getElementById('btn-bun-script')?.addEventListener('click', runBunScript);
document.getElementById('btn-python-version')?.addEventListener('click', runPythonCommand);
document.getElementById('btn-python-script')?.addEventListener('click', runPythonScript);
document.getElementById('btn-system-info')?.addEventListener('click', runSystemInfo);
document.getElementById('btn-git-status')?.addEventListener('click', runGitStatus);
document.getElementById('btn-brew-info')?.addEventListener('click', runBrewInfo);
```

### Advanced Subprocess with Streaming

```javascript
// Streaming subprocess for long-running commands
async function runStreamingCommand(command, args = []) {
    try {
    if (!ALLOWED_CMDS.has(command)) throw new Error(`Command not allowed: ${command}`);
    const escapedArgs = (args || []).map(shellEscapeArg).join(' ');
    const full = `${command} ${escapedArgs}`.trim();
    displayOutput(`🔄 Starting streaming command: ${full}`);
        
    const process = await Neutralino.os.spawnProcess(full);
        appendOutput(`📋 Process ID: ${process.id}`);
        
        // Listen for process output
        Neutralino.events.on('spawnedProcess', (evt) => {
            if (evt.detail.id === process.id) {
                if (evt.detail.action === 'stdOut') {
                    appendOutput(`📤 ${evt.detail.data}`);
                } else if (evt.detail.action === 'stdErr') {
                    appendOutput(`⚠️ ${evt.detail.data}`);
                } else if (evt.detail.action === 'exit') {
                    appendOutput(`✅ Process exited with code: ${evt.detail.data}`);
                }
            }
        });
        
        return process;
    } catch (error) {
        appendOutput(`❌ Streaming command failed: ${error.message}`);
        return null;
    }
}

// Example: Long-running ping command
async function runPingTest() {
    await runStreamingCommand('ping', ['-c', '5', 'google.com']);
}

// Add to HTML:
/*
<button id="btn-ping-test">Ping Test (Streaming)</button>
*/

document.getElementById('btn-ping-test')?.addEventListener('click', runPingTest);
```

---

## 5. Storage and Configuration

### Using Neutralino Storage

```javascript
// Storage operations
async function saveToStorage() {
    try {
        const data = {
            timestamp: new Date().toISOString(),
            user: await Neutralino.os.getEnv('USER') || 'Unknown',
            counter: Math.floor(Math.random() * 1000)
        };
        
        await Neutralino.storage.setData('appData', JSON.stringify(data));
        appendOutput('💾 Data saved to storage');
        appendOutput(`Data: ${JSON.stringify(data, null, 2)}`);
    } catch (error) {
        appendOutput(`❌ Storage save error: ${error.message}`);
    }
}

async function loadFromStorage() {
    try {
        const dataString = await Neutralino.storage.getData('appData');
        const data = JSON.parse(dataString);
        appendOutput('📖 Data loaded from storage');
        appendOutput(`Data: ${JSON.stringify(data, null, 2)}`);
    } catch (error) {
        appendOutput(`❌ Storage load error: ${error.message}`);
    }
}

async function listStorageKeys() {
    try {
        const keys = await Neutralino.storage.getKeys();
        appendOutput('🔑 Storage keys:');
        keys.forEach(key => appendOutput(`  - ${key}`));
    } catch (error) {
        appendOutput(`❌ Storage keys error: ${error.message}`);
    }
}

// Configuration management
class AppConfig {
    constructor() {
        this.defaultConfig = {
            theme: 'dark',
            language: 'en',
            autoSave: true,
            windowSize: { width: 800, height: 600 }
        };
    }
    
    async load() {
        try {
            const configString = await Neutralino.storage.getData('appConfig');
            return { ...this.defaultConfig, ...JSON.parse(configString) };
        } catch (error) {
            return this.defaultConfig;
        }
    }
    
    async save(config) {
        try {
            await Neutralino.storage.setData('appConfig', JSON.stringify(config));
            return true;
        } catch (error) {
            console.error('Config save error:', error);
            return false;
        }
    }
}

const appConfig = new AppConfig();

async function demonstrateConfig() {
    const config = await appConfig.load();
    appendOutput('⚙️ Current configuration:');
    appendOutput(JSON.stringify(config, null, 2));
    
    // Modify and save
    config.theme = config.theme === 'dark' ? 'light' : 'dark';
    config.lastUsed = new Date().toISOString();
    
    const saved = await appConfig.save(config);
    appendOutput(saved ? '✅ Configuration updated' : '❌ Configuration save failed');
}

// Add buttons:
/*
<div class="storage-section">
    <h3>Storage & Configuration</h3>
    <button id="btn-save-storage">Save to Storage</button>
    <button id="btn-load-storage">Load from Storage</button>
    <button id="btn-list-keys">List Storage Keys</button>
    <button id="btn-demo-config">Demo Configuration</button>
</div>
*/

// Add event listeners:
document.getElementById('btn-save-storage')?.addEventListener('click', saveToStorage);
document.getElementById('btn-load-storage')?.addEventListener('click', loadFromStorage);
document.getElementById('btn-list-keys')?.addEventListener('click', listStorageKeys);
document.getElementById('btn-demo-config')?.addEventListener('click', demonstrateConfig);
```

---

## 6. Window Management

### Window Operations

```javascript
// Window management functions
async function minimizeWindow() {
    try {
        await Neutralino.window.minimize();
        // Note: Output won't be visible since window is minimized
    } catch (error) {
        appendOutput(`❌ Minimize error: ${error.message}`);
    }
}

async function maximizeWindow() {
    try {
        await Neutralino.window.maximize();
        appendOutput('🔼 Window maximized');
    } catch (error) {
        appendOutput(`❌ Maximize error: ${error.message}`);
    }
}

async function unmaximizeWindow() {
    try {
        await Neutralino.window.unmaximize();
        appendOutput('🔽 Window restored');
    } catch (error) {
        appendOutput(`❌ Unmaximize error: ${error.message}`);
    }
}

async function toggleFullscreen() {
    try {
        const isFullScreen = await Neutralino.window.isFullScreen();
        if (isFullScreen) {
            await Neutralino.window.exitFullScreen();
            appendOutput('↩️ Exited fullscreen');
        } else {
            await Neutralino.window.setFullScreen();
            appendOutput('🔳 Entered fullscreen');
        }
    } catch (error) {
        appendOutput(`❌ Fullscreen toggle error: ${error.message}`);
    }
}

async function setWindowSize() {
    try {
        await Neutralino.window.setSize({
            width: 600,
            height: 500
        });
        appendOutput('📐 Window resized to 600x500');
    } catch (error) {
        appendOutput(`❌ Resize error: ${error.message}`);
    }
}

async function centerWindow() {
    try {
        await Neutralino.window.center();
        appendOutput('⭕ Window centered');
    } catch (error) {
        appendOutput(`❌ Center error: ${error.message}`);
    }
}

async function getWindowInfo() {
    try {
        const size = await Neutralino.window.getSize();
        const position = await Neutralino.window.getPosition();
        const isMaximized = await Neutralino.window.isMaximized();
        const isFullScreen = await Neutralino.window.isFullScreen();
        
        appendOutput('📊 Window Information:');
        appendOutput(`Size: ${size.width}x${size.height}`);
        appendOutput(`Position: (${position.x}, ${position.y})`);
        appendOutput(`Maximized: ${isMaximized}`);
        appendOutput(`Fullscreen: ${isFullScreen}`);
    } catch (error) {
        appendOutput(`❌ Window info error: ${error.message}`);
    }
}

// Add buttons:
/*
<div class="window-section">
    <h3>Window Management</h3>
    <button id="btn-minimize">Minimize</button>
    <button id="btn-maximize">Maximize</button>
    <button id="btn-restore">Restore</button>
    <button id="btn-fullscreen">Toggle Fullscreen</button>
    <button id="btn-resize">Resize (600x500)</button>
    <button id="btn-center">Center Window</button>
    <button id="btn-window-info">Window Info</button>
</div>
*/

// Add event listeners:
document.getElementById('btn-minimize')?.addEventListener('click', minimizeWindow);
document.getElementById('btn-maximize')?.addEventListener('click', maximizeWindow);
document.getElementById('btn-restore')?.addEventListener('click', unmaximizeWindow);
document.getElementById('btn-fullscreen')?.addEventListener('click', toggleFullscreen);
document.getElementById('btn-resize')?.addEventListener('click', setWindowSize);
document.getElementById('btn-center')?.addEventListener('click', centerWindow);
document.getElementById('btn-window-info')?.addEventListener('click', getWindowInfo);
```

---

## 7. Notifications and System Integration

### System Notifications

```javascript
// Notification functions
async function showBasicNotification() {
    try {
        await Neutralino.os.showNotification('Hello!', 'This is a basic notification from your Neutralino app.');
        appendOutput('📬 Basic notification sent');
    } catch (error) {
        appendOutput(`❌ Notification error: ${error.message}`);
    }
}

async function showNotificationWithIcon() {
    try {
        await Neutralino.os.showNotification(
            'Neutralino App',
            'Task completed successfully! 🎉',
            'INFO'
        );
        appendOutput('📬 Icon notification sent');
    } catch (error) {
        appendOutput(`❌ Notification error: ${error.message}`);
    }
}

// System integration
async function openURL() {
    try {
    const url = 'https://neutralino.js.org';
    const u = new URL(url);
    if (!/^https?:$/.test(u.protocol)) throw new Error('Unsupported URL scheme');
    await Neutralino.os.open(u.toString());
        appendOutput('🌐 Opened Neutralino website');
    } catch (error) {
        appendOutput(`❌ URL open error: ${error.message}`);
    }
}

async function showMessageBox() {
    try {
        const result = await Neutralino.os.showMessageBox(
            'Confirmation',
            'Do you want to continue?',
            'YES_NO',
            'QUESTION'
        );
        appendOutput(`💬 Message box result: ${result}`);
    } catch (error) {
        appendOutput(`❌ Message box error: ${error.message}`);
    }
}

async function getTrayMenu() {
    try {
        // Set up tray menu (if supported)
        const trayMenuTemplate = {
            icon: '/resources/icons/trayIcon.png',
            menuItems: [
                { id: 'show', text: 'Show' },
                { id: 'separator', text: '-' },
                { id: 'quit', text: 'Quit' }
            ]
        };
        
        await Neutralino.os.setTray(trayMenuTemplate);
        appendOutput('🔰 Tray menu set up');
        
        // Listen for tray menu clicks
        Neutralino.events.on('trayMenuItemClicked', (evt) => {
            switch (evt.detail.id) {
                case 'show':
                    Neutralino.window.show();
                    break;
                case 'quit':
                    Neutralino.app.exit();
                    break;
            }
        });
    } catch (error) {
        appendOutput(`❌ Tray menu error: ${error.message}`);
    }
}

// Clipboard operations
async function copyToClipboard() {
    try {
        const text = 'Hello from Neutralino clipboard!';
        await Neutralino.clipboard.writeText(text);
        appendOutput(`📋 Copied to clipboard: ${text}`);
    } catch (error) {
        appendOutput(`❌ Clipboard write error: ${error.message}`);
    }
}

async function readFromClipboard() {
    try {
        const text = await Neutralino.clipboard.readText();
        appendOutput(`📋 Clipboard content: ${text}`);
    } catch (error) {
        appendOutput(`❌ Clipboard read error: ${error.message}`);
    }
}

// Add buttons:
/*
<div class="system-section">
    <h3>System Integration</h3>
    <button id="btn-basic-notification">Basic Notification</button>
    <button id="btn-icon-notification">Icon Notification</button>
    <button id="btn-open-url">Open Website</button>
    <button id="btn-message-box">Show Message Box</button>
    <button id="btn-setup-tray">Setup Tray</button>
    <button id="btn-copy-clipboard">Copy to Clipboard</button>
    <button id="btn-read-clipboard">Read Clipboard</button>
</div>
*/

// Add event listeners:
document.getElementById('btn-basic-notification')?.addEventListener('click', showBasicNotification);
document.getElementById('btn-icon-notification')?.addEventListener('click', showNotificationWithIcon);
document.getElementById('btn-open-url')?.addEventListener('click', openURL);
document.getElementById('btn-message-box')?.addEventListener('click', showMessageBox);
document.getElementById('btn-setup-tray')?.addEventListener('click', getTrayMenu);
document.getElementById('btn-copy-clipboard')?.addEventListener('click', copyToClipboard);
document.getElementById('btn-read-clipboard')?.addEventListener('click', readFromClipboard);
```

---

## 8. Building Custom Extensions

### Creating a Custom Extension

Create `extensions/hello/` directory and files:

**extensions/hello/hello.py:**

```python
import sys
import json

def hello_world(name="World"):
    return f"Hello, {name} from Python extension!"

def get_system_info():
    import platform
    return {
        "platform": platform.platform(),
        "python_version": platform.python_version(),
        "architecture": platform.architecture()[0]
    }

def main():
    data = json.loads(sys.argv[1])
    method = data.get("method", "")
    
    if method == "hello":
        name = data.get("data", {}).get("name", "World")
        result = hello_world(name)
    elif method == "info":
        result = get_system_info()
    else:
        result = {"error": "Unknown method"}
    
    print(json.dumps(result))

if __name__ == "__main__":
    main()
```

**extensions/hello/manifest.json:**

```json
{
    "id": "hello",
    "name": "Hello Extension",
    "version": "1.0.0",
    "description": "A simple Python extension for Neutralino",
    "main": "hello.py",
    "type": "python"
}
```

### Using the Extension in JavaScript

```javascript
// Extension communication
async function callHelloExtension() {
    try {
        const result = await Neutralino.extensions.dispatch('hello', 'hello', {
            name: 'Neutralino Developer'
        });
        appendOutput(`🐍 Extension result: ${result}`);
    } catch (error) {
        appendOutput(`❌ Extension error: ${error.message}`);
    }
}

async function getExtensionInfo() {
    try {
        const result = await Neutralino.extensions.dispatch('hello', 'info', {});
        const info = JSON.parse(result);
        appendOutput('🐍 Python Extension System Info:');
        appendOutput(`Platform: ${info.platform}`);
        appendOutput(`Python Version: ${info.python_version}`);
        appendOutput(`Architecture: ${info.architecture}`);
    } catch (error) {
        appendOutput(`❌ Extension info error: ${error.message}`);
    }
}

// Add buttons:
/*
<div class="extension-section">
    <h3>Custom Extensions</h3>
    <button id="btn-call-extension">Call Hello Extension</button>
    <button id="btn-extension-info">Extension System Info</button>
</div>
*/

// Add event listeners:
document.getElementById('btn-call-extension')?.addEventListener('click', callHelloExtension);
document.getElementById('btn-extension-info')?.addEventListener('click', getExtensionInfo);
```

---

## 9. Advanced Configuration

### Complete neutralino.config.json

```json
{
  "applicationId": "js.neutralino.myapp",
  "version": "1.0.0",
  "defaultMode": "window",
  "port": 0,
  "documentRoot": "/resources/",
  "url": "/",
  "enableServer": true,
  "enableNativeAPI": true,
  "tokenSecurity": "one-time",
  "logging": {
    "enabled": true,
    "writeToLogFile": true
  },
    "nativeAllowList": [
        "app.*",
        "window.*",
        "events.*",
        "clipboard.*",
        "storage.*",
        "filesystem.readFile",
        "filesystem.writeFile",
        "filesystem.removeFile",
        "os.execCommand",
        "os.spawnProcess",
        "os.open",
        "os.showOpenDialog",
        "os.showSaveDialog",
        "os.showMessageBox",
        "os.setTray"
    ],
  "globalVariables": {
    "TEST_MODE": "true",
    "API_URL": "https://api.example.com"
  },
  "modes": {
    "window": {
      "title": "My Advanced Neutralino App",
      "width": 1000,
      "height": 700,
      "minWidth": 600,
      "minHeight": 400,
      "fullScreen": false,
      "alwaysOnTop": false,
      "icon": "/resources/icons/appIcon.png",
    "enableInspector": false,
      "borderless": false,
      "maximize": false,
      "hidden": false,
      "resizable": true,
      "exitProcessOnClose": true
    },
    "browser": {
      "globalVariables": {
        "TEST_MODE": "false"
      },
      "nativeBlockList": [
        "filesystem.*",
        "os.*"
      ]
    },
    "cloud": {
      "url": "/resources/#cloud",
      "nativeAllowList": [
        "app.*"
      ]
    }
  },
  "cli": {
    "binaryName": "myapp",
    "resourcesPath": "/resources/",
    "extensionsPath": "/extensions/",
    "clientLibrary": "/resources/js/neutralino.js",
    "binaryVersion": "4.12.0",
    "clientVersion": "3.8.0"
  },
  "extensions": [
    {
      "id": "hello",
      "command": "python extensions/hello/hello.py"
    }
  ]
}
```

### Environment-specific Configuration

```javascript
// Configuration manager with environment support
class AdvancedConfig {
    constructor() {
        this.environment = NL_MODE || 'window';
        this.config = {};
    }
    
    async loadConfig() {
        try {
            // Load base config
            const baseConfig = JSON.parse(await Neutralino.filesystem.readFile('./neutralino.config.json'));
            
            // Merge with environment-specific config
            this.config = {
                ...baseConfig,
                ...baseConfig.modes[this.environment]
            };
            
            return this.config;
        } catch (error) {
            console.error('Config load error:', error);
            return {};
        }
    }
    
    getGlobalVariable(key) {
        return this.config.globalVariables?.[key] || null;
    }
    
    isNativeAPIAllowed(api) {
        const allowList = this.config.nativeAllowList || [];
        return allowList.some(pattern => {
            const regex = new RegExp(pattern.replace('*', '.*'));
            return regex.test(api);
        });
    }
}

// Usage example
async function demonstrateAdvancedConfig() {
    const config = new AdvancedConfig();
    await config.loadConfig();
    
    appendOutput('⚙️ Advanced Configuration Demo:');
    appendOutput(`Environment: ${config.environment}`);
    appendOutput(`Test Mode: ${config.getGlobalVariable('TEST_MODE')}`);
    appendOutput(`API URL: ${config.getGlobalVariable('API_URL')}`);
    appendOutput(`Can use filesystem: ${config.isNativeAPIAllowed('filesystem.readFile')}`);
    appendOutput(`Can use os: ${config.isNativeAPIAllowed('os.execCommand')}`);
}
```

---

## 10. Production Build and Distribution

### Build Commands

```bash
# Build for current platform
neu build

# Build for specific platforms
neu build --release

# Build for multiple platforms (requires setup)
neu build --target linux
neu build --target mac
neu build --target win

# Clean build directory
neu clean

# Update Neutralino binaries
neu update
```

### Complete Build Script

Create `scripts/build.js`:

```javascript
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

class BuildManager {
    constructor() {
        this.distPath = path.join(process.cwd(), 'dist');
        this.platforms = ['linux', 'mac', 'win'];
    }
    
    log(message) {
        console.log(`[Build] ${new Date().toISOString()} - ${message}`);
    }
    
    clean() {
        this.log('Cleaning build directory...');
        if (fs.existsSync(this.distPath)) {
            fs.rmSync(this.distPath, { recursive: true, force: true });
        }
    }
    
    updateNeutralino() {
        this.log('Updating Neutralino binaries...');
        execSync('neu update', { stdio: 'inherit' });
    }
    
    buildForPlatform(platform) {
        this.log(`Building for ${platform}...`);
        try {
            execSync(`neu build --target ${platform}`, { stdio: 'inherit' });
            this.log(`✅ Build completed for ${platform}`);
        } catch (error) {
            this.log(`❌ Build failed for ${platform}: ${error.message}`);
        }
    }
    
    buildAll() {
        this.clean();
        this.updateNeutralino();
        
        this.platforms.forEach(platform => {
            this.buildForPlatform(platform);
        });
        
        this.generateChecksums();
        this.log('🎉 All builds completed!');
    }
    
    generateChecksums() {
        this.log('Generating checksums...');
        // Implementation would generate SHA256 checksums for each binary
    }
}

// Usage
const builder = new BuildManager();
builder.buildAll();
```

### Package.json for NPM Scripts

Create/update `package.json`:

```json
{
  "name": "my-neutralino-app",
  "version": "1.0.0",
  "description": "Advanced Neutralino Application",
  "scripts": {
    "dev": "neu run",
    "build": "neu build --release",
    "build:all": "node scripts/build.js",
    "clean": "neu clean",
    "update": "neu update",
    "serve": "neu run --frontend-lib-dev",
    "lint": "eslint resources/js/",
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "keywords": ["neutralino", "desktop", "cross-platform"],
  "author": "Your Name",
  "license": "MIT",
  "devDependencies": {
    "eslint": "^8.0.0",
    "@neutralinojs/neu": "^10.0.0"
  }
}
```

---

## 11. Helper Utilities (RAD)

Reusable helpers that are safe-by-default. Copy/paste into your `resources/js/main.js` (or a `utils.js` file) and import.

```javascript
// ===== Helper Utilities for Neutralino Apps =====
// Safe output appending with size bound
function safeAppend(el, text, maxLen = 20000) {
    const toAdd = (el.textContent ? '\n' : '') + String(text ?? '');
    el.textContent = (el.textContent + toAdd).slice(-maxLen);
}

// Promisified result wrapper: const [err, data] = await toResult(promise)
async function toResult(promise) {
    try { return [null, await promise]; } catch (e) { return [e, null]; }
}

// Retry helper with exponential backoff
async function retry(fn, { retries = 3, baseDelayMs = 200 } = {}) {
    let attempt = 0; let lastErr;
    while (attempt < retries) {
        try { return await fn(attempt); } catch (e) {
            lastErr = e; attempt += 1;
            await new Promise(r => setTimeout(r, baseDelayMs * Math.pow(2, attempt - 1)));
        }
    }
    throw lastErr;
}

// Timeout wrapper
async function withTimeout(promise, ms, message = 'Operation timed out') {
    let timer;
    const timeout = new Promise((_, rej) => { timer = setTimeout(() => rej(new Error(message)), ms); });
    try { return await Promise.race([promise, timeout]); } finally { clearTimeout(timer); }
}

// Minimal cross-platform shell arg escaper and allowlist guard
function shellEscapeArg(arg) {
    if (arg === undefined || arg === null) return '';
    const s = String(arg);
    if (["\n","\r"].some(ch => s.includes(ch))) throw new Error('Invalid arg with newline');
    if (NL_OS === 'Windows') { return '"' + s.replace(/([\^&|<>\"])\/g, '^$1') + '"'; }
    return '\'' + s.replace(/'/g, `'"'"'`) + '\'';
}

const ALLOWED_CMDS = new Set(['node','bun','python3','uname','systeminfo','git','brew','ping']);

async function safeExec(command, args = []) {
    if (!ALLOWED_CMDS.has(command)) throw new Error(`Command not allowed: ${command}`);
    const full = `${command} ${(args||[]).map(shellEscapeArg).join(' ')}`.trim();
    return await Neutralino.os.execCommand(full);
}

// Safe URL opener (http/https only)
async function safeOpenUrl(url) {
    const u = new URL(String(url));
    if (!/^https?:$/.test(u.protocol)) throw new Error('Unsupported URL scheme');
    return Neutralino.os.open(u.toString());
}

// JSON storage helper with namespace
class JsonStorage {
    constructor(ns = 'app') { this.ns = ns; }
    key(k) { return `${this.ns}:${k}`; }
    async get(key, fallback = null) {
        try { const raw = await Neutralino.storage.getData(this.key(key)); return JSON.parse(raw); }
        catch { return fallback; }
    }
    async set(key, val) { await Neutralino.storage.setData(this.key(key), JSON.stringify(val)); }
    async remove(key) { await Neutralino.storage.setData(this.key(key), ''); }
}

// Confirm user intent before dangerous operations
async function confirmAction(message = 'Are you sure?') {
    const res = await Neutralino.os.showMessageBox('Confirm', message, 'YES_NO', 'WARNING');
    return String(res).toUpperCase() === 'YES';
}
```

Usage examples:

```javascript
// Example: run a safe command with timeout and print
const res = await withTimeout(safeExec('git', ['--version']), 5000);
appendOutput(res.stdOut.trim());

// Example: namespaced storage
const kv = new JsonStorage('demo');
await kv.set('settings', { theme: 'dark' });
const loaded = await kv.get('settings');
appendOutput(JSON.stringify(loaded));
```

### Distribution Checklist

Before distributing your app:

```bash
# 1. Update version in neutralino.config.json
# 2. Clean build
neu clean

# 3. Update Neutralino
neu update

# 4. Build for release
neu build --release

# 5. Test the binary
./dist/myapp-linux_x64

# 6. Create installer/package (platform-specific)
# Linux: Create .deb/.rpm/.AppImage
# macOS: Create .dmg/.pkg
# Windows: Create .exe installer/.msi

# 7. Sign binaries (for distribution)
# 8. Generate checksums
# 9. Create release notes
# 10. Upload to distribution platform
```

---

## Complete Example Application

Put it all together with this updated HTML structure:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Neutralino App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>🚀 Advanced Neutralino Application</h1>
        
        <!-- Basic Operations -->
        <section class="section">
            <h2>Basic Operations</h2>
            <div class="buttons">
                <button id="btn-hello">Say Hello</button>
                <button id="btn-info">System Info</button>
                <button id="btn-close">Close App</button>
            </div>
        </section>
        
        <!-- File Operations -->
        <section class="section">
            <h2>File Operations</h2>
            <div class="buttons">
                <button id="btn-create-file">Create File</button>
                <button id="btn-read-file">Read File</button>
                <button id="btn-list-dir">List Directory</button>
                <button id="btn-open-file">Open File</button>
                <button id="btn-save-file">Save File</button>
            </div>
        </section>
        
        <!-- Subprocess Operations -->
        <section class="section">
            <h2>Subprocess Operations</h2>
            <div class="buttons">
                <button id="btn-node-version">Node Version</button>
                <button id="btn-node-script">Run Node Script</button>
                <button id="btn-bun-version">Bun Version</button>
                <button id="btn-bun-script">Run Bun Script</button>
                <button id="btn-python-version">Python Version</button>
                <button id="btn-python-script">Run Python Script</button>
                <button id="btn-ping-test">Ping Test</button>
            </div>
        </section>
        
        <!-- Storage & Config -->
        <section class="section">
            <h2>Storage & Configuration</h2>
            <div class="buttons">
                <button id="btn-save-storage">Save to Storage</button>
                <button id="btn-load-storage">Load from Storage</button>
                <button id="btn-list-keys">List Keys</button>
                <button id="btn-demo-config">Demo Config</button>
            </div>
        </section>
        
        <!-- Window Management -->
        <section class="section">
            <h2>Window Management</h2>
            <div class="buttons">
                <button id="btn-minimize">Minimize</button>
                <button id="btn-maximize">Maximize</button>
                <button id="btn-restore">Restore</button>
                <button id="btn-fullscreen">Fullscreen</button>
                <button id="btn-center">Center</button>
                <button id="btn-window-info">Window Info</button>
            </div>
        </section>
        
        <!-- System Integration -->
        <section class="section">
            <h2>System Integration</h2>
            <div class="buttons">
                <button id="btn-basic-notification">Notification</button>
                <button id="btn-open-url">Open URL</button>
                <button id="btn-message-box">Message Box</button>
                <button id="btn-copy-clipboard">Copy Text</button>
                <button id="btn-read-clipboard">Read Clipboard</button>
            </div>
        </section>
        
        <!-- Extensions -->
        <section class="section">
            <h2>Custom Extensions</h2>
            <div class="buttons">
                <button id="btn-call-extension">Call Extension</button>
                <button id="btn-extension-info">Extension Info</button>
            </div>
        </section>
        
        <!-- Output -->
        <div id="output" class="output"></div>
    </div>
    
    <script src="/js/neutralino.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
```

This comprehensive guide provides everything needed to build advanced Neutralino applications with RAD development practices!
