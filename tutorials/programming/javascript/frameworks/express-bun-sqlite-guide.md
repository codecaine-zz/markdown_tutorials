# Express.js with Bun + SQLite — Practical Tutorial (ARM Mac optimized)

Copy‑pasteable guide to build a fast, tiny REST API using Express.js on Bun and Bun’s built‑in SQLite (`bun:sqlite`). Optimized defaults for Apple Silicon (M1/M2/M3+) Macs, with clean, easy‑to‑read code.

## Table of Contents

- [Overview](#overview)
- [Why Bun + Database Wrappers](#why-bun--database-wrappers)
- [Prerequisites (ARM Mac)](#prerequisites-arm-mac)
- [Project Setup](#project-setup)
- [App Structure](#app-structure)
- [Database (Using Database Wrapper)](#database-using-database-wrapper)
- [Repository (Type-Safe Data Access)](#repository-type-safe-data-access)
- [Express App](#express-app)
- [CRUD Routes (Type-Safe Todos API)](#crud-routes-type-safe-todos-api)
- [Run and Test](#run-and-test)
- [Error Handling](#error-handling)
- [Performance Tips](#performance-tips)
- [Security Basics](#security-basics)
- [Troubleshooting](#troubleshooting)
- [Next Steps](#next-steps)

---

## Overview

We'll build a minimal REST API for a Todo list:

- Runtime: Bun (fast, single binary, Node compatible)
- Web framework: Express.js
- Database: SQLite via production-ready database wrapper
- Storage: Local file `data/app.db` with optimized configuration
- Features: Type-safe CRUD, error handling, automatic resource management, transactions

All code blocks are complete and copy‑pasteable.

## Why Bun + Database Wrappers

- Bun is fast and compatible with Node.js ecosystems
- Database wrappers provide type safety, automatic resource management, and built-in security
- SQLite is zero‑config, durable, and perfect for local apps/APIs and small to medium services
- WAL mode gives excellent concurrency (many readers, one writer), great for APIs
- Automatic cleanup prevents memory leaks and resource exhaustion

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
bun add https://github.com/codecaine-zz/bun_database_wrappers
```

This creates `package.json`, installs Express, and adds the production-ready database wrappers. Bun defaults to ESM imports which work perfectly with both Express and the database wrappers.

## App Structure

```text
.
├─ src/
│  ├─ db.js          # Database wrapper setup and initialization
│  ├─ repo.js        # Type-safe data access layer (todos repository)
│  ├─ routes.js      # Express routes with async/await support
│  └─ server.js      # Express app + middleware + database initialization
└─ data/
   └─ app.db        # SQLite database file (auto-created)
```

Create files as below.

## Database (Using Database Wrapper)

First, install the database wrappers:

```bash
# Add the database wrappers from GitHub
bun add https://github.com/codecaine-zz/bun_database_wrappers
```

File: `src/db.js`

```javascript
// src/db.js
import { createSQLite } from "bun_database_wrappers";

// Create database instance with automatic resource management
export const db = createSQLite("data/app.db");

// Initialize database with schema (idempotent)
export async function initializeDatabase() {
  // Create todos table
  await db.createTable("todos", {
    id: "INTEGER PRIMARY KEY AUTOINCREMENT",
    title: "TEXT NOT NULL",
    completed: "INTEGER NOT NULL DEFAULT 0",
    created_at: "TEXT NOT NULL DEFAULT (datetime('now'))",
    updated_at: "TEXT NOT NULL DEFAULT (datetime('now'))"
  });
}
```

## Repository (Type-Safe Data Access)

File: `src/repo.js`

```javascript
// src/repo.js
import { db } from "./db.js";

// TypeScript-style interface for documentation (JSDoc)
/**
 * @typedef {Object} Todo
 * @property {number} [id] - Auto-generated ID
 * @property {string} title - Todo title
 * @property {number} completed - 0 or 1 (SQLite boolean)
 * @property {string} [created_at] - ISO timestamp
 * @property {string} [updated_at] - ISO timestamp
 */

export async function listTodos() {
  return await db.select("todos", "*", null, null, "id DESC");
}

export async function getTodo(id) {
  return await db.get("SELECT * FROM todos WHERE id = ?", [id]);
}

export async function createTodo({ title, completed = 0 }) {
  const result = await db.insert("todos", { title, completed });
  return await getTodo(Number(result.lastInsertRowid));
}

export async function updateTodo(id, data) {
  const current = await getTodo(id);
  if (!current) return undefined;
  
  const updateData = {
    title: data.title ?? current.title,
    completed: data.completed ?? current.completed,
    updated_at: "datetime('now')"
  };
  
  await db.update("todos", updateData, "id = ?", [id]);
  return await getTodo(id);
}

export async function deleteTodo(id) {
  const before = await getTodo(id);
  if (!before) return false;
  
  await db.delete("todos", "id = ?", [id]);
  return true;
}

export async function seedTodos(rows) {
  // Use transaction for bulk inserts
  await db.transaction(
    rows.map(row => ({
      sql: "INSERT INTO todos (title, completed) VALUES (?, ?)",
      params: [row.title, row.completed ? 1 : 0]
    }))
  );
  return rows.length;
}
```

## Express App

File: `src/server.js`

```javascript
// src/server.js
import express from "express";
import { router } from "./routes.js";
import { initializeDatabase } from "./db.js";

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

// Initialize database and start server
async function startServer() {
  try {
    await initializeDatabase();
    console.log("Database initialized successfully");
    
    app.listen(PORT, () => {
      console.log(`Server running on http://localhost:${PORT}`);
    });
  } catch (error) {
    console.error("Failed to start server:", error);
    process.exit(1);
  }
}

startServer();
```

## CRUD Routes (Type-Safe Todos API)

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

// Health check
router.get("/health", (_req, res) => {
  res.json({ ok: true, uptime: process.uptime() });
});

// Seed (dev only)
router.post("/seed", async (req, res) => {
  try {
    const rows = Array.isArray(req.body) ? req.body : [];
    const count = await seedTodos(
      rows.map((t) => ({ 
        title: String(t.title || "Untitled"), 
        completed: t.completed ? 1 : 0 
      }))
    );
    res.json({ inserted: count });
  } catch (error) {
    console.error("Seed error:", error);
    res.status(500).json({ error: "Failed to seed todos" });
  }
});

// List all todos
router.get("/todos", async (_req, res) => {
  try {
    const items = await listTodos();
    // Convert SQLite integers to JavaScript booleans for completed field
    const todos = items.map((t) => ({ 
      ...t, 
      completed: Boolean(t.completed) 
    }));
    res.json(todos);
  } catch (error) {
    console.error("List todos error:", error);
    res.status(500).json({ error: "Failed to fetch todos" });
  }
});

// Get todo by id
router.get("/todos/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ error: "Invalid id" });
    }
    
    const item = await getTodo(id);
    if (!item) {
      return res.status(404).json({ error: "Not found" });
    }
    
    // Convert SQLite integer to boolean
    item.completed = Boolean(item.completed);
    res.json(item);
  } catch (error) {
    console.error("Get todo error:", error);
    res.status(500).json({ error: "Failed to fetch todo" });
  }
});

// Create new todo
router.post("/todos", async (req, res) => {
  try {
    const title = typeof req.body?.title === "string" ? req.body.title.trim() : "";
    const completed = req.body?.completed ? 1 : 0;
    
    if (title.length === 0) {
      return res.status(400).json({ error: "title is required" });
    }
    
    const item = await createTodo({ title, completed });
    item.completed = Boolean(item.completed);
    res.status(201).json(item);
  } catch (error) {
    console.error("Create todo error:", error);
    res.status(500).json({ error: "Failed to create todo" });
  }
});

// Update todo (partial)
router.patch("/todos/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ error: "Invalid id" });
    }
    
    const data = {};
    if (typeof req.body?.title === "string") {
      data.title = req.body.title.trim();
    }
    if (typeof req.body?.completed === "boolean") {
      data.completed = req.body.completed ? 1 : 0;
    }
    
    const item = await updateTodo(id, data);
    if (!item) {
      return res.status(404).json({ error: "Not found" });
    }
    
    item.completed = Boolean(item.completed);
    res.json(item);
  } catch (error) {
    console.error("Update todo error:", error);
    res.status(500).json({ error: "Failed to update todo" });
  }
});

// Delete todo
router.delete("/todos/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ error: "Invalid id" });
    }
    
    const ok = await deleteTodo(id);
    if (!ok) {
      return res.status(404).json({ error: "Not found" });
    }
    
    res.status(204).end();
  } catch (error) {
    console.error("Delete todo error:", error);
    res.status(500).json({ error: "Failed to delete todo" });
  }
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

## Error Handling

The database wrapper provides comprehensive error handling:

- Built-in `DBError` class with rich context information
- Automatic SQL injection protection through identifier validation
- Type-safe operations prevent runtime errors
- Proper async/await error propagation

Example error handling:

```javascript
import { DBError } from "bun_database_wrappers";

try {
  await db.select("invalid_table");
} catch (error) {
  if (error instanceof DBError) {
    console.log("Query:", error.context.query);
    console.log("Params:", error.context.params);
    console.log("Cause:", error.cause);
  }
}
```

- Use a single global error handler (already included).
- Return 400 for bad input, 404 for missing resources, 500 for unexpected errors.
- Log errors to console in dev; consider a logger for prod.

## Performance Tips

- Database wrapper automatically manages connections and prepared statements
- Uses WAL mode and optimized SQLite settings for best performance
- Built-in transaction support for atomic operations
- Automatic resource cleanup prevents memory leaks
- Type-safe operations reduce overhead of runtime type checking
- For read‑heavy endpoints, consider pagination with `LIMIT/OFFSET`
- Batch operations use transactions automatically for optimal performance

Example pagination:

```javascript
// Add to repo.js
export async function getTodosPaginated(page = 1, limit = 10) {
  const offset = (page - 1) * limit;
  return await db.all(
    "SELECT * FROM todos ORDER BY id DESC LIMIT ? OFFSET ?",
    [limit, offset]
  );
}
```

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

- "Module not found: express": run `bun add express` in the project folder.
- "Module not found: bun_database_wrappers": run `bun add https://github.com/codecaine-zz/bun_database_wrappers`.
- "Invalid SQL identifier" error: Only alphanumeric and underscore characters allowed in table/column names (prevents SQL injection).
- Database wrapper errors: Check `error.context.query` and `error.context.params` for debugging.
- TypeScript errors with generics: Ensure interfaces extend `Record<string, unknown>`.
- Async/await errors: Ensure all database operations use `await` keyword.
- ESM import errors: ensure you're using Bun to run (`bun src/server.js`).

## Next Steps

- Add input validation with [Zod](https://github.com/colinhacks/zod) for stricter type checking
- Implement pagination and search filters using the database wrapper's query methods
- Split feature domains into modules (`users`, `projects`, etc.)
- Add TypeScript for full compile-time type safety
- Serve a frontend (static or SSR) via Express, or use Bun's bundler
- Add tests with `bun test` using the database wrapper's test helpers
- Explore other database wrappers (MySQL, Redis) for multi-database architectures

---

Copy any block into your project and adapt names/paths as needed.
