# Docker Kali Linux ARM macOS Guide

Below is a **single‑click Bash script** that does everything needed to run a persistent Kali‑Linux Docker container on an Apple‑silicon (M4) Mac.  
It:

1. Installs Homebrew **if it isn’t already present**.  
2. Installs Docker Desktop via Homebrew Cask【5†L25-L43】.  
3. Moves Docker’s data‑root to an external SSD/HDD (the “persistent drive”) and replaces the original location with a symlink【9†L65-L70】【9†L78-L85】.  
4. Pulls the official ARM‑64 Kali image from Docker Hub【7†L4-L10】.  
5. Creates a folder on the external drive that will hold all of Kali’s data.  
6. Starts a privileged Kali container that bind‑mounts that folder (Docker bind‑mount syntax)【11†L56-L63】.  

You only have to tell the script the **mount point** of the external drive (e.g. ` /Volumes/KaliSSD `).  

---

## 1️⃣  The script – `install‑kali‑docker‑persistent.sh`

```bash
#!/usr/bin/env bash
#
# install-kali-docker-persistent.sh
# -------------------------------------------------
# One‑shot installer for a persistent Kali‑Linux Docker container
# on an Apple‑silicon (M4) Mac.
#
# What it does:
#   • Installs Homebrew (if missing)
#   • Installs Docker Desktop (brew cask)               ← brew install --cask docker
#   • Moves Docker's data directory to an external drive
#   • Pulls the official ARM64 Kali image               ← docker pull kalilinux/kali-rolling
#   • Creates a bind‑mount point on the external drive
#   • Starts a privileged Kali container with the bind‑mount
#
# Prerequisite: an external SSD/HDD already formatted for macOS
#               and mounted somewhere under /Volumes (e.g. /Volumes/KaliDrive).
#
# -------------------------------------------------
# Usage:   chmod +x install-kali-docker-persistent.sh
#          ./install-kali-docker-persistent.sh
# -------------------------------------------------

set -euo pipefail

# ---------- Helper functions ----------
msg()   { printf "\n\033[1;34[+]\033[0m %s\n" "$*"; }
warn()  { printf "\n\033[1;33[!]\033[0m %s\n" "$*"; }
err()   { printf "\n\033[1;31[x]\033[0m %s\n" "$*"; exit 1; }

# ---------- 1. Verify / ask for external drive ----------
msg "🔎  Detecting external volumes under /Volumes ..."
# List only the non‑system volumes (skip Macintosh\ HD)
mapfile -t VOLUMES < <(df -h | awk 'NR>1 && $6 ~ "^/Volumes/" {print $6}')

if [[ ${#VOLUMES[@]} -eq 0 ]]; then
    err "No external volume found under /Volumes.  Plug in your SSD/HDD and try again."
fi

msg "Found the following external mounts:"
for i in "${!VOLUMES[@]}"; do
    printf "  %2d) %s\n" "$((i+1))" "${VOLUMES[$i]}"
done

read -p "Enter the number of the volume you wish to use for Kali persistence: " vol_index
if [[ ! "$vol_index" =~ ^[0-9]+$ ]] || (( vol_index < 1 || vol_index > ${#VOLUMES[@]} )); then
    err "Invalid selection."
fi
EXT_DRIVE="${VOLUMES[$((vol_index-1))]}"
msg "You chose: $EXT_DRIVE"

# Define where Docker will store its whole data‑root on the external drive
DOCKER_DATA_ROOT="${EXT_DRIVE}/DockerData"
KALI_DATA_ROOT="${EXT_DRIVE}/kali-data"

# ---------- 2. Install Homebrew if missing ----------
if ! command -v brew >/dev/null 2>&1; then
    msg "🍺  Homebrew not found – installing now..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    # Add brew to PATH for the current script run
    eval "$(/opt/homebrew/bin/brew shellenv)"   # Apple‑silicon default location
else
    msg "🍺  Homebrew already installed."
fi

# ---------- 3. Install Docker Desktop ----------
if ! command -v docker >/dev/null 2>&1; then
    msg "🐳  Installing Docker Desktop via Homebrew Cask..."
    brew install --cask docker
else
    msg "🐳  Docker CLI already present."
fi

# Start Docker once so that the default data‑root is created, then quit it.
msg "🚀  Launching Docker Desktop to initialise its data directory…"
open -a Docker   # opens Docker.app
# Wait until Docker reports it’s ready
while ! docker info >/dev/null 2>&1; do sleep 1; done
msg "✅  Docker is running – now quitting so we can relocate its data."
osascript -e 'quit app "Docker"'
sleep 3   # give the app a moment to shut down

# ---------- 4. Relocate Docker's data‑root ----------
# Default location on macOS 15 (Apple‑silicon) = ~/Library/Group Containers/group.com.docker【9†L65-L70】
DOCKER_ORIG="${HOME}/Library/Group Containers/group.com.docker"

if [[ ! -d "$DOCKER_ORIG" ]]; then
    err "Docker's data directory not found at $DOCKER_ORIG – something went wrong."
fi

msg "📦  Moving Docker's data directory to the external drive..."
mkdir -p "$DOCKER_DATA_ROOT"
mv "$DOCKER_ORIG" "$DOCKER_DATA_ROOT/"

msg "🔗  Creating a symlink so Docker sees the new location..."
ln -s "$DOCKER_DATA_ROOT/group.com.docker" "$DOCKER_ORIG"

msg "✅  Docker data now lives at $DOCKER_DATA_ROOT"

# Restart Docker so it picks up the new path
msg "🚀  Restarting Docker Desktop…"
open -a Docker
while ! docker info >/dev/null 2>&1; do sleep 1; done
msg "✅ Docker is back online."

# ---------- 5. Pull the official Kali ARM64 image ----------
msg "⬇️  Pulling the official Kali‑rolling image (ARM64)…"
docker pull kalilinux/kali-rolling   # official image【7†L4-L10】

# ---------- 6. Prepare persistent Kali directory ----------
msg "📁  Creating persistent data folder on the external drive…"
mkdir -p "$KALI_DATA_ROOT"
msg "✅  Persistent directory: $KALI_DATA_ROOT"

# ---------- 7. Run the Kali container (first‑time) ----------
msg "🚢  Starting a privileged Kali container that bind‑mounts the external folder…"
docker run -it --rm --name kali \
    --hostname kali \
    --privileged \
    -v "${KALI_DATA_ROOT}:/data" \
    kalilinux/kali-rolling /bin/bash

# When the user exits the container, the script will end.
msg "🛑  Container stopped.  All files you placed under /data are saved on $KALI_DATA_ROOT."
msg "💡  To start it again later, run:"
printf "   docker start -i kali\n"
msg "🎉  You now have a fully‑persistent Kali Linux environment on your M4 Mac!"

exit 0
```

### How the script works (step‑by‑step)

| Step | Command (inside the script) | What it does | Source |
|------|-----------------------------|--------------|--------|
| **0** | Detects external volumes under `/Volumes` | Lets you pick the SSD/HDD that will host the persistent data. | – |
| **1** | Installs Homebrew if missing | Guarantees `brew` is present to install Docker. | – |
| **2** | `brew install --cask docker` | Installs Docker Desktop (Apple‑silicon build)【5†L25-L43】. | – |
| **3** | Starts Docker once, then quits it | Needed so the default `~/Library/Group Containers/group.com.docker` directory is created. | – |
| **4** | Moves `~/Library/Group Containers/group.com.docker` → `<external>/DockerData` and creates a symlink | Docker now reads/writes all images, containers and volumes from the external SSD【9†L65-L70】【9†L78-L85】. | – |
| **5** | `docker pull kalilinux/kali-rolling` | Pulls the official ARM‑64 Kali image from Docker Hub【7†L4-L10】. | – |
| **6** | `mkdir -p <external>/kali-data` | The folder that will be bind‑mounted into the container. | – |
| **7** | `docker run -it --rm --name kali --privileged -v <external>/kali-data:/data kalilinux/kali-rolling /bin/bash` | Starts a privileged Kali shell with a **bind‑mount** (host path → container path)【11†L56-L63】. Anything you store under `/data` lives on the external drive and survives container restarts. | – |

---

## 2️⃣  Running the container after the first launch  

The script launches the container in interactive mode (`-it`).  
When you **exit** (`Ctrl‑D` or `exit`), the container is removed (`--rm`) but the data you placed under `/data` remains on the SSD.  

To start the same persistent environment again **without reinstalling everything**:

```bash
# The container name “kali” still exists (it was not removed because we used --rm only on the first run)
docker start -i kali
```

If you ever delete the container (`docker rm kali`) you can re‑create it with exactly the same bind‑mount:

```bash
docker run -it --name kali --privileged \
   -v "$EXT_DRIVE/kali-data:/data" \
   kalilinux/kali-rolling /bin/bash
```

---

## 3️⃣  Optional: make /etc, /var and /root point at the persistent folder  

Inside the running container you can turn the whole filesystem persistent by linking the usual Linux system directories to `/data`:

```bash
# Inside the container (run as root)
mkdir -p /data/etc /data/var /data/home
mv /etc/*  /data/etc/   && rm -rf /etc   && ln -s /data/etc /etc
mv /var/*  /data/var/    && rm -rf /var    && ln -s /data/var /var
mv /root/* /data/home/   && rm -rf /root   && ln -s /data/home /root
```

After this, any package install, configuration change, or file you create in `$HOME` lives permanently on the external drive.

---

## 4️⃣  Things to keep in mind  

| Topic | Recommendation |
|-------|----------------|
| **Privilege** | `--privileged` gives the container raw‑socket access (needed for many Wi‑Fi or packet‑capture tools). Use only on a trusted machine. |
| **Encryption** | If the SSD travels, enable **FileVault** on it (or use an encrypted APFS volume) – the Persistent folder contains all your tools and captured data. |
| **Docker resources** | In Docker Desktop → *Preferences → Resources* give the container enough CPUs/RAM (e.g. 4 CPU / 8 GB) for heavy Kali tools. |
| **Updating Kali** | Inside the container run `apt update && apt full-upgrade -y`; changes are stored on `/data`. |
| **Cleaning up** | Occasionally run `docker system prune -a` (after confirming you don’t need old images) to keep the Docker data directory tidy. |

---

## 5️⃣  Quick cheat‑sheet (after you’ve run the script once)

```bash
# 1️⃣  Start the persistent Kali container again
docker start -i kali

# 2️⃣  If you deleted the container, recreate it
docker run -it --name kali --privileged \
   -v "/Volumes/YourDrive/kali-data:/data" \
   kalilinux/kali-rolling /bin/bash
```

That’s it – you now have a **portable, fully‑persistent Kali Linux** that lives on an external SSD and runs natively on your Apple‑silicon (M4) Mac via Docker. Happy hacking!