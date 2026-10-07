## 🌟 Bun + **prompts** – ES‑module version  

Below is the same “install‑and‑run” flow you already saw, but every code snippet now uses **`import`** (no `require`).  
Bun can execute ESM files out‑of‑the‑box; just give the file a `.js`/`.mjs` extension **and** make sure the project’s `package.json` declares `"type":"module"` (or use the `.mjs` suffix).

---

### 1️⃣ Set up a fresh project (or reuse an existing one)

```bash
mkdir bun‑cli && cd bun‑cli
bun init                         # creates package.json and a starter file
```

If the generated `package.json` does **not** already have `"type":"module"`, add it:

```json
{
  "name": "bun-cli",
  "version": "0.1.0",
  "type": "module",          // <‑‑ tells Node/Bun to treat .js as ESM
  "scripts": {}
}
```

---

### 2️⃣ Add the `prompts` library with Bun’s package manager

```bash
bun add prompts
```

Bun resolves the package, writes it to `node_modules`, and updates `package.json` automatically【8†L90-L95】.

---

### 3️⃣ Write your interactive script – **import** style  

Create (or overwrite) `index.js` (or `index.mjs`) with any of the examples you need.

#### 🎯 Single‑question example

```js
// index.js
import prompts from 'prompts';

(async () => {
  const response = await prompts({
    type: 'text',
    name: 'meaning',
    message: 'What is the meaning of life?'
  });

  console.log('Your answer →', response.meaning);
})();
```

#### 📋 Prompt chain (multiple questions)

```js
// index.js
import prompts from 'prompts';

const questions = [
  {
    type: 'text',
    name: 'username',
    message: 'What is your GitHub username?'
  },
  {
    type: 'number',
    name: 'age',
    message: 'How old are you?'
  },
  {
    type: 'text',
    name: 'about',
    message: 'Tell something about yourself',
    initial: 'Why should I?'
  }
];

(async () => {
  const answers = await prompts(questions);
  console.log('Collected →', answers);
})();
```

#### ✅ Validation + default value

```js
// index.js
import prompts from 'prompts';

(async () => {
  const { age } = await prompts({
    type: 'number',
    name: 'age',
    message: 'How old are you?',
    validate: v => (v < 18 ? 'Nightclub is 18+ only' : true), // reject <18
    initial: 21                                            // default shown
  });

  console.log(`You are ${age} years old`);
})();
```

#### 🔄 Conditional (dynamic) prompts

```js
// index.js
import prompts from 'prompts';

const questions = [
  {
    type: 'text',
    name: 'dish',
    message: 'Do you like pizza?'
  },
  {
    // ask only if the answer was “pizza”
    type: prev => (prev.toLowerCase() === 'pizza' ? 'text' : null),
    name: 'topping',
    message: 'Name a topping you love'
  }
];

(async () => {
  const answers = await prompts(questions);
  console.log('Result →', answers);
})();
```

#### 🛑 Cancel / abort handling

```js
// index.js
import prompts from 'prompts';

(async () => {
  const response = await prompts(
    {
      type: 'text',
      name: 'name',
      message: 'Your name?'
    },
    {
      onCancel: () => {
        console.log('\n✋ Prompt cancelled – exiting.');
        process.exit(0);
      }
    }
  );

  console.log('Hello', response.name);
})();
```

All of the snippets above are **copy‑and‑paste ready**. They work the same as the `require` version, only using the modern ES‑module syntax.

---

### 4️⃣ Run the script

```bash
bun run index.js     # or: bun index.js
```

Bun compiles and executes the file instantly – no extra build step.

---

## 📚 Reference links

| What you’re looking for | URL |
|------------------------|-----|
| Bun CLI – `add` (package manager) | <https://bun.com/docs/cli/add> |
| Full `prompts` README (ESM support mentioned) | <https://github.com/terkelg/prompts#readme> |
| List of prompt types (text, number, select, …) | <https://github.com/terkelg/prompts#prompt-types> |
| Bun’s built‑in APIs (fs, http, etc.) | <https://bun.com/docs/api> |

---

### TL;DR – One‑liner starter

```bash
mkdir demo && cd demo && bun init && echo '{"type":"module"}' > package.json && bun add prompts && cat > index.js <<'EOF'
import prompts from 'prompts';

(async () => {
  const answers = await prompts([
    { type: 'text', name: 'username', message: 'GitHub username?' },
    { type: prev => (prev.toLowerCase() === 'bun' ? 'confirm' : null),
      name: 'likeBun',
      message: 'Do you like Bun?' }
  ]);
  console.log('Result →', answers);
})();
EOF
bun run index.js
```

You now have a fully functional, **import‑based** CLI built with Bun and the `prompts` library-no `require` statements, no Node‑specific confusion. Happy coding!