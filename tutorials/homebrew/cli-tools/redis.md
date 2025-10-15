# Strings
SET foo "bar"               # set
GET foo                     # get
SETEX session:123 300 "x"   # set with 5‑min TTL
INCR counter                # atomic integer ++
MSET a 1 b 2 c 3            # multi‑set
MGET a b c                  # multi‑get

# Hashes
HSET user:1 name "John" age 30
HGETALL user:1
HINCRBY user:1 visits 1

# Lists
LPUSH tasks "job1"
RPUSH tasks "job2"
LRANGE tasks 0 -1
LPOP tasks

# Sets
SADD tags "redis" "cache"
SMEMBERS tags
SISMEMBER tags "redis"

# Sorted Sets
ZADD leaderboard 1000 "bob" 1500 "alice"
ZRANGE leaderboard 0 -1 WITHSCORES
ZINCRBY leaderboard 200 "bob"

# TTL & expiration
EXPIRE user:1 3600
TTL user:1
