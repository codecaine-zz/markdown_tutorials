### Table of Contents

1.  [What is `cava`?](https://www.google.com/search?q=%231-what-is-cava)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Basic Usage & The Interface](https://www.google.com/search?q=%234-basic-usage--the-interface)
5.  [Customizing `cava` via the Config File](https://www.google.com/search?q=%235-customizing-cava-via-the-config-file)
6.  [Interactive Controls (While Running)](https://www.google.com/search?q=%236-interactive-controls-while-running)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `cava`?

`cava` is a Console-based Audio Visualizer. It's a highly configurable program that generates a real-time audio spectrum analysis in your terminal, creating a "dancing bars" effect that syncs with your system's audio output. It's a fun and stylish way to "see" your music, reminiscent of the visualizers on classic stereo systems.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `cava` using a single Homebrew command:

```bash
brew install cava
```

### 4\. Basic Usage & The Interface

Using `cava` is incredibly simple. Just play some music on your Mac and run the command.

  * **Command:**

    ```bash
    cava
    ```

  * **The Main Interface:**
    Your terminal will immediately display the audio visualizer, with bars reacting to the frequencies of the sound currently playing.

    **Example Interface (visualizing a song with a strong beat):**

    ```text
    ████████████████████████████████
    ██████████████████████████▒▒▒▒▒▒
    ████████████████████████▒▒░░░░░░
    ████████████████████████▒▒
    ██████████████████████▒▒▒▒
    ██████████████████▓▓▓▓░░░░
    ██████████████████▓▓▒▒
    ██████████████▒▒▒▒▒▒░░
    ██████████████▒▒░░░░
    ██████████▒▒▒▒
    ██████████▒▒
    ██████▓▓▓▓░░
    ██████▓▓▒▒
    ████▓▓▒▒
    ████▓▓
    ██▒▒
    ```

### 5\. Customizing `cava` via the Config File

The real power of `cava` lies in its configuration file, which allows you to change everything from the number of bars to the color scheme.

1.  **Create the configuration directory and file:**
    `cava` looks for its config at `~/.config/cava/config`. The Homebrew installation includes a sample configuration file that you can copy to get started.

    ```bash
    mkdir -p ~/.config/cava
    cp $(brew --prefix)/etc/cava/config ~/.config/cava/
    ```

2.  **Edit the configuration file:**
    Now you can open `~/.config/cava/config` with your favorite editor (e.g., `micro ~/.config/cava/config`) and change its settings.

    **Example Customizations:**
    Uncomment and change the following lines in the config file to create a colorful, high-resolution visualizer.

    ```ini
    # config

    # Change the number of bars for more detail (default is around 25)
    bars = 70

    # Make the bars thinner to fit more on screen
    bar_spacing = 0

    # Enable gradient mode to use multiple colors
    gradient = 1

    # Define a custom color gradient (up to 8 colors)
    gradient_color_1 = 'red'
    gradient_color_2 = 'orange'
    gradient_color_3 = 'yellow'
    gradient_color_4 = 'green'
    gradient_color_5 = 'blue'
    gradient_color_6 = 'indigo'
    gradient_color_7 = 'violet'
    ```

3.  **Relaunch `cava`** to see your changes.

### 6\. Interactive Controls (While Running)

You can also make adjustments on-the-fly while `cava` is running.

  * `Up` / `Down` Arrow Keys: Increase or decrease the visualizer's sensitivity.
  * `Left` / `Right` Arrow Keys: Decrease or increase the spacing between bars.
  * `r`: Reload the configuration file. This is useful for seeing your changes without restarting.
  * `c`: Cycles through the first 8 defined gradient colors.
  * `f` / `b`: Cycle foreground/background colors.
  * `q`: Quit the program.

### 7\. Uninstallation

If you need to remove `cava`, you can do so easily with Homebrew.

```bash
brew uninstall cava
```