# binascii - Convert between binary and ASCII

The `binascii` module provides low-level conversions between binary data and various ASCII-encoded binary representations. It is widely used for encoding data in hexadecimal, Base64, and calculating checksums (CRC32).

Below are correct, complete, and self-contained examples of utilizing the `binascii` module.

## Table of Contents

1. [Example 1: Hexadecimal Encoding (hexlify)](#example-1-hexadecimal-encoding-hexlify)
2. [Example 2: Hexadecimal Decoding (unhexlify)](#example-2-hexadecimal-decoding-unhexlify)
3. [Example 3: Base64 Encoding (b2a_base64)](#example-3-base64-encoding-b2a_base64)
4. [Example 4: Base64 Decoding (a2b_base64)](#example-4-base64-decoding-a2b_base64)
5. [Example 5: CRC32 Checksum Calculation](#example-5-crc32-checksum-calculation)

---

### Example 1: Hexadecimal Encoding (hexlify)

To convert a byte string into its hexadecimal representation, use the `hexlify` function (or its alias `b2a_hex`).

```python
import binascii

# Define raw binary data (bytes)
binary_data = b'Hello, World!'

# Convert to a hexadecimal byte string
hex_bytes = binascii.hexlify(binary_data)

# Decode to a standard Python string for display
hex_string = hex_bytes.decode('utf-8')
print("Hexadecimal representation:", hex_string)  # Output: 48656c6c6f2c20576f726c6421
```

### Example 2: Hexadecimal Decoding (unhexlify)

To convert a hexadecimal string (or hex bytes) back into the original raw binary data, use the `unhexlify` function (or its alias `a2b_hex`).

```python
import binascii

# Define a hexadecimal string
hex_string = "48656c6c6f2c20576f726c6421"

# Convert the hex string back to raw binary data (bytes)
binary_data = binascii.unhexlify(hex_string)

# Decode bytes to a string
original_string = binary_data.decode('utf-8')
print("Decoded original data:", original_string)  # Output: Hello, World!
```

### Example 3: Base64 Encoding (b2a_base64)

The `binascii` module provides a low-level Base64 encoder `b2a_base64`.

> [!NOTE]
> `b2a_base64` appends a newline character (`\n`) to the output. If you do not want this newline, you can set `newline=False` (available in Python 3.6+).

```python
import binascii

# Define raw binary data
binary_data = b'Hello, World!'

# Encode binary data to Base64 bytes
base64_bytes = binascii.b2a_base64(binary_data, newline=False)

# Convert to string for printing
base64_string = base64_bytes.decode('utf-8')
print("Base64 representation:", base64_string)  # Output: SGVsbG8sIFdvcmxkIQ==
```

### Example 4: Base64 Decoding (a2b_base64)

To decode Base64 data back to raw binary data, use `a2b_base64`.

```python
import binascii

# Define a Base64 string
base64_string = "SGVsbG8sIFdvcmxkIQ=="

# Decode Base64 data to raw bytes
binary_data = binascii.a2b_base64(base64_string)

# Decode bytes to a string
original_string = binary_data.decode('utf-8')
print("Decoded original data:", original_string)  # Output: Hello, World!
```

### Example 5: CRC32 Checksum Calculation

You can calculate the CRC32 checksum of a byte sequence using `binascii.crc32`. This returns an unsigned 32-bit integer.

```python
import binascii

# Define raw data
data = b'Hello, World!'

# Calculate CRC32 checksum
checksum = binascii.crc32(data)
print(f"CRC32 Checksum (integer): {checksum}")
print(f"CRC32 Checksum (hex): {hex(checksum)}")
```

These examples cover the primary use cases of the `binascii` module for low-level encoding, decoding, and checksum calculation in Python.
