# Modern JavaScript Reference Guide

Welcome to the Modern JavaScript Reference Guide. This tutorial is structured to help beginners and intermediate developers master modern JavaScript (ES6+) through clean, self-contained examples and clear explanations.

Every topic is illustrated with code snippets that you can copy and run directly in a browser developer console or a Node.js REPL.

---

## Table of Contents

1. [Variables, Constants & Hoisting](#1-variables-constants--hoisting)
2. [Data Types & typeof](#2-data-types--typeof)
3. [String Quotes & Template Literals](#3-string-quotes--template-literals)
4. [Numbers, NaN & Infinity](#4-numbers-nan--infinity)
5. [Type Conversions](#5-type-conversions)
6. [Basic Operators](#6-basic-operators)
7. [Comparisons, Coercion & Optional Chaining](#7-comparisons-coercion--optional-chaining)
8. [Logical Operators & Short-Circuiting](#8-logical-operators--short-circuiting)
9. [Control Flow: If, Switch & Ternary](#9-control-flow-if-switch--ternary)
10. [Loops: While, Do-While, For, For-Of & For-In](#10-loops-while-do-while-for-for-of--for-in)
11. [Functions: Declarations, Expressions & Arrows](#11-functions-declarations-expressions--arrows)
12. [The 'this' Keyword & Explicit Binding](#12-the-this-keyword--explicit-binding)
13. [Objects: Creation, Methods & Property Descriptors](#13-objects-creation-methods--property-descriptors)
14. [Object Destructuring & Spread Operator](#14-object-destructuring--spread-operator)
15. [Arrays: Basic & Higher-Order Methods](#15-arrays-basic--higher-order-methods)
16. [Sets & Maps](#16-sets--maps)
17. [Date & Time](#17-date--time)
18. [Error Handling & Custom Errors](#18-error-handling--custom-errors)
19. [Asynchronous JavaScript: Callbacks, Promises & Async/Await](#19-asynchronous-javascript-callbacks-promises--asyncawait)
20. [ES6 Modules](#20-es6-modules)
21. [Regular Expressions](#21-regular-expressions)
22. [JSON Serialization & Parsing](#22-json-serialization--parsing)
23. [Symbols](#23-symbols)
24. [Proxies](#24-proxies)
25. [Classic Prototype-Based Inheritance](#25-classic-prototype-based-inheritance)
26. [ES6 Class Syntax](#26-es6-class-syntax)
27. [DOM Manipulation & Browser APIs](#27-dom-manipulation--browser-apis)
28. [Common Mistakes & Best Practices](#28-common-mistakes--best-practices)

---

### 1. Variables, Constants & Hoisting

JavaScript offers three main ways to declare variables: `let`, `const`, and `var`. Understanding scope and hoisting is crucial to avoiding bugs.

- **`let`**: Block-scoped. Variables declared with `let` are only accessible inside the `{}` block where they are defined, and they can be reassigned.
- **`const`**: Block-scoped. Variables declared with `const` cannot be reassigned. However, the value they hold *can* still be mutated (e.g., properties of an object or elements of an array).
- **`var`**: Function-scoped. Avoid using `var` in modern JavaScript. It ignores block scopes (like `if` statements or `for` loops), and it can lead to unexpected behavior due to hoisting.
- **Hoisting**: JavaScript moves variable and function declarations to the top of their containing scope before code execution.
  - `var` variables are hoisted and initialized to `undefined`.
  - `let` and `const` variables are hoisted but not initialized, residing in a **Temporal Dead Zone (TDZ)**. Accessing them before their declaration line throws a `ReferenceError`.

```javascript
// "use strict"; // Enables strict mode (recommended at the top of your scripts)

// 1. let - block-scoped and mutable
let age = 25;
age = 26; // Reassignment is allowed
console.log(age); // Output: 26

// 2. const - block-scoped and immutable binding
const PI = 3.14159;
// PI = 3.14; // TypeError: Assignment to constant variable.

const user = { name: "Ana" };
user.name = "Bob"; // Allowed! The object's properties can change, but the reference to the object cannot.
// user = {}; // TypeError: Assignment to constant variable.

// 3. var - function-scoped and hoisted
var legacy = "I exist globally or function-wide";

function hoistDemo() {
    // console.log(hoistedVar); // Output: undefined (hoisted declaration, but not assignment)
    var hoistedVar = "I am hoisted!";
    console.log(hoistedVar); // Output: "I am hoisted!"
}
hoistDemo();
```

---

### 2. Data Types & typeof

JavaScript is a dynamically typed language. The interpreter assigns a type to variables at runtime based on their value. There are **8 data types**: 7 primitive types and 1 non-primitive type (Object).

- **Primitives** (immutable values representing a single value):
  - `String`: Text.
  - `Number`: Integers and decimal numbers.
  - `Boolean`: `true` or `false`.
  - `Undefined`: A variable that has been declared but not assigned a value.
  - `Null`: Represents a deliberate non-value.
  - `Symbol`: Unique and immutable identifiers.
  - `BigInt`: Integers of arbitrary size (useful for extra-large numbers).
- **Non-Primitives** (mutable data structures):
  - `Object`: Collection of properties (includes arrays, functions, dates, etc.).
- **Historical Bug**: Calling `typeof null` returns `"object"`. This is a legacy bug in JavaScript that was never fixed to prevent breaking existing web code.

```javascript
let str = "hello";         // String
let num = 42;              // Number
let bool = true;           // Boolean
let undef;                 // Undefined
let nul = null;            // Null
let sym = Symbol("id");    // Symbol
let big = 10n;             // BigInt
let obj = { name: "Ana" }; // Object

console.log(typeof str);   // Output: "string"
console.log(typeof num);   // Output: "number"
console.log(typeof bool);  // Output: "boolean"
console.log(typeof undef); // Output: "undefined"
console.log(typeof nul);   // Output: "object" (Known JS bug!)
console.log(typeof sym);   // Output: "symbol"
console.log(typeof big);   // Output: "bigint"
console.log(typeof obj);   // Output: "object"
```

---

### 3. String Quotes & Template Literals

In JavaScript, strings can be defined using single quotes (`'`), double quotes (`"`), or backticks (`` ` ``).

- **Single & Double Quotes**: Perform identically.
- **Template Literals (Backticks)**: Provide powerful formatting capabilities:
  - **Interpolation**: Safely embed expressions or variables inside strings using the `${expression}` syntax.
  - **Multi-line Strings**: Preserve line breaks directly inside the code without using escape characters like `\n`.

```javascript
let name = "Alice";
let single = 'single quotes';
let double = "double quotes";

// String interpolation with template literals
let backticks = `Hello, ${name}! 2 + 2 = ${2 + 2}`;
console.log(backticks); // Output: "Hello, Alice! 2 + 2 = 4"

// Multi-line strings
let multiLine = `First line
Second line`;
console.log(multiLine);
/* Output:
First line
Second line
*/
```

---

### 4. Numbers, NaN & Infinity

JavaScript represents all numbers using a double-precision 64-bit format (IEEE 754). This single type handles both integers and decimal values. It also features three special numeric values:

- **`NaN`** (Not-a-Number): Represents a computational error or invalid math (e.g., dividing zero by zero). `NaN` is unique because it is not equal to anything, including itself.
- **`Infinity` & `-Infinity`**: Represent mathematical infinity.
- **Safer Checks**: Always use `Number.isNaN()` rather than the global `isNaN()`. The global version attempts type coercion before making the check, which leads to confusing behavior (e.g., `isNaN("hello")` is `true`, while `Number.isNaN("hello")` is `false`).

```javascript
let nan = 0 / 0;      // NaN
let inf = 1 / 0;      // Infinity
let negInf = -1 / 0;  // -Infinity

console.log(typeof nan); // Output: "number" (NaN is technically a number type!)

// Checking for NaN
console.log(nan === nan);           // Output: false (NaN never equals itself)
console.log(Number.isNaN(nan));     // Output: true
console.log(isNaN("hello"));        // Output: true (attempts to convert "hello" to a number, gets NaN)
console.log(Number.isNaN("hello")); // Output: false (checks if value is NaN directly, without converting type first)

// Checking for Finite numbers
console.log(isFinite(inf)); // Output: false
console.log(isFinite(42));  // Output: true
```

---

### 5. Type Conversions

JavaScript variables can be converted to other types explicitly (manually) or implicitly (automatically via coercion).

- **String Conversion**: Converts a value to text.
- **Number Conversion**: Converts a value to a numeric type. Unary plus (`+`) is a common shorthand.
- **Boolean Conversion**: Evaluates values as either `true` (truthy) or `false` (falsy).
  - **Falsy values**: `0`, `""` (empty string), `null`, `undefined`, `NaN`, and `false`.
  - **Truthy values**: Everything else (including empty arrays `[]` and empty objects `{}`).

```javascript
// --- Conversion to String ---
console.log(String(123));          // Output: "123"
console.log(String(false));        // Output: "false"
console.log(String(null));         // Output: "null"

// --- Conversion to Number ---
console.log(Number("45"));         // Output: 45
console.log(Number(""));           // Output: 0
console.log(Number(null));         // Output: 0
console.log(Number(undefined));    // Output: NaN
console.log(+"12");                // Output: 12 (Unary plus shorthand)

// --- Conversion to Boolean ---
console.log(Boolean(0));           // Output: false
console.log(Boolean(""));          // Output: false
console.log(Boolean(null));        // Output: false
console.log(Boolean("text"));      // Output: true
console.log(Boolean([]));          // Output: true (Arrays are objects, which are truthy!)
```

---

### 6. Basic Operators

Operators manipulate operands to produce a result.

- **Arithmetic Operators**: `+` (addition), `-` (subtraction), `*` (multiplication), `/` (division), `%` (modulo - remainder), and `**` (exponentiation).
- **Increment/Decrement**: `++` and `--` can be applied before (prefix) or after (postfix) a variable.
  - **Postfix (`x++`)**: Returns the current value, then modifies the variable.
  - **Prefix (`++x`)**: Modifies the variable, then returns the new value.
- **Assignment**: Returns the assigned value. Multiple variables can be chained.

```javascript
// Arithmetic
let sum = 5 + 2;   // 7
let diff = 5 - 2;  // 3
let prod = 5 * 2;  // 10
let div = 5 / 2;   // 2.5
let mod = 5 % 2;   // 1 (remainder)
let pow = 2 ** 3;  // 8 (2 cubed)

// Postfix vs Prefix
let i = 1;
let post = i++; // post gets value of i (1), then i becomes 2
console.log(post, i); // Output: 1 2

let pre = ++i; // i becomes 3, pre gets value of i (3)
console.log(pre, i); // Output: 3 3

// Chained Assignment
let x, y;
x = (y = 3);
console.log(x, y); // Output: 3 3
```

---

### 7. Comparisons, Coercion & Optional Chaining

Comparison operators verify the relationship between values.

- **Loose Equality (`==`)**: Compares values after coercing their types. Avoid this, as it often produces confusing results.
- **Strict Equality (`===`)**: Compares values and types without coercion. This is the industry-standard comparison method.
- **Nullish Coalescing (`??`)**: Returns the right-hand operand only if the left-hand operand is `null` or `undefined`. Unlike the logical OR (`||`) operator, it does not fall back for other falsy values (like `0` or `""`).
- **Optional Chaining (`?.`)**: Safely accesses deeply nested properties. If any intermediate reference is `null` or `undefined`, the expression returns `undefined` instead of crashing.

```javascript
// Comparisons
console.log(5 > 4);            // Output: true
console.log(5 == "5");         // Output: true  (Loose comparison converts "5" to a number)
console.log(5 === "5");        // Output: false (Strict comparison checks types)

// Special cases to avoid
console.log(null == undefined); // Output: true
console.log(null === undefined); // Output: false
console.log(null >= 0);        // Output: true  (Avoid mixed logic comparisons!)

// Nullish Coalescing (??)
let userInput = null;
let saved = userInput ?? "default";
console.log(saved); // Output: "default"

let clicks = 0;
console.log(clicks || 10); // Output: 10 (since 0 is falsy, || falls back)
console.log(clicks ?? 10); // Output: 0  (since 0 is not null/undefined, ?? accepts it)

// Optional Chaining (?.)
const user = { name: "Ana", address: { city: "Madrid" } };
console.log(user?.address?.city); // Output: "Madrid"
console.log(user?.contact?.phone); // Output: undefined (no crash!)
```

---

### 8. Logical Operators & Short-Circuiting

JavaScript logical operators use short-circuit evaluation, returning the value of one of their operands directly rather than a strict boolean.

- **Logical OR (`||`)**: Returns the **first truthy** value it encounters. If all are falsy, it returns the final value.
- **Logical AND (`&&`)**: Returns the **first falsy** value it encounters. If all are truthy, it returns the final value.
- **Logical NOT (`!`)**: Converts a value to its boolean opposite. Adding a double exclamation mark (`!!`) is a quick way to convert any value into a boolean.

```javascript
// Logical OR (||) - returns first truthy operand
let result = "" || 0 || "hello" || "world";
console.log(result); // Output: "hello"

// Logical AND (&&) - returns first falsy operand
let result2 = "foo" && null && 5;
console.log(result2); // Output: null

// Using AND for conditional execution
let userLoaded = true;
userLoaded && console.log("Initializing Dashboard..."); // Output: "Initializing Dashboard..."

// Double NOT conversion
console.log(!!""); // Output: false
console.log(!!42); // Output: true
```

---

### 9. Control Flow: If, Switch & Ternary

Control flow statements branch code execution based on conditions.

- **`if / else if / else`**: Executes code blocks based on truthy/falsy evaluation.
- **Ternary Operator (`? :`)**: A shorthand expression for `if-else` blocks.
- **`switch`**: Evaluates an expression against multiple cases using strict comparison (`===`). Remember to end each case with `break` to prevent fall-through!

```javascript
let score = 85;

// if / else if / else
if (score >= 90) {
    console.log("Grade: A");
} else if (score >= 80) {
    console.log("Grade: B"); // Output: "Grade: B"
} else {
    console.log("Grade: C");
}

// Ternary operator (condition ? trueResult : falseResult)
let grade = (score >= 90) ? "A" : (score >= 80) ? "B" : "C";
console.log(grade); // Output: "B"

// switch statement (strict equality ===)
switch (grade) {
    case "A":
        console.log("Excellent job!");
        break;
    case "B":
        console.log("Good effort!"); // Output: "Good effort!"
        break;
    default:
        console.log("Pass");
}
```

---

### 10. Loops: While, Do-While, For, For-Of & For-In

Loops allow repetitive execution of code blocks.

- **`while`**: Continually evaluates a condition and runs code as long as the condition remains true.
- **`do...while`**: Runs code at least once before checking the condition.
- **`for`**: Classic loop containing initializer, condition, and step.
- **`for...of`**: Best for iterating over values of iterable collections (Arrays, Strings, Maps, Sets).
- **`for...in`**: Best for iterating over the keys of an object. Do not use this to iterate arrays.
- **`break` / `continue`**: `break` exits the loop. `continue` skips the rest of the current iteration and jumps to the next evaluation.

```javascript
// while loop
let n = 0;
while (n < 3) {
    console.log("while count:", n);
    n++;
}

// do...while loop
let m = 0;
do {
    console.log("do count:", m);
    m++;
} while (m < 3);

// standard for loop
for (let i = 0; i < 3; i++) {
    console.log("for loop index:", i);
}

// for...of (iterating values of arrays)
let arr = [10, 20, 30];
for (let val of arr) {
    console.log("for...of value:", val);
}

// for...in (iterating keys of objects)
let obj = { a: 1, b: 2 };
for (let key in obj) {
    console.log("for...in property:", key, "=", obj[key]);
}

// break & continue
for (let i = 0; i < 5; i++) {
    if (i === 2) continue; // skips logging '2'
    if (i === 4) break;    // halts the loop entirely
    console.log("number:", i); // Output: 0, 1, 3
}
```

---

### 11. Functions: Declarations, Expressions & Arrows

JavaScript supports multiple ways to define functions, each having unique behavior.

- **Function Declarations**: Hoisted to the top of the scope. They can be called *before* they are written in the source code.
- **Function Expressions**: Declared inside a variable. Not hoisted. Cannot be called before assignment.
- **Arrow Functions**: Introduced in ES6, offering a shorter syntax. Arrow functions do not define their own context for `this`; they inherit it dynamically from their enclosing scope.
- **Default Parameters**: Fallback values used when arguments are omitted or passed as `undefined`.
- **Rest Parameters (`...`)**: Groups multiple remaining arguments into a single array.

```javascript
// 1. Function Declaration (Hoisted)
console.log(sum(2, 3)); // Output: 5 (runs because of hoisting)
function sum(a, b) {
    return a + b;
}

// 2. Function Expression (Not Hoisted)
const mul = function(a, b) {
    return a * b;
};
console.log(mul(2, 3)); // Output: 6

// 3. Arrow Function
const pow = (x, n) => x ** n; // Implicit return when omitting curly braces {}
console.log(pow(2, 3)); // Output: 8

// 4. Default Parameters
function greet(name = "Guest") {
    console.log(`Hello, ${name}`);
}
greet(); // Output: Hello, Guest

// 5. Rest Parameters
function concat(separator, ...parts) {
    return parts.join(separator);
}
console.log(concat("-", "a", "b", "c")); // Output: "a-b-c"
```

---

### 12. The 'this' Keyword & Explicit Binding

The value of `this` refers to the object executing the current function. It is dynamic and depends entirely on the execution context.

- **Object Method**: `this` refers to the object containing the method.
- **Arrow Function**: Arrow functions do not have a `this` context. They capture the `this` value of their enclosing execution environment.
- **Explicit Binding**:
  - **`call(thisArg, arg1, arg2...)`**: Invokes the function immediately, binding `this` to the first argument.
  - **`apply(thisArg, [argsArray])`**: Identical to `call`, but arguments are passed in a single array.
  - **`bind(thisArg)`**: Returns a new copy of the function with `this` permanently bound. The new function can be invoked later.

```javascript
const person = {
    name: "Tom",
    sayHi() {
        console.log(this.name);
    },
    shout: () => {
        // Arrow functions do not bind 'this'; they inherit it from the outer scope (e.g. global window object)
        console.log(this.name);
    }
};

person.sayHi(); // Output: "Tom" (the calling context is 'person')
person.shout(); // Output: undefined (no name property on global scope)

// Explicit Binding Example
function showDetails(age, job) {
    console.log(`${this.id} is ${age} years old and works as a ${job}.`);
}

const employee1 = { id: "User_101" };

// Using call
showDetails.call(employee1, 30, "Designer"); // Output: "User_101 is 30 years old and works as a Designer."

// Using apply
showDetails.apply(employee1, [25, "Developer"]); // Output: "User_101 is 25 years old and works as a Developer."

// Using bind (creates new bound function)
const boundShow = showDetails.bind(employee1);
boundShow(40, "Manager"); // Output: "User_101 is 40 years old and works as a Manager."
```

---

### 13. Objects: Creation, Methods & Property Descriptors

Objects store keyed collections of data.

- **Constructor Functions**: Mimic class instances using `new`.
- **Property Descriptors**: Configured via `Object.defineProperty()`.
  - `writable`: If `false`, the property value cannot be reassigned.
  - `configurable`: If `false`, the property cannot be deleted or reconfigured.
  - `enumerable`: If `false`, the property is hidden in loops.
- **Getters & Setters**: Methods that access or modify virtual properties.

```javascript
// 1. Literal Object
let car = {
    brand: "Toyota",
    speed: 0,
    accelerate(delta) {
        this.speed += delta;
    }
};
car.accelerate(50);
console.log(car.speed); // Output: 50

// 2. Constructor Function
function User(name) {
    this.name = name;
}
let u = new User("Anna");
console.log(u.name); // Output: "Anna"

// 3. Property Descriptor
Object.defineProperty(car, "speed", {
    writable: false, // Make property read-only
    configurable: true,
    enumerable: true,
    value: car.speed
});
// car.speed = 100; // Throws a TypeError in strict mode!

// 4. Getters / Setters
let rectangle = {
    width: 10,
    height: 20,
    get area() {
        return this.width * this.height;
    },
    set area(v) {
        console.warn("area is read-only!");
    }
};
console.log(rectangle.area); // Output: 200
```

---

### 14. Object Destructuring & Spread Operator

Destructuring and spread operators streamline handling objects and arrays.

- **Destructuring**: Conveniently extracts values from objects into variables.
- **Spread Operator (`...`)**: Copies properties from one object to another. Note that it performs a **shallow copy**, meaning nested objects remain reference-linked to the original.

```javascript
let options = { title: "Menu", width: 100, height: 200 };

// Object Destructuring
let { title, width, ...rest } = options;
console.log(title); // Output: "Menu"
console.log(width); // Output: 100
console.log(rest);  // Output: { height: 200 } (rest collects remaining keys)

// Object Merging with Spread
let objA = { a: 1, b: 2 };
let objB = { b: 3, c: 4 }; // 'b' will be overwritten by objB's 'b'
let merged = { ...objA, ...objB };
console.log(merged); // Output: { a: 1, b: 3, c: 4 }
```

---

### 15. Arrays: Basic & Higher-Order Methods

Arrays are indexed lists of data. JavaScript features highly optimized built-in higher-order array iteration methods:

- **`map`**: Returns a new array, transforming each item.
- **`filter`**: Returns a new array containing items that pass a conditional test.
- **`reduce`**: Reduces an array to a single accumulator value.
- **`find` / `findIndex`**: Searches for the first matching element or its index.
- **`some` / `every`**: Check if one or all items meet a condition.
- **`sort`**: Sorts elements in place. Crucial: sort converts elements to strings by default (so 10 would sort before 2). Provide a comparison callback for numeric sorting.

```javascript
let numbers = [1, 2, 3, 4, 5];

// map - transform elements
let squares = numbers.map(x => x * x);
console.log(squares); // Output: [1, 4, 9, 16, 25]

// filter - select subset
let evens = numbers.filter(x => x % 2 === 0);
console.log(evens); // Output: [2, 4]

// reduce - accumulate values
// (accumulator, currentValue) => accumulator + currentValue
let sumAll = numbers.reduce((acc, val) => acc + val, 0);
console.log(sumAll); // Output: 15

// find / findIndex
let firstGT3 = numbers.find(x => x > 3);
let indexGT3 = numbers.findIndex(x => x > 3);
console.log(firstGT3, indexGT3); // Output: 4 3

// some / every
let hasNeg = numbers.some(x => x < 0);
let allPos = numbers.every(x => x > 0);
console.log(hasNeg, allPos); // Output: false true

// sort (sorting numbers requires compare function)
let unsorted = [3, 10, 4, 1, 5];
unsorted.sort((a, b) => a - b); // sorts ascending
console.log(unsorted); // Output: [1, 3, 4, 5, 10]
```

---

### 16. Sets & Maps

ES6 introduced Sets and Maps as alternative collections.

- **Set**: A collection of completely unique values. Duplicates are omitted automatically.
- **Map**: A key-value collection that accepts keys of *any* type (including objects or functions), whereas standard objects coerce keys into strings.

```javascript
// 1. Set
let uniq = new Set([1, 2, 2, 3]);
uniq.add(4);
uniq.add(1); // duplicate is ignored
uniq.delete(2);
console.log(uniq.has(3)); // Output: true
console.log([...uniq]);   // Output: [1, 3, 4] (converted back to array)

// 2. Map
let dict = new Map();
dict.set("apple", 5);
dict.set("orange", 10);
console.log(dict.get("apple")); // Output: 5

dict.delete("orange");
for (let [k, v] of dict) {
    console.log(`${k} -> ${v}`); // Output: apple -> 5
}
```

---

### 17. Date & Time

The `Date` object handles date, time, and timestamp operations.

- **Months**: Note that month indexing in JavaScript begins at `0` (January is `0`, December is `11`).
- **ISO Format**: `toISOString()` returns a standard formatted date string.

```javascript
let now = new Date();
let timestamp = Date.now();             // Milliseconds elapsed since Jan 1, 1970 UTC
let specific = new Date(2023, 0, 31);   // Jan 31, 2023 (Month '0' is January)

console.log(specific.toISOString());    // Output: "2023-01-31T00:00:00.000Z"
```

---

### 18. Error Handling & Custom Errors

Robust applications use error handling to recover gracefully from unexpected failures.

- **`try...catch...finally`**: Code inside `try` runs; if it throws, execution shifts immediately to the `catch` block. The `finally` block always runs regardless of the outcome.
- **Custom Errors**: Create error objects by extending the base `Error` class to implement custom error categorization.

```javascript
try {
    // Deliberate parsing failure
    JSON.parse("{ bad json }");
} catch (err) {
    console.error("Parsing error caught:", err.message);
} finally {
    console.log("Clean-up logic: This block always executes.");
}

// Custom error definition
class ValidationError extends Error {
    constructor(msg) {
        super(msg);
        this.name = "ValidationError";
    }
}

function validate(val) {
    if (!val) {
        throw new ValidationError("Input must not be empty!");
    }
}

try {
    validate("");
} catch (err) {
    console.log(err.name);    // Output: "ValidationError"
    console.log(err.message); // Output: "Input must not be empty!"
}
```

---

### 19. Asynchronous JavaScript: Callbacks, Promises & Async/Await

Asynchronous actions allow execution of tasks in the background without freezing the browser page.

- **Callbacks**: Functions passed as arguments that execute when a task finishes.
- **Promises**: Objects that represent the pending completion (or failure) of an asynchronous task.
- **Async/Await**: Syntactic sugar for Promises, making asynchronous code look clean and read sequentially.

```javascript
// 1. Callback-based timer
setTimeout(() => console.log("Runs after 1 second delay"), 1000);

// 2. Promise wrapper
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
delay(500).then(() => console.log("Promise resolved after 500ms"));

// 3. Async / Await syntax
async function demo() {
    console.log("Starting task...");
    await delay(300); // code pauses here until delay resolves
    console.log("Completed task after 300ms!");
}
demo();

// 4. Fetch API (Browser requests)
async function fetchUser(username) {
    try {
        const response = await fetch(`https://api.github.com/users/${username}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        console.log(`User name: ${data.name}`);
    } catch (err) {
        console.error("Fetch request failed:", err);
    }
}
// fetchUser("octocat"); // Uncomment in browser context to execute
```

---

### 20. ES6 Modules

Modules organize codebase structures by separating code into files.

- **`export`**: Exposes functions or variables from a file.
- **`import`**: Pulls exported functionality from another file.
- **Dynamic Imports (`import()`)**: Imports files dynamically, returning a Promise. Useful for lazy loading.

```javascript
// ----- file: math.js -----
/*
export const add = (a, b) => a + b;
export default function mul(a, b) { return a * b; }
*/

// ----- file: main.js -----
/*
import mul, { add } from "./math.js";
console.log(add(2, 3)); // 5
console.log(mul(2, 3)); // 6

// Dynamic Import (code-splitting)
import("./math.js").then(mod => {
    console.log(mod.add(1, 2)); // 3
});
*/
```

---

### 21. Regular Expressions

Regular Expressions (Regex) define search patterns to validate or modify text.

```javascript
let re = /\d{3}-\d{2}-\d{4}/;        // Match US SSN (Social Security Number) structure
console.log(re.test("123-45-6789")); // Output: true

let matches = "abc123xyz".match(/\d+/); // Find numbers
console.log(matches[0]); // Output: "123"

let replaced = "Hello 2021".replace(/\d+/, "2022");
console.log(replaced); // Output: "Hello 2022"
```

---

### 22. JSON Serialization & Parsing

JSON (JavaScript Object Notation) converts structured data into strings for network transfers or local storage.

- **`JSON.stringify()`**: Serializes an object into a JSON string.
- **`JSON.parse()`**: Parses a JSON string back into a JavaScript object.

```javascript
let obj = { name: "Mike", age: 30, skills: ["JS", "CSS"] };

// Serialization (Object to String)
let json = JSON.stringify(obj);
console.log(json); // Output: '{"name":"Mike","age":30,"skills":["JS","CSS"]}'

// Deserialization (String to Object)
let parsed = JSON.parse(json);
console.log(parsed.name); // Output: "Mike"
```

---

### 23. Symbols

Symbols are primitive values representing unique identifiers. They are typically used to create hidden properties on objects.

```javascript
let sym1 = Symbol("id");
let sym2 = Symbol("id");
console.log(sym1 === sym2); // Output: false (Every symbol is uniquely created)

let user2 = {
    [sym1]: 123, // Symbol-based property key
    name: "Sam"
};
console.log(user2[sym1]); // Output: 123
console.log(Object.keys(user2)); // Output: ["name"] (Symbol key is omitted from standard key loops)
```

---

### 24. Proxies

Proxies wrap target objects to intercept and customize low-level operations (like fetching or setting property values).

```javascript
let target = { a: 1, b: 2 };

let handler = {
    get(obj, prop) {
        console.log(`Interceptor: Reading property '${prop}'`);
        return prop in obj ? obj[prop] : "Property not found!";
    },
    set(obj, prop, value) {
        console.log(`Interceptor: Writing property '${prop}' = ${value}`);
        obj[prop] = value;
        return true; // Indicates set operation succeeded
    }
};

let proxy = new Proxy(target, handler);
console.log(proxy.a); // Logs: Interceptor: Reading property 'a', Output: 1
proxy.c = 3;          // Logs: Interceptor: Writing property 'c' = 3
```

---

### 25. Classic Prototype-Based Inheritance

JavaScript works natively via prototypes. Objects inherit methods and variables from other objects via a prototype chain.

```javascript
// Base animal constructor
function Animal(name) {
    this.name = name;
}
// Add shared function to prototype (conserves memory across instances)
Animal.prototype.say = function() {
    console.log(`${this.name} makes a noise.`);
};

// Derived Dog constructor
function Dog(name) {
    Animal.call(this, name); // Call parent constructor
}

// Map Dog prototype inheritance to Animal prototype
Dog.prototype = Object.create(Animal.prototype);
Dog.prototype.constructor = Dog; // Reset constructor pointer

Dog.prototype.bark = function() {
    console.log(`${this.name} barks!`);
};

let d = new Dog("Rex");
d.say();  // Output: "Rex makes a noise." (inherited)
d.bark(); // Output: "Rex barks!"
```

---

### 26. ES6 Class Syntax

ES6 introduced class declarations, providing a clean syntactic wrapper over standard prototype inheritance.

- **`extends`**: Links subclasses to parent classes.
- **`super()`**: Calls the parent constructor. It must be called in a child constructor before using `this`.
- **`static`**: Defines methods accessed directly on the class itself rather than on instances.

```javascript
class Person {
    constructor(name) {
        this.name = name;
    }
    greet() {
        console.log(`Hi, I am ${this.name}.`);
    }
    static description() {
        console.log("This is a Person class template.");
    }
}

class Employee extends Person {
    constructor(name, role) {
        super(name); // Call base constructor
        this.role = role;
    }
    greet() {
        super.greet(); // Run parent method
        console.log(`My role is ${this.role}.`);
    }
}

Person.description(); // Output: "This is a Person class template."

let emp = new Employee("Lia", "Dev");
emp.greet();
/* Output:
Hi, I am Lia.
My role is Dev.
*/
```

---

### 27. DOM Manipulation & Browser APIs

The Document Object Model (DOM) is the API used by browsers to represent page structures. JavaScript interacts with it to modify visual interfaces.

- **Element Selectors**: `querySelector` (returns first match) and `querySelectorAll` (returns matching list).
- **Event Listeners**: Register interactions (e.g. `click`, `submit`).
- **Event Delegation**: Optimizes applications by registering a single click handler on a parent node instead of separate handlers on individual children.
- **Form Handling**: Calls `e.preventDefault()` to override standard page reloads on submission.
- **LocalStorage**: Simple key-value storage persisted inside the browser.

```javascript
// 1. Selector APIs
let btn = document.querySelector("#myBtn");
let listItems = document.querySelectorAll(".item");

// 2. Creating and inserting nodes
let newDiv = document.createElement("div");
newDiv.textContent = "Hello DOM Element!";
document.body.appendChild(newDiv);

// 3. Event listeners
btn.addEventListener("click", event => {
    console.log("Button clicked:", event);
    btn.disabled = true; // mutate attributes
});

// 4. Event Delegation
document.body.addEventListener("click", event => {
    // Match target using selectors
    if (event.target.matches(".item")) {
        console.log("List item clicked:", event.target.textContent);
    }
});

// 5. Form Handling
let form = document.querySelector("form");
form.addEventListener("submit", event => {
    event.preventDefault(); // Stop page reload
    let data = new FormData(form);
    console.log(Object.fromEntries(data)); // { username: "name", ... }
});

// 6. Local Storage
localStorage.setItem("theme", "dark");
let theme = localStorage.getItem("theme");
console.log("Persisted Theme:", theme); // Output: "dark"
```

---

### 28. Common Mistakes & Best Practices

1. **Upper-Case Constants**: Upper-case names (e.g., `const PI`) are a style convention, not a syntax rule.
2. **`typeof null`**: Remember that `typeof null === "object"`. To verify if a variable is `null`, check strictly: `value === null`.
3. **Avoid Hoisting Confusions**: Avoid declaring variables using `var`. Always prefer `const` (for constants/unchanging references) and `let` (for variables requiring reassignment).
4. **Equality Checks**: Never rely on automatic type conversion with `==`. Always use strict comparison (`===` and `!==`).
5. **Arrow Functions Context**: Remember that arrow functions do not define a context for `this`. Do not use them as object methods if you need to reference the parent object.
6. **Object Cloning**: The spread operator `...` copies object keys shallowly. Nested objects or arrays will stay reference-linked. For independent copies, use `structuredClone(obj)`.
7. **`for...in` Traps**: `for...in` loops iterate over *all* enumerable properties, including inherited prototype keys. Use `for...of` for lists or check `Object.hasOwn(obj, prop)` in loops.
8. **Array Sorting**: Running `.sort()` without passing an arguments function sorts values alphabetically. Always provide `(a, b) => a - b` for ascending numerical sorting.
9. **`Number.isNaN` Safety**: Always prefer `Number.isNaN()` over the global `isNaN()` helper.
10. **Promise Rejections**: Always handle promise rejections. Uncaught promise failures will throw warnings and may crash applications in future JavaScript environments.