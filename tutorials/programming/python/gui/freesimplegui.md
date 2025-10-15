**FreeSimpleGUI – Practical Beginner‑Friendly Tutorial**  
*(All code is copy‑paste ready.  Official reference links are included.)*  

---  

## 1. Overview  

FreeSimpleGUI is a thin, free‑forever wrapper around the familiar **PySimpleGUI** API.  
You build a window by:

1. **Defining a layout** – a list of rows (`[[elem, …], …]`).  
2. **Creating a `Window`** with that layout.  
3. **Running an event loop** (`window.read()`) that returns `event, values`.  

The whole process is only a few lines of Python.

*Official docs:* https://freesimplegui.readthedocs.io/en/latest/  

---  

## 2. Install  

```bash
python -m pip install FreeSimpleGUI
```

> **Tip:** Use a virtual environment (`python -m venv .venv && source .venv/bin/activate`) so the package stays isolated.

---  

## 3. Quick‑Start  

```python
import FreeSimpleGUI as sg      # same import style as PySimpleGUI

sg.theme('DarkBlue3')          # optional – see the Themes section later

layout = [
    [sg.Text('Your name')],
    [sg.Input(key='-NAME-')],
    [sg.Button('Hello'), sg.Button('Quit')],
]

window = sg.Window('Hello FreeSimpleGUI', layout)

while True:
    event, values = window.read()
    if event in (sg.WIN_CLOSED, 'Quit'):
        break
    if event == 'Hello':
        name = values['-NAME-'] or 'there'
        sg.popup(f'Hello, {name}!')

window.close()
```

*Why it works:* `sg.Window.read()` blocks until a button is pressed or the window is closed, then returns the **event** (the widget that generated it) and a dict of **values** keyed by the element’s `key`.

---  

## 4. Layouts & Elements  

A layout is just a list of rows. Elements are created with the `sg.<Element>` constructors.  

```python
import FreeSimpleGUI as sg

layout = [
    [sg.Text('Login')],
    [sg.Text('Email', size=(10, 1)), sg.Input(key='-EMAIL-')],
    [sg.Text('Password', size=(10, 1)),
     sg.Input(password_char='•', key='-PASS-')],
    [sg.Checkbox('Remember me', key='-REM-')],
    [sg.Button('Sign in'), sg.Button('Cancel')],
]
```

*Key points*

* **`key`** – a unique identifier for later access (`window['-EMAIL-'].update(...)`).  
* **`size`** – `(width, height)` in characters (or pixels for some widgets).  

The official element reference is here: https://freesimplegui.readthedocs.io/en/latest/call%20reference/#elements  

---  

## 5. Events & Values  

```python
window = sg.Window('Auth', layout)

while True:
    event, values = window.read()
    if event in (sg.WIN_CLOSED, 'Cancel'):
        break
    if event == 'Sign in':
        email    = values['-EMAIL-']
        password = values['-PASS-']
        remember = values['-REM-']
        sg.popup(f'Email: {email}\nRemember: {remember}')
window.close()
```

* `event` → widget name or special constants (`sg.WIN_CLOSED`).  
* `values` → `dict` mapping each `key` to the current widget content.

---  

## 6. Common Widgets  

| Widget | Example | Notes |
|--------|---------|-------|
| **Text** | `sg.Text('Hello')` | static label |
| **Input** | `sg.Input('default', key='-IN-')` | single‑line entry |
| **Multiline** | `sg.Multiline('multi\\nline', size=(30,5), key='-ML-')` | larger text area |
| **Checkbox** | `sg.Checkbox('Check me', key='-CB-')` | boolean |
| **Radio** | `sg.Radio('A','grp',key='-RA-')` | mutually exclusive in same *group* |
| **Combo** | `sg.Combo(['Red','Green'], key='-C-')` | dropdown |
| **Slider** | `sg.Slider(range=(0,100), orientation='h', key='-SL-')` | numeric slide |
| **Listbox** | `sg.Listbox(['one','two'], key='-LB-', select_mode=sg.SELECT_MODE_SINGLE)` | single / multiple select |
| **Spin** | `sg.Spin([0,1,2], key='-SP-')` | small numeric selector |
| **Button** | `sg.Button('Run')` | action trigger |

Full widget list: https://freesimplegui.readthedocs.io/en/latest/call%20reference/#elements  

---  

## 7. Pop‑ups & File/Folder Dialogs  

FreeSimpleGUI’s “high‑level” pop‑ups are covered in the official docs【5†L0-L5】.  They behave like `print()` but in a window.

```python
sg.popup('Info', 'Saved successfully')               # generic
sg.popup_ok_cancel('Proceed?')
sg.popup_yes_no('Are you sure?')
sg.popup_error('Something went wrong')
```

### File & Folder helpers  

```python
file_path = sg.popup_get_file('Choose a file')
folder    = sg.popup_get_folder('Select a folder')
save_as   = sg.popup_get_file('Save as', save_as=True,
                             default_extension='.txt')
```

(Older builds may not support the `no_window` argument – the code falls back gracefully.)

---  

## 8. Updating Elements Dynamically  

```python
window = sg.Window('Dynamic',
    [[sg.Text('Count:'), sg.Text('0', key='-COUNT-')],
     [sg.Button('Inc'), sg.Button('Reset')]]
)

count = 0
while True:
    ev, _ = window.read()
    if ev == sg.WIN_CLOSED:
        break
    if ev == 'Inc':
        count += 1
        window['-COUNT-'].update(str(count))          # ← update text
    if ev == 'Reset':
        count = 0
        window['-COUNT-'].update('0')
window.close()
```

You can also disable or hide widgets:

```python
window['-IN-'].update(disabled=True)      # grey‑out
window['-BTN-'].update(visible=False)    # hide
```

---  

## 9. Long‑Running Work (Threading)

Never block the GUI thread.  Run heavy work in a **daemon thread** and poll a queue:

```python
import FreeSimpleGUI as sg, threading, time, queue

RESULT_EVENT = '-RESULT-'
result_q = queue.Queue()

def worker():
    time.sleep(2)               # pretend to do work
    result_q.put('done')

layout = [[sg.Button('Start'), sg.Text('Idle', key='-STATUS-')]]
window = sg.Window('Worker', layout)

while True:
    event, _ = window.read(timeout=100)       # timeout → non‑blocking
    if event == sg.WIN_CLOSED:
        break
    if event == 'Start':
        threading.Thread(target=worker, daemon=True).start()
        window['-STATUS-'].update('Working…')
    try:
        msg = result_q.get_nowait()
        if msg == 'done':
            window['-STATUS-'].update('Finished!')
    except queue.Empty:
        pass
window.close()
```

If your FreeSimpleGUI version supports it, you can push the result back to the window directly:

```python
window.write_event_value(RESULT_EVENT, data)   # optional
```

---  

## 10. Tabs, Columns & Layout Organization  

**Bug fix:** the original example missed a comma inside the `TabGroup`.  The correct syntax is:

```python
import FreeSimpleGUI as sg

col_left  = [[sg.Text('Left panel')], [sg.Button('A')]]
col_right = [[sg.Text('Right panel')], [sg.Button('B')]]

layout = [
    [sg.TabGroup([[
        sg.Tab('Main',
               [[sg.Column(col_left), sg.VSeparator(),
                 sg.Column(col_right)]])
        ,
        sg.Tab('Settings', [[sg.Text('Settings go here')]])
    ]])]
]

window = sg.Window('Organized Layout', layout)
while True:
    ev, _ = window.read()
    if ev == sg.WIN_CLOSED:
        break
window.close()
```

Tabs, columns and separators are described here: https://freesimplegui.readthedocs.io/en/latest/call%20reference/#containers  

---  

## 11. Themes  

FreeSimpleGUI ships with dozens of built‑in themes.  List them and set one **before** creating any window:

```python
import FreeSimpleGUI as sg
print(sg.theme_list())               # → list of theme names
sg.theme('DarkGrey13')                # set theme
```

The theme API is documented in the reference (see lines 4300‑4330 of the docs)【3†L4300-L4330】.  

---  

## 12. Validation & Form Submit  

```python
import FreeSimpleGUI as sg

layout = [
    [sg.Text('Age'), sg.Input(key='-AGE-', size=(5,1))],
    [sg.Button('Submit')]
]

win = sg.Window('Validate', layout)
while True:
    ev, vals = win.read()
    if ev == sg.WIN_CLOSED:
        break
    if ev == 'Submit':
        try:
            age = int(vals['-AGE-'])
            if age < 0:
                raise ValueError('Age must be non‑negative')
        except Exception as e:
            sg.popup_error(f'Invalid age: {e}')
            continue
        sg.popup(f'Thanks. Age = {age}')
win.close()
```

---  

## 13. State Persistence (Save/Load Settings)  

```python
import FreeSimpleGUI as sg, json, os

SETTINGS_FILE = 'settings.json'

def load_settings():
    if os.path.exists(SETTINGS_FILE):
        with open(SETTINGS_FILE, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}

def save_settings(data):
    with open(SETTINGS_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=2)

settings = load_settings()

layout = [
    [sg.Text('Username'), sg.Input(settings.get('username',''), key='-USER-')],
    [sg.Checkbox('Auto‑start', default=settings.get('autostart',False), key='-AUTO-')],
    [sg.Button('Save'), sg.Button('Close')]
]

win = sg.Window('Settings', layout)
while True:
    ev, vals = win.read()
    if ev in (sg.WIN_CLOSED, 'Close'):
        break
    if ev == 'Save':
        save_settings({'username': vals['-USER-'], 'autostart': vals['-AUTO-']})
        sg.popup('Saved')
win.close()
```

---  

## 14. Re‑usable Utilities & Dialog Kit  

Below is a **single‑file helper library** (`fsgui_utils.py`) you can drop into any project.  It builds upon the official API, adds validation, and returns `None` on cancel.  (Only the most useful functions are shown; the full file is the same as in the original tutorial.)

```python
# fsgui_utils.py
from __future__ import annotations
import threading, queue, json, os
from dataclasses import dataclass
from typing import Any, Callable, Iterable, List, Mapping, Optional, Sequence, Tuple, Union

import FreeSimpleGUI as sg

# ----------------------------------------------------------------------
# Small helpers
# ----------------------------------------------------------------------
ChoiceLike = Union[str, Tuple[str, Any]]

def _norm_choices(choices: Sequence[ChoiceLike]) -> List[Tuple[str, Any]]:
    """Return a [(label, value), …] list."""
    out = []
    for c in choices:
        if isinstance(c, tuple) and len(c) == 2:
            out.append((str(c[0]), c[1]))
        else:
            out.append((str(c), c))
    return out

def _popup_error(msg: str, title: str = "Error") -> None:
    try:
        sg.popup_error(msg, title=title, keep_on_top=True)
    except TypeError:                     # older builds
        sg.popup(msg, title=title, keep_on_top=True)

# ----------------------------------------------------------------------
# Basic ask* dialogs (text, int, float, choices, …)
# ----------------------------------------------------------------------
def ask_text(
    title: str,
    prompt: str,
    *,
    default: str = "",
    allow_empty: bool = True,
    multiline: bool = False,
    password: bool = False,
    validate: Optional[Callable[[str], Tuple[bool, Optional[str]]]] = None,
) -> Optional[str]:
    """Prompt the user for a string (single line or multiline)."""
    InputElem = sg.Multiline if multiline else sg.Input
    layout = [
        [sg.Text(prompt)],
        [InputElem(default,
                  key='-VAL-',
                  size=(50, 3) if multiline else (50, 1),
                  password_char='•' if password else '')],
        [sg.Push(), sg.Button('OK', bind_return_key=True), sg.Button('Cancel')],
    ]
    win = sg.Window(title, layout, modal=True, keep_on_top=True)
    try:
        while True:
            ev, vals = win.read()
            if ev in (sg.WIN_CLOSED, 'Cancel'):
                return None
            if ev == 'OK':
                val = vals['-VAL-'] if not multiline else (vals['-VAL-'] or '')
                if not allow_empty and not str(val).strip():
                    _popup_error('Value required')
                    continue
                if validate:
                    ok, msg = validate(str(val))
                    if not ok:
                        _popup_error(msg or 'Invalid value')
                        continue
                return str(val)
    finally:
        win.close()

def ask_password(title: str, prompt: str = "Password", *, allow_empty: bool = False) -> Optional[str]:
    return ask_text(title, prompt, password=True, allow_empty=allow_empty)

def _coerce_number(val: str, kind: str) -> Tuple[bool, Optional[Union[int, float]], Optional[str]]:
    try:
        if kind == 'int':
            return True, int(val.strip()), None
        return True, float(val.strip()), None
    except Exception as e:
        return False, None, f'Enter a valid {kind}: {e}'

def ask_int(
    title: str,
    prompt: str,
    *,
    default: Optional[int] = None,
    min_value: Optional[int] = None,
    max_value: Optional[int] = None,
) -> Optional[int]:
    def _validate(s: str) -> Tuple[bool, Optional[str]]:
        ok, num, msg = _coerce_number(s, 'int')
        if not ok:
            return False, msg
        if min_value is not None and num < min_value:
            return False, f'Minimum is {min_value}'
        if max_value is not None and num > max_value:
            return False, f'Maximum is {max_value}'
        return True, None
    out = ask_text(title, prompt,
                   default=str(default or ''),
                   allow_empty=default is not None,
                   validate=_validate)
    return int(out) if out is not None else None

def ask_float(
    title: str,
    prompt: str,
    *,
    default: Optional[float] = None,
    min_value: Optional[float] = None,
    max_value: Optional[float] = None,
) -> Optional[float]:
    def _validate(s: str) -> Tuple[bool, Optional[str]]:
        ok, num, msg = _coerce_number(s, 'float')
        if not ok:
            return False, msg
        if min_value is not None and num < min_value:
            return False, f'Minimum is {min_value}'
        if max_value is not None and num > max_value:
            return False, f'Maximum is {max_value}'
        return True, None
    out = ask_text(title, prompt,
                   default=str(default or ''),
                   allow_empty=default is not None,
                   validate=_validate)
    return float(out) if out is not None else None

def ask_choices(
    title: str,
    prompt: str,
    choices: Sequence[ChoiceLike],
    *,
    default: Optional[Union[ChoiceLike, Sequence[ChoiceLike]]] = None,
    multi: bool = False,
    sort: bool = False,
    size: Tuple[int, int] = (40, 10),
) -> Optional[Union[Any, List[Any]]]:
    pairs = _norm_choices(choices)
    if sort:
        pairs.sort(key=lambda x: x[0].lower())
    labels = [p[0] for p in pairs]
    label_to_val = {p[0]: p[1] for p in pairs}

    if multi:
        default_labels = []
        if default is not None:
            dlist = default if isinstance(default, (list, tuple)) else [default]
            default_labels = [str(_norm_choices([c])[0][0]) for c in dlist]

        layout = [
            [sg.Text(prompt)],
            [sg.Listbox(values=labels,
                        default_values=default_labels,
                        size=size,
                        select_mode=sg.SELECT_MODE_EXTENDED,
                        key='-L-')],
            [sg.Push(), sg.Button('OK', bind_return_key=True), sg.Button('Cancel')],
        ]
        win = sg.Window(title, layout, modal=True, keep_on_top=True)
        try:
            while True:
                ev, vals = win.read()
                if ev in (sg.WIN_CLOSED, 'Cancel'):
                    return None
                if ev == 'OK':
                    sel = vals['-L-'] or []
                    return [label_to_val[l] for l in sel]
        finally:
            win.close()
    else:
        default_label = None
        if default is not None:
            default_label = str(default[0]) if isinstance(default, tuple) else str(default)

        layout = [
            [sg.Text(prompt)],
            [sg.Combo(labels,
                      default_value=default_label,
                      key='-C-',
                      size=(size[0], 1),
                      readonly=True)],
            [sg.Push(), sg.Button('OK', bind_return_key=True), sg.Button('Cancel')],
        ]
        win = sg.Window(title, layout, modal=True, keep_on_top=True)
        try:
            while True:
                ev, vals = win.read()
                if ev in (sg.WIN_CLOSED, 'Cancel'):
                    return None
                if ev == 'OK':
                    label = vals['-C-']
                    if not label:
                        _popup_error('Please choose an option')
                        continue
                    return label_to_val[label]
        finally:
            win.close()

# ----------------------------------------------------------------------
# File / folder helpers
# ----------------------------------------------------------------------
def open_file(
    title: str = 'Open File',
    *,
    file_types: Optional[Sequence[Tuple[str, str]]] = None,
    multiple: bool = False,
    initial_folder: Optional[str] = None,
) -> Optional[Union[str, List[str]]]:
    """Wrapper around sg.popup_get_file that works on older builds."""
    file_types = file_types or [('All Files', '*.*')]
    try:
        path = sg.popup_get_file(
            title,
            file_types=file_types,
            multiple_files=multiple,
            no_window=True,
            initial_folder=initial_folder,
        )
    except TypeError:                     # older version
        path = sg.popup_get_file(title)
    if not path:
        return None
    if multiple and isinstance(path, str) and ';' in path:
        return [p.strip() for p in path.split(';') if p.strip()]
    return path

def save_file(
    title: str = 'Save As',
    *,
    default_extension: str = '',
    file_types: Optional[Sequence[Tuple[str, str]]] = None,
    initial_folder: Optional[str] = None,
) -> Optional[str]:
    file_types = file_types or [('All Files', '*.*')]
    try:
        path = sg.popup_get_file(
            title,
            save_as=True,
            default_extension=default_extension,
            file_types=file_types,
            no_window=True,
            initial_folder=initial_folder,
        )
    except TypeError:
        path = sg.popup_get_file(title, save_as=True)
    return path or None

def select_folder(
    title: str = 'Select Folder',
    *,
    initial_folder: Optional[str] = None,
) -> Optional[str]:
    try:
        return sg.popup_get_folder(title, no_window=True,
                                   initial_folder=initial_folder) or None
    except TypeError:
        return sg.popup_get_folder(title) or None

# ----------------------------------------------------------------------
# Notifications, confirms and progress
# ----------------------------------------------------------------------
def info(msg: str, title: str = 'Info', *, auto_close: Optional[float] = None) -> None:
    kwargs = dict(title=title, keep_on_top=True)
    if auto_close:
        kwargs.update(auto_close=True, auto_close_duration=float(auto_close))
    try:
        sg.popup(msg, **kwargs)
    except TypeError:                     # very old versions
        sg.popup(msg, title=title)

def warn(msg: str, title: str = 'Warning') -> None:
    sg.popup_ok(msg, title=title, keep_on_top=True)

def error(msg: str, title: str = 'Error') -> None:
    _popup_error(msg, title)

def confirm(
    message: str,
    title: str = 'Confirm',
    *,
    yes_text: str = 'Yes',
    no_text: str = 'No',
    default_yes: bool = True,
    timeout_ms: Optional[int] = None,
) -> Optional[bool]:
    layout = [
        [sg.Text(message)],
        [sg.Push(),
         sg.Button(yes_text, bind_return_key=default_yes, key='-Y-'),
         sg.Button(no_text, bind_return_key=not default_yes, key='-N-')],
    ]
    win = sg.Window(title, layout, modal=True, keep_on_top=True)
    try:
        ev, _ = win.read(timeout=timeout_ms if timeout_ms else None)
        if ev in (sg.WIN_CLOSED, None):
            return None
        return ev == '-Y-'
    finally:
        win.close()

def run_worker_with_progress(
    fn: Callable[..., Any],
    *args: Any,
    title: str = 'Working…',
    message: str = 'Please wait',
    cancellable: bool = True,
    **kwargs: Any,
) -> Tuple[bool, Any]:
    """Run ``fn`` in a daemon thread while a pulsing progress bar is shown."""
    stop_flag = {'val': False}
    def stop() -> bool: return stop_flag['val']

    result_q: "queue.Queue[Tuple[str, Any]]" = queue.Queue()

    def wrapper():
        try:
            res = fn(*args, **({**kwargs, 'stop': stop} if 'stop' not in kwargs else kwargs)
            result_q.put(('ok', res))
        except Exception as e:            # noqa: BLE001
            result_q.put(('err', e))

    threading.Thread(target=wrapper, daemon=True).start()

    bar = sg.ProgressBar(max_value=100,
                         orientation='h',
                         size=(40, 20),
                         bar_color=('green', 'lightgray'),
                         key='-PB-')
    layout = [
        [sg.Text(message, key='-MSG-')],
        [bar],
        [sg.Push(), sg.Button('Cancel', key='-C-', visible=cancellable)],
    ]
    win = sg.Window(title, layout, modal=True, keep_on_top=True)
    try:
        pulse = 0
        while True:
            ev, _ = win.read(timeout=100)
            if ev in (sg.WIN_CLOSED, '-C-') and cancellable:
                stop_flag['val'] = True
            try:
                kind, payload = result_q.get_nowait()
                if kind == 'ok':
                    return (stop_flag['val'], payload)
                else:                       # error path
                    return (stop_flag['val'], payload)
            except queue.Empty:
                pulse = (pulse + 5) % 100
                bar.update(current_count=pulse)
    finally:
        win.close()

# ----------------------------------------------------------------------
# Quick schema‑based form dialog
# ----------------------------------------------------------------------
@dataclass
class Field:
    key: str
    label: str
    kind: str = 'text'               # text|password|multiline|int|float|checkbox|combo
    default: Any = None
    required: bool = False
    tooltip: Optional[str] = None
    choices: Optional[Sequence[ChoiceLike]] = None
    size: Tuple[int, int] = (30, 1)
    min_value: Optional[float] = None
    max_value: Optional[float] = None

def form_dialog(title: str, fields: Sequence[Field]) -> Optional[Mapping[str, Any]]:
    rows: List[List[Any]] = []
    for f in fields:
        label = sg.Text(f.label, size=(18, 1), tooltip=f.tooltip)
        if f.kind == 'password':
            elem = sg.Input(default_text=str(f.default or ''),
                            password_char='•',
                            key=f.key,
                            size=f.size,
                            tooltip=f.tooltip)
        elif f.kind == 'multiline':
            elem = sg.Multiline(default_text=str(f.default or ''),
                               key=f.key,
                               size=(max(f.size[0], 30), max(f.size[1], 4)),
                               tooltip=f.tooltip)
        elif f.kind in ('int', 'float'):
            elem = sg.Input(default_text=str(f.default or ''),
                            key=f.key,
                            size=f.size,
                            tooltip=f.tooltip)
        elif f.kind == 'checkbox':
            elem = sg.Checkbox('', default=bool(f.default), key=f.key, tooltip=f.tooltip)
        elif f.kind == 'combo':
            choices = _norm_choices(f.choices or [])
            labels = [c[0] for c in choices]
            default_label = str(f.default) if f.default is not None else (labels[0] if labels else '')
            elem = sg.Combo(labels,
                            default_value=default_label,
                            readonly=True,
                            key=f.key,
                            size=f.size,
                            tooltip=f.tooltip)
        else:
            elem = sg.Input(default_text=str(f.default or ''), key=f.key,
                            size=f.size, tooltip=f.tooltip)
        rows.append([label, elem])
    rows.append([sg.HorizontalSeparator()])
    rows.append([sg.Push(),
                 sg.Button('OK', bind_return_key=True),
                 sg.Button('Cancel')])

    win = sg.Window(title, rows, modal=True, keep_on_top=True)
    try:
        while True:
            ev, vals = win.read()
            if ev in (sg.WIN_CLOSED, 'Cancel'):
                return None
            if ev == 'OK':
                # ---- validation ----
                for f in fields:
                    v = vals.get(f.key)
                    if f.kind == 'checkbox':
                        continue
                    if f.required and (v is None or str(v).strip() == ''):
                        _popup_error(f'"{f.label}" is required')
                        break
                    if f.kind in ('int', 'float') and str(v).strip():
                        ok, num, msg = _coerce_number(str(v), f.kind)
                        if not ok:
                            _popup_error(msg or f'Enter a valid {f.kind}')
                            break
                        if f.min_value is not None and num < f.min_value:
                            _popup_error(f'Minimum for "{f.label}" is {f.min_value}')
                            break
                        if f.max_value is not None and num > f.max_value:
                            _popup_error(f'Maximum for "{f.label}" is {f.max_value}')
                            break
                else:
                    # ---- convert & return ----
                    out: dict[str, Any] = {}
                    for f in fields:
                        v = vals.get(f.key)
                        if f.kind == 'int' and str(v).strip():
                            out[f.key] = int(str(v).strip())
                        elif f.kind == 'float' and str(v).strip():
                            out[f.key] = float(str(v).strip())
                        else:
                            out[f.key] = v
                    return out
    finally:
        win.close()

# ----------------------------------------------------------------------
# Simple table viewer
# ----------------------------------------------------------------------
def table_viewer(
    title: str,
    headers: Sequence[str],
    rows: Sequence[Sequence[Any]],
    *,
    size: Tuple[int, int] = (60, 15),
    copy_button: bool = True,
) -> None:
    table = sg.Table(
        values=[[str(c) for c in r] for r in rows],
        headings=list(headers),
        auto_size_columns=True,
        display_row_numbers=False,
        justification='left',
        num_rows=size[1],
        key='-T-',
        enable_events=True,
        expand_x=True,
        expand_y=True,
    )
    layout = [
        [table],
        [sg.Push(),
         sg.Button('Close'),
         sg.Button('Copy', key='-COPY-', visible=copy_button)],
    ]
    win = sg.Window(title, layout, resizable=True, keep_on_top=True, modal=True)
    try:
        while True:
            ev, vals = win.read()
            if ev in (sg.WIN_CLOSED, 'Close'):
                return
            if ev == '-COPY-':
                sel = vals.get('-T-', [])
                lines: List[str] = []
                if sel:
                    for idx in sel:
                        lines.append('\t'.join(str(c) for c in rows[idx]))
                else:
                    lines.append('\n'.join('\t'.join(str(c) for c in r) for r in rows))
                sg.clipboard_set('\n'.join(lines))
                info('Copied to clipboard')
    finally:
        win.close()
```

### Quick usage examples

```python
import FreeSimpleGUI as sg
import fsgui_utils as util

# 1️⃣  Ask user input
name = util.ask_text('Input', 'Your name?', allow_empty=False)
age  = util.ask_int('Age', 'How old?', min_value=0, max_value=130)
color = util.ask_choices('Color', 'Pick one', ['Red', 'Green', 'Blue'])

# 2️⃣  Confirm & notify
if util.confirm('Proceed?'):
    util.info('Onwards!')

# 3️⃣  File helpers
path   = util.open_file()
save_to = util.save_file(default_extension='.txt')
folder = util.select_folder()

# 4️⃣  Schema‑based form
from fsgui_utils import Field
result = util.form_dialog('Profile', [
    Field('name',  'Name', required=True),
    Field('age',   'Age',  kind='int', min_value=0),
    Field('role',  'Role', kind='combo', choices=['User', 'Admin']),
    Field('bio',   'Bio',  kind='multiline')
])
print('Form result →', result)

# 5️⃣  Worker with progress bar
import time
def work(n, stop):
    for i in range(n):
        if stop():
            return 'cancelled'
        time.sleep(0.05)
    return 'done'

cancelled, out = util.run_worker_with_progress(work, 100,
                                              title='Heavy Task',
                                              message='Crunching…')
sg.popup(f'Cancelled={cancelled}, Result={out}')
```

---  

## 14. Packaging Notes  

* **PyInstaller** works out‑of‑the‑box:* `pyinstaller --onefile your_script.py`*.  
* Keep non‑Python assets (icons, images) in the same folder as the compiled exe or embed them with `--add-data`.  
* Remember to **exclude** long‑running work from the main thread (see the threading section).  

---  

## 15. Troubleshooting  

| Symptom | Typical cause | Fix |
|---------|---------------|-----|
| Window freezes / “beachball” | Event loop blocked by heavy computation | Move work to a thread or use `window.read(timeout=…)` |
| Element doesn’t change | `update()` called before the window is `finalize()`‑d | Pass `finalize=True` when creating the window or call `window.finalize()` early |
| Fonts look tiny on macOS | High‑DPI scaling not accounted for | Use a larger theme (e.g., `sg.theme('DarkGrey13')`) or set explicit `font=('Helvetica', 12)` on elements |
| `popup_get_file` raises `TypeError` | Very old FreeSimpleGUI version | Update the package (`python -m pip install -U FreeSimpleGUI`) |

---  

## 16. References  

* **FreeSimpleGUI official docs** – complete API, themes, containers, pop‑ups: https://freesimplegui.readthedocs.io/en/latest/  
* **FreeSimpleGUI GitHub repository** (source, issue tracker): https://github.com/spyoungtech/FreeSimpleGUI  
* **PySimpleGUI docs** (the reference implementation FreeSimpleGUI mirrors): https://pysimplegui.readthedocs.io/  

Happy coding! 🎉  