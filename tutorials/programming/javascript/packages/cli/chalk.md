# 🎨 Chalk + Bun – A Beginner‑Friendly Tutorial  
*(All code is runnable with the **Bun** JavaScript runtime. Every example points to the official Chalk documentation.)*

---  

## 1️⃣  Prerequisites – Install Bun  

```bash
# macOS / Linux – use the official installer
curl -fsSL https://bun.sh/install | bash
# Windows – use PowerShell
iwr https://bun.sh/install | iex
```

Bun’s docs: <https://bun.com/docs/installation>【5†L0-L5】  

> **Tip:** After installation, `bun --version` should print a version number (e.g. `1.1.26`).

---

## 2️⃣  Create a new project folder  

```bash
mkdir chalk-demo && cd chalk-demo
bun init        # creates a package.json and a starter index.mjs
```

`bun init` is described in the CLI docs: <https://bun.com/docs/cli/init>【5†L7-L10】  

---

## 3️⃣  Add Chalk as a dependency  

```bash
bun add chalk   # equivalent to npm install chalk
```

Official Bun “add” page: <https://bun.com/docs/cli/add>【9†L0-L13】  

---

## 4️⃣  Basic Usage – “Hello, world!”  

Create **`index.mjs`** (ESM is required – Chalk 5 ships as ESM).

```js
// index.mjs
import chalk from "chalk";

console.log(chalk.blue("Hello, world!"));
```

Run it:

```bash
bun run index.mjs
```

*Chalk “Usage” docs*: <https://documentation.page/github/chalk/chalk#usage>【1†L35-L42】  

---

## 5️⃣  Text Colors  

| Example | Code | Output |
|--------|------|--------|
| Red text | `chalk.red("Error!")` | <span style="color:red">Error!</span> |
| Bright cyan | `chalk.cyanBright("Info")` | <span style="color:#00ffff">Info</span> |
| Custom HEX | `chalk.hex('#ff8800')('Orange')` | <span style="color:#ff8800">Orange</span> |

```js
console.log(chalk.red("Error!"));
console.log(chalk.cyanBright("Info"));
console.log(chalk.hex('#ff8800')('Orange'));
```

*Color reference*: <https://documentation.page/github/chalk/chalk#colors>【7†L194-L212】  

---

## 6️⃣  Background Colors  

```js
console.log(chalk.bgGreen.black('  SUCCESS  '));
console.log(chalk.bgRedBright.white('  FAILURE  '));
```

*Background docs*: <https://documentation.page/github/chalk/chalk#background-colors>【7†L213-L230】  

---

## 7️⃣  Modifiers (Bold, Italic, Underline, …)  

```js
console.log(chalk.bold('Bold text'));
console.log(chalk.italic('Italic text'));          // not widely supported
console.log(chalk.underline('Underlined'));
console.log(chalk.inverse('Inverse colors'));
console.log(chalk.strikethrough('Strikethrough'));
```

*Modifiers list*: <https://documentation.page/github/chalk/chalk#styles>【7†L177-L190】  

---

## 8️⃣  Chaining & Nesting Styles  

```js
// Chain many styles
console.log(
  chalk.blue.bgYellow.bold.underline('Chained!')
);

// Nest a style inside another
console.log(
  chalk.red(
    'Error: ',
    chalk.underline.bgWhite.black('File not found')
  )
);
```

The chaining API is explained in the “Usage” section: <https://documentation.page/github/chalk/chalk#usage>【1†L45-L56】  

---

## 9️⃣  256‑Color & Truecolor (RGB / HEX)  

```js
// 256‑color index
console.log(chalk.ansi256(202)('256‑color orange'));

// Truecolor (RGB)
console.log(chalk.rgb(123, 45, 67).bold('RGB text'));

// Truecolor background
console.log(chalk.bgHex('#00ff00').black('Green background'));
```

*256 & Truecolor docs*: <https://documentation.page/github/chalk/chalk#256-and-truecolor-color-support>【7†L232-L250】  

---

## 🔟  Creating Reusable Themes  

```js
// theme.mjs
import chalk from "chalk";

export const error   = chalk.bold.red;
export const warning = chalk.hex('#FFA500'); // orange
export const success = chalk.greenBright;

// usage in another file
import {error, warning, success} from "./theme.mjs";

console.log(error('❌  Something went wrong'));
console.log(warning('⚠️  Watch out!'));
console.log(success('✅  All good'));
```

The idea of “defining your own themes” is shown in the README examples: <https://documentation.page/github/chalk/chalk#usage>【1†L81-L90】  

---

## 1️⃣1️⃣  Using Chalk with `console.log` Substitutions  

```js
const name = 'Sindre';
console.log(chalk.green('Hello %s!'), name);   // → Hello Sindre!
```

See the “string substitution” example in the docs: <https://documentation.page/github/chalk/chalk#usage>【1†L92-L100】  

---

## 1️⃣2️⃣  Controlling Color Support Level  

Sometimes you want to force‑disable colors (e.g., CI logs).  

```js
import { Chalk } from 'chalk';

// Force all colors off
const noColor = new Chalk({level: 0});

console.log(noColor.red('This will be plain text'));
```

*Level API*: <https://documentation.page/github/chalk/chalk#chalklevel>【1†L116-L124】  

---

## 1️⃣3️⃣  Full Example – Put It All Together  

```js
// index.mjs
import chalk from "chalk";

console.log(chalk.yellow.bold('=== Chalk Demo with Bun ==='));

console.log(chalk.blue('Basic text'));
console.log(chalk.red('Error!'));
console.log(chalk.greenBright('Success!'));

console.log(chalk.bgMagenta.white('  NOTICE  '));

console.log(chalk.bold.underline('Important').italic(' message'));

console.log(
  chalk.rgb(255, 165, 0).bgBlack('Truecolor orange on black')
);

console.log(chalk.ansi256(202)('256‑color orange'));

console.log(
  chalk.red('Nested ', chalk.underline.bgWhite.black('styles'), ' example')
);
```

Run:

```bash
bun run index.mjs
```

You should see a colorful CLI output demonstrating everything covered.

---

## 📚  Quick Reference Links  

| Topic | Official Chalk Docs |
|-------|---------------------|
| Install | <https://documentation.page/github/chalk/chalk#install> |
| Usage / Import | <https://documentation.page/github/chalk/chalk#usage> |
| Styles & Modifiers | <https://documentation.page/github/chalk/chalk#styles> |
| Colors | <https://documentation.page/github/chalk/chalk#colors> |
| Background Colors | <https://documentation.page/github/chalk/chalk#background-colors> |
| 256 / Truecolor | <https://documentation.page/github/chalk/chalk#256-and-truecolor-color-support> |
| Custom Themes | Same “Usage” page (theme example) |
| Color Level | <https://documentation.page/github/chalk/chalk#chalklevel> |

---

## 🚀  What’s Next?  

* Build a full‑featured CLI with **commander** or **yargs** and style its output with Chalk.  
* Use **Bun’s `bun test`** to write unit tests for your CLI, asserting colored output.  
* Explore **`chalk-template`** for template‑literal styling (see the Chalk “Related” section).  

Enjoy styling your terminal apps with the speed of Bun and the elegance of Chalk! 🎉  