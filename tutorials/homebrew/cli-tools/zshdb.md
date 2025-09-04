# zshdb: Debugger for zsh scripts

## Table of Contents

1. [Overview](#1-overview)
2. [Install](#2-install)
3. [Beginner: Run a script with zshdb](#3-beginner-run-a-script-with-zshdb)
4. [Intermediate: Breakpoints and stepping](#4-intermediate-breakpoints-and-stepping)
5. [Advanced: Watches and conditional breaks](#5-advanced-watches-and-conditional-breaks)
6. [Troubleshooting](#6-troubleshooting)
7. [Uninstall](#7-uninstall)

-----

### 1. Overview

`zshdb` is an interactive debugger for zsh. Set breakpoints, step through code, and inspect variables.

### 2. Install

```bash
brew install zshdb
```

### 3. Beginner: Run a script with zshdb

Create `demo.zsh`:

```zsh
#!/usr/bin/env zsh
x=1
y=$((x+1))
echo "x=$x y=$y"
```

Run:

```bash
zshdb demo.zsh
```

Output excerpt:

```text
(zshdb) run
x=1 y=2
(zshdb) quit
```

### 4. Intermediate: Breakpoints and stepping

```text
(zshdb) break 3
(zshdb) run
(zshdb) next
(zshdb) step
(zshdb) print $x $y
```

### 5. Advanced: Watches and conditional breaks

```text
(zshdb) watch y
(zshdb) condition 1 y -gt 5
```

### 6. Troubleshooting

- Ensure scripts start with `#!/usr/bin/env zsh`.
- Use absolute paths when debugging from other dirs.

### 7. Uninstall

```bash
brew uninstall zshdb
```
