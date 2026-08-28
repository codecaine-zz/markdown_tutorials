# Drizzle ORM Type-Safe TypeScript Database Guide

`Drizzle ORM` is a modern, lightweight, type-safe Object-Relational Mapper (ORM) for TypeScript and JavaScript. Unlike heavyweight ORMs with custom query engines, Drizzle provides a thin, zero-overhead abstraction layer that mirrors standard SQL while delivering 100% compile-time type safety, automated schema migrations (`drizzle-kit`), and support for PostgreSQL, MySQL, SQLite, and serverless databases.

---

## 📚 Table of Contents

1. [Overview & `Prisma` vs `Drizzle` Comparison](#overview--prisma-vs-drizzle-comparison)
2. [Prerequisites & Package Installation](#prerequisites--package-installation)
3. [Declaring Database Schemas (`schema.ts`)](#declaring-database-schemas-schemats)
4. [Setting Up Database Connections (Postgres / SQLite)](#setting-up-database-connections-postgres--sqlite)
5. [CRUD Operations: Insert, Select, Update, Delete](#crud-operations-insert-select-update-delete)
6. [Advanced Queries: Joins, Aggregations & Prepared Statements](#advanced-queries-joins-aggregations--prepared-statements)
7. [Relational Queries (Prisma-Style Nested Queries)](#relational-queries-prisma-style-nested-queries)
8. [Schema Migrations & Prototyping (`drizzle-kit`)](#schema-migrations--prototyping-drizzle-kit)
9. [Everyday Cheat Sheet & Useful Snippets](#everyday-cheat-sheet--useful-snippets)

---

## 🔍 Overview & `Prisma` vs `Drizzle` Comparison

| Feature | Prisma | Drizzle ORM |
| :--- | :--- | :--- |
| **Architecture** | Heavy Rust query engine binary | Zero dependencies, pure TypeScript |
| **SQL Closeness** | Custom abstraction | Direct SQL-like builder syntax |
| **Cold Starts (Serverless)** | Slower (starts binary) | Instant (~0ms overhead) |
| **Type Safety** | Generated `.prisma` types | Inferred native TypeScript types |
| **Bundle Size** | ~15MB+ | ~30KB |

---

## ⚙️ Prerequisites & Package Installation

Install Drizzle ORM along with database drivers and the `drizzle-kit` CLI:

```bash
# For PostgreSQL (using node-postgres / pg)
npm install drizzle-orm pg @types/pg
npm install -D drizzle-kit tsx

# Or with Bun:
bun add drizzle-orm pg
bun add -d drizzle-kit @types/pg
```

---

## 📐 Declaring Database Schemas (`src/schema.ts`)

Define your database tables and relations directly in TypeScript:

```typescript
import { pgTable, serial, text, varchar, timestamp, integer } from "drizzle-orm/pg-core";
import { relations } from "drizzle-orm";

// Users Table
export const users = pgTable("users", {
  id: serial("id").primaryKey(),
  name: text("name").notNull(),
  email: varchar("email", { length: 255 }).notNull().unique(),
  createdAt: timestamp("created_at").defaultNow().notNull(),
});

// Posts Table
export const posts = pgTable("posts", {
  id: serial("id").primaryKey(),
  title: text("title").notNull(),
  content: text("content"),
  authorId: integer("author_id").references(() => users.id, { onDelete: "cascade" }),
  createdAt: timestamp("created_at").defaultNow().notNull(),
});

// Relationships
export const usersRelations = relations(users, ({ many }) => ({
  posts: many(posts),
}));

export const postsRelations = relations(posts, ({ one }) => ({
  author: one(users, {
    fields: [posts.authorId],
    references: [users.id],
  }),
}));
```

---

## 🔌 Setting Up Database Connections (`src/db.ts`)

```typescript
import { drizzle } from "drizzle-orm/node-postgres";
import { Pool } from "pg";
import * as schema from "./schema";

const pool = new Pool({
  connectionString: process.env.DATABASE_URL || "postgres://postgres:password@localhost:5432/mydb",
});

export const db = drizzle(pool, { schema });
```

---

## 🚀 CRUD Operations

### 1. Insert Rows

```typescript
import { db } from "./db";
import { users } from "./schema";

// Insert single row and return inserted record
const [newUser] = await db
  .insert(users)
  .values({
    name: "Alice Johnson",
    email: "alice@example.com",
  })
  .returning();

console.log("Created user:", newUser.id);
```

### 2. Select & Filter Queries

```typescript
import { eq, like, desc } from "drizzle-orm";

// Query with WHERE clause and ordering
const allUsers = await db
  .select()
  .from(users)
  .where(like(users.email, "%@example.com"))
  .orderBy(desc(users.createdAt))
  .limit(10);
```

### 3. Update & Delete Rows

```typescript
// Update record
await db
  .update(users)
  .set({ name: "Alice J." })
  .where(eq(users.id, 1));

// Delete record
await db.delete(users).where(eq(users.id, 1));
```

---

## 🌿 Relational Queries (Nested Includes)

Query associated relations effortlessly without manual SQL joins:

```typescript
const userWithPosts = await db.query.users.findMany({
  where: (users, { eq }) => eq(users.name, "Alice Johnson"),
  with: {
    posts: true,
  },
});
```

---

## 🛠️ Schema Migrations & Prototyping (`drizzle-kit`)

Create a `drizzle.config.ts` in your project root:

```typescript
import { defineConfig } from "drizzle-kit";

export default defineConfig({
  schema: "./src/schema.ts",
  out: "./drizzle",
  dialect: "postgresql",
  dbCredentials: {
    url: process.env.DATABASE_URL!,
  },
});
```

### Essential CLI Commands:

```bash
# Push schema changes directly to DB (fast prototyping)
npx drizzle-kit push

# Generate migration SQL files
npx drizzle-kit generate

# Run database migrations
npx drizzle-kit migrate

# Launch Drizzle Studio web GUI for visual database browsing
npx drizzle-kit studio
```

---

## 📋 Everyday Cheat Sheet & Useful Snippets

### Quick Reference Table

| Task | Snippet |
| :--- | :--- |
| **Insert & Return** | `db.insert(table).values({...}).returning()` |
| **Select with Join** | `db.select().from(t1).leftJoin(t2, eq(t1.id, t2.fk))` |
| **Count Rows** | `db.select({ count: count() }).from(table)` |
| **Push Schema** | `npx drizzle-kit push` |
| **Launch Studio GUI** | `npx drizzle-kit studio` |
