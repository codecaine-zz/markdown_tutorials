# C Programming Tutorial for Beginners (ARM Mac + VSCode + Brew)

This tutorial covers C programming from basics to standard library usage with practical examples. We'll set up the environment on an ARM Mac using Homebrew and VSCode.

## Prerequisites & Setup

First, install the required tools:
```bash
# Install Homebrew if not already installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install GCC compiler
brew install gcc

# Install VSCode
brew install --cask visual-studio-code
```

Configure VSCode for C development:
1. Install the "C/C++" extension by Microsoft
2. Install the "Code Runner" extension for easy execution

## 1. Basic Syntax and Structure

### Hello World Program
```c
// Include standard input/output library
#include <stdio.h>

// Main function - entry point of every C program
int main() {
    // Print text to console
    printf("Hello, World!\n");
    
    // Return 0 indicates successful execution
    return 0;
}
```
[Documentation: printf function](https://en.cppreference.com/w/c/io/printf)

### Variables and Data Types
```c
#include <stdio.h>

int main() {
    // Integer variables
    int age = 25;
    short small_number = 100;
    long big_number = 1000000L;
    
    // Floating-point variables
    float price = 19.99f;
    double precise_price = 19.99;
    
    // Character variables
    char grade = 'A';
    char name[] = "John"; // String is array of characters
    
    // Boolean (requires stdbool.h)
    #include <stdbool.h>
    bool is_valid = true;
    
    // Print variables
    printf("Age: %d\n", age);
    printf("Price: %.2f\n", price);
    printf("Grade: %c\n", grade);
    printf("Name: %s\n", name);
    
    return 0;
}
```
[Documentation: Data types](https://en.cppreference.com/w/c/language/arithmetic_types)

## 2. Operators and Expressions

### Arithmetic Operators
```c
#include <stdio.h>

int main() {
    int a = 10, b = 3;
    
    // Basic arithmetic
    printf("Addition: %d + %d = %d\n", a, b, a + b);
    printf("Subtraction: %d - %d = %d\n", a, b, a - b);
    printf("Multiplication: %d * %d = %d\n", a, b, a * b);
    printf("Division: %d / %d = %d\n", a, b, a / b);
    printf("Modulus: %d %% %d = %d\n", a, b, a % b);
    
    // Increment/decrement
    int x = 5;
    printf("x++: %d\n", x++); // Use then increment
    printf("++x: %d\n", ++x); // Increment then use
    
    return 0;
}
```
[Documentation: Arithmetic operators](https://en.cppreference.com/w/c/language/operator_arithmetic)

### Comparison and Logical Operators
```c
#include <stdio.h>
#include <stdbool.h>

int main() {
    int a = 10, b = 20;
    
    // Comparison operators
    printf("a == b: %s\n", (a == b) ? "true" : "false");
    printf("a != b: %s\n", (a != b) ? "true" : "false");
    printf("a < b: %s\n", (a < b) ? "true" : "false");
    printf("a > b: %s\n", (a > b) ? "true" : "false");
    printf("a <= b: %s\n", (a <= b) ? "true" : "false");
    printf("a >= b: %s\n", (a >= b) ? "true" : "false");
    
    // Logical operators
    bool x = true, y = false;
    printf("x && y: %s\n", (x && y) ? "true" : "false"); // AND
    printf("x || y: %s\n", (x || y) ? "true" : "false"); // OR
    printf("!x: %s\n", (!x) ? "true" : "false");         // NOT
    
    return 0;
}
```
[Documentation: Comparison operators](https://en.cppreference.com/w/c/language/operator_comparison)
[Documentation: Logical operators](https://en.cppreference.com/w/c/language/operator_logical)

## 3. Control Flow

### Conditional Statements (if/else)
```c
#include <stdio.h>

int main() {
    int score = 85;
    
    // Simple if statement
    if (score >= 90) {
        printf("Grade: A\n");
    } else if (score >= 80) {
        printf("Grade: B\n");
    } else if (score >= 70) {
        printf("Grade: C\n");
    } else {
        printf("Grade: F\n");
    }
    
    // Ternary operator (shorthand if/else)
    int age = 20;
    char* status = (age >= 18) ? "Adult" : "Minor";
    printf("Status: %s\n", status);
    
    return 0;
}
```
[Documentation: if statement](https://en.cppreference.com/w/c/language/if)

### Switch Statement
```c
#include <stdio.h>

int main() {
    int day = 3;
    
    switch (day) {
        case 1:
            printf("Monday\n");
            break;
        case 2:
            printf("Tuesday\n");
            break;
        case 3:
            printf("Wednesday\n");
            break;
        case 4:
            printf("Thursday\n");
            break;
        case 5:
            printf("Friday\n");
            break;
        case 6:
        case 7:
            printf("Weekend\n");
            break;
        default:
            printf("Invalid day\n");
    }
    
    return 0;
}
```
[Documentation: switch statement](https://en.cppreference.com/w/c/language/switch)

### Loops
```c
#include <stdio.h>

int main() {
    // For loop
    printf("For loop (0 to 4):\n");
    for (int i = 0; i < 5; i++) {
        printf("%d ", i);
    }
    printf("\n");
    
    // While loop
    printf("While loop (5 to 1):\n");
    int j = 5;
    while (j > 0) {
        printf("%d ", j);
        j--;
    }
    printf("\n");
    
    // Do-while loop
    printf("Do-while loop (execute at least once):\n");
    int k = 0;
    do {
        printf("%d ", k);
        k++;
    } while (k < 3);
    printf("\n");
    
    // Nested loops
    printf("Nested loops (multiplication table):\n");
    for (int i = 1; i <= 3; i++) {
        for (int j = 1; j <= 3; j++) {
            printf("%d ", i * j);
        }
        printf("\n");
    }
    
    return 0;
}
```
[Documentation: for loop](https://en.cppreference.com/w/c/language/for)
[Documentation: while loop](https://en.cppreference.com/w/c/language/while)
[Documentation: do-while loop](https://en.cppreference.com/w/c/language/do)

## 4. Functions

### Basic Function Definition
```c
#include <stdio.h>

// Function declaration (prototype)
int add(int a, int b);
void print_message(char* message);

// Main function
int main() {
    int result = add(5, 3);
    printf("5 + 3 = %d\n", result);
    
    print_message("Hello from function!");
    
    return 0;
}

// Function definition
int add(int a, int b) {
    return a + b;
}

void print_message(char* message) {
    printf("Message: %s\n", message);
}
```
[Documentation: Functions](https://en.cppreference.com/w/c/language/functions)

### Functions with Arrays
```c
#include <stdio.h>

// Function to calculate array sum
int sum_array(int arr[], int size) {
    int sum = 0;
    for (int i = 0; i < size; i++) {
        sum += arr[i];
    }
    return sum;
}

// Function to find maximum value
int find_max(int arr[], int size) {
    int max = arr[0];
    for (int i = 1; i < size; i++) {
        if (arr[i] > max) {
            max = arr[i];
        }
    }
    return max;
}

int main() {
    int numbers[] = {10, 5, 8, 20, 3};
    int size = sizeof(numbers) / sizeof(numbers[0]);
    
    printf("Array: ");
    for (int i = 0; i < size; i++) {
        printf("%d ", numbers[i]);
    }
    printf("\n");
    
    printf("Sum: %d\n", sum_array(numbers, size));
    printf("Max: %d\n", find_max(numbers, size));
    
    return 0;
}
```

## 5. Arrays and Strings

### Arrays
```c
#include <stdio.h>

int main() {
    // Array declaration and initialization
    int numbers[5] = {10, 20, 30, 40, 50};
    char letters[] = {'A', 'B', 'C', 'D'};
    
    // Accessing array elements
    printf("First number: %d\n", numbers[0]);
    printf("Last number: %d\n", numbers[4]);
    
    // Modifying array elements
    numbers[0] = 100;
    printf("Modified first number: %d\n", numbers[0]);
    
    // Array traversal
    printf("All numbers: ");
    for (int i = 0; i < 5; i++) {
        printf("%d ", numbers[i]);
    }
    printf("\n");
    
    // 2D array
    int matrix[3][3] = {
        {1, 2, 3},
        {4, 5, 6},
        {7, 8, 9}
    };
    
    printf("Matrix:\n");
    for (int i = 0; i < 3; i++) {
        for (int j = 0; j < 3; j++) {
            printf("%d ", matrix[i][j]);
        }
        printf("\n");
    }
    
    return 0;
}
```
[Documentation: Arrays](https://en.cppreference.com/w/c/language/array)

### Strings
```c
#include <stdio.h>
#include <string.h>

int main() {
    // String declaration
    char str1[] = "Hello";
    char str2[20] = "World";
    char str3[50];
    
    // String functions from string.h
    printf("Length of '%s': %lu\n", str1, strlen(str1));
    
    // String copy
    strcpy(str3, str1);
    printf("Copied string: %s\n", str3);
    
    // String concatenation
    strcat(str3, " ");
    strcat(str3, str2);
    printf("Concatenated string: %s\n", str3);
    
    // String comparison
    if (strcmp(str1, "Hello") == 0) {
        printf("Strings are equal\n");
    }
    
    // Getting string input
    char name[50];
    printf("Enter your name: ");
    fgets(name, sizeof(name), stdin);
    printf("Hello, %s", name);
    
    return 0;
}
```
[Documentation: String functions](https://en.cppreference.com/w/c/string/byte)

## 6. Pointers

### Pointer Basics
```c
#include <stdio.h>

int main() {
    int num = 42;
    int* ptr;  // Pointer to integer
    
    // Assign address of num to ptr
    ptr = &num;
    
    printf("Value of num: %d\n", num);
    printf("Address of num: %p\n", &num);
    printf("Value of ptr: %p\n", ptr);
    printf("Value pointed by ptr: %d\n", *ptr);
    
    // Modify value through pointer
    *ptr = 100;
    printf("New value of num: %d\n", num);
    
    return 0;
}
```
[Documentation: Pointers](https://en.cppreference.com/w/c/language/pointer)

### Pointers and Arrays
```c
#include <stdio.h>

int main() {
    int arr[] = {10, 20, 30, 40, 50};
    int* ptr = arr;  // Array name is pointer to first element
    
    printf("Array elements using pointer:\n");
    for (int i = 0; i < 5; i++) {
        printf("Element %d: %d (address: %p)\n", i, *(ptr + i), ptr + i);
    }
    
    // Pointer arithmetic
    printf("\nPointer arithmetic:\n");
    printf("ptr points to: %d\n", *ptr);
    ptr++;
    printf("After ptr++: %d\n", *ptr);
    ptr += 2;
    printf("After ptr+=2: %d\n", *ptr);
    
    return 0;
}
```

### Pointers to Functions
```c
#include <stdio.h>

// Function prototypes
int add(int a, int b);
int multiply(int a, int b);

// Function that takes a function pointer
int calculate(int a, int b, int (*operation)(int, int)) {
    return operation(a, b);
}

int main() {
    int x = 10, y = 5;
    
    // Function pointers
    int (*add_ptr)(int, int) = add;
    int (*mul_ptr)(int, int) = multiply;
    
    printf("Using function pointers:\n");
    printf("Add: %d\n", add_ptr(x, y));
    printf("Multiply: %d\n", mul_ptr(x, y));
    
    // Passing function pointers to functions
    printf("\nUsing higher-order functions:\n");
    printf("Add: %d\n", calculate(x, y, add));
    printf("Multiply: %d\n", calculate(x, y, multiply));
    
    return 0;
}

int add(int a, int b) {
    return a + b;
}

int multiply(int a, int b) {
    return a * b;
}
```

## 7. Memory Management

### Dynamic Memory Allocation
```c
#include <stdio.h>
#include <stdlib.h>

int main() {
    // malloc - allocate memory
    int* ptr = (int*)malloc(5 * sizeof(int));
    if (ptr == NULL) {
        printf("Memory allocation failed\n");
        return 1;
    }
    
    // Initialize allocated memory
    for (int i = 0; i < 5; i++) {
        ptr[i] = i * 10;
    }
    
    // Print values
    printf("Values in allocated memory:\n");
    for (int i = 0; i < 5; i++) {
        printf("%d ", ptr[i]);
    }
    printf("\n");
    
    // realloc - resize allocated memory
    ptr = (int*)realloc(ptr, 10 * sizeof(int));
    if (ptr == NULL) {
        printf("Memory reallocation failed\n");
        return 1;
    }
    
    // Initialize new elements
    for (int i = 5; i < 10; i++) {
        ptr[i] = i * 10;
    }
    
    printf("Values after reallocation:\n");
    for (int i = 0; i < 10; i++) {
        printf("%d ", ptr[i]);
    }
    printf("\n");
    
    // free - deallocate memory
    free(ptr);
    ptr = NULL;  // Good practice to avoid dangling pointers
    
    return 0;
}
```
[Documentation: malloc](https://en.cppreference.com/w/c/memory/malloc)
[Documentation: free](https://en.cppreference.com/w/c/memory/free)

## 8. Structures and Unions

### Structures
```c
#include <stdio.h>
#include <string.h>

// Define a structure
struct Person {
    char name[50];
    int age;
    float height;
};

// Typedef for easier usage
typedef struct {
    int x;
    int y;
} Point;

int main() {
    // Create structure variables
    struct Person person1;
    Point point1 = {10, 20};
    
    // Initialize structure members
    strcpy(person1.name, "John Doe");
    person1.age = 30;
    person1.height = 5.9;
    
    // Access structure members
    printf("Person: %s, Age: %d, Height: %.1f\n", 
           person1.name, person1.age, person1.height);
    printf("Point: (%d, %d)\n", point1.x, point1.y);
    
    // Array of structures
    struct Person people[2] = {
        {"Alice", 25, 5.5},
        {"Bob", 35, 6.0}
    };
    
    printf("\nPeople:\n");
    for (int i = 0; i < 2; i++) {
        printf("Name: %s, Age: %d, Height: %.1f\n",
               people[i].name, people[i].age, people[i].height);
    }
    
    return 0;
}
```
[Documentation: Structures](https://en.cppreference.com/w/c/language/struct)

### Nested Structures
```c
#include <stdio.h>
#include <string.h>

struct Address {
    char street[100];
    char city[50];
    int zip;
};

struct Employee {
    int id;
    char name[50];
    struct Address address;  // Nested structure
};

int main() {
    struct Employee emp = {
        101,
        "John Smith",
        {"123 Main St", "New York", 10001}
    };
    
    printf("Employee ID: %d\n", emp.id);
    printf("Name: %s\n", emp.name);
    printf("Address: %s, %s, %d\n", 
           emp.address.street, 
           emp.address.city, 
           emp.address.zip);
    
    return 0;
}
```

## 9. File Handling

### Basic File Operations
```c
#include <stdio.h>
#include <stdlib.h>

int main() {
    FILE* file;
    char data[100];
    
    // Writing to a file
    file = fopen("example.txt", "w");
    if (file == NULL) {
        printf("Error opening file for writing\n");
        return 1;
    }
    
    fprintf(file, "Hello, File!\n");
    fprintf(file, "This is line 2\n");
    fclose(file);
    
    // Reading from a file
    file = fopen("example.txt", "r");
    if (file == NULL) {
        printf("Error opening file for reading\n");
        return 1;
    }
    
    printf("File contents:\n");
    while (fgets(data, sizeof(data), file) != NULL) {
        printf("%s", data);
    }
    fclose(file);
    
    // Appending to a file
    file = fopen("example.txt", "a");
    if (file == NULL) {
        printf("Error opening file for appending\n");
        return 1;
    }
    
    fprintf(file, "This line was appended\n");
    fclose(file);
    
    return 0;
}
```
[Documentation: File I/O](https://en.cppreference.com/w/c/io)

### Binary File Operations
```c
#include <stdio.h>
#include <stdlib.h>

struct Student {
    int id;
    char name[50];
    float grade;
};

int main() {
    FILE* file;
    struct Student s1 = {1, "John Doe", 85.5};
    struct Student s2;
    
    // Writing binary data
    file = fopen("students.dat", "wb");
    if (file == NULL) {
        printf("Error opening file for writing\n");
        return 1;
    }
    
    fwrite(&s1, sizeof(struct Student), 1, file);
    fclose(file);
    
    // Reading binary data
    file = fopen("students.dat", "rb");
    if (file == NULL) {
        printf("Error opening file for reading\n");
        return 1;
    }
    
    fread(&s2, sizeof(struct Student), 1, file);
    fclose(file);
    
    printf("Student ID: %d\n", s2.id);
    printf("Name: %s\n", s2.name);
    printf("Grade: %.1f\n", s2.grade);
    
    return 0;
}
```

## 10. Standard Library Functions

### Math Functions
```c
#include <stdio.h>
#include <math.h>

int main() {
    double x = 4.0;
    double y = 2.0;
    
    printf("Square root of %.1f: %.2f\n", x, sqrt(x));
    printf("%.1f raised to power %.1f: %.2f\n", x, y, pow(x, y));
    printf("Sine of 90 degrees: %.2f\n", sin(90 * M_PI / 180));
    printf("Cosine of 0 degrees: %.2f\n", cos(0));
    printf("Natural log of %.1f: %.2f\n", x, log(x));
    printf("Absolute value of -5: %.0f\n", fabs(-5.0));
    
    return 0;
}
```
[Documentation: Math functions](https://en.cppreference.com/w/c/numeric/math)

### Time Functions
```c
#include <stdio.h>
#include <time.h>

int main() {
    // Current time
    time_t current_time = time(NULL);
    printf("Current time: %s", ctime(&current_time));
    
    // Formatted time
    struct tm* time_info = localtime(&current_time);
    char buffer[80];
    strftime(buffer, sizeof(buffer), "%Y-%m-%d %H:%M:%S", time_info);
    printf("Formatted time: %s\n", buffer);
    
    // Measure execution time
    clock_t start = clock();
    
    // Some work
    for (int i = 0; i < 1000000; i++) {
        // Empty loop
    }
    
    clock_t end = clock();
    double cpu_time_used = ((double)(end - start)) / CLOCKS_PER_SEC;
    printf("Time taken: %f seconds\n", cpu_time_used);
    
    return 0;
}
```
[Documentation: Time functions](https://en.cppreference.com/w/c/chrono)

### Character Functions
```c
#include <stdio.h>
#include <ctype.h>

int main() {
    char ch = 'A';
    char str[] = "Hello, World! 123";
    
    printf("Character: %c\n", ch);
    printf("Is uppercase? %s\n", isupper(ch) ? "Yes" : "No");
    printf("To lowercase: %c\n", tolower(ch));
    
    printf("\nString: %s\n", str);
    printf("Processing each character:\n");
    
    for (int i = 0; str[i] != '\0'; i++) {
        if (isalpha(str[i])) {
            printf("'%c' is a letter\n", str[i]);
        } else if (isdigit(str[i])) {
            printf("'%c' is a digit\n", str[i]);
        } else if (isspace(str[i])) {
            printf("'%c' is whitespace\n", str[i]);
        } else {
            printf("'%c' is a special character\n", str[i]);
        }
    }
    
    return 0;
}
```
[Documentation: Character functions](https://en.cppreference.com/w/c/string/byte)

## 11. Error Handling

### errno and perror
```c
#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <string.h>

int main() {
    FILE* file = fopen("nonexistent.txt", "r");
    
    if (file == NULL) {
        printf("Error opening file: %s\n", strerror(errno));
        perror("fopen");
        return 1;
    }
    
    fclose(file);
    return 0;
}
```
[Documentation: Error handling](https://en.cppreference.com/w/c/error)

### Assert
```c
#include <stdio.h>
#include <assert.h>

int divide(int a, int b) {
    assert(b != 0);  // Program will terminate if b is 0
    return a / b;
}

int main() {
    int result;
    
    result = divide(10, 2);
    printf("10 / 2 = %d\n", result);
    
    // This will cause assertion failure
    // result = divide(10, 0);
    
    return 0;
}
```
[Documentation: assert](https://en.cppreference.com/w/c/error/assert)

## 12. Preprocessor Directives

### Macros and Conditional Compilation
```c
#include <stdio.h>

// Simple macro
#define PI 3.14159
#define SQUARE(x) ((x) * (x))

// Conditional compilation
#ifdef DEBUG
    #define DBG_PRINT(x) printf("Debug: %s\n", x)
#else
    #define DBG_PRINT(x)
#endif

// Multi-line macro
#define MAX(a, b) ((a) > (b) ? (a) : (b))

int main() {
    printf("PI: %.5f\n", PI);
    printf("Square of 5: %d\n", SQUARE(5));
    
    DBG_PRINT("This is a debug message");
    
    int x = 10, y = 20;
    printf("Max of %d and %d: %d\n", x, y, MAX(x, y));
    
    return 0;
}
```
[Documentation: Preprocessor](https://en.cppreference.com/w/c/preprocessor)

## 13. Command Line Arguments

### Processing Command Line Arguments
```c
#include <stdio.h>

int main(int argc, char* argv[]) {
    printf("Number of arguments: %d\n", argc);
    
    printf("Program name: %s\n", argv[0]);
    
    printf("Arguments:\n");
    for (int i = 1; i < argc; i++) {
        printf("  argv[%d]: %s\n", i, argv[i]);
    }
    
    // Example: Simple calculator
    if (argc == 4) {
        int a = atoi(argv[1]);
        int b = atoi(argv[3]);
        char op = argv[2][0];
        
        switch (op) {
            case '+':
                printf("Result: %d\n", a + b);
                break;
            case '-':
                printf("Result: %d\n", a - b);
                break;
            case '*':
                printf("Result: %d\n", a * b);
                break;
            case '/':
                if (b != 0) {
                    printf("Result: %d\n", a / b);
                } else {
                    printf("Error: Division by zero\n");
                }
                break;
            default:
                printf("Error: Unknown operator\n");
        }
    } else {
        printf("Usage: %s <num1> <operator> <num2>\n", argv[0]);
        printf("Example: %s 5 + 3\n", argv[0]);
    }
    
    return 0;
}
```
[Documentation: main function](https://en.cppreference.com/w/c/language/main_function)

## 14. Best Practices and Tips

### Header Files and Modular Programming
```c
// math_utils.h
#ifndef MATH_UTILS_H
#define MATH_UTILS_H

int add(int a, int b);
int subtract(int a, int b);
int multiply(int a, int b);
float divide(int a, int b);

#endif
```

```c
// math_utils.c
#include "math_utils.h"

int add(int a, int b) {
    return a + b;
}

int subtract(int a, int b) {
    return a - b;
}

int multiply(int a, int b) {
    return a * b;
}

float divide(int a, int b) {
    if (b != 0) {
        return (float)a / b;
    }
    return 0.0f;
}
```

```c
// main.c
#include <stdio.h>
#include "math_utils.h"

int main() {
    int x = 10, y = 5;
    
    printf("Add: %d\n", add(x, y));
    printf("Subtract: %d\n", subtract(x, y));
    printf("Multiply: %d\n", multiply(x, y));
    printf("Divide: %.2f\n", divide(x, y));
    
    return 0;
}
```

To compile:
```bash
gcc -o program main.c math_utils.c
```

## Compilation and Execution

### Basic Compilation
```bash
# Compile single file
gcc -o hello hello.c

# Compile with warnings enabled
gcc -Wall -Wextra -o hello hello.c

# Compile multiple files
gcc -o program main.c math_utils.c

# Compile with debugging information
gcc -g -o program program.c

# Run the program
./hello
```

### Makefile Example
```makefile
# Makefile
CC = gcc
CFLAGS = -Wall -Wextra -std=c99
TARGET = program
SOURCES = main.c math_utils.c
OBJECTS = $(SOURCES:.c=.o)

$(TARGET): $(OBJECTS)
	$(CC) $(OBJECTS) -o $(TARGET)

%.o: %.c
	$(CC) $(CFLAGS) -c $< -o $@

clean:
	rm -f $(OBJECTS) $(TARGET)

.PHONY: clean
```

This tutorial provides a comprehensive introduction to C programming with practical examples. Each code block is designed to be copy-paste friendly for rapid development. Remember to always compile with warnings enabled (`-Wall -Wextra`) to catch potential issues early.

For more detailed information, refer to the official C documentation at [cppreference.com](https://en.cppreference.com/w/c) and the [GNU C Library documentation](https://www.gnu.org/software/libc/manual/).