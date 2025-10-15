# Django — Practical Tutorial (with Table of Contents)

A concise, copy‑pasteable guide to building web apps with [Django](https://docs.djangoproject.com/en/stable/). It covers modern defaults, typical patterns, and small utilities you can drop into projects quickly.

## Table of Contents

- [Overview](#overview)
- [Install & New Project](#install--new-project)
- [Run Dev Server](#run-dev-server)
- [App Structure & Conventions](#app-structure--conventions)
- [Settings Essentials](#settings-essentials)
- [URL Routing](#url-routing)
- [Views & Templates](#views--templates)
- [Static & Media Files](#static--media-files)
- [Models & Migrations](#models--migrations)
- [Admin](#admin)
- [Forms & CSRF](#forms--csrf)
- [QuerySet Cheatsheet](#queryset-cheatsheet)
- [Auth & Users](#auth--users)
- [Django REST Framework (optional)](#django-rest-framework-optional)
- [Testing](#testing)
- [Management Commands](#management-commands)
- [Environment Variables](#environment-variables)
- [Deployment Notes](#deployment-notes)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

Django is a batteries‑included framework with ORM, templating, routing, admin, auth, and more. Typical structure: project → apps. Apps are modular units (models/views/templates/static/tests).

## Install & New Project

```bash
python -m pip install "django>=5.0"

# create folder and project
mkdir mysite && cd mysite
python -m django startproject config .  # creates config/ manage.py in current dir

# create first app
python manage.py startapp blog
```

## Run Dev Server

```bash
python manage.py migrate
python manage.py runserver
```

Visit [http://127.0.0.1:8000/](http://127.0.0.1:8000/) (development server).

## App Structure & Conventions

```text
mysite/
  manage.py
  config/
    __init__.py
    settings.py
    urls.py
    wsgi.py
    asgi.py
  blog/
    __init__.py
    admin.py
    apps.py
    models.py
    views.py
    urls.py            # you create this
    templates/blog/    # templates live here
    static/blog/       # app static assets here
    tests.py
```

## Settings Essentials

Edit `config/settings.py`:

```python
from pathlib import Path
import os

BASE_DIR = Path(__file__).resolve().parent.parent

SECRET_KEY = os.getenv("DJANGO_SECRET_KEY", "dev-insecure-change-me")
DEBUG = os.getenv("DJANGO_DEBUG", "1") == "1"
ALLOWED_HOSTS = os.getenv("DJANGO_ALLOWED_HOSTS", "").split() or ["127.0.0.1", "localhost"]

INSTALLED_APPS = [
    "django.contrib.admin",
    "django.contrib.auth",
    "django.contrib.contenttypes",
    "django.contrib.sessions",
    "django.contrib.messages",
    "django.contrib.staticfiles",
    # your apps
    "blog",
]

TEMPLATES = [
    {
        "BACKEND": "django.template.backends.django.DjangoTemplates",
        "DIRS": [BASE_DIR / "templates"],  # global templates dir (optional)
        "APP_DIRS": True,
        "OPTIONS": {"context_processors": [
            "django.template.context_processors.debug",
            "django.template.context_processors.request",
            "django.contrib.auth.context_processors.auth",
            "django.contrib.messages.context_processors.messages",
        ]},
    }
]

STATIC_URL = "static/"
STATICFILES_DIRS = [BASE_DIR / "static"]  # optional shared static dir
STATIC_ROOT = BASE_DIR / "staticfiles"     # for collectstatic (deploy)

MEDIA_URL = "media/"
MEDIA_ROOT = BASE_DIR / "media"
```

## URL Routing

Project `config/urls.py`:

```python
from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    path("admin/", admin.site.urls),
    path("", include("blog.urls")),
]

if settings.DEBUG:  # serve media files in dev
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
```

App `blog/urls.py`:

```python
from django.urls import path
from . import views

urlpatterns = [
    path("", views.home, name="home"),
    path("posts/", views.post_list, name="post_list"),
    path("posts/<int:pk>/", views.post_detail, name="post_detail"),
]
```

## Views & Templates

`blog/views.py`:

```python
from django.http import HttpRequest, HttpResponse
from django.shortcuts import render, get_object_or_404
from .models import Post

def home(request: HttpRequest) -> HttpResponse:
    return render(request, "blog/home.html", {})

def post_list(request: HttpRequest) -> HttpResponse:
    posts = Post.objects.order_by("-created_at")
    return render(request, "blog/post_list.html", {"posts": posts})

def post_detail(request: HttpRequest, pk: int) -> HttpResponse:
    post = get_object_or_404(Post, pk=pk)
    return render(request, "blog/post_detail.html", {"post": post})
```

Templates:

```text
blog/templates/blog/
  base.html
  home.html
  post_list.html
  post_detail.html
```

`blog/templates/blog/base.html`:

```html
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{% block title %}My Site{% endblock %}</title>
    {% load static %}
    <link rel="stylesheet" href="{% static 'blog/style.css' %}" />
  </head>
  <body>
    <header><h1><a href="/">My Site</a></h1></header>
    <main>
      {% block content %}{% endblock %}
    </main>
  </body>
</html>
```

`blog/templates/blog/post_list.html`:

```html
{% extends 'blog/base.html' %}
{% block title %}Posts · My Site{% endblock %}
{% block content %}
  <h2>Posts</h2>
  <ul>
    {% for p in posts %}
      <li><a href="{% url 'post_detail' p.pk %}">{{ p.title }}</a></li>
    {% empty %}
      <li>No posts yet.</li>
    {% endfor %}
  </ul>
{% endblock %}
```

## Static & Media Files

- Put app‑specific assets under `blog/static/blog/` (e.g., `style.css`).
- In templates, `{% load static %}` and use `{% static 'blog/style.css' %}`.
- For user uploads, use `MEDIA_ROOT`/`MEDIA_URL` and a `FileField`/`ImageField`.

Example model field:

```python
from django.db import models

class Photo(models.Model):
    image = models.ImageField(upload_to="photos/%Y/%m/%d/")
```

## Models & Migrations

`blog/models.py`:

```python
from django.db import models

class Post(models.Model):
    title = models.CharField(max_length=200)
    slug = models.SlugField(unique=True)
    body = models.TextField()
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ["-created_at"]

    def __str__(self) -> str:
        return self.title
```

Apply migrations:

```bash
python manage.py makemigrations
python manage.py migrate
```

## Admin

`blog/admin.py`:

```python
from django.contrib import admin
from .models import Post

@admin.register(Post)
class PostAdmin(admin.ModelAdmin):
    list_display = ("id", "title", "slug", "created_at")
    prepopulated_fields = {"slug": ("title",)}
    search_fields = ("title", "body")
```

Create superuser and login at `/admin/`:

```bash
python manage.py createsuperuser
```

## Forms & CSRF

`blog/forms.py`:

```python
from django import forms
from .models import Post

class PostForm(forms.ModelForm):
    class Meta:
        model = Post
        fields = ["title", "slug", "body"]
```

Create a view to handle create form:

```python
# blog/views.py
from django.shortcuts import redirect
from .forms import PostForm

# ...existing views...

def post_create(request):
    if request.method == "POST":
        form = PostForm(request.POST)
        if form.is_valid():
            form.save()
            return redirect("post_list")
    else:
        form = PostForm()
    return render(request, "blog/post_form.html", {"form": form})
```

Template with CSRF token `blog/templates/blog/post_form.html`:

```html
{% extends 'blog/base.html' %}
{% block content %}
  <h2>New Post</h2>
  <form method="post">
    {% csrf_token %}
    {{ form.as_p }}
    <button type="submit">Save</button>
  </form>
{% endblock %}
```

Add URL:

```python
# blog/urls.py
urlpatterns = [
    # ...
    path("posts/new/", views.post_create, name="post_create"),
]
```

## QuerySet Cheatsheet

```python
from blog.models import Post

# create
p = Post.objects.create(title="Hello", slug="hello", body="...")

# get / filter
one = Post.objects.get(pk=1)
qs = Post.objects.filter(title__icontains="hello")
exists = Post.objects.filter(slug="x").exists()
count = Post.objects.count()

# order, slice
recent = Post.objects.order_by("-created_at")[:10]

# update/delete
Post.objects.filter(pk=1).update(title="Hi")
Post.objects.filter(pk=2).delete()

# select related (FK example)
# Post.objects.select_related("author").all()
```

## Auth & Users

Add `login_required` to protect views:

```python
from django.contrib.auth.decorators import login_required

@login_required
def dashboard(request):
    return render(request, "blog/dashboard.html")
```

Login/Logout URLs (use built‑in auth views):

```python
# config/urls.py
urlpatterns += [
    path("accounts/", include("django.contrib.auth.urls")),
]
```

This gives you `/accounts/login/`, `/accounts/logout/`, etc. Provide templates under `registration/` (e.g., `registration/login.html`).

## Django REST Framework (optional)

```bash
python -m pip install djangorestframework
```

`config/settings.py`:

```python
INSTALLED_APPS += ["rest_framework"]
```

A minimal API:

```python
# blog/serializers.py
from rest_framework import serializers
from .models import Post

class PostSerializer(serializers.ModelSerializer):
    class Meta:
        model = Post
        fields = ["id", "title", "slug", "body", "created_at"]
```

```python
# blog/api.py
from rest_framework.viewsets import ModelViewSet
from .models import Post
from .serializers import PostSerializer

class PostViewSet(ModelViewSet):
    queryset = Post.objects.all()
    serializer_class = PostSerializer
```

```python
# blog/urls.py
from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .api import PostViewSet

router = DefaultRouter()
router.register(r"posts", PostViewSet)

urlpatterns = [
    # ...existing urls...
    path("api/", include(router.urls)),
]
```

## Testing

```python
# blog/tests.py
from django.test import TestCase
from django.urls import reverse
from .models import Post

class BlogTests(TestCase):
    def test_post_list_empty(self):
        resp = self.client.get(reverse("post_list"))
        self.assertEqual(resp.status_code, 200)
        self.assertContains(resp, "No posts yet")

    def test_post_detail_404(self):
        resp = self.client.get(reverse("post_detail", args=[999]))
        self.assertEqual(resp.status_code, 404)
```

Run tests:

```bash
python manage.py test
```

## Management Commands

```python
# blog/management/commands/hello.py
from django.core.management.base import BaseCommand

class Command(BaseCommand):
    help = "Say hello"

    def handle(self, *args, **options):
        self.stdout.write("Hello from custom command!")
```

Use:

```bash
python manage.py hello
```

## Environment Variables

- Read via `os.getenv()` in `settings.py` (as shown).
- Optional: load from `.env` using [python-dotenv](https://pypi.org/project/python-dotenv/) or [django-environ](https://django-environ.readthedocs.io/en/latest/).

Example `.env`:

```text
DJANGO_SECRET_KEY=replace-me
DJANGO_DEBUG=1
DJANGO_ALLOWED_HOSTS=127.0.0.1 localhost
```

## Deployment Notes

- `DEBUG = False`, set `ALLOWED_HOSTS` properly.
- Run `python manage.py collectstatic`.
- Serve static files with your web server or [WhiteNoise](https://whitenoise.evans.io/en/stable/).
- Use a production DB (PostgreSQL recommended) and a WSGI/ASGI server (gunicorn/uvicorn+daphne).

## Troubleshooting

- App not found: check `INSTALLED_APPS` and `blog.apps.BlogConfig` if customized.
- Template not found: ensure file path and TEMPLATES `APP_DIRS=True` or template DIRS include.
- Static not loading: verify `{% load static %}` and files under `static/` or `app/static/app/`.
- 403 on POST: ensure `{% csrf_token %}` is included in forms.

## References

- Official docs: [https://docs.djangoproject.com/en/stable/](https://docs.djangoproject.com/en/stable/)
- Tutorial: [https://docs.djangoproject.com/en/stable/intro/](https://docs.djangoproject.com/en/stable/intro/)

---

Copy any block into your project and adapt paths/names as needed. Happy shipping!
