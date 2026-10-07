# Vlang Desktop & Systems Ecosystem Suite - The Master Architectural Guide

Welcome to the comprehensive architectural guide to the **V Desktop & Systems Engineering Ecosystem**, authored and maintained by [@codecaine-zz](https://github.com/codecaine-zz).

This ecosystem delivers a complete, cohesive developer platform for building high-performance native desktop applications, rapid application development (RAD) studios, and production backend/CLI utilities in [V (vlang)](https://vlang.io).

---

## 🏛️ The Four Foundational Pillars

```text
┌─────────────────────────────────────────────────────────────────────────────┐
│                             THE VLANG ECOSYSTEM                             │
└──────────────────────────────────────┬──────────────────────────────────────┘
                                       │
         ┌─────────────────────────────┼─────────────────────────────┐
         ▼                             ▼                             ▼
┌──────────────────┐          ┌──────────────────┐          ┌──────────────────┐
│ vlang_simplegui  │          │    simple_gg     │          │vlang_webview_rad │
│  (Native Cocoa)  │          │  (Sokol Canvas)  │          │     _studio      │
│  macOS / AppKit  │          │ Cross-Platform   │          │ (Webview Visual) │
│ ~1MB RAM / 1MB Bin│         │ 87 Themes / 60FPS│          │ Delphi / VB RAD  │
└────────┬─────────┘          └────────┬─────────┘          └────────┬─────────┘
         │                             │                             │
         └─────────────────────────────┼─────────────────────────────┘
                                       │ Powered by
                                       ▼
                    ┌──────────────────────────────────────┐
                    │             vlang_utils              │
                    │   40 Production Utility Modules      │
                    │ (v2.0.0: Web, JSON, Markdown, Crypto)│
                    └──────────────────────────────────────┘
```

| Repository | Paradigm | Target Platforms | Key Strengths |
| :--- | :--- | :--- | :--- |
| [**`vlang_simplegui`**](https://github.com/codecaine-zz/vlang_simplegui) | **Native Cocoa / AppKit** | macOS | Direct Objective-C runtime bridge, genuine Apple widgets, native accessibility, ~1MB executable, sub-millisecond launch. |
| [**`simple_gg`**](https://github.com/codecaine-zz/simple_gg) | **Sokol GFX Canvas** | macOS, Linux, Windows | Hardware-accelerated (Metal/DX11/OpenGL), 87 built-in themes, Super Controls suite, zero web runtimes, 15-30MB RAM. |
| [**`vlang_webview_rad_studio`**](https://github.com/codecaine-zz/vlang_webview_rad_studio) | **OS Webview + Visual IDE** | macOS, Linux, Windows | Borland Delphi / VB6 drag-and-drop form designer, 70+ components, 9-point placement geometry, 42 themes, ~3-8MB binaries. |
| [**`vlang_utils`**](https://github.com/codecaine-zz/vlang_utils) | **Universal Utility Engine** | macOS, Linux, Windows | 40 production-grade modules (Web, JSON pointer/patch, GFM Markdown, SQLite, Crypto, Async, Flow), zero external C dependencies. |

---

## 📑 Table of Contents

1. [Architectural Comparison & Technology Selection](#1-architectural-comparison--technology-selection)
2. [Deep Dive: `vlang_simplegui` (Native macOS Cocoa)](#2-deep-dive-vlang_simplegui-native-macos-cocoa)
3. [Deep Dive: `simple_gg` (Cross-Platform Sokol Hardware Canvas)](#3-deep-dive-simple_gg-cross-platform-sokol-hardware-canvas)
4. [Deep Dive: `vlang_webview_rad_studio` (Visual RAD IDE & Webview)](#4-deep-dive-vlang_webview_rad_studio-visual-rad-ide--webview)
5. [Deep Dive: `vlang_utils` (The 40-Module Universal Backbone)](#5-deep-dive-vlang_utils-the-40-module-universal-backbone)
6. [Cross-Framework Code Rosetta Stone](#6-cross-framework-code-rosetta-stone)
   - [Example: Creating a Window, Labeled Input & Button](#example-creating-a-window-labeled-input--button)
7. [Enterprise Workstation Catalog Summary (115 Production Apps)](#7-enterprise-workstation-catalog-summary-115-production-apps)
8. [Quick Navigation to Dedicated Tutorials](#8-quick-navigation-to-dedicated-tutorials)

---

## 1. Architectural Comparison & Technology Selection

### Feature & Capability Matrix

| Feature / Metric | `vlang_simplegui` | `simple_gg` | `vlang_webview_rad_studio` |
| :--- | :--- | :--- | :--- |
| **Rendering Engine** | Apple AppKit (`NSWindow` / `NSView`) | Sokol GFX (`gg` Metal/DX/GL) | Native OS Webview (WebKit / Edge) |
| **Operating Systems** | macOS Only | macOS, Linux, Windows | macOS, Linux, Windows |
| **RAM Footprint (Idle)**| **~1 MB – 5 MB** | **15 MB – 30 MB** | **35 MB – 65 MB** |
| **Binary Size (Release)**| **~800 KB – 1.8 MB** | **~2 MB – 4.5 MB** | **~3 MB – 8 MB** |
| **Cold Startup Time** | **< 10 ms** | **< 80 ms** | **< 150 ms** |
| **Visual Form Designer**| Built-in (`designer.html` + `designer.v`) | Code Builder | Master Visual IDE (`bun_rad_studio` port) |
| **Built-in Themes** | 18 Curated Themes | **87 Production Themes** | **42 Theme Design System** |
| **Component Palette** | 40+ Cocoa Controls | 45+ Super Controls & Grids | 70+ Delphi-Style Components |
| **Web Runtime Needed?** | **None** | **None** | Uses Pre-installed OS Webview |
| **Interactive Demos** | 135 Demos | 44 Demos | 66 Demos |
| **Production Apps** | 50 Workstations | 47 Workstations | 18 Studios |
| **Companion CLI Tools**| 52 Tools (`simplecli`) | 52 Tools (`simplecli`) | 16 CLI Tools |

### When to Choose What?

```text
Decision Flowchart:
Are you targeting macOS only and demand 100% genuine Apple native UI?
  ├── YES ──► Choose `vlang_simplegui` (AppKit, Cocoa menus, TouchBar, <1MB RAM)
  └── NO (Need Windows & Linux support)
        │
        ├── Do you want a Visual Drag-and-Drop RAD Form Designer (Delphi/VB style)?
        │     ├── YES ──► Choose `vlang_webview_rad_studio` (70+ components, Webview)
        │     └── NO  ──► Continue
        │
        └── Do you want ultra-fast 60+ FPS hardware graphics with zero web dependencies?
              └── YES ──► Choose `simple_gg` (Sokol gg, 87 themes, 15MB RAM)
```

---

## 2. Deep Dive: `vlang_simplegui` (Native macOS Cocoa)

**Primary Focus**: Pure native macOS desktop applications with genuine Apple AppKit widgets.

- **No Canvas Simulation**: `win.add_button()` creates an actual `NSButton`, not pixels drawn on a buffer. It supports macOS voiceover accessibility, native spellcheck, dictionary lookup, standard keybindings (`Cmd+C`, `Cmd+V`, `Cmd+Z`), and system dark mode automatically.
- **Form Automation**: Automatically generate clean macOS forms from any V `struct` using compile-time field reflection and struct tags (`[validate: "email"]`, `[label: "Full Name"]`).
- **TouchBar & Menus**: Native macOS menu bar integration, status items (menu bar tray icons), and Dock badging.
- **50 Workstations & 135 Demos**: Includes workstations for `ripgrep`, `fd`, `sqlite`, `ffmpeg`, `docker`, `nmap`, and developer packaging.

👉 **Full Dedicated Guide**: [SimpleGUI Complete Project Guide](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-simplegui-guide.md)  
👉 **API Reference**: [SimpleGUI Complete API Reference](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/simplegui-api-guide.md)

---

## 3. Deep Dive: `simple_gg` (Cross-Platform Sokol Hardware Canvas)

**Primary Focus**: High-performance, cross-platform desktop UI running at 60+ FPS on macOS, Linux, and Windows with zero web overhead.

- **Sokol Backend**: Direct Metal rendering on macOS, DirectX 11 on Windows, and OpenGL on Linux.
- **87 Production Themes**: Includes the entire 76-theme Bun RAD Studio catalog (Nord, Dracula, Cyberpunk, Catppuccin, Tokyo Night, Gruvbox, etc.) plus SimpleGUI exclusives.
- **Super Controls Palette**: TagInput, dual-thumb RangeSlider, monospace CodeEditor with line gutter, native FileDropZone, Delphi-style PropertyGrid, embedded Sparklines, SplitView, Command Palette (`Ctrl+K`), and Context Menus.
- **Universal State & Session**: Key-value reactive store, automatic app data folder persistence, and window geometry restoration.
- **47 Workstations & 44 Demos**: Production tools including `omnitool_studio.v`, `sqlite_studio.v`, `docker_studio.v`, `api_studio.v`, and `app_bundler_studio.v`.

👉 **Full Dedicated Guide**: [simple_gg Complete Guide](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/simple-gg-guide.md)

---

## 4. Deep Dive: `vlang_webview_rad_studio` (Visual RAD IDE & Webview)

**Primary Focus**: Visual Rapid Application Development (RAD) with a drag-and-drop form canvas, component inspector, and two-way IPC.

- **Borland Delphi / Visual Basic Workflow**: Visual form canvas with grid snapping, Object Inspector for property edits, non-visual component tray, and one-click V code generation.
- **70+ Component Palette**: Standard controls, data-aware grids, dialogs, timers, and advanced devtools.
- **Window Management Foundation**: Derived directly from `vlang_macos_webview_app_template`, featuring Cocoa `window_helper.m` bridging, 9-point screen placement presets (`center`, `upper_left`, etc.), stay-on-top pinning, and fullscreen control.
- **Bidirectional IPC**: Bind native V functions directly to JavaScript (`w.bind()`) and evaluate UI code from V (`w.eval()`).
- **18 Enterprise Studios & 66 Demos**: Standalone IDE suites including System Studio, Database Studio, Docker Studio, API Studio, and Git Studio.

👉 **Full Dedicated Guide**: [vlang_webview_rad_studio Complete Guide](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-webview-rad-studio-guide.md)

---

## 5. Deep Dive: `vlang_utils` (The 40-Module Universal Backbone)

**Primary Focus**: The universal developer utility engine powering all three desktop frameworks as well as standalone CLI and backend services.

- **40 Self-Contained Modules**: Zero external C dependencies, strict type safety, standards citations (RFCs), and comprehensive unit tests.
- **New in v2.0.0**:
  - `webutils`: Full Express-style web framework with EJS-compatible template engine, sessions, CSRF, CORS, rate limiting, and static file serving.
  - `jsonutils`: RFC 6901 JSON Pointer (`pointer_get`, `pointer_set`), RFC 7386 JSON Merge Patch, RFC 8785 canonical serialization, and structural diffing.
  - `markdownutils`: CommonMark/GFM rendering (tables, task lists, code fences), heading anchors, automated TOC, and plain-text extraction.
- **Security Hardened**: CSPRNG tokens, constant-time `secure_compare`, TOTP authentication, algorithm-pinned JWTs, tar-slip / zip-slip traversal defense, and decompression bomb limiters.

👉 **Full Dedicated Guide**: [Vlang Utils 40-Module Complete Guide](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-utils-guide.md)

---

## 6. Cross-Framework Code Rosetta Stone

### Example: Creating a Window, Labeled Input & Button

Here is how the exact same interactive user workflow is constructed across all three desktop paradigms:

#### 1. Native macOS Cocoa (`vlang_simplegui`)

```v
module main

import simplegui

fn main() {
    mut win := simplegui.new_simple_window('Cocoa Native Window', 500, 300)
    win.set_theme('Apple Dark')

    win.add_heading('User Onboarding')
    win.add_form_field('Username:', 'txt_user', 'alex')

    win.add_button('btn_submit', 'Create Account')
    win.on_click('btn_submit', fn (mut win simplegui.SimpleWindow) {
        user := win.get_text('txt_user')
        win.info('Account Created', 'Welcome to macOS, ${user}!')
    })

    win.run()
}
```

#### 2. Cross-Platform Sokol Hardware Canvas (`simple_gg`)

```v
module main

import simplegui

fn main() {
    mut win := simplegui.new_simple_window('Sokol Canvas Window', 500, 300)
    win.set_theme('Nord')

    win.add_heading('User Onboarding')
    win.add_form_field('Username:', 'txt_user', 'alex')

    win.add_button('btn_submit', '[Save] Create Account')
    win.on_click('btn_submit', fn (mut win simplegui.SimpleWindow) {
        user := win.get_text('txt_user')
        win.info('Account Created', 'Welcome to Sokol 60FPS, ${user}!')
    })

    win.run()
}
```

#### 3. Cross-Platform Webview RAD (`vlang_webview_rad_studio`)

```v
module main

import webview

fn main() {
    mut w := webview.create_window(title: 'Webview RAD Window', width: 500, height: 300)
    w.set_placement('center')

    w.bind('submitUser', fn (w &webview.Window, user string) string {
        println('Native V received username: ${user}')
        return '{"status":"ok", "message":"Welcome to Webview RAD!"}'
    })

    w.navigate('data:text/html,
    <html><body style="background:#1e1e2e;color:#cdd6f4;font-family:sans-serif;padding:24px;">
      <h2>User Onboarding</h2>
      <input id="u" value="alex" style="padding:8px;font-size:14px;" />
      <button onclick="window.submitUser(document.getElementById(\'u\').value).then(r=>alert(JSON.parse(r).message))" style="padding:8px 16px;">Create Account</button>
    </body></html>')
    w.run()
}
```

---

## 7. Enterprise Workstation Catalog Summary (115 Production Apps)

Across the three GUI repositories, the suite includes over **115 ready-to-run desktop workstation applications**:

| Workstation Tool | `vlang_simplegui` (Cocoa) | `simple_gg` (Sokol) | `vlang_webview_rad_studio` |
| :--- | :---: | :---: | :---: |
| **SQLite Studio** | ✅ (`applications/sqlite_studio.v`) | ✅ (`applications/sqlite_studio.v`) | ✅ (`applications/database_studio.v`) |
| **REST API Studio** | ✅ (`applications/api_studio.v`) | ✅ (`applications/api_studio.v`) | ✅ (`applications/api_studio.v`) |
| **Docker Studio** | ✅ (`applications/docker_studio.v`) | ✅ (`applications/docker_studio.v`) | ✅ (`applications/docker_studio.v`) |
| **Media / FFmpeg Studio**| ✅ (`applications/media_studio_hub.v`) | ✅ (`applications/media_studio_hub.v`) | ✅ (`applications/media_studio.v`) |
| **Network / Nmap Studio**| ✅ (`applications/nmap_studio.v`) | ✅ (`applications/nmap_studio.v`) | ✅ (`applications/network_studio.v`) |
| **Git Studio** | ✅ (`applications/git_studio.v`) | ✅ (`applications/git_studio.v`) | ✅ (`applications/git_studio.v`) |
| **Task / Process Manager**| ✅ (`applications/task_manager.v`) | ✅ (`applications/task_manager.v`) | ✅ (`applications/task_manager_studio.v`)|
| **App Bundler & Icon Studio**| ✅ (`applications/app_bundler_studio.v`)| ✅ (`applications/app_bundler_studio.v`)| ✅ (`applications/app_bundler_studio.v`)|
| **Markdown Studio** | ✅ (`applications/markdown_studio.v`) | ✅ (`applications/markdown_studio.v`) | ✅ (`applications/markdown_studio.v`) |
| **JSON Query Studio** | ✅ (`applications/json_studio.v`) | ✅ (`applications/jq_studio.v`) | ✅ (`applications/json_studio.v`) |
| **OmniTool DevTools** | ✅ (`applications/omnitool_studio.v`) | ✅ (`applications/omnitool_studio.v`) | ✅ (`applications/devtools_studio.v`) |

---

## 8. Quick Navigation to Dedicated Tutorials

Explore each technology stack in depth:

1. [**`vlang-utils-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-utils-guide.md) - Complete 40-module API guide covering Web, JSON, Markdown, Crypto, SQLite, Concurrency, and System utilities.
2. [**`simple-gg-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/simple-gg-guide.md) - Cross-platform Sokol `gg` guide with 87 themes, Super Controls, reactive state store, and 47 workstation apps.
3. [**`vlang-webview-rad-studio-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-webview-rad-studio-guide.md) - Webview RAD Studio visual form designer, 70+ components, 9 placement presets, and 18 studio apps.
4. [**`vlang-simplegui-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/vlang-simplegui-guide.md) - Native macOS Cocoa AppKit project guide with 135 demos, 50 workstations, and struct-driven form generation.
5. [**`simplegui-api-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/simplegui-api-guide.md) - Comprehensive API reference with 23 sections of code examples for Cocoa desktop development.
6. [**`v-programming-language-guide.md`**](file:///Users/codecaine/markdown_tutorials/tutorials/programming/vlang/v-programming-language-guide.md) - The comprehensive textbook guide covering core V language syntax, types, error handling, concurrency, and standard library.
