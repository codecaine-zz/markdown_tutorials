# `simple_gg` — Cross-Platform SimpleGUI for V Complete Guide

`simple_gg` is a lightweight, beginner-friendly UI framework for building native, hardware-accelerated desktop applications in [V (vlang)](https://vlang.io). Built on top of V's native `gg` graphics module (powered by the Sokol rendering backend), `simple_gg` delivers uniform, 60+ FPS UI rendering across **macOS, Linux, and Windows** without relying on heavyweight external C or Objective-C dependencies.

Repository: [codecaine-zz/simple_gg](https://github.com/codecaine-zz/simple_gg)

---

## 📚 Table of Contents

1. [Architectural Overview & Core Philosophy](#1-architectural-overview--core-philosophy)
2. [Prerequisites & Getting Started](#2-prerequisites--getting-started)
3. [Window Creation & Fluent Builder API](#3-window-creation--fluent-builder-api)
4. [Control Palette & UI Components](#4-control-palette--ui-components)
5. [Reactive State Management & Two-Way Binding](#5-reactive-state-management--two-way-binding)
6. [Hardened Security & Safe Subprocess Execution Engine](#6-hardened-security--safe-subprocess-execution-engine)
7. [Enterprise Application Workstations (47 Production Apps)](#7-enterprise-application-workstations-47-production-apps)
8. [Building & Distributing Standalone Executables](#8-building--distributing-standalone-executables)

---

## 1. Architectural Overview & Core Philosophy

- **Zero Heavy Web Runtime**: No Chromium, Electron, or Node.js overhead. Memory footprint is typically 15MB–30MB RAM.
- **Hardware-Accelerated Sokol Engine**: Renders natively via Metal on macOS, DirectX on Windows, and OpenGL on Linux.
- **Fluent Declarative Syntax**: UI layouts are created cleanly using chainable builder methods: `win.add_button()`, `win.add_textbox()`, `win.add_slider()`.
- **Built-in Security Engine**: Hardened POSIX quote escaping and safe subprocess execution to prevent command injection vulnerabilities.

---

## 2. Prerequisites & Getting Started

### Installation

Clone `simple_gg` into your workspace or local V modules directory:

```bash
git clone https://github.com/codecaine-zz/simple_gg.git
cd simple_gg
```

### Minimal "Hello World" Window

```v
import simplegui

fn main() {
    mut win := simplegui.new_window(
        title: 'Hello simple_gg'
        width: 400
        height: 250
    )

    win.add_label(text: 'Welcome to simple_gg for V!', x: 20, y: 30)

    win.add_button(
        text: 'Click Me'
        x: 20
        y: 80
        width: 120
        height: 36
        on_click: fn (btn &simplegui.Button) {
            println('Button clicked!')
        }
    )

    win.run()
}
```

Run directly:

```bash
v run hello.v
```

---

## 3. Window Creation & Fluent Builder API

The `new_window` configuration options allow custom positioning, resizing, background styling, and refresh rates:

```v
mut win := simplegui.new_window(
    title: 'Developer Workstation'
    width: 900
    height: 600
    bg_color: simplegui.rgb(24, 24, 37) // Custom dark theme background
    resizable: true
    fps: 60
)
```

---

## 4. Control Palette & UI Components

`simple_gg` provides a comprehensive widget suite:

| Control | Builder Method | Description |
| :--- | :--- | :--- |
| **Button** | `win.add_button(...)` | Push buttons with hover animations and click handlers |
| **Label** | `win.add_label(...)` | Dynamic text labels with font sizing and color options |
| **TextBox** | `win.add_textbox(...)` | Single-line and multi-line editable text fields |
| **CheckBox** | `win.add_checkbox(...)` | Boolean toggle box with check state bindings |
| **Slider** | `win.add_slider(...)` | Numeric range slider with live drag updates |
| **ProgressBar** | `win.add_progressbar(...)`| Progress indicators for background tasks |
| **Table / DataGrid** | `win.add_table(...)` | Sortable tabular grids with row selection |
| **Image** | `win.add_image(...)` | Hardware-accelerated image viewer (PNG, JPEG) |
| **Dropdown** | `win.add_dropdown(...)` | Selectable picklist menus |

### Interactive Form Example

```v
mut win := simplegui.new_window(title: 'Settings', width: 450, height: 350)

// Text input
mut name_input := win.add_textbox(
    placeholder: 'Enter your username'
    x: 30, y: 40, width: 250, height: 32
)

// Checkbox
mut dark_mode_chk := win.add_checkbox(
    text: 'Enable Dark Mode'
    checked: true
    x: 30, y: 90
)

// Slider
mut volume_slider := win.add_slider(
    min: 0, max: 100, value: 75
    x: 30, y: 140, width: 250
)

// Submit button
win.add_button(
    text: 'Save Preferences'
    x: 30, y: 200, width: 160, height: 36
    on_click: fn [mut win, name_input, dark_mode_chk, volume_slider] (btn &simplegui.Button) {
        println('Saving user: ${name_input.text}, Dark: ${dark_mode_chk.checked}, Volume: ${volume_slider.value}')
    }
)

win.run()
```

---

## 5. Reactive State Management & Two-Way Binding

`simple_gg` includes reactive state bindings (`state.v`):

```v
import simplegui

struct AppState {
mut:
    counter int
    status string
}

fn main() {
    mut state := AppState{ counter: 0, status: 'Idle' }
    mut win := simplegui.new_window(title: 'Reactive Counter', width: 350, height: 200)

    mut label := win.add_label(text: 'Count: 0', x: 30, y: 40)

    win.add_button(
        text: 'Increment (+1)'
        x: 30, y: 90, width: 140, height: 36
        on_click: fn [mut label, mut state] (btn &simplegui.Button) {
            state.counter++
            label.set_text('Count: ${state.counter}')
        }
    )

    win.run()
}
```

---

## 6. Hardened Security & Safe Subprocess Execution Engine

`simple_gg` includes a built-in security engine (`simplegui/security.v`) to protect desktop apps from command injection when executing shell tools:

```v
import simplegui

// 1. POSIX Single-Quote Escaping
safe_arg := simplegui.quote_arg("user input; rm -rf /")
// Result: 'user input; rm -rf /' (fully neutralized!)

// 2. Safe Subshell Execution (Automatically escapes all arguments)
res := simplegui.exec_safe('git', ['log', '-n', '5', '--oneline'])
println('Git Log:\n${res.output}')

// 3. Filename Sanitization (Strips path traversal tokens like ../)
clean_name := simplegui.sanitize_filename('../../../etc/passwd')
// Result: 'etcpasswd'
```

---

## 7. Enterprise Application Workstations (47 Production Apps)

`simple_gg` includes 47 full-featured desktop applications in `applications/`:

| Workstation | Category | Run Command | Description |
| :--- | :--- | :--- | :--- |
| **`omnitool_studio.v`** | DevTools | `v run applications/omnitool_studio.v` | Developer Swiss Army Knife: fd, sd, watchexec, rg, rip, bat, eza |
| **`watchexec_studio.v`** | DevTools | `v run applications/watchexec_studio.v` | Real-time filesystem watcher with debouncing & trigger actions |
| **`app_bundler_studio.v`** | Packaging| `v run applications/app_bundler_studio.v`| macOS .app bundler, Retina .icns generator & packager |
| **`api_studio.v`** | DevTools | `v run applications/api_studio.v` | API testing client & HTTP REST request builder |
| **`media_studio_hub.v`** | Media | `v run applications/media_studio_hub.v` | Audio, video, and image transformation suite |
| **`ffmpeg_studio.v`** | Media | `v run applications/ffmpeg_studio.v` | Video/audio encoding, format conversion, and clipping |
| **`sqlite_studio.v`** | Database | `v run applications/sqlite_studio.v` | Visual SQLite schema browser, table editor, and SQL query runner |
| **`docker_studio.v`** | DevOps | `v run applications/docker_studio.v` | Docker container inspector, logs tailer, and lifecycle manager |
| **`task_manager.v`** | System | `v run applications/task_manager.v` | Real-time process monitor with CPU and memory telemetry |
| **`nmap_studio.v`** | Security | `v run applications/nmap_studio.v` | Network security scanner, subnet discovery, and port analyzer |

---

## 8. Building & Distributing Standalone Executables

Compile native, zero-dependency release binaries:

```bash
# Optimized production build
v -prod -skip-unused main.v

# Output binary size is typically ~2MB to ~5MB!
ls -lh main
```
