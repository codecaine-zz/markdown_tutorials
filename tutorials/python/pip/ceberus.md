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