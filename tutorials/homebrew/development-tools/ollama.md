## Prerequisites

Before starting, ensure you have:
- macOS 10.15 or later
- Homebrew installed (if not installed yet: `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"`)

## Step 1: Install Ollama via Homebrew

```bash
# Update Homebrew
brew update

# Install Ollama
brew install ollama

# Verify installation
ollama --version
```

## Step 2: Start Ollama Service

```bash
# Start the Ollama service (automatically starts on boot)
brew services start ollama

# Or start manually
ollama serve

# Check if it's running
brew services list | grep ollama
```

## Step 3: Basic Usage Examples

### Download and Run a Model

```bash
# Pull a model (this will download the latest Llama 3 model)
ollama run llama3

# You can also specify a specific model version
ollama run llama3:8b
```

### Interactive Chat Session

```bash
# Start an interactive session with default model
ollama run llama3

# In the interactive prompt, you can ask questions:
# What is machine learning?
# Explain quantum computing in simple terms.
# Write a poem about programming.

# Exit the session by typing 'exit' or pressing Ctrl+C
```

### Run Model Without Interactive Session

```bash
# Simple question without entering interactive mode
ollama run llama3 "What is the capital of France?"

# Multiple questions with context
ollama run llama3 "Explain the difference between AI and ML in one sentence."
ollama run llama3 "How does a neural network learn?"
```

## Step 4: Working with Different Models

### List Available Models

```bash
# See all downloaded models
ollama list

# Pull additional models (if needed)
ollama pull mistral
ollama pull phi3
ollama pull gemma
```

### Run Specific Models

```bash
# Run Mistral model
ollama run mistral "Summarize the benefits of using renewable energy."

# Run Phi-3 model
ollama run phi3 "Write a short story about a robot learning to paint."

# Run Gemma model
ollama run gemma "Explain how photosynthesis works."
```

## Step 5: Advanced Usage Examples

### Custom Prompt with Model

```bash
# Create custom prompt for code generation
ollama run llama3 'You are a helpful coding assistant. Explain Python decorators in simple terms.'
```

### Using Ollama API Programmatically (Python)

Create `ollama_api.py`:

```python
import requests
import json

def query_ollama(prompt, model="llama3"):
    """
    Query Ollama with a prompt and return the response
    """
    url = "http://localhost:11434/api/generate"
    
    data = {
        "model": model,
        "prompt": prompt,
        "stream": False
    }
    
    response = requests.post(url, json=data)
    
    if response.status_code == 200:
        result = response.json()
        return result.get('response', 'No response received')
    else:
        return f"Error: {response.status_code}"

# Example usage
if __name__ == "__main__":
    # Test query
    prompt = "What is the meaning of life?"
    answer = query_ollama(prompt)
    print(f"Question: {prompt}")
    print(f"Answer: {answer}")
```

Run with:
```bash
python3 ollama_api.py
```

### Batch Processing Example

Create `batch_processor.py`:

```python
import subprocess
import json

def run_ollama_command(prompt, model="llama3"):
    """Execute Ollama command and return result"""
    try:
        # Run the command and capture output
        result = subprocess.run([
            'ollama', 'run', model, prompt
        ], capture_output=True, text=True, check=True)
        
        return result.stdout.strip()
    except subprocess.CalledProcessError as e:
        return f"Error: {e.stderr}"

def process_batch(questions):
    """Process multiple questions"""
    results = []
    
    for i, question in enumerate(questions):
        print(f"\nProcessing question {i+1}: {question}")
        
        answer = run_ollama_command(question)
        results.append({
            'question': question,
            'answer': answer
        })
        
        print(f"Answer: {answer}")
    
    return results

# Example usage
if __name__ == "__main__":
    questions = [
        "What is artificial intelligence?",
        "Explain the concept of neural networks",
        "How do I start learning Python programming?",
        "What are renewable energy sources?"
    ]
    
    batch_results = process_batch(questions)
    
    # Save results to JSON file
    with open('ollama_results.json', 'w') as f:
        json.dump(batch_results, f, indent=2)
    
    print("\nResults saved to ollama_results.json")
```

## Step 6: Configuration and Customization

### Check Ollama Settings

```bash
# View current configuration
ollama --help

# See specific model information
ollama show llama3

# List all available models on the hub
ollama list | grep -v "NAME"
```

### Configure Model Settings (if supported)

```bash
# Note: Some configurations require editing config files or using environment variables
# For example, setting temperature for generation:
export OLLAMA_DEBUG=1  # Enable debug mode

# Restart service after changes
brew services restart ollama
```

## Step 7: Troubleshooting Common Issues

### Issue 1: Service Not Starting

```bash
# Check service status
brew services list | grep ollama

# Try restarting the service
brew services stop ollama
brew services start ollama

# View logs for errors
brew services list | grep -A5 -B5 ollama
```

### Issue 2: Port Conflicts

```bash
# Check if port 11434 is in use
lsof -i :11434

# If needed, change the port (requires manual configuration)
# This typically involves editing Ollama's config file or using environment variables
```

### Issue 3: Model Download Problems

```bash
# Clear cache and retry download
ollama ps --filter status=down
ollama rm [model_name]
ollama pull [model_name]

# Check disk space (required for model downloads)
df -h
```

## Step 8: Complete Usage Script

Create `complete_ollama_tutorial.sh`:

```bash
#!/bin/bash

echo "=== Ollama Installation and Tutorial ==="

# Check if ollama is installed
if ! command -v ollama &> /dev/null; then
    echo "Installing Ollama via Homebrew..."
    brew install ollama
fi

# Start service
echo "Starting Ollama service..."
brew services start ollama

# Show current status
echo "Ollama status:"
ollama --version

# Pull a model if needed
echo "Pulling Llama3 model..."
ollama pull llama3

echo ""
echo "=== Tutorial Complete ==="
echo "Try these commands now:"
echo "1. ollama run llama3"
echo "2. ollama run llama3 'What is machine learning?'"
echo "3. ollama list"
```

Make executable and run:
```bash
chmod +x complete_ollama_tutorial.sh
./complete_ollama_tutorial.sh
```

## Additional Resources

### Useful Commands Reference

```bash
# View all commands
ollama --help

# Show specific model info
ollama show llama3

# List local models
ollama list

# Remove a model
ollama rm [model_name]

# Generate with streaming (for long responses)
ollama run llama3 "Write a detailed explanation of quantum computing. Be thorough."

# Stop the service when done
brew services stop ollama
```

### Environment Variables (Optional)

```bash
# Set custom Ollama directory
export OLLAMA_MODELS="/path/to/custom/models"

# Set HTTP proxy if needed
export http_proxy=http://your.proxy:port
export https_proxy=https://your.proxy:port
```

This tutorial provides a complete guide to installing and using Ollama with Homebrew, including basic usage, advanced examples, troubleshooting tips, and practical applications for different use cases.

