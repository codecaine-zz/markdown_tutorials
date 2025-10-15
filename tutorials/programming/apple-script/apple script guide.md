# 📚 The **Ultimate AppleScript Guide for Absolute Beginners** (2025)  
*Plain‑language explanations, copy‑paste examples, and **VS Code support** so you can write and run AppleScript no matter which editor you prefer.*

---

## 🎯 Who This Guide Is For  

| ✅ | Description |
|---|-------------|
| **Never programmed before** | No prior scripting experience is assumed. |
| **Want to understand *why* AppleScript works** | We explain the reasoning behind each language feature. |
| **Prefer step‑by‑step, copy‑paste code** | Every section contains a tiny, runnable snippet you can try instantly. |
| **Will later need a production‑ready script** | The case‑study at the end shows a SOLID‑style “file‑organiser + email‑sender” script you can drop into any Mac workflow. |
| **Like VS Code** | All examples can be edited, built, and executed from VS Code (macOS). |

---

## 📖 How to Use This Document  

1. **Read the sections in order** – each builds on the previous one.  
2. **Copy the code into a new `.scpt` file** (or a plain `.applescript` text file).  
3. **Run the snippet** (see “Getting Started”).  
4. **When you see a “🔗” link, click it** – it opens Apple’s official docs.  
5. **When you finish the fundamentals, jump to the “Case Study”** to see everything in a realistic setting.

---

## 📦 Table of Contents  

| # | Topic | Quick link |
|---|-------|------------|
| 1️⃣ | **Getting Started** – Script Editor, `osascript`, VS Code | [Go](#1️⃣-getting‑started) |
| 2️⃣ | **Basic Syntax** – comments, literals, `tell` blocks | [Go](#2️⃣-basic-syntax) |
| 3️⃣ | **Data Types** – numbers, strings, lists, records, dates, file/alias, booleans | [Go](#3️⃣-data-types) |
| 4️⃣ | **Variables & Properties** – `set`, `global`, `property` | [Go](#4️⃣-variables‑and‑properties) |
| 5️⃣ | **Control Flow** – `if`, `else`, `repeat`, `exit repeat`, `considering` | [Go](#5️⃣-control‑flow) |
| 6️⃣ | **Handlers (Functions)** – parameters, returned values, `run` handler | [Go](#6️⃣-handlers‑functions) |
| 7️⃣ | **Error Handling** – `try`/`on error` blocks | [Go](#7️⃣-error‑handling) |
| 8️⃣ | **AppleScript Objects** – `application`, specifiers, `tell` blocks | [Go](#8️⃣-apple‑script‑objects) |
| 9️⃣ | **UI Scripting** – controlling UI via **System Events** | [Go](#9️⃣‑ui‑scripting) |
| 🔟 | **AppleScriptObjC** – bridging to Cocoa classes | [Go](#🔟‑applescriptobjc) |
| 1️⃣1️⃣ | **Running AppleScript in VS Code** – tasks, debugging, extensions | [Go](#1️⃣1️⃣‑running‑applescript‑in‑vscode) |
| 1️⃣2️⃣ | **Testing** – simple test harnesses, `do script` in Script Editor | [Go](#1️⃣2️⃣‑testing) |
| 1️⃣3️⃣ | **Debugging & Performance** – `log`, `beep`, Script Editor debugger | [Go](#1️⃣3️⃣‑debugging‑and‑performance) |
| 1️⃣4️⃣ | **Best‑Practice Checklist** – quick sanity‑check | [Go](#1️⃣4️⃣‑best‑practice‑checklist) |
| 1️⃣5️⃣ | **Case Study – SOLID File‑Organizer & Mailer** | [Go](#1️⃣5️⃣‑case‑study‑solid‑file‑organizer‑mailer) |
| 1️⃣6️⃣ | **RAD Helper Library** – ready‑to‑paste utility functions for non‑programmers | [Go](#1️⃣6️⃣‑rad‑helper‑library) |
| 1️⃣7️⃣ | **Glossary & Further Resources** – plain‑English definitions + links | [Go](#1️⃣7️⃣‑glossary‑and‑further‑resources) |

---

## 1️⃣ Getting Started  

| Concept | What it is | How to obtain it | Quick test |
|---------|------------|------------------|------------|
| **Script Editor** (macOS) | Apple’s built‑in IDE for AppleScript & JavaScript for Automation. | Open **/Applications/Utilities/Script Editor**. | `display dialog "👋 AppleScript!"` → click **OK**. |
| **`osascript` CLI** | Command‑line interpreter (`osascript -e '…'`). Works in Terminal, can be called from any language. | Comes with macOS (no install needed). | `osascript -e 'return 2+2'` → prints `4`. |
| **VS Code** (cross‑platform) | Free editor; with the “AppleScript” extension you get syntax‑highlighting, snippets, and task integration. | 1️⃣ Download VS Code → https://code.visualstudio.com. <br>2️⃣ In the Extensions view (⇧⌘X) install **AppleScript** by *kylescott*. <br>3️⃣ (optional) install **Code Runner** to execute a script with a click. | Create `Hello.applescript` → paste `display dialog "👋 from VS Code!"`. <br>Open integrated terminal (**Ctrl+`**) → `osascript Hello.applescript`. |
| **Script Libraries** | Re‑usable `.scpt` files you can `load script` or `script`‑import. | Save any script as `MyLib.scpt` and `load script "MyLib.scpt"` in another script. | See the “RAD Helper Library” section for a ready‑made `Utilities.scpt`. |

### Running AppleScript in VS Code (quick reference)

```jsonc
// .vscode/tasks.json  – place it in your project folder
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "Run AppleScript (osascript)",
            "type": "shell",
            "command": "osascript \"${file}\"",
            "group": "build",
            "presentation": { "reveal": "always" }
        }
    ]
}
```

*Open **Command Palette** → **Tasks: Run Task** → pick **Run AppleScript (osascript)** while your cursor is on a `.applescript` file. Output appears in the terminal pane.*

> **VS Code tip** – The AppleScript extension shows a light‑blue squiggle under syntax errors (just like Script Editor). Hover for a description.

---

## 2️⃣ Basic Syntax  

```applescript
-- 1️⃣ comment (two hyphens)  

display dialog "Hello, world!"   -- shows a modal dialog  

-- 2️⃣ a simple tell block (the core of AppleScript)
tell application "Finder"
    set desktopCount to count of items of desktop
    display dialog "Desktop has " & desktopCount & " items."
end tell
```

*Why `tell`?* Almost all AppleScript commands are **sent to a target application** (the *receiver*) – the `tell` block makes that explicit.

**Run in VS Code** → **Ctrl+Shift+B** → “Run AppleScript (osascript)”. The dialog will pop up.

---

## 3️⃣ Data Types  

| Type | Literal example | Typical use |
|------|----------------|-------------|
| **Number** | `42`, `3.14` | Arithmetic, counters. |
| **String** | `"Hello"` | Text display, file paths. |
| **Boolean** | `true`, `false` | Conditions. |
| **List** | `{1, 2, 3}` | Ordered collections, can contain mixed types. |
| **Record** | `{name:"Ada", age:30}` | Named fields (similar to dictionaries). |
| **Date** | `date "Thursday, 12 July 2025 10:00:00"` | Scheduling, timestamps. |
| **File** | `alias "Macintosh HD:Users:alice:Desktop:"` | File‑system references (folder or file). |
| **POSIX path** | `"~/Documents/file.txt"` (as string) | When you need a Unix‑style path for shell commands. |
| **Application object** | `application "Finder"` | Target of a `tell` block. |
| **Reference (specifier)** | `item 1 of listVar` | Precise location inside a container. |

> **Official reference** – Apple’s *Commands Reference* lists every built‑in class and its properties: 🔗[Commands Reference](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html)【12†L14-L16】.

---

## 4️⃣ Variables & Properties  

```applescript
global gCounter          -- accessible from any handler
property scriptName : "DemoScript"   -- persistent across runs

set localVar to "I am local"
set gCounter to 0          -- global assignment
set scriptName to "My Awesome Script"
```

*Key points*  

* **`set`** assigns a value.  
* **`global`** declares a variable that lives outside any handler.  
* **`property`** creates a value that *remains* between script runs when the script is saved as a compiled `.scpt`.  

> **AppleScript Lexical Conventions** explain the grammar for identifiers and keywords: 🔗[Lexical Conventions](https://developer.apple.com/conceptual/ASLR_lexical_conventions.html)【14†L0-L0】.

---

## 5️⃣ Control Flow  

```applescript
-- IF / ELSE
set score to 85
if score ≥ 90 then
    display dialog "A+"
else if score ≥ 80 then
    display dialog "A"
else
    display dialog "B or lower"
end if

-- REPEAT (counted)
repeat with i from 1 to 5
    display dialog "Round " & i
end repeat

-- REPEAT UNTIL (condition)
set tries to 0
repeat
    set tries to tries + 1
    if tries ≥ 3 then exit repeat
    display dialog "Try #" & tries
end repeat

-- CONSIDERING (change how AppleScript treats data)
considering numeric strings
    set result to "2" + 3   -- → 5 (numeric strings are coerced)
end considering
```

> **Why `considering`?** It temporarily changes type‑coercion rules – useful when you receive data from the shell as strings but need numbers【12†L34-L38】.

---

## 6️⃣ Handlers (Functions)  

```applescript
-- The special `run` handler is executed when the script starts.
on run
    greet("Ada")
end run

-- A custom handler with parameters and a returned value
on greet(name)
    set greeting to "Hello, " & name & "!"
    display dialog greeting
    return greeting
end greet

-- Handler with multiple parameters & default values (via `if` checks)
on addNumbers(a, b)
    return a + b
end addNumbers
```

*Handlers are the AppleScript equivalent of functions.* They can be placed anywhere in the script; the `run` handler is optional but gives you a clear entry point.

> **About Handlers** – Apple’s full reference: 🔗[About Handlers](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html)【12†L39-L41】.

---

## 7️⃣ Error Handling  

```applescript
try
    set f to alias "Macintosh HD:nonexistent:"
    display dialog "File exists"
on error errMsg number errNum
    display dialog "❗️Error " & errNum & ": " & errMsg buttons {"OK"} default button 1
end try
```

*`try … on error … end try`* catches any runtime error. The `number` parameter gives the OS error code (e.g., `-1708` for “command not understood”).

> **Error handling** – see the *Commands Reference* section on “try/on error” for the complete list of error numbers: 🔗[Commands Reference – Error Handling](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html)【12†L14-L16】.

---

## 8️⃣ AppleScript Objects  

```applescript
-- Targeting the Finder
tell application "Finder"
    set desktopFolder to folder "Desktop" of home
    set fileCount to count of items of desktopFolder
    display dialog "Desktop contains " & fileCount & " items."
end tell

-- Specifier shortcut – “Finder” is implied inside the block
tell application "Finder"
    set theName to name of file 1 of desktop
    display dialog "First desktop file: " & theName
end tell
```

*Every scriptable app exposes a dictionary of **classes**, **properties**, and **commands**.* Use **Script Editor → File → Open Dictionary…** to explore them.

> **AppleScript Fundamentals** – explains the object model in depth: 🔗[AppleScript Fundamentals](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/fundamentals/fundamentals.html)【12†L66-L68】.

---

## 9️⃣ UI Scripting  

> *UI‑Scripting lets you control any UI element (buttons, menus, windows) via the **System Events** scripting addition.*  

```applescript
tell application "System Events"
    -- Activate Safari
    tell process "Safari"
        set frontmost to true
        delay 0.5

        -- Click the "File" menu → "New Window"
        click menu item "New Window" of menu "File" of menu bar 1
    end tell
end tell
```

*Important*: UI scripting must be enabled in **System Settings → Privacy & Security → Accessibility**.  

> **UI Scripting docs** – Apple’s “UI scripting guide” (still hosted in the archive): 🔗[UI Scripting Overview](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/UI_Scripting/ui_scripting.html).

---

## 🔟 AppleScriptObjC  

AppleScriptObjC (ASObjC) lets you **bridge to Cocoa** – you can instantiate and use any Cocoa class directly from AppleScript.

```applescript
use framework "Foundation"
use scripting additions

-- Create an NSDate (current date & time)
set now to current date
set isoString to (now's description) as text
display dialog "ISO: " & isoString
```

*Key syntax*  

* `use framework "FrameworkName"` loads the Objective‑C runtime for that framework.  
* You can call methods with the **dot** syntax (`object's methodName:`) or the older AppleScript messaging syntax (`methodName of object`).  

> **AppleScriptObjC Guide** – Apple’s documentation: 🔗[AppleScriptObjC Release Notes](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptObjC/AppleScriptObjC.html).

---

## 1️⃣1️⃣ Running AppleScript in VS Code  

| Feature | How to enable |
|---------|----------------|
| **Syntax highlighting & snippets** | Install **AppleScript** extension (by *kylescott*). |
| **Run a single script** | Add the `tasks.json` snippet shown earlier and run **Ctrl+Shift+B → Run AppleScript (osascript)**. |
| **Debug with breakpoints** | VS Code can launch `osascript -l AppleScript -e '…'` under the **CodeLLDB** debugger – set a breakpoint with `debugger` statements: `debugger` (acts like `break` in Script Editor). |
| **Load a library** | Place reusable `.scpt` files in a `Libraries/` folder and `load script` them from any script. |
| **Auto‑completion** | The AppleScript extension offers completions for standard commands (e.g., `display dialog`, `set`). |

**Sample `launch.json`** (debug a script with a breakpoint):

```jsonc
{
  "version": "0.2.0",
  "configurations": [
    {
      "type": "lldb",
      "request": "launch",
      "name": "Debug AppleScript",
      "program": "/usr/bin/osascript",
      "args": ["${file}"],
      "stopAtEntry": false,
      "cwd": "${workspaceFolder}"
    }
  ]
}
```

Add `debugger` inside your script:

```applescript
display dialog "Before breakpoint"
debugger   -- ← VS Code will pause here
display dialog "After breakpoint"
```

Press **F5** to launch the debugger.

---

## 1️⃣2️⃣ Testing  

AppleScript doesn’t have a built‑in test framework, but you can use a simple **test harness** that runs a series of handlers and reports success/failure.

```applescript
-- Test harness (save as TestHarness.applescript)
property tests : {}

on addTest(testName, testHandler)
    set end of tests to {testName:testName, handler:testHandler}
end addTest

on runTests()
    repeat with t in tests
        try
            my (handler of t)()
            log "✅ " & (testName of t) & " passed"
        on error errMsg number errNum
            log "❌ " & (testName of t) & " failed: " & errMsg
        end try
    end repeat
end runTests
```

**Usage in a script**

```applescript
load script "/path/to/TestHarness.applescript"

-- Define a test
my addTest("addition works", ¬
    (→
        if (2 + 2) ≠ 4 then error "Math broken"
    )
)

my runTests()
```

*If you use VS Code, the **Test Explorer** extension can discover scripts that export a `runTests()` handler and display the results in a side‑panel.*

---

## 1️⃣3️⃣ Debugging & Performance  

| Tool | What you can see |
|------|------------------|
| **Script Editor Debugger** | Step‑through, watch variables, set breakpoints. |
| **`log` command** | Sends a line to the macOS Console (viewable in Console.app). |
| **`beep`** | Auditory cue when a script reaches a point. |
| **`debugger`** (LLDB) | Works with VS Code’s CodeLLDB integration. |
| **`time`** (shell) | Wrap an `osascript` call: `time osascript myScript.applescript` to see execution time. |

**Example – Using `log`**

```applescript
log "Starting heavy loop"
repeat with i from 1 to 10_000
    set _ to i * i
end repeat
log "Loop finished"
```

Open **Console.app**, filter by “AppleScript”, and you’ll see the two log lines.

---

## 1️⃣4️⃣ Best‑Practice Checklist  

| ✅ | Checklist item | Why it matters |
|----|----------------|----------------|
| ✅ | **Always `tell` a specific application** (don’t use `tell application "System Events"` for everything). | Keeps scripts stable; avoids accidental UI scripting. |
| ✅ | **Prefer `set` over `property` for transient data** | `property` persists between runs – only use when needed. |
| ✅ | **Keep handlers small & focused** (single responsibility). | Improves readability and makes testing easier. |
| ✅ | **Use `try/on error` for every external call** (file IO, UI actions). | Prevents script crashes and gives user‑friendly feedback. |
| ✅ | **Enable `considering numeric strings` only when necessary** | Avoids subtle type coercion bugs. |
| ✅ | **Store reusable code in libraries (`load script`)** | Encourages DRY (don’t repeat yourself) and easier versioning. |
| ✅ | **Document each handler with a leading comment** | Generates readable source for future maintainers. |
| ✅ | **Run through Script Editor’s “Check Grammar”** before distributing. | Catches syntax errors early. |
| ✅ | **Add a `run` handler as the clear entry point** | Makes the script self‑documenting. |
| ✅ | **If you expose UI, ask the user for permission first** | Improves security & user trust. |

---

## 1️⃣5️⃣ Case Study – SOLID File‑Organizer & Mailer  

Below is a **single‑file AppleScript** that does three things while respecting SOLID principles:

1. **S** – *Single Responsibility*: each handler does one thing (scan, move, email).  
2. **O** – *Open‑Closed*: you can add new file‑type actions without touching existing code.  
3. **L** – *Liskov*: new folder‑handlers inherit from the generic `FolderHandler` protocol.  
4. **I** – *Interface Segregation*: callers depend only on the methods they need (`scan`, `process`).  
5. **D** – *Dependency Inversion*: the high‑level script depends on abstract handlers, not concrete file‑system code.

### The script (save as `FileOrganizerMailer.applescript`)

```applescript
-- ==============================
-- SOLID File Organizer & Mailer
-- ==============================
use scripting additions
use framework "Foundation"

-- ==== 1️⃣ ABSTRACT PROTOCOL (interface) ====
on scanFolder(theFolder) → list
    error "Must be overridden"
end scanFolder

on processItem(itemInfo) → boolean
    error "Must be overridden"
end processItem

-- ==== 2️⃣ CONCRETE HANDLER: Images ====
script ImageHandler
    property parent : me   -- inherit abstract protocol

    on scanFolder(theFolder)
        tell application "Finder"
            set imgFiles to (files of theFolder whose name extension is "jpg" or name extension is "png")
            return imgFiles
        end tell
    end scanFolder

    on processItem(aFile)
        tell application "Finder"
            set destFolder to folder "Pictures:Sorted:" of home
            move aFile to destFolder
        end tell
        return true
    end processItem
end script

-- ==== 3️⃣ CONCRETE HANDLER: PDFs ====
script PDFHandler
    property parent : me

    on scanFolder(theFolder)
        tell application "Finder"
            set pdfFiles to (files of theFolder whose name extension is "pdf")
            return pdfFiles
        end tell
    end scanFolder

    on processItem(aFile)
        tell application "Finder"
            set destFolder to folder "Documents:PDFs:" of home
            move aFile to destFolder
        end tell
        return true
    end processItem
end script

-- ==== 4️⃣ HIGH‑LEVEL COORDINATOR ====
on run
    set sourceFolder to folder "Desktop:Incoming:" of home
    set handlers to {ImageHandler, PDFHandler}
    repeat with h in handlers
        my handleFolder(h, sourceFolder)
    end repeat
    my sendSummaryMail()
end run

on handleFolder(aHandler, srcFolder)
    try
        set itemsToProcess to my aHandler's scanFolder(srcFolder)
        repeat with i in itemsToProcess
            my aHandler's processItem(i) -- polymorphic call
        end repeat
    on error errMsg number errNum
        log "❗️Handler error: " & errMsg
    end try
end handleFolder

-- ==== 5️⃣ Simple email summary (uses Mail.app) ====
on sendSummaryMail()
    set subjectLine to "📂 File‑organiser run completed"
    set bodyText to "All files in ~/Desktop/Incoming have been sorted."
    tell application "Mail"
        set newMessage to make new outgoing message with properties {subject:subjectLine, content:bodyText & return}
        tell newMessage
            make new to recipient at end of to recipients with properties {address:"you@example.com"}
            send
        end tell
    end tell
    display notification "Organiser finished – email sent" with title "File Organizer"
end sendSummaryMail
```

#### How SOLID is expressed  

| SOLID | How the script follows it |
|------|---------------------------|
| **S – Single Responsibility** | `ImageHandler` only handles images; `PDFHandler` only handles PDFs. |
| **O – Open‑Closed** | To add a new file type (e.g., `DocsHandler`), create a new script object that implements `scanFolder` / `processItem`. No existing code changes. |
| **L – Liskov Substitution** | Both handlers can replace each other in the `handlers` list – they conform to the same abstract protocol. |
| **I – Interface Segregation** | The high‑level `handleFolder` uses only `scanFolder` + `processItem`. No extra, unused methods are forced on callers. |
| **D – Dependency Inversion** | The coordinator (`run`) depends on the abstract `handler` objects, not on the Finder directly. |

> **Run it** – Save the script, then `osascript FileOrganizerMailer.applescript` from Terminal or press **Ctrl+Shift+B** with the VS Code task defined earlier.  

> **Tip** – If you want to **extend** the script without editing the main file, place each handler in its own `.scpt` file inside a `Handlers/` folder and `load script` them at runtime.

---

## 1️⃣6️⃣ RAD Helper Library  

Copy‑paste these utilities into a file called `Utilities.scpt` and `load script` it from any script. They make rapid prototyping (especially for non‑programmers) painless.

```applescript
-- Utilities.scpt – ready‑to‑use helpers for AppleScript RAD
use scripting additions
use framework "Foundation"

-- 1️⃣ Prompt the user for a string (with a default)
on promptUser(promptMessage, defaultAnswer:"")
    display dialog promptMessage default answer defaultAnswer
    return text returned of result
end promptUser

-- 2️⃣ Choose a file via standard Open dialog
on chooseFile(promptMessage:"Select a file")
    set chosenFile to (choose file with prompt promptMessage)
    return chosenFile
end chooseFile

-- 3️⃣ Simple alert (no buttons, just OK)
on showAlert(messageText)
    display alert messageText buttons {"OK"} default button 1
end showAlert

-- 4️⃣ Run a shell command and get its stdout as a string
on runShell(commandString)
    set task to current application's NSTask's alloc()'s init()
    task's setLaunchPath:"/bin/bash"
    task's setArguments:{"-c", commandString}
    set pipe to current application's NSPipe's pipe()
    task's setStandardOutput:pipe
    task's launch()
    task's waitUntilExit()
    set data to pipe's fileHandleForReading's readDataToEndOfFile()
    set output to (current application's NSString's alloc()'s initWithData:data encoding:(current application's NSUTF8StringEncoding)) as text
    return output
end runShell

-- 5️⃣ JSON encode a record (requires macOS 10.15+)
on jsonEncode(recordData)
    set jsonData to current application's NSJSONSerialization's dataWithJSONObject:recordData options:0 |error|:(reference)
    if jsonData is missing value then error "JSON encode failed"
    set jsonString to current application's NSString'salloc()'s initWithData:jsonData encoding:(current application's NSUTF8StringEncoding)
    return jsonString as text
end jsonEncode

-- 6️⃣ Delay (seconds) – useful in UI scripts
on delaySeconds(sec)
    delay sec
end delaySeconds

-- 7️⃣ Log to Console.app (useful for debugging without popping dialogs)
on logMessage(msg)
    log msg
end logMessage
```

**Typical usage**

```applescript
load script "Utilities.scpt"

set name to my promptUser("What is your name?", defaultAnswer:"Ada")
my showAlert("Hello, " & name & "!")
my logMessage("User entered: " & name)

set filePath to my chooseFile()
set fileContents to read filePath
my logMessage("Read " & (length of fileContents) & " characters")
```

These helpers reduce the boiler‑plate that usually scares non‑programmers away from scripting.

---

## 1️⃣7️⃣ Glossary & Further Resources  

| Term | Plain‑English definition |
|------|--------------------------|
| **`tell` block** | “Hey, *application X*, do these commands.” |
| **`alias` / `file`** | References to a file or folder; `alias` uses Finder‑style colon paths, `POSIX file` uses `/` paths. |
| **`handler`** | Function‑like block; can take parameters and return a value. |
| **`run` handler** | The entry point that executes when the script is launched. |
| **`considering`** | Temporary change of type‑coercion rules. |
| **`script object`** | An object defined with `script … end script`; can have its own properties and handlers. |
| **`UI scripting`** | Controlling the UI (buttons, menus) via the **System Events** scripting addition. |
| **`AppleScriptObjC`** | Bridge that lets AppleScript call native Cocoa classes/methods. |
| **`load script`** | Import another compiled/script file (`.scpt`) at runtime. |
| **`property`** | Persistent value that survives across script runs when saved as a compiled script. |
| **`global`** | Variable visible to any handler in the script. |

### Official Documentation (latest)

| Area | Link |
|------|------|
| AppleScript Language Guide (AppleScript 2.0+) | 🔗[AppleScript Language Guide – Introduction](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/introduction/ASLR_intro.html) |
| Lexical Conventions | 🔗[Lexical Conventions](https://developer.apple.com/conceptual/ASLR_lexical_conventions.html) |
| Variables & Properties | 🔗[Variables and Properties](https://developer.apple.com/conceptual/ASLR_variables.html) |
| Handlers | 🔗[About Handlers](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html) |
| Commands Reference (full list of built‑in commands) | 🔗[Commands Reference](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_reference_forms.html) |
| UI Scripting Overview | 🔗[UI Scripting Guide](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/UI_Scripting/ui_scripting.html) |
| AppleScriptObjC Guide | 🔗[AppleScriptObjC Release Notes](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptObjC/AppleScriptObjC.html) |
| Script Editor Help (built‑in) | Open Script Editor → Help → *AppleScript Language Guide*. |
| VS Code AppleScript Extension | 🔗[AppleScript (kylescott) on VS Marketplace](https://marketplace.visualstudio.com/items?itemName=KyleScott.appleScript) |
| `osascript` man page | In Terminal: `man osascript` (covers command‑line usage). |

---

## 🎉 Congratulations!  

You now have:

* **A complete, up‑to‑date AppleScript learning path** (including VS Code workflow).  
* **A SOLID‑style production script** you can adapt to your own automation tasks.  
* **A “RAD Helper” library** that lets anyone—no matter their coding background—create useful scripts quickly.  

Open Script Editor, fire up VS Code, or run from Terminal and start automating your Mac like a pro. 🚀  