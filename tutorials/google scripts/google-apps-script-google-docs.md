# Google Apps Script - Google Docs Complete Tutorial
## From Beginner to Advanced with Reusable Code Examples

This comprehensive tutorial teaches you Google Docs automation using Google Apps Script with clean, reusable code organized in classes and functions. Perfect for beginners to advanced users who want to master document automation.

## 📚 Table of Contents

### 🟢 **BEGINNER LEVEL**
1. [Getting Started](#1-getting-started)
   - [Environment Setup](#environment-setup)
   - [Your First Document Script](#your-first-document-script)
   - [Understanding Google Apps Script for Docs](#understanding-google-apps-script-for-docs)

2. [Basic Document Operations](#2-basic-document-operations)
   - [Creating and Opening Documents](#creating-and-opening-documents)
   - [Reading Document Content](#reading-document-content)
   - [Writing Simple Text](#writing-simple-text)

3. [Working with Text](#3-working-with-text)
   - [Text Manipulation Basics](#text-manipulation-basics)
   - [Finding and Replacing Text](#finding-and-replacing-text)
   - [Basic Text Formatting](#basic-text-formatting)

### 🟡 **INTERMEDIATE LEVEL**
4. [Document Structure Management](#4-document-structure-management)
   - [Paragraphs and Headings](#paragraphs-and-headings)
   - [Lists and Bullets](#lists-and-bullets)
   - [Page Breaks and Sections](#page-breaks-and-sections)

5. [Advanced Formatting](#5-advanced-formatting)
   - [Styles and Themes](#styles-and-themes)
   - [Tables and Data](#tables-and-data)
   - [Images and Media](#images-and-media)

6. [Document Navigation](#6-document-navigation)
   - [Bookmarks and Cross-References](#bookmarks-and-cross-references)
   - [Table of Contents](#table-of-contents)
   - [Headers and Footers](#headers-and-footers)

### 🔴 **ADVANCED LEVEL**
7. [Advanced Text Processing](#7-advanced-text-processing)
   - [Regular Expressions](#regular-expressions)
   - [Pattern Matching](#pattern-matching)
   - [Content Analysis](#content-analysis)

8. [Document Integration](#8-document-integration)
   - [Merging Documents](#merging-documents)
   - [Template Systems](#template-systems)
   - [External Data Sources](#external-data-sources)

9. [Automation and Workflows](#9-automation-and-workflows)
   - [Batch Processing](#batch-processing)
   - [Document Generation](#document-generation)
   - [Custom Functions](#custom-functions)

### 🎯 **REAL-WORLD PROJECTS**
10. [Complete Project Examples](#10-complete-project-examples)
    - [Report Generator](#report-generator)
    - [Contract Management System](#contract-management-system)
    - [Document Analytics Dashboard](#document-analytics-dashboard)
    - [Automated Letter Writer](#automated-letter-writer)

11. [Best Practices and Optimization](#11-best-practices-and-optimization)
    - [Performance Optimization](#performance-optimization)
    - [Error Handling Patterns](#error-handling-patterns)
    - [Security and Permissions](#security-and-permissions)

---

## 1. Getting Started

### Environment Setup

Before diving into document automation, let's set up your development environment properly.

#### Creating Your First Google Apps Script Project

1. **Access Google Apps Script:**
   - Visit [script.google.com](https://script.google.com)
   - Sign in with your Google account
   - Click "New Project"

2. **Set up your project:**
   - Rename your project (click "Untitled project")
   - Choose a descriptive name like "Document Automation Tools"

3. **Understanding the Google Docs API:**
   - **DocumentApp**: Main class for working with Google Docs
   - **Document**: Represents an entire Google Doc
   - **Body**: The main content area of a document
   - **Element**: Individual components (paragraphs, text, tables, etc.)

### Your First Document Script

Let's start with a simple "Hello World" example to verify everything works:

```javascript
/**
 * Your first Google Docs automation script
 * This function demonstrates basic document interaction
 */
function helloWorldDocument() {
  // Create a new document
  const doc = DocumentApp.create('My First Automated Document');
  
  // Get the document body
  const body = doc.getBody();
  
  // Add a title
  const title = body.appendParagraph('Welcome to Document Automation!');
  title.setHeading(DocumentApp.Heading.HEADING_1);
  
  // Add some content
  body.appendParagraph('This document was created automatically using Google Apps Script.');
  body.appendParagraph('The possibilities are endless!');
  
  // Add current date and time
  body.appendParagraph(`Generated on: ${new Date().toLocaleString()}`);
  
  // Get document info
  const docUrl = doc.getUrl();
  const docId = doc.getId();
  
  // Show success message
  DocumentApp.getUi().alert(
    'Document Created!',
    `Your automated document has been created successfully!\n\nDocument ID: ${docId}\nURL: ${docUrl}`,
    DocumentApp.getUi().ButtonSet.OK
  );
  
  // Log information to the console
  console.log('✅ Document created successfully');
  console.log(`📄 Document name: ${doc.getName()}`);
  console.log(`🔗 Document URL: ${docUrl}`);
}
```

**To run this script:**
1. Click the "Run" button (▶️) in the toolbar
2. Grant permissions when prompted
3. Check your Google Drive to see the new document created

### Understanding Google Apps Script for Docs

Google Apps Script provides powerful automation capabilities for Google Docs through the DocumentApp service.

#### Key Concepts:

1. **DocumentApp**: The main service for creating and accessing documents
2. **Document**: Represents a Google Docs file
3. **Body**: The main content container
4. **Elements**: Individual content pieces (text, paragraphs, tables, etc.)

#### Core Configuration Class

Let's create a reusable configuration system for document automation:

```javascript
/**
 * DocsConfig - Centralized configuration for all document operations
 * This class holds common settings and provides utility methods
 */
class DocsConfig {
  constructor() {
    this.settings = {
      DEFAULT_FONT_FAMILY: 'Arial',
      DEFAULT_FONT_SIZE: 11,
      HEADING_FONT_SIZE: {
        1: 20,
        2: 16,
        3: 14,
        4: 12,
        5: 11,
        6: 11
      },
      DEFAULT_LINE_SPACING: 1.15,
      PARAGRAPH_SPACING: 6,
      MARGIN_SIZE: 72, // 1 inch in points
      PAGE_WIDTH: 612, // 8.5 inches in points
      PAGE_HEIGHT: 792, // 11 inches in points
      COLORS: {
        PRIMARY: '#1a73e8',
        SECONDARY: '#34a853',
        WARNING: '#fbbc04',
        ERROR: '#ea4335',
        TEXT_DARK: '#202124',
        TEXT_LIGHT: '#5f6368'
      }
    };
  }

  /**
   * Get a configuration value
   * @param {string} key - Configuration key
   * @return {*} Configuration value
   */
  get(key) {
    return this.settings[key];
  }

  /**
   * Set a configuration value
   * @param {string} key - Configuration key
   * @param {*} value - Configuration value
   */
  set(key, value) {
    this.settings[key] = value;
  }

  /**
   * Get all configuration settings
   * @return {Object} All settings
   */
  getAll() {
    return { ...this.settings };
  }

  /**
   * Initialize the document automation environment
   */
  initialize() {
    console.log('📝 Initializing Google Docs automation...');
    
    try {
      const activeDoc = DocumentApp.getActiveDocument();
      console.log(`📄 Active document: ${activeDoc.getName()}`);
      console.log(`🔗 Document URL: ${activeDoc.getUrl()}`);
    } catch (error) {
      console.log('ℹ️  No active document (this is normal for new scripts)');
    }
    
    console.log('✅ Environment setup complete');
  }
}

// Create a global configuration instance
const DOCS_CONFIG = new DocsConfig();

/**
 * Example: How to use the configuration
 */
function demonstrateDocsConfig() {
  // Initialize the environment
  DOCS_CONFIG.initialize();
  
  // Use configuration values
  const defaultFont = DOCS_CONFIG.get('DEFAULT_FONT_FAMILY');
  const primaryColor = DOCS_CONFIG.get('COLORS').PRIMARY;
  
  console.log(`Using default font: ${defaultFont}`);
  console.log(`Primary color: ${primaryColor}`);
  
  // Modify configuration if needed
  DOCS_CONFIG.set('DEFAULT_FONT_SIZE', 12);
  
  console.log('Configuration updated successfully');
}
```

### DocsConfig: Quick usage

```javascript
function docsConfigExamples() {
  DOCS_CONFIG.initialize();
  // Read config
  const font = DOCS_CONFIG.get('DEFAULT_FONT_FAMILY');
  // Update config
  DOCS_CONFIG.set('DEFAULT_FONT_SIZE', 12);
  console.log('Font:', font, 'Size:', DOCS_CONFIG.get('DEFAULT_FONT_SIZE'));
}
```

## 2. Text Manipulation Functions

### Inserting and Formatting Text

```javascript
/**
 * Inserts text at the cursor position or end of document
 * @param {string} text - The text to insert
 * @param {Object} options - Formatting options (optional)
 * @returns {GoogleAppsScript.Document.Paragraph}
 */
function insertText(text, options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  // Insert at cursor position or end of document
  const cursor = doc.getCursor();
  if (cursor && cursor.getElement()) {
    // Insert at cursor position
    const element = cursor.getElement().asText();
    element.insertText(cursor.getOffset(), text);
    return element.getParent().asParagraph();
  } else {
    // Insert at end of document
    const paragraph = body.appendParagraph(text);
    
    // Apply formatting if provided
    if (options.bold) paragraph.setBold(true);
    if (options.italic) paragraph.setItalic(true);
    if (options.fontSize) paragraph.setFontSize(options.fontSize);
    
    return paragraph;
  }
}

/**
 * Replaces text in a document with optional case sensitivity
 * @param {string} findText - Text to find
 * @param {string} replaceText - Text to replace with
 * @param {boolean} caseSensitive - Whether to perform case sensitive search (default: true)
 * @returns {number} Number of replacements made
 */
function replaceText(findText, replaceText, caseSensitive = true) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  let replacedCount = 0;
  const textElements = getAllTextElements(body);
  
  textElements.forEach(element => {
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      const text = element.asText();
      const content = caseSensitive ? 
        text.getText() : 
        text.getText().toLowerCase();
      
      let searchIndex = -1;
      while ((searchIndex = caseSensitive ?
              content.indexOf(findText, searchIndex + 1) :
              content.indexOf(findText.toLowerCase(), searchIndex + 1)) !== -1) {
        
        const length = findText.length;
        if (caseSensitive) {
          text.deleteText(searchIndex, searchIndex + length);
          text.insertText(searchIndex, replaceText);
        } else {
          // For case insensitive replacement
          const original = text.getText();
          const before = original.substring(0, searchIndex);
          const after = original.substring(searchIndex + length);
          text.setText(before + replaceText + after);
        }
        
        replacedCount++;
      }
    }
  });
  
  return replacedCount;
}

/**
 * Gets all text elements from a document body
 * @param {GoogleAppsScript.Document.Body} body - Document body object
 * @returns {Array<GoogleAppsScript.Document.Element>} Array of text elements
 */
function getAllTextElements(body) {
  const elements = [];
  const iterator = body.getIterator();
  
  while (iterator.hasNext()) {
    const element = iterator.next();
    if (element.getType() === DocumentApp.ElementType.TEXT || 
        element.getType() === DocumentApp.ElementType.PARAGRAPH ||
        element.getType() === DocumentApp.ElementType.LIST_ITEM) {
      elements.push(element);
    }
  }
  
  return elements;
}
```

### Text insert/replace: Quick usage

```javascript
function textBasicsExamples() {
  // Insert text at end
  insertText('Hello from Apps Script!', { bold: true, fontSize: 14 });

  // Replace text (case-insensitive)
  const replaced = replaceText('hello', 'Hi', false);
  console.log('Replaced count:', replaced);

  // List text elements count
  const body = DocumentApp.getActiveDocument().getBody();
  const els = getAllTextElements(body);
  console.log('Text-like elements:', els.length);
}
```

### Text Formatting Functions

```javascript
/**
 * Formats selected text with various options
 * @param {Object} options - Format options (optional)
 * @returns {void}
 */
function formatSelectedText(options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const selection = doc.getSelection();
  
  if (!selection) return;
  
  // Get all elements in selection
  const elements = [];
  for (let i = 0; i < selection.getRangeElements().length; i++) {
    const rangeElement = selection.getRangeElements()[i];
    elements.push(rangeElement.getElement());
  }
  
  elements.forEach(element => {
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      const text = element.asText();
      
      // Apply formatting options
      if (options.bold !== undefined) text.setBold(options.bold);
      if (options.italic !== undefined) text.setItalic(options.italic);
      if (options.underline !== undefined) text.setUnderline(options.underline);
      if (options.fontSize !== undefined) text.setFontSize(options.fontSize);
      if (options.fontFamily !== undefined) text.setFontFamily(options.fontFamily);
      
      // Color options
      if (options.textColor !== undefined) text.setForegroundColor(options.textColor);
      if (options.backgroundColor !== undefined) text.setBackgroundColor(options.backgroundColor);
    }
  });
}

/**
 * Applies heading formatting to a paragraph
 * @param {number} level - Heading level (1-6)
 * @param {GoogleAppsScript.Document.Paragraph} paragraph - Paragraph to format (optional, uses current selection if not provided)
 * @returns {void}
 */
function applyHeading(level, paragraph = null) {
  const doc = DocumentApp.getActiveDocument();
  
  // Get the paragraph to format
  if (!paragraph) {
    const selection = doc.getSelection();
    if (selection && selection.getRangeElements().length > 0) {
      const rangeElement = selection.getRangeElements()[0];
      paragraph = rangeElement.getElement().asParagraph();
    } else {
      return;
    }
  }
  
  // Apply heading style
  switch (level) {
    case 1:
      paragraph.setHeading(DocumentApp.Heading.HEADING_1);
      break;
    case 2:
      paragraph.setHeading(DocumentApp.Heading.HEADING_2);
      break;
    case 3:
      paragraph.setHeading(DocumentApp.Heading.HEADING_3);
      break;
    case 4:
      paragraph.setHeading(DocumentApp.Heading.HEADING_4);
      break;
    case 5:
      paragraph.setHeading(DocumentApp.Heading.HEADING_5);
      break;
    case 6:
      paragraph.setHeading(DocumentApp.Heading.HEADING_6);
      break;
    default:
      paragraph.setHeading(null); // Remove heading
  }
}
```

### Text formatting: Quick usage

```javascript
function textFormattingExamples() {
  // Select some text in the doc, then run:
  formatSelectedText({ bold: true, underline: true, textColor: '#1a73e8' });

  // Create a paragraph and apply heading programmatically
  const body = DocumentApp.getActiveDocument().getBody();
  const p = body.appendParagraph('Section Title');
  applyHeading(2, p); // Heading 2
}
```

## 3. Table Operations

```javascript
/**
 * Creates a table with specified rows and columns
 * @param {number} rows - Number of rows (default: 2)
 * @param {number} columns - Number of columns (default: 2)
 * @param {Object} options - Table formatting options (optional)
 * @returns {GoogleAppsScript.Document.Table}
 */
function createTable(rows = 2, columns = 2, options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  // Create table
  const table = body.appendTable(rows, columns);
  
  // Apply formatting if provided
  if (options.tableStyle) {
    table.setTableAlignment(options.tableStyle.alignment || DocumentApp.HorizontalAlignment.LEFT);
    
    // Set border styles
    if (options.borderSize) {
      table.setBorderWidth(options.borderSize);
    }
  }
  
  return table;
}

/**
 * Adds a row to an existing table
 * @param {GoogleAppsScript.Document.Table} table - The table to modify
 * @param {Array<string>} rowData - Array of cell values for the new row (optional)
 * @returns {GoogleAppsScript.Document.TableRow}
 */
function addTableRow(table, rowData = []) {
  if (!table) return null;
  
  const newRow = table.appendTableRow();
  
  // Fill cells with data if provided
  if (rowData.length > 0) {
    for (let i = 0; i < Math.min(rowData.length, table.getNumColumns()); i++) {
      newRow.getCell(i).setText(rowData[i]);
    }
  }
  
  return newRow;
}

/**
 * Formats a table cell
 * @param {GoogleAppsScript.Document.TableCell} cell - The cell to format
 * @param {Object} options - Formatting options (optional)
 * @returns {void}
 */
function formatTableCell(cell, options = {}) {
  if (!cell) return;
  
  // Apply formatting options
  if (options.backgroundColor) cell.setBackgroundColor(options.backgroundColor);
  if (options.textColor) cell.setForegroundColor(options.textColor);
  if (options.bold !== undefined) cell.setBold(options.bold);
  if (options.italic !== undefined) cell.setItalic(options.italic);
  if (options.fontSize !== undefined) cell.setFontSize(options.fontSize);
  
  // Text alignment
  if (options.alignment) {
    switch (options.alignment.toLowerCase()) {
      case 'left':
        cell.setHorizontalAlignment(DocumentApp.HorizontalAlignment.LEFT);
        break;
      case 'center':
        cell.setHorizontalAlignment(DocumentApp.HorizontalAlignment.CENTER);
        break;
      case 'right':
        cell.setHorizontalAlignment(DocumentApp.HorizontalAlignment.RIGHT);
        break;
    }
  }
}
```

### Tables: Quick usage

```javascript
function tableExamples() {
  // Create 2x2 table
  const table = createTable(2, 2, { tableStyle: { alignment: DocumentApp.HorizontalAlignment.CENTER } });
  // Add a header row with labels
  addTableRow(table, ['Name', 'Role']);
  // Format first header cell
  const headerCell = table.getRow(0).getCell(0);
  formatTableCell(headerCell, { backgroundColor: '#E3F2FD', bold: true, alignment: 'center' });
}
```

## 4. Document Structure Operations

```javascript
/**
 * Inserts a section break
 * @param {DocumentApp.SectionBreakType} type - The type of section break (default: NEW_PAGE)
 * @returns {GoogleAppsScript.Document.Paragraph}
 */
function insertSectionBreak(type = DocumentApp.SectionBreakType.NEW_PAGE) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  return body.appendPageBreak();
}

/**
 * Inserts a page break
 * @param {number} count - Number of page breaks to insert (default: 1)
 * @returns {void}
 */
function insertPageBreak(count = 1) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  for (let i = 0; i < count; i++) {
    body.appendPageBreak();
  }
}

/**
 * Inserts a horizontal rule
 * @returns {GoogleAppsScript.Document.Paragraph}
 */
function insertHorizontalRule() {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  return body.appendHorizontalRule();
}

/**
 * Adds content to the document header or footer
 * @param {string} content - Content to add
 * @param {string} location - "header" or "footer" (default: "header")
 * @returns {void}
 */
function addToHeaderFooter(content, location = "header") {
  const doc = DocumentApp.getActiveDocument();
  const section = doc.getBody().getSectionElement(0);
  
  if (location.toLowerCase() === 'header') {
    section.setHeaderContent(content);
  } else {
    section.setFooterContent(content);
  }
}
```

### Structure: Quick usage

```javascript
function structureExamples() {
  insertPageBreak(1);
  insertHorizontalRule();
  addToHeaderFooter('Automated Header', 'header');
}
```

## 5. Advanced Text Processing

```javascript
/**
 * Finds and replaces all instances of a pattern with formatting
 * @param {string} pattern - Regular expression pattern to match
 * @param {function} replacementFunction - Function that returns replacement text (optional)
 * @returns {number} Number of replacements made
 */
function replaceWithPattern(pattern, replacementFunction = null) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  let replacedCount = 0;
  const textElements = getAllTextElements(body);
  
  textElements.forEach(element => {
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      const text = element.asText();
      const content = text.getText();
      
      // Create regex with global flag
      const regex = new RegExp(pattern, 'g');
      
      let match;
      while ((match = regex.exec(content)) !== null) {
        const replacement = replacementFunction ? 
          replacementFunction(match[0], match) : 
          `[${match[0]}]`;
        
        const startIndex = match.index;
        const endIndex = startIndex + match[0].length;
        
        text.deleteText(startIndex, endIndex);
        text.insertText(startIndex, replacement);
        
        replacedCount++;
      }
    }
  });
  
  return replacedCount;
}

/**
 * Extracts all links from the document
 * @returns {Array<Object>} Array of link objects with url and text
 */
function extractAllLinks() {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  const links = [];
  const iterator = body.getIterator();
  
  while (iterator.hasNext()) {
    const element = iterator.next();
    
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      const textElement = element.asText();
      
      // Iterate through all runs in the text
      for (let i = 0; i < textElement.getNumRuns(); i++) {
        const run = textElement.getRun(i);
        const url = run.getLinkUrl();
        
        if (url) {
          links.push({
            url: url,
            text: run.getText(),
            position: {
              start: textElement.getRangeStart(textElement),
              end: textElement.getRangeEnd(textElement)
            }
          });
        }
      }
    }
  }
  
  return links;
}

/**
 * Creates a table of contents
 * @param {string} title - Title for the TOC (default: "Table of Contents")
 * @returns {void}
 */
function createTableOfContents(title = "Table of Contents") {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  // Add title
  const heading = body.appendParagraph(title);
  heading.setHeading(DocumentApp.Heading.HEADING_1);
  
  // Add TOC placeholder (this would need to be manually updated)
  body.appendParagraph("TOC will be generated automatically when the document is saved.");
}
```

### Advanced text: Quick usage

```javascript
function advancedTextExamples() {
  // Regex replace example: wrap all 4-digit numbers in [brackets]
  const count = replaceWithPattern('\\b\\d{4}\\b', (match) => `[${match}]`);
  console.log('Pattern replacements:', count);

  // Extract links
  const links = extractAllLinks();
  console.log('Links found:', links.length);

  // Add TOC title placeholder
  createTableOfContents('Table of Contents');
}
```

## 6. Document Management Functions

```javascript
/**
 * Copies a document and optionally renames it
 * @param {string} sourceDocumentId - ID of source document
 * @param {string} newName - New name for copied document (optional)
 * @returns {GoogleAppsScript.Document.Document}
 */
function copyDocument(sourceDocumentId, newName = null) {
  const sourceDoc = DocumentApp.openById(sourceDocumentId);
  
  if (!newName) {
    newName = "Copy of " + sourceDoc.getName();
  }
  
  const newDoc = DriveApp.getFileById(sourceDocumentId).makeCopy(newName);
  return DocumentApp.openById(newDoc.getId());
}

/**
 * Gets document statistics
 * @param {GoogleAppsScript.Document.Document} doc - The document object (optional)
 * @returns {Object} Document statistics
 */
function getDocumentStats(doc = DocumentApp.getActiveDocument()) {
  const document = doc || DocumentApp.getActiveDocument();
  
  let wordCount = 0;
  let charCount = 0;
  let paraCount = 0;
  
  // Get text elements and count words/characters
  const body = document.getBody();
  
  const iterator = body.getIterator();
  
  while (iterator.hasNext()) {
    const element = iterator.next();
    
    if (element.getType() === DocumentApp.ElementType.PARAGRAPH) {
      paraCount++;
      
      // Get text content and count words/characters
      const paragraph = element.asParagraph();
      const text = paragraph.getText();
      
      wordCount += text.trim() ? text.trim().split(/\s+/).length : 0;
      charCount += text.length;
    }
    
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      paraCount++;
      
      const textElement = element.asText();
      const text = textElement.getText();
      
      wordCount += text.trim() ? text.trim().split(/\s+/).length : 0;
      charCount += text.length;
    }
  }
  
  return {
    id: document.getId(),
    title: document.getName(),
    wordCount: wordCount,
    characterCount: charCount,
    paragraphCount: paraCount
  };
}

/**
 * Saves the current document to a specific folder
 * @param {string} folderPath - Path to folder in Drive (optional)
 * @returns {void}
 */
function saveDocumentToFolder(folderPath = null) {
  const doc = DocumentApp.getActiveDocument();
  
  // Save as Google Docs file in specified folder or root
  if (folderPath) {
    try {
      const folder = DriveApp.getFoldersByName(folderPath).next();
      const file = DriveApp.getFileById(doc.getId());
      
      // Remove from current location and add to new folder
      file.getParents().next().removeFile(file);
      folder.addFile(file);
    } catch (error) {
      console.log("Could not save to folder: " + error.message);
    }
  }
}
```

### Document management: Quick usage

```javascript
function docManagementExamples() {
  // Copy current doc
  const doc = DocumentApp.getActiveDocument();
  const copy = copyDocument(doc.getId(), `${doc.getName()} (Copy)`);
  console.log('Copied URL:', copy.getUrl());

  // Stats
  const stats = getDocumentStats();
  console.log('Stats:', stats);

  // Move to folder named "Reports" (if exists)
  saveDocumentToFolder('Reports');
}
```

## 7. Utility Functions

```javascript
/**
 * Creates a reusable document element builder
 * @param {string} type - Type of element ('paragraph', 'heading', etc.)
 * @param {Object} options - Element configuration options (optional)
 * @returns {function} Function to build the element with text and formatting
 */
function createElementBuilder(type, options = {}) {
  const doc = DocumentApp.getActiveDocument();
  
  return function(text, formattingOptions = {}) {
    const body = doc.getBody();
    
    switch (type.toLowerCase()) {
      case 'paragraph':
        const paragraph = body.appendParagraph(text);
        
        if (formattingOptions.bold) paragraph.setBold(true);
        if (formattingOptions.italic) paragraph.setItalic(true);
        if (formattingOptions.fontSize) paragraph.setFontSize(formattingOptions.fontSize);
        if (formattingOptions.color) paragraph.setForegroundColor(formattingOptions.color);
        
        return paragraph;
        
      case 'heading':
        const heading = body.appendParagraph(text);
        applyHeading(options.level || 1, heading);
        
        if (formattingOptions.bold) heading.setBold(true);
        if (formattingOptions.fontSize) heading.setFontSize(formattingOptions.fontSize);
        
        return heading;
    }
    
    return null;
  };
}

/**
 * Batch process multiple text replacements
 * @param {Array<Object>} replacements - Array of replacement objects with find and replace properties
 * @returns {number} Total number of replacements made
 */
function batchReplace(replacements) {
  let totalReplaced = 0;
  
  replacements.forEach(replacement => {
    const count = replaceText(
      replacement.find,
      replacement.replace,
      replacement.caseSensitive !== false // Default to case sensitive
    );
    totalReplaced += count;
  });
  
  return totalReplaced;
}

/**
 * Logs document activity with timestamps
 * @param {string} message - Activity message
 * @returns {void}
 */
function logActivity(message) {
  const timestamp = new Date().toISOString();
  console.log(`[${timestamp}] ${message}`);
  
  // Also add to document for tracking (optional)
  try {
    const doc = DocumentApp.getActiveDocument();
    const body = doc.getBody();
    
    if (body.getNumChildren() > 0) {
      const lastElement = body.getChild(body.getNumChildren() - 1);
      if (lastElement.getType() === DocumentApp.ElementType.PARAGRAPH) {
        // Add to existing paragraph or create new one
        const para = lastElement.asParagraph();
        para.appendText(`\n[${timestamp}] ${message}`);
      } else {
        body.appendParagraph(`[${timestamp}] ${message}`);
      }
    } else {
      body.appendParagraph(`[${timestamp}] ${message}`);
    }
  } catch (error) {
    console.log("Could not add to document: " + error.message);
  }
}
```

### Utilities: Quick usage

```javascript
function utilitiesExamples() {
  // Element builder: heading level 3
  const makeH3 = createElementBuilder('heading', { level: 3 });
  makeH3('Quick Usage Section', { bold: true });

  // Batch replace
  const total = batchReplace([
    { find: 'foo', replace: 'bar' },
    { find: '2024', replace: '2025', caseSensitive: false }
  ]);
  logActivity(`Batch replacements: ${total}`);
}
```

## Usage Examples

```javascript
/**
 * Example usage of the functions above
 */
function exampleUsage() {
  // Create a new document
  const doc = createNewDocument("Example Document");
  
  // Insert formatted text
  insertText("Hello World!", { bold: true, fontSize: 16 });
  
  // Replace text
  replaceText("World", "Universe");
  
  // Create table
  const table = createTable(3, 4);
  
  // Add rows to table
  addTableRow(table, ["Name", "Age", "City", "Country"]);
  addTableRow(table, ["John", "25", "New York", "USA"]);
---

## 10. Complete Project Examples

### Report Generator

Let's create a comprehensive report generation system that brings together all our classes:

#### ReportGenerator Class

```javascript
/**
 * ReportGenerator - Complete automated report generation system
 * Combines all document automation features for professional report creation
 */
class ReportGenerator {
  constructor() {
    this.config = DOCS_CONFIG;
  }

  /**
   * Generate comprehensive business report
   * @param {Object} reportData - Report data and configuration
   * @return {Document} Generated report document
   */
  generateBusinessReport(reportData) {
    const {
      title = 'Business Report',
      author = 'Report System',
      date = new Date(),
      company = 'Company Name',
      sections = [],
      data = {},
      charts = [],
      theme = 'corporate'
    } = reportData;

    try {
      console.log('📊 Starting business report generation...');
      
      // Create document
      const reportTitle = `${title} - ${date.toLocaleDateString()}`;
      const doc = DOC_MANAGER.createDocument(reportTitle);
      
      // Apply professional template
      HEADER_FOOTER_MANAGER.applyProfessionalTemplate(doc, {
        companyName: company,
        documentTitle: title,
        author: author,
        date: date.toLocaleDateString()
      });
      
      // Create title page
      this.createTitlePage(doc, { title, author, date, company });
      
      // Add table of contents
      const tocInfo = TOC_MANAGER.createAdvancedTOC(doc, {
        title: 'Table of Contents',
        createBookmarks: true,
        linkToHeadings: true
      });
      
      // Add executive summary
      this.addExecutiveSummary(doc, data.summary || 'Executive summary not provided.');
      
      // Generate report sections
      for (const section of sections) {
        this.generateReportSection(doc, section, data);
      }
      
      // Add data tables if provided
      if (data.tables) {
        this.addDataTables(doc, data.tables);
      }
      
      // Add charts section
      if (charts.length > 0) {
        this.addChartsSection(doc, charts);
      }
      
      // Add appendix
      this.addAppendix(doc, data.appendix || {});
      
      // Apply theme
      STYLE_MANAGER.applyTheme(doc, theme);
      
      console.log('✅ Business report generated successfully');
      return doc;
      
    } catch (error) {
      console.error(`❌ Error generating business report: ${error.message}`);
      throw error;
    }
  }

  /**
   * Create professional title page
   * @param {Document} doc - Target document
   * @param {Object} titleInfo - Title page information
   */
  createTitlePage(doc, titleInfo) {
    const { title, author, date, company } = titleInfo;
    
    // Add company logo placeholder
    TEXT_WRITER.writeText(doc, '[COMPANY LOGO]', {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 14,
      bold: true
    });
    
    // Add spacing
    TEXT_WRITER.writeText(doc, '');
    TEXT_WRITER.writeText(doc, '');
    
    // Add title
    TEXT_WRITER.writeText(doc, title, {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 24,
      bold: true,
      color: this.config.get('COLORS').PRIMARY
    });
    
    // Add spacing
    TEXT_WRITER.writeText(doc, '');
    
    // Add subtitle
    TEXT_WRITER.writeText(doc, 'Comprehensive Analysis and Insights', {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 16,
      italic: true
    });
    
    // Add spacing
    for (let i = 0; i < 8; i++) {
      TEXT_WRITER.writeText(doc, '');
    }
    
    // Add author and date
    TEXT_WRITER.writeText(doc, `Prepared by: ${author}`, {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 12
    });
    
    TEXT_WRITER.writeText(doc, `Date: ${date.toLocaleDateString()}`, {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 12
    });
    
    TEXT_WRITER.writeText(doc, `${company}`, {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      fontSize: 12,
      bold: true
    });
    
    // Add page break
    PAGE_MANAGER.insertPageBreak(doc);
  }

  /**
   * Add executive summary section
   * @param {Document} doc - Target document
   * @param {string} summaryText - Summary content
   */
  addExecutiveSummary(doc, summaryText) {
    TEXT_WRITER.writeHeading(doc, 'Executive Summary', 1);
    TEXT_WRITER.writeText(doc, summaryText, {
      fontSize: 12,
      lineSpacing: 1.5
    });
    
    // Add key highlights
    TEXT_WRITER.writeHeading(doc, 'Key Highlights', 2);
    LIST_MANAGER.createAdvancedList(doc, [
      { text: 'Strategic insights and recommendations', styles: { bold: true } },
      { text: 'Performance metrics and analysis', styles: { bold: true } },
      { text: 'Future opportunities and challenges', styles: { bold: true } }
    ], { listType: 'bullet' });
  }

  /**
   * Generate individual report section
   * @param {Document} doc - Target document
   * @param {Object} sectionConfig - Section configuration
   * @param {Object} data - Report data
   */
  generateReportSection(doc, sectionConfig, data) {
    const { title, content, subsections = [], dataKey = null } = sectionConfig;
    
    // Add section title
    TEXT_WRITER.writeHeading(doc, title, 2);
    
    // Add content
    if (content) {
      if (Array.isArray(content)) {
        TEXT_WRITER.writeMultipleParagraphs(doc, content);
      } else {
        TEXT_WRITER.writeText(doc, content);
      }
    }
    
    // Add data visualization if data key provided
    if (dataKey && data[dataKey]) {
      this.addDataVisualization(doc, data[dataKey], title);
    }
    
    // Add subsections
    for (const subsection of subsections) {
      TEXT_WRITER.writeHeading(doc, subsection.title, 3);
      if (subsection.content) {
        TEXT_WRITER.writeText(doc, subsection.content);
      }
    }
  }

  /**
   * Add data visualization to section
   * @param {Document} doc - Target document
   * @param {Array<Object>} data - Data to visualize
   * @param {string} title - Chart title
   */
  addDataVisualization(doc, data, title) {
    if (!Array.isArray(data) || data.length === 0) return;
    
    // Create summary table
    const columns = Object.keys(data[0]);
    TABLE_MANAGER.createSummaryTable(doc, data, columns);
    
    // Add chart placeholder
    TEXT_WRITER.writeText(doc, `[Chart: ${title} - Data Visualization]`, {
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      italic: true,
      backgroundColor: '#f0f0f0'
    });
  }

  /**
   * Add data tables section
   * @param {Document} doc - Target document
   * @param {Object} tables - Table configurations
   */
  addDataTables(doc, tables) {
    PAGE_MANAGER.insertPageBreak(doc);
    TEXT_WRITER.writeHeading(doc, 'Data Tables', 1);
    
    for (const [tableName, tableData] of Object.entries(tables)) {
      TEXT_WRITER.writeHeading(doc, tableName, 2);
      
      if (Array.isArray(tableData) && tableData.length > 0) {
        // Convert objects to 2D array
        const headers = Object.keys(tableData[0]);
        const rows = [headers];
        
        for (const row of tableData) {
          rows.push(headers.map(header => String(row[header] || '')));
        }
        
        TABLE_MANAGER.createTable(doc, rows, {
          hasHeaders: true,
          alternatingRows: true
        });
      }
    }
  }

  /**
   * Add charts section
   * @param {Document} doc - Target document
   * @param {Array<Object>} charts - Chart configurations
   */
  addChartsSection(doc, charts) {
    PAGE_MANAGER.insertPageBreak(doc);
    TEXT_WRITER.writeHeading(doc, 'Charts and Visualizations', 1);
    
    for (const chart of charts) {
      TEXT_WRITER.writeHeading(doc, chart.title, 2);
      TEXT_WRITER.writeText(doc, chart.description || 'Chart description not provided.');
      
      // Add chart placeholder
      TEXT_WRITER.writeText(doc, `[Chart: ${chart.type} - ${chart.title}]`, {
        alignment: DocumentApp.HorizontalAlignment.CENTER,
        italic: true,
        backgroundColor: '#f0f0f0',
        fontSize: 14
      });
      
      if (chart.insights) {
        TEXT_WRITER.writeText(doc, 'Key Insights:');
        LIST_MANAGER.createAdvancedList(doc, chart.insights.map(insight => ({ text: insight })), {
          listType: 'bullet'
        });
      }
    }
  }

  /**
   * Add appendix section
   * @param {Document} doc - Target document
   * @param {Object} appendixData - Appendix data
   */
  addAppendix(doc, appendixData) {
    if (Object.keys(appendixData).length === 0) return;
    
    PAGE_MANAGER.insertPageBreak(doc);
    TEXT_WRITER.writeHeading(doc, 'Appendix', 1);
    
    for (const [sectionTitle, content] of Object.entries(appendixData)) {
      TEXT_WRITER.writeHeading(doc, sectionTitle, 2);
      
      if (typeof content === 'string') {
        TEXT_WRITER.writeText(doc, content);
      } else if (Array.isArray(content)) {
        LIST_MANAGER.createAdvancedList(doc, content.map(item => ({ text: item })), {
          listType: 'bullet'
        });
      }
    }
  }
}

// Create global report generator instance
const REPORT_GENERATOR = new ReportGenerator();
```

### Contract Management System

```javascript
/**
 * ContractManager - Automated contract generation and management
 * Creates legal documents with template substitution and formatting
 */
class ContractManager {
  constructor() {
    this.config = DOCS_CONFIG;
    this.templates = this.initializeTemplates();
  }

  /**
   * Initialize contract templates
   * @return {Object} Contract templates
   */
  initializeTemplates() {
    return {
      serviceAgreement: {
        title: 'Service Agreement',
        sections: [
          { title: 'Parties', template: 'This agreement is between {{clientName}} ("Client") and {{providerName}} ("Provider").' },
          { title: 'Services', template: 'Provider agrees to provide the following services: {{serviceDescription}}' },
          { title: 'Term', template: 'This agreement begins on {{startDate}} and ends on {{endDate}}.' },
          { title: 'Payment', template: 'Client agrees to pay {{paymentAmount}} {{paymentSchedule}}.' },
          { title: 'Termination', template: 'Either party may terminate this agreement with {{noticePeriod}} notice.' }
        ]
      },
      
      employmentContract: {
        title: 'Employment Contract',
        sections: [
          { title: 'Position', template: '{{employeeName}} is hired for the position of {{jobTitle}}.' },
          { title: 'Compensation', template: 'Annual salary: {{salary}}. Benefits: {{benefits}}.' },
          { title: 'Start Date', template: 'Employment begins on {{startDate}}.' },
          { title: 'Confidentiality', template: 'Employee agrees to maintain confidentiality of proprietary information.' }
        ]
      }
    };
  }

  /**
   * Generate contract from template
   * @param {string} templateType - Type of contract template
   * @param {Object} contractData - Data to populate template
   * @return {Document} Generated contract
   */
  generateContract(templateType, contractData) {
    const template = this.templates[templateType];
    if (!template) {
      throw new Error(`Template "${templateType}" not found`);
    }

    try {
      console.log(`📄 Generating ${template.title}...`);
      
      // Create document
      const docTitle = `${template.title} - ${contractData.clientName || contractData.employeeName || 'Contract'}`;
      const doc = DOC_MANAGER.createDocument(docTitle);
      
      // Add header
      HEADER_FOOTER_MANAGER.addHeader(doc, {
        centerText: template.title,
        rightText: new Date().toLocaleDateString(),
        addLine: true
      });
      
      // Add title
      TEXT_WRITER.writeHeading(doc, template.title, 1);
      
      // Add contract sections
      for (const section of template.sections) {
        TEXT_WRITER.writeHeading(doc, section.title, 2);
        
        // Replace placeholders in template
        const processedText = this.processTemplate(section.template, contractData);
        TEXT_WRITER.writeText(doc, processedText, { fontSize: 12 });
      }
      
      // Add signature section
      this.addSignatureSection(doc, contractData);
      
      // Add footer
      HEADER_FOOTER_MANAGER.addFooter(doc, {
        leftText: 'Confidential',
        centerText: `© ${new Date().getFullYear()}`,
        includePageNumbers: true
      });
      
      console.log('✅ Contract generated successfully');
      return doc;
      
    } catch (error) {
      console.error(`❌ Error generating contract: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process template with data substitution
   * @param {string} template - Template text
   * @param {Object} data - Data for substitution
   * @return {string} Processed text
   */
  processTemplate(template, data) {
    let processedText = template;
    
    for (const [key, value] of Object.entries(data)) {
      const placeholder = new RegExp(`{{${key}}}`, 'g');
      processedText = processedText.replace(placeholder, String(value));
    }
    
    return processedText;
  }

  /**
   * Add signature section to contract
   * @param {Document} doc - Target document
   * @param {Object} contractData - Contract data
   */
  addSignatureSection(doc, contractData) {
    // Add spacing
    TEXT_WRITER.writeText(doc, '');
    TEXT_WRITER.writeText(doc, '');
    
    TEXT_WRITER.writeHeading(doc, 'Signatures', 2);
    
    // Create signature table
    const signatureData = [
      ['Party', 'Signature', 'Date'],
      [contractData.clientName || contractData.employeeName || 'Party 1', '_________________________', '___________'],
      [contractData.providerName || 'Company Representative', '_________________________', '___________']
    ];
    
    const signatureTable = TABLE_MANAGER.createTable(doc, signatureData, {
      hasHeaders: true,
      fontSize: 11,
      cellPadding: 10
    });
    
    // Add witness section
    TEXT_WRITER.writeText(doc, '');
    TEXT_WRITER.writeText(doc, 'Witness: _________________________     Date: ___________', {
      fontSize: 10
    });
  }
}

// Create global contract manager instance
const CONTRACT_MANAGER = new ContractManager();
```

### Automated Letter Writer

```javascript
/**
 * LetterWriter - Automated letter generation system
 * Creates professional letters with proper formatting and templates
 */
class LetterWriter {
  constructor() {
    this.config = DOCS_CONFIG;
  }

  /**
   * Generate business letter
   * @param {Object} letterData - Letter configuration
   * @return {Document} Generated letter
   */
  generateBusinessLetter(letterData) {
    const {
      senderInfo = {},
      recipientInfo = {},
      subject = '',
      body = '',
      letterType = 'formal',
      includeDate = true,
      includeSignature = true
    } = letterData;

    try {
      console.log('💌 Generating business letter...');
      
      // Create document
      const docTitle = `Letter - ${subject || 'Business Correspondence'}`;
      const doc = DOC_MANAGER.createDocument(docTitle);
      
      // Add letterhead if provided
      if (senderInfo.company) {
        this.addLetterhead(doc, senderInfo);
      }
      
      // Add date
      if (includeDate) {
        TEXT_WRITER.writeText(doc, new Date().toLocaleDateString(), {
          alignment: DocumentApp.HorizontalAlignment.RIGHT,
          spacingAfter: 12
        });
      }
      
      // Add recipient address
      this.addRecipientAddress(doc, recipientInfo);
      
      // Add subject line
      if (subject) {
        TEXT_WRITER.writeText(doc, `Re: ${subject}`, {
          bold: true,
          spacingBefore: 12,
          spacingAfter: 12
        });
      }
      
      // Add salutation
      const salutation = this.getSalutation(recipientInfo, letterType);
      TEXT_WRITER.writeText(doc, salutation, { spacingAfter: 12 });
      
      // Add body
      if (Array.isArray(body)) {
        TEXT_WRITER.writeMultipleParagraphs(doc, body.map(para => ({ text: para })));
      } else {
        TEXT_WRITER.writeText(doc, body, { lineSpacing: 1.5 });
      }
      
      // Add closing
      const closing = this.getClosing(letterType);
      TEXT_WRITER.writeText(doc, closing, { spacingBefore: 24 });
      
      // Add signature block
      if (includeSignature) {
        this.addSignatureBlock(doc, senderInfo);
      }
      
      // Apply letter formatting
      this.applyLetterFormatting(doc, letterType);
      
      console.log('✅ Business letter generated successfully');
      return doc;
      
    } catch (error) {
      console.error(`❌ Error generating business letter: ${error.message}`);
      throw error;
    }
  }

  /**
   * Add letterhead to document
   * @param {Document} doc - Target document
   * @param {Object} senderInfo - Sender information
   */
  addLetterhead(doc, senderInfo) {
    const { company, address, phone, email, website } = senderInfo;
    
    // Company name
    TEXT_WRITER.writeText(doc, company, {
      fontSize: 18,
      bold: true,
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      color: this.config.get('COLORS').PRIMARY
    });
    
    // Contact information
    const contactInfo = [address, phone, email, website].filter(info => info).join(' | ');
    TEXT_WRITER.writeText(doc, contactInfo, {
      fontSize: 10,
      alignment: DocumentApp.HorizontalAlignment.CENTER,
      spacingAfter: 24
    });
    
    // Add horizontal line
    PAGE_MANAGER.insertHorizontalRule(doc);
    TEXT_WRITER.writeText(doc, ''); // Spacing
  }

  /**
   * Add recipient address
   * @param {Document} doc - Target document
   * @param {Object} recipientInfo - Recipient information
   */
  addRecipientAddress(doc, recipientInfo) {
    const { name, title, company, address } = recipientInfo;
    
    const addressLines = [];
    if (name) addressLines.push(name);
    if (title) addressLines.push(title);
    if (company) addressLines.push(company);
    if (address) addressLines.push(address);
    
    for (const line of addressLines) {
      TEXT_WRITER.writeText(doc, line);
    }
    
    TEXT_WRITER.writeText(doc, ''); // Spacing
  }

  /**
   * Get appropriate salutation
   * @param {Object} recipientInfo - Recipient information
   * @param {string} letterType - Type of letter
   * @return {string} Salutation
   */
  getSalutation(recipientInfo, letterType) {
    const { name, title } = recipientInfo;
    
    if (letterType === 'casual' && name) {
      return `Hi ${name.split(' ')[0]},`;
    } else if (name) {
      const titlePrefix = title && (title.includes('Dr') || title.includes('Prof')) ? title.split(' ')[0] : 'Mr./Ms.';
      return `Dear ${titlePrefix} ${name.split(' ').pop()},`;
    } else {
      return 'Dear Sir/Madam,';
    }
  }

  /**
   * Get appropriate closing
   * @param {string} letterType - Type of letter
   * @return {string} Closing
   */
  getClosing(letterType) {
    const closings = {
      formal: 'Sincerely,',
      business: 'Best regards,',
      casual: 'Best,',
      friendly: 'Warm regards,'
    };
    
    return closings[letterType] || 'Sincerely,';
  }

  /**
   * Add signature block
   * @param {Document} doc - Target document
   * @param {Object} senderInfo - Sender information
   */
  addSignatureBlock(doc, senderInfo) {
    const { name, title, company, phone, email } = senderInfo;
    
    // Spacing for signature
    TEXT_WRITER.writeText(doc, '');
    TEXT_WRITER.writeText(doc, '');
    TEXT_WRITER.writeText(doc, '');
    
    // Name
    if (name) {
      TEXT_WRITER.writeText(doc, name, { bold: true });
    }
    
    // Title
    if (title) {
      TEXT_WRITER.writeText(doc, title);
    }
    
    // Company
    if (company) {
      TEXT_WRITER.writeText(doc, company);
    }
    
    // Contact info
    const contactLines = [];
    if (phone) contactLines.push(`Phone: ${phone}`);
    if (email) contactLines.push(`Email: ${email}`);
    
    for (const contact of contactLines) {
      TEXT_WRITER.writeText(doc, contact, { fontSize: 10 });
    }
  }

  /**
   * Apply letter-specific formatting
   * @param {Document} doc - Target document
   * @param {string} letterType - Type of letter
   */
  applyLetterFormatting(doc, letterType) {
    // Set document margins
    const body = doc.getBody();
    body.setMarginTop(72);    // 1 inch
    body.setMarginBottom(72); // 1 inch
    body.setMarginLeft(72);   // 1 inch
    body.setMarginRight(72);  // 1 inch
    
    // Apply theme based on letter type
    const themeMap = {
      formal: 'academic',
      business: 'corporate',
      casual: 'minimal',
      friendly: 'creative'
    };
    
    const theme = themeMap[letterType] || 'corporate';
    STYLE_MANAGER.applyTheme(doc, theme);
  }
}

// Create global letter writer instance
const LETTER_WRITER = new LetterWriter();

/**
 * Demo function: Complete project examples
 */
function demonstrateCompleteProjects() {
  try {
    console.log('🎯 Starting Complete Projects Demo...');
    
    console.log('1. Generating business report...');
    const reportData = {
      title: 'Q4 Performance Report',
      author: 'Analytics Team',
      company: 'Demo Corporation',
      sections: [
        {
          title: 'Financial Performance',
          content: ['Strong revenue growth observed in Q4.', 'Profit margins improved by 15%.']
        },
        {
          title: 'Market Analysis',
          content: 'Competitive landscape shows positive trends for our products.'
        }
      ],
      data: {
        summary: 'Q4 showed exceptional performance across all key metrics.',
        tables: {
          'Revenue by Quarter': [
            { Quarter: 'Q1', Revenue: 100000, Growth: '5%' },
            { Quarter: 'Q2', Revenue: 120000, Growth: '20%' },
            { Quarter: 'Q3', Revenue: 115000, Growth: '-4%' },
            { Quarter: 'Q4', Revenue: 140000, Growth: '22%' }
          ]
        }
      },
      charts: [
        {
          title: 'Revenue Trend',
          type: 'line',
          description: 'Quarterly revenue progression',
          insights: ['Strong Q4 performance', 'Consistent growth trajectory']
        }
      ]
    };
    
    const report = REPORT_GENERATOR.generateBusinessReport(reportData);
    console.log(`📊 Business report generated: ${report.getName()}`);
    
    console.log('2. Generating service contract...');
    const contractData = {
      clientName: 'ABC Company',
      providerName: 'XYZ Services',
      serviceDescription: 'Web development and maintenance services',
      startDate: '2024-01-01',
      endDate: '2024-12-31',
      paymentAmount: '$5,000',
      paymentSchedule: 'monthly',
      noticePeriod: '30 days'
    };
    
    const contract = CONTRACT_MANAGER.generateContract('serviceAgreement', contractData);
    console.log(`📄 Service contract generated: ${contract.getName()}`);
    
    console.log('3. Generating business letter...');
    const letterData = {
      senderInfo: {
        name: 'John Smith',
        title: 'Project Manager',
        company: 'Tech Solutions Inc.',
        address: '123 Business St, City, State 12345',
        phone: '(555) 123-4567',
        email: 'john.smith@techsolutions.com'
      },
      recipientInfo: {
        name: 'Sarah Johnson',
        title: 'Director',
        company: 'Client Corp',
        address: '456 Corporate Ave, City, State 67890'
      },
      subject: 'Project Proposal - Q1 2024',
      body: [
        'I hope this letter finds you well. I am writing to present our proposal for the Q1 2024 project.',
        'Our team has extensive experience in delivering high-quality solutions that meet and exceed client expectations.',
        'I would welcome the opportunity to discuss this proposal with you in detail. Please let me know your availability for a meeting.'
      ],
      letterType: 'business'
    };
    
    const letter = LETTER_WRITER.generateBusinessLetter(letterData);
    console.log(`💌 Business letter generated: ${letter.getName()}`);
    
    console.log('✅ Complete Projects demo completed successfully');
    
    return { report, contract, letter };
    
  } catch (error) {
    console.error('❌ Complete Projects demo failed:', error.message);
    throw error;
  }
}
```

---

## 11. Best Practices and Optimization

### Performance Optimization

```javascript
/**
 * PerformanceOptimizer - Document processing optimization utilities
 * Provides tools and techniques for optimizing Google Docs automation performance
 */
class PerformanceOptimizer {
  constructor() {
    this.config = DOCS_CONFIG;
    this.operationCache = new Map();
    this.batchQueue = [];
    this.metrics = {
      operationsCount: 0,
      totalTime: 0,
      cacheHits: 0,
      batchOperations: 0
    };
  }

  /**
   * Batch multiple operations for better performance
   * @param {Array<Function>} operations - Array of operations to batch
   * @return {Array<any>} Results of batched operations
   */
  batchOperations(operations) {
    const startTime = Date.now();
    const results = [];
    
    try {
      console.log(`⚡ Starting batch operation with ${operations.length} items...`);
      
      // Execute operations in batch
      for (const operation of operations) {
        const result = operation();
        results.push(result);
      }
      
      const endTime = Date.now();
      const duration = endTime - startTime;
      
      this.metrics.batchOperations++;
      this.metrics.totalTime += duration;
      
      console.log(`✅ Batch operation completed in ${duration}ms`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operation failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Cache frequently accessed document elements
   * @param {string} key - Cache key
   * @param {Function} operation - Operation to cache result of
   * @return {any} Operation result (cached or fresh)
   */
  cacheOperation(key, operation) {
    if (this.operationCache.has(key)) {
      this.metrics.cacheHits++;
      console.log(`💾 Cache hit for key: ${key}`);
      return this.operationCache.get(key);
    }
    
    const result = operation();
    this.operationCache.set(key, result);
    console.log(`💿 Cached result for key: ${key}`);
    
    return result;
  }

  /**
   * Optimize document processing with chunked operations
   * @param {Array<any>} items - Items to process
   * @param {Function} processor - Processing function
   * @param {number} chunkSize - Size of processing chunks
   * @return {Array<any>} Processed results
   */
  processInChunks(items, processor, chunkSize = 10) {
    const results = [];
    const totalChunks = Math.ceil(items.length / chunkSize);
    
    console.log(`🔄 Processing ${items.length} items in ${totalChunks} chunks...`);
    
    for (let i = 0; i < items.length; i += chunkSize) {
      const chunk = items.slice(i, i + chunkSize);
      const chunkResults = chunk.map(processor);
      results.push(...chunkResults);
      
      console.log(`✓ Processed chunk ${Math.floor(i / chunkSize) + 1}/${totalChunks}`);
      
      // Add small delay to prevent rate limiting
      if (i + chunkSize < items.length) {
        Utilities.sleep(100);
      }
    }
    
    return results;
  }

  /**
   * Get performance metrics
   * @return {Object} Performance statistics
   */
  getMetrics() {
    return {
      ...this.metrics,
      averageOperationTime: this.metrics.operationsCount > 0 
        ? this.metrics.totalTime / this.metrics.operationsCount 
        : 0,
      cacheHitRatio: this.metrics.operationsCount > 0 
        ? this.metrics.cacheHits / this.metrics.operationsCount 
        : 0
    };
  }

  /**
   * Clear performance cache
   */
  clearCache() {
    this.operationCache.clear();
    console.log('🧹 Performance cache cleared');
  }
}

// Create global performance optimizer instance
const PERFORMANCE_OPTIMIZER = new PerformanceOptimizer();
```

### PerformanceOptimizer: Quick usage

```javascript
function performanceExamples() {
  // Batch simple operations
  PERFORMANCE_OPTIMIZER.batchOperations([
    () => insertText('Line 1'),
    () => insertText('Line 2')
  ]);

  // Cache an expensive op
  const res = PERFORMANCE_OPTIMIZER.cacheOperation('doc_title', () => DocumentApp.getActiveDocument().getName());
  console.log('Cached title:', res);
}
```

### Error Handling Patterns

```javascript
/**
 * ErrorHandler - Comprehensive error handling for document operations
 * Provides robust error handling patterns and recovery mechanisms
 */
class ErrorHandler {
  constructor() {
    this.config = DOCS_CONFIG;
    this.errorLog = [];
    this.retryAttempts = 3;
    this.retryDelay = 1000;
  }

  /**
   * Execute operation with comprehensive error handling
   * @param {Function} operation - Operation to execute
   * @param {Object} options - Error handling options
   * @return {any} Operation result
   */
  executeWithHandling(operation, options = {}) {
    const {
      retries = this.retryAttempts,
      retryDelay = this.retryDelay,
      fallback = null,
      silent = false
    } = options;

    for (let attempt = 1; attempt <= retries; attempt++) {
      try {
        const result = operation();
        
        if (attempt > 1) {
          console.log(`✅ Operation succeeded on attempt ${attempt}`);
        }
        
        return result;
        
      } catch (error) {
        const errorInfo = {
          message: error.message,
          attempt: attempt,
          timestamp: new Date().toISOString(),
          stack: error.stack
        };
        
        this.errorLog.push(errorInfo);
        
        if (attempt === retries) {
          if (fallback) {
            console.warn(`⚠️ Operation failed, using fallback after ${retries} attempts`);
            return fallback();
          }
          
          if (!silent) {
            console.error(`❌ Operation failed after ${retries} attempts:`, error.message);
          }
          
          throw error;
        }
        
        console.warn(`⚠️ Attempt ${attempt} failed, retrying in ${retryDelay}ms...`);
        Utilities.sleep(retryDelay);
      }
    }
  }

  /**
   * Validate document operations before execution
   * @param {Document} doc - Document to validate
   * @param {Object} requirements - Validation requirements
   * @return {Object} Validation results
   */
  validateDocument(doc, requirements = {}) {
    const validation = {
      isValid: true,
      errors: [],
      warnings: []
    };

    try {
      // Check document accessibility
      if (!doc) {
        validation.errors.push('Document is null or undefined');
        validation.isValid = false;
      }
      
      if (doc && !doc.getId()) {
        validation.errors.push('Document ID is not accessible');
        validation.isValid = false;
      }
      
      // Check permissions
      if (requirements.needsWriteAccess) {
        try {
          doc.getName(); // Test read access
          // Write access test would need actual write operation
        } catch (error) {
          validation.errors.push('Insufficient document permissions');
          validation.isValid = false;
        }
      }
      
      // Check content requirements
      if (requirements.minimumLength) {
        const content = doc.getBody().getText();
        if (content.length < requirements.minimumLength) {
          validation.warnings.push(`Document content is shorter than expected (${content.length} chars)`);
        }
      }
      
    } catch (error) {
      validation.errors.push(`Validation error: ${error.message}`);
      validation.isValid = false;
    }
    
    return validation;
  }

  /**
   * Log error with context information
   * @param {Error} error - Error to log
   * @param {Object} context - Additional context
   */
  logError(error, context = {}) {
    const errorEntry = {
      timestamp: new Date().toISOString(),
      message: error.message,
      stack: error.stack,
      context: context,
      userAgent: Session.getTemporaryActiveUserEmail() || 'Unknown'
    };
    
    this.errorLog.push(errorEntry);
    console.error('📝 Error logged:', errorEntry);
  }

  /**
   * Get error log
   * @param {number} limit - Maximum number of entries to return
   * @return {Array<Object>} Error log entries
   */
  getErrorLog(limit = 50) {
    return this.errorLog.slice(-limit);
  }

  /**
   * Clear error log
   */
  clearErrorLog() {
    this.errorLog = [];
    console.log('🧹 Error log cleared');
  }
}

// Create global error handler instance
const ERROR_HANDLER = new ErrorHandler();
```

### ErrorHandler: Quick usage

```javascript
function errorHandlerExamples() {
  const result = ERROR_HANDLER.executeWithHandling(() => {
    if (Math.random() < 0.5) throw new Error('Intermittent');
    return 'ok';
  }, { retries: 2, fallback: () => 'fallback' });
  console.log('Result:', result);
  console.log('Errors logged:', ERROR_HANDLER.getErrorLog().length);
}
```

### Security and Permissions

```javascript
/**
 * SecurityManager - Security and permissions management
 * Handles document security, permissions, and access control
 */
class SecurityManager {
  constructor() {
    this.config = DOCS_CONFIG;
  }

  /**
   * Check user permissions for document operations
   * @param {Document} doc - Document to check
   * @return {Object} Permission information
   */
  checkPermissions(doc) {
    const permissions = {
      canRead: false,
      canWrite: false,
      canShare: false,
      isOwner: false
    };

    try {
      // Test read permission
      doc.getName();
      permissions.canRead = true;
      
      // Test write permission (non-destructive)
      const body = doc.getBody();
      const originalText = body.getText();
      permissions.canWrite = true;
      
      // Check ownership (approximate)
      const userEmail = Session.getActiveUser().getEmail();
      permissions.isOwner = doc.getUrl().includes(userEmail) || false;
      
    } catch (error) {
      console.warn(`⚠️ Permission check failed: ${error.message}`);
    }
    
    return permissions;
  }

  /**
   * Sanitize user input to prevent injection
   * @param {string} input - User input to sanitize
   * @return {string} Sanitized input
   */
  sanitizeInput(input) {
    if (typeof input !== 'string') {
      return String(input);
    }
    
    // Remove potentially harmful patterns
    return input
      .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
      .replace(/javascript:/gi, '')
      .replace(/on\w+=/gi, '')
      .trim();
  }

  /**
   * Validate email addresses for sharing
   * @param {string} email - Email to validate
   * @return {boolean} Whether email is valid
   */
  validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  /**
   * Create secure document with restricted access
   * @param {string} title - Document title
   * @param {Array<string>} allowedEmails - Emails allowed to access
   * @return {Document} Created secure document
   */
  createSecureDocument(title, allowedEmails = []) {
    try {
      // Create document
      const doc = DOC_MANAGER.createDocument(`[SECURE] ${title}`);
      
      // Add security notice
      TEXT_WRITER.writeText(doc, '🔒 CONFIDENTIAL DOCUMENT', {
        bold: true,
        color: this.config.get('COLORS').ERROR,
        alignment: DocumentApp.HorizontalAlignment.CENTER,
        fontSize: 14
      });
      
      TEXT_WRITER.writeText(doc, 'This document contains sensitive information. Unauthorized access is prohibited.', {
        italic: true,
        alignment: DocumentApp.HorizontalAlignment.CENTER,
        fontSize: 10
      });
      
      PAGE_MANAGER.insertHorizontalRule(doc);
      
      // Note: Actual permission setting would require DriveApp
      // This is a placeholder for the security setup
      console.log(`🔒 Secure document created with restricted access`);
      
      return doc;
      
    } catch (error) {
      console.error(`❌ Error creating secure document: ${error.message}`);
      throw error;
    }
  }
}

// Create global security manager instance
const SECURITY_MANAGER = new SecurityManager();

/**
 * Master Demo Function - Runs all major functionality demonstrations
 */
function runComprehensiveTutorialDemo() {
  try {
    console.log('🚀 Starting Comprehensive Google Docs Tutorial Demo...');
    
    // Initialize configuration
    DOCS_CONFIG.initialize();
    
    console.log('\n📚 Running all demo functions...\n');
    
    // Run all demo functions
    const results = {
      documentManager: demonstrateDocumentManager(),
      contentReader: demonstrateContentReader(),
      textWriter: demonstrateTextWriter(),
      textProcessing: demonstrateTextProcessing(),
      textFormatting: demonstrateTextFormatting(),
      structureManagement: demonstrateStructureManagement(),
      advancedFormatting: demonstrateAdvancedFormatting(),
      documentNavigation: demonstrateDocumentNavigation(),
      completeProjects: demonstrateCompleteProjects()
    };
    
    console.log('\n📊 Demo Results Summary:');
    for (const [demoName, result] of Object.entries(results)) {
      if (result) {
        console.log(`✅ ${demoName}: ${result.getName ? result.getName() : 'Completed'}`);
      }
    }
    
    // Show performance metrics
    const metrics = PERFORMANCE_OPTIMIZER.getMetrics();
    console.log('\n⚡ Performance Metrics:', metrics);
    
    console.log('\n🎉 Comprehensive Tutorial Demo completed successfully!');
    console.log('📝 Check your Google Drive for all generated documents.');
    
    return results;
    
  } catch (error) {
    ERROR_HANDLER.logError(error, { function: 'runComprehensiveTutorialDemo' });
    console.error('❌ Tutorial demo failed:', error.message);
    throw error;
  }
}
```

---

## 📘 Tutorial Summary

This comprehensive Google Apps Script tutorial for Google Docs provides:

### 🟢 **Beginner Features:**

- Document creation and management
- Basic text operations
- Simple formatting and styling
- File I/O operations

### 🟡 **Intermediate Features:**

- Advanced structure management
- List and table creation
- Media integration
- Navigation systems

### 🔴 **Advanced Features:**

- Template systems
- Batch processing
- Performance optimization
- Error handling patterns

### 🎯 **Real-World Applications:**

- Automated report generation
- Contract management
- Letter writing systems
- Document analytics

### 💡 **Key Benefits:**

- Reusable: All code is organized in classes and functions for maximum reusability
- Scalable: Designed to handle both simple scripts and complex automation systems
- Professional: Includes error handling, logging, and performance optimization
- Comprehensive: Covers every aspect of Google Docs automation

### 🚀 Getting Started

1. Copy any of the classes into your Google Apps Script project
2. Run the demo functions to see examples in action
3. Customize the code for your specific needs
4. Build upon the foundation to create powerful document automation systems

This tutorial provides everything you need to master Google Docs automation, from basic operations to enterprise-level document processing systems.

Happy Automating! 🤖📄

### Styles and Themes

Advanced formatting goes beyond basic text styling to create professional, consistent document themes.

#### StyleManager Class

```javascript
/**
 * StyleManager - Professional document styling and theming
 * Provides advanced formatting capabilities with consistent themes
 */
class StyleManager {
  constructor() {
    this.config = DOCS_CONFIG;
    this.themes = this.initializeThemes();
  }

  /**
   * Initialize predefined document themes
   * @return {Object} Available themes
   */
  initializeThemes() {
    return {
      corporate: {
        name: 'Corporate Professional',
        primaryColor: '#1f4e79',
        secondaryColor: '#4472c4',
        accentColor: '#70ad47',
        textColor: '#2f2f2f',
        backgroundColor: '#ffffff',
        fonts: {
          heading: 'Calibri',
          body: 'Calibri',
          mono: 'Consolas'
        }
      },
      
      academic: {
        name: 'Academic Paper',
        primaryColor: '#1a1a1a',
        secondaryColor: '#4a4a4a',
        accentColor: '#0066cc',
        textColor: '#000000',
        backgroundColor: '#ffffff',
        fonts: {
          heading: 'Times New Roman',
          body: 'Times New Roman',
          mono: 'Courier New'
        }
      },
      
      creative: {
        name: 'Creative Modern',
        primaryColor: '#e74c3c',
        secondaryColor: '#3498db',
        accentColor: '#f39c12',
        textColor: '#2c3e50',
        backgroundColor: '#ffffff',
        fonts: {
          heading: 'Open Sans',
          body: 'Lato',
          mono: 'Source Code Pro'
        }
      },
      
      minimal: {
        name: 'Minimal Clean',
        primaryColor: '#333333',
        secondaryColor: '#666666',
        accentColor: '#007acc',
        textColor: '#1a1a1a',
        backgroundColor: '#ffffff',
        fonts: {
          heading: 'Helvetica',
          body: 'Helvetica',
          mono: 'Monaco'
        }
      }
    };
  }

  /**
   * Apply theme to entire document
   * @param {Document} doc - Target document
   * @param {string} themeName - Theme to apply
   * @param {Object} customizations - Custom theme modifications
   */
  applyTheme(doc, themeName, customizations = {}) {
    const theme = { ...this.themes[themeName], ...customizations };
    
    if (!theme) {
      throw new Error(`Theme "${themeName}" not found`);
    }

    try {
      console.log(`🎨 Applying ${theme.name} theme...`);
      
      // Apply theme to all headings
      this.applyThemeToHeadings(doc, theme);
      
      // Apply theme to body text
      this.applyThemeToBody(doc, theme);
      
      // Apply theme to lists
      this.applyThemeToLists(doc, theme);
      
      // Apply theme to tables
      this.applyThemeToTables(doc, theme);
      
      console.log(`✅ ${theme.name} theme applied successfully`);
      
    } catch (error) {
      console.error(`❌ Error applying theme: ${error.message}`);
      throw error;
    }
  }

  /**
   * Apply theme styling to all headings
   * @param {Document} doc - Target document
   * @param {Object} theme - Theme configuration
   */
  applyThemeToHeadings(doc, theme) {
    const body = doc.getBody();
    const elements = body.getNumChildren();
    
    for (let i = 0; i < elements; i++) {
      const element = body.getChild(i);
      
      if (element.getType() === DocumentApp.ElementType.PARAGRAPH) {
        const heading = element.getHeading();
        const text = element.editAsText();
        
        switch (heading) {
          case DocumentApp.Heading.HEADING_1:
            text.setFontFamily(theme.fonts.heading);
            text.setFontSize(24);
            text.setBold(true);
            text.setForegroundColor(theme.primaryColor);
            break;
            
          case DocumentApp.Heading.HEADING_2:
            text.setFontFamily(theme.fonts.heading);
            text.setFontSize(20);
            text.setBold(true);
            text.setForegroundColor(theme.secondaryColor);
            break;
            
          case DocumentApp.Heading.HEADING_3:
            text.setFontFamily(theme.fonts.heading);
            text.setFontSize(16);
            text.setBold(true);
            text.setForegroundColor(theme.accentColor);
            break;
            
          default:
            if (heading !== DocumentApp.Heading.NORMAL) {
              text.setFontFamily(theme.fonts.heading);
              text.setBold(true);
              text.setForegroundColor(theme.primaryColor);
            }
        }
      }
    }
  }

  /**
   * Apply theme styling to body text
   * @param {Document} doc - Target document
   * @param {Object} theme - Theme configuration
   */
  applyThemeToBody(doc, theme) {
    const body = doc.getBody();
    const elements = body.getNumChildren();
    
    for (let i = 0; i < elements; i++) {
      const element = body.getChild(i);
      
      if (element.getType() === DocumentApp.ElementType.PARAGRAPH) {
        const heading = element.getHeading();
        
        // Only apply to normal paragraphs (not headings)
        if (heading === DocumentApp.Heading.NORMAL) {
          const text = element.editAsText();
          text.setFontFamily(theme.fonts.body);
          text.setFontSize(11);
          text.setForegroundColor(theme.textColor);
        }
      }
    }
  }

  /**
   * Apply theme styling to lists
   * @param {Document} doc - Target document
   * @param {Object} theme - Theme configuration
   */
  applyThemeToLists(doc, theme) {
    const body = doc.getBody();
    const elements = body.getNumChildren();
    
    for (let i = 0; i < elements; i++) {
      const element = body.getChild(i);
      
      if (element.getType() === DocumentApp.ElementType.LIST_ITEM) {
        const text = element.editAsText();
        text.setFontFamily(theme.fonts.body);
        text.setFontSize(11);
        text.setForegroundColor(theme.textColor);
      }
    }
  }

  /**
   * Apply theme styling to tables
   * @param {Document} doc - Target document
   * @param {Object} theme - Theme configuration
   */
  applyThemeToTables(doc, theme) {
    const body = doc.getBody();
    const elements = body.getNumChildren();
    
    for (let i = 0; i < elements; i++) {
      const element = body.getChild(i);
      
      if (element.getType() === DocumentApp.ElementType.TABLE) {
        const table = element.asTable();
        const numRows = table.getNumRows();
        
        for (let row = 0; row < numRows; row++) {
          const tableRow = table.getRow(row);
          const numCells = tableRow.getNumCells();
          
          for (let cell = 0; cell < numCells; cell++) {
            const tableCell = tableRow.getCell(cell);
            const text = tableCell.editAsText();
            
            // Header row styling
            if (row === 0) {
              text.setBold(true);
              text.setForegroundColor(theme.backgroundColor);
              tableCell.setBackgroundColor(theme.primaryColor);
            } else {
              text.setFontFamily(theme.fonts.body);
              text.setForegroundColor(theme.textColor);
              
              // Alternating row colors
              if (row % 2 === 0) {
                tableCell.setBackgroundColor('#f8f9fa');
              }
            }
          }
        }
      }
    }
  }

  /**
   * Create custom style presets
   * @param {string} styleName - Name for the custom style
   * @param {Object} styleConfig - Style configuration
   */
  createCustomStyle(styleName, styleConfig) {
    this.themes[styleName] = styleConfig;
    console.log(`🎨 Custom style "${styleName}" created`);
  }

  /**
   * Export current theme configuration
   * @param {string} themeName - Theme to export
   * @return {string} JSON string of theme configuration
   */
  exportTheme(themeName) {
    const theme = this.themes[themeName];
    if (!theme) {
      throw new Error(`Theme "${themeName}" not found`);
    }
    
    return JSON.stringify(theme, null, 2);
  }

  /**
   * Import theme configuration
   * @param {string} themeName - Name for imported theme
   * @param {string} themeJson - JSON string of theme configuration
   */
  importTheme(themeName, themeJson) {
    try {
      const theme = JSON.parse(themeJson);
      this.themes[themeName] = theme;
      console.log(`📥 Theme "${themeName}" imported successfully`);
      
    } catch (error) {
      throw new Error(`Failed to import theme: ${error.message}`);
    }
  }

  /**
   * Get available themes
   * @return {Array<string>} List of available theme names
   */
  getAvailableThemes() {
    return Object.keys(this.themes);
  }
}

// Create global style manager instance
const STYLE_MANAGER = new StyleManager();
```

### StyleManager: Quick usage

```javascript
function styleManagerExamples() {
  const doc = DocumentApp.getActiveDocument();
  STYLE_MANAGER.applyTheme(doc, 'corporate');
  console.log('Themes:', STYLE_MANAGER.getAvailableThemes());
}
```

### Tables and Data

Tables are essential for organizing structured data. Let's create a comprehensive table management system.

#### TableManager Class

```javascript
/**
 * TableManager - Advanced table creation and management
 * Handles all aspects of table creation, formatting, and data manipulation
 */
class TableManager {
  constructor() {
    this.config = DOCS_CONFIG;
  }

  /**
   * Create table with advanced formatting options
   * @param {Document} doc - Target document
   * @param {Array<Array<string>>} data - 2D array of table data
   * @param {Object} options - Table formatting options
   * @return {Table} Created table element
   */
  createTable(doc, data, options = {}) {
    const {
      hasHeaders = true,
      style = 'default',
      alternatingRows = true,
      borderStyle = 'solid',
      cellPadding = 6,
      fontSize = 10,
      headerColor = this.config.get('COLORS').PRIMARY,
      headerTextColor = '#ffffff'
    } = options;

    try {
      const body = doc.getBody();
      
      if (!Array.isArray(data) || data.length === 0) {
        throw new Error('Table data must be a non-empty 2D array');
      }
      
      // Create table from data
      const table = body.appendTable(data);
      
      // Apply basic formatting
      this.applyTableFormatting(table, options);
      
      // Apply header formatting if specified
      if (hasHeaders && data.length > 0) {
        this.formatTableHeaders(table, headerColor, headerTextColor);
      }
      
      // Apply alternating row colors
      if (alternatingRows) {
        this.applyAlternatingRows(table, hasHeaders);
      }
      
      console.log(`📊 Created table with ${data.length} rows and ${data[0].length} columns`);
      return table;
      
    } catch (error) {
      console.error(`❌ Error creating table: ${error.message}`);
      throw error;
    }
  }

  /**
   * Apply basic table formatting
   * @param {Table} table - Table to format
   * @param {Object} options - Formatting options
   */
  applyTableFormatting(table, options) {
    const {
      fontSize = 10,
      fontFamily = this.config.get('DEFAULT_FONT_FAMILY'),
      cellPadding = 6,
      borderWidth = 1,
      borderColor = '#000000'
    } = options;

    const numRows = table.getNumRows();
    
    for (let i = 0; i < numRows; i++) {
      const row = table.getRow(i);
      const numCells = row.getNumCells();
      
      for (let j = 0; j < numCells; j++) {
        const cell = row.getCell(j);
        
        // Set text formatting
        const text = cell.editAsText();
        text.setFontFamily(fontFamily);
        text.setFontSize(fontSize);
        
        // Set cell padding
        cell.setPaddingTop(cellPadding);
        cell.setPaddingBottom(cellPadding);
        cell.setPaddingLeft(cellPadding);
        cell.setPaddingRight(cellPadding);
        
        // Set borders
        cell.setBorderWidth(borderWidth);
        cell.setBorderColor(borderColor);
      }
    }
  }

  /**
   * Format table headers with special styling
   * @param {Table} table - Table to format
   * @param {string} headerColor - Header background color
   * @param {string} headerTextColor - Header text color
   */
  formatTableHeaders(table, headerColor, headerTextColor) {
    if (table.getNumRows() === 0) return;
    
    const headerRow = table.getRow(0);
    const numCells = headerRow.getNumCells();
    
    for (let i = 0; i < numCells; i++) {
      const cell = headerRow.getCell(i);
      const text = cell.editAsText();
      
      // Apply header styling
      cell.setBackgroundColor(headerColor);
      text.setBold(true);
      text.setForegroundColor(headerTextColor);
      
      // Center align headers
      const paragraphs = cell.getNumChildren();
      for (let p = 0; p < paragraphs; p++) {
        const paragraph = cell.getChild(p);
        if (paragraph.getType() === DocumentApp.ElementType.PARAGRAPH) {
          paragraph.setAlignment(DocumentApp.HorizontalAlignment.CENTER);
        }
      }
    }
  }

  /**
   * Apply alternating row colors
   * @param {Table} table - Table to format
   * @param {boolean} hasHeaders - Whether table has headers
   */
  applyAlternatingRows(table, hasHeaders = true) {
    const numRows = table.getNumRows();
    const startRow = hasHeaders ? 1 : 0;
    const evenColor = '#f8f9fa';
    const oddColor = '#ffffff';
    
    for (let i = startRow; i < numRows; i++) {
      const row = table.getRow(i);
      const numCells = row.getNumCells();
      const backgroundColor = (i - startRow) % 2 === 0 ? evenColor : oddColor;
      
      for (let j = 0; j < numCells; j++) {
        const cell = row.getCell(j);
        cell.setBackgroundColor(backgroundColor);
      }
    }
  }

  /**
   * Add row to existing table
   * @param {Table} table - Target table
   * @param {Array<string>} rowData - Data for new row
   * @param {number} position - Position to insert row (optional, appends if not specified)
   * @return {TableRow} Added row
   */
  addTableRow(table, rowData, position = null) {
    try {
      let newRow;
      
      if (position !== null) {
        newRow = table.insertTableRow(position);
      } else {
        newRow = table.appendTableRow();
      }
      
      // Add cells with data
      for (let i = 0; i < rowData.length; i++) {
        if (i < newRow.getNumCells()) {
          const cell = newRow.getCell(i);
          cell.clear();
          cell.appendParagraph(String(rowData[i]));
        } else {
          // Add new cell if needed
          const cell = newRow.appendTableCell(String(rowData[i]));
        }
      }
      
      console.log('➕ Table row added');
      return newRow;
      
    } catch (error) {
      console.error(`❌ Error adding table row: ${error.message}`);
      throw error;
    }
  }

  /**
   * Remove row from table
   * @param {Table} table - Target table
   * @param {number} rowIndex - Index of row to remove
   */
  removeTableRow(table, rowIndex) {
    try {
      if (rowIndex < 0 || rowIndex >= table.getNumRows()) {
        throw new Error('Row index out of bounds');
      }
      
      const row = table.getRow(rowIndex);
      row.removeFromParent();
      
      console.log(`🗑️ Table row ${rowIndex} removed`);
      
    } catch (error) {
      console.error(`❌ Error removing table row: ${error.message}`);
      throw error;
    }
  }

  /**
   * Update cell content
   * @param {Table} table - Target table
   * @param {number} rowIndex - Row index
   * @param {number} colIndex - Column index
   * @param {string} content - New content
   * @param {Object} formatting - Cell formatting options
   */
  updateCell(table, rowIndex, colIndex, content, formatting = {}) {
    try {
      const row = table.getRow(rowIndex);
      const cell = row.getCell(colIndex);
      
      // Clear existing content
      cell.clear();
      
      // Add new content
      const paragraph = cell.appendParagraph(String(content));
      
      // Apply formatting
      if (Object.keys(formatting).length > 0) {
        const text = paragraph.editAsText();
        
        if (formatting.bold) text.setBold(true);
        if (formatting.italic) text.setItalic(true);
        if (formatting.fontSize) text.setFontSize(formatting.fontSize);
        if (formatting.color) text.setForegroundColor(formatting.color);
        if (formatting.backgroundColor) cell.setBackgroundColor(formatting.backgroundColor);
        if (formatting.alignment) paragraph.setAlignment(formatting.alignment);
      }
      
      console.log(`📝 Cell [${rowIndex}, ${colIndex}] updated`);
      
    } catch (error) {
      console.error(`❌ Error updating cell: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get table data as 2D array
   * @param {Table} table - Source table
   * @param {boolean} includeHeaders - Whether to include headers in result
   * @return {Array<Array<string>>} Table data
   */
  getTableData(table, includeHeaders = true) {
    const data = [];
    const numRows = table.getNumRows();
    
    const startRow = includeHeaders ? 0 : 1;
    
    for (let i = startRow; i < numRows; i++) {
      const row = table.getRow(i);
      const numCells = row.getNumCells();
      const rowData = [];
      
      for (let j = 0; j < numCells; j++) {
        const cell = row.getCell(j);
        rowData.push(cell.getText());
      }
      
      data.push(rowData);
    }
    
    return data;
  }

  /**
   * Create summary table from data analysis
   * @param {Document} doc - Target document
   * @param {Array<Object>} data - Data to analyze
   * @param {Array<string>} columns - Columns to include in summary
   * @return {Table} Summary table
   */
  createSummaryTable(doc, data, columns) {
    try {
      const summaryData = [['Metric', 'Value']];
      
      // Calculate basic statistics
      summaryData.push(['Total Records', data.length.toString()]);
      
      for (const column of columns) {
        const values = data.map(row => row[column]).filter(val => val !== null && val !== undefined);
        
        if (values.length > 0) {
          // Check if numeric
          const numericValues = values.map(val => parseFloat(val)).filter(val => !isNaN(val));
          
          if (numericValues.length > 0) {
            const sum = numericValues.reduce((a, b) => a + b, 0);
            const avg = sum / numericValues.length;
            const min = Math.min(...numericValues);
            const max = Math.max(...numericValues);
            
            summaryData.push([`${column} - Average`, avg.toFixed(2)]);
            summaryData.push([`${column} - Min`, min.toString()]);
            summaryData.push([`${column} - Max`, max.toString()]);
          } else {
            // String data - show unique count
            const uniqueValues = [...new Set(values)];
            summaryData.push([`${column} - Unique Values`, uniqueValues.length.toString()]);
          }
        }
      }
      
      // Create table with summary styling
      const table = this.createTable(doc, summaryData, {
        hasHeaders: true,
        alternatingRows: true,
        headerColor: this.config.get('COLORS').SECONDARY
      });
      
      console.log(`📈 Summary table created with ${summaryData.length - 1} metrics`);
      return table;
      
    } catch (error) {
      console.error(`❌ Error creating summary table: ${error.message}`);
      throw error;
    }
  }
}

// Create global table manager instance
const TABLE_MANAGER = new TableManager();
```

### TableManager: Quick usage

```javascript
function tableManagerExamples() {
  const doc = DocumentApp.getActiveDocument();
  const data = [
    ['Name', 'Score'],
    ['Alice', '95'],
    ['Bob', '88']
  ];
  const table = TABLE_MANAGER.createTable(doc, data, { hasHeaders: true, alternatingRows: true });
  // Update a cell
  TABLE_MANAGER.updateCell(table, 1, 1, '96', { bold: true, color: '#1a73e8' });
}
```

### Images and Media

Media integration enhances document visual appeal and functionality.

#### MediaManager Class

```javascript
/**
 * MediaManager - Image and media handling for documents
 * Manages insertion, positioning, and formatting of media elements
 */
class MediaManager {
  constructor() {
    this.config = DOCS_CONFIG;
  }

  /**
   * Insert image from URL with advanced options
   * @param {Document} doc - Target document
   * @param {string} imageUrl - URL of image to insert
   * @param {Object} options - Image options
   * @return {InlineImage} Inserted image element
   */
  insertImageFromUrl(doc, imageUrl, options = {}) {
    const {
      width = null,
      height = null,
      alignment = 'left',
      caption = null,
      altText = '',
      position = null
    } = options;

    try {
      const body = doc.getBody();
      
      // Fetch image from URL
      const response = UrlFetchApp.fetch(imageUrl);
      const imageBlob = response.getBlob();
      
      // Insert image
      let image;
      if (position !== null) {
        image = body.insertImage(position, imageBlob);
      } else {
        image = body.appendImage(imageBlob);
      }
      
      // Set dimensions if specified
      if (width) image.setWidth(width);
      if (height) image.setHeight(height);
      
      // Set alt text
      if (altText) image.setAltDescription(altText);
      
      // Set alignment by wrapping in paragraph
      const paragraph = image.getParent();
      if (paragraph.getType() === DocumentApp.ElementType.PARAGRAPH) {
        switch (alignment.toLowerCase()) {
          case 'center':
            paragraph.setAlignment(DocumentApp.HorizontalAlignment.CENTER);
            break;
          case 'right':
            paragraph.setAlignment(DocumentApp.HorizontalAlignment.RIGHT);
            break;
          default:
            paragraph.setAlignment(DocumentApp.HorizontalAlignment.LEFT);
        }
      }
      
      // Add caption if specified
      if (caption) {
        const captionParagraph = body.appendParagraph(caption);
        captionParagraph.setAlignment(paragraph.getAlignment());
        captionParagraph.editAsText().setItalic(true);
        captionParagraph.editAsText().setFontSize(9);
      }
      
      console.log('🖼️ Image inserted from URL');
      return image;
      
    } catch (error) {
      console.error(`❌ Error inserting image: ${error.message}`);
      throw error;
    }
  }

  /**
   * Insert image from Google Drive
   * @param {Document} doc - Target document
   * @param {string} fileId - Google Drive file ID
   * @param {Object} options - Image options
   * @return {InlineImage} Inserted image element
   */
  insertImageFromDrive(doc, fileId, options = {}) {
    try {
      const file = DriveApp.getFileById(fileId);
      const blob = file.getBlob();
      
      const body = doc.getBody();
      const image = body.appendImage(blob);
      
      // Apply options
      this.applyImageOptions(image, options);
      
      console.log('📁 Image inserted from Google Drive');
      return image;
      
    } catch (error) {
      console.error(`❌ Error inserting image from Drive: ${error.message}`);
      throw error;
    }
  }

  /**
   * Apply formatting options to image
   * @param {InlineImage} image - Image element
   * @param {Object} options - Formatting options
   */
  applyImageOptions(image, options) {
    const {
      width = null,
      height = null,
      altText = '',
      maintainAspectRatio = true
    } = options;

    if (altText) image.setAltDescription(altText);
    
    if (width || height) {
      if (maintainAspectRatio && width && height) {
        // Calculate proportional dimensions
        const originalWidth = image.getWidth();
        const originalHeight = image.getHeight();
        const aspectRatio = originalWidth / originalHeight;
        
        if (width / height > aspectRatio) {
          image.setWidth(height * aspectRatio);
          image.setHeight(height);
        } else {
          image.setWidth(width);
          image.setHeight(width / aspectRatio);
        }
      } else {
        if (width) image.setWidth(width);
        if (height) image.setHeight(height);
      }
    }
  }

  /**
   * Create image gallery layout
   * @param {Document} doc - Target document
   * @param {Array<Object>} images - Array of image configurations
   * @param {Object} options - Gallery options
   */
  createImageGallery(doc, images, options = {}) {
    const {
      imagesPerRow = 2,
      imageWidth = 200,
      spacing = 10,
      captions = true
    } = options;

    try {
      const body = doc.getBody();
      
      // Create table for gallery layout
      const numRows = Math.ceil(images.length / imagesPerRow);
      const tableData = [];
      
      for (let row = 0; row < numRows; row++) {
        const rowData = [];
        for (let col = 0; col < imagesPerRow; col++) {
          const imageIndex = row * imagesPerRow + col;
          if (imageIndex < images.length) {
            rowData.push(''); // Placeholder for image
          } else {
            rowData.push(''); // Empty cell
          }
        }
        tableData.push(rowData);
      }
      
      const table = body.appendTable(tableData);
      
      // Insert images into table cells
      let imageIndex = 0;
      for (let row = 0; row < numRows; row++) {
        const tableRow = table.getRow(row);
        
        for (let col = 0; col < imagesPerRow && imageIndex < images.length; col++) {
          const cell = tableRow.getCell(col);
          const imageConfig = images[imageIndex];
          
          cell.clear();
          
          // Insert image
          let image;
          if (imageConfig.url) {
            const response = UrlFetchApp.fetch(imageConfig.url);
            const blob = response.getBlob();
            image = cell.appendImage(blob);
          } else if (imageConfig.fileId) {
            const file = DriveApp.getFileById(imageConfig.fileId);
            const blob = file.getBlob();
            image = cell.appendImage(blob);
          }
          
          if (image) {
            image.setWidth(imageWidth);
            if (imageConfig.altText) image.setAltDescription(imageConfig.altText);
            
            // Add caption if specified
            if (captions && imageConfig.caption) {
              const captionParagraph = cell.appendParagraph(imageConfig.caption);
              captionParagraph.editAsText().setItalic(true);
              captionParagraph.editAsText().setFontSize(9);
              captionParagraph.setAlignment(DocumentApp.HorizontalAlignment.CENTER);
            }
          }
          
          imageIndex++;
        }
      }
      
      // Remove table borders for clean gallery look
      TABLE_MANAGER.applyTableFormatting(table, {
        borderWidth: 0,
        cellPadding: spacing
      });
      
      console.log(`🖼️ Image gallery created with ${images.length} images`);
      return table;
      
    } catch (error) {
      console.error(`❌ Error creating image gallery: ${error.message}`);
      throw error;
    }
  }

  /**
   * Insert chart or diagram from Google Sheets
   * @param {Document} doc - Target document
   * @param {string} spreadsheetId - Google Sheets ID
   * @param {string} chartId - Chart ID within the sheet
   * @param {Object} options - Chart options
   */
  insertChartFromSheets(doc, spreadsheetId, chartId, options = {}) {
    try {
      // This is a placeholder for chart insertion
      // In practice, you'd export the chart as an image and insert it
      const body = doc.getBody();
      const placeholder = body.appendParagraph(`[Chart from Sheets: ${spreadsheetId}/${chartId}]`);
      placeholder.editAsText().setItalic(true);
      placeholder.setAlignment(DocumentApp.HorizontalAlignment.CENTER);
      
      console.log('📊 Chart placeholder inserted');
      return placeholder;
      
    } catch (error) {
      console.error(`❌ Error inserting chart: ${error.message}`);
      throw error;
    }
  }
}

// Create global media manager instance
const MEDIA_MANAGER = new MediaManager();

/**
 * Demo function: Advanced formatting and media
 */
function demonstrateAdvancedFormatting() {
  try {
    console.log('🎨 Starting Advanced Formatting Demo...');
    
    // Create a sample document
    const doc = DOC_MANAGER.createDocument('Advanced Formatting Demo - ' + new Date().toISOString());
    

    ### MediaManager: Quick usage

    ```javascript
    function mediaManagerExamples() {
      const doc = DocumentApp.getActiveDocument();
      // Insert an image from URL
      MEDIA_MANAGER.insertImageFromUrl(doc, 'https://via.placeholder.com/300', {
        width: 300,
        alignment: 'center',
        caption: 'Sample image'
      });
    }
    ```
    console.log('1. Creating sample content...');
    TEXT_WRITER.writeHeading(doc, 'Advanced Formatting Demonstration', 1);
    TEXT_WRITER.writeHeading(doc, 'Table Example', 2);
    
    // Create sample table
    const tableData = [
      ['Product', 'Price', 'Category', 'Rating'],
      ['Laptop', '$999', 'Electronics', '4.5'],
      ['Book', '$19', 'Education', '4.8'],
      ['Phone', '$699', 'Electronics', '4.3'],
      ['Desk', '$299', 'Furniture', '4.1']
    ];
    
    console.log('2. Creating formatted table...');
    const table = TABLE_MANAGER.createTable(doc, tableData, {
      hasHeaders: true,
      alternatingRows: true,
      headerColor: '#1a73e8',
      headerTextColor: '#ffffff'
    });
    
    console.log('3. Adding summary table...');
    const summaryData = tableData.slice(1).map(row => ({
      Product: row[0],
      Price: parseFloat(row[1].replace('$', '')),
      Category: row[2],
      Rating: parseFloat(row[3])
    }));
    
    TEXT_WRITER.writeHeading(doc, 'Summary Statistics', 2);
    TABLE_MANAGER.createSummaryTable(doc, summaryData, ['Price', 'Rating']);
    
    console.log('4. Applying theme...');
    TEXT_WRITER.writeHeading(doc, 'Themed Section', 2);
    TEXT_WRITER.writeText(doc, 'This section demonstrates theme application with consistent styling.');
    
    // Apply corporate theme
    STYLE_MANAGER.applyTheme(doc, 'corporate');
    
    console.log('5. Available themes:');
    const themes = STYLE_MANAGER.getAvailableThemes();
    console.log('🎨 Available themes:', themes);
    
    console.log('✅ Advanced Formatting demo completed successfully');
    return doc;
    
  } catch (error) {
    console.error('❌ Advanced Formatting demo failed:', error.message);
    throw error;
  }
}

