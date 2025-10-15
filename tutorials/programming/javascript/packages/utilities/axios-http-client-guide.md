## 📦 Beginner‑friendly guide – using **`axios`** (HTTP client) together with **Bun’s built‑in file APIs**  

> This tutorial assumes you have **Bun ≥ 1.0** installed. If you haven’t installed it yet, follow the quick guide on the Bun site: <https://bun.com/docs/installation>.  

We’ll walk through every step:

1. **Create a project folder**  
2. **Add `axios`** with Bun’s package manager  
3. **Make GET / POST requests** (the most common operations)  
4. **Save a response to a file** using **Bun’s native `Bun.file` / `Bun.write`** helpers (no extra dependencies)  
5. **Read a file** (e.g., to load an API key) with `Bun.file`  
6. **Run / watch the script** with Bun’s fast CLI  

All code is plain JavaScript (`.js`), but you can rename the files to `.ts` if you prefer TypeScript – Bun will compile them on the fly.

---  

## 1️⃣ Create a fresh folder

```bash
mkdir bun‑axios‑demo
cd bun‑axios‑demo
bun init               # creates a tiny package.json (you can just press <Enter> for defaults)
```

Your folder now looks like:

```
bun-axios-demo/
├─ package.json
└─ (empty for now)
```

---  

## 2️⃣ Install `axios` with Bun

```bash
bun add axios
```

Bun writes the dependency into `package.json` and caches the package locally – it’s super‑fast (usually under a second).

---  

## 3️⃣ A **GET** request – “Hello, world!”  

Create a file called **`get.js`**:

```js
// get.js
import axios from 'axios';

// A tiny async wrapper so we can use await at the top level
async function main() {
  try {
    // 1️⃣ Make the request
    const response = await axios.get('https://api.github.com/repos/axios/axios');

    // 2️⃣ Log the HTTP status and a bit of data
    console.log('🚀  Status:', response.status);          // e.g. 200
    console.log('🗂️  Repo name:', response.data.name);   // "axios"
  } catch (err) {
    // 3️⃣ Simple error handling for beginners
    console.error('❌  Request failed:', err.message);
  }
}

main();
```

Run it:

```bash
bun run get.js
```

You should see something like:

```
🚀  Status: 200
🗂️  Repo name: axios
```

> **What just happened?**  
> * `axios.get(url)` returns a **Promise**. `await` pauses until the HTTP call finishes. The resolved object has the shape `{ data, status, headers, ... }`. This shape is documented in the Axios README 【official‑repo】.

---  

## 4️⃣ A **POST** request – sending JSON  

Create **`post.js`**:

```js
// post.js
import axios from 'axios';

async function main() {
  const payload = {
    title: 'Bun demo',
    body: 'Hello from Bun + Axios!',
    userId: 1,
  };

  try {
    const res = await axios.post(
      'https://jsonplaceholder.typicode.com/posts',
      payload,                         // <-- body gets JSON‑stringified automatically
      { headers: { 'Content-Type': 'application/json' } } // optional but explicit
    );

    console.log('✅  Created post with id:', res.data.id);
    console.log('🔎  Server echoed back:', res.data);
  } catch (e) {
    console.error('❗️  POST failed:', e.message);
  }
}

main();
```

Run:

```bash
bun run post.js
```

You’ll see a fake post ID (the placeholder API always returns `id: 101`).  

---  

## 5️⃣ **Saving a response to a file** – using **Bun’s native file helpers**  

Bun ships its own extremely easy‑to‑use file API:

* `Bun.file(path)` → returns a `File` object that can be read with `.text()`, `.json()`, etc.  
* `Bun.write(fileOrPath, data)` → writes `string | Uint8Array | Blob | File` to disk in one line.

> Docs: <https://bun.com/docs/runtime/file-handling>

### Example: download a small image and write it to `logo.png`

Create **`download-image.js`**:

```js
// download-image.js
import axios from 'axios';
import { writeFile } from 'fs/promises';   // you could also use Bun.write – both work

async function main() {
  const imageUrl = 'https://raw.githubusercontent.com/axios/axios/master/logo.svg';

  try {
    // `responseType: 'arraybuffer'` gives us the raw binary data
    const { data } = await axios.get(imageUrl, { responseType: 'arraybuffer' });

    // -------------------------------------------------
    // OPTION A – using Node's `fs/promises` (works everywhere)
    await writeFile('logo.svg', Buffer.from(data));
    // -------------------------------------------------
    // OPTION B – using Bun's built‑in helper (even shorter)
    // await Bun.write('logo.svg', data);
    // -------------------------------------------------

    console.log('✅  Image saved as logo.svg');
  } catch (err) {
    console.error('❌  Download failed:', err.message);
  }
}

main();
```

Run it:

```bash
bun run download-image.js
```

A file **`logo.svg`** appears in your folder. Open it with any image viewer to confirm.

### Why we used `arraybuffer`  

When you need the exact bytes (binary files, images, PDFs, etc.) you must tell Axios not to treat the response as JSON or text. `responseType: 'arraybuffer'` returns a `Uint8Array`‑compatible buffer that you can hand straight to `Bun.write` or `fs/promises.writeFile`.

---  

## 6️⃣ **Reading a configuration file** (e.g., an API key) with **`Bun.file`**

Suppose you store a personal GitHub token in a plain‑text file called **`.github-token`** (never commit this file!). You can read it synchronously with `Bun.file`:

```js
// token-demo.js
import axios from 'axios';

// Load the token once at the top level (synchronous, fine for small files)
const token = Bun.file('.github-token').text();   // returns a string

async function main() {
  try {
    const { data } = await axios.get('https://api.github.com/user', {
      headers: { Authorization: `token ${token.trim()}` },
    });
    console.log('👤  Authenticated as', data.login);
  } catch (err) {
    console.error('❗️  Auth failed:', err.response?.status);
  }
}

main();
```

Run:

```bash
bun run token-demo.js
```

*If the token file does not exist or is empty, the request will fail – that’s expected, and the catch block prints a friendly message.*

> **Note** – `Bun.file(...).text()` is **synchronous** because the file is read only once and is tiny. For large files you’d use `await Bun.file(path).arrayBuffer()` (async) instead.

---  

## 7️⃣ Adding **interceptors** (optional, but handy once you get comfortable)

Interceptors let you add logic that runs **before every request** (e.g., automatically attach the token) and **after every response** (e.g., logging).

```js
// api-client.js
import axios from 'axios';

// Create a pre‑configured instance – you only have to set the token once
const api = axios.create({
  baseURL: 'https://api.github.com',
  timeout: 5_000,
});

// ---------- Request interceptor ----------
api.interceptors.request.use(cfg => {
  // Load the token the first time the interceptor runs
  if (!cfg.headers.Authorization) {
    const token = Bun.file('.github-token').text().trim();
    cfg.headers.Authorization = `token ${token}`;
  }
  console.log('➡️  Request →', cfg.method?.toUpperCase(), cfg.url);
  return cfg;
});

// ---------- Response interceptor ----------
api.interceptors.response.use(
  res => {
    console.log('✅  Response', res.status, res.config.url);
    return res;               // pass the response on
  },
  err => {
    console.error('❌  API error', err.response?.status);
    return Promise.reject(err);
  }
);

// Example usage
async function fetchMyRepos() {
  const { data } = await api.get('/user/repos');
  console.log('🗂️  You have', data.length, 'repos');
}

fetchMyRepos().catch(() => {});   // swallow the rejected promise for demo purposes
```

Run:

```bash
bun run api-client.js
```

You’ll see the request/response logs plus a list of your repos (if the token is valid).  

---  

## 8️⃣ **Watch mode** – edit & auto‑re‑run  

When you are experimenting, let Bun automatically re‑execute the script after each file save:

```bash
bun --watch run download-image.js
```

Every time you modify `download-image.js` (or any imported file) the script runs again – perfect for rapid prototyping.

---  

## 9️⃣ **Bundling the whole thing into a single executable**  

Because both **Axios** and **Bun’s file helpers** are pure‑JavaScript, you can ship a single binary:

```bash
bun build api-client.js --compile --outfile api-client
```

`./api-client` now contains everything (Axios code, your logic, Bun runtime) and can be copied to any machine—no `node_modules` folder needed.

---  

## 📚 Official documentation links (keep them handy)

| Topic | Link |
|------|------|
| **Axios repository & README** (install, API, interceptors, streaming) | <https://github.com/axios/axios> |
| **Axios npm page** (latest version, quick install command) | <https://www.npmjs.com/package/axios> |
| **Bun file handling** (`Bun.file`, `Bun.write`) | <https://bun.com/docs/runtime/file-handling> |
| **Bun package manager** (`bun add`, `bun install`) | <https://bun.com/docs/cli/add> |
| **Bun run & watch mode** | <https://bun.com/docs/cli/run> |
| **Bundling with `bun build`** | <https://bun.com/docs/bundler> |

---  

## ✅ TL;DR – one‑liner cheat sheet for beginners  

```bash
# 1️⃣ scaffold a project
mkdir bun-axios-demo && cd bun-axios-demo && bun init

# 2️⃣ add the HTTP client
bun add axios

# 3️⃣ write a simple GET request (get.js)
cat > get.js <<'EOF'
import axios from 'axios';
(async () => {
  try {
    const { data, status } = await axios.get('https://api.github.com');
    console.log('Status:', status);
    console.log('Hostname:', data.current_user_url);
  } catch (e) { console.error('Failed:', e.message); }
})();
EOF

# 4️⃣ run it (or watch while you edit)
bun run get.js               # normal run
bun --watch run get.js       # auto‑re‑run on changes
```

That’s it – you now have a **fully functional HTTP client** that can:

* fetch data (`GET`, `POST`, etc.)  
* write the result to a file with **Bun’s native `Bun.write`** or Node’s `fs` module  
* read configuration (API keys) with **`Bun.file`**  
* (optionally) use interceptors for auth or logging  

Happy coding and enjoy the speed of Bun! 🚀  