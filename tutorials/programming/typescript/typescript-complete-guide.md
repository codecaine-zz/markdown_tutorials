# The TypeScript 5+ & Bun Programming Language: A Comprehensive Textbook Guide for macOS

Welcome to the ultimate learning guide for TypeScript 5+ powered by the ultra-fast Bun runtime on macOS (ARM64 Apple Silicon)! This textbook is structured specifically to take you from a complete beginner (zero programming experience) to an advanced TypeScript developer capable of building type-safe, high-performance web APIs, CLI applications, and frontend systems. Rather than relying on legacy Node.js tooling or external `ts-node` compilers, this guide leverages Bun's native TypeScript runtime, lightning-fast bundler, and zero-config test runner.

> [!NOTE]
> **How to read this book:** Each section starts with a clear explanation of TypeScript types and runtime mechanics, followed by concrete, runnable TypeScript code snippets. All code runs out-of-the-box with `bun run`.

> [!TIP]
> **Interactive Learning:** You can run any TypeScript file instantly using `bun run filename.ts` or test snippets live on the official [TypeScript Playground (typescriptlang.org/play)](https://www.typescriptlang.org/play).

## Repository Structure

This book is paired with a topic-based project layout:

- `types_and_variables/` for primitive types, type inference, `type` aliases, and `const` assertions
- `interfaces_and_objects/` for modeling structured data, optional/readonly properties, and index signatures
- `functions_and_generics/` for typed function signatures, generic constraints (`<T extends object>`), and overloads
- `unions_and_narrowing/` for discriminated unions, type guards (`is`), `typeof`, and exhaustive checks
- `async_and_bun/` for Promises, `async/await`, native `Bun.serve()`, and file I/O (`Bun.file`)

## Quick Start: Learn TypeScript with Bun

1. Install Bun using Homebrew on macOS ARM64:
   ```bash
   brew install oven-sh/bun/bun
   ```
2. Verify Bun installation:
   ```bash
   bun --version
   ```
3. Initialize a new TypeScript project:
   ```bash
   bun init my-ts-app
   cd my-ts-app
   ```
4. Create a file named `src/index.ts`:
   ```ts
   // src/index.ts
   const message: string = "Hello, TypeScript 5+ with Bun on Apple Silicon!";
   console.log(message);
   ```
5. Run it instantly (no build step required):
   ```bash
   bun run src/index.ts
   ```

TypeScript essentials to remember early:
- TypeScript adds **static types** to JavaScript to catch bugs at edit/compile time.
- Types are erased during execution; Bun executes TypeScript directly at native speed.
- Turn on `"strict": true` in `tsconfig.json` for maximum type safety.
- Prefer `interface` or `type` over `any`. Avoid `any` whenever possible.

## Core Language Essentials to Learn Early

- Explicit type annotations (`string`, `number`, `boolean`, `string[]`, `Record<K, V>`)
- Interfaces (`interface`) and Type Aliases (`type`)
- Function signatures, optional parameters (`?`), and default values
- Type Narrowing (`typeof`, `instanceof`, user-defined type guards)
- Generics (`<T>`) for reusable, type-safe functions and data structures
- Discriminated Unions (`type Shape = { kind: "circle"; radius: number } | { kind: "square"; side: number }`)
- Utility Types (`Partial<T>`, `Required<T>`, `Readonly<T>`, `Pick<T, K>`, `Omit<T, K>`)
- Modern Bun Web Standards (`fetch`, `Headers`, `Request`, `Response`, `Bun.serve`)

## Must-Learn-Before-Building Checklist

Before building production TypeScript applications, ensure you can:

- Configure a strict `tsconfig.json` with `"verbatimModuleSyntax": true`
- Define data schemas using `interface` or `type`
- Write generic functions with type bounds (`<T extends { id: string }>`)
- Safely handle `unknown` inputs using type narrowing instead of unsafe type assertions (`as T`)
- Model state machines using discriminated unions
- Handle asynchronous operations with `async/await` and handle errors cleanly

## Why This Matters

TypeScript eliminates common runtime bugs (e.g. `cannot read property of undefined`) before code ever touches production. Combined with Bun, you get instant TypeScript execution, fast hot-reloading, and built-in SQLite/HTTP support.

## Suggested Learning Path

- **Chapter 1**: Primitive Types, Inference, and Annotations
- **Chapter 2**: Interfaces, Objects, and Readonly Rules
- **Chapter 3**: Functions, Tuples, and Generics
- **Chapter 4**: Type Narrowing and Discriminated Unions
- **Chapter 5**: Advanced Utility Types and Mapped Types
- **Chapter 6**: Async Execution and Bun Native APIs

## Practice Exercises

1. Write a function `capitalize(str: string): string` with strict null checks.
2. Define a `User` interface with optional `phone?: string` and `readonly id: string`.
3. Create a generic function `firstElement<T>(arr: T[]): T | undefined`.
4. Implement a type guard `isString(val: unknown): val is string`.
5. Build an HTTP REST server using `Bun.serve()` with JSON type validation.

---

## Textbook Chapters & Runnable Examples

### Chapter 1: Primitive Types, Inference, and Variable Declarations

```ts
// 01_primitives.ts
const appName: string = "TypeScript Textbook";
const version: number = 5.4;
const isProduction: boolean = false;

// Array types
const skills: string[] = ["TypeScript", "Bun", "macOS ARM64"];
const scores: Array<number> = [95, 88, 92];

// Tuples (fixed-length arrays)
const userPair: [string, number] = ["Alice", 30];

// Type Inference (TS automatically infers types)
let inferredCount = 42; // Inferred as number

console.log(`App: ${appName} v${version} | Active Skills: ${skills.join(", ")}`);
console.log(`User: ${userPair[0]}, Age: ${userPair[1]}`);
```

### Chapter 2: Interfaces and Object Data Models

```ts
// 02_interfaces.ts
export interface Product {
  readonly id: string;
  name: string;
  price: number;
  description?: string; // Optional property
}

export type ProductID = string;

const laptop: Product = {
  id: "prod_1001",
  name: "MacBook Pro M4",
  price: 1999.99,
  description: "Apple Silicon Powerhouse",
};

function displayProduct(item: Product): void {
  console.log(`[${item.id}] ${item.name} - $${item.price.toFixed(2)}`);
  if (item.description) {
    console.log(` Details: ${item.description}`);
  }
}

displayProduct(laptop);
```

### Chapter 3: Functions, Generics, and Constraints

```ts
// 03_generics.ts
// Generic identity function
function wrapInArray<T>(item: T): T[] {
  return [item];
}

// Generic constraint restricting T to objects with an id property
interface Identifiable {
  id: string | number;
}

function findById<T extends Identifiable>(items: T[], targetId: T["id"]): T | undefined {
  return items.find((item) => item.id === targetId);
}

const users = [
  { id: 1, name: "Alice" },
  { id: 2, name: "Bob" },
];

const foundUser = findById(users, 2);
console.log("Found User:", foundUser);
```

### Chapter 4: Discriminated Unions and Exhaustive Type Checking

```ts
// 04_discriminated_unions.ts
type NetworkState =
  | { status: "idle" }
  | { status: "loading" }
  | { status: "success"; data: string[] }
  | { status: "error"; error: Error };

function renderUI(state: NetworkState): string {
  switch (state.status) {
    case "idle":
      return "Ready to load data.";
    case "loading":
      return "Loading data, please wait...";
    case "success":
      return `Loaded ${state.data.length} items.`;
    case "error":
      return `Error occurred: ${state.error.message}`;
    default: {
      // Exhaustive checking: TS enforces that all cases are handled!
      const _exhaustiveCheck: never = state;
      return _exhaustiveCheck;
    }
  }
}

console.log(renderUI({ status: "success", data: ["item1", "item2"] }));
```

### Chapter 5: Built-in Utility Types

```ts
// 05_utility_types.ts
interface UserProfile {
  id: string;
  name: string;
  email: string;
  role: "admin" | "user";
}

// Partial: makes all fields optional (great for updates)
type UserUpdateInput = Partial<UserProfile>;

// Omit: removes specified fields
type PublicUser = Omit<UserProfile, "email">;

// Pick: selects specified fields
type UserCredentials = Pick<UserProfile, "email">;

const updateData: UserUpdateInput = { name: "Alice Vance" };
console.log("Update Data Payload:", updateData);
```

### Chapter 6: Native Bun APIs (HTTP Server & File I/O)

```ts
// 06_bun_server.ts
import { serve } from "bun";

const server = serve({
  port: 3000,
  fetch(req: Request): Response {
    const url = new URL(req.url);
    if (url.pathname === "/api/health") {
      return Response.json({ status: "ok", uptime: process.uptime() });
    }
    return new Response("Hello from Bun + TypeScript Server!", { status: 200 });
  },
});

console.log(`Server listening on http://localhost:${server.port}`);
```

---

## Your First Project: A Fast REST API with Bun

```ts
// api_app.ts
import { serve } from "bun";

interface TodoItem {
  id: number;
  text: string;
  done: boolean;
}

const todos: TodoItem[] = [
  { id: 1, text: "Learn TypeScript 5", done: true },
  { id: 2, text: "Build API with Bun", done: false },
];

serve({
  port: 4000,
  fetch(req: Request): Response {
    const url = new URL(req.url);
    
    if (req.method === "GET" && url.pathname === "/todos") {
      return Response.json(todos);
    }

    return Response.json({ error: "Not Found" }, { status: 404 });
  },
});

console.log("REST API running on http://localhost:4000/todos");
```

---

## Quick Reference & Guidelines for macOS ARM64

### Essential Bun Commands

```bash
# Run TypeScript file directly
bun run src/index.ts

# Watch mode (auto-reload on code save)
bun run --watch src/index.ts

# Run tests using Bun's built-in runner
bun test

# Add dependency
bun add express zlib
```

### Common Mistakes to Avoid

1. **Overusing `any`**: Defeats TypeScript's type safety. Use `unknown` or generics instead.
2. **Ignoring `strict` mode**: Always set `"strict": true` in `tsconfig.json`.
3. **Confusing `type` vs `interface`**: Use `interface` for expandable object shapes and `type` for unions/primitives.

---

## Everyday Copy-and-Paste TypeScript & Bun Recipes

```ts
// 1. Typed HTTP GET Fetch Helper with Timeout & Error Handling
async function fetchJson<T>(url: string, timeoutMs: number = 5000): Promise<T> {
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), timeoutMs);
  
  try {
    const res = await fetch(url, { signal: controller.signal });
    if (!res.ok) throw new Error(`HTTP Error ${res.status}: ${res.statusText}`);
    return (await res.json()) as T;
  } finally {
    clearTimeout(id);
  }
}

// 2. Fast File I/O with Bun Native File API
async function readAndParseJson<T>(filePath: string): Promise<T> {
  const file = Bun.file(filePath);
  if (!(await file.exists())) {
    throw new Error(`File not found: ${filePath}`);
  }
  return await file.json();
}

async function writeJsonAtomic<T>(filePath: string, data: T): Promise<number> {
  const content = JSON.stringify(data, null, 2);
  return await Bun.write(filePath, content);
}

// 3. Embedded SQLite Queries with Bun Native sqlite
import { Database } from "bun:sqlite";

interface UserRecord {
  id: number;
  name: string;
  email: string;
}

function queryUsers(dbPath: string): UserRecord[] {
  const db = new Database(dbPath);
  db.run("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, email TEXT)");
  
  const query = db.query<UserRecord, []>("SELECT * FROM users LIMIT 10");
  return query.all();
}

// 4. Safe Runtime Type Guard Validator (Narrowing `unknown`)
interface AppConfig {
  port: number;
  host: string;
  debug: boolean;
}

function isAppConfig(val: unknown): val is AppConfig {
  return (
    typeof val === "object" &&
    val !== null &&
    typeof (val as Record<string, unknown>).port === "number" &&
    typeof (val as Record<string, unknown>).host === "string" &&
    typeof (val as Record<string, unknown>).debug === "boolean"
  );
}

// 5. High-Performance HTTP REST Router using Bun.serve()
import { serve } from "bun";

serve({
  port: 3000,
  async fetch(req: Request): Promise<Response> {
    const url = new URL(req.url);

    if (req.method === "GET" && url.pathname === "/api/health") {
      return Response.json({ status: "ok", timestamp: new Date().toISOString() });
    }

    if (req.method === "POST" && url.pathname === "/api/echo") {
      const body = await req.json();
      return Response.json({ received: body }, { status: 201 });
    }

    return Response.json({ error: "Route Not Found" }, { status: 404 });
  },
});
```