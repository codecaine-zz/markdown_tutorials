# Podman: Rootless Daemonless Containers & Kubernetes-Native Guide

`Podman` (Pod Manager) is a daemonless, open-source container engine designed to develop, manage, and run OCI containers on Linux, macOS, and Windows. Unlike Docker, Podman runs **rootless by default**, requires no central background daemon (`dockerd`), and natively understands Kubernetes **Pods**.

---

## 📚 Table of Contents

1. [Architectural Comparison: Podman vs. Docker](#1-architectural-comparison-podman-vs-docker)
2. [Prerequisites & Installation](#2-prerequisites--installation)
3. [Podman Machine on macOS (Apple Silicon)](#3-podman-machine-on-macos-apple-silicon)
4. [Running Rootless Containers](#4-running-rootless-containers)
5. [Podman Pods (Local Kubernetes Pods Architecture)](#5-podman-pods-local-kubernetes-pods-architecture)
6. [Kubernetes Manifest Integration (`play kube` & `generate kube`)](#6-kubernetes-manifest-integration-play-kube--generate-kube)
7. [Docker Drop-in Compatibility & Podman Compose](#7-docker-drop-in-compatibility--podman-compose)
8. [Companion Tools: Buildah & Skopeo](#8-companion-tools-buildah--skopeo)

---

## 1. Architectural Comparison: Podman vs. Docker

| Feature | Docker | Podman |
| :--- | :--- | :--- |
| **Daemon Architecture** | Monolithic background daemon (`dockerd`) | Daemonless (Fork-exec model directly under PID) |
| **Default Privileges** | Root / Privileged daemon socket | Non-root (Rootless by default) |
| **Security Surface** | Root socket hijacking risk (`docker.sock`) | Isolated User Namespaces (`subuid`/`subgid`) |
| **Unit of Execution** | Single Container | Container or **Pod** (Shared network/IPC) |
| **Kubernetes Integration** | Requires Kind/Minikube | Native (`podman play kube`, `generate kube`) |
| **Systemd Integration** | Limited (`--restart=always`) | Native Quadlet / systemd generators |

---

## 2. Prerequisites & Installation

```bash
# macOS via Homebrew
brew install podman

# Ubuntu / Debian
sudo apt update && sudo apt install -y podman
```

---

## 3. Podman Machine on macOS (Apple Silicon)

On macOS, Linux containers require a lightweight Linux hypervisor machine. Podman manages this automatically using Apple's native Hypervisor framework:

```bash
# Initialize Linux VM with 4 cores, 4GB RAM, and virtiofs shared disk
podman machine init --cpus 4 --memory 4096 --disk-size 50

# Start the machine
podman machine start

# Verify VM status
podman machine list

# SSH into the underlying VM
podman machine ssh
```

---

## 4. Running Rootless Containers

When running rootless, user IDs inside the container are mapped into a slice of subordinate user IDs (`/etc/subuid`) on the host. Even if an attacker escapes the container, they are completely unprivileged on the host system.

```bash
# Run an isolated NGINX web server on unprivileged port 8080
podman run -d --name my-web -p 8080:80 nginx:alpine

# Verify running process (note: PID exists directly in host process table!)
podman ps

# Stop and remove container
podman stop my-web
podman rm my-web
```

---

## 5. Podman Pods (Local Kubernetes Pods Architecture)

Similar to Kubernetes, a **Pod** in Podman groups multiple containers that share the same network namespace, IP address, and storage volumes.

```bash
# 1. Create a pod exposing port 8080
podman pod create --name web-pod -p 8080:80

# 2. Add an Nginx frontend container to the pod
podman run -d --pod web-pod --name nginx-frontend nginx:alpine

# 3. Add a backend container communicating over localhost inside the same pod
podman run -d --pod web-pod --name backend-api python:3.11-alpine python -m http.server 80

# 4. List pods and their child containers
podman pod ps
podman pod inspect web-pod
```

---

## 6. Kubernetes Manifest Integration (`play kube` & `generate kube`)

Podman allows you to run production Kubernetes YAML files locally without Minikube or Kind:

### Run Kubernetes Manifest Locally (`podman play kube`)

Create `app-stack.yaml`:

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: cache-stack
spec:
  containers:
  - name: redis-db
    image: redis:alpine
    ports:
    - containerPort: 6379
      hostPort: 6379
```

Run directly:

```bash
# Launch the Kubernetes manifest locally
podman play kube app-stack.yaml

# Teardown the pod stack
podman play kube --down app-stack.yaml
```

### Export Running Containers to Kubernetes YAML (`podman generate kube`)

```bash
# Generate valid Kubernetes YAML from an existing local container
podman generate kube my-web > k8s-deployment.yaml
```

---

## 7. Docker Drop-in Compatibility & Podman Compose

Podman supports exact Docker CLI syntax:

```bash
# Add alias to ~/.zshrc or ~/.bashrc
alias docker=podman

# Enable Docker socket emulation for tools expecting /var/run/docker.sock
podman system service --time=0 unix:///tmp/podman.sock &
export DOCKER_HOST="unix:///tmp/podman.sock"
```

### Multi-Container Orchestration with `podman-compose`

```bash
# Install podman-compose
brew install podman-compose   # macOS
pip install podman-compose    # Linux

# Run standard docker-compose.yml files
podman-compose up -d
podman-compose down
```

---

## 8. Companion Tools: Buildah & Skopeo

The container ecosystem is modular. Podman is paired with **Buildah** and **Skopeo**:

### Skopeo: Inspect & Copy Images Without Pulling

```bash
brew install skopeo

# Inspect remote image manifest and layers without downloading gigabytes of data
skopeo inspect docker://docker.io/library/ubuntu:latest

# Copy image directly between registries without local docker daemon
skopeo copy docker://docker.io/library/nginx:alpine docker://quay.io/myorg/nginx:alpine
```

### Buildah: Build OCI Images Without a Dockerfile

```bash
# Build layers using standard shell scripts
container=$(buildah from alpine:latest)
buildah run $container apk add --no-cache curl
buildah commit $container my-custom-alpine:1.0
```
