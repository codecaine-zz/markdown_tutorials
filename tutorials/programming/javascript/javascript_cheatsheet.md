```javascript
/*=====================================================================
  The Modern JavaScript Tutorial –  All concepts illustrated by code.
  Every topic from https://javascript.info/ is represented by a small,
  self‑contained example written **inside comments**.
  Feel free to copy‑paste any block into a console or a file to run it.
=====================================================================*/

/*--------------------------------------------------------------
  0️⃣  The very first line – strict mode (recommended everywhere)
--------------------------------------------------------------*/
// "use strict";               // enables strict mode for the whole script

/*--------------------------------------------------------------
  1️⃣  VARIABLES, CONSTANTS & HOISTING
--------------------------------------------------------------*/
// let – block‑scoped, mutable
let age = 25;
age = 26;                     // OK

// const – block‑scoped, immutable binding (value can be an object)
const PI = 3.14159;           // good practice, but uppercase is *optional*
const user = { name: "Ana" };
user.name = "Bob";            // allowed – the object itself is mutable

// var – function‑scoped, hoisted (avoid in modern code)
var legacy = "I exist before the line";
function hoistDemo() {
    console.log(legacy);      // undefined (hoisted declaration, not init)
    var legacy = "I am hoisted!";
}
hoistDemo();

/*--------------------------------------------------------------
  2️⃣  DATA TYPES & typeof
--------------------------------------------------------------*/
let str = "hello";
let num = 42;
let bool = true;
let undef;                    // undefined
let nul = null;               // object (historical bug)
let sym = Symbol("id");
let big = 10n;                // BigInt

console.log(typeof str);    // "string"
console.log(typeof num);    // "number"
console.log(typeof bool);   // "boolean"
console.log(typeof undef);  // "undefined"
console.log(typeof nul);    // "object"   <-- known quirk, treat null specially
console.log(typeof sym);    // "symbol"
console.log(typeof big);    // "bigint"

/*--------------------------------------------------------------
  3️⃣  STRING QUOTES & TEMPLATE LITERALS
--------------------------------------------------------------*/
let single = 'single quotes';
let double = "double quotes";
let backticks = `template ${num} + ${age}`; // expression interpolation
let multiLine = `First line
Second line`;                 // preserves new‑lines

/*--------------------------------------------------------------
  4️⃣  NUMBERS, NaN & Infinity
--------------------------------------------------------------*/
let nan = 0/0;                // NaN – not a number
let inf = 1/0;                // Infinity
let negInf = -1/0;            // -Infinity

console.log(Number.isNaN(nan));   // true
console.log(isFinite(inf));       // false

/*--------------------------------------------------------------
  5️⃣  TYPE CONVERSIONS
--------------------------------------------------------------*/
// → String
String(123)          // "123"
String(false)        // "false"
String(null)         // "null"
String(undefined)    // "undefined"

// → Number
Number("45")         // 45
Number("")           // 0
Number(null)         // 0
Number(undefined)    // NaN
+ "12"               // unary plus → 12

// → Boolean
Boolean(0)           // false
Boolean("")          // false
Boolean(null)        // false
Boolean("text")      // true

/*--------------------------------------------------------------
  6️⃣  BASIC OPERATORS
--------------------------------------------------------------*/
// Arithmetic
let sum = 5 + 2;    // 7
let diff = 5 - 2;   // 3
let prod = 5 * 2;   // 10
let div = 5 / 2;    // 2.5
let mod = 5 % 2;    // 1
let pow = 2 ** 3;   // 8

// Increment / Decrement (postfix vs prefix)
let i = 1;
i++;                // i = 2  (post)
++i;                // i = 3  (pre)
let a = i++;        // a = 3, i = 4
let b = ++i;        // b = 5, i = 5

// Assignment (returns the assigned value)
let x = (y = 3);    // y = 3, x = 3

/*--------------------------------------------------------------
  7️⃣  COMPARISONS & COERCION
--------------------------------------------------------------*/
console.log(5 > 4);            // true
console.log(5 == "5");         // true  (type coercion)
console.log(5 === "5");        // false (strict)
console.log(null == 0);        // false
console.log(null >= 0);        // true   <-- special case, avoid with ===

// Nullish coalescing (??) – works only with null/undefined
let saved = userInput ?? "default";

// Optional chaining (?.) – safe navigation
let city = user?.address?.city;

/*--------------------------------------------------------------
  8️⃣  LOGICAL OPERATORS
--------------------------------------------------------------*/
// OR (||) – returns first truthy operand
let result = "" || 0 || "hello";   // "hello"

// AND (&&) – returns first falsy operand
let result2 = "foo" && null && 5;  // null

/*--------------------------------------------------------------
  9️⃣  CONTROL FLOW – IF / SWITCH / TERNARY
--------------------------------------------------------------*/
let score = 85;

// if … else
if (score >= 90) {
    console.log("A");
} else if (score >= 80) {
    console.log("B");
} else {
    console.log("C");
}

// Ternary operator
let grade = (score >= 90) ? "A" : (score >= 80) ? "B" : "C";

// switch (strict comparison)
switch (grade) {
    case "A":
        console.log("Excellent");
        break;
    case "B":
        console.log("Good");
        break;
    default:
        console.log("Okay");
}

/*--------------------------------------------------------------
  🔟  LOOPS
--------------------------------------------------------------*/
// while
let n = 0;
while (n < 3) {
    console.log("while:", n);
    n++;
}

// do … while
let m = 0;
do {
    console.log("do:", m);
    m++;
} while (m < 3);

// for (classic)
for (let i = 0; i < 3; i++) {
    console.log("for:", i);
}

// for…of (iterates over iterable values)
let arr = [10, 20, 30];
for (let val of arr) console.log("of:", val);

// for…in (iterates over object keys)
let obj = {a:1, b:2};
for (let key in obj) console.log("in:", key, "=", obj[key]);

// break / continue
for (let i = 0; i < 5; i++) {
    if (i === 2) continue;   // skip 2
    if (i === 4) break;      // stop loop at 4
    console.log(i);
}

/*--------------------------------------------------------------
  1️⃣1️⃣  FUNCTIONS – DECLARATIONS, EXPRESSIONS, ARROWS
--------------------------------------------------------------*/
// Declaration – hoisted
function sum(a, b) {
    return a + b;
}
console.log(sum(2,3));

// Expression – not hoisted
const mul = function(a, b) {
    return a * b;
};
console.log(mul(2,3));

// Arrow – concise syntax, lexical this
const pow = (x, n) => x ** n;
console.log(pow(2,3));

// Default parameters
function greet(name = "Guest") {
    console.log(`Hello, ${name}`);
}
greet();                // Hello, Guest

// Rest parameters
function concat(...parts) {
    return parts.join("-");
}
console.log(concat("a","b","c")); // a-b-c

/*--------------------------------------------------------------
  1️⃣2️⃣  THIS & BINDING
--------------------------------------------------------------*/
const person = {
    name: "Tom",
    sayHi() {
        console.log(this.name);
    },
    // Arrow functions do NOT have their own this
    shout: () => console.log(this.name)   // undefined in strict mode
};
person.sayHi();   // Tom
person.shout();  // undefined

// Explicit binding
function show() { console.log(this.id); }
const obj1 = {id:1};
show.call(obj1);          // 1
show.apply(obj1);         // 1
const bound = show.bind({id:2});
bound();                  // 2

/*--------------------------------------------------------------
  1️⃣3️⃣  OBJECTS – CREATION, METHODS, DESCRIPTORS
--------------------------------------------------------------*/
// Literal
let car = {
    brand: "Toyota",
    speed: 0,
    accelerate(delta) { this.speed += delta; }
};
car.accelerate(50);
console.log(car.speed);   // 50

// Constructor (new)
function User(name) {
    this.name = name;
}
let u = new User("Anna");

// Property descriptor (defineProperty)
Object.defineProperty(car, "speed", {
    writable: false,       // read‑only
    configurable: true,
    enumerable: true,
    value: car.speed
});
// car.speed = 100;        // throws in strict mode

// Getters / Setters
let rectangle = {
    width: 10,
    height: 20,
    get area() { return this.width * this.height; },
    set area(v) { console.log("area is read‑only"); }
};
console.log(rectangle.area);   // 200

/*--------------------------------------------------------------
  1️⃣4️⃣  OBJECT DESTRUCTURING & SPREAD
--------------------------------------------------------------*/
let options = {title:"Menu", width:100, height:200};
let {title, width, ...rest} = options;
console.log(title, width, rest); // "Menu" 100 {height:200}

// Spread (shallow copy / merge)
let objA = {a:1, b:2};
let objB = {b:3, c:4};
let merged = {...objA, ...objB}; // {a:1,b:3,c:4}

/*--------------------------------------------------------------
  1️⃣5️⃣  ARRAYS – BASIC & HIGHER‑ORDER METHODS
--------------------------------------------------------------*/
let numbers = [1,2,3,4,5];

// map – transform
let squares = numbers.map(x => x*x); // [1,4,9,16,25]

// filter – select
let evens = numbers.filter(x => x%2===0); // [2,4]

// reduce – accumulate
let sumAll = numbers.reduce((acc, val) => acc + val, 0); // 15

// find / findIndex
let firstGT3 = numbers.find(x => x>3);        // 4
let indexGT3 = numbers.findIndex(x => x>3);   // 3

// some / every
let hasNeg = numbers.some(x => x<0);          // false
let allPos = numbers.every(x => x>0);         // true

// sort (in‑place)
let unsorted = [3,1,4,1,5];
unsorted.sort((a,b)=>a-b);   // [1,1,3,4,5]

/*--------------------------------------------------------------
  1️⃣6️⃣  SETS & MAPS (collection data structures)
--------------------------------------------------------------*/
// Set – unique values
let uniq = new Set([1,2,2,3]);
uniq.add(4);
uniq.delete(2);
console.log(uniq.has(3));      // true
console.log([...uniq]);        // [1,3,4]

// Map – key ⇒ value
let dict = new Map();
dict.set("apple", 5);
dict.set("orange", 10);
console.log(dict.get("apple")); // 5
dict.delete("orange");
for (let [k,v] of dict) console.log(k, v);

/*--------------------------------------------------------------
  1️⃣7️⃣  DATE & TIME
--------------------------------------------------------------*/
let now = new Date();
let timestamp = Date.now();             // milliseconds since 1970‑01‑01
let specific = new Date(2023, 0, 31);   // Jan 31, 2023 (month 0‑based)
console.log(specific.toISOString());

/*--------------------------------------------------------------
  1️⃣8️⃣  ERROR HANDLING
--------------------------------------------------------------*/
try {
    // code that may throw
    JSON.parse("{ bad json }");
} catch (err) {
    console.error("Parsing error:", err.message);
} finally {
    console.log("Always runs");
}

// Custom error
class ValidationError extends Error {
    constructor(msg) { super(msg); this.name = "ValidationError"; }
}
function test(val) {
    if (!val) throw new ValidationError("Value required");
}

/*--------------------------------------------------------------
  1️⃣9️⃣  ASYNCHRONOUS CODE – CALLBACKS, PROMISES, async/await
--------------------------------------------------------------*/
// Callback (old school)
setTimeout(() => console.log("delayed 1s"), 1000);

// Promise
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
delay(500).then(() => console.log("half second"));

// async / await (syntactic sugar)
async function demo() {
    console.log("start");
    await delay(300);
    console.log("after 300ms");
}
demo();

// Fetch API (browser) – simplified example
// fetch("https://api.github.com/users/octocat")
//   .then(r => r.json())
//   .then(data => console.log(data))
//   .catch(err => console.error(err));

/*--------------------------------------------------------------
  2️⃣0️⃣  MODULES (ES6)
--------------------------------------------------------------*/
// ----- file: math.js -----
/*
export const add = (a,b) => a+b;
export default function mul(a,b) { return a*b; }
*/

// ----- file: main.js -----
/*
import mul, {add} from "./math.js";
console.log(add(2,3)); // 5
console.log(mul(2,3)); // 6

// dynamic import (code‑splitting)
import("./math.js").then(mod => console.log(mod.add(1,2)));
*/

/*--------------------------------------------------------------
  2️⃣1️⃣  REGULAR EXPRESSIONS
--------------------------------------------------------------*/
let re = /\d{3}-\d{2}-\d{4}/;        // US SSN pattern
console.log(re.test("123-45-6789")); // true
let matches = "abc123".match(/\d+/); // ["123"]
let replaced = "Hello 2021".replace(/\d+/, "2022"); // "Hello 2022"

/*--------------------------------------------------------------
  2️⃣2️⃣  JSON – serialization & parsing
--------------------------------------------------------------*/
let obj = {name:"Mike", age:30, nested:{a:1}};
let json = JSON.stringify(obj);
let parsed = JSON.parse(json);
console.log(json, parsed);

/*--------------------------------------------------------------
  2️⃣3️⃣  SYMBOLS – unique identifiers
--------------------------------------------------------------*/
let sym1 = Symbol("id");
let sym2 = Symbol("id");
console.log(sym1 === sym2); // false

let user2 = {
    [sym1]: 123,
    name: "Sam"
};
console.log(user2[sym1]); // 123

/*--------------------------------------------------------------
  2️⃣4️⃣  PROXIES – meta‑programming
--------------------------------------------------------------*/
let target = {a:1, b:2};
let handler = {
    get(obj, prop) {
        console.log(`Getting ${prop}`);
        return obj[prop];
    },
    set(obj, prop, value) {
        console.log(`Setting ${prop} = ${value}`);
        obj[prop] = value;
        return true;
    }
};
let proxy = new Proxy(target, handler);
proxy.a;          // logs "Getting a"
proxy.c = 3;      // logs "Setting c = 3"

/*--------------------------------------------------------------
  2️⃣5️⃣  CLASSIC INHERITANCE – prototype chain
--------------------------------------------------------------*/
function Animal(name) {
    this.name = name;
}
Animal.prototype.say = function() {
    console.log(`${this.name} makes a noise`);
};
function Dog(name) {
    Animal.call(this, name); // super constructor
}
Dog.prototype = Object.create(Animal.prototype);
Dog.prototype.constructor = Dog;
Dog.prototype.bark = function() {
    console.log(`${this.name} barks`);
};
let d = new Dog("Rex");
d.say();   // Rex makes a noise
d.bark();  // Rex barks

/*--------------------------------------------------------------
  2️⃣6️⃣  CLASS SYNTAX (ES6)
--------------------------------------------------------------*/
class Person {
    constructor(name) {
        this.name = name;
    }
    greet() { console.log(`Hi, I am ${this.name}`); }

    static description() { console.log("Person class"); }
}
class Employee extends Person {
    constructor(name, role) {
        super(name);
        this.role = role;
    }
    greet() {                     // method overriding
        super.greet();
        console.log(`My role is ${this.role}`);
    }
}
Person.description(); // Person class
let emp = new Employee("Lia", "Dev");
emp.greet();
/*
Hi, I am Lia
My role is Dev
*/

/*--------------------------------------------------------------
  2️⃣7️⃣  DOM MANIPULATION (browser only)
--------------------------------------------------------------*/
// Selecting elements
let btn = document.querySelector("#myBtn");
let listItems = document.querySelectorAll(".item");

// Creating / inserting nodes
let newDiv = document.createElement("div");
newDiv.textContent = "Hello DOM";
document.body.appendChild(newDiv);

// Event handling
btn.addEventListener("click", e => {
    console.log("Button clicked", e);
    btn.disabled = true;      // change attribute
});

// Event delegation (single listener for many children)
document.body.addEventListener("click", e => {
    if (e.target.matches(".item")) {
        console.log("Item clicked:", e.target.textContent);
    }
});

// Form handling
let form = document.querySelector("form");
form.addEventListener("submit", e => {
    e.preventDefault();                     // stop page reload
    let data = new FormData(form);
    console.log(Object.fromEntries(data));  // log {field1: "value", …}
});

// Local storage (simple persistence)
localStorage.setItem("theme", "dark");
let theme = localStorage.getItem("theme");

/*--------------------------------------------------------------
  2️⃣8️⃣  CORRECTIONS & COMMON MISTAKES (from the tutorial)
--------------------------------------------------------------*/
// 1. Upper‑case const names are a *convention*, not a rule.
// 2. typeof null === "object" – treat null separately.
// 3. Beware of var hoisting; always prefer let/const.
// 4. When using == with objects, never rely on automatic conversion – use ===.
// 5. In arrow functions, `this` does NOT refer to the calling object.
// 6. Spread (`...`) on objects performs a shallow copy – nested objects stay linked.
// 7. `for…in` iterates over *enumerable* properties, including inherited ones.
//    Use `Object.hasOwnProperty` check or `Object.keys()` to avoid surprises.
// 8. `Array.sort` without a compare function sorts strings lexicographically.
//    Always provide `(a,b)=>a-b` for numeric sorting.
// 9. `Number.isNaN` is safer than the global `isNaN` (the latter coerces first).
//10. Always handle promise rejections (`catch` or try/catch with await).

/*=====================================================================
  END OF TUTORIAL – all concepts are demonstrated in the code above.
  Copy any block into a REPL, browser console, or a .js file to run.
=====================================================================*/
```