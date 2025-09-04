# eza: A Modern Replacement for ls

## Table of Contents

1.  [What is `eza`?](#1-what-is-eza)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Basic Usage & Output](#4-basic-usage--output)
5.  [Key Features & Output](#5-key-features--output)
6.  [Configuration: Creating Aliases](#6-configuration-creating-aliases)
7.  [Enabling Icons with Nerd Fonts](#7-enabling-icons-with-nerd-fonts)
8.  [Uninstallation](#8-uninstallation)

-----

### 1\. What is `eza`?

`eza` is a modern, feature-rich replacement for the standard `ls` command. It provides better defaults, color-coding, file-specific icons, a tree view, Git integration, and more, all out of the box.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `eza` using a single Homebrew command:

```bash
brew install eza
```

### 4\. Basic Usage & Output

You can use `eza` as a direct replacement for `ls`.

  * **List files:**

    ```bash
    eza
    ```

    **Example Output:** (A colorful, grid-based view)

    ```text
    README.md    node_modules/    src/
    index.js     package.json     utils.js
    ```

  * **Long listing format (details like permissions, size, date):**

    ```bash
    eza -l
    ```

    **Example Output:**

    ```text
    .rw-r--r-- user  1.2k Sep  3 19:01 README.md
    drwxr-xr-x user     - Sep  3 19:02 node_modules/
    .rw-r--r-- user  2.5k Sep  3 19:03 index.js
    .rw-r--r-- user  345B Sep  3 19:04 package.json
    drwxr-xr-x user     - Sep  3 19:05 src/
    .rw-r--r-- user  876B Sep  3 19:06 utils.js
    ```

  * **List all files, including hidden ones (dotfiles):**

    ```bash
    eza -a
    ```

    **Example Output:**

    ```text
    .            .git/         .gitignore    index.js       src/
    ..           .env          README.md     node_modules/  utils.js
    ```

### 5\. Key Features & Output

Here are some of the features that make `eza` powerful.

  * **Tree View:**

    ```bash
    # Display files and directories in a tree structure, two levels deep
    eza --tree --level=2
    ```

    **Example Output:**

    ```text
    .
    ├── .env
    ├── .git
    │  ├── HEAD
    │  ├── config
    │  ├── description
    │  └── hooks
    ├── .gitignore
    ├── README.md
    ├── index.js
    ├── package.json
    └── src
       └── component.js
    ```

  * **Icons & Git Integration:** (Requires a Nerd Font, see section 7)
    This is where `eza` truly shines. Let's use a common, powerful combination.

    ```bash
    eza -l --icons --git
    ```

    **Example Output:**
    (Note: `N` = new, `M` = modified. Icons are represented by ``, ``, ``)

    ```text
    Permissions  Size  User  Date Modified  Git  Icon  Name
    .rw-r--r--   1.2k  user  Sep  3 19:01   -M      README.md
    drwxr-xr-x      -  user  Sep  3 19:05   --      src/
    .rw-r--r--   2.5k  user  Sep  3 19:03   N-      index.js
    .rw-r--r--   876B  user  Sep  3 19:06   --      utils.js
    ```

### 6\. Configuration: Creating Aliases

To make `eza` your default and create convenient shortcuts, you can add aliases to your shell's configuration file (e.g., `~/.zshrc` for Zsh, which is the default on modern macOS).

1.  Open your configuration file:

    ```bash
    # For Zsh
    open ~/.zshrc
    ```

2.  Add the following lines. These functions and aliases will replace `ls` with `eza` and create useful shortcuts.

    ```bash
    # Use eza for ls, with icons if available
    ls() { eza --icons "$@" }

    # Aliases for common eza commands
    alias ll='eza -l --git'       # long listing with git status
    alias la='eza -la --git'      # long listing, all files, with git
    alias lt='eza --tree --level=2' # tree view, 2 levels deep
    ```

3.  Apply the changes by restarting your terminal or sourcing the file:

    ```bash
    source ~/.zshrc
    ```

### 7\. Enabling Icons with Nerd Fonts

For the `--icons` flag to work correctly, you need to install and use a "Nerd Font" in your terminal.

1.  **Tap the Homebrew font repository:**

    ```bash
    brew tap homebrew/cask-fonts
    ```

2.  **Install a Nerd Font.** Fira Code is a popular choice.

    ```bash
    brew install --cask font-fira-code-nerd-font
    ```

3.  **Configure Your Terminal:** Open your terminal's preferences (e.g., Terminal.app, iTerm2, Warp) and set the font to the Nerd Font you just installed (e.g., `FiraCode Nerd Font`).

### 8\. Uninstallation

If you need to remove `eza`, you can do so easily with Homebrew.

```bash
brew uninstall eza
```

Remember to also remove any aliases you added to your shell configuration file.