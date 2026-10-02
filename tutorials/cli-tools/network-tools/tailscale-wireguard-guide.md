# Tailscale & WireGuard Mesh Networking Guide

Modern developer workflows and homelabs frequently span multiple isolated locations (local laptops, cloud VMs, home servers, and remote offices). **WireGuard** provides high-speed, kernel-level cryptographic VPN tunneling, while **Tailscale** builds a zero-config, encrypted peer-to-peer **mesh overlay network** on top of WireGuard with automatic NAT traversal and MagicDNS.

---

## 📚 Table of Contents

1. [WireGuard vs. Tailscale: How They Relate](#1-wireguard-vs-tailscale-how-they-relate)
2. [Tailscale CLI Quickstart & Node Management](#2-tailscale-cli-quickstart--node-management)
3. [MagicDNS & Instant Machine Addressing](#3-magicdns--instant-machine-addressing)
4. [Subnet Routers: Accessing Private LANs](#4-subnet-routers-accessing-private-lans)
5. [Exit Nodes: Secure Full-Tunnel Internet Routing](#5-exit-nodes-secure-full-tunnel-internet-routing)
6. [Tailscale Serve & Funnel: Exposing Local Web Services](#6-tailscale-serve--funnel-exposing-local-web-services)
7. [Native WireGuard Configuration (`wg-quick`)](#7-native-wireguard-configuration-wg-quick)
8. [Troubleshooting & Connection Diagnostics](#8-troubleshooting--connection-diagnostics)

---

## 1. WireGuard vs. Tailscale: How They Relate

- **WireGuard**: A lean, high-performance VPN protocol (~4,000 lines of code) operating with ChaCha20-Poly1305 encryption. Requires manually exchanging static public keys and IP endpoints between peers.
- **Tailscale**: An automated control plane that configures WireGuard dynamically. It coordinates keys, punches through NATs and firewalls using STUN/ICE, falls back to encrypted DERP relays when direct connections fail, and provides automatic DNS.

---

## 2. Tailscale CLI Quickstart & Node Management

### Installation

```bash
# macOS
brew install tailscale

# Ubuntu / Debian
curl -fsSL https://tailscale.com/install.sh | sh
```

### Authenticating & Joining Your Tailnet

```bash
# Connect and open authentication URL in browser
sudo tailscale up

# Check status of all devices on your mesh network
tailscale status

# Print current node's 100.x.y.z Tailscale IPv4 and IPv6
tailscale ip -4
tailscale ip -6

# Ping a remote node directly across the mesh
tailscale ping server-01

# Disconnect / Reconnect
sudo tailscale down
sudo tailscale up
```

---

## 3. MagicDNS & Instant Machine Addressing

With MagicDNS enabled, every machine on your tailnet receives a stable human-readable domain name (e.g. `workstation.tailnet-name.ts.net`).

You can connect directly without memorizing dynamic IPs:

```bash
# SSH directly to another machine in your tailnet
ssh user@workstation

# Query a service running on another tailnet peer
curl http://nas-home:8080/api/health
```

---

## 4. Subnet Routers: Accessing Private LANs

A **Subnet Router** allows a single device inside a remote private network (e.g., a Raspberry Pi at home on `192.168.1.0/24`) to expose that entire subnet to all authorized devices on your tailnet.

### Step 1: Enable IP Forwarding on the Linux Router Device

```bash
echo 'net.ipv4.ip_forward = 1' | sudo tee -a /etc/sysctl.d/99-tailscale.conf
echo 'net.ipv6.conf.all.forwarding = 1' | sudo tee -a /etc/sysctl.d/99-tailscale.conf
sudo sysctl -p /etc/sysctl.d/99-tailscale.conf
```

### Step 2: Advertise the Subnet Route

```bash
sudo tailscale up --advertise-routes=192.168.1.0/24
```

### Step 3: Approve the Route

Log in to the [Tailscale Admin Console](https://login.tailscale.com/admin/machines), click on the machine's menu > **Edit route settings**, and check the box to approve `192.168.1.0/24`.

Now, your laptop can reach `192.168.1.50` from anywhere in the world!

---

## 5. Exit Nodes: Secure Full-Tunnel Internet Routing

Use a trusted home node or cloud VPS as an **Exit Node** to route all laptop web traffic through it when connected to untrusted public Wi-Fi.

### On the Exit Node (Linux Server):

```bash
sudo tailscale up --advertise-exit-node
```
*(Approve exit node in the Tailscale Admin Console)*

### On Your Client (Laptop):

```bash
# Route all traffic through the exit node
tailscale up --exit-node=my-vps-server

# Verify external IP now matches VPS
curl https://ipinfo.io/ip

# Disable exit node routing
tailscale up --exit-node=
```

---

## 6. Tailscale Serve & Funnel: Exposing Local Web Services

Share local development servers without ngrok or third-party port-forwarding tools:

### Tailscale Serve (Private to Tailnet)

Expose a local web app on port 3000 securely over HTTPS only to devices on your tailnet:

```bash
tailscale serve https / http://localhost:3000
```

### Tailscale Funnel (Public to Entire Internet)

Expose your local development server to the public internet with a valid, automated Let's Encrypt TLS certificate:

```bash
tailscale funnel 443 on
tailscale funnel status
```

---

## 7. Native WireGuard Configuration (`wg-quick`)

For standalone setups without third-party control planes:

### Generate Keypair

```bash
# Generate private and public keys
wg genkey | tee server_private.key | wg pubkey > server_public.key
chmod 600 server_private.key
```

### Server Configuration (`/etc/wireguard/wg0.conf`)

```ini
[Interface]
Address = 10.0.0.1/24
ListenPort = 51820
PrivateKey = <SERVER_PRIVATE_KEY>
PostUp = iptables -A FORWARD -i wg0 -j ACCEPT; iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
PostDown = iptables -D FORWARD -i wg0 -j ACCEPT; iptables -t nat -D POSTROUTING -o eth0 -j MASQUERADE

# Peer (Client Laptop)
[Peer]
PublicKey = <CLIENT_PUBLIC_KEY>
AllowedIPs = 10.0.0.2/32
```

### Managing the WireGuard Tunnel

```bash
# Start tunnel
sudo wg-quick up wg0

# Inspect active handshake and transfer bytes
sudo wg show

# Stop tunnel
sudo wg-quick down wg0
```

---

## 8. Troubleshooting & Connection Diagnostics

```bash
# Check if connections are direct (p2p) or relayed (DERP)
tailscale status

# Check latency to all global DERP relay regions
tailscale netcheck

# Check DNS configuration applied by Tailscale
tailscale debug dns
```
