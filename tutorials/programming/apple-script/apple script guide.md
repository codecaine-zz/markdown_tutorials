# The AppleScript Automation Language: A Comprehensive Textbook Guide for macOS

Welcome to the ultimate learning guide for AppleScript automation on macOS (Sequoia / Sonoma / Apple Silicon)! This textbook is structured specifically to take you from a complete beginner (zero programming experience) to an advanced macOS automation specialist capable of controlling native apps, manipulating files in Finder, creating dialog-driven workflows, automating System Events, and invoking terminal commands.

> [!NOTE]
> **How to read this book:** Each section starts with a clear explanation of AppleScript concepts, followed by concrete, runnable code examples. Every script can be executed in macOS **Script Editor** or directly from Terminal using `osascript`.

> [!TIP]
> **Interactive Learning:** You can test any AppleScript snippet from your terminal using `osascript -e 'display dialog "Hello macOS!"'` or by pasting code into `/Applications/Utilities/Script Editor.app`.

## Repository Structure

This book is paired with a topic-based folder layout:

- `syntax_and_types/` for text, numbers, booleans, lists, records, and dates
- `control_flow/` for `if/else` conditionals, `repeat` loops, and `try/on error` traps
- `finder_and_files/` for file creation, folder scanning, file moving, and alias resolution
- `app_control/` for driving native apps (Safari, Notes, Calendar, Mail, Terminal)
- `system_events/` for GUI automation, keystrokes, menu items, and process monitoring

## Quick Start: Learn AppleScript by Building Things

1. Open Script Editor on your Mac:
   ```
   Spotlight -> Script Editor.app
   ```
2. Type your first script:
   ```applescript
   display dialog "Hello, macOS Sequoia Automation!" buttons {"OK"} default button 1
   ```
3. Click **Run** (⌘R) or run from Terminal:
   ```bash
   osascript -e 'display dialog "Hello, macOS Sequoia Automation!"'
   ```

Core AppleScript concepts to keep in mind early:
- AppleScript uses natural English-like syntax (e.g. `set myVar to "value"`).
- Lists use 1-based indexing (`item 1 of myGroup`).
- Target apps using `tell application "Finder" to ...`.
- Trapping errors prevents script crashes (`try ... on error ... end try`).

## Core Language Essentials to Learn Early

- `set` statements for variable assignment
- Lists (`{"Apple", "Banana"}`) and Records (`{name:"Alice", age:30}`)
- `if ... then ... else ... end if` conditional branching
- `repeat` loops (`repeat with i from 1 to 5`, `repeat with anItem in aList`)
- `tell application "App Name"` blocks
- File handling with `alias` and POSIX paths (`POSIX file "/Users/..."`)
- Command line shell execution via `do shell script`

## Must-Learn-Before-Building Checklist

Before writing complex automations, ensure you can:

- Run AppleScripts in Script Editor and from Terminal via `osascript`
- Distinguish between HFS colon paths (`Macintosh HD:Users:...`) and POSIX paths (`/Users/...`)
- Safely handle user input with `display dialog` and `choose file`
- Automate GUI elements with `tell application "System Events"`
- Catch runtime exceptions using `try / on error` blocks

## Why This Matters

AppleScript provides deep, native integration into macOS system architectures, allowing you to automate repetitive tasks across Finder, Safari, Terminal, Notes, and third-party Mac apps without installing external software.

## Suggested Learning Path

- **Chapter 1**: Variables, Data Types, and User Dialogs
- **Chapter 2**: Control Flow, Branching, and Error Trapping
- **Chapter 3**: Interacting with Finder & File Systems
- **Chapter 4**: Automating macOS Native Applications
- **Chapter 5**: System Events & GUI Scripting
- **Chapter 6**: Integrating Shell Scripts (`do shell script`)

---

## Textbook Chapters & Runnable Examples

### Chapter 1: Variables, Data Types, and User Dialogs

```applescript
-- 01_variables.applescript

-- Variables & Strings
set developerName to "Ada"
set greeting to "Hello, " & developerName & "!"

-- Numbers & Booleans
set itemCount to 42
set isAutomationActive to true

-- Lists (1-based indexing)
set fruits to {"Apple", "Banana", "Orange"}
set firstFruit to item 1 of fruits

-- Records (Key-Value Keyrings)
set userInfo to {userName:"Alice", userRole:"Admin"}

-- Interactive Input Dialog
set userResponse to display dialog "Enter your project name:" default answer "MyMacApp" buttons {"Cancel", "OK"} default button "OK"
set projectName to text returned of userResponse

display dialog "Project created: " & projectName & return & "Greeting: " & greeting
```

### Chapter 2: Control Flow, Loops, and Error Trapping

```applescript
-- 02_control_flow.applescript

set userAge to 20

if userAge ≥ 18 then
    display dialog "Access Granted: Adult user."
else
    display dialog "Access Restricted."
end if

-- Repeat Loops
set numbersList to {10, 20, 30}
set sum to 0

repeat with num in numbersList
    set sum to sum + num
end repeat

display dialog "Sum of numbers: " & sum

-- Error Handling with Try/Catch
try
    set resultValue to 10 / 0
on error errorMessage number errorNum
    display dialog "Caught error #" & errorNum & ": " & errorMessage
end try
```

### Chapter 3: Finder & File System Operations

```applescript
-- 03_finder.applescript

tell application "Finder"
    -- Get Desktop folder path
    set desktopPath to path to desktop folder as text
    
    -- Check if a folder exists
    set targetFolderName to "AutomationOutput"
    if not (exists folder (desktopPath & targetFolderName)) then
        make new folder at desktopPath with properties {name:targetFolderName}
    end if
end tell

-- Convert POSIX path to AppleScript alias
set posixPath to "/Users/Shared"
set macAlias to (POSIX file posixPath) as alias

display dialog "Folder created on Desktop!"
```

### Chapter 4: Driving Native Applications (Notes & Safari)

```applescript
-- 04_app_control.applescript

-- Create a quick note in Apple Notes
tell application "Notes"
    activate
    make new note at folder "Notes" with properties {body:"<h1>Automation Note</h1><p>Created via AppleScript on macOS Sequoia!</p>"}
end tell

-- Open a URL in Safari
tell application "Safari"
    activate
    open location "https://developer.apple.com"
end tell
```

### Chapter 5: Integrating Shell Commands (`do shell script`)

```applescript
-- 05_shell_scripting.applescript

-- Run bash/zsh command from AppleScript
set uptimeOutput to do shell script "uptime"
set currentDirectory to do shell script "pwd"

display dialog "System Uptime:" & return & uptimeOutput & return & return & "Current Directory: " & currentDirectory
```

---

## Your First Project: A Mac Desktop Organizer Script

```applescript
-- desktop_organizer.applescript

tell application "Finder"
    set desktopFolder to path to desktop folder
    set filesList to every file of desktopFolder
    
    set movedCount to 0
    repeat with aFile in filesList
        set fileExt to name extension of aFile
        if fileExt is "pdf" then
            -- Create PDFs folder if needed and move
            if not (exists folder "PDF_Documents" of desktopFolder) then
                make new folder at desktopFolder with properties {name:"PDF_Documents"}
            end if
            move aFile to folder "PDF_Documents" of desktopFolder
            set movedCount to movedCount + 1
        end if
    end repeat
    
    display dialog "Desktop Cleanup Complete! Moved " & movedCount & " PDF file(s)."
end tell
```

---

## Quick Reference & Guidelines for macOS ARM64

### Terminal Execution Commands

```bash
# Execute AppleScript file
osascript desktop_organizer.applescript

# Execute inline AppleScript string
osascript -e 'tell application "Finder" to display dialog "Quick Test"'
```

### Common Mistakes to Avoid

1. **Confusing 1-based indexing**: AppleScript lists start at index 1, not 0!
2. **Mixing HFS colons and POSIX slashes**: Use `POSIX file` conversion functions.
3. **Forgetting to activate target apps**: Use `activate` before UI automation steps.