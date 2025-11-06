# Google Apps Script - Google Sheets Complete Tutorial
## From Beginner to Advanced with Reusable Code Examples

This comprehensive tutorial teaches you Google Sheets automation using Google Apps Script with clean, reusable code organized in classes and functions. Perfect for beginners to advanced users.

## 📚 Table of Contents

### 🟢 **BEGINNER LEVEL**
1. [Getting Started](#1-getting-started)
   - [Environment Setup](#environment-setup)
   - [Your First Script](#your-first-script)
   - [Understanding Google Apps Script](#understanding-google-apps-script)

1. [Basic Sheet Operations](#2-basic-sheet-operations)
   - [Opening and Creating Sheets](#opening-and-creating-sheets)
   - [Reading Single Cells](#reading-single-cells)
   - [Writing Simple Data](#writing-simple-data)

1. [Working with Ranges](#3-working-with-ranges)
   - [Understanding A1 Notation](#understanding-a1-notation)
   - [Reading Ranges of Data](#reading-ranges-of-data)
   - [Writing Data to Ranges](#writing-data-to-ranges)

### 🟡 **INTERMEDIATE LEVEL**
1. [Data Management](#4-data-management)
   - [Managing Multiple Sheets](#managing-multiple-sheets)
   - [Finding and Filtering Data](#finding-and-filtering-data)
   - [Sorting and Organizing](#sorting-and-organizing)

1. [Formatting and Styling](#5-formatting-and-styling)
   - [Cell Formatting](#cell-formatting)

### 🔴 **ADVANCED LEVEL**
1. [Complete Project Example: Employee Management System](#complete-project-example-employee-management-system)

### ⚡ **PERFORMANCE OPTIMIZATION**

1. [Performance Best Practices](#performance-best-practices)
   - [Batch Operations](#batch-operations)
   - [Caching Strategies](#caching-strategies)
   - [Avoiding Common Bottlenecks](#avoiding-common-bottlenecks)

### 🎯 **REAL-WORLD PROJECTS**

1. [Complete Project Example: Employee Management System](#complete-project-example-employee-management-system)

### ✅ **GUIDES AND REFERENCES**

1. [Best Practices and Error Handling](#11-best-practices-and-error-handling)

  - [Error Handling Patterns](#error-handling-patterns)

1. [Conclusion](#conclusion)

  - [Next Steps](#next-steps)
  - [Key Takeaways](#key-takeaways)

## 1. Getting Started

### Environment Setup

Before diving into automation, set up your Google Apps Script project.

### Your First Script

```javascript
/**
 * Your first Google Apps Script function
 * This function demonstrates basic spreadsheet interaction
 */
function helloWorld() {
  // Get the active spreadsheet
  const spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
  
  // Get the active sheet
  const sheet = spreadsheet.getActiveSheet();
  
  // Write "Hello, World!" to cell A1
  sheet.getRange('A1').setValue('Hello, World!');
  
  // Show a simple alert
  SpreadsheetApp.getUi().alert('Your first script ran successfully!');
  
  // Log information to the console
  console.log('Script executed successfully');
  console.log(`Spreadsheet name: ${spreadsheet.getName()}`);
  console.log(`Sheet name: ${sheet.getName()}`);
}
```

**To run this script:**

1. Click the "Run" button (▶️) in the toolbar
2. Grant permissions when prompted
3. Check your spreadsheet to see "Hello, World!" in cell A1

### Understanding Google Apps Script

Google Apps Script is a JavaScript-based platform that allows you to integrate with and automate tasks across Google products.

#### Key Concepts

1. **SpreadsheetApp**: The main class for working with Google Sheets
2. **Spreadsheet**: Represents an entire Google Sheets file
3. **Sheet**: Represents a single tab within a spreadsheet
4. **Range**: Represents a cell or group of cells

#### Core Configuration Class

Let's create a reusable configuration system:

```javascript
/**
 * SheetsConfig - Centralized configuration for all sheet operations
 * This class holds common settings and provides utility methods
 */
class SheetsConfig {
  constructor() {
    this.settings = {
      DEFAULT_SHEET_NAME: 'Data',
      DATE_FORMAT: 'yyyy-MM-dd',
      CURRENCY_FORMAT: '$#,##0.00',
      PERCENTAGE_FORMAT: '0.00%',
      DATETIME_FORMAT: 'yyyy-MM-dd HH:mm:ss',
      HEADER_BACKGROUND: '#4285f4',
      HEADER_TEXT_COLOR: 'white',
      ERROR_COLOR: '#ff0000',
      SUCCESS_COLOR: '#00ff00',
      // Performance settings
      BATCH_SIZE: 1000,
      CACHE_DURATION: 300, // 5 minutes in seconds
      MAX_RETRIES: 3
    };
    this.cache = CacheService.getScriptCache();
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
   * Initialize the script environment
   */
  initialize() {
    console.log('🚀 Initializing Google Sheets automation...');
    console.log(`📊 Active spreadsheet: ${SpreadsheetApp.getActiveSpreadsheet().getName()}`);
    console.log(`📋 Active sheet: ${SpreadsheetApp.getActiveSheet().getName()}`);
    console.log('✅ Environment setup complete');
  }

  /**
   * Get cached value or compute and cache
   * @param {string} key - Cache key
   * @param {Function} computeFn - Function to compute value if not cached
   * @param {number} ttl - Time to live in seconds
   * @return {*} Cached or computed value
   */
  getCached(key, computeFn, ttl = null) {
    const cached = this.cache.get(key);
    if (cached !== null) {
      return JSON.parse(cached);
    }
    
    const value = computeFn();
    const cacheTtl = ttl || this.get('CACHE_DURATION');
    this.cache.put(key, JSON.stringify(value), cacheTtl);
    return value;
  }

  /**
   * Clear specific cache key or all cache
   * @param {string} key - Optional: specific key to clear
   */
  clearCache(key = null) {
    if (key) {
      this.cache.remove(key);
    } else {
      this.cache.removeAll();
    }
  }
}

// Create a global configuration instance
const CONFIG = new SheetsConfig();

/**
 * Example: How to use the configuration
 */
function demonstrateConfig() {
  // Initialize the environment
  CONFIG.initialize();
  
  // Use configuration values
  const defaultSheetName = CONFIG.get('DEFAULT_SHEET_NAME');
  const dateFormat = CONFIG.get('DATE_FORMAT');
  
  console.log(`Using default sheet: ${defaultSheetName}`);
  console.log(`Date format: ${dateFormat}`);
  
  // Modify configuration if needed
  CONFIG.set('DEFAULT_SHEET_NAME', 'MyCustomSheet');
  
  console.log('Configuration updated successfully');
}
```

## 2. Basic Sheet Operations

### Opening and Creating Sheets

Understanding how to access and create sheets is fundamental. Let's build a comprehensive Sheet Manager class:

```javascript
/**
 * SheetManager - A comprehensive class for basic sheet operations
 * This class provides methods for accessing, creating, and managing sheets
 * OPTIMIZED FOR PERFORMANCE with caching and batch operations
 */
class SheetManager {
  constructor(spreadsheetId = null) {
    this.spreadsheet = spreadsheetId 
      ? SpreadsheetApp.openById(spreadsheetId)
      : SpreadsheetApp.getActiveSpreadsheet();
    this.sheetCache = new Map();
    this.cache = CacheService.getScriptCache();
  }

  /**
   * Get a sheet by name, with caching for performance
   * @param {string} sheetName - Name of the sheet
   * @return {GoogleAppsScript.Spreadsheet.Sheet}
   */
  getSheet(sheetName) {
    try {
      // Check memory cache first (fastest)
      if (this.sheetCache.has(sheetName)) {
        return this.sheetCache.get(sheetName);
      }

      // Get sheet from spreadsheet
      const sheet = this.spreadsheet.getSheetByName(sheetName);
      if (!sheet) {
        throw new Error(`Sheet '${sheetName}' does not exist`);
      }
      
      // Cache the sheet object
      this.sheetCache.set(sheetName, sheet);
      console.log(`📋 Accessed and cached sheet: ${sheetName}`);
      return sheet;
    } catch (error) {
      throw new Error(`❌ Failed to get sheet '${sheetName}': ${error.message}`);
    }
  }

  /**
   * Get a spreadsheet (current or by ID)
   * @param {string} spreadsheetId - Optional: Specific spreadsheet ID
   * @return {GoogleAppsScript.Spreadsheet.Spreadsheet}
   */
  getSpreadsheet(spreadsheetId = null) {
    try {
      if (spreadsheetId && spreadsheetId !== this.spreadsheet.getId()) {
        return SpreadsheetApp.openById(spreadsheetId);
      }
      return this.spreadsheet;
    } catch (error) {
      throw new Error(`❌ Failed to access spreadsheet: ${error.message}`);
    }
  }

  /**
   * Create a new sheet with optional configuration
   * @param {string} sheetName - Name for the new sheet
   * @param {Object} options - Optional configuration
   * @return {GoogleAppsScript.Spreadsheet.Sheet}
   */
  createSheet(sheetName, options = {}) {
    try {
      // Check if sheet already exists
      if (this.sheetExists(sheetName)) {
        if (options.overwrite) {
          this.deleteSheet(sheetName);
          console.log(`🗑️ Deleted existing sheet: ${sheetName}`);
        } else {
          throw new Error(`Sheet '${sheetName}' already exists`);
        }
      }

      // Create the new sheet
      const sheet = this.spreadsheet.insertSheet(sheetName);

      // Apply optional configurations
      if (options.tabColor) {
        sheet.setTabColor(options.tabColor);
      }

      if (options.headers && Array.isArray(options.headers)) {
        this.addHeaders(sheet, options.headers);
      }

      if (options.rowCount && options.rowCount !== 1000) {
        sheet.insertRows(1, options.rowCount - sheet.getMaxRows());
      }

      if (options.columnCount && options.columnCount !== 26) {
        sheet.insertColumns(1, options.columnCount - sheet.getMaxColumns());
      }

      console.log(`✅ Created sheet: ${sheetName}`);
      return sheet;

    } catch (error) {
      throw new Error(`❌ Failed to create sheet '${sheetName}': ${error.message}`);
    }
  }

  /**
   * Check if a sheet exists
   * @param {string} sheetName - Name of the sheet to check
   * @return {boolean}
   */
  sheetExists(sheetName) {
    return this.spreadsheet.getSheetByName(sheetName) !== null;
  }

  /**
   * Delete a sheet by name
   * @param {string} sheetName - Name of sheet to delete
   */
  deleteSheet(sheetName) {
    try {
      const sheet = this.getSheet(sheetName);
      this.spreadsheet.deleteSheet(sheet);
      console.log(`🗑️ Deleted sheet: ${sheetName}`);
    } catch (error) {
      throw new Error(`❌ Failed to delete sheet '${sheetName}': ${error.message}`);
    }
  }

  /**
   * Add formatted headers to a sheet
   * @param {GoogleAppsScript.Spreadsheet.Sheet} sheet - Target sheet
   * @param {Array} headers - Array of header names
   */
  addHeaders(sheet, headers) {
    if (!headers || !Array.isArray(headers) || headers.length === 0) {
      throw new Error('Headers must be a non-empty array');
    }

    // Set header values
    const headerRange = sheet.getRange(1, 1, 1, headers.length);
    headerRange.setValues([headers]);

    // Format headers
    headerRange
      .setFontWeight('bold')
      .setBackground(CONFIG.get('HEADER_BACKGROUND'))
      .setFontColor(CONFIG.get('HEADER_TEXT_COLOR'))
      .setHorizontalAlignment('center')
      .setBorder(true, true, true, true, false, false);

    // Freeze header row
    sheet.setFrozenRows(1);

    console.log(`📋 Added ${headers.length} headers to sheet`);
  }

  /**
   * Get information about all sheets (cached for performance)
   * @return {Array} Array of sheet information objects
   */
  getAllSheetsInfo() {
    try {
      const cacheKey = `sheetsInfo_${this.spreadsheet.getId()}`;
      
      return CONFIG.getCached(cacheKey, () => {
        const sheets = this.spreadsheet.getSheets();
        return sheets.map(sheet => ({
          name: sheet.getName(),
          id: sheet.getSheetId(),
          index: sheet.getIndex(),
          lastRow: sheet.getLastRow(),
          lastColumn: sheet.getLastColumn(),
          maxRows: sheet.getMaxRows(),
          maxColumns: sheet.getMaxColumns(),
          tabColor: sheet.getTabColor()
        }));
      }, 60); // Cache for 1 minute
    } catch (error) {
      throw new Error(`❌ Failed to get sheets info: ${error.message}`);
    }
  }

  /**
   * Clear the sheet cache (call after sheet operations)
   */
  clearCache() {
    this.sheetCache.clear();
    CONFIG.clearCache(`sheetsInfo_${this.spreadsheet.getId()}`);
    console.log('🧹 Cleared sheet cache');
  }
}
```

#### SheetManager: Quick usage examples

```javascript
// Minimal, copy-pasteable examples
function sheetManagerExamples() {
  const sm = new SheetManager();

  // 1) Get the active sheet and spreadsheet
  const ss = sm.getSpreadsheet();
  const active = sm.getActiveSheet();
  console.log('Active sheet:', active.getName());

  // 2) Create or overwrite a sheet with headers
  const sheet = sm.createSheet('Customers', {
    headers: ['ID', 'Name', 'Email', 'Created'],
    tabColor: '#3F51B5',
    overwrite: true
  });

  // 3) Check if a sheet exists
  console.log('Has Customers?', sm.sheetExists('Customers'));

  // 4) Add headers (if you didn’t set via options)
  sm.addHeaders(sheet, ['ID', 'Name', 'Email', 'Created']);

  // 5) Inspect all sheets
  const info = sm.getAllSheetsInfo();
  info.forEach(i => console.log(`${i.name} -> rows:${i.lastRow}, cols:${i.lastColumn}`));

  // 6) Delete a sheet (careful!)
  // sm.deleteSheet('Old_Sheet');
}
```

### Reading Single Cells

Let's start with the most basic operation - reading data from individual cells:

```javascript
/**
 * CellReader - Simple class for reading single cell values
 * Perfect for beginners to understand basic operations
 */
class CellReader {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Read a value from a single cell
   * @param {string} cellAddress - Cell address (e.g., 'A1', 'B5')
   * @param {string} sheetName - Optional: Sheet name (uses active if not provided)
   * @return {*} The value in the cell
   */
  readCell(cellAddress, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const value = sheet.getRange(cellAddress).getValue();
      
      console.log(`📖 Read cell ${cellAddress}: ${value}`);
      return value;

    } catch (error) {
      throw new Error(`❌ Failed to read cell ${cellAddress}: ${error.message}`);
    }
  }

  /**
   * Read multiple single cells at once
   * @param {Array} cellAddresses - Array of cell addresses ['A1', 'B2', 'C3']
   * @param {string} sheetName - Optional: Sheet name
   * @return {Object} Object with cell addresses as keys and values as values
   */
  readCells(cellAddresses, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const results = {};
      
      cellAddresses.forEach(address => {
        results[address] = sheet.getRange(address).getValue();
      });

      console.log(`📖 Read ${cellAddresses.length} cells`);
      return results;

    } catch (error) {
      throw new Error(`❌ Failed to read cells: ${error.message}`);
    }
  }

  /**
   * Check if a cell is empty
   * @param {string} cellAddress - Cell address to check
   * @param {string} sheetName - Optional: Sheet name
   * @return {boolean} True if cell is empty
   */
  isCellEmpty(cellAddress, sheetName = null) {
    try {
      const value = this.readCell(cellAddress, sheetName);
      return value === '' || value === null || value === undefined;
    } catch (error) {
      throw new Error(`❌ Failed to check if cell is empty: ${error.message}`);
    }
  }

  /**
   * Get the data type of a cell's content
   * @param {string} cellAddress - Cell address
   * @param {string} sheetName - Optional: Sheet name
   * @return {string} Data type ('string', 'number', 'date', 'boolean', 'empty')
   */
  getCellType(cellAddress, sheetName = null) {
    try {
      const value = this.readCell(cellAddress, sheetName);
      
      if (value === '' || value === null || value === undefined) {
        return 'empty';
      } else if (typeof value === 'number') {
        return 'number';
      } else if (value instanceof Date) {
        return 'date';
      } else if (typeof value === 'boolean') {
        return 'boolean';
      } else {
        return 'string';
      }
    } catch (error) {
      throw new Error(`❌ Failed to determine cell type: ${error.message}`);
    }
  }
}
```

#### CellReader: Quick usage examples

```javascript
function cellReaderExamples() {
  const sm = new SheetManager();
  const reader = new CellReader(sm);

  // Read single cell (active sheet)
  const a1 = reader.readCell('A1');
  console.log('A1:', a1);

  // Read multiple cells
  const many = reader.readCells(['A1', 'B2', 'C3']);
  console.log('Many:', many);

  // Empty check
  console.log('C3 empty?', reader.isCellEmpty('C3'));

  // Type detection
  console.log('Type of B2:', reader.getCellType('B2'));
}
```

### Writing Simple Data

Now let's learn how to write data to individual cells:

```javascript
/**
 * CellWriter - Simple class for writing data to single cells
 * Includes validation and error handling for beginners
 */
class CellWriter {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Write a value to a single cell
   * @param {*} value - Value to write
   * @param {string} cellAddress - Cell address (e.g., 'A1', 'B5')
   * @param {string} sheetName - Optional: Sheet name
   */
  writeCell(value, cellAddress, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      sheet.getRange(cellAddress).setValue(value);
      
      console.log(`✏️ Wrote to cell ${cellAddress}: ${value}`);

    } catch (error) {
      throw new Error(`❌ Failed to write to cell ${cellAddress}: ${error.message}`);
    }
  }

  /**
   * Write values to multiple single cells
   * @param {Object} cellData - Object with cell addresses as keys and values as values
   * @param {string} sheetName - Optional: Sheet name
   */
  writeCells(cellData, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      Object.entries(cellData).forEach(([address, value]) => {
        sheet.getRange(address).setValue(value);
      });

      const cellCount = Object.keys(cellData).length;
      console.log(`✏️ Wrote to ${cellCount} cells`);

    } catch (error) {
      throw new Error(`❌ Failed to write to cells: ${error.message}`);
    }
  }

  /**
   * Write a formula to a cell
   * @param {string} formula - Formula to write (e.g., '=SUM(A1:A10)')
   * @param {string} cellAddress - Cell address
   * @param {string} sheetName - Optional: Sheet name
   */
  writeFormula(formula, cellAddress, sheetName = null) {
    try {
      // Ensure formula starts with =
      if (!formula.startsWith('=')) {
        formula = '=' + formula;
      }

      this.writeCell(formula, cellAddress, sheetName);
      console.log(`📊 Wrote formula to ${cellAddress}: ${formula}`);

    } catch (error) {
      throw new Error(`❌ Failed to write formula: ${error.message}`);
    }
  }

  /**
   * Clear content from a cell
   * @param {string} cellAddress - Cell address to clear
   * @param {string} sheetName - Optional: Sheet name
   */
  clearCell(cellAddress, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      sheet.getRange(cellAddress).clear();
      console.log(`🧹 Cleared cell ${cellAddress}`);

    } catch (error) {
      throw new Error(`❌ Failed to clear cell: ${error.message}`);
    }
  }
}
```

#### CellWriter: Quick usage examples

```javascript
function cellWriterExamples() {
  const sm = new SheetManager();
  const writer = new CellWriter(sm);

  // Write one cell
  writer.writeCell('Hello', 'A1');

  // Write many cells
  writer.writeCells({ A2: 'Alice', B2: 32, C2: 'NYC' });

  // Write a formula
  writer.writeFormula('SUM(B2:B10)', 'B11');

  // Clear a cell
  writer.clearCell('C2');
}
```

### Practical Example: Basic Operations Demo

Here's a complete example that demonstrates all basic operations:

```javascript
/**
 * Demonstration of basic sheet operations
 * Run this function to see all basic operations in action
 */
function basicOperationsDemo() {
  try {
    console.log('🚀 Starting Basic Operations Demo');

    // Step 1: Initialize our classes
    const sheetManager = new SheetManager();
    const cellReader = new CellReader(sheetManager);
    const cellWriter = new CellWriter(sheetManager);

    // Step 2: Create a demo sheet
    const demoSheet = sheetManager.createSheet('Basic_Operations_Demo', {
      headers: ['Name', 'Age', 'City', 'Salary'],
      tabColor: '#4CAF50',
      overwrite: true
    });

    console.log('📋 Created demo sheet with headers');

    // Step 3: Write some sample data
    const sampleData = {
      'A2': 'John Doe',
      'B2': 30,
      'C2': 'New York',
      'D2': 75000,
      'A3': 'Jane Smith', 
      'B3': 28,
      'C3': 'San Francisco',
      'D3': 85000
    };

    cellWriter.writeCells(sampleData);
    console.log('✏️ Added sample employee data');

    // Step 4: Add some formulas
    cellWriter.writeFormula('AVERAGE(B2:B3)', 'B4');
    cellWriter.writeFormula('SUM(D2:D3)', 'D4');
    cellWriter.writeCell('Averages:', 'A4');

    console.log('📊 Added calculation formulas');

    // Step 5: Read back some data
    const johnAge = cellReader.readCell('B2');
    const totalSalary = cellReader.readCell('D4');
    const averageAge = cellReader.readCell('B4');

    console.log(`👤 John's age: ${johnAge}`);
    console.log(`💰 Total salary: $${totalSalary}`);
    console.log(`📊 Average age: ${averageAge}`);

    // Step 6: Check data types
    console.log('🔍 Data type analysis:');
    console.log(`Name column type: ${cellReader.getCellType('A2')}`);
    console.log(`Age column type: ${cellReader.getCellType('B2')}`);
    console.log(`Salary column type: ${cellReader.getCellType('D2')}`);

    // Step 7: Display sheet information
    const sheetsInfo = sheetManager.getAllSheetsInfo();
    console.log('📊 Spreadsheet contains these sheets:');
    sheetsInfo.forEach(info => {
      console.log(`  - ${info.name}: ${info.lastRow} rows, ${info.lastColumn} columns`);
    });

    console.log('✅ Basic Operations Demo completed successfully!');
    
    // Show success message to user
    SpreadsheetApp.getUi().alert(
      'Demo Complete!', 
      'Check the console logs and the new "Basic_Operations_Demo" sheet to see the results.',
      SpreadsheetApp.getUi().ButtonSet.OK
    );

  } catch (error) {
    console.error('❌ Demo failed:', error.message);
    SpreadsheetApp.getUi().alert('Demo Error', error.message, SpreadsheetApp.getUi().ButtonSet.OK);
  }
}
```

## 3. Working with Ranges

### Understanding A1 Notation

A1 notation is how Google Sheets identifies cells and ranges. It's essential to understand this before working with ranges.

#### A1 Notation Examples

- `A1` - Single cell in column A, row 1
- `A1:C3` - Rectangle from A1 to C3 (3x3 cells)
- `A:A` - Entire column A
- `1:1` - Entire row 1
- `A1:A` - Column A starting from A1
- `A1:10` - Row 1 from column A to column 10

```javascript
/**
 * RangeHelper - Utility class for working with A1 notation and range operations
 * This class helps beginners understand and work with ranges effectively
 */
class RangeHelper {
  constructor() {}

  /**
   * Convert row and column numbers to A1 notation
   * @param {number} row - Row number (1-based)
   * @param {number} col - Column number (1-based)
   * @return {string} A1 notation (e.g., "B5")
   */
  static rowColToA1(row, col) {
    const columnLetter = this.numberToColumnLetter(col);
    return `${columnLetter}${row}`;
  }

  /**
   * Convert A1 notation to row and column numbers
   * @param {string} a1Notation - A1 notation (e.g., "B5")
   * @return {Object} Object with row and col properties
   */
  static a1ToRowCol(a1Notation) {
    const match = a1Notation.match(/^([A-Z]+)(\d+)$/);
    if (!match) {
      throw new Error(`Invalid A1 notation: ${a1Notation}`);
    }
    
    const columnLetter = match[1];
    const row = parseInt(match[2]);
    const col = this.columnLetterToNumber(columnLetter);
    
    return { row, col };
  }

  /**
   * Convert column number to letter (1=A, 2=B, 27=AA...)
   * @param {number} num - Column number (1-based)
   * @return {string} Column letter(s)
   */
  static numberToColumnLetter(num) {
    let result = '';
    while (num > 0) {
      num--;
      result = String.fromCharCode(num % 26 + 'A'.charCodeAt(0)) + result;
      num = Math.floor(num / 26);
    }
    return result;
  }

  /**
   * Convert column letter to number (A=1, B=2, AA=27...)
   * @param {string} letters - Column letter(s)
   * @return {number} Column number (1-based)
   */
  static columnLetterToNumber(letters) {
    let result = 0;
    for (let i = 0; i < letters.length; i++) {
      result = result * 26 + (letters.charCodeAt(i) - 'A'.charCodeAt(0) + 1);
    }
    return result;
  }

  /**
   * Create a range string from coordinates
   * @param {number} startRow - Starting row (1-based)
   * @param {number} startCol - Starting column (1-based) 
   * @param {number} numRows - Number of rows
   * @param {number} numCols - Number of columns
   * @return {string} Range in A1 notation
   */
  static createRange(startRow, startCol, numRows, numCols) {
    const startA1 = this.rowColToA1(startRow, startCol);
    const endA1 = this.rowColToA1(startRow + numRows - 1, startCol + numCols - 1);
    return `${startA1}:${endA1}`;
  }

  /**
   * Validate A1 notation format
   * @param {string} range - Range to validate
   * @return {boolean} True if valid
   */
  static isValidRange(range) {
    // Simple validation regex for A1 notation
    const singleCell = /^[A-Z]+\d+$/;
    const rangePattern = /^[A-Z]+\d+:[A-Z]+\d+$/;
    const columnPattern = /^[A-Z]+:[A-Z]+$/;
    const rowPattern = /^\d+:\d+$/;
    
    return singleCell.test(range) || 
           rangePattern.test(range) || 
           columnPattern.test(range) || 
           rowPattern.test(range);
  }
}
```

#### RangeHelper: Quick usage examples

```javascript
function rangeHelperExamples() {
  // Convert row/col to A1
  const a1 = RangeHelper.rowColToA1(5, 3); // C5
  console.log('row/col -> A1:', a1);

  // Convert A1 to row/col
  const rc = RangeHelper.a1ToRowCol('AB7');
  console.log('A1 -> row/col:', rc.row, rc.col);

  // Column conversions
  console.log('28 ->', RangeHelper.numberToColumnLetter(28)); // AB
  console.log('AB ->', RangeHelper.columnLetterToNumber('AB')); // 28

  // Build a range and validate
  const range = RangeHelper.createRange(2, 1, 10, 5); // A2:E11
  console.log('Range:', range, 'valid?', RangeHelper.isValidRange(range));
}
```

### Reading Ranges of Data

Now let's learn how to read multiple cells at once using ranges:

```javascript
/**
 * RangeReader - Class for reading data from ranges efficiently
 * OPTIMIZED: Uses batch operations and caching
 */
class RangeReader {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
    this.cache = CacheService.getScriptCache();
  }

  /**
   * Read data from a range with comprehensive error handling
   * OPTIMIZED: Single API call per range
   * @param {string} range - Range in A1 notation (e.g., "A1:C10")
   * @param {string} sheetName - Optional: Sheet name
   * @return {Array} 2D array of cell values
   */
  readRange(range, sheetName = null) {
    try {
      if (!RangeHelper.isValidRange(range)) {
        throw new Error(`Invalid range format: ${range}`);
      }

      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Single API call - most efficient
      const rangeObj = sheet.getRange(range);
      const values = rangeObj.getValues();
      
      console.log(`📖 Read range ${range}: ${values.length} rows x ${values[0]?.length || 0} columns`);
      return values;

    } catch (error) {
      throw new Error(`❌ Failed to read range ${range}: ${error.message}`);
    }
  }

  /**
   * Read all data from a sheet (excluding empty trailing rows/columns)
   * OPTIMIZED: Uses getDataRange() for best performance
   * @param {string} sheetName - Optional: Sheet name
   * @param {boolean} includeHeaders - Include header row (default: true)
   * @return {Array} 2D array of all data
   */
  readAllData(sheetName = null, includeHeaders = true) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Most efficient way to get all data - single API call
      const dataRange = sheet.getDataRange();
      if (dataRange.getNumRows() === 0) {
        console.log('📖 Sheet is empty');
        return [];
      }

      const data = dataRange.getValues();
      
      if (!includeHeaders && data.length > 0) {
        return data.slice(1);
      }

      return data;

    } catch (error) {
      throw new Error(`❌ Failed to read all data: ${error.message}`);
    }
  }

  /**
   * Read multiple ranges at once (batch operation)
   * OPTIMIZED: Single batch API call instead of multiple calls
   * @param {Array} ranges - Array of range strings
   * @param {string} sheetName - Optional: Sheet name
   * @return {Array} Array of range data arrays
   */
  readMultipleRanges(ranges, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Batch operation - much faster than individual calls
      const rangeObjects = ranges.map(range => {
        if (!RangeHelper.isValidRange(range)) {
          throw new Error(`Invalid range format: ${range}`);
        }
        return sheet.getRange(range);
      });

      // Single batch call to get all values
      const results = rangeObjects.map(rangeObj => rangeObj.getValues());

      console.log(`📖 Read ${ranges.length} ranges in batch operation`);
      return results;

    } catch (error) {
      throw new Error(`❌ Failed to read multiple ranges: ${error.message}`);
    }
  }

  /**
   * Read a specific column of data
   * @param {string|number} column - Column letter (A, B, C...) or number (1, 2, 3...)
   * @param {string} sheetName - Optional: Sheet name
   * @param {number} startRow - Starting row (default: 1)
   * @param {number} endRow - Ending row (default: last row with data)
   * @return {Array} Array of values from the column
   */
  readColumn(column, sheetName = null, startRow = 1, endRow = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Convert column to number if it's a letter
      const columnNum = typeof column === 'string' 
        ? RangeHelper.columnLetterToNumber(column)
        : column;

      const finalEndRow = endRow || sheet.getLastRow();
      
      if (startRow > finalEndRow) {
        console.log('📖 Start row is beyond data range');
        return [];
      }

      const range = RangeHelper.createRange(startRow, columnNum, finalEndRow - startRow + 1, 1);
      const data = this.readRange(range, sheetName);
      
      // Convert 2D array to 1D array for single column
      return data.map(row => row[0]);

    } catch (error) {
      throw new Error(`❌ Failed to read column ${column}: ${error.message}`);
    }
  }

  /**
   * Read a specific row of data
   * @param {number} row - Row number (1-based)
   * @param {string} sheetName - Optional: Sheet name
   * @param {number} startCol - Starting column (default: 1)
   * @param {number} endCol - Ending column (default: last column with data)
   * @return {Array} Array of values from the row
   */
  readRow(row, sheetName = null, startCol = 1, endCol = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const finalEndCol = endCol || sheet.getLastColumn();
      
      if (startCol > finalEndCol) {
        console.log('📖 Start column is beyond data range');
        return [];
      }

      const range = RangeHelper.createRange(row, startCol, 1, finalEndCol - startCol + 1);
      const data = this.readRange(range, sheetName);
      
      // Return the first (and only) row
      return data[0] || [];

    } catch (error) {
      throw new Error(`❌ Failed to read row ${row}: ${error.message}`);
    }
  }

  /**
   * Read data and convert to objects using headers
   * @param {string} sheetName - Optional: Sheet name
   * @param {number} headerRow - Row containing headers (default: 1)
   * @return {Array} Array of objects with header names as keys
   */
  readAsObjects(sheetName = null, headerRow = 1) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Get headers
      const headers = this.readRow(headerRow, sheetName);
      
      if (headers.length === 0) {
        throw new Error('No headers found');
      }

      // Get data rows
      const dataStartRow = headerRow + 1;
      const lastRow = sheet.getLastRow();
      
      if (dataStartRow > lastRow) {
        console.log('📖 No data rows found');
        return [];
      }

      const dataRange = RangeHelper.createRange(dataStartRow, 1, lastRow - headerRow, headers.length);
      const dataRows = this.readRange(dataRange, sheetName);

      // Convert to objects
      return dataRows.map(row => {
        const obj = {};
        headers.forEach((header, index) => {
          obj[header] = row[index];
        });
        return obj;
      });

    } catch (error) {
      throw new Error(`❌ Failed to read as objects: ${error.message}`);
    }
  }

  /**
   * Read multiple ranges at once (batch operation)
   * OPTIMIZED: Single batch API call instead of multiple calls
   * @param {Array} ranges - Array of range strings
   * @param {string} sheetName - Optional: Sheet name
   * @return {Array} Array of range data arrays
   */
  readMultipleRanges(ranges, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Batch operation - much faster than individual calls
      const rangeObjects = ranges.map(range => {
        if (!RangeHelper.isValidRange(range)) {
          throw new Error(`Invalid range format: ${range}`);
        }
        return sheet.getRange(range);
      });

      // Single batch call to get all values
      const results = rangeObjects.map(rangeObj => rangeObj.getValues());

      console.log(`📖 Read ${ranges.length} ranges in batch operation`);
      return results;

    } catch (error) {
      throw new Error(`❌ Failed to read multiple ranges: ${error.message}`);
    }
  }
}
```

### Writing Data to Ranges

Let's learn how to write data to multiple cells efficiently:

```javascript
/**
 * RangeWriter - Class for writing data to ranges efficiently
 * OPTIMIZED: Uses batch operations and minimal API calls
 */
class RangeWriter {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Write data to a range with validation
   * OPTIMIZED: Single setValues() call
   * @param {Array} data - 2D array of values to write
   * @param {string} range - Range in A1 notation
   * @param {string} sheetName - Optional: Sheet name
   */
  writeRange(data, range, sheetName = null) {
    try {
      if (!Array.isArray(data)) {
        throw new Error('Data must be an array');
      }

      // Ensure data is 2D array
      if (data.length > 0 && !Array.isArray(data[0])) {
        data = [data];
      }

      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Single API call - most efficient
      const rangeObj = sheet.getRange(range);
      rangeObj.setValues(data);

      console.log(`✏️ Wrote ${data.length} rows to range ${range}`);

    } catch (error) {
      throw new Error(`❌ Failed to write range ${range}: ${error.message}`);
    }
  }

  /**
   * Write data starting at a specific cell (auto-sizing the range)
   * @param {Array} data - 2D array of values
   * @param {string} startCell - Starting cell (e.g., "A1")
   * @param {string} sheetName - Optional: Sheet name
   */
  writeFromCell(data, startCell, sheetName = null) {
    try {
      if (!Array.isArray(data) || data.length === 0) {
        throw new Error('Data must be a non-empty array');
      }

      // Ensure 2D array
      if (!Array.isArray(data[0])) {
        data = [data];
      }

      const numRows = data.length;
      const numCols = Math.max(...data.map(row => row.length));

      // Parse starting cell
      const { row, col } = RangeHelper.a1ToRowCol(startCell);
      
      // Create the range
      const range = RangeHelper.createRange(row, col, numRows, numCols);
      
      // Pad rows to ensure consistent column count
      const paddedData = data.map(row => {
        while (row.length < numCols) {
          row.push('');
        }
        return row;
      });

      this.writeRange(paddedData, range, sheetName);

    } catch (error) {
      throw new Error(`❌ Failed to write from cell ${startCell}: ${error.message}`);
    }
  }

  /**
   * Append data to the bottom of existing data
   * @param {Array} data - Data to append (1D or 2D array)
   * @param {string} sheetName - Optional: Sheet name
   * @param {boolean} includeTimestamp - Add timestamp column (default: false)
   * @return {number} Row number where data was inserted
   */
  appendData(data, sheetName = null, includeTimestamp = false) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      // Ensure data is 2D array
      if (!Array.isArray(data[0])) {
        data = [data];
      }

      // Add timestamp if requested
      if (includeTimestamp) {
        data = data.map(row => [...row, new Date()]);
      }

      const lastRow = sheet.getLastRow();
      const startRow = lastRow + 1;

      const startCell = RangeHelper.rowColToA1(startRow, 1);
      this.writeFromCell(data, startCell, sheetName);

      console.log(`✏️ Appended ${data.length} rows starting at row ${startRow}`);
      return startRow;

    } catch (error) {
      throw new Error(`❌ Failed to append data: ${error.message}`);
    }
  }

  /**
   * Write objects to sheet using headers
   * @param {Array} objects - Array of objects to write
   * @param {string} sheetName - Optional: Sheet name
   * @param {boolean} writeHeaders - Write headers first (default: true)
   * @param {string} startCell - Starting cell (default: "A1")
   */
  writeObjects(objects, sheetName = null, writeHeaders = true, startCell = "A1") {
    try {
      if (!Array.isArray(objects) || objects.length === 0) {
        throw new Error('Objects must be a non-empty array');
      }

      // Extract headers from first object
      const headers = Object.keys(objects[0]);
      
      // Convert objects to 2D array
      const dataRows = objects.map(obj => 
        headers.map(header => obj[header] || '')
      );

      let finalData = dataRows;
      if (writeHeaders) {
        finalData = [headers, ...dataRows];
      }

      this.writeFromCell(finalData, startCell, sheetName);

      const rowsWritten = finalData.length;
      console.log(`✏️ Wrote ${objects.length} objects (${rowsWritten} total rows including headers)`);

    } catch (error) {
      throw new Error(`❌ Failed to write objects: ${error.message}`);
    }
  }

  /**
   * Clear data from a range
   * @param {string} range - Range to clear
   * @param {string} sheetName - Optional: Sheet name
   * @param {boolean} contentOnly - Clear content only, keep formatting (default: false)
   */
  clearRange(range, sheetName = null, contentOnly = false) {
    try {
      if (!RangeHelper.isValidRange(range)) {
        throw new Error(`Invalid range format: ${range}`);
      }

      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const rangeObj = sheet.getRange(range);
      
      if (contentOnly) {
        rangeObj.clearContent();
      } else {
        rangeObj.clear();
      }

      console.log(`🧹 Cleared range ${range}${contentOnly ? ' (content only)' : ''}`);

    } catch (error) {
      throw new Error(`❌ Failed to clear range ${range}: ${error.message}`);
    }
  }

  /**
   * Write formulas to a range
   * @param {Array} formulas - 2D array of formulas
   * @param {string} range - Range to write formulas to
   * @param {string} sheetName - Optional: Sheet name
   */
  writeFormulas(formulas, range, sheetName = null) {
    try {
      // Ensure formulas start with =
      const processedFormulas = formulas.map(row => {
        if (!Array.isArray(row)) {
          row = [row];
        }
        return row.map(formula => {
          if (typeof formula === 'string' && formula.trim() !== '' && !formula.startsWith('=')) {
            return '=' + formula;
          }
          return formula;
        });
      });

      this.writeRange(processedFormulas, range, sheetName);
      console.log(`📊 Wrote formulas to range ${range}`);

    } catch (error) {
      throw new Error(`❌ Failed to write formulas: ${error.message}`);
    }
  }
}
```

#### RangeWriter: Quick usage examples

```javascript
function rangeWriterExamples() {
  const sm = new SheetManager();
  const writer = new RangeWriter(sm);

  // Write an exact range
  writer.writeRange([
    ['Product', 'Q1', 'Q2'],
    ['Widget A', 120, 140],
    ['Widget B', 80, 95]
  ], 'A1:C3');

  // Write starting at a cell (auto-sized)
  writer.writeFromCell([
    ['Name', 'Age'],
    ['Alice', 30],
    ['Bob', 28]
  ], 'E1');

  // Append rows (with timestamp)
  writer.appendData([
    ['Carol', 26],
    ['David', 33]
  ], null, true);

  // Write objects (headers auto)
  writer.writeObjects([
    { id: 1, name: 'Alice', city: 'NYC' },
    { id: 2, name: 'Bob', city: 'SF' }
  ], null, true, 'H1');

  // Clear a range (content only)
  writer.clearRange('B10:D20', null, true);

  // Write formulas across a range
  writer.writeFormulas([
    ['=SUM(B2:B5)'],
    ['=AVERAGE(B2:B5)']
  ], 'C2:C3');
}
```

### Practical Example: Working with Ranges Demo

Here's a comprehensive example that demonstrates all range operations:

```javascript
/**
 * Comprehensive demonstration of range operations
 * This example shows reading and writing ranges in various ways
 */
function rangeOperationsDemo() {
  try {
    console.log('🚀 Starting Range Operations Demo');

    // Initialize our classes
    const sheetManager = new SheetManager();
    const rangeReader = new RangeReader(sheetManager);
    const rangeWriter = new RangeWriter(sheetManager);

    // Create a demo sheet
    const demoSheet = sheetManager.createSheet('Range_Operations_Demo', {
      tabColor: '#FF9800',
      overwrite: true
    });

    console.log('📋 Created demo sheet');

    // Example 1: Write structured data using ranges
    const salesData = [
      ['Product', 'Q1 Sales', 'Q2 Sales', 'Q3 Sales', 'Q4 Sales'],
      ['Widget A', 1200, 1350, 1400, 1600],
      ['Widget B', 800, 900, 950, 1100],
      ['Widget C', 1500, 1600, 1700, 1800],
      ['Widget D', 600, 650, 700, 750]
    ];

    rangeWriter.writeRange(salesData, 'A1:E5', 'Range_Operations_Demo');
    console.log('✏️ Wrote sales data using range notation');

    // Example 2: Read back the data in different ways
    const allData = rangeReader.readAllData('Range_Operations_Demo');
    console.log(`📖 Read all data: ${allData.length} rows`);

    const headers = rangeReader.readRow(1, 'Range_Operations_Demo');
    console.log(`📖 Headers: ${headers.join(', ')}`);

    const productColumn = rangeReader.readColumn('A', 'Range_Operations_Demo', 2); // Skip header
    console.log(`📖 Products: ${productColumn.join(', ')}`);

    // Example 3: Add calculated columns using formulas
    const formulas = [
      ['Total Sales', 'Average'],
      ['=SUM(B2:E2)', '=AVERAGE(B2:E2)'],
      ['=SUM(B3:E3)', '=AVERAGE(B3:E3)'],
      ['=SUM(B4:E4)', '=AVERAGE(B4:E4)'],
      ['=SUM(B5:E5)', '=AVERAGE(B5:E5)']
    ];

    rangeWriter.writeFormulas(formulas, 'F1:G5', 'Range_Operations_Demo');
    console.log('📊 Added calculated columns with formulas');

    // Example 4: Append new data
    const newProducts = [
      ['Widget E', 900, 1000, 1100, 1200],
      ['Widget F', 750, 800, 850, 900]
    ];

    const startRow = rangeWriter.appendData(newProducts, 'Range_Operations_Demo');
    console.log(`✏️ Appended new products starting at row ${startRow}`);

    // Example 5: Work with objects
    const objectData = [
      { name: 'Alice', department: 'Sales', salary: 75000, startDate: new Date('2020-01-15') },
      { name: 'Bob', department: 'Engineering', salary: 85000, startDate: new Date('2019-03-20') },
      { name: 'Charlie', department: 'Marketing', salary: 70000, startDate: new Date('2021-06-10') }
    ];

    rangeWriter.writeObjects(objectData, 'Range_Operations_Demo', true, 'I1');
    console.log('✏️ Wrote employee data as objects');

    // Example 6: Read data as objects
    const employeeObjects = rangeReader.readAsObjects('Range_Operations_Demo');
    console.log('📖 Read employee data as objects:');
    employeeObjects.forEach(emp => {
      if (emp.name && emp.name !== '') {
        console.log(`  - ${emp.name}: ${emp.department}`);
      }
    });

    // Example 7: Demonstrate batch operations
    const multipleRanges = ['A1:E1', 'F1:G1', 'I1:L1'];
    const batchData = rangeReader.readMultipleRanges(multipleRanges, 'Range_Operations_Demo');
    console.log(`📖 Read ${batchData.length} ranges in batch operation`);

    // Example 8: Work with A1 notation utilities
    console.log('🔧 A1 Notation utilities:');
    console.log(`  Row 5, Column 3 = ${RangeHelper.rowColToA1(5, 3)}`);
    console.log(`  "C5" = Row ${RangeHelper.a1ToRowCol("C5").row}, Column ${RangeHelper.a1ToRowCol("C5").col}`);
    console.log(`  Column 28 = ${RangeHelper.numberToColumnLetter(28)}`);
    console.log(`  Column "AB" = ${RangeHelper.columnLetterToNumber("AB")}`);

    console.log('✅ Range Operations Demo completed successfully!');

    // Show success message
    SpreadsheetApp.getUi().alert(
      'Range Demo Complete!',
      'Check the "Range_Operations_Demo" sheet to see all the examples in action. Review the console logs for detailed information.',
      SpreadsheetApp.getUi().ButtonSet.OK
    );

  } catch (error) {
    console.error('❌ Range demo failed:', error.message);
    SpreadsheetApp.getUi().alert('Demo Error', error.message, SpreadsheetApp.getUi().ButtonSet.OK);
  }
}
```

## 4. Data Management

### Managing Multiple Sheets

As your projects grow, you'll need to work with multiple sheets efficiently. Let's create a comprehensive system for managing multiple sheets:

```javascript
/**
 * MultiSheetManager - Advanced class for managing multiple sheets
 * This class provides enterprise-level sheet management capabilities
 */
class MultiSheetManager extends SheetManager {
  constructor(spreadsheetId = null) {
    super(spreadsheetId);
    this.sheetCache = new Map(); // Cache sheets for better performance
  }

  /**
   * Get or create multiple sheets at once
   * @param {Array} sheetConfigs - Array of sheet configuration objects
   * @return {Array} Array of sheet objects
   */
  getOrCreateSheets(sheetConfigs) {
    const results = [];
    
    try {
      sheetConfigs.forEach(config => {
        if (typeof config === 'string') {
          // Simple sheet name
          config = { name: config };
        }

        let sheet;
        if (this.sheetExists(config.name)) {
          sheet = this.getSheet(config.name);
          console.log(`📋 Retrieved existing sheet: ${config.name}`);
        } else {
          sheet = this.createSheet(config.name, config);
          console.log(`✅ Created new sheet: ${config.name}`);
        }

        // Cache the sheet
        this.sheetCache.set(config.name, sheet);
        results.push(sheet);
      });

      return results;

    } catch (error) {
      throw new Error(`❌ Failed to get/create sheets: ${error.message}`);
    }
  }

  /**
   * Copy data between sheets
   * @param {string} sourceSheetName - Source sheet name
   * @param {string} targetSheetName - Target sheet name
   * @param {string} sourceRange - Source range (optional, uses all data if not provided)
   * @param {string} targetCell - Target starting cell (default: A1)
   */
  copyBetweenSheets(sourceSheetName, targetSheetName, sourceRange = null, targetCell = 'A1') {
    try {
      const sourceSheet = this.getSheet(sourceSheetName);
      const targetSheet = this.getSheet(targetSheetName);

      let data;
      if (sourceRange) {
        data = sourceSheet.getRange(sourceRange).getValues();
      } else {
        // Get all data from source sheet
        const lastRow = sourceSheet.getLastRow();
        const lastCol = sourceSheet.getLastColumn();
        
        if (lastRow === 0 || lastCol === 0) {
          console.log('📋 Source sheet is empty, nothing to copy');
          return;
        }
        
        data = sourceSheet.getRange(1, 1, lastRow, lastCol).getValues();
      }

      if (data.length === 0) {
        console.log('📋 No data to copy');
        return;
      }

      // Write to target sheet
      const targetRange = targetSheet.getRange(targetCell)
        .offset(0, 0, data.length, data[0].length);
      targetRange.setValues(data);

      console.log(`📋 Copied ${data.length} rows from ${sourceSheetName} to ${targetSheetName}`);

    } catch (error) {
      throw new Error(`❌ Failed to copy between sheets: ${error.message}`);
    }
  }

  /**
   * Consolidate data from multiple sheets into one
   * @param {Array} sourceSheets - Array of source sheet names
   * @param {string} targetSheetName - Target sheet name
   * @param {boolean} includeSheetNames - Add a column indicating source sheet
   * @param {boolean} includeHeaders - Include headers from first sheet only
   */
  consolidateSheets(sourceSheets, targetSheetName, includeSheetNames = false, includeHeaders = true) {
    try {
      const targetSheet = this.getOrCreateSheet(targetSheetName);
      targetSheet.clear();

      let consolidatedData = [];
      let headers = null;

      sourceSheets.forEach((sheetName, index) => {
        const sheet = this.getSheet(sheetName);
        const lastRow = sheet.getLastRow();
        const lastCol = sheet.getLastColumn();

        if (lastRow === 0 || lastCol === 0) {
          console.log(`📋 Sheet ${sheetName} is empty, skipping`);
          return;
        }

        const data = sheet.getRange(1, 1, lastRow, lastCol).getValues();

        if (index === 0) {
          // First sheet - capture headers if needed
          if (includeHeaders) {
            headers = data[0];
            if (includeSheetNames) {
              headers.push('Source Sheet');
            }
            consolidatedData.push(headers);
          }
          // Add data rows
          const dataRows = includeHeaders ? data.slice(1) : data;
          dataRows.forEach(row => {
            if (includeSheetNames) {
              row.push(sheetName);
            }
            consolidatedData.push(row);
          });
        } else {
          // Subsequent sheets - skip headers, add data
          const dataRows = includeHeaders ? data.slice(1) : data;
          dataRows.forEach(row => {
            if (includeSheetNames) {
              row.push(sheetName);
            }
            consolidatedData.push(row);
          });
        }
      });

      if (consolidatedData.length > 0) {
        const range = targetSheet.getRange(1, 1, consolidatedData.length, consolidatedData[0].length);
        range.setValues(consolidatedData);

        // Format headers if included
        if (includeHeaders && headers) {
          const headerRange = targetSheet.getRange(1, 1, 1, headers.length);
          headerRange.setFontWeight('bold')
                    .setBackground('#E3F2FD')
                    .setBorder(true, true, true, true, false, false);
        }

        console.log(`✅ Consolidated ${sourceSheets.length} sheets into ${targetSheetName}`);
        console.log(`📊 Total rows: ${consolidatedData.length}`);
      }

    } catch (error) {
      throw new Error(`❌ Failed to consolidate sheets: ${error.message}`);
    }
  }

  /**
   * Split data from one sheet into multiple sheets based on a column value
   * @param {string} sourceSheetName - Source sheet name
   * @param {string|number} splitColumn - Column to split by (name or index)
   * @param {string} sheetNamePrefix - Prefix for new sheet names
   * @param {boolean} includeHeaders - Include headers in each split sheet
   */
  splitSheetByColumn(sourceSheetName, splitColumn, sheetNamePrefix = 'Split_', includeHeaders = true) {
    try {
      const sourceSheet = this.getSheet(sourceSheetName);
      const data = sourceSheet.getDataRange().getValues();

      if (data.length === 0) {
        console.log('📋 Source sheet is empty');
        return;
      }

      const headers = includeHeaders ? data[0] : null;
      const dataRows = includeHeaders ? data.slice(1) : data;

      // Determine split column index
      let splitColumnIndex;
      if (typeof splitColumn === 'string') {
        splitColumnIndex = headers.indexOf(splitColumn);
        if (splitColumnIndex === -1) {
          throw new Error(`Column '${splitColumn}' not found`);
        }
      } else {
        splitColumnIndex = splitColumn - 1; // Convert to 0-based
      }

      // Group data by split column value
      const groups = new Map();
      dataRows.forEach(row => {
        const groupKey = row[splitColumnIndex];
        if (!groups.has(groupKey)) {
          groups.set(groupKey, []);
        }
        groups.get(groupKey).push(row);
      });

      // Create sheets for each group
      groups.forEach((rows, groupKey) => {
        const cleanSheetName = String(groupKey).replace(/[^\w\s-]/g, '').substring(0, 30);
        const sheetName = `${sheetNamePrefix}${cleanSheetName}`;

        const sheet = this.createSheet(sheetName, { overwrite: true });

        let sheetData = rows;
        if (includeHeaders) {
          sheetData = [headers, ...rows];
        }

        sheet.getRange(1, 1, sheetData.length, sheetData[0].length).setValues(sheetData);

        if (includeHeaders) {
          // Format headers
          const headerRange = sheet.getRange(1, 1, 1, headers.length);
          headerRange.setFontWeight('bold')
                    .setBackground('#E8F5E8')
                    .setBorder(true, true, true, true, false, false);
        }

        console.log(`✅ Created sheet ${sheetName} with ${rows.length} rows`);
      });

      console.log(`📊 Split data into ${groups.size} sheets`);

    } catch (error) {
      throw new Error(`❌ Failed to split sheet: ${error.message}`);
    }
  }

  /**
   * Archive old data by moving to separate sheets
   * @param {string} sourceSheetName - Source sheet name
   * @param {string|number} dateColumn - Date column for archiving
   * @param {number} daysOld - Days old to consider for archiving
   * @param {string} archiveSheetPrefix - Prefix for archive sheet names
   */
  archiveOldData(sourceSheetName, dateColumn, daysOld = 90, archiveSheetPrefix = 'Archive_') {
    try {
      const sourceSheet = this.getSheet(sourceSheetName);
      const data = sourceSheet.getDataRange().getValues();

      if (data.length <= 1) {
        console.log('📋 No data to archive');
        return;
      }

      const headers = data[0];
      const dataRows = data.slice(1);

      // Determine date column index
      let dateColumnIndex;
      if (typeof dateColumn === 'string') {
        dateColumnIndex = headers.indexOf(dateColumn);
        if (dateColumnIndex === -1) {
          throw new Error(`Date column '${dateColumn}' not found`);
        }
      } else {
        dateColumnIndex = dateColumn - 1;
      }

      const cutoffDate = new Date();
      cutoffDate.setDate(cutoffDate.getDate() - daysOld);

      const archiveRows = [];
      const keepRows = [];

      dataRows.forEach(row => {
        const cellDate = new Date(row[dateColumnIndex]);
        if (cellDate < cutoffDate) {
          archiveRows.push(row);
        } else {
          keepRows.push(row);
        }
      });

      if (archiveRows.length === 0) {
        console.log('📋 No data old enough to archive');
        return;
      }

      // Create archive sheet
      const archiveSheetName = `${archiveSheetPrefix}${new Date().toISOString().split('T')[0]}`;
      const archiveSheet = this.createSheet(archiveSheetName, { overwrite: false });

      // Move old data to archive
      const archiveData = [headers, ...archiveRows];
      archiveSheet.getRange(1, 1, archiveData.length, archiveData[0].length)
                  .setValues(archiveData);

      // Keep recent data in source sheet
      sourceSheet.clear();
      const keepData = [headers, ...keepRows];
      sourceSheet.getRange(1, 1, keepData.length, keepData[0].length)
                 .setValues(keepData);

      console.log(`📋 Archived ${archiveRows.length} old rows to ${archiveSheetName}`);
      console.log(`📋 Kept ${keepRows.length} recent rows in ${sourceSheetName}`);

    } catch (error) {
      throw new Error(`❌ Failed to archive data: ${error.message}`);
    }
  }

  /**
   * Get cached sheet or retrieve from spreadsheet
   * @param {string} sheetName - Sheet name
   * @return {GoogleAppsScript.Spreadsheet.Sheet} Sheet object
   */
  getCachedSheet(sheetName) {
    if (this.sheetCache.has(sheetName)) {
      return this.sheetCache.get(sheetName);
    }

    const sheet = this.getSheet(sheetName);
    this.sheetCache.set(sheetName, sheet);
    return sheet;
  }

  /**
   * Clear the sheet cache
   */
  clearCache() {
    this.sheetCache.clear();
    console.log('🧹 Cleared sheet cache');
  }
}
```

#### MultiSheetManager: Quick usage examples

```javascript
function multiSheetManagerExamples() {
  const msm = new MultiSheetManager();

  // Ensure multiple sheets exist
  msm.getOrCreateSheets([
    { name: 'North', headers: ['ID', 'Amount'], tabColor: '#4CAF50' },
    { name: 'South', headers: ['ID', 'Amount'], tabColor: '#FF5722' }
  ]);

  // Copy data between sheets
  msm.copyBetweenSheets('North', 'South', 'A1:B10', 'A1');

  // Consolidate into a report (include source sheet names)
  msm.consolidateSheets(['North', 'South'], 'All_Regions', true, true);

  // Split a sheet by a column value
  msm.splitSheetByColumn('All_Regions', 'ID', 'ID_', true);

  // Archive data older than 60 days based on a date column
  // msm.archiveOldData('All_Regions', 'Created At', 60, 'Archive_');

  // Cache utilities
  const cached = msm.getCachedSheet('North');
  console.log('Cached sheet:', cached.getName());
  msm.clearCache();
}
```

### Finding and Filtering Data

Let's create a powerful data search and filter system:

```javascript
/**
 * DataSearcher - Advanced search and filter functionality
 * Provides SQL-like querying capabilities for Google Sheets
 */
class DataSearcher {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Find data using multiple criteria
   * @param {Object} criteria - Search criteria object
   * @param {string} sheetName - Sheet name to search in
   * @param {boolean} caseSensitive - Case sensitive search (default: false)
   * @return {Array} Array of matching row objects
   */
  findData(criteria, sheetName, caseSensitive = false) {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      const data = sheet.getDataRange().getValues();

      if (data.length === 0) {
        return [];
      }

      const headers = data[0];
      const dataRows = data.slice(1);
      const matches = [];

      dataRows.forEach((row, index) => {
        let isMatch = true;

        for (const [column, searchValue] of Object.entries(criteria)) {
          const columnIndex = headers.indexOf(column);
          if (columnIndex === -1) {
            throw new Error(`Column '${column}' not found`);
          }

          const cellValue = row[columnIndex];

          if (typeof searchValue === 'object' && searchValue.operator) {
            isMatch = this.evaluateCondition(cellValue, searchValue, caseSensitive);
          } else {
            // Simple equality check
            isMatch = this.simpleMatch(cellValue, searchValue, caseSensitive);
          }

          if (!isMatch) break;
        }

        if (isMatch) {
          const rowObject = { _rowNumber: index + 2 }; // +2 for header and 0-based index
          headers.forEach((header, idx) => {
            rowObject[header] = row[idx];
          });
          matches.push(rowObject);
        }
      });

      console.log(`🔍 Found ${matches.length} matching rows`);
      return matches;

    } catch (error) {
      throw new Error(`❌ Search failed: ${error.message}`);
    }
  }

  /**
   * Advanced filter with multiple operators
   * @param {string} sheetName - Sheet name
   * @param {Array} filters - Array of filter objects
   * @param {string} logicalOperator - 'AND' or 'OR' (default: 'AND')
   * @return {Array} Filtered data
   */
  advancedFilter(sheetName, filters, logicalOperator = 'AND') {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      const data = sheet.getDataRange().getValues();

      if (data.length === 0) {
        return [];
      }

      const headers = data[0];
      const dataRows = data.slice(1);
      const results = [];

      dataRows.forEach((row, index) => {
        const filterResults = filters.map(filter => {
          const columnIndex = headers.indexOf(filter.column);
          if (columnIndex === -1) {
            throw new Error(`Column '${filter.column}' not found`);
          }

          const cellValue = row[columnIndex];
          return this.evaluateCondition(cellValue, {
            operator: filter.operator,
            value: filter.value,
            value2: filter.value2 // For BETWEEN operations
          }, filter.caseSensitive || false);
        });

        let passesFilter;
        if (logicalOperator === 'OR') {
          passesFilter = filterResults.some(result => result);
        } else {
          passesFilter = filterResults.every(result => result);
        }

        if (passesFilter) {
          const rowObject = { _rowNumber: index + 2 };
          headers.forEach((header, idx) => {
            rowObject[header] = row[idx];
          });
          results.push(rowObject);
        }
      });

      console.log(`🔍 Advanced filter returned ${results.length} rows`);
      return results;

    } catch (error) {
      throw new Error(`❌ Advanced filter failed: ${error.message}`);
    }
  }

  /**
   * Full text search across all columns
   * @param {string} searchText - Text to search for
   * @param {string} sheetName - Sheet name
   * @param {boolean} wholeWord - Match whole words only
   * @param {boolean} caseSensitive - Case sensitive search
   * @return {Array} Array of matching rows with highlighted matches
   */
  fullTextSearch(searchText, sheetName, wholeWord = false, caseSensitive = false) {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      const data = sheet.getDataRange().getValues();

      if (data.length === 0) {
        return [];
      }

      const headers = data[0];
      const dataRows = data.slice(1);
      const matches = [];

      const searchPattern = wholeWord 
        ? new RegExp(`\\b${this.escapeRegExp(searchText)}\\b`, caseSensitive ? 'g' : 'gi')
        : new RegExp(this.escapeRegExp(searchText), caseSensitive ? 'g' : 'gi');

      dataRows.forEach((row, index) => {
        const matchingColumns = [];

        row.forEach((cellValue, colIndex) => {
          const stringValue = String(cellValue);
          if (searchPattern.test(stringValue)) {
            matchingColumns.push({
              column: headers[colIndex],
              value: stringValue,
              matches: stringValue.match(searchPattern)
            });
          }
        });

        if (matchingColumns.length > 0) {
          const rowObject = { 
            _rowNumber: index + 2,
            _matchingColumns: matchingColumns,
            _matchCount: matchingColumns.reduce((sum, col) => sum + col.matches.length, 0)
          };

          headers.forEach((header, idx) => {
            rowObject[header] = row[idx];
          });

          matches.push(rowObject);
        }
      });

      // Sort by match count (most matches first)
      matches.sort((a, b) => b._matchCount - a._matchCount);

      console.log(`🔍 Full text search found ${matches.length} rows containing "${searchText}"`);
      return matches;

    } catch (error) {
      throw new Error(`❌ Full text search failed: ${error.message}`);
    }
  }

  /**
   * Find duplicate rows based on specified columns
   * @param {string} sheetName - Sheet name
   * @param {Array} columns - Columns to check for duplicates
   * @param {boolean} keepFirst - Keep first occurrence (default: true)
   * @return {Object} Object with duplicates and unique data
   */
  findDuplicates(sheetName, columns, keepFirst = true) {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      const data = sheet.getDataRange().getValues();

      if (data.length === 0) {
        return { duplicates: [], unique: [] };
      }

      const headers = data[0];
      const dataRows = data.slice(1);

      // Get column indices
      const columnIndices = columns.map(col => {
        const index = headers.indexOf(col);
        if (index === -1) {
          throw new Error(`Column '${col}' not found`);
        }
        return index;
      });

      const seen = new Map();
      const duplicates = [];
      const unique = [];

      dataRows.forEach((row, index) => {
        // Create a key from specified columns
        const key = columnIndices.map(idx => String(row[idx])).join('|');

        if (seen.has(key)) {
          // This is a duplicate
          const rowObject = { _rowNumber: index + 2 };
          headers.forEach((header, idx) => {
            rowObject[header] = row[idx];
          });
          duplicates.push(rowObject);

          if (!keepFirst) {
            // Also mark the first occurrence as duplicate
            const firstOccurrence = seen.get(key);
            if (!duplicates.find(dup => dup._rowNumber === firstOccurrence._rowNumber)) {
              duplicates.push(firstOccurrence);
            }
          }
        } else {
          // First occurrence
          const rowObject = { _rowNumber: index + 2 };
          headers.forEach((header, idx) => {
            rowObject[header] = row[idx];
          });
          seen.set(key, rowObject);
          
          if (keepFirst) {
            unique.push(rowObject);
          }
        }
      });

      if (!keepFirst) {
        // If not keeping first, unique contains only truly unique rows
        seen.forEach((row, key) => {
          const isDuplicate = duplicates.some(dup => dup._rowNumber === row._rowNumber);
          if (!isDuplicate) {
            unique.push(row);
          }
        });
      }

      console.log(`🔍 Found ${duplicates.length} duplicate rows, ${unique.length} unique rows`);
      return { duplicates, unique };

    } catch (error) {
      throw new Error(`❌ Find duplicates failed: ${error.message}`);
    }
  }

  /**
   * Helper method to evaluate search conditions
   * @private
   */
  evaluateCondition(cellValue, condition, caseSensitive = false) {
    const { operator, value, value2 } = condition;

    switch (operator) {
      case 'equals':
        return this.simpleMatch(cellValue, value, caseSensitive);

      case 'contains':
        return this.containsMatch(cellValue, value, caseSensitive);

      case 'startsWith':
        return this.startsWithMatch(cellValue, value, caseSensitive);

      case 'endsWith':
        return this.endsWithMatch(cellValue, value, caseSensitive);

      case 'greaterThan':
        return Number(cellValue) > Number(value);

      case 'lessThan':
        return Number(cellValue) < Number(value);

      case 'greaterThanOrEqual':
        return Number(cellValue) >= Number(value);

      case 'lessThanOrEqual':
        return Number(cellValue) <= Number(value);

      case 'between':
        const numValue = Number(cellValue);
        return numValue >= Number(value) && numValue <= Number(value2);

      case 'isEmpty':
        return cellValue === '' || cellValue === null || cellValue === undefined;

      case 'isNotEmpty':
        return cellValue !== '' && cellValue !== null && cellValue !== undefined;

      case 'dateAfter':
        return new Date(cellValue) > new Date(value);

      case 'dateBefore':
        return new Date(cellValue) < new Date(value);

      case 'dateEqual':
        const cellDate = new Date(cellValue);
        const compareDate = new Date(value);
        return cellDate.toDateString() === compareDate.toDateString();

      default:
        return this.simpleMatch(cellValue, value, caseSensitive);
    }
  }

  /**
   * Helper methods for different match types
   * @private
   */
  simpleMatch(cellValue, searchValue, caseSensitive) {
    if (!caseSensitive) {
      return String(cellValue).toLowerCase() === String(searchValue).toLowerCase();
    }
    return String(cellValue) === String(searchValue);
  }

  containsMatch(cellValue, searchValue, caseSensitive) {
    const cell = String(cellValue);
    const search = String(searchValue);
    return caseSensitive 
      ? cell.includes(search)
      : cell.toLowerCase().includes(search.toLowerCase());
  }

  startsWithMatch(cellValue, searchValue, caseSensitive) {
    const cell = String(cellValue);
    const search = String(searchValue);
    return caseSensitive
      ? cell.startsWith(search)
      : cell.toLowerCase().startsWith(search.toLowerCase());
  }

  endsWithMatch(cellValue, searchValue, caseSensitive) {
    const cell = String(cellValue);
    const search = String(searchValue);
    return caseSensitive
      ? cell.endsWith(search)
      : cell.toLowerCase().endsWith(search.toLowerCase());
  }

  escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
}
```

#### DataSearcher: Quick usage examples

```javascript
function dataSearcherExamples() {
  const msm = new MultiSheetManager();
  const searcher = new DataSearcher(msm);

  // 1) Exact match by column
  const north = searcher.findData({ Region: 'North' }, 'Sales_Data');
  console.log('North rows:', north.length);

  // 2) Advanced filter (Amount > 1000 AND Product contains "Widget")
  const high = searcher.advancedFilter('Sales_Data', [
    { column: 'Amount', operator: 'greaterThan', value: 1000 },
    { column: 'Product', operator: 'contains', value: 'Widget' }
  ], 'AND');
  console.log('High-value widget sales:', high.length);

  // 3) Full-text search
  const hits = searcher.fullTextSearch('Alice', 'Sales_Data');
  console.log('Rows mentioning Alice:', hits.length);

  // 4) Duplicate finder
  const { duplicates, unique } = searcher.findDuplicates('Sales_Data', ['Salesperson']);
  console.log('Duplicates:', duplicates.length, 'Unique:', unique.length);
}
```

```javascript
/**
 * Filters data based on multiple conditions
 * @param {string} filterColumn - Column name or index to filter by
 * @param {*} filterValue - Value to match or filter object
 * @param {string} sheetName - Optional: Name of sheet
 * @param {boolean} exactMatch - Whether to do exact matching
 * @return {Array} Filtered data as 2D array
 */
function filterData(filterColumn, filterValue, sheetName = null, exactMatch = true) {
  try {
    const sheet = getSheet(sheetName);
    const data = getAllData(sheetName, null, true);
    
    if (data.length === 0) return [];
    
    const headers = data[0];
    let columnIndex;
    
    if (typeof filterColumn === 'string') {
      columnIndex = headers.indexOf(filterColumn);
      if (columnIndex === -1) {
        throw new Error(`Column '${filterColumn}' not found`);
      }
    } else {
      columnIndex = filterColumn - 1; // Convert to 0-based
    }
    
    const filteredRows = data.filter((row, index) => {
      if (index === 0) return true; // Keep headers
      
      const cellValue = row[columnIndex];
      
      if (exactMatch) {
        return cellValue === filterValue;
      } else {
        return String(cellValue).toLowerCase().includes(String(filterValue).toLowerCase());
      }
    });
    return filteredRows;
    
  } catch (error) {
    throw new Error(`Filter failed: ${error.message}`);
  }
}
```

#### filterData: Usage with helper glue

Note: This utility expects `getSheet` and `getAllData` helpers. Use the snippets below or prefer `DataSearcher` for richer filtering.

```javascript
function filterDataExamples() {
  const sm = new SheetManager();
  const rr = new RangeReader(sm);

  // Minimal glue to support filterData()
  this.getSheet = (name) => name ? sm.getSheet(name) : sm.getActiveSheet();
  this.getAllData = (name) => rr.readAllData(name, true);

  const rowsExact = filterData('Region', 'North', 'Sales_Data', true);
  const rowsFuzzy = filterData('Product', 'widget', 'Sales_Data', false);
  console.log('Exact:', rowsExact.length, 'Fuzzy:', rowsFuzzy.length);
}
```

### Sorting and Organizing

Data organization is crucial for analysis. Let's create a powerful sorting system:

```javascript
/**
 * DataSorter - Advanced sorting functionality for Google Sheets
 * Provides multiple sorting options and maintains data integrity
 */
class DataSorter {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Sort data by one or more columns with advanced options
   * @param {string} sheetName - Sheet name to sort
   * @param {Array} sortCriteria - Array of sort criteria objects
   * @param {boolean} hasHeaders - Whether data has headers (default: true)
   * @param {string} range - Optional: Specific range to sort
   */
  sortData(sheetName, sortCriteria, hasHeaders = true, range = null) {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      
      let sortRange;
      if (range) {
        sortRange = sheet.getRange(range);
      } else {
        const lastRow = sheet.getLastRow();
        const lastCol = sheet.getLastColumn();
        
        if (lastRow <= (hasHeaders ? 1 : 0)) {
          console.log('📊 No data to sort');
          return;
        }
        
        const startRow = hasHeaders ? 2 : 1;
        const numRows = lastRow - (hasHeaders ? 1 : 0);
        sortRange = sheet.getRange(startRow, 1, numRows, lastCol);
      }

      // Convert sort criteria to Google Sheets format
      const sortSpecs = sortCriteria.map(criterion => {
        let columnIndex;
        
        if (typeof criterion.column === 'string') {
          // Column name - need to find index from headers
          if (hasHeaders) {
            const headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];
            columnIndex = headers.indexOf(criterion.column) + 1;
            if (columnIndex === 0) {
              throw new Error(`Column '${criterion.column}' not found`);
            }
          } else {
            throw new Error('Cannot use column names without headers');
          }
        } else {
          columnIndex = criterion.column;
        }

        return {
          column: columnIndex,
          ascending: criterion.ascending !== false // Default to true
        };
      });

      // Apply sort
      sortRange.sort(sortSpecs);
      
      console.log(`📊 Sorted data by ${sortSpecs.length} criteria`);

    } catch (error) {
      throw new Error(`❌ Sort failed: ${error.message}`);
    }
  }

  /**
   * Randomize (shuffle) data rows
   * @param {string} sheetName - Sheet name
   * @param {boolean} hasHeaders - Whether to preserve headers
   */
  shuffleData(sheetName, hasHeaders = true) {
    try {
      const sheet = this.sheetManager.getSheet(sheetName);
      const data = sheet.getDataRange().getValues();

      if (data.length === 0) {
        console.log('📊 No data to shuffle');
        return;
      }

      const headers = hasHeaders ? data[0] : null;
      const dataRows = hasHeaders ? data.slice(1) : data;

      // Fisher-Yates shuffle algorithm
      for (let i = dataRows.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [dataRows[i], dataRows[j]] = [dataRows[j], dataRows[i]];
      }

      // Write back to sheet
      sheet.clear();
      const shuffledData = hasHeaders ? [headers, ...dataRows] : dataRows;
      sheet.getRange(1, 1, shuffledData.length, shuffledData[0].length)
           .setValues(shuffledData);

      console.log('📊 Data shuffled randomly');

    } catch (error) {
      throw new Error(`❌ Shuffle failed: ${error.message}`);
    }
  }
}

/**
 * Performance testing utility
 */
function measurePerformance(fn, label) {
  const startTime = new Date().getTime();
  const result = fn();
  const endTime = new Date().getTime();
  
  console.log(`⏱️ ${label}: ${endTime - startTime}ms`);
  return result;
}

/**
 * Compare performance of different approaches
 */
function performanceComparison() {
  const sheet = SpreadsheetApp.getActiveSheet();
  const testData = Array(100).fill().map((_, i) => [`Row ${i + 1}`]);

  // Test slow approach
  measurePerformance(() => {
    PerformanceOptimizer.slowWrite(sheet, testData);
  }, 'Slow Write (individual cells)');

  // Clear sheet
  sheet.clear();

  // Test fast approach
  measurePerformance(() => {
    PerformanceOptimizer.fastWrite(sheet, testData);
  }, 'Fast Write (batch)');
}
```

## 5. Formatting and Styling

### Cell Formatting

Professional formatting makes your data more readable and impactful:

```javascript
/**
 * CellFormatter - Comprehensive cell and range formatting
 * Provides professional styling options for Google Sheets
 */
class CellFormatter {
  constructor(sheetManager) {
    this.sheetManager = sheetManager;
  }

  /**
   * Apply comprehensive formatting to a range
   * @param {string} range - Range in A1 notation
   * @param {Object} formatOptions - Formatting options object
   * @param {string} sheetName - Optional: Sheet name
   */
  formatRange(range, formatOptions, sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const rangeObj = sheet.getRange(range);

      // Text formatting
      if (formatOptions.bold !== undefined) rangeObj.setFontWeight(formatOptions.bold ? 'bold' : 'normal');
      if (formatOptions.italic !== undefined) rangeObj.setFontStyle(formatOptions.italic ? 'italic' : 'normal');
      if (formatOptions.fontSize) rangeObj.setFontSize(formatOptions.fontSize);
      if (formatOptions.fontFamily) rangeObj.setFontFamily(formatOptions.fontFamily);
      if (formatOptions.fontColor) rangeObj.setFontColor(formatOptions.fontColor);

      // Background and borders
      if (formatOptions.backgroundColor) rangeObj.setBackground(formatOptions.backgroundColor);
      
      if (formatOptions.borders) {
        this.applyBorders(rangeObj, formatOptions.borders);
      }

      // Alignment
      if (formatOptions.horizontalAlignment) {
        rangeObj.setHorizontalAlignment(formatOptions.horizontalAlignment);
      }
      if (formatOptions.verticalAlignment) {
        rangeObj.setVerticalAlignment(formatOptions.verticalAlignment);
      }

      // Number formatting
      if (formatOptions.numberFormat) {
        rangeObj.setNumberFormat(formatOptions.numberFormat);
      }

      // Text wrapping and rotation
      if (formatOptions.wrapText !== undefined) {
        rangeObj.setWrap(formatOptions.wrapText);
      }

      console.log(`✨ Applied formatting to range ${range}`);

    } catch (error) {
      throw new Error(`❌ Failed to format range: ${error.message}`);
    }
  }

  /**
   * Format currency columns
   * @param {string|Array} columns - Column or array of columns (A, B, C or [A, B, C])
   * @param {string} currencySymbol - Currency symbol (default: $)
   * @param {string} sheetName - Optional: Sheet name
   */
  formatCurrency(columns, currencySymbol = '$', sheetName = null) {
    try {
      const columnsArray = Array.isArray(columns) ? columns : [columns];
      
      columnsArray.forEach(column => {
        const range = `${column}:${column}`;
        const numberFormat = `${currencySymbol}#,##0.00`;
        
        this.formatRange(range, { numberFormat }, sheetName);
      });

      console.log(`💰 Applied currency formatting to columns: ${columnsArray.join(', ')}`);

    } catch (error) {
      throw new Error(`❌ Failed to format currency: ${error.message}`);
    }
  }

  /**
   * Format date columns
   * @param {string|Array} columns - Column or array of columns
   * @param {string} dateFormat - Date format (default: 'yyyy-mm-dd')
   * @param {string} sheetName - Optional: Sheet name
   */
  formatDates(columns, dateFormat = 'yyyy-mm-dd', sheetName = null) {
    try {
      const columnsArray = Array.isArray(columns) ? columns : [columns];
      
      columnsArray.forEach(column => {
        const range = `${column}:${column}`;
        this.formatRange(range, { numberFormat: dateFormat }, sheetName);
      });

      console.log(`📅 Applied date formatting to columns: ${columnsArray.join(', ')}`);

    } catch (error) {
      throw new Error(`❌ Failed to format dates: ${error.message}`);
    }
  }

  /**
   * Apply alternating row colors (zebra striping)
   * @param {string} range - Range to apply striping to
   * @param {string} color1 - First color (default: white)
   * @param {string} color2 - Second color (default: light gray)
   * @param {string} sheetName - Optional: Sheet name
   */
  applyZebraStriping(range, color1 = '#FFFFFF', color2 = '#F8F9FA', sheetName = null) {
    try {
      const sheet = sheetName 
        ? this.sheetManager.getSheet(sheetName)
        : this.sheetManager.getActiveSheet();

      const rangeObj = sheet.getRange(range);
      const numRows = rangeObj.getNumRows();
      const numCols = rangeObj.getNumColumns();

      // Create background color array
      const backgrounds = [];
      for (let i = 0; i < numRows; i++) {
        const rowColor = i % 2 === 0 ? color1 : color2;
        backgrounds.push(new Array(numCols).fill(rowColor));
      }

      rangeObj.setBackgrounds(backgrounds);
      console.log(`🦓 Applied zebra striping to range ${range}`);

    } catch (error) {
      throw new Error(`❌ Failed to apply zebra striping: ${error.message}`);
    }
  }

  /**
   * Helper method to apply borders
   * @private
   */
  applyBorders(rangeObj, borderOptions) {
    const { style = 'solid', color = '#000000' } = borderOptions;

    // Define border style
    let borderStyle;
    switch (style) {
      case 'solid':
        borderStyle = SpreadsheetApp.BorderStyle.SOLID;
        break;
      case 'dashed':
        borderStyle = SpreadsheetApp.BorderStyle.DASHED;
        break;
      case 'dotted':
        borderStyle = SpreadsheetApp.BorderStyle.DOTTED;
        break;
      default:
        borderStyle = SpreadsheetApp.BorderStyle.SOLID;
    }

    // Apply borders based on type
    if (borderOptions.type === 'all') {
      rangeObj.setBorder(true, true, true, true, true, true, color, borderStyle);
    } else if (borderOptions.type === 'outline') {
      rangeObj.setBorder(true, true, true, true, false, false, color, borderStyle);
    }
  }
}
```

#### CellFormatter: Quick usage examples

```javascript
function cellFormatterExamples() {
  const msm = new MultiSheetManager();
  const fmt = new CellFormatter(msm);

  // Apply header styling
  fmt.formatRange('A1:E1', {
    bold: true,
    backgroundColor: '#E3F2FD',
    horizontalAlignment: 'center'
  });

  // Currency and date formats
  fmt.formatCurrency(['D', 'E'], '$');
  fmt.formatDates('A', 'yyyy-mm-dd');

  // Zebra striping for data body
  fmt.applyZebraStriping('A2:E100', '#FFFFFF', '#F8F9FA');
}
```

## Complete Project Example: Employee Management System

Let's create a comprehensive employee management system that demonstrates all concepts:

```javascript
/**
 * EmployeeManager - Complete employee management system
 * OPTIMIZED for performance with caching and batch operations
 */
class EmployeeManager {
  constructor() {
    this.sheetManager = new MultiSheetManager();
    this.formatter = new CellFormatter(this.sheetManager);
    this.searcher = new DataSearcher(this.sheetManager);
    this.sorter = new DataSorter(this.sheetManager);
    this.cacheManager = new CacheManager();
    
    this.initializeSystem();
  }

  /**
   * Initialize the employee management system
   */
  initializeSystem() {
    try {
      console.log('🏢 Initializing Employee Management System');

      // Create necessary sheets
      const sheets = [
        { 
          name: 'Employees', 
          headers: ['Employee ID', 'Name', 'Email', 'Department', 'Position', 'Hire Date', 'Salary', 'Status'],
          tabColor: '#4CAF50'
        },
        { 
          name: 'Departments', 
          headers: ['Department', 'Manager', 'Budget', 'Location'],
          tabColor: '#2196F3'
        },
        { 
          name: 'Payroll', 
          headers: ['Employee ID', 'Name', 'Base Salary', 'Net Pay', 'Pay Date'],
          tabColor: '#FF9800'
        }
      ];

      this.sheetManager.getOrCreateSheets(sheets);

      // Format headers for all sheets
      sheets.forEach(sheetConfig => {
        this.formatter.formatRange('1:1', {
          bold: true,
          backgroundColor: '#E3F2FD',
          horizontalAlignment: 'center'
        }, sheetConfig.name);
      });

      console.log('✅ Employee Management System initialized');

    } catch (error) {
      throw new Error(`❌ Failed to initialize system: ${error.message}`);
    }
  }

  /**
   * Add a new employee
   * @param {Object} employeeData - Employee information
   */
  addEmployee(employeeData) {
    try {
      const {
        employeeId,
        name,
        email,
        department,
        position,
        hireDate,
        salary,
        status = 'Active'
      } = employeeData;

      // Validate required fields
      if (!employeeId || !name || !email || !department) {
        throw new Error('Missing required employee information');
      }

      // Check if employee ID already exists
      const existingEmployee = this.searcher.findData(
        { 'Employee ID': employeeId },
        'Employees'
      );

      if (existingEmployee.length > 0) {
        throw new Error(`Employee ID ${employeeId} already exists`);
      }

      // Add employee to sheet
      const rangeWriter = new RangeWriter(this.sheetManager);
      const employeeRow = [
        employeeId,
        name,
        email,
        department,
        position,
        new Date(hireDate),
        salary,
        status
      ];

      rangeWriter.appendData([employeeRow], 'Employees', true);

      // Format the new row
      const sheet = this.sheetManager.getSheet('Employees');
      const lastRow = sheet.getLastRow();
      
      // Format salary as currency and date
      this.formatter.formatRange(`G${lastRow}`, { numberFormat: '$#,##0.00' }, 'Employees');
      this.formatter.formatRange(`F${lastRow}`, { numberFormat: 'yyyy-mm-dd' }, 'Employees');

      console.log(`👤 Added employee: ${name} (ID: ${employeeId})`);

    } catch (error) {
      throw new Error(`❌ Failed to add employee: ${error.message}`);
    }
  }

  /**
   * Add multiple employees in batch (OPTIMIZED)
   * @param {Array} employees - Array of employee objects
   */
  addEmployeesBatch(employees) {
    try {
      if (!Array.isArray(employees) || employees.length === 0) {
        throw new Error('Employees must be a non-empty array');
      }

      // Prepare all rows at once
      const rows = employees.map(emp => [
        emp.employeeId,
        emp.name,
        emp.email,
        emp.department,
        emp.position,
        new Date(emp.hireDate),
        emp.salary,
        emp.status || 'Active'
      ]);

      // Single batch write
      const rangeWriter = new RangeWriter(this.sheetManager);
      const startRow = rangeWriter.appendData(rows, 'Employees', true);

      // Batch format salary and date columns
      const sheet = this.sheetManager.getSheet('Employees');
      const numRows = rows.length;
      
      PerformanceOptimizer.batchFormat(sheet, [
        { range: `G${startRow}:G${startRow + numRows - 1}`, numberFormat: '$#,##0.00' },
        { range: `F${startRow}:F${startRow + numRows - 1}`, numberFormat: 'yyyy-mm-dd' }
      ]);

      // Clear cache after batch insert
      this.cacheManager.clearAll();

      console.log(`👥 Added ${employees.length} employees in batch`);

    } catch (error) {
      throw new Error(`❌ Failed to add employees in batch: ${error.message}`);
    }
  }

  /**
   * Generate comprehensive employee report
   * @return {Object} Report data
   */
  generateReport() {
    return this.cacheManager.memoize('employee_report', () => {
      console.log('📊 Generating fresh employee report');

      const employees = this.getAllEmployees();

      // Calculate statistics
      const totalEmployees = employees.length;
      const activeEmployees = employees.filter(emp => emp.Status === 'Active').length;
      
      // Department breakdown
      const departmentBreakdown = {};
      employees.forEach(emp => {
        const dept = emp.Department;
        if (!departmentBreakdown[dept]) {
          departmentBreakdown[dept] = { count: 0, totalSalary: 0 };
        }
        departmentBreakdown[dept].count++;
        departmentBreakdown[dept].totalSalary += emp.Salary || 0;
      });

      return {
        generatedDate: new Date(),
        totalEmployees,
        activeEmployees,
        departmentBreakdown,
        topEarners: employees
          .sort((a, b) => (b.Salary || 0) - (a.Salary || 0))
          .slice(0, 5)
      };
    }, 600); // Cache for 10 minutes
  }

  /**
   * Get all employees (with caching)
   * @return {Array} Array of employee objects
   */
  getAllEmployees() {
    return this.cacheManager.cacheSheetData('Employees', () => {
      const rangeReader = new RangeReader(this.sheetManager);
      return rangeReader.readAsObjects('Employees');
    }, 300); // Cache for 5 minutes
  }

  /**
   * Search employees with criteria
   * @param {Object} searchCriteria - Search criteria
   * @return {Array} Matching employees
   */
  searchEmployees(searchCriteria) {
    try {
      return this.searcher.findData(searchCriteria, 'Employees');
    } catch (error) {
      throw new Error(`❌ Employee search failed: ${error.message}`);
    }
  }
}

/**
 * Demo function showcasing the complete employee management system
 */
function employeeManagementDemo() {
  try {
    console.log('🚀 Starting Employee Management System Demo');

    // Initialize the system
    const empManager = new EmployeeManager();

    // Add sample employees
    const sampleEmployees = [
      {
        employeeId: 'EMP001',
        name: 'Alice Johnson',
        email: 'alice@company.com',
        department: 'Engineering',
        position: 'Senior Developer',
        hireDate: '2020-01-15',
        salary: 85000
      },
      {
        employeeId: 'EMP002',
        name: 'Bob Smith',
        email: 'bob@company.com',
        department: 'Marketing',
        position: 'Marketing Manager',
        hireDate: '2021-03-20',
        salary: 75000
      },
      {
        employeeId: 'EMP003',
        name: 'Carol Davis',
        email: 'carol@company.com',
        department: 'Engineering',
        position: 'Junior Developer',
        hireDate: '2023-06-10',
        salary: 65000
      }
    ];

    sampleEmployees.forEach(emp => {
      empManager.addEmployee(emp);
    });

    // Generate report
    const report = empManager.generateReport();
    console.log('📊 Report Summary:', {
      totalEmployees: report.totalEmployees,
      departments: Object.keys(report.departmentBreakdown).length
    });

    const allEmployees = empManager.getAllEmployees();
    console.log('All Employees:', allEmployees);

    // Search for engineering employees
    const engineeringEmployees = empManager.searchEmployees({
      Department: 'Engineering'
    });
    console.log(`🔍 Found ${engineeringEmployees.length} engineering employees`);

    console.log('✅ Employee Management System Demo completed successfully!');

    // Show success message
    SpreadsheetApp.getUi().alert(
      'Employee Management Demo Complete!',
      'Check the Employees, Departments, and Payroll sheets to see the complete system in action.',
      SpreadsheetApp.getUi().ButtonSet.OK
    );

  } catch (error) {
    console.error('❌ Employee Management Demo failed:', error.message);
    SpreadsheetApp.getUi().alert('Demo Error', error.message, SpreadsheetApp.getUi().ButtonSet.OK);
  }
}
```

### EmployeeManager: Minimal usage example

```javascript
function employeeManagerQuickStart() {
  const mgr = new EmployeeManager();

  mgr.addEmployee({
    employeeId: 'EMP100',
    name: 'Eve Adams',
    email: 'eve@company.com',
    department: 'Engineering',
    position: 'Developer',
    hireDate: '2024-07-01',
    salary: 92000
  });

  const report = mgr.generateReport();
  console.log('Total employees:', report.totalEmployees);

  const eng = mgr.searchEmployees({ Department: 'Engineering' });
  console.log('Engineering count:', eng.length);
}
```

## 11. Best Practices and Error Handling

### Error Handling Patterns

Robust error handling is essential for reliable automation:

```javascript
/**
 * ErrorHandler - Centralized error handling and logging
 * Provides consistent error reporting and recovery mechanisms
 */
class ErrorHandler {
  constructor() {
    this.errorLog = [];
  }

  /**
   * Handle and log errors with context
   * @param {Error} error - The error object
   * @param {string} context - Context where error occurred
   * @param {Object} additionalInfo - Additional debugging information
   */
  handleError(error, context, additionalInfo = {}) {
    const errorEntry = {
      timestamp: new Date(),
      context,
      message: error.message,
      additionalInfo
    };

    this.errorLog.push(errorEntry);
    
    console.error(`❌ Error in ${context}:`, error.message);
    console.error('Additional info:', additionalInfo);
  }

  /**
   * Get error log
   * @return {Array} Array of error entries
   */
  getErrorLog() {
    return [...this.errorLog];
  }
}

/**
 * Global error handler instance
 */
const ERROR_HANDLER = new ErrorHandler();

/**
 * Wrapper function for safe execution of operations
 * @param {Function} operation - Operation to execute safely
 * @param {string} context - Context description
 * @param {*} defaultReturn - Default return value on error
 */
function safeExecute(operation, context, defaultReturn = null) {
  try {
    return operation();
  } catch (error) {
    ERROR_HANDLER.handleError(error, context, { 
      timestamp: new Date().toISOString()
    });
    return defaultReturn;
  }
}
```

#### ErrorHandler and safeExecute: Quick usage

```javascript
function errorHandlingExamples() {
  const result = safeExecute(() => {
    // Simulate error
    throw new Error('Boom');
  }, 'Demo operation', 'fallback');

  console.log('Result on error:', result); // -> 'fallback'
  console.log('Errors captured:', ERROR_HANDLER.getErrorLog().length);
}
```

## Conclusion

This comprehensive Google Apps Script tutorial has covered everything from basic operations to advanced enterprise-level systems. You now have:

✅ **Complete Understanding**: From beginner concepts to advanced implementations  
✅ **Reusable Code**: Well-structured classes and functions you can use in any project  
✅ **Real-World Examples**: Practical systems like employee management  
✅ **Best Practices**: Error handling, performance optimization, and security  
✅ **Professional Standards**: Clean, documented, and maintainable code

### Next Steps

1. **Practice**: Try the demo functions to see everything in action
2. **Customize**: Adapt the classes for your specific needs  
3. **Extend**: Add new features to the example systems
4. **Share**: Use this knowledge to help others automate their workflows

### Key Takeaways

- **Start Simple**: Begin with basic operations and build up complexity
- **Use Classes**: Organize your code in reusable, maintainable classes
- **Handle Errors**: Always implement comprehensive error handling
- **Optimize for Speed**: Use batch operations, caching, and minimize API calls
- **Think Performance**: Every API call matters - batch whenever possible
- **Document Everything**: Clear documentation makes code maintainable

**Performance Mantra:** Read once, write once, process in memory! 🚀
