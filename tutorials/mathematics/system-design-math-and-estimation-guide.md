# 🏗️ System Design Math and Back-of-the-Envelope Estimation

## How to Calculate Servers, Storage, and Bandwidth Without a Calculator

> **Welcome!** When software engineers design systems like Twitter, YouTube, or Uber, they don't start by writing code. They grab a napkin or whiteboard and do quick **"back-of-the-envelope" math** to estimate: *"How many servers will we need? How many terabytes of hard drive space will this use per month? Will our network explode under peak traffic?"*  
> This guide shows you the mental tricks, rules of thumb, and shortcuts used by senior engineers at top tech companies.

---

## Table of Contents

1. [What is Back-of-the-Envelope Estimation?](#what-is-back-of-the-envelope-estimation)
2. [The 4 Numbers Every Developer Must Memorize](#the-4-numbers-every-developer-must-memorize)
   - [Powers of Two (Bytes and Prefixes)](#powers-of-two-bytes-and-prefixes)
   - [The Seconds in a Day Trick (The 100K Shortcut)](#the-seconds-in-a-day-trick-the-100k-shortcut)
3. [Computer Latency: The Human Scale Comparison](#computer-latency-the-human-scale-comparison)
4. [Step-by-Step Sizing Formulas](#step-by-step-sizing-formulas)
   - [Traffic: Calculating Queries Per Second (QPS)](#traffic-calculating-queries-per-second-qps)
   - [Storage: Calculating Hard Drive Growth Over Time](#storage-calculating-hard-drive-growth-over-time)
   - [Bandwidth: Calculating Ingress and Egress Network Speed](#bandwidth-calculating-ingress-and-egress-network-speed)
5. [Real-World Walkthrough: Sizing a URL Shortener (Bitly)](#real-world-walkthrough-sizing-a-url-shortener-bitly)
6. [Everyday Developer Math: The Rule of 72 and Compounding](#everyday-developer-math-the-rule-of-72-and-compounding)
7. [Cheat Sheet and Quick Mental Multipliers](#cheat-sheet-and-quick-mental-multipliers)

---

## What is Back-of-the-Envelope Estimation?

Physicist Enrico Fermi was famous for estimating things accurately using only rough guesses and common sense (called **Fermi Problems**). For instance, he famously estimated the number of piano tuners in Chicago without looking at a phone book, and arrived at the exact answer within 5%!

In system design, **we don't need exact decimal precision**.
- Does our app need **1 server** or **1,000 servers**?
- Will our database hold **50 Gigabytes** or **50 Petabytes**?

Knowing the rough order of magnitude tells you whether a design is brilliant or impossible!

---

## The 4 Numbers Every Developer Must Memorize

### Powers of Two (Bytes and Prefixes)
In computer systems, memory doubles in powers of 2. Fortunately, powers of 2 closely match our familiar decimal thousands:

| Power of 2 | Exact Value | Rough Decimal Equivalent | Storage Prefix |
| :--- | :--- | :--- | :--- |
| $2^{10}$ | $1,024$ | $\approx 1,000$ (Thousand) | **1 Kilobyte (KB)** |
| $2^{20}$ | $1,048,576$ | $\approx 1,000,000$ (Million) | **1 Megabyte (MB)** |
| $2^{30}$ | $1,073,741,824$ | $\approx 1,000,000,000$ (Billion) | **1 Gigabyte (GB)** |
| $2^{40}$ | $1,099,511,627,776$ | $\approx 1,000,000,000,000$ (Trillion) | **1 Terabyte (TB)** |
| $2^{50}$ | $1.125 \times 10^{15}$ | $\approx 1,000,000,000,000,000$ (Quadrillion) | **1 Petabyte (PB)** |

> 💡 **The Mental Shortcut:**  
> - Millions $\longrightarrow$ Megabytes  
> - Billions $\longrightarrow$ Gigabytes  
> - Trillions $\longrightarrow$ Terabytes  

---

### The Seconds in a Day Trick (The 100K Shortcut)
How many seconds are in a single 24-hour day?
$$60\text{ seconds} \times 60\text{ minutes} \times 24\text{ hours} = 86,400\text{ seconds}$$

In your head, **round $86,400$ up to $100,000$ ($10^5$)**!
- If your app gets **10 million requests per day**:
$$\text{Average QPS} \approx \frac{10,000,000}{100,000} \approx 100\text{ requests per second!}$$
Dividing by $100,000$ is as simple as crossing off 5 zeros!

---

## Computer Latency: The Human Scale Comparison

Computers operate in nanoseconds ($10^{-9}\text{s}$) and milliseconds ($10^{-3}\text{s}$). Because human brains cannot comprehend a nanosecond, legendary computer scientist Peter Norvig created the **Human Scale Latency Comparison**:

> **Imagine $1$ CPU Cycle ($0.3$ nanoseconds) was equal to $1$ Human Second:**

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

> 💡 **What this teaches us:** Reading data from RAM is like walking down the hall. Going across the internet to another continent is like spending 15 years on an expedition! **Always cache frequently used data in local RAM (e.g. Redis)!**

---

## Step-by-Step Sizing Formulas

### Traffic: Calculating Queries Per Second (QPS)
$$\text{Average QPS} = \frac{\text{Daily Active Users (DAU)} \times \text{Average Requests per User}}{86,400\text{ (or } 100,000)}$$

$$\text{Peak QPS} \approx \text{Average QPS} \times 2\text{ (or } 3\times)$$
*(Traffic is never evenly spread throughout the day; lunchtime and evenings always see major surges!)*

---

### Storage: Calculating Hard Drive Growth Over Time
$$\text{Daily Storage} = \text{Daily Writes} \times \text{Average Payload Size}$$
$$\text{Annual Storage (1 Year)} = \text{Daily Storage} \times 365$$

---

### Bandwidth: Calculating Ingress and Egress Network Speed
Bandwidth is measured in **bits per second (bps)**, while files and storage are measured in **Bytes (B)**.
Remember: **$1\text{ Byte} = 8\text{ bits}$**!

$$\text{Bandwidth (Bytes/sec)} = \text{QPS} \times \text{Payload Size}$$
$$\text{Network Wire Bandwidth (bits/sec)} = \text{Bytes/sec} \times 8$$

---

## Real-World Walkthrough: Sizing a URL Shortener (Bitly)

Let's design a high-scale service like Bitly or TinyURL where users shorten long links.

### The Assumptions:
- **100 million** new URLs created per month.
- Read-to-Write ratio is **10:1** (for every 1 link created, it is clicked 10 times).
- Each URL record takes **500 Bytes** of database storage.
- We must store links for **5 years**.

---

### Step 1: Traffic Estimation (QPS)
- **Writes (New URLs):**
  - $100\text{ million} / \text{month} \approx \frac{100,000,000}{30 \times 86,400} \approx \frac{100\text{M}}{2.5\text{M seconds}} \approx \mathbf{40\text{ writes/sec}}$.
- **Reads (Redirects / Clicks):**
  - Read-to-Write is $10:1$, so: $40 \times 10 = \mathbf{400\text{ reads/sec}}$.
  - **Peak Read QPS:** $400 \times 2 = \mathbf{800\text{ requests/sec}}$.
  *Conclusion: A single modern web server or Node/Go instance can easily handle 800 QPS!*

---

### Step 2: Storage Estimation (5 Years)
- New links in 5 years:
  - $100\text{ million/month} \times 12\text{ months} \times 5\text{ years} = \mathbf{6\text{ billion URLs}}$.
- Storage needed:
  - $6,000,000,000\text{ records} \times 500\text{ Bytes} = 3,000,000,000,000\text{ Bytes} = \mathbf{3\text{ Terabytes}}$.
  *Conclusion: 3TB easily fits onto a single modern NVMe SSD drive!*

---

### Step 3: Cache / RAM Memory Estimation (80/20 Rule)
The Pareto Principle (80/20 Rule) states that **20% of the links generate 80% of the read traffic**.  
We want to cache that hot 20% in RAM (Redis) so we don't hammer our disk database:

- Daily Read Volume: $400\text{ requests/sec} \times 86,400\text{ sec} \approx 35\text{ million clicks/day}$.
- Total data read per day: $35\text{ million} \times 500\text{ Bytes} \approx 17.5\text{ GB/day}$.
- Cache 20% of daily traffic:
$$17.5\text{ GB} \times 0.20 = \mathbf{3.5\text{ GB of RAM}}$$
*Conclusion: A standard 8GB RAM Redis instance will cache 80% of all traffic, making the system blazing fast!*

---

## Everyday Developer Math: The Rule of 72 and Compounding

### The Rule of 72 (How Long Until It Doubles?)
If your user base, API requests, or money grows at a steady percentage each year, **divide 72 by the growth rate to find when it will DOUBLE**:

$$\text{Years to Double} \approx \frac{72}{\text{Growth Rate \%}}$$

- At **$10\%$ annual growth**: $72 \div 10 = \mathbf{7.2\text{ years}}$ to double.
- At **$24\%$ annual growth**: $72 \div 24 = \mathbf{3\text{ years}}$ to double!

---

### The Asymmetric Percentages Trap
If your website traffic drops by **$50\%$**, how much must it grow to return to normal?  
Most people instinctively say *"50%"*.  
**WRONG!**
- If you have 100 users, and drop 50%, you now have **50 users**.
- If 50 users grow by 50%, you only have **75 users**!
- You need a **$100\%$ gain** just to get back to where you started!

| Drop in Traffic | Gain Needed to Recover |
| :--- | :--- |
| **$-10\%$** | $+11\%$ |
| **$-20\%$** | $+25\%$ |
| **$-50\%$** | **$+100\%$ (Doubling!)** |
| **$-90\%$** | **$+900\%$ (10x growth!)** |

---

## Cheat Sheet and Quick Mental Multipliers

```
Mental Multipliers:
- Bytes to Bits: Multiply by 8 (e.g., 100 MB/s = 800 Mbps).
- Daily to Per-Second: Divide by 100,000 (drop 5 zeros).
- Monthly to Per-Second: Divide by 2,500,000.
- Million * KB = Gigabyte
- Million * MB = Terabyte
- Billion * KB = Terabyte
- Billion * MB = Petabyte
```

### 💪 Test Yourself!
1. Your app receives 50 million API calls every day. What is the approximate average QPS?  
   *(Answer: $50,000,000 \div 100,000 = \mathbf{500\text{ requests per second}}$!)*
2. If your database stores 1 billion user profile photos, and each photo is 1 MB, how much disk space is that?  
   *(Answer: $1\text{ Billion} \times 1\text{ MB} = \mathbf{1\text{ Petabyte}}$!)*
3. If an API response time improves from 200ms to 50ms, what is the speedup?  
   *(Answer: $\frac{200}{50} = \mathbf{4\times\text{ faster}}$ (or a 75% reduction in latency)!)*
