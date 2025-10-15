# Google Apps Script Gmail Functions - Comprehensive Tutorial

> **Professional Gmail automation with class-based architecture for enterprise-level email processing**

---

## 📋 Table of Contents

### 🟢 **Beginner Level**
1. [Setup and Configuration](#1-setup-and-configuration)
2. [Basic Gmail Operations](#2-basic-gmail-operations)
3. [Message Reading and Processing](#3-message-reading-and-processing)
4. [Simple Label Management](#4-simple-label-management)

### 🟡 **Intermediate Level**
5. [Advanced Email Search](#5-advanced-email-search)
6. [Batch Processing and Automation](#6-batch-processing-and-automation)
7. [Attachment Management](#7-attachment-management)
8. [Email Filtering and Organization](#8-email-filtering-and-organization)

### 🔴 **Advanced Level**
9. [Email Analytics and Reporting](#9-email-analytics-and-reporting)
10. [Email Template System](#10-email-template-system)
11. [Advanced Automation Workflows](#11-advanced-automation-workflows)
12. [Complete Project Examples](#12-complete-project-examples)

### 🎯 **Professional Features**
13. [Performance Optimization](#13-performance-optimization)
14. [Error Handling and Logging](#14-error-handling-and-logging)
15. [Security and Best Practices](#15-security-and-best-practices)

---

## 1. Setup and Configuration

### GmailConfig Class - Centralized Configuration Management

```javascript
/**
 * GmailConfig - Centralized configuration management for Gmail automation
 * Handles all configuration settings, API limits, and default values
 * @class
 */
class GmailConfig {
  /**
   * Initialize configuration with default values
   */
  static initialize() {
    this._config = new Map();
    this.loadDefaults();
    console.log('✅ Gmail configuration initialized');
  }

  /**
   * Load default configuration values
   * @private
   */
  static loadDefaults() {
    const defaults = {
      // API and Performance Settings
      API_LIMITS: {
        DAILY_EMAIL_QUOTA: 100,
        SEARCH_LIMIT: 500,
        BATCH_SIZE: 50,
        RETRY_ATTEMPTS: 3,
        RETRY_DELAY: 1000
      },

      // Search Configuration
      SEARCH: {
        DEFAULT_QUERY: 'is:unread',
        MAX_RESULTS: 50,
        INCLUDE_SPAM: false,
        INCLUDE_TRASH: false,
        DATE_FORMAT: 'yyyy-MM-dd'
      },

      // Label Configuration
      LABELS: {
        AUTO_PROCESSED: '🤖 Auto-Processed',
        FLAGGED: '🚩 Flagged',
        ARCHIVE: '📦 Archive',
        URGENT: '⚡ Urgent',
        FOLLOW_UP: '📋 Follow-up',
        SPAM: '🚫 Spam'
      },

      // Email Processing
      PROCESSING: {
        CHUNK_SIZE: 10,
        SLEEP_BETWEEN_CHUNKS: 500,
        MAX_ATTACHMENT_SIZE: 25 * 1024 * 1024, // 25MB
        ALLOWED_ATTACHMENT_TYPES: [
          'application/pdf',
          'text/plain',
          'image/jpeg',
          'image/png',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]
      },

      // Keywords and Patterns
      KEYWORDS: {
        URGENT: ['urgent', 'asap', 'emergency', 'immediate', 'critical'],
        SPAM: ['spam', 'phishing', 'scam', 'fake', 'suspicious'],
        FOLLOW_UP: ['follow-up', 'reminder', 'pending', 'waiting'],
        MEETING: ['meeting', 'appointment', 'calendar', 'schedule', 'zoom']
      },

      // Email Templates
      TEMPLATES: {
        AUTO_REPLY: {
          SUBJECT: 'Auto-Reply: {originalSubject}',
          BODY: 'Thank you for your email. This is an automated response.'
        },
        FORWARD_PREFIX: 'Fwd: ',
        REPLY_PREFIX: 'Re: '
      },

      // Colors for UI and Reports
      COLORS: {
        PRIMARY: '#1a73e8',
        SUCCESS: '#34a853',
        WARNING: '#fbbc05',
        ERROR: '#ea4335',
        INFO: '#9aa0a6'
      }
    };

    for (const [key, value] of Object.entries(defaults)) {
      this._config.set(key, value);
    }
  }

  /**
   * Get configuration value by key
   * @param {string} key - Configuration key
   * @return {*} Configuration value
   */
  static get(key) {
    if (!this._config) {
      this.initialize();
    }
    return this._config.get(key);
  }

  /**
   * Set configuration value
   * @param {string} key - Configuration key
   * @param {*} value - Configuration value
   */
  static set(key, value) {
    if (!this._config) {
      this.initialize();
    }
    this._config.set(key, value);
    console.log(`📝 Configuration updated: ${key}`);
  }

  /**
   * Get nested configuration value using dot notation
   * @param {string} path - Dot notation path (e.g., 'API_LIMITS.BATCH_SIZE')
   * @return {*} Configuration value
   */
  static getPath(path) {
    const keys = path.split('.');
    let current = this.get(keys[0]);
    
    for (let i = 1; i < keys.length && current; i++) {
      current = current[keys[i]];
    }
    
    return current;
  }

  /**
   * Update multiple configuration values
   * @param {Object} updates - Configuration updates
   */
  static update(updates) {
    for (const [key, value] of Object.entries(updates)) {
      this.set(key, value);
    }
  }

  /**
   * Reset configuration to defaults
   */
  static reset() {
    this.initialize();
    console.log('🔄 Configuration reset to defaults');
  }
}

// Initialize global configuration
GMAIL_CONFIG = GmailConfig;
GMAIL_CONFIG.initialize();
```

---

## 2. Basic Gmail Operations

### GmailManager Class - Core Gmail Operations

```javascript
/**
 * GmailManager - Core Gmail operations and thread management
 * Provides basic functionality for accessing and managing Gmail threads and messages
 * @class
 */
class GmailManager {
  constructor() {
    this.config = GMAIL_CONFIG;
  }

  /**
   * Get all user labels with metadata
   * @return {Array<Object>} Array of label objects with statistics
   */
  getUserLabels() {
    try {
      console.log('📋 Getting user labels...');
      
      const labels = GmailApp.getUserLabels();
      const labelData = [];
      
      for (const label of labels) {
        const threads = label.getThreads();
        const unreadCount = label.getUnreadThreads().length;
        
        labelData.push({
          name: label.getName(),
          id: label.getId(),
          totalThreads: threads.length,
          unreadCount: unreadCount,
          color: this.getLabelColor(label.getName()),
          isSystem: this.isSystemLabel(label.getName())
        });
      }
      
      console.log(`✅ Retrieved ${labelData.length} labels`);
      return labelData;
      
    } catch (error) {
      console.error(`❌ Error getting user labels: ${error.message}`);
      throw error;
    }
  }

  /**
   * Search Gmail with comprehensive options
   * @param {string} query - Gmail search query
   * @param {Object} options - Search options
   * @return {Array<Object>} Array of thread objects
   */
  searchEmails(query, options = {}) {
    const {
      maxResults = this.config.getPath('SEARCH.MAX_RESULTS'),
      includeSpam = this.config.getPath('SEARCH.INCLUDE_SPAM'),
      includeTrash = this.config.getPath('SEARCH.INCLUDE_TRASH'),
      startIndex = 0
    } = options;

    try {
      console.log(`🔍 Searching emails with query: "${query}"`);
      
      let searchQuery = query;
      
      // Add spam/trash exclusions if needed
      if (!includeSpam) {
        searchQuery += ' -in:spam';
      }
      if (!includeTrash) {
        searchQuery += ' -in:trash';
      }
      
      const threads = GmailApp.search(searchQuery, startIndex, maxResults);
      const threadData = [];
      
      for (const thread of threads) {
        const firstMessage = thread.getMessages()[0];
        
        threadData.push({
          id: thread.getId(),
          subject: thread.getFirstMessageSubject(),
          snippet: thread.getSnippet(),
          from: firstMessage.getFrom(),
          to: firstMessage.getTo(),
          date: thread.getLastMessageDate(),
          messageCount: thread.getMessageCount(),
          isUnread: thread.isUnread(),
          isImportant: thread.isImportant(),
          hasStarredMessages: thread.hasStarredMessages(),
          labels: thread.getLabels().map(label => label.getName()),
          permalink: thread.getPermalink()
        });
      }
      
      console.log(`✅ Found ${threadData.length} threads`);
      return threadData;
      
    } catch (error) {
      console.error(`❌ Error searching emails: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get thread by ID with full message data
   * @param {string} threadId - Thread ID
   * @return {Object|null} Thread object with messages
   */
  getThreadById(threadId) {
    try {
      console.log(`📧 Getting thread: ${threadId}`);
      
      const thread = GmailApp.getThreadById(threadId);
      if (!thread) {
        console.log('⚠️ Thread not found');
        return null;
      }
      
      const messages = thread.getMessages();
      const messageData = [];
      
      for (const message of messages) {
        messageData.push({
          id: message.getId(),
          subject: message.getSubject(),
          from: message.getFrom(),
          to: message.getTo(),
          cc: message.getCc(),
          bcc: message.getBcc(),
          date: message.getDate(),
          body: message.getPlainBody(),
          htmlBody: message.getHtmlBody(),
          isUnread: message.isUnread(),
          isDraft: message.isDraft(),
          attachments: this.getAttachmentInfo(message),
          headers: this.getImportantHeaders(message)
        });
      }
      
      const threadInfo = {
        id: thread.getId(),
        subject: thread.getFirstMessageSubject(),
        messageCount: thread.getMessageCount(),
        isUnread: thread.isUnread(),
        isImportant: thread.isImportant(),
        labels: thread.getLabels().map(label => label.getName()),
        lastMessageDate: thread.getLastMessageDate(),
        messages: messageData,
        permalink: thread.getPermalink()
      };
      
      console.log(`✅ Thread retrieved with ${messageData.length} messages`);
      return threadInfo;
      
    } catch (error) {
      console.error(`❌ Error getting thread: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get attachment information from a message
   * @param {GmailMessage} message - Gmail message object
   * @return {Array<Object>} Array of attachment info
   * @private
   */
  getAttachmentInfo(message) {
    const attachments = message.getAttachments();
    return attachments.map(attachment => ({
      name: attachment.getName(),
      mimeType: attachment.getContentType(),
      size: attachment.getSize(),
      isGoogleType: attachment.isGoogleType(),
      hash: attachment.getHash()
    }));
  }

  /**
   * Get important headers from a message
   * @param {GmailMessage} message - Gmail message object
   * @return {Object} Important headers
   * @private
   */
  getImportantHeaders(message) {
    return {
      messageId: message.getId(),
      threadId: message.getThread().getId(),
      replyTo: message.getReplyTo(),
      subject: message.getSubject()
    };
  }

  /**
   * Check if label is a system label
   * @param {string} labelName - Label name
   * @return {boolean} Whether it's a system label
   * @private
   */
  isSystemLabel(labelName) {
    const systemLabels = ['INBOX', 'SENT', 'DRAFT', 'SPAM', 'TRASH', 'IMPORTANT', 'STARRED'];
    return systemLabels.includes(labelName.toUpperCase());
  }

  /**
   * Get label color based on name
   * @param {string} labelName - Label name
   * @return {string} Color code
   * @private
   */
  getLabelColor(labelName) {
    const colors = this.config.get('COLORS');
    
    if (labelName.includes('🚩') || labelName.toLowerCase().includes('urgent')) {
      return colors.ERROR;
    }
    if (labelName.includes('✅') || labelName.toLowerCase().includes('done')) {
      return colors.SUCCESS;
    }
    if (labelName.includes('⚠️') || labelName.toLowerCase().includes('warning')) {
      return colors.WARNING;
    }
    
    return colors.PRIMARY;
  }
}

// Create global Gmail manager instance
const GMAIL_MANAGER = new GmailManager();

/**
 * Demo function: Basic Gmail operations
 */
function demonstrateBasicGmailOperations() {
  try {
    console.log('🎯 Starting Basic Gmail Operations Demo...');
    
    console.log('1. Getting user labels...');
    const labels = GMAIL_MANAGER.getUserLabels();
    console.log(`📋 Found labels:`, labels.slice(0, 5).map(l => l.name));
    
    console.log('2. Searching for unread emails...');
    const unreadEmails = GMAIL_MANAGER.searchEmails('is:unread', { maxResults: 5 });
    console.log(`📧 Found ${unreadEmails.length} unread emails`);
    
    if (unreadEmails.length > 0) {
      console.log('3. Getting first thread details...');
      const threadDetails = GMAIL_MANAGER.getThreadById(unreadEmails[0].id);
      console.log(`🔍 Thread "${threadDetails.subject}" has ${threadDetails.messageCount} messages`);
    }
    
    console.log('4. Searching for emails from last week...');
    const lastWeek = new Date();
    lastWeek.setDate(lastWeek.getDate() - 7);
    const dateQuery = `after:${lastWeek.getFullYear()}-${(lastWeek.getMonth() + 1).toString().padStart(2, '0')}-${lastWeek.getDate().toString().padStart(2, '0')}`;
    const recentEmails = GMAIL_MANAGER.searchEmails(dateQuery, { maxResults: 10 });
    console.log(`📅 Found ${recentEmails.length} emails from last week`);
    
    console.log('✅ Basic Gmail Operations demo completed successfully');
    return { labels, unreadEmails, threadDetails, recentEmails };
    
  } catch (error) {
    console.error('❌ Basic Gmail Operations demo failed:', error.message);
    throw error;
  }
}
```
  
  ---

## 5. Advanced Email Search

### GmailSearcher Class - Advanced Search Capabilities

```javascript
/**
 * GmailSearcher - Advanced email search with query building and filtering
 * Provides sophisticated search capabilities with caching and optimization
 * @class
 */
class GmailSearcher {
  constructor() {
    this.config = GMAIL_CONFIG;
    this.searchCache = new Map();
    this.cacheTimeout = 300000; // 5 minutes
  }

  /**
   * Build complex search query using fluent interface
   * @return {GmailQueryBuilder} Query builder instance
   */
  buildQuery() {
    return new GmailQueryBuilder(this);
  }

  /**
   * Search emails with advanced options and caching
   * @param {string} query - Search query
   * @param {Object} options - Search options
   * @return {Array<Object>} Search results
   */
  search(query, options = {}) {
    const {
      maxResults = this.config.getPath('SEARCH.MAX_RESULTS'),
      useCache = true,
      includeSpam = this.config.getPath('SEARCH.INCLUDE_SPAM'),
      includeTrash = this.config.getPath('SEARCH.INCLUDE_TRASH'),
      sortBy = 'date',
      sortOrder = 'desc'
    } = options;

    try {
      console.log(`🔍 Searching with query: "${query}"`);
      
      const cacheKey = `${query}_${maxResults}_${includeSpam}_${includeTrash}`;
      
      // Check cache first
      if (useCache && this.searchCache.has(cacheKey)) {
        const cached = this.searchCache.get(cacheKey);
        if (Date.now() - cached.timestamp < this.cacheTimeout) {
          console.log('📦 Returning cached results');
          return this.sortResults(cached.data, sortBy, sortOrder);
        }
      }
      
      // Build search query
      let searchQuery = query;
      if (!includeSpam) searchQuery += ' -in:spam';
      if (!includeTrash) searchQuery += ' -in:trash';
      
      // Execute search
      const threads = GmailApp.search(searchQuery, 0, maxResults);
      const results = this.processThreadResults(threads);
      
      // Cache results
      if (useCache) {
        this.searchCache.set(cacheKey, {
          data: results,
          timestamp: Date.now()
        });
      }
      
      console.log(`✅ Found ${results.length} results`);
      return this.sortResults(results, sortBy, sortOrder);
      
    } catch (error) {
      console.error(`❌ Search failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Find emails by sender with advanced filtering
   * @param {string} sender - Sender email or name
   * @param {Object} options - Filter options
   * @return {Array<Object>} Matching emails
   */
  findBySender(sender, options = {}) {
    const {
      dateRange = null,
      hasAttachments = null,
      isUnread = null,
      maxResults = 50
    } = options;

    let query = `from:${sender}`;
    
    if (dateRange) {
      const { start, end } = dateRange;
      const startStr = Utilities.formatDate(start, Session.getScriptTimeZone(), 'yyyy/MM/dd');
      const endStr = Utilities.formatDate(end, Session.getScriptTimeZone(), 'yyyy/MM/dd');
      query += ` after:${startStr} before:${endStr}`;
    }
    
    if (hasAttachments !== null) {
      query += hasAttachments ? ' has:attachment' : ' -has:attachment';
    }
    
    if (isUnread !== null) {
      query += isUnread ? ' is:unread' : ' -is:unread';
    }
    
    return this.search(query, { maxResults });
  }

  /**
   * Find emails with specific attachments
   * @param {Object} criteria - Attachment criteria
   * @return {Array<Object>} Matching emails
   */
  findWithAttachments(criteria = {}) {
    const {
      fileTypes = [],
      minSize = null,
      maxSize = null,
      fileName = null
    } = criteria;

    let query = 'has:attachment';
    
    if (fileName) {
      query += ` filename:${fileName}`;
    }
    
    if (fileTypes.length > 0) {
      query += ` (${fileTypes.map(type => `filename:${type}`).join(' OR ')})`;
    }
    
    const results = this.search(query);
    
    // Post-process for size filtering
    if (minSize || maxSize) {
      return results.filter(result => {
        const threadDetails = GMAIL_MANAGER.getThreadById(result.id);
        return threadDetails.messages.some(message => {
          return message.attachments.some(attachment => {
            const size = attachment.size;
            return (!minSize || size >= minSize) && (!maxSize || size <= maxSize);
          });
        });
      });
    }
    
    return results;
  }

  /**
   * Search for emails matching multiple keywords
   * @param {Array<string>} keywords - Keywords to search for
   * @param {string} operator - Search operator ('AND' or 'OR')
   * @param {Object} options - Additional options
   * @return {Array<Object>} Matching emails
   */
  findByKeywords(keywords, operator = 'OR', options = {}) {
    const { searchIn = 'subject', maxResults = 50 } = options;
    
    let query = '';
    
    if (operator === 'AND') {
      query = keywords.map(keyword => `${searchIn}:"${keyword}"`).join(' ');
    } else {
      query = `${searchIn}:(${keywords.map(k => `"${k}"`).join(' OR ')})`;
    }
    
    return this.search(query, { maxResults });
  }

  /**
   * Find duplicate emails based on subject and sender
   * @param {Object} options - Duplicate detection options
   * @return {Array<Object>} Groups of duplicate emails
   */
  findDuplicates(options = {}) {
    const { maxResults = 500, timeRange = 30 } = options;
    
    try {
      console.log('🔍 Searching for duplicate emails...');
      
      // Search recent emails
      const cutoffDate = new Date();
      cutoffDate.setDate(cutoffDate.getDate() - timeRange);
      const dateStr = Utilities.formatDate(cutoffDate, Session.getScriptTimeZone(), 'yyyy/MM/dd');
      
      const emails = this.search(`after:${dateStr}`, { maxResults });
      
      // Group by subject and sender
      const groups = new Map();
      
      for (const email of emails) {
        const key = `${email.subject.replace(/^(Re|Fwd?):\s*/i, '')}_${email.from}`;
        
        if (!groups.has(key)) {
          groups.set(key, []);
        }
        groups.get(key).push(email);
      }
      
      // Filter groups with more than one email
      const duplicates = [];
      for (const [key, group] of groups) {
        if (group.length > 1) {
          duplicates.push({
            subject: group[0].subject,
            sender: group[0].from,
            count: group.length,
            emails: group
          });
        }
      }
      
      console.log(`✅ Found ${duplicates.length} groups of duplicates`);
      return duplicates.sort((a, b) => b.count - a.count);
      
    } catch (error) {
      console.error(`❌ Error finding duplicates: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process thread results into standardized format
   * @param {Array<GmailThread>} threads - Gmail thread objects
   * @return {Array<Object>} Processed results
   * @private
   */
  processThreadResults(threads) {
    return threads.map(thread => {
      const firstMessage = thread.getMessages()[0];
      const lastMessage = thread.getMessages()[thread.getMessageCount() - 1];
      
      return {
        id: thread.getId(),
        subject: thread.getFirstMessageSubject(),
        snippet: thread.getSnippet(),
        from: firstMessage.getFrom(),
        to: firstMessage.getTo(),
        firstMessageDate: thread.getFirstMessageDate(),
        lastMessageDate: thread.getLastMessageDate(),
        messageCount: thread.getMessageCount(),
        isUnread: thread.isUnread(),
        isImportant: thread.isImportant(),
        hasStarredMessages: thread.hasStarredMessages(),
        labels: thread.getLabels().map(label => label.getName()),
        hasAttachments: thread.getMessages().some(msg => msg.getAttachments().length > 0)
      };
    });
  }

  /**
   * Sort search results
   * @param {Array<Object>} results - Search results
   * @param {string} sortBy - Sort field
   * @param {string} sortOrder - Sort order ('asc' or 'desc')
   * @return {Array<Object>} Sorted results
   * @private
   */
  sortResults(results, sortBy, sortOrder) {
    const sortField = sortBy === 'date' ? 'lastMessageDate' : sortBy;
    
    return results.sort((a, b) => {
      let aVal = a[sortField];
      let bVal = b[sortField];
      
      if (sortField.includes('Date')) {
        aVal = new Date(aVal);
        bVal = new Date(bVal);
      }
      
      if (sortOrder === 'desc') {
        return bVal > aVal ? 1 : bVal < aVal ? -1 : 0;
      } else {
        return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
      }
    });
  }

  /**
   * Clear search cache
   */
  clearCache() {
    this.searchCache.clear();
    console.log('🧹 Search cache cleared');
  }
}

/**
 * GmailQueryBuilder - Fluent interface for building search queries
 * @class
 */
class GmailQueryBuilder {
  constructor(searcher) {
    this.searcher = searcher;
    this.query = '';
    this.options = {};
  }

  /**
   * Add sender filter
   * @param {string} sender - Sender email or name
   * @return {GmailQueryBuilder} Builder instance
   */
  from(sender) {
    this.query += `from:${sender} `;
    return this;
  }

  /**
   * Add recipient filter
   * @param {string} recipient - Recipient email
   * @return {GmailQueryBuilder} Builder instance
   */
  to(recipient) {
    this.query += `to:${recipient} `;
    return this;
  }

  /**
   * Add subject filter
   * @param {string} subject - Subject text
   * @return {GmailQueryBuilder} Builder instance
   */
  subject(subject) {
    this.query += `subject:"${subject}" `;
    return this;
  }

  /**
   * Add label filter
   * @param {string} label - Label name
   * @return {GmailQueryBuilder} Builder instance
   */
  hasLabel(label) {
    this.query += `label:${label} `;
    return this;
  }

  /**
   * Exclude label
   * @param {string} label - Label name to exclude
   * @return {GmailQueryBuilder} Builder instance
   */
  withoutLabel(label) {
    this.query += `-label:${label} `;
    return this;
  }

  /**
   * Add date range filter
   * @param {Date} startDate - Start date
   * @param {Date} endDate - End date (optional)
   * @return {GmailQueryBuilder} Builder instance
   */
  dateRange(startDate, endDate = null) {
    const startStr = Utilities.formatDate(startDate, Session.getScriptTimeZone(), 'yyyy/MM/dd');
    this.query += `after:${startStr} `;
    
    if (endDate) {
      const endStr = Utilities.formatDate(endDate, Session.getScriptTimeZone(), 'yyyy/MM/dd');
      this.query += `before:${endStr} `;
    }
    
    return this;
  }

  /**
   * Filter for unread messages
   * @return {GmailQueryBuilder} Builder instance
   */
  unread() {
    this.query += 'is:unread ';
    return this;
  }

  /**
   * Filter for messages with attachments
   * @return {GmailQueryBuilder} Builder instance
   */
  hasAttachments() {
    this.query += 'has:attachment ';
    return this;
  }

  /**
   * Filter for important messages
   * @return {GmailQueryBuilder} Builder instance
   */
  important() {
    this.query += 'is:important ';
    return this;
  }

  /**
   * Set maximum results
   * @param {number} max - Maximum number of results
   * @return {GmailQueryBuilder} Builder instance
   */
  limit(max) {
    this.options.maxResults = max;
    return this;
  }

  /**
   * Execute the search
   * @return {Array<Object>} Search results
   */
  execute() {
    return this.searcher.search(this.query.trim(), this.options);
  }
}

// Create global Gmail searcher instance
const GMAIL_SEARCHER = new GmailSearcher();
```

---

## 6. Batch Processing and Automation

### GmailBatchProcessor Class - Efficient Bulk Operations

```javascript
/**
 * GmailBatchProcessor - Efficient batch processing for bulk Gmail operations
 * Handles large-scale email processing with rate limiting and error recovery
 * @class
 */
class GmailBatchProcessor {
  constructor() {
    this.config = GMAIL_CONFIG;
    this.processingQueue = [];
    this.isProcessing = false;
    this.metrics = {
      totalProcessed: 0,
      successCount: 0,
      errorCount: 0,
      startTime: null,
      endTime: null
    };
  }

  /**
   * Process threads in batches with rate limiting
   * @param {Array<string>} threadIds - Array of thread IDs
   * @param {Function} processingFunction - Function to process each thread
   * @param {Object} options - Processing options
   * @return {Object} Processing results
   */
  async processThreads(threadIds, processingFunction, options = {}) {
    const {
      batchSize = this.config.getPath('API_LIMITS.BATCH_SIZE'),
      delayBetweenBatches = this.config.getPath('PROCESSING.SLEEP_BETWEEN_CHUNKS'),
      maxRetries = this.config.getPath('API_LIMITS.RETRY_ATTEMPTS'),
      continueOnError = true
    } = options;

    try {
      console.log(`⚡ Starting batch processing of ${threadIds.length} threads...`);
      this.initializeMetrics();
      this.isProcessing = true;
      
      const results = [];
      const errors = [];
      
      // Process in batches
      for (let i = 0; i < threadIds.length; i += batchSize) {
        const batch = threadIds.slice(i, i + batchSize);
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1} (${batch.length} items)`);
        
        const batchResults = await this.processBatch(
          batch, 
          processingFunction, 
          maxRetries, 
          continueOnError
        );
        
        results.push(...batchResults.successes);
        errors.push(...batchResults.errors);
        
        // Update metrics
        this.metrics.successCount += batchResults.successes.length;
        this.metrics.errorCount += batchResults.errors.length;
        this.metrics.totalProcessed += batch.length;
        
        // Rate limiting delay
        if (i + batchSize < threadIds.length) {
          console.log(`⏱️ Waiting ${delayBetweenBatches}ms before next batch...`);
          await this.sleep(delayBetweenBatches);
        }
      }
      
      this.metrics.endTime = new Date();
      this.isProcessing = false;
      
      const summary = {
        totalThreads: threadIds.length,
        successCount: this.metrics.successCount,
        errorCount: this.metrics.errorCount,
        processingTime: this.metrics.endTime - this.metrics.startTime,
        results: results,
        errors: errors
      };
      
      console.log('✅ Batch processing completed:', summary);
      return summary;
      
    } catch (error) {
      this.isProcessing = false;
      console.error(`❌ Batch processing failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process a single batch of threads
   * @param {Array<string>} threadIds - Thread IDs in the batch
   * @param {Function} processingFunction - Processing function
   * @param {number} maxRetries - Maximum retry attempts
   * @param {boolean} continueOnError - Whether to continue on errors
   * @return {Object} Batch results
   * @private
   */
  async processBatch(threadIds, processingFunction, maxRetries, continueOnError) {
    const successes = [];
    const errors = [];
    
    for (const threadId of threadIds) {
      try {
        const result = await this.processWithRetry(threadId, processingFunction, maxRetries);
        successes.push({ threadId, result });
        
      } catch (error) {
        const errorInfo = {
          threadId,
          error: error.message,
          timestamp: new Date()
        };
        
        errors.push(errorInfo);
        console.warn(`⚠️ Error processing thread ${threadId}: ${error.message}`);
        
        if (!continueOnError) {
          throw error;
        }
      }
    }
    
    return { successes, errors };
  }

  /**
   * Process thread with retry logic
   * @param {string} threadId - Thread ID
   * @param {Function} processingFunction - Processing function
   * @param {number} maxRetries - Maximum retry attempts
   * @return {*} Processing result
   * @private
   */
  async processWithRetry(threadId, processingFunction, maxRetries) {
    let lastError;
    
    for (let attempt = 1; attempt <= maxRetries; attempt++) {
      try {
        return await processingFunction(threadId);
        
      } catch (error) {
        lastError = error;
        
        if (attempt < maxRetries) {
          const delay = Math.pow(2, attempt - 1) * 1000; // Exponential backoff
          console.log(`🔄 Retry ${attempt}/${maxRetries} for thread ${threadId} in ${delay}ms`);
          await this.sleep(delay);
        }
      }
    }
    
    throw lastError;
  }

  /**
   * Bulk archive threads
   * @param {Array<string>} threadIds - Thread IDs to archive
   * @param {Object} options - Archive options
   * @return {Object} Archive results
   */
  async bulkArchive(threadIds, options = {}) {
    console.log(`📦 Bulk archiving ${threadIds.length} threads...`);
    
    const archiveFunction = async (threadId) => {
      const thread = GmailApp.getThreadById(threadId);
      if (thread) {
        thread.moveToArchive();
        return { action: 'archived', threadId };
      }
      throw new Error('Thread not found');
    };
    
    return await this.processThreads(threadIds, archiveFunction, options);
  }

  /**
   * Bulk label application
   * @param {Array<string>} threadIds - Thread IDs
   * @param {string} labelName - Label to apply
   * @param {Object} options - Processing options
   * @return {Object} Processing results
   */
  async bulkLabel(threadIds, labelName, options = {}) {
    console.log(`🏷️ Bulk labeling ${threadIds.length} threads with "${labelName}"...`);
    
    const label = LABEL_MANAGER.createLabel(labelName);
    
    const labelFunction = async (threadId) => {
      const thread = GmailApp.getThreadById(threadId);
      if (thread) {
        thread.addLabel(label);
        return { action: 'labeled', threadId, label: labelName };
      }
      throw new Error('Thread not found');
    };
    
    return await this.processThreads(threadIds, labelFunction, options);
  }

  /**
   * Bulk thread deletion (move to trash)
   * @param {Array<string>} threadIds - Thread IDs to delete
   * @param {Object} options - Delete options
   * @return {Object} Delete results
   */
  async bulkDelete(threadIds, options = {}) {
    console.log(`🗑️ Bulk deleting ${threadIds.length} threads...`);
    
    const deleteFunction = async (threadId) => {
      const thread = GmailApp.getThreadById(threadId);
      if (thread) {
        thread.moveToTrash();
        return { action: 'deleted', threadId };
      }
      throw new Error('Thread not found');
    };
    
    return await this.processThreads(threadIds, deleteFunction, options);
  }

  /**
   * Schedule automated email processing
   * @param {Object} schedule - Schedule configuration
   */
  scheduleProcessing(schedule) {
    const {
      searchQuery = 'is:unread',
      processFunction,
      interval = 'hourly',
      triggerName = 'AutoEmailProcessor'
    } = schedule;

    try {
      console.log(`⏰ Scheduling email processing: ${interval}`);
      
      // Delete existing trigger if it exists
      this.deleteScheduledTrigger(triggerName);
      
      // Create new trigger
      let trigger;
      switch (interval) {
        case 'hourly':
          trigger = ScriptApp.newTrigger('scheduledEmailProcessing')
            .timeBased()
            .everyHours(1)
            .create();
          break;
        case 'daily':
          trigger = ScriptApp.newTrigger('scheduledEmailProcessing')
            .timeBased()
            .everyDays(1)
            .atHour(9) // 9 AM
            .create();
          break;
        case 'weekly':
          trigger = ScriptApp.newTrigger('scheduledEmailProcessing')
            .timeBased()
            .everyWeeks(1)
            .onWeekDay(ScriptApp.WeekDay.MONDAY)
            .atHour(9)
            .create();
          break;
      }
      
      // Store schedule configuration
      PropertiesService.getScriptProperties().setProperties({
        'SCHEDULED_QUERY': searchQuery,
        'SCHEDULED_FUNCTION': processFunction.name,
        'TRIGGER_ID': trigger.getUniqueId()
      });
      
      console.log(`✅ Processing scheduled: ${interval} at trigger ID ${trigger.getUniqueId()}`);
      
    } catch (error) {
      console.error(`❌ Error scheduling processing: ${error.message}`);
      throw error;
    }
  }

  /**
   * Delete scheduled trigger
   * @param {string} triggerName - Trigger name
   * @private
   */
  deleteScheduledTrigger(triggerName) {
    const triggers = ScriptApp.getProjectTriggers();
    for (const trigger of triggers) {
      if (trigger.getHandlerFunction() === triggerName) {
        ScriptApp.deleteTrigger(trigger);
        console.log(`🗑️ Deleted existing trigger: ${triggerName}`);
      }
    }
  }

  /**
   * Initialize processing metrics
   * @private
   */
  initializeMetrics() {
    this.metrics = {
      totalProcessed: 0,
      successCount: 0,
      errorCount: 0,
      startTime: new Date(),
      endTime: null
    };
  }

  /**
   * Sleep utility function
   * @param {number} ms - Milliseconds to sleep
   * @return {Promise} Sleep promise
   * @private
   */
  sleep(ms) {
    return new Promise(resolve => {
      Utilities.sleep(ms);
      resolve();
    });
  }

  /**
   * Get processing metrics
   * @return {Object} Current metrics
   */
  getMetrics() {
    return { ...this.metrics };
  }

  /**
   * Reset metrics
   */
  resetMetrics() {
    this.initializeMetrics();
    console.log('📊 Metrics reset');
  }
}

// Create global batch processor instance
const GMAIL_BATCH_PROCESSOR = new GmailBatchProcessor();

/**
 * Scheduled email processing function (called by triggers)
 */
function scheduledEmailProcessing() {
  try {
    const properties = PropertiesService.getScriptProperties().getProperties();
    const query = properties.SCHEDULED_QUERY || 'is:unread';
    
    console.log('⏰ Running scheduled email processing...');
    
    // Search for emails to process
    const emails = GMAIL_SEARCHER.search(query, { maxResults: 100 });
    
    if (emails.length > 0) {
      console.log(`📧 Found ${emails.length} emails to process`);
      
      // Auto-organize emails
      const threadIds = emails.map(email => email.id);
      const organizationResults = LABEL_MANAGER.organizeThreads(threadIds);
      
      console.log('✅ Scheduled processing completed:', organizationResults);
    } else {
      console.log('📭 No emails found to process');
    }
    
  } catch (error) {
    console.error('❌ Scheduled processing failed:', error.message);
    // Optionally send error notification email
  }
}
```

---

## 7. Attachment Management

### AttachmentManager Class - File Handling and Processing

```javascript
/**
 * AttachmentManager - Comprehensive attachment management
 * Handles attachment extraction, processing, and storage with security checks
 * @class
 */
class AttachmentManager {
  constructor() {
    this.config = GMAIL_CONFIG;
  }

  /**
   * Extract all attachments from a thread
   * @param {string} threadId - Thread ID
   * @param {Object} options - Extraction options
   * @return {Array<Object>} Array of attachment data
   */
  extractAttachments(threadId, options = {}) {
    const {
      saveToDrive = false,
      driveFolder = null,
      filterByType = null,
      maxSize = this.config.getPath('PROCESSING.MAX_ATTACHMENT_SIZE')
    } = options;

    try {
      console.log(`📎 Extracting attachments from thread: ${threadId}`);
      
      const thread = GmailApp.getThreadById(threadId);
      if (!thread) {
        throw new Error('Thread not found');
      }
      
      const messages = thread.getMessages();
      const attachments = [];
      
      for (let i = 0; i < messages.length; i++) {
        const message = messages[i];
        const msgAttachments = message.getAttachments();
        
        for (let j = 0; j < msgAttachments.length; j++) {
          const attachment = msgAttachments[j];
          
          // Security and size checks
          if (!this.isAttachmentSafe(attachment)) {
            console.warn(`⚠️ Skipping unsafe attachment: ${attachment.getName()}`);
            continue;
          }
          
          if (attachment.getSize() > maxSize) {
            console.warn(`⚠️ Skipping large attachment: ${attachment.getName()} (${attachment.getSize()} bytes)`);
            continue;
          }
          
          // Type filtering
          if (filterByType && !this.matchesTypeFilter(attachment, filterByType)) {
            continue;
          }
          
          const attachmentData = {
            messageIndex: i,
            attachmentIndex: j,
            name: attachment.getName(),
            mimeType: attachment.getContentType(),
            size: attachment.getSize(),
            hash: attachment.getHash(),
            isGoogleType: attachment.isGoogleType(),
            extension: this.getFileExtension(attachment.getName()),
            sizeFormatted: this.formatFileSize(attachment.getSize()),
            isSafe: true,
            data: attachment // Store the actual attachment object
          };
          
          // Save to Drive if requested
          if (saveTorive) {
            try {
              attachmentData.driveFile = this.saveAttachmentToDrive(
                attachment, 
                driveFolder, 
                attachmentData
              );
            } catch (error) {
              console.warn(`⚠️ Failed to save to Drive: ${error.message}`);
              attachmentData.driveError = error.message;
            }
          }
          
          attachments.push(attachmentData);
        }
      }
      
      console.log(`✅ Extracted ${attachments.length} attachments`);
      return attachments;
      
    } catch (error) {
      console.error(`❌ Error extracting attachments: ${error.message}`);
      throw error;
    }
  }

  /**
   * Save attachment to Google Drive
   * @param {GmailAttachment} attachment - Attachment object
   * @param {string} folderId - Drive folder ID (optional)
   * @param {Object} metadata - Attachment metadata
   * @return {Object} Drive file information
   */
  saveAttachmentToDrive(attachment, folderId = null, metadata = {}) {
    try {
      console.log(`💾 Saving attachment to Drive: ${attachment.getName()}`);
      
      const blob = attachment.copyBlob();
      let folder = DriveApp.getRootFolder();
      
      if (folderId) {
        folder = DriveApp.getFolderById(folderId);
      } else {
        // Create default folder structure
        folder = this.getOrCreateFolder('Gmail Attachments');
      }
      
      // Create unique filename to avoid conflicts
      const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
      const fileName = `${timestamp}_${attachment.getName()}`;
      
      const file = folder.createFile(blob.setName(fileName));
      
      // Set file metadata
      file.setDescription(`Extracted from Gmail thread. Original name: ${attachment.getName()}`);
      
      const fileInfo = {
        id: file.getId(),
        name: file.getName(),
        originalName: attachment.getName(),
        url: file.getUrl(),
        downloadUrl: file.getDownloadUrl(),
        size: file.getSize(),
        mimeType: file.getMimeType(),
        createdDate: file.getCreatedDate(),
        folder: folder.getName(),
        folderId: folder.getId()
      };
      
      console.log(`✅ Attachment saved to Drive: ${fileName}`);
      return fileInfo;
      
    } catch (error) {
      console.error(`❌ Error saving to Drive: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process attachments by type (e.g., images, documents, etc.)
   * @param {Array<Object>} attachments - Array of attachment data
   * @return {Object} Processed attachments grouped by type
   */
  processAttachmentsByType(attachments) {
    try {
      console.log(`🔄 Processing ${attachments.length} attachments by type...`);
      
      const processed = {
        images: [],
        documents: [],
        spreadsheets: [],
        presentations: [],
        pdfs: [],
        archives: [],
        other: []
      };
      
      for (const attachment of attachments) {
        const category = this.categorizeAttachment(attachment);
        
        // Add processing results based on type
        const processedAttachment = {
          ...attachment,
          category,
          processed: true,
          processingDate: new Date()
        };
        
        // Type-specific processing
        switch (category) {
          case 'images':
            processedAttachment.imageInfo = this.processImage(attachment);
            break;
          case 'documents':
            processedAttachment.textContent = this.extractTextContent(attachment);
            break;
          case 'pdfs':
            processedAttachment.pageCount = this.getPdfPageCount(attachment);
            break;
        }
        
        processed[category].push(processedAttachment);
      }
      
      const summary = Object.entries(processed)
        .map(([type, items]) => `${type}: ${items.length}`)
        .join(', ');
      
      console.log(`✅ Processing complete: ${summary}`);
      return processed;
      
    } catch (error) {
      console.error(`❌ Error processing attachments: ${error.message}`);
      throw error;
    }
  }

  /**
   * Scan attachments for security threats
   * @param {Array<Object>} attachments - Attachments to scan
   * @return {Object} Security scan results
   */
  scanAttachmentsForThreats(attachments) {
    try {
      console.log(`🔒 Scanning ${attachments.length} attachments for threats...`);
      
      const results = {
        safe: [],
        suspicious: [],
        dangerous: [],
        totalScanned: attachments.length
      };
      
      for (const attachment of attachments) {
        const threats = this.detectThreats(attachment);
        
        const scanResult = {
          ...attachment,
          threats,
          riskLevel: this.calculateRiskLevel(threats),
          scanDate: new Date()
        };
        
        if (threats.length === 0) {
          results.safe.push(scanResult);
        } else if (this.isHighRisk(threats)) {
          results.dangerous.push(scanResult);
        } else {
          results.suspicious.push(scanResult);
        }
      }
      
      console.log(`✅ Security scan complete:`, {
        safe: results.safe.length,
        suspicious: results.suspicious.length,
        dangerous: results.dangerous.length
      });
      
      return results;
      
    } catch (error) {
      console.error(`❌ Error scanning attachments: ${error.message}`);
      throw error;
    }
  }

  /**
   * Check if attachment is safe based on type and size
   * @param {GmailAttachment} attachment - Attachment to check
   * @return {boolean} Whether attachment is safe
   * @private
   */
  isAttachmentSafe(attachment) {
    const allowedTypes = this.config.getPath('PROCESSING.ALLOWED_ATTACHMENT_TYPES');
    const maxSize = this.config.getPath('PROCESSING.MAX_ATTACHMENT_SIZE');
    
    // Check file type
    const mimeType = attachment.getContentType();
    if (!allowedTypes.includes(mimeType)) {
      return false;
    }
    
    // Check file size
    if (attachment.getSize() > maxSize) {
      return false;
    }
    
    // Check file extension
    const fileName = attachment.getName();
    const dangerousExtensions = ['exe', 'bat', 'cmd', 'scr', 'vbs', 'js'];
    const extension = this.getFileExtension(fileName);
    
    return !dangerousExtensions.includes(extension);
  }

  /**
   * Categorize attachment by type
   * @param {Object} attachment - Attachment data
   * @return {string} Category name
   * @private
   */
  categorizeAttachment(attachment) {
    const mimeType = attachment.mimeType.toLowerCase();
    
    if (mimeType.startsWith('image/')) return 'images';
    if (mimeType.includes('pdf')) return 'pdfs';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'documents';
    if (mimeType.includes('sheet') || mimeType.includes('excel')) return 'spreadsheets';
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) return 'presentations';
    if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('tar')) return 'archives';
    
    return 'other';
  }

  /**
   * Get or create Drive folder
   * @param {string} folderName - Folder name
   * @param {DriveApp.Folder} parent - Parent folder (optional)
   * @return {DriveApp.Folder} Folder object
   * @private
   */
  getOrCreateFolder(folderName, parent = null) {
    const parentFolder = parent || DriveApp.getRootFolder();
    const folders = parentFolder.getFoldersByName(folderName);
    
    if (folders.hasNext()) {
      return folders.next();
    } else {
      return parentFolder.createFolder(folderName);
    }
  }

  /**
   * Get file extension from filename
   * @param {string} fileName - File name
   * @return {string} File extension
   * @private
   */
  getFileExtension(fileName) {
    const lastDot = fileName.lastIndexOf('.');
    return lastDot > 0 ? fileName.substring(lastDot + 1).toLowerCase() : '';
  }

  /**
   * Format file size in human-readable format
   * @param {number} bytes - File size in bytes
   * @return {string} Formatted size
   * @private
   */
  formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  /**
   * Detect potential threats in attachment
   * @param {Object} attachment - Attachment data
   * @return {Array<string>} Array of detected threats
   * @private
   */
  detectThreats(attachment) {
    const threats = [];
    
    // Check file extension
    const dangerousExtensions = ['exe', 'bat', 'cmd', 'scr', 'vbs', 'js', 'jar'];
    if (dangerousExtensions.includes(attachment.extension)) {
      threats.push('Executable file type');
    }
    
    // Check file name patterns
    const suspiciousNames = ['invoice', 'receipt', 'document', 'file', 'update', 'install'];
    const fileName = attachment.name.toLowerCase();
    if (suspiciousNames.some(name => fileName.includes(name)) && 
        dangerousExtensions.includes(attachment.extension)) {
      threats.push('Suspicious filename pattern');
    }
    
    // Check for double extensions
    const dotCount = (attachment.name.match(/\./g) || []).length;
    if (dotCount > 1) {
      threats.push('Multiple file extensions');
    }
    
    return threats;
  }

  /**
   * Calculate risk level based on threats
   * @param {Array<string>} threats - Detected threats
   * @return {string} Risk level
   * @private
   */
  calculateRiskLevel(threats) {
    if (threats.length === 0) return 'low';
    if (threats.length >= 2 || threats.some(t => t.includes('Executable'))) return 'high';
    return 'medium';
  }

  /**
   * Check if threats indicate high risk
   * @param {Array<string>} threats - Detected threats
   * @return {boolean} Whether threats are high risk
   * @private
   */
  isHighRisk(threats) {
    return this.calculateRiskLevel(threats) === 'high';
  }

  /**
   * Match attachment against type filter
   * @param {GmailAttachment} attachment - Attachment object
   * @param {Array<string>} typeFilter - Type filter
   * @return {boolean} Whether attachment matches filter
   * @private
   */
  matchesTypeFilter(attachment, typeFilter) {
    const mimeType = attachment.getContentType().toLowerCase();
    const extension = this.getFileExtension(attachment.getName());
    
    return typeFilter.some(filter => 
      mimeType.includes(filter.toLowerCase()) || 
      extension === filter.toLowerCase()
    );
  }

  // Placeholder methods for advanced processing
  processImage(attachment) { return { processed: true }; }
  extractTextContent(attachment) { return 'Text extraction not implemented'; }
  getPdfPageCount(attachment) { return 1; }
}

---

## 10. Email Template System

### EmailTemplateManager Class - Dynamic Template System

```javascript
/**
 * EmailTemplateManager - Dynamic email template creation and management
 * Provides comprehensive template system with variable substitution and styling
 * @class
 */
class EmailTemplateManager {
  constructor() {
    this.config = GMAIL_CONFIG;
    this.templates = new Map();
    this.loadDefaultTemplates();
  }

  /**
   * Load default email templates
   * @private
   */
  loadDefaultTemplates() {
    // Auto-reply template
    this.createTemplate('auto-reply', {
      subject: 'Re: {{originalSubject}}',
      htmlBody: `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #1a73e8;">Thank you for your email</h2>
          <p>Hello {{senderName}},</p>
          <p>Thank you for reaching out. This is an automated response to confirm that we have received your email regarding "{{originalSubject}}".</p>
          <p>We will review your message and respond within {{responseTime}} business hours.</p>
          <p>Best regards,<br>{{signature}}</p>
          <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 20px 0;">
          <p style="font-size: 12px; color: #666;">This is an automated message. Please do not reply directly to this email.</p>
        </div>
      `,
      variables: ['originalSubject', 'senderName', 'responseTime', 'signature']
    });

    // Follow-up template
    this.createTemplate('follow-up', {
      subject: 'Following up on: {{originalSubject}}',
      htmlBody: `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #34a853;">Follow-up on Previous Conversation</h2>
          <p>Hi {{recipientName}},</p>
          <p>I wanted to follow up on our previous conversation regarding {{topic}}.</p>
          <p>{{followUpMessage}}</p>
          <p>Please let me know if you have any questions or if there's anything else I can help you with.</p>
          <p>Best regards,<br>{{senderName}}</p>
        </div>
      `,
      variables: ['originalSubject', 'recipientName', 'topic', 'followUpMessage', 'senderName']
    });

    // Meeting request template
    this.createTemplate('meeting-request', {
      subject: 'Meeting Request: {{meetingTopic}}',
      htmlBody: `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #fbbc05;">Meeting Request</h2>
          <p>Hi {{recipientName}},</p>
          <p>I would like to schedule a meeting to discuss {{meetingTopic}}.</p>
          <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <strong>Meeting Details:</strong><br>
            📅 Date: {{meetingDate}}<br>
            ⏰ Time: {{meetingTime}}<br>
            📍 Location: {{meetingLocation}}<br>
            ⏱️ Duration: {{duration}}
          </div>
          <p>Please let me know if this time works for you or if you would prefer to schedule for a different time.</p>
          <p>Best regards,<br>{{senderName}}</p>
        </div>
      `,
      variables: ['meetingTopic', 'recipientName', 'meetingDate', 'meetingTime', 'meetingLocation', 'duration', 'senderName']
    });

    console.log('✅ Default email templates loaded');
  }

  /**
   * Create a new email template
   * @param {string} templateName - Template name
   * @param {Object} templateConfig - Template configuration
   * @return {string} Template ID
   */
  createTemplate(templateName, templateConfig) {
    const {
      subject = '',
      htmlBody = '',
      plainBody = '',
      variables = [],
      style = 'default',
      category = 'general'
    } = templateConfig;

    try {
      console.log(`📝 Creating email template: ${templateName}`);
      
      const templateId = `tpl_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      
      const template = {
        id: templateId,
        name: templateName,
        subject,
        htmlBody,
        plainBody: plainBody || this.htmlToPlain(htmlBody),
        variables,
        style,
        category,
        created: new Date(),
        lastUsed: null,
        usageCount: 0
      };
      
      this.templates.set(templateId, template);
      
      console.log(`✅ Template created: ${templateName} (${templateId})`);
      return templateId;
      
    } catch (error) {
      console.error(`❌ Error creating template: ${error.message}`);
      throw error;
    }
  }

  /**
   * Generate email from template
   * @param {string} templateId - Template ID or name
   * @param {Object} variables - Variable values
   * @param {Object} options - Generation options
   * @return {Object} Generated email
   */
  generateEmail(templateId, variables = {}, options = {}) {
    const { 
      format = 'html',
      validate = true,
      preview = false 
    } = options;

    try {
      console.log(`📧 Generating email from template: ${templateId}`);
      
      const template = this.getTemplate(templateId);
      if (!template) {
        throw new Error(`Template not found: ${templateId}`);
      }
      
      // Validate required variables
      if (validate) {
        this.validateVariables(template, variables);
      }
      
      // Process template
      const processedSubject = this.processTemplate(template.subject, variables);
      const processedHtmlBody = this.processTemplate(template.htmlBody, variables);
      const processedPlainBody = this.processTemplate(template.plainBody, variables);
      
      // Update usage statistics
      if (!preview) {
        template.usageCount++;
        template.lastUsed = new Date();
      }
      
      const generatedEmail = {
        templateId: template.id,
        templateName: template.name,
        subject: processedSubject,
        htmlBody: format === 'html' ? processedHtmlBody : null,
        plainBody: format === 'plain' ? processedPlainBody : this.htmlToPlain(processedHtmlBody),
        variables: variables,
        generatedAt: new Date(),
        preview: preview
      };
      
      console.log(`✅ Email generated: ${processedSubject}`);
      return generatedEmail;
      
    } catch (error) {
      console.error(`❌ Error generating email: ${error.message}`);
      throw error;
    }
  }

  /**
   * Send email using template
   * @param {string} templateId - Template ID or name
   * @param {Object} emailConfig - Email configuration
   * @return {Object} Send result
   */
  sendTemplatedEmail(templateId, emailConfig) {
    const {
      to,
      cc = '',
      bcc = '',
      variables = {},
      attachments = [],
      replyTo = '',
      from = ''
    } = emailConfig;

    try {
      console.log(`📤 Sending templated email to: ${to}`);
      
      const generatedEmail = this.generateEmail(templateId, variables);
      
      // Prepare Gmail send options
      const options = {
        htmlBody: generatedEmail.htmlBody,
        cc: cc,
        bcc: bcc,
        replyTo: replyTo,
        from: from,
        attachments: attachments
      };
      
      // Remove empty options
      Object.keys(options).forEach(key => {
        if (!options[key] || (Array.isArray(options[key]) && options[key].length === 0)) {
          delete options[key];
        }
      });
      
      // Send email
      GmailApp.sendEmail(to, generatedEmail.subject, generatedEmail.plainBody, options);
      
      const result = {
        success: true,
        templateId: templateId,
        subject: generatedEmail.subject,
        recipients: [to, ...cc.split(',').filter(Boolean), ...bcc.split(',').filter(Boolean)],
        sentAt: new Date()
      };
      
      console.log(`✅ Email sent successfully: ${generatedEmail.subject}`);
      return result;
      
    } catch (error) {
      console.error(`❌ Error sending templated email: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get template by ID or name
   * @param {string} identifier - Template ID or name
   * @return {Object|null} Template object
   * @private
   */
  getTemplate(identifier) {
    // Try by ID first
    if (this.templates.has(identifier)) {
      return this.templates.get(identifier);
    }
    
    // Try by name
    for (const template of this.templates.values()) {
      if (template.name === identifier) {
        return template;
      }
    }
    
    return null;
  }

  /**
   * Process template with variable substitution
   * @param {string} template - Template string
   * @param {Object} variables - Variables to substitute
   * @return {string} Processed template
   * @private
   */
  processTemplate(template, variables) {
    let processed = template;
    
    // Handle conditional blocks
    processed = this.processConditionals(processed, variables);
    
    // Handle loops
    processed = this.processLoops(processed, variables);
    
    // Handle variable substitution
    Object.entries(variables).forEach(([key, value]) => {
      const regex = new RegExp(`{{${key}}}`, 'g');
      processed = processed.replace(regex, String(value || ''));
    });
    
    // Clean up any remaining template markers
    processed = processed.replace(/{{[^}]+}}/g, '');
    
    return processed;
  }

  /**
   * Process conditional blocks in templates
   * @param {string} template - Template string
   * @param {Object} variables - Variables
   * @return {string} Processed template
   * @private
   */
  processConditionals(template, variables) {
    const conditionalRegex = /{{#if\s+(\w+)}}(.*?){{\/if}}/gs;
    
    return template.replace(conditionalRegex, (match, condition, content) => {
      return variables[condition] ? content : '';
    });
  }

  /**
   * Process loop blocks in templates
   * @param {string} template - Template string
   * @param {Object} variables - Variables
   * @return {string} Processed template
   * @private
   */
  processLoops(template, variables) {
    const loopRegex = /{{#each\s+(\w+)}}(.*?){{\/each}}/gs;
    
    return template.replace(loopRegex, (match, arrayName, content) => {
      const array = variables[arrayName];
      if (!Array.isArray(array)) return '';
      
      return array.map((item, index) => {
        let itemContent = content;
        itemContent = itemContent.replace(/{{@index}}/g, index);
        itemContent = itemContent.replace(/{{this}}/g, item);
        
        // Handle object properties
        if (typeof item === 'object') {
          Object.entries(item).forEach(([key, value]) => {
            const regex = new RegExp(`{{${key}}}`, 'g');
            itemContent = itemContent.replace(regex, String(value || ''));
          });
        }
        
        return itemContent;
      }).join('');
    });
  }

  /**
   * Validate required template variables
   * @param {Object} template - Template object
   * @param {Object} variables - Provided variables
   * @private
   */
  validateVariables(template, variables) {
    const missing = [];
    
    for (const requiredVar of template.variables) {
      if (!(requiredVar in variables)) {
        missing.push(requiredVar);
      }
    }
    
    if (missing.length > 0) {
      throw new Error(`Missing required variables: ${missing.join(', ')}`);
    }
  }

  /**
   * Convert HTML to plain text (simplified)
   * @param {string} html - HTML string
   * @return {string} Plain text
   * @private
   */
  htmlToPlain(html) {
    return html
      .replace(/<[^>]*>/g, '')
      .replace(/&nbsp;/g, ' ')
      .replace(/&amp;/g, '&')
      .replace(/&lt;/g, '<')
      .replace(/&gt;/g, '>')
      .replace(/\s+/g, ' ')
      .trim();
  }

  /**
   * List available templates
   * @param {string} category - Filter by category (optional)
   * @return {Array<Object>} Array of template info
   */
  listTemplates(category = null) {
    const templates = Array.from(this.templates.values());
    
    const filtered = category 
      ? templates.filter(t => t.category === category)
      : templates;
    
    return filtered.map(t => ({
      id: t.id,
      name: t.name,
      category: t.category,
      usageCount: t.usageCount,
      lastUsed: t.lastUsed,
      variables: t.variables
    }));
  }

  /**
   * Delete template
   * @param {string} templateId - Template ID to delete
   * @return {boolean} Success status
   */
  deleteTemplate(templateId) {
    try {
      const template = this.getTemplate(templateId);
      if (template) {
        this.templates.delete(template.id);
        console.log(`🗑️ Deleted template: ${template.name}`);
        return true;
      }
      return false;
    } catch (error) {
      console.error(`❌ Error deleting template: ${error.message}`);
      return false;
    }
  }

  /**
   * Export templates to JSON
   * @return {string} JSON representation of templates
   */
  exportTemplates() {
    const exportData = {
      version: '1.0',
      exported: new Date(),
      templates: Array.from(this.templates.values())
    };
    
    return JSON.stringify(exportData, null, 2);
  }

  /**
   * Import templates from JSON
   * @param {string} jsonData - JSON template data
   * @return {number} Number of templates imported
   */
  importTemplates(jsonData) {
    try {
      const data = JSON.parse(jsonData);
      let imported = 0;
      
      for (const template of data.templates) {
        this.templates.set(template.id, template);
        imported++;
      }
      
      console.log(`📥 Imported ${imported} templates`);
      return imported;
      
    } catch (error) {
      console.error(`❌ Error importing templates: ${error.message}`);
      throw error;
    }
  }
}

// Create global email template manager instance
const EMAIL_TEMPLATE_MANAGER = new EmailTemplateManager();
```

---

## 11. Advanced Automation Workflows

### GmailWorkflowEngine Class - Automated Email Workflows

```javascript
/**
 * GmailWorkflowEngine - Advanced email automation workflow system
 * Orchestrates complex email processing workflows with triggers and actions
 * @class
 */
class GmailWorkflowEngine {
  constructor() {
    this.config = GMAIL_CONFIG;
    this.workflows = new Map();
    this.executionLog = [];
    this.isRunning = false;
  }

  /**
   * Create a new automation workflow
   * @param {string} workflowName - Workflow name
   * @param {Object} workflowConfig - Workflow configuration
   * @return {string} Workflow ID
   */
  createWorkflow(workflowName, workflowConfig) {
    const {
      triggers = [],
      conditions = [],
      actions = [],
      schedule = null,
      enabled = true,
      description = ''
    } = workflowConfig;

    try {
      console.log(`⚙️ Creating workflow: ${workflowName}`);
      
      const workflowId = `wf_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      
      const workflow = {
        id: workflowId,
        name: workflowName,
        description,
        triggers,
        conditions,
        actions,
        schedule,
        enabled,
        created: new Date(),
        lastRun: null,
        executionCount: 0,
        successCount: 0,
        errorCount: 0
      };
      
      this.workflows.set(workflowId, workflow);
      
      // Set up scheduled execution if needed
      if (schedule && enabled) {
        this.setupSchedule(workflow);
      }
      
      console.log(`✅ Workflow created: ${workflowName} (${workflowId})`);
      return workflowId;
      
    } catch (error) {
      console.error(`❌ Error creating workflow: ${error.message}`);
      throw error;
    }
  }

  /**
   * Execute a workflow
   * @param {string} workflowId - Workflow ID
   * @param {Object} context - Execution context
   * @return {Object} Execution results
   */
  async executeWorkflow(workflowId, context = {}) {
    const workflow = this.workflows.get(workflowId);
    if (!workflow) {
      throw new Error(`Workflow not found: ${workflowId}`);
    }

    if (!workflow.enabled) {
      console.log(`⏸️ Workflow is disabled: ${workflow.name}`);
      return { skipped: true, reason: 'disabled' };
    }

    try {
      console.log(`🚀 Executing workflow: ${workflow.name}`);
      
      const executionId = `exec_${Date.now()}_${Math.random().toString(36).substr(2, 5)}`;
      const startTime = new Date();
      
      // Update workflow statistics
      workflow.executionCount++;
      workflow.lastRun = startTime;
      
      // Get emails based on triggers
      const emails = await this.processTriggeres(workflow.triggers, context);
      console.log(`📧 Found ${emails.length} emails matching triggers`);
      
      if (emails.length === 0) {
        return {
          executionId,
          workflowId,
          workflowName: workflow.name,
          processed: 0,
          success: 0,
          failed: 0,
          skipped: emails.length,
          duration: new Date() - startTime
        };
      }
      
      // Apply conditions to filter emails
      const filteredEmails = emails.filter(email => 
        this.evaluateConditions(workflow.conditions, email, context)
      );
      
      console.log(`🔍 ${filteredEmails.length} emails passed conditions`);
      
      // Execute actions on filtered emails
      const results = await this.executeActions(workflow.actions, filteredEmails, context);
      
      // Update workflow statistics
      workflow.successCount += results.success;
      workflow.errorCount += results.failed;
      
      const executionResult = {
        executionId,
        workflowId,
        workflowName: workflow.name,
        processed: filteredEmails.length,
        success: results.success,
        failed: results.failed,
        skipped: emails.length - filteredEmails.length,
        duration: new Date() - startTime,
        actions: results.actions
      };
      
      // Log execution
      this.executionLog.push({
        ...executionResult,
        timestamp: new Date()
      });
      
      console.log(`✅ Workflow execution completed:`, executionResult);
      return executionResult;
      
    } catch (error) {
      workflow.errorCount++;
      console.error(`❌ Workflow execution failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process workflow triggers to get matching emails
   * @param {Array<Object>} triggers - Workflow triggers
   * @param {Object} context - Execution context
   * @return {Array<Object>} Matching emails
   * @private
   */
  async processTriggeres(triggers, context) {
    const allEmails = [];
    
    for (const trigger of triggers) {
      try {
        const emails = await this.processTrigger(trigger, context);
        allEmails.push(...emails);
      } catch (error) {
        console.warn(`⚠️ Trigger processing failed: ${error.message}`);
      }
    }
    
    // Remove duplicates based on email ID
    const uniqueEmails = [];
    const seenIds = new Set();
    
    for (const email of allEmails) {
      if (!seenIds.has(email.id)) {
        seenIds.add(email.id);
        uniqueEmails.push(email);
      }
    }
    
    return uniqueEmails;
  }

  /**
   * Process a single trigger
   * @param {Object} trigger - Trigger configuration
   * @param {Object} context - Execution context
   * @return {Array<Object>} Matching emails
   * @private
   */
  async processTrigger(trigger, context) {
    const { type, query, maxResults = 100, timeRange = null } = trigger;
    
    let searchQuery = query;
    
    // Add time range if specified
    if (timeRange) {
      const cutoffDate = new Date();
      cutoffDate.setHours(cutoffDate.getHours() - timeRange);
      const dateStr = Utilities.formatDate(cutoffDate, Session.getScriptTimeZone(), 'yyyy/MM/dd');
      searchQuery += ` after:${dateStr}`;
    }
    
    switch (type) {
      case 'search':
        return GMAIL_SEARCHER.search(searchQuery, { maxResults });
        
      case 'unread':
        return GMAIL_SEARCHER.search(`is:unread ${searchQuery}`, { maxResults });
        
      case 'important':
        return GMAIL_SEARCHER.search(`is:important ${searchQuery}`, { maxResults });
        
      case 'label':
        return GMAIL_SEARCHER.search(`label:${trigger.labelName} ${searchQuery}`, { maxResults });
        
      case 'sender':
        return GMAIL_SEARCHER.search(`from:${trigger.senderEmail} ${searchQuery}`, { maxResults });
        
      default:
        throw new Error(`Unknown trigger type: ${type}`);
    }
  }

  /**
   * Evaluate workflow conditions
   * @param {Array<Object>} conditions - Conditions to evaluate
   * @param {Object} email - Email to check
   * @param {Object} context - Execution context
   * @return {boolean} Whether all conditions are met
   * @private
   */
  evaluateConditions(conditions, email, context) {
    if (conditions.length === 0) return true;
    
    return conditions.every(condition => {
      switch (condition.type) {
        case 'hasAttachments':
          return email.hasAttachments === condition.value;
          
        case 'isUnread':
          return email.isUnread === condition.value;
          
        case 'subjectContains':
          return email.subject.toLowerCase().includes(condition.value.toLowerCase());
          
        case 'senderIs':
          return email.from.toLowerCase().includes(condition.value.toLowerCase());
          
        case 'hasLabel':
          return email.labels && email.labels.includes(condition.value);
          
        case 'dateAfter':
          return new Date(email.lastMessageDate) > new Date(condition.value);
          
        case 'dateBefore':
          return new Date(email.lastMessageDate) < new Date(condition.value);
          
        case 'messageCount':
          return this.compareNumbers(email.messageCount, condition.operator, condition.value);
          
        default:
          return true;
      }
    });
  }

  /**
   * Execute workflow actions on emails
   * @param {Array<Object>} actions - Actions to execute
   * @param {Array<Object>} emails - Emails to process
   * @param {Object} context - Execution context
   * @return {Object} Execution results
   * @private
   */
  async executeActions(actions, emails, context) {
    const results = {
      success: 0,
      failed: 0,
      actions: []
    };
    
    for (const action of actions) {
      try {
        const actionResult = await this.executeAction(action, emails, context);
        results.actions.push(actionResult);
        results.success += actionResult.success || 0;
        results.failed += actionResult.failed || 0;
        
      } catch (error) {
        console.error(`❌ Action execution failed: ${error.message}`);
        results.failed += emails.length;
        results.actions.push({
          type: action.type,
          success: 0,
          failed: emails.length,
          error: error.message
        });
      }
    }
    
    return results;
  }

  /**
   * Execute a single action
   * @param {Object} action - Action to execute
   * @param {Array<Object>} emails - Emails to process
   * @param {Object} context - Execution context
   * @return {Object} Action result
   * @private
   */
  async executeAction(action, emails, context) {
    const threadIds = emails.map(email => email.id);
    
    switch (action.type) {
      case 'addLabel':
        const labelResult = await GMAIL_BATCH_PROCESSOR.bulkLabel(threadIds, action.labelName);
        return { type: 'addLabel', ...labelResult };
        
      case 'archive':
        const archiveResult = await GMAIL_BATCH_PROCESSOR.bulkArchive(threadIds);
        return { type: 'archive', ...archiveResult };
        
      case 'delete':
        const deleteResult = await GMAIL_BATCH_PROCESSOR.bulkDelete(threadIds);
        return { type: 'delete', ...deleteResult };
        
      case 'markAsRead':
        let readSuccess = 0;
        for (const threadId of threadIds) {
          try {
            const thread = GmailApp.getThreadById(threadId);
            if (thread) {
              thread.markRead();
              readSuccess++;
            }
          } catch (error) {
            console.warn(`⚠️ Failed to mark thread as read: ${threadId}`);
          }
        }
        return { type: 'markAsRead', success: readSuccess, failed: threadIds.length - readSuccess };
        
      case 'sendReply':
        return await this.executeSendReply(action, emails, context);
        
      case 'forward':
        return await this.executeForward(action, emails, context);
        
      case 'webhook':
        return await this.executeWebhook(action, emails, context);
        
      default:
        throw new Error(`Unknown action type: ${action.type}`);
    }
  }

  /**
   * Execute send reply action
   * @param {Object} action - Reply action
   * @param {Array<Object>} emails - Emails to reply to
   * @param {Object} context - Execution context
   * @return {Object} Reply result
   * @private
   */
  async executeSendReply(action, emails, context) {
    let success = 0;
    let failed = 0;
    
    for (const email of emails) {
      try {
        const variables = {
          originalSubject: email.subject,
          senderName: this.extractName(email.from),
          ...context,
          ...action.variables
        };
        
        const replyEmail = EMAIL_TEMPLATE_MANAGER.generateEmail(action.templateId, variables);
        
        const thread = GmailApp.getThreadById(email.id);
        if (thread) {
          thread.reply(replyEmail.plainBody, {
            htmlBody: replyEmail.htmlBody,
            subject: replyEmail.subject
          });
          success++;
        }
        
      } catch (error) {
        console.warn(`⚠️ Failed to send reply: ${error.message}`);
        failed++;
      }
    }
    
    return { type: 'sendReply', success, failed };
  }

  /**
   * Execute forward action
   * @param {Object} action - Forward action
   * @param {Array<Object>} emails - Emails to forward
   * @param {Object} context - Execution context
   * @return {Object} Forward result
   * @private
   */
  async executeForward(action, emails, context) {
    let success = 0;
    let failed = 0;
    
    for (const email of emails) {
      try {
        const thread = GmailApp.getThreadById(email.id);
        if (thread && thread.getMessageCount() > 0) {
          const message = thread.getMessages()[0];
          const forwardSubject = `Fwd: ${email.subject}`;
          
          GmailApp.sendEmail(
            action.forwardTo,
            forwardSubject,
            message.getPlainBody(),
            {
              htmlBody: message.getHtmlBody(),
              attachments: message.getAttachments()
            }
          );
          success++;
        }
        
      } catch (error) {
        console.warn(`⚠️ Failed to forward email: ${error.message}`);
        failed++;
      }
    }
    
    return { type: 'forward', success, failed };
  }

  /**
   * Execute webhook action
   * @param {Object} action - Webhook action
   * @param {Array<Object>} emails - Emails that triggered the webhook
   * @param {Object} context - Execution context
   * @return {Object} Webhook result
   * @private
   */
  async executeWebhook(action, emails, context) {
    try {
      const payload = {
        emails: emails.map(email => ({
          id: email.id,
          subject: email.subject,
          from: email.from,
          date: email.lastMessageDate
        })),
        context,
        timestamp: new Date()
      };
      
      const response = UrlFetchApp.fetch(action.webhookUrl, {
        method: 'POST',
        contentType: 'application/json',
        payload: JSON.stringify(payload)
      });
      
      return { 
        type: 'webhook', 
        success: 1, 
        failed: 0, 
        statusCode: response.getResponseCode() 
      };
      
    } catch (error) {
      return { 
        type: 'webhook', 
        success: 0, 
        failed: 1, 
        error: error.message 
      };
    }
  }

  /**
   * Setup scheduled execution for workflow
   * @param {Object} workflow - Workflow object
   * @private
   */
  setupSchedule(workflow) {
    const { schedule } = workflow;
    
    try {
      // Create trigger based on schedule type
      let trigger;
      
      switch (schedule.type) {
        case 'interval':
          if (schedule.hours) {
            trigger = ScriptApp.newTrigger('executeScheduledWorkflows')
              .timeBased()
              .everyHours(schedule.hours)
              .create();
          }
          break;
          
        case 'daily':
          trigger = ScriptApp.newTrigger('executeScheduledWorkflows')
            .timeBased()
            .everyDays(1)
            .atHour(schedule.hour || 9)
            .create();
          break;
          
        case 'weekly':
          trigger = ScriptApp.newTrigger('executeScheduledWorkflows')
            .timeBased()
            .everyWeeks(1)
            .onWeekDay(ScriptApp.WeekDay[schedule.day || 'MONDAY'])
            .atHour(schedule.hour || 9)
            .create();
          break;
      }
      
      if (trigger) {
        workflow.triggerId = trigger.getUniqueId();
        console.log(`⏰ Scheduled workflow: ${workflow.name}`);
      }
      
    } catch (error) {
      console.error(`❌ Error setting up schedule: ${error.message}`);
    }
  }

  /**
   * Compare numbers based on operator
   * @param {number} actual - Actual value
   * @param {string} operator - Comparison operator
   * @param {number} expected - Expected value
   * @return {boolean} Comparison result
   * @private
   */
  compareNumbers(actual, operator, expected) {
    switch (operator) {
      case 'equals': return actual === expected;
      case 'greaterThan': return actual > expected;
      case 'lessThan': return actual < expected;
      case 'greaterThanOrEqual': return actual >= expected;
      case 'lessThanOrEqual': return actual <= expected;
      default: return false;
    }
  }

  /**
   * Extract name from email address
   * @param {string} email - Email string
   * @return {string} Extracted name
   * @private
   */
  extractName(email) {
    const match = email.match(/^(.+?)\s*<.+>$/);
    if (match) {
      return match[1].replace(/['"]/g, '');
    }
    return email.split('@')[0];
  }

  /**
   * Get workflow execution statistics
   * @param {string} workflowId - Workflow ID
   * @return {Object} Statistics
   */
  getWorkflowStats(workflowId) {
    const workflow = this.workflows.get(workflowId);
    if (!workflow) return null;
    
    return {
      id: workflow.id,
      name: workflow.name,
      enabled: workflow.enabled,
      executionCount: workflow.executionCount,
      successCount: workflow.successCount,
      errorCount: workflow.errorCount,
      successRate: workflow.executionCount > 0 
        ? Math.round((workflow.successCount / workflow.executionCount) * 100)
        : 0,
      lastRun: workflow.lastRun,
      created: workflow.created
    };
  }

  /**
   * List all workflows
   * @return {Array<Object>} Workflow summaries
   */
  listWorkflows() {
    return Array.from(this.workflows.values()).map(workflow => ({
      id: workflow.id,
      name: workflow.name,
      enabled: workflow.enabled,
      executionCount: workflow.executionCount,
      lastRun: workflow.lastRun
    }));
  }

  /**
   * Delete workflow
   * @param {string} workflowId - Workflow ID
   * @return {boolean} Success status
   */
  deleteWorkflow(workflowId) {
    try {
      const workflow = this.workflows.get(workflowId);
      if (workflow) {
        // Delete associated trigger if exists
        if (workflow.triggerId) {
          const triggers = ScriptApp.getProjectTriggers();
          const trigger = triggers.find(t => t.getUniqueId() === workflow.triggerId);
          if (trigger) {
            ScriptApp.deleteTrigger(trigger);
          }
        }
        
        this.workflows.delete(workflowId);
        console.log(`🗑️ Deleted workflow: ${workflow.name}`);
        return true;
      }
      return false;
    } catch (error) {
      console.error(`❌ Error deleting workflow: ${error.message}`);
      return false;
    }
  }
}

// Create global workflow engine instance
const GMAIL_WORKFLOW_ENGINE = new GmailWorkflowEngine();

/**
 * Scheduled workflow execution function (called by triggers)
 */
function executeScheduledWorkflows() {
  try {
    console.log('⏰ Running scheduled workflows...');
    
    const workflows = GMAIL_WORKFLOW_ENGINE.listWorkflows()
      .filter(wf => wf.enabled);
    
    for (const workflow of workflows) {
      try {
        GMAIL_WORKFLOW_ENGINE.executeWorkflow(workflow.id);
      } catch (error) {
        console.error(`❌ Scheduled workflow failed: ${workflow.name} - ${error.message}`);
      }
    }
    
    console.log('✅ Scheduled workflows execution completed');
    
  } catch (error) {
    console.error('❌ Scheduled workflows execution failed:', error.message);
  }
}
```

---

## 12. Complete Project Examples

### Project 1: Automated Email Organizer

```javascript
/**
 * Complete Email Organization System
 * Automatically organizes emails based on intelligent rules
 */
function createEmailOrganizerSystem() {
  // Create intelligent email organization system
  const organizerWorkflow = GMAIL_WORKFLOW_ENGINE.createWorkflow('Email Organizer', {
    description: 'Automatically organize incoming emails',
    triggers: [
      {
        type: 'unread',
        query: 'newer_than:1d',
        maxResults: 50
      }
    ],
    conditions: [
      {
        type: 'isUnread',
        value: true
      }
    ],
    actions: [
      {
        type: 'addLabel',
        labelName: 'AutoProcessed'
      }
    ],
    schedule: {
      type: 'interval',
      hours: 2
    }
  });

  // Create email filters for different categories
  const workFilter = EMAIL_FILTER.createFilter('Work Emails', {
    rules: [
      {
        field: 'from',
        operator: 'contains',
        value: '@company.com',
        actions: ['apply_label:Work', 'mark_important']
      },
      {
        field: 'subject',
        operator: 'contains',
        value: '[URGENT]',
        actions: ['apply_label:Urgent', 'star']
      }
    ]
  });

  const personalFilter = EMAIL_FILTER.createFilter('Personal Emails', {
    rules: [
      {
        field: 'from',
        operator: 'in',
        value: ['gmail.com', 'yahoo.com', 'hotmail.com'],
        actions: ['apply_label:Personal']
      }
    ]
  });

  console.log('🎯 Email Organizer System Created');
  return {
    workflowId: organizerWorkflow,
    filters: [workFilter, personalFilter]
  };
}

/**
 * Smart Email Responder
 * Automatically responds to emails based on content analysis
 */
function createSmartResponder() {
  // Create auto-reply templates
  const meetingTemplate = EMAIL_TEMPLATE_MANAGER.createTemplate('meeting-response', {
    subject: 'Re: {{originalSubject}} - Meeting Confirmation',
    htmlBody: `
      <div style="font-family: Arial, sans-serif;">
        <p>Hi {{senderName}},</p>
        <p>Thank you for your meeting request. I'll check my calendar and get back to you within 24 hours with my availability.</p>
        <p>Meeting Details:</p>
        <ul>
          <li>Topic: {{meetingTopic}}</li>
          <li>Requested Date: {{requestedDate}}</li>
          <li>Duration: {{duration}}</li>
        </ul>
        <p>Best regards,<br>{{myName}}</p>
      </div>
    `,
    variables: ['originalSubject', 'senderName', 'meetingTopic', 'requestedDate', 'duration', 'myName']
  });

  // Create smart responder workflow
  const responderWorkflow = GMAIL_WORKFLOW_ENGINE.createWorkflow('Smart Responder', {
    description: 'Automatically respond to specific types of emails',
    triggers: [
      {
        type: 'search',
        query: 'subject:(meeting OR appointment OR schedule) is:unread',
        maxResults: 20
      }
    ],
    conditions: [
      {
        type: 'isUnread',
        value: true
      },
      {
        type: 'subjectContains',
        value: 'meeting'
      }
    ],
    actions: [
      {
        type: 'sendReply',
        templateId: meetingTemplate,
        variables: {
          myName: 'Your Name',
          meetingTopic: 'Extracted from email',
          requestedDate: 'TBD',
          duration: '30 minutes'
        }
      },
      {
        type: 'addLabel',
        labelName: 'AutoReplied'
      }
    ]
  });

  return responderWorkflow;
}
```

### Project 2: Email Analytics Dashboard

```javascript
/**
 * Email Analytics Dashboard System
 * Creates comprehensive email usage reports
 */
function createAnalyticsDashboard() {
  // Generate comprehensive email report
  const report = EMAIL_ANALYTICS.generateComprehensiveReport({
    period: 'month',
    includeCharts: true,
    exportToSheets: true
  });

  // Create weekly analytics workflow
  const analyticsWorkflow = GMAIL_WORKFLOW_ENGINE.createWorkflow('Weekly Analytics', {
    description: 'Generate weekly email analytics report',
    triggers: [
      {
        type: 'search',
        query: 'newer_than:7d',
        maxResults: 1000
      }
    ],
    actions: [
      {
        type: 'webhook',
        webhookUrl: 'https://your-analytics-endpoint.com/email-data'
      }
    ],
    schedule: {
      type: 'weekly',
      day: 'MONDAY',
      hour: 8
    }
  });

  // Create performance tracking
  function trackEmailPerformance() {
    const stats = EMAIL_ANALYTICS.getEmailStats('week');
    const trends = EMAIL_ANALYTICS.getTrends('month');
    
    // Log to Google Sheets for tracking
    const sheet = SpreadsheetApp.openById('your-sheet-id').getActiveSheet();
    sheet.appendRow([
      new Date(),
      stats.totalEmails,
      stats.sentEmails,
      stats.receivedEmails,
      stats.responseRate,
      trends.volumeTrend
    ]);
    
    console.log('📊 Performance data logged to spreadsheet');
  }

  return {
    workflowId: analyticsWorkflow,
    report: report,
    trackingFunction: trackEmailPerformance
  };
}
```

### Project 3: Email Security Monitor

```javascript
/**
 * Email Security Monitoring System
 * Monitors for suspicious emails and security threats
 */
function createSecurityMonitor() {
  // Create security filter for suspicious emails
  const securityFilter = EMAIL_FILTER.createFilter('Security Monitor', {
    rules: [
      {
        field: 'from',
        operator: 'not_in_contacts',
        value: true,
        actions: ['apply_label:Unknown Sender']
      },
      {
        field: 'subject',
        operator: 'contains',
        value: ['urgent', 'verify', 'suspended', 'action required'],
        actions: ['apply_label:Potential Phishing', 'mark_important']
      },
      {
        field: 'hasAttachments',
        operator: 'equals',
        value: true,
        actions: ['scan_attachments']
      }
    ]
  });

  // Create security monitoring workflow
  const securityWorkflow = GMAIL_WORKFLOW_ENGINE.createWorkflow('Security Monitor', {
    description: 'Monitor for security threats and suspicious emails',
    triggers: [
      {
        type: 'search',
        query: 'newer_than:1h has:attachment',
        maxResults: 50
      }
    ],
    conditions: [
      {
        type: 'hasAttachments',
        value: true
      }
    ],
    actions: [
      {
        type: 'webhook',
        webhookUrl: 'https://security-monitor.com/email-scan'
      },
      {
        type: 'addLabel',
        labelName: 'Security Scanned'
      }
    ],
    schedule: {
      type: 'interval',
      hours: 1
    }
  });

  // Security alert function
  function sendSecurityAlert(threatDetails) {
    const alertEmail = EMAIL_TEMPLATE_MANAGER.generateEmail('security-alert', {
      threatType: threatDetails.type,
      senderEmail: threatDetails.sender,
      detectionTime: new Date(),
      riskLevel: threatDetails.risk
    });

    GmailApp.sendEmail(
      'security@yourcompany.com',
      'Security Alert: Suspicious Email Detected',
      alertEmail.plainBody,
      { htmlBody: alertEmail.htmlBody }
    );
  }

  return {
    workflowId: securityWorkflow,
    filterId: securityFilter,
    alertFunction: sendSecurityAlert
  };
}
```

---

## 13. Performance Optimization & Best Practices

### Performance Optimization Techniques

```javascript
/**
 * GmailPerformanceOptimizer - Advanced performance optimization utilities
 * Provides caching, batching, and performance monitoring for Gmail operations
 * @class
 */
class GmailPerformanceOptimizer {
  constructor() {
    this.cache = new Map();
    this.performanceMetrics = [];
    this.rateLimiter = {
      requests: 0,
      resetTime: Date.now() + (60 * 1000) // Reset every minute
    };
  }

  /**
   * Execute operation with performance monitoring
   * @param {string} operationName - Operation name
   * @param {Function} operation - Operation to execute
   * @param {Object} options - Optimization options
   * @return {*} Operation result
   */
  executeWithMonitoring(operationName, operation, options = {}) {
    const { useCache = false, cacheKey = null, cacheDuration = 300000 } = options;
    
    const startTime = Date.now();
    console.log(`⚡ Starting operation: ${operationName}`);
    
    try {
      // Check cache if enabled
      if (useCache && cacheKey && this.cache.has(cacheKey)) {
        const cached = this.cache.get(cacheKey);
        if (Date.now() - cached.timestamp < cacheDuration) {
          console.log(`💾 Cache hit for: ${operationName}`);
          return cached.data;
        } else {
          this.cache.delete(cacheKey);
        }
      }
      
      // Check rate limits
      this.checkRateLimit();
      
      // Execute operation
      const result = operation();
      
      // Cache result if enabled
      if (useCache && cacheKey) {
        this.cache.set(cacheKey, {
          data: result,
          timestamp: Date.now()
        });
      }
      
      const duration = Date.now() - startTime;
      
      // Record performance metrics
      this.performanceMetrics.push({
        operation: operationName,
        duration: duration,
        timestamp: new Date(),
        cached: useCache && cacheKey && this.cache.has(cacheKey)
      });
      
      console.log(`✅ Operation completed: ${operationName} (${duration}ms)`);
      return result;
      
    } catch (error) {
      const duration = Date.now() - startTime;
      console.error(`❌ Operation failed: ${operationName} (${duration}ms) - ${error.message}`);
      throw error;
    }
  }

  /**
   * Batch multiple operations for efficiency
   * @param {Array<Function>} operations - Operations to batch
   * @param {Object} options - Batch options
   * @return {Array<*>} Batch results
   */
  batchOperations(operations, options = {}) {
    const { batchSize = 10, delay = 100 } = options;
    const results = [];
    
    for (let i = 0; i < operations.length; i += batchSize) {
      const batch = operations.slice(i, i + batchSize);
      
      console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
      
      const batchResults = batch.map(operation => {
        try {
          return operation();
        } catch (error) {
          console.warn(`⚠️ Batch operation failed: ${error.message}`);
          return null;
        }
      });
      
      results.push(...batchResults);
      
      // Add delay between batches to avoid rate limits
      if (i + batchSize < operations.length && delay > 0) {
        Utilities.sleep(delay);
      }
    }
    
    return results;
  }

  /**
   * Check and enforce rate limits
   * @private
   */
  checkRateLimit() {
    const now = Date.now();
    
    // Reset counter if time window has passed
    if (now > this.rateLimiter.resetTime) {
      this.rateLimiter.requests = 0;
      this.rateLimiter.resetTime = now + (60 * 1000);
    }
    
    // Check if we're approaching limits
    if (this.rateLimiter.requests > 50) { // Conservative limit
      const waitTime = this.rateLimiter.resetTime - now;
      console.log(`⏰ Rate limit approached, waiting ${waitTime}ms`);
      Utilities.sleep(waitTime);
      this.rateLimiter.requests = 0;
      this.rateLimiter.resetTime = Date.now() + (60 * 1000);
    }
    
    this.rateLimiter.requests++;
  }

  /**
   * Get performance statistics
   * @return {Object} Performance statistics
   */
  getPerformanceStats() {
    if (this.performanceMetrics.length === 0) {
      return { message: 'No performance data available' };
    }
    
    const durations = this.performanceMetrics.map(m => m.duration);
    const operations = {};
    
    // Group by operation type
    this.performanceMetrics.forEach(metric => {
      if (!operations[metric.operation]) {
        operations[metric.operation] = {
          count: 0,
          totalDuration: 0,
          avgDuration: 0,
          minDuration: Infinity,
          maxDuration: 0,
          cacheHits: 0
        };
      }
      
      const op = operations[metric.operation];
      op.count++;
      op.totalDuration += metric.duration;
      op.minDuration = Math.min(op.minDuration, metric.duration);
      op.maxDuration = Math.max(op.maxDuration, metric.duration);
      if (metric.cached) op.cacheHits++;
    });
    
    // Calculate averages
    Object.values(operations).forEach(op => {
      op.avgDuration = Math.round(op.totalDuration / op.count);
      op.cacheHitRate = Math.round((op.cacheHits / op.count) * 100);
    });
    
    return {
      totalOperations: this.performanceMetrics.length,
      averageDuration: Math.round(durations.reduce((a, b) => a + b, 0) / durations.length),
      minDuration: Math.min(...durations),
      maxDuration: Math.max(...durations),
      cacheSize: this.cache.size,
      operationBreakdown: operations,
      timeRange: {
        start: this.performanceMetrics[0]?.timestamp,
        end: this.performanceMetrics[this.performanceMetrics.length - 1]?.timestamp
      }
    };
  }

  /**
   * Clear cache and metrics
   */
  clearCache() {
    this.cache.clear();
    this.performanceMetrics = [];
    console.log('🧹 Cache and metrics cleared');
  }

  /**
   * Optimize email search with intelligent caching
   * @param {string} query - Search query
   * @param {Object} options - Search options
   * @return {Array<Object>} Search results
   */
  optimizedSearch(query, options = {}) {
    const cacheKey = `search_${query}_${JSON.stringify(options)}`;
    
    return this.executeWithMonitoring(
      'Gmail Search',
      () => GMAIL_SEARCHER.search(query, options),
      {
        useCache: true,
        cacheKey: cacheKey,
        cacheDuration: 300000 // 5 minutes
      }
    );
  }

  /**
   * Optimized batch email processing
   * @param {Array<string>} threadIds - Thread IDs to process
   * @param {Function} processor - Processing function
   * @param {Object} options - Processing options
   * @return {Object} Processing results
   */
  optimizedBatchProcess(threadIds, processor, options = {}) {
    const { batchSize = 20, maxConcurrent = 5 } = options;
    
    return this.executeWithMonitoring(
      'Batch Process',
      () => {
        const operations = threadIds.map(threadId => () => processor(threadId));
        return this.batchOperations(operations, { batchSize });
      }
    );
  }
}

// Create global performance optimizer instance
const GMAIL_PERFORMANCE_OPTIMIZER = new GmailPerformanceOptimizer();
```

### Security Best Practices

```javascript
/**
 * Gmail Security Best Practices Implementation
 */
class GmailSecurityManager {
  constructor() {
    this.securityPolicies = new Map();
    this.securityLog = [];
  }

  /**
   * Validate email addresses for security
   * @param {string} email - Email to validate
   * @return {Object} Validation result
   */
  validateEmailSecurity(email) {
    const validationResult = {
      isValid: true,
      riskLevel: 'low',
      warnings: [],
      recommendations: []
    };

    // Check for suspicious patterns
    const suspiciousPatterns = [
      /[0-9]{10,}@/,  // Long numbers in email
      /[a-zA-Z]{20,}@/,  // Very long random strings
      /\.(tk|ml|ga|cf)$/,  // Suspicious TLD
      /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(bit|onion)$/  // Dark web domains
    ];

    for (const pattern of suspiciousPatterns) {
      if (pattern.test(email)) {
        validationResult.riskLevel = 'high';
        validationResult.warnings.push('Email matches suspicious pattern');
        break;
      }
    }

    // Check domain reputation (simplified)
    const domain = email.split('@')[1];
    const trustedDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
    
    if (!trustedDomains.includes(domain)) {
      validationResult.riskLevel = 'medium';
      validationResult.warnings.push('Email from unknown domain');
      validationResult.recommendations.push('Verify sender identity');
    }

    this.logSecurityEvent('email_validation', {
      email: email,
      result: validationResult
    });

    return validationResult;
  }

  /**
   * Secure email sending with validation
   * @param {Object} emailData - Email data
   * @return {Object} Send result
   */
  secureSendEmail(emailData) {
    const { to, subject, body, attachments = [] } = emailData;

    // Validate recipients
    const recipients = Array.isArray(to) ? to : [to];
    for (const recipient of recipients) {
      const validation = this.validateEmailSecurity(recipient);
      if (validation.riskLevel === 'high') {
        throw new Error(`High risk recipient detected: ${recipient}`);
      }
    }

    // Scan attachments for security
    for (const attachment of attachments) {
      if (!this.isAttachmentSafe(attachment)) {
        throw new Error(`Unsafe attachment detected: ${attachment.getName()}`);
      }
    }

    // Log security event
    this.logSecurityEvent('email_sent', {
      recipients: recipients,
      hasAttachments: attachments.length > 0,
      timestamp: new Date()
    });

    // Send email with security logging
    try {
      GmailApp.sendEmail(to, subject, body, {
        attachments: attachments,
        replyTo: 'noreply@yourcompany.com'
      });

      console.log('✅ Email sent securely');
      return { success: true, securityValidated: true };

    } catch (error) {
      this.logSecurityEvent('email_send_failed', {
        error: error.message,
        recipients: recipients
      });
      throw error;
    }
  }

  /**
   * Check if attachment is safe
   * @param {Blob} attachment - Attachment to check
   * @return {boolean} Whether attachment is safe
   * @private
   */
  isAttachmentSafe(attachment) {
    const unsafeTypes = [
      'application/x-msdownload',
      'application/x-msdos-program',
      'application/x-executable',
      'application/x-winexe',
      'application/x-bat',
      'application/x-com',
      'application/x-javascript'
    ];

    const fileName = attachment.getName().toLowerCase();
    const unsafeExtensions = ['.exe', '.bat', '.com', '.cmd', '.scr', '.pif', '.vbs', '.js'];

    // Check file extension
    for (const ext of unsafeExtensions) {
      if (fileName.endsWith(ext)) {
        return false;
      }
    }

    // Check MIME type
    const contentType = attachment.getContentType();
    if (unsafeTypes.includes(contentType)) {
      return false;
    }

    // Check file size (prevent extremely large attachments)
    const maxSize = 25 * 1024 * 1024; // 25MB
    if (attachment.getBytes().length > maxSize) {
      return false;
    }

    return true;
  }

  /**
   * Log security events
   * @param {string} eventType - Type of security event
   * @param {Object} eventData - Event data
   * @private
   */
  logSecurityEvent(eventType, eventData) {
    const logEntry = {
      timestamp: new Date(),
      type: eventType,
      data: eventData,
      sessionId: Session.getTemporaryActiveUserKey()
    };

    this.securityLog.push(logEntry);

    // Keep log size manageable
    if (this.securityLog.length > 1000) {
      this.securityLog = this.securityLog.slice(-500);
    }

    console.log(`🔒 Security event logged: ${eventType}`);
  }

  /**
   * Get security report
   * @return {Object} Security report
   */
  getSecurityReport() {
    const eventTypes = {};
    let riskEvents = 0;

    this.securityLog.forEach(entry => {
      eventTypes[entry.type] = (eventTypes[entry.type] || 0) + 1;
      
      if (entry.data.riskLevel === 'high' || entry.type.includes('failed')) {
        riskEvents++;
      }
    });

    return {
      totalEvents: this.securityLog.length,
      riskEvents: riskEvents,
      eventBreakdown: eventTypes,
      recentActivity: this.securityLog.slice(-10),
      securityScore: Math.max(0, 100 - (riskEvents * 5))
    };
  }
}

// Create global security manager instance
const GMAIL_SECURITY_MANAGER = new GmailSecurityManager();
```

---

## 14. Testing & Debugging

### Testing Framework for Gmail Functions

```javascript
/**
 * Gmail Testing Framework
 * Comprehensive testing utilities for Gmail automation
 */
class GmailTester {
  constructor() {
    this.testResults = [];
    this.mockData = new Map();
  }

  /**
   * Run test suite
   * @param {Array<Function>} tests - Array of test functions
   * @return {Object} Test results
   */
  runTestSuite(tests) {
    console.log('🧪 Starting Gmail test suite...');
    
    const startTime = Date.now();
    let passed = 0;
    let failed = 0;

    for (const test of tests) {
      try {
        console.log(`🔍 Running test: ${test.name}`);
        const result = test();
        
        if (result) {
          console.log(`✅ Test passed: ${test.name}`);
          passed++;
        } else {
          console.log(`❌ Test failed: ${test.name}`);
          failed++;
        }
        
        this.testResults.push({
          name: test.name,
          passed: !!result,
          duration: Date.now() - startTime
        });
        
      } catch (error) {
        console.error(`💥 Test error: ${test.name} - ${error.message}`);
        failed++;
        this.testResults.push({
          name: test.name,
          passed: false,
          error: error.message,
          duration: Date.now() - startTime
        });
      }
    }

    const summary = {
      total: tests.length,
      passed: passed,
      failed: failed,
      duration: Date.now() - startTime,
      successRate: Math.round((passed / tests.length) * 100)
    };

    console.log('📊 Test Summary:', summary);
    return summary;
  }

  /**
   * Test Gmail search functionality
   * @return {boolean} Test result
   */
  testGmailSearch() {
    try {
      // Test basic search
      const results = GMAIL_SEARCHER.search('is:unread', { maxResults: 5 });
      
      // Validate results structure
      if (!Array.isArray(results)) {
        throw new Error('Search results should be an array');
      }

      // Test each result has required properties
      for (const result of results) {
        if (!result.id || !result.subject || !result.from) {
          throw new Error('Search result missing required properties');
        }
      }

      return true;
    } catch (error) {
      console.error(`Search test failed: ${error.message}`);
      return false;
    }
  }

  /**
   * Test email template system
   * @return {boolean} Test result
   */
  testEmailTemplates() {
    try {
      // Create test template
      const templateId = EMAIL_TEMPLATE_MANAGER.createTemplate('test-template', {
        subject: 'Test: {{title}}',
        htmlBody: '<p>Hello {{name}}, this is a test email about {{topic}}.</p>',
        variables: ['title', 'name', 'topic']
      });

      // Test template generation
      const email = EMAIL_TEMPLATE_MANAGER.generateEmail(templateId, {
        title: 'Unit Test',
        name: 'John Doe',
        topic: 'Gmail Automation'
      });

      // Validate generated email
      if (email.subject !== 'Test: Unit Test') {
        throw new Error('Template variable substitution failed in subject');
      }

      if (!email.htmlBody.includes('Hello John Doe')) {
        throw new Error('Template variable substitution failed in body');
      }

      // Cleanup
      EMAIL_TEMPLATE_MANAGER.deleteTemplate(templateId);

      return true;
    } catch (error) {
      console.error(`Template test failed: ${error.message}`);
      return false;
    }
  }

  /**
   * Test workflow engine
   * @return {boolean} Test result
   */
  testWorkflowEngine() {
    try {
      // Create test workflow
      const workflowId = GMAIL_WORKFLOW_ENGINE.createWorkflow('Test Workflow', {
        description: 'Test workflow for validation',
        triggers: [
          {
            type: 'search',
            query: 'is:unread',
            maxResults: 1
          }
        ],
        conditions: [],
        actions: [
          {
            type: 'addLabel',
            labelName: 'TestLabel'
          }
        ],
        enabled: false // Don't actually run it
      });

      // Validate workflow creation
      const workflows = GMAIL_WORKFLOW_ENGINE.listWorkflows();
      const testWorkflow = workflows.find(wf => wf.id === workflowId);
      
      if (!testWorkflow) {
        throw new Error('Workflow was not created properly');
      }

      // Cleanup
      GMAIL_WORKFLOW_ENGINE.deleteWorkflow(workflowId);

      return true;
    } catch (error) {
      console.error(`Workflow test failed: ${error.message}`);
      return false;
    }
  }

  /**
   * Test performance optimization
   * @return {boolean} Test result
   */
  testPerformanceOptimization() {
    try {
      // Test cached operation
      const operation = () => 'test-result';
      
      const result1 = GMAIL_PERFORMANCE_OPTIMIZER.executeWithMonitoring(
        'Test Operation',
        operation,
        { useCache: true, cacheKey: 'test-key' }
      );

      const result2 = GMAIL_PERFORMANCE_OPTIMIZER.executeWithMonitoring(
        'Test Operation',
        operation,
        { useCache: true, cacheKey: 'test-key' }
      );

      // Both results should be identical
      if (result1 !== result2 || result1 !== 'test-result') {
        throw new Error('Caching not working properly');
      }

      // Test batch operations
      const batchOps = [
        () => 'result1',
        () => 'result2',
        () => 'result3'
      ];

      const batchResults = GMAIL_PERFORMANCE_OPTIMIZER.batchOperations(batchOps, { batchSize: 2 });
      
      if (batchResults.length !== 3) {
        throw new Error('Batch operation failed');
      }

      return true;
    } catch (error) {
      console.error(`Performance test failed: ${error.message}`);
      return false;
    }
  }

  /**
   * Run all Gmail tests
   * @return {Object} Comprehensive test results
   */
  runAllTests() {
    const tests = [
      this.testGmailSearch,
      this.testEmailTemplates,
      this.testWorkflowEngine,
      this.testPerformanceOptimization
    ];

    return this.runTestSuite(tests);
  }
}

// Create global tester instance
const GMAIL_TESTER = new GmailTester();

/**
 * Debug helper functions
 */
const GmailDebugger = {
  /**
   * Log email details for debugging
   * @param {GmailThread} thread - Gmail thread
   */
  logEmailDetails(thread) {
    console.log('🔍 Email Debug Info:');
    console.log(`  ID: ${thread.getId()}`);
    console.log(`  Subject: ${thread.getFirstMessageSubject()}`);
    console.log(`  Messages: ${thread.getMessageCount()}`);
    console.log(`  Labels: ${thread.getLabels().map(l => l.getName()).join(', ')}`);
    console.log(`  Last Message Date: ${thread.getLastMessageDate()}`);
    console.log(`  Is Unread: ${thread.isUnread()}`);
    
    const messages = thread.getMessages();
    messages.forEach((message, index) => {
      console.log(`  Message ${index + 1}:`);
      console.log(`    From: ${message.getFrom()}`);
      console.log(`    To: ${message.getTo()}`);
      console.log(`    Date: ${message.getDate()}`);
      console.log(`    Attachments: ${message.getAttachments().length}`);
    });
  },

  /**
   * Monitor Gmail API quotas and usage
   */
  monitorQuotas() {
    const quotaInfo = {
      dailyUsage: PropertiesService.getScriptProperties().getProperty('daily_api_calls') || 0,
      rateLimitRemaining: 100, // Estimate
      lastResetTime: new Date().toDateString()
    };

    console.log('📊 Gmail API Quota Status:');
    console.log(`  Daily Usage: ${quotaInfo.dailyUsage}`);
    console.log(`  Rate Limit Remaining: ${quotaInfo.rateLimitRemaining}`);
    console.log(`  Last Reset: ${quotaInfo.lastResetTime}`);

    return quotaInfo;
  },

  /**
   * Test Gmail API connectivity
   */
  testConnectivity() {
    try {
      const inbox = GmailApp.getInboxThreads(1);
      console.log('✅ Gmail API connectivity test passed');
      return true;
    } catch (error) {
      console.error('❌ Gmail API connectivity test failed:', error.message);
      return false;
    }
  }
};
```

---

## 15. Conclusion

This comprehensive Gmail automation tutorial provides a professional, class-based system for managing Gmail operations through Google Apps Script. The tutorial covers:

### Key Features:
- **Professional Architecture**: Object-oriented design with specialized classes
- **Comprehensive Functionality**: From basic operations to advanced workflows
- **Performance Optimization**: Caching, batching, and rate limiting
- **Security Best Practices**: Input validation and threat detection
- **Advanced Automation**: Workflow engine with triggers and actions
- **Testing Framework**: Comprehensive testing and debugging tools

### Class System Overview:
1. **GmailConfig**: Centralized configuration management
2. **GmailManager**: Core Gmail operations and thread management
3. **MessageReader**: Advanced message analysis and content extraction
4. **LabelManager**: Intelligent label operations and organization
5. **GmailSearcher**: Advanced search with query building and caching
6. **GmailBatchProcessor**: Efficient bulk operations with rate limiting
7. **AttachmentManager**: Secure file handling with Drive integration
8. **EmailFilter**: Rule-based filtering with smart pattern detection
9. **EmailAnalytics**: Comprehensive reporting and trend analysis
10. **EmailTemplateManager**: Dynamic template system with variable substitution
11. **GmailWorkflowEngine**: Advanced automation workflow orchestration
12. **GmailPerformanceOptimizer**: Performance monitoring and optimization
13. **GmailSecurityManager**: Security validation and threat detection
14. **GmailTester**: Comprehensive testing framework

### Getting Started:
1. Copy the classes to your Google Apps Script project
2. Configure the `GMAIL_CONFIG` object with your settings
3. Use the global instances for immediate functionality
4. Extend and customize based on your specific needs

### Next Steps:
- Implement custom workflows for your use cases
- Add additional security policies as needed
- Integrate with other Google Workspace services
- Set up monitoring and alerting for automated processes

This system provides a robust foundation for any Gmail automation project, from simple email organization to complex business workflows.
```
```
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
  
 

