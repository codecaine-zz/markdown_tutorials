# 📘 Official‑style **Docker** Guide for macOS (Homebrew — 2025 edition)

> **What’s new?**  
> The cheat‑sheet now covers **every core Docker CLI command** (run, build, images, ps, exec, logs, stop, rm, rmi, network, volume, compose up/down) as ready‑to‑copy‑paste one‑liners, each linked to the official Docker reference page.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1‑prereqs)  
2. [Install Docker (CLI + Desktop) via Homebrew](#2‑install)  
3. [Where Homebrew puts Docker (binaries, config, data)](#3‑locations)  
4. [Start / Stop / Restart Docker Desktop](#4‑service)  
5. [Docker CLI basics – interactive & one‑liners](#5‑cli)  
6. **[Docker cheat‑sheet – run / build / push / pull / compose / …](#6‑cheatsheet)** ← **copy‑paste ready**  
7. [Docker Compose – multi‑container workflows](#7‑compose)  
8. [Persistence & storage (images, containers, volumes, build cache)](#8‑persistence)  
9. [Security – docker group, rootless mode, content‑trust, secrets](#9‑security)  
10. [Maintenance – prune, system df, rebuild, daemon logs](#10‑maintenance)  
11. [Common pitfalls & troubleshooting](#11‑mistakes)  
12. [Uninstall / clean‑up](#12‑uninstall)  

---  

<a name="1‑prereqs"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version            # 4.x or later required
# If missing → install Homebrew
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Homebrew is the **officially supported** way to install Docker on macOS 【1†L5-L9】.  

---  

<a name="2‑install"></a>
## 2️⃣ Install Docker (CLI + Desktop) via Homebrew  

| What you need | Command | Result |
|---------------|---------|--------|
| **Docker CLI only** (no daemon) | `brew install docker` | Installs the `docker` client (v 28.5.0) 【15†L4-L13】 |
| **Docker Desktop** (includes the daemon, GUI, and Compose v2) | `brew install --cask docker` | Installs Docker Desktop 4.31.0 (macOS ≥ 12) 【17†L4-L13】【17†L23-L27】 |
| **Docker Compose** (stand‑alone, if you prefer the old v1) | `brew install docker-compose` | Installs Docker Compose 2.27.1 【14†L60-L66】 |

*Why the cask?* The Docker daemon (`dockerd`) runs only inside Docker Desktop on macOS; the formula alone provides only the client binaries.  

---  

<a name="3‑locations"></a>
## 3️⃣ Where Homebrew puts Docker  

| Item | Path (Apple silicon) | Path (Intel) | How to retrieve |
|------|---------------------|--------------|-----------------|
| **Docker client binaries** (`docker`, `docker-compose`) | `/opt/homebrew/opt/docker/bin/` | `/usr/local/opt/docker/bin/` | `$(brew --prefix docker)/bin` |
| **Docker Desktop app** | `/Applications/Docker.app` (installed by cask) | same | `open -a Docker` |
| **Docker daemon socket** | `unix:///var/run/docker.sock` (managed by Desktop) | same | `docker info` shows it |
| **Configuration directory** (`~/.docker/`) | `~/.docker/` (CLI config, auth) | same | |
| **Engine data (images, containers, volumes)** | `$HOME/Library/Containers/com.docker.docker/Data/vms/0/` (Desktop) | same | See Docker Desktop *Troubleshoot* screen |
| **Compose file** (project‑local) | `docker-compose.yml` in your project directory | – | – |

The Homebrew formula installs the client binaries, while Docker Desktop creates its own VM‑backed data directory (see the Desktop *Troubleshoot* panel).  

---  

<a name="4‑service"></a>
## 4️⃣ Start / Stop / Restart Docker Desktop  

| Action | Command / UI | Result |
|--------|--------------|--------|
| **Launch Desktop** (GUI) | `open -a Docker` or click Docker.app in /Applications | Docker icon appears in the menu bar; daemon starts |
| **Stop Desktop** | Click the Docker menu → *Quit Docker Desktop* | Daemon shuts down, socket disappears |
| **Restart** | Click the Docker menu → *Restart Docker Desktop* | Re‑initialises the VM |
| **Run headless (no UI)** | `open -g -a Docker` (launches without bringing the app front) | Useful for CI scripts |
| **Check daemon health** | `docker info` | Shows `Server Version`, `Docker Root Dir`, etc. |

Docker Desktop automatically starts at login after the first launch (can be toggled in *Preferences → General*).  

---  

<a name="5‑cli"></a>
## 5️⃣ Docker CLI basics – interactive & one‑liners  

```bash
# Verify client can talk to the daemon
docker version               # client & server version
docker info                  # daemon details

# Pull an image
docker pull alpine           # pulls the latest Alpine Linux image

# Run a container (one‑off)
docker run --rm -it alpine sh

# List running containers
docker ps

# List all containers (incl. stopped)
docker ps -a
```

All commands support `--help` for inline docs (e.g. `docker run --help`).  
See the official CLI reference 【19†L19-L45】.  

---  

<a name="6‑cheatsheet"></a>
## 6️⃣ **Docker cheat‑sheet – one‑liners for everyday container work**  

> Copy‑paste any line into your terminal.  Each command links to the official Docker reference page.  

| Goal | Command | Docs |
|------|---------|------|
| **Run a container** (detached, name `web`, port 8080 → 80) | `docker run -d --name web -p 8080:80 nginx:alpine` | <https://docs.docker.com/engine/reference/commandline/run/> |
| **Run a container with a bind‑mount** | `docker run -d -v $(pwd)/code:/app myimage` | <https://docs.docker.com/engine/reference/commandline/run/#mount-volume---v---mount> |
| **Execute a command in a running container** | `docker exec -it web bash` | <https://docs.docker.com/engine/reference/commandline/exec/> |
| **Show container logs (follow)** | `docker logs -f web` | <https://docs.docker.com/engine/reference/commandline/logs/> |
| **Stop & remove a container** | `docker rm -f web` | <https://docs.docker.com/engine/reference/commandline/rm/> |
| **List images** | `docker images -a` | <https://docs.docker.com/engine/reference/commandline/images/> |
| **Remove an image** | `docker rmi nginx:alpine` | <https://docs.docker.com/engine/reference/commandline/rmi/> |
| **Build an image from a Dockerfile** | `docker build -t myapp:1.0 .` | <https://docs.docker.com/engine/reference/commandline/build/> |
| **Push an image to a registry** | `docker push myrepo/myapp:1.0` | <https://docs.docker.com/engine/reference/commandline/push/> |
| **Pull an image** | `docker pull redis:7-alpine` | <https://docs.docker.com/engine/reference/commandline/pull/> |
| **Tag an existing image** | `docker tag myapp:1.0 myrepo/myapp:latest` | <https://docs.docker.com/engine/reference/commandline/tag/> |
| **Show disk usage** | `docker system df` | <https://docs.docker.com/engine/reference/commandline/system_df/> |
| **Prune unused objects** | `docker system prune -a --volumes` | <https://docs.docker.com/engine/reference/commandline/system_prune/> |
| **Create a network** | `docker network create backend` | <https://docs.docker.com/engine/reference/commandline/network_create/> |
| **List networks** | `docker network ls` | <https://docs.docker.com/engine/reference/commandline/network_ls/> |
| **Remove a network** | `docker network rm backend` | <https://docs.docker.com/engine/reference/commandline/network_rm/> |
| **Create a volume** | `docker volume create dbdata` | <https://docs.docker.com/engine/reference/commandline/volume_create/> |
| **List volumes** | `docker volume ls` | <https://docs.docker.com/engine/reference/commandline/volume_ls/> |
| **Remove a volume** | `docker volume rm dbdata` | <https://docs.docker.com/engine/reference/commandline/volume_rm/> |
| **Inspect an object (JSON output)** | `docker inspect web` | <https://docs.docker.com/engine/reference/commandline/inspect/> |
| **Copy files from/to a container** | `docker cp web:/etc/hosts ./hosts` | <https://docs.docker.com/engine/reference/commandline/cp/> |
| **Get a shell inside a container** | `docker exec -it web /bin/sh` | same as `exec` above |
| **View real‑time resource usage** | `docker stats` | <https://docs.docker.com/engine/reference/commandline/stats/> |

---  

<a name="7‑compose"></a>
## 7️⃣ Docker **Compose** – multi‑container workflows  

Docker Desktop ships **Compose v2** (as `docker compose`). The classic `docker‑compose` command is still available if you installed the separate formula.  

| Goal | Command | Docs |
|------|---------|------|
| **Start services defined in `docker-compose.yml`** | `docker compose up -d` | <https://docs.docker.com/compose/reference/up/> |
| **Re‑build images and start** | `docker compose up --build -d` | same |
| **Stop & remove containers** | `docker compose down` | <https://docs.docker.com/compose/reference/down/> |
| **View service logs (follow)** | `docker compose logs -f` | <https://docs.docker.com/compose/reference/logs/> |
| **Run a one‑off command in a service** | `docker compose run --rm web python app.py` | <https://docs.docker.com/compose/reference/run/> |
| **Scale a service** | `docker compose up -d --scale web=3` | <https://docs.docker.com/compose/reference/up/#scale> |
| **List containers belonging to the compose project** | `docker compose ps` | <https://docs.docker.com/compose/reference/ps/> |

---  

<a name="8‑persistence"></a>
## 8️⃣ Persistence & storage  

| Storage type | Where it lives (Docker Desktop) | How to manage |
|--------------|-------------------------------|---------------|
| **Images & layers** | `$HOME/Library/Containers/com.docker.docker/Data/vms/0/data/docker/` (managed by the VM) | `docker image prune`, `docker system prune` |
| **Containers (filesystem)** | Same VM directory, each container gets a thin‑provisioned overlay | `docker rm -v` to delete container + its anonymous volumes |
| **Named volumes** | `$HOME/Library/Containers/com.docker.docker/Data/vms/0/volumes/` | `docker volume ls`, `docker volume rm` |
| **Build cache** | `~/.docker/buildx` (if using BuildKit) | `docker builder prune` |
| **Docker Desktop settings** (disk image size, file sharing) | Preferences → Resources → Advanced | Adjust via UI or `Docker Desktop` → *Troubleshoot* → *Reset to factory defaults* |

---  

<a name="9‑security"></a>
## 9️⃣ Security  

| Concern | Remedy | Docs |
|---------|--------|------|
| **Avoid `sudo` for every command** | Add your user to the `docker` group (macOS uses the Docker Desktop daemon, so the UI creates the appropriate socket permissions). For Linux, run `sudo dscl . -create /Groups/docker` and add your user. | <https://docs.docker.com/engine/install/linux-postinstall/> |
| **Rootless mode** (daemon runs without root) | Enable in Docker Desktop → *Settings → Docker Engine* → `"experimental": true` and then `docker context use rootless` | <https://docs.docker.com/engine/security/rootless/> |
| **Content‑trust (image signing)** | `export DOCKER_CONTENT_TRUST=1` | <https://docs.docker.com/engine/security/trust/> |
| **Credential helpers** (store registry passwords in macOS Keychain) | `docker login` automatically uses `osxkeychain` helper (`~/.docker/config.json` contains `"credsStore":"osxkeychain"`). | <https://docs.docker.com/engine/reference/commandline/login/> |
| **Secrets management** | Use `docker secret create` (Swarm) or mount secrets as files/ENV vars; for compose, use `docker compose --secret` | <https://docs.docker.com/engine/swarm/secrets/> |
| **Scan images for vulnerabilities** | `docker scan myimage` (Docker Desktop ships with `docker scan` powered by Snyk) | <https://docs.docker.com/engine/scan/> |

---  

<a name="10‑maintenance"></a>
## 1️⃣0️⃣ Maintenance – housekeeping & diagnostics  

| Task | Command | When to run | Docs |
|------|---------|-------------|------|
| **Show daemon logs** | `log show --predicate 'process == "Docker"' --info --debug` (macOS Console) or via Desktop *Troubleshoot* → *Diagnose & Feedback* | After a failure | <https://docs.docker.com/desktop/mac/troubleshoot/> |
| **Prune dangling objects** | `docker system prune -a --volumes` | Periodically to free space | <https://docs.docker.com/engine/reference/commandline/system_prune/> |
| **Inspect disk usage** | `docker system df` | Before cleanup | <https://docs.docker.com/engine/reference/commandline/system_df/> |
| **Rebuild builder cache** | `docker builder prune` | After many builds | <https://docs.docker.com/engine/reference/commandline/builder_prune/> |
| **Upgrade Docker Desktop** | Desktop UI → *Settings → General → Check for updates* or `brew upgrade --cask docker` | Keep up‑to‑date | Homebrew docs |
| **Reset to factory defaults** | Desktop → *Troubleshoot* → *Reset to factory defaults* | Corrupt state | same as above |

---  

<a name="11‑mistakes"></a>
## ⚠️ 1️⃣1️⃣ Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `Cannot connect to the Docker daemon at unix:///var/run/docker.sock` | Docker Desktop not running (socket missing) | Launch Docker (`open -a Docker`) |
| **Permission denied** on `/var/run/docker.sock` (macOS) | The socket belongs to root & the Desktop group; your user isn’t in the group | Restart Desktop (it sets proper permissions) or run with `sudo` (not recommended) |
| **“Error response from daemon: pull access denied”** | Trying to pull a private image without login | `docker login` to the registry |
| **Out‑of‑space errors** (`No space left on device`) | Disk image for Docker Desktop filled up | Increase disk size in *Preferences → Resources → Disk image size* or prune unused images/volumes |
| **Build hangs** | BuildKit cache corrupted | `docker builder prune -a` |
| **Compose reports “service … not found”** | Wrong working directory (no `docker-compose.yml`) | `cd` to the directory containing the Compose file |
| **`docker compose` command not found** | Using older Docker Desktop (pre‑v2) or only installed `docker-compose` formula | Upgrade Desktop or use `docker-compose` explicitly |

*General troubleshooting*: <https://docs.docker.com/desktop/troubleshoot/>  

---  

<a name="12‑uninstall"></a>
## 1️⃣2️⃣ Uninstall / clean‑up  

```bash
# Stop Docker Desktop first
osascript -e 'quit app "Docker"'   # or click Quit in the menu bar

# Remove the CLI formula
brew uninstall docker
brew uninstall docker-compose    # if you installed it separately

# Remove the Desktop app (cask)
brew uninstall --cask docker

# Optional: delete all Docker data (VM, images, volumes, config)
rm -rf "$HOME/Library/Containers/com.docker.docker"
rm -rf "$HOME/.docker"
```

If you keep the Desktop app but want a fresh start, you can also use *Docker Desktop → Troubleshoot → Reset to factory defaults* (no manual file removal needed).  

*Uninstall docs*: <https://docs.brew.sh/Manpage#uninstall>  

---  

## Reference List (all linked commands)

- **docker** (CLI base) – <https://docs.docker.com/engine/reference/commandline/docker/>  
- **docker run** – <https://docs.docker.com/engine/reference/commandline/run/>  
- **docker build** – <https://docs.docker.com/engine/reference/commandline/build/>  
- **docker pull / push** – <https://docs.docker.com/engine/reference/commandline/pull/> / <https://docs.docker.com/engine/reference/commandline/push/>  
- **docker exec** – <https://docs.docker.com/engine/reference/commandline/exec/>  
- **docker logs** – <https://docs.docker.com/engine/reference/commandline/logs/>  
- **docker rm / rmi** – <https://docs.docker.com/engine/reference/commandline/rm/> / <https://docs.docker.com/engine/reference/commandline/rmi/>  
- **docker compose** – <https://docs.docker.com/compose/reference/>  
- **docker system prune / df** – <https://docs.docker.com/engine/reference/commandline/system_prune/> / <https://docs.docker.com/engine/reference/commandline/system_df/>  
- **docker network / volume** – <https://docs.docker.com/engine/reference/commandline/network_create/> / <https://docs.docker.com/engine/reference/commandline/volume_create/>  
- **docker login / credential helpers** – <https://docs.docker.com/engine/reference/commandline/login/>  
- **Rootless mode** – <https://docs.docker.com/engine/security/rootless/>  

---  

# 🎉 All set!  

You now have a **complete, copy‑paste‑ready reference** for installing Docker (CLI + Desktop) on macOS via Homebrew, locating its files, starting the daemon, using the full suite of Docker commands, composing multi‑container apps, securing your setup, maintaining storage, and troubleshooting common issues – each command linked to the official Docker documentation. Happy containerizing! 🚀  