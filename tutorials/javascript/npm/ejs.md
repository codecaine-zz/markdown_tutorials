# EJS — Practical Tutorial (with Table of Contents)

A concise, copy‑pasteable guide to [EJS (Embedded JavaScript Templates)](https://ejs.co/). It shows core templating syntax, Express integration, partials/layouts, helpers, and common patterns.

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start (Express)](#quick-start-express)
- [EJS Syntax Essentials](#ejs-syntax-essentials)
  - [Variables and escaping](#variables-and-escaping)
  - [Conditionals](#conditionals)
  - [Loops](#loops)
  - [Includes (partials)](#includes-partials)
  - [Layouts](#layouts)
- [Pass data from Express](#pass-data-from-express)
- [Partials as components](#partials-as-components)
- [Helpers (utilities) in views](#helpers-utilities-in-views)
- [Static assets](#static-assets)
- [CLI rendering (no Express)](#cli-rendering-no-express)
- [Email templates (Nodemailer)](#email-templates-nodemailer)
- [Caching and performance](#caching-and-performance)
- [Error handling and debugging](#error-handling-and-debugging)
- [Security notes](#security-notes)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

EJS is a simple templating language that lets you generate HTML with plain JavaScript. It supports:

- Interpolation with escaping (`<%= %>`) and raw output (`<%- %>`).
- Flow control: `if`, `for`, etc. with `<% %>`.
- Includes/partials for reuse.
- Works with Express, or standalone via the EJS module.

## Install

```bash
npm init -y
npm install ejs express
# optional helper packages
npm install express-ejs-layouts
```

## Quick Start (Express)

```javascript
// server.js
import express from 'express';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.get('/', (req, res) => {
  res.render('home', { title: 'Hello EJS', user: { name: 'Ada' } });
});

app.listen(3000, () => console.log('http://localhost:3000'));
```

`views/home.ejs`:

```ejs
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><%= title %></title>
  </head>
  <body>
    <h1>Welcome, <%= user.name %>!</h1>
  </body>
</html>
```

Run:

```bash
node server.js
```

## EJS Syntax Essentials

### Variables and escaping

- `<%= value %>`: HTML-escapes the value.
- `<%- value %>`: outputs raw HTML (no escaping).

```ejs
<p>Escaped: <%= user.bio %></p>
<p>Raw: <%- user.bio_html %></p>
```

### Conditionals

```ejs
<% if (user) { %>
  <p>Hello, <%= user.name %></p>
<% } else { %>
  <p>Please sign in.</p>
<% } %>
```

### Loops

```ejs
<ul>
  <% for (const item of items) { %>
    <li><%= item %></li>
  <% } %>
</ul>
```

### Includes (partials)

`views/partials/nav.ejs`:

```ejs
<nav>
  <a href="/">Home</a>
  <a href="/about">About</a>
</nav>
```

Use in a page:

```ejs
<%- include('partials/nav') %>
```

Data to includes can be passed by adding variables to the current scope or via `include('path', data)` (EJS v3+):

```ejs
<%- include('partials/card', { title: 'Card', body: 'Hello' }) %>
```

### Layouts

EJS does not have layouts built-in, but you can:

- Use `express-ejs-layouts` middleware, or
- Manually include a header and footer partial.

`express-ejs-layouts` example:

```javascript
// server.js (continued)
import expressLayouts from 'express-ejs-layouts';
app.use(expressLayouts);
app.set('layout', 'layouts/main'); // views/layouts/main.ejs
```

`views/layouts/main.ejs`:

```ejs
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><%= title || 'Site' %></title>
  </head>
  <body>
    <%- include('../partials/nav') %>
    <main>
      <%- body %>
    </main>
  </body>
</html>
```

`views/home.ejs` remains the page body:

```ejs
<h1>Home</h1>
<p>Welcome, <%= user.name %></p>
```

Manual header/footer include alternative:

```ejs
<%- include('partials/header', { title }) %>
<h1>Home</h1>
<%- include('partials/footer') %>
```

## Pass data from Express

```javascript
app.get('/products', (req, res) => {
  const products = [
    { id: 1, name: 'Pen', price: 1.99 },
    { id: 2, name: 'Book', price: 9.5 }
  ];
  res.render('products/list', { title: 'Products', products });
});
```

`views/products/list.ejs`:

```ejs
<h1><%= title %></h1>
<ul>
  <% for (const p of products) { %>
    <li>
      <a href="/products/<%= p.id %>"><%= p.name %></a>
      — $<%= p.price.toFixed(2) %>
    </li>
  <% } %>
</ul>
```

## Partials as components

`views/components/alert.ejs`:

```ejs
<div class="alert alert-<%= type %>">
  <% if (title) { %><strong><%= title %></strong> <% } %>
  <%= message %>
</div>
```

Use:

```ejs
<%- include('../components/alert', { type: 'info', title: 'Heads up', message: 'Saved!' }) %>
```

## Helpers (utilities) in views

Attach helpers to `res.locals` middleware or pass per render.

```javascript
// server.js
app.use((req, res, next) => {
  res.locals.currency = (n) => `$${Number(n).toFixed(2)}`;
  res.locals.capitalize = (s) => (s ? String(s)[0].toUpperCase() + String(s).slice(1) : '');
  next();
});
```

Use in EJS:

```ejs
<p>Price: <%= currency(product.price) %></p>
<p>Name: <%= capitalize(product.name) %></p>
```

## Static assets

```javascript
app.use('/static', express.static('public'));
```

In templates:

```ejs
<link rel="stylesheet" href="/static/styles.css" />
<script src="/static/app.js" defer></script>
```

## CLI rendering (no Express)

```javascript
// render.js
import ejs from 'ejs';
import { readFile } from 'node:fs/promises';

const template = await readFile('views/email/welcome.ejs', 'utf8');
const html = ejs.render(template, { user: { name: 'Ada' } }, { filename: 'views/email/welcome.ejs' });
console.log(html);
```

Run:

```bash
node render.js > out.html
```

## Email templates (Nodemailer)

```bash
npm install nodemailer
```

```javascript
// mailer.js
import ejs from 'ejs';
import nodemailer from 'nodemailer';
import { readFile } from 'node:fs/promises';

const transporter = nodemailer.createTransport({
  host: 'smtp.example.com',
  port: 587,
  secure: false,
  auth: { user: 'user', pass: 'pass' }
});

export async function sendWelcome(to, data) {
  const template = await readFile('views/email/welcome.ejs', 'utf8');
  const html = ejs.render(template, data, { filename: 'views/email/welcome.ejs' });
  await transporter.sendMail({ from: 'noreply@example.com', to, subject: 'Welcome', html });
}
```

`views/email/welcome.ejs`:

```ejs
<!doctype html>
<html>
  <body>
    <p>Hello <%= user.name %>, welcome aboard!</p>
  </body>
</html>
```

## Caching and performance

- Set `view cache` in Express for production to cache compiled templates.

```javascript
app.set('view cache', process.env.NODE_ENV === 'production');
```

- For standalone `ejs.render`, reuse the template string and pass `{cache: true, filename: 'path'}` to enable internal caching by filename.

```javascript
const html = ejs.render(tpl, data, { cache: true, filename: 'views/page.ejs' });
```

## Error handling and debugging

- In Express, unhandled errors can be rendered by an error handler.

```javascript
app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).render('error', { title: 'Error', error: err });
});
```

- During development, add context to your views (titles, debug blocks) and log locals if needed.

```ejs
<% /* debug: <%= JSON.stringify(locals || {}, null, 2) %> */ %>
```

## Security notes

- Prefer `<%= ... %>` to escape output by default. Only use `<%- ... %>` for trusted HTML.
- Sanitize user‑generated HTML content server‑side before injecting as raw HTML.
- Consider using a CSP (Content Security Policy) to mitigate XSS.

## Troubleshooting

- Template not found: verify `app.set('views', ...)` and the path used in `res.render()`.
- Helper undefined: ensure you attached it to `res.locals` before `res.render()` or passed in the render call.
- Include path issues: when rendering standalone, pass `{ filename: 'full/path/to/template.ejs' }` so includes resolve relative to that file.
- Layout not applying: confirm `express-ejs-layouts` is registered with `app.use()` and `app.set('layout', ...)` points to a valid file.

## References

- EJS home: [https://ejs.co/](https://ejs.co/)
- EJS docs: [https://ejs.co/#docs](https://ejs.co/#docs)
- Express docs: [https://expressjs.com/](https://expressjs.com/)

---

Copy any block into your project and adapt names/paths as needed.
