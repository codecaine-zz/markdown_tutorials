# Dive Docker Image Explorer & Optimizer Guide

`dive` is an interactive command-line tool designed for exploring Docker and OCI container image layers, inspecting file contents per layer, and identifying wasted space to shrink container image sizes.

---

## 📚 Table of Contents

1. [Overview & Why Use `dive`?](#overview--why-use-dive)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Inspecting Docker Images with `dive`](#inspecting-docker-images-with-dive)
4. [TUI Layout: Layers, Layer Details & Image Efficiency](#tui-layout-layers-layer-details--image-efficiency)
5. [Detecting Wasted Space & Inefficiencies](#detecting-wasted-space--inefficiencies)
6. [CI/CD Integration & Automated Image Quality Gates](#cicd-integration--automated-image-quality-gates)
7. [Analyzing Local Dockerfiles & Images Directly](#analyzing-local-dockerfiles--images-directly)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
9. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `dive`?

When writing Dockerfiles, common mistakes (like creating temporary files in one `RUN` step and deleting them in a later `RUN` step) do not reduce the final image size—the data remains trapped in intermediate layers. `dive` analyzes each layer individually and visualizes exact file modifications, additions, and deletions.

| Metric | Description |
| :--- | :--- |
| **Image Efficiency Score** | Percentage score rating wasted space (duplicated or deleted files). |
| **Wasted Space Total** | Exact megabytes/gigabytes consumed by inaccessible, overwritten files. |
| **Layer-by-Layer File Tree** | Shows added (`+`), modified (`~`), or removed (`-`) files for every layer. |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew

```bash
brew install dive
```

### 2. Verify Installation

```bash
dive --version
```

---

## 🚀 Inspecting Docker Images with `dive`

Analyze any locally available image or pull directly from a remote registry:

```bash
# Analyze a local or remote Docker image
dive node:20-alpine

# Analyze a custom application image
dive my-web-app:latest

# Analyze image using podman engine instead of docker
dive podman://my-app:v1
```

---

## 🖥️ TUI Layout: Layers, Layer Details & Image Efficiency

`dive` splits the terminal into two primary interactive panes:

### 1. Left Pane: Layers & Metrics
- Displays each Dockerfile instruction (`RUN`, `COPY`, `WORKDIR`).
- Shows the uncompressed size added by each individual layer.
- Displays the overall **Efficiency Score** and **Wasted Space**.

### 2. Right Pane: Current Layer File Tree
- Renders the container filesystem up to the currently selected layer.
- Color codes files:
  - **Green (`+`)**: File added in this layer.
  - **Yellow (`~`)**: File modified or overwritten in this layer.
  - **Red (`-`)**: File deleted in this layer.

### Interactive Navigation:
- `Tab` : Switch focus between Layers pane and File Tree pane.
- `▲` / `▼` or `k` / `j` : Navigate layers or directories.
- `Ctrl + f` : Filter and search files in the current layer.
- `Ctrl + a` : Toggle file attributes (permissions, size, owner).
- `Space` : Collapse or expand directory nodes in the tree.
- `Ctrl + c` / `q` : Quit `dive`.

---

## 🎯 Detecting Wasted Space & Inefficiencies

To view only files causing wasted space (duplicated or deleted across layers), press `Ctrl + u` inside the file tree pane.

### Common Wasted Space Patterns & Fixes:

#### Problem 1: Separate `apt-get` and cleanup steps
```dockerfile
# ❌ Bad: Cache files persist in Layer 1 even after deletion in Layer 2
RUN apt-get update && apt-get install -y build-essential
RUN rm -rf /var/lib/apt/lists/*
```

```dockerfile
# ✅ Good: Clean up within the SAME layer instruction
RUN apt-get update && apt-get install -y --no-install-recommends build-essential \
    && rm -rf /var/lib/apt/lists/*
```

---

## 🤖 CI/CD Integration & Automated Image Quality Gates

Run `dive` in CI/CD pipelines (GitHub Actions, GitLab CI) to automatically fail builds if image efficiency drops below a threshold:

```bash
# Fail build if image efficiency is below 95% or wasted space exceeds 20MB
CI=true dive --ci-config .dive-ci.yaml my-app:latest
```

### Example `.dive-ci.yaml` Configuration:

```yaml
rules:
  # Minimum acceptable efficiency (0.0 to 1.0)
  lowestEfficiency: 0.95
  # Maximum allowed wasted space in bytes
  highestUserWastedPercent: 0.10
  highestWastedBytes: 20MB
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Inspect Image** | `dive <image_name>` |
| **Build & Inspect Local Dockerfile** | `dive build -t test-img .` |
| **CI Automation Mode** | `CI=true dive <image_name>` |
| **Inspect Podman Image** | `dive podman://<image_name>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Dive shortcuts
alias docker-inspect='dive'
alias docker-shrink='dive'
```

---

## 🗑️ Uninstallation

To remove `dive`:

```bash
brew uninstall dive
```
