# K9s Kubernetes Terminal UI Management Guide

`k9s` is a powerful, terminal-based user interface for managing and monitoring Kubernetes clusters. Written in Go, it replaces long, tedious `kubectl` commands with real-time cluster telemetry, instant log streaming, shell execution into containers, port forwarding, and interactive resource management.

---

## 📚 Table of Contents

1. [Overview & Why Use `k9s`?](#overview--why-use-k9s)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Launching `k9s` & Connecting to Clusters](#launching-k9s--connecting-to-clusters)
4. [Navigating Kubernetes Resources (`:pod`, `:svc`, `:deploy`, etc.)](#navigating-kubernetes-resources-pod-svc-deploy-etc)
5. [Viewing & Streaming Logs (`l`)](#viewing--streaming-logs-l)
6. [Interactive Shell Exec into Containers (`s`)](#interactive-shell-exec-into-containers-s)
7. [Port Forwarding Services & Pods (`Shift + F`)](#port-forwarding-services--pods-shift--f)
8. [Editing, Scaling & Restarting Deployments](#editing-scaling--restarting-deployments)
9. [Filtering by Namespaces & Search Filtering](#filtering-by-namespaces--search-filtering)
10. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
11. [Uninstallation](#uninstallation)

---

## 🔍 Overview & Why Use `k9s`?

Managing Kubernetes via `kubectl` often requires remembering long namespaces, pod hashes, and continuous repetitive typing (`kubectl get pods -n prod`, `kubectl logs -f pod-xyz -n prod -c main`). `k9s` provides instant keyboard-driven visual interaction across your entire cluster.

| Action | Traditional `kubectl` | `k9s` Shortcut |
| :--- | :--- | :--- |
| **View Pods** | `kubectl get pods -A` | Type `:pods` |
| **Stream Pod Logs** | `kubectl logs -f <pod> -c <container>` | Select pod and press `l` |
| **Exec Shell** | `kubectl exec -it <pod> -- sh` | Select pod and press `s` |
| **Port Forward** | `kubectl port-forward <pod> 8080:80` | Select pod and press `Shift + f` |
| **Scale Deployment** | `kubectl scale deploy <name> --replicas=3` | Select deploy and press `s` |
| **Describe Resource** | `kubectl describe <resource> <name>` | Select item and press `d` |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Prerequisites
Ensure you have a working `kubectl` setup and a valid `~/.kube/config` file connected to a Kubernetes cluster (Minikube, Kind, GKE, EKS, AKS, etc.).

### 2. Install via Homebrew

```bash
brew install k9s
```

### 3. Verify Installation

```bash
k9s version
```

---

## 🚀 Launching `k9s` & Connecting to Clusters

```bash
# Launch k9s with default context
k9s

# Launch targeting a specific namespace
k9s -n production

# Launch with a specific kubeconfig context
k9s --context my-cluster-admin

# Launch in read-only mode (prevents accidental modifications)
k9s --readonly
```

---

## 🧭 Navigating Kubernetes Resources

In `k9s`, press `:` (colon) to open the command bar, type the resource name or short alias, and press `Enter`:

### Common Resource Views:
- `:pods` or `:po` — Pods view
- `:deployments` or `:dp` — Deployments view
- `:services` or `:svc` — Services view
- `:nodes` or `:no` — Cluster nodes and CPU/Memory capacity
- `:configmaps` or `:cm` — ConfigMaps
- `:secrets` or `:sec` — Secrets (press `x` to decode secret values in TUI)
- `:ingresses` or `:ing` — Ingress routes
- `:namespaces` or `:ns` — Namespaces
- `:crds` — Custom Resource Definitions
- `:ctx` — Switch between different Kubernetes cluster contexts

---

## 📜 Viewing & Streaming Logs (`l`)

1. Highlight a pod in the `:pods` view.
2. Press `l` to open live log stream.
3. Use keys inside the log viewer:
   - `0` : Show all logs since pod creation
   - `1` : Last 1 minute
   - `2` : Last 5 minutes
   - `w` : Toggle word wrap
   - `t` : Toggle timestamps
   - `/` : Search within log lines
   - `Ctrl + c` : Save logs to a local file

---

## ⚡ Interactive Shell Exec into Containers (`s`)

1. Select a running pod and press `s`.
2. `k9s` automatically spawns an interactive shell inside the container (`/bin/sh` or `/bin/bash`).
3. If the pod has multiple containers, `k9s` prompts you to choose the target container.
4. Type `exit` to leave the container shell and return to `k9s`.

---

## 🌐 Port Forwarding Services & Pods (`Shift + F`)

1. Select a Pod or Service.
2. Press `Shift + f`.
3. Enter the local port and target container port.
4. View all active port-forward tunnels by typing `:portforwards` or `:pf`.

---

## 🎯 Filtering by Namespaces & Search Filtering

### 1. Filter by Namespace (Press `0` to `9`)
- `0` : All namespaces (`-A`)
- `1` : Default namespace
- `Shift + 1..9` : Custom configured namespaces

### 2. Search / Fuzzy Filter (`/`)
Press `/`, type the string to filter resources by name, and press `Enter`.

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Key | Action |
| :--- | :--- |
| `:` | Open resource command line (`:pod`, `:svc`, `:deploy`, `:ns`) |
| `/` | Filter/search active list |
| `l` | Stream logs for selected pod |
| `s` | Exec into container shell |
| `d` | Describe selected resource |
| `e` | Edit resource YAML in `$EDITOR` |
| `y` | View resource YAML |
| `Shift + f` | Set up port forwarding |
| `Ctrl + d` | Delete selected resource |
| `Ctrl + z` | Toggle error/warning pod filter |
| `?` | Show all available keyboard shortcuts |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# K9s shortcuts
alias k='k9s'
alias k-prod='k9s -n production'
alias k-ro='k9s --readonly'
```

---

## 🗑️ Uninstallation

To remove `k9s`:

```bash
brew uninstall k9s
```
