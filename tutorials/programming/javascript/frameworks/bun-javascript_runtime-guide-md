# Complete Bun Tutorial for Beginners

Bun is a fast all-in-one JavaScript runtime that includes a bundler, test runner, and package manager. This tutorial covers everything you need to know to get started with Bun, from installation to advanced features.

## Installation

First, install Bun using Homebrew on your ARM Mac:

```bash
# Install Bun using Homebrew
brew tap oven-sh/bun
brew install bun
```

Verify installation:
```bash
bun --version
```

[Official Installation Guide](https://bun.sh/docs/installation)

## Setting Up VS Code

Install these recommended extensions for Bun development:
1. [Bun extension](https://marketplace.visualstudio.com/items?itemName=oven.bun-vscode)
2. JavaScript/TypeScript support (usually pre-installed)

Configure VS Code for Bun by creating `.vscode/settings.json` in your project:
```json
{
  "bun.enable": true,
  "typescript.preferences.includePackageJsonAutoImports": "auto"
}
```

## Your First Bun Script

Create a new project directory and initialize it:
```bash
mkdir bun-tutorial
cd bun-tutorial
bun init
```

This creates a basic `package.json` file. Now create your first script `index.js`:

```javascript
// Simple "Hello World" example
console.log("Hello, Bun!");

// Bun supports top-level await (no need for async function)
const response = await fetch("https://jsonplaceholder.typicode.com/posts/1");
const post = await response.json();
console.log(post.title);
```

Run it with:
```bash
bun index.js
```

[Official Getting Started Guide](https://bun.sh/docs/quickstart)

## Bun's Built-in Test Runner

Create a test file `math.test.js`:

```javascript
// Bun has a built-in test runner - no external libraries needed!
import { expect, test } from "bun:test";

// Simple test case
test("addition works", () => {
  expect(2 + 3).toBe(5);
});

// Async test example
test("fetch returns data", async () => {
  const response = await fetch("https://jsonplaceholder.typicode.com/posts/1");
  expect(response.status).toBe(200);
});
```

Run tests with:
```bash
bun test
```

[Official Testing Documentation](https://bun.sh/docs/test)

## Working with Packages

Bun includes its own package manager. Install packages with:
```bash
bun add react
bun add --dev typescript
```

Create a simple React component `App.jsx`:

```jsx
// Bun supports JSX out of the box
import React from "react";

export default function App() {
  const [count, setCount] = React.useState(0);
  
  return (
    <div>
      <h1>Counter App</h1>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>
        Increment
      </button>
    </div>
  );
}
```

[Package Management Docs](https://bun.sh/docs/cli/install)

## Bun's Built-in Web Server

Create a simple HTTP server `server.js`:

```javascript
// Bun's built-in HTTP server - extremely fast!
export default {
  port: 3000,
  fetch(request) {
    const url = new URL(request.url);
    
    // Simple routing
    if (url.pathname === "/") {
      return new Response("Hello from Bun!", {
        headers: { "content-type": "text/plain" },
      });
    }
    
    if (url.pathname === "/json") {
      return Response.json({
        message: "Hello JSON!",
        timestamp: new Date().toISOString(),
      });
    }
    
    return new Response("Not Found", { status: 404 });
  },
};

// Run with: bun run server.js
```

[HTTP Server Documentation](https://bun.sh/docs/api/http)

## Working with Files

Create a file example `files.js`:

```javascript
// Bun makes file operations simple with built-in fs-like APIs
import { readFile, writeFile } from "fs/promises";

// Reading a file
try {
  const content = await readFile("example.txt", "utf-8");
  console.log("File content:", content);
} catch (error) {
  console.log("File not found, creating it...");
}

// Writing a file
await writeFile("example.txt", "Hello from Bun!", "utf-8");
console.log("File written successfully!");

// Bun also supports reading JSON files directly
// const config = await import("./config.json", { assert: { type: "json" } });
```

[File System API Docs](https://bun.sh/docs/api/file-io)

## Environment Variables

Create `.env` file:
```
API_KEY=your-secret-key
DATABASE_URL=postgresql://localhost:5432/mydb
```

Use in your code `env-example.js`:

```javascript
// Bun automatically loads .env files
// No need for dotenv package!

console.log("API Key:", process.env.API_KEY);
console.log("Database URL:", process.env.DATABASE_URL);

// Set default values
const PORT = process.env.PORT || 3000;
console.log("Server will run on port:", PORT);
```

[Environment Variables Docs](https://bun.sh/docs/runtime/env)

## Working with Databases

Example with SQLite (Bun has built-in SQLite support):

```javascript
// Bun has built-in SQLite3 support - no need to install anything!
import { Database } from "bun:sqlite";

// Open or create database
const db = new Database("mydb.sqlite");

// Create table
db.exec(`
  CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE
  )
`);

// Insert data
const insertUser = db.prepare("INSERT INTO users (name, email) VALUES (?, ?)");
insertUser.run("John Doe", "john@example.com");

// Query data
const getUsers = db.prepare("SELECT * FROM users");
const users = getUsers.all();
console.log("Users:", users);

// Close database
db.close();
```

[SQLite Documentation](https://bun.sh/docs/api/sqlite)

## Bun's Built-in Bundler

Create a simple frontend app with `src/index.js`:

```javascript
// This is a simple frontend module
import { add } from "./math.js";

document.addEventListener("DOMContentLoaded", () => {
  const result = add(5, 3);
  document.getElementById("app").innerHTML = `<h1>Result: ${result}</h1>`;
});
```

And `src/math.js`:
```javascript
// Simple math module
export function add(a, b) {
  return a + b;
}

export function multiply(a, b) {
  return a * b;
}
```

Bundle with:
```bash
bun build src/index.js --outfile dist/bundle.js
```

[Bundler Documentation](https://bun.sh/docs/bundler)

## Working with TypeScript

Rename `index.js` to `index.ts`:

```typescript
// Bun has built-in TypeScript support!
interface User {
  id: number;
  name: string;
  email: string;
}

// Type-safe function
function greetUser(user: User): string {
  return `Hello, ${user.name}!`;
}

const user: User = {
  id: 1,
  name: "Alice",
  email: "alice@example.com"
};

console.log(greetUser(user));

// Bun automatically compiles TypeScript without config!
```

[TypeScript Support Docs](https://bun.sh/docs/typescript)

## HTTP Client (Built-in fetch)

Create `api-client.js`:

```javascript
// Bun uses the standard Web API fetch - no need for axios!
async function fetchPosts() {
  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts");
    const posts = await response.json();
    return posts.slice(0, 5); // First 5 posts
  } catch (error) {
    console.error("Error fetching posts:", error);
    return [];
  }
}

// POST request example
async function createPost(title, body) {
  const response = await fetch("https://jsonplaceholder.typicode.com/posts", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ title, body, userId: 1 }),
  });
  
  return await response.json();
}

// Usage
const posts = await fetchPosts();
console.log("Posts:", posts);

const newPost = await createPost("My Post", "This is my post content");
console.log("Created post:", newPost);
```

[Fetch API Docs](https://bun.sh/docs/api/http)

## Working with WebSockets

Create `websocket-server.js`:

```javascript
// Bun has built-in WebSocket support
export default {
  port: 3000,
  fetch(req, server) {
    // Upgrade to WebSocket connection
    if (req.url.endsWith("/ws")) {
      const success = server.upgrade(req);
      return success 
        ? undefined 
        : new Response("WebSocket upgrade failed", { status: 400 });
    }
    
    return new Response("Hello from Bun!");
  },
  
  websocket: {
    open(ws) {
      console.log("WebSocket opened");
      ws.send("Welcome to Bun WebSocket!");
    },
    
    message(ws, message) {
      console.log("Received:", message);
      // Echo the message back
      ws.send(`Echo: ${message}`);
    },
    
    close(ws) {
      console.log("WebSocket closed");
    },
  },
};
```

[WebSocket Documentation](https://bun.sh/docs/api/websocket)

## Creating a Complete Web App

Create a full example app with `app.js`:

```javascript
// Complete Bun web application example
import { Database } from "bun:sqlite";

// Initialize database
const db = new Database("todos.sqlite");
db.exec(`
  CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);

// Prepare statements for performance
const insertTodo = db.prepare("INSERT INTO todos (title) VALUES (?) RETURNING *");
const getAllTodos = db.prepare("SELECT * FROM todos ORDER BY created_at DESC");
const updateTodo = db.prepare("UPDATE todos SET completed = ? WHERE id = ? RETURNING *");

export default {
  port: 3000,
  fetch(request) {
    const url = new URL(request.url);
    
    // CORS headers for frontend access
    const headers = {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE",
      "Access-Control-Allow-Headers": "Content-Type",
      "Content-Type": "application/json",
    };
    
    // Handle preflight requests
    if (request.method === "OPTIONS") {
      return new Response(null, { headers });
    }
    
    // GET /todos - List all todos
    if (url.pathname === "/todos" && request.method === "GET") {
      const todos = getAllTodos.all();
      return Response.json(todos, { headers });
    }
    
    // POST /todos - Create new todo
    if (url.pathname === "/todos" && request.method === "POST") {
      return handleCreateTodo(request, headers);
    }
    
    // PUT /todos/:id - Update todo
    if (url.pathname.startsWith("/todos/") && request.method === "PUT") {
      const id = url.pathname.split("/")[2];
      return handleUpdateTodo(request, id, headers);
    }
    
    return new Response("Not Found", { status: 404, headers });
  },
};

async function handleCreateTodo(request, headers) {
  try {
    const { title } = await request.json();
    if (!title) {
      return Response.json({ error: "Title is required" }, { status: 400, headers });
    }
    
    const todo = insertTodo.get(title);
    return Response.json(todo, { headers });
  } catch (error) {
    return Response.json({ error: "Invalid JSON" }, { status: 400, headers });
  }
}

async function handleUpdateTodo(request, id, headers) {
  try {
    const { completed } = await request.json();
    const todo = updateTodo.get(completed, id);
    
    if (!todo) {
      return Response.json({ error: "Todo not found" }, { status: 404, headers });
    }
    
    return Response.json(todo, { headers });
  } catch (error) {
    return Response.json({ error: "Invalid JSON" }, { status: 400, headers });
  }
}
```

Run with:
```bash
bun app.js
```

You can test with curl:
```bash
# Create a todo
curl -X POST http://localhost:3000/todos -H "Content-Type: application/json" -d '{"title":"Learn Bun"}'

# Get all todos
curl http://localhost:3000/todos

# Update a todo
curl -X PUT http://localhost:3000/todos/1 -H "Content-Type: application/json" -d '{"completed":true}'
```

## Bun Development Tips

Create a `development.js` script:

```javascript
// Development utilities for Bun projects
import { watch } from "fs";

// Hot reloading for development
console.log("Starting development server...");

// Watch for file changes
watch("./src", { recursive: true }, (eventType, filename) => {
  if (filename) {
    console.log(`File changed: ${filename}`);
    // In a real app, you'd restart the server or rebuild here
  }
});

// Memory usage monitoring
setInterval(() => {
  const usage = process.memoryUsage();
  console.log(`Memory usage: ${Math.round(usage.heapUsed / 1024 / 1024)} MB`);
}, 5000);

// Graceful shutdown
process.on("SIGINT", () => {
  console.log("\nShutting down gracefully...");
  process.exit(0);
});
```

## Performance Monitoring

Create `benchmark.js`:

```javascript
// Bun performance benchmarking
async function benchmark() {
  const start = performance.now();
  
  // Your code to benchmark here
  for (let i = 0; i < 1000000; i++) {
    Math.sqrt(i);
  }
  
  const end = performance.now();
  console.log(`Execution time: ${end - start} milliseconds`);
}

// Run benchmark
await benchmark();

// Bun is extremely fast!
```

[Performance Guide](https://bun.sh/docs/performance)

## Next Steps

1. Explore the [Bun documentation](https://bun.sh/docs)
2. Check out [Bun examples on GitHub](https://github.com/oven-sh/bun-examples)
3. Join the [Bun Discord community](https://discord.gg/CXdq2DP29u)

This tutorial covered Bun's core features:
- Built-in test runner
- Package management
- HTTP server and client
- File system operations
- SQLite database support
- TypeScript support
- Bundler
- WebSocket support

With these examples, you can build fast, modern JavaScript/TypeScript applications using Bun's all-in-one toolkit!