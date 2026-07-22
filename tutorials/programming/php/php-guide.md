# The PHP 8.3/8.4 Programming Language: A Comprehensive Textbook Guide for macOS

Welcome to the ultimate learning guide for modern PHP (PHP 8.3/8.4) on macOS (ARM64 Apple Silicon)! This textbook is structured specifically to take you from a complete beginner (zero programming experience) to an advanced backend developer capable of building robust web APIs, object-oriented applications, database-driven services, and scalable web platforms. Rather than treating PHP as legacy script tags mixed with HTML, this guide emphasizes modern PHP 8+ engineering: strong typing, constructor property promotion, enums, `readonly` classes, `match` expressions, attributes, fibers, and Composer-managed architectures.

> [!NOTE]
> **How to read this book:** Each section starts with a clear explanation of core PHP 8+ language features, followed by concrete, runnable PHP code examples. Every example is tested for Homebrew PHP on macOS ARM64 using standard CLI execution.

> [!TIP]
> **Interactive Learning:** You can execute any PHP file directly in your terminal using `php filename.php` or launch a local dev server with `php -S localhost:8000`.

## Repository Structure

This book is paired with a topic-based repository layout:

- `basics/` for scalar types, variable definitions, string interpolation, and operators
- `control_flow/` for conditional logic, modern `match` expressions, and loops
- `functions_and_closures/` for typed parameters, default arguments, arrow functions, and named arguments
- `oop_and_classes/` for classes, constructor property promotion, enums, `readonly` classes, and interfaces
- `error_handling_and_exceptions/` for `try`/`catch`/`finally`, custom exceptions, and error boundaries
- `database_and_pdo/` for safe database queries with PDO, SQLite, MySQL, and dynamic prepared statements

## Quick Start: Learn Modern PHP by Building Things

1. Install PHP on macOS Apple Silicon via Homebrew:
   ```bash
   brew install php
   ```
2. Verify PHP installation:
   ```bash
   php --version
   ```
3. Create a file named `hello.php`:
   ```php
   <?php

   declare(strict_types=1);

   $message = "Hello, Modern PHP 8.3/8.4 on Apple Silicon!";
   echo $message . PHP_EOL;
   ```
4. Run it directly:
   ```bash
   php hello.php
   ```

Modern PHP rules to remember early:
- Always declare `declare(strict_types=1);` at the top of PHP files for strict type enforcement.
- Use **Constructor Property Promotion** to eliminate boilerplates in OOP classes.
- Use `match` expressions instead of legacy `switch` statements for cleaner, type-safe return values.
- Use PDO prepared statements with parameter binding to prevent SQL injection vulnerabilities.

## Core Language Essentials to Learn Early

- Strict typing with `declare(strict_types=1);`
- Primitive scalar types (`int`, `float`, `string`, `bool`, `array`, `mixed`) and nullable types (`?string`)
- Modern control flow: `if`/`else`, ternary, null coalescing operator (`??`), and `match`
- Object-Oriented PHP 8+: Constructor Property Promotion, `readonly` properties/classes, Enums (`enum`), and Interfaces
- Named arguments (`greet(name: "Alice", greeting: "Hi")`)
- Exception handling (`try`/`catch`/`finally`)
- Prepared PDO statements (`PDO::PREPARE`)

## Must-Learn-Before-Building Checklist

Before building web applications, ensure you can:

- Run PHP files via CLI and launch built-in dev servers (`php -S`)
- Write strictly-typed functions with explicit parameter and return types
- Define modern PHP 8 classes with Constructor Property Promotion and `readonly` properties
- Handle enumerations with `enum`
- Safely query databases using PDO without raw concatenated SQL strings
- Parse and emit JSON using `json_encode()` and `json_decode()`

## Why This Matters

PHP powers over 75% of the web. Modern PHP 8.3/8.4 offers JIT (Just-In-Time) compilation, performance competing with NodeJS/Go, and elegant object-oriented design patterns.

## Suggested Learning Path

- **Chapter 1**: Strict Typing, Variables, and Primitive Syntax
- **Chapter 2**: Control Flow and Modern `match` Expressions
- **Chapter 3**: Functions, Named Parameters, and Arrow Functions
- **Chapter 4**: Object-Oriented Architecture, Enums, and Readonly Classes
- **Chapter 5**: Exception Trapping and Error Handling
- **Chapter 6**: PDO Database Access & REST JSON APIs

## Practice Exercises

1. Write a function with strict types that calculates compound interest.
2. Build an `enum Status: string` with cases `Pending`, `Approved`, `Rejected`.
3. Create a `readonly class Invoice` using PHP 8.3 constructor property promotion.
4. Implement a `match` expression to calculate discounts based on user tier.
5. Create a PDO SQLite helper script that creates a table and inserts records safely.

---

## Textbook Chapters & Runnable Examples

### Chapter 1: Strict Typing, Primitive Variables, and Output

```php
<?php

declare(strict_types=1);

// Scalar Data Types
$language = "PHP";
$version = 8.3;
$isAwesome = true;
$itemCount = 42;

// Null Coalescing Operator (??)
$username = $_GET['user'] ?? 'Guest';

// Constant definitions
define('APP_NAME', 'Modern PHP Textbook');
const TAX_RATE = 0.08;

echo "Welcome to " . APP_NAME . "!" . PHP_EOL;
echo "Running {$language} {$version} | User: {$username}" . PHP_EOL;
```

### Chapter 2: Control Flow and Type-Safe `match` Expressions

```php
<?php

declare(strict_types=1);

$statusCode = 200;

// Modern PHP 8 match expression (returns a value directly)
$responseMessage = match ($statusCode) {
    200, 201 => "Request successful",
    400 => "Bad Request",
    404 => "Resource Not Found",
    500 => "Internal Server Error",
    default => "Unknown status code",
};

echo "HTTP Status {$statusCode}: {$responseMessage}" . PHP_EOL;
```

### Chapter 3: Functions, Named Arguments, and Arrow Functions

```php
<?php

declare(strict_types=1);

// Strictly typed function with default parameters
function calculateTotal(float $price, int $quantity = 1, float $discount = 0.0): float {
    $subtotal = $price * $quantity;
    return $subtotal - ($subtotal * $discount);
}

// Named arguments (passing parameters by name out of order)
$total = calculateTotal(discount: 0.10, price: 100.00, quantity: 2);
echo "Calculated Total: $" . number_format($total, 2) . PHP_EOL;

// Short Arrow Functions (fn)
$numbers = [1, 2, 3, 4, 5];
$squares = array_map(fn(int $n): int => $n * $n, $numbers);
echo "Squares: " . implode(", ", $squares) . PHP_EOL;
```

### Chapter 4: OOP with Constructor Property Promotion and Enums

```php
<?php

declare(strict_types=1);

// Modern PHP 8 Backed Enum
enum UserRole: string {
    case Admin = 'admin';
    case Editor = 'editor';
    case Subscriber = 'subscriber';
}

// Readonly Class with Constructor Property Promotion
readonly class User {
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public UserRole $role = UserRole::Subscriber
    ) {}

    public function getSummary(): string {
        return "User #{$this->id}: {$this->name} ({$this->email}) [Role: {$this->role->value}]";
    }
}

$user = new User(
    id: 101,
    name: "Ada Lovelace",
    email: "ada@php.net",
    role: UserRole::Admin
);

echo $user->getSummary() . PHP_EOL;
```

### Chapter 5: Exception Handling and Custom Exceptions

```php
<?php

declare(strict_types=1);

class InvalidTransactionException extends Exception {}

function withdraw(float $balance, float $amount): float {
    if ($amount > $balance) {
        throw new InvalidTransactionException("Insufficient funds: Cannot withdraw {$amount} from balance {$balance}");
    }
    return $balance - $amount;
}

try {
    $remainingBalance = withdraw(100.0, 150.0);
    echo "New Balance: {$remainingBalance}" . PHP_EOL;
} catch (InvalidTransactionException $e) {
    echo "Transaction Error: " . $e->getMessage() . PHP_EOL;
}
```

### Chapter 6: Database Access with PDO SQLite

```php
<?php

declare(strict_types=1);

try {
    // Create or connect to SQLite database
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table
    $pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, email TEXT)");

    // Prepared statement (prevents SQL injection)
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
    $stmt->execute(['name' => 'John Doe', 'email' => 'john@example.com']);

    // Fetch records
    $query = $pdo->query("SELECT * FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    echo "Database Users:" . PHP_EOL;
    print_r($users);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
}
```

---

## Your First Project: A Lightweight REST JSON API

```php
<?php

declare(strict_types=1);

header("Content-Type: application/json");

$products = [
    ["id" => 1, "name" => "MacBook Pro", "price" => 1999.99],
    ["id" => 2, "name" => "Magic Keyboard", "price" => 99.00],
];

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($uri === '/api/products') {
    echo json_encode(["status" => "success", "data" => $products]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
}
```

---

## Quick Reference & Guidelines for macOS ARM64

### Useful PHP CLI Commands

```bash
# Execute a PHP script
php script.php

# Start a local development web server
php -S localhost:8000

# Check PHP configuration and installed extensions
php -m
```

### Common PHP Mistakes to Avoid

1. **Omitting `declare(strict_types=1);`**: Allows silent type coercion bugs.
2. **Using legacy `switch`**: Prefer type-safe `match` expressions.
3. **Concatenating SQL strings**: Always use PDO prepared statements with bound parameters.
4. **Modifying `readonly` properties**: `readonly` properties can only be initialized once in the constructor.