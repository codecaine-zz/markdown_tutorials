---
title: This is a well-structured and reusable set of JavaScript classes for managing Gmail operations within Google Apps Script. Here's an analysis and some suggestions for improvement:

## Strengths

1. **Modular Design**: The code is organized into clear, single-responsibility classes
2. **Error Handling**: Proper try-catch blocks with meaningful error messages
3. **Caching**: The `GmailApp` search results are cached to improve performance
4. **Configuration Management**: Centralized configuration that's easy to modify
5. **Reusability**: Classes can be imported and used across different projects

## Suggestions for Improvement

### 1. Add Type Definitions
```javascript
/**
 * @typedef {Object} GmailThread
 * @property {Function} moveToArchive
 * @property {Function} addLabel
 */

/**
 * @typedef {Object} GmailMessage
 * @property {Function} addToLabel
 */
```

### 2. Add Async/Await Support for Better Error Handling
```javascript
// In GmailApp.search, you might want to add:
async search(query, start = 0, maxResults = 50) {
  try {
    const threads = await GmailApp.search(query, start, maxResults);
    return threads;
  } catch (error) {
    throw new Error(`Search failed for query "${query}": ${error.message}`);
  }
}
```

### 3. Add Validation to Configuration Manager
```javascript
static get(path) {
  const keys = path.split('.');
  let current = this.defaults;
  
  for (let i = 0; i < keys.length; i++) {
    if (!current || typeof current !== 'object') {
      return undefined;
    }
    
    current = current[keys[i]];
  }
  
  // Add validation
  if (current === undefined) {
    console.warn(`Configuration path "${path}" not found`);
  }
  
  return current;
}
```

### 4. Add Logging Utility
```javascript
class Logger {
  static info(message, data = null) {
    console.log(`[INFO] ${message}`, data);
  }
  
  static error(message, error = null) {
    console.error(`[ERROR] ${message}`, error);
  }
  
  static debug(message, data = null) {
    if (ConfigManager.get('debug')) {
      console.log(`[DEBUG] ${message}`, data);
    }
  }
}
```

### 5. Add Unit Test Structure
```javascript
// For testing purposes
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { ConfigManager, GmailApp, Logger };
}
```

## Usage Example

```javascript
// Initialize components
const config = ConfigManager.get('labels');
const gmail = new GmailApp();

// Search and process threads
async function processEmails() {
  const threads = await gmail.getThreads('label:inbox', 50);
  
  // Process in batches
  for (let i = 0; i < threads.length; i += 10) {
    const batch = threads.slice(i, i + 10);
    await processBatch(batch);
    await Utilities.sleep(1000); // Rate limiting
  }
}
```

## Additional Features You Could Add

1. **Rate Limiting**: To prevent exceeding Gmail API quotas
2. **Retry Logic**: For transient failures
3. **Email Parsing**: More sophisticated email content extraction
4. **Metrics Collection**: Track processing statistics
5. **Backup/Restore**: Configuration backup capabilities

The current implementation provides a solid foundation that can be extended based on specific use cases while maintaining good separation of concerns and reusability.
date: 2025-08-30 14:03:01
---
**:Model Instructions:** 

  ---

  
**:You:** Create comprehensive tutorial will walk you through creating reusable, optimized functions for working with Google mail Google Apps Script.

---

**:qwen3-coder:latest:** # Comprehensive Guide to Creating Reusable, Optimized Functions for Google Mail in Google Apps Script

## Table of Contents
1. [Introduction](#introduction)
2. [Setting Up Your Environment](#setting-up-your-environment)
3. [Basic Gmail Operations](#basic-gmail-operations)
4. [Reusable Function Patterns](#reusable-function-patterns)
5. [Optimization Techniques](#optimization-techniques)
6. [Advanced Utilities](#advanced-utilities)
7. [Error Handling and Logging](#error-handling-and-logging)
8. [Best Practices and Performance Tips](#best-practices-and-performance-tips)

## Introduction

Google Apps Script provides powerful integration with Google Mail through the Gmail service, allowing you to automate email tasks, process messages, and create sophisticated email management solutions. This guide will walk you through creating reusable, optimized functions that can be used across multiple projects.

## Setting Up Your Environment

### Prerequisites
- Google Account with access to Gmail
- Basic understanding of JavaScript/TypeScript
- Google Apps Script editor (script.google.com)

### Enable Required Services
```javascript
// In the Apps Script editor, go to:
// Services → Add a service → Gmail API
```

## Basic Gmail Operations

Let's start with fundamental functions that form the foundation for more complex operations:

```javascript
/**
 * Gets all labels in the user's Gmail account
 * @returns {Array<Object>} Array of label objects
 */
function getAllLabels() {
  try {
    const labels = GmailApp.getUserLabels();
    return labels.map(label => ({
      id: label.getId(),
      name: label.getName(),
      threadCount: label.getUnreadThreads().length
    }));
  } catch (error) {
    console.error('Error getting labels:', error);
    return [];
  }
}

/**
 * Gets all threads matching a search query
 * @param {string} query - Gmail search query
 * @returns {Array<Object>} Array of thread objects
 */
function getThreads(query = '') {
  try {
    const threads = GmailApp.search(query);
    return threads.map(thread => ({
      id: thread.getId(),
      subject: thread.getFirstMessageSubject(),
      from: thread.getMessages()[0].getFrom(),
      date: thread.getDate(),
      unread: !thread.isRead()
    }));
  } catch (error) {
    console.error('Error getting threads:', error);
    return [];
  }
}

/**
 * Gets messages from a specific thread
 * @param {string} threadId - Thread ID
 * @returns {Array<Object>} Array of message objects
 */
function getMessagesFromThread(threadId) {
  try {
    const thread = GmailApp.getThreadById(threadId);
    if (!thread) return [];
    
    const messages = thread.getMessages();
    return messages.map(msg => ({
      id: msg.getId(),
      subject: msg.getSubject(),
      from: msg.getFrom(),
      to: msg.getTo(),
      date: msg.getDate(),
      body: msg.getBody()
    }));
  } catch (error) {
    console.error('Error getting thread messages:', error);
    return [];
  }
}
```

## Reusable Function Patterns

### Pattern 1: Configuration-Based Functions
```javascript
/**
 * Generic Gmail operation with configurable parameters
 * @param {Object} config - Configuration object
 * @returns {Object} Operation result
 */
function gmailOperation(config) {
  const defaults = {
    query: '',
    limit: 50,
    action: 'get',
    labels: [],
    includeSpam: false,
    includeTrash: false,
    processFunction: null
  };
  
  const options = Object.assign(defaults, config);
  
  try {
    let results;
    
    switch (options.action) {
      case 'search':
        results = searchGmail(options.query, options.limit, options.includeSpam, options.includeTrash);
        break;
      case 'getLabels':
        results = getAllLabels();
        break;
      default:
        throw new Error('Invalid action specified');
    }
    
    if (options.processFunction && typeof options.processFunction === 'function') {
      return options.processFunction(results);
    }
    
    return results;
  } catch (error) {
    console.error('Gmail operation failed:', error);
    return null;
  }
}

/**
 * Search Gmail with various filters
 * @param {string} query - Search query
 * @param {number} limit - Maximum number of results
 * @param {boolean} includeSpam - Include spam messages
 * @param {boolean} includeTrash - Include trash messages
 * @returns {Array<Object>} Found threads
 */
function searchGmail(query, limit = 50, includeSpam = false, includeTrash = false) {
  try {
    let fullQuery = query;
    
    if (!includeSpam) {
      fullQuery += ' -is:spam';
    }
    
    if (!includeTrash) {
      fullQuery += ' -is:trash';
    }
    
    const threads = GmailApp.search(fullQuery, 0, limit);
    return threads.map(thread => ({
      id: thread.getId(),
      subject: thread.getFirstMessageSubject(),
      from: thread.getMessages()[0].getFrom(),
      date: thread.getDate()
    }));
  } catch (error) {
    console.error('Error searching Gmail:', error);
    return [];
  }
}
```

### Pattern 2: Chainable Functions
```javascript
/**
 * Gmail chainable operation builder
 */
class GmailQueryBuilder {
  constructor() {
    this.query = '';
    this.limit = null;
    this.labels = [];
    this.excludeLabels = [];
  }
  
  /**
   * Add a subject filter
   * @param {string} subject - Subject to match
   * @returns {GmailQueryBuilder}
   */
  withSubject(subject) {
    this.query += `subject:"${subject}" `;
    return this;
  }
  
  /**
   * Add a sender filter
   * @param {string} from - Sender email address
   * @returns {GmailQueryBuilder}
   */
  from(from) {
    this.query += `from:${from} `;
    return this;
  }
  
  /**
   * Add date range filters
   * @param {Date} startDate - Start date
   * @param {Date} endDate - End date
   * @returns {GmailQueryBuilder}
   */
  between(startDate, endDate) {
    const start = Utilities.formatDate(startDate, Session.getScriptTimeZone(), 'yyyy-MM-dd');
    const end = Utilities.formatDate(endDate, Session.getScriptTimeZone(), 'yyyy-MM-dd');
    this.query += `after:${start} before:${end} `;
    return this;
  }
  
  /**
   * Add label filters
   * @param {Array<string>} labels - Label names to include
   * @returns {GmailQueryBuilder}
   */
  withLabels(labels) {
    if (Array.isArray(labels)) {
      labels.forEach(label => {
        this.query += `label:${label} `;
      });
    }
    return this;
  }
  
  /**
   * Set result limit
   * @param {number} limit - Maximum results
   * @returns {GmailQueryBuilder}
   */
  limit(limit) {
    this.limit = limit;
    return this;
  }
  
  /**
   * Execute the search
   * @returns {Array<Object>} Search results
   */
  execute() {
    try {
      let finalQuery = this.query.trim();
      
      if (this.excludeLabels.length > 0) {
        this.excludeLabels.forEach(label => {
          finalQuery += ` -label:${label}`;
        });
      }
      
      const threads = GmailApp.search(finalQuery, 0, this.limit || 50);
      return threads.map(thread => ({
        id: thread.getId(),
        subject: thread.getFirstMessageSubject(),
        from: thread.getMessages()[0].getFrom(),
        date: thread.getDate(),
        labels: thread.getLabels().map(l => l.getName())
      }));
    } catch (error) {
      console.error('Search execution failed:', error);
      return [];
    }
  }
  
  /**
   * Add label exclusion
   * @param {string} label - Label to exclude
   * @returns {GmailQueryBuilder}
   */
  withoutLabel(label) {
    this.excludeLabels.push(label);
    return this;
  }
}

// Usage example:
/*
const results = new GmailQueryBuilder()
  .from('boss@company.com')
  .withSubject('Report')
  .between(new Date(2023, 0, 1), new Date())
  .limit(10)
  .execute();
*/
```

### Pattern 3: Batch Processing Functions
```javascript
/**
 * Process multiple threads in batches to avoid quota limits
 * @param {Array<string>} threadIds - Array of thread IDs
 * @param {Function} processFunction - Function to apply to each thread
 * @param {number} batchSize - Number of items per batch (default 50)
 * @returns {Promise<Array>} Processed results
 */
async function processThreadsInBatch(threadIds, processFunction, batchSize = 50) {
  const results = [];
  
  for (let i = 0; i < threadIds.length; i += batchSize) {
    const batch = threadIds.slice(i, i + batchSize);
    
    try {
      // Process batch
      const batchResults = await Promise.all(
        batch.map(threadId => processFunction(threadId))
      );
      
      results.push(...batchResults);
      
      // Add delay to respect quotas (optional)
      if (i + batchSize < threadIds.length) {
        await Utilities.sleep(1000); // 1 second delay
      }
    } catch (error) {
      console.error('Batch processing failed:', error);
      results.push(null); // or handle as needed
    }
  }
  
  return results;
}

/**
 * Process threads with retry logic
 * @param {string} threadId - Thread ID to process
 * @param {Function} operation - Operation function
 * @param {number} maxRetries - Maximum retry attempts (default 3)
 * @returns {Promise<any>} Result of operation
 */
async function processWithRetry(threadId, operation, maxRetries = 3) {
  let lastError;
  
  for (let attempt = 0; attempt <= maxRetries; attempt++) {
    try {
      return await operation(threadId);
    } catch (error) {
      lastError = error;
      
      if (attempt < maxRetries) {
        // Wait before retry
        await Utilities.sleep(Math.pow(2, attempt) * 1000); // Exponential backoff
      }
    }
  }
  
  throw new Error(`Failed after ${maxRetries} retries: ${lastError.message}`);
}
```

## Reusable Components

### Email Processing Component
```javascript
/**
 * Email processor component for common operations
 */
class EmailProcessor {
  /**
   * Extract email addresses from text
   * @param {string} text - Text to search
   * @returns {Array<string>} Array of email addresses found
   */
  static extractEmails(text) {
    const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g;
    const matches = text.match(emailRegex);
    return matches ? [...new Set(matches)] : [];
  }
  
  /**
   * Parse email from message
   * @param {GmailMessage} message - Gmail message object
   * @returns {Object} Parsed email data
   */
  static parseEmail(message) {
    return {
      id: message.getId(),
      subject: message.getSubject(),
      from: message.getFrom(),
      to: message.getTo(),
      date: message.getDate(),
      body: message.getBody(),
      htmlBody: message.getHtmlBody(),
      attachments: this.parseAttachments(message),
      labels: this.getMessageLabels(message)
    };
  }
  
  /**
   * Parse attachments from a message
   * @param {GmailMessage} message - Gmail message object
   * @returns {Array<Object>} Array of attachment data
   */
  static parseAttachments(message) {
    const attachments = [];
    const msgAttachments = message.getAttachments();
    
    for (let i = 0; i < msgAttachments.length; i++) {
      const attachment = msgAttachments[i];
      attachments.push({
        name: attachment.getName(),
        type: attachment.getMimeType(),
        size: attachment.getSize(),
        url: attachment.getUrl()
      });
    }
    
    return attachments;
  }
  
  /**
   * Get message labels
   * @param {GmailMessage} message - Gmail message object
   * @returns {Array<string>} Array of label names
   */
  static getMessageLabels(message) {
    const labels = [];
    const msgLabels = message.getLabels();
    
    for (let i = 0; i < msgLabels.length; i++) {
      labels.push(msgLabels[i].getName());
    }
    
    return labels;
  }
  
  /**
   * Check if email contains certain keywords
   * @param {GmailMessage} message - Gmail message object
   * @param {Array<string>} keywords - Keywords to search for
   * @returns {boolean} Whether any keyword was found
   */
  static hasKeywords(message, keywords) {
    const content = `${message.getSubject()} ${message.getBody()}`.toLowerCase();
    return keywords.some(keyword => 
      content.includes(keyword.toLowerCase())
    );
  }
}
```

### Gmail Service Component
```javascript
/**
 * Gmail service for common operations with caching and error handling
 */
class GmailService {
  constructor() {
    this.cache = new Map();
    this.cacheTimeout = 300000; // 5 minutes
  }
  
  /**
   * Get threads with caching support
   * @param {string} query - Search query
   * @param {number} maxResults - Maximum results to return
   * @returns {Array<GmailThread>} Threads matching the query
   */
  async getThreads(query, maxResults = 50) {
    const cacheKey = `threads_${query}_${maxResults}`;
    
    // Check cache first
    if (this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.cacheTimeout) {
        return cached.data;
      }
    }
    
    try {
      const threads = await GmailApp.search(query, 0, maxResults);
      
      // Cache the results
      this.cache.set(cacheKey, {
        data: threads,
        timestamp: Date.now()
      });
      
      return threads;
    } catch (error) {
      console.error('Error getting threads:', error);
      throw new Error(`Failed to get threads: ${error.message}`);
    }
  }
  
  /**
   * Move messages to a label
   * @param {Array<GmailMessage>} messages - Messages to move
   * @param {string} labelName - Label name to apply
   * @returns {Promise<void>}
   */
  async moveToLabel(messages, labelName) {
    try {
      const label = this.getLabel(labelName);
      
      for (let i = 0; i < messages.length; i++) {
        await messages[i].addToLabel(label);
      }
      
      console.log(`Moved ${messages.length} messages to "${labelName}"`);
    } catch (error) {
      console.error('Error moving messages:', error);
      throw new Error(`Failed to move messages: ${error.message}`);
    }
  }
  
  /**
   * Get or create a label
   * @param {string} labelName - Name of the label
   * @returns {GmailLabel} Label object
   */
  getLabel(labelName) {
    let label = GmailApp.getUserLabelByName(labelName);
    
    if (!label) {
      console.log(`Creating new label: ${labelName}`);
      label = GmailApp.createLabel(labelName);
    }
    
    return label;
  }
  
  /**
   * Archive threads
   * @param {Array<GmailThread>} threads - Threads to archive
   * @returns {Promise<void>}
   */
  async archiveThreads(threads) {
    try {
      for (let i = 0; i < threads.length; i++) {
        await threads[i].moveToArchive();
      }
      
      console.log(`Archived ${threads.length} threads`);
    } catch (error) {
      console.error('Error archiving threads:', error);
      throw new Error(`Failed to archive threads: ${error.message}`);
    }
  }
  
  /**
   * Clean up cache
   * @returns {void}
   */
  clearCache() {
    this.cache.clear();
  }
}
```

### Configuration Management
```javascript
/**
 * Configuration manager for reusable settings and constants
 */
class ConfigManager {
  static get defaults() {
    return {
      search: {
        maxResults: 50,
        timeout: 30000,
        batchLimit: 100
      },
      
      labels: {
        processed: 'Processed',
        flagged: 'Flagged',
        archive: 'Archive'
      },
      
      keywords: {
        urgent: ['urgent', 'asap', '!important'],
        spam: ['spam', 'junk', 'advertisement'],
        followup: ['follow-up', 'reminder']
      },
      
      email: {
        defaultFrom: '',
        subjectRegex: /^.*\[(.*?)\].*$/,
        attachmentLimit: 10
      }
    };
  }
  
  /**
   * Get configuration value by path
   * @param {string} path - Path to configuration (e.g., 'search.maxResults')
   * @returns {*} Configuration value or undefined
   */
  static get(path) {
    const keys = path.split('.');
    let current = this.defaults;
    
    for (let i = 0; i < keys.length; i++) {
      if (!current || typeof current !== 'object') {
        return undefined;
      }
      
      current = current[keys[i]];
    }
    
    return current;
  }
  
  /**
   * Set configuration value
   * @param {string} path - Path to configuration (e.g., 'search.maxResults')
   * @param {*} value - Value to set
   * @returns {void}
   */
  static set(path, value) {
    const keys = path.split('.');
    let current = this.defaults;
    
    for (let i = 0; i < keys.length - 1; i++) {
      if (!current[keys[i]] || typeof current[keys[i]] !== 'object') {
        current[keys[i]] = {};
      }
      
      current = current[keys[i]];
    }
    
    current[keys[keys.length - 1]] = value;
  }
  
  /**
   * Get labels configuration
   * @returns {Object} Labels configuration
   */
  static getLabels() {
    return this.get('labels');
  }
  
 

