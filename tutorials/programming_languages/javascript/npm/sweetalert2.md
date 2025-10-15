**SweetAlert2 — Reusable, Secure Dialogs (Copy‑paste recipes)**  

_Practical patterns for SweetAlert2 with a reusable class and helpers that are:_

* **Safe by default** – no unsanitized HTML is rendered unless you explicitly enable it with a sanitizer.  
* **Easy to reuse** – one service instance (`$swal`) can be imported everywhere.  
* **Full coverage** – alerts, confirmations, prompts, selects, sliders, file uploads, toasts, multi‑step wizards, loading states, etc.  
* **Works with bundlers** (`import Swal from 'sweetalert2'`) **or via CDN** (`window.Swal`).  

All code snippets now contain direct links to the official SweetAlert2 documentation.

---  

## Table of Contents  

1. [Install](#install)  
2. [Security model (important)](#security-model-important)  
3. [Core wrapper (drop‑in module)](#core-wrapper-drop-in-module)  
4. [Common alerts (success/error/info/warning/question)](#common-alerts)  
5. [Confirmations (destructive, double confirm)](#confirmations)  
6. [Prompts (text, email, password, number, url, textarea)](#prompts)  
7. [Choice inputs (select, radio, checkbox)](#choice-inputs)  
8. [Sliders & pickers (range, color, date, time, datetime)](#sliders)  
9. [Files & images (upload, preview)](#files)  
10. [Async + loading (preConfirm, validation)](#async-loading)  
11. [Toasts (success/error/info)](#toasts)  
12. [Queues & multi‑step wizards](#queues)  
13. [More helper dialogs (secure patterns)](#more-helpers)  
14. [Theming & UX tips](#theming)  
15. [Troubleshooting](#troubleshooting)  

---  

## 1. Install  

```bash
npm i sweetalert2          # core library
npm i dompurify            # optional sanitizer for HTML content
```

**CDN (optional)**  

```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3/dist/purify.min.js"></script>
```

> **Security tip** – When using a CDN in production, add SRI and `crossorigin="anonymous"` (hashes are shown on the CDN page).  

*Docs*: **Installation** – https://sweetalert2.github.io/#download  

---  

## 2. Security model (important)  

| Guideline | Why it matters | How the wrapper enforces it |
|-----------|----------------|----------------------------|
| Use `title` / `text` instead of `html` | Prevents XSS | `Swal.fire` is called with `text` unless you explicitly enable HTML (`allowHtml:true` + sanitizer). |
| Only enable HTML when the content is **controlled** or **sanitized** | Unsanitized HTML can execute scripts | The wrapper checks for a supplied `dompurify` instance; otherwise HTML is stripped (`stripTags`). |
| Validate all user‑provided input on the client **and** server | Defense‑in‑depth | Input validators are built into each prompt (`inputValidator`). |
| Prefer `allowOutsideClick: false` for destructive dialogs | Avoid accidental confirm | Set in the `defaultBaseConfig`. |

*Docs*: **Security** – https://sweetalert2.github.io/#security  

---  

## 3. Core wrapper (drop‑in module)  

Create **`swal.js`** (or `sweetalertService.ts`) and import it wherever you need dialogs.

```javascript
// swal.js — SweetAlert2 reusable service (secure by default)
// Docs for SweetAlert2 options: https://sweetalert2.github.io/#configuration
/** @typedef {import('sweetalert2').SweetAlertOptions} SweetAlertOptions */

const defaultBaseConfig = {
  allowOutsideClick: false,
  allowEscapeKey: true,
  showConfirmButton: true,
  confirmButtonText: 'OK',
  focusConfirm: true,
  buttonsStyling: true,
};

function stripTags(input) {
  return String(input || '').replace(/<[^>]*>/g, '');
}

export class SweetAlertService {
  /**
   * @param {{ Swal?: any, defaults?: Partial<SweetAlertOptions>, dompurify?: any, allowHtml?: boolean }} [opts]
   */
  constructor(opts = {}) {
    this.Swal = opts.Swal || (globalThis && globalThis.Swal);
    if (!this.Swal) throw new Error('SweetAlert2 (Swal) not found');
    this.defaults = { ...defaultBaseConfig, ...(opts.defaults || {}) };
    this.dompurify = opts.dompurify || null;
    this.allowHtml = Boolean(opts.allowHtml) && !!this.dompurify;
  }

  /** internal HTML sanitizer */
  _sanitizeHtml(html) {
    if (!html) return undefined;
    if (!this.allowHtml) return undefined;
    const dp = this.dompurify;
    try {
      const sanitize = typeof dp === 'function' ? dp : dp?.sanitize;
      return sanitize ? sanitize(String(html)) : stripTags(html);
    } catch (_) {
      return stripTags(html);
    }
  }

  /** main fire method – respects sanitizer & defaults */
  fire(opts = {}) {
    const { html, text, ...rest } = opts;
    const config = { ...this.defaults, ...rest };
    if (html != null) {
      const safe = this._sanitizeHtml(html);
      if (safe != null) config.html = safe;
      else config.text = stripTags(html);
    } else if (text != null) {
      config.text = String(text);
    }
    return this.Swal.fire(config);
  }

  /* ---------- Alerts ---------- */
  alert({ title = 'Notice', text = '', icon = 'info', ...rest } = {}) {
    return this.fire({ title, text, icon, ...rest });
  }
  success(text = 'Operation completed', title = 'Success', rest = {}) {
    return this.alert({ title, text, icon: 'success', ...rest });
  }
  error(text = 'Something went wrong', title = 'Error', rest = {}) {
    return this.alert({ title, text, icon: 'error', ...rest });
  }
  info(text = '', title = 'Info', rest = {}) {
    return this.alert({ title, text, icon: 'info', ...rest });
  }
  warning(text = '', title = 'Warning', rest = {}) {
    return this.alert({ title, text, icon: 'warning', ...rest });
  }
  question(text = '', title = 'Question', rest = {}) {
    return this.alert({ title, text, icon: 'question', ...rest });
  }

  /* ---------- Confirmations ---------- */
  async confirm({
    title = 'Are you sure?',
    text = 'This action cannot be undone.',
    confirmText = 'Yes',
    cancelText = 'Cancel',
    icon = 'question',
    danger = false,
    ...rest
  } = {}) {
    const res = await this.fire({
      title,
      text,
      icon,
      showCancelButton: true,
      confirmButtonText: confirmText,
      cancelButtonText: cancelText,
      reverseButtons: true,
      focusCancel: true,
      ...(danger ? { confirmButtonColor: '#d33' } : {}),
      ...rest,
    });
    return res.isConfirmed === true;
  }

  // double‑confirm for destructive actions
  async confirmDestructive({
    resourceName = 'item',
    requirePhrase = 'DELETE',
    ...rest
  } = {}) {
    const ok = await this.confirm({
      title: `Delete ${resourceName}?`,
      text: `Type ${requirePhrase} to confirm.`,
      confirmText: 'Continue',
      danger: true,
      ...rest,
    });
    if (!ok) return false;
    const res = await this.fire({
      title: 'Confirm deletion',
      input: 'text',
      inputPlaceholder: requirePhrase,
      inputValidator: (v) =>
        String(v).trim() === requirePhrase
          ? undefined
          : `You must type ${requirePhrase}`,
      showCancelButton: true,
      confirmButtonText: 'Delete',
      focusCancel: true,
      icon: 'warning',
      confirmButtonColor: '#d33',
    });
    return res.isConfirmed === true;
  }

  /* ---------- Prompts (text‑like) ---------- */
  async prompt(cfg = {}) {
    const {
      title = 'Enter a value',
      input = 'text',
      inputLabel,
      inputValue,
      placeholder,
      required = true,
      minLength,
      maxLength,
      pattern,
      ...rest
    } = cfg;

    const inputAttributes = {};
    if (minLength != null) inputAttributes.minLength = String(minLength);
    if (maxLength != null) inputAttributes.maxLength = String(maxLength);
    if (pattern) inputAttributes.pattern = String(pattern);

    const res = await this.fire({
      title,
      input,
      inputLabel,
      inputValue,
      inputPlaceholder: placeholder,
      inputAttributes,
      showCancelButton: true,
      inputValidator: (v) => {
        const s = String(v ?? '');
        if (required && s.trim().length === 0) return 'This field is required';
        if (minLength != null && s.length < minLength)
          return `Min length is ${minLength}`;
        if (maxLength != null && s.length > maxLength)
          return `Max length is ${maxLength}`;
        if (pattern && !(new RegExp(pattern)).test(s))
          return 'Invalid format';
        return undefined;
      },
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }
  promptText(opts = {}) { return this.prompt({ input: 'text', ...opts }); }
  promptEmail(opts = {}) { return this.prompt({ input: 'email', ...opts }); }
  promptPassword(opts = {}) { return this.prompt({ input: 'password', ...opts }); }
  promptNumber(opts = {}) { return this.prompt({ input: 'number', ...opts }); }
  promptUrl(opts = {}) { return this.prompt({ input: 'url', ...opts }); }
  promptTextarea(opts = {}) { return this.prompt({ input: 'textarea', ...opts }); }

  /* ---------- Choice inputs ---------- */
  async select({ title = 'Choose one', options = {}, placeholder = 'Select…', required = true, ...rest } = {}) {
    const safeOptions = Object.fromEntries(
      Object.entries(options).map(([v, l]) => [v, stripTags(l)])
    );
    const res = await this.fire({
      title,
      input: 'select',
      inputOptions: safeOptions,
      inputPlaceholder: placeholder,
      showCancelButton: true,
      inputValidator: (v) => (required && (v == null || v === '')) ? 'Please choose an option' : undefined,
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  async radio({ title = 'Pick one', options = {}, required = true, ...rest } = {}) {
    const safeOptions = Object.fromEntries(
      Object.entries(options).map(([v, l]) => [v, stripTags(l)])
    );
    const res = await this.fire({
      title,
      input: 'radio',
      inputOptions: safeOptions,
      showCancelButton: true,
      inputValidator: (v) => (required && (v == null || v === '')) ? 'Please pick one' : undefined,
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  async checkbox({ title = 'Confirm', label = 'I agree', checked = false, required = false, ...rest } = {}) {
    const res = await this.fire({
      title,
      input: 'checkbox',
      inputPlaceholder: stripTags(label),
      inputValue: checked,
      showCancelButton: true,
      inputValidator: (v) => (required && !v) ? 'You must check the box' : undefined,
      ...rest,
    });
    return res.isConfirmed ? Boolean(res.value) : undefined;
  }

  /* ---------- Sliders & pickers ---------- */
  async range({ title = 'Select value', min = 0, max = 100, step = 1, value, ...rest } = {}) {
    const res = await this.fire({
      title,
      input: 'range',
      inputValue: value ?? Math.round((min + max) / 2),
      inputAttributes: { min: String(min), max: String(max), step: String(step) },
      showCancelButton: true,
      ...rest,
    });
    return res.isConfirmed ? Number(res.value) : undefined;
  }
  color(opts = {}) { return this.prompt({ input: 'color', required: true, ...opts }); }
  date(opts = {}) { return this.prompt({ input: 'date', required: true, ...opts }); }
  time(opts = {}) { return this.prompt({ input: 'time', required: true, ...opts }); }
  datetime(opts = {}) { return this.prompt({ input: 'datetime-local', required: true, ...opts }); }

  /* ---------- Files & images ---------- */
  async file({ title = 'Select file', accept, multiple = false, required = true, ...rest } = {}) {
    const res = await this.fire({
      title,
      input: 'file',
      inputAttributes: { ...(accept ? { accept } : {}), ...(multiple ? { multiple: true } : {}) },
      showCancelButton: true,
      inputValidator: (v) => (required && !v) ? 'Please select a file' : undefined,
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  image({ title = '', text = '', url, alt = 'Image', width, height, ...rest } = {}) {
    return this.fire({
      title,
      text,
      imageUrl: url,
      imageAlt: alt,
      ...(width ? { imageWidth: width } : {}),
      ...(height ? { imageHeight: height } : {}),
      ...rest,
    });
  }

  /* ---------- Async + loading (preConfirm) ---------- */
  async withLoader({
    title = 'Please confirm',
    text = '',
    confirmText = 'Continue',
    preConfirm, // async () => any
    icon = 'question',
    ...rest
  } = {}) {
    if (typeof preConfirm !== 'function') throw new Error('preConfirm must be a function');
    const res = await this.fire({
      title,
      text,
      icon,
      showCancelButton: true,
      confirmButtonText: confirmText,
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !this.Swal.isLoading(),
      preConfirm: async () => {
        try {
          const val = await preConfirm();
          return val;
        } catch (err) {
          const raw = err?.message || 'Request failed';
          const dp = this.dompurify;
          let safeMsg = stripTags(String(raw));
          try {
            if (this.allowHtml && dp) {
              const sanitize = typeof dp === 'function' ? dp : dp?.sanitize;
              if (sanitize) safeMsg = sanitize(String(raw));
            }
          } catch (_) {}
          this.Swal.showValidationMessage(String(safeMsg));
          return false;
        }
      },
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  /* ---------- Toasts ---------- */
  _toastMixin(opts = {}) {
    return this.Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      showCloseButton: true,
      ...opts,
    });
  }
  toast({ title, icon = 'success', ...rest } = {}) {
    return this._toastMixin(rest).fire({ title, icon });
  }
  toastSuccess(title = 'Saved') { return this.toast({ title, icon: 'success' }); }
  toastError(title = 'Error') { return this.toast({ title, icon: 'error' }); }
  toastInfo(title = 'Info') { return this.toast({ title, icon: 'info' }); }

  /* ---------- Queues & steps ---------- */
  async steps(steps = []) {
    const mix = this.Swal.mixin({
      progressSteps: steps.map((_, i) => String(i + 1)),
      confirmButtonText: 'Next',
      showCancelButton: true,
      reverseButtons: true,
    });
    const results = [];
    for (let i = 0; i < steps.length; i++) {
      const { html, text, ...rest } = steps[i] || {};
      const cfg = { ...rest };
      if (html != null) {
        const safe = this._sanitizeHtml(html);
        if (safe != null) cfg.html = safe; else cfg.text = stripTags(html);
      } else if (text != null) cfg.text = String(text);
      const res = await mix.fire({ currentProgressStep: i, ...cfg });
      if (!res.isConfirmed) return undefined;
      results.push(res.value);
    }
    return results;
  }
}

/* Factory – convenient one‑liner */
export function createSweetAlerts({
  Swal,
  defaults,
  dompurify,
  allowHtml = false,
} = {}) {
  return new SweetAlertService({ Swal, defaults, dompurify, allowHtml });
}
```

### Initialise once (ESM)

```javascript
// app-swal.js
import Swal from 'sweetalert2';
import DOMPurify from 'dompurify';
import { createSweetAlerts } from './swal.js';

export const $swal = createSweetAlerts({
  Swal,
  dompurify: DOMPurify,
  allowHtml: false,               // set true only if you *trust* the HTML source
  defaults: {
    confirmButtonColor: '#2563eb', // Tailwind indigo‑600
    cancelButtonColor: '#6b7280', // Tailwind gray‑500
  },
});
```

### CDN usage (no imports)

```javascript
// just after the CDN scripts have loaded
const $swal = new SweetAlertService({
  Swal: window.Swal,
  dompurify: window.DOMPurify,
  allowHtml: false,
});
```

*Documentation*: **SweetAlert2 API reference** – https://sweetalert2.github.io/#configuration  

---  

## 4. Common alerts (success / error / info / warning / question)

```javascript
await $swal.success('Profile saved');
await $swal.error('Failed to save');
await $swal.info('Your session will expire soon');
await $swal.warning('This will overwrite existing data');
await $swal.question('Continue?');

// Custom alert
await $swal.alert({ title: 'Heads up', text: 'Read this carefully', icon: 'info' });
```

*Docs*: **Basic alerts** – https://sweetalert2.github.io/#basic-alert  

---  

## 5. Confirmations (destructive, double confirm)

```javascript
// Simple confirmation
const ok = await $swal.confirm({
  title: 'Archive project?',
  text: 'You can restore it later',
  confirmText: 'Archive',
  icon: 'warning',
});
if (ok) { /* do archive */ }

// Double‑confirm (type phrase then confirm)
const reallyDelete = await $swal.confirmDestructive({
  resourceName: 'project',
  requirePhrase: 'DELETE',
});
if (reallyDelete) { /* irreversible delete */ }
```

*Docs*: **Confirm dialogs** – https://sweetalert2.github.io/#confirm-dialog  

---  

## 6. Prompts (text, email, password, number, url, textarea)

```javascript
const name = await $swal.promptText({ title: 'Your name', minLength: 2 });
const email = await $swal.promptEmail({ title: 'Email', pattern: '^.+@.+\\..+$' });
const pwd = await $swal.promptPassword({ title: 'New password', minLength: 8 });
const age = await $swal.promptNumber({ title: 'Age', required: false });
const site = await $swal.promptUrl({ title: 'Website', required: false });
const notes = await $swal.promptTextarea({ title: 'Notes', maxLength: 500, required: false });
```

*Docs*: **Input dialogs** – https://sweetalert2.github.io/#input-dialog  

---  

## 7. Choice inputs (select, radio, checkbox)

```javascript
// Dropdown
const color = await $swal.select({
  title: 'Pick a color',
  options: { red: 'Red', green: 'Green', blue: 'Blue' },
});

// Radio list
const size = await $swal.radio({
  title: 'Size',
  options: { s: 'Small', m: 'Medium', l: 'Large' },
});

// Simple checkbox
const agreed = await $swal.checkbox({
  title: 'Terms',
  label: 'I agree to the terms',
  required: true,
});
```

*Docs*: **Select, radio, checkbox** – https://sweetalert2.github.io/#input-types  

---  

## 8. Sliders and pickers (range, color, date, time, datetime)

```javascript
const volume = await $swal.range({ title: 'Volume', min: 0, max: 100, step: 5, value: 50 });
const themeColor = await $swal.color({ title: 'Theme color' });
const meetingDate = await $swal.date({ title: 'Meeting date' });
const meetingTime = await $swal.time({ title: 'Meeting time' });
const when = await $swal.datetime({ title: 'When' });
```

*Docs*: **Range & other HTML5 inputs** – https://sweetalert2.github.io/#input-types  

---  

## 9. Files and images (file upload, image preview)

```javascript
// Single image upload
const avatar = await $swal.file({ title: 'Upload avatar', accept: 'image/*' });

if (avatar) {
  // preview using the built‑in image dialog
  await $swal.image({ title: 'Preview', url: URL.createObjectURL(avatar), width: 200 });
}
```

*Docs*: **File input** – https://sweetalert2.github.io/#input-types  

---  

## 10. Async + loading states (preConfirm, showValidationMessage)

```javascript
const data = await $swal.withLoader({
  title: 'Fetch user?',
  text: 'We will fetch from the server',
  confirmText: 'Fetch',
  preConfirm: async () => {
    const r = await fetch('/api/user', { credentials: 'same-origin' });
    if (!r.ok) throw new Error('Network error');
    return r.json();
  },
});

if (data) $swal.success('User loaded');
```

*Docs*: **preConfirm & loader** – https://sweetalert2.github.io/#preconfirm  

---  

## 11. Toasts (success / error / info)

```javascript
$swal.toastSuccess('Saved');
$swal.toastError('Oops');
$swal.toastInfo('Heads up');

// Custom toast
$swal.toast({ title: 'Bottom left', icon: 'info', position: 'bottom-start', timer: 4000 });
```

*Docs*: **Toast notifications** – https://sweetalert2.github.io/#toast  

---  

## 12. Queues & multi‑step wizards

```javascript
const results = await $swal.steps([
  { title: 'Step 1', input: 'text', inputLabel: 'Name' },
  { title: 'Step 2', input: 'email', inputLabel: 'Email' },
  { title: 'Step 3', input: 'password', inputLabel: 'Password' },
]);

if (results) {
  const [name, email, pwd] = results;
  // handle data
}
```

*Docs*: **Queue & mixin** – https://sweetalert2.github.io/#queue  

---  

## 13. More helper dialogs (secure patterns)

Below are **ready‑to‑copy** recipes that showcase a secure implementation for typical UI needs.

### Login (email + password) with client‑side validation  

```javascript
const creds = await $swal.withLoader({
  title: 'Sign in',
  text: 'Enter your credentials',
  confirmText: 'Sign in',
  icon: 'question',
  willOpen: () => {
    const box = $swal.Swal.getHtmlContainer();
    const form = document.createElement('form');
    form.setAttribute('novalidate', '');

    // Email field
    const lblEmail = document.createElement('label');
    lblEmail.textContent = 'Email';
    const inpEmail = document.createElement('input');
    inpEmail.type = 'email';
    inpEmail.id = 'login-email';
    inpEmail.required = true;
    lblEmail.appendChild(inpEmail);

    // Password field
    const lblPass = document.createElement('label');
    lblPass.textContent = 'Password';
    const inpPass = document.createElement('input');
    inpPass.type = 'password';
    inpPass.id = 'login-pass';
    inpPass.required = true;
    inpPass.minLength = 8;
    lblPass.appendChild(inpPass);

    form.appendChild(lblEmail);
    form.appendChild(lblPass);
    box.appendChild(form);
  },
  preConfirm: async () => {
    const email = document.getElementById('login-email')?.value?.trim();
    const pass = document.getElementById('login-pass')?.value || '';
    if (!email) return 'Email required';
    if (!/.+@.+\..+/.test(email)) return 'Invalid email';
    if (pass.length < 8) return 'Password must be ≥8 chars';

    const r = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X‑CSRF‑Token': window.csrfToken || '',
      },
      body: JSON.stringify({ email, pass }),
      credentials: 'same-origin',
    });
    if (!r.ok) throw new Error('Invalid credentials');
    return r.json();
  },
});

if (creds) $swal.toastSuccess('Signed in');
```

*Docs*: **Custom HTML with willOpen** – https://sweetalert2.github.io/#custom-html  

### Re‑auth before a sensitive action (password confirm)  

```javascript
const token = await $swal.withLoader({
  title: 'Re‑authenticate',
  text: 'Enter your password to continue',
  confirmText: 'Confirm',
  icon: 'warning',
  preConfirm: async () => {
    const pwd = await $swal.promptPassword({ title: 'Password', minLength: 8 });
    if (!pwd) throw new Error('Cancelled');
    const r = await fetch('/api/re-auth', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X‑CSRF‑Token': window.csrfToken || '' },
      body: JSON.stringify({ password: pwd }),
      credentials: 'same-origin',
    });
    if (!r.ok) throw new Error('Authentication failed');
    const { sessionToken } = await r.json();
    return sessionToken;
  },
});

if (token) {
  // proceed with the privileged operation
}
```

*Docs*: **preConfirm async** – https://sweetalert2.github.io/#preconfirm  

### Unsaved‑changes protection (navigation/close)  

```javascript
let dirty = false; // set true when form changes

window.addEventListener('beforeunload', (e) => {
  if (!dirty) return;
  e.preventDefault();
  e.returnValue = '';
});

async function confirmLeave() {
  if (!dirty) return true;
  return await $swal.confirm({
    title: 'Discard changes?',
    text: 'You have unsaved changes',
    confirmText: 'Discard',
    icon: 'warning',
  });
}
```

*Docs*: **beforeunload** – standard browser API (no SweetAlert2 link).  

### Three‑option choice: Save / Don’t save / Cancel  

```javascript
const res = await $swal.fire({
  title: 'Save changes?',
  text: 'Choose what to do',
  icon: 'question',
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: 'Save',
  denyButtonText: "Don't save",
  reverseButtons: true,
});

if (res.isConfirmed) {
  // save
} else if (res.isDenied) {
  // discard
} // cancel → do nothing
```

*Docs*: **Deny button** – https://sweetalert2.github.io/#deny-button  

### Date‑range picker (no HTML strings)  

```javascript
const range = await $swal.withLoader({
  title: 'Pick a date range',
  confirmText: 'Apply',
  icon: 'question',
  willOpen: () => {
    const box = $swal.Swal.getHtmlContainer();
    const wrapper = document.createElement('div');
    wrapper.style.display = 'grid';
    wrapper.style.gap = '8px';

    const start = document.createElement('input');
    start.type = 'date';
    start.id = 'start-date';
    const end = document.createElement('input');
    end.type = 'date';
    end.id = 'end-date';

    wrapper.appendChild(start);
    wrapper.appendChild(end);
    box.appendChild(wrapper);
  },
  preConfirm: () => {
    const s = document.getElementById('start-date')?.value;
    const e = document.getElementById('end-date')?.value;
    if (!s || !e) return 'Both dates required';
    if (new Date(s) > new Date(e)) return 'Start must be before end';
    return { start: s, end: e };
  },
});
```

*Docs*: **willOpen** – https://sweetalert2.github.io/#custom-html  

### File upload with size/type validation and CSRF  

```javascript
const avatar = await $swal.file({ title: 'Upload avatar', accept: 'image/*' });
if (avatar) {
  if (!/^image\//.test(avatar.type)) {
    await $swal.error('Only images are allowed');
  } else if (avatar.size > 5 * 1024 * 1024) {
    await $swal.error('Max 5 MB');
  } else {
    await $swal.withLoader({
      title: 'Uploading…',
      preConfirm: async () => {
        const fd = new FormData();
        fd.append('avatar', avatar);
        const r = await fetch('/api/avatar', {
          method: 'POST',
          body: fd,
          headers: { 'X‑CSRF‑Token': window.csrfToken || '' },
          credentials: 'same-origin',
        });
        if (!r.ok) throw new Error('Upload failed');
        return true;
      },
    });
    $swal.toastSuccess('Uploaded');
  }
}
```

*Docs*: **File input** – https://sweetalert2.github.io/#input-types  

### Remote select options (loaded safely)  

```javascript
const res = await $swal.fire({
  title: 'Pick a project',
  input: 'select',
  inputOptions: (async () => {
    const r = await fetch('/api/projects', { credentials: 'same-origin' });
    if (!r.ok) throw new Error('Failed to load');
    const items = await r.json(); // [{id, name}]
    const clean = (s) => String(s || '').replace(/<[^>]*>/g, '');
    return Object.fromEntries(items.map(p => [p.id, clean(p.name)]));
  })(),
  inputPlaceholder: 'Select…',
  showCancelButton: true,
  inputValidator: (v) => (!v ? 'Please choose a project' : undefined),
});
if (res.isConfirmed) {
  const projectId = res.value;
}
```

*Docs*: **inputOptions (Promise)** – https://sweetalert2.github.io/#input-options  

### Two‑factor code (6 digits)  

```javascript
const code = await $swal.prompt({
  title: 'Enter 2FA code',
  input: 'text',
  minLength: 6,
  maxLength: 6,
  pattern: '^\\d{6}$',
  placeholder: '123456',
});
if (code) {
  // verify on server
}
```

*Docs*: **inputValidator** – https://sweetalert2.github.io/#input-validator  

### Destructive with countdown unlock (accident prevention)  

```javascript
let remaining = 5;
const res = await $swal.fire({
  title: 'Delete account?',
  text: `Confirm will unlock in ${remaining}s`,
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Delete',
  didOpen: () => {
    const btn = $swal.Swal.getConfirmButton();
    btn.disabled = true;
    const timer = setInterval(() => {
      remaining -= 1;
      $swal.Swal.update({ text: `Confirm will unlock in ${remaining}s` });
      if (remaining <= 0) {
        clearInterval(timer);
        btn.disabled = false;
        $swal.Swal.update({ text: 'This action is irreversible' });
      }
    }, 1000);
  },
  willClose: () => { /* clear interval if needed */ },
});
if (res.isConfirmed) {
  // proceed
}
```

*Docs*: **didOpen** – https://sweetalert2.github.io/#custom-html  

### Toast with **Undo** action  

```javascript
const Toast = $swal.Swal.mixin({
  toast: true,
  position: 'top-end',
  timer: 4000,
  showConfirmButton: true,
  confirmButtonText: 'Undo',
  showCloseButton: true,
});

const t = await Toast.fire({ icon: 'success', title: 'Item archived' });
if (t.isConfirmed) {
  // undo the archive
}
```

*Docs*: **Toast mixin** – https://sweetalert2.github.io/#toast  

---

### Security checklist for all the above patterns  

| Practice | Reason | Implementation |
|----------|--------|----------------|
| **Never render raw HTML** unless you explicitly enable it with a sanitizer. | Prevent XSS. | Wrapper strips HTML; `allowHtml` requires `dompurify`. |
| **Validate client‑side AND server‑side**. | Client validation is convenience only. | All prompts use `inputValidator`; server must re‑check. |
| **Include CSRF tokens** for POST/PUT/DELETE. | Protect against cross‑site request forgery. | Example snippets send `'X‑CSRF‑Token'`. |
| **Use `focusCancel: true`** for confirmations. | Reduces accidental Enter confirmation. | Set in wrapper’s default config (`reverseButtons`, `focusCancel`). |
| **Add a short delay or typed phrase** for destructive actions. | Provides a mental pause. | `confirmDestructive` and countdown examples. |
| **Disable outside clicks while loading** (`allowOutsideClick: () => !Swal.isLoading()`). | Avoid cancelling a pending async operation. | Implemented in `withLoader`. |

*Docs*: **Security** – https://sweetalert2.github.io/#security  

---  

## 14. Theming & UX tips  

| Tip | How to apply (code) |
|-----|---------------------|
| **Consistent button order** – Cancel / Confirm (or reverse for destructive) | `reverseButtons: true`, `focusCancel: true` (already in defaults). |
| **Danger color for destructive confirm** | `confirmButtonColor: '#d33'` (see `confirmDestructive`). |
| **Accessibility** – set `aria-label` via `title` / `text` (no HTML). | Use plain `title`/`text` options. |
| **Keep dialogs short** – avoid long scrollable content. | Break large forms into steps (`steps` method). |
| **Use toasts for non‑blocking feedback** | `$swal.toastSuccess('Saved')`. |
| **Add keyboard shortcuts** – `Esc` to cancel (default). | `allowEscapeKey: true` (default). |
| **Match your UI theme** – custom CSS class via `customClass`. | Pass `{ customClass: { popup: 'my-popup' } }`. |
| **Avoid duplicate dialogs** – central service (`$swal`). | Import `$swal` everywhere instead of creating new instances. |

---  

## 15. Troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `Swal is not defined` | CDN not loaded or import missing. | Ensure `<script src="…sweetalert2.min.js"></script>` runs before your script, or `import Swal from 'sweetalert2'`. |
| HTML is rendered unsanitized | `allowHtml` set to `true` without a sanitizer. | Remove `allowHtml` or provide a valid `dompurify` instance. |
| `showValidationMessage` never appears | `preConfirm` returns `false` instead of throwing. | Throw an `Error` or return a rejected Promise; the wrapper catches it and calls `showValidationMessage`. |
| Buttons stay disabled after loading | `allowOutsideClick` still returns `true` while loading. | Use the wrapper’s built‑in `allowOutsideClick: () => !Swal.isLoading()`. |
| Prompt returns `undefined` even after OK | `inputValidator` returned a non‑undefined string (treated as error). | Ensure validator returns `undefined` (no error) on success. |
| Toast stays on screen forever | `timer` not set or `timerProgressBar` disabled. | Use `timer: 2500` (default in `_toastMixin`). |

*Docs*: **Common issues** – https://sweetalert2.github.io/#faq  

---  

### Quick import example (ESM)

```javascript
// main.js (your app entry)
import { $swal } from './app-swal.js';

// Example usage somewhere in the UI
document.getElementById('delete-btn')?.addEventListener('click', async () => {
  const confirmed = await $swal.confirmDestructive({ resourceName: 'record' });
  if (confirmed) {
    // delete via API…
    $swal.toastSuccess('Record removed');
  }
});
```

---  

## Reference – SweetAlert2 Documentation  

| Feature | Official doc link |
|---------|-------------------|
| **Installation** | https://sweetalert2.github.io/#download |
| **Configuration & defaults** | https://sweetalert2.github.io/#configuration |
| **Basic alerts** | https://sweetalert2.github.io/#basic-alert |
| **Confirm dialogs** | https://sweetalert2.github.io/#confirm-dialog |
| **Input dialogs** (text, email, password, etc.) | https://sweetalert2.github.io/#input-dialog |
| **Select / radio / checkbox** | https://sweetalert2.github.io/#input-types |
| **File input** | https://sweetalert2.github.io/#input-types |
| **preConfirm & loader** | https://sweetalert2.github.io/#preconfirm |
| **Toast notifications** | https://sweetalert2.github.io/#toast |
| **Queue / mixins** | https://sweetalert2.github.io/#queue |
| **Custom HTML (willOpen / didOpen)** | https://sweetalert2.github.io/#custom-html |
| **Security / XSS** | https://sweetalert2.github.io/#security |
| **FAQ / common issues** | https://sweetalert2.github.io/#faq |

You now have a **complete, copy‑paste‑ready set of SweetAlert2 recipes** that are **secure**, **re‑usable**, **bundler‑friendly**, and **well‑documented**. Happy alert building!