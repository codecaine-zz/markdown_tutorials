# Bun + Express Stack Guide

## 🚀 Express + Bun Guides (quick links)

- Production API with views: [Express + Bun + MySQL + EJS](?page=programming%2Fjavascript%2Fframeworks%2Fexpress-bun-mysql-guide)
- Data store quickstarts:
  - [Express + Bun + MySQL](?page=programming%2Fjavascript%2Fframeworks%2Fexpress-bun-mysql-guide)
  - [Express + Bun + Redis](?page=programming%2Fjavascript%2Fframeworks%2Fexpress-bun-redis-guide)
  - [Express + Bun + SQLite](?page=programming%2Fjavascript%2Fframeworks%2Fexpress-bun-sqlite-guide)

---

## 🧩 Forms & AJAX Starters

Two tiny patterns you’ll use everywhere—full, working examples are in the MySQL+EJS guide.

Classic form (PRG):

- GET a form page (`/users/new`)
- POST form to `/users`
- Redirect back to list (`303 -> /users`)

 Minimal examples:

 ```html
 <!-- Classic form: POST then redirect -->
 <form method="post" action="/users">
   <input type="email" name="email" required />
   <input type="text" name="username" required />
   <button type="submit">Create</button>
   <!-- server handles redirect to /users on success (see route below) -->
 </form>
 ```

 ```ts
 // Express route (PRG)
 app.post('/users', async (req, res) => {
   // ...create user...
   res.redirect(303, '/users');
 });
 ```

 ```html
 <!-- Small AJAX: toggle action then reload -->
 <button id="deactivate">Deactivate</button>
 <script>
 document.getElementById('deactivate').addEventListener('click', async () => {
   const res = await fetch('/api/users/1/deactivate', { method: 'PATCH' });
   if (res.ok) location.reload();
 });
 </script>
 ```


See: [Express + Bun + MySQL + EJS](?page=programming%2Fjavascript%2Fframeworks%2Fexpress-bun-mysql-guide#ejs-views-server-rendered-ui) → “EJS Views (Server-Rendered UI)” section.

## 📂 PROJECT LAYOUT (copy‑paste)

```text
my‑api/
│
├─ src/
│   ├─ wrappers/          # ← already in the repo
│   │   ├─ mysqlwrapper.ts
│   │   ├─ rediswrapper.ts
│   │   └─ sqlitewrapper.ts
│   └─ server/
│       ├─ mysqlServer.ts
│       ├─ redisServer.ts
│       └─ sqliteServer.ts
├─ package.json
├─ tsconfig.json
└─ .env          # optional – see step 2

---

## ✅ ONE‑TIME SET‑UP (copy‑paste)

```bash
# 1️⃣ Install Bun (skip if you already have it)
curl -fsSL https://bun.sh/install | bash

# 2️⃣ Clone the wrapper repository
git clone https://github.com/codecaine-zz/bun_database_wrappers my-api
cd my-api

# 3️⃣ Install Express (the only extra dependency you need)
bun add express @types/express   # @types optional but helpful for TS

# 4️⃣ (optional) create an .env file – the examples also work with
#    the hard‑coded connection strings shown below.
cat > .env <<EOF
MYSQL_URL=mysql://root:your_password@localhost:3306/testdb
REDIS_URL=redis://localhost:6379
PORT=3000
EOF
```

---

## 1️⃣ MySQL + Express (`src/server/mysqlServer.ts`)

| Step | What you’re doing |
|------|-------------------|
| 1️⃣  | Initialise the **MySQLWrapper** (change the URL or use `process.env.MYSQL_URL`). |
| 2️⃣  | Create an Express app (`express.json()` middleware). |
| 3️⃣  | Implement CRUD endpoints for a `users` table. |
| 4️⃣  | Start the server on `PORT` (default 3000). |

### Full source – MySQL (copy‑paste)

```ts
// src/server/mysqlServer.ts
import express from "express";
import { MySQLWrapper } from "../wrappers/mysqlwrapper";

// ---------------------------------------------------
// 1️⃣ Initialise the wrapper (use .env or edit the URL)
// ---------------------------------------------------
const db = new MySQLWrapper(
  process.env.MYSQL_URL ??
    "mysql://root:your_password@localhost:3306/testdb"
);

// ---------------------------------------------------
// 2️⃣ Create Express app
// ---------------------------------------------------
const app = express();
app.use(express.json());

// ---------------------------------------------------
// 3️⃣ CRUD routes for a “users” table
// ---------------------------------------------------
app.get("/users", async (_req, res) => {
  const users = await db.all("SELECT * FROM users");
  res.json(users);
});

app.post("/users", async (req, res) => {
  const { name, email } = req.body;
  await db.run("INSERT INTO users (name, email) VALUES (?, ?)", [
    name,
    email,
  ]);
  res.status(201).json({ message: "User created" });
});

app.put("/users/:id", async (req, res) => {
  const { id } = req.params;
  const { name, email } = req.body;
  await db.run(
    "UPDATE users SET name = ?, email = ? WHERE id = ?",
    [name, email, id]
  );
  res.json({ message: "User updated" });
});

app.delete("/users/:id", async (req, res) => {
  const { id } = req.params;
  await db.run("DELETE FROM users WHERE id = ?", [id]);
  res.json({ message: "User deleted" });
});

// ---------------------------------------------------
// 4️⃣ Start the server
// ---------------------------------------------------
const PORT = process.env.PORT ? Number(process.env.PORT) : 3000;
app.listen(PORT, () => console.log(`MySQL API listening on ${PORT}`));
```

#### Run – MySQL

```bash
bun run src/server/mysqlServer.ts
```

> **Create the table once (MySQL shell or any client)**  

```sql
CREATE DATABASE IF NOT EXISTS testdb;
USE testdb;

CREATE TABLE IF NOT EXISTS users (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE
);
```

---

## 2️⃣ Redis + Express (`src/server/redisServer.ts`)

| Step | What you’re doing |
|------|-------------------|
| 1️⃣  | Connect with `RedisWrapper.connect()` (uses `REDIS_URL` or defaults). |
| 2️⃣  | Simple string key/value endpoints (`GET`, `POST`, `DELETE`). |
| 3️⃣  | JSON helper endpoints (`setJSON` / `getJSON`). |
| 4️⃣  | Launch the server (default port 3001). |

### Full source – Redis (copy‑paste)

```ts
// src/server/redisServer.ts
import express from "express";
import { RedisWrapper } from "../wrappers/rediswrapper";

const app = express();
app.use(express.json());

async function start() {
  // ---------------------------------------------------
  // 1️⃣ Connect to Redis
  // ---------------------------------------------------
  const redis = await RedisWrapper.connect(
    process.env.REDIS_URL ?? "redis://localhost:6379"
  );

  // ---------------------------------------------------
  // 2️⃣ Plain string API (GET /kv/:key, POST /kv/:key, DELETE /kv/:key)
  // ---------------------------------------------------
  app.get("/kv/:key", async (req, res) => {
    const value = await redis.get(req.params.key);
    if (value === null) return res.status(404).send("Not found");
    res.send(value);
  });

  app.post("/kv/:key", async (req, res) => {
    const { value } = req.body;
    await redis.set(req.params.key, value);
    res.status(201).send("OK");
  });

  app.delete("/kv/:key", async (req, res) => {
    await redis.del(req.params.key);
    res.send("Deleted");
  });

  // ---------------------------------------------------
  // 3️⃣ JSON helpers (store objects under `user:{id}`)
  // ---------------------------------------------------
  app.post("/users/:id", async (req, res) => {
    await redis.setJSON(`user:${req.params.id}`, req.body);
    res.status(201).send("User stored");
  });

  app.get("/users/:id", async (req, res) => {
    const user = await redis.getJSON(`user:${req.params.id}`);
    if (!user) return res.status(404).send("User not found");
    res.json(user);
  });

  // ---------------------------------------------------
  // 4️⃣ Start the server
  // ---------------------------------------------------
  const PORT = process.env.PORT ? Number(process.env.PORT) : 3001;
  app.listen(PORT, () => console.log(`Redis API listening on ${PORT}`));
}

// Run the async starter
start().catch((e) => {
  console.error("Failed to start Redis server:", e);
  process.exit(1);
});
```

#### Run – Redis

```bash
bun run src/server/redisServer.ts
```

---

## 3️⃣ SQLite + Express (`src/server/sqliteServer.ts`)

| Step | What you’re doing |
|------|-------------------|
| 1️⃣  | Initialise `SQLiteWrapper` (`:memory:` or a file path). |
| 2️⃣  | Create a `notes` table on startup. |
| 3️⃣  | CRUD routes for notes (`GET /notes`, `POST /notes`, `PUT /notes/:id`, `DELETE /notes/:id`). |
| 4️⃣  | Launch the server (default port 3002). |

### Full source – SQLite (copy‑paste)

```ts
// src/server/sqliteServer.ts
import express from "express";
import { SQLiteWrapper } from "../wrappers/sqlitewrapper";

const app = express();
app.use(express.json());

// ---------------------------------------------------
// 1️⃣ Initialise SQLite – change ":memory:" to "./data.db" for a file DB
// ---------------------------------------------------
const db = new SQLiteWrapper(":memory:");

// Create the notes table (runs once when the file is loaded)
await db.run(`
  CREATE TABLE IF NOT EXISTS notes (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    body  TEXT
  )
`);

// ---------------------------------------------------
// 2️⃣ CRUD routes
// ---------------------------------------------------
app.get("/notes", async (_req, res) => {
  const notes = await db.all("SELECT * FROM notes");
  res.json(notes);
});

app.post("/notes", async (req, res) => {
  const { title, body } = req.body;
  await db.run("INSERT INTO notes (title, body) VALUES (?, ?)", [
    title,
    body,
  ]);
  res.status(201).json({ message: "Note created" });
});

app.put("/notes/:id", async (req, res) => {
  const { id } = req.params;
  const { title, body } = req.body;
  await db.run(
    "UPDATE notes SET title = ?, body = ? WHERE id = ?",
    [title, body, id]
  );
  res.json({ message: "Note updated" });
});

app.delete("/notes/:id", async (req, res) => {
  const { id } = req.params;
  await db.run("DELETE FROM notes WHERE id = ?", [id]);
  res.json({ message: "Note deleted" });
});

// ---------------------------------------------------
// 3️⃣ Start the server
// ---------------------------------------------------
const PORT = process.env.PORT ? Number(process.env.PORT) : 3002;
app.listen(PORT, () => console.log(`SQLite API listening on ${PORT}`));
```

#### Run – SQLite

```bash
bun run src/server/sqliteServer.ts
```

*If you prefer a persistent file, replace the constructor line with:*  

```ts
const db = new SQLiteWrapper("./data.db");
```

---

## 📚 OFFICIAL REFERENCE LINKS (copy‑paste)

| Topic | URL |
|-------|-----|
| Bun – General documentation | [https://bun.com/docs/](https://bun.com/docs/) |
| Bun – SQL API (MySQL & SQLite) | [https://bun.com/reference/bun/SQL](https://bun.com/reference/bun/SQL) |
| Bun – MySQL driver (`adapter: "mysql"`) | [https://bun.com/reference/bun/SQL/PostgresOrMySQLOptions#adapter](https://bun.com/reference/bun/SQL/PostgresOrMySQLOptions#adapter) |
| Bun – SQLite driver (`adapter: "sqlite"`) | [https://bun.com/reference/bun/SQL/SQLiteOptions](https://bun.com/reference/bun/SQL/SQLiteOptions) |
| Bun – Redis client | [https://bun.com/reference/bun/RedisClient](https://bun.com/reference/bun/RedisClient) |
| Express JS (routing, middleware) | [https://expressjs.com/](https://expressjs.com/) |
| Your wrapper library (source) | [https://github.com/codecaine-zz/bun_database_wrappers](https://github.com/codecaine-zz/bun_database_wrappers) |

---

## Everyday Copy-and-Paste Express & Bun Production Recipes

```ts
// 1. Async Express Route Error Wrapper (Eliminates try-catch in routes)
import type { Request, Response, NextFunction, RequestHandler } from "express";

export const asyncHandler = (fn: RequestHandler): RequestHandler => 
  (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next);
  };

// Usage: app.get("/users", asyncHandler(async (req, res) => { ... }));

// 2. JWT Authentication Middleware
import jwt from "jsonwebtoken";

const JWT_SECRET = process.env.JWT_SECRET || "super-secret-key";

export function authenticateJwt(req: Request, res: Response, next: NextFunction) {
  const authHeader = req.headers.authorization;
  if (!authHeader?.startsWith("Bearer ")) {
    return res.status(401).json({ error: "Missing or invalid authorization header" });
  }

  const token = authHeader.substring(7);
  try {
    const payload = jwt.verify(token, JWT_SECRET);
    (req as any).user = payload;
    next();
  } catch (err) {
    res.status(403).json({ error: "Invalid or expired token" });
  }
}

// 3. Fast In-Memory Rate Limiter Middleware for Express
const rateLimitMap = new Map<string, { count: number; resetTime: number }>();

export function rateLimiter(maxRequests = 100, windowMs = 60000): RequestHandler {
  return (req: Request, res: Response, next: NextFunction) => {
    const ip = req.ip || "unknown";
    const now = Date.now();

    const record = rateLimitMap.get(ip) ?? { count: 0, resetTime: now + windowMs };

    if (now > record.resetTime) {
      record.count = 1;
      record.resetTime = now + windowMs;
    } else {
      record.count++;
    }

    rateLimitMap.set(ip, record);

    if (record.count > maxRequests) {
      return res.status(429).json({ error: "Too many requests. Please try again later." });
    }

    next();
  };
}
```

---

### 🎉 THAT’S IT

You now have three **copy‑and‑paste‑ready** Express servers that run on the ultra‑fast **Bun** runtime and use the `MySQLWrapper`, `RedisWrapper`, and `SQLiteWrapper` you published.  

*Pick the one you need, run the matching `bun run …` command, and start building your app.* Happy coding!

