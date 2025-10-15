### Table of Contents

1.  [What is `btop`?](https://www.google.com/search?q=%231-what-is-btop)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Running `btop` & The Interface](https://www.google.com/search?q=%234-running-btop--the-interface)
5.  [Navigation and Key Features](https://www.google.com/search?q=%235-navigation-and-key-features)
6.  [Interactive Example: Finding and Killing a Process](https://www.google.com/search?q=%236-interactive-example-finding-and-killing-a-process)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `btop`?

`btop` is a modern, fast, and beautiful resource monitor for the command line. It's a powerful successor to tools like `top` and `htop`, showing detailed usage and stats for your processor, memory, disks, network, and running processes. It features a clean, interactive TUI (Terminal User Interface) with full mouse support, making it easy to see what's happening on your system at a glance.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `btop` using a single Homebrew command:

```bash
brew install btop
```

### 4\. Running `btop` & The Interface

Using `btop` is as simple as typing its name.

  * **Command:**

    ```bash
    btop
    ```

  * **Example Interface:**
    When you run the command, your terminal will be taken over by the `btop` TUI, which looks something like this. It's composed of several boxes, each showing a different aspect of your system.

    ```text
    ┌─CPU (16 Threads)─────────────────────────────────────────────────────────────┐
    │ CPU1 [■■■■      15%] CPU2 [■■        8%] CPU3 [■■■       12%] ...           │
    │ All  [■■■       11%] Uptime: 2d 15h 30m          Temp: 55°C                  │
    └──────────────────────────────────────────────────────────────────────────────┘
    ┌─Mem───M4 MacBook Pro─────────────────────────────────────────────────────────┐
    │ Mem [■■■■■■■■■■■■■■■■■■■■■■■■■  38.5/48.0 GiB]                              │
    │ Swp [                                0/0 GiB]                                │
    └──────────────────────────────────────────────────────────────────────────────┘
    ┌─Net──────────────────────────────────────────────────────────────────────────┐
    │ en0: Down 5.2 MiB/s ▲ Up 1.1 MiB/s ▼ Total Down: 10.2 GiB Up: 2.1 GiB        │
    └──────────────────────────────────────────────────────────────────────────────┘
    ┌─Processes──542 running────────────────────────────Sorted by cpu lazy─────────┐
    │      PID USER      PRI  NI  VIRT   RES   SHR S CPU% MEM%   TIME+  Command    │
    │ ▸  98123 user       20   0  1.2G  150M   50M R 12.2  0.8 0:15.23  Code Helper│
    │    12345 root       20   0  500M   80M   20M S  5.5  0.4 2:03.11  kernel_task│
    │    54321 user       20   0  2.1G  250M   80M S  2.1  1.2 0:05.55  firefox    │
    │    ...                                                                       │
    └──────────────────────────────────────────────────────────────────────────────┘
    ```

### 5\. Navigation and Key Features

While `btop` supports mouse clicks, using keyboard shortcuts is much faster. Here are the essentials:

  * **Quitting:**

      * `q` or `Esc`: Quit the program.

  * **Navigating Processes:**

      * `Up`/`Down` Arrow Keys: Select a process.
      * `PgUp`/`PgDn`: Move up or down the list by a full page.

  * **Process Management:**

      * `f`: Opens a prompt to **filter** the processes by name.
      * `t`: Send a TERM signal (graceful shutdown) to the selected process.
      * `k`: Send a KILL signal (forceful shutdown) to the selected process.

  * **Changing Views:**

      * `p`: Toggle a detailed, full-screen view of the **Processes** box.
      * `c`: Toggle a detailed view of the **CPU** box.
      * `m`: Toggle a detailed view of the **Memory/Disks** box.
      * `n`: Toggle a detailed view of the **Network** box.
      * `Esc`: Return to the default view from any detailed view.

  * **Options Menu:**

      * `m`: Opens the main options menu where you can change colors, display settings, and more.

### 6\. Interactive Example: Finding and Killing a Process

Let's say you have a runaway `nginx` process that you need to stop.

1.  **Launch `btop`:**
    ```bash
    btop
    ```
2.  **Filter for the process:**
      * Press the `f` key. A filter input box will appear.
      * Type `nginx` and press `Enter`. The process list will now only show items containing "nginx".
3.  **Select the process:**
      * Use the `Up`/`Down` arrow keys to highlight the specific `nginx` process you want to stop.
4.  **Kill the process:**
      * Press the `k` key. `btop` will ask for confirmation.
      * Press `Enter` to confirm. The process will be sent a SIGKILL signal and should disappear from the list.
5.  **Exit:**
      * Press `q` to quit `btop` and return to your prompt.

### 7\. Uninstallation

If you need to remove `btop`, you can do so easily with Homebrew.

```bash
brew uninstall btop
```