# FreeSimpleGUI — Practical Tutorial (with Table of Contents)

A concise, copy‑pasteable guide to building desktop GUIs with [FreeSimpleGUI](https://github.com/spyoungtech/FreeSimpleGUI). It follows familiar PySimpleGUI‑style APIs to create windows, layouts, and event loops with minimal code.

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start](#quick-start)
- [Layouts and Elements](#layouts-and-elements)
- [Events and Values](#events-and-values)
- [Common Widgets](#common-widgets)
- [Popups and File/Folder Dialogs](#popups-and-filefolder-dialogs)
- [Updating Elements Dynamically](#updating-elements-dynamically)
- [Long‑Running Tasks (threading)](#longrunning-tasks-threading)
- [Tabs, Columns, and Organization](#tabs-columns-and-organization)
- [Themes](#themes)
- [Validation and Form Submit](#validation-and-form-submit)
- [State Persistence (save/load settings)](#state-persistence-saveload-settings)
- [Reusable Utilities and Dialogs (copy‑paste kit)](#reusable-utilities-and-dialogs-copypaste-kit)
- [Packaging notes](#packaging-notes)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

FreeSimpleGUI provides a simple, Pythonic interface (similar to PySimpleGUI) to create cross‑platform desktop apps. You:

- Define a layout (a list of rows, each a list of elements).
- Create a `Window` with that layout.
- Enter an event loop: read events/values, update UI, and exit cleanly.

## Install

```bash
python -m pip install FreeSimpleGUI
```

If you use virtual environments, activate them first. On macOS, ensure you’re running Python from the environment where the package is installed.

## Quick Start

```python
import FreeSimpleGUI as sg

sg.theme('DarkBlue3')  # optional theme

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

## Layouts and Elements

A layout is a list of rows. Each row is a list of elements:

```python
import FreeSimpleGUI as sg

layout = [
    [sg.Text('Login')],
    [sg.Text('Email', size=(10, 1)), sg.Input(key='-EMAIL-')],
    [sg.Text('Password', size=(10, 1)), sg.Input(password_char='•', key='-PASS-')],
    [sg.Checkbox('Remember me', key='-REM-')],
    [sg.Button('Sign in'), sg.Button('Cancel')],
]
```

Keys identify elements so you can access their values or update them later.

## Events and Values

Use an event loop to handle user interactions:

```python
window = sg.Window('Auth', layout)

while True:
    event, values = window.read()
    if event in (sg.WIN_CLOSED, 'Cancel'):
        break
    if event == 'Sign in':
        email = values['-EMAIL-']
        password = values['-PASS-']
        remember = values['-REM-']
        sg.popup(f"Email: {email}\nRemember: {remember}")

window.close()
```

- `event` is the name of the element or button pressed.
- `values` is a dict mapping element keys to current values.

## Common Widgets

```python
import FreeSimpleGUI as sg

layout = [
    [sg.Text('Text'), sg.Input('default', key='-IN-')],
    [sg.Multiline('multi\nline', size=(30, 5), key='-ML-')],
    [sg.Checkbox('Check me', key='-CB-'), sg.Radio('Option A', 'grp', True, key='-RA-'), sg.Radio('Option B', 'grp', key='-RB-')],
    [sg.Combo(['Red', 'Green', 'Blue'], default_value='Red', key='-COLOR-'), sg.Slider(range=(0, 100), orientation='h', key='-SL-')],
    [sg.Listbox(values=['one', 'two', 'three'], size=(15, 3), key='-LB-', select_mode=sg.SELECT_MODE_SINGLE)],
    [sg.Spin([i for i in range(0, 11)], initial_value=3, key='-SP-')],
    [sg.Button('Show Values')]
]

window = sg.Window('Widgets', layout)
while True:
    event, values = window.read()
    if event == sg.WIN_CLOSED:
        break
    if event == 'Show Values':
        sg.popup_scrolled(values, title='Current Values')
window.close()
```

## Popups and File/Folder Dialogs

Useful one‑liners:

```python
sg.popup('Info', 'Saved successfully')
sg.popup_ok_cancel('Proceed?')
sg.popup_yes_no('Are you sure?')
sg.popup_error('Something went wrong')
```

File/folder selection helpers (function names follow PySimpleGUI patterns):

```python
file_path = sg.popup_get_file('Choose a file')
folder = sg.popup_get_folder('Choose a folder')
save_as = sg.popup_get_file('Save as', save_as=True, default_extension='.txt')
```

## Updating Elements Dynamically

```python
window = sg.Window('Dynamic', [[sg.Text('Count:'), sg.Text('0', key='-COUNT-')], [sg.Button('Inc'), sg.Button('Reset')]])
count = 0
while True:
    event, _ = window.read()
    if event == sg.WIN_CLOSED:
        break
    if event == 'Inc':
        count += 1
        window['-COUNT-'].update(str(count))
    if event == 'Reset':
        count = 0
        window['-COUNT-'].update('0')
window.close()
```

You can also enable/disable or show/hide elements:

```python
window['-IN-'].update(disabled=True)
window['-BTN-'].update(visible=False)
```

## Long‑Running Tasks (threading)

Avoid blocking the GUI thread. Offload work to a background thread and poll with a timeout:

```python
import FreeSimpleGUI as sg
import threading, time, queue

RESULT_EVENT = '-RESULT-'
result_q: "queue.Queue[str]" = queue.Queue()

def worker():
    time.sleep(2)
    result_q.put('done')

layout = [[sg.Button('Start'), sg.Text('Idle', key='-STATUS-')]]
window = sg.Window('Worker', layout)

while True:
    event, _ = window.read(timeout=100)
    if event == sg.WIN_CLOSED:
        break
    if event == 'Start':
        threading.Thread(target=worker, daemon=True).start()
        window['-STATUS-'].update('Working...')
    try:
        msg = result_q.get_nowait()
        if msg == 'done':
            window['-STATUS-'].update('Finished!')
    except queue.Empty:
        pass

window.close()
```

Alternatively, some PySimpleGUI‑style APIs support posting events back to the window (e.g., `window.write_event_value`) if available:

```python
# if supported by your FreeSimpleGUI build
window.write_event_value('-RESULT-', data)
```

## Tabs, Columns, and Organization

```python
import FreeSimpleGUI as sg

col_left = [[sg.Text('Left panel')], [sg.Button('A')]]
col_right = [[sg.Text('Right panel')], [sg.Button('B')]]

layout = [
    [sg.TabGroup([[sg.Tab('Main', [[sg.Column(col_left), sg.VSeparator(), sg.Column(col_right)]]),
                   sg.Tab('Settings', [[sg.Text('Settings go here')]])]])]
]

window = sg.Window('Layout', layout)
while True:
    event, _ = window.read()
    if event == sg.WIN_CLOSED:
        break
window.close()
```

## Themes

```python
import FreeSimpleGUI as sg
print(sg.theme_list())
sg.theme('DarkGrey13')
```

Theme must be set before creating the window to affect defaults.

## Validation and Form Submit

```python
import FreeSimpleGUI as sg

layout = [
    [sg.Text('Age'), sg.Input(key='-AGE-', size=(5, 1))],
    [sg.Button('Submit')]
]

window = sg.Window('Validate', layout)

while True:
    event, values = window.read()
    if event == sg.WIN_CLOSED:
        break
    if event == 'Submit':
        try:
            age = int(values['-AGE-'])
            if age < 0:
                raise ValueError('Age must be non‑negative')
        except Exception as e:
            sg.popup_error(f'Invalid age: {e}')
            continue
        sg.popup(f'Thanks. Age={age}')

window.close()
```

## State Persistence (save/load settings)

```python
import FreeSimpleGUI as sg, json, os

SETTINGS_FILE = 'settings.json'

def load_settings():
    if os.path.exists(SETTINGS_FILE):
        with open(SETTINGS_FILE, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}

def save_settings(data: dict):
    with open(SETTINGS_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=2)

settings = load_settings()

layout = [
    [sg.Text('Username'), sg.Input(settings.get('username', ''), key='-USER-')],
    [sg.Checkbox('Auto‑start', default=settings.get('autostart', False), key='-AUTO-')],
    [sg.Button('Save'), sg.Button('Close')]
]

window = sg.Window('Settings', layout)
while True:
    event, values = window.read()
    if event in (sg.WIN_CLOSED, 'Close'):
        break
    if event == 'Save':
        save_settings({'username': values['-USER-'], 'autostart': values['-AUTO-']})
        sg.popup('Saved')
window.close()
```

## Reusable Utilities and Dialogs (copy‑paste kit)

Drop this module into your project as fsgui_utils.py. It provides EasyGUI‑style dialogs with stronger validation, richer options, and consistent return values. All functions return None on cancel. Copy, paste, and import FreeSimpleGUI as sg.

```python
# fsgui_utils.py — Reusable dialogs and helpers for FreeSimpleGUI
from __future__ import annotations

import threading
import queue
from dataclasses import dataclass
from typing import Any, Callable, Iterable, List, Mapping, Optional, Sequence, Tuple, Union

import FreeSimpleGUI as sg


# ---------- Small helpers ----------
ChoiceLike = Union[str, Tuple[str, Any]]

def _norm_choices(choices: Sequence[ChoiceLike]) -> List[Tuple[str, Any]]:
    out: List[Tuple[str, Any]] = []
    for c in choices:
        if isinstance(c, tuple) and len(c) == 2:
            out.append((str(c[0]), c[1]))
        else:
            out.append((str(c), c))
    return out


def _popup_error(msg: str, title: str = "Error") -> None:
    try:
        sg.popup_error(msg, title=title, keep_on_top=True)
    except TypeError:
        # Fallback for older builds
        sg.popup(msg, title=title, keep_on_top=True)


# ---------- Basic ask* dialogs ----------
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
    """Ask for text (or multiline/password). Returns str or None on cancel.

    validate: function(value) -> (ok, message). If not ok, shows message and keeps dialog open.
    """
    input_elem = sg.Multiline if multiline else sg.Input
    layout = [
        [sg.Text(prompt)],
        [input_elem(default, key='-VAL-', size=(50, 3) if multiline else (50, 1), password_char='•' if password else '')],
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
                if not allow_empty and (val is None or str(val) == ''):
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


def ask_password(title: str, prompt: str = 'Password', *, allow_empty: bool = False) -> Optional[str]:
    return ask_text(title, prompt, default='', allow_empty=allow_empty, password=True)


def _coerce_number(val: str, kind: str) -> Tuple[bool, Optional[Union[int, float]], Optional[str]]:
    try:
        if kind == 'int':
            return True, int(str(val).strip()), None
        return True, float(str(val).strip()), None
    except Exception as e:
        return False, None, f'Enter a valid {kind} ({e})'


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

    out = ask_text(title, prompt, default=str(default or ''), allow_empty=default is not None, validate=_validate)
    if out is None:
        return None
    return int(out)


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
        assert num is not None
        if min_value is not None and num < min_value:
            return False, f'Minimum is {min_value}'
        if max_value is not None and num > max_value:
            return False, f'Maximum is {max_value}'
        return True, None

    out = ask_text(title, prompt, default=str(default or ''), allow_empty=default is not None, validate=_validate)
    if out is None:
        return None
    return float(out)


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
        default_labels: List[str] = []
        if default is not None:
            dlist = default if isinstance(default, (list, tuple)) else [default]
            default_labels = [p[0] if isinstance(p, tuple) else str(p) for p in _norm_choices(dlist)]
        layout = [
            [sg.Text(prompt)],
            [sg.Listbox(values=labels, default_values=default_labels, size=size, select_mode=sg.SELECT_MODE_EXTENDED, key='-L-')],
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
                    return [label_to_val[s] for s in sel]
        finally:
            win.close()
    else:
        default_label = None
        if default is not None:
            if isinstance(default, tuple):
                default_label = str(default[0])
            else:
                default_label = str(default)
        layout = [
            [sg.Text(prompt)],
            [sg.Combo(labels, default_value=default_label, key='-C-', size=(size[0], 1), readonly=True)],
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


# ---------- File/Folder helpers ----------
def open_file(
    title: str = 'Open File',
    *,
    file_types: Optional[Sequence[Tuple[str, str]]] = None,
    multiple: bool = False,
    initial_folder: Optional[str] = None,
) -> Optional[Union[str, List[str]]]:
    file_types = file_types or [('All Files', '*.*')]
    try:
        path = sg.popup_get_file(
            title,
            file_types=file_types,
            multiple_files=multiple,
            no_window=True,
            initial_folder=initial_folder,
        )
    except TypeError:
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


def select_folder(title: str = 'Select Folder', *, initial_folder: Optional[str] = None) -> Optional[str]:
    try:
        return sg.popup_get_folder(title, no_window=True, initial_folder=initial_folder) or None
    except TypeError:
        return sg.popup_get_folder(title) or None


# ---------- Notifications & confirms ----------
def info(msg: str, title: str = 'Info', *, auto_close: Optional[float] = None) -> None:
    kwargs = dict(title=title, keep_on_top=True)
    if auto_close:
        kwargs.update(auto_close=True, auto_close_duration=float(auto_close))
    try:
        sg.popup(msg, **kwargs)
    except TypeError:
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
         sg.Button(no_text, bind_return_key=not default_yes, key='-N-')]
    ]
    win = sg.Window(title, layout, modal=True, keep_on_top=True)
    try:
        ev, _ = win.read(timeout=timeout_ms if timeout_ms and timeout_ms > 0 else None)
        if ev in (sg.WIN_CLOSED, None):
            return None
        if ev == '-Y-':
            return True
        if ev == '-N-':
            return False
        return None
    finally:
        win.close()


# ---------- Progress + worker ----------
def run_worker_with_progress(
    fn: Callable[..., Any],
    *args: Any,
    title: str = 'Working…',
    message: str = 'Please wait',
    cancellable: bool = True,
    **kwargs: Any,
) -> Tuple[bool, Any]:
    """Run fn in a background thread while showing an indeterminate progress dialog.

    Returns (cancelled, result_or_exception).
    If cancellable=True, provides a stop() callback in kwargs that the worker can check.

    Example worker:
        def work(n, stop):
            for i in range(n):
                if stop():
                    return 'stopped'
                time.sleep(0.05)
            return 'done'
    """
    stop_flag = {
        'val': False
    }

    def stop() -> bool:
        return stop_flag['val']

    result_q: "queue.Queue[Tuple[str, Any]]" = queue.Queue()

    def wrapper():
        try:
            res = fn(*args, **({**kwargs, 'stop': stop} if 'stop' not in kwargs else kwargs))
            result_q.put((
                'ok', res
            ))
        except Exception as e:  # noqa: BLE001
            result_q.put(('err', e))

    th = threading.Thread(target=wrapper, daemon=True)
    th.start()

    bar = sg.ProgressBar(max_value=100, orientation='h', size=(40, 20), bar_color=('green', 'lightgray'), key='-PB-')
    layout = [
        [sg.Text(message, key='-MSG-', size=(50, 1))],
        [bar],
        [sg.Push(), sg.Button('Cancel', key='-C-', visible=cancellable)]
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
                    return False if not stop_flag['val'] else True, payload
                else:
                    return False if not stop_flag['val'] else True, payload
            except queue.Empty:
                pulse = (pulse + 5) % 100
                bar.update(current_count=pulse)
    finally:
        win.close()


# ---------- Quick schema-based form ----------
@dataclass
class Field:
    key: str
    label: str
    kind: str = 'text'  # text|password|multiline|int|float|checkbox|combo
    default: Any = None
    required: bool = False
    tooltip: Optional[str] = None
    choices: Optional[Sequence[ChoiceLike]] = None  # for combo
    size: Tuple[int, int] = (30, 1)
    min_value: Optional[float] = None
    max_value: Optional[float] = None


def form_dialog(title: str, fields: Sequence[Field]) -> Optional[Mapping[str, Any]]:
    rows: List[List[Any]] = []
    for f in fields:
        label = sg.Text(f.label, size=(18, 1), tooltip=f.tooltip)
        elem: Any
        if f.kind == 'password':
            elem = sg.Input(default_text=str(f.default or ''), password_char='•', key=f.key, size=f.size, tooltip=f.tooltip)
        elif f.kind == 'multiline':
            elem = sg.Multiline(default_text=str(f.default or ''), key=f.key, size=(max(f.size[0], 30), max(f.size[1], 4)), tooltip=f.tooltip)
        elif f.kind == 'int':
            elem = sg.Input(default_text=str(f.default or ''), key=f.key, size=f.size, tooltip=f.tooltip)
        elif f.kind == 'float':
            elem = sg.Input(default_text=str(f.default or ''), key=f.key, size=f.size, tooltip=f.tooltip)
        elif f.kind == 'checkbox':
            elem = sg.Checkbox('', default=bool(f.default), key=f.key, tooltip=f.tooltip)
        elif f.kind == 'combo':
            choices = _norm_choices(f.choices or [])
            labels = [c[0] for c in choices]
            default_label = str(f.default) if f.default is not None else (labels[0] if labels else '')
            elem = sg.Combo(labels, default_value=default_label, readonly=True, key=f.key, size=f.size, tooltip=f.tooltip)
        else:
            elem = sg.Input(default_text=str(f.default or ''), key=f.key, size=f.size, tooltip=f.tooltip)
        rows.append([label, elem])
    rows.append([sg.HorizontalSeparator()])
    rows.append([sg.Push(), sg.Button('OK', bind_return_key=True), sg.Button('Cancel')])

    win = sg.Window(title, rows, modal=True, keep_on_top=True)
    try:
        while True:
            ev, vals = win.read()
            if ev in (sg.WIN_CLOSED, 'Cancel'):
                return None
            if ev == 'OK':
                # Validate
                for f in fields:
                    v = vals.get(f.key)
                    if f.kind == 'checkbox':
                        continue
                    if f.required and (v is None or str(v).strip() == ''):
                        _popup_error(f'"{f.label}" is required')
                        break
                    if f.kind in ('int', 'float') and str(v).strip() != '':
                        kind = 'int' if f.kind == 'int' else 'float'
                        ok, num, msg = _coerce_number(str(v), kind)
                        if not ok:
                            _popup_error(msg or f'Enter a valid {kind}')
                            break
                        if f.min_value is not None and num is not None and num < f.min_value:
                            _popup_error(f'Minimum for "{f.label}" is {f.min_value}')
                            break
                        if f.max_value is not None and num is not None and num > f.max_value:
                            _popup_error(f'Maximum for "{f.label}" is {f.max_value}')
                            break
                else:
                    # Coerce numeric values
                    out: dict[str, Any] = {}
                    for f in fields:
                        v = vals.get(f.key)
                        if f.kind == 'int' and str(v).strip() != '':
                            out[f.key] = int(str(v).strip())
                        elif f.kind == 'float' and str(v).strip() != '':
                            out[f.key] = float(str(v).strip())
                        else:
                            out[f.key] = v
                    return out
    finally:
        win.close()


# ---------- Small table viewer ----------
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
        [sg.Push(), sg.Button('Close'), sg.Button('Copy', key='-COPY-', visible=copy_button)],
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

Quick usage examples:

```python
import FreeSimpleGUI as sg
import fsgui_utils as util

# 1) Ask stuff
name = util.ask_text('Input', 'Your name?', allow_empty=False)
age = util.ask_int('Age', 'How old?', min_value=0, max_value=130)
color = util.ask_choices('Color', 'Pick one', ['Red', 'Green', 'Blue'])
tags = util.ask_choices('Tags', 'Pick many', ['a', 'b', 'c', 'd'], multi=True)

# 2) Confirm and notify
if util.confirm('Proceed?', default_yes=True):
    util.info('Onwards!')

# 3) Files/folders
path = util.open_file(multiple=False)
save_to = util.save_file(default_extension='.txt')
folder = util.select_folder()

# 4) Form dialog
from fsgui_utils import Field
result = util.form_dialog('Profile', [
    Field('name', 'Name', required=True),
    Field('age', 'Age', kind='int', min_value=0),
    Field('role', 'Role', kind='combo', choices=['User', 'Admin']),
    Field('bio', 'Bio', kind='multiline'),
])

# 5) Background worker with progress
import time
def work(n, stop):
    for i in range(n):
        if stop():
            return 'cancelled'
        time.sleep(0.05)
    return 'done'

cancelled, res = util.run_worker_with_progress(work, 100, title='Heavy Task', message='Crunching…')
sg.popup(f'Cancelled={cancelled}, Result={res}')
```

## Packaging notes

- Use `pyinstaller` to bundle into an app. Keep resources next to the executable or embed them.
- Avoid long‑running work in the GUI thread; use threads and poll or post events.

## Troubleshooting

- Window freezes or beachball: you’re blocking the event loop. Use `window.read(timeout=...)` and threads for long work.
- Elements don’t update: ensure you call `Element.update(...)` with the correct key and that the element exists.
- Fonts or scaling oddities on macOS: try different themes and explicit font sizes, or adjust display scaling.

## References

- Project: [FreeSimpleGUI on GitHub](https://github.com/spyoungtech/FreeSimpleGUI)
- PySimpleGUI docs (API similarities): [https://pysimplegui.readthedocs.io/](https://pysimplegui.readthedocs.io/)

---

Copy any block into your script and run it with Python. Happy building!
