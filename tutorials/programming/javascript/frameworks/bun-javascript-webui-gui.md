# Complete Bun + WebUI Tutorial for Desktop Applications

This tutorial shows how to build desktop applications using Bun JavaScript runtime with WebUI as the frontend framework. WebUI is a lightweight web browser engine that allows you to create desktop apps with HTML, CSS, and JavaScript.

## Installation

First, install Bun and WebUI:

```bash
# Install Bun globally using Homebrew (ARM Mac)
brew tap oven-sh/bun
brew install bun

# Create project directory
mkdir bun-webui-app
cd bun-webui-app

# Initialize Bun project
bun init -y
```

Install WebUI for Bun:
```bash
# WebUI doesn't have direct Bun bindings, so we'll use it through FFI
# Download WebUI library manually or use a wrapper
# For this tutorial, we'll create a simple binding
```

[Official Bun Installation Guide](https://bun.sh/docs/installation)

## Setting Up WebUI

Create `webui.js` wrapper:

```javascript
// webui.js - WebUI wrapper for Bun
// Note: This is a simplified implementation
// In production, you'd use proper FFI bindings

class WebUI {
    constructor() {
        this.windows = new Map();
        this.nextWindowId = 1;
    }

    // Create a new window
    newWindow() {
        const windowId = this.nextWindowId++;
        this.windows.set(windowId, {
            id: windowId,
            html: "",
            callbacks: new Map()
        });
        return windowId;
    }

    // Set HTML content for a window
    setHtml(windowId, html) {
        const window = this.windows.get(windowId);
        if (window) {
            window.html = html;
        }
    }

    // Show a window
    async show(windowId) {
        const window = this.windows.get(windowId);
        if (!window) return false;

        // In a real implementation, this would launch the WebUI window
        console.log(`Showing window ${windowId}`);
        console.log("HTML Content:", window.html.substring(0, 100) + "...");
        
        // For demo purposes, we'll simulate this with a local server
        return true;
    }

    // Bind a function to a UI element
    bind(windowId, elementId, callback) {
        const window = this.windows.get(windowId);
        if (window) {
            window.callbacks.set(elementId, callback);
        }
    }

    // Close a window
    exit() {
        console.log("Closing all windows");
        process.exit(0);
    }
}

// Export singleton instance
export default new WebUI();
```

[WebUI Documentation](https://webui.me/docs/)

## Basic HTML Setup

Create `index.html`:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Bun WebUI App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .output {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            min-height: 50px;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bun WebUI Desktop App</h1>
        
        <div class="section">
            <h2>System Information</h2>
            <button id="getSystemInfo">Get System Info</button>
            <div id="systemInfoOutput" class="output"></div>
        </div>
        
        <div class="section">
            <h2>File Operations</h2>
            <input type="text" id="filePath" placeholder="Enter file path">
            <button id="readFile">Read File</button>
            <button id="browseFile">Browse</button>
            <textarea id="fileContent" rows="5" placeholder="File content will appear here"></textarea>
            <button id="saveFile">Save File</button>
        </div>
        
        <div class="section">
            <h2>Network Operations</h2>
            <input type="text" id="apiUrl" placeholder="Enter API URL" value="https://jsonplaceholder.typicode.com/posts/1">
            <select id="method">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
            <button id="sendRequest">Send Request</button>
            <div id="networkOutput" class="output"></div>
        </div>
        
        <div class="section">
            <h2>Settings</h2>
            <input type="text" id="username" placeholder="Enter username">
            <select id="theme">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>
            <button id="saveSettings">Save Settings</button>
        </div>
        
        <div class="section">
            <h2>Application Control</h2>
            <button id="showNotification">Show Notification</button>
            <button id="quitApp" style="background-color: #dc3545;">Quit Application</button>
        </div>
    </div>
    
    <div id="notification" class="notification"></div>
    
    <script>
        // This script will be enhanced by our Bun backend
        console.log("WebUI frontend loaded");
    </script>
</body>
</html>
```

## Main Application

Create `app.js`:

```javascript
// app.js - Main Bun WebUI Application
import webui from "./webui.js";
import { readFile, writeFile, stat } from "fs/promises";
import { join } from "path";

// Create WebUI window
const windowId = webui.newWindow();

// Read and set HTML content
try {
    const htmlContent = await readFile("index.html", "utf-8");
    webui.setHtml(windowId, htmlContent);
} catch (error) {
    console.error("Error reading HTML file:", error);
    webui.setHtml(windowId, "<h1>Error loading application</h1>");
}

// Application state
let appState = {
    username: "",
    theme: "light",
    settingsFile: "settings.json"
};

// Initialize application
async function initApp() {
    // Load saved settings
    await loadSettings();
    
    // Bind UI events
    bindEvents();
    
    // Show the window
    await webui.show(windowId);
    
    console.log("Bun WebUI App started successfully!");
}

// Load saved settings
async function loadSettings() {
    try {
        const settingsData = await readFile(appState.settingsFile, "utf-8");
        const settings = JSON.parse(settingsData);
        appState = { ...appState, ...settings };
        
        // Update UI with loaded settings
        // In a real implementation, you'd send this to the frontend
        console.log("Settings loaded:", appState);
    } catch (error) {
        console.log("No saved settings found, using defaults");
    }
}

// Save settings
async function saveSettings() {
    try {
        await writeFile(appState.settingsFile, JSON.stringify(appState, null, 2));
        console.log("Settings saved");
    } catch (error) {
        console.error("Error saving settings:", error);
    }
}

// Bind UI events
function bindEvents() {
    // System Info
    webui.bind(windowId, "getSystemInfo", handleGetSystemInfo);
    
    // File Operations
    webui.bind(windowId, "readFile", handleReadFile);
    webui.bind(windowId, "browseFile", handleBrowseFile);
    webui.bind(windowId, "saveFile", handleSaveFile);
    
    // Network Operations
    webui.bind(windowId, "sendRequest", handleSendRequest);
    
    // Settings
    webui.bind(windowId, "saveSettings", handleSaveSettings);
    
    // Application Control
    webui.bind(windowId, "showNotification", handleShowNotification);
    webui.bind(windowId, "quitApp", handleQuitApp);
}

// Event Handlers
async function handleGetSystemInfo() {
    try {
        const info = {
            platform: process.platform,
            arch: process.arch,
            bunVersion: process.versions.bun,
            memoryUsage: process.memoryUsage(),
            uptime: process.uptime(),
            pid: process.pid
        };
        
        // In a real implementation, you'd send this to the frontend
        console.log("System Info:", info);
        
        // Simulate sending to frontend
        sendToFrontend("systemInfo", info);
    } catch (error) {
        console.error("Error getting system info:", error);
    }
}

async function handleReadFile() {
    // In a real implementation, you'd get the file path from the frontend
    const filePath = "example.txt"; // This would come from UI
    
    try {
        const content = await readFile(filePath, "utf-8");
        console.log("File content:", content);
        sendToFrontend("fileContent", content);
    } catch (error) {
        console.error("Error reading file:", error);
        sendToFrontend("error", `Error reading file: ${error.message}`);
    }
}

async function handleBrowseFile() {
    // File dialog would be implemented here
    console.log("Browse file dialog would open");
    sendToFrontend("notification", "File browser would open in full implementation");
}

async function handleSaveFile() {
    const filePath = "example.txt"; // This would come from UI
    const content = "Sample content saved by Bun WebUI app"; // This would come from UI
    
    try {
        await writeFile(filePath, content, "utf-8");
        console.log("File saved successfully");
        sendToFrontend("notification", "File saved successfully");
    } catch (error) {
        console.error("Error saving file:", error);
        sendToFrontend("error", `Error saving file: ${error.message}`);
    }
}

async function handleSendRequest() {
    const url = "https://jsonplaceholder.typicode.com/posts/1"; // This would come from UI
    const method = "GET"; // This would come from UI
    
    try {
        const response = await fetch(url, { method });
        const data = await response.json();
        
        console.log("API Response:", data);
        sendToFrontend("apiResponse", data);
    } catch (error) {
        console.error("Error making request:", error);
        sendToFrontend("error", `Error making request: ${error.message}`);
    }
}

async function handleSaveSettings() {
    // Settings would come from UI
    appState.username = "Sample User"; // This would come from UI
    appState.theme = "dark"; // This would come from UI
    
    await saveSettings();
    sendToFrontend("notification", "Settings saved successfully");
}

async function handleShowNotification() {
    console.log("Showing notification");
    sendToFrontend("notification", "This is a sample notification!");
}

function handleQuitApp() {
    console.log("Quitting application");
    webui.exit();
}

// Helper function to send data to frontend
function sendToFrontend(event, data) {
    // In a real WebUI implementation, you'd use WebUI's event system
    console.log(`Sending to frontend - Event: ${event}, Data:`, data);
}

// Start the application
initApp().catch(console.error);

// Handle graceful shutdown
process.on("SIGINT", () => {
    console.log("\nShutting down gracefully...");
    webui.exit();
});
```

## File Operations Module

Create `fileManager.js`:

```javascript
// fileManager.js - File operations for Bun WebUI app
import { readFile, writeFile, stat, readdir, mkdir } from "fs/promises";
import { join, dirname } from "path";

export class FileManager {
    // Read a file
    async readFile(filePath) {
        try {
            const content = await readFile(filePath, "utf-8");
            const stats = await stat(filePath);
            
            return {
                content,
                size: stats.size,
                modified: stats.mtime,
                path: filePath
            };
        } catch (error) {
            throw new Error(`Failed to read file: ${error.message}`);
        }
    }

    // Write to a file
    async writeFile(filePath, content) {
        try {
            // Ensure directory exists
            await this.ensureDirectory(dirname(filePath));
            
            await writeFile(filePath, content, "utf-8");
            
            const stats = await stat(filePath);
            return {
                size: stats.size,
                modified: stats.mtime,
                path: filePath
            };
        } catch (error) {
            throw new Error(`Failed to write file: ${error.message}`);
        }
    }

    // Ensure directory exists
    async ensureDirectory(dirPath) {
        try {
            await mkdir(dirPath, { recursive: true });
        } catch (error) {
            // Directory might already exist
            if (error.code !== "EEXIST") {
                throw error;
            }
        }
    }

    // List directory contents
    async listDirectory(dirPath) {
        try {
            const entries = await readdir(dirPath, { withFileTypes: true });
            
            return entries.map(entry => ({
                name: entry.name,
                isDirectory: entry.isDirectory(),
                isFile: entry.isFile()
            }));
        } catch (error) {
            throw new Error(`Failed to list directory: ${error.message}`);
        }
    }

    // Get file statistics
    async getFileStats(filePath) {
        try {
            const stats = await stat(filePath);
            
            return {
                size: stats.size,
                created: stats.birthtime,
                modified: stats.mtime,
                accessed: stats.atime,
                isDirectory: stats.isDirectory(),
                isFile: stats.isFile()
            };
        } catch (error) {
            throw new Error(`Failed to get file stats: ${error.message}`);
        }
    }

    // Delete a file
    async deleteFile(filePath) {
        try {
            // Note: Bun doesn't have unlink yet, so we'll simulate
            console.log(`Would delete file: ${filePath}`);
            return true;
        } catch (error) {
            throw new Error(`Failed to delete file: ${error.message}`);
        }
    }
}

// Export singleton instance
export default new FileManager();
```

## Network Operations Module

Create `networkManager.js`:

```javascript
// networkManager.js - Network operations for Bun WebUI app
export class NetworkManager {
    // Make HTTP request
    async makeRequest(url, options = {}) {
        try {
            // Default options
            const defaultOptions = {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                },
                ...options
            };

            // Make request using Bun's built-in fetch
            const response = await fetch(url, defaultOptions);
            
            // Parse response
            let body;
            const contentType = response.headers.get("content-type");
            
            if (contentType && contentType.includes("application/json")) {
                body = await response.json();
            } else {
                body = await response.text();
            }
            
            return {
                status: response.status,
                statusText: response.statusText,
                headers: Object.fromEntries(response.headers.entries()),
                body: body,
                url: response.url
            };
        } catch (error) {
            throw new Error(`Network request failed: ${error.message}`);
        }
    }

    // Check internet connectivity
    async checkConnectivity() {
        try {
            const response = await fetch("https://httpbin.org/get", { 
                method: "GET",
                timeout: 5000 
            });
            return response.ok;
        } catch (error) {
            return false;
        }
    }

    // Get network interfaces (simulated)
    async getNetworkInfo() {
        // Bun doesn't have direct network interface access yet
        return {
            platform: process.platform,
            hasNetwork: await this.checkConnectivity(),
            timestamp: new Date().toISOString()
        };
    }
}

// Export singleton instance
export default new NetworkManager();
```

## Settings Manager

Create `settingsManager.js`:

```javascript
// settingsManager.js - Settings management for Bun WebUI app
import { readFile, writeFile } from "fs/promises";

export class SettingsManager {
    constructor(settingsFile = "app-settings.json") {
        this.settingsFile = settingsFile;
        this.settings = {
            username: "",
            theme: "light",
            language: "en",
            notifications: true,
            autoSave: true
        };
    }

    // Load settings from file
    async load() {
        try {
            const data = await readFile(this.settingsFile, "utf-8");
            const loadedSettings = JSON.parse(data);
            this.settings = { ...this.settings, ...loadedSettings };
            return this.settings;
        } catch (error) {
            // File doesn't exist or is invalid, use defaults
            console.log("Using default settings");
            return this.settings;
        }
    }

    // Save settings to file
    async save() {
        try {
            await writeFile(this.settingsFile, JSON.stringify(this.settings, null, 2));
            return true;
        } catch (error) {
            throw new Error(`Failed to save settings: ${error.message}`);
        }
    }

    // Get a setting value
    get(key) {
        return this.settings[key];
    }

    // Set a setting value
    set(key, value) {
        this.settings[key] = value;
    }

    // Get all settings
    getAll() {
        return { ...this.settings };
    }

    // Update multiple settings
    update(newSettings) {
        this.settings = { ...this.settings, ...newSettings };
    }
}

// Export singleton instance
export default new SettingsManager();
```

## Enhanced Main Application

Update `app.js` with enhanced functionality:

```javascript
// app.js - Enhanced Bun WebUI Application
import webui from "./webui.js";
import { readFile, writeFile } from "fs/promises";
import { FileManager } from "./fileManager.js";
import { NetworkManager } from "./networkManager.js";
import { SettingsManager } from "./settingsManager.js";

// Managers
const fileManager = new FileManager();
const networkManager = new NetworkManager();
const settingsManager = new SettingsManager();

// Create WebUI window
const windowId = webui.newWindow();

// Read and set HTML content
try {
    const htmlContent = await readFile("index.html", "utf-8");
    webui.setHtml(windowId, htmlContent);
} catch (error) {
    console.error("Error reading HTML file:", error);
    webui.setHtml(windowId, "<h1>Error loading application</h1>");
}

// Initialize application
async function initApp() {
    // Load settings
    await settingsManager.load();
    
    // Bind UI events
    bindEvents();
    
    // Show the window
    await webui.show(windowId);
    
    console.log("Enhanced Bun WebUI App started successfully!");
}

// Bind UI events
function bindEvents() {
    // System Info
    webui.bind(windowId, "getSystemInfo", handleGetSystemInfo);
    
    // File Operations
    webui.bind(windowId, "readFile", handleReadFile);
    webui.bind(windowId, "browseFile", handleBrowseFile);
    webui.bind(windowId, "saveFile", handleSaveFile);
    
    // Network Operations
    webui.bind(windowId, "sendRequest", handleSendRequest);
    
    // Settings
    webui.bind(windowId, "saveSettings", handleSaveSettings);
    
    // Application Control
    webui.bind(windowId, "showNotification", handleShowNotification);
    webui.bind(windowId, "quitApp", handleQuitApp);
}

// Event Handlers
async function handleGetSystemInfo() {
    try {
        const systemInfo = {
            platform: process.platform,
            arch: process.arch,
            bunVersion: process.versions.bun,
            memoryUsage: process.memoryUsage(),
            uptime: Math.floor(process.uptime()),
            pid: process.pid,
            network: await networkManager.getNetworkInfo()
        };
        
        sendToFrontend("systemInfo", systemInfo);
    } catch (error) {
        console.error("Error getting system info:", error);
        sendToFrontend("error", `Error: ${error.message}`);
    }
}

async function handleReadFile() {
    try {
        // In a real implementation, get filePath from frontend
        const filePath = "example.txt";
        const fileData = await fileManager.readFile(filePath);
        sendToFrontend("fileContent", fileData);
    } catch (error) {
        console.error("Error reading file:", error);
        sendToFrontend("error", `Error reading file: ${error.message}`);
    }
}

async function handleBrowseFile() {
    // File dialog implementation would go here
    sendToFrontend("notification", "File browser opened");
}

async function handleSaveFile() {
    try {
        // In a real implementation, get content from frontend
        const filePath = "example.txt";
        const content = "Content saved by Bun WebUI app at " + new Date().toISOString();
        const result = await fileManager.writeFile(filePath, content);
        sendToFrontend("notification", `File saved: ${result.path}`);
    } catch (error) {
        console.error("Error saving file:", error);
        sendToFrontend("error", `Error saving file: ${error.message}`);
    }
}

async function handleSendRequest() {
    try {
        // In a real implementation, get URL and method from frontend
        const url = "https://jsonplaceholder.typicode.com/posts/1";
        const method = "GET";
        
        const response = await networkManager.makeRequest(url, { method });
        sendToFrontend("apiResponse", response);
    } catch (error) {
        console.error("Error making request:", error);
        sendToFrontend("error", `Error making request: ${error.message}`);
    }
}

async function handleSaveSettings() {
    try {
        // In a real implementation, get settings from frontend
        settingsManager.update({
            username: "Bun User",
            theme: "dark",
            language: "en"
        });
        
        await settingsManager.save();
        sendToFrontend("notification", "Settings saved successfully");
    } catch (error) {
        console.error("Error saving settings:", error);
        sendToFrontend("error", `Error saving settings: ${error.message}`);
    }
}

async function handleShowNotification() {
    sendToFrontend("notification", "Hello from Bun WebUI!");
}

function handleQuitApp() {
    console.log("Quitting application");
    webui.exit();
}

// Helper function to send data to frontend
function sendToFrontend(event, data) {
    // In a real WebUI implementation, you'd use WebUI's event system
    console.log(`Frontend Event: ${event}`, JSON.stringify(data, null, 2));
}

// Start the application
initApp().catch(console.error);

// Handle graceful shutdown
process.on("SIGINT", () => {
    console.log("\nShutting down gracefully...");
    webui.exit();
});
```

## Package.json Configuration

Update `package.json`:

```json
{
  "name": "bun-webui-app",
  "version": "1.0.0",
  "description": "Desktop application using Bun and WebUI",
  "main": "app.js",
  "scripts": {
    "start": "bun app.js",
    "dev": "bun --watch app.js",
    "build": "bun build app.js --outfile dist/app.js"
  },
  "keywords": ["bun", "webui", "desktop", "application"],
  "author": "Your Name",
  "license": "MIT",
  "dependencies": {},
  "devDependencies": {}
}
```

## Enhanced HTML with JavaScript

Update `index.html` with frontend JavaScript:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Bun WebUI App</title>
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --border: #dee2e6;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            color: #212529;
        }
        
        body.dark-theme {
            background-color: #212529;
            color: #f8f9fa;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        body.dark-theme .container {
            background: #343a40;
        }
        
        h1, h2, h3 {
            margin-top: 0;
            color: var(--primary);
        }
        
        body.dark-theme h1, 
        body.dark-theme h2, 
        body.dark-theme h3 {
            color: #6ea8fe;
        }
        
        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            margin: 5px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        button.secondary {
            background-color: #6c757d;
        }
        
        button.secondary:hover {
            background-color: #545b62;
        }
        
        button.success {
            background-color: var(--success);
        }
        
        button.success:hover {
            background-color: #1e7e34;
        }
        
        button.danger {
            background-color: var(--danger);
        }
        
        button.danger:hover {
            background-color: #bd2130;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid var(--border);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            background: white;
            color: #212529;
        }
        
        body.dark-theme input, 
        body.dark-theme select, 
        body.dark-theme textarea {
            background: #495057;
            color: white;
            border: 1px solid #6c757d;
        }
        
        .output {
            background-color: var(--light);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            min-height: 60px;
            max-height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        body.dark-theme .output {
            background-color: #495057;
            border: 1px solid #6c757d;
        }
        
        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        body.dark-theme .section {
            border-bottom: 1px solid #6c757d;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .form-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .form-row > * {
            flex: 1;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success);
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            max-width: 300px;
        }
        
        .notification.error {
            background: var(--danger);
        }
        
        pre {
            margin: 0;
            white-space: pre-wrap;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bun WebUI Desktop App</h1>
        <p>A modern desktop application built with Bun JavaScript runtime and WebUI</p>
        
        <div class="section">
            <h2>System Information</h2>
            <div class="btn-group">
                <button id="getSystemInfo">Get System Info</button>
            </div>
            <div id="systemInfoOutput" class="output">Click button to get system information</div>
        </div>
        
        <div class="section">
            <h2>File Operations</h2>
            <div class="form-row">
                <input type="text" id="filePath" placeholder="Enter file path" value="example.txt">
                <button id="browseFile" class="secondary">Browse</button>
            </div>
            <div class="btn-group">
                <button id="readFile">Read File</button>
                <button id="saveFile" class="success">Save File</button>
            </div>
            <textarea id="fileContent" rows="6" placeholder="File content will appear here"></textarea>
        </div>
        
        <div class="section">
            <h2>Network Operations</h2>
            <div class="form-row">
                <input type="text" id="apiUrl" placeholder="Enter API URL" value="https://jsonplaceholder.typicode.com/posts/1">
                <select id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
            <div class="btn-group">
                <button id="sendRequest">Send Request</button>
            </div>
            <div id="networkOutput" class="output">API responses will appear here</div>
        </div>
        
        <div class="section">
            <h2>Settings</h2>
            <div class="form-row">
                <input type="text" id="username" placeholder="Enter username">
                <select id="theme">
                    <option value="light">Light Theme</option>
                    <option value="dark">Dark Theme</option>
                </select>
            </div>
            <button id="saveSettings" class="success">Save Settings</button>
        </div>
        
        <div class="section">
            <h2>Application Control</h2>
            <div class="btn-group">
                <button id="showNotification">Show Notification</button>
                <button id="quitApp" class="danger">Quit Application</button>
            </div>
        </div>
    </div>
    
    <div id="notification" class="notification"></div>
    
    <script>
        // Frontend JavaScript for WebUI
        console.log("Bun WebUI frontend loaded");
        
        // DOM Elements
        const elements = {
            getSystemInfo: document.getElementById('getSystemInfo'),
            systemInfoOutput: document.getElementById('systemInfoOutput'),
            filePath: document.getElementById('filePath'),
            browseFile: document.getElementById('browseFile'),
            readFile: document.getElementById('readFile'),
            saveFile: document.getElementById('saveFile'),
            fileContent: document.getElementById('fileContent'),
            apiUrl: document.getElementById('apiUrl'),
            method: document.getElementById('method'),
            sendRequest: document.getElementById('sendRequest'),
            networkOutput: document.getElementById('networkOutput'),
            username: document.getElementById('username'),
            theme: document.getElementById('theme'),
            saveSettings: document.getElementById('saveSettings'),
            showNotification: document.getElementById('showNotification'),
            quitApp: document.getElementById('quitApp'),
            notification: document.getElementById('notification')
        };
        
        // Event Listeners
        elements.getSystemInfo.addEventListener('click', () => {
            // In a real WebUI implementation, this would call backend functions
            console.log("Get System Info clicked");
            showNotification("Getting system information...", "info");
        });
        
        elements.readFile.addEventListener('click', () => {
            const filePath = elements.filePath.value;
            if (!filePath) {
                showNotification("Please enter a file path", "error");
                return;
            }
            console.log("Read File clicked:", filePath);
            showNotification(`Reading file: ${filePath}`, "info");
        });
        
        elements.saveFile.addEventListener('click', () => {
            const filePath = elements.filePath.value;
            const content = elements.fileContent.value;
            if (!filePath) {
                showNotification("Please enter a file path", "error");
                return;
            }
            console.log("Save File clicked:", filePath);
            showNotification(`Saving file: ${filePath}`, "info");
        });
        
        elements.sendRequest.addEventListener('click', () => {
            const url = elements.apiUrl.value;
            const method = elements.method.value;
            if (!url) {
                showNotification("Please enter an API URL", "error");
                return;
            }
            console.log("Send Request clicked:", method, url);
            showNotification(`Sending ${method} request to: ${url}`, "info");
        });
        
        elements.saveSettings.addEventListener('click', () => {
            const username = elements.username.value;
            const theme = elements.theme.value;
            console.log("Save Settings clicked:", { username, theme });
            
            // Apply theme
            document.body.className = theme === 'dark' ? 'dark-theme' : '';
            
            showNotification("Settings saved successfully!", "success");
        });
        
        elements.showNotification.addEventListener('click', () => {
            showNotification("This is a sample notification from the frontend!", "success");
        });
        
        elements.quitApp.addEventListener('click', () => {
            if (confirm("Are you sure you want to quit the application?")) {
                console.log("Quit Application clicked");
            }
        });
        
        // Notification system
        function showNotification(message, type = "info") {
            elements.notification.textContent = message;
            elements.notification.className = "notification " + (type === "error" ? "error" : "");
            elements.notification.style.display = "block";
            
            setTimeout(() => {
                elements.notification.style.display = "none";
            }, 3000);
        }
        
        // Initialize with saved settings
        document.addEventListener('DOMContentLoaded', () => {
            // Set initial theme
            document.body.className = 'light-theme';
            
            // Set sample data
            elements.fileContent.value = "Welcome to Bun WebUI App!\n\nThis is a sample file content.\nYou can edit this text and save it to a file.";
            
            console.log("App initialized");
        });
    </script>
</body>
</html>
```

## Running the Application

Create a simple example file:

```bash
# Create an example file
echo "This is an example file for the Bun WebUI application." > example.txt
```

Run the application:

```bash
# Run the application
bun app.js

# Or in development mode with auto-reload
bun --watch app.js
```

## Building for Distribution

Add build scripts to `package.json`:

```json
{
  "scripts": {
    "start": "bun app.js",
    "dev": "bun --watch app.js",
    "build": "bun build app.js --outfile dist/app.js",
    "package": "mkdir -p dist && cp -r app.js webui.js fileManager.js networkManager.js settingsManager.js index.html dist/"
  }
}
```

Build the application:

```bash
# Build the application
bun run build

# Package all files
bun run package
```

## Advanced Features

### Database Integration

Create `database.js`:

```javascript
// database.js - SQLite database integration
// Note: Bun has built-in SQLite support
import { Database } from "bun:sqlite";

export class DatabaseManager {
    constructor(dbPath = "app.db") {
        this.db = new Database(dbPath);
        this.init();
    }
    
    init() {
        // Create tables
        this.db.exec(`
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                email TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `);
        
        this.db.exec(`
            CREATE TABLE IF NOT EXISTS settings (
                key TEXT PRIMARY KEY,
                value TEXT
            )
        `);
    }
    
    // User operations
    createUser(username, email) {
        const stmt = this.db.prepare("INSERT INTO users (username, email) VALUES (?, ?)");
        return stmt.run(username, email);
    }
    
    getUser(username) {
        const stmt = this.db.prepare("SELECT * FROM users WHERE username = ?");
        return stmt.get(username);
    }
    
    getAllUsers() {
        const stmt = this.db.prepare("SELECT * FROM users");
        return stmt.all();
    }
    
    // Settings operations
    setSetting(key, value) {
        const stmt = this.db.prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        return stmt.run(key, JSON.stringify(value));
    }
    
    getSetting(key) {
        const stmt = this.db.prepare("SELECT value FROM settings WHERE key = ?");
        const result = stmt.get(key);
        return result ? JSON.parse(result.value) : null;
    }
    
    close() {
        this.db.close();
    }
}

// Export singleton instance
export default new DatabaseManager();
```

### Logging System

Create `logger.js`:

```javascript
// logger.js - Application logging system
import { appendFile } from "fs/promises";
import { join } from "path";

export class Logger {
    constructor(logFile = "app.log") {
        this.logFile = logFile;
    }
    
    async log(level, message, data = null) {
        const timestamp = new Date().toISOString();
        const logEntry = {
            timestamp,
            level,
            message,
            data
        };
        
        const logString = `${timestamp} [${level.toUpperCase()}] ${message}${data ? ` ${JSON.stringify(data)}` : ''}\n`;
        
        try {
            await appendFile(this.logFile, logString);
        } catch (error) {
            console.error("Failed to write to log file:", error);
        }
        
        // Also log to console
        console.log(`[${level.toUpperCase()}] ${message}`, data || '');
    }
    
    info(message, data) {
        return this.log("info", message, data);
    }
    
    warn(message, data) {
        return this.log("warn", message, data);
    }
    
    error(message, data) {
        return this.log("error", message, data);
    }
    
    debug(message, data) {
        return this.log("debug", message, data);
    }
}

// Export singleton instance
export default new Logger();
```

## Complete Application Structure

```
bun-webui-app/
├── app.js                 # Main application
├── webui.js              # WebUI wrapper
├── fileManager.js        # File operations
├── networkManager.js     # Network operations
├── settingsManager.js    # Settings management
├── database.js           # Database integration
├── logger.js             # Logging system
├── index.html            # Main HTML file
├── example.txt           # Sample file
├── app-settings.json     # Application settings
├── package.json          # Project configuration
└── dist/                 # Build output
```

## Best Practices

1. **Error Handling**: Always wrap async operations in try-catch blocks
2. **Modular Code**: Organize functionality into separate modules
3. **Type Safety**: Use TypeScript for larger applications
4. **Security**: Validate all inputs and sanitize data
5. **Performance**: Use Bun's built-in optimizations
6. **Logging**: Implement proper logging for debugging

## Next Steps

1. Explore [Bun Documentation](https://bun.sh/docs)
2. Check out [WebUI Documentation](https://webui.me/docs/)
3. Try [WebUI Examples](https://github.com/webui-dev/webui)
4. Join [Bun Discord Community](https://discord.gg/CXdq2DP29u)

This tutorial covered:
- Installation with Bun
- WebUI integration
- File operations
- Network operations
- Settings management
- Complete application example
- Building and packaging

With these examples, you can build powerful cross-platform desktop applications using Bun JavaScript runtime and WebUI!