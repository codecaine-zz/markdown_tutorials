# argparse - Parser for command-line options, arguments and subcommands

## Overview

The `argparse` module provides a powerful and flexible framework for building command-line interfaces. It automatically generates help messages, usage text, and performs type conversion and validation.

## Table of Contents

1. [Basic Argument Parsing](#basic-argument-parsing)
2. [Optional Arguments](#optional-arguments)
3. [Positional Arguments](#positional-arguments)
4. [Type Conversion](#type-conversion)
5. [Multiple Values](#multiple-values-nargs)
6. [Subcommands](#subcommands)
7. [Mutually Exclusive Groups](#mutually-exclusive-groups)
8. [Help and Usage](#help-and-usage)
9. [Advanced Features](#advanced-features)

---

## Basic Argument Parsing

### Example 1: Simple Parser with Optional Arguments

```python
import argparse

# Create a parser object
parser = argparse.ArgumentParser(
    description='A simple greeting program'
)

# Add optional arguments
parser.add_argument(
    '--name',
    type=str,
    help='Name of the person to greet'
)

parser.add_argument(
    '-v', '--verbose',
    action='store_true',
    help='Enable verbose output'
)

# Parse arguments
args = parser.parse_args()

# Use the parsed arguments
if args.name:
    print(f'Hello, {args.name}!')
else:
    print('Hello, World!')

if args.verbose:
    print('Verbose mode is enabled.')
```

**Usage:**
```bash
python script.py --name Alice
python script.py --name Bob --verbose
python script.py -v
```

---

## Optional Arguments

### Example 2: Default Values and Required Arguments

```python
import argparse

parser = argparse.ArgumentParser(description='File processor')

# Optional with default value
parser.add_argument(
    '--output',
    type=str,
    default='output.txt',
    help='Output file (default: output.txt)'
)

# Required optional argument
parser.add_argument(
    '--input',
    type=str,
    required=True,
    help='Input file (required)'
)

# Boolean flags
parser.add_argument(
    '--force', '-f',
    action='store_true',
    help='Force overwrite existing files'
)

parser.add_argument(
    '--no-backup',
    action='store_false',
    dest='backup',
    help='Do not create backup files'
)

args = parser.parse_args()

print(f'Input: {args.input}')
print(f'Output: {args.output}')
print(f'Force: {args.force}')
print(f'Backup: {args.backup}')
```

**Usage:**
```bash
python script.py --input data.txt
python script.py --input data.txt --output results.txt --force
```

---

## Positional Arguments

### Example 3: Required Positional Arguments

```python
import argparse

parser = argparse.ArgumentParser(description='Calculator')

# Positional arguments (required by default)
parser.add_argument(
    'first',
    type=float,
    help='First number'
)

parser.add_argument(
    'second',
    type=float,
    help='Second number'
)

parser.add_argument(
    'operation',
    type=str,
    choices=['add', 'subtract', 'multiply', 'divide'],
    help='Operation to perform'
)

args = parser.parse_args()

if args.operation == 'add':
    result = args.first + args.second
elif args.operation == 'subtract':
    result = args.first - args.second
elif args.operation == 'multiply':
    result = args.first * args.second
elif args.operation == 'divide':
    if args.second == 0:
        print('Error: Cannot divide by zero')
        exit(1)
    result = args.first / args.second

print(f'{args.first} {args.operation} {args.second} = {result}')
```

**Usage:**
```bash
python script.py 10 5 add           # Output: 10 add 5 = 15.0
python script.py 20 3 multiply      # Output: 20 multiply 3 = 60.0
```

---

## Type Conversion

### Example 4: Custom Type Conversion

```python
import argparse
from pathlib import Path

def positive_integer(value):
    """Ensure value is a positive integer"""
    try:
        ivalue = int(value)
        if ivalue <= 0:
            raise ValueError(f'{value} is not a positive integer')
        return ivalue
    except ValueError as e:
        raise argparse.ArgumentTypeError(f'Invalid positive integer: {e}')

def file_path(value):
    """Ensure path exists and is readable"""
    p = Path(value)
    if not p.exists():
        raise argparse.ArgumentTypeError(f'File not found: {value}')
    if not p.is_file():
        raise argparse.ArgumentTypeError(f'Not a file: {value}')
    return p

parser = argparse.ArgumentParser(description='Data processor')

parser.add_argument(
    'input_file',
    type=file_path,
    help='Input file (must exist)'
)

parser.add_argument(
    '--threads',
    type=positive_integer,
    default=1,
    help='Number of threads (must be positive)'
)

args = parser.parse_args()

print(f'Processing: {args.input_file}')
print(f'Threads: {args.threads}')
```

**Usage:**
```bash
python script.py data.csv --threads 4
```

---

## Multiple Values (nargs)

### Example 5: Accepting Multiple Arguments

```python
import argparse

parser = argparse.ArgumentParser(description='Sum calculator')

# Accept exactly N arguments
parser.add_argument(
    '--coordinates',
    type=float,
    nargs=2,
    help='X and Y coordinates'
)

# Accept 1 or more arguments
parser.add_argument(
    'numbers',
    type=float,
    nargs='+',
    help='Numbers to sum (at least 1)'
)

# Accept 0 or more arguments
parser.add_argument(
    '--tags',
    type=str,
    nargs='*',
    help='Optional tags'
)

# Accept any number of arguments
parser.add_argument(
    '--values',
    type=int,
    nargs=argparse.REMAINDER,
    help='Remaining values'
)

args = parser.parse_args()

if args.numbers:
    total = sum(args.numbers)
    print(f'Sum of {args.numbers} = {total}')

if args.coordinates:
    print(f'Coordinates: x={args.coordinates[0]}, y={args.coordinates[1]}')

if args.tags:
    print(f'Tags: {args.tags}')
```

**Usage:**
```bash
python script.py 1 2 3 4 5                    # Sum: 15
python script.py 10 20 --tags python coding  # With tags
```

---

## Subcommands

### Example 6: Basic Subcommands

```python
import argparse

parser = argparse.ArgumentParser(description='Git-like tool')

# Create subparsers
subparsers = parser.add_subparsers(
    dest='command',
    help='Available commands'
)

# Add subcommand
add_parser = subparsers.add_parser(
    'add',
    help='Add files'
)
add_parser.add_argument(
    'files',
    nargs='+',
    help='Files to add'
)

# Commit subcommand
commit_parser = subparsers.add_parser(
    'commit',
    help='Commit changes'
)
commit_parser.add_argument(
    '-m', '--message',
    type=str,
    required=True,
    help='Commit message'
)

# Push subcommand
push_parser = subparsers.add_parser(
    'push',
    help='Push changes'
)
push_parser.add_argument(
    'remote',
    type=str,
    default='origin',
    help='Remote name'
)

args = parser.parse_args()

if args.command == 'add':
    print(f'Adding files: {args.files}')
elif args.command == 'commit':
    print(f'Committing with message: {args.message}')
elif args.command == 'push':
    print(f'Pushing to remote: {args.remote}')
else:
    parser.print_help()
```

**Usage:**
```bash
python script.py add file1.txt file2.txt
python script.py commit -m "Initial commit"
python script.py push origin
python script.py push                    # Uses default 'origin'
```

---

## Mutually Exclusive Groups

### Example 7: Mutually Exclusive Options

```python
import argparse

parser = argparse.ArgumentParser(description='File converter')

# Create mutually exclusive group
format_group = parser.add_mutually_exclusive_group(required=True)

format_group.add_argument(
    '--to-json',
    action='store_true',
    help='Convert to JSON'
)

format_group.add_argument(
    '--to-csv',
    action='store_true',
    help='Convert to CSV'
)

format_group.add_argument(
    '--to-xml',
    action='store_true',
    help='Convert to XML'
)

# Regular argument
parser.add_argument(
    'input_file',
    type=str,
    help='Input file'
)

args = parser.parse_args()

print(f'Converting {args.input_file} to: ', end='')
if args.to_json:
    print('JSON')
elif args.to_csv:
    print('CSV')
elif args.to_xml:
    print('XML')
```

**Usage:**
```bash
python script.py data.txt --to-json        # Valid
python script.py data.txt --to-csv         # Valid
python script.py data.txt --to-json --to-csv  # Error: mutually exclusive
```

---

## Help and Usage

### Example 8: Custom Help and Descriptions

```python
import argparse

parser = argparse.ArgumentParser(
    prog='data-processor',
    description='Process data files with various options',
    epilog='Examples:\n  %(prog)s input.txt --format json\n  %(prog)s input.txt --validate',
    formatter_class=argparse.RawDescriptionHelpFormatter
)

# Create argument groups for better organization
input_group = parser.add_argument_group('input options')
input_group.add_argument(
    'input_file',
    help='Input data file'
)
input_group.add_argument(
    '--encoding',
    type=str,
    default='utf-8',
    help='File encoding (default: utf-8)'
)

output_group = parser.add_argument_group('output options')
output_group.add_argument(
    '-o', '--output',
    type=str,
    help='Output file (default: stdout)'
)
output_group.add_argument(
    '--format',
    type=str,
    choices=['json', 'csv', 'xml'],
    default='json',
    help='Output format (default: json)'
)

processing_group = parser.add_argument_group('processing options')
processing_group.add_argument(
    '--validate',
    action='store_true',
    help='Validate data'
)
processing_group.add_argument(
    '--compress',
    action='store_true',
    help='Compress output'
)

args = parser.parse_args()

print(f'Input: {args.input_file}')
print(f'Output format: {args.format}')
print(f'Validate: {args.validate}')
print(f'Compress: {args.compress}')
```

**Usage:**
```bash
python script.py --help
```

---

## Advanced Features

### Example 9: Nested Subcommands with Shared Arguments

```python
import argparse

parser = argparse.ArgumentParser(description='Database manager')

# Global arguments available to all subcommands
parser.add_argument(
    '--config',
    type=str,
    default='config.ini',
    help='Configuration file'
)

subparsers = parser.add_subparsers(dest='command', help='Commands')

# Database command with sub-subcommands
db_parser = subparsers.add_parser('db', help='Database operations')
db_subparsers = db_parser.add_subparsers(dest='db_command')

migrate_parser = db_subparsers.add_parser('migrate', help='Run migrations')
migrate_parser.add_argument('version', help='Target version')

backup_parser = db_subparsers.add_parser('backup', help='Create backup')
backup_parser.add_argument('--output', help='Backup file')

# Server command
server_parser = subparsers.add_parser('server', help='Server operations')
server_parser.add_argument('--port', type=int, default=8000)
server_parser.add_argument('--host', default='localhost')

args = parser.parse_args()

print(f'Config: {args.config}')

if args.command == 'db':
    if args.db_command == 'migrate':
        print(f'Migrating to version: {args.version}')
    elif args.db_command == 'backup':
        print(f'Creating backup: {args.output}')
elif args.command == 'server':
    print(f'Starting server on {args.host}:{args.port}')
```

**Usage:**
```bash
python script.py --config prod.ini db migrate 2.0
python script.py db backup --output db.backup
python script.py server --port 3000
```

### Example 10: Action Classes and Custom Behavior

```python
import argparse

class ValidatePath(argparse.Action):
    """Custom action to validate file paths"""
    def __call__(self, parser, namespace, values, option_string=None):
        from pathlib import Path
        path = Path(values)
        if not path.exists():
            parser.error(f'Path does not exist: {values}')
        setattr(namespace, self.dest, path)

parser = argparse.ArgumentParser(description='File explorer')

parser.add_argument(
    'path',
    action=ValidatePath,
    help='Directory to explore'
)

args = parser.parse_args()

print(f'Exploring: {args.path}')
for item in args.path.iterdir():
    print(f'  {item.name}')
```

---

## Practical Example: Complete CLI Application

```python
import argparse
import sys
from pathlib import Path

def main():
    parser = argparse.ArgumentParser(
        prog='backup',
        description='Simple backup utility',
        formatter_class=argparse.RawDescriptionHelpFormatter
    )
    
    subparsers = parser.add_subparsers(dest='command', required=True)
    
    # Backup command
    backup_parser = subparsers.add_parser('backup', help='Create backup')
    backup_parser.add_argument('source', type=str, help='Source directory')
    backup_parser.add_argument('-d', '--destination', type=str, required=True)
    backup_parser.add_argument('--compress', action='store_true')
    
    # Restore command
    restore_parser = subparsers.add_parser('restore', help='Restore backup')
    restore_parser.add_argument('backup_file', type=str)
    restore_parser.add_argument('-d', '--destination', type=str, required=True)
    
    # List command
    list_parser = subparsers.add_parser('list', help='List backups')
    list_parser.add_argument('--location', type=str, default='.')
    
    args = parser.parse_args()
    
    if args.command == 'backup':
        print(f'Backing up {args.source} to {args.destination}')
        if args.compress:
            print('Compression enabled')
    
    elif args.command == 'restore':
        print(f'Restoring {args.backup_file} to {args.destination}')
    
    elif args.command == 'list':
        print(f'Listing backups in {args.location}')

if __name__ == '__main__':
    main()
```

**Usage:**
```bash
python backup.py backup /home/user/documents -d /mnt/backup --compress
python backup.py restore /mnt/backup/backup.tar -d /home/user/documents
python backup.py list --location /mnt/backup
```

---

## Documentation References

- [Official Python argparse Documentation](https://docs.python.org/3/library/argparse.html)
- [ArgumentParser Constructor](https://docs.python.org/3/library/argparse.html#argumentparser-objects)
- [add_argument() Reference](https://docs.python.org/3/library/argparse.html#the-add-argument-method)
- [Subparsers Tutorial](https://docs.python.org/3/library/argparse.html#sub-commands)
- [Type Converters](https://docs.python.org/3/library/argparse.html#type)
- [Argument Actions](https://docs.python.org/3/library/argparse.html#action)
