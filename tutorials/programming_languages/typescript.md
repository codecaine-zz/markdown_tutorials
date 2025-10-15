# Complete TypeScript Tutorial (TypeScript 5.0+) - Extended Edition with Detailed Best Practices and Gotchas

## Table of Contents
1. [Introduction to TypeScript](#1-introduction-to-typescript)
2. [Setting Up TypeScript](#2-setting-up-typescript)
3. [Basic Syntax and Variables](#3-basic-syntax-and-variables)
4. [Data Types](#4-data-types)
5. [Operators](#5-operators)
6. [Control Flow](#6-control-flow)
7. [Functions](#7-functions)
8. [Data Structures](#8-data-structures)
9. [Object-Oriented Programming](#9-object-oriented-programming)
10. [Modules and Namespaces](#10-modules-and-namespaces)
11. [File Handling](#11-file-handling)
12. [Error Handling](#12-error-handling)
13. [Advanced Features](#13-advanced-features)
14. [JSON Handling](#14-json-handling)
15. [Best Practices and Gotchas](#15-best-practices-and-gotchas)

---

## 1. Introduction to TypeScript

TypeScript is a statically typed superset of JavaScript that compiles to plain JavaScript. It adds optional types, classes, interfaces, and other features to help catch errors at compile time.

```typescript
// Your first TypeScript program
console.log("Hello, TypeScript!");

// TypeScript with type annotations
const name: string = "Alice";      // String
const age: number = 25;           // Number
const height: number = 5.8;       // Number (no separate float type)
const isStudent: boolean = true;  // Boolean

console.log(`My name is ${name}, I'm ${age} years old`);
```

### Best Practices for TypeScript Introduction

**1. Use meaningful variable names and type annotations:**
```typescript
// Good
const userName: string = "Alice";
const totalPrice: number = 19.99;

// Avoid (though TypeScript will infer these types)
const x = "Alice";  // What is x?
const tp = 19.99;   // What is tp?

// Even better - let TypeScript infer when obvious
const userName2 = "Alice";  // TypeScript infers string
const totalPrice2 = 19.99;  // TypeScript infers number
```

**2. Follow naming conventions:**
- Use camelCase for variables and functions
- Use PascalCase for classes and interfaces
- Use UPPER_SNAKE_CASE for constants
- Use 2 spaces for indentation

**3. Write documentation comments:**
```typescript
/**
 * Authenticates a user with username and password.
 * 
 * @param username - The user's username
 * @param password - The user's password
 * @returns True if authentication successful, false otherwise
 */
function loginUser(username: string, password: string): boolean {
  // Implementation here
  return true;
}
```

### Common Gotchas for Beginners

**1. TypeScript is case-sensitive:**
```typescript
// These are different variables!
const Name: string = "Alice";
const name: string = "Bob";
const NAME: string = "Charlie";

console.log(Name);  // Alice
console.log(name);  // Bob
console.log(NAME);  // Charlie
```

**2. Type checking happens at compile time:**
```typescript
// This will cause a compile-time error:
const age: number = "25";  // Type 'string' is not assignable to type 'number'

// Correct way:
const age2: number = 25;
const age3: string = "25";
```

**3. Variables must be declared before use:**
```typescript
// This will cause a compile-time error:
// console.log(undeclaredVariable);  // Cannot find name 'undeclaredVariable'

// Correct way:
const declaredVariable: string = "Hello";
console.log(declaredVariable);
```

## 2. Setting Up TypeScript

### Best Practices for Environment Setup

**1. Initialize a TypeScript project:**
```bash
# Create package.json
npm init -y

# Install TypeScript locally
npm install -D typescript @types/node

# Create tsconfig.json
npx tsc --init
```

**2. Configure tsconfig.json properly:**
```json
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "commonjs",
    "lib": ["ES2020"],
    "outDir": "./dist",
    "rootDir": "./src",
    "strict": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true
  },
  "include": ["src/**/*"],
  "exclude": ["node_modules", "dist"]
}
```

**3. Use a build script in package.json:**
```json
{
  "scripts": {
    "build": "tsc",
    "start": "node dist/index.js",
    "dev": "ts-node src/index.ts",
    "watch": "tsc -w"
  }
}
```

### Gotchas with Environment Setup

**1. TypeScript vs JavaScript execution:**
```typescript
// You can't run TypeScript directly in Node.js
// node myfile.ts  // This won't work!

// You need to compile first:
// tsc myfile.ts
// node myfile.js

// Or use ts-node for development:
// npx ts-node myfile.ts
```

**2. Type definition files:**
```bash
# For Node.js APIs
npm install -D @types/node

# For Express
npm install -D @types/express

# For React
npm install -D @types/react @types/react-dom
```

**3. Module resolution issues:**
```json
// In tsconfig.json, make sure you have:
{
  "compilerOptions": {
    "moduleResolution": "node",
    "esModuleInterop": true
  }
}
```

## 3. Basic Syntax and Variables

### Best Practices for Variables and Syntax

**1. Use const and let instead of var:**
```typescript
// Good
const userName = "Alice";  // For values that won't change
let counter = 0;          // For values that will change

// Avoid
var oldStyle = "Don't use var";  // Function-scoped, can cause issues
```

**2. Use descriptive variable names:**
```typescript
// Good
const studentGrades: number[] = [85, 92, 78, 96];
const totalStudents: number = studentGrades.length;
const averageGrade: number = studentGrades.reduce((a, b) => a + b, 0) / totalStudents;

// Avoid
const sg: number[] = [85, 92, 78, 96];  // Unclear what sg means
const ts: number = sg.length;           // Unclear what ts means
const ag: number = sg.reduce((a, b) => a + b, 0) / ts;  // Unclear what ag means
```

**3. Use type annotations for complex types:**
```typescript
// Good - explicit typing for complex objects
interface User {
  name: string;
  age: number;
  email: string;
}

const user: User = {
  name: "Alice",
  age: 25,
  email: "alice@example.com"
};

// For simple types, let TypeScript infer
const userName = "Alice";  // TypeScript infers string
const userAge = 25;        // TypeScript infers number
```

### Detailed Gotchas with Variables

**1. Variable scope with let and const:**
```typescript
// Block-scoped variables
if (true) {
  const blockScoped = "I'm only accessible here";
  let anotherBlockScoped = "Me too";
  
  // This works:
  console.log(blockScoped);
}

// This will cause a compile-time error:
// console.log(blockScoped);  // Cannot find name 'blockScoped'
```

**2. const with objects and arrays:**
```typescript
// const prevents reassignment, not mutation
const config = {
  apiUrl: "https://api.example.com",
  timeout: 5000
};

// This is allowed (mutating properties):
config.timeout = 10000;

// This is not allowed (reassigning the variable):
// config = { apiUrl: "new", timeout: 5000 };  // Cannot assign to 'config' because it is a constant

// For truly immutable objects, use Object.freeze or readonly properties
const frozenConfig = Object.freeze({
  apiUrl: "https://api.example.com",
  timeout: 5000
});

// frozenConfig.timeout = 10000;  // This will throw in strict mode
```

**3. Hoisting with let and const:**
```typescript
// This works with var (but is confusing):
console.log(hoistedVar);  // undefined
var hoistedVar = "Hello";

// This causes a compile-time error with let/const:
// console.log(hoistedLet);  // Cannot access 'hoistedLet' before initialization
let hoistedLet = "Hello";
```

## 4. Data Types

### Best Practices for Data Types

**1. Use appropriate data types for the job:**
```typescript
// For unique items, use Set (fast membership testing)
const allowedUsers = new Set<string>(["alice", "bob", "charlie"]);
if (allowedUsers.has("alice")) {  // O(1) operation
  console.log("Access granted");
}

// For key-value pairs, use Map or plain objects
const userData = new Map<string, string>();
userData.set("name", "Alice");
userData.set("email", "alice@example.com");

// Or with plain objects:
interface User {
  name: string;
  age: number;
  email: string;
}

const user: User = {
  name: "Alice",
  age: 25,
  email: "alice@example.com"
};

// For ordered data that might change size, use arrays
const shoppingCart: string[] = ["apple", "banana", "orange"];
shoppingCart.push("grape");
```

**2. Use union types for flexible typing:**
```typescript
// Union types allow a value to be one of several types
type Status = "pending" | "approved" | "rejected";

const orderStatus: Status = "pending";

// This will cause a compile-time error:
// const invalidStatus: Status = "invalid";  // Type '"invalid"' is not assignable to type 'Status'
```

**3. Use enums for related constants:**
```typescript
// Numeric enums
enum Direction {
  Up = 1,
  Down,
  Left,
  Right
}

// String enums
enum HttpStatus {
  Ok = "OK",
  NotFound = "NOT_FOUND",
  InternalServerError = "INTERNAL_SERVER_ERROR"
}

// Const enums (more efficient)
const enum Color {
  Red = "RED",
  Green = "GREEN",
  Blue = "BLUE"
}
```

### Detailed Gotchas with Data Types

**1. any type - avoid when possible:**
```typescript
// Avoid using 'any' - it defeats the purpose of TypeScript
let value: any = "Hello";
value = 42;        // No error
value = true;      // No error
value.someMethod(); // No compile-time error, but might fail at runtime

// Better alternatives:
// 1. Union types
let flexibleValue: string | number = "Hello";
flexibleValue = 42;  // OK
// flexibleValue = true;  // Error: Type 'boolean' is not assignable to type 'string | number'

// 2. unknown (safer than any)
let unknownValue: unknown = "Hello";
// unknownValue.someMethod();  // Error: Object is of type 'unknown'

if (typeof unknownValue === "string") {
  console.log(unknownValue.toUpperCase());  // OK - TypeScript knows it's a string
}
```

**2. null and undefined:**
```typescript
// With strictNullChecks enabled (recommended):
let maybeString: string | null = null;
maybeString = "Hello";  // OK
// maybeString = undefined;  // Error: Type 'undefined' is not assignable to type 'string | null'

// Without strictNullChecks:
let definitelyString: string = "Hello";
// definitelyString = null;  // Error in strict mode, OK without strict mode

// Optional properties:
interface User {
  name: string;
  email?: string;  // Optional property (equivalent to string | undefined)
}

const user: User = { name: "Alice" };  // email is optional
```

**3. Type assertions (be careful):**
```typescript
// Type assertions tell TypeScript to treat a value as a specific type
const someValue: any = "Hello";
const strLength: number = (someValue as string).length;

// Alternative syntax:
const strLength2: number = (<string>someValue).length;

// Be careful - TypeScript won't check if the assertion is correct:
const numValue: any = 42;
// const invalidLength: number = (numValue as string).length;  // No compile error, but will fail at runtime

// Safer approach with type guards:
if (typeof someValue === "string") {
  const safeLength: number = someValue.length;  // TypeScript knows someValue is a string
}
```

**4. Array vs tuple types:**
```typescript
// Arrays - variable length, same type
const numbers: number[] = [1, 2, 3, 4, 5];
const strings: string[] = ["a", "b", "c"];

// Tuples - fixed length, specific types at each position
const person: [string, number] = ["Alice", 25];
const name: string = person[0];  // string
const age: number = person[1];   // number

// This will cause a compile-time error:
// const invalidPerson: [string, number] = ["Alice", 25, "extra"];  // Type '[string, number, string]' is not assignable to type '[string, number]'
```

## 5. Operators

### Best Practices for Operators

**1. Use strict equality (=== and !==):**
```typescript
// Good - strict equality
if (value === null) {
  // Handle null case
}

if (value !== undefined) {
  // Handle defined case
}

// Avoid - loose equality (TypeScript will warn about this)
// if (value == null) { ... }  // This checks for both null and undefined
```

**2. Use logical operators for default values:**
```typescript
// Good
const userName = user?.name || "Anonymous";
const timeout = config?.timeout ?? 30000;  // Nullish coalescing

// Avoid
// const userName = user.name ? user.name : "Anonymous";  // More verbose
```

**3. Use spread operator for object/array copying:**
```typescript
// Good - immutable operations
const originalArray = [1, 2, 3];
const newArray = [...originalArray, 4, 5];

const originalObject = { name: "Alice", age: 25 };
const newObject = { ...originalObject, email: "alice@example.com" };
```

### Detailed Gotchas with Operators

**1. Type coercion with loose equality:**
```typescript
// These can be confusing:
console.log(0 == false);    // true
console.log("" == false);   // true
console.log([] == false);   // true
console.log("0" == false);  // true

// Strict equality is more predictable:
console.log(0 === false);    // false
console.log("" === false);   // false
console.log([] === false);   // false
console.log("0" === false);  // false
```

**2. Short-circuit evaluation:**
```typescript
// This is a feature, but can be a gotcha:
function expensiveOperation(): boolean {
  console.log("Expensive operation called!");
  return true;
}

// The expensive operation won't be called:
const result = false && expensiveOperation();  // Prints nothing
console.log(result);  // false

// The expensive operation will be called:
const result2 = true && expensiveOperation();   // Prints "Expensive operation called!"
console.log(result2);  // true

// Use this pattern for safe operations:
const myArray: number[] = [1, 2, 3];
// Safe way to check if array is not empty and first element exists
if (myArray.length > 0 && myArray[0] > 0) {
  console.log("First element is positive");
}
```

**3. Nullish coalescing vs logical OR:**
```typescript
// Nullish coalescing (??) only considers null and undefined as "nullish"
const value1 = 0 ?? 42;        // 0 (0 is not nullish)
const value2 = null ?? 42;     // 42
const value3 = undefined ?? 42; // 42

// Logical OR (||) considers all falsy values
const value4 = 0 || 42;        // 42 (0 is falsy)
const value5 = null || 42;     // 42
const value6 = undefined || 42; // 42
```

**4. Optional chaining:**
```typescript
// Safe property access:
interface User {
  name: string;
  address?: {
    street: string;
    city: string;
  };
}

const user: User = { name: "Alice" };  // address is optional

// Without optional chaining:
// const city = user.address.city;  // Error: Object is possibly 'undefined'

// With optional chaining:
const city = user.address?.city;  // undefined (no error)

// Optional chaining with method calls:
const result = user.address?.toString?.();  // undefined if address or toString doesn't exist

// Optional chaining with array access:
const firstItem = user.items?.[0];  // undefined if items doesn't exist or is empty
```

## 6. Control Flow

### Best Practices for Control Flow

**1. Use early returns to reduce nesting:**
```typescript
// Good - early returns
function processUserData(data: User | null): string {
  if (!data) {
    return "No data provided";
  }
  
  if (data.age < 18) {
    return "User must be adult";
  }
  
  // Main processing logic here
  return "Data processed successfully";
}

// Avoid - deep nesting
function processUserDataBad(data: User | null): string {
  if (data) {
    if (data.age >= 18) {
      // Main processing logic here
      return "Data processed successfully";
    } else {
      return "User must be adult";
    }
  } else {
    return "No data provided";
  }
}
```

**2. Use guard clauses for error conditions:**
```typescript
function divideNumbers(a: number, b: number): number {
  // Guard clauses
  if (typeof a !== "number") {
    throw new TypeError("First argument must be a number");
  }
  
  if (typeof b !== "number") {
    throw new TypeError("Second argument must be a number");
  }
  
  if (b === 0) {
    throw new Error("Cannot divide by zero");
  }
  
  return a / b;
}
```

**3. Use for...of instead of traditional for loops:**
```typescript
// Good
const fruits = ["apple", "banana", "cherry"];
for (const fruit of fruits) {
  console.log(fruit);
}

// Good for index access:
fruits.forEach((fruit, index) => {
  console.log(`${index}: ${fruit}`);
});

// Avoid traditional for loops when possible:
// for (let i = 0; i < fruits.length; i++) {
//   console.log(`${i}: ${fruits[i]}`);
// }
```

### Detailed Gotchas with Control Flow

**1. for...in vs for...of:**
```typescript
const array = ["a", "b", "c"];

// for...in iterates over indices/keys:
for (const key in array) {
  console.log(key);  // "0", "1", "2" (strings!)
  console.log(typeof key);  // string
}

// for...of iterates over values:
for (const value of array) {
  console.log(value);  // "a", "b", "c"
}

// For objects:
const obj = { a: 1, b: 2, c: 3 };

// for...in for object keys:
for (const key in obj) {
  if (obj.hasOwnProperty(key)) {
    console.log(key, obj[key]);  // "a" 1, "b" 2, "c" 3
  }
}

// Object.keys/values/entries for better type safety:
Object.keys(obj).forEach(key => {
  console.log(key, obj[key]);
});
```

**2. Switch statements with exhaustiveness checking:**
```typescript
type Status = "pending" | "approved" | "rejected";

function handleStatus(status: Status): string {
  switch (status) {
    case "pending":
      return "Processing...";
    case "approved":
      return "Approved!";
    case "rejected":
      return "Rejected!";
    // TypeScript will warn if we forget a case when strict mode is enabled
  }
}

// For exhaustive checking, add a default that never should be reached:
function handleStatusExhaustive(status: Status): string {
  switch (status) {
    case "pending":
      return "Processing...";
    case "approved":
      return "Approved!";
    case "rejected":
      return "Rejected!";
    default:
      // This will cause a compile error if we add a new status and forget to handle it
      const _exhaustiveCheck: never = status;
      return _exhaustiveCheck;
  }
}
```

**3. Async/await with control flow:**
```typescript
// Good - proper error handling
async function processData(): Promise<string> {
  try {
    const data = await fetchData();
    if (!data) {
      return "No data";
    }
    
    const processed = await process(data);
    return `Processed: ${processed}`;
  } catch (error) {
    console.error("Processing failed:", error);
    return "Processing failed";
  }
}

// Avoid - mixing async/await with .then()
// async function badExample() {
//   const data = await fetchData()
//     .then(result => {
//       if (!result) throw new Error("No data");
//       return result;
//     })
//     .catch(error => {
//       console.error(error);
//       return null;
//     });
// }
```

## 7. Functions

### Best Practices for Functions

**1. Use explicit return types:**
```typescript
// Good - explicit return type
function calculateArea(length: number, width: number): number {
  return length * width;
}

// Good for void functions
function logMessage(message: string): void {
  console.log(message);
}

// Avoid - implicit return type (TypeScript can infer, but explicit is clearer)
function calculateAreaImplicit(length: number, width: number) {
  return length * width;  // TypeScript infers number
}
```

**2. Use arrow functions for simple operations:**
```typescript
// Good - arrow function for simple operations
const square = (x: number): number => x * x;

// Good - with explicit typing
const add: (a: number, b: number) => number = (a, b) => a + b;

// For complex functions, regular function syntax is often clearer
function complexCalculation(input: number[]): number {
  // Complex logic here
  return input.reduce((sum, current) => sum + current, 0);
}
```

**3. Use rest parameters and spread operator:**
```typescript
// Rest parameters
function sum(...numbers: number[]): number {
  return numbers.reduce((total, num) => total + num, 0);
}

// Usage:
console.log(sum(1, 2, 3, 4, 5));  // 15

// Spread operator
const arr1 = [1, 2, 3];
const arr2 = [4, 5, 6];
const combined = [...arr1, ...arr2];  // [1, 2, 3, 4, 5, 6]
```

**4. Use function overloads for different signatures:**
```typescript
// Function overloads
function format(value: string): string;
function format(value: number): string;
function format(value: boolean): string;
function format(value: string | number | boolean): string {
  return String(value);
}

// Usage:
const strResult = format("hello");  // string
const numResult = format(42);       // string
const boolResult = format(true);    // string
```

### Detailed Gotchas with Functions

**1. this binding in methods:**
```typescript
class Calculator {
  private value: number = 0;
  
  add(num: number): this {  // Method chaining
    this.value += num;
    return this;
  }
  
  getValue(): number {
    return this.value;
  }
}

const calc = new Calculator();
const result = calc.add(5).add(3).getValue();  // 8

// Arrow functions preserve 'this' context:
class EventHandler {
  private message: string = "Hello";
  
  // Regular method - 'this' depends on how it's called
  handleClick(this: EventHandler) {  // Explicit 'this' parameter
    console.log(this.message);
  }
  
  // Arrow function - 'this' is lexically bound
  handleArrowClick = () => {
    console.log(this.message);
  }
}
```

**2. Optional and default parameters:**
```typescript
// Optional parameters (must come after required parameters)
function greet(name: string, greeting?: string): string {
  return `${greeting || "Hello"}, ${name}!`;
}

// Default parameters
function greetWithDefault(name: string, greeting: string = "Hello"): string {
  return `${greeting}, ${name}!`;
}

// Usage:
console.log(greet("Alice"));           // "Hello, Alice!"
console.log(greet("Alice", "Hi"));     // "Hi, Alice!"
console.log(greetWithDefault("Bob"));  // "Hello, Bob!"
```

**3. Generic functions:**
```typescript
// Generic function
function identity<T>(arg: T): T {
  return arg;
}

// Usage:
const stringResult = identity<string>("hello");  // Type is string
const numberResult = identity<number>(42);       // Type is number
const autoResult = identity("hello");            // TypeScript infers string

// Generic with constraints
function logLength<T extends { length: number }>(arg: T): T {
  console.log(arg.length);
  return arg;
}

// Usage:
logLength("hello");     // Works - string has length
logLength([1, 2, 3]);   // Works - array has length
// logLength(42);       // Error - number doesn't have length
```

**4. Function types and callbacks:**
```typescript
// Function type aliases
type MathOperation = (a: number, b: number) => number;

const add: MathOperation = (a, b) => a + b;
const multiply: MathOperation = (a, b) => a * b;

// Callbacks with proper typing
function calculate(
  a: number, 
  b: number, 
  operation: MathOperation
): number {
  return operation(a, b);
}

// Usage:
const result = calculate(5, 3, add);       // 8
const result2 = calculate(5, 3, multiply); // 15
```

## 8. Data Structures

### Best Practices for Data Structures

**1. Choose the right data structure for the job:**
```typescript
// For unique items with fast membership testing: use Set
const allowedUsers = new Set<string>(["alice", "bob", "charlie"]);
if (allowedUsers.has("alice")) {  // O(1) operation
  console.log("Access granted");
}

// For key-value pairs: use Map or plain objects
const userData = new Map<string, string>();
userData.set("name", "Alice");
userData.set("email", "alice@example.com");

// Or with plain objects and interfaces:
interface User {
  name: string;
  age: number;
  email: string;
}

const user: User = {
  name: "Alice",
  age: 25,
  email: "alice@example.com"
};

// For ordered data that might change size: use arrays
const shoppingCart: string[] = ["apple", "banana", "orange"];
shoppingCart.push("grape");

// For fixed-size, immutable sequences: use readonly arrays or tuples
const coordinates: readonly [number, number] = [10, 20] as const;
const rgbColor: readonly [number, number, number] = [255, 128, 0] as const;
```

**2. Use array methods for transformations:**
```typescript
// Good - functional approach
const numbers = [1, 2, 3, 4, 5];
const squares = numbers.map(x => x * x);
const evenNumbers = numbers.filter(x => x % 2 === 0);
const sum = numbers.reduce((acc, curr) => acc + curr, 0);

// Avoid - imperative loops when functional methods are clearer
// const squares = [];
// for (let i = 0; i < numbers.length; i++) {
//   squares.push(numbers[i] * numbers[i]);
// }
```

**3. Use Map and Set for better performance with large datasets:**
```typescript
// Map for key-value pairs with better performance than objects for frequent additions/deletions
const cache = new Map<string, any>();

// Set for unique values with better performance than arrays for membership testing
const uniqueItems = new Set<number>();

// WeakMap and WeakSet for memory management
const privateData = new WeakMap<object, any>();
```

### Detailed Gotchas with Data Structures

**1. Array mutability and references:**
```typescript
// Shallow copy issues:
const original = [[1, 2], [3, 4]];
const copy1 = original;           // Reference to same object
const copy2 = [...original];      // Shallow copy
const copy3 = original.slice();   // Shallow copy

// Modifying nested elements affects shallow copies:
original[0][0] = 999;
console.log(copy2[0][0]);  // 999 - affected by shallow copy!
console.log(copy3[0][0]);  // 999 - affected by shallow copy!

// Deep copy for nested structures:
const deepCopy = JSON.parse(JSON.stringify(original));  // Simple deep copy (has limitations)
original[0][0] = 888;
console.log(deepCopy[0][0]);  // Still 999 - not affected

// For better deep copy, consider libraries like lodash:
// import { cloneDeep } from 'lodash';
// const deepCopy = cloneDeep(original);
```

**2. Map vs Object for key-value pairs:**
```typescript
// Objects have limitations as maps:
const obj = {};
// obj[{}] = "value";  // All object keys become "[object Object]"
// obj[1] = "number key";  // Keys are converted to strings
// obj["1"] = "string key";  // Overwrites the number key!

// Map preserves key types:
const map = new Map();
map.set({}, "value");      // Object key works
map.set(1, "number key");  // Number key preserved
map.set("1", "string key"); // String key preserved (different from number key)

console.log(map.size);  // 3
console.log(obj);       // Only one property: "1": "string key"
```

**3. Set operations:**
```typescript
// Set operations (need to implement manually):
const set1 = new Set([1, 2, 3, 4, 5]);
const set2 = new Set([4, 5, 6, 7, 8]);

// Union
const union = new Set([...set1, ...set2]);

// Intersection
const intersection = new Set([...set1].filter(x => set2.has(x)));

// Difference
const difference = new Set([...set1].filter(x => !set2.has(x)));

// Set membership testing is very fast:
const allowedUsers = new Set(["alice", "bob", "charlie"]);  // O(1) lookup
if (allowedUsers.has("alice")) {  // Very fast even for large sets
  console.log("Access granted");
}
```

**4. Readonly arrays and tuples:**
```typescript
// Readonly arrays prevent mutation:
const readOnlyArray: readonly number[] = [1, 2, 3, 4, 5];
// readOnlyArray.push(6);  // Error: Property 'push' does not exist on type 'readonly number[]'

// Readonly tuples:
const point: readonly [number, number] = [10, 20];
// point[0] = 15;  // Error: Cannot assign to '0' because it is a read-only property

// Creating from mutable arrays:
const mutableArray = [1, 2, 3];
const readOnlyView: readonly number[] = mutableArray as const;
// readOnlyView.push(4);  // Error

// But the original array can still be mutated:
mutableArray.push(4);  // This works
```

## 9. Object-Oriented Programming

### Best Practices for OOP

**1. Use interfaces for type contracts:**
```typescript
// Interfaces define the shape of objects
interface User {
  name: string;
  age: number;
  email: string;
  greet(): string;
}

// Classes can implement interfaces
class Person implements User {
  constructor(
    public name: string,
    public age: number,
    public email: string
  ) {}
  
  greet(): string {
    return `Hello, I'm ${this.name}`;
  }
}

// Interfaces can extend other interfaces
interface Employee extends User {
  employeeId: number;
  department: string;
}

class CompanyEmployee implements Employee {
  constructor(
    public name: string,
    public age: number,
    public email: string,
    public employeeId: number,
    public department: string
  ) {}
  
  greet(): string {
    return `Hello, I'm ${this.name} from ${this.department}`;
  }
}
```

**2. Use access modifiers appropriately:**
```typescript
class BankAccount {
  private balance: number = 0;        // Only accessible within class
  protected accountType: string;      // Accessible within class and subclasses
  public accountNumber: string;       // Accessible everywhere (default)
  
  constructor(accountNumber: string, accountType: string = "checking") {
    this.accountNumber = accountNumber;
    this.accountType = accountType;
  }
  
  public deposit(amount: number): void {
    if (amount > 0) {
      this.balance += amount;
    }
  }
  
  public getBalance(): number {
    return this.balance;
  }
  
  protected getAccountInfo(): string {
    return `${this.accountNumber} (${this.accountType})`;
  }
}
```

**3. Use abstract classes for partial implementations:**
```typescript
abstract class Shape {
  protected color: string;
  
  constructor(color: string) {
    this.color = color;
  }
  
  // Abstract method must be implemented by subclasses
  abstract calculateArea(): number;
  
  // Concrete method can be inherited
  public getColor(): string {
    return this.color;
  }
  
  // Abstract method with implementation
  abstract toString(): string;
}

class Rectangle extends Shape {
  constructor(
    color: string,
    private width: number,
    private height: number
  ) {
    super(color);
  }
  
  calculateArea(): number {
    return this.width * this.height;
  }
  
  toString(): string {
    return `Rectangle ${this.width}x${this.height} (${this.color})`;
  }
}
```

**4. Use generics in classes:**
```typescript
class Container<T> {
  private items: T[] = [];
  
  add(item: T): void {
    this.items.push(item);
  }
  
  get(index: number): T {
    return this.items[index];
  }
  
  getAll(): T[] {
    return [...this.items];  // Return copy to prevent external mutation
  }
  
  size(): number {
    return this.items.length;
  }
}

// Usage:
const stringContainer = new Container<string>();
stringContainer.add("hello");
stringContainer.add("world");

const numberContainer = new Container<number>();
numberContainer.add(1);
numberContainer.add(2);
```

### Detailed Gotchas with OOP

**1. this context in methods:**
```typescript
class Counter {
  private count: number = 0;
  
  increment(): void {
    this.count++;
  }
  
  getCount(): number {
    return this.count;
  }
  
  // Method that returns a function - 'this' can be lost
  getIncrementer(): () => void {
    return this.increment;  // 'this' will be undefined when called
  }
  
  // Fix with arrow function:
  getIncrementerFixed = (): (() => void) => {
    return () => this.increment();  // 'this' is preserved
  }
  
  // Or with bind:
  getIncrementerBound(): () => void {
    return this.increment.bind(this);  // 'this' is bound
  }
}

const counter = new Counter();
const incrementer = counter.getIncrementer();
// incrementer();  // TypeError: Cannot read property 'count' of undefined

const incrementerFixed = counter.getIncrementerFixed();
incrementerFixed();  // Works
```

**2. Static vs instance members:**
```typescript
class MathUtils {
  static PI: number = 3.14159;
  
  static circleArea(radius: number): number {
    return MathUtils.PI * radius * radius;
  }
  
  // Instance method
  square(x: number): number {
    return x * x;
  }
}

// Static members are accessed on the class:
console.log(MathUtils.PI);  // 3.14159
console.log(MathUtils.circleArea(5));  // 78.53975

// Instance members are accessed on instances:
const utils = new MathUtils();
console.log(utils.square(5));  // 25

// Static methods don't have access to instance properties:
class BadExample {
  private instanceValue: number = 42;
  
  static badMethod(): number {
    // return this.instanceValue;  // Error: 'this' cannot be referenced in a static property
    return 0;
  }
}
```

**3. Inheritance and method overriding:**
```typescript
class Animal {
  constructor(protected name: string) {}
  
  speak(): void {
    console.log(`${this.name} makes a sound`);
  }
  
  move(): void {
    console.log(`${this.name} moves`);
  }
}

class Dog extends Animal {
  constructor(name: string) {
    super(name);  // Must call super constructor
  }
  
  // Override method
  speak(): void {
    console.log(`${this.name} barks`);
  }
  
  // Add new method
  wagTail(): void {
    console.log(`${this.name} wags tail`);
  }
}

// Polymorphism:
const animals: Animal[] = [
  new Animal("Generic"),
  new Dog("Buddy")
];

animals.forEach(animal => animal.speak());
// "Generic makes a sound"
// "Buddy barks"
```

**4. Private vs #private (ES2022):**
```typescript
class Example {
  private privateField: string = "TypeScript private";
  # trulyPrivateField: string = "ECMAScript private";
  
  getPrivateField(): string {
    return this.privateField;
  }
  
  getTrulyPrivateField(): string {
    return this.#trulyPrivateField;
  }
}

const example = new Example();
console.log(example.getPrivateField());      // "TypeScript private"
console.log(example.getTrulyPrivateField()); // "ECMAScript private"

// TypeScript private is compile-time only:
// console.log(example.privateField);  // Error: Property 'privateField' is private

// But it's accessible at runtime:
// console.log((example as any).privateField);  // "TypeScript private" - works!

// ECMAScript private is truly private:
// console.log(example.#trulyPrivateField);  // SyntaxError!
```

## 10. Modules and Namespaces

### Best Practices for Modules and Namespaces

**1. Use ES modules (import/export):**
```typescript
// mathUtils.ts
export const PI = 3.14159;

export function add(a: number, b: number): number {
  return a + b;
}

export function multiply(a: number, b: number): number {
  return a * b;
}

// Default export
export default function subtract(a: number, b: number): number {
  return a - b;
}

// Exporting types
export interface CalculatorConfig {
  precision: number;
  rounding: boolean;
}
```

```typescript
// main.ts
import subtract, { PI, add, multiply, type CalculatorConfig } from './mathUtils';

// Or import everything:
import * as MathUtils from './mathUtils';

// Or import with alias:
import { add as sum } from './mathUtils';

// Usage:
console.log(PI);  // 3.14159
console.log(add(2, 3));  // 5
console.log(subtract(5, 3));  // 2
```

**2. Use barrel exports for organized imports:**
```typescript
// utils/index.ts
export * from './mathUtils';
export * from './stringUtils';
export * from './dateUtils';

// Now you can import from the directory:
// import { add, formatDate } from './utils';
```

**3. Use namespaces for logical grouping (less common now):**
```typescript
// Old style with namespaces:
namespace Validation {
  export interface StringValidator {
    isAcceptable(s: string): boolean;
  }
  
  const lettersRegexp = /^[A-Za-z]+$/;
  
  export class LettersOnlyValidator implements StringValidator {
    isAcceptable(s: string): boolean {
      return lettersRegexp.test(s);
    }
  }
}

// Usage:
const validator = new Validation.LettersOnlyValidator();
```

**4. Use module augmentation for extending existing modules:**
```typescript
// globals.d.ts
declare global {
  interface Window {
    myCustomProperty: string;
  }
}

// Now you can use:
// window.myCustomProperty = "Hello";
```

### Detailed Gotchas with Modules and Namespaces

**1. Circular dependencies:**
```typescript
// file1.ts
import { function2 } from './file2';

export function function1(): string {
  return "Function 1";
}

// file2.ts
import { function1 } from './file1';  // Circular dependency!

export function function2(): string {
  return function1() + " and Function 2";
}

// Solutions:
// 1. Restructure to avoid circular dependencies
// 2. Use dynamic imports:
export async function function2Async(): Promise<string> {
  const { function1 } = await import('./file1');
  return function1() + " and Function 2";
}

// 3. Move shared code to a third file
```

**2. Default vs named exports:**
```typescript
// With default export:
// math.ts
const add = (a: number, b: number): number => a + b;
export default add;

// Import:
import add from './math';  // Can use any name

// With named export:
// math.ts
export const add = (a: number, b: number): number => a + b;

// Import:
import { add } from './math';  // Must use exact name
// or
import { add as sum } from './math';  // With alias
```

**3. Re-exporting:**
```typescript
// reexports.ts
export { add, multiply } from './mathUtils';
export { default as subtract } from './subtractUtils';
export * as validators from './validators';

// Now you can:
// import { add, subtract, validators } from './reexports';
```

**4. Module resolution:**
```json
// tsconfig.json
{
  "compilerOptions": {
    "baseUrl": "./src",
    "paths": {
      "@utils/*": ["utils/*"],
      "@components/*": ["components/*"]
    }
  }
}
```

```typescript
// Now you can import with aliases:
import { add } from '@utils/mathUtils';
import Button from '@components/Button';
```

## 11. File Handling

### Best Practices for File Handling

**1. Use Node.js fs module with promises:**
```typescript
import { promises as fs } from 'fs';
import { join } from 'path';

// Reading files
async function readFileContent(filePath: string): Promise<string> {
  try {
    const content = await fs.readFile(filePath, 'utf-8');
    return content;
  } catch (error) {
    throw new Error(`Failed to read file ${filePath}: ${error}`);
  }
}

// Writing files
async function writeFileContent(filePath: string, content: string): Promise<void> {
  try {
    await fs.writeFile(filePath, content, 'utf-8');
  } catch (error) {
    throw new Error(`Failed to write file ${filePath}: ${error}`);
  }
}

// Working with directories
async function ensureDirectory(dirPath: string): Promise<void> {
  try {
    await fs.mkdir(dirPath, { recursive: true });
  } catch (error) {
    throw new Error(`Failed to create directory ${dirPath}: ${error}`);
  }
}
```

**2. Use proper error handling:**
```typescript
import { promises as fs } from 'fs';

async function safeFileOperation(filePath: string): Promise<string | null> {
  try {
    const stats = await fs.stat(filePath);
    if (stats.isFile()) {
      return await fs.readFile(filePath, 'utf-8');
    } else {
      console.warn(`${filePath} is not a file`);
      return null;
    }
  } catch (error: any) {
    if (error.code === 'ENOENT') {
      console.warn(`File ${filePath} not found`);
      return null;
    } else {
      throw error;  // Re-throw unexpected errors
    }
  }
}
```

**3. Use streams for large files:**
```typescript
import { createReadStream, createWriteStream } from 'fs';
import { pipeline } from 'stream/promises';

async function copyFile(source: string, destination: string): Promise<void> {
  const readStream = createReadStream(source);
  const writeStream = createWriteStream(destination);
  
  try {
    await pipeline(readStream, writeStream);
    console.log(`File copied from ${source} to ${destination}`);
  } catch (error) {
    throw new Error(`Failed to copy file: ${error}`);
  }
}
```

### Detailed Gotchas with File Handling

**1. Asynchronous vs synchronous operations:**
```typescript
import { promises as fs, readFileSync, writeFileSync } from 'fs';

// Asynchronous (preferred)
async function asyncExample(): Promise<void> {
  try {
    const content = await fs.readFile('data.txt', 'utf-8');
    console.log(content);
  } catch (error) {
    console.error('Error reading file:', error);
  }
}

// Synchronous (blocks the event loop)
function syncExample(): void {
  try {
    const content = readFileSync('data.txt', 'utf-8');
    console.log(content);
  } catch (error) {
    console.error('Error reading file:', error);
  }
}

// Never mix async/await with sync operations in the same function
// unless you have a specific reason
```

**2. File paths and cross-platform compatibility:**
```typescript
import { join, resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

// Get current directory in ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Cross-platform path joining
const configPath = join(__dirname, 'config', 'app.json');
const outputPath = resolve('output', 'results.txt');

// Avoid hardcoded path separators:
// const badPath = 'data\\file.txt';  // Only works on Windows
const goodPath = join('data', 'file.txt');  // Works on all platforms
```

**3. File permissions and security:**
```typescript
import { promises as fs } from 'fs';

// Check file permissions before operations
async function secureFileRead(filePath: string): Promise<string> {
  try {
    // Check if file exists and is readable
    await fs.access(filePath, fs.constants.R_OK);
    
    // Read file
    const content = await fs.readFile(filePath, 'utf-8');
    return content;
  } catch (error: any) {
    if (error.code === 'EACCES') {
      throw new Error(`Permission denied: ${filePath}`);
    }
    throw error;
  }
}

// Validate file paths to prevent directory traversal
import { resolve, relative } from 'path';

function isPathSafe(basePath: string, requestedPath: string): boolean {
  const resolvedPath = resolve(basePath, requestedPath);
  const relativePath = relative(basePath, resolvedPath);
  
  // Check if relative path starts with '..' (directory traversal)
  return !relativePath.startsWith('..');
}
```

## 12. Error Handling

### Best Practices for Error Handling

**1. Create custom error classes:**
```typescript
class ApplicationError extends Error {
  constructor(message: string, public code?: string) {
    super(message);
    this.name = this.constructor.name;
  }
}

class ValidationError extends ApplicationError {
  constructor(message: string, public field?: string) {
    super(message, 'VALIDATION_ERROR');
  }
}

class NetworkError extends ApplicationError {
  constructor(message: string, public statusCode?: number) {
    super(message, 'NETWORK_ERROR');
  }
}

// Usage:
function validateUser(user: any): void {
  if (!user.email) {
    throw new ValidationError('Email is required', 'email');
  }
  
  if (!user.email.includes('@')) {
    throw new ValidationError('Invalid email format', 'email');
  }
}
```

**2. Use union types for error handling:**
```typescript
type Result<T> = {
  success: true;
  data: T;
} | {
  success: false;
  error: Error;
};

function safeParseJSON(json: string): Result<any> {
  try {
    const data = JSON.parse(json);
    return { success: true, data };
  } catch (error) {
    return { success: false, error: error as Error };
  }
}

// Usage:
const result = safeParseJSON('{"name": "Alice"}');
if (result.success) {
  console.log(result.data.name);  // Type-safe access
} else {
  console.error('Parse error:', result.error.message);
}
```

**3. Handle async errors properly:**
```typescript
async function fetchUserData(userId: string): Promise<User | null> {
  try {
    const response = await fetch(`/api/users/${userId}`);
    
    if (!response.ok) {
      throw new NetworkError(
        `Failed to fetch user: ${response.statusText}`, 
        response.status
      );
    }
    
    const userData = await response.json();
    return userData;
  } catch (error) {
    if (error instanceof NetworkError) {
      console.error('Network error:', error.message);
    } else {
      console.error('Unexpected error:', error);
    }
    return null;
  }
}
```

### Detailed Gotchas with Error Handling

**1. Error instanceof checks:**
```typescript
// Custom errors might not work as expected with instanceof after transpilation
class CustomError extends Error {
  constructor(message: string) {
    super(message);
    this.name = 'CustomError';
  }
}

// This might not work in some environments:
// try {
//   throw new CustomError('test');
// } catch (error) {
//   if (error instanceof CustomError) {  // Might be false
//     console.log('Custom error caught');
//   }
// }

// Better approach - use error codes or custom properties:
class BetterError extends Error {
  constructor(message: string, public code: string) {
    super(message);
    this.name = 'BetterError';
  }
}

try {
  throw new BetterError('test', 'CUSTOM_ERROR');
} catch (error: any) {
  if (error.code === 'CUSTOM_ERROR') {
    console.log('Custom error caught');
  }
}
```

**2. Promise rejection handling:**
```typescript
// Always handle promise rejections:
asyncFunction()
  .then(result => console.log(result))
  .catch(error => console.error(error));

// Or with async/await:
try {
  const result = await asyncFunction();
  console.log(result);
} catch (error) {
  console.error(error);
}

// Unhandled promise rejections:
// process.on('unhandledRejection', (reason, promise) => {
//   console.error('Unhandled Rejection at:', promise, 'reason:', reason);
//   // Application specific logging, throwing an error, or other logic here
// });
```

**3. Error boundary patterns:**
```typescript
// Function wrapper for error boundaries:
function withErrorHandling<T extends (...args: any[]) => any>(
  fn: T,
  errorHandler?: (error: Error) => void
): (...args: Parameters<T>) => ReturnType<T> | null {
  return function(...args: Parameters<T>): ReturnType<T> | null {
    try {
      return fn(...args);
    } catch (error) {
      if (errorHandler) {
        errorHandler(error as Error);
      } else {
        console.error('Error in function:', error);
      }
      return null;
    }
  };
}

// Usage:
const safeParse = withErrorHandling(JSON.parse);
const result = safeParse('invalid json');  // Returns null, logs error
```

## 13. Advanced Features

### Best Practices for Advanced Features

**1. Use decorators for cross-cutting concerns:**
```typescript
// Enable experimentalDecorators in tsconfig.json
function log(target: any, propertyKey: string, descriptor: PropertyDescriptor) {
  const originalMethod = descriptor.value;
  
  descriptor.value = function(...args: any[]) {
    console.log(`Calling ${propertyKey} with arguments:`, args);
    const result = originalMethod.apply(this, args);
    console.log(`Result:`, result);
    return result;
  };
  
  return descriptor;
}

class Calculator {
  @log
  add(a: number, b: number): number {
    return a + b;
  }
}
```

**2. Use mixins for multiple inheritance:**
```typescript
type Constructor<T = {}> = new (...args: any[]) => T;

function Timestamped<TBase extends Constructor>(Base: TBase) {
  return class extends Base {
    timestamp = Date.now();
  };
}

function Activatable<TBase extends Constructor>(Base: TBase) {
  return class extends Base {
    isActivated = false;
    
    activate() {
      this.isActivated = true;
    }
    
    deactivate() {
      this.isActivated = false;
    }
  };
}

class User {
  constructor(public name: string) {}
}

// Apply mixins:
const TimestampedUser = Timestamped(User);
const SmartUser = Activatable(TimestampedUser);

const user = new SmartUser("Alice");
user.activate();
console.log(user.isActivated);  // true
console.log(user.timestamp);    // timestamp
```

**3. Use conditional types for advanced typing:**
```typescript
// Conditional types
type NonNullable<T> = T extends null | undefined ? never : T;

type Message<T> = T extends string ? string : number;

// Usage:
type NonNullString = NonNullable<string | null>;  // string
type NonNullNumber = NonNullable<number | undefined>;  // number

type StringMessage = Message<string>;  // string
type NumberMessage = Message<number>;  // number
```

### Detailed Gotchas with Advanced Features

**1. Decorator metadata:**
```typescript
// For reflection metadata, you need reflect-metadata
import 'reflect-metadata';

function logType(target: any, propertyKey: string) {
  const type = Reflect.getMetadata('design:type', target, propertyKey);
  console.log(`${propertyKey} type: ${type.name}`);
}

class Test {
  @logType
  public name: string;
  
  @logType
  public age: number;
}

// Output:
// name type: String
// age type: Number
```

**2. Template literal types:**
```typescript
// Template literal types
type World = "world";
type Greeting = `hello ${World}`;  // "hello world"

// String manipulation types
type EmailLocaleIDs = "welcome_email" | "email_heading";
type FooterLocaleIDs = "footer_title" | "footer_sendoff";

type AllLocaleIDs = `${EmailLocaleIDs | FooterLocaleIDs}_id`;
// "welcome_email_id" | "email_heading_id" | "footer_title_id" | "footer_sendoff_id"
```

**3. Key remapping in mapped types:**
```typescript
// Key remapping
type Getters<T> = {
  [K in keyof T as `get${Capitalize<string & K>}`]: () => T[K]
};

interface Person {
  name: string;
  age: number;
  location: string;
}

type LazyPerson = Getters<Person>;
// {
//   getName: () => string;
//   getAge: () => number;
//   getLocation: () => string;
// }
```

## 14. JSON Handling

### Best Practices for JSON Handling

**1. Use proper typing for JSON data:**
```typescript
interface User {
  id: number;
  name: string;
  email: string;
  age: number;
}

// Parse JSON with type assertion
function parseUser(json: string): User {
  return JSON.parse(json) as User;
}

// Better - validate after parsing
function safeParseUser(json: string): User | null {
  try {
    const data = JSON.parse(json);
    
    // Basic validation
    if (typeof data.id !== 'number' || 
        typeof data.name !== 'string' || 
        typeof data.email !== 'string' || 
        typeof data.age !== 'number') {
      return null;
    }
    
    return data;
  } catch (error) {
    return null;
  }
}
```

**2. Handle JSON serialization of complex types:**
```typescript
class User {
  constructor(
    public id: number,
    public name: string,
    public createdAt: Date
  ) {}
  
  toJSON(): any {
    return {
      id: this.id,
      name: this.name,
      createdAt: this.createdAt.toISOString()
    };
  }
  
  static fromJSON(json: any): User {
    return new User(
      json.id,
      json.name,
      new Date(json.createdAt)
    );
  }
}

// Usage:
const user = new User(1, "Alice", new Date());
const json = JSON.stringify(user);  // Uses toJSON method
const parsedUser = User.fromJSON(JSON.parse(json));
```

**3. Use generics for reusable JSON utilities:**
```typescript
class JSONUtils {
  static parse<T>(json: string): T | null {
    try {
      return JSON.parse(json) as T;
    } catch (error) {
      console.error('JSON parse error:', error);
      return null;
    }
  }
  
  static stringify<T>(obj: T, space?: string | number): string {
    try {
      return JSON.stringify(obj, null, space);
    } catch (error) {
      console.error('JSON stringify error:', error);
      return '';
    }
  }
}

// Usage:
interface Config {
  apiUrl: string;
  timeout: number;
}

const configJson = '{"apiUrl": "https://api.example.com", "timeout": 5000}';
const config = JSONUtils.parse<Config>(configJson);
```

### Detailed Gotchas with JSON Handling

**1. JSON serialization limitations:**
```typescript
// JSON doesn't support:
const problematicData = {
  func: () => console.log("Hello"),  // Functions are omitted
  undef: undefined,                  // undefined values are omitted
  sym: Symbol("test"),              // Symbols are omitted
  date: new Date(),                 // Dates become strings
  regex: /test/g,                   // Regexps become empty objects
  nan: NaN,                         // NaN becomes null
  inf: Infinity                     // Infinity becomes null
};

console.log(JSON.stringify(problematicData));
// {"date":"2023-01-01T00:00:00.000Z","regex":{},"nan":null,"inf":null}
```

**2. Circular references:**
```typescript
const obj: any = { name: "Alice" };
obj.self = obj;  // Circular reference

// This will throw:
// JSON.stringify(obj);  // TypeError: Converting circular structure to JSON

// Solution - use a replacer function:
function getCircularReplacer() {
  const seen = new WeakSet();
  return (key: string, value: any) => {
    if (typeof value === "object" && value !== null) {
      if (seen.has(value)) {
        return "[Circular]";
      }
      seen.add(value);
    }
    return value;
  };
}

console.log(JSON.stringify(obj, getCircularReplacer()));
// {"name":"Alice","self":"[Circular]"}
```

**3. Date handling:**
```typescript
// Dates become strings in JSON:
const data = { date: new Date() };
const json = JSON.stringify(data);
console.log(json);  // {"date":"2023-01-01T00:00:00.000Z"}

// Parse back to Date:
const parsed = JSON.parse(json);
console.log(typeof parsed.date);  // string

// Convert back to Date:
parsed.date = new Date(parsed.date);
console.log(parsed.date instanceof Date);  // true

// Helper function:
function parseWithDates(json: string): any {
  return JSON.parse(json, (key, value) => {
    if (typeof value === 'string' && /\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/.test(value)) {
      return new Date(value);
    }
    return value;
  });
}
```

## 15. Best Practices and Gotchas

### General Best Practices

**1. Enable strict mode in tsconfig.json:**
```json
{
  "compilerOptions": {
    "strict": true,
    "noImplicitAny": true,
    "strictNullChecks": true,
    "strictFunctionTypes": true,
    "strictBindCallApply": true,
    "strictPropertyInitialization": true,
    "noImplicitThis": true,
    "alwaysStrict": true
  }
}
```

**2. Use ESLint with TypeScript plugin:**
```json
// .eslintrc.json
{
  "extends": [
    "@typescript-eslint/recommended",
    "prettier/@typescript-eslint",
    "plugin:prettier/recommended"
  ],
  "parser": "@typescript-eslint/parser",
  "plugins": ["@typescript-eslint"],
  "rules": {
    "@typescript-eslint/explicit-function-return-type": "warn",
    "@typescript-eslint/no-unused-vars": "error"
  }
}
```

**3. Write comprehensive tests:**
```typescript
// Using Jest with TypeScript
import { calculateArea } from './mathUtils';

describe('calculateArea', () => {
  it('should calculate area of rectangle', () => {
    expect(calculateArea(5, 3)).toBe(15);
  });
  
  it('should handle zero dimensions', () => {
    expect(calculateArea(0, 5)).toBe(0);
    expect(calculateArea(5, 0)).toBe(0);
  });
  
  it('should handle negative dimensions', () => {
    expect(calculateArea(-5, 3)).toBe(-15);  // Depending on requirements
  });
});
```

### Common TypeScript Gotchas Summary

**1. Type inference vs explicit typing:**
```typescript
// Let TypeScript infer when obvious:
const name = "Alice";  // string
const age = 25;        // number

// Be explicit for complex types:
interface User {
  name: string;
  age: number;
}

const user: User = {
  name: "Alice",
  age: 25
};

// Function return types:
function add(a: number, b: number) {  // TypeScript infers number
  return a + b;
}

function addExplicit(a: number, b: number): number {  // Explicit
  return a + b;
}
```

**2. Union types and type narrowing:**
```typescript
function processValue(value: string | number) {
  // Type narrowing:
  if (typeof value === "string") {
    console.log(value.toUpperCase());  // TypeScript knows it's a string
  } else {
    console.log(value.toFixed(2));     // TypeScript knows it's a number
  }
}

// With discriminated unions:
interface Success {
  type: "success";
  data: string;
}

interface Error {
  type: "error";
  message: string;
}

type Result = Success | Error;

function handleResult(result: Result) {
  if (result.type === "success") {
    console.log(result.data);  // TypeScript knows it's Success
  } else {
    console.log(result.message);  // TypeScript knows it's Error
  }
}
```

**3. any vs unknown:**
```typescript
// any - disables all type checking (avoid when possible)
let anyValue: any = "Hello";
anyValue = 42;        // OK
anyValue.someMethod(); // No compile-time error

// unknown - safer than any
let unknownValue: unknown = "Hello";
// unknownValue.someMethod();  // Error: Object is of type 'unknown'

if (typeof unknownValue === "string") {
  console.log(unknownValue.toUpperCase());  // OK - TypeScript knows it's a string
}
```

**4. Type vs Interface:**
```typescript
// Interfaces (preferred for object shapes):
interface User {
  name: string;
  age: number;
}

interface User {  // Declaration merging
  email: string;
}

// Types (more flexible):
type User2 = {
  name: string;
  age: number;
};

type StringOrNumber = string | number;

// Extending:
interface Admin extends User {
  permissions: string[];
}

type Admin2 = User & {
  permissions: string[];
};
```

### Performance Tips

**1. Use const assertions for literal types:**
```typescript
// Without const assertion:
const colors = ["red", "green", "blue"];  // string[]
type Color = typeof colors[number];       // string

// With const assertion:
const colors = ["red", "green", "blue"] as const;  // readonly ["red", "green", "blue"]
type Color = typeof colors[number];                 // "red" | "green" | "blue"
```

**2. Avoid excessive type assertions:**
```typescript
// Bad - excessive type assertions:
const data: any = fetchData();
const user = data as User;
const name = user.name as string;

// Good - validate and narrow types:
const data: unknown = fetchData();
if (isUser(data)) {
  const user: User = data;  // No assertion needed
  const name = user.name;   // TypeScript knows it's a string
}

function isUser(data: unknown): data is User {
  return (
    typeof data === "object" &&
    data !== null &&
    "name" in data &&
    "age" in data
  );
}
```

### Security Best Practices

**1. Validate external data:**
```typescript
// Never trust external data types:
interface ExternalUser {
  id: unknown;
  name: unknown;
  email: unknown;
}

function validateUser(data: ExternalUser): User | null {
  if (
    typeof data.id === "number" &&
    typeof data.name === "string" &&
    typeof data.email === "string" &&
    data.email.includes("@")
  ) {
    return {
      id: data.id,
      name: data.name,
      email: data.email
    };
  }
  return null;
}
```

**2. Handle sensitive data properly:**
```typescript
// Environment variables:
declare global {
  namespace NodeJS {
    interface ProcessEnv {
      DATABASE_URL: string;
      JWT_SECRET: string;
      API_KEY: string;
    }
  }
}

// Usage:
const dbUrl = process.env.DATABASE_URL;
if (!dbUrl) {
  throw new Error("DATABASE_URL is not set");
}
```

### Conclusion

This comprehensive TypeScript tutorial covers the essential concepts, best practices, and common gotchas you'll encounter as a TypeScript developer. Remember:

1. **Embrace TypeScript's type system** - it catches errors at compile time
2. **Use strict mode** - it helps you write more robust code
3. **Follow naming conventions** - consistency improves readability
4. **Write tests** - they ensure your code works as expected
5. **Stay updated** - TypeScript evolves rapidly with new features

TypeScript adds powerful static typing to JavaScript, helping you catch errors before runtime and providing better tooling support. By following these guidelines and being aware of the common pitfalls, you'll write better, more maintainable TypeScript code.