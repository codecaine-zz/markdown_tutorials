# Redis: In-Memory Key-Value Data Store CLI Guide

## Table of Contents

1. [What is `redis`?](#1-what-is-redis)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Service Management (`brew services`)](#4-service-management-brew-services)
5. [Connecting with `redis-cli`](#5-connecting-with-redis-cli)
6. [Core & Advanced Data Types](#6-core--advanced-data-types)
7. [Key Expiration & TTL (Time-To-Live)](#7-key-expiration--ttl-time-to-live)
8. [Transactions, Pipelines & Streams](#8-transactions-pipelines--streams)
9. [Pub/Sub Messaging Features](#9-pubsub-messaging-features)
10. [Terminal Scripting & Shell One-Liners](#10-terminal-scripting--shell-one-liners)
11. [Server Monitoring & Benchmarking](#11-server-monitoring--benchmarking)
12. [Uninstallation](#12-uninstallation)

---

### 1. What is `redis`?

`redis` (Remote Dictionary Server) is an open-source, in-memory data structure store used as a database, cache, message broker, and streaming engine. The `redis-cli` tool provides a command-line interface to interact directly with local or remote Redis server instances.

#### Common Use Cases
* **High-Speed Caching:** Caching database query results with TTL expiration.
* **Session Storage:** Fast web session management across cluster nodes.
* **Real-time Messaging & Streaming:** Pub/Sub message channels, job queues, and stream processing.
* **Leaderboards & Analytics:** Real-time ranking using Sorted Sets and HyperLogLog counters.

---

### 2. Prerequisites

Verify Homebrew on your Apple Silicon Mac:

```bash
brew --version
```

---

### 3. Installation on ARM macOS

Install Redis server and client via Homebrew:

```bash
brew install redis
```

Verify binary installation:

```bash
which redis-cli
redis-cli --version
```

**Expected Output:**
```text
/opt/homebrew/bin/redis-cli
redis-cli 7.x.x (or latest)
```

---

### 4. Service Management (`brew services`)

Manage the Redis server daemon using `brew services`:

#### Start Redis Background Service
```bash
brew services start redis
```

#### Check Service Status
```bash
brew services list | grep redis
```

#### Stop or Restart Service
```bash
brew services stop redis
brew services restart redis
```

---

### 5. Connecting with `redis-cli`

Launch `redis-cli` to connect to local Redis running on port 6379:

```bash
redis-cli
```

#### Connect to Custom Host/Port or Password
```bash
redis-cli -h 127.0.0.1 -p 6379 -a "your_password"
```

#### Test Server Ping
Inside the prompt (`127.0.0.1:6379>`):

```text
127.0.0.1:6379> PING
PONG
```

---

### 6. Core & Advanced Data Types

#### 1. Strings (Simple Key-Value & Counters)
```text
127.0.0.1:6379> SET user:100:name "Alice"
OK
127.0.0.1:6379> GET user:100:name
"Alice"
127.0.0.1:6379> INCR page_views
(integer) 1
127.0.0.1:6379> INCRBY page_views 10
(integer) 11
```

#### 2. Hashes (Object Structures)
```text
127.0.0.1:6379> HSET user:100 email "alice@example.com" age 28 role "admin"
(integer) 3
127.0.0.1:6379> HGET user:100 email
"alice@example.com"
127.0.0.1:6379> HGETALL user:100
1) "email"
2) "alice@example.com"
3) "age"
4) "28"
5) "role"
6) "admin"
```

#### 3. Lists (Ordered Sequences & Job Queues)
```text
127.0.0.1:6379> LPUSH job_queue "task_email_1"
(integer) 1
127.0.0.1:6379> LPUSH job_queue "task_email_2"
(integer) 2
127.0.0.1:6379> RPOP job_queue
"task_email_1"
127.0.0.1:6379> LRANGE job_queue 0 -1
1) "task_email_2"
```

#### 4. Sets (Unique Unordered Collections)
```text
127.0.0.1:6379> SADD tags "database" "cli" "macos" "cli"
(integer) 3
127.0.0.1:6379> SMEMBERS tags
1) "database"
2) "cli"
3) "macos"
127.0.0.1:6379> SISMEMBER tags "cli"
(integer) 1
```

#### 5. Sorted Sets (Ranked Lists / Leaderboards)
```text
127.0.0.1:6379> ZADD leaderboard 2500 "PlayerOne" 3100 "PlayerTwo" 1800 "PlayerThree"
(integer) 3
127.0.0.1:6379> ZREVRANGE leaderboard 0 -1 WITHSCORES
1) "PlayerTwo"
2) "3100"
3) "PlayerOne"
4) "2500"
5) "PlayerThree"
6) "1800"
```

#### 6. Bitmaps (Feature Flags & Active Users)
```text
127.0.0.1:6379> SETBIT active_users:2026-07-22 101 1
(integer) 0
127.0.0.1:6379> SETBIT active_users:2026-07-22 102 1
(integer) 0
127.0.0.1:6379> BITCOUNT active_users:2026-07-22
(integer) 2
```

#### 7. HyperLogLog (Cardinality Estimation)
```text
127.0.0.1:6379> PFADD unique_visitors "192.168.1.1" "192.168.1.2" "192.168.1.1"
(integer) 1
127.0.0.1:6379> PFCOUNT unique_visitors
(integer) 2
```

#### 8. Geospatial (Location Distances)
```text
127.0.0.1:6379> GEOADD cities -122.4194 37.7749 "SanFrancisco" -118.2437 34.0522 "LosAngeles"
(integer) 2
127.0.0.1:6379> GEODIST cities "SanFrancisco" "LosAngeles" km
"559.1206"
```

---

### 7. Key Expiration & TTL (Time-To-Live)

Set temporary keys that expire automatically:

```text
127.0.0.1:6379> SET session_token "abc123xyz" EX 10
OK
127.0.0.1:6379> TTL session_token
(integer) 7
```

Set expiration on an existing key or persist it permanently:

```text
127.0.0.1:6379> EXPIRE user:100:name 300
(integer) 1
127.0.0.1:6379> PERSIST user:100:name
(integer) 1
```

---

### 8. Transactions, Pipelines & Streams

#### 1. Atomic Transactions (`MULTI` / `EXEC`)
```text
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> INCR account:A:balance
QUEUED
127.0.0.1:6379> DECR account:B:balance
QUEUED
127.0.0.1:6379> EXEC
1) (integer) 101
2) (integer) 49
```

#### 2. Event Streaming (`XADD` / `XREAD`)
```text
127.0.0.1:6379> XADD mystream * sensor-id 12 temperature 19.8
"1700000000000-0"
127.0.0.1:6379> XRANGE mystream - +
1) 1) "1700000000000-0"
   2) 1) "sensor-id"
      2) "12"
      3) "temperature"
      4) "19.8"
```

---

### 9. Pub/Sub Messaging Features

Redis includes lightweight Publish/Subscribe messaging out-of-the-box.

#### Subscriber (Terminal 1)
```bash
redis-cli SUBSCRIBE notifications
```

#### Publisher (Terminal 2)
```bash
redis-cli PUBLISH notifications "New user registered!"
```

---

### 10. Terminal Scripting & Shell One-Liners

Execute Redis commands directly from your macOS terminal shell:

#### 1. Single Command One-Liner
```bash
redis-cli GET user:100:name
```

#### 2. Batch Imports with Command Piping (`redis-cli --pipe`)
```bash
cat << 'EOF' > commands.txt
SET item:1 "Keyboard"
SET item:2 "Mouse"
SET item:3 "Monitor"
EOF

cat commands.txt | redis-cli --pipe
```

#### 3. Scan Keys matching Pattern
```bash
redis-cli --scan --pattern 'user:*'
```

#### 4. Run Lua Evaluation Script
```bash
redis-cli EVAL "return redis.call('GET', KEYS[1])" 1 user:100:name
```

#### 5. Find Largest Keys (`--bigkeys`)
```bash
redis-cli --bigkeys
```

---

### 11. Server Monitoring & Benchmarking

#### Monitor Live Commands in Real-Time
```bash
redis-cli MONITOR
```

#### Check Memory & Server Stats
```bash
redis-cli INFO memory
```

#### Run Performance Benchmark (`redis-benchmark`)
Test server throughput on your Mac:

```bash
redis-benchmark -n 100000 -t set,get -q
```

---

### 12. Uninstallation

```bash
brew services stop redis
brew uninstall redis
rm -f /opt/homebrew/var/db/redis/dump.rdb
```