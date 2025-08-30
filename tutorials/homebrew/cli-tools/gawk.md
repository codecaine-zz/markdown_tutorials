## Table of Contents
1. [Introduction to GAWK](#introduction-to-gawk)
2. [Basic Syntax and Patterns](#basic-syntax-and-patterns)
3. [Built-in Variables and Functions](#built-in-variables-and-functions)
4. [Field Processing and Regular Expressions](#field-processing-and-regular-expressions)
5. [Advanced Pattern Matching](#advanced-pattern-matching)
6. [Functions and User-defined Functions](#functions-and-user-defined-functions)
7. [Arrays in GAWK](#arrays-in-gawk)
8. [File Handling and I/O Operations](#file-handling-and-io-operations)
9. [Practical Examples and Use Cases](#practical-examples-and-use-cases)
10. [Performance Optimization Techniques](#performance-optimization-techniques)

## Introduction to GAWK

GAWK (GNU awk) is a powerful text processing tool that can read files line by line, perform pattern matching, and transform data based on rules you define.

### Basic Command Structure
```bash
gawk [options] 'program' [file...]
```

### Simple Example
```bash
# Print all lines from a file
echo "Hello World" | gawk '{print $0}'

# Print first field of each line
echo -e "John Doe\nJane Smith" | gawk '{print $1}'
```

## Basic Syntax and Patterns

### The Fundamental Structure
```bash
# Pattern { Action }
BEGIN { action }           # Runs once before processing input
pattern { action }         # Runs for matching lines
END { action }             # Runs once after processing input

# Example: Simple print with BEGIN/END
gawk 'BEGIN { print "Processing started" }
      { print $0 }
      END { print "Processing completed" }' file.txt
```

### Pattern Types

```bash
# 1. All lines (no pattern)
{ action }

# 2. Regular expressions
/regex/ { action }
/^start/ { action }          # Lines starting with 'start'
/end$/ { action }            # Lines ending with 'end'

# 3. Numeric expressions
NR == 1 { action }           # First line
NR > 10 { action }           # Lines after line 10
FNR == NR { action }         # First file processing

# 4. Boolean expressions
$1 ~ /pattern/ { action }    # Field 1 matches pattern
$2 !~ /pattern/ { action }   # Field 2 does not match pattern

# 5. Range patterns (lines between two patterns)
/start/, /end/ { action }
```

### Simple Pattern Matching Examples

```bash
# Create test file
cat > employees.txt << EOF
John,Engineer,30000,New York
Jane,Manager,50000,San Francisco
Bob,Clerk,25000,Boston
Alice,Director,75000,Chicago
EOF

# Print all lines with "Manager"
gawk '/Manager/ { print $1 }' employees.txt

# Print first field of lines matching pattern
gawk '$1 ~ /J/ { print $1 }' employees.txt

# Print line numbers where salary > 30000
gawk '$3 > 30000 { print NR ": " $1 " - $" $3 }' employees.txt
```

## Built-in Variables and Functions

### Essential AWK Variables

```bash
# Field variables
$1, $2, ..., $NF    # First, second, etc. fields; last field
FS                 # Field separator (default: space/tab)
OFS                # Output field separator (default: space)

# Line variables  
$0                 # Entire input line
NR                 # Current record number (line number)
FNR                # File record number (per file)
FILENAME           # Current filename

# Example demonstrating built-in variables
gawk 'BEGIN { print "Field Separator:", FS }
      NR == 1 { print "First line:"; print $0 }
      NR > 1 && NR < 4 { print "Line", NR, ": ", $0 }
      END { print "Total lines processed:", NR }' employees.txt
```

### Built-in Functions

```bash
# String functions
length()            # Length of string or field
substr(string, start [, length])    # Extract substring
index(string, substr)               # Find position of substring
tolower(string)                     # Convert to lowercase  
toupper(string)                     # Convert to uppercase

# Numeric functions
int(x)              # Integer part of x
sqrt(x)             # Square root of x
log(x)              # Natural logarithm
exp(x)              # Exponential function  
rand()              # Random number 0-1

# Example using string functions
gawk '{
    print "Line length:", length($0)
    print "First 5 chars:", substr($0, 1, 5)
    if (length($1) > 3) {
        print "Name is long"
    }
}' employees.txt

# Numeric operations example
cat > scores.txt << EOF
Math:85
Science:92
English:78
History:88
EOF

gawk '{
    score = $2 + 0   # Convert to number (safely)
    print "Score:", score, ", Square root:", sqrt(score)
}' scores.txt
```

## Field Processing and Regular Expressions

### Field Separators and Processing

```bash
# Custom field separator
cat > data.csv << EOF
John;25;Engineer;New York
Jane;30;Manager;San Francisco
Bob;28;Clerk;Boston
EOF

gawk 'BEGIN { FS = ";" } 
      { print "Name:", $1, "Age:", $2, "Job:", $3 }' data.csv

# Change output field separator
gawk 'BEGIN { OFS = "|" }
      { print $1, $2, $3 }' employees.txt
```

### Regular Expression Patterns

```bash
# Basic regex matching with ~ and !
echo -e "hello world\nworld peace\nhelloworld" | gawk '/world/ { print $0 }'

# Pattern negation
echo -e "apple\nbanana\ncherry" | gawk '!/a/ { print $0 }'

# Complex pattern matching
cat > log.txt << EOF
2023-12-01 14:30:45 ERROR Connection failed
2023-12-01 14:31:05 WARN Timeout occurred
2023-12-01 14:32:15 INFO Processing complete
EOF

# Extract error messages
gawk '/ERROR/ { print $3, $4 }' log.txt

# Find lines with specific date format
gawk '/[0-9]{4}-[0-9]{2}-[0-9]{2}/ { print $1 }' log.txt
```

### Field Processing Examples

```bash
# Process fields with conditions
gawk '{
    if ($3 ~ /Manager|Director/) {
        print "Senior:", $1, "- Salary: $" $3
    } else {
        print "Junior:", $1, "- Salary: $" $3
    }
}' employees.txt

# Arithmetic operations on fields  
echo -e "20 5\n15 3\n8 4" | gawk '{print $1 "/" $2 "=", $1/$2}'
```

## Advanced Pattern Matching

### Range Patterns and Complex Conditions

```bash
# Range patterns (process lines between two patterns)
cat > book.txt << EOF
Title: The Great Gatsby
Author: F. Scott Fitzgerald
Year: 1925

Title: To Kill a Mockingbird
Author: Harper Lee
Year: 1960

Title: 1984
Author: George Orwell
Year: 1948
EOF

# Process only lines between "Title:" and blank line
gawk '/^Title:/, /^$/ { print $2 }' book.txt

# Multiple pattern conditions
gawk '($3 > 50000 && $2 ~ /Manager|Director/) || ($3 < 30000) {
    print "Name:", $1, "Salary:", $3
}' employees.txt
```

### Pattern Matching with Variables

```bash
# Use variables in patterns (use -v flag)
pattern="Engineer"
gawk -v search=$pattern '$2 == search { print $1 }' employees.txt

# Using built-in variable in pattern
gawk 'NR > 1 && NR < 4 { print "Line", NR, ":", $0 }' employees.txt
```

### Advanced Field Extraction

```bash
# Extract specific parts using regex and field manipulation
cat > phonebook.csv << EOF
John Smith,555-1234,jsmith@email.com,New York
Jane Doe,555-5678,jdoe@email.com,San Francisco
Bob Johnson,555-9012,bjohnson@email.com,Boston
EOF

gawk 'BEGIN { FS = "," }
      {
          phone = $2  # Extract phone number
          if (phone ~ /555-[0-9]{4}/) {
              print "Valid contact:", $1, "Phone:", phone
          }
      }' phonebook.csv
```

## Practical Examples and Exercises

### Data Analysis Example

```bash
# Simple statistics calculator using awk
cat > sales.txt << EOF
ProductA:100:5.99
ProductB:75:8.50
ProductC:200:3.25
EOF

gawk 'BEGIN { total = 0; count = 0 }
      {
          quantity = $2 + 0   # Convert to number
          price = $3 + 0
          revenue = quantity * price
          total += revenue
          count++
      }
      END { 
          print "Total Revenue:", total
          print "Average Revenue:", total/count
      }' sales.txt

# Count and categorize data  
gawk '{
    if ($2 > 150) {
        high++
    } else if ($2 > 50) {
        medium++
    } else {
        low++
    }
}
END {
    print "High Sales:", high
    print "Medium Sales:", medium  
    print "Low Sales:", low
}' sales.txt
```

### Text Transformation Example

```bash
# Convert text case and format data
cat > raw_data.txt << EOF
john smith 25 engineer new york
jane doe 30 manager san francisco
bob johnson 28 clerk boston
EOF

gawk '{
    # Capitalize first letter of names  
    name1 = $1; name2 = $2
    name1 = toupper(substr(name1,1,1)) substr(name1,2)
    name2 = toupper(substr(name2,1,1)) substr(name2,2)
    
    print "Name:", name1 " " name2 
    print "Age:", $3
    print "Job:", $4
    print "Location:", $5  
    print "---"
}' raw_data.txt

# Format output with padding
gawk '{
    printf "%-10s %-5s %-10s\n", $1, $2, $3
}' employees.txt
```

### Multi-file Processing

```bash
# Process multiple files using command line arguments  
cat > file1.txt << EOF
Name: John Age: 25 City: New York
Name: Jane Age: 30 City: San Francisco  
EOF

gawk '{
    gsub(/Name:/, "Person:") 
    print $0
}' file1.txt file2.txt

# Combine output from multiple files with line numbers
gawk '{print NR ": " $0}' employees.txt log.txt
```

These examples demonstrate the power of `awk` for text processing and data manipulation. The key features include field-based operations, pattern matching with regular expressions, arithmetic calculations, string manipulation functions, and flexible input/output formatting. You can combine these techniques to build complex text processing pipelines for various real-world scenarios like log analysis, data transformation, report generation, and more.

Would you like me to elaborate on any specific aspect of `awk` or provide additional examples? This should give you a solid foundation to start using awk effectively!

