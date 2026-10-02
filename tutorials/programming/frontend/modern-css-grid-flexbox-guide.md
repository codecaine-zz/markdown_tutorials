# Modern CSS Guide: Grid, Flexbox, Container Queries & Modern Layouts

Modern CSS has evolved past fragile floats, clearing hacks, and heavy CSS frameworks. With native **CSS Grid**, **Flexbox**, **Container Queries**, the `:has()` parent selector, and **CSS Custom Properties**, developers can craft fully responsive, dark-mode-ready, fluid layouts using vanilla CSS.

---

## 📚 Table of Contents

1. [Flexbox vs. Grid: When to Use Which](#1-flexbox-vs-grid-when-to-use-which)
2. [Flexbox Deep Dive (1-Dimensional Layouts)](#2-flexbox-deep-dive-1-dimensional-layouts)
3. [CSS Grid Deep Dive (2-Dimensional Systems)](#3-css-grid-deep-dive-2-dimensional-systems)
4. [Container Queries: Component-Driven Responsiveness](#4-container-queries-component-driven-responsiveness)
5. [Fluid Typography & Spacing with `clamp()`](#5-fluid-typography--spacing-with-clamp)
6. [The `:has()` Parent Selector](#6-the-has-parent-selector)
7. [CSS Custom Properties & Native Dark Mode](#7-css-custom-properties--native-dark-mode)
8. [Everyday Production Layout Recipes](#8-everyday-production-layout-recipes)

---

## 1. Flexbox vs. Grid: When to Use Which

| Concept | Flexbox | CSS Grid |
| :--- | :--- | :--- |
| **Dimensionality** | 1-Dimensional (Row OR Column) | 2-Dimensional (Rows AND Columns simultaneously) |
| **Layout Flow** | **Content-First**: items size themselves and push boundaries | **Layout-First**: items fit into a predefined coordinate grid |
| **Best For** | Navbars, button groups, form inputs, toolbars | Full page layouts, card galleries, dashboards |
| **Gap Support** | `gap`, `row-gap`, `column-gap` | `gap`, `row-gap`, `column-gap` |

---

## 2. Flexbox Deep Dive (1-Dimensional Layouts)

### Container Properties

```css
.flex-container {
  display: flex;
  flex-direction: row;          /* row | row-reverse | column | column-reverse */
  flex-wrap: wrap;              /* nowrap | wrap | wrap-reverse */
  justify-content: space-between;/* flex-start | center | flex-end | space-between | space-around | space-evenly */
  align-items: center;          /* stretch | flex-start | center | flex-end | baseline */
  gap: 1.5rem;                  /* Spacing between items without margin hacks */
}
```

### Item Properties (`flex` shorthand)

```css
.flex-item {
  /* flex: <flex-grow> <flex-shrink> <flex-basis> */
  flex: 1 1 250px;
}
```

- **`flex-grow: 1`**: Item will absorb remaining available free space.
- **`flex-shrink: 1`**: Item will shrink proportionally if space is constrained.
- **`flex-basis: 250px`**: Initial size before growing or shrinking begins.

---

## 3. CSS Grid Deep Dive (2-Dimensional Systems)

### The Magic Responsive Card Grid (Zero Media Queries!)

Create an automatically wrapping card grid that dynamically fits any screen size without a single `@media` rule:

```css
.card-grid {
  display: grid;
  /* Automatically fits columns of minimum 280px up to 1 fractional unit */
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}
```

### Visual Layouts with `grid-template-areas`

```css
.dashboard-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  grid-template-rows: auto 1fr auto;
  grid-template-areas:
    "sidebar header"
    "sidebar main"
    "sidebar footer";
  min-height: 100vh;
}

.header  { grid-area: header; }
.sidebar { grid-area: sidebar; }
.main    { grid-area: main; }
.footer  { grid-area: footer; }
```

### Subgrid (Inheriting Parent Tracks)

When a card contains a title, body, and action button, `subgrid` aligns internal elements across neighboring cards seamlessly:

```css
.card {
  display: grid;
  grid-template-rows: subgrid;
  grid-row: span 3;
}
```

---

## 4. Container Queries: Component-Driven Responsiveness

Media queries inspect the entire browser viewport width (`@media (min-width: 768px)`).

**Container Queries** inspect the width of the parent container holding the component, allowing widgets to adapt whether placed in a narrow sidebar or a wide main content area:

```css
/* Step 1: Define the parent as a query container */
.widget-wrapper {
  container-type: inline-size;
  container-name: card-container;
}

/* Step 2: Query the container's width */
@container card-container (max-width: 400px) {
  .user-card {
    flex-direction: column;
    text-align: center;
  }
}

@container card-container (min-width: 401px) {
  .user-card {
    flex-direction: row;
    text-align: left;
  }
}
```

---

## 5. Fluid Typography & Spacing with `clamp()`

Eliminate breakpoint jumps by interpolating smoothly between minimum, preferred, and maximum values:

```css
/* Syntax: clamp(MINIMUM, PREFERRED_VIEWPORT, MAXIMUM) */

/* Typography smoothly scales between 1.25rem (mobile) and 2.5rem (desktop) */
h1 {
  font-size: clamp(1.25rem, 1rem + 2.5vw, 2.5rem);
}

/* Fluid responsive section padding */
section {
  padding-block: clamp(2rem, 5vw, 6rem);
}
```

---

## 6. The `:has()` Parent Selector

The `:has()` pseudo-class styles a parent element based on its children or descendants:

```css
/* Style a card container differently if it contains an image */
.card:has(img) {
  grid-template-columns: 1fr 2fr;
}

/* Highlight a form fieldset when any input inside is focused */
fieldset:has(input:focus) {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

/* Darken page background when a modal dialog is open */
body:has(dialog[open]) {
  overflow: hidden;
}
```

---

## 7. CSS Custom Properties & Native Dark Mode

```css
:root {
  --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  --bg-primary: #ffffff;
  --text-primary: #0f172a;
  --accent: #2563eb;
  --card-bg: #f8fafc;
  --border-color: #e2e8f0;
}

/* Automatic Dark Mode triggered by OS or User Preference */
@media (prefers-color-scheme: dark) {
  :root {
    --bg-primary: #090d16;
    --text-primary: #f8fafc;
    --accent: #3b82f6;
    --card-bg: #131c2e;
    --border-color: #1e293b;
  }
}

/* Optional manual dark-theme override class */
[data-theme="dark"] {
  --bg-primary: #090d16;
  --text-primary: #f8fafc;
  --card-bg: #131c2e;
  --border-color: #1e293b;
}

body {
  background-color: var(--bg-primary);
  color: var(--text-primary);
  font-family: var(--font-sans);
  transition: background-color 0.2s ease, color 0.2s ease;
}
```

---

## 8. Everyday Production Layout Recipes

### Perfect Centering (1 Line of CSS)

```css
.center-box {
  display: grid;
  place-items: center;
  min-height: 100vh;
}
```

### Sticky Footer Layout

```css
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex: 1; /* Pushes footer to bottom even on empty pages */
}
```
