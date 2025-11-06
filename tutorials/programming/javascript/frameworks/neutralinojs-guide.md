# Complete Neutralinojs Tutorial for Beginners

Neutralinojs is a lightweight alternative to Electron for building cross-platform desktop apps with web technologies. This tutorial covers everything you need to know to build desktop applications with Neutralinojs.

## Installation

First, install Neutralinojs CLI globally using either npm or bun:

```bash
# Install Neutralinojs CLI with npm
npm install -g @neutralinojs/neu

# OR install with bun (recommended for faster installation)
bun install -g @neutralinojs/neu
```

Create a new project:
```bash
# Create new Neutralinojs project
neu create my-app
cd my-app

# Run the app
neu run
```

[Official Installation Guide](https://neutralino.js.org/docs/getting-started/installation)

## Project Structure

A basic Neutralinojs project structure:
```
my-app/
├── resources/
│   ├── index.html
│   ├── styles.css
│   └── js/
│       └── main.js
├── neutralino.config.json
└── neutralino.js (auto-generated)
```

## Basic HTML Setup

Edit `resources/index.html`:

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>My Neutralino App</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div id="app">
      <h1>Neutralino App</h1>
      <p id="info">Loading...</p>
      <button id="helloButton">Say Hello</button>
      <button id="openFileButton">Open File</button>
      <button id="quitButton">Quit App</button>
      <div id="output"></div>
    </div>
    
    <!-- Include Neutralinojs client library -->
    <script src="/neutralino.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
```

[HTML Structure Docs](https://neutralino.js.org/docs/how-to/use-a-frontend-library)

## Configuration

Edit `neutralino.config.json`:

```json
{
  "applicationId": "js.neutralino.sample",
  "version": "1.0.0",
  "defaultMode": "window",
  "port": 0,
  "documentRoot": "/resources/",
  "url": "/",
  "enableServer": true,
  "enableNativeAPI": true,
  "enableExtensions": true,
  "tokenSecurity": "one-time",
  "modes": {
    "window": {
      "title": "My Neutralino App",
      "width": 800,
      "height": 500,
      "minWidth": 400,
      "minHeight": 200,
      "icon": "/resources/icons/appIcon.png"
    }
  },
  "cli": {
    "binaryName": "myapp",
    "resourcesPath": "/resources/",
    "extensionsPath": "/extensions/",
    "clientLibrary": "/neutralino.js",
    "binaryVersion": "4.15.0",
    "clientVersion": "3.13.0"
  }
}
```

[Configuration Docs](https://neutralino.js.org/docs/configuration/neutralino-config)

## Main JavaScript Application

Edit `resources/js/main.js`:

```javascript
// Initialize Neutralino app
Neutralino.init();

// Wait for app to be ready
Neutralino.events.on("ready", async () => {
    // Update app info
    try {
        const appInfo = await Neutralino.app.getInfo();
        document.getElementById("info").innerHTML = `
            App ID: ${appInfo.id}<br>
            Version: ${appInfo.version}
        `;
    } catch (error) {
        console.error("Error getting app info:", error);
    }

    // Set up event listeners
    setupEventListeners();
});

// Set up all event listeners
function setupEventListeners() {
    // Hello button
    document.getElementById("helloButton").addEventListener("click", () => {
        Neutralino.os.showNotification("Hello", "Welcome to Neutralinojs!");
    });

    // Open file button
    document.getElementById("openFileButton").addEventListener("click", async () => {
        try {
            const response = await Neutralino.os.showDialogOpen({
                title: "Select a file",
                multiSelection: false
            });
            
            if(response.files && response.files.length > 0) {
                const filePath = response.files[0];
                document.getElementById("output").innerHTML = `
                    <p>Selected file: ${filePath}</p>
                `;
            }
        } catch (error) {
            console.error("Error opening file dialog:", error);
        }
    });

    // Quit button
    document.getElementById("quitButton").addEventListener("click", () => {
        Neutralino.app.exit();
    });
}

// Handle app close event
Neutralino.events.on("windowClose", () => {
    Neutralino.app.exit();
});
```

[App API Docs](https://neutralino.js.org/docs/api/app)

## Working with Files

Create a file manager example in `resources/js/fileManager.js`:

```javascript
// File operations with Neutralinojs
class FileManager {
    constructor() {
        this.currentPath = null;
    }

    // Read a file
    async readFile(filePath) {
        try {
            const content = await Neutralino.filesystem.readFile(filePath);
            return content;
        } catch (error) {
            throw new Error(`Failed to read file: ${error.message}`);
        }
    }

    // Write to a file
    async writeFile(filePath, content) {
        try {
            await Neutralino.filesystem.writeFile(filePath, content);
            return true;
        } catch (error) {
            throw new Error(`Failed to write file: ${error.message}`);
        }
    }

    // Create directory
    async createDirectory(dirPath) {
        try {
            await Neutralino.filesystem.createDirectory(dirPath);
            return true;
        } catch (error) {
            throw new Error(`Failed to create directory: ${error.message}`);
        }
    }

    // List directory contents
    async readDirectory(dirPath) {
        try {
            const entries = await Neutralino.filesystem.readDirectory(dirPath);
            return entries;
        } catch (error) {
            throw new Error(`Failed to read directory: ${error.message}`);
        }
    }

    // Delete file
    async removeFile(filePath) {
        try {
            await Neutralino.filesystem.removeFile(filePath);
            return true;
        } catch (error) {
            throw new Error(`Failed to delete file: ${error.message}`);
        }
    }

    // Get file stats
    async getStats(path) {
        try {
            const stats = await Neutralino.filesystem.getStats(path);
            return stats;
        } catch (error) {
            throw new Error(`Failed to get file stats: ${error.message}`);
        }
    }
}

// Example usage
const fileManager = new FileManager();

// Create a simple file editor UI
async function setupFileEditor() {
    const appDiv = document.getElementById("app");
    appDiv.innerHTML += `
        <div id="fileEditor">
            <h2>File Editor</h2>
            <input type="text" id="filePath" placeholder="Enter file path">
            <button id="loadFile">Load</button>
            <button id="saveFile">Save</button>
            <br><br>
            <textarea id="fileContent" rows="10" cols="50" placeholder="File content"></textarea>
        </div>
    `;

    // Load file button
    document.getElementById("loadFile").addEventListener("click", async () => {
        const filePath = document.getElementById("filePath").value;
        if (!filePath) return;

        try {
            const content = await fileManager.readFile(filePath);
            document.getElementById("fileContent").value = content;
        } catch (error) {
            Neutralino.os.showNotification("Error", error.message);
        }
    });

    // Save file button
    document.getElementById("saveFile").addEventListener("click", async () => {
        const filePath = document.getElementById("filePath").value;
        const content = document.getElementById("fileContent").value;
        
        if (!filePath) return;

        try {
            await fileManager.writeFile(filePath, content);
            Neutralino.os.showNotification("Success", "File saved successfully!");
        } catch (error) {
            Neutralino.os.showNotification("Error", error.message);
        }
    });
}

// Add to main.js ready event:
// setupFileEditor();
```

[Filesystem API Docs](https://neutralino.js.org/docs/api/filesystem)

## OS Operations

Create `resources/js/osOperations.js`:

```javascript
// Operating system operations
class OSOperations {
    // Show notification
    async showNotification(title, content, icon = null) {
        try {
            await Neutralino.os.showNotification(title, content, icon);
        } catch (error) {
            console.error("Notification error:", error);
        }
    }

    // Open URL in default browser
    async openUrl(url) {
        try {
            await Neutralino.os.open(url);
        } catch (error) {
            throw new Error(`Failed to open URL: ${error.message}`);
        }
    }

    // Get system information
    async getSystemInfo() {
        try {
            const info = {
                platform: await Neutralino.os.getOSInfo(),
                cpuInfo: await Neutralino.os.getStats(),
                memoryInfo: await Neutralino.computer.getMemoryInfo(),
                clipboard: await Neutralino.clipboard.read()
            };
            return info;
        } catch (error) {
            throw new Error(`Failed to get system info: ${error.message}`);
        }
    }

    // Copy text to clipboard
    async copyToClipboard(text) {
        try {
            await Neutralino.clipboard.write(text);
            return true;
        } catch (error) {
            throw new Error(`Failed to copy to clipboard: ${error.message}`);
        }
    }

    // Read from clipboard
    async readFromClipboard() {
        try {
            const text = await Neutralino.clipboard.read();
            return text;
        } catch (error) {
            throw new Error(`Failed to read clipboard: ${error.message}`);
        }
    }

    // Show open dialog
    async showOpenDialog(title = "Open File", multiSelection = false) {
        try {
            const response = await Neutralino.os.showDialogOpen({
                title: title,
                multiSelection: multiSelection
            });
            return response.files || [];
        } catch (error) {
            throw new Error(`Failed to show open dialog: ${error.message}`);
        }
    }

    // Show save dialog
    async showSaveDialog(title = "Save File") {
        try {
            const response = await Neutralino.os.showDialogSave({
                title: title
            });
            return response.files || [];
        } catch (error) {
            throw new Error(`Failed to show save dialog: ${error.message}`);
        }
    }

    // Show message box
    async showMessageBox(title, content, type = "INFO") {
        try {
            const response = await Neutralino.os.showMessageBox(title, content, type);
            return response;
        } catch (error) {
            throw new Error(`Failed to show message box: ${error.message}`);
        }
    }

    // Execute command
    async executeCommand(command, background = false) {
        try {
            const result = await Neutralino.os.execCommand(command, {
                background: background
            });
            return result;
        } catch (error) {
            throw new Error(`Failed to execute command: ${error.message}`);
        }
    }
}

// Example usage
const osOps = new OSOperations();

// System info panel
async function setupSystemInfoPanel() {
    const appDiv = document.getElementById("app");
    appDiv.innerHTML += `
        <div id="systemInfo">
            <h2>System Information</h2>
            <button id="getSystemInfo">Get System Info</button>
            <div id="systemInfoOutput"></div>
        </div>
    `;

    document.getElementById("getSystemInfo").addEventListener("click", async () => {
        try {
            const info = await osOps.getSystemInfo();
            document.getElementById("systemInfoOutput").innerHTML = `
                <pre>${JSON.stringify(info, null, 2)}</pre>
            `;
        } catch (error) {
            Neutralino.os.showNotification("Error", error.message);
        }
    });
}

// Add to main.js ready event:
// setupSystemInfoPanel();
```

[OS API Docs](https://neutralino.js.org/docs/api/os)

## Storage Operations

Create `resources/js/storageManager.js`:

```javascript
// Data storage with Neutralinojs
class StorageManager {
    // Set key-value pair
    async setItem(key, value) {
        try {
            await Neutralino.storage.setData(key, JSON.stringify(value));
            return true;
        } catch (error) {
            throw new Error(`Failed to set storage item: ${error.message}`);
        }
    }

    // Get value by key
    async getItem(key) {
        try {
            const data = await Neutralino.storage.getData(key);
            return JSON.parse(data);
        } catch (error) {
            // Return null if key doesn't exist
            if (error.code === "NE_ST_NOSTKEX") {
                return null;
            }
            throw new Error(`Failed to get storage item: ${error.message}`);
        }
    }

    // Remove item by key
    async removeItem(key) {
        try {
            await Neutralino.storage.setData(key, null);
            return true;
        } catch (error) {
            throw new Error(`Failed to remove storage item: ${error.message}`);
        }
    }

    // Get all keys
    async getKeys() {
        try {
            const keys = await Neutralino.storage.getKeys();
            return keys;
        } catch (error) {
            throw new Error(`Failed to get storage keys: ${error.message}`);
        }
    }
}

// Example usage
const storage = new StorageManager();

// Settings panel
async function setupSettingsPanel() {
    const appDiv = document.getElementById("app");
    appDiv.innerHTML += `
        <div id="settingsPanel">
            <h2>Settings</h2>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" placeholder="Enter username">
                <button id="saveUsername">Save</button>
            </div>
            <div>
                <label for="theme">Theme:</label>
                <select id="theme">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                </select>
                <button id="saveTheme">Save</button>
            </div>
            <div id="settingsOutput"></div>
        </div>
    `;

    // Load saved settings
    const savedUsername = await storage.getItem("username");
    const savedTheme = await storage.getItem("theme");
    
    if (savedUsername) {
        document.getElementById("username").value = savedUsername;
    }
    
    if (savedTheme) {
        document.getElementById("theme").value = savedTheme;
    }

    // Save username
    document.getElementById("saveUsername").addEventListener("click", async () => {
        const username = document.getElementById("username").value;
        try {
            await storage.setItem("username", username);
            Neutralino.os.showNotification("Success", "Username saved!");
        } catch (error) {
            Neutralino.os.showNotification("Error", error.message);
        }
    });

    // Save theme
    document.getElementById("saveTheme").addEventListener("click", async () => {
        const theme = document.getElementById("theme").value;
        try {
            await storage.setItem("theme", theme);
            Neutralino.os.showNotification("Success", "Theme saved!");
            
            // Apply theme
            document.body.className = theme;
        } catch (error) {
            Neutralino.os.showNotification("Error", error.message);
        }
    });
}

// Add to main.js ready event:
// setupSettingsPanel();
```

[Storage API Docs](https://neutralino.js.org/docs/api/storage)

## Network Operations

Create `resources/js/networkManager.js`:

```javascript
// Network operations with Neutralinojs
class NetworkManager {
    // Make HTTP request
    async makeRequest(url, options = {}) {
        try {
            // Default options
            const defaultOptions = {
                method: "GET",
                headers: {},
                ...options
            };

            // Use fetch API for HTTP requests
            const response = await fetch(url, defaultOptions);
            
            // Return response data
            const data = {
                status: response.status,
                statusText: response.statusText,
                headers: Object.fromEntries(response.headers.entries()),
                body: await response.text()
            };
            
            return data;
        } catch (error) {
            throw new Error(`Network request failed: ${error.message}`);
        }
    }

    // Get network interfaces
    async getNetworkInterfaces() {
        try {
            // This is a placeholder - Neutralino doesn't have direct network interface API
            // You could implement this with a custom extension or command execution
            const result = await Neutralino.os.execCommand("ipconfig", { background: true });
            return result.stdOut || "Network info not available";
        } catch (error) {
            return "Network info not available";
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
}

// Example usage
const network = new NetworkManager();

// API tester panel
async function setupApiTester() {
    const appDiv = document.getElementById("app");
    appDiv.innerHTML += `
        <div id="apiTester">
            <h2>API Tester</h2>
            <div>
                <input type="text" id="apiUrl" placeholder="Enter API URL" value="https://jsonplaceholder.typicode.com/posts/1">
                <select id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
                <button id="sendRequest">Send Request</button>
            </div>
            <div>
                <h3>Request Headers (JSON):</h3>
                <textarea id="headers" rows="4" cols="50">{"Content-Type": "application/json"}</textarea>
            </div>
            <div>
                <h3>Request Body (JSON):</h3>
                <textarea id="body" rows="4" cols="50"></textarea>
            </div>
            <div>
                <h3>Response:</h3>
                <button id="clearResponse">Clear</button>
                <pre id="responseOutput"></pre>
            </div>
        </div>
    `;

    // Send request button
    document.getElementById("sendRequest").addEventListener("click", async () => {
        const url = document.getElementById("apiUrl").value;
        const method = document.getElementById("method").value;
        
        if (!url) {
            Neutralino.os.showNotification("Error", "Please enter a URL");
            return;
        }

        try {
            // Parse headers
            let headers = {};
            try {
                headers = JSON.parse(document.getElementById("headers").value);
            } catch (e) {
                Neutralino.os.showNotification("Error", "Invalid headers JSON");
                return;
            }

            // Prepare options
            const options = { method, headers };
            
            // Add body for non-GET requests
            if (method !== "GET" && document.getElementById("body").value) {
                try {
                    options.body = document.getElementById("body").value;
                } catch (e) {
                    Neutralino.os.showNotification("Error", "Invalid body JSON");
                    return;
                }
            }

            // Make request
            const response = await network.makeRequest(url, options);
            
            // Display response
            document.getElementById("responseOutput").textContent = 
                JSON.stringify(response, null, 2);
        } catch (error) {
            document.getElementById("responseOutput").textContent = 
                `Error: ${error.message}`;
        }
    });

    // Clear response button
    document.getElementById("clearResponse").addEventListener("click", () => {
        document.getElementById("responseOutput").textContent = "";
    });
}

// Add to main.js ready event:
// setupApiTester();
```

[Network API Docs](https://neutralino.js.org/docs/api/network)

## Complete Example Application

Create a complete application in `resources/js/app.js`:

```javascript
// Complete Neutralinojs Application
class NeutralinoApp {
    constructor() {
        this.storage = new StorageManager();
        this.osOps = new OSOperations();
        this.fileManager = new FileManager();
        this.network = new NetworkManager();
        this.currentView = "home";
    }

    async init() {
        // Initialize Neutralino
        Neutralino.init();
        
        // Set up event listeners
        Neutralino.events.on("ready", () => this.onAppReady());
        Neutralino.events.on("windowClose", () => this.onWindowClose());
        
        // Set up global error handling
        window.addEventListener("error", (event) => {
            console.error("Global error:", event.error);
            this.osOps.showNotification("Error", `An error occurred: ${event.error.message}`);
        });
    }

    async onAppReady() {
        // Load saved theme
        const theme = await this.storage.getItem("theme");
        if (theme) {
            document.body.className = theme;
        }

        // Set up UI
        this.setupUI();
        
        // Show welcome notification
        this.osOps.showNotification("Welcome", "Neutralino App is ready!");
    }

    setupUI() {
        // Main app container
        document.body.innerHTML = `
            <div id="appContainer">
                <nav id="navbar">
                    <button class="nav-btn" data-view="home">Home</button>
                    <button class="nav-btn" data-view="files">Files</button>
                    <button class="nav-btn" data-view="settings">Settings</button>
                    <button class="nav-btn" data-view="network">Network</button>
                    <button id="quitApp">Quit</button>
                </nav>
                <main id="mainContent">
                    <div id="viewContainer"></div>
                </main>
            </div>
            
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                }
                
                body.dark {
                    background-color: #333;
                    color: white;
                }
                
                #navbar {
                    background-color: #f0f0f0;
                    padding: 10px;
                    border-bottom: 1px solid #ccc;
                }
                
                body.dark #navbar {
                    background-color: #444;
                    border-bottom: 1px solid #666;
                }
                
                .nav-btn {
                    margin-right: 10px;
                    padding: 8px 16px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                
                .nav-btn:hover {
                    background-color: #0056b3;
                }
                
                #mainContent {
                    padding: 20px;
                }
                
                .form-group {
                    margin-bottom: 15px;
                }
                
                .form-group label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }
                
                .form-group input, .form-group select, .form-group textarea {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }
                
                body.dark .form-group input, 
                body.dark .form-group select, 
                body.dark .form-group textarea {
                    background-color: #555;
                    color: white;
                    border: 1px solid #666;
                }
                
                button {
                    padding: 10px 15px;
                    background-color: #28a745;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                
                button:hover {
                    background-color: #218838;
                }
                
                #quitApp {
                    background-color: #dc3545;
                    float: right;
                }
                
                #quitApp:hover {
                    background-color: #c82333;
                }
                
                pre {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    padding: 10px;
                    border-radius: 4px;
                    overflow-x: auto;
                }
                
                body.dark pre {
                    background-color: #444;
                    border: 1px solid #666;
                }
            </style>
        `;

        // Set up navigation
        this.setupNavigation();
        
        // Show home view by default
        this.showView("home");
    }

    setupNavigation() {
        // Navigation buttons
        document.querySelectorAll(".nav-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const view = e.target.getAttribute("data-view");
                this.showView(view);
            });
        });

        // Quit button
        document.getElementById("quitApp").addEventListener("click", () => {
            Neutralino.app.exit();
        });
    }

    async showView(viewName) {
        this.currentView = viewName;
        const container = document.getElementById("viewContainer");
        
        switch(viewName) {
            case "home":
                container.innerHTML = `
                    <h1>Welcome to Neutralino App</h1>
                    <p>This is a complete example application built with Neutralinojs.</p>
                    <div class="form-group">
                        <button id="showNotification">Show Notification</button>
                        <button id="getAppInfo">Get App Info</button>
                    </div>
                    <div id="homeOutput"></div>
                `;
                
                document.getElementById("showNotification").addEventListener("click", () => {
                    this.osOps.showNotification("Hello", "This is a notification from Neutralino!");
                });
                
                document.getElementById("getAppInfo").addEventListener("click", async () => {
                    try {
                        const info = await Neutralino.app.getInfo();
                        document.getElementById("homeOutput").innerHTML = `
                            <pre>${JSON.stringify(info, null, 2)}</pre>
                        `;
                    } catch (error) {
                        document.getElementById("homeOutput").innerHTML = 
                            `<p>Error: ${error.message}</p>`;
                    }
                });
                break;
                
            case "files":
                container.innerHTML = `
                    <h1>File Manager</h1>
                    <div class="form-group">
                        <input type="text" id="filePath" placeholder="Enter file path">
                        <button id="browseFile">Browse</button>
                        <button id="loadFile">Load</button>
                        <button id="saveFile">Save</button>
                    </div>
                    <div class="form-group">
                        <textarea id="fileContent" rows="10" placeholder="File content"></textarea>
                    </div>
                    <div id="fileOutput"></div>
                `;
                
                document.getElementById("browseFile").addEventListener("click", async () => {
                    try {
                        const files = await this.osOps.showOpenDialog("Select a file");
                        if (files.length > 0) {
                            document.getElementById("filePath").value = files[0];
                        }
                    } catch (error) {
                        this.osOps.showNotification("Error", error.message);
                    }
                });
                
                document.getElementById("loadFile").addEventListener("click", async () => {
                    const filePath = document.getElementById("filePath").value;
                    if (!filePath) {
                        this.osOps.showNotification("Error", "Please enter a file path");
                        return;
                    }
                    
                    try {
                        const content = await this.fileManager.readFile(filePath);
                        document.getElementById("fileContent").value = content;
                        this.osOps.showNotification("Success", "File loaded successfully");
                    } catch (error) {
                        this.osOps.showNotification("Error", error.message);
                    }
                });
                
                document.getElementById("saveFile").addEventListener("click", async () => {
                    const filePath = document.getElementById("filePath").value;
                    const content = document.getElementById("fileContent").value;
                    
                    if (!filePath) {
                        this.osOps.showNotification("Error", "Please enter a file path");
                        return;
                    }
                    
                    try {
                        await this.fileManager.writeFile(filePath, content);
                        this.osOps.showNotification("Success", "File saved successfully");
                    } catch (error) {
                        this.osOps.showNotification("Error", error.message);
                    }
                });
                break;
                
            case "settings":
                container.innerHTML = `
                    <h1>Settings</h1>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" placeholder="Enter username">
                        <button id="saveUsername">Save</button>
                    </div>
                    <div class="form-group">
                        <label for="theme">Theme:</label>
                        <select id="theme">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                        <button id="saveTheme">Save</button>
                    </div>
                    <div id="settingsOutput"></div>
                `;
                
                // Load saved settings
                this.loadSettings();
                
                document.getElementById("saveUsername").addEventListener("click", async () => {
                    const username = document.getElementById("username").value;
                    try {
                        await this.storage.setItem("username", username);
                        this.osOps.showNotification("Success", "Username saved!");
                    } catch (error) {
                        this.osOps.showNotification("Error", error.message);
                    }
                });
                
                document.getElementById("saveTheme").addEventListener("click", async () => {
                    const theme = document.getElementById("theme").value;
                    try {
                        await this.storage.setItem("theme", theme);
                        document.body.className = theme;
                        this.osOps.showNotification("Success", "Theme saved!");
                    } catch (error) {
                        this.osOps.showNotification("Error", error.message);
                    }
                });
                break;
                
            case "network":
                container.innerHTML = `
                    <h1>Network Tools</h1>
                    <div class="form-group">
                        <input type="text" id="apiUrl" placeholder="Enter API URL" value="https://jsonplaceholder.typicode.com/posts/1">
                        <select id="method">
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="DELETE">DELETE</option>
                        </select>
                        <button id="sendRequest">Send Request</button>
                    </div>
                    <div class="form-group">
                        <label>Headers (JSON):</label>
                        <textarea id="headers" rows="3">{"Content-Type": "application/json"}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Body (for POST/PUT):</label>
                        <textarea id="body" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <button id="clearResponse">Clear Response</button>
                        <pre id="responseOutput"></pre>
                    </div>
                `;
                
                document.getElementById("sendRequest").addEventListener("click", async () => {
                    await this.sendApiRequest();
                });
                
                document.getElementById("clearResponse").addEventListener("click", () => {
                    document.getElementById("responseOutput").textContent = "";
                });
                break;
        }
    }

    async loadSettings() {
        try {
            const username = await this.storage.getItem("username");
            const theme = await this.storage.getItem("theme");
            
            if (username) {
                document.getElementById("username").value = username;
            }
            
            if (theme) {
                document.getElementById("theme").value = theme;
            }
        } catch (error) {
            console.error("Error loading settings:", error);
        }
    }

    async sendApiRequest() {
        const url = document.getElementById("apiUrl").value;
        const method = document.getElementById("method").value;
        
        if (!url) {
            this.osOps.showNotification("Error", "Please enter a URL");
            return;
        }

        try {
            // Parse headers
            let headers = {};
            try {
                headers = JSON.parse(document.getElementById("headers").value);
            } catch (e) {
                this.osOps.showNotification("Error", "Invalid headers JSON");
                return;
            }

            // Prepare options
            const options = { method, headers };
            
            // Add body for non-GET requests
            if (method !== "GET" && document.getElementById("body").value) {
                try {
                    options.body = document.getElementById("body").value;
                } catch (e) {
                    this.osOps.showNotification("Error", "Invalid body JSON");
                    return;
                }
            }

            // Show loading
            document.getElementById("responseOutput").textContent = "Loading...";
            
            // Make request
            const response = await this.network.makeRequest(url, options);
            
            // Display response
            document.getElementById("responseOutput").textContent = 
                JSON.stringify(response, null, 2);
        } catch (error) {
            document.getElementById("responseOutput").textContent = 
                `Error: ${error.message}`;
        }
    }

    onWindowClose() {
        Neutralino.app.exit();
    }
}

// Initialize app when Neutralino is ready
Neutralino.events.on("ready", () => {
    window.neutralinoApp = new NeutralinoApp();
    window.neutralinoApp.init();
});
```

Update `resources/index.html` to use the complete app:

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Complete Neutralino App</title>
  </head>
  <body>
    <!-- Loading message -->
    <div id="loading">
      <p>Loading application...</p>
    </div>
    
    <!-- Include Neutralinojs client library -->
    <script src="/neutralino.js"></script>
    
    <!-- Include all modules -->
    <script src="js/storageManager.js"></script>
    <script src="js/osOperations.js"></script>
    <script src="js/fileManager.js"></script>
    <script src="js/networkManager.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
```

## Building and Packaging

Add these commands to your `package.json` scripts:

```json
{
  "scripts": {
    "start": "neu run",
    "build": "neu build",
    "release": "neu release"
  }
}
```

Build your application:
```bash
# Build for current platform
neu build

# Build for specific platforms
neu build --release

# Create release packages
neu release
```

[Build Documentation](https://neutralino.js.org/docs/cli/neu-cli)

## Advanced Features

### Custom Extensions

Create `extensions/myExtension/main.js`:

```javascript
// Custom extension example
module.exports = {
    onReady: async () => {
        console.log("Extension loaded");
    },
    
    myCustomMethod: async (data) => {
        // Custom logic here
        return {
            success: true,
            message: "Custom method executed",
            data: data
        };
    }
};
```

### Using with Frontend Frameworks

To use with React, Vue, or other frameworks:

```bash
# Create React app inside resources
npx create-react-app resources

# Or with bun
bun create react resources
```

Then modify the React app to include Neutralinojs:

```javascript
// In your React app's index.js
import Neutralino from 'neutralinojs';

// Initialize Neutralino
Neutralino.init();

// Use in components
function App() {
    const showNotification = () => {
        Neutralino.os.showNotification('Hello', 'From React!');
    };
    
    return (
        <div className="App">
            <button onClick={showNotification}>
                Show Notification
            </button>
        </div>
    );
}
```

## Best Practices

1. **Error Handling**: Always wrap Neutralino API calls in try-catch blocks
2. **Async/Await**: Use async/await for better readability
3. **Modular Code**: Organize code into classes and modules
4. **Storage**: Use Neutralino's storage API for persistent data
5. **Security**: Validate all inputs and sanitize data

## Next Steps

1. Explore [Neutralinojs Documentation](https://neutralino.js.org/docs/)
2. Check out [Examples Repository](https://github.com/neutralinojs/neutralinojs/tree/main/examples)
3. Join [Discord Community](https://discord.gg/cybpp4guTJ)
4. Try [Neutralinojs Templates](https://github.com/neutralinojs/neutralinojs-minimal)

This tutorial covered:
- Installation with bun
- Project structure
- Configuration
- File operations
- OS operations
- Storage management
- Network operations
- Complete application example
- Building and packaging

With these examples, you can build powerful cross-platform desktop applications using web technologies and Neutralinojs!