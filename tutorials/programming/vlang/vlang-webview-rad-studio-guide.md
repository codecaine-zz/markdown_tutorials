# `vlang_webview_rad_studio` — Cross-Platform Webview Visual RAD Studio Complete Guide

`vlang_webview_rad_studio` is an enterprise-grade visual Rapid Application Development (RAD) IDE and desktop workstation suite powered by native [V (vlang)](https://vlang.io) and lightweight, hardware-accelerated OS Webviews (macOS Cocoa / WebKit, Windows Win32 / Edge WebView2, and Linux GTK / WebKit2GTK).

Repository: [codecaine-zz/vlang_webview_rad_studio](https://github.com/codecaine-zz/vlang_webview_rad_studio)

---

## 📚 Table of Contents

1. [Architectural Overview & Lineage](#1-architectural-overview--lineage)
2. [Prerequisites & Webview Setup](#2-prerequisites--webview-setup)
3. [60-Second Copy-Paste Starter Application](#3-60-second-copy-paste-starter-application)
4. [Visual RAD Form Designer Architecture](#4-visual-rad-form-designer-architecture)
   - [The Borland Delphi / Visual Basic Workflow](#the-borland-delphi--visual-basic-workflow)
   - [70+ Component Palette Catalog](#70-component-palette-catalog)
   - [Object Inspector & Property Grid](#object-inspector--property-grid)
   - [Code Generation Engine](#code-generation-engine)
5. [Native Window Management & Placement Presets](#5-native-window-management--placement-presets)
   - [Inheritance from `vlang_macos_webview_app_template`](#inheritance-from-vlang_macos_webview_app_template)
   - [9-Point Screen Placement Geometry](#9-point-screen-placement-geometry)
   - [Stay-on-Top Pinning & Fullscreen Control](#stay-on-top-pinning--fullscreen-control)
6. [Bidirectional IPC & JavaScript-to-V Bindings](#6-bidirectional-ipc--javascript-to-v-bindings)
7. [The 42-Theme Design System](#7-the-42-theme-design-system)
8. [Bundled Developer Utilities (`vlang_utils` 40-Module Suite)](#8-bundled-developer-utilities-vlang_utils-40-module-suite)
9. [Enterprise Production Workstations (18 Studios)](#9-enterprise-production-workstations-18-studios)
10. [Companion Headless CLI Tools (16 Utilities)](#10-companion-headless-cli-tools-16-utilities)
11. [Interactive Demos Suite (66 Demos)](#11-interactive-demos-suite-66-demos)
12. [Cross-Platform Packaging & Desktop Bundling (`build.vsh`)](#12-cross-platform-packaging--desktop-bundling-buildvsh)

---

## 1. Architectural Overview & Lineage

`vlang_webview_rad_studio` unifies four foundational open-source codebases authored by [@codecaine-zz](https://github.com/codecaine-zz):

```text
               ┌─────────────────────────────────────────────────────────────┐
               │                  bun_rad_studio (IDE Core)                  │
               │         Visual Designer, 70+ Drag-and-Drop Controls         │
               └──────────────────────────────┬──────────────────────────────┘
                                              │ (Ported from TypeScript to V)
┌─────────────────────────────────────────────▼──────────────────────────────┐
│                          vlang_webview_rad_studio                          │
│                  Native V + Lightweight OS Webview Engine                  │
└──────────────▲──────────────────────────────▲──────────────────────────────┘
               │                              │
┌──────────────┴──────────────┐ ┌─────────────┴──────────────┐ ┌─────────────┴──────────────┐
│vlang_macos_webview_app_template│ │        simple_gg /       │ │        vlang_utils         │
│Native Window Geometry & IPC │ │      vlang_simplegui       │ │ 40 Production Utility Pkgs │
│Cocoa window_helper.m Bridge │ │ RAD System & Stdlib Tools  │ │ v2.0.0 Hardened Standard   │
└─────────────────────────────┘ └────────────────────────────┘ └────────────────────────────┘
```

- **`vlang_macos_webview_app_template` Foundation**: Serves as the native window management and Webview lifecycle substrate. Provides C/C++ Webview bindings, Cocoa Objective-C window helper integration (`window_helper.m`), 9-point placement geometry, stay-on-top window pinning (`set_always_on_top`), and fullscreen toggling.
- **`bun_rad_studio` Blueprint**: Directly ported to native V, providing the Borland Delphi & Visual Basic visual form designer architecture, 70+ drag-and-drop components, anchor & docking layout engines, property grid, 10 application templates, non-visual component tray, and 42-theme design system.
- **`simple_gg` & `vlang_simplegui` Toolkits**: Contributed the native runtime toolkits (`system/sys.v` and `system/stdlib.v`): process execution (`exec_safe`), real-time hardware telemetry (CPU, RAM, battery, network ping), native dialogs, clipboard, cryptography, and encoders.
- **`vlang_utils` Suite**: Bundles all **40 modular developer packages** from `vlang_utils` v2.0.0 (`webutils`, `jsonutils`, `markdownutils`, `sqliteutils`, `cryptoutils`, `cacheutils`, `flowutils`, etc.).
- **Zero Heavy Runtimes**: Relies on the host operating system's pre-installed Webview engine. Output executables are compact (~3MB to ~8MB) with instant startup.

---

## 2. Prerequisites & Webview Setup

### System Prerequisites

```bash
# macOS
xcode-select --install
brew install v

# Linux (Ubuntu / Debian)
sudo apt update && sudo apt install -y build-essential libwebkit2gtk-4.1-dev libgtk-3-dev
```

### Install V Webview Dependency

Install the official V webview module globally via VPM:

```bash
v install ttytm.webview

# Or install directly from GitHub:
v install --git https://github.com/vlang/webview
```

### Clone and Launch the Visual Studio

```bash
git clone https://github.com/codecaine-zz/vlang_webview_rad_studio.git
cd vlang_webview_rad_studio

# Launch the visual RAD Studio IDE
v run main.v
```

---

## 3. 60-Second Copy-Paste Starter Application

Create a standalone desktop Webview application in `app.v`:

```v
module main

import webview
import json

struct SystemStats {
    cpu_cores int
    status    string
}

fn main() {
    // 1. Create a native Webview window
    mut w := webview.create_window(
        title: 'Vlang Webview RAD App'
        width: 800
        height: 600
        debug: true
    )

    // 2. Position window using 9-point placement preset from template
    w.set_placement('center')
    w.set_always_on_top(false)

    // 3. Bind native V function callable directly from JavaScript
    w.bind('fetchStats', fn (w &webview.Window, args string) string {
        stats := SystemStats{
            cpu_cores: 8
            status: 'Operational'
        }
        return json.encode(stats)
    })

    // 4. Load HTML UI with interactive JavaScript bridge
    html_content := '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #1e1e2e; color: #cdd6f4; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            button { background: #89b4fa; color: #11111b; border: none; padding: 12px 24px; font-size: 16px; font-weight: bold; border-radius: 8px; cursor: pointer; transition: 0.2s; }
            button:hover { background: #b4befe; }
            #output { margin-top: 20px; font-size: 18px; color: #a6e3a1; }
        </style>
    </head>
    <body>
        <h1>Vlang Webview RAD Studio</h1>
        <button onclick="loadData()">Fetch System Metrics</button>
        <div id="output">Click button to query native V backend</div>
        <script>
            async function loadData() {
                const res = await window.fetchStats();
                const data = JSON.parse(res);
                document.getElementById("output").innerText = "CPU Cores: " + data.cpu_cores + " | Status: " + data.status;
            }
        </script>
    </body>
    </html>
    '

    w.navigate('data:text/html,' + html_content)
    w.run()
}
```

Run directly:

```bash
v run app.v
```

---

## 4. Visual RAD Form Designer Architecture

### The Borland Delphi / Visual Basic Workflow

The visual RAD designer delivers the legendary rapid-prototyping workflow of classic desktop RAD studios:

1. **Component Palette**: Choose from 70+ controls categorized by function.
2. **Form Canvas**: Pixel-grid visual canvas with alignment snapping, anchor guidelines, and coordinate placement.
3. **Object Inspector**: Live property editor modifying titles, bounds, colors, fonts, and event callbacks.
4. **Code Generator**: Generates clean, idiomatic V source code from visual FormSpec JSON descriptions.

### 70+ Component Palette Catalog

| Category | Controls |
| :--- | :--- |
| **Standard** | Button, Label, TextBox, Memo/TextArea, CheckBox, RadioButton, ComboBox, ListBox, GroupBox, Panel |
| **Additional** | SpeedButton, Image, Shape, ScrollBox, Splitter, CheckListBox, ValueListEditor, HeaderControl |
| **Data-Aware** | DataGrid, DBTable, DBNavigator, DBText, DBEdit, DBMemo, DBImage, DBCalendar |
| **Advanced Controls** | TagInput, RangeSlider, CodeEditor, FileDropZone, Sparkline, Pagination, ColorPalette, StatusBar |
| **Dialogs** | OpenFileDialog, SaveFileDialog, ColorDialog, FontDialog, FindDialog, ReplaceDialog |
| **System & Non-Visual** | Timer, OpenHTTPClient, FileSystemWatcher, SQLiteConnector, TrayIcon, KeyShortcutListener |

### Object Inspector & Property Grid

The Object Inspector inspects the active component on the canvas:

- **Geometry**: `x`, `y`, `width`, `height`, `min_width`, `max_height`.
- **Layout Anchors**: `top`, `bottom`, `left`, `right` anchors for responsive resizing.
- **Appearance**: `theme`, `bg_color`, `text_color`, `border_radius`, `font_family`, `font_size`.
- **Events**: `on_click`, `on_change`, `on_focus`, `on_blur`, `on_drop`.

### Code Generation Engine

When you click **Generate Code** in the designer, the RAD engine serializes the canvas into a FormSpec JSON and generates clean V code:

```v
// Auto-generated by Vlang Webview RAD Studio
module main

import webview

fn create_main_form() &webview.Window {
    mut win := webview.create_window(title: 'Customer Directory', width: 680, height: 420)
    win.set_placement('center')
    // Generated UI bindings and DOM nodes...
    return win
}
```

---

## 5. Native Window Management & Placement Presets

### Inheritance from `vlang_macos_webview_app_template`

The core window management substrate is derived directly from `vlang_macos_webview_app_template`:

- **Direct Cocoa Bridging (`window_helper.m`)**: Objective-C runtime hooks to `NSWindow`, `NSApplication`, and `WKWebView` on macOS for borderless styling, title bar customization, and transparency.
- **Cross-Platform Parity**: Employs equivalent Win32 and GTK bindings on Windows and Linux.

### 9-Point Screen Placement Geometry

Control window initial placement using the 9 standard screen anchor presets:

```v
// Available Presets:
// 'center'        - Centered horizontally and vertically on active monitor
// 'upper_left'    - Top-left corner with margin
// 'upper_right'   - Top-right corner with margin
// 'top_center'    - Centered horizontally, docked at top
// 'bottom_left'   - Bottom-left corner with margin
// 'bottom_right'  - Bottom-right corner with margin
// 'bottom_center' - Centered horizontally, docked at bottom
// 'center_left'   - Centered vertically, docked on left edge
// 'center_right'  - Centered vertically, docked on right edge

w.set_placement('center')
```

### Stay-on-Top Pinning & Fullscreen Control

```v
// Pin window on top of all other desktop applications
w.set_always_on_top(true)

// Toggle native OS fullscreen mode
w.toggle_fullscreen()
```

---

## 6. Bidirectional IPC & JavaScript-to-V Bindings

### Binding Native V Functions to JavaScript

Expose any V logic to the Webview DOM via `w.bind()`:

```v
// Bind JavaScript: window.saveCustomer(jsonString) -> V backend
w.bind('saveCustomer', fn (w &webview.Window, args string) string {
    println('Saving customer payload: ${args}')
    // Execute database insert or validation...
    return '{"status": "success", "id": 104}'
})
```

### Executing JavaScript from V

Evaluate arbitrary JavaScript inside the Webview DOM from V:

```v
// Push live telemetry data into frontend chart
w.eval('window.updateTelemetry({ cpu: 45.2, ram: 68.1 });')
```

---

## 7. The 42-Theme Design System

`vlang_webview_rad_studio` includes a built-in 42-theme design system covering modern dark modes, light modes, and retro palettes:

- **Modern Dark Themes**: `Catppuccin Mocha`, `Tokyo Night`, `Nord`, `Dracula`, `One Dark`, `Gruvbox Dark`, `Rosé Pine`, `Cyberpunk`, `Monokai Pro`.
- **Clean Light Themes**: `Apple Light`, `GitHub Light`, `Solarized Light`, `Nord Light`, `Catppuccin Latte`.
- **High-Contrast & Accessibility**: `OLED Black`, `High Contrast Dark`, `Matrix Terminal`.

Themes automatically apply CSS variables to all canvas controls:

```css
:root {
    --bg-primary: #1e1e2e;
    --surface: #313244;
    --accent: #89b4fa;
    --text-primary: #cdd6f4;
    --border: #45475a;
}
```

---

## 8. Bundled Developer Utilities (`vlang_utils` 40-Module Suite)

The full **40-module utility suite** from `vlang_utils` v2.0.0 is directly accessible in any RAD Studio project:

```v
import fileutils
import sqliteutils
import webutils
import jsonutils
import markdownutils
import cryptoutils
import cacheutils
import asyncutils
```

---

## 9. Enterprise Production Workstations (18 Studios)

The `applications/` directory contains **18 enterprise desktop studio applications**:

| Workstation | Entry Point | Core Capabilities |
| :--- | :--- | :--- |
| **System Studio** | `applications/system_studio.v` | Live CPU, RAM, disk I/O, process killer, battery health monitor |
| **Database Studio** | `applications/database_studio.v` | SQLite visual schema browser, SQL query editor, table exporter |
| **Docker Studio** | `applications/docker_studio.v` | Container lifecycle, live logs streamer, image manager |
| **API Studio** | `applications/api_studio.v` | REST API request builder, header inspector, JSON tree viewer |
| **Network Studio** | `applications/network_studio.v` | Nmap scanner, ping diagnostics, DNS resolver, Wi-Fi info |
| **Git Studio** | `applications/git_studio.v` | Visual branch graph, diff inspector, commit manager |
| **DevTools Studio** | `applications/devtools_studio.v` | Developer utility swiss army knife (hashes, encoders, generators) |
| **Markdown Studio** | `applications/markdown_studio.v` | Split-pane live Markdown editor with HTML preview |
| **JSON Studio** | `applications/json_studio.v` | RFC 6901 pointer query explorer, formatter & structural diff |
| **Task Manager** | `applications/task_manager_studio.v`| Real-time process monitor with CPU/RAM metrics and process kill |
| **Watcher Studio** | `applications/watcher_studio.v` | Real-time filesystem watcher with debounced triggers |
| **Process Studio** | `applications/process_studio.v` | Safe subprocess execution manager with live output streaming |
| **App Bundler** | `applications/app_bundler_studio.v`| macOS .app packager with Retina icon generator |
| **Color Studio** | `applications/color_studio.v` | Hex/RGB/HSL/OKLCH color picker, contrast checker, palette generator |
| **Crypto Studio** | `applications/crypto_studio.v` | Password generator, SHA/HMAC hasher, JWT validator |
| **DataConvert Studio**| `applications/dataconvert_studio.v` | Data format converter between JSON, CSV, YAML, and TOML |
| **Env Studio** | `applications/env_studio.v` | Environment variable manager with `.env` file editor |
| **Finder Studio** | `applications/finder_studio.v` | Fast multi-threaded file search and regex replacement workbench |

Run any application directly:

```bash
v run applications/system_studio.v
v run applications/database_studio.v
v run applications/api_studio.v
v run applications/docker_studio.v
```

---

## 10. Companion Headless CLI Tools (16 Utilities)

Every visual studio in `applications/` has a headless counterpart in `cli_apps/` for scripting, CI/CD pipelines, and remote SSH administration:

```bash
# System hardware telemetry in JSON format
v run cli_apps/system_cli.v --json

# Query SQLite database from CLI
v run cli_apps/database_cli.v --db app.db "SELECT * FROM users;"

# Test REST endpoint
v run cli_apps/api_cli.v GET https://api.github.com

# Convert data formats from CLI
v run cli_apps/dataconvert_cli.v --from json --to csv input.json output.csv
```

---

## 11. Interactive Demos Suite (66 Demos)

The `demos/` directory contains **66 runnable demo scripts** demonstrating visual controls, layouts, templates, and all 40 utility modules:

```bash
# Run visual control demos
v run demos/01_standard_controls.v
v run demos/02_advanced_modern_controls.v
v run demos/04_window_placement_and_pin.v
v run demos/08_analytics_dashboard_template.v
v run demos/09_file_explorer_ide_template.v
v run demos/10_db_studio_query_editor_template.v
v run demos/17_simplegui_layout_types_showcase.v

# Run utility integration demos
v run demos/demo_webutils.v
v run demos/demo_jsonutils.v
v run demos/demo_markdownutils.v
v run demos/demo_sqliteutils.v

# Run all demos sequentially
v run demos/run_all_demos.v
```

---

## 12. Cross-Platform Packaging & Desktop Bundling (`build.vsh`)

The built-in bundler (`build.vsh`) compiles, packages, and signs native executables and icons into platform-specific bundles:

```bash
# 1. Build standalone macOS .app bundle
v run build.vsh -n "RAD Studio" main.v

# 2. Build a specific studio into a .app bundle
v run build.vsh -n "System Studio" applications/system_studio.v

# 3. Build Windows installer / standalone executable
v run build.vsh -t windows -n "SystemStudio" applications/system_studio.v

# 4. Build Linux desktop package
v run build.vsh -t linux -n "SystemStudio" applications/system_studio.v
```
