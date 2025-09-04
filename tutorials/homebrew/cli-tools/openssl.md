### Installation and Setup with Homebrew

```sh
brew install openssl@3
```

After installation, you **must** add OpenSSL to your shell's path to use it directly. For `zsh` (the default on modern macOS):

```sh
# Add OpenSSL to your path
echo 'export PATH="/opt/homebrew/opt/openssl@3/bin:$PATH"' >> ~/.zshrc

# For compilers and libraries to find it
export LDFLAGS="-L/opt/homebrew/opt/openssl@3/lib"
export CPPFLAGS="-I/opt/homebrew/opt/openssl@3/include"

# Apply the changes to your current terminal session
source ~/.zshrc
```

-----

### OpenSSL Command Examples

The examples below walk through common tasks like creating a self-signed SSL certificate and encrypting files. These commands will create new files in your current directory.

#### 1\. Generate a Private Key

This is the first step for creating any SSL certificate.

**Command:**

```sh
openssl genpkey -algorithm RSA -out private.key -pkeyopt rsa_keygen_bits:2048
```

**Result:**
This command creates a new 2048-bit RSA private key. A new file named `private.key` will appear in your directory, containing text that starts with `-----BEGIN PRIVATE KEY-----`.

#### 2\. Create a Self-Signed SSL Certificate

For local development (e.g., `https://localhost`), you can generate a certificate signed by your own private key.

**Command:**

```sh
openssl req -x509 -new -nodes -key private.key -sha256 -days 365 -out certificate.crt \
-subj "/C=US/ST=California/L=Cupertino/O=MyOrg/OU=Dev/CN=localhost"
```

**Result:**
This creates a self-signed SSL certificate named `certificate.crt` that is valid for 365 days. It uses the `private.key` you generated and fills in the certificate details non-interactively with the `-subj` flag.

#### 3\. View Certificate Details

Inspect the contents of a certificate file in human-readable text.

**Command:**

```sh
openssl x509 -in certificate.crt -text -noout
```

**Output (Snippet):**

```
Certificate:
    Data:
        Version: 3 (0x2)
        Serial Number:
            1b:97:18:6f:d4:c7:c3:e4:6b:c4:4a:04:78:2f:8c:13
        Signature Algorithm: sha256WithRSAEncryption
        Issuer: C = US, ST = California, L = Cupertino, O = MyOrg, OU = Dev, CN = localhost
        Validity
            Not Before: Sep  4 00:30:00 2025 GMT
            Not After : Sep  4 00:30:00 2026 GMT
        Subject: C = US, ST = California, L = Cupertino, O = MyOrg, OU = Dev, CN = localhost
...
```

#### 4\. Hash a File

Calculate a SHA-256 hash (or other digest) of a file. First, let's create a file to hash.

**Commands:**

```sh
# Create a sample file
echo "hello world" > message.txt

# Calculate the SHA-256 hash
openssl dgst -sha256 message.txt
```

**Output:**

```
SHA256(message.txt)= b94d27b9934d3e08a52e52d7da7dabfac484efe37a5380ee9088f7ace2efcde9
```

#### 5\. Encrypt a File

Encrypt a file using the AES-256-CBC cipher with a password.

**Command:**

```sh
openssl enc -aes-256-cbc -salt -in message.txt -out encrypted.dat -pass pass:MySecretPassword123
```

**Result:**
This encrypts `message.txt` and creates a new binary (unreadable) file named `encrypted.dat`.

#### 6\. Decrypt a File

Decrypt the file using the same algorithm and password.

**Command:**

```sh
openssl enc -d -aes-256-cbc -in encrypted.dat -out decrypted.txt -pass pass:MySecretPassword123
```

**Result:**
This decrypts `encrypted.dat`. A new file, `decrypted.txt`, is created, containing the original content: "hello world".

For a complete list of all commands and options, run `man openssl`.