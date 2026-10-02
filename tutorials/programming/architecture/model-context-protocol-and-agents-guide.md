# Model Context Protocol (MCP) & AI Agent Architecture Guide

The **Model Context Protocol (MCP)** is an open standard designed by Anthropic and open-source contributors that standardizes how AI applications and agents securely access external context, enterprise databases, developer tools, and API environments.

---

## 📚 Table of Contents

1. [Why MCP? Eliminating Integration Fragmentation](#1-why-mcp-eliminating-integration-fragmentation)
2. [MCP Architecture & Roles (Hosts, Clients & Servers)](#2-mcp-architecture--roles-hosts-clients--servers)
3. [The Three MCP Primitives: Tools, Resources & Prompts](#3-the-three-mcp-primitives-tools-resources--prompts)
4. [Transport Mechanisms: Stdio vs. SSE](#4-transport-mechanisms-stdio-vs-sse)
5. [Building an MCP Server with TypeScript / Node.js](#5-building-an-mcp-server-with-typescript--nodejs)
6. [Building an MCP Server with Python (`FastMCP`)](#6-building-an-mcp-server-with-python-fastmcp)
7. [Configuring Host Environments (Claude, IDEs & Agents)](#7-configuring-host-environments-claude-ides--agents)
8. [Security, Guardrails & Human-in-the-Loop](#8-security-guardrails--human-in-the-loop)

---

## 1. Why MCP? Eliminating Integration Fragmentation

Prior to MCP, connecting LLMs to data sources required custom integrations for every AI client (ChatGPT Plugins, LangChain tools, custom IDE plugins). 

MCP establishes a universal **USB-C-like standard** for AI agents. An MCP server written once for PostgreSQL or GitHub can be consumed interchangeably by Claude Desktop, Antigravity IDE, CLI agents, and custom enterprise bots.

```text
[ AI Host: Claude / IDE / Agent ]
           │
           │ JSON-RPC 2.0 (stdio or SSE)
           ▼
[ MCP Server: Database / Filesystem / APIs ]
           │
           ▼
[ External Systems: Postgres, AWS, GitHub, Shell ]
```

---

## 2. MCP Architecture & Roles

- **MCP Host**: The coordinating application that users interact with (e.g., Claude Desktop, Antigravity IDE, custom AI chat UI).
- **MCP Client**: The internal layer inside the host maintaining 1:1 connections with individual MCP servers.
- **MCP Server**: A standalone lightweight process or service exposing structured tools, resources, and prompt templates.

---

## 3. The Three MCP Primitives

1. **Tools**: Functions that the LLM can decide to execute (with JSON Schema parameters) to produce side-effects or query state (e.g. `run_sql_query`, `send_slack_message`).
2. **Resources**: Read-only data payloads attached to conversation context (e.g. file contents, system logs, live database schemas).
3. **Prompts**: User-accessible reusable prompt templates and workflows that guide LLM interactions (e.g. `/code-review`, `/explain-architecture`).

---

## 4. Transport Mechanisms: Stdio vs. SSE

- **Standard Input/Output (`stdio`)**: The host spawns the MCP server as a local child subprocess and communicates over `stdin` and `stdout`. Fast, secure, and ideal for local developer tools.
- **Server-Sent Events (`SSE`)**: The host connects to a remote MCP server over HTTP/HTTPS with bidirectional SSE and HTTP POST. Ideal for cloud-hosted microservices and shared enterprise tools.

---

## 5. Building an MCP Server with TypeScript / Node.js

### Initialize Project

```bash
mkdir mcp-git-server && cd mcp-git-server
npm init -y
npm install @modelcontextprotocol/sdk zod
npm install -D typescript @types/node
npx tsc --init
```

### Server Code (`src/index.ts`)

```typescript
import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import {
  CallToolRequestSchema,
  ListToolsRequestSchema,
} from "@modelcontextprotocol/sdk/types.js";
import { z } from "zod";
import { execSync } from "child_process";

const server = new Server(
  {
    name: "mcp-git-helper",
    version: "1.0.0",
  },
  {
    capabilities: {
      tools: {},
    },
  }
);

// 1. List available tools
server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
      {
        name: "get_git_status",
        description: "Returns the current git status of the active repository",
        inputSchema: {
          type: "object",
          properties: {},
        },
      },
      {
        name: "git_log_summary",
        description: "Returns the last N git commits",
        inputSchema: {
          type: "object",
          properties: {
            count: { type: "number", description: "Number of commits to retrieve", default: 5 },
          },
        },
      },
    ],
  };
});

// 2. Handle tool invocation
server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;

  if (name === "get_git_status") {
    const status = execSync("git status --short").toString();
    return {
      content: [{ type: "text", text: status || "Working tree clean." }],
    };
  }

  if (name === "git_log_summary") {
    const count = Number(args?.count) || 5;
    const log = execSync(`git log -n ${count} --oneline`).toString();
    return {
      content: [{ type: "text", text: log }],
    };
  }

  throw new Error(`Unknown tool: ${name}`);
});

// 3. Connect via Standard I/O Transport
const transport = new StdioServerTransport();
await server.connect(transport);
```

---

## 6. Building an MCP Server with Python (`FastMCP`)

Python offers `FastMCP` for defining tools and resources using native Python type hints:

```bash
pip install mcp
```

### Server Code (`server.py`)

```python
from mcp.server.fastmcp import FastMCP
import sqlite3

# Initialize MCP server
mcp = FastMCP("SQLite Analytics Engine")

DB_PATH = "analytics.db"

@mcp.tool()
def query_database(sql: str) -> str:
    """Execute a read-only SELECT query against the analytics database."""
    if not sql.strip().upper().startswith("SELECT"):
        return "Error: Only read-only SELECT queries are permitted."
    
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    try:
        cursor.execute(sql)
        rows = cursor.fetchall()
        return str(rows)
    except Exception as e:
        return f"Query failed: {str(e)}"
    finally:
        conn.close()

@mcp.resource("schema://tables")
def get_table_schema() -> str:
    """Expose the database table schema to the model."""
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    cursor.execute("SELECT sql FROM sqlite_master WHERE type='table';")
    schemas = [row[0] for row in cursor.fetchall() if row[0]]
    conn.close()
    return "\n\n".join(schemas)

if __name__ == "__main__":
    mcp.run()
```

---

## 7. Configuring Host Environments

Host applications load servers via a JSON configuration file (e.g., `claude_desktop_config.json`):

```json
{
  "mcpServers": {
    "git-tools": {
      "command": "node",
      "args": ["/Users/codecaine/mcp-git-server/build/index.js"]
    },
    "sqlite-analytics": {
      "command": "python3",
      "args": ["/Users/codecaine/server.py"]
    }
  }
}
```

---

## 8. Security, Guardrails & Human-in-the-Loop

Because LLMs can execute arbitrary tools autonomously, follow core safety principles:

1. **Principle of Least Privilege**: Grant read-only access where possible (`SELECT` only, no `DROP TABLE` or `rm -rf`).
2. **Human Approval Gates**: Destructive operations (writing to disk, sending emails, executing shell code) should require explicit user confirmation.
3. **Prompt Injection Defense**: Never blindly trust external content retrieved by resources or web scrapers. Treat external data as untrusted text rather than executable system instructions.
