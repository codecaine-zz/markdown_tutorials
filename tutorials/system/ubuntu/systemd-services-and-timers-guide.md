# Systemd Services, Timers & Journalctl Administration Guide

`systemd` is the standard Linux init system and service manager. It provides parallel service startup, cgroup-based resource sandboxing, dependency orchestration, unified logging via `journald`, and declarative scheduled timers that replace legacy cron.

---

## 📚 Table of Contents

1. [Systemd Architecture & Unit Types](#1-systemd-architecture--unit-types)
2. [Writing Production Service Units (`.service`)](#2-writing-production-service-units-service)
3. [Sandboxing & Hardening Services with Cgroups](#3-sandboxing--hardening-services-with-cgroups)
4. [Systemd Timers: The Modern Cron Replacement (`.timer`)](#4-systemd-timers-the-modern-cron-replacement-timer)
5. [User Services (`systemctl --user`) Without Sudo](#5-user-services-systemctl---user-without-sudo)
6. [Inspecting & Troubleshooting Logs with `journalctl`](#6-inspecting--troubleshooting-logs-with-journalctl)
7. [Analyzing Boot Performance (`systemd-analyze`)](#7-analyzing-boot-performance-systemd-analyze)

---

## 1. Systemd Architecture & Unit Types

Systemd manages resources through declarative configuration files called **Units**.

| Unit Extension | Purpose | Example |
| :--- | :--- | :--- |
| `.service` | Manages daemons and applications | `docker.service`, `nginx.service` |
| `.timer` | Triggers services based on time/events | `logrotate.timer`, `backup.timer` |
| `.socket` | IPC or network socket activation | `docker.socket`, `sshd.socket` |
| `.mount` | Filesystem mount point management | `/mnt/data.mount` |
| `.target` | Synchronization group/runlevel | `multi-user.target`, `graphical.target` |

### Unit File Storage Locations

- `/lib/systemd/system/`: Package-installed default units (do not edit directly).
- `/etc/systemd/system/`: Administrator-created or override units (highest priority).
- `~/.config/systemd/user/`: User-specific units running without root privileges.

---

## 2. Writing Production Service Units (`.service`)

Create an enterprise-ready daemon unit for a backend API server:

`/etc/systemd/system/web-api.service`

```ini
[Unit]
Description=Backend Golang Web API Daemon
Documentation=https://api.internal/docs
After=network.target postgresql.service
Wants=postgresql.service

[Service]
Type=simple
User=appuser
Group=appuser
WorkingDirectory=/opt/web-api
ExecStart=/opt/web-api/server --port=8080 --env=production
ExecReload=/bin/kill -HUP $MAINPID

# Restart behavior
Restart=always
RestartSec=5s
StartLimitIntervalSec=60s
StartLimitBurst=3

# Resource and environment limits
Environment="PORT=8080"
EnvironmentFile=-/etc/default/web-api
LimitNOFILE=65535

[Install]
WantedBy=multi-user.target
```

### Service Lifecycle Commands

```bash
# Reload systemd manager configuration after editing unit files
sudo systemctl daemon-reload

# Start and enable service to launch automatically on boot
sudo systemctl enable --now web-api.service

# Check live status, PID, memory usage, and recent output
sudo systemctl status web-api.service

# Restart or stop
sudo systemctl restart web-api.service
sudo systemctl stop web-api.service
```

---

## 3. Sandboxing & Hardening Services with Cgroups

systemd allows declarative security sandboxing directly in the `[Service]` block without external container tools:

```ini
[Service]
# Prevent process from obtaining root privileges via setuid
NoNewPrivileges=true

# Restrict filesystem access
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=/opt/web-api/data /tmp

# Private /tmp directory isolated from other processes
PrivateTmp=true

# Restrict networking (e.g. only IPv4 and IPv6)
RestrictAddressFamilies=AF_INET AF_INET6 AF_UNIX

# Memory and CPU resource caps
MemoryMax=1G
CPUQuota=150%
```

---

## 4. Systemd Timers: The Modern Cron Replacement (`.timer`)

Timers consist of two paired files: a target `.service` that executes the work, and a `.timer` that dictates the schedule.

### Step 1: The Worker Service (`/etc/systemd/system/db-backup.service`)

```ini
[Unit]
Description=Perform Nightly Database Backup

[Service]
Type=oneshot
ExecStart=/usr/local/bin/backup-script.sh
```

### Step 2: The Timer Unit (`/etc/systemd/system/db-backup.timer`)

```ini
[Unit]
Description=Trigger Nightly Database Backup
Requires=db-backup.service

[Timer]
# Run every day at 02:30 UTC
OnCalendar=*-*-* 02:30:00

# Run 15 minutes after system boots up
OnBootSec=15min

# If machine was turned off when scheduled, run immediately upon next boot
Persistent=true

# Introduce randomized delay (jitter) to prevent thundering herd across a fleet
RandomizedDelaySec=5m

[Install]
WantedBy=timers.target
```

### Managing Timers

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now db-backup.timer

# List all active timers, next run times, and previous run status
systemctl list-timers
```

---

## 5. User Services (`systemctl --user`) Without Sudo

Developers can run background user daemons (file watchers, bot listeners, personal proxies) that start automatically when their user session initializes.

```bash
# Create user systemd directory
mkdir -p ~/.config/systemd/user

# Create user service
cat << 'EOF' > ~/.config/systemd/user/discord-bot.service
[Unit]
Description=Discord Utility Bot

[Service]
ExecStart=/home/ubuntu/.local/bin/bot-daemon
Restart=on-failure

[Install]
WantedBy=default.target
EOF

# Reload and manage user service (no sudo!)
systemctl --user daemon-reload
systemctl --user enable --now discord-bot.service
systemctl --user status discord-bot.service

# Allow user services to run even when user is NOT logged in (Linger)
sudo loginctl enable-linger $USER
```

---

## 6. Inspecting & Troubleshooting Logs with `journalctl`

`journalctl` indexes structured metadata for all processes managed by systemd.

```bash
# Follow live logs for a specific service in real-time
journalctl -u web-api.service -f

# Show logs from the current system boot only
journalctl -b

# Filter logs by time window
journalctl -u web-api.service --since "1 hour ago"
journalctl --since "2026-10-02 00:00:00" --until "2026-10-02 12:00:00"

# Show only errors and critical alerts (severity level err or higher)
journalctl -p err..emerg

# Inspect last 100 lines with full explanations for failures
journalctl -xeu web-api.service -n 100

# Check disk space used by system logs
journalctl --disk-usage

# Vacuum / prune log journal to max 500MB
sudo journalctl --vacuum-size=500M
```

---

## 7. Analyzing Boot Performance (`systemd-analyze`)

Optimize boot times and locate sluggish initialization scripts:

```bash
# Overall kernel and userspace boot duration
systemd-analyze

# List services ranked by startup time (find bottlenecks)
systemd-analyze blame

# Print visual critical-chain dependency graph
systemd-analyze critical-chain
```
