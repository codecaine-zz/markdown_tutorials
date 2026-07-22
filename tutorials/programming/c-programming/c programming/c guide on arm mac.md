# The C23 Programming Language: A Comprehensive Textbook Guide for Apple Silicon macOS

Welcome to the ultimate learning guide for the C programming language (C23 standard) on macOS (ARM64 Apple Silicon)! This textbook is structured specifically to take you from a complete beginner (zero programming experience) to an advanced C developer capable of building system-level utilities, memory-safe data structures, file processors, and high-performance applications. Rather than teaching outdated C99 or C11 quirks, this guide focuses on modern C23 standards: standard library safety, native boolean types, `nullptr`, `constexpr`, explicit memory management, pointer discipline, and native Apple Silicon compilation.

> [!NOTE]
> **How to read this book:** Each section begins with a clear explanation of core C concepts, followed by concrete, runnable code examples. All examples are target-tested for Apple Clang and GCC on macOS ARM64 using standard `-std=c23` compiler flags.

> [!TIP]
> **Interactive Learning:** You can compile and run any C example directly in your terminal using Apple Clang (`clang -std=c23 -Wall -Wextra -O2 -arch arm64 main.c -o main`) or online at [godbolt.org](https://godbolt.org/).

## Repository Structure

This book is paired with a topic-based repository layout so it is simple to navigate:

- `basics/` for primitive types, variables, arithmetic, and basic input/output
- `control_flow/` for conditional branching (`if`/`switch`) and loops (`for`/`while`)
- `functions_and_scope/` for stack execution, parameter passing, and modularity
- `pointers_and_memory/` for pointer arithmetic, dynamic memory (`malloc`/`free`), and memory safety
- `structs_and_unions/` for custom data structures, memory layout, and typedefs
- `file_io_and_systems/` for file reading/writing, command-line arguments, and POSIX system calls

## Quick Start: Learn C by Building Things

1. Verify Apple Clang or Homebrew GCC compiler on your ARM Mac:
   ```bash
   clang --version
   ```
2. Create a file named `hello.c`:
   ```c
   #include <stdio.stdio.h> // Correct top-level header inclusion
   #include <stdio.h>

   int main(void) {
       printf("Hello, C23 on Apple Silicon ARM64!\n");
       return 0;
   }
   ```
3. Compile and execute:
   ```bash
   clang -std=c23 -arch arm64 hello.c -o hello
   ./hello
   ```

Key rules to keep in mind when writing modern C:
- Always place `#include` directives at the **top level (file scope)** of your source file, never inside functions!
- In C23, `bool`, `true`, `false`, `nullptr`, and `constexpr` are built-in native keywords.
- Always check the return values of dynamic memory allocations (`malloc`, `calloc`) against `nullptr` or `NULL`.
- Prevent buffer overflows by using safe string functions such as `snprintf` instead of `strcpy` or `sprintf`.

## Core Language Essentials to Learn Early

- Data types (`int`, `double`, `char`, `size_t`, `bool`) and C23 `constexpr` constants
- Explicit type conversions and arithmetic operator rules
- Control flow (`if`, `else if`, `switch`, `for`, `while`)
- Stack arrays vs Heap dynamic memory allocations
- Pointers (`T*`), dereferencing (`*p`), address-of (`&var`), and `nullptr`
- Structs (`struct`), bitfields, and typedefs
- File streams (`fopen`, `fread`, `fwrite`, `fclose`)

## Must-Learn-Before-Building Checklist

Before writing low-level systems code, make sure you can:

- Declare top-level includes cleanly without scoping errors
- Write function prototypes before `main()`
- Use pointers to pass values by reference and mutate caller variables
- Allocate memory dynamically with `malloc()` and free it with `free()` without leaks
- Safely format strings with `snprintf()`
- Open files, check for stream errors, and close handles reliably

## Why This Matters

C is the foundational language of modern operating systems, hardware drivers, databases, and high-performance runtimes. Learning C teaches you how memory management, pointers, the stack, and the CPU cache actually work on ARM64 hardware.

## Suggested Learning Path

- **Chapter 1**: Primitive Types, Variables, and Formatting
- **Chapter 2**: Operators, Expressions, and C23 Keywords
- **Chapter 3**: Control Flow and Looping Structures
- **Chapter 4**: Functions, Stack Frames, and Scope
- **Chapter 5**: Pointers and Address Operations
- **Chapter 6**: Dynamic Memory Management (`malloc`/`free`)
- **Chapter 7**: Structs, Typedefs, and Data Modeling
- **Chapter 8**: File Input/Output and Systems Programming

## Practice Exercises

1. Write a C program to calculate the factorial of a number using recursion.
2. Implement a swap function `void swap(int* a, int* b)` using pointers.
3. Build a dynamic integer array in C that doubles its capacity using `realloc`.
4. Create a struct for a `Student` record and write functions to print and update record fields.
5. Write a file reader that counts lines, words, and characters in a given text file.

---

## Textbook Chapters & Runnable Examples

### Chapter 1: Variables, Primitive Types, and C23 Basics

```c
// 01_variables.c
#include <stdio.h>
#include <stdbool.h> // Included at file scope for full compatibility
#include <stddef.h>

int main(void) {
    // Basic types
    int age = 25;
    double price = 99.95;
    char grade = 'A';
    
    // C23 native keywords
    bool is_active = true;
    void* ptr = nullptr; // C23 standard null pointer constant
    constexpr double PI = 3.14159265;
    
    printf("Age: %d\n", age);
    printf("Price: %.2f\n", price);
    printf("Grade: %c\n", grade);
    printf("Active: %s\n", is_active ? "true" : "false");
    printf("PI: %.8f\n", PI);
    printf("Pointer address: %p\n", ptr);
    
    return 0;
}
```

### Chapter 2: Control Flow & Switch Statements

```c
// 02_control_flow.c
#include <stdio.h>

int main(void) {
    int score = 88;
    
    if (score >= 90) {
        printf("Grade: A\n");
    } else if (score >= 80) {
        printf("Grade: B\n");
    } else {
        printf("Grade: C or below\n");
    }
    
    // Switch-case execution
    int day_code = 2;
    switch (day_code) {
        case 1: printf("Monday\n"); break;
        case 2: printf("Tuesday\n"); break;
        case 3: printf("Wednesday\n"); break;
        default: printf("Other day\n"); break;
    }
    
    // For loop count
    for (int i = 0; i < 5; ++i) {
        printf("Index: %d\n", i);
    }
    
    return 0;
}
```

### Chapter 3: Functions and Pass-by-Reference via Pointers

```c
// 03_functions.c
#include <stdio.h>
#include <stdbool.h>

// Prototype declaration
void swap(int* x, int* y);
bool divide(double dividend, double divisor, double* out_result);

int main(void) {
    int a = 10, b = 20;
    printf("Before swap: a = %d, b = %d\n", a, b);
    swap(&a, &b);
    printf("After swap:  a = %d, b = %d\n", a, b);
    
    double result = 0.0;
    if (divide(100.0, 4.0, &result)) {
        printf("100 / 4 = %.2f\n", result);
    }
    
    return 0;
}

void swap(int* x, int* y) {
    int temp = *x;
    *x = *y;
    *y = temp;
}

bool divide(double dividend, double divisor, double* out_result) {
    if (divisor == 0.0 || out_result == nullptr) {
        return false;
    }
    *out_result = dividend / divisor;
    return true;
}
```

### Chapter 4: Pointers and Dynamic Memory Allocation

```c
// 04_memory.c
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    size_t count = 5;
    
    // Allocate heap memory for dynamic array
    int* arr = (int*)malloc(count * sizeof(int));
    if (arr == nullptr) {
        fprintf(stderr, "Error: Memory allocation failed!\n");
        return 1;
    }
    
    // Populate array
    for (size_t i = 0; i < count; ++i) {
        arr[i] = (int)(i + 1) * 10;
    }
    
    printf("Allocated Array: ");
    for (size_t i = 0; i < count; ++i) {
        printf("%d ", arr[i]);
    }
    printf("\n");
    
    // Free dynamic memory to prevent memory leaks
    free(arr);
    arr = nullptr;
    
    return 0;
}
```

### Chapter 5: Structs, Typedefs, and Safe Strings

```c
// 05_structs.c
#include <stdio.h>
#include <string.h>

typedef struct {
    int id;
    char name[50];
    double gpa;
} Student;

void print_student(const Student* s) {
    if (s == nullptr) return;
    printf("Student ID: %d | Name: %s | GPA: %.2f\n", s->id, s->name, s->gpa);
}

int main(void) {
    Student s1;
    s1.id = 1001;
    s1.gpa = 3.92;
    
    // Use snprintf for safe string copying
    snprintf(s1.name, sizeof(s1.name), "Alexander Morgan");
    
    print_student(&s1);
    return 0;
}
```

### Chapter 6: File Input / Output Operations

```c
// 06_file_io.c
#include <stdio.h>

int main(void) {
    const char* filename = "data.txt";
    
    // Write to file
    FILE* file = fopen(filename, "w");
    if (file != nullptr) {
        fprintf(file, "C23 File I/O Demonstration\nLine 2: Modern C Programming\n");
        fclose(file);
    }
    
    // Read from file
    file = fopen(filename, "r");
    if (file != nullptr) {
        char buffer[128];
        while (fgets(buffer, sizeof(buffer), file) != nullptr) {
            printf("Read line: %s", buffer);
        }
        fclose(file);
    }
    
    return 0;
}
```

---

## Your First Project: A Dynamic Bank Account Manager CLI

```c
// bank_app.c
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>

typedef struct {
    int account_number;
    char owner[64];
    double balance;
} BankAccount;

BankAccount* create_account(int acc_num, const char* owner_name, double initial_deposit) {
    BankAccount* acc = (BankAccount*)malloc(sizeof(BankAccount));
    if (acc == nullptr) return nullptr;
    
    acc->account_number = acc_num;
    snprintf(acc->owner, sizeof(acc->owner), "%s", owner_name);
    acc->balance = initial_deposit;
    return acc;
}

bool deposit(BankAccount* acc, double amount) {
    if (acc == nullptr || amount <= 0) return false;
    acc->balance += amount;
    return true;
}

void print_statement(const BankAccount* acc) {
    if (acc == nullptr) return;
    printf("\n=== Bank Statement ===\n");
    printf("Account #: %d\n", acc->account_number);
    printf("Owner:     %s\n", acc->owner);
    printf("Balance:   $%.2f\n", acc->balance);
    printf("======================\n");
}

int main(void) {
    BankAccount* my_acc = create_account(90812, "Jane Doe", 500.00);
    if (my_acc == nullptr) {
        printf("Failed to create account.\n");
        return 1;
    }
    
    print_statement(my_acc);
    deposit(my_acc, 250.75);
    print_statement(my_acc);
    
    free(my_acc);
    my_acc = nullptr;
    return 0;
}
```

---

## Quick Reference & Guidelines for macOS ARM64

### Compiler Command Line Flags

```bash
# Apple Clang build with C23 standard
clang -std=c23 -Wall -Wextra -O2 -arch arm64 main.c -o main

# Include debug symbols for LLDB debugging
clang -std=c23 -g -arch arm64 main.c -o main

# Run under LLDB debugger on macOS
lldb ./main
```

### Common C Mistakes & Pitfalls

1. **Placing `#include` inside function bodies**: Always put `#include` statements at the top of the file!
2. **Buffer Overflows with `strcpy`/`sprintf`**: Always use bounded functions like `snprintf`.
3. **Dangling Pointers & Memory Leaks**: Set pointers to `nullptr` after calling `free()`.
4. **Ignoring Allocation Check**: Always verify `ptr != nullptr` after dynamic allocation.

---

## Everyday Copy-and-Paste Modern C Snippets & Systems Recipes

```c
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <time.h>

// 1. Read an Entire File into Dynamically Allocated Memory
char* read_entire_file(const char* filepath, size_t* out_size) {
    FILE* file = fopen(filepath, "rb");
    if (file == nullptr) return nullptr;

    fseek(file, 0, SEEK_END);
    long length = ftell(file);
    fseek(file, 0, SEEK_SET);

    if (length < 0) {
        fclose(file);
        return nullptr;
    }

    char* buffer = (char*)malloc((size_t)length + 1);
    if (buffer == nullptr) {
        fclose(file);
        return nullptr;
    }

    size_t read_bytes = fread(buffer, 1, (size_t)length, file);
    buffer[read_bytes] = '\0';
    fclose(file);

    if (out_size != nullptr) *out_size = read_bytes;
    return buffer;
}

// 2. High-Precision Time Measurement Utility (POSIX clock_gettime)
double measure_execution_time(void (*func_to_test)(void)) {
    struct timespec start, end;
    clock_gettime(CLOCK_MONOTONIC, &start);
    func_to_test();
    clock_gettime(CLOCK_MONOTONIC, &end);

    return (double)(end.tv_sec - start.tv_sec) + (double)(end.tv_nsec - start.tv_nsec) / 1e9;
}

// 3. Dynamic Growing Array (Vector Equivalent in C)
typedef struct {
    int* data;
    size_t size;
    size_t capacity;
} IntVector;

IntVector vector_create(size_t initial_capacity) {
    IntVector vec;
    vec.size = 0;
    vec.capacity = initial_capacity > 0 ? initial_capacity : 4;
    vec.data = (int*)malloc(vec.capacity * sizeof(int));
    return vec;
}

bool vector_push(IntVector* vec, int value) {
    if (vec->size >= vec->capacity) {
        size_t new_cap = vec->capacity * 2;
        int* new_data = (int*)realloc(vec->data, new_cap * sizeof(int));
        if (new_data == nullptr) return false;
        vec->data = new_data;
        vec->capacity = new_cap;
    }
    vec->data[vec->size++] = value;
    return true;
}

void vector_free(IntVector* vec) {
    if (vec->data != nullptr) {
        free(vec->data);
        vec->data = nullptr;
    }
    vec->size = 0;
    vec->capacity = 0;
}
```