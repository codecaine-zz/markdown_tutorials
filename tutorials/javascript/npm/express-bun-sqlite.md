# Express.js with Bun + SQLite — Practical Tutorial (ARM Mac optimized)

Copy‑pasteable guide to build a fast, tiny REST API using Express.js on Bun and Bun’s built‑in SQLite (`bun:sqlite`). Optimized defaults for Apple Silicon (M1/M2/M3) Macs, with clean, easy‑to‑read code.

## Table of Contents

- [Overview](#overview)
- [Why Bun + SQLite](#why-bun--sqlite)
- [Prerequisites (ARM Mac)](#prerequisites-arm-mac)
- [Project Setup](#project-setup)
- [App Structure](#app-structure)
- [Database (bun:sqlite)](#database-bunsqlite)
- [Express App](#express-app)
- [CRUD Routes (Todos example)](#crud-routes-todos-example)
- [Run and Test](#run-and-test)
- [Validation (optional)](#validation-optional)
- [Error Handling](#error-handling)
- [Performance Tips](#performance-tips)
- [Security Basics](#security-basics)
- [Troubleshooting](#troubleshooting)
- [Next Steps](#next-steps)

---

## Overview

We’ll build a minimal REST API for a Todo list:

- Runtime: Bun (fast, single binary, Node compatible)
- Web framework: Express.js
- Database: Bun’s built‑in `bun:sqlite` (no extra native deps)
- Storage: Local file `data/app.db` with WAL mode enabled
- Features: CRUD, prepared statements, transactions, safe integers, strict bindings

All code blocks are complete and copy‑pasteable.

## Why Bun + SQLite

- Bun is fast and ships a native SQLite driver with a simple, synchronous API.
- SQLite is zero‑config, durable, and perfect for local apps/APIs and small to medium services.
- WAL mode gives excellent concurrency (many readers, one writer), great for APIs.

## Prerequisites (ARM Mac)

- macOS on Apple Silicon (M1/M2/M3)
- Bun installed

```bash
curl -fsSL https://bun.sh/install | bash
# restart terminal or: source ~/.zshrc
bun --version
```

No Homebrew SQLite needed—`bun:sqlite` is built-in.

## Project Setup

```bash
mkdir express-bun-sqlite && cd $_
bun init -y
bun add express
```

This creates `package.json` and installs Express. Bun defaults to ESM imports which work fine with Express.

## App Structure

```text
.
├─ src/
│  ├─ db.js          # SQLite connection, schema, prepared statements
│  ├─ repo.js        # Data access helpers (todos repository)
│  ├─ routes.js      # Express routes (wire repo to HTTP)
│  └─ server.js      # Express app + middleware + startup
└─ data/
   └─ app.db        # SQLite database file (auto-created)
```

Create files as below.

## Database (`bun:sqlite`)

File: `src/db.js`

```javascript
// src/db.js
import { Database } from "bun:sqlite";

// One shared connection per process
export const db = new Database("data/app.db", {
  create: true,
  strict: true,       // allow binding without prefixes and throw on missing params
  safeIntegers: true, // return integers as bigint for safety
});

// Recommended pragmas for APIs
db.exec(`
  PRAGMA journal_mode = WAL;        -- better concurrency
  PRAGMA foreign_keys = ON;         -- enforce FK constraints
  PRAGMA busy_timeout = 3000;       -- wait for 3s if the DB is busy
`);

// Schema (idempotent)
db.exec(`
  CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    completed INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now'))
  );
`);

// Prepared statements (cached on db)
export const stmt = {
  insertTodo: db.query(
    `INSERT INTO todos (title, completed) VALUES ($title, $completed)`
  ),
  getTodo: db.query(
    `SELECT id, title, completed, created_at, updated_at FROM todos WHERE id = $id`
  ),
  listTodos: db.query(
    `SELECT id, title, completed, created_at, updated_at FROM todos ORDER BY id DESC`
  ),
  updateTodo: db.query(
    `UPDATE todos
     SET title = $title,
         completed = $completed,
         updated_at = datetime('now')
     WHERE id = $id`
  ),
  deleteTodo: db.query(`DELETE FROM todos WHERE id = $id`),
};

// Batch helpers using transactions
export const tx = {
  insertTodos: db.transaction((rows) => {
    for (const row of rows) stmt.insertTodo.run(row);
    return rows.length;
  }),
};
```

## Repository (clean data access)

File: `src/repo.js`

```javascript
// src/repo.js
import { stmt, tx } from "./db.js";

export function listTodos() {
  return stmt.listTodos.all();
}

export function getTodo(id) {
  return stmt.getTodo.get({ id });
}

export function createTodo({ title, completed = 0 }) {
  const { lastInsertRowid } = stmt.insertTodo.run({ title, completed });
  return getTodo(Number(lastInsertRowid));
}

export function updateTodo(id, data) {
  const current = getTodo(id);
  if (!current) return undefined;
  const title = data.title ?? current.title;
  const completed = data.completed ?? current.completed;
  stmt.updateTodo.run({ id, title, completed });
  return getTodo(id);
}

export function deleteTodo(id) {
  const before = getTodo(id);
  if (!before) return false;
  stmt.deleteTodo.run({ id });
  return true;
}

export function seedTodos(rows) {
  return tx.insertTodos(rows);
}
```

## Express App

File: `src/server.js`

```javascript
// src/server.js
import express from "express";
import { router } from "./routes.js";

const app = express();

// Minimal, fast middlewares
app.disable("x-powered-by");
app.use(express.json({ limit: "100kb" }));

// Simple request logger (no deps)
app.use((req, res, next) => {
  const start = performance.now();
  res.on("finish", () => {
    const ms = (performance.now() - start).toFixed(1);
    console.log(`${req.method} ${req.originalUrl} ${res.statusCode} - ${ms}ms`);
  });
  next();
});

// Routes
app.use("/api", router);

// 404
app.use((req, res) => {
  res.status(404).json({ error: "Not found" });
});

// Global error handler
app.use((err, req, res, _next) => {
  console.error(err);
  res.status(500).json({ error: "Internal Server Error" });
});

const PORT = Number(process.env.PORT || 3000);
app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});
```

## CRUD Routes (Todos example)

File: `src/routes.js`

```javascript
// src/routes.js
import { Router } from "express";
import {
  listTodos,
  getTodo,
  createTodo,
  updateTodo,
  deleteTodo,
  seedTodos,
} from "./repo.js";

export const router = Router();

// Health
router.get("/health", (_req, res) => {
  res.json({ ok: true, uptime: process.uptime() });
});

// Seed (dev only)
router.post("/seed", (req, res) => {
  const rows = Array.isArray(req.body) ? req.body : [];
  const count = seedTodos(
    rows.map((t) => ({ title: String(t.title || "Untitled"), completed: t.completed ? 1 : 0 }))
  );
  res.json({ inserted: count });
});

// List
router.get("/todos", (_req, res) => {
  const items = listTodos().map((t) => ({ ...t, completed: Number(t.completed) }));
  res.json(items);
});

// Get by id
router.get("/todos/:id", (req, res) => {
  const id = Number(req.params.id);
  if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
  const item = getTodo(id);
  if (!item) return res.status(404).json({ error: "Not found" });
  item.completed = Number(item.completed);
  res.json(item);
});

// Create
router.post("/todos", (req, res) => {
  const title = typeof req.body?.title === "string" ? req.body.title.trim() : "";
  const completed = req.body?.completed ? 1 : 0;
  if (title.length === 0) return res.status(400).json({ error: "title is required" });
  const item = createTodo({ title, completed });
  item.completed = Number(item.completed);
  res.status(201).json(item);
});

// Update (partial)
router.patch("/todos/:id", (req, res) => {
  const id = Number(req.params.id);
  if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
  const data = {};
  if (typeof req.body?.title === "string") data.title = req.body.title.trim();
  if (typeof req.body?.completed === "boolean") data.completed = req.body.completed ? 1 : 0;
  const item = updateTodo(id, data);
  if (!item) return res.status(404).json({ error: "Not found" });
  item.completed = Number(item.completed);
  res.json(item);
});

// Delete
router.delete("/todos/:id", (req, res) => {
  const id = Number(req.params.id);
  if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
  const ok = deleteTodo(id);
  if (!ok) return res.status(404).json({ error: "Not found" });
  res.status(204).end();
});
```

## Run and Test

Start the server (hot reload optional):

```bash
# dev
bun --hot src/server.js

# or without hot reload
bun src/server.js
```

Test with curl:

```bash
# health
curl -s http://localhost:3000/api/health | jq

# seed a few todos
curl -s -X POST http://localhost:3000/api/seed \
  -H 'content-type: application/json' \
  -d '[{"title":"Learn Bun"},{"title":"Build API","completed":true}]' | jq

# list
curl -s http://localhost:3000/api/todos | jq

# create
curl -s -X POST http://localhost:3000/api/todos \
  -H 'content-type: application/json' \
  -d '{"title":"Write docs"}' | jq

# get
curl -s http://localhost:3000/api/todos/1 | jq

# update (partial)
curl -s -X PATCH http://localhost:3000/api/todos/1 \
  -H 'content-type: application/json' \
  -d '{"completed":true}' | jq

# delete
curl -i -X DELETE http://localhost:3000/api/todos/1
```

Data is persisted to `data/app.db`.

## Validation (optional)

For stricter validation, add [Zod](https://github.com/colinhacks/zod):

```bash
bun add zod
```

Then validate inputs inside routes before calling the repo.

## Error Handling

- Use a single global error handler (already included).
- Return 400 for bad input, 404 for missing resources, 500 for unexpected errors.
- Log errors to console in dev; consider a logger for prod.

## Performance Tips

- Keep one `Database` instance per process; reuse prepared statements.
- WAL mode + `busy_timeout` help with concurrency under load.
- Batch inserts with `db.transaction(...)` for big imports.
- Avoid N+1 queries in larger apps—add joins or repository methods as needed.
- For read‑heavy endpoints, consider pagination (`LIMIT/OFFSET`).

## Security Basics

- `app.disable("x-powered-by")` hides Express signature.
- Validate and sanitize inputs; keep error messages generic.
- For public APIs, add CORS as needed and a rate limiter.

Optional packages:

```bash
bun add cors express-rate-limit helmet
```

Then wire them at the top of `src/server.js`:

```javascript
import helmet from "helmet";
import cors from "cors";
import rateLimit from "express-rate-limit";

app.use(helmet());
app.use(cors());
app.use(rateLimit({ windowMs: 60_000, max: 300 }));
```

## Troubleshooting

- “Module not found: express”: run `bun add express` in the project folder.
- DB locked under heavy writes: increase `PRAGMA busy_timeout`, ensure single process, keep transactions short.
- Integers appear as `bigint`: set `safeIntegers: false` if you prefer JS numbers (may lose precision for big values).
- ESM import errors: ensure you’re using Bun to run (`bun src/server.js`).

## Next Steps

- Add pagination and search filters.
- Split feature domains into modules (`users`, `projects`, etc.).
- Serve a frontend (static or SSR) via Express, or use Bun’s bundler.
- Add tests with `bun test`.

---

Copy any block into your project and adapt names/paths as needed.
