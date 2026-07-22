# direnv: Directory-Specific Environment Variable Manager

## Table of Contents

1. [What is `direnv`?](#1-what-is-direnv)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Shell Integration (`.zshrc`)](#4-shell-integration-zshrc)
5. [Basic Usage & Practical Examples](#5-basic-usage-practical-examples)
6. [Security & `.envrc` Approvals](#6-security-envrc-approvals)
7. [Integration with `.env` Files & Python Virtual Environments](#7-integration-with-env-files-python-virtual-environments)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `direnv`?

`direnv` is an extension for your terminal shell that automatically loads and unloads environment variables depending on your current working directory. It checks for a `.envrc` file in your active folder, loads your custom variables when you `cd` into the project, and automatically exports/clears them when you leave.

#### Common Use Cases
* Managing local API credentials, database ports, and secret keys without polluting global shell profiles.
* Automatically activating Python virtual environments (`venv`, `poetry`, `conda`) upon entering project folders.
* Setting project-specific `PATH` modifications for custom binaries.

---

### 2. Prerequisites

Ensure Homebrew is ready on your ARM Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install `direnv` using Homebrew:

```bash
brew install direnv
```

Verify installation:

```bash
which direnv
direnv version
```

**Expected Output:**
```text
/opt/homebrew/bin/direnv
2.x.x
```

---

### 4. Shell Integration (`.zshrc`)

For `direnv` to hook into shell directory changes on macOS Zsh, append the initialization hook to `~/.zshrc`:

```bash
echo 'eval "$(direnv hook zsh)"' >> ~/.zshrc
source ~/.zshrc
```

---

### 5. Basic Usage & Practical Examples

#### Step 1: Create a Project Directory
```bash
mkdir -p ~/projects/my-api-service
cd ~/projects/my-api-service
```

#### Step 2: Define Environment Variables in `.envrc`
Create a `.envrc` file inside the folder:

```bash
echo 'export PORT=8080' >> .envrc
echo 'export API_KEY="dev_secret_key_12345"' >> .envrc
```

#### Step 3: Authorize `.envrc` Execution
For security reasons, `direnv` blocks unauthorized `.envrc` files. You must explicitly allow it:

```bash
direnv allow
```

**Output:**
```text
direnv: loading .envrc
direnv: export +API_KEY +PORT
```

#### Step 4: Verify Environment Activation
```bash
echo $PORT
echo $API_KEY
```

**Output:**
```text
8080
dev_secret_key_12345
```

#### Step 5: Test Auto-Unload on Leaving Folder
```bash
cd ..
```

**Output:**
```text
direnv: unloading
```

If you check `echo $PORT`, the variable is clean and un-exported.

---

### 6. Security & `.envrc` Approvals

Because `.envrc` files contain executable shell script logic, `direnv` prompts for approval whenever a `.envrc` file is created or modified.

* **Grant approval:** `direnv allow`
* **Revoke approval:** `direnv deny`
* **Reload environment:** `direnv reload`

> [!CAUTION]
> Always add `.envrc` to your global or project `.gitignore` so secrets are never pushed to public Git repositories:
> ```bash
> echo ".envrc" >> ~/.gitignore
> ```

---

### 7. Integration with `.env` Files & Python Virtual Environments

#### Standard `.env` File Integration
If your team already uses standard `.env` files, add a single line to your `.envrc`:

```bash
# inside .envrc
dotenv .env
```

#### Automatic Python VirtualEnv Activation
To automatically build and activate a Python virtual environment when you enter a directory, add this to `.envrc`:

```bash
layout python python3.11
```

When you enter the folder, `direnv` will automatically create a `.direnv/python-3.11` virtualenv and activate it for your session.

---

### 8. Uninstallation

1. Remove `eval "$(direnv hook zsh)"` from `~/.zshrc`.
2. Uninstall `direnv`:
   ```bash
   brew uninstall direnv
   ```
