# SweetAlert2 — Reusable, Secure Dialogs (Copy‑paste recipes)

Practical patterns for SweetAlert2 with a reusable class and helpers that are:

- Safe by default (no unsanitized HTML)
- Easy to reuse across apps
- Cover the most common dialog types (alerts, confirm, prompts, selects, file, range, date/time, image, toasts, queues/steps, loaders)
- Optimized for a clean UX and accessible defaults

Works with bundlers (import from `sweetalert2`) or via CDN (`window.Swal`).

## Table of Contents

- Install
- Security model (important)
- Core wrapper (drop‑in module)
- Common alerts (success/error/info/warning/question)
- Confirmations (destructive, double confirm)
- Prompts (text, email, password, number, url, textarea)
- Choice inputs (select, radio, checkbox)
- Sliders and pickers (range, color, date, time, datetime)
- Files and images (file upload, image preview)
- Async + loading states (preConfirm, showValidationMessage)
- Toasts (success/error/info)
- Queues & multi‑step wizards
- More helper dialogs (secure patterns)
- Theming & UX tips
- Troubleshooting

---

## Install

```bash
npm i sweetalert2
# optional, recommended if you will display custom HTML content
npm i dompurify
```

CDN (optional):

```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- optional sanitizer -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
```

> Security tip: When using CDNs in production, prefer Subresource Integrity (SRI) and `crossorigin="anonymous"`. Obtain the correct `integrity` hashes from the CDN and add them to your script tags.

## Security model (important)

- Prefer using `title` and `text` options instead of `html` to avoid XSS.
- Only enable HTML when the content is controlled or sanitized.
- If you must show HTML, sanitize it (e.g., with DOMPurify) before passing to SweetAlert2.
- This guide’s wrapper disables `html` by default and falls back to plain text.

## Core wrapper (drop‑in module)

Create a `swal.js` (or similar) module using the class below. It works with ESM bundlers and also with a global CDN `window.Swal`.

```javascript
// swal.js — SweetAlert2 reusable service (secure by default)
// Usage: import Swal from 'sweetalert2';
// Optional: import DOMPurify from 'dompurify';

/** @typedef {import('sweetalert2').SweetAlertOptions} SweetAlertOptions */

const defaultBaseConfig = {
  // sensible defaults for UX and accessibility
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
   * @param {{
   *  Swal?: any,
   *  defaults?: Partial<SweetAlertOptions>,
   *  dompurify?: any,
   *  allowHtml?: boolean,
   * }} [opts]
   */
  constructor(opts = {}) {
    this.Swal = opts.Swal || (globalThis && globalThis.Swal);
    if (!this.Swal) throw new Error('SweetAlert2 (Swal) not found');
    this.defaults = { ...defaultBaseConfig, ...(opts.defaults || {}) };
    this.dompurify = opts.dompurify || null;
    this.allowHtml = Boolean(opts.allowHtml) && !!this.dompurify; // only allow when sanitizer present
  }

  _sanitizeHtml(html) {
    if (!html) return undefined;
    if (!this.allowHtml) return undefined; // blocked unless sanitizer provided and enabled
    const dp = this.dompurify;
    try {
      // DOMPurify can be a function or have .sanitize
      const sanitize = typeof dp === 'function' ? dp : dp?.sanitize;
      return sanitize ? sanitize(String(html)) : stripTags(html);
    } catch (_) {
      return stripTags(html);
    }
  }

  /** @param {SweetAlertOptions} opts */
  fire(opts = {}) {
    const { html, text, ...rest } = opts;
    const config = { ...this.defaults, ...rest };
    if (html != null) {
      const safe = this._sanitizeHtml(html);
      if (safe != null) config.html = safe; else config.text = stripTags(html);
    } else if (text != null) {
      config.text = String(text);
    }
    return this.Swal.fire(config);
  }

  // ---------- Alerts ----------
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

  // ---------- Confirmations ----------
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

  // Optional second step confirmation (type AND click)
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
      inputValidator: (v) => (String(v).trim() === requirePhrase ? undefined : `You must type ${requirePhrase}`),
      showCancelButton: true,
      confirmButtonText: 'Delete',
      focusCancel: true,
      icon: 'warning',
      confirmButtonColor: '#d33',
    });
    return res.isConfirmed === true;
  }

  // ---------- Prompts (text-like) ----------
  /** @param {{
   *  title?: string,
   *  input?: 'text'|'email'|'password'|'number'|'url'|'textarea',
   *  inputLabel?: string,
   *  inputValue?: string|number,
   *  placeholder?: string,
   *  required?: boolean,
   *  minLength?: number,
   *  maxLength?: number,
   *  pattern?: string,
   * }} cfg
   */
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
        if (minLength != null && s.length < minLength) return `Min length is ${minLength}`;
        if (maxLength != null && s.length > maxLength) return `Max length is ${maxLength}`;
        if (pattern && !(new RegExp(pattern)).test(s)) return 'Invalid format';
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

  // ---------- Choice inputs ----------
  /** Single-select dropdown */
  async select({ title = 'Choose one', options = {}, placeholder = 'Select…', required = true, ...rest } = {}) {
    // sanitize labels to avoid accidental HTML rendering in option text
    const safeOptions = Object.fromEntries(
      Object.entries(options).map(([value, label]) => [value, stripTags(label)])
    );
    const res = await this.fire({
      title,
      input: 'select',
      inputOptions: safeOptions, // { value: label }
      inputPlaceholder: placeholder,
      showCancelButton: true,
      inputValidator: (v) => (required && (v == null || v === '')) ? 'Please choose an option' : undefined,
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  /** Single-choice radio */
  async radio({ title = 'Pick one', options = {}, required = true, ...rest } = {}) {
    const safeOptions = Object.fromEntries(
      Object.entries(options).map(([value, label]) => [value, stripTags(label)])
    );
    const res = await this.fire({
      title,
      input: 'radio',
      inputOptions: safeOptions, // { value: label }
      showCancelButton: true,
      inputValidator: (v) => (required && (v == null || v === '')) ? 'Please pick one' : undefined,
      ...rest,
    });
    return res.isConfirmed ? res.value : undefined;
  }

  /** Boolean checkbox */
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

  // ---------- Sliders & pickers ----------
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

  // ---------- Files & images ----------
  /** File input; returns File or FileList when multiple */
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

  /** Safe image preview (URL should be trusted) */
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

  // ---------- Async + loading ----------
  /** Runs an async action on confirm with loader and validation message on error */
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

  // ---------- Toasts ----------
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
  toast({ title, icon = 'success', ...rest } = {}) { return this._toastMixin(rest).fire({ title, icon }); }
  toastSuccess(title = 'Saved') { return this.toast({ title, icon: 'success' }); }
  toastError(title = 'Error') { return this.toast({ title, icon: 'error' }); }
  toastInfo(title = 'Info') { return this.toast({ title, icon: 'info' }); }

  // ---------- Queues & steps ----------
  /** Simple multi-step wizard; steps is an array of SweetAlertOptions */
  async steps(steps = []) {
    const mix = this.Swal.mixin({
      progressSteps: steps.map((_, i) => String(i + 1)),
      confirmButtonText: 'Next',
      showCancelButton: true,
      reverseButtons: true,
    });
    /** @type {any[]} */
    const results = [];
    for (let i = 0; i < steps.length; i++) {
      const s = steps[i];
      // sanitize html/text to avoid bypassing wrapper's fire()
      const { html, text, ...rest } = s || {};
      const stepCfg = { ...rest };
      if (html != null) {
        const safe = this._sanitizeHtml(html);
        if (safe != null) stepCfg.html = safe; else stepCfg.text = stripTags(html);
      } else if (text != null) {
        stepCfg.text = String(text);
      }
      const res = await mix.fire({ currentProgressStep: i, ...stepCfg });
      if (!res.isConfirmed) return undefined;
      results.push(res.value);
    }
    return results;
  }
}

// Factory with safe defaults
export function createSweetAlerts({ Swal, defaults, dompurify, allowHtml = false } = {}) {
  return new SweetAlertService({ Swal, defaults, dompurify, allowHtml });
}
```

Initialize once and reuse:

```javascript
// app-swal.js
import Swal from 'sweetalert2';
import DOMPurify from 'dompurify';
import { createSweetAlerts } from './swal.js';

export const $swal = createSweetAlerts({
  Swal,
  dompurify: DOMPurify,
  allowHtml: false, // flip to true if you pass sanitized html
  defaults: {
    confirmButtonColor: '#2563eb', // Tailwind indigo-600
    cancelButtonColor: '#6b7280',  // Tailwind gray-500
  },
});
```

If using the CDN, skip imports and do:

```javascript
// global
const $swal = new SweetAlertService({ Swal: window.Swal, dompurify: window.DOMPurify, allowHtml: false });
```

---

## Common alerts (success/error/info/warning/question)

```javascript
await $swal.success('Profile saved');
await $swal.error('Failed to save');
await $swal.info('Your session will expire soon');
await $swal.warning('This will overwrite existing data');
await $swal.question('Continue?');

// Custom
await $swal.alert({ title: 'Heads up', text: 'Read this carefully', icon: 'info' });
```

## Confirmations (destructive, double confirm)

```javascript
const okay = await $swal.confirm({
  title: 'Archive project?',
  text: 'You can restore it later',
  confirmText: 'Archive',
  icon: 'warning',
});
if (okay) {
  // perform action
}

const reallyDelete = await $swal.confirmDestructive({ resourceName: 'project', requirePhrase: 'DELETE' });
if (reallyDelete) {
  // irreversible delete
}
```

## Prompts (text, email, password, number, url, textarea)

```javascript
const name = await $swal.promptText({ title: 'Your name', minLength: 2 });
const email = await $swal.promptEmail({ title: 'Email', pattern: '^.+@.+\\..+$' });
const pwd = await $swal.promptPassword({ title: 'New password', minLength: 8 });
const age = await $swal.promptNumber({ title: 'Age', required: false });
const site = await $swal.promptUrl({ title: 'Website', required: false });
const notes = await $swal.promptTextarea({ title: 'Notes', maxLength: 500, required: false });
```

## Choice inputs (select, radio, checkbox)

```javascript
const color = await $swal.select({
  title: 'Pick a color',
  options: { red: 'Red', green: 'Green', blue: 'Blue' },
});

const size = await $swal.radio({
  title: 'Size',
  options: { s: 'Small', m: 'Medium', l: 'Large' },
});

const agreed = await $swal.checkbox({ title: 'Terms', label: 'I agree to the terms', required: true });
```

## Sliders and pickers (range, color, date, time, datetime)

```javascript
const volume = await $swal.range({ title: 'Volume', min: 0, max: 100, step: 5, value: 50 });
const themeColor = await $swal.color({ title: 'Theme color' });
const meetingDate = await $swal.date({ title: 'Meeting date' });
const meetingTime = await $swal.time({ title: 'Meeting time' });
const when = await $swal.datetime({ title: 'When' });
```

## Files and images (file upload, image preview)

```javascript
// Single file
const file = await $swal.file({ title: 'Upload avatar', accept: 'image/*' });

// Multiple files
const files = await $swal.file({ title: 'Attach files', multiple: true, required: false });

// Image preview (URL should be trusted)
await $swal.image({ title: 'Preview', url: '/images/sample.png', alt: 'Sample image', width: 480 });
```

## Async + loading states (preConfirm, showValidationMessage)

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

if (data) {
  await $swal.success('User loaded');
}
```

## Toasts (success/error/info)

```javascript
$swal.toastSuccess('Saved');
$swal.toastError('Oops');
$swal.toastInfo('Heads up');

// Custom position/duration
$swal.toast({ title: 'Bottom left', icon: 'info', position: 'bottom-start', timer: 4000 });
```

## Queues & multi‑step wizards

```javascript
const results = await $swal.steps([
  { title: 'Step 1', input: 'text', inputLabel: 'Name' },
  { title: 'Step 2', input: 'email', inputLabel: 'Email' },
  { title: 'Step 3', input: 'password', inputLabel: 'Password' },
]);

if (results) {
  const [name, email, pass] = results;
  // handle
}
```

## More helper dialogs (secure patterns)

These focus on common needs and safe defaults: avoid unsanitized HTML, validate input, handle CSRF, and keep users from confirming by accident.

Note: In willOpen/didOpen callbacks we reference `Swal` helpers like `Swal.getHtmlContainer()`. Ensure `Swal` is available (CDN global) or imported in your module (`import Swal from 'sweetalert2'`).

### Login (email + password) with validation

```javascript
const creds = await $swal.withLoader({
  title: 'Sign in',
  text: 'Enter your credentials',
  confirmText: 'Sign in',
  icon: 'question',
  // Build form with DOM APIs (no unsanitized HTML)
  willOpen: () => {
    const box = Swal.getHtmlContainer();
    const form = document.createElement('form');
    form.setAttribute('novalidate', '');

    const lblEmail = document.createElement('label');
    lblEmail.style.display = 'block';
    lblEmail.style.marginBottom = '6px';
    lblEmail.textContent = 'Email';
    const inpEmail = document.createElement('input');
    inpEmail.type = 'email';
    inpEmail.id = 'login-email';
    inpEmail.required = true;
    inpEmail.style.width = '100%';
    inpEmail.style.padding = '.5rem';
    inpEmail.style.marginTop = '.25rem';
    lblEmail.appendChild(inpEmail);

    const lblPass = document.createElement('label');
    lblPass.style.display = 'block';
    lblPass.textContent = 'Password';
    const inpPass = document.createElement('input');
    inpPass.type = 'password';
    inpPass.id = 'login-pass';
    inpPass.required = true;
    inpPass.minLength = 8;
    inpPass.style.width = '100%';
    inpPass.style.padding = '.5rem';
    inpPass.style.marginTop = '.25rem';
    lblPass.appendChild(inpPass);

    form.appendChild(lblEmail);
    form.appendChild(lblPass);
    box.appendChild(form);
  },
  preConfirm: async () => {
    const email = document.getElementById('login-email')?.value?.trim();
    const pass = document.getElementById('login-pass')?.value || '';
    if (!email) return 'Email is required';
    if (!/.+@.+\..+/.test(email)) return 'Enter a valid email';
    if (pass.length < 8) return 'Password must be at least 8 characters';
    // Example POST with CSRF
    const r = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': window.csrfToken || '',
      },
      body: JSON.stringify({ email, pass }),
      credentials: 'same-origin',
    });
    if (!r.ok) throw new Error('Invalid credentials');
    return r.json();
  },
});

if (creds) {
  $swal.toastSuccess('Signed in');
}
```

### Re-auth before sensitive action (password confirm)

```javascript
const token = await $swal.withLoader({
  title: 'Re-authenticate',
  text: 'Please confirm your password to continue',
  confirmText: 'Confirm',
  icon: 'warning',
  preConfirm: async () => {
    const v = await $swal.promptPassword({ title: 'Password', minLength: 8 });
    if (!v) throw new Error('Cancelled');
    const r = await fetch('/api/re-auth', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.csrfToken || '' },
      body: JSON.stringify({ password: v }),
      credentials: 'same-origin',
    });
    if (!r.ok) throw new Error('Authentication failed');
    const { sessionToken } = await r.json();
    return sessionToken;
  },
});

if (token) {
  // proceed with sensitive action using token
}
```

### Unsaved changes protection (navigation/close)

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
// Example: call confirmLeave() on route change click
```

### Three-option choice: Save / Don’t save / Cancel

```javascript
const res = await $swal.fire({
  title: 'Save changes?',
  text: 'Choose what to do',
  icon: 'question',
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: 'Save',
  denyButtonText: `Don't save`,
  reverseButtons: true,
});

if (res.isConfirmed) {
  // save
} else if (res.isDenied) {
  // discard
} // cancel => do nothing
```

### Date range (start/end) without HTML strings

```javascript
const range = await $swal.withLoader({
  title: 'Pick a date range',
  confirmText: 'Apply',
  icon: 'question',
  willOpen: () => {
    const box = Swal.getHtmlContainer();
    const wrap = document.createElement('div');
    wrap.style.display = 'grid';
    wrap.style.gap = '8px';
    const start = document.createElement('input');
    start.type = 'date';
    start.id = 'start-date';
    const end = document.createElement('input');
    end.type = 'date';
    end.id = 'end-date';
    wrap.appendChild(start);
    wrap.appendChild(end);
    box.appendChild(wrap);
  },
  preConfirm: () => {
    const s = document.getElementById('start-date')?.value;
    const e = document.getElementById('end-date')?.value;
    if (!s || !e) return 'Both dates are required';
    if (new Date(s) > new Date(e)) return 'Start must be before end';
    return { start: s, end: e };
  },
});
```

### File upload with type/size validation and CSRF

```javascript
const avatar = await $swal.file({ title: 'Upload avatar', accept: 'image/*' });
if (avatar) {
  const file = avatar; // File
  if (!/^image\//.test(file.type)) {
    await $swal.error('Only images are allowed');
  } else if (file.size > 5 * 1024 * 1024) {
    await $swal.error('Max 5 MB');
  } else {
    await $swal.withLoader({
      title: 'Uploading…',
      preConfirm: async () => {
        const fd = new FormData();
        fd.append('avatar', file);
        const r = await fetch('/api/avatar', { method: 'POST', body: fd, headers: { 'X-CSRF-Token': window.csrfToken || '' }, credentials: 'same-origin' });
        if (!r.ok) throw new Error('Upload failed');
        return true;
      },
    });
    $swal.toastSuccess('Uploaded');
  }
}
```

### Remote select options (loaded safely)

```javascript
const res = await $swal.fire({
  title: 'Pick a project',
  input: 'select',
  inputOptions: (async () => {
    const r = await fetch('/api/projects', { credentials: 'same-origin' });
    if (!r.ok) throw new Error('Failed to load');
    const items = await r.json();
    // return { value: label } with label sanitized to avoid accidental HTML injection
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

### Two-factor code (6 digits)

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
  // verify code server-side
}
```

### Destructive with countdown unlock (accident prevention)

```javascript
let remaining = 5;
const timerId = { v: null };
const res = await $swal.fire({
  title: 'Delete account?',
  text: `Confirm will unlock in ${remaining}s`,
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Delete',
  didOpen: () => {
    const btn = Swal.getConfirmButton();
    btn.disabled = true;
    timerId.v = setInterval(() => {
      remaining -= 1;
      Swal.update({ text: `Confirm will unlock in ${remaining}s` });
      if (remaining <= 0) {
        clearInterval(timerId.v);
        btn.disabled = false;
        Swal.update({ text: 'This action is irreversible' });
      }
    }, 1000);
  },
  willClose: () => { if (timerId.v) clearInterval(timerId.v); },
});
if (res.isConfirmed) {
  // proceed with deletion
}
```

### Toast with Undo action

```javascript
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  timer: 4000,
  showConfirmButton: true,
  confirmButtonText: 'Undo',
  showCloseButton: true,
});
const t = await Toast.fire({ icon: 'success', title: 'Item archived' });
if (t.isConfirmed) {
  // undo archive
}
```

Security tips for these patterns:

- Prefer DOM creation in willOpen over passing raw HTML. If you must use HTML strings, sanitize with DOMPurify and enable `allowHtml` in the service.
- Validate and bound-check all user inputs (length, pattern, ranges). Never trust client-only checks—verify on the server too.
- Include CSRF tokens and send credentials with same-origin requests where applicable.
- Disable confirm or use countdowns for destructive actions to reduce accidental clicks.
- Never echo server error bodies directly into `html`; show generic messages or sanitize first.

## Theming & UX tips

- Keep confirm as primary and cancel as secondary; use `reverseButtons: true` to avoid accidental confirms.
- For destructive actions, set `confirmButtonColor` to a danger color and use `confirmDestructive`.
- Use `focusCancel: true` to steer accidental Enter presses away from confirming.
- For long operations, use `withLoader` and set `allowOutsideClick: () => !Swal.isLoading()`.
- Prefer `text` over `html`. If you must show HTML, sanitize and explicitly set `allowHtml: true` when creating the service.
- Use `customClass` and your CSS framework to align visuals with your app.

## Troubleshooting

- “Swal not found”: ensure `import Swal from 'sweetalert2'` or that the CDN script is loaded before your code.
- HTML not rendering: by design, this wrapper disables HTML unless you pass a sanitizer and set `allowHtml: true`.
- Validation not showing: use `showValidationMessage` inside `preConfirm`, or return a string from `inputValidator`.
- Type errors in editors: add JSDoc types as shown or use TypeScript definitions from `sweetalert2`.

---

Copy any block into your project and adapt names/paths as needed.
