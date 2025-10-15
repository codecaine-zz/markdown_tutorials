# Complete Tutorial: Using Cerberus with Python 3

This comprehensive tutorial will guide you through installing and using Cerberus, a lightweight and flexible data validation library for Python.

## Table of Contents
1. [Introduction to Cerberus](#introduction-to-cerberus)
2. [Installation](#installation)
3. [Basic Validation Concepts](#basic-validation-concepts)
4. [Core Features](#core-features)
5. [Advanced Usage](#advanced-usage)
6. [Error Handling and Customization](#error-handling-and-customization)
7. [Best Practices](#best-practices)

## Introduction to Cerberus

Cerberus is a lightweight, schema-based data validation library for Python that provides:
- Simple and intuitive syntax
- Flexible validation rules
- Comprehensive error reporting
- Support for nested structures
- Extensible validation capabilities

## Installation

First, install Cerberus using pip:

```bash
pip install cerberus
```

For Python 3.12 specifically:
```bash
python3.12 -m pip install cerberus
```

Let's verify the installation and start coding:

```python
# Verify installation
try:
    import cerberus
    print(f"Cerberus version: {cerberus.__version__}")
except ImportError as e:
    print(f"Installation failed: {e}")
```

## Basic Validation Concepts

### Simple Schema Definition

Let's start with a basic example:

```python
from cerberus import Validator

# Define a simple schema
schema = {
    'name': {'type': 'string', 'required': True},
    'age': {'type': 'integer', 'required': True, 'min': 0},
    'email': {'type': 'string', 'required': False, 'regex': r'^[^@]+@[^@]+\.[^@]+$'}
}

# Test data
document = {
    'name': 'John Doe',
    'age': 30,
    'email': 'john.doe@example.com'
}

# Validate the document
v = Validator(schema)
is_valid = v.validate(document)

print(f"Is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Validation successful!")
```

### Understanding Schema Rules

Cerberus provides a rich set of built-in validation rules:

```python
from cerberus import Validator

# Complex schema with various validation rules
complex_schema = {
    'username': {
        'type': 'string',
        'required': True,
        'minlength': 3,
        'maxlength': 20,
        'regex': r'^[a-zA-Z0-9_]+$'
    },
    'password': {
        'type': 'string',
        'required': True,
        'minlength': 8
    },
    'age': {
        'type': 'integer',
        'required': False,
        'min': 0,
        'max': 150
    },
    'balance': {
        'type': 'float',
        'required': False,
        'min': 0.0
    },
    'active': {
        'type': 'boolean',
        'required': True
    },
    'tags': {
        'type': 'list',
        'required': False,
        'schema': {'type': 'string'}
    }
}

# Test data
test_document = {
    'username': 'johndoe',
    'password': 'secret123',
    'age': 25,
    'balance': 100.50,
    'active': True,
    'tags': ['python', 'developer', 'backend']
}

v = Validator(complex_schema)
is_valid = v.validate(test_document)

print(f"Is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Document is valid!")
```

## Core Features

### 1. Nested Validation

Cerberus handles nested structures beautifully:

```python
from cerberus import Validator

# Nested schema example
nested_schema = {
    'user': {
        'type': 'dict',
        'schema': {
            'name': {'type': 'string', 'required': True},
            'email': {'type': 'string', 'required': True, 'regex': r'^[^@]+@[^@]+\.[^@]+$'},
            'address': {
                'type': 'dict',
                'schema': {
                    'street': {'type': 'string', 'required': True},
                    'city': {'type': 'string', 'required': True},
                    'zipcode': {'type': 'string', 'regex': r'^\d{5}(-\d{4})?$'}
                }
            }
        }
    },
    'orders': {
        'type': 'list',
        'schema': {
            'type': 'dict',
            'schema': {
                'product_id': {'type': 'string', 'required': True},
                'quantity': {'type': 'integer', 'min': 1},
                'price': {'type': 'float', 'min': 0.0}
            }
        }
    }
}

# Test nested data
nested_document = {
    'user': {
        'name': 'John Doe',
        'email': 'john@example.com',
        'address': {
            'street': '123 Main St',
            'city': 'Anytown',
            'zipcode': '12345'
        }
    },
    'orders': [
        {
            'product_id': 'P001',
            'quantity': 2,
            'price': 29.99
        }
    ]
}

v = Validator(nested_schema)
is_valid = v.validate(nested_document)

print(f"Nested validation is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Nested document is valid!")
```

### 2. Schema Inheritance

Cerberus supports schema inheritance through the `extends` keyword:

```python
from cerberus import Validator

# Base schema
base_schema = {
    'id': {'type': 'string', 'required': True},
    'created_at': {'type': 'datetime', 'required': True}
}

# Extended schema
extended_schema = {
    'name': {'type': 'string', 'required': True},
    'email': {'type': 'string', 'regex': r'^[^@]+@[^@]+\.[^@]+$'},
    'age': {'type': 'integer', 'min': 0}
}

# Combined schema using extends
combined_schema = {
    **base_schema,
    **extended_schema
}

# Or using the extends keyword (if supported by your version)
# This approach works with newer versions of Cerberus

print("Combined schema:", combined_schema)

test_data = {
    'id': 'user123',
    'created_at': '2024-01-01T00:00:00Z',
    'name': 'Jane Smith',
    'email': 'jane@example.com',
    'age': 28
}

v = Validator(combined_schema)
is_valid = v.validate(test_data)

print(f"Extended validation is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Extended document is valid!")
```

### 3. Conditional Validation

Using the `allow_unknown` and conditional logic:

```python
from cerberus import Validator

# Schema with conditional fields
conditional_schema = {
    'type': {'type': 'string', 'required': True, 'allowed': ['user', 'admin']},
    'username': {'type': 'string', 'required': True},
    'email': {'type': 'string', 'required': True, 'regex': r'^[^@]+@[^@]+\.[^@]+$'},
    'permissions': {
        'type': 'list',
        'schema': {'type': 'string'}
    },
    # Only required for admins
    'admin_level': {
        'type': 'integer',
        'required': False,
        'min': 1,
        'max': 10
    }
}

# Test data with different types
user_data = {
    'type': 'user',
    'username': 'regular_user',
    'email': 'user@example.com'
}

admin_data = {
    'type': 'admin',
    'username': 'admin_user',
    'email': 'admin@example.com',
    'admin_level': 5
}

# Validate user data
v1 = Validator(conditional_schema)
is_valid1 = v1.validate(user_data)
print(f"User validation: {is_valid1}")
if not is_valid1:
    print("User errors:", v1.errors)

# Validate admin data  
v2 = Validator(conditional_schema)
is_valid2 = v2.validate(admin_data)
print(f"Admin validation: {is_valid2}")
if not is_valid2:
    print("Admin errors:", v2.errors)
```

## Advanced Usage

### Custom Validators and Normalizers

```python
from cerberus import Validator
import re

# Custom validator functions
def validate_phone(value):
    """Validate US phone number format"""
    pattern = r'^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$'
    return bool(re.match(pattern, value))

# Custom normalizer
def normalize_email(value):
    """Normalize email to lowercase"""
    return value.lower() if isinstance(value, str) else value

# Schema with custom validators and normalizers
advanced_schema = {
    'name': {'type': 'string', 'required': True},
    'email': {
        'type': 'string',
        'required': True,
        'validator': validate_phone,  # Custom validator
        'normalizer': normalize_email  # Custom normalizer
    },
    'phone': {
        'type': 'string',
        'regex': r'^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$'
    }
}

# Test with custom validators
test_document = {
    'name': 'Alice Johnson',
    'email': 'ALICE@EXAMPLE.COM',  # Will be normalized
    'phone': '(555) 123-4567'
}

v = Validator(advanced_schema)
is_valid = v.validate(test_document)

print(f"Advanced validation is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Document is valid!")
    print("Normalized data:", v.document)  # Shows normalized values
```

### Validation with Custom Error Messages

```python
from cerberus import Validator

# Schema with custom error messages
custom_error_schema = {
    'username': {
        'type': 'string',
        'required': True,
        'minlength': 3,
        'maxlength': 20,
        'messages': {
            'required_field': "Username is mandatory",
            'min_length': "Username must be at least {min} characters long",
            'max_length': "Username cannot exceed {max} characters"
        }
    },
    'email': {
        'type': 'string',
        'required': True,
        'regex': r'^[^@]+@[^@]+\.[^@]+$'
    },
    'age': {
        'type': 'integer',
        'min': 0,
        'max': 150
    }
}

# Test validation with custom messages
test_data = {
    'username': 'ab',  # Too short
    'email': 'invalid-email',
    'age': -5
}

v = Validator(custom_error_schema)
is_valid = v.validate(test_data)

print(f"Custom error validation is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
```

### Batch Validation

```python
from cerberus import Validator

# Schema for batch validation
batch_schema = {
    'name': {'type': 'string', 'required': True},
    'age': {'type': 'integer', 'min': 0, 'max': 150}
}

# Multiple documents to validate
documents = [
    {'name': 'John', 'age': 30},
    {'name': 'Jane', 'age': 25},
    {'name': '', 'age': -5},  # Invalid document
]

print("Batch validation results:")

for i, doc in enumerate(documents):
    v = Validator(batch_schema)
    is_valid = v.validate(doc)
    
    print(f"Document {i+1}: {'Valid' if is_valid else 'Invalid'}")
    if not is_valid:
        print(f"  Errors: {v.errors}")
```

## Error Handling and Customization

### Comprehensive Error Handling

```python
from cerberus import Validator
import json

def handle_validation_errors(schema, document):
    """Handle validation with comprehensive error reporting"""
    
    v = Validator(schema)
    
    if not v.validate(document):
        print("Validation failed!")
        print("=" * 50)
        
        # Get all errors in a structured way
        for field, errors in v.errors.items():
            print(f"Field '{field}':")
            if isinstance(errors, list):
                for error in errors:
                    print(f"  - {error}")
            else:
                print(f"  - {errors}")
        
        return False
    
    return True

# Test with various scenarios
test_schema = {
    'name': {'type': 'string', 'required': True, 'minlength': 2},
    'email': {'type': 'string', 'required': True, 'regex': r'^[^@]+@[^@]+\.[^@]+$'},
    'age': {'type': 'integer', 'required': False, 'min': 0}
}

# Valid document
valid_doc = {
    'name': 'Alice',
    'email': 'alice@example.com'
}

# Invalid documents
invalid_docs = [
    # Missing required fields
    {'name': 'Bob'},
    
    # Invalid email format
    {'name': 'Charlie', 'email': 'not-an-email'},
    
    # Invalid age (negative)
    {'name': 'David', 'email': 'david@example.com', 'age': -10}
]

print("Testing valid document:")
handle_validation_errors(test_schema, valid_doc)

for i, doc in enumerate(invalid_docs):
    print(f"\nTesting invalid document {i+1}:")
    handle_validation_errors(test_schema, doc)
```

### Custom Validation Rules

```python
from cerberus import Validator

# Create a custom validator class
class CustomValidator(Validator):
    
    def _validate_is_even(self, is_even, field, value):
        """Validate that the value is even"""
        if is_even and isinstance(value, int) and value % 2 != 0:
            self._error(field, "Value must be an even number")
    
    def _validate_is_odd(self, is_odd, field, value):
        """Validate that the value is odd"""
        if is_odd and isinstance(value, int) and value % 2 == 0:
            self._error(field, "Value must be an odd number")

# Schema with custom validation rules
custom_rules_schema = {
    'number': {
        'type': 'integer',
        'is_even': True,  # Custom rule - number must be even
        'min': 2,
        'max': 100
    },
    'sequence': {
        'type': 'list',
        'schema': {'type': 'integer'},
        'is_odd': True  # This won't work as expected since it's a list validation rule
    }
}

# Test custom rules
test_data = {
    'number': 4,  # Valid (even)
    'sequence': [1, 3, 5]  # Valid (all odd numbers in list)
}

v = CustomValidator(custom_rules_schema)
is_valid = v.validate(test_data)

print(f"Custom rule validation is valid: {is_valid}")
if not is_valid:
    print("Errors:", v.errors)
else:
    print("Document passed custom validation!")
```

## Best Practices

### 1. Schema Design Patterns

```python
from cerberus import Validator

# Reusable schema components
user_schema = {
    'id': {'type': 'string', 'required': True},
    'created_at': {'type': 'datetime', 'required': True}
}

profile_schema = {
    'name': {'type': 'string', 'required': True, 'minlength': 2},
    'email': {'type': 'string', 'required': True, 
              'regex': r'^[^@]+@[^@]+\.[^@]+$'},
    'age': {'type': 'integer', 'min': 0}
}

# Combined schema
complete_user_schema = {
    **user_schema,
    **profile_schema,
    'active': {'type': 'boolean', 'required': True},
    'preferences': {
        'type': 'dict',
        'schema': {
            'theme': {'type': 'string', 'allowed': ['light', 'dark']},
            'notifications': {'type': 'boolean'}
        }
    }
}

# Reusable validator instances
user_validator = Validator(complete_user_schema)

def validate_user_document(document):
    """Validate user document with proper error handling"""
    if not isinstance(document, dict):
        return False, "Document must be a dictionary"
    
    is_valid = user_validator.validate(document)
    
    if not is_valid:
        errors = []
        for field, field_errors in user_validator.errors.items():
            errors.append(f"{field}: {field_errors}")
        return False, "; ".join(errors)
    
    return True, "Valid document"

# Test validation function
test_user = {
    'id': 'user123',
    'created_at': '2024-01-01T00:00:00Z',
    'name': 'John Doe',
    'email': 'john@example.com',
    'age': 30,
    'active': True
}

is_valid, message = validate_user_document(test_user)
print(f"Validation result: {is_valid}, Message: {message}")
```

### 2. Performance Optimization

```python
from cerberus import Validator
import time

# Pre-create validators for better performance in loops
class OptimizedValidator:
    
    def __init__(self):
        self.schema = {
            'product_id': {'type': 'string', 'required': True},
            'name': {'type': 'string', 'required': True},
            'price': {'type': 'float', 'min': 0.0},
            'quantity': {'type': 'integer', 'min': 0},
            'category': {'type': 'string', 'allowed': ['electronics', 'books', 'clothing']}
        }
        self.validator = Validator(self.schema)
    
    def validate_product(self, product):
        """Validate a single product"""
        return self.validator.validate(product)
    
    def batch_validate(self, products):
        """Batch validation for multiple products"""
        results = []
        
        # Reuse the same validator instance
        for product in products:
            is_valid = self.validator.validate(product)
            results.append({
                'product': product,
                'valid': is_valid,
                'errors': self.validator.errors if not is_valid else None
            })
            
            # Reset errors for next validation
            self.validator._errors.clear()
        
        return results

# Usage example
optimizer = OptimizedValidator()

products = [
    {'product_id': 'P001', 'name': 'Laptop', 'price': 999.99, 'quantity': 5, 'category': 'electronics'},
    {'product_id': 'P002', 'name': 'Book', 'price': 19.99, 'quantity': 100, 'category': 'books'},
    {'product_id': '', 'name': '', 'price': -10.0, 'quantity': -5}  # Invalid product
]

start_time = time.time()
results = optimizer.batch_validate(products)
end_time = time.time()

print(f"Batch validation completed in {end_time - start_time:.4f} seconds")
for i, result in enumerate(results):
    print(f"Product {i+1}: {'Valid' if result['valid'] else 'Invalid'}")
    if not result['valid']:
        print(f"  Errors: {result['errors']}")
```

## Complete Working Example

Here's a complete example that demonstrates various Cerberus features:

```python
from cerberus import Validator
import datetime

def main():
    """Complete example demonstrating Cerberus usage"""
    
    # Define complex schema for user registration
    user_schema = {
        'user_id': {'type': 'string', 'required': True, 'regex': r'^[a-zA-Z0-9_]{3,20}$'},
        'profile': {
            'type': 'dict',
            'schema': {
                'first_name': {'type': 'string', 'required': True, 'minlength': 1},
                'last_name': {'type': 'string', 'required': True, 'minlength': 1},
                'email': {'type': 'string', 'required': True, 
                         'regex': r'^[^@]+@[^@]+\.[^@]+$'},
                'date_of_birth': {'type': 'datetime'}
            }
        },
        'preferences': {
            'type': 'dict',
            'schema': {
                'theme': {'type': 'string', 'allowed': ['light', 'dark']},
                'language': {'type': 'string', 'default': 'en'},
                'notifications_enabled': {'type': 'boolean', 'default': True}
            }
        },
        'account_status': {'type': 'string', 'allowed': ['active', 'inactive', 'suspended'], 
                          'default': 'active'}
    }
    
    # Test data
    test_users = [
        {
            'user_id': 'john_doe',
            'profile': {
                'first_name': 'John',
                'last_name': 'Doe',
                'email': 'john.doe@example.com'
            },
            'preferences': {
                'theme': 'dark',
                'language': 'en'
            }
        },
        {
            'user_id': 'invalid_email',  # This will fail validation
            'profile': {
                'first_name': '',
                'last_name': 'Smith',
                'email': 'invalid-email'  # Invalid email format
            }
        }
    ]
    
    print("User Registration Validation Demo")
    print("=" * 50)
    
    for i, user_data in enumerate(test_users):
        print(f"\nTesting User {i+1}:")
        v = Validator(user_schema)
        
        is_valid = v.validate(user_data)
        if is_valid:
            print("✓ VALIDATION PASSED")
            # Show normalized data
            print("Normalized data:", v.document)
        else:
            print("✗ VALIDATION FAILED")
            for field, errors in v.errors.items():
                print(f"  {field}: {errors}")

if __name__ == "__main__":
    main()
```

## Summary

This tutorial covered:

1. **Installation and setup** of Cerberus with Python 3.12
2. **Basic validation concepts** with simple schemas
3. **Core features**: nested validation, schema inheritance, conditional validation
4. **Advanced usage**: custom validators, normalizers, batch processing
5. **Error handling** and customization techniques
6. **Best practices** for performance optimization and reusability

Cerberus is an excellent choice for data validation in Python applications due to its:
- Lightweight design
- Rich set of built-in validation rules  
- Easy-to-use API
- Support for complex nested structures
- Extensible architecture

Remember to always validate your input data before processing it, and use Cerberus's comprehensive error reporting to provide meaningful feedback to users or other systems.

## Reusable, Optimized Cerberus Utilities (Copy‑Paste Ready)

Use these drop-in helpers to speed up schema writing, normalization, and error handling. They are safe, dependency-free, and designed for Python 3.9+ and Cerberus 1.3+.

### Quick validator factory with sensible defaults

```python
from cerberus import Validator

def make_validator(schema, *, allow_unknown=False, purge_unknown=False, require_all=False, **kwargs):
    """Create a configured Validator.
    - allow_unknown: allow keys not present in schema
    - purge_unknown: strip unknown keys during normalization
    - require_all: treat all fields as required unless they have required=False
    """
    return Validator(
        schema,
        allow_unknown=allow_unknown,
        purge_unknown=purge_unknown,
        require_all=require_all,
        **kwargs,
    )

class StrictValidator(Validator):
    """Disallow unknown keys and strip them if they appear (safe for APIs)."""
    def __init__(self, *args, **kwargs):
        kwargs.setdefault('allow_unknown', False)
        kwargs.setdefault('purge_unknown', True)
        super().__init__(*args, **kwargs)
```

### Common coercers (normalizers) you’ll reuse a lot

Use these in your schema with the `coerce` rule. You can pass a single callable or a list to chain multiple coercers.

```python
import datetime as _dt
import uuid as _uuid

def _is_str(x):
    return isinstance(x, str)

def strip_str(v):
    return v.strip() if _is_str(v) else v

def to_lower(v):
    return v.lower() if _is_str(v) else v

def empty_to_none(v):
    return None if v == '' else v

def to_int(v):
    try:
        return int(v) if v is not None and v != '' else v
    except (TypeError, ValueError):
        return v

def to_float(v):
    try:
        return float(v) if v is not None and v != '' else v
    except (TypeError, ValueError):
        return v

def to_bool(v):
    if isinstance(v, bool):
        return v
    if _is_str(v):
        s = v.strip().lower()
        if s in {'true', '1', 'yes', 'y', 'on'}:
            return True
        if s in {'false', '0', 'no', 'n', 'off'}:
            return False
    return v

def to_datetime(v):
    """Parse common ISO-8601 strings to datetime (timezone-aware when possible)."""
    if isinstance(v, _dt.datetime):
        return v
    if not _is_str(v):
        return v
    s = v.strip()
    if s.endswith('Z'):
        s = s[:-1] + '+00:00'
    try:
        return _dt.datetime.fromisoformat(s)
    except ValueError:
        return v

def to_uuid(v):
    if isinstance(v, _uuid.UUID):
        return v
    if v is None:
        return v
    try:
        return _uuid.UUID(str(v))
    except (ValueError, AttributeError, TypeError):
        return v

def uniq_list(v):
    """Deduplicate while preserving order (hashable items only)."""
    if isinstance(v, list):
        seen = set()
        out = []
        for item in v:
            try:
                if item not in seen:
                    seen.add(item)
                    out.append(item)
            except TypeError:
                # Unhashable, give up and return original
                return v
        return out
    return v
```

### Extended validator: custom types and rules

Add a UUID type, list uniqueness, and simple cross-field constraints.

```python
from cerberus import Validator
import uuid

class ExtendedValidator(Validator):
    # Custom type: 'uuid'
    def _validate_type_uuid(self, value):  # type: ignore[override]
        """Enable 'type': 'uuid' in schemas.
        The rule's arguments are validated against this schema:\n    {'type': 'uuid'}
        """
        if isinstance(value, uuid.UUID):
            return True
        try:
            uuid.UUID(str(value))
            return True
        except Exception:
            return False

    # Custom rule: 'unique': True for lists
    def _validate_unique(self, unique, field, value):
        """{'type': 'boolean'} — Ensure list elements are unique."""
        if unique and isinstance(value, list):
            try:
                if len(value) != len(set(value)):
                    self._error(field, 'List elements must be unique')
            except TypeError:
                self._error(field, 'List contains unhashable elements; cannot enforce uniqueness')

    # Custom rule: 'forbidden_with': ['other', ...]
    def _validate_forbidden_with(self, other_fields, field, value):
        """{'type': 'list'} — Field cannot appear together with any of the given fields."""
        if value is None:
            return
        if isinstance(other_fields, (list, tuple)):
            conflicts = [f for f in other_fields if f in (self.document or {})]
            if conflicts:
                self._error(field, f"Cannot be used together with: {', '.join(conflicts)}")

    # Custom rule: 'requires_any': ['a', 'b'] — at least one must be present
    def _validate_requires_any(self, fields, field, value):
        """{'type': 'list'} — Require at least one of the provided fields when this field is present."""
        if value is None:
            return
        doc = self.document or {}
        if isinstance(fields, (list, tuple)) and not any(f in doc for f in fields):
            self._error(field, f"Requires at least one of: {', '.join(map(str, fields))}")
```

Example usage:

```python
schema = {
    'id': {'type': 'uuid', 'required': True, 'coerce': to_uuid},
    'email': {'type': 'string', 'required': True, 'regex': r'^[^@\s]+@[^@\s]+\.[^@\s]+$', 'coerce': [strip_str, to_lower]},
    'tags': {'type': 'list', 'schema': {'type': 'string', 'coerce': strip_str}, 'unique': True},
    'note': {'type': 'string', 'forbidden_with': ['admin_only']},
    'admin_only': {'type': 'boolean', 'requires_any': ['note']},
}

v = ExtendedValidator(schema, allow_unknown=False, purge_unknown=True)
doc = {'id': '550e8400-e29b-41d4-a716-446655440000', 'email': ' USER@EXAMPLE.COM ', 'tags': ['a','a','b']}
ok = v.validate(doc)
print(ok, v.document, v.errors)
```

### Error formatting helpers (flat list, dot-paths)

```python
from collections.abc import Mapping

def flatten_errors(errors_tree):
    """Convert Cerberus errors to a flat list of {'path': 'a.b[2]', 'message': '...'} dicts."""
    out = []

    def _walk(prefix, node):
        if isinstance(node, Mapping):
            for k, v in node.items():
                _walk(f"{prefix}.{k}" if prefix else str(k), v)
        elif isinstance(node, list):
            for item in node:
                if isinstance(item, (Mapping, list)):
                    _walk(prefix, item)
                else:
                    out.append({'path': prefix, 'message': str(item)})
        else:
            out.append({'path': prefix, 'message': str(node)})

    _walk('', errors_tree)
    return out

def errors_as_string(errors_tree, sep='; '):
    return sep.join(f"{e['path']}: {e['message']}" for e in flatten_errors(errors_tree))
```

Usage:

```python
v = Validator(schema)
if not v.validate(doc):
    print(errors_as_string(v.errors))
```

### Batch/stream validation utilities

```python
from dataclasses import dataclass
from typing import Any, Iterable, Iterator, Optional

@dataclass
class ValidationResult:
    index: int
    valid: bool
    document: dict | None
    errors: Any  # Cerberus error tree

class BatchValidator:
    """Reuse one validator instance for many documents (single-threaded)."""
    def __init__(self, schema: dict, validator_cls: type[Validator] = Validator, **kwargs):
        self.validator = validator_cls(schema, **kwargs)

    def validate_iter(self, docs: Iterable[dict]) -> Iterator[ValidationResult]:
        for i, d in enumerate(docs):
            ok = self.validator.validate(d)
            yield ValidationResult(index=i, valid=ok, document=(self.validator.document if ok else None), errors=(None if ok else self.validator.errors))

    def validate_list(self, docs: list[dict]) -> list[ValidationResult]:
        return list(self.validate_iter(docs))
```

### Common field schema snippets

Copy these into your schemas to stay consistent across modules.

```python
EMAIL_FIELD = {
    'type': 'string',
    'regex': r'^[^@\s]+@[^@\s]+\.[^@\s]+$',
    'minlength': 3,
    'maxlength': 254,
    'coerce': [strip_str, to_lower],
}

NONEMPTY_STR_FIELD = {
    'type': 'string',
    'minlength': 1,
    'empty': False,
    'coerce': strip_str,
}

OPTIONAL_STR_FIELD = {
    'type': 'string',
    'nullable': True,
    'empty': True,
    'coerce': strip_str,
}

POS_INT_FIELD = {'type': 'integer', 'min': 0, 'coerce': to_int}

DATETIME_FIELD = {'type': 'datetime', 'coerce': to_datetime}

# Requires ExtendedValidator (custom 'uuid' type)
UUID_FIELD = {'type': 'uuid', 'required': True, 'coerce': to_uuid}

# Tags with trimming and uniqueness (requires ExtendedValidator for 'unique')
TAGS_FIELD = {'type': 'list', 'schema': {'type': 'string', 'coerce': strip_str}, 'unique': True}
```

### Deep-merge schemas (compose reusable parts)

```python
from copy import deepcopy

def deep_merge_schema(*schemas: dict) -> dict:
    """Deep-merge dicts, combining nested 'schema' blocks for Cerberus."""
    def _merge(a: dict, b: dict) -> dict:
        out = deepcopy(a)
        for k, v in b.items():
            if k in out and isinstance(out[k], dict) and isinstance(v, dict):
                out[k] = _merge(out[k], v)
            else:
                out[k] = deepcopy(v)
        return out
    if not schemas:
        return {}
    result = deepcopy(schemas[0])
    for s in schemas[1:]:
        result = _merge(result, s)
    return result
```

### Minimal end-to-end example

```python
user_core = {
    'id': UUID_FIELD,
    'email': EMAIL_FIELD,
    'created_at': {'type': 'datetime', 'coerce': to_datetime, 'nullable': True},
    'tags': TAGS_FIELD,
}

profile_extras = {
    'name': NONEMPTY_STR_FIELD,
    'age': {'type': 'integer', 'min': 0, 'coerce': to_int},
}

schema = deep_merge_schema(user_core, profile_extras)

docs = [
    {'id': '550e8400-e29b-41d4-a716-446655440000', 'email': 'A@B.COM', 'name': '  Alice  ', 'tags': ['x','x','y']},
    {'id': 'not-a-uuid', 'email': 'bad', 'age': -1},
]

bv = BatchValidator(schema, validator_cls=ExtendedValidator, allow_unknown=False, purge_unknown=True)
for r in bv.validate_iter(docs):
    if r.valid:
        print('OK:', r.document)
    else:
        print('ERR:', errors_as_string(r.errors))
```
