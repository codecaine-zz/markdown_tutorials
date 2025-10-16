# 📘 Official‑style **Redis** Guide for macOS (Homebrew — 2025 edition)

> **What’s new?**  
> The **Data‑type cheat‑sheet** now contains *all* core DML commands (SET/GET, LPUSH/LPOP, SADD/SMEMBERS, ZADD/ZRANGE, HSET/HGET, etc.) with a single‑line copy‑paste version and a direct link to the official Redis command reference.  

---  

## Table of Contents
1. [Prerequisites – Homebrew](#1‑prereqs)  
2. [Install Redis (brew)](#2‑install)  
3. [Where Homebrew puts Redis](#3‑locations)  
4. [Start / Stop / Restart the server (brew services)](#4‑service)  
5. [Interact with the server – `redis-cli`](#5‑cli)  
6. **[Redis command cheat‑sheet – “set & get” for every native data type](#6‑cheatsheet)** ← **copy‑paste ready**  
7. [Config file, `CONFIG REWRITE`, and runtime tweaks](#7‑config)  
8. [Persistence (RDB, AOF) & backups](#8‑persistence)  
9. [Security – passwords, ACLs, TLS](#9‑security)  
10. [Maintenance – `SAVE`, `BGSAVE`, `INFO`, `MEMORY USAGE`](#10‑maintenance)  
11. [Common mistakes & troubleshooting](#11‑mistakes)  
12. [Uninstall / clean‑up](#12‑uninstall)  

---  

<a name="1‑prereqs"></a>
## 1️⃣ Prerequisites – Homebrew  

```bash
brew --version            # should print 4.x+ (Homebrew >= 4)
# If missing → install it
 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Homebrew is the **officially supported** way to install Redis on macOS 【1†L5-L9】.  

*Official reference*: <https://brew.sh>  

---  

<a name="2‑install"></a>
## 2️⃣ Install Redis (brew)  

```bash
brew install redis          # installs the latest stable (redis 8.2.x as of 2025)
```

The formula page shows the exact command and current version 【15†L6-L13】.  

*Formula page*: <https://formulae.brew.sh/formula/redis>  

---  

<a name="3‑locations"></a>
## 3️⃣ Where Homebrew puts Redis  

| Item | Apple silicon (`/opt/homebrew`) | Intel (`/usr/local`) | How to obtain |
|------|--------------------------------|----------------------|---------------|
| Binary (`redis-server`, `redis-cli`) | `/opt/homebrew/opt/redis/bin/` | `/usr/local/opt/redis/bin/` | `$(brew --prefix redis)/bin` |
| Default config (`redis.conf`) | `$HOMEBREW_PREFIX/etc/redis.conf` | same | |
| Data directory (RDB/AOF) | `$HOMEBREW_PREFIX/var/db/redis/` | same | |
| Log file (launchd) | `$HOMEBREW_PREFIX/var/log/redis.log` | same | |
| Launch‑d plist | `$HOMEBREW_PREFIX/opt/redis/homebrew.mxcl.redis.plist` | same | |

Homebrew’s `install` step creates a **default data directory** (`var/db/redis`) 【15†L81-L85】.  

---  

<a name="4‑service"></a>
## 4️⃣ Start / Stop / Restart the server (brew services)

| Action | Command | Result |
|--------|---------|--------|
| **Foreground (debug)** | `redis-server $(brew --prefix redis)/etc/redis.conf` | Logs to console; stop with **Ctrl‑C** |
| **Launch‑d service** | `brew services start redis` | “Successfully started `redis`” |
| **Stop service** | `brew services stop redis` | Clean shutdown |
| **Restart (reload config)** | `brew services restart redis` | Stops then starts again |
| **Status** | `brew services list` | Shows `redis` → `started` / `stopped` |
| **Custom daemon** | `redis-server /path/to/custom.conf --daemonize yes` | Runs as a daemon |

---  

<a name="5‑cli"></a>
## 5️⃣ Interact with the server – `redis-cli`

```bash
# Connect to the default local instance (port 6379)
redis-cli

# One‑liner (useful in scripts)
redis-cli SET greeting "Hello, world!" && redis-cli GET greeting
```

Inside the REPL you can use the same command syntax as the wire protocol (see the full command reference 【16†L5-L13】).  

---  

<a name="6‑cheatsheet"></a>
## 6️⃣ **Redis command cheat‑sheet – “set & get” for every native data type**  

> All commands are ready‑to‑copy‑paste.  Every line links to the official Redis command reference.

### 6.1️⃣ Strings  

| Operation | Command | Docs |
|-----------|---------|------|
| **Set** | `redis-cli SET name "Alice"`【7†L5-L13】 | <https://redis.io/commands/set> |
| **Get** | `redis-cli GET name`【17†L4-L11】 | <https://redis.io/commands/get> |
| **Increment (numeric)** | `redis-cli INCR visits` | <https://redis.io/commands/incr> |
| **Append** | `redis-cli APPEND notes "more text"` | <https://redis.io/commands/append> |
| **Set with expiry** | `redis-cli SET session:123 "xyz" EX 300` | <https://redis.io/commands/set> |

### 6.2️⃣ Lists  

| Operation | Command | Docs |
|-----------|---------|------|
| **Push to head** | `redis-cli LPUSH mylist a b c`【18†L9-L13】 | <https://redis.io/commands/lpush> |
| **Push to tail** | `redis-cli RPUSH mylist x y` | <https://redis.io/commands/rpush> |
| **Pop from head** | `redis-cli LPOP mylist`【18†L9-L13】 | <https://redis.io/commands/lpop> |
| **Pop from tail** | `redis-cli RPOP mylist` | <https://redis.io/commands/rpop> |
| **Read range** | `redis-cli LRANGE mylist 0 -1`【18†L9-L13】 | <https://redis.io/commands/lrange> |
| **Index lookup** | `redis-cli LINDEX mylist 2` | <https://redis.io/commands/lindex> |
| **Trim** (keep a window) | `redis-cli LTRIM mylist 0 99` | <https://redis.io/commands/ltrim> |

### 6.3️⃣ Sets  

| Operation | Command | Docs |
|-----------|---------|------|
| **Add members** | `redis-cli SADD colors red blue`【19†L5-L13】 | <https://redis.io/commands/sadd> |
| **Read all members** | `redis-cli SMEMBERS colors` | <https://redis.io/commands/smembers> |
| **Check membership** | `redis-cli SISMEMBER colors red` | <https://redis.io/commands/sismember> |
| **Remove members** | `redis-cli SREM colors blue` | <https://redis.io/commands/srem> |
| **Random member** | `redis-cli SRANDMEMBER colors` | <https://redis.io/commands/srandmember> |
| **Cardinality** | `redis-cli SCARD colors` | <https://redis.io/commands/scard> |

### 6.4️⃣ Sorted Sets  

| Operation | Command | Docs |
|-----------|---------|------|
| **Add / update scores** | `redis-cli ZADD scores 100 player1 200 player2`【20†L9-L13】 | <https://redis.io/commands/zadd> |
| **Read by rank** | `redis-cli ZRANGE scores 0 -1 WITHSCORES` | <https://redis.io/commands/zrange> |
| **Read by score range** | `redis-cli ZRANGEBYSCORE scores 0 150 WITHSCORES` | <https://redis.io/commands/zrangebyscore> |
| **Get rank of a member** | `redis-cli ZRANK scores player2` | <https://redis.io/commands/zrank> |
| **Increment score** | `redis-cli ZINCRBY scores 10 player1` | <https://redis.io/commands/zincrby> |
| **Remove member** | `redis-cli ZREM scores player2` | <https://redis.io/commands/zrem> |

### 6.5️⃣ Hashes  

| Operation | Command | Docs |
|-----------|---------|------|
| **Set field(s)** | `redis-cli HSET user:1 name "Bob" age 30`【7†L5-L13】 | <https://redis.io/commands/hset> |
| **Get single field** | `redis-cli HGET user:1 name` | <https://redis.io/commands/hget> |
| **Get all fields** | `redis-cli HGETALL user:1` | <https://redis.io/commands/hgetall> |
| **Delete field** | `redis-cli HDEL user:1 age` | <https://redis.io/commands/hdel> |
| **Increment numeric field** | `redis-cli HINCRBY user:1 visits 1` | <https://redis.io/commands/hincrby> |
| **Check field existence** | `redis-cli HEXISTS user:1 name` | <https://redis.io/commands/hexists> |

### 6.6️⃣ Bitmaps  

| Operation | Command | Docs |
|-----------|---------|------|
| **Set a bit** | `redis-cli SETBIT bits 7 1`【7†L5-L13】 | <https://redis.io/commands/setbit> |
| **Read a bit** | `redis-cli GETBIT bits 7`【17†L4-L13】 | <https://redis.io/commands/getbit> |
| **Count set bits** | `redis-cli BITCOUNT bits` | <https://redis.io/commands/bitcount> |
| **Bitwise AND** | `redis-cli BITOP AND result bits1 bits2` | <https://redis.io/commands/bitop> |

### 6.7️⃣ HyperLogLog  

| Operation | Command | Docs |
|-----------|---------|------|
| **Add elements** | `redis-cli PFADD visitors ip1 ip2 ip3`【7†L5-L13】 | <https://redis.io/commands/pfadd> |
| **Get approximate count** | `redis-cli PFCOUNT visitors`【7†L5-L13】 | <https://redis.io/commands/pfcount> |
| **Merge HLLs** | `redis-cli PFMERGE unique visitors1 visitors2` | <https://redis.io/commands/pfmerge> |

### 6.8️⃣ Streams  

| Operation | Command | Docs |
|-----------|---------|------|
| **Append an entry** | `redis-cli XADD mystream * event login user-id 42`【7†L5-L13】 | <https://redis.io/commands/xadd> |
| **Read a range** | `redis-cli XRANGE mystream - +`【7†L5-L13】 | <https://redis.io/commands/xrange> |
| **Read last N** | `redis-cli XREVRANGE mystream + - COUNT 10`【7†L5-L13】 | <https://redis.io/commands/xrevrange> |
| **Trim stream** | `redis-cli XTRIM mystream MAXLEN 1000` | <https://redis.io/commands/xtrim> |
| **Consumer group** | `redis-cli XGROUP CREATE mystream mygroup $ MKSTREAM` | <https://redis.io/commands/xgroup> |
| **Read as consumer** | `redis-cli XREADGROUP GROUP mygroup consumer COUNT 10 STREAMS mystream >` | <https://redis.io/commands/xreadgroup> |

---  

<a name="7‑config"></a>
## 7️⃣ Config file, `CONFIG REWRITE`, and runtime tweaks  

*The server reads `redis.conf` from the path you pass to `redis-server`. Homebrew links a copy at* `$(brew --prefix)/etc/redis.conf`.  

**Reload a live change without restart**

```bash
redis-cli CONFIG SET maxmemory 1gb
redis-cli CONFIG SET maxmemory-policy allkeys-lru
```

**Persist changes back to `redis.conf`**  

```bash
redis-cli CONFIG REWRITE     # rewrites the file with minimal changes【21†L13-L19】
```

*Documentation*: <https://redis.io/commands/config> & <https://redis.io/commands/config-rewrite>  

---  

<a name="8‑persistence"></a>
## 8️⃣ Persistence (RDB / AOF) & backups  

| Mode | How to enable (via `redis.conf`) | Manual trigger | Docs |
|------|-----------------------------------|----------------|------|
| **RDB snapshot** (`dump.rdb`) | `save 900 1` (default) | `redis-cli SAVE` (blocking) or `redis-cli BGSAVE` (background) | <https://redis.io/topics/persistence#snapshotting> |
| **AOF (Append‑Only File)** | `appendonly yes` | `redis-cli BGREWRITEAOF` (compact) | <https://redis.io/topics/persistence#append-only-file> |
| **Combined** | enable both; AOF is the source of truth after a restart if `appendonly yes` | – | same |

---  

<a name="9‑security"></a>
## 9️⃣ Security – passwords, ACLs, TLS  

| Concern | Remedy | Docs |
|---------|--------|------|
| **Require a password** | Set `requirepass <strong‑pwd>` in `redis.conf` and restart | <https://redis.io/topics/security#authentication> |
| **Fine‑grained ACLs** | `ACL SETUSER alice on >password ~* +@all` | <https://redis.io/topics/acl> |
| **TLS encryption** | Install OpenSSL (brew provides `openssl@3`) and set `tls-port 6379`, `tls-cert-file …` | <https://redis.io/topics/encryption> |
| **Disable remote access** | In `redis.conf` set `bind 127.0.0.1` or firewall with `pfctl` | same |

---  

<a name="10‑maintenance"></a>
## 1️⃣0️⃣ Maintenance – monitoring & housekeeping  

| Task | Command | When to run | Docs |
|------|---------|-------------|------|
| **Info** (stats) | `redis-cli INFO` | Quick health check | <https://redis.io/commands/info> |
| **Memory usage** | `redis-cli MEMORY USAGE key` | Spot‑check large keys | <https://redis.io/commands/memory-usage> |
| **Latency monitoring** | `redis-cli LATENCY DOCTOR` | Diagnose latency spikes | <https://redis.io/commands/latency-doctor> |
| **Keyspace size** | `redis-cli DBSIZE` | Overview of total keys | <https://redis.io/commands/dbsize> |
| **Flush DB** | `redis-cli FLUSHDB` (or `FLUSHALL`) | Reset dev environment | <https://redis.io/commands/flushdb> |
| **Eviction policy tune** | `CONFIG SET maxmemory-policy allkeys-lru` | When memory is constrained | <https://redis.io/topics/lru-cache> |

---  

<a name="11‑mistakes"></a>
## ⚠️ 1️⃣1️⃣ Common pitfalls & troubleshooting  

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `Could not connect to Redis at 127.0.0.1:6379` | Server not started (`brew services list`) | `brew services start redis` |
| `NOAUTH Authentication required.` | `requirepass` set but client not providing password | `redis-cli -a <pwd>` or configure `auth` in your client |
| **Data loss after restart** | AOF disabled & `save` schedule not reached | Enable AOF (`appendonly yes`) or run `BGSAVE` before shutdown |
| **High memory, OOM kill** | No `maxmemory` limit | Add `maxmemory <bytes>` and a policy (`maxmemory-policy allkeys-lru`) |
| **Commands not found** (e.g., `LPUSH`) | Connected to a very old Redis version (pre‑2.0) | Ensure you installed the Homebrew version (8.2.1) and are connecting to the correct port |

*General troubleshooting*: <https://redis.io/topics/diagnostics>  

---  

<a name="12‑uninstall"></a>
## 1️⃣2️⃣ Uninstall / clean‑up  

```bash
# Stop the service first
brew services stop redis

# Remove the formula
brew uninstall redis

# Optional: delete the data directory (careful – this erases all data)
rm -rf $(brew --prefix)/var/db/redis
```

If you ever need to reinstall, just run `brew install redis` again – the data directory will be recreated automatically.  

*Uninstall docs*: <https://docs.brew.sh/Manpage#uninstall>  

---  

# 🎉 All set!  

You now have a **complete, copy‑paste‑ready reference** for every Redis data type on macOS, covering installation, file locations, service control, the full “set‑and‑get” cheat‑sheet, configuration, persistence, security and routine maintenance – all linked to the official Redis and Homebrew documentation. Happy caching! 🚀  