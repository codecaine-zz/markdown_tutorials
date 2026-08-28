# SSH Complete Hardening, Config Profiles & Jump Hosts Guide

`ssh` (Secure Shell) is the foundational tool for remote server administration, cloud infrastructure access, and secure data tunnels. While basic SSH connections (`ssh user@ip`) are common, configuring `~/.ssh/config`, modern elliptic-curve cryptography (**Ed25519**), connection multiplexing (**ControlMaster**), and multi-hop **ProxyJump** transforms remote administration into a fast, secure, and effortless experience.

---

## 📚 Table of Contents

1. [Overview & Modern SSH Cryptography](#overview--modern-ssh-cryptography)
2. [Generating Modern Ed25519 & Hardware Security Keys](#generating-modern-ed25519--hardware-security-keys)
3. [Mastering the `~/.ssh/config` File](#mastering-the-sshconfig-file)
4. [Bastion & Jump Hosts (`ProxyJump`)](#bastion--jump-hosts-proxyjump)
5. [Turbocharging Connection Speed with Multiplexing (`ControlMaster`)](#turbocharging-connection-speed-with-multiplexing-controlmaster)
6. [Local & Remote Port Forwarding Tunnels (`-L` / `-R`)](#local--remote-port-forwarding-tunnels--l---r)
7. [Server-Side SSH Hardening Best Practices (`sshd_config`)](#server-side-ssh-hardening-best-practices-sshd_config)
8. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)

---

## 🔍 Overview & Modern SSH Cryptography

Legacy SSH setups used RSA 2048/4096-bit keys and DSA. Modern best practices enforce **Ed25519** (Edwards-curve Digital Signature Algorithm), which provides:

- **Superior Security**: Robust resistance to side-channel attacks.
- **Ultra-Compact Keys**: 68-character public key strings (easy to copy/paste).
- **Fast Handshakes**: Substantially faster signature verification and connection initialization.

---

## 🔑 Generating Modern Ed25519 & Hardware Security Keys

### 1. Generate Standard Ed25519 Keypair

```bash
# Generate high-security Ed25519 key with 100 key-derivation rounds
ssh-keygen -t ed25519 -a 100 -C "username@macbook-pro" -f ~/.ssh/id_ed25519
```

### 2. Copy Public Key to Remote Server

```bash
# Upload public key using ssh-copy-id
ssh-copy-id -i ~/.ssh/id_ed25519.pub user@remote-server.com
```

### 3. Generate FIDO2 / YubiKey Hardware-Backed Keys (`-sk`)

If you use a physical FIDO2 hardware security key (e.g. YubiKey):

```bash
ssh-keygen -t ed25519-sk -O resident -C "yubikey-fido2"
```

---

## 🛠️ Mastering the `~/.ssh/config` File

Instead of typing `ssh -i ~/.ssh/prod_key.pem -p 2222 admin@192.168.1.100`, define clean aliases in `~/.ssh/config`:

```ssh
# Default settings for all hosts
Host *
    AddKeysToAgent yes
    UseKeychain yes
    IdentityFile ~/.ssh/id_ed25519
    ServerAliveInterval 60
    ServerAliveCountMax 3

# Production Server Profile
Host prod
    HostName 198.51.100.42
    User ubuntu
    Port 2222
    IdentityFile ~/.ssh/id_ed25519_prod

# Staging Server Profile
Host staging
    HostName staging.internal.company.com
    User deploy
    ForwardAgent yes
```

Now you can simply type:

```bash
ssh prod
ssh staging
```

---

## 🦘 Bastion & Jump Hosts (`ProxyJump`)

To access a private database or internal server behind a DMZ bastion host:

```ssh
# Bastion / Jump Server (Publicly accessible)
Host bastion
    HostName bastion.company.com
    User ec2-user
    IdentityFile ~/.ssh/bastion_key

# Internal Private Server (Only reachable through bastion)
Host internal-db
    HostName 10.0.15.200
    User postgres
    ProxyJump bastion
    IdentityFile ~/.ssh/db_key
```

Running `ssh internal-db` automatically routes traffic securely through `bastion` via end-to-end encrypted TCP tunneling.

---

## ⚡ Turbocharging Connection Speed with Multiplexing (`ControlMaster`)

Standard SSH establishes a brand new TCP connection, key exchange, and TLS authentication every time you run `ssh`, `scp`, or `rsync`. **Connection Multiplexing** reuses an already established socket, making subsequent connections instant (0.01s):

Add this to `~/.ssh/config`:

```ssh
Host *
    ControlMaster auto
    ControlPath ~/.ssh/sockets/%r@%h:%p
    ControlPersist 10m
```

Create the sockets directory:

```bash
mkdir -p ~/.ssh/sockets && chmod 700 ~/.ssh/sockets
```

---

## 🚇 Local & Remote Port Forwarding Tunnels

### 1. Local Port Forwarding (`-L`)

Forward a remote database port (e.g. remote MySQL on port 3306) to your local `localhost:3306`:

```bash
# Access remote database locally at localhost:3306
ssh -L 3306:localhost:3306 prod

# Forward in background without opening interactive shell (-N -f)
ssh -N -f -L 5432:localhost:5432 staging
```

### 2. Remote Port Forwarding (`-R`)

Expose a local web server (running at `localhost:3000`) to a remote public server port `8080`:

```bash
ssh -R 8080:localhost:3000 public-vps
```

---

## 🔒 Server-Side SSH Hardening Best Practices (`sshd_config`)

To lock down remote Linux servers, apply these hardening rules in `/etc/ssh/sshd_config`:

```ini
# Disable root login over SSH
PermitRootLogin no

# Disable password authentication (force SSH keys)
PasswordAuthentication no
ChallengeResponseAuthentication no
PubkeyAuthentication yes

# Disable empty passwords
PermitEmptyPasswords no

# Limit authentication attempts
MaxAuthTries 3

# Enforce secure modern ciphers only
KexAlgorithms curve25519-sha256,curve25519-sha256@libssh.org
Ciphers chacha20-poly1305@openssh.com,aes256-gcm@openssh.com
MACs hmac-sha2-512-etm@openssh.com
```

Restart the daemon after edits: `sudo systemctl restart sshd`.

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Generate Ed25519 Key** | `ssh-keygen -t ed25519 -C "<email>"` |
| **Upload Key to Server** | `ssh-copy-id -i ~/.ssh/id.pub <host>` |
| **Test Config Profile** | `ssh <alias>` |
| **Local Port Forward** | `ssh -L <local_port>:localhost:<remote_port> <host>` |
| **Multi-Hop Connection** | `ssh -J <jump_host> <target_host>` |
| **Execute Remote Command** | `ssh <host> "uptime && free -h"` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# SSH shortcuts
alias s='ssh'
alias sc='cat ~/.ssh/config'
alias keygen='ssh-keygen -t ed25519 -a 100'
alias ssh-tunnel='ssh -N -f -L'
```
