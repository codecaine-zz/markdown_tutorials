# Bun All-in-One JavaScript & TypeScript Runtime Guide

`bun` is an ultra-fast, modern all-in-one JavaScript and TypeScript toolkit written from scratch in Zig (powered by Apple's JavaScriptCore engine). Designed as a high-performance replacement for Node.js, `npm`, `npx`, `tsc`, and `jest`, `bun` delivers instant startup times, built-in TypeScript/JSX execution, an ultra-fast package manager, and a native bundler and test runner.

---

## 📚 Table of Contents

1. [Overview & `Node.js` vs `Bun` Comparison](#overview--nodejs-vs-bun-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Running JavaScript & TypeScript Directly (`bun run`)](#running-javascript--typescript-directly-bun-run)
4. [High-Speed Package Management (`bun install`, `bun add`)](#high-speed-package-management-bun-install-bun-add)
5. [Running Scripts & Ephemeral Packages (`bunx`)](#running-scripts--ephemeral-packages-bunx)
6. [Built-in Native Test Runner (`bun test`)](#built-in-native-test-runner-bun-test)
7. [Bundling Applications & Libraries (`bun build`)](#bundling-applications--libraries-bun-build)
8. [High-Performance Native APIs (`Bun.serve`, `Bun.file`, `Bun.password`)](#high-performance-native-apis-bunserve-bunfile-bunpassword)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `Node.js` vs `Bun` Comparison

Node.js development often requires a constellation of tools (`ts-node`/`tsx` for TypeScript, `npm`/`pnpm` for packages, `webpack`/`esbuild` for bundling, and `jest`/`vitest` for testing). `bun` replaces them all.

| Feature | Node.js Ecosystem | Bun |
| :--- | :--- | :--- |
| **Engine** | V8 (C++) | JavaScriptCore (Zig) |
| **TypeScript / JSX** | Requires transpile / `ts-node` | Native (zero-config, instant) |
| **Package Manager** | `npm` / `yarn` / `pnpm` (separate) | Built-in `bun install` (25x faster) |
| **Test Runner** | `jest` / `vitest` (separate) | Built-in `bun test` |
| **Bundler** | `webpack` / `esbuild` (separate) | Built-in `bun build` |
| **Hot Reloading** | `nodemon` / `tsx --watch` | Built-in `bun --watch` / `bun --hot` |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install bun
```

### 2. Install via Official Script

```bash
curl -fsSL https://bun.sh/install | bash
```

### 3. Verify Installation

```bash
bun --version
```

---

## 🚀 Running JavaScript & TypeScript Directly

`bun` executes `.js`, `.ts`, `.jsx`, and `.tsx` files directly with zero build step:

```bash
# Execute TypeScript file directly
bun run server.ts

# Run with hot reloading (preserves application state)
bun --hot index.ts

# Run with file system auto-restart
bun --watch app.ts
```

---

## 📦 High-Speed Package Management (`bun install`, `bun add`)

`bun` reads standard `package.json` files and creates an ultra-compact binary lockfile (`bun.lockb` or `bun.lock`):

```bash
# Install all dependencies in a project (replaces npm install)
bun install

# Add production package (replaces npm i <package>)
bun add express @prisma/client

# Add development dependency (replaces npm i -D <package>)
bun add -d typescript @types/node vitest

# Remove dependency
bun remove express
```

---

## ⚡ Running Scripts & Ephemeral Packages (`bunx`)

Execute packages from npm registry without installing them globally (drop-in replacement for `npx`):

```bash
# Initialize a modern Next.js or Vite app
bunx create-vite my-app --template react-ts

# Run Prisma migrations
bunx prisma migrate dev

# Run Prettier code formatting
bunx prettier --write .
```

---

## 🧪 Built-in Native Test Runner (`bun test`)

`bun test` is a Jest/Vitest-compatible test runner with built-in mocking and snapshot testing that runs up to 30x faster:

```bash
# Run all *.test.ts and *.spec.ts files
bun test

# Run tests in watch mode
bun test --watch

# Filter tests by filename pattern
bun test user.test.ts
```

### Example Test File (`math.test.ts`):

```typescript
import { expect, test, describe } from "bun:test";

describe("Arithmetic", () => {
  test("adds numbers correctly", () => {
    expect(2 + 2).toBe(4);
  });
});
```

---

## 🏗️ Bundling Applications & Libraries (`bun build`)

Bundle client-side applications or backend scripts into single standalone output files:

```bash
# Bundle frontend app for browser
bun build ./src/index.tsx --outdir ./dist --minify

# Compile script into a self-contained executable binary
bun build ./cli.ts --compile --outfile my-cli-tool
```

---

## ⚡ High-Performance Native APIs

`bun` includes native HTTP and file APIs that outperform standard Node `http` and `fs`:

### High-Speed HTTP Server (`server.ts`):

```typescript
// Handles 100,000+ requests/sec out of the box
Bun.serve({
  port: 3000,
  fetch(req) {
    const url = new URL(req.url);
    if (url.pathname === "/") return new Response("Welcome to Bun!");
    if (url.pathname === "/json") return Response.json({ status: "success", uptime: process.uptime() });
    return new Response("Not Found", { status: 404 });
  },
});

console.log("Server running at http://localhost:3000");
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Run TypeScript file** | `bun run <file.ts>` |
| **Run with Hot Reload** | `bun --hot <file.ts>` |
| **Install dependencies** | `bun install` |
| **Add package** | `bun add <package>` |
| **Run npx command** | `bunx <cmd>` |
| **Run tests** | `bun test` |
| **Compile to binary** | `bun build <file.ts> --compile --outfile <name>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Bun shortcuts
alias b='bun'
alias bx='bunx'
alias bi='bun install'
alias ba='bun add'
alias bt='bun test'
alias bdev='bun --hot'
```

---

## 🗑️ Uninstallation

To remove `bun`:

```bash
brew uninstall bun
```
