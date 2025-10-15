### Table of Contents

1.  [What is `trash`?](https://www.google.com/search?q=%231-what-is-trash)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Basic Usage & Output](https://www.google.com/search?q=%234-basic-usage--output)
5.  [Managing Your Trashed Files](https://www.google.com/search?q=%235-managing-your-trashed-files)
6.  [Pro-Tip: Aliasing `rm` for Safety](https://www.google.com/search?q=%236-pro-tip-aliasing-rm-for-safety)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `trash`?

`trash` is a command-line utility that provides a safe alternative to the permanent `rm` command. Instead of irreversibly deleting files and directories, `trash` moves them to the macOS system Trash. This gives you a crucial safety net, allowing you to review or recover items you've deleted from the command line, just as you would with files you drag to the Trash in the Finder.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `trash` using a single Homebrew command:

```bash
brew install trash
```

### 4\. Basic Usage & Output

The syntax for `trash` is intentionally simple and mimics the `rm` command. The command itself is silent on success.

Let's assume you have a directory with the following files:

```bash
$ ls -F
archive.zip   image.png   project/   report.docx
```

  * **Trashing a single file:**

    ```bash
    trash report.docx
    ```

  * **Trashing multiple items:**
    You can provide multiple files and directories as arguments.

    ```bash
    trash archive.zip project/
    ```

  * **Using wildcards (globbing):**
    You can use wildcards just like with `rm`. For example, to trash all files ending in `.png`:

    ```bash
    trash *.png
    ```

**Example "After" State:** After running the commands above, your directory would be empty.

```bash
$ ls -F
# No output, as all files were trashed.
```

### 5\. Managing Your Trashed Files

  * **Viewing Trashed Items:**
    The `trash` command moves items to your user's hidden `.Trash` directory. You can list its contents to verify your files are there.

    ```bash
    ls ~/.Trash
    ```

    **Example Output:**

    ```text
    archive.zip   image.png   project/   report.docx
    ```

  * **Recovering Files:**
    The easiest way to recover a trashed file is to open the **Trash** in your Dock, find the item, right-click it, and select **"Put Back"**. Alternatively, you can manually move it back using the `mv` command:

    ```bash
    mv ~/.Trash/report.docx ~/path/to/original/location/
    ```

  * **Emptying the Trash:**
    You can empty the Trash from the command line by permanently deleting the contents of the `.Trash` directory. **Warning: This is irreversible, just like `rm`.**

    ```bash
    # Use with caution!
    rm -rf ~/.Trash/*
    ```

    The standard macOS method is to right-click the Trash icon in your Dock and select "Empty Trash".

### 6\. Pro-Tip: Aliasing `rm` for Safety

To prevent accidental permanent deletions, many developers replace the `rm` command with `trash` using a shell alias. This is a highly recommended practice.

1.  **Open your shell configuration file** (likely `~/.zshrc` on your Mac):

    ```bash
    open ~/.zshrc
    ```

2.  **Add the alias:**
    Add the following line to the end of the file.

    ```bash
    # Make `rm` safer by aliasing it to `trash`
    alias rm='trash'
    ```

3.  **Apply the changes:**
    Restart your terminal or source the file for the changes to take effect.

    ```bash
    source ~/.zshrc
    ```

    Now, whenever you type `rm`, you'll actually be running `trash`.

4.  **How to permanently delete (when you really mean it):**
    If you ever need to bypass your new, safer alias to permanently delete a file, simply add a backslash (`\`) before the command.

    ```bash
    # This will use the original rm command, not the alias
    \rm very_large_useless_file.log
    ```

### 7\. Uninstallation

If you need to remove `trash`, you can do so easily with Homebrew.

```bash
brew uninstall trash
```