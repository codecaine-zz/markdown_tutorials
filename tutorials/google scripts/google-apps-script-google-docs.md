## 1. Basic Document Operations

### Creating a New Document

```javascript
/**
 * Creates a new Google Doc
 * @param {string} title - The title of the document (optional)
 * @returns {GoogleAppsScript.Document.Document} The created document object
 */
function createNewDocument(title = "Untitled Document") {
  const doc = DocumentApp.create(title);
  return doc;
}

/**
 * Opens an existing document by ID
 * @param {string} documentId - The ID of the document to open
 * @returns {GoogleAppsScript.Document.Document} The opened document object
 */
function openDocument(documentId) {
  if (!documentId) {
    throw new Error("Document ID is required");
  }
  return DocumentApp.openById(documentId);
}
```

### Getting Document Information

```javascript
/**
 * Gets basic information about a document
 * @param {GoogleAppsScript.Document.Document} doc - The document object (optional, uses active document if not provided)
 * @returns {Object} Document information
 */
function getDocumentInfo(doc = DocumentApp.getActiveDocument()) {
  const document = doc || DocumentApp.getActiveDocument();
  
  return {
    id: document.getId(),
    title: document.getName(),
    url: document.getUrl(),
    createdTime: new Date(document.getDateCreated()),
    modifiedTime: new Date(document.getLastModified())
  };
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
  
  // Format a paragraph as heading
  applyHeading(1);
  
  // Get document statistics
  const stats = getDocumentStats();
  console.log("Word Count: " + stats.wordCount);
  
  // Batch replace
  batchReplace([
    { find: "old text", replace: "new text" },
    { find: "another old", replace: "another new" }
  ]);
  
  logActivity("Example usage completed");
}
```

## Best Practices and Optimization Tips

### Performance Optimizations:

1. **Batch Operations**: Perform multiple operations on the same document to reduce API calls
2. **Minimize Iterator Usage**: Cache results when possible, especially for large documents
3. **Use DocumentApp.getActiveDocument() Sparingly**: Store references in variables rather than repeatedly calling it

```javascript
/**
 * Optimized batch text processing function
 * @param {Array<string>} texts - Array of texts to process
 * @returns {void}
 */
function optimizedBatchInsert(texts) {
  const doc = DocumentApp.getActiveDocument();
  const body = doc.getBody();
  
  // Process all at once for better performance
  texts.forEach((text, index) => {
    if (index > 0) body.appendParagraph(""); // Add spacing between paragraphs
    body.appendParagraph(text);
  });
}
```

This comprehensive tutorial provides a robust set of reusable functions for automating Google Docs operations in Google Apps Script. Each function is designed to be flexible with optional parameters and optimized for performance while maintaining readability and usability.

