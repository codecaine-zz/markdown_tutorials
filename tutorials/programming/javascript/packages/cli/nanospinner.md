**🚀 Quick‑Start – Using **`nanospinner`** (tiny asynchronous spinners) with the **Bun** runtime**  

*(All the snippets below run with plain JavaScript or TypeScript – no extra bundler required.)*  

---  

## 1️⃣ What is **`nanospinner`**?  

`nanospinner` is a minimal (~ 1 KB) library that shows a sleek, animated spinner while you await asynchronous work. It works everywhere Node‑compatible runtimes run – Bun included.  

Official repository & full README: **GitHub → helloroman/nanospinner** – see the *“Usage”* and *API* sections for every method 【official‑repo】.  
npm page (install command, version badge, quick examples) <https://www.npmjs.com/package/nanospinner>.

---  

## 2️⃣ Prerequisites  

1. **Bun** (≥ 1.0). Install it – <https://bun.com/docs/installation>.  
2. A folder for the demo:

```bash
mkdir bun‑nanospinner-demo && cd bun‑nanospinner-demo
bun init                     # creates a minimal package.json
```

---  

## 3️⃣ Install `nanospinner` with Bun  

```bash
bun add nanospinner          # adds the dependency to package.json
```

(You can pin a version (`bun add nanospinner@0.1.0`) or install as a dev‑dependency with `--dev` – see **`bun add`** docs <https://bun.com/docs/cli/add>.)

---  

## 4️⃣ The “Hello World” spinner  

Create **`hello.js`** (or `hello.ts`):

```js
// hello.js
import { createSpinner } from 'nanospinner';

async function main() {
  const spinner = createSpinner('Loading…').start();   // ← start the animation

  // Simulate async work – replace with your real promise
  await new Promise(r => setTimeout(r, 1500));

  spinner.success({ text: 'All done! 🎉' });           // ← green check + final text
}

main();
```

Run it:

```bash
bun run hello.js          # or simply: bun hello.js
```

You’ll see a tiny spinner that turns into a green check‑mark after 1.5 s.

---  

## 5️⃣ Full API cheat‑sheet  

| Method / Property | What it does | Example |
|-------------------|--------------|---------|
| `createSpinner(text)` | Returns a `Spinner` instance with the supplied initial text. | `const s = createSpinner('Fetching…')` |
| `.start()` | Starts the animation (returns the same instance). | `s.start()` |
| `.stop()` | Stops and clears the spinner without any final symbol. | `s.stop()` |
| `.success(options?)` | Stops the spinner, shows a **green ✔** and persists the text. | `s.success({ text: 'Nice!' })` |
| `.error(options?)` | Stops the spinner, shows a **red ✖** and persists the text. | `s.error({ text: 'Oops' })` |
| `.warn(options?)` | Stops the spinner, shows a **yellow ⚠**. | `s.warn({ text: 'Almost' })` |
| `.update(options?)` | Change the spinner’s text (or color) while it’s running. | `s.update({ text: 'Halfway…' })` |
| `options` (for creation) | `text?: string`, `color?: string` (any `chalk` colour name) | `createSpinner({text:'Hi', color:'magenta'})` |

All of the above are listed in the **API** section of the README 【official‑repo】.

---  

## 6️⃣ A realistic async example (download simulation)

```js
// download-demo.js
import { createSpinner } from 'nanospinner';
import fs from 'fs/promises';
import https from 'https';

function download(url, dest) {
  return new Promise((resolve, reject) => {
    const file = fs.open(dest, 'w');
    https.get(url, async response => {
      const stream = response.pipe((await file).createWriteStream());
      stream.on('finish', resolve);
      stream.on('error', reject);
    });
  });
}

async function main() {
  const spinner = createSpinner('Downloading image…').start();

  try {
    await download('https://picsum.photos/200/300', 'photo.jpg');
    spinner.success({ text: '✅  Image saved as photo.jpg' });
  } catch (e) {
    spinner.error({ text: `❌  Failed: ${e.message}` });
  }
}

main();
```

Run:

```bash
bun run download-demo.js
```

While the download proceeds, the spinner animates; on success you get a green check, on failure a red cross.

---  

## 7️⃣ Using **Bun’s watch mode** while you iterate

If you are tweaking the UI or the async logic, let Bun auto‑restart the script:

```bash
bun --watch run download-demo.js
```

*(The `--watch` flag must appear directly after `bun` – see Bun docs <https://bun.com/docs/cli/run#watch>.)*  

---  

## 8️⃣ Combining **`nanospinner`** with other Bun‑compatible libraries  

Because `nanospinner` returns a plain object, you can mix it with logging tools (`consola`, `ora`) or colour libraries (`chalk`). Example:

```js
// combo.js
import { createSpinner } from 'nanospinner';
import { consola } from 'consola';
import chalk from 'chalk';

async function main() {
  const s = createSpinner(chalk.blue('Preparing…')).start();
  consola.info('Doing some heavy work...');
  await new Promise(r => setTimeout(r, 1200));
  s.success({ text: chalk.green('Ready!') });
  consola.success('All set.');
}

main();
```

Run with `bun run combo.js` – you’ll see a spinner, `consola` logs, and coloured text together.

---  

## 9️⃣ Bundling the CLI into a **single binary**  

`nanospinner` is pure JavaScript, so you can package your CLI with `bun build`:

```bash
bun build cli.js --compile --outfile my-cli
```

The resulting `my-cli` binary contains the spinner code and runs on any machine without a `node_modules` folder.

---  

## 🔟 Where to find the **official documentation**  

| Item | Link |
|------|------|
| Repository & full README (install, API, examples) | <https://github.com/helloroman/nanospinner> |
| npm package page (latest version, install command) | <https://www.npmjs.com/package/nanospinner> |
| Bun package manager (`bun add`) | <https://bun.com/docs/cli/add> |
| Running scripts & watch mode (`bun run`) | <https://bun.com/docs/cli/run> |

---  

## ✅ TL;DR (the 5‑step cheat sheet)

1. **Install** `bun add nanospinner`  
2. **Import** `import { createSpinner } from 'nanospinner'`  
3. **Create & start** `const s = createSpinner('Working…').start()`  
4. **When the async task finishes** → `s.success({text:'Done!'})` *or* `s.error({text:'Failed'})`  
5. **Run** `bun run your‑script.js` (or `bun --watch run …` while developing).  

You now have a tiny, ultra‑fast spinner that works out‑of‑the‑box with Bun – perfect for any CLI that needs a visual “loading…” cue. Happy spinning! 🚀  