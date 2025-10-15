### Table of Contents

1.  [What is `gum`?](https://www.google.com/search?q=%231-what-is-gum)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [The Core Commands (Your Script's Building Blocks)](https://www.google.com/search?q=%234-the-core-commands-your-scripts-building-blocks)
5.  [Putting It All Together: An Interactive Git Commit Script](https://www.google.com/search?q=%235-putting-it-all-together-an-interactive-git-commit-script)
6.  [Uninstallation](https://www.google.com/search?q=%236-uninstallation)

-----

### 1\. What is `gum`?

`gum` is a tool for creating "glamorous" shell scripts. It's not a single program you run, but rather a collection of small, composable commands that provide beautiful, interactive UI elements for your scripts. With `gum`, you can easily add user-friendly prompts like menus, text inputs, confirmations, and spinners to your shell scripts, making them more professional and easier to use.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `gum` using a single Homebrew command:

```bash
brew install gum
```

### 4\. The Core Commands (Your Script's Building Blocks)

`gum` provides several commands that act as interactive components. You capture their output in shell variables to build your script's logic.

  * **`gum choose`**: Present a list of options for the user to select.
    **Command:**

    ```bash
    gum choose "Feature" "Fix" "Chore" "Docs"
    ```

    **Example Interaction:**

    ```text
    ? Choose an option: 
      Feature
    > Fix
      Chore
      Docs
    (Use arrow keys to move, enter to select)
    ```

  * **`gum input`**: Prompt the user for free-form text.
    **Command:**

    ```bash
    gum input --placeholder "Enter a brief summary..."
    ```

    **Example Interaction:**

    ```text
    > Enter a brief summary...|
    ```

  * **`gum confirm`**: Ask a yes/no question. It exits with a status code of 0 (success) for "yes" and 1 (failure) for "no".
    **Command:**

    ```bash
    gum confirm "Ready to proceed?"
    ```

    **Example Interaction:**

    ```text
    ? Ready to proceed? Yes / No
    ```

  * **`gum spin`**: Display a spinner while a long-running command executes.
    **Command:**

    ```bash
    gum spin --spinner dot --title "Sleeping for 3 seconds..." -- sleep 3
    ```

    **Example Interaction:**

    ```text
    ⠇ Sleeping for 3 seconds...
    ```

  * **`gum style`**: Add borders, padding, and colors to text for beautiful output.
    **Command:**

    ```bash
    gum style --border double --padding "1 2" --foreground 214 "Task Complete!"
    ```

    **Example Output:**

    ```text
    ╔════════════════╗
    ║ Task Complete! ║
    ╚════════════════╝
    ```

### 5\. Putting It All Together: An Interactive Git Commit Script

The real power of `gum` is combining these commands. Here is a script that creates a conventional git commit message interactively.

1.  **Create the script:** Save the following as `glam-commit.sh`.

    ```bash
    #!/bin/bash

    # Use gum to style the script's header
    gum style --border normal --margin "1" --padding "1 2" "Create a new Git commit"

    # Get the type of commit
    TYPE=$(gum choose "feat" "fix" "chore" "docs" "style" "refactor" "test")

    # Get the commit summary
    SUMMARY=$(gum input --placeholder "Summary of this change")

    # Combine them into the final commit message
    COMMIT_MESSAGE="$TYPE: $SUMMARY"

    # Show the user the final message and ask for confirmation
    gum style --foreground 5 "Commit message will be: '$COMMIT_MESSAGE'"
    gum confirm "Proceed with commit?" || exit 0

    # If confirmed, run the git command behind a spinner
    gum spin --spinner line --title "Committing..." -- git commit -m "$COMMIT_MESSAGE"

    # Print a final success message
    gum style --bold --foreground 2 "✔ Commit successful!"
    ```

2.  **Make it executable:**

    ```bash
    chmod +x glam-commit.sh
    ```

3.  **Run it in a git repository:**

    ```bash
    ./glam-commit.sh
    ```

    The script will guide you through each step: choosing a type, writing a message, confirming, and finally executing the commit, all with a polished, interactive interface.

### 6\. Uninstallation

If you need to remove `gum`, you can do so easily with Homebrew.

```bash
brew uninstall gum
```