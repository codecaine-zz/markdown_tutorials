### Table of Contents

1.  [What is `sd`?](https://www.google.com/search?q=%231-what-is-sd)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Basic Usage & Output](https://www.google.com/search?q=%234-basic-usage--output)
5.  [Key Features & Examples](https://www.google.com/search?q=%235-key-features--examples)
6.  [Practical Scenarios](https://www.google.com/search?q=%236-practical-scenarios)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

-----

### 1\. What is `sd`?

`sd` is a fast and intuitive find-and-replace command-line tool. It aims to be a simpler, more user-friendly alternative to traditional tools like `sed` for the vast majority of day-to-day substitution tasks. Its core strengths are its simple syntax, use of regular expressions by default, and consistent behavior across platforms.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `sd` using a single Homebrew command:

```bash
brew install sd
```

### 4\. Basic Usage & Output

The syntax for `sd` is refreshingly simple: `sd <find> <replace>`. It works on piped data (stdin) or directly on files.

  * **Using with Pipes (stdin/stdout):**
    You can pipe the output of any command into `sd` for quick replacements. `sd` replaces all occurrences by default.

    ```bash
    echo "the quick brown fox jumps over the lazy brown dog" | sd "brown" "red"
    ```

    **Example Output:**

    ```text
    the quick red fox jumps over the lazy red dog
    ```

  * **Replacing in Files:**
    When you provide a filename, `sd` performs the replacement **in-place**, modifying the file directly.

    **File `config.txt` (Before):**

    ```text
    # config.txt
    ENVIRONMENT=development
    API_URL=http://localhost:8080
    ```

    **Command:**

    ```bash
    sd 'development' 'production' config.txt
    ```

    **File `config.txt` (After):**

    ```text
    # config.txt
    ENVIRONMENT=production
    API_URL=http://localhost:8080
    ```

### 5\. Key Features & Examples

  * **Regex Capture Groups:**
    `sd` uses regular expressions in the `find` argument, and you can reference capture groups in the `replace` argument with `$1`, `$2`, etc. This is incredibly powerful for reformatting text.

    **Command:**

    ```bash
    echo "Date: 2025-09-03" | sd '(\d{4})-(\d{2})-(\d{2})' '$2/$3/$1'
    ```

    **Example Output:**

    ```text
    Date: 09/03/2025
    ```

  * **String-Literal Search:**
    If your search pattern contains special regex characters (like `.`, `*`, `+`) that you want to match literally, use the `-s` (`--string-literal`) flag.

    **Command (Regex Mode - Incorrect for this goal):**

    ```bash
    echo "find.this.string" | sd '.' '-'
    ```

    **Output (Regex Mode):**

    ```text
    ------------------
    ```

    **Command (String-Literal Mode - Correct):**

    ```bash
    echo "find.this.string" | sd -s '.' '-'
    ```

    **Example Output (String-Literal Mode):**

    ```text
    find-this-string
    ```

  * **Multiple Files:**
    You can pass multiple files to `sd` to perform the same replacement across all of them.

    ```bash
    sd 'old_function_name' 'new_function_name' src/main.js src/utils.js
    ```

### 6\. Practical Scenarios

  * **Scenario 1: Refactor a CSS class name across multiple files.**
    Imagine you want to rename `.btn-primary` to `.button-main` in all your CSS files.

    **Command:**

    ```bash
    sd '.btn-primary' '.button-main' styles/main.css styles/forms.css
    ```

    This command will open both files, find all occurrences of the old class name, and replace them with the new one.

  * **Scenario 2: Reformat log entries.**
    Suppose you have a log file with lines like `[INFO] user=alice action=login`. You want to convert this to JSON format.

    **File `app.log`:**

    ```text
    [INFO] user=alice action=login
    [ERROR] user=bob action=delete_post
    ```

    **Command:**

    ```bash
    sd '\[(\w+)\] user=(\w+) action=(\w+)' '{"level": "$1", "user": "$2", "action": "$3"}' app.log
    ```

    **File `app.log` (After):**

    ```text
    {"level": "INFO", "user": "alice", "action": "login"}
    {"level": "ERROR", "user": "bob", "action": "delete_post"}
    ```

### 7\. Uninstallation

If you need to remove `sd`, you can do so easily with Homebrew.

```bash
brew uninstall sd
```