# fzf: Fuzzy Finder for the Command Line

## Table of Contents

1. [What is `fzf`?](#1-what-is-fzf)
2. [Prerequisites](#2-prerequisites)
3. [Installation](#3-installation)
4. [Shell Configuration (Crucial Step)](#4-shell-configuration-crucial-step)
5. [Default Keybindings & Output](#5-default-keybindings--output)
6. [Usage with Pipes (Advanced Power)](#6-usage-with-pipes-advanced-power)
7. [Uninstallation](#7-uninstallation)

-----

### 1\. What is `fzf`?

`fzf` is a blazing-fast, general-purpose, command-line fuzzy finder. It allows you to interactively filter any list of items (files, command history, processes, git branches, etc.) by typing parts of their names. It reads a list from standard input, opens a TUI (Terminal User Interface) to filter it, and writes the selected item to standard output, making it an incredibly powerful tool for composing commands.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `fzf` using a single Homebrew command:

```bash
brew install fzf
```

### 4\. Shell Configuration (Crucial Step)

After installation, `fzf` provides an install script that automatically configures your shell with useful keybindings and settings. This step is essential for the best experience.

1.  **Run the installation script:**

    ```bash
    $(brew --prefix)/opt/fzf/install
    ```

2.  The script will ask you a few questions. It's generally safe to answer yes (`y`) to all of them:

      * `Do you want to enable fuzzy auto-completion?` (Recommended)
      * `Do you want to enable key bindings?` (Recommended)
      * `Do you want to update your shell configuration file?` (Required)

3.  Apply the changes by restarting your terminal or sourcing your config file (likely `~/.zshrc`):

    ```bash
    source ~/.zshrc
    ```

### 5\. Default Keybindings & Output

The setup script enables three powerful keybindings by default.

  * **`Ctrl+T` — Find and paste files or directories**
    Type part of a command (e.g., ` cat  `) and press `Ctrl+T`. `fzf` will open a file finder. Selecting a file pastes its path onto your command line.

    **Example Interaction:** You type ` cat  ` and press `Ctrl+T`. A finder opens.

    ```text
    > src/api/client.js
      src/components/Header.jsx
      README.md
      package.json
      .gitignore

      15/15
    > head
    ```

    After selecting `src/components/Header.jsx` and pressing `Enter`, your terminal prompt will look like this:

    ```bash
    $ cat src/components/Header.jsx_
    ```

  * **`Ctrl+R` — Find and paste commands from history**
    Press `Ctrl+R` to open a fuzzy search of your entire command history. This is a powerful replacement for the default reverse search.

    > **Note on `atuin` conflict:** If you have also installed and configured `atuin`, it will take control of `Ctrl+R` by default. Your shell will use whichever tool's `init` script is loaded last in your `~/.zshrc` file. To choose `fzf` for `Ctrl+R`, ensure its setup lines are *after* `atuin`'s, or comment out the `atuin` init script.

    **Example Interaction:** Press `Ctrl+R` and type `install`.

    ```text
    > brew install fzf
      npm install
      brew install atuin

      3/1502
    > install
    ```

    Pressing `Enter` will paste `brew install fzf` onto your command line, ready to be executed.

  * **`Alt+C` — Find and `cd` into a directory**
    Press `Alt+C` (or `Option+C` on macOS) from anywhere to fuzzy-find a directory, then immediately `cd` into it.

    **Example Interaction:** Press `Alt+C` and type `comp`.

    ```text
    > src/components
      node_modules/@types/react-dom/components

      2/43
    > comp
    ```

    Pressing `Enter` will execute the `cd` command for you. Your prompt will change:

    ```bash
    $ cd /Users/yourname/project/src/components
    your-mac:~/project/src/components$
    ```

### 6\. Usage with Pipes (Advanced Power)

`fzf`'s true power comes from using it in a command pipeline. You can pipe any list into `fzf` to select an item from it.

  * **Example: Interactively kill a process**

    1.  List all running processes and pipe them into `fzf`.
    2.  Select the process you want to kill.
    3.  Pipe the selected line to `awk` to extract the process ID (PID).
    4.  Pipe the PID to `xargs` to run the `kill -9` command.

    **Command:**

    ```bash
    ps aux | fzf | awk '{print $2}' | xargs kill -9
    ```

    **Example Interaction:** Running `ps aux | fzf` will open this UI:

    ```text
    > yourname      85341   0.0  0.1  4112252  25804   ??  S    Tue09AM   0:15.19 /Applications/Visual Studio Code.app/Contents/Frameworks/Code Helper (Renderer).app/Contents/MacOS/Code Helper (Renderer)
      yourname      85278   0.0  0.1  4074212  16872   ??  S    Tue09AM   0:00.38 /usr/local/Cellar/node/18.12.1/bin/node /Users/yourname/project/node_modules/.bin/vite

      120/120
    > vite
    ```

    After you select the `vite` process and press `Enter`, the rest of the command (`| awk ... | xargs ...`) will run, instantly killing it.

### 7\. Uninstallation

To completely remove `fzf`:

1.  **Uninstall the package:**
    ```bash
    brew uninstall fzf
    ```
2.  **Clean your shell config:** Open your `~/.zshrc` file and delete the lines that the installation script added. They will be clearly marked with `# fzf`.