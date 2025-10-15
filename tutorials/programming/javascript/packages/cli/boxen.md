**🚀 Quick‑Start – Using **`boxen`** (pretty terminal boxes) with the **Bun** JavaScript runtime**  

*(All code works with plain JavaScript or TypeScript – no extra build step needed.)*  

---  

## 1️⃣ What is **`boxen`**?  

`boxen` is a tiny utility that draws a box (border, padding, margin, background colour, etc.) around any string you pass to it. It’s perfect for highlighting headings, errors, or any text you want to stand out in the terminal.  

Official repository & README: **GitHub → vercel/boxen** – see the *“API”* section for a full list of options 【official‑repo】.  
npm page (install command, version badge, quick examples) <https://www.npmjs.com/package/boxen>.

---  

## 2️⃣ Prerequisites  

1. **Bun** (≥ 1.0). Install it – <https://bun.com/docs/installation>.  
2. A folder for the demo:

```bash
mkdir bun‑boxen-demo && cd bun‑boxen-demo
bun init                     # creates a minimal package.json
```

---  

## 3️⃣ Install `boxen` with Bun  

```bash
bun add boxen                # adds the dependency to package.json
```

(You can pin a version, e.g. `bun add boxen@8.0.0`, or add it as a dev‑dependency with `--dev` – see **`bun add`** docs <https://bun.com/docs/cli/add>.)

---  

## 4️⃣ The simplest box  

Create **`hello.js`** (or `hello.ts`):

```js
// hello.js
import boxen from 'boxen';

const message = boxen('Hello, world!', {
  padding: 1,
  margin: 1,
  borderStyle: 'round',
});

console.log(message);
```

Run it:

```bash
bun run hello.js          # or simply: bun hello.js
```

You’ll see a nicely‑rounded box around the text:

```
┌───────────────┐
│ Hello, world! │
└───────────────┘
```

---  

## 5️⃣ All the **customisation options**  

`boxen` accepts an options object. Below is a reference cheat‑sheet (the same options are documented in the README under **“Options”**).

| Option | Type | Default | What it does |
|--------|------|---------|--------------|
| `padding` | `number | object` | `0` | Space inside the box. Can be `{top, right, bottom, left}`. |
| `margin`  | `number | object` | `0` | Space outside the box. Same shape as `padding`. |
| `borderStyle` | `string` | `'single'` | `'single'`, `'double'`, `'round'`, `'bold'`, `'singleDouble'`, `'doubleSingle'`, `'classic'`. |
| `borderColor` | `string` | `undefined` | Any colour name supported by **chalk** (e.g., `'red'`, `'cyan'`). |
| `backgroundColor` | `string` | `undefined` | Background colour of the whole box. |
| `align` | `'left' | 'center' | 'right'` | `'left'` | Horizontal alignment of the inner text. |
| `float` | `'left' | 'center' | 'right'` | `'left'` | Alignment of the **box** itself when printed on a line with other text. |
| `title` | `string` | `undefined` | Adds a title (shown on the top border). |
| `titleAlignment` | `'left' | 'center' | 'right'` | `'center'` | Position of the title. |
| `titleColor` | `string` | `undefined` | Colour of the title text. |

---  

## 6️⃣ A richer example (colours, title, padding & margin)

```js
// fancy-box.js
import boxen from 'boxen';
import chalk from 'chalk';               // optional – colourful text inside

const title = chalk.bold.yellow('🚀  Deployment Summary');
const inner = chalk.green('✓ Build succeeded\n') + chalk.red('✗ Tests failed');

const output = boxen(inner, {
  title,
  titleAlignment: 'center',
  titleColor: 'magenta',
  borderStyle: 'double',
  borderColor: 'cyan',
  backgroundColor: '#0d1117',   // dark GitHub‑style bg
  padding: { top: 1, bottom: 1, left: 2, right: 2 },
  margin: 1,
  align: 'left',
});

console.log(output);
```

Run it:

```bash
bun run fancy-box.js
```

The result is a double‑border box with a coloured title, coloured inner text, generous padding, and a dark background.

---  

## 7️⃣ Using **`boxen`** together with other Bun‑compatible libraries  

Because Bun can import **ESM** modules directly, you can combine `boxen` with any other logger (e.g., `consola`, `ora`, or `chalk`). Here’s a common pattern for a CLI that prints a header box and then logs regular output:

```js
// cli.js
#!/usr/bin/env bun
import boxen from 'boxen';
import { consola } from 'consola';
import chalk from 'chalk';

function header() {
  const txt = chalk.bold.cyan('My Awesome CLI');
  return boxen(txt, {
    borderStyle: 'round',
    borderColor: 'cyan',
    padding: 1,
    margin: { top: 0, bottom: 1 },
    align: 'center',
  });
}

async function main() {
  console.log(header());

  consola.info('Starting process…');
  // pretend we do something async
  await new Promise(r => setTimeout(r, 800));

  consola.success('All done 🎉');
}

main();
```

Make it executable (`chmod +x cli.js`) and run `./cli.js`. The header appears as a boxed title, then the `consola` messages follow underneath.

---  

## 8️⃣ Using **Bun’s watch mode** while tweaking boxes  

When you are iterating on the design of your boxes, let Bun re‑run the script automatically:

```bash
bun --watch run fancy-box.js
```

*(The `--watch` flag must be placed directly after `bun` – see Bun docs <https://bun.com/docs/cli/run#watch>.)*  

---  

## 9️⃣ **Bundling** the script into a single binary  

`boxen` is pure JavaScript (no native addons), so you can compile your CLI into a standalone executable with `bun build`:

```bash
bun build cli.js --compile --outfile my-cli
```

The produced `my-cli` binary contains the box‑drawing code and runs on any machine without needing `node_modules`.

---  

## 🔟 Where to find the **official documentation**  

| Item | Link |
|------|------|
| Repository & full README (install, API, options, examples) | <https://github.com/vercel/boxen> |
| npm package page (latest version, install command) | <https://www.npmjs.com/package/boxen> |
| Bun package manager (`bun add`) | <https://bun.com/docs/cli/add> |
| Running scripts & watch mode (`bun run`) | <https://bun.com/docs/cli/run> |

---  

## ✅ TL;DR (the 5‑step cheat sheet)

1. **Install** `bun add boxen` (optionally `chalk` for colours).  
2. **Import** `import boxen from 'boxen'`.  
3. **Wrap** any string with `boxen(str, { …options })`.  
4. **Print** `console.log(boxedString)`.  
5. **Run** `bun run your‑script.js` (or `bun --watch run …` while developing).  

You now have a tiny, dependency‑free way to make your terminal output look *beautiful* on any Bun‑powered CLI. Happy boxing! 🚀  