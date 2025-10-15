# PHP 8.3 – “All‑You‑Need‑to‑Know” Tutorial  
*(plain language, every code block annotated with why it exists, common pitfalls, best‑practice tips and a link to the official PHP manual)*  

---  

## 0️⃣ One‑time installation  

```bash
# macOS (Homebrew)
brew install php               # puts `php` in /usr/local/bin
php -v                         # → PHP 8.3.x (cli)

# Ubuntu / Debian
sudo apt update && sudo apt install php8.3-cli php8.3-common

# Windows
# download the MSI from https://windows.php.net/download/ and run it
```

*❓ **Why** – You need the interpreter before you can run any PHP code.*  
*⚠️ **Pitfall** – PHP’s folder not on `$PATH`; the terminal will say “command not found”.*  
*💡 **Tip** – Verify with `php -v` and keep PHP up‑to‑date via your package manager.*  

📚 **Docs**: <https://www.php.net/manual/en/install.php>  

---  

## 1️⃣ “Hello, world!”  

```php
<?php
// 📚 Docs: https://www.php.net/manual/en/functions.general.php
// ❓ Why: the smallest valid program and the default output function.
// ⚠️ Pitfall: forgetting the opening <?php tag.
// 💡 Tip: `php -r "echo 'Hello, world!\n';"` for a quick one‑liner.
echo 'Hello, world!';
```

---  

## 2️⃣ Variables, constants & mutability  

```php
<?php
declare(strict_types=1);                 // enable strict type checking

$name = 'Bob';          // string, inferred
$age  = 42;             // int

$counter = 0;
$counter++;             // all variables are mutable; you just don’t want them to change unexpectedly

$height = 1.78;         // float; explicit type hint is optional but helps IDEs
const PI = 3.1415926535;

echo "Hi $name, you are $age years old\n";
```

*❓ **Why** – Shows variables, constants and the benefit of strict typing.*  
*⚠️ **Pitfall** – `define()` inside functions creates a global constant, which can be confusing. Use `const` instead.*  
*💡 **Tip** – Add `declare(strict_types=1);` to every file you write; it forces explicit casts and prevents silent coercion.*  

📚 **Docs**: <https://www.php.net/manual/en/language.types.declarations.php>  

---  

## 3️⃣ Numbers – literals, separators, casting, formatting  

```php
<?php
declare(strict_types=1);

// bases
$dec = 123;
$hex = 0x7B;               // 123
$bin = 0b01111011;         // 123
$oct = 0o173;              // 123
assert($dec === $hex && $hex === $bin && $bin === $oct);

// readability
$million = 1_000_000;
echo "million = $million\n";

// explicit casts
$i64   = (int)123;
$float = (float)42;

// floats & scientific notation
$f  = 3.14;
$f2 = 42e1;                // 420.0
$f3 = 1.23e-2;             // 0.0123
echo "f=$f  f2=$f2  f3=$f3\n";

// printf‑style formatting
$x = 123.4567;
printf("[%.2f]\n", $x);                // [123.46]
printf("[%10.2f]\n", $x);              // right‑aligned width 10
printf("[%-10.2f]\n", $x);             // left‑aligned
printf("[%010d]\n", (int)$x);          // zero‑padded
printf("[%b]\n", (int)$x);             // binary
printf("[%X]\n", (int)$x);             // uppercase hex
```

*❓ **Why** – Shows numeric literal forms, the new underscore separator, safe casting, and built‑in formatting.*  
*⚠️ **Pitfall** – Casting a value that won’t fit in an `int` (e.g., on a 32‑bit build) silently overflows. Use `assert($val <= PHP_INT_MAX);` when necessary.*  
*💡 **Tip** – For locale‑aware number formatting, use `number_format()`.*  

📚 **Docs**: <https://www.php.net/manual/en/language.types.numeric.php>  

---  

## 4️⃣ Strings – literals, interpolation, concatenation, common methods  

```php
<?php
declare(strict_types=1);

// literals
$s = 'Hello, PHP!';
echo $s . "\n";

// interpolation (recommended)
$name = 'Alice';
echo "Hi $name, length=" . strlen($name) . "\n";

// concatenation with .
$greeting = 'Hello, ';
$greeting .= $name;                // "Hello, Alice"
echo $greeting . "\n";

// useful built‑ins
$csv   = 'a,b,c,d';
$parts = explode(',', $csv);       // ['a','b','c','d']
print_r($parts);

$txt   = "first row\nsecond row";
$lines = preg_split('/\s+/', $txt); // split on any whitespace
print_r($lines);

echo strrev('Hello V') . "\n";                // V olleH
echo str_replace('a', 'x', 'ababa') . "\n";   // xbxbx
echo preg_replace('/a/', 'x', 'ababa', 1) . "\n"; // xbaba
echo str_repeat('go', 3) . "\n";             // gogogo

// nowdoc – raw string (no interpolation, no escapes)
$raw = <<<'EOT'
c:\temp\new\noline
EOT;
echo $raw . "\n";
```

*❓ **Why** – Shows the most frequent string ops, from interpolation to powerful built‑ins.*  
*⚠️ **Pitfall** – Using `+` for concatenation – PHP treats it as numeric addition and throws a warning.  
*💡 **Tip** – For many pieces inside a loop, build an array and `implode('', $parts)`; it’s faster than repeated `.`.*  

📚 **Docs**: <https://www.php.net/manual/en/language.types.string.php>  

---  

## 5️⃣ Runes – Unicode code‑points  

```php
<?php
declare(strict_types=1);

// emoji (UTF‑8)
$rocket = '🚀';
echo "rocket as string: $rocket\n";

// number of Unicode code‑points (multibyte aware)
$len = mb_strlen($rocket, 'UTF-8');
echo "code‑points = $len\n";

// raw bytes
$bytes = unpack('C*', $rocket);
print_r($bytes);

// split a string into Unicode “runes”
$txt   = 'Hello 👋';
$runes = preg_split('//u', $txt, -1, PREG_SPLIT_NO_EMPTY);
print_r($runes);

// round‑trip
$back = implode('', $runes);
assert($back === $txt);
```

📚 **Docs**: <https://www.php.net/manual/en/book.mbstring.php>  

---  

## 6️⃣ Arrays – creation, indexing, mutation, helpers  

```php
<?php
declare(strict_types=1);

// literal indexed array
$a = [1, 5, 7, 0, 9];
print_r($a);
echo "min=" . min($a) . " max=" . max($a) . "\n";

// mutable, growing array
$nums = [];
$nums[] = 5;                     // push one element
$nums = array_merge($nums, [6, 7]); // push another slice
print_r($nums);                  // [5,6,7]

// delete an element (re‑index)
unset($nums[1]);                 // removes element 6
$nums = array_values($nums);    // re‑index => [0=>5,1=>7]
print_r($nums);

// length
echo "len=" . count($nums) . "\n";

// spread operator (PHP 8.0+)
$b = [8, 9];
$c = [...$nums, ...$b];
print_r($c);
```

*❓ **Why** – Introduces PHP’s versatile array type (both list and map).  
*⚠️ **Pitfall** – `unset` leaves a “hole” in numeric keys; re‑index with `array_values` if you need consecutive keys.  
*💡 **Tip** – Use the **spread operator** (`...`) for clean merging of small arrays.*  

📚 **Docs**: <https://www.php.net/manual/en/language.types.array.php>  

---  

## 7️⃣ Maps (associative arrays) – literals, lookup, mutation, utilities  

```php
<?php
declare(strict_types=1);

// map literal
$scores = [
    'alice' => 12,
    'bob'   => 9,
];
print_r($scores);
echo "alice → {$scores['alice']}\n";

// mutable copy
$mutable = $scores;
$mutable['carol'] = 15;
print_r($mutable);

// invert (value → key) – custom helper
function invert(array $map): array {
    $inv = [];
    foreach ($map as $k => $v) {
        $inv[$v] = $k;          // later keys overwrite earlier ones
    }
    return $inv;
}
print_r(invert($mutable));

// merge (second map overrides)
$merged = array_merge($mutable, ['bob' => 99, 'dave' => 7]);
print_r($merged);
```

*❓ **Why** – Shows the most common map operations: creation, mutation, inversion, merging.  
*⚠️ **Pitfall** – `array_merge` reindexes numeric keys; use `+` when you want to keep left‑hand side keys untouched.  
*💡 **Tip** – The **null‑coalescing operator** (`??`) is perfect for optional entries: `$score = $scores['eve'] ?? 0;`*  

📚 **Docs**: <https://www.php.net/manual/en/language.types.array.php#language.types.array.associative>  

---  

## 8️⃣ Functions – declarations, defaults, variadic, nullable returns  

```php
<?php
declare(strict_types=1);

// simple typed function
function add(int $a, int $b): int {
    return $a + $b;
}
echo add(2, 3) . "\n";

// default argument
function greet(string $name = 'World'): string {
    return "Hi $name!";
}
echo greet() . "\n";

// variadic
function sumAll(int ...$numbers): int {
    return array_sum($numbers);
}
echo sumAll(1, 2, 3, 4) . "\n";

// nullable return (null = none)
function findUser(int $id): ?array {
    $users = [1 => ['id'=>1, 'name'=>'Bob']];
    return $users[$id] ?? null;
}
$user = findUser(0) ?? ['id'=>0, 'name'=>'guest'];
print_r($user);
```

*❓ **Why** – Shows modern function signatures with strict typing, default values, variadics, and nullable returns.  
*⚠️ **Pitfall** – Forgetting `declare(strict_types=1)`; PHP will silently cast `'2'` → `2`.  
*💡 **Tip** – Arrow functions (`fn(int $x): int => $x * 2;`) are ideal for short callbacks.*  

📚 **Docs**: <https://www.php.net/manual/en/functions.user-defined.php>  

---  

## 9️⃣ Classes, objects & property visibility  

```php
<?php
declare(strict_types=1);

class Point {
    public readonly int $x;   // immutable after construction (PHP 8.1+)
    public int $y;            // mutable

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function distance(): float {
        return sqrt($this->x ** 2 + $this->y ** 2);
    }

    public function move(int $dx, int $dy): void {
        // $this->x is readonly → cannot change it
        $this->y += $dy;
    }
}
$pt = new Point(3, 4);
echo $pt->distance() . "\n";   // 5
$pt->move(1, -2);
print_r($pt);
```

*❓ **Why** – Shows the basic building blocks of OOP in PHP.  
*⚠️ **Pitfall** – Trying to assign to a `readonly` property after construction triggers a fatal error.  
*💡 **Tip** – Keep most properties **private** or **protected** and expose them via getters/setters only when you need validation or read‑only access.*  

📚 **Docs**: <https://www.php.net/manual/en/language.oop5.basic.php>  

---  

## 🔟 Inheritance, abstract classes & traits  

```php
<?php
declare(strict_types=1);

abstract class Shape {
    abstract public function area(): float;
}

final class Circle extends Shape {
    public function __construct(public float $radius) {}

    public function area(): float {
        return pi() * $this->radius ** 2;
    }
}

// trait – reusable code chunk
trait Loggable {
    public function log(string $msg): void {
        error_log('[LOG] ' . $msg);
    }
}
class Service {
    use Loggable;
}
(new Service())->log('started');
```

*❓ **Why** – Shows how to share code (`trait`) and enforce a contract (`abstract class`).  
*⚠️ **Pitfall** – Declaring a class `final` when you later need to extend it.  
*💡 **Tip** – Prefer **interfaces** (see next section) for contracts; keep abstract classes for shared implementation.*  

📚 **Docs**: <https://www.php.net/manual/en/language.oop5.inheritance.php>  

---  

## 1️⃣1️⃣ Interfaces (contracts)  

```php
<?php
declare(strict_types=1);

interface Serializer {
    public function serialize(): string;
    public function deserialize(string $data): void;
}

class JsonUser implements Serializer {
    public function __construct(public string $name = '') {}

    public function serialize(): string {
        return json_encode(['name' => $this->name]);
    }

    public function deserialize(string $data): void {
        $arr = json_decode($data, true);
        $this->name = $arr['name'] ?? '';
    }
}

// type‑hinting the interface
function save(Serializer $obj): void {
    // pretend we write to a DB
    echo "Saving: " . $obj->serialize() . "\n";
}
$user = new JsonUser('Ada');
save($user);
```

*❓ **Why** – An interface defines a **contract** that any class can fulfill, enabling loose coupling.  
*⚠️ **Pitfall** – Adding a new method to the interface without updating every implementation causes a fatal error.  
*💡 **Tip** – Keep interfaces **small** (single responsibility) and name methods clearly (`serialize`, `deserialize`).*  

📚 **Docs**: <https://www.php.net/manual/en/language.oop5.interfaces.php>  

---  

## 1️⃣2️⃣ Enums – PHP 8.1+  

```php
<?php
declare(strict_types=1);

enum Color: string {
    case Red   = 'red';
    case Green = 'green';
    case Blue  = 'blue';

    public function isPrimary(): bool {
        return $this === self::Red || $this === self::Blue;
    }
}

$c = Color::Red;
echo $c->value . "\n";               // red
echo $c->isPrimary() ? 'yes' : 'no'; // yes

// safe conversion from string – returns ?self
$maybe = Color::tryFrom('green');    // Color::Green
$none  = Color::tryFrom('purple');   // null
```

*❓ **Why** – Enums give you a **type‑safe list** of possible values, preventing misspellings and invalid data.  
*⚠️ **Pitfall** – Using a plain string constant instead of an enum removes compile‑time checking.  
*💡 **Tip** – Use **backed enums** (`enum Name: string`) when you need to store the value (e.g., in a DB) and plain enums when you only need identifiers.*  

📚 **Docs**: <https://www.php.net/manual/en/language.enumerations.backed.php>  

---  

## 1️⃣3️⃣ Error handling – Exceptions (`try / catch`)  

```php
<?php
declare(strict_types=1);

class NotFoundException extends Exception {}

function findUser(int $id): array {
    $users = [1 => ['id'=>1, 'name'=>'Bob']];
    if (!isset($users[$id])) {
        throw new NotFoundException("User $id not found", 404);
    }
    return $users[$id];
}

try {
    $u = findUser(2);
    print_r($u);
} catch (NotFoundException $e) {
    echo "Error ({$e->getCode()}): {$e->getMessage()}\n";
} catch (Throwable $e) {   // catches any other error
    echo "Unexpected: " . $e->getMessage() . "\n";
}
```

*❓ **Why** – Exceptions separate normal flow from error handling and automatically unwind the stack.  
*⚠️ **Pitfall** – Swallowing all exceptions (`catch (Exception $e)`) can hide real bugs.  
*💡 **Tip** – Throw **specific** exception types and catch the most specific one first.*  

📚 **Docs**: <https://www.php.net/manual/en/language.exceptions.php>  

---  

## 1️⃣4️⃣ Namespaces & Composer (PSR‑4 autoloading)  

```bash
# initialise a Composer project
composer init --name=myapp/todo --require=vlucas/phpdotenv:^5.6 -n
composer require vlucas/phpdotenv
```

```php
<?php
// src/Model/Task.php
namespace App\Model;

class Task {
    public function __construct(
        public int $id,
        public string $title,
        public bool $done = false
    ) {}
}
```

```php
<?php
// public/index.php (front controller)
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\TaskController;

// simple routing based on the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/tasks' && $method === 'GET') {
    (new TaskController())->list();
} elseif ($uri === '/tasks' && $method === 'POST') {
    (new TaskController())->create();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
```

*❓ **Why** – Namespaces avoid name collisions; Composer’s autoloader loads classes automatically (PSR‑4).  
*⚠️ **Pitfall** – Forgetting to run `composer dump-autoload` after adding a new class – the class will not be found.  
*💡 **Tip** – Keep the `src/` folder for your own code, `public/` for the web‑root, and follow the **PSR‑4** mapping in `composer.json`.  

📚 **Docs**: <https://www.php.net/manual/en/language.namespaces.php> & <https://getcomposer.org/doc/01-basic-usage.md>  

---  

## 1️⃣5️⃣ **Single‑Responsibility Principle (SRP) & a tiny MVC example**  

> **SRP** – *A class should have only one reason to change.*  
> In an MVC (Model‑View‑Controller) architecture each component has a single responsibility:  
> * **Model** – represents data and business rules.  
> * **View** – presents data (HTML, JSON, etc.).  
> * **Controller** – orchestrates the request, delegates to the Model, and chooses a View.

### 5.1 Folder layout (PSR‑4)

```
myapp/
├─ composer.json
├─ public/
│   └─ index.php            ← front controller
├─ src/
│   ├─ Model/
│   │   └─ Task.php
│   ├─ Repository/
│   │   └─ TaskRepository.php
│   ├─ Service/
│   │   └─ TaskService.php
│   ├─ Controller/
│   │   └─ TaskController.php
│   └─ View/
│       └─ JsonView.php
└─ v.env                    ← optional environment file
```

### 5.2 Model – only holds data  

```php
<?php
// src/Model/Task.php
namespace App\Model;

class Task {
    public function __construct(
        public int $id,
        public string $title,
        public bool $done = false
    ) {}
}
```

* *Single responsibility*: the class knows **what a task is**, nothing else.

### 5.3 Repository – data‑access concern only  

```php
<?php
// src/Repository/TaskRepository.php
namespace App\Repository;

use App\Model\Task;
use PDO;

class TaskRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /** @return Task[] */
    public function all(): array {
        $stmt = $this->pdo->query('SELECT id, title, done FROM tasks');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Task((int)$r['id'], $r['title'], (bool)$r['done']), $rows);
    }

    public function find(int $id): ?Task {
        $stmt = $this->pdo->prepare('SELECT id, title, done FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Task((int)$row['id'], $row['title'], (bool)$row['done']) : null;
    }

    public function add(Task $task): void {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (title, done) VALUES (:title, :done)');
        $stmt->execute(['title' => $task->title, 'done' => $task->done ? 1 : 0]);
        $task->id = (int)$this->pdo->lastInsertId(); // mutates the model – acceptable because the model is a *data* holder
    }

    public function update(Task $task): void {
        $stmt = $this->pdo->prepare('UPDATE tasks SET title = :title, done = :done WHERE id = :id');
        $stmt->execute(['title' => $task->title, 'done' => $task->done ? 1 : 0, 'id' => $task->id]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
```

* *Single responsibility*: **only** talks to the database. No HTTP, no JSON, no UI logic.

### 5.4 Service – business rules only  

```php
<?php
// src/Service/TaskService.php
namespace App\Service;

use App\Repository\TaskRepository;
use App\Model\Task;

class TaskService {
    private TaskRepository $repo;

    public function __construct(TaskRepository $repo) {
        $this->repo = $repo;
    }

    /** @return Task[] */
    public function listAll(): array {
        return $this->repo->all();
    }

    public function create(string $title): Task {
        $task = new Task(0, $title, false);
        $this->repo->add($task);
        return $task;
    }

    public function toggleDone(int $id): ?Task {
        $task = $this->repo->find($id);
        if ($task) {
            $task->done = !$task->done;
            $this->repo->update($task);
        }
        return $task;
    }

    public function delete(int $id): void {
        $this->repo->delete($id);
    }
}
```

* *Single responsibility*: **encapsulates** the business logic (e.g., toggling a task) without caring about how the data is stored or how the result will be presented.

### 5.5 View – format the response (JSON in this case)  

```php
<?php
// src/View/JsonView.php
namespace App\View;

class JsonView {
    public static function send(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function error(string $msg, int $status = 400): void {
        self::send(['error' => $msg], $status);
    }
}
```

* *Single responsibility*: **only** handles HTTP headers and JSON encoding.

### 5.6 Controller – glue everything together  

```php
<?php
// src/Controller/TaskController.php
namespace App\Controller;

use App\Service\TaskService;
use App\View\JsonView;
use PDO;

class TaskController {
    private TaskService $service;

    public function __construct() {
        // In a real app you would inject the PDO via a DI container.
        $pdo = new PDO('sqlite:' . __DIR__ . '/../../data/tasks.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $repo = new \App\Repository\TaskRepository($pdo);
        $this->service = new TaskService($repo);
    }

    // GET /tasks
    public function list(): void {
        $tasks = $this->service->listAll();
        JsonView::send($tasks);
    }

    // POST /tasks  (expects JSON body { "title": "Buy milk" })
    public function create(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['title'])) {
            JsonView::error('title required', 422);
            return;
        }
        $task = $this->service->create($data['title']);
        JsonView::send($task, 201);
    }

    // PATCH /tasks/{id}  (toggles done flag)
    public function toggle(int $id): void {
        $task = $this->service->toggleDone($id);
        if ($task) {
            JsonView::send($task);
        } else {
            JsonView::error('not found', 404);
        }
    }

    // DELETE /tasks/{id}
    public function delete(int $id): void {
        $this->service->delete($id);
        JsonView::send(null, 204);
    }
}
```

* *Single responsibility*: **only** translates HTTP input into service calls and chooses a view for the output.  

### 5.7 Front controller (routing) – very tiny router  

```php
<?php
// public/index.php
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\TaskController;

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$controller = new TaskController();

if ($uri === '/tasks' && $method === 'GET') {
    $controller->list();
} elseif ($uri === '/tasks' && $method === 'POST') {
    $controller->create();
} elseif (preg_match('#^/tasks/(\d+)$#', $uri, $m) && $method === 'PATCH') {
    $controller->toggle((int)$m[1]);
} elseif (preg_match('#^/tasks/(\d+)$#', $uri, $m) && $method === 'DELETE') {
    $controller->delete((int)$m[1]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'not found']);
}
```

**Why this is SRP‑friendly**  

| Component | Responsibility |
|-----------|-----------------|
| **Model** (`Task`) | Holds the data structure. |
| **Repository** | Pure data‑access (SQL only). |
| **Service** | Business rules (create, toggle, delete). |
| **Controller** | HTTP → Service mapping. |
| **View** (`JsonView`) | Formats data as JSON and sets HTTP status/headers. |
| **Front controller** | Very thin router; no business logic. |

*❓ **Why** – Shows a concrete, minimal MVC implementation that **obeys the Single‑Responsibility Principle**.*  
*⚠️ **Pitfall** – Mixing SQL into the Controller or putting HTML generation inside the Model violates SRP and quickly becomes hard to maintain.*  
*💡 **Tip** – Keep each class *tiny*; when a class grows beyond ~50 lines, ask yourself whether it now does more than one thing and consider extracting a new class.*  

📚 **Docs**:  
* PSR‑4 autoloading – <https://www.php-fig.org/psr/psr-4/>  
* PSR‑12 coding style (helps keep SRP‑friendly files tidy) – <https://www.php-fig.org/psr/psr-12/>  

---  

## 1️⃣6️⃣ Enums – PHP 8.1+ (re‑listed for quick reference)  

*(same code as section 1.12 above – omitted here for brevity; see that section.)*  

---  

## 1️⃣7️⃣ HTTP client – GET, POST, JSON decode  

```php
<?php
declare(strict_types=1);

function fetchRepo(string $owner, string $repo): array {
    $url  = "https://api.github.com/repos/$owner/$repo";
    $opts = [
        "http" => [
            "method"  => "GET",
            "header"  => "User-Agent: my-php-app\r\nAccept: application/vnd.github.v3+json\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $data    = file_get_contents($url, false, $context);
    if ($data === false) {
        throw new RuntimeException("Could not fetch $url");
    }
    return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
}

// usage
try {
    $repo = fetchRepo('vlang', 'v');
    echo "⭐ {$repo['full_name']} has {$repo['stargazers_count']} stars\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

*❓ **Why** – Most apps talk to web APIs; the built‑in `file_get_contents` with a stream context is a zero‑dependency way to make HTTP calls.  
*⚠️ **Pitfall** – Omitting a `User‑Agent` header when querying GitHub causes a 403.  
*💡 **Tip** – Wrap all external calls in a function that returns `!array` (or throws) so callers can decide how to recover.*  

📚 **Docs**: <https://www.php.net/manual/en/function.file-get-contents.php>  

---  

## 1️⃣8️⃣ **REST‑style request handling – GET | POST | PUT | DELETE**  

### 8.1 Plain‑PHP router (no external library)  

```php
<?php
declare(strict_types=1);
header('Content-Type: application/json');

// helper: read JSON body (works for POST, PUT, DELETE)
function json_body(): array {
    $raw = file_get_contents('php://input');
    return $raw ? json_decode($raw, true, 512, JSON_THROW_ON_ERROR) : [];
}

// in‑memory “tasks” collection (for demo only)
static $tasks = [
    1 => ['id'=>1, 'title'=>'Buy milk',  'done'=>false],
    2 => ['id'=>2, 'title'=>'Read book', 'done'=>true],
];

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch (true) {
    // GET /tasks
    case $method === 'GET' && $path === '/tasks':
        echo json_encode(array_values($tasks));
        break;

    // GET /tasks/{id}
    case $method === 'GET' && preg_match('#^/tasks/(\d+)$#', $path, $m):
        $id = (int)$m[1];
        if (isset($tasks[$id])) {
            echo json_encode($tasks[$id]);
        } else {
            http_response_code(404);
            echo json_encode(['error'=>'Task not found']);
        }
        break;

    // POST /tasks   { "title": "…" }
    case $method === 'POST' && $path === '/tasks':
        $data = json_body();
        $newId = max(array_keys($tasks)) + 1;
        $task  = ['id'=>$newId, 'title'=>$data['title'] ?? 'Untitled', 'done'=>false];
        $tasks[$newId] = $task;
        http_response_code(201);
        echo json_encode($task);
        break;

    // PUT /tasks/{id}   { "title":"…", "done":true }
    case $method === 'PUT' && preg_match('#^/tasks/(\d+)$#', $path, $m):
        $id = (int)$m[1];
        if (!isset($tasks[$id])) {
            http_response_code(404);
            echo json_encode(['error'=>'Task not found']);
            break;
        }
        $data = json_body();
        $tasks[$id] = array_merge($tasks[$id], $data);
        echo json_encode($tasks[$id]);
        break;

    // DELETE /tasks/{id}
    case $method === 'DELETE' && preg_match('#^/tasks/(\d+)$#', $path, $m):
        $id = (int)$m[1];
        if (isset($tasks[$id])) {
            unset($tasks[$id]);
            http_response_code(204); // No Content
        } else {
            http_response_code(404);
            echo json_encode(['error'=>'Task not found']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error'=>'Route not found']);
}
```

**How to run it**

```bash
php -S localhost:8000 router.php
```

Now you can issue the four verbs with `curl` (or any HTTP client):

```bash
# list
curl -i http://localhost:8000/tasks

# create
curl -i -X POST -H "Content-Type: application/json" \
     -d '{"title":"Write tutorial"}' http://localhost:8000/tasks

# update (toggle done)
curl -i -X PUT -H "Content-Type: application/json" \
     -d '{"done":true}' http://localhost:8000/tasks/1

# delete
curl -i -X DELETE http://localhost:8000/tasks/2
```

*❓ **Why** – Shows the core of a REST API without any framework.  
*⚠️ **Pitfall** – Forgetting to read the raw request body (`php://input`) – `$_POST` is empty for `PUT`/`DELETE`.  
*💡 **Tip** – Always return a proper HTTP status code (`201` on create, `204` on delete, `404` when not found).  

📚 **Docs**: <https://www.php.net/manual/en/reserved.variables.server.php>  

### 8.2 FastRoute library (still tiny, but cleaner)  

```bash
composer require nikic/fast-route
```

```php
<?php
declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

header('Content-Type: application/json');

// in‑memory tasks (same as above)
static $tasks = [
    1 => ['id'=>1, 'title'=>'Buy milk',  'done'=>false],
    2 => ['id'=>2, 'title'=>'Read book', 'done'=>true],
];

// helper to read JSON body
function json_body(): array {
    $raw = file_get_contents('php://input');
    return $raw ? json_decode($raw, true, 512, JSON_THROW_ON_ERROR) : [];
}

// dispatcher
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET',    '/tasks',          'list');
    $r->addRoute('GET',    '/tasks/{id:\d+}', 'get');
    $r->addRoute('POST',   '/tasks',          'create');
    $r->addRoute('PUT',    '/tasks/{id:\d+}', 'update');
    $r->addRoute('DELETE', '/tasks/{id:\d+}', 'delete');
});

// dispatch
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowed = $routeInfo[1];
        http_response_code(405);
        header('Allow: '.implode(', ', $allowed));
        echo json_encode(['error' => 'Method not allowed']);
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];
        // call a tiny function for each handler
        $handler($vars);
        break;
}

/* ---------- handler functions ---------- */
function list(): void {
    global $tasks;
    echo json_encode(array_values($tasks));
}
function get(array $v): void {
    global $tasks;
    $id = (int)$v['id'];
    if (isset($tasks[$id])) {
        echo json_encode($tasks[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error'=>'Task not found']);
    }
}
function create(): void {
    global $tasks;
    $data   = json_body();
    $newId  = max(array_keys($tasks)) + 1;
    $task   = ['id'=>$newId, 'title'=>$data['title'] ?? 'Untitled', 'done'=>false];
    $tasks[$newId] = $task;
    http_response_code(201);
    echo json_encode($task);
}
function update(array $v): void {
    global $tasks;
    $id = (int)$v['id'];
    if (!isset($tasks[$id])) {
        http_response_code(404);
        echo json_encode(['error'=>'Task not found']);
        return;
    }
    $data = json_body();
    $tasks[$id] = array_merge($tasks[$id], $data);
    echo json_encode($tasks[$id]);
}
function delete(array $v): void {
    global $tasks;
    $id = (int)$v['id'];
    if (isset($tasks[$id])) {
        unset($tasks[$id]);
        http_response_code(204);
    } else {
        http_response_code(404);
        echo json_encode(['error'=>'Task not found']);
    }
}
```

*❓ **Why** – FastRoute gives a **compact routing table** and makes the dispatcher logic clearer.  
*⚠️ **Pitfall** – Forgetting `parse_url(..., PHP_URL_PATH)`; query strings would break the matches.  
*💡 **Tip** – Keep the route‑handler functions tiny and delegate real work to **service classes** (as shown in the MVC section) – that way the router stays framework‑agnostic and testable.*  

📚 **Docs**: <https://github.com/nikic/FastRoute>  

---  

## 1️⃣9️⃣ Sessions & Cookies  

```php
<?php
declare(strict_types=1);
session_start();                     // must be first output‑free line

// store a value
$_SESSION['user'] = ['id'=>1, 'name'=>'Ada'];

// read it later
if (isset($_SESSION['user'])) {
    echo "Logged in as {$_SESSION['user']['name']}\n";
}

// delete a key
unset($_SESSION['flash']);

// destroy the whole session
session_destroy();
```

*❓ **Why** – Sessions let you keep state (e.g., login) across HTTP requests.  
*⚠️ **Pitfall** – Sending any output (`echo`, whitespace) before `session_start()` triggers “Headers already sent”.  
*💡 **Tip** – Put `session_start()` at the very top of every script that needs it, or in a bootstrap file included by all entry points.*  

📚 **Docs**: <https://www.php.net/manual/en/book.session.php>  

---  

## 2️⃣0️⃣ Unit testing & benchmarks (PHPUnit)  

```bash
composer require --dev phpunit/phpunit
```

```php
<?php
// tests/MathTest.php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class MathTest extends TestCase {
    public function testAdd(): void {
        $this->assertSame(5, add(2, 3));
        $this->assertSame(0, add(-1, 1));
    }

    // benchmark style – using the built‑in micro‑bench (PHP 8.2+)
    public function testBenchmarkAdd(): void {
        $start = hrtime(true);
        for ($i = 0; $i < 1_000_000; $i++) {
            add(123, 456);
        }
        $elapsed = (hrtime(true) - $start) / 1_000_000; // µs
        echo "add() took {$elapsed} µs\n";
    }
}
```

Run the suite:

```bash
vendor/bin/phpunit tests
```

*❓ **Why** – Automated tests keep code trustworthy; benchmarks let you compare implementations.  
*⚠️ **Pitfall** – Forgetting the `_Test.php` suffix – PHPUnit ignores files that don’t match the pattern.  
*💡 **Tip** – Keep tests next to the code they test (same folder) and name them `FooTest.php`. Use `assert*` helpers for simple checks; for more complex expectations, use `expect*` from the `phpunit/phpunit` API.  

📚 **Docs**: <https://phpunit.de/manual/current/en/>  

---  

## 2️⃣1️⃣ Asynchronous code – Fibers (PHP 8.1+)  

```php
<?php
declare(strict_types=1);
// 📚 Docs: https://www.php.net/manual/en/class.fiber.php
$fib = new Fiber(function (int $n) {
    for ($i = 0; $i < $n; $i++) {
        echo "Fiber tick $i\n";
        Fiber::suspend();   // give control back to the caller
    }
    return 'done';
});

$fib->start(3);   // prints tick 0
$fib->resume();   // tick 1
$fib->resume();   // tick 2
echo $fib->resume(); // prints 'done'
```

*❓ **Why** – Fibers let you write **non‑blocking** code without callbacks, useful for IO‑heavy servers.  
*⚠️ **Pitfall** – Fibers run in a **single thread**; they don’t give you parallel CPU execution.  
*💡 **Tip** – Pair Fibers with an event loop library (e.g., **ReactPHP**) for real high‑concurrency servers.  

📚 **Docs**: <https://www.php.net/manual/en/class.fiber.php>  

---  

## 2️⃣2️⃣ Logging – Monolog (PSR‑3)  

```bash
composer require monolog/monolog
```

```php
<?php
require __DIR__.'/vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__.'/app.log', Logger::DEBUG));

$log->info('starting up');
$log->warning('low disk space');
$log->error('cannot open config file');   // still runs, does NOT exit (Monolog)

$log->info('user login', ['username' => 'bob', 'ip' => '192.0.2.42']);
```

*❓ **Why** – A lightweight, PSR‑3‑compatible logger works for CLI tools, micro‑services, and larger frameworks.  
*⚠️ **Pitfall** – Forgetting to push at least one handler → nothing gets written.  
*💡 **Tip** – Call `Logger::setLevel(Logger::DEBUG)` (or read the level from an env var) at the start of `main` when you need verbose output during development.  

📚 **Docs**: <https://github.com/Seldaek/monolog>  

---  

## 2️⃣3️⃣ Security basics – input validation, password hashing, CSRF  

```php
<?php
declare(strict_types=1);

// password hashing
$hash = password_hash('secret123', PASSWORD_DEFAULT);
if (password_verify('secret123', $hash)) {
    echo "Password OK\n";
}

// filter input (GET/POST)
$name  = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    die('Invalid email');
}

// simple CSRF token
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        die('CSRF check failed');
    }
}
$_SESSION['csrf'] = bin2hex(random_bytes(32));
```

*❓ **Why** – Prevent common web attacks (SQL‑injection, XSS, CSRF).  
*⚠️ **Pitfall** – Using `addslashes` instead of prepared statements; it does **not** protect against all injections.  
*💡 **Tip** – ALWAYS use **prepared statements** (`PDO::prepare`) for any data that goes to the database, and store passwords only with `password_hash`.  

📚 **Docs**: <https://www.php.net/manual/en/security.php>  

---  

## 2️⃣4️⃣ Composer scripts & environment variables  

```bash
# .env file (never commit!)
DB_HOST=127.0.0.1
DB_USER=dbuser
DB_PASS=secret
```

```php
<?php
// bootstrap.php
require __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname=myapp;charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
```

*❓ **Why** – Keeps secrets out of source control and makes builds reproducible.  
*⚠️ **Pitfall** – Forgetting to add `.env` to `.gitignore` – credentials get leaked.  
*💡 **Tip** – Commit the generated `composer.lock` file; it pins third‑party versions so every developer gets the exact same code.  

📚 **Docs**: <https://github.com/vlucas/phpdotenv>  

---  

## 2️⃣5️⃣ **Quick‑start checklist for absolute beginners**  

1. **Install PHP** and run the “Hello, world!” script.  
2. **Play with `declare(strict_types=1);`** – try assigning a string to an `int` variable and see the compiler error.  
3. **Create an associative array** (`$scores`) and use the **null‑coalescing operator** (`$scores['eve'] ?? 0`).  
4. **Define an enum** (`Color`) and call a method on it (`isPrimary`).  
5. **Write a function that returns `?User`** and handle the `null` case with `??`.  
6. **Convert the same function to return an exception** (`throw new RuntimeException`) and propagate with `try / catch`.  
7. **Build a generic “Stack[T]” class** (section 8) and push/pop different types.  
8. **Spawn a few coroutines** (`go`) or Fibers and communicate via a channel (`chan`).  
9. **Encode a struct to JSON**, write it to a file, read it back and decode.  
10. **Run the MVC example (section 1.5)** – confirm that each class does **one** thing (model, repository, service, controller, view).  

If each step compiles and runs, you have covered the **core of PHP 8.3** and are ready to write production‑grade code! 🎉  

---  

## 2️⃣6️⃣ Clean reference list (exact URLs)

| Topic | Documentation URL |
|-------|-------------------|
| Installation (Homebrew, apt, Windows MSI) | https://www.php.net/manual/en/install.php |
| Variables & constants | https://www.php.net/manual/en/language.types.declarations.php |
| Primitive types (int, float, string, bool, null) | https://www.php.net/manual/en/language.types.php |
| Numbers – literals, separators, casting | https://www.php.net/manual/en/language.types.numeric.php |
| Formatting (`printf`, `sprintf`) | https://www.php.net/manual/en/function.printf.php |
| Strings – methods (`explode`, `implode`, `str_replace`, …) | https://www.php.net/manual/en/language.types.string.php |
| Multibyte strings (`mb_*`) | https://www.php.net/manual/en/book.mbstring.php |
| Arrays – helpers (`min`, `max`, spread `...`) | https://www.php.net/manual/en/language.types.array.php |
| Associative arrays (maps) | https://www.php.net/manual/en/language.types.array.php#language.types.array.associative |
| Functions & variadics | https://www.php.net/manual/en/functions.user-defined.php |
| OOP basics (classes, properties, methods) | https://www.php.net/manual/en/language.oop5.basic.php |
| Inheritance, abstract classes, traits | https://www.php.net/manual/en/language.oop5.inheritance.php |
| Interfaces (contracts) | https://www.php.net/manual/en/language.oop5.interfaces.php |
| Enums (backed & pure) | https://www.php.net/manual/en/language.enumerations.backed.php |
| Exceptions (`try / catch`) | https://www.php.net/manual/en/language.exceptions.php |
| Namespaces & Composer autoloading (PSR‑4) | https://www.php.net/manual/en/language.namespaces.php |
| PSR‑4 (autoloading) | https://www.php-fig.org/psr/psr-4/ |
| PSR‑12 coding style | https://www.php-fig.org/psr/psr-12/ |
| FastRoute (tiny router) | https://github.com/nikic/FastRoute |
| PDO (database abstraction) | https://www.php.net/manual/en/book.pdo.php |
| File I/O (`file_get_contents`, `file_put_contents`) | https://www.php.net/manual/en/book.filesystem.php |
| JSON (`json_encode`, `json_decode`) | https://www.php.net/manual/en/book.json.php |
| HTTP client (stream context) | https://www.php.net/manual/en/function.file-get-contents.php |
| Sessions | https://www.php.net/manual/en/book.session.php |
| PHPUnit (testing) | https://phpunit.de/manual/current/en/ |
| Fibers (async) | https://www.php.net/manual/en/class.fiber.php |
| Monolog (PSR‑3 logger) | https://github.com/Seldaek/monolog |
| Security (filter_input, password_hash) | https://www.php.net/manual/en/security.php |
| dotenv (`vlucas/phpdotenv`) | https://github.com/vlucas/phpdotenv |
| Composer (dependency manager) | https://getcomposer.org/doc/01-basic-usage.md |
| VPM (not applicable to PHP) – ignored | — |

All URLs point to the official PHP 8.3 documentation (or the official repository of the referenced third‑party package).  

---  

**Happy coding!** 🚀  