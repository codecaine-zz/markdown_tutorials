# Complete pywebview Tutorial for Non-Programmers

pywebview is a Python library that allows you to create desktop applications using HTML, CSS, and JavaScript - just like building websites. This tutorial will guide you from the basics to advanced features in simple terms.

## What is pywebview?

Think of pywebview as a bridge between web development and desktop applications. You can build your app's interface using familiar web technologies (HTML, CSS, JavaScript) and use Python for the backend logic. It's like having a web browser built into your application without needing an internet connection.

## Getting Started

### Installation

First, you'll need to install pywebview. Open your command prompt or terminal and type:

```bash
pip install pywebview
```

### Basic Example - Hello World

Let's start with the simplest possible application:

```python
import webview

# Create a window with a title and HTML content
window = webview.create_window('Hello World', html='<h1>Hello World!</h1>')

# Start the application
webview.start()
```

This code creates a window with the title "Hello World" and displays an HTML heading saying "Hello World!".

## Basic Concepts

### Creating Windows

Windows are the foundation of pywebview applications. Here's how to create different types of windows:

#### Simple URL Window

```python
import webview

# Load a website in a window
window = webview.create_window('My App', 'https://www.google.com')
webview.start()
```

#### HTML Content Window

```python
import webview

html_content = """
<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
</head>
<body>
    <h1>Welcome to My App</h1>
    <p>This is my first pywebview application.</p>
</body>
</html>
"""

window = webview.create_window('My App', html=html_content)
webview.start()
```

### Window Customization

You can customize your windows with various parameters:

```python
import webview

window = webview.create_window(
    title='Custom Window',
    html='<h1>Customized Window</h1>',
    width=1000,        # Width in pixels
    height=600,        # Height in pixels
    resizable=True,    # Can resize the window
    fullscreen=False,  # Start in fullscreen mode
    frameless=False    # Remove window borders
)

webview.start()
```

## Connecting Python and JavaScript

One of pywebview's most powerful features is connecting Python (backend) with JavaScript (frontend).

### Exposing Python Functions to JavaScript

```python
import webview

class Api:
    def hello(self, name):
        return f'Hello {name}!'

def create_app():
    api = Api()
    window = webview.create_window('API Example', html='<h1>API Example</h1>', js_api=api)
    webview.start()

if __name__ == '__main__':
    create_app()
```

To call this from JavaScript:

```html
<!DOCTYPE html>
<html>
<body>
    <button onclick="greet()">Greet</button>
    <p id="result"></p>
    
    <script>
        function greet() {
            // Call Python function
            window.pywebview.api.hello('World').then(function(result) {
                document.getElementById('result').innerText = result;
            });
        }
    </script>
</body>
</html>
```

## Working with Files and Dialogs

### File Dialogs

```python
import webview

def open_file_dialog(window):
    # Open file dialog
    file_types = ('Image Files (*.bmp;*.jpg;*.gif)', 'All files (*.*)')
    result = window.create_file_dialog(webview.OPEN_DIALOG, allow_multiple=True, file_types=file_types)
    print(result)

window = webview.create_window('File Dialog Example', html='<h1>File Dialog Example</h1>')
webview.start(open_file_dialog, window)
```

### Confirmation Dialogs

```python
import webview

def show_confirmation(window):
    result = window.create_confirmation_dialog('Confirmation', 'Are you sure?')
    if result:
        print('User clicked OK')
    else:
        print('User clicked Cancel')

window = webview.create_window('Confirmation Dialog', html='<h1>Confirmation Dialog</h1>')
webview.start(show_confirmation, window)
```

## DOM Manipulation

pywebview allows you to manipulate the HTML document from Python:

```python
import webview

html = """
<!DOCTYPE html>
<html>
<body>
    <div id="container">
        <p>Original text</p>
    </div>
</body>
</html>
"""

def manipulate_dom(window):
    # Get element by ID
    container = window.dom.get_element('#container')
    
    # Change text
    container.children[0].text = 'Text changed from Python!'
    
    # Add new element
    container.append('<p>New paragraph added from Python</p>')

window = webview.create_window('DOM Manipulation', html=html)
webview.start(manipulate_dom, window)
```

## Window Events

You can respond to window events like loading, closing, resizing, etc.:

```python
import webview

def on_loaded():
    print('Window loaded')

def on_closed():
    print('Window closed')

html = '<h1>Event Example</h1>'

window = webview.create_window('Event Example', html=html)
window.events.loaded += on_loaded
window.events.closed += on_closed

webview.start()
```

## Multiple Windows

You can create multiple windows in one application:

```python
import webview

def create_windows():
    window1 = webview.create_window('Window 1', html='<h1>Window 1</h1>')
    window2 = webview.create_window('Window 2', html='<h1>Window 2</h1>')
    webview.start()

if __name__ == '__main__':
    create_windows()
```

## Working with Local Files

You can load local HTML files:

```python
import webview
import os

# Get the directory where the script is located
current_dir = os.path.dirname(os.path.abspath(__file__))
html_file = os.path.join(current_dir, 'index.html')

window = webview.create_window('Local File', html_file)
webview.start()
```

## Advanced Features

### Custom CSS

```python
import webview

html = '<h1 id="heading">Styled Heading</h1>'
css = '''
    #heading {
        color: blue;
        font-size: 36px;
        text-align: center;
    }
'''

def apply_css(window):
    window.load_css(css)

window = webview.create_window('CSS Example', html=html)
webview.start(apply_css, window)
```

### Running JavaScript

```python
import webview

html = '<h1 id="heading">Original Text</h1>'

def change_text(window):
    # Execute JavaScript to change text
    window.evaluate_js("""
        document.getElementById('heading').innerText = 'Changed by JavaScript';
    """)

window = webview.create_window('JavaScript Example', html=html)
webview.start(change_text, window)
```

### Window Management

```python
import webview

def manage_window(window):
    # Resize window
    window.resize(800, 600)
    
    # Move window
    window.move(100, 100)
    
    # Maximize window
    window.maximize()
    
    # Minimize window
    window.minimize()
    
    # Restore window
    window.restore()
    
    # Toggle fullscreen
    window.toggle_fullscreen()

window = webview.create_window('Window Management', html='<h1>Window Management</h1>')
webview.start(manage_window, window)
```

## Practical Example - Simple Note-Taking App

Let's build a complete application:

```python
import webview

class NoteApp:
    def __init__(self):
        self.notes = []
    
    def add_note(self, note):
        self.notes.append(note)
        return {'status': 'success', 'message': 'Note added'}
    
    def get_notes(self):
        return self.notes
    
    def clear_notes(self):
        self.notes = []
        return {'status': 'success', 'message': 'Notes cleared'}

def create_note_app():
    api = NoteApp()
    
    html = """
    <!DOCTYPE html>
    <html>
    <head>
        <title>Simple Note App</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            textarea { width: 100%; height: 100px; margin-bottom: 10px; }
            button { padding: 10px 20px; margin: 5px; }
            #notes { margin-top: 20px; }
        </style>
    </head>
    <body>
        <h1>Simple Note App</h1>
        <textarea id="noteInput" placeholder="Enter your note here..."></textarea><br>
        <button onclick="addNote()">Add Note</button>
        <button onclick="showNotes()">Show Notes</button>
        <button onclick="clearNotes()">Clear All</button>
        <div id="notes"></div>
        
        <script>
            function addNote() {
                const note = document.getElementById('noteInput').value;
                if (note.trim() !== '') {
                    window.pywebview.api.add_note(note).then(function(response) {
                        alert(response.message);
                        document.getElementById('noteInput').value = '';
                    });
                }
            }
            
            function showNotes() {
                window.pywebview.api.get_notes().then(function(notes) {
                    const notesDiv = document.getElementById('notes');
                    notesDiv.innerHTML = '<h2>Notes</h2>';
                    if (notes.length === 0) {
                        notesDiv.innerHTML += '<p>No notes yet.</p>';
                    } else {
                        const list = document.createElement('ul');
                        notes.forEach(function(note) {
                            const item = document.createElement('li');
                            item.textContent = note;
                            list.appendChild(item);
                        });
                        notesDiv.appendChild(list);
                    }
                });
            }
            
            function clearNotes() {
                window.pywebview.api.clear_notes().then(function(response) {
                    alert(response.message);
                    document.getElementById('notes').innerHTML = '';
                });
            }
        </script>
    </body>
    </html>
    """
    
    window = webview.create_window('Note App', html=html, js_api=api, width=600, height=500)
    webview.start()

if __name__ == '__main__':
    create_note_app()
```

## Best Practices

1. **Error Handling**: Always handle potential errors in your JavaScript-Python communication
2. **Security**: Be careful when exposing Python functions to JavaScript - only expose what's necessary
3. **Performance**: Avoid frequent DOM manipulations from Python as they can be slow
4. **File Paths**: Use relative paths or proper path resolution for local files

## Common Use Cases

1. **Desktop Applications**: Convert web apps to desktop apps
2. **Prototyping**: Quickly build application prototypes using web technologies
3. **Data Visualization**: Create desktop apps with complex visualizations using libraries like D3.js
4. **Legacy System Interfaces**: Modernize old systems with web-based interfaces
5. **Cross-Platform Apps**: Build applications that work on Windows, macOS, and Linux with single codebase

## Next Steps

1. Explore the [official examples](https://pywebview.flowrl.com/examples/) for more advanced use cases
2. Learn about [debugging pywebview applications](https://pywebview.flowrl.com/guide/debugging)
3. Understand [security considerations](https://pywebview.flowrl.com/guide/security)
4. Experiment with different [web engines](https://pywebview.flowrl.com/guide/web_engine)

This tutorial covered the fundamentals of pywebview, from basic window creation to advanced features like DOM manipulation and JavaScript-Python communication. With this foundation, you can start building your own desktop applications using familiar web technologies.