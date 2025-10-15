**🚀 Quick‑Start – Using **`@clack/prompts`** (tiny, beautiful CLI prompts) with the **Bun** runtime**  

*(All the code works with plain JavaScript or TypeScript – no extra bundler needed.)*  

---  

## 1️⃣ What is **`@clack/prompts`**?  

`@clack/prompts` is a lightweight, colour‑rich prompt library that ships with a tiny runtime (≈ 2 KB) and works in any Node‑compatible environment – Bun included.  
Official repository & documentation: **GitHub → natemoo‑re/clack** – see the *README* for the full API and design philosophy 【official‑repo】.  

---  

## 2️⃣ Prerequisites  

1. **Bun** (≥ 1.0). Install it – <https://bun.com/docs/installation>.  
2. A folder for the demo:

```bash
mkdir bun‑clack-demo && cd bun‑clack-demo
bun init                     # creates a minimal package.json
```

---  

## 3️⃣ Install `@clack/prompts` with Bun  

Bun’s package manager is `bun add`:

```bash
bun add @clack/prompts      # adds the dependency to package.json
```

(You can also pin a version: `bun add @clack/prompts@2.2.0`, or add it as a dev‑dependency with `--dev` – see **`bun add`** docs <https://bun.com/docs/cli/add>.)  

---  

## 4️⃣ The smallest possible prompt (intro + text + outro)

Create **`hello.js`** (or `hello.ts`):

```js
// hello.js
import { intro, text, outro } from '@clack/prompts';

async function main() {
  intro('👋  Welcome to the Clack demo');   // a coloured header

  const name = await text({
    message: 'What is your name?',
    placeholder: 'John Doe',
  });

  // If the user aborts (Ctrl‑C), `name` will be `undefined`.
  if (name === undefined) {
    outro('⚡️  Prompt cancelled');
    return;
  }

  outro(`✅  Nice to meet you, ${name}!`);
}

main();
```

Run it:

```bash
bun run hello.js          # or simply: bun hello.js
```

You’ll see a bright header, a one‑line input, and a coloured footer.  

---  

## 5️⃣ Common Prompt Types  

Below is a ready‑to‑copy “kitchen‑sink” example that demonstrates every built‑in prompt provided by Clack.

```js
// all-prompts.js
#!/usr/bin/env bun
import {
  intro,
  outro,
  cancel,
  isCancel,
  note,
  text,
  password,
  confirm,
  select,
  multiselect,
  spinner,
} from '@clack/prompts';

async function main() {
  intro('🛠  Clack prompts demo');

  // 1️⃣ Text (single line)
  const nickname = await text({
    message: 'Pick a nickname',
    placeholder: 'SuperCoder',
  });
  if (isCancel(nickname)) return cancel('Operation aborted');

  // 2️⃣ Password (masked)
  const secret = await password({
    message: 'Enter a secret token',
  });
  if (isCancel(secret)) return cancel('Operation aborted');

  // 3️⃣ Confirmation (yes/no)
  const wantsNewsletter = await confirm({
    message: 'Subscribe to our newsletter?',
    initialValue: true,
  });
  if (isCancel(wantsNewsletter)) return cancel('Operation aborted');

  // 4️⃣ Select (single‑choice list)
  const favouriteLang = await select({
    message: 'Your favourite programming language?',
    options: [
      { value: 'js', label: 'JavaScript' },
      { value: 'ts', label: 'TypeScript' },
      { value: 'py', label: 'Python' },
      { value: 'go', label: 'Go' },
      { value: 'rust', label: 'Rust' },
    ],
    initialValue: 'ts',
  });
  if (isCancel(favouriteLang)) return cancel('Operation aborted');

  // 5️⃣ Multiselect (multiple‑choice list)
  const tools = await multiselect({
    message: 'Which dev tools do you use?',
    options: [
      { value: 'vscode', label: 'VS Code' },
      { value: 'vim',    label: 'Vim' },
      { value: 'neovim', label: 'Neovim' },
      { value: 'webstorm', label: 'WebStorm' },
      { value: 'nano',   label: 'nano' },
    ],
    initialValues: ['vscode'],
    // `required: true` forces at least one selection
    required: true,
  });
  if (isCancel(tools)) return cancel('Operation aborted');

  // 6️⃣ A spinner while we “do work”
  const s = spinner();
  s.start('Pretending to do heavy work...');
  await new Promise(r => setTimeout(r, 1500));   // fake async work
  s.stop('All done!');

  // 7️⃣ Show a final note
  note(`
    👤  Name: ${nickname}
    🔒  Secret: ${'*'.repeat(secret.length)}
    📧  Newsletter: ${wantsNewsletter ? 'yes' : 'no'}
    🖥️  Language: ${favouriteLang}
    🛠️  Tools: ${tools.join(', ')}
  `);

  outro('🚀  All prompts finished – thanks for trying Clack!');
}

main();
```

Run it:

```bash
bun run all-prompts.js
```

You’ll experience:

| Prompt | Shortcut / Behaviour |
|--------|---------------------|
| **`text`** | Inline editor; press **Enter** to accept. |
| **`password`** | Input masked with `*`. |
| **`confirm`** | `y/n` toggle (← → or Space). |
| **`select`** | Arrow keys to move, **Enter** to pick. |
| **`multiselect`** | Arrow + Space to toggle items; **Enter** to confirm. |
| **`spinner`** | Shows a tiny animated spinner; call `start()` / `stop()` manually. |
| **`cancel` / `isCancel`** | Detect Ctrl‑C (or ESC) aborts – use `cancel(message)` to show a graceful exit. |
| **`intro` / `outro` / `note`** | Helper functions for coloured headings, footers and info blocks. |

All of these functions are documented in the **“API”** section of the official README 【official‑repo】.  

---  

## 6️⃣ Using **Bun’s watch mode** while you tinker  

When building a CLI you often want the script to restart automatically after you edit a file:

```bash
bun --watch run all-prompts.js
```

*(The `--watch` flag must appear directly after `bun` – see Bun docs <https://bun.com/docs/cli/run#watch>.)*  

---  

## 7️⃣ Handling **Abort / Cancel** elegantly  

`@clack/prompts` returns `undefined` when the user presses **Ctrl‑C** (or **Esc** on some prompts). The helper `isCancel(value)` lets you differentiate between a normal answer and an abort. A typical pattern (used in the snippets above) is:

```js
import { cancel, isCancel } from '@clack/prompts';

const answer = await text({ message: 'Your name?' });
if (isCancel(answer)) return cancel('User aborted');
```

The call to `cancel()` prints a consistent red “✖ Canceled” line and then you can `return` / `process.exit(0)` as you wish.

---  

## 8️⃣ Customising the UI (colors, symbols)  

Clack uses the `kleur` package internally for colours. If you want a different colour theme, you can set the environment variable `CLACK_COLOR` (e.g., `CLACK_COLOR=blue`) before running your script, or you can wrap prompts with your own colour functions using `import { color } from 'kleur'`. The official README explains the theming mechanism under **“Styling”**.

---  

## 9️⃣ Using `@clack/prompts` inside a **Bun plugin / CLI**  

Because Clack does **not** rely on heavy Node APIs, you can safely bundle a CLI that ships a single executable with `bun build`. Example:

```bash
bun build ./cli.js --compile --outfile ./my-cli
```

Now `./my-cli` works on any machine that has the compiled binary (no `node_modules` required).  

---  

## 🔟 Where to find the **official documentation**  

| Item | Link |
|------|------|
| Repository & full README (install, API, examples) | <https://github.com/natemoo-re/clack> |
| npm package page (latest version, install command) | <https://www.npmjs.com/package/@clack/prompts> |
| Bun package manager (`bun add`) | <https://bun.com/docs/cli/add> |
| Running scripts & watch mode (`bun run`) | <https://bun.com/docs/cli/run> |

---  

## ✅ TL;DR (the 5‑step cheat sheet)

1. **Install** `bun add @clack/prompts`  
2. **Import** `import { intro, text, select, confirm, outro } from '@clack/prompts'`  
3. **Create prompts** (call the helper for each UI you need).  
4. **Check for cancellation** with `isCancel(value)` and finish with `cancel()` / `outro()`.  
5. **Run** `bun run your‑script.js` (or `bun --watch run …` while you develop).  

You now have a minimal‑size, beautifully styled interactive prompt toolkit ready for any Bun‑powered CLI application. Happy prompting! 🚀  