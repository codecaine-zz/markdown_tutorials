# SimpleGUI — Native macOS GUIs in V — Complete Project Guide

A complete, production-grade guide to **SimpleGUI** (`codecaine-zz/vlang_simplegui`), a native Cocoa desktop GUI starter framework and Rapid Application Development (RAD) ecosystem written in the **V programming language** (`vlang`), featuring a built-in Delphi/VB-inspired Visual UI Designer, a headless CLI toolkit (`simplecli`), 30 developer utility modules, and 70+ native macOS demos.

---

## 📑 Table of Contents

- [1. Overview & Architectural Philosophy](#1-overview-architectural-philosophy)
  - [Native Cocoa without Objective-C](#native-cocoa-without-objective-c)
  - [The Delphi & Visual Basic RAD Ergonomics](#the-delphi-visual-basic-rad-ergonomics)
  - [Platform Support Matrix](#platform-support-matrix)
- [2. Requirements & Installation](#2-requirements-installation)
  - [Prerequisites](#prerequisites)
  - [Installing via VPM or Git](#installing-via-vpm-or-git)
  - [Repository Setup & Directory Structure](#repository-setup-directory-structure)
- [3. 60-Second Copy-Paste Starter Application](#3-60-second-copy-paste-starter-application)
- [4. Native Cocoa Window Architecture & Configuration](#4-native-cocoa-window-architecture-configuration)
  - [Window Options & Dimensions](#window-options-dimensions)
  - [Screen Positioning & Multi-Display Support](#screen-positioning-multi-display-support)
  - [Native macOS Menus & Status Items](#native-macos-menus-status-items)
- [5. 40+ Native macOS Cocoa Controls Catalog](#5-40-native-macos-cocoa-controls-catalog)
  - [Standard Cocoa Controls](#standard-cocoa-controls)
  - [Rich macOS Widgets (TokenField, ComboBox, Ratings)](#rich-macos-widgets-tokenfield-combobox-ratings)
  - [Dashboard & Data Controls (Charts, Grids, TreeViews)](#dashboard-data-controls-charts-grids-treeviews)
- [6. Advanced Layout Engine: Grids, Flexbox & Cards](#6-advanced-layout-engine-grids-flexbox-cards)
  - [CSS Multi-Column Grid Containers](#css-multi-column-grid-containers)
  - [Flexbox Containers & Alignment Modifiers](#flexbox-containers-alignment-modifiers)
  - [Card Containers & Nested Hierarchies](#card-containers-nested-hierarchies)
- [7. Curated Themes & State Persistence (18 Themes)](#7-curated-themes-state-persistence-18-themes)
  - [Built-in Theme Catalog](#built-in-theme-catalog)
  - [Cross-App Theme Synchronization](#cross-app-theme-synchronization)
- [8. RAD Visual UI Designer & Code Studio](#8-rad-visual-ui-designer-code-studio)
  - [Launching the Visual Designer Studio](#launching-the-visual-designer-studio)
  - [WYSIWYG Canvas & Component Inspector](#wysiwyg-canvas-component-inspector)
  - [1-Click Code Generation & Live Test Run](#1-click-code-generation-live-test-run)
  - [RAD Code Explorer & Live Previewer](#rad-code-explorer-live-previewer)
- [9. Form Automation & Validation Engine](#9-form-automation-validation-engine)
  - [Auto-Generating Forms from Structs](#auto-generating-forms-from-structs)
  - [Compile-Time Struct Tag Validation](#compile-time-struct-tag-validation)
- [10. Centralized Security & Command Injection Prevention](#10-centralized-security-command-injection-prevention)
  - [The Shell Injection Threat](#the-shell-injection-threat)
  - [Safe Command Execution: exec_safe](#safe-command-execution-exec_safe)
  - [POSIX Argument & Path Escaping](#posix-argument-path-escaping)
- [11. Neutralino-Inspired OS & System APIs](#11-neutralino-inspired-os-system-apis)
  - [Hardware Telemetry & Resource Probing](#hardware-telemetry-resource-probing)
  - [Network Interfaces, Wi-Fi & Port Scanning](#network-interfaces-wi-fi-port-scanning)
  - [macOS Dock Badging, Bouncing & AppleScript Notifications](#macos-dock-badging-bouncing-applescript-notifications)
- [12. V Standard Library Integrations](#12-v-standard-library-integrations)
  - [HTTP & WebSocket Client Threads](#http-websocket-client-threads)
  - [Cryptography, Hashing & Passwords](#cryptography-hashing-passwords)
  - [High-Performance Compression (Gzip, Zstd)](#high-performance-compression-gzip-zstd)
  - [Parsers & Collections (TOML, JSON, Stacks, Queues)](#parsers-collections-toml-json-stacks-queues)
- [13. SimpleCLI: Headless Console & RAD Toolkit](#13-simplecli-headless-console-rad-toolkit)
  - [CLI Flag & Argument Parsing](#cli-flag-argument-parsing)
  - [Interactive Prompts & Multi-Step Pipelines](#interactive-prompts-multi-step-pipelines)
  - [Multi-Level Structured Logging](#multi-level-structured-logging)
- [14. Developer Utility Suite (30 Modules)](#14-developer-utility-suite-30-modules)
  - [Core Utility Catalog](#core-utility-catalog)
  - [Practical Examples: strutils, sqliteutils, fileutils](#practical-examples-strutils-sqliteutils-fileutils)
- [15. Demos Suite & Production Workstations](#15-demos-suite-production-workstations)
  - [70+ Interactive Demos Catalog](#70-interactive-demos-catalog)
  - [30+ Production Workstations (Fd, Rg, SQLite, Media Hub)](#30-production-workstations-fd-rg-sqlite-media-hub)
- [16. Compiling & Packaging Standalone macOS Apps](#16-compiling-packaging-standalone-macos-apps)
  - [Compiling Production Binaries](#compiling-production-binaries)
  - [Bundling into macOS .app & Creating DMG Installers](#bundling-into-macos-app-creating-dmg-installers)
- [17. Tutorial: Building a Native macOS System Monitor App](#17-tutorial-building-a-native-macos-system-monitor-app)
  - [Step 1: Application Skeleton & Layout](#step-1-application-skeleton-layout)
  - [Step 2: Polling OS Telemetry with Interval Timers](#step-2-polling-os-telemetry-with-interval-timers)
  - [Step 3: Hooking up Native Alerts & Dock Badges](#step-3-hooking-up-native-alerts-dock-badges)
  - [Step 4: Compiling & Running the App](#step-4-compiling-running-the-app)
- [18. Quick Reference Cheat Sheet & FAQ](#18-quick-reference-cheat-sheet-faq)
  - [Common CLI Commands](#common-cli-commands)
  - [Troubleshooting & Gotchas](#troubleshooting-gotchas)

---

## 1. Overview & Architectural Philosophy

### Native Cocoa without Objective-C

Building graphical applications on macOS has traditionally forced developers to make an uncomfortable trade-off:
1. **Apple's Native Stack (Swift / Objective-C)**: Beautiful, true macOS UI widgets with native text rendering, accessibility, and high performance, but tied to heavy IDEs (Xcode) and steep language learning curves.
2. **Web Wrappers (Electron / Tauri / Webview)**: Familiar web technologies, but rendering non-native DOM elements that don't match macOS system controls, with higher memory consumption and cold-boot delays.
3. **Cross-Platform Canvas Toolkits (Flutter / Qt / Dear ImGui)**: Custom simulated controls drawn onto OpenGL or Metal canvases that feel foreign on macOS (missing native spellcheck, dictionary popups, standard keyboard shortcuts, and voice-over accessibility).

**SimpleGUI** solves this problem by directly bridging the **V programming language** to Apple's native **Cocoa (`AppKit`)** framework using lightweight C/Objective-C bindings (`window.m` and `window.h`). When you call `win.add_button()`, SimpleGUI does not draw pixels onto a canvas—it instantiates a genuine Apple `NSButton` managed directly by macOS `NSWindow` and `NSView`.

```
┌─────────────────────────────────────────────────────────────┐
│                 SimpleGUI V Application                     │
│                  (main.v / developer app)                   │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│                   SimpleGUI API Layer (V)                   │
│   controls.v • window.v • layout.v • theming.v • dialogs.v  │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│               Objective-C / Cocoa Bridge                    │
│                 (window.h / window.m)                       │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│                 Apple macOS AppKit Framework                │
│     NSWindow • NSButton • NSTextField • NSComboBox • etc.   │
└─────────────────────────────────────────────────────────────┘
```

### The Delphi & Visual Basic RAD Ergonomics

Inspired by Borland Delphi and Visual Basic 6, SimpleGUI maximizes developer velocity:
- **Zero-Boilerplate Setup**: Launch a complete, functional window in fewer than 10 lines of code.
- **Fluent Method Chaining**: Configure controls inline using dots (e.g. `win.add_input('txt_name', 'Placeholder').width(250).tooltip('Enter name')`).
- **Visual RAD Designer**: Drag and drop controls visually and instantly export clean, formatted V code.
- **Headless Companion (`simplecli`)**: Use the exact same system calls, networking tools, and logging APIs in non-GUI terminal scripts.
- **30 Developer Utility Modules**: Instant access to `sqliteutils`, `strutils`, `httputils`, `fileutils`, `cacheutils`, and more without third-party dependencies.

### Platform Support Matrix

| Platform | Architecture | Backend | Status |
| :--- | :--- | :--- | :--- |
| **macOS (Apple Silicon arm64)** | M1 / M2 / M3 / M4 | Native Apple Cocoa (`AppKit`) | Tier 1 (Primary Platform) |
| **macOS (Intel x86_64)** | Intel Core i5 / i7 / i9 | Native Apple Cocoa (`AppKit`) | Tier 1 (Full Support) |
| **Linux (x86_64 / arm64)** | Ubuntu, Debian, Arch | GTK4 / WebKit Bridge | Architecture Layer Planned |
| **Windows (x64)** | Windows 10 / 11 | Win32 / Direct2D | Architecture Layer Planned |

---

## 2. Requirements & Installation

### Prerequisites

1. **macOS**: macOS 11 (Big Sur), macOS 12 (Monterey), macOS 13 (Ventura), macOS 14 (Sonoma), or macOS 15 (Sequoia).
2. **V Language**: Installed and present on your `PATH`.
   ```bash
   # Verify V installation
   v version
   ```
3. **Xcode Command Line Tools**: Required for the native Cocoa C compiler bridge.
   ```bash
   xcode-select --install
   ```

### Installing via VPM or Git

You can install SimpleGUI as a global V module using the V Package Manager (VPM):

```bash
# Install via V Package Manager (VPM)
v install codecaine-zz.simplegui

# Or install directly from GitHub
v install --git https://github.com/codecaine-zz/vlang_simplegui
```

Once installed, import it into any V file with `import simplegui`.

### Repository Setup & Directory Structure

To work directly with the complete repository, demos, visual designer, and enterprise applications:

```bash
# Clone the official repository
git clone https://github.com/codecaine-zz/vlang_simplegui.git
cd vlang_simplegui

# Check dependencies
./install_homebrew_dependencies.sh
```

Key repository directories:
- `controls.v`, `window.v`, `layout.v`, `theming.v`: Core SimpleGUI library modules.
- `window.m`, `window.h`: Native Cocoa Objective-C bridge.
- `tools/ui_designer.v`: Delphi-style Visual RAD Form Designer.
- `tools/vlang_simple_gui_previewer.v`: Live code previewer and studio IDE.
- `demos/`: Over 70 runnable demo programs testing all features.
- `applications/`: Over 30 production desktop workstation utilities (`fd_studio.v`, `sqlite_studio.v`, `rg_studio.v`, etc.).
- `simplecli/`: Zero-window headless console RAD toolkit.
- `strutils/`, `sqliteutils/`, `fileutils/`, ...: 30 standalone utility packages.

---

## 3. 60-Second Copy-Paste Starter Application

Create a file named `hello_simplegui.v`:

```v
module main

import simplegui

fn main() {
	// 1. Create a native macOS window
	mut win := simplegui.new_window(
		title: 'Hello SimpleGUI'
		width: 420
		height: 280
		theme: 'apple_dark'
	)

	// 2. Add an introductory heading label
	win.add_label('lbl_title', 'Welcome to Native macOS GUIs!')
		.font_size(18)
		.bold()

	// 3. Add a labeled input field
	win.add_text_input('txt_name', 'Enter your name...')
		.width(260)

	// 4. Add a button with a click event handler
	win.add_button('btn_greet', 'Click Me!', fn (mut w simplegui.Window) {
		name := w.get_text('txt_name')
		if name == '' {
			w.alert('Please enter your name first!')
			return
		}
		w.alert('Hello, ${name}! Welcome to V Native Cocoa.')
	}).variant(.primary)

	// 5. Run the native macOS event loop
	win.run()
}
```

Run it immediately with V:

```bash
v run hello_simplegui.v
```

In under a second, a native Apple Cocoa window appears with system font smoothing, keyboard shortcuts, and dark mode theming!

---

## 4. Native Cocoa Window Architecture & Configuration

### Window Options & Dimensions

`simplegui.new_window` accepts a configuration struct controlling geometry, styling, and behavior:

```v
mut win := simplegui.new_window(
	title: 'Pro Inspector'
	width: 800
	height: 600
	min_width: 400
	min_height: 300
	theme: 'github_dark'
	resizable: true
	closable: true
	miniaturizable: true
	always_on_top: false
	center: true
)
```

Useful window runtime methods:
- `win.center()`: Re-centers the window on the active display.
- `win.set_title(title string)`: Dynamically changes the titlebar text.
- `win.set_always_on_top(flag bool)`: Toggles floating window level (`NSFloatingWindowLevel`).
- `win.close()`: Programmatically closes the window and terminates the event loop.

### Screen Positioning & Multi-Display Support

SimpleGUI provides built-in position helpers:

```v
// Position presets
win.set_position_preset(.top_right)
win.set_position_preset(.bottom_left)

// Manual coordinates (Cocoa coordinates: x from left, y from top/bottom)
win.set_position(120, 100)
```

### Native macOS Menus & Status Items

Add top-level application menus and tray status icons:

```v
// Configure the standard macOS Application Menu
win.add_menu_item('File', 'New Project', 'Cmd+N', fn (mut w simplegui.Window) {
	w.notify('New project triggered')
})

win.add_menu_item('File', 'Save File', 'Cmd+S', fn (mut w simplegui.Window) {
	w.notify('File saved!')
})

// Add a native macOS system tray status item (Menu Bar icon)
win.add_tray_icon('MyApp', fn (mut w simplegui.Window) {
	w.unminimize()
	w.bring_to_front()
})
```

---

## 5. 40+ Native macOS Cocoa Controls Catalog

Every control in SimpleGUI wraps a native Cocoa `NSView` subclass.

### Standard Cocoa Controls

| Control Name | Creation Method | Underlying Cocoa Class | Description |
| :--- | :--- | :--- | :--- |
| **Button** | `win.add_button(id, text, onClick)` | `NSButton` | Standard push button supporting primary, secondary, and destructive variants. |
| **TextInput** | `win.add_text_input(id, placeholder)` | `NSTextField` | Single-line editable text field with placeholder. |
| **PasswordInput** | `win.add_password_input(id)` | `NSSecureTextField` | Masked password entry field. |
| **TextArea** | `win.add_text_area(id, rows)` | `NSTextView` / `NSScrollView` | Multi-line scrollable text editor. |
| **Checkbox** | `win.add_checkbox(id, label, checked)`| `NSButton` (Switch style) | Boolean state toggle checkbox. |
| **RadioGroup** | `win.add_radio_group(id, options)` | `NSMatrix` / `NSButton` | Mutually exclusive radio buttons. |
| **Dropdown** | `win.add_dropdown(id, options)` | `NSPopUpButton` | Dropdown selection menu. |
| **Slider** | `win.add_slider(id, min, max, val)` | `NSSlider` | Continuous numeric range track. |
| **ProgressBar** | `win.add_progress_bar(id, percentage)`| `NSProgressIndicator` | Deterministic progress indicator bar. |
| **Spinner** | `win.add_spinner(id)` | `NSProgressIndicator` | Indeterminate circular spinning wheel. |

### Rich macOS Widgets (TokenField, ComboBox, Ratings)

| Control Name | Creation Method | Description & Capabilities |
| :--- | :--- | :--- |
| **ComboBox** | `win.add_combo_box(id, options)` | Editable text field paired with an auto-completing suggestion popup. |
| **TokenField** | `win.add_token_field(id)` | Native `NSTokenField` tag editor with chip pills, deletion, and parsing. |
| **Rating / Stars** | `win.add_rating(id, max_stars)` | Interactive 5-star scoring indicator (`NSLevelIndicator`). |
| **BreadcrumbBar** | `win.add_breadcrumbs(id, items)` | Hierarchical navigation path bar with clickable segments. |
| **ShortcutRecorder**| `win.add_shortcut_recorder(id)` | Captures keyboard shortcut sequences (e.g. `Cmd + Shift + F`). |
| **ColorWell** | `win.add_color_well(id, hex_color)` | Native macOS color picker palette button. |
| **DatePicker** | `win.add_date_picker(id)` | Native Apple `NSDatePicker` calendar selector. |
| **FileDropZone** | `win.add_file_drop_zone(id, onDrop)` | Drag-and-drop target accepting files dropped from Finder. |

### Dashboard & Data Controls (Charts, Grids, TreeViews)

| Control Name | Creation Method | Description & Capabilities |
| :--- | :--- | :--- |
| **EditableDataGrid**| `win.add_editable_grid(id, cols)` | Spreadsheet-style editable table with sorting and cell navigation. |
| **TreeView** | `win.add_tree_view(id, nodes)` | Collapsible hierarchical tree view for file trees and ASTs. |
| **CodeEditor** | `win.add_code_editor(id, lang)` | Monospaced code view with line gutter and syntax coloring. |
| **LineChart** | `win.add_line_chart(id, data)` | Vector line graph for metrics, memory, and telemetry. |
| **GaugeMeter** | `win.add_gauge(id, value, min, max)`| Circular dial gauge for CPU load or disk fullness. |
| **PropertyGrid** | `win.add_property_grid(id, props)` | Delphi-style two-column key-value inspector grid. |
| **Timeline** | `win.add_timeline(id, events)` | Chronological audit log feed with timestamp pins. |

---

## 6. Advanced Layout Engine: Grids, Flexbox & Cards

SimpleGUI includes a modern layout container engine, freeing you from hardcoding `(x, y)` pixel coordinates.

### CSS Multi-Column Grid Containers

The Grid container automatically divides space into equal columns:

```v
// Begin a 3-column responsive grid
win.begin_grid(3)

win.add_text_input('txt_first_name', 'First Name...')
win.add_text_input('txt_middle_name', 'Middle Name...')
win.add_text_input('txt_last_name', 'Last Name...')

win.add_text_input('txt_email', 'Email Address...')
win.add_text_input('txt_phone', 'Phone Number...')
win.add_dropdown('drp_country', ['United States', 'Canada', 'United Kingdom', 'Germany'])

win.end_grid()
```

### Flexbox Containers & Alignment Modifiers

Flexbox containers support row or column flow with alignment and spacing:

```v
// Horizontal row flex container centered on cross-axis
win.begin_flex_box(
	direction: .row
	justify: .space_between
	align: .center
	spacing: 16
)

win.add_label('lbl_status', 'System Status: Active')
win.add_button('btn_pause', 'Pause', on_pause_click)
win.add_button('btn_stop', 'Stop', on_stop_click).variant(.danger)

win.end_flex_box()
```

Control alignment modifiers:
- `.align_left()` / `.align_center()` / `.align_right()`
- `.expand_fill()`: Expands control to take up all remaining container width.

### Card Containers & Nested Hierarchies

Cards create visually distinct, elevated panels with headers:

```v
win.begin_card('Database Connection Settings')

win.begin_grid(2)
win.add_text_input('txt_db_host', '127.0.0.1')
win.add_text_input('txt_db_port', '5432')
win.end_grid()

win.add_checkbox('chk_ssl', 'Enforce SSL / TLS Encryption', true)

win.end_card()
```

---

## 7. Curated Themes & State Persistence (18 Themes)

### Built-in Theme Catalog

SimpleGUI provides **18 pixel-perfect themes** that style both the window background and all Cocoa widgets:

1. `github_dark` (Default): GitHub modern dark canvas and subtle slate borders.
2. `apple_dark`: Native macOS Sonoma dark mode appearance.
3. `apple_light`: Clean Apple silver and crisp white canvas.
4. `deep_space_oled`: Pure `#000000` pitch-black for high contrast and OLED screens.
5. `tokyo_night`: Japanese-inspired dark indigo and violet night tones.
6. `nord_arctic`: Cool arctic blues, slate, and frosty cyans.
7. `dracula_vampire`: Iconic dark violet slate with vivid pink and green accents.
8. `cyberpunk_neon`: High-voltage magenta, cyan, and deep purple contrast.
9. `catppuccin_mocha`: Soothing pastel warm tones on velvety slate.
10. `monokai_pro`: Warm amber, magenta, and cyan on charcoal.
11. `gruvbox_dark`: Retro warm amber, orange, and olive green.
12. `cobalt_blue`: Deep ocean blue with bright sky-blue accents.
13. `emerald_forest`: Deep evergreen canvas with bright emerald highlights.
14. `sunset_dusk`: Warm twilight purple, coral, and gold.
15. `github_light`: Crisp white canvas with high-contrast text.
16. `solarized_dark`: Low-contrast dark teal and sage green.
17. `solarized_light`: Warm cream and paper-inspired low-contrast palette.
18. `warm_paper`: Sepia and warm cream reading palette.

### Cross-App Theme Synchronization

When a user switches themes in any SimpleGUI app, their choice is saved to:

```
~/.config/simplegui/theme.txt
```

All other SimpleGUI applications synchronize with this preference on launch, ensuring a unified desktop aesthetic.

```v
// Change theme dynamically at runtime
win.set_theme('tokyo_night')
```

---

## 8. RAD Visual UI Designer & Code Studio

SimpleGUI includes a dedicated, Delphi/VB-style **Visual Form Designer** (`tools/ui_designer.v`) and **RAD Code Explorer & Live Previewer** (`tools/vlang_simple_gui_previewer.v`).

### Launching the Visual Designer Studio

```bash
# Launch the Visual RAD Designer
v run tools/ui_designer.v

# Launch the Code Explorer & Live Previewer Studio
v run tools/vlang_simple_gui_previewer.v
```

### WYSIWYG Canvas & Component Inspector

The visual designer provides:
- **Component Palette**: Drag-and-drop 25+ native macOS controls directly onto the design canvas.
- **Visual Rubberband Selection**: Box-select multiple controls to move, align, or distribute them in bulk.
- **Snap Guidelines**: Visual alignment lines that snap controls to adjacent sibling edges and centers.
- **Object Inspector**: Edit control IDs, titles, placeholders, dimensions, colors, and event callbacks.
- **Pre-Loaded Layout Presets**: Instant starter templates for Customer Registration, Auth Login, KPI Dashboard, Settings Studio, and Data Grid CRUD.

### 1-Click Code Generation & Live Test Run

- **V Code Generator**: Generates clean, idiomatic SimpleGUI V source code with generated callback stubs.
- **Test Run Form**: Click **"Test Run Form"** (`tb_run`) to spin up an interactive native Cocoa preview window executing the form live.
- **Undo / Redo**: Full `Cmd+Z` and `Cmd+Shift+Z` history stack.

### RAD Code Explorer & Live Previewer

The companion tool `tools/vlang_simple_gui_previewer.v` provides a standalone IDE experience:
- Workspace folder explorer with drag-and-drop folder opening.
- Line numbers gutter (`001 |`, `002 |`) that auto-strip when compiling or copying.
- "Jump to Line" (`Cmd+G`) and "Find in Code" search highlighting.
- 1-click `v fmt -w` auto-formatting.
- 1-click compilation and execution in a non-blocking background thread.

---

## 9. Form Automation & Validation Engine

### Auto-Generating Forms from Structs

Rather than manually adding 10 text inputs for a database model, SimpleGUI can automatically generate an entire form from a V struct using compile-time reflection:

```v
struct UserProfile {
pub mut:
	first_name string @[required; min_len: 2]
	last_name  string @[required; min_len: 2]
	email      string @[required; email]
	age        int    @[min: 18; max: 120]
	is_admin   bool
}

fn main() {
	mut win := simplegui.new_window(title: 'User Profile Setup', width: 480, height: 420)

	// Automatically creates labels and appropriate inputs for all struct fields
	win.add_form_from_struct[UserProfile]()

	win.add_button('btn_submit', 'Save Profile', fn (mut w simplegui.Window) {
		// Validates struct constraints
		errors := w.validate_struct[UserProfile]()
		if errors.len > 0 {
			w.alert('Validation failed:\n' + errors.join('\n'))
			return
		}
		w.alert('Profile successfully validated and saved!')
	})

	win.run()
}
```

### Compile-Time Struct Tag Validation

Supported validation attributes:
- `@[required]`: Field cannot be blank.
- `@[email]`: Validates standard email address regex.
- `@[url]`: Validates HTTP/HTTPS URL format.
- `@[min_len: N]` / `@[max_len: N]`: String character length bounds.
- `@[min: N]` / `@[max: N]`: Numeric integer/float limits.
- `@[alphanumeric]`: Restricts characters to letters and numbers.

---

## 10. Centralized Security & Command Injection Prevention

### The Shell Injection Threat

Desktop utility apps frequently execute command-line tools (e.g. `ffmpeg`, `nmap`, `git`, `fd`). Standard naive string concatenation:

```v
// ⚠️ DANGEROUS VULNERABILITY: Shell command injection!
os.execute('git clone ' + user_input)
```

If `user_input` is `https://github.com/repo.git; rm -rf ~`, the shell executes both commands, resulting in catastrophic data loss.

### Safe Command Execution: exec_safe

SimpleGUI enforces secure-by-default process execution:

```v
// SECURE: Executes the process directly with isolated argument vectors
result := simplegui.exec_safe('git', ['clone', repo_url, target_dir])

if result.exit_code != 0 {
	win.alert('Git clone failed: ${result.stderr}')
} else {
	win.notify('Repository cloned successfully!')
}
```

Features of `exec_safe`:
- Bypasses the shell interpreter entirely (`/bin/sh`), preventing subshell breakouts (`;`, `&&`, `|`, `` ` ``, `$()`, `>`, `<`).
- Supports timeouts (`exec_timeout`) and background execution (`exec_bg`).
- Provides structured exit code, stdout, and stderr metadata.

### POSIX Argument & Path Escaping

When shell invocation is strictly necessary:

```v
safe_arg  := simplegui.quote_arg(user_filename)
safe_path := simplegui.quote_path(directory_path)
clean_name := simplegui.sanitize_filename(uploaded_name)
```

---

## 11. Neutralino-Inspired OS & System APIs

SimpleGUI includes a rich suite of built-in OS and hardware query APIs inspired by the Neutralinojs desktop framework.

### Hardware Telemetry & Resource Probing

Query hardware metrics with zero external C libraries:

```v
// CPU telemetry
cpu_model := simplegui.cpu_model()          // e.g. "Apple M3 Pro"
cores     := simplegui.cpu_physical_cores()  // e.g. 12
freq_ghz  := simplegui.cpu_frequency_ghz()   // e.g. 4.05

// Memory metrics
total_ram_mb := simplegui.total_memory_mb() // e.g. 36864
free_ram_mb  := simplegui.free_memory_mb()
usage_pct    := simplegui.memory_usage_pct()

// Disk storage stats
disk := simplegui.disk_stats('/')
println('Disk: ${disk.free_gb:.1f} GB free of ${disk.total_gb:.1f} GB')
```

### Network Interfaces, Wi-Fi & Port Scanning

```v
local_ip   := simplegui.local_ip()
public_ip  := simplegui.public_ip()
wifi_ssid  := simplegui.wifi_ssid()
dns_hosts  := simplegui.dns_servers()

// Check if a remote service or port is accessible
is_online  := simplegui.tcp_ping('8.8.8.8', 53, 1000)
```

### macOS Dock Badging, Bouncing & AppleScript Notifications

Interact natively with the macOS desktop environment:

```v
// Bounce the macOS Dock icon to grab attention
simplegui.dock_bounce(.informational) // Single bounce
simplegui.dock_bounce(.critical)      // Continuous bounce until focused

// Set a numeric or text badge on the Dock icon
simplegui.dock_set_badge('3')
simplegui.dock_clear_badge()

// Post a native macOS User Notification banner via AppleScript
simplegui.post_system_notification('Backup Completed', 'All 42 files synced successfully.')
```

---

## 12. V Standard Library Integrations

SimpleGUI exposes convenient wrappers for the V standard library.

### HTTP & WebSocket Client Threads

```v
// Synchronous HTTP GET
resp := simplegui.http_get('https://api.github.com/zen')
println('Zen: ${resp.body}')

// Async WebSocket Client connection
ws := simplegui.connect_websocket('wss://echo.websocket.events', fn (msg string) {
	println('Received WS packet: ${msg}')
})
```

### Cryptography, Hashing & Passwords

```v
hash_sha256 := simplegui.sha256_string('secret_token')
hash_md5    := simplegui.md5_string('file_contents')

// Secure password hashing and verification using bcrypt
hashed_pw   := simplegui.hash_password('MyP@ssw0rd!')
is_valid    := simplegui.verify_password('MyP@ssw0rd!', hashed_pw)

// AES-128 CBC Encryption
ciphertext  := simplegui.aes_encrypt('Sensitive Payload', '16ByteSecretKey!')
plaintext   := simplegui.aes_decrypt(ciphertext, '16ByteSecretKey!')
```

### High-Performance Compression (Gzip, Zstd)

```v
// Compress and decompress byte arrays
compressed   := simplegui.gzip_compress(raw_bytes)
decompressed := simplegui.gzip_decompress(compressed)
```

### Parsers & Collections (TOML, JSON, Stacks, Queues)

```v
// Generic Stack & Queue data structures
mut stack := simplegui.new_stack[string]()
stack.push('alpha')
stack.push('beta')
item := stack.pop() // "beta"

mut queue := simplegui.new_queue[int]()
queue.enqueue(10)
queue.enqueue(20)
val := queue.dequeue() // 10
```

---

## 13. SimpleCLI: Headless Console & RAD Toolkit

`simplecli` is the zero-window headless console companion module. It provides all of SimpleGUI's OS, hardware, crypto, validation, and logging features for non-GUI command-line tools.

### CLI Flag & Argument Parsing

```v
module main

import simplecli

fn main() {
	mut app := simplecli.new_cli_app(
		name: 'sysmon'
		description: 'System Telemetry Monitor'
		version: '1.0.0'
	)

	app.add_flag(name: 'interval', short: 'i', description: 'Refresh delay in seconds', default_val: '5')
	app.add_flag(name: 'json', short: 'j', description: 'Output in JSON format', is_bool: true)

	app.parse_args()

	interval := app.get_int('interval')
	is_json  := app.get_bool('json')
}
```

### Interactive Prompts & Multi-Step Pipelines

```v
// Interactive terminal prompt
user_name := simplecli.prompt('Enter admin username:')
password  := simplecli.password_prompt('Enter secret token:')
confirmed := simplecli.confirm('Do you wish to continue?')

// Multi-step task pipeline with spinner
mut runner := simplecli.new_pipeline_runner()
runner.add_step('Validating credentials...', fn () bool { return true })
runner.add_step('Connecting to cluster...', fn () bool { return true })
runner.add_step('Syncing database...', fn () bool { return true })
runner.run()
```

### Multi-Level Structured Logging

```v
mut log := simplecli.new_logger(level: .info, log_file: 'app.log')
log.info('System daemon started on port 8080')
log.warn('Disk storage above 85% threshold')
log.error('Failed to establish database socket')
```

---

## 14. Developer Utility Suite (30 Modules)

The repository includes **30 standalone utility packages** (`UTILS_API.md`):

### Core Utility Catalog

| Module | Purpose & Core Capabilities |
| :--- | :--- |
| `strutils` | Advanced string manipulation, casing, slugification, and Levenshtein distance. |
| `sqliteutils`| High-level SQLite wrapper with automated migrations and connection pooling. |
| `fileutils` | Atomic file writes, recursive directory search, and file checksums. |
| `httputils` | Fluent HTTP client with connection retry and bearer token injection. |
| `cacheutils`| High-speed in-memory LRU cache with TTL expiration. |
| `timeutils` | Humanized timestamps ("2 hours ago"), cron expressions, and parsing. |
| `cryptoutils`| HMAC, Wyhash, UUID v4 generation, and random tokens. |
| `validutils`| Email, IPv4/IPv6, URL, credit card, and phone regex validators. |
| `netutils` | Socket diagnostics, WHOIS lookup, and CIDR subnet calculations. |
| `colorutils`| Hex, RGB, HSL conversions, color lightening, darkening, and WCAG contrast ratio. |

### Practical Examples: strutils, sqliteutils, fileutils

```v
import strutils
import sqliteutils
import fileutils

// 1. String utilities
slug := strutils.slugify('Hello World! This is SimpleGUI.') // "hello-world-this-is-simplegui"
dist := strutils.levenshtein_distance('kitten', 'sitting')    // 3

// 2. High-level SQLite
mut db := sqliteutils.open('data.db') or { panic(err) }
db.execute('CREATE TABLE IF NOT EXISTS notes (id INTEGER PRIMARY KEY, text TEXT)')
db.insert('notes', {'text': 'Remember to test Cocoa bridge'})

// 3. File utilities
fileutils.write_file_atomic('config.json', '{"status": "ok"}') or { panic(err) }
files := fileutils.find_files_by_extension('/Users/codecaine/Projects', '.v')
```

---

## 15. Demos Suite & Production Workstations

### 70+ Interactive Demos Catalog

The `demos/` directory provides over 70 runnable demo applications:

| Demo Script | Key Feature Tested |
| :--- | :--- |
| `demos/01_window_basics.v` | Minimal window lifecycle, dimensions, and centering. |
| `demos/all_controls_demo.v` | Massive single form rendering every single `win.add_*` control. |
| `demos/developer_controls_demo.v` | Property grids, timeline feeds, tag clouds, avatar cards, and badges. |
| `demos/editable_grid_showcase_demo.v`| Spreadsheet-style data grid with cell editing, sorting, and row addition. |
| `demos/crud_table_demo.v` | Complete SQLite-backed CRUD table with add, edit, delete, and search filter. |
| `demos/animation_demo.v` | Smooth UI transitions and animated progress meters. |
| `demos/clipboard_demo.v` | Reading and writing text to the macOS system clipboard. |
| `demos/context_menus_demo.v` | Right-click Cocoa context popup menus. |

To run any demo:

```bash
v run demos/all_controls_demo.v
v run demos/developer_controls_demo.v
```

### 30+ Production Workstations (Fd, Rg, SQLite, Media Hub)

Located in `applications/`:
- `fd_studio.v`: High-speed visual file search frontend for `fd`.
- `rg_studio.v`: Visual regex code search workstation wrapping `ripgrep`.
- `sqlite_studio.v`: Full desktop SQLite browser and SQL query runner.
- `api_studio.v`: REST API client tester with JSON formatting and latency timer.
- `media_studio_hub.v`: Audio/video conversion and metadata inspector using `ffmpeg`.
- `brew_studio.v`: Homebrew package manager desktop GUI (installed packages, updates, search).
- `git_studio.v`: Visual Git commit log and branch manager.

Launch any workstation:

```bash
v run applications/fd_studio.v
v run applications/sqlite_studio.v
v run applications/brew_studio.v
```

---

## 16. Compiling & Packaging Standalone macOS Apps

### Compiling Production Binaries

Compile with optimizations enabled (`-prod`):

```bash
v -prod -skip-unused my_app.v
```

This compiles a single native Mach-O binary linked against Apple Cocoa.

### Bundling into macOS .app & Creating DMG Installers

To create a genuine macOS `.app` bundle ready to drag into `/Applications`:

```bash
# Run the automated app bundler script
v run compile_apps.vsh my_app.v
```

This performs the following steps:
1. Compiles the binary with `-prod`.
2. Creates the bundle directory tree:
   ```
   MyApp.app/
   └── Contents/
       ├── Info.plist
       ├── MacOS/
       │   └── MyApp
       └── Resources/
           └── AppIcon.icns
   ```
3. Generates high-resolution `.icns` application icons from PNG files.
4. Generates an Apple DMG installer using `create-dmg`:
   ```bash
   create-dmg \
     --volname "MyApp Installer" \
     --window-pos 200 120 \
     --window-size 600 400 \
     --icon-size 100 \
     --app-drop-link 420 200 \
     MyApp.dmg \
     MyApp.app
   ```

---

## 17. Tutorial: Building a Native macOS System Monitor App

Let's build a functional **Native macOS System Monitor** in under 60 lines of V.

### Step 1: Application Skeleton & Layout

Create `sys_monitor.v`:

```v
module main

import simplegui
import time

fn main() {
	mut win := simplegui.new_window(
		title: 'macOS Hardware Telemetry'
		width: 520
		height: 480
		theme: 'apple_dark'
	)

	win.add_label('lbl_header', '🖥️ System Performance Monitor')
		.font_size(18)
		.bold()

	// Hardware Info Card
	win.begin_card('Hardware Profile')
	win.add_label('lbl_cpu_model', 'Processor: ${simplegui.cpu_model()}')
	win.add_label('lbl_cores', 'Cores: ${simplegui.cpu_physical_cores()} Physical / ${simplegui.cpu_logical_cores()} Logical')
	win.add_label('lbl_total_ram', 'Installed Memory: ${simplegui.total_memory_mb() / 1024} GB')
	win.end_card()

	// Live Telemetry Card
	win.begin_card('Resource Utilization')
	win.add_label('lbl_cpu_pct', 'CPU Load: 0%')
	win.add_progress_bar('bar_cpu', 0)

	win.add_label('lbl_ram_pct', 'RAM Usage: 0%')
	win.add_progress_bar('bar_ram', 0)
	win.end_card()
```

### Step 2: Polling OS Telemetry with Interval Timers

Add a recurring background timer that polls system metrics every second:

```v
	// Refresh telemetry every 1000ms (1 second)
	win.add_interval_timer(1000, fn (mut w simplegui.Window) {
		mem_pct := simplegui.memory_usage_pct()
		disk := simplegui.disk_stats('/')

		// Update UI labels and progress meters
		w.set_text('lbl_ram_pct', 'RAM Usage: ${mem_pct:.1f}%')
		w.set_progress('bar_ram', int(mem_pct))

		// If memory is critically high, alert dock icon
		if mem_pct > 90.0 {
			simplegui.dock_bounce(.informational)
			simplegui.dock_set_badge('!')
		} else {
			simplegui.dock_clear_badge()
		}
	})
```

### Step 3: Hooking up Native Alerts & Dock Badges

```v
	win.begin_row()
	win.add_button('btn_refresh', 'Manual Refresh', fn (mut w simplegui.Window) {
		w.notify('Telemetry updated!')
	}).variant(.primary)

	win.add_button('btn_notify', 'Send Desktop Notification', fn (mut w simplegui.Window) {
		simplegui.post_system_notification('Telemetry Alert', 'All hardware systems are operational.')
	})
	win.end_row()

	win.run()
}
```

### Step 4: Compiling & Running the App

```bash
v run sys_monitor.v
```

A native Apple Cocoa window displays hardware telemetry, updating every second with live memory usage meters and macOS dock badging!

---

## 18. Quick Reference Cheat Sheet & FAQ

### Common CLI Commands

| Action | Terminal Command |
| :--- | :--- |
| **Run Any SimpleGUI App** | `v run my_app.v` |
| **Launch Visual Form Designer** | `v run tools/ui_designer.v` |
| **Launch Code Previewer Studio** | `v run tools/vlang_simple_gui_previewer.v` |
| **Run All Controls Demo** | `v run demos/all_controls_demo.v` |
| **Run Developer Controls Demo** | `v run demos/developer_controls_demo.v` |
| **Launch Fd Search Studio** | `v run applications/fd_studio.v` |
| **Launch Ripgrep Search Studio**| `v run applications/rg_studio.v` |
| **Launch SQLite Studio** | `v run applications/sqlite_studio.v` |
| **Run Full Test Suite** | `v test tests/` |
| **Compile Optimized Binary** | `v -prod -skip-unused my_app.v` |
| **Package as macOS `.app`** | `v run compile_apps.vsh my_app.v` |

### Troubleshooting & Gotchas

> [!NOTE]
> **Xcode Command Line Tools missing**: If the compiler warns about missing Cocoa headers or `clang`, reinstall the command-line tools:
> ```bash
> xcode-select --install
> ```

> [!TIP]
> **Command Injection Protection**: Never use `os.execute()` with untrusted user input. Always use `simplegui.exec_safe('tool_name', ['arg1', 'arg2'])` to bypass the shell interpreter securely.

> [!IMPORTANT]
> **Avoid Emojis in Native sokol/gg Text**: When running cross-platform canvas modes, stick to standard ASCII labels or brackets like `[+]`, `[-]`, `[Save]` to guarantee consistent font glyph rendering. On macOS Cocoa, all standard Unicode characters and SF Symbols are fully supported.

---

## Summary & Ecosystem Links

SimpleGUI brings the legendary Rapid Application Development (RAD) speed of Delphi and Visual Basic into the modern V programming language ecosystem, providing native macOS Cocoa performance with zero Objective-C boilerplate.

- **GitHub Repository**: [codecaine-zz/vlang_simplegui](https://github.com/codecaine-zz/vlang_simplegui)
- **Sister Project (TypeScript RAD IDE)**: [codecaine-zz/bun_rad_studio](https://github.com/codecaine-zz/bun_rad_studio)
- **V Programming Language**: [vlang.io](https://vlang.io)
