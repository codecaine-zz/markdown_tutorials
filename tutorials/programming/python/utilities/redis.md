# Complete Python Redis Tutorial

This tutorial will guide you through using Redis with Python, covering installation, basic operations, advanced features, and best practices.

## Table of Contents
1. [Installation](#installation)
2. [Basic Connection](#basic-connection)
3. [String Operations](#string-operations)
4. [Hash Operations](#hash-operations)
5. [List Operations](#list-operations)
6. [Set Operations](#set-operations)
7. [Sorted Set Operations](#sorted-set-operations)
8. [Pub/Sub Messaging](#pubsub-messaging)
9. [Transactions and Pipelining](#transactions-and-pipelining)
10. [Advanced Features](#advanced-features)
11. [Error Handling](#error-handling)
12. [Best Practices](#best-practices)

## Installation

First, install the Redis Python client library:

```bash
pip install redis
```

## Basic Connection

```python
import redis
import json

# Connect to Redis server (default: localhost:6379)
r = redis.Redis(host='localhost', port=6379, db=0)

# Test connection
try:
    r.ping()
    print("Connected to Redis successfully!")
except redis.ConnectionError:
    print("Failed to connect to Redis")

# Alternative connection with password and custom settings
r_password = redis.Redis(
    host='localhost',
    port=6379,
    db=0,
    password='your_password',  # If Redis requires authentication
    decode_responses=True,     # Automatically decode responses to strings
    socket_timeout=5,          # Socket timeout in seconds
    retry_on_timeout=True      # Retry on timeout
)

# Connection pool (recommended for production)
pool = redis.ConnectionPool(host='localhost', port=6379, db=0)
r_pool = redis.Redis(connection_pool=pool)
```

## String Operations

String operations are the most basic Redis data type.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# SET and GET operations
r.set('name', 'John Doe')
print(r.get('name'))  # Output: John Doe

# SET with expiration (in seconds)
r.setex('session_id', 3600, 'abc123')  # Expires in 1 hour
r.psetex('temp_key', 5000, 'temporary_value')  # Expires in 5 seconds

# GETSET - Get old value and set new value
old_value = r.getset('counter', '1')
print(f"Old value: {old_value}")  # Output: None (first time)
current_counter = r.get('counter')
print(f"Current counter: {current_counter}")  # Output: 1

# MSET and MGET for multiple keys
r.mset({'name': 'Alice', 'age': '25', 'city': 'New York'})
values = r.mget(['name', 'age', 'city'])
print(values)  # Output: ['Alice', '25', 'New York']

# INCR and DECR operations (for integers)
r.set('visits', 10)
r.incr('visits')           # Increment by 1
r.incrby('visits', 5)      # Increment by 5
r.decr('visits')           # Decrement by 1
print(r.get('visits'))     # Output: 14

# APPEND operation
r.set('message', 'Hello')
r.append('message', ' World!')
print(r.get('message'))    # Output: Hello World!

# STRLEN - Get string length
print(r.strlen('message')) # Output: 12

# EXISTS and DEL operations
r.set('test_key', 'value')
exists = r.exists('test_key')  # Returns 1 if exists, 0 otherwise
print(f"Key exists: {bool(exists)}")  # Output: True

r.delete('test_key')
print(r.exists('test_key'))  # Output: False

# KEYS - Find all keys matching pattern
r.set('user:1:name', 'John')
r.set('user:2:name', 'Jane')
r.set('product:1:name', 'Laptop')

keys = r.keys('user:*')      # Find all user keys
print(keys)                  # Output: ['user:1:name', 'user:2:name']

# TTL - Get time to live (seconds)
r.setex('expiring_key', 60, 'value')
ttl = r.ttl('expiring_key')  # Returns remaining seconds or -1 if no expiration
print(ttl)                   # Output: around 60

# PERSIST - Remove expiration from key
r.expire('expiring_key', 30)
r.persist('expiring_key')    # Remove expiration (key will never expire)
```

## Hash Operations

Hashes store field-value pairs within a single key.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# HSET and HGET operations
r.hset('user:1000', 'name', 'John')
r.hset('user:1000', 'age', 30)
r.hset('user:1000', 'email', 'john@example.com')

# Get specific field
print(r.hget('user:1000', 'name'))  # Output: John

# HGETALL - Get all fields and values
user_data = r.hgetall('user:1000')
print(user_data)  # Output: {'name': 'John', 'age': '30', 'email': 'john@example.com'}

# HMSET - Set multiple fields at once
r.hmset('product:12345', {
    'name': 'Laptop',
    'price': 999.99,
    'category': 'Electronics'
})

# HMGET - Get multiple fields
fields = r.hmget('product:12345', ['name', 'price'])
print(fields)  # Output: ['Laptop', '999.99']

# HSETNX - Set field only if it doesn't exist
r.hsetnx('user:1000', 'age', 25)  # Won't set since age already exists
print(r.hget('user:1000', 'age'))  # Output: 30 (unchanged)

# HINCRBY - Increment hash field by integer
r.hincrby('product:12345', 'price', 10)  # Add $10 to price
print(r.hget('product:12345', 'price'))  # Output: 1009.99

# HINCRBYFLOAT - Increment hash field by float
r.hincrbyfloat('product:12345', 'price', 10.5)  # Add $10.5 to price
print(r.hget('product:12345', 'price'))  # Output: 1020.49

# HDEL - Delete field(s)
r.hdel('user:1000', 'email')  # Remove email field

# HEXISTS - Check if field exists
print(r.hexists('user:1000', 'name'))    # Output: True
print(r.hexists('user:1000', 'email'))   # Output: False

# HKEYS and HVALS - Get all keys or values
keys = r.hkeys('product:12345')
values = r.hvals('product:12345')
print(f"Keys: {keys}")  # Output: ['name', 'price', 'category']
print(f"Values: {values}")  # Output: ['Laptop', '1020.49', 'Electronics']

# HLEN - Get number of fields
print(r.hlen('product:12345'))  # Output: 3

# HSCAN - Scan hash keys (useful for large hashes)
cursor, data = r.hscan('user:1000', cursor=0, match='*')
print(f"Scan result: {data}")
```

## List Operations

Lists are ordered collections of strings that support operations at both ends.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# LPUSH and RPUSH - Add elements to left or right end
r.lpush('mylist', 'first')
r.rpush('mylist', 'last')
r.lpush('mylist', 'middle')

print(r.lrange('mylist', 0, -1))  # Output: ['middle', 'first', 'last']

# LPOP and RPOP - Remove and return elements from left or right
left_item = r.lpop('mylist')
right_item = r.rpop('mylist')
print(f"Left item: {left_item}")   # Output: middle
print(f"Right item: {right_item}") # Output: last

# LRANGE - Get range of elements (0 to -1 means all)
all_items = r.lrange('mylist', 0, -1)
print(all_items)  # Output: ['first']

# LINDEX - Get element at specific index
print(r.lindex('mylist', 0))  # Output: first

# LLEN - Get list length
print(r.llen('mylist'))  # Output: 1

# LINSERT - Insert element before or after another element
r.rpush('numbers', '1', '2', '3')
r.linsert('numbers', 'BEFORE', '2', '1.5')  # Insert 1.5 before 2
print(r.lrange('numbers', 0, -1))  # Output: ['1', '1.5', '2', '3']

# LREM - Remove elements by value (count = number of occurrences to remove)
r.rpush('fruits', 'apple', 'banana', 'apple')
r.lrem('fruits', 1, 'apple')  # Remove first occurrence
print(r.lrange('fruits', 0, -1))  # Output: ['banana', 'apple']

# LSET - Set element at specific index
r.lset('numbers', 0, 'new_value')
print(r.lrange('numbers', 0, -1))  # Output: ['new_value', '1.5', '2', '3']

# LTRIM - Trim list to specified range
r.ltrim('numbers', 0, 1)  # Keep only first two elements
print(r.lrange('numbers', 0, -1))  # Output: ['new_value', '1.5']

# BLPOP and BRPOP - Blocking operations (wait for element)
# Note: These are blocking operations that wait until an element is available

# RPOPLPUSH - Remove last element from one list and push to another
r.rpush('source_list', 'a', 'b', 'c')
r.rpush('dest_list', 'x')

popped = r.rpoplpush('source_list', 'dest_list')
print(f"Popped: {popped}")  # Output: c
print(r.lrange('source_list', 0, -1))  # Output: ['a', 'b']
print(r.lrange('dest_list', 0, -1))    # Output: ['x', 'c']

# BRPOPLPUSH - Blocking version of RPOPLPUSH
```

## Set Operations

Sets store unique strings without order.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# SADD - Add elements to set
r.sadd('colors', 'red', 'blue', 'green')
print(r.smembers('colors'))  # Output: {'green', 'blue', 'red'}

# SREM - Remove elements from set
r.srem('colors', 'blue')
print(r.smembers('colors'))  # Output: {'green', 'red'}

# SISMEMBER - Check if element exists in set
print(r.sismember('colors', 'red'))   # Output: True
print(r.sismember('colors', 'yellow'))  # Output: False

# SCARD - Get set cardinality (number of elements)
print(r.scard('colors'))  # Output: 2

# SPOP - Remove and return random element
popped = r.spop('colors')
print(f"Popped: {popped}")
print(r.smembers('colors'))

# SRANDMEMBER - Get random elements without removing them
random_elements = r.srandmember('colors', 1)
print(random_elements)

# SMEMBERS - Get all members of set
all_colors = r.smembers('colors')
print(all_colors)  # Output: Set with remaining colors

# SDIFF - Difference between sets
r.sadd('set_a', 'a', 'b', 'c')
r.sadd('set_b', 'b', 'c', 'd')
diff = r.sdiff('set_a', 'set_b')  # Elements in set_a but not in set_b
print(diff)  # Output: {'a'}

# SDIFFSTORE - Store difference in a new key
r.sdiffstore('result_set', 'set_a', 'set_b')
print(r.smembers('result_set'))  # Output: {'a'}

# SINTER - Intersection of sets
intersection = r.sinter('set_a', 'set_b')  # Elements common to both sets
print(intersection)  # Output: {'b', 'c'}

# SINTERSTORE - Store intersection in a new key
r.sinterstore('intersection_result', 'set_a', 'set_b')
print(r.smembers('intersection_result'))  # Output: {'b', 'c'}

# SUNION - Union of sets
union = r.sunion('set_a', 'set_b')  # All elements from both sets (unique)
print(union)  # Output: {'a', 'b', 'c', 'd'}

# SUNIONSTORE - Store union in a new key
r.sunionstore('union_result', 'set_a', 'set_b')
print(r.smembers('union_result'))  # Output: {'a', 'b', 'c', 'd'}

# SSCAN - Scan set elements (useful for large sets)
cursor, data = r.sscan('colors', cursor=0, match='*')
print(f"Scan result: {data}")
```

## Sorted Set Operations

Sorted sets are like sets but with scores that determine ordering.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# ZADD - Add elements with scores
r.zadd('leaderboard', {'Alice': 150, 'Bob': 200, 'Charlie': 180})
print(r.zrange('leaderboard', 0, -1, withscores=True))  
# Output: [('Alice', 150.0), ('Charlie', 180.0), ('Bob', 200.0)]

# ZRANGE - Get range of elements (ascending)
print(r.zrange('leaderboard', 0, -1))  # Output: ['Alice', 'Charlie', 'Bob']

# ZREVRANGE - Get range in descending order
print(r.zrevrange('leaderboard', 0, -1))  # Output: ['Bob', 'Charlie', 'Alice']

# ZSCORE - Get score of element
print(r.zscore('leaderboard', 'Bob'))  # Output: 200.0

# ZRANK - Get rank (ascending) of element
print(r.zrank('leaderboard', 'Bob'))   # Output: 2

# ZREVRANK - Get rank (descending) of element
print(r.zrevrank('leaderboard', 'Bob'))  # Output: 0

# ZINCRBY - Increment score by amount
r.zincrby('leaderboard', 50, 'Alice')
print(r.zscore('leaderboard', 'Alice'))  # Output: 200.0

# ZREM - Remove element from sorted set
r.zrem('leaderboard', 'Charlie')

# ZCARD - Get number of elements in sorted set
print(r.zcard('leaderboard'))  # Output: 2

# ZCOUNT - Count elements within score range
r.zadd('scores', {'A': 10, 'B': 20, 'C': 30, 'D': 40})
count = r.zcount('scores', 15, 35)  # Count elements between scores 15 and 35
print(count)  # Output: 2 (elements B and C)

# ZRANGEBYSCORE - Get range of elements by score
range_result = r.zrangebyscore('scores', 15, 35)
print(range_result)  # Output: ['B', 'C']

# ZREVRANGEBYSCORE - Get descending range by score
rev_range = r.zrevrangebyscore('scores', 35, 15)
print(rev_range)  # Output: ['C', 'B']

# ZREM RANGE - Remove elements in a score range
r.zremrangebyscore('scores', 20, 30)  # Remove scores between 20 and 30

# ZREMRANGEBYRANK - Remove elements by rank (index)
r.zadd('test_rank', {'first': 1, 'second': 2, 'third': 3})
r.zremrangebyrank('test_rank', 0, 1)  # Remove first two elements
print(r.zrange('test_rank', 0, -1))   # Output: ['third']

# ZSCAN - Scan sorted set elements
cursor, data = r.zscan('leaderboard', cursor=0, match='*')
print(f"Scan result: {data}")

# ZLEXCOUNT - Count elements within lexicographic range (requires Redis 2.8+)
r.zadd('alphabet', {'a': 0, 'b': 0, 'c': 0, 'd': 0})
count = r.zlexcount('alphabet', '[b', '[d')  # Count from b to d
print(count)  # Output: 3

# ZRANGEBYLEX - Get elements within lexicographic range
elements = r.zrangebylex('alphabet', '[b', '[d')
print(elements)  # Output: ['b', 'c', 'd']
```

## Pub/Sub Messaging

Redis supports publish/subscribe messaging pattern.

```python
import redis
import threading
import time

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# Publisher example (in a separate script or thread)
def publisher():
    pubsub = r.pubsub()
    
    # Publish messages to channel
    for i in range(5):
        message = f"Message {i}"
        r.publish('test_channel', message)
        print(f"Published: {message}")
        time.sleep(1)

# Subscriber example (in a separate script or thread)
def subscriber():
    pubsub = r.pubsub()
    
    # Subscribe to channel
    pubsub.subscribe('test_channel')
    
    print("Subscriber waiting for messages...")
    
    # Listen for messages
    for message in pubsub.listen():
        if message['type'] == 'message':
            print(f"Received: {message['data']}")
            if message['data'] == 'quit':
                break

# Example of using pattern matching
def pattern_subscriber():
    pubsub = r.pubsub()
    
    # Subscribe to multiple channels using patterns
    pubsub.psubscribe('user:*')  # Listen to all user channels
    
    print("Pattern subscriber waiting for messages...")
    
    try:
        for message in pubsub.listen():
            if message['type'] == 'pmessage':
                print(f"Pattern matched: {message['channel']} - {message['data']}")
                if message['data'] == 'stop':
                    break
    except KeyboardInterrupt:
        print("Stopping subscriber")

# Example usage with threading (run in separate processes)
def run_pubsub_example():
    # Start publisher in a thread
    pub_thread = threading.Thread(target=publisher)
    pub_thread.start()
    
    # Start subscriber in another thread  
    sub_thread = threading.Thread(target=subscriber)
    sub_thread.start()
    
    # Wait for threads to complete
    pub_thread.join()
    sub_thread.join()

# Publish and subscribe example
def simple_pubsub():
    pubsub = r.pubsub()
    
    # Subscribe to channel
    pubsub.subscribe('chat_room')
    
    def handle_messages():
        for message in pubsub.listen():
            if message['type'] == 'message':
                print(f"Chat: {message['data']}")
                
                # Stop after receiving 3 messages
                if int(message['data']) >= 3:
                    break
    
    # Publish some test messages
    r.publish('chat_room', '1')
    r.publish('chat_room', '2') 
    r.publish('chat_room', '3')
    
    print("Messages published. Listening...")
    
# Channel information
def channel_info():
    pubsub = r.pubsub()
    
    # Get list of subscribed channels
    pubsub.subscribe('channel1', 'channel2')
    channels = pubsub.channels()  # Returns dict with subscription status
    
    # Get pattern subscriptions
    pubsub.psubscribe('pattern:*')
    patterns = pubsub.patterns()  # Returns dict with pattern info
    
    print(f"Channels: {channels}")
    print(f"Patterns: {patterns}")

# Clean up subscriptions (important for long-running applications)
def cleanup_subscriptions():
    pubsub = r.pubsub()
    
    # Subscribe to channels
    pubsub.subscribe('test1', 'test2')
    
    # Unsubscribe from all channels
    pubsub.unsubscribe()
    
    # Or unsubscribe from specific channel
    pubsub.subscribe('test3')
    pubsub.unsubscribe('test3')  # Remove specific subscription

# Run the examples (uncomment to test)
# simple_pubsub()  # Uncomment to run this example
```

## Transactions and Pipelining

Handle multiple operations atomically with transactions or optimize performance with pipelining.

```python
import redis

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# Transaction using MULTI/EXEC (similar to database transactions)
def transaction_example():
    # Create a pipeline for atomic operations
    pipe = r.pipeline()
    
    try:
        # Start transaction block
        pipe.multi()
        
        # Perform multiple operations that will be atomic
        pipe.set('counter', 0)
        pipe.incr('counter')
        pipe.incrby('counter', 5)
        pipe.setex('session_data', 3600, 'user_session_info')
        
        # Execute all operations as one transaction
        results = pipe.execute()
        print(f"Transaction results: {results}")
        
    except redis.WatchError:
        print("Transaction failed due to concurrent modification")
    except Exception as e:
        print(f"Transaction error: {e}")

# Watch and multi commands for optimistic locking
def watch_example():
    # Watch a key for changes
    r.set('balance', '1000')
    
    try:
        with r.pipeline() as pipe:
            while True:
                try:
                    # Watch the balance key
                    pipe.watch('balance')
                    
                    # Get current value
                    current_balance = int(pipe.get('balance'))
                    
                    # Calculate new balance (simulate a transaction)
                    new_balance = current_balance - 100
                    
                    # Start multi-exec block
                    pipe.multi()
                    pipe.set('balance', str(new_balance))
                    
                    # Execute the transaction
                    pipe.execute()
                    print(f"Transaction successful. New balance: {new_balance}")
                    break
                    
                except redis.WatchError:
                    # If watch failed, retry the operation
                    print("Watch error - retrying...")
                    continue
                    
    except Exception as e:
        print(f"Error: {e}")

# Pipelining for performance optimization (batch operations)
def pipeline_example():
    # Pipeline multiple operations without waiting for each response
    pipe = r.pipeline()
    
    # Add operations to the pipeline
    for i in range(1000):
        pipe.set(f'key:{i}', f'value:{i}')
        
    # Execute all operations at once (much faster)
    results = pipe.execute()
    
    print(f"Pipeline executed {len(results)} operations")

# Pipeline with custom commands
def advanced_pipeline():
    # Create pipeline with transaction-like behavior
    pipe = r.pipeline(transaction=True)
    
    try:
        # Multiple operations in one atomic block
        pipe.set('user:1', 'John')
        pipe.hset('user:1:data', 'age', 30)
        pipe.sadd('user:1:roles', 'admin', 'user')
        
        results = pipe.execute()
        print(f"Multi-operation results: {results}")
        
    except Exception as e:
        print(f"Pipeline execution failed: {e}")

# Performance comparison example
def performance_comparison():
    import time
    
    # Regular operations (slow)
    start_time = time.time()
    
    for i in range(100):
        r.set(f'regular:{i}', f'value:{i}')
        
    regular_time = time.time() - start_time
    
    # Pipelined operations (fast)
    start_time = time.time()
    
    pipe = r.pipeline()
    for i in range(100):
        pipe.set(f'pipeline:{i}', f'value:{i}')
        
    pipe.execute()
    
    pipeline_time = time.time() - start_time
    
    print(f"Regular operations took: {regular_time:.4f} seconds")
    print(f"Pipelined operations took: {pipeline_time:.4f} seconds")
```

## Advanced Features

Advanced Redis features for complex applications.

```python
import redis
import json
from datetime import datetime, timedelta

r = redis.Redis(host='localhost', port=6379, db=0, decode_responses=True)

# Key expiration and persistence controls
def expiration_features():
    # Set key with TTL in seconds
    r.setex('temp_key', 300, 'temporary data')  # Expires in 5 minutes
    
    # Set key with TTL in milliseconds  
    r.psetex('ms_key', 1000, 'data expires in 1 second')
    
    # Get remaining time to live
    ttl = r.ttl('temp_key')
    print(f"Time to live: {ttl} seconds")
    
    # Remove expiration (make key permanent)
    r.persist('temp_key')
    
    # Get all keys with their TTLs
    for key in r.keys('*'):
        ttl = r.ttl(key)
        if ttl != -1 and ttl != -2:
            print(f"Key: {key}, TTL: {ttl}")

# Redis connection settings and configuration
def connection_settings():
    # Configure client behavior
    config = {
        'host': 'localhost',
        'port': 6379,
        'db': 0,
        'password': None,
        'socket_timeout': 5,
        'socket_connect_timeout': 5,
        'retry_on_timeout': True,
        'health_check_interval': 30,  # Health check every 30 seconds
        'decode_responses': True,
    }
    
    r = redis.Redis(**config)
    
    # Check Redis info
    info = r.info()
    print(f"Redis version: {info['redis_version']}")
    print(f"Connected clients: {info['connected_clients']}")
    
    # Get memory usage
    memory_info = r.info('memory')
    print(f"Memory used: {memory_info['used_memory_human']}")

# Data structures for caching patterns
def cache_patterns():
    # Simple key-value cache with expiration
    def set_cache(key, value, ttl=3600):
        r.setex(key, ttl, json.dumps(value))
    
    def get_cache(key):
        data = r.get(key)
        return json.loads(data) if data else None
    
    # Set example
    user_data = {'name': 'John', 'age': 30, 'city': 'New York'}
    set_cache('user:123', user_data, ttl=1800)  # Cache for 30 minutes
    
    # Get example  
    cached_user = get_cache('user:123')
    print(cached_user)

# Rate limiting using Redis
def rate_limiting():
    def is_rate_limited(key, max_requests=5, window=60):
        """
        Simple token bucket rate limiter
        """
        now = datetime.now().timestamp()
        
        # Create key with current time window
        window_key = f"{key}:window"
        
        # Add timestamp to sorted set (scores are timestamps)
        r.zadd(window_key, {str(now): now})
        
        # Remove old entries outside the window
        r.zremrangebyscore(window_key, 0, now - window)
        
        # Get current count
        count = r.zcard(window_key)
        
        # Return True if limit exceeded
        return count > max_requests
    
    # Example usage
    for i in range(10):
        limited = is_rate_limited('user:123', 5, 60)
        print(f"Request {i+1}: {'Limited' if limited else 'Allowed'}")

# Session management with Redis
def session_management():
    def create_session(session_id, user_data, timeout=3600):
        """Create a Redis session"""
        r.hset(f"session:{session_id}", mapping=user_data)
        r.expire(f"session:{session_id}", timeout)
        
    def get_session(session_id):
        """Get session data"""
        return r.hgetall(f"session:{session_id}")
    
    def delete_session(session_id):
        """Delete a session"""
        r.delete(f"session:{session_id}")
    
    # Create example session
    create_session('sess_abc123', {
        'user_id': 12345,
        'username': 'john_doe',
        'role': 'user'
    }, timeout=1800)  # Session expires in 30 minutes
    
    # Retrieve session
    session_data = get_session('sess_abc123')
    print(session_data)

# Pub/Sub with message queues
def message_queue_example():
    def enqueue_message(queue_name, message):
        """Add message to queue"""
        r.lpush(queue_name, json.dumps(message))
        
    def dequeue_message(queue_name, timeout=0):
        """Get message from queue (blocking)"""
        result = r.brpop(queue_name, timeout=timeout)
        if result:
            return json.loads(result[1])
        return None
    
    # Example usage
    enqueue_message('task_queue', {'type': 'email', 'to': 'user@example.com'})
    enqueue_message('task_queue', {'type': 'sms', 'to': '+1234567890'})
    
    # Process messages
    message = dequeue_message('task_queue')
    if message:
        print(f"Processing: {message}")

# Redis with JSON data
def json_handling():
    # Store and retrieve JSON data
    
    def set_json(key, data):
        """Store JSON object"""
        r.set(key, json.dumps(data))
        
    def get_json(key):
        """Retrieve and parse JSON object"""
        data = r.get(key)
        return json.loads(data) if data else None
    
    # Example
    user_profile = {
        'id': 123,
        'name': 'John Doe',
        'preferences': {
            'theme': 'dark',
            'notifications': True
        }
    }
    
    set_json('user:123:profile', user_profile)
    
    retrieved = get_json('user:123:profile')
    print(retrieved)

# Monitor Redis operations
def monitoring():
    # Get current connections
    info = r.info()
    
    # Get memory usage
    memory_info = r.info('memory')
    
    # Get database statistics  
    stats = r.info('stats')
    
    print("=== Redis Info ===")
    print(f"Version: {info['redis_version']}")
    print(f"Connected clients: {info['connected_clients']}")
    print(f"Memory used: {memory_info['used_memory_human']}")
    print(f"Total commands processed: {stats['total_commands_processed']}")

# Clean up example data
def cleanup():
    # Remove test keys
    test_keys = r.keys('test_*') + r.keys('user:*') + r.keys('session:*') + r.keys('temp_*') + r.keys('ms_*')
    if test_keys:
        r.delete(*test_keys)
        print("Test keys deleted.")

# Run examples
if __name__ == "__main__":
    # Uncomment the examples you want to run
    # expiration_features()
    # connection_settings()
    # cache_patterns()
    # rate_limiting()
    # session_management()
    # message_queue_example()
    # json_handling()
    # monitoring()
    pass
```

## Error Handling

Proper error handling is essential for robust Redis applications.

```python
import redis
import time
import logging
from redis.exceptions import (
    ConnectionError, 
    TimeoutError, 
    AuthenticationError,
    DataError,
    ResponseError,
    WatchError
)

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Connection error handling
def connection_error_handling():
    try:
        # Attempt to connect to Redis
        r = redis.Redis(
            host='localhost', 
            port=6379, 
            db=0,
            socket_timeout=5,
            socket_connect_timeout=5,
            retry_on_timeout=True,
            decode_responses=True
        )
        
        # Test the connection
        r.ping()
        logger.info("Successfully connected to Redis")
        
        return r
        
    except ConnectionError:
        logger.error("Failed to connect to Redis server")
        return None
    except AuthenticationError:
        logger.error("Redis authentication failed")
        return None
    except Exception as e:
        logger.error(f"Unexpected error connecting to Redis: {e}")
        return None

# Timeout handling
def timeout_handling():
    r = redis.Redis(host='localhost', port=6379, socket_timeout=1)
    
    try:
        # This might timeout if Redis is slow to respond
        result = r.get('some_key')
        return result
        
    except TimeoutError:
        logger.warning("Redis operation timed out")
        return None
    except Exception as e:
        logger.error(f"Error during Redis operation: {e}")
        return None

# Data validation and error handling
def safe_redis_operations():
    r = redis.Redis(host='localhost', port=6379, decode_responses=True)
    
    def safe_get(key):
        try:
            return r.get(key)
        except (ConnectionError, TimeoutError) as e:
            logger.error(f"Redis connection issue: {e}")
            return None
        except Exception as e:
            logger.error(f"Unexpected error getting key {key}: {e}")
            return None
    
    def safe_set(key, value, ex=None):
        try:
            return r.set(key, value, ex=ex)
        except (ConnectionError, TimeoutError) as e:
            logger.error(f"Redis connection issue: {e}")
            return False
        except DataError as e:
            logger.error(f"Invalid data for Redis: {e}")
            return False
        except Exception as e:
            logger.error(f"Unexpected error setting key {key}: {e}")
            return False
    
    def safe_incr(key, amount=1):
        try:
            return r.incr(key, amount)
        except ResponseError as e:
            if "not an integer" in str(e):
                logger.error(f"Key {key} does not contain an integer value")
            else:
                logger.error(f"Redis response error: {e}")
            return None
        except Exception as e:
            logger.error(f"Error incrementing key {key}: {e}")
            return None
    
    # Example usage
    safe_set('counter', '0')
    safe_incr('counter', 5)
    value = safe_get('counter')
    
    return value

# Transaction error handling
def safe_transaction():
    r = redis.Redis(host='localhost', port=6379, decode_responses=True)
    
    try:
        with r.pipeline() as pipe:
            while True:
                try:
                    # Watch a key for changes
                    pipe.watch('balance')
                    
                    # Get current balance
                    current_balance = pipe.get('balance')
                    if current_balance is None:
                        pipe.set('balance', '1000')  # Initialize if not exists
                        current_balance = 1000
                    else:
                        current_balance = int(current_balance)
                    
                    # Check if we have sufficient balance
                    if current_balance < 100:
                        logger.warning("Insufficient balance for transaction")
                        return False
                    
                    # Start transaction
                    pipe.multi()
                    pipe.set('balance', str(current_balance - 100))
                    
                    # Execute transaction
                    pipe.execute()
                    logger.info("Transaction completed successfully")
                    return True
                    
                except WatchError:
                    logger.warning("Transaction failed due to concurrent modification, retrying...")
                    continue
                except ValueError as e:
                    logger.error(f"Invalid balance value: {e}")
                    return False
                    
    except Exception as e:
        logger.error(f"Transaction failed: {e}")
        return False

# Connection pool error handling
def connection_pool_handling():
    try:
        # Create connection pool
        pool = redis.ConnectionPool(
            host='localhost',
            port=6379,
            db=0,
            max_connections=10,
            retry_on_timeout=True,
            socket_timeout=5
        )
        
        r = redis.Redis(connection_pool=pool)
        
        # Test connection
        r.ping()
        logger.info("Connection pool created successfully")
        
        return r
        
    except Exception as e:
        logger.error(f"Failed to create connection pool: {e}")
        return None

# Retry mechanism with exponential backoff
def redis_with_retry(max_retries=3, base_delay=1):
    def decorator(func):
        def wrapper(*args, **kwargs):
            for attempt in range(max_retries):
                try:
                    return func(*args, **kwargs)
                except (ConnectionError, TimeoutError) as e:
                    if attempt == max_retries - 1:
                        logger.error(f"Failed after {max_retries} attempts: {e}")
                        raise
                    
                    delay = base_delay * (2 ** attempt)
                    logger.warning(f"Attempt {attempt + 1} failed, retrying in {delay}s...")
                    time.sleep(delay)
                    
        return wrapper
    return decorator

# Example usage of retry decorator
@redis_with_retry(max_retries=3, base_delay=0.5)
def get_with_retry(r, key):
    return r.get(key)

# Circuit breaker pattern for Redis
class RedisCircuitBreaker:
    def __init__(self, failure_threshold=5, reset_timeout=60):
        self.failure_threshold = failure_threshold
        self.reset_timeout = reset_timeout
        self.failure_count = 0
        self.last_failure_time = None
        self.state = 'CLOSED'  # CLOSED, OPEN, HALF_OPEN
    
    def call(self, func, *args, **kwargs):
        if self.state == 'OPEN':
            if time.time() - self.last_failure_time > self.reset_timeout:
                self.state = 'HALF_OPEN'
            else:
                raise Exception("Circuit breaker is OPEN")
        
        try:
            result = func(*args, **kwargs)
            self.on_success()
            return result
        except Exception as e:
            self.on_failure()
            raise e
    
    def on_success(self):
        self.failure_count = 0
        self.state = 'CLOSED'
    
    def on_failure(self):
        self.failure_count += 1
        self.last_failure_time = time.time()
        
        if self.failure_count >= self.failure_threshold:
            self.state = 'OPEN'
            logger.warning("Circuit breaker opened due to failures")

# Example usage of circuit breaker
def redis_with_circuit_breaker():
    r = redis.Redis(host='localhost', port=6379)
    breaker = RedisCircuitBreaker(failure_threshold=3, reset_timeout=30)
    
    try:
        result = breaker.call(r.get, 'some_key')
        return result
    except Exception as e:
        logger.error(f"Circuit breaker prevented call: {e}")
        return None
```

## Best Practices

Follow these best practices for efficient and reliable Redis usage.

```python
import redis
import json
import hashlib
from typing import Optional, Any, Dict, List
import logging

logger = logging.getLogger(__name__)

# 1. Connection Management Best Practices
class RedisManager:
    """
    Centralized Redis connection management with best practices
    """
    _instance = None
    _redis_client = None
    
    def __new__(cls):
        if cls._instance is None:
            cls._instance = super(RedisManager, cls).__new__(cls)
        return cls._instance
    
    def __init__(self):
        if self._redis_client is None:
            self._redis_client = self._create_redis_client()
    
    def _create_redis_client(self):
        """Create Redis client with optimal settings"""
        pool = redis.ConnectionPool(
            host='localhost',
            port=6379,
            db=0,
            max_connections=20,          # Pool size
            retry_on_timeout=True,       # Retry failed operations
            socket_timeout=5,            # Socket timeout
            socket_connect_timeout=5,    # Connection timeout
            health_check_interval=30,    # Health check frequency
            decode_responses=True        # Auto-decode responses
        )
        
        return redis.Redis(connection_pool=pool)
    
    @property
    def client(self):
        return self._redis_client

# Usage
redis_manager = RedisManager()
r = redis_manager.client

# 2. Key Naming Conventions
class RedisKeyManager:
    """
    Consistent key naming and management
    """
    
    @staticmethod
    def user_key(user_id: int) -> str:
        """Generate user key"""
        return f"user:{user_id}"
    
    @staticmethod
    def user_session_key(user_id: int) -> str:
        """Generate user session key"""
        return f"user:{user_id}:session"
    
    @staticmethod
    def cache_key(namespace: str, identifier: str) -> str:
        """Generate cache key with namespace"""
        return f"cache:{namespace}:{identifier}"
    
    @staticmethod
    def counter_key(name: str) -> str:
        """Generate counter key"""
        return f"counter:{name}"
    
    @staticmethod
    def queue_key(queue_name: str) -> str:
        """Generate queue key"""
        return f"queue:{queue_name}"
    
    @staticmethod
    def hash_key(data: str) -> str:
        """Generate hash for cache keys"""
        return hashlib.md5(data.encode()).hexdigest()

# 3. Data Serialization Best Practices
class RedisSerializer:
    """
    Efficient data serialization for Redis
    """
    
    @staticmethod
    def serialize(data: Any) -> str:
        """Serialize Python object to JSON string"""
        try:
            return json.dumps(data, default=str)
        except (TypeError, ValueError) as e:
            logger.error(f"Serialization error: {e}")
            return "{}"
    
    @staticmethod
    def deserialize(data: Optional[str]) -> Any:
        """Deserialize JSON string to Python object"""
        if not data:
            return None
            
        try:
            return json.loads(data)
        except (TypeError, ValueError, json.JSONDecodeError) as e:
            logger.error(f"Deserialization error: {e}")
            return None

# 4. Cache Implementation with Best Practices
class RedisCache:
    """
    Production-ready caching implementation
    """
    
    def __init__(self, redis_client, default_ttl=3600):
        self.redis = redis_client
        self.default_ttl = default_ttl
        self.serializer = RedisSerializer()
    
    def get(self, key: str) -> Optional[Any]:
        """Get value from cache"""
        try:
            data = self.redis.get(key)
            return self.serializer.deserialize(data)
        except Exception as e:
            logger.error(f"Cache get error for key {key}: {e}")
            return None
    
    def set(self, key: str, value: Any, ttl: Optional[int] = None) -> bool:
        """Set value in cache with TTL"""
        try:
            ttl = ttl or self.default_ttl
            serialized_data = self.serializer.serialize(value)
            return self.redis.setex(key, ttl, serialized_data)
        except Exception as e:
            logger.error(f"Cache set error for key {key}: {e}")
            return False
    
    def delete(self, key: str) -> bool:
        """Delete key from cache"""
        try:
            return bool(self.redis.delete(key))
        except Exception as e:
            logger.error(f"Cache delete error for key {key}: {e}")
            return False
    
    def get_or_set(self, key: str, factory_func, ttl: Optional[int] = None) -> Any:
        """Get value from cache or set it using factory function"""
        value = self.get(key)
        if value is not None:
            return value
        
        try:
            value = factory_func()
            self.set(key, value, ttl)
            return value
        except Exception as e:
            logger.error(f"Cache factory function error for key {key}: {e}")
            return None
    
    def increment(self, key: str, amount: int = 1) -> Optional[int]:
        """Increment counter with error handling"""
        try:
            return self.redis.incr(key, amount)
        except Exception as e:
            logger.error(f"Cache increment error for key {key}: {e}")
            return None

# 5. Session Management Best Practices
class RedisSessionManager:
    """
    Secure and efficient session management
    """
    
    def __init__(self, redis_client, session_ttl=1800):
        self.redis = redis_client
        self.session_ttl = session_ttl
        self.key_manager = RedisKeyManager()
    
    def create_session(self, session_id: str, user_data: Dict) -> bool:
        """Create new session"""
        try:
            session_key = f"session:{session_id}"
            pipe = self.redis.pipeline()
            pipe.hset(session_key, mapping=user_data)
            pipe.expire(session_key, self.session_ttl)
            pipe.execute()
            return True
        except Exception as e:
            logger.error(f"Error creating session {session_id}: {e}")
            return False
    
    def get_session(self, session_id: str) -> Optional[Dict]:
        """Get session data"""
        try:
            session_key = f"session:{session_id}"
            data = self.redis.hgetall(session_key)
            if data:
                # Refresh session TTL on access
                self.redis.expire(session_key, self.session_ttl)
            return data if data else None
        except Exception as e:
            logger.error(f"Error getting session {session_id}: {e}")
            return None
    
    def update_session(self, session_id: str, data: Dict) -> bool:
        """Update session data"""
        try:
            session_key = f"session:{session_id}"
            pipe = self.redis.pipeline()
            pipe.hset(session_key, mapping=data)
            pipe.expire(session_key, self.session_ttl)
            pipe.execute()
            return True
        except Exception as e:
            logger.error(f"Error updating session {session_id}: {e}")
            return False
    
    def delete_session(self, session_id: str) -> bool:
        """Delete session"""
        try:
            session_key = f"session:{session_id}"
            return bool(self.redis.delete(session_key))
        except Exception as e:
            logger.error(f"Error deleting session {session_id}: {e}")
            return False

# 6. Rate Limiting Best Practices
class RedisRateLimiter:
    """
    Token bucket rate limiter using Redis
    """
    
    def __init__(self, redis_client):
        self.redis = redis_client
    
    def is_allowed(self, key: str, max_requests: int, window_seconds: int) -> tuple[bool, Dict]:
        """
        Check if request is allowed
        Returns: (is_allowed, info_dict)
        """
        try:
            now = int(time.time())
            window_start = now - window_seconds
            
            pipe = self.redis.pipeline()
            
            # Remove old entries
            pipe.zremrangebyscore(key, 0, window_start)
            
            # Add current request
            pipe.zadd(key, {str(now): now})
            
            # Get current count
            pipe.zcard(key)
            
            # Set expiration
            pipe.expire(key, window_seconds)
            
            results = pipe.execute()
            current_requests = results[2]
            
            is_allowed = current_requests <= max_requests
            
            info = {
                'allowed': is_allowed,
                'current_requests': current_requests,
                'max_requests': max_requests,
                'window_seconds': window_seconds,
                'reset_time': now + window_seconds
            }
            
            return is_allowed, info
            
        except Exception as e:
            logger.error(f"Rate limiter error for key {key}: {e}")
            # Fail open - allow request if Redis is down
            return True, {'error': str(e)}

# 7. Performance Optimization Best Practices
class RedisPerformanceOptimizer:
    """
    Performance optimization utilities
    """
    
    def __init__(self, redis_client):
        self.redis = redis_client
    
    def batch_operations(self, operations: List[tuple]) -> List:
        """
        Execute multiple operations in a pipeline
        operations: List of (operation, key, value) tuples
        """
        pipe = self.redis.pipeline()
        
        for operation, *args in operations:
            if operation == 'set':
                pipe.set(*args)
            elif operation == 'get':
                pipe.get(*args)
            elif operation == 'delete':
                pipe.delete(*args)
            elif operation == 'incr':
                pipe.incr(*args)
            # Add more operations as needed
        
        return pipe.execute()
    
    def scan_keys_safely(self, pattern: str, count: int = 1000) -> List[str]:
        """
        Safely scan keys without blocking Redis
        """
        keys = []
        cursor = 0
        
        while True:
            cursor, batch = self.redis.scan(
                cursor=cursor, 
                match=pattern, 
                count=count
            )
            keys.extend(batch)
            
            if cursor == 0:
                break
                
        return keys
    
    def memory_usage_report(self) -> Dict:
        """Get memory usage information"""
        info = self.redis.info('memory')
        return {
            'used_memory': info.get('used_memory'),
            'used_memory_human': info.get('used_memory_human'),
            'used_memory_peak': info.get('used_memory_peak'),
            'used_memory_peak_human': info.get('used_memory_peak_human'),
            'memory_fragmentation_ratio': info.get('mem_fragmentation_ratio')
        }

# 8. Monitoring and Health Check
class RedisHealthCheck:
    """
    Health monitoring for Redis
    """
    
    def __init__(self, redis_client):
        self.redis = redis_client
    
    def health_check(self) -> Dict:
        """Comprehensive health check"""
        try:
            start_time = time.time()
            
            # Test basic connectivity
            self.redis.ping()
            ping_time = time.time() - start_time
            
            # Get server info
            info = self.redis.info()
            
            # Test read/write operations
            test_key = f"healthcheck:{int(time.time())}"
            self.redis.set(test_key, 'test', ex=60)
            self.redis.get(test_key)
            self.redis.delete(test_key)
            
            return {
                'status': 'healthy',
                'ping_time_ms': round(ping_time * 1000, 2),
                'redis_version': info.get('redis_version'),
                'connected_clients': info.get('connected_clients'),
                'used_memory_human': info.get('used_memory_human', 'N/A'),
                'uptime_in_seconds': info.get('uptime_in_seconds')
            }
            
        except Exception as e:
            return {
                'status': 'unhealthy',
                'error': str(e)
            }

# 9. Usage Examples with Best Practices
def production_examples():
    """Examples using best practices"""
    
    # Initialize components
    cache = RedisCache(r, default_ttl=1800)
    session_manager = RedisSessionManager(r)
    rate_limiter = RedisRateLimiter(r)
    health_checker = RedisHealthCheck(r)
    
    # Cache example
    def get_user_profile(user_id: int):
        cache_key = RedisKeyManager.cache_key('user_profile', str(user_id))
        
        def fetch_from_db():
            # Simulate database fetch
            return {'id': user_id, 'name': f'User {user_id}', 'email': f'user{user_id}@example.com'}
        
        return cache.get_or_set(cache_key, fetch_from_db, ttl=3600)
    
    # Rate limiting example
    def api_endpoint_with_rate_limit(user_id: int):
        rate_key = f"rate_limit:user:{user_id}"
        is_allowed, info = rate_limiter.is_allowed(rate_key, max_requests=100, window_seconds=3600)
        
        if not is_allowed:
            return {'error': 'Rate limit exceeded', 'info': info}
        
        return {'success': True, 'rate_info': info}
    
    # Session example
    def login_user(user_id: int, session_id: str):
        user_data = {
            'user_id': str(user_id),
            'login_time': str(int(time.time())),
            'role': 'user'
        }
        
        success = session_manager.create_session(session_id, user_data)
        return {'success': success}
    
    # Health check example
    def get_system_health():
        return health_checker.health_check()
    
    # Example usage
    print("=== Production Examples ===")
    
    # Test cache
    profile = get_user_profile(123)
    print(f"User profile: {profile}")
    
    # Test rate limiting
    rate_result = api_endpoint_with_rate_limit(123)
    print(f"Rate limit result: {rate_result}")
    
    # Test session
    login_result = login_user(123, 'sess_abc123')
    print(f"Login result: {login_result}")
    
    # Test health
    health = get_system_health()
    print(f"Health status: {health}")

# Run production examples
if __name__ == "__main__":
    production_examples()

# 10. Configuration Management
REDIS_CONFIG = {
    'development': {
        'host': 'localhost',
        'port': 6379,
        'db': 0,
        'max_connections': 5
    },
    'production': {
        'host': 'redis.production.com',
        'port': 6379,
        'db': 0,
        'password': 'secure_password',
        'max_connections': 50,
        'ssl': True,
        'ssl_cert_reqs': None
    }
}

# Environment-specific configuration
def get_redis_client(environment='development'):
    config = REDIS_CONFIG.get(environment, REDIS_CONFIG['development'])
    pool = redis.ConnectionPool(**config)
    return redis.Redis(connection_pool=pool, decode_responses=True)
```

## Summary

This comprehensive Redis tutorial covers:

- **Basic Operations**: Strings, Hashes, Lists, Sets, Sorted Sets
- **Advanced Features**: Pub/Sub, Transactions, Pipelining
- **Production Patterns**: Caching, Session Management, Rate Limiting
- **Error Handling**: Connection issues, Timeouts, Data validation
- **Best Practices**: Performance optimization, Monitoring, Security

### Key Takeaways

1. **Always use connection pools** in production applications
2. **Implement proper error handling** for network and data issues  
3. **Use consistent key naming conventions** for maintainability
4. **Leverage pipelining** for bulk operations to improve performance
5. **Set appropriate TTLs** to prevent memory bloat
6. **Monitor Redis health** and performance metrics
7. **Use transactions** for atomic operations when needed
8. **Implement rate limiting** to protect your application
9. **Handle serialization carefully** when storing complex data
10. **Plan for failover** and high availability in production

Redis is a powerful tool that can significantly improve your application's performance when used correctly. Follow these patterns and best practices to build robust, scalable applications with Redis and Python.

### Additional Resources

- [Redis Official Documentation](https://redis.io/documentation)
- [redis-py Documentation](https://redis-py.readthedocs.io/)
- [Redis Best Practices](https://redis.io/docs/manual/patterns/)
- [Redis Performance Tips](https://redis.io/docs/manual/optimization/)

---

*This tutorial provides a solid foundation for using Redis with Python. Practice with the examples and adapt them to your specific use cases.*
