# PHP Tutorial for Beginners with Official Documentation Links

I'll create a comprehensive tutorial to help you learn PHP quickly with links to official documentation for each concept.

## Installation on ARM Mac with Homebrew

```bash
# Install PHP using Homebrew
brew install php

# Verify installation
php --version

# Install VS Code extension for PHP
# In VS Code, search for "PHP IntelliSense" extension by Felix Becker
```

[PHP Installation Guide](https://www.php.net/manual/en/install.php)

## Basic Syntax Examples

### 1. Hello World

```php
<?php
// hello.php
echo "Hello, World!\n";
?>
```

[PHP Basics Documentation](https://www.php.net/manual/en/language.basic-syntax.php)

To run:
```bash
php hello.php
```

### 2. Variables and Data Types

```php
<?php
// variables.php

// Basic data types
$name = "PHP";
$age = 25;
$price = 99.99;
$is_active = true;
$null_value = null;

// Explicit typing (PHP 7+)
function setAge(int $age): void {
    echo "Age is: $age\n";
}

// Constants
define("PI", 3.14159);
const SITE_NAME = "My Website";

echo "Name: $name\n";
echo "Age: $age\n";
echo "Price: $$price\n";
echo "Active: " . ($is_active ? "Yes" : "No") . "\n";
echo "PI: " . PI . "\n";
echo "Site Name: " . SITE_NAME . "\n";

setAge(30);
?>
```

[Variables Documentation](https://www.php.net/manual/en/language.variables.php)

### 3. Functions

```php
<?php
// functions.php

// Function with parameters and return value
function say_hello($name) {
    return "Hello, $name!";
}

function add($x, $y) {
    return $x + $y;
}

// Function with default parameters
function greet($name, $greeting = "Hello") {
    return "$greeting, $name!";
}

// Function with type hints (PHP 7+)
function multiply(float $x, float $y): float {
    return $x * $y;
}

// Function that returns multiple values
function divide($dividend, $divisor) {
    if ($divisor == 0) {
        return ["quotient" => null, "remainder" => null, "error" => "Division by zero"];
    }
    return [
        "quotient" => intdiv($dividend, $divisor),
        "remainder" => $dividend % $divisor,
        "error" => null
    ];
}

// Usage examples
echo say_hello("World") . "\n";
echo "5 + 3 = " . add(5, 3) . "\n";
echo greet("Alice") . "\n";
echo greet("Bob", "Hi") . "\n";
echo "4.5 * 2.0 = " . multiply(4.5, 2.0) . "\n";

$result = divide(10, 3);
if ($result["error"]) {
    echo "Error: " . $result["error"] . "\n";
} else {
    echo "10 / 3 = " . $result["quotient"] . " remainder " . $result["remainder"] . "\n";
}
?>
```

[Functions Documentation](https://www.php.net/manual/en/language.functions.php)

### 4. Control Flow

```php
<?php
// control_flow.php

// If statements
$age = 20;
if ($age >= 18) {
    echo "You are an adult\n";
} elseif ($age >= 13) {
    echo "You are a teenager\n";
} else {
    echo "You are a child\n";
}

// For loops
echo "Counting to 5:\n";
for ($i = 0; $i < 5; $i++) {
    echo "$i\n";
}

// While loop
$count = 0;
while ($count < 3) {
    echo "While loop: $count\n";
    $count++;
}

// Foreach loop with array
$numbers = [1, 2, 3, 4, 5];
echo "Array elements:\n";
foreach ($numbers as $num) {
    echo "$num\n";
}

// Switch statement
$day = "Monday";
switch ($day) {
    case "Monday":
        echo "Start of work week\n";
        break;
    case "Friday":
        echo "End of work week\n";
        break;
    default:
        echo "Mid-week day\n";
}

// Match expression (PHP 8+)
$food = 'apple';
$return_value = match ($food) {
    'apple' => 'This is an apple',
    'banana' => 'This is a banana',
    'orange' => 'This is an orange',
    default => 'Unknown fruit',
};
echo "$return_value\n";
?>
```

[Control Flow Documentation](https://www.php.net/manual/en/language.control-structures.php)

### 5. Arrays

```php
<?php
// arrays.php

// Indexed arrays
$numbers = [1, 2, 3, 4, 5];
echo "First element: " . $numbers[0] . "\n";
echo "Array count: " . count($numbers) . "\n";

// Modifying array
$numbers[0] = 10;
echo "Modified first element: " . $numbers[0] . "\n";

// Adding elements
$numbers[] = 6;  // Append
array_push($numbers, 7);  // Another way to append

// Associative arrays (key-value pairs)
$scores = [
    "Alice" => 95,
    "Bob" => 87,
    "Charlie" => 92
];

echo "Alice's score: " . $scores["Alice"] . "\n";

// Check if key exists
if (array_key_exists("David", $scores)) {
    echo "David's score: " . $scores["David"] . "\n";
} else {
    echo "David has no score recorded\n";
}

// Iterating through associative array
echo "All scores:\n";
foreach ($scores as $name => $score) {
    echo "$name: $score\n";
}

// Multidimensional arrays
$students = [
    ["name" => "Alice", "age" => 20, "grade" => "A"],
    ["name" => "Bob", "age" => 21, "grade" => "B"],
    ["name" => "Charlie", "age" => 19, "grade" => "A"]
];

echo "Student list:\n";
foreach ($students as $student) {
    echo $student["name"] . " is " . $student["age"] . " years old with grade " . $student["grade"] . "\n";
}
?>
```

[Arrays Documentation](https://www.php.net/manual/en/language.types.array.php)

### 6. Classes (Object-Oriented Programming)

```php
<?php
// classes.php

class Person {
    // Properties
    private $name;
    private $age;
    private $is_happy;
    
    // Constructor
    public function __construct($name, $age, $is_happy = true) {
        $this->name = $name;
        $this->age = $age;
        $this->is_happy = $is_happy;
    }
    
    // Getter methods
    public function getName() {
        return $this->name;
    }
    
    public function getAge() {
        return $this->age;
    }
    
    public function isHappy() {
        return $this->is_happy;
    }
    
    // Setter methods
    public function setHappiness($happy) {
        $this->is_happy = $happy;
    }
    
    // Methods
    public function greet() {
        echo "Hello, my name is " . $this->name . "\n";
    }
    
    public function haveBirthday() {
        $this->age++;
    }
    
    // Magic method for string representation
    public function __toString() {
        return $this->name . " (Age: " . $this->age . ")";
    }
}

// Creating a class instance
$person = new Person("Alice", 30, true);

echo "Name: " . $person->getName() . "\n";
echo "Age: " . $person->getAge() . "\n";
echo "Is happy: " . ($person->isHappy() ? "Yes" : "No") . "\n";

// Using methods
$person->greet();
$person->haveBirthday();
echo "New age: " . $person->getAge() . "\n";

echo "Person details: " . $person . "\n";
?>
```

[Classes Documentation](https://www.php.net/manual/en/language.oop5.php)

### 7. Error Handling

```php
<?php
// error_handling.php

// Custom exception
class DivisionByZeroException extends Exception {}

function divide_safe($x, $y) {
    if ($y == 0) {
        throw new DivisionByZeroException("Division by zero is not allowed");
    }
    return $x / $y;
}

// Try-catch block
try {
    $result = divide_safe(10, 2);
    echo "Result: $result\n";
} catch (DivisionByZeroException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}

try {
    $result = divide_safe(10, 0);
    echo "Result: $result\n";
} catch (DivisionByZeroException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Error handling with error handlers
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    echo "Custom Error: [$errno] $errstr - $errfile:$errline\n";
    return true;
}

set_error_handler("custom_error_handler");

// This will trigger our custom error handler
echo $undefined_variable;
?>
```

[Error Handling Documentation](https://www.php.net/manual/en/book.errorfunc.php)

### 8. Working with Strings

```php
<?php
// strings.php

// String operations
$greeting = "Hello";
$name = "PHP Language";

// String concatenation
$message = $greeting . ", " . $name . "!";
echo "$message\n";

// String interpolation (with double quotes)
echo "Welcome to $name\n";

// String functions
echo "Length: " . strlen($message) . "\n";
echo "Uppercase: " . strtoupper($message) . "\n";
echo "Lowercase: " . strtolower($message) . "\n";

// Substrings
$part = substr($message, 0, 5);
echo "First 5 chars: $part\n";

// String replacement
$new_message = str_replace("PHP", "Awesome PHP", $message);
echo "Replaced: $new_message\n";

// String position
if (strpos($message, "PHP") !== false) {
    echo "Message mentions PHP\n";
}

// Exploding strings
$csv_data = "apple,banana,orange";
$fruits = explode(",", $csv_data);
echo "Fruits: " . implode(", ", $fruits) . "\n";

// Trimming whitespace
$spaced_text = "  Hello World  ";
echo "Trimmed: '" . trim($spaced_text) . "'\n";
?>
```

[Strings Documentation](https://www.php.net/manual/en/book.strings.php)

### 9. File I/O

```php
<?php
// file_io.php

// Writing to a file
$data = "Hello from PHP!\nThis is a test file.";
if (file_put_contents("example.txt", $data) !== false) {
    echo "File written successfully\n";
} else {
    echo "Failed to write file\n";
}

// Reading from a file
if (file_exists("example.txt")) {
    $content = file_get_contents("example.txt");
    echo "File content:\n$content\n";
    
    // Reading file line by line
    $lines = file("example.txt");
    echo "Lines in file: " . count($lines) . "\n";
} else {
    echo "File does not exist\n";
}

// Using file handles for more control
$file = fopen("example2.txt", "w");
if ($file) {
    fwrite($file, "Line 1\n");
    fwrite($file, "Line 2\n");
    fclose($file);
    echo "File written with file handle\n";
}

// Reading with file handle
if (file_exists("example2.txt")) {
    $file = fopen("example2.txt", "r");
    if ($file) {
        echo "File content (via handle):\n";
        while (($line = fgets($file)) !== false) {
            echo $line;
        }
        fclose($file);
    }
}

// Clean up
if (file_exists("example.txt")) {
    unlink("example.txt");
}
if (file_exists("example2.txt")) {
    unlink("example2.txt");
}
?>
```

[File I/O Documentation](https://www.php.net/manual/en/book.filesystem.php)

### 10. Working with JSON

```php
<?php
// json_example.php

class User {
    public $name;
    public $age;
    public $city;
    
    public function __construct($name, $age, $city) {
        $this->name = $name;
        $this->age = $age;
        $this->city = $city;
    }
}

// Create an object
$user = new User("Alice", 30, "New York");

// Serialize to JSON
$json_data = json_encode($user);
echo "JSON: $json_data\n";

// Deserialize from JSON
$user2 = json_decode($json_data);
echo "Name: " . $user2->name . "\n";
echo "Age: " . $user2->age . "\n";
echo "City: " . $user2->city . "\n";

// Working with associative arrays
$data = [
    "users" => [
        ["name" => "Alice", "age" => 30],
        ["name" => "Bob", "age" => 25]
    ],
    "total" => 2
];

$json_array = json_encode($data, JSON_PRETTY_PRINT);
echo "Pretty JSON:\n$json_array\n";

$decoded_array = json_decode($json_array, true); // true for associative array
echo "First user: " . $decoded_array["users"][0]["name"] . "\n";
?>
```

[JSON Documentation](https://www.php.net/manual/en/book.json.php)

### 11. HTTP Requests

```php
<?php
// http_client.php

// Simple GET request using file_get_contents
$url = "https://httpbin.org/get";
$response = file_get_contents($url);
if ($response !== false) {
    echo "Response from file_get_contents:\n";
    echo $response . "\n";
} else {
    echo "Failed to make request with file_get_contents\n";
}

// Using cURL for more control
function http_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ["response" => $response, "http_code" => $http_code];
}

$result = http_get("https://httpbin.org/get");
echo "cURL Response (HTTP " . $result["http_code"] . "):\n";
echo $result["response"] . "\n";

// POST request with cURL
function http_post($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

$post_data = ["name" => "Alice", "age" => 30];
$post_response = http_post("https://httpbin.org/post", $post_data);
echo "POST Response:\n";
echo $post_response . "\n";
?>
```

[HTTP Context Options](https://www.php.net/manual/en/context.http.php)
[cURL Documentation](https://www.php.net/manual/en/book.curl.php)

### 12. Working with Dates and Time

```php
<?php
// time_example.php

// Current time
$current_time = time();
echo "Current timestamp: $current_time\n";

// Formatted date/time
echo "Current date/time: " . date("Y-m-d H:i:s") . "\n";
echo "Formatted: " . date("F j, Y, g:i a") . "\n";

// Creating DateTime objects
$date = new DateTime();
echo "DateTime object: " . $date->format("Y-m-d H:i:s") . "\n";

// Parsing dates
$parsed_date = DateTime::createFromFormat("Y-m-d", "2023-12-25");
echo "Parsed date: " . $parsed_date->format("F j, Y") . "\n";

// Date arithmetic
$future_date = new DateTime();
$future_date->add(new DateInterval("P7D")); // Add 7 days
echo "7 days from now: " . $future_date->format("Y-m-d") . "\n";

$past_date = new DateTime();
$past_date->sub(new DateInterval("P30D")); // Subtract 30 days
echo "30 days ago: " . $past_date->format("Y-m-d") . "\n";

// Timezone handling
$date_utc = new DateTime("now", new DateTimeZone("UTC"));
echo "UTC time: " . $date_utc->format("Y-m-d H:i:s T") . "\n";

$date_ny = new DateTime("now", new DateTimeZone("America/New_York"));
echo "New York time: " . $date_ny->format("Y-m-d H:i:s T") . "\n";

// Measuring execution time
$start_time = microtime(true);

// Simulate some work
sleep(1);

$end_time = microtime(true);
$execution_time = ($end_time - $start_time);
echo "Executed in " . number_format($execution_time, 4) . " seconds\n";
?>
```

[Date/Time Documentation](https://www.php.net/manual/en/book.datetime.php)

## VS Code Configuration

Create these files in your project root:

### .vscode/settings.json
```json
{
    "php.validate.executablePath": "/opt/homebrew/bin/php",
    "php.suggest.basic": true,
    "files.associations": {
        "*.php": "php"
    }
}
```

[PHP VS Code Extension](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-intellisense)

### .vscode/tasks.json
```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "run php script",
            "type": "shell",
            "command": "php",
            "args": ["${file}"],
            "group": {
                "kind": "build",
                "isDefault": true
            },
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "shared"
            }
        }
    ]
}
```

## Quick Reference Summary

```php
<?php
// quick_reference.php

// Variables
$immutable_var = "value";  // All PHP variables can be changed
$mutable_var = "value";

// Basic types
$age = 25;           // Integer
$price = 99.99;      // Float
$is_active = true;   // Boolean
$name = "PHP";       // String
$null_value = null;  // Null

// Arrays
$indexed_array = [1, 2, 3];  // Indexed array
$assoc_array = [             // Associative array
    "Alice" => 95,
    "Bob" => 87
];

// Functions
function add($x, $y) {
    return $x + $y;
}

// Classes
class Point {
    public $x, $y;
    
    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }
}

// Error handling
try {
    // Code that might throw an exception
} catch (Exception $e) {
    // Handle exception
}
?>
```

[PHP Language Reference](https://www.php.net/manual/en/langref.php)

## Running PHP Scripts

```bash
# Run a PHP script
php script.php

# Run PHP built-in server for web development
php -S localhost:8000

# Check PHP syntax without running
php -l script.php

# Run PHP interactively
php -a

# View PHP configuration
php -i
```

[PHP Command Line Documentation](https://www.php.net/manual/en/features.commandline.php)

This tutorial covers the essential concepts of PHP programming language with practical examples and links to official documentation. Start with the basic examples and gradually work through more complex concepts. PHP is a server-side scripting language that's excellent for web development, with a simple syntax that's easy to learn for beginners.