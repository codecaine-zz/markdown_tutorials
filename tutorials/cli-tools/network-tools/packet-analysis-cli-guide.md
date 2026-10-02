# Packet Analysis CLI Guide: `tcpdump` & `tshark`

Packet sniffing and deep packet inspection (DPI) from the command line are essential skills for diagnosing API latency, dropped connections, TLS handshake negotiations, and malicious network traffic.

---

## 📚 Table of Contents

1. [Tool Comparison: `tcpdump` vs. `tshark`](#1-tool-comparison-tcpdump-vs-tshark)
2. [Prerequisites & Installation](#2-prerequisites--installation)
3. [Mastering `tcpdump` Basics & Berkeley Packet Filters (BPF)](#3-mastering-tcpdump-basics--berkeley-packet-filters-bpf)
4. [Saving & Inspecting PCAP Capture Files](#4-saving--inspecting-pcap-capture-files)
5. [Deep Protocol Dissection with `tshark` (Wireshark CLI)](#5-deep-protocol-dissection-with-tshark-wireshark-cli)
6. [Extracting Structured Fields (`-T fields`)](#6-extracting-structured-fields--t-fields)
7. [Inspecting TLS Handshakes & Server Name Indication (SNI)](#7-inspecting-tls-handshakes--server-name-indication-sni)
8. [Everyday Troubleshooting Recipes](#8-everyday-troubleshooting-recipes)

---

## 1. Tool Comparison: `tcpdump` vs. `tshark`

| Feature | `tcpdump` | `tshark` |
| :--- | :--- | :--- |
| **Footprint** | Pre-installed on almost all Unix/BSD/Linux systems | Requires Wireshark suite |
| **Filter Syntax** | Berkeley Packet Filter (BPF) | BPF (capture) + Wireshark Display Filters (read) |
| **Dissection Depth** | L2 - L4 (Ethernet, IP, TCP, UDP, ICMP, DNS) | L2 - L7 (HTTP/2, gRPC, TLS, QUIC, WebSocket) |
| **JSON/CSV Export** | Manual regex/awk | Native `-T json` and `-T fields` |
| **Best For** | Low-overhead production captures & quick checks | In-depth protocol analysis & forensic dissection |

---

## 2. Prerequisites & Installation

```bash
# macOS
brew install wireshark   # Installs tshark CLI

# Ubuntu / Debian
sudo apt update && sudo apt install -y tcpdump tshark
```

> [!NOTE]
> Capturing packets requires administrative access (`sudo` or assigning `CAP_NET_RAW` / `CAP_NET_ADMIN` capabilities on Linux).

---

## 3. Mastering `tcpdump` Basics & Berkeley Packet Filters (BPF)

### List Available Interfaces

```bash
sudo tcpdump -D
```

### Essential Capture Flags

```bash
# Syntax: tcpdump -n (no DNS lookup) -nn (no port name lookup) -i (interface) -v (verbose)
sudo tcpdump -i any -nn -v
```

### Powerful BPF Filter Expressions

```bash
# 1. Filter by host IP
sudo tcpdump -i any -nn host 1.1.1.1

# 2. Filter by port (or port range)
sudo tcpdump -i any -nn port 443
sudo tcpdump -i any -nn portrange 8000-8080

# 3. Filter only DNS traffic (UDP port 53)
sudo tcpdump -i any -nn udp port 53

# 4. Filter only TCP SYN packets (detect new connection attempts / port scans)
sudo tcpdump -i any -nn "tcp[tcpflags] & tcp-syn != 0 and tcp[tcpflags] & tcp-ack == 0"

# 5. Filter TCP RST packets (detect rejected / aborted connections)
sudo tcpdump -i any -nn "tcp[tcpflags] & tcp-rst != 0"
```

---

## 4. Saving & Inspecting PCAP Capture Files

Never attempt to read live high-throughput traffic in your terminal. Capture packets to a `.pcap` file and analyze them offline:

```bash
# Capture 10,000 packets on interface en0 and write to trace.pcap
sudo tcpdump -i en0 -c 10000 -w trace.pcap

# Read and print the captured packets
tcpdump -nn -r trace.pcap
```

---

## 5. Deep Protocol Dissection with `tshark` (Wireshark CLI)

`tshark` provides the full analytical power of Wireshark without requiring a graphical display:

```bash
# Live capture with display filter for HTTP GET requests
sudo tshark -i any -Y "http.request.method == GET"

# Read from saved trace with display filter for DNS failures
tshark -r trace.pcap -Y "dns.flags.rcode != 0"
```

---

## 6. Extracting Structured Fields (`-T fields`)

Extract exact packet metadata formatted as TSV/CSV or JSON for piping into `jq`, `awk`, or database ingestion:

### Extract DNS Queries and Responses

```bash
tshark -r trace.pcap -Y "dns.flags.response == 1" \
  -T fields -e frame.time -e ip.src -e dns.qry.name -e dns.a
```

### Extract HTTP Requests & User Agents

```bash
tshark -r trace.pcap -Y "http.request" \
  -T fields -e ip.src -e http.request.method -e http.host -e http.request.uri -e http.user_agent
```

### Export Capture as Structured JSON

```bash
tshark -r trace.pcap -c 5 -T json | jq '.[0]._source.layers.ip'
```

---

## 7. Inspecting TLS Handshakes & Server Name Indication (SNI)

Even when HTTPS payload traffic is encrypted, the initial TLS Client Hello negotiation broadcasts the requested domain name in plaintext via **Server Name Indication (SNI)**:

```bash
# Extract all domains being queried via HTTPS in real-time
sudo tshark -i any -Y "tls.handshake.type == 1" \
  -T fields -e ip.src -e ip.dst -e tls.handshake.extensions_server_name
```

---

## 8. Everyday Troubleshooting Recipes

### Detect TCP Retransmissions (High Packet Loss / Congestion)

```bash
tshark -r trace.pcap -Y "tcp.analysis.retransmission" \
  -T fields -e frame.number -e ip.src -e ip.dst -e tcp.analysis.retransmission
```

### Measure HTTP Round-Trip Time (Latency Bottlenecks)

```bash
tshark -r trace.pcap -Y "http.time" \
  -T fields -e http.request.uri -e http.time | sort -k2 -n -r | head -n 10
```

### Monitor ICMP Ping & Unreachable Responses

```bash
sudo tcpdump -i any -nn "icmp or icmp6"
```
