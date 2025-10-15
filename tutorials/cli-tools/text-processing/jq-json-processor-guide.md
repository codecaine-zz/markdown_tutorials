# Complete jq Tutorial: JSON Processing Made Easy

## What is jq?

jq is a powerful command-line JSON processor that allows you to filter, transform, and manipulate JSON data. It's like grep for JSON but much more powerful.

## Table of Contents

1. [Installation](#installation)
2. [Basic Concepts](#basic-concepts)
3. [Getting Started with Examples](#getting-started-with-examples)
4. [Filtering Data](#filtering-data)
5. [Transforming Data](#transforming-data)
6. [Working with Arrays](#working-with-arrays)
7. [Advanced Operations](#advanced-operations)
8. [String Manipulation](#string-manipulation)
9. [Error Handling and Debugging](#error-handling-and-debugging)

## Installation

### macOS
```bash
brew install jq
```

### Ubuntu/Debian
```bash
sudo apt-get install jq
```

### Windows (WSL or PowerShell)
```powershell
# Using Chocolatey
choco install jq

# Using winget (Windows 10+)
winget install jq
```

## Basic Concepts

jq works by taking JSON input and applying filters to transform it. The basic syntax is:
```bash
jq 'filter' file.json
# or
echo '{"key": "value"}' | jq '.'
```

The `.` represents the identity filter - it outputs the entire input.

## Getting Started with Examples

Let's start with some simple examples using this sample JSON:

```json
{
  "users": [
    {
      "id": 1,
      "name": "Alice Johnson",
      "email": "alice@example.com",
      "age": 30,
      "active": true,
      "role": "admin"
    },
    {
      "id": 2,
      "name": "Bob Smith",
      "email": "bob@example.com",
      "age": 25,
      "active": false,
      "role": "user"
    },
    {
      "id": 3,
      "name": "Charlie Brown",
      "email": "charlie@example.com",
      "age": 35,
      "active": true,
      "role": "moderator"
    }
  ],
  "total_count": 3
}
```

Save this as `sample.json` for testing.

### Basic Operations

```bash
# Output the entire JSON
jq '.' sample.json

# Get a specific field
jq '.users' sample.json

# Get first user's name
jq '.users[0].name' sample.json

# Get all names
jq '.users[].name' sample.json
```

## Filtering Data

### Basic Filtering with `select()`

```bash
# Filter users by age (older than 28)
jq 'select(.age > 28)' sample.json

# Filter active users
jq '.users | map(select(.active == true))' sample.json

# Multiple conditions
jq '.users | map(select(.age >= 30 and .role == "admin"))' sample.json
```

### Using `map()` for Transformations

```bash
# Get all user names using map
jq 'map(.name)' sample.json

# Filter and transform in one step
jq 'map(select(.active == true) | {id: .id, name: .name})' sample.json

# Transform users to include uppercase names
jq 'map({id: .id, name: (.name | ascii_upcase)})' sample.json
```

### Complex Filtering with Conditions

```bash
# Users who are active AND older than 25
jq '.users[] | select(.active == true and .age > 25)' sample.json

# Users whose names start with "A" or "B"
jq '.users[] | select(.name | startswith("A") or startswith("B"))' sample.json

# Users with email domains
jq '.users[] | {name: .name, domain: (.email | split("@")[1])}' sample.json
```

## Transforming Data

### Renaming Fields and Creating New Structures

```bash
# Rename fields in user objects
jq 'map({user_id: .id, full_name: .name, email_address: .email})' sample.json

# Create a summary object
jq '{total_users: (.users | length), active_users: (.users | map(select(.active == true)) | length)}' sample.json

# Transform users into simple list with id and name only
jq '.users[] | {id, name}' sample.json
```

### Calculating Values

```bash
# Calculate average age of all users
jq '[.users[].age] | add / length' sample.json

# Get max age
jq '[.users[].age] | max' sample.json

# Get min age
jq '[.users[].age] | min' sample.json

# Age groups
jq 'map({name: .name, age_group: (if .age < 30 then "young" elif .age < 40 then "middle" else "senior" end)})' sample.json
```

## Working with Arrays

### Array Operations

```bash
# Get first user
jq '.users[0]' sample.json

# Get last user (using length)
jq '.users[.users | length - 1]' sample.json

# First three users
jq '.users[0:3]' sample.json

# All users except the first one
jq '.users[1:]' sample.json

# Users from index 1 to 2 (exclusive)
jq '.users[1:2]' sample.json
```

### Array Manipulation Functions

```bash
# Sort users by age (ascending)
jq 'sort_by(.age)' sample.json

# Sort users by name
jq 'sort_by(.name)' sample.json

# Reverse array order
jq 'reverse' sample.json

# Unique values in array
jq '.users | map(.role) | unique' sample.json

# Flatten nested arrays (if any)
jq '.users[] | {id: .id, roles: [.role]}' sample.json
```

### Array Filtering and Transformation

```bash
# Filter users by role using index matching
jq 'map(select(.role == "admin"))' sample.json

# Get all unique roles in a specific order
jq '.users | map(.role) | sort | unique' sample.json

# Count users by role
jq '.users | group_by(.role) | map({role: .[0].role, count: length})' sample.json

# Create lookup table of user IDs to names
jq 'map({key: .id, value: .name})' sample.json
```

## Advanced Operations

### Using Variables and Custom Functions

```bash
# Store results in variables for reuse
jq --arg name "Alice" '.users[] | select(.name == $name)' sample.json

# Complex transformations with multiple steps
jq '
  .users 
  | map(
      {
        id: .id,
        full_name: .name,
        email_domain: (.email | split("@")[1]),
        status: (if .active then "active" else "inactive" end)
      }
    )
' sample.json

# Nested transformations
jq '
  {users: 
    [(.users[] | {
      id: .id,
      details: {
        name: .name,
        email_info: (.email | split("@") | {username: .[0], domain: .[1]})
      }
    })]
  }
' sample.json
```

### Conditional Logic and Error Handling

```bash
# Safe field access (returns null if field doesn't exist)
jq '.users[].phone // "No phone"' sample.json

# Conditional transformations with default values
jq '
  map(
    {
      id: .id,
      name: .name,
      phone: (.phone // "N/A"),
      status: (.active | if . then "active" else "inactive" end)
    }
  )
' sample.json

# Transform based on multiple conditions
jq '
  map(
    {
      user_id: .id,
      display_name: .name,
      category: (
        if .age < 25 then "young"
        elif .age <= 30 then "adult" 
        else "senior"
        end
      )
    }
  )
' sample.json
```

### Working with Nested JSON

```bash
# Example with nested structure
cat > nested.json << EOF
{
  "company": {
    "name": "Tech Corp",
    "departments": [
      {
        "name": "Engineering",
        "employees": [
          {"id": 1, "name": "John", "skills": ["javascript", "python"]},
          {"id": 2, "name": "Jane", "skills": ["java", "go"]}
        ]
      },
      {
        "name": "Marketing",
        "employees": [
          {"id": 3, "name": "Bob", "skills": ["seo", "content"]}
        ]
      }
    ]
  }
}
EOF

# Extract all employee names
jq '.company.departments[].employees[].name' nested.json

# Get employees with specific skills
jq '.company.departments[].employees[] | select(.skills | index("javascript"))' nested.json

# Flatten the structure to get all skills for all employees
jq '.company.departments[].employees[].skills[]' nested.json
```

### String and Text Processing

```bash
# Convert to uppercase and lowercase
jq 'map(.name | ascii_upcase)' sample.json

# Get string length
jq 'map(.name | length)' sample.json

# Extract substring (first 3 characters)
jq 'map(.name | .[0:3])' sample.json

# Replace text in strings
jq '.users[] | {id: .id, name: (.name | gsub("John"; "Jane"))}' sample.json

# Join arrays into strings
jq '.users[].skills | join(", ")' sample.json

# Split strings
jq 'map(.name | split(" "))' sample.json
```

### Date and Time Operations (if data contains dates)

```bash
# If you have date fields, you can do:
# jq --argjson date1 "2023-01-01" '.users[] | select(.created_at > $date1)' sample.json

# Format dates using external tools or jq's built-in functions where available
jq 'map({id: .id, name: .name, created_month: (.created_at | split("-")[1])})' sample.json
```

### Performance and Optimization Tips

```bash
# Use --compact-output flag for compact JSON
# jq -c '.users[] | select(.active)' sample.json

# For large files, process in chunks or use streaming:
# jq 'limit(10; .users[])' sample.json  # Process first 10 items only

# Use index-based access when possible instead of filtering
jq '.users[0]' sample.json  # Faster than filter for specific item
```

### Complex Example: Data Analysis Pipeline

```bash
# Complete example combining many concepts:
jq '
  .users 
  | map({
      id: .id,
      name: .name,
      email_domain: (.email | split("@")[1]),
      status: (.active | if . then "Active" else "Inactive" end),
      category: (
        if .age < 25 then "Young"
        elif .age <= 30 then "Adult"
        else "Senior"
        end
      ),
      skill_count: (.skills // [] | length)
    })
  | sort_by(.name)
  | {
      total_users: length,
      active_users: (map(select(.status == "Active")) | length),
      user_breakdown: (
        group_by(.category) 
        | map({category: .[0].category, count: length})
      ),
      domains: (map(.email_domain) | unique)
    }
' sample.json
```

This comprehensive example shows how to:
- Transform data structures
- Perform complex filtering and grouping
- Use conditional logic
- Calculate aggregates
- Sort results
- Generate summary statistics

The power of jq comes from its ability to chain these operations together, making it perfect for processing JSON data in command-line environments or within automated pipelines.

