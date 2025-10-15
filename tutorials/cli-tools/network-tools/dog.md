# dog: Modern DNS Client with Homebrew

## Table of Contents

1. [Overview](#overview)
2. [Install](#install)
3. [Basic Queries](#basic-queries)
4. [Record Types](#record-types)
5. [Name Servers and Transport](#name-servers-and-transport)
6. [JSON and Pretty Output](#json-and-pretty-output)
7. [Common Tasks](#common-tasks)
8. [Troubleshooting](#troubleshooting)

## Overview

`dog` is a user-friendly DNS client, a modern alternative to `dig`. It features readable output, color, and useful defaults.

## Install

```bash
brew install dog
dog --version
```

## Basic Queries

```bash
dog example.com
dog example.com A
dog example.com AAAA
```

## Record Types

```bash
dog example.com MX
dog example.com TXT
dog example.com NS
dog _sip._tcp.example.com SRV
```

## Name Servers and Transport

```bash
# Query a specific DNS server
dog example.com @1.1.1.1

# Use TCP or DoH (DNS over HTTPS)
dog --tcp example.com
dog --https https://cloudflare-dns.com/dns-query example.com
```

## JSON and Pretty Output

```bash
# JSON for scripting
dog --json example.com A | jq .

# Short answers only
dog --short example.com A
```

## Common Tasks

```bash
# Check domain mail setup
dog example.com MX
dog example.com TXT | grep -i spf

# Trace resolution path
dog --trace example.com

# Reverse lookup
dog 8.8.8.8 PTR
```

## Troubleshooting

- Timeouts: choose a different resolver `@1.1.1.1` or add `--timeout 5`
- No AAAA: the domain might not have IPv6 records
- Empty results: verify DNS propagation and TTL

