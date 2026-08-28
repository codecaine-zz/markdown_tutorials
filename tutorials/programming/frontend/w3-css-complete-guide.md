# **w3.css Complete Guide**  
*(plain language, every code block explains **what it’s useful for**, a **common mistake** to avoid, a **best‑practice tip**, and a link to the official w3.css documentation. The guide moves from a blank page to advanced layouts, responsive design, forms, utilities, and a tiny bit of vanilla JavaScript that works hand‑in‑hand with w3.css.)*  

---  

## Table of Contents
| # | Topic |
|---|-------|
| 0️⃣ | Project setup – no build tools needed |
| 1️⃣ | Adding the w3.css stylesheet |
| 2️⃣ | The HTML skeleton (still plain HTML) |
| 3️⃣ | Basic typography & colour utilities |
| 4️⃣ | Layout basics – the **grid** system and **flex** utilities |
| 5️⃣ | Responsive design with the w3‑responsive classes |
| 6️⃣ | Buttons, badges, and chips |
| 7️⃣ | Navigation bars (top, side, and bottom) |
| 8️⃣ | Cards, panels, and image‑overlays |
| 9️⃣ | Tables, lists & pagination |
| 🔟 | Forms – inputs, validation, and accessibility |
| 1️⃣1️⃣ | Modals, tooltips & pop‑overs |
| 1️⃣2️⃣ | Animations & transitions (w3‑animate) |
| 1️⃣3️⃣ | Dark‑mode & CSS custom properties |
| 1️⃣4️⃣ | Tiny JavaScript helpers (`w3.show()`, `w3.toggle()`, `w3.filter()`) |
| 1️⃣5️⃣ | Real‑world page layout that combines **grid + flex** |
| 1️⃣6️⃣ | Security‑first checklist (XSS, CSP, ARIA, link‑targets) |
| 1️⃣7️⃣ | Quick reference table (class → purpose) |
| 1️⃣8️⃣ | Where to find the official docs |

---  

## 0️⃣ Project setup – no build tools needed  

| Step | Action | Why it matters |
|------|--------|----------------|
| **Create a folder** | `mkdir my-w3-site && cd my-w3-site` | Keeps all files together. |
| **Starter files** | `touch index.html style.css` | `index.html` holds markup, `style.css` is for custom overrides only. |
| **Run a tiny dev server** | `python -m http.server 8000` **or** `npx serve .` | Allows `fetch`/`import` calls (CORS issues disappear). |
| **Optional editor** | VS Code + Live Server extension | Auto‑reload on every save. |

> **Common mistake** – Opening `index.html` with `file:///` makes any `fetch()` to an external API fail because of CORS.  
> **Tip** – Keep the folder tiny; you only need HTML, CSS, and optional JS files.  

📚 **Doc**: <https://developer.mozilla.org/en-US/docs/Learn/Getting_started_with_the_web>

---  

## 1️⃣ Adding the w3.css stylesheet  

```html
<!-- Inside <head> of index.html -->
<link rel="stylesheet"
      href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet"
      href="https://www.w3schools.com/lib/w3-theme-blue.css">
```

* **Why** – The first line pulls the core w3.css framework (≈ 30 KB gzipped). The second line adds a *theme* (colour palette, background, button colours).  
* **Common mistake** – Forgetting the **protocol‑relative URL** (`https://`). If you use just `//…` on a `file://` page the stylesheet never loads.  
* **Tip** – Load the theme **after** the core file; the later file overrides the earlier one.  

📚 **Docs**: <https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_default>

---  

## 2️⃣ The HTML skeleton (still plain HTML)  

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width,initial-scale=1">
  <title>My w3.css Site</title>
  <!-- w3.css + theme -->
  <link rel="stylesheet"
        href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet"
        href="https://www.w3schools.com/lib/w3-theme-blue.css">
  <!-- custom overrides (optional) -->
  <link rel="stylesheet" href="style.css">
</head>
<body class="w3-light-grey">

  <!-- page content goes here -->
  <header class="w3-container w3-padding-32 w3-center w3-theme">
    <h1 class="w3-xxxlarge">Welcome to w3.css</h1>
    <p class="w3-large">A lightweight, pure‑CSS framework</p>
  </header>

</body>
</html>
```

* **Why** – The skeleton is *exactly* the same as a regular HTML page; w3.css adds utility classes that you sprinkle onto elements.  
* **Common mistake** – Adding `w3-theme` classes **without** a theme stylesheet results in default black‑on‑white colours that may clash with your brand.  
* **Tip** – Use the `class="w3-theme"` on a container that wraps the whole visible area (often `<body>` or `<header>`) so the theme colours cascade automatically.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_templates.asp>

---  

## 3️⃣ Basic typography & colour utilities  

| Example | Code | What it does | Common mistake | Tip |
|---------|------|--------------|----------------|-----|
| **Large heading** | `<h2 class="w3-xxlarge w3-text-theme">Title</h2>` | `w3-xxlarge` = 36 px (default); `w3-text-theme` pulls the theme colour. | Using `w3-text-theme` on a dark background (low contrast). | Test contrast with a WCAG checker; add `w3-white` or `w3-black` if needed. |
| **Muted paragraph** | `<p class="w3-text-grey">This is a secondary text.</p>` | `w3-text-grey` = #757575, good for secondary info. | Forgetting the `w3-light-grey` background that makes the text hard to read. | Pair `w3-text-grey` with a light background (`w3-white`, `w3-light-grey`). |
| **Background colour** | `<div class="w3-container w3-light-blue w3-padding">…</div>` | `w3-light-blue` = theme‑light colour; `w3-padding` adds 16 px padding. | Using multiple background colours on the same element (the later overrides the first). | Keep **one** background utility per element; nest if you need layered colours. |

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_color_themes.asp>

---  

## 4️⃣ Layout basics – the **grid** system and **flex** utilities  

### 4.1 Grid columns (`w3-row`, `w3-col`)  

```html
<div class="w3-row">
  <div class="w3-col m6 l4 w3-card w3-padding">
    <h3>Column 1</h3>
    <p>Half‑width on medium screens, one‑third on large.</p>
  </div>

  <div class="w3-col m6 l4 w3-card w3-padding">
    <h3>Column 2</h3>
    <p>Same sizing as column 1.</p>
  </div>

  <div class="w3-col l4 w3-card w3-padding">
    <h3>Column 3</h3>
    <p>Only shows on large screens; hidden on medium‑and‑smaller.</p>
  </div>
</div>
```

* **Why** – `w3-row` creates a flex container; `w3-col` splits it into columns. The suffixes (`m6`, `l4`) are **breakpoint‑specific**:  
  * `m` = **medium** (≥601 px)  
  * `l` = **large** (≥993 px)  
  * number = *fraction of 12* (e.g., `l4` = 4/12 = 33 %).  
* **Common mistake** – Adding columns that sum to **more than 12** for a breakpoint. The layout will wrap unexpectedly.  
* **Tip** – Use `w3-col` **without** a size (e.g., just `w3-col`) to let the column **auto‑size** to its content.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_grid.asp>

### 4.2 Flex utilities (`w3-row`, `w3-col`, `w3-display-container`)  

```html
<div class="w3-row w3-center w3-padding-16">
  <div class="w3-col s4">
    <button class="w3-button w3-blue w3-block">Left</button>
  </div>
  <div class="w3-col s4">
    <button class="w3-button w3-green w3-block">Center</button>
  </div>
  <div class="w3-col s4">
    <button class="w3-button w3-red w3-block">Right</button>
  </div>
</div>
```

* **Why** – Adding `w3-display-container` (or `w3-row`) makes the children **flex items** that automatically stretch to equal height. `w3-block` forces the button to fill its column’s width.  
* **Common mistake** – Mixing `w3-block` with `display:inline` elements (e.g., `<a>` without `w3-button`). The element won’t stretch.  
* **Tip** – If you only need **horizontal centering**, combine `w3-row` + `w3-center`; for vertical centering add `w3-justify-center`.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_flexbox.asp>

---  

## 5️⃣ Responsive design with the w3‑responsive classes  

| Class | Breakpoint | Effect |
|-------|------------|--------|
| `w3-hide-small` | `<601 px` | hides element on **small** screens |
| `w3-hide-medium` | `601‑992 px` | hides on **medium** screens |
| `w3-hide-large` | `>992 px` | hides on **large** screens |
| `w3-show-inline-block` | any | forces `display:inline-block` (overrides hidden) |
| `w3-responsive` (on `<img>`) | any | makes the image **max‑width:100%** and height:auto |

### Example – Mobile‑first navigation  

```html
<!-- Desktop nav (visible on medium+ screens) -->
<nav class="w3-bar w3-theme-d2 w3-hide-small">
  <a href="#" class="w3-bar-item w3-button">Home</a>
  <a href="#" class="w3-bar-item w3-button">About</a>
  <a href="#" class="w3-bar-item w3-button">Contact</a>
</nav>

<!-- Hamburger button (visible on small screens only) -->
<button class="w3-button w3-xlarge w3-hide-medium w3-hide-large"
        onclick="w3.toggle('#mobileNav')">☰</button>

<!-- Collapsible mobile menu -->
<div id="mobileNav" class="w3-bar-block w3-hide w3-hide-large w3-hide-medium w3-theme-d2">
  <a href="#" class="w3-bar-item w3-button">Home</a>
  <a href="#" class="w3-bar-item w3-button">About</a>
  <a href="#" class="w3-bar-item w3-button">Contact</a>
</div>
```

* **Why** – The three `w3-hide‑*` classes let you show/hide content *exactly* where you need it. The hamburger button toggles a hidden `<div>` using the tiny built‑in `w3.toggle()` helper (covered later).  
* **Common mistake** – Forgetting to add `w3-hide` **in addition** to `w3-hide-medium`/`large`. The element stays visible on the first load because the JS‑toggle only adds/removes `w3-show`.  
* **Tip** – Put the **mobile menu** *after* the desktop menu in the markup; screen‑readers will encounter the desktop version first (more useful on larger screens).  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_responsive.asp>

---  

## 6️⃣ Buttons, badges, and chips  

| Element | Code | What it does | Common mistake | Tip |
|---------|------|--------------|----------------|-----|
| **Primary button** | `<button class="w3-button w3-blue w3-round">Submit</button>` | `w3-button` gives padding/hover; `w3-blue` theme colour; `w3-round` rounded corners. | Using `<a>` with `w3-button` but forgetting `href="#"` – the element becomes **non‑focusable**. | Prefer `<button>` for actions; use `<a>` only for navigation. |
| **Badge** | `<span class="w3-badge w3-red">3</span>` | Small red circle; great for notifications. | Badges with **large numbers** overflow the circle. | Use `w3-badge w3-large` or custom CSS to enlarge. |
| **Chip** (label + close) | ```html <span class="w3-tag w3-round w3-theme-l1">Tag <i class="w3-button w3-small w3-black w3-hover-red" onclick="this.parentElement.style.display='none'">✖</i></span> ``` | `w3-tag` = pill‑shaped label; inner `<i>` acts as the close button. | Forgetting `type="button"` on the close `<i>` – browsers may treat it as a submit button inside a form. | Keep the close element **outside** a `<form>` or set `type="button"` (if using `<button>`). |

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_buttons.asp>

---  

## 7️⃣ Navigation bars (top, side, and bottom)  

### 7.1 Top navigation (fixed)

```html
<div class="w3-top w3-bar w3-theme w3-large">
  <a href="#" class="w3-bar-item w3-button w3-mobile">Home</a>
  <a href="#" class="w3-bar-item w3-button w3-mobile">Blog</a>
  <a href="#" class="w3-bar-item w3-button w3-mobile">Contact</a>
</div>

<!-- add a top margin to push page content below the fixed bar -->
<div class="w3-container w3-padding-64">
  …page content…
</div>
```

* **Why** – `w3-top` makes the bar **fixed** to the viewport top, similar to `position:fixed`. `w3-mobile` makes each item stack on very small screens.  
* **Common mistake** – Forgetting a top margin/padding (`w3-padding-64`) on the first content block, causing the bar to *cover* the content.  
* **Tip** – Use `w3-bar-block` inside a `<nav>` element for better semantic markup (`<nav class="w3-bar w3-theme">`).  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_navbar.asp>

### 7.2 Sidebar navigation (collapsible)

```html
<!-- Sidebar (hidden on small screens) -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-hide-small"
     id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()"
     class="w3-bar-item w3-button w3-hide-large w3-display-topleft">Close ×</a>
  <a href="#" class="w3-bar-item w3-button">Dashboard</a>
  <a href="#" class="w3-bar-item w3-button">Settings</a>
  <a href="#" class="w3-bar-item w3-button">Profile</a>
</nav>

<!-- Page content – button to open sidebar on small screens -->
<div class="w3-main" style="margin-left:250px">
  <button class="w3-button w3-xlarge w3-hide-large w3-hide-medium"
          onclick="w3_open()">☰</button>
  …rest of page…
</div>

<script type="module">
  // tiny helpers – they exist in the w3.js library but we can write them ourselves
  function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
  }
  function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
  }
</script>
```

* **Why** – `w3-sidebar` + `w3-collapse` give a **slide‑in** effect on small devices; `w3-hide-large` hides the toggle button on larger screens.  
* **Common mistake** – Using `position:fixed` on the sidebar *and* `margin-left:250px` on the content, which doubles the offset when the sidebar is shown.  
* **Tip** – Keep the sidebar **outside** the main content `<div>` so the margin only applies when the sidebar is visible.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_sidebar.asp>

---  

## 8️⃣ Cards, panels, and image‑overlays  

```html
<div class="w3-card-4 w3-margin w3-white">
  <img src="https://picsum.photos/600/200?image=1050"
       alt="Random scenery" style="width:100%">

  <div class="w3-container">
    <h3>Card Title</h3>
    <p>Some descriptive text that explains the card content.</p>
    <button class="w3-button w3-theme w3-right">Read more →</button>
  </div>
</div>
```

* **Why** – `w3-card-4` gives a subtle shadow and rounded corners; `w3-white` sets a clean background.  
* **Common mistake** – Forgetting `alt` on the `<img>` – screen readers lose context and SEO suffers.  
* **Tip** – Add `w3-hover-opacity` to the image if you want a subtle hover effect without extra CSS.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_cards.asp>

### 8.1 Image overlay (text on top of picture)

```html
<div class="w3-display-container w3-margin">
  <img src="https://picsum.photos/800/300?image=1051"
       alt="Mountain view" style="width:100%">

  <div class="w3-display-middle w3-large w3-text-white
              w3-padding w3-black w3-opacity-min">
    Hello from the summit!
  </div>
</div>
```

* **Why** – `w3-display-container` creates a **relative** positioning context; `w3-display-middle` centers the overlay both vertically and horizontally.  
* **Common mistake** – Using `w3-display-bottomright` with a *very long* text string; the text may spill outside the image.  
* **Tip** – Keep overlay text *short* and add `w3-opacity` to darken the background for readability.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_image_overlay.asp>

---  

## 9️⃣ Tables, lists & pagination  

### 9.1 Responsive table

```html
<table class="w3-table w3-striped w3-bordered w3-responsive">
  <thead>
    <tr class="w3-theme">
      <th>#</th><th>Name</th><th>Score</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>1</td><td>Alice</td><td>95</td></tr>
    <tr><td>2</td><td>Bob</td><td>82</td></tr>
    <tr><td>3</td><td>Charlie</td><td>74</td></tr>
  </tbody>
</table>
```

* **Why** – `w3-table` gives basic styling; `w3-striped` adds alternating row colours; `w3-responsive` makes the table horizontally scroll on small screens (no overflow).  
* **Common mistake** – Forgetting `<thead>` – screen readers won’t recognise column headings.  
* **Tip** – Use `scope="col"` on each `<th>` for maximum accessibility.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_table.asp>

### 9.2 Pagination controls

```html
<div class="w3-center w3-padding-16">
  <a href="#first" class="w3-button w3-theme w3-round">&laquo;</a>
  <a href="#2" class="w3-button">2</a>
  <a href="#3" class="w3-button">3</a>
  <a href="#next" class="w3-button w3-theme w3-round">›</a>
</div>
```

* **Why** – `w3-button` creates clickable areas; `w3-round` gives a pill shape for the first/last arrows.  
* **Common mistake** – Using `href="#"` without `event.preventDefault()` – the page jumps to the top.  
* **Tip** – When JavaScript drives pagination, use `href="javascript:void(0)"` or add a `click` handler that calls `event.preventDefault()`.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_pagination.asp>

---  

## 🔟 Forms – inputs, validation, and accessibility  

```html
<form class="w3-container w3-card-4 w3-light-grey w3-margin"
      id="contactForm" novalidate>
  <h2>Contact us</h2>

  <label class="w3-text-theme" for="name">Name</label>
  <input class="w3-input w3-border w3-round"
         type="text" id="name" name="name"
         placeholder="Your name" required minlength="2">

  <label class="w3-text-theme" for="email">Email</label>
  <input class="w3-input w3-border w3-round"
         type="email" id="email" name="email"
         placeholder="you@example.com" required>

  <label class="w3-text-theme" for="msg">Message</label>
  <textarea class="w3-input w3-border w3-round"
            id="msg" name="msg" rows="4"
            required></textarea>

  <button type="submit"
          class="w3-button w3-theme w3-right w3-margin-top">Send</button>
</form>

<script type="module">
  const form = document.getElementById('contactForm');

  form.addEventListener('submit', e => {
    e.preventDefault();                // stop normal POST
    // HTML5 already validated required fields; we add a quick custom check:
    if (form.name.value.trim().length < 2) {
      alert('Name must be at least 2 characters.');
      return;
    }
    // Simulate async send (replace with real fetch in production)
    console.log('Form data ready to send:', new FormData(form));
    alert('Thank you! Your message was “sent”.');
    form.reset();
  });
</script>
```

* **Why** – Built‑in HTML5 validation (`required`, `type="email"`) gives instant feedback **without** JavaScript. The tiny script only adds a *custom* length check and prevents a page reload.  
* **Common mistake** – Setting `novalidate` **and** forgetting to implement your own validation – the form will submit empty data.  
* **Tip** – Keep `novalidate` only when you fully control validation via JS; otherwise let the browser do its job. Also add `aria-describedby` on inputs that have an error message `<span id="err-name">` for screen‑reader users.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_forms.asp>

---  

## 1️⃣1️⃣ Modals, tooltips & pop‑overs  

### 11.1 Simple modal (w3‑show / w3‑hide)

```html
<!-- Trigger button -->
<button class="w3-button w3-theme" onclick="w3_show('#myModal')">
  Open modal
</button>

<!-- Modal markup -->
<div id="myModal" class="w3-modal">
  <div class="w3-modal-content w3-animate-top w3-card-4">
    <header class="w3-container w3-theme">
      <span onclick="w3_hide('#myModal')"
            class="w3-button w3-display-topright">&times;</span>
      <h2>Modal Header</h2>
    </header>

    <div class="w3-container w3-padding">
      <p>This is a simple modal using only w3.css utilities.</p>
    </div>

    <footer class="w3-container w3-theme">
      <button class="w3-button w3-white w3-right"
              onclick="w3_hide('#myModal')">Close</button>
    </footer>
  </div>
</div>
```

* **Why** – `w3-modal` creates a **full‑screen overlay**; the `w3-animate-top` class gives a smooth slide‑in animation. The helper functions `w3_show()` / `w3_hide()` are tiny wrappers around `style.display`.  
* **Common mistake** – Forgetting `tabindex="-1"` on the modal container, which makes keyboard focus stay behind the overlay.  
* **Tip** – After opening, move focus to the first focusable element inside the modal (`document.querySelector('#myModal button').focus()`).  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_modals.asp>  

### 11.2 Tooltip (pure CSS)

```html
<span class="w3-tooltip w3-teal">Hover me
  <span class="w3-text w3-small w3-animate-opacity w3-white w3-padding w3-round">
    Tooltip text
  </span>
</span>
```

* **Why** – `w3-tooltip` positions the inner `<span>` above the parent element on hover. No JavaScript required.  
* **Common mistake** – Using tooltips for essential information; keyboard‑only users never see them.  
* **Tip** – Pair tooltips with `aria-label` or `aria-describedby` for screen‑reader access.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_tooltip.asp>

---  

## 1️⃣2️⃣ Animations & transitions (w3‑animate)  

| Class | Effect | Example |
|-------|--------|---------|
| `w3-animate-top` | Slides element **down** from the top. | `<div class="w3-animate-top">…</div>` |
| `w3-animate-bottom` | Slides up from bottom. | |
| `w3-animate-opacity` | Fades in/out (changes opacity). | |
| `w3-animate-fading` | Simple fade‑in on load. | |
| `w3-hover-opacity` | Hover reduces opacity to 0.6 (quick visual feedback). | `<img class="w3-hover-opacity">` |

```html
<img src="https://picsum.photos/200/120?image=1010"
     alt="Demo picture"
     class="w3-image w3-hover-opacity w3-animate-fading">
```

* **Why** – w3.css ships with a **tiny** set of keyframe animations that are **hardware‑accelerated**.  
* **Common mistake** – Overusing animations on every element – this hurts performance on low‑end devices.  
* **Tip** – Respect the user’s `prefers-reduced-motion` setting. Add a small media query to **disable** `w3-animate-*` classes when needed:

```css
@media (prefers-reduced-motion: reduce) {
  .w3-animate-top,
  .w3-animate-bottom,
  .w3-animate-fading,
  .w3-animate-opacity { animation: none !important; }
}
```

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_animate.asp>

---  

## 1️⃣3️⃣ Dark‑mode & CSS custom properties  

w3.css themes are built on **CSS custom properties** (`--w3-theme`, `--w3-theme-light`, …). You can switch them at runtime:

```html
<button class="w3-button w3-theme" id="darkToggle">
  🌙 Dark mode
</button>

<script type="module">
  const toggle = document.getElementById('darkToggle');
  toggle.addEventListener('click', () => {
    const root = document.documentElement;
    const dark = root.style.getPropertyValue('--w3-dark-theme');
    if (dark) {
      // already dark – revert to default
      root.style.removeProperty('--w3-theme');
      root.style.removeProperty('--w3-theme-light');
      toggle.textContent = '🌙 Dark mode';
    } else {
      // set dark palette (pick any colours you like)
      root.style.setProperty('--w3-theme', '#212121');
      root.style.setProperty('--w3-theme-light', '#424242');
      toggle.textContent = '☀️ Light mode';
    }
    // remember choice
    localStorage.setItem('theme', root.style.getPropertyValue('--w3-theme') ? 'dark' : 'light');
  });

  // on load – honour saved preference
  if (localStorage.getItem('theme') === 'dark') toggle.click();
</script>
```

* **Why** – By overriding the **CSS variables** you instantly change the entire site’s colour scheme without touching the markup.  
* **Common mistake** – Forgetting to also adjust `--w3-text` and `--w3-hover` variables; contrast can become unreadable.  
* **Tip** – Keep the dark palette **mid‑grey** for background and **light‑grey** for text; contrast ratios stay above 4.5:1.  

📚 **Docs**: <https://www.w3schools.com/w3css/w3css_themes.asp>

---  

## 1️⃣4️⃣ Tiny JavaScript helpers (`w3.show()`, `w3.toggle()`, `w3.filter()`)  

w3.css ships with a **tiny helper library** (`w3.js`) that you can load **or** rewrite with a few lines of vanilla code (the examples above already use the helpers).  

### Loading the helper (optional)

```html
<script src="https://www.w3schools.com/lib/w3.js"></script>
```

| Helper | Description |
|--------|-------------|
| `w3_show(selector)` | Sets `display:block` (or the element’s default) for the matched element(s). |
| `w3_hide(selector)` | Sets `display:none`. |
| `w3_toggle(selector)` | Toggles between `display:none` and `display:block`. |
| `w3_filter(selector, text)` | Shows only those child elements whose text includes `text` (case‑insensitive). |
| `w3_sortHTML(id, selector, sortvalue)` | Simple client‑side table sort (useful for small data sets). |

> **Security note** – The helpers never use `innerHTML`; they only manipulate `style.display`. If you ever need to inject HTML, **sanitize** or use `textContent`.  

📚 **Docs**: <https://www.w3schools.com/w3js/default.asp>

---  

## 1️⃣5️⃣ Real‑world page layout that combines **grid + flex**  

Below is a **complete, ready‑to‑copy** page that uses:

* **named grid areas** for the outer skeleton (`header`, `nav`, `main`, `aside`, `footer`)  
* **Flexbox** inside the `main` area for a responsive **card deck**  
* **w3.css utilities** for colours, spacing, and dark‑mode toggle  

### `index.html`

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>w3.css Grid + Flex Demo</title>

  <!-- w3.css core + theme -->
  <link rel="stylesheet"
        href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet"
        href="https://www.w3schools.com/lib/w3-theme-indigo.css">

  <!-- optional tiny helper lib (for w3.toggle) -->
  <script src="https://www.w3schools.com/lib/w3.js"></script>

  <!-- custom CSS only for layout (no design changes) -->
  <style>
    /* -------------------------------------------------
       GRID LAYOUT – named areas
       ------------------------------------------------- */
    .grid-page {
      display: grid;
      min-height: 100vh;
      grid-template-columns: 1fr minmax(250px, 300px);
      grid-template-rows: auto 1fr auto;
      grid-template-areas:
        "header header"
        "main   aside"
        "footer footer";
      gap: 1rem;
    }
    header   { grid-area: header; }
    nav      { grid-area: nav; }      /* not used in this example */
    main     { grid-area: main; }
    aside    { grid-area: aside; }
    footer   { grid-area: footer; }

    /* -------------------------------------------------
       FLEX CARD DECK (inside <main>)
       ------------------------------------------------- */
    .card-deck {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .card {
      flex: 1 1 calc(33.333% - 1rem);
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .card img { width:100%; height:auto; }
    .card h3   { margin:0.5rem; }
    .card p    { margin:0.5rem; flex-grow:1; }
    .card .cta { margin:0.5rem; align-self:flex-start; }
    @media (max-width: 800px) {
      .card { flex: 1 1 100%; }   /* one‑column on mobile */
    }

    /* -------------------------------------------------
       DARK‑MODE toggle (simple) – custom vars
       ------------------------------------------------- */
    :root { --bg:#fafafa; --txt:#111; }
    [data-theme="dark"] { --bg:#212121; --txt:#eee; }
    body { background:var(--bg); color:var(--txt); }
  </style>
</head>
<body class="w3-light-grey">

  <div class="grid-page w3-container">

    <!-- HEADER -->
    <header class="w3-bar w3-theme w3-padding-16">
      <h1 class="w3-left">w3.css Demo</h1>
      <button id="themeToggle"
              class="w3-button w3-right w3-white w3-round">
        🌙 Dark
      </button>
    </header>

    <!-- MAIN (card deck) -->
    <main class="w3-padding">
      <section class="card-deck">
        <article class="card w3-card-2">
          <img src="https://picsum.photos/400/200?img=10" alt="Demo 1">
          <h3>Card 1</h3>
          <p>Brief description of the first card.</p>
          <a href="#" class="w3-button w3-theme cta">Learn more →</a>
        </article>

        <article class="card w3-card-2">
          <img src="https://picsum.photos/400/200?img=11" alt="Demo 2">
          <h3>Card 2</h3>
          <p>Another short blurb, a little longer than the first.</p>
          <a href="#" class="w3-button w3-theme cta">Learn more →</a>
        </article>

        <article class="card w3-card-2">
          <img src="https://picsum.photos/400/200?img=12" alt="Demo 3">
          <h3>Card 3</h3>
          <p>Third card, same height thanks to flex layout.</p>
          <a href="#" class="w3-button w3-theme cta">Learn more →</a>
        </article>
      </section>
    </main>

    <!-- ASIDE (optional links) -->
    <aside class="w3-padding w3-light-grey">
      <h3>Quick links</h3>
      <ul class="w3-ul">
        <li><a href="#">Documentation</a></li>
        <li><a href="#">Examples</a></li>
        <li><a href="#">GitHub</a></li>
      </ul>
    </aside>

    <!-- FOOTER -->
    <footer class="w3-bar w3-theme-d2 w3-center w3-padding-16">
      © 2025 w3.css demo – <a href="#" class="w3-text-white">Privacy</a>
    </footer>

  </div> <!-- /.grid-page -->

  <script type="module">
    // Dark‑mode toggle (same logic as the snippet in Section 13)
    const toggle = document.getElementById('themeToggle');
    toggle.addEventListener('click', () => {
      const root = document.documentElement;
      const isDark = root.dataset.theme === 'dark';
      root.dataset.theme = isDark ? '' : 'dark';
      toggle.textContent = isDark ? '🌙 Dark' : '☀️ Light';
      localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });
    // Restore saved theme
    if (localStorage.getItem('theme') === 'dark') toggle.click();
  </script>

</body>
</html>
```

**What the page demonstrates**

| Feature | w3.css class | Reason |
|---------|--------------|--------|
| **Outer grid** | custom CSS (`display:grid`) – w3.css does not have a built‑in grid system, but it works nicely with the framework. | Gives a clear, semantic layout. |
| **Card deck** | Flexbox (`display:flex`) + w3‑utility classes (`w3-card-2`, `w3-button w3-theme`). | Automatic equal‑height columns, responsive collapse via a media query. |
| **Responsive hide/show** | Not needed here, but you could add `w3-hide-small` to the aside for mobile. | Shows how grid and flex can coexist. |
| **Dark mode** | Custom CSS variables + tiny JS. | Demonstrates that w3.css works perfectly with native CSS variables. |
| **Accessibility** | `<header>`, `<main>`, `<aside>`, `<footer>` plus proper `alt` text for images. | Semantic markup + ARIA‑friendly components. |
| **Security** | No `innerHTML` is used; all dynamic content (theme toggle) works through attribute changes only. | Avoids XSS. |

📚 **Docs** for the pieces used above:  
* Grid → MDN (standard CSS) – <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout>  
* Flexbox → w3.css flex page – <https://www.w3schools.com/w3css/w3css_flexbox.asp>  
* Dark‑mode → CSS variables – <https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties>

---  

## 1️⃣6️⃣ Security‑first checklist (HTML + CSS + JS)

| Area | Checklist |
|------|-----------|
| **HTML** | • All `<img>` have meaningful `alt`.  <br>• All form `<input>`/`<textarea>` have associated `<label>`.  <br>• Use `<button type="button">` for non‑submit controls. |
| **Links** | • External links → `target="_blank" rel="noopener noreferrer"` to block reverse‑tabnabbing.  <br>• Avoid `javascript:` URLs. |
| **CSS** | • Do **not** embed user‑generated CSS (no `<style>` with unsanitised data).  <br>• Use `box-sizing: border-box;` globally (w3.css already does). |
| **JavaScript** | • All scripts are **modules** (`type="module"`).  <br>• Use `const`/`let`; avoid `var`.  <br>• No `innerHTML` with unchecked data – prefer `textContent` or a sanitiser.  <br>• Wrap any network call (`fetch`) in `try / catch`; check `response.ok`. |
| **CSP (Content Security Policy)** | Example header: <br>`Content‑Security‑Policy: default-src 'self'; style-src 'self' https://www.w3schools.com; script-src 'self' https://www.w3schools.com; img-src https: data:` |
| **Responsive / Accessibility** | • Test with keyboard (`Tab`) – every interactive element must be focusable.  <br>• Add `aria-label`/`aria-describedby` where visual cues (tooltips) are only CSS.  <br>• Respect `prefers-reduced-motion`. |
| **Performance** | • Load w3.css **once** (CDN).  <br>• Keep custom CSS in a separate file (`style.css`) to benefit from caching.  <br>• Use `loading="lazy"` on large images. |

---  

## 1️⃣7️⃣ Quick reference table (most‑used w3.css classes)

| Category | Class | Effect |
|----------|-------|--------|
| **Layout** | `w3-row` | Flex container (horizontal). |
| | `w3-col` | Flex item (auto‑size). |
| | `w3-col m6 l4` | Column spans ½ on medium, ⅓ on large. |
| | `w3-container` | Adds 16 px padding. |
| | `w3-padding-16` | 16 px padding (all sides). |
| **Colors** | `w3-theme` | Uses primary theme colour. |
| | `w3-theme-l5` … `w3-theme-d5` | Lightest‑to‑darkest shades. |
| | `w3-text-grey` | Text colour #757575. |
| **Typography** | `w3-xxlarge` | Font‑size ≈ 36 px. |
| | `w3-serif` | Serif font stack. |
| | `w3-animate-opacity` | Fade‑in animation. |
| **Buttons** | `w3-button` | Base button styling. |
| | `w3-round` | Rounded corners. |
| | `w3-hover-opacity` | Lower opacity on hover. |
| **Cards** | `w3-card-2` … `w3-card-8` | Incremental shadow depth. |
| | `w3-hover-shadow` | Raise shadow on hover. |
| **Modals** | `w3-modal` | Full‑screen overlay. |
| | `w3-animate-top` | Slide‑in from top. |
| **Forms** | `w3-input` | Styled `<input>/<textarea>`. |
| | `w3-select` | Styled `<select>`. |
| | `w3-check` | Styled checkbox/radio. |
| **Navigation** | `w3-bar` | Horizontal nav container. |
| | `w3-bar-item` | Nav item (inline). |
| | `w3-top` / `w3-bottom` | Fixed to viewport edge. |
| **Responsive utilities** | `w3-hide-small`, `w3-hide-medium`, `w3-hide-large` | Hide at breakpoint. |
| | `w3-show-inline-block` | Force `display:inline-block`. |
| **Utility** | `w3-round-large` | Very rounded corners. |
| | `w3-margin` / `w3-margin-top` | Margin spacing. |
| | `w3-display-container` | Relative positioning for overlays. |
| | `w3-display-middle` | Center overlay. |

---  

## 1️⃣8️⃣ Where to find the official documentation  

| Area | URL |
|------|-----|
| **Core w3.css** | <https://www.w3schools.com/w3css/> |
| **Grid (custom CSS)** | <https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout> |
| **Flexbox utilities** | <https://www.w3schools.com/w3css/w3css_flexbox.asp> |
| **Colors & themes** | <https://www.w3schools.com/w3css/w3css_color_themes.asp> |
| **Buttons** | <https://www.w3schools.com/w3css/w3css_buttons.asp> |
| **Cards** | <https://www.w3schools.com/w3css/w3css_cards.asp> |
| **Modals** | <https://www.w3schools.com/w3css/w3css_modals.asp> |
| **Forms** | <https://www.w3schools.com/w3css/w3css_forms.asp> |
| **Responsive helpers** | <https://www.w3schools.com/w3css/w3css_responsive.asp> |
| **Animations** | <https://www.w3schools.com/w3css/w3css_animate.asp> |
| **JavaScript helpers (w3.js)** | <https://www.w3schools.com/w3js/default.asp> |
| **Accessibility checklist** | <https://www.w3.org/WAI/WCAG21/quickref/> |
| **Content‑Security‑Policy guide** | <https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy> |

---  

### 🎉 You’re ready!  

*Start with the skeleton, sprinkle a few `w3-` classes, add a grid or flex container, and you have a fully‑responsive, accessible, and **secure** website without writing any CSS of your own.*  

Feel free to copy the **mini‑apps** (To‑Do list, Dark‑mode toggle, Weather widget, etc.) from the sections above into your own projects, then replace the placeholder content with your real data. All the heavy lifting (layout, colors, basic animation) is handled by **w3.css**, leaving you to focus on the *logic* and *content* of your site. Happy coding! 🚀