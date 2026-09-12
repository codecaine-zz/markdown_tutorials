# UV Fast Python Package & Project Manager Guide

`uv` is an extremely fast, modern Python package installer and resolver written in Rust by Astral (the creators of Ruff). Designed as a drop-in replacement for `pip`, `pip-tools`, `pipx`, `poetry`, `pyenv`, and `virtualenv`, `uv` is 10–100x faster than traditional Python tooling.

---

## 📚 Table of Contents

1. [Overview & Why Use `uv`?](#overview-why-use-uv)
2. [Prerequisites & Homebrew Installation](#prerequisites-homebrew-installation)
3. [Installing Python Versions (`uv python`)](#installing-python-versions-uv-python)
4. [Creating & Managing Virtual Environments (`uv venv`)](#creating-managing-virtual-environments-uv-venv)
5. [High-Speed Package Management (`uv pip`)](#high-speed-package-management-uv-pip)
6. [Universal Project Management (`uv init`, `uv add`, `uv run`)](#universal-project-management-uv-init-uv-add-uv-run)
7. [Running Standalone CLI Tools with `uvx` (pipx Alternative)](#running-standalone-cli-tools-with-uvx-pipx-alternative)
8. [Lockfiles & Deterministic Builds (`uv.lock`)](#lockfiles-deterministic-builds-uvlock)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet-useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `uv`?

Standard Python environments often suffer from fragmented tooling (`pyenv` to install Python, `venv` to create environments, `pip` to install packages, `pip-tools` or `poetry` for lockfiles, and `pipx` for CLI tools). `uv` unifies this entire workflow into a single binary.

| Feature | Legacy Tools (`pip`, `poetry`, `pyenv`) | `uv` |
| :--- | :--- | :--- |
| **Language** | Python | Rust (hyper-threaded) |
| **Installation Speed** | 5s – 60s per package | Milliseconds (cached global wheel link) |
| **Python Version Management** | Requires `pyenv` or manual compile | Built-in (`uv python install 3.12`) |
| **Virtual Environments** | `python -m venv` (slow) | `uv venv` (near instant) |
| **Deterministic Locking** | `poetry.lock` / `requirements.txt` | Universal cross-platform `uv.lock` |
| **Isolated Script Execution** | `pipx` | `uvx` / `uv run --with` |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew (macOS & Linux)

```bash
brew install uv
```

### 2. Install via Official Standalone Script

```bash
curl -LsSf https://astral.sh/uv/install.sh | sh
```

### 3. Verify Installation

```bash
uv --version
```

---

## 🐍 Installing Python Versions (`uv python`)

`uv` can download and manage standalone, pre-built Python binaries without requiring system compilation tools or `pyenv`:

```bash
# List available Python releases
uv python list

# Install specific Python versions
uv python install 3.12
uv python install 3.11 3.13

# Pin Python version for the current directory
uv python pin 3.12
```

---

## 📦 Creating & Managing Virtual Environments (`uv venv`)

```bash
# Create virtual environment (.venv) using default Python
uv venv

# Create virtual environment with specific Python version
uv venv --python 3.12

# Activate environment
source .venv/bin/activate
```

---

## ⚡ High-Speed Package Management (`uv pip`)

`uv pip` is a drop-in replacement for standard `pip` commands with extreme caching:

```bash
# Install packages
uv pip install fastapi uvicorn pydantic

# Install from requirements.txt
uv pip install -r requirements.txt

# Compile locked requirements (replaces pip-compile)
uv pip compile pyproject.toml -o requirements.txt

# Sync exact environment state (replaces pip-sync)
uv pip sync requirements.txt
```

---

## 🚀 Universal Project Management (`uv init`, `uv add`, `uv run`)

`uv` provides a modern project workflow similar to Rust's Cargo or Node's npm:

### 1. Initialize a Project

```bash
mkdir my-api && cd my-api
uv init --app
```

### 2. Add and Remove Dependencies

```bash
# Add packages (automatically updates pyproject.toml and uv.lock)
uv add fastapi "uvicorn[standard]"

# Add development-only dependencies
uv add --dev pytest ruff mypy

# Remove dependencies
uv remove fastapi
```

### 3. Run Commands Inside the Project Environment

Execute scripts without manually activating `.venv`:

```bash
# Runs inside the project's virtual environment automatically
uv run python main.py

# Run test suite
uv run pytest
```

---

## 🛠️ Running Standalone CLI Tools with `uvx` (pipx Alternative)

Run any Python CLI tool in an ephemeral, isolated environment without polluting global packages:

```bash
# Run Ruff linter on current folder
uvx ruff check .

# Run HTTPie Python client
uvx httpie https://api.github.com

# Run Black code formatter
uvx black .
```

---

## 🔒 Lockfiles & Deterministic Builds (`uv.lock`)

`uv` generates a universal `uv.lock` file that captures exact dependency resolutions across macOS, Linux, and Windows:

```bash
# Generate / update lockfile
uv lock

# Export uv.lock to standard requirements.txt format
uv export --format requirements-txt > requirements.txt
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Goal | Command |
| :--- | :--- |
| **New Project** | `uv init <project_name>` |
| **Add Dependency** | `uv add <package>` |
| **Add Dev Dependency** | `uv add --dev <package>` |
| **Run Script** | `uv run <script.py>` |
| **Run CLI Tool** | `uvx <tool_name>` |
| **Create Venv** | `uv venv` |
| **Install Python 3.12** | `uv python install 3.12` |
| **Lock Dependencies** | `uv lock` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# UV shortcuts
alias pip='uv pip'
alias venv='uv venv'
alias py='uv run python'
alias pytest='uv run pytest'
```

---

## 🗑️ Uninstallation

To remove `uv`:

```bash
brew uninstall uv
```
