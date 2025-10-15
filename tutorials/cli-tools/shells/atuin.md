### Table of Contents

1.  [What is `atuin`?](https://www.google.com/search?q=%231-what-is-atuin)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Shell Configuration (Crucial Step)](https://www.google.com/search?q=%234-shell-configuration-crucial-step)
5.  [Basic Usage: The Search UI (`Ctrl+R`)](https://www.google.com/search?q=%235-basic-usage-the-search-ui-ctrlr)
6.  [Command-Line Usage & Output](https://www.google.com/search?q=%236-command-line-usage--output)
7.  [Cloud Sync (Optional but Recommended)](https://www.google.com/search?q=%237-cloud-sync-optional-but-recommended)
8.  [Uninstallation](https://www.google.com/search?q=%238-uninstallation)

-----

### 1\. What is `atuin`?

`atuin` replaces your default shell history with a powerful, SQLite-powered database. It enriches your command history with context like the exit code, duration, and working directory of each command. Its main feature is a full-screen, searchable history UI that replaces the standard `Ctrl+R` prompt, making it incredibly fast to find and re-run previous commands. It also offers optional, end-to-end encrypted synchronization of your history across multiple machines.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `atuin` using a single Homebrew command:

```bash
brew install atuin
```

### 4\. Shell Configuration (Crucial Step)

For `atuin` to take over your shell's history and `Ctrl+R` keybinding, it must be initialized in your shell configuration file.

1.  Open your configuration file (likely `~/.zshrc` on your Mac):

    ```bash
    open ~/.zshrc
    ```

2.  Add the following line to the **end** of the file:

    ```bash
    # Initialize Atuin
    eval "$(atuin init zsh)"
    ```

3.  Apply the changes by restarting your terminal or sourcing the file:

    ```bash
    source ~/.zshrc
    ```

    `atuin` is now logging your commands and is ready to use.

### 5\. Basic Usage: The Search UI (`Ctrl+R`)

The primary way to interact with `atuin` is by pressing `Ctrl+R` in your terminal.

  * **Action:** Press `Ctrl+R`. A full-screen UI will appear. Start typing a part of any command you've run in the past.

  * **Example UI:** Imagine you type `brew install` into the search prompt.

    **Simulated Output:** You get a rich, filterable list. You can navigate with arrow keys (`↑`/`↓`) and press `Enter` to execute.

    ```text
    Atuin Search (Wed, 03 Sep 2025 19:09:09)
    ↑/↓: Navigate, Enter: Execute, Tab: Edit, Ctrl+R: Filter Mode

    ╭──────────────────────────────────────────────────────────────────────────────╮
    │  [ 2s 150ms ] [ Failed (1) ] brew install nonexistant-package                 │
    │    └─ ~/Downloads on Your-MBP @ 2 hours ago                                  │
    │                                                                              │
    │> [ 5s 300ms ] [ Success (0) ] brew install zoxide                             │
    │    └─ ~/ on Your-MBP @ 1 day ago                                             │
    │                                                                              │
    │  [ 4s 800ms ] [ Success (0) ] brew install eza                                │
    │    └─ ~/ on Your-MBP @ 1 day ago                                             │
    ╰──────────────────────────────────────────────────────────────────────────────╯
    Search › brew install_
    ```

### 6\. Command-Line Usage & Output

You can also interact with your history directly from the command line.

  * **Search for a command:**

    ```bash
    atuin search --limit 3 "git commit"
    ```

    **Example Output:** A clean list of the most recent matching commands.

    ```text
    git commit -m "feat: add user authentication"
    git commit -m "fix: resolve login bug"
    git commit --amend --no-edit
    ```

  * **View statistics:** `atuin` can show you stats about your command usage.

    ```bash
    atuin stats
    ```

    **Example Output:**

    ```text
    - Atuin Statistics -
    Total history count: 15,302
    Unique commands: 4,123

    Top 5 Commands:
    1. git (2015)
    2. ls (1840)
    3. z (1102)
    4. rm (543)
    5. code (499)
    ```

### 7\. Cloud Sync (Optional but Recommended)

Sync your command history across all your machines. The data is end-to-end encrypted, meaning only you can read it.

1.  **Register an account:**

    ```bash
    # Choose a username and provide an email and password
    atuin register -u <your-username>
    ```

2.  **Login:** On your other machines, you only need to log in.

    ```bash
    atuin login -u <your-username>
    ```

3.  **Sync:** Pull down your history from the server and push up local changes.

    ```bash
    atuin sync
    ```

    `atuin` will also automatically sync in the background periodically.

### 8\. Uninstallation

If you need to remove `atuin`:

1.  **Uninstall the package:**
    ```bash
    brew uninstall atuin
    ```
2.  **Clean your shell config:** Open your `~/.zshrc` file again and remove the `eval "$(atuin init zsh)"` line.