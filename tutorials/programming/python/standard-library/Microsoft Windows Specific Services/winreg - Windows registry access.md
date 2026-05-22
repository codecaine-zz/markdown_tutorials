# winreg - Windows registry access

The `winreg` module is a built-in Python module that provides access to the Windows Registry API. It is only available on Windows platforms.

> [!NOTE]
> This module is Windows-specific. Running these scripts on macOS or Linux will result in an `ImportError`.

Here are correct, complete, and self-contained code examples demonstrating various registry operations using `winreg`:

## Table of Contents

1. [Example 1: Creating a Key and Setting a Value](#example-1-creating-a-key-and-setting-a-value)
2. [Example 2: Reading a Value from a Key](#example-2-reading-a-value-from-a-key)
3. [Example 3: Modifying an Existing Value](#example-3-modifying-an-existing-value)
4. [Example 4: Deleting a Value from a Key](#example-4-deleting-a-value-from-a-key)
5. [Example 5: Creating a Subkey with Multiple Value Types](#example-5-creating-a-subkey-with-multiple-value-types)
6. [Example 6: Enumerating and Reading All Values in a Key](#example-6-enumerating-and-reading-all-values-in-a-key)
7. [Example 7: Enumerating All Subkeys in a Root Key](#example-7-enumerating-all-subkeys-in-a-root-key)
8. [Example 8: Deleting a Subkey and All Its Values](#example-8-deleting-a-subkey-and-all-its-values)

---

### Example 1: Creating a Key and Setting a Value

This example demonstrates how to create a registry key and store a string value inside it under the current user hive.

```python
import sys

# winreg is Windows-only
if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MyKey"

    try:
        # Create or open a key under HKEY_CURRENT_USER
        key = winreg.CreateKey(winreg.HKEY_CURRENT_USER, key_path)
        
        # Set a string value (REG_SZ)
        winreg.SetValueEx(key, "ValueName", 0, winreg.REG_SZ, "Hello World")
        print("Key and value created successfully.")
        
        # Always close keys when done
        winreg.CloseKey(key)
    except OSError as e:
        print(f"Failed to create registry key: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 2: Reading a Value from a Key

To retrieve a registry value, open the key with read permissions and query it by its value name.

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MyKey"
    value_name = "ValueName"

    try:
        # Open key for reading
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_READ)
        
        # Query the value data and type
        value_data, value_type = winreg.QueryValueEx(key, value_name)
        print(f"Value Name: {value_name}")
        print(f"Value Data: {value_data}")
        print(f"Value Type: {value_type}")
        
        winreg.CloseKey(key)
    except FileNotFoundError:
        print("The specified registry key or value does not exist.")
    except OSError as e:
        print(f"An error occurred: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 3: Modifying an Existing Value

To update a value, open the key with write permissions (`KEY_SET_VALUE` or `KEY_WRITE`) and write to the same value name.

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MyKey"
    value_name = "ValueName"

    try:
        # Open key with write permission
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_SET_VALUE)
        
        # Overwrite the value data
        winreg.SetValueEx(key, value_name, 0, winreg.REG_SZ, "Updated Value")
        print("Value updated successfully.")
        
        winreg.CloseKey(key)
    except FileNotFoundError:
        print("Key does not exist.")
    except OSError as e:
        print(f"Failed to modify value: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 4: Deleting a Value from a Key

You can delete a specific value from an open key using the `DeleteValue` function.

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MyKey"
    value_name = "ValueName"

    try:
        # Open key for modification
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_SET_VALUE)
        
        # Delete the value
        winreg.DeleteValue(key, value_name)
        print(f"Value '{value_name}' deleted successfully.")
        
        winreg.CloseKey(key)
    except FileNotFoundError:
        print("Key or value not found.")
    except OSError as e:
        print(f"Failed to delete value: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 5: Creating a Subkey with Multiple Value Types

The registry supports multiple types of data (strings, integers, binaries). Here is how to write them:

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MySubKey"
    values_to_set = {
        "StringValue": "Hello, Subkey!",
        "IntegerValue": 123,
        "BinaryValue": b"\x01\x02\x03"
    }

    try:
        # Create subkey
        key = winreg.CreateKey(winreg.HKEY_CURRENT_USER, key_path)
        
        for name, data in values_to_set.items():
            if isinstance(data, str):
                reg_type = winreg.REG_SZ
            elif isinstance(data, int):
                reg_type = winreg.REG_DWORD
            elif isinstance(data, bytes):
                reg_type = winreg.REG_BINARY
            
            winreg.SetValueEx(key, name, 0, reg_type, data)
        print("All values written successfully.")
        
        winreg.CloseKey(key)
    except OSError as e:
        print(f"An error occurred: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 6: Enumerating and Reading All Values in a Key

> [!IMPORTANT]
> The `winreg` module does **not** have a `QueryValueNames` function. To list all values inside a key, you must call `EnumValue` in a loop, incrementing the index from 0 until an `OSError` is raised.

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example\MySubKey"

    try:
        # Open key with read permissions
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_READ)
        
        print(f"Values in {key_path}:")
        index = 0
        while True:
            try:
                # EnumValue returns (name, value_data, value_type)
                name, data, val_type = winreg.EnumValue(key, index)
                print(f"- Name: {name}, Data: {data}, Type: {val_type}")
                index += 1
            except OSError:
                # Loop terminates when index exceeds the number of values
                break
                
        winreg.CloseKey(key)
    except FileNotFoundError:
        print("Key not found.")
    except OSError as e:
        print(f"An error occurred: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 7: Enumerating All Subkeys in a Root Key

To list all subkeys (nested folders) under a key, loop using `EnumKey` until it raises an `OSError`.

```python
import sys

if sys.platform == "win32":
    import winreg

    key_path = r"SOFTWARE\Example"

    try:
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_READ)
        
        print(f"Subkeys in {key_path}:")
        index = 0
        while True:
            try:
                subkey_name = winreg.EnumKey(key, index)
                print(f"- {subkey_name}")
                index += 1
            except OSError:
                # Loop terminates when index exceeds number of subkeys
                break
                
        winreg.CloseKey(key)
    except FileNotFoundError:
        print("Key not found.")
    except OSError as e:
        print(f"An error occurred: {e}")
else:
    print("winreg is only supported on Windows.")
```

### Example 8: Deleting a Subkey and All Its Values

On Windows, `DeleteKey` fails if a key has subkeys, but it can delete a key that only has values. To delete a key and its values, you can delete its values first (though deleting a key automatically deletes its values on modern Windows, deleting them explicitly can be done like this).

```python
import sys

if sys.platform == "win32":
    import winreg

    root_key = winreg.HKEY_CURRENT_USER
    subkey_path = r"SOFTWARE\Example\MySubKey"
    
    subkey = None
    try:
        # Open key to enumerate and delete values
        subkey = winreg.OpenKey(root_key, subkey_path, 0, winreg.KEY_ALL_ACCESS)
        
        # Get list of value names first (we cannot delete while iterating, index will shift)
        value_names = []
        index = 0
        while True:
            try:
                name, _, _ = winreg.EnumValue(subkey, index)
                value_names.append(name)
                index += 1
            except OSError:
                break
        
        # Delete each value
        for name in value_names:
            winreg.DeleteValue(subkey, name)
            
        # Close subkey handle before deleting the key itself
        winreg.CloseKey(subkey)
        subkey = None
        
        # Delete the empty subkey
        winreg.DeleteKey(root_key, subkey_path)
        print("Subkey deleted successfully.")
    except FileNotFoundError:
        print("Subkey not found.")
    except OSError as e:
        print(f"Failed to delete subkey: {e}")
    finally:
        # Safely close if OpenKey succeeded but later steps failed
        if subkey is not None:
            try:
                winreg.CloseKey(subkey)
            except OSError:
                pass
else:
    print("winreg is only supported on Windows.")
```

This guide covers typical operations in the Windows Registry using the standard library `winreg` module, with complete, self-contained examples.
