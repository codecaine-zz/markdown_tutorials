# Complete Hyperfine Tutorial: Benchmarking with Homebrew

This comprehensive tutorial will guide you through setting up and using Hyperfine for benchmarking, including installation via Homebrew, basic usage, advanced features, and practical examples.

## Installation with Homebrew

First, install Hyperfine using Homebrew:

```bash
# Install hyperfine
brew install hyperfine

# Verify installation
hyperfine --version
```

## Basic Usage Examples

### 1. Simple Command Benchmarking

Let's start with basic command benchmarking:

```bash
# Benchmark a simple command
hyperfine 'echo "Hello World"'

# Benchmark multiple commands
hyperfine \
    'echo "Hello World"' \
    'printf "%s\n" "Hello World"' \
    'cat << EOF | head -1'
```

### 2. Benchmarking with Input/Output

```bash
# Benchmark commands with input from a file
hyperfine --input-file data.txt 'grep "pattern"'

# Benchmark commands that read from stdin
hyperfine 'cat file.txt | wc -l' 'wc -l < file.txt'
```

## Advanced Usage Examples

### 3. Customizing Benchmark Runs

```bash
# Run benchmarks with specific number of runs
hyperfine --runs 10 'ls -la'

# Set minimum time for each benchmark
hyperfine --min-time 2s 'find . -name "*.txt"'

# Warm up the system before running benchmarks
hyperfine --warmup 3 'python script.py'
```

### 4. Comparing Multiple Commands

```bash
# Compare multiple commands with statistical analysis
hyperfine \
    --runs 5 \
    --warmup 2 \
    'gzip file.txt' \
    'bzip2 file.txt' \
    'xz file.txt'

# Save results to a file for later analysis
hyperfine \
    --runs 10 \
    --output results.json \
    'python script.py' \
    'node script.js'
```

### 5. Advanced Benchmarking with Variables

```bash
# Create a benchmark script that uses variables
cat > benchmark.sh << 'EOF'
#!/bin/bash
# This script benchmarks different sorting methods
echo "Benchmarking sort commands..."
hyperfine \
    --runs 5 \
    --warmup 2 \
    'sort data.txt' \
    'sort -k1,1 data.txt' \
    'sort -n data.txt' \
    --output results.json

# Create performance report
echo "Performance Report:"
jq '.results[] | "\(.command): \(.mean) ± \(.stddev)"' results.json
EOF

chmod +x benchmark.sh
```

## Practical Real-World Examples

### 6. File Processing Benchmarking

```bash
#!/bin/bash
# Create sample data for testing
create_sample_data() {
    echo "Creating sample data file..."
    seq 1 100000 > large_file.txt
    
    # Add some complexity with mixed content
    for i in {1..5}; do
        echo "Line $i: This is a test line" >> large_file.txt
    done
}

# Benchmark different file processing approaches
benchmark_file_processing() {
    echo "Benchmarking file processing methods..."
    
    hyperfine \
        --runs 3 \
        --warmup 1 \
        'grep -c "test" large_file.txt' \
        'awk "/test/ {count++} END {print count}" large_file.txt' \
        'sed -n "/test/p" large_file.txt | wc -l'
}

# Run the tests
create_sample_data
benchmark_file_processing

# Clean up
rm -f large_file.txt results.json
```

### 7. Programming Language Performance Comparison

```python
#!/usr/bin/env python3
# Save as benchmark_languages.py

import subprocess
import json
from datetime import datetime

def create_test_files():
    """Create test files for different languages"""
    
    # Python script
    with open('test_python.py', 'w') as f:
        f.write("""
import sys
data = [i**2 for i in range(1000)]
print(sum(data))
""")
    
    # JavaScript (Node.js)
    with open('test_node.js', 'w') as f:
        f.write("""
const data = Array.from({length: 1000}, (_, i) => i ** 2);
console.log(data.reduce((sum, val) => sum + val, 0));
""")
    
    # C program
    with open('test_c.c', 'w') as f:
        f.write("""
#include <stdio.h>
#include <stdlib.h>

int main() {
    long long sum = 0;
    for (int i = 0; i < 1000; i++) {
        sum += (long long)i * i;
    }
    printf("%lld\\n", sum);
    return 0;
}
""")
    
    # Compile C program
    subprocess.run(['gcc', '-O2', 'test_c.c', '-o', 'test_c'])

def benchmark_languages():
    """Benchmark different programming languages"""
    
    commands = [
        ('Python', 'python3 test_python.py'),
        ('Node.js', 'node test_node.js'),
        ('C (compiled)', './test_c')
    ]
    
    print("Benchmarking programming languages...")
    
    # Create a comprehensive benchmark
    cmd_list = [cmd for _, cmd in commands]
    hyperfine(
        '--runs 5',
        '--warmup 2',
        *cmd_list,
        output='language_benchmark.json'
    )
    
    print("Results saved to language_benchmark.json")

def cleanup():
    """Clean up test files"""
    subprocess.run(['rm', '-f', 'test_python.py', 'test_node.js', 'test_c.c', './test_c'])

# Run the benchmark
if __name__ == "__main__":
    create_test_files()
    benchmark_languages()
    cleanup()
```

### 8. Database Query Performance

```bash
#!/bin/bash
# Benchmark different database query approaches

# Create sample data (mock database)
create_mock_database() {
    echo "Creating mock database..."
    
    # Generate some test data
    for i in {1..1000}; do
        echo "$i,User $i,Email$user$i@example.com" >> users.csv
    done
    
    # Create a simple SQLite database
    sqlite3 test.db << EOF
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name TEXT,
    email TEXT
);
.mode csv
.import users.csv users
EOF
}

# Benchmark different approaches to querying data
benchmark_database_queries() {
    echo "Benchmarking database queries..."
    
    hyperfine \
        --runs 5 \
        --warmup 2 \
        'sqlite3 test.db "SELECT COUNT(*) FROM users;"' \
        'sqlite3 test.db "SELECT * FROM users WHERE id = 500;"' \
        'echo "select count(*) from users;" | sqlite3 test.db'
}

# Run the benchmark
create_mock_database
benchmark_database_queries

# Clean up
rm -f users.csv test.db
```

## Complex Benchmarking Examples

### 9. Network Performance Testing

```bash
#!/bin/bash
# Test network performance with different tools

echo "Testing HTTP request performance..."

hyperfine \
    --runs 10 \
    --warmup 3 \
    'curl -s https://httpbin.org/delay/1' \
    'wget -qO- https://httpbin.org/delay/1' \
    'httpie https://httpbin.org/delay/1'

# Test different connection methods
echo "Testing different connection approaches..."

hyperfine \
    --runs 5 \
    --warmup 2 \
    'curl -s --connect-timeout 3 https://google.com' \
    'curl -s --max-time 5 https://google.com' \
    'timeout 10 curl -s https://google.com'
```

### 10. System Resource Usage Monitoring

```bash
#!/bin/bash
# Benchmark with resource monitoring

# Create a CPU-intensive benchmark
create_cpu_benchmark() {
    cat > cpu_test.sh << 'EOF'
#!/bin/bash
# Simple CPU intensive task
for i in {1..1000}; do
    echo $((i**2)) > /dev/null 2>&1
done
EOF

chmod +x cpu_test.sh
}

# Benchmark with custom script
benchmark_with_resources() {
    echo "Benchmarking with resource usage..."
    
    # This would require external monitoring, but here's the basic structure
    hyperfine \
        --runs 3 \
        --warmup 1 \
        './cpu_test.sh' \
        'sleep 1'
}

create_cpu_benchmark
benchmark_with_resources
```

## Complete Benchmark Suite Script

```bash
#!/bin/bash
# comprehensive_hyperfine_benchmark.sh

set -e

BENCHMARK_DIR="/tmp/hyperfine_benchmarks"
mkdir -p "$BENCHMARK_DIR"

echo "Starting comprehensive Hyperfine benchmarking tutorial..."

# Function to run a simple benchmark and capture output
run_benchmark() {
    local name="$1"
    local command="$2"
    
    echo "Running: $name"
    echo "Command: $command"
    
    hyperfine \
        --runs 3 \
        --warmup 1 \
        --output "${BENCHMARK_DIR}/${name}.json" \
        "$command"
    
    echo "Results saved to ${BENCHMARK_DIR}/${name}.json"
}

# Create test data
echo "Creating test data..."
cat > "${BENCHMARK_DIR}/test_data.txt" << EOF
Line 1: This is a sample text file for testing.
Line 2: Hyperfine can benchmark various operations.
Line 3: Including file processing tasks.
EOF

# Run various benchmarks
run_benchmark "file_processing" "cat ${BENCHMARK_DIR}/test_data.txt"
run_benchmark "string_operations" "echo 'Hello World' | wc -c"
run_benchmark "math_operations" "python3 -c \"print(sum(range(1000)))\""

# Simple performance comparison
echo "
=== Performance Comparison ===
Running multiple benchmarks to compare system performance:

$(hyperfine --runs 3 \
    --warmup 1 \
    'ls /usr/bin | head -5' \
    'find /usr/bin -maxdepth 1 -type f | head -5' \
    --output="${BENCHMARK_DIR}/comparison.json")
"

echo "
=== Tutorial Complete ===
Benchmark results are saved in $BENCHMARK_DIR
Use: cat ${BENCHMARK_DIR}/*.json to view detailed results
"

# Cleanup function (optional)
cleanup() {
    echo "Cleaning up temporary files..."
    rm -rf "$BENCHMARK_DIR"
}

# Uncomment the line below if you want auto-cleanup
# cleanup

echo "
To analyze results:
1. View raw JSON output: cat ${BENCHMARK_DIR}/*.json
2. Visualize using hyperfine's built-in features
3. Compare performance between different commands
"

```

This comprehensive set of examples demonstrates various ways to use Hyperfine for benchmarking:

1. **Basic operations** - Simple command execution timing
2. **File processing** - Text file handling and manipulation  
3. **Mathematical computations** - CPU-intensive tasks
4. **Network operations** - HTTP request performance testing
5. **Database queries** - SQL operation timing
6. **System utilities** - Comparing different tools for same tasks
7. **Resource monitoring** - Performance comparison across systems

Each example includes proper setup, execution with hyperfine parameters, and cleanup procedures to ensure clean tests without affecting your system. You can run these individually or as part of a comprehensive benchmark suite.