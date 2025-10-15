# SweetAlert 2 – Complete API Guide (latest v11.x)  
*(plain language, every code block explains **why it’s useful**, a **common mistake** to avoid, a **best‑practice tip**, and a link to the official SweetAlert2 documentation. All JavaScript uses modern ES modules, `const` / `let`, strict mode, and follows security‑first best practices.)*  

---  

## Table of Contents
| # | Topic |
|---|-------|
| 0️⃣ | Project setup – CDN vs. npm |
| 1️⃣ | Importing SweetAlert2 (ES module & CDN) |
| 2️⃣ | The basic `Swal.fire()` alert |
| 3️⃣ | Custom icons, images & HTML content |
| 4️⃣ | Buttons, custom actions & `preConfirm` |
| 5️⃣ | Input fields (text, email, password, select, radio, checkbox, range) |
| 6️⃣ | Asynchronous workflows (`async / await`) |
| 7️⃣ | Timers, progress bars & auto‑close |
| 8️⃣ | Queues & step‑by‑step wizards (`Swal.mixin`) |
| 9️⃣ | Theming – dark mode, custom CSS, and theming API |
| 🔟 | Positioning & animation options |
| 1️⃣1️⃣ | Validation & sanitising user‑generated HTML |
| 1️⃣2️⃣ | Accessibility (ARIA, focus management) |
| 1️⃣3️⃣ | Security checklist (XSS, CSP, external resources) |
| 1️⃣4️⃣ | Performance checklist (bundling, lazy loading) |
| 1️⃣5️⃣ | Quick reference table (option → purpose) |
| 1️⃣6️⃣ | Where to find the official docs |

---  

## 0️⃣ Project setup – CDN vs. npm  

| Approach | When to use it | How to add it |
|----------|----------------|----------------|
| **CDN (quick prototype)** | CodePen, small demos, learning | `<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>` |
| **npm (real project)** | Webpack/Vite/Parcel, theming, tree‑shaking | `npm i sweetalert2@11` then `import Swal from 'sweetalert2';` |
| **Download the source** | Offline environments, corporate firewalls | Grab the **minified** `sweetalert2.min.js` from <https://github.com/sweetalert2/sweetalert2/releases> and host it locally. |

> **Why** – The CDN version is instantly available and already minified; the npm version lets you customize the bundle (e.g., exclude the default CSS).  
> **Common mistake** – Loading both the CDN script **and** the npm bundle; the second load overwrites the first and can cause “`Swal` is not a function” errors.  
> **Tip** – In a bundler, import the *CSS* separately: `import 'sweetalert2/dist/sweetalert2.min.css';` – this lets you scope the styles with your own build process.  

📚 **Docs**: <https://sweetalert2.github.io/#download>

---  

## 1️⃣ Importing SweetAlert2 (ES module & CDN)  

### 1.1 CDN (global variable)

```html
<!-- In the <head> or before your own script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

* **Why** – The script defines a global variable `Swal` that you can call anywhere.  
* **Common mistake** – Placing the CDN script **after** your own script that calls `Swal.fire()` – you’ll get `ReferenceError: Swal is not defined`.  
* **Tip** – Add the `defer` attribute so the CDN loads **after** the HTML is parsed but **before** your custom script (if the custom script also uses `defer`).  

```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
```

### 1.2 npm + ES module (recommended for modern apps)

```js
// main.js (or any module)
import Swal from 'sweetalert2';

// Optional: import the default CSS if you use a bundler
import 'sweetalert2/dist/sweetalert2.min.css';
```

* **Why** – ES modules give you **tree‑shaking** (unused features can be dropped) and avoid polluting the global scope.  
* **Common mistake** – Forgetting to import the CSS – the modal will appear but be **unstyled** (no backdrop, hidden buttons).  
* **Tip** – If you want to ship only a subset of the default theme, copy the source SCSS from `node_modules/sweetalert2/src/` into your own Sass pipeline and customize the `$primary` variable.  

📚 **Docs**: <https://sweetalert2.github.io/#installation>

---  

## 2️⃣ The basic `Swal.fire()` alert  

```js
// simple alert – fires immediately
Swal.fire({
  title: 'Welcome!',
  text: 'This is a basic SweetAlert2 modal.',
  icon: 'info',          // options: 'success', 'error', 'warning', 'info', 'question'
  confirmButtonText: 'Got it',
});
```

* **Why** – `Swal.fire()` creates a **modal** that blocks interaction and returns a **Promise** that resolves when the user closes it.  
* **Common mistake** – Assuming the function returns the button element; it returns a **Promise**, not the DOM node.  
* **Tip** – Use `.then()` or `await` to handle the user’s response, e.g.:

```js
Swal.fire({title:'Confirm?', showCancelButton:true})
  .then(result => {
    if (result.isConfirmed) console.log('User clicked OK');
  });
```

📚 **Docs**: <https://sweetalert2.github.io/#basic-alert>

---  

## 3️⃣ Custom icons, images & HTML content  

### 3.1 Custom SVG / image icon  

```js
Swal.fire({
  title: 'Custom icon',
  html: '<b>Bold HTML content</b> <br> with a custom SVG',
  imageUrl: 'https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f60e.svg',
  imageWidth: 80,
  imageHeight: 80,
  imageAlt: 'Cool emoji',
});
```

* **Why** – `imageUrl` lets you completely replace the default icon with any picture (including SVG).  
* **Common mistake** – Using a **cross‑origin** image that blocks the modal’s focus (some browsers restrict drawing cross‑origin images on canvas). Use images served with proper CORS headers or host them yourself.  
* **Tip** – If you need **animated** icons, use a GIF or inline SVG with `animate` tags – SweetAlert2 does **not** restrict them.  

### 3.2 Full HTML content (dangerous if unsanitised)

```js
Swal.fire({
  title: '<strong>HTML title</strong>',
  html: '<p>Paragraph with <a href="https://example.com" target="_blank" rel="noopener">link</a>.</p>',
  showCloseButton: true,
});
```

* **Why** – `title` and `html` accept **raw HTML**, allowing rich formatting.  
* **Common mistake** – Passing **user‑generated** HTML directly → open XSS vector.  
* **Tip** – **Sanitise** any external string with a library like **DOMPurify** before giving it to SweetAlert2:

```js
import DOMPurify from 'dompurify';
const safeHtml = DOMPurify.sanitize(userInput);
Swal.fire({html: safeHtml});
```

📚 **Docs**: <https://sweetalert2.github.io/#custom-icon>

---  

## 4️⃣ Buttons, custom actions & `preConfirm`  

### 4.1 Multiple buttons (confirm / cancel)

```js
Swal.fire({
  title: 'Delete file?',
  text: "This action cannot be undone.",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, keep it',
  reverseButtons: true      // puts cancel on the left (helps with UX)
});
```

* **Why** – `showCancelButton` automatically adds a secondary button; `reverseButtons` swaps their order (better for left‑to‑right languages).  
* **Common mistake** – Relying on `result.isConfirmed` **only**; when the user dismisses via the Escape key or clicking outside, `result.isDismissed` is true.  
* **Tip** – Always check for both `isConfirmed` and `isDismissed`:

```js
Swal.fire(...).then(result => {
  if (result.isConfirmed) { /* OK */ }
  else if (result.isDismissed) { /* cancel or click-away */ }
});
```

### 4.2 `preConfirm` – run async work before closing  

```js
Swal.fire({
  title: 'Enter your username',
  input: 'text',
  inputPlaceholder: 'username',
  showCancelButton: true,
  preConfirm: async (username) => {
    try {
      const response = await fetch(`/api/users/${username}`);
      if (!response.ok) throw new Error('User not found');
      return await response.json(); // returns data to .then()
    } catch (err) {
      Swal.showValidationMessage(`Request failed: ${err}`);
    }
  }
}).then(result => {
  if (result.isConfirmed) console.log('User data:', result.value);
});
```

* **Why** – `preConfirm` runs **before** the modal resolves, allowing you to validate or fetch data. If it throws or returns a rejected Promise, SweetAlert2 displays a validation message and **keeps the modal open**.  
* **Common mistake** – Forgetting to `return` a value from `preConfirm`; the resolved Promise will be `undefined`.  
* **Tip** – Use `Swal.showValidationMessage()` for **inline** error feedback instead of `alert()`.  

📚 **Docs**: <https://sweetalert2.github.io/#handling-dismissals>

---  

## 5️⃣ Input fields (text, email, password, select, radio, checkbox, range)  

SweetAlert2 can transform the modal into a **form** by setting the `input` option.

| Input type | Code snippet | Use‑case |
|------------|--------------|----------|
| `text` | `Swal.fire({title:'Name', input:'text', inputPlaceholder:'Your name'})` | Simple one‑line text |
| `email` | `Swal.fire({title:'Email', input:'email', inputValidator: (value)=>!value && 'Please type email'})` | Built‑in HTML5 validation |
| `password` | `Swal.fire({title:'Password', input:'password', inputAttributes:{autocomplete:'new-password'}})` | Secure password entry |
| `select` | `Swal.fire({title:'Pick a fruit', input:'select', inputOptions:{apple:'Apple', banana:'Banana'}, inputPlaceholder:'Select a fruit'})` | Dropdown list |
| `radio` | `Swal.fire({title:'Choose', input:'radio', inputOptions:{a:'A', b:'B'}, inputValidator:... })` | Mutually exclusive options |
| `checkbox` | `Swal.fire({title:'Accept terms', input:'checkbox', inputLabel:'I agree', inputValue:1})` | Simple boolean flag |
| `range` | `Swal.fire({title:'Rate', input:'range', inputAttributes:{ min:1, max:10, step:1 }, inputValue:5 })` | Slider UI |

* **Why** – SweetAlert2’s built‑in inputs keep the modal **self‑contained**, reducing the need for separate form markup.  
* **Common mistake** – Using `inputValidator` that returns a **string** for success (it should return `undefined`/`null` on success, a string on error).  
* **Tip** – For **multiple** fields, use the **queue** (see §8) or a **custom HTML** form inside `html:` and read `document.getElementById(...)` inside `preConfirm`.  

📚 **Docs**: <https://sweetalert2.github.io/#input-types>

---  

## 6️⃣ Asynchronous workflows (`async / await`)  

Because `Swal.fire()` returns a **Promise**, you can write clean async code:

```js
async function askNameAndGreet() {
  const { value: name } = await Swal.fire({
    title: 'What is your name?',
    input: 'text',
    inputPlaceholder: 'Enter name',
    showCancelButton: true,
  });

  if (name) {
    await Swal.fire(`Hello, <b>${DOMPurify.sanitize(name)}</b>!`);
  } else {
    await Swal.fire('No name entered.');
  }
}
askNameAndGreet();
```

* **Why** – `await` pauses execution until the user closes the modal, simplifying branching logic.  
* **Common mistake** – Forgetting `await` when you need the result; the code proceeds with an unresolved Promise.  
* **Tip** – Wrap the whole flow in a **try / catch** block to handle any rejected Promise (e.g., network error inside `preConfirm`).  

📚 **Docs**: <https://sweetalert2.github.io/#handling-dismissals>

---  

## 7️⃣ Timers, progress bars & auto‑close  

### 7.1 Auto‑close after N milliseconds  

```js
Swal.fire({
  title: 'Auto close alert',
  timer: 3000,               // 3 seconds
  timerProgressBar: true,    // shows a progress bar
  didOpen: () => {
    // optional: pause timer on hover
    const b = Swal.getHtmlContainer();
    b.addEventListener('mouseenter', Swal.stopTimer);
    b.addEventListener('mouseleave', Swal.resumeTimer);
  }
});
```

* **Why** – The `timer` option automatically closes the modal after the delay; `timerProgressBar` gives visual feedback.  
* **Common mistake** – Using `timer` **without** handling the `dismiss` reason; the Promise resolves with `dismiss: 'timer'`.  
* **Tip** – If you want the user to be able to **cancel** the timer, expose a “Cancel” button and call `Swal.stopTimer()` in its handler.  

### 7.2 Progress steps (wizard‑style)  

```js
Swal.fire({
  title: 'Step 1 of 3',
  text: 'First question',
  showCancelButton: true,
  confirmButtonText: 'Next →',
  progressSteps: ['1', '2', '3'],
  currentProgressStep: 0
}).then(() => {
  // second step
  Swal.fire({
    title: 'Step 2 of 3',
    text: 'Second question',
    showCancelButton: true,
    confirmButtonText: 'Next →',
    progressSteps: ['1', '2', '3'],
    currentProgressStep: 1
  });
});
```

* **Why** – `progressSteps` shows a visual stepper at the top of the modal.  
* **Common mistake** – Forgetting to update `currentProgressStep` for each step; the stepper will stay on the first index.  
* **Tip** – Use **`Swal.mixin()`** (see §8) to avoid repeating options and automatically manage the step index.  

📚 **Docs**: <https://sweetalert2.github.io/#timer>

---  

## 8️⃣ Queues & step‑by‑step wizards (`Swal.mixin`)  

When you have a series of prompts, create a **pre‑configured** instance:

```js
const wizard = Swal.mixin({
  input: 'text',
  confirmButtonText: 'Next →',
  showCancelButton: true,
  progressSteps: ['1', '2', '3'],
  currentProgressStep: 0,
  // optionally store results in an array
  preConfirm: (value) => {
    wizardValues.push(value);
  }
});

let wizardValues = [];

wizard.fire({
  title: 'First name',
  currentProgressStep: 0
}).then(() => wizard.fire({
  title: 'Last name',
  currentProgressStep: 1
})).then(() => wizard.fire({
  title: 'Email',
  input: 'email',
  currentProgressStep: 2
})).then(() => {
  console.log('Collected values:', wizardValues);
  Swal.fire('All done!', JSON.stringify(wizardValues), 'success');
});
```

* **Why** – `Swal.mixin()` returns a **new Swal object** with the supplied defaults, so you write the wizard steps more concisely.  
* **Common mistake** – Using the **same** variable `Swal` for both the default and the mixin; you’ll lose the original defaults. Store the mixin in a different constant (`wizard`).  
* **Tip** – `Swal.queue()` is a higher‑level helper that automatically iterates an **array of step objects**. Example:

```js
Swal.queue([
  {title:'Username', input:'text'},
  {title:'Password', input:'password'}
]).then(result => {
  if (result.value) console.log('Collected:', result.value);
});
```

📚 **Docs**: <https://sweetalert2.github.io/#multiple-modals>

---  

## 9️⃣ Theming – dark mode, custom CSS, and the theming API  

### 9.1 Built‑in dark theme  

```js
Swal.fire({
  title: 'Dark mode alert',
  text: 'This uses the built‑in dark theme.',
  background: '#2c2c2c',
  color: '#fff',
  confirmButtonColor: '#3085d6',
  backdrop: `
    rgba(0,0,0,0.4)
    url("https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif")
    left top
    no-repeat
  `,
});
```

* **Why** – SweetAlert2 does **not** ship a global dark mode, but you can set `background`, `color`, and `confirmButtonColor` to dark values.  
* **Common mistake** – Overriding only `background` and leaving `color` black → text becomes invisible.  
* **Tip** – Create a **CSS class** for your theme and pass it via `customClass` (see §9.2).  

### 9.2 Custom CSS via `customClass`

```js
Swal.fire({
  title: 'Styled alert',
  html: '<p class="my-html">Custom styled paragraph.</p>',
  customClass: {
    popup: 'my-popup',
    title: 'my-title',
    htmlContainer: 'my-html',
    confirmButton: 'my-confirm-btn',
    cancelButton: 'my-cancel-btn',
    container: 'my-container'
  }
});
```

```css
/* style.css */
.my-popup   { border-radius: 12px; }
.my-title   { color: #ff6b6b; }
.my-confirm-btn { background: #ff6b6b; border: none; }
```

* **Why** – `customClass` lets you attach **your own CSS classes** to each part of the modal, keeping the core library untouched.  
* **Common mistake** – Using class names that clash with Bootstrap’s `.btn` etc., unintentionally inheriting styles.  
* **Tip** – Prefix all custom classes (e.g., `my-`) to avoid collisions.  

📚 **Docs**: <https://sweetalert2.github.io/#custom-class>

---  

## 🔟 Positioning & animation options  

| Option | Example | Effect |
|--------|---------|--------|
| `position` | `position: 'top-end'` | Places modal at any corner (`top`, `top-start`, `top-end`, `center`, `center-start`, `center-end`, `bottom`, `bottom-start`, `bottom-end`). |
| `showClass` / `hideClass` | `showClass: { popup: 'animate__animated animate__fadeInDown' }` | Uses CSS classes (e.g., Animate.css) for entrance/exit animations. |
| `backdrop` | `backdrop: false` | Disables the dark overlay; can also be a custom CSS string (see §9.1). |
| `allowOutsideClick` | `allowOutsideClick: false` | Prevents closing when clicking outside. |
| `allowEscapeKey` | `allowEscapeKey: false` | Disables the ESC key. |
| `focusConfirm` | `focusConfirm: false` | Leaves focus on the first input instead of auto‑focusing the Confirm button. |

```js
Swal.fire({
  title: 'Top‑right toast‑style',
  position: 'top-end',
  toast: true,               // makes it look like a toast
  timer: 3000,
  timerProgressBar: true,
  showConfirmButton: false,
  background: '#333',
  color: '#fff',
  icon: 'success',
});
```

* **Why** – `toast: true` turns the modal into a **non‑blocking notification** that appears in a corner.  
* **Common mistake** – Using `toast: true` together with `showCancelButton` – the cancel button is hidden because toast mode removes the footer.  
* **Tip** – When you need a **persistent** toast (no auto‑close), set `timer: null` and add a manual close button via `showCloseButton: true`.  

📚 **Docs**: <https://sweetalert2.github.io/#position>

---  

## 1️⃣1️⃣ Validation & sanitising user‑generated HTML  

SweetAlert2’s `preConfirm` can return a validation error; you should **never** trust raw user input for the `title` or `html` fields.

```js
Swal.fire({
  title: 'Enter safe HTML',
  html: `<textarea id="userHtml" class="form-control" rows="4"></textarea>`,
  preConfirm: () => {
    const raw = document.getElementById('userHtml').value;
    // Use DOMPurify (or similar) to clean the HTML
    const safe = DOMPurify.sanitize(raw, {ALLOWED_TAGS: ['b','i','u','a','p']});
    if (!safe) {
      Swal.showValidationMessage('Content was stripped entirely.');
    }
    return safe;
  }
}).then(result => {
  if (result.isConfirmed) {
    Swal.fire({html: result.value}); // safe to render
  }
});
```

* **Why** – Sanitising prevents **Cross‑Site Scripting (XSS)** when you later inject the string into `html:` or elsewhere in the page.  
* **Common mistake** – Using `innerHTML` elsewhere in the page with the same unsanitised string – the same XSS risk applies.  
* **Tip** – **Never** turn off CSP. If you use a CSP header, add `'unsafe-inline'` **only** if you must; otherwise keep the policy strict and rely on the library’s own CSS (which is not inline).  

📚 **Docs**: <https://sweetalert2.github.io/#input-validator>

---  

## 1️⃣2️⃣ Accessibility (ARIA, focus management)  

| Feature | How SweetAlert2 handles it |
|---------|----------------------------|
| **ARIA role** | `role="dialog"` is added automatically to the popup. |
| **Labeling** | The `title` becomes the dialog’s accessible name. |
| **Focus trap** | When the modal opens, focus is moved to the first focusable element (input or confirm button). Tab navigation is **trapped** inside the dialog. |
| **Escape key** | Closes the modal (unless `allowEscapeKey: false`). |
| **Screen‑reader announce** | Uses `aria-live="assertive"` for the popup container. |
| **Dismiss reason** | The resolved Promise contains `dismiss` values (`'esc'`, `'overlay'`, `'timer'`, `'backdrop'`, `'close'`, `'cancel'`). |

**Accessibility tip** – If you replace the default title with custom HTML (e.g., `title: '<span id="myTitle">…</span>'`), add `aria-labelledby="myTitle"` manually:

```js
Swal.fire({
  title: '<span id="myTitle">Complex title</span>',
  customClass: { title: 'visually-hidden' }, // hide duplicate text for SR
  // ARIA – SweetAlert2 will still set role="dialog"
});
```

> **Common mistake** – Hiding the modal with `display:none` *before* it’s fully removed, which can cause screen readers to speak the hidden content. SweetAlert2 handles removal internally, so **don’t** manually set `display:none` on the container.  

📚 **Docs**: <https://sweetalert2.github.io/#accessibility>

---  

## 1️⃣3️⃣ Security checklist (XSS, CSP, external resources)  

| Area | Checklist |
|------|-----------|
| **External scripts** | Load SweetAlert2 from a **trusted CDN** (jsDelivr, unpkg) and use **SRI** (`integrity` attribute). |
| **User‑generated HTML** | Always **sanitize** with DOMPurify (or a similar library) before passing to `title` or `html`. |
| **`target="_blank"` links** | Include `rel="noopener noreferrer"` to prevent reverse‑tabnabbing. |
| **Content‑Security‑Policy** | Example header: <br>`Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; img-src 'self' data: https:` |
| **`preConfirm` network calls** | Validate URLs before fetching; avoid open redirects. Use `fetch(..., {credentials: 'same-origin'})` unless you need cross‑origin. |
| **Avoid `eval`** | Never enable `allowOutsideClick: true` in conjunction with `preConfirm` that calls `eval` on user data. |
| **CORS** | Ensure any external image used in `imageUrl` is served with `Access-Control-Allow-Origin: *` or your domain. |
| **Sanitize error messages** | When you call `Swal.showValidationMessage(err.message)`, make sure `err.message` is **trusted** (don’t echo raw server errors). |

---  

## 1️⃣4️⃣ Performance checklist (bundling, lazy loading)  

| Item | Why it matters |
|------|----------------|
| **Tree‑shake** | Import only the parts you need (`import Swal from 'sweetalert2';`). The library’s source is already modular, so unused helpers are dropped. |
| **Lazy‑load** | If alerts are only used on a specific page, load SweetAlert2 **on demand** with dynamic import: `import('sweetalert2').then(({default:Swal})=>{…});` |
| **CSS only once** | Import the minified CSS once at the root of your app; duplicate imports increase CSS size. |
| **Cache‑control** | Serve `sweetalert2.min.js` with a long `max‑age` header; browsers will reuse it across pages. |
| **Avoid excessive toasts** | Show no more than 3 concurrent toast‑style alerts; more will cause layout thrashing. |
| **No unnecessary images** | Use `imageUrl` only for small icons. Large images should be loaded outside the modal to keep the bundle light. |

---  

## 1️⃣5️⃣ Quick reference table (most‑used options)  

| Option | Type | Typical values / description |
|--------|------|------------------------------|
| `title` | `string` | Plain text or HTML (sanitize!). |
| `html` | `string` | Rich HTML content (sanitise!). |
| `text` | `string` | Plain‑text fallback (no HTML). |
| `icon` | `string` | `'success'`, `'error'`, `'warning'`, `'info'`, `'question'` |
| `imageUrl` | `string` | URL of a custom image (replaces the icon). |
| `imageWidth` / `imageHeight` | `number` | Size of custom image. |
| `showConfirmButton` | `boolean` | Show “OK” button (default `true`). |
| `confirmButtonText` | `string` | Text for the confirm button. |
| `showCancelButton` | `boolean` | Adds a second “Cancel” button. |
| `cancelButtonText` | `string` | Text for cancel button. |
| `reverseButtons` | `boolean` | Swaps order of confirm/cancel. |
| `timer` | `number` (ms) | Auto‑close after N ms. |
| `timerProgressBar` | `boolean` | Shows progress bar while timer runs. |
| `input` | `string` | `'text'`, `'email'`, `'password'`, `'select'`, `'radio'`, `'checkbox'`, `'range'`, `'textarea'`, `'file'` |
| `inputPlaceholder` | `string` | Placeholder text for the input. |
| `inputOptions` | `object` | For `select`, `radio`, `checkbox` – `{value: label}` pairs. |
| `inputValue` | `any` | Initial value for the input. |
| `inputValidator` | `function` | Sync validator, returns string (error) or `undefined`. |
| `preConfirm` | `function` (may be async) | Runs before modal resolves, can fetch data, return a value, or call `Swal.showValidationMessage`. |
| `allowOutsideClick` | `boolean` | Click outside to dismiss (`true` by default). |
| `allowEscapeKey` | `boolean` | ESC key dismisses (`true` by default). |
| `backdrop` | `boolean` / `string` | `true` (default), `false` (no backdrop), or custom CSS string. |
| `position` | `string` | `'center'` (default) or any corner (`'top-end'`, etc.). |
| `toast` | `boolean` | Turns the modal into a toast (no overlay). |
| `customClass` | `object` | Assign custom CSS classes to parts (`popup`, `title`, `htmlContainer`, `confirmButton`, …). |
| `showClass` / `hideClass` | `object` | Custom CSS classes for entry/exit animations. |
| `progressSteps` | `array<string>` | Labels for step‑by‑step wizard. |
| `currentProgressStep` | `number` | Index of the current step (0‑based). |
| `didOpen` | `function` | Callback after modal is inserted; useful for adding event listeners. |
| `willClose` | `function` | Runs before the modal starts closing. |
| `didClose` | `function` | Runs after modal is removed from the DOM. |

---  

## 1️⃣6️⃣ Where to find the official docs  

| Area | URL |
|------|-----|
| **Home / Installation** | <https://sweetalert2.github.io/#download> |
| **Basic usage** | <https://sweetalert2.github.io/#basic-alert> |
| **Input types** | <https://sweetalert2.github.io/#input-types> |
| **Custom icons & HTML** | <https://sweetalert2.github.io/#custom-icon> |
| **Pre‑confirm & async** | <https://sweetalert2.github.io/#handling-dismissals> |
| **Queue / multi‑step** | <https://sweetalert2.github.io/#multiple-modals> |
| **Theming & custom CSS** | <https://sweetalert2.github.io/#custom-class> |
| **Accessibility** | <https://sweetalert2.github.io/#accessibility> |
| **Security & sanitisation** | <https://sweetalert2.github.io/#input-validator> |
| **API reference (all options)** | <https://sweetalert2.github.io/#configuration> |
| **GitHub repository (issues, source)** | <https://github.com/sweetalert2/sweetalert2> |
| **DOMPurify (recommended sanitizer)** | <https://github.com/cure53/DOMPurify> |

---  

## 🎉 Wrap‑up  

1. **Add the library** (CDN or npm) – keep a single source of truth, use SRI for CDN.  
2. **Fire a simple alert** (`Swal.fire({title:'…'})`) and handle the returned Promise (`await` or `.then`).  
3. **Leverage inputs** (`input:`) or **custom HTML** (`html:`) for forms, but always **sanitise** user data.  
4. **Use `preConfirm`** for async validation or fetch calls; return a value or call `Swal.showValidationMessage`.  
5. **Create wizards** with `Swal.mixin()` or `Swal.queue()` for multi‑step flows.  
6. **Theme** your alerts with `customClass` or by passing `background`, `color`, etc., and add dark‑mode support if needed.  
7. **Secure** everything: CSP, sanitisation, `rel="noopener"` on external links, and avoid trusting user HTML.  
8. **Optimise** – lazy‑load the library, tree‑shake, and use a single global CSS import.  

You now have a **complete, up‑to‑date SweetAlert2 guide** covering everything from the simplest alert to complex, accessible, asynchronous wizards, with best‑practice security and performance tips. Happy alert‑building! 🎈  