Absolutely! Below is the **RAD (Rapid Application Development)-friendly version** of the tutorial. All code examples are **fully commented**, **copy-paste ready**, and structured so you can **build step-by-step** without needing to think too much.

---

# 🧰 WebUI v2.5 – Python RAD Tutorial with Fully Commented Code

> ✅ Copy-paste each snippet into your editor to build a working app in minutes.

---

## 🧱 1. Install WebUI

Run in terminal:

```bash
pip install webui2
```

---

## 🧪 2. Hello World – Minimal Example

**File: `hello.py`**

```python
# hello.py
# This is the simplest WebUI app. Just shows a message in a browser window.

from webui import webui  # Import the WebUI library

# Create a new window object
window = webui.Window()

# Show a simple HTML string in the window
window.show('<h1>Hello, World from Python!</h1>')

# Keep the app running until the user closes the window
webui.wait()
```

✅ Run with:

```bash
python hello.py
```

---

## 📄 3. Load HTML File

### Step 1: Create `index.html`

```html
<!-- index.html -->
<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
    <!-- Required: Enables communication between JS and Python -->
    <script src="webui.js"></script>
</head>
<body>
    <h1>Welcome to WebUI</h1>
    <button id="myButton">Click Me!</button>
    <p id="output">...</p>

    <script>
        // When the button is clicked, call a Python function
        document.getElementById("myButton").addEventListener("click", () => {
            // Call Python function named "myPythonFunction"
            myPythonFunction("Hello from JS").then(response => {
                // Update the page with the response
                document.getElementById("output").innerText = response;
            });
        });
    </script>
</body>
</html>
```

---

### Step 2: Create `main.py`

```python
# main.py
# Loads an HTML file and binds a Python function to be called from JavaScript.

from webui import webui

# This function is called from JavaScript
def my_python_function(event):
    # Print to Python console
    print("✅ Button clicked!")

    # Send a response back to JavaScript
    return "Hello from Python!"

# Create a new window
window = webui.Window()

# Bind the Python function so JavaScript can call it
window.bind("myPythonFunction", my_python_function)

# Show the HTML file in the window
window.show("index.html")

# Keep the app running
try:
    webui.wait()
except KeyboardInterrupt:
    print("\n👋 Exiting...")
    window.close()  # Close the window
```

✅ Run with:

```bash
python main.py
```

---

## 🔄 4. Calling JavaScript from Python

You can also run JavaScript **from Python**.

### Add this to your Python handler:

```python
# Run JavaScript without waiting for a response
window.run("alert('This is a message from Python!')")

# Or update content dynamically
window.run("document.getElementById('output').innerText = 'Updated from Python!'")
```

---

## 🧮 5. Full Example: Counter App

### `counter.html`

```html
<!-- counter.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Counter App</title>
    <script src="webui.js"></script>
</head>
<body>
    <h2>Count: <span id="count">0</span></h2>
    <button id="inc">+</button>
    <button id="dec">-</button>
    <button id="reset">Reset</button>

    <script>
        // Call Python functions when buttons are clicked
        document.getElementById("inc").addEventListener("click", () => {
            increment().then(v => document.getElementById("count").innerText = v);
        });
        document.getElementById("dec").addEventListener("click", () => {
            decrement().then(v => document.getElementById("count").innerText = v);
        });
        document.getElementById("reset").addEventListener("click", () => {
            reset().then(v => document.getElementById("count").innerText = v);
        });
    </script>
</body>
</html>
```

---

### `counter.py`

```python
# counter.py
# A simple counter app that demonstrates bidirectional communication.

from webui import webui

# Global variable to store the counter
count = 0

# Function called when "+" is clicked
def increment(event):
    global count
    count += 1
    print("🔼 Count is now:", count)
    return count  # Return new value to JavaScript

# Function called when "-" is clicked
def decrement(event):
    global count
    count -= 1
    print("🔽 Count is now:", count)
    return count

# Function called when "Reset" is clicked
def reset(event):
    global count
    count = 0
    print("🔁 Count reset to 0")
    return count

# Create a window
window = webui.Window()

# Bind functions so they can be called from JavaScript
window.bind("increment", increment)
window.bind("decrement", decrement)
window.bind("reset", reset)

# Show the HTML file
window.show("counter.html")

# Wait for the window to close
try:
    webui.wait()
except KeyboardInterrupt:
    print("\n👋 Goodbye!")
    window.close()
```

✅ Run with:

```bash
python counter.py
```

---

## 🧠 6. Best Practices – Quick Reference

### ✅ Always include `webui.js` in your HTML:

```html
<script src="webui.js"></script>
```

### ✅ Handle shutdown gracefully:

```python
try:
    webui.wait()
except KeyboardInterrupt:
    window.close()
```

### ✅ Validate data from JavaScript:

```python
def my_function(event):
    if not isinstance(event.data, str):
        return "Invalid input"
    ...
```

### ✅ Run long tasks in a thread to avoid freezing the UI:

```python
import threading

def long_task():
    # Simulate long work
    import time
    time.sleep(3)
    window.run("alert('Task finished!')")

threading.Thread(target=long_task).start()
```

---

## 🧰 7. RAD Development Snippets

### ✅ Show alert in browser from Python:

```python
window.run("alert('Hello from Python!')")
```

### ✅ Update text in HTML:

```python
window.run("document.getElementById('output').innerText = 'New Value'")
```

### ✅ Bind a Python function to JavaScript:

```python
def my_func(event):
    return "Response from Python"

window.bind("myFunc", my_func)
```

Call from JS:

```js
myFunc().then(res => console.log(res));
```

---

## 🧪 8. Debugging Tips

| Problem | Solution |
|--------|----------|
| Window closes immediately | Make sure `webui.js` is included |
| JS function not found | Check the name in `window.bind()` |
| Python hangs | Make sure `webui.wait()` is called |
| UI freezes | Don’t block the main thread – use threading |

---

## 🧰 9. Bonus: Package as EXE (Optional)

Install `pyinstaller`:

```bash
pip install pyinstaller
```

Package your app:

```bash
pyinstaller --onefile counter.py
```

Your `.exe` will be in `dist/`.

---

## 🎉 You're Ready!

With this guide, you can now:

✅ Build apps with Python and HTML  
✅ Call Python from JavaScript  
✅ Call JavaScript from Python  
✅ Debug and package your apps  

---

Let me know if you'd like a **project template ZIP**, a **video walkthrough**, or help with **advanced features** like sending/receiving JSON or using multiple windows!