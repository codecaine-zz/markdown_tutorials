# ripgrep (rg) with Homebrew: Fast Searching

## Table of Contents

1. [Installation](#installation-with-homebrew)
2. [Command Examples](#ripgrep-rg-command-examples-with-output)
  - [Basic Search](#1-basic-search)
  - [Case-Insensitive Search](#2-case-insensitive-search)
  - [Search by File Type](#3-search-by-file-type)
  - [List Files with Matches](#4-list-files-with-matches)
  - [Invert Search Results](#5-invert-search-results)
  - [Search Ignored Files](#6-search-ignored-files)
  - [Show Context](#7-show-context)
  - [Count Matches](#8-count-matches)

### Installation with Homebrew

```sh
brew install ripgrep
```

-----

### ripgrep (`rg`) Command Examples with Output

All examples below are based on a sample directory with the following file contents:

**Sample Structure (`search_project/`)**

  * `README.md`:
    ```
    Project setup and configuration.
    ```
  * `.gitignore`:
    ```
    node_modules
    ```
  * `src/app.js`:
    ```javascript
    const config = require('./config');
    function start() {
      // start server
    }
    ```
  * `src/config.js`:
    ```javascript
    const config = { port: 3000 };
    module.exports = config;
    ```
  * `node_modules/some_lib/index.js`:
    ```javascript
    console.log('library code');
    ```

-----

#### 1\. Basic Search

Recursively search for a pattern. `rg` automatically respects `.gitignore`.

**Command:**

```sh
rg config
```

**Output:**

```
src/config.js
1:const config = { port: 3000 };
2:module.exports = config;

src/app.js
1:const config = require('./config');
```

#### 2\. Case-Insensitive Search

Use the `-i` flag to match case-insensitively.

**Command:**

```sh
rg -i project
```

**Output:**

```
README.md
1:Project setup and configuration.
```

#### 3\. Search by File Type

Use the `-t` flag to limit the search to specific file types (e.g., `js`, `py`, `md`).

**Command:**

```sh
rg -tjs 'start'
```

**Output:**

```
src/app.js
2:function start() {
3:  // start server
```

#### 4\. List Files with Matches

Use the `-l` flag to print only the names of files containing matches.

**Command:**

```sh
rg -l config
```

**Output:**

```
src/config.js
src/app.js
```

#### 5\. Invert Search Results

Use the `-v` flag to show lines that **do not** match a pattern.

**Command:**

```sh
rg -v 'start' src/app.js
```

**Output:**

```
src/app.js
1:const config = require('./config');
4:}
```

#### 6\. Search Ignored Files

Use the `-u` flag to disable ignore rules (`.gitignore`, etc.). Use it twice (`-uu`) to also include hidden files.

**Command:**

```sh
rg -uu 'library'
```

**Output:**

```
node_modules/some_lib/index.js
1:console.log('library code');
```

#### 7\. Show Context

Use the `-C` (or `--context`) flag to show lines before and after the match.

**Command:**

```sh
rg -C 1 'start()'
```

**Output:**

```
src/app.js
1:const config = require('./config');
2:function start() {
3:  // start server
```

#### 8\. Count Matches

Use the `-c` flag to get a count of matches per file.

**Command:**

```sh
rg -c config
```

**Output:**

```
src/config.js:2
src/app.js:1
```

For a complete list of all options, run `man rg` or `rg --help`.