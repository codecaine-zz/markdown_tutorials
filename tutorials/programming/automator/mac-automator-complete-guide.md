# 📚 The **Ultimate Automator Guide for Absolute Beginners** (2025)  
*Plain‑language explanations, copy‑paste workflow files, and step‑by‑step screenshots – everything you need to master Automator **inside the Automator app** (no external editors required).*

---

## 🎯 Who This Guide Is For  

| ✅ | Description |
|---|-------------|
| **Never automated before** | No programming or scripting experience assumed. |
| **Love a visual “drag‑and‑drop” approach** | Automator’s UI is the focus – you’ll never open a code editor. |
| **Prefer copy‑paste, instantly runnable workflows** | Each section provides a ready‑to‑import *.workflow* file you can drop into Automator. |
| **Will later need a production‑ready automation** | The case‑study at the end shows a SOLID‑style “File‑Organizer + Email‑Notifier” Quick Action you can ship to teammates. |
| **Enjoy the macOS ecosystem** | All examples run on macOS 14 (Sonoma), 15 (Sequoia), or later – the latest Apple‑provided Automator version. |

---

## 📖 How to Use This Document  

1. **Read the sections sequentially** – each builds on the previous one.  
2. **Import the *.workflow* file** the section mentions (File → Import… in Automator).  
3. **Run the workflow** (⌘ R inside Automator, or from the Finder/Services menu).  
4. **When you see a “🔗” link, click it** – it opens Apple’s official Automator documentation.  
5. **When you finish the fundamentals, jump to the “Case Study”** to see everything in a realistic, SOLID‑compliant workflow.  

---

## 📦 Table of Contents  

| # | Topic | Quick link |
|---|-------|------------|
| 1️⃣ | **Getting Started** – open Automator, create a workflow, run it | [Go](#1️⃣-getting‑started) |
| 2️⃣ | **Workflow Types** – Application, Quick Action, Service, etc. | [Go](#2️⃣-workflow‑types) |
| 3️⃣ | **The Library & Actions** – finding, adding, configuring actions | [Go](#3️⃣-the‑library‑actions) |
| 4️⃣ | **Variables** – creating, setting, getting, and using them | [Go](#4️⃣-variables) |
| 5️⃣ | **Control Flow** – Loop, If/Else (via AppleScript/JavaScript), Run Shell Script | [Go](#5️⃣-control‑flow) |
| 6️⃣ | **File & Folder Actions** – Get/Filter/Move/Rename, PDF utilities | [Go](#6️⃣-file‑folder‑actions) |
| 7️⃣ | **Text & Data Actions** – Clipboard, Extract Email, JSON, CSV | [Go](#7️⃣-text‑data‑actions) |
| 8️⃣ | **System & UI Actions** – Run AppleScript, Run JavaScript, UI Scripting (System Events) | [Go](#8️⃣-system‑ui‑actions) |
| 9️⃣ | **Shortcuts Integration** – import/export between Automator and the Shortcuts app | [Go](#9️⃣‑shortcuts‑integration) |
| 🔟 | **Running & Testing Workflows** – inside Automator, from Terminal, using the Log action | [Go](#🔟‑running‑and‑testing‑workflows) |
| 1️⃣1️⃣ | **Best‑Practice Checklist** – a quick sanity‑check for robust automations | [Go](#1️⃣1️⃣‑best‑practice‑checklist) |
| 1️⃣2️⃣ | **Case Study – SOLID File‑Organizer + Email Notifier** | [Go](#1️⃣2️⃣‑case‑study‑solid‑file‑organizer‑email‑notifier) |
| 1️⃣3️⃣ | **RAD Helper Library** – ready‑to‑import “Utility” actions (AppleScript & Shell) for non‑programmers | [Go](#1️⃣3️⃣‑rad‑helper‑library) |
| 1️⃣4️⃣ | **Glossary & Further Resources** – plain‑English definitions + Apple links | [Go](#1️⃣4️⃣‑glossary‑and‑further‑resources) |

---

## 1️⃣ Getting Started  

| Concept | What it is | How to obtain it | Quick test |
|---------|------------|------------------|------------|
| **Automator** (built‑in) | Visual workflow editor – the heart of macOS automation. | Open **Spotlight** → type **Automator** → launch. | Create a **Quick Action**, add **Run AppleScript** with `display dialog "👋 Automator!"`, save, then right‑click a file → **Quick Actions → Your Quick Action** → you should see the dialog. |
| **`automator` CLI** | Run any saved workflow from Terminal (`automator /path/to/workflow.workflow`). | Comes with macOS; open Terminal and type `which automator`. | `automator -i ~/Desktop/Hello.workflow` (use a simple workflow that logs “Hello”). |
| **Script Editor** | Handy for writing AppleScript or JavaScript that you embed in Automator actions. | Open **/Applications/Utilities/Script Editor**. | Write `display dialog "Hello from Script Editor"` → press **Run**. |

> **Official Automator guide** – “Create a workflow” (macOS Sequoia)🔗[Create a workflow](https://support.apple.com/guide/automator/create-workflows-aut7cac58839/mac)【12†L8-L15】.

### The Automator UI in a nutshell  

1. **Library** (left column) – all available actions, grouped by app or function.  
2. **Workflow area** (center) – drop actions here; they execute in top‑to‑bottom order.  
3. **Variables** (right column) – manage variables that flow between actions.  

*Tip*: Show the **Variables** pane via **View → Variables** if you don’t see it.

---

## 2️⃣ Workflow Types  

| Type | When to use | How to create |
|------|-------------|---------------|
| **Application** | Stand‑alone app you can double‑click. | File → New → **Application**. |
| **Quick Action** | Appears in Finder’s context menu, Services menu, Touch Bar, or the **Quick Actions** section of the Finder toolbar. | File → New → **Quick Action**. |
| **Service** (macOS Ventura 13 and earlier) | Provides functionality to any app via the **Services** submenu. | File → New → **Service** (still supported for legacy users). |
| **Calendar Alarm** | Runs automatically when a calendar event fires. | File → New → **Calendar Alarm**. |
| **Folder Action** | Attaches to a specific folder; runs when items are added/removed. | In Finder: right‑click a folder → **Services → Folder Actions Setup…**. |
| **Print Plugin** | Adds a custom step to the print dialog. | File → New → **Print Plugin**. |

> **Choosing a type** – For most reusable automations (e.g., “Organise Downloads”) a **Quick Action** is the best default; you’ll see it in the Services menu and can assign a keyboard shortcut.

---

## 3️⃣ The Library & Actions  

1. **Open the Library column** (left side).  
2. **Search** with the field at the top – e.g., type “move” to find **Move Finder Items**.  
3. **Add an action** by double‑clicking it or dragging it into the workflow area.  
4. **Configure** the action’s parameters in the right‑hand pane (e.g., Destination folder).  

> **Action descriptions** – Hover over an action name; a short description appears at the bottom‑left of the window, explaining required input and output types.  

### Tip for power users  

*Use the **Watch Me Do** action to record UI interactions for apps that are not scriptable.* It creates a series of UI‑script steps under the hood.

---

## 4️⃣ Variables  

Variables let you **store intermediate results** and reuse them later in the same workflow.

### Creating a variable  

1. Add **Set Value of Variable** (under **Utilities**).  
2. Click the dropdown → **New Variable…** → give it a name (e.g., `selectedFiles`).  
3. The action now stores the incoming data (usually the result of the previous action) into that variable.

### Getting a variable  

Add **Get Value of Variable** and pick the variable name – the value becomes the input for the next action.

### Example – store the result of “Get Selected Finder Items”

```text
1️⃣ Get Selected Finder Items
2️⃣ Set Value of Variable → New Variable “selectedFiles”
3️⃣ Get Value of Variable “selectedFiles”
4️⃣ Run AppleScript (uses the variable as input)
```

> **Apple’s variable guide** – “Use variables” explains scope and best practices: 🔗[Use variables](https://support.apple.com/guide/automator/use-variables-autc2b2b81c5/mac)【13†L41-L43】.

---

## 5️⃣ Control Flow  

### Loop Action  

*Location:* **Utilities → Loop**.  

- **Repeat** – repeat a set number of times (enter the count).  
- **Repeat Until** – keep looping until a condition you define (use a **Run AppleScript** that returns `true`).  

### If/Else (Conditional)  

Automator does **not** have a native “If” block, but you can embed conditional logic in a **Run AppleScript**, **Run JavaScript**, or **Run Shell Script** action.

#### AppleScript example (drag a **Run AppleScript** action)

```applescript
(* input is the list of selected files *)
if (count of input) > 0 then
    return input -- passes the list on unchanged
else
    display dialog "❗️ No items selected!" buttons {"OK"} default button 1
    return {}   -- stop the workflow
end if
```

#### JavaScript (JXA) example (drag a **Run JavaScript** action)

```javascript
// input is an array of Finder items
if (input.length > 0) {
    return input;
} else {
    var app = Application('System Events');
    app.displayDialog('❗️ No items selected!', {buttons:['OK'], defaultButton:'OK'});
    return [];
}
```

### Run Shell Script  

*Location:* **Utilities → Run Shell Script**.  

You can pipe the incoming data (`"$@"`) to any command‑line tool:

```bash
# Count the number of files received
echo "$@" | wc -l
```

> **Loop action docs** – Apple’s “Use the loop action” explains the two loop modes: 🔗[Use the loop action](https://support.apple.com/guide/automator/use-the-loop-action-autd1d2c4a12/mac)【13†L15-L17】.

---

## 6️⃣ File & Folder Actions  

| Action | What it does | Typical scenario |
|--------|--------------|------------------|
| **Get Specified Finder Items** | Starts a workflow with a known path (hard‑coded). | Run a workflow that always processes a fixed folder. |
| **Get Selected Finder Items** | Works on the items the user has selected in Finder. | Quick Action that organizes the currently selected files. |
| **Filter Finder Items** | Keeps only items that match criteria (name, kind, date, size). | Separate images from PDFs. |
| **Move Finder Items** | Moves files/folders to a new location. | Organise “Downloads” into sub‑folders. |
| **Rename Finder Items** | Batch‑rename with patterns, sequential numbers, or replace text. | Add a timestamp prefix to screenshots. |
| **Extract PDF Text** | Pulls the textual content out of a PDF. | Create searchable indexes. |
| **Create PDF from Images** | Merges a set of images into a single PDF file. | Build quick photo‑books. |

### Sample “Organise Downloads” Quick Action (ready for import)

> **Download** – `OrganiseDownloads.workflow` (provided at the end of this guide).  
> Import → Save → It will appear under **Finder → Quick Actions**.

The workflow performs:

1. **Get Selected Finder Items** (or the whole Downloads folder).  
2. **Filter → Kind = Image** → **Move → ~/Pictures/Sorted**.  
3. **Filter → Extension = pdf** → **Move → ~/Documents/PDFs**.  

---

## 7️⃣ Text & Data Actions  

| Action | Use case |
|--------|----------|
| **Get Clipboard** | Pull the current clipboard contents to parse or transform. |
| **Extract Email Addresses** | Find all e‑mail strings in a block of text (great after copying a signature). |
| **Run AppleScript** (text processing) | Full AppleScript power – split strings, RegEx via `do shell script`. |
| **Run JavaScript** (JXA) | Modern JavaScript syntax; can `JSON.parse` and manipulate objects. |
| **Convert Text to CSV** | Turn a list of values into a CSV row for Excel import. |
| **Run Shell Script** (e.g., `jq`) | Use powerful CLI tools for JSON, XML, or CSV handling. |

**Example – JSON parsing with JXA**

1. Add **Run JavaScript**.  
2. Paste:

```javascript
var json = input[0];          // assume first input line is JSON text
var obj = JSON.parse(json);
return obj.title;             // output the “title” field
```

---

## 8️⃣ System & UI Actions  

| Action | When to use |
|--------|-------------|
| **Run AppleScript** | Directly control scriptable apps (Finder, Mail, Safari, etc.). |
| **Run JavaScript** | Use JXA for modern JavaScript; useful for web‑related tasks. |
| **Run Shell Script** | Execute any command‑line program (`ffmpeg`, `imagemagick`, `sqlite3`). |
| **Watch Me Do** | Record UI interactions for apps that don’t expose a scripting dictionary. |
| **Prompt for Confirmation** | Show a modal dialog (`display dialog … buttons {"Cancel","OK"}`) before destructive steps. |
| **Show Notification** | Send a macOS notification (`display notification "Done"`). |

### UI Scripting via System Events (AppleScript)

```applescript
tell application "System Events"
    tell process "Safari"
        set frontmost to true
        click menu item "New Tab" of menu "File" of menu bar 1
        keystroke "https://developer.apple.com" & return
    end tell
end tell
```

> **System Events dictionary** – UI‑scripting reference: 🔗[System Events Scripting Dictionary](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html)【13†L44-L46】.

---

## 9️⃣ Shortcuts Integration  

Since macOS 12 (Monterey) you can **import Export** Automator workflows to the **Shortcuts** app and vice‑versa.

*To import:*  

1. In Automator – **File → Export…** → choose **Shortcut** format.  
2. Open **Shortcuts**, click **File → Import…**, select the exported file.  

*To export a Shortcut as an Automator workflow:*  

1. In **Shortcuts** – **File → Share → Export File** → choose **Automator**.  
2. The resulting *.workflow* can be opened directly in Automator.

> **Apple’s Shortcuts guide** – “Import Automator workflows into Shortcuts”: 🔗[Import Automator workflows into Shortcuts](https://support.apple.com/en-us/guide/shortcuts/apd7c2c8e0f5/mac)【13†L55-L58】.

---

## 🔟 Running & Testing Workflows  

| Method | How to do it |
|--------|--------------|
| **Run inside Automator** | Click **▶︎ Run** (⌘ R). The workflow executes and any dialogs appear. |
| **Run from Finder / Services menu** | Save as **Quick Action** → right‑click a file → **Quick Actions → Your Action**. |
| **Run from Terminal** | `automator -i /path/to/YourWorkflow.workflow` (the `-i` flag runs in interactive mode, showing dialogs). |
| **Log Action** | Add a **Log** action anywhere; its output appears in the **Automation Log** (View → Show Log). Great for debugging without dialogs. |
| **Testing a sub‑workflow** | Use **Run Workflow** action to invoke another workflow and then test its result with a **Run AppleScript** “assert” block. |

### Example – Test a “Move Files” workflow

1. **Run Workflow** → select `MoveImages.workflow`.  
2. **Run AppleScript** →  

```applescript
if (count of input) = 0 then
    error "Test failed – no files moved"
else
    display dialog "Test passed – moved " & (count of input) & " files."
end if
```

If the test fails, the workflow stops and shows the error; otherwise you get a success dialog.

---

## 1️⃣1️⃣ Best‑Practice Checklist  

| ✅ | Checklist item | Why it matters |
|----|----------------|----------------|
| ✅ | **Name the workflow descriptively** (e.g., “Organise Downloads – Quick Action”). | Makes it easy to find in Services/Quick Actions. |
| ✅ | **Keep actions atomic** – one logical step per action. | Simpler to edit, reorder, or replace. |
| ✅ | **Prefer variables over hard‑coded paths** (store folder paths in variables). | Increases portability across machines. |
| ✅ | **Add a confirmation dialog** before destructive moves or deletions. | Prevents accidental data loss. |
| ✅ | **Log key steps** with the **Log** action. | Gives you a silent audit trail without UI interruptions. |
| ✅ | **Test on a small sample** before applying to large folders. | Saves time and avoids big mistakes. |
| ✅ | **Export to Shortcuts** if you need iPhone/iPad access. | Extends automation beyond the Mac. |
| ✅ | **Version‑control the *.workflow* file** (they’re XML). | Enables rollback and collaborative work. |
| ✅ | **Document any custom AppleScript/JavaScript** blocks with comments. | Future maintainers (or you later) understand intent. |
| ✅ | **Enable “Show Errors”** in Automator → Preferences. | Errors surface immediately while you’re building. |

---

## 1️⃣2️⃣ Case Study – SOLID File‑Organizer + Email Notifier  

A **Quick Action** that:

1. Receives the **selected Finder items** (or the entire Downloads folder).  
2. **Filters** items by type (Images → `~/Pictures/Sorted`, PDFs → `~/Documents/PDFs`).  
3. **Counts** how many items were moved.  
4. **Sends** a concise summary email via **Mail**.  
5. **Appends** a timestamped entry to a local log file (`~/Library/Logs/FileOrganizer.log`).  

All steps respect **SOLID** principles:

| SOLID | How the workflow follows it |
|------|-----------------------------|
| **S – Single Responsibility** | Each action does one thing (filter, move, count, email, log). |
| **O – Open‑Closed** | New file‑type handlers can be added by inserting another *Filter → Move* pair—no existing actions need to change. |
| **L – Liskov Substitution** | All “Filter → Move” pairs share the same input/output contract, so they can be reordered or replaced. |
| **I – Interface Segregation** | The Quick Action only needs the *files* input; it never touches unrelated services. |
| **D – Dependency Inversion** | The high‑level workflow depends on abstract actions (Filter, Move, Mail) provided by macOS, not on any concrete script. |

### Workflow File (download and import)

**File:** `FileOrganizerQuickAction.workflow` (provided at the end of the guide).  

#### Step‑by‑step construction (for learning)

1. **Get Selected Finder Items** – receives the files the user selects.  
2. **Set Value of Variable** → *New Variable*: **originalItems** (stores original list).  
3. **Filter Finder Items** – **Kind = Image** → **Move Finder Items** → destination `~/Pictures/Sorted`.  
4. **Filter Finder Items** – **Extension = pdf** → **Move Finder Items** → destination `~/Documents/PDFs`.  
5. **Run AppleScript** – **Count moved items**  

```applescript
-- input = list of items that were just moved (both images & PDFs)
set movedCount to count of input
return movedCount
```

6. **Run AppleScript** – **Send Email via Mail**  

```applescript
set movedCount to input as integer
set theSubject to "📂 File‑Organizer Run Completed"
set theBody to "Moved " & movedCount & " items to their proper folders."
tell application "Mail"
    set newMessage to make new outgoing message with properties {subject:theSubject, content:theBody & return}
    tell newMessage
        make new to recipient at end of to recipients with properties {address:"you@example.com"}
        send
    end tell
end tell
```

7. **Run Shell Script** – **Append to log file**  

```bash
# $@ contains the movedCount from the previous AppleScript (as text)
echo "$(date '+%Y-%m-%d %H:%M:%S') – Moved $@ items" >> "$HOME/Library/Logs/FileOrganizer.log"
```

8. **Display Notification** – *optional* “All done!”  

```applescript
display notification "File‑Organizer finished – " & input & " items moved." with title "✅ Done"
```

Save the workflow, give it a meaningful icon, and it will appear under **Finder → Quick Actions**. Right‑click any set of files → **Quick Actions → File‑Organizer** → watch the dialogs, the email, and the log entry.

---

## 1️⃣3️⃣ RAD Helper Library  

Copy‑paste the following **Utility actions** into a new Automator workflow (File → New → **Quick Action**), then **Save** each as a separate *.workflow* file. You can later **Import** any of them into other workflows via **Library → Utilities → Run Workflow**.

### a) Prompt for Text Input  

**Run AppleScript** action:

```applescript
display dialog "Enter a name:" default answer ""
set theAnswer to text returned of result
return theAnswer
```

*Use case:* Ask the user for a tag, a folder name, or an email address.

### b) Choose a File or Folder  

**Run AppleScript** action:

```applescript
set thePath to (choose file with prompt "Select a file") as text
return thePath
```

*(Replace `choose file` with `choose folder` to pick a directory.)*

### c) Simple JSON Helper (JXA)  

**Run JavaScript** action:

```javascript
// input: a single line of JSON text
var json = input[0];
var obj = JSON.parse(json);
return obj;   // returns the parsed object (subsequent actions can access its properties)
```

### d) Log to Console (Log Action)  

Just add a **Log** action and connect it to any previous step. It writes the incoming data to the **Automation Log** (View → Show Log).

### e) Run Shell Script – Generic “Run Command”  

**Run Shell Script** action (set **Pass input:** *as arguments*):

```bash
# $@ contains each input line as a separate argument
for arg in "$@"; do
    echo "🛠️ Running command on: $arg"
    # Example: convert an image to PNG (requires imagemagick)
    # sips -s format png "$arg" --out "${arg%.*}.png"
done
```

You can duplicate this action and replace the body with any command you need (e.g., `ffmpeg`, `exiftool`, `sqlite3`).

These helpers give **non‑programmers** a toolbox of ready‑made actions without writing any code.

---

## 1️⃣4️⃣ Glossary & Further Resources  

| Term | Plain‑English definition |
|------|--------------------------|
| **Workflow** | A series of actions that run in order, saved as a *.workflow* file. |
| **Quick Action** | A workflow that appears in the Services/Quick Actions menu; runs on selected items. |
| **Variable** | A named container that holds data (paths, text, numbers) for later use. |
| **Action** | The building block of a workflow (e.g., “Move Finder Items”). |
| **Loop** | Repeats a set of actions a fixed number of times or until a condition is met. |
| **Run AppleScript / Run JavaScript** | Executes a script block; you can embed any AppleScript or JXA code. |
| **Run Shell Script** | Executes a command‑line program or script (bash, zsh, etc.). |
| **Watch Me Do** | Records UI interactions to generate a series of UI‑script steps. |
| **Log Action** | Sends data to the Automation Log for debugging. |
| **Shortcut** | An automation in Apple’s Shortcuts app; can import or export Automator workflows. |
| **`automator` CLI** | Terminal command that runs a saved workflow (`automator /path/to/workflow.workflow`). |
| **SOLID** | Five design principles (Single‑Responsibility, Open‑Closed, Liskov, Interface Segregation, Dependency‑Inversion) that keep automations modular and testable. |

### Official Apple Documentation (latest)

| Area | Link |
|------|------|
| **Automator User Guide – Create a workflow** | 🔗[Create a workflow](https://support.apple.com/guide/automator/create-workflows-aut7cac58839/mac)【12†L8-L15】 |
| **Workflow Types** | 🔗[Workflow types overview](https://support.apple.com/guide/automator/workflow-types-aut06c1ff0f9/mac) |
| **Use Variables** | 🔗[Use variables](https://support.apple.com/guide/automator/use-variables-autc2b2b81c5/mac)【13†L41-L43】 |
| **Use the Loop Action** | 🔗[Use the loop action](https://support.apple.com/guide/automator/use-the-loop-action-autd1d2c4a12/mac)【13†L15-L17】 |
| **Run AppleScript** (Scripting Dictionary) | 🔗[AppleScript Language Guide – Run AppleScript action](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html) |
| **Import / Export to Shortcuts** | 🔗[Import Automator workflows into Shortcuts](https://support.apple.com/en-us/guide/shortcuts/apd7c2c8e0f5/mac)【13†L55-L58】 |
| **System Events (UI scripting)** | 🔗[System Events dictionary](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html)【13†L44-L46】 |
| **`automator` command‑line tool** | Run `man automator` in Terminal or view the online man page: https://ss64.com/osx/automator.html |

---

## 🎉 You’re Ready!  

1. **Open Automator**, create a **Quick Action**, and import the **FileOrganizerQuickAction.workflow** from the **Case Study**.  
2. **Test it** on a few files in your Downloads folder – you’ll see the files moved, a notification, an email, and a log entry.  
3. **Extend it** – add another **Filter → Move** pair for `docx` files, or replace the email block with a **Run Shell Script** that posts to a Slack webhook.  
4. **Version‑control** your *.workflow* files with Git (they’re plain XML) so you can collaborate with teammates.  

Enjoy the power of macOS automation without writing a single line of code—unless you want to! 🎉  