### Installation with Homebrew
# CA Certificates with Homebrew: Using the Trust Store

## Table of Contents

1. [Install](#install)
2. [What the Package Provides](#what-the-package-provides)
3. [Locate the Certificate Bundle](#locate-the-certificate-bundle)
4. [Verify TLS with OpenSSL](#verify-tls-with-openssl)
5. [Use with curl](#use-with-curl)
6. [Use in Scripts and Runtimes](#use-in-scripts-and-runtimes)
7. [Advanced Tips](#advanced-tips)

## Install

```sh
brew install ca-certificates
```

## What the Package Provides

This formula installs and maintains an up-to-date bundle of trusted Certificate Authority roots at a predictable path. Other programs (curl, openssl, Python, node, etc.) can use this file to verify server certificates.

## Locate the Certificate Bundle

Find the canonical path to the bundle:

```sh
echo "$(brew --prefix)/etc/ssl/cert.pem"
```

Example output:

```text
/opt/homebrew/etc/ssl/cert.pem
```

## Verify TLS with OpenSSL

Connect to a server and verify using the Homebrew bundle:

```sh
openssl s_client -connect google.com:443 -CAfile "$(brew --prefix)/etc/ssl/cert.pem" </dev/null
```

Output snippet (success shows Verify return code: 0):

```text
SSL-Session:
    Protocol  : TLSv1.3
    Cipher    : TLS_AES_256_GCM_SHA384
...
    Verify return code: 0 (ok)
```

## Use with curl

Force curl to use this CA bundle explicitly (useful if multiple curl versions are installed):

```sh
curl --cacert "$(brew --prefix)/etc/ssl/cert.pem" https://example.com
```

Expected: HTML output of the page, or a certificate error if the site is untrusted.

## Use in Scripts and Runtimes

Export an environment variable for processes launched from your shell:

```sh
export SSL_CERT_FILE="$(brew --prefix)/etc/ssl/cert.pem"
```

Common alternatives respected by various tools:

- Python requests: set `REQUESTS_CA_BUNDLE`
- Node.js: set `NODE_EXTRA_CA_CERTS`

Examples:

```sh
# Python requests
export REQUESTS_CA_BUNDLE="$(brew --prefix)/etc/ssl/cert.pem"

# Node.js
export NODE_EXTRA_CA_CERTS="$(brew --prefix)/etc/ssl/cert.pem"
```

## Advanced Tips

- If you have a private CA, append it to a copy of `cert.pem` and point your app to that file.
- On macOS, some tools use the System Keychain instead. Pointing explicitly to this bundle ensures consistent behavior across dev machines and CI.
If using CI, export the variable in your job environment before running tools that perform HTTPS requests.