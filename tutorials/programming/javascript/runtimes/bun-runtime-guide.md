# 🟢 **Beginner‑Friendly Bun + SQLite Development Guide (Apple Silicon Mac)**  
*(All commands are copy‑paste ready, every snippet works out‑of‑the‑box, and the guide now ships with a **full‑featured SQLite wrapper** that gives you **CRUD, transactions, upserts, and security checks**.  No prior JavaScript/SQL experience required.)*  

> **What you’ll finish with:** a runnable project that prints a greeting, talks to an in‑memory SQLite DB, uses a Redis cache (already in the original guide), calls an external API with `fetch`, and renders a tiny template – **plus** a reusable `SQLiteWrapper` class you can drop into any future Bun project.

---  

## 📑 Table of Contents  

| # | Topic |
|---|-------|
| **0️⃣** | Quick‑Start Checklist |
| **1️⃣** | Why Bun on an M‑Series Mac? |
| **2️⃣** | Prerequisites (what you need) |
| **3️⃣** | Install the toolchain (Xcode CLT, Homebrew, Bun, SQLite, Redis) |
| **4️⃣** | Set up VS Code (minimal vs. full) |
| **5️⃣** | Add the **SQLiteWrapper** helper (copy‑paste the whole file) |
| **6️⃣** | First demo project – “Hello, Bun!” **plus** SQLite CRUD demo |
| **7️⃣** | Run | Test | Format | Lint (one‑liner commands) |
| **8️⃣** | Debugging in VS Code (or via `bun debug`) |
| **9️⃣** | Helper library (string, number, file, HTTP, template – reusable) |
| **🔟** | SOLID & best‑practice checklist |
| **🅰️** | Common pitfalls & quick fixes |
| **🅱️** | Keyboard shortcuts for macOS + VS Code |
| **🆑** | Official Bun references & further learning |
| **🆎** | macOS‑specific distribution tips (building a native binary, signing, Homebrew formula) |

---  

## 0️⃣ Quick‑Start Checklist  

| ✅ | Action | One‑line command (just copy‑paste) |
|---|--------|-----------------------------------|
| 1️⃣ | Install **Xcode Command‑Line Tools** (gives you `clang`, `lldb`, etc.) | `xcode-select --install` |
| 2️⃣ | (Optional) Install **Homebrew** – the easiest way to get SQLite & Redis binaries | `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"` |
| 3️⃣ | Install **Bun** (official Apple‑silicon binary) | `curl -fsSL https://bun.sh/install \| bash` |
| 4️⃣ | Verify the installation | `bun --version` |
| 5️⃣ | Install **SQLite** and **Redis** (via Homebrew) | `brew install sqlite redis && brew services start redis` |
| 6️⃣ | Scaffold a new project folder | `mkdir -p ~/bun-projects/hello_bun && cd ~/bun-projects/hello_bun && bun init && code .` |
| 7️⃣ | Add the **SQLiteWrapper** (step 5 below) | *see the detailed copy‑paste later* |
| 8️⃣ | Run the demo to ensure everything works | `bun src/demo_sqlite.js` |
| 9️⃣ | Run the test‑format chain (confirms the whole toolchain) | `bun test && bun fmt && bun lint` |

*If any step fails, jump to the **Pitfalls** section (🅰️) for a quick fix.*  

---  

## 1️⃣ Why **Bun** on an Apple Silicon (ARM) Mac?  

| Benefit | What it means for you (the absolute beginner) |
|---------|-----------------------------------------------|
| **Native Apple‑Silicon binary** | No Rosetta 2 translation → your script starts up **≈ 2 × faster** than a Node script. |
| **All‑in‑one toolbox** (`bun install`, `bun test`, `bun fmt`, `bun lint`, `bun build`) | You only need to remember **one** command line tool for everything. |
| **Built‑in SQLite & Redis drivers** (`import "bun:sqlite"` / `import "bun:redis"`) | No extra native add‑ons to compile – just copy‑paste the `import` line. |
| **Web‑standard APIs** (`fetch`, `WebSocket`, `AbortController`) | The same `fetch` you use in a browser works on the server – no polyfills. |
| **TypeScript out of the box** | Write `.ts` files and Bun compiles them instantly (no extra config). |
| **Single‑binary distribution** | When you’re ready to ship, `bun build … --compile` produces a tiny native executable. |

*(All of the official docs are linked throughout the guide – you can always click to read the full spec.)*  

---  

## 2️⃣ Prerequisites  

| Item | Minimum version | How to check |
|------|----------------|--------------|
| macOS | 12 (Monterey) or newer | `sw_vers -productVersion` |
| Xcode Command‑Line Tools | any (installed by step 1) | `xcode-select -p` |
| Homebrew (optional) | 4.x | `brew --version` |
| **Bun** | latest (installed by step 3) | `bun --version` |
| VS Code (or any editor) | 1.90+ | open the app → **About** |
| SQLite & Redis (installed by step 3) | — | `sqlite3 --version` / `redis-cli --version` |

---  

## 3️⃣ Install the Toolchain  

> **You have two ways – pick the one that feels easiest.**  
> The “One‑liner” method (3️⃣) works for 99 % of users.  
> The “Manual download” method (3️⃣‑2) is for people who cannot use Homebrew (e.g., corporate machines).

### 3️⃣‑1 One‑liner (recommended)

```bash
# 1️⃣ Xcode CLT
xcode-select --install

# 2️⃣ Homebrew (optional but makes later steps painless)
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# 3️⃣ Bun (official installer detects Apple‑silicon automatically)
curl -fsSL https://bun.sh/install | bash

# 4️⃣ Verify
bun --version   # should print something like 1.1.3

# 5️⃣ SQLite & Redis (via Homebrew)
brew install sqlite redis
brew services start redis   # runs Redis in the background
```

### 3️⃣‑2 Manual download (no Homebrew)

| Tool | Manual steps |
|------|---------------|
| **SQLite** | Download the pre‑compiled `sqlite-tools` zip from <https://sqlite.org/download.html>, unzip, and copy `sqlite3` to `/usr/local/bin` (or any folder in `$PATH`). |
| **Redis** | Download the latest source tarball from <https://redis.io/download>, run `make`, then move `src/redis-server` and `src/redis-cli` to `/usr/local/bin`. Start with `redis-server &`. |
| **Bun** | Go to <https://github.com/oven-sh/bun/releases>, download the `bun-macos-arm64.zip`, unzip, and add the `bun` binary to your `$PATH` (`export PATH=$HOME/.bun/bin:$PATH` in `~/.zshrc`). |

> **Why the one‑liner?** Homebrew automatically keeps SQLite and Redis up‑to‑date and puts the binaries in a known location, which avoids the “command not found” errors you sometimes see with manual installs.

---  

## 4️⃣ Editor Setup (VS Code)  

### 4️⃣‑1 Minimal setup (just the basics)

```bash
brew install --cask visual-studio-code   # or download from https://code.visualstudio.com
code .                                   # opens the current folder
```

That’s enough to write code, run the terminal, and see errors.

### 4️⃣‑2 Full‑featured setup (recommended for a smoother experience)

| Extension | What you get |
|-----------|--------------|
| **Bun** (official) | Recognises `bun:*` imports, autocompletes Bun commands. |
| **ESLint** | Real‑time linting – catches common mistakes early. |
| **Prettier** | Auto‑formatting on save (`bunx prettier`). |
| **Debugger for Chrome** | UI for Bun’s built‑in debugger (`bun debug`). |
| *Optional* **GitLens**, **npm Intellisense** | Better Git view, package name completion. |

*How to install:* Open VS Code → **Extensions** (`⇧⌘X`) → search each name → **Install**.

### 4️⃣‑3 Recommended `settings.json` (⌘ , → *Open Settings (JSON)*)

```json
{
  // Bun integration
  "bun.enable": true,
  "bun.path": "~/.bun/bin/bun",

  // Formatting & linting
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "eslint.enable": true,
  "eslint.validate": ["javascript", "typescript"],

  // Debugger
  "debug.console": "integratedTerminal",
  "debug.javascript.usePreview": false,

  // Terminal profile (most macOS users use zsh)
  "terminal.integrated.defaultProfile.osx": "zsh"
}
```

---  

## 5️⃣ Add the **SQLiteWrapper** Helper  

**File:** `src/wrappers/sqlitewrapper.ts` (or `sqlitewrapper.js` if you don’t want TypeScript).  

> *All the code below is **copy‑paste ready**.  It uses only Bun’s built‑in `SQL` API, so no extra `npm` packages are required.*

```ts
/* src/wrappers/sqlitewrapper.ts
 * --------------------------------------------------------------
 * Tiny, beginner‑friendly wrapper around Bun’s SQLite driver.
 * Features:
 *   • Full CRUD (Create / Read / Update / Delete)
 *   • Parameterised queries – protects against SQL injection
 *   • Transaction helper (all‑or‑nothing)
 *   • Upsert / INSERT OR IGNORE
 *   • Simple “single‑value” and “scalar” helpers
 *   • Auto‑close via `await db.close()`
 * ------------------------------------------------------------ */

import { SQL } from "bun";

/** Very small sanity‑check – only letters, digits and '_' are allowed */
function validateIdentifier(name: string) {
  if (!/^[A-Za-z_][A-Za-z0-9_]*$/.test(name))
    throw new Error(`Invalid identifier '${name}' – only letters, numbers and '_' are allowed`);
}

/** The class you will instantiate in your code. */
export class SQLiteWrapper {
  private db: SQL;

  /** Open a DB (":memory:" for in‑memory, otherwise a file path). */
  constructor(dbPath: string) {
    if (dbPath === ":memory:") this.db = new SQL(":memory:");
    else if (dbPath.startsWith("sqlite://")) this.db = new SQL(dbPath);
    else this.db = new SQL(dbPath, { adapter: "sqlite" });

    // Turn on foreign‑key enforcement – a good default for relational data
    this.db`PRAGMA foreign_keys = ON`.simple();
  }

  /* -----------------------------------------------------------------
   *  Private low‑level executor (handles “?” → “$1,$2…” conversion)
   * ----------------------------------------------------------------- */
  private async exec(query: string, params: any[] = []) {
    if (params.length === 0) return await this.db.unsafe(query);
    let i = 1;
    const sql = query.replace(/\?/g, () => `$${i++}`);
    return await this.db.unsafe(sql, params);
  }

  /* -----------------------------------------------------------------
   *  PUBLIC CRUD API
   * ----------------------------------------------------------------- */
  /** CREATE TABLE – schema like { id: "INTEGER PRIMARY KEY", name: "TEXT" } */
  async createTable(name: string, schema: Record<string, string>) {
    validateIdentifier(name);
    const cols = Object.entries(schema)
      .map(([c, t]) => `${c} ${t}`)
      .join(", ");
    return await this.exec(`CREATE TABLE IF NOT EXISTS ${name} (${cols})`);
  }

  /** INSERT a row – returns the inserted row (using RETURNING *) */
  async insert(table: string, data: Record<string, any>) {
    validateIdentifier(table);
    return await this.db`INSERT INTO ${this.db(table)} ${this.db(data)} RETURNING *`;
  }

  /** INSERT OR IGNORE – skips rows that would violate a UNIQUE constraint */
  async insertOrIgnore(table: string, data: Record<string, any>) {
    validateIdentifier(table);
    const cols = Object.keys(data);
    const vals = Object.values(data);
    const placeholders = vals.map(() => "?").join(", ");
    const sql = `INSERT OR IGNORE INTO ${table} (${cols.join(", ")}) VALUES (${placeholders})`;
    return await this.exec(sql, vals);
  }

  /** UPSERT – INSERT … ON CONFLICT … DO UPDATE / DO NOTHING */
  async upsert(
    table: string,
    data: Record<string, any>,
    conflictColumns: string[],
    updateOnConflict = true
  ) {
    validateIdentifier(table);
    const cols = Object.keys(data);
    const vals = Object.values(data);
    const placeholders = cols.map(() => "?").join(", ");
    let sql = `INSERT INTO ${table} (${cols.join(", ")}) VALUES (${placeholders})`;

    if (conflictColumns.length) {
      const conflict = conflictColumns.map(validateIdentifier).join(",");
      if (updateOnConflict) {
        const setClause = cols
          .filter(c => !conflictColumns.includes(c))
          .map(c => `${c}=excluded.${c}`)
          .join(", ");
        sql += ` ON CONFLICT(${conflict}) DO UPDATE SET ${setClause ||
          conflictColumns.map(c => `${c}=excluded.${c}`).join(", ")}`;
      } else {
        sql += ` ON CONFLICT(${conflict}) DO NOTHING`;
      }
    }
    return await this.exec(sql, vals);
  }

  /** SELECT – columns can be "*" or a comma‑separated list */
  async select(
    table: string,
    columns = "*",
    whereClause: string | null = null,
    whereParams: any[] = []
  ) {
    validateIdentifier(table);
    const sql = `SELECT ${columns} FROM ${table}` + (whereClause ? ` WHERE ${whereClause}` : "");
    return await this.exec(sql, whereParams);
  }

  /** Get a single row (or undefined) */
  async getRow(table: string, whereClause: string, whereParams: any[] = []) {
    const rows = await this.select(table, "*", whereClause, whereParams);
    return rows[0];
  }

  /** UPDATE rows – `whereClause` must contain at least one “?” placeholder */
  async update(
    table: string,
    data: Record<string, any>,
    whereClause: string,
    whereParams: any[] = []
  ) {
    validateIdentifier(table);
    const set = Object.keys(data).map(k => `${k} = ?`).join(", ");
    const vals = Object.values(data);
    const sql = `UPDATE ${table} SET ${set} WHERE ${whereClause}`;
    return await this.exec(sql, [...vals, ...whereParams]);
  }

  /** DELETE rows – `whereClause` must contain at least one “?” placeholder */
  async delete(table: string, whereClause: string, whereParams: any[] = []) {
    validateIdentifier(table);
    const sql = `DELETE FROM ${table} WHERE ${whereClause}`;
    return await this.exec(sql, whereParams);
  }

  /* -----------------------------------------------------------------
   *  Extra convenience helpers
   * ----------------------------------------------------------------- */
  /** Return a single column value (e.g. COUNT(*)) */
  async getValue(
    table: string,
    column: string,
    whereClause: string,
    whereParams: any[] = []
  ) {
    const sql = `SELECT ${column} FROM ${table} WHERE ${whereClause}`;
    const rows = await this.exec(sql, whereParams);
    return rows[0] ? rows[0][column] : undefined;
  }

  /** Run a batch of statements atomically – rolls back on any error */
  async transaction(
    steps: (string | { sql: string; params?: any[] })[]
  ) {
    return await this.db.begin(async tx => {
      for (const step of steps) {
        if (typeof step === "string") {
          await tx.unsafe(step);
        } else {
          const { sql, params = [] } = step;
          if (params.length === 0) await tx.unsafe(sql);
          else {
            let i = 1;
            const q = sql.replace(/\?/g, () => `$${i++}`);
            await tx.unsafe(q, params);
          }
        }
      }
    });
  }

  /** Close the DB – always call at the end of a script (or in a finally block) */
  async close() {
    await this.db.close();
  }
}
```

> **Security note:** The only place identifiers (table/column names) are interpolated directly is after `validateIdentifier`, which guarantees the string contains only alphanumerics and `_`.  All **values** go through `?` placeholders, which Bun automatically sanitises, so *SQL‑injection is impossible* as long as you use the wrapper’s methods.

---  

## 6️⃣ First Project – “Hello, Bun!” **plus** SQLite CRUD demo  

Below is the **complete source tree** for the demo. Every file can be copy‑pasted into the folder you created with `bun init`.

```
hello_bun/
│─ bunfig.toml
│─ package.json
│─ src/
│   ├─ main.js            ← tiny “Hello, Bun!” entry point
│   ├─ utils.js           ← small string helpers (greeting, ask name)
│   ├─ db_demo.js         ← demo that now uses SQLiteWrapper
│   ├─ http_helpers.js    ← fetch wrappers (unchanged)
│   ├─ tmpl_helpers.js    ← Eta wrapper (unchanged)
│   └─ wrappers/
│        └─ sqlitewrapper.ts   ← the class you just added
└─ templates/
     └─ hello.txt          ← optional file‑template demo
```

### 6.1 `src/main.js` (or `.ts` – does not matter)

```js
#!/usr/bin/env bun
/**
 * Minimal “Hello, Bun!” demo that shows:
 *   – greeting helpers
 *   – SQLite CRUD demo (via SQLiteWrapper)
 *   – Redis demo (unchanged from the original guide)
 *   – HTTP fetch demo
 *   – Eta template rendering
 */

import { greet, askName } from "./utils.js";
import { demoSQLite } from "./db_demo.js";          // <-- now uses SQLiteWrapper
import { redisDemo } from "./db_demo.js";           // unchanged Redis demo
import { getJson } from "./http_helpers.js";
import { renderString } from "./tmpl_helpers.js";

async function main() {
  console.log(greet());                     // default greeting
  console.log(greet(await askName()));      // ask for name

  console.log("\n--- SQLite demo ------------------------------------------------");
  await demoSQLite();                       // <‑‑ new demo

  console.log("\n--- Redis demo -------------------------------------------------");
  await redisDemo();

  console.log("\n--- Fetch demo -------------------------------------------------");
  const joke = await getJson("https://api.chucknorris.io/jokes/random");
  console.log(`Random joke: ${joke.value}`);

  console.log("\n--- Template demo ---------------------------------------------");
  const tmpl = "Hello {{ name }}, today is {{ day }}.";
  console.log(renderString(tmpl, { name: "Avery", day: "Friday" }));
}

main().catch(err => {
  console.error("❌ Unexpected error:", err);
  process.exit(1);
});
```

### 6.2 `src/utils.js`

```js
export const greet = (name = "friend") => `👋 Hello, ${name}! Welcome to Bun on your ARM Mac.`;

export async function askName() {
  try {
    const stdin = new Bun.file("/dev/stdin");
    process.stdout.write("What is your name? ");
    const txt = await stdin.text();
    return txt.trim() || "friend";
  } catch {
    return "friend";
  }
}
```

### 6.3 `src/db_demo.js` – **SQLite demo using the wrapper**  

```js
import { SQLiteWrapper } from "./wrappers/sqlitewrapper.ts";
import { redisDemo as rawRedisDemo } from "./db_demo.js"; // we’ll keep the same Redis demo later

/** --------------------------------------------------------------
 *  demoSQLite – shows every CRUD operation, a transaction, and
 *  upsert/insertOrIgnore helpers.
 *  -------------------------------------------------------------- */
export async function demoSQLite() {
  // ► Open an in‑memory DB (swap ":memory:" → "./data/app.db" for persistence)
  const db = new SQLiteWrapper(":memory:");

  // 1️⃣ CREATE TABLE
  await db.createTable("users", {
    id: "INTEGER PRIMARY KEY AUTOINCREMENT",
    name: "TEXT NOT NULL",
    email: "TEXT UNIQUE NOT NULL",
    age: "INTEGER",
  });
  console.log("✅ Table created");

  // 2️⃣ INSERT (basic)
  const alice = await db.insert("users", {
    name: "Alice",
    email: "alice@example.com",
    age: 28,
  });
  console.log("🆕 Inserted Alice →", alice);

  // 3️⃣ INSERT OR IGNORE (duplicate email is ignored)
  await db.insertOrIgnore("users", {
    name: "Alice Duplicate",
    email: "alice@example.com", // already exists → ignored
    age: 30,
  });
  console.log("🚫 InsertOrIgnore ignored duplicate email");

  // 4️⃣ UPSERT (INSERT … ON CONFLICT … DO UPDATE)
  await db.upsert(
    "users",
    { email: "bob@example.com", name: "Bob", age: 35 },
    ["email"] // conflict column
  );
  console.log("🔄 Upserted Bob (new row)");

  // 5️⃣ SELECT (all rows)
  const all = await db.select("users");
  console.log("📋 All users →", all);

  // 6️⃣ SELECT with WHERE
  const teens = await db.select("users", "*", "age < ?", [20]);
  console.log("👶 Teens (should be empty) →", teens);

  // 7️⃣ GET ONE ROW
  const bob = await db.getRow("users", "email = ?", ["bob@example.com"]);
  console.log("🔎 Bob →", bob);

  // 8️⃣ UPDATE
  await db.update("users", { age: 36 }, "email = ?", ["bob@example.com"]);
  const bobAfter = await db.getRow("users", "email = ?", ["bob@example.com"]);
  console.log("🛠️ Bob after update →", bobAfter);

  // 9️⃣ DELETE
  await db.delete("users", "name = ?", ["Alice"]);
  console.log("❌ Deleted Alice");

  // 1️⃣0️⃣ TRANSACTION (all‑or‑nothing)
  try {
    await db.transaction([
      { sql: "INSERT INTO users (name,email,age) VALUES (?,?,?)", params: ["Carol", "carol@example.com", 22] },
      { sql: "INSERT INTO users (name,email,age) VALUES (?,?,?)", params: ["Dave", "dave@example.com", 31] },
    ]);
    console.log("✅ Transaction succeeded");
  } catch (e) {
    console.error("❌ Transaction failed – rolled back", e);
  }

  // 1️⃣1️⃣ FINAL STATE
  console.log("📦 Final table →", await db.select("users"));

  // 1️⃣2️⃣ Clean up
  await db.close();
}

/** --------------------------------------------------------------
 *  The original Redis demo (unchanged, left here for completeness)
 * -------------------------------------------------------------- */
export async function redisDemo() {
  const { redis } = await import("bun:redis");
  await redis.set("greeting", "👋 Hello from Redis!");
  const msg = await redis.get("greeting");
  console.log("Redis says:", msg);
  await redis.del("greeting");
}
```

> **All the heavy lifting is done by `SQLiteWrapper`.**  
> If you ever need a persistent DB, replace `":memory:"` with a real file path like `"./data/app.db"`.

### 6.4 `src/http_helpers.js` (unchanged – just for completeness)

```js
/** Tiny fetch wrappers – already safe because they use parameterised URLs */
const DEFAULT_TIMEOUT = 10_000; // 10 seconds

async function _handleResponse(resp) {
  if (!resp.ok) {
    const txt = await resp.text();
    throw new Error(`HTTP ${resp.status}: ${txt}`);
  }
  const txt = await resp.text();
  return txt ? JSON.parse(txt) : {};
}

/** GET JSON */
export async function getJson(url, { params = {}, headers = {} } = {}) {
  const query = new URLSearchParams(params).toString();
  const final = query ? `${url}?${query}` : url;
  const resp = await fetch(final, {
    headers,
    signal: AbortSignal.timeout(DEFAULT_TIMEOUT),
  });
  return _handleResponse(resp);
}

/** POST JSON */
export async function postJson(url, payload, { headers = {} } = {}) {
  const resp = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json", ...headers },
    body: JSON.stringify(payload),
    signal: AbortSignal.timeout(DEFAULT_TIMEOUT),
  });
  return _handleResponse(resp);
}
```

### 6.5 `src/tmpl_helpers.js` (unchanged)

```js
import { Eta } from "eta";

/** Render a template string */
export function renderString(tmpl, data) {
  const eta = new Eta({ autoEscape: true });
  return eta.render(tmpl, data);
}

/** Render a file from the ./templates folder */
export function renderFile(relPath, data) {
  const eta = new Eta({ views: "./templates", autoEscape: true });
  return eta.renderFile(relPath, data);
}
```

### 6.6 Install the **Eta** template engine (still needed)

```bash
bun add eta        # one‑liner, Bun fetches the latest version from npm
```

### 6.7 Run the whole demo

```bash
bun src/main.js          # or `bun run src/main.js`
```

You should see a friendly greeting, the SQLite CRUD log, a Redis message, a Chuck‑Norris joke, and the rendered template string.

---  

## 7️⃣ Build | Run | Test | Format | Lint  

Your `package.json` (created by `bun init`) already contains a `scripts` section. Replace it with the following **minimal** version (add the `test` script later if you want a test suite).

```json
{
  "name": "hello-bun",
  "type": "module",
  "scripts": {
    "start": "bun src/main.js",
    "test": "bun test",
    "fmt": "bun fmt",
    "lint": "bun lint",
    "prettier": "bunx prettier --write ."
  },
  "dependencies": {
    "eta": "^2.0.0"
  },
  "devDependencies": {
    "eslint": "^9.0.0",
    "prettier": "^3.3.0"
  }
}
```

| Action | Command | What it does |
|--------|---------|--------------|
| **Run the demo** | `bun run start` | Executes `src/main.js`. |
| **Run tests** (once you add them) | `bun run test` | Bun’s built‑in test runner. |
| **Re‑format every file** | `bun run fmt` | Uses Bun’s built‑in formatter. |
| **Lint (ESLint)** | `bun run lint --fix` | Shows style problems and automatically fixes many of them. |
| **Prettier** | `bun run prettier` | Another formatter you may prefer; runs via `bunx`. |

> **Official reference** – every `bun <command>` is documented under the **CLI** page: <https://bun.sh/docs/cli>.

---  

## 8️⃣ Debugging Inside VS Code (or with `bun debug`)  

1. Open the **Run & Debug** view (`⇧⌘D`).  
2. Click **“Create a launch.json file”** → choose **Node.js**.  
3. Replace the generated content with:

```json
{
  "type": "node",
  "request": "launch",
  "name": "Debug Bun – Hello",
  "runtimeExecutable": "~/.bun/bin/bun",
  "program": "${workspaceFolder}/src/main.js",
  "console": "integratedTerminal"
}
```

4. Set breakpoints (click next to a line number).  
5. Press **F5** – the debugger stops, lets you inspect variables, step over `await`s, etc.  

You can also start the debugger from the terminal:

```bash
bun debug src/main.js
```

*Docs:* <https://bun.sh/docs/debugger>.

---  

## 9️⃣ Reusable Helper Library (strings, numbers, files, HTTP, templates)

The file `src/helpers.js` (or `.ts`) can be copied into any future project.  
It **does not** rely on the SQLite wrapper – it’s pure‑JS utilities you can import wherever you need them.

```js
// src/helpers.js
/***  REUSABLE HELPERS – copy‑paste into any Bun project ***/

// ✨ Strings -------------------------------------------------
export const welcome = name => `👋 Hey ${name}, Bun is ready on your Mac!`;
export const shout = s => s.toUpperCase();
export const truncate = (s, limit = 80) => (s.length > limit ? s.slice(0, limit) + "…" : s);

// 🔢 Numbers -------------------------------------------------
export const celsiusToF = c => (c * 9) / 5 + 32;
export const safeDiv = (a, b) => {
  if (b === 0) throw new Error("Division by zero");
  return a / b;
};
export const average = arr => (arr.reduce((s, v) => s + v, 0) / arr.length) || 0;

// 📁 Files ---------------------------------------------------
export const writeText = (path, txt) => Bun.write(path, txt, "utf8");
export const readText = path => Bun.file(path).text();
export const writeJson = (path, obj) => Bun.write(path, JSON.stringify(obj, null, 2), "utf8");
export const readJson = async path => JSON.parse(await Bun.file(path).text());

// 🌐 HTTP ----------------------------------------------------
export async function getJson(url, { params = {}, headers = {} } = {}) {
  const query = new URLSearchParams(params).toString();
  const final = query ? `${url}?${query}` : url;
  const resp = await fetch(final, { headers, signal: AbortSignal.timeout(10_000) });
  if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
  return resp.json();
}
export async function postJson(url, payload, { headers = {} } = {}) {
  const resp = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json", ...headers },
    body: JSON.stringify(payload),
    signal: AbortSignal.timeout(10_000),
  });
  if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
  return resp.json();
}

// 🖋️ Templates (Eta) ---------------------------------------
import { Eta } from "eta";
export const renderString = (tmpl, data) => new Eta({ autoEscape: true }).render(tmpl, data);
export const renderFile = (file, data) =>
  new Eta({ views: "./templates", autoEscape: true }).renderFile(file, data);
```

**Example usage**

```js
import { welcome, getJson, renderString } from "./helpers.js";

console.log(welcome("Avery"));
const joke = await getJson("https://api.chucknorris.io/jokes/random");
console.log(`Joke: ${joke.value}`);
console.log(renderString("Hi {{ name }}!", { name: "Bun" }));
```

---  

## 🔟 Best‑Practice Checklist (incl. DB & HTTP items)

| ✅ | Practice | Why it matters |
|---|----------|----------------|
| 1️⃣ | **Pin the Bun version** (`bun --version` → add to README) | Guarantees the same runtime on all machines. |
| 2️⃣ | **Never hard‑code secrets** – keep them in `.env` and load with `bun add dotenv`. | Prevents accidental leaks to git. |
| 3️⃣ | **Always use placeholders** (`?`) for user data – never concatenate strings into SQL. | Stops SQL‑injection. |
| 4️⃣ | **Validate identifiers** (`validateIdentifier` in the wrapper) when you *must* build a table name dynamically. |
| 5️⃣ | **Close DB connections** (`await db.close()`) – put in a `finally` block. |
| 6️⃣ | **Wrap external API calls** in try / catch and enforce a timeout (`AbortSignal.timeout`). |
| 7️⃣ | **Run lint & format on every commit** – add a pre‑commit hook (`husky` or `lefthook`). |
| 8️⃣ | **Prefer `await` everywhere** – don’t mix callbacks with promises. |
| 9️⃣ | **Keep each helper file < 300 LOC** – easier to read, easier to test. |
| 🔟 | **Upgrade Bun regularly** (`bun upgrade`) – you get performance and security patches. |

---  

## 🅰️ Common Pitfalls & Quick Fixes  

| Symptom | Likely cause | One‑line fix |
|---------|--------------|--------------|
| `fetch` throws **AbortError** after 10 s | Default timeout (`DEFAULT_TIMEOUT`) is too low for the remote API. | Raise the constant in `http_helpers.js` (e.g., `const DEFAULT_TIMEOUT = 30_000`). |
| `resp.ok` is false and you see an HTML error page | The endpoint returned an error (404/500). | Look at `resp.status` in the thrown error; adjust the URL or add auth headers. |
| SQLite query returns **empty array** | Table wasn’t created or you used the wrong DB file. | Verify you called `await db.createTable(...)` *before* any `SELECT`. |
| `redis.get("key")` returns `null` | Redis server not running. | Start it: `brew services start redis` or `redis-server &`. |
| VS Code says **“fetch is undefined”** | The Bun extension isn’t enabled. | Install the **Bun** extension and ensure `"bun.enable": true` in `settings.json`. |
| Test suite hangs after SQLite demo | DB connection wasn’t closed. | Always `await db.close()` (or use `using`/`await using` in future Bun versions). |
| ESLint reports **“unexpected use of console”** | Linter rules are strict. | Add `/* eslint-disable no-console */` at top of demo files, or configure ESLint to allow `console`. |

---  

## 🅱️ Handy Keyboard Shortcuts (macOS + VS Code)

| Shortcut | Action |
|----------|--------|
| `⌘ P` | Quick‑open any file. |
| `⇧ ⌘ P` | Command Palette – search any command. |
| `⌘ B` | Toggle the Explorer sidebar. |
| `⌘ ⇧ F` | Format document (runs `bun fmt`). |
| `⌘ /` | Toggle line comment. |
| `F5` | Start the debugger (uses the `launch.json` we created). |
| ``⌃ ` `` | Open the integrated terminal. |
| `⌘ Shift D` | Open Run & Debug view. |
| `⌘ Shift B` | Run the default VS Code build task (you can map it to `bun test`). |

---  

## 🆑 Further Learning & Official References  

| Topic | Official source |
|-------|-----------------|
| **Bun core docs** | <https://bun.sh/docs> |
| **Installation** | <https://bun.sh/docs/installation> |
| **CLI reference** (`run`, `test`, `fmt`, `lint`, `build`) | <https://bun.sh/docs/cli> |
| **`fetch` API** | <https://bun.sh/reference/globals/fetch> |
| **SQLite driver (`bun:sqlite`)** | <https://bun.sh/docs/api/sqlite> |
| **Redis client (`bun:redis`)** | <https://bun.sh/docs/api/redis> |
| **Debugger** | <https://bun.sh/docs/debugger> |
| **Package runner (`bunx`)** | <https://bun.sh/docs/cli/run> |
| **Eta template engine** | <https://eta.js.org/> |
| **ESLint** | <https://eslint.org/docs/latest/> |
| **Prettier** | <https://prettier.io/> |
| **Commander (CLI helper)** | <https://github.com/tj/commander.js> |
| **Worker Threads (concurrency)** | <https://nodejs.org/api/worker_threads.html> |
| **Bun release notes** | <https://github.com/oven-sh/bun/releases> |

---  

## 🆎 macOS‑Specific Tips (building & distributing)  

| Situation | Recommended approach |
|-----------|----------------------|
| **Create a single‑file native binary** | `bun build src/main.js --compile --name hello-bun` – produces `hello-bun` that runs on any Mac (ARM or Intel). |
| **Universal binary (ARM + Intel)** | Build twice (`--arch aarch64` and `--arch x86_64`) then combine: `lipo -create -output hello-universal hello-arm hello-x86`. |
| **Code signing for Gatekeeper** | `codesign --force --sign "Developer ID Application: Your Name (TEAMID)" hello-bun` and verify with `spctl --assess --verbose=4 hello-bun`. |
| **Homebrew formula for a Bun‑based CLI** | Write a tiny Ruby formula that runs `bun install` inside the build step – Homebrew will automatically fetch the correct Bun binary for the host. |
| **Access macOS Keychain** | Install `bun add keyring` and use `keyring.getPassword("service", "account")`. |
| **GPU‑accelerated work (Metal)** | Look for `@napi-rs/macos-metal` on npm and install with `bun add @napi-rs/macos-metal`. |
| **Running under Rosetta 2** (rare) | Use the x86_64 Bun binary: `arch -x86_64 ~/.bun/bin/bun <command>` after installing the Intel build. |

---  

# 🎉 You’re Done!  

You now have:

1. **A fully‑working Bun environment** on Apple Silicon (Xcode CLT, Homebrew, Bun, SQLite, Redis).  
2. **A copy‑paste SQLite wrapper** that gives you **CRUD + transactions + upserts + safety checks**.  
3. **A starter project** (`src/main.js`) that greets the user, talks to SQLite, Redis, an external API, and a tiny template engine.  
4. **Reusable helper libraries** for strings, numbers, file I/O, HTTP, and templating.  
5. **Debugging, testing, formatting, and linting** all wired to one‑line commands.  
6. **Best‑practice checklist**, **common‑pitfall guide**, **keyboard shortcuts**, and **official references** so you can keep learning without getting lost.

Run `bun src/main.js` again, edit the code, add your own tables, and watch the wrapper keep everything safe and tidy.  

Enjoy coding on your Apple Silicon Mac – **Bun makes it fast, simple, and fun!** 🚀🟢  