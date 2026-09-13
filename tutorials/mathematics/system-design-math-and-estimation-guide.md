# System Design Math and Back-of-the-Envelope Estimation

## How to Calculate Servers, Storage, and Bandwidth with Python Code

> **Welcome!** When software engineers design systems like Twitter, YouTube, or Uber, they don't start by writing complex microservices. They do quick **"back-of-the-envelope" math** to estimate: *"How many servers will we need? How many terabytes of hard drive space will this use per month? Will our network explode under peak traffic?"*  
> This guide provides clean, runnable Python code examples and mental shortcuts to calculate traffic, storage, memory, and bandwidth in seconds.

---

## Table of Contents

1. [What is Back-of-the-Envelope Estimation?](#what-is-back-of-the-envelope-estimation)
2. [The 4 Numbers Every Developer Must Memorize](#the-4-numbers-every-developer-must-memorize)
   - [Powers of Two in Python](#powers-of-two-in-python)
   - [The Seconds in a Day Trick (The 100K Shortcut)](#the-seconds-in-a-day-trick-the-100k-shortcut)
3. [Computer Latency: The Human Scale Comparison](#computer-latency-the-human-scale-comparison)
4. [Step-by-Step Sizing Formulas in Python](#step-by-step-sizing-formulas-in-python)
   - [Traffic: Calculating Queries Per Second (QPS)](#traffic-calculating-queries-per-second-qps)
   - [Storage: Calculating Hard Drive Growth Over Time](#storage-calculating-hard-drive-growth-over-time)
   - [Bandwidth: Calculating Ingress and Egress Network Speed](#bandwidth-calculating-ingress-and-egress-network-speed)
5. [Real-World Walkthrough: Sizing Bitly in Python](#real-world-walkthrough-sizing-bitly-in-python)
6. [Everyday Developer Math: Compounding and Percentage Traps](#everyday-developer-math-compounding-and-percentage-traps)
7. [The Complete Python System Sizing Toolkit](#the-complete-python-system-sizing-toolkit)
8. [Cheat Sheet and Practice Quiz](#cheat-sheet-and-practice-quiz)

---

## What is Back-of-the-Envelope Estimation?

Physicist Enrico Fermi was famous for estimating things accurately using only rough guesses and common sense (called **Fermi Problems**). For instance, he famously estimated the number of piano tuners in Chicago without looking at a phone book, and arrived at the exact answer within 5%!

In system design, **we don't need exact decimal precision**:
- Does our app need **1 server** or **1,000 servers**?
- Will our database hold **50 Gigabytes** or **50 Petabytes**?

Knowing the rough order of magnitude tells you whether a proposed architecture is brilliant or impossible before writing a single line of application code.

---

## The 4 Numbers Every Developer Must Memorize

### Powers of Two in Python
In computer systems, memory doubles in powers of 2. In Python, you can calculate and format these numbers effortlessly using bit shifts (`1 << n`) or exponentiation (`2 ** n`):

```python
# Powers of 2 conversion reference in Python
KB = 1024
MB = 1024 * KB
GB = 1024 * MB
TB = 1024 * GB
PB = 1024 * TB

powers = [
    ("1 KB", 2**10, "~1,000 (Thousand)"),
    ("1 MB", 2**20, "~1,000,000 (Million)"),
    ("1 GB", 2**30, "~1,000,000,000 (Billion)"),
    ("1 TB", 2**40, "~1,000,000,000,000 (Trillion)"),
    ("1 PB", 2**50, "~1,000,000,000,000,000 (Quadrillion)"),
]

print(f"{'Prefix':<8} | {'Exact Bytes':<18} | {'Decimal Approximation'}")
print("-" * 55)
for prefix, exact, approx in powers:
    print(f"{prefix:<8} | {exact:<18,d} | {approx}")
```

**Output:**
```text
Prefix   | Exact Bytes        | Decimal Approximation
-------------------------------------------------------
1 KB     | 1,024              | ~1,000 (Thousand)
1 MB     | 1,048,576          | ~1,000,000 (Million)
1 GB     | 1,073,741,824      | ~1,000,000,000 (Billion)
1 TB     | 1,099,511,627,776  | ~1,000,000,000,000 (Trillion)
1 PB     | 1,125,899,906,842,624 | ~1,000,000,000,000,000 (Quadrillion)
```

> 💡 **The Mental Shortcut:**  
> - Millions → Megabytes (10⁶)  
> - Billions → Gigabytes (10⁹)  
> - Trillions → Terabytes (10¹²)  

---

### The Seconds in a Day Trick (The 100K Shortcut)
How many seconds are in a single 24-hour day?

```python
seconds_per_day = 60 * 60 * 24
print(f"Exact seconds per day: {seconds_per_day:,}") # 86,400

# The Back-of-the-Envelope Shortcut: Round up to 100,000
approx_seconds = 100_000

daily_requests = 10_000_000 # 10 Million requests per day
avg_qps = daily_requests / approx_seconds

print(f"Estimated QPS: {avg_qps:.0f} req/sec") # 100 req/sec
```

In your head, dividing by 100,000 is as simple as **crossing off 5 zeros**!

---

## Computer Latency: The Human Scale Comparison

Computers operate in nanoseconds (10⁻⁹s) and milliseconds (10⁻³s). Because human brains cannot comprehend a nanosecond, legendary computer scientist Peter Norvig created the **Human Scale Latency Comparison**:

> **Imagine 1 CPU Cycle (0.3 nanoseconds) was equal to 1 Human Second:**

```
Hardware Action                Actual Latency       Scaled to Human Time
-------------------------------------------------------------------------
L1 Cache Reference             0.5 ns               1.5 seconds (A heartbeat)
L2 Cache Reference             7 ns                 21 seconds (A brief pause)
RAM Memory Read                100 ns               5 minutes (Walking to kitchen)
NVMe SSD Read                  16,000 ns (16 µs)    15 hours (A full workday)
Rotational Hard Disk Seek      4,000,000 ns (4 ms)  4.5 months (A semester at college)
Send Packet from NY to London  150,000,000 ns       15 YEARS (An entire generation!)
```

```python
# Scaling hardware latency to human seconds in Python
ONE_CPU_CYCLE_NS = 0.3 # 0.3 ns = 1 human second

latencies_ns = {
    "L1 Cache": 0.5,
    "L2 Cache": 7.0,
    "RAM Read": 100.0,
    "NVMe SSD": 16_000.0,
    "HDD Seek": 4_000_000.0,
    "NY to London (Internet)": 150_000_000.0,
}

for name, ns in latencies_ns.items():
    human_seconds = ns / ONE_CPU_CYCLE_NS
    if human_seconds < 60:
        human_str = f"{human_seconds:.1f} seconds"
    elif human_seconds < 3600:
        human_str = f"{human_seconds / 60:.1f} minutes"
    elif human_seconds < 86400:
        human_str = f"{human_seconds / 3600:.1f} hours"
    elif human_seconds < 31_536_000:
        human_str = f"{human_seconds / 86400:.1f} days"
    else:
        human_str = f"{human_seconds / 31_536_000:.1f} YEARS"
    print(f"{name:<25}: {human_str}")
```

> 💡 **What this teaches us:** Reading data from RAM is like walking down the hall. Going across the internet to another continent is like spending 15 years on an expedition! **Always cache frequently accessed data in local RAM (e.g. Redis)!**

---

## Step-by-Step Sizing Formulas in Python

### Traffic: Calculating Queries Per Second (QPS)

Traffic is never spread evenly throughout the day. Lunchtime and evenings always see major traffic surges. We use a **Peak Factor of 2× to 3×**:

```python
def estimate_traffic(daily_active_users: int, requests_per_user: int, peak_factor: float = 2.0):
    """Calculate average and peak queries per second (QPS)."""
    total_daily_requests = daily_active_users * requests_per_user
    
    # 86,400 seconds in a day
    avg_qps = total_daily_requests / 86_400
    peak_qps = avg_qps * peak_factor
    
    return {
        "daily_requests": total_daily_requests,
        "avg_qps": round(avg_qps, 1),
        "peak_qps": round(peak_qps, 1),
    }

# Example: 10 Million users making 20 requests per day
traffic = estimate_traffic(daily_active_users=10_000_000, requests_per_user=20)
print(f"Daily Requests: {traffic['daily_requests']:,}")
print(f"Average QPS:    {traffic['avg_qps']:,} req/sec")
print(f"Peak QPS (2x):  {traffic['peak_qps']:,} req/sec")
```

**Output:**
```text
Daily Requests: 200,000,000
Average QPS:    2,314.8 req/sec
Peak QPS (2x):  4,629.6 req/sec
```

---

### Storage: Calculating Hard Drive Growth Over Time

```python
def estimate_storage(daily_writes: int, payload_bytes: int, years: int = 1):
    """Calculate storage growth per day, per year, and over N years."""
    bytes_per_day = daily_writes * payload_bytes
    bytes_per_year = bytes_per_day * 365
    total_bytes = bytes_per_year * years
    
    gb = 1024 ** 3
    tb = 1024 ** 4
    
    return {
        "daily_gb": round(bytes_per_day / gb, 2),
        "annual_tb": round(bytes_per_year / tb, 2),
        "total_tb": round(total_bytes / tb, 2),
    }

# Example: 50 million writes per day, each record is 500 bytes, for 5 years
storage = estimate_storage(daily_writes=50_000_000, payload_bytes=500, years=5)
print(f"Daily Storage:    {storage['daily_gb']} GB/day")
print(f"Annual Storage:   {storage['annual_tb']} TB/year")
print(f"5-Year Storage:   {storage['total_tb']} TB total")
```

**Output:**
```text
Daily Storage:    23.28 GB/day
Annual Storage:   8.3 TB/year
5-Year Storage:   41.49 TB total
```

---

### Bandwidth: Calculating Ingress and Egress Network Speed

Remember: **1 Byte = 8 bits**. Internet bandwidth from cloud providers (AWS, GCP) is billed and measured in **bits per second (Mbps / Gbps)**:

```python
def estimate_bandwidth(qps: float, payload_bytes: int):
    """Convert QPS and payload size into network bandwidth (Mbps and MB/s)."""
    bytes_per_sec = qps * payload_bytes
    bits_per_sec = bytes_per_sec * 8
    
    # Megabytes per second
    mb_per_sec = bytes_per_sec / (1024 * 1024)
    # Megabits per second (Wire speed)
    mbps = bits_per_sec / 1_000_000
    
    return {
        "mb_per_sec": round(mb_per_sec, 2),
        "mbps": round(mbps, 2),
    }

# Example: 5,000 QPS with 2KB payload per request
bandwidth = estimate_bandwidth(qps=5000, payload_bytes=2048)
print(f"Throughput:    {bandwidth['mb_per_sec']} MB/s")
print(f"Network Wire:  {bandwidth['mbps']} Mbps")
```

**Output:**
```text
Throughput:    9.77 MB/s
Network Wire:  81.92 Mbps
```

---

## Real-World Walkthrough: Sizing Bitly in Python

Let's design a high-scale URL shortener like **Bitly** or **TinyURL** completely in Python.

### The System Requirements:
- **100 million** new URLs created per month.
- Read-to-Write ratio is **10:1** (every link is clicked 10 times on average).
- Each URL database record takes **500 Bytes**.
- Links must be retained for **5 years**.
- 80% of read traffic goes to 20% of hot links (**Pareto 80/20 Rule**).

### The Complete Sizing Script:

```python
# Bitly System Sizing Calculator
MONTHS_PER_YEAR = 12
SECONDS_PER_DAY = 86_400
DAYS_PER_MONTH = 30

# 1. Inputs
monthly_new_urls = 100_000_000
read_to_write_ratio = 10
bytes_per_record = 500
retention_years = 5

# 2. Traffic Calculations
daily_writes = monthly_new_urls / DAYS_PER_MONTH
write_qps = daily_writes / SECONDS_PER_DAY

daily_reads = daily_writes * read_to_write_ratio
read_qps = daily_reads / SECONDS_PER_DAY
peak_read_qps = read_qps * 2.0

# 3. Storage Calculations (5 Years)
total_records = monthly_new_urls * MONTHS_PER_YEAR * retention_years
total_storage_bytes = total_records * bytes_per_record
total_storage_tb = total_storage_bytes / (1024 ** 4)

# 4. Cache Memory (80/20 Rule)
# Cache 20% of daily read requests in Redis RAM
daily_read_bytes = daily_reads * bytes_per_record
cache_ram_bytes = daily_read_bytes * 0.20
cache_ram_gb = cache_ram_bytes / (1024 ** 3)

# 5. Display System Architecture Summary
print("=" * 50)
print("       BITLY SYSTEM DESIGN SIZING REPORT")
print("=" * 50)
print(f"New URLs per Second (Write QPS):  {write_qps:>8.1f} writes/sec")
print(f"Average Click QPS (Read QPS):     {read_qps:>8.1f} reads/sec")
print(f"Peak Click QPS (2x Surges):       {peak_read_qps:>8.1f} reads/sec")
print("-" * 50)
print(f"Total 5-Year URL Records:         {total_records:>8,d} URLs")
print(f"Total 5-Year Disk Storage:        {total_storage_tb:>8.2f} Terabytes")
print("-" * 50)
print(f"Daily Read Data Volume:           {daily_read_bytes / (1024**3):>8.2f} GB/day")
print(f"Redis RAM Cache Required (20%):   {cache_ram_gb:>8.2f} GB of RAM")
print("=" * 50)
```

**Output:**
```text
==================================================
       BITLY SYSTEM DESIGN SIZING REPORT
==================================================
New URLs per Second (Write QPS):      38.6 writes/sec
Average Click QPS (Read QPS):        385.8 reads/sec
Peak Click QPS (2x Surges):          771.6 reads/sec
--------------------------------------------------
Total 5-Year URL Records:        6,000,000,000 URLs
Total 5-Year Disk Storage:             2.73 Terabytes
--------------------------------------------------
Daily Read Data Volume:               15.52 GB/day
Redis RAM Cache Required (20%):        3.10 GB of RAM
==================================================
```

### Architectural Takeaways:
1. **Server Capacity:** At ~770 peak QPS, **a single Go, Node.js, or Fastify server** can easily handle the entire read load! Two instances behind a load balancer provide complete high availability.
2. **Storage:** 2.73 TB of storage over 5 years easily fits onto a single **NVMe SSD drive** costing under $200!
3. **RAM Cache:** Caching 20% of daily requests requires only **3.1 GB of RAM**. A standard $20/month Redis container will handle 80% of all queries from RAM with sub-millisecond response times!

---

## Everyday Developer Math: Compounding and Percentage Traps

### The Rule of 72 in Python
If your API traffic or user base grows at a steady annual percentage, **divide 72 by the growth rate** to find how many years it will take to double:

```python
def years_to_double(growth_rate_percent: float) -> float:
    """Calculate doubling time using the Rule of 72."""
    if growth_rate_percent <= 0:
        return float('inf')
    return 72.0 / growth_rate_percent

for rate in [5, 10, 15, 24, 50, 72]:
    doubling_time = years_to_double(rate)
    print(f"At {rate:>2}% annual growth -> Doubles in {doubling_time:>4.1f} years")
```

**Output:**
```text
At  5% annual growth -> Doubles in 14.4 years
At 10% annual growth -> Doubles in  7.2 years
At 15% annual growth -> Doubles in  4.8 years
At 24% annual growth -> Doubles in  3.0 years
At 50% annual growth -> Doubles in  1.4 years
At 72% annual growth -> Doubles in  1.0 years
```

---

### The Asymmetric Percentages Trap in Python

If your website traffic or server budget drops by **50%**, how much must it gain to return to its original number? Most people say *"50%"*. In Python, we can simulate why that is mathematically wrong:

```python
def recovery_gain_needed(drop_percent: float) -> float:
    """Calculate the exact percentage gain needed to recover from a drop."""
    # If starting value is 100, new value is 100 - drop
    remaining = 100.0 - drop_percent
    if remaining <= 0:
        return float('inf')
    # Required gain: (100 - remaining) / remaining * 100
    gain_needed = (drop_percent / remaining) * 100.0
    return gain_needed

drops = [10, 20, 30, 50, 75, 90]

print(f"{'Traffic Drop':<15} | {'Gain Needed to Recover'}")
print("-" * 40)
for drop in drops:
    gain = recovery_gain_needed(drop)
    print(f"-{drop}%{'':<11} | +{gain:.1f}%")
```

**Output:**
```text
Traffic Drop    | Gain Needed to Recover
----------------------------------------
-10%            | +11.1%
-20%            | +25.0%
-30%            | +42.9%
-50%            | +100.0% (Doubling!)
-75%            | +300.0% (4x gain!)
-90%            | +900.0% (10x gain!)
```

---

## The Complete Python System Sizing Toolkit

Save this script as `system_calc.py` to calculate system design metrics directly from your terminal:

```python
#!/usr/bin/env python3
"""
system_calc.py - Back-of-the-envelope system design sizing calculator.
"""

def system_summary(dau: int, writes_per_user: int, reads_per_user: int, payload_bytes: int):
    daily_writes = dau * writes_per_user
    daily_reads = dau * reads_per_user
    
    write_qps = daily_writes / 86_400
    read_qps = daily_reads / 86_400
    peak_read_qps = read_qps * 2.5
    
    daily_storage_gb = (daily_writes * payload_bytes) / (1024 ** 3)
    annual_storage_tb = (daily_writes * payload_bytes * 365) / (1024 ** 4)
    cache_ram_gb = ((daily_reads * payload_bytes) * 0.20) / (1024 ** 3)
    
    network_mbps = (read_qps * payload_bytes * 8) / 1_000_000

    print("\n" + "=" * 45)
    print("      SYSTEM DESIGN SIZING SUMMARY")
    print("=" * 45)
    print(f"Daily Active Users (DAU): {dau:>14,d}")
    print(f"Write QPS:                {write_qps:>14.1f} writes/s")
    print(f"Average Read QPS:         {read_qps:>14.1f} reads/s")
    print(f"Peak Read QPS (2.5x):     {peak_read_qps:>14.1f} reads/s")
    print("-" * 45)
    print(f"Daily Storage:            {daily_storage_gb:>14.2f} GB/day")
    print(f"Annual Storage:           {annual_storage_tb:>14.2f} TB/year")
    print(f"Recommended Redis RAM:    {cache_ram_gb:>14.2f} GB")
    print(f"Outbound Bandwidth:       {network_mbps:>14.2f} Mbps")
    print("=" * 45 + "\n")

if __name__ == "__main__":
    # Example: Photo-sharing app with 5M DAU, 2 photos uploaded/day, 50 viewed/day
    system_summary(
        dau=5_000_000,
        writes_per_user=2,
        reads_per_user=50,
        payload_bytes=200_000 # 200 KB per photo thumbnail
    )
```

---

## Cheat Sheet and Practice Quiz

```python
# Quick Python Mental Multipliers Reference
"""
Bytes to Bits:       Multiply by 8 (100 MB/s = 800 Mbps wire speed)
Daily to Per-Second: Divide by 100,000 (drop 5 zeros)
Monthly to Second:   Divide by 2,500,000
Million * KB:        = 1 Gigabyte
Million * MB:        = 1 Terabyte
Billion * KB:        = 1 Terabyte
Billion * MB:        = 1 Petabyte
"""
```

### 💪 Test Yourself!
1. Your app receives 50 million API calls every day. What is the approximate average QPS?  
   ```python
   # 50,000,000 / 100,000 (drop 5 zeros)
   print(50_000_000 / 100_000) # 500 req/sec!
   ```
2. If your database stores 1 billion user profile photos, and each photo is 1 MB, how much disk space is that?  
   ```python
   # 1 Billion * 1 MB = 1 Petabyte (PB)!
   print(f"{(1_000_000_000 * 1024 * 1024) / (1024**5):.0f} PB") # 1 PB
   ```
3. If an API response time improves from 200ms to 50ms, what is the speedup?  
   ```python
   # 200 / 50 = 4x faster!
   print(f"{200 / 50}x faster (a {((200-50)/200)*100:.0f}% latency reduction)")
   ```
