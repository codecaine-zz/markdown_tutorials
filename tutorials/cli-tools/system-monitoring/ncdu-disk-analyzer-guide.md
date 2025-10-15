### Table of Contents

1.  [What is `ncdu`?](https://www.google.com/search?q=%231-what-is-ncdu)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Running `ncdu` & The Interface](https://www.google.com/search?q=%234-running-ncdu--the-interface)
5.  [Navigation and Key Features](https://www.google.com/search?q=%235-navigation-and-key-features)
6.  [Interactive Example: Cleaning Up a Directory](https://www.google.com/search?q=%236-interactive-example-cleaning-up-a-directory)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `ncdu`?

`ncdu` stands for **NCurses Disk Usage**. It is a fast and easy-to-use disk usage analyzer for the command line. Think of it as an interactive, supercharged version of the `du` command. It scans a given directory, calculates the disk space being used by every file and folder, and then presents you with an interactive TUI (Terminal User Interface) to browse the results, making it incredibly simple to find what's consuming your disk space.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `ncdu` using a single Homebrew command:

```bash
brew install ncdu
```

### 4\. Running `ncdu` & The Interface

You can run `ncdu` on its own to scan the current directory, or provide a path to scan a specific one.

  * **Command:**

    ```bash
    # Scan the current directory
    ncdu

    # Scan a specific directory, like your home folder
    ncdu ~
    ```

  * **The Scanning Phase:**
    `ncdu` first needs to scan the directory tree and calculate sizes. It shows a progress screen while it works.

    ```text
    ncdu 1.19 ~ Use the arrow keys to navigate, press ? for help
    --- Scanning... /Users/yourname ------------------------------------------------
    ```

  * **The Main Interface:**
    Once the scan is complete, you are presented with an interactive, navigable list of files and directories, sorted by size in descending order.

    **Example Interface:**

    ```text
     --- /Users/yourname/Code ----------------------------------------------------
       .  50.5 GiB [##########] /project-alpha
       .  22.1 GiB [####      ] /project-beta
       .   8.9 GiB [#         ] /archive
       .   2.3 GiB [          ] /dotfiles
     e          [          ] /empty-project
       150.7 MiB [          ]  some-large-file.log

     Total disk usage: 83.8 GiB  Apparent size: 95.2 GiB  Items: 451,321
    ```

### 5\. Navigation and Key Features

Navigating and managing files within `ncdu` is done with simple keyboard shortcuts.

  * **Quitting:**

      * `q`: Quit the program.

  * **Navigation:**

      * `Up`/`Down` Arrow Keys: Move the selection up or down.
      * `Right` Arrow or `Enter`: Open the selected directory and view its contents.
      * `Left` Arrow: Go up to the parent directory.

  * **File & Directory Actions:**

      * `d`: **Delete** the selected file or directory. It will ask for confirmation before proceeding.
      * `i`: Show detailed **information** about the selected item (full path, size, etc.).

  * **Sorting & Display:**

      * `s`: Toggle sorting by **size** (default) or by **name**.
      * `a`: Toggle between showing **apparent size** and **disk usage**.
      * `g`: Toggle the display of the size graph (`####`) vs percentages.

  * **Help:**

      * `?`: Show the built-in help screen with all available keybindings.

### 6\. Interactive Example: Cleaning Up a Directory

Let's say your `~/Downloads` folder is full and you want to find and delete large files.

1.  **Launch `ncdu` on the directory:**

    ```bash
    ncdu ~/Downloads
    ```

2.  **Wait for the scan to finish.** The largest files and folders will be at the top.

3.  **Explore the contents:**

      * Use the `Down` arrow key to navigate to a large file, for example, `old-linux-distro.iso`.
      * Or, if the largest item is a directory (e.g., `/unpacked-files`), press `Enter` to navigate into it.

4.  **Delete an item:**

      * Select the large, unnecessary file you want to remove.
      * Press the `d` key. `ncdu` will ask for confirmation.

    **Example Confirmation Prompt:**

    ```text
    Are you sure you want to delete '/Users/yourname/Downloads/old-linux-distro.iso'? [y/N]
    ```

5.  **Confirm deletion:**

      * Type `y` and press `Enter`. The file will be permanently deleted.
      * Notice that the directory totals immediately update to reflect the newly freed space.

6.  **Exit:**

      * Press `q` to quit `ncdu` when you are finished.

### 7\. Uninstallation

If you need to remove `ncdu`, you can do so easily with Homebrew.

```bash
brew uninstall ncdu
```