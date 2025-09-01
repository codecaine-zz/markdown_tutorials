# Complete PyWebView Tutorial: Build Desktop Apps with Python + HTML/JS

Create lightweight, cross‑platform desktop apps using Python for logic and HTML/CSS/JS for UI. This guide walks you from “hello window” to multi‑window apps, Python↔JS bridging, dialogs, long tasks, and packaging.

## Table of Contents

1. Introduction and Prerequisites
2. Installation
3. Your First Window (inline HTML)
4. Loading Local Files and Assets
5. Python ↔ JavaScript Bridge (js_api)
6. Calling JS from Python (evaluate_js)
7. Dialogs: Open/Save Files and Folders
8. Long‑Running Tasks and Threads
9. Multi‑Window Apps
10. Window Options (size, fullscreen, frameless)
11. Serving a Local Web App (Flask/FastAPI) inside PyWebView
12. Packaging with PyInstaller (macOS/Linux/Windows)
13. Security Tips and Best Practices
14. Troubleshooting
15. Quick Dialog Helpers (EasyGUI/FreeSimpleGUI-style)

---

## 1) Introduction and Prerequisites

PyWebView lets you render web content in a native window and call Python from JavaScript and vice‑versa. You’ll need:

- Python 3.8+
- Basic HTML/CSS/JS familiarity
- macOS, Linux, or Windows

Optional GUI backends are selected automatically; you don’t need to install one manually for typical use.

## 2) Installation

```bash
pip install pywebview
```

Tip: Use a virtualenv for projects.

---

## 3) Your First Window (inline HTML)

Create a minimal window with embedded HTML.

Filename: basic_app.py

```python
import webview

html = """
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>PyWebView • Hello</title>
        <style>
            body { font-family: system-ui, -apple-system, Segoe UI, Arial; margin: 2rem; }
            h1 { color: #1f6feb; }
            button { padding: .6rem 1rem; border-radius: 6px; border: 1px solid #ccc; cursor: pointer; }
        </style>
    </head>
    <body>
        <h1>Hello from PyWebView</h1>
        <p>This window renders HTML, CSS and JavaScript.</p>
        <button onclick="alert('Hello from JS')">Click me</button>
    </body>
</html>
"""

if __name__ == '__main__':
        webview.create_window('Hello', html=html, width=600, height=420)
        webview.start()
```

Run: `python basic_app.py`

---

## 4) Loading Local Files and Assets

To keep HTML, CSS, and JS in files, point the window to your local `index.html`. Use the built‑in HTTP server so relative asset paths work reliably.

Project structure

```text
my_app/
├─ main.py
└─ web/
     ├─ index.html
     ├─ styles.css
     └─ app.js
```

Filename: web/index.html

```html
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>PyWebView • Local Files</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Local Files Example</h1>
        <p>Assets are loaded via the built‑in HTTP server.</p>
        <script src="app.js"></script>
    </body>
        </html>
```

Filename: main.py

```python
import os
import webview

if __name__ == '__main__':
        # Serve files from the 'web' folder using the built-in HTTP server
        # Pass a file path relative to this script or an absolute path
        index_path = os.path.join(os.path.dirname(__file__), 'web', 'index.html')
        webview.create_window('Local Files', index_path, width=900, height=650)

        # http_server=True enables a lightweight server so relative assets work
        webview.start(http_server=True)
```

Note: Without `http_server=True`, browsers may block some file:// features.

---

## 5) Python ↔ JavaScript Bridge (js_api)

Expose Python methods to JavaScript by passing `js_api` to `create_window`. In JS, call them via `window.pywebview.api.<method>` which returns a Promise.

Filename: bridge_app.py

```python
import time
import webview

class Api:
        def greet(self, name: str) -> str:
                return f"Hello, {name}!"

        def add(self, a: float, b: float) -> float:
                return float(a) + float(b)

        def slow(self, seconds: int) -> str:
                time.sleep(int(seconds))
                return f"Slept for {seconds} seconds"

html = """
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Py ↔ JS Bridge</title>
        <style>body{font-family:system-ui;margin:2rem} button{margin-right:.5rem}</style>
    </head>
    <body>
        <h1>Bridge Demo</h1>
        <input id="name" placeholder="Your name" />
        <button onclick="callGreet()">Greet</button>
        <button onclick="callAdd()">Add 2 + 3</button>
        <button onclick="callSlow()">Slow (2s)</button>
        <pre id="out"></pre>

        <script>
            const out = document.getElementById('out')
            function log(x){ out.textContent = String(x) }

            async function callGreet(){
                const name = document.getElementById('name').value || 'World'
                const msg = await window.pywebview.api.greet(name)
                log(msg)
            }

            async function callAdd(){
                const sum = await window.pywebview.api.add(2, 3)
                log(`2 + 3 = ${sum}`)
            }

            async function callSlow(){
                log('Working...')
                const msg = await window.pywebview.api.slow(2)
                log(msg)
            }
        </script>
    </body>
</html>
"""

if __name__ == '__main__':
        window = webview.create_window('Bridge', html=html, js_api=Api(), width=700, height=520)
        webview.start()
```

Notes

- Arguments and return values are JSON‑serialized.
- Use small, serializable types (numbers, strings, dicts, lists).

---

## 6) Calling JS from Python (evaluate_js)

You can execute JavaScript in the page and receive results back as Python values.

Filename: eval_js.py

```python
import webview

html = """
<!doctype html>
<html>
    <body>
        <h1>evaluate_js demo</h1>
        <p id="v"></p>
        <script>
            window.valueFromJS = 42
            function setText(t){ document.getElementById('v').textContent = t }
        </script>
    </body>
</html>
"""

def after_start(window):
        # Read a JS variable
        result = window.evaluate_js('window.valueFromJS')
        print('JS value:', result)

        # Call a JS function
        window.evaluate_js("setText('Hello from Python')")

if __name__ == '__main__':
        w = webview.create_window('Eval JS', html=html)
        # Run a callback after the window is ready
        webview.start(after_start, w)
```

---

## 7) Dialogs: Open/Save Files and Folders

Use the window’s file dialog helpers for native pickers.

Filename: dialogs.py

```python
import webview

html = """
<!doctype html>
<html>
    <body style="font-family:system-ui;margin:2rem">
        <h1>Dialogs</h1>
        <button onclick="pickOpen()">Open file</button>
        <button onclick="pickSave()">Save as</button>
        <button onclick="pickFolder()">Choose folder</button>
        <pre id='out'></pre>
        <script>
            const out = document.getElementById('out')
            function show(x){ out.textContent = JSON.stringify(x, null, 2) }
            async function pickOpen(){ const r = await window.pywebview.api.open(); show(r) }
            async function pickSave(){ const r = await window.pywebview.api.save(); show(r) }
            async function pickFolder(){ const r = await window.pywebview.api.folder(); show(r) }
        </script>
    </body>
</html>
"""

class Api:
        def __init__(self, window):
                self.window = window

        def open(self):
                return self.window.create_file_dialog(webview.OPEN_DIALOG, allow_multiple=False)

        def save(self):
                return self.window.create_file_dialog(webview.SAVE_DIALOG, save_filename='untitled.txt')

        def folder(self):
                return self.window.create_file_dialog(webview.FOLDER_DIALOG)

if __name__ == '__main__':
        win = webview.create_window('Dialogs', html=html)
        win.expose(Api(win))  # Alternative to passing js_api in create_window
        webview.start()
```

Notes

- The return value is a list of selected paths or None if cancelled.
- `expose(obj)` can be used after window creation to add JS API.

---

## 8) Long‑Running Tasks and Threads

Don’t block the UI. Run heavy work in a thread/process and notify the UI via `evaluate_js` or API callbacks.

Filename: long_task.py

```python
import threading
import time
import webview

html = """
<!doctype html>
<html>
    <body style="font-family:system-ui;margin:2rem">
        <h1>Long Task</h1>
        <button onclick="start()">Start 5s job</button>
        <div id="status">Idle</div>
        <script>
            function setStatus(t){ document.getElementById('status').textContent = t }
        </script>
    </body>
</html>
"""

def run_job(window):
        window.evaluate_js("setStatus('Working...')")
        time.sleep(5)
        window.evaluate_js("setStatus('Done!')")

class Api:
        def __init__(self, window):
                self.window = window
        def start(self):
                threading.Thread(target=run_job, args=(self.window,), daemon=True).start()
                return 'started'

if __name__ == '__main__':
        win = webview.create_window('Long Task', html=html, js_api=Api(None))
        # fix circular: set window on API after creation
        win.js_api.window = win
        webview.start()
```

---

## 9) Multi‑Window Apps

Create multiple windows and communicate between them.

Filename: multi_window.py

```python
import webview

html = """
<!doctype html>
<html><body style="font-family:system-ui;margin:1.5rem">
    <h2 id='t'></h2>
    <script>function setTitle(t){ document.getElementById('t').textContent = t }</script>
</body></html>
"""

def after_start(main_win, child_win):
        main_win.evaluate_js("setTitle('Main Window')")
        child_win.evaluate_js("setTitle('Child Window')")

if __name__ == '__main__':
        main_win = webview.create_window('Main', html=html, width=500, height=360)
        child_win = webview.create_window('Child', html=html, width=400, height=300)
        webview.start(after_start, (main_win, child_win))
```

Access all windows via `webview.windows`.

---

## 10) Window Options (size, fullscreen, frameless)

Common `create_window` options you may use:

- `width`, `height`, `min_size=(w, h)`, `resizable=True|False`
- `fullscreen=True`
- `on_top=True` (always on top)
- `frameless=True`, `easy_drag=True` (custom title bars)
- `background_color="#ffffff"`, `transparent=False`
- `confirm_close=True` (ask before closing)

Example

```python
import webview

html = '<!doctype html><html><body style="margin:0">\n' \
             '<div style="background:#1f6feb;color:#fff;padding:10px; -webkit-app-region: drag;">Custom Title</div>' \
             '<div style="padding:20px">Content</div></body></html>'

if __name__ == '__main__':
        webview.create_window(
                'Frameless', html=html,
                width=800, height=500,
                frameless=True, easy_drag=True, on_top=False,
        )
        webview.start()
```

---

## 11) Serving a Local Web App (Flask/FastAPI) inside PyWebView

You can host a local server and point PyWebView to `http://127.0.0.1:port`.

Filename: flask_in_webview.py

```python
import threading
import webview
from flask import Flask

app = Flask(__name__)

@app.route('/')
def index():
        return '<h1 style="font-family:system-ui">Flask served in PyWebView</h1>'

def run_server():
        app.run(port=5001, debug=False, use_reloader=False)

if __name__ == '__main__':
        threading.Thread(target=run_server, daemon=True).start()
        webview.create_window('Flask App', 'http://127.0.0.1:5001', width=900, height=650)
        webview.start()
```

This approach is helpful when reusing an existing web app.

---

## 12) Packaging with PyInstaller (macOS/Linux/Windows)

Add your web assets, set windowed mode, and build a distributable app.

Example project

```text
my_app/
├─ main.py
└─ web/               # Static UI assets
     ├─ index.html
     ├─ styles.css
     └─ app.js
```

PyInstaller (macOS/Linux shell)

```bash
python -m pip install pyinstaller
pyinstaller --noconfirm \
    --windowed \
    --name "MyApp" \
    --add-data "web:web" \
    main.py
```

Notes

- `--windowed` hides the console window.
- `--add-data "web:web"` bundles the `web/` folder; use `;` on Windows: `web;web`.
- On macOS, the app appears under `dist/MyApp.app`.

---

## 13) Security Tips and Best Practices

- Prefer `http_server=True` and local files over loading remote sites.
- Expose only the minimal `js_api` surface; validate inputs in Python.
- Avoid `eval`/`Function` in your JS.
- If loading remote URLs, consider a simple navigation guard (e.g., check URLs before allowing redirects) and use HTTPS only.
- Disable debug tooling in production (`webview.start(debug=False)`).

---

## 14) Troubleshooting

- Window is blank when opening local files: start with `http_server=True` so relative assets work.
- JS can’t find `window.pywebview`: ensure you passed `js_api` or called `window.expose(api_instance)` before using it.
- Long tasks freeze UI: run work in threads or processes; never `sleep` on the main thread.
- File dialogs return `None`: user cancelled the dialog; handle that case.

---

You now have a solid foundation to build full desktop apps with Python logic and a modern web UI. Adapt the examples to your app structure and ship it with PyInstaller.

---

## 15) Quick Dialog Helpers (EasyGUI/FreeSimpleGUI-style)

If you like the simplicity of EasyGUI/FreeSimpleGUI, you can build a tiny helper that opens small modal PyWebView windows for common dialogs. This keeps everything pure-Python with no extra dependencies and works cross‑platform.

Filename: quick_dialogs.py

```python
from __future__ import annotations
from typing import List, Optional, Sequence, Union
import webview


class QuickDialogs:
    """
    EasyGUI/FreeSimpleGUI-style dialogs implemented with small PyWebView windows.

    Synchronous usage: each method creates a temporary window, blocks until the
    user responds (webview.start returns), then returns a Python value.
    """

    # ---------- Low-level helper ----------
    class _Result:
        def __init__(self):
            self.value = None
            self.window: Optional[webview.Window] = None

        # Exposed to JS as window.pywebview.api.submit(value)
        def submit(self, value):  # value is JSON-serializable
            self.value = value
            # Close the window as soon as we have a result
            if self.window is not None:
                self.window.destroy()
            return True

    @staticmethod
    def _run_modal(html: str, *, width=420, height=260, title="", background="#ffffff"):
        api = QuickDialogs._Result()
        html_doc = f"""
        <!doctype html>
        <html>
            <head>
                <meta charset=\"utf-8\" />
                <title>{title or 'Dialog'}</title>
                <style>
                    :root {{ color-scheme: light dark; }}
                    body {{
                        margin: 0; padding: 16px; font-family: system-ui, -apple-system, Segoe UI, Arial;
                        background: {background};
                    }}
                    .msg {{ white-space: pre-wrap; line-height: 1.35; margin-bottom: 1rem; }}
                    .row {{ display: flex; gap: .5rem; margin-top: 1rem; }}
                    button {{ padding: .5rem .9rem; border-radius: 6px; border: 1px solid #ccc; cursor: pointer; }}
                    input, select, textarea {{ width: 100%; padding: .5rem .6rem; border: 1px solid #ccc; border-radius: 6px; }}
                    .right {{ justify-content: flex-end; }}
                    .list {{ width: 100%; min-height: 8rem; }}
                </style>
            </head>
            <body>
                {html}
                <script>
                    // Helpers available to snippets
                    function _submit(v) {{ return window.pywebview.api.submit(v); }}
                    function _cancel() {{ return window.pywebview.api.submit(null); }}
                </script>
            </body>
        </html>
        """
        win = webview.create_window(title or "Dialog", html=html_doc, width=width, height=height, resizable=False)
        win.events.shown += lambda: win.activate()  # focus when shown
        win.expose(api)
        api.window = win
        webview.start()
        return api.value

    # ---------- Simple dialogs ----------
    @staticmethod
    def alert(message: str, *, title: str = "Message", ok_text: str = "OK") -> None:
        QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div class=\"row right\">
                <button onclick=\"_submit(true)\">{ok_text}</button>
            </div>
            """,
            width=420, height=180, title=title,
        )

    @staticmethod
    def confirm(message: str, *, title: str = "Confirm", ok_text: str = "OK", cancel_text: str = "Cancel") -> bool:
        res = QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(true)\">{ok_text}</button>
            </div>
            """,
            width=440, height=190, title=title,
        )
        return bool(res)

    @staticmethod
    def prompt(message: str, *, title: str = "Input", default: str = "", password: bool = False, ok_text="OK", cancel_text="Cancel") -> Optional[str]:
        input_type = "password" if password else "text"
        res = QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <input id=\"inp\" type=\"{input_type}\" value=\"{default}\" onkeydown=\"if(event.key==='Enter'){_submit(document.getElementById('inp').value)}\" />
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('inp').value)\">{ok_text}</button>
            </div>
            <script>document.getElementById('inp').focus()</script>
            """,
            width=460, height=210, title=title,
        )
        return None if res is None else str(res)

    @staticmethod
    def select(message: str, choices: Sequence[str], *, title: str = "Select", multiselect: bool = False, ok_text="OK", cancel_text="Cancel") -> Optional[Union[str, List[str]]]:
        if not choices:
            return None
        options = "\n".join(f"<option value=\"{c}\">{c}</option>" for c in choices)
        multiple_attr = " multiple size=8" if multiselect else " size=8"
        js_value = (
            "Array.from(document.getElementById('sel').selectedOptions).map(o=>o.value)"
            if multiselect else
            "(document.getElementById('sel').value)"
        )
        res = QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <select id=\"sel\" class=\"list\"{multiple_attr}>
                {options}
            </select>
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit({js_value})\">{ok_text}</button>
            </div>
            <script>document.getElementById('sel').focus()</script>
            """,
            width=520, height=360, title=title,
        )
        if res is None:
            return None
        return res if multiselect else str(res)

    # ---------- Native file/folder pickers ----------
    @staticmethod
    def open_file(*, allow_multiple: bool = False, file_types: Optional[Sequence[str]] = None, title: str = "Open File") -> Optional[Union[str, List[str]]]:
        result = {"value": None}

        def _after_start(win: webview.Window):
            patterns = None
            if file_types:
                # pywebview expects list of (description, patterns) or patterns list; keep simple with patterns
                patterns = list(file_types)
            paths = win.create_file_dialog(webview.OPEN_DIALOG, allow_multiple=allow_multiple, file_types=patterns)
            result["value"] = paths
            win.destroy()

        win = webview.create_window(title, html="<html><body></body></html>", width=10, height=10, resizable=False)
        webview.start(_after_start, win)
        if result["value"] is None:
            return None
        if allow_multiple:
            return result["value"] or []
        return result["value"][0] if result["value"] else None

    @staticmethod
    def save_file(*, default_name: str = "untitled.txt", title: str = "Save As") -> Optional[str]:
        result = {"value": None}

        def _after_start(win: webview.Window):
            path = win.create_file_dialog(webview.SAVE_DIALOG, save_filename=default_name)
            result["value"] = path
            win.destroy()

        win = webview.create_window(title, html="<html><body></body></html>", width=10, height=10, resizable=False)
        webview.start(_after_start, win)
        return result["value"][0] if result["value"] else None

    @staticmethod
    def choose_folder(*, title: str = "Choose Folder") -> Optional[str]:
        result = {"value": None}

        def _after_start(win: webview.Window):
            path = win.create_file_dialog(webview.FOLDER_DIALOG)
            result["value"] = path
            win.destroy()

        win = webview.create_window(title, html="<html><body></body></html>", width=10, height=10, resizable=False)
        webview.start(_after_start, win)
        return result["value"][0] if result["value"] else None


if __name__ == "__main__":
    # Quick demo when running this file directly
    QuickDialogs.alert("Hello from QuickDialogs")
    ok = QuickDialogs.confirm("Proceed?")
    print("confirm:", ok)
    name = QuickDialogs.prompt("Your name:", default="World")
    print("prompt:", name)
    fruit = QuickDialogs.select("Pick one:", ["Apple", "Banana", "Cherry"])
    print("select:", fruit)
    # files = QuickDialogs.open_file(allow_multiple=True, file_types=["*.txt", "*.md"])  # Uncomment to test
    # print("open:", files)
```

Minimal usage

Filename: sample_quick_dialogs.py

```python
from quick_dialogs import QuickDialogs as Q

Q.alert("Welcome!")
if Q.confirm("Would you like to enter your name?"):
    name = Q.prompt("Your name:", default="World")
    if name:
        Q.alert(f"Hello, {name}")

choice = Q.select("Choose a language:", ["Python", "JavaScript", "Go", "Rust"]) or "Python"
print("You chose:", choice)

path = Q.save_file(default_name="notes.txt")
print("Save path:", path)
```

Notes

- Each function is blocking and returns immediately after the temporary window closes.
- For production, consider centralizing styles or localizing button labels.
- You can extend this pattern to build multi-field forms quickly (e.g., labels + inputs in the HTML and submit a dict).

### More helper dialogs most users and developers want

Below is an extension that adds frequently used dialogs: date/time pickers, color picker, multiline text, credentials, checklist/radiolist, error dialog with expandable details, yes/no/cancel, and a cancellable progress runner.

Filename: extended_quick_dialogs.py

```python
from __future__ import annotations
from typing import List, Optional, Sequence, Tuple, Union, Callable, Any, Dict
import threading
import webview

# Reuse the base helpers from section 15
from quick_dialogs import QuickDialogs


class ExtendedDialogs(QuickDialogs):
    """
    Adds common dialogs on top of QuickDialogs.
    All simple dialogs are implemented via QuickDialogs._run_modal.
    """

    # ---- Inputs and pickers ----
    @staticmethod
    def date(message: str, *, title: str = "Choose Date", default: Optional[str] = None,
             min_date: Optional[str] = None, max_date: Optional[str] = None,
             ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        # Dates formatted as YYYY-MM-DD per <input type=date>
        attrs = []
        if default: attrs.append(f"value=\"{default}\"")
        if min_date: attrs.append(f"min=\"{min_date}\"")
        if max_date: attrs.append(f"max=\"{max_date}\"")
        attr_str = " ".join(attrs)
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <input id=\"d\" type=\"date\" {attr_str} />
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('d').value)\">{ok_text}</button>
            </div>
            <script>document.getElementById('d').focus()</script>
            """,
            width=420, height=210, title=title,
        ) or None

    @staticmethod
    def time(message: str, *, title: str = "Choose Time", default: Optional[str] = None,
             ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        # Time format HH:MM per <input type=time>
        attr = f"value=\"{default}\"" if default else ""
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <input id=\"t\" type=\"time\" {attr} />
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('t').value)\">{ok_text}</button>
            </div>
            <script>document.getElementById('t').focus()</script>
            """,
            width=420, height=210, title=title,
        ) or None

    @staticmethod
    def datetime(message: str, *, title: str = "Choose Date & Time", default: Optional[str] = None,
                 ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        # Format per <input type=datetime-local>, e.g. 2025-09-01T12:30
        attr = f"value=\"{default}\"" if default else ""
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <input id=\"dt\" type=\"datetime-local\" {attr} />
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('dt').value)\">{ok_text}</button>
            </div>
            <script>document.getElementById('dt').focus()</script>
            """,
            width=460, height=220, title=title,
        ) or None

    @staticmethod
    def color(message: str, *, title: str = "Pick a Color", default: str = "#1f6feb",
              ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <input id=\"c\" type=\"color\" value=\"{default}\" style=\"width:4rem;height:2.2rem;padding:0;border:none;background:transparent\" />
            <div class=\"row right\" style=\"margin-top:1rem\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('c').value)\">{ok_text}</button>
            </div>
            """,
            width=360, height=180, title=title,
        ) or None

    @staticmethod
    def multiline(message: str, *, title: str = "Input", default: str = "",
                  rows: int = 8, ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <textarea id=\"txt\" rows=\"{rows}\">{default}</textarea>
            <div class=\"row right\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(document.getElementById('txt').value)\">{ok_text}</button>
            </div>
            <script>document.getElementById('txt').focus()</script>
            """,
            width=560, height=360, title=title,
        ) or None

    @staticmethod
    def credentials(message: str = "Sign in", *, title: str = "Credentials", username: str = "",
                    ok_text: str = "Sign in", cancel_text: str = "Cancel") -> Optional[Tuple[str, str]]:
        res = QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div class=\"row\" style=\"flex-direction:column;gap:.5rem\">
                <input id=\"u\" type=\"text\" placeholder=\"Username\" value=\"{username}\" />
                <input id=\"p\" type=\"password\" placeholder=\"Password\" />
            </div>
            <div class=\"row right\" style=\"margin-top:1rem\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit([document.getElementById('u').value, document.getElementById('p').value])\">{ok_text}</button>
            </div>
            <script>document.getElementById('u').focus()</script>
            """,
            width=460, height=250, title=title,
        )
        return None if res is None else (res[0], res[1])

    @staticmethod
    def checklist(message: str, choices: Sequence[str], *, title: str = "Choose", checked: Sequence[str] = (),
                  ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[List[str]]:
        items = "\n".join(
            f"<label><input type=\"checkbox\" value=\"{c}\" {'checked' if c in checked else ''}/> {c}</label>"
            for c in choices
        )
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div id=\"wrap\" style=\"display:flex;flex-direction:column;gap:.4rem;max-height:260px;overflow:auto\">{items}</div>
            <div class=\"row right\" style=\"margin-top:1rem\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit(Array.from(document.querySelectorAll('#wrap input:checked')).map(i=>i.value))\">{ok_text}</button>
            </div>
            """,
            width=520, height=380, title=title,
        ) or None

    @staticmethod
    def radiolist(message: str, choices: Sequence[str], *, title: str = "Select One",
                  selected: Optional[str] = None, ok_text: str = "OK", cancel_text: str = "Cancel") -> Optional[str]:
        items = "\n".join(
            f"<label><input type=\"radio\" name=\"r\" value=\"{c}\" {'checked' if c==selected else ''}/> {c}</label>"
            for c in choices
        )
        return QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div id=\"wrap\" style=\"display:flex;flex-direction:column;gap:.4rem;max-height:260px;overflow:auto\">{items}</div>
            <div class=\"row right\" style=\"margin-top:1rem\">
                <button onclick=\"_cancel()\">{cancel_text}</button>
                <button onclick=\"_submit((document.querySelector('input[name=\\'r\\']:checked')||{{value:null}}).value)\">{ok_text}</button>
            </div>
            """,
            width=520, height=340, title=title,
        ) or None

    @staticmethod
    def error(message: str, *, title: str = "Error", details: Optional[str] = None,
              ok_text: str = "Close") -> None:
        details_html = f"<details style=\"margin-top: .6rem\"><summary>Details</summary><pre style=\"white-space:pre-wrap\">{details}</pre></details>" if details else ""
        QuickDialogs._run_modal(
            f"""
            <div class=\"msg\" style=\"color:#b00020\"><strong>⚠️ {message}</strong></div>
            {details_html}
            <div class=\"row right\">
                <button onclick=\"_submit(true)\">{ok_text}</button>
            </div>
            """,
            width=560, height=260 if details else 180, title=title,
        )

    @staticmethod
    def yes_no_cancel(message: str, *, title: str = "Confirm", yes_text: str = "Yes",
                      no_text: str = "No", cancel_text: str = "Cancel") -> Optional[str]:
        res = QuickDialogs._run_modal(
            f"""
            <div class=\"msg\">{message}</div>
            <div class=\"row right\">
                <button onclick=\"_submit('cancel')\">{cancel_text}</button>
                <button onclick=\"_submit('no')\">{no_text}</button>
                <button onclick=\"_submit('yes')\">{yes_text}</button>
            </div>
            """,
            width=460, height=190, title=title,
        )
        return None if res is None else str(res)

    # ---- Cancellable progress runner ----
    class _ProgressHandle:
        def __init__(self, window: webview.Window, state: Dict[str, Any]):
            self._window = window
            self._state = state
        @property
        def cancelled(self) -> bool:
            return bool(self._state.get('cancelled'))
        def set_percent(self, pct: float):
            pct = max(0, min(100, float(pct)))
            try:
                self._window.evaluate_js(f"setProgress({pct})")
            except Exception:
                pass
        def set_message(self, text: str):
            try:
                self._window.evaluate_js(f"setMessage({repr(text)})")
            except Exception:
                pass

    @staticmethod
    def run_with_progress(task: Callable[["ExtendedDialogs._ProgressHandle"], Any], *,
                          title: str = "Working",
                          message: str = "Please wait…",
                          cancellable: bool = True) -> Dict[str, Any]:
        """
        Runs `task(handle)` in a background thread while showing a modal with a progress bar.
        The task may call handle.set_percent(x), handle.set_message(text), and check handle.cancelled.
        Returns { 'result': Any, 'cancelled': bool, 'error': Optional[str] }.
        """
        state: Dict[str, Any] = {"cancelled": False, "done": False, "result": None, "error": None}

    html = f"""
        <div class=\"msg\" id=\"msg\">{message}</div>
        <div style=\"display:flex;align-items:center;gap:.6rem;margin-top:.4rem\">
            <progress id=\"bar\" value=\"0\" max=\"100\" style=\"width:100%\"></progress>
            <span id=\"pct\">0%</span>
        </div>
    <div class=\"row right\" style=\"margin-top:1rem\">{"<button onclick=\\"window.pywebview.api.cancel()\\">Cancel</button>" if cancellable else ""}</div>
        <script>
            function setProgress(p){{
                const b = document.getElementById('bar');
                const s = document.getElementById('pct');
                b.value = p; s.textContent = Math.round(p)+'%';
            }}
            function setMessage(t){{ document.getElementById('msg').textContent = t }}
        </script>
        """

        class _Api:
            def __init__(self):
                self.window: Optional[webview.Window] = None
            def cancel(self):
                state["cancelled"] = True
                return True

        api = _Api()
        win = webview.create_window(title, html=ExtendedDialogs._wrap_html(html, title=title), width=520, height=220, resizable=False)
        api.window = win

        # Expose functions expected by the template
        def _submit(_):
            # Not used here; keep for API symmetry
            pass

        # We need a thin wrapper to reuse the base template
        win.expose(type("_Expose", (), {"submit": lambda self, v: True, "cancel": api.cancel})())

        def _worker():
            handle = ExtendedDialogs._ProgressHandle(win, state)
            try:
                res = task(handle)
                state["result"] = res
            except Exception as e:
                state["error"] = str(e)
            finally:
                state["done"] = True
                try:
                    win.destroy()
                except Exception:
                    pass

        t = threading.Thread(target=_worker, daemon=True)

        # Run the window and start the worker after it's shown
        def _after_start(w: webview.Window):
            t.start()

        webview.start(_after_start, win)
        return {"result": state["result"], "cancelled": bool(state["cancelled"]), "error": state["error"]}

    # Helper to reuse QuickDialogs CSS/shell and insert custom body
    @staticmethod
    def _wrap_html(inner_html: str, *, title: str = "Dialog", background: str = "#ffffff") -> str:
        return f"""
        <!doctype html>
        <html>
            <head>
                <meta charset=\"utf-8\" />
                <title>{title}</title>
                <style>
                    :root {{ color-scheme: light dark; }}
                    body {{
                        margin: 0; padding: 16px; font-family: system-ui, -apple-system, Segoe UI, Arial;
                        background: {background};
                    }}
                    .msg {{ white-space: pre-wrap; line-height: 1.35; margin-bottom: .6rem; }}
                    .row {{ display: flex; gap: .5rem; margin-top: .6rem; }}
                    button {{ padding: .5rem .9rem; border-radius: 6px; border: 1px solid #ccc; cursor: pointer; }}
                    input, select, textarea {{ width: 100%; padding: .5rem .6rem; border: 1px solid #ccc; border-radius: 6px; }}
                </style>
            </head>
            <body>
                {inner_html}
            </body>
        </html>
        """

```

Minimal usage

Filename: sample_extended_dialogs.py

```python
from extended_quick_dialogs import ExtendedDialogs as X

# Date / time / color
print("date:", X.date("Pick a date:"))
print("time:", X.time("Pick a time:"))
print("datetime:", X.datetime("Pick date and time:"))
print("color:", X.color("Pick a brand color:", default="#ff5722"))

# Multiline and credentials
bio = X.multiline("Tell us about yourself:")
creds = X.credentials("Sign in to continue", username="user@example.com")
print("bio:", bio[:20] + "…" if bio else None)
print("creds:", creds)

# Checklist / radiolist / yes-no-cancel
features = X.checklist("Enable features:", ["Sync", "Analytics", "Beta updates"], checked=["Sync"])
lang = X.radiolist("Default language:", ["English", "Spanish", "French"], selected="English")
answer = X.yes_no_cancel("Save changes before exiting?")
print(features, lang, answer)

# Error with details
X.error("Failed to save file", details="Traceback (most recent call last):\n ... ValueError: invalid path")

# Cancellable progress runner
def long_task(h):
    # Simulate work that checks for cancellation
    import time
    for i in range(101):
        if h.cancelled:
            return None
        h.set_percent(i)
        if i % 10 == 0:
            h.set_message(f"Processed {i}%…")
        time.sleep(0.03)
    return "done"

result = X.run_with_progress(long_task, title="Exporting", message="Preparing export…", cancellable=True)
print(result)
```

Notes

- The progress runner returns a dict with result, cancelled, and error. Your task function should periodically check `handle.cancelled` and return early if set.
- Date/time pickers follow the browser’s native widgets; values are strings you can parse with `datetime` in Python.
- For non-blocking toast notifications inside an existing PyWebView app, prefer creating a frameless, always-on-top window from within your app’s event loop.
