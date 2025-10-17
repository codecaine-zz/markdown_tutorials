# Bun JavaScript Guide (official docs–based)

Updated: 2025-10-17 • Works on macOS, Linux, and Windows

This guide distills the Bun documentation into a practical, copy‑ready reference for building apps with Bun's runtime, package manager, bundler, test runner, and APIs. Links point to the official docs for deeper detail.

[Official docs home](https://bun.sh/docs)

---

## 1) Install and verify

- macOS/Linux (curl) — see [Installation](https://bun.sh/docs/installation)

```bash
curl -fsSL https://bun.com/install | bash
# optional: install a specific version
# curl -fsSL https://bun.com/install | bash -s "bun-v1.3.0"
```

- Windows (PowerShell) — see [Installation](https://bun.sh/docs/installation)

```powershell
powershell -c "irm bun.sh/install.ps1|iex"
```

Notes from docs:

- Linux needs unzip; kernel 5.6+ recommended (min 5.1).
- If “bun: command not found”, add ~/.bun/bin to PATH.
- Verify and show revision:

```bash
bun --version
bun --revision
```

---

## 2) Quickstart project

### Creating Your First Bun Project

**Step 1: Initialize a new project**

```bash
# Create a new directory and navigate into it
mkdir my-first-bun-app
cd my-first-bun-app

# Initialize the project (Bun will ask you questions)
bun init
```

When you run `bun init`, you'll see prompts like:
- **Package name:** Press Enter to use the folder name
- **Entry point:** Press Enter to use `index.ts`

This creates:
- `package.json` – Lists your project dependencies
- `index.ts` – Your main code file
- `tsconfig.json` – TypeScript configuration
- `.gitignore` – Files to ignore in Git
- `README.md` – Project documentation

**Step 2: Write your first program**

Edit `index.ts`:

```ts
// index.ts - A simple greeting program
console.log("Hello from Bun! 🥟")

// Define a function to greet someone
function greet(name: string) {
  return `Welcome, ${name}!`
}

// Use the function
const message = greet("Developer")
console.log(message)

// Show some Bun info
console.log(`Running on Bun version: ${Bun.version}`)
```

**Step 3: Run your program**

```bash
# Run the file directly - no compilation needed!
bun run index.ts

# Or simply:
bun index.ts
```

### Using Templates for Quick Starts

```bash
# Create a React app
bunx create-react-app my-react-app

# Create a Next.js app
bunx create-next-app my-next-app

# Create a basic HTTP server
bunx --bun create bun-examples/http my-server
```

### Setting Up Scripts in package.json

```json
{
  "name": "my-first-bun-app",
  "scripts": {
    "start": "bun run index.ts",
    "dev": "bun --watch index.ts",
    "test": "bun test"
  }
}
```

Now you can use short commands:

```bash
bun start    # Runs your app once
bun dev      # Runs and restarts when files change
bun test     # Runs your tests
```

Sources: [What is Bun?](https://bun.sh/docs), [bun run](https://bun.sh/docs/cli/run), [TypeScript](https://bun.sh/docs/runtime/typescript), [bunx](https://bun.sh/docs/cli/bunx)

---

## 3) Package manager essentials

### Installing and Managing Packages

**Installing all dependencies from package.json:**

```bash
# Install everything listed in package.json
bun install
```

**Adding new packages:**

```bash
# Add a package for production use
bun add axios

# Add a package only for development (like testing tools)
bun add --dev @types/node

# Add a package globally (available everywhere on your computer)
bun add --global typescript
```

**Example: Adding and using a package**

```bash
# Install a package to make HTTP requests
bun add axios
```

```ts
// Using the axios package in your code
import axios from "axios"

// Fetch data from an API
const response = await axios.get("https://api.github.com/users/github")
console.log(`GitHub user: ${response.data.name}`)
console.log(`Followers: ${response.data.followers}`)
```

**Removing packages:**

```bash
# Remove a package you no longer need
bun remove axios
```

**Updating packages:**

```bash
# Update all packages to their latest versions
bun update

# Update a specific package
bun update axios
```

**Checking package information:**

```bash
# See which packages are outdated
bun outdated

# See why a package is installed (which other packages need it)
bun why axios

# List all installed packages
bun pm ls
```

**Understanding bun.lockb:**

When you run `bun install`, Bun creates a `bun.lockb` file. This file:
- Records the exact versions of all packages installed
- Ensures everyone on your team gets the same versions
- Makes installations faster (binary format)

You should commit `bun.lockb` to Git!

Sources: [Package manager](https://bun.sh/docs/cli/install), [isolated installs](https://bun.sh/docs/install/isolated), [workspaces](https://bun.sh/docs/install/workspaces)

---

## 4) Run one‑off binaries with bunx

### Understanding bunx (Like npx, but Faster)

`bunx` lets you run command-line tools without installing them permanently.

**Simple examples:**

```bash
# Run cowsay to display a message
bunx cowsay "Hello from Bun!"

# Create a new React app (downloads create-react-app and runs it)
bunx create-react-app my-app

# Run TypeScript compiler once
bunx tsc --version

# Run prettier to format a file
bunx prettier --write myfile.js
```

**Using multiple packages together:**

```bash
# Install and use Angular CLI
bunx -p @angular/cli ng version

# Use a specific version
bunx -p typescript@5.0.0 tsc --version
```

**Force using Bun runtime:**

```bash
# Some tools have Node.js shebang; force them to use Bun
bunx --bun vite --version
```

Source: [bunx](https://bun.sh/docs/cli/bunx)

---

## 5) Runtime: HTTP server and WebSockets

### Building Your First Web Server

**Example 1: Simple Hello World Server**

```ts
// server.ts - The simplest web server
const server = Bun.serve({
  port: 3000, // The port to listen on
  
  // This function handles every request
  fetch(request) {
    return new Response("Hello World! 👋")
  },
})

console.log(`Server running at http://localhost:${server.port}`)
```

Run it:

```bash
bun run server.ts
# Visit http://localhost:3000 in your browser
```

**Example 2: Handling Different URLs (Routes)**

```ts
// server-routes.ts - Server with multiple pages
const server = Bun.serve({
  port: 3000,
  
  fetch(request) {
    const url = new URL(request.url)
    
    // Homepage
    if (url.pathname === "/") {
      return new Response("Welcome to my website!")
    }
    
    // About page
    if (url.pathname === "/about") {
      return new Response("This is the about page")
    }
    
    // API endpoint that returns JSON
    if (url.pathname === "/api/data") {
      const data = { message: "Hello", timestamp: Date.now() }
      return Response.json(data)
    }
    
    // 404 Not Found for everything else
    return new Response("Page not found", { status: 404 })
  },
})

console.log(`Server at http://localhost:${server.port}`)
```

**Example 3: Serving HTML Pages**

```ts
// html-server.ts - Serve HTML content
const server = Bun.serve({
  port: 3000,
  
  fetch(request) {
    const url = new URL(request.url)
    
    if (url.pathname === "/") {
      const html = `
        <!DOCTYPE html>
        <html>
          <head>
            <title>My Bun App</title>
          </head>
          <body>
            <h1>Welcome! 🥟</h1>
            <p>This HTML is served by Bun.</p>
            <a href="/api/time">Check the time</a>
          </body>
        </html>
      `
      return new Response(html, {
        headers: { "Content-Type": "text/html" }
      })
    }
    
    if (url.pathname === "/api/time") {
      return Response.json({ time: new Date().toISOString() })
    }
    
    return new Response("Not found", { status: 404 })
  },
})

console.log(`Visit http://localhost:${server.port}`)
```

**Example 4: Serving Static Files**

```ts
// static-server.ts - Serve files from a folder
const server = Bun.serve({
  port: 3000,
  
  async fetch(request) {
    const url = new URL(request.url)
    
    // Serve index.html for the homepage
    if (url.pathname === "/") {
      const file = Bun.file("./public/index.html")
      return new Response(file)
    }
    
    // Serve any file from the public folder
    const filePath = `./public${url.pathname}`
    const file = Bun.file(filePath)
    
    // Check if file exists
    if (await file.exists()) {
      return new Response(file)
    }
    
    return new Response("File not found", { status: 404 })
  },
})

console.log(`Serving files from ./public at http://localhost:${server.port}`)
```

Create `public/index.html`:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Static Site</title>
  </head>
  <body>
    <h1>Hello from a static file!</h1>
  </body>
</html>
```

**Example 5: Simple WebSocket Chat**

```ts
// websocket-server.ts - Real-time chat server
const server = Bun.serve({
  port: 4000,
  
  // WebSocket handlers
  websocket: {
    // When a client connects
    open(ws) {
      console.log("Client connected")
      ws.send("Welcome to the chat!")
    },
    
    // When a client sends a message
    message(ws, message) {
      console.log("Received:", message)
      // Echo the message back
      ws.send(`Server received: ${message}`)
    },
    
    // When a client disconnects
    close(ws) {
      console.log("Client disconnected")
    },
  },
  
  // HTTP handler for initial connection
  fetch(request, server) {
    const url = new URL(request.url)
    
    // Upgrade HTTP to WebSocket
    if (url.pathname === "/chat") {
      const upgraded = server.upgrade(request)
      if (upgraded) {
        return undefined // Successfully upgraded
      }
    }
    
    // Serve a simple HTML client
    if (url.pathname === "/") {
      const html = `
        <!DOCTYPE html>
        <html>
          <head><title>WebSocket Chat</title></head>
          <body>
            <h1>WebSocket Chat</h1>
            <div id="messages"></div>
            <input id="input" type="text" placeholder="Type a message...">
            <button onclick="send()">Send</button>
            <script>
              const ws = new WebSocket('ws://localhost:4000/chat')
              const messages = document.getElementById('messages')
              const input = document.getElementById('input')
              
              ws.onmessage = (event) => {
                messages.innerHTML += '<p>' + event.data + '</p>'
              }
              
              function send() {
                ws.send(input.value)
                input.value = ''
              }
              
              input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') send()
              })
            </script>
          </body>
        </html>
      `
      return new Response(html, {
        headers: { "Content-Type": "text/html" }
      })
    }
    
    return new Response("Not found", { status: 404 })
  },
})

console.log(`WebSocket server at http://localhost:${server.port}`)
```

Sources: API → HTTP server, WebSockets, File I/O

---

## 6) Environment variables (.env built‑in)

### Using .env Files (No Extra Package Needed!)

**Step 1: Create a .env file**

Create a file named `.env` in your project root:

```bash
# .env - Store your configuration here
API_KEY=abc123xyz789
DATABASE_URL=postgresql://localhost:5432/mydb
PORT=3000
NODE_ENV=development
```

**Step 2: Use the variables in your code**

```ts
// config.ts - Access environment variables
console.log("API Key:", process.env.API_KEY)
console.log("Database:", process.env.DATABASE_URL)
console.log("Port:", process.env.PORT)

// You can also use Bun.env (same as process.env)
console.log("Environment:", Bun.env.NODE_ENV)

// Or import.meta.env
console.log("API Key (import.meta):", import.meta.env.API_KEY)
```

**Step 3: Run your app**

```bash
# Bun automatically loads .env
bun run config.ts
```

**Using different .env files:**

```bash
# .env.development - For local development
API_URL=http://localhost:3000
DEBUG=true

# .env.production - For production servers
API_URL=https://api.myapp.com
DEBUG=false

# .env.local - For your personal overrides (add to .gitignore!)
API_KEY=my_personal_test_key
```

Bun automatically loads:
- `.env` (always loaded)
- `.env.local` (personal overrides)
- `.env.development` or `.env.production` (based on NODE_ENV)

**Example: Server with Environment Variables**

```ts
// server-env.ts - Configure server with .env
const server = Bun.serve({
  port: Number(process.env.PORT) || 3000,
  
  fetch(request) {
    const url = new URL(request.url)
    
    if (url.pathname === "/api/config") {
      // Don't expose sensitive data in production!
      return Response.json({
        environment: process.env.NODE_ENV,
        port: process.env.PORT,
        // Never expose API keys like this in real apps!
      })
    }
    
    return new Response("Hello!")
  },
})

console.log(`Server running on port ${server.port}`)
console.log(`Environment: ${process.env.NODE_ENV}`)
```

**Running with different environments:**

```bash
# Use development settings
NODE_ENV=development bun run server-env.ts

# Use production settings
NODE_ENV=production bun run server-env.ts

# Use a custom .env file
bun --env-file=.env.staging run server-env.ts
```

Source: [Environment variables](https://bun.sh/docs/runtime/env)

---

## 7) TypeScript in the runtime

### TypeScript Made Easy

**You can write TypeScript directly - no setup needed!**

```ts
// math.ts - TypeScript with type safety
// Define types for your data
type User = {
  name: string
  age: number
  email: string
}

// Function with typed parameters and return value
function createUser(name: string, age: number, email: string): User {
  return { name, age, email }
}

// Use the function
const user = createUser("Alice", 30, "alice@example.com")
console.log(user)

// TypeScript catches errors!
// createUser("Bob", "thirty", "bob@example.com") // Error: age must be number
```

**Example: Type-safe API responses**

```ts
// api.ts - Typed API calls
// Define the shape of your API response
interface GithubUser {
  login: string
  name: string
  followers: number
  public_repos: number
}

// Fetch and type the response
async function getGithubUser(username: string): Promise<GithubUser> {
  const response = await fetch(`https://api.github.com/users/${username}`)
  const data: GithubUser = await response.json()
  return data
}

// Use it with type safety
const user = await getGithubUser("github")
console.log(`${user.name} has ${user.followers} followers`)
// TypeScript knows all the properties available!
```

**Path mapping for cleaner imports:**

Setup `tsconfig.json`:

```json
{
  "compilerOptions": {
    "baseUrl": "./src",
    "paths": {
      "@utils/*": ["utils/*"],
      "@components/*": ["components/*"],
      "@types/*": ["types/*"]
    }
  }
}
```

Now you can import like this:

```ts
// Instead of: import { helper } from "../../../utils/helper"
import { helper } from "@utils/helper"

// Instead of: import { Button } from "../../components/Button"
import { Button } from "@components/Button"
```

**Important Notes:**

- Bun **runs** TypeScript but doesn't **check** types
- Use `tsc` to check for type errors:

```bash
# Install TypeScript
bun add --dev typescript

# Check for type errors (doesn't run code)
bunx tsc --noEmit

# Or add to package.json scripts
{
  "scripts": {
    "typecheck": "tsc --noEmit"
  }
}
```

Source: [TypeScript runtime](https://bun.sh/docs/runtime/typescript)

---

## 8) Testing with bun test

### Writing Your First Tests

**Example 1: Basic Math Tests**

```ts
// math.test.ts - Simple tests
import { describe, it, expect } from "bun:test"

// Group related tests
describe("basic math", () => {
  
  it("adds two numbers", () => {
    const result = 2 + 2
    expect(result).toBe(4)
  })
  
  it("subtracts numbers", () => {
    expect(10 - 5).toBe(5)
  })
  
  it("multiplies numbers", () => {
    expect(3 * 4).toBe(12)
  })
  
})
```

Run tests:

```bash
# Run all tests in your project
bun test

# Run only files matching a pattern
bun test math

# Run in watch mode (re-run when files change)
bun test --watch
```

**Example 2: Testing Functions**

```ts
// utils.ts - Functions to test
export function greet(name: string): string {
  return `Hello, ${name}!`
}

export function isEven(num: number): boolean {
  return num % 2 === 0
}

export function sum(numbers: number[]): number {
  return numbers.reduce((acc, n) => acc + n, 0)
}
```

```ts
// utils.test.ts - Test the functions
import { describe, it, expect } from "bun:test"
import { greet, isEven, sum } from "./utils"

describe("greet function", () => {
  it("greets a person by name", () => {
    expect(greet("Alice")).toBe("Hello, Alice!")
  })
  
  it("works with different names", () => {
    expect(greet("Bob")).toBe("Hello, Bob!")
  })
})

describe("isEven function", () => {
  it("returns true for even numbers", () => {
    expect(isEven(2)).toBe(true)
    expect(isEven(4)).toBe(true)
    expect(isEven(100)).toBe(true)
  })
  
  it("returns false for odd numbers", () => {
    expect(isEven(1)).toBe(false)
    expect(isEven(3)).toBe(false)
    expect(isEven(99)).toBe(false)
  })
})

describe("sum function", () => {
  it("adds an array of numbers", () => {
    expect(sum([1, 2, 3])).toBe(6)
  })
  
  it("returns 0 for empty array", () => {
    expect(sum([])).toBe(0)
  })
})
```

**Example 3: Testing Async Functions**

```ts
// api.test.ts - Test async code
import { describe, it, expect } from "bun:test"

// Function that returns a Promise
async function fetchData(): Promise<string> {
  return new Promise((resolve) => {
    setTimeout(() => resolve("data"), 100)
  })
}

describe("async functions", () => {
  
  it("fetches data successfully", async () => {
    const data = await fetchData()
    expect(data).toBe("data")
  })
  
  it("handles async/await", async () => {
    const result = await Promise.resolve(42)
    expect(result).toBe(42)
  })
  
})
```

**Example 4: Using Test Lifecycle Hooks**

```ts
// lifecycle.test.ts - Setup and teardown
import { describe, it, expect, beforeEach, afterEach } from "bun:test"

describe("database tests", () => {
  let db: string[] // Pretend database
  
  // Runs before each test
  beforeEach(() => {
    console.log("Setting up test...")
    db = [] // Start with empty database
  })
  
  // Runs after each test
  afterEach(() => {
    console.log("Cleaning up...")
    db = [] // Clean up
  })
  
  it("adds items to database", () => {
    db.push("item1")
    expect(db).toHaveLength(1)
  })
  
  it("starts with empty database", () => {
    // This proves beforeEach ran and cleared the db
    expect(db).toHaveLength(0)
  })
  
})
```

**Common Matchers:**

```ts
// Equality
expect(value).toBe(expected)        // Exact match (===)
expect(value).toEqual(expected)     // Deep equality for objects

// Truthiness
expect(value).toBeTruthy()          // Truthy value
expect(value).toBeFalsy()           // Falsy value
expect(value).toBeNull()            // Exactly null
expect(value).toBeUndefined()       // Exactly undefined

// Numbers
expect(value).toBeGreaterThan(3)
expect(value).toBeLessThan(10)
expect(value).toBeCloseTo(0.3)      // For floating point

// Strings
expect(string).toContain("substring")
expect(string).toMatch(/pattern/)

// Arrays
expect(array).toHaveLength(3)
expect(array).toContain(item)

// Errors
expect(() => fn()).toThrow()
expect(() => fn()).toThrow("error message")
```

Sources: [bun test](https://bun.sh/docs/cli/test) and related test docs

---

## 9) Bundling with Bun.build

### Creating Production-Ready Bundles

**Example 1: Simple Bundle**

```ts
// build.ts - Bundle your app
await Bun.build({
  entrypoints: ["./src/index.ts"],
  outdir: "./dist",
})

console.log("Build complete!")
```

Run it:

```bash
bun run build.ts
```

**Example 2: Browser Bundle with Minification**

```ts
// build-browser.ts - Bundle for browsers
const result = await Bun.build({
  entrypoints: ["./src/app.tsx"],
  outdir: "./public/js",
  target: "browser",     // Optimize for browsers
  minify: true,          // Make files smaller
  splitting: true,       // Split into multiple files for caching
})

console.log(`Built ${result.outputs.length} files`)

// Show what was created
for (const output of result.outputs) {
  console.log(`- ${output.path}`)
}
```

**Example 3: Build Configuration with All Options**

```ts
// build-config.ts - Production build
const result = await Bun.build({
  // Input files
  entrypoints: ["./src/index.tsx"],
  
  // Output directory
  outdir: "./dist",
  
  // Target environment
  target: "browser", // or "bun" or "node"
  
  // Output format
  format: "esm", // or "cjs" or "iife"
  
  // Optimization
  minify: {
    whitespace: true,
    identifiers: true,
    syntax: true,
  },
  
  // Code splitting for better caching
  splitting: true,
  
  // Source maps for debugging
  sourcemap: "external",
  
  // Public path for assets (e.g., CDN)
  publicPath: "/assets/",
  
  // Define environment variables
  define: {
    "process.env.NODE_ENV": '"production"',
    "process.env.API_URL": '"https://api.myapp.com"',
  },
})

if (!result.success) {
  console.error("Build failed!")
  for (const log of result.logs) {
    console.error(log)
  }
  process.exit(1)
}

console.log("✅ Build successful!")
```

**Example 4: CLI Build Commands**

```bash
# Basic build
bun build ./src/index.tsx --outdir ./dist

# Build for Node.js
bun build ./src/server.ts --target=node --outfile ./dist/server.js

# Minified browser bundle
bun build ./src/app.ts --target=browser --minify --outdir ./public

# Watch mode (rebuild on changes)
bun build ./src/index.ts --outdir ./dist --watch

# Build with code splitting
bun build ./src/index.tsx --outdir ./dist --splitting
```

**Example 5: Creating a Standalone Executable**

```ts
// cli.ts - A simple command-line tool
#!/usr/bin/env bun

const args = process.argv.slice(2)

if (args.length === 0) {
  console.log("Usage: mycli <name>")
  process.exit(1)
}

console.log(`Hello, ${args[0]}! 👋`)
console.log(`You're using Bun ${Bun.version}`)
```

Build it into an executable:

```bash
# Create a single binary file that includes Bun runtime
bun build ./cli.ts --compile --outfile mycli

# Now you can run it directly (no need for "bun run")
./mycli World
# Output: Hello, World! 👋

# You can share this file with others - it's completely standalone!
```

**Example 6: Package.json Build Scripts**

```json
{
  "scripts": {
    "build": "bun build ./src/index.tsx --outdir ./dist --target browser",
    "build:min": "bun build ./src/index.tsx --outdir ./dist --minify --splitting",
    "build:watch": "bun build ./src/index.tsx --outdir ./dist --watch",
    "build:server": "bun build ./src/server.ts --target node --outfile ./dist/server.js",
    "build:cli": "bun build ./src/cli.ts --compile --outfile ./bin/mycli"
  }
}
```

Use them:

```bash
bun run build
bun run build:min
bun run build:watch
```

Sources: [Bun.build](https://bun.sh/docs/bundler), [Executables](https://bun.sh/docs/bundler/executables)

---

## 10) SQLite with bun:sqlite

### Local Database Made Simple

**Example 1: Create and Use a Database**

```ts
// database.ts - Your first SQLite database
import { Database } from "bun:sqlite"

// Create or open a database file
const db = new Database("myapp.db", { create: true })

// Create a table
db.run(`
  CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
  )
`)

// Insert data
db.run(
  "INSERT INTO users (name, email) VALUES (?, ?)",
  ["Alice", "alice@example.com"]
)

db.run(
  "INSERT INTO users (name, email) VALUES (?, ?)",
  ["Bob", "bob@example.com"]
)

// Query all users
const users = db.query("SELECT * FROM users").all()
console.log("All users:", users)

// Query one user
const alice = db.query("SELECT * FROM users WHERE name = ?").get("Alice")
console.log("Found user:", alice)

// Close the database
db.close()
```

**Example 2: Todo List App with SQLite**

```ts
// todo-app.ts - Complete todo list with database
import { Database } from "bun:sqlite"

// Setup database
const db = new Database("todos.db", { create: true })

db.run(`
  CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    task TEXT NOT NULL,
    completed INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
  )
`)

// Prepare reusable queries
const insertTodo = db.query("INSERT INTO todos (task) VALUES (?)")
const getTodos = db.query("SELECT * FROM todos")
const completeTodo = db.query("UPDATE todos SET completed = 1 WHERE id = ?")
const deleteTodo = db.query("DELETE FROM todos WHERE id = ?")

// Add todos
insertTodo.run("Buy groceries")
insertTodo.run("Write code")
insertTodo.run("Exercise")

// Get all todos
console.log("\n📝 All todos:")
const todos = getTodos.all()
for (const todo of todos) {
  const status = todo.completed ? "✅" : "⬜"
  console.log(`${status} ${todo.id}. ${todo.task}`)
}

// Complete a todo
completeTodo.run(1)

// Show completed todos
console.log("\n✅ Completed todos:")
const completed = db.query("SELECT * FROM todos WHERE completed = 1").all()
for (const todo of completed) {
  console.log(`${todo.id}. ${todo.task}`)
}

db.close()
```

**Example 3: Using Transactions**

```ts
// transactions.ts - Safe batch operations
import { Database } from "bun:sqlite"

const db = new Database("store.db", { create: true })

db.run(`
  CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY,
    name TEXT,
    price REAL,
    stock INTEGER
  )
`)

// Transaction: all succeed or all fail
const addProducts = db.transaction((products) => {
  const insert = db.prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)")
  
  for (const product of products) {
    insert.run(product.name, product.price, product.stock)
  }
})

// Add multiple products safely
try {
  addProducts([
    { name: "Laptop", price: 999.99, stock: 10 },
    { name: "Mouse", price: 29.99, stock: 50 },
    { name: "Keyboard", price: 79.99, stock: 30 },
  ])
  console.log("✅ Products added successfully")
} catch (error) {
  console.error("❌ Transaction failed:", error)
}

// View all products
const products = db.query("SELECT * FROM products").all()
console.log("\n Products in store:")
for (const product of products) {
  console.log(`- ${product.name}: $${product.price} (${product.stock} in stock)`)
}

db.close()
```

**Example 4: In-Memory Database (Fast, Temporary)**

```ts
// memory-db.ts - Database that exists only in RAM
import { Database } from "bun:sqlite"

// ":memory:" means no file is created
const db = new Database(":memory:")

db.run("CREATE TABLE cache (key TEXT PRIMARY KEY, value TEXT)")

// Use it like a super-fast cache
db.run("INSERT INTO cache VALUES (?, ?)", ["user:1", "Alice"])
db.run("INSERT INTO cache VALUES (?, ?)", ["user:2", "Bob"])

const user = db.query("SELECT value FROM cache WHERE key = ?").get("user:1")
console.log("Cached user:", user)

// Data is lost when the program ends!
```

**Example 5: Simple API with SQLite Backend**

```ts
// api-server.ts - REST API with database
import { Database } from "bun:sqlite"

// Setup database
const db = new Database("api.db", { create: true })
db.run(`
  CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
  )
`)

// Start server
const server = Bun.serve({
  port: 3000,
  
  fetch(request) {
    const url = new URL(request.url)
    
    // GET /posts - Get all posts
    if (url.pathname === "/posts" && request.method === "GET") {
      const posts = db.query("SELECT * FROM posts").all()
      return Response.json(posts)
    }
    
    // POST /posts - Create a post
    if (url.pathname === "/posts" && request.method === "POST") {
      const body = await request.json()
      db.run(
        "INSERT INTO posts (title, content) VALUES (?, ?)",
        [body.title, body.content]
      )
      return Response.json({ message: "Post created!" })
    }
    
    // GET /posts/:id - Get one post
    const match = url.pathname.match(/^\/posts\/(\d+)$/)
    if (match && request.method === "GET") {
      const id = match[1]
      const post = db.query("SELECT * FROM posts WHERE id = ?").get(id)
      if (post) {
        return Response.json(post)
      }
      return new Response("Not found", { status: 404 })
    }
    
    return new Response("Not found", { status: 404 })
  },
})

console.log(`API server running at http://localhost:${server.port}`)
console.log(`Try: curl http://localhost:3000/posts`)
```

Test it:

```bash
# Get all posts
curl http://localhost:3000/posts

# Create a post
curl -X POST http://localhost:3000/posts \
  -H "Content-Type: application/json" \
  -d '{"title":"My Post","content":"Hello world!"}'

# Get one post
curl http://localhost:3000/posts/1
```

Source: API → SQLite (see docs site API section)

---

## 11) Node.js compatibility

Bun aims to be largely Node‑compatible (globals like process/Buffer and many node:* modules). Check the docs for current status. When targeting Node with bundles, set target:"node".

Source: Runtime → Node.js compatibility (docs site)

---

## 12) Single‑file executables

### Creating Standalone Apps

**Example 1: Simple CLI Tool**

```ts
// greeter.ts - A greeting tool
#!/usr/bin/env bun

const name = process.argv[2] || "World"
const greeting = `Hello, ${name}! 🎉`

console.log(greeting)
console.log(`This executable was built with Bun ${Bun.version}`)
```

Build and use:

```bash
# Create the executable
bun build ./greeter.ts --compile --outfile greeter

# Run it (no bun command needed!)
./greeter Alice
# Output: Hello, Alice! 🎉

# It's a standalone file - you can copy it anywhere
cp greeter /usr/local/bin/
greeter Bob
```

**Example 2: CLI with Arguments**

```ts
// calc.ts - Simple calculator
#!/usr/bin/env bun

const [operation, a, b] = process.argv.slice(2)

if (!operation || !a || !b) {
  console.log("Usage: calc <operation> <num1> <num2>")
  console.log("Operations: add, subtract, multiply, divide")
  process.exit(1)
}

const num1 = parseFloat(a)
const num2 = parseFloat(b)

let result: number

switch (operation) {
  case "add":
    result = num1 + num2
    break
  case "subtract":
    result = num1 - num2
    break
  case "multiply":
    result = num1 * num2
    break
  case "divide":
    result = num1 / num2
    break
  default:
    console.log("Unknown operation")
    process.exit(1)
}

console.log(`Result: ${result}`)
```

Build and use:

```bash
bun build ./calc.ts --compile --outfile calc

./calc add 5 3        # Result: 8
./calc multiply 4 7   # Result: 28
./calc divide 10 2    # Result: 5
```

**Example 3: Server as Executable**

```ts
// server-exe.ts - Portable web server
const server = Bun.serve({
  port: 8080,
  fetch(request) {
    return new Response("Hello from a standalone executable! 🚀")
  },
})

console.log(`Server running at http://localhost:${server.port}`)
console.log("Press Ctrl+C to stop")
```

Build and deploy:

```bash
# Build the server
bun build ./server-exe.ts --compile --outfile myserver

# Now you have a single file that IS your server!
./myserver

# Copy it to any machine (same OS/arch) and run
scp myserver user@server:/opt/myapp/
ssh user@server
cd /opt/myapp
./myserver
```

Source: [Executables](https://bun.sh/docs/bundler/executables)

---

## 13) Docker image

### Running Bun in Containers

**Example 1: Basic Dockerfile**

```dockerfile
# Dockerfile - Simple Bun app
FROM oven/bun:latest

# Create app directory
WORKDIR /app

# Copy package files
COPY package.json bun.lockb ./

# Install dependencies
RUN bun install --frozen-lockfile

# Copy app source
COPY . .

# Expose port
EXPOSE 3000

# Run the app
CMD ["bun", "run", "src/server.ts"]
```

Build and run:

```bash
# Build the image
docker build -t my-bun-app .

# Run the container
docker run -p 3000:3000 my-bun-app

# Visit http://localhost:3000
```

**Example 2: Multi-stage Build (Smaller Image)**

```dockerfile
# Dockerfile.multi - Production-optimized
# Stage 1: Dependencies
FROM oven/bun:latest AS deps
WORKDIR /app
COPY package.json bun.lockb ./
RUN bun install --frozen-lockfile --production

# Stage 2: Build
FROM oven/bun:latest AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .
# Build your app if needed
# RUN bun run build

# Stage 3: Runtime
FROM oven/bun:latest AS runtime
WORKDIR /app

# Copy only production files
COPY --from=deps /app/node_modules ./node_modules
COPY --from=builder /app/src ./src
COPY --from=builder /app/package.json ./

# Non-root user for security
USER bun

EXPOSE 3000
CMD ["bun", "run", "src/server.ts"]
```

**Example 3: Docker Compose for Development**

```yaml
# docker-compose.yml - Development setup
version: '3.8'

services:
  app:
    image: oven/bun:latest
    working_dir: /app
    volumes:
      - .:/app
      - /app/node_modules
    ports:
      - "3000:3000"
    command: bun run --watch src/server.ts
    environment:
      - NODE_ENV=development
      - PORT=3000
  
  db:
    image: postgres:15
    environment:
      - POSTGRES_PASSWORD=password
      - POSTGRES_DB=myapp
    ports:
      - "5432:5432"
    volumes:
      - db-data:/var/lib/postgresql/data

volumes:
  db-data:
```

Use it:

```bash
# Start everything
docker-compose up

# Run in background
docker-compose up -d

# View logs
docker-compose logs -f

# Stop everything
docker-compose down
```

**Example 4: Environment Variables in Docker**

```dockerfile
# Dockerfile.env - With environment variables
FROM oven/bun:latest
WORKDIR /app

COPY package.json bun.lockb ./
RUN bun install --frozen-lockfile

COPY . .

# Set default environment variables
ENV NODE_ENV=production
ENV PORT=3000

EXPOSE 3000
CMD ["bun", "run", "src/server.ts"]
```

Run with custom environment:

```bash
# Override environment variables
docker run -p 3000:3000 \
  -e NODE_ENV=development \
  -e API_KEY=abc123 \
  my-bun-app

# Or use an env file
docker run -p 3000:3000 --env-file .env.production my-bun-app
```

Source: [Installation → Docker](https://bun.sh/docs/installation)

---

## 14) Troubleshooting checklist

- bun not found: ensure ~/.bun/bin is on PATH.
- Linux “GLIBC not found”: use musl variants or the install script’s auto‑selection.
- CPU instruction issues: use baseline builds (see Installation → CPU requirements).
- .env not loading: verify NODE_ENV and file names; or pass --env-file.
- Type checking: run tsc; Bun runtime transpiles but doesn’t typecheck.
- Slow cold starts in server bundles: try Bun.build bytecode:true (target:"bun", format:"cjs").
- In containers: runtime transpiler cache is disabled by default in official images.

---

## Sources (official documentation)

- [What is Bun?](https://bun.sh/docs)
- [Installation](https://bun.sh/docs/installation)
- [Package manager](https://bun.sh/docs/cli/install)
- [bunx](https://bun.sh/docs/cli/bunx)
- [Environment variables](https://bun.sh/docs/runtime/env)
- [TypeScript runtime](https://bun.sh/docs/runtime/typescript)
- API: [HTTP](https://bun.sh/docs/api/http), [WebSockets](https://bun.sh/docs/api/websockets), [File I/O](https://bun.sh/docs/api/file-io), [SQLite](https://bun.sh/docs/api/sqlite)
- Bundler: [Bun.build](https://bun.sh/docs/bundler), [Executables](https://bun.sh/docs/bundler/executables)
- Tests: [bun test](https://bun.sh/docs/cli/test)

All trademarks and links belong to their respective owners. This guide paraphrases and links to the official Bun docs for accuracy and currency.