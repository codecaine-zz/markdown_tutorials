# Zenity CLI Tutorial

<!-- markdownlint-disable MD013 MD012 -->

Zenity is a lightweight tool that shows GTK+ dialogs from the command
line or from shell scripts. This tutorial expands the basics with
practical examples, form dialogs, checklists, and progress integration
with commands. It also includes tips for robust scripting.

## Table of contents

1. Introduction
2. Installation
3. Quick examples
4. Dialog types (examples)
   - Info, Error, Warning
   - Question
   - Entry / Password
   - Calendar
   - File selection (single / multiple / save)
   - List / Checklist / Radiolist
   - Forms
   - Text info
   - Progress (with commands)
   - Notification
5. Parsing output & exit codes
6. Real-world scripts
7. Tips & tricks
8. References

---

## 1. Introduction

Zenity lets shell scripts interact with users via GUI dialogs. It is
cross-distro on Linux and can be installed on macOS via Homebrew.

## 2. Installation

### On macOS (Homebrew)

```sh
brew install zenity
```

### On Debian/Ubuntu

```sh
sudo apt-get update
sudo apt-get install zenity
```

## 3. Quick examples

Show a simple message:

```sh
zenity --info --text="Hello, World!"
```

Ask a yes/no question:

```sh
if zenity --question --text="Continue?"; then
    echo "User chose yes"
else
    echo "User chose no or closed the dialog"
fi
```

Get text input:

```sh
name=$(zenity --entry --title="Name" --text="Enter your name:")
echo "Hello, $name"
```

## 4. Dialog types (examples)

### Info / Error / Warning

```sh
zenity --info --title="Info" --text="Operation completed successfully."

zenity --warning --title="Warning" --text="This may take a while."

zenity --error --title="Error" --text="Something went wrong."
```

### Question (confirmations)

Return value example (useful in scripts):

```sh
if zenity --question --title="Remove file?" --text="Are you sure?"; then
    # exit code 0 = OK
    rm somefile
else
    # exit code 1 = cancelled/closed
    echo "Cancelled"
fi
```

### Entry (single-line) and Password

```sh
user=$(zenity --entry --title="Login" --text="Username:")
pass=$(zenity --password --title="Login" --text="Password:")
echo "User: $user (password is in variable 'pass')"
```

Use `--entry --hide-text` if your version lacks `--password`.

### Calendar

```sh
date=$(zenity --calendar --title="Pick a date")
echo "Selected: $date"
```

### File selection (single, multiple, save)

Single file:

```sh
file=$(zenity --file-selection --title="Open file")
echo "Chosen: $file"
```

Multiple files:

```sh
files=$(zenity --file-selection --multiple --title="Pick files" --separator="|")
# 'files' contains paths separated by |
IFS='|' read -r -a arr <<< "$files"
for f in "${arr[@]}"; do
    echo "File: $f"
done
```

Save dialog:

```sh
savepath=$(zenity --file-selection --save --confirm-overwrite --title="Save As")
echo "Save to: $savepath"
```

### List / Checklist / Radiolist

Simple list (single selection):

```sh
choice=$(zenity --list --title="Pick one" --column="Option" A B C)
echo "You chose: $choice"
```

Checklist (multiple selection):

```sh
checked=$(zenity --checklist --title="Choose packages" \
    --column="Install" --column="Package" \
    TRUE curl FALSE wget FALSE git)
# returns a '|' separated list of selected items
echo "Install: $checked"
```

Radiolist (single selection with custom labels):

```sh
selection=$(zenity --radiolist --title="Pick role" \
    --column="Pick" --column="Name" --column="Role" \
    TRUE alice Admin FALSE bob User)
echo "Selected: $selection"
```

### Forms (multiple fields)

Zenity supports `--forms` to collect several fields in one dialog. Example:

```sh
result=$(zenity --forms --title="User info" \
    --text="Fill user data" \
    --add-entry="Name" \
    --add-entry="Email" \
    --add-password="Password")

# fields are returned separated by | by default
IFS='|' read -r name email pass <<< "$result"
echo "Name=$name, Email=$email"
```

If your zenity build lacks `--forms`, you can chain multiple `--entry` dialogs instead.

### Text info / log viewer

Show a text file or command output in a scrollable window:

```sh
zenity --text-info --title="System Log" --filename="/var/log/syslog" --width=700 --height=500

# or from a command
uname -a | zenity --text-info --title="Uname"
```

### Progress (piped percentages or commands)

The progress dialog reads percentage values (0-100) from stdin. A practical pattern is to pipe progress updates from a loop or from a command that reports progress.

Example: simple simulated progress

```sh
(for i in 1 2 3 4 5; do
    echo $((i*20))
    sleep 1
done) | zenity --progress --title="Working" --text="Processing..." --percentage=0 --auto-close
```

Example: use rsync for a copy and approximate progress (rsync's --info=progress2 parses differently; this is an approximate pattern):

```sh
# Use pv to get byte progress (install pv via package manager)
pv largefile | zenity --progress --title="Copying" --text="Copying largefile..." --pulsate --auto-close
```

Note: `--auto-close` closes the dialog when it reaches 100%. `--auto-kill` will kill the piped process if the user cancels.

### Notification

```sh
zenity --notification --text="Background job finished" --listen
```

## 5. Parsing output & exit codes

Zenity prints user input to stdout (or uses exit codes). Typical patterns:

- Capture output: `val=$(zenity --entry ...)`
- Multiple values (forms, file-selection --multiple) are separated by `|` by default. Use `--separator` to change this.
- Exit codes: 0 = success/OK, 1 = cancel/closed, >1 = other errors.

Example handling both output and exit status:

```sh
value=$(zenity --entry --text="Enter value:" )
status=$?
if [ $status -eq 0 ]; then
    echo "OK: $value"
else
    echo "Cancelled or closed (status=$status)"
fi
```

## 6. Real-world scripts

Below are two small, practical scripts that show how to combine dialogs into useful tools.

### A: Select files, compress and show progress

```sh
#!/usr/bin/env bash

files=$(zenity --file-selection --multiple --separator="|" --title="Pick files to compress")
status=$?
if [ $status -ne 0 ]; then
    zenity --error --text="No files selected."
    exit 1
fi

outfile=$(zenity --file-selection --save --confirm-overwrite --title="Save archive as")
if [ -z "$outfile" ]; then
    zenity --error --text="No output selected."
    exit 1
fi

# convert |-separated list to array
IFS='|' read -r -a parts <<< "$files"

# create a tar.gz and pipe progress (rough approximation)
(tar -czf - "${parts[@]}" | pv -s $(du -cb "${parts[@]}" | tail -1 | awk '{print $1}') > "$outfile") \
    | zenity --progress --title="Compressing" --text="Archiving..." --pulsate --auto-close

zenity --info --text="Saved: $outfile"
```

Notes: this example uses `pv` for byte-level progress; install via your package manager if needed.

### B: Search text and show matches in a dialog

```sh
#!/usr/bin/env bash
pattern=$(zenity --entry --title="Search" --text="Enter search pattern:")
if [ -z "$pattern" ]; then
    zenity --warning --text="No pattern provided"
    exit 1
fi

dir=$(zenity --file-selection --directory --title="Search directory")
if [ -z "$dir" ]; then
    zenity --warning --text="No directory chosen"
    exit 1
fi

matches=$(grep -RIn --line-number --color=never -- "$pattern" "$dir" | sed -n '1,1000p')
if [ -z "$matches" ]; then
    zenity --info --text="No matches found."
else
    echo "$matches" | zenity --text-info --title="Matches for '$pattern'" --width=800 --height=600
fi
```

## 7. Tips & tricks

- Use `--width` and `--height` to control dialog size.
- Use `--title` and `--text` to make dialogs clear for non-technical users.
- Use `--separator` to choose a delimiter that won't appear in filenames (e.g., `--separator=$'\n'`).
- Check exit codes (`$?`) to detect Cancel/Close events and handle them gracefully.
- For complex UIs consider `yad` (fancier) or a small GTK script, but Zenity is ideal for quick, portable scripts.

## 8. References

- [Zenity manual](https://help.gnome.org/users/zenity/stable/)
- `man zenity`
- [Zenity GitHub](https://github.com/GNOME/zenity)

<!-- markdownlint-enable MD013 MD012 -->
