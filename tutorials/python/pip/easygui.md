# EasyGUI: Complete, Copy‑Paste Tutorial (Python)

EasyGUI lets you build simple pop-up GUIs using plain functions—no event loops or layout managers. This guide is practical and code-first: every section includes a minimal, runnable snippet you can paste into a file and run.

- Works best for quick utilities, prompts, and desktop helpers
- No external windowing code to learn; everything is a function call

## Table of Contents

1. Install and Quick Check
2. Basics: Message and Confirmation Boxes
3. Buttons and Menus
4. Text Input: Single, Multi, Password, Integer
5. Lists and Multi-Select
6. Files and Folders: Open, Save, Directory
7. Show Text, Code, and Exceptions
8. Images in Dialogs
9. Control Flow Patterns (validation, cancel/close handling)
10. Mini App Example (glued together)
11. Tips, Gotchas, and Version Notes

---

## 1) Install and Quick Check

```bash
# Recommended: create and activate a virtual environment first
python -m venv .venv
source .venv/bin/activate  # macOS/Linux

pip install easygui
```

Sanity check (optional demo shipped with EasyGUI):

```bash
python -m easygui.egdemo
```

If a window of sample dialogs appears, you're set.

---

## 2) Basics: Message and Confirmation Boxes

```python
# file: 01_msg_and_confirm.py
import easygui as eg

# Simple info
eg.msgbox("Hello from EasyGUI!", title="Greeting")

# Yes/No question -> returns True for Yes, False for No
answer = eg.ynbox("Proceed with the operation?", title="Confirm", choices=("Yes", "No"))
if answer:
	eg.msgbox("You chose Yes.")
else:
	eg.msgbox("You chose No.")

# Continue/Cancel (ccbox) -> True for Continue, False for Cancel
if eg.ccbox("Continue to next step?", title="Continue?"):
	eg.msgbox("Continuing…")
else:
	eg.msgbox("Canceled.")

# Boolean box (OK/Cancel) -> True/False
ok = eg.boolbox("OK or Cancel?", title="Bool", choices=("OK", "Cancel"))
eg.msgbox(f"Result: {ok}")
```

Notes:

- Closing the window (X) usually returns None or False depending on the box. Treat it as cancel.

---

## 3) Buttons and Menus

```python
# file: 02_buttons_and_menu.py
import easygui as eg

# buttonbox: custom list of labeled buttons -> returns the label clicked, or None on close
choice = eg.buttonbox(
	msg="Pick an action:",
	title="Actions",
	choices=["Create", "Open", "Save", "Quit"],
)

if choice == "Create":
	eg.msgbox("Create selected")
elif choice == "Open":
	eg.msgbox("Open selected")
elif choice == "Save":
	eg.msgbox("Save selected")
elif choice == "Quit":
	eg.msgbox("Bye!")
else:
	eg.msgbox("Window closed/canceled.")

# indexbox: like buttonbox but returns the index (0-based) instead of the label
idx = eg.indexbox(
	msg="Choose a color:",
	title="Index Menu",
	choices=["Red", "Green", "Blue"],
)
if idx is not None:
	eg.msgbox(f"You chose index {idx}")
```

---

## 4) Text Input: Single, Multi, Password, Integer

```python
# file: 03_text_inputs.py
import easygui as eg

# enterbox: single line input -> returns string or None
name = eg.enterbox("What's your name?", title="Enter Name", default="Ada")
if name is None:
	eg.msgbox("Canceled.")
else:
	eg.msgbox(f"Hi, {name}!")

# passwordbox: masked single line input -> returns string or None
pwd = eg.passwordbox("Enter password:", title="Auth")
eg.msgbox(f"Password length: {len(pwd) if pwd is not None else 'Canceled'}")

# multenterbox: multiple labeled fields -> returns list[str] aligned with fields, or None
fields = ["First Name", "Last Name", "Email"]
values = eg.multenterbox("Please fill out all fields:", title="Profile", fields=fields, values=["Ada", "Lovelace", "ada@example.com"])
if values:
	first, last, email = values
	eg.msgbox(f"Hello {first} {last} <{email}>")
else:
	eg.msgbox("Canceled.")

# multpasswordbox: multiple masked fields -> list[str] or None
secrets = eg.multpasswordbox("Enter credentials:", title="Secrets", fields=["Username", "Password"])
if secrets:
	user, pw = secrets
	eg.msgbox(f"User: {user} (pw length: {len(pw)})")

# integerbox: constrained integer input -> returns int or None
age = eg.integerbox(
	msg="Your age (18-120):",
	title="Age",
	lowerbound=18,
	upperbound=120,
	default=30,
)
eg.msgbox(f"Age: {age}")
```

Validation tip: You can loop until values are valid; see section 9.

---

## 5) Lists and Multi‑Select

```python
# file: 04_choice_lists.py
import easygui as eg

# choicebox: single selection from a list -> returns selected string or None
langs = ["Python", "JavaScript", "Rust", "Go", "Julia"]
lang = eg.choicebox("Pick one language:", title="Choice", choices=langs)
if lang:
	eg.msgbox(f"You picked: {lang}")

# multchoicebox: multiple selection -> returns list[str] or None
features = ["CLI", "GUI", "API", "DB", "Tests"]
selected = eg.multchoicebox("Pick features:", title="MultiChoice", choices=features)
if selected:
	eg.msgbox("You chose: " + ", ".join(selected))
else:
	eg.msgbox("Nothing selected or canceled.")
```

Large lists: pass a long list; EasyGUI will add scrollbars automatically.

---

## 6) Files and Folders: Open, Save, Directory

```python
# file: 05_files_and_folders.py
import easygui as eg

# fileopenbox: open an existing file -> returns path or None
path = eg.fileopenbox(
	msg="Select a file to open",
	title="Open File",
	default="*",
	filetypes=["*.txt", "*.md", ["Python", "*.py"], "*.*"],
)
eg.msgbox(f"Selected: {path}")

# filesavebox: choose where to save -> returns path or None
save_to = eg.filesavebox(
	msg="Save output as…",
	title="Save File",
	default="output.txt",
	filetypes=["*.txt", "*.*"],
)
eg.msgbox(f"Save path: {save_to}")

# diropenbox: choose a directory -> returns path or None
dir_path = eg.diropenbox(msg="Choose a folder", title="Pick Folder")
eg.msgbox(f"Folder: {dir_path}")
```

Notes:

- Some platforms may ignore parts of the filter spec; keep patterns simple for reliability.
- If the user cancels, you’ll get None—handle it.

---

## 7) Show Text, Code, and Exceptions

```python
# file: 06_text_code_exception.py
import easygui as eg

# textbox: shows text with scrollbars (proportional font)
long_text = "\n".join(f"Line {i}" for i in range(1, 51))
eg.textbox(
	msg="Here is some text:",
	title="Text View",
	text=long_text,
	codebox=False,  # proportional font
)

# codebox: monospaced, better for code/logs
code_sample = """
# Example: compute factorial

def fact(n):
	return 1 if n <= 1 else n * fact(n-1)

print(fact(5))
""".strip()
eg.codebox("Code Sample", title="Code View", text=code_sample)

# exceptionbox: nicely shows the current exception traceback
try:
	1 / 0
except Exception:
	eg.exceptionbox(title="Oops! An error occurred")
```

---

## 8) Images in Dialogs

You can embed an image in many dialogs (e.g., msgbox, buttonbox) using the `image` parameter. PNG/JPG/GIF usually work.

```python
# file: 07_images.py
import easygui as eg

# Replace with a valid path to an image on your machine
IMAGE_PATH = "./screenshots/1.png"  # adjust as needed

eg.msgbox(
	msg="Look at this image!",
	title="Image Demo",
	image=IMAGE_PATH,
)

choice = eg.buttonbox(
	msg="Pick one",
	title="Image Buttons",
	choices=["One", "Two", "Three"],
	image=IMAGE_PATH,
)
eg.msgbox(f"You picked: {choice}")
```

If the image doesn’t show, verify the path and format.

---

## 9) Control Flow Patterns (validation, cancel/close)

```python
# file: 08_flow_patterns.py
import easygui as eg

# Loop until the user enters a non-empty name or cancels
while True:
	name = eg.enterbox("Enter your name:", title="Validation")
	if name is None:
		eg.msgbox("Canceled.")
		break
	if name.strip():
		eg.msgbox(f"Welcome, {name.strip()}!")
		break
	eg.msgbox("Name cannot be empty. Please try again.")

# Validate multiple entries and redisplay with hints
fields = ["Email", "Age"]
values = ["", ""]
while True:
	values = eg.multenterbox("Enter details:", title="Validate Form", fields=fields, values=values)
	if values is None:
		eg.msgbox("Canceled.")
		break
	email, age = values
	errors = []
	if "@" not in email:
		errors.append("- Email must contain @")
	try:
		age_val = int(age)
		if age_val < 18:
			errors.append("- Age must be 18+")
	except ValueError:
		errors.append("- Age must be a number")

	if not errors:
		eg.msgbox(f"Saved: {email}, {age_val}")
		break
	eg.msgbox("Please fix:\n" + "\n".join(errors))
```

Key ideas:

- Most dialogs return None on cancel/close; treat as “abort” or redisplay.
- Keep previous values in `values` to make re-entry friendlier.

---

## 10) Mini App Example (glued together)

```python
# file: 09_mini_app.py
import easygui as eg

while True:
	action = eg.buttonbox(
		msg="Select an operation",
		title="Mini App",
		choices=["New Note", "Open Note", "Show Log", "Quit"],
	)
	if action is None or action == "Quit":
		break

	if action == "New Note":
		text = eg.textbox("Write your note (will be saved):", title="New Note", text="")
		if text is not None and text.strip():
			save_to = eg.filesavebox(title="Save Note As", default="note.txt", filetypes=["*.txt", "*.*"])
			if save_to:
				try:
					with open(save_to, "w", encoding="utf-8") as f:
						f.write(text)
					eg.msgbox(f"Saved to {save_to}")
				except Exception:
					eg.exceptionbox(title="Save Failed")

	elif action == "Open Note":
		path = eg.fileopenbox(title="Open Note", filetypes=["*.txt", "*.*"])
		if path:
			try:
				with open(path, "r", encoding="utf-8") as f:
					content = f.read()
				eg.textbox("Viewing note:", title=path, text=content)
			except Exception:
				eg.exceptionbox(title="Open Failed")

	elif action == "Show Log":
		log_lines = [f"Event {i}" for i in range(1, 51)]
		eg.codebox("Recent Events", title="Log", text="\n".join(log_lines))
```

Run any file with:

```bash
python 09_mini_app.py
```

---

## 11) Tips, Gotchas, and Version Notes

- Cancel/Close handling: Always check for None/False and branch accordingly.
- Defaults: Many functions accept `title`, `default`, or `choices`—prefer explicit titles for clarity.
- Long text: Use `textbox` or `codebox` rather than `msgbox` for big content.
- File filters: Keep them simple; behavior varies slightly by platform.
- Images: Use common formats (PNG/JPG). Pass absolute paths if relative paths cause issues.
- Multiple file open: Some EasyGUI versions support `fileopenbox(..., multiple=True)` to return a list; if unavailable in your version, you’ll get a TypeError—omit the parameter in that case.
- Demo explorer: `python -m easygui.egdemo` shows most capabilities interactively.

---

Happy prompting! Copy any section into a `.py` file and run it with Python.
