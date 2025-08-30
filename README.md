# Markdown Tutorials

A modern web application for browsing and viewing markdown-based tutorials with syntax highlighting, navigation, and responsive design.

## Features

- 📚 **Interactive Tutorial Browser** - Navigate through organized tutorial collections
- 🎨 **Syntax Highlighting** - Code blocks highlighted with Highlight.js for 20+ languages
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices
- 📋 **Copy to Clipboard** - One-click copying for all code blocks
- 🧭 **Smart Navigation** - Breadcrumb navigation and expandable folder structure
- 🔍 **Search & Discovery** - Easy browsing through categorized tutorials
- 🎯 **Smooth Scrolling** - In-page anchor navigation with smooth scrolling

## Screenshots

### Application Interface

![Main Interface](screenshots/1.png)
*Main tutorial browser interface with navigation sidebar and content area*

![Tutorial View](screenshots/2.png)
*Detailed tutorial view with syntax highlighting and copy functionality*

## Tutorial Categories

### 📦 Homebrew

Comprehensive guides for macOS package management:

- **CLI Tools**: bat, curl, fd-find, gawk, grep, jq, sed, wget
- **Development Tools**: Docker, FFmpeg, Git, ImageMagick, Ollama, OpenSSL
- **Programming Languages**: V Language
- **Security Tools**: Metasploit, Nmap
- **System Guides**: Keyboard shortcuts, MacBook tips, Terminal usage

### 🐍 Python Standard Library

Complete documentation for Python's built-in modules organized by category:

- Binary Data Services
- Concurrent Execution
- Cryptographic Services
- Data Compression and Archiving
- File and Directory Access
- Internet Protocols and Support
- And many more...

### 📝 Google Apps Script

Tutorials for automating Google services:

- Gmail Functions
- Google Docs automation
- Google Drive operations
- Google Sheets manipulation
- YouTube API integration

## Technology Stack

### Backend

- **PHP 7.4+** - Server-side rendering and routing
- **Parsedown** - Markdown parsing library
- **Composer** - Dependency management

### Frontend

- **Pure CSS** - Custom responsive styling
- **Highlight.js** - Syntax highlighting for 20+ languages
- **Marked.js** - Client-side markdown processing
- **Font Awesome** - Icons and visual elements

### Supported Languages

- Python, Java, PHP, JavaScript
- Bash, SQL, JSON, YAML
- Docker, Go, Rust, Swift, Kotlin
- And many more...

## Installation

### Prerequisites

- PHP 7.4 or higher
- Composer (for dependency management)
- Web server (built-in PHP server works fine for development)

### Setup

1. **Clone the repository**:

   ```bash
   git clone <repository-url>
   cd markdown_tutorials
   ```

2. **Install dependencies**:

   ```bash
   composer install
   ```

3. **Start the development server**:

   ```bash
   php -S localhost:8080
   ```

4. **Open in browser**:
   Navigate to `http://localhost:8080`

## Usage

### Adding New Tutorials

1. Create markdown files (`.md`) in the `tutorials/` directory
2. Organize them in folders for better navigation
3. Use standard markdown syntax with front matter if needed
4. Code blocks will automatically get syntax highlighting and copy buttons

Example tutorial structure:

```text
tutorials/
├── category-name/
│   ├── tutorial-1.md
│   ├── tutorial-2.md
│   └── subcategory/
│       └── advanced-tutorial.md
```

### Markdown Format

Tutorials support standard markdown with enhancements:

````markdown
# Tutorial Title

## Section Header

Regular text content with **bold** and *italic* formatting.

### Code Examples

```python
def hello_world():
    print("Hello, World!")
    return True
```

- Lists work normally
- With nested items
  - Like this

> Blockquotes for important notes
````

## Project Structure

```text
markdown_tutorials/
├── index.php                 # Main application entry point
├── assets/
│   ├── css/
│   │   └── style.css         # All application styles
│   └── js/
│       └── app.js            # Frontend functionality
├── logs/                     # Application logs
├── screenshots/              # Documentation images
├── tutorials/                # Tutorial content
│   ├── homebrew/            # Homebrew package guides
│   ├── python/              # Python documentation
│   └── google scripts/      # Google Apps Script tutorials
├── vendor/                   # Composer dependencies
│   └── parsedown/           # Markdown parser
└── README.md                # This file
```

## Key Features Explained

### Navigation System

- **Hierarchical browsing** with expandable folders
- **Breadcrumb navigation** shows current location
- **Mobile-responsive** sidebar with toggle button
- **Smart expansion** - folders open automatically when viewing contained files

### Syntax Highlighting

- Powered by **Highlight.js 11.9.0**
- Supports **20+ programming languages**
- **GitHub Dark theme** for consistent appearance
- **Automatic language detection**

### Copy Functionality

- **One-click copying** for all code blocks
- **Visual feedback** with success/error states
- **Preserves formatting** and indentation

### Responsive Design

- **Mobile-first approach**
- **Collapsible sidebar** on small screens
- **Touch-friendly** navigation elements
- **Readable typography** across all devices

## Development

### File Organization

- **Single-file application** (`index.php`) for simplicity
- **Separation of concerns** with dedicated CSS and JS files
- **Class-based architecture** with clean method separation
- **Error handling** with logging to `logs/` directory

### Adding Language Support

To add syntax highlighting for additional languages:

1. Add the language script in `index.php`:

   ```php
   <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/language-name.min.js"></script>
   ```

2. The highlighting will work automatically in code blocks:

   ````markdown
   ```language-name
   // Your code here
   ```
   ````

## Troubleshooting

### Common Issues

**Server not starting:**

- Ensure you're running from the project root directory
- Check PHP version: `php --version`
- Try a different port: `php -S localhost:3000`

**Tutorials not loading:**

- Check file permissions on `tutorials/` directory
- Verify markdown files have `.md` extension
- Check browser console for JavaScript errors

**Styling issues:**

- Clear browser cache
- Check that `assets/` directory is accessible
- Verify CSS file loads in browser developer tools

## License

This project is open source. Feel free to use, modify, and distribute according to your needs.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Support

For issues or questions:

- Check the browser console for JavaScript errors
- Review PHP error logs in `logs/php_errors.log`
- Verify file permissions and server configuration
