# AI Coding Agent Specification Guide: Solo Architecture and Composition

A ready-to-use master specification template you can drop into any project as `AGENTS.md`, `CLAUDE.md`, or `.cursorrules`. This guide instructs AI coding agents (Claude, Cursor, Copilot, Antigravity, ChatGPT, Codex) to write code adhering strictly to **Single-Use Principles**, **Modular Composition**, and **Solo Developer Ergonomics**.

---

## Table of Contents

1. [Why AI Agents Need an Architectural Spec](#why-ai-agents-need-an-architectural-spec)
2. [Where to Place This Spec in Your Projects](#where-to-place-this-spec-in-your-projects)
3. [The Master Agent Specification (Copy-Paste Template)](#the-master-agent-specification-copy-paste-template)
4. [Breakdown of Enforced Agent Behaviors](#breakdown-of-enforced-agent-behaviors)
   - [Strict Doer vs Coordinator Separation](#strict-doer-vs-coordinator-separation)
   - [Enforcing Composition Over Inheritance](#enforcing-composition-over-inheritance)
   - [Data-Oriented Programming (Plain Data First)](#data-oriented-programming-plain-data-first)
   - [Feature-First Folder Colocation](#feature-first-folder-colocation)
   - [100% API Documentation Coverage & RAD Examples](#100-api-documentation-coverage--rad-examples)
5. [Explicitly Forbidden Agent Anti-Patterns](#explicitly-forbidden-agent-anti-patterns)
6. [How to Prompt and Enforce Compliance](#how-to-prompt-and-enforce-compliance)

---

## Why AI Agents Need an Architectural Spec

By default, Large Language Models (LLMs) are trained on massive corpuses of enterprise code repositories. As a result, when an AI agent is asked to write a simple feature, its default instinct is often to generate:
- 5 abstract interfaces and generic factory classes for a 20-line feature.
- Monolithic 150-line "God Functions" that combine HTTP routing, validation, database queries, and email sending.
- Deep inheritance hierarchies (`BaseController -> CrudController -> UserController`).
- Premature abstractions and multi-layered folder sprawl (`controllers/`, `services/`, `repositories/`, `dtos/`, `mappers/`).

For a solo developer, this default AI behavior produces **toxic technical debt**. 

When you provide a clear, unambiguous specification document in your repository root, the AI agent is constrained to write flat, composable, single-purpose code that is easy to understand, easy to test, and easy to delete.

---

## Where to Place This Spec in Your Projects

Different AI coding tools look for instructions in specific standardized files in your repository root:

| AI Tool / Platform | Recommended Filename | Path in Repository |
| :--- | :--- | :--- |
| **Antigravity / Gemini CLI** | `AGENTS.md` or `.agents/rules/` | `./AGENTS.md` or `.agents/rules/coding-style.md` |
| **Cursor IDE** | `.cursorrules` or `.cursor/rules/` | `./.cursorrules` or `.cursor/rules/*.mdc` |
| **Claude Code (Anthropic CLI)** | `CLAUDE.md` | `./CLAUDE.md` |
| **GitHub Copilot / VS Code** | `.github/copilot-instructions.md` | `./.github/copilot-instructions.md` |
| **Windsurf / Cascade** | `.windsurfrules` | `./.windsurfrules` |
| **Universal Fallback** | `SPEC.md` / `PROMPT_GUIDE.md` | `./SPEC.md` (Referenced in your system prompt) |

> [!TIP]
> You can create a symlink or keep an identical copy as `AGENTS.md` and `CLAUDE.md` in your repo root so every tool honors the same architectural standards.

---

## The Master Agent Specification (Copy-Paste Template)

Copy the markdown block below directly into your repository root as `AGENTS.md` or `CLAUDE.md`:

````markdown
# Project Architecture and Coding Guidelines for AI Agents

## Role & Core Philosophy
You are a senior pragmatic software engineer pair-programming with a solo developer.
Our team constraint is simple: **One human maintains this entire codebase.**

Your highest priority is to minimize cognitive load, maintain velocity, and produce code that is:
1. **Single-Purpose (Single Responsibility)**: Small, focused building blocks that do exactly one job.
2. **Composed, Not Inherited**: Flat structures and pure functions composed together like LEGO bricks.
3. **Data-Oriented**: Plain records, dicts, and JSON over complex, stateful OOP hierarchies.
4. **Obvious & Readable**: Code that reads like a recipe from top to bottom.
5. **Easy to Delete**: High cohesion within features; zero sprawling cross-module entanglements.

---

## 1. Single Responsibility: The Doer vs Coordinator Pattern
Every function you write must strictly belong to one of two categories:

### A. Doers (Workers)
- Pure logic, calculation, validation, or single I/O actions (e.g., hash a password, calculate sales tax, execute one SQL query).
- **Target Size**: 5 to 25 lines.
- **Constraints**: No hidden side effects. Do not call other business logic domains.
- Must be independently testable without mocks or databases.

### B. Coordinators (Orchestrators)
- Functions that coordinate Doers to complete a user-facing business flow.
- **Rules**: Coordinators do NOT perform heavy math, string parsing, or low-level SQL inline. They simply call a sequence of Doers in logical order.
- Must read linearly from top to bottom like an English recipe.

---

## 2. Composition Over Inheritance (Strictly Enforced)
- **NO deep class hierarchies**: Never inherit more than 1 level deep.
- **NO abstract template base classes**: Do not create `AbstractBaseService` or `GenericManager`.
- **Prefer Functions Over Classes**: If a class does not hold mutable internal state (e.g., a connection pool or UI control), write it as a collection of exported pure functions and plain types.
- **Compose Capabilities**: Combine data types and functions using intersection types (`TypeA & TypeB`), protocols, interfaces, or pipeline helpers (`pipe()`).

---

## 3. Data-Oriented Programming (Plain Data First)
- Keep data and logic completely separate.
- Represent all entities as plain data:
  - TypeScript: `type` and `interface`
  - Python: `dataclasses`, `TypedDict`, or Pydantic models
  - Go: plain `struct`
- Avoid "Rich Domain Models" that bind database queries directly to entity instances (avoid ActiveRecord/God models where `user.save()` triggers side effects).

---

## 4. Colocation and Folder Structure (Feature-First)
Organize code by **Feature / Business Domain**, NEVER by technical layer:

```
# ✅ REQUIRED: Feature-First Colocation
src/
  features/
    auth/
      authRoutes.ts
      authDoers.ts
      authCoordinator.ts
      auth.test.ts
    billing/
      checkout.ts
      invoices.ts
      stripeWebhook.ts
      billing.test.ts
  shared/
    db.ts
    email.ts
```

- **Rule**: If a feature is deprecated, deleting its single folder must remove 100% of its code with zero orphan files across the repo.

---

## 5. Pragmatic Engineering Rules
1. **KISS**: Favor standard SQL, boring monoliths, and built-in runtime libraries over third-party framework magic.
2. **YAGNI**: Implement ONLY what is needed for the immediate prompt. Do not write abstractions for hypothetical future requirements.
3. **WET Over Premature DRY (The Rule of Three)**: Duplicate code twice before abstracting. It is far cheaper to duplicate code than to maintain the wrong abstraction.
4. **Descriptive Errors**: Every thrown exception must include full debugging context:
   - Bad: `throw new Error("Invalid request")`
   - Good: `throw new Error("[ChargeCard] Customer ${id} card declined: ${reason}")`
5. **No Magic Strings**: Use string literal union types or enums for status codes and event types.

---

## 6. Forbidden Patterns (DO NOT GENERATE)
The following enterprise anti-patterns are strictly forbidden:
- ❌ Monolithic "God Functions" exceeding 40 lines that combine validation, DB, and notifications.
- ❌ Abstract Factory patterns, Strategy classes, or Visitor patterns for basic logic.
- ❌ Generic multi-layer indirection (`Controllers -> Services -> Repositories -> Mappers -> DTOs`).
- ❌ Class inheritance trees deeper than 1 level (`class C extends B extends A`).
- ❌ Layer-first folder structures (`controllers/`, `models/`, `services/`).
- ❌ Premature microservices, Kafka/RabbitMQ queues, or distributed caches unless explicitly requested.

---

## 7. Output Format Expectations
When providing solutions:
1. Show the single-use Doers first.
2. Show the Coordinator that orchestrates them.
3. Keep code blocks self-contained and runnable.
4. Avoid unnecessary comments that merely restate the code; explain the *why* or edge-case decisions instead.

---

## 8. 100% API Documentation Coverage & RAD Examples (Strictly Enforced)
Every feature and API in this codebase exists for a solo developer building high-velocity software. To maximize velocity and eliminate context-switching:

1. **Zero Documentation Drift**: Whenever any function, coordinator, doer, option, CLI flag, or type signature is added or updated, documentation must be updated in the exact same turn.
2. **100% API Surface Coverage**: Every exported function, data interface, and configuration option must be documented. No "left as an exercise to the reader" or undocumented flags.
3. **RAD (Rapid Application Development) Code Blocks**:
   - Provide concrete, copy-and-pasteable TypeScript code blocks for the **entire** API surface.
   - Developers must be able to copy a recipe directly into their application and have it work immediately without guessing imports, types, or argument structures.
4. **Three-Tier Documentation Architecture**:
   - **Feature README (`src/features/<feature>/README.md`)**: Full local documentation containing CLI flags, interactive TUI shortcuts (if any), full TypeScript types, and end-to-end runnable recipes.
   - **Central Cookbook (`docs/API.md`)**: Unified API reference with cross-feature integration recipes and complete types.
   - **Root README (`README.md`)**: CLI cheatsheet and workflow overview covering all commands and flags.
5. **Executable Verification**:
   - All documentation code recipes must be mirrored in automated tests (e.g., `src/test_readme_recipes.test.ts`) to guarantee they never rot, fail, or fall out of sync with active code.
````

---

## Breakdown of Enforced Agent Behaviors

Here is how this specification changes the AI agent's coding behavior in practice:

### Strict Doer vs Coordinator Separation

When asked to implement an endpoint (e.g. *“Add an endpoint to cancel a subscription”*), an unconstrained AI will write a 90-line handler containing database transactions, Stripe API calls, status checks, and email dispatchers in one tangled blob.

With this specification, the AI agent automatically breaks the task down:
1. `validateCancellationEligibility(subscription)` (Doer)
2. `cancelStripeSubscription(stripeSubId)` (Doer)
3. `recordCancellationInDb(userId, reason)` (Doer)
4. `sendCancellationSurveyEmail(userEmail)` (Doer)
5. `cancelSubscriptionHandler(req, res)` (Coordinator)

If Stripe changes its API, you only touch `cancelStripeSubscription`. The database logic and email logic remain untouched.

---

### Enforcing Composition Over Inheritance

Without rules, AI agents love generating class structures:
```typescript
// ❌ What AI generates by default:
class BaseRepository<T> { ... }
class UserRepository extends BaseRepository<User> { ... }
class EnterpriseUserRepository extends UserRepository { ... }
```

With this specification, the AI generates composable functions:
```typescript
// ✅ What AI generates with this spec:
export async function findUserById(db: DbConnection, id: string): Promise<User | null> {
  return db.query("SELECT * FROM users WHERE id = $1", [id]);
}

export async function updateUserStatus(db: DbConnection, id: string, status: UserStatus): Promise<void> {
  await db.query("UPDATE users SET status = $1 WHERE id = $2", [status, id]);
}
```

---

### Data-Oriented Programming (Plain Data First)

Agents frequently wrap data in classes with getters, setters, and mutation methods. This specification forces agents to treat data as **transparent values**:

```python
# ✅ What the agent generates with this spec:
from dataclasses import dataclass
from typing import Optional

@dataclass(frozen=True)
class CartItem:
    id: str
    name: str
    price_cents: int
    quantity: int

@dataclass(frozen=True)
class Order:
    order_id: str
    customer_id: str
    items: list[CartItem]
    coupon_code: Optional[str] = None
```
Because the data is plain and immutable (`frozen=True`), any function can process it safely without worrying about hidden mutations.

---

### Feature-First Folder Colocation

Instead of scattering a feature's files across 5 directories, the agent places all related files in one cohesive location:

```
src/
  features/
    notifications/
      notificationChannels.ts   # Doers for SMS, Email, Push
      notificationTemplate.ts   # Pure formatting logic
      sendNotification.ts       # Coordinator
      notification.test.ts      # Unit & integration tests
```

---

### 100% API Documentation Coverage & RAD Examples

For a solo developer, context-switching is fatal to productivity. If an AI writes a clean feature but leaves configuration options undocumented, omits imports, or leaves pseudo-code placeholders like `// ...initialize client here`, the developer has to stop and inspect the source code to figure out how to call it.

Under this rule, the AI agent is required to deliver **100% complete, runnable, and test-verified documentation** in the exact same turn that code is generated or modified.

#### What AI Generates by Default vs. With This Spec

| Behavior | ❌ Without Spec (Default AI) | ✅ With 100% Documentation Rule |
| :--- | :--- | :--- |
| **API Coverage** | Explains the happy path; leaves options and edge cases undocumented | Documents 100% of exported types, options, flags, and return values |
| **Code Snippets** | Incomplete snippets with missing imports and `...` ellipsis | Self-contained, copy-and-pasteable TypeScript recipes ready to run |
| **CLI & TUI Flags** | Implements CLI flags in code but omits them from `README.md` | Documents every flag, default value, and keyboard shortcut in a table |
| **Recipe Verification** | Untested markdown snippets that rot as APIs evolve | Markdown recipes are mirrored and tested in `test_readme_recipes.test.ts` |

#### Concrete Example: Agent-Generated Feature README

Here is an example of what the AI agent produces when implementing a new feature (e.g., `src/features/billing/README.md`):

````markdown
# Billing Feature (`src/features/billing`)

Coordinates Stripe subscription lifecycles, webhook event processing, and customer invoice generation.

## 1. Quickstart Recipe (Copy & Paste)

```typescript
import { createBillingCoordinator } from "./billingCoordinator";
import { type BillingConfig, type CheckoutSessionResult } from "./billingTypes";

// 1. Initialize coordinator with configuration
const config: BillingConfig = {
  stripeSecretKey: process.env.STRIPE_SECRET_KEY!,
  webhookSecret: process.env.STRIPE_WEBHOOK_SECRET!,
  currency: "usd",
  maxRetries: 3,
};

const billing = createBillingCoordinator(config);

// 2. Create a customer checkout session
const session: CheckoutSessionResult = await billing.createCheckoutSession({
  customerId: "cus_987654321",
  planId: "price_pro_monthly",
  successUrl: "https://app.example.com/dashboard?billing=success",
  cancelUrl: "https://app.example.com/dashboard?billing=cancelled",
});

console.log("Redirect user to:", session.checkoutUrl);
```

## 2. Complete Exported Types & Signatures

```typescript
export interface BillingConfig {
  readonly stripeSecretKey: string;
  readonly webhookSecret: string;
  readonly currency: "usd" | "eur" | "gbp";
  readonly maxRetries?: number; // Defaults to 3 if omitted
}

export interface CreateCheckoutParams {
  readonly customerId: string;
  readonly planId: string;
  readonly successUrl: string;
  readonly cancelUrl: string;
}

export interface CheckoutSessionResult {
  readonly sessionId: string;
  readonly checkoutUrl: string;
  readonly expiresAt: number; // Unix timestamp in seconds
}
```

## 3. CLI Commands and Options

| Flag | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `--customer-id <id>` | `string` | *(Required)* | Stripe customer identifier |
| `--plan <tier>` | `string` | `"pro_monthly"` | Subscription plan identifier |
| `--currency <code>` | `string` | `"usd"` | Currency code (`usd`, `eur`, `gbp`) |
| `--dry-run` | `boolean` | `false` | Validates session params without creating a live Stripe session |

## 4. Executable Verification Test

To ensure recipes never rot, the agent creates `src/features/billing/test_readme_recipes.test.ts`:

```typescript
import { describe, it, expect } from "vitest";
import { createBillingCoordinator } from "./billingCoordinator";

describe("Billing README Quickstart Recipe", () => {
  it("compiles and runs the exact recipe published in the README", async () => {
    const billing = createBillingCoordinator({
      stripeSecretKey: "sk_test_mock_key_12345",
      webhookSecret: "whsec_mock_secret_12345",
      currency: "usd",
      maxRetries: 1,
    });

    expect(billing).toBeDefined();
    expect(typeof billing.createCheckoutSession).toBe("function");
  });
});
```
````

---

## Explicitly Forbidden Agent Anti-Patterns

This table highlights why each forbidden pattern is banned for solo developers:

| Banned Pattern | Why AI Agents Love It | Why It Breaks Solo Developers |
| :--- | :--- | :--- |
| **"God Functions"** | Easy to spit out in one prompt response | Impossible to test in pieces; 1 bug breaks everything |
| **Abstract Factory Patterns** | Mimics old enterprise Java libraries | Adds 5 files of boilerplate before writing any real logic |
| **Deep Inheritance** | Feels organized in theory | Fragile base class problem; changes at the root break children |
| **Premature DRY Abstractions** | Trained to eliminate duplicate characters | Couples unrelated features into an unwieldy mega-function |
| **Layer-First Folders** | Default MVC tutorial style | Forces jumping across 6 folders to edit or delete 1 feature |
| **Documentation Drift & Ghost Flags** | Fast to code and skip docs | Forces developer to inspect source code to discover options or fix broken snippets |
| **Incomplete Pseudo-Code Snippets** | Saves LLM tokens with `...` placeholders | Forces context-switching to guess missing imports, types, or configs |

---

## How to Prompt and Enforce Compliance

To make sure your AI coding agent follows this spec on every request:

### 1. In Antigravity / Gemini CLI
Save the template as `AGENTS.md` in your project root or in `.gemini/config/rules/solo-architecture.md`. Antigravity automatically loads and enforces these rules in planning and execution phases.

### 2. In Cursor / Claude Code
Add this line to your project's `.cursorrules` or `CLAUDE.md`:
```markdown
Always read and strictly follow the architecture and single-use guidelines in AGENTS.md.
```

### 3. Quick Slash / Conversation Reminder
If an AI agent ever generates a long monolithic function, leaves APIs undocumented, or uses deep class hierarchies, use this quick reminder:

> *"Refactor this following our AGENTS.md rules: split into single-use Doers and one Coordinator, keep data flat, and provide 100% documented, copy-pasteable RAD recipes in the feature README."*

The agent will immediately dismantle the monolithic code into clean, modular building blocks and generate production-ready, test-verified documentation.
