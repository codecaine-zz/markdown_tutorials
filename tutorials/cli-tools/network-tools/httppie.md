# HTTPie with Homebrew: Human-friendly HTTP Client

## Table of Contents

1.  [What is `httpie`?](#1-what-is-httpie)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Basic Usage & Output](#4-basic-usage--output)
5.  [Key Features & Examples](#5-key-features--examples)
6.  [Uninstallation](#6-uninstallation)

-----

### 1\. What is `httpie`?

`httpie` (pronounced *aitch-tee-tee-pie*) is a human-friendly command-line HTTP client. Its goal is to make CLI interaction with web services as intuitive and pleasant as possible. It serves as a modern replacement for tools like `curl` and `wget`, providing a simple `http` command with expressive syntax, sensible defaults, and beautifully formatted, colorized output.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `httpie` using a single Homebrew command. Although the package is `httpie`, the command you will use is `http`.

```bash
brew install httpie
```

### 4\. Basic Usage & Output

The syntax is designed to be simple and natural.

  * **Making a GET request:**
    Simply follow the `http` command with a URL. `GET` is the default method.

    ```bash
    http pie.dev/get
    ```

    **Example Output:** `httpie` automatically formats and color-codes the response.

    ```text
    // Response headers are shown first
    HTTP/2 200 OK
    content-length: 442
    content-type: application/json
    date: Thu, 04 Sep 2025 00:11:44 GMT

    // The JSON body is automatically parsed and pretty-printed
    {
        "args": {},
        "headers": {
            "Accept": "*/*",
            "Accept-Encoding": "gzip, deflate",
            "Host": "pie.dev",
            "User-Agent": "HTTPie/3.2.2"
        },
        "origin": "123.123.123.123",
        "url": "https://pie.dev/get"
    }
    ```

  * **Making a POST request with JSON:**
    `httpie` assumes `application/json` when you use the `item:=value` syntax for non-string JSON types (like numbers, booleans, or objects).

    ```bash
    http POST pie.dev/post name='Alice' isCool:=true age:=30
    ```

    **Example Output:** The API echos back the JSON data you sent.

    ```text
    HTTP/2 200 OK
    // ... headers

    {
        // ... other response data
        "json": {
            "age": 30,
            "isCool": true,
            "name": "Alice"
        }
    }
    ```

### 5\. Key Features & Examples

  * **Viewing the Request:**
    Use the `-v` (`--verbose`) flag to see the request that `httpie` sends, in addition to the response.

    ```bash
    http -v pie.dev/get
    ```

    **Example Output:**

    ```text
    // The outgoing request is shown first
    GET /get HTTP/1.1
    Accept: */*
    Accept-Encoding: gzip, deflate
    Connection: keep-alive
    Host: pie.dev
    User-Agent: HTTPie/3.2.2

    // A separator divides the request from the response
    -----------------------------------------

    // The response is shown next
    HTTP/2 200 OK
    // ...
    ```

  * **Setting Custom Headers:**
    Use the `Header:Value` syntax to add custom headers to your request.

    ```bash
    http pie.dev/headers 'Authorization:Bearer my-secret-token' 'X-Request-ID:12345'
    ```

    **Example Output:** The API echos back the headers you sent.

    ```text
    {
        "headers": {
            "Accept": "*/*",
            "Authorization": "Bearer my-secret-token",
            "Host": "pie.dev",
            "X-Request-Id": "12345"
        }
    }
    ```

  * **Submitting Forms:**
    Use the `--form` flag with the `field==value` syntax to send data as `application/x-www-form-urlencoded`, like a traditional web form.

    ```bash
    http --form POST pie.dev/post username=='testuser' password=='supersecret'
    ```

    **Example Output:**

    ```text
    {
        // ...
        "form": {
            "password": "supersecret",
            "username": "testuser"
        }
        // ...
    }
    ```

  * **Downloading Files:**
    Use the `-d` or `--download` flag to save the response body directly to a file. A progress bar is displayed.

    ```bash
    http --download https://pie.dev/image/png
    ```

    **Example Output:**

    ```text
    Downloading 8.05 kB to "png"
    Done. 8.05 kB in 0.01s (580.21 kB/s)
    ```

### 6\. Uninstallation

If you need to remove `httpie`, you can do so easily with Homebrew.

```bash
brew uninstall httpie
```