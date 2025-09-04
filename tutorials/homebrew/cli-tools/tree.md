### Installation with Homebrew

```sh
brew install tree
```

-----

### `tree` Command Examples with Output

All examples below are based on this sample directory structure:

**Sample Structure (`my_project/`)**

```
.
├── .env
├── dist
│   └── bundle.js
├── node_modules
│   └── some_lib
│       └── index.js
└── src
    ├── app.js
    └── styles.css
```

-----

#### 1\. Basic Usage

Show the tree structure of the current directory.

**Command:**

```sh
tree
```

**Output:**

```shell
.
├── dist
│   └── bundle.js
├── node_modules
│   └── some_lib
│       └── index.js
└── src
    ├── app.js
    └── styles.css

4 directories, 5 files
```

#### 2\. Control Depth

Limit the directory depth to 2 levels.

**Command:**

```sh
tree -L 2
```

**Output:**

```shell
.
├── dist
│   └── bundle.js
├── node_modules
│   └── some_lib
└── src
    ├── app.js
    └── styles.css

3 directories, 3 files
```

#### 3\. Display Options

Include hidden files (like `.env`).

**Command:**

```sh
tree -a
```

**Output:**

```shell
.
├── .env
├── dist
│   └── bundle.js
├── node_modules
│   └── some_lib
│       └── index.js
└── src
    ├── app.js
    └── styles.css

4 directories, 6 files
```

#### 4\. File Information

Show permissions and human-readable file sizes.

**Command:**

```sh
tree -h -p
```

**Output:**

```shell
.
├── [drwxr-xr-x]  dist
│   └── [-rw-r--r--       512K]  bundle.js
├── [drwxr-xr-x]  node_modules
│   └── [drwxr-xr-x]  some_lib
│       └── [-rw-r--r--       1.2K]  index.js
└── [drwxr-xr-x]  src
    ├── [-rw-r--r--       2.1K]  app.js
    └── [-rw-r--r--        834]  styles.css

4 directories, 5 files
```

#### 5\. Filtering

Ignore a specific directory by name.

**Command:**

```sh
tree -I 'node_modules'
```

**Output:**

```shell
.
├── dist
│   └── bundle.js
└── src
    ├── app.js
    └── styles.css

2 directories, 3 files
```

Show only files matching a pattern.

**Command:**

```sh
tree -P '*.js'
```

**Output:**

```shell
.
├── dist
│   └── bundle.js
├── node_modules
│   └── some_lib
│       └── index.js
└── src
    └── app.js

3 directories, 3 files
```

#### 6\. Output Formats

Output the tree structure as JSON.

**Command:**

```sh
tree -J
```

**Output:**

```json
[
  {"type":"directory","name":".",
   "contents":[
    {"type":"directory","name":"dist",
     "contents":[
      {"type":"file","name":"bundle.js"}
    ]},
    {"type":"directory","name":"node_modules",
     "contents":[
      {"type":"directory","name":"some_lib",
       "contents":[
        {"type":"file","name":"index.js"}
      ]}
    ]},
    {"type":"directory","name":"src",
     "contents":[
      {"type":"file","name":"app.js"},
      {"type":"file","name":"styles.css"}
    ]}
  ]}
]
```

For a complete list of all options, run `man tree`.