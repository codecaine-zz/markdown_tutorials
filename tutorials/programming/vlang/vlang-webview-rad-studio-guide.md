# Vlang Webview RAD Studio Complete Guide

`vlang_webview_rad_studio` is a cross-platform visual Rapid Application Development (RAD) IDE and enterprise desktop suite powered by native [V (vlang)](https://vlang.io) and hardware-accelerated OS Webviews (macOS Cocoa / WebKit, Windows Win32 / Edge WebView2, and Linux GTK / WebKit2GTK).

Repository: [codecaine-zz/vlang_webview_rad_studio](https://github.com/codecaine-zz/vlang_webview_rad_studio)

---

## 📚 Table of Contents

1. [Architectural Overview & Lineage](#1-architectural-overview--lineage)
2. [Prerequisites & Environment Setup](#2-prerequisites--environment-setup)
3. [Visual RAD Form Designer Architecture](#3-visual-rad-form-designer-architecture)
4. [Cross-Platform Native Window Management](#4-cross-platform-native-window-management)
5. [Enterprise Production Workstations (16 Studios)](#5-enterprise-production-workstations-16-studios)
6. [Companion Headless CLI Tools (16 Utilities)](#6-companion-headless-cli-tools-16-utilities)
7. [Cross-Platform Packaging & Desktop Bundling (`build.vsh`)](#7-cross-platform-packaging--desktop-bundling-buildvsh)

---

## 1. Architectural Overview & Lineage

`vlang_webview_rad_studio` unifies several foundational open-source codebases authored by [@codecaine-zz](https://github.com/codecaine-zz):

```text
               ┌───────────────────────────────┐
               │    bun_rad_studio (IDE Core)   │
               │  Visual Designer, 70+ Controls │
               └───────────────┬───────────────┘
                               │ (Ported to V)
┌──────────────────────────────▼──────────────────────────────┐
│                  vlang_webview_rad_studio                   │
│          Native V + Lightweight OS Webview Engine           │
└──────────────▲───────────────────────────────▲──────────────┘
               │                               │
┌──────────────┴───────────────┐ ┌─────────────┴──────────────┐
│vlang_macos_webview_app_template│ │         vlang_utils          │
│Native Window Geometry & IPC  │ │   37 Production Utilities  │
└──────────────────────────────┘ └────────────────────────────┘
```

- **Zero Heavy Runtimes**: Utilizes the operating system's pre-installed Webview engine. Executables are ~3MB to ~8MB with instant launch times.
- **Borland Delphi & Visual Basic 6 Workflow**: Form canvas, component palette, property inspector, drag-and-drop alignment, and multi-target code generation.
- **Integrated Utilities**: 37 modular utilities from `vlang_utils` directly baked into the runtime.

---

## 2. Prerequisites & Environment Setup

### System Dependencies

```bash
# macOS
xcode-select --install
brew install v

# Ubuntu / Debian
sudo apt update && sudo apt install -y build-essential libwebkit2gtk-4.1-dev libgtk-3-dev
```

### Clone and Launch the Master IDE

```bash
git clone https://github.com/codecaine-zz/vlang_webview_rad_studio.git
cd vlang_webview_rad_studio

# Launch the visual RAD Studio IDE
v run main.v
```

---

## 3. Visual RAD Form Designer Architecture

The IDE provides a visual drag-and-drop workspace:

1. **Component Palette**: 70+ controls categorized into Standard, Additional, Data-Aware, Dialogs, and System.
2. **Form Canvas**: Pixel-grid visual canvas supporting multi-selection, alignment snapping, and coordinate placement.
3. **Object Inspector**: Live property editor modifying titles, bounds, colors, fonts, and event callbacks.
4. **Code Generator**: Generates clean, idiomatic V source code from visual FormSpec JSON descriptions.

---

## 4. Cross-Platform Native Window Management

Inherited from `vlang_macos_webview_app_template`, window placement is controlled via 9-point screen presets:

```v
import webview

// Native window initialization with placement presets
mut app := webview.new_window(
    title: 'Enterprise Studio'
    width: 1024
    height: 720
    preset: .center          // .upper_left, .top_center, .bottom_right, etc.
    always_on_top: false
    resizable: true
)

// Two-way IPC event loop
app.bind('onSaveConfig', fn (payload string) string {
    println('Received configuration from UI: ${payload}')
    return '{"status":"saved"}'
})

app.run()
```

---

## 5. Enterprise Production Workstations (16 Studios)

The project includes 16 production-grade desktop applications located in `applications/`:

| Workstation | Entry Point | Core Capabilities |
| :--- | :--- | :--- |
| **System Studio** | `applications/system_studio.v` | Live CPU, RAM, disk I/O, process killer, battery health |
| **Database Studio** | `applications/database_studio.v` | SQLite visual browser, SQL query editor, table exporter |
| **Docker Studio** | `applications/docker_studio.v` | Container lifecycle, live logs streamer, image manager |
| **API Studio** | `applications/api_studio.v` | REST API request builder, header inspector, JSON tree view |
| **Media Studio** | `applications/media_studio.v` | FFmpeg video/audio converter, trimmer, metadata editor |
| **Network Studio** | `applications/network_studio.v` | Nmap scanner, ping diagnostics, DNS resolver, Wi-Fi info |
| **Git Studio** | `applications/git_studio.v` | Visual branch graph, diff inspector, commit manager |
| **OmniTool Studio** | `applications/omnitool_studio.v` | Fast find, regex replace, safe deletion, code counter |
| **Markdown Studio** | `applications/markdown_studio.v` | Split-pane live Markdown editor with HTML preview |
| **Regex Studio** | `applications/regex_studio.v` | Real-time regex validator, match groups, syntax highlighter |
| **Security Studio** | `applications/security_studio.v` | Password generator, SHA/HMAC hasher, JWT decoder |
| **Cron Studio** | `applications/cron_studio.v` | Visual cron expression builder with schedule simulator |
| **App Bundler** | `applications/app_bundler_studio.v`| macOS .app packager with custom icon generator |
| **Color Studio** | `applications/color_studio.v` | Hex/RGB/HSL picker, contrast checker, palette generator |
| **Archive Studio** | `applications/archive_studio.v` | Zip/Tar archive manager, viewer, and compressor |
| **Log Studio** | `applications/log_studio.v` | Real-time log file tailer with regex filtering & alerts |

Run any application directly:

```bash
v run applications/system_studio.v
v run applications/database_studio.v
v run applications/api_studio.v
```

---

## 6. Companion Headless CLI Tools (16 Utilities)

Every studio application is paired with a headless CLI tool in `cli_apps/` for scripting, CI/CD pipelines, and remote SSH administration:

```bash
# System hardware telemetry
v run cli_apps/system_cli.v --json

# Query SQLite database from CLI
v run cli_apps/database_cli.v --db app.db "SELECT * FROM users;"

# Test REST endpoint
v run cli_apps/api_cli.v GET https://api.github.com

# Parse cron expression
v run cli_apps/cron_cli.v "0 */4 * * *"
```

---

## 7. Cross-Platform Packaging & Desktop Bundling (`build.vsh`)

The built-in bundler packages native executables, icons, and metadata into platform-specific application packages:

```bash
# Build standalone macOS .app bundle
v run build.vsh -n "RAD Studio" main.v

# Build a specific studio into a .app bundle
v run build.vsh -n "System Studio" applications/system_studio.v

# Build Windows installer/executable
v run build.vsh -t windows -n "SystemStudio" applications/system_studio.v

# Build Linux desktop package
v run build.vsh -t linux -n "SystemStudio" applications/system_studio.v
```
