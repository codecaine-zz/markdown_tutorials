# Croc Secure CLI File Transfer Guide

`croc` is an easy, fast, and secure command-line tool written in Go that allows any two computers to transfer files and folders over the internet or local networks. Utilizing Password-Authenticated Key Exchange (PAKE) and end-to-end encryption (PAKE-authenticated AES-256), `croc` allows sharing files with human-readable codephrases without port forwarding or complex SSH key setups.

---

## 📚 Table of Contents

1. [Overview & `scp` / `rsync` vs `croc` Comparison](#overview--scp--rsync-vs-croc-comparison)
2. [Prerequisites & Homebrew Installation](#prerequisites--homebrew-installation)
3. [Sending Files & Generating Codephrases (`croc send`)](#sending-files--generating-codephrases-croc-send)
4. [Receiving Files with Codephrases](#receiving-files-with-codephrases)
5. [Custom Codephrases & Password Protection](#custom-codephrases--password-protection)
6. [Sending Folders & Multiple Files](#sending-folders--multiple-files)
7. [Local-Only Transfers (LAN Mode) & Self-Hosted Relays](#local-only-transfers-lan-mode--self-hosted-relays)
8. [Sending Raw Text & Clipboard Snippets](#sending-raw-text--clipboard-snippets)
9. [Everyday Cheat Sheet & Useful Shell Aliases](#everyday-cheat-sheet--useful-shell-aliases)
10. [Uninstallation](#uninstallation)

---

## 🔍 Overview & `scp` / `rsync` vs `croc` Comparison

Transferring files between computers with traditional tools like `scp`, `rsync`, or FTP requires public IP addresses, router port forwarding, or pre-shared SSH keys. `croc` connects peers automatically across NATs and firewalls via secure encrypted relays.

| Feature | `scp` / `rsync` | `croc` |
| :--- | :--- | :--- |
| **Firewall / NAT Traversal** | Requires open ports / port forwarding | Automatic peer-to-peer / relay |
| **Authentication** | SSH keys or server passwords | Human-readable ephemeral codephrase |
| **End-to-End Encryption** | TLS / SSH tunnel | PAKE-authenticated AES-GCM (256-bit) |
| **Resumable Transfers** | `rsync` only | Built-in automatic resume |
| **Local Network (LAN) Speed** | Manual IP connection | Automatic local peer discovery |

---

## ⚙️ Prerequisites & Homebrew Installation

### 1. Install via Homebrew (macOS & Linux)

```bash
brew install croc
```

### 2. Install via Go

```bash
go install github.com/schollz/croc/v9@latest
```

### 3. Verify Installation

```bash
croc --version
```

---

## 🚀 Sending Files & Generating Codephrases (`croc send`)

To send a file, run `croc send` (or simply `croc`):

```bash
# Send a file
croc send dataset.zip
```

### Terminal Output:

```text
Sending 'dataset.zip' (450 MB)
Code is: 7214-matrix-orbit-jupiter
On the other computer run:

croc 7214-matrix-orbit-jupiter
```

`croc` generates a unique, memorable codephrase and waits for the recipient computer to connect.

---

## 📥 Receiving Files with Codephrases

On the receiving machine, simply run `croc` followed by the codephrase:

```bash
croc 7214-matrix-orbit-jupiter
```

### Recipient Prompt:

```text
Receiving 'dataset.zip' (450 MB)
Accept? (Y/n) y
  450 MB / 450 MB [==============================] 100.00% 48 MB/s 9s
```

Type `y` and press `Enter`. The file transfers directly with live progress meters and checksum verification.

---

## 🔑 Custom Codephrases & Password Protection

### 1. Specify a Custom Codephrase (`--code`)

```bash
# Send with a custom memorable codephrase
croc send --code "super-secret-release-v2" release_bundle.tar.gz
```

### 2. Automatically Accept Transfers (`--yes`)

Skip the interactive confirmation prompt on automated servers:

```bash
croc --yes 7214-matrix-orbit-jupiter
```

---

## 📁 Sending Folders & Multiple Files

`croc` transfers entire folder trees recursively without requiring prior `zip` or `tar` archiving:

```bash
# Send a directory
croc send ./my-project/

# Send multiple files at once
croc send document.pdf schema.sql logo.png
```

---

## 🏠 Local-Only Transfers (LAN Mode) & Self-Hosted Relays

### 1. Local Network Only (`--local`)

Force transfers over the local Wi-Fi / Ethernet subnet to achieve maximum LAN speeds (up to 1+ Gbps) without routing through public relay servers:

```bash
croc --local send large-video.mp4
```

### 2. Run Your Own Private Relay Server

For sensitive corporate environments, host your own relay:

```bash
# Start private croc relay server on port 9009
croc relay --port 9009

# Use your private relay on sender and receiver
croc --relay "my-relay.internal.net:9009" send confidential.docx
```

---

## 📝 Sending Raw Text & Clipboard Snippets

Send text strings or credentials without creating temporary files:

```bash
# Send text message or token
croc send --text "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5..."
```

---

## 📋 Everyday Cheat Sheet & Useful Shell Aliases

### Quick Reference Table

| Task | Command |
| :--- | :--- |
| **Send single file** | `croc send <file>` |
| **Send folder** | `croc send <folder>/` |
| **Send with custom code** | `croc send --code "<phrase>" <file>` |
| **Send text string** | `croc send --text "<message>"` |
| **Receive file** | `croc <codephrase>` |
| **Receive auto-accept** | `croc --yes <codephrase>` |
| **Local network only** | `croc --local send <file>` |

### Recommended Aliases (Add to `~/.zshrc` or `~/.bashrc`)

```bash
# Croc shortcuts
alias send='croc send'
alias receive='croc'
alias send-local='croc --local send'
```

---

## 🗑️ Uninstallation

To remove `croc`:

```bash
brew uninstall croc
```
