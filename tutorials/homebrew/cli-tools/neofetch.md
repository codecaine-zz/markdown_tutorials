# Neofetch: System Info in Your Terminal

## Table of Contents

1.  [What is `neofetch`?](#1-what-is-neofetch)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Basic Usage & The Interface](#4-basic-usage--the-interface)
5.  [Customizing `neofetch`](#5-customizing-neofetch)
6.  [Pro-Tip: Run `neofetch` on Terminal Startup](#6-pro-tip-run-neofetch-on-terminal-startup)
7.  [Uninstallation](#7-uninstallation)

-----

### 1\. What is `neofetch`?

`neofetch` is a popular command-line system information tool. It runs on most operating systems and displays a summary of your system's hardware and software specifications in a clean, aesthetic format. Its signature feature is showing this information next to a large ASCII art logo of your operating system, making it a favorite for sharing terminal screenshots.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `neofetch` using a single Homebrew command:

```bash
brew install neofetch
```

### 4\. Basic Usage & The Interface

Using `neofetch` is as simple as running the command with no arguments.

  * **Command:**

    ```bash
    neofetch
    ```

  * **The Main Interface:**
    `neofetch` will detect your system's properties and display them. For your setup, the output would look something like this:

    **Example Output for your M4 MacBook Pro:**

    ```text
                -`                    yourname@your-macbook-pro
               .o+`                   ---------------------------
              `ooo/                   OS: macOS 15.0 Sequoia arm64
             `o+:
            `o+`                      Host: MacBook Pro (16-inch, M4, 2025)
           `/o+oooooo-`               Kernel: 24.5.0
          `+oooooooooo+`              Uptime: 1 day, 5 hours, 32 minutes
         -ooooooooooooo-              Packages: 172 (brew)
        :oooooooooooooo:              Shell: zsh 5.9
       -ooooooooooooooo-              Resolution: 3456x2234
      :ooooooooooooooo:               DE: Aqua
     .oooooooooooooooo.               WM: Quartz Compositor
    .ooooooooooooooooo-               Terminal: iTerm2
    -ooooooooooooooooo.                CPU: Apple M4 Max
    -oooooooooooooooooo-                GPU: Apple M4 Max (40-core)
    ```

.ooooooooooooooooooo.                Memory: 48GiB
:oooooooooooooooooo:
\+ooooooooooooooooo:
`oooooooooooooooo- .+oooooooooooo+`
`+ooooo+o+o+`
`+o+o+o+o+`
`-+o+o+o`
`-+o+`
\`-.
\`\`\`

### 5\. Customizing `neofetch`

You can easily customize almost every aspect of `neofetch`'s output.

  * **Via Command-Line Flags:**
    You can temporarily change the output by using flags.

      * **Disable information:** Turn off specific info lines with `--disable`.
        ```bash
        # Hide the Resolution, DE, and WM info
        neofetch --disable Resolution DE WM
        ```
      * **Change the ASCII art:** Show a different OS logo.
        ```bash
        # Feeling nostalgic for another OS
        neofetch --ascii_distro Windows10
        ```

  * **Via the Config File (Permanent Changes):**
    For permanent customization, edit the configuration file located at `~/.config/neofetch/config.conf`. If it doesn't exist, it will be created the first time you run `neofetch`.

    1.  **Open the config file:**

        ```bash
        micro ~/.config/neofetch/config.conf
        ```

    2.  **Edit the info output:**
        Scroll down to the `print_info()` function. This shell function controls what information is displayed and in what order. To disable a line, simply add a `#` to comment it out.

        **Example `print_info()` modification:**

        ```bash
        # config.conf

        print_info() {
            info "OS"
            info "Host"
            info "Kernel"
            info "Uptime"
            info "Packages"
            info "Shell"
            # info "Resolution"  # <-- I've commented this out to hide it.
            info "Terminal"
            info "CPU"
            info "GPU"
            info "Memory"
        }
        ```

    Save the file, and the next time you run `neofetch`, the "Resolution" line will be gone.

### 6\. Pro-Tip: Run `neofetch` on Terminal Startup

A very common use case is to have `neofetch` run automatically every time you open a new terminal window.

1.  **Add `neofetch` to your shell's startup file** (likely `~/.zshrc` for you):
    ```bash
    echo "neofetch" >> ~/.zshrc
    ```
2.  **Apply the changes:**
    Restart your terminal or `source ~/.zshrc`. Now you'll be greeted with your system info every time.

### 7\. Uninstallation

If you need to remove `neofetch`, you can do so easily with Homebrew.

```bash
brew uninstall neofetch
```