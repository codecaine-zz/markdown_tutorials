# ensurepip - Bootstrapping the pip installer

The `ensurepip` module provides support for bootstrapping the `pip` installer into an existing Python installation or a virtual environment. It does not access the internet; all bootstrap files are included as part of the Python installation.

Here are correct and working code examples demonstrating how to use `ensurepip` programmatically:

## Table of Contents

1. [Basic Bootstrapping (Default installation)](#1-basic-bootstrapping-default-installation)
2. [Upgrading pip during Bootstrap](#2-upgrading-pip-during-bootstrap)
3. [Bootstrapping with Altinstall (Version-specific executable only)](#3-bootstrapping-with-altinstall-version-specific-executable-only)
4. [Installing to a Custom Root Directory](#4-installing-to-custom-root-directory)
5. [Verifying standard installation and Handling exceptions](#5-verifying-standard-installation-and-handling-exceptions)

### 1. Basic Bootstrapping (Default installation)

By default, `ensurepip.bootstrap()` installs the version of `pip` bundled with Python into the current environment.

```python
import ensurepip

# Bootstrap pip in the current Python environment
ensurepip.bootstrap()
print("pip bootstrapping attempt complete.")
```

### 2. Upgrading pip during Bootstrap

If `pip` is already installed, calling `bootstrap()` without options will not overwrite it. To upgrade pip to the bundled version, pass `upgrade=True`.

```python
import ensurepip

# Upgrade the installed version of pip to the bundled version
ensurepip.bootstrap(upgrade=True)
print("pip upgraded to the bundled version.")
```

### 3. Bootstrapping with Altinstall (Version-specific executable only)

By default, `bootstrap` installs a `pip` script and a versioned script (like `pip3.11`). If you only want the versioned script (to avoid shadowing a system `pip`), use `altinstall=True`.

```python
import ensurepip

# Install version-specific executable (e.g. pip3.11) only
ensurepip.bootstrap(altinstall=True)
print("pip bootstrapped with altinstall.")
```

### 4. Installing to a Custom Root Directory

You can specify a custom root directory using the `root` parameter. This is equivalent to using the pip `--root` option.

```python
import ensurepip
import os
import tempfile

# Use a temporary directory as a custom installation root
with tempfile.TemporaryDirectory() as temp_root:
    ensurepip.bootstrap(root=temp_root)
    print(f"pip bootstrapped relative to custom root: {temp_root}")
    # Inspect contents
    print("Files created in root:", os.listdir(temp_root))
```

### 5. Verifying standard installation and Handling exceptions

When bootstrapping, ensurepip will raise exceptions if it encounters an invalid environment or permission errors. You can handle standard system exceptions like `PermissionError`.

```python
import ensurepip
import sys

try:
    # Attempt bootstrapping with upgrade enabled
    ensurepip.bootstrap(upgrade=True)
    print("pip successfully bootstrapped!")
except PermissionError:
    print("Error: Insufficient permissions to install packages. Try running as administrator/sudo.")
except Exception as e:
    print(f"An unexpected error occurred during bootstrapping: {e}")
```

### Notes

- **Offline Operation**: `ensurepip` does not fetch files from PyPI. It bootstraps the exact version of pip that was packaged with your current Python release.
- **Command Line Usage**: You can also run it via shell using `python -m ensurepip --upgrade`.
