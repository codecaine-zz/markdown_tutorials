# pathlib - Object-oriented filesystem paths

## Overview

The `pathlib` module provides an object-oriented approach to work with filesystem paths. It offers the `Path` class which represents filesystem paths, eliminating the need for `os.path` in many cases.

## Table of Contents

1. [Basic Path Operations](#basic-path-operations)
2. [Path Existence and Type Checks](#path-existence-and-type-checks)
3. [Creating and Managing Directories](#creating-and-managing-directories)
4. [File and Directory Operations](#file-and-directory-operations)
5. [Path Properties and Methods](#path-properties-and-methods)
6. [Iterating Through Directories](#iterating-through-directories)
7. [Path Manipulation](#path-manipulation)
8. [Reading and Writing Files](#reading-and-writing-files)

---

## Basic Path Operations

```python
from pathlib import Path

# Create Path objects
current_dir = Path('.')
file_path = Path('example.txt')
nested_path = Path('documents/reports/2024')

# Convert to string
print(str(file_path))      # Output: example.txt

# Get current working directory
cwd = Path.cwd()
print(f'Current directory: {cwd}')

# Get home directory
home = Path.home()
print(f'Home directory: {home}')

# Create Path using division operator (cleaner than os.path.join)
combined = Path('documents') / 'reports' / 'summary.txt'
print(f'Combined path: {combined}')
```

---

## Path Existence and Type Checks

```python
from pathlib import Path

path = Path('example.txt')
directory = Path('documents')

# Check if path exists
if path.exists():
    print(f'{path} exists')
else:
    print(f'{path} does not exist')

# Check if path is a file
if path.is_file():
    print(f'{path} is a file')

# Check if path is a directory
if directory.is_dir():
    print(f'{directory} is a directory')

# Check if path is a symlink
if path.is_symlink():
    print(f'{path} is a symbolic link')

# Check if path is absolute
if path.is_absolute():
    print(f'{path} is an absolute path')
else:
    print(f'{path} is a relative path')
```

---

## Creating and Managing Directories

```python
from pathlib import Path

# Create a single directory
new_dir = Path('new_directory')
if not new_dir.exists():
    new_dir.mkdir()
    print(f'Created directory: {new_dir}')

# Create nested directories (creates parents if needed)
nested_dir = Path('data/processed/2024')
nested_dir.mkdir(parents=True, exist_ok=True)
print(f'Created nested directories: {nested_dir}')

# The exist_ok parameter prevents error if directory already exists
nested_dir.mkdir(parents=True, exist_ok=True)

# Remove an empty directory
# empty_dir.rmdir()

# Remove a directory and all its contents (use with caution!)
# import shutil
# shutil.rmtree(directory_path)
```

---

## File and Directory Operations

```python
from pathlib import Path
import shutil

# Create a sample file (if it doesn't exist)
source_file = Path('original.txt')
if not source_file.exists():
    source_file.write_text('Hello, World!')

# Copy a file
destination = Path('copy.txt')
shutil.copy(source_file, destination)
print(f'Copied {source_file} to {destination}')

# Rename a file
new_name = Path('renamed.txt')
source_file.rename(new_name)
print(f'Renamed to {new_name}')

# Move a file to a different directory
backup_dir = Path('backup')
backup_dir.mkdir(exist_ok=True)
backup_path = backup_dir / new_name.name
new_name.rename(backup_path)
print(f'Moved to {backup_path}')

# Delete a file
# file_path.unlink()  # Raises error if file doesn't exist
# file_path.unlink(missing_ok=True)  # No error if file doesn't exist

# Get file size
file_size = backup_path.stat().st_size
print(f'File size: {file_size} bytes')

# Get file modification time
import time
mod_time = backup_path.stat().st_mtime
print(f'Modified: {time.ctime(mod_time)}')
```

---

## Path Properties and Methods

```python
from pathlib import Path

file_path = Path('documents/reports/summary.txt')

# Get individual path components
print(f'Name: {file_path.name}')              # summary.txt
print(f'Stem (name without extension): {file_path.stem}')  # summary
print(f'Suffix (extension): {file_path.suffix}')          # .txt
print(f'Parent: {file_path.parent}')          # documents/reports

# Get all parents
print(f'Parents: {list(file_path.parents)}')

# Get absolute path (resolving relative paths)
absolute = file_path.resolve()
print(f'Absolute path: {absolute}')

# Get path parts
print(f'Parts: {file_path.parts}')  # ('documents', 'reports', 'summary.txt')

# Check if path is relative to another path
if file_path.is_relative_to(Path('documents')):
    print(f'{file_path} is relative to documents')

# Get relative path
relative = file_path.relative_to(Path('documents'))
print(f'Relative path: {relative}')  # reports/summary.txt
```

---

## Iterating Through Directories

```python
from pathlib import Path

# Create a sample directory structure for demonstration
import tempfile
import shutil

# Using the current directory
directory = Path('.')

# List all items in a directory (non-recursive)
print('Direct contents:')
for item in directory.iterdir():
    if item.name.startswith('.'):  # Skip hidden files
        continue
    item_type = 'DIR' if item.is_dir() else 'FILE'
    print(f'  {item_type}: {item.name}')

# Find all Python files recursively
print('\nAll Python files:')
for py_file in directory.rglob('*.py'):
    print(f'  {py_file}')

# Find all files (not directories)
print('\nAll files (non-recursive):')
for file_path in directory.glob('*'):
    if file_path.is_file():
        print(f'  {file_path.name}')

# Find all directories with specific pattern
print('\nDirectories matching pattern:')
for dir_path in directory.glob('*_test'):
    if dir_path.is_dir():
        print(f'  {dir_path.name}')
```

---

## Path Manipulation

```python
from pathlib import Path

base_path = Path('documents/reports')

# Join paths using the / operator
full_path = base_path / 'summary' / 'monthly.txt'
print(f'Joined path: {full_path}')

# With wildcards for pattern matching
pattern_path = base_path / '*.txt'
print(f'Pattern: {pattern_path}')

# Match files against a pattern
path = Path('example.txt')
if path.match('*.txt'):
    print(f'{path} matches *.txt')

if path.match('exam*.txt'):
    print(f'{path} matches exam*.txt')
```

---

## Reading and Writing Files

```python
from pathlib import Path

file_path = Path('data.txt')

# Write text to a file
file_path.write_text('Hello, World!\nPython pathlib is great!')
print(f'Written to {file_path}')

# Read text from a file
content = file_path.read_text()
print(f'Content:\n{content}')

# Write bytes to a file
file_path.write_bytes(b'Binary data here')

# Read bytes from a file
binary_content = file_path.read_bytes()
print(f'Binary: {binary_content}')

# Append to a file
file_path.write_text(file_path.read_text() + '\nAppended line')

# Use with context manager for safer file handling
with file_path.open('r') as f:
    lines = f.readlines()
    print(f'Lines: {len(lines)}')

# Write with context manager
with file_path.open('w') as f:
    f.write('New content\n')
    f.write('Second line\n')

# Clean up
# file_path.unlink()
```

---

## Practical Example: File Organization

```python
from pathlib import Path
from datetime import datetime
import shutil

def organize_files(source_dir, organize_by='extension'):
    """Organize files into subdirectories by extension or date"""
    source = Path(source_dir)
    
    if not source.exists():
        print(f'Source directory {source} does not exist')
        return
    
    for file in source.glob('*'):
        if file.is_file():
            if organize_by == 'extension':
                # Organize by file extension
                ext = file.suffix[1:] if file.suffix else 'no_extension'
                target_dir = source / ext
            elif organize_by == 'date':
                # Organize by modification date
                mod_time = datetime.fromtimestamp(file.stat().st_mtime)
                target_dir = source / f'{mod_time.year}-{mod_time.month:02d}'
            else:
                continue
            
            # Create target directory if it doesn't exist
            target_dir.mkdir(exist_ok=True)
            
            # Move file to target directory
            target_file = target_dir / file.name
            shutil.move(str(file), str(target_file))
            print(f'Moved: {file.name} -> {target_dir.name}')

# Example usage (uncomment to use):
# organize_files('.', organize_by='extension')
```

---

## Documentation References

- [Official Python pathlib Documentation](https://docs.python.org/3/library/pathlib.html)
- [Path Methods Reference](https://docs.python.org/3/library/pathlib.html#methods)
- [Replacing os.path with pathlib](https://docs.python.org/3/library/pathlib.html#correspondence-to-tools-in-the-os-module)
