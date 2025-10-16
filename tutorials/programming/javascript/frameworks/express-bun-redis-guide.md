# Express.js with Bun + Redis — Practical Tutorial (ARM Mac optimized)

Copy‑pasteable guide to build a fast REST API using Express.js on Bun with Redis as the backend, using Bun’s native Redis client. Optimized for Apple Silicon (M1/M2/M3+) Macs with simple, readable code.

## Table of Contents

- Overview
- Why Bun + Redis
- Prerequisites (ARM Mac)
- Project Setup
- App Structure
- Redis Client (Bun)
- Repository (Todos with Redis)
- Express Routes
- Server
- Run and Test
- Caching and TTL (optional)
- Error Handling
- Performance Tips
- Security Basics
- Troubleshooting
- Next Steps

---

## Overview

We’ll build a tiny Todo API where data is stored in Redis:

- Runtime: Bun (Node-compatible, fast)
- Web framework: Express.js
- Database: Redis (keys + hashes + sorted set index)
- Features: Auto‑incrementing IDs, CRUD, list index, optional TTL/caching

All blocks below are complete and copy‑pasteable.

## Why Bun + Redis

- Bun ships a native Redis client with auto‑pipelining and reconnection.
- Redis is perfect for fast data access, caching, counters, sessions, and ephemeral storage.
- Great for APIs that need super low latency and simple data shapes.

## Prerequisites (ARM Mac)

- Bun installed

```bash
curl -fsSL https://bun.sh/install | bash
# restart terminal or: source ~/.zshrc
bun --version
```

- Redis server running locally or remote URL

```bash
# Install via Homebrew
brew install redis

# Start as a background service
brew services start redis

# Verify
redis-cli ping
```

If you use a remote Redis, set `REDIS_URL` (e.g., `redis://localhost:6379`).

## Project Setup

```bash
mkdir express-bun-redis && cd $_
bun init -y
bun add express
```

Set `REDIS_URL` in your environment as needed. Defaults to `redis://localhost:6379`.

## App Structure

```text
.
├─ src/
│  ├─ redis.js      # Bun Redis client init + helpers
│  ├─ repo.js       # Data access (todos in Redis)
│  ├─ routes.js     # Express routes
│  └─ server.js     # Express app + middleware + startup
```

## Redis Client (Bun)

File: `src/redis.js`

```javascript
// src/redis.js
import { RedisClient } from "bun";

const REDIS_URL = process.env.REDIS_URL || "redis://localhost:6379";

// One client per process; auto-pipelining & reconnection enabled by default
export const redis = new RedisClient(REDIS_URL, {
  // tweakable options:
  connectionTimeout: 10_000,
  idleTimeout: 0,
  autoReconnect: true,
  maxRetries: 10,
  enableOfflineQueue: true,
  enableAutoPipelining: true,
});

// Optional: connect early to fail fast
export async function ensureRedis() {
  if (!redis.connected) {
    try {
      await redis.connect();
      // PING via raw command
      await redis.send("PING", []);
      console.log("Redis connected");
    } catch (err) {
      console.error("Redis connect error:", err);
      throw err;
    }
  }
}

// Graceful close on shutdown
process.on("SIGINT", () => {
  try { redis.close(); } catch {}
  process.exit(0);
});
```

## Repository (Todos with Redis)

We’ll store each Todo as a Redis Hash at `todo:{id}` and keep a sorted set index `todo:index` (score = id) for ordering.

File: `src/repo.js`

```javascript
// src/repo.js
import { redis } from "./redis.js";

const SEQ_KEY = "todo:seq";        // INCR for next id
const INDEX_KEY = "todo:index";    // ZADD score=id, member=id

function idKey(id) {
  return `todo:${id}`;
}

function nowISO() {
  return new Date().toISOString();
}

function toTodoObject(fieldsArray) {
  // Expect [id, title, completed, created_at, updated_at]
  const [id, title, completed, created_at, updated_at] = fieldsArray;
  if (id == null) return undefined;
  return {
    id: Number(id),
    title: title ?? "",
    completed: completed === "1" || completed === 1,
    created_at: created_at || "",
    updated_at: updated_at || "",
  };
}

export async function listTodos() {
  const ids = await redis.send("ZREVRANGE", [INDEX_KEY, "0", "-1"]);
  if (!ids?.length) return [];
  // Parallel HMGET; Bun auto-pipelines
  const todos = await Promise.all(
    ids.map((id) => redis.hmget(idKey(id), [
      "id",
      "title",
      "completed",
      "created_at",
      "updated_at",
    ]))
  );
  return todos
    .map(toTodoObject)
    .filter(Boolean);
}

export async function getTodo(id) {
  const key = idKey(id);
  const exists = await redis.exists(key);
  if (!exists) return undefined;
  const data = await redis.hmget(key, [
    "id",
    "title",
    "completed",
    "created_at",
    "updated_at",
  ]);
  return toTodoObject(data);
}

export async function createTodo({ title, completed = false }) {
  const id = await redis.incr(SEQ_KEY);
  const key = idKey(id);
  const now = nowISO();
  await redis.hmset(key, [
    "id", String(id),
    "title", String(title),
    "completed", completed ? "1" : "0",
    "created_at", now,
    "updated_at", now,
  ]);
  await redis.send("ZADD", [INDEX_KEY, String(id), String(id)]);
  return getTodo(id);
}

export async function updateTodo(id, patch) {
  const key = idKey(id);
  const exists = await redis.exists(key);
  if (!exists) return undefined;
  const now = nowISO();
  const updates = ["updated_at", now];
  if (typeof patch.title === "string") updates.push("title", patch.title.trim());
  if (typeof patch.completed === "boolean") updates.push("completed", patch.completed ? "1" : "0");
  if (updates.length > 0) {
    await redis.hmset(key, updates);
  }
  return getTodo(id);
}

export async function deleteTodo(id) {
  const key = idKey(id);
  const exists = await redis.exists(key);
  if (!exists) return false;
  await redis.del(key);
  await redis.send("ZREM", [INDEX_KEY, String(id)]);
  return true;
}

export async function seedTodos(rows) {
  const created = await Promise.all(
    rows.map((t) => createTodo({
      title: String(t.title || "Untitled"),
      completed: !!t.completed,
    }))
  );
  return created.length;
}
```

## Express Routes

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
router.post("/seed", async (req, res, next) => {
  try {
    const rows = Array.isArray(req.body) ? req.body : [];
    const count = await seedTodos(rows);
    res.json({ inserted: count });
  } catch (e) {
    next(e);
  }
});

// List
router.get("/todos", async (_req, res, next) => {
  try {
    const items = await listTodos();
    res.json(items);
  } catch (e) {
    next(e);
  }
});

// Get by id
router.get("/todos/:id", async (req, res, next) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
    const item = await getTodo(id);
    if (!item) return res.status(404).json({ error: "Not found" });
    res.json(item);
  } catch (e) {
    next(e);
  }
});

// Create
router.post("/todos", async (req, res, next) => {
  try {
    const title = typeof req.body?.title === "string" ? req.body.title.trim() : "";
    const completed = !!req.body?.completed;
    if (title.length === 0) return res.status(400).json({ error: "title is required" });
    const item = await createTodo({ title, completed });
    res.status(201).json(item);
  } catch (e) {
    next(e);
  }
});

// Update (partial)
router.patch("/todos/:id", async (req, res, next) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
    const patch = {};
    if (typeof req.body?.title === "string") patch.title = req.body.title.trim();
    if (typeof req.body?.completed === "boolean") patch.completed = req.body.completed;
    const item = await updateTodo(id, patch);
    if (!item) return res.status(404).json({ error: "Not found" });
    res.json(item);
  } catch (e) {
    next(e);
  }
});

// Delete
router.delete("/todos/:id", async (req, res, next) => {
  try {
    const id = Number(req.params.id);
    if (!Number.isInteger(id) || id < 1) return res.status(400).json({ error: "Invalid id" });
    const ok = await deleteTodo(id);
    if (!ok) return res.status(404).json({ error: "Not found" });
    res.status(204).end();
  } catch (e) {
    next(e);
  }
});
```

## Server

File: `src/server.js`

```javascript
// src/server.js
import express from "express";
import { router } from "./routes.js";
import { ensureRedis } from "./redis.js";

const app = express();

app.disable("x-powered-by");
app.use(express.json({ limit: "100kb" }));

// Simple request logger
app.use((req, res, next) => {
  const start = performance.now();
  res.on("finish", () => {
    const ms = (performance.now() - start).toFixed(1);
    console.log(`${req.method} ${req.originalUrl} ${res.statusCode} - ${ms}ms`);
  });
  next();
});

app.use("/api", router);

// 404
app.use((req, res) => {
  res.status(404).json({ error: "Not found" });
});

// Error handler
app.use((err, req, res, _next) => {
  console.error(err);
  res.status(500).json({ error: "Internal Server Error" });
});

const PORT = Number(process.env.PORT || 3000);

await ensureRedis();
app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});
```

## Run and Test

Start the server (hot reload optional):

```bash
# dev hot reload
bun --hot src/server.js

# or run once
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

# update
curl -s -X PATCH http://localhost:3000/api/todos/1 \
  -H 'content-type: application/json' \
  -d '{"completed":true}' | jq

# delete
curl -i -X DELETE http://localhost:3000/api/todos/1
```

## Caching and TTL (optional)

You can add expirations to records (e.g., ephemeral todos):

```javascript
// After createTodo gets the key
await redis.expire(key, 86400); // expire in 24h
```

Or add a cache layer for expensive reads using `GET/SET` with JSON.

## Error Handling

- Global error handler sends generic 500 with server log.
- Return 400/404 for invalid input or missing resources.
- Redis connection errors surface at startup via `ensureRedis()`.

## Performance Tips

- Keep a single Redis client per process; Bun auto‑pipelines commands.
- Use `Promise.all([...])` to parallelize independent reads for faster lists.
- Prefer sorted sets for ordered listings and pagination: `ZREVRANGE` with `LIMIT`.
- Consider `SCAN` only for admin/maintenance tools.

## Security Basics

- `app.disable("x-powered-by")` hides Express signature.
- Validate inputs; keep error messages generic.
- Use `REDIS_URL` with auth/TLS for production (e.g., `rediss://`).

Optional hardening:

```bash
bun add helmet cors express-rate-limit
```

```javascript
// in src/server.js, before routes
import helmet from "helmet";
import cors from "cors";
import rateLimit from "express-rate-limit";
app.use(helmet());
app.use(cors());
app.use(rateLimit({ windowMs: 60_000, max: 300 }));
```

## Troubleshooting

- “Cannot connect to Redis”: ensure Redis is running; check `REDIS_URL`.
- “Command error” details: check server logs; Bun raises typed errors.
- Data not listing: verify `todo:index` contains ids (ZADD/ZREM paths).

## Next Steps

- Add pagination to list: `ZREVRANGE todo:index start stop`.
- Add search by title with secondary indexes or a small in‑memory filter cache.
- Add sessions or rate limiting using Redis (see Bun Redis examples).
- Containerize with Docker or deploy to your preferred host with managed Redis.

---

Copy any block into your project and adapt names/paths as needed.
