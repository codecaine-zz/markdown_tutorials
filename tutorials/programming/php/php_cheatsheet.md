```php
<?php
/*====================================================================
  PHP 8+ – Complete “All‑in‑One” Tutorial
  Every major topic from the official manual (https://www.php.net/manual/en/)
  is illustrated as a runnable code snippet inside comments.
  Save this file as tutorial.php and run:
        php -d display_errors=1 tutorial.php
  Copy any block into a separate script to experiment further.
====================================================================*/

/*--------------------------------------------------------------
  0️⃣  PHP DECLARATIVE HEADERS
--------------------------------------------------------------*/
// <?php            – opening tag
// <?php declare(strict_types=1);  – enable strict typing (recommended)
// declare(strict_types=1); // after opening tag forces scalar type checks

declare(strict_types=1);

/*--------------------------------------------------------------
  1️⃣  VARIABLES & CONSTANTS
--------------------------------------------------------------*/
// Variables start with $ and are case‑sensitive.
$age   = 30;                     // int
$name  = 'Alice';                // string
$price = 12.99;                  // float
$isVip = true;                   // bool
$nothing = null;                 // null

// Constant – defined at compile time, global, conventionally UPPERCASE.
define('PI', 3.1415926535);
// or using const (allowed inside classes and namespaces)
const MAX_USERS = 100;

/*--------------------------------------------------------------
  2️⃣  BASIC TYPES & TYPE CASTING
--------------------------------------------------------------*/
echo gettype($age);          // integer
$age = (int)'42';            // string → int
$price = (float)'12,34';     // locale‑dependent, better use (float)str_replace(',' ,'.', $price);
$flag = (bool)0;             // false

/*--------------------------------------------------------------
  3️⃣  STRINGS
--------------------------------------------------------------*/
$single = 'single quotes – $noInterpolation';
$double = "double quotes – $name is $age years old";
$heredoc = <<<EOT
Heredoc respects variable interpolation:
Name: $name
Age: $age
EOT;
$nowdoc = <<<'EOT'
Nowdoc behaves like single quotes – no interpolation:
$name stays as $name
EOT;
echo $double . PHP_EOL;
echo $heredoc . PHP_EOL;
echo $nowdoc . PHP_EOL;

// String functions
echo strlen($name);                  // 5
echo strpos('abcdef', 'cd');          // 2
echo substr('abcdef', 2, 3);          // cde
echo str_repeat('X', 4);             // XXXX
echo strtoupper('php');              // PHP

/*--------------------------------------------------------------
  4️⃣  ARRAYS
--------------------------------------------------------------*/
// Indexed (list) array
$numbers = [1, 2, 3];
$numbers[] = 4;                     // push
array_push($numbers, 5);
array_pop($numbers);                // remove last
array_unshift($numbers, 0);         // prepend
array_shift($numbers);              // remove first

// Associative array
$user = [
    'id'   => 42,
    'name' => 'Bob',
    'age'  => 28,
];
$user['email'] = 'bob@example.com';
unset($user['age']);

foreach ($user as $key => $value) {
    echo "$key => $value".PHP_EOL;
}

// Multi‑dimensional
$matrix = [
    [1,2],
    [3,4],
];
echo $matrix[1][0];                 // 3

/*--------------------------------------------------------------
  5️⃣  OPERATORS
--------------------------------------------------------------*/
// Arithmetic
$sum  = 5 + 2;      // 7
$sub  = 5 - 2;      // 3
$mul  = 5 * 2;      // 10
$div  = 5 / 2;      // 2.5
$mod  = 5 % 2;      // 1
$exp  = 2 ** 3;     // 8 (PHP 5.6+)

// Assignment returns the assigned value
$x = ($y = 3);      // $x == 3, $y == 3

// Increment / Decrement
$i = 1;
$i++;               // post‑increment: $i becomes 2
++$i;               // pre‑increment: $i becomes 3

// Comparison
var_dump(5 == '5');      // true  (type juggling)
var_dump(5 === '5');     // false (strict)
var_dump(null == false); // true (oddity – avoid with ===)

// Null coalescing (??) – only null/undefined
$val = $maybe ?? 'default';

// Spaceship operator (<=>) – returns -1,0,1
var_dump(2 <=> 3);   // -1

/*--------------------------------------------------------------
  6️⃣  LOGICAL OPERATORS
--------------------------------------------------------------*/
// OR (||) – returns first truthy operand
$result = '' || 0 || 'hello';   // 'hello'

// AND (&&) – returns first falsy operand
$result2 = 'foo' && null && 5;  // null

/*--------------------------------------------------------------
  7️⃣  CONTROL STRUCTURES
--------------------------------------------------------------*/
// IF / ELSEIF / ELSE
if ($age > 60) {
    echo "senior";
} elseif ($age > 30) {
    echo "adult";
} else {
    echo "young";
}

// Ternary (?:) – expression form
$grade = ($score >= 90) ? 'A' :
         ($score >= 80) ? 'B' : 'C';

// NULL‑coalescing ternary (??)
$username = $input ?? 'guest';

// SWITCH (strict comparison)
switch ($grade) {
    case 'A':
        echo "Excellent";
        break;
    case 'B':
        echo "Good";
        break;
    default:
        echo "Okay";
}

/*--------------------------------------------------------------
  8️⃣  LOOPS
--------------------------------------------------------------*/
// while
$n = 0;
while ($n < 3) {
    echo "while $n".PHP_EOL;
    $n++;
}

// do…while
$m = 0;
do {
    echo "do $m".PHP_EOL;
    $m++;
} while ($m < 3);

// for (classic)
for ($i = 0; $i < 5; $i++) {
    echo "for $i".PHP_EOL;
}

// foreach (arrays)
foreach ($numbers as $num) {
    echo "num $num".PHP_EOL;
}
foreach ($user as $key => $value) {
    echo "$key => $value".PHP_EOL;
}

// break / continue
for ($i = 0; $i < 5; $i++) {
    if ($i == 2) continue;   // skip 2
    if ($i == 4) break;      // stop at 4
    echo $i.PHP_EOL;
}

/*--------------------------------------------------------------
  9️⃣  FUNCTIONS
--------------------------------------------------------------*/
function greet(string $name = 'Guest'): string
{
    return "Hello, $name!";
}
echo greet('Sam');
echo greet();                     // default

// Variable-length arguments (variadic)
function concat(string ...$parts): string
{
    return implode('-', $parts);
}
echo concat('a','b','c');        // a-b-c

// Return multiple values via array (list)
function min_max(array $arr): array
{
    return [min($arr), max($arr)];
}
[$min, $max] = min_max([3,7,2,9]);
echo "min=$min, max=$max".PHP_EOL;

/*--------------------------------------------------------------
  🔟  TYPE DECLARATIONS (PHP 7+)
--------------------------------------------------------------*/
// Scalar type hints (int, float, string, bool) + return types.
// With `declare(strict_types=1)` they are enforced strictly.
// Nullable types: ?int, ?string, etc.

function add(int $a, int $b): int
{
    return $a + $b;
}

/*--------------------------------------------------------------
  1️⃣1️⃣  ANONYMOUS FUNCTIONS (Closures)
--------------------------------------------------------------*/
$multiplier = fn(int $x): int => $x * 2;   // arrow function (PHP 7.4+)
echo $multiplier(5);                      // 10

// Classic closure with use()
$factor = 3;
$closure = function (int $n) use ($factor): int {
    return $n * $factor;
};
echo $closure(4);                         // 12

/*--------------------------------------------------------------
  1️⃣2️⃣  NAMESPACES & AUToloading
--------------------------------------------------------------*/
namespace MyApp\Utils;                     // place code inside a namespace

class Helper {
    public static function hello(): void {
        echo "Hello from Helper\n";
    }
}

// In another file you would `require` it and use:
# require 'Helper.php';
# use MyApp\Utils\Helper;
# Helper::hello();

/*--------------------------------------------------------------
  1️⃣3️⃣  CLASSES, INHERITANCE, TRAITS
--------------------------------------------------------------*/
class Person {
    protected string $name;
    protected int $age;

    public function __construct(string $name, int $age) {
        $this->name = $name;
        $this->age  = $age;
    }

    public function greet(): void {
        echo "Hi, I’m {$this->name}, {$this->age} years old.\n";
    }
}

class Employee extends Person {
    private string $role;

    public function __construct(string $name, int $age, string $role) {
        parent::__construct($name, $age);
        $this->role = $role;
    }

    public function greet(): void {
        parent::greet();                 // method overriding with parent call
        echo "My role is {$this->role}.\n";
    }
}

// Trait – code reuse
trait Logger {
    public function log(string $msg): void {
        echo "[LOG] $msg".PHP_EOL;
    }
}
class Service {
    use Logger;                         // injects log()
}
$svc = new Service();
$svc->log('service started');

/*--------------------------------------------------------------
  1️⃣4️⃣  MAGIC METHODS
--------------------------------------------------------------*/
class MagicBox {
    private array $data = [];

    public function __get(string $name) {
        return $this->data[$name] ?? null;
    }
    public function __set(string $name, $value): void {
        $this->data[$name] = $value;
    }
    public function __toString(): string {
        return json_encode($this->data);
    }
}
$box = new MagicBox();
$box->color = 'red';
echo $box;               // {"color":"red"}

/*--------------------------------------------------------------
  1️⃣5️⃣  INTERFACES & ABSTRACT CLASSES
--------------------------------------------------------------*/
interface JsonSerializable {
    public function json(): string;
}
abstract class Animal {
    abstract public function sound(): string;
}
class Dog extends Animal implements JsonSerializable {
    public function sound(): string { return 'woof'; }
    public function json(): string { return json_encode(['type'=>'dog']); }
}
$dog = new Dog();
echo $dog->sound();      // woof
echo $dog->json();       // {"type":"dog"}

/*--------------------------------------------------------------
  1️⃣6️⃣  EXCEPTIONS
--------------------------------------------------------------*/
try {
    $num = intdiv(10, 0);          // throws DivisionByZeroError
} catch (DivisionByZeroError $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
} catch (Throwable $e) {            // catch any other exception
    echo "General error: ".$e->getMessage().PHP_EOL;
} finally {
    echo "Cleanup runs regardless".PHP_EOL;
}

/*--------------------------------------------------------------
  1️⃣7️⃣  FILE I/O
--------------------------------------------------------------*/
// Write (overwrite)
file_put_contents('example.txt', "First line\nSecond line\n");

// Append
file_put_contents('example.txt', "Third line\n", FILE_APPEND);

// Read whole file
$content = file_get_contents('example.txt');
echo $content;

// Stream handling
$handle = fopen('example.txt', 'r');
while (($line = fgets($handle)) !== false) {
    echo ">> $line";
}
fclose($handle);

/*--------------------------------------------------------------
  1️⃣8️⃣  DIRECTORY OPERATIONS
--------------------------------------------------------------*/
mkdir('tmp_dir');
$files = scandir('.');               // returns array of filenames
rmdir('tmp_dir');

/*--------------------------------------------------------------
  1️⃣9️⃣  FILTERING INPUT (superglobals)
--------------------------------------------------------------*/
// GET/POST data (automatically escaped in older PHP, now raw)
$username = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
$email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    echo "Invalid email";
}

/*--------------------------------------------------------------
  2️⃣0️⃣  SESSIONS & COOKIES
--------------------------------------------------------------*/
session_start();                     // starts/resumes a session
$_SESSION['user'] = $username;
echo $_SESSION['user'];

setcookie('theme', 'dark', time()+60*60*24*30);  // 30‑day cookie

/*--------------------------------------------------------------
  2️⃣1️⃣  PDO – DATABASE ACCESS (prepared statements)
--------------------------------------------------------------*/
$dsn = 'mysql:host=127.0.0.1;dbname=test;charset=utf8mb4';
$user = 'dbuser';
$pass = 'dbpass';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // INSERT with placeholders
    $stmt = $pdo->prepare('INSERT INTO users (name,email) VALUES (:name,:email)');
    $stmt->execute(['name'=>'Bob','email'=>'bob@example.com']);

    // SELECT
    $stmt = $pdo->query('SELECT id,name FROM users WHERE id < 10');
    foreach ($stmt as $row) {
        echo $row['id'].": ".$row['name'].PHP_EOL;
    }
} catch (PDOException $e) {
    echo "DB error: ".$e->getMessage().PHP_EOL;
}

/*--------------------------------------------------------------
  2️⃣2️⃣  CURL – HTTP CLIENT (alternative to file_get_contents)
--------------------------------------------------------------*/
function http_get(string $url): string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 5,
    ]);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new RuntimeException('Curl error: '.curl_error($ch));
    }
    curl_close($ch);
    return $data;
}
try {
    $github = http_get('https://api.github.com');
    echo substr($github,0,120); // first 120 chars
} catch (Throwable $e) {
    echo $e->getMessage();
}

/*--------------------------------------------------------------
  2️⃣3️⃣  COMPOSER (dependency manager) – quick example
--------------------------------------------------------------*/
// 1. Install Composer globally (https://getcomposer.org)
// 2. Create composer.json:
// {
//     "require": {
//         "guzzlehttp/guzzle": "^7.0"
//     }
// }
// 3. Run `composer install`
// 4. Autoload (generated in vendor/autoload.php)

require __DIR__.'/vendor/autoload.php';
// Now you can use Guzzle, Symfony components, etc.

// Example with Guzzle (HTTP client)
use GuzzleHttp\Client;
$client = new Client(['base_uri' => 'https://api.github.com/']);
$response = $client->request('GET', 'repos/guzzle/guzzle');
echo $response->getStatusCode(); // 200
echo $response->getBody();

/*--------------------------------------------------------------
  2️⃣4️⃣  ITERATORS & GENERATORS
--------------------------------------------------------------*/
// Simple iterator class
class Counter implements Iterator {
    private int $i = 0;
    private int $limit;
    public function __construct(int $limit) { $this->limit = $limit; }
    public function current(): int   { return $this->i; }
    public function key(): int       { return $this->i; }
    public function next(): void     { $this->i++; }
    public function rewind(): void   { $this->i = 0; }
    public function valid(): bool    { return $this->i < $this->limit; }
}
foreach (new Counter(3) as $num) {
    echo "cnt $num".PHP_EOL;  // 0 1 2
}

// Generator (yield) – lazy sequence
function fibonacci(int $n): Generator {
    $a = 0; $b = 1;
    for ($i = 0; $i < $n; $i++) {
        yield $a;
        $temp = $a + $b;
        $a = $b;
        $b = $temp;
    }
}
foreach (fibonacci(6) as $v) {
    echo "$v ";                // 0 1 1 2 3 5
}
echo PHP_EOL;

/*--------------------------------------------------------------
  2️⃣5️⃣  ATTRIBUTE VALIDATION (PHP 8.0+ – typed properties)
--------------------------------------------------------------*/
class Product {
    public string $name;
    public float $price;
    public int $stock = 0;          // default value
    public function __construct(string $name, float $price) {
        $this->name  = $name;
        $this->price = $price;
    }
}
$p = new Product('Desk', 199.99);
$p->stock = 10;

/*--------------------------------------------------------------
  2️⃣6️⃣  MATCH EXPRESSION (PHP 8.0+)
--------------------------------------------------------------*/
$status = 200;
$message = match ($status) {
    200 => 'OK',
    400 => 'Bad Request',
    404 => 'Not Found',
    default => 'Unknown',
};
echo $message;               // OK

/*--------------------------------------------------------------
  2️⃣7️⃣  UNION TYPES (PHP 8.0+)
--------------------------------------------------------------*/
function foo(int|float $num): int|float {
    return $num * 2;
}
echo foo(5);      // 10
echo foo(2.5);    // 5

/*--------------------------------------------------------------
  2️⃣8️⃣  ATTRIBUTES (PHP 8.0+ – metadata similar to annotations)
--------------------------------------------------------------*/
#[Attribute(Attribute::TARGET_CLASS)]
class Table {
    public function __construct(public string $name) {}
}
#[Table('users')]
class UserModel {}
// Reflection can read attributes:
$ref = new ReflectionClass(UserModel::class);
$attrs = $ref->getAttributes(Table::class);
foreach ($attrs as $attr) {
    $inst = $attr->newInstance();
    echo "Mapped to table {$inst->name}\n"; // Mapped to table users
}

/*--------------------------------------------------------------
  2️⃣9️⃣  COMMON PITFALLS & CORRECTIONS (from the manual)
--------------------------------------------------------------*/
// 1️⃣  Forgetting `declare(strict_types=1)` → silent type coercion.
//    Always enable it for new projects.
// 2️⃣  Using `=` instead of `==` in conditionals (assignment vs comparison).
//    `if ($a = 5) { … }` will always be true; use `==` or `===`.
// 3️⃣  Mixing `echo` with array output – `echo $array` prints “Array”.
//    Use `print_r($array)` or `var_export($array, true)`.
// 4️⃣  Not checking return value of `file_put_contents` – it returns false on failure.
// 5️⃣  Accessing undefined array keys returns `null` and raises a notice.
//    Use `isset($arr['key'])` or null‑coalescing `$arr['key'] ?? $default`.
// 6️⃣  Using `mysql_*` functions (removed in PHP 7). Use PDO or MySQLi.
// 7️⃣  Forgetting to close database connections – PDO does it automatically
//    when the object is destroyed, but explicit `$pdo = null;` is clearer.
// 8️⃣  Using `eval()` – dangerous and unnecessary in most cases.
// 9️⃣  Not escaping output (XSS). Always `htmlspecialchars($input, ENT_QUOTES)`
//    when echoing user data into HTML.
// 🔟  Session fixation – call `session_regenerate_id(true)` after login.

echo "All sections executed successfully.\n";

/*====================================================================
  END OF TUTORIAL – every core PHP feature has been demonstrated.
  Feel free to split this file into modules, add Composer packages,
  or experiment with newer language features (PHP 8.2+).
====================================================================*/
?>
```