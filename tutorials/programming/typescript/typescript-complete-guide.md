# 🎉 Beginner‑Friendly **TypeScript 5+** Tutorial – Now Powered by **Bun**  
*(All code snippets work out‑of‑the‑box with the Bun runtime. No `ts-node` or `node` needed.)*

---

## 📦 0️⃣ Install Bun  

```bash
# macOS / Linux – Homebrew
brew install oven-sh/bun/bun

# Windows – Scoop
scoop install bun

# Verify
bun --version          # → e.g. 1.1.12
```

> **Why Bun?**  
> *Fast TypeScript compilation, native ESM, built‑in test runner, and a tiny standard‑library (`Bun.file`, `Bun.write`, `import.meta.dir`, JSON imports, etc.).*  

---

## 1️⃣ Initialise a Project  

| Option | What you type | What you get |
|--------|--------------|--------------|
| **Bun’s scaffolding** | `bun init my‑app && cd my‑app` | `package.json`, `src/` folder |
| **Manual (works everywhere)** | ```bash\nmkdir ts‑starter && cd ts‑starter\nnpm init -y   # any package manager works\nbun add -d typescript   # install TS as a dev‑dependency\nbunx tsc --init   # generate tsconfig.json\n``` | Same result, but you stay in control of the layout |

> **Tip** – `bunx` runs a binary from `node_modules/.bin` (just like `npx`).  

---

## 2️⃣ Minimal `tsconfig.json` (TS 5‑ready, Bun‑friendly)

```json
{
  "compilerOptions": {
    "target": "ES2022",
    "module": "ES2022",
    "moduleResolution": "bundler",          // makes Bun’s import‑map behaviour work
    "lib": ["ES2022"],
    "outDir": "./dist",
    "rootDir": "./src",
    "strict": true,                         // all strict flags on
    "esModuleInterop": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true,
    "verbatimModuleSyntax": true,           // keep import/export exactly as written (TS 5)
    "resolveJsonModule": true               // lets you `import "./data.json"`
  },
  "include": ["src"]
}
```

> The `"verbatimModuleSyntax"` flag is a **TS 5** feature that makes the emitted JavaScript look exactly like the source – perfect for bundlers that read the import statements (Bun, Vite, etc.).  

---

## 3️⃣ npm‑style scripts that use **Bun**

```json
{
  "scripts": {
    "dev":   "bun run src/index.ts",       // run .ts directly – no ts-node
    "build": "bunx tsc",                  // compile to ./dist
    "start": "node dist/index.js",        // run the compiled output
    "test":  "bun test"                  // built‑in test runner
  }
}
```

Run any script with `bun run <script>` – e.g. `bun run dev`.

---

## 4️⃣ Your First TypeScript File – “Hello, Bun!”

Create `src/index.ts`:

```ts
// src/index.ts
console.log("👋 Hello, Bun + TypeScript!");

// 👉 type‑annotated variables
const name: string = "Alice";
const age: number = 28;
const isStudent: boolean = true;

console.log(`${name} is ${age} years old. Student? ${isStudent}`);
```

```bash
bun run dev
# → 👋 Hello, Bun + TypeScript!
# → Alice is 28 years old. Student? true
```

---

## 5️⃣ Variables & Primitive Types  

| Syntax | Example | What you see |
|--------|---------|--------------|
| `const` (never re‑assigned) | `const PI = 3.14;` | ✅ safe |
| `let` (re‑assignable) | `let counter = 0;` | ✅ |
| `var` (function‑scoped) | `var legacy = "avoid";` | ❌ discouraged |
| **Type inference** | `let inferred = 42; // number` | ✅ no manual annotation needed |
| **Explicit annotation** | `let mixed: string | null = null;` | ✅ required when type can’t be inferred |

---

## 6️⃣ Objects & Interfaces  

```ts
// src/types.ts
export interface User {
  /** Unique identifier – never changes */
  readonly id: string;
  name: string;
  /** Optional – may be omitted */
  age?: number;
  email: string;
}

// src/index.ts (continue)
import type { User } from "./types";

const alice: User = { id: "u‑01", name: "Alice", email: "alice@example.com" };
console.log(alice);
```

*Use an **interface** when you want a shape that can be *merged* later (e.g. library augmentations). Use a **type alias** for unions, mapped types, etc.*

---

## 7️⃣ Arrays, Tuples & Collections  

```ts
// Quick‑start
const nums: number[] = [1, 2, 3];
const point: [number, number] = [10, 20];          // tuple

// Read‑only literal array (great for `as const`‑free inference)
const colors = ["red", "green", "blue"] as const; // readonly tuple
type Color = typeof colors[number];               // "red" | "green" | "blue"
```

### Sets & Maps (just like in Node)

```ts
const unique = new Set<string>(["a", "b"]);
const dict = new Map<number, string>();
dict.set(1, "one");
```

---

## 8️⃣ Enums & Union (Discriminated) Types  

```ts
enum Direction { Up = 1, Down, Left, Right }
enum HttpStatus { Ok = "OK", NotFound = "NOT_FOUND" }

type OrderStatus = "pending" | "approved" | "rejected";
let status: OrderStatus = "pending";
```

### Exhaustive `switch` with a discriminated union

```ts
type Shape =
  | { kind: "circle"; radius: number }
  | { kind: "rect"; width: number; height: number };

function area(s: Shape): number {
  switch (s.kind) {
    case "circle": return Math.PI * s.radius ** 2;
    case "rect":   return s.width * s.height;
    // No `default` → TypeScript forces you to handle every case
  }
}
```

---

## 9️⃣ Functions  

```ts
// Simple typed function
function add(a: number, b: number): number {
  return a + b;
}

// Overloads (plain TS syntax)
function format(v: string): string;
function format(v: number): string;
function format(v: string | number): string {
  return String(v);
}

// JSDoc overloads – works with Bun too
/**
 * @overload
 * @param {string} value
 * @return {void}
 */
/**
 * @overload
 * @param {number} value
 * @param {number} [maximumFractionDigits]
 * @return {void}
 */
/**
 * @param {string|number} value
 * @param {number} [maximumFractionDigits]
 */
function printValue(value: string | number, maximumFractionDigits?: number) {
  if (typeof value === "number") {
    value = value.toLocaleString("en-US", { maximumFractionDigits });
  }
  console.log(value);
}
```

---

## 🔟 Advanced TS 5+ Features (Beginner‑friendly)

### 10.1 `const` Type Parameters – no more `as const`

```ts
// Before TS 5
function getNames<T extends { names: readonly string[] }>(arg: T) {
  return arg.names;
}
const namesOld = getNames({ names: ["Alice", "Bob"] as const });

// TS 5
function getNames<const T extends { names: readonly string[] }>(arg: T) {
  return arg.names;
}
const namesNew = getNames({ names: ["Alice", "Bob"] }); // ✅ exact type inferred
```

### 10.2 `satisfies` – validate a literal object without widening its type

```ts
type Config = {
  strict: boolean;
  outDir?: string;
};

const myConfig = {
  strict: true,
  outDir: "./dist",
} satisfies Config; // ✅ type‑checked, still the exact shape
```

### 10.3 Template‑Literal Types

```ts
type HexColor = `#${string}`;
function setColor(c: HexColor) {
  console.log(`Color set to ${c}`);
}
setColor("#ff00ff"); // OK
// setColor("red"); // ❌ compile error
```

---

## 1️⃣1️⃣ Object‑Oriented Programming (Classes)

```ts
class BankAccount {
  #balance = 0;                         // private field (stage‑4)
  public readonly accountNumber: string;

  constructor(accountNumber: string) {
    this.accountNumber = accountNumber;
  }

  public deposit(amount: number): void {
    if (amount > 0) this.#balance += amount;
  }

  public getBalance(): number {
    return this.#balance;
  }
}
```

### Abstract class & interface

```ts
interface Shape {
  getColor(): string;
  calculateArea(): number;
}
abstract class AbstractShape implements Shape {
  constructor(protected color: string) {}
  abstract calculateArea(): number;
  getColor() { return this.color; }
}
```

### Standardized decorators (Bun supports the official decorator API)

```ts
function loggedMethod(original: any, context: ClassMethodDecoratorContext) {
  const name = String(context.name);
  return function (this: any, ...args: any[]) {
    console.log(`→ Enter ${name}`);
    const result = original.apply(this, args);
    console.log(`← Exit ${name}`);
    return result;
  };
}

class Greeter {
  constructor(public name: string) {}

  @loggedMethod
  greet() {
    console.log(`Hello, ${this.name}!`);
  }
}
new Greeter("Bob").greet();
```

> **Bun’s experimental‑decorator flag is no longer required** – the decorator API is now part of the language (TS 5).

---

## 1️⃣2️⃣ Modules (ESM) & Barrel Files  

### `mathUtils.ts`

```ts
export const PI = 3.14159;
export function add(a: number, b: number) {
  return a + b;
}
export default function subtract(a: number, b: number) {
  return a - b;
}
```

### `src/index.ts`

```ts
import subtract, { PI, add } from "./mathUtils";

console.log(`π ≈ ${PI}`);
console.log(`2 + 3 = ${add(2, 3)}`);
console.log(`10 - 4 = ${subtract(10, 4)}`);
```

### Barrel (`src/utils/index.ts`)

```ts
export * from "./mathUtils";
export * from "./stringUtils";   // ← any other helper module
```

Now any consumer can do `import { add } from "./utils";`.

---

## 1️⃣3️⃣ File I/O with **Bun** (no `fs/promises` needed)

```ts
import { join } from "path";

/** Read a text file – relative paths are resolved from the project root */
export async function readFileContent(relPath: string): Promise<string> {
  // Bun.file returns a BunFile (a Blob subclass) – see Bun docs【14†L2-L9】【14†L12-L13】 
  const file = Bun.file(join(import.meta.dir, relPath));
  return await file.text();           // reads as string
}

/** Write a string to a file – fastest builtin API【15†L2-L7】【15†L14-L17】 */
export async function writeFileContent(relPath: string, data: string): Promise<number> {
  const file = Bun.file(join(import.meta.dir, relPath));
  return await Bun.write(file, data); // returns bytes written
}
```

> **`import.meta.dir`** gives the absolute directory of the current module (Bun feature)【17†L12-L14】.  
> **No need for `node:fs`** – Bun’s `Bun.file` and `Bun.write` are fully typed.

### JSON handling

*Direct import (no extra loader)*  

```ts
import cfg from "./config.json" assert { type: "json" }; // TypeScript sees the shape
console.log(cfg.version);
```

*Or read‑and‑parse at runtime*  

```ts
export async function readConfig<T>(relPath: string): Promise<T> {
  const file = Bun.file(join(import.meta.dir, relPath));
  return await file.json(); // parses JSON → value of type T
}
```

Both approaches are type‑safe thanks to the `"resolveJsonModule": true` flag in `tsconfig.json`.

---

## 1️⃣4️⃣ Error Handling & Custom Errors  

```ts
class AppError extends Error {
  constructor(message: string, public readonly code: number = 500) {
    super(message);
    this.name = "AppError";
  }
}

// Type guard
function isAppError(e: unknown): e is AppError {
  return e instanceof AppError;
}

try {
  throw new AppError("Database failed", 503);
} catch (e) {
  if (isAppError(e)) console.error(`⚠️ ${e.code}: ${e.message}`);
  else console.error("Unexpected:", e);
}
```

---

## 1️⃣5️⃣ **Testing** – Bun’s Built‑in Test Runner  

Create `src/add.test.ts`:

```ts
import { expect, test } from "bun:test";

function add(a: number, b: number) {
  return a + b;
}

test("basic addition", () => {
  expect(add(2, 3)).toBe(5);
});

test("async example", async () => {
  const result = await Promise.resolve(add(10, 20));
  expect(result).toEqual(30);
});
```

Run it:

```bash
bun test
# → PASS: basic addition
# → PASS: async example
```

*The snippets above are taken directly from Bun’s docs*【18†L104-L110】【18†L142-L148】.

---

## 1️⃣6️⃣ Performance & Build Tips (Bun + TS)

| Goal | How to achieve it |
|------|-------------------|
| **Fast dev cycles** | `bun run dev` reads TS on‑the‑fly (no separate compile step). |
| **Incremental builds** | Run `bunx tsc --watch` – only changed files re‑emit. |
| **Tree‑shaking** | Keep `"moduleResolution": "bundler"` and `"verbatimModuleSyntax": true`. |
| **Small bundles** | Use `as const` / `const` type parameters to keep literal types. |
| **No stray JS** | `"noEmitOnError": true` (default with `strict`). |

---

## 1️⃣7️⃣ **Best‑Practice Checklist** (Bun + TS)  

| ✅ | Practice | How to enforce |
|---|----------|----------------|
| 1 | **Strict mode** (`strict: true`) | In `tsconfig.json` |
| 2 | **Never `any`** – prefer `unknown` + guards | ESLint rule `@typescript-eslint/no-explicit-any` |
| 3 | **Prefer `const` over `let`** | Code reviews / IDE hints |
| 4 | **Use `as const` or `const` type parameters** for literal inference | TS 5 features |
| 5 | **`readonly` for immutable public fields** | `readonly` keyword |
| 6 | **Validate external data** (`unknown` → guard) | Write `isX` type‑guards |
| 7 | **Leverage Bun’s `Bun.file` / `Bun.write`** instead of Node `fs` | Faster syscalls |
| 8 | **Import JSON directly** (`import data from "./x.json"`) | `"resolveJsonModule": true` |
| 9 | **Use `import.meta.dir` for portable file paths** | No hard‑coded `__dirname` |
|10 | **Run tests with `bun test`** (built‑in, no extra deps) | `npm run test` → `bun test` |
|11 | **Add lint with `eslint` + `@typescript-eslint`** | `bun add -d eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin` |
|12 | **Keep one source of truth for config** – use `satisfies` when you need a literal‑checked object | `const cfg = { … } satisfies Config;` |
|13 | **Enable `verbatimModuleSyntax`** for clear import/export | Already in `tsconfig.json` |
|14 | **Use standardized decorators** (no experimental flag) | As shown in the decorator section |
|15 | **Pin Bun version** (`"bun": "^1.1.0"` in `package.json`) | Guarantees reproducible builds |

---

## 🎉 Wrap‑Up  

You now have:

* A **Bun‑powered TypeScript project** that you can run, test, and build with a single command.  
* **Modern TS 5 features** (`const` type params, `satisfies`, template‑literal types) shown in clear, beginner‑friendly snippets.  
* **Bun‑specific APIs** (`Bun.file`, `Bun.write`, `import.meta.dir`, native JSON imports) that replace the usual Node `fs` boilerplate.  
* A **built‑in test runner** (`bun:test`) so you don’t need Jest or other heavy test frameworks.  

Happy coding with **TypeScript 5 + Bun**! 🚀  