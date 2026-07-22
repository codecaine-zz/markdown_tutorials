# lazydocker: Terminal UI for Docker & Docker Compose

## Table of Contents

1. [What is `lazydocker`?](#1-what-is-lazydocker)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Basic Layout & Keybindings](#4-basic-layout-keybindings)
5. [Managing Containers, Images & Volumes](#5-managing-containers-images-volumes)
6. [Real-World Workflows](#6-real-world-workflows)
7. [Custom Configuration](#7-custom-configuration)
8. [Uninstallation](#8-uninstallation)

---

### 1. What is `lazydocker`?

`lazydocker` is a simple, keyboard-focused terminal UI (TUI) for Docker and Docker Compose, written in Go. Created by the developers behind `lazygit`, `lazydocker` lets you view container logs, monitor resource consumption (CPU, RAM), restart services, clean up unused images/volumes, and exec into running containers with a single keypress.

---

### 2. Prerequisites

1. Homebrew installed on your Apple Silicon Mac:
   ```bash
   brew --version
   ```
2. **Docker Environment:** Docker Desktop, OrbStack, or Colima running on macOS ARM64. Verify Docker daemon availability:
   ```bash
   docker info
   ```

---

### 3. Installation on ARM macOS

Install `lazydocker` via Homebrew:

```bash
brew install lazydocker
```

Verify installation:

```bash
which lazydocker
lazydocker --version
```

**Expected Output:**
```text
/opt/homebrew/bin/lazydocker
lazydocker 0.23.x (or latest)
```

---

### 4. Basic Layout & Keybindings

Launch `lazydocker`:

```bash
lazydocker
```

#### Panel Overview
The terminal interface is divided into 4 key navigation panels on the left side, with detailed context on the right:

1. **Services / Containers:** Status of active Docker Compose services or standalone containers.
2. **Images:** List of locally stored Docker images with size information.
3. **Volumes:** Persistent volume mounts.
4. **Project Info:** Docker engine statistics.

#### Core Navigation Controls

| Key | Action |
|---|---|
| `Tab` / `Shift+Tab` | Switch focus between left panels |
| `j` / `k` or `Down` / `Up` | Select items inside a panel |
| `Enter` | Focus main view / view container logs |
| `e` | Exec into selected container shell (`sh`/`bash`) |
| `r` | Restart container / service |
| `s` | Stop container |
| `d` | Remove selected container/image |
| `m` | View container logs with `docker logs -f` |
| `b` | Display bulk actions menu (prune containers/images) |
| `q` | Quit `lazydocker` |

---

### 5. Managing Containers, Images & Volumes

#### Live Logs & Resource Graphs
Selecting a running container automatically displays streaming logs alongside real-time CPU and Memory usage charts:

```text
┌─ Containers ────────────┐┌─ Logs: web-server ──────────────────────────────┐
│ [running] web-server   ││ 2025-09-03 14:02:11 [INFO] Server started :8080│
│ [running] postgres-db  ││ 2025-09-03 14:02:12 [INFO] Connected to DB    │
│ [exited]  redis-cache  ││ 2025-09-03 14:02:15 GET /api/v1/health 200 OK  │
└─────────────────────────┘└──────────────────────────────────────────────────┘
```

#### Executing Shell inside a Container
1. Highlight a running container (e.g., `postgres-db`).
2. Press `e`.
3. `lazydocker` instantly drops you into `docker exec -it postgres-db /bin/sh`.

---

### 6. Real-World Workflows

#### Pruning Unused Docker Resources
1. Press `b` inside `lazydocker` to open the **Bulk Commands** menu.
2. Select **Prune Unused Containers**, **Prune Images**, or **Prune Volumes**.
3. Confirm to instantly reclaim disk space on your Mac.

#### Docker Compose Service Control
Navigate to any directory containing a `docker-compose.yml` file and launch `lazydocker`. The top panel will group your containers by service name, allowing you to restart individual microservices without affecting the rest of your stack.

---

### 7. Custom Configuration

`lazydocker` configuration files are stored at `~/.config/lazydocker/config.yml`. You can add custom commands to the UI menu:

```yaml
gui:
  scrollHeight: 2
  theme:
    activeBorderColor:
      - green
      - bold
customCommands:
  containers:
    - name: Run Database Migrations
      attach: true
      command: 'docker exec -it {{ .Container.ID }} npm run migrate'
      serviceNames:
        - web-api
```

---

### 8. Uninstallation

```bash
brew uninstall lazydocker
rm -rf ~/.config/lazydocker
```
