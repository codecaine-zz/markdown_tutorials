Based on my research of the latest TypeScript documentation and features, I'll now provide you with an improved and updated TypeScript tutorial that incorporates the newest TypeScript 5.0+ features and best practices.

# Complete TypeScript Tutorial (TypeScript 5.0+) – Extended Edition with Detailed Best Practices and Gotchas

## Table of Contents
1. [Introduction to TypeScript](#1-introduction-to-typescript)  
2. [Setting Up TypeScript](#2-setting-up-typescript)  
3. [Basic Syntax and Variables](#3-basic-syntax-and-variables)  
4. [Data Types](#4-data-types)  
5. [Operators](#5-operators)  
6. [Control Flow](#6-control-flow)  
7. [Functions](#7-functions)  
8. [Data Structures](#8-data-structures)  
9. [Object‑Oriented Programming](#9-object-oriented-programming)  
10. [Modules and Namespaces](#10-modules-and-namespaces)  
11. [File Handling](#11-file-handling)  
12. [Error Handling](#12-error-handling)  
13. [Advanced Features](#13-advanced-features)  
14. [JSON Handling](#14-json-handling)  
15. [Best Practices and Gotchas](#15-best-practices-and-gotchas)  

---

## 1. Introduction to TypeScript  

> TypeScript is a statically typed superset of JavaScript that compiles to plain JavaScript. It adds optional types, classes, interfaces, and other features to help catch errors at compile time.

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

* **Documentation:** *Everyday Types* – primitives, type annotations, inference【1†L4-L12】【1†L20-L28】  

---

## 2. Setting Up TypeScript  

### 2.1 Initialise a project  

```bash
npm init -y
npm install -D typescript @types/node
npx tsc --init
```

* **Documentation:** *What is a tsconfig.json* – project‑wide compiler options, `include`/`exclude` etc.【3†L4-L12】【3†L31-L38】

### 2.2 Sample `tsconfig.json`

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
    "forceConsistentCasingInFileNames": true,
    // TypeScript 5.0+ features
    "verbatimModuleSyntax": true,
    "moduleResolution": "bundler"
  },
  "include": ["src/**/*"],
  "exclude": ["node_modules", "dist"]
}
```

* **Documentation:** *TSConfig Reference* – list of all compiler options【3†L118-L124】

### 2.3 npm scripts  

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

---

## 3. Basic Syntax and Variables  

```typescript
// const vs let vs var
const userName = "Alice";   // never reassigned
let counter = 0;            // may change
var oldStyle = "avoid";    // function‑scoped, discouraged
```

* **Documentation:** *Basic Types & Variable Declarations* – `let`, `const`, type annotations【1†L86-L95】

### 3.1 Interfaces for complex objects  

```typescript
interface User {
  name: string;
  age: number;
  email: string;
}
const user: User = { name: "Alice", age: 25, email: "alice@example.com" };
```

* **Documentation:** *Interfaces* – defining object shapes【5†L24-L31】

---

## 4. Data Types  

### 4.1 Primitives & literals  

* `string`, `number`, `boolean` – see *Everyday Types* (primitives)【1†L20-L28】  

### 4.2 Arrays, tuples & generics  

```typescript
const numbers: number[] = [1, 2, 3];
const point: [number, number] = [10, 20];   // tuple
```

* **Documentation:** *Arrays* – `T[]` and `Array<T>` syntax, see "Arrays" section【1†L39-L45】  

### 4.3 Enums  

```typescript
enum Direction { Up = 1, Down, Left, Right }
enum HttpStatus { Ok = "OK", NotFound = "NOT_FOUND" }
```

* **Documentation:** *Enums* – numeric and string enums【5†L96-L106】

### 4.4 Union & literal types  

```typescript
type Status = "pending" | "approved" | "rejected";
const orderStatus: Status = "pending";
```

* **Documentation:** *Advanced Types* – union and literal types【5†L215-L228】

### 4.5 `any` vs `unknown`

```typescript
let anyValue: any = "hi";
let unknownValue: unknown = "hi";
if (typeof unknownValue === "string") {
  console.log(unknownValue.toUpperCase());
}
```

* **Documentation:** *The `any` type* – why to avoid it, and the safer `unknown` type【1†L50-L58】【1†L62-L69】

---

## 5. Operators  

* Strict equality (`===`, `!==`) – recommended.  
* Nullish coalescing (`??`) vs logical OR (`||`).  
* Spread (`...`) for immutable copies.

* **Documentation:** *Operators* – equality, logical, spread, nullish coalescing are covered throughout the handbook (see the "Operators" chapter).  

---

## 6. Control Flow  

```typescript
function processUser(data: User | null): string {
  if (!data) return "No data";
  if (data.age < 18) return "Underage";
  return "OK";
}
```

* **Documentation:** *Control Flow Analysis* – type‑guards, exhaustiveness checking【5†L170-L186】

---

## 7. Functions  

### 7.1 Basic function with explicit types  

```typescript
function add(a: number, b: number): number {
  return a + b;
}
```

* **Documentation:** *Functions* – declarations, type annotations, return types【4†L71-L84】【4†L91-L100】

### 7.2 Function types & overloads  

```typescript
type MathOp = (a: number, b: number) => number;
const addOp: MathOp = (a, b) => a + b;

function format(value: string): string;
function format(value: number): string;
function format(value: string | number): string {
  return String(value);
}
```

* **Documentation:** *Function Types* – signatures, overloads【4†L63-L73】【4†L84-L92】

### 7.3 TypeScript 5.0+ Features: `@overload` in JSDoc

```typescript
// @ts-check

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
 * @param {string | number} value
 * @param {number} [maximumFractionDigits]
 */
function printValue(value, maximumFractionDigits) {
  if (typeof value === "number") {
    const formatter = Intl.NumberFormat("en-US", {
      maximumFractionDigits,
    });
    value = formatter.format(value);
  }

  console.log(value);
}
```

---

## 8. Data Structures  

### 8.1 Sets & Maps  

```typescript
const unique = new Set<string>(["a", "b"]);
const dict = new Map<number, string>();
dict.set(1, "one");
```

* **Documentation:** *ES2015 Collections* – `Set` and `Map` usage (see the "Built‑in Objects" section).  

### 8.2 Read‑only arrays & tuples  

```typescript
const colors = ["red", "green", "blue"] as const;   // readonly tuple
type Color = typeof colors[number];                 // "red" | "green" | "blue"
```

* **Documentation:** *Literal Types & const assertions* – using `as const` for literal inference【5†L215-L223】

### 8.3 TypeScript 5.0+ Features: `const` Type Parameters

```typescript
// Before TypeScript 5.0
function getNamesExactly<T extends { names: readonly string[] }>(arg: T): T["names"] {
  return arg.names;
}

// You had to use 'as const' to get exact types
const names1 = getNamesExactly({ names: ["Alice", "Bob", "Eve"] as const });

// With TypeScript 5.0+
function getNamesExactly<const T extends { names: readonly string[] }>(arg: T): T["names"] {
  return arg.names;
}

// No need for 'as const' anymore
const names2 = getNamesExactly({ names: ["Alice", "Bob", "Eve"] });
// Type is now readonly ["Alice", "Bob", "Eve"] instead of string[]
```

---

## 9. Object‑Oriented Programming  

### 9.1 Classes & access modifiers  

```typescript
class BankAccount {
  private balance = 0;
  protected accountType: string;
  public accountNumber: string;

  constructor(accountNumber: string, accountType = "checking") {
    this.accountNumber = accountNumber;
    this.accountType = accountType;
  }

  public deposit(amount: number): void {
    if (amount > 0) this.balance += amount;
  }

  public getBalance(): number {
    return this.balance;
  }
}
```

* **Documentation:** *Classes* – fields, constructors, modifiers, inheritance【5†L24-L32】【5†L56-L68】

### 9.2 Interfaces & abstract classes  

```typescript
interface Shape {
  getColor(): string;
  calculateArea(): number;
}
abstract class AbstractShape implements Shape {
  constructor(protected color: string) {}
  abstract calculateArea(): number;
  getColor(): string { return this.color; }
}
```

* **Documentation:** *Interfaces* and *Abstract Classes* – contracts and partial implementations【5†L24-L31】【5†L56-L64】

### 9.3 Generics in classes  

```typescript
class Container<T> {
  private items: T[] = [];
  add(item: T) { this.items.push(item); }
  get(index: number): T { return this.items[index]; }
}
```

* **Documentation:** *Generics* – generic classes, methods【5†L215-L226】

### 9.4 TypeScript 5.0+ Features: Standardized Decorators

```typescript
// Enable experimentalDecorators in tsconfig (see tsconfig docs)
function loggedMethod(originalMethod: any, context: ClassMethodDecoratorContext) {
  const methodName = String(context.name);

  function replacementMethod(this: any, ...args: any[]) {
    console.log(`LOG: Entering method '${methodName}'.`);
    const result = originalMethod.call(this, ...args);
    console.log(`LOG: Exiting method '${methodName}'.`);
    return result;
  }

  return replacementMethod;
}

class Person {
  name: string;
  constructor(name: string) {
    this.name = name;
  }

  @loggedMethod
  greet() {
    console.log(`Hello, my name is ${this.name}.`);
  }
}

const p = new Person("Ron");
p.greet();
// Output:
// LOG: Entering method 'greet'.
// Hello, my name is Ron.
// LOG: Exiting method 'greet'.
```

---

## 10. Modules and Namespaces  

### 10.1 ES‑module syntax  

```typescript
// mathUtils.ts
export const PI = 3.14159;
export function add(a: number, b: number): number { return a + b; }
export default function subtract(a: number, b: number): number { return a - b; }

// main.ts
import subtract, { PI, add } from "./mathUtils";
```

* **Documentation:** *Modules* – import/export, default exports【6†L4-L12】

### 10.2 Barrel (index) files  

```typescript
// utils/index.ts
export * from "./mathUtils";
export * from "./stringUtils";
```

* **Documentation:** *Modules – Re‑exports* – how to create a "barrel" file【6†L18-L23】

### 10.3 Namespaces (legacy)  

```typescript
namespace Validation {
  export interface StringValidator {
    isAcceptable(s: string): boolean;
  }
  const lettersRegexp = /^[A-Za-z]+$/;
  export class LettersOnlyValidator implements StringValidator {
    isAcceptable(s: string) { return lettersRegexp.test(s); }
  }
}
```

* **Documentation:** *Namespaces* – internal modules, exporting members【8†L4-L11】【8†L21-L31】

### 10.4 TypeScript 5.0+ Features: Multiple Configuration Files in `extends`

```json
// tsconfig.json
{
  "extends": ["@tsconfig/strictest/tsconfig.json", "../../../tsconfig.base.json"],
  "compilerOptions": {
    "outDir": "../lib"
  },
  "files": ["./index.ts"]
}
```

---

## 11. File Handling  

```typescript
import { promises as fs } from "fs";
import { join } from "path";

async function readFileContent(filePath: string): Promise<string> {
  return await fs.readFile(filePath, "utf-8");
}
```

* **Documentation:** *Node.js Type Definitions* – `fs.promises` is covered under the Node.js library typings (see the "Node.js" section of the handbook).  

---

## 12. Error Handling  

```typescript
class ApplicationError extends Error {
  constructor(message: string, public code?: string) {
    super(message);
    this.name = this.constructor.name;
  }
}
```

* **Documentation:** *Error Handling* – extending built‑in `Error`, custom error classes (see the "Classes" chapter).  

---

## 13. Advanced Features  

### 13.1 Decorators  

```typescript
// Enable experimentalDecorators in tsconfig (see tsconfig docs)
function log(target: any, propertyKey: string, descriptor: PropertyDescriptor) {
  const original = descriptor.value;
  descriptor.value = function (...args: any[]) {
    console.log(`Calling ${propertyKey}`, args);
    return original.apply(this, args);
  };
}
class Calculator {
  @log
  add(a: number, b: number) { return a + b; }
}
```

* **Documentation:** *Decorators* – enabling, usage on classes/methods, decorator factories【9†L21-L33】【9†L46-L53】

### 13.2 Conditional Types  

```typescript
type NonNullable<T> = T extends null | undefined ? never : T;
type Message<T> = T extends string ? string : number;
```

* **Documentation:** *Conditional Types* – `extends` in type positions【5†L215-L228】

### 13.3 Mapped & key‑remapped types  

```typescript
type Getters<T> = {
  [K in keyof T as `get${Capitalize<string & K>}`]: () => T[K];
};
```

* **Documentation:** *Mapped Types* – key remapping, `as` clause【5†L240-L250】

### 13.4 TypeScript 5.0+ Features: `@satisfies` Support in JSDoc

```typescript
// @ts-check

/**
 * @typedef CompilerOptions
 * @prop {boolean} [strict]
 * @prop {string} [outDir]
 */

/**
 * @typedef ConfigSettings
 * @prop {CompilerOptions} [compilerOptions]
 * @prop {string | string[]} [extends]
 */

/**
 * @satisfies {ConfigSettings}
 */
let myConfigSettings = {
  compilerOptions: {
    strict: true,
    outDir: "../lib",
  },
  extends: [
    "@tsconfig/strictest/tsconfig.json",
    "../../../tsconfig.base.json"
  ],
};

// TypeScript knows that myConfigSettings.extends is an array
let inheritedConfigs = myConfigSettings.extends.map(resolveConfig);
```

---

## 14. JSON Handling  

```typescript
interface User { id: number; name: string; email: string; }
function parseUser(json: string): User {
  return JSON.parse(json) as User;   // simple cast
}
```

* **Documentation:** *JSON* – serialization, `JSON.parse`/`stringify`, custom `toJSON` methods (see the "Libraries" chapter).  

---

## 15. Best Practices and Gotchas  

| Area | Official guidance |
|------|-------------------|
| **Strict mode** – `strict`, `noImplicitAny`, `strictNullChecks` | *TSConfig Reference* – enabling all strict flags【3†L118-L124】 |
| **ES‑lint** – `@typescript-eslint` rules | *Style guide* – not part of the handbook but recommended in the "Tools" section |
| **Testing** – Jest with TypeScript | *Testing* – see the "Testing" guide (linked from the handbook) |
| **Performance** – `as const` for literal types, avoid over‑using `any` | *Literal Types* – const assertions【5†L215-L223】 |
| **Security** – validate external data, keep secrets out of code | *Type Safety* – use `unknown` and type guards, see "Advanced Types" |

### TypeScript 5.0+ Best Practices

1. **Use `const` type parameters** for better type inference:
   ```typescript
   function useConstType<const T>(arg: T) {
     // Better inference without 'as const'
   }
   ```

2. **Leverage standardized decorators** instead of legacy experimental decorators:
   ```typescript
   // Modern decorator syntax
   class MyClass {
     @loggedMethod
     myMethod() { /* ... */ }
   }
   ```

3. **Use `verbatimModuleSyntax`** for clearer import/export behavior:
   ```json
   {
     "compilerOptions": {
       "verbatimModuleSyntax": true
     }
   }
   ```

4. **Take advantage of multiple `extends`** in tsconfig.json:
   ```json
   {
     "extends": ["@tsconfig/strictest/tsconfig.json", "./base.json"]
   }
   ```

5. **Use `@satisfies` in JSDoc** for type checking without type changes:
   ```javascript
   /** @satisfies {ConfigSettings} */
   let config = { /* ... */ };
   ```

---

### Footnotes – Quick Links to the Official Handbook  

| # | Topic | Link |
|---|-------|------|
| 1 | Everyday Types (primitives, type annotations, inference) | https://www.typescriptlang.org/docs/handbook/2/everyday-types.html |
| 2 | tsconfig.json – project configuration | https://www.typescriptlang.org/docs/handbook/tsconfig-json.html |
| 3 | Functions – declarations, overloads, signatures | https://www.typescriptlang.org/docs/handbook/functions.html |
| 4 | Classes – fields, constructors, inheritance, abstract | https://www.typescriptlang.org/docs/handbook/classes.html |
| 5 | Modules – import/export, re‑exports, module resolution | https://www.typescriptlang.org/docs/handbook/modules.html |
| 6 | Namespaces – internal modules, export syntax | https://www.typescriptlang.org/docs/handbook/namespaces.html |
| 7 | Decorators – experimental support, usage on classes/methods | https://www.typescriptlang.org/docs/handbook/decorators.html |
| 8 | Enums, Advanced Types (union, literal, conditional) | https://www.typescriptlang.org/docs/handbook/2/everyday-types.html#union-types (and the "Advanced Types" chapter) |
| 9 | Mapped & key‑remapped types | https://www.typescriptlang.org/docs/handbook/2/mapped-types.html |

Feel free to click any of the links directly in the tutorial to jump to the corresponding section of the official TypeScript documentation. Happy coding!