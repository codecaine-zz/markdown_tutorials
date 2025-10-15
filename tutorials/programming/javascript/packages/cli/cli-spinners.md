# 🎡 cli‑spinners + Bun (TypeScript edition) – A **step‑by‑step** guide for beginners  

Below you’ll create a tiny TypeScript project that shows terminal spinners, picks a random one, and even lets you browse **all** spinners.  
Every code fragment is ready to copy into a **`.ts`** file and every section links back to the official `cli‑spinners` docs.

---

## 📦  What you’ll need  

| Tool | Why we need it |
|------|----------------|
| **Bun** (JavaScript runtime) | Runs the code *without* a separate build step. |
| **cli‑spinners** | JSON‑like definitions of > 70 ready‑made spinners. |
| **log‑update** | Helper that rewrites the same line in the terminal – perfect for animation. |
| (optional) **chalk** | Adds colour to the text. |

All of these have TypeScript type declarations built‑in, so you get autocomplete and type‑checking for free.

---

## 1️⃣  Install **Bun**  

```bash
# macOS / Linux
curl -fsSL https://bun.sh/install | bash

# Windows (PowerShell)
iwr https://bun.sh/install | iex
```

Bun’s install page: <https://bun.com/docs/installation>【5†L0-L5】

Confirm it works:

```bash
bun --version   # e.g. 1.1.26
```

---

## 2️⃣  Create a new folder & initialise a project  

```bash
mkdir cli-spinners-ts && cd cli-spinners-ts
bun init          # creates a tiny package.json and a starter file
```

`bun init` is documented here: <https://bun.com/docs/cli/init>【5†L7-L10】

> **Tip** – You can keep the default `index.ts` that `bun init` creates; we’ll replace its contents later.

---

## 3️⃣  Add the libraries we’ll use  

```bash
bun add cli-spinners log-update chalk   # chalk is optional, but looks nice
```

*Installation info from the official README* (npm syntax – works the same with Bun)【16†L14-L17】.  

All three packages already ship **`.d.ts`** files, so TypeScript knows their shapes automatically.

---

## 4️⃣  Understanding a spinner object  

Open a terminal and run:

```bash
bun run -e "
import cliSpinners from 'cli-spinners';
console.log(cliSpinners.dots);
"
```

You’ll see something like:

```json
{
  "interval": 80,
  "frames": ["⠋","⠙","⠹","⠸","⠼","⠴","⠦","⠧","⠇","⠏"]
}
```

*Official usage example*【16†L18-L27】.  

*What the two properties mean*

| Property | Description |
|----------|-------------|
| `interval` | How many **milliseconds** to wait before showing the next frame. |
| `frames`   | An array of single‑character strings that make up the animation. |

Because the package ships types, you can hover over `cliSpinners.dots` in an IDE and see the same shape.

---

## 5️⃣  A **single** spinner in TypeScript  

Create **`single.ts`** (replace the file that `bun init` gave you):

```ts
// single.ts
import process from "node:process";
import logUpdate from "log-update";
import cliSpinners from "cli-spinners";

/**
 * Choose a spinner by name.
 * If the user does not provide a name, fall back to "dots".
 */
const spinnerName = process.argv[2] ?? "dots";
const spinner = (cliSpinners as Record<string, {interval: number; frames: string[]}>)[spinnerName];

if (!spinner) {
  console.error(`❌ Unknown spinner "${spinnerName}"`);
  process.exit(1);
}

let frameIdx = 0;

/**
 * Draw one frame, then schedule the next one.
 */
setInterval(() => {
  const {frames, interval} = spinner;
  const glyph = frames[frameIdx++ % frames.length];
  logUpdate(`${glyph} ${spinnerName}…`);
}, spinner.interval);
```

Run it:

```bash
bun run single.ts           # default "dots"
bun run single.ts line      # choose another spinner, e.g. "line"
```

The code mirrors the repository’s `example.js` (the same idea)【14†L4-L10】, but we added a small type‑cast so TypeScript knows the shape.

---

## 6️⃣  **All** spinners – step through them one by one  

Create **`all.ts`**:

```ts
// all.ts
import process from "node:process";
import readline from "node:readline";
import logUpdate from "log-update";
import cliSpinners from "cli-spinners";

const names = Object.keys(cliSpinners) as string[];
let spinnerIdx = 0;
let frameIdx = 0;
let intervalHandle: NodeJS.Timer;
let timeoutHandle: NodeJS.Timeout;

/** Render the current frame of the active spinner */
function draw() {
  const {frames} = cliSpinners[names[spinnerIdx]];
  const glyph = frames[frameIdx++ % frames.length];
  logUpdate(`${glyph} ${names[spinnerIdx]}`);
}

/** Move to the next spinner (or exit when we’re done) */
function nextSpinner() {
  clearInterval(intervalHandle);
  spinnerIdx++;

  if (spinnerIdx >= names.length) {
    console.log("\n✔ All spinners displayed");
    process.exit(0);
  }

  frameIdx = 0;
  const {interval, frames} = cliSpinners[names[spinnerIdx]];
  intervalHandle = setInterval(draw, interval);
  // Wait at least one full animation cycle, but never less than 1 s
  timeoutHandle = setTimeout(
    nextSpinner,
    Math.max(interval * frames.length, 1_000)
  );
}

/* ----------  USER INTERACTION (Enter = skip, Ctrl‑C = quit) ---------- */
readline.emitKeypressEvents(process.stdin);
process.stdin.setRawMode(true);
process.stdin.on("keypress", (_, key) => {
  if (key.ctrl && key.name === "c") process.exit(130);
  if (key.name === "return") {
    clearTimeout(timeoutHandle);
    nextSpinner();
  }
});

/* ----------  START ---------- */
console.log(`${names.length} spinners\n`);
nextSpinner();
```

Run it:

```bash
bun run all.ts
```

Press **Enter** to jump to the next spinner; **Ctrl‑C** to stop.  

The logic is taken from the official `example-all.js` (the same idea)【13†L12-L24】【13†L31-L41】, but written in TypeScript with explicit types.

---

## 7️⃣  Get a **random** spinner (one‑liner)  

Create **`random.ts`**:

```ts
// random.ts
import logUpdate from "log-update";
import {randomSpinner} from "cli-spinners";

const spinner = randomSpinner();          // returns {interval, frames}
let i = 0;

setInterval(() => {
  const {frames} = spinner;
  const glyph = frames[i = ++i % frames.length];
  logUpdate(`${glyph} Working…`);
}, spinner.interval);
```

Run:

```bash
bun run random.ts
```

`randomSpinner()` is described in the API section of the README【16†L38-L44】.

---

## 8️⃣  A **fancy** coloured loading line (optional – uses **chalk**)  

If you installed `chalk` (`bun add chalk`), try this:

```ts
// fancy.ts
import logUpdate from "log-update";
import cliSpinners from "cli-spinners";
import chalk from "chalk";

const spinner = cliSpinners.bouncingBar;   // a nice looking spinner
let i = 0;
const message = chalk.cyan.bold("Fetching data");

setInterval(() => {
  const {frames} = spinner;
  const glyph = frames[i = ++i % frames.length];
  logUpdate(`${glyph} ${message}…`);
}, spinner.interval);
```

Run:

```bash
bun run fancy.ts
```

You’ll see a colourful, smoothly animated loading bar.

---

## 9️⃣  How Bun runs **TypeScript**  

* You **do not** need a separate `tsconfig.json` – Bun automatically compiles `.ts` files on the fly.  
* If you *do* want a custom config (e.g., to change the target ES version), just add a `tsconfig.json` and Bun will respect it.

```json
{
  "compilerOptions": {
    "target": "ES2022",
    "module": "ESNext",
    "strict": true
  }
}
```

Place the file at the project root; then run the same `bun run <file>.ts` command.

---

## 🔎  Quick reference of commands  

| Command | What it does |
|--------|--------------|
| `bun add cli-spinners log-update chalk` | Install the three libraries (with TypeScript types). |
| `bun run single.ts` | Show a single spinner (default “dots”). |
| `bun run single.ts line` | Show a specific spinner (`line`). |
| `bun run all.ts` | Cycle through every spinner, press **Enter** to skip. |
| `bun run random.ts` | Display a random spinner forever. |
| `bun run fancy.ts` | Colourful loading line (needs `chalk`). |

---

## 📚  Official documentation links (for you to explore)  

| Topic | URL |
|-------|-----|
| **Installation** | <https://github.com/sindresorhus/cli-spinners#install>【16†L14-L17】 |
| **Basic usage** (import & object shape) | <https://github.com/sindresorhus/cli-spinners#usage>【16†L18-L27】 |
| **`randomSpinner()`** API | <https://github.com/sindresorhus/cli-spinners#api> (see “randomSpinner”)【16†L38-L44】 |
| **List of spinners (JSON)** | <https://github.com/sindresorhus/cli-spinners/blob/main/spinners.json> |
| **All‑spinner demo** (source) | <https://github.com/sindresorhus/cli-spinners/blob/main/example-all.js>【13†L12-L24】【13†L31-L41】 |
| **Single‑spinner demo** (source) | <https://github.com/sindresorhus/cli-spinners/blob/main/example.js>【14†L4-L10】 |
| **Bun – run a file** | <https://bun.com/docs/cli/run>【5†L11-L13】 |
| **Bun – add a package** | <https://bun.com/docs/cli/add>【9†L0-L13】 |

---

## 🎉  What’s next?  

* **Combine with real work** – e.g., fetch data (`await fetch(...)`) and keep the spinner running until the promise resolves.  
* **Create your own spinner** – just export an object `{interval: number, frames: string[]}` and use it like any built‑in one.  
* **Wrap it in a reusable function** – so you can start/stop spinners from any part of a larger CLI app.

Happy coding! 🎈
