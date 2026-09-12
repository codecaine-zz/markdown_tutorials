# ⚡ Bun RAD Studio (Delphi / Visual Basic Style IDE) — Complete Guide

A comprehensive, production-grade guide to **Bun RAD Studio** (`codecaine-zz/bun_rad_studio`), a high-performance Rapid Application Development (RAD) Visual IDE for **Bun** and **Webview-Bun**, inspired by classic Borland Delphi and Visual Basic 6, built with modern web technologies and TypeScript.

---

## 📑 Table of Contents

- [1. Overview & Architectural Philosophy](#1-overview-architectural-philosophy)
  - [The Delphi & Visual Basic 6 Legacy](#the-delphi-visual-basic-6-legacy)
  - [Modern Engine: Bun + Webview-Bun + TypeScript](#modern-engine-bun-webview-bun-typescript)
- [2. Prerequisites & Installation](#2-prerequisites-installation)
  - [System Requirements & Dependencies](#system-requirements-dependencies)
  - [Clone & Repository Setup](#clone-repository-setup)
  - [Homebrew & Native GTK/WebKit Tooling](#homebrew-native-gtkwebkit-tooling)
- [3. Launching Bun RAD Studio](#3-launching-bun-rad-studio)
  - [Starting the Visual Studio IDE](#starting-the-visual-studio-ide)
  - [IDE Layout: Palette, Canvas & Object Inspector](#ide-layout-palette-canvas-object-inspector)
- [4. Visual RAD Form Designer](#4-visual-rad-form-designer)
  - [FormSpec JSON Data Schema](#formspec-json-data-schema)
  - [Visual Canvas Drag-and-Drop & Grid Snapping](#visual-canvas-drag-and-drop-grid-snapping)
  - [Object Inspector: Properties & Event Wireup](#object-inspector-properties-event-wireup)
  - [Multi-Target Code Generation](#multi-target-code-generation)
- [5. Native Window Management & Placement API](#5-native-window-management-placement-api)
  - [Position Presets & Multi-Monitor Pinning](#position-presets-multi-monitor-pinning)
  - [Frameless Windows & Draggable Titlebars](#frameless-windows-draggable-titlebars)
  - [Window Modality & Dynamic Resizing](#window-modality-dynamic-resizing)
- [6. MS Access & Delphi Data-Aware Controls](#6-ms-access-delphi-data-aware-controls)
  - [DataStore Schema & In-Memory Records](#datastore-schema-in-memory-records)
  - [Data-Aware Controls: DataGrid, DBNavigator & Field Binding](#data-aware-controls-datagrid-dbnavigator-field-binding)
  - [Automated CRUD Pipelines with Zero SQL](#automated-crud-pipelines-with-zero-sql)
- [7. Complete 70+ UI & RAD Controls Catalog](#7-complete-70-ui-rad-controls-catalog)
  - [Standard Cocoa & Windows Controls](#standard-cocoa-windows-controls)
  - [Rich macOS & Modern Desktop Widgets](#rich-macos-modern-desktop-widgets)
  - [Developer, Charting & System Monitoring Controls](#developer-charting-system-monitoring-controls)
- [8. Desktop Form Themes & Visual Gallery (63+ Themes)](#8-desktop-form-themes-visual-gallery-63-themes)
  - [Theme Categories & Visual Palettes](#theme-categories-visual-palettes)
  - [Live Theme Preview Switcher](#live-theme-preview-switcher)
  - [Applying Themes in SimpleGUI Code](#applying-themes-in-simplegui-code)
- [9. Declarative SimpleGUI Module in TypeScript](#9-declarative-simplegui-module-in-typescript)
  - [Zero-Boilerplate Window Initialization](#zero-boilerplate-window-initialization)
  - [Layout Containers: Stack, Row, Grid & Card](#layout-containers-stack-row-grid-card)
  - [Fluent Method Chaining & Property Modifiers](#fluent-method-chaining-property-modifiers)
  - [Reactive State Store & Universal JSON Persistence](#reactive-state-store-universal-json-persistence)
- [10. Interactive Demos Suite (25 Demos)](#10-interactive-demos-suite-25-demos)
  - [Demos Catalog & CLI Commands](#demos-catalog-cli-commands)
  - [Running the Modern Productivity Controls Studio](#running-the-modern-productivity-controls-studio)
  - [Running the Theme & Controls Showcase](#running-the-theme-controls-showcase)
- [11. Enterprise Production Workstations & Utilities](#11-enterprise-production-workstations-utilities)
  - [Fd Studio & Rip Studio Pro Workstations](#fd-studio-rip-studio-pro-workstations)
  - [SQLite Studio Pro & Redis Studio Workstations](#sqlite-studio-pro-redis-studio-workstations)
  - [System Studio, Network Studio & Git Studio](#system-studio-network-studio-git-studio)
- [12. Compiling Standalone macOS Binaries & .app Bundles](#12-compiling-standalone-macos-binaries-app-bundles)
  - [Native Single-File Executable (`build:binary`)](#native-single-file-executable-buildbinary)
  - [Full macOS `.app` Application Bundle with Custom ICNS](#full-macos-app-application-bundle-with-custom-icns)
- [13. Tutorial: Building a Full Database App from Scratch](#13-tutorial-building-a-full-database-app-from-scratch)
  - [Step 1: Project Setup & Window Skeleton](#step-1-project-setup-window-skeleton)
  - [Step 2: Designing the Form Controls](#step-2-designing-the-form-controls)
  - [Step 3: Hooking up SQLite Storage & DB Navigator](#step-3-hooking-up-sqlite-storage-db-navigator)
  - [Step 4: Running & Testing the Application](#step-4-running-testing-the-application)
- [14. Quick Reference Cheat Sheet & FAQ](#14-quick-reference-cheat-sheet-faq)
  - [Common CLI Scripts](#common-cli-scripts)
  - [Keyboard Shortcuts & Power Actions](#keyboard-shortcuts-power-actions)
  - [Troubleshooting & Gotchas](#troubleshooting-gotchas)

---

## 1. Overview & Architectural Philosophy

### The Delphi & Visual Basic 6 Legacy

In the 1990s and early 2000s, tools like **Borland Delphi** (Object Pascal) and **Microsoft Visual Basic 6** defined peak developer ergonomics:
1. **Instant Visual Feedback**: Drag a button or data grid onto a form, double-click to write an event handler, and run immediately.
2. **Data-Aware Components**: UI controls connected directly to datasets, queries, and tables without boilerplate synchronization code.
3. **Single Fast Executables**: Compilation completed in seconds, producing self-contained native desktop applications.

Over two decades, web technologies took over UI design with CSS and flexbox, while desktop development became bloated with multi-gigabyte Electron binaries consuming hundreds of megabytes of RAM.

**Bun RAD Studio** bridges this divide. It resurrects the beloved, high-velocity Delphi and VB6 development workflow while running on modern TypeScript and **Bun**, utilizing lightweight native OS webviews (`webview-bun`) instead of heavyweight Chromium bundles.

```
┌──────────────────────────────────────────────────────────────┐
│                      Bun RAD Studio                          │
│                                                              │
│  ┌────────────────────┐ ┌──────────────────────────────────┐ │
│  │ Component Palette  │ │       Visual Form Canvas         │ │
│  │ (70+ RAD Controls) │ │  (Drag, Drop, Resize, Snap Grid) │ │
│  └─────────┬──────────┘ └─────────────────┬────────────────┘ │
│            │                              │                  │
│            ▼                              ▼                  │
│  ┌─────────────────────────────────────────────────────────┐ │
│  │           FormSpec JSON Synchronizer (Two-Way)          │ │
│  └────────────────────────┬────────────────────────────────┘ │
│                           │                                  │
│       ┌───────────────────┼───────────────────┐              │
│       ▼                   ▼                   ▼              │
│  ┌──────────┐     ┌───────────────┐     ┌────────────┐       │
│  │ Object   │     │ Multi-Target  │     │ Webview    │       │
│  │Inspector │     │Code Generator │     │ IPC Bridge │       │
│  └──────────┘     └───────────────┘     └────────────┘       │
│                           │                   │              │
│                           ▼                   ▼              │
│             ┌───────────────────────────┐                    │
│             │ Bun Native Runtime Engine │                    │
│             │   (webview-bun / Cocoa)   │                    │
│             └───────────────────────────┘                    │
└──────────────────────────────────────────────────────────────┘
```

### Modern Engine: Bun + Webview-Bun + TypeScript

- **Lightning-Fast Startup**: Bun boots in under 10 milliseconds, launching windows with zero warm-up lag.
- **Microscopic Footprint**: Leverages the system's pre-installed native browser engine (WebKit on macOS via Cocoa, WebKitGTK on Linux, and WebView2 on Windows). A typical Bun RAD Studio application consumes **20 MB to 40 MB** of RAM, versus 250 MB to 600 MB for equivalent Electron apps.
- **Zero-Build TypeScript**: Bun executes `.ts` and `.tsx` directly without requiring `tsc`, Babel, or Webpack compilation steps.
- **Bi-Directional IPC**: Instant synchronous and asynchronous communication between the UI canvas and Bun backend via `window.ipc.postMessage` and bound RPC handlers.

---

## 2. Prerequisites & Installation

### System Requirements & Dependencies

| Operating System | Webview Engine | Minimum OS Version | Extra Packages |
| :--- | :--- | :--- | :--- |
| **macOS** | WebKit (Cocoa) | macOS 11 Big Sur or newer | Command Line Tools (`xcode-select --install`) |
| **Linux** | WebKitGTK 6.0 / 4.1 | Ubuntu 22.04, Debian 12, Fedora 38 | `libwebkitgtk-6.0-dev` or `libwebkitgtk-4.1-dev` |
| **Windows** | Microsoft Edge WebView2 | Windows 10 / 11 | Evergreen WebView2 Runtime (pre-installed) |

### Clone & Repository Setup

Make sure you have [Bun](https://bun.sh) installed (v1.1+ or v1.3+ recommended):

```bash
# Verify Bun installation
bun --version

# Clone the official repository
git clone https://github.com/codecaine-zz/bun_rad_studio.git
cd bun_rad_studio

# Install package dependencies
bun install
```

### Homebrew & Native GTK/WebKit Tooling

On macOS, optional system CLI tools utilized by the enterprise workstations (like `fd`, `ripgrep`, `jq`, `sqlite3`, `redis`, `imagemagick`) can be installed using the repository's automated installer script:

```bash
# Run automated Homebrew dependencies installer
./install_homebrew_dependencies.sh

# Or verify Homebrew dependencies
bun run brew:check
```

On Linux systems (e.g. Ubuntu or Debian), install the native WebKitGTK and build tools:

```bash
sudo apt-get update
sudo apt-get install -y \
  build-essential \
  libwebkitgtk-6.0-dev \
  libgtk-3-dev \
  curl \
  unzip
```

---

## 3. Launching Bun RAD Studio

### Starting the Visual Studio IDE

Launch the main visual environment with a single Bun command:

```bash
bun start
# Or
bun run index.ts
```

This initializes the Webview-Bun native window, spawns the visual designer canvas, and hooks up the local IPC backend.

### IDE Layout: Palette, Canvas & Object Inspector

The Bun RAD Studio user interface mirrors the three-pane ergonomics of classic Borland Delphi:

1. **Left Pane — Component Palette**:
   - Organized into categorized drawers: **Standard Controls**, **Form Inputs**, **Containers & Layouts**, **Data-Aware Controls**, **Charts & Gauges**, and **System & Diagnostics**.
   - Hover over any widget to view its property schema and default dimensions.
   - Click to add to the canvas or drag directly to a target coordinate.

2. **Center Pane — Visual Form Canvas**:
   - Represents the physical desktop window.
   - Renders with an adjustable pixel grid (8px, 16px, or freeform).
   - Features multi-control marquee selection, rubber-band dragging, edge and corner resize handles, and alignment guidelines (snap to sibling edges and centers).

3. **Right Pane — Object Inspector & Code Generator**:
   - **Properties Tab**: Edit control title, font, size, foreground/background color, enabled/read-only flags, and layout anchors.
   - **Events Tab**: Attach click, change, blur, submit, or timer events to TypeScript callbacks.
   - **Code Preview Tab**: Live, syntax-highlighted code output updated in real-time as you drag and configure controls.

---

## 4. Visual RAD Form Designer

### FormSpec JSON Data Schema

The core design state is synchronized as a single declarative JSON structure:

```json
{
  "title": "Customer Registration Form",
  "width": 780,
  "height": 520,
  "theme": "codefreelance",
  "background_color": "#050505",
  "font_color": "#ffffff",
  "padding": 20,
  "spacing": 12,
  "controls": [
    {
      "id": "lbl_customer_name",
      "control_type": "label",
      "x": 24,
      "y": 30,
      "width": 120,
      "height": 24,
      "text": "Full Name:"
    },
    {
      "id": "txt_customer_name",
      "control_type": "text_input",
      "x": 150,
      "y": 28,
      "width": 260,
      "height": 30,
      "placeholder": "Enter customer full name..."
    },
    {
      "id": "btn_save",
      "control_type": "button",
      "x": 150,
      "y": 80,
      "width": 140,
      "height": 34,
      "text": "Save Customer",
      "variant": "primary"
    }
  ]
}
```

### Visual Canvas Drag-and-Drop & Grid Snapping

- **Moving Controls**: Click and drag any control or group of selected controls. Coordinates update live in the Object Inspector.
- **Resizing**: Drag the square handles located at the corners and edges of the active selection border.
- **Snapping**: Controls snap automatically to adjacent sibling elements, maintaining uniform margins (8px or 12px) without manual pixel calculation.
- **Grouping**: Box-select multiple controls to move, align, or distribute them horizontally or vertically in bulk.

### Object Inspector: Properties & Event Wireup

Every control exposes a structured set of configurable parameters:
- `id`: Unique programmatic identifier used to access control values (e.g. `win.getText("txt_customer_name")`).
- `x`, `y`, `width`, `height`: Absolute geometric dimensions on the form canvas.
- `text` / `value` / `placeholder`: Content displayed inside the control.
- `enabled`, `visible`, `read_only`: UI state flags.
- `onChange` / `onClick`: Script names or IPC function bindings triggered on user action.

### Multi-Target Code Generation

Bun RAD Studio can export your visual form directly into production code across multiple language backends:
1. **TypeScript / Bun (SimpleGUI)**: Generates high-level fluent TypeScript code using `SimpleGUI.createWindow(...)`.
2. **TypeScript / Webview-Bun**: Produces native Webview IPC bindings and HTML/CSS template strings.
3. **V Language (`vlang_simplegui`)**: Generates native V code compatible with macOS Cocoa `window.v` and `controls.v`.
4. **HTML5 / CSS3 / Vanilla JS**: Standalone web form ready for embedding or browser preview.

---

## 5. Native Window Management & Placement API

Desktop utilities frequently need specific screen positioning (such as a floating toolbar pinned to the top right or an inspector pinned to the bottom dock). Bun RAD Studio includes a comprehensive Window Placement API.

### Position Presets & Multi-Monitor Pinning

```typescript
import { SimpleGUI } from "./src/simplegui";

const win = SimpleGUI.createWindow({
  title: "Diagnostics Toolbar",
  width: 320,
  height: 480,
  // Position presets: "center", "top_left", "top_right", "bottom_left", "bottom_right"
  position: "top_right",
  always_on_top: true,
  resizable: false
});

// Dynamic repositioning via API
win.setPosition(100, 100);
win.center();
win.setAlwaysOnTop(true);
```

### Frameless Windows & Draggable Titlebars

For customized, sleek HUDs or floating widgets:

```typescript
const hud = SimpleGUI.createWindow({
  title: "HUD Monitor",
  width: 400,
  height: 200,
  frameless: true,
  transparent: true,
  theme: "deep_space_oled"
});

// Create a custom draggable header bar
hud.addCustomTitleBar({
  title: "System Telemetry",
  showClose: true,
  showMinimize: true
});
```

### Window Modality & Dynamic Resizing

```typescript
// Open a modal dialog blocking user interaction with the parent form
const result = await win.showModalDialog({
  title: "Confirm Deletion",
  message: "Are you sure you want to drop this table? This operation cannot be undone.",
  type: "warning",
  buttons: ["Cancel", "Delete Record"]
});

if (result === "Delete Record") {
  win.notify("Record successfully removed", "success");
}
```

---

## 6. MS Access & Delphi Data-Aware Controls

A standout feature of Bun RAD Studio is its **Data-Aware Component Architecture**, recreating the ease of Borland Delphi's `TDataSource` and `TDBGrid`.

### DataStore Schema & In-Memory Records

```typescript
import { SimpleDataStore } from "./src/datastore";

// Define a reactive in-memory or SQLite-backed DataStore
const employees = new SimpleDataStore({
  name: "employees",
  primaryKey: "id",
  fields: [
    { name: "id", type: "integer", autoIncrement: true },
    { name: "name", type: "string", required: true },
    { name: "department", type: "string" },
    { name: "salary", type: "float" },
    { name: "active", type: "boolean", default: true }
  ]
});

// Seed initial records
employees.insert({ name: "Jane Doe", department: "Engineering", salary: 125000 });
employees.insert({ name: "John Smith", department: "Design", salary: 110000 });
```

### Data-Aware Controls: DataGrid, DBNavigator & Field Binding

```typescript
// Add a data-aware grid linked to the store
win.addDataGrid({
  id: "grid_employees",
  store: employees,
  columns: [
    { field: "id", label: "ID", width: 60 },
    { field: "name", label: "Full Name", width: 180, editable: true },
    { field: "department", label: "Dept", width: 140 },
    { field: "salary", label: "Salary ($)", width: 100, format: "currency" }
  ],
  onRowSelect: (record) => {
    // Automatically populate individual form fields when a row is clicked
    win.setValue("txt_emp_name", record.name);
    win.setValue("txt_emp_salary", record.salary);
  }
});

// Add a classic Delphi-style DB Navigator bar (First, Prior, Next, Last, New, Delete, Save)
win.addDBNavigator({
  id: "nav_employees",
  store: employees,
  buttons: ["first", "prior", "next", "last", "new", "delete", "post", "cancel"]
});
```

### Automated CRUD Pipelines with Zero SQL

Changes in the grid or through the navigator immediately emit transactional events (`beforePost`, `afterPost`, `onDelete`), allowing you to bind updates back to a SQLite database or REST backend with zero boilerplate:

```typescript
employees.on("afterPost", (record) => {
  console.log("Record saved:", record);
  win.notify(`Updated employee #${record.id}`, "info");
});
```

---

## 7. Complete 70+ UI & RAD Controls Catalog

Bun RAD Studio includes a vast palette of desktop controls.

### Standard Cocoa & Windows Controls

| Control | Constructor Method | Description & Capabilities |
| :--- | :--- | :--- |
| **Button** | `win.addButton(id, text, onClick)` | Push button with primary, secondary, danger, and ghost styles. |
| **TextInput** | `win.addTextInput(id, placeholder)` | Single-line editable text field with clear button and change events. |
| **PasswordInput** | `win.addPasswordInput(id)` | Masked input field with optional reveal-password toggle icon. |
| **TextArea** | `win.addTextArea(id, rows)` | Multi-line scrollable text field with monospace formatting option. |
| **Checkbox** | `win.addCheckbox(id, label, checked)` | Toggle checkbox supporting boolean reading and event callbacks. |
| **RadioGroup** | `win.addRadioGroup(id, options)` | Mutual-exclusion radio button list with instant selection binding. |
| **Dropdown** | `win.addDropdown(id, options)` | Dropdown select box with customizable items and default indexes. |
| **Slider** | `win.addSlider(id, min, max, val)` | Continuous numeric range track with live numeric value badge. |
| **ProgressBar** | `win.addProgressBar(id, percentage)` | Horizontal fill meter for progress tracking and task status. |
| **ActivitySpinner** | `win.addSpinner(id)` | Animated rotating indicator for asynchronous wait states. |

### Rich macOS & Modern Desktop Widgets

| Control | Constructor Method | Description & Capabilities |
| :--- | :--- | :--- |
| **ComboBox** | `win.addComboBox(id, options)` | Editable text field paired with a dropdown suggestions list. |
| **Rating / Stars** | `win.addRating(id, maxStars)` | Interactive 5-star rating control for scoring and reviews. |
| **TokenField** | `win.addTokenField(id)` | Bubble tag editor with chip removal, auto-completion, and delimiter parsing. |
| **BreadcrumbBar** | `win.addBreadcrumbs(id, items)` | Hierarchical navigation trail with clickable historical segments. |
| **ShortcutRecorder** | `win.addShortcutRecorder(id)` | Visual hotkey capturer (e.g. `Cmd + Shift + P`) for keybindings. |
| **ColorPicker** | `win.addColorPicker(id, defaultHex)`| Color swatch with RGB/Hex picker and live preview circle. |
| **DatePicker** | `win.addDatePicker(id)` | Calendar popup selector with formatted output. |
| **DropZone** | `win.addFileDropZone(id, onDrop)` | Native drag-and-drop file target accepting local file paths. |

### Developer, Charting & System Monitoring Controls

| Control | Constructor Method | Description & Capabilities |
| :--- | :--- | :--- |
| **CodeEditor** | `win.addCodeEditor(id, lang)` | Embedded code editor with syntax highlighting, line numbers, and indentation. |
| **LineChart** | `win.addLineChart(id, points)` | Vector canvas line chart with smooth curves and data tooltips. |
| **AreaChart** | `win.addAreaChart(id, series)` | Filled gradient area graph for time-series bandwidth or telemetry. |
| **GaugeMeter** | `win.addGauge(id, value, min, max)`| Circular dial gauge for CPU %, memory pressure, or disk speed. |
| **PropertyGrid** | `win.addPropertyGrid(id, schema)` | Delphi-style property inspector with categorized key-value editors. |
| **TreeView** | `win.addTreeView(id, nodes)` | Collapsible hierarchical file or entity tree with click handlers. |
| **Timeline** | `win.addTimeline(id, events)` | Vertical chronological event feed with icon markers and timestamps. |
| **SplitPane** | `win.addSplitPane(id, left, right)`| Resizable split container with movable divider handle. |

---

## 8. Desktop Form Themes & Visual Gallery (63+ Themes)

Bun RAD Studio includes **63+ carefully designed desktop themes** with zero external stylesheet dependencies. Every theme provides harmonious contrast, custom typography, and curated color palettes for all widgets.

### Theme Categories & Visual Palettes

1. **Brand & Signature**:
   - `codefreelance`: Obsidian black canvas (`#050505`), card surface (`#121212`), neon emerald accent (`#0fb36a`), and purple secondary (`#bd00ff`).
2. **Modern macOS & Apple**:
   - `apple_dark`: Native macOS Sonoma dark appearance.
   - `apple_light`: Clean, airy Apple silver and porcelain white.
   - `sonoma_emerald`: macOS dark mode with Sonoma forest-green accents.
   - `mac_os_aqua`: Classic Mac OS X brushed metallic aqua appearance.
3. **AAA Studio & Designer Palettes**:
   - `deep_space_oled`: Pure `#000000` pitch-black backgrounds for OLED displays.
   - `cyberpunk_neon`: High-voltage magenta, cyan, and deep purple contrast.
   - `catppuccin_mocha`: Pastel warm tones on smooth soothing dark slate.
   - `tokyo_night`: Clean Japanese-inspired dark indigo and violet tones.
4. **Developer & IDE Syntax**:
   - `monokai_pro`: Vibrant amber, cyan, and magenta on dark brown charcoal.
   - `dracula_vampire`: Iconic dark slate with purple, pink, and yellow accents.
   - `nord_arctic`: Cool arctic blues, frosty cyans, and snowy whites.
   - `gruvbox_dark`: Earthy retro retro-warm amber, orange, and olive green.
5. **Clean Light & High-Contrast**:
   - `github_light`: Crisp white canvas with slate borders and subtle shadows.
   - `warm_paper`: Eye-friendly sepia and warm cream reading palette.
   - `solarized_light`: Ethan Schoonover's precision-engineered low-contrast palette.

### Live Theme Preview Switcher

Test any theme live on your desktop:

```bash
bun run demo:themes <theme_name>

# Examples:
bun run demo:themes codefreelance
bun run demo:themes monokai_pro
bun run demo:themes tokyo_night
bun run demo:themes dracula_vampire
bun run demo:themes sonoma_emerald
```

### Applying Themes in SimpleGUI Code

Themes can be set globally on window initialization or swapped dynamically at runtime:

```typescript
const win = SimpleGUI.createWindow({
  title: "Tokyo Night Workspace",
  theme: "tokyo_night",
  width: 900,
  height: 600
});

// Dynamic theme switching at runtime
win.setTheme("monokai_pro");
```

---

## 9. Declarative SimpleGUI Module in TypeScript

`simplegui` is the companion fluent library included with Bun RAD Studio, allowing you to assemble full desktop applications without writing HTML, CSS, or JSON.

### Zero-Boilerplate Window Initialization

```typescript
import { SimpleGUI } from "./src/simplegui";

const win = SimpleGUI.createWindow({
  title: "Quick Utility",
  width: 480,
  height: 360,
  theme: "codefreelance"
});

win.addLabel("lbl_heading", "System Optimizer")
   .fontSize(18)
   .bold();

win.addButton("btn_clean", "Clean Temp Caches", async () => {
  win.setBusy(true, "Clearing temporary files...");
  await Bun.sleep(1500);
  win.setBusy(false);
  win.notify("Freed 2.4 GB of disk space!", "success");
});

win.show();
```

### Layout Containers: Stack, Row, Grid & Card

SimpleGUI organizes controls using structured containers:

```typescript
// 1. Group controls inside an elevated card container
win.beginCard("Network Settings");

// 2. Horizontal row container
win.beginRow();
win.addLabel("lbl_ip", "Server Host:");
win.addTextInput("txt_host", "127.0.0.1").width(180);
win.addTextInput("txt_port", "8080").width(80);
win.endRow();

// 3. Multi-column grid container (3 columns)
win.beginGrid(3);
win.addCheckbox("chk_ssl", "Enable TLS / SSL", true);
win.addCheckbox("chk_cors", "Allow CORS", true);
win.addCheckbox("chk_auth", "Require API Key", false);
win.endGrid();

win.endCard();
```

### Fluent Method Chaining & Property Modifiers

Every control constructor returns a `SimpleControlRef` supporting chainable modifiers:

```typescript
win.addTextInput("txt_search", "Search entries...")
   .width(320)
   .height(36)
   .tooltip("Press Enter to filter table results")
   .textColor("#38bdf8")
   .backgroundColor("#0f172a")
   .onChange((val) => {
     console.log("Search query updated:", val);
   });
```

### Reactive State Store & Universal JSON Persistence

Manage application state with automatic file persistence:

```typescript
// Define persistent reactive state
const store = win.createStateStore("app_preferences.json", {
  dark_mode: true,
  refresh_interval: 30,
  last_directory: "/Users/codecaine/Documents"
});

// Read state
const currentInterval = store.get("refresh_interval");

// Update state (automatically triggers UI listeners and writes to disk)
store.set("refresh_interval", 60);

// Two-way binding: automatically sync an input field with the store
win.bindControlToState("txt_interval", store, "refresh_interval");
```

---

## 10. Interactive Demos Suite (25 Demos)

Bun RAD Studio includes **25 dedicated runnable demos** testing every control, layout system, and integration feature.

### Demos Catalog & CLI Commands

| CLI Script | Demo File | Features Showcased |
| :--- | :--- | :--- |
| `bun run demo:standard` | `demos/01_standard_controls.ts` | All primary inputs, buttons, sliders, progress bars, and checkboxes. |
| `bun run demo:modern` | `demos/02_advanced_modern_controls.ts` | Token fields, shortcut recorders, rating stars, and combo boxes. |
| `bun run demo:data` | `demos/03_data_and_non_visual.ts` | In-memory DataStore, live data tables, and DB Navigator bindings. |
| `bun run demo:window` | `demos/04_window_placement_and_pin.ts` | Window placement presets, pinning, frameless modes, and multi-monitor layouts. |
| `bun run demo:crud` | `demos/05_crud_todo_table.ts` | Complete working CRUD table with add, edit, delete, and search filtering. |
| `bun run demo:timer` | `demos/06_timer_control_studio.ts` | Recurring interval timers, countdowns, and scheduled UI updates. |
| `bun run demo:desktop` | `demos/07_labeled_form_and_desktop_controls.ts` | Compound label-plus-input pairs and enterprise form building. |
| `bun run demo:dashboard` | `demos/08_analytics_dashboard_template.ts` | Full analytics dashboard with stat cards, line charts, and gauges. |
| `bun run demo:ide` | `demos/09_file_explorer_ide_template.ts` | Multi-pane IDE layout with file tree, code editor, and console. |
| `bun run demo:db` | `demos/10_db_studio_query_editor_template.ts`| Database query studio with SQL syntax editor and tabular results grid. |
| `bun run demo:productivity`| `demos/13_productivity_controls_studio.ts`| Comprehensive studio showcasing all 30+ productivity widgets. |
| `bun run demo:themes` | `demos/23_all_themes_all_controls_showcase.ts` | Full visual gallery rendering every control across all 63 themes. |

### Running the Modern Productivity Controls Studio

```bash
bun run demo:productivity
```

This launches a fully interactive single-window workstation rendering status indicators, metric meters, property inspectors, timeline feeds, tag clouds, avatar cards, and split buttons simultaneously.

### Running the Theme & Controls Showcase

```bash
bun run demo:themes
```

Renders a comprehensive showcase form and lets you cycle through all 63 color palettes in real-time with zero restart delay.

---

## 11. Enterprise Production Workstations & Utilities

Bun RAD Studio is not just a UI framework—it comes bundled with **16 full-featured desktop workstation applications** located in the `applications/` directory.

### Fd Studio & Rip Studio Pro Workstations

High-speed desktop front-ends for `fd` and `ripgrep`:

```bash
# Launch Fd Studio (Lightning-fast visual file & directory search)
bun run app:fd

# Launch Rip Studio Pro (High-speed regex code search with syntax preview)
bun run app:rip
```

- **Live Debounced Filtering**: Searches hundreds of thousands of files in milliseconds.
- **Regex Tester Mode**: Live syntax preview with line matches and context highlighting.
- **Direct File Handoff**: Open matching files directly in VS Code, Sublime Text, or the native Finder/Explorer.

### SQLite Studio Pro & Redis Studio Workstations

Desktop database management environments:

```bash
# Launch SQLite Studio Pro
bun run app:sqlite

# Launch Redis Studio
bun run app:database
```

- **Interactive Schema Browser**: Inspect tables, triggers, indexes, and column types.
- **Tabular Data Editing**: Inline cell editing, pagination, sorting, and CSV export.
- **Query Editor**: Multi-tab SQL query editor with execution timers and query plan visualization.

### System Studio, Network Studio & Git Studio

Deep OS diagnostics and operational tooling:

```bash
# Launch System Studio Pro (Hardware, CPU cores, RAM, Disk, Processes)
bun run app:system

# Launch Network Studio (Ports, Ping, Wi-Fi telemetry, DNS diagnostics)
bun run app:network

# Launch Git Studio (Branch visualizer, commit log, status review)
bun run app:git
```

---

## 12. Compiling Standalone macOS Binaries & .app Bundles

Bun RAD Studio includes native packaging scripts allowing you to distribute self-contained executables and macOS application bundles without requiring users to install Bun or Node.js.

### Native Single-File Executable (`build:binary`)

Bun can compile your application, its assets, and the runtime engine into a single executable binary:

```bash
# Compile standalone native binary
bun run build:binary
```

The resulting binary is output to `dist/bin/` and can be moved anywhere or run directly from the command line:

```bash
./dist/bin/bun_rad_studio
```

### Full macOS `.app` Application Bundle with Custom ICNS

To create a drag-and-drop macOS application suitable for `/Applications`:

```bash
# Compile and package complete macOS .app bundle
bun run scripts/build.ts app
```

The build script performs the following automated steps:
1. Compiles the native binary via `bun build --compile`.
2. Generates the standard macOS bundle hierarchy:
   ```
   BunRADStudio.app/
   └── Contents/
       ├── Info.plist
       ├── MacOS/
       │   └── BunRADStudio
       └── Resources/
           ├── AppIcon.icns
           └── assets/
   ```
3. Generates high-resolution multi-size `.icns` icons from PNG sources using macOS `iconutil`.
4. Writes the bundle identifier, version strings, and document types to `Info.plist`.
5. Signs the bundle for local gatekeeper verification.

---

## 13. Tutorial: Building a Full Database App from Scratch

Let's build a functional **Contact Directory Desktop App** from scratch using SimpleGUI in under 50 lines of TypeScript.

### Step 1: Project Setup & Window Skeleton

Create a file named `contacts_app.ts`:

```typescript
import { SimpleGUI } from "./src/simplegui";
import { Database } from "bun:sqlite";

// Initialize local SQLite database
const db = new Database("contacts.sqlite");
db.run(`
  CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT
  )
`);

// Create main desktop window
const win = SimpleGUI.createWindow({
  title: "Enterprise Contact Directory",
  width: 720,
  height: 540,
  theme: "sonoma_emerald"
});
```

### Step 2: Designing the Form Controls

```typescript
// Header Section
win.addLabel("lbl_title", "👥 Contact Directory")
   .fontSize(20)
   .bold();

// Add Input Card
win.beginCard("New Contact Entry");
win.beginGrid(3);

win.addTextInput("txt_name", "Full Name...");
win.addTextInput("txt_email", "Email Address...");
win.addDropdown("drp_role", ["Engineering", "Design", "Sales", "Support"]);

win.endGrid();

win.beginRow();
win.addButton("btn_add", "Save Contact", () => {
  const name = win.getText("txt_name").trim();
  const email = win.getText("txt_email").trim();
  const role = win.getText("drp_role");

  if (!name || !email) {
    win.notify("Please fill in both Name and Email", "error");
    return;
  }

  // Insert into SQLite
  db.run("INSERT INTO contacts (name, email, role) VALUES (?, ?, ?)", [name, email, role]);

  // Clear inputs and refresh table
  win.setValue("txt_name", "");
  win.setValue("txt_email", "");
  refreshTable();
  win.notify(`Added ${name} to directory`, "success");
}).variant("primary");

win.addButton("btn_clear", "Clear Inputs", () => {
  win.setValue("txt_name", "");
  win.setValue("txt_email", "");
}).variant("ghost");

win.endRow();
win.endCard();
```

### Step 3: Hooking up SQLite Storage & DB Navigator

```typescript
// Contacts Data Table
win.addLabel("lbl_list", "Existing Directory Records").fontSize(14).bold();

const table = win.addTable({
  id: "tbl_contacts",
  columns: [
    { field: "id", label: "ID", width: 50 },
    { field: "name", label: "Full Name", width: 220 },
    { field: "email", label: "Email Address", width: 220 },
    { field: "role", label: "Department", width: 140 }
  ]
});

function refreshTable() {
  const records = db.query("SELECT * FROM contacts ORDER BY id DESC").all();
  table.setData(records);
}

// Initial table load
refreshTable();
```

### Step 4: Running & Testing the Application

Execute your new desktop app directly with Bun:

```bash
bun run contacts_app.ts
```

In seconds, a native window appears with full keyboard navigation, dark theme styling, SQLite persistence, and instant visual updates!

---

## 14. Quick Reference Cheat Sheet & FAQ

### Common CLI Scripts

| Action | Command |
| :--- | :--- |
| **Launch RAD Designer** | `bun start` or `bun run index.ts` |
| **Run Unit & E2E Tests** | `bun test` |
| **Run Productivity Demo** | `bun run demo:productivity` |
| **Run CRUD Table Demo** | `bun run demo:crud` |
| **Run Theme Showcase** | `bun run demo:themes <theme_name>` |
| **Launch SQLite Studio** | `bun run app:sqlite` |
| **Launch Fd File Search** | `bun run app:fd` |
| **Launch Ripgrep Search** | `bun run app:rip` |
| **Compile Standalone Binary** | `bun run build:binary` |
| **Compile macOS `.app` Bundle** | `bun run scripts/build.ts app` |

### Keyboard Shortcuts & Power Actions

- **Ctrl / Cmd + S**: Save active form specification to disk.
- **Ctrl / Cmd + E**: Toggle live code generation preview panel.
- **Ctrl / Cmd + T**: Open the interactive theme picker dropdown.
- **Delete / Backspace**: Remove currently selected controls from canvas.
- **Arrow Keys**: Nudge selected controls by 1 pixel (hold **Shift** to nudge by 10 pixels).
- **Escape**: Deselect all active controls.

### Troubleshooting & Gotchas

> [!NOTE]
> **Linux `libwebkitgtk` missing**: If you see an error like `cannot open shared object file: libwebkitgtk-6.0.so.4`, install WebKitGTK using:
> ```bash
> sudo apt-get install -y libwebkitgtk-6.0-dev libgtk-3-dev
> ```

> [!TIP]
> **Lightweight Memory Usage**: If you need to monitor memory utilization of running Webview instances, check `System Studio` (`bun run app:system`), which reads RSS memory directly from the operating system kernel.

> [!IMPORTANT]
> **Packaging for Distribution**: When distributing `.app` bundles to other Mac computers outside your developer machine, ensure you codesign with your Apple Developer ID certificate:
> ```bash
> codesign --deep --force --verify --verbose --sign "Developer ID Application: Your Name (TeamID)" BunRADStudio.app
> ```

---

## Summary & Ecosystem Links

Bun RAD Studio brings back the joyful, high-speed visual application building experience of classic Delphi while fully embracing the modern TypeScript, Bun, and native WebKit runtime ecosystem.

- **GitHub Repository**: [codecaine-zz/bun_rad_studio](https://github.com/codecaine-zz/bun_rad_studio)
- **Sister Project (Native macOS V GUI)**: [codecaine-zz/vlang_simplegui](https://github.com/codecaine-zz/vlang_simplegui)
- **Webview Engine**: [Webview-Bun](https://github.com/codecaine-zz/bun_webview)
