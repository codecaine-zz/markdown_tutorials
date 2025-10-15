### Installation with Homebrew

```sh
brew install ripgrep-all
```

-----

### ripgrep-all (`rga`) Command Examples with Output

`rga`'s power is searching inside various file formats. The examples below use a directory with diverse file types.

**Sample Structure (`search_plus/`)**

  * `document.pdf`: A PDF file containing the text "Annual Report 2025".
  * `archive.zip`: A zip file which contains `log.txt`.
      * `log.txt` (inside the zip): Contains the line "ERROR: Connection failed".
  * `database.sqlite3`: An SQLite database with a `users` table, containing a user named "Alice".
  * `video.mp4`: A video file with subtitles or metadata containing the word "Interview".
  * `src/app.js`: A plain text file with the code `const user = 'Alice';`.

-----

#### 1\. Universal Search

Search for a string across all supported file types automatically.

**Command:**

```sh
rga "Report"
```

**Output:**

```
document.pdf
1:Annual Report 2025
```

#### 2\. Search Inside Archives

`rga` seamlessly searches inside zip, tar, and other archives.

**Command:**

```sh
rga "ERROR"
```

**Output:**

```
archive.zip/log.txt
1:ERROR: Connection failed
```

#### 3\. Search Inside Databases and Code

Find a pattern that exists in both a database and a regular text file.

**Command:**

```sh
rga Alice
```

**Output:**

```
database.sqlite3
users.name: Alice

src/app.js
1:const user = 'Alice';
```

#### 4\. Search Inside Media Files

Find text in video subtitles, metadata, or audio transcripts.

**Command:**

```sh
rga Interview
```

**Output:**

```
video.mp4
1:Interview
```

#### 5\. List Files with Matches

Use the `-l` flag to see which files and sub-files contain a match.

**Command:**

```sh
rga -l Alice
```

**Output:**

```
database.sqlite3
src/app.js
```

#### 6\. Case-Insensitive Search

Standard `ripgrep` flags like `-i` work as expected across all file types.

**Command:**

```sh
rga -i "annual report"
```

**Output:**

```
document.pdf
1:Annual Report 2025
```

#### 7\. Count Matches

Use the `-c` flag to get a count of matches per file.

**Command:**

```sh
rga -c "Alice"
```

**Output:**

```
database.sqlite3:1
src/app.js:1
```

For a complete list of all options, run `rga --help`.