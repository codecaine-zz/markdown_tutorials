# Programming Styles for Solo Developers: Single-Use Principles and Composition

A practical, pragmatic guide to writing clean, maintainable, and resilient software as an independent or solo developer. Learn why single-purpose functions, modular composition, and flat architectures beat complex enterprise design patterns when you are the sole creator and maintainer of a codebase.

---

## Table of Contents

1. [The Solo Developer Reality and Cognitive Load](#the-solo-developer-reality-and-cognitive-load)
2. [Single Responsibility and Single-Use Principles](#single-responsibility-and-single-use-principles)
3. [Composition Over Inheritance](#composition-over-inheritance)
4. [Survey of Core Programming Styles and Paradigms](#survey-of-core-programming-styles-and-paradigms)
5. [The Solo Developer Architecture Playbook](#the-solo-developer-architecture-playbook)
6. [Real-World Refactoring: Enterprise Bloat vs Composed Simplicity](#real-world-refactoring-enterprise-bloat-vs-composed-simplicity)
7. [Functional Pipelines and Middleware Composition](#functional-pipelines-and-middleware-composition)
8. [Testing and Maintainability for One-Person Teams](#testing-and-maintainability-for-one-person-teams)
9. [Summary Cheat Sheet and Mental Checklist](#summary-cheat-sheet-and-mental-checklist)

---

## The Solo Developer Reality and Cognitive Load

When building software alone—whether you are an indie hacker, a solo founder, a freelancer, or the sole engineer on an internal tool—your constraints are fundamentally different from a 50-person engineering team at a Fortune 500 company.

### The Big Company vs Solo Developer Divide

| Factor | 50-Person Engineering Team | Solo Developer |
| :--- | :--- | :--- |
| **Primary Goal** | Prevent engineers from breaking each other's code | Ship features quickly and maintain high velocity |
| **Code Review** | Mandatory pull requests, strict gates, committee decisions | Self-review, automated tests, instant deployment |
| **Architecture** | Microservices, deep abstractions, strict interfaces | Monolith, flat modules, direct and readable flow |
| **Biggest Risk** | Communication breakdown and merge conflicts | **Cognitive overload** and abandonment of messy code |
| **Cost of Wrong Code** | High friction across teams | High rewrite time if tangled, zero if modular |

### The Cognitive Load Budget
As a solo developer, your most scarce resource is **mental bandwidth**. 

You do not have a dedicated DevOps engineer, QA tester, product manager, or database administrator. You wear every hat simultaneously. If you write code with:
- Deep 6-level class inheritance hierarchies,
- Abstract factory builders that hide where database queries actually run,
- Over-engineered state managers with 12 boilerplate files for a single form input,

...you will return to that code in 4 months and spend 3 hours just trying to remember how data flows from point A to point B.

> [!TIP]
> **The Golden Rule of Solo Programming:**  
> Write code that is **easy to delete**, **easy to replace**, and **obvious to understand** after a 6-month vacation. The best code is code you can read like a recipe from top to bottom.

---

## Single Responsibility and Single-Use Principles

The **Single Responsibility Principle (SRP)** is often taught with academic jargon: *"A class should have one, and only one, reason to change."*

For a solo developer, let's translate that into plain English:

> **The Single-Use Rule:**  
> A function or module should **do exactly one job, do it completely, and do nothing else**.

### The Swiss Army Knife Trap vs The Kitchen Knife
Think of physical tools:
- A **Swiss Army Knife** has a blade, scissors, tweezers, a magnifying glass, and a toothpick. It can do 20 things, but it is clumsy and awkward at all of them. If the scissors jam, you might break the blade trying to fix them.
- A **Chef's Knife** has one job: cut food cleanly. It does not open wine bottles or play music. Because it does one thing, it is indestructible, easy to clean, and never fails.

In software, programmers constantly fall into the trap of writing **"God Functions"**—monolithic functions that receive a web request, parse JSON, validate email syntax, connect to Stripe, charge a credit card, write to PostgreSQL, and send a Welcome email via SendGrid, all in one 250-line block!

### The Two Kinds of Functions: "Doers" vs "Coordinators"
To keep your code clean, divide your functions into two strict categories:

1. **Doers (Workers):** Pure logic or specific I/O actions. They take input, perform one task, and return output. They have zero side effects outside their explicit task.
2. **Coordinators (Orchestrators):** Functions that do not do heavy calculation themselves; they simply call a sequence of Doers in order.

```javascript
// ❌ BAD: A monolithic God Function that does everything
async function handleUserRegistration(req, res) {
  const { email, password, name } = req.body;
  
  // Job 1: Validation logic embedded directly
  if (!email || !email.includes('@') || password.length < 8) {
    return res.status(400).send('Invalid input');
  }

  // Job 2: Database query embedded directly
  const existing = await db.query('SELECT * FROM users WHERE email = $1', [email]);
  if (existing.rows.length > 0) {
    return res.status(400).send('Email already in use');
  }

  // Job 3: Password hashing embedded directly
  const salt = crypto.randomBytes(16).toString('hex');
  const hash = crypto.scryptSync(password, salt, 64).toString('hex');

  // Job 4: Storage logic embedded directly
  const user = await db.query(
    'INSERT INTO users (name, email, password_hash, salt) VALUES ($1, $2, $3, $4) RETURNING *',
    [name, email, hash, salt]
  );

  // Job 5: Notification logic embedded directly
  await sendgrid.send({
    to: email,
    subject: 'Welcome!',
    text: `Hi ${name}, welcome aboard!`
  });

  return res.json({ success: true, user: user.rows[0] });
}
```

Why is the above function a nightmare for a solo dev?
- Want to test password hashing? You must spin up a mock HTTP request and mock database.
- Want to change SendGrid to Postmark? You risk breaking the database insert logic.
- Want to create a test user from a CLI script? You can't reuse this logic because it's glued to `req` and `res`.

### Refactored: Single-Use Functions + Coordinator

```typescript
// ✅ STEP 1: Single-Use Doers (Isolated, testable, reusable)

// Doer 1: Pure input validation (No I/O, runs in 0.001ms)
function validateRegistrationInput(data: { email?: string; password?: string }) {
  if (!data.email || !data.email.includes('@')) {
    throw new Error('Valid email is required');
  }
  if (!data.password || data.password.length < 8) {
    throw new Error('Password must be at least 8 characters');
  }
}

// Doer 2: Cryptographic hashing
function hashPassword(password: string) {
  const salt = crypto.randomBytes(16).toString('hex');
  const hash = crypto.scryptSync(password, salt, 64).toString('hex');
  return { hash, salt };
}

// Doer 3: Database insertion
async function insertUser(userData: { name: string; email: string; hash: string; salt: string }) {
  const result = await db.query(
    'INSERT INTO users (name, email, password_hash, salt) VALUES ($1, $2, $3, $4) RETURNING id, name, email',
    [userData.name, userData.email, userData.hash, userData.salt]
  );
  return result.rows[0];
}

// Doer 4: Email dispatcher
async function sendWelcomeEmail(email: string, name: string) {
  return emailClient.send({
    to: email,
    subject: 'Welcome!',
    text: `Hi ${name}, welcome aboard!`
  });
}

// ✅ STEP 2: The Coordinator (Reads like an English recipe)
async function registerUser(input: RegistrationPayload) {
  validateRegistrationInput(input);
  
  const { hash, salt } = hashPassword(input.password);
  
  const newUser = await insertUser({
    name: input.name,
    email: input.email,
    hash,
    salt,
  });

  // Background task - don't fail registration if email fails
  sendWelcomeEmail(newUser.email, newUser.name).catch(console.error);

  return newUser;
}
```

Notice the superpowers of this design:
- Every function fits in your head in 5 seconds.
- You can unit test `validateRegistrationInput` without any database or network connection.
- If you need a CLI script to create an admin user, simply import `registerUser` or `insertUser` directly.

---

## Composition Over Inheritance

One of the most important architectural realizations in computer science history is **"Favor Object Composition Over Class Inheritance"** (Gang of Four, 1994).

For a solo developer, this rule is not just good practice—**it is a survival mechanism**.

### The Classic Flaw of Inheritance: The Banana Gorilla Problem

Computer scientist Joe Armstrong (creator of Erlang) famously described inheritance:
> *"The problem with object-oriented languages is they’ve got all this implicit environment that they carry around with them. You wanted a banana, but what you got was a gorilla holding the banana and the entire jungle."*

Look at what happens when you use deep inheritance in a real application:

```typescript
// ❌ THE INHERITANCE TRAP: Deep hierarchical trees
class DatabaseEntity {
  id: string;
  createdAt: Date;
  save() { /* ... */ }
  delete() { /* ... */ }
}

class User extends DatabaseEntity {
  name: string;
  email: string;
  authenticate() { /* ... */ }
}

class AdminUser extends User {
  permissions: string[];
  banUser(userId: string) { /* ... */ }
}

class SuperAdminUser extends AdminUser {
  auditLogs() { /* ... */ }
}
```

**The Breakdown:**
1. What if you need a `GuestUser` that can `authenticate()` but should **never** have a `save()` or `delete()` method because they don't exist in the database?
2. What if you need an `AutomatedBot` that can `banUser()` but is not a `User` and has no `email`?
3. With inheritance, you are forced to refactor the entire parent tree or override methods with `throw new Error("Not supported")`.

### The LEGO Solution: Modular Composition

Instead of saying what an object **IS** (a Dog IS-A Animal), describe what an object **HAS** or what it **CAN DO** (a Dog HAS-A SoundMaker, HAS-A Runner).

Think of your code like LEGO blocks. A LEGO brick does not care whether you are building a spaceship, a castle, or a pirate boat. It just snaps together with standard studs.

```typescript
// ✅ THE COMPOSITION APPROACH: Small, independent capabilities

// Capability 1: Pure Timestamps
interface Timestamped {
  createdAt: Date;
  updatedAt: Date;
}

// Capability 2: Identity
interface Identifiable {
  id: string;
}

// Capability 3: Authentication
interface Authenticatable {
  email: string;
  passwordHash: string;
}

// Capability 4: Administrative powers
interface AdminRights {
  permissions: string[];
  level: 'support' | 'moderator' | 'superadmin';
}

// Now compose exactly what you need with zero bloat!

// A standard customer:
type Customer = Identifiable & Timestamped & Authenticatable & {
  stripeCustomerId: string;
};

// A support team member:
type StaffMember = Identifiable & Authenticatable & AdminRights;

// An API service bot (No email, no password, has admin permissions):
type ServiceBot = Identifiable & AdminRights & {
  apiKeyHash: string;
};
```

### Composing Functions Instead of Classes

In modern JavaScript, TypeScript, Python, and Go, you rarely need `class` definitions for business logic. You compose plain functions that take plain data and return plain data:

```typescript
// Reusable Single-Use Functions
const withLogging = (fn) => (...args) => {
  console.log(`[LOG] Calling function with args:`, args);
  return fn(...args);
};

const withTiming = (fn) => async (...args) => {
  const start = performance.now();
  const result = await fn(...args);
  console.log(`[TIMER] Took ${(performance.now() - start).toFixed(2)}ms`);
  return result;
};

// Original simple function:
const calculateDiscount = (price, percent) => price * (1 - percent / 100);

// Composed function with superpowers added on-demand:
const instrumentedDiscount = withLogging(calculateDiscount);
instrumentedDiscount(100, 15);
// Output:
// [LOG] Calling function with args: [100, 15]
// Returns: 85
```

---

## Survey of Core Programming Styles and Paradigms

As a solo developer, you do not need to be a religious purist about any single programming paradigm. The most effective solo developers are **pragmatic polyglots** who borrow the best ideas from each paradigm:

```
                  ┌─────────────────────────────────────┐
                  │    The Pragmatic Solo Dev Style     │
                  └──────────────────┬──────────────────┘
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
┌───────────────────┐       ┌───────────────────┐       ┌───────────────────┐
│ Functional Logic  │       │ Data-Oriented     │       │ Minimal OOP       │
│ Pure functions,   │       │ Plain JSON/dict,  │       │ State containers, │
│ pipelines, zero   │       │ separate data     │       │ UI components,    │
│ side effects      │       │ from logic        │       │ SDK wrappers      │
└───────────────────┘       └───────────────────┘       └───────────────────┘
```

### 1. Functional Programming (FP): Use for Business Logic
- **Core Concept:** Functions are mathematical transformations: `Input → Output`. No hidden variables modified behind your back.
- **Where It Excels:** Data transformation, calculations, parsing, validations, search/filter algorithms.
- **Why Solo Devs Love It:** Pure functions never break other parts of your code. You can test them with a single line of code.

### 2. Object-Oriented Programming (OOP): Use for Encapsulated State
- **Core Concept:** Grouping state (variables) together with the behaviors (methods) that manipulate that state.
- **Where It Excels:** Stateful UI elements (a video player with pause/play/seek controls), external service clients (`new StripeClient(apiKey)`), or game actors.
- **When It Hurts:** Creating anemic business classes (`UserHelperManagerService`) that do nothing but wrap basic SQL queries in layers of bureaucracy.

### 3. Data-Oriented Programming (DOP): The Modern Default
- **Core Concept:** Treat data as pure data (plain records, dicts, arrays, JSON) and logic as pure functions.
- **Where It Excels:** 95% of web development, APIs, backend pipelines, and database operations.
- **Why Solo Devs Love It:** Serializing to JSON is free. No need to map database rows into class instances and back into JSON. Data flows cleanly through your system without boilerplate classes.

---

## The Solo Developer Architecture Playbook

Here are the operational rules used by the most productive independent software developers:

### 1. The KISS Principle (Keep It Simple, Stupid)
If your architecture diagram looks like an airport flight schedule with 14 microservices, 3 message queues, and a distributed cache for a project that has 200 daily active users, you have failed the KISS principle.
- Start with a single monolithic backend and a single database (PostgreSQL or SQLite).
- A well-optimized SQLite or PostgreSQL instance can easily handle **thousands of queries per second** on a $10/month VPS.

### 2. The YAGNI Principle (You Aren't Gonna Need It)
Do not build features or abstractions for hypothetical futures:
- *"What if we switch from PostgreSQL to MongoDB next year?"* You won't. Don't build an abstract database repository layer.
- *"What if we need multi-region replication across 4 continents?"* You don't have users on 4 continents yet. Ship the product first.
- Solve the problem you have today. When you outgrow it, you will have the revenue and data to refactor intelligently.

### 3. WET over Premature DRY
We are taught in school: **DRY (Don't Repeat Yourself)**.  
However, premature DRY is one of the biggest sources of technical debt for solo developers!

> **The Rule of Three (WET - Write Everything Twice):**  
> It is **much cheaper to duplicate code twice** than it is to build the wrong abstraction!

- **Iteration 1:** Write the code directly where you need it.
- **Iteration 2:** You need something similar elsewhere. Copy and paste it. Adjust it slightly.
- **Iteration 3:** Now you have seen three real-world use cases. You now understand the true shared pattern. **Now** extract it into a clean, single-use utility function.

### 4. Feature-First Colocation
Organize your codebase by **Feature / Domain**, not by technical layer:

```
❌ BAD: Layer-First Organization (Forces you to jump across 6 folders to edit 1 feature)
src/
  controllers/
    authController.ts
    billingController.ts
    invoiceController.ts
  models/
    userModel.ts
    invoiceModel.ts
  services/
    billingService.ts
    emailService.ts

✅ GOOD: Feature-First Colocation (Everything for a feature is in one place)
src/
  features/
    auth/
      authRoutes.ts
      authLogic.ts
      authTests.ts
    billing/
      checkout.ts
      invoices.ts
      stripeWebhook.ts
      billingTests.ts
```
When you want to modify billing, you open the `billing/` folder. All relevant code, types, queries, and tests are right in front of you. When you want to delete a feature, you delete one folder!

---

## Real-World Refactoring: Enterprise Bloat vs Composed Simplicity

Let's study a real-world scenario: An E-commerce checkout pricing engine that:
1. Validates items in the cart.
2. Applies a coupon code discount.
3. Adds regional sales tax.
4. Calculates shipping cost.

### The Enterprise OOP Over-Engineered Approach

```typescript
// ❌ 70+ lines of interfaces, strategies, and inheritance boilerplate
interface TaxStrategy {
  calculateTax(amount: number): number;
}

class StandardTaxStrategy implements TaxStrategy {
  calculateTax(amount: number): number { return amount * 0.08; }
}

abstract class OrderProcessorTemplate {
  abstract validate(order: any): boolean;
  abstract applyDiscount(amount: number, code: string): number;
  
  public process(order: any): number {
    if (!this.validate(order)) throw new Error("Invalid");
    let total = order.items.reduce((s, i) => s + i.price, 0);
    total = this.applyDiscount(total, order.coupon);
    return total;
  }
}

class RetailOrderProcessor extends OrderProcessorTemplate {
  constructor(private taxStrategy: TaxStrategy) { super(); }
  validate(order: any): boolean { return order.items.length > 0; }
  applyDiscount(amount: number, code: string): number {
    return code === 'SAVE10' ? amount * 0.9 : amount;
  }
}
```
**Why this slows you down:** To trace where the discount happens, you must jump through two class files, an interface, and a template pattern method.

---

### The Clean Solo Dev Composed Approach

```typescript
// ✅ PURE, SINGLE-USE FUNCTIONS (Simple, transparent, zero magic)

// Type definitions (Pure data)
type CartItem = { id: string; name: string; price: number; weightLbs: number };
type Cart = { items: CartItem[]; couponCode?: string; state: string };

// Single-Use Worker 1: Subtotal
const getSubtotal = (items: CartItem[]): number => 
  items.reduce((sum, item) => sum + item.price, 0);

// Single-Use Worker 2: Coupon calculation
const applyCoupon = (subtotal: number, code?: string): number => {
  if (code === 'SAVE10') return subtotal * 0.90;
  if (code === 'HALFOFF') return subtotal * 0.50;
  return subtotal;
};

// Single-Use Worker 3: Sales tax lookup
const getTaxRate = (state: string): number => {
  const rates: Record<string, number> = { CA: 0.09, NY: 0.08, TX: 0.0625 };
  return rates[state] ?? 0.05; // 5% default fallback
};

// Single-Use Worker 4: Shipping based on total weight
const calculateShipping = (items: CartItem[]): number => {
  const totalWeight = items.reduce((sum, item) => sum + item.weightLbs, 0);
  return totalWeight > 10 ? 15.00 : 5.00;
};

// The Composed Coordinator: Everything is crystal clear
function calculateOrderTotal(cart: Cart) {
  if (cart.items.length === 0) {
    throw new Error('Cart cannot be empty');
  }

  const subtotal = getSubtotal(cart.items);
  const discountedSubtotal = applyCoupon(subtotal, cart.couponCode);
  const tax = discountedSubtotal * getTaxRate(cart.state);
  const shipping = calculateShipping(cart.items);
  
  const finalTotal = discountedSubtotal + tax + shipping;

  return {
    subtotal,
    discount: subtotal - discountedSubtotal,
    tax,
    shipping,
    finalTotal,
  };
}
```

Look at the difference:
- No classes. No `this` context binding issues.
- Every function is 2 to 6 lines long.
- You can test `applyCoupon` in a Node REPL or browser console in 2 seconds.
- Anyone can read `calculateOrderTotal` and understand the business logic immediately.

---

## Functional Pipelines and Middleware Composition

One of the most elegant ways to compose single-use functions is using a **Pipeline**.

In Unix command line, you pipe commands together:
```bash
cat access.log | grep "404" | cut -d' ' -f1 | sort | uniq -c
```
Each command does one job (`cat`, `grep`, `cut`, `sort`, `uniq`). None of them know or care about the others. They are composed dynamically via standard input and output streams.

We can do the exact same thing in our code!

### Creating a Simple `pipe` Function
A `pipe` utility takes a starting value and passes it through an array of functions from left to right:

```typescript
// A lightweight 5-line pipe helper
const pipe = <T>(...fns: Array<(arg: any) => any>) => 
  (initialValue: T) => 
    fns.reduce((value, currentFn) => currentFn(value), initialValue);
```

### Building a Clean Text Sanitization Pipeline

```typescript
// Define independent, single-use string filters
const trimWhitespace = (s: string): string => s.trim();
const normalizeSpaces = (s: string): string => s.replace(/\s+/g, ' ');
const stripHtmlTags = (s: string): string => s.replace(/<[^>]*>?/gm, '');
const lowercase = (s: string): string => s.toLowerCase();

// Compose them into a single-purpose pipeline!
const cleanUserComment = pipe<string>(
  trimWhitespace,
  stripHtmlTags,
  normalizeSpaces
);

const createSearchSlug = pipe<string>(
  trimWhitespace,
  lowercase,
  (s) => s.replace(/[^a-z0-9]+/g, '-')
);

// Run the pipeline
console.log(cleanUserComment("  <script>alert(1)</script> Hello    world!   "));
// Output: "Hello world!"

console.log(createSearchSlug("   MacBook Pro 16-inch (M3 Max)!  "));
// Output: "macbook-pro-16-inch-m3-max-"
```

When you need to change how slugs are generated, you add or swap a single filter function without touching anything else!

---

## Testing and Maintainability for One-Person Teams

When you work alone, you don't have a dedicated QA team to manually click through your website before every deployment. However, writing 10,000 brittle unit tests will grind your feature velocity to a halt.

Here is the pragmatic solo developer testing strategy:

### 1. Test Pure Logic with Unit Tests
Pure, single-use functions (pricing calculations, permission checkers, string formatters) are trivially easy to test because they require no mocking, no database, and no server:

```typescript
// Super fast unit test (runs in 2ms)
test('calculates 10% coupon correctly', () => {
  expect(applyCoupon(100, 'SAVE10')).toBe(90);
  expect(applyCoupon(100, undefined)).toBe(100);
});
```

### 2. Test Critical Paths with High-Level Integration Tests
Don't waste time unit-testing individual SQL queries. Write 3 to 5 integration tests for your primary revenue-generating flows:
- Can a new user sign up?
- Can a user add an item to their cart and complete checkout?
- Does a Stripe webhook successfully upgrade the user's account?

If those 3 tests pass, your business is alive.

### 3. Make Error Messages Extremely Descriptive
When you receive an exception at 2:00 AM on a Friday, you do not want to see:
`Error: Failed to process`

Instead, your single-use functions should fail with full context:
`Error: [ChargeCustomer] Failed to charge customer cus_9912. Reason: Card expired`

---

## Summary Cheat Sheet and Mental Checklist

Print out or bookmark this checklist when designing your next feature or project:

```
                    ┌──────────────────────────────────────┐
                    │ Solo Developer Architecture Checklist│
                    └──────────────────┬───────────────────┘
                                       │
    [ ] Can I explain this function in 15 seconds?
    [ ] Does this function do ONE job, or is it secretly doing three?
    [ ] Did I separate my pure calculations (Doers) from my I/O calls?
    [ ] Am I using Composition instead of a rigid class hierarchy?
    [ ] Am I storing data as plain JSON/objects rather than complex OOP classes?
    [ ] Did I duplicate this twice before prematurely creating a complex abstraction?
    [ ] Can I unit-test the business logic without starting a database or web server?
    [ ] If I delete this feature next month, is it contained in one folder?
```

### The 5 Mantras of the Productive Solo Engineer
1. **Boring is Beautiful:** Standard PostgreSQL, plain Node/Python/Go, and simple single-use functions make money. Over-engineered microservices burn out engineers.
2. **Do One Thing Well:** Small functions with clear names never need a 5-paragraph comment explaining what they do.
3. **Compose Like LEGO:** Snap small capabilities together to form larger systems.
4. **Colocate Everything:** Keep files that change together inside the same folder.
5. **Optimize for Deletion:** The easier a piece of code is to remove and replace, the healthier your project will remain over the years.
