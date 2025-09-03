# The Complete Bun JavaScript Guide

## From JavaScript Fundamentals to Bun Runtime Mastery

## Introduction

Bun is an all-in-one JavaScript runtime & toolkit designed as a faster, leaner, more modern replacement for Node.js. At its core is the Bun runtime, a fast JavaScript runtime written in Zig and powered by JavaScriptCore under the hood, dramatically reducing startup times and memory usage.

### What makes Bun special

- **Blazing fast** - Processes start 4x faster than Node.js with up to 2.5x more requests per second
- **All-in-one toolkit** - Runtime, bundler, transpiler, package manager, and test runner in a single executable
- **Node.js compatibility** - Drop-in replacement for most Node.js applications with full compatibility for built-in globals and modules
- **Modern by default** - Built-in support for TypeScript, JSX, and ES modules without configuration
- **Zero-configuration** - Works out of the box with sensible defaults
- **Web-standard APIs** - Implements standard Web APIs like `fetch`, `WebSocket`, and `ReadableStream`

### Design Goals

- **Speed**: Built from the ground-up for performance
- **TypeScript & JSX support**: Direct execution of `.jsx`, `.ts`, and `.tsx` files
- **ESM & CommonJS compatibility**: Supports both module systems seamlessly  
- **Web-standard APIs**: Uses Safari's implementation for familiar web APIs
- **Complete toolkit**: Package manager, transpiler, bundler, script runner, test runner, and more

## Table of Contents

### By topic

- JavaScript Fundamentals
    - [Getting Started](#getting-started)
    - [Variables and Data Types](#variables-and-data-types)
    - [Functions](#functions)
    - [Objects and Arrays](#objects-and-arrays)
    - [Classes and Modules](#classes-and-modules)
    - [Async Programming](#async-programming)
    - [Error Handling](#error-handling)

- Bun Runtime
    - [Installation](#installation)
    - [Running JavaScript](#running-javascript)
    - [Package Management](#package-management)
    - [File System Operations](#file-system-operations)
    - [HTTP Server](#http-server)
    - [HTTP Client](#http-client)
    - [Environment Variables](#environment-variables)
    - [WebSockets](#websockets)
    - [SQLite](#sqlite)
    - [Redis](#redis)
    - [Workers and Child Processes](#workers-and-child-processes)
    - [FFI](#ffi)
    - [ESM and CJS Interop](#esm-and-cjs-interop)
    - [URL Imports and Transpilation](#url-imports-and-transpilation)
    - [Shebang CLI Scripts](#shebang-cli-scripts)

- Development Tools
    - [Test Runner](#test-runner)
    - [Bundler](#bundler)
    - [Watch Mode](#watch-mode)
    - [Debugging](#debugging)
    - [TypeScript Support](#typescript-support)
    - [Workspaces and Monorepos](#workspaces-and-monorepos)
    - [Docker and Deployment](#docker-and-deployment)
    - [Tips and Pitfalls](#tips-and-pitfalls)
    - [Bun CLI Cheat Sheet](#bun-cli-cheat-sheet)

- Quickstart Recipes
    - [Hello HTTP Server](#hello-http-server)
    - [Simple REST API](#simple-rest-api)
    - [SQLite CRUD](#sqlite-crud)
    - [File Upload Handler](#file-upload-handler)
    - [WebSocket Echo](#websocket-echo)
    - [Worker Queue](#worker-queue)
    - [CLI Script](#cli-script)
    - [Unit Test](#unit-test)
    - [Read and Write Files](#read-and-write-files)
    - [Read and Write JSON](#read-and-write-json)
    - [Environment Variables](#environment-variables-quickstart)
    - [Console Apps (CLI)](#console-apps-cli)

---

## Quickstart Recipes

Beginner-friendly, copy–paste snippets you can run with Bun right away.

### Hello HTTP Server

```javascript
// server.js
// A minimal HTTP server using Bun.serve
// - Bun.serve starts an HTTP server
// - routes lets you map paths (like "/" or "/health") to responses
// - fetch() below is a fallback when no route matches
const server = Bun.serve({
    port: 3000, // The TCP port; can also use env vars $BUN_PORT or $PORT
    routes: {
        '/': new Response('Hello from Bun!'), // Quick static response (fastest path)
        '/health': new Response('OK'),        // Useful for health checks
    },
    // Fallback for unmatched routes
    fetch() { return new Response('Not Found', { status: 404 }); },
});
console.log(server.url.toString()); // Prints the base URL, e.g. http://localhost:3000
// Run in terminal: bun server.js
```

### Simple REST API

```javascript
// api.js
// Simple in-memory REST API for beginners
// - We keep data in a plain array for now (resets on restart)
// - routes object supports per-method handlers (GET/POST)
const todos = [{ id: 1, text: 'Learn Bun' }];

const api = Bun.serve({
    port: 3001,
    routes: {
        '/todos': {
            GET: () => Response.json(todos), // Return JSON with Response.json()
            POST: async (req) => {
                // Read JSON body safely; default to empty object on parse error
                const body = await req.json().catch(() => ({}));
                // Create a new todo; crypto.randomUUID() generates a unique id
                const todo = { id: crypto.randomUUID(), text: String(body.text || '') };
                todos.push(todo);
                return Response.json(todo, { status: 201 }); // 201 Created
            },
        },
        // Static responses are great for health/readiness
        '/health': new Response('OK'),
    },
    fetch() { return new Response('Not Found', { status: 404 }); },
});
console.log(api.url.toString()); // http://localhost:3001
// Run: bun api.js
```

### SQLite CRUD

```javascript
// sqlite-demo.js
import { Database } from 'bun:sqlite';

// Opens (or creates) a file named app.db next to this script
const db = new Database('app.db');
// Create a table if it doesn't exist yet
db.run('CREATE TABLE IF NOT EXISTS notes (id INTEGER PRIMARY KEY, text TEXT)');

const insert = db.query('INSERT INTO notes (text) VALUES (?)');
insert.run('hello'); // Add a row

const all = db.query('SELECT id, text FROM notes').all();
console.log(all);
// Run: bun sqlite-demo.js
```

### File Upload Handler

```javascript
// upload.js
export default Bun.serve({
    port: 3002,
    async fetch(req) {
        const url = new URL(req.url);
        if (url.pathname === '/upload' && req.method === 'POST') {
            const form = await req.formData();
            const file = form.get('file'); // Returns a Blob (file-like object)
            if (file) {
                // Ensure the uploads directory exists (creates it on first run)
                await Bun.write('uploads/.keep', '');
                // Save the uploaded file by name
                await Bun.write(`uploads/${file.name}`, file);
                return new Response('uploaded');
            }
            return new Response('no file', { status: 400 });
        }

        // simple HTML form for testing
        return new Response(`<!doctype html>
<form method="post" action="/upload" enctype="multipart/form-data">
    <input type="file" name="file" />
    <button>Upload</button>
</form>`, { headers: { 'Content-Type': 'text/html' }});
    },
});
console.log('http://localhost:3002');
// Run: bun upload.js
```

### WebSocket Echo

```javascript
// ws.js
export default Bun.serve({
    port: 3003,
    fetch(req, server) {
    // Upgrade HTTP request to a WebSocket connection
    if (server.upgrade(req)) return; // After upgrade, handlers in `websocket` take over
        return new Response('WebSocket server');
    },
    websocket: {
    open(ws) { ws.send('Welcome'); }, // Called when a client connects
    message(ws, msg) { ws.send(msg); }, // Echo back whatever the client sent
    },
});
console.log('ws://localhost:3003');
// Run: bun ws.js
```

### Worker Queue

```javascript
// worker.js
self.onmessage = (e) => {
    // Receive a number from the main thread
    const n = Number(e.data || 0);
    // Do some work (here: square the number)
    const result = n * n; // pretend heavy work
    // Send the result back to the main thread
    self.postMessage(result);
};
```

```javascript
// worker-main.js
// Create a Worker from a separate file
const worker = new Worker(new URL('./worker.js', import.meta.url));
worker.onmessage = (e) => {
    // Log the received result and end the worker
    console.log('result:', e.data);
    worker.terminate(); // Always terminate when done to free resources
};
worker.postMessage(12);
// Run: bun worker-main.js
```

### CLI Script

```javascript
#!/usr/bin/env bun
// greet.mjs
const [, , name = 'world'] = process.argv;
console.log(`Hello, ${name}!`);
// Make executable: chmod +x greet.mjs
// Run: ./greet.mjs Alice
```

### Unit Test

```typescript
// math.test.ts
import { describe, expect, it } from 'bun:test';

// A simple function to test
function add(a: number, b: number) { return a + b; }

describe('add', () => {
    it('adds numbers', () => {
        // expect(...).toBe(...) is an assertion
        expect(add(2, 3)).toBe(5);
    });
});
// Run: bun test
```



### Read and Write Files

```javascript
// files-demo.js
// Write text
await Bun.write('note.txt', 'Hello from Bun');

// Read text
const text = await Bun.file('note.txt').text();
console.log('text:', text);

// Write binary (copy a file)
const src = Bun.file('note.txt');
await Bun.write('note-copy.txt', src);
console.log('copied');

// Append text (idiomatic: use Node's fs.appendFile in Bun)
import { appendFile } from 'node:fs/promises';
await appendFile('note.txt', '\nAnother line from Bun', 'utf8');
console.log('appended');
// Run: bun files-demo.js
```

### Read and Write JSON

```javascript
// json-utils.js
// Reusable helpers for JSON files using Bun APIs

export async function writeJSON(filePath, data, { pretty = 2 } = {}) {
    // pretty: 0 for compact, 2 for indented output
    const text = JSON.stringify(data, null, pretty);
    return Bun.write(filePath, text);
}

export async function readJSON(filePath, { fallback, reviver } = {}) {
    try {
        if (reviver) {
            // If a JSON.parse reviver is needed
            const text = await Bun.file(filePath).text();
            return JSON.parse(text, reviver);
        }
        // Fast path
        return await Bun.file(filePath).json();
    } catch (err) {
        // Return fallback for missing or malformed JSON when provided
        const isNotFound = err && (err.code === 'ENOENT' || err.name === 'NotFoundError');
        const isSyntax = err instanceof SyntaxError || err?.name === 'SyntaxError';
        if (fallback !== undefined && (isNotFound || isSyntax)) {
            return typeof fallback === 'function' ? fallback(err) : fallback;
        }
        throw err;
    }
}

export async function updateJSON(filePath, updater, { pretty = 2, fallback = {} } = {}) {
    // Read existing (or fallback), compute next state, then write it back
    const current = await readJSON(filePath, { fallback });
    const next = await Promise.resolve(updater(current));
    await writeJSON(filePath, next, { pretty });
    return next;
}

// Demo when run directly: create, read, then update a JSON file
if (import.meta.main) {
    await writeJSON('data.json', { count: 1, tags: ['bun'] });
    const data = await readJSON('data.json');
    console.log('read:', data);
    const updated = await updateJSON('data.json', (d) => ({ ...d, count: d.count + 1 }));
    console.log('updated:', updated);
}
// Run: bun json-utils.js
```

```javascript
// json-demo.js
// Example usage by importing the helpers
import { readJSON, writeJSON, updateJSON } from './json-utils.js';

await writeJSON('config.json', { featureFlag: true, retries: 3 });
const cfg = await readJSON('config.json');
console.log('config:', cfg);

await updateJSON('config.json', (c) => ({ ...c, retries: (c.retries ?? 0) + 1 }));
console.log('bumped retries');
// Run: bun json-demo.js
```

### Environment Variables (Quickstart)

```javascript
// env-demo.js
// Set before running: MY_NAME=Alice bun env-demo.js
const name = Bun.env.MY_NAME || 'world';
console.log(`Hello, ${name}!`);
```

### Console Apps (CLI)

```javascript
// cli-examples.js
// 1) Hello with flags
const args = process.argv.slice(2);
const idx = args.indexOf('--name');
const name = idx !== -1 ? args[idx + 1] : 'world';
console.log(`Hello, ${name}!`);

// 2) Pretty-print JSON from a file using Bun APIs
if (args.includes('--pp') && args[idx + 2]) {
    const filePath = args[idx + 2];
    const data = await Bun.file(filePath).json();
    console.log(JSON.stringify(data, null, 2));
}

// 3) Append logs (idiomatic append with fs.appendFile)
import { appendFile } from 'node:fs/promises';
if (args.includes('--log')) {
    await appendFile('cli.log', `[${new Date().toISOString()}] ${name}\n`, 'utf8');
    console.log('logged');
}

// 4) Read stdin -> uppercase
if (args.includes('--upper')) {
    let input = '';
    for await (const chunk of process.stdin) input += chunk;
    process.stdout.write(input.toUpperCase());
}
// Usage examples:
//   bun cli-examples.js --name Alice
//   bun cli-examples.js --name data --pp ./config.json
//   bun cli-examples.js --log --name TaskStarted
//   echo "hello" | bun cli-examples.js --upper
```

## JavaScript Fundamentals

### Getting Started

Let's start with the basics of JavaScript, the language that powers Bun.

#### Hello World

```javascript
// Your first JavaScript program
console.log('Hello, World!');

// Variables and basic operations  
const name = 'Developer';
const age = 25;
console.log(`Hello ${name}, you are ${age} years old`);

// Simple function
function greet(person) {
    return `Welcome to JavaScript, ${person}!`;
}

console.log(greet('Alice'));
```

#### Control Flow and Comparisons (Beginner Essentials)

```javascript
// if/else decides which block to run based on a condition
const score = 85;
if (score >= 90) {
    console.log('Grade: A');
} else if (score >= 80) {
    console.log('Grade: B');
} else {
    console.log('Keep practicing!');
}

// switch is handy for multiple fixed cases
const role = 'admin';
switch (role) {
    case 'admin':
        console.log('Full access');
        break; // Prevents falling through to the next case
    case 'editor':
        console.log('Can edit content');
        break;
    default:
        console.log('Read-only');
}

// Loops
const nums = [1, 2, 3];
for (let i = 0; i < nums.length; i++) {
    console.log('for i =', i, 'value =', nums[i]);
}

for (const n of nums) {
    console.log('for...of value =', n); // Preferred for arrays
}

// Strict equality (===) compares value AND type; prefer it over ==
console.log(1 === '1'); // false (number vs string)
console.log(1 == '1');  // true  (coerces types; avoid in beginners code)

// Truthy/falsy and the nullish coalescing operator
// || uses truthiness, ?? only checks null or undefined
const input = '';
console.log(input || 'fallback when empty string'); // prints fallback ('' is falsy)
console.log(input ?? 'only for null/undefined');    // prints '' (not null/undefined)

// Optional chaining avoids errors when a property might be missing
const user = { profile: null };
console.log(user.profile?.email); // undefined instead of throwing
```

#### Understanding JavaScript Basics

JavaScript is a dynamic, interpreted programming language that's:

- **Dynamically typed** - Variables can hold any type of value
- **Interpreted** - No compilation step needed (though Bun optimizes this)
- **Multi-paradigm** - Supports object-oriented, functional, and procedural programming

```javascript
// Dynamic typing examples
let value = 42;           // Number
console.log(typeof value); // "number"

value = 'Hello';          // Now it's a string
console.log(typeof value); // "string" 

value = true;             // Now it's a boolean
console.log(typeof value); // "boolean"

value = { name: 'Alice' }; // Now it's an object
console.log(typeof value); // "object"
```

### Variables and Data Types

#### Variable Declarations

JavaScript has three ways to declare variables:

```javascript
// const - Cannot be reassigned (immutable binding)
const PI = 3.14159;
const users = ['Alice', 'Bob', 'Charlie'];
// PI = 3.14; // ❌ Error: Cannot assign to const variable

// let - Can be reassigned, block-scoped
let counter = 0;
let message = 'Loading...';

counter = 10;        // ✅ OK - can reassign let variables
message = 'Done!';   // ✅ OK

// var - Old style, avoid in modern JavaScript
var oldStyle = 'avoid this'; // ❌ Don't use var in modern JS
```

#### Data Types

JavaScript has several built-in data types:

```javascript
// Primitive Types
const name = 'Alice';              // String
const age = 30;                    // Number  
const isActive = true;             // Boolean
const nothing = null;              // Null
let uninitialized;                 // Undefined
const uniqueId = Symbol('id');     // Symbol

// Object Types
const person = {                   // Object
    name: 'Bob',
    age: 25
};

const numbers = [1, 2, 3, 4, 5];   // Array
const today = new Date();          // Date
const pattern = /hello/i;          // RegExp

// Display type information
console.log('=== Data Types Demo ===');
console.log(`name: "${name}" (${typeof name})`);
console.log(`age: ${age} (${typeof age})`);
console.log(`isActive: ${isActive} (${typeof isActive})`);
console.log(`nothing: ${nothing} (${typeof nothing})`); // Note: null shows as "object"
console.log(`uninitialized: ${uninitialized} (${typeof uninitialized})`);
console.log(`person: ${JSON.stringify(person)} (${typeof person})`);
console.log(`numbers: [${numbers}] (${Array.isArray(numbers) ? 'array' : typeof numbers})`);
```

#### Working with Strings

```javascript
// String creation and manipulation
const firstName = 'John';
const lastName = 'Doe';

// Template literals (modern way)
const fullName = `${firstName} ${lastName}`;
const greeting = `Hello, my name is ${fullName} and I'm ${age} years old`;

// String methods
console.log('=== String Operations ===');
console.log(fullName.length);              // Length: 8
console.log(fullName.toUpperCase());       // JOHN DOE
console.log(fullName.toLowerCase());       // john doe
console.log(firstName.charAt(0));          // J
console.log(lastName.includes('o'));       // true
console.log(fullName.split(' '));          // ['John', 'Doe']
console.log(greeting.substring(0, 5));     // Hello

// String formatting and escaping
const quote = 'He said, "JavaScript is awesome!"';
const path = 'C:\\Users\\Documents\\file.txt';
const multiline = `
    This is a 
    multiline string
    with proper indentation
`;
```

#### Working with Numbers

```javascript
// Number operations and formatting
const price = 29.99;
const quantity = 3;
const discount = 0.1; // 10%

// Basic arithmetic
const subtotal = price * quantity;
const discountAmount = subtotal * discount;
const total = subtotal - discountAmount;

console.log('=== Shopping Cart Calculation ===');
console.log(`Price: $${price.toFixed(2)}`);
console.log(`Quantity: ${quantity}`);
console.log(`Subtotal: $${subtotal.toFixed(2)}`);
console.log(`Discount (${discount * 100}%): -$${discountAmount.toFixed(2)}`);
console.log(`Total: $${total.toFixed(2)}`);

// Number methods and properties
console.log('=== Number Methods ===');
console.log(Math.PI);                    // 3.141592653589793
console.log(Math.round(3.7));            // 4
console.log(Math.floor(3.9));            // 3
console.log(Math.ceil(3.1));             // 4
console.log(Math.random());              // Random number 0-1
console.log(Math.max(10, 5, 8, 3));      // 10
console.log(Math.min(10, 5, 8, 3));      // 3

// Number parsing and validation
const userInput = '42';
const parsed = parseInt(userInput);      // 42
const isValid = !isNaN(parsed);          // true
console.log(`Input: "${userInput}" -> ${parsed} (valid: ${isValid})`);
```

### Functions

Functions are the building blocks of JavaScript applications.

#### Function Declarations

```javascript
// Traditional function declaration
function calculateArea(width, height) {
    return width * height;
}

// Function expression
const calculateVolume = function(length, width, height) {
    return length * width * height;
};

// Arrow functions (modern, concise syntax)
const calculatePerimeter = (width, height) => {
    return 2 * (width + height);
};

// Shorthand arrow function (for single expressions)
const square = x => x * x;
const add = (a, b) => a + b;

// Demo: Using different function styles
console.log('=== Function Examples ===');
console.log(`Area (5x3): ${calculateArea(5, 3)}`);
console.log(`Volume (5x3x2): ${calculateVolume(5, 3, 2)}`);
console.log(`Perimeter (5x3): ${calculatePerimeter(5, 3)}`);
console.log(`Square of 7: ${square(7)}`);
console.log(`Add 10 + 5: ${add(10, 5)}`);
```

#### Function Parameters and Default Values

```javascript
// Default parameters
function greetUser(name = 'Guest', greeting = 'Hello') {
    return `${greeting}, ${name}!`;
}

// Rest parameters (collect remaining arguments)
function sum(...numbers) {
    return numbers.reduce((total, num) => total + num, 0);
}

// Destructuring parameters
function createUser({ name, age, email }) {
    return {
        id: Math.random().toString(36).substr(2, 9),
        name,
        age,
        email,
        createdAt: new Date()
    };
}

// Demo: Advanced function features
console.log('=== Advanced Functions ===');
console.log(greetUser());                        // Hello, Guest!
console.log(greetUser('Alice'));                 // Hello, Alice!
console.log(greetUser('Bob', 'Welcome'));        // Welcome, Bob!

console.log(`Sum: ${sum(1, 2, 3, 4, 5)}`);      // Sum: 15
console.log(`Sum: ${sum(10, 20)}`);              // Sum: 30

const newUser = createUser({
    name: 'Charlie',
    age: 28,
    email: 'charlie@example.com'
});
console.log('New user:', newUser);
```

#### Higher-Order Functions

```javascript
// Functions that work with other functions
const numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

// Map - transform each element
const doubled = numbers.map(x => x * 2);
console.log('Doubled:', doubled);

// Filter - keep elements that match condition  
const evens = numbers.filter(x => x % 2 === 0);
console.log('Even numbers:', evens);

// Reduce - combine elements into single value
const total = numbers.reduce((sum, num) => sum + num, 0);
console.log('Total:', total);

// Find - get first matching element
const found = numbers.find(x => x > 5);
console.log('First number > 5:', found);

// Chain operations together
const result = numbers
    .filter(x => x % 2 === 0)    // Get even numbers
    .map(x => x * x)             // Square them
    .reduce((sum, x) => sum + x, 0); // Sum the squares

console.log('Sum of squares of even numbers:', result);
```

#### forEach vs for...of (Beginner tip)

```javascript
const items = ['a', 'b', 'c'];

// forEach passes each element to a callback; you cannot break early
items.forEach((item, index) => {
    console.log(index, item);
});

// for...of loops over values and allows break/continue
for (const item of items) {
    if (item === 'b') continue; // skip 'b'
    console.log(item);
}
```

### Objects and Arrays

#### Working with Objects

```javascript
// Object creation and manipulation
const student = {
    name: 'Alice Johnson',
    age: 22,
    major: 'Computer Science',
    gpa: 3.8,
    isEnrolled: true,
    
    // Methods (functions inside objects)
    getInfo() {
        return `${this.name}, ${this.age} years old, studying ${this.major}`;
    },
    
    updateGPA(newGPA) {
        this.gpa = newGPA;
        console.log(`GPA updated to ${this.gpa}`);
    }
};

// Accessing object properties
console.log('=== Object Operations ===');
console.log(`Name: ${student.name}`);
console.log(`Major: ${student['major']}`); // Alternative syntax
console.log(student.getInfo());

// Adding new properties
student.email = 'alice@university.edu';
student.graduationYear = 2025;

// Object destructuring
const { name, age, major } = student;
console.log(`Destructured: ${name}, ${age}, ${major}`);

// Object methods
const keys = Object.keys(student);
const values = Object.values(student);
console.log('Properties:', keys);
console.log('Values:', values);
```

#### Working with Arrays

```javascript
// Array creation and basic operations
const fruits = ['apple', 'banana', 'orange'];
const numbers = [10, 20, 30, 40, 50];
const mixed = ['text', 42, true, { name: 'object' }];

console.log('=== Array Operations ===');

// Adding elements
fruits.push('grape');           // Add to end
fruits.unshift('mango');        // Add to beginning
console.log('After adding:', fruits);

// Removing elements  
const lastFruit = fruits.pop();        // Remove from end
const firstFruit = fruits.shift();     // Remove from beginning
console.log(`Removed: ${firstFruit} and ${lastFruit}`);
console.log('After removing:', fruits);

// Array methods
console.log(`Array length: ${fruits.length}`);
console.log(`Index of 'banana': ${fruits.indexOf('banana')}`);
console.log(`Includes 'apple': ${fruits.includes('apple')}`);

// Slicing and splicing
const citrus = fruits.slice(1, 3);     // Extract portion
console.log('Citrus fruits:', citrus);

fruits.splice(1, 1, 'kiwi', 'peach');  // Replace elements
console.log('After splice:', fruits);
```

#### Advanced Array Operations

```javascript
// Working with array of objects
const products = [
    { id: 1, name: 'Laptop', price: 999.99, category: 'Electronics' },
    { id: 2, name: 'Book', price: 19.99, category: 'Education' },
    { id: 3, name: 'Phone', price: 699.99, category: 'Electronics' },
    { id: 4, name: 'Desk', price: 299.99, category: 'Furniture' }
];

console.log('=== Advanced Array Operations ===');

// Find products under $500
const affordable = products.filter(product => product.price < 500);
console.log('Affordable products:', affordable);

// Get all product names
const productNames = products.map(product => product.name);
console.log('Product names:', productNames);

// Find total value of all products
const totalValue = products.reduce((sum, product) => sum + product.price, 0);
console.log(`Total inventory value: $${totalValue.toFixed(2)}`);

// Group by category
const byCategory = products.reduce((groups, product) => {
    const category = product.category;
    if (!groups[category]) {
        groups[category] = [];
    }
    groups[category].push(product);
    return groups;
}, {});

console.log('Products by category:', byCategory);

// Find most expensive product
const mostExpensive = products.reduce((max, product) => 
    product.price > max.price ? product : max
);
console.log('Most expensive:', mostExpensive);
```

### Classes and Modules

#### ES6 Classes

```javascript
// Modern class syntax
class BankAccount {
    constructor(accountNumber, initialBalance = 0) {
        this.accountNumber = accountNumber;
        this.balance = initialBalance;
        this.transactions = [];
    }
    
    // Public methods
    deposit(amount) {
        if (amount <= 0) {
            throw new Error('Deposit amount must be positive');
        }
        
        this.balance += amount;
        this.transactions.push({
            type: 'deposit',
            amount,
            date: new Date(),
            balance: this.balance
        });
        
        console.log(`Deposited $${amount}. New balance: $${this.balance}`);
        return this.balance;
    }
    
    withdraw(amount) {
        if (amount <= 0) {
            throw new Error('Withdrawal amount must be positive');
        }
        
        if (amount > this.balance) {
            throw new Error('Insufficient funds');
        }
        
        this.balance -= amount;
        this.transactions.push({
            type: 'withdrawal', 
            amount,
            date: new Date(),
            balance: this.balance
        });
        
        console.log(`Withdrew $${amount}. New balance: $${this.balance}`);
        return this.balance;
    }
    
    getBalance() {
        return this.balance;
    }
    
    getStatement() {
        return {
            accountNumber: this.accountNumber,
            balance: this.balance,
            transactions: this.transactions
        };
    }
}

// Using the class
console.log('=== Banking System Demo ===');
const account = new BankAccount('ACC-001', 100);

try {
    account.deposit(50);
    account.withdraw(30);
    account.withdraw(200); // This will throw an error
} catch (error) {
    console.log('Error:', error.message);
}

console.log('Final balance:', account.getBalance());
console.log('Account statement:', account.getStatement());
```

#### Modules 101 (import/export basics)

```javascript
// math-utils.js (module file)
export function add(a, b) { return a + b; }
export const PI = 3.14159;

// You can also have a default export (one per file)
export default function square(n) { return n * n; }
```

```javascript
// app.js (import from another file)
import square, { add, PI } from './math-utils.js';
console.log(add(2, 3)); // 5
console.log(PI);        // 3.14159
console.log(square(4)); // 16
// Run: bun app.js (ensure both files are in the same folder)
```

More import/export patterns you’ll use with Bun:

```javascript
// 1) Namespace import (import everything as an object)
import * as math from './math-utils.js';
console.log(math.add(1, 2), math.PI, math.default(5)); // 3 3.14159 25

// 2) Import with renaming (aliases)
import { add as sum, PI as pi } from './math-utils.js';
console.log(sum(2, 2), pi); // 4 3.14159

// 3) Re-export from another module (barrel/aggregator file)
// index.js
export { add, PI } from './math-utils.js'; // named re-exports
export { default as square } from './math-utils.js'; // re-export default under a name

// consumer.js
import { add, PI, square } from './index.js';

// 4) Export groups and aliases from your own file
// stats.js
const mean = arr => arr.reduce((a, b) => a + b, 0) / arr.length;
const median = arr => arr.toSorted((a, b) => a - b)[Math.floor(arr.length / 2)];
function mode(arr) { /* ...impl... */ }

export { mean, median as mid, mode };

// consumer
import { mean, mid } from './stats.js';

// 5) Dynamic import with top-level await (supported in Bun)
const { add: addFn } = await import('./math-utils.js');
console.log(addFn(10, 5));

// 6) Import Node built-ins using the `node:` scheme (recommended)
import { readFile } from 'node:fs/promises';
const text = await readFile(new URL('./README.md', import.meta.url), 'utf8');

// 7) Import Bun built-ins with the `bun:` scheme
import { Database } from 'bun:sqlite';
// const db = new Database('app.db');

// 8) Test utilities from Bun
import { describe, it, expect } from 'bun:test';
```

#### Class Inheritance

```javascript
// Extending classes
class SavingsAccount extends BankAccount {
    constructor(accountNumber, initialBalance = 0, interestRate = 0.02) {
        super(accountNumber, initialBalance); // Call parent constructor
        this.interestRate = interestRate;
        this.type = 'Savings';
    }
    
    // Add interest to the account
    addInterest() {
        const interest = this.balance * this.interestRate;
        this.balance += interest;
        
        this.transactions.push({
            type: 'interest',
            amount: interest,
            date: new Date(),
            balance: this.balance
        });
        
        console.log(`Interest added: $${interest.toFixed(2)}. New balance: $${this.balance.toFixed(2)}`);
        return interest;
    }
    
    // Override parent method
    getStatement() {
        const statement = super.getStatement();
        statement.type = this.type;
        statement.interestRate = this.interestRate;
        return statement;
    }
}

// Demo inheritance
console.log('=== Savings Account Demo ===');
const savings = new SavingsAccount('SAV-001', 1000, 0.05);
savings.deposit(500);
savings.addInterest();
console.log('Savings statement:', savings.getStatement());
```

### Async Programming

#### Promises and Async/Await

```javascript
// Simulating asynchronous operations
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function fetchUserData(userId) {
    return new Promise((resolve, reject) => {
        // Simulate API delay
        setTimeout(() => {
            if (userId > 0) {
                resolve({
                    id: userId,
                    name: `User ${userId}`,
                    email: `user${userId}@example.com`,
                    createdAt: new Date()
                });
            } else {
                reject(new Error('Invalid user ID'));
            }
        }, 1000);
    });
}

// Using Promises with .then()
console.log('=== Promise Demo ===');
console.log('Fetching user data...');

fetchUserData(123)
    .then(user => {
        console.log('User data received:', user);
        return fetchUserData(456); // Chain another promise
    })
    .then(secondUser => {
        console.log('Second user data:', secondUser);
    })
    .catch(error => {
        console.error('Error:', error.message);
    });

// Using async/await (modern, cleaner syntax)
async function getUsersAsync() {
    console.log('=== Async/Await Demo ===');
    
    try {
        console.log('Fetching users...');
        
        // Wait for multiple operations
        const user1 = await fetchUserData(789);
        console.log('First user:', user1.name);
        
        const user2 = await fetchUserData(101);  
        console.log('Second user:', user2.name);
        
        // Parallel execution
        console.log('Fetching users in parallel...');
        const [user3, user4] = await Promise.all([
            fetchUserData(111),
            fetchUserData(222)
        ]);
        
        console.log('Parallel results:', user3.name, user4.name);
        
    } catch (error) {
        console.error('Async error:', error.message);
    }
}

// Call the async function
getUsersAsync();
```

#### Working with APIs

```javascript
// HTTP requests using fetch API
async function apiDemo() {
    console.log('=== API Demo ===');
    
    try {
        // GET request
        console.log('Fetching data from API...');
        const response = await fetch('https://jsonplaceholder.typicode.com/users/1');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const user = await response.json();
        console.log('User data:', user.name, user.email);
        
        // POST request
        const newPost = {
            title: 'My First Post',
            body: 'This is the content of my post',
            userId: user.id
        };
        
        console.log('Creating new post...');
        const postResponse = await fetch('https://jsonplaceholder.typicode.com/posts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newPost)
        });
        
        const createdPost = await postResponse.json();
        console.log('Created post:', createdPost);
        
    } catch (error) {
        console.error('API Error:', error.message);
    }
}

// Uncomment to run API demo (requires internet connection)
// apiDemo();
```

### Error Handling

Robust apps handle failures gracefully. Use try/catch and throw custom errors when needed.

```javascript
// Basic try/catch
function parseUser(json) {
    try {
        const data = JSON.parse(json);
        if (!data.name) throw new Error('Missing name');
        return data;
    } catch (err) {
        console.error('Failed to parse user:', err.message);
        return null; // or rethrow
    }
}

console.log(parseUser('{"name":"Alice"}'));   // OK
console.log(parseUser('not-json'));              // Handled

// Custom error classes
class ValidationError extends Error {
    constructor(message, issues = []) {
        super(message);
        this.name = 'ValidationError';
        this.issues = issues;
    }
}

function validateUser(user) {
    const issues = [];
    if (!user.name) issues.push('name required');
    if (!user.email) issues.push('email required');
    if (issues.length) throw new ValidationError('Invalid user', issues);
    return true;
}

try {
    validateUser({ name: 'Bob' });
} catch (e) {
    if (e instanceof ValidationError) {
        console.log('Validation failed:', e.issues.join(', '));
    } else {
        console.log('Unexpected error:', e.message);
    }
}
```

---

## Installation

### Installing Bun

Bun works on macOS, Linux, and Windows (WSL).

#### macOS and Linux

```bash
# Install Bun
curl -fsSL https://bun.sh/install | bash

# Restart your terminal or run:
source ~/.zshrc  # if you use zsh (macOS default)
# or source ~/.bashrc if you use bash

# Verify installation
bun --version
```

#### Windows (WSL)

```bash
# First install WSL if you haven't already
# Then run the same commands as macOS/Linux
curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun --version
```

#### Upgrading Bun

```bash
# Update to latest version
bun upgrade

# Install specific version
bun upgrade --canary
```

### Your First Bun Project

```bash
# Create a new project
mkdir my-bun-app
cd my-bun-app

# Initialize package.json
bun init

# The interactive setup will ask:
# - package name: (my-bun-app)  
# - entry point: (index.js)
```

This creates a basic project structure:

```text
my-bun-app/
├── package.json
├── index.js
├── bun.lockb
└── README.md
```

Let's create our first Bun application:

```javascript
// index.js - Your first Bun app
console.log('🚀 Welcome to Bun!');
console.log(`Bun version: ${Bun.version}`);
console.log(`Platform: ${process.platform}`);
console.log(`Node.js compatibility: ${process.version}`);

// Bun-specific features
console.log('Current working directory:', import.meta.dir);
console.log('This file:', import.meta.path);

// Performance demonstration
const start = performance.now();

// Simulate some work
for (let i = 0; i < 1000000; i++) {
    Math.sqrt(i);
}

const end = performance.now();
console.log(`Computation took ${(end - start).toFixed(2)} milliseconds`);
```

Run your first Bun app:

```bash
# Run with Bun (fast startup)
bun run index.js

# Or simply
bun index.js
```

## Running JavaScript

### Basic Script Execution

```javascript
// math-demo.js - Mathematical operations demo
console.log('=== Mathematical Operations Demo ===');

// Basic calculations
function calculateCircle(radius) {
    return {
        area: Math.PI * radius * radius,
        circumference: 2 * Math.PI * radius,
        diameter: 2 * radius
    };
}

function fibonacci(n) {
    if (n <= 1) return n;
    
    let a = 0, b = 1;
    for (let i = 2; i <= n; i++) {
        [a, b] = [b, a + b];
    }
    return b;
}

// Demo calculations
const radius = 5;
const circle = calculateCircle(radius);

console.log(`Circle with radius ${radius}:`);
console.log(`  Area: ${circle.area.toFixed(2)}`);
console.log(`  Circumference: ${circle.circumference.toFixed(2)}`);
console.log(`  Diameter: ${circle.diameter}`);

console.log('\nFibonacci sequence (first 10 numbers):');
for (let i = 0; i < 10; i++) {
    console.log(`  F(${i}) = ${fibonacci(i)}`);
}

// Performance timing
const start = Bun.nanoseconds();
const result = fibonacci(30);
const duration = Bun.nanoseconds() - start;

console.log(`\nPerformance test:`);
console.log(`  F(30) = ${result}`);
console.log(`  Calculated in ${duration / 1_000_000} milliseconds`);
```

### Command Line Arguments

```javascript
// cli-demo.js - Command line arguments demo
console.log('=== Command Line Arguments Demo ===');

// Process arguments
const args = process.argv.slice(2); // Remove 'bun' and script name
console.log('Raw arguments:', args);

// Parse arguments manually
function parseArgs(argv) {
    const parsed = {
        _: [], // Positional arguments
        flags: {},
        options: {}
    };
    
    for (let i = 0; i < argv.length; i++) {
        const arg = argv[i];
        
        if (arg.startsWith('--')) {
            // Long option: --name=value or --name value
            const [key, value] = arg.substring(2).split('=');
            if (value) {
                parsed.options[key] = value;
            } else if (i + 1 < argv.length && !argv[i + 1].startsWith('-')) {
                parsed.options[key] = argv[++i];
            } else {
                parsed.flags[key] = true;
            }
        } else if (arg.startsWith('-')) {
            // Short flag: -v, -h, etc.
            parsed.flags[arg.substring(1)] = true;
        } else {
            // Positional argument
            parsed._.push(arg);
        }
    }
    
    return parsed;
}

// Parse and display
const parsed = parseArgs(args);
console.log('Parsed arguments:', parsed);

// Example usage
if (parsed.flags.h || parsed.flags.help) {
    console.log(`
Usage: bun cli-demo.js [options] [files...]

Options:
  --name <value>    Your name
  --age <value>     Your age  
  -v, --verbose     Verbose output
  -h, --help        Show this help
  
Examples:
  bun cli-demo.js --name Alice --age 25 file1.txt
  bun cli-demo.js -v --name Bob
    `);
    process.exit(0);
}

if (parsed.options.name) {
    const name = parsed.options.name;
    const age = parsed.options.age || 'unknown';
    console.log(`Hello ${name}! Age: ${age}`);
}

if (parsed.flags.v || parsed.flags.verbose) {
    console.log('Verbose mode enabled');
    console.log('Environment:', process.env.NODE_ENV || 'development');
    console.log('Platform:', process.platform);
    console.log('Architecture:', process.arch);
}

if (parsed._.length > 0) {
    console.log('Files to process:', parsed._);
}
```

Run with arguments:

```bash
# Basic usage
bun cli-demo.js

# With arguments  
bun cli-demo.js --name Alice --age 25 file1.txt file2.txt

# With flags
bun cli-demo.js -v --name Bob

# Show help
bun cli-demo.js --help
```

## Package Management

### Installing Packages

```bash
# Install dependencies
bun install express
bun install --dev typescript @types/node

# Install from package.json
bun install

# Install globally
bun install -g typescript

# Install specific version
bun install react@18.2.0
```

### Package.json Management

```json
{
  "name": "my-bun-app",
  "version": "1.0.0",
  "type": "module",
  "scripts": {
    "start": "bun run index.js",
    "dev": "bun --watch index.js",
    "build": "bun build index.js --outdir ./dist",
    "test": "bun test",
    "clean": "rm -rf dist node_modules"
  },
  "dependencies": {
    "express": "^4.18.0",
    "cors": "^2.8.5"
  },
  "devDependencies": {
    "@types/node": "^20.0.0",
    "typescript": "^5.0.0"
  }
}
```

### Using NPM Packages

```javascript
// package-demo.js - Using NPM packages with Bun
import express from 'express';
import cors from 'cors';

console.log('=== Package Demo ===');

// Express server setup
const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Routes
app.get('/', (req, res) => {
    res.json({
        message: 'Hello from Bun + Express!',
        timestamp: new Date().toISOString(),
        runtime: 'Bun',
        version: Bun.version
    });
});

app.get('/api/users', (req, res) => {
    const users = [
        { id: 1, name: 'Alice', email: 'alice@example.com' },
        { id: 2, name: 'Bob', email: 'bob@example.com' },
        { id: 3, name: 'Charlie', email: 'charlie@example.com' }
    ];
    
    res.json(users);
});

app.post('/api/users', (req, res) => {
    const { name, email } = req.body;
    
    if (!name || !email) {
        return res.status(400).json({ error: 'Name and email are required' });
    }
    
    const newUser = {
        id: Date.now(),
        name,
        email,
        createdAt: new Date().toISOString()
    };
    
    res.status(201).json(newUser);
});

// Start server
app.listen(PORT, () => {
    console.log(`🚀 Server running on http://localhost:${PORT}`);
    console.log(`🎯 Try: curl http://localhost:${PORT}/api/users`);
});
```

Install and run:

```bash
# Install dependencies
bun install express cors

# Run the server
bun package-demo.js
```

## File System Operations

### Reading and Writing Files

```javascript
// file-operations.js - File system operations demo
import { promises as fs } from 'fs';
import { join } from 'path';

console.log('=== File System Operations Demo ===');

async function fileOperationsDemo() {
    const dataDir = './data';
    const usersFile = join(dataDir, 'users.json');
    const logFile = join(dataDir, 'app.log');
    
    try {
        // Create directory if it doesn't exist
        try {
            await fs.access(dataDir);
        } catch {
            console.log('Creating data directory...');
            await fs.mkdir(dataDir, { recursive: true });
        }
        
        // Sample data
        const users = [
            { id: 1, name: 'Alice', email: 'alice@example.com' },
            { id: 2, name: 'Bob', email: 'bob@example.com' },
            { id: 3, name: 'Charlie', email: 'charlie@example.com' }
        ];
        
        // Write JSON file
        console.log('Writing users data...');
        await fs.writeFile(usersFile, JSON.stringify(users, null, 2));
        
        // Read JSON file
        console.log('Reading users data...');
        const userData = await fs.readFile(usersFile, 'utf-8');
        const parsedUsers = JSON.parse(userData);
        
        console.log('Users loaded:', parsedUsers.length);
        parsedUsers.forEach(user => {
            console.log(`  ${user.name} (${user.email})`);
        });
        
        // Append to log file
        const logEntry = `${new Date().toISOString()} - Application started, ${users.length} users loaded\n`;
        await fs.appendFile(logFile, logEntry);
        
        // Read log file
        console.log('\nReading log file...');
        const logContent = await fs.readFile(logFile, 'utf-8');
        console.log('Log contents:');
        console.log(logContent);
        
        // File stats
        console.log('File statistics:');
        const userStats = await fs.stat(usersFile);
        const logStats = await fs.stat(logFile);
        
        console.log(`  ${usersFile}: ${userStats.size} bytes, modified ${userStats.mtime}`);
        console.log(`  ${logFile}: ${logStats.size} bytes, modified ${logStats.mtime}`);
        
        // List directory contents
        console.log('\nDirectory contents:');
        const files = await fs.readdir(dataDir);
        for (const file of files) {
            const filePath = join(dataDir, file);
            const stats = await fs.stat(filePath);
            console.log(`  ${file}: ${stats.isDirectory() ? 'DIR' : 'FILE'} (${stats.size} bytes)`);
        }
        
    } catch (error) {
        console.error('File operation error:', error.message);
    }
}

// Bun-specific file operations
async function bunFileOperations() {
    console.log('\n=== Bun-Specific File Operations ===');
    
    try {
        // Using Bun.file() for optimized file operations
        const configData = {
            app: {
                name: 'My Bun App',
                version: '1.0.0',
                debug: true
            },
            database: {
                host: 'localhost',
                port: 5432,
                name: 'myapp'
            }
        };
        
        // Write file with Bun.write
        await Bun.write('./config.json', JSON.stringify(configData, null, 2));
        console.log('Config file written with Bun.write');
        
        // Read file with Bun.file
        const file = Bun.file('./config.json');
        const config = await file.json();
        console.log('Config loaded:', config.app.name);
        
        // File info
        console.log(`File size: ${file.size} bytes`);
        console.log(`File type: ${file.type}`);
        
        // Read as different formats
        const textContent = await file.text();
        const buffer = await file.arrayBuffer();
        
        console.log(`Text length: ${textContent.length} characters`);
        console.log(`Buffer size: ${buffer.byteLength} bytes`);
        
    } catch (error) {
        console.error('Bun file operation error:', error.message);
    }
}

// Run demos
await fileOperationsDemo();
await bunFileOperations();
```

### Working with CSV and Data Files

```javascript
// csv-demo.js - CSV and data file processing
console.log('=== CSV Processing Demo ===');

// Simple CSV parser
function parseCSV(csvText) {
    const lines = csvText.trim().split('\n');
    const headers = lines[0].split(',').map(h => h.trim());
    
    return lines.slice(1).map(line => {
        const values = line.split(',').map(v => v.trim());
        const obj = {};
        
        headers.forEach((header, index) => {
            obj[header] = values[index];
        });
        
        return obj;
    });
}

// CSV generator
function generateCSV(data) {
    if (data.length === 0) return '';
    
    const headers = Object.keys(data[0]);
    const csvRows = [
        headers.join(','),
        ...data.map(row => 
            headers.map(header => `"${row[header] || ''}"`).join(',')
        )
    ];
    
    return csvRows.join('\n');
}

async function csvDemo() {
    try {
        // Sample data
        const employees = [
            { id: 1, name: 'Alice Johnson', department: 'Engineering', salary: 85000 },
            { id: 2, name: 'Bob Smith', department: 'Marketing', salary: 65000 },
            { id: 3, name: 'Charlie Brown', department: 'Sales', salary: 70000 },
            { id: 4, name: 'Diana Wilson', department: 'Engineering', salary: 90000 }
        ];
        
        // Generate CSV
        const csvContent = generateCSV(employees);
        console.log('Generated CSV:');
        console.log(csvContent);
        
        // Write CSV file
        await Bun.write('./employees.csv', csvContent);
        console.log('\nCSV file written');
        
        // Read and parse CSV
        const file = Bun.file('./employees.csv');
        const csvText = await file.text();
        const parsedData = parseCSV(csvText);
        
        console.log('\nParsed CSV data:');
        parsedData.forEach(emp => {
            console.log(`${emp.name} - ${emp.department} - $${emp.salary}`);
        });
        
        // Data analysis
        const totalSalary = parsedData.reduce((sum, emp) => sum + parseInt(emp.salary), 0);
        const avgSalary = totalSalary / parsedData.length;
        
        console.log(`\nSalary Analysis:`);
        console.log(`Total: $${totalSalary.toLocaleString()}`);
        console.log(`Average: $${avgSalary.toLocaleString()}`);
        
        // Group by department
        const byDepartment = parsedData.reduce((groups, emp) => {
            if (!groups[emp.department]) {
                groups[emp.department] = [];
            }
            groups[emp.department].push(emp);
            return groups;
        }, {});
        
        console.log('\nEmployees by department:');
        for (const [dept, emps] of Object.entries(byDepartment)) {
            console.log(`${dept}: ${emps.length} employees`);
        }
        
    } catch (error) {
        console.error('CSV processing error:', error.message);
    }
}

await csvDemo();
```

## HTTP Server

### Basic HTTP Server

```javascript
// server.js - Basic HTTP server with Bun
console.log('=== Bun HTTP Server Demo ===');

// Create a basic HTTP server using Bun's built-in server
const server = Bun.serve({
    port: 3000,
    hostname: 'localhost',
    
    async fetch(request) {
        const url = new URL(request.url);
        const method = request.method;
        
        console.log(`${method} ${url.pathname}`);
        
        // Route handling
        if (url.pathname === '/') {
            return new Response(JSON.stringify({
                message: 'Welcome to Bun HTTP Server!',
                timestamp: new Date().toISOString(),
                runtime: 'Bun',
                version: Bun.version
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        if (url.pathname === '/health') {
            return new Response(JSON.stringify({
                status: 'healthy',
                uptime: process.uptime(),
                memory: process.memoryUsage()
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        if (url.pathname === '/api/time') {
            const now = new Date();
            return new Response(JSON.stringify({
                timestamp: now.toISOString(),
                unix: now.getTime(),
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        // Handle POST requests
        if (method === 'POST' && url.pathname === '/api/echo') {
            const body = await request.text();
            return new Response(JSON.stringify({
                method: method,
                body: body,
                headers: Object.fromEntries(request.headers),
                receivedAt: new Date().toISOString()
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        // File serving example
        if (url.pathname.startsWith('/static/')) {
            const filePath = url.pathname.slice(8); // Remove '/static/'
            try {
                const file = Bun.file(`./public/${filePath}`);
                return new Response(file);
            } catch {
                return new Response('File not found', { status: 404 });
            }
        }
        
        // 404 for unmatched routes
        return new Response(JSON.stringify({
            error: 'Not Found',
            path: url.pathname,
            method: method
        }), {
            status: 404,
            headers: { 'Content-Type': 'application/json' }
        });
    },
    
    // Error handling
    error(error) {
        console.error('Server error:', error);
        return new Response(`Server Error: ${error.message}`, { 
            status: 500 
        });
    }
});

console.log(`🚀 Server running at http://localhost:${server.port}`);
console.log('Try these endpoints:');
console.log('  GET  http://localhost:3000/');
console.log('  GET  http://localhost:3000/health');
console.log('  GET  http://localhost:3000/api/time');
console.log('  POST http://localhost:3000/api/echo');

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('\n👋 Shutting down server...');
    server.stop();
    process.exit(0);
});
```

### Idiomatic routing with routes (faster, simpler)

```javascript
// routes-server.js - use Bun.serve "routes" and per-method handlers
const routesServer = Bun.serve({
    port: 3002,
    routes: {
        '/': new Response('Home'),
        '/health': new Response('OK'),
        '/api/time': () => Response.json({ now: new Date().toISOString() }),
        '/api/posts': {
            GET: () => Response.json([{ id: 1, title: 'Hello' }]),
            POST: async (req) => {
                const post = await req.json().catch(() => ({}));
                return Response.json({ id: crypto.randomUUID(), ...post }, { status: 201 });
            },
        },
        // Parameter and wildcard routes
        '/users/:id': (req) => new Response(`User ${req.params.id}`),
        '/api/*': new Response('Not found', { status: 404 }),
    },
    fetch() { return new Response('Not Found', { status: 404 }); },
});
console.log('Routes server:', routesServer.url.toString());
```

### Static vs File responses

```javascript
// static-vs-file.js - choose based on size/update pattern
Bun.serve({
    routes: {
        // Static: buffers content at startup, fastest and supports ETag
        '/logo.png': new Response(await Bun.file('./public/logo.png').bytes(), {
            headers: { 'Content-Type': 'image/png' },
        }),
        // File: streams per-request, supports Range/Last-Modified and 404
        '/download.zip': new Response(Bun.file('./files/app.zip')),
    },
    fetch() { return new Response('404', { status: 404 }); },
});
```

### Lifecycle and hot reload

```javascript
// lifecycle.js
const srv = Bun.serve({
    routes: { '/version': Response.json({ v: '1' }) },
    fetch() { return new Response('v1'); },
});
console.log('Running at', srv.url.toString());

// Update handlers without restart
setTimeout(() => {
    srv.reload({
        routes: { '/version': Response.json({ v: '2' }) },
        fetch() { return new Response('v2'); },
    });
}, 2000);

// Graceful stop after 5s (finishes in-flight by default)
setTimeout(async () => { await srv.stop(); }, 5000);
```

### Per-request controls and client info

```javascript
// per-request.js
Bun.serve({
    async fetch(req, server) {
        // Increase idle timeout to 60s for this request
        server.timeout(req, 60);

        const ip = server.requestIP(req);
        const who = ip ? `${ip.address}:${ip.port}` : 'unknown';
        if (new URL(req.url).pathname === '/whoami') {
            return Response.json({ who });
        }
        return new Response('ok');
    },
});
```

### Advanced Server with Routing

```javascript
// advanced-server.js - Advanced HTTP server with routing
class BunRouter {
    constructor() {
        this.routes = {
            GET: new Map(),
            POST: new Map(),
            PUT: new Map(),
            DELETE: new Map()
        };
        this.middleware = [];
    }
    
    // Add middleware
    use(fn) {
        this.middleware.push(fn);
    }
    
    // Route methods
    get(path, handler) {
        this.routes.GET.set(path, handler);
    }
    
    post(path, handler) {
        this.routes.POST.set(path, handler);
    }
    
    put(path, handler) {
        this.routes.PUT.set(path, handler);
    }
    
    delete(path, handler) {
        this.routes.DELETE.set(path, handler);
    }
    
    // Handle request
    async handle(request) {
        const url = new URL(request.url);
        const method = request.method;
        
        // Create context object
        const ctx = {
            request,
            url,
            method,
            query: Object.fromEntries(url.searchParams),
            body: null,
            headers: Object.fromEntries(request.headers)
        };
        
        // Parse body for POST/PUT requests
        if (method === 'POST' || method === 'PUT') {
            const contentType = request.headers.get('content-type');
            if (contentType?.includes('application/json')) {
                try {
                    ctx.body = await request.json();
                } catch {
                    return new Response('Invalid JSON', { status: 400 });
                }
            } else {
                ctx.body = await request.text();
            }
        }
        
        // Run middleware
        for (const middleware of this.middleware) {
            const result = await middleware(ctx);
            if (result) return result; // Early return if middleware returns response
        }
        
        // Find and execute route handler
        const routes = this.routes[method];
        if (routes && routes.has(url.pathname)) {
            try {
                const handler = routes.get(url.pathname);
                const result = await handler(ctx);
                
                if (result instanceof Response) {
                    return result;
                } else {
                    return new Response(JSON.stringify(result), {
                        headers: { 'Content-Type': 'application/json' }
                    });
                }
            } catch (error) {
                console.error('Route handler error:', error);
                return new Response(JSON.stringify({
                    error: 'Internal Server Error',
                    message: error.message
                }), {
                    status: 500,
                    headers: { 'Content-Type': 'application/json' }
                });
            }
        }
        
        // 404 for unmatched routes
        return new Response(JSON.stringify({
            error: 'Not Found',
            path: url.pathname,
            method: method
        }), {
            status: 404,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Create router and define routes
const router = new BunRouter();

// Middleware examples
router.use(async (ctx) => {
    // CORS middleware
    if (ctx.method === 'OPTIONS') {
        return new Response(null, {
            status: 204,
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers': 'Content-Type, Authorization'
            }
        });
    }
});

router.use(async (ctx) => {
    // Logging middleware
    console.log(`${ctx.method} ${ctx.url.pathname} - ${new Date().toISOString()}`);
});

// Define routes
router.get('/', async (ctx) => {
    return {
        message: 'Advanced Bun Server with Routing',
        timestamp: new Date().toISOString(),
        endpoints: [
            'GET /',
            'GET /api/users',
            'POST /api/users',
            'GET /api/users/:id',
            'PUT /api/users/:id',
            'DELETE /api/users/:id'
        ]
    };
});

// In-memory data store
let users = [
    { id: 1, name: 'Alice', email: 'alice@example.com' },
    { id: 2, name: 'Bob', email: 'bob@example.com' }
];
let nextId = 3;

// User management routes
router.get('/api/users', async (ctx) => {
    const { limit, search } = ctx.query;
    
    let result = users;
    
    // Search functionality
    if (search) {
        result = users.filter(user => 
            user.name.toLowerCase().includes(search.toLowerCase()) ||
            user.email.toLowerCase().includes(search.toLowerCase())
        );
    }
    
    // Limit results
    if (limit) {
        result = result.slice(0, parseInt(limit));
    }
    
    return {
        users: result,
        total: users.length,
        filtered: result.length
    };
});

router.post('/api/users', async (ctx) => {
    const { name, email } = ctx.body;
    
    if (!name || !email) {
        return new Response(JSON.stringify({
            error: 'Bad Request',
            message: 'Name and email are required'
        }), {
            status: 400,
            headers: { 'Content-Type': 'application/json' }
        });
    }
    
    const newUser = {
        id: nextId++,
        name,
        email,
        createdAt: new Date().toISOString()
    };
    
    users.push(newUser);
    
    return new Response(JSON.stringify(newUser), {
        status: 201,
        headers: { 'Content-Type': 'application/json' }
    });
});

// Start server
const server = Bun.serve({
    port: 3001,
    async fetch(request) {
        return await router.handle(request);
    }
});

console.log(`🚀 Advanced server running at http://localhost:${server.port}`);
console.log('Try these commands:');
console.log('  curl http://localhost:3001/');
console.log('  curl http://localhost:3001/api/users');
console.log('  curl -X POST http://localhost:3001/api/users -H "Content-Type: application/json" -d \'{"name":"Charlie","email":"charlie@example.com"}\'');
```

## HTTP Client

Use Bun's fetch (WHATWG standard with Bun extensions) for fast, flexible HTTP(S) requests.

### Basic requests and JSON

```javascript
// basic-get.js
try {
    const res = await fetch('https://jsonplaceholder.typicode.com/todos/1');
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    console.log('todo:', data.title);
} catch (err) {
    console.error('request failed:', err.message);
}

// timeout with AbortSignal
try {
    const res = await fetch('https://httpbin.org/delay/3', {
        signal: AbortSignal.timeout(1000), // cancel after 1s
    });
    console.log('status:', res.status);
} catch (err) {
    console.error('timed out:', err.name || err.message);
}
// Run: bun basic-get.js
```

### POST JSON, FormData, and file upload

```javascript
// post-examples.js
// JSON body
const created = await fetch('https://httpbin.org/post', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ hello: 'bun' }),
});
console.log('json response:', (await created.json()).json);

// FormData (Content-Type set automatically with boundary)
const form = new FormData();
form.set('name', 'alice');
form.set('avatar', new Blob(['hi'], { type: 'text/plain' }), 'note.txt');
const formRes = await fetch('https://httpbin.org/post', { method: 'POST', body: form });
console.log('form files:', Object.keys((await formRes.json()).files));

// Upload a real file with Bun.file
const fd = new FormData();
fd.set('file', Bun.file('./README.md'));
const up = await fetch('https://httpbin.org/post', { method: 'POST', body: fd });
console.log('uploaded size:', (await up.json()).headers['Content-Length']);
// Run: bun post-examples.js
```

### Streaming responses and requests

```javascript
// streaming.js
// Stream response directly to disk (fast path)
const res = await fetch('https://httpbin.org/stream/5');
await Bun.write('out.txt', res); // Bun.write can take a Response/ReadableStream
console.log('saved to out.txt');

// Read chunks manually
const r = await fetch('https://httpbin.org/bytes/64');
for await (const chunk of r.body) {
    console.log('chunk', chunk.byteLength || chunk.length);
}

// Streaming request body
const stream = new ReadableStream({
    start(controller) {
        controller.enqueue('Hello');
        controller.enqueue(' from ');
        controller.enqueue('ReadableStream');
        controller.close();
    },
});
const echoed = await fetch('https://httpbin.org/post', { method: 'POST', body: stream });
console.log('echoed length:', (await echoed.json()).headers['Content-Length']);
// Run: bun streaming.js
```

### Headers, cookies, and debugging

```javascript
// headers-debug.js
const headers = new Headers({ 'X-Trace-Id': crypto.randomUUID() });
headers.set('Accept', 'application/json');

const res = await fetch('https://httpbin.org/headers', {
    headers,
    // Bun extension: verbose logging of request/response
    verbose: true,
});
console.log((await res.json()).headers);
// Note: Cookies follow standard fetch semantics (no cookie jar by default).
```

### Advanced transports and options

```javascript
// advanced-transports.js
// HTTP proxy
await fetch('http://example.com', { proxy: 'http://localhost:3128' }).catch(e => console.error('proxy error:', e.message));

// Unix domain socket
// await fetch('http://localhost/status', { unix: '/var/run/my.sock' });

// TLS options (use with care!)
await fetch('https://example.com', {
    tls: {
        // Provide client certs when required
        // key: Bun.file('/path/to/key.pem'),
        // cert: Bun.file('/path/to/cert.pem'),
        // Custom validation
        // checkServerIdentity: (hostname, cert) => undefined, // return Error to reject
        // Trust-all (INSECURE):
        rejectUnauthorized: false,
    },
    // Bun extensions
    decompress: true, // automatic gzip/br/deflate (default true)
    keepalive: true,  // connection reuse (default true); set false to disable
}).catch(e => console.error('tls fetch:', e.message));
```

### Protocols beyond HTTP(S)

```javascript
// protocols.js
// file://
const fileRes = await fetch(`file://${import.meta.dir}/../README.md`);
console.log('file bytes:', (await fileRes.bytes()).length);

// data:
const dataRes = await fetch('data:text/plain;base64,SGVsbG8sIEJ1biE=');
console.log(await dataRes.text());

// blob:
const blob = new Blob(['Hello Blob'], { type: 'text/plain' });
const url = URL.createObjectURL(blob);
console.log('blob text:', await (await fetch(url)).text());

// S3 (credentials required; see Bun docs)
// const s3Get = await fetch('s3://my-bucket/path/to/object');
// console.log('s3 status:', s3Get.status);
```

### Performance helpers

```javascript
// performance-helpers.js
import { dns, fetch as bunFetch } from 'bun';

dns.prefetch('bun.com');         // warm DNS cache
bunFetch.preconnect('https://bun.com'); // warm TCP/TLS connection

const resPerf = await fetch('https://bun.com');
console.log('status:', resPerf.status);
```

### Robust error handling pattern

```javascript
// error-handling.js
async function safeFetch(url, init) {
    try {
        const res = await fetch(url, init);
        if (!res.ok) {
            const body = await res.text().catch(() => '');
            throw new Error(`HTTP ${res.status} ${res.statusText} — ${body.slice(0, 200)}`);
        }
        return res;
    } catch (e) {
        // Network/TLS/timeout/etc
        console.error('fetch failed:', e.message);
        throw e;
    }
}

const r = await safeFetch('https://httpbin.org/status/200');
console.log('ok:', r.status);
```

### Calling your local Bun server

```javascript
// local-call.js
// If you have: const server = Bun.serve({ fetch: () => new Response('ok') })
// You can call it using its base URL
// const res = await fetch(new URL('/health', server.url));
// console.log(await res.text());
```

Notes

- Bun implements standard fetch plus extensions: proxy, unix, tls, verbose, keepalive, decompress, and more.
- For large downloads, prefer Bun.write(response, filePath) to avoid buffering.
- Avoid rejectUnauthorized: false in production; use proper CAs/certs.

## Environment Variables

Working with configuration is simple in Bun. Use a .env file for secrets and access values via process.env (Node-compatible) or Bun.env.

### Create a .env file

```env
# .env
PORT=3000
API_KEY=super-secret-key
NODE_ENV=development
FEATURE_FLAG=true
```

### Read environment variables

```javascript
// env-demo.js
const port = Number(process.env.PORT || 3000);
const apiKey = process.env.API_KEY ?? '';
const env = process.env.NODE_ENV || 'development';
const featureFlag = (process.env.FEATURE_FLAG || 'false') === 'true';

console.log('Config loaded:');
console.log({ port, apiKey: apiKey.slice(0, 3) + '***', env, featureFlag });

// Bun also exposes Bun.env as a fast, typed map (string -> string)
console.log('Bun.env.PORT =', Bun.env.PORT);
```

Run with an env file:

```bash
bun --env-file=.env env-demo.js
```

Tip: Bun will also load .env files automatically in many common cases. Using --env-file makes it explicit and lets you choose a file (e.g., .env.local).

### Use env in a server

```javascript
// server-env.js
const PORT = Number(process.env.PORT || 3002);

const server = Bun.serve({
    port: PORT,
    fetch() {
        return new Response(
            JSON.stringify({ env: process.env.NODE_ENV || 'development' }),
            { headers: { 'Content-Type': 'application/json' } }
        );
    }
});

console.log(`Server on http://localhost:${server.port}`);
```

Run with a different env file:

```bash
bun --env-file=.env server-env.js
```

## WebSockets

Bun has first-class WebSocket support built into Bun.serve. Upgrade requests to a WebSocket endpoint and handle events in the websocket option.

### Minimal WebSocket echo server

```javascript
// ws-server.js
const server = Bun.serve({
    port: 3003,
    fetch(req, server) {
        const { pathname } = new URL(req.url);
        if (pathname === '/ws') {
            // Upgrade the request to a WebSocket
            if (server.upgrade(req)) return; // Handled by websocket handlers
            return new Response('Upgrade failed', { status: 500 });
        }
        return new Response('WebSocket server: connect to /ws');
    },
    websocket: {
        open(ws) {
            ws.send('Welcome! Send a message and I will echo it.');
        },
        message(ws, message) {
            // Echo back
            ws.send(`You said: ${message}`);
        },
        close(ws, code, reason) {
            console.log('Socket closed', code, reason);
        }
    }
});

console.log(`🔌 WebSocket server on ws://localhost:${server.port}/ws`);
```

### Simple browser/client

```javascript
// client (run in browser devtools or a small HTML page)
const socket = new WebSocket('ws://localhost:3003/ws');
socket.onopen = () => socket.send('Hello from client!');
socket.onmessage = (e) => console.log('Message:', e.data);
```

## SQLite

Bun ships a built-in, zero-dependency SQLite3 driver via the `bun:sqlite` module. It’s fast, synchronous, and ideal for small services, CLIs, and local apps.

### Open a database

```javascript
import { Database } from 'bun:sqlite';

// File-backed DB (created if missing when { create: true } or by default on most platforms)
const db = new Database('app.db', { readonly: false, create: true });

// In-memory DB (all of these open an in-memory database)
const mem1 = new Database(':memory:');
const mem2 = new Database();
const mem3 = new Database('');

// Readonly mode
const ro = new Database('app.db', { readonly: true });
```

Load via ES module import attribute:

```javascript
// Equivalent to: new Database('./mydb.sqlite')
import db from './mydb.sqlite' with { type: 'sqlite' };
console.log(db.query('select 1 as x').get());
```

Recommended: enable WAL for performance (especially many readers, single writer):

```javascript
db.exec("PRAGMA journal_mode = WAL;");
```

### Constructor options worth knowing

- strict: true — throw when a named parameter is missing and allow binding without prefixes ($/:/@)
- safeIntegers: true — return SQLite 64-bit integers as bigint; validate bound bigints are 64-bit

```javascript
const strictDb = new Database(':memory:', { strict: true, safeIntegers: true });
```

### Closing, lifetime, and utilities

- db.close(false) — close connection, let in-flight queries finish (called automatically on GC)
- db.close(true) — throw if there are pending queries
- using — auto-close when leaving scope (Bun ≥ 1.1.14)
- serialize/deserialize — snapshot a DB to a Uint8Array and restore later

```javascript
{
    using scoped = new Database('scoped.sqlite');
    using q = scoped.query("select 'Hello' as msg");
    console.log(q.get()); // { msg: 'Hello' }
}

const contents = db.serialize();
const restored = Database.deserialize(contents);
```

### Preparing and executing queries

- db.query(sql) — prepare and cache a Statement on the Database
- db.prepare(sql) — prepare without caching
- db.run(sql, params?) / db.exec(sql, params?) — execute immediately; returns { lastInsertRowid, changes }

```javascript
// Schema
db.run(`
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )
`);

// Prepared, cached statement
const insert = db.query('INSERT INTO users (name, email) VALUES ($name, $email)');
insert.run({ $name: 'Alice', $email: 'alice@example.com' });
insert.run({ $name: 'Bob', $email: 'bob@example.com' });

// Statement APIs
const select = db.query('SELECT id, name, email, created_at FROM users ORDER BY id');
console.log(select.all());        // Array<{...}>
console.log(select.get());        // First row or undefined
console.log(select.values());     // Array<Array<values>>
for (const row of select.iterate()) { /* stream rows */ }

// Debug & lifecycle
console.log(select.toString());   // Expanded SQL with last bound params
select.finalize();                // Free resources (GC normally handles this)
```

Parameter binding:

- Named: { $param: value } or with strict: true — { param: value }
- Positional: ?1, ?2 … pass values in order

```javascript
db.query('SELECT $greet as g').get({ $greet: 'hi' });
db.query('SELECT ?1, ?2').all('hello', 42);
```

Map rows to a class (constructor isn’t called; prototype/methods are attached):

```javascript
class UserRow { get isGmail() { return this.email?.endsWith('@gmail.com'); } }
const users = db.query('SELECT id, email FROM users').as(UserRow).all();
console.log(users[0].isGmail);
```

Iterate large result sets lazily:

```javascript
for (const row of db.query('SELECT * FROM users').iterate()) {
    // process row
}
```

### Transactions

Wrap multiple statements atomically with db.transaction(fn). It returns a callable function; variants: .deferred/.immediate/.exclusive. Nested transactions become savepoints.

```javascript
const insertUser = db.query('INSERT INTO users (name, email) VALUES ($name, $email)');

const insertMany = db.transaction((rows) => {
    for (const r of rows) insertUser.run(r);
    return rows.length;
});

const n = insertMany([
    { $name: 'Charlie', $email: 'charlie@example.com' },
    { $name: 'Diana', $email: 'diana@example.com' },
]);
console.log('Inserted', n);

insertMany.deferred([...]);   // BEGIN DEFERRED
insertMany.immediate([...]);  // BEGIN IMMEDIATE
insertMany.exclusive([...]);  // BEGIN EXCLUSIVE
```

### Extensions and file control

```javascript
import { Database, constants } from 'bun:sqlite';

db.loadExtension('myext');

// Prevent persistent WAL files (platform-dependent)
db.fileControl(constants.SQLITE_FCNTL_PERSIST_WAL, 0);
```

### Datatypes

- string → TEXT
- number → INTEGER/DECIMAL
- boolean → INTEGER (1/0)
- Uint8Array/Buffer → BLOB
- bigint → INTEGER (enable safeIntegers to read as bigint)
- null → NULL

### Minimal CRUD example

```javascript
// sqlite-crud.js
import { Database } from 'bun:sqlite';
const db = new Database('app.db', { create: true });
db.exec('PRAGMA journal_mode = WAL;');

db.run(`CREATE TABLE IF NOT EXISTS notes (id INTEGER PRIMARY KEY, text TEXT)`);
const add = db.query('INSERT INTO notes (text) VALUES ($text)');
add.run({ $text: 'hello' });
add.run({ $text: 'world' });

console.log(db.query('SELECT id, text FROM notes').all());
console.log(db.run('UPDATE notes SET text = $t WHERE id = $id', { $id: 1, $t: 'hi' }));
console.log(db.query('SELECT COUNT(*) as c FROM notes').get().c);
```

## Redis

Bun provides a native, promise-based Redis client (v1.2.9+). By default it reads connection info from REDIS_URL, otherwise redis://localhost:6379.

### Quick sanity check

```ts
import { redis } from "bun";

await redis.set("greeting", "Hello from Bun Redis");
console.log(await redis.get("greeting"));
await redis.del("greeting");
```

### Connecting and lifecycle

```ts
import { redis, RedisClient } from "bun";

// Shared default client (uses REDIS_URL)
await redis.set("hello", "world");
console.log(await redis.get("hello"));

// Dedicated client
const client = new RedisClient("redis://localhost:6379");
await client.set("counter", "0");
await client.incr("counter");
console.log("counter:", await client.get("counter"));
client.close();
```

Manual connect/close and connection status:

```ts
import { RedisClient } from "bun";

const c = new RedisClient();
console.log("connected?", c.connected);
await c.connect();
console.log("connected?", c.connected);
await c.set("k", "v");
console.log("bufferedAmount:", c.bufferedAmount);
c.close();
```

Connection events:

```ts
import { RedisClient } from "bun";

const c = new RedisClient();
c.onconnect = () => console.log("Connected to Redis");
c.onclose = (err) => console.error("Disconnected:", err);
await c.connect();
c.close();
```

### Connection options and URL formats

```ts
import { RedisClient } from "bun";

const c = new RedisClient("redis://localhost:6379", {
    connectionTimeout: 5_000,
    idleTimeout: 30_000,
    autoReconnect: true,
    maxRetries: 10,
    enableOfflineQueue: true,
    enableAutoPipelining: true,
    // tls: true,
    // tls: { rejectUnauthorized: true },
});

// URLs
new RedisClient("redis://localhost:6379");
new RedisClient("redis://username:password@localhost:6379");
new RedisClient("redis://localhost:6379/0");
new RedisClient("rediss://localhost:6379");
new RedisClient("redis+tls://localhost:6379");
new RedisClient("redis+unix:///tmp/redis.sock");
new RedisClient("redis+tls+unix:///tmp/redis.sock");
```

Reconnection uses exponential backoff (to 2s cap), controlled by the options above. Commands queue while offline if enableOfflineQueue=true.

### Error handling

```ts
import { redis } from "bun";

try {
    await redis.get("missing");
} catch (error: any) {
    if (error.code === "ERR_REDIS_CONNECTION_CLOSED") {
        console.error("Connection closed");
    } else if (error.code === "ERR_REDIS_AUTHENTICATION_FAILED") {
        console.error("Auth failed");
    } else if (error.code === "ERR_REDIS_INVALID_RESPONSE") {
        console.error("Invalid response");
    } else {
        console.error("Unexpected:", error);
    }
}
```

### Basic operations

Strings and keys:

```ts
import { redis } from "bun";

await redis.set("user:1:name", "Alice");
console.log(await redis.get("user:1:name"));
await redis.del("user:1:name");
console.log(await redis.exists("user:1:name"));

await redis.set("session:123", "active");
await redis.expire("session:123", 3600);
console.log(await redis.ttl("session:123"));
```

Numeric:

```ts
await redis.set("counter", "0");
await redis.incr("counter");
await redis.decr("counter");
```

Hashes:

```ts
await redis.hmset("user:123", [
    "name", "Alice",
    "email", "alice@example.com",
    "active", "true",
]);
const [name, email] = await redis.hmget("user:123", ["name", "email"]);
await redis.hincrby("user:123", "visits", 1);
await redis.hincrbyfloat("user:123", "score", 1.5);
```

Sets:

```ts
await redis.sadd("tags", "javascript");
console.log(await redis.sismember("tags", "javascript"));
console.log(await redis.smembers("tags"));
console.log(await redis.srandmember("tags"));
console.log(await redis.spop("tags"));
await redis.srem("tags", "javascript");
```

Lists (via raw commands):

```ts
await redis.send("LPUSH", ["mylist", "value1", "value2"]);
const items = await redis.send("LRANGE", ["mylist", "0", "-1"]);
console.log(items);
```

### Advanced usage

Auto-pipelining and batching:

```ts
import { redis, RedisClient } from "bun";

const [n1, n2] = await Promise.all([
    redis.get("user:1:name"),
    redis.get("user:2:email"),
]);

const noPipe = new RedisClient("redis://localhost:6379", { enableAutoPipelining: false });
await noPipe.set("x", "1");
noPipe.close();
```

Raw commands:

```ts
const info = await redis.send("INFO", []);
await redis.send("LPUSH", ["mylist", "a", "b"]);
const list = await redis.send("LRANGE", ["mylist", "0", "-1"]);
```

Transactions (MULTI/EXEC) via raw:

```ts
import { RedisClient } from "bun";
const tx = new RedisClient();
await tx.send("MULTI", []);
await tx.send("SET", ["balance:1", "100"]);
await tx.send("INCR", ["balance:1"]);
const replies = await tx.send("EXEC", []);
console.log(replies);
tx.close();
```

Type conversion notes

- Integers → number; bulk/simple strings → string; null bulk → null
- Arrays → arrays; RESP3 booleans → boolean; maps → objects; sets → arrays
- EXISTS and SISMEMBER return booleans
- Not auto-pipelined: AUTH, INFO, QUIT, MULTI/EXEC, WATCH, SCRIPT, SELECT, CLUSTER, DISCARD, UNWATCH, PIPELINE, SUBSCRIBE/UNSUBSCRIBE

### Reusable helpers

JSON cache, rate limiter, and session store you can drop into any app:

```ts
// redis-helpers.ts
import { RedisClient, redis as shared } from "bun";

type JsonValue = unknown;
export async function withClient<T>(fn: (c: RedisClient) => Promise<T>, c: RedisClient = (shared as unknown as RedisClient)) {
    return fn(c);
}

export const jsonCache = {
    async get<T = JsonValue>(key: string, c: RedisClient = (shared as unknown as RedisClient)) {
        const v = await c.get(key);
        return v ? (JSON.parse(v) as T) : null;
    },
    async set(key: string, value: JsonValue, ttlSecs?: number, c: RedisClient = (shared as unknown as RedisClient)) {
        await c.set(key, JSON.stringify(value));
        if (ttlSecs && ttlSecs > 0) await c.expire(key, ttlSecs);
    },
    async del(key: string, c: RedisClient = (shared as unknown as RedisClient)) {
        await c.del(key);
    },
};

export function createRateLimiter({ limit = 100, windowSecs = 3600 } = {}) {
    return async function check(ip: string, c: RedisClient = (shared as unknown as RedisClient)) {
        const key = `ratelimit:${ip}`;
        const count = await c.incr(key);
        if (count === 1) await c.expire(key, windowSecs);
        return { limited: count > limit, remaining: Math.max(0, limit - count) };
    };
}

export const sessionStore = {
    async create(userId: number | string, data: Record<string, unknown>, ttlSecs = 86_400, c: RedisClient = (shared as unknown as RedisClient)) {
        const id = crypto.randomUUID();
        const key = `session:${id}`;
        await c.hmset(key, [
            "userId", String(userId),
            "created", String(Date.now()),
            "data", JSON.stringify(data),
        ]);
        await c.expire(key, ttlSecs);
        return id;
    },
    async get(id: string, c: RedisClient = (shared as unknown as RedisClient)) {
        const key = `session:${id}`;
        const exists = await c.exists(key);
        if (!exists) return null;
        const [userId, created, data] = await c.hmget(key, ["userId", "created", "data"]);
        return { userId: Number(userId), created: Number(created), data: JSON.parse(String(data || "null")) } as {
            userId: number; created: number; data: unknown;
        };
    },
    async destroy(id: string, c: RedisClient = (shared as unknown as RedisClient)) {
        await c.del(`session:${id}`);
    },
};

// usage
await jsonCache.set("user:42", { name: "Ada" }, 3600);
console.log(await jsonCache.get("user:42"));
const rateLimit = createRateLimiter({ limit: 10, windowSecs: 60 });
console.log(await rateLimit("127.0.0.1"));
const id = await sessionStore.create(42, { role: "admin" });
console.log(await sessionStore.get(id));
await sessionStore.destroy(id);
```

### Example use cases

Caching:

```ts
import { redis } from "bun";
async function getUserWithCache(userId: number) {
    const key = `user:${userId}`;
    const cached = await redis.get(key);
    if (cached) return JSON.parse(cached);
    const user = { id: userId, name: "Alice" }; // replace with DB
    await redis.set(key, JSON.stringify(user));
    await redis.expire(key, 3600);
    return user;
}
```

Rate limiting:

```ts
import { redis } from "bun";
async function rateLimit(ip: string, limit = 100, windowSecs = 3600) {
    const key = `ratelimit:${ip}`;
    const count = await redis.incr(key);
    if (count === 1) await redis.expire(key, windowSecs);
    return { limited: count > limit, remaining: Math.max(0, limit - count) };
}
```

Session storage:

```ts
import { redis } from "bun";
export async function createSession(userId: number, data: unknown) {
    const id = crypto.randomUUID();
    const key = `session:${id}`;
    await redis.hmset(key, [
        "userId", String(userId),
        "created", String(Date.now()),
        "data", JSON.stringify(data),
    ]);
    await redis.expire(key, 86_400);
    return id;
}
export async function getSession(id: string) {
    const key = `session:${id}`;
    const exists = await redis.exists(key);
    if (!exists) return null;
    const [userId, created, data] = await redis.hmget(key, ["userId", "created", "data"]);
    return { userId: Number(userId), created: Number(created), data: JSON.parse(String(data)) };
}
```

Notes

- Pub/Sub and Streams currently lack high-level APIs in Bun; prefer alternative patterns or another client when you need them.
- Some commands are intentionally not auto-pipelined for correctness (see type conversion notes above).

## Workers and Child Processes

Run parallel work with Web Workers and spawn external processes when needed.

### Web Workers

```javascript
// worker.js
self.onmessage = (e) => {
    const n = Number(e.data || 35);
    const fib = (x) => (x <= 1 ? x : fib(x - 1) + fib(x - 2));
    postMessage({ n, result: fib(n) });
};
```

```javascript
// worker-main.js
const worker = new Worker(new URL('./worker.js', import.meta.url).href);
worker.onmessage = (e) => console.log('Worker result:', e.data);
worker.postMessage(25);
```

### Spawn processes

```javascript
// Basic spawn
const proc = Bun.spawn(["bun", "--version"]);
const versionText = await proc.stdout.text();
console.log(versionText.trim()); // e.g. 1.x.y
```

Configure options like cwd, env, and an onExit handler. You can also read the PID and await `exited`.

```javascript
const p = Bun.spawn(["bun", "--version"], {
    cwd: "./", // working directory
    env: { ...process.env, FOO: "bar" },
    onExit(sub, exitCode, signalCode, error) {
        console.log("exited", { exitCode, signalCode, err: error?.message });
    },
});

console.log("pid:", p.pid);
await p.exited; // resolves when process exits
```

Input streams (stdin)

```javascript
// 1) Incremental writes with a pipe
const cat1 = Bun.spawn(["cat"], { stdin: "pipe", stdout: "pipe" });
cat1.stdin.write("hello");
cat1.stdin.write(new TextEncoder().encode(" world"));
cat1.stdin.flush();
cat1.stdin.end();
console.log((await cat1.stdout.text()).trim()); // "hello world"

// 2) Pipe a ReadableStream to stdin
const stream = new ReadableStream({
    start(controller) {
        controller.enqueue("Hello from ");
        controller.enqueue("ReadableStream!");
        controller.close();
    },
});
const cat2 = Bun.spawn(["cat"], { stdin: stream, stdout: "pipe" });
console.log((await cat2.stdout.text()).trim()); // "Hello from ReadableStream!"

// 3) Use a Response/Request/File as stdin
const file = Bun.file("README.md");
const cat3 = Bun.spawn(["cat"], { stdin: file, stdout: "pipe" });
console.log((await cat3.stdout.text()).split("\n")[0]); // prints first line
```

Output streams (stdout/stderr)

```javascript
// Defaults: stdout is "pipe", stderr is "inherit"
const ls = Bun.spawn(["echo", "hi"], { stdout: "pipe" });
console.log((await ls.stdout.text()).trim()); // hi

// Write output directly to a file
await Bun.spawn(["echo", "file out"], { stdout: Bun.file("out.txt") }).exited;

// Stream all stdio to the parent terminal (useful for daemons/tools)
const tool = Bun.spawn(["bash", "-lc", "echo on stderr 1>&2 && echo on stdout"], {
    stdio: ["inherit", "inherit", "inherit"],
});
await tool.exited;
```

Exit handling and signals

```javascript
const sleep = Bun.spawn(["sleep", "100"], { stdout: "ignore" });
sleep.kill("SIGTERM");           // or sleep.kill(15)
await sleep.exited;
console.log({ killed: sleep.killed, code: sleep.exitCode, signal: sleep.signalCode });

// Detach so parent can exit without waiting
const daemon = Bun.spawn(["sleep", "5"], { stdio: ["ignore", "ignore", "ignore"] });
daemon.unref();
```

AbortSignal, timeout, and killSignal

```javascript
// Abort via AbortController
const ac = new AbortController();
const long = Bun.spawn({ cmd: ["sleep", "10"], signal: ac.signal });
ac.abort(); // later
await long.exited;

// Auto-kill after N ms (default SIGTERM; customize with killSignal)
const timed = Bun.spawn({ cmd: ["sleep", "10"], timeout: 500, killSignal: "SIGKILL" });
await timed.exited;
console.log("signal:", timed.signalCode); // e.g. "SIGKILL"
```

Resource usage

```javascript
const r = Bun.spawn(["bun", "--version"]);
await r.exited;
console.log(r.resourceUsage()); // { maxRSS, cpuTime, ... }
```

Synchronous API (spawnSync)

```javascript
// Good for small, short-lived commands in CLIs
const res = Bun.spawnSync(["echo", "hello"]);
console.log(res.stdout.toString().trim()); // "hello"

// Limit output buffer (kill if exceeded)
const limited = Bun.spawnSync({ cmd: ["yes"], maxBuffer: 100 }); // may exit early
```

IPC between processes

```typescript
// parent.ts — Bun ↔ Bun IPC
const child = Bun.spawn(["bun", new URL("./child.ts", import.meta.url).href], {
    ipc(message, sub) {
        console.log("from child:", message);
        sub.send({ pong: true });
        sub.kill();
    },
    stdio: ["inherit", "inherit", "inherit"],
});

child.send("ping");
```

```typescript
// child.ts
process.on("message", (msg) => {
    console.log("from parent:", msg);
    process.send({ ok: true });
});
```

```javascript
// Bun ↔ Node IPC: set serialization: "json"
const node = Bun.spawn({
    cmd: ["node", new URL("./node-child.cjs", import.meta.url).href],
    serialization: "json",
    ipc(message) {
        console.log("node says:", message);
        node.send({ from: "bun" });
        node.kill();
    },
    stdio: ["inherit", "inherit", "inherit"],
});
node.send({ hello: "node" });
```

Notes

- `stdout`/`stderr` can be: "pipe" (default for stdout), "inherit" (default for stderr), "ignore", a Bun.file, or a file descriptor.
- `stdin` accepts: null (default), "pipe" (for incremental writes), ReadableStream, Response/Request, Bun.file, TypedArray/DataView, Blob, or a file descriptor.
- Prefer `Bun.spawn` in servers/apps; use `Bun.spawnSync` in small CLI tools.

## FFI

Call native C libraries directly. Handy for system calls or tapping into existing C APIs.

```javascript
// ffi-demo.js
import { dlopen, FFIType, ptr } from 'bun:ffi';

const libcPath = process.platform === 'darwin'
    ? '/usr/lib/libSystem.B.dylib'
    : 'libc.so.6'; // Linux

const lib = dlopen(libcPath, {
    puts: { args: [FFIType.cstring], returns: FFIType.int },
});

lib.symbols.puts(ptr('Hello from bun:ffi'));
```

Notes:

- Paths differ by OS; `libc.so.6` (Linux) vs `libSystem.B.dylib` (macOS).
- Be careful with memory ownership; prefer passing strings and numbers unless you know how to free buffers.

## ESM and CJS Interop

Bun runs ESM by default but supports CommonJS seamlessly.

```javascript
// esm.mjs
import { readFileSync } from 'node:fs';
console.log('ESM ok', typeof readFileSync);
```

```javascript
// cjs.cjs
const os = require('node:os');
console.log('CJS ok', os.platform());
```

In `package.json`:

```json
{
    "type": "module" // use ESM by default; .cjs stays CJS
}
```

Importing CJS from ESM works for most packages; if you hit issues, check if the package ships dual builds or use dynamic `await import()`.

## URL Imports and Transpilation

Bun can import remote modules and transpile TS/JSX on the fly.

```javascript
// url-imports.js
import _ from 'https://cdn.jsdelivr.net/npm/lodash-es@4.17.21/lodash.min.js';
console.log('chunk size:', _.chunk([1,2,3,4], 2).length);
```

Transpile TypeScript without a build step:

```typescript
// ts-demo.ts
type User = { id: number; name: string };
const u: User = { id: 1, name: 'TS' };
console.log(u);
```

Run directly:

```bash
bun ts-demo.ts
```

Advanced bundling/transpilation flags:

```bash
bun build src/index.ts \
    --outdir=dist \
    --target=browser \
    --sourcemap \
    --minify \
    --splitting \
    --define:process.env.NODE_ENV='"production"' \
    --external:react --external:react-dom
```

## Shebang CLI Scripts

Write fast CLIs that start instantly.

```javascript
#!/usr/bin/env bun
// bin/hello
import { argv, env } from 'node:process';

const nameIdx = argv.indexOf('--name');
const name = nameIdx !== -1 ? argv[nameIdx + 1] : env.USER || 'friend';
console.log(`Hello, ${name}!`);
```

Make it executable and run:

```bash
chmod +x bin/hello
./bin/hello --name Alice
```

Optionally wire in package.json bin field:

```json
{
    "name": "my-cli",
    "version": "1.0.0",
    "bin": {
        "hello": "bin/hello"
    }
}
```

## Test Runner

Use Bun's built-in test runner. Create test files and run bun test.

### Write a tiny module and tests

```typescript
// src/math.ts
export function add(a: number, b: number) { return a + b; }
export function isEven(n: number) { return n % 2 === 0; }
```

```typescript
// tests/math.test.ts
import { describe, test, expect, beforeEach, mock } from 'bun:test';
import { add, isEven } from '../src/math';

describe('math utils', () => {
    let log: string[];
    const logger = { info: (msg: string) => log.push(msg) };

    beforeEach(() => { log = []; });

    test('add adds numbers', () => {
        expect(add(2, 3)).toBe(5);
    });

    test('isEven checks parity', () => {
        expect(isEven(4)).toBe(true);
        expect(isEven(5)).toBe(false);
    });

    test('mocks work', () => {
        const info = mock(logger, 'info');
        logger.info('hello');
        expect(info).toHaveBeenCalledTimes(1);
    });
});
```

Run tests:

```bash
bun test
bun test --watch
bun test tests/math.test.ts
bun test --filter even
```

## Bundler

Bun includes a fast bundler for browser or node targets.

### Bundle for the browser

```typescript
// src/main.ts
const el = document.createElement('div');
el.id = 'app';
el.textContent = 'Hello from a bundled Bun app!';
document.body.appendChild(el);
```

```bash
# Build to dist/ for the browser
bun build src/main.ts \
    --outdir=dist \
    --minify \
    --sourcemap \
    --target=browser
```

Create a minimal HTML file:

```html
<!-- dist/index.html -->
<!doctype html>
<html>
    <head><meta charset="utf-8"><title>Bun Bundle</title></head>
    <body>
        <script src="main.js"></script>
    </body>
    </html>
```

Serve the dist folder with Bun:

```javascript
// serve-dist.js
const server = Bun.serve({
    port: 8080,
    async fetch(req) {
        const url = new URL(req.url);
        const filePath = url.pathname === '/' ? '/index.html' : url.pathname;
        try {
            return new Response(Bun.file(`./dist${filePath}`));
        } catch {
            return new Response('Not found', { status: 404 });
        }
    }
});

console.log(`Static server on http://localhost:${server.port}`);
```

## Watch Mode

Re-run scripts automatically when files change using --watch.

```bash
bun --watch server.js
```

In package.json scripts:

```json
{
    "scripts": {
        "dev": "bun --watch server.js",
        "test:watch": "bun test --watch"
    }
}
```

## Debugging

Use the inspector and debugger statements while running Bun.

```bash
# Start with the inspector enabled
bun --inspect server.js
```

Then open the DevTools link printed in the terminal. You can also drop a debugger statement in code:

```javascript
// debug-demo.js
function compute(x) {
    debugger; // Execution will pause here when DevTools is attached
    return x * 2;
}

console.log(compute(21));
```

Tips:

- Prefer small, focused repros for debugging
- Use console.log, console.error, console.table, and console.trace
- Handle errors in Bun.serve's error callback to get clear stack traces

## TypeScript Support

Bun runs TypeScript out of the box. For editor IntelliSense of Bun APIs, add bun-types.

Install types and add tsconfig:

```bash
bun add -d bun-types typescript
```

```json
// tsconfig.json
{
    "compilerOptions": {
        "target": "ES2020",
        "module": "ESNext",
        "moduleResolution": "Bundler",
        "strict": true,
        "types": ["bun-types"]
    },
    "include": ["src", "tests"]
}
```

Example typed server:

```typescript
// src/typed-server.ts
import type { Server } from 'bun';

const server: Server = Bun.serve({
    port: 3004,
    fetch() {
        return new Response('typed');
    }
});

console.log('Typed server on', server.port);
```

---

## Workspaces and Monorepos

Manage multi-package repos similar to npm/pnpm workspaces.

```json
// package.json (root)
{
    "name": "acme-monorepo",
    "private": true,
    "workspaces": ["packages/*", "apps/*"],
    "scripts": {
        "build": "bun run --filter ./packages/* build"
    }
}
```

Each workspace has its own `package.json`. Install deps at the root with `bun install`. You can filter script runs with `bun run --filter` targeting paths or package names.

## Docker and Deployment

Use a small base image and copy only what you need.

```dockerfile
# Dockerfile
FROM oven/bun:1.1-alpine
WORKDIR /app
COPY package.json bun.lockb ./
RUN bun install --frozen-lockfile
COPY . .
EXPOSE 3000
CMD ["bun", "server.js"]
```

### Docker tips

- Use `--frozen-lockfile` for deterministic installs.
- For TypeScript, build inside the image and start with compiled files or run TS directly.
- Multi-stage builds can reduce image size further.

## Tips and Pitfalls

- Prefer ESM (`"type":"module"`) and use `.cjs` only when needed.
- Use `Bun.file`/`Bun.write` for fast file ops; they’re faster than Node’s fs for common cases.
- Use `--watch` for servers and `bun test --watch` for tests to speed feedback.
- Keep long-running work off the main request path—use Workers or queues.
- If a Node package fails, try the ESM entry, or update it; Bun tracks Node compat but some dynamic CJS loaders can break.
- For multipart forms in `Bun.serve`, prefer `await request.formData()`:

```javascript
// inside fetch handler
if (url.pathname === '/upload' && request.method === 'POST') {
    const form = await request.formData();
    const file = form.get('file'); // Blob
    if (file) await Bun.write(`./uploads/${file.name}`, file);
    return new Response('ok');
}
```

- Password hashing with Bun:

```javascript
// password-demo.js
const hash = await Bun.password.hash('secret');
const ok = await Bun.password.verify('secret', hash);
console.log({ ok });
```

## Bun CLI Cheat Sheet

- Runtime
    - Run a file: `bun file.ts`
    - Watch mode: `bun --watch server.js`
    - Inspector: `bun --inspect app.js`

- Packages
    - Add: `bun add react@latest`
    - Dev add: `bun add -d typescript bun-types`
    - Remove: `bun remove lodash`
    - Install from manifest: `bun install`

- Build
    - Bundle: `bun build src/index.ts --outdir dist --target browser`
    - Minify: add `--minify` and `--sourcemap`

- Test
    - Run: `bun test`
    - Filter: `bun test --filter math`
    - Watch: `bun test --watch`

## Summary

This Bun JavaScript guide provides:

✅ **Complete JavaScript fundamentals** - Variables, functions, objects, classes, and async programming  
✅ **Bun-specific features** - Fast runtime, built-in APIs, and optimized file operations  
✅ **Practical examples** - Real-world applications and common patterns  
✅ **Progressive learning** - From basics to advanced topics  
✅ **Ready-to-run code** - All examples work out of the box  

The guide follows the same comprehensive approach as your V programming language tutorial, covering both the language fundamentals and the runtime-specific features that make Bun unique in the JavaScript ecosystem.
