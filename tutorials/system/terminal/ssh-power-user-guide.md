# SSH Power User & Tunneling Guide

Secure Shell (`SSH`) is the foundational protocol for remote systems administration, cloud infrastructure access, and secure data tunnels. Beyond standard passwordless logins, mastering SSH configuration, socket multiplexing, dynamic tunneling, and jump hosts drastically accelerates remote workflows.

---

## 📚 Table of Contents

1. [Modern Key Generation: Ed25519 vs. RSA](#1-modern-key-generation-ed25519-vs-rsa)
2. [Advanced Client Configuration (`~/.ssh/config`)](#2-advanced-client-configuration-sshconfig)
3. [Connection Multiplexing (Zero-Delay Subsequent Logins)](#3-connection-multiplexing-zero-delay-subsequent-logins)
4. [Bastion / Jump Hosts (`ProxyJump`)](#4-bastion--jump-hosts-proxyjump)
5. [SSH Port Forwarding & Tunneling](#5-ssh-port-forwarding--tunneling)
   - [Local Port Forwarding (`-L`)](#local-port-forwarding--l)
   - [Remote / Reverse Port Forwarding (`-R`)](#remote--reverse-port-forwarding--r)
   - [Dynamic SOCKS5 Proxy (`-D`)](#dynamic-socks5-proxy--d)
6. [macOS Keychain Integration & Agent Forwarding](#6-macos-keychain-integration--agent-forwarding)
7. [SSH Escape Characters & Session Control](#7-ssh-escape-characters--session-control)
8. [Debugging & Troubleshooting SSH](#8-debugging--troubleshooting-ssh)

---

## 1. Modern Key Generation: Ed25519 vs. RSA

Legacy RSA keys (2048/4096-bit) are slower and require larger key sizes compared to modern elliptic-curve cryptography (**Ed25519**).

```bash
# Generate high-security Ed25519 key pair with custom comment and key derivation iterations
ssh-keygen -t ed25519 -a 100 -C "dev@workstation" -f ~/.ssh/id_ed25519

# Secure file permissions (critical: SSH will refuse keys with open permissions)
chmod 700 ~/.ssh
chmod 600 ~/.ssh/id_ed25519
chmod 644 ~/.ssh/id_ed25519.pub

# Copy public key to remote host
ssh-copy-id -i ~/.ssh/id_ed25519.pub user@remote-host.com
```

---

## 2. Advanced Client Configuration (`~/.ssh/config`)

Eliminate typing full hostnames, ports, and usernames by writing structured configuration blocks.

```ini
# Global defaults applied to all hosts
Host *
    AddKeysToAgent yes
    IdentitiesOnly yes
    ServerAliveInterval 60
    ServerAliveCountMax 3
    Compression yes

# Production Database Server (Behind Bastion)
Host db-prod
    HostName 10.0.1.50
    User ubuntu
    IdentityFile ~/.ssh/id_ed25519
    ProxyJump bastion-gateway

# Bastion / Jump Host
Host bastion-gateway
    HostName bastion.company.com
    Port 2222
    User admin
    IdentityFile ~/.ssh/id_ed25519

# Personal Raspberry Pi / Home Lab
Host pi-lab
    HostName 192.168.1.120
    User pi
    ForwardAgent no
```

With this config, connecting to the isolated internal database server requires only:

```bash
ssh db-prod
```

---

## 3. Connection Multiplexing (Zero-Delay Subsequent Logins)

SSH multiplexing reuses a single established TCP connection for multiple parallel sessions, file transfers (`scp`/`rsync`), and Git operations, dropping connection latency from seconds to milliseconds.

Add this block to your `~/.ssh/config`:

```ini
Host *
    ControlMaster auto
    ControlPath ~/.ssh/sockets/%r@%h:%p.socket
    ControlPersist 10m
```

Create the sockets directory:

```bash
mkdir -p ~/.ssh/sockets && chmod 700 ~/.ssh/sockets
```

### Managing Active Multiplexed Sockets

```bash
# Check if a multiplex master connection is active
ssh -O check user@remote-host

# Gracefully stop reusing connection (existing sessions remain open)
ssh -O stop user@remote-host

# Immediately terminate master connection and all child sessions
ssh -O exit user@remote-host
```

---

## 4. Bastion / Jump Hosts (`ProxyJump`)

When internal servers are located inside a private Virtual Private Cloud (VPC), access them transparently without logging into the intermediate bastion manually.

```bash
# Ad-hoc CLI jump command
ssh -J user@bastion.example.com target-user@10.0.0.15

# Chaining multiple jump hosts
ssh -J jump1.com,jump2.com internal-node.internal
```

---

## 5. SSH Port Forwarding & Tunneling

### Local Port Forwarding (`-L`)

Forward a local port on your laptop through the SSH tunnel to access a remote service (e.g., remote Redis or PostgreSQL database).

```bash
# Syntax: ssh -L [LOCAL_IP:]LOCAL_PORT:DESTINATION_HOST:DESTINATION_PORT USER@REMOTE_SERVER
ssh -N -L 5432:localhost:5432 user@remote-db-server.com
```

Now connect locally to `localhost:5432` as if PostgreSQL were running on your machine.

### Remote / Reverse Port Forwarding (`-R`)

Expose a local development web server (e.g., running on `localhost:3000`) to a public server so external clients can test it.

```bash
# Syntax: ssh -R REMOTE_PORT:LOCAL_HOST:LOCAL_PORT USER@PUBLIC_SERVER
ssh -N -R 8080:localhost:3000 user@public-vps.com
```

> [!NOTE]
> For remote ports to bind to `0.0.0.0` (accessible to the internet), set `GatewayPorts yes` in `/etc/ssh/sshd_config` on the remote server.

### Dynamic SOCKS5 Proxy (`-D`)

Turn any remote SSH server into an instant encrypted SOCKS5 proxy to route web traffic through it.

```bash
# Open local SOCKS5 proxy on port 1080
ssh -D 1080 -C -q -N user@remote-server.com
```

Configure your browser or CLI tool to use `socks5h://127.0.0.1:1080`:

```bash
curl --socks5-hostname 127.0.0.1:1080 https://ipinfo.io/ip
```

---

## 6. macOS Keychain Integration & Agent Forwarding

On macOS, configure SSH to automatically load keys into the native system keychain without re-prompting for passphrases on reboot:

```ini
Host *
    UseKeychain yes
    AddKeysToAgent yes
    IdentityFile ~/.ssh/id_ed25519
```

Load your key once into the macOS Keychain:

```bash
ssh-add --apple-use-keychain ~/.ssh/id_ed25519
```

> [!CAUTION]
> Avoid enabling `ForwardAgent yes` globally. Malicious root users on compromised remote servers can access your forwarded agent socket. Only forward keys to trusted bastions using `ForwardAgent yes` inside specific `Host` blocks.

---

## 7. SSH Escape Characters & Session Control

When an SSH session freezes or becomes unresponsive, use **SSH Escape Sequences**. Type newline followed by `~` and the command key:

| Escape Key | Action | Description |
| :--- | :--- | :--- |
| `Enter` + `~.` | **Kill Session** | Instantly closes frozen or hung connection |
| `Enter` + `~^Z` | **Background SSH** | Puts SSH into background; return with `fg` |
| `Enter` + `~#` | **List Channels** | Lists active multiplexed port forwards |
| `Enter` + `~?` | **Help Menu** | Shows available escape characters |

---

## 8. Debugging & Troubleshooting SSH

When SSH fails to connect or authentication is rejected:

```bash
# Level 1 verbose output: shows negotiation and selected identity file
ssh -v user@host.com

# Level 2 verbose: detailed protocol exchanges and key algorithms
ssh -vv user@host.com

# Level 3 verbose: full cryptographic debug trace
ssh -vvv user@host.com
```

### Common Fixes for "Permission Denied (publickey)"

```bash
# 1. Verify remote authorized_keys permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

# 2. Check if identity was offered
ssh -o PubkeyAuthentication=yes -i ~/.ssh/id_ed25519 user@host.com
```
