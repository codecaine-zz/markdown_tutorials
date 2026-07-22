# The Ultimate Automator & Shortcuts Guide for macOS: A Comprehensive Textbook Guide

Welcome to the ultimate learning guide for Apple Automator and Shortcuts on macOS (Sequoia / Sonoma / Apple Silicon)! This textbook guide takes you step-by-step from an absolute beginner with zero scripting experience to an advanced macOS power user capable of building drag-and-drop Quick Actions, Folder Actions, automated file organizers, shell script wrappers, and Shortcuts workflows.

> [!NOTE]
> **How to use this guide:** Each section covers a core macOS automation mechanism, complete with plain-language explanations, UI step-by-step instructions, and embedded AppleScript / Shell script action blocks that you can copy directly into Automator or the macOS Shortcuts app.

> [!TIP]
> **Quick Test:** Open **/System/Applications/Automator.app**, choose **Quick Action**, drag a **Run AppleScript** action into the workflow, paste `display dialog "Automator Active!"`, and click **Run** (⌘R).

## Repository Structure & Automation Types

- `quick_actions/` for context-menu actions in Finder, Touch Bar, and Services menu
- `applications/` for standalone double-clickable `.app` workflows
- `folder_actions/` for workflows attached to specific directories that trigger when items are added
- `calendar_alarms/` for scheduled tasks running on a specified clock or calendar trigger
- `shortcuts_integration/` for bridging legacy Automator workflows with the modern macOS Shortcuts app

## Quick Start: Learn Automator by Building Things

1. Launch Automator on macOS:
   ```
   Press ⌘+Space -> Type "Automator" -> Press Enter
   ```
2. Select **File -> New**, choose **Quick Action**, and set:
   - **Workflow receives current**: `files or folders` in `Finder.app`.
3. In the left Library pane, search for **Run Shell Script**.
4. Drag **Run Shell Script** to the right workflow builder area.
5. Set **Pass input**: `as arguments`.
6. Replace the script content with:
   ```bash
   for f in "$@"; do
       echo "Processing file: $f" >> ~/Desktop/automation_log.txt
   done
   ```
7. Save the workflow as `Log Selected Files`.
8. Right-click any file in Finder -> **Quick Actions -> Log Selected Files**.

## Core Essentials to Learn Early

- Distinguishing between **Quick Actions**, **Applications**, and **Folder Actions**
- Passing action outputs to downstream actions (pipeline flow)
- Setting and retrieving workflow **Variables**
- Embedding **Run Shell Script** (`zsh`/`bash`) and **Run AppleScript** for complex logic
- Exporting workflows and assigning global keyboard shortcuts in **System Settings -> Keyboard -> Keyboard Shortcuts -> Services**

## Must-Learn-Before-Building Checklist

Before distributing workflows to teammates, ensure you:

- Test execution inside Automator using the **Run** (⌘R) button and inspect the **Log** pane
- Verify file permissions (Granting Accessibility / Full Disk Access in **System Settings -> Privacy & Security**)
- Confirm that actions handle single and multiple file selections cleanly
- Wrap shell scripts in double quotes (`"$f"`) to safely handle file paths containing spaces

## Why This Matters

Automator and Shortcuts allow non-programmers and developers alike to eliminate repetitive tasks—like batch image resizing, PDF merging, file sorting, and automated email notifications—directly within the macOS UI without installing third-party tools.

---

## Textbook Chapters & Hands-on Workflows

### Chapter 1: Workflow Types & Choosing the Right Automation

| Type | Best Use Case | How to Trigger |
|------|--------------|----------------|
| **Quick Action** | File tools, text converters, Finder utilities | Right-click in Finder, Services Menu, Touch Bar |
| **Application** | Standalone utilities, desktop shortcuts | Double-click `.app` bundle, drop files onto app icon |
| **Folder Action** | Auto-sorting Downloads or Desktop items | Drops file into attached folder automatically |
| **Calendar Alarm** | Daily/Weekly scheduled system maintenance | Fires automatically via Apple Calendar event |

### Chapter 2: Drag-and-Drop Library Actions & Variables

1. **Get Specified Finder Items**: Allows picking test files.
2. **Copy Finder Items**: Copies inputs to a destination folder.
3. **Rename Finder Items**: Batch renames files with date stamps or sequential numbers.

### Chapter 3: Embedded Shell Scripting in Automator

Combine native macOS command line utilities (`sips`, `ffmpeg`, `textutil`) with Automator UI:

```bash
# Batch Convert Images to WebP using sips in Automator
for f in "$@"; do
    filename="${f%.*}"
    /usr/bin/sips -s format webp "$f" --out "${filename}.webp"
done
```

### Chapter 4: Embedded AppleScript for UI Notifications

```applescript
on run {input, parameters}
    set fileCount to count of input
    display notification ("Successfully processed " & fileCount & " item(s).") with title "macOS Automator" sound name "Glass"
    return input
end run
```

### Chapter 5: Folder Action – Auto-Organizing Downloads

Attach this Folder Action to `~/Downloads`:

```bash
# Auto-sort PDFs into Downloads/PDFs
for f in "$@"; do
    if [[ "$f" == *.pdf ]]; then
        mkdir -p ~/Downloads/PDFs
        mv "$f" ~/Downloads/PDFs/
    fi
done
```

---

## Your First Production Workflow: SOLID File Organizer + Notifier

This Quick Action sorts selected files into categorised directories (`Images`, `Documents`, `Code`) and sends a macOS notification upon completion.

```bash
# solid_organizer.sh
for f in "$@"; do
    ext="${f##*.}"
    dir="$(dirname "$f")"
    
    case "$ext" in
        jpg|jpeg|png|webp|gif)
            target="$dir/Images"
            ;;
        pdf|docx|txt|md)
            target="$dir/Documents"
            ;;
        js|ts|py|c|cpp|rs|v)
            target="$dir/Code"
            ;;
        *)
            target="$dir/Misc"
            ;;
    esac
    
    mkdir -p "$target"
    mv "$f" "$target/"
done

osascript -e 'display notification "Selected files organized successfully!" with title "File Organizer"'
```

---

## Quick Reference & Guidelines for macOS ARM64

### Terminal Command Line Execution

```bash
# Execute saved Automator workflow from Terminal
automator /path/to/workflow.workflow

# Run with specific input files
automator -i ~/Desktop/sample.pdf /path/to/MergePDFs.workflow
```

### Best Practice Checklist

1. **Handle Spaces in Paths**: Always quote variables in shell actions (`"$f"`).
2. **Add User Notifications**: End long workflows with a notification or sound.
3. **Grant Privacy Permissions**: Ensure Automator has Full Disk Access if managing files outside user folders.