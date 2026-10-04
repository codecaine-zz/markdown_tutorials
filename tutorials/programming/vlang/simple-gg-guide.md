# `simple_gg` — Cross-Platform Sokol SimpleGUI for V Complete Guide

`simple_gg` is an enterprise-grade, lightweight UI framework for building native, hardware-accelerated desktop applications in [V (vlang)](https://vlang.io). Built directly on top of V's native `gg` graphics module (powered by the Sokol rendering backend), `simple_gg` delivers uniform, 60+ FPS UI rendering across **macOS, Linux, and Windows** without relying on heavyweight external C, Objective-C, or web runtime dependencies.

Repository: [codecaine-zz/simple_gg](https://github.com/codecaine-zz/simple_gg)

---

## 📚 Table of Contents

1. [Architectural Overview & Core Philosophy](#1-architectural-overview--core-philosophy)
2. [Prerequisites & Module Setup](#2-prerequisites--module-setup)
3. [60-Second Copy-Paste Starter Application](#3-60-second-copy-paste-starter-application)
4. [Window Configuration & Layout Engine](#4-window-configuration--layout-engine)
   - [Window Builder Options](#window-builder-options)
   - [Horizontal Rows (`begin_row`)](#horizontal-rows-begin_row)
   - [Multi-Column Responsive Grids (`begin_grid`)](#multi-column-responsive-grids-begin_grid)
   - [Flexbox Containers (`begin_flex_box`)](#flexbox-containers-begin_flex_box)
   - [Tabbed Navigation & Group Cards](#tabbed-navigation--group-cards)
5. [The 87 Built-in Production Themes](#5-the-87-built-in-production-themes)
6. [Core Widget Reference](#6-core-widget-reference)
7. [The Modern Super Controls Suite](#7-the-modern-super-controls-suite)
   - [Tag Input Control (`TagInput`)](#tag-input-control-taginput)
   - [Dual-Thumb Range Slider (`RangeSlider`)](#dual-thumb-range-slider-rangeslider)
   - [Monospace Code Editor (`CodeEditor`)](#monospace-code-editor-codeeditor)
   - [Native File Drop Zone (`FileDropZone`)](#native-file-drop-zone-filedropzone)
   - [Delphi-Style Property Grid (`PropertyGrid`)](#delphi-style-property-grid-propertygrid)
   - [Sparkline Micro-Trend Charts (`Sparkline`)](#sparkline-micro-trend-charts-sparkline)
   - [Toast Notification Stack & Floating Alerts](#toast-notification-stack--floating-alerts)
   - [Command Palette (`Ctrl+K`) & Context Menus](#command-palette-ctrlk--context-menus)
8. [Reactive State Management & Crash-Proof Persistence (`state.v`)](#8-reactive-state-management--crash-proof-persistence-statev)
9. [Hardened Security & Safe Subprocess Engine (`sys.v`)](#9-hardened-security--safe-subprocess-engine-sysv)
10. [Headless Console RAD Toolkit (`simplecli`)](#10-headless-console-rad-toolkit-simplecli)
11. [V Standard Library Integrations (`stdlib.v`)](#11-v-standard-library-integrations-stdlibv)
12. [Bundled Developer Utilities (`vlang_utils` 40-Module Integration)](#12-bundled-developer-utilities-vlang_utils-40-module-integration)
13. [Enterprise Application Workstations (47 Production Apps)](#13-enterprise-application-workstations-47-production-apps)
14. [Interactive Demos & CLI Companion Tools](#14-interactive-demos--cli-companion-tools)
15. [Compiling & Distributing Standalone Executables](#15-compiling--distributing-standalone-executables)

---

## 1. Architectural Overview & Core Philosophy

`simple_gg` was designed to eliminate the heavy baggage of Electron, Chromium, and bloated multi-gigabyte GUI toolkits while offering a first-class visual experience:

```text
┌─────────────────────────────────────────────────────────────┐
│                 simple_gg Application (V)                   │
│             User Windows, Layouts & Event Handlers          │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│                   simplegui API Framework                   │
│  Super Controls • 87 Themes • Reactive State • simplecli   │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│              V Native Graphics Subsystem (`gg`)             │
│        Sokol GFX: Metal (macOS) • DX11 (Win) • GL (Linux)   │
└─────────────────────────────────────────────────────────────┘
```

- **Zero Heavy Web Runtimes**: No Chromium or Node.js. Typical memory footprint is **15MB to 30MB RAM** at idle.
- **Hardware-Accelerated Sokol Engine**: Renders directly via Metal on macOS, DirectX on Windows, and OpenGL on Linux at rock-solid 60+ FPS.
- **Ultra-Fast Startup**: Cold boot is virtually instantaneous (< 100 milliseconds).
- **Fluent Declarative API**: Build complex forms, grids, and dashboards using chainable methods without managing low-level matrix transformations.
- **Cross-Platform Parity**: Identical appearance, theme tokens, and behavior across all three major desktop operating systems.

---

## 2. Prerequisites & Module Setup

### System Prerequisites

Make sure the V compiler is installed:

```bash
# macOS
brew install v

# Linux (Ubuntu/Debian)
sudo apt update && sudo apt install -y build-essential libgl1-mesa-dev libxcursor-dev libxi-dev libxinerama-dev libxrandr-dev
```

### Installation

Clone the repository and link it into your V modules path:

```bash
git clone https://github.com/codecaine-zz/simple_gg.git
mkdir -p ~/.vmodules
ln -s "$PWD/simple_gg/simplegui" ~/.vmodules/simplegui
```

Now you can import `simplegui` from any directory:

```v
import simplegui
```

---

## 3. 60-Second Copy-Paste Starter Application

Create `main.v`:

```v
module main

import simplegui

fn main() {
    // 1. Create a modern window with dark theme
    mut win := simplegui.new_simple_window('Developer Workstation', 720, 520)
    win.set_theme('Apple Dark')

    // 2. Add Title and Form Header
    win.add_heading('Cloud Service Deployment')
    win.add_form_field('Service Name:', 'txt_service', 'auth-gateway')
    win.add_form_field('Cluster Target:', 'txt_cluster', 'us-east-prod-1')

    // 3. Add Settings Controls
    win.add_checkbox('chk_ssl', 'Enforce Mutual TLS (mTLS)', true)
    win.add_slider('sld_replicas', 'Pod Replicas: 3', 1, 10, 3)

    // 4. Horizontal Button Row
    win.begin_row('btn_actions')
    win.add_button('btn_deploy', '[Deploy] Push to Cluster')
    win.add_button('btn_reset', '[Reset] Defaults')
    win.end_row()

    // 5. Interactive Event Callbacks
    win.on_click('btn_deploy', fn (mut win simplegui.SimpleWindow) {
        svc := win.get_text('txt_service')
        cluster := win.get_text('txt_cluster')
        replicas := win.get_slider_value('sld_replicas')

        // Show floating toast alert
        win.info('Deployment Queued', 'Deploying ${replicas} replicas of ${svc} to ${cluster}')
    })

    win.on_click('btn_reset', fn (mut win simplegui.SimpleWindow) {
        win.set_text('txt_service', 'new-service')
        win.warn('Form Reset', 'Parameters returned to defaults.')
    })

    win.run()
}
```

Run directly:

```bash
v run main.v
```

---

## 4. Window Configuration & Layout Engine

### Window Builder Options

Create customized windows with explicit sizing, refresh rates, background colors, and resizing options:

```v
mut win := simplegui.new_window(
    title: 'High-Performance Dashboard'
    width: 1024
    height: 768
    fps: 60
    resizable: true
    bg_color: simplegui.rgb(20, 22, 30)
)
```

### Horizontal Rows (`begin_row`)

Group multiple controls horizontally on a single line with automatic spacing:

```v
win.begin_row('action_bar')
win.add_button('btn_start', 'Start')
win.add_button('btn_pause', 'Pause')
win.add_button('btn_stop', 'Stop')
win.end_row()
```

### Multi-Column Responsive Grids (`begin_grid`)

Evenly distribute controls across columns:

```v
// 3-column metric cards grid
win.begin_grid('metrics_grid', 3, 16) // (id, columns, gap_px)
win.add_metric_card('card_cpu', 'CPU Load', '42%', '+2.4%')
win.add_metric_card('card_mem', 'RAM Usage', '14.2 GB', 'Stable')
win.add_metric_card('card_disk', 'Disk Free', '248 GB', '-1.1%')
win.end_grid()
```

### Flexbox Containers (`begin_flex_box`)

Dynamic flex containers supporting justify content and align items:

```v
win.begin_flex_box('toolbar_flex')
win.add_search_box('search_input', 'Filter logs...')
win.add_dropdown('filter_level', ['ALL', 'INFO', 'WARN', 'ERROR'], 0)
win.end_flex_box()
```

### Tabbed Navigation & Group Cards

Organize large workflows into clean multi-tab panels:

```v
mut tabs := win.add_tab_container('main_tabs', ['Overview', 'Networking', 'Security', 'Logs'])

// Group Card for visual separation
win.begin_card('Database Connection Settings')
win.add_form_field('Host:', 'db_host', '127.0.0.1')
win.add_form_field('Port:', 'db_port', '5432')
win.end_card()
```

---

## 5. The 87 Built-in Production Themes

`simple_gg` integrates the entire **87-theme design palette** (the full 76-theme Bun RAD Studio design catalog plus exclusive SimpleGUI editions). Every theme specifies custom colors for background, surface, borders, primary accent, secondary accent, muted text, and active focus highlights.

### Switching Themes Dynamically

```v
// Switch by exact name anytime
win.set_theme('Nord')
win.set_theme('Dracula')
win.set_theme('Apple Dark')
win.set_theme('Cyberpunk')
win.set_theme('Catppuccin Mocha')
win.set_theme('Tokyo Night')
win.set_theme('Gruvbox Dark')
win.set_theme('Solarized Dark')
win.set_theme('Synthwave')
win.set_theme('Monokai Pro')
```

### Theming API Helpers

```v
// Query all available theme names
names := simplegui.available_themes()

// Get theme color tokens
theme := win.get_current_theme()
println('Primary Accent: ${theme.primary}')
println('Surface Background: ${theme.surface}')
```

---

## 6. Core Widget Reference

| Control | Constructor / Method | Description |
| :--- | :--- | :--- |
| **Button** | `win.add_button(id, label)` | Push buttons with hover elevation, active state, and click handlers |
| **Label** | `win.add_label(id, text)` | Text labels with customizable font sizing and color overrides |
| **Heading** | `win.add_heading(text)` | Prominent section titles with automatic spacing |
| **TextBox** | `win.add_textbox(id, default)` | Single-line editable text input with selection & clipboard |
| **TextArea** | `win.add_textarea(id, text)` | Multi-line text editor with auto-scroll and word wrapping |
| **CheckBox** | `win.add_checkbox(id, label, val)` | Toggleable checkbox with boolean value synchronization |
| **ToggleSwitch** | `win.add_toggle(id, label, on)` | iOS/macOS-style sliding toggle switch |
| **Slider** | `win.add_slider(id, label, min, max, val)` | Numeric range slider with live drag updates |
| **Dropdown** | `win.add_dropdown(id, items, idx)` | Selectable dropdown popup menu |
| **ProgressBar**| `win.add_progressbar(id, val)` | Smooth progress bar (0.0 to 100.0) |
| **DataTable** | `win.add_datatable(id, cols, rows)` | High-performance sortable table with row selection |
| **TreeView** | `win.add_treeview(id, root_node)` | Collapsible hierarchical file/schema explorer |
| **MetricCard** | `win.add_metric_card(id, title, val, trend)` | KPI dashboard tile with delta indicator |
| **Accordion** | `win.add_accordion(id, title, open)` | Expandable/collapsible content pane |
| **Alert** | `win.add_alert(id, msg, type)` | In-line informational, warning, and error notice banners |

---

## 7. The Modern Super Controls Suite

`simple_gg` features the **Super Controls Suite**—a set of advanced desktop controls tailored for enterprise IDEs, devtools, and data workstations.

### Tag Input Control (`TagInput`)

Interactive multi-tag token field with backspace tag removal and chip rendering:

```v
mut tags := win.add_tag_input('tag_editor', ['vlang', 'desktop', 'cross-platform'])
tags.on_tag_added(fn (tag string) {
    println('Added tag: ${tag}')
})
```

### Dual-Thumb Range Slider (`RangeSlider`)

Slider supporting lower and upper bounds simultaneously (e.g. price range, port range):

```v
mut range := win.add_range_slider('port_range', 1024, 65535, 8000, 9000)
win.on_change('port_range', fn (mut win simplegui.SimpleWindow) {
    min_val, max_val := win.get_range_slider_values('port_range')
    println('Selected range: ${min_val} - ${max_val}')
})
```

### Monospace Code Editor (`CodeEditor`)

Lightweight code editor with line numbering gutter, cursor navigation, and text search:

```v
mut editor := win.add_code_editor('sql_query', 'SELECT * FROM users\nWHERE active = 1;')
editor.set_font_size(14)
```

### Native File Drop Zone (`FileDropZone`)

Accepts OS drag-and-drop file events:

```v
mut drop := win.add_file_drop_zone('upload_zone', 'Drag & Drop files here or click to browse')
drop.on_drop(fn (files []string) {
    for f in files {
        println('Received file: ${f}')
    }
})
```

### Delphi-Style Property Grid (`PropertyGrid`)

Two-column inspector for editing component properties and settings:

```v
mut prop_grid := win.add_property_grid('config_grid')
prop_grid.add_property('Service Name', 'Gateway', .string_val)
prop_grid.add_property('Port', '8080', .int_val)
prop_grid.add_property('Debug Mode', 'true', .bool_val)
```

### Sparkline Micro-Trend Charts (`Sparkline`)

Compact inline trend visualizer embedded inside cards or table rows:

```v
win.add_sparkline('trend_latency', [24.0, 22.5, 30.1, 45.0, 28.2, 19.5, 21.0])
```

### Toast Notification Stack & Floating Alerts

Non-blocking auto-dismissing toast notifications rendered in the top-right corner:

```v
win.info('System Update', 'Database migration applied successfully.')
win.warn('Disk Warning', 'Available storage is below 15%.')
win.error('Connection Failed', 'Could not reach remote Redis instance.')
```

### Command Palette (`Ctrl+K`) & Context Menus

Quick fuzzy action launcher and right-click contextual menus:

```v
// Register global command palette items
win.register_command('Git: Pull Origin', fn () { /* ... */ })
win.register_command('Theme: Toggle Dark/Light', fn () { /* ... */ })

// Right-click context menu
win.add_context_menu('table_menu', [
    simplegui.MenuItem{ label: 'Copy Row JSON', action: 'copy_json' },
    simplegui.MenuItem{ label: 'Delete Record', action: 'delete_row' },
])
```

---

## 8. Reactive State Management & Crash-Proof Persistence (`state.v`)

`simple_gg` includes an integrated reactive state store with automated crash-proof disk persistence:

```v
import simplegui

// 1. Reactive State Store
win.set_state('user_id', 42)
win.set_state('username', 'AdaLovelace')
win.set_state('logged_in', true)

// 2. Typed State Accessors
uid := win.get_state_int('user_id')        // 42
uname := win.get_state_string('username')  // "AdaLovelace"

// 3. State Change Observers
win.on_state_change('username', fn (new_val string) {
    println('Username updated to: ${new_val}')
})

// 4. Atomic Crash-Proof State Persistence
// Automatically saves key-value store to standard OS AppData directory
win.save_app_state('MyDeveloperStudio')!

// Restore on next launch
win.load_app_state('MyDeveloperStudio')!

// 5. Window Geometry & Session Restoration
// Remembers window position, dimensions, and fullscreen state
win.restore_window_session('MyDeveloperStudio')
```

---

## 9. Hardened Security & Safe Subprocess Engine (`sys.v`)

Desktop tools often execute external command-line utilities (`git`, `ffmpeg`, `nmap`, `docker`). `simple_gg` eliminates command injection vulnerabilities using safe subprocess execution:

```v
import simplegui

// 1. POSIX Single-Quote Argument Neutralization
safe_arg := simplegui.quote_arg("malicious; rm -rf /")
// Output: 'malicious; rm -rf /' (fully escaped and neutralized)

// 2. Safe Subprocess Execution (Bypasses shell evaluation)
res := simplegui.exec_safe('git', ['log', '-n', '5', '--oneline'])
println('Git Output:\n${res.output}')

// 3. File Path Sanitization (Blocks directory traversal attacks)
safe_path := simplegui.sanitize_filename('../../../etc/passwd')
// Output: 'etcpasswd'

// 4. Real-Time Hardware Telemetry
cpu := simplegui.cpu_info()
ram := simplegui.memory_info()
println('CPU: ${cpu.model} (${cpu.cores} cores), RAM: ${ram.used_mb}MB / ${ram.total_mb}MB')
```

---

## 10. Headless Console RAD Toolkit (`simplecli`)

`simple_gg` includes `simplecli`—a zero-window terminal framework for companion CLI tools:

```v
import simplecli

mut parser := simplecli.new_flag_parser('deploy_tool', '1.0.0')
env := parser.string('env', `e`, 'production', 'Target deployment environment')
replicas := parser.int('replicas', `r`, 3, 'Number of instances')
parser.parse()!

// Styled ANSI Terminal Output
simplecli.print_header('Deployment Pipeline')
simplecli.print_success('Connected to cluster')

// Terminal Table
mut tbl := simplecli.new_table(['Service', 'Status', 'Instances'])
tbl.add_row(['API Gateway', 'Healthy', '3'])
tbl.add_row(['Auth Service', 'Healthy', '2'])
println(tbl.render())
```

---

## 11. V Standard Library Integrations (`stdlib.v`)

`stdlib.v` exposes fluent wrappers around common V standard library operations directly from the `simplegui` namespace:

```v
import simplegui

// 1. HTTP GET & POST
resp := simplegui.http_get('https://api.github.com')!

// 2. Cryptography
sha := simplegui.sha256('password123')
hmac := simplegui.hmac_sha256('payload', 'secret')

// 3. Zstandard & Gzip Compression
compressed := simplegui.compress_zstd('data buffer...')!

// 4. TOML Configuration Parsing
toml_doc := simplegui.parse_toml('[server]\nport = 8080\n')!
```

---

## 12. Bundled Developer Utilities (`vlang_utils` 40-Module Integration)

All **40 production modules** from `vlang_utils` v2.0.0 are directly available in `simple_gg`:

```v
import fileutils
import sqliteutils
import webutils
import jsonutils
import markdownutils
import asyncutils
import cacheutils
import cryptoutils
```

- Query JSON via RFC 6901 pointers with `jsonutils.pointer_get`.
- Parse CommonMark/GFM markdown with `markdownutils.to_html`.
- Run background tasks via `asyncutils.new_worker_pool`.
- Maintain O(1) in-memory data with `cacheutils.new_lru_cache`.

---

## 13. Enterprise Application Workstations (47 Production Apps)

`simple_gg` includes **47 full-featured desktop workstation applications** in the `applications/` directory:

| Workstation | Category | Run Command | Core Capabilities |
| :--- | :--- | :--- | :--- |
| **`omnitool_studio.v`** | DevTools | `v run applications/omnitool_studio.v` | Multi-tool swiss army knife: fd, sd, watchexec, rg, rip, bat, eza |
| **`watchexec_studio.v`** | DevTools | `v run applications/watchexec_studio.v` | Filesystem watcher with debounced triggers and auto-rebuild |
| **`app_bundler_studio.v`**| Packaging| `v run applications/app_bundler_studio.v`| macOS .app packager, Retina icon generator, DMG builder |
| **`api_studio.v`** | Networking| `v run applications/api_studio.v` | REST API testing workbench with headers, payloads & JSON tree |
| **`media_studio_hub.v`** | Media | `v run applications/media_studio_hub.v` | FFmpeg-powered video/audio encoder, trimmer, and format converter |
| **`sqlite_studio.v`** | Database | `v run applications/sqlite_studio.v` | Visual SQLite schema designer, SQL editor, and data exporter |
| **`docker_studio.v`** | DevOps | `v run applications/docker_studio.v` | Docker container inspector, image manager, and live log tailer |
| **`task_manager.v`** | System | `v run applications/task_manager.v` | Real-time process monitor with CPU/RAM metrics and process kill |
| **`nmap_studio.v`** | Security | `v run applications/nmap_studio.v` | Subnet discovery, port security auditing, and service banner grabber |
| **`crypto_studio.v`** | Security | `v run applications/crypto_studio.v` | SHA/HMAC hash generator, AES cipher tool, and JWT validator |
| **`brew_studio.v`** | Package | `v run applications/brew_studio.v` | Homebrew package browser, update checker, and cleanup workstation |
| **`dns_studio.v`** | Network | `v run applications/dns_studio.v` | DNS record lookup (A, AAAA, MX, TXT, CNAME) and propagation check |
| **`imagemagick_studio.v`**| Media | `v run applications/imagemagick_studio.v`| Batch image resizing, format conversion, watermarking, filters |
| **`jq_studio.v`** | DevTools | `v run applications/jq_studio.v` | Interactive JSON query explorer and syntax-highlighted formatter |
| **`kalker_studio.v`** | Math | `v run applications/kalker_studio.v` | Scientific math calculator, unit conversion, and calculus solver |
| **`ocr_studio.v`** | AI/Vision | `v run applications/ocr_studio.v` | Tesseract-powered optical character recognition on images and PDFs |
| **`pandoc_studio.v`** | Document | `v run applications/pandoc_studio.v` | Multi-format document converter (Markdown, PDF, DOCX, HTML, EPUB) |
| **`ouch_studio.v`** | Archive | `v run applications/ouch_studio.v` | Visual archive compressor and extractor (Zip, Tar, 7z, Gz, Zstd) |

Run any application directly:

```bash
v run applications/sqlite_studio.v
v run applications/api_studio.v
v run applications/docker_studio.v
```

---

## 14. Interactive Demos & CLI Companion Tools

### Running Interactive Demos

The `demos/` directory contains 44 focused demos showcasing individual components and features:

```bash
v run demos/01_quickstart.v
v run demos/02_theme_gallery.v
v run demos/06_dashboard.v
v run demos/11_data_table_pro.v
v run demos/13_reactive_state_store.v
v run demos/22_super_controls.v
v run demos/23_modern_images.v
v run demos/25_modern_ui_suite.v
```

### 52 Companion Headless CLI Tools

Every workstation in `applications/` has a headless counterpart in `cli_apps/` for automated scripting and SSH servers:

```bash
# Query SQLite from CLI
v run cli_apps/sqlite_cli.v --db app.db "SELECT count(*) FROM users;"

# Inspect containers
v run cli_apps/docker_cli.v --running

# Test REST endpoint
v run cli_apps/api_cli.v GET https://api.github.com
```

---

## 15. Compiling & Distributing Standalone Executables

Compile ultra-optimized standalone release binaries without debug symbols:

```bash
# Production build
v -prod -skip-unused main.v

# Verify binary size
ls -lh main
# Resulting binary is typically ~2MB to ~5MB!
```

### Cross-Compiling

Because `simple_gg` relies on Sokol, you can cross-compile between desktop operating systems using V's built-in flags or Docker toolchains:

```bash
# Compile for Windows from macOS/Linux
v -os windows -prod -skip-unused main.v

# Compile for Linux from macOS
v -os linux -prod -skip-unused main.v
```
