# Redis CLI Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial teaches you how to use the Redis CLI (Command Line Interface) with best practices, even if you're not a programmer. We'll cover installation, basic commands, and practical examples.

---

## 🧰 Prerequisites

### 1. Install Homebrew (if not installed)
Homebrew is a package manager for macOS that makes installing software easy.

```bash
# Install Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

🔗 [Homebrew Documentation](https://brew.sh/)

---

### 2. Install Redis Using Brew

```bash
# Install Redis
brew install redis
```

🔗 [Redis Installation via Brew](https://formulae.brew.sh/formula/redis)

---

### 3. Start Redis Server

```bash
# Start Redis server in background
brew services start redis
```

To verify it's running:

```bash
# Check Redis status
brew services list | grep redis
```

🔗 [Redis Quick Start Guide](https://redis.io/docs/getting-started/)

---

## 🚀 Using Redis CLI

### 1. Connect to Redis CLI

```bash
# Open Redis CLI
redis-cli
```

You’ll see a prompt like `127.0.0.1:6379>` — that means you're connected!

🔗 [Redis CLI Documentation](https://redis.io/topics/rediscli)

---

## 🔤 Basic Commands

### 1. Ping Redis

```bash
# Test connection
ping
```

Expected output: `PONG`

---

### 2. Set a Key-Value Pair

```bash
# Store a value
set greeting "Hello, Redis!"
```

This creates a key called `greeting` with the value `"Hello, Redis!"`.

---

### 3. Get a Value

```bash
# Retrieve the value
get greeting
```

Expected output: `"Hello, Redis!"`

---

### 4. Delete a Key

```bash
# Remove the key
del greeting
```

Now try getting it again:

```bash
get greeting
```

Expected output: `(nil)` (means it doesn't exist)

---

## 📦 Working with Data Types

### 1. Strings

```bash
# Set a string
set name "Alice"

# Get the string
get name
```

🔗 [Redis Strings Documentation](https://redis.io/docs/data-types/strings/)

---

### 2. Lists (Ordered Collection)

```bash
# Add items to a list
lpush tasks "Buy milk"
lpush tasks "Walk dog"

# View all items
lrange tasks 0 -1
```

Expected output:
```
1) "Walk dog"
2) "Buy milk"
```

🔗 [Redis Lists Documentation](https://redis.io/docs/data-types/lists/)

---

### 3. Sets (Unique Items Only)

```bash
# Add unique items to a set
sadd colors "red"
sadd colors "blue"
sadd colors "red"  # Won't be added again

# View all items
smembers colors
```

Expected output:
```
1) "red"
2) "blue"
```

🔗 [Redis Sets Documentation](https://redis.io/docs/data-types/sets/)

---

### 4. Hashes (Key-Value Pairs within a Key)

```bash
# Store user info
hset user:1000 name "Bob" age 25 city "NYC"

# Get specific field
hget user:1000 name

# Get all fields
hgetall user:1000
```

Expected output:
```
1) "name"
2) "Bob"
3) "age"
4) "25"
5) "city"
6) "NYC"
```

🔗 [Redis Hashes Documentation](https://redis.io/docs/data-types/hashes/)

---

## ⏱️ Expiration & TTL

### Set Expiration Time

```bash
# Set a key that expires in 10 seconds
set temp "This will disappear" ex 10

# Check time to live
ttl temp
```

After 10 seconds:

```bash
get temp
```

Expected output: `(nil)`

🔗 [Redis Keys with TTL](https://redis.io/commands/ttl/)

---

## 🧪 Best Practices

### 1. Use Namespaces in Keys

Use prefixes to organize data:

```bash
set user:1001:name "Charlie"
set user:1001:email "charlie@example.com"
```

---

### 2. Avoid Long Keys

Shorter keys are faster and use less memory.

✅ Good:
```bash
set u:1001 "active"
```

❌ Bad:
```bash
set user_status_for_user_with_id_1001 "active"
```

---

### 3. Monitor Memory Usage

```bash
# Check memory usage
info memory
```

🔗 [Redis Memory Optimization](https://redis.io/docs/management/optimization/memory-optimization/)

---

## 🛠️ VSCode Setup Tips

### 1. Install Redis Syntax Highlighting Extension

In VSCode:
- Go to Extensions (`Cmd + Shift + X`)
- Search: **Redis Syntax Highlighter**
- Install it for better `.redis` file editing

---

### 2. Create a `.redis` Script File

Create a file named `commands.redis` and paste your commands:

```redis
# commands.redis
SET greeting "Hello from file"
GET greeting
DEL greeting
```

Run it from terminal:

```bash
redis-cli < commands.redis
```

🔗 [VSCode Redis Extension](https://marketplace.visualstudio.com/items?itemName=Redis.redis-syntax-highlighter)

---

## 🧹 Cleanup & Stop Redis

### Stop Redis Server

```bash
# Stop Redis service
brew services stop redis
```

### Flush All Data (⚠️ Use Carefully)

```bash
# Clear all keys
flushall
```

---

## 📚 Further Reading

- [Official Redis CLI Docs](https://redis.io/topics/rediscli)
- [Redis Data Types Overview](https://redis.io/docs/data-types/)
- [Redis Best Practices](https://redis.io/docs/management/best-practices/)

---

## ✅ Summary

| Task | Command |
|------|---------|
| Start Redis | `brew services start redis` |
| Connect CLI | `redis-cli` |
| Set Key | `set key value` |
| Get Key | `get key` |
| Delete Key | `del key` |
| List Keys | `keys *` |
| Exit CLI | `exit` |

You're now ready to use Redis CLI confidently! Practice these commands daily and build small projects to reinforce learning.