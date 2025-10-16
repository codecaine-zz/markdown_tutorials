# 🌟 Complete **C Development Guide** for **ARM Mac (M‑series)** with Visual Studio Code  

**For absolute beginners** – every concept is explained in plain English, every command is shown step‑by‑step, and every code snippet is accompanied by a link to the official documentation so you can dig deeper whenever you like.  

> The guide mirrors the structure of the C++ guide you already have, but all examples use **pure C** (no C++ features). The only extra bits are macOS‑specific C APIs (CoreFoundation, IOKit, etc.) that let you write “native” Mac programs.

---

## Table of Contents
0. [Quick‑Start Checklist](#quick-start)  
1. [Why C on an ARM Mac?](#why-c)  
2. [What You’ll Need](#what-you-need)  
3. [Install the Tools (Homebrew, clang/GCC, CMake, make)](#install-tools)  
4. [Set Up Visual Studio Code (VS Code)](#setup-vs-code)  
5. [Your First Project – “Hello, World!”](#first-project)  
6. [Compiling & Running (Terminal, Makefile, CMake)](#build‑run)  
7. [Debugging Inside VS Code (LLDB)](#debugging)  
8. [Example Gallery (pure C)](#examples)  
   * 8.1 [String Basics & Manipulation](#ex‑strings)  
   * 8.2 [Number ↔ String Conversions](#ex‑numstr)  
   * 8.3 [Functions, Variadic Functions & Function Pointers](#ex‑functions)  
   * 8.4 [Macro – Minimum of Two Values](#ex‑macro)  
   * 8.5 [File‑I/O – Reading & Writing Text Files](#ex‑fileio)  
   * 8.6 [CSV Reader (safe quoting)](#ex‑csv)  
   * 8.7 [Regular‑Expression Search (POSIX regex)](#ex‑regex)  
   * 8.8 [Guess‑the‑Number Game (loops & random numbers)](#ex‑guess)  
   * 8.9 [JSON Read & Write (cJSON library)](#ex‑json)  
   * 8.10 [HTTP GET & POST (libcurl)](#ex‑http)  
   * 8.11 [Simple Math REPL (getline + sscanf)](#ex‑repl)  
9. [Best‑Practice Checklist](#best‑practices)  
10. [Common Pitfalls & Quick Fixes](#mistakes)  
11. [Handy Keyboard Shortcuts (macOS + VS Code)](#shortcuts)  
12. [Further Learning & Reference Links](#resources)  
13. [macOS‑Specific API Samples (C & Objective‑C)](#macos‑api)  

---  

<a name="quick-start"></a>
## 0️⃣ Quick‑Start Checklist  

| ✅ | Item |
|----|------|
| ✅ | macOS 13+ (Monterey or later) on Apple Silicon |
| ✅ | Homebrew installed |
| ✅ | Xcode Command‑Line Tools (`xcode-select --install`) |
| ✅ | clang (bundled) **or** GNU gcc (via Homebrew) |
| ✅ | make, CMake (`brew install make cmake`) |
| ✅ | VS Code (`brew install --cask visual-studio-code`) |
| ✅ | C/C++ extension for VS Code (Microsoft) |
| ✅ | At least one folder ready for your code (`~/c-projects`) |

Once the checklist is green, you’re ready to code!  

---  

<a name="why-c"></a>
## 1️⃣ Why C on an ARM Mac?  

| Reason | What it gives you |
|--------|-------------------|
| **Maximum performance** | Native machine code runs at the full speed of the M‑series CPU. |
| **Portability** | Same source compiles on Windows, Linux, Intel macOS, and ARM macOS. |
| **System‑level access** | Direct calls to macOS frameworks (CoreFoundation, IOKit, etc.) are easiest from C. |
| **Small binaries** | No C++ runtime overhead – perfect for utilities, embedded tools, and scripts. |

> **Bottom line:** With a few clicks you’ll have a professional‑grade C environment that works **exactly** like the one used by Apple, Docker, Git, and countless open‑source tools.  

---  

<a name="what-you-need"></a>
## 2️⃣ What You’ll Need  

| Item | Why? | Minimum version |
|------|------|-----------------|
| **macOS** | The OS that ships with the ARM chip | 12.0 (Monterey) or later |
| **Homebrew** | macOS package manager – one‑line installs for everything | any |
| **Xcode Command‑Line Tools** | Provides `clang`, SDKs, `make`, etc. | any |
| **VS Code** | Lightweight editor with C/C++ IntelliSense & debugging | 1.90+ |
| **Git** (optional) | Version control – essential for any real project | any |
| **Internet** | To download the tools | — |

---  

<a name="install-tools"></a>
## 3️⃣ Install the Tools  

Open **Terminal** (`⌘ Space → Terminal`) and run each block **exactly** as shown.

### 3.1 Xcode Command‑Line Tools  

```bash
xcode-select --install
```

Docs: <https://developer.apple.com/library/archive/technotes/tn2339/_index.html>

### 3.2 Homebrew  

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Docs: <https://brew.sh/>

### 3.3 Install a C Compiler  

Apple ships **clang** (perfectly fine). If you prefer GNU gcc:

```bash
brew install gcc          # installs gcc‑13, gcc‑13, etc. (binaries are gcc‑13, g++‑13)
```

> **Tip:** The default clang on Apple Silicon supports `-target arm64-apple-macos12`, so you get native code without extra flags.

### 3.4 Install make and CMake  

```bash
brew install make cmake
```

Docs: make – <https://www.gnu.org/software/make/manual/make.html> | CMake – <https://cmake.org/documentation/>

### 3.5 Verify everything  

```bash
clang --version          # Apple clang, version 15+ on macOS 13+
gcc-13 --version        # only if you installed GNU gcc
make --version
cmake --version
```

If version numbers appear, you’re good to go!  

---  

<a name="setup-vs-code"></a>
## 4️⃣ Set Up Visual Studio Code  

### 4.1 Install VS Code  

```bash
brew install --cask visual-studio-code
```

Docs: <https://code.visualstudio.com/docs/setup/mac>

### 4.2 Install Required Extensions  

| Extension | What it does |
|-----------|--------------|
| **C/C++** (Microsoft) | IntelliSense, debugging, code navigation |
| **CMake Tools** (Microsoft) | CMake integration, configure/run tasks |
| **Code Runner** (Jun Han) | One‑click run of a single file |
| **GitLens** (optional) | Fancy Git UI inside VS Code |

*Install:* `⌘ Shift X` → search → *C/C++* → **Install** (repeat for the others).

### 4.3 Global VS Code Settings (JSON)  

Open Settings → click the **Open Settings (JSON)** icon (top‑right) and paste:

```json
{
  // -------------------------------------------------
  //  Compiler & IntelliSense
  // -------------------------------------------------
  "C_Cpp.default.compilerPath": "/usr/bin/clang",               // use clang (change to /opt/homebrew/bin/gcc-13 if you prefer GNU)
  "C_Cpp.default.intelliSenseMode": "macos-clang-arm64",
  "C_Cpp.default.cStandard": "c11",
  "C_Cpp.errorSquiggles": "enabled",

  // -------------------------------------------------
  //  Code Runner – one‑click execution
  // -------------------------------------------------
  "code-runner.executorMap": {
    "c": "cd $dir && clang -std=c11 $fileName -o $fileNameWithoutExt && ./$fileNameWithoutExt"
  },
  "code-runner.runInTerminal": true,
  "code-runner.clearPreviousOutput": true,

  // -------------------------------------------------
  //  Formatting (optional)
  // -------------------------------------------------
  "editor.formatOnSave": true,
  "C_Cpp.clang_format_style": "Google"
}
```

Docs: VS Code settings – <https://code.visualstudio.com/docs/getstarted/settings>

### 4.4 Create a Workspace Folder  

`File → Open…` → pick/create a folder such as `~/c-projects`. All your source files will live here.  

---  

<a name="first-project"></a>
## 5️⃣ Your First Project – “Hello, World!”  

### 5.1 Create the Directory  

```bash
mkdir -p ~/c-projects/hello-world/src
cd ~/c-projects/hello-world
code .            # launches VS Code in this folder
```

### 5.2 Write `src/main.c`

```c
/* src/main.c -------------------------------------------------
 * A minimal C program that prints “Hello, ARM Mac World!”.
 * ----------------------------------------------------------- */
#include <stdio.h>      // printf, puts
#include <stdlib.h>     // exit, EXIT_SUCCESS

int main(void) {
    puts("👋 Hello, ARM Mac World!");
    return EXIT_SUCCESS;   // 0 signals “all good” to the OS
}
```

Docs: `<stdio.h>` – <https://en.cppreference.com/w/c/header/stdio>  

### 5.3 Build & Run – three ways  

| Method | When to use | Steps |
|--------|------------|-------|
| **Code Runner** (quick test) | One file, no Makefile needed | Click the **Run** ▶ button or press `Ctrl + Alt + N`. |
| **Terminal (manual)** | See the exact compile command | ```bash<br>clang -std=c11 src/main.c -o hello<br>./hello<br>``` |
| **Makefile** (later) | Project grows, many source files | See the Makefile example in § 6.2. |

---  

<a name="build-run"></a>
## 6️⃣ Compiling & Running (Beyond “Hello, World!”)

### 6.1 Simple Makefile (tabs **must** be used for recipe lines)

Create a file named **`Makefile`** in the project root:

```makefile
# -------------------------------------------------
#   Simple Makefile for C projects
# -------------------------------------------------
CC      = clang                    # change to gcc-13 if you prefer GNU
CFLAGS  = -std=c11 -Wall -Wextra -g
SRC     = $(wildcard src/*.c)
OBJ     = $(SRC:.c=.o)
TARGET  = hello

all: $(TARGET)

$(TARGET): $(OBJ)
	$(CC) $(CFLAGS) $^ -o $@

src/%.o: src/%.c
	$(CC) $(CFLAGS) -c $< -o $@

clean:
	rm -f $(OBJ) $(TARGET)

.PHONY: all clean
```

> **Important:** Each command line in a recipe **must start with a TAB**, not spaces.  

Build & run:

```bash
make          # creates ./hello
./hello
make clean    # removes objects & binary
```

Docs: GNU Make manual – <https://www.gnu.org/software/make/manual/make.html>

### 6.2 CMake (recommended for larger projects)

Project layout:

```
hello-cmake/
├─ CMakeLists.txt
└─ src/
   └─ main.c
```

**CMakeLists.txt**

```cmake
cmake_minimum_required(VERSION 3.23)   # macOS ships ≥ 3.23
project(HelloCMake C)

set(CMAKE_C_STANDARD 11)
set(CMAKE_C_STANDARD_REQUIRED ON)

add_executable(hello-cmake src/main.c)
```

**Build & run**

```bash
mkdir -p build && cd build
cmake ..                # configure
cmake --build .         # compile
./hello-cmake           # run
```

Docs: CMake tutorial – <https://cmake.org/cmake/help/latest/guide/tutorial/index.html>

---  

<a name="debugging"></a>
## 7️⃣ Debugging Inside VS Code (LLDB)

1. Click the **Run** icon (⌘ + Shift + D).  
2. Press **Create a launch.json file** → choose **C++ (GDB/LLDB)** → **clang build and debug active file** (the name still says C++, but it works for C).  
3. Replace the generated content with the snippet below (adjust `program` if you named the binary differently).

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Debug (LLDB)",
            "type": "cppdbg",
            "request": "launch",
            "program": "${workspaceFolder}/hello",    // ← binary to debug
            "args": [],
            "stopAtEntry": false,
            "cwd": "${workspaceFolder}",
            "environment": [],
            "externalConsole": false,
            "MIMode": "lldb",
            "preLaunchTask": "C/C++: clang build active file"
        }
    ]
}
```

### How to Debug

* **Set a breakpoint** – click to the left of a line number (red dot appears).  
* Press **F5** (or click the green *Start Debugging* button).  
* Step: **F10** (Step Over), **F11** (Step Into), **⇧ F11** (Step Out).  

Docs: VS Code debugging – <https://code.visualstudio.com/docs/editor/debugging>

---  

<a name="examples"></a>
## 8️⃣ Example Gallery (pure C)  

Copy each snippet into its own **`.c`** file, compile with the command shown, and run. Every program prints a short description of what it does.

<a name="ex-strings"></a>
### 8.1 String Basics & Manipulation  

```c
/* strings_demo.c -------------------------------------------------
 * Demonstrates C string handling with <string.h>.
 * --------------------------------------------------------------- */
#include <stdio.h>
#include <string.h>
#include <ctype.h>   // toupper, tolower

int main(void) {
    char greeting[] = "Hello, C on macOS!";

    /* 1️⃣ Length & emptiness */
    size_t len = strlen(greeting);
    printf("Length: %zu\n", len);
    printf("Is empty? %s\n", (len == 0) ? "yes" : "no");

    /* 2️⃣ Concatenation – need a buffer big enough */
    char buffer[128];
    strcpy(buffer, "Welcome, ");
    strcat(buffer, "developer");
    printf("%s\n", buffer);

    /* 3️⃣ Substring extraction (manual) */
    char sub[4];                       // space for "C++" + '\0'
    strncpy(sub, greeting + 7, 3);
    sub[3] = '\0';
    printf("Substring: %s\n", sub);

    /* 4️⃣ Find & replace (simple) */
    char *pos = strstr(greeting, "macOS");
    if (pos) {
        /* Overwrite "macOS" with "Apple Silicon" (same length) */
        memcpy(pos, "Apple Silicon", strlen("Apple Silicon"));
        printf("After replace: %s\n", greeting);
    }

    /* 5️⃣ Upper‑case conversion */
    for (size_t i = 0; i < len; ++i) {
        greeting[i] = toupper((unsigned char)greeting[i]);
    }
    printf("Upper‑case: %s\n", greeting);

    return 0;
}
```

*Key functions:* `strlen`, `strcpy`, `strcat`, `strncpy`, `strstr`, `memcpy`, `toupper`.  
Docs: `<string.h>` – <https://en.cppreference.com/w/c/header/string> ; `<ctype.h>` – <https://en.cppreference.com/w/c/header/ctype>.

---  

<a name="ex-numstr"></a>
### 8.2 Number ↔ String Conversions  

```c
/* num_string_conv.c --------------------------------------------
 * Demonstrates converting between numbers and strings.
 * ------------------------------------------------------------ */
#include <stdio.h>
#include <stdlib.h>     // atoi, atof, strtol, strtod
#include <errno.h>
#include <limits.h>     // INT_MAX, LONG_MAX

int main(void) {
    /* ----- 1️⃣ int → string ----- */
    int i = 42;
    char buf[32];
    snprintf(buf, sizeof(buf), "%d", i);      // safe, bounds‑checked
    printf("int → string: %s\n", buf);

    /* ----- 2️⃣ string → int ----- */
    const char *src = "12345";
    char *endptr;
    long val = strtol(src, &endptr, 10);
    if (errno == ERANGE || endptr == src) {
        perror("strtol");
    } else {
        printf("string → long: %ld\n", val);
    }

    /* ----- 3️⃣ double ↔ string ----- */
    double d = 3.14159;
    printf("double → string with 3 decimals: %.3f\n", d);

    const char *dsrc = "2.71828";
    double dval = strtod(dsrc, &endptr);
    if (errno == ERANGE || endptr == dsrc) {
        perror("strtod");
    } else {
        printf("string → double: %f\n", dval);
    }

    /* ----- 4️⃣ Hexadecimal conversion ----- */
    unsigned int hex = 0xFF;
    printf("Hex 0xFF → decimal: %u\n", hex);
    printf("Decimal 255 → hex: 0x%X\n", hex);
}
```

*Key functions:* `snprintf`, `atoi`, `strtol`, `strtod`, error checking with `errno`.  
Docs: `strtol` – <https://en.cppreference.com/w/c/string/byte/strtol>.

---  

<a name="ex-functions"></a>
### 8.3 Functions, Variadic Functions & Function Pointers  

```c
/* functions_demo.c ---------------------------------------------
 * Shows plain‑C functions, optional arguments (via overload
 * style tricks), variadic functions, and function pointers.
 * ------------------------------------------------------------ */
#include <stdio.h>
#include <stdarg.h>    // va_list, va_start, va_end

/* 1️⃣ Simple function with default argument emulated via a wrapper */
int add(int a, int b) { return a + b; }
int add_default(int a) { return add(a, 10); }   // default b = 10

/* 2️⃣ Variadic function – sum of any number of ints */
int sum(int count, ...) {
    int total = 0;
    va_list args;
    va_start(args, count);
    for (int i = 0; i < count; ++i) {
        total += va_arg(args, int);
    }
    va_end(args);
    return total;
}

/* 3️⃣ Function pointer example */
typedef int (*binary_op)(int, int);

int multiply(int a, int b) { return a * b; }

int main(void) {
    printf("add(5)            = %d   // default b=10\n", add_default(5));
    printf("add(5,7)          = %d\n", add(5, 7));
    printf("sum(1,2,3,4)      = %d\n", sum(4, 1, 2, 3, 4));

    /* Use a function pointer */
    binary_op op = multiply;
    printf("multiply via pointer (6*7) = %d\n", op(6, 7));
}
```

*Key concepts:* normal functions, wrapper for default arguments, `stdarg.h` variadic functions, function pointers.  
Docs: `stdarg.h` – <https://en.cppreference.com/w/c/header/stdarg>.

---  

<a name="ex-macro"></a>
### 8.4 Macro – Minimum of Two Values  

```c
/* min_macro.c -------------------------------------------------
 * Macro that evaluates its arguments exactly once.
 * ----------------------------------------------------------- */
#include <stdio.h>

#define MIN(a,b) ({                \
    typeof(a) _a = (a);            \
    typeof(b) _b = (b);            \
    (_a < _b) ? _a : _b; })

int main(void) {
    int i = MIN(3, 7);
    double d = MIN(2.5, 1.8);
    char c = MIN('z', 'a');

    printf("MIN(3,7)   = %d\n", i);
    printf("MIN(2.5,1.8) = %g\n", d);
    printf("MIN('z','a') = %c\n", c);
    return 0;
}
```

*Why the braces?* The GNU statement‑expression (`({ … })`) ensures each argument is evaluated **once**, avoiding side‑effects.  

Docs: Macros – <https://en.cppreference.com/w/c/preprocessor/replace>  

---  

<a name="ex-fileio"></a>
### 8.5 File‑I/O – Reading & Writing Text Files  

```c
/* file_io.c ---------------------------------------------------
 * Demonstrates fopen/fread/fwrite/fprintf/fgets.
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    /* ---- Write a file ---- */
    FILE *out = fopen("example.txt", "w");
    if (!out) {
        perror("fopen for write");
        return 1;
    }
    fprintf(out, "Line 1: Hello, file!\n");
    fprintf(out, "Line 2: ARM Mac C demo.\n");
    fclose(out);
    printf("✅ Wrote example.txt\n");

    /* ---- Read the file back ---- */
    FILE *in = fopen("example.txt", "r");
    if (!in) {
        perror("fopen for read");
        return 1;
    }
    char line[128];
    printf("📄 Contents of example.txt:\n");
    while (fgets(line, sizeof(line), in)) {
        printf("  %s", line);   // line already contains '\n'
    }
    fclose(in);
    return 0;
}
```

*Key functions:* `fopen`, `fprintf`, `fgets`, `fclose`.  
Docs: `fopen` – <https://en.cppreference.com/w/c/io/fopen>.

---  

<a name="ex-csv"></a>
### 8.6 CSV Reader (Safe Quoting)  

```c
/* csv_reader.c -----------------------------------------------
 * Reads a CSV file that follows RFC‑4180 rules:
 *   – fields may be quoted,
 *   – escaped quotes are double "".
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>

#define MAX_FIELDS  64
#define MAX_LINE   1024

/* Split one CSV line according to RFC‑4180. */
static int parse_csv_line(const char *line, char *fields[], int max_fields) {
    int col = 0;
    bool in_quotes = false;
    const char *p = line;
    char *buf = NULL;
    size_t buflen = 0;
    size_t pos = 0;

    while (*p && col < max_fields) {
        if (*p == '"' && !in_quotes) {
            in_quotes = true;
            p++;
            continue;
        }

        if (*p == '"' && in_quotes) {
            if (p[1] == '"') {               // escaped quote ""
                if (pos + 1 >= buflen) {
                    buflen = buflen ? buflen * 2 : 32;
                    buf = realloc(buf, buflen);
                }
                buf[pos++] = '"';
                p += 2;
                continue;
            } else {
                in_quotes = false;
                p++;
                continue;
            }
        }

        if (*p == ',' && !in_quotes) {
            buf[pos] = '\0';
            fields[col++] = strdup(buf);
            pos = 0;
            p++;
            continue;
        }

        if (pos + 1 >= buflen) {
            buflen = buflen ? buflen * 2 : 32;
            buf = realloc(buf, buflen);
        }
        buf[pos++] = *p++;
    }

    buf[pos] = '\0';
    fields[col++] = strdup(buf);
    free(buf);
    return col;        // number of columns parsed
}

int main(void) {
    const char *filename = "data.csv";
    FILE *fp = fopen(filename, "r");
    if (!fp) {
        perror("fopen");
        return 1;
    }

    char line[MAX_LINE];
    int row = 0;
    while (fgets(line, sizeof(line), fp)) {
        /* Remove trailing newline */
        line[strcspn(line, "\r\n")] = '\0';

        char *fields[MAX_FIELDS];
        int cols = parse_csv_line(line, fields, MAX_FIELDS);

        printf("Row %d (%d cols): ", ++row, cols);
        for (int i = 0; i < cols; ++i) {
            printf("[%s] ", fields[i]);
            free(fields[i]);
        }
        putchar('\n');
    }
    fclose(fp);
    return 0;
}
```

*What it does:* Handles quoted fields, escaped quotes, commas inside quotes, and both LF/CRLF line endings.  

Docs: `strdup` – <https://en.cppreference.com/w/c/string/byte/strdup>.  

---  

<a name="ex-regex"></a>
### 8.7 Simple Regular‑Expression Search (POSIX regex)  

```c
/* regex_demo.c -----------------------------------------------
 * Finds all e‑mail addresses in a line using POSIX regex.
 * ----------------------------------------------------------- */
#define _POSIX_C_SOURCE 200809L   // enable modern POSIX features
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <regex.h>

int main(void) {
    const char *text = "Contact: alice@example.com, bob@test.org, carol@foo.io";
    const char *pattern = "([[:alnum:]_\\.+-]+)@([[:alnum:]\\.-]+\\.[[:alpha:]]{2,})";

    regex_t regex;
    if (regcomp(&regex, pattern, REG_EXTENDED | REG_ICASE) != 0) {
        fprintf(stderr, "Could not compile regex\n");
        return 1;
    }

    const char *p = text;
    regmatch_t pmatch[3];   // whole match + two capture groups
    int match_no = 0;

    while (regexec(&regex, p, 3, pmatch, 0) == 0) {
        ++match_no;
        int start = pmatch[0].rm_so;
        int end   = pmatch[0].rm_eo;
        printf("Match %d: %.*s\n", match_no, end - start, p + start);
        p += end;   // continue after this match
    }

    regfree(&regex);
    return 0;
}
```

*Key functions:* `regcomp`, `regexec`, `regfree`.  
Docs: POSIX regex – <https://pubs.opengroup.org/onlinepubs/9699919799/basedefs/regex.h.html>.

---  

<a name="ex-guess"></a>
### 8.8 Guess‑the‑Number Game (loops & random numbers)  

```c
/* guess_number.c ---------------------------------------------
 * Simple interactive game – guess a random number 1‑100.
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>

int main(void) {
    srand((unsigned)time(NULL));
    int secret = (rand() % 100) + 1;   // 1‑100 inclusive
    char line[64];
    int attempts = 0;

    printf("🎯 Guess the number (1‑100). Type \"quit\" to exit.\n");

    while (1) {
        printf("> ");
        if (!fgets(line, sizeof(line), stdin)) break;

        if (strncmp(line, "quit", 4) == 0) {
            printf("👋 Bye!\n");
            break;
        }

        int guess = atoi(line);
        ++attempts;

        if (guess < secret) {
            puts("Too low ❄️");
        } else if (guess > secret) {
            puts("Too high 🔥");
        } else {
            printf("🎉 Correct! %d attempts.\n", attempts);
            break;
        }
    }
    return 0;
}
```

*Key concepts:* `srand`, `rand`, `fgets`, `atoi`, loop control.  

---  

<a name="ex-repl"></a>
### 8.9 Simple Math REPL (Read‑Eval‑Print Loop)  

```c
/* repl.c ----------------------------------------------------
 * Reads lines like "3 + 4" and prints the result.
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(void) {
    char line[128];
    printf("Simple Math REPL – type \"exit\" to quit\n");
    while (1) {
        printf("> ");
        if (!fgets(line, sizeof(line), stdin)) break;
        if (strncmp(line, "exit", 4) == 0) break;

        double a, b;
        char op;
        if (sscanf(line, "%lf %c %lf", &a, &op, &b) != 3) {
            puts("Invalid format. Use: <num> <op> <num>");
            continue;
        }

        double result = 0;
        switch (op) {
            case '+': result = a + b; break;
            case '-': result = a - b; break;
            case '*': result = a * b; break;
            case '/':
                if (b == 0) { puts("divide‑by‑zero!"); continue; }
                result = a / b; break;
            default: puts("Unsupported operator"); continue;
        }
        printf(" = %g\n", result);
    }
    return 0;
}
```

*Key functions:* `sscanf` – parses formatted strings; `printf` for output.  

---  

<a name="ex-json"></a>
### 8.10 JSON Read & Write (using **cJSON**, a single‑header library)

**One‑time install**

```bash
brew install cjson   # installs the header + library at /opt/homebrew/include and /opt/homebrew/lib
```

**Code (json_demo.c)**

```c
/* json_demo.c ------------------------------------------------
 * Uses cJSON (https://github.com/DaveGamble/cJSON) to
 * write a JSON file and read it back.
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>
#include <cjson/cJSON.h>

int main(void) {
    /* Build a JSON object in memory */
    cJSON *person = cJSON_CreateObject();
    cJSON_AddStringToObject(person, "name", "Alice");
    cJSON_AddNumberToObject(person, "age", 30);
    cJSON *skills = cJSON_CreateStringArray((const char*[]){"C", "Metal", "CMake"}, 3);
    cJSON_AddItemToObject(person, "skills", skills);
    cJSON_AddBoolToObject(person, "active", cJSON_True);

    /* Write to a pretty‑printed file */
    char *rendered = cJSON_Print(person);
    FILE *fp = fopen("person.json", "w");
    if (!fp) { perror("fopen"); return 1; }
    fputs(rendered, fp);
    fclose(fp);
    free(rendered);
    printf("✅ Wrote person.json\n");

    /* Read the file back */
    fp = fopen("person.json", "r");
    if (!fp) { perror("fopen"); return 1; }
    fseek(fp, 0, SEEK_END);
    long len = ftell(fp);
    fseek(fp, 0, SEEK_SET);
    char *buf = malloc(len + 1);
    fread(buf, 1, len, fp);
    buf[len] = '\0';
    fclose(fp);

    cJSON *loaded = cJSON_Parse(buf);
    free(buf);
    if (!loaded) {
        fprintf(stderr, "JSON parse error\n");
        return 1;
    }

    const cJSON *name = cJSON_GetObjectItemCaseSensitive(loaded, "name");
    const cJSON *age  = cJSON_GetObjectItemCaseSensitive(loaded, "age");
    if (cJSON_IsString(name) && cJSON_IsNumber(age)) {
        printf("Read back – name: %s, age: %d\n", name->valuestring, age->valueint);
    }

    /* Clean up */
    cJSON_Delete(person);
    cJSON_Delete(loaded);
    return 0;
}
```

Compile:

```bash
clang -std=c11 json_demo.c -I /opt/homebrew/include -L /opt/homebrew/lib -lcjson -o json_demo
./json_demo
```

Docs: cJSON – <https://github.com/DaveGamble/cJSON>  

---  

<a name="ex-http"></a>
### 8.11 HTTP GET & POST (using **libcurl**)

**One‑time install**

```bash
brew install curl   # includes libcurl headers & library
```

**Code (http_curl.c)**

```c
/* http_curl.c ------------------------------------------------
 * Demonstrates a simple GET and a POST request with libcurl.
 * ----------------------------------------------------------- */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <curl/curl.h>

/* Write callback – libcurl gives us data chunk by chunk */
static size_t write_cb(void *ptr, size_t size, size_t nmemb, void *userdata) {
    size_t total = size * nmemb;
    strncat((char *)userdata, (char *)ptr, total);
    return total;
}

/* Helper to perform GET */
static void http_get(const char *url) {
    CURL *curl = curl_easy_init();
    if (!curl) { fprintf(stderr, "curl init failed\n"); return; }

    char response[65536] = {0};

    curl_easy_setopt(curl, CURLOPT_URL, url);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_cb);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, response);
    curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);

    CURLcode rc = curl_easy_perform(curl);
    if (rc != CURLE_OK) {
        fprintf(stderr, "GET error: %s\n", curl_easy_strerror(rc));
    } else {
        long code;
        curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &code);
        printf("GET %s – status %ld\n", url, code);
        printf("Body (first 200 chars):\n%.*s\n",
               (int)strlen(response) > 200 ? 200 : (int)strlen(response),
               response);
    }
    curl_easy_cleanup(curl);
}

/* Helper to perform POST with JSON payload */
static void http_post(const char *url, const char *json) {
    CURL *curl = curl_easy_init();
    if (!curl) { fprintf(stderr, "curl init failed\n"); return; }

    struct curl_slist *headers = NULL;
    headers = curl_slist_append(headers, "Content-Type: application/json");

    char response[65536] = {0};

    curl_easy_setopt(curl, CURLOPT_URL, url);
    curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, json);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_cb);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, response);

    CURLcode rc = curl_easy_perform(curl);
    if (rc != CURLE_OK) {
        fprintf(stderr, "POST error: %s\n", curl_easy_strerror(rc));
    } else {
        long code;
        curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &code);
        printf("POST %s – status %ld\n", url, code);
        printf("Response (first 200 chars):\n%.*s\n",
               (int)strlen(response) > 200 ? 200 : (int)strlen(response),
               response);
    }
    curl_slist_free_all(headers);
    curl_easy_cleanup(curl);
}

int main(void) {
    curl_global_init(CURL_GLOBAL_DEFAULT);

    http_get("https://httpbin.org/get?hello=world");
    http_post("https://httpbin.org/post",
              "{\"name\":\"Alice\",\"score\":100}");

    curl_global_cleanup();
    return 0;
}
```

Compile:

```bash
clang -std=c11 http_curl.c -I /opt/homebrew/include -L /opt/homebrew/opt/curl/lib -lcurl -o http_demo
./http_demo
```

Docs: libcurl – <https://curl.se/libcurl/c/>  

---  

<a name="best-practices"></a>
## 9️⃣ Best‑Practice Checklist  

| Practice | Why it matters | How to do it |
|----------|----------------|--------------|
| **Enable all warnings** (`-Wall -Wextra -Werror`) | C compilers are noisy for a reason | Add to `CFLAGS` in your Makefile/CMake |
| **Use `size_t` for sizes/indices** | Prevent signed/unsigned bugs | `size_t i = 0;` |
| **Always check return values** (`fopen`, `malloc`, `strtol`) | Early error detection | `if (!ptr) { perror(...); exit(EXIT_FAILURE); }` |
| **Prefer `snprintf` over `sprintf`** | Avoid buffer overruns | `snprintf(buf, sizeof(buf), "%d", x);` |
| **Free every allocation** (`free`) | No leaks, good for long‑running tools | Use `valgrind` (`brew install valgrind`) to verify |
| **Document functions with comment blocks** | Makes code readable for others (and you) | `/** Brief … */` |
| **Keep functions < 50 lines** | Easier to test and reuse | Break logic into helper functions |
| **Version control with Git** | Safe backup, collaboration | `git init`, commit often with meaningful messages |
| **Format consistently** | Professional appearance | Install `clang-format` (`brew install clang-format`) and set VS Code to format on save |
| **Avoid magic numbers** | Clear intent | `#define MAX_USERS 100` or `const int max_users = 100;` |

> **Pro tip:** When a warning appears, **fix it** – never ignore warnings.  

---  

<a name="mistakes"></a>
## 🔟 Common Pitfalls & Quick Fixes  

| Problem | Symptom | Fix |
|---------|---------|-----|
| **Forgot a header** | `error: ‘printf’ was not declared` | Add `#include <stdio.h>` at the top |
| **Off‑by‑one in loops** | Missing last element or out‑of‑bounds crash | Remember C loops are `for (i = 0; i < n; ++i)` |
| **Using `gets()`** (deprecated) | Compilation warning “deprecated” | Use `fgets()` instead |
| **Mismatched `malloc`/`free`** | Leak or crash on free | Free the exact pointer returned by `malloc` |
| **Mixing `scanf` and `getline`** | Input appears to be skipped | Flush the newline: `int c; while ((c = getchar()) != '\n' && c != EOF);` |
| **Wrong format specifier** (`%d` vs `%ld`) | Wrong numbers printed | Use the correct specifier (`%ld` for `long`, `%zu` for `size_t`) |
| **Forgot to link a library** (`-lcurl`, `-lcjson`) | “undefined reference to `curl_easy_init`” | Add the appropriate `-l` flag in your Makefile/CMake |
| **Hard‑coded file paths** | Works on your machine but not others | Use relative paths or read a path from the command line |
| **Ignoring `errno` after `strtol`** | Silent overflow / invalid input | Check `errno` and `endptr` after conversion |

> **When debugging:** Insert `printf("x = %d\n", x);` or use VS Code’s LLDB debugger to step through code.  

---  

<a name="shortcuts"></a>
## 1️⃣1️⃣ Handy Keyboard Shortcuts (macOS + VS Code)

| Shortcut | What it does |
|----------|--------------|
| `⌘ P` | Quick file open (type any part of the filename) |
| `⌘ Shift P` | Command palette (access any VS Code command) |
| `⌘ B` | Toggle the Explorer sidebar |
| `⌘ /` | Toggle line comment |
| `⌥ ↑` / `⌥ ↓` | Move the current line up/down |
| `⌘ D` | Add next occurrence of the current selection (multi‑cursor) |
| `⌘ Shift L` | Select all occurrences of the current word |
| `F5` | Start debugging (LLDB) |
| `F9` | Toggle breakpoint on the current line |
| `F10` | Step over (debugger) |
| `F11` | Step into (debugger) |
| ``Ctrl ` `` | Open integrated terminal |
| `⌘ Shift F` | Search across all files in the workspace |
| `⌘ K, ⌘ C` | Comment block (press `⌘K`, release, then `⌘C`) |
| `⌘ K, ⌘ U` | Uncomment block |

---  

<a name="resources"></a>
## 1️⃣2️⃣ Further Learning & Reference Links  

| Category | Links |
|----------|-------|
| **C language reference** | <https://en.cppreference.com/w/c> |
| **POSIX API** | <https://pubs.opengroup.org/onlinepubs/9699919799/> |
| **Apple Developer Docs (C APIs)** | <https://developer.apple.com/documentation/> |
| **Learn C (free)** | <https://www.learn-c.org/> |
| **C Programming – Tutorial** | <https://www.tutorialspoint.com/cprogramming/index.htm> |
| **CMake tutorial** | <https://cmake.org/cmake/help/latest/guide/tutorial/index.html> |
| **LLDB command reference** | <https://lldb.llvm.org/use/tutorial.html> |
| **cJSON** | <https://github.com/DaveGamble/cJSON> |
| **libcurl** | <https://curl.se/libcurl/> |
| **macOS System Programming** (books) | *“macOS Programming for Unix Developers”* – Stephen G. Kellogg |
| **Stack Overflow** | Tag: [c] – <https://stackoverflow.com/questions/tagged/c> |

---  

<a name="macos-api"></a>
## 1️⃣3️⃣ macOS‑Specific API Samples (C & Objective‑C)  

You can write *pure C* that talks to macOS frameworks, or you can sprinkle a tiny amount of **Objective‑C** (files with the `.mm` extension) to get higher‑level APIs. Below are ready‑to‑copy snippets that compile on Apple Silicon.  

> **Compilation tip:** For pure‑C files (`.c`) use `clang`. For Objective‑C++ (`.mm`) use `clang -x objective-c++`. Add the required `-framework` flags (see each snippet).  

### 13.1 Show a Simple Alert (AppKit)

```objc
/* alert_box.mm -------------------------------------------------
 * Shows a native macOS alert dialog.
 * ----------------------------------------------------------- */
#import <Cocoa/Cocoa.h>
int main(void) {
    @autoreleasepool {
        NSAlert *alert = [[NSAlert alloc] init];
        [alert setMessageText:@"Hello from C!"];
        [alert setInformativeText:@"This is a native macOS dialog box."];
        [alert addButtonWithTitle:@"OK"];
        [alert runModal];
    }
    return 0;
}
```

Compile:

```bash
clang++ -std=c11 -framework Foundation -framework AppKit alert_box.mm -o alert_box
./alert_box
```

Docs: `NSAlert` – <https://developer.apple.com/documentation/appkit/nsalert>  

---

### 13.2 Detect Dark Mode (AppKit)

```objc
/* dark_mode.mm ------------------------------------------------
 * Prints whether the user has enabled Dark Mode.
 * ----------------------------------------------------------- */
#import <AppKit/AppKit.h>
#include <stdio.h>

int main(void) {
    @autoreleasepool {
        NSString *style = [[NSUserDefaults standardUserDefaults] stringForKey:@"AppleInterfaceStyle"];
        if (style && [style isEqualToString:@"Dark"])
            puts("🌙 Dark Mode is ON");
        else
            puts("☀️ Light Mode is ON");
    }
    return 0;
}
```

Compile:

```bash
clang++ -std=c11 -framework Foundation -framework AppKit dark_mode.mm -o dark_mode
./dark_mode
```

Docs: `AppleInterfaceStyle` – <https://developer.apple.com/documentation/appkit/nsapplication/1534516-appleinterfacestyle>  

---

### 13.3 Read Battery Status (IOKit)

```objc
/* battery_status.c --------------------------------------------
 * Prints battery % and charging state on a MacBook.
 * ----------------------------------------------------------- */
#include <CoreFoundation/CoreFoundation.h>
#include <IOKit/ps/IOPowerSources.h>
#include <IOKit/ps/IOPSKeys.h>
#include <stdio.h>

int main(void) {
    CFTypeRef info = IOPSCopyPowerSourcesInfo();
    CFArrayRef list = IOPSCopyPowerSourcesList(info);
    if (CFArrayGetCount(list) == 0) {
        puts("No battery (desktop Mac?)");
        return 0;
    }

    CFDictionaryRef description = IOPSGetPowerSourceDescription(info,
        CFArrayGetValueAtIndex(list, 0));

    CFNumberRef capacityNum = CFDictionaryGetValue(description,
        CFSTR(kIOPSCurrentCapacityKey));
    int capacity = 0;
    CFNumberGetValue(capacityNum, kCFNumberIntType, &capacity);

    CFBooleanRef isCharging = CFDictionaryGetValue(description,
        CFSTR(kIOPSIsChargingKey));

    printf("🔋 Battery: %d%% %s\n",
           capacity,
           (isCharging == kCFBooleanTrue) ? "(charging)" : "(on battery)");

    CFRelease(list);
    CFRelease(info);
    return 0;
}
```

Compile:

```bash
clang -std=c11 -framework Foundation -framework IOKit battery_status.c -o battery_status
./battery_status
```

Docs: `IOPSCopyPowerSourcesInfo` – <https://developer.apple.com/documentation/iokit/1656940-iopscopypowersourcesinfo>  

---

### 13.4 Get Cursor Position (ApplicationServices)

```objc
/* cursor_position.mm -----------------------------------------
 * Prints the current mouse location in screen coordinates.
 * ----------------------------------------------------------- */
#import <ApplicationServices/ApplicationServices.h>
#include <stdio.h>

int main(void) {
    @autoreleasepool {
        CGEventRef ev = CGEventCreate(NULL);
        CGPoint p = CGEventGetLocation(ev);
        CFRelease(ev);
        printf("🖱️  Cursor: x = %.0f, y = %.0f\n", p.x, p.y);
    }
    return 0;
}
```

Compile:

```bash
clang++ -std=c11 -framework Foundation -framework ApplicationServices cursor_position.mm -o cursor_pos
./cursor_pos
```

Docs: `CGEventGetLocation` – <https://developer.apple.com/documentation/coregraphics/1454539-cgeventgetlocation>  

---

### 13.5 Move the Mouse (ApplicationServices – requires Accessibility)

```objc
/* move_cursor.mm ---------------------------------------------
 * Moves the mouse to the centre of the primary display.
 * ----------------------------------------------------------- */
#import <ApplicationServices/ApplicationServices.h>
#include <unistd.h>   // sleep

int main(void) {
    @autoreleasepool {
        // Get screen size
        CGDirectDisplayID display = CGMainDisplayID();
        CGSize size = CGDisplayScreenSize(display);
        CGRect bounds = CGDisplayBounds(display);
        CGPoint centre = CGPointMake(bounds.origin.x + bounds.size.width / 2,
                                    bounds.origin.y + bounds.size.height / 2);

        // Create a mouse‑move event
        CGEventRef move = CGEventCreateMouseEvent(NULL,
            kCGEventMouseMoved, centre, kCGMouseButtonLeft);
        CGEventPost(kCGHIDEventTap, move);
        CFRelease(move);

        printf("Moved cursor to (%.0f, %.0f)\n", centre.x, centre.y);
    }
    return 0;
}
```

Compile:

```bash
clang++ -std=c11 -framework Foundation -framework ApplicationServices move_cursor.mm -o move_cursor
./move_cursor    # you may need to enable “Accessibility” for Terminal in System Settings → Privacy & Security
```

Docs: `CGEventCreateMouseEvent` – <https://developer.apple.com/documentation/coregraphics/1454565-cgeventcreatemouseevent>  

---

### 13.6 Play a Sound File (AVFoundation)

```objc
/* play_sound.mm ---------------------------------------------
 * Plays a short audio file (any format supported by macOS).
 * ----------------------------------------------------------- */
#import <AVFoundation/AVFoundation.h>
#include <stdio.h>
#include <unistd.h>

int main(int argc, char *argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            fprintf(stderr, "Usage: %s <audio-file>\n", argv[0]);
            return 1;
        }

        NSURL *url = [NSURL fileURLWithPath:@argv[1]];
        NSError *err = nil;
        AVAudioPlayer *player = [[AVAudioPlayer alloc] initWithContentsOfURL:url error:&err];
        if (err) {
            fprintf(stderr, "Error loading audio: %s\n", [[err localizedDescription] UTF8String]);
            return 1;
        }
        [player play];
        printf("🔊 Playing…\n");
        // Sleep for the duration of the clip
        usleep((useconds_t)(player.duration * 1e6));
    }
    return 0;
}
```

Compile:

```bash
clang++ -std=c11 -framework Foundation -framework AVFoundation play_sound.mm -o play_sound
./play_sound /System/Library/Sounds/Glass.aiff
```

Docs: `AVAudioPlayer` – <https://developer.apple.com/documentation/avfoundation/avaudioplayer>  

---

### 13.7 Get System Volume (CoreAudio)

```c
/* system_volume.c --------------------------------------------
 * Prints current output volume (0‑100%) and can set it.
 * ----------------------------------------------------------- */
#include <CoreAudio/CoreAudio.h>
#include <stdio.h>
#include <stdlib.h>

static AudioDeviceID defaultOutputDevice(void) {
    AudioDeviceID dev = kAudioObjectUnknown;
    UInt32 size = sizeof(dev);
    AudioObjectPropertyAddress addr = {
        kAudioHardwarePropertyDefaultOutputDevice,
        kAudioObjectPropertyScopeGlobal,
        kAudioObjectPropertyElementMaster
    };
    AudioObjectGetPropertyData(kAudioObjectSystemObject, &addr, 0, NULL, &size, &dev);
    return dev;
}

static float getVolume(AudioDeviceID dev) {
    Float32 vol = 0.0f;
    UInt32 size = sizeof(vol);
    AudioObjectPropertyAddress addr = {
        kAudioDevicePropertyVolumeScalar,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMaster
    };
    AudioObjectGetPropertyData(dev, &addr, 0, NULL, &size, &vol);
    return vol;
}

static void setVolume(AudioDeviceID dev, float vol) {
    if (vol < 0.0f) vol = 0.0f;
    if (vol > 1.0f) vol = 1.0f;
    UInt32 size = sizeof(vol);
    AudioObjectPropertyAddress addr = {
        kAudioDevicePropertyVolumeScalar,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMaster
    };
    AudioObjectSetPropertyData(dev, &addr, 0, NULL, size, &vol);
}

int main(int argc, char *argv[]) {
    AudioDeviceID dev = defaultOutputDevice();
    if (dev == kAudioObjectUnknown) {
        fprintf(stderr, "❌ Could not find output device\n");
        return 1;
    }

    if (argc == 2 && strcmp(argv[1], "mute") == 0) {
        setVolume(dev, 0.0f);
        puts("🔇 Muted");
        return 0;
    }

    if (argc == 2 && strcmp(argv[1], "unmute") == 0) {
        setVolume(dev, 0.5f); // 50% as a simple “unmute”
        puts("🔊 Unmuted (50%)");
        return 0;
    }

    if (argc == 2) {
        int pct = atoi(argv[1]);
        if (pct < 0 || pct > 100) {
            fprintf(stderr, "Volume must be 0‑100\n");
            return 1;
        }
        setVolume(dev, pct / 100.0f);
        printf("✅ Set volume to %d%%\n", pct);
        return 0;
    }

    float vol = getVolume(dev);
    printf("🔊 Current volume: %.0f%%\n", vol * 100);
    return 0;
}
```

Compile:

```bash
clang -std=c11 -framework CoreAudio system_volume.c -o sysvol
./sysvol            # prints current volume
./sysvol 30         # set to 30%
./sysvol mute       # mute
./sysvol unmute     # set to 50%
```

Docs: CoreAudio – <https://developer.apple.com/documentation/coreaudio>  

---

### 13.8 Power Management (Sleep / Restart / Shutdown)

```c
/* power_control.c --------------------------------------------
 * Sends AppleEvents to request sleep, restart, or shutdown.
 * Requires admin rights (you’ll be prompted for a password).
 * ----------------------------------------------------------- */
#include <CoreServices/CoreServices.h>
#include <stdio.h>
#include <string.h>

static OSStatus sendAppleEvent(AEEventID eventID) {
    AEAddressDesc target;
    ProcessSerialNumber psn = {0, kSystemProcess};
    OSStatus err = AECreateDesc(typeProcessSerialNumber,
                               &psn, sizeof(psn), &target);
    if (err != noErr) return err;

    AppleEvent ev = {typeNull, NULL};
    err = AECreateAppleEvent(kCoreEventClass, eventID,
                             &target, kAutoGenerateReturnID,
                             kAnyTransactionID, &ev);
    AEDisposeDesc(&target);
    if (err != noErr) return err;

    err = AESendMessage(&ev, NULL, kAENoReply, kAEDefaultTimeout);
    AEDisposeDesc(&ev);
    return err;
}

int main(int argc, char *argv[]) {
    if (argc != 2) {
        puts("Usage: power_control <sleep|restart|shutdown|logout>");
        return 1;
    }

    if (strcmp(argv[1], "sleep") == 0) {
        puts("💤 Sleeping…");
        sendAppleEvent(kAESleep);
    } else if (strcmp(argv[1], "restart") == 0) {
        puts("🔄 Restarting…");
        sendAppleEvent(kAERestart);
    } else if (strcmp(argv[1], "shutdown") == 0) {
        puts("⏻ Shutting down…");
        sendAppleEvent(kAEShutDown);
    } else if (strcmp(argv[1], "logout") == 0) {
        puts("👋 Logging out…");
        sendAppleEvent(kAEReallyLogOut);
    } else {
        fprintf(stderr, "Unknown command: %s\n", argv[1]);
        return 1;
    }
    return 0;
}
```

Compile:

```bash
clang -std=c11 -framework CoreServices power_control.c -o powerctl
./powerctl sleep       # puts the Mac to sleep (after 3 s delay you can cancel)
# Try restart / shutdown / logout the same way
```

Docs: AppleEvents – <https://developer.apple.com/documentation/coreservices/appleevents>  

---

### 13.9 Adjust Screen Brightness (IOKit – built‑in display only)

```c
/* brightness.c -----------------------------------------------
 * Reads or sets the built‑in display brightness (0‑1 float).
 * ----------------------------------------------------------- */
#include <IOKit/graphics/IOGraphicsLib.h>
#include <stdio.h>
#include <stdlib.h>

static float getBrightness(void) {
    io_service_t service = IOServiceGetMatchingService(kIOMasterPortDefault,
                              IOServiceMatching("IODisplayConnect"));
    if (!service) return -1.0f;

    float brightness = -1.0f;
    IODisplayGetFloatParameter(service, kNilOptions,
                               CFSTR(kIODisplayBrightnessKey), &brightness);
    IOObjectRelease(service);
    return brightness;
}

static void setBrightness(float b) {
    if (b < 0.0f) b = 0.0f;
    if (b > 1.0f) b = 1.0f;
    io_service_t service = IOServiceGetMatchingService(kIOMasterPortDefault,
                              IOServiceMatching("IODisplayConnect"));
    if (!service) return;
    IODisplaySetFloatParameter(service, kNilOptions,
                               CFSTR(kIODisplayBrightnessKey), b);
    IOObjectRelease(service);
}

int main(int argc, char *argv[]) {
    if (argc == 2) {
        int pct = atoi(argv[1]);
        if (pct < 0 || pct > 100) {
            fprintf(stderr, "Percent must be 0‑100\n");
            return 1;
        }
        setBrightness(pct / 100.0f);
        printf("✅ Brightness set to %d%%\n", pct);
    } else {
        float cur = getBrightness();
        if (cur < 0) {
            puts("❌ Could not read brightness (maybe external monitor)");
        } else {
            printf("💡 Current brightness: %.0f%%\n", cur * 100);
        }
    }
    return 0;
}
```

Compile:

```bash
clang -std=c11 -framework IOKit brightness.c -o brightness
./brightness          # show current
./brightness 30       # set to 30%
```

Docs: `IODisplaySetFloatParameter` – <https://developer.apple.com/documentation/iokit/1556574-iodisplaysetfloatparameter>  

---

### 13.10 Full “System‑Control” Summary Table

| Feature | Framework | Key API | Typical Permission |
|---------|-----------|---------|--------------------|
| Cursor position | ApplicationServices | `CGEventGetLocation` | None |
| Move mouse / click | ApplicationServices | `CGEventCreateMouseEvent`/`CGEventPost` | **Accessibility** (System Settings → Privacy & Security) |
| Dark‑mode detection | AppKit | `NSUserDefaults` key `AppleInterfaceStyle` | None |
| Battery status | IOKit | `IOPSCopyPowerSourcesInfo` | None |
| Screen brightness | IOKit | `IODisplayGet/SetFloatParameter` | None (built‑in display only) |
| System volume | CoreAudio | `AudioObjectGet/SetPropertyData` (volume scalar) | None |
| Power actions (sleep, restart, shutdown, logout) | CoreServices / AppleEvents | `kAESleep`, `kAERestart`, etc. | **Admin** (may prompt) |
| Notification center | UserNotifications | `UNUserNotificationCenter` (Obj‑C) | User must allow notifications |
| File dialogs | AppKit | `NSOpenPanel` / `NSSavePanel` | None |
| Simple GUI window | AppKit | `NSWindow`, `NSButton` | None |
| Metal (GPU) | Metal | `MTLCreateSystemDefaultDevice` | None |
| Audio playback | AVFoundation | `AVAudioPlayer` | None |
| HTTP networking | libcurl (third‑party) | `curl_easy_*` | None |
| JSON parsing | cJSON (third‑party) | `cJSON_Parse` / `cJSON_Print` | None |

> **Security reminder:** macOS will block mouse/keyboard automation unless you give the Terminal (or your compiled binary) Accessibility rights. Go to **System Settings → Privacy & Security → Accessibility** and add the program the first time you run it.

---  

## 🎉 You Did It!  

You now have:

* ✅ A **fully configured C development environment** on ARM macOS.  
* ✅ Multiple ways to **compile** (manual, Makefile, CMake).  
* ✅ **Debugging** with LLDB inside VS Code.  
* ✅ **10+ ready‑to‑run C examples** covering strings, files, networking, system APIs, and more.  
* ✅ A **macOS‑specific toolbox** (alerts, dark mode, battery, cursor, volume, brightness, power control, etc.).  
* ✅ **Best‑practice checklist** and a troubleshooting cheat‑sheet.  

### Next Steps

1. **Pick a tiny project** – e.g. a command‑line todo list that stores its data as JSON.  
2. **Add one feature at a time** – start with file I/O, then add JSON, then a simple GUI using AppKit.  
3. **Version‑control** – `git init`, commit often, push to a private repo on GitHub or GitLab.  
4. **Explore deeper macOS APIs** – try Metal for graphics, AVFoundation for audio, or CoreBluetooth for BLE.  

**Remember:** Every expert was once a beginner. Keep writing, keep reading the docs, and enjoy the speed of native ARM Mac C code! 🚀  

---  

*Document version:* **2.0**  
*Last updated:* **October 2025**  
*Feedback / corrections:* open an issue on the GitHub repo you keep for your notes, or consult the Apple Developer Forums.  

Happy coding! 🎉  