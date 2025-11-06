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
 * OPTIMIZED: Minimizes API calls by caching body reference
 * @param {string} text - The text to insert
 * @param {Object} options - Formatting options (optional)
 * @returns {GoogleAppsScript.Document.Paragraph}
 */
function insertText(text, options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody(); // Cache body reference
  
  const cursor = doc.getCursor();
  if (cursor) {
    const element = cursor.getElement();
    const textElement = element.asText();
    textElement.insertText(cursor.getOffset(), text);
    
    // Apply formatting in single pass
    if (Object.keys(options).length > 0) {
      const start = cursor.getOffset();
      const end = start + text.length;
      if (options.bold) textElement.setBold(start, end, true);
      if (options.italic) textElement.setItalic(start, end, true);
      if (options.fontSize) textElement.setFontSize(start, end, options.fontSize);
    }
    
    return element.getParent().asParagraph();
  } else {
    const paragraph = body.appendParagraph(text);
    const textElement = paragraph.editAsText();
    
    // Batch formatting operations
    if (options.bold) textElement.setBold(true);
    if (options.italic) textElement.setItalic(true);
    if (options.fontSize) textElement.setFontSize(options.fontSize);
    if (options.fontFamily) textElement.setFontFamily(options.fontFamily);
    if (options.textColor) textElement.setForegroundColor(options.textColor);
    
    return paragraph;
  }
}

/**
 * Replaces text in a document with optional case sensitivity
 * OPTIMIZED: Uses RangeElement for efficient searching and minimal API calls
 * @param {string} findText - Text to find
 * @param {string} replaceText - Text to replace with
 * @param {boolean} caseSensitive - Whether to perform case sensitive search (default: true)
 * @returns {number} Number of replacements made
 */
function replaceText(findText, replaceText, caseSensitive = true) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  let replacedCount = 0;
  
  // Use built-in search functionality for better performance
  const searchType = caseSensitive ? 
    DocumentApp.SearchType.NORMAL : 
    null;
  
  if (!caseSensitive) {
    // For case-insensitive, use replaceText method (most efficient)
    const pattern = new RegExp(findText, 'gi');
    replacedCount = body.replaceText(findText, replaceText) ? 1 : 0;
    
    // Count actual replacements by searching
    let searchResult = body.findText(replaceText);
    while (searchResult !== null) {
      replacedCount++;
      searchResult = body.findText(replaceText, searchResult);
    }
    
    return replacedCount;
  }
  
  // For case-sensitive replacement
  let searchResult = body.findText(findText);
  const replacements = []; // Batch replacements
  
  while (searchResult !== null) {
    const element = searchResult.getElement();
    const startOffset = searchResult.getStartOffset();
    const endOffset = searchResult.getEndOffsetInclusive();
    
    replacements.push({
      element: element,
      start: startOffset,
      end: endOffset
    });
    
    searchResult = body.findText(findText, searchResult);
  }
  
  // Perform replacements in reverse order to maintain indices
  for (let i = replacements.length - 1; i >= 0; i--) {
    const { element, start, end } = replacements[i];
    const textElement = element.asText();
    textElement.deleteText(start, end);
    textElement.insertText(start, replaceText);
    replacedCount++;
  }
  
  return replacedCount;
}

/**
 * Gets all text elements from a document body
 * OPTIMIZED: Uses ChildIndex for efficient element access
 * @param {GoogleAppsScript.Document.Body} body - Document body object
 * @returns {Array<GoogleAppsScript.Document.Element>} Array of text elements
 */
function getAllTextElements(body) {
  const elements = [];
  const numChildren = body.getNumChildren();
  
  // Use indexed access instead of iterator for better performance
  for (let i = 0; i < numChildren; i++) {
    const element = body.getChild(i);
    const type = element.getType();
    
    if (type === DocumentApp.ElementType.PARAGRAPH) {
      elements.push(element);
    } else if (type === DocumentApp.ElementType.LIST_ITEM) {
      elements.push(element);
    } else if (type === DocumentApp.ElementType.TABLE) {
      // Recursively get table cell elements
      const table = element.asTable();
      const numRows = table.getNumRows();
      for (let r = 0; r < numRows; r++) {
        const row = table.getRow(r);
        const numCells = row.getNumCells();
        for (let c = 0; c < numCells; c++) {
          const cell = row.getCell(c);
          elements.push(...getAllTextElements(cell));
        }
      }
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
 * OPTIMIZED: Batch formatting operations to reduce API calls
 * @param {Object} options - Format options (optional)
 * @returns {void}
 */
function formatSelectedText(options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const selection = doc.getSelection();
  
  if (!selection) {
    console.log('No text selected');
    return;
  }
  
  const rangeElements = selection.getRangeElements();
  
  // Batch process all elements
  for (let i = 0; i < rangeElements.length; i++) {
    const rangeElement = rangeElements[i];
    const element = rangeElement.getElement();
    
    if (element.getType() === DocumentApp.ElementType.TEXT) {
      const text = element.asText();
      const startOffset = rangeElement.getStartOffset();
      const endOffset = rangeElement.getEndOffsetInclusive();
      
      // Apply all formatting in one pass
      if (startOffset !== -1) {
        // Partial element selection
        if (options.bold !== undefined) text.setBold(startOffset, endOffset, options.bold);
        if (options.italic !== undefined) text.setItalic(startOffset, endOffset, options.italic);
        if (options.underline !== undefined) text.setUnderline(startOffset, endOffset, options.underline);
        if (options.fontSize !== undefined) text.setFontSize(startOffset, endOffset, options.fontSize);
        if (options.fontFamily !== undefined) text.setFontFamily(startOffset, endOffset, options.fontFamily);
        if (options.textColor !== undefined) text.setForegroundColor(startOffset, endOffset, options.textColor);
        if (options.backgroundColor !== undefined) text.setBackgroundColor(startOffset, endOffset, options.backgroundColor);
      } else {
        // Full element selection
        if (options.bold !== undefined) text.setBold(options.bold);
        if (options.italic !== undefined) text.setItalic(options.italic);
        if (options.underline !== undefined) text.setUnderline(options.underline);
        if (options.fontSize !== undefined) text.setFontSize(options.fontSize);
        if (options.fontFamily !== undefined) text.setFontFamily(options.fontFamily);
        if (options.textColor !== undefined) text.setForegroundColor(options.textColor);
        if (options.backgroundColor !== undefined) text.setBackgroundColor(options.backgroundColor);
      }
    }
  }
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
 * OPTIMIZED: Pre-allocates table size and batch formats
 * @param {number} rows - Number of rows (default: 2)
 * @param {number} columns - Number of columns (default: 2)
 * @param {Object} options - Table formatting options (optional)
 * @returns {GoogleAppsScript.Document.Table}
 */
function createTable(rows = 2, columns = 2, options = {}) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  // Create 2D array for initial table data (more efficient)
  const tableData = Array(rows).fill(null).map(() => Array(columns).fill(''));
  
  // Create table with initial data
  const table = body.appendTable(tableData);
  
  // Batch apply formatting
  if (options.borderWidth !== undefined) {
    table.setBorderWidth(options.borderWidth);
  }
  if (options.borderColor) {
    table.setBorderColor(options.borderColor);
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
 * OPTIMIZED: Single editAsText() call with batched operations
 * @param {GoogleAppsScript.Document.TableCell} cell - The cell to format
 * @param {Object} options - Formatting options (optional)
 * @returns {void}
 */
function formatTableCell(cell, options = {}) {
  if (!cell) return;
  
  // Apply cell-level properties first
  if (options.backgroundColor) cell.setBackgroundColor(options.backgroundColor);
  if (options.verticalAlignment) cell.setVerticalAlignment(options.verticalAlignment);
  if (options.width) cell.setWidth(options.width);
  
  // Get text once and apply all text formatting
  const numChildren = cell.getNumChildren();
  for (let i = 0; i < numChildren; i++) {
    const child = cell.getChild(i);
    if (child.getType() === DocumentApp.ElementType.PARAGRAPH) {
      const para = child.asParagraph();
      const text = para.editAsText();
      
      // Batch text formatting
      if (options.bold !== undefined) text.setBold(options.bold);
      if (options.italic !== undefined) text.setItalic(options.italic);
      if (options.fontSize !== undefined) text.setFontSize(options.fontSize);
      if (options.fontFamily) text.setFontFamily(options.fontFamily);
      if (options.textColor) text.setForegroundColor(options.textColor);
      
      // Paragraph alignment
      if (options.alignment) {
        para.setAlignment(options.alignment);
      }
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
 * OFFICIAL BEST PRACTICES from Google Apps Script Documentation
 */
class PerformanceOptimizer {
  constructor() {
    this.config = DOCS_CONFIG;
    this.operationCache = new Map();
    this.metrics = {
      operationsCount: 0,
      totalTime: 0,
      cacheHits: 0,
      apiCalls: 0
    };
  }

  /**
   * Batch multiple document operations for better performance
   * BEST PRACTICE: Minimize calls to document services
   * @param {Array<Function>} operations - Array of operations to batch
   * @return {Array<any>} Results of batched operations
   */
  batchOperations(operations) {
    const startTime = Date.now();
    const results = [];
    
    try {
      console.log(`⚡ Batching ${operations.length} operations...`);
      
      // Cache document and body references
      const doc = DocumentApp.getActiveDocument();
      const body = doc.getBody();
      
      // Execute operations with cached references
      for (const operation of operations) {
        const result = operation(doc, body);
        results.push(result);
      }
      
      const duration = Date.now() - startTime;
      this.metrics.totalTime += duration;
      this.metrics.operationsCount += operations.length;
      
      console.log(`✅ Batch completed in ${duration}ms (avg: ${(duration/operations.length).toFixed(2)}ms per op)`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operation failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process large datasets in chunks to avoid timeout
   * BEST PRACTICE: Handle 6-minute execution limit
   * @param {Array<any>} items - Items to process
   * @param {Function} processor - Processing function
   * @param {number} chunkSize - Size of processing chunks
   * @return {Array<any>} Processed results
   */
  processInChunks(items, processor, chunkSize = 100) {
    const results = [];
    const totalChunks = Math.ceil(items.length / chunkSize);
    const startTime = Date.now();
    const MAX_EXECUTION_TIME = 5.5 * 60 * 1000; // 5.5 minutes (safety margin)
    
    console.log(`🔄 Processing ${items.length} items in ${totalChunks} chunks...`);
    
    for (let i = 0; i < items.length; i += chunkSize) {
      // Check execution time limit
      if (Date.now() - startTime > MAX_EXECUTION_TIME) {
        console.warn(`⚠️ Approaching execution time limit. Processed ${i}/${items.length} items.`);
        break;
      }
      
      const chunk = items.slice(i, i + chunkSize);
      const chunkResults = chunk.map(processor);
      results.push(...chunkResults);
      
      console.log(`✓ Chunk ${Math.floor(i / chunkSize) + 1}/${totalChunks} (${results.length}/${items.length} items)`);
      
      // Minimal delay to prevent rate limiting
      if (i + chunkSize < items.length) {
        Utilities.sleep(50);
      }
    }
    
    return results;
  }

  /**
   * Optimize document reading by minimizing API calls
   * BEST PRACTICE: Cache document structure
   * @param {Document} doc - Document to read
   * @return {Object} Optimized document structure
   */
  optimizeDocumentReading(doc) {
    const cacheKey = doc.getId();
    
    if (this.operationCache.has(cacheKey)) {
      this.metrics.cacheHits++;
      return this.operationCache.get(cacheKey);
    }
    
    const body = doc.getBody();
    const structure = {
      id: doc.getId(),
      name: doc.getName(),
      url: doc.getUrl(),
      numChildren: body.getNumChildren(),
      elements: []
    };
    
    // Single pass to collect all element information
    for (let i = 0; i < structure.numChildren; i++) {
      const element = body.getChild(i);
      structure.elements.push({
        type: element.getType(),
        index: i
      });
    }
    
    this.operationCache.set(cacheKey, structure);
    return structure;
  }

  /**
   * Batch text updates to minimize writes
   * BEST PRACTICE: Combine multiple edits before committing
   * @param {Document} doc - Target document
   * @param {Array<Object>} updates - Array of {element, text, formatting}
   */
  batchTextUpdates(doc, updates) {
    const body = doc.getBody();
    
    // Group updates by element to minimize API calls
    const updatesByElement = new Map();
    
    for (const update of updates) {
      const key = update.elementIndex;
      if (!updatesByElement.has(key)) {
        updatesByElement.set(key, []);
      }
      updatesByElement.get(key).push(update);
    }
    
    // Apply grouped updates
    for (const [elementIndex, elementUpdates] of updatesByElement) {
      const element = body.getChild(elementIndex);
      
      if (element.getType() === DocumentApp.ElementType.PARAGRAPH) {
        const para = element.asParagraph();
        const text = para.editAsText();
        
        // Apply all updates to this element in one pass
        for (const update of elementUpdates) {
          if (update.text !== undefined) {
            text.setText(update.text);
          }
          if (update.formatting) {
            const fmt = update.formatting;
            if (fmt.bold !== undefined) text.setBold(fmt.bold);
            if (fmt.italic !== undefined) text.setItalic(fmt.italic);
            if (fmt.fontSize) text.setFontSize(fmt.fontSize);
            if (fmt.color) text.setForegroundColor(fmt.color);
          }
        }
      }
    }
    
    this.metrics.apiCalls++;
  }

  /**
   * Monitor and log performance metrics
   * BEST PRACTICE: Track execution for optimization
   * @return {Object} Performance statistics
   */
  getMetrics() {
    return {
      ...this.metrics,
      averageOperationTime: this.metrics.operationsCount > 0 
        ? (this.metrics.totalTime / this.metrics.operationsCount).toFixed(2) + 'ms'
        : '0ms',
      cacheHitRatio: this.metrics.operationsCount > 0 
        ? ((this.metrics.cacheHits / this.metrics.operationsCount) * 100).toFixed(1) + '%'
        : '0%',
      estimatedTimeRemaining: (6 * 60 * 1000 - this.metrics.totalTime) + 'ms'
    };
  }

  /**
   * Reset metrics and cache
   * BEST PRACTICE: Clear cache periodically to manage memory
   */
  reset() {
    this.operationCache.clear();
    this.metrics = {
      operationsCount: 0,
      totalTime: 0,
      cacheHits: 0,
      apiCalls: 0
    };
    console.log('🔄 Performance optimizer reset');
  }
}

// Create global performance optimizer instance
const PERFORMANCE_OPTIMIZER = new PerformanceOptimizer();
```

### Advanced Performance Patterns

```javascript
/**
 * BEST PRACTICE: Efficient table creation and manipulation
 * Creates and populates table in a single operation
 */
function createOptimizedTable(doc, data, options = {}) {
  const body = doc.getBody();
  
  // Create table with all data at once (most efficient)
  const table = body.appendTable(data);
  
  // Batch format all cells
  const numRows = table.getNumRows();
  for (let r = 0; r < numRows; r++) {
    const row = table.getRow(r);
    const numCells = row.getNumCells();
    
    for (let c = 0; c < numCells; c++) {
      const cell = row.getCell(c);
      
      // Apply all formatting at once
      if (r === 0 && options.headerStyle) {
        cell.setBackgroundColor(options.headerStyle.backgroundColor || '#4285f4');
        const text = cell.editAsText();
        text.setBold(true);
        text.setForegroundColor(options.headerStyle.color || '#ffffff');
      }
      
      if (options.cellPadding) {
        cell.setPaddingTop(options.cellPadding);
        cell.setPaddingBottom(options.cellPadding);
        cell.setPaddingLeft(options.cellPadding);
        cell.setPaddingRight(options.cellPadding);
      }
    }
  }
  
  return table;
}

/**
 * BEST PRACTICE: Efficient text search and replace
 * Uses built-in methods instead of manual iteration
 */
function optimizedFindAndReplace(doc, searchPattern, replacement) {
  const body = doc.getBody();
  let count = 0;
  
  // Use built-in replaceText for simple patterns (fastest)
  if (typeof searchPattern === 'string') {
    body.replaceText(searchPattern, replacement);
    
    // Count occurrences
    let searchResult = body.findText(replacement);
    while (searchResult !== null) {
      count++;
      searchResult = body.findText(replacement, searchResult);
    }
    
    return count;
  }
  
  // For regex patterns
  body.replaceText(searchPattern.source, replacement);
  return count;
}

/**
 * BEST PRACTICE: Memory-efficient document copying
 * Avoids loading entire document into memory
 */
function optimizedDocumentCopy(sourceDocId, newName) {
  // Use Drive API for efficient copying
  const file = DriveApp.getFileById(sourceDocId);
  const copy = file.makeCopy(newName);
  
  return DocumentApp.openById(copy.getId());
}

/**
 * BEST PRACTICE: Efficient paragraph iteration
 * Minimize getType() calls by using element indices
 */
function optimizedParagraphProcessing(doc, processor) {
  const body = doc.getBody();
  const numChildren = body.getNumChildren();
  const results = [];
  
  // Single pass with indexed access
  for (let i = 0; i < numChildren; i++) {
    const element = body.getChild(i);
    const type = element.getType();
    
    if (type === DocumentApp.ElementType.PARAGRAPH) {
      results.push(processor(element.asParagraph(), i));
    }
  }
  
  return results;
}

/**
 * BEST PRACTICE: Quota-aware operations
 * Implements exponential backoff for rate limits
 */
function quotaAwareOperation(operation, maxRetries = 3) {
  let lastError;
  
  for (let attempt = 0; attempt < maxRetries; attempt++) {
    try {
      return operation();
    } catch (error) {
      lastError = error;
      
      // Check if it's a quota error
      if (error.message.includes('quota') || error.message.includes('limit')) {
        const backoffTime = Math.pow(2, attempt) * 1000; // Exponential backoff
        console.log(`⚠️ Rate limit hit, waiting ${backoffTime}ms...`);
        Utilities.sleep(backoffTime);
      } else {
        throw error; // Re-throw non-quota errors
      }
    }
  }
  
  throw new Error(`Operation failed after ${maxRetries} retries: ${lastError.message}`);
}
```

### Official Best Practices Summary

```javascript
/**
 * GOOGLE APPS SCRIPT BEST PRACTICES CHECKLIST
 * 
 * ✅ PERFORMANCE:
 * 1. Minimize calls to document services - cache references
 * 2. Use batch operations instead of individual updates
 * 3. Prefer built-in methods (replaceText) over manual iteration
 * 4. Cache expensive operations (getBody(), getNumChildren())
 * 5. Use indexed access instead of iterators when possible
 * 
 * ✅ EXECUTION TIME:
 * 1. Monitor 6-minute execution limit
 * 2. Process large datasets in chunks
 * 3. Implement checkpoints for resumable operations
 * 4. Use Utilities.sleep() sparingly
 * 
 * ✅ MEMORY MANAGEMENT:
 * 1. Clear caches periodically
 * 2. Avoid loading entire documents into memory
 * 3. Process elements incrementally
 * 4. Use Drive API for file operations
 * 
 * ✅ API QUOTAS:
 * 1. Implement exponential backoff for rate limits
 * 2. Batch requests when possible
 * 3. Cache results to reduce API calls
 * 4. Monitor daily quota usage
 * 
 * ✅ ERROR HANDLING:
 * 1. Use try-catch for all external operations
 * 2. Log errors with context
 * 3. Implement retry logic for transient failures
 * 4. Validate inputs before processing
 * 
 * ✅ CODE ORGANIZATION:
 * 1. Use classes for related functionality
 * 2. Implement single responsibility principle
 * 3. Document functions with JSDoc
 * 4. Use consistent naming conventions
 */

/**
 * Performance-optimized demo function
 */
function demonstrateOptimizedOperations() {
  try {
    console.log('⚡ Starting Optimized Operations Demo...');
    
    const startTime = Date.now();
    
    // Cache document reference
    const doc = DocumentApp.getActiveDocument();
    const body = doc.getBody();
    
    console.log('1. Creating content with batch operations...');
    
    // Batch text insertions
    PERFORMANCE_OPTIMIZER.batchOperations([
      (doc, body) => body.appendParagraph('Section 1'),
      (doc, body) => body.appendParagraph('Section 2'),
      (doc, body) => body.appendParagraph('Section 3')
    ]);
    
    console.log('2. Optimized table creation...');
    const tableData = [
      ['A', 'B', 'C'],
      ['1', '2', '3'],
      ['4', '5', '6']
    ];
    createOptimizedTable(doc, tableData, {
      headerStyle: { backgroundColor: '#4285f4', color: '#ffffff' },
      cellPadding: 8
    });
    
    console.log('3. Efficient find and replace...');
    const replacements = optimizedFindAndReplace(doc, 'Section', 'Chapter');
    console.log(`   Replaced ${replacements} instances`);
    
    const duration = Date.now() - startTime;
    console.log(`✅ Demo completed in ${duration}ms`);
    console.log('📊 Metrics:', PERFORMANCE_OPTIMIZER.getMetrics());
    
    return doc;
    
  } catch (error) {
    console.error('❌ Demo failed:', error.message);
    throw error;
  }
}
```

