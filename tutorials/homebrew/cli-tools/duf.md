# duf: Disk Usage/Free Viewer

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Beginner: Basic views](#3-beginner-basic-views)
4. [Intermediate: Filtering and sorting](#4-intermediate-filtering-and-sorting)
5. [Advanced: JSON output, scripting, and TUI tips](#5-advanced-json-output-scripting-and-tui-tips)
6. [Compare with df](#6-compare-with-df)
7. [Uninstall](#7-uninstall)

-----

### 1\. Overview

`duf` is a modern, colorful replacement for `df -h`. It shows mounted filesystems and usage with optional JSON output for automation.

### 2\. Install

```bash
brew install duf
```

Check version:

```bash
duf --version
```

Example output:

```text
duf 0.8.1
```

### 3\. Beginner: Basic views

Human-friendly summary:

```bash
duf
```

Sample output:

```text
╭────────────────────────────────────────────────────────────────────────────╮
│ 1 local device                                                             │
│ Filesystem   Type  Size  Used  Avail  Use%  Mounted on                     │
│ /dev/disk3s5 apfs  466G   75G   391G   16%  /                              │
╰────────────────────────────────────────────────────────────────────────────╯
```

Only local disks:

```bash
duf --only local
```

### 4\. Intermediate: Filtering and sorting

- Exclude virtual and small mounts:

```bash
duf --hide-fs=autofs,devfs,overlay --min-size=1G
```

- Show network mounts only:

```bash
duf --only network
```

- Sort by usage descending:

```bash
duf --sort usage
```

### 5\. Advanced: JSON output, scripting, and TUI tips

Machine-readable JSON:

```bash
duf --json
```

Example JSON snippet:

```json
{
  "mountpoints": [
    {
      "filesystem": "/dev/disk3s5",
      "mountpoint": "/",
      "size": 500107862016,
      "used": 80425852928,
      "available": 419682009088,
      "usage": 0.16,
      "type": "apfs"
    }
  ]
}
```

Use JSON in scripts (jq required):

```bash
duf --json | jq -r '.mountpoints[] | select(.usage > 0.8) | "\(.mountpoint) \(.usage*100|floor)%"'
```

TUI tips:

- Resize terminal for better tables.
- Use `--theme=light` or `--theme=dark` to match your terminal.

### 6\. Compare with df

`df -h` rough equivalent:

```bash
df -h
duf
```

`duf` provides clearer columns, filtering, and JSON output out of the box.

### 7\. Uninstall

```bash
brew uninstall duf
```
