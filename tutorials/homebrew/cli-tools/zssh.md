# zssh: Interactive ssh with file transfer

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Beginner: Connect and transfer](#3-beginner-connect-and-transfer)
4. [Advanced: Keybindings and tips](#4-advanced-keybindings-and-tips)
5. [Troubleshooting](#5-troubleshooting)
6. [Uninstall](#6-uninstall)

-----

### 1. Overview

`zssh` wraps `ssh` providing a split interface to send files interactively (like `scp` within a session).

### 2. Install

```bash
brew install zssh
```

### 3. Beginner: Connect and transfer

```bash
zssh user@host
```

Inside session, use Ctrl+@ (or Meta+Ctrl+Z) to open local pane for file copy.

### 4. Advanced: Keybindings and tips

- Use `r` to receive and `s` to send files in zssh mode.
- Works best with standard terminals; ensure correct Meta key settings.

### 5. Troubleshooting

- If shortcuts fail, verify terminal sends the proper escape sequences.

### 6. Uninstall

```bash
brew uninstall zssh
```
