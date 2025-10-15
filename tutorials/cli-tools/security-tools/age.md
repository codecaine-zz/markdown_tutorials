### Table of Contents

1.  [What is `age`?](https://www.google.com/search?q=%231-what-is-age)
2.  [Prerequisites](https://www.google.com/search?q=%232-prerequisites)
3.  [Installation](https://www.google.com/search?q=%233-installation)
4.  [Core Workflow: Asymmetric Encryption (Public Keys)](https://www.google.com/search?q=%234-core-workflow-asymmetric-encryption-public-keys)
5.  [Alternative: Symmetric Encryption (Passphrase)](https://www.google.com/search?q=%235-alternative-symmetric-encryption-passphrase)
6.  [Key Features & Examples](https://www.google.com/search?q=%236-key-features--examples)
7.  [Uninstallation](https://www.google.com/search?q=%237-uninstallation)

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
    age -r <alice_pubkey> -r <bob_pubkey> -o shared.zip.age shared.zip
    ```

    Either Alice (with her private key) or Bob (with his private key) can now decrypt `shared.zip.age`.

  * **Using Your Existing SSH Keys**
    One of `age`'s best features is its ability to use SSH keys you already have.

      * **Encrypt** using a recipient's SSH public key with the `-R` (capital R) flag.
        ```bash
        # Assuming the recipient gave you their id_ed25519.pub file
        age -R ~/.ssh/recipient_key.pub -o data.age data.txt
        ```
      * **Decrypt** using your corresponding SSH private key with the `-i` flag.
        ```bash
        age -d -i ~/.ssh/id_ed25519 data.age > data.txt
        ```

  * **Combining Recipients**
    You can even mix and match recipient types. The command below encrypts a file so it can be decrypted by Alice (with her `age` key), Bob (with his SSH key), OR with a passphrase as a backup.

    ```bash
    age -r <alice_pubkey> -R <bob_ssh_pubkey> -p -o project.tar.gz.age project.tar.gz
    ```

### 7\. Uninstallation

If you need to remove `age`, you can do so easily with Homebrew.

```bash
brew uninstall age
```