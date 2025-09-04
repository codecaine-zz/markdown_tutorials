# tldr with Homebrew: Quick Examples for Busy Humans

## Table of Contents

1. [Overview](#overview)
2. [Install](#install)
3. [Basic Usage](#basic-usage)
4. [Pages and Platforms](#pages-and-platforms)
5. [Updating Cache](#updating-cache)
6. [Integrations and Tips](#integrations-and-tips)
7. [Uninstall](#uninstall)

## Overview

`tldr` shows community-maintained, example-driven help pages for commands.

## Install

```bash
brew install tldr
 tldr --version
```

## Basic Usage

```bash
tldr tar
# find examples by keyword
tldr -s archive
# view options
tldr --help
```

## Pages and Platforms

```bash
# Show page for a specific platform
TLDR_PLATFORM=macos tldr sed

# List available pages containing a term
tldr --list | grep git
```

## Updating Cache

```bash
tldr --update
```

## Integrations and Tips

- Combine with fzf:

```bash
tldr --list | fzf | xargs -I{} tldr {}
```

- Use `tldr --markdown` to copy examples into docs

## Uninstall

```bash
brew uninstall tldr
```
