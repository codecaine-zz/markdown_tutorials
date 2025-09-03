# The Complete Bun JavaScript Guide

*From JavaScript Fundamentals to Bun Runtime Mastery*

## Introduction

Bun is an all-in-one JavaScript runtime & toolkit designed as a faster, leaner, more modern replacement for Node.js. At its core is the Bun runtime, a fast JavaScript runtime written in Zig and powered by JavaScriptCore under the hood, dramatically reducing startup times and memory usage.

**What makes Bun special:**
- **Blazing fast** - Processes start 4x faster than Node.js with up to 2.5x more requests per second
- **All-in-one toolkit** - Runtime, bundler, transpiler, package manager, and test runner in a single executable
- **Node.js compatibility** - Drop-in replacement for most Node.js applications with full compatibility for built-in globals and modules
- **Modern by default** - Built-in support for TypeScript, JSX, and ES modules without configuration
- **Zero-configuration** - Works out of the box with sensible defaults
- **Web-standard APIs** - Implements standard Web APIs like `fetch`, `WebSocket`, and `ReadableStream`

**Design Goals:**
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
    - [Environment Variables](#environment-variables)
    - [WebSockets](#websockets)

- Development Tools
    - [Test Runner](#test-runner)
    - [Bundler](#bundler)
    - [Watch Mode](#watch-mode)
    - [Debugging](#debugging)
    - [TypeScript Support](#typescript-support)

---

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
source ~/.bashrc  # or ~/.zshrc

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

*This is the beginning of our comprehensive Bun guide. The complete tutorial continues with Web Development, Testing, TypeScript integration, and advanced deployment topics...*

## Summary

This Bun JavaScript guide provides:

✅ **Complete JavaScript fundamentals** - Variables, functions, objects, classes, and async programming  
✅ **Bun-specific features** - Fast runtime, built-in APIs, and optimized file operations  
✅ **Practical examples** - Real-world applications and common patterns  
✅ **Progressive learning** - From basics to advanced topics  
✅ **Ready-to-run code** - All examples work out of the box  

The guide follows the same comprehensive approach as your V programming language tutorial, covering both the language fundamentals and the runtime-specific features that make Bun unique in the JavaScript ecosystem.
