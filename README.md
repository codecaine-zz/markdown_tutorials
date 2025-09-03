# Markdown Tutorials

A modern web application for browsing and viewing markdown-based tutorials with syntax highlighting, navigation, and responsive design.

## Related: neu_markdown_buddy (desktop, web, cloud)

If you prefer a native deskto- **Toggleabl- **Toggleab- # Markdown Tutorials

A modern, feature-rich web application for browsing and viewing markdown-based tutorials with advanced search, bookmarking, progress tracking, and responsive design.

## 🚀 Latest Features & Improvements

### Enhanced User Experience
- **🔍 Fuzzy Search System** - Smart search with highlighting, suggestions, and history
- **📖 Reading Progress Tracking** - Automatic progress tracking with visual indicators
- **🔖 Advanced Bookmark System** - One-click bookmarking with progress persistence
- **📱 Quick Actions Menu** - Floating action button with bookmark, history, print, and share
- **⏱️ Recent History** - Automatically tracks and displays recently viewed tutorials
- **⌨️ Enhanced Keyboard Shortcuts** - Comprehensive shortcuts for power users
- **🔔 Smart Notifications** - Toast notifications for user actions and feedback
- **🎨 Improved Dark Theme** - Complete dark mode support for all features
- **🖨️ Print-Optimized Styles** - Clean print layout removing navigation elements
- **♿ Better Accessibility** - Keyboard navigation, focus styles, and ARIA labels
- **📱 Mobile Enhancements** - Touch-friendly interactions and responsive design

## Related: neu_markdown_buddy (desktop, web, cloud)

If you prefer a native desktop experience, check out neu_markdown_buddy — a cross‑platform NeutralinoJS port of this project for macOS, Windows, and Linux.

- Repository: [codecaine-zz/neu_markdown_buddy](https://github.com/codecaine-zz/neu_markdown_buddy)
- Platforms: macOS, Windows, Linux (desktop); core UI also runs in a regular browser
- Highlights: advanced navigation, smart search, ToC, code copy, theme + zoom, random tutorial, and persistent UI state

Quick desktop start (summary):
- Install Node.js or Bun and the NeutralinoJS CLI (@neutralinojs/neu)
- In the neu_markdown_buddy repo: run `neu update`, then `neu run` for dev or `neu build` for distributable binaries

Use your existing tutorials with the desktop app:
- Copy or symlink this repo's `tutorials/` into neu_markdown_buddy at `resources/tutorials/` to browse the same content in the desktop UI

Website/cloud usage options:
- Host this PHP app (current repo) on any PHP-capable server or platform (see Installation below)
- Or serve neu_markdown_buddy's `resources/` (index.html, styles, main.js, libs) as a static site; desktop‑only features (e.g., tray, always‑on‑top, native FS access) won't apply in browsers, but the core reader/navigation work well

## Features

### Core Functionality
- 📚 **Interactive Tutorial Browser** - Navigate through organized tutorial collections
- 🎨 **Syntax Highlighting** - Code blocks highlighted with Highlight.js for 20+ languages
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices
- 📋 **Copy to Clipboard** - One-click copying for all code blocks
- 🧭 **Smart Navigation** - Breadcrumb navigation and expandable folder structure
- 🧩 **Auto-Display Table of Contents** - TOC automatically appears for all tutorials with dynamic toggle functionality
- 🧱 **Sidebar Width Toggle** - Expand/collapse the sidebar for focus or overview
- 🎯 **Smooth Scrolling** - In-page anchor navigation with smooth scrolling and target highlight
- 🧰 **Top Toolbar** - Home, Collapse/Expand All, Refresh, Theme, and Zoom controls
- 🌗 **Theme Toggle** - Light/Dark theme with instant switch and saved preference
- 🔎 **Zoom Controls** - 80–180% zoom with live indicator and persistence
- 💾 **Persistent UI State** - Remembers theme, zoom level, sidebar width, and folder open/closed states
- 🧮 **Homepage Insights** - Stats (total tutorials, categories, last updated), browse-by-category, and recently added list
- 🎲 **Random Tutorial** - Jump to a randomly selected guide from the homepage
- 🖼️ **SVG Favicon + Theme Color** - Crisp tab icon and browser UI color via `<link rel="icon">` and `meta theme-color`

### Advanced Features
- **🔍 Enhanced Search System**
  - Fuzzy search that finds tutorials even with partial matches
  - Search highlighting with query terms highlighted in results
  - Search history with recent searches saved and suggested
  - Search suggestions dropdown with recent search terms
  - Better results display showing full path context and result count

- **🔖 Advanced Bookmark System**
  - One-click bookmarking with bookmark button next to tutorials
  - Bookmark management modal showing all saved tutorials
  - Reading progress saved with bookmarks for resuming later
  - Quick access through floating action button
  - Metadata including bookmark date and reading progress

- **📖 Reading Progress Tracking**
  - Automatic tracking of how much of each tutorial you've read
  - Visual progress indicators showing completion percentage
  - Progress persisted locally and restored on revisit
  - Progress displayed in bookmark listings

- **📱 Quick Actions Menu (FAB)**
  - Floating action button always accessible from bottom-right
  - Multiple quick actions: View Bookmarks, Recent Pages, Print, Share
  - Mobile-friendly with optimized touch targets
  - Smooth animations and intuitive interactions

- **⏱️ Recent History Tracking**
  - Automatically tracks recently viewed tutorials
  - Quick access to recent pages through FAB menu
  - Chronological order with most recent pages shown first
  - Persistent across sessions with local storage

- **⌨️ Enhanced Keyboard Shortcuts**
  - **F1**: Show keyboard shortcuts help modal
  - **Ctrl/Cmd+K**: Focus search input for quick searching
  - **Ctrl/Cmd+D**: Toggle bookmark for current tutorial
  - **Ctrl/Cmd+P**: Print current tutorial with optimized layout
  - **Ctrl/Cmd+B**: Toggle sidebar width (normal/expanded/collapsed)
  - **Ctrl/Cmd+H**: Navigate to home page
  - **+/-**: Zoom in/out for better readability
  - **Esc**: Close dialogs, clear search, or close menus

- **🔔 Smart Notification System**
  - Toast notifications for user actions (bookmark added/removed, etc.)
  - Multiple notification types: success, error, and info
  - Auto-hide after 3 seconds with manual close option
  - Non-intrusive design that doesn't block interface

- **♿ Enhanced Accessibility**
  - Comprehensive keyboard navigation for all features
  - Clear visual focus indicators for keyboard users
  - Proper ARIA labels and descriptions for screen readers
  - High contrast mode support
  - Reduced motion respect for user preferences
  - Mobile accessibility with touch-friendly interactions

- **🖨️ Print-Optimized Styles**
  - Clean print layout removing navigation and interactive elements
  - Optimized typography with better font sizes and spacing
  - Smart page break handling around headings and code blocks
  - Print-friendly color scheme and layout adjustments

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

- **Pure CSS** - Custom responsive styling with modern features
- **Highlight.js** - Syntax highlighting for 20+ languages
- **Marked.js** - Client-side markdown processing
- **Font Awesome** - Icons and visual elements
- **LocalStorage** - Client-side data persistence for user preferences

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
   php -S localhost:8000
   ```

4. **Open in browser**:
   Navigate to `http://localhost:8000`

## Usage Guide

### Getting Started

1. **Search Tutorials**: Press `Ctrl/Cmd+K` or click the search box to try fuzzy search
2. **Bookmark Tutorials**: Navigate to any tutorial and click the bookmark button
3. **Quick Actions**: Click the blue + button in the bottom-right corner
4. **Keyboard Shortcuts**: Press `F1` or click "Help" in the toolbar to see all shortcuts

### Power User Features

- **Advanced Search**: Use partial terms - the fuzzy search will find relevant tutorials
- **Bookmark Organization**: Use bookmarks to create your personal tutorial collection
- **Progress Tracking**: Resume reading exactly where you left off with automatic progress tracking
- **Keyboard Navigation**: Learn the shortcuts (F1) for faster navigation
- **Mobile Usage**: The floating action button provides quick access to all features on mobile

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
├── favicon.svg              # SVG site favicon
├── index.php                 # Main application entry point
├── assets/
│   ├── css/
│   │   └── style.css         # All application styles (enhanced with new features)
│   └── js/
│       └── app.js            # Frontend functionality (significantly enhanced)
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

### Enhanced Navigation System

- **Hierarchical browsing** with expandable folders and improved visual hierarchy
- **Breadcrumb navigation** shows current location with clickable path segments
- **Mobile-responsive** sidebar with hamburger toggle and touch-friendly interactions
- **Smart expansion** - folders open automatically when viewing contained files
- **Search integration** - search results show full folder context

### Advanced UI Features

- **Enhanced Search System**
  - Real-time fuzzy search with intelligent matching
  - Search result highlighting with query term emphasis
  - Search history and suggestions for faster discovery
  - Context-aware results showing folder paths
  - Mobile-optimized search interface

- **Bookmark & Progress System**
  - One-click bookmarking with visual feedback
  - Reading progress automatically tracked and displayed
  - Bookmark management with metadata (date, progress)
  - Cross-session persistence with local storage
  - Quick access through floating action button

- **Rating & Community Features**
  - 5-star rating system for tutorial quality feedback
  - Average ratings displayed with vote counts
  - User-specific ratings remembered across sessions
  - Visual feedback with star animations on hover/click

- **Smart Notifications**
  - Non-intrusive toast notifications for user actions
  - Success/error/info notification types
  - Auto-hide with manual close option
  - Mobile-friendly positioning and sizing

### Improved Core Features

- **Sidebar Width Toggle**
  - Click the chevron button in the sidebar to expand/collapse it
  - The main content shifts to accommodate the chosen width
  - State persisted across page loads

- **Enhanced Table of Contents**
  - Auto-generated from headings with improved navigation
  - Toggleable visibility with preference persistence
  - Smooth scrolling with visual feedback
  - "Previous" button for returning to prior position

- **Advanced Code Block Features**
  - Always-visible copy buttons with improved mobile support
  - Success/failure feedback with visual indicators
  - Clipboard fallback for broader browser support
  - Syntax highlighting with 20+ language support

- **Top Toolbar Enhancements**
  - Quick actions: Home, Collapse All, Expand All, Refresh
  - Help button for keyboard shortcuts discovery
  - Theme toggle with improved dark mode support
  - Zoom controls with live percentage indicator

### Keyboard Shortcuts & Accessibility

- **Comprehensive Keyboard Support**
  - F1: Show keyboard shortcuts help
  - Ctrl/Cmd+K: Focus search (quick access)
  - Ctrl/Cmd+D: Toggle bookmark current tutorial
  - Ctrl/Cmd+P: Print with optimized layout
  - Ctrl/Cmd+B: Toggle sidebar width
  - Ctrl/Cmd+H: Navigate to home
  - +/-: Zoom in/out
  - Esc: Universal close/cancel action

- **Accessibility Improvements**
  - Clear focus indicators for keyboard navigation
  - ARIA labels and descriptions for screen readers
  - High contrast mode support
  - Reduced motion respect
  - Mobile accessibility enhancements

### Data Persistence

- **Local Storage Features**
  - User preferences (theme, zoom, sidebar width)
  - Bookmarks with metadata and progress
  - Reading progress for all tutorials
  - Search history and suggestions
  - Recent page history

## Development

### File Organization

- **Enhanced single-file application** (`index.php`) with improved organization
- **Separation of concerns** with dedicated CSS and JS files significantly expanded
- **Class-based architecture** with clean method separation and new features
- **Error handling** with logging to `logs/` directory
- **Modular JavaScript** with feature-specific functions and improved code organization

### Performance Optimizations

- **Debounced search** to prevent excessive queries
- **Efficient local storage** usage for user data
- **Smooth animations** optimized for performance
- **Lazy loading** for better initial page load
- **Mobile-first responsive** design with optimized touch interactions

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
- Clear browser cache and local storage
- Check that `assets/` directory is accessible
- Verify CSS file loads in browser developer tools

**Features not working:**
- Ensure JavaScript is enabled in browser
- Check browser console for errors
- Verify local storage is available and not full
- Try clearing local storage data for fresh start

## Browser Compatibility

- **Modern browsers**: Full feature support (Chrome, Firefox, Safari, Edge)
- **Older browsers**: Graceful degradation with core functionality
- **Mobile browsers**: Optimized touch interactions and responsive design
- **Accessibility**: Screen reader support and keyboard navigation

## Performance

- **Fast initial load**: Optimized asset loading and minimal dependencies
- **Responsive interactions**: Debounced search and smooth animations
- **Efficient storage**: Smart use of local storage for user data
- **Mobile optimized**: Touch-friendly interface with optimized touch targets

## License

This project is open source. Feel free to use, modify, and distribute according to your needs.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly across different browsers and devices
5. Submit a pull request

## Support

For issues or questions:

- Check the browser console for JavaScript errors
- Review PHP error logs in `logs/php_errors.log`
- Verify file permissions and server configuration
- Test with local storage cleared for fresh start
- Check browser compatibility and JavaScript supportable of Contents**
  - On any tutorial page, click "Contents" to show/hide the ToC.
  - The ToC is generated from headings and supports smooth scrolling.
  - Target headings briefly highlight when navigated to.

- **Smart "Previous" Button Navigation**
  - When clicking on table of contents links or in-page anchor links, a "Previous" button appears next to the target heading.
  - The button allows you to return to your previous scroll position with one click.
  - Buttons persist until used (they remove themselves after being clicked) or until you navigate to a different section.
  - Only one "Previous" button is visible at a time - clicking new ToC links replaces the previous button location.
  - No manual dismiss option keeps the interface clean and focused on core navigation functionality.

- **Enhanced Anchor Navigation**e of Contents**
   - On any tutorial page, click "Contents" to show/hide the ToC.
   - The ToC is generated from headings and supports smooth scrolling.
   - Target headings briefly highlight when navigated to.

- **Smart "Previous" Button Navigation**
   - When clicking on table of contents links or in-page anchor links, a "Previous" button appears next to the target heading.
   - The button allows you to return to your previous scroll position with one click.
   - Buttons persist until used (they remove themselves after being clicked) or until you navigate to a different section.
   - Only one "Previous" button is visible at a time - clicking new ToC links replaces the previous button location.
   - No manual dismiss option keeps the interface clean and focused on core navigation functionality.

- **Enhanced Anchor Navigation**
   - Stable, GitHub-like heading IDs, smooth in-page links, and automatic link rewrites for reliable anchors.ience, check out neu_markdown_buddy — a cross‑platform NeutralinoJS port of this project for macOS, Windows, and Linux.

- Repository: [codecaine-zz/neu_markdown_buddy](https://github.com/codecaine-zz/neu_markdown_buddy)
- Platforms: macOS, Windows, Linux (desktop); core UI also runs in a regular browser
- Highlights: advanced navigation, smart search, ToC, code copy, theme + zoom, random tutorial, and persistent UI state

Quick desktop start (summary):

- Install Node.js or Bun and the NeutralinoJS CLI (@neutralinojs/neu)
- In the neu_markdown_buddy repo: run `neu update`, then `neu run` for dev or `neu build` for distributable binaries

Use your existing tutorials with the desktop app:

- Copy or symlink this repo’s `tutorials/` into neu_markdown_buddy at `resources/tutorials/` to browse the same content in the desktop UI

Website/cloud usage options:

- Host this PHP app (current repo) on any PHP-capable server or platform (see Installation below)
- Or serve neu_markdown_buddy’s `resources/` (index.html, styles, main.js, libs) as a static site; desktop‑only features (e.g., tray, always‑on‑top, native FS access) won’t apply in browsers, but the core reader/navigation work well

## Features

- 📚 **Interactive Tutorial Browser** - Navigate through organized tutorial collections
- 🎨 **Syntax Highlighting** - Code blocks highlighted with Highlight.js for 20+ languages
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices
- 📋 **Copy to Clipboard** - One-click copying for all code blocks
- 🧭 **Smart Navigation** - Breadcrumb navigation and expandable folder structure
- 🔍 **Search & Discovery** - Sidebar search with live filtering and auto-expanding results
- 🧩 **Auto-Display Table of Contents** - TOC automatically appears for all tutorials with dynamic toggle functionality
- 🧱 **Sidebar Width Toggle** - Expand/collapse the sidebar for focus or overview
- 🎯 **Smooth Scrolling** - In-page anchor navigation with smooth scrolling and target highlight
- 🧰 **Top Toolbar** - Home, Collapse/Expand All, Refresh, Theme, and Zoom controls
- 🌗 **Theme Toggle** - Light/Dark theme with instant switch and saved preference
- 🔎 **Zoom Controls** - 80–180% zoom with live indicator and persistence
- 💾 **Persistent UI State** - Remembers theme, zoom level, sidebar width, and folder open/closed states
- 🧮 **Homepage Insights** - Stats (total tutorials, categories, last updated), browse-by-category, and recently added list
- 🎲 **Random Tutorial** - Jump to a randomly selected guide from the homepage
- 🖼️ **SVG Favicon + Theme Color** - Crisp tab icon and browser UI color via `<link rel="icon">` and `meta theme-color`

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
├── favicon.svg              # SVG site favicon
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

## Homepage Overview

The homepage now helps you get to content faster:

- Stats summary: total tutorials, total categories, and the time since the latest update
- Quick actions: focus the sidebar search and open a random tutorial
- Browse by category: top-level folders with tutorial counts
- Recently added: most recently updated tutorials with “time ago” labels

These sections are generated dynamically from the `tutorials/` directory.

## Key Features Explained

### Navigation System

- **Hierarchical browsing** with expandable folders
- **Breadcrumb navigation** shows current location
- **Mobile-responsive** sidebar with hamburger toggle
- **Smart expansion** - folders open automatically when viewing contained files

### UI Enhancements

- **Sidebar Width Toggle**
   - Click the chevron button in the sidebar to expand/collapse it.
   - The main content shifts to accommodate the chosen width.

- **Sidebar Search (Live Filter)**
   - Type in the sidebar search box to filter tutorials by name.
   - Matching items remain visible and parent folders auto-expand for quick access.
   - Clear the query with the “×” button.

- **Toggleable Table of Contents**
   - On any tutorial page, click “Contents” to show/hide the ToC.
   - The ToC is generated from headings and supports smooth scrolling.
   - Target headings briefly highlight; a small inline “Previous” button returns to your prior scroll position.

- **Enhanced Anchor Navigation**
   - Stable, GitHub-like heading IDs, smooth in-page links, and automatic link rewrites for reliable anchors.

- **Improved Code Copy UX**
   - Always-visible copy buttons on code blocks (mobile friendly), with success/failure feedback and clipboard fallback.

- **Top Toolbar**
   - Quick actions: Home, Collapse All, Expand All, Refresh.
   - Theme toggle (sun/moon) and Zoom controls with a live percentage indicator.

- **Theme & Zoom Persistence**
   - Theme and zoom preferences are saved automatically and restored on page load.
   - Zoom range: 80–180% for comfortable reading.

- **Bulk Sidebar Actions & Busy Overlay**
   - Collapse/Expand All folders with a brief, non-blocking spinner overlay.
   - Keeps the UI responsive during batch updates.

- **Persistent Navigation State**
   - Remembers sidebar width state (normal/expanded/collapsed).
   - Restores open/closed states of folders across visits.

- **Keyboard Shortcuts**
   - F1: Show shortcut help
   - Cmd/Ctrl+F: Focus sidebar search
   - Cmd/Ctrl+B: Toggle sidebar width
   - Cmd/Ctrl+H: Home
   - Plus/Minus: Zoom in/out

### What’s New

Latest updates:

- **Auto-Toggle Table of Contents**: TOC now appears automatically when viewing any markdown tutorial, with improved toggle functionality and dynamic button states
- New homepage insights panel with quick actions (focus search, random tutorial), stats, browse-by-category, and recently added tutorials
- Added SVG favicon (`favicon.svg`) and `theme-color` for better browser tab and mobile UI integration

Previously shipped:

- Top toolbar, dark/light theme toggle, zoom controls with live indicator
- Batch expand/collapse for the sidebar and persistent UI state (theme, zoom, sidebar width, folder states)

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

## Favicon and Theme Color

- Favicon lives at the repo root as `favicon.svg`
- Included via:
  - `<link rel="icon" type="image/svg+xml" href="/favicon.svg">`
  - `<meta name="theme-color" content="#0f172a">`

Modern browsers will show the SVG in the tab bar and use the theme color for UI accents (especially on mobile).

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
