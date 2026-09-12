# 🐳 Ubuntu Docker Development & Environment Manager Guide

**A professional-grade toolkit and reference for deploying, managing, and automating high-performance Docker environments on Ubuntu Linux.**

Unlike macOS and Windows which run Docker inside a lightweight hypervisor virtual machine, running Docker directly on Ubuntu Linux delivers **100% bare-metal performance, near-instant container startup, and native cgroups v2 resource scheduling**.

---

## 📋 Table of Contents

1. [✨ Features & Linux Native Advantages](#features--linux-native-advantages)
2. [📋 Clean Installation (Official Docker CE & Compose v2)](#clean-installation-official-docker-ce--compose-v2)
3. [👤 Non-Root & Rootless Docker Security Setup](#non-root--rootless-docker-security-setup)
4. [⚙️ Production Daemon Configuration (`daemon.json`)](#production-daemon-configuration-daemonjson)
5. [🚀 Complete Development Stack Compose Template](#complete-development-stack-compose-template)
6. [🛠 Docker Development Manager CLI Script](#docker-development-manager-cli-script)
7. [💾 Data Persistence, Volume Management & Backups](#data-persistence-volume-management--backups)
8. [📊 Resource Limits, Profiling & Real-Time Monitoring](#resource-limits-profiling--real-time-monitoring)
9. [🐛 Troubleshooting & Firewall Conflicts](#troubleshooting--firewall-conflicts)
10. [🧹 Automated Cleanup & Disk Maintenance](#automated-cleanup--disk-maintenance)

---

## ✨ Features & Linux Native Advantages

- **Zero Virtualization Overhead**: Native Linux kernel namespaces and cgroups v2.
- **Direct Filesystem I/O**: Eliminates VirtioFS / osxfs volume speed bottlenecks.
- **Production Parity**: Local dev containers run identically to cloud servers (Kubernetes, AWS ECS, GCP Cloud Run).
- **Official Repository Strategy**: Avoids Canonical Snap container sandboxing and permissions issues.
- **Rootless Engine Support**: Run the entire Docker daemon and containers inside user namespaces with unprivileged accounts.

---

## 📋 Clean Installation (Official Docker CE & Compose v2)

Do **not** install Docker from Ubuntu's default universe repo or via Snap. Follow the official Docker repository setup for latest updates and Compose v2:

```bash
# 1. Remove any legacy or conflicting packages
sudo apt remove -y docker docker-engine docker.io containerd runc 2>/dev/null || true

# 2. Install required transport and certificate utilities
sudo apt update
sudo apt install -y ca-certificates curl gnupg lsb-release

# 3. Add Docker official GPG key
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# 4. Set up Docker APT repository
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# 5. Install Docker Engine, CLI, containerd, and Compose v2 plugin
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# 6. Verify installation
docker --version
docker compose version
```

---

## 👤 Non-Root & Rootless Docker Security Setup

### 1. Standard Non-Root User Configuration
By default, the Docker daemon binds to a Unix socket owned by `root`. Add your user to the `docker` group to run commands without `sudo`:

```bash
# Create docker group if not existing
sudo groupadd docker 2>/dev/null || true

# Add current user to docker group
sudo usermod -aG docker $USER

# Apply group changes immediately without logging out
newgrp docker

# Verify non-root execution
docker run --rm hello-world
```

### 2. Rootless Docker Setup (Hardened Security)
Rootless mode allows running the Docker daemon and containers as a regular unprivileged user to mitigate container breakout vulnerabilities:

```bash
# Install rootless prerequisites
sudo apt install -y uidmap dbus-user-session

# Disable standard system-wide daemon if running rootless exclusively
sudo systemctl disable --now docker.service docker.socket

# Run official rootless installer script
dockerd-rootless-setuptool.sh install

# Export rootless socket environment variable
echo 'export DOCKER_HOST=unix://$XDG_RUNTIME_DIR/docker.sock' >> ~/.bashrc
source ~/.bashrc

# Enable rootless daemon to persist across reboots without logging in
loginctl enable-linger $USER
```

---

## ⚙️ Production Daemon Configuration (`daemon.json`)

Configure `/etc/docker/daemon.json` for optimal logging, storage performance, and stability:

```bash
sudo tee /etc/docker/daemon.json << 'EOF'
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "20m",
    "max-file": "5"
  },
  "storage-driver": "overlay2",
  "live-restore": true,
  "default-ulimits": {
    "nofile": {
      "Name": "nofile",
      "Hard": 65536,
      "Soft": 65536
    }
  },
  "dns": ["1.1.1.1", "8.8.8.8"],
  "features": {
    "buildkit": true
  }
}
EOF

# Restart Docker daemon to apply configuration
sudo systemctl restart docker
```

---

## 🚀 Complete Development Stack Compose Template

A ready-to-use `docker-compose.yml` defining a modern multi-service stack with hot-reloading, persistent volumes, and health checks:

```yaml
services:
  # Node.js / Bun Fullstack Web Service
  app:
    image: oven/bun:latest
    container_name: dev_app
    restart: unless-stopped
    working_dir: /app
    volumes:
      - ./:/app
      - app_node_modules:/app/node_modules
    ports:
      - "3000:3000"
    environment:
      - NODE_ENV=development
      - DATABASE_URL=postgresql://postgres:secretpassword@postgres:5432/dev_db
      - REDIS_URL=redis://redis:6379
    command: bun run --hot src/index.ts
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_healthy

  # PostgreSQL Database
  postgres:
    image: postgres:16-alpine
    container_name: dev_postgres
    restart: unless-stopped
    environment:
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: secretpassword
      POSTGRES_DB: dev_db
    ports:
      - "5432:5432"
    volumes:
      - pg_data:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 5s
      timeout: 5s
      retries: 5

  # Redis In-Memory Cache
  redis:
    image: redis:7-alpine
    container_name: dev_redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 5s
      timeout: 3s
      retries: 5

volumes:
  pg_data:
  redis_data:
  app_node_modules:
```

---

## 🛠 Docker Development Manager CLI Script

Create a reusable bash CLI tool at `~/.local/bin/docker-dev` for container lifecycle management:

```bash
cat << 'EOF' > ~/.local/bin/docker-dev
#!/usr/bin/env bash
set -euo pipefail

function show_help() {
    echo "Usage: docker-dev [command]"
    echo ""
    echo "Commands:"
    echo "  start     Start all stack containers in detached mode"
    echo "  stop      Stop all running containers"
    echo "  restart   Restart the development stack"
    echo "  logs      Stream live logs across all containers"
    echo "  status    Show active containers, health status, and port mappings"
    echo "  sh [app]  Open bash/sh shell inside specified container"
    echo "  backup    Create compressed backup of all named volumes"
    echo "  clean     Remove unused containers, networks, and images"
    echo ""
}

CMD="${1:-help}"

case "$CMD" in
    start)
        echo "==> Starting Docker development stack..."
        docker compose up -d
        ;;
    stop)
        echo "==> Stopping Docker development stack..."
        docker compose stop
        ;;
    restart)
        echo "==> Restarting Docker development stack..."
        docker compose restart
        ;;
    logs)
        docker compose logs -f --tail=100
        ;;
    status)
        echo "==> Docker Stack Status:"
        docker compose ps -a
        echo ""
        echo "==> Resource Utilization:"
        docker stats --no-stream
        ;;
    sh)
        TARGET="${2:-app}"
        echo "==> Opening interactive shell in container: $TARGET"
        docker compose exec -it "$TARGET" /bin/sh
        ;;
    backup)
        BACKUP_DIR="./backups"
        mkdir -p "$BACKUP_DIR"
        TIMESTAMP=$(date +%Y%m%d_%H%M%S)
        echo "==> Backing up PostgreSQL data volume..."
        docker run --rm -v "$(basename "$PWD")_pg_data":/source:ro -v "$PWD/$BACKUP_DIR":/backup alpine \
            tar -czf "/backup/postgres_backup_${TIMESTAMP}.tar.gz" -C /source .
        echo "Backup saved to: $BACKUP_DIR/postgres_backup_${TIMESTAMP}.tar.gz"
        ;;
    clean)
        echo "==> Cleaning up unused containers, networks, and untagged images..."
        docker system prune -f
        ;;
    *)
        show_help
        exit 1
        ;;
esac
EOF

chmod +x ~/.local/bin/docker-dev
```

---

## 💾 Data Persistence, Volume Management & Backups

### Inspecting & Managing Docker Volumes
```bash
# List all Docker volumes on the system
docker volume ls

# Inspect storage path on host filesystem (located under /var/lib/docker/volumes/)
docker volume inspect dev_postgres_data

# Restore a volume from a compressed tarball
docker run --rm \
    -v dev_postgres_data:/target \
    -v $(pwd)/backups:/backup alpine \
    tar -xzf /backup/postgres_backup.tar.gz -C /target
```

---

## 📊 Resource Limits, Profiling & Real-Time Monitoring

### Setting Precise Resource Limits (cgroups v2)
Prevent runaway build processes from freezing your Ubuntu desktop:

```bash
# Run container constrained to 2 CPU cores and 2GB RAM
docker run -d \
    --name build_runner \
    --cpus="2.0" \
    --memory="2048m" \
    --memory-swap="2048m" \
    ubuntu:24.04 sleep infinity

# Inspect applied cgroup resource boundaries on host
cat /sys/fs/cgroup/docker/<container_id>/memory.max
```

### Real-Time Resource Monitoring
```bash
# Formatted live stats table (CPU %, MEM %, NET I/O, BLOCK I/O)
docker stats --format "table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.NetIO}}\t{{.BlockIO}}"
```

---

## 🐛 Troubleshooting & Firewall Conflicts

### 1. UFW (Uncomplicated Firewall) Port Exposure
By default, Docker modifies `iptables` directly, which can bypass UFW rules and expose ports to external networks!

```bash
# To make Docker respect UFW on Ubuntu, install ufw-docker utility
sudo wget -O /usr/local/bin/ufw-docker \
  https://github.com/chaifeng/ufw-docker/raw/master/ufw-docker
sudo chmod +x /usr/local/bin/ufw-docker

# Install the necessary firewall chains
sudo ufw-docker install

# Now you can explicitly allow or block container ports via UFW:
sudo ufw-docker allow dev_app 3000
```

### 2. Docker Daemon Socket Permissions Error
```bash
# Error: "permission denied while trying to connect to the Docker daemon socket"
sudo chmod 666 /var/run/docker.sock
# Permanent fix:
sudo usermod -aG docker $USER && newgrp docker
```

### 3. Container DNS Resolution Failure
If containers cannot resolve external domain names (often caused by systemd-resolved on Ubuntu):
```bash
# Check if /etc/resolv.conf points to 127.0.0.53
cat /etc/resolv.conf

# Add explicit fallback nameservers in /etc/docker/daemon.json:
# "dns": ["1.1.1.1", "8.8.8.8"]
sudo systemctl restart docker
```

---

## 🧹 Automated Cleanup & Disk Maintenance

### Safe Maintenance Commands
```bash
# Remove all stopped containers
docker container prune -f

# Remove all dangling images
docker image prune -f

# Remove all unused networks
docker network prune -f

# Deep cleanup: remove all unused containers, networks, and unused images
docker system prune -a --volumes -f
```

### Automated Weekly Cleanup Cron Job
```bash
# Add automated cleanup cron to prune unused build cache and containers
(crontab -l 2>/dev/null; echo "0 3 * * 0 /usr/bin/docker system prune -f --filter 'until=168h'") | crontab -
```
