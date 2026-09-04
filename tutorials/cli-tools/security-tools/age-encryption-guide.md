# Age File Encryption Guide

### Table of Contents

1.  [What is `age`?](#1-what-is-age)
2.  [Prerequisites](#2-prerequisites)
3.  [Installation](#3-installation)
4.  [Core Workflow: Asymmetric Encryption (Public Keys)](#4-core-workflow-asymmetric-encryption-public-keys)
5.  [Alternative: Symmetric Encryption (Passphrase)](#5-alternative-symmetric-encryption-passphrase)
6.  [Key Features & Examples](#6-key-features--examples)
7.  [Uninstallation](#7-uninstallation)

-----

### 1\. What is `age`?

`age` is a simple, modern, and secure encryption tool. It is designed to be a user-friendly and focused alternative to complex tools like GPG/PGP for common file encryption tasks. `age` allows you to encrypt files using a recipient's public key (asymmetric) or with a simple passphrase (symmetric). It is designed to be a small, composable Unix tool that does one thing well.

### 2\. Prerequisites

You must have [Homebrew](https://brew.sh/) installed. You can verify your installation by running:

```bash
brew --version
```

### 3\. Installation

Install `age` and its companion key generation tool `age-keygen` using a single Homebrew command:

```bash
brew install age
```

### 4\. Core Workflow: Asymmetric Encryption (Public Keys)

This is the most common and powerful way to use `age`, allowing you to encrypt a file that only a specific keyholder can decrypt.

  * **Step 1: Generate a Keypair**
    You need an `age` keypair, which consists of a secret private key and a shareable public key.

    ```bash
    age-keygen -o key.txt
    ```

    This command creates a file named `key.txt`.

    **Example `key.txt` contents:**

    ```text
    # created: 2025-09-03T19:30:15-05:00
    # public key: age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz
    AGE-SECRET-KEY-1Q8Z2S...REST_OF_YOUR_PRIVATE_KEY
    ```

      * The `public key` (starting with `age1...`) is what you share with others so they can encrypt files for you.
      * The `AGE-SECRET-KEY-1...` is your **private key**. Keep this file safe and do not share it.

  * **Step 2: Encrypt a File**
    To encrypt a file, you need the recipient's **public key**. Use the `-r` (`--recipient`) flag.
    Let's say you have a file `secrets.txt` and the recipient's public key.

    ```bash
    # Create a dummy file to encrypt
    echo "this is a top secret message" > secrets.txt

    # Encrypt it using the recipient's public key
    age -r age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz -o secrets.txt.age secrets.txt
    ```

    This creates an encrypted file `secrets.txt.age`. The original `secrets.txt` is untouched.

  * **Step 3: Decrypt a File**
    The recipient uses their **private key** file (`key.txt` in our example) to decrypt. Use the `-d` (`--decrypt`) flag and the `-i` (`--identity`) flag to specify your private key.

    ```bash
    age -d -i key.txt secrets.txt.age > secrets_decrypted.txt
    ```

    **Example Output:**
    The command will output the decrypted contents, which we redirect to a new file.

    ```bash
    $ cat secrets_decrypted.txt
    this is a top secret message
    ```

### 5\. Alternative: Symmetric Encryption (Passphrase)

For encrypting files for your own use, a passphrase is often simpler.

  * **Encrypting with a Passphrase**
    Use the `-p` (`--passphrase`) flag. `age` will securely prompt you to create a password.

    ```bash
    age -p -o personal.log.age personal.log
    ```

    **Example Interaction:**

    ```text
    Enter passphrase:
    Confirm passphrase:
    ```

    This creates the encrypted file `personal.log.age`.

  * **Decrypting with a Passphrase**
    Simply use the `-d` flag. `age` is smart enough to know the file is password-protected and will prompt you for it.

    ```bash
    age -d personal.log.age > personal_decrypted.log
    ```

    **Example Interaction:**

    ```text
    Enter passphrase:
    ```

### 6\. Key Features & Examples

  * **Multiple Recipients**
    You can encrypt a file so that multiple people can decrypt it. Simply provide multiple `-r` flags.

    ```bash
    age -r age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz -r age1k5w... -o shared.zip.age shared.zip
    ```

    Either Alice (with her private key) or Bob (with his private key) can now decrypt `shared.zip.age`.

  * **Using Your Existing SSH Keys**
    One of `age`'s best features is its ability to use SSH keys you already have.

      * **Encrypt** using a recipient's SSH public key with the `-R` (capital R) flag.
        ```bash
        # Encrypt with a public SSH key (Ed25519 or RSA)
        age -R ~/.ssh/id_ed25519.pub -o secrets.txt.age secrets.txt
        ```
      * **Decrypt** using your corresponding SSH private key with the `-i` flag.
        ```bash
        age -d -i ~/.ssh/id_ed25519 secrets.txt.age > secrets.txt
        ```

  * **Encrypting Files for a GitHub User**
    You can fetch any GitHub user's public SSH keys and encrypt files for them directly:

    ```bash
    # Encrypt a file using torvalds' public SSH keys from GitHub
    curl -s https://github.com/torvalds.keys | age -R - -o message.txt.age message.txt
    ```

  * **ASCII Armor Output (`-a` / `--armor`)**
    By default, `age` outputs binary data. Use `-a` to produce PEM-formatted ASCII text that can easily be pasted into emails, chat apps, or configuration files:

    ```bash
    # Encrypt to printable text format
    age -a -r age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz -o secrets.txt.asc secrets.txt

    # Decrypt ASCII armor text file
    age -d -i key.txt secrets.txt.asc
    ```

  * **Encrypting Entire Directories on the Fly (Piping with `tar`)**
    Avoid saving unencrypted archive files to disk by streaming directly through `tar`:

    ```bash
    # Compress and encrypt directory 'my_folder' directly to encrypted archive
    tar -czf - my_folder/ | age -r age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz > my_folder.tar.gz.age

    # Decrypt and extract directly to current directory
    age -d -i key.txt my_folder.tar.gz.age | tar -xzf -
    ```

  * **Batch Encrypt All Files in a Directory**
    Encrypt all `.conf` or `.env` files in a folder into individual encrypted copies:

    ```bash
    # Encrypt every .env file in the current directory
    for file in *.env; do
      age -r age1ql3z7h0cfscglarss3sl2dp2xts5uea52p0k0fh5wqg5pj38waesq455wz -o "${file}.age" "$file"
    done
    ```

  * **CI/CD Pipeline Stream Encryption (Passphrase from Environment Variable)**
    Pass credentials securely via stdin in shell scripts or GitHub Actions:

    ```bash
    # Encrypt using password passed via environment variable (no terminal prompt)
    echo "$BACKUP_PASSPHRASE" | age -p -o database.sql.age database.sql

    # Decrypt stream to database restore command
    echo "$BACKUP_PASSPHRASE" | age -d database.sql.age | psql -U postgres mydatabase
    ```

### 7\. Uninstallation

If you need to remove `age`, you can do so easily with Homebrew.

```bash
brew uninstall age
```