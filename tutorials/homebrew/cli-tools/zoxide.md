# zoxide: Smarter cd

## Table of Contents

1.  [What is `zoxide`?](#1-what-is-zoxide)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Shell Configuration (Crucial Step)](#4-shell-configuration-crucial-step)
5.  [Basic Usage & Output](#5-basic-usage--output)
6.  [Interactive Mode with `fzf`](#6-interactive-mode-with-fzf)
7.  [Managing the Database](#7-managing-the-database)
8.  [Uninstallation](#8-uninstallation)

-----

### 1\. What is `zoxide`?

`zoxide` is a "smarter `cd` command" that revolutionizes how you navigate your filesystem. It remembers the directories you use most frequently, allowing you to jump to them in a few keystrokes without needing to remember or type the full path. It works by ranking your directories based on how often and how recently you visit them.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `zoxide` using a single Homebrew command:

```bash
brew install zoxide
```

### 4\. Shell Configuration (Crucial Step)

For `zoxide` to work, it must be initialized in your shell's configuration file. This step hooks it into your terminal so it can replace the standard `cd` behavior.

1.  Open your configuration file (likely `~/.zshrc` on your Mac):

    ```bash
    open ~/.zshrc
    ```

2.  Add the following line to the **end** of the file:

    ```bash
    # Initialize Zoxide
    eval "$(zoxide init zsh)"
    ```

3.  Apply the changes by restarting your terminal or sourcing the file:

    ```bash
    source ~/.zshrc
    ```

    `zoxide` is now active. It will automatically learn from every `cd` command you use.

### 5\. Basic Usage & Output

The primary command is `z`. It takes a partial query and jumps to the best match.

  * **Navigate using a keyword:**
    Imagine you frequently visit `~/Documents/Code/Project-Tsunami`. Instead of typing the full path, you can just use a keyword.

    ```bash
    # From anywhere in your filesystem
    z tsunami
    ```

    **Example Output:** The output is simply your shell prompt changing to the new directory.

    ```text
    your-mac:~$ z tsunami
    your-mac:~/Documents/Code/Project-Tsunami$ 
    ```

  * **Navigate to a subdirectory:**
    You can also jump to a subdirectory of a matched path.

    ```bash
    # Jump directly into the 'src' folder of the best match for 'tsunami'
    z tsunami src
    ```

    **Example Output:**

    ```text
    your-mac:~$ z tsunami src
    your-mac:~/Documents/Code/Project-Tsunami/src$
    ```

### 6\. Interactive Mode with `fzf`

For cases where your query might have multiple matches, you can use `zi` to open an interactive selection menu. This requires a fuzzy finder like `fzf`.

1.  **Install `fzf` (if you don't have it):**

    ```bash
    brew install fzf
    ```

2.  **Use the interactive command `zi`:**

    ```bash
    # Interactively search for a directory matching 'project'
    zi project
    ```

    **Example Output:** `fzf` will open a menu. You can use arrow keys to select and press `Enter` to navigate.

    ```text
    > ~/Documents/Code/Project-Tsunami
      ~/Downloads/side-project
      ~/Work/archive/old-project

      3/3
    > project
    ```

### 7\. Managing the Database

You can directly query and manage the database `zoxide` builds.

  * **Query the database:** See which path `z` would navigate to without actually going there.

    ```bash
    zoxide query project
    ```

    **Example Output:**

    ```text
    /Users/yourname/Documents/Code/Project-Tsunami
    ```

  * **List all tracked directories with their scores:**

    ```bash
    zoxide query -l
    ```

    **Example Output:**

    ```text
    120.25  /Users/yourname/Documents/Code/Project-Tsunami
     75.00  /Users/yourname/Downloads
     40.10  /Users/yourname/.config
     10.00  /Users/yourname/Work/archive/old-project
    ```

### 8\. Uninstallation

If you need to remove `zoxide`:

1.  **Uninstall the package:**
    ```bash
    brew uninstall zoxide
    ```
2.  **Clean your shell config:** Open your `~/.zshrc` file again and remove the `eval "$(zoxide init zsh)"` line.