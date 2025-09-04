### Part 1: Introduction & Installation

**What is `pandoc`?**

`pandoc` is a command-line "Swiss Army knife" for documents. Its purpose is to convert files from one markup format into another. It can read dozens of formats (like Markdown, HTML, Microsoft Word `.docx`) and write to dozens more (including PDF, ePub, HTML slideshows, and more). It allows you to write in a simple format like Markdown and publish it anywhere, freeing your documents from being locked into a single application.

**Installation with Homebrew:**

```sh
brew install pandoc
```

-----

### Part 2: The Basic Conversion Workflow

The basic `pandoc` command structure is `pandoc [options] [input_file] -o [output_file]`. Pandoc is excellent at guessing the input and output formats from the file extensions.

**Example: Markdown to HTML**

This is the most common and straightforward conversion.

1.  First, create a simple Markdown file named `article.md`:

    ```markdown
    # The Solar System

    Our solar system is a fascinating place.

    ## Planets

    There are eight planets:

    * Mercury
    * Venus
    * Earth
    * Mars
    ```

2.  Now, run the `pandoc` command:

    ```sh
    pandoc article.md -s -o article.html
    ```

      * `article.md`: Your input file.
      * `-s` or `--standalone`: This is a crucial option. It produces a complete, standalone HTML file with `<html>`, `<head>`, and `<body>` tags, rather than just a fragment.
      * `-o article.html`: Your desired output file. Pandoc knows to create an HTML file because of the `.html` extension.

**Result:**
You will now have a file named `article.html` with this content:

```html
<!DOCTYPE html>
...
<h1 id="the-solar-system">The Solar System</h1>
<p>Our solar system is a fascinating place.</p>
<h2 id="planets">Planets</h2>
<p>There are eight planets:</p>
<ul>
<li>Mercury</li>
<li>Venus</li>
<li>Earth</li>
<li>Mars</li>
</ul>
...
</html>
```

-----

### Part 3: Working with Different Formats

Here are a few "recipes" to show off Pandoc's versatility.

**1. Convert Microsoft Word `.docx` to Markdown**

Have a `.docx` file you want to put under version control or edit in a plain text editor? Pandoc handles it easily.

**Command:**

```sh
pandoc "My Report.docx" -o report.md
```

**2. Create a Presentation from Markdown**

Pandoc can turn a simple Markdown file into a web-based Reveal.js slideshow. Structure your Markdown with `---` between slides and use headers for slide titles.

1.  Create `presentation.md`:

    ```markdown
    # Slide 1: Title

    Hello, world!

    ---

    # Slide 2: A List

    * Point 1
    * Point 2
    ```

2.  Run the command:

    ```sh
    pandoc presentation.md -t revealjs -s -o presentation.html
    ```

      * `-t revealjs`: Here we explicitly tell Pandoc the output format is `revealjs`.

**Result:**
Open `presentation.html` in your web browser. You'll have a fully working slideshow you can navigate with arrow keys.

-----

### Part 4: Creating PDFs — The Advanced Case

Creating high-quality PDFs is one of Pandoc's killer features, but it requires an external dependency: a **LaTeX** installation.

**1. Prerequisite: Install a LaTeX Engine**

Pandoc uses LaTeX to typeset PDFs. The recommended way to install it on macOS with Homebrew is:

```sh
# This is a large download (a few gigabytes)
brew install --cask mactex-no-gui
```

**2. Add Metadata to Your Markdown**

To create a professional-looking document, add a YAML metadata block to the very top of your `article.md` file.

```markdown
---
title: A Report on the Solar System
author: Alex Doe
date: September 3, 2025
fontsize: 12pt
---

# The Solar System

Our solar system is a fascinating place...
```

**3. Run the Pandoc Command**

Now, creating the PDF is as simple as changing the output extension.

**Command:**

```sh
pandoc article.md -o article.pdf
```

**Result:**
Pandoc will create a beautifully typeset PDF file named `article.pdf`, complete with a title block generated from your YAML metadata.

-----

### Part 5: Citations and Bibliographies

For academic and technical writing, `pandoc` has best-in-class support for citations.

1.  **Create a Bibliography File**
    Save a bibliography file named `refs.bib` in BibTeX format:

    ```bibtex
    @book{nasa,
      author    = {NASA},
      title     = {Exploring Our System},
      publisher = {NASA Press},
      year      = {2024},
    }
    ```

2.  **Add Citations in Markdown**
    In your `article.md`, cite your source using `[@key]` syntax.

    ```markdown
    ---
    title: A Report on the Solar System
    author: Alex Doe
    date: September 3, 2025
    bibliography: refs.bib
    ---

    Our solar system is a fascinating place [@nasa].
    ```

    *(Note: We also added the `bibliography` key to the metadata block.)*

3.  **Run Pandoc with `--citeproc`**
    This flag processes the citations and automatically builds a reference list.

    ```sh
    pandoc article.md --citeproc -o article_with_citations.pdf
    ```

**Result:**
The output PDF will have an in-text citation like "(NASA, 2024)" and a formatted "References" section at the end.

This tutorial covers the core workflows of `pandoc`. Its capabilities are vast, so be sure to check the excellent official documentation and run `pandoc --help` to learn more.