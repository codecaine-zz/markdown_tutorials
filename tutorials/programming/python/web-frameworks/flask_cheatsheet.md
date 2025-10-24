```python
#!/usr/bin/env python3
# ======================================================================
# Flask – Complete “All‑in‑One” Tutorial
# ======================================================================
# Every major topic from the official Flask documentation
# (https://flask.palletsprojects.com/) is demonstrated as a runnable
# code snippet inside comments.  Copy any block into its own *.py file,
# install the required packages and run `python file.py`.
# ======================================================================

# --------------------------------------------------------------
# 0️⃣  INSTALLATION
# --------------------------------------------------------------
#   pip install Flask
#   # Optional extensions (pick what you need)
#   pip install Flask-WTF            # forms & CSRF
#   pip install Flask-Login          # user session management
#   pip install Flask-Migrate        # DB migrations
#   pip install Flask-SQLAlchemy     # ORM
#   pip install Flask-Mail           # email sending
#   pip install Flask-Testing        # test utilities
#   pip install gunicorn            # production WSGI server
#
#   # Verify install
#   python -c "import flask; print('Flask version', flask.__version__)"

# --------------------------------------------------------------
# 1️⃣  A MINIMAL “Hello World” APPLICATION
# --------------------------------------------------------------
"""
from flask import Flask

app = Flask(__name__)                     # create the app instance

@app.route('/')                           # route decorator → URL path
def hello():
    return 'Hello, Flask!'

if __name__ == '__main__':
    # run the development server (debug on for auto‑reload)
    app.run(debug=True)                  # http://127.0.0.1:5000/
"""
# --------------------------------------------------------------
# 2️⃣  CONFIGURATION
# --------------------------------------------------------------
"""
from flask import Flask
app = Flask(__name__)

# 2‑a – simple key/value config
app.config['SECRET_KEY'] = 'dev-secret-key'   # needed for sessions/CSRF
app.config['DEBUG'] = True

# 2‑b – load from a Python file
# config.py
#   DEBUG = False
#   DATABASE_URI = 'sqlite:///app.db'
# app.config.from_object('config')

# 2‑c – load from environment variables (12‑factor)
# export FLASK_SECRET_KEY='prod-secret'
# app.config.from_prefixed_env()   # Flask 2.3+ reads FLASK_*

# 2‑d – use a class‑based config (common pattern)
class Config:
    DEBUG = False
    TESTING = False
    SECRET_KEY = 'a‑very‑secret'

class DevelopmentConfig(Config):
    DEBUG = True

app.config.from_object(DevelopmentConfig)
"""
# --------------------------------------------------------------
# 3️⃣  REQUEST & RESPONSE OBJECTS
# --------------------------------------------------------------
"""
from flask import Flask, request, make_response, jsonify, redirect, url_for
app = Flask(__name__)

@app.route('/info')
def info():
    # request – inbound data
    user_agent = request.headers.get('User-Agent')
    remote_ip   = request.remote_addr
    method      = request.method
    args        = request.args      # query string dict‑like
    json_body   = request.get_json(silent=True)  # parsed JSON or None

    # Build a response
    data = {
        'method': method,
        'ua': user_agent,
        'ip': remote_ip,
        'args': args,
        'json_body': json_body,
    }
    resp = make_response(jsonify(data), 200)   # set status code
    resp.headers['X-Custom'] = 'FlaskDemo'     # custom header
    return resp
"""
# --------------------------------------------------------------
# 4️⃣  URL BUILDING & REDIRECTS
# --------------------------------------------------------------
"""
from flask import Flask, url_for, redirect

app = Flask(__name__)

@app.route('/login')
def login():
    # … authentication logic …
    return redirect(url_for('dashboard', user_id=42))

@app.route('/dashboard/<int:user_id>')
def dashboard(user_id):
    return f'Welcome user {user_id}'
"""
# --------------------------------------------------------------
# 5️⃣  TEMPLATE RENDERING (Jinja2)
# --------------------------------------------------------------
"""
# Directory layout:
#   project/
#     app.py
#     templates/
#         base.html
#         index.html
#     static/
#         css/style.css

# base.html
# ------------------------------------------------
# <!doctype html>
# <html lang="en">
#   <head>
#     <meta charset="utf-8">
#     <title>{% block title %}My App{% endblock %}</title>
#     <link rel="stylesheet" href="{{ url_for('static', filename='css/style.css') }}">
#   </head>
#   <body>
#     {% block content %}{% endblock %}
#   </body>
# </html>
# ------------------------------------------------

# index.html (extends base)
# ------------------------------------------------
# {% extends "base.html" %}
# {% block title %}Home{% endblock %}
# {% block content %}
#   <h1>Hello {{ name|e }}</h1>
#   <p>Current time: {{ now }}</p>
#   <ul>
#   {% for item in items %}
#       <li>{{ loop.index }} – {{ item }}</li>
#   {% else %}
#       <li>No items.</li>
#   {% endfor %}
#   </ul>
# {% endblock %}
# ------------------------------------------------

from flask import Flask, render_template
from datetime import datetime

app = Flask(__name__)

@app.route('/')
def index():
    return render_template(
        'index.html',
        name='Alice',
        now=datetime.utcnow(),
        items=['Apple', 'Banana', 'Cherry']
    )
"""
# --------------------------------------------------------------
# 6️⃣  STATIC FILES (CSS / JS / Images)
# --------------------------------------------------------------
"""
# Flask automatically serves files placed in the `static/` folder.
# In a template you refer to them via `url_for('static', filename='path/in/static')`.

# Example (templates/base.html):
# <link rel="stylesheet" href="{{ url_for('static', filename='css/main.css') }}">
# <script src="{{ url_for('static', filename='js/app.js') }}"></script>

# If you need a custom static folder:
# app = Flask(__name__, static_folder='public')
"""
# --------------------------------------------------------------
# 7️⃣  FORMS & CSRF PROTECTION (Flask‑WTF)
# --------------------------------------------------------------
"""
# pip install Flask-WTF
# -----------------------------------------------------------------
# forms.py
# -----------------------------------------------------------------
from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, SubmitField, BooleanField
from wtforms.validators import DataRequired, Email, Length, EqualTo

class RegisterForm(FlaskForm):
    email    = StringField('Email', validators=[DataRequired(), Email()])
    password = PasswordField('Password', validators=[DataRequired(), Length(min=6)])
    confirm  = PasswordField('Confirm', validators=[
        DataRequired(),
        EqualTo('password', message='Passwords must match')
    ])
    accept   = BooleanField('I accept the Terms', validators=[DataRequired()])
    submit   = SubmitField('Register')
# -----------------------------------------------------------------
# app.py (excerpt)
# -----------------------------------------------------------------
from flask import Flask, render_template, flash, redirect, url_for
from forms import RegisterForm

app = Flask(__name__)
app.config['SECRET_KEY'] = 'change‑me'

@app.route('/register', methods=['GET', 'POST'])
def register():
    form = RegisterForm()
    if form.validate_on_submit():
        # Here you would hash the password & store the user.
        flash('Account created!', 'success')
        return redirect(url_for('index'))
    return render_template('register.html', form=form)

# -----------------------------------------------------------------
# templates/register.html
# -----------------------------------------------------------------
# {% extends "base.html" %}
# {% block content %}
#   <h2>Register</h2>
#   <form method="POST">
#       {{ form.hidden_tag() }}   {# CSRF token #}
#       <p>{{ form.email.label }} {{ form.email() }}</p>
#       <p>{{ form.password.label }} {{ form.password() }}</p>
#       <p>{{ form.confirm.label }} {{ form.confirm() }}</p>
#       <p>{{ form.accept() }} {{ form.accept.label }}</p>
#       <p>{{ form.submit() }}</p>
#   </form>
# {% endblock %}
"""
# --------------------------------------------------------------
# 8️⃣  DATABASE INTEGRATION – Flask‑SQLAlchemy
# --------------------------------------------------------------
"""
# pip install Flask-SQLAlchemy
# -----------------------------------------------------------------
# models.py
# -----------------------------------------------------------------
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash

db = SQLAlchemy()

class User(db.Model):
    __tablename__ = 'users'
    id       = db.Column(db.Integer, primary_key=True)
    email    = db.Column(db.String(120), unique=True, nullable=False)
    pwd_hash = db.Column(db.String(128), nullable=False)

    def set_password(self, password):
        self.pwd_hash = generate_password_hash(password)

    def check_password(self, password):
        return check_password_hash(self.pwd_hash, password)

# -----------------------------------------------------------------
# app.py (excerpt)
# -----------------------------------------------------------------
from flask import Flask
from models import db, User

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///app.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db.init_app(app)

# Create tables (run once, e.g. in a Flask shell)
with app.app_context():
    db.create_all()

# Use inside a view
@app.route('/add_user/<email>/<pwd>')
def add_user(email, pwd):
    if User.query.filter_by(email=email).first():
        return f'User {email} exists', 400
    user = User(email=email)
    user.set_password(pwd)
    db.session.add(user)
    db.session.commit()
    return f'Created {email}'
"""
# --------------------------------------------------------------
# 9️⃣  DATABASE MIGRATIONS – Flask‑Migrate (Alembic)
# --------------------------------------------------------------
"""
# pip install Flask-Migrate
# -----------------------------------------------------------------
# app.py (add migration support)
# -----------------------------------------------------------------
from flask_migrate import Migrate

migrate = Migrate(app, db)

# -----------------------------------------------------------------
# Command line (run from project root)
# -----------------------------------------------------------------
# flask db init        # creates migrations/ folder
# flask db migrate -m "Initial migration"
# flask db upgrade     # applies to the DB
# flask db downgrade   # roll back
"""
# --------------------------------------------------------------
# 1️⃣0️⃣  USER AUTHENTICATION – Flask‑Login
# --------------------------------------------------------------
"""
# pip install Flask-Login
# -----------------------------------------------------------------
# auth.py
# -----------------------------------------------------------------
from flask_login import LoginManager, UserMixin, login_user, logout_user, login_required, current_user
from models import User, db

login_manager = LoginManager()
login_manager.login_view = 'login'   # redirect unauthenticated users

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

class AuthUser(UserMixin, User):
    # Flask‑Login expects .is_active, .is_authenticated etc.
    # Inherit from User model and add required properties if needed.
    pass

# -----------------------------------------------------------------
# app.py (excerpt)
# -----------------------------------------------------------------
from flask import Flask, render_template, request, redirect, url_for, flash
from auth import login_manager, login_user, logout_user, login_required, AuthUser
from forms import LoginForm

app = Flask(__name__)
app.config['SECRET_KEY'] = 'change‑me'
login_manager.init_app(app)

@app.route('/login', methods=['GET', 'POST'])
def login():
    form = LoginForm()
    if form.validate_on_submit():
        user = User.query.filter_by(email=form.email.data).first()
        if user and user.check_password(form.password.data):
            login_user(AuthUser.query.get(user.id))
            flash('Logged in', 'success')
            return redirect(url_for('dashboard'))
        flash('Invalid credentials', 'danger')
    return render_template('login.html', form=form)

@app.route('/logout')
@login_required
def logout():
    logout_user()
    flash('You are logged out', 'info')
    return redirect(url_for('index'))

@app.route('/dashboard')
@login_required
def dashboard():
    return f'Hello {current_user.email}'
"""
# --------------------------------------------------------------
# 1️⃣1️⃣  EMAIL SENDING – Flask‑Mail
# --------------------------------------------------------------
"""
# pip install Flask-Mail
# -----------------------------------------------------------------
# app.py (excerpt)
# -----------------------------------------------------------------
from flask_mail import Mail, Message

app.config.update(
    MAIL_SERVER='smtp.example.com',
    MAIL_PORT=587,
    MAIL_USE_TLS=True,
    MAIL_USERNAME='you@example.com',
    MAIL_PASSWORD='secret',
    MAIL_DEFAULT_SENDER=('My App', 'no-reply@example.com')
)

mail = Mail(app)

@app.route('/send-test')
def send_test():
    msg = Message('Test Mail', recipients=['receiver@example.com'])
    msg.body = 'This is a plain‑text email'
    msg.html = '<p>This is <b>HTML</b> email</p>'
    mail.send(msg)
    return 'Mail sent!'
"""
# --------------------------------------------------------------
# 1️⃣2️⃣  BLUEPRINTS – Modular Application Structure
# --------------------------------------------------------------
"""
# Directory layout:
#   myapp/
#       __init__.py
#       auth/
#           __init__.py
#           routes.py
#           forms.py
#       blog/
#           __init__.py
#           routes.py
#           models.py
#       templates/
#           ...

# myapp/__init__.py
# ------------------------------------------------
from flask import Flask
from .auth.routes import auth_bp
from .blog.routes import blog_bp

def create_app():
    app = Flask(__name__)
    app.config.from_mapping(
        SECRET_KEY='dev',
        SQLALCHEMY_DATABASE_URI='sqlite:///app.db',
    )
    # init extensions here (db.init_app(app), migrate.init_app(app, db), …)

    # register blueprints
    app.register_blueprint(auth_bp, url_prefix='/auth')
    app.register_blueprint(blog_bp, url_prefix='/blog')
    return app
# ------------------------------------------------

# myapp/auth/routes.py
# ------------------------------------------------
from flask import Blueprint, render_template, redirect, url_for, flash
from .forms import LoginForm
from flask_login import login_user, logout_user, login_required

auth_bp = Blueprint('auth', __name__)

@auth_bp.route('/login', methods=['GET', 'POST'])
def login():
    form = LoginForm()
    if form.validate_on_submit():
        # authenticate …
        login_user(user)
        return redirect(url_for('blog.index'))
    return render_template('auth/login.html', form=form)

@auth_bp.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('auth.login'))
# ------------------------------------------------

# Run the app
# ------------------------------------------------
# run.py
from myapp import create_app

app = create_app()

if __name__ == '__main__':
    app.run(debug=True)
# ------------------------------------------------
"""
# --------------------------------------------------------------
# 1️⃣3️⃣  JSON API ENDPOINTS
# --------------------------------------------------------------
"""
from flask import Flask, request, jsonify, abort

app = Flask(__name__)

# Simple in‑memory storage for demo
DATA = {}

@app.route('/api/items', methods=['GET'])
def list_items():
    return jsonify(list(DATA.values()))

@app.route('/api/items/<string:item_id>', methods=['GET'])
def get_item(item_id):
    item = DATA.get(item_id)
    if not item:
        abort(404, description='Item not found')
    return jsonify(item)

@app.route('/api/items', methods=['POST'])
def create_item():
    payload = request.get_json(force=True)   # raise error if not JSON
    item_id = payload.get('id')
    if not item_id or item_id in DATA:
        abort(400, description='Invalid or duplicate id')
    DATA[item_id] = payload
    return jsonify(payload), 201
"""
# --------------------------------------------------------------
# 1️⃣4️⃣  ERROR HANDLING
# --------------------------------------------------------------
"""
from flask import Flask, render_template

app = Flask(__name__)

@app.errorhandler(404)
def not_found(error):
    return render_template('404.html', error=error), 404

@app.errorhandler(500)
def internal_error(error):
    # Log the exception here (e.g., to Sentry)
    return render_template('500.html'), 500

# Custom abort with JSON response
from flask import abort, jsonify

@app.route('/secret')
def secret():
    abort(403)   # will invoke the 403 errorhandler if defined
"""
# --------------------------------------------------------------
# 1️⃣5️⃣  TESTING (Flask‑Testing / pytest)
# --------------------------------------------------------------
"""
# pip install Flask-Testing pytest
# -----------------------------------------------------------------
# tests/test_basic.py
# -----------------------------------------------------------------
import json
import pytest
from myapp import create_app

@pytest.fixture
def client():
    app = create_app()
    app.config['TESTING'] = True
    with app.test_client() as client:
        yield client

def test_index(client):
    resp = client.get('/')
    assert resp.status_code == 200
    assert b'Hello' in resp.data

def test_api_create_and_get(client):
    # create
    payload = {'id':'42', 'name':'The Answer'}
    resp = client.post('/api/items', json=payload)
    assert resp.status_code == 201
    # retrieve
    resp = client.get('/api/items/42')
    assert resp.status_code == 200
    data = resp.get_json()
    assert data['name'] == 'The Answer'
"""
# --------------------------------------------------------------
# 1️⃣6️⃣  DEPLOYMENT
# --------------------------------------------------------------
"""
# Production recommendations
# 1. Use a WSGI server – gunicorn (Unix) or waitress (Windows)
#    gunicorn -w 4 -b 0.0.0.0:8000 myapp:app
#
# 2. Serve static files via a real web server (nginx, Apache)
#    Example nginx config fragment:
#    ------------------------------------------------
#    server {
#        listen 80;
#        server_name example.com;
#
#        location /static/ {
#            alias /path/to/myapp/static/;
#        }
#
#        location / {
#            proxy_pass http://127.0.0.1:8000;
#            proxy_set_header Host $host;
#            proxy_set_header X-Real-IP $remote_addr;
#            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
#            proxy_set_header X-Forwarded-Proto $scheme;
#        }
#    }
#    ------------------------------------------------
#
# 3. Environment variables for secrets (DATABASE_URL, SECRET_KEY, …)
# 4. Enable HTTPS (Let’s Encrypt certbot) and set `SESSION_COOKIE_SECURE=True`.
# 5. Optional: Containerise with Docker
# ------------------------------------------------
# Dockerfile (minimal)
# ------------------------------------------------
# FROM python:3.12-slim
# WORKDIR /app
# COPY requirements.txt .
# RUN pip install --no-cache-dir -r requirements.txt
# COPY . .
# ENV FLASK_APP=run.py
# CMD ["gunicorn", "-w", "4", "-b", "0.0.0.0:8000", "run:app"]
# ------------------------------------------------
"""
# --------------------------------------------------------------
# 1️⃣7️⃣  COMMON PITFALLS & CORRECTIONS (from the docs)
# --------------------------------------------------------------
# 1️⃣  Forgetting `app.run(debug=True)` in development → changes
#    don’t auto‑reload. Use `flask run --reload` or set `debug=True`.

# 2️⃣  Using mutable default arguments in view functions
#    (e.g., `def foo(data=[]): …`) → shared list across requests.
#    Replace with `None` and initialise inside the function.

# 3️⃣  Not setting `SECRET_KEY` → CSRF protection and sessions break.
#    Always configure a strong, random secret (e.g. via `os.urandom(24)`).

# 4️⃣  Returning plain strings instead of proper Response objects
#    when you need custom headers/status codes. Use `make_response`.

# 5️⃣  Database sessions not committed → data never saved.
#    Call `db.session.commit()` or use `session.flush()`.

# 6️⃣  Circular imports between app, models, and blueprints.
#    Use Flask application factories (`create_app`) and import inside functions.

# 7️⃣  Running `flask db upgrade` before `flask db init` will error.
#    Follow the proper migration workflow (init → migrate → upgrade).

# 8️⃣  Large payloads > 1 MB may hit default request size limit.
#    Increase `app.config['MAX_CONTENT_LENGTH']` accordingly.

# 9️⃣  Using `request.form` for JSON payloads – it will be empty.
#    Use `request.get_json()` or `request.json`.

# 🔟  Forgetting `@login_required` on protected views → unauthorized access.
#    Decorate each view that needs authentication.

# --------------------------------------------------------------
# 1️⃣8️⃣  FULL MINIMAL APPLICATION (everything together)
# --------------------------------------------------------------
"""
# -----------------------------------------------------------------
# run.py – a single‑file demo app with DB, auth, templates & API
# -----------------------------------------------------------------
from flask import Flask, render_template, redirect, url_for, flash, request, jsonify, abort
from flask_sqlalchemy import SQLAlchemy
from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, SubmitField, BooleanField
from wtforms.validators import DataRequired, Email, Length, EqualTo
from flask_login import LoginManager, UserMixin, login_user, logout_user, login_required, current_user
from werkzeug.security import generate_password_hash, check_password_hash

# ---- App & extensions ------------------------------------------------
app = Flask(__name__)
app.config.update(
    SECRET_KEY='replace‑with‑secure‑random',
    SQLALCHEMY_DATABASE_URI='sqlite:///demo.db',
    SQLALCHEMY_TRACK_MODIFICATIONS=False,
)

db = SQLAlchemy(app)
login_manager = LoginManager(app)
login_manager.login_view = 'login'

# ---- Models ---------------------------------------------------------
class User(UserMixin, db.Model):
    id       = db.Column(db.Integer, primary_key=True)
    email    = db.Column(db.String(120), unique=True, nullable=False)
    pwd_hash = db.Column(db.String(128), nullable=False)

    def set_password(self, pwd):
        self.pwd_hash = generate_password_hash(pwd)

    def check_password(self, pwd):
        return check_password_hash(self.pwd_hash, pwd)

# ---- Forms ----------------------------------------------------------
class LoginForm(FlaskForm):
    email    = StringField('Email', validators=[DataRequired(), Email()])
    password = PasswordField('Password', validators=[DataRequired()])
    submit   = SubmitField('Login')

class RegisterForm(FlaskForm):
    email    = StringField('Email', validators=[DataRequired(), Email()])
    password = PasswordField('Password', validators=[DataRequired(), Length(min=6)])
    confirm  = PasswordField('Confirm', validators=[DataRequired(),
                                                    EqualTo('password')])
    submit   = SubmitField('Register')

# ---- User loader ----------------------------------------------------
@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

# ---- Routes ---------------------------------------------------------
@app.route('/')
def index():
    return render_template('index.html')

@app.route('/register', methods=['GET', 'POST'])
def register():
    form = RegisterForm()
    if form.validate_on_submit():
        if User.query.filter_by(email=form.email.data).first():
            flash('Email already registered', 'danger')
            return redirect(url_for('register'))
        user = User(email=form.email.data)
        user.set_password(form.password.data)
        db.session.add(user)
        db.session.commit()
        flash('Account created – please log in', 'success')
        return redirect(url_for('login'))
    return render_template('register.html', form=form)

@app.route('/login', methods=['GET', 'POST'])
def login():
    form = LoginForm()
    if form.validate_on_submit():
        user = User.query.filter_by(email=form.email.data).first()
        if user and user.check_password(form.password.data):
            login_user(user)
            flash('Logged in', 'success')
            return redirect(url_for('dashboard'))
        flash('Invalid credentials', 'danger')
    return render_template('login.html', form=form)

@app.route('/logout')
@login_required
def logout():
    logout_user()
    flash('Logged out', 'info')
    return redirect(url_for('index'))

@app.route('/dashboard')
@login_required
def dashboard():
    return f'Welcome {current_user.email}! <a href="{url_for("logout")}">Logout</a>'

# ---- Simple JSON API -------------------------------------------------
@app.route('/api/items', methods=['GET', 'POST'])
@login_required
def api_items():
    if request.method == 'POST':
        data = request.get_json(force=True)
        # Here you would store `data` in the DB.
        return jsonify(data), 201
    # GET – return static list for demo
    return jsonify([{'id':1,'name':'Demo'}])

# ---- Error handlers --------------------------------------------------
@app.errorhandler(404)
def not_found(e):
    return render_template('404.html'), 404

@app.errorhandler(500)
def server_error(e):
    return render_template('500.html'), 500

# ---- Command line entry point ----------------------------------------
if __name__ == '__main__':
    # Create tables on first run
    with app.app_context():
        db.create_all()
    app.run(debug=True)
# -----------------------------------------------------------------
# templates/index.html (minimal)
# -----------------------------------------------------------------
# {% extends "base.html" %}
# {% block title %}Home{% endblock %}
# {% block content %}
#   <h1>Welcome to the Flask demo</h1>
#   {% if current_user.is_authenticated %}
#       <p>Hello {{ current_user.email }}! <a href="{{ url_for('dashboard') }}">Dashboard</a></p>
#       <p><a href="{{ url_for('logout') }}">Logout</a></p>
#   {% else %}
#       <p><a href="{{ url_for('login') }}">Login</a> | <a href="{{ url_for('register') }}">Register</a></p>
#   {% endif %}
# {% endblock %}
# -----------------------------------------------------------------
# templates/base.html (layout)
# -----------------------------------------------------------------
# <!doctype html>
# <html lang="en">
#   <head>
#     <meta charset="utf-8">
#     <title>{% block title %}{% endblock %} – Flask Demo</title>
#     <link rel="stylesheet" href="{{ url_for('static', filename='style.css') }}">
#   </head>
#   <body>
#     {% with messages = get_flashed_messages(with_categories=True) %}
#       {% if messages %}
#         <ul class="flashes">
#           {% for category, msg in messages %}
#             <li class="{{ category }}">{{ msg }}</li>
#           {% endfor %}
#         </ul>
#       {% endif %}
#     {% endwith %}
#     {% block content %}{% endblock %}
#   </body>
# </html>
# -----------------------------------------------------------------
# Run the demo:
#   python run.py
# Visit http://127.0.0.1:5000/
"""
# ======================================================================
# END OF TUTORIAL – every core Flask concept is covered.
# Mix, extend and customize to build real‑world web applications.
# ======================================================================
```