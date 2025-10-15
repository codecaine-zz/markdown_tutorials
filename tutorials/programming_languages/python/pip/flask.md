# Complete Flask Tutorial: From Beginner to Advanced

This comprehensive tutorial will guide you through building web applications with Flask, the popular Python web framework. We'll cover everything from basic setup to advanced features.

## Table of Contents
1. [Installation and Setup](#installation-and-setup)
2. [Basic Flask Application](#basic-flask-application)
3. [Routing and URL Building](#routing-and-url-building)
4. [Request Handling](#request-handling)
5. [Templates and Jinja2](#templates-and-jinja2)
6. [Forms and Form Validation](#forms-and-form-validation)
7. [Database Integration with SQLAlchemy](#database-integration-with-sqlalchemy)
8. [Sessions and Authentication](#sessions-and-authentication)
9. [File Uploads](#file-uploads)
10. [API Development](#api-development)
11. [Error Handling](#error-handling)

---

## Installation and Setup

### Prerequisites
First, ensure you have Python installed (3.7 or higher recommended). Then install Flask:

```bash
# Create a virtual environment (recommended)
python -m venv flask_env

# Activate the virtual environment
# On Windows:
flask_env\Scripts\activate
# On macOS/Linux:
source flask_env/bin/activate

# Install Flask
pip install Flask
```

### Project Structure
Create this directory structure for our tutorial:

```
flask_app/
│
├── app.py              # Main application file
├── requirements.txt    # Dependencies
├── templates/          # HTML templates
│   ├── base.html       # Base template
│   ├── index.html      # Home page
│   └── about.html      # About page
└── static/             # Static files (CSS, JS, images)
    └── style.css       # CSS stylesheet
```

---

## Basic Flask Application

Let's start with a simple "Hello World" application:

```python
# app.py - Basic Flask Application
from flask import Flask

# Create the Flask application instance
app = Flask(__name__)

# Define a route for the root URL '/'
@app.route('/')
def home():
    return '<h1>Welcome to Flask!</h1>'

# Define another route for '/about'
@app.route('/about')
def about():
    return '<h1>About Page</h1><p>This is a Flask application tutorial.</p>'

# Run the application
if __name__ == '__main__':
    app.run(debug=True)
```

**Key Concepts:**
- `Flask(__name__)` creates the Flask application instance
- `@app.route()` decorator maps URLs to functions
- `app.run(debug=True)` starts the development server

Run with: `python app.py`
Visit http://localhost:5000/ and http://localhost:5000/about

---

## Routing and URL Building

Flask routes can accept parameters, multiple methods, and have various configurations:

```python
# app.py - Advanced Routing
from flask import Flask, render_template, url_for, request

app = Flask(__name__)

# Simple parameterized route
@app.route('/user/<username>')
def show_user_profile(username):
    # The username is passed as a string parameter
    return f'<h1>Hello {username}!</h1>'

# Route with different data types
@app.route('/post/<int:post_id>')
def show_post(post_id):
    # post_id will be converted to integer automatically
    return f'<h1>Post ID: {post_id}</h1>'

# Multiple methods for one route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        # Handle form submission (POST)
        username = request.form['username']
        password = request.form['password']
        return f'Logging in as {username}'
    else:
        # Display the login form (GET)
        return '<form method="post"><input type="text" name="username"><input type="password" name="password"><input type="submit"></form>'

# Route with optional parameter
@app.route('/page/<path:subpath>')
def show_subpath(subpath):
    # Accepts any path after /page/
    return f'Subpath: {subpath}'

# Generate URLs programmatically using url_for()
@app.route('/url-generation')
def url_generation():
    # Build URLs for other routes
    home_url = url_for('home')  # Generates '/'

# Multiple routes pointing to same function
@app.route('/index')
@app.route('/')
def home():
    return '<h1>Home Page</h1>'

if __name__ == '__main__':
    app.run(debug=True)
```

---

## Request Handling

Flask provides robust request handling for forms, files, headers, etc.:

```python
# app.py - Advanced Request Handling
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/form', methods=['GET', 'POST'])
def handle_form():
    if request.method == 'POST':
        # Handle form data from POST request
        
        # Get form fields
        name = request.form.get('name')  # Safe way to get field
        email = request.form['email']    # Will raise KeyError if missing
        age = request.form.get('age', 0) # Default value if not provided
        
        return f'Hello {name}, your email is {email} and age is {age}'
    
    else:
        # Handle GET request (display form)
        return '''
            <form method="post">
                Name: <input type="text" name="name"><br>
                Email: <input type="email" name="email"><br>
                Age: <input type="number" name="age"><br>
                <input type="submit">
            </form>
        '''

# Get URL parameters
@app.route('/search')
def search():
    # Query parameters from URL: /search?query=python&category=web
    query = request.args.get('query', '')  # Safe way to get parameter
    category = request.args.get('category', 'all')
    
    return f'Searching for "{query}" in category "{category}"'

# Get request headers
@app.route('/headers')
def show_headers():
    user_agent = request.headers.get('User-Agent')
    content_type = request.headers.get('Content-Type')
    
    return f'User Agent: {user_agent}<br>Content Type: {content_type}'

# Handle JSON requests (API-style)
@app.route('/api/data', methods=['POST'])
def api_data():
    # Parse JSON data from request
    if request.is_json:
        data = request.get_json()
        name = data.get('name')
        email = data.get('email')
        
        return jsonify({
            'message': f'Received: {name} - {email}',
            'status': 'success'
        })
    else:
        return jsonify({'error': 'Content must be JSON'}), 400

if __name__ == '__main__':
    app.run(debug=True)
```

---

## Templates and Jinja2

Templates allow dynamic content generation using Jinja2 templating engine:

```python
# app.py - Template Example
from flask import Flask, render_template, request

app = Flask(__name__)

# Sample data for templates
users = [
    {'id': 1, 'name': 'Alice', 'email': 'alice@example.com'},
    {'id': 2, 'name': 'Bob', 'email': 'bob@example.com'},
    {'id': 3, 'name': 'Charlie', 'email': 'charlie@example.com'}
]

@app.route('/users')
def show_users():
    return render_template('users.html', users=users)

@app.route('/user/<int:user_id>')
def show_user(user_id):
    # Find user by ID
    user = next((u for u in users if u['id'] == user_id), None)
    if user:
        return render_template('user.html', user=user)
    else:
        return 'User not found', 404

# Dynamic content with loops and conditionals
@app.route('/dynamic')
def dynamic_content():
    products = [
        {'name': 'Laptop', 'price': 999.99, 'in_stock': True},
        {'name': 'Mouse', 'price': 29.99, 'in_stock': False},
        {'name': 'Keyboard', 'price': 79.99, 'in_stock': True}
    ]
    
    return render_template('products.html', products=products)

if __name__ == '__main__':
    app.run(debug=True)
```

**templates/base.html:**
```html
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}Flask Tutorial{% endblock %}</title>
    <link rel="stylesheet" href="{{ url_for('static', filename='style.css') }}">
</head>
<body>
    <nav>
        <a href="/">Home</a> | 
        <a href="/users">Users</a> | 
        <a href="/dynamic">Products</a>
    </nav>
    
    <div class="container">
        {% block content %}{% endblock %}
    </div>
</body>
</html>
```

**templates/users.html:**
```html
{% extends "base.html" %}

{% block title %}Users - Flask Tutorial{% endblock %}

{% block content %}
<h1>Users List</h1>
<ul>
{% for user in users %}
    <li><a href="/user/{{ user.id }}">{{ user.name }}</a> - {{ user.email }}</li>
{% endfor %}
</ul>
{% endblock %}
```

**templates/user.html:**
```html
{% extends "base.html" %}

{% block title %}{{ user.name }} - Flask Tutorial{% endblock %}

{% block content %}
<h1>{{ user.name }}</h1>
<p>Email: {{ user.email }}</p>
<a href="/users">Back to users</a>
{% endblock %}
```

**templates/products.html:**
```html
{% extends "base.html" %}

{% block title %}Products - Flask Tutorial{% endblock %}

{% block content %}
<h1>Our Products</h1>
<div class="products">
{% for product in products %}
    <div class="product">
        <h3>{{ product.name }}</h3>
        <p>Price: ${{ "%.2f"|format(product.price) }}</p>
        {% if product.in_stock %}
            <span class="in-stock">In Stock</span>
        {% else %}
            <span class="out-of-stock">Out of Stock</span>
        {% endif %}
    </div>
{% endfor %}
</div>
{% endblock %}
```

**static/style.css:**
```css
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

nav {
    background-color: #f0f0f0;
    padding: 10px;
    margin-bottom: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.product {
    border: 1px solid #ddd;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
}

.in-stock {
    color: green;
    font-weight: bold;
}

.out-of-stock {
    color: red;
    font-weight: bold;
}
```

---

## Complete Flask Application

```python
# app.py - Complete Example
from flask import Flask, render_template, request, jsonify

app = Flask(__name__)

# Sample data
users = [
    {'id': 1, 'name': 'Alice', 'email': 'alice@example.com'},
    {'id': 2, 'name': 'Bob', 'email': 'bob@example.com'},
    {'id': 3, 'name': 'Charlie', 'email': 'charlie@example.com'}
]

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/api/users')
def api_users():
    return jsonify(users)

@app.route('/api/user/<int:user_id>')
def api_user(user_id):
    user = next((u for u in users if u['id'] == user_id), None)
    if user:
        return jsonify(user)
    else:
        return jsonify({'error': 'User not found'}), 404

@app.route('/api/users', methods=['POST'])
def create_user():
    data = request.get_json()
    
    new_user = {
        'id': len(users) + 1,
        'name': data.get('name'),
        'email': data.get('email')
    }
    
    users.append(new_user)
    return jsonify(new_user), 201

if __name__ == '__main__':
    app.run(debug=True)
```

**templates/index.html:**
```html
{% extends "base.html" %}

{% block title %}Welcome - Flask Tutorial{% endblock %}

{% block content %}
<h1>Welcome to Flask Tutorial</h1>
<p>This is a complete example of a Flask application with:</p>
<ul>
    <li>HTML templates</li>
    <li>Dynamic content rendering</li>
    <li>RESTful API endpoints</li>
    <li>JSON handling</li>
    <li>CSS styling</li>
</ul>

<h2>Demo Endpoints:</h2>
<ul>
    <li><a href="/api/users">/api/users - Get all users (JSON)</a></li>
    <li><a href="/api/user/1">/api/user/1 - Get user by ID (JSON)</a></li>
</ul>

<p>Try making POST requests to <code>/api/users</code> with JSON data:</p>
<pre>
{
  "name": "David",
  "email": "david@example.com"
}
</pre>
{% endblock %}
```

This comprehensive Flask tutorial covers:

1. Basic routing and request handling
2. Template rendering with Jinja2
3. Form processing (GET/POST)
4. JSON API development
5. Static file serving (CSS, JS)
6. Error handling
7. URL parameters and query strings

The example demonstrates both web application features and RESTful API capabilities in a single Flask app structure.
```