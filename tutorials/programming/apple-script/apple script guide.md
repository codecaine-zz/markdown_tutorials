# AppleScript Tutorial for Beginners (ARM Mac + Script Editor)

This tutorial covers AppleScript programming from basics to advanced usage with practical examples. We'll use the built-in Script Editor on macOS.

## Prerequisites & Setup

AppleScript is built into macOS, so no additional installation is required. Simply open:
```
Applications > Utilities > Script Editor
```

Configure Script Editor for better development:
1. Enable "Show Script menu in menu bar" (Script > Show Script Menu)
2. Preferences > General: Set default language to AppleScript
3. Preferences > Editing: Enable "Show line numbers" and "Show invisibles"

## 1. Basic Syntax and Structure

### Hello World Script
```applescript
-- This is a comment
display dialog "Hello, World!" buttons {"OK"} default button 1
```
[Documentation: display dialog](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_cmds.html#//apple_ref/doc/uid/TP40000983-CH216-SW1)

### Variables and Data Types
```applescript
-- Text (string)
set myName to "John"
set greeting to "Hello, " & myName

-- Numbers
set age to 25
set price to 19.99

-- Boolean
set isActive to true
set isComplete to false

-- Lists
set fruits to {"Apple", "Banana", "Orange"}
set firstFruit to item 1 of fruits

-- Records (like dictionaries)
set person to {name:"Alice", age:30, city:"New York"}
set personName to name of person

-- Date and time
set currentTime to current date
set tomorrow to (current date) + (1 * days)

-- Display results
display dialog greeting & return & "Age: " & age & return & "First fruit: " & firstFruit
```
[Documentation: Data types](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_data_types.html#//apple_ref/doc/uid/TP40000983-CH213-SW1)

## 2. Control Flow

### Conditional Statements (if/else)
```applescript
set userAge to 20

if userAge ≥ 18 then
    display dialog "You are an adult."
else if userAge ≥ 13 then
    display dialog "You are a teenager."
else
    display dialog "You are a child."
end if

-- Using "considering" for text comparisons
set userInput to "Hello"
considering case
    if userInput is "hello" then
        display dialog "Case sensitive match"
    else
        display dialog "Case does not match"
    end if
end considering
```
[Documentation: if statement](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_control_statements.html#//apple_ref/doc/uid/TP40000983-CH209-SW1)

### Try/Catch Error Handling
```applescript
try
    -- This will cause an error
    set result to 10 / 0
on error errorMessage number errorNumber
    display dialog "Error " & errorNumber & ": " & errorMessage
end try

-- Try with specific error handling
try
    set fileContents to read alias "Macintosh HD:nonexistent.txt"
on error
    display dialog "Could not read the file"
end try
```
[Documentation: try statement](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_control_statements.html#//apple_ref/doc/uid/TP40000983-CH209-SW6)

## 3. Loops

### Repeat Loops
```applescript
-- Repeat a specific number of times
repeat 5 times
    display dialog "Count: " & i
end repeat

-- Repeat with counter
repeat with i from 1 to 10
    log "Number: " & i
end repeat

-- Repeat with list items
set colors to {"Red", "Green", "Blue"}
repeat with color in colors
    log "Color: " & color
end repeat

-- Repeat while condition
set counter to 1
repeat while counter ≤ 5
    log "Counter: " & counter
    set counter to counter + 1
end repeat

-- Repeat until condition
set value to 10
repeat until value < 0
    log "Value: " & value
    set value to value - 2
end repeat
```
[Documentation: repeat statement](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_control_statements.html#//apple_ref/doc/uid/TP40000983-CH209-SW4)

## 4. Working with Files and Folders

### File Operations
```applescript
-- Get file information
set filePath to (path to desktop folder as string) & "example.txt"
try
    tell application "System Events"
        set fileExists to exists file filePath
        if fileExists then
            set fileSize to size of file filePath
            set fileDate to modification date of file filePath
            display dialog "File exists!" & return & "Size: " & fileSize & " bytes" & return & "Modified: " & fileDate
        else
            display dialog "File does not exist."
        end if
    end tell
end try

-- Create and write to a file
set fileContent to "This is line 1" & return & "This is line 2" & return & "This is line 3"
set fileHandle to open for access file filePath with write permission
set eof of fileHandle to 0
write fileContent to fileHandle
close access fileHandle

-- Read from a file
set fileHandle to open for access file filePath
set fileContents to read fileHandle
close access fileHandle
display dialog "File contents:" & return & fileContents
```
[Documentation: File operations](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_cmds.html#//apple_ref/doc/uid/TP40000983-CH216-SW10)

### Folder Operations
```applescript
-- List folder contents
tell application "System Events"
    set folderPath to path to desktop folder
    set folderItems to name of every item of folderPath
end tell

set itemList to ""
repeat with item in folderItems
    set itemList to itemList & item & return
end repeat

display dialog "Desktop items:" & return & itemList

-- Create a new folder
set newFolderName to "AppleScript Test"
set desktopPath to (path to desktop folder as string)
set newFolderPath to desktopPath & newFolderName

tell application "System Events"
    if not (exists folder newFolderPath) then
        make new folder at desktop with properties {name:newFolderName}
        display dialog "Created folder: " & newFolderName
    else
        display dialog "Folder already exists."
    end if
end tell
```

## 5. Working with Applications

### Basic Application Control
```applescript
-- Launch an application
tell application "Safari"
    activate
    -- Open a URL
    open location "https://www.apple.com"
end tell

-- Get application information
tell application "Finder"
    set frontWindow to front window
    set windowBounds to bounds of frontWindow
    display dialog "Front Finder window bounds: " & windowBounds
end tell

-- Quit an application
tell application "TextEdit"
    if it is running then
        quit
        display dialog "TextEdit has been quit."
    else
        display dialog "TextEdit is not running."
    end if
end tell
```
[Documentation: Application scripting](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/conceptual/ASLR_about_handlers.html#//apple_ref/doc/uid/TP40000983-CH206-SW1)

### TextEdit Automation
```applescript
tell application "TextEdit"
    activate
    -- Create a new document
    set newDoc to make new document
    -- Set document content
    set text of newDoc to "This is line 1" & return & "This is line 2" & return & "Date: " & (current date)
    -- Save the document
    set docName to "AppleScript Document"
    set desktopPath to (path to desktop folder as string)
    set docPath to desktopPath & docName & ".txt"
    save newDoc in file docPath
end tell

display dialog "Document saved to desktop."
```

### Safari Automation
```applescript
tell application "Safari"
    activate
    -- Get current tab information
    tell front document
        set currentURL to URL of current tab
        set pageTitle to name of current tab
    end tell
    
    display dialog "Current page:" & return & "Title: " & pageTitle & return & "URL: " & currentURL
    
    -- Open multiple tabs
    set urlsToOpen to {"https://www.apple.com", "https://developer.apple.com", "https://support.apple.com"}
    
    repeat with url in urlsToOpen
        tell front window
            set current tab to (make new tab with properties {URL:url})
        end tell
    end repeat
end tell
```

## 6. User Interaction

### Dialogs and Alerts
```applescript
-- Simple dialog
display dialog "Do you want to continue?" buttons {"Yes", "No"} default button 1

-- Dialog with input
set userResponse to display dialog "Enter your name:" default answer "Anonymous"
set userName to text returned of userResponse

-- Dialog with choices
set userChoice to choose from list {"Red", "Green", "Blue"} with prompt "Choose a color:"
if userChoice is not false then
    set selectedColor to item 1 of userChoice
    display dialog "You selected: " & selectedColor
end if

-- File selection
set chosenFile to choose file with prompt "Select a file:"
display dialog "You selected: " & (POSIX path of chosenFile)

-- Folder selection
set chosenFolder to choose folder with prompt "Select a folder:"
display dialog "You selected: " & (POSIX path of chosenFolder)
```
[Documentation: User interaction](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_cmds.html#//apple_ref/doc/uid/TP40000983-CH216-SW1)

## 7. System Information and Control

### Getting System Information
```applescript
-- System information
tell application "System Events"
    set sysInfo to system info
    set osVersion to system version of sysInfo
    set cpuName to CPU type of sysInfo
    set ramSize to RAM size of sysInfo
end tell

-- Current user information
tell application "System Events"
    set currentUser to name of current user
    set loginName to short user name of current user
end tell

-- Display information
display dialog "System Information:" & return & ¬
    "OS Version: " & osVersion & return & ¬
    "CPU: " & cpuName & return & ¬
    "RAM: " & ramSize & " GB" & return & ¬
    "Current User: " & currentUser
```

### System Control
```applescript
-- Volume control
set volume output volume 50  -- Set volume to 50%

-- Screen brightness (requires additional permissions)
-- tell application "System Preferences"
--     activate
--     set current pane to pane "com.apple.preference.displays"
-- end tell

-- Sleep display
tell application "System Events"
    sleep
end tell

-- Get current date and time
set now to current date
set timeString to time string of now
set dateString to date string of now

display dialog "Current time: " & timeString & return & "Current date: " & dateString
```

## 8. Working with Lists and Records

### List Operations
```applescript
-- Create and manipulate lists
set fruits to {"Apple", "Banana", "Orange", "Grape"}
set numberOfFruits to length of fruits

-- Add items
set end of fruits to "Pineapple"
set beginning of fruits to "Strawberry"

-- Access items
set firstFruit to item 1 of fruits
set lastFruit to item -1 of fruits
set secondFruit to item 2 of fruits

-- Remove items
set fruits to items 2 thru -1 of fruits  -- Remove first item

-- Sort list
set sortedFruits to fruits's contents  -- This doesn't sort, just copies
-- For actual sorting, you'd need a more complex approach

-- Display results
set listString to ""
repeat with fruit in fruits
    set listString to listString & fruit & ", "
end repeat

display dialog "Fruits: " & listString & return & "Count: " & numberOfFruits
```

### Record Operations
```applescript
-- Create records
set person to {name:"John", age:30, city:"New York"}
set book to {title:"AppleScript Guide", author:"Jane Smith", pages:250}

-- Access record properties
set personName to name of person
set bookTitle to title of book

-- Modify record properties
set age of person to 31
set pages of book to 300

-- Create nested records
set employee to {name:"Alice", department:"Engineering", contact:{email:"alice@company.com", phone:"555-1234"}}

set employeeEmail to email of contact of employee

-- Display information
display dialog "Person: " & personName & ", Age: " & age of person & return & ¬
    "Book: " & bookTitle & ", Pages: " & pages of book & return & ¬
    "Employee: " & name of employee & ", Email: " & employeeEmail
```

## 9. Text Processing

### String Manipulation
```applescript
set originalText to "Hello, World! This is AppleScript."

-- String length
set textLength to length of originalText

-- Substrings
set firstWord to characters 1 thru 5 of originalText as string
set lastWord to characters -10 thru -2 of originalText as string

-- Text replacement
set modifiedText to my replaceText(originalText, "AppleScript", "Automation")

-- Text case conversion
set upperText to my toUpper(originalText)
set lowerText to my toLower(originalText)

-- Word count
set wordCount to count of words of originalText

-- Character count
set charCount to count of characters of originalText

display dialog "Original: " & originalText & return & ¬
    "Length: " & textLength & return & ¬
    "First word: " & firstWord & return & ¬
    "Modified: " & modifiedText & return & ¬
    "Uppercase: " & upperText & return & ¬
    "Word count: " & wordCount

-- Handler to replace text
on replaceText(sourceText, searchText, replacementText)
    set {oldDelimiters, AppleScript's text item delimiters} to {AppleScript's text item delimiters, searchText}
    set textItems to text items of sourceText
    set AppleScript's text item delimiters to replacementText
    set newText to textItems as string
    set AppleScript's text item delimiters to oldDelimiters
    return newText
end replaceText

-- Handler to convert to uppercase
on toUpper(sourceText)
    set upperChars to "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    set lowerChars to "abcdefghijklmnopqrstuvwxyz"
    
    set resultText to ""
    repeat with i from 1 to length of sourceText
        set char to character i of sourceText
        set charIndex to offset of char in lowerChars
        if charIndex > 0 then
            set resultText to resultText & character charIndex of upperChars
        else
            set resultText to resultText & char
        end if
    end repeat
    return resultText
end toUpper

-- Handler to convert to lowercase
on toLower(sourceText)
    set upperChars to "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    set lowerChars to "abcdefghijklmnopqrstuvwxyz"
    
    set resultText to ""
    repeat with i from 1 to length of sourceText
        set char to character i of sourceText
        set charIndex to offset of char in upperChars
        if charIndex > 0 then
            set resultText to resultText & character charIndex of lowerChars
        else
            set resultText to resultText & char
        end if
    end repeat
    return resultText
end toLower
```

## 10. Date and Time Handling

### Date Operations
```applescript
-- Current date and time
set now to current date
set currentTime to time string of now
set currentDate to date string of now

-- Create specific dates
set specificDate to date "January 1, 2024"
set futureDate to (current date) + (7 * days)  -- 7 days from now
set pastDate to (current date) - (30 * days)   -- 30 days ago

-- Date components
set theYear to year of now
set theMonth to month of now
set theDay to day of now
set theWeekday to weekday of now

-- Time calculations
set oneHour to 1 * hours
set thirtyMinutes to 30 * minutes
set futureTime to (current date) + oneHour + thirtyMinutes

-- Format date for display
set formattedDate to (month of now) & " " & (day of now) & ", " & (year of now)

display dialog "Current time: " & currentTime & return & ¬
    "Current date: " & currentDate & return & ¬
    "Formatted date: " & formattedDate & return & ¬
    "Future date: " & futureDate & return & ¬
    "Weekday: " & theWeekday
```

## 11. Working with Clipboard

### Clipboard Operations
```applescript
-- Get clipboard contents
set clipboardContent to the clipboard

-- Set clipboard contents
set the clipboard to "This text is now on the clipboard"

-- Clipboard with styled text (in TextEdit)
tell application "TextEdit"
    activate
    set the clipboard to "Styled clipboard text"
    tell application "System Events"
        keystroke "v" using command down
    end tell
end tell

-- Clipboard with file references
set fileRef to alias "Macintosh HD:Users:Shared:"
set the clipboard to fileRef

display dialog "Clipboard content was: " & clipboardContent & return & ¬
    "Clipboard has been updated with new text."
```

## 12. Advanced Scripting Techniques

### Handlers (Functions)
```applescript
-- Simple handler
on greet(name)
    return "Hello, " & name & "!"
end greet

-- Handler with multiple parameters
on calculateArea(length, width)
    return length * width
end calculateArea

-- Handler that returns multiple values
on getMinMax(numbers)
    set minVal to item 1 of numbers
    set maxVal to item 1 of numbers
    
    repeat with num in numbers
        if num < minVal then set minVal to num
        if num > maxVal then set maxVal to num
    end repeat
    
    return {min:minVal, max:maxVal}
end getMinMax

-- Using the handlers
set greeting to my greet("Alice")
set area to my calculateArea(10, 5)

set numberList to {3, 7, 1, 9, 4}
set minMaxResult to my getMinMax(numberList)
set minValue to min of minMaxResult
set maxValue to max of minMaxResult

display dialog greeting & return & ¬
    "Area: " & area & return & ¬
    "Min: " & minValue & ", Max: " & maxValue
```

### Properties (Global Variables)
```applescript
-- Properties retain their values between script runs
property counter : 0
property lastRunDate : missing value

-- Increment counter
set counter to counter + 1

-- Update last run date
set lastRunDate to current date

display dialog "This script has run " & counter & " times." & return & ¬
    "Last run: " & lastRunDate
```

### Script Objects
```applescript
-- Create a script object
script Calculator
    on add(a, b)
        return a + b
    end add
    
    on multiply(a, b)
        return a * b
    end multiply
    
    on power(base, exponent)
        set result to 1
        repeat exponent times
            set result to result * base
        end repeat
        return result
    end power
end script

-- Use the script object
set sum to Calculator's add(5, 3)
set product to Calculator's multiply(4, 7)
set powerResult to Calculator's power(2, 8)

display dialog "5 + 3 = " & sum & return & ¬
    "4 × 7 = " & product & return & ¬
    "2^8 = " & powerResult
```

## 13. Working with System Events

### UI Scripting
```applescript
-- Note: UI scripting requires accessibility permissions
-- System Preferences > Security & Privacy > Privacy > Accessibility

tell application "System Events"
    -- Get information about the front process
    set frontAppName to name of first application process whose frontmost is true
    
    -- Get UI elements of the front application
    tell process frontAppName
        -- This would depend on the specific application
        -- set windowCount to count of windows
    end tell
end tell

-- Example: Working with the Dock
tell application "System Events"
    tell dock preferences
        set magnification to true
        set magnification size to 0.7
        set autohide to true
    end tell
end tell

display dialog "Front application: " & frontAppName
```

## 14. Practical Examples

### File Organizer Script
```applescript
-- This script organizes files on the desktop by extension
tell application "System Events"
    set desktopItems to every item of desktop
    set fileCount to 0
    
    repeat with anItem in desktopItems
        if class of anItem is file then
            set fileCount to fileCount + 1
            set fileName to name of anItem
            set fileExtension to name extension of anItem
            
            -- Create folder for extension if it doesn't exist
            set folderName to fileExtension & " Files"
            set folderPath to ((path to desktop folder) & folderName) as string
            
            if not (exists folder folderPath) then
                make new folder at desktop with properties {name:folderName}
            end if
            
            -- Move file to appropriate folder
            move anItem to folder folderPath
        end if
    end repeat
end tell

display dialog "Organized " & fileCount & " files on the desktop."
```

### System Monitor Script
```applescript
-- Get system information
tell application "System Events"
    set sysInfo to system info
    set cpuName to CPU type of sysInfo
    set ramSize to RAM size of sysInfo
    set osVersion to system version of sysInfo
end tell

-- Get current user
tell application "System Events"
    set currentUser to name of current user
end tell

-- Get current date and time
set now to current date
set timeString to time string of now
set dateString to date string of now

-- Display system information
display dialog "System Information:" & return & ¬
    "User: " & currentUser & return & ¬
    "OS: " & osVersion & return & ¬
    "CPU: " & cpuName & return & ¬
    "RAM: " & ramSize & " GB" & return & ¬
    "Date: " & dateString & return & ¬
    "Time: " & timeString
```

### Text File Processor
```applescript
-- Choose a text file to process
set chosenFile to choose file with prompt "Select a text file to process:"

-- Read the file
set fileHandle to open for access chosenFile
set fileContents to read fileHandle
close access fileHandle

-- Process the text (count words, lines, characters)
set lineCount to count of paragraphs of fileContents
set wordCount to count of words of fileContents
set charCount to count of characters of fileContents

-- Find most frequent word
set wordList to words of fileContents
set wordFrequency to {}
repeat with aWord in wordList
    set currentWord to contents of aWord
    set found to false
    repeat with i from 1 to length of wordFrequency
        if item 1 of item i of wordFrequency is currentWord then
            set item 2 of item i of wordFrequency to (item 2 of item i of wordFrequency) + 1
            set found to true
            exit repeat
        end if
    end repeat
    if not found then
        set end of wordFrequency to {currentWord, 1}
    end if
end repeat

-- Find most frequent word
set maxCount to 0
set mostFrequentWord to ""
repeat with wordItem in wordFrequency
    if item 2 of wordItem > maxCount then
        set maxCount to item 2 of wordItem
        set mostFrequentWord to item 1 of wordItem
    end if
end repeat

-- Display results
display dialog "File Analysis:" & return & ¬
    "File: " & (name of (info for chosenFile)) & return & ¬
    "Lines: " & lineCount & return & ¬
    "Words: " & wordCount & return & ¬
    "Characters: " & charCount & return & ¬
    "Most frequent word: " & mostFrequentWord & " (" & maxCount & " times)"
```

## Running and Saving Scripts

### How to Run Scripts
1. Open Script Editor
2. Write your script
3. Press Cmd+R or click the "Run" button
4. Save with Cmd+S

### Saving Options
```applescript
-- Scripts can be saved as:
-- 1. Script (.scpt) - editable AppleScript file
-- 2. Application (.app) - double-clickable app
-- 3. Text (.txt) - plain text file
-- 4. Script Bundle (.scptd) - bundle with resources
```

### Compiling and Running from Command Line
```bash
# Save your script as script.scpt first
osascript script.scpt

# Or run inline scripts
osascript -e 'display dialog "Hello from command line!"'
```

This tutorial provides a comprehensive introduction to AppleScript programming with practical examples. Each code block is designed to be copy-paste friendly for rapid development and learning. 

For more detailed information, refer to the official [AppleScript Language Guide](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/introduction/ASLR_intro.html) and [AppleScript Command Reference](https://developer.apple.com/library/archive/documentation/AppleScript/Conceptual/AppleScriptLangGuide/reference/ASLR_cmds.html).