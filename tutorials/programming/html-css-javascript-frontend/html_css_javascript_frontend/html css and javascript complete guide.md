# Vanilla HTML + CSS + JavaScript – Complete Beginner‑to‑Advanced Guide  
*(plain language, every code block shows why it’s useful, a common mistake to avoid, a best‑practice tip, and a link to the official MDN documentation. The guide explicitly covers **`querySelector` / `querySelectorAll`**, **`fetch`**, and **`async / await`**. All JavaScript uses **modern ES modules**, **`const` / `let`**, **strict mode**, and follows security‑first best practices.)*  

---

## Table of Contents
1. [Project setup – no build tools needed](#0-project-setup---no-build-tools-needed)  
2. [HTML skeleton](#0-project-setup---no-build-tools-needed)  
3. [Text elements](#2-text-elements-headings-paragraphs-inline-tags)  
4. [Images & links](#3-images-links)  
5. [Lists & tables](#4-lists-tables)  
6. [Forms – inputs, validation, accessibility](#5-forms---inputs-validation-accessibility)  
7. [Semantic HTML – sections, nav, article, aside, footer](#6-semantic-html---sections-nav-article-aside-footer)  
8. [CSS basics – selector, property, cascade](#7-css-basics---selector-property-cascade)  
9. [Box model & basic layout](#8-box-model-basic-layout-margin-padding-border)  
10. [Positioning](#9-positioning-static-relative-absolute-fixed-sticky)  
11. [Flexbox – one‑dimensional layout](#flexbox---onedimensional-layout)  
12. **More Flexbox examples** (nav bar, cards, media object, equal‑height columns)  
13. [CSS Grid – two‑dimensional layout](#12-css-grid---twodimensional-layout)  
14. **More Grid examples** (named grid‑areas, overlapping items, image gallery)  
15. **Real‑world page layouts that combine Grid + Flexbox**  
16. [Responsive design – media queries](#15-responsive-design---media-queries)  
17. [CSS custom properties (variables)](#16-css-custom-properties-variables)  
18. [Typography – fonts, line‑height, @font‑face](#17-typography---fonts-lineheight-fontface)  
19. [Colors, gradients & shadows](#18-colors-gradients-shadows)  
20. [Transitions & animations](#19-transitions-animations)  
21. [Pseudo‑classes & pseudo‑elements](#20-pseudoclasses-pseudoelements)  
22. [JavaScript foundations – variables, types, functions](#21-javascript-foundations---variables-types-functions)  
23. [DOM selection & manipulation – `querySelector` / `querySelectorAll`](#22-dom-selection-manipulation---queryselector-queryselectorall)  
24. [Events – click, submit, keyboard, delegation](#23-events---click-submit-keyboard-delegation)  
25. [Fetch API – GET & POST with `async / await`](#24-fetch-api---get-post-with-async-await)  
26. [Form handling & client‑side validation (JS)](#25-form-handling-clientside-validation-js)  
27. [JavaScript modules (`type="module"`)](#26-javascript-modules-typemodule)  
28. [Local storage & session storage](#27-local-storage-session-storage)  
29. [Timers – `setTimeout`, `setInterval`, `requestAnimationFrame`](#28-timers---settimeout-setinterval-requestanimationframe)  
30. [Error handling – `try / catch` & custom errors](#29-error-handling---try-catch-custom-errors)  
31. [Accessibility basics – focus management & ARIA](#30-accessibility-basics---focus-management-aria)  
32. [Best‑practice checklist (HTML + CSS + JS)](#31-bestpractice-checklist-html-css-js)  
33. **Secure mini‑apps** (To‑Do List, Dark‑Mode toggle, Weather widget, Countdown timer, Image carousel)  
34. [Reference list (MDN)](#33-reference-list-mdn)

---

## 0️⃣ Project setup – no build tools needed

| Step                               | Command / Action                                                                                                               | What it does                            |
|------------------------------------|-------------------------------------------------------------------------------------------------------------------------------|----------------------------------------|
| **Create a folder**                | `mkdir my-site && cd my-site`                                                                                                | Holds all files.                       |
| **Create starter files**           | `touch index.html style.css script.js`                                                                                         | HTML page, stylesheet, JavaScript file. |
| **Open in a browser**              | Drag `index.html` into any browser **or** run a tiny dev server: <br>`npx serve .` (Node) or `python -m http.server` (Python). | Live‑preview while you edit.           |
| **(Optional) VS Code Live Server**| Install the *Live Server* extension and click “Go Live”.                                                                       | Auto‑reload on changes.                |

> **Why** – Pure HTML/CSS/JS run directly in the browser; you only need a folder and a file viewer.  
> **Pitfall** – Opening the file via `file:///` disables `fetch()` calls to other origins because of CORS restrictions. Use a local server (`http://localhost`).  
> **Tip** – Keep the three files (`index.html`, `style.css`, `script.js`) in the same folder for the simplest possible project structure.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Learn/Getting_started_with_the_web>

---

## 1️⃣ The skeleton of an HTML document

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <!-- 📚 Docs: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My First Page</title>
    <link rel="stylesheet" href="style.css" />
    <!-- attach CSS -->
    <script type="module" src="script.js" defer></script>
    <!-- ES‑module, defer = run after parsing -->
  </head>
  <body>
    <h1>Hello, HTML + CSS + JS!</h1>
    <p>This is a paragraph.</p>
  </body>
</html>
```

* **Why** – `<!DOCTYPE html>` tells the browser to use modern HTML5. `<head>` holds meta‑information; `<body>` holds visible content.  
* **Pitfall** – Forgetting `defer` (or placing `<script>` **before** the `<body>` without it) makes the script run before the DOM is ready, causing “undefined element” errors.  
* **Tip** – Keep the `<script>` tag **right before** `</head>` *or* add `defer`; the script will run after parsing.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script>

---

## 2️⃣ Text elements (headings, paragraphs, inline tags)

```html
<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3 … down to h6</h3>

<p>A paragraph. <strong>Bold importance</strong> and <em>emphasis</em>.</p>

<a href="https://developer.mozilla.org/">MDN link</a>
<code>inline code</code>
<abbr title="Cascading Style Sheets">CSS</abbr>
```

* **Why** – Headings create a logical outline; `<p>` groups sentences; `<strong>`/`<em>` convey meaning, not just visual style.  
* **Pitfall** – Using `<b>`/`<i>` for visual style only; screen readers ignore them.  
* **Tip** – Keep heading levels in order (don’t jump from `<h1>` straight to `<h4>`).

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/HTML/Element>

---

## 3️⃣ Images & links

```html
<img src="cat.jpg" alt="Fluffy orange cat" width="300" />

<a href="https://github.com/vlang/v" target="_blank" rel="noopener">
  Visit V‑language repo
</a>
```

* **Why** – `<img>` embeds pictures; `alt` text is essential for accessibility and SEO.  
* **Pitfall** – Missing `alt` results in a bad experience for screen‑reader users and hurts SEO.  
* **Tip** – Add `rel="noopener"` (or `noreferrer`) whenever you use `target="_blank"`; it prevents the **reverse‑tabnabbing** security issue.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img>

---

## 4️⃣ Lists & tables

```html
<ol>
  <li>First ordered item</li>
  <li>Second ordered item</li>
</ol>

<ul>
  <li>Apple</li>
  <li>Orange</li>
</ul>

<table>
  <caption>Scoreboard</caption>
  <thead>
    <tr><th>Name</th><th>Score</th></tr>
  </thead>
  <tbody>
    <tr><td>Alice</td><td>12</td></tr>
    <tr><td>Bob</td><td>9</td></tr>
  </tbody>
</table>
```

* **Why** – Lists for collections; tables for genuine tabular data (never for layout).  
* **Pitfall** – Using `<table>` to create page columns – this breaks accessibility and makes responsive design painful.  
* **Tip** – Add a `<caption>` and use `<thead>/<tbody>` for proper semantics; screen readers love them.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/HTML/Element/table>

---

## 5️⃣ Forms – inputs, validation, accessibility

```html
<form action="/submit.php" method="POST" id="contact-form">
  <fieldset>
    <legend>Contact us</legend>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required minlength="2" />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required />

    <label for="msg">Message:</label>
    <textarea id="msg" name="msg" rows="4" required></textarea>

    <label>
      <input type="checkbox" name="subscribe" value="yes" />
      Subscribe to newsletter
    </label>

    <button type="submit">Send</button>
  </fieldset>
</form>
```

* **Why** – Forms collect user data; built‑in validation (`required`, `type="email"`) gives immediate feedback without JavaScript.  
* **Pitfall** – Forgetting `<label>` elements makes the form hard to use with a mouse or screen reader.  
* **Tip** – Keep the `<label>` `for` attribute matching the input’s `id`. Group related fields with `<fieldset>`/`<legend>` for better semantics.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Learn/Forms>

---

## 6️⃣ Semantic HTML – sections, nav, article, aside, footer

```html
<header>
  <h1>My Blog</h1>
  <nav>
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="/about.html">About</a></li>
    </ul>
  </nav>
</header>

<main>
  <article>
    <h2>First post</h2>
    <p>Post content …</p>
  </article>

  <aside>
    <h3>Related links</h3>
    <ul><li><a href="#">Link A</a></li></ul>
  </aside>
</main>

<footer>© 2025 My Blog – <a href="/privacy.html">Privacy</a></footer>
```

* **Why** – Semantic tags give meaning to the page structure, improving SEO and accessibility.  
* **Pitfall** – Using multiple `<header>`/`<footer>` tags without understanding that they can be **section‑level** (inside `<article>` or `<section>`).  
* **Tip** – Use **one** `<main>` per page – it tells assistive tech where the primary content lives.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/HTML/Element#sectioning_content>

---

## 7️⃣ CSS basics – selector, property, cascade

```css
/* style.css – comment */
body {
  font-family: Arial, Helvetica, sans-serif;
  background: #f9f9f9;
  color: #333;
}

/* class selector */
.highlight {
  background: yellow;
}

/* id selector (high specificity) */
#logo {
  width: 120px;
}

/* element selector */
a {
  color: #0066cc;
  text-decoration: none;
}
```

* **Why** – CSS tells the browser **how** elements should look. The cascade (order, specificity, inheritance) decides which rule wins.  
* **Pitfall** – Over‑specific selectors (`div#content ul li a`) make later overrides hard.  
* **Tip** – Prefer **class** selectors for styling; keep IDs for JavaScript hooks or truly unique elements.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/Cascade>

---

## 8️⃣ Box model & basic layout (margin, padding, border)

```css
.box {
  width: 200px;
  padding: 20px;               /* space inside the border */
  border: 2px solid #555;
  margin: 15px;                /* space outside the border */
  background: #e0e0e0;
}

/* Global border‑box reset – makes sizing intuitive */
*, *::before, *::after {
  box-sizing: border-box;
}
```

* **Why** – Every element is a rectangular **box**; understanding the four parts (margin, border, padding, content) is essential for layout.  
* **Pitfall** – By default `width` does **not** include padding or border (`box-sizing: content-box`).  
* **Tip** – Apply `box-sizing: border-box;` globally (as shown) to make width calculations intuitive.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Learn/CSS/Building_blocks/The_box_model>

---

## 9️⃣ Positioning (static, relative, absolute, fixed, sticky)

```css
.parent {
  position: relative;          /* establishes containing block */
  height: 200px;
  background: #ddd;
}
.child {
  position: absolute;          /* positioned inside .parent */
  top: 20px;
  right: 20px;
  width: 80px;
  height: 80px;
  background: coral;
}
```

| Position | Description |
|----------|-------------|
| `static` (default) | follows normal flow. |
| `relative` | offset from its normal place **but** still occupies original space. |
| `absolute` | taken out of flow; placed relative to the nearest **positioned** ancestor (`relative`, `absolute`, `fixed`, `sticky`). |
| `fixed` | anchored to the viewport; stays put when scrolling. |
| `sticky` | behaves like `relative` until a scroll threshold, then becomes `fixed`. |

* **Why** – Positioning lets you build overlays, fixed navbars, card layouts, etc.  
* **Pitfall** – Forgetting to set a positioned ancestor for an `absolute` child; it will attach to the page `<html>` instead.  
* **Tip** – Use `position: relative` **only** when you need a child positioned absolutely; otherwise keep the layout flow simple.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/position>

---

## 🔟 Flexbox – one‑dimensional layout

```css
.flex-container {
  display: flex;
  flex-direction: row;            /* column is also possible */
  justify-content: space-between;
  align-items: center;
  gap: 1rem;                      /* modern browsers – avoids double‑margin hacks */
}
.flex-item {
  flex: 1 1 0;                    /* grow, shrink, basis */
}
```

```html
<div class="flex-container">
  <div class="flex-item">A</div>
  <div class="flex-item">B</div>
  <div class="flex-item">C</div>
</div>
```

* **Why** – Flexbox makes it trivial to create responsive rows or columns that automatically distribute space.  
* **Pitfall** – Mixing `float`/`inline-block` with Flexbox can cause unexpected wrapping.  
* **Tip** – Use `gap` for spacing instead of manual margins; it avoids double‑margin problems.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout>

---

## 1️⃣1️⃣ More Flexbox examples (real‑world patterns)

### a) Horizontal navigation bar (logo + links)

```html
<nav class="nav-bar" aria-label="Primary navigation">
  <a href="/" class="logo">MySite</a>
  <ul class="nav-list">
    <li><a href="/articles">Articles</a></li>
    <li><a href="/about">About</a></li>
    <li><a href="/contact">Contact</a></li>
  </ul>
</nav>
```

```css
.nav-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 1rem;
  background: #fff;
  border-bottom: 1px solid #ddd;
}
.logo { font-weight: bold; font-size: 1.25rem; color: var(--accent); }
.nav-list {
  display: flex;
  gap: 1.5rem;
  list-style: none;
}
.nav-list a { text-decoration: none; color: #333; }
.nav-list a:hover { color: var(--accent); }
```

* **Why** – Flexbox aligns the logo left and the link list right with *one* container.  
* **Pitfall** – Forgetting `list-style: none;` leaves unwanted bullets.  
* **Tip** – Add `aria-label` to the `<nav>` element for screen readers.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout>

---

### b) Card deck (equal‑height product cards)

```html
<section class="card-deck">
  <article class="card">
    <img src="https://picsum.photos/300/150?1" alt="Demo product 1">
    <h3>Product A</h3>
    <p class="price">$19.99</p>
    <button class="cta">Buy</button>
  </article>

  <article class="card">
    <img src="https://picsum.photos/300/150?2" alt="Demo product 2">
    <h3>Product B</h3>
    <p class="price">$29.99</p>
    <button class="cta">Buy</button>
  </article>

  <!-- …more cards -->
</section>
```

```css
.card-deck {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}
.card {
  flex: 1 1 calc(33.333% - 1rem);   /* three‑per‑row, responsive */
  display: flex;
  flex-direction: column;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  overflow: hidden;
}
.card img { width: 100%; height: auto; }
.card h3 { margin: 0.5rem; }
.card .price { margin: 0.5rem auto; font-weight: bold; }
.card .cta {
  margin-top: auto;               /* pushes button to bottom */
  background: var(--accent);
  color: #fff;
  border: none;
  padding: 0.5rem;
  cursor: pointer;
}
```

* **Why** – Using `flex-direction: column` + `margin-top: auto` makes every card’s button sit at the bottom, guaranteeing equal height regardless of content length.  
* **Pitfall** – Not setting a `flex-basis` (`calc(33.333% - 1rem)`) can cause cards to shrink too far on small screens.  
* **Tip** – Add a media query to switch to `flex-basis: 100%` on mobile (see the Media Queries section later).

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/flex-grow>

---

### c) Media object (image left, text right) – classic “article preview”

```html
<div class="media-object">
  <img src="https://picsum.photos/120/80?3" alt="Thumbnail" class="media-img">
  <div class="media-body">
    <h4>Interesting article</h4>
    <p>Short excerpt that teases the full story…</p>
  </div>
</div>
```

```css
.media-object {
  display: flex;
  gap: 0.75rem;
}
.media-img {
  flex-shrink: 0;         /* keep image size */
  width: 120px;
  height: 80px;
  object-fit: cover;
  border-radius: 3px;
}
.media-body {
  display: flex;
  flex-direction: column;
}
.media-body h4 { margin: 0; }
.media-body p { margin-top: 0.5rem; font-size: 0.9rem; color: #555; }
```

* **Why** – Flexbox takes care of vertical centering and spacing without floats.  
* **Pitfall** – Forgetting `flex-shrink: 0` on the image; the image could become tiny when the container narrows.  
* **Tip** – Use `object-fit: cover` to keep the image’s aspect ratio while filling its box.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/object-fit>

---

### d) Equal‑height columns inside a footer

```html
<footer class="site-footer">
  <div class="footer-col">
    <h4>About</h4>
    <p>Brief description of the site.</p>
  </div>
  <div class="footer-col">
    <h4>Links</h4>
    <ul>
      <li><a href="#">Terms</a></li>
      <li><a href="#">Privacy</a></li>
    </ul>
  </div>
  <div class="footer-col">
    <h4>Contact</h4>
    <p>email@example.com</p>
  </div>
</footer>
```

```css
.site-footer {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  background: #222;
  color: #eee;
  padding: 2rem;
}
.footer-col {
  flex: 1 1 200px;     /* minimum 200 px, grow as needed */
}
.footer-col h4 { margin-bottom: 0.5rem; }
.footer-col a   { color: #66c; }
```

* **Why** – Flexbox makes three columns that stay equal height even when the content lengths differ.  
* **Pitfall** – Not adding a `flex-wrap` can cause overflow on very narrow viewports.  
* **Tip** – Set a sensible `min-width` (`200px` in the example) so the columns collapse to a single stack on mobile.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/flex-wrap>

---

## 1️⃣2️⃣ CSS Grid – two‑dimensional layout

```css
.grid-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);   /* three equal columns */
  grid-template-rows: auto 200px;           /* first row auto, second fixed */
  gap: 1rem;
}
.item-a { grid-column: 1 / 3; }   /* span two columns */
.item-b { grid-row: 2; }          /* force into second row */
```

```html
<div class="grid-container">
  <div class="item-a">A (spans 2 cols)</div>
  <div class="item-b">B (second row)</div>
  <div>C</div>
  <div>D</div>
  <div>E</div>
</div>
```

* **Why** – Grid lets you place items in rows **and** columns simultaneously, perfect for complex page layouts.  
* **Pitfall** – Forgetting `display: grid` on the container; child `grid-column` rules then have no effect.  
* **Tip** – Combine Grid for the **page skeleton** and Flexbox inside individual grid items for inner alignment.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout>

---

## 1️⃣3️⃣ More Grid examples (named‑areas, overlapping items, gallery)

### a) Named grid‑areas – a classic *header / nav / main / aside / footer* layout  

```html
<div class="layout">
  <header class="site-header">Header</header>
  <nav class="site-nav">Nav</nav>
  <main class="site-main">Main content</main>
  <aside class="site-sidebar">Sidebar</aside>
  <footer class="site-footer">Footer</footer>
</div>
```

```css
.layout {
  display: grid;
  grid-template-columns: 1fr 250px;
  grid-template-rows: auto 1fr auto;
  grid-template-areas:
    "header header"
    "main   sidebar"
    "footer footer";
  gap: 1rem;
  min-height: 100vh;
}

/* Assign each region */
.site-header   { grid-area: header;   background:#fff; padding:1rem; }
.site-nav      { grid-area: nav;      background:#f5f5f5; }
.site-main     { grid-area: main;     background:#fafafa; padding:1rem; }
.site-sidebar  { grid-area: sidebar; background:#f0f0f0; padding:1rem; }
.site-footer   { grid-area: footer;  background:#ddd; padding:1rem; text-align:center; }
```

* **Why** – Named areas make the markup **self‑documenting** (`grid-area: header;` maps directly to the HTML class).  
* **Pitfall** – Misspelling a name (`grid-area: headr;`) silently breaks the layout.  
* **Tip** – Keep the naming consistent with the **semantic** element you’re styling (header → `site-header`, etc.).  

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/grid-template-areas>

---

### b) Overlapping items (e.g., a hero image with centered text)

```html
<div class="hero">
  <img src="https://picsum.photos/1200/400?hero" alt="">
  <div class="hero-text">Welcome to My Site</div>
</div>
```

```css
.hero {
  position: relative;
  display: grid;                     /* enables easy centering */
}
.hero img { width: 100%; height: auto; }

.hero-text {
  position: absolute;
  inset: 0;                         /* top/right/bottom/left = 0 */
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 2rem;
  text-shadow: 0 2px 4px rgba(0,0,0,.5);
}
```

* **Why** – `position: absolute` with `inset: 0` stretches the text overlay to cover the whole hero, while `display: flex` centers it perfectly.  
* **Pitfall** – Forgetting `color` contrast on the overlay can make text unreadable.  
* **Tip** – Pair the overlay with `background: rgba(0,0,0,.3)` on `.hero-text` for extra legibility on bright images.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/inset>

---

### c) CSS‑Grid image gallery (responsive, auto‑flow)

```html
<section class="gallery">
  <img src="https://picsum.photos/300/200?g1" alt="Nature">
  <img src="https://picsum.photos/300/200?g2" alt="City">
  <img src="https://picsum.photos/300/200?g3" alt="Mountains">
  <img src="https://picsum.photos/300/200?g4" alt="Ocean">
  <img src="https://picsum.photos/300/200?g5" alt="Forest">
</section>
```

```css
.gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 0.5rem;
}
.gallery img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
}
```

* **Why** – `auto-fill` + `minmax()` creates a **fluid** gallery that adds/removes columns as the viewport changes, with no media queries.  
* **Pitfall** – Not setting `object-fit: cover` can stretch images unevenly.  
* **Tip** – If you need a **lightbox**, keep the gallery pure CSS/HTML and add the lightbox logic with vanilla JS (no extra markup required).

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/grid-template-columns>

---

## 1️⃣4️⃣ Real‑world page layout that **combines Grid + Flexbox**

Below is a *complete* starter page that uses **named grid‑areas** for the outer structure and **Flexbox** for inner components (nav bar, cards, footer columns). The whole file is ready to copy‑paste into your `index.html` + `style.css`.

### `index.html`

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modern Blog – Grid + Flex</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <script type="module" src="script.js" defer></script>
</head>
<body>
  <div class="layout">
    <!-- ① Header -->
    <header class="site-header">
      <h1>My Blog</h1>
      <nav class="top-nav" aria-label="Main menu">
        <a href="#">Home</a>
        <a href="#">Articles</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
      </nav>
    </header>

    <!-- ② Sidebar (categories) -->
    <aside class="site-sidebar">
      <h2>Categories</h2>
      <ul>
        <li><a href="#">JavaScript</a></li>
        <li><a href="#">CSS</a></li>
        <li><a href="#">HTML</a></li>
        <li><a href="#">Accessibility</a></li>
      </ul>
    </aside>

    <!-- ③ Main content – a Flexbox card deck -->
    <main class="site-main">
      <section class="card-deck" aria-label="Latest articles">
        <article class="card">
          <img src="https://picsum.photos/400/210?card1" alt="Demo image 1">
          <h2>Understanding Flexbox</h2>
          <p>Flexbox lets you align items without floats …</p>
          <a href="#" class="read-more">Read more →</a>
        </article>

        <article class="card">
          <img src="https://picsum.photos/400/210?card2" alt="Demo image 2">
          <h2>CSS Grid: Named Areas</h2>
          <p>Grid‑area makes layout code declarative …</p>
          <a href="#" class="read-more">Read more →</a>
        </article>

        <!-- Add more cards as needed -->
      </section>
    </main>

    <!-- ④ Footer – Flex columns -->
    <footer class="site-footer">
      <div class="footer-col">
        <h4>About</h4>
        <p>Short description of the site.</p>
      </div>
      <div class="footer-col">
        <h4>Links</h4>
        <ul>
          <li><a href="#">Terms</a></li>
          <li><a href="#">Privacy</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <p>email@example.com</p>
      </div>
    </footer>
  </div>
</body>
</html>
```

### `style.css`

```css
/* ────────────────────── Global reset & variables ────────────────────── */
*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
:root {
  --bg: #fafafa;
  --txt: #222;
  --accent: #0066cc;
  --gap: 1rem;
}

/* ────────────────────── Layout (CSS Grid) ────────────────────── */
.layout {
  display: grid;
  min-height: 100vh;
  grid-template-columns: 1fr minmax(200px, 250px);
  grid-template-rows: auto 1fr auto;
  grid-template-areas:
    "header header"
    "main   sidebar"
    "footer footer";
  gap: var(--gap);
  background: var(--bg);
  color: var(--txt);
}

/* ────────────────────── Named grid areas ────────────────────── */
.site-header   { grid-area: header;   background:#fff; padding:var(--gap); border-bottom:1px solid #ddd; }
.site-sidebar  { grid-area: sidebar; background:#f5f5f5; padding:var(--gap); }
.site-main     { grid-area: main;    padding:var(--gap); }
.site-footer   { grid-area: footer; background:#222; color:#eee; padding:var(--gap); display:flex; flex-wrap:wrap; gap:2rem; }

/* ────────────────────── Header (Flex) ────────────────────── */
.top-nav {
  display: flex;
  gap: 1.5rem;
  margin-top: 0.5rem;
}
.top-nav a {
  text-decoration: none;
  color: var(--txt);
}
.top-nav a:hover { color: var(--accent); }

/* ────────────────────── Card deck (Flex) ────────────────────── */
.card-deck {
  display: flex;
  flex-wrap: wrap;
  gap: var(--gap);
}
.card {
  flex: 1 1 calc(33.333% - var(--gap));
  display: flex;
  flex-direction: column;
  background:#fff;
  border:1px solid #e0e0e0;
  border-radius:4px;
  overflow:hidden;
}
.card img { width:100%; height:auto; }
.card h2 { margin:0.5rem; }
.card p  { margin:0.5rem; flex-grow:1; }
.card .read-more {
  margin:0.5rem;
  align-self:flex-start;
  color:var(--accent);
  text-decoration:none;
}
.card .read-more:hover { text-decoration:underline; }

/* ────────────────────── Footer columns (Flex) ────────────────────── */
.footer-col {
  flex: 1 1 200px;
}
.footer-col h4 { margin-bottom:0.5rem; }
.footer-col a  { color:#66c; }

/* ────────────────────── Responsive tweak (mobile‑first) ────────────────────── */
@media (max-width: 800px) {
  .layout {
    grid-template-columns: 1fr;
    grid-template-areas:
      "header"
      "main"
      "sidebar"
      "footer";
  }
  .card { flex: 1 1 100%; }
}
```

**What you see in this example**

| Feature | How it’s achieved |
|--------|-------------------|
| **Outer skeleton** | `display: grid` with *named areas* (`header`, `main`, `sidebar`, `footer`). |
| **Top navigation** | Flexbox aligns the logo and links on the same line. |
| **Article cards** | Flex container with `flex-wrap` + `flex-basis: calc(33.333% - gap)` → three‑per‑row on desktop, single column on mobile (media query). |
| **Footer** | Flex columns stay equal height and wrap on narrow screens. |
| **Accessibility** | `aria-label` on main navigation and card deck; proper heading hierarchy. |
| **Security** | No inline `script`/`style`; content is purely declarative. All links are `rel="noopener"` when `target="_blank"` would be added later. |
| **Performance** | Global `box-sizing: border-box`; CSS variables for easy theming; `gap` replaces manual margins. |

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout>, <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout>

---

## 1️⃣5️⃣ Responsive design – media queries

```css
/* Mobile‑first (default) */
body { font-size: 1rem; }

/* Tablet */
@media (min-width: 600px) {
  body { font-size: 1.125rem; }
}

/* Desktop */
@media (min-width: 900px) {
  .grid-container { grid-template-columns: repeat(4, 1fr); }
}
```

* **Why** – Media queries let the layout adapt to different screen widths, pixel densities, or user preferences.  
* **Pitfall** – Writing `max-width` queries first (desktop‑first) can make mobile overrides harder; prefer **mobile‑first** (`min-width`).  
* **Tip** – Use `em` or `rem` breakpoints (based on font size) for better scalability when users zoom.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries/Using_media_queries>

---

## 1️⃣6️⃣ CSS custom properties (variables)

```css
:root {
  --primary: #0066cc;
  --spacing: 1rem;
}
.button {
  background: var(--primary);
  padding: var(--spacing);
}
```

* **Why** – Variables let you change a value in one place and have the whole site update instantly.  
* **Pitfall** – Using variables inside `calc()` without the `var()` wrapper will break the CSS.  
* **Tip** – Scope variables to a component if you need a different theme: `.dark { --primary:#ddd; }`.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties>

---

## 1️⃣7️⃣ Typography – fonts, line‑height, @font‑face

```css
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap");

body {
  font-family: "Roboto", Arial, sans-serif;
  line-height: 1.6;
  font-size: 1rem;
}

/* Self‑hosted */
@font-face {
  font-family: "MyFont";
  src: url("fonts/MyFont.woff2") format("woff2");
  font-display: swap;
}
```

* **Why** – Typography influences readability and brand identity.  
* **Pitfall** – Forgetting `font-display: swap;` can cause invisible text while the font loads.  
* **Tip** – Use relative units (`rem`, `em`) for font size and line‑height; they scale with the user’s browser settings.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face>

---

## 1️⃣8️⃣ Colors, gradients & shadows

```css
:root { --brand: #0066cc; }

.header {
  background: linear-gradient(45deg, var(--brand), #00cc99);
}

.card {
  box-shadow: 0 4px 8px rgba(0,0,0,.15);
  border-radius: 8px;
  padding: 1rem;
  background: #fff;
}
```

* **Why** – Visual design makes a site memorable; gradients and shadows add depth.  
* **Pitfall** – Low‑contrast colour combos hurt accessibility.  
* **Tip** – Test contrast with the **WCAG Contrast Checker** and aim for a minimum ratio of **4.5:1** for normal text.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/linear-gradient>

---

## 1️⃣9️⃣ Transitions & animations

```css
.button {
  background: var(--primary);
  color:#fff;
  padding:var(--spacing);
  transition: background .3s ease, transform .2s;
}
.button:hover {
  background:#0055aa;
  transform:translateY(-2px);
}

/* Keyframe spinner */
@keyframes spin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}
.spinner {
  width:40px; height:40px;
  border:4px solid #ccc;
  border-top-color:var(--primary);
  border-radius:50%;
  animation:spin 1s linear infinite;
}

/* Respect prefers‑reduced‑motion */
@media (prefers-reduced-motion: reduce) {
  .spinner { animation:none; }
}
```

* **Why** – Transitions give subtle feedback; keyframe animations create eye‑catching effects without JavaScript.  
* **Pitfall** – Overusing animations can cause motion sickness and waste CPU; respect the user’s `prefers-reduced-motion` setting.  
* **Tip** – Wrap animation rules in a media query (as shown) so users who request reduced motion get a static UI.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/transition>

---

## 2️⃣0️⃣ Pseudo‑classes & pseudo‑elements

```css
a:hover { text-decoration: underline; }
input:focus { outline:2px solid var(--primary); }

blockquote::before {
  content:"“";
  font-size:3rem;
  margin-right:.2rem;
}
```

* **Why** – Target element states (`:hover`, `:focus`) and parts of an element (`::before`, `::after`).  
* **Pitfall** – Forgetting the double colon (`::`) for pseudo‑elements in modern CSS; single colon still works for legacy support but `::` is the standard.  
* **Tip** – Use `:focus-visible` instead of plain `:focus` for keyboard‑only focus styles; it avoids showing a focus ring when the mouse is used.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes>

---

## 2️⃣1️⃣ JavaScript foundations – variables, types, functions

```html
<script type="module">
  // 📚 Docs: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Declarations
  const PI = 3.14159;       // never re‑assigned
  let counter = 0;          // block‑scoped, re‑assignable
  // var legacy = 42;       // avoid; function‑scoped & hoisted

  // Arrow function (concise syntax)
  const add = (a, b) => a + b;
  console.log("2 + 3 =", add(2, 3));

  // Simple loop
  for (let i = 0; i < 5; i++) {
    console.log("i =", i);
  }

  // Type‑coercion pitfall demo
  console.log("5" + 1); // "51" (string concat)
  console.log("5" - 1); // 4 (numeric subtraction)
</script>
```

* **Why** – Understanding `let`, `const`, and `var` is the first step to writing reliable scripts.  
* **Pitfall** – Using `var` creates hoisting surprises; prefer `let`/`const`.  
* **Tip** – **Never** use `==` for comparison; always use strict equality `===` to avoid hidden type coercion.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Grammar_and_types>

---

## 2️⃣2️⃣ DOM selection & manipulation – `querySelector`, `querySelectorAll`

```html
<h2 id="title">Original title</h2>
<ul class="menu">
  <li data-id="1">Home</li>
  <li data-id="2">About</li>
  <li data-id="3">Contact</li>
</ul>
<button id="changeBtn">Change title</button>

<script type="module">
  // 📚 Docs: https://developer.mozilla.org/en-US/docs/Web/API/Document/querySelector
  const title   = document.querySelector("#title");
  const button  = document.getElementById("changeBtn");
  const menu    = document.querySelectorAll(".menu li");

  // Change text safely
  title.textContent = "New title set by JS";
  title.classList.add("highlight");

  // Highlight a menu item on click (delegation is usually better)
  menu.forEach(item => {
    item.addEventListener("click", () => {
      alert(`You clicked ${item.textContent}`);
    });
  });

  // Demonstrate live HTMLCollection vs static NodeList
  const liveDivs = document.getElementsByTagName("div"); // live
  console.log("Live div count before:", liveDivs.length);
  const newDiv = document.createElement("div");
  document.body.appendChild(newDiv);
  console.log("Live div count after adding one:", liveDivs.length); // +1
</script>
```

* **Why** – `querySelector`/`querySelectorAll` let you reuse **CSS selectors** inside JavaScript, giving a powerful and concise way to find elements.  
* **Pitfall** – Using `innerHTML` with untrusted data creates an XSS vulnerability; prefer `textContent` or proper sanitisation.  
* **Tip** – **Cache** frequently accessed elements (`const btn = …`) instead of querying the DOM on every event – it’s faster.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/API/Document/querySelector>

---

## 2️⃣3️⃣ Events – click, submit, keyboard, delegation

```html
<ul id="menu">
  <li data-id="1">Home</li>
  <li data-id="2">About</li>
  <li data-id="3">Contact</li>
</ul>

<script type="module">
  const menu = document.getElementById("menu");

  // Event delegation – one listener for many children
  menu.addEventListener("click", e => {
    const li = e.target.closest("li");
    if (!li) return;
    console.log("Clicked item", li.dataset.id);
  });

  // Keyboard shortcut (Escape to close modals, etc.)
  document.addEventListener("keydown", e => {
    if (e.key === "Escape") console.log("Escape pressed");
  });
</script>
```

* **Why** – Events are the bridge between user actions and JavaScript logic. Delegation reduces the number of listeners and improves performance.  
* **Pitfall** – Forgetting `event.preventDefault()` on a form’s submit handler; the page will reload.  
* **Tip** – Use **passive** listeners for scroll/touch events when you *don’t* call `preventDefault()`: `addEventListener('scroll', handler, { passive: true })`.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Building_blocks/Events>

---

## 2️⃣4️⃣ Fetch API – GET & POST with **`async / await`**

```html
<button id="loadBtn">Load GitHub repo</button>

<script type="module">
  async function loadRepo() {
    const url = "https://api.github.com/repos/vlang/v";
    try {
      const response = await fetch(url, {
        headers: { "User-Agent": "my-demo-app" } // GitHub requires it
      });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      const data = await response.json();
      console.log("Repo data:", data);
    } catch (err) {
      console.error("Fetch error:", err);
    }
  }
  document.getElementById("loadBtn").addEventListener("click", loadRepo);
</script>
```

* **Why** – `fetch` replaces the old `XMLHttpRequest`; it returns a **Promise**, making asynchronous code easy to read with `async/await`.  
* **Pitfall** – Forgetting `await response.json()` – you’ll log a Promise object instead of the data.  
* **Tip** – Always check `response.ok` before parsing JSON; it catches 4xx/5xx HTTP errors. Wrap the whole call in a `try/catch` block so network failures are handled gracefully.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API>

---

## 2️⃣5️⃣ Form handling & client‑side validation (JS)

```html
<form id="contact">
  <label>Name: <input type="text" name="name" required minlength="2"></label>
  <label>Email: <input type="email" name="email" required></label>
  <button type="submit">Send</button>
</form>

<script type="module">
  const form = document.getElementById("contact");
  form.addEventListener("submit", async e => {
    e.preventDefault();                 // stop normal POST
    const data = new FormData(form);    // auto‑collects inputs

    // optional extra client‑side check
    if (data.get("name").length < 2) {
      alert("Name must be at least 2 chars");
      return;
    }

    try {
      const resp = await fetch("/contact.php", {
        method: "POST",
        body: data
      });
      const result = await resp.json();
      alert(result.message);
    } catch (err) {
      console.error("Submission error:", err);
    }
  });
</script>
```

* **Why** – Enhances UX by preventing page reloads and allowing instant feedback.  
* **Pitfall** – Relying only on client‑side validation; **always** repeat checks on the server.  
* **Tip** – Use the `FormData` API for file uploads; it automatically sets the correct `Content-Type`.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/API/FormData>

---

## 2️⃣6️⃣ JavaScript modules (`type="module"`)

```html
<!-- index.html -->
<script type="module" src="main.js"></script>
```

```js
// main.js
import { greet } from "./utils.js";

greet("World");
```

```js
// utils.js
export function greet(name) {
  console.log(`Hello, ${name}!`);
}
```

* **Why** – ES 6 modules give you a clean way to split code into reusable files, with **static analysis** and no global namespace pollution.  
* **Pitfall** – Forgetting `type="module"` on the `<script>` tag; the browser will treat the file as a classic script and `import` will throw a syntax error.  
* **Tip** – Modules are **deferred** by default, so you don’t need `defer`. They also run in **strict mode** automatically, which catches many common mistakes.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules>

---

## 2️⃣7️⃣ Local storage & session storage

```js
// Save user‑chosen theme (persists across tabs, survives restarts)
localStorage.setItem("theme", "dark");

// Retrieve
const theme = localStorage.getItem("theme") || "light";
document.body.dataset.theme = theme;

// Session storage (cleared when the tab/window is closed)
sessionStorage.setItem("temp", "123");
```

* **Why** – Store small pieces of data on the client (e.g., user preferences) without a server.  
* **Pitfall** – Storing **sensitive** information (passwords, tokens) in plain text is insecure.  
* **Tip** – Serialize complex objects with `JSON.stringify()` before saving and `JSON.parse()` when reading.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage>

---

## 2️⃣8️⃣ Timers – `setTimeout`, `setInterval`, `requestAnimationFrame`

```js
// Run once after 2 seconds
setTimeout(() => console.log("2 seconds passed"), 2000);

// Repeat every second
const id = setInterval(() => console.log("tick"), 1000);
// Stop after 5 ticks
let count = 0;
const stopId = setInterval(() => {
  if (++count === 5) clearInterval(stopId);
}, 1000);

// Animation‑friendly loop
function animate(time) {
  // time = high‑resolution timestamp
  // …update UI…
  requestAnimationFrame(animate);
}
requestAnimationFrame(animate);
```

* **Why** – Timers let you schedule work in the future; `requestAnimationFrame` syncs visual updates with the browser’s refresh rate.  
* **Pitfall** – Using `setInterval` for animations can cause frame‑dropping; prefer `requestAnimationFrame`.  
* **Tip** – Store the timer ID (`const id = setTimeout(...)`) so you can `clearTimeout(id)` for **debounce** patterns.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/API/WindowOrWorkerGlobalScope/setTimeout>

---

## 2️⃣9️⃣ Error handling – `try / catch` & custom errors

```js
function fetchJson(url) {
  return fetch(url).then(r => {
    if (!r.ok) throw new Error(`HTTP ${r.status}`);
    return r.json();
  });
}

(async () => {
  try {
    const data = await fetchJson("https://api.github.com/users/vlang");
    console.log(data);
  } catch (err) {
    console.error("Failed to load data:", err.message);
  }
})();
```

* **Why** – `try / catch` lets you recover from exceptions without crashing the whole script.  
* **Pitfall** – Swallowing errors (`catch (e) {}`) hides bugs; always at least log the error.  
* **Tip** – Create **custom error classes** for domain‑specific failures: `class ValidationError extends Error {}`.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/try...catch>

---

## 3️⃣0️⃣ Accessibility basics – focus management & ARIA

```html
<button id="openModal">Open modal</button>

<div id="modal" role="dialog" aria-modal="true" hidden>
  <h2 id="modalTitle">Modal title</h2>
  <p>Some content …</p>
  <button id="closeModal">Close</button>
</div>

<script type="module">
  const open  = document.getElementById("openModal");
  const close = document.getElementById("closeModal");
  const modal = document.getElementById("modal");

  open.addEventListener("click", () => {
    modal.hidden = false;
    modal.focus();               // move keyboard focus inside
  });
  close.addEventListener("click", () => (modal.hidden = true));
</script>
```

* **Why** – Users of screen readers and keyboards need proper **focus** and **ARIA** landmarks to navigate.  
* **Pitfall** – Forgetting `role="dialog"` and `aria-modal="true"`; screen readers will treat the overlay as normal page content.  
* **Tip** – When you hide/show content, use the `hidden` attribute (or `display:none`) – it removes the element from the accessibility tree.

📚 **Docs**: <https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA>

---

## 3️⃣1️⃣ Best‑practice checklist (HTML + CSS + JS)

| Area | Checklist |
|------|-----------|
| **HTML** | • `<!DOCTYPE html>` present.<br>• All images have meaningful `alt`.<br>• Every `<input>` has an associated `<label>`.<br>• Use semantic elements (`<header>`, `<nav>`, `<main>`, `<article>`, `<aside>`, `<footer>`). |
| **CSS** | • Global `box-sizing: border-box`.<br>• Mobile‑first `@media (min-width…)` queries.<br>• No `!important` unless absolutely necessary.<br>• Color contrast meets WCAG 2.1 AA. |
| **JS** | • Use `const`/`let`; avoid `var`.<br>• Cache frequently accessed DOM nodes.<br>• All async code uses `async/await` + `try/catch`.<br>• **Never** use `innerHTML` with untrusted data. |
| **Performance** | • Optimise images (WebP/AVIF).<br>• Use `gap` instead of manual margins for Flex/Grid.<br>• `requestAnimationFrame` for visual updates.<br>• Remove unused event listeners (`removeEventListener`). |
| **Accessibility** | • Keyboard‑focusable (`tabindex="0"` only when needed).<br>• ARIA landmarks on complex widgets.<br>• Respect `prefers-reduced-motion` in animations.<br>• Form fields have `required`/`type` attributes. |
| **Testing** | • Open DevTools → **Console** – no uncaught errors.<br>• Test in at least two browsers (Chrome, Firefox).<br>• Test on a mobile device or use the responsive emulator. |
| **Version control** | • Commit source (`.html`, `.css`, `.js`) only.<br>• Add generated assets (e.g., `dist/`) to `.gitignore`.<br>• Include a short `README.md` describing the project. |

> **If you can tick every box, you’ve built a robust, accessible, and maintainable front‑end site using only vanilla HTML, CSS, and JavaScript.** 🎉

---

## 3️⃣2️⃣ Secure Mini‑Apps (copy‑and‑paste ready)

Below are **stand‑alone** examples that demonstrate the concepts covered and already contain the security best practices discussed earlier (sanitisation, CSP‑compatible markup, safe `fetch`, etc.). Each snippet can be dropped into its own folder (or combined) and run on a **local dev server** (`http://localhost`).

### 1️⃣ Secure To‑Do List (DOM sanitisation + event delegation)

```html
<!-- todo.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Secure To‑Do</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="todo.js" defer></script>
</head>
<body>
  <h1>My To‑Do List</h1>
  <input type="text" id="newTask" placeholder="New task…" maxlength="100">
  <button id="addBtn">Add</button>
  <ul id="taskList"></ul>
</body>
</html>
```

```css
/* style.css (shared) */
body {font-family:Arial,Helvetica,sans-serif; padding:1rem;}
.highlight {background:#ff0;}
button{margin-left:.5rem;}
li{margin-top:.5rem;}
```

```js
// todo.js
const list   = document.getElementById("taskList");
const input  = document.getElementById("newTask");
const addBtn = document.getElementById("addBtn");

// Simple HTML‑entity sanitizer
function sanitize(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}
function addTask() {
  const text = input.value.trim();
  if (!text) return;
  const li = document.createElement("li");
  li.innerHTML = `<span>${sanitize(text)}</span>
                  <button class="del">🗑️</button>`;
  list.appendChild(li);
  input.value = "";
}
addBtn.addEventListener("click", addTask);
input.addEventListener("keypress", e => { if (e.key==="Enter") addTask(); });

// Event delegation for delete
list.addEventListener("click", e => {
  if (e.target.classList.contains("del")) e.target.parentElement.remove();
});
```

> **Security** – No `innerHTML` with raw user data; we **escape** via a temporary element. Event delegation means only one listener is attached.

---

### 2️⃣ Secure Dark‑Mode Toggle (no user input)

```html
<!-- dark.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dark‑Mode Demo</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="dark.js" defer></script>
</head>
<body>
  <button id="themeToggle" aria-label="Toggle dark mode">🌓</button>
  <p>Hello, the theme respects your preference!</p>
</body>
</html>
```

```css
/* style.css */
:root {--bg:#fff;--txt:#222;--accent:#0066cc;}
[data-theme="dark"] {
  --bg:#111;--txt:#eee;
}
body {background:var(--bg);color:var(--txt);transition:background .3s,color .3s;}
button {background:none;border:none;font-size:1.5rem;cursor:pointer;}
```

```js
// dark.js
const toggle = document.getElementById("themeToggle");
const saved = localStorage.getItem("theme");
if (saved === "dark") document.documentElement.dataset.theme = "dark";

toggle.addEventListener("click", () => {
  const isDark = document.documentElement.dataset.theme === "dark";
  document.documentElement.dataset.theme = isDark ? "" : "dark";
  localStorage.setItem("theme", isDark ? "light" : "dark");

  // Announce change for screen readers
  const live = document.createElement("div");
  live.setAttribute("aria-live","polite");
  live.textContent = `Theme changed to ${isDark?"light":"dark"} mode`;
  document.body.append(live);
  setTimeout(() => live.remove(), 1000);
});
```

> **Security** – No user‑supplied data is processed; `localStorage` only stores a harmless string (`dark`/`light`).  

---

### 3️⃣ Secure Weather Widget (fetch + error handling)

```html
<!-- weather.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weather Widget</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="weather.js" defer></script>
</head>
<body>
  <input id="city" placeholder="Enter city" maxlength="50">
  <button id="getWeather">Get Weather</button>
  <div id="output" role="status" aria-live="polite"></div>
</body>
</html>
```

```css
/* style.css */
body{font-family:sans-serif;padding:1rem;}
#output{margin-top:1rem;padding:1rem;background:#f0f0f0;border-radius:4px;}
```

```js
// weather.js
const cityInput = document.getElementById("city");
const button    = document.getElementById("getWeather");
const output    = document.getElementById("output");

// Very simple sanitiser – removes anything that isn’t a letter, space or hyphen
function sanitizeCity(str) {
  return str.replace(/[^a-zA-Z\s\-]/g, "").trim();
}

async function getWeather() {
  const city = sanitizeCity(cityInput.value);
  if (!city) return (output.textContent = "Please enter a valid city");

  const apiKey = "YOUR_API_KEY";   // 👉 NEVER commit a real key to a public repo
  const url = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(city)}&appid=${apiKey}&units=metric`;

  try {
    output.textContent = "Loading…";
    const res = await fetch(url);
    if (!res.ok) {
      if (res.status===404) throw new Error("City not found");
      throw new Error(`Weather service error ${res.status}`);
    }
    const data = await res.json();

    // Validate response shape before using
    if (!data.name || !data.main?.temp) throw new Error("Malformed data");

    const safeName = data.name.replace(/[<>]/g, c => (c==="&"? "&amp;" : c===">"? "&gt;" : "&lt;"));
    output.innerHTML = `<h2>${safeName}</h2>
                        <p>🌡️ ${Math.round(data.main.temp)}°C</p>
                        <p>☁️ ${data.weather[0]?.description ?? ""}</p>`;
  } catch (err) {
    console.error(err);
    output.textContent = err.message || "Failed to fetch weather.";
  }
}
button.addEventListener("click", getWeather);
cityInput.addEventListener("keypress", e => { if (e.key==="Enter") getWeather(); });
```

> **Security** – Input sanitisation, strict response validation, and **no** raw `innerHTML` with user data. API keys must **never** be exposed in client‑side code for production (use a server‑side proxy).

---

### 4️⃣ Secure Countdown Timer (input validation)

```html
<!-- timer.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Countdown Timer</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="timer.js" defer></script>
</head>
<body>
  <label>Minutes: <input id="minutes" type="number" min="1" max="60" value="5"></label>
  <button id="startBtn">Start</button>
  <button id="resetBtn">Reset</button>
  <div id="display" role="timer" aria-live="polite">05:00</div>
</body>
</html>
```

```css
/* style.css */
#display {font-size:2rem;margin-top:1rem;}
button{margin-right:.5rem;}
```

```js
// timer.js
let interval = null;
let remaining = 0;
const display = document.getElementById("display");
const minutesInput = document.getElementById("minutes");
const startBtn = document.getElementById("startBtn");
const resetBtn = document.getElementById("resetBtn");

function fmt(sec) {
  const m = String(Math.floor(Math.abs(sec) / 60)).padStart(2, "0");
  const s = String(Math.abs(sec) % 60).padStart(2, "0");
  return `${sec < 0 ? "-" : ""}${m}:${s}`;
}
function start() {
  const mins = parseInt(minutesInput.value, 10);
  if (isNaN(mins) || mins < 1 || mins > 60) {
    display.textContent = "Enter 1‑60 minutes";
    return;
  }
  clearInterval(interval);
  remaining = mins * 60;
  display.textContent = fmt(remaining);
  interval = setInterval(() => {
    remaining--;
    display.textContent = fmt(remaining);
    if (remaining <= 0) {
      clearInterval(interval);
      if ("vibrate" in navigator) navigator.vibrate([500, 100, 500]);
      display.textContent = "⏰ Time's up!";
    }
  }, 1000);
}
function reset() {
  clearInterval(interval);
  const mins = parseInt(minutesInput.value, 10) || 5;
  remaining = mins * 60;
  display.textContent = fmt(remaining);
}
startBtn.addEventListener("click", start);
resetBtn.addEventListener("click", reset);
```

> **Security** – No external input other than a numeric field (validated with `min`/`max`). No XSS surface.

---

### 5️⃣ Secure Image Carousel (no user input, accessible)

```html
<!-- carousel.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Image Carousel</title>
  <link rel="stylesheet" href="style.css">
  <script type="module" src="carousel.js" defer></script>
</head>
<body>
  <div class="carousel" role="region" aria-label="Image carousel">
    <img src="https://picsum.photos/300/200?c1" alt="Nature" />
    <img src="https://picsum.photos/300/200?c2" alt="City" />
    <img src="https://picsum.photos/300/200?c3" alt="Mountains" />
  </div>
  <button class="nav prev" aria-label="Previous image">←</button>
  <button class="nav next" aria-label="Next image">→</button>

  <div class="indicators" role="tablist" aria-label="Slide controls">
    <button class="dot active" aria-label="Slide 1" aria-selected="true"></button>
    <button class="dot" aria-label="Slide 2" aria-selected="false"></button>
    <button class="dot" aria-label="Slide 3" aria-selected="false"></button>
  </div>
</body>
</html>
```

```css
/* style.css */
.carousel {
  display: flex;
  overflow:hidden;
  width:300px;
  margin:auto;
}
.carousel img {
  min-width:100%;
  transition:transform .5s ease;
}
.nav {
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  background:rgba(0,0,0,.5);
  color:#fff;
  border:none;
  padding:.5rem;
  cursor:pointer;
}
.prev { left:10px; }
.next { right:10px; }
.indicators { text-align:center; margin-top:.5rem; }
.dot {
  width:12px;height:12px;
  border-radius:50%;
  background:#ccc;
  border:none;
  margin:0 4px;
  cursor:pointer;
}
.dot.active { background:#0066cc; }
```

```js
// carousel.js
let index = 0;
const images = document.querySelectorAll(".carousel img");
const dots   = document.querySelectorAll(".dot");
const total  = images.length;

function update() {
  const offset = -index * 300;          // 300 = image width
  document.querySelector(".carousel").style.transform = `translateX(${offset}px)`;
  dots.forEach((d,i)=>d.classList.toggle("active", i===index));
}
function next() { index = (index+1)%total; update(); }
function prev() { index = (index-1+total)%total; update(); }

document.querySelector(".next").addEventListener("click", next);
document.querySelector(".prev").addEventListener("click", prev);
dots.forEach((dot,i)=>dot.addEventListener("click",()=>{ index=i; update(); }));

// Keyboard navigation (accessibility)
document.addEventListener("keydown", e=>{
  if (e.key==="ArrowRight") next();
  if (e.key==="ArrowLeft")  prev();
});
```

> **Security** – No user input at all, so XSS is impossible. All images are from a trusted placeholder service; in a real project you’d host your own assets or whitelist domains.

---

## 3️⃣3️⃣ Reference list (MDN)

| Topic | MDN URL |
|-------|----------|
| HTML basics | <https://developer.mozilla.org/en-US/docs/Learn/HTML> |
| Semantic elements | <https://developer.mozilla.org/en-US/docs/Web/HTML/Element#sectioning_content> |
| Forms & validation | <https://developer.mozilla.org/en-US/docs/Learn/Forms> |
| CSS cascade | <https://developer.mozilla.org/en-US/docs/Web/CSS/Cascade> |
| Box model | <https://developer.mozilla.org/en-US/docs/Learn/CSS/Building_blocks/The_box_model> |
| Positioning | <https://developer.mozilla.org/en-US/docs/Web/CSS/position> |
| Flexbox | <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout> |
| Grid | <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout> |
| Media queries | <https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries/Using_media_queries> |
| CSS custom properties | <https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties> |
| Typography & @font-face | <https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face> |
| Transitions & animations | <https://developer.mozilla.org/en-US/docs/Web/CSS/transition> |
| Pseudo‑classes/elements | <https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes> |
| JavaScript declarations || Topic | MDN URL |
|-------|----------|
| **JavaScript fundamentals** (variables, types, functions) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Grammar_and_types> |
| **Strict mode** (enabled automatically in modules) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Strict_mode> |
| **`querySelector` / `querySelectorAll`** | <https://developer.mozilla.org/en-US/docs/Web/API/Document/querySelector> |
| **HTMLCollection vs. NodeList** | <https://developer.mozilla.org/en-US/docs/Web/API/HTMLCollection> |
| **Event handling** (addEventListener, delegation) | <https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Building_blocks/Events> |
| **`preventDefault()` & `stopPropagation()`** | <https://developer.mozilla.org/en-US/docs/Web/API/Event/preventDefault> |
| **Passive event listeners** | <https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener#parameters> |
| **Fetch API** (GET, POST, streaming) | <https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API> |
| **`async` / `await`** (working with promises) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/await> |
| **`FormData` API** (multipart/form data) | <https://developer.mozilla.org/en-US/docs/Web/API/FormData> |
| **JavaScript modules** (`type="module"`) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules> |
| **Dynamic `import()`** (code‑splitting) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import> |
| **`localStorage` / `sessionStorage`** | <https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage> |
| **Timers** (`setTimeout`, `setInterval`, `clearTimeout`, `clearInterval`) | <https://developer.mozilla.org/en-US/docs/Web/API/WindowOrWorkerGlobalScope/setTimeout> |
| **`requestAnimationFrame`** (smooth animations) | <https://developer.mozilla.org/en-US/docs/Web/API/window/requestAnimationFrame> |
| **Error handling** (`try…catch`, `throw`, custom errors) | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/try...catch> |
| **Custom Error classes** | <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Error#custom_error_types> |
| **ARIA landmarks & roles** (dialog, region, navigation) | <https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles> |
| **`aria-live`** (polite/assertive announcements) | <https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Live_Regions> |
| **`prefers-reduced‑motion` media query** | <https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion> |
| **`focus-visible` pseudo‑class** (keyboard focus) | <https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-visible> |
| **`rel="noopener"` / `rel="noreferrer"`** (prevent reverse‑tabnabbing) | <https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#security_and_privacy> |
| **Content Security Policy (CSP) basics** | <https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP> |
| **WCAG Contrast Checker** (accessibility testing) | <https://developer.mozilla.org/en-US/docs/Web/Accessibility/Understanding_WCAG/Contrast> |
| **Responsive design fundamentals** | <https://developer.mozilla.org/en-US/docs/Learn/CSS/CSS_layout/Responsive_Design> |
| **Box‑sizing best practice** | <https://developer.mozilla.org/en-US/docs/Web/CSS/box-sizing> |
| **CSS `gap` for Flexbox** (modern browsers) | <https://developer.mozilla.org/en-US/docs/Web/CSS/gap> |
| **`object-fit`** (image cropping) | <https://developer.mozilla.org/en-US/docs/Web/CSS/object-fit> |
| **`font-display: swap`** (prevent FOIT) | <https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display> |
| **`@keyframes`** (CSS animation definition) | <https://developer.mozilla.org/en-US/docs/Web/CSS/@keyframes> |
| **`scroll-behavior: smooth`** (native smooth scrolling) | <https://developer.mozilla.org/en-US/docs/Web/CSS/scroll-behavior> |
| **`transform`** (hardware‑accelerated animations) | <https://developer.mozilla.org/en-US/docs/Web/CSS/transform> |
| **`will-change`** (hint to the browser) | <https://developer.mozilla.org/en-US/docs/Web/CSS/will-change> |

---

## 🎉 Final notes

* **Security first** – Always treat any data that originates from the user, the URL, or an external API as *untrusted*. Use **sanitisation**, **strict CSP**, and **avoid `innerHTML`** wherever possible.  
* **Performance tip** – Keep the number of DOM queries low (`document.querySelector` → cache), use **CSS `gap`** instead of margins, and prefer **`requestAnimationFrame`** for visual updates.  
* **Accessibility matters** – Keyboard navigation, ARIA roles, `prefers-reduced-motion`, and colour contrast are not optional; they protect users and improve SEO.  
* **Modern tooling (optional)** – If you later decide you need a bundler, tools like **Vite** or **esbuild** add zero‑config ES‑module support, TypeScript checking, and minification without sacrificing the simplicity you’ve learned here.

Now you have a **complete, security‑hardened, modern** vanilla‑JS starter kit, plus a library of reusable layout patterns (named‑grid, Flexbox cards, nav bars, image galleries) and ready‑to‑run mini‑apps. Build, experiment, and iterate—*the web is yours to shape safely.* 🚀