# ShellCheck with Homebrew: Lint Shell Scripts

## Table of Contents

1.  [What is `shellcheck`?](#1-what-is-shellcheck)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Basic Usage & Output](#4-basic-usage--output)
5.  [Understanding the Output](#5-understanding-the-output)
6.  [Key Features & Examples](#6-key-features--examples)
7.  [A Note on Editor Integration](#7-a-note-on-editor-integration)
8.  [Uninstallation](#8-uninstallation)

-----

### 1\. What is `shellcheck`?

`shellcheck` is a static analysis tool, or "linter," for shell scripts. It's an essential utility that reads your script's source code and warns you about common bugs, stylistic issues, and dangerous constructs without ever running the script. Its goal is to help you write more robust, portable, and error-free `sh`/`bash` scripts by catching common pitfalls before they become problems.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `shellcheck` using a single Homebrew command:

```bash
brew install shellcheck
```

### 4\. Basic Usage & Output

The most common way to use `shellcheck` is to run it against a script file.

  * **Let's create a sample script with a few common errors.**
    Save the following content to a file named `test.sh`:

    ```bash
    #!/bin/bash

    FILES="*"
    # This will expand to all files in the current directory, which is not what is intended.
    echo The files are: $FILES

    # This comparison is problematic for several reasons.
    if [ $(whoami) = "root" ]; then
      echo "Running as root!"
    fi
    ```

  * **Command:**
    Now, run `shellcheck` on this file.

    ```bash
    shellcheck test.sh
    ```

  * **Example Output:**
    `shellcheck` will produce a detailed, colorful report pointing out the issues.

    ```text
    In test.sh line 5:
    echo The files are: $FILES
                         ^-- SC2086 (info): Double quote to prevent globbing and word splitting.

    In test.sh line 8:
    if [ $(whoami) = "root" ]; then
         ^-- SC2046 (warning): Quote this to prevent word splitting.
         ^-- SC2077 (warning): You should use '==' for string comparisons.

    For more information:
      https://www.shellcheck.net/wiki/SC2046 -- Quote this to prevent word ...
      https://www.shellcheck.net/wiki/SC2077 -- You should use '==' for st...
      https://www.shellcheck.net/wiki/SC2086 -- Double quote to prevent g...
    ```

### 5\. Understanding the Output

The output is the core feature of `shellcheck`. Let's break down one of the warnings:

`^-- SC2086 (info): Double quote to prevent globbing and word splitting.`

  * **`^--`**: A pointer to the exact part of the line with the issue.
  * **`SC2086`**: A unique error code for this specific type of warning. This is the most important part.
  * **`(info)`**: The severity level (e.g., `error`, `warning`, `info`).
  * **The Message**: A human-readable description of the problem.

The magic is in the `SCxxxx` codes. You can visit `https://www.shellcheck.net/wiki/SC<CODE>` (e.g., [shellcheck.net/wiki/SC2086](https://www.shellcheck.net/wiki/SC2086)) to get a detailed explanation of the issue, with examples of bad code and good code.

### 6\. Key Features & Examples

  * **Checking Piped Input:**
    You can check snippets of code by piping them into `shellcheck`. Use `-` to tell it to read from standard input.

    ```bash
    echo 'my_array=(1 2 3); echo ${my_array}' | shellcheck -
    ```

    **Example Output:**

    ```text
    In - line 1:
    echo ${my_array}
         ^-- SC2128 (warning): Expanding an array without an index only gives the first element.
    ```

  * **Ignoring Warnings:**
    Sometimes you have a good reason to ignore a specific warning. You can do this with a comment directive.

    **Ignoring a single line:**

    ```bash
    # This is a special case where we want word splitting.
    # shellcheck disable=SC2086
    echo The files are: $FILES 
    ```

    **Ignoring a warning for the entire file:** Place this directive at the top of your script.

    ```bash
    #!/bin/bash
    # shellcheck disable=SC2077
    ```

  * **Specifying Shell Dialect:**
    By default, `shellcheck` analyzes scripts based on their shebang (`#!/bin/bash`). You can override this to check for POSIX `sh` compliance, for example.

    ```bash
    shellcheck -s sh ./my_bash_script.sh
    ```

### 7\. A Note on Editor Integration

While `shellcheck` is a fantastic command-line tool, its true power is unleashed when integrated directly into your text editor (like VS Code, Vim, Sublime Text, etc.). Most editors have plugins that will run `shellcheck` in the background as you type, giving you instant feedback and highlighting errors directly in your code. This is the recommended way to use it for serious script development.

### 8\. Uninstallation

If you need to remove `shellcheck`, you can do so easily with Homebrew.

```bash
brew uninstall shellcheck
```