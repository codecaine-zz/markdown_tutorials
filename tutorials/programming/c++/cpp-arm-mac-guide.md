# C++ Tutorial for Beginners with Official Documentation Links

I'll create a comprehensive tutorial to help you learn C++ quickly with links to official documentation for each concept.

## Installation on ARM Mac with Homebrew

```bash
# Install GCC compiler using Homebrew
brew install gcc

# Verify installation
g++ --version

# Install VS Code extension for C++
# In VS Code, search for "C/C++" extension by Microsoft
```

[C++ Installation Guide](https://cplusplus.com/doc/tutorial/introduction/)

## Basic Syntax Examples

### 1. Hello World

```cpp
// main.cpp
#include <iostream>

int main() {
    std::cout << "Hello, World!" << std::endl;
    return 0;
}
```

[Main Function Documentation](https://en.cppreference.com/w/cpp/language/main_function)

To compile and run:
```bash
g++ main.cpp -o main
./main
```

### 2. Variables and Data Types

```cpp
// variables.cpp
#include <iostream>
#include <string>

int main() {
    // Basic data types
    int age = 25;
    double price = 99.99;
    char grade = 'A';
    bool is_active = true;
    std::string name = "C++";
    
    // Auto keyword (type inferred)
    auto temperature = 36.6;  // Will be double
    
    // Constants
    const double PI = 3.14159;
    
    std::cout << "Name: " << name << std::endl;
    std::cout << "Age: " << age << std::endl;
    std::cout << "Price: $" << price << std::endl;
    std::cout << "Grade: " << grade << std::endl;
    std::cout << "Active: " << is_active << std::endl;
    
    return 0;
}
```

[Variables Documentation](https://en.cppreference.com/w/cpp/language/declarations)

### 3. Functions

```cpp
// functions.cpp
#include <iostream>

// Function declaration
int add(int x, int y);
void say_hello(std::string name);
double divide(double dividend, double divisor);

int main() {
    say_hello("World");
    
    int result = add(5, 3);
    std::cout << "5 + 3 = " << result << std::endl;
    
    double result2 = divide(10.0, 3.0);
    std::cout << "10 / 3 = " << result2 << std::endl;
    
    return 0;
}

// Function definitions
void say_hello(std::string name) {
    std::cout << "Hello, " << name << "!" << std::endl;
}

int add(int x, int y) {
    return x + y;
}

double divide(double dividend, double divisor) {
    if (divisor != 0) {
        return dividend / divisor;
    } else {
        std::cout << "Error: Division by zero!" << std::endl;
        return 0;
    }
}
```

[Functions Documentation](https://en.cppreference.com/w/cpp/language/functions)

### 4. Control Flow

```cpp
// control_flow.cpp
#include <iostream>
#include <vector>

int main() {
    // If statements
    int age = 20;
    if (age >= 18) {
        std::cout << "You are an adult" << std::endl;
    } else if (age >= 13) {
        std::cout << "You are a teenager" << std::endl;
    } else {
        std::cout << "You are a child" << std::endl;
    }
    
    // For loops
    std::cout << "Counting to 5:" << std::endl;
    for (int i = 0; i < 5; i++) {
        std::cout << i << std::endl;
    }
    
    // While loop
    int count = 0;
    while (count < 3) {
        std::cout << "While loop: " << count << std::endl;
        count++;
    }
    
    // For loop with range (C++11)
    std::vector<int> numbers = {1, 2, 3, 4, 5};
    std::cout << "Vector elements:" << std::endl;
    for (int num : numbers) {
        std::cout << num << std::endl;
    }
    
    // Switch statement
    int day = 1;
    switch (day) {
        case 1:
            std::cout << "Monday" << std::endl;
            break;
        case 5:
            std::cout << "Friday" << std::endl;
            break;
        default:
            std::cout << "Mid-week day" << std::endl;
    }
    
    return 0;
}
```

[Control Flow Documentation](https://en.cppreference.com/w/cpp/language/control)

### 5. Arrays and Vectors

```cpp
// arrays_vectors.cpp
#include <iostream>
#include <vector>

int main() {
    // Fixed-size arrays
    int numbers[5] = {1, 2, 3, 4, 5};
    std::cout << "First element: " << numbers[0] << std::endl;
    std::cout << "Array size: " << sizeof(numbers)/sizeof(numbers[0]) << std::endl;
    
    // Modifying array
    numbers[0] = 10;
    std::cout << "Modified first element: " << numbers[0] << std::endl;
    
    // Vectors (dynamic arrays)
    std::vector<int> dynamic_list;
    dynamic_list.push_back(1);  // Append
    dynamic_list.push_back(2);
    dynamic_list.push_back(3);
    
    std::cout << "Vector elements: ";
    for (int num : dynamic_list) {
        std::cout << num << " ";
    }
    std::cout << std::endl;
    
    std::cout << "Vector size: " << dynamic_list.size() << std::endl;
    
    return 0;
}
```

[Arrays Documentation](https://en.cppreference.com/w/cpp/language/array)
[Vectors Documentation](https://en.cppreference.com/w/cpp/container/vector)

### 6. Classes (Custom Data Types)

```cpp
// classes.cpp
#include <iostream>
#include <string>

class Person {
private:
    std::string name;
    int age;
    bool is_happy;

public:
    // Constructor
    Person(std::string n, int a, bool happy = true) 
        : name(n), age(a), is_happy(happy) {}
    
    // Getter methods
    std::string getName() const { return name; }
    int getAge() const { return age; }
    bool isHappy() const { return is_happy; }
    
    // Setter methods
    void setHappiness(bool happy) { is_happy = happy; }
    
    // Methods
    void greet() const {
        std::cout << "Hello, my name is " << name << std::endl;
    }
    
    void haveBirthday() {
        age++;
    }
};

int main() {
    // Creating a class instance
    Person person("Alice", 30, true);
    
    std::cout << "Name: " << person.getName() << std::endl;
    std::cout << "Age: " << person.getAge() << std::endl;
    std::cout << "Is happy: " << person.isHappy() << std::endl;
    
    // Using methods
    person.greet();
    person.haveBirthday();
    std::cout << "New age: " << person.getAge() << std::endl;
    
    return 0;
}
```

[Classes Documentation](https://en.cppreference.com/w/cpp/language/classes)

### 7. Maps (Key-Value Collections)

```cpp
// maps.cpp
#include <iostream>
#include <map>
#include <string>

int main() {
    // Creating a map
    std::map<std::string, int> scores;
    
    // Adding values
    scores["Alice"] = 95;
    scores["Bob"] = 87;
    scores["Charlie"] = 92;
    
    // Accessing values
    std::cout << "Alice's score: " << scores["Alice"] << std::endl;
    
    // Check if key exists
    if (scores.find("David") == scores.end()) {
        std::cout << "David has no score recorded" << std::endl;
    }
    
    // Iterating through map
    std::cout << "All scores:" << std::endl;
    for (const auto& pair : scores) {
        std::cout << pair.first << ": " << pair.second << std::endl;
    }
    
    // Size of map
    std::cout << "Total students: " << scores.size() << std::endl;
    
    return 0;
}
```

[Maps Documentation](https://en.cppreference.com/w/cpp/container/map)

### 8. Error Handling

```cpp
// error_handling.cpp
#include <iostream>
#include <stdexcept>

double divide_safe(double x, double y) {
    if (y == 0) {
        throw std::invalid_argument("Division by zero");
    }
    return x / y;
}

int main() {
    try {
        double result = divide_safe(10.0, 2.0);
        std::cout << "Result: " << result << std::endl;
    } catch (const std::exception& e) {
        std::cout << "Error: " << e.what() << std::endl;
    }
    
    try {
        double result = divide_safe(10.0, 0.0);
        std::cout << "Result: " << result << std::endl;
    } catch (const std::exception& e) {
        std::cout << "Error: " << e.what() << std::endl;
    }
    
    return 0;
}
```

[Exception Handling Documentation](https://en.cppreference.com/w/cpp/error/exception)

### 9. Namespaces and Headers

```cpp
// math_utils.h
#ifndef MATH_UTILS_H
#define MATH_UTILS_H

namespace math_utils {
    int add(int x, int y);
    int multiply(int x, int y);
}

#endif
```

```cpp
// math_utils.cpp
#include "math_utils.h"

namespace math_utils {
    int add(int x, int y) {
        return x + y;
    }
    
    int multiply(int x, int y) {
        return x * y;
    }
}
```

```cpp
// main.cpp
#include <iostream>
#include "math_utils.h"

int main() {
    int result = math_utils::add(5, 3);
    std::cout << "5 + 3 = " << result << std::endl;
    
    int result2 = math_utils::multiply(4, 7);
    std::cout << "4 * 7 = " << result2 << std::endl;
    
    return 0;
}
```

[Namespaces Documentation](https://en.cppreference.com/w/cpp/language/namespace)

### 10. Working with Strings

```cpp
// strings.cpp
#include <iostream>
#include <string>

int main() {
    // String operations
    std::string greeting = "Hello";
    std::string name = "C++ Language";
    
    // String concatenation
    std::string message = greeting + ", " + name + "!";
    std::cout << message << std::endl;
    
    // String methods
    std::cout << "Length: " << message.length() << std::endl;
    std::cout << "Uppercase: " << message << std::endl;  // Note: C++ doesn't have built-in to_upper
    
    // Substrings
    std::string part = message.substr(0, 5);
    std::cout << "First 5 chars: " << part << std::endl;
    
    // Find
    if (message.find("C++") != std::string::npos) {
        std::cout << "Message mentions C++" << std::endl;
    }
    
    return 0;
}
```

[Strings Documentation](https://en.cppreference.com/w/cpp/string/basic_string)

### 11. File I/O

```cpp
// file_io.cpp
#include <iostream>
#include <fstream>
#include <string>

int main() {
    // Writing to a file
    std::ofstream file("example.txt");
    if (file.is_open()) {
        file << "Hello from C++!\n";
        file << "This is a test file.";
        file.close();
    } else {
        std::cout << "Failed to create file" << std::endl;
        return 1;
    }
    
    // Reading from a file
    std::ifstream read_file("example.txt");
    std::string content;
    if (read_file.is_open()) {
        std::string line;
        while (getline(read_file, line)) {
            content += line + "\n";
        }
        read_file.close();
        
        std::cout << "File content:" << std::endl;
        std::cout << content;
    } else {
        std::cout << "Failed to read file" << std::endl;
        return 1;
    }
    
    // Clean up
    std::remove("example.txt");
    
    return 0;
}
```

[File I/O Documentation](https://en.cppreference.com/w/cpp/io)

### 12. Concurrency (Threads)

```cpp
// concurrency.cpp
#include <iostream>
#include <thread>
#include <chrono>

void calculate_something(int seconds) {
    std::cout << "Starting calculation for " << seconds << " seconds" << std::endl;
    std::this_thread::sleep_for(std::chrono::seconds(seconds));
    std::cout << "Finished calculation after " << seconds << " seconds" << std::endl;
}

int main() {
    // Create threads
    std::thread t1(calculate_something, 5);
    std::thread t2(calculate_something, 3);
    
    // Wait for threads to finish
    t1.join();
    t2.join();
    
    std::cout << "Main function finished" << std::endl;
    
    return 0;
}
```

[Threading Documentation](https://en.cppreference.com/w/cpp/thread/thread)

## Standard Library Examples

### Working with JSON (using nlohmann/json)

```cpp
// json_example.cpp
// Note: Requires nlohmann/json library
// #include <nlohmann/json.hpp>
// using json = nlohmann::json;

/*
struct User {
    std::string name;
    int age;
    std::string city;
};

int main() {
    // Create a struct (in C++ we'd use the library's serialization)
    json user = {
        {"name", "Alice"},
        {"age", 30},
        {"city", "New York"}
    };
    
    // Serialize to JSON
    std::string json_data = user.dump();
    std::cout << "JSON: " << json_data << std::endl;
    
    // Deserialize from JSON
    json user2 = json::parse(json_data);
    
    std::cout << "Name: " << user2["name"] << std::endl;
    std::cout << "Age: " << user2["age"] << std::endl;
    std::cout << "City: " << user2["city"] << std::endl;
    
    return 0;
}
*/
```

[JSON Libraries for C++](https://github.com/nlohmann/json)

### HTTP Client (using libcurl)

```cpp
// http_client.cpp
// Note: Requires libcurl library
// #include <curl/curl.h>

/*
int main() {
    CURL *curl;
    CURLcode res;
    
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_URL, "https://httpbin.org/get");
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    
    return 0;
}
*/
```

[libcurl Documentation](https://curl.se/libcurl/)

### Working with Time

```cpp
// time_example.cpp
#include <iostream>
#include <chrono>
#include <thread>

int main() {
    // Current time
    auto start = std::chrono::high_resolution_clock::now();
    std::cout << "Current time measured" << std::endl;
    
    // Sleep
    std::cout << "Sleeping for 1 second..." << std::endl;
    std::this_thread::sleep_for(std::chrono::seconds(1));
    std::cout << "Awake!" << std::endl;
    
    // Measure execution time
    auto end = std::chrono::high_resolution_clock::now();
    auto duration = std::chrono::duration_cast<std::chrono::milliseconds>(end - start);
    std::cout << "Slept for " << duration.count() << " milliseconds" << std::endl;
    
    return 0;
}
```

[Chrono Documentation](https://en.cppreference.com/w/cpp/chrono)

## VS Code Configuration

Create these files in your project root:

### .vscode/settings.json
```json
{
    "C_Cpp.default.compilerPath": "/opt/homebrew/bin/g++-13",
    "C_Cpp.default.intelliSenseMode": "macos-gcc-arm64",
    "files.associations": {
        "*.cpp": "cpp"
    }
}
```

[C/C++ Extension Documentation](https://code.visualstudio.com/docs/languages/cpp)

### .vscode/tasks.json
```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "build and run",
            "type": "shell",
            "command": "g++",
            "args": ["-g", "${file}", "-o", "${fileBasenameNoExtension}"],
            "group": {
                "kind": "build",
                "isDefault": true
            },
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "shared"
            },
            "problemMatcher": "$gcc"
        }
    ]
}
```

### .vscode/c_cpp_properties.json
```json
{
    "configurations": [
        {
            "name": "Mac",
            "includePath": [
                "${workspaceFolder}/**"
            ],
            "defines": [],
            "macFrameworkPath": [
                "/Applications/Xcode.app/Contents/Developer/Platforms/MacOSX.platform/Developer/SDKs/MacOSX.sdk/System/Library/Frameworks"
            ],
            "compilerPath": "/opt/homebrew/bin/g++-13",
            "cStandard": "c17",
            "cppStandard": "c++17",
            "intelliSenseMode": "macos-gcc-arm64"
        }
    ],
    "version": 4
}
```

## Quick Reference Summary

```cpp
// quick_reference.cpp
#include <iostream>
#include <vector>
#include <string>

class Point {
public:
    int x, y;
    Point(int x, int y) : x(x), y(y) {}
};

int add(int x, int y) {
    return x + y;
}

int main() {
    // Variables
    const std::string immutable_var = "value";  // Cannot change
    int mutable_var = 10;  // Can change
    
    // Basic types
    int age = 25;
    double price = 99.99;
    bool is_active = true;
    std::string name = "C++";
    
    // Arrays
    int fixed_array[3] = {1, 2, 3};  // Fixed size
    
    // Vectors
    std::vector<int> dynamic_array = {1, 2, 3};
    
    // Maps
    std::map<std::string, int> scores = {
        {"Alice", 95},
        {"Bob", 87}
    };
    
    return 0;
}
```

[C++ Language Reference](https://en.cppreference.com/w/cpp/language)

## Building and Running

```bash
# Compile a C++ program
g++ main.cpp -o main

# Compile with debugging symbols
g++ -g main.cpp -o main

# Compile with optimizations
g++ -O2 main.cpp -o main

# Compile with C++17 standard
g++ -std=c++17 main.cpp -o main

# Run the executable
./main
```

[Compilation Documentation](https://gcc.gnu.org/onlinedocs/gcc/Invoking-GCC.html)

This tutorial covers the essential concepts of C++ programming language with practical examples and links to official documentation. Start with the basic examples and gradually work through more complex concepts. C++ is a powerful language that gives you fine control over system resources, making it excellent for performance-critical applications.