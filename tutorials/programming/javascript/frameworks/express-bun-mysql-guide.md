# Express.js with Bun + MySQL + EJS — Production-Ready Tutorial (ARM Mac optimized)

Copy‑pasteable guide to build a robust, scalable REST API using Express.js on Bun with MySQL. Features production-ready database wrapper with type safety, connection pooling, and enterprise-grade error handling. Optimized for Apple Silicon (M1/M2/M3+) Macs.

## Table of Contents

- [Overview](#overview)
- [Why Bun + MySQL + Database Wrapper](#why-bun--mysql--database-wrapper)
- [Prerequisites (ARM Mac)](#prerequisites-arm-mac)
- [Project Setup](#project-setup)
- [App Structure](#app-structure)
- [Database (Using MySQL Wrapper)](#database-using-mysql-wrapper)
- [Repository (Type-Safe Data Access)](#repository-type-safe-data-access)
- [Express App](#express-app)
- [EJS Views (Server-Rendered UI)](#ejs-views-server-rendered-ui)
- [CRUD Routes (User Management API)](#crud-routes-user-management-api)
- [Advanced Features](#advanced-features)
- [Run and Test](#run-and-test)
- [Error Handling](#error-handling)
- [Performance Tips](#performance-tips)
- [Security Basics](#security-basics)
- [Troubleshooting](#troubleshooting)
- [Next Steps](#next-steps)

---

## Overview

We'll build a production-ready REST API for user management:

- Runtime: Bun (fast, single binary, Node compatible)
- Web framework: Express.js
- Database: MySQL via production-ready database wrapper
- Storage: MySQL server with connection pooling and optimized configuration
- Features: Type-safe CRUD, transactions, bulk operations, automatic resource management, comprehensive error handling

All code blocks are complete and copy‑pasteable.

## Why Bun + MySQL + Database Wrapper

- Bun is fast and compatible with Node.js ecosystems, with built-in MySQL support
- MySQL provides ACID compliance, complex relationships, and excellent performance for production workloads
- Database wrapper provides type safety, automatic resource management, connection pooling, and built-in security
- Connection pooling handles high concurrent loads efficiently
- Automatic cleanup prevents memory leaks and connection exhaustion
- Built-in transaction support ensures data integrity

## Prerequisites (ARM Mac)

- macOS on Apple Silicon (M1/M2/M3)
- Bun installed
- MySQL server running

```bash
# Install Bun
curl -fsSL https://bun.sh/install | bash
# restart terminal or: source ~/.zshrc
bun --version

# Install MySQL via Homebrew
brew install mysql
brew services start mysql

# Secure MySQL installation (optional but recommended)
mysql_secure_installation
```

## Project Setup

```bash
mkdir express-bun-mysql && cd $_
bun init -y
bun add express
bun add ejs
bun add https://github.com/codecaine-zz/bun_database_wrappers
```

This creates `package.json`, installs Express, and adds the production-ready database wrappers with MySQL support.

## App Structure

```text
.
├─ src/
│  ├─ config/
│  │  └─ database.js     # Database configuration and connection
│  ├─ db.js              # MySQL wrapper setup and initialization
│  ├─ repo.js            # Type-safe data access layer (user repository)
│  ├─ routes.js          # Express routes with async/await support
│  ├─ web-routes.js      # EJS-rendered pages (server-rendered UI)
│  └─ views/             # EJS templates
│     ├─ index.ejs
│     ├─ users/
│     │  └─ list.ejs
│     └─ partials/
│        ├─ header.ejs
│        └─ footer.ejs
│  └─ server.js          # Express app + middleware + database initialization
├─ .env                  # Environment variables
└─ data/
   └─ schema.sql        # Database schema (for reference)
```

## Database (Using MySQL Wrapper)

First, create the environment configuration:

File: `.env`

```bash
# MySQL Configuration
MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASSWORD=your_password
MYSQL_DATABASE=express_demo

# Application Configuration
PORT=3000
NODE_ENV=development
```

File: `src/config/database.js`

```javascript
// src/config/database.js
export const dbConfig = {
  host: process.env.MYSQL_HOST || 'localhost',
  port: parseInt(process.env.MYSQL_PORT) || 3306,
  user: process.env.MYSQL_USER || 'root',
  password: process.env.MYSQL_PASSWORD || '',
  database: process.env.MYSQL_DATABASE || 'express_demo',
};

export const connectionString = `mysql://${dbConfig.user}:${dbConfig.password}@${dbConfig.host}:${dbConfig.port}/${dbConfig.database}`;
```

File: `src/db.js`

```javascript
// src/db.js
import { createMySQL } from "bun_database_wrappers";
import { connectionString } from "./config/database.js";

// Create MySQL instance with automatic resource management
export const db = createMySQL(connectionString);

// Initialize database with schema
export async function initializeDatabase() {
  try {
    // Create database if it doesn't exist
    await db.query("CREATE DATABASE IF NOT EXISTS ?? CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci", [process.env.MYSQL_DATABASE || 'express_demo']);
    
    // Use the database
    await db.query("USE ??", [process.env.MYSQL_DATABASE || 'express_demo']);
    
    // Create users table with comprehensive fields
    await db.createTable("users", {
      id: "INT AUTO_INCREMENT PRIMARY KEY",
      email: "VARCHAR(255) UNIQUE NOT NULL",
      username: "VARCHAR(100) UNIQUE NOT NULL",
      first_name: "VARCHAR(100) NOT NULL",
      last_name: "VARCHAR(100) NOT NULL",
      password_hash: "VARCHAR(255) NOT NULL",
      is_active: "BOOLEAN DEFAULT TRUE",
      role: "ENUM('user', 'admin', 'moderator') DEFAULT 'user'",
      created_at: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
      updated_at: "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    });

    // Create indexes for better query performance
    // Guard against duplicate index errors on re-run (MySQL doesn't support IF NOT EXISTS here)
    const createIndex = async (sql) => {
      try { await db.query(sql); } catch (err) {
        if (err?.cause?.code !== 'ER_DUP_KEYNAME') throw err;
      }
    };
    await createIndex("CREATE INDEX idx_users_email ON users(email)");
    await createIndex("CREATE INDEX idx_users_username ON users(username)");
    await createIndex("CREATE INDEX idx_users_active ON users(is_active)");

    console.log("Database initialized successfully");
  } catch (error) {
    console.error("Database initialization failed:", error);
    throw error;
  }
}
```

File: `data/schema.sql` (for reference)

```sql
-- Reference schema for the users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  username VARCHAR(100) UNIQUE NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  role ENUM('user', 'admin', 'moderator') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_active ON users(is_active);
```

## Repository (Type-Safe Data Access)

File: `src/repo.js`

```javascript
// src/repo.js
import { db } from "./db.js";

// TypeScript-style interface for documentation (JSDoc)
/**
 * @typedef {Object} User
 * @property {number} [id] - Auto-generated ID
 * @property {string} email - User email (unique)
 * @property {string} username - Username (unique)
 * @property {string} first_name - First name
 * @property {string} last_name - Last name
 * @property {string} password_hash - Hashed password
 * @property {boolean} is_active - Whether user is active
 * @property {'user'|'admin'|'moderator'} role - User role
 * @property {Date} [created_at] - Creation timestamp
 * @property {Date} [updated_at] - Last update timestamp
 */

/**
 * @typedef {Object} CreateUserData
 * @property {string} email
 * @property {string} username
 * @property {string} first_name
 * @property {string} last_name
 * @property {string} password_hash
 * @property {'user'|'admin'|'moderator'} [role]
 */

export async function listUsers(filters = {}) {
  const { role, isActive, limit = 50, offset = 0 } = filters;
  
  let whereClause = "WHERE 1=1";
  const params = [];
  
  if (role) {
    whereClause += " AND role = ?";
    params.push(role);
  }
  
  if (typeof isActive === 'boolean') {
    whereClause += " AND is_active = ?";
    params.push(isActive);
  }
  
  return await db.all(`
    SELECT id, email, username, first_name, last_name, role, is_active, created_at, updated_at
    FROM users 
    ${whereClause}
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
  `, [...params, limit, offset]);
}

export async function getUserById(id) {
  return await db.get(`
    SELECT id, email, username, first_name, last_name, role, is_active, created_at, updated_at
    FROM users 
    WHERE id = ?
  `, [id]);
}

export async function getUserByEmail(email) {
  return await db.get(`
    SELECT id, email, username, first_name, last_name, role, is_active, created_at, updated_at
    FROM users 
    WHERE email = ?
  `, [email]);
}

export async function getUserByUsername(username) {
  return await db.get(`
    SELECT id, email, username, first_name, last_name, role, is_active, created_at, updated_at
    FROM users 
    WHERE username = ?
  `, [username]);
}

export async function createUser(userData) {
  const { email, username, first_name, last_name, password_hash, role = 'user' } = userData;
  
  const result = await db.insert("users", {
    email,
    username,
    first_name,
    last_name,
    password_hash,
    role
  });
  // MySQL returns insertId for the newly inserted row
  return await getUserById(Number(result.insertId));
}

export async function updateUser(id, userData) {
  const current = await getUserById(id);
  if (!current) return null;
  
  // Filter out undefined values and id
  const updateData = {};
  const allowedFields = ['email', 'username', 'first_name', 'last_name', 'password_hash', 'role', 'is_active'];
  
  for (const field of allowedFields) {
    if (userData[field] !== undefined) {
      updateData[field] = userData[field];
    }
  }
  
  if (Object.keys(updateData).length === 0) {
    return current; // No changes
  }
  
  await db.update("users", updateData, "id = ?", [id]);
  return await getUserById(id);
}

export async function deleteUser(id) {
  const user = await getUserById(id);
  if (!user) return false;
  
  await db.delete("users", "id = ?", [id]);
  return true;
}

export async function deactivateUser(id) {
  return await updateUser(id, { is_active: false });
}

export async function activateUser(id) {
  return await updateUser(id, { is_active: true });
}

export async function getUsersCount(filters = {}) {
  const { role, isActive } = filters;
  
  let whereClause = "WHERE 1=1";
  const params = [];
  
  if (role) {
    whereClause += " AND role = ?";
    params.push(role);
  }
  
  if (typeof isActive === 'boolean') {
    whereClause += " AND is_active = ?";
    params.push(isActive);
  }
  
  const result = await db.scalar(`SELECT COUNT(*) FROM users ${whereClause}`, params);
  return Number(result) || 0;
}

export async function bulkCreateUsers(usersData) {
  // Use transaction for bulk operations
  const queries = usersData.map(userData => ({
    sql: "INSERT INTO users (email, username, first_name, last_name, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)",
    params: [
      userData.email,
      userData.username,
      userData.first_name,
      userData.last_name,
      userData.password_hash,
      userData.role || 'user'
    ]
  }));
  
  await db.transaction(queries);
  return usersData.length;
}

export async function searchUsers(searchTerm, limit = 20) {
  const term = `%${searchTerm}%`;
  return await db.all(`
    SELECT id, email, username, first_name, last_name, role, is_active, created_at, updated_at
    FROM users 
    WHERE (email LIKE ? OR username LIKE ? OR first_name LIKE ? OR last_name LIKE ?)
    AND is_active = TRUE
    ORDER BY created_at DESC
    LIMIT ?
  `, [term, term, term, term, limit]);
}
```

## Express App

File: `src/server.js`

```javascript
// src/server.js
import express from "express";
import { router } from "./routes.js";
import { initializeDatabase } from "./db.js";
import path from "path";
import { fileURLToPath } from "url";
import { webRouter } from "./web-routes.js";

const app = express();

// Security and performance middlewares
app.disable("x-powered-by");
app.use(express.json({ limit: "1mb" }));
app.use(express.urlencoded({ extended: true, limit: "1mb" }));

// Configure EJS view engine
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));

// Request logging middleware
app.use((req, res, next) => {
  const start = performance.now();
  const timestamp = new Date().toISOString();
  
  res.on("finish", () => {
    const duration = (performance.now() - start).toFixed(1);
    const { method, originalUrl } = req;
    const { statusCode } = res;
    
    console.log(`[${timestamp}] ${method} ${originalUrl} ${statusCode} - ${duration}ms`);
  });
  
  next();
});

// CORS middleware (for development)
app.use((req, res, next) => {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Methods", "GET,HEAD,PUT,PATCH,POST,DELETE");
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept, Authorization");
  
  if (req.method === "OPTIONS") {
    res.status(204).end();
    return;
  }
  
  next();
});

// Health check endpoint
app.get("/health", (req, res) => {
  res.json({ 
    status: "healthy", 
    timestamp: new Date().toISOString(),
    uptime: process.uptime(),
    memory: process.memoryUsage()
  });
});

// API routes
app.use("/api", router);

// Web routes (EJS-rendered pages)
app.use("/", webRouter);

// 404 handler
app.use((req, res) => {
  res.status(404).json({ 
    error: "Not found",
    message: `Route ${req.method} ${req.originalUrl} not found`,
    timestamp: new Date().toISOString()
  });
});

// Global error handler
app.use((err, req, res, next) => {
  console.error("Global error handler:", err);
  
  // Don't leak error details in production
  const isDevelopment = process.env.NODE_ENV === 'development';
  
  res.status(err.status || 500).json({
    error: "Internal Server Error",
    message: isDevelopment ? err.message : "An unexpected error occurred",
    timestamp: new Date().toISOString(),
    ...(isDevelopment && { stack: err.stack })
  });
});

const PORT = Number(process.env.PORT || 3000);

// Initialize database and start server
async function startServer() {
  try {
    console.log("Initializing database...");
    await initializeDatabase();
    
    app.listen(PORT, () => {
      console.log(`🚀 Server running on http://localhost:${PORT}`);
      console.log(`📊 Health check: http://localhost:${PORT}/health`);
      console.log(`🔧 Environment: ${process.env.NODE_ENV || 'development'}`);
    });
  } catch (error) {
    console.error("Failed to start server:", error);
    process.exit(1);
  }
}

// Graceful shutdown
process.on('SIGINT', async () => {
  console.log('Received SIGINT, shutting down gracefully...');
  process.exit(0);
});

process.on('SIGTERM', async () => {
  console.log('Received SIGTERM, shutting down gracefully...');
  process.exit(0);
});

startServer();
```

## EJS Views (Server-Rendered UI)

Add a lightweight server-rendered UI using EJS to quickly browse users in the browser.

Install dependency (already included above):

```bash
bun add ejs
```

File: `src/web-routes.js`

```javascript
// src/web-routes.js
import { Router } from "express";
import { listUsers, getUsersCount, getUserByEmail, getUserByUsername, createUser } from "./repo.js";

export const webRouter = Router();

// Home page
webRouter.get("/", (req, res) => {
  res.render("index", { title: "Express + Bun + MySQL", year: new Date().getFullYear() });
});

// User list page with basic pagination
webRouter.get("/users", async (req, res) => {
  const page = parseInt(req.query.page || "1");
  const limit = 20;
  const offset = (page - 1) * limit;
  const filters = { limit, offset };

  try {
    const [users, total] = await Promise.all([
      listUsers(filters),
      getUsersCount(filters)
    ]);

    res.render("users/list", {
      title: "Users",
      users,
      page,
      pages: Math.max(1, Math.ceil(total / limit)),
      total
    });
  } catch (err) {
    console.error("Render users error:", err);
    res.status(500).send("Failed to load users");
  }
});

// Render "new user" form (classic navigation)
webRouter.get("/users/new", (_req, res) => {
  res.render("users/new", { title: "Create user" });
});

// Handle classic HTML form submit and redirect (PRG pattern)
webRouter.post("/users", async (req, res) => {
  const { email, username, first_name, last_name, password, role = "user" } = req.body;

  try {
    // basic validation + conflict checks (keep it simple here)
    if (!email || !username || !first_name || !last_name || !password) {
      return res.status(400).render("users/new", { title: "Create user", error: "All fields are required" });
    }
    if (await getUserByEmail(email)) {
      return res.status(409).render("users/new", { title: "Create user", error: "Email already in use" });
    }
    if (await getUserByUsername(username)) {
      return res.status(409).render("users/new", { title: "Create user", error: "Username already in use" });
    }

    // tiny demo hash (use bcrypt in production)
    const password_hash = Buffer.from(password + "salt123").toString("base64");
    await createUser({ email, username, first_name, last_name, password_hash, role });

    // Post/Redirect/Get back to list
    res.redirect(303, "/users");
  } catch (err) {
    console.error("Form submit error:", err);
    res.status(500).render("users/new", { title: "Create user", error: "Failed to create user" });
  }
});
```

Create views directory and templates:

File: `src/views/partials/header.ejs`

```ejs
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><%= title %></title>
    <style>
      body { font-family: -apple-system, system-ui, sans-serif; margin: 2rem; }
      nav a { margin-right: 1rem; }
      table { border-collapse: collapse; width: 100%; }
      th, td { border: 1px solid #ddd; padding: 8px; }
      th { background: #f7f7f7; text-align: left; }
    </style>
  </head>
  <body>
    <nav>
      <a href="/">Home</a>
      <a href="/users">Users</a>
      <a href="/health" target="_blank">Health JSON</a>
    </nav>
    <hr />
```

File: `src/views/partials/footer.ejs`

```ejs
    <hr />
    <footer>
      <small>&copy; <%= new Date().getFullYear() %> Express + Bun + MySQL</small>
    </footer>
  </body>
</html>
```

File: `src/views/index.ejs`

```ejs
<%- include('partials/header', { title }) %>
  <h1><%= title %></h1>
  <p>Welcome! Explore the REST API under <code>/api</code> or view users under <a href="/users">/users</a>.</p>
  <ul>
    <li>API: <code>GET /api/users</code></li>
    <li>API: <code>POST /api/users</code></li>
    <li>Web: <a href="/users">User list</a></li>
  </ul>
<%- include('partials/footer') %>
```

File: `src/views/users/list.ejs`

```ejs
<%- include('../partials/header', { title }) %>
  <h1>Users</h1>
  <p><a href="/users/new">➕ Add user</a></p>
  <p>Total: <%= total %></p>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Username</th>
        <th>Role</th>
        <th>Active</th>
        <th>Actions</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <% if (users.length === 0) { %>
        <tr><td colspan="7">No users yet</td></tr>
      <% } else { %>
        <% users.forEach(u => { %>
          <tr>
            <td><%= u.id %></td>
            <td><%= u.email %></td>
            <td><%= u.username %></td>
            <td><%= u.role %></td>
            <td><%= u.is_active ? 'yes' : 'no' %></td>
            <td>
              <% if (u.is_active) { %>
                <button class="toggle" data-id="<%= u.id %>" data-mode="deactivate">Deactivate</button>
              <% } else { %>
                <button class="toggle" data-id="<%= u.id %>" data-mode="activate">Activate</button>
              <% } %>
            </td>
            <td><%= new Date(u.created_at).toLocaleString() %></td>
          </tr>
        <% }) %>
      <% } %>
    </tbody>
  </table>

  <p>
    Page <%= page %> of <%= pages %>
    <% if (page > 1) { %>
      <a href="/users?page=<%= page - 1 %>">&laquo; Prev</a>
    <% } %>
    <% if (page < pages) { %>
      <a href="/users?page=<%= page + 1 %>">Next &raquo;</a>
    <% } %>
  </p>

  <script>
    // Small AJAX example: toggle activation
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('button.toggle');
      if (!btn) return;
      const id = btn.dataset.id;
      const mode = btn.dataset.mode; // 'activate' | 'deactivate'
      try {
        const res = await fetch(`/api/users/${id}/${mode}`, { method: 'PATCH' });
        if (!res.ok) throw new Error('Request failed');
        // naïve approach: reload to reflect changes
        location.reload();
      } catch (err) {
        alert('Failed to update user');
      }
    });
  </script>
<%- include('../partials/footer') %>
```

File: `src/views/users/new.ejs`

```ejs
<%- include('../partials/header', { title }) %>
  <h1>Create user</h1>
  <% if (typeof error === 'string') { %>
    <p style="color:#b00"><%= error %></p>
  <% } %>
  <form method="post" action="/users">
    <p>
      <label>Email<br>
        <input type="email" name="email" required>
      </label>
    </p>
    <p>
      <label>Username<br>
        <input type="text" name="username" required>
      </label>
    </p>
    <p>
      <label>First name<br>
        <input type="text" name="first_name" required>
      </label>
    </p>
    <p>
      <label>Last name<br>
        <input type="text" name="last_name" required>
      </label>
    </p>
    <p>
      <label>Password<br>
        <input type="password" name="password" minlength="6" required>
      </label>
    </p>
    <p>
      <label>Role<br>
        <select name="role">
          <option value="user" selected>User</option>
          <option value="admin">Admin</option>
          <option value="moderator">Moderator</option>
        </select>
      </label>
    </p>
    <p>
      <button type="submit">Create</button>
      <a href="/users">Cancel</a>
    </p>
  </form>
<%- include('../partials/footer') %>
```

Notes:

- The form POST uses the PRG pattern and redirects to `/users` on success.
- The AJAX example is intentionally tiny; for production, add CSRF protection and show inline errors.

## CRUD Routes (User Management API)

File: `src/routes.js`

```javascript
// src/routes.js
import { Router } from "express";
import {
  listUsers,
  getUserById,
  getUserByEmail,
  getUserByUsername,
  createUser,
  updateUser,
  deleteUser,
  deactivateUser,
  activateUser,
  getUsersCount,
  bulkCreateUsers,
  searchUsers,
} from "./repo.js";

export const router = Router();

// Utility function to hash passwords (in production, use bcrypt)
function hashPassword(password) {
  // This is a simple example - use bcrypt in production!
  return Buffer.from(password + "salt123").toString("base64");
}

// Validation middleware
function validateUserData(req, res, next) {
  const { email, username, first_name, last_name, password } = req.body;
  const errors = [];
  
  if (!email || !email.includes('@')) {
    errors.push("Valid email is required");
  }
  
  if (!username || username.length < 3) {
    errors.push("Username must be at least 3 characters");
  }
  
  if (!first_name || first_name.trim().length === 0) {
    errors.push("First name is required");
  }
  
  if (!last_name || last_name.trim().length === 0) {
    errors.push("Last name is required");
  }
  
  if (req.method === 'POST' && (!password || password.length < 6)) {
    errors.push("Password must be at least 6 characters");
  }
  
  if (errors.length > 0) {
    return res.status(400).json({ 
      error: "Validation failed", 
      details: errors,
      timestamp: new Date().toISOString()
    });
  }
  
  next();
}

// List users with filtering and pagination
router.get("/users", async (req, res) => {
  try {
    const { role, active, page = 1, limit = 50, search } = req.query;
    const offset = (parseInt(page) - 1) * parseInt(limit);
    
    let users;
    let total;
    
    if (search) {
      users = await searchUsers(search, parseInt(limit));
      total = users.length;
    } else {
      const filters = {
        role: role || undefined,
        isActive: active !== undefined ? active === 'true' : undefined,
        limit: parseInt(limit),
        offset
      };
      
      users = await listUsers(filters);
      total = await getUsersCount(filters);
    }
    
    res.json({
      users,
      pagination: {
        page: parseInt(page),
        limit: parseInt(limit),
        total,
        pages: Math.ceil(total / parseInt(limit))
      },
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("List users error:", error);
    res.status(500).json({ 
      error: "Failed to fetch users",
      timestamp: new Date().toISOString()
    });
  }
});

// Get user by ID
router.get("/users/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ 
        error: "Invalid user ID",
        timestamp: new Date().toISOString()
      });
    }
    
    const user = await getUserById(id);
    if (!user) {
      return res.status(404).json({ 
        error: "User not found",
        timestamp: new Date().toISOString()
      });
    }
    
    res.json({ user, timestamp: new Date().toISOString() });
  } catch (error) {
    console.error("Get user error:", error);
    res.status(500).json({ 
      error: "Failed to fetch user",
      timestamp: new Date().toISOString()
    });
  }
});

// Create new user
router.post("/users", validateUserData, async (req, res) => {
  try {
    const { email, username, first_name, last_name, password, role = 'user' } = req.body;
    
    // Check for existing email
    const existingEmail = await getUserByEmail(email);
    if (existingEmail) {
      return res.status(409).json({ 
        error: "Email already exists",
        timestamp: new Date().toISOString()
      });
    }
    
    // Check for existing username
    const existingUsername = await getUserByUsername(username);
    if (existingUsername) {
      return res.status(409).json({ 
        error: "Username already exists",
        timestamp: new Date().toISOString()
      });
    }
    
    const userData = {
      email: email.toLowerCase().trim(),
      username: username.trim(),
      first_name: first_name.trim(),
      last_name: last_name.trim(),
      password_hash: hashPassword(password),
      role
    };
    
    const user = await createUser(userData);
    res.status(201).json({ 
      user, 
      message: "User created successfully",
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Create user error:", error);
    res.status(500).json({ 
      error: "Failed to create user",
      timestamp: new Date().toISOString()
    });
  }
});

// Update user
router.patch("/users/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ 
        error: "Invalid user ID",
        timestamp: new Date().toISOString()
      });
    }
    
    const updateData = {};
    const allowedFields = ['email', 'username', 'first_name', 'last_name', 'role', 'is_active'];
    
    for (const field of allowedFields) {
      if (req.body[field] !== undefined) {
        if (field === 'email') {
          updateData[field] = req.body[field].toLowerCase().trim();
        } else if (typeof req.body[field] === 'string') {
          updateData[field] = req.body[field].trim();
        } else {
          updateData[field] = req.body[field];
        }
      }
    }
    
    // Handle password update separately
    if (req.body.password) {
      if (req.body.password.length < 6) {
        return res.status(400).json({ 
          error: "Password must be at least 6 characters",
          timestamp: new Date().toISOString()
        });
      }
      updateData.password_hash = hashPassword(req.body.password);
    }
    
    // Check for conflicts if email or username is being updated
    if (updateData.email) {
      const existing = await getUserByEmail(updateData.email);
      if (existing && existing.id !== id) {
        return res.status(409).json({ 
          error: "Email already exists",
          timestamp: new Date().toISOString()
        });
      }
    }
    
    if (updateData.username) {
      const existing = await getUserByUsername(updateData.username);
      if (existing && existing.id !== id) {
        return res.status(409).json({ 
          error: "Username already exists",
          timestamp: new Date().toISOString()
        });
      }
    }
    
    const user = await updateUser(id, updateData);
    if (!user) {
      return res.status(404).json({ 
        error: "User not found",
        timestamp: new Date().toISOString()
      });
    }
    
    res.json({ 
      user, 
      message: "User updated successfully",
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Update user error:", error);
    res.status(500).json({ 
      error: "Failed to update user",
      timestamp: new Date().toISOString()
    });
  }
});

// Delete user
router.delete("/users/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ 
        error: "Invalid user ID",
        timestamp: new Date().toISOString()
      });
    }
    
    const success = await deleteUser(id);
    if (!success) {
      return res.status(404).json({ 
        error: "User not found",
        timestamp: new Date().toISOString()
      });
    }
    
    res.status(204).end();
  } catch (error) {
    console.error("Delete user error:", error);
    res.status(500).json({ 
      error: "Failed to delete user",
      timestamp: new Date().toISOString()
    });
  }
});

// Deactivate user (soft delete)
router.patch("/users/:id/deactivate", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ 
        error: "Invalid user ID",
        timestamp: new Date().toISOString()
      });
    }
    
    const user = await deactivateUser(id);
    if (!user) {
      return res.status(404).json({ 
        error: "User not found",
        timestamp: new Date().toISOString()
      });
    }
    
    res.json({ 
      user, 
      message: "User deactivated successfully",
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Deactivate user error:", error);
    res.status(500).json({ 
      error: "Failed to deactivate user",
      timestamp: new Date().toISOString()
    });
  }
});

// Activate user
router.patch("/users/:id/activate", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    if (!Number.isInteger(id) || id < 1) {
      return res.status(400).json({ 
        error: "Invalid user ID",
        timestamp: new Date().toISOString()
      });
    }
    
    const user = await activateUser(id);
    if (!user) {
      return res.status(404).json({ 
        error: "User not found",
        timestamp: new Date().toISOString()
      });
    }
    
    res.json({ 
      user, 
      message: "User activated successfully",
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Activate user error:", error);
    res.status(500).json({ 
      error: "Failed to activate user",
      timestamp: new Date().toISOString()
    });
  }
});

// Bulk create users (admin endpoint)
router.post("/users/bulk", async (req, res) => {
  try {
    const { users } = req.body;
    
    if (!Array.isArray(users) || users.length === 0) {
      return res.status(400).json({ 
        error: "Users array is required",
        timestamp: new Date().toISOString()
      });
    }
    
    // Validate each user
    const validatedUsers = users.map((user, index) => {
      const { email, username, first_name, last_name, password, role = 'user' } = user;
      
      if (!email || !username || !first_name || !last_name || !password) {
        throw new Error(`User at index ${index} is missing required fields`);
      }
      
      return {
        email: email.toLowerCase().trim(),
        username: username.trim(),
        first_name: first_name.trim(),
        last_name: last_name.trim(),
        password_hash: hashPassword(password),
        role
      };
    });
    
    const count = await bulkCreateUsers(validatedUsers);
    res.status(201).json({ 
      message: `${count} users created successfully`,
      count,
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Bulk create users error:", error);
    res.status(500).json({ 
      error: error.message || "Failed to create users",
      timestamp: new Date().toISOString()
    });
  }
});

// Search users
router.get("/search/users", async (req, res) => {
  try {
    const { q, limit = 20 } = req.query;
    
    if (!q || q.trim().length === 0) {
      return res.status(400).json({ 
        error: "Search query is required",
        timestamp: new Date().toISOString()
      });
    }
    
    const users = await searchUsers(q.trim(), parseInt(limit));
    res.json({ 
      users, 
      query: q.trim(),
      count: users.length,
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error("Search users error:", error);
    res.status(500).json({ 
      error: "Failed to search users",
      timestamp: new Date().toISOString()
    });
  }
});
```

## Advanced Features

### Pagination Helper

```javascript
// src/utils/pagination.js
export function createPaginationResponse(data, page, limit, total) {
  return {
    data,
    pagination: {
      page: parseInt(page),
      limit: parseInt(limit),
      total,
      pages: Math.ceil(total / parseInt(limit)),
      hasNext: page * limit < total,
      hasPrev: page > 1
    }
  };
}
```

### Database Migrations

```javascript
// src/migrations/001_create_users_table.js
export async function up(db) {
  await db.query(`
    CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(255) UNIQUE NOT NULL,
      username VARCHAR(100) UNIQUE NOT NULL,
      first_name VARCHAR(100) NOT NULL,
      last_name VARCHAR(100) NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      is_active BOOLEAN DEFAULT TRUE,
      role ENUM('user', 'admin', 'moderator') DEFAULT 'user',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
  `);
  
  await db.query(`
    CREATE INDEX idx_users_email ON users(email);
    CREATE INDEX idx_users_username ON users(username);
    CREATE INDEX idx_users_active ON users(is_active);
  `);
}

export async function down(db) {
  await db.query("DROP TABLE IF EXISTS users");
}
```

## Run and Test

Start the MySQL server and create database:

```bash
# Start MySQL service
brew services start mysql

# Create database
mysql -u root -p -e "CREATE DATABASE express_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Start the server:

```bash
# Development with hot reload
bun --hot src/server.js

# Production
bun src/server.js
```

Test with curl:

```bash
# Health check
curl -s http://localhost:3000/health | jq

# Create a user
curl -s -X POST http://localhost:3000/api/users \
  -H 'Content-Type: application/json' \
  -d '{
    "email": "alice@example.com",
    "username": "alice",
    "first_name": "Alice",
    "last_name": "Johnson",
    "password": "secret123",
    "role": "user"
  }' | jq

# List users
curl -s http://localhost:3000/api/users | jq

# Get user by ID
curl -s http://localhost:3000/api/users/1 | jq

# Update user
curl -s -X PATCH http://localhost:3000/api/users/1 \
  -H 'Content-Type: application/json' \
  -d '{"first_name": "Alicia"}' | jq

# Search users
curl -s "http://localhost:3000/api/search/users?q=alice" | jq

# Deactivate user
curl -s -X PATCH http://localhost:3000/api/users/1/deactivate | jq

# Delete user
curl -i -X DELETE http://localhost:3000/api/users/1
```

Also try the EJS-rendered pages in your browser:

- Home: [http://localhost:3000/](http://localhost:3000/)
- Users list: [http://localhost:3000/users](http://localhost:3000/users)

## Error Handling

The MySQL wrapper provides comprehensive error handling:

```javascript
import { DBError } from "bun_database_wrappers";

try {
  await db.insert("users", userData);
} catch (error) {
  if (error instanceof DBError) {
    console.log("Query:", error.context.query);
    console.log("Params:", error.context.params);
    console.log("Cause:", error.cause);
    
    // Handle specific MySQL errors
    if (error.cause?.code === 'ER_DUP_ENTRY') {
      // Handle duplicate key error
      return res.status(409).json({ error: "Duplicate entry" });
    }
  }
}
```

## Performance Tips

- MySQL wrapper automatically manages connection pooling
- Uses prepared statements for optimal performance and security
- Built-in transaction support for atomic operations
- Indexes are created for commonly queried fields
- Pagination prevents large result sets from overwhelming memory
- Connection pooling handles concurrent requests efficiently

Example performance optimization:

```javascript
// Batch operations for better performance
export async function bulkUpdateUserRoles(userIds, newRole) {
  const queries = userIds.map(id => ({
    sql: "UPDATE users SET role = ? WHERE id = ?",
    params: [newRole, id]
  }));
  
  await db.transaction(queries);
  return userIds.length;
}
```

## Security Basics

- Input validation prevents malicious data
- SQL injection protection through parameterized queries
- Password hashing (use bcrypt in production)
- Role-based access control
- Request size limits to prevent DoS attacks

Production security enhancements:

```bash
# Add bcrypt for password hashing
bun add bcrypt

# Add rate limiting
bun add express-rate-limit

# Add security headers
bun add helmet
```

## Troubleshooting

- "Connection refused": Ensure MySQL server is running (`brew services start mysql`)
- "Database not found": Create the database manually or ensure proper permissions
- "Module not found": Run `bun add https://github.com/codecaine-zz/bun_database_wrappers`
- "Duplicate entry": Handle unique constraint violations in your error handling
- "Too many connections": Adjust MySQL's `max_connections` setting
- Connection timeout: Check MySQL server status and network connectivity

## Next Steps

- Add JWT authentication and authorization middleware
- Implement comprehensive logging with structured logs
- Add input validation with [Zod](https://github.com/colinhacks/zod)
- Set up database migrations system
- Add comprehensive test suite with `bun test`
- Implement caching layer with Redis
- Add monitoring and health checks
- Set up CI/CD pipeline for deployment
- Add API documentation with OpenAPI/Swagger

---

Copy any block into your project and adapt as needed for your specific requirements.
