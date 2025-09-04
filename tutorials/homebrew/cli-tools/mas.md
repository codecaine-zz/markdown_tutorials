### Table of Contents

1.  [What is `mas`?](https://www.google.com/search?q=%231-what-is-mas)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Basic Usage: Finding & Installing Apps](https://www.google.com/search?q=%234-basic-usage-finding--installing-apps)
5.  [Managing Your Apps](https://www.google.com/search?q=%235-managing-your-apps)
6.  [Advanced Usage: Automation with `Brewfile`](https://www.google.com/search?q=%236-advanced-usage-automation-with-brewfile)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `mas`?

`mas` (Mac App Store) is a simple command-line interface for the Apple Mac App Store. It allows you to search, install, and update applications directly from your terminal, without ever opening the graphical App Store. This makes it an incredibly powerful tool for scripting and automating the setup of a new Mac.

### 2\. Prerequisites

1.  **Homebrew:** You must have [Homebrew](https://brew.sh/) installed.
2.  **Apple ID:** You must be signed into the Mac App Store with your Apple ID for `mas` to install and update apps. You can check your status with `mas account`.

### 3\. Installation

Install `mas` using a single Homebrew command:

```bash
brew install mas
```

### 4\. Basic Usage: Finding & Installing Apps

The core workflow of `mas` involves finding an application's unique ID and then using that ID to install it.

  * **Step 1: Search for an App**
    Use `mas search` to find an application and its ID.

    ```bash
    mas search "Microsoft Remote Desktop"
    ```

    **Example Output:** `mas` will list matching apps with their ID on the left.

    ```text
        1295203466  Microsoft Remote Desktop
        714464092   Microsoft Desktop
    ...
    ```

    Here, the ID for "Microsoft Remote Desktop" is `1295203466`.

  * **Step 2: Install an App**
    Use `mas install` with the app's ID.

    ```bash
    mas install 1295203466
    ```

    **Example Output:** `mas` will show a progress bar as it downloads and installs the app.

    ```text
    ==> Downloading Microsoft Remote Desktop
    ==> Installing Microsoft Remote Desktop
    [==================================] 100%
    ```

### 5\. Managing Your Apps

`mas` also makes it easy to see what you have installed and to keep everything up to date.

  * **List Installed Apps:**
    To see all the applications you've installed from the App Store, use `mas list`.

    ```bash
    mas list
    ```

    **Example Output:**

    ```text
     497799835 Xcode
    1147396723 Tailscale
     409201541 Pages
    1289583905 Amphetamine
    ```

  * **Check for Outdated Apps:**
    To see which of your apps have pending updates, use `mas outdated`.

    ```bash
    mas outdated
    ```

    **Example Output:**

    ```text
    409201541  Pages (14.0 -> 14.1)
    497799835  Xcode (15.4 -> 16.0)
    ```

  * **Upgrade Apps:**
    To upgrade all outdated applications at once, use `mas upgrade`.

    ```bash
    mas upgrade
    ```

    **Example Output:**

    ```text
    Upgrading 2 outdated applications:
    Pages (14.0 -> 14.1)
    Xcode (15.4 -> 16.0)
    ==> Downloading Pages...
    ==> Installing Pages
    ==> Downloading Xcode...
    ==> Installing Xcode
    ```

### 6\. Advanced Usage: Automation with `Brewfile`

The true power of `mas` is unlocked when you combine it with `homebrew-bundle` to automate your entire Mac setup. You can list your App Store apps in a `Brewfile` alongside your Homebrew packages.

1.  **Create a `Brewfile`:**
    Create a file named `Brewfile` in your home directory or a project folder.

2.  **Add `mas` entries:**
    Add your desired App Store apps to the file using the format `mas 'AppName', id: 12345`.

    **Example `Brewfile`:**

    ```ruby
    # Tap a repository for casks
    tap "homebrew/cask-fonts"

    # Install a CLI tool
    brew "btop"

    # Install a GUI application
    cask "visual-studio-code"

    # Install Mac App Store apps
    mas "Tailscale", id: 1147396723
    mas "Xcode", id: 497799835
    mas "Amphetamine", id: 1289583905
    ```

3.  **Run the `Brewfile`:**
    From the directory containing your `Brewfile`, simply run:

    ```bash
    brew bundle install
    ```

    Homebrew will read the file and automatically install everything—your `brew` packages, your `casks`, and your `mas` App Store apps.

### 7\. Uninstallation

If you need to remove `mas`, you can do so easily with Homebrew.

```bash
brew uninstall mas
```