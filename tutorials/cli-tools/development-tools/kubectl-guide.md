# Kubernetes CLI (`kubectl`) Complete Administration & Troubleshooting Guide

`kubectl` is the primary command-line tool for orchestrating containerized workloads on Kubernetes clusters. It communicates with the `kube-apiserver` via declarative YAML manifests or imperative commands to deploy, scale, and debug microservices.

---

## 📚 Table of Contents

1. [Prerequisites & Kubeconfig Architecture](#1-prerequisites--kubeconfig-architecture)
2. [Cluster & Context Navigation (`kubectx` & `kubens`)](#2-cluster--context-navigation-kubectx--kubens)
3. [Core Resource Management (Pods, Deployments, Services)](#3-core-resource-management-pods-deployments-services)
4. [Debugging & Live Container Inspection](#4-debugging--live-container-inspection)
5. [JSONPath & Output Formatting Mastery](#5-jsonpath--output-formatting-mastery)
6. [Declarative Manifest Generation (`--dry-run=client`)](#6-declarative-manifest-generation---dry-runclient)
7. [Diagnosing Failed Pod States](#7-diagnosing-failed-pod-states)
8. [Everyday Production Cheat Sheet](#8-everyday-production-cheat-sheet)

---

## 1. Prerequisites & Kubeconfig Architecture

`kubectl` connects to clusters using a configuration file located at `~/.kube/config` (or referenced by the `$KUBECONFIG` environment variable).

### Installation

```bash
# macOS
brew install kubectl

# Linux (Debian/Ubuntu)
sudo apt update && sudo apt install -y apt-transport-https ca-certificates curl
curl -fsSL https://pkgs.k8s.io/core:/stable:/v1.30/deb/Release.key | sudo gpg --dearmor -o /etc/apt/keyrings/kubernetes-apt-keyring.gpg
echo 'deb [signed-by=/etc/apt/keyrings/kubernetes-apt-keyring.gpg] https://pkgs.k8s.io/core:/stable:/v1.30/deb/ /' | sudo tee /etc/apt/sources.list.d/kubernetes.list
sudo apt update && sudo apt install -y kubectl
```

### Shell Autocompletion

```bash
# Add to ~/.zshrc or ~/.bashrc
source <(kubectl completion zsh)
alias k=kubectl
complete -o default -F __start_kubectl k
```

---

## 2. Cluster & Context Navigation (`kubectx` & `kubens`)

Manage multiple staging, dev, and production environments seamlessly:

```bash
# View all configured contexts
kubectl config get-contexts

# Switch active context
kubectl config use-context production-cluster

# Set default namespace for current context
kubectl config set-context --current --namespace=backend-services

# Recommended CLI helpers
brew install kubectx
kubectx production-cluster
kubens backend-services
```

---

## 3. Core Resource Management

### Inspecting Resources

```bash
# Get pods with IP and node assignment
kubectl get pods -o wide

# Get all resources in current namespace
kubectl get all

# View resource usage across nodes and pods (requires metrics-server)
kubectl top nodes
kubectl top pods --sort-by=memory
```

### Scaling & Rolling Updates

```bash
# Scale deployment replicas
kubectl scale deployment api-gateway --replicas=5

# Trigger rolling restart
kubectl rollout restart deployment api-gateway

# Monitor rollout status
kubectl rollout status deployment api-gateway

# Roll back to previous revision
kubectl rollout undo deployment api-gateway
```

---

## 4. Debugging & Live Container Inspection

### Tail Logs

```bash
# Stream live logs from pod
kubectl logs -f pod-name

# Stream logs from previous crashed instance
kubectl logs pod-name --previous

# Stream logs from all pods with a given label selector
kubectl logs -f -l app=backend --tail=100
```

### Interactive Shell & Port Forwarding

```bash
# Spawn interactive bash session inside container
kubectl exec -it pod-name -- /bin/sh

# If pod has multiple containers, specify container name
kubectl exec -it pod-name -c sidecar-proxy -- /bin/sh

# Forward remote pod/service port directly to your localhost
kubectl port-forward svc/postgres-service 5432:5432
kubectl port-forward pod/api-7f89d-abc 8080:8080
```

---

## 5. JSONPath & Output Formatting Mastery

Extract exact fields from complex Kubernetes resources without external dependencies:

```bash
# Extract all container images running in the cluster
kubectl get pods -A -o jsonpath='{range .items[*]}{.spec.containers[*].image}{"\n"}{end}' | sort -u

# Get internal node IPs
kubectl get nodes -o jsonpath='{.items[*].status.addresses[?(@.type=="InternalIP")].address}'

# Custom tabular columns
kubectl get pods -o custom-columns=NAME:.metadata.name,NODE:.spec.nodeName,STATUS:.status.phase
```

---

## 6. Declarative Manifest Generation (`--dry-run=client`)

Never write boilerplate YAML from scratch. Use `--dry-run=client -o yaml` to generate baseline manifests:

```bash
# Generate a Deployment YAML manifest
kubectl create deployment web-app --image=nginx:alpine --port=80 --replicas=3 --dry-run=client -o yaml > deployment.yaml

# Generate a ClusterIP Service YAML manifest
kubectl expose deployment web-app --port=80 --target-port=80 --dry-run=client -o yaml > service.yaml

# Generate a ConfigMap from a local environment file
kubectl create configmap app-config --from-env-file=.env --dry-run=client -o yaml > configmap.yaml

# Apply manifests declaratively
kubectl apply -f deployment.yaml
kubectl apply -f service.yaml
```

---

## 7. Diagnosing Failed Pod States

When pods fail, use `kubectl describe pod <name>` to check the **Events** section at the bottom.

### 1. `CrashLoopBackOff`
- **Cause**: Application container process exits immediately with a non-zero exit code.
- **Fix**: Check `kubectl logs <pod> --previous` to view the fatal application traceback.

### 2. `ImagePullBackOff` / `ErrImagePull`
- **Cause**: Image tag not found on registry, or missing `imagePullSecrets` for private repositories.
- **Fix**: Verify repository URL and verify secret: `kubectl get secret docker-registry-cred`.

### 3. `OOMKilled` (Exit Code 137)
- **Cause**: Container exceeded its configured `resources.limits.memory` cap.
- **Fix**: Increase memory limits in deployment spec:
  ```yaml
  resources:
    limits:
      memory: "2Gi"
    requests:
      memory: "512Mi"
  ```

### 4. `Pending`
- **Cause**: Cluster has insufficient CPU/memory resources, or persistent volume claim (`PVC`) cannot bind to a storage class.
- **Fix**: Check `kubectl describe pod` for `0/8 nodes available: insufficient memory`.

---

## 8. Everyday Production Cheat Sheet

```bash
# Delete pod immediately (force kill without 30s grace period)
kubectl delete pod <pod-name> --grace-period=0 --force

# Create temporary ephemeral debug container in existing pod
kubectl debug -it <pod-name> --image=busybox --target=<container-name>

# Cordon and drain node for maintenance
kubectl cordon node-01
kubectl drain node-01 --ignore-daemonsets --delete-emptydir-data

# Uncordon node after maintenance
kubectl uncordon node-01

# Copy files to/from container
kubectl cp local-file.txt <pod-name>:/tmp/remote-file.txt
kubectl cp <pod-name>:/var/log/app.log ./local-app.log
```
