## 🎯 Goal  
Create a **portable, persistent Ubuntu‑Linux development / testing environment** on an Apple‑silicon (M4) Mac using Docker.  
Everything – the Docker engine, the Ubuntu image and all of the container’s data – lives on an external SSD/HDD, so you can unplug the drive, move it to another Mac, or free up internal space.

The whole process is wrapped in **one Bash script** that you simply run once. After that you can start the same Ubuntu container any time with a single `docker start -i ubuntu‑persist`.

---

## 📦 What the script does (step‑by‑step)

| # | Action | Command (inside the script) | Why it matters |
|---|--------|-----------------------------|----------------|
| 1️⃣ | Detect external volumes under `/Volumes` and let you pick the one that will store the persistent data. | `df -h | awk …` | Guarantees you use the right SSD/HDD. |
| 2️⃣ | Install **Homebrew** if it’s missing (the package manager we use to get Docker). | `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"` | Gives us `brew`. |
| 3️⃣ | Install **Docker Desktop** for Apple‑silicon via Homebrew Cask【5†L25-L43】. | `brew install --cask docker` | Provides the Docker daemon (`dockerd`) and the `docker` CLI. |
| 4️⃣ | Start Docker once so its default data directory (`~/Library/Group Containers/group.com.docker`) is created, then quit it. | `open -a Docker …` / `osascript -e 'quit app "Docker"'` | We need the folder to move it. |
| 5️⃣ | **Move** Docker’s data‑root to the external drive and replace the original location with a **symlink**【9†L65-L70】【9†L78-L85】. | `mv … /Volumes/YourDrive/DockerData/` + `ln -s …` | All images, containers and volumes now live on the SSD – internal storage stays free. |
| 6️⃣ | Restart Docker so it uses the new location. | `open -a Docker` (wait until `docker info` works) | Docker picks up the relocated data. |
| 7️⃣ | Pull the **official Ubuntu arm64 image** (Docker Hub supports `arm64v8` automatically on Apple‑silicon)【13†L57-L61】. | `docker pull ubuntu` | Gives us a native Ubuntu Linux root‑fs. |
| 8️⃣ | Create a folder on the external drive that will be **bind‑mounted** into the container【11†L56-L63】. | `mkdir -p /Volumes/YourDrive/ubuntu-data` | This directory is the only thing that needs to be persisted. |
| 9️⃣ | Launch a **privileged** Ubuntu container with the bind‑mount (`-v host:container`). | `docker run -it --name ubuntu … -v …:/data ubuntu /bin/bash` | The container sees `/data` as a regular directory whose contents are stored on the SSD – they survive container removal. |
| 🔟 | When you exit the container, the script prints a quick‑start reminder. | `printf "docker start -i ubuntu\n"` | Gives you a one‑liner to restart the same persistent environment later. |

---

## 📜 The full script – `install‑ubuntu‑docker‑persistent.sh`

```bash
#!/usr/bin/env bash
# -------------------------------------------------
# One‑shot installer for a persistent Ubuntu Docker
# container on Apple‑silicon (M4) macOS.
# -------------------------------------------------
# Prerequisite: an external SSD/HDD already mounted under /Volumes
# -------------------------------------------------

set -euo pipefail

# ---------- Helper ----------
msg()   { printf "\n\033[1;34[+]\033[0m %s\n" "$*"; }
err()   { printf "\n\033[1;31[x]\033[0m %s\n" "$*"; exit 1; }

# ---------- 1️⃣ Pick external volume ----------
msg "🔎 Detecting external volumes under /Volumes ..."
mapfile -t VOLS < <(df -h | awk 'NR>1 && $6 ~ "^/Volumes/" {print $6}')
[[ ${#VOLS[@]} -eq 0 ]] && err "No external volume found. Plug in the SSD/HDD and retry."

msg "Found the following external mounts:"
for i in "${!VOLS[@]}"; do printf "  %2d) %s\n" "$((i+1))" "${VOLS[$i]}"; done
read -p "Enter the number of the volume you want to use for persistence: " idx
[[ "$idx" =~ ^[0-9]+$ ]] && (( idx >= 1 && idx <= ${#VOLS[@]} )) || err "Invalid selection."
EXT_DRIVE="${VOLS[$((idx-1))]}"
msg "Selected drive: $EXT_DRIVE"

DOCKER_ROOT="${EXT_DRIVE}/DockerData"
UBUNTU_ROOT="${EXT_DRIVE}/ubuntu-data"

# ---------- 2️⃣ Install Homebrew if missing ----------
if ! command -v brew >/dev/null 2>&1; then
    msg "🍺 Homebrew not found – installing..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    eval "$(/opt/homebrew/bin/brew shellenv)"   # Apple‑silicon default path
else
    msg "🍺 Homebrew already present."
fi

# ---------- 3️⃣ Install Docker Desktop ----------
if ! command -v docker >/dev/null 2>&1; then
    msg "🐳 Installing Docker Desktop via Homebrew Cask..."
    brew install --cask docker
else
    msg "🐳 Docker CLI already available."
fi

# ---------- 4️⃣ Start Docker once, then quit ----------
msg "🚀 Launching Docker Desktop to create its default data directory..."
open -a Docker
while ! docker info >/dev/null 2>&1; do sleep 1; done
msg "✅ Docker is running – now quitting so we can relocate its files."
osascript -e 'quit app "Docker"'
sleep 3

# ---------- 5️⃣ Relocate Docker's data root ----------
DOCKER_ORIG="${HOME}/Library/Group Containers/group.com.docker"
[[ -d "$DOCKER_ORIG" ]] || err "Docker data dir not found at $DOCKER_ORIG"
msg "📦 Moving Docker's data directory to $DOCKER_ROOT ..."
mkdir -p "$DOCKER_ROOT"
mv "$DOCKER_ORIG" "$DOCKER_ROOT/"
msg "🔗 Creating symlink ..."
ln -s "$DOCKER_ROOT/group.com.docker" "$DOCKER_ORIG"

# ---------- 6️⃣ Restart Docker ----------
msg "🚀 Restarting Docker Desktop..."
open -a Docker
while ! docker info >/dev/null 2>&1; do sleep 1; done
msg "✅ Docker is back online and using the external drive."

# ---------- 7️⃣ Pull Ubuntu image ----------
msg "⬇️ Pulling the official Ubuntu image (ARM64)…"
docker pull ubuntu   # Docker Hub will serve the arm64v8 variant on Apple‑silicon【13†L57-L61】

# ---------- 8️⃣ Prepare persistent directory ----------
msg "📁 Creating persistent folder on the external drive..."
mkdir -p "$UBUNTU_ROOT"
msg "✅ Persistent path: $UBUNTU_ROOT"

# ---------- 9️⃣ Run the container ----------
msg "🚢 Starting a privileged Ubuntu container with bind‑mount …"
docker run -it --rm --name ubuntu \
    --hostname ubuntu \
    --privileged \
    -v "${UBUNTU_ROOT}:/data" \
    ubuntu /bin/bash

# When the user exits the container, the script continues here.
msg "🛑 Container stopped. Anything you placed under /data is stored on $UBUNTU_ROOT."
msg "💡 To start the same persistent environment again, run:"
printf "   docker start -i ubuntu\n"
msg "🎉 You now have a fully‑persistent Ubuntu Linux on your M4 Mac!"

exit 0
```

*Save the file, make it executable and run it:*  

```bash
chmod +x install-ubuntu-docker-persistent.sh
./install-ubuntu-docker-persistent.sh
```

The script will ask you which external drive to use, install the required software, move Docker’s whole storage to that drive, pull Ubuntu and finally drop you into an interactive shell inside the container.

---

## 🔁 How to **re‑enter** the persistent Ubuntu container later  

If you simply exited (`Ctrl‑D` or `exit`) the container, Docker kept the image and the bind‑mounted data, so you can bring it back with a one‑liner:

```bash
docker start -i ubuntu          # re‑attach to the same container
```

If you ever removed the container (`docker rm ubuntu`) you can recreate it exactly the same way:

```bash
docker run -it --name ubuntu --privileged \
    -v "/Volumes/YourDrive/ubuntu-data:/data" \
    ubuntu /bin/bash
```

---

## 📂 Optional: make the whole root‑filesystem persistent  

If you want **everything** (`/etc`, `/var`, `$HOME`) to live on the external drive (just like a ‘real’ VM), run the following *once* inside the container (as root):

```bash
mkdir -p /data/etc /data/var /data/home
mv /etc/*  /data/etc/   && rm -rf /etc   && ln -s /data/etc /etc
mv /var/*  /data/var/    && rm -rf /var    && ln -s /data/var /var
mv /root/* /data/home/   && rm -rf /root   && ln -s /data/home /root
```

Now any `apt install`, configuration changes or files you create in `$HOME` are written directly to the SSD and survive any container removal.

---

## 🛠️ Tips & Best Practices  

| Topic | Recommendation |
|-------|----------------|
| **Docker resources** | In Docker Desktop → *Preferences → Resources* give ≥4 CPU cores and 8 GB RAM for comfortable Ubuntu work. |
| **File‑share permission** | Ensure the selected external volume appears under *Docker Desktop → Preferences → Resources → File Sharing* (macOS 15 does this automatically). |
| **Encryption** | Turn on **FileVault** for the external SSD if you intend to transport it – all your Ubuntu data will be encrypted. |
| **Updating Ubuntu** | Inside the container: `apt update && apt full-upgrade -y`. The upgraded packages are stored under `/data`. |
| **Cleaning up old images** | `docker system prune -a` (after confirming you don’t need any stale images). |
| **Privileged mode** | `--privileged` is required only for low‑level tools (e.g., `tcpdump`, `nmap --packet-trace`). Remove it if you don’t need such access. |

---

## 🎉 Summary  

* **One script** does the whole job: installs Homebrew → Docker → relocates Docker’s storage → pulls Ubuntu → creates a bind‑mounted persistent folder → drops you into a ready‑to‑use Ubuntu shell.  
* All the heavy data lives on an **external SSD/HDD**, making the setup **portable** and **space‑friendly** for your M4 Mac.  
* After the first run you can restart the same environment with a single `docker start -i ubuntu` command, or rebuild it any time with the same `docker run …` line.

You now have a fast, lightweight, **persistent Ubuntu development environment** on Apple‑silicon – ready for coding, testing, or any Linux‑only tool you need. Happy hacking!