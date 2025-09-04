### Table of Contents

1.  [What is `gping`?](https://www.google.com/search?q=%231-what-is-gping)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Basic Usage & The Interface](https://www.google.com/search?q=%234-basic-usage--the-interface)
5.  [Key Features & Examples](https://www.google.com/search?q=%235-key-features--examples)
6.  [Uninstallation](https://www.google.com/search?q=%236-uninstallation)

-----

### 1\. What is `gping`?

`gping` is a command-line tool that offers "`ping`, but with a graph". It provides a real-time, graphical visualization of network latency directly in your terminal. This makes it incredibly easy to spot trends, outages, or fluctuations in response time that are difficult to see with the standard `ping` command's wall of text.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `gping` using a single Homebrew command:

```bash
brew install gping
```

### 4\. Basic Usage & The Interface

The simplest way to use `gping` is to provide it with a host to ping.

  * **Command:**

    ```bash
    gping google.com
    ```

  * **The Main Interface:**
    `gping` will take over your terminal and display a continuously updating graph of the ping latency.

    **Example Interface:**

    ```text
    gping google.com (142.250.190.142)
    Press 'q' to quit

    25ms ┤
        │                                                     ╭──╮
    20ms ┤                               ╭──╮     ╭──╮         │  │
        │                         ╭──╮    │  │     │  │    ╭──╮ │  │
    15ms ┤╭──╮     ╭──╮     ╭──╮   │  │╭──╮│  │╭──╮ │  │╭──╮│  │ │  │╭─
        ││  │     │  │     │  │   │  ││  ││  ││  │ │  ││  ││  │ │  ││
    10ms ┤│  │╭──╮ │  │╭──╮ │  │╭──╯  ││  ││  ││  │╭╯  ││  ││  │╭╯  ││
        ││  ││  │ │  ││  │ │  ││      ││  │╰──╯│  ││   ╰──╯│  ││    ╰╯
     5ms ┤╰──╯│  │╭╯  ││  │╭╯  │╰──────╯│  │   ╰──╯│       ╰──╯│
        │    ╰──╯│   ╰──╯│    │       ╰──╯     │           │
     0ms ┼───────┴───────┴────┴───────────────┴───────────┴──────────

    Latency: min 8.5ms, max 22.1ms, avg 15.3ms
    ```

### 5\. Key Features & Examples

  * **Pinging Multiple Hosts:**
    You can provide multiple hosts to `gping` to compare their latencies on the same graph. Each host will be assigned a different color and letter for identification.

    **Command:**

    ```bash
    gping google.com github.com 1.1.1.1
    ```

    **Example Multi-Host Interface:**

    ```text
    gping (3 hosts)
    Press 'q' to quit

    50ms ┤
        │
    40ms ┤               ╭──╮
        │               │  │
    30ms ┤               │ b│      ╭──╮
        │               ╰──╯      │ c│
    20ms ┤╭──╮     ╭──╮     ╭──╮   ╰──╯    ╭──╮
        ││ a│     │ a│     │ a│           │ a│
    10ms ┤│  │╭──╮ │  │╭──╮ │  │           │  │
        │╰──╯│ b│╭╯  ││  │╭╯  │           ╰──╯
     0ms ┼────┴──┴───┴───┴───┴───────────────────────────────────────

    [a] google.com: min 15.2ms, max 21.8ms, avg 18.1ms
    [b] github.com: min 8.9ms, max 32.5ms, avg 12.4ms
    [c] 1.1.1.1:    min 28.1ms, max 30.2ms, avg 29.5ms
    ```

  * **Graphing Command Execution Time:**
    A powerful, advanced feature is the ability to graph the execution time of any command, not just network pings. This lets you monitor the performance of scripts, API calls, or database queries over time. Use the `--cmd` flag.

    **Command:** Graph the response time of a `curl` request.

    ```bash
    gping --cmd 'curl -o /dev/null -s -w "%{time_total}" https://api.github.com'
    ```

    In this mode, the Y-axis of the graph will represent the command's total execution time in seconds.

  * **Adjusting the Graph Buffer:**
    You can control how much history the graph displays with the `-b` or `--buffer` flag.

    **Command:** Show the last 2 minutes (120 seconds) of ping history.

    ```bash
    gping -b 120 cloudflare.com
    ```

### 6\. Uninstallation

If you need to remove `gping`, you can do so easily with Homebrew.

```bash
brew uninstall gping
```