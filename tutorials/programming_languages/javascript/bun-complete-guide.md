# Complete Bun JavaScript Runtime Tutorial (latest v1.x) – **Extra Examples for Beginners**  

> **TL;DR:** Install Bun → `bun init` a project → write modern JavaScript/TypeScript → run with `bun run` → use Bun’s built‑in APIs (`readFile`, `writeFile`, `$`, `Bun.serve`, `bun test`, …). All code samples are ready‑to‑copy‑paste.

---  

## Table of Contents
1. [Introduction to Bun](#1-introduction-to-bun)  
2. [Installing Bun](#2-installing-bun)  
3. [Running Your First Script](#3-running-your-first-script)  
4. [Project Layout & `bun init`](#4-project-layout--bun-init)  
5. [Basic JavaScript Syntax & Variables – More Examples](#5-basic-javascript-syntax--variables---more-examples)  
6. [Data Types & TypeScript (optional) – More Examples](#6-data-types--typescript-optional---more-examples)  
7. [Operators & Expressions – More Examples](#7-operators--expressions---more-examples)  
8. [Control Flow – More Examples](#8-control-flow---more-examples)  
9. [Functions (sync / async) – More Examples](#9-functions-sync‑async---more-examples)  
10. [Data Structures – More Examples](#10-data-structures---more-examples)  
11. [Object‑Oriented Programming – More Examples](#11-oop-classes---more-examples)  
12. [Modules & Packages – More Examples](#12-modules--packages---more-examples)  
13. [File I/O with the Bun API – More Examples](#13-file-io---more-examples)  
14. [Error Handling & Custom Errors – More Examples](#14-error-handling---more-examples)  
15. [Advanced Features (Workers, HTTP, Bundling, Testing) – More Examples](#15-advanced-features---more-examples)  
16. [JSON Handling – More Examples](#16-json-handling---more-examples)  
17. [Best Practices & Gotchas – Extra Tips](#17-best-practices--gotchas---extra-tips)  
18. [Reference Cheat Sheet](#18-reference-cheat-sheet)  

---

## 1. Introduction to Bun  

Bun is a **single binary** that bundles a JavaScript/TypeScript runtime, a package manager, a bundler, and a test runner. It aims to be a drop‑in replacement for Node.js while being dramatically faster thanks to its Rust implementation and V8 engine 【3†L0-L5】.

---

## 2. Installing Bun  

```bash
# macOS / Linux
curl -fsSL https://bun.sh/install | bash

# Windows (PowerShell)
iwr https://bun.sh/install -useb | iex
```

The installer adds `~/.bun/bin` (or `%USERPROFILE%\.bun\bin`) to your `PATH`. Verify the installation:

```bash
bun --version   # e.g. 1.1.27
```

> **Upgrade later** with `bun upgrade`. 【4†L6-L13】

---

## 3. Running Your First Script  

```js
// hello.js
console.log("👋 Hello, Bun!");
```

```bash
bun run hello.js   # 👉 executes the file directly
```

Output:

```
👋 Hello, Bun!
```

`bun run` works for **`.js`**, **`.ts`**, **`.mjs`** (and even `.tsx`) files without a separate Node.js step 【3†L7-L9】.

### One‑liner with the built‑in shell helper `$`

```bash
bun run -e 'console.log("Now:", $`date`)'   # prints the current OS date
```

`$` executes a shell command and returns its stdout as a string 【6†L71-L73】.

---

## 4. Project Layout & `bun init`

```bash
mkdir my-app && cd my-app
bun init            # choose “plain”, “React”, “Vue”, etc.
```

Typical scaffold (plain JavaScript):

```
my-app/
├─ src/
│  └─ index.js
├─ bunfig.toml      # Bun‑specific config (build, test, env…)
├─ package.json     # generated for npm compatibility
└─ node_modules/
```

`bunfig.toml` holds all Bun‑specific options (build target, minify, watch mode, …). Keep it under version control.

---

## 5. Basic JavaScript Syntax & Variables — **More Examples**

| Concept | Example | Explanation |
|---------|---------|-------------|
| **Constants vs. mutable** | `const PI = 3.14159; let radius = 5; radius += 2;` | `const` cannot be reassigned; `let` can. |
| **Template literals** | `` const name = "Alice"; console.log(`Hi, ${name}!`); `` | Easy string interpolation and multi‑line strings. |
| **Arithmetic & rounding** | `let avg = (10+20+30)/3; avg = Math.round(avg*100)/100;` | Demonstrates basic math and rounding. |
| **`console.table`** | `console.table([{id:1, name:"Bob"}, {id:2, name:"Eve"}]);` | Quick visual debugging of arrays/objects. |
| **Strict equality** | `5 === "5"` → `false` (always use `===`). |
| **NaN check** | `Number.isNaN(value)` – never `NaN === NaN`. |

**Gotchas**  
* `==` coerces types – avoid it.  
* `NaN` is never equal to itself; use `Number.isNaN`.

---

## 6. Data Types & TypeScript (optional) — **More Examples**

### 6.1 Primitive vs. reference

```js
// Primitive copy
let a = 10;
let b = a;   // b = 10 (independent)
a = 20;      // b stays 10

// Reference copy
let obj1 = {x:1};
let obj2 = obj1;   // same object
obj1.x = 5;
console.log(obj2.x); // 5
```

### 6.2 TypeScript interfaces & enums (saved as `person.ts`)

```ts
export interface Person {
  name: string;
  age: number;
  role?: "admin" | "user";
}
export enum Color {
  Red = "red",
  Green = "green",
  Blue = "blue",
}
```

```ts
// main.ts
import { Person, Color } from "./person";

const alice: Person = { name: "Alice", age: 30 };
console.log(alice.name, Color.Green);
```

Run directly (no extra compile step): `bun run main.ts`.

### 6.3 `bigint` for huge integers

```js
const huge = 123456789012345678901234567890n; // note the trailing `n`
console.log(huge * 2n);
```

**Gotchas**  
* `null` → `typeof "object"` – test with `value === null`.  
* Mixing `bigint` and `number` throws a `TypeError`; convert explicitly.

---

## 7. Operators & Expressions — **More Examples**

| Operator | Example | Typical Use |
|----------|---------|-------------|
| `??` (nullish coalescing) | `const name = input ?? "guest";` | Fallback only when `null`/`undefined`. |
| `?.` (optional chaining) | `user?.profile?.email` | Safe navigation through nested objects. |
| Bitwise `&`, `|`, `<<` | `const READ = 1 << 0; const WRITE = 1 << 1; let perm = READ | WRITE;` | Permission flags. |
| `+` with numbers | `5 + 3` → `8` | Arithmetic. |
| `+` with strings | `5 + "3"` → `"53"` | String concatenation (be careful!). |

**Gotchas**  
* `+` with a string always concatenates – avoid accidental type coercion.  
* `||` returns the first *truthy* value; for “use default only when undefined” prefer `??`.

---

## 8. Control Flow — **More Examples**

```js
// for…of (preferred for arrays)
const fruits = ["apple", "banana", "cherry"];
for (const f of fruits) console.log(`- ${f}`);

// break / continue
for (let i = 0; i < 10; i++) {
  if (i % 2) continue;          // skip odds
  if (i > 6) break;             // stop at 8
  console.log(i);               // 0,2,4,6
}

// while with user input (Node REPL or browser prompt)
let line;
while ((line = prompt("Enter text (blank to stop):")) !== "") {
  console.log(`You typed: ${line}`);
}
```

**Gotchas**  
* `for…in` iterates over **property names** (including inherited ones); use `for…of` for values.  
* Changing an array while looping (`push`, `splice`) can skip elements – iterate over a copy (`[...arr]`) if you need to mutate.

---

## 9. Functions (sync / async) — **More Examples**

### 9.1 Default parameters & destructuring

```js
function greet({name = "Guest", age = 0} = {}) {
  console.log(`${name} is ${age} years old`);
}
greet({name:"Bob"});   // Bob is 0 years old
greet();               // Guest is 0 years old
```

### 9.2 Higher‑order function

```js
function makeAdder(x) {
  return y => x + y;
}
const add5 = makeAdder(5);
console.log(add5(3)); // 8
```

### 9.3 Async function with retry logic

```js
async function fetchWithRetry(url, attempts = 3) {
  for (let i = 0; i < attempts; i++) {
    try {
      const res = await fetch(url);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return await res.json();
    } catch (e) {
      if (i === attempts - 1) throw e;   // final failure
      console.warn(`Attempt ${i+1} failed – retrying…`);
    }
  }
}
```

### 9.4 Node‑style callback (Bun still supports it)

```js
import { readFile } from "bun";

readFile("package.json", (err, data) => {
  if (err) return console.error(err);
  console.log(JSON.parse(data));
});
```

**Gotchas**  
* Forgetting `await` on a promise leaves a **pending Promise** – always `await` or `.then`.  
* Arrow functions capture the surrounding `this`; regular functions get their own `this`.

---

## 10. Data Structures — **More Examples**

| Structure | Quick Example | When to Use |
|-----------|---------------|--------------|
| **Array** | `const nums = [1,2,3]; nums.push(4);` | Ordered, indexable collections. |
| **Object** | `const user = {name:"Bob", age:30};` | Simple key/value maps (string/symbol keys). |
| **Map** | `const cache = new Map(); cache.set(obj, "data");` | Non‑string keys, guaranteed order. |
| **Set** | `const uniq = new Set([1,2,2,3]);` | Store unique values, fast `has`. |
| **WeakMap** | `const memo = new WeakMap(); memo.set(obj, result);` | Cache without preventing GC. |

### Set Operations (intersection / union)

```js
const a = new Set([1,2,3]);
const b = new Set([3,4,5]);

const intersect = new Set([...a].filter(x => b.has(x))); // {3}
const union = new Set([...a, ...b]);                     // {1,2,3,4,5}
```

### Shallow vs. deep copy

```js
const shallow = {inner:{x:1}};
const copy = {...shallow};          // shallow – inner still shared
copy.inner.x = 99;
console.log(shallow.inner.x); // 99 (still linked)

const deep = structuredClone(shallow); // deep copy (Node 22+/Bun)
deep.inner.x = 5;
console.log(shallow.inner.x); // 99 (unchanged)
```

**Gotchas**  
* `Object.assign` / spread (`{...obj}`) only copy the first level.  
* `for…in` enumerates prototype properties – prefer `Object.entries`/`Object.keys`.

---

## 11. Object‑Oriented Programming — **More Examples**

```js
class Counter {
  #value = 0;                 // private field (stage‑3)
  increment() { this.#value++; }
  get value() { return this.#value; }
  static description = "Simple integer counter"; // static property
}

const c = new Counter();
c.increment();
console.log(c.value);               // 1
console.log(Counter.description);    // Simple integer counter
```

### Inheritance & `super`

```js
class Animal {
  constructor(name) { this.name = name; }
  speak() { console.log(`${this.name} makes a noise.`); }
}
class Dog extends Animal {
  speak() { super.speak(); console.log(`${this.name} barks!`); }
}
new Dog("Rex").speak();
// Rex makes a noise.
// Rex barks!
```

### Mixins (re‑use behavior)

```js
const Logger = Base => class extends Base {
  log(msg) { console.log(`[${this.constructor.name}] ${msg}`); }
};

class Service {}
class LoggedService extends Logger(Service) {}

const s = new LoggedService();
s.log("started");   // [LoggedService] started
```

**Gotchas**  
* Private fields (`#`) are **not** inherited – each class defines its own.  
* Forgetting `super()` in a subclass constructor throws a `ReferenceError`.

---

## 12. Modules & Packages — **More Examples**

### 12.1 ES Module syntax (preferred)

```js
// utils.mjs
export function add(a, b) { return a + b; }
export const PI = 3.14159;

// main.mjs
import { add, PI } from "./utils.mjs";
console.log(add(2,3), PI);
```

### 12.2 Re‑exporting a whole module

```js
// index.mjs
export * from "./utils.mjs";
export { default as Config } from "./config.mjs";
```

### 12.3 Dynamic `import()` (code‑splitting)

```js
async function loadFeature(flag) {
  if (flag) {
    const { heavy } = await import("./heavy.mjs");
    heavy();
  } else {
    console.log("Feature disabled");
  }
}
```

### 12.4 Installing a third‑party package

```bash
bun add zod          # adds to dependencies and updates bun.lockb
```

```js
import { z } from "zod";

const User = z.object({
  name: z.string(),
  age:  z.number().int().positive(),
});

User.parse({name:"Bob", age:25}); // succeeds
```

**Gotchas**  
* Mixing `require` and `import` can lead to default‑export confusion; stick to one system.  
* When importing a CommonJS-only package, use `import pkg from "cjs-pkg"` (default export) 【9†L24-L30】.

---

## 13. File I/O with the Bun API — **More Examples**

### 13.1 Read a file as a string

```js
import { readFile } from "bun";

const data = await readFile("package.json", "utf8");
console.log(JSON.parse(data));
```

### 13.2 Write JSON atomically

```js
import { writeFile } from "bun";

const config = { port: 3000, env: "dev" };
await writeFile("config.json", JSON.stringify(config, null, 2));
```

### 13.3 Stream a massive log file line‑by‑line

```js
import { file } from "bun";

let lines = 0;
for await (const line of file("big.log")) {
  // each `line` is a Uint8Array; convert to string
  const txt = line.toString().trim();
  // …process txt…
  lines++;
}
console.log(`Processed ${lines} lines`);
```

> `file()` returns a **`BunFile`**, an async iterator, which is much more memory‑efficient for large files 【6†L71-L73】.

### 13.4 Delete a file safely

```js
import { rm } from "bun";

try {
  await rm("old.tmp");
  console.log("Removed");
} catch (e) {
  if (e.code === "ENOENT") console.warn("File already missing");
  else throw e;
}
```

**Gotchas**  
* Omit the encoding argument and you get a `Uint8Array` instead of a string.  
* `BunFile` is **not** a plain string – you must `await` or iterate over it.

---

## 14. Error Handling & Custom Errors — **More Examples**

### 14.1 Granular `try…catch`

```js
async function loadConfig() {
  try {
    const txt = await readFile("config.json", "utf8");
    return JSON.parse(txt);
  } catch (err) {
    if (err instanceof SyntaxError) {
      console.error("Invalid JSON!");
    } else if (err.code === "ENOENT") {
      console.error("Config file missing");
    } else {
      console.error("Unexpected error:", err);
    }
    throw err;        // re‑throw if we cannot recover
  }
}
```

### 14.2 Custom error hierarchy

```js
class AppError extends Error {
  constructor(message, public status = 500) {
    super(message);
    this.name = "AppError";
  }
}
class NotFoundError extends AppError {
  constructor(resource) {
    super(`${resource} not found`, 404);
    this.name = "NotFoundError";
  }
}
function getUser(id) {
  if (id !== 1) throw new NotFoundError(`User ${id}`);
  return { id, name: "Alice" };
}
```

### 14.3 `finally` for cleanup (e.g., file handles)

```js
let fh;
try {
  fh = await Bun.file("temp.txt").open("w");
  await fh.write("temporary data");
} finally {
  if (fh) await fh.close();   // always runs, even on error
}
```

**Gotchas**  
* A rejected Promise inside `try` that isn’t `await`ed will bypass `catch`.  
* Returning from `finally` overrides any earlier `return` — avoid that pattern.

---

## 15. Advanced Features — **More Examples**

### 15.1 Workers (CPU‑heavy background tasks)

```js
// heavy.mjs
export function prime(limit) {
  const primes = [];
  for (let i = 2; i <= limit; i++) {
    if (primes.every(p => i % p !== 0)) primes.push(i);
  }
  return primes;
}
```

```js
// main.mjs
import { Worker } from "worker_threads";

const w = new Worker(new URL("./heavy.mjs", import.meta.url));
w.postMessage(1_000_000);
w.on("message", result => console.log(`Found ${result.length} primes`));
w.on("error", err => console.error(err));
```

Workers run on separate OS threads, preventing heavy calculations from blocking the event loop.

### 15.2 Minimal HTTP server with `Bun.serve`

```js
import { serve } from "bun";

serve({
  port: 3000,
  fetch(req) {
    const url = new URL(req.url);
    if (url.pathname === "/time") {
      return new Response(JSON.stringify({ now: Date.now() }), {
        headers: { "Content-Type": "application/json" },
      });
    }
    return new Response("Not found", { status: 404 });
  },
});
```

*`Bun.serve` is a high‑performance server backed by uWebSockets* 【8†L12-L18】【8†L63-L66】.

### 15.3 Bundling a CLI tool

```bash
# src/cli.ts
import { readFile, writeFile } from "bun";

if (process.argv.length < 3) {
  console.error("Usage: bun run cli.ts <file>");
  process.exit(1);
}
const path = process.argv[2];
const data = await readFile(path, "utf8");
await writeFile(`${path}.bak`, data);
console.log(`Backed up ${path}`);
```

```bash
bun build src/cli.ts --outdir dist --minify
```

The resulting `dist/cli.js` is a single, self‑contained executable that can be run with `bun run dist/cli.js`.

### 15.4 Testing with `bun:test`

```ts
// sum.test.ts
import { describe, it, expect, test } from "bun:test";
import { sum } from "./sum";

describe("sum()", () => {
  it("adds numbers", () => expect(sum(1,2,3)).toBe(6));
  test("empty input → 0", () => expect(sum()).toBe(0));
});
```

Run:

```bash
bun test                # discovers *.test.* automatically
bun test --watch        # re‑runs on file changes
bun test --coverage     # produces a coverage report
```

The test runner is **Jest‑compatible** and runs inside the Bun runtime 【10†L6-L13】【10†L18-L22】.

---

## 16. JSON Handling — **More Examples**

### 16.1 Streaming NDJSON (line‑delimited JSON)

```js
import { file } from "bun";

let processed = 0;
for await (const line of file("events.ndjson")) {
  const event = JSON.parse(line);
  // …process event…
  processed++;
}
console.log(`Handled ${processed} events`);
```

### 16.2 Deep‑clone via JSON (when the data is JSON‑compatible)

```js
function deepClone(obj) {
  return JSON.parse(JSON.stringify(obj));
}
const original = { a: 1, b: { c: 2 } };
const copy = deepClone(original);
copy.b.c = 99;
console.log(original.b.c); // 2 – unchanged
```

### 16.3 Write pretty‑printed JSON with Unicode preserved

```js
const payload = { msg: "Hello, 世界!" };
await writeFile("out.json", JSON.stringify(payload, null, 2), "utf8");
```

**Gotchas**  
* Circular references cannot be stringified – use a custom replacer or a library like `flatted`.  
* Large numeric literals lose precision (`0.1 + 0.2 !== 0.3`); for financial data use `BigInt`/`Decimal`.

---

## 17. Best Practices & Gotchas – **Extra Tips**

| Topic | ✅ Recommended | ❌ Common Pitfall |
|-------|---------------|-------------------|
| **Project scaffolding** | `bun init` → keep `bunfig.toml` versioned. | Manually editing `package.json` without updating Bun config. |
| **Dependency management** | `bun add <pkg>` (fast, lockfile `bun.lockb`). | Mixing `npm install` and `bun add` → duplicate lockfiles. |
| **Async code** | `await` every promise *or* handle `.catch`. | Forgetting `await` → silent Promise. |
| **Modules** | Use **ESM** (`import`/`export`). | Mixing `require` and `import` without interop. |
| **File I/O** | `await readFile/writeFile` (non‑blocking). | Using synchronous `fs.readFileSync` – blocks the event loop. |
| **Error handling** | Catch specific error types (`err.code`). | Empty `catch {}` blocks that swallow bugs. |
| **Performance** | Prefer Bun’s built‑in APIs (`fetch`, `$`, `Bun.file`). | Adding heavy third‑party polyfills for features already present. |
| **Testing** | `bun test` + `describe/it/expect`. | Using external test runners unnecessarily. |
| **Environment variables** | Access via `process.env` or `Bun.env`. | Hard‑coding secrets. |
| **Version stability** | Pin Bun version in CI (`bun install -g bun@1.1.27`). | Assuming the “latest” will never break. |

### Frequently‑Encountered Gotchas

| Gotcha | Why it happens | Fix |
|--------|----------------|-----|
| `__dirname` / `__filename` missing in ESM | ESM uses URL metadata. | `new URL('.', import.meta.url).pathname`. |
| Circular imports returning `undefined` | Modules are evaluated in order; early import sees incomplete exports. | Refactor shared code into a third module or use dynamic `import()`. |
| Private fields (`#`) not inherited | They are truly private per class. | Expose via getters/setters or avoid `#` for inherited state. |
| `bun build` vs. `npm run build` | `bun build` reads `bunfig.toml`; npm scripts ignore it. | Keep scripts consistent: `"build": "bun build src/index.ts"` in `package.json`. |
| Returning from `finally` overrides earlier returns | `finally` always runs last. | Do not `return` inside `finally`; let the original return win. |

---

## 18. Reference Cheat Sheet

| Command / API | Quick description | Docs |
|---------------|-------------------|------|
| `bun --version` | Show installed Bun version | — |
| `bun run <file>` | Execute a JS/TS file directly | 【3†L7-L9】 |
| `bun init` | Scaffold a new project (templates) | 【5†L9-L15】 |
| `$` (global) | Run shell command, get stdout | 【6†L71-L73】 |
| `readFile / writeFile` | Async file I/O helpers | 【6†L71-L73】 |
| `Bun.file(path)` | Returns a streaming `BunFile` | 【6†L71-L73】 |
| `serve({port, fetch})` | Start a fast HTTP server | 【8†L12-L18】 |
| `Worker` (from `worker_threads`) | Create background OS thread | — |
| `bun add / bun install` | Install dependencies, updates `bun.lockb` | — |
| `bun test` | Built‑in Jest‑compatible test runner | 【10†L6-L13】 |
| `bun build <entry>` | Bundle for production (minify, target) | — |
| `bun upgrade` | Upgrade Bun to latest version | — |
| `process.env` / `Bun.env` | Access environment variables | — |

---

## Bibliography  

| # | URL | What it documents |
|---|-----|-------------------|
| 1 | <https://bun.com/docs> | General overview of Bun (runtime, package manager, bundler). |
| 2 | <https://bun.com/docs/quickstart> | Quickstart, `bun run`, script execution. |
| 3 | <https://bun.com/docs/installation> | Installation instructions, upgrade command. |
| 4 | <https://bun.com/docs/cli/init> | `bun init` templating command. |
| 5 | <https://bun.com/reference/globals> | Global `$` shell helper and other globals. |
| 6 | <https://bun.com/reference/bun/serve> | API for `Bun.serve` (HTTP server). |
| 7 | <https://bun.com/reference/bun/test> | `bun:test` module – test runner API. |
| 8 | <https://bun.com/docs/cli/run> | `bun run` CLI reference. |
| 9 | <https://bun.com/docs/cli/add> | `bun add` / `bun install` package manager. |
|10| <https://bun.com/docs/bunfile> (implicit in globals) | `Bun.file` streaming API. |

*(All links point to the official Bun documentation at `bun.com`.)*  

---  

**You now have a beginner‑friendly, example‑rich guide to start building with Bun.** Copy the snippets, experiment, and let Bun’s speed make your development loop tighter than ever. Happy coding! 🚀  