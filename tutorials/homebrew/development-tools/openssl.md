## Table of Contents

1. [Installation](#installation)
2. [Basic Commands](#basic-commands)
3. [Generating Keys](#generating-keys)
4. [Certificate Management](#certificate-management)
5. [Encryption/Decryption](#encryptiondecryption)
6. [Hashing and Digital Signatures](#hashing-and-digital-signatures)
7. [SSL/TLS Configuration](#ssltls-configuration)
8. [Advanced Topics](#advanced-topics)

## Installation

### Ubuntu/Debian
```bash
sudo apt update
sudo apt install openssl libssl-dev
```

### CentOS/RHEL/Fedora
```bash
sudo yum install openssl-devel
# or for newer versions:
sudo dnf install openssl-devel
```

### macOS (using Homebrew)
```bash
brew install openssl
```

## Basic Commands

### Check OpenSSL Version
```bash
openssl version
# Output: OpenSSL 3.0.2 15 Mar 2022 (Library: OpenSSL 3.0.2 15 Mar 2022)
```

### List Available Algorithms
```bash
openssl list -digest-algorithms
openssl list -cipher-algorithms
openssl list -key-types
```

### Basic Information About Commands
```bash
# Show help for a specific command
openssl genrsa -help

# Show all available commands
openssl help
```

## Generating Keys

### RSA Key Generation

#### Generate a 2048-bit RSA private key:
```bash
# Without password protection (not recommended for production)
openssl genrsa -out private.key 2048

# With password protection
openssl genrsa -aes256 -out private_encrypted.key 2048

# Prompt for password interactively
openssl genrsa -aes256 -passout pass:my_password -out private_secure.key 2048
```

#### Generate public key from private key:
```bash
# Extract public key from private key
openssl rsa -in private.key -pubout -out public.key

# View the keys in human-readable format
openssl rsa -in private.key -text -noout
openssl rsa -in public.key -pubin -text -noout
```

### Elliptic Curve Key Generation

#### Generate EC key:
```bash
# Generate a 256-bit EC key (secp256r1)
openssl ecparam -genkey -name prime256v1 -out ec_private.key

# Generate EC key with password protection
openssl ecparam -genkey -name secp384r1 -aes-256-cbc -out ec_encrypted.key

# Convert to PKCS#8 format
openssl pkcs8 -in ec_private.key -topk8 -nocrypt -out ec_pkcs8.key
```

### Key Management

#### View key details:
```bash
# Show private key details (no password needed)
openssl rsa -in private.key -text -noout

# Show EC key details
openssl ec -in ec_private.key -text -noout

# Check if a private key matches public key
openssl rsa -in private.key -pubout -out temp.pub
openssl pkeyutl -verify -inkey temp.pub \
    -pubin -in test.txt \
    -sigfile signature.sig
```

## Certificate Management

### Generate Self-Signed Certificate

#### Create certificate with RSA:
```bash
# Generate a self-signed certificate (1 year validity)
openssl req -new -x509 -key private.key -out certificate.crt \
    -days 365 \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

# Interactive version (recommended for production)
openssl req -newkey rsa:2048 -keyout server.key \
    -x509 -nodes -out server.crt \
    -days 365

# With more detailed configuration
openssl req -newkey rsa:2048 \
    -keyout server.key \
    -x509 \
    -nodes \
    -out server.crt \
    -days 365 \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"
```

#### Create Certificate with SAN (Subject Alternative Name):
```bash
# Create a configuration file for SAN
cat > cert.conf << EOF
[req]
default_bits = 2048
prompt = no
default_md = sha256
distinguished_name = dn
req_extensions = v3_req

[dn]
C = US
ST = State
L = City  
O = Organization
CN = localhost

[v3_req]
basicConstraints = CA:FALSE
keyUsage = nonRepudiation, digitalSignature, keyEncipherment
subjectAltName = @alt_names

[alt_names]
DNS.1 = localhost
DNS.2 = example.com
DNS.3 = www.example.com
IP.1 = 127.0.0.1
EOF

# Generate certificate with SAN
openssl req -newkey rsa:2048 \
    -keyout server.key \
    -x509 \
    -nodes \
    -out server.crt \
    -days 365 \
    -config cert.conf
```

### Certificate Signing Request (CSR)

#### Generate CSR:
```bash
# Generate private key and CSR together
openssl req -newkey rsa:2048 \
    -keyout mydomain.key \
    -nodes \
    -out mydomain.csr

# Generate CSR from existing private key
openssl req -new -key server.key \
    -out server.csr

# Specify details in command line
openssl req -newkey rsa:2048 \
    -keyout server.key \
    -out server.csr \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=example.com"
```

### Certificate Verification and Inspection

#### View certificate details:
```bash
# Display certificate information
openssl x509 -in certificate.crt -text -noout

# Check certificate validity period
openssl x509 -in certificate.crt -noout -dates

# Extract public key from certificate
openssl x509 -in certificate.crt -pubkey -noout > pubkey.pem

# Verify certificate signature
openssl verify certificate.crt

# Check if a certificate is valid for a domain
openssl s_client -connect example.com:443 -servername example.com 2>/dev/null | openssl x509 -text -noout | grep "Subject Alternative Name"
```

#### Certificate Chain Verification:
```bash
# Verify certificate against CA chain
openssl verify -CAfile ca.crt certificate.crt

# Check intermediate certificates
openssl crl2pkcs7 -nocrl -certfile cert.pem -out cert.p7b
```

## Encryption/Decryption

### Symmetric Encryption (AES)

#### Encrypt files with AES:
```bash
# AES-256 encryption (CBC mode)
openssl enc -aes-256-cbc -in plaintext.txt -out encrypted.enc

# With password prompt
openssl enc -aes-256-cbc -salt -in plaintext.txt -out encrypted.enc

# With explicit password
openssl enc -aes-256-cbc -pass pass:my_secret_password \
    -in plaintext.txt \
    -out encrypted.enc

# Encrypt with base64 output (human readable)
openssl enc -aes-256-cbc -a -salt \
    -in plaintext.txt \
    -out encrypted.b64
```

#### Decrypt files:
```bash
# Decrypt with password prompt
openssl enc -aes-256-cbc -d -in encrypted.enc

# Decrypt with explicit password
openssl enc -aes-256-cbc -d -pass pass:my_secret_password \
    -in encrypted.enc \
    -out decrypted.txt

# Decrypt base64 encoded file
openssl enc -aes-256-cbc -d -a \
    -in encrypted.b64 \
    -out decrypted.txt
```

### Asymmetric Encryption (RSA)

#### Encrypt with public key:
```bash
# Generate a test message
echo "Hello World" > message.txt

# Encrypt using public key (no padding)
openssl rsautl -encrypt -inkey private.key \
    -pubin -in message.txt \
    -out encrypted_message.bin

# Encrypt using public key with PKCS1-OAEP padding
openssl rsautl -encrypt -inkey private.key \
    -pubin -in message.txt \
    -out encrypted_message_oaep.bin \
    -pkcs

# Decrypt with private key (no padding)
openssl rsautl -decrypt -inkey private.key \
    -in encrypted_message.bin \
    -out decrypted_message.txt

# Decrypt with private key using PKCS1-OAEP
openssl rsautl -decrypt -inkey private.key \
    -in encrypted_message_oaep.bin \
    -out decrypted_message_oaep.txt \
    -pkcs
```

### Hybrid Encryption Example (RSA + AES)

```bash
# Create a hybrid encryption script
cat > hybrid_encrypt.sh << 'EOF'
#!/bin/bash

if [ $# -ne 2 ]; then
    echo "Usage: $0 <input_file> <public_key>"
    exit 1
fi

INPUT_FILE=$1
PUBLIC_KEY=$2

# Generate random AES key (32 bytes = 256 bits)
openssl rand -hex 32 > aes_key.txt

# Encrypt file with AES-256
openssl enc -aes-256-cbc -a -salt \
    -in "$INPUT_FILE" \
    -out "${INPUT_FILE}.enc" \
    -pass file:aes_key.txt

# Encrypt the AES key with RSA public key
openssl rsautl -encrypt -pubin \
    -in aes_key.txt \
    -inkey "$PUBLIC_KEY" \
    -out "${INPUT_FILE}.aes_key.enc"

echo "Encryption complete!"
echo "Encrypted file: ${INPUT_FILE}.enc"
echo "Encrypted AES key: ${INPUT_FILE}.aes_key.enc"
EOF

chmod +x hybrid_encrypt.sh
```

## Digital Signatures

### RSA Signature Generation and Verification

#### Generate signature:
```bash
# Create a test message
echo "This is a signed document" > document.txt

# Generate RSA private key (if needed)
openssl genrsa -out private.key 2048

# Generate public key from private key  
openssl rsa -in private.key -pubout -out public.key

# Sign the document with RSA-SHA256
openssl dgst -sha256 -sign private.key \
    -out document.sig \
    document.txt

# Verify signature
openssl dgst -sha256 -verify public.key \
    -signature document.sig \
    document.txt

# Sign and verify without intermediate files
echo "Message to sign" | openssl dgst -sha256 -sign private.key | openssl enc -base64 > message.sig
```

### Certificate-based Signing

#### Create a simple CA:
```bash
# Generate CA private key
openssl genrsa -out ca.key 2048

# Generate self-signed CA certificate  
openssl req -new -x509 \
    -key ca.key \
    -out ca.crt \
    -days 365 \
    -subj "/C=US/ST=State/L=City/O=MyCA/CN=myca.example.com"

# Create a server key and CSR
openssl genrsa -out server.key 2048
openssl req -new \
    -key server.key \
    -out server.csr

# Sign the certificate with CA
openssl x509 -req \
    -in server.csr \
    -CA ca.crt \
    -CAkey ca.key \
    -CAcreateserial \
    -out server.crt \
    -days 365
```

## Python Integration Example

```python
import subprocess
import os

def encrypt_file_aes(input_file, output_file, password):
    """Encrypt file using AES-256"""
    cmd = [
        'openssl', 'enc', '-aes-256-cbc', '-a', '-salt',
        '-in', input_file,
        '-out', output_file,
        '-pass', f'pass:{password}'
    ]
    
    try:
        result = subprocess.run(cmd, capture_output=True, text=True)
        if result.returncode == 0:
            return True
        else:
            print(f"Error: {result.stderr}")
            return False
    except Exception as e:
        print(f"Exception: {e}")
        return False

def decrypt_file_aes(input_file, output_file, password):
    """Decrypt file using AES-256"""
    cmd = [
        'openssl', 'enc', '-aes-256-cbc', '-d', '-a',
        '-in', input_file,
        '-out', output_file,
        '-pass', f'pass:{password}'
    ]
    
    try:
        result = subprocess.run(cmd, capture_output=True, text=True)
        if result.returncode == 0:
            return True
        else:
            print(f"Error: {result.stderr}")
            return False
    except Exception as e:
        print(f"Exception: {e}")
        return False

def generate_rsa_keys(private_key_path, public_key_path, key_size=2048):
    """Generate RSA key pair"""
    # Generate private key
    subprocess.run([
        'openssl', 'genrsa',
        '-out', private_key_path,
        str(key_size)
    ])
    
    # Generate public key from private key
    subprocess.run([
        'openssl', 'rsa',
        '-in', private_key_path,
        '-pubout',
        '-out', public_key_path
    ])

# Example usage:
if __name__ == "__main__":
    # Test encryption/decryption
    with open('test.txt', 'w') as f:
        f.write("This is a test message for encryption.")
    
    if encrypt_file_aes('test.txt', 'encrypted.enc', 'mypassword'):
        print("Encryption successful")
        
        if decrypt_file_aes('encrypted.enc', 'decrypted.txt', 'mypassword'):
            print("Decryption successful")
            with open('decrypted.txt', 'r') as f:
                print(f"Decrypted content: {f.read()}")
```

This comprehensive guide covers the major aspects of OpenSSL usage for security operations including encryption, decryption, signing, verification, and certificate management. The examples demonstrate practical implementations that can be used directly in production environments or adapted for specific requirements.

You'll want to adjust key sizes, algorithms, and paths according to your specific security requirements and compliance standards.
```

