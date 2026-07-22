# yq Portable YAML Processor Guide

`yq` is a lightweight, portable command-line YAML, JSON, XML, CSV, and TOML processor. Inspired by `jq`, `yq` allows you to evaluate expressions, query nested attributes, update values in-place, and transcode data between YAML, JSON, TOML, and XML formats cleanly.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview--prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Querying YAML Files](#querying-yaml-files)
4. [In-Place Value Modification (`-i`)](#in-place-value-modification--i)
5. [Format Conversion (YAML $\leftrightarrow$ JSON $\leftrightarrow$ TOML $\leftrightarrow$ XML)](#format-conversion-yaml-%E2%86%94-json-%E2%86%94-toml-%E2%86%94-xml)
6. [Merging YAML Documents](#merging-yaml-documents)
7. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

`yq` (Mike Farah's Go implementation) processes structured data files without breaking comments or document formatting.

---

## ⚙️ Installation via Homebrew

```bash
# Install yq on macOS
brew install yq

# Verify installation
yq --version
```

---

## 🚀 Querying YAML Files

Given a sample `docker-compose.yml`:

```yaml
version: '3.8'
services:
  web:
    image: nginx:latest
    ports:
      - "8080:80"
  db:
    image: postgres:15
    environment:
      POSTGRES_PASSWORD: secretpassword
```

### 1. Extract Specific Fields
```bash
# Extract web service image name
yq '.services.web.image' docker-compose.yml
# Output: nginx:latest

# Extract first port mapping
yq '.services.web.ports[0]' docker-compose.yml
# Output: 8080:80
```

### 2. List Keys or Array Elements
```bash
# List top-level service names
yq '.services | keys' docker-compose.yml
# Output:
# - web
# - db
```

---

## 📝 In-Place Value Modification (`-i`)

Update values directly inside configuration files without manually opening text editors.

```bash
# Update PostgreSQL password in-place (-i)
yq -i '.services.db.environment.POSTGRES_PASSWORD = "new_secure_password"' docker-compose.yml

# Add a new environment variable
yq -i '.services.web.environment.NODE_ENV = "production"' docker-compose.yml

# Delete an attribute
yq -i 'del(.services.db.environment.POSTGRES_PASSWORD)' docker-compose.yml
```

---

## 🔄 Format Conversion (YAML $\leftrightarrow$ JSON $\leftrightarrow$ TOML $\leftrightarrow$ XML)

Convert seamlessly between serialization formats:

```bash
# Convert YAML file to formatted JSON output (-o=json)
yq -o=json docker-compose.yml

# Convert JSON to YAML (-p=json -o=yaml)
cat package.json | yq -p=json -o=yaml

# Convert YAML to TOML (-o=toml)
yq -o=toml config.yaml

# Convert XML to JSON (-p=xml -o=json)
yq -p=xml -o=json pom.xml
```

---

## 🔀 Merging YAML Documents

Merge multiple configuration files (e.g. `base.yaml` and `override.yaml`).

```bash
# Deep merge override.yaml into base.yaml
yq eval-all '. as $item ireduce ({}; . * $item)' base.yaml override.yaml
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Read value | `yq '.path.to.key' config.yml` |
| Modify in-place | `yq -i '.key = "new_val"' config.yml` |
| Delete key | `yq -i 'del(.key)' config.yml` |
| YAML to JSON | `yq -o=json config.yml` |
| JSON to YAML | `yq -p=json -o=yaml config.json` |
| YAML to TOML | `yq -o=toml config.yml` |
| List Keys | `yq '. | keys' config.yml` |
