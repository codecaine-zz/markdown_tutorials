# Bootstrap 5 – Complete API Guide (latest 5.3.x)  
*(plain language, every code block explains **why it’s useful**, a **common mistake** to avoid, a **best‑practice tip**, and a link to the official Bootstrap documentation. All JavaScript uses modern **ES modules**, **`const` / `let`**, **strict mode**, and follows security‑first best practices.)*  

---  

## Table of Contents
| # | Topic |
|---|-------|
| 0️⃣ | Project setup – CDN vs. npm |
| 1️⃣ | Adding the Bootstrap stylesheet & JavaScript bundle |
| 2️⃣ | Basic HTML skeleton (Bootstrap‑ready) |
| 3️⃣ | Grid system – rows, columns, breakpoints |
| 4️⃣ | Flex utilities (quick one‑dimensional layout) |
| 5️⃣ | Responsive helpers (`d‑*`, `order‑*`, `offset‑*`) |
| 6️⃣ | Typography & color utilities |
| 7️⃣ | Buttons, badges, chips, and progress bars |
| 8️⃣ | Navigation bars (navbar, off‑canvas, scrollspy) |
| 9️⃣ | Cards, accordions, and lists (media object) |
| 🔟 | Tables, pagination, and data display |
| 1️⃣1️⃣ | Forms – inputs, validation, floating labels, custom selects |
| 1️⃣2️⃣ | Modals, tooltips, popovers, and toasts |
| 1️⃣3️⃣ | Carousel, collapse, and spinners |
| 1️⃣4️⃣ | Icons (Bootstrap Icons) |
| 1️⃣5️⃣ | JavaScript API – Instantiating & controlling components |
| 1️⃣6️⃣ | Customising via Sass (variables, map‑merge) |
| 1️⃣7️⃣ | Dark mode & CSS custom properties |
| 1️⃣8️⃣ | Security checklist (CSP, XSS, link targets) |
| 1️⃣9️⃣ | Performance checklist (CDN, defer, tree‑shaking) |
| 2️⃣0️⃣ | Quick reference table (class → purpose) |
| 2️⃣1️⃣ | Where to find the official docs |

---  

## 0️⃣ Project setup – CDN vs. npm  

| Approach | When to use it | How to set it up |
|----------|----------------|------------------|
| **CDN (quick prototype)** | Small demos, learning, CodePen, no build step. | Add two `<link>`/`<script>` tags (see §1). |
| **npm + bundler** | Real projects, theming with Sass, code‑splitting. | `npm init -y` → `npm i bootstrap@5.3` → import in your JS entry file (`import 'bootstrap';`). Use a bundler (Vite, Webpack, Parcel) that supports **ESM**. |
| **Download the source** | Offline work, corporate firewalls that block CDN. | Grab the *compiled* CSS/JS from <https://github.com/twbs/bootstrap/releases>. Place them in a `vendor/` folder and reference locally. |

> **Why** – The CDN is fastest for a *starter* because browsers already cache the file from other sites.  
> **Pitfall** – Using the **minified** bundle without the **popper** dependency (Bootstrap 5’s dropdowns, tooltips, popovers need Popper). The bundled file (`bootstrap.bundle.min.js`) already contains Popper, but the *plain* `bootstrap.min.js` does **not**.  
> **Tip** – For production, serve the bundled file **with an SRI hash** (Sub‑resource Integrity) so the browser can verify it hasn’t been tampered with.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/getting-started/introduction/>

---  

## 1️⃣ Adding the Bootstrap stylesheet & JavaScript bundle  

```html
<!-- 1️⃣  Bootstrap CSS (latest 5.3) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUa6yYh7e6YbKf5V8fK+f8uD86x8O0QwVYb2aNwGQ7+0jXG9V8Y6Kz4R4p6i"
      crossorigin="anonymous">

<!-- 2️⃣  Bootstrap Icons (optional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      rel="stylesheet">

<!-- 3️⃣  Bootstrap JS bundle (includes Popper) -->
<script type="module"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoF8nEJg4gk8GZbZK7qfJ9F+4cH5QeFZ6zF4P5pKj2y7Y3V"
        crossorigin="anonymous"
        defer></script>
```

* **Why** – The CSS gives you the visual toolkit; the JS bundle adds interactive components (modals, dropdowns, tooltips) **without** needing jQuery.  
* **Common mistake** – Adding `defer` **after** an `type="module"` script is unnecessary; modules are deferred by default. It’s harmless but can be confusing.  
* **Best‑practice tip** – Keep the **integrity** attribute; it protects against supply‑chain attacks. Store the hash in a version‑controlled file (e.g., a “vendor‑hashes.txt”) so you can audit updates.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/getting-started/introduction/>

---  

## 2️⃣ Basic HTML skeleton (Bootstrap‑ready)  

```html
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bootstrap 5 Starter</title>

  <!-- Bootstrap CSS & Icons (see §1) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-…" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

  <!-- Optional custom overrides -->
  <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Content will go here -->
  <main class="container py-4">
    <h1 class="display-4">Hello, Bootstrap 5!</h1>
    <p class="lead">A quick start page with the most common utilities.</p>
  </main>

  <!-- Bootstrap bundle (see §1) -->
  <script type="module"
          src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-…" crossorigin="anonymous"></script>
</body>
</html>
```

* **Why** – `data-bs-theme="light"` (or `"dark"`) enables the built‑in dark‑mode switch introduced in v5.3.  
* **Common mistake** – Forgetting the `<meta name="viewport">`; on mobile the layout collapses to a tiny width and the responsive grid breaks.  
* **Tip** – Add the `container` class **once** at the top‑level content (`<main>`). All inner rows/columns will then be properly padded.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/layout/container/>

---  

## 3️⃣ Grid system – rows, columns, breakpoints  

Bootstrap’s grid is **12‑column Flexbox** based. Breakpoint prefixes:

| Prefix | Min‑width |
|--------|-----------|
| `col-` | <576 px (extra‑small) |
| `col-sm-` | ≥576 px |
| `col-md-` | ≥768 px |
| `col-lg-` | ≥992 px |
| `col-xl-` | ≥1200 px |
| `col-xxl-` | ≥1400 px |

### Simple 3‑column layout (responsive)

```html
<div class="row g-3"><!-- g-3 = gap 1rem -->
  <div class="col-12 col-md-4">
    <div class="p-3 border bg-white">Column 1</div>
  </div>
  <div class="col-12 col-md-4">
    <div class="p-3 border bg-white">Column 2</div>
  </div>
  <div class="col-12 col-md-4">
    <div class="p-3 border bg-white">Column 3</div>
  </div>
</div>
```

* **Why** – On phones (`col-12`) each column stacks full‑width; on medium+ screens (`col-md-4`) they sit side‑by‑side.  
* **Common mistake** – Adding **more than 12** column units to a single row (e.g., three `col-md-5`). The layout will wrap unexpectedly.  
* **Tip** – Use the **auto‑layout** (`.col`) when you want equal‑width columns without calculating units:  

```html
<div class="row">
  <div class="col"><div class="p-3 border">Equal 1</div></div>
  <div class="col"><div class="p-3 border">Equal 2</div></div>
  <div class="col"><div class="p-3 border">Equal 3</div></div>
</div>
```

📚 **Docs**: <https://getbootstrap.com/docs/5.3/layout/grid/>

---  

## 4️⃣ Flex utilities (quick one‑dimensional layout)  

| Utility | Example | What it does |
|---------|---------|--------------|
| `d-flex` | `<div class="d-flex">…</div>` | Turns container into a Flexbox. |
| `justify-content-*` | `justify-content-between` | Horizontal spacing (start, end, center, around, between). |
| `align-items-*` | `align-items-center` | Vertical alignment of items in the cross‑axis. |
| `flex-fill` | `<div class="flex-fill">…</div>` | Makes element grow to fill remaining space. |
| `flex-nowrap` | `<div class="d-flex flex-nowrap">…</div>` | Prevents wrapping. |
| `gap-*` | `gap-3` | Adds consistent spacing (replaces manual margins). |
| `order-*` | `order-2` | Changes visual order without altering markup. |

### Example: Nav bar with centered brand, right‑aligned links  

```html
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="#">
      <i class="bi bi-bootstrap-fill"></i> MySite
    </a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav gap-2">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
      </ul>
    </div>
  </div>
</nav>
```

* **Why** – Flex utilities give you precise control over alignment (`justify-content-between`) while the built‑in `navbar` component adds responsive toggling.  
* **Common mistake** – Forgetting the **`data-bs-toggle="collapse"`** attribute on the toggle button; the menu will never open on mobile.  
* **Tip** – Use `gap-2` (or any `gap-*`) on the `<ul>` to keep consistent spacing without extra margin classes on each `<li>`.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/utilities/flex/>

---  

## 5️⃣ Responsive helpers (`d-*`, `order-*`, `offset-*`)  

| Class | Description |
|-------|-------------|
| `d-none` | Hide element on **all** breakpoints. |
| `d-sm-block`, `d-md-inline`, `d-lg-flex` | Show at a specific breakpoint (and up). |
| `order-1` … `order-12` | Reorder flex items (default `order-0`). |
| `offset-md-2` | Add left margin equal to 2 columns on **md**+ screens. |
| `visually-hidden` | Hide from visual UI but keep accessible to screen readers (ARIA). |
| `text-truncate` | Single‑line text ellipsis (requires a width). |

### Example: Show a “Download” button only on large screens  

```html
<button class="btn btn-primary d-none d-lg-inline-block ms-3">
  <i class="bi bi-download"></i> Download
</button>
```

* **Why** – Reduces clutter on mobile while preserving functionality on desktop.  
* **Common mistake** – Combining `d-none` with a *later* breakpoint class **without** placing the breakpoint class **after** `d-none` (order matters). The later class will **override** the earlier one, but if you write `d-lg-inline-block d-none` the element will stay hidden.  
* **Tip** – Follow the **mobile‑first** order: start with the *most‑general* (`d-none`) and then add *larger* breakpoint overrides (`d-lg-inline-block`).  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/utilities/display/>

---  

## 6️⃣ Typography & color utilities  

| Utility | Example | Result |
|---------|---------|--------|
| `display-1` … `display-6` | `<h1 class="display-4">Big heading</h1>` | Large, responsive headings (lighter weight). |
| `lead` | `<p class="lead">Larger paragraph.</p>` | Increases font size & line‑height. |
| `text-muted` | `<small class="text-muted">Fine print</small>` | Low‑contrast, accessible grey. |
| `text-primary` / `text-secondary` | `<span class="text-success">Success</span>` | Applies theme colour. |
| `bg-primary` (and `bg-*`) | `<div class="bg-dark text-white p-3">Dark block</div>` | Background colour + optional text colour. |
| `fw-bold` / `fst-italic` | `<p class="fw-bold fst-italic">Bold italic</p>` | Font‑weight / style shortcuts. |
| `text-truncate` | `<div class="text-truncate" style="width:150px;">Very long text …</div>` | Single‑line ellipsis. |

### Example: Card title with muted subtitle

```html
<div class="card w-100">
  <div class="card-body">
    <h5 class="card-title">Bootstrap 5 Guide</h5>
    <h6 class="card-subtitle mb-2 text-muted">Complete API reference</h6>
    <p class="card-text">A hands‑on, beginner‑to‑advanced walkthrough.</p>
    <a href="#" class="card-link">Read more</a>
  </div>
</div>
```

* **Why** – The typographic utilities keep the markup **semantic** while avoiding custom CSS.  
* **Common mistake** – Using `text-muted` on a **dark** background without also setting a contrasting text colour; the text becomes unreadable.  
* **Tip** – Pair `text-muted` with `bg-light` (or use `text-body-secondary` introduced in v5.3, which adapts to the current theme).  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/utilities/text/>

---  

## 7️⃣ Buttons, badges, chips, and progress bars  

### Buttons  

```html
<button type="button" class="btn btn-primary">Primary</button>
<button type="button" class="btn btn-outline-secondary">Outline</button>
<button type="button" class="btn btn-success btn-lg">Large</button>
<button type="button" class="btn btn-danger btn-sm">Small</button>
```

* **Why** – `btn` gives the base styling; the `-primary`, `-outline-*`, size modifiers (`btn-lg`, `btn-sm`) are composable.  
* **Common mistake** – Nesting a `<button>` inside an `<a>` or vice‑versa – results in invalid HTML and unpredictable behavior.  
* **Tip** – Use **`type="button"`** on non‑submit buttons inside forms to avoid accidental form submission.  

### Badges  

```html
<span class="badge bg-success">New</span>
<a href="#" class="badge bg-warning text-dark">Beta</a>
```

* **Why** – Badges convey status/labels without extra markup.  
* **Common mistake** – Forgetting `bg-` prefix (`badge-primary` → removed in v5). Use `bg-primary` instead.  
* **Tip** – Combine with `position-absolute` for notification bubbles (e.g., on an icon).  

### Progress bar  

```html
<div class="progress" style="height: 1.5rem;">
  <div class="progress-bar bg-info" role="progressbar"
       style="width: 68%;" aria-valuenow="68"
       aria-valuemin="0" aria-valuemax="100">
    68%
  </div>
</div>
```

* **Why** – Accessible (`role="progressbar"` + `aria-valuenow`).  
* **Common mistake** – Setting `width` **outside** of the inner `.progress-bar`; the bar won’t display.  
* **Tip** – Use CSS custom properties to change height or color dynamically (`--bs-progress-height`, `--bs-progress-bg`).  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/components/buttons/> and <https://getbootstrap.com/docs/5.3/components/badge/>

---  

## 8️⃣ Navigation bars (navbar, off‑canvas, scrollspy)  

### 8.1 Standard responsive navbar (see §4 for layout)  

```html
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="bi bi-bootstrap"></i> Brand
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMain"
            aria-controls="navMain" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Docs</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            More
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Separated</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search"
               placeholder="Search…" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
```

* **Why** – The `navbar-expand-lg` class makes the menu collapse below 992 px. Dropdowns are automatically wired via Popper.  
* **Common mistake** – Not providing `aria-label` on the `<form>` or search input, which harms screen‑reader navigation.  
* **Tip** – For **accessibility**, include `aria-current="page"` on the active link (`<a class="nav-link active" aria-current="page">`).  

### 8.2 Off‑canvas navigation (mobile‑first drawer)  

```html
<button class="btn btn-primary" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav"
        aria-controls="offcanvasNav">
  <i class="bi bi-list"></i> Menu
</button>

<div class="offcanvas offcanvas-start" tabindex="-1"
     id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasNavLabel">Menu</h5>
    <button type="button" class="btn-close text-reset"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
    </ul>
  </div>
</div>
```

* **Why** – Off‑canvas replaces the classic “mobile navbar” with a sliding drawer that can contain any markup (menus, search forms, etc.).  
* **Common mistake** – Forgetting `tabindex="-1"` on the off‑canvas element – keyboard focus will be trapped incorrectly.  
* **Tip** – Pair the off‑canvas with `data-bs-scroll="true"` if you want the background page to stay scrollable while the drawer is open.  

### 8.3 Scrollspy (auto‑highlight navigation while scrolling)

```html
<nav id="toc" class="nav flex-column sticky-top"
     style="top: 2rem;">
  <a class="nav-link" href="#section1">Section 1</a>
  <a class="nav-link" href="#section2">Section 2</a>
  <a class="nav-link" href="#section3">Section 3</a>
</nav>

<div data-bs-spy="scroll" data-bs-target="#toc"
     data-bs-offset="100" class="scrollspy-example"
     tabindex="0" style="height:400px; overflow:auto;">
  <section id="section1"><h2>Section 1</h2>…</section>
  <section id="section2"><h2>Section 2</h2>…</section>
  <section id="section3"><h2>Section 3</h2>…</section>
</div>
```

* **Why** – `data-bs-spy="scroll"` watches scroll position and automatically adds the `active` class to the matching nav link.  
* **Common mistake** – Forgetting `tabindex="0"` on the scroll container; the plugin requires a focusable element.  
* **Tip** – Adjust `data-bs-offset` to match the height of any fixed header so the active link updates at the right moment.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/components/navbar/> and <https://getbootstrap.com/docs/5.3/components/offcanvas/>

---  

## 9️⃣ Cards, accordions, and lists (media object)  

### 9.1 Card with image, body, and footer  

```html
<div class="card h-100">
  <img src="https://picsum.photos/400/200?image=20"
       class="card-img-top" alt="Random image">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">Card title</h5>
    <p class="card-text flex-grow-1">
      Some quick example text to build on the card title and make up the bulk of the card's content.
    </p>
    <a href="#" class="btn btn-primary mt-auto">Go somewhere</a>
  </div>
  <div class="card-footer text-muted">
    Updated 3 mins ago
  </div>
</div>
```

* **Why** – `h-100` forces equal height when the card is placed inside a flex container; `flex-grow-1` pushes the button to the bottom.  
* **Common mistake** – Using `card-img` instead of `card-img-top` (the former makes the image a background, breaking the layout).  
* **Tip** – Combine `text-truncate` on the title if you need a single‑line ellipsis in a grid of cards.  

### 9.2 Accordion (collapsible panels)

```html
<div class="accordion" id="demoAccordion">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#collapseOne"
              aria-expanded="false"
              aria-controls="collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne"
         class="accordion-collapse collapse"
         aria-labelledby="headingOne"
         data-bs-parent="#demoAccordion">
      <div class="accordion-body">
        <strong>This is the first item's content.</strong> It is hidden by default.
      </div>
    </div>
  </div>

  <!-- Repeat for more items -->
</div>
```

* **Why** – The accordion uses the **collapse** component with ARIA attributes baked in, giving keyboard‑accessible expand/collapse.  
* **Common mistake** – Forgetting `data-bs-parent="#demoAccordion"` → panels become independent (multiple can stay open).  
* **Tip** – If you need **all panels closed on load**, add the `collapsed` class to each button and ensure the `.collapse` divs **do not** have `show`.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/components/card/> and <https://getbootstrap.com/docs/5.3/components/accordion/>

---  

## 🔟 Tables, pagination, and data display  

### 10.1 Table with striped rows & hover  

```html
<table class="table table-striped table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th class="text-end">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>Alice</td>
      <td>alice@example.com</td>
      <td class="text-end"><button class="btn btn-sm btn-outline-primary">Edit</button></td>
    </tr>
    <!-- more rows -->
  </tbody>
</table>
```

* **Why** – `table-striped` improves readability; `table-hover` gives visual feedback on rows.  
* **Common mistake** – Forgetting `scope="col"` on `<th>` – reduces accessibility.  
* **Tip** – Use `class="text-end"` (or `text-start`/`text-center`) to align numeric or action columns.  

### 10.2 Pagination component  

```html
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">«</a></li>
    <li class="page-item active"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">»</a></li>
  </ul>
</nav>
```

* **Why** – Semantic `<nav>` with `aria-label` tells screen readers it's a pagination control.  
* **Common mistake** – Using `href="#"` without preventing default navigation in a SPA – the page will scroll to the top.  
* **Tip** – For client‑side navigation, change `href="# "` to `href="javascript:void(0)"` **or** attach a click handler that calls `event.preventDefault()`.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/content/tables/> and <https://getbootstrap.com/docs/5.3/components/pagination/>

---  

## 1️⃣1️⃣ Forms – inputs, validation, floating labels, custom selects  

### 11.1 Basic input group

```html
<form class="row g-3 needs-validation" novalidate>
  <div class="col-md-6">
    <label for="firstName" class="form-label">First name</label>
    <input type="text" class="form-control" id="firstName"
           placeholder="John" required>
    <div class="invalid-feedback">
      Please provide a first name.
    </div>
  </div>

  <div class="col-md-6">
    <label for="lastName" class="form-label">Last name</label>
    <input type="text" class="form-control" id="lastName"
           placeholder="Doe" required>
    <div class="invalid-feedback">
      Please provide a last name.
    </div>
  </div>

  <div class="col-12">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email"
           placeholder="you@example.com" required>
    <div class="invalid-feedback">
      A valid email is required.
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-primary" type="submit">Submit form</button>
  </div>
</form>

<script type="module">
  // Bootstrap’s custom validation (HTML5 + visual feedback)
  const forms = document.querySelectorAll('.needs-validation');
  forms.forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
</script>
```

* **Why** – `needs-validation` + the script above turns native HTML5 validation into Bootstrap’s styled feedback (`.is-valid` / `.is-invalid`).  
* **Common mistake** – Omitting `novalidate` on the `<form>` – the browser will display its own native tooltip instead of the Bootstrap UI.  
* **Tip** – Use **floating labels** (`form-floating`) for a modern UI that saves vertical space (see next example).  

### 11.2 Floating label + custom select

```html
<div class="form-floating mb-3">
  <input type="email" class="form-control" id="floatingEmail"
         placeholder="name@example.com" required>
  <label for="floatingEmail">Email address</label>
  <div class="invalid-feedback">Please enter a valid email.</div>
</div>

<div class="form-floating">
  <select class="form-select" id="floatingSelect" aria-label="Floating label select">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
  </select>
  <label for="floatingSelect">Select an option</label>
</div>
```

* **Why** – The label floats above the field when it has content or focus, saving space.  
* **Common mistake** – Forgetting the `placeholder` attribute on inputs; without it the floating label won’t transition correctly in some browsers.  
* **Tip** – Pair floating labels with `was-validated` (as above) – the error state works seamlessly.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/forms/validation/> and <https://getbootstrap.com/docs/5.3/forms/floating-labels/>

---  

## 1️⃣2️⃣ Modals, tooltips, popovers, and toasts  

### 12.1 Modal (declarative markup + JS API)

```html
<!-- Trigger button -->
<button type="button" class="btn btn-primary"
        data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal markup -->
<div class="modal fade" id="exampleModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close"
                data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
```

* **Why** – `data-bs-toggle="modal"` automatically wires the button to the modal; the `fade` class adds a smooth fade‑in/out.  
* **Common mistake** – Leaving `role="dialog"` off the `.modal` container – it reduces accessibility. (Bootstrap adds it automatically when the JS initializes, but only if the markup follows the pattern).  
* **Tip** – To **focus** the first input inside the modal when it opens, use the `shown.bs.modal` event:

```js
const myModal = document.getElementById('exampleModal');
myModal.addEventListener('shown.bs.modal', () => {
  myModal.querySelector('input, textarea, select')?.focus();
});
```

### 12.2 Tooltip (lightweight, requires Popper – already bundled)

```html
<button type="button" class="btn btn-outline-secondary"
        data-bs-toggle="tooltip" data-bs-placement="top"
        title="Tooltip on top">
  Hover me
</button>

<script type="module">
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
</script>
```

* **Why** – Tooltips give extra context without cluttering the UI.  
* **Common mistake** – Not initializing them (Bootstrap does **not** auto‑init tooltips).  
* **Tip** – Use `trigger: 'focus'` (via `data-bs-trigger="focus"`) for keyboard‑only users – the tooltip appears on focus and disappears on blur.  

### 12.3 Popover (rich content)

```html
<button type="button" class="btn btn-lg btn-danger"
        data-bs-toggle="popover" title="Popover title"
        data-bs-content="And here is some <strong>HTML</strong> content."
        data-bs-html="true">
  Click for popover
</button>

<script type="module">
  const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
  const popoverList = [...popoverTriggerList].map(el => new bootstrap.Popover(el));
</script>
```

* **Why** – Popovers can contain **HTML** (set `data-bs-html="true"`).  
* **Common mistake** – Forgetting `data-bs-html="true"` when you actually need HTML – the markup will be rendered as plain text.  
* **Tip** – **Never** inject untrusted user data into a popover’s `data-bs-content`; always sanitise or use `textContent`.  

### 12.4 Toast (auto‑dismissable notifications)

```html
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
  <div id="liveToast" class="toast" role="alert"
       aria-live="assertive" aria-atomic="true"
       data-bs-autohide="false">
    <div class="toast-header">
      <i class="bi bi-info-circle me-2"></i>
      <strong class="me-auto">Notice</strong>
      <small>just now</small>
      <button type="button" class="btn-close ms-2 mb-1"
              data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Your changes have been saved.
    </div>
  </div>
</div>

<script type="module">
  const toastEl = document.getElementById('liveToast');
  const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
  // Show toast on page load (demo)
  toast.show();
</script>
```

* **Why** – Toasts appear in a corner, auto‑hide after a delay, and are ARIA‑friendly.  
* **Common mistake** – Placing the toast container **inside** a `overflow:hidden` parent – it will be clipped.  
* **Tip** – Use the `position-fixed` utility classes (`top-0`, `bottom-0`, `start-0`, `end-0`) to locate the container reliably.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/components/modal/> and <https://getbootstrap.com/docs/5.3/components/toasts/>

---  

## 1️⃣3️⃣ Carousel, collapse, and spinners  

### 13.1 Carousel (auto‑slide, touch support)

```html
<div id="demoCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#demoCarousel"
            data-bs-slide-to="0" class="active" aria-current="true"
            aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#demoCarousel"
            data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#demoCarousel"
            data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://picsum.photos/900/300?image=30"
           class="d-block w-100" alt="First slide">
    </div>
    <div class="carousel-item">
      <img src="https://picsum.photos/900/300?image=31"
           class="d-block w-100" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img src="https://picsum.photos/900/300?image=32"
           class="d-block w-100" alt="Third slide">
    </div>
  </div>

  <button class="carousel-control-prev" type="button"
          data-bs-target="#demoCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button"
          data-bs-target="#demoCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
```

* **Why** – The carousel gives a ready‑made slideshow with **keyboard**, **touch**, and **ARIA** support.  
* **Common mistake** – Using large, unoptimised images → the carousel becomes sluggish on mobile.  
* **Tip** – Add `loading="lazy"` to each `<img>` for native lazy‑loading, or serve responsive images via `<picture>`/`srcset`.  

### 13.2 Collapse (show/hide content)  

```html
<p>
  <a class="btn btn-outline-primary" data-bs-toggle="collapse"
     href="#collapseExample" role="button" aria-expanded="false"
     aria-controls="collapseExample">
    Toggle more info
  </a>
</p>

<div class="collapse" id="collapseExample">
  <div class="card card-body">
    This content is hidden until the button above is clicked.
  </div>
</div>
```

* **Why** – Collapse lets you hide large sections (FAQs, extra details) while keeping the markup in the DOM.  
* **Common mistake** – Forgetting `aria-controls` on the trigger, which impairs screen‑reader users.  
* **Tip** – Use `data-bs-parent="#accordion"` if you want an accordion (only one panel open at a time).  

### 13.3 Spinner (loading indicator)

```html
<div class="spinner-border text-primary" role="status">
  <span class="visually-hidden">Loading…</span>
</div>
<div class="spinner-grow text-success" role="status">
  <span class="visually-hidden">Loading…</span>
</div>
```

* **Why** – Provides visual feedback while an asynchronous operation is in progress.  
* **Common mistake** – Using spinners without the `"visually-hidden"` text; screen readers get no info about the loading state.  
* **Tip** – Pair spinners with `aria-live="polite"` containers that announce status changes.  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/components/carousel/> and <https://getbootstrap.com/docs/5.3/components/spinners/>

---  

## 1️⃣4️⃣ Icons (Bootstrap Icons)  

```html
<!-- Include the CSS from the CDN (see §1) -->
<i class="bi bi-alarm-fill text-danger me-2"></i> Alarm
```

* **Why** – Bootstrap Icons are SVG‑based, scalable, and theme‑aware (they inherit `color`).  
* **Common mistake** – Forgetting to add `aria-hidden="true"` when the icon is **decorative** only; screen readers will read the class name as nonsense.  
* **Tip** – For icons that convey meaning, add a `<span class="visually-hidden">Alarm</span>` alongside the `<i>` or use `role="img"` with an `aria-label`.  

📚 **Docs**: <https://icons.getbootstrap.com/>

---  

## 1️⃣5️⃣ JavaScript API – Instantiating & controlling components  

Bootstrap ships **ESM** classes that can be imported from the bundle (or from the `node_modules` if you install via npm).  

### 5️⃣5 1 Example: Programmatic modal control  

```js
import { Modal } from 'bootstrap';

// Get the modal element
const myModalEl = document.getElementById('exampleModal');

// Create a Modal instance
const myModal = new Modal(myModalEl, {
  backdrop: 'static',   // prevent closing when clicking outside
  keyboard: false       // disable ESC key
});

// Open the modal from JavaScript
document.getElementById('openBtn').addEventListener('click', () => myModal.show());

// Close it after 5 seconds (demo)
setTimeout(() => myModal.hide(), 5000);
```

* **Why** – Instantiating the class gives you **full programmatic control** (show/hide, toggle, dispose).  
* **Common mistake** – Creating a new instance **every time** you click a button – you end up with many duplicate listeners. Store the instance in a variable (as above).  
* **Tip** – Call `instance.dispose()` when you permanently remove the element from the DOM; it cleans up listeners and avoids memory leaks.  

### 5️⃣5 2 Example: Using `bootstrap.Collapse` to open a panel from code  

```js
import { Collapse } from 'bootstrap';

const myCollapse = new Collapse('#myCollapse', { toggle: false });

// Open
document.getElementById('openCollapse').addEventListener('click', () => myCollapse.show());
// Close
document.getElementById('closeCollapse').addEventListener('click', () => myCollapse.hide());
```

* **Why** – The `toggle: false` option prevents the component from automatically opening on page load.  
* **Common mistake** – Passing an **HTMLCollection** instead of a single element string/HTMLElement – the constructor will throw.  
* **Tip** – You can toggle a list of accordions by iterating over the NodeList and creating a `Collapse` instance for each.  

### Available component classes (ESM)

| Component | Import path (npm) | Typical use |
|-----------|-------------------|-------------|
| `Alert` | `import { Alert } from 'bootstrap';` | programmatic closing |
| `Button` | `import { Button } from 'bootstrap';` | toggle state |
| `Carousel` | `import { Carousel } from 'bootstrap';` | start/stop cycling |
| `Collapse` | `import { Collapse } from 'bootstrap';` | open/close panels |
| `Dropdown` | `import { Dropdown } | bootstrap';` | manual show/hide |
| `Modal` | `import { Modal } from 'bootstrap';` | show/hide with options |
| `Popover` | `import { Popover } from 'bootstrap';` | dynamic content |
| `ScrollSpy` | `import { ScrollSpy } from 'bootstrap';` | highlight nav |
| `Tab` | `import { Tab } from 'bootstrap';` | switch tabs |
| `Toast` | `import { Toast } from 'bootstrap';` | show notifications |
| `Tooltip` | `import { Tooltip } from 'bootstrap';` | hover/focus hints |

> **Note** – When you import from the **bundle** (`bootstrap.bundle.min.js`) you get all components in one file. When you use **ESM imports**, you can tree‑shake unused components for a smaller final bundle (especially useful with bundlers like Vite).  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/getting-started/javascript/>

---  

## 1️⃣6️⃣ Customising via Sass (variables, map‑merge)  

Bootstrap’s source is built with **Sass**. To customise colours, breakpoints, or components you can create a `custom.scss` file:

```scss
// custom.scss – place in your src/ directory
@use "bootstrap/scss/functions";
@use "bootstrap/scss/variables" as *;
@use "bootstrap/scss/maps";

// 1️⃣ Override a color
$primary: #0066cc !default;          // new primary colour
$body-bg: #fdfdfd;

// 2️⃣ Add a new breakpoint (e.g., xxxl)
$grid-breakpoints: map-merge(
  $grid-breakpoints,
  ("xxxl": 1800px)
);

// 3️⃣ Disable a component’s CSS if you never use it (reduces bundle size)
$enable-caret: false;
$enable-card: true;

// 4️⃣ Import the rest of Bootstrap
@import "bootstrap/scss/bootstrap";
```

Then compile with **Sass** (e.g., `sass src/custom.scss dist/custom.css --no-source-map`).  

* **Why** – Overriding Sass variables **before** importing Bootstrap changes the generated CSS throughout the framework (no need to write extra overrides).  
* **Common mistake** – Changing a variable **after** `@import "bootstrap"` – it has no effect because the variable has already been used.  
* **Tip** – Use the **`map-merge`** technique for breakpoint extensions or for extending the `$theme-colors` map (adds new colour utilities automatically).  

📚 **Docs**: <https://getbootstrap.com/docs/5.3/customize/sass/>

---  

## 1️⃣7️⃣ Dark mode & CSS custom properties  

Bootstrap 5.3 introduced **built‑in dark‑mode support** using the `data-bs-theme` attribute.  

```html
<html lang="en" data-bs-theme="dark">
  …
</html>
```

All theme‑aware utilities (`bg-body`, `text-body`, `border`, `btn-primary`, etc.) automatically adapt.  

### Overriding dark colours  

```scss
// custom-dark.scss (import after Bootstrap)
[data-bs-theme="dark"] {
  // Example: make the primary colour a teal in dark mode
  $primary: #20c997 !global;
  // Re‑declare the CSS variable used by Bootstrap
  --bs-primary: #20c997;
}
```

* **Why** – Users can toggle theme at runtime, while the CSS automatically recalculates colour variables.  
* **Common mistake** – Forgetting to add `data-bs-theme` to the **root** (`<html>` or `<body>`); only then will the CSS variables switch.  
* **Tip** – Persist the user’s preference in **`localStorage`** and set the attribute on page load:

```js
const saved = localStorage.getItem('bsTheme');
if (saved) document.documentElement.dataset.bsTheme = saved;
```

📚 **Docs**: <https://getbootstrap.com/docs/5.3/customize/color-modes/>

---  

## 1️⃣8️⃣ Security checklist (XSS, CSP, link targets)  

| Area | Checklist |
|------|-----------|
| **External resources** | Use **SRI** (`integrity` attribute) for CDN links. |
| **`target="_blank"`** | Always add `rel="noopener noreferrer"` to prevent reverse‑tabnabbing. |
| **Dynamic HTML** | **Never** insert user‑generated strings with `innerHTML`. Use `textContent` or a sanitiser (DOMPurify). |
| **Form handling** | Add `novalidate` if you rely on Bootstrap’s visual validation; still perform **server‑side** validation. |
| **Content‑Security‑Policy** | Example header: <br>`Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; img-src 'self' data: https:` |
| **ARIA & focus** | All interactive components (modals, off‑canvas, popovers) must receive focus when opened and return focus when closed. Use the built‑in events (`shown.bs.modal`, `hidden.bs.modal`). |
| **`autocomplete`** | Add appropriate `autocomplete="off"` or specific values (`email`, `current-password`) for security‑sensitive inputs. |
| **HTTPS** | Load Bootstrap, icons, and any third‑party assets **over HTTPS** only. |

---  

## 1️⃣9️⃣ Performance checklist  

| Item | Why it matters |
|------|----------------|
| **Use the minified bundle** (`bootstrap.bundle.min.js`) | Shrinks download size (~30 KB gzipped). |
| **Leverage browser caching** | Set `Cache‑Control: max-age=31536000` for static assets. |
| **Load CSS in `<head>` and JS with `defer`** | Prevents render‑blocking. |
| **Tree‑shake with ES modules** | Import only the components you need; reduces bundle size dramatically. |
| **Lazy‑load images** (`loading="lazy"`) | Defers off‑screen images, improves first‑paint. |
| **Avoid unnecessary utilities** | Remove unused utility classes via PurgeCSS or `bootstrap/scss/utilities` if you’re building a custom CSS file. |
| **Enable HTTP/2 or HTTP/3** | Allows parallel loading of multiple small CSS/JS files if you split them. |
| **Add `prefetch`/`preload` for critical fonts** | Improves text‑rendering speed. |

---  

## 2️⃣0️⃣ Quick reference table (most‑used classes)  

| Category | Class | Effect |
|----------|-------|--------|
| **Grid** | `container` / `container-fluid` | Fixed‑width vs full‑width wrapper |
| | `row` | Flex container with negative gutters |
| | `col-{breakpoint}-{n}` | Column spanning *n* of 12 |
| | `g-{0‑5}` | Gap (gutter) size |
| **Flex** | `d-flex`, `d-inline-flex` | Turn element into Flex container |
| | `justify-content-*` | Horizontal alignment |
| | `align-items-*` | Vertical alignment |
| | `flex-grow-1` | Fill remaining space |
| **Spacing** | `m-{0‑5}` / `p-{0‑5}` | Margin / padding (scale 0‑5) |
| | `mt-*, mb-*, ms-*, me-*` | Directional shortcuts |
| **Display** | `d-none`, `d-{sm,md,lg,xl,xxl}-block` | Responsive show/hide |
| **Background / text** | `bg-primary`, `bg-light`, `bg-dark`, `bg-opacity-75` | Background color & opacity |
| | `text-primary`, `text-muted`, `text-white-50` | Text colour utilities |
| **Borders** | `border`, `border-0`, `border-start`, `rounded`, `rounded-circle` |
| **Utilities** | `position-relative`, `position-absolute`, `top-0`, `start-0` |
| **Typography** | `display-1`‑`display-6`, `lead`, `fw-bold`, `fst-italic`, `text-uppercase` |
| **Buttons** | `btn`, `btn-primary`, `btn-outline-secondary`, `btn-lg`, `btn-sm` |
| **Cards** | `card`, `card-body`, `card-title`, `card-img-top`, `h-100` |
| **Modals** | `modal`, `modal-dialog`, `modal-content`, `fade`, `show` |
| **Carousel** | `carousel`, `carousel-inner`, `carousel-item`, `carousel-caption` |
| **Spinners** | `spinner-border`, `spinner-grow` |
| **Alerts** | `alert`, `alert-success`, `alert-dismissible` |
| **Toast** | `toast`, `toast-header`, `toast-body` |
| **Pagination** | `pagination`, `page-item`, `page-link`, `active` |
| **Dropdown** | `dropdown`, `dropdown-toggle`, `dropdown-menu`, `dropdown-item` |
| **Navs / Tabs** | `nav`, `nav-tabs`, `nav-pills`, `nav-link`, `active` |
| **Forms** | `form-control`, `form-select`, `form-check`, `form-check-input`, `form-floating` |
| **Validation** | `needs-validation`, `was-validated`, `is-invalid`, `invalid-feedback` |
| **Tooltips / Popovers** | `tooltip`, `popover`, `data-bs-toggle="tooltip"`/`popover` |
| **Off‑canvas** | `offcanvas`, `offcanvas-start`, `offcanvas-header`, `offcanvas-body` |

---  

## 2️⃣1️⃣ Where to find the official docs  

| Area | URL |
|------|-----|
| **Getting started** | <https://getbootstrap.com/docs/5.3/getting-started/introduction/> |
| **Layout (grid & flex)** | <https://getbootstrap.com/docs/5.3/layout/grid/> |
| **Utilities** | <https://getbootstrap.com/docs/5.3/utilities/> |
| **Components** | <https://getbootstrap.com/docs/5.3/components/> |
| **JavaScript API** | <https://getbootstrap.com/docs/5.3/getting-started/javascript/> |
| **Customising with Sass** | <https://getbootstrap.com/docs/5.3/customize/sass/> |
| **Color modes (dark)** | <https://getbootstrap.com/docs/5.3/customize/color-modes/> |
| **Icons** | <https://icons.getbootstrap.com/> |
| **Accessibility** | <https://getbootstrap.com/docs/5.3/getting-started/accessibility/> |
| **Migration guide (v4 → v5)** | <https://getbootstrap.com/docs/5.3/migration/> |

---  

## 🎉 Wrap‑up  

* **Start** with the CDN skeleton (§1‑§2).  
* **Lay out** your page using the **grid** (§3) for the overall skeleton and **flex utilities** (§4) for inner alignment.  
* **Add components**—navbars, cards, modals, forms—by copying the markup snippets, then **customise** colours or breakpoints via **Sass** (§1️⃣6️⃣) if needed.  
* **Control everything** with the **Bootstrap JavaScript API** (§1️⃣5️⃣) when you need programmatic show/hide, dynamic content, or custom options.  
* **Secure** your site with the checklist (§1️⃣8️⃣) and **optimise** with the performance tips (§1️⃣9️⃣).  

You now have a **complete, up‑to‑date Bootstrap 5 guide** that covers markup, utilities, JavaScript, theming, accessibility, security, and performance—all in plain language and ready for copy‑and‑paste into your own projects. Happy building! 🚀