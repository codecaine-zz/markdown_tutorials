# Jinja2 — Practical Tutorial (with Table of Contents)

Jinja2 is a fast, expressive templating engine for Python. It powers Flask and many other projects. This guide is copy‑pasteable and focuses on practical usage, security, and performance.

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start](#quick-start)
- [Environment & Loaders](#environment--loaders)
- [Template Syntax Basics](#template-syntax-basics)
- [Control Structures](#control-structures)
- [Filters & Tests](#filters--tests)
- [Template Inheritance & Macros](#template-inheritance--macros)
- [Includes & Imports](#includes--imports)
- [Autoescaping & Security](#autoescaping--security)
- [Async Rendering](#async-rendering)
- [Internationalization](#internationalization)
- [Reusable utilities (copy-paste)](#reusable-utilities-copy-paste)
- [Common web patterns (secure)](#common-web-patterns-secure)
- [Performance Tips](#performance-tips)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

Jinja2 renders text files (HTML, emails, configs) from templates and data. It supports expressions, filters, control flow, inheritance, macros, i18n, and autoescaping.

## Install

```bash
python -m pip install jinja2 markupsafe
```

## Quick Start

```python
from jinja2 import Environment, DictLoader

env = Environment(loader=DictLoader({
    'hello.html': 'Hello, {{ name }}!'
}))

tmpl = env.get_template('hello.html')
print(tmpl.render(name='World'))
```

## Environment & Loaders

```python
from jinja2 import Environment, FileSystemLoader, ChoiceLoader, PackageLoader, select_autoescape

template_dirs = ["./templates", "./more_templates"]

env = Environment(
    loader=ChoiceLoader([
        FileSystemLoader(template_dirs),
        PackageLoader("my_package", "templates"),  # optional
    ]),
    autoescape=select_autoescape(["html", "htm", "xml", "xhtml", "j2"]),
    trim_blocks=True,
    lstrip_blocks=True,
)

tmpl = env.get_template('page.html')
html = tmpl.render(title='Home', items=[1,2,3])
```

## Template Syntax Basics

```jinja
{# comment #}
Hello {{ user.full_name }}

Escaped: {{ '<b>bold</b>' }}
Unescaped (be careful): {{ html|safe }}

Upper: {{ title|upper }}
Default: {{ missing|default('N/A') }}
```

## Control Structures

```jinja
{% if items %}
  <ul>
  {% for i in items %}
    <li>{{ loop.index }}: {{ i }}</li>
  {% endfor %}
  </ul>
{% else %}
  <p>No items</p>
{% endif %}

{% set total = prices|sum %}
Total: {{ total }}
```

## Filters & Tests

```jinja
Len: {{ items|length }}
Join: {{ items|join(', ') }}
Date: {{ ts|datetimeformat('%Y-%m-%d') }}

{% if user.email is string %}email is a string{% endif %}
{% if value is number %}value is numeric{% endif %}
```

## Template Inheritance & Macros

Base (`base.html`):

```jinja
<!doctype html>
<title>{% block title %}Site{% endblock %}</title>
<main>
  {% block content %}{% endblock %}
</main>
```

Child (`page.html`):

```jinja
{% extends 'base.html' %}
{% block title %}{{ super() }} · Page{% endblock %}
{% block content %}
  <h1>{{ headline }}</h1>
{% endblock %}
```

Macros (`macros.html`):

```jinja
{% macro badge(text, kind='info') %}
  <span class="badge badge-{{ kind }}">{{ text }}</span>
{% endmacro %}
```

Use macros:

```jinja
{% from 'macros.html' import badge %}
{{ badge('New', 'success') }}
```

## Includes & Imports

```jinja
{% include 'partials/header.html' %}
{% import 'macros.html' as m %}
{{ m.badge('Hi') }}
```

## Autoescaping & Security

```python
from markupsafe import Markup, escape

safe_html = Markup('<strong>safe</strong>')       # trusted
escaped = escape('<b>not safe</b>')               # becomes &lt;b&gt;...
```

Turn on autoescape (recommended for HTML/XML) using `select_autoescape` as above. Avoid marking user input as `safe`.

### Best practices

- Always enable autoescape for HTML/XML templates via `select_autoescape`.
- In development, use `StrictUndefined` to catch missing variables early.
- Avoid `|safe` on user-provided data. Only mark trusted, pre-sanitized content as safe.
- Don’t render untrusted templates; use `SandboxedEnvironment` when templates come from users.
- Whitelist template names to prevent path traversal and unintended templates.
- Embed JSON safely using the `tojson` filter and a CSP nonce.
- Prefer URL builders and `urlencode` for query parameters.

Safe JSON in a script tag with CSP nonce:

```jinja
<script nonce="{{ csp_nonce() }}">
    const DATA = {{ data|tojson }};
    // ...
</script>
```

Whitelist template rendering (Python):

```python
ALLOWED_TEMPLATES = {
        'base.html', 'page.html', 'macros.html',
}

html = render_template_whitelisted(env, 'page.html', context, allowed=ALLOWED_TEMPLATES)
```

## Async Rendering

```python
from jinja2 import Environment, DictLoader
import asyncio

env = Environment(
    loader=DictLoader({'a.html': "Hello {{ name }}"}),
    enable_async=True,
)

async def main():
    tmpl = env.get_template('a.html')
    print(await tmpl.render_async(name='Async'))

asyncio.run(main())
```

## Internationalization

Jinja integrates with gettext. Minimal example using a callable:

```python
from jinja2 import Environment, DictLoader

def _(s: str) -> str:
    # Replace with gettext: gettext.gettext(s)
    return s

env = Environment(loader=DictLoader({'t.html': "{{ _('Hello') }}"}))
env.globals['_'] = _
print(env.get_template('t.html').render())
```

## Reusable utilities (copy-paste)

Drop-in helpers for robust Jinja2 setup. Copy the blocks you need.

### Environment factories and caching

```python
from __future__ import annotations

from functools import lru_cache
from typing import Iterable, Mapping, Any
from jinja2 import (
    Environment, FileSystemLoader, ChoiceLoader, DictLoader,
    PackageLoader, select_autoescape, StrictUndefined
)
from jinja2.bccache import FileSystemBytecodeCache
from markupsafe import Markup, escape
import json


def make_env(
    template_dirs: Iterable[str] | None = None,
    packages: Iterable[tuple[str, str]] | None = None,  # (package_name, template_subdir)
    bytecode_cache_dir: str | None = None,
    *,
    strict: bool = True,
    autoescape_exts: Iterable[str] = ("html", "htm", "xml", "xhtml", "j2"),
    trim_blocks: bool = True,
    lstrip_blocks: bool = True,
) -> Environment:
    loaders = []
    if template_dirs:
        loaders.append(FileSystemLoader(list(template_dirs)))
    if packages:
        for pkg, subdir in packages:
            loaders.append(PackageLoader(pkg, subdir))
    if not loaders:
        loaders.append(DictLoader({}))

    env = Environment(
        loader=ChoiceLoader(loaders) if len(loaders) > 1 else loaders[0],
        autoescape=select_autoescape(list(autoescape_exts)),
        trim_blocks=trim_blocks,
        lstrip_blocks=lstrip_blocks,
        undefined=StrictUndefined if strict else None,
        auto_reload=True,
        enable_async=True,
    )

    if bytecode_cache_dir:
        env.bytecode_cache = FileSystemBytecodeCache(directory=bytecode_cache_dir)

    # Default filters
    env.filters.setdefault('tojson', lambda x: Markup(json.dumps(x)))
    env.filters.setdefault('datetimeformat', datetimeformat)
    env.filters.setdefault('nl2br', nl2br)
    env.filters.setdefault('truncate_words', truncate_words)
    env.filters.setdefault('pluralize', pluralize)
    env.filters.setdefault('urlencode', urlencode)

    # Useful globals
    env.globals.setdefault('static_url', static_url)
    env.globals.setdefault('build_url', build_url)
    env.globals.setdefault('csp_nonce', default_nonce)
    env.globals.setdefault('version', lambda: "1.0.0")
    return env


@lru_cache(maxsize=8)
def get_env_cached(*, key: str = "default") -> Environment:
    """Return a cached Environment keyed by name (customize as needed)."""
    return make_env(template_dirs=("templates",), bytecode_cache_dir=".jinja_cache")


def attach_csp_nonce(env: Environment, get_nonce):
    """Attach a CSP nonce provider callable to env.globals (per-request if provided in context)."""
    env.globals['csp_nonce'] = get_nonce
    return env
```

### Convenience renderers

```python
from typing import Mapping, Any
from jinja2 import TemplateNotFound


def render_template(env: Environment, template_name: str, context: Mapping[str, Any] | None = None) -> str:
    try:
        tmpl = env.get_template(template_name)
    except TemplateNotFound as e:
        raise FileNotFoundError(f"Template not found: {template_name}") from e
    return tmpl.render(**(context or {}))


async def render_template_async(env: Environment, template_name: str, context: Mapping[str, Any] | None = None) -> str:
    tmpl = env.get_template(template_name)
    return await tmpl.render_async(**(context or {}))


def render_string(env: Environment, source: str, context: Mapping[str, Any] | None = None) -> str:
    tmpl = env.from_string(source)
    return tmpl.render(**(context or {}))


def render_template_whitelisted(env: Environment, template_name: str, context: Mapping[str, Any] | None = None,
                                *, allowed: set[str] | None = None) -> str:
    """Render only if template is in an allowed whitelist.
    Prevents path traversal or rendering unintended templates.
    """
    if allowed is not None and template_name not in allowed:
        raise PermissionError(f"Template not allowed: {template_name}")
    return render_template(env, template_name, context)
```

### Handy filters and utilities

```python
import html
import re
from datetime import datetime
from urllib.parse import urlencode as _urlencode, urlsplit, urlunsplit, parse_qsl
import os
import base64


def datetimeformat(value: datetime | str, fmt: str = "%Y-%m-%d %H:%M") -> str:
    if isinstance(value, str):
        try:
            value = datetime.fromisoformat(value)
        except Exception:
            return value  # fallback
    return value.strftime(fmt)


def nl2br(text: str) -> Markup:
    return Markup("<br>\n").join(Markup.escape(text).splitlines())


def truncate_words(s: str, num: int = 25, end: str = '…') -> str:
    parts = s.split()
    return s if len(parts) <= num else " ".join(parts[:num]) + end


def pluralize(n: int, singular: str = '', plural: str = 's') -> str:
    return singular if abs(n) == 1 else plural


def static_url(path: str, version: str | None = None, base: str = '/static/') -> str:
    if not path.startswith('/'):
        path = base.rstrip('/') + '/' + path
    return f"{path}?v={version}" if version else path


def urlencode(value) -> str:
    """URL-encode strings or mapping/sequence of pairs."""
    if isinstance(value, str):
        return _urlencode({'v': value})[2:]  # drop 'v='
    return _urlencode(value, doseq=True)


def build_url(base: str, params: dict[str, Any] | None = None,
              *, preserve: set[str] | None = None, current_query: str | None = None) -> str:
    """Build URL with merged query parameters.
    - preserve: set of keys to keep from current_query
    """
    scheme, netloc, path, query, frag = urlsplit(base)
    existing = dict(parse_qsl(current_query or query, keep_blank_values=True))
    merged = {**({k: existing[k] for k in (preserve or set()) if k in existing}), **(params or {})}
    return urlunsplit((scheme, netloc, path, _urlencode(merged, doseq=True), frag))


def default_nonce() -> str:
    """Generate a random base64 CSP nonce. Replace per-request in frameworks."""
    return base64.b64encode(os.urandom(16)).decode('ascii')
```

### Register filters/tests/globals in bulk

```python
def register_all(env: Environment, *, filters: dict[str, Any] | None = None,
                 tests: dict[str, Any] | None = None,
                 globals: dict[str, Any] | None = None) -> Environment:
    for k, v in (filters or {}).items():
        env.filters[k] = v
    for k, v in (tests or {}).items():
        env.tests[k] = v
    for k, v in (globals or {}).items():
        env.globals[k] = v
    return env


# Example usage
env = make_env(template_dirs=("templates",))
register_all(env, filters={
    'tojson': lambda x: Markup(json.dumps(x)),
    'datetimeformat': datetimeformat,
})
```

### Sandboxed environment (for untrusted templates)

```python
from jinja2.sandbox import SandboxedEnvironment

def make_sandboxed_env(**kws) -> SandboxedEnvironment:
    env = SandboxedEnvironment(
        autoescape=select_autoescape(["html", "xml"]),
        undefined=StrictUndefined,
        **kws
    )
    env.filters['tojson'] = lambda x: Markup(json.dumps(x))
    return env
```

### In-memory templates and partials

```python
from jinja2 import DictLoader, ChoiceLoader

def add_in_memory_templates(env: Environment, mapping: dict[str, str]) -> None:
    new_loader = DictLoader(mapping)
    # Prepend to search in-memory first
    env.loader = ChoiceLoader([new_loader, env.loader])
```

## Common web patterns (secure)

Practical, secure snippets most apps use.

### Base layout with CSP nonce and asset helpers

```jinja
{# templates/base.html #}
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{% block title %}My Site{% endblock %}</title>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="{{ static_url('css/app.css', version()) }}" nonce="{{ csp_nonce() }}" />
        {% block head %}{% endblock %}
    </head>
    <body>
        {% include 'partials/flash.html' %}
        <main>{% block content %}{% endblock %}</main>
        <script src="{{ static_url('js/app.js', version()) }}" nonce="{{ csp_nonce() }}" defer></script>
        {% block scripts %}{% endblock %}
    </body>
  
</html>
```

### CSRF-protected form (server generates csrf_token)

```jinja
{# templates/auth/login.html #}
{% extends 'base.html' %}
{% block title %}Login · {{ super() }}{% endblock %}
{% block content %}
    <h1>Login</h1>
    <form method="post" action="/login">
        <input type="hidden" name="csrf_token" value="{{ csrf_token }}" />
        <label>Email <input name="email" type="email" required value="{{ email|default('') }}" /></label>
        <label>Password <input name="password" type="password" required /></label>
        <button type="submit">Sign in</button>
    </form>
{% endblock %}
```

Server side outline:

```python
def get_csrf_token(session) -> str:
        token = base64.b64encode(os.urandom(24)).decode('ascii')
        session['csrf'] = token
        return token


def verify_csrf(session, token: str) -> bool:
        return token and session.get('csrf') == token
```

### Pagination macro preserving filters

```jinja
{# templates/macros/pager.html #}
{% macro pager(page, per_page, total, base_url, query) %}
    {% set pages = (total // per_page) + (1 if total % per_page else 0) %}
    {% if pages > 1 %}
    <nav class="pager" aria-label="Pagination">
        <ul>
            {% for p in range(1, pages + 1) %}
                {% set href = build_url(base_url, {'page': p}, preserve={'q', 'sort'}, current_query=query) %}
                <li class="{{ 'active' if p == page else '' }}">
                    <a href="{{ href }}">{{ p }}</a>
                </li>
            {% endfor %}
        </ul>
    </nav>
    {% endif %}
{% endmacro %}
```

Usage:

```jinja
{% from 'macros/pager.html' import pager %}
{{ pager(page, per_page, total, request.path, request.query_string|safe) }}
```

### Flash messages partial

```jinja
{# templates/partials/flash.html #}
{% if flashes %}
    <div class="flashes">
        {% for msg in flashes %}
            <div class="flash flash-{{ msg.category|default('info') }}">{{ msg.text }}</div>
        {% endfor %}
    </div>
{% endif %}
```

### Safe HTML snippets from trusted source

```jinja
{# Only mark trusted content as safe #}
<div class="content">{{ trusted_html|safe }}</div>
```

### Email templates (HTML + plain text)

```jinja
{# templates/email/welcome.txt #}
Hello {{ user.full_name }},

Welcome to our service.
```

```jinja
{# templates/email/welcome.html #}
<!doctype html>
<html>
    <body>
        <p>Hello {{ user.full_name }},</p>
        <p>Welcome to our service.</p>
    </body>
</html>
```

### Example: end-to-end HTML rendering

```python
env = make_env(template_dirs=("templates",), bytecode_cache_dir=".jinja_cache")

ctx = {
    'title': 'Dashboard',
    'user': {'full_name': 'Ada Lovelace'},
    'items': ["alpha", "beta", "gamma"],
}

html = render_template(env, 'page.html', ctx)
print(html)
```

## Performance Tips

- Use a bytecode cache (e.g., `FileSystemBytecodeCache`) in production.
- Enable `trim_blocks` and `lstrip_blocks` to reduce whitespace in HTML.
- Keep templates simple; push complex logic into Python helpers/filters.
- Cache or memoize expensive data preparation; pass results to templates.
- Prefer `ChoiceLoader` with a small, ordered set of loaders.

## Troubleshooting

- Template not found: check your loader search paths and filenames.
- Undefined errors: set `undefined=StrictUndefined` in development to catch typos early.
- Autoescape issues: ensure `select_autoescape` covers your file extensions.
- Async errors: set `enable_async=True` and call `render_async` when using `await` in templates.

## References

- Docs: [https://jinja.palletsprojects.com/](https://jinja.palletsprojects.com/)
- API: [https://jinja.palletsprojects.com/en/latest/api/](https://jinja.palletsprojects.com/en/latest/api/)
- Templates: [https://jinja.palletsprojects.com/en/latest/templates/](https://jinja.palletsprojects.com/en/latest/templates/)

---

Copy any block into your project and adapt. Render safely and quickly.
