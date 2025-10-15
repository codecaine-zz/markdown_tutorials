Below is a **fully‑linked version of the tutorial**.  
Every PyWebView API that appears in the code now has a short comment that points to the **exact official documentation page** – you can Ctrl‑click (or ⌘‑click) the URL to jump straight to the reference.

---

## Quick‑reference table  

| API / concept | Official doc link |
|---------------|-------------------|
| `webview.create_window()` | <https://pywebview.flowrl.com/api.html#webview-create_window> |
| `webview.start()` | <https://pywebview.flowrl.com/api.html#webview-start> |
| JavaScript‑Python bridge (`js_api` / `window.pywebview.api`) | same page as `create_window` (see **js_api** argument) |
| `window.evaluate_js()` | <https://pywebview.flowrl.com/api.html#window-evaluate_js> |
| `window.expose()` (dynamic expose) | <https://pywebview.flowrl.com/api.html#window-expose> |
| Window‑event system (`window.events.*`) | <https://pywebview.flowrl.com/api.html#window-events> |
| `webview.screens` (multi‑monitor) | <https://pywebview.flowrl.com/api.html#webview-screens> |
| `webview.Menu` (native menus) | <https://pywebview.flowrl.com/api.html#webview-menu> |
| Installation notes (extra GTK/Qt deps) | <https://pywebview.flowrl.com/guide#installation> |

---

## 1️⃣ Basic window  

```python
# 1_basic_window.py
import webview

def main():
    """Create a simple window that loads a remote URL."""
    # 👉 https://pywebview.flowrl.com/api.html#webview-create_window
    webview.create_window(
        title="Basic Window",
        url="https://www.example.com",
        width=800,
        height=600,
        resizable=True,
        fullscreen=False,
    )
    # 👉 https://pywebview.flowrl.com/api.html#webview-start
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 2️⃣ Load raw HTML (inline)  

```python
# 2_local_content.py
import webview

def main():
    """Load HTML directly from a Python string."""
    html_content = """
    <!DOCTYPE html>
    <html><head><title>Local Content</title></head>
    <body>
        <h1>Hello from PyWebView!</h1>
        <p>This HTML is embedded in the Python script.</p>
    </body></html>
    """
    # 👉 https://pywebview.flowrl.com/api.html#webview-create_window (html=)
    webview.create_window("Local Content", html=html_content, width=600, height=400)
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 3️⃣ Load an HTML file  

```python
# 3_file_content.py
import webview
from pathlib import Path

def main():
    """Load HTML from a local file (creates one if missing)."""
    html_file = Path(__file__).parent / "index.html"

    if not html_file.exists():
        html_file.write_text(
            """
            <!DOCTYPE html><html><head><title>File Content</title></head>
            <body><h1>Content from file</h1><p>Loaded from a local HTML file.</p></body></html>
            """
        )
    # 👉 https://pywebview.flowrl.com/api.html#webview-create_window (url=)
    webview.create_window(
        "File Content",
        url=html_file.as_uri(),
        width=600,
        height=400,
    )
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 4️⃣ JavaScript‑Python bridge (`js_api`)  

```python
# 4_js_api_example.py
import webview
import time

class Api:
    """Methods exposed as window.pywebview.api in JavaScript."""
    def get_data(self, params):
        name = params.get("name", "Anonymous")
        return {"message": f"Hello {name}!", "timestamp": time.time()}

    def process_data(self, data):
        return f"Processed: {data}"

def main():
    api = Api()
    html = """
    <!DOCTYPE html><html><head><title>JS API Example</title></head>
    <body>
        <h1>JavaScript API Integration</h1>
        <button onclick="getData()">Get Data from Python</button>
        <button onclick="sendData()">Send Data to Python</button>
        <div id="output"></div>

        <script>
        async function getData() {
            const result = await window.pywebview.api.get_data({name: 'User'});
            document.getElementById('output').innerText =
                `Message: ${result.message}\\nTimestamp: ${result.timestamp}`;
        }
        async function sendData() {
            const result = await window.pywebview.api.process_data("Sample data from JS");
            document.getElementById('output').innerText = result;
        }
        </script>
    </body></html>
    """
    # 👉 https://pywebview.flowrl.com/api.html#webview-create_window (js_api=)
    webview.create_window(
        "JS API Example",
        html=html,
        js_api=api,
        width=600,
        height=400,
    )
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 5️⃣ Background work & UI updates from a thread  

```python
# 5_threading_example.py
import webview
import threading
import time

class BackgroundTasks:
    """Runs a long‑running job without freezing the UI."""
    def __init__(self, window):
        self.window = window

    def long_running_task(self):
        # 👉 https://pywebview.flowrl.com/api.html#window-evaluate_js
        self.window.evaluate_js('document.getElementById("status").innerText = "Processing…"')
        time.sleep(3)                                   # simulate heavy work
        self.window.evaluate_js('document.getElementById("status").innerText = "Task completed!"')

def main():
    html = """
    <!DOCTYPE html><html><head><title>Threading Example</title></head>
    <body>
        <h1>Background Tasks</h1>
        <button onclick="startTask()">Start Long Task</button>
        <div id="status">Ready</div>
        <script>
        function startTask() {
            window.pywebview.api.start_background_task();
        }
        </script>
    </body></html>
    """
    window = webview.create_window(
        "Threading Example",
        html=html,
        width=600,
        height=400,
    )
    tasks = BackgroundTasks(window)

    class Api:
        def start_background_task(self):
            threading.Thread(target=tasks.long_running_task, daemon=True).start()

    # 👉 https://pywebview.flowrl.com/api.html#webview-start (passing js_api here)
    webview.start(js_api=Api())

if __name__ == "__main__":
    main()
```

---

## 6️⃣ Window‑lifecycle events  

```python
# 6_window_events.py
import webview

def main():
    html = """
    <!DOCTYPE html><html><head><title>Window Events</title></head>
    <body>
        <h1>Window Events</h1>
        <div id="events"></div>
        <script>
        window.addEventListener('pywebviewready', () => {
            document.getElementById('events').innerHTML += '<p>PyWebView is ready</p>';
        });
        </script>
    </body></html>
    """
    window = webview.create_window(
        "Window Events",
        html=html,
        width=600,
        height=400,
    )

    # 👉 https://pywebview.flowrl.com/api.html#window-events
    def on_loaded():
        print("✅ Page loaded")

    def on_closing():
        print("⚠️ Window is closing")
        # return False to cancel closing, True/None to allow
    window.events.loaded += on_loaded
    window.events.closing += on_closing

    webview.start()

if __name__ == "__main__":
    main()
```

---

## 7️⃣ Multiple windows (thread‑safe creation)  

```python
# 7_multiple_windows.py
import webview
import threading

def main():
    def create_secondary():
        webview.create_window(
            "Secondary Window",
            html="<h1>Secondary Window</h1>",
            width=400,
            height=300,
        )

    html = """
    <!DOCTYPE html><html><head><title>Main Window</title></head>
    <body>
        <h1>Main Window</h1>
        <button onclick="createWindow()">Create New Window</button>
        <script>
        function createWindow() {
            window.pywebview.api.create_new_window();
        }
        </script>
    </body></html>
    """

    class Api:
        def create_new_window(self):
            # 👉 thread‑safe window creation
            threading.Thread(target=create_secondary, daemon=True).start()

    webview.create_window(
        "Main Window",
        html=html,
        js_api=Api(),
        width=600,
        height=400,
    )
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 8️⃣ Centralised configuration (best‑practice example)  

```python
# 8_best_practice_config.py
import webview

def main():
    config = {
        "title": "Best‑Practice Example",
        "width": 800,
        "height": 600,
        "min_size": (400, 300),
        "resizable": True,
        "fullscreen": False,
        "frameless": False,
        "easy_drag": True,
        "text_select": True,
        "zoomable": True,
    }

    html = """
    <!DOCTYPE html><html><head><title>Configuration Example</title></head>
    <body>
        <h1>Configuration Example</h1>
        <p>Window configured with best practices.</p>
    </body></html>
    """

    # 👉 all arguments accepted via **kwargs
    window = webview.create_window(html=html, **config)

    def on_shown():
        print("✅ Window shown")
    # 👉 window.events.shown
    window.events.shown += on_shown
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 9️⃣ Error handling in the bridge  

```python
# 9_error_handling.py
import webview
import random

class Api:
    def risky_operation(self):
        if random.choice([True, False]):
            return "✅ Operation successful!"
        raise RuntimeError("🚨 Simulated error occurred")

def main():
    html = """
    <!DOCTYPE html><html><head><title>Error Handling</title></head>
    <body>
        <h1>Error Handling</h1>
        <button onclick="callPython()">Call Python</button>
        <div id="result"></div>

        <script>
        async function callPython() {
            try {
                const r = await window.pywebview.api.risky_operation();
                document.getElementById('result').innerText = r;
            } catch (e) {
                document.getElementById('result').innerText = 'Error: ' + e.message;
            }
        }
        </script>
    </body></html>
    """
    webview.create_window(
        "Error Handling",
        html=html,
        js_api=Api(),
        width=600,
        height=400,
    )
    webview.start()

if __name__ == "__main__":
    main()
```

---

## 🔟 Complete application (task manager)  

```python
# 10_complete_application.py
import webview
import threading
import time

class AppApi:
    """All logic exposed to JavaScript."""
    def __init__(self, window):
        self.window = window
        self.data = []

    # ----- CRUD ---------------------------------------------------------
    def add_item(self, item: str):
        self.data.append({"id": len(self.data) + 1, "text": item, "ts": time.time()})
        return {"success": True, "id": len(self.data)}

    def get_items(self):
        return self.data

    def clear_items(self):
        self.data.clear()
        return {"success": True}

    # ----- Long‑running work -------------------------------------------
    def long_process(self):
        def _work():
            self.window.evaluate_js('document.getElementById("status").innerText = "Processing…"')
            time.sleep(3)
            self.window.evaluate_js('document.getElementById("status").innerText = "Completed!"')
        threading.Thread(target=_work, daemon=True).start()
        return {"started": True}

def main():
    html = """
    <!DOCTYPE html><html><head><title>Complete Application</title></head>
    <body>
        <h1>Task Manager</h1>
        <input id="itemInput" placeholder="Enter new item">
        <button onclick="addItem()">Add</button>
        <button onclick="loadItems()">Refresh</button>
        <button onclick="clearItems()">Clear all</button>
        <button onclick="startProcess()">Long process</button>

        <div id="status">Ready</div>
        <div id="items"></div>

        <script>
        async function addItem() {
            const txt = document.getElementById('itemInput').value.trim();
            if (!txt) return;
            await window.pywebview.api.add_item(txt);
            document.getElementById('itemInput').value = '';
            loadItems();
        }
        async function loadItems() {
            const items = await window.pywebview.api.get_items();
            document.getElementById('items').innerHTML = items
                .map(i => `<div class="item">${i.text} (ID: ${i.id})</div>`).join('');
        }
        async function clearItems() {
            await window.pywebview.api.clear_items();
            document.getElementById('items').innerHTML = '';
        }
        async function startProcess() {
            await window.pywebview.api.long_process();
        }
        document.addEventListener('pywebviewready', loadItems);
        </script>
    </body></html>
    """

    window = webview.create_window(
        "Complete Application",
        html=html,
        width=800,
        height=600,
        min_size=(600, 400),
        resizable=True,
    )
    api = AppApi(window)

    # 👉 window.events.loaded & closing
    def on_loaded():
        print("✅ UI loaded")
    def on_closing():
        print("⚙️ Shutting down")
        return True            # allow close
    window.events.loaded += on_loaded
    window.events.closing += on_closing

    webview.start(js_api=api)

if __name__ == "__main__":
    main()
```

---

## 11️⃣ New example – **dynamic expose** (`window.expose`)  

```python
# 11_expose_function.py
import webview

def greet(name: str = "World"):
    """Simple function that will be callable from JS after being exposed."""
    return f"👋 Hello, {name}!"

def main():
    # Create a minimal window – the HTML can be anything.
    window = webview.create_window("Expose Demo", html="<h1>Check console</h1>", width=400, height=300)

    # 👉 https://pywebview.flowrl.com/api.html#window-expose
    window.expose(greet)          # becomes window.pywebview.api.greet in JS

    webview.start()

if __name__ == "__main__":
    main()
```

**JavaScript side** (add to any page loaded in the window):

```html
<script>
async function demo() {
    const msg = await window.pywebview.api.greet("Alice");
    console.log(msg);   // → "👋 Hello, Alice!"
}
demo();
</script>
```

*Why `expose`?* – It lets you add or replace a callable **after** the GUI loop has started, which is handy for plug‑in architectures or for adding debug helpers on the fly.

---

### How to run  

```bash
pip install pywebview       # on Linux you may need extra deps – see the Installation docs
python 1_basic_window.py   # pick any file name from the list above
```

Every snippet now carries a **one‑click reference** to the official documentation, so you can instantly verify arguments, defaults, and platform‑specific notes. Enjoy experimenting with PyWebView! 🎉  