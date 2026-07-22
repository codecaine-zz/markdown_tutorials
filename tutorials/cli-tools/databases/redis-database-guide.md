# Redis: In-Memory Key-Value Data Store CLI Guide

## Table of Contents

1. [What is `redis`?](#1-what-is-redis)
2. [Prerequisites](#2-prerequisites)
3. [Installation on ARM macOS](#3-installation-on-arm-macos)
4. [Service Management (`brew services`)](#4-service-management-brew-services)
5. [Connecting with `redis-cli`](#5-connecting-with-redis-cli)
6. [Core Data Types & Examples](#6-core-data-types--examples)
7. [Key Expiration & TTL (Time-To-Live)](#7-key-expiration--ttl-time-to-live)
8. [Pub/Sub Messaging Features](#8-pubsub-messaging-features)
9. [Server Monitoring & Benchmarking](#9-server-monitoring--benchmarking)
10. [Uninstallation](#10-uninstallation)

---

### 1. What is `redis`?

`redis` (Remote Dictionary Server) is an open-source, in-memory data structure store used as a database, cache, message broker, and streaming engine. The `redis-cli` tool provides a command-line interface to interact directly with local or remote Redis server instances.

#### Common Use Cases
* **High-Speed Caching:** Caching database query results with TTL expiration.
* **Session Storage:** Fast web session management across cluster nodes.
* **Real-time Messaging:** Pub/Sub message channels and job queues.

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

#### Test Server Ping
Inside the prompt (`127.0.0.1:6379>`):

```text
127.0.0.1:6379> PING
PONG
```

---

### 6. Core Data Types & Examples

#### 1. Strings (Simple Key-Value)
```text
127.0.0.1:6379> SET user:100:name "Alice"
OK
127.0.0.1:6379> GET user:100:name
"Alice"
127.0.0.1:6379> INCR page_views
(integer) 1
```

#### 2. Hashes (Object-like Structures)
```text
127.0.0.1:6379> HSET user:100 email "alice@example.com" age 28 role "admin"
(integer) 3
127.0.0.1:6379> HGETALL user:100
1) "email"
2) "alice@example.com"
3) "age"
4) "28"
5) "role"
6) "admin"
```

#### 3. Lists (Ordered Sequences / Queues)
```text
127.0.0.1:6379> LPUSH job_queue "task_email_1"
(integer) 1
127.0.0.1:6379> LPUSH job_queue "task_email_2"
(integer) 2
127.0.0.1:6379> RPOP job_queue
"task_email_1"
```

#### 4. Sets (Unique Unordered Elements)
```text
127.0.0.1:6379> SADD tags "database" "cli" "macos" "cli"
(integer) 3
127.0.0.1:6379> SMEMBERS tags
1) "database"
2) "cli"
3) "macos"
```

---

### 7. Key Expiration & TTL (Time-To-Live)

Set temporary keys that expire automatically after a specified number of seconds:

```text
127.0.0.1:6379> SET session_token "abc123xyz" EX 10
OK
127.0.0.1:6379> TTL session_token
(integer) 7
```

After 10 seconds:

```text
127.0.0.1:6379> GET session_token
(nil)
```

---

### 8. Pub/Sub Messaging Features

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

### 9. Server Monitoring & Benchmarking

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

### 10. Uninstallation

```bash
brew services stop redis
brew uninstall redis
rm -f /opt/homebrew/var/db/redis/dump.rdb
```