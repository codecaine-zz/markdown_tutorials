### Part 1: Introduction & Installation

**What is `glow`?**

`glow` is a command-line tool for rendering Markdown files. While tools like `cat` show plain text and `bat` adds syntax highlighting, `glow` takes it a step further by actually *rendering* the Markdown with proper formatting, styles, and layout, making it perfect for reading `README.md` files and other documentation directly in your terminal.

**Installation with Homebrew:**

```sh
brew install glow
```

-----

### Part 2: Basic Usage - Viewing Local Files

The most common use for `glow` is to view a local Markdown file.

Let's assume you have a file named `README.md` with the following content:

```markdown
# My Awesome Project
A tool to demonstrate **glow**.

## Features
* Renders lists correctly
* Shows off `inline code`
* Even handles tables!

| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
```

**Command:**

```sh
glow README.md
```

**Result:**
Instead of plain text, `glow` will open the file in a pager (like `less`) and render it beautifully. The output will look something like this (imagine it's themed with colors):

```
                               My Awesome Project
 A tool to demonstrate glow.

 Features
   • Renders lists correctly
   • Shows off inline code
   • Even handles tables!

 │ Header 1 │ Header 2 │
 ├──────────┼──────────┤
 │ Cell 1   │ Cell 2   │

```

**Navigation:**

  * Use the **Up/Down Arrow Keys** or **j/k** to scroll.
  * Press **q** to quit and return to your prompt.

-----

### Part 3: Viewing Remote Files

`glow`'s power isn't limited to local files. You can render Markdown from the web or directly from GitHub repositories.

**1. From a URL:**
You can give `glow` a URL to a raw Markdown file.

**Command:**

```sh
glow https://raw.githubusercontent.com/charmbracelet/glow/main/README.md
```

**Result:**
`glow` will download and render the `README.md` from the official `glow` repository.

**2. From a GitHub Repository:**
`glow` has a special syntax for GitHub. It will automatically find and display the `README.md` of the repository.

**Command:**

```sh
glow github.com/charmbracelet/glow
```

**Result:**
Same as above. This is just a much cleaner and easier way to do it.

-----

### Part 4: The Stash - Your Personal Reading List

`glow` includes a "stash" feature that lets you save Markdown files to a local library, like a "read it later" list.

**1. Stashing a File:**
Use the `stash` subcommand to save a local or remote document.

**Command:**

```sh
# Stash a local file
glow stash README.md

# Stash a document from a GitHub repo
glow stash github.com/stedolan/jq/docs/manual/manual.md
```

**2. Listing Your Stash:**
To see everything you've saved, just run:

**Command:**

```sh
glow stash
```

**Result:**

```
  ID   Date                          NOTE
 ╭────┬─────────────────────────────┬─────────────────────────────────────────────────╮
 │ 1  │ 2025-09-03 18:50:00 │ README.md                                       │
 │ 2  │ 2025-09-03 18:51:00 │ manual.md from github.com/stedolan/jq/docs/ma… │
 ╰────┴─────────────────────────────┴─────────────────────────────────────────────────╯
```

**3. Reading from Your Stash:**
Use the ID from the list to read a stashed item.

**Command:**

```sh
glow stash 1
```

**4. Deleting from Your Stash:**
Use the `-d` flag and the ID to remove an item.

**Command:**

```sh
glow stash -d 1
```

-----

### Part 5: Customization & Scripting

**1. Using Different Styles:**
`glow` comes with built-in themes. You can pass a style with the `-s` flag.

**Command:**

```sh
glow -s light README.md
# Or for a dark theme
glow -s dark README.md
```

*(You can create your own custom styles with a config file.)*

**2. Using Without a Pager:**
For scripting or piping, you might want the rendered output sent directly to the standard output without the interactive pager. Use the `-p` flag.

**Command:**

```sh
glow -p README.md > rendered_output.txt
```

**Result:**
The file `rendered_output.txt` will contain the fully rendered (but uncolored) text of the Markdown.

This covers the complete workflow of using `glow` from basic reading to managing your own library of documents. You can always see all options with `glow --help`.