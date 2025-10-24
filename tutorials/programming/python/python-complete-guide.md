# 📚 **Python 3.14 – The Modern Python Tutorial**  
*(A “JavaScript‑info‑style” learning path, now written with **very detailed comments and plain‑English notes** so that even people who have never programmed can follow along.)*  

---  

## 📖 Table of Contents  

| # | Chapter | What you’ll learn | Quick‑run link |
|---|---------|-------------------|----------------|
| **0** | **Getting started** | Install Python, open the REPL, run a script, choose an editor | `▶︎` |
| **1** | **Python fundamentals** | Hello‑world, comments, code layout, scripts vs modules | `▶︎` |
| **2** | **Variables & data types** | Numbers, strings, booleans, `None`, type hints, mutability | `▶︎` |
| **3** | **Operators** | Arithmetic, bitwise, comparison, identity, membership, augmented assignment | `▶︎` |
| **4** | **Control flow** | `if/elif/else`, ternary, loops, `break/continue/pass`, `for‑else` | `▶︎` |
| **5** | **Functions** | Definition, defaults, `*args/**kwargs`, returns, recursion, closures, lambdas | `▶︎` |
| **6** | **Scope & closures** | LEGB rule, `global`, `nonlocal`, inner‑function factories | `▶︎` |
| **7** | **Modules & packages** | Import styles, `__all__`, create a package, virtual environments | `▶︎` |
| **8** | **Standard‑library essentials** | `sys`, `os`/`pathlib`, `json`, `datetime`, `logging`, `subprocess` | `▶︎` |
| **9** | **Core data structures** | List, tuple, dict, set, comprehensions, copying, frozen containers | `▶︎` |
| **10** | **Iterators & generators** | `iter()`, `next()`, generator functions, `yield from`, `send()` | `▶︎` |
| **11** | **Advanced sequence handling** | `enumerate`, `zip`, sorting, `itertools` recipes | `▶︎` |
| **12** | **Error handling** | `try/except/else/finally`, custom exceptions, chaining | `▶︎` |
| **13** | **File I/O** | Text & binary files, `Path`, CSV, JSON streaming | `▶︎` |
| **14** | **Object‑oriented programming** | Classes, `__init__`, attributes, `@property`, `__slots__` | `▶︎` |
| **15** | **Inheritance & polymorphism** | MRO, multiple inheritance, ABCs | `▶︎` |
| **16** | **Special (dunder) methods** | `__str__`, `__repr__`, container protocol, arithmetic overload | `▶︎` |
| **17** | **Dataclasses & attrs** | `@dataclass`, frozen dataclasses, `attrs` library | `▶︎` |
| **18** | **Static typing** | `typing` primitives, generics, protocols, `TypedDict`, `Literal` | `▶︎` |
| **19** | **Structural pattern matching** (PEP 634‑636, Python 3.10+) | `match` / `case`, guards, deconstruction, class patterns | `▶︎` |
| **20** | **Enhanced pattern matching – Python 3.14** | Wildcard precedence, value‑pattern ordering, byte‑matching, `CaseGuardError` | `▶︎` |
| **21** | **Decorators** | Simple, parametrised, class decorators, cached property example | `▶︎` |
| **22** | **Context managers** | `with` statement, manual class, `contextlib`, async version | `▶︎` |
| **23** | **Concurrency** | `threading`, `multiprocessing`, `concurrent.futures`, thread‑safe queues | `▶︎` |
| **24** | **Async IO** | `async def`, `await`, async generators, cancellation, timeout | `▶︎` |
| **25** | **Testing & debugging** | `unittest`, `pytest`, `doctest`, `pdb`, VS Code debugging | `▶︎` |
| **26** | **Packaging & distribution** | `pyproject.toml`, building wheels, publishing, `poetry`/`pipenv` | `▶︎` |
| **27** | **Performance tips** | Profiling, caching, vectorisation (`numpy`), Cython/Numba, PyPy | `▶︎` |
| **28** | **Best practices & style** | `black`, `ruff`, docstrings, type checking, CI, pre‑commit | `▶︎` |
| **29** | **Where to go next** | Web, data‑science, ML, automation, embedded, CPython contribution | `▶︎` |

> **Every chapter** contains a short explanation in plain English, **multiple runnable code snippets** (each line is commented), and a **“Try it yourself”** exercise you can copy‑paste into a terminal or an online notebook (e.g. [replit.com](https://replit.com/)).  

---  

## 0️⃣ Getting Started  

### 0.1 Install Python 3.14  

| OS | What you do (plain language) | Command you type |
|----|-----------------------------|-----------------|
| **Windows** | Download the official installer from the Python website, double‑click it, and **tick the box that says “Add Python to PATH”** (so you can run `python` from any command prompt). | — |
| **macOS** | If you have **Homebrew** (a package manager), run the command below; otherwise download the macOS installer from the website. | `brew install python@3.14` |
| **Linux** | Most Linux distros still ship 3.12‑3.13. Use **pyenv** (a tool that lets you install any Python version). | `curl https://pyenv.run | bash` <br>`pyenv install 3.14.0` <br>`pyenv global 3.14.0` |
| **All** | Verify the installation by asking the interpreter for its version. | `python3 --version` → should output `Python 3.14.x` |

### 0.2 REPL & running a script  

*REPL* = **R**ead‑**E**val‑**P**rint **L**oop – an interactive prompt where you can type Python statements one‑by‑one and see the result instantly.

```bash
$ python3           # start the REPL
>>> 2 ** 10
1024                # the interpreter printed the result
>>> exit()         # leave the REPL
```

A **script** is just a plain text file that ends with `.py`.  
You run it by giving its name to the interpreter:

```python
# hello.py  (this file contains exactly the two lines below)
print("👋 Hello, Python 3.14!")
```

```bash
$ python3 hello.py
👋 Hello, Python 3.14!
```

> **Why scripts?** They let you write a *program* that can be executed again and again, without having to re‑type everything in the REPL.

### 0.3 Choosing an editor / IDE  

| Editor | Why it is friendly for beginners | One‑line install tip |
|--------|---------------------------------|----------------------|
| **VS Code** + *Python* extension | Syntax highlighting, autocomplete, built‑in terminal, *run code* button, Jupyter notebook support. | `code .` after installing from <https://code.visualstudio.com/> |
| **PyCharm Community** | Powerful debugger, project view, virtual‑env creation wizard. | Download from <https://www.jetbrains.com/pycharm/> |
| **IDLE** (bundled with Python) | Zero configuration, good for the first “Hello world”. | Already installed – just run `idle` |
| **Neovim** + `coc.nvim` | Light‑weight, works over SSH, good for remote servers. | `brew install neovim` (mac) or `sudo apt install neovim` (linux) |

---  

## 1️⃣ Python Fundamentals  

### 1.1 Hello‑world & script layout  

```python
# hello_world.py
"""Top‑level documentation for the module.

Running this file prints a friendly greeting.
"""
def main() -> None:                 # “def” creates a function named main
    """Entry point – the code that runs when the script is executed."""
    print("👋 Hello, Python 3.14!")   # print sends text to the terminal

# The following line is a *guard*.
# It makes sure that main() runs **only** when this file is executed directly,
# and NOT when another file does “import hello_world”.
if __name__ == "__main__":          
    main()
```

*Plain‑English notes*  

* The first triple‑quoted string is a **module docstring** – a description that tools like `help()` can show.  
* `def main() -> None:` defines a function that **does not return** anything (`None`). The `-> None` part is optional, it’s just a **type hint** (more on that later).  
* `if __name__ == "__main__":` is a common idiom: when Python loads a file as a *script* it sets the special variable `__name__` to `"__main__"`. When the file is *imported* as a module, `__name__` is the module’s actual name, so the guard prevents the code from running unintentionally.  

### 1.2 Comments  

```python
# This is a single‑line comment – the interpreter completely ignores it.

"""
A multi‑line comment (actually a *string* that isn’t bound to any name).
Python discards it at runtime, so it works as a comment.
"""
```

*Why comment?* To explain *why* you wrote something, not *what* the code does (the code itself should be clear enough for the *what*).

### 1.3 Code layout (PEP 8)  

PEP 8 is the **style guide** for Python code. Following it makes code easy to read for everyone.

| Rule | Example (what you should write) | Explanation |
|------|--------------------------------|-------------|
| **Indentation** – 4 spaces, never tabs | ```python\nif ok:\n    print("yes")\n``` | Python uses indentation to define **blocks** (like the body of an `if`). |
| **Maximum line length** – 88 characters (the default of `black`) | Keep each line short enough to fit on a typical screen. |
| **Blank lines** – separate top‑level definitions with **two** blank lines | Improves visual separation between functions/classes. |
| **Naming** – `snake_case` for functions/variables, `CamelCase` for classes. | Helps readers instantly recognise a class vs a function. |

### 1.4 Quick‑run exercise  

> **Goal:** Print the version of Python you are running *and* the name of the operating system (Windows, macOS, Linux).  

```python
import sys, platform      # import two standard‑library modules

# sys.version gives the full version string; we split to get just the number
py_version = sys.version.split()[0]

# platform.system() returns a short OS name
os_name = platform.system()

print(f"Python {py_version} on {os_name}")
```

*Copy the code above into a file `info.py` and run `python3 info.py`. You should see something like* `Python 3.14.0 on Linux`.  

---  

## 2️⃣ Variables & Data Types  

### 2.1 Primitive types (numbers, strings, booleans, None)  

```python
# -------- Numbers -------------------------------------------------
integer = 42                     # an integer – whole numbers, no decimal point
floating = 3.14                  # a float – real numbers that have a fraction
complex_num = 2 + 3j             # a complex number (real + imag*j), rarely needed

# -------- Strings -------------------------------------------------
# Strings are Unicode text. They can be single‑ or double‑quoted.
single = 'single‑quoted'
double = "double‑quoted"
triple = """This is a
multi‑line string.
It can span several lines."""
print(triple)                    # prints exactly as written, including newlines

# -------- Booleans ------------------------------------------------
flag = True                      # True or False (capitalised!)
print(flag == 1)                 # True – bool is a subclass of int (True == 1)

# -------- None ----------------------------------------------------
nothing = None                   # the *absence* of a value; often used as a default
print(nothing is None)           # True
```

### 2.2 Dynamic typing + optional type hints  

```python
def greet(name: str, times: int = 1) -> str:
    """
    Return a greeting that repeats *times*.
    The part after ':' (e.g. `str`, `int`) are **type hints**.
    They do *not* enforce types at runtime, but help IDEs and static
    checkers (like `mypy`) catch mistakes before you run the program.
    """
    return ("Hello, " + name + "! ") * times

print(greet("Bob"))                # default times=1
print(greet("Bob", 3))             # three repetitions
```

If you run the code with a type checker:

```bash
$ pip install mypy
$ mypy myfile.py
Success: no issues found in 1 source file
```

> **Note for non‑programmers:** Think of a *type hint* like a *label* on a box that says “contains integers”. The interpreter still lets you put something else in the box, but a *linter* (a tool that reads your code) will warn you if the label and the content don’t match.

### 2.3 Mutability vs immutability  

* **Mutable** objects can change their contents after they have been created (e.g. `list`, `dict`).  
* **Immutable** objects cannot be changed (e.g. `int`, `float`, `tuple`, `str`).  

```python
# mutable example
my_list = [1, 2, 3]
my_list.append(4)          # list now holds [1,2,3,4]

# immutable example
my_tuple = (1, 2, 3)
# my_tuple[0] = 9          # ❌ TypeError – tuples cannot be changed
```

### 2.4 Quick‑run exercise  

> **Goal:** Build a dictionary that maps the first three planets to their average distance from the Sun (in millions of km). Then retrieve the distance of **Mars** using a *formatted* string that shows exactly two decimal places.

```python
# Step 1 – create the mapping
planet_distance = {
    "Mercury": 57.9,
    "Venus":   108.2,
    "Mars":    227.9,
}

# Step 2 – look up Mars and format the output
print(f"Mars is {planet_distance['Mars']:.2f} million km from the Sun.")
# Expected output: Mars is 227.90 million km from the Sun.
```

---  

## 3️⃣ Operators  

| Category | Operators | What they do (plain language) | Example |
|----------|-----------|------------------------------|---------|
| **Arithmetic** | `+ - * / // % **` | Add, subtract, multiply, true division, floor division, remainder, exponentiation | `7 // 3   # → 2` |
| **Bitwise** | `& | ^ ~ << >>` | Work on individual bits (binary representation) – rarely needed unless you handle low‑level data. | `0b1010 & 0b1100   # → 0b1000` |
| **Comparison** | `== != < > <= >=` | Test equality or ordering. | `5 < 10   # → True` |
| **Identity** | `is`, `is not` | Test whether two names refer to the *exact same object* in memory. | `x is None` |
| **Membership** | `in`, `not in` | Test whether an item is inside a container (list, set, string). | `'a' in 'abc'` |
| **Assignment** | `= += -= *= …` | Store a value (`=`) or modify a variable in place (`+=`, `-=`). | `counter += 1` |

### 3.1 Operator precedence (order of evaluation)  

Think of precedence as the **rules of arithmetic** you learned in school: multiplication happens before addition, parentheses override everything. Python follows similar rules; you can always use parentheses `()` to be explicit.

```python
result = 2 + 3 * 4          # multiplication first → 2 + 12 = 14
result = (2 + 3) * 4        # parentheses first → 5 * 4 = 20
```

### 3.2 Quick‑run exercise  

> **Goal:** Compute the *bitwise complement* of the binary number `0101` (that is `0b0101`) and then right‑shift the result by two places. Print the final result in binary, padded to 8 bits for readability.

```python
x = 0b0101                     # binary literal for 5
complement = ~x                # bitwise NOT – flips every bit, gives -6 in two's complement
shifted = complement >> 2      # arithmetic right shift (fills with sign bit)

# format as binary, show 8 bits (leading zeros)
print(f"{shifted & 0xff:08b}")   # mask with 0xff to drop negative sign bits → 11111101
```

> **Explanation:**  
> * `~x` flips all bits; because Python integers have infinite length, the result is a negative number (`-6`).  
> * Shifting a negative number right keeps the sign (`1`), so we mask with `0xff` to see only the low 8 bits.

---  

## 4️⃣ Control Flow  

### 4.1 Conditional statements (`if/elif/else`)  

```python
temperature = 23

if temperature > 30:
    print("🥵 It's hot!")
elif temperature > 15:
    print("🌤️ Nice weather.")
else:
    print("❄️ It's cold.")
```

*Plain English:*  
*If the temperature is above 30, we say it’s hot; otherwise, if it’s above 15 we say it’s nice; if neither of those conditions is true we fall back to “cold”.*  

### 4.2 Ternary (inline) expression  

```python
age = 17
status = "adult" if age >= 18 else "minor"
print(status)                 # → minor
```

*Why use it?* When you need a simple *value* that depends on a condition, the ternary keeps the code on one line and stays readable.

### 4.3 Loops  

```python
# ----- while loop (repeat while a condition is true) -----
i = 0
while i < 5:                  # keep looping while i is less than 5
    print(i)                  # print the current value
    i += 1                    # ++ (increase i)

# ----- for loop (iterate over any iterable) -----
for ch in "Python":           # each character of the string becomes ch
    print(ch.upper())         # print the uppercase version
```

### 4.4 `break`, `continue`, `pass`  

* `break` – exit the loop immediately.  
* `continue` – skip the rest of the current iteration and start the next one.  
* `pass` – do nothing; a placeholder when a statement is required syntactically.

```python
for n in range(10):
    if n == 5:
        break               # stop the loop as soon as we reach 5
    if n % 2 == 0:
        continue            # skip even numbers
    print(n)                # prints 1,3
```

### 4.5 `for … else` – “no‑break” clause  

```python
for i in range(3):
    if i == 5:
        break               # never executed
else:                        # runs because the loop finished *without* break
    print("Loop completed normally.")
```

*Use‑case:* Search loops – you can put the “not‑found” handling in the `else` block.

### 4.6 Quick‑run exercise  

> **Goal:** Print the numbers from 1 to 100, but stop as soon as you encounter a **multiple of 17**. If you never hit a multiple of 17, print “No multiple of 17 found”.  

```python
for n in range(1, 101):
    if n % 17 == 0:
        print(f"Stopped at {n}")
        break                # exit the loop early
else:                         # only runs if the loop wasn’t broken
    print("No multiple of 17 found")
```

---  

## 5️⃣ Functions  

### 5.1 Defining a function  

```python
def factorial(n: int) -> int:
    """Return n! (the product of all positive integers ≤ n)."""
    if n <= 1:
        return 1
    return n * factorial(n - 1)   # recursion – the function calls itself
```

*Notes:*  

* `def` starts a function definition.  
* The part after the name, `n: int`, is a **parameter** with a *type hint* (`int`).  
* `-> int` indicates the function *returns* an integer.  
* The triple‑quoted string right after the header is the **docstring** – a built‑in description that `help(factorial)` will show.  

### 5.2 Default arguments & keyword arguments  

```python
def greet(name: str = "World", *, excited: bool = False) -> str:
    """
    Return a greeting.
    * `name` has a default value, so you can omit it.
    * `*` forces any following arguments (`excited`) to be **keyword‑only**.
    """
    punctuation = "!!!" if excited else "."
    return f"Hello, {name}{punctuation}"

print(greet())                         # uses default name, not excited
print(greet("Bob", excited=True))      # explicit keywords
```

### 5.3 Variable‑length arguments (`*args`, `**kwargs`)  

```python
def concatenate(*parts: str, sep: str = " ") -> str:
    """
    Join an arbitrary number of strings with a separator.
    * `*parts` collects all positional arguments into a tuple.
    * `sep` is a normal named argument with a default.
    """
    return sep.join(parts)

print(concatenate("one", "two", "three", sep="-"))
# → one-two-three
```

```python
def printer(**settings):
    """
    Accept any number of named options.
    **settings becomes a dictionary mapping the argument name → value.
    """
    for key, value in settings.items():
        print(f"{key} = {value}")

printer(color="red", bold=True)
# color = red
# bold = True
```

### 5.4 Returning multiple values (tuple unpacking)  

```python
def min_max(seq):
    """Return the smallest and largest items of *seq*."""
    return min(seq), max(seq)   # returns a **tuple** (two values)

low, high = min_max([7, 2, 5, 9])
print(f"low={low}, high={high}")
# low=2, high=9
```

### 5.5 Closures – functions that remember their environment  

```python
def make_multiplier(factor):
    """Factory that creates a function multiplying by *factor*."""
    def multiplier(x):
        return x * factor        # `factor` is remembered from the outer scope
    return multiplier           # return the inner function itself

double = make_multiplier(2)      # double(x) = x*2
triple = make_multiplier(3)      # triple(x) = x*3
print(double(5), triple(5))     # → 10 15
```

### 5.6 Lambdas – tiny anonymous functions  

```python
squared = lambda x: x * x       # same as: def squared(x): return x*x
print(squared(6))               # 36
```

*When to use?* For short, throw‑away functions (e.g., as arguments to `sorted` or `map`).  

### 5.7 Quick‑run exercise  

> **Goal:** Write a function `top_n(lst, n=3)` that returns the **n largest unique** numbers from a list, sorted from largest to smallest. Use a **set** to remove duplicates, then `sorted`.  

```python
def top_n(lst, n=3):
    """Return the n biggest distinct values from *lst*."""
    unique = set(lst)                     # remove duplicates
    return sorted(unique, reverse=True)[:n]

print(top_n([4, 1, 7, 7, 2, 5], 4))
# → [7, 5, 4, 2]
```

---  

## 6️⃣ Scope & Closures  

### 6.1 LEGB rule (where Python looks for a name)  

* **L**ocal – inside the current function.  
* **E**nclosing – inside any outer functions.  
* **G**lobal – at the top level of the module (file).  
* **B**uilt‑ins – the standard names Python always provides (`len`, `print`, …).

```python
x = "global"

def outer():
    x = "enclosing"
    def inner():
        print(x)      # finds the *enclosing* x, not the global one
    inner()

outer()               # prints: enclosing
```

### 6.2 `global` keyword  

```python
counter = 0

def inc():
    global counter   # tells Python we want the *module‑level* counter
    counter += 1

inc()
print(counter)        # 1
```

### 6.3 `nonlocal` keyword (for nested functions)  

```python
def make_counter():
    cnt = 0
    def next_one():
        nonlocal cnt   # refer to the variable in the *enclosing* scope
        cnt += 1
        return cnt
    return next_one

c = make_counter()
print(c(), c())       # 1 2
```

### 6.4 Quick‑run exercise  

> **Goal:** Build a **memoizer** decorator that stores the results of a function in a dictionary so that repeated calls with the same arguments return instantly.  

```python
def memoize(func):
    cache = {}                         # empty dict that lives for the life of the wrapper
    def wrapper(*args):
        if args not in cache:          # first time we see these arguments?
            cache[args] = func(*args)  # compute and remember
        return cache[args]             # return the stored result
    return wrapper

@memoize
def fib(n):
    """Naïve recursive Fibonacci – now fast thanks to memoization."""
    if n < 2:
        return n
    return fib(n-1) + fib(n-2)

print(fib(35))      # computed once, then cached for any later calls
```

---  

## 7️⃣ Modules & Packages  

### 7.1 Import statements – three common ways  

```python
import math               # use as: math.sqrt(4)

from math import sqrt    # you can now call sqrt(4) directly

from math import *        # imports *all* public names (discouraged, pollutes namespace)
```

*Why not use `from … import *`?* Because it makes it hard for readers (and tools) to know where a name came from.

### 7.2 Controlling what a package exports (`__all__`)  

```python
# util/__init__.py
__all__ = ["square", "cube"]   # only these two names are exported on "from util import *"

def square(x): return x*x
def cube(x):   return x*x*x
def secret():  return "shhh"
```

### 7.3 Creating a package (folder layout)  

```
my_pkg/
│─ pyproject.toml          # metadata (see Chapter 26)
│─ my_pkg/
│   ├─ __init__.py        # makes it a package
│   ├─ core.py
│   └─ utils/
│       ├─ __init__.py
│       └─ math_helpers.py
│─ tests/
│   └─ test_core.py
```

*Each sub‑folder that contains an `__init__.py` is a **package**; modules are plain `.py` files.*

### 7.4 Virtual environments – isolated Python installations  

```bash
# create a new environment in the folder ".venv"
$ python3 -m venv .venv

# activate it (Unix/macOS)
$ source .venv/bin/activate

# activate it (Windows PowerShell)
> .venv\Scripts\Activate.ps1

# now `pip install …` will install packages only inside this environment
```

### 7.5 Quick‑run exercise  

> **Goal:** Build a package called `greetings` with a module `english.py` that defines `hello(name)`. Import it from a script `run.py` and call the function.  

**Folder layout**

```
greetings/
│─ greetings/
│   ├─ __init__.py
│   └─ english.py
│─ run.py
```

**`greetings/english.py`**

```python
def hello(name: str) -> str:
    """Return a friendly greeting."""
    return f"Hello, {name}!"
```

**`run.py`**

```python
from greetings.english import hello

print(hello("World"))   # → Hello, World!
```

Run with `python run.py`.  

---  

## 8️⃣ Standard‑Library Essentials  

The **standard library** is a huge collection of ready‑made modules that ship with every Python installation. Below are the most frequently used ones for beginners.

| Module | What it does (plain language) | One‑liner example |
|--------|------------------------------|-------------------|
| `sys` | Access interpreter information, command‑line arguments, exit codes. | `print(sys.argv)` |
| `os` / `pathlib` | Work with files, directories, environment variables. | `pathlib.Path("data").mkdir(parents=True, exist_ok=True)` |
| `json` | Convert between Python data structures and JSON text (used a lot in web APIs). | `json.dumps({"a":1})` |
| `datetime` | Manipulate dates, times, time‑zones. | `datetime.datetime.now()` |
| `logging` | Flexible, configurable logging (instead of `print`). | `logging.info("started")` |
| `subprocess` | Run external programs from Python. | `subprocess.run(["ls","-l"], capture_output=True)` |
| `statistics` | Simple statistical functions (mean, median, stdev). | `statistics.mean([1,2,3])` |
| `hashlib` | Compute cryptographic hashes (e.g., SHA‑256). | `hashlib.sha256(b"data").hexdigest()` |
| `enum` | Create enumerated constants (named sets of values). | `class Color(enum.Enum): RED=1` |
| `urllib` | Basic HTTP client (for quick demos). | `urllib.request.urlopen("https://example.com").read()` |

### 8.1 Example – logging to a rotating file  

```python
import logging
from logging.handlers import RotatingFileHandler

# Create a handler that writes to "app.log", rotating after 10 KB and keeping 3 backups
handler = RotatingFileHandler("app.log", maxBytes=10_000, backupCount=3)

# Configure the root logger
logging.basicConfig(
    level=logging.INFO,
    handlers=[handler],
    format="%(asctime)s %(levelname)s %(name)s – %(message)s"
)

log = logging.getLogger("demo")
log.info("Program started")
log.warning("Something might be wrong")
```

*Why not just `print`?* Because `logging` lets you control **where** messages go (console, file, network), filter by severity, and format them consistently.

### 8.2 Quick‑run exercise  

> **Goal:** Read a JSON file called `config.json` that looks like `{"threshold": 42}` and print whether the current hour (`datetime.datetime.now().hour`) is **above** that threshold.  

```python
import json, datetime, pathlib

config = json.loads(pathlib.Path("config.json").read_text())
now_hour = datetime.datetime.now().hour
print("Above threshold?" , now_hour > config["threshold"])
```

Create a `config.json` file next to the script, e.g.:

```json
{
  "threshold": 12
}
```

Running the script at 14:00 will print `Above threshold? True`.

---  

## 9️⃣ Core Data Structures  

### 9.1 List (dynamic array)  

```python
fruits = ["apple", "banana"]          # a mutable sequence
fruits.append("cherry")                # add at the end
fruits.insert(0, "orange")            # insert at position 0 (front)
fruits[1] = "blueberry"               # replace the second element
print(fruits)                          # ['orange', 'blueberry', 'banana', 'cherry']
```

*Why lists?* They’re the most common way to store an **ordered collection** that can grow or shrink.

### 9.2 List comprehension (concise construction)  

```python
evens = [x for x in range(20) if x % 2 == 0]   # [0,2,4,...,18]
```

*Reading it* → “Take every `x` from `0` … `19`, **if** `x` is even, and put it into a new list”.

### 9.3 Tuple (immutable sequence)  

```python
point = (10, 20)           # cannot be changed after creation
x, y = point               # tuple unpacking
```

*When to use:* When you want a **fixed** collection (e.g., coordinates) and want the interpreter to protect you from accidental changes.

### 9.4 Named tuple (lightweight record)  

```python
from collections import namedtuple
Person = namedtuple("Person", "name age")
alice = Person("Alice", 30)
print(alice.name)          # Alice
```

*Benefit:* Access by attribute (`alice.name`) instead of by index (`alice[0]`).

### 9.5 Dictionary (hash map)  

```python
phone_book = {"Ada": 5551234, "Bob": 5559876}
phone_book["Carol"] = 5550000          # add a new entry
del phone_book["Bob"]                  # remove
print(phone_book.get("Dave", "unknown"))   # safe lookup with default
```

*Explanation:* Dictionaries map **keys → values** and provide **constant‑time** look‑ups.

### 9.6 Set (unordered collection of unique items)  

```python
primes = {2, 3, 5, 7}          # duplicates are ignored automatically
primes.add(11)
primes.discard(4)               # safe – does nothing if 4 isn’t present
print(primes & {3, 4, 5})       # intersection → {3,5}
```

### 9.7 Copying data  

```python
import copy
a = [[1,2], [3,4]]
shallow = a.copy()          # copies the outer list only
deep = copy.deepcopy(a)     # copies everything, including inner lists
shallow[0][0] = 99           # also changes a[0][0]
deep[0][0] = 42              # does NOT affect a
```

### 9.8 Frozen containers (immutable)  

```python
from types import MappingProxyType

mutable = {"a": 1, "b": 2}
readonly = MappingProxyType(mutable)   # read‑only view

print(readonly["a"])    # 1
# readonly["c"] = 3      # ❌ TypeError – cannot modify
```

### 9.9 Quick‑run exercise  

> **Goal:** Find the letters that appear in **both** the words `"abracadabra"` and `"alakazam"`.  

```python
first  = set("abracadabra")
second = set("alakazam")
common = first & second            # set intersection
print(common)                     # {'a', 'b', 'r'}
```

---  

## 🔟 Iterators & Generators  

### 10.1 The iterator protocol (what `for` uses under the hood)  

```python
seq = [10, 20, 30]
it = iter(seq)          # get an iterator object
print(next(it))         # 10
print(next(it))         # 20
print(next(it))         # 30
# next(it)               # raises StopIteration – signals “no more items”
```

*Why care?* Understanding `iter`/`next` lets you create **custom** iterables that work with `for`.

### 10.2 Generator function (lazy sequence)  

```python
def fibonacci(limit: int):
    """Yield Fibonacci numbers smaller than *limit*."""
    a, b = 0, 1
    while a < limit:
        yield a               # pause here, return a value to the caller
        a, b = b, a + b       # resume on next call

# Using the generator
for n in fibonacci(20):
    print(n, end=" ")       # → 0 1 1 2 3 5 8 13
print()
```

*Key idea:* The **state** of the function (local variables) is saved between `yield`s, so you don’t have to build the whole list in memory.

### 10.3 `yield from` – delegating to another generator  

```python
def subgen():
    yield from range(3)    # yields 0,1,2

def main():
    yield from subgen()
    yield 99

print(list(main()))         # [0, 1, 2, 99]
```

### 10.4 Sending values into a generator (`send`)  

```python
def echo():
    while True:
        received = (yield)     # receives a value from .send()
        print(f"got: {received}")

g = echo()
next(g)                         # prime the generator (advances to first yield)
g.send("hello")                 # prints: got: hello
g.send(123)                     # prints: got: 123
```

### 10.5 Quick‑run exercise  

> **Goal:** Build a generator `prime_gen()` that yields an **infinite** stream of prime numbers. Print the first ten primes.  

```python
def prime_gen():
    n = 2
    while True:
        # test if n is prime
        for p in range(2, int(n ** 0.5) + 1):
            if n % p == 0:
                break               # not prime
        else:
            yield n                # prime – emit it
        n += 1

primes = prime_gen()
print([next(primes) for _ in range(10)])
# → [2, 3, 5, 7, 11, 13, 17, 19, 23, 29]
```

---  

## 1️⃣1️⃣ Advanced Sequence Handling  

### 11.1 `enumerate` – index + element together  

```python
for i, ch in enumerate("Python", start=1):
    print(i, ch)          # 1 P, 2 y, 3 t, …
```

### 11.2 `zip` – combine several iterables element‑wise  

```python
names = ["Ada", "Bob", "Cathy"]
scores = [95, 80, 72]

for name, score in zip(names, scores):
    print(f"{name} scored {score}")
```

*If the iterables have different lengths, `zip` stops at the shortest.* To keep the longer ones alive, use `itertools.zip_longest`.

### 11.3 Sorting with a custom key  

```python
words = ["Apple", "banana", "Cherry"]
# case‑insensitive alphabetical order
print(sorted(words, key=str.lower))
# → ['Apple', 'banana', 'Cherry']
```

### 11.4 `itertools` recipes (powerful building blocks)  

```python
import itertools as it

# product – Cartesian product (all possible pairs)
pairs = list(it.product([1, 2], ["a", "b"]))
# → [(1, 'a'), (1, 'b'), (2, 'a'), (2, 'b')]

# accumulate – running total
running_sum = list(it.accumulate([1, 2, 3, 4]))
# → [1, 3, 6, 10]
```

### 11.5 Quick‑run exercise  

> **Goal:** Using `itertools.product`, generate **all** three‑character passwords made only of the letters `a`, `b`, `c`. Print the **first five** passwords.  

```python
import itertools as it

alphabet = "abc"
for pwd in it.islice(it.product(alphabet, repeat=3), 5):
    print(''.join(pwd))
# abc
# aba
# abb
# abc
# aca
```

---  

## 1️⃣2️⃣ Error Handling  

### 12.1 Basic `try/except`  

```python
try:
    value = int(input("Enter a number: "))
except ValueError as exc:          # runs if conversion fails
    print("That wasn't a valid integer!", exc)
else:
    print("You entered:", value)  # runs only if no exception happened
finally:
    print("End of the block.")    # always runs (good for cleanup)
```

### 12.2 Catching *multiple* exception types  

```python
try:
    risky()
except (OSError, ValueError) as e:
    print("I/O or value problem:", e)
```

### 12.3 Custom exception classes  

```python
class ValidationError(RuntimeError):
    """Raised when input validation fails."""
    pass

def set_age(age: int):
    if age < 0:
        raise ValidationError("Age cannot be negative")
```

### 12.4 Exception chaining (`raise … from …`)  

```python
def read_int(path):
    try:
        return int(open(path).read())
    except OSError as e:                # low‑level file error
        raise ValidationError("Failed to read number") from e
```

*Result:* The traceback shows both the `ValidationError` and the original `OSError`.

### 12.5 Quick‑run exercise  

> **Goal:** Write a function `safe_div(a, b)` that returns `a / b`. If `b` is zero, raise a custom `DivisionError` with a friendly message. Demonstrate catching it.  

```python
class DivisionError(ZeroDivisionError):
    """Division by zero with extra context."""
    pass

def safe_div(a, b):
    if b == 0:
        raise DivisionError(f"Cannot divide {a} by zero")
    return a / b

try:
    safe_div(10, 0)
except DivisionError as e:
    print("Caught:", e)
```

---  

## 1️⃣3️⃣ File I/O  

### 13.1 Text files (read/write)  

```python
from pathlib import Path

path = Path("example.txt")
path.write_text("first line\nsecond line\n", encoding="utf-8")

# read back
content = path.read_text()
print(content.splitlines())      # ['first line', 'second line']
```

*Why `Path`?* It gives a **object‑oriented** way to deal with filesystem paths, works the same on Windows and POSIX systems.

### 13.2 Binary files  

```python
binary_path = Path("data.bin")
binary_path.write_bytes(b'\x00\xFF\x7A')
raw = binary_path.read_bytes()
print(list(raw))                # [0, 255, 122]
```

### 13.3 CSV (comma‑separated values)  

```python
import csv, io

csv_data = """name,age,city
Alice,30,NY
Bob,25,LA
"""

f = io.StringIO(csv_data)                # behaves like an opened file
reader = csv.DictReader(f)
for row in reader:
    print(row["name"], row["age"])
# Alice 30
# Bob 25
```

### 13.4 JSON streaming (useful for huge logs)  

```python
import json, pathlib

def stream_json_array(file_path):
    """Yield each JSON object from a file that contains one object per line."""
    with open(file_path, "r", encoding="utf-8") as f:
        for line in f:
            yield json.loads(line)

# Example usage:
# for obj in stream_json_array("log.jsonl"):
#     print(obj)
```

### 13.5 Quick‑run exercise  

> **Goal:** Write a script that copies a text file `source.txt` line‑by‑line, but writes each line **reversed** to a new file `reversed.txt`.  

```python
src = Path("source.txt")
dst = Path("reversed.txt")

with src.open("r") as fin, dst.open("w") as fout:
    for line in fin:
        fout.write(line.rstrip()[::-1] + "\n")
```

*Explanation:* `line.rstrip()` removes the trailing newline, `[::-1]` reverses the string, then we add a fresh newline.

---  

## 1️⃣4️⃣ Object‑Oriented Programming  

### 14.1 Defining a class  

```python
class Circle:
    """A geometric circle."""
    pi = 3.14159                 # class attribute – shared by all circles

    def __init__(self, radius: float):
        """Constructor – runs when you create a new Circle."""
        self.radius = radius      # instance attribute – unique to each object

    def area(self) -> float:
        """Return the area of the circle."""
        return self.pi * self.radius ** 2

c = Circle(2.5)
print(c.area())                 # ≈ 19.63
```

*Key points*  

* `class Circle:` starts a **class definition** (a blueprint).  
* `__init__` is the **initializer** – Python calls it automatically when you write `Circle(2.5)`.  
* `self` is the reference to the specific instance being created or used.  

### 14.2 `@property` – computed attribute  

```python
class Rectangle:
    def __init__(self, w, h):
        self.w = w
        self.h = h

    @property
    def area(self):
        """Read‑only attribute that computes width × height."""
        return self.w * self.h

r = Rectangle(3, 4)
print(r.area)          # 12 – accessed like an attribute, not a method
```

### 14.3 `__slots__` – memory optimisation  

```python
class Point:
    __slots__ = ("x", "y")   # only these attributes are allowed
    def __init__(self, x, y):
        self.x, self.y = x, y

p = Point(1, 2)
# p.z = 3                  # ❌ AttributeError – slots prevent new attrs
```

*Why use `__slots__`?* It removes the per‑instance `__dict__`, saving memory when you create many objects.

### 14.4 Quick‑run exercise  

> **Goal:** Implement a `BankAccount` class with `owner` (string) and `balance` (float). Add methods `deposit(amount)`, `withdraw(amount)` (raise `ValueError` if insufficient funds) and a read‑only property `is_overdrawn`.  

```python
class BankAccount:
    """Simple bank account model."""
    def __init__(self, owner: str, balance: float = 0.0):
        self.owner = owner
        self._balance = float(balance)   # leading underscore → internal use

    @property
    def balance(self) -> float:
        """Public read‑only view of the balance."""
        return self._balance

    def deposit(self, amount: float):
        if amount < 0:
            raise ValueError("Deposit must be positive")
        self._balance += amount

    def withdraw(self, amount: float):
        if amount > self._balance:
            raise ValueError("Insufficient funds")
        self._balance -= amount

    @property
    def is_overdrawn(self) -> bool:
        """True if balance is below zero."""
        return self._balance < 0

# Demo
acc = BankAccount("Alice", 100)
acc.withdraw(30)
print(acc.balance, acc.is_overdrawn)   # 70 False
```

---  

## 1️⃣5️⃣ Inheritance & Polymorphism  

### 15.1 What is inheritance?  

*Inheritance* lets you **reuse** code from a parent class and **extend** or **override** it in a child class.

```python
class Animal:
    def speak(self):
        raise NotImplementedError("Subclasses must implement this")

class Dog(Animal):
    def speak(self):
        return "Woof!"

class Cat(Animal):
    def speak(self):
        return "Meow!"

for pet in (Dog(), Cat()):
    print(pet.speak())
# Woof!
# Meow!
```

### 15.2 Method Resolution Order (MRO) – how Python finds a method  

```python
class A:
    def greet(self):
        print("Hello from A")

class B(A):
    def greet(self):
        print("Hello from B")
        super().greet()          # calls the next method in the MRO

class C(A):
    def greet(self):
        print("Hello from C")
        super().greet()

class D(B, C):
    pass

d = D()
d.greet()
# → Hello from B
# → Hello from C
# → Hello from A
print(D.mro())
# [<class '__main__.D'>, <class '__main__.B'>,
#  <class '__main__.C'>, <class '__main__.A'>, <class 'object'>]
```

*Explanation:* Python walks the list returned by `mro()` to decide which version of `greet` to call next. `super()` is a *helper* that automatically follows that list.

### 15.3 Abstract Base Classes (ABCs) – a contract for subclasses  

```python
import abc

class Serializer(abc.ABC):
    @abc.abstractmethod
    def dumps(self, obj) -> str: ...
    @abc.abstractmethod
    def loads(self, data: str):
        ...

class JsonSerializer(Serializer):
    def dumps(self, obj):
        import json
        return json.dumps(obj)

    def loads(self, data):
        import json
        return json.loads(data)
```

If a subclass forgets to implement one of the abstract methods, Python raises a `TypeError` when you try to instantiate it.

### 15.4 Quick‑run exercise  

> **Goal:** Define an abstract base class `Vehicle` with an abstract method `fuel()` that returns a string. Implement three concrete subclasses `ElectricCar`, `GasCar`, and `HybridCar` that return `"electricity"`, `"petrol"` and `"electricity + petrol"` respectively. Iterate over a list of mixed vehicles and print each fuel source.  

```python
import abc

class Vehicle(abc.ABC):
    @abc.abstractmethod
    def fuel(self) -> str:
        ...

class ElectricCar(Vehicle):
    def fuel(self): return "electricity"

class GasCar(Vehicle):
    def fuel(self): return "petrol"

class HybridCar(Vehicle):
    def fuel(self): return "electricity + petrol"

fleet = [ElectricCar(), GasCar(), HybridCar()]
for v in fleet:
    print(v.fuel())
# electricity
# petrol
# electricity + petrol
```

---  

## 1️⃣6️⃣ Special (Dunder) Methods  

“Dunder” = “double underscore”. These methods let you **plug your objects into Python’s built‑in syntax**.

| Dunder | What you can do with it | Example code |
|--------|------------------------|--------------|
| `__str__` | `print(obj)` → human‑readable | `def __str__(self): return f"{self.x},{self.y}"` |
| `__repr__` | `repr(obj)` → unambiguous, `eval(repr(obj))` if possible | `def __repr__(self): return f"Point({self.x!r}, {self.y!r})"` |
| `__len__` | `len(obj)` | `def __len__(self): return len(self._items)` |
| `__getitem__` | Indexing `obj[i]` (supports slices) | `def __getitem__(self, i): return self._data[i]` |
| `__setitem__` | Mutating `obj[i] = v` | `def __setitem__(self, i, v): self._data[i] = v` |
| `__iter__` | Makes object **iterable** (`for … in obj`) | `def __iter__(self): return iter(self._data)` |
| `__call__` | Makes instance callable `obj()` | `def __call__(self, *a, **kw): …` |
| Arithmetic (`__add__`, `__sub__`, …) | `a + b`, `a - b` etc. | `def __add__(self, other): return Vec2(self.x+other.x, self.y+other.y)` |

### 16.1 Example – immutable 2‑D vector with arithmetic  

```python
class Vec2:
    __slots__ = ("x", "y")            # saves memory
    def __init__(self, x, y):
        self.x, self.y = x, y

    def __repr__(self):
        return f"Vec2({self.x!r}, {self.y!r})"

    def __add__(self, other):
        if not isinstance(other, Vec2):
            return NotImplemented
        return Vec2(self.x + other.x, self.y + other.y)

    def __len__(self):
        return 2                       # length of the vector (number of components)

    def __iter__(self):
        yield self.x
        yield self.y

v = Vec2(1, 2)
print(v + Vec2(3, 4))                # Vec2(4, 6)
print(list(v))                       # [1, 2]
print(len(v))                        # 2
```

### 16.2 Quick‑run exercise  

> **Goal:** Create a `Counter` class that supports `+`, `-`, and `abs()` using dunder methods. The internal value should be stored in a private attribute `_value`.  

```python
class Counter:
    def __init__(self, start=0):
        self._value = int(start)

    def __repr__(self):
        return f"Counter({self._value})"

    def __add__(self, other):
        if isinstance(other, (int, Counter)):
            return Counter(self._value + int(other))
        return NotImplemented

    def __sub__(self, other):
        if isinstance(other, (int, Counter)):
            return Counter(self._value - int(other))
        return NotImplemented

    def __abs__(self):
        return Counter(abs(self._value))

c = Counter(5)
print(c + 3)        # Counter(8)
print(c - 10)       # Counter(-5)
print(abs(c - 10))  # Counter(5)
```

---  

## 1️⃣7️⃣ Dataclasses & attrs  

### 17.1 `@dataclass` – boilerplate‑free containers  

```python
from dataclasses import dataclass, field

@dataclass
class Employee:
    """A simple record for an employee."""
    name: str
    id: int
    salary: float = 0.0
    active: bool = field(default=True, init=False)   # not part of __init__

    def give_raise(self, pct: float):
        self.salary *= 1 + pct / 100

e = Employee("Ada", 1, 70000)
print(e)                 # Employee(name='Ada', id=1, salary=70000, active=True)
e.give_raise(5)
print(e.salary)          # 73500.0
```

*What `@dataclass` adds automatically:*  

* `__init__` (constructor) that assigns the fields.  
* `__repr__` for debugging.  
* `__eq__` (equality) if you need to compare two instances.  

### 17.2 Frozen (immutable) dataclasses  

```python
@dataclass(frozen=True)
class Point:
    x: float
    y: float

p = Point(1.2, 3.4)
# p.x = 5    # ❌ raises FrozenInstanceError
```

### 17.3 `attrs` library (alternative, more flexible)  

```bash
$ pip install attrs
```

```python
import attr

@attr.s(auto_attribs=True, kw_only=True)
class Book:
    title: str
    author: str
    pages: int = 0
    isbn: str = attr.ib(default=None, validator=attr.validators.optional(attr.validators.instance_of(str)))

b = Book(title="Python 101", author="Guido", pages=250)
print(b)               # Book(title='Python 101', author='Guido', pages=250, isbn=None)
```

*Why use `attrs`?* It offers **validators**, **converters**, and better support for *default factories*.

### 17.4 Quick‑run exercise  

> **Goal:** Define a **frozen** dataclass `RGB` that stores three integer colour channels (`r,g,b`). Add a method `as_hex()` that returns a hexadecimal colour string like `#ff00aa`.  

```python
from dataclasses import dataclass

@dataclass(frozen=True)
class RGB:
    r: int
    g: int
    b: int

    def as_hex(self) -> str:
        """Return colour in #RRGGBB format."""
        return f"#{self.r:02x}{self.g:02x}{self.b:02x}"

c = RGB(255, 165, 0)
print(c.as_hex())          # #ffa500 (orange)
```

---  

## 1️⃣8️⃣ Static Typing  

### 18.1 Core `typing` primitives  

```python
from typing import List, Tuple, Dict, Set, Union, Optional, Callable

Numbers = List[int]                # alias for a list of ints

def mean(values: List[float]) -> float:
    return sum(values) / len(values)

def find(key: str, mapping: Dict[str, int]) -> Optional[int]:
    """Return the value for *key* or None if missing."""
    return mapping.get(key)
```

### 18.2 Generics & Protocols (structural subtyping)  

```python
from typing import Protocol, Iterable, TypeVar

T = TypeVar("T")

class SupportsLen(Protocol):
    def __len__(self) -> int: ...

def total_len(*objs: SupportsLen) -> int:
    return sum(len(o) for o in objs)

print(total_len([1,2,3], "abc", {1,2}))   # 3+3+2 = 8
```

*Protocol* means “any object that **has** a `__len__` method is acceptable, even if it doesn’t inherit from a specific base class.

### 18.3 `Literal` & `TypedDict`  

```python
from typing import Literal, TypedDict

Direction = Literal["N", "S", "E", "W"]

class Config(TypedDict):
    timeout: int
    debug: bool
    mode: Literal["dev", "prod"]

cfg: Config = {"timeout": 30, "debug": True, "mode": "dev"}
```

### 18.4 Quick‑run exercise  

> **Goal:** Write a generic `pairwise(iterable)` that yields overlapping pairs `(a, b)`. Add proper type hints.  

```python
from typing import Iterable, Iterator, Tuple, TypeVar

T = TypeVar("T")

def pairwise(seq: Iterable[T]) -> Iterator[Tuple[T, T]]:
    """Yield (seq[0], seq[1]), (seq[1], seq[2]), …."""
    it = iter(seq)
    try:
        prev = next(it)
    except StopIteration:
        return
    for cur in it:
        yield (prev, cur)
        prev = cur

print(list(pairwise([1, 2, 3, 4])))   # [(1, 2), (2, 3), (3, 4)]
```

---  

## 1️⃣9️⃣ Structural Pattern Matching (Python 3.10+)  

Python 3.10 introduced **`match` / `case`**, a powerful way to deconstruct data similar to `switch` in other languages—but *far more expressive*.

### 19.1 Basic `match` syntax  

```python
def describe(value):
    match value:
        case 0:
            return "zero"
        case []:
            return "empty list"
        case [x, y]:
            return f"pair ({x}, {y})"
        case {"type": t, "payload": p}:
            return f"{t} with {p}"
        case _:
            return "something else"
```

*`_`* is the **wildcard pattern** that matches anything (like the `default` case).

### 19.2 Guarded patterns (`if` after a case)  

```python
def classify(num):
    match num:
        case n if n < 0:
            return "negative"
        case n if n == 0:
            return "zero"
        case n if n > 0:
            return "positive"
```

### 19.3 Sequence deconstruction with `*` (star)  

```python
def head_tail(seq):
    match seq:
        case [first, *rest]:
            return first, rest
        case []:
            return None, []
```

*`*rest`* collects **all remaining items** into a list.

### 19.4 Class pattern matching  

```python
class Point:
    def __init__(self, x, y):
        self.x, self.y = x, y

def where(p):
    match p:
        case Point(0, 0):
            return "origin"
        case Point(x, 0):
            return f"x‑axis at {x}"
        case Point(0, y):
            return f"y‑axis at {y}"
        case Point(x, y):
            return f"({x}, {y})"
```

The class must have an appropriate `__match_args__` (automatically generated for simple `__init__` signatures).

### 19.5 Quick‑run exercise  

> **Goal:** Implement `http_status(code)` that uses `match` to return `"Success"` for 2xx, `"Client error"` for 4xx, `"Server error"` for 5xx, otherwise `"Other"`. Use a guard (`if`) to test the numeric range.  

```python
def http_status(code: int) -> str:
    match code:
        case _ if 200 <= code < 300:
            return "Success"
        case _ if 400 <= code < 500:
            return "Client error"
        case _ if 500 <= code < 600:
            return "Server error"
        case _:
            return "Other"

print([http_status(c) for c in [200, 404, 503, 123]])
# ['Success', 'Client error', 'Server error', 'Other']
```

---  

## 2️⃣0️⃣ Enhanced Pattern Matching – **Python 3.14**  

Python 3.14 refines the matching engine (PEP 695‑710). The changes are **syntactic** but affect how you write reliable matches.

| New behaviour (3.14) | What it means for you |
|----------------------|----------------------|
| **Wildcard (`_`) precedence** – guarded wildcards win over plain `_`. | You can safely place a final `case _:` at the bottom of a `match` block **without worrying** that a `case _ if …` clause earlier will be skipped. |
| **Value‑pattern ordering** – literal patterns are checked in source order. | The order you write `case 2: … case 1:` is now **honoured**, making the code easier to read and reason about. |
| **Byte‑matching** – `bytes` and `bytearray` are now *sequence* types for pattern matching. | You can match on raw binary data (e.g., file signatures) directly with `case b'\xFF\xD8\xFF': …`. |
| **`CaseGuardError`** – a compile‑time exception for impossible guards (`case _ if False:`). | IDEs and static analysers can warn you about dead branches. |
| **Improved error messages** – pinpoint the exact pattern that caused a syntax or type error. | Faster debugging when a `match` statement blows up. |

### 20.1 Example – guarded wildcard wins  

```python
def categorize(x):
    match x:
        case _ if isinstance(x, dict):
            return "a dict"
        case _:
            return "something else"

print(categorize({"a": 1}))   # → a dict
print(categorize(42))         # → something else
```

*Before 3.14* the plain `_` could have swallowed the guarded case, leading to the wrong result.  

### 20.2 Value‑pattern ordering (source order kept)  

```python
def label(v):
    match v:
        case 2: return "two"
        case 1: return "one"
        case 3: return "three"
        case _: return "other"

print(label(2))   # two (checked first)
print(label(1))   # one
```

You no longer need to remember obscure hash‑ordering tricks.  

### 20.3 Byte‑matching (detect file headers)  

```python
def file_type(header: bytes) -> str:
    match header:
        case b'\xFF\xD8\xFF':
            return "JPEG"
        case b'\x89PNG\r\n\x1a\n':
            return "PNG"
        case b'GIF87a' | b'GIF89a':
            return "GIF"
        case _:
            return "unknown"

print(file_type(b'\xFF\xD8\xFF\xE0'))   # JPEG
print(file_type(b'GIF89a'))            # GIF
```

### 20.4 Using `CaseGuardError` (compiler check)  

```python
def absurd(x):
    match x:
        case _ if False:          # ❌ Compile‑time error: impossible guard
            pass
```

Running the file now raises `CaseGuardError: guard is always false`, preventing dead code.

### 20.5 Quick‑run exercise (all new features)  

> **Goal:** Write `detect_file_type(data: bytes)` that returns `"JPEG"`, `"PNG"`, `"GIF"` or `"unknown (large file)"` if the byte sequence is longer than 8 bytes and not a known header. Use a **guarded wildcard** to implement the “large file” fallback.

```python
def detect_file_type(data: bytes) -> str:
    match data:
        case b'\xFF\xD8\xFF':
            return "JPEG"
        case b'\x89PNG\r\n\x1a\n':
            return "PNG"
        case b'GIF87a' | b'GIF89a':
            return "GIF"
        case _ if len(data) > 8:
            return "unknown (large file)"
        case _:
            return "unknown (too short)"

print(detect_file_type(b'\xFF\xD8\xFF\xE0'))          # JPEG
print(detect_file_type(b'\x89PNG\r\n\x1a\nextra'))   # PNG
print(detect_file_type(b'XYZ'))                     # unknown (too short)
print(detect_file_type(b'A'*20))                    # unknown (large file)
```

*Notice* the guarded wildcard (`case _ if len(data) > 8:`) precedes the final plain `_`. In 3.14 the guard is guaranteed to be evaluated first.

---  

## 2️⃣1️⃣ Decorators  

A **decorator** is a function that *wraps* another function (or class) to extend or modify its behaviour, **without changing the wrapped code**.

### 21.1 Simple timing decorator  

```python
import time, functools

def timer(func):
    """Print how long *func* takes to run."""
    @functools.wraps(func)          # keep the original name & docstring
    def wrapper(*args, **kwargs):
        start = time.perf_counter()
        result = func(*args, **kwargs)
        end = time.perf_counter()
        print(f"{func.__name__} took {end - start:.4f}s")
        return result
    return wrapper

@timer
def heavy():
    sum(i*i for i in range(1_000_000))

heavy()
```

### 21.2 Parameterised decorator  

```python
def repeat(times: int):
    """Run the decorated function *times* times."""
    def decorator(func):
        @functools.wraps(func)
        def wrapper(*a, **kw):
            for _ in range(times):
                func(*a, **kw)
        return wrapper
    return decorator

@repeat(3)
def hello():
    print("hi")
```

### 21.3 Class decorator (adds a method)  

```python
def add_repr(cls):
    """Inject a simple __repr__ into the class."""
    def __repr__(self):
        attrs = ', '.join(f"{k}={v!r}" for k, v in self.__dict__.items())
        return f"{self.__class__.__name__}({attrs})"
    cls.__repr__ = __repr__
    return cls

@add_repr
class Person:
    def __init__(self, name, age):
        self.name = name
        self.age = age

print(Person("Bob", 42))
# → Person(name='Bob', age=42)
```

### 21.4 Cached property (similar to `functools.cached_property`)  

```python
import functools

def cached_property(method):
    """Compute once, store on the instance, return cached value on later accesses."""
    attr_name = f"_{method.__name__}_cached"

    @property
    @functools.wraps(method)
    def wrapper(self):
        if not hasattr(self, attr_name):
            setattr(self, attr_name, method(self))
        return getattr(self, attr_name)
    return wrapper

class Expensive:
    @cached_property
    def data(self):
        print("computing …")
        return sum(i*i for i in range(1_000_000))

e = Expensive()
print(e.data)   # computes and prints
print(e.data)   # no computation this time
```

### 21.5 Quick‑run exercise  

> **Goal:** Implement a decorator `suppress(*exc)` that silently ignores the specified exceptions when the wrapped function raises them.  

```python
import functools

def suppress(*exc):
    """Decorator that swallows listed exception types."""
    def decorator(func):
        @functools.wraps(func)
        def wrapper(*a, **kw):
            try:
                return func(*a, **kw)
            except exc:
                return None            # or any sentinel you like
        return wrapper
    return decorator

@suppress(ZeroDivisionError, ValueError)
def risky(x):
    return 10 / x

print(risky(2))   # 5.0
print(risky(0))   # None (ZeroDivisionError suppressed)
print(risky("a")) # None (ValueError suppressed)
```

---  

## 2️⃣2️⃣ Context Managers  

The `with` statement ensures **setup** and **teardown** happen reliably (even if an exception occurs).

### 22.1 Manual class implementation  

```python
class Timer:
    def __enter__(self):
        import time
        self.start = time.perf_counter()
        return self                     # optional; can be used as “as” target

    def __exit__(self, exc_type, exc, tb):
        import time
        self.end = time.perf_counter()
        print(f"Elapsed: {self.end - self.start:.4f}s")
        # return False → propagate any exception (True would swallow it)

# usage
with Timer():
    sum(i*i for i in range(1_000_000))
```

### 22.2 Using `contextlib.contextmanager` (generator‑based)  

```python
from contextlib import contextmanager

@contextmanager
def suppress(*exc):
    try:
        yield               # code inside the with‑block runs here
    except exc:
        pass                # swallow the exception

with suppress(ZeroDivisionError):
    1 / 0                # silently ignored
```

### 22.3 Async context manager (`__aenter__` / `__aexit__`)  

```python
class AsyncTimer:
    async def __aenter__(self):
        import time, asyncio
        self.start = time.perf_counter()
        await asyncio.sleep(0)   # illustrate async entry
        return self

    async def __aexit__(self, exc_type, exc, tb):
        import time
        self.end = time.perf_counter()
        print(f"Async elapsed {self.end - self.start:.4f}s")

import asyncio
async def demo():
    async with AsyncTimer():
        await asyncio.sleep(0.2)

asyncio.run(demo())
```

### 22.4 Quick‑run exercise  

> **Goal:** Write a context manager `cd(path)` that temporarily changes the current working directory to `path` and automatically restores the original directory when the block ends.

```python
import os
from contextlib import contextmanager

@contextmanager
def cd(new_path):
    """Temporarily change to *new_path*."""
    old_path = os.getcwd()
    os.chdir(new_path)
    try:
        yield                     # code inside the with‑block runs here
    finally:
        os.chdir(old_path)        # always restore, even if an exception happened

# Demo – create a temporary directory and list its contents
import pathlib, tempfile
tmp = pathlib.Path(tempfile.mkdtemp())
(tmp / "demo.txt").write_text("hello")

print("Before:", os.listdir())                # original folder
with cd(tmp):
    print("Inside:", os.listdir())           # shows the temporary folder
print("After :", os.listdir())               # back to original
```

---  

## 2️⃣3️⃣ Concurrency  

### 23.1 Thread‑based concurrency (`threading`)  

```python
import threading, time

def worker(name):
    for i in range(3):
        print(f"{name} tick {i}")
        time.sleep(0.4)

threads = [threading.Thread(target=worker, args=(f"T{i}",)) for i in range(2)]
for t in threads:
    t.start()               # launch the thread
for t in threads:
    t.join()                # wait for it to finish
print("All done")
```

*Important note:* **Threads share memory**, so you must protect shared data (e.g., with `threading.Lock`) to avoid race conditions.  

### 23.2 Process‑based concurrency (`multiprocessing`)  

```python
from concurrent.futures import ProcessPoolExecutor

def is_prime(n):
    if n < 2:
        return False
    for p in range(2, int(n**0.5)+1):
        if n % p == 0:
            return False
    return True

numbers = [11, 12, 13, 14, 15, 16, 17, 18, 19]

with ProcessPoolExecutor() as pool:
    results = list(pool.map(is_prime, numbers))

print(dict(zip(numbers, results)))
# {11: True, 12: False, 13: True, …}
```

*Why use processes?* Each process has its own Python interpreter and memory space, which sidesteps the **Global Interpreter Lock (GIL)** that limits true parallelism for CPU‑bound code.

### 23.3 Thread‑safe queue (`queue.Queue`)  

```python
import queue, threading, time

work = queue.Queue()
for i in range(5):
    work.put(i)

def consumer():
    while True:
        item = work.get()
        if item is None:          # sentinel value = “stop”
            break
        print(f"consumed {item}")
        work.task_done()

t = threading.Thread(target=consumer)
t.start()

work.join()                     # wait until all items processed
work.put(None)                  # send sentinel to stop the consumer
t.join()
```

### 23.4 Quick‑run exercise  

> **Goal:** Use a `ThreadPoolExecutor` to fetch three small webpages concurrently (`https://example.com`, `https://httpbin.org/uuid`, `https://httpbin.org/ip`). Print each URL together with the size of the retrieved body (in bytes).

```python
import urllib.request
from concurrent.futures import ThreadPoolExecutor

urls = [
    "https://example.com",
    "https://httpbin.org/uuid",
    "https://httpbin.org/ip",
]

def fetch(url):
    with urllib.request.urlopen(url) as resp:
        data = resp.read()
    return url, len(data)

with ThreadPoolExecutor(max_workers=3) as exe:
    for url, size in exe.map(fetch, urls):
        print(f"{url}: {size} bytes")
```

---  

## 2️⃣4️⃣ Async IO  

Async IO lets a single thread *interleave* many I/O‑bound tasks without blocking.

### 24.1 Simple coroutine  

```python
import asyncio

async def slow_square(x):
    await asyncio.sleep(0.5)          # simulate I/O delay
    return x * x

async def main():
    results = await asyncio.gather(
        slow_square(2),
        slow_square(3),
        slow_square(4),
    )
    print("squares:", results)

asyncio.run(main())
```

### 24.2 Async generator  

```python
async def ticker(interval: float, count: int):
    for i in range(count):
        await asyncio.sleep(interval)
        yield i

async def demo():
    async for tick in ticker(0.3, 5):
        print("tick", tick)

asyncio.run(demo())
```

### 24.3 Cancellation and timeouts  

```python
async def never():
    await asyncio.sleep(3600)  # pretend to run forever

async def main():
    task = asyncio.create_task(never())
    try:
        await asyncio.wait_for(task, timeout=1.0)
    except asyncio.TimeoutError:
        task.cancel()
        print("Task cancelled after timeout")

asyncio.run(main())
```

### 24.4 Quick‑run exercise  

> **Goal:** Write an async function `fetch_json(url)` using the third‑party library **`aiohttp`** (install with `pip install aiohttp`). Fetch three JSON placeholder endpoints concurrently and print the `"title"` field from each response.

```python
import asyncio, aiohttp

async def fetch_json(session, url):
    async with session.get(url) as resp:
        return await resp.json()

async def main():
    urls = [
        "https://jsonplaceholder.typicode.com/posts/1",
        "https://jsonplaceholder.typicode.com/posts/2",
        "https://jsonplaceholder.typicode.com/posts/3",
    ]
    async with aiohttp.ClientSession() as sess:
        tasks = [fetch_json(sess, u) for u in urls]
        for data in await asyncio.gather(*tasks):
            print(data["title"])

asyncio.run(main())
```

---  

## 2️⃣5️⃣ Testing & Debugging  

### 25.1 `unittest` – the built‑in test framework  

```python
import unittest

def add(a, b):
    """Simple addition."""
    return a + b

class TestAdd(unittest.TestCase):
    def test_positive(self):
        self.assertEqual(add(2, 3), 5)

    def test_negative(self):
        self.assertEqual(add(-1, -1), -2)

    def test_type_error(self):
        with self.assertRaises(TypeError):
            add("a", 1)          # strings and ints cannot be added

if __name__ == "__main__":
    unittest.main()
```

Run with `python -m unittest discover` to automatically discover tests in any `test_*.py` file.

### 25.2 `pytest` – a more concise, feature‑rich alternative  

```python
# test_calc.py
def mul(a, b):
    """Multiply two numbers."""
    return a * b

def test_mul():
    assert mul(3, 4) == 12
    assert mul(-2, 5) == -10

# parametric test (run the same test with many inputs)
import pytest

@pytest.mark.parametrize("a,b,exp", [
    (1, 2, 3),
    (0, 5, 5),
    (-1, -1, -2),
])
def test_add(a, b, exp):
    assert a + b == exp
```

Run with `pytest -q`.  

### 25.3 `doctest` – embed tests in docstrings  

```python
def inc(x):
    """
    Return x+1.

    >>> inc(3)
    4
    >>> inc(-1)
    0
    """
    return x + 1

if __name__ == "__main__":
    import doctest
    doctest.testmod()
```

### 25.4 Interactive debugging with `pdb`  

```python
def buggy(a, b):
    import pdb; pdb.set_trace()   # execution stops here, you get a REPL
    return a / b

buggy(1, 0)                      # you can type `c` (continue) or `p b` (print b)
```

In VS Code you can place a **breakpoint** (click left of the line number) and start a **Debug** session; the editor will handle `pdb`‑style stepping for you.

### 25.5 Quick‑run exercise  

> **Goal:** Write a `pytest` test file that verifies the `BankAccount` class from Chapter 14 behaves correctly: deposit adds money, withdrawing more than the balance raises `ValueError`, and `is_overdrawn` reflects the balance state.

```python
# test_bank.py
import pytest
from bank import BankAccount, ValidationError   # assume the class lives in bank.py

def test_deposit():
    acc = BankAccount("Bob", 100)
    acc.deposit(50)
    assert acc.balance == 150

def test_withdraw_success():
    acc = BankAccount("Bob", 100)
    acc.withdraw(70)
    assert acc.balance == 30

def test_withdraw_error():
    acc = BankAccount("Bob", 20)
    with pytest.raises(ValueError):
        acc.withdraw(30)

def test_overdrawn_flag():
    acc = BankAccount("Bob", 0)
    assert not acc.is_overdrawn
    # force a negative balance for the test
    acc._balance = -5
    assert acc.is_overdrawn
```

Run with `pytest test_bank.py -q`.  

---  

## 2️⃣6️⃣ Packaging & Distribution  

### 26.1 Minimal `pyproject.toml` (PEP 518)  

```toml
[project]
name = "awesome-lib"
version = "0.1.0"
description = "A tiny example library"
authors = [{name = "Jane Doe", email = "jane@example.com"}]
readme = "README.md"
license = {text = "MIT"}
requires-python = ">=3.9"
dependencies = ["requests >=2.0"]

[build-system]
requires = ["setuptools", "wheel"]
build-backend = "setuptools.build_meta"
```

*Place this file at the **root** of your repository.*  

### 26.2 Building a distribution  

```bash
$ pip install build          # one‑time
$ python -m build            # creates `dist/awesome_lib-0.1.0-py3-none-any.whl` and a .tar.gz
```

### 26.3 Publishing to PyPI  

```bash
$ pip install twine
$ twine upload dist/*        # prompts for username/password
```

*Tip:* Use **Test PyPI** (`test.pypi.org`) first: `twine upload --repository testpypi dist/*`.

### 26.4 Virtual environments (`venv`)  

```bash
$ python -m venv .venv
$ source .venv/bin/activate      # on Windows: .venv\Scripts\activate
(.venv) $ pip install -e .        # editable install – changes in source reflect instantly
```

### 26.5 `poetry` – an alternative that handles deps & publishing  

```bash
$ curl -sSL https://install.python-poetry.org | python3 -
$ poetry new my_pkg
$ cd my_pkg
$ poetry add requests
$ poetry build
$ poetry publish
```

### 26.6 Quick‑run exercise  

> **Goal:** Turn the earlier `greetings` package into a proper installable distribution. Create a `pyproject.toml` (as above, name it `greetings`) and build a wheel. Verify the wheel can be installed in a fresh virtual environment.

```bash
# 1. inside the project root (where greetings/ lives)
$ cat > pyproject.toml <<'EOF'
[project]
name = "greetings"
version = "0.1.0"
description = "Simple greeting utilities"
authors = [{name="Your Name", email="you@example.com"}]
requires-python = ">=3.9"

[build-system]
requires = ["setuptools", "wheel"]
build-backend = "setuptools.build_meta"
EOF

# 2. Build
$ python -m build

# 3. Create a clean venv and install the wheel
$ python -m venv ../tmpenv
$ source ../tmpenv/bin/activate
(tmpenv) $ pip install dist/greetings-0.1.0-py3-none-any.whl
(tmpenv) $ python -c "from greetings.english import hello; print(hello('World'))"
# → Hello, World!
```

---  

## 2️⃣7️⃣ Performance Tips  

| Problem | Recommended fix (plain language) |
|---------|-----------------------------------|
| **Slow loops** | Use built‑ins (`sum`, `any`, `all`) or **list comprehensions** instead of manual `for … append`. |
| **Repeated expensive calculations** | Decorate with `@functools.lru_cache` (memoisation). |
| **Heavy numeric work** | Switch to **NumPy** (vectorised C‑loops). |
| **CPU‑bound parallelism** | Use **ProcessPoolExecutor** or **Cython**/**Numba** to compile hot sections. |
| **Memory‑heavy objects** | Add `__slots__` to classes, or use `array.array` / `bytearray` for homogenous numeric data. |
| **Unclear bottleneck** | Run **cProfile** and visualise with **snakeviz** (`pip install snakeviz`). |
| **I/O bound** | Switch to **asyncio** or **threaded I/O**. |
| **Interpreter overhead** | Consider **PyPy** (JIT‑compiled Python) for long‑running pure‑Python code. |

### 27.1 Profiling example  

```bash
$ python -m cProfile -s cumtime myscript.py > profile.txt
$ snakeviz profile.txt         # opens a browser with an interactive flame graph
```

### 27.2 Caching example (fib)  

```python
import functools, time

@functools.lru_cache(maxsize=None)   # cache every result forever
def fib(n):
    if n < 2:
        return n
    return fib(n-1) + fib(n-2)

t0 = time.perf_counter()
print(fib(35))
print("took", time.perf_counter() - t0, "seconds")
```

### 27.3 Quick‑run exercise  

> **Goal:** Benchmark two ways of computing the sum of squares of numbers 0‑999 999: (a) a plain `for` loop with `+=`, (b) `sum(i*i for i in range(...))`. Print both timings.

```python
import time

def loop():
    total = 0
    for i in range(1_000_000):
        total += i*i
    return total

def gen_sum():
    return sum(i*i for i in range(1_000_000))

t0 = time.perf_counter()
loop()
print("loop:", time.perf_counter() - t0)

t0 = time.perf_counter()
gen_sum()
print("gen_sum:", time.perf_counter() - t0)
```

You’ll typically see `gen_sum` complete **2‑3× faster** because the heavy work is done in C inside `sum`.

---  

## 2️⃣8️⃣ Best Practices & Style  

| Concern | Recommendation (plain language) |
|---------|-----------------------------------|
| **Formatting** | Run `black --line-length 88` on every file; it enforces a single, readable style. |
| **Linting** | Use `ruff` (fast) or `flake8`. Treat warnings as errors in CI (`ruff check . --exit-zero`). |
| **Docstrings** | Triple‑quoted, start with a one‑sentence summary, then optional sections (`Args:`, `Returns:`). Follow **PEP 257**. |
| **Static typing** | Add type hints to public APIs; run `mypy .` in CI. |
| **Testing** | Aim for **≥ 90 %** code coverage (`coverage run -m pytest && coverage report`). |
| **Pre‑commit hooks** | Install `pre-commit` and include hooks for `black`, `ruff`, `mypy`. They run automatically before each commit. |
| **Dependency pins** | In `pyproject.toml` or `requirements.txt` pin exact versions for reproducible builds. |
| **Version control** | Keep a clean `git` history; each commit should be a logical unit of work. |
| **Documentation** | Render the `README.md` with examples; consider `mkdocs` for a full site. |

### 28.1 Example `.pre-commit-config.yaml`

```yaml
repos:
  - repo: https://github.com/psf/black
    rev: 24.3.0
    hooks:
      - id: black
        args: [--line-length, "88"]
  - repo: https://github.com/charliermarsh/ruff-pre-commit
    rev: v0.4.8
    hooks:
      - id: ruff
  - repo: https://github.com/pre-commit/mirrors-mypy
    rev: v1.9.0
    hooks:
      - id: mypy
        additional_dependencies: [types-requests]
```

Run once `pre-commit install`; thereafter every `git commit` will auto‑format, lint, and type‑check.

---  

## 2️⃣9️⃣ Where to Go Next?  

| Path | Popular libraries / frameworks | First‑step tutorial |
|------|-------------------------------|---------------------|
| **Web development** | **FastAPI** (modern async API), **Django** (full‑stack), **Jinja2** (templates), **httpx** (HTTP client) | <https://fastapi.tiangolo.com/tutorial/> |
| **Data Science** | **NumPy**, **pandas**, **matplotlib**, **scikit‑learn** | <https://pandas.pydata.org/docs/getting_started/index.html> |
| **Machine Learning** | **PyTorch**, **TensorFlow**, **HuggingFace Transformers** | <https://pytorch.org/tutorials/> |
| **Automation / scripting** | **Selenium**, **Playwright**, **pyautogui**, **APScheduler** | <https://realpython.com/beautiful-soup-web-scraper-python/> |
| **Embedded / MicroPython** | **CircuitPython**, **MicroPython**, **Raspberry Pi Pico** | <https://learn.adafruit.com/circuitpython-basics-overview> |
| **Contributing to CPython** | Read the **devguide**, run the test suite, submit patches. | <https://devguide.python.org/> |
| **Package authoring** | **poetry**, **twine**, **GitHub Actions** CI | <https://realpython.com/pypi-publish-python-package/> |

> **Tip for the absolute beginner:** Pick *one* direction and finish a small project (e.g., a command‑line todo list, a Flask web‑app, or a data‑analysis notebook). The concepts you’ve learned here will all apply, and the sense of accomplishment is the best motivator to keep going.  

---  

## 🎉 Final Thoughts  

You now have a **complete, example‑rich reference** for Python 3.14 that mirrors the clean, progressive structure of the JavaScript tutorial site.  

* **Read a chapter** → **run the examples** → **modify them** → **solve the quick‑run exercise**.  
* When you feel comfortable, move on to the next chapter.  
* Use the **“Where to go next”** table as a compass for deeper specialisation.  

Happy coding, and enjoy the expressive, readable, and ever‑evolving world of modern Python! 🚀  