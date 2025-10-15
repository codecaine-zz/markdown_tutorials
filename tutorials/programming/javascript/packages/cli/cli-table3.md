# 📊 cli‑table3 + Bun – A Complete Beginner’s Tutorial  

**Goal** – Learn how to create beautiful, Unicode‑rich tables in the terminal using **cli‑table3** while running the code with the **Bun** JavaScript runtime. Every example is linked to the official `cli-table3` documentation, so you can dive deeper whenever you like.  

---

## 1️⃣  Prerequisites – Install Bun  

```bash
# macOS / Linux
curl -fsSL https://bun.sh/install | bash

# Windows (PowerShell)
iwr https://bun.sh/install | iex
```

Bun docs: <https://bun.com/docs/installation>【5†L0-L5】

After installation run:

```bash
bun --version    # should print a version like 1.1.26
```

---

## 2️⃣  Create a Project  

```bash
mkdir cli-table-demo && cd cli-table-demo
bun init          # creates a minimal package.json and a starter file
```

`bun init` is described in the CLI docs: <https://bun.com/docs/cli/init>【5†L7-L10】

---

## 3️⃣  Install **cli‑table3**  

```bash
bun add cli-table3      # same as npm install cli-table3
```

The official installation section (npm‑style) is shown in the README【15†L109-L113】 – Bun’s `add` command works the same way.

---

## 4️⃣  Importing the library  

`cli-table3` is a CommonJS module, but Bun can import it either with `require` **or** with ESM syntax. Both work:

```js
// ESM (preferred with Bun)
import Table from "cli-table3";

// OR CommonJS
// const Table = require("cli-table3");
```

---

## 5️⃣  Basic Tables  

### 5.1 Horizontal Table  

```js
// file: horizontal.mjs
import Table from "cli-table3";

const table = new Table({
  head: ["TH 1 label", "TH 2 label"],   // column headers
  colWidths: [30, 30]                    // optional fixed widths
});

table.push(
  ["First value", "Second value"],
  ["First value", "Second value"]
);

console.log(table.toString());
```

Run:

```bash
bun run horizontal.mjs
```

**Docs** – Horizontal table example【22†L127-L144】.

---

### 5.2 Vertical Table  

```js
// file: vertical.mjs
import Table from "cli-table3";

const table = new Table();   // no headers → vertical key/value layout

table.push(
  { "Some key": "Some value" },
  { "Another key": "Another value" }
);

console.log(table.toString());
```

Run:

```bash
bun run vertical.mjs
```

**Docs** – Vertical table example【22†L147-L158】.

---

### 5.3 Cross (Key‑Value) Table  

```js
// file: cross.mjs
import Table from "cli-table3";

const table = new Table({
  head: ["", "Top Header 1", "Top Header 2"]   // first cell empty per spec
});

table.push(
  { "Left Header 1": ["Value Row 1 Col 1", "Value Row 1 Col 2"] },
  { "Left Header 2": ["Value Row 2 Col 1", "Value Row 2 Col 2"] }
);

console.log(table.toString());
```

Run:

```bash
bun run cross.mjs
```

**Docs** – Cross table example【22†L161-L179】.

---

## 6️⃣  Customising the Table Appearance  

You can completely control the characters used for borders via the `chars` option.

```js
// file: custom-chars.mjs
import Table from "cli-table3";

const table = new Table({
  chars: {
    top: "═", "top-mid": "╤", "top-left": "╔", "top-right": "╗",
    bottom: "═", "bottom-mid": "╧", "bottom-left": "╚", "bottom-right": "╝",
    left: "║", "left-mid": "╟", mid: "─", "mid-mid": "┼",
    right: "║", "right-mid": "╢", middle: "│"
  }
});

table.push(
  ["foo", "bar", "baz"],
  ["frob", "bar", "quuz"]
);

console.log(table.toString());
```

Run:

```bash
bun run custom-chars.mjs
```

Result looks like a “double‑line” box.  

**Docs** – Custom styles section【22†L182-L201】.

---

## 7️⃣  Advanced Layouts – Column & Row Spanning  

`cli-table3` supports cell spanning (the **advanced‑usage** file). Below are the most common patterns.

### 7.1 Column Span  

```js
// file: colspan.mjs
import Table from "cli-table3";

let table = new Table({ style: { head: [], border: [] } });

table.push(
  [{ colSpan: 2, content: "greetings" }],   // first row: one cell spanning 2 cols
  [{ colSpan: 2, content: "greetings" }],   // second row: same
  ["hello", "howdy"]                        // normal row
);

console.log(table.toString());
```

### 7.2 Row Span  

```js
// file: rowspan.mjs
import Table from "cli-table3";

let table = new Table({ style: { head: [], border: [] } });

table.push(
  [{ rowSpan: 2, content: "greetings" }, { rowSpan: 2, content: "greetings", vAlign: "center" }, "hello"],
  ["howdy"]
);

console.log(table.toString());
```

### 7.3 Mixing rowSpan & colSpan  

```js
// file: complex-span.mjs
import Table from "cli-table3";

let table = new Table({ style: { head: [], border: [] } });

table.push(
  [
    { content: "hello", colSpan: 2 },
    { rowSpan: 2, colSpan: 2, content: "sup" },
    { rowSpan: 3, content: "hi" }
  ],
  [{ content: "howdy", colSpan: 2 }],
  ["o", "k", "", ""]
);

console.log(table.toString());
```

All three snippets are taken from the official **advanced‑usage** markdown【19†L3-L40】【19†L42-L56】【19†L78-L95】.

Run any of them with `bun run <file>.mjs`.

---

## 8️⃣  Styling Headers with Colors (optional)

`cli-table3` can colour headers using any ANSI‑compatible library (e.g., **chalk**). Here’s a quick combo:

```js
// file: colored-header.mjs
import Table from "cli-table3";
import chalk from "chalk";

const table = new Table({
  head: [
    chalk.red.bold("ID"),
    chalk.green.bold("Name"),
    chalk.yellow.bold("Status")
  ]
});

table.push(
  [1, "Alice", chalk.green("✔")],
  [2, "Bob", chalk.red("✖")]
);

console.log(table.toString());
```

Run:

```bash
bun run colored-header.mjs
```

The table shows a colourful header while the rest of the layout stays the same.

---

## 9️⃣  Full Example – All Features in One File  

```js
// file: demo.mjs
import Table from "cli-table3";
import chalk from "chalk";

// ---------- Table definition ----------
const table = new Table({
  head: [
    chalk.cyan.bold("Product"),
    chalk.magenta.bold("Price"),
    chalk.yellow.bold("Qty")
  ],
  colWidths: [20, 10, 6],
  style: {
    border: [],      // keep border default colour
    head: []         // we already coloured the header ourselves
  },
  chars: {          // a tidy single‑line style
    mid: "", leftMid: "", rightMid: "", "mid-mid": ""
  }
});

// ---------- Fill rows ----------
table.push(
  ["Apple", chalk.green("$1.00"), 4],
  ["Banana", chalk.green("$0.50"), 12],
  // cell that spans two columns
  [{ colSpan: 2, content: chalk.blue.bold("Total") }, chalk.green("$8.00")]
);

// ---------- Output ----------
console.log(table.toString());
```

Run:

```bash
bun run demo.mjs
```

You’ll see a compact, coloured table with a spanning “Total” row.

---

## 📦  Quick Recap of Commands  

| Step | Command |
|------|---------|
| Install Bun | `curl -fsSL https://bun.sh/install \| bash` |
| Init project | `bun init` |
| Add cli‑table3 (and optional chalk) | `bun add cli-table3 chalk` |
| Run a script | `bun run <file>.mjs` |

---

## 📚  Official Documentation Links  

| Topic | Official Docs |
|-------|----------------|
| Installation (npm) | <https://github.com/cli-table/cli-table3#installation>【15†L109-L113】 |
| Horizontal tables | <https://github.com/cli-table/cli-table3#horizontal-tables>【22†L127-L144】 |
| Vertical tables | <https://github.com/cli-table/cli-table3#vertical-tables>【22†L147-L158】 |
| Cross tables | <https://github.com/cli-table/cli-table3#cross-tables>【22†L161-L179】 |
| Custom characters / styles | <https://github.com/cli-table/cli-table3#custom-styles>【22†L182-L201】 |
| Advanced usage (colSpan/rowSpan) | <https://github.com/cli-table/cli-table3/blob/master/advanced-usage.md> (see lines 3‑56, 78‑95)【19†L3-L40】【19†L42-L56】【19†L78-L95】 |
| Bun installation & CLI | <https://bun.com/docs/installation>【5†L0-L5】 |
| Bun run command | <https://bun.com/docs/cli/run>【5†L11-L13】 |
| Bun add (package manager) | <https://bun.com/docs/cli/add>【9†L0-L13】 |

---

## 🎉  What’s Next?  

* **Combine with other CLI tools** – e.g., `commander` for argument parsing.  
* **Export tables to markdown/HTML** – `cli-table3` can be stringified and written to a file.  
* **Dynamic data** – fetch data from an API (using Bun’s built‑in fetch) and feed it into the table.  

Enjoy building tidy terminal UIs with `cli-table3` and the blazing fast Bun runtime! 🚀  