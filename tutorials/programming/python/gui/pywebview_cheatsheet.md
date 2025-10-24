```python
#!/usr/bin/env python3
# =============================================================================
# pywebview – Complete “All‑in‑One” Tutorial
# =============================================================================
# pywebview (https://github.com/r0x0r/pywebview) lets you create native GUI
# windows that render HTML/CSS/JS using the system’s web engine (Edge,
# WebKit, Gtk‑WebKit, WKWebView, etc.).
#
# Everything below is an executable, self‑contained example.
# Copy any block into its own *.py file, install the required packages and run.
# =============================================================================

# --------------------------------------------------------------
# 0️⃣  INSTALLATION
# --------------------------------------------------------------
#   pip install pywebview               # core library
#   # Optional GUI back‑ends (install what you need)
#   pip install pywebview[qt]           # Qt (Windows/macOS/Linux)
#   pip install pywebview[cef]          # Chromium Embedded Framework
#   pip install pywebview[gtk]          # GTK‑WebKit (Linux)
#   pip install pywebview[edge]         # Microsoft Edge (Windows 10+)
#   pip install pywebview[macos]        # WKWebView (macOS)
#
#   # Verify installation
#   python -c "import webview; print('pywebview version', webview.__version__)"

# --------------------------------------------------------------
# 1️⃣  BASIC WINDOW – “Hello World”
# --------------------------------------------------------------
"""
import webview

# A simple HTML string – you could also load a file with `webview.load_url()`
html = """<html>
<head><title>Hello</title></head>
<body style="font-family:Arial; text-align:center;">
<h1>Hello from pywebview!</h1>
</body>
</html>"""

# Create a window, `width`/`height` are optional (defaults: 800×600)
window = webview.create_window('Demo – Hello', html=html, width=400, height=300)

# Start the GUI event loop (blocks until all windows are closed)
webview.start()
"""
# --------------------------------------------------------------
# 2️⃣  LOADING EXTERNAL URL
# --------------------------------------------------------------
"""
import webview

# Load any remote page – still runs inside a native window
window = webview.create_window('Google', 'https://www.google.com')
webview.start()
"""
# --------------------------------------------------------------
# 3️⃣  MULTIPLE WINDOWS
# --------------------------------------------------------------
"""
import webview
import threading

def open_second():
    # This runs in a separate thread because `create_window` must be called
    # after the GUI loop is started (or from a callback).
    webview.create_window('Second', html='<h2>Second window</h2>')

# First (main) window
win1 = webview.create_window('Main', html='<h1>Main Window</h1>')

# Start the GUI in a background thread so we can create more windows later
threading.Thread(target=webview.start, daemon=True).start()

# Give the GUI a moment to initialise (optional)
import time; time.sleep(0.5)

# Open the second window
open_second()

# Keep the script alive while windows exist
while webview.windows:
    time.sleep(0.5)
"""
# --------------------------------------------------------------
# 4️⃣  COMMUNICATING BETWEEN PYTHON & JAVASCRIPT
# --------------------------------------------------------------
"""
import webview
import json

# --------- 1. JS → Python (expose a Python object) ----------
class Api:
    def __init__(self):
        self.counter = 0

    # This method becomes callable from JS as `window.pywebview.api.increment()`
    def increment(self):
        self.counter += 1
        return self.counter

    # Returns a JSON‑serialisable object
    def get_data(self):
        return {'counter': self.counter, 'time': str(webview.utils.now())}

api = Api()

html = """
<!DOCTYPE html>
<html>
<body>
<h2>JS ↔ Python demo</h2>
<p>Counter: <span id="cnt">0</span></p>
<button onclick="increment()">Increment (JS → Py)</button>
<button onclick="fetchData()">Get Data (JS → Py)</button>

<script>
function increment() {
    // Calls the exposed Python method, returns a Promise
    window.pywebview.api.increment().then(value => {
        document.getElementById('cnt').innerText = value;
    });
}
function fetchData() {
    window.pywebview.api.get_data().then(obj => {
        alert('Counter=' + obj.counter + '\\nTime=' + obj.time);
    });
}
</script>
</body>
</html>
"""

win = webview.create_window('JS↔Python', html=html, js_api=api)
webview.start()
"""
# --------------------------------------------------------------
# 5️⃣  PYTHON → JS (evaluating JS code)
# --------------------------------------------------------------
"""
import webview, time

def on_loaded():
    # This callback runs after the DOM is ready.
    # `evaluate_js` returns a Promise‑like object (synchronous in the
    # Python API) that resolves to the JS expression result.
    result = webview.evaluate_js('document.title')
    print('Page title reported by JS:', result)

    # Change the page title from Python
    webview.evaluate_js("document.title = 'Changed by Python'")
    # Verify change
    new_title = webview.evaluate_js('document.title')
    print('New title:', new_title)

html = "<html><head><title>Original Title</title></head><body>Hello</body></html>"
win = webview.create_window('Eval JS', html=html, on_top=True)

# Register the `on_loaded` callback: `webview.start` accepts a function that
# receives the first window as argument.
webview.start(on_loaded, win)
"""
# --------------------------------------------------------------
# 6️⃣  LOCAL FILES & ASSETS
# --------------------------------------------------------------
"""
import webview
import os, pathlib

# Suppose we have a folder `static/` with index.html, style.css, logo.png
# Provide the absolute path to the folder; pywebview will treat it as a base URL.
base_path = pathlib.Path(__file__).parent / 'static'
url = f'file://{base_path / "index.html"}'

win = webview.create_window('Local Assets', url)
webview.start()
"""
# --------------------------------------------------------------
# 7️⃣  RESIZABLE / MIN / MAX / CENTERED WINDOWS
# --------------------------------------------------------------
"""
import webview

win = webview.create_window(
    title='Advanced Window',
    html='<h1>Resizable, Min/Max, Centered</h1>',
    width=600,
    height=400,
    resizable=True,       # user can resize (default True)
    fullscreen=False,
    min_size=(300, 200),  # minimal size (width, height)
    max_size=(1024, 768), # maximal size
    frameless=False,      # hide OS window frame (platform‑specific)
    hidden=False,         # start hidden, then show later with `show()`
)

# Example: show the window after 2 seconds (demonstrates the `hidden` flag)
import threading, time
def delayed_show():
    time.sleep(2)
    win.show()
threading.Thread(target=delayed_show).start()

webview.start()
"""
# --------------------------------------------------------------
# 8️⃣  MENUS & SHORTCUTS (Windows / macOS / Linux)
# --------------------------------------------------------------
"""
import webview

def about():
    webview.create_window('About', html='<h2>pywebview demo</h2>', width=300, height=150)

def quit_app():
    webview.destroy_window()   # closes all windows & stops the loop

menu = [
    webview.MenuItem('File', [
        webview.MenuItem('About', about),
        webview.MenuItem('Quit', quit_app, shortcut='Ctrl+Q')
    ]),
    webview.MenuItem('Edit', [
        webview.MenuItem('Copy', lambda: None, shortcut='Ctrl+C')
    ])
]

win = webview.create_window('Menu Demo', html='<h1>Check the native menu</h1>', menu=menu)
webview.start()
"""
# --------------------------------------------------------------
# 9️⃣  TRANSPARENT WINDOWS (macOS / Windows 10+)
# --------------------------------------------------------------
"""
# Transparent (alpha‑blended) windows are useful for overlays.
import webview

win = webview.create_window(
    title='Transparent Demo',
    html='<body style="background:rgba(0,0,0,0.0)"><h2 style="color:white">Overlay</h2></body>',
    transparent=True,   # <‑‑ key flag
    width=400,
    height=200,
    frameless=True
)
webview.start()
"""
# --------------------------------------------------------------
# 🔟  CUSTOM USER AGENTS & HEADERS (WebView configuration)
# --------------------------------------------------------------
"""
import webview

# Some sites block the default pywebview user‑agent.
# Pass a custom UA via the `user_agent` parameter.
custom_ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' \
            'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36'

win = webview.create_window(
    'Custom UA',
    'https://httpbin.org/user-agent',
    user_agent=custom_ua
)
webview.start()
"""
# --------------------------------------------------------------
# 1️⃣1️⃣  DEBUGGING / DEVTOOLS
# --------------------------------------------------------------
"""
# Most back‑ends can open the browser’s DevTools (Chrome/Edge/Inspect).
# Set `debug=True` when creating the window (or use command line args).
import webview

win = webview.create_window('DevTools', html='<h1>Open console → F12</h1>', debug=True)
webview.start()
"""
# --------------------------------------------------------------
# 1️⃣2️⃣  CALLBACKS (on_loaded, on_closed, on_topmost_changed, …)
# --------------------------------------------------------------
"""
import webview, time

def on_loaded():
    print('✅ Window DOM is ready')
    # You can now safely evaluate JS or interact with the page.

def on_closed():
    print('❌ Window was closed')
    # Perform cleanup if necessary.

win = webview.create_window('Callbacks', html='<h1>Look at the console</h1>',
                            on_topmost_changed=lambda: print('Top‑most toggled'))

# `webview.start` can accept a function that receives the first window.
def start_gui(first_window):
    first_window.events.loaded += on_loaded
    first_window.events.closed += on_closed

webview.start(start_gui, win)
"""
# --------------------------------------------------------------
# 1️⃣3️⃣  THREADING & LONG‑RUNNING TASKS
# --------------------------------------------------------------
"""
import webview, threading, time

class Api:
    def long_task(self, seconds):
        # Simulate a CPU/IO bound task
        time.sleep(seconds)
        return f'Done after {seconds}s'

api = Api()

html = """
<!DOCTYPE html>
<html>
<body>
<h2>Long task demo</h2>
<button onclick="runTask()">Run 5‑second task</button>
<p id="out"></p>
<script>
function runTask() {
    document.getElementById('out').innerText = 'working…';
    // `pywebview.api.long_task` returns a Promise
    window.pywebview.api.long_task(5).then(r => {
        document.getElementById('out').innerText = r;
    });
}
</script>
</body>
</html>
"""

win = webview.create_window('Threaded API', html=html, js_api=api)

# Ensure pywebview runs its internal thread‑pool so the UI stays responsive.
# No extra code required – the library already dispatches exposed methods
# to a background thread.  If you need custom threads you can still use
# `threading.Thread` as shown earlier.

webview.start()
"""
# --------------------------------------------------------------
# 1️⃣4️⃣  PLATFORM‑SPECIFIC NOTES
# --------------------------------------------------------------
# • Windows:
#     - Default engine: Microsoft Edge (WebView2) – must be installed.
#       `pip install pywebview[edge]` pulls the required binaries.
#     - Alternatively, use Qt (pip install pywebview[qt]) if you need more control.
#
# • macOS:
#     - Uses WKWebView (Apple’s native engine) – no extra deps.
#     - For transparent windows you must also set `frameless=True`.
#
# • Linux:
#     - Preferred: Gtk‑WebKit (pip install pywebview[gtk]).
#     - Fallback: Qt (requires `PyQt5` or `PySide2`).
#     - Ensure `libwebkit2gtk-4.0` is installed (`sudo apt install libwebkit2gtk-4.0-dev`).
#
# • Mobile (Android/iOS):
#     - pywebview does **not** support mobile platforms.  Use the
#       `webview_flutter` (Dart) or `cefpython3` for desktop‑only apps.

# --------------------------------------------------------------
# 1️⃣5️⃣  COMMON ERRORS & HOW TO FIX THEM
# --------------------------------------------------------------
# 1️⃣  ImportError: No module named 'webview'
#    → Install the package: `pip install pywebview` (choose a backend).

# 2️⃣  “WebView2 Runtime not found” (Windows)
#    → Download and install the Edge WebView2 runtime
#       https://developer.microsoft.com/en-us/microsoft-edge/webview2/
#       or install via `pip install pywebview[edge]` which bundles the
#       evergreen installer for recent Windows builds.

# 3️⃣  “Failed to load libwebkit2gtk” (Linux)
#    → Install system packages:
#       Ubuntu/Debian: `sudo apt-get install libwebkit2gtk-4.0-dev`
#       Fedora: `sudo dnf install webkit2gtk3-devel`

# 4️⃣  JS API not reachable (`window.pywebview is undefined`)
#    → Make sure you passed the `js_api` argument to `create_window`
#       and that the page has fully loaded (use the `loaded` event).

# 5️⃣  Transparent window appears black on Linux with GTK
#    → GTK‑WebKit does not support true transparency; use the Qt backend
#       (`pip install pywebview[qt]`) instead.

# --------------------------------------------------------------
# 1️⃣6️⃣  FULL FEATURED MINIMAL APPLICATION
# --------------------------------------------------------------
"""
import webview, threading, json, pathlib

# ---------- API exposed to JavaScript ----------
class Backend:
    def __init__(self):
        self.counter = 0

    def ping(self) -> str:
        """Simple health check."""
        return 'pong'

    def add(self, a: int, b: int) -> int:
        """Return a + b."""
        return a + b

    def get_state(self) -> dict:
        """Return a JSON‑serialisable dict."""
        return {'counter': self.counter, 'time': webview.utils.now().isoformat()}

    def inc(self) -> int:
        self.counter += 1
        return self.counter

backend = Backend()

# ---------- HTML UI ----------
HTML = """
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>pywebview demo</title>
<style>
  body {font-family:Arial; padding:20px;}
  button {margin:5px;}
</style>
</head>
<body>
<h1>pywebview demo</h1>
<p>Counter: <span id="cnt">0</span></p>
<button onclick="increment()">Increment (JS→Py)</button>
<button onclick="calc()">2 + 3 (JS→Py)</button>
<button onclick="showState()">Show State (JS→Py)</button>
<script>
function increment() {
    window.pywebview.api.inc().then(v => document.getElementById('cnt').innerText = v);
}
function calc() {
    window.pywebview.api.add(2,3).then(v => alert('2+3 = '+v));
}
function showState() {
    window.pywebview.api.get_state().then(o => alert(JSON.stringify(o, null, 2)));
}
</script>
</body>
</html>
"""

# ---------- Window creation ----------
win = webview.create_window(
    title='pywebview – all features demo',
    html=HTML,
    js_api=backend,
    width=500,
    height=400,
    debug=True,          # open devtools (useful during development)
    resizable=True,
    fullscreen=False,
)

# Optional: add a native menu (Windows/macOS/Linux)
def quit_app():
    webview.destroy_window()

menu = [
    webview.MenuItem('File', [
        webview.MenuItem('Quit', quit_app, shortcut='Ctrl+Q')
    ])
]
win.menu = menu

# ---------- Start GUI ----------
webview.start()
"""
# =============================================================================
# END OF TUTORIAL – every core pywebview capability is demonstrated.
# Mix and match the blocks, add your own HTML/CSS, and ship native
# desktop apps with a single Python file!
# =============================================================================
```