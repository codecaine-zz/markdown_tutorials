# 🌟 Complete C++ Development Guide for ARM Mac (M‑series) with Visual Studio Code  

**Designed for absolute beginners** – every concept is explained in plain English, every command is shown step‑by‑step, and every code snippet is accompanied by a link to the official documentation so you can dig deeper whenever you like.

---

## Table of Contents
0. [Quick Start Checklist](#quick-start)
1. [Why C++ on an ARM Mac?](#why-c-on-an-arm-mac)  
2. [What You’ll Need](#what-you-need)  
3. [Install the Tools (Homebrew, clang/GCC, CMake, Make)](#install-tools)  
4. [Set Up Visual Studio Code (VS Code)](#setup-vs-code)  
5. [Your First Project – “Hello, World!”](#first-project)  
6. [Compiling & Running (Code Runner, Terminal, Makefile, CMake)](#build‑run)  
7. [Debugging Inside VS Code (LLDB)](#debugging)  
8. [Example Gallery (pure C++)](#examples)  
   * 8.1 [String Basics & Manipulation](#example-strings)  
   * 8.2 [Number ↔ String Conversions](#example-numstr)  
   * 8.3 [Functions, Overloads, Default Arguments & Lambdas](#example-functions)  
   * 8.4 [Template Function – Minimum of Two Values](#example-template)  
   * 8.5 [STL Algorithms – Sorting & Searching](#example-stl)  
   * 8.6 [Simple Math REPL](#example-repl)  
   * 8.7 [Temperature Converter (classes & I/O)](#example-temp)  
   * 8.8 [Simple Bank Account System (vectors & smart pointers)](#example-bank)  
   * 8.9 [File‑I/O – CSV Reader](#example-csv)  
   * 8.10 [Simple Regular‑Expression Search](#example-regex)  
   * 8.11 [Guess the Number Game (loops & random numbers)](#example-guess)  
   * 8.12 [JSON Read & Write (simple file example)](#example-json)  
   * 8.13 [HTTP GET & POST (libcurl)](#example-http)  
9. [Best‑Practice Checklist](#best-practices)  
10. [Common Pitfalls & Quick Fixes](#mistakes)  
11. [Handy Keyboard Shortcuts (macOS)](#shortcuts)  
12. [Further Learning & Reference Links](#resources)  
13. [macOS‑Specific API Samples (C++/Objective‑C++)](#macos‑api)  

---  

<a name="why-c-on-an-arm-mac"></a>
## 1️⃣ Why C++ on an ARM Mac?  

| Reason | What it gives you |
|--------|-------------------|
| **Speed** | Native machine code runs at the full performance of Apple Silicon chips. |
| **Portability** | Same source builds on Windows, Linux, Intel macOS, and ARM macOS. |
| **Industry relevance** | Used for games, graphics, finance, embedded devices, and more. |

> **Bottom line:** With a few clicks you’ll have a professional‑grade C++ environment that works **exactly** like the one used by large companies.

---  

<a name="what-you-need"></a>
## 2️⃣ What You’ll Need  

| Item | Why? | Minimum version |
|------|------|-----------------|
| **macOS** | The OS that ships with the ARM chip | 12.0 (Monterey) or later |
| **Homebrew** | macOS package manager – one‑line installs for everything | any |
| **Xcode Command‑Line Tools** | Provides `clang`, SDKs, `make`, etc. | any |
| **VS Code** | Powerful editor with C++ extensions | 1.90+ |
| **Git** (optional) | Version control – essential for any real project | any |
| **Internet** | To download the tools | — |

---  

<a name="install-tools"></a>
## 3️⃣ Install the Tools  

Open **Terminal** (`⌘ Space → Terminal`) and follow each block **exactly** as shown.

### 3.1 Install Xcode Command‑Line Tools  

```bash
xcode-select --install
```

> **Docs:** <https://developer.apple.com/library/archive/technotes/tn2339/_index.html>

### 3.2 Install Homebrew  

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

> **Docs:** <https://brew.sh/>

### 3.3 Install a GNU Compiler (optional)  

Apple Silicon already ships with `clang` (perfectly fine).  
If you prefer the GNU tool‑chain:

```bash
brew install gcc          # installs g++‑13, gcc‑13, etc.
```

> **Note:** The binaries are version‑suffixed (`g++-13`).  

### 3.4 Install CMake and make  

```bash
brew install cmake make
```

> **Docs:** CMake – <https://cmake.org/documentation/> | make – <https://www.gnu.org/software/make/manual/make.html>

### 3.5 Verify Installations  

```bash
# clang (bundled with Xcode)
clang++ --version          # Apple clang, version 15+ on macOS 13+

# If you installed GCC:
g++-13 --version

cmake --version
make --version
```

If version numbers appear, you’re ready to go!

---  

<a name="setup-vs-code"></a>
## 4️⃣ Set Up Visual Studio Code  

### 4.1 Install VS Code  

```bash
brew install --cask visual-studio-code
```

> **Docs:** <https://code.visualstudio.com/docs/setup/mac>

### 4.2 Install Required Extensions  

| Extension | What it does |
|-----------|--------------|
| **C/C++** (Microsoft) | IntelliSense, debugging, code navigation |
| **CMake Tools** (Microsoft) | CMake integration, configure/run tasks |
| **Code Runner** (Jun Han) | One‑click run of a single file |
| **GitLens** (optional) | Nice Git UI inside VS Code |

*Install:* `⌘ Shift X` → search → *C/C++* → **Install** (repeat for the others).

### 4.3 Global VS Code Settings (JSON)  

Open the JSON view: `⌘ ,` → click the **Open Settings (JSON)** icon (top‑right).  
Paste the snippet below – adjust the compiler path if you use GCC.

```json
{
  // -------------------------------------------------
  //  Compiler & IntelliSense
  // -------------------------------------------------
  "C_Cpp.default.compilerPath": "/opt/homebrew/bin/g++-13",   // change to clang: "/usr/bin/clang++"
  "C_Cpp.default.intelliSenseMode": "macos-clang-arm64",
  "C_Cpp.default.cppStandard": "c++17",
  "C_Cpp.errorSquiggles": "enabled",

  // -------------------------------------------------
  //  Code Runner – one‑click execution
  // -------------------------------------------------
  "code-runner.executorMap": {
    "cpp": "cd $dir && g++-13 -std=c++17 $fileName -o $fileNameWithoutExt && ./$fileNameWithoutExt"
  },
  "code-runner.runInTerminal": true,
  "code-runner.clearPreviousOutput": true,

  // -------------------------------------------------
  //  Formatting & Linting (optional)
  // -------------------------------------------------
  "editor.formatOnSave": true,
  "C_Cpp.clang_format_style": "Google"
}
```

> **Docs:** VS Code settings – <https://code.visualstudio.com/docs/getstarted/settings>

### 4.4 Create a Workspace Folder  

`File → Open…` → pick/create a folder such as `~/cpp-projects`.  
All files you create will live inside this folder.

---  

<a name="first-project"></a>
## 5️⃣ Your First Project – “Hello, World!”  

### 5.1 Create the Project Structure  

```bash
mkdir -p ~/cpp-projects/hello-world/src
cd ~/cpp-projects/hello-world
code .            # launches VS Code in this folder
```

### 5.2 Write `src/main.cpp`

```cpp
// src/main.cpp
#include <iostream>   // Lets us print messages and read keyboard input.
#include <string>     // Gives us the easy-to-use text type called std::string.

int main() {
    std::cout << "👋 Hello, ARM Mac World!\n";  // Say hello so we know the program runs.

    std::string name;                            // Create a text box to hold the user’s name.
    std::cout << "What is your name? ";          // Ask the question in the terminal.
    std::getline(std::cin, name);                // Read the whole line exactly as typed.

    std::cout << "Nice to meet you, " << name << "!\n";  // Personalised reply.
    return 0;                                    // 0 means “all good” to the operating system.
}
```

> **Docs:** `<iostream>` – <https://en.cppreference.com/w/cpp/header/iostream> ; `<string>` – <https://en.cppreference.com/w/cpp/header/string>

### 5.3 Build & Run – Three Ways  

| Method | When to use | Steps |
|--------|------------|-------|
| **Code Runner** (quick test) | One file, no makefile needed | Click the **Run** ▶ button or press `Ctrl + Alt + N`. |
| **Terminal (manual)** | You want to see the exact compile command | ```bash<br>g++-13 -std=c++17 src/main.cpp -o hello<br>./hello<br>``` |
| **Makefile** (later) | Project grows, many source files | See the Makefile example in §6.2. |

---  

<a name="build-run"></a>
## 6️⃣ Compiling & Running (Beyond “Hello, World!”)

### 6.1 Simple Makefile (tabs **must** be used for recipe lines)

Create a file named **`Makefile`** in the project root:

```makefile
# -------------------------------------------------
#   Simple Makefile for C++ projects
# -------------------------------------------------
CXX      = g++-13                 # replace with clang++ if you prefer clang
CXXFLAGS = -std=c++17 -Wall -Wextra -g
SRC      = $(wildcard src/*.cpp)
OBJ      = $(SRC:.cpp=.o)
TARGET   = hello

all: $(TARGET)

$(TARGET): $(OBJ)
	$(CXX) $(CXXFLAGS) $^ -o $@

src/%.o: src/%.cpp
	$(CXX) $(CXXFLAGS) -c $< -o $@

clean:
	rm -f $(OBJ) $(TARGET)

.PHONY: all clean
```

> **Important:** Each command line in a recipe **must start with a TAB**, not spaces.  

Now run:

```bash
make          # builds → ./hello
./hello       # executes
make clean    # cleans up
```

> **Docs:** GNU Make manual – <https://www.gnu.org/software/make/manual/make.html>

### 6.2 CMake (recommended for larger projects)

Project layout:

```
hello-cmake/
├─ CMakeLists.txt
└─ src/
   └─ main.cpp
```

**CMakeLists.txt**

```cmake
cmake_minimum_required(VERSION 3.23)   # macOS ships ≥ 3.23
project(HelloCMake LANGUAGES CXX)

set(CMAKE_CXX_STANDARD 17)
set(CMAKE_CXX_STANDARD_REQUIRED ON)

add_executable(hello-cmake src/main.cpp)
```

**Build & run**

```bash
mkdir -p build && cd build
cmake ..                # configure
cmake --build .         # compile
./hello-cmake           # run
```

> **Docs:** CMake tutorial – <https://cmake.org/cmake/help/latest/guide/tutorial/index.html>

---  

<a name="debugging"></a>
## 7️⃣ Debugging Inside VS Code (LLDB)

1. Click the **Run** icon (⌘ + Shift + D).  
2. Press **Create a launch.json file** → choose **C++ (GDB/LLDB)** → **g++ build and debug active file**.  
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
            "preLaunchTask": "C/C++: g++-13 build active file"
        }
    ]
}
```

### How to Debug

* **Set a breakpoint** – click to the left of a line number (red dot appears).  
* Press **F5** (or click the green *Start Debugging* button).  
* Step: **F10** (Step Over), **F11** (Step Into), **⇧ F11** (Step Out).  

> **Docs:** VS Code debugging – <https://code.visualstudio.com/docs/editor/debugging>

---  

<a name="examples"></a>
## 8️⃣ Example Gallery (pure C++)  

Below are small, self‑contained programs you can copy, paste, compile, and run. Each bullet links to the official reference for the used feature.

<a name="example-strings"></a>
### 8.1 String Basics & Manipulation  

```cpp
// strings_demo.cpp
#include <iostream>
#include <string>
#include <algorithm>   // std::reverse, std::transform

int main() {
    std::string s = "Hello, C++ on macOS!";

    // 1️⃣ Length & empty check
    std::cout << "Length: " << s.size() << "\n";
    std::cout << "Is empty? " << std::boolalpha << s.empty() << "\n";

    // 2️⃣ Concatenation & operator+=
    std::string greeting = "Welcome, ";
    greeting += "developer";
    std::cout << greeting << "\n";

    // 3️⃣ Substring extraction
    std::string sub = s.substr(7, 3);   // "C++"
    std::cout << "Substring: " << sub << "\n";

    // 4️⃣ Find & replace (simple)
    size_t pos = s.find("macOS");
    if (pos != std::string::npos) {
        s.replace(pos, 5, "Apple Silicon");
    }
    std::cout << "After replace: " << s << "\n";

    // 5️⃣ To upper‑case using std::transform
    std::string upper = s;
    std::transform(upper.begin(), upper.end(), upper.begin(),
                   [](unsigned char c){ return std::toupper(c); });
    std::cout << "Upper‑case: " << upper << "\n";

    // 6️⃣ Reverse the string
    std::string rev = s;
    std::reverse(rev.begin(), rev.end());
    std::cout << "Reversed: " << rev << "\n";

    // 7️⃣ Split on delimiter (manual loop)
    std::string csv = "apple,banana,cherry";
    std::cout << "Splitting \"" << csv << "\": ";
    size_t start = 0;
    while (true) {
        size_t comma = csv.find(',', start);
        std::string token = csv.substr(start, comma - start);
        std::cout << "[" << token << "] ";
        if (comma == std::string::npos) break;
        start = comma + 1;
    }
    std::cout << "\n";
}
```
*Key concepts:* `size()`, `empty()`, `operator+`/`+=`, `substr`, `find`, `replace`, `std::transform`, `std::reverse`.  
*Docs:* `<string>` – <https://en.cppreference.com/w/cpp/header/string> ; `std::transform` – <https://en.cppreference.com/w/cpp/algorithm/transform>.

---

<a name="example-numstr"></a>
### 8.2 Number ↔ String Conversions  

```cpp
// num_string_conv.cpp
#include <iostream>
#include <string>
#include <sstream>      // std::stringstream
#include <iomanip>      // std::setprecision
#include <charconv>    // std::from_chars, std::to_chars (C++17)

int main() {
    // ----- 1️⃣ integer → string -----
    int intVal = 42;
    std::string intStr = std::to_string(intVal);               // classic
    std::cout << "int → string (std::to_string): " << intStr << "\n";

    // modern using std::to_chars (no heap allocation)
    char buffer[16];
    auto [ptr, ec] = std::to_chars(buffer, buffer + sizeof(buffer), intVal);
    std::string intStr2(buffer, ptr);
    std::cout << "int → string (std::to_chars): " << intStr2 << "\n";

    // ----- 2️⃣ string → integer -----
    std::string src = "12345";
    int parsed = 0;
    std::from_chars(src.data(), src.data() + src.size(), parsed);
    std::cout << "string → int (std::from_chars): " << parsed << "\n";

    // ----- 3️⃣ double ↔ string -----
    double d = 3.14159;
    std::ostringstream oss;
    oss << std::fixed << std::setprecision(3) << d;   // format to 3 decimals
    std::string dblStr = oss.str();
    std::cout << "double → string (ostringstream): " << dblStr << "\n";

    double back = 0.0;
    std::istringstream iss(dblStr);
    iss >> back;
    std::cout << "string → double (istringstream): " << back << "\n";

    // ----- 4️⃣ Using std::stod / std::stoi (throws on error) -----
    std::string mixed = "  99 apples";
    try {
        int val = std::stoi(mixed);       // skips leading whitespace, stops at non‑digit
        std::cout << "std::stoi result: " << val << "\n";
    } catch (const std::invalid_argument& e) {
        std::cerr << "Invalid argument: " << e.what() << "\n";
    }

    // ----- 5️⃣ Hexadecimal conversion -----
    int hexVal = 0xFF;
    std::cout << "hex 0xFF → decimal: " << hexVal << "\n";
    std::cout << "decimal 255 → hex: " << std::hex << hexVal << std::dec << "\n";
}
```
*Key concepts:* `std::to_string`, `std::stoi`, `std::stod`, `std::stringstream`, `std::from_chars` / `std::to_chars` (zero‑allocation), manipulators, exception handling.  
*Docs:* `std::to_string` – <https://en.cppreference.com/w/cpp/string/basic_string/to_string> ; `std::from_chars` – <https://en.cppreference.com/w/cpp/utility/from_chars>.

---

<a name="example-functions"></a>
### 8.3 Functions, Overloads, Default Arguments & Lambdas  

```cpp
// functions_demo.cpp
#include <iostream>
#include <vector>
#include <algorithm>

// 1️⃣ Simple function with default argument
int add(int a, int b = 10) {
    return a + b;
}

// 2️⃣ Overloaded function (different parameter types)
double add(double a, double b) { return a + b; }

// 3️⃣ Variadic template – sum of any number of arguments
template <typename... Args>
auto sum(Args... args) {
    return (args + ...);   // fold expression (C++17)
}

// 4️⃣ Lambda that captures by value and mutates its own copy
auto make_counter = []() {
    int cnt = 0;
    return [cnt]() mutable {
        ++cnt;
        return cnt;
    };
};

int main() {
    std::cout << "add(5)            = " << add(5) << "   // uses default b=10\n";
    std::cout << "add(5, 7)         = " << add(5, 7) << "\n";
    std::cout << "add(2.5, 3.1)     = " << add(2.5, 3.1) << "\n";

    std::cout << "sum(1, 2, 3, 4)   = " << sum(1, 2, 3, 4) << "\n";

    auto counter = make_counter();
    std::cout << "counter() → " << counter() << "\n"; // 1
    std::cout << "counter() → " << counter() << "\n"; // 2
    std::cout << "counter() → " << counter() << "\n"; // 3

    // 5️⃣ Using a lambda with std::for_each
    std::vector<int> vec = {1, 2, 3, 4, 5};
    std::cout << "Elements doubled: ";
    std::for_each(vec.begin(), vec.end(),
                  [](int &x){ x *= 2; std::cout << x << ' '; });
    std::cout << '\n';
}
```
*Key concepts:* default parameters, function overloading, variadic templates with fold expressions, capturing lambdas, mutable lambdas, standard algorithms with lambda callbacks.  
*Docs:* Default arguments – <https://en.cppreference.com/w/cpp/language/default_arguments> ; Overloading – <https://en.cppreference.com/w/cpp/language/overload> ; Variadic templates – <https://en.cppreference.com/w/cpp/language/parameter_pack> ; Lambdas – <https://en.cppreference.com/w/cpp/language/lambda>.

---

<a name="example-template"></a>
### 8.4 Template Function – Minimum of Two Values  

```cpp
// min_template.cpp
#include <iostream>

template <typename T>
T minValue(const T& a, const T& b) { return (a < b) ? a : b; }

int main() {
    std::cout << "min(3,7)   = " << minValue(3,7)   << '\n';
    std::cout << "min(2.5,1.8) = " << minValue(2.5,1.8) << '\n';
    std::cout << "min('z','a') = " << minValue('z','a') << '\n';
}
```
*Docs:* Function templates – <https://en.cppreference.com/w/cpp/language/template>

---

<a name="example-stl"></a>
### 8.5 STL Algorithms – Sorting & Searching  

```cpp
// stl_algos.cpp
#include <iostream>
#include <vector>
#include <algorithm>
#include <random>

int main() {
    std::vector<int> nums(10);
    std::random_device rd;
    std::mt19937 gen(rd());
    std::uniform_int_distribution<> dis(1, 100);
    for (int& n : nums) n = dis(gen);

    std::cout << "Unsorted: "; for (int n : nums) std::cout << n << ' '; std::cout << '\n';
    std::sort(nums.begin(), nums.end());

    std::cout << "Sorted:   "; for (int n : nums) std::cout << n << ' '; std::cout << '\n';

    int target = nums[5];
    bool found = std::binary_search(nums.begin(), nums.end(), target);
    std::cout << "Is " << target << " present? " << (found ? "yes" : "no") << '\n';
}
```
*Docs:* `std::sort` – <https://en.cppreference.com/w/cpp/algorithm/sort>

---

<a name="example-repl"></a>
### 8.6 Simple Math REPL  

```cpp
// repl.cpp
#include <iostream>
#include <string>
#include <sstream>

int main() {
    std::cout << "Simple Math REPL – type \"exit\" to quit\n";
    std::string line;
    while (true) {
        std::cout << "> ";
        std::getline(std::cin, line);
        if (line == "exit") break;

        std::istringstream iss(line);
        double a, b; char op;
        if (!(iss >> a >> op >> b)) {
            std::cout << "Invalid expression. Use: <num> <op> <num>\n";
            continue;
        }

        double result = 0;
        switch (op) {
            case '+': result = a + b; break;
            case '-': result = a - b; break;
            case '*': result = a * b; break;
            case '/':
                if (b == 0) { std::cout << "divide‑by‑zero!\n"; continue; }
                result = a / b; break;
            default: std::cout << "Unsupported operator\n"; continue;
        }
        std::cout << " = " << result << '\n';
    }
}
```
*Docs:* `std::istringstream` – <https://en.cppreference.com/w/cpp/io/basic_istringstream>

---

<a name="example-temp"></a>
### 8.7 Temperature Converter (classes & I/O)  

```cpp
// temperature_converter.cpp
#include <iostream>
#include <iomanip>

class Temperature {
public:
    static double celsiusToFahrenheit(double c) { return (c * 9.0/5.0) + 32.0; }
    static double fahrenheitToCelsius(double f) { return (f - 32.0) * 5.0/9.0; }
};

int main() {
    int choice; double value;
    std::cout << "1) C → F   2) F → C   (0 to quit): ";
    while (std::cin >> choice && choice != 0) {
        std::cout << "Enter temperature: ";
        std::cin >> value;
        std::cout << std::fixed << std::setprecision(2);
        if (choice == 1)
            std::cout << value << "°C = " << Temperature::celsiusToFahrenheit(value) << "°F\n";
        else if (choice == 2)
            std::cout << value << "°F = " << Temperature::fahrenheitToCelsius(value) << "°C\n";
        else
            std::cout << "Invalid option!\n";
        std::cout << "\n1) C → F   2) F → C   (0 to quit): ";
    }
}
```
*Docs:* `std::fixed` – <https://en.cppreference.com/w/cpp/io/manip/fixed>

---

<a name="example-bank"></a>
### 8.8 Simple Bank Account System (vectors & smart pointers)  

```cpp
// bank_account.cpp
#include <iostream>
#include <string>
#include <vector>
#include <memory>
#include <iomanip>

class BankAccount {
    std::string holder_;
    double balance_;
    int number_;
    static int nextNumber_;

public:
    BankAccount(std::string holder, double init = 0.0)
        : holder_(std::move(holder)), balance_(init), number_(++nextNumber_) {}

    void deposit(double amt)   { if (amt > 0) balance_ += amt; }
    void withdraw(double amt)  { if (amt > 0 && amt <= balance_) balance_ -= amt; }

    void print() const {
        std::cout << "Account #" << number_ << " – " << holder_
                  << " – $" << std::fixed << std::setprecision(2) << balance_ << '\n';
    }
};

int BankAccount::nextNumber_ = 1000;   // first account will be #1001

int main() {
    std::vector<std::unique_ptr<BankAccount>> accounts;
    accounts.emplace_back(std::make_unique<BankAccount>("Alice", 1500));
    accounts.emplace_back(std::make_unique<BankAccount>("Bob"));

    accounts[0]->deposit(200);
    accounts[1]->withdraw(50);   // insufficient funds → ignored
    for (const auto& acc : accounts) acc->print();
}
```
*Docs:* `std::unique_ptr` – <https://en.cppreference.com/w/cpp/memory/unique_ptr>

---

<a name="example-csv"></a>
### 8.9 CSV Reader (File‑I/O)  

```cpp
// csv_reader.cpp
#include <iostream>
#include <fstream>
#include <sstream>
#include <vector>
#include <string>

using Row = std::vector<std::string>;

Row split(const std::string& line, char delim = ',') {
    Row result;
    std::stringstream ss(line);
    std::string cell;
    while (std::getline(ss, cell, delim)) result.push_back(cell);
    return result;
}

int main() {
    std::ifstream in("data.csv");
    if (!in) { std::cerr << "Cannot open data.csv\n"; return 1; }

    std::vector<Row> table;
    std::string line;
    while (std::getline(in, line)) table.push_back(split(line));

    for (const auto& row : table) {
        for (size_t i = 0; i < row.size(); ++i) {
            std::cout << row[i];
            if (i + 1 < row.size()) std::cout << " | ";
        }
        std::cout << '\n';
    }
}
```
*Docs:* `std::ifstream` – <https://en.cppreference.com/w/cpp/io/basic_ifstream>

#### 8.9.1 CSV Pitfalls with Different Strings (commas, quotes, new lines, UTF‑8)

- Commas inside a value: they must be wrapped in quotes, like "ACME, Inc".
- Quotes inside a value: double them, like "He said ""Hello""" (CSV standard).
- New lines inside a cell: allowed if the cell is quoted.
- Leading UTF‑8 BOM (byte‑order mark): some tools add it; you may need to strip it.
- Windows line endings (CRLF) vs macOS (LF): handle both when reading.

```cpp
// csv_safe_reader_writer.cpp
// This version safely reads CSV rows with quotes and writes rows with correct quoting.
// Plain-English idea: we walk each character, remember if we're "inside quotes", and split only on commas that are outside quotes.
#include <iostream>
#include <fstream>
#include <string>
#include <vector>

using Row = std::vector<std::string>;

// Remove UTF‑8 BOM if present at the start of a string.
static inline void strip_bom(std::string& s) {
    if (s.size() >= 3 &&
        static_cast<unsigned char>(s[0]) == 0xEF &&
        static_cast<unsigned char>(s[1]) == 0xBB &&
        static_cast<unsigned char>(s[2]) == 0xBF) {
        s.erase(0, 3);
    }
}

// Parse one CSV line according to common CSV rules (RFC 4180‑style).
Row parse_csv_line(const std::string& line) {
    Row out;
    std::string cell;
    bool in_quotes = false;

    for (size_t i = 0; i < line.size(); ++i) {
        char c = line[i];
        if (c == '"') {
            if (in_quotes && i + 1 < line.size() && line[i + 1] == '"') {
                cell.push_back('"'); // Doubled quote inside a quoted field → literal quote.
                ++i;                 // Skip the second quote.
            } else {
                in_quotes = !in_quotes; // Enter or leave quoted mode.
            }
        } else if (c == ',' && !in_quotes) {
            out.push_back(cell);
            cell.clear();
        } else {
            cell.push_back(c);
        }
    }
    out.push_back(cell);
    return out;
}

// Quote a cell if needed (contains comma, quote, or newline). Double quotes inside.
std::string quote_csv_cell(const std::string& cell) {
    bool need_quotes = false;
    for (unsigned char ch : cell) {
        if (ch == ',' || ch == '"' || ch == '\n' || ch == '\r') { need_quotes = true; break; }
    }
    if (!need_quotes) return cell;

    std::string escaped;
    escaped.reserve(cell.size() + 2);
    escaped.push_back('"');
    for (char ch : cell) {
        if (ch == '"') escaped.push_back('"'); // double up
        escaped.push_back(ch);
    }
    escaped.push_back('"');
    return escaped;
}

int main() {
    // Read
    std::ifstream in("people.csv");
    if (!in) { std::cerr << "Cannot open people.csv\n"; return 1; }

    std::string line;
    bool first = true;
    while (std::getline(in, line)) {           // Handles LF and CRLF
        if (first) { strip_bom(line); first = false; } // Remove BOM if the file starts with it.
        Row row = parse_csv_line(line);
        std::cout << "Row with " << row.size() << " cells\n";
        for (const auto& cell : row) std::cout << "  [" << cell << "]\n";
    }

    // Write
    std::ofstream out("people_out.csv");
    if (!out) { std::cerr << "Cannot create people_out.csv\n"; return 1; }

    Row header = {"Name", "Note", "Score"};
    Row r1 = {"ACME, Inc", "He said \"Hello\"\nOn Monday", "100"};
    Row r2 = {"Zoë", "UTF‑8 works fine", "98"};

    auto write_row = [&](const Row& r) {
        for (size_t i = 0; i < r.size(); ++i) {
            if (i) out << ',';
            out << quote_csv_cell(r[i]);
        }
        out << '\n';
    };

    write_row(header);
    write_row(r1);
    write_row(r2);

    std::cout << "Wrote people_out.csv with safe quoting.\n";
}
```

> Tip: If a spreadsheet shows “weird characters,” ensure the CSV is saved as UTF‑8.

---  

<a name="example-regex"></a>
### 8.10 Simple Regular‑Expression Search  

```cpp
// regex_demo.cpp
#include <iostream>
#include <string>
#include <regex>

int main() {
    std::string text = "Contact: alice@example.com, bob@test.org, carol@foo.io";

    // Regex for simple e‑mail addresses (very naive)
    std::regex email_regex(R"(([\w.%+-]+)@([\w.-]+\.[a-zA-Z]{2,}))");
    auto words_begin = std::sregex_iterator(text.begin(), text.end(), email_regex);
    auto words_end   = std::sregex_iterator();

    std::cout << "Found " << std::distance(words_begin, words_end) << " e‑mail addresses:\n";
    for (std::sregex_iterator i = words_begin; i != words_end; ++i) {
        std::smatch match = *i;
        std::cout << "  " << match.str() << "   (user=" << match[1] << ", domain=" << match[2] << ")\n";
    }

    // Replace all e‑mail addresses with "[redacted]"
    std::string redacted = std::regex_replace(text, email_regex, "[redacted]");
    std::cout << "\nRedacted text:\n" << redacted << '\n';
}
```
*Key concepts:* `std::regex`, `std::smatch`, `std::regex_iterator`, `std::regex_replace`.  
*Docs:* `<regex>` – <https://en.cppreference.com/w/cpp/header/regex>.

---  

<a name="example-guess"></a>
### 8.11 Guess the Number Game (loops & random numbers)  

```cpp
// guess_number.cpp
#include <iostream>      // Basic input/output streams.
#include <random>        // Tools to create fair random numbers.
#include <string>        // Used for safely reading the player’s guesses.

int main() {
    std::random_device rd;                       // Hardware seed so the game changes each run.
    std::mt19937 gen(rd());                      // Mersenne Twister: reliable random generator.
    std::uniform_int_distribution<> dist(1, 50); // Pick numbers between 1 and 50, inclusive.
    const int secret = dist(gen);                // The number the player must guess.

    std::cout << "🎯 Guess the Number (1–50). Type 'quit' to exit.\n";

    std::string input;
    int attempts = 0;
    while (true) {
        std::cout << "Enter your guess: ";
        std::getline(std::cin, input);           // Accepts blank lines or words safely.

        if (input == "quit") {                   // Friendly exit option for the player.
            std::cout << "No worries—maybe next time!\n";
            break;
        }

        try {
            int guess = std::stoi(input);        // Convert what they typed into a whole number.
            ++attempts;

            if (guess < secret) {
                std::cout << "Too low ❄️  Try a bigger number.\n";
            } else if (guess > secret) {
                std::cout << "Too high 🔥  Try a smaller number.\n";
            } else {
                std::cout << "🎉 You got it in " << attempts << " tries! Great job!\n";
                break;                           // Game over: the player nailed it.
            }
        } catch (const std::exception&) {        // Handles words or invalid numbers politely.
            std::cout << "Please type a whole number between 1 and 50, or 'quit'.\n";
        }
    }
}
```
*Key concepts:* user-friendly loops, safe string-to-number conversion, modern random number utilities, and gentle error handling for new programmers.  
*Docs:* Random utilities – <https://en.cppreference.com/w/cpp/numeric/random>; `std::stoi` – <https://en.cppreference.com/w/cpp/string/basic_string/stol>.

---

<a name="example-json"></a>
### 8.12 JSON Read & Write (simple file example)

Plain-English goal: save some data to a JSON file (looks like a dictionary), then load it back.

Install the header-only library (one-time):
```bash
brew install nlohmann-json
```

Compile (adjust compiler if using clang++):
```bash
g++-13 -std=c++17 -I /opt/homebrew/include json_read_write.cpp -o json_rw
./json_rw
```

```cpp
// json_read_write.cpp
// We use a tiny header-only library so beginners avoid complex setup.
#include <fstream>        // File reading/writing.
#include <iostream>       // Print friendly messages.
#include <nlohmann/json.hpp> // Installed via Homebrew: nlohmann-json (header-only).

using nlohmann::json;

int main() {
    // 1) Create data in memory (like a contact card).
    json person = {
        {"name", "Alice"},
        {"age", 30},
        {"skills", {"C++", "Metal", "CMake"}},
        {"active", true}
    };

    // 2) Write it to a file in human-readable form.
    {
        std::ofstream out("person.json");
        if (!out) { std::cerr << "Cannot create person.json\n"; return 1; }
        out << person.dump(2) << '\n'; // 2 = pretty indentation so humans can read it.
    }

    // 3) Read it back from disk and access fields safely.
    json loaded;
    {
        std::ifstream in("person.json");
        if (!in) { std::cerr << "Cannot open person.json\n"; return 1; }
        in >> loaded; // The library parses the text for you.
    }

    // 4) Print values (with simple checks).
    if (loaded.contains("name")) {
        std::cout << "Name: " << loaded["name"].get<std::string>() << "\n";
    }
    int age = loaded.value("age", 0); // default to 0 if missing
    std::cout << "Age: " << age << "\n";

    // 5) Update and save again (like changing a setting).
    loaded["active"] = false;
    std::ofstream out2("person_updated.json");
    out2 << loaded.dump(2) << '\n';

    std::cout << "Wrote person.json and person_updated.json\n";
    return 0;
}
```

*Why this approach?* It’s one header file, no complex build steps, and the code reads like English.

---

<a name="example-http"></a>
### 8.13 HTTP GET & POST (libcurl)

Plain-English goal: talk to a web server. We’ll first GET some data (like reading a page), then POST some data (like sending a form).

Install the small networking library (one-time):
```bash
brew install curl
```

Compile (choose one):
```bash
# Easiest if pkg-config is available
g++-13 -std=c++17 http_curl.cpp $(pkg-config --cflags --libs libcurl) -o http_curl

# Or with explicit Homebrew paths (Apple Silicon default prefix)
g++-13 -std=c++17 http_curl.cpp -I /opt/homebrew/opt/curl/include -L /opt/homebrew/opt/curl/lib -lcurl -o http_curl
```

```cpp
// http_curl.cpp
// Idea in plain words: use a tiny helper to download/upload text over the internet.
// We'll show a GET (read-only) and a POST (send data) and print friendly results.
#include <curl/curl.h>
#include <iostream>
#include <string>

// When the server sends back data, this function appends it to a string we provide.
static size_t write_to_string(void* ptr, size_t size, size_t nmemb, void* userdata) {
    size_t bytes = size * nmemb;
    std::string* out = static_cast<std::string*>(userdata);
    out->append(static_cast<char*>(ptr), bytes);
    return bytes;
}

struct HttpResult {
    long status = 0;          // 200 means OK
    std::string body;         // Text we received from the server
    std::string error;        // Human-readable error if something went wrong
};

HttpResult http_get(const std::string& url) {
    HttpResult res;
    CURL* curl = curl_easy_init();
    if (!curl) { res.error = "Failed to init curl"; return res; }

    char errbuf[CURL_ERROR_SIZE] = {0};
    std::string response;

    curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
    curl_easy_setopt(curl, CURLOPT_ERRORBUFFER, errbuf);
    curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);   // Follow redirects
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_to_string);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);

    CURLcode code = curl_easy_perform(curl);
    if (code != CURLE_OK) {
        res.error = errbuf[0] ? errbuf : curl_easy_strerror(code);
    } else {
        curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &res.status);
        res.body = std::move(response);
    }
    curl_easy_cleanup(curl);
    return res;
}

HttpResult http_post(const std::string& url, const std::string& data, const std::string& content_type) {
    HttpResult res;
    CURL* curl = curl_easy_init();
    if (!curl) { res.error = "Failed to init curl"; return res; }

    char errbuf[CURL_ERROR_SIZE] = {0};
    std::string response;

    struct curl_slist* headers = nullptr;
    std::string ct = "Content-Type: " + content_type; // e.g., application/json
    headers = curl_slist_append(headers, ct.c_str());

    curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
    curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
    curl_easy_setopt(curl, CURLOPT_ERRORBUFFER, errbuf);
    curl_easy_setopt(curl, CURLOPT_POST, 1L);
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data.c_str());
    curl_easy_setopt(curl, CURLOPT_POSTFIELDSIZE, data.size());
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_to_string);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);

    CURLcode code = curl_easy_perform(curl);
    if (code != CURLE_OK) {
        res.error = errbuf[0] ? errbuf : curl_easy_strerror(code);
    } else {
        curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &res.status);
        res.body = std::move(response);
    }

    curl_slist_free_all(headers);
    curl_easy_cleanup(curl);
    return res;
}

int main() {
    // One-time global setup/teardown for the library.
    curl_global_init(CURL_GLOBAL_DEFAULT);

    std::cout << "GET example…\n";
    auto getRes = http_get("https://httpbin.org/get?hello=world");
    if (!getRes.error.empty()) {
        std::cout << "GET error: " << getRes.error << "\n";
    } else {
        std::cout << "GET status: " << getRes.status << "\n";
        std::cout << "GET body (first 300 chars):\n"
                  << getRes.body.substr(0, 300) << "\n\n";
    }

    std::cout << "POST example…\n";
    std::string jsonPayload = R"({"name":"Alice","score":100})";
    auto postRes = http_post("https://httpbin.org/post", jsonPayload, "application/json");
    if (!postRes.error.empty()) {
        std::cout << "POST error: " << postRes.error << "\n";
    } else {
        std::cout << "POST status: " << postRes.status << "\n";
        std::cout << "POST body (first 300 chars):\n"
                  << postRes.body.substr(0, 300) << "\n";
    }

    curl_global_cleanup();
    return 0;
}
```

Notes:
- If a corporate proxy is used, set environment variables like HTTPS_PROXY before running.
- Status 200 means success; other codes (e.g., 400, 401, 404) indicate server-side responses you can handle.

---

<a name="best-practices"></a>
## 9️⃣ Best‑Practice Checklist

Think of these as "success habits" that professional programmers follow every day.

| Practice | Why it matters | How to do it |
|----------|----------------|--------------|
| **Use version control (Git)** | Never lose work; collaborate easily | `git init`, commit often with clear messages |
| **Write comments in plain English** | Your future self (and teammates) will thank you | `// This loop finds the highest score in the list` |
| **Use meaningful names** | Code reads like a story | `totalPrice` beats `tp`; `calculateTax()` beats `ct()` |
| **Check for errors** | Programs crash less, users stay happy | Always check if files opened, if input is valid |
| **Keep functions small** | Easier to test, easier to understand | One function = one clear job (like "calculate tax") |
| **Enable compiler warnings** | Catches mistakes early | Use `-Wall -Wextra` flags |
| **Use modern C++ features** | Less error-prone, cleaner code | Prefer `std::string` over `char[]`, `std::vector` over raw arrays |
| **Test incrementally** | Find bugs when they're tiny | Run your code after every small change |
| **Format consistently** | Professional appearance | Use an auto-formatter (clang-format, built into VS Code) |
| **Read error messages carefully** | They tell you exactly what's wrong | Start from the *first* error; later ones often vanish when you fix it |

> **Pro tip:** When stuck, explain your code out loud (or to a rubber duck). If you can't explain it, you probably found the bug!

---

<a name="mistakes"></a>
## 🔟 Common Pitfalls & Quick Fixes

Even experts hit these snags. Here's how to dodge them:

| Problem | Symptom | Fix |
|---------|---------|-----|
| **Forgot to include a header** | `error: 'cout' was not declared` | Add `#include <iostream>` at the top |
| **Missing semicolon** | `error: expected ';' before 'return'` | Every statement needs `;` at the end |
| **Wrong variable type** | `warning: conversion from 'double' to 'int'` | Use the right type or cast explicitly: `int x = static_cast<int>(3.14);` |
| **Uninitialized variable** | Random garbage values | Always initialize: `int count = 0;` |
| **Array out of bounds** | Crash or weird behavior | Check index: `if (i < myArray.size()) { ... }` |
| **Forgot `return` in non-void function** | `warning: control reaches end of non-void function` | Add `return someValue;` before the closing `}` |
| **Mixing `std::cin >>` and `std::getline`** | Skips input unexpectedly | Use `std::cin.ignore()` after `>>` before `getline` |
| **Pointer/reference confusion** | Segmentation fault | Prefer references for parameters unless you need nullptr |
| **Forgetting `std::` prefix** | `error: 'vector' is not a type` | Write `std::vector` or add `using std::vector;` |
| **Comparing strings with `==` in C-style** | Always false | Use `std::string` and `==` works perfectly |

> **When debugging:** Print variables (`std::cout << "x = " << x << '\n';`) or use VS Code's debugger to step through line-by-line.

---

<a name="shortcuts"></a>
## 1️⃣1️⃣ Handy Keyboard Shortcuts (macOS)

Speed up your workflow with these time-savers inside **VS Code**:

| Shortcut | What it does |
|----------|--------------|
| `⌘ P` | Quick file open (start typing filename) |
| `⌘ Shift P` | Command palette (access any VS Code command) |
| `⌘ B` | Toggle sidebar (file explorer) |
| `⌘ /` | Comment/uncomment selected lines |
| `⌥ ↑` / `⌥ ↓` | Move line(s) up/down |
| `⌘ D` | Select next occurrence of current word (multi-cursor editing) |
| `⌘ Shift L` | Select all occurrences of current word |
| `F5` | Start debugging |
| `F9` | Toggle breakpoint on current line |
| `F10` | Step over (debugger) |
| `F11` | Step into (debugger) |
| `Ctrl + ` ` | Toggle integrated terminal |
| `⌘ K, ⌘ C` | Comment block (press ⌘K, release, then press ⌘C) |
| `⌘ K, ⌘ U` | Uncomment block |
| `⌘ Shift F` | Search across all files in workspace |

---

<a name="resources"></a>
## 1️⃣2️⃣ Further Learning & Reference Links

Your learning journey doesn't stop here. Bookmark these trusted resources:

### 📚 Official Documentation
- **C++ Reference** – <https://en.cppreference.com/> (the bible of C++ features)
- **ISO C++ FAQ** – <https://isocpp.org/faq> (answers from the standards committee)
- **CMake Docs** – <https://cmake.org/documentation/>
- **Apple Developer Docs** – <https://developer.apple.com/documentation/> (for macOS APIs)

### 🎓 Interactive Learning
- **LearnCpp.com** – <https://www.learncpp.com/> (free, thorough, beginner-friendly)
- **C++ Core Guidelines** – <https://isocpp.github.io/CppCoreGuidelines/> (best practices from experts)
- **Compiler Explorer** – <https://godbolt.org/> (see how your code compiles to assembly)

### 📺 Video Tutorials
- **The Cherno (YouTube)** – C++ series from beginner to advanced
- **CppCon Talks** – <https://www.youtube.com/user/CppCon> (conference talks on modern C++)

### 📖 Books (Classic & Modern)
- *C++ Primer* (Lippman, Lajoie, Moo) – comprehensive intro
- *Effective Modern C++* (Scott Meyers) – best practices for C++11/14
- *A Tour of C++* (Bjarne Stroustrup) – quick overview by the language creator

### 🛠️ Tools & Libraries
- **Homebrew** – <https://brew.sh/> (package manager for macOS)
- **vcpkg** – <https://vcpkg.io/> (C++ library manager, cross-platform)
- **Conan** – <https://conan.io/> (another popular C++ package manager)

---

<a name="macos-api"></a>
## 1️⃣3️⃣ macOS‑Specific API Samples (C++/Objective‑C++)

Apple's operating system gives your C++ programs super-powers: play sounds, show pop-up windows, read battery status, detect dark mode, and much more. These examples use **Objective-C++** (regular C++ with a sprinkle of Apple's square-bracket syntax) so you can talk directly to macOS.

> **What's Objective-C++?** It's just C++ files with a `.mm` extension instead of `.cpp`. The compiler understands both languages in the same file, letting you call Apple's built-in functions (called "frameworks") from your C++ code.

### 🔧 Compilation Basics

All these examples compile with this pattern:

```bash
# For Foundation-only examples (no GUI)
clang++ -std=c++17 -framework Foundation example.mm -o example

# For AppKit examples (windows, dialogs, menus)
clang++ -std=c++17 -framework Foundation -framework AppKit example.mm -o example

# For sound/video (AVFoundation)
clang++ -std=c++17 -framework Foundation -framework AVFoundation example.mm -o example

# For Metal (3D graphics)
clang++ -std=c++17 -framework Foundation -framework Metal -framework MetalKit example.mm -o example
```

The `-framework SomeName` flag tells the compiler "please include Apple's SomeName library."

---

### 13.1 Display a Simple Alert Box (AppKit)

**What it does:** Shows a pop-up message with an "OK" button, just like when your Mac asks "Are you sure?"

**When to use it:** Notify the user of errors, success, or important info.

```objc
// alert_box.mm
// Idea: Create a small window that grabs the user's attention.
#import <Cocoa/Cocoa.h>
#include <iostream>

int main() {
    @autoreleasepool {
        // The @autoreleasepool handles memory automatically (Apple's way of cleaning up).
        
        NSAlert* alert = [[NSAlert alloc] init];  // Create a new alert object.
        [alert setMessageText:@"Hello from C++!"];       // Big bold title.
        [alert setInformativeText:@"This is your first macOS dialog box."]; // Smaller detail text.
        [alert addButtonWithTitle:@"Cool!"];      // The button the user clicks.
        
        [alert runModal];  // Show the alert and wait until the user clicks "Cool!"
        
        std::cout << "User closed the alert.\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit alert_box.mm -o alert_box
./alert_box
```

**Plain-English breakdown:**
- `NSAlert` = a pre-built dialog box class from Apple
- `setMessageText:` = the big headline
- `setInformativeText:` = the explanation underneath
- `runModal` = "show this and wait for the user to respond"

---

### 13.2 Check Battery Status (IOKit)

**What it does:** Tells you if your Mac is plugged in and how much battery is left (0–100%).

**When to use it:** Build apps that pause heavy work when battery is low, or warn the user.

```objc
// battery_status.mm
// Idea: Ask the operating system "How's my battery doing?"
#import <Foundation/Foundation.h>
#import <IOKit/ps/IOPowerSources.h>
#import <IOKit/ps/IOPSKeys.h>
#include <iostream>

int main() {
    @autoreleasepool {
        // Get the "power source" info (Apple's term for battery/AC adapter).
        CFTypeRef powerInfo = IOPSCopyPowerSourcesInfo();
        CFArrayRef powerSources = IOPSCopyPowerSourcesList(powerInfo);
        
        if (CFArrayGetCount(powerSources) == 0) {
            std::cout << "No battery detected (desktop Mac?).\n";
        } else {
            // Look at the first power source (the internal battery).
            CFDictionaryRef powerDict = IOPSGetPowerSourceDescription(powerInfo, CFArrayGetValueAtIndex(powerSources, 0));
            
            // Is it charging? (YES if plugged into power)
            CFBooleanRef isCharging = (CFBooleanRef)CFDictionaryGetValue(powerDict, CFSTR(kIOPSIsChargingKey));
            bool charging = (isCharging == kCFBooleanTrue);
            
            // Current percentage (0–100)
            CFNumberRef currentCapacity = (CFNumberRef)CFDictionaryGetValue(powerDict, CFSTR(kIOPSCurrentCapacityKey));
            int capacity = 0;
            if (currentCapacity) CFNumberGetValue(currentCapacity, kCFNumberIntType, &capacity);
            
            std::cout << "🔋 Battery: " << capacity << "%\n";
            std::cout << (charging ? "⚡ Charging\n" : "🔌 On battery\n");
        }
        
        CFRelease(powerSources);
        CFRelease(powerInfo);
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework IOKit battery_status.mm -o battery_status
./battery_status
```

**Plain-English breakdown:**
- `IOPSCopyPowerSourcesInfo()` = "Give me battery data"
- `CFDictionaryGetValue` = look up a specific fact (like "current charge percentage")
- `CFRelease` = clean up memory (required when using Apple's "CF" functions)

---

### 13.3 Detect Dark Mode (AppKit)

**What it does:** Checks if the user enabled Dark Mode in System Preferences.

**When to use it:** Adjust your app's colors or UI to match the system theme.

```objc
// dark_mode_check.mm
// Idea: Ask macOS "Is the user in Dark Mode?"
#import <AppKit/AppKit.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSString* theme = [[NSUserDefaults standardUserDefaults] stringForKey:@"AppleInterfaceStyle"];
        // Apple stores the theme name as "Dark" if dark mode is on, or nil (nothing) if light mode.
        
        bool isDark = (theme != nil && [theme isEqualToString:@"Dark"]);
        std::cout << (isDark ? "🌙 Dark Mode is ON\n" : "☀️ Light Mode is ON\n");
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit dark_mode_check.mm -o dark_mode
./dark_mode
```

**Plain-English breakdown:**
- `NSUserDefaults` = a big key-value storage where macOS keeps user preferences
- `AppleInterfaceStyle` = the key name Apple uses for "Dark" or nothing
- `nil` = Objective-C's word for "nothing" or "null"

---

### 13.4 Play a Sound File (AVFoundation)

**What it does:** Plays an audio file (`.mp3`, `.wav`, `.m4a`, etc.) through your speakers.

**When to use it:** Add sound effects, play music, or give audio feedback in apps.

```objc
// play_sound.mm
// Idea: Use Apple's audio player to load and play a file.
#import <Foundation/Foundation.h>
#import <AVFoundation/AVFoundation.h>
#include <iostream>
#include <thread>
#include <chrono>

int main(int argc, char* argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            std::cerr << "Usage: ./play_sound <path_to_audio_file>\n";
            return 1;
        }
        
        NSString* filePath = [NSString stringWithUTF8String:argv[1]];
        NSURL* fileURL = [NSURL fileURLWithPath:filePath];
        
        NSError* error = nil;
        AVAudioPlayer* player = [[AVAudioPlayer alloc] initWithContentsOfURL:fileURL error:&error];
        
        if (error) {
            std::cerr << "Error loading file: " << [[error localizedDescription] UTF8String] << "\n";
            return 1;
        }
        
        [player play];
        std::cout << "🎵 Playing audio... (duration: " << [player duration] << " seconds)\n";
        
        // Wait for the sound to finish (sleep for slightly longer than the duration).
        std::this_thread::sleep_for(std::chrono::milliseconds(static_cast<int>([player duration] * 1000) + 500));
        
        std::cout << "✅ Finished playing.\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AVFoundation play_sound.mm -o play_sound
./play_sound /System/Library/Sounds/Ping.aiff   # Built-in macOS sound
```

**Plain-English breakdown:**
- `AVAudioPlayer` = Apple's class that handles all audio playback
- `initWithContentsOfURL:error:` = "load this file, and tell me if it fails"
- `play` = start playback immediately
- We sleep the main thread so the program doesn't exit before the sound finishes

---

### 13.5 Show a File Open Dialog (AppKit)

**What it does:** Opens the standard "Choose a file" window so users can pick a file from their folders.

**When to use it:** Let users select which file your app should process (images, documents, etc.).

```objc
// file_open_dialog.mm
// Idea: Show the familiar macOS file picker, just like in TextEdit or Photos.
#import <AppKit/AppKit.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSOpenPanel* panel = [NSOpenPanel openPanel];  // Create the file picker window.
        
        [panel setCanChooseFiles:YES];         // Allow selecting files.
        [panel setCanChooseDirectories:NO];    // Don't allow selecting folders (only files).
        [panel setAllowsMultipleSelection:NO]; // Only one file at a time.
        
        // Optional: limit to specific file types (uncomment the next line if you want).
        // [panel setAllowedFileTypes:@[@"txt", @"cpp", @"mm"]];
        
        if ([panel runModal] == NSModalResponseOK) {
            NSURL* selectedFile = [[panel URLs] firstObject];  // Get the file the user picked.
            NSString* path = [selectedFile path];
            std::cout << "✅ User selected: " << [path UTF8String] << "\n";
        } else {
            std::cout << "❌ User cancelled.\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit file_open_dialog.mm -o file_dialog
./file_dialog
```

**Plain-English breakdown:**
- `NSOpenPanel` = the standard macOS file picker
- `runModal` = show it and wait for the user's choice
- `NSModalResponseOK` = user clicked "Open" (not "Cancel")
- The result is an `NSURL` object, which we convert to a C++ string

---

### 13.5A Keyboard Input Dialogs (Text Input from Users) (AppKit)

**What it does:** Shows dialog boxes where users can type text, passwords, or make choices—just like when apps ask "What's your name?" or "Enter password."

**When to use it:** Get user input for settings, login credentials, search queries, or any text data your app needs.

#### Example 1: Simple Text Input Dialog

```objc
// text_input_dialog.mm
// Idea: Ask the user to type something and capture what they enter.
#import <AppKit/AppKit.h>
#include <iostream>
#include <string>

std::string showTextInputDialog(const std::string& title, const std::string& message, const std::string& defaultValue = "") {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        [alert addButtonWithTitle:@"OK"];
        [alert addButtonWithTitle:@"Cancel"];
        
        // Create a text field (the box where users type).
        NSTextField* input = [[NSTextField alloc] initWithFrame:NSMakeRect(0, 0, 300, 24)];
        [input setStringValue:[NSString stringWithUTF8String:defaultValue.c_str()]];
        [alert setAccessoryView:input];  // Add the text box to the alert.
        
        // Make the text field active so user can start typing immediately.
        [[alert window] setInitialFirstResponder:input];
        
        NSInteger button = [alert runModal];
        if (button == NSAlertFirstButtonReturn) {  // User clicked "OK"
            return std::string([[input stringValue] UTF8String]);
        }
        return "";  // User clicked "Cancel" or closed the dialog
    }
}

int main() {
    @autoreleasepool {
        std::string name = showTextInputDialog(
            "Welcome!",
            "Please enter your name:",
            "John Doe"  // Default/placeholder text
        );
        
        if (!name.empty()) {
            std::cout << "👋 Hello, " << name << "!\n";
            
            // Ask another question
            std::string email = showTextInputDialog(
                "Email Address",
                "Please enter your email:",
                "user@example.com"
            );
            
            if (!email.empty()) {
                std::cout << "📧 Email: " << email << "\n";
            }
        } else {
            std::cout << "😢 You didn't enter a name.\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit text_input_dialog.mm -o text_input
./text_input
```

**Plain-English breakdown:**
- `NSAlert` = creates the dialog window
- `NSTextField` = a text box where users can type
- `setAccessoryView:` = "add this custom element to the alert"
- `setInitialFirstResponder:` = "put the cursor here so users can type immediately"
- `NSAlertFirstButtonReturn` = checks if user clicked the first button (OK)

---

#### Example 2: Password Input Dialog (Hidden Text)

```objc
// password_dialog.mm
// Idea: Ask for sensitive text (like a password) that shows dots (•••) instead of letters.
#import <AppKit/AppKit.h>
#include <iostream>
#include <string>

std::string showPasswordDialog(const std::string& title, const std::string& message) {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        [alert addButtonWithTitle:@"Unlock"];
        [alert addButtonWithTitle:@"Cancel"];
        
        // NSSecureTextField = special text field that hides what you type (shows dots).
        NSSecureTextField* input = [[NSSecureTextField alloc] initWithFrame:NSMakeRect(0, 0, 300, 24)];
        [alert setAccessoryView:input];
        [[alert window] setInitialFirstResponder:input];
        
        NSInteger button = [alert runModal];
        if (button == NSAlertFirstButtonReturn) {
            return std::string([[input stringValue] UTF8String]);
        }
        return "";
    }
}

int main() {
    @autoreleasepool {
        std::string password = showPasswordDialog(
            "Authentication Required",
            "Enter your password to continue:"
        );
        
        if (!password.empty()) {
            std::cout << "🔐 Password received (length: " << password.length() << " characters)\n";
            // In a real app, you'd verify the password here.
            if (password == "secret123") {
                std::cout << "✅ Access granted!\n";
            } else {
                std::cout << "❌ Incorrect password.\n";
            }
        } else {
            std::cout << "🚫 Access denied (no password entered).\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit password_dialog.mm -o password_dialog
./password_dialog
```

**Plain-English breakdown:**
- `NSSecureTextField` = like NSTextField but masks the input (shows bullets)
- Perfect for passwords, PINs, or any sensitive data
- The actual text is still accessible in code, just hidden on screen

---

#### Example 3: Multi-Line Text Input (Text View)

```objc
// multiline_input.mm
// Idea: Let users type multiple lines (like writing a note or description).
#import <AppKit/AppKit.h>
#include <iostream>
#include <string>

std::string showMultiLineInputDialog(const std::string& title, const std::string& message, int height = 100) {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        [alert addButtonWithTitle:@"Submit"];
        [alert addButtonWithTitle:@"Cancel"];
        
        // NSTextView inside a scrollable container (for longer text).
        NSScrollView* scrollView = [[NSScrollView alloc] initWithFrame:NSMakeRect(0, 0, 350, height)];
        [scrollView setHasVerticalScroller:YES];
        [scrollView setBorderType:NSBezelBorder];
        
        NSTextView* textView = [[NSTextView alloc] initWithFrame:NSMakeRect(0, 0, 350, height)];
        [textView setFont:[NSFont systemFontOfSize:13]];
        [textView setString:@""];  // Start with empty text
        [scrollView setDocumentView:textView];
        
        [alert setAccessoryView:scrollView];
        [[alert window] setInitialFirstResponder:textView];
        
        NSInteger button = [alert runModal];
        if (button == NSAlertFirstButtonReturn) {
            return std::string([[textView string] UTF8String]);
        }
        return "";
    }
}

int main() {
    @autoreleasepool {
        std::string notes = showMultiLineInputDialog(
            "Feedback Form",
            "Tell us what you think (multiple lines allowed):",
            120  // Height in pixels
        );
        
        if (!notes.empty()) {
            std::cout << "📝 You wrote:\n" << notes << "\n";
            std::cout << "\n✅ Feedback received (" << notes.length() << " characters).\n";
        } else {
            std::cout << "⚠️ No feedback provided.\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit multiline_input.mm -o multiline_input
./multiline_input
```

**Plain-English breakdown:**
- `NSTextView` = a rich text editor (like TextEdit in miniature)
- `NSScrollView` = adds scrollbars when text is longer than the box
- Perfect for comments, descriptions, or any multi-paragraph input
- Users can type Enter/Return to create new lines

---

#### Example 4: Choice Dialog with Multiple Buttons

```objc
// choice_dialog.mm
// Idea: Ask the user to pick from 2, 3, or more options (not just OK/Cancel).
#import <AppKit/AppKit.h>
#include <iostream>
#include <vector>
#include <string>

int showChoiceDialog(const std::string& title, 
                     const std::string& message, 
                     const std::vector<std::string>& choices) {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        
        // Add a button for each choice.
        for (const auto& choice : choices) {
            [alert addButtonWithTitle:[NSString stringWithUTF8String:choice.c_str()]];
        }
        
        NSInteger button = [alert runModal];
        
        // NSAlertFirstButtonReturn = 1000, NSAlertSecondButtonReturn = 1001, etc.
        return static_cast<int>(button - NSAlertFirstButtonReturn);
    }
}

int main() {
    @autoreleasepool {
        int choice = showChoiceDialog(
            "Choose Your Favorite",
            "Which programming language do you prefer?",
            {"C++", "Python", "JavaScript", "Rust", "Go"}
        );
        
        std::vector<std::string> languages = {"C++", "Python", "JavaScript", "Rust", "Go"};
        
        if (choice >= 0 && choice < languages.size()) {
            std::cout << "✅ You chose: " << languages[choice] << "\n";
        } else {
            std::cout << "❌ Dialog was dismissed.\n";
        }
        
        // Another example: Yes/No/Maybe question
        int decision = showChoiceDialog(
            "Confirm Action",
            "Do you want to save your changes?",
            {"Save", "Don't Save", "Cancel"}
        );
        
        switch (decision) {
            case 0: std::cout << "💾 Saving...\n"; break;
            case 1: std::cout << "🗑️  Discarding changes.\n"; break;
            case 2: std::cout << "🚫 Cancelled.\n"; break;
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit choice_dialog.mm -o choice_dialog
./choice_dialog
```

**Plain-English breakdown:**
- Each `addButtonWithTitle:` creates a clickable button
- Buttons appear in the order you add them (left to right)
- `runModal` returns which button was clicked (0 = first button, 1 = second, etc.)
- Perfect for "Save/Don't Save/Cancel" or "Yes/No" questions

---

#### Example 5: Combo Box (Dropdown Menu) Input

```objc
// combobox_dialog.mm
// Idea: Show a dropdown menu where users pick from a list (like choosing a country or file format).
#import <AppKit/AppKit.h>
#include <iostream>
#include <string>
#include <vector>

std::string showComboBoxDialog(const std::string& title,
                                const std::string& message,
                                const std::vector<std::string>& options,
                                int defaultIndex = 0) {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        [alert addButtonWithTitle:@"Select"];
        [alert addButtonWithTitle:@"Cancel"];
        
        // NSComboBox = a dropdown menu (like HTML <select>).
        NSComboBox* comboBox = [[NSComboBox alloc] initWithFrame:NSMakeRect(0, 0, 300, 26)];
        [comboBox setEditable:NO];  // User can't type custom values, only pick from list.
        
        // Add all options to the dropdown.
        for (const auto& option : options) {
            [comboBox addItemWithObjectValue:[NSString stringWithUTF8String:option.c_str()]];
        }
        
        if (defaultIndex >= 0 && defaultIndex < options.size()) {
            [comboBox selectItemAtIndex:defaultIndex];  // Pre-select an option.
        }
        
        [alert setAccessoryView:comboBox];
        [[alert window] setInitialFirstResponder:comboBox];
        
        NSInteger button = [alert runModal];
        if (button == NSAlertFirstButtonReturn) {
            NSInteger selectedIndex = [comboBox indexOfSelectedItem];
            if (selectedIndex >= 0) {
                return options[selectedIndex];
            }
        }
        return "";
    }
}

int main() {
    @autoreleasepool {
        std::vector<std::string> fileFormats = {"PDF", "Word Document", "Plain Text", "HTML", "Markdown"};
        
        std::string format = showComboBoxDialog(
            "Export Options",
            "Choose an export format:",
            fileFormats,
            0  // Pre-select "PDF" (index 0)
        );
        
        if (!format.empty()) {
            std::cout << "📄 Exporting as: " << format << "\n";
        } else {
            std::cout << "🚫 Export cancelled.\n";
        }
        
        // Another example: selecting a country
        std::vector<std::string> countries = {
            "United States", "Canada", "United Kingdom", "Australia", 
            "Germany", "France", "Japan", "Other"
        };
        
        std::string country = showComboBoxDialog(
            "Location",
            "Select your country:",
            countries,
            0
        );
        
        if (!country.empty()) {
            std::cout << "🌍 Selected country: " << country << "\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit combobox_dialog.mm -o combobox
./combobox
```

**Plain-English breakdown:**
- `NSComboBox` = a dropdown menu (also called a "select" or "picker")
- `setEditable:NO` = user can only pick from the list (can't type custom values)
- `selectItemAtIndex:` = which option is shown by default
- Perfect for file formats, countries, categories, or any predefined list

---

#### Example 6: Number Input with Stepper (Spinner Control)

```objc
// number_input_dialog.mm
// Idea: Let users pick a number using up/down buttons (stepper) or typing.
#import <AppKit/AppKit.h>
#include <iostream>

int showNumberInputDialog(const std::string& title,
                          const std::string& message,
                          int defaultValue = 0,
                          int minValue = 0,
                          int maxValue = 100) {
    @autoreleasepool {
        NSAlert* alert = [[NSAlert alloc] init];
        [alert setMessageText:[NSString stringWithUTF8String:title.c_str()]];
        [alert setInformativeText:[NSString stringWithUTF8String:message.c_str()]];
        [alert addButtonWithTitle:@"OK"];
        [alert addButtonWithTitle:@"Cancel"];
        
        // Container view to hold both the text field and stepper side-by-side.
        NSView* container = [[NSView alloc] initWithFrame:NSMakeRect(0, 0, 300, 30)];
        
        // Text field for displaying/typing the number.
        NSTextField* numberField = [[NSTextField alloc] initWithFrame:NSMakeRect(0, 3, 240, 24)];
        [numberField setIntValue:defaultValue];
        
        // Stepper (up/down arrows).
        NSStepper* stepper = [[NSStepper alloc] initWithFrame:NSMakeRect(245, 0, 55, 30)];
        [stepper setMinValue:minValue];
        [stepper setMaxValue:maxValue];
        [stepper setIntValue:defaultValue];
        [stepper setIncrement:1];
        
        // Link stepper and text field so they update each other.
        [stepper setTarget:numberField];
        [stepper setAction:@selector(takeIntValueFrom:)];
        [numberField setTarget:stepper];
        [numberField setAction:@selector(takeIntValueFrom:)];
        
        [container addSubview:numberField];
        [container addSubview:stepper];
        
        [alert setAccessoryView:container];
        [[alert window] setInitialFirstResponder:numberField];
        
        NSInteger button = [alert runModal];
        if (button == NSAlertFirstButtonReturn) {
            return [numberField intValue];
        }
        return -1;  // Indicate cancellation
    }
}

int main() {
    @autoreleasepool {
        int age = showNumberInputDialog(
            "Age Verification",
            "Please enter your age:",
            25,   // Default value
            0,    // Minimum
            120   // Maximum
        );
        
        if (age >= 0) {
            std::cout << "🎂 Your age: " << age << "\n";
            if (age >= 18) {
                std::cout << "✅ Access granted (adult).\n";
            } else {
                std::cout << "🚫 Access denied (must be 18+).\n";
            }
        } else {
            std::cout << "🚫 Cancelled.\n";
        }
        
        // Another example: quantity selector
        int quantity = showNumberInputDialog(
            "Order Quantity",
            "How many items would you like?",
            1,    // Default
            1,    // Min
            999   // Max
        );
        
        if (quantity > 0) {
            std::cout << "🛒 Ordering " << quantity << " item(s).\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit number_input_dialog.mm -o number_input
./number_input
```

**Plain-English breakdown:**
- `NSTextField` = displays the current number (users can also type directly)
- `NSStepper` = the up/down arrows for incrementing/decrementing
- `setMinValue:` / `setMaxValue:` = enforces boundaries (like age 0-120)
- They're linked together so changing one updates the other automatically
- Perfect for quantities, ages, percentages, or any numeric input

---

#### Complete Keyboard Input Summary Table

| Dialog Type | Best For | Key Component | Example Use Case |
|-------------|----------|---------------|------------------|
| **Text Input** | Single-line text | `NSTextField` | Name, email, search query |
| **Password Input** | Sensitive text | `NSSecureTextField` | Passwords, PIN codes, API keys |
| **Multi-line Input** | Long text | `NSTextView` | Comments, descriptions, notes |
| **Choice Buttons** | Multiple options | Multiple `addButtonWithTitle:` | Save/Don't Save/Cancel |
| **Combo Box** | Dropdown list | `NSComboBox` | Country, file format, category |
| **Number Input** | Numeric values | `NSTextField` + `NSStepper` | Age, quantity, percentage |

> **Pro tip:** You can combine these elements! For example, show both a text field AND a combo box in the same dialog to ask for "Name" and "Country" together.

---

### 13.6 Send a macOS Notification (UserNotifications)

**What it does:** Shows a notification in the top-right corner of the screen (like when you get a new email or calendar alert).

**When to use it:** Notify users of completed tasks, reminders, or alerts even when your app is in the background.

```objc
// send_notification.mm
// Idea: Post a message that appears in the notification center.
#import <Foundation/Foundation.h>
#import <UserNotifications/UserNotifications.h>
#include <iostream>
#include <thread>
#include <chrono>

int main() {
    @autoreleasepool {
        UNUserNotificationCenter* center = [UNUserNotificationCenter currentNotificationCenter];
        
        // Request permission (macOS will ask the user the first time).
        [center requestAuthorizationWithOptions:(UNAuthorizationOptionAlert | UNAuthorizationOptionSound)
                              completionHandler:^(BOOL granted, NSError* error) {
            if (!granted) {
                std::cout << "⚠️ Notification permission denied.\n";
            }
        }];
        
        // Give the permission dialog a moment to appear (if first run).
        std::this_thread::sleep_for(std::chrono::seconds(1));
        
        // Build the notification content.
        UNMutableNotificationContent* content = [[UNMutableNotificationContent alloc] init];
        content.title = @"Hello from C++!";
        content.body = @"Your program just sent a notification.";
        content.sound = [UNNotificationSound defaultSound];  // Optional: play a sound.
        
        // Schedule it to appear immediately.
        UNNotificationRequest* request = [UNNotificationRequest requestWithIdentifier:@"MyNotification"
                                                                              content:content
                                                                              trigger:nil];
        
        [center addNotificationRequest:request withCompletionHandler:^(NSError* error) {
            if (error) {
                std::cout << "❌ Error: " << [[error localizedDescription] UTF8String] << "\n";
            } else {
                std::cout << "✅ Notification sent!\n";
            }
        }];
        
        // Keep the app alive long enough for the notification to appear.
        std::this_thread::sleep_for(std::chrono::seconds(2));
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework UserNotifications send_notification.mm -o notify
./notify
```

**Plain-English breakdown:**
- `UNUserNotificationCenter` = Apple's notification manager
- `requestAuthorizationWithOptions` = ask the user "Can I show you notifications?"
- `UNMutableNotificationContent` = the message (title, body, sound)
- `addNotificationRequest` = "Show this now (or schedule it for later)"

---

### 13.7 Read Clipboard (Copy/Paste) (AppKit)

**What it does:** Reads whatever text the user last copied (⌘C).

**When to use it:** Process copied data, auto-fill forms, or analyze what's on the clipboard.

```objc
// read_clipboard.mm
// Idea: Look at the macOS clipboard (pasteboard) and grab the text.
#import <AppKit/AppKit.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSPasteboard* pasteboard = [NSPasteboard generalPasteboard];  // The system clipboard.
        NSString* clipboardText = [pasteboard stringForType:NSPasteboardTypeString];
        
        if (clipboardText) {
            std::cout << "📋 Clipboard contains:\n" << [clipboardText UTF8String] << "\n";
        } else {
            std::cout << "📋 Clipboard is empty (or doesn't contain text).\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit read_clipboard.mm -o read_clip
./read_clip
```

**Plain-English breakdown:**
- `NSPasteboard` = Apple's clipboard manager
- `generalPasteboard` = the main clipboard (there can be multiple for advanced uses)
- `stringForType:` = "Give me text, if there is any"

---

### 13.8 Write to Clipboard (Set Copy/Paste) (AppKit)

**What it does:** Puts text onto the clipboard, as if the user pressed ⌘C.

**When to use it:** Let users copy results, export data, or prepare text for pasting elsewhere.

```objc
// write_clipboard.mm
// Idea: Put your own text onto the macOS clipboard so users can paste it anywhere.
#import <AppKit/AppKit.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSPasteboard* pasteboard = [NSPasteboard generalPasteboard];
        [pasteboard clearContents];  // Erase whatever was there before.
        
        NSString* newText = @"Hello from C++! This text is now on your clipboard.";
        [pasteboard setString:newText forType:NSPasteboardTypeString];
        
        std::cout << "✅ Text copied to clipboard! Try pressing ⌘V to paste it.\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit write_clipboard.mm -o write_clip
./write_clip
```

**Plain-English breakdown:**
- `clearContents` = wipe the clipboard clean
- `setString:forType:` = "Put this text on the clipboard"

---

### 13.9 Get System Info (macOS Version, CPU, RAM) (Foundation)

**What it does:** Displays macOS version, computer model, CPU architecture (ARM/Intel), and physical memory.

**When to use it:** Diagnostic tools, system requirements checks, or logging environment info.

```objc
// system_info.mm
// Idea: Ask macOS about the computer it's running on.
#import <Foundation/Foundation.h>
#include <iostream>
#include <sys/sysctl.h>  // Low-level system info functions.

std::string sysctl_string(const char* name) {
    size_t size = 0;
    sysctlbyname(name, nullptr, &size, nullptr, 0);  // Find out how much space we need.
    if (size == 0) return "(unknown)";
    
    char* buffer = new char[size];
    sysctlbyname(name, buffer, &size, nullptr, 0);   // Actually read the value.
    std::string result(buffer);
    delete[] buffer;
    return result;
}

uint64_t sysctl_uint64(const char* name) {
    uint64_t value = 0;
    size_t size = sizeof(value);
    sysctlbyname(name, &value, &size, nullptr, 0);
    return value;
}

int main() {
    @autoreleasepool {
        // macOS version (e.g., "14.2.1" for Sonoma)
        NSOperatingSystemVersion osVer = [[NSProcessInfo processInfo] operatingSystemVersion];
        std::cout << "🍎 macOS Version: " << osVer.majorVersion << "."
                  << osVer.minorVersion << "." << osVer.patchVersion << "\n";
        
        // Computer model (e.g., "MacBookPro18,1")
        std::cout << "💻 Model: " << sysctl_string("hw.model") << "\n";
        
        // CPU architecture (e.g., "arm64" for M-series, "x86_64" for Intel)
        std::cout << "🖥️  CPU Architecture: " << sysctl_string("hw.machine") << "\n";
        
        // Physical memory in GB
        uint64_t memBytes = sysctl_uint64("hw.memsize");
        double memGB = memBytes / (1024.0 * 1024.0 * 1024.0);
        std::cout << "🧠 RAM: " << memGB << " GB\n";
        
        // Number of CPU cores
        std::cout << "⚙️  CPU Cores: " << sysctl_uint64("hw.ncpu") << "\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation system_info.mm -o sysinfo
./sysinfo
```

**Plain-English breakdown:**
- `NSProcessInfo` = Apple's class for runtime information
- `sysctlbyname` = a low-level function that queries the kernel (the core of macOS)
- `hw.memsize` = total physical RAM in bytes
- `hw.ncpu` = number of logical CPU cores

---

### 13.10 Launch Another App (Workspace API) (AppKit)

**What it does:** Opens another macOS application (like Safari, Calculator, or your own app).

**When to use it:** Build launcher tools, integrate with other apps, or automate workflows.

```objc
// launch_app.mm
// Idea: Tell macOS "please open this application."
#import <AppKit/AppKit.h>
#include <iostream>

int main(int argc, char* argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            std::cerr << "Usage: ./launch_app <app_name>\n";
            std::cerr << "Example: ./launch_app Safari\n";
            return 1;
        }
        
        NSString* appName = [NSString stringWithUTF8String:argv[1]];
        NSWorkspace* workspace = [NSWorkspace sharedWorkspace];
        
        // Try to launch the app by name.
        BOOL success = [workspace launchApplication:appName];
        
        if (success) {
            std::cout << "✅ Launched " << [appName UTF8String] << "\n";
        } else {
            std::cerr << "❌ Could not launch " << [appName UTF8String] << "\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit launch_app.mm -o launch
./launch Safari
./launch Calculator
```

**Plain-English breakdown:**
- `NSWorkspace` = Apple's manager for launching apps and opening files
- `launchApplication:` = "Start this app (if it exists)"

---

### 13.11 Create a Simple Window (AppKit)

**What it does:** Opens a graphical window with a title bar and a button that prints to the terminal when clicked.

**When to use it:** Build custom GUI apps, tools with visual interfaces, or simple utilities.

```objc
// simple_window.mm
// Idea: Draw a window on the screen, just like any Mac app.
#import <Cocoa/Cocoa.h>
#include <iostream>

// A minimal AppDelegate to handle the window lifecycle.
@interface AppDelegate : NSObject <NSApplicationDelegate>
@property (strong) NSWindow* window;
@end

@implementation AppDelegate

- (void)applicationDidFinishLaunching:(NSNotification*)notification {
    // Create a window (x, y, width, height).
    NSRect frame = NSMakeRect(100, 100, 400, 300);
    self.window = [[NSWindow alloc] initWithContentRect:frame
                                              styleMask:(NSWindowStyleMaskTitled |
                                                         NSWindowStyleMaskClosable |
                                                         NSWindowStyleMaskResizable)
                                                backing:NSBackingStoreBuffered
                                                  defer:NO];
    [self.window setTitle:@"My C++ Window"];
    [self.window makeKeyAndOrderFront:nil];  // Show the window.
    
    // Add a button.
    NSButton* button = [[NSButton alloc] initWithFrame:NSMakeRect(150, 130, 100, 40)];
    [button setTitle:@"Click Me"];
    [button setTarget:self];
    [button setAction:@selector(buttonClicked:)];
    [[self.window contentView] addSubview:button];
    
    std::cout << "🪟 Window is now open!\n";
}

- (void)buttonClicked:(id)sender {
    std::cout << "🖱️ Button was clicked!\n";
    NSAlert* alert = [[NSAlert alloc] init];
    [alert setMessageText:@"Button Clicked!"];
    [alert setInformativeText:@"You pressed the button."];
    [alert runModal];
}

- (void)applicationWillTerminate:(NSNotification*)notification {
    std::cout << "👋 App is closing.\n";
}

@end

int main() {
    @autoreleasepool {
        NSApplication* app = [NSApplication sharedApplication];
        AppDelegate* delegate = [[AppDelegate alloc] init];
        [app setDelegate:delegate];
        [app run];  // Start the event loop (keeps the window alive).
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit simple_window.mm -o simple_window
./simple_window
```

**Plain-English breakdown:**
- `NSWindow` = a macOS window (the thing with a title bar and close button)
- `NSButton` = a clickable button
- `setTarget:` and `setAction:` = "When clicked, call this method"
- `[app run]` = start the main event loop (keeps the app running until you close the window)

---

### 13.12 Access Metal (GPU) for 3D Graphics (Metal)

**What it does:** Initializes Apple's Metal framework (the graphics engine that powers games and 3D apps).

**When to use it:** High-performance graphics, game engines, machine learning acceleration, or image processing.

```objc
// metal_init.mm
// Idea: Talk to the graphics card (GPU) to do ultra-fast calculations or rendering.
#import <Foundation/Foundation.h>
#import <Metal/Metal.h>
#include <iostream>

int main() {
    @autoreleasepool {
        // Get the default Metal device (the GPU).
        id<MTLDevice> device = MTLCreateSystemDefaultDevice();
        
        if (!device) {
            std::cerr << "❌ Metal is not supported on this device.\n";
            return 1;
        }
        
        NSString* deviceName = [device name];
        std::cout << "✅ Metal initialized!\n";
        std::cout << "🎮 GPU: " << [deviceName UTF8String] << "\n";
        std::cout << "🚀 Max threads per threadgroup: " << [device maxThreadsPerThreadgroup].width << "\n";
        
        // Create a command queue (like a to-do list for the GPU).
        id<MTLCommandQueue> commandQueue = [device newCommandQueue];
        if (commandQueue) {
            std::cout << "✅ Command queue created. Ready for GPU tasks!\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework Metal metal_init.mm -o metal_init
./metal_init
```

**Plain-English breakdown:**
- `MTLDevice` = represents the GPU (graphics card)
- `MTLCommandQueue` = a queue where you send rendering or compute tasks
- Metal is Apple's super-fast graphics API (like Vulkan or DirectX, but Mac-only)

---

### 13.13 Read Environment Variables (Foundation)

**What it does:** Retrieves system environment variables (like `PATH`, `HOME`, `USER`).

**When to use it:** Adapt your app based on the user's environment, find paths, or debug.

```objc
// env_vars.mm
// Idea: Look up configuration stored in environment variables.
#import <Foundation/Foundation.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSDictionary* env = [[NSProcessInfo processInfo] environment];
        
        // Common environment variables:
        NSString* user = env[@"USER"];
        NSString* home = env[@"HOME"];
        NSString* path = env[@"PATH"];
        NSString* shell = env[@"SHELL"];
        
        std::cout << "👤 User: " << (user ? [user UTF8String] : "(unknown)") << "\n";
        std::cout << "🏠 Home: " << (home ? [home UTF8String] : "(unknown)") << "\n";
        std::cout << "🐚 Shell: " << (shell ? [shell UTF8String] : "(unknown)") << "\n";
        std::cout << "🛤️  PATH (first 100 chars): "
                  << (path ? [[path substringToIndex:MIN(100, [path length])] UTF8String] : "(unknown)") << "...\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation env_vars.mm -o env_vars
./env_vars
```

---

### 13.14 Execute Shell Commands (Foundation)

**What it does:** Runs terminal commands (like `ls`, `git`, `python3`) from your C++ program and captures the output.

**When to use it:** Automate tasks, call other tools, or integrate with command-line utilities.

```objc
// run_shell_command.mm
// Idea: Tell macOS "run this terminal command and give me the result."
#import <Foundation/Foundation.h>
#include <iostream>

std::string run_command(const std::string& cmd) {
    NSTask* task = [[NSTask alloc] init];
    [task setLaunchPath:@"/bin/zsh"];  // or @"/bin/bash"
    [task setArguments:@[@"-c", [NSString stringWithUTF8String:cmd.c_str()]]];
    
    NSPipe* pipe = [NSPipe pipe];
    [task setStandardOutput:pipe];
    [task launch];
    [task waitUntilExit];
    
    NSData* data = [[pipe fileHandleForReading] readDataToEndOfFile];
    NSString* output = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    return std::string([output UTF8String]);
}

int main() {
    @autoreleasepool {
        std::cout << "Running: ls -lh\n";
        std::string result = run_command("ls -lh");
        std::cout << "📂 Output:\n" << result << "\n";
        
        std::cout << "\nRunning: date\n";
        std::string date = run_command("date");
        std::cout << "📅 Current date: " << date;
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation run_shell_command.mm -o runcmd
./runcmd
```

**Plain-English breakdown:**
- `NSTask` = a way to run other programs from your code
- `setLaunchPath:` = which shell to use (zsh, bash, etc.)
- `setArguments:` = the command to run
- `NSPipe` = captures the output so you can read it

---

### 13.15 Access Files with Bookmarks (Security-Scoped) (AppKit)

**What it does:** Saves a reference to a file the user selects, so you can re-access it later even if macOS sandboxing is enabled.

**When to use it:** Apps that remember user-selected files across launches (document editors, media players).

```objc
// file_bookmark.mm
// Idea: Get permanent permission to access a file, even in sandboxed apps.
#import <AppKit/AppKit.h>
#include <iostream>

int main() {
    @autoreleasepool {
        NSOpenPanel* panel = [NSOpenPanel openPanel];
        [panel setCanChooseFiles:YES];
        [panel setCanChooseDirectories:NO];
        [panel setMessage:@"Select a file to bookmark"];
        
        if ([panel runModal] == NSModalResponseOK) {
            NSURL* fileURL = [[panel URLs] firstObject];
            
            // Create a security-scoped bookmark (permanent permission).
            NSError* error = nil;
            NSData* bookmarkData = [fileURL bookmarkDataWithOptions:NSURLBookmarkCreationWithSecurityScope
                                   includingResourceValuesForKeys:nil
                                                    relativeToURL:nil
                                                            error:&error];
            
            if (error) {
                std::cerr << "❌ Error creating bookmark: " << [[error localizedDescription] UTF8String] << "\n";
            } else {
                std::cout << "✅ Bookmark created for: " << [[fileURL path] UTF8String] << "\n";
                std::cout << "📦 Bookmark size: " << [bookmarkData length] << " bytes\n";
                // You can save bookmarkData to disk and reload it later with
                // [NSURL URLByResolvingBookmarkData:...options:...error:]
            }
        } else {
            std::cout << "❌ User cancelled.\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework AppKit file_bookmark.mm -o bookmark
./bookmark
```

**Plain-English breakdown:**
- `bookmarkDataWithOptions:` = "Give me a token that proves the user said I can access this file"
- `NSURLBookmarkCreationWithSecurityScope` = works even in sandboxed apps
- Save the `NSData` to a file, and later reload it to regain access

---

### 13.16 Monitor Folder Changes (File System Events) (CoreServices)

**What it does:** Watches a folder and prints a message whenever a file is added, removed, or modified.

**When to use it:** Auto-reload config files, sync tools, or file backup utilities.

```objc
// folder_watcher.mm
// Idea: Ask macOS to notify you whenever something changes in a folder.
#import <Foundation/Foundation.h>
#import <CoreServices/CoreServices.h>
#include <iostream>

// Callback function: macOS calls this whenever a change happens.
void fileSystemCallback(ConstFSEventStreamRef streamRef,
                        void* clientCallBackInfo,
                        size_t numEvents,
                        void* eventPaths,
                        const FSEventStreamEventFlags eventFlags[],
                        const FSEventStreamEventId eventIds[]) {
    char** paths = (char**)eventPaths;
    for (size_t i = 0; i < numEvents; i++) {
        std::cout << "📁 Change detected in: " << paths[i] << "\n";
    }
}

int main(int argc, char* argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            std::cerr << "Usage: ./folder_watcher <path_to_folder>\n";
            return 1;
        }
        
        NSString* folderPath = [NSString stringWithUTF8String:argv[1]];
        NSArray* paths = @[folderPath];
        
        FSEventStreamRef stream = FSEventStreamCreate(
            nullptr,                // allocator
            &fileSystemCallback,    // callback function
            nullptr,                // context
            (__bridge CFArrayRef)paths,
            kFSEventStreamEventIdSinceNow,  // start from now
            1.0,                    // latency (seconds): how often to batch events
            kFSEventStreamCreateFlagFileEvents  // report individual file changes
        );
        
        if (!stream) {
            std::cerr << "❌ Could not create file system event stream.\n";
            return 1;
        }
        
        FSEventStreamScheduleWithRunLoop(stream, CFRunLoopGetCurrent(), kCFRunLoopDefaultMode);
        FSEventStreamStart(stream);
        
        std::cout << "👀 Watching folder: " << [folderPath UTF8String] << "\n";
        std::cout << "Press Ctrl+C to stop.\n";
        
        CFRunLoopRun();  // Run forever (until Ctrl+C).
        
        FSEventStreamStop(stream);
        FSEventStreamInvalidate(stream);
        FSEventStreamRelease(stream);
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework CoreServices folder_watcher.mm -o folder_watcher
./folder_watcher ~/Desktop
# Now create, edit, or delete files on your Desktop and watch the output!
```

**Plain-English breakdown:**
- `FSEventStream` = Apple's system for monitoring file changes
- `FSEventStreamCreate` = set up a watcher
- `CFRunLoopRun` = keep the program running forever, waiting for events

---

### 13.17 Mouse Control & Cursor Position (Core Graphics / Quartz Events)

**What it does:** Reads where the mouse cursor is located on screen, moves the cursor programmatically, and simulates mouse clicks.

**When to use it:** Automation tools, accessibility apps, screen recording software, or testing utilities.

> **Important:** These functions require **Accessibility permissions** on modern macOS. Go to System Settings → Privacy & Security → Accessibility and add your Terminal app or compiled program.

#### Example 1: Get Current Cursor Position

```objc
// cursor_position.mm
// Idea: Ask macOS "Where is the mouse cursor right now?"
#import <Foundation/Foundation.h>
#import <ApplicationServices/ApplicationServices.h>
#include <iostream>

int main() {
    @autoreleasepool {
        // Get the current mouse location in screen coordinates.
        CGEventRef event = CGEventCreate(NULL);
        CGPoint cursor = CGEventGetLocation(event);
        CFRelease(event);
        
        std::cout << "🖱️  Cursor position:\n";
        std::cout << "   X: " << cursor.x << " pixels from left edge\n";
        std::cout << "   Y: " << cursor.y << " pixels from top edge\n";
        
        // Get screen dimensions to show relative position.
        CGRect mainScreen = CGDisplayBounds(CGMainDisplayID());
        std::cout << "\n📺 Screen size: " << mainScreen.size.width 
                  << " × " << mainScreen.size.height << " pixels\n";
        
        // Calculate percentage position
        double xPercent = (cursor.x / mainScreen.size.width) * 100.0;
        double yPercent = (cursor.y / mainScreen.size.height) * 100.0;
        std::cout << "   Position: " << xPercent << "% across, " 
                  << yPercent << "% down\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework ApplicationServices cursor_position.mm -o cursor_pos
./cursor_pos
```

**Plain-English breakdown:**
- `CGEventCreate(NULL)` = creates a temporary event to query the cursor
- `CGEventGetLocation` = returns x,y coordinates (0,0 is top-left corner)
- `CGMainDisplayID()` = gets info about your main screen
- Screen coordinates start at (0,0) in the top-left corner

---

#### Example 2: Move the Mouse Cursor

```objc
// move_cursor.mm
// Idea: Programmatically move the mouse pointer to a specific location.
#import <Foundation/Foundation.h>
#import <ApplicationServices/ApplicationServices.h>
#include <iostream>
#include <thread>
#include <chrono>

void moveCursor(double x, double y) {
    CGPoint newLocation = CGPointMake(x, y);
    CGEventRef move = CGEventCreateMouseEvent(
        NULL,                       // Event source (NULL = system)
        kCGEventMouseMoved,         // Event type (mouse movement)
        newLocation,                // New position
        kCGMouseButtonLeft          // Which button (doesn't matter for moves)
    );
    CGEventPost(kCGHIDEventTap, move);  // Send the event to the system
    CFRelease(move);
}

int main() {
    @autoreleasepool {
        std::cout << "🖱️  Moving cursor in 3 seconds...\n";
        std::cout << "⚠️  Make sure Accessibility permissions are enabled!\n";
        std::this_thread::sleep_for(std::chrono::seconds(3));
        
        // Move cursor to center of screen
        CGRect screen = CGDisplayBounds(CGMainDisplayID());
        double centerX = screen.size.width / 2;
        double centerY = screen.size.height / 2;
        
        std::cout << "📍 Moving to center: (" << centerX << ", " << centerY << ")\n";
        moveCursor(centerX, centerY);
        
        std::this_thread::sleep_for(std::chrono::seconds(1));
        
        // Draw a small square with the cursor
        std::cout << "✏️  Drawing a square...\n";
        double size = 100;
        moveCursor(centerX - size, centerY - size);  // Top-left
        std::this_thread::sleep_for(std::chrono::milliseconds(500));
        
        moveCursor(centerX + size, centerY - size);  // Top-right
        std::this_thread::sleep_for(std::chrono::milliseconds(500));
        
        moveCursor(centerX + size, centerY + size);  // Bottom-right
        std::this_thread::sleep_for(std::chrono::milliseconds(500));
        
        moveCursor(centerX - size, centerY + size);  // Bottom-left
        std::this_thread::sleep_for(std::chrono::milliseconds(500));
        
        moveCursor(centerX - size, centerY - size);  // Back to start
        
        std::cout << "✅ Done! Cursor returned to center.\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework ApplicationServices move_cursor.mm -o move_cursor
./move_cursor
```

**Plain-English breakdown:**
- `CGEventCreateMouseEvent` = creates a fake mouse movement
- `kCGEventMouseMoved` = tells macOS "the mouse moved"
- `CGEventPost` = sends the fake event to the system (makes it real)
- Coordinates are in pixels from the top-left corner (0,0)

---

#### Example 3: Simulate Mouse Clicks

```objc
// mouse_click.mm
// Idea: Make the computer think someone clicked the mouse.
#import <Foundation/Foundation.h>
#import <ApplicationServices/ApplicationServices.h>
#include <iostream>
#include <thread>
#include <chrono>

void mouseClick(double x, double y, bool rightClick = false) {
    CGPoint clickLocation = CGPointMake(x, y);
    CGMouseButton button = rightClick ? kCGMouseButtonRight : kCGMouseButtonLeft;
    CGEventType downEvent = rightClick ? kCGEventRightMouseDown : kCGEventLeftMouseDown;
    CGEventType upEvent = rightClick ? kCGEventRightMouseUp : kCGEventLeftMouseUp;
    
    // Move cursor to position
    CGEventRef move = CGEventCreateMouseEvent(NULL, kCGEventMouseMoved, clickLocation, button);
    CGEventPost(kCGHIDEventTap, move);
    CFRelease(move);
    
    std::this_thread::sleep_for(std::chrono::milliseconds(50));
    
    // Press down
    CGEventRef down = CGEventCreateMouseEvent(NULL, downEvent, clickLocation, button);
    CGEventPost(kCGHIDEventTap, down);
    CFRelease(down);
    
    std::this_thread::sleep_for(std::chrono::milliseconds(50));
    
    // Release
    CGEventRef up = CGEventCreateMouseEvent(NULL, upEvent, clickLocation, button);
    CGEventPost(kCGHIDEventTap, up);
    CFRelease(up);
}

void doubleClick(double x, double y) {
    CGPoint clickLocation = CGPointMake(x, y);
    
    // First click
    CGEventRef down1 = CGEventCreateMouseEvent(NULL, kCGEventLeftMouseDown, clickLocation, kCGMouseButtonLeft);
    CGEventSetIntegerValueField(down1, kCGMouseEventClickState, 1);  // Click count = 1
    CGEventPost(kCGHIDEventTap, down1);
    CFRelease(down1);
    
    CGEventRef up1 = CGEventCreateMouseEvent(NULL, kCGEventLeftMouseUp, clickLocation, kCGMouseButtonLeft);
    CGEventSetIntegerValueField(up1, kCGMouseEventClickState, 1);
    CGEventPost(kCGHIDEventTap, up1);
    CFRelease(up1);
    
    std::this_thread::sleep_for(std::chrono::milliseconds(100));
    
    // Second click
    CGEventRef down2 = CGEventCreateMouseEvent(NULL, kCGEventLeftMouseDown, clickLocation, kCGMouseButtonLeft);
    CGEventSetIntegerValueField(down2, kCGMouseEventClickState, 2);  // Click count = 2
    CGEventPost(kCGHIDEventTap, down2);
    CFRelease(down2);
    
    CGEventRef up2 = CGEventCreateMouseEvent(NULL, kCGEventLeftMouseUp, clickLocation, kCGMouseButtonLeft);
    CGEventSetIntegerValueField(up2, kCGMouseEventClickState, 2);
    CGEventPost(kCGHIDEventTap, up2);
    CFRelease(up2);
}

int main() {
    @autoreleasepool {
        std::cout << "🖱️  Mouse Click Demo\n";
        std::cout << "⚠️  Make sure Accessibility permissions are enabled!\n";
        std::cout << "Starting in 3 seconds... Position your cursor where you want to click.\n";
        std::this_thread::sleep_for(std::chrono::seconds(3));
        
        // Get current cursor position and click there
        CGEventRef event = CGEventCreate(NULL);
        CGPoint cursor = CGEventGetLocation(event);
        CFRelease(event);
        
        std::cout << "🖱️  Left click at (" << cursor.x << ", " << cursor.y << ")\n";
        mouseClick(cursor.x, cursor.y, false);
        
        std::this_thread::sleep_for(std::chrono::seconds(1));
        
        std::cout << "🖱️  Right click at same location\n";
        mouseClick(cursor.x, cursor.y, true);
        
        std::this_thread::sleep_for(std::chrono::seconds(1));
        
        std::cout << "🖱️  Double click at same location\n";
        doubleClick(cursor.x, cursor.y);
        
        std::cout << "✅ Done!\n";
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework ApplicationServices mouse_click.mm -o mouse_click
./mouse_click
```

**Plain-English breakdown:**
- `kCGEventLeftMouseDown` = "press the left button"
- `kCGEventLeftMouseUp` = "release the left button"
- A click = press down + release up
- `kCGMouseEventClickState` = tells macOS if it's a single, double, or triple click
- Right clicks work the same way, just different event types

---

### 13.18 Power Management (Shutdown, Restart, Sleep)

**What it does:** Commands the computer to shut down, restart, sleep, or log out.

**When to use it:** System utilities, scheduled tasks, remote management tools, or automated maintenance scripts.

> **Important:** These operations require **administrator privileges**. The system may prompt for a password.

```objc
// power_management.mm
// Idea: Send power commands to the operating system.
#import <Foundation/Foundation.h>
#import <CoreServices/CoreServices.h>
#include <iostream>
#include <string>

enum class PowerAction {
    Sleep,
    Restart,
    Shutdown,
    LogOut
};

bool executePowerAction(PowerAction action) {
    OSStatus result;
    std::string actionName;
    
    switch (action) {
        case PowerAction::Sleep:
            actionName = "Sleep";
            // Send sleep command (immediate, doesn't ask for confirmation)
            result = SendAppleEventToSystemProcess(kAESleep);
            break;
            
        case PowerAction::Restart:
            actionName = "Restart";
            result = SendAppleEventToSystemProcess(kAERestart);
            break;
            
        case PowerAction::Shutdown:
            actionName = "Shutdown";
            result = SendAppleEventToSystemProcess(kAEShutDown);
            break;
            
        case PowerAction::LogOut:
            actionName = "Log Out";
            result = SendAppleEventToSystemProcess(kAEReallyLogOut);
            break;
    }
    
    if (result == noErr) {
        std::cout << "✅ " << actionName << " command sent successfully.\n";
        return true;
    } else {
        std::cerr << "❌ Failed to send " << actionName << " command (error code: " 
                  << result << ")\n";
        return false;
    }
}

// Helper function to send Apple Events to the system
OSStatus SendAppleEventToSystemProcess(AEEventID eventID) {
    AEAddressDesc targetDesc;
    static const ProcessSerialNumber kPSNOfSystemProcess = {0, kSystemProcess};
    
    OSStatus status = AECreateDesc(typeProcessSerialNumber, 
                                    &kPSNOfSystemProcess, 
                                    sizeof(kPSNOfSystemProcess), 
                                    &targetDesc);
    if (status != noErr) return status;
    
    AppleEvent event = {typeNull, NULL};
    AppleEvent reply = {typeNull, NULL};
    
    status = AECreateAppleEvent(kCoreEventClass, 
                                eventID, 
                                &targetDesc, 
                                kAutoGenerateReturnID, 
                                kAnyTransactionID, 
                                &event);
    
    AEDisposeDesc(&targetDesc);
    if (status != noErr) return status;
    
    status = AESendMessage(&event, &reply, kAENoReply, kAEDefaultTimeout);
    
    AEDisposeDesc(&event);
    AEDisposeDesc(&reply);
    
    return status;
}

int main(int argc, char* argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            std::cout << "🔋 Power Management Utility\n\n";
            std::cout << "Usage: ./power_management <action>\n\n";
            std::cout << "Actions:\n";
            std::cout << "  sleep     - Put the computer to sleep (like closing a laptop)\n";
            std::cout << "  restart   - Restart the computer (reboot)\n";
            std::cout << "  shutdown  - Turn off the computer completely\n";
            std::cout << "  logout    - Log out the current user\n\n";
            std::cout << "Example: ./power_management sleep\n";
            return 1;
        }
        
        std::string action = argv[1];
        
        if (action == "sleep") {
            std::cout << "💤 Putting computer to sleep in 3 seconds...\n";
            std::this_thread::sleep_for(std::chrono::seconds(3));
            executePowerAction(PowerAction::Sleep);
        } else if (action == "restart") {
            std::cout << "🔄 Restarting computer in 5 seconds... SAVE YOUR WORK!\n";
            std::this_thread::sleep_for(std::chrono::seconds(5));
            executePowerAction(PowerAction::Restart);
        } else if (action == "shutdown") {
            std::cout << "⚡ Shutting down computer in 5 seconds... SAVE YOUR WORK!\n";
            std::this_thread::sleep_for(std::chrono::seconds(5));
            executePowerAction(PowerAction::Shutdown);
        } else if (action == "logout") {
            std::cout << "👋 Logging out in 3 seconds... SAVE YOUR WORK!\n";
            std::this_thread::sleep_for(std::chrono::seconds(3));
            executePowerAction(PowerAction::LogOut);
        } else {
            std::cerr << "❌ Unknown action: " << action << "\n";
            std::cerr << "Valid actions: sleep, restart, shutdown, logout\n";
            return 1;
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework CoreServices power_management.mm -o power_management

# Test commands (be careful!)
./power_management sleep      # Puts Mac to sleep
./power_management restart    # Restarts computer
./power_management shutdown   # Shuts down computer
./power_management logout     # Logs out current user
```

**Plain-English breakdown:**
- `SendAppleEventToSystemProcess` = sends a command to the macOS core system
- `kAESleep` = the "go to sleep" command code
- `kAERestart` / `kAEShutDown` = restart and shutdown codes
- These are low-level system commands that macOS processes immediately
- The system may show a confirmation dialog depending on security settings

---

### 13.19 Screen Brightness Control (IOKit)

**What it does:** Reads and adjusts the brightness of your Mac's built-in display.

**When to use it:** Night mode apps, battery saver utilities, accessibility tools, or automation scripts.

```objc
// screen_brightness.mm
// Idea: Control how bright or dim the screen appears.
#import <Foundation/Foundation.h>
#import <IOKit/graphics/IOGraphicsLib.h>
#include <iostream>
#include <iomanip>

float getDisplayBrightness() {
    io_iterator_t iterator;
    kern_return_t result = IOServiceGetMatchingServices(
        kIOMasterPortDefault,
        IOServiceMatching("IODisplayConnect"),
        &iterator
    );
    
    if (result != kIOReturnSuccess) return -1.0f;
    
    float brightness = -1.0f;
    io_object_t service;
    
    while ((service = IOIteratorNext(iterator))) {
        float currentBrightness;
        result = IODisplayGetFloatParameter(service, kNilOptions, 
                                           CFSTR(kIODisplayBrightnessKey), 
                                           &currentBrightness);
        
        if (result == kIOReturnSuccess) {
            brightness = currentBrightness;
            IOObjectRelease(service);
            break;
        }
        IOObjectRelease(service);
    }
    
    IOObjectRelease(iterator);
    return brightness;
}

bool setDisplayBrightness(float brightness) {
    // Clamp brightness between 0.0 (darkest) and 1.0 (brightest)
    if (brightness < 0.0f) brightness = 0.0f;
    if (brightness > 1.0f) brightness = 1.0f;
    
    io_iterator_t iterator;
    kern_return_t result = IOServiceGetMatchingServices(
        kIOMasterPortDefault,
        IOServiceMatching("IODisplayConnect"),
        &iterator
    );
    
    if (result != kIOReturnSuccess) return false;
    
    bool success = false;
    io_object_t service;
    
    while ((service = IOIteratorNext(iterator))) {
        result = IODisplaySetFloatParameter(service, kNilOptions, 
                                           CFSTR(kIODisplayBrightnessKey), 
                                           brightness);
        
        if (result == kIOReturnSuccess) {
            success = true;
        }
        IOObjectRelease(service);
    }
    
    IOObjectRelease(iterator);
    return success;
}

int main(int argc, char* argv[]) {
    @autoreleasepool {
        if (argc < 2) {
            // Just show current brightness
            float current = getDisplayBrightness();
            if (current >= 0.0f) {
                int percent = static_cast<int>(current * 100);
                std::cout << "💡 Current screen brightness: " << percent << "%\n";
                
                // Visual bar
                std::cout << "[";
                int bars = static_cast<int>(current * 20);
                for (int i = 0; i < 20; i++) {
                    std::cout << (i < bars ? "█" : "░");
                }
                std::cout << "]\n";
            } else {
                std::cerr << "❌ Could not read brightness (desktop Mac or external display?).\n";
            }
            
            std::cout << "\nUsage: ./screen_brightness <0-100>\n";
            std::cout << "Example: ./screen_brightness 75\n";
            return 0;
        }
        
        int newBrightness = std::stoi(argv[1]);
        if (newBrightness < 0 || newBrightness > 100) {
            std::cerr << "❌ Brightness must be between 0 and 100\n";
            return 1;
        }
        
        float brightnessFloat = newBrightness / 100.0f;
        
        if (setDisplayBrightness(brightnessFloat)) {
            std::cout << "✅ Brightness set to " << newBrightness << "%\n";
        } else {
            std::cerr << "❌ Could not set brightness (may require special permissions).\n";
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework IOKit screen_brightness.mm -o screen_brightness

# Check current brightness
./screen_brightness

# Set brightness to 50%
./screen_brightness 50

# Set brightness to maximum
./screen_brightness 100

# Dim screen to 10%
./screen_brightness 10
```

**Plain-English breakdown:**
- `IOServiceGetMatchingServices` = finds the display hardware
- `IODisplayGetFloatParameter` = reads a setting (brightness is 0.0 to 1.0)
- `IODisplaySetFloatParameter` = changes the setting
- `kIODisplayBrightnessKey` = the identifier for "screen brightness"
- Works on MacBooks and iMacs; external displays may not respond

---

### 13.20 System Volume Control (CoreAudio)

**What it does:** Reads and adjusts the system-wide audio volume, and can mute/unmute.

**When to use it:** Media players, alarm apps, accessibility tools, or automation scripts.

```objc
// system_volume.mm
// Idea: Control how loud or quiet your Mac's speakers are.
#import <Foundation/Foundation.h>
#import <CoreAudio/CoreAudio.h>
#include <iostream>
#include <iomanip>

AudioDeviceID getDefaultOutputDevice() {
    AudioDeviceID deviceID = kAudioDeviceUnknown;
    UInt32 propertySize = sizeof(deviceID);
    
    AudioObjectPropertyAddress propertyAddress = {
        kAudioHardwarePropertyDefaultOutputDevice,
        kAudioObjectPropertyScopeGlobal,
        kAudioObjectPropertyElementMain
    };
    
    AudioObjectGetPropertyData(kAudioObjectSystemObject, &propertyAddress, 
                              0, NULL, &propertySize, &deviceID);
    return deviceID;
}

float getVolume(AudioDeviceID deviceID) {
    if (deviceID == kAudioDeviceUnknown) return -1.0f;
    
    Float32 volume = 0.0f;
    UInt32 propertySize = sizeof(volume);
    
    AudioObjectPropertyAddress propertyAddress = {
        kAudioDevicePropertyVolumeScalar,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMain
    };
    
    OSStatus result = AudioObjectGetPropertyData(deviceID, &propertyAddress, 
                                                 0, NULL, &propertySize, &volume);
    
    return (result == noErr) ? volume : -1.0f;
}

bool setVolume(AudioDeviceID deviceID, float volume) {
    if (deviceID == kAudioDeviceUnknown) return false;
    if (volume < 0.0f) volume = 0.0f;
    if (volume > 1.0f) volume = 1.0f;
    
    Float32 volumeFloat = volume;
    UInt32 propertySize = sizeof(volumeFloat);
    
    AudioObjectPropertyAddress propertyAddress = {
        kAudioDevicePropertyVolumeScalar,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMain
    };
    
    OSStatus result = AudioObjectSetPropertyData(deviceID, &propertyAddress, 
                                                 0, NULL, propertySize, &volumeFloat);
    
    return (result == noErr);
}

bool getMute(AudioDeviceID deviceID) {
    if (deviceID == kAudioDeviceUnknown) return false;
    
    UInt32 mute = 0;
    UInt32 propertySize = sizeof(mute);
    
    AudioObjectPropertyAddress propertyAddress = {
        kAudioDevicePropertyMute,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMain
    };
    
    AudioObjectGetPropertyData(deviceID, &propertyAddress, 
                              0, NULL, &propertySize, &mute);
    
    return (mute != 0);
}

bool setMute(AudioDeviceID deviceID, bool mute) {
    if (deviceID == kAudioDeviceUnknown) return false;
    
    UInt32 muteValue = mute ? 1 : 0;
    UInt32 propertySize = sizeof(muteValue);
    
    AudioObjectPropertyAddress propertyAddress = {
        kAudioDevicePropertyMute,
        kAudioDevicePropertyScopeOutput,
        kAudioObjectPropertyElementMain
    };
    
    OSStatus result = AudioObjectSetPropertyData(deviceID, &propertyAddress, 
                                                 0, NULL, propertySize, &muteValue);
    
    return (result == noErr);
}

int main(int argc, char* argv[]) {
    @autoreleasepool {
        AudioDeviceID device = getDefaultOutputDevice();
        
        if (device == kAudioDeviceUnknown) {
            std::cerr << "❌ Could not find default audio output device.\n";
            return 1;
        }
        
        if (argc < 2) {
            // Show current volume
            float volume = getVolume(device);
            bool muted = getMute(device);
            
            if (volume >= 0.0f) {
                int percent = static_cast<int>(volume * 100);
                std::cout << "🔊 Current volume: " << percent << "%";
                if (muted) std::cout << " (MUTED 🔇)";
                std::cout << "\n";
                
                // Visual bar
                std::cout << "[";
                int bars = static_cast<int>(volume * 20);
                for (int i = 0; i < 20; i++) {
                    std::cout << (i < bars ? "█" : "░");
                }
                std::cout << "]\n";
            } else {
                std::cerr << "❌ Could not read volume.\n";
            }
            
            std::cout << "\nUsage:\n";
            std::cout << "  ./system_volume <0-100>   - Set volume\n";
            std::cout << "  ./system_volume mute      - Mute sound\n";
            std::cout << "  ./system_volume unmute    - Unmute sound\n";
            std::cout << "\nExample: ./system_volume 50\n";
            return 0;
        }
        
        std::string arg = argv[1];
        
        if (arg == "mute") {
            if (setMute(device, true)) {
                std::cout << "🔇 Audio muted.\n";
            } else {
                std::cerr << "❌ Could not mute audio.\n";
            }
        } else if (arg == "unmute") {
            if (setMute(device, false)) {
                std::cout << "🔊 Audio unmuted.\n";
            } else {
                std::cerr << "❌ Could not unmute audio.\n";
            }
        } else {
            // Assume it's a volume level
            try {
                int newVolume = std::stoi(arg);
                if (newVolume < 0 || newVolume > 100) {
                    std::cerr << "❌ Volume must be between 0 and 100\n";
                    return 1;
                }
                
                float volumeFloat = newVolume / 100.0f;
                
                if (setVolume(device, volumeFloat)) {
                    std::cout << "✅ Volume set to " << newVolume << "%\n";
                    // Unmute if currently muted
                    if (getMute(device)) {
                        setMute(device, false);
                        std::cout << "🔊 Audio unmuted automatically.\n";
                    }
                } else {
                    std::cerr << "❌ Could not set volume.\n";
                }
            } catch (const std::exception& e) {
                std::cerr << "❌ Invalid argument: " << arg << "\n";
                return 1;
            }
        }
    }
    return 0;
}
```

**Compile & run:**
```bash
clang++ -std=c++17 -framework Foundation -framework CoreAudio system_volume.mm -o system_volume

# Check current volume
./system_volume

# Set volume to 50%
./system_volume 50

# Mute sound
./system_volume mute

# Unmute sound
./system_volume unmute

# Set volume to 100% (max)
./system_volume 100
```

**Plain-English breakdown:**
- `kAudioHardwarePropertyDefaultOutputDevice` = finds your default speakers/headphones
- `kAudioDevicePropertyVolumeScalar` = the volume setting (0.0 = silent, 1.0 = max)
- `kAudioDevicePropertyMute` = on/off switch for sound (true = muted)
- `AudioObjectGetPropertyData` = reads a setting
- `AudioObjectSetPropertyData` = changes a setting
- Changes take effect immediately (you'll hear the difference)

---

### 13.21 System Control Summary Table

Here's a quick reference for all the system control capabilities:

| Feature | Framework | Key Function | Permissions Needed |
|---------|-----------|--------------|-------------------|
| **Cursor Position** | ApplicationServices | `CGEventGetLocation` | None |
| **Move Mouse** | ApplicationServices | `CGEventCreateMouseEvent` | Accessibility |
| **Click Mouse** | ApplicationServices | `CGEventPost` | Accessibility |
| **Sleep Computer** | CoreServices | `SendAppleEventToSystemProcess(kAESleep)` | Admin (may prompt) |
| **Restart Computer** | CoreServices | `SendAppleEventToSystemProcess(kAERestart)` | Admin (may prompt) |
| **Shutdown Computer** | CoreServices | `SendAppleEventToSystemProcess(kAEShutDown)` | Admin (may prompt) |
| **Log Out** | CoreServices | `SendAppleEventToSystemProcess(kAEReallyLogOut)` | Admin (may prompt) |
| **Screen Brightness** | IOKit | `IODisplaySetFloatParameter` | None (built-in displays) |
| **System Volume** | CoreAudio | `AudioObjectSetPropertyData` | None |
| **Mute/Unmute** | CoreAudio | `kAudioDevicePropertyMute` | None |

> **Security Note:** macOS protects users by requiring permissions for sensitive operations:
> - **Accessibility** (mouse/keyboard control): System Settings → Privacy & Security → Accessibility
> - **Admin commands** (power management): System may prompt for password
> - These safeguards prevent malicious software from controlling your Mac without permission

---

### 13.22 Complete API Summary Table

Here's a quick-reference table of all the macOS frameworks and what they do:

| Framework | What it provides | Common use cases |
|-----------|------------------|------------------|
| **Foundation** | Basic data types, strings, dates, file handling | Almost every macOS/iOS app uses this |
| **AppKit** | Windows, buttons, menus, dialogs (macOS GUI) | Desktop apps with graphical interfaces |
| **UIKit** | Touch interfaces (iOS/iPadOS, not macOS) | iPhone and iPad apps |
| **Cocoa** | Umbrella for Foundation + AppKit | Shortcut to include both at once |
| **Core Graphics (Quartz)** | 2D drawing, PDF generation | Custom graphics, charts, image manipulation |
| **Core Image** | GPU-accelerated image filters | Photo editors, real-time effects |
| **Core Animation** | Smooth animations and transitions | Animated UI, game effects |
| **AVFoundation** | Audio and video playback/recording | Media players, video editors, podcasting apps |
| **Metal** | Low-level GPU programming | High-performance 3D graphics, machine learning |
| **MetalKit** | Helper utilities for Metal | Easier Metal setup for 3D apps |
| **Core ML** | Machine learning model inference | AI features (image recognition, predictions) |
| **Vision** | Image analysis (faces, text, objects) | Photo organizers, AR apps |
| **Natural Language** | Text processing (sentiment, language detection) | Chatbots, text analysis tools |
| **IOKit** | Low-level hardware access | Battery info, USB devices, sensors |
| **Core Location** | GPS and location services | Maps, weather, location-based reminders |
| **MapKit** | Interactive maps | Navigation apps, store locators |
| **UserNotifications** | System notifications | Reminders, alerts, background tasks |
| **Core Data** | Local database (object persistence) | Apps with lots of structured data |
| **CloudKit** | iCloud sync and storage | Multi-device apps, backup features |
| **Network** | Modern networking (TCP/UDP/WebSocket) | Servers, real-time multiplayer games |
| **Combine** | Reactive programming (async events) | Handling streams of data or UI events |
| **SwiftUI** | Declarative UI framework (Swift-only) | Modern app interfaces (not C++ compatible) |
| **CoreServices** | File system events, metadata | File watchers, Spotlight integration |
| **Security** | Cryptography, keychain access | Password storage, encrypted data |
| **Accelerate** | Vectorized math (SIMD, BLAS, FFT) | Scientific computing, signal processing |
| **SceneKit** | 3D rendering (higher-level than Metal) | 3D visualizations, simple games |
| **SpriteKit** | 2D game engine | Casual games, animated graphics |
| **GameplayKit** | AI, pathfinding, random generators | Game logic, NPC behavior |
| **ARKit** | Augmented reality | AR experiences (overlaying 3D on camera) |
| **Core Bluetooth** | Bluetooth LE communication | Smart home devices, fitness trackers |
| **Core NFC** | NFC tag reading (iPhone only) | Payment apps, smart labels |
| **HealthKit** | Access health/fitness data (with permission) | Fitness apps, health trackers |
| **EventKit** | Calendar and reminders | Scheduling apps, event management |
| **Contacts** | Address book access | Contact pickers, messaging apps |
| **Photos** | Photo library access | Image galleries, photo editors |
| **PDFKit** | PDF viewing and annotation | Document readers, note-taking apps |
| **QuickLook** | File previews (images, documents, etc.) | File managers, mail clients |
| **WebKit** | Web browser engine | Embedded web views, hybrid apps |

> **Pro tip:** For the full official docs on any framework, visit [developer.apple.com/documentation](https://developer.apple.com/documentation/) and search the framework name.

---

### 13.18 Tips for Learning More macOS APIs

1. **Start small:** Pick one framework (like AppKit or AVFoundation) and build a tiny project.
2. **Read Apple's samples:** <https://developer.apple.com/sample-code/> has hundreds of examples.
3. **Use Xcode documentation:** Press `⌥ + Click` on any Apple class/method in Xcode to see docs.
4. **Experiment:** Change values, break things, see what happens—best way to learn!
5. **Join communities:** [Apple Developer Forums](https://developer.apple.com/forums/), Reddit's r/iOSProgramming, Stack Overflow.

---

## 🎉 You Did It!

You now have:
- ✅ A fully configured C++ development environment on ARM macOS
- ✅ Dozens of copy-paste-ready code examples (strings, files, JSON, HTTP, games)
- ✅ macOS-specific superpowers (alerts, notifications, dark mode, GPU access, and more)
- ✅ Best practices to write clean, professional code
- ✅ Links to trusted resources for continued learning

**Next steps:**
1. Pick a small project idea (a calculator, a to-do list, a file organizer)
2. Build it incrementally (one feature at a time)
3. Use the examples in this guide as building blocks
4. Celebrate every small win—programming is a skill you build over time!

**Remember:** Every expert was once a beginner. The only way to fail is to stop trying. Keep coding, keep learning, and have fun! 🚀

---

**Document version:** 2.0  
**Last updated:** October 2025  
**For questions or corrections:** [Create an issue on GitHub](https://github.com/codecaine-zz/markdown_tutorials) or check [Apple Developer Documentation](https://developer.apple.com/documentation/)

---