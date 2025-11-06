# Google Apps Script Drive Automation - Complete Professional System

## Table of Contents

### Beginner Level

1. Introduction
2. Configuration and Setup
3. Basic Drive Operations
4. File Management

### Intermediate Level  

1. Advanced Search and Query
2. File Sharing and Permissions
3. Batch Processing
4. File Analytics and Monitoring

### Advanced Level

1. Complete Project Examples
2. Performance Optimization and Best Practices
3. Testing Framework
4. Conclusion and Next Steps

---

## 1. Introduction

This comprehensive tutorial provides a professional, class-based system for Google Drive automation using Google Apps Script with the latest API patterns and optimizations (2024+).

### What You'll Learn

- **Modern API Usage**: Latest Drive API v3 patterns and methods
- **Performance Optimization**: Quota management, caching, and batch operations
- **Professional Architecture**: Object-oriented design with specialized classes
- **Complete Drive Management**: File operations, folder management, and advanced search
- **Automation Workflows**: Automated file organization and synchronization
- **Security & Permissions**: Advanced sharing and access control using current APIs
- **Real-world Projects**: Complete implementation examples with best practices

### Prerequisites

- Google Account with Drive access
- Basic JavaScript knowledge
- Google Apps Script environment
- Understanding of Google Drive concepts
- Enable Drive API v3 in Apps Script (Services → Drive API)

---

## 2. Configuration and Setup

### DriveConfig Class - Centralized Configuration Management

```javascript
/**
 * DriveConfig - Centralized configuration management for Drive operations
 * Uses modern API patterns and includes quota management
 * @class
 */
class DriveConfig {
  constructor() {
    this.settings = {
      // API Configuration
      useAdvancedDriveAPI: true, // Enable Drive API v3
      apiVersion: 'v3',
      
      // Default folder settings
      defaultParentFolder: 'root',
      createMissingFolders: true,
      
      // File operation settings
      maxFileSize: 100 * 1024 * 1024, // 100MB
      allowedMimeTypes: [
        'application/vnd.google-apps.document',
        'application/vnd.google-apps.spreadsheet',
        'application/vnd.google-apps.presentation',
        'application/pdf',
        'text/plain',
        'image/jpeg',
        'image/png',
        'application/json'
      ],
      
      // Search and pagination (optimized)
      defaultPageSize: 100,
      maxSearchResults: 1000,
      usePageTokens: true, // Use pagination tokens for large results
      
      // Performance settings (2024+ optimized)
      batchSize: 100, // Increased for better performance
      operationDelay: 50, // Reduced delay with better quota management
      retryAttempts: 3,
      retryBackoff: 'exponential', // exponential or linear
      cacheExpiration: 300000, // 5 minutes
      useCacheService: true, // Use Apps Script CacheService
      
      // Quota Management
      quotaManagement: {
        enabled: true,
        requestsPerMinute: 1000,
        requestsPerDay: 20000,
        trackUsage: true
      },
      
      // Security settings
      validateFileTypes: true,
      scanForMalware: false, // Requires additional setup
      requireSafeNames: true,
      
      // Sharing settings
      defaultSharingRole: 'reader',
      allowExternalSharing: false,
      requireComment: false,
      sendNotificationEmails: false,
      
      // Logging and debugging
      enableLogging: true,
      logLevel: 'info', // error, warn, info, debug
      debugMode: false,
      logToStackdriver: false // Cloud Logging integration
    };
    
    this.mimeTypes = {
      // Google Workspace types
      DOCUMENT: 'application/vnd.google-apps.document',
      SPREADSHEET: 'application/vnd.google-apps.spreadsheet',
      PRESENTATION: 'application/vnd.google-apps.presentation',
      FORM: 'application/vnd.google-apps.form',
      DRAWING: 'application/vnd.google-apps.drawing',
      FOLDER: 'application/vnd.google-apps.folder',
      SHORTCUT: 'application/vnd.google-apps.shortcut',
      
      // Common file types
      PDF: 'application/pdf',
      TEXT: 'text/plain',
      HTML: 'text/html',
      CSV: 'text/csv',
      JSON: 'application/json',
      XML: 'application/xml',
      
      // Image types
      JPEG: 'image/jpeg',
      PNG: 'image/png',
      GIF: 'image/gif',
      SVG: 'image/svg+xml',
      WEBP: 'image/webp',
      
      // Office types
      WORD: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      EXCEL: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      POWERPOINT: 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
      
      // Archive types
      ZIP: 'application/zip',
      RAR: 'application/x-rar-compressed',
      
      // Video types
      MP4: 'video/mp4',
      WEBM: 'video/webm'
    };
    
    this.folderStructure = {
      templates: 'Templates',
      archive: 'Archive',
      shared: 'Shared',
      temp: 'Temp',
      backup: 'Backup',
      reports: 'Reports'
    };
    
    // Initialize quota tracker
    this.quotaTracker = {
      requestCount: 0,
      dailyCount: 0,
      lastReset: new Date(),
      lastMinuteReset: new Date()
    };
    
    console.log('🔧 DriveConfig initialized with optimized settings');
  }

  /**
   * Get configuration setting with dot notation support
   * @param {string} key - Setting key (supports dot notation)
   * @return {*} Setting value
   */
  get(key) {
    return key.split('.').reduce((obj, k) => obj && obj[k], this.settings);
  }

  /**
   * Set configuration setting with validation
   * @param {string} key - Setting key (supports dot notation)
   * @param {*} value - Setting value
   */
  set(key, value) {
    const keys = key.split('.');
    const lastKey = keys.pop();
    const target = keys.reduce((obj, k) => obj[k] = obj[k] || {}, this.settings);
    target[lastKey] = value;
    
    if (this.get('enableLogging')) {
      console.log(`⚙️ Config updated: ${key} = ${JSON.stringify(value)}`);
    }
  }

  /**
   * Update multiple settings at once
   * @param {Object} newSettings - Settings object
   */
  update(newSettings) {
    Object.entries(newSettings).forEach(([key, value]) => {
      this.set(key, value);
    });
  }

  /**
   * Reset to default settings
   */
  reset() {
    this.constructor();
    console.log('🔄 Configuration reset to defaults');
  }

  /**
   * Validate file based on security settings
   * @param {GoogleAppsScript.Drive.File} file - File to validate
   * @return {Object} Validation result
   */
  validateFile(file) {
    const result = {
      isValid: true,
      warnings: [],
      errors: []
    };

    try {
      // Check file size
      const fileSize = file.getSize();
      if (fileSize > this.get('maxFileSize')) {
        result.errors.push(`File size exceeds limit: ${this.formatBytes(fileSize)} > ${this.formatBytes(this.get('maxFileSize'))}`);
        result.isValid = false;
      }

      // Check MIME type
      const mimeType = file.getMimeType();
      if (this.get('validateFileTypes') && !this.get('allowedMimeTypes').includes(mimeType)) {
        result.warnings.push(`File type may not be allowed: ${mimeType}`);
      }

      // Check filename safety
      if (this.get('requireSafeNames')) {
        const unsafeChars = /[<>:"|?*\x00-\x1f]/;
        const fileName = file.getName();
        if (unsafeChars.test(fileName)) {
          result.errors.push('Filename contains unsafe characters');
          result.isValid = false;
        }
        
        // Check for very long filenames
        if (fileName.length > 255) {
          result.errors.push('Filename exceeds 255 characters');
          result.isValid = false;
        }
      }
    } catch (error) {
      result.errors.push(`Validation error: ${error.message}`);
      result.isValid = false;
    }

    return result;
  }

  /**
   * Get MIME type by extension
   * @param {string} extension - File extension
   * @return {string} MIME type
   */
  getMimeTypeByExtension(extension) {
    const extensionMap = {
      'pdf': this.mimeTypes.PDF,
      'txt': this.mimeTypes.TEXT,
      'html': this.mimeTypes.HTML,
      'csv': this.mimeTypes.CSV,
      'json': this.mimeTypes.JSON,
      'xml': this.mimeTypes.XML,
      'jpg': this.mimeTypes.JPEG,
      'jpeg': this.mimeTypes.JPEG,
      'png': this.mimeTypes.PNG,
      'gif': this.mimeTypes.GIF,
      'svg': this.mimeTypes.SVG,
      'webp': this.mimeTypes.WEBP,
      'docx': this.mimeTypes.WORD,
      'xlsx': this.mimeTypes.EXCEL,
      'pptx': this.mimeTypes.POWERPOINT,
      'zip': this.mimeTypes.ZIP,
      'rar': this.mimeTypes.RAR,
      'mp4': this.mimeTypes.MP4,
      'webm': this.mimeTypes.WEBM
    };
    
    return extensionMap[extension.toLowerCase()] || 'application/octet-stream';
  }

  /**
   * Format bytes to human-readable size
   * @param {number} bytes - Bytes to format
   * @return {string} Formatted size
   */
  formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  /**
   * Check and enforce quota limits
   * @return {boolean} Whether operation can proceed
   */
  checkQuota() {
    if (!this.get('quotaManagement.enabled')) return true;
    
    const now = new Date();
    const minutesPassed = (now - this.quotaTracker.lastMinuteReset) / 60000;
    const daysPassed = (now - this.quotaTracker.lastReset) / 86400000;
    
    // Reset minute counter
    if (minutesPassed >= 1) {
      this.quotaTracker.requestCount = 0;
      this.quotaTracker.lastMinuteReset = now;
    }
    
    // Reset daily counter
    if (daysPassed >= 1) {
      this.quotaTracker.dailyCount = 0;
      this.quotaTracker.lastReset = now;
    }
    
    // Check limits
    if (this.quotaTracker.requestCount >= this.get('quotaManagement.requestsPerMinute')) {
      console.warn('⚠️ Per-minute quota limit reached. Waiting...');
      Utilities.sleep(1000);
      return this.checkQuota();
    }
    
    if (this.quotaTracker.dailyCount >= this.get('quotaManagement.requestsPerDay')) {
      throw new Error('Daily quota limit reached. Please try again tomorrow.');
    }
    
    return true;
  }

  /**
   * Track API usage
   */
  trackRequest() {
    if (this.get('quotaManagement.trackUsage')) {
      this.quotaTracker.requestCount++;
      this.quotaTracker.dailyCount++;
    }
  }

  /**
   * Export configuration as JSON
   * @return {string} Configuration JSON
   */
  export() {
    return JSON.stringify({
      settings: this.settings,
      timestamp: new Date(),
      version: '2.0'
    }, null, 2);
  }

  /**
   * Import configuration from JSON
   * @param {string} configJson - Configuration JSON string
   */
  import(configJson) {
    try {
      const config = JSON.parse(configJson);
      this.settings = { ...this.settings, ...config.settings };
      console.log('📥 Configuration imported successfully');
    } catch (error) {
      console.error('❌ Failed to import configuration:', error.message);
      throw new Error(`Configuration import failed: ${error.message}`);
    }
  }
}

// Create global configuration instance
const DRIVE_CONFIG = new DriveConfig();

/**
 * Initialize Google Drive API and required services
 * Modern initialization with Drive API v3 support
 */
function enableDriveServices() {
  try {
    console.log('🔌 Enabling Google Drive services...');
    
    // Check if Drive API v3 is enabled
    if (DRIVE_CONFIG.get('useAdvancedDriveAPI')) {
      try {
        // Test Drive API v3 access
        Drive.Files.list({ pageSize: 1 });
        console.log('✅ Drive API v3 enabled and accessible');
      } catch (error) {
        console.warn('⚠️ Drive API v3 not enabled. Enable it in Services → Drive API');
        console.warn('Falling back to DriveApp service (limited functionality)');
        DRIVE_CONFIG.set('useAdvancedDriveAPI', false);
      }
    }
    
    // Test DriveApp access
    const testFolder = DriveApp.getRootFolder();
    console.log(`✅ DriveApp access confirmed: ${testFolder.getName()}`);
    
    // Initialize folder structure if needed
    if (DRIVE_CONFIG.get('createMissingFolders')) {
      createRequiredFolders();
    }
    
    console.log('🎯 Google Drive services ready');
    return true;
    
  } catch (error) {
    console.error('❌ Failed to enable Drive services:', error.message);
    throw new Error(`Drive services initialization failed: ${error.message}`);
  }
}

/**
 * Create required folder structure
 * @private
 */
function createRequiredFolders() {
  try {
    const rootFolder = DriveApp.getRootFolder();
    const requiredFolders = DRIVE_CONFIG.folderStructure;
    
    Object.entries(requiredFolders).forEach(([key, folderName]) => {
      try {
        const existingFolders = rootFolder.getFoldersByName(folderName);
        if (!existingFolders.hasNext()) {
          const newFolder = rootFolder.createFolder(folderName);
          console.log(`📁 Created folder: ${folderName} (${newFolder.getId()})`);
        }
      } catch (error) {
        console.warn(`⚠️ Could not create folder ${folderName}: ${error.message}`);
      }
    });
    
  } catch (error) {
    console.error('❌ Error creating folder structure:', error.message);
  }
}
```

## 3. Basic Drive Operations

### DriveManager Class - Core Drive Operations (Optimized)

```javascript
/**
 * DriveManager - Core Google Drive operations with modern API patterns
 * Optimized for performance with caching, quota management, and batch operations
 * @class
 */
class DriveManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.operationLog = [];
    this.cache = this.config.get('useCacheService') ? CacheService.getScriptCache() : new Map();
    this.useAdvancedAPI = this.config.get('useAdvancedDriveAPI');
  }

  /**
   * Create a new file in Google Drive with modern API
   * @param {string} fileName - Name of the file
   * @param {string|Blob} content - File content
   * @param {Object} options - Creation options
   * @return {GoogleAppsScript.Drive.File} Created file
   */
  createFile(fileName, content = '', options = {}) {
    const {
      mimeType = 'text/plain',
      parentFolderId = null,
      description = '',
      starred = false,
      properties = {}
    } = options;

    try {
      this.config.checkQuota();
      console.log(`📝 Creating file: ${fileName}`);
      
      this.validateFileName(fileName);
      
      let file;
      
      if (this.useAdvancedAPI && parentFolderId) {
        // Use Drive API v3 for better performance
        const fileMetadata = {
          name: fileName,
          parents: [parentFolderId],
          description: description,
          starred: starred,
          properties: properties
        };
        
        let fileBlob;
        if (typeof content === 'string') {
          fileBlob = Utilities.newBlob(content, mimeType, fileName);
        } else {
          fileBlob = content;
        }
        
        const createdFile = Drive.Files.create(fileMetadata, fileBlob, {
          fields: 'id,name,mimeType,size,createdTime,modifiedTime'
        });
        
        file = DriveApp.getFileById(createdFile.id);
      } else {
        // Fallback to DriveApp
        let fileBlob;
        if (typeof content === 'string') {
          fileBlob = Utilities.newBlob(content, mimeType, fileName);
        } else {
          fileBlob = content;
        }
        
        if (parentFolderId) {
          const parentFolder = this.getFolderById(parentFolderId);
          file = parentFolder.createFile(fileBlob);
        } else {
          file = DriveApp.createFile(fileBlob);
        }
        
        if (description) file.setDescription(description);
        if (starred) file.setStarred(true);
      }
      
      this.config.trackRequest();
      
      // Validate created file
      const validation = this.config.validateFile(file);
      if (!validation.isValid) {
        console.warn('⚠️ File validation warnings:', validation.warnings);
        if (validation.errors.length > 0) {
          file.setTrashed(true);
          throw new Error(`File validation failed: ${validation.errors.join(', ')}`);
        }
      }
      
      this.logOperation('file_created', {
        fileName: fileName,
        fileId: file.getId(),
        mimeType: file.getMimeType(),
        size: file.getSize()
      });
      
      console.log(`✅ File created: ${fileName} (${file.getId()})`);
      return file;
      
    } catch (error) {
      console.error(`❌ Error creating file: ${error.message}`);
      throw error;
    }
  }

  /**
   * Create a new folder with Drive API v3 support
   * @param {string} folderName - Name of the folder
   * @param {Object} options - Creation options
   * @return {GoogleAppsScript.Drive.Folder} Created folder
   */
  createFolder(folderName, options = {}) {
    if (typeof options === 'string') {
      options = { parentFolderId: options };
    }
    
    const {
      parentFolderId = null,
      description = '',
      properties = {}
    } = options;

    try {
      this.config.checkQuota();
      console.log(`📁 Creating folder: ${folderName}`);
      
      this.validateFileName(folderName);
      
      const parentFolder = parentFolderId ? 
        this.getFolderById(parentFolderId) : 
        DriveApp.getRootFolder();
      
      // Check if folder already exists
      const existingFolders = parentFolder.getFoldersByName(folderName);
      if (existingFolders.hasNext()) {
        const existingFolder = existingFolders.next();
        console.log(`📁 Folder already exists: ${folderName} (${existingFolder.getId()})`);
        return existingFolder;
      }
      
      let folder;
      
      if (this.useAdvancedAPI && parentFolderId) {
        // Use Drive API v3
        const folderMetadata = {
          name: folderName,
          mimeType: this.config.mimeTypes.FOLDER,
          parents: [parentFolderId],
          description: description,
          properties: properties
        };
        
        const createdFolder = Drive.Files.create(folderMetadata, null, {
          fields: 'id,name,createdTime'
        });
        
        folder = DriveApp.getFolderById(createdFolder.id);
      } else {
        // Fallback to DriveApp
        folder = parentFolder.createFolder(folderName);
      }
      
      this.config.trackRequest();
      
      this.logOperation('folder_created', {
        folderName: folderName,
        folderId: folder.getId(),
        parentId: parentFolderId
      });
      
      console.log(`✅ Folder created: ${folderName} (${folder.getId()})`);
      return folder;
      
    } catch (error) {
      console.error(`❌ Error creating folder: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get file by ID with intelligent caching
   * @param {string} fileId - File ID
   * @param {boolean} useCache - Whether to use cache
   * @return {GoogleAppsScript.Drive.File} File object
   */
  getFileById(fileId, useCache = true) {
    const cacheKey = `file_${fileId}`;
    
    if (useCache) {
      const cached = this.getCachedData(cacheKey);
      if (cached) {
        console.log(`💾 Cache hit for file: ${fileId}`);
        return cached;
      }
    }

    try {
      this.config.checkQuota();
      console.log(`📄 Getting file: ${fileId}`);
      
      const file = DriveApp.getFileById(fileId);
      this.config.trackRequest();
      
      if (useCache) {
        this.setCachedData(cacheKey, file);
      }
      
      return file;
      
    } catch (error) {
      console.error(`❌ Error getting file: ${error.message}`);
      throw new Error(`File not found or inaccessible: ${fileId}`);
    }
  }

  /**
   * Get folder by ID with intelligent caching
   * @param {string} folderId - Folder ID
   * @param {boolean} useCache - Whether to use cache
   * @return {GoogleAppsScript.Drive.Folder} Folder object
   */
  getFolderById(folderId, useCache = true) {
    const cacheKey = `folder_${folderId}`;
    
    if (useCache) {
      const cached = this.getCachedData(cacheKey);
      if (cached) {
        console.log(`💾 Cache hit for folder: ${folderId}`);
        return cached;
      }
    }

    try {
      this.config.checkQuota();
      console.log(`📁 Getting folder: ${folderId}`);
      
      const folder = DriveApp.getFolderById(folderId);
      this.config.trackRequest();
      
      if (useCache) {
        this.setCachedData(cacheKey, folder);
      }
      
      return folder;
      
    } catch (error) {
      console.error(`❌ Error getting folder: ${error.message}`);
      throw new Error(`Folder not found or inaccessible: ${folderId}`);
    }
  }

  /**
   * Copy file with Drive API v3 support
   * @param {string} fileId - Source file ID
   * @param {Object} options - Copy options
   * @return {GoogleAppsScript.Drive.File} Copied file
   */
  copyFile(fileId, options = {}) {
    const {
      newName = null,
      parentFolderId = null,
      description = null
    } = options;

    try {
      this.config.checkQuota();
      console.log(`📋 Copying file: ${fileId}`);
      
      const sourceFile = this.getFileById(fileId);
      const fileName = newName || `Copy of ${sourceFile.getName()}`;
      
      let copiedFile;
      
      if (this.useAdvancedAPI && parentFolderId) {
        // Use Drive API v3 for better control
        const fileMetadata = {
          name: fileName,
          parents: [parentFolderId],
          description: description
        };
        
        const copied = Drive.Files.copy(fileMetadata, fileId, {
          fields: 'id,name,mimeType'
        });
        
        copiedFile = DriveApp.getFileById(copied.id);
      } else {
        // Fallback to DriveApp
        if (parentFolderId) {
          const parentFolder = this.getFolderById(parentFolderId);
          copiedFile = sourceFile.makeCopy(fileName, parentFolder);
        } else {
          copiedFile = sourceFile.makeCopy(fileName);
        }
        
        if (description) {
          copiedFile.setDescription(description);
        }
      }
      
      this.config.trackRequest();
      
      this.logOperation('file_copied', {
        sourceId: fileId,
        copiedId: copiedFile.getId(),
        fileName: fileName
      });
      
      console.log(`✅ File copied: ${fileName} (${copiedFile.getId()})`);
      return copiedFile;
      
    } catch (error) {
      console.error(`❌ Error copying file: ${error.message}`);
      throw error;
    }
  }

  /**
   * Move file with Drive API v3 support
   * @param {string} fileId - File ID to move
   * @param {string} targetFolderId - Target folder ID
   * @return {GoogleAppsScript.Drive.File} Moved file
   */
  moveFile(fileId, targetFolderId) {
    try {
      this.config.checkQuota();
      console.log(`🚚 Moving file: ${fileId} to ${targetFolderId}`);
      
      const file = this.getFileById(fileId);
      
      if (this.useAdvancedAPI) {
        // Use Drive API v3 for atomic move operation
        const currentParents = file.getParents();
        const previousParents = [];
        
        while (currentParents.hasNext()) {
          previousParents.push(currentParents.next().getId());
        }
        
        Drive.Files.update(
          { addParents: targetFolderId, removeParents: previousParents.join(',') },
          fileId,
          null,
          { fields: 'id,parents' }
        );
      } else {
        // Fallback to DriveApp
        const targetFolder = this.getFolderById(targetFolderId);
        const currentParents = file.getParents();
        
        targetFolder.addFile(file);
        
        while (currentParents.hasNext()) {
          const parent = currentParents.next();
          parent.removeFile(file);
        }
      }
      
      this.config.trackRequest();
      
      this.logOperation('file_moved', {
        fileId: fileId,
        targetFolderId: targetFolderId,
        fileName: file.getName()
      });
      
      console.log(`✅ File moved: ${file.getName()}`);
      return file;
      
    } catch (error) {
      console.error(`❌ Error moving file: ${error.message}`);
      throw error;
    }
  }

  /**
   * Delete file with Drive API v3 support
   * @param {string} fileId - File ID to delete
   * @param {boolean} permanent - Whether to permanently delete
   * @return {boolean} Success status
   */
  deleteFile(fileId, permanent = false) {
    try {
      this.config.checkQuota();
      console.log(`🗑️ Deleting file: ${fileId} (permanent: ${permanent})`);
      
      if (permanent && this.useAdvancedAPI) {
        // Permanent deletion using Drive API v3
        Drive.Files.remove(fileId);
        console.log('✅ File permanently deleted');
      } else {
        // Move to trash
        const file = this.getFileById(fileId);
        const fileName = file.getName();
        file.setTrashed(true);
        console.log(`✅ File moved to trash: ${fileName}`);
      }
      
      this.config.trackRequest();
      
      this.logOperation('file_deleted', {
        fileId: fileId,
        permanent: permanent
      });
      
      // Clear from cache
      this.clearCachedData(`file_${fileId}`);
      
      return true;
      
    } catch (error) {
      console.error(`❌ Error deleting file: ${error.message}`);
      return false;
    }
  }

  /**
   * List files in folder with pagination support
   * @param {string} folderId - Folder ID
   * @param {Object} options - List options
   * @return {Array<GoogleAppsScript.Drive.File>} Array of files
   */
  getFilesInFolder(folderId, options = {}) {
    const {
      pageSize = this.config.get('defaultPageSize'),
      pageToken = null,
      orderBy = 'name',
      fields = 'files(id,name,mimeType,size,createdTime,modifiedTime)',
      maxResults = 1000
    } = options;

    try {
      this.config.checkQuota();
      
      const files = [];
      
      if (this.useAdvancedAPI) {
        // Use Drive API v3 with pagination
        let nextPageToken = pageToken;
        
        do {
          const response = Drive.Files.list({
            q: `'${folderId}' in parents and trashed = false`,
            pageSize: Math.min(pageSize, maxResults - files.length),
            pageToken: nextPageToken,
            orderBy: orderBy,
            fields: `nextPageToken,${fields}`
          });
          
          if (response.files) {
            response.files.forEach(fileData => {
              files.push(DriveApp.getFileById(fileData.id));
            });
          }
          
          nextPageToken = response.nextPageToken;
          
          if (files.length >= maxResults) break;
          
        } while (nextPageToken && this.config.get('usePageTokens'));
        
      } else {
        // Fallback to DriveApp (less efficient)
        const folder = this.getFolderById(folderId);
        const fileIterator = folder.getFiles();
        
        while (fileIterator.hasNext() && files.length < maxResults) {
          files.push(fileIterator.next());
        }
      }
      
      this.config.trackRequest();
      
      console.log(`✅ Retrieved ${files.length} files from folder`);
      return files;
      
    } catch (error) {
      console.error(`❌ Error listing files: ${error.message}`);
      throw error;
    }
  }

  /**
   * Batch file operations with optimized processing
   * @param {Array<Object>} operations - Operations to perform
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchOperations(operations, options = {}) {
    const {
      batchSize = this config.get('batchSize'),
      continueOnError = true,
      useBackoff = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        batch.forEach((operation, index) => {
          try {
            const result = this.executeOperation(operation);
            results.results.push({ success: true, result, operation });
            results.successful++;
          } catch (error) {
            results.results.push({ success: false, error: error.message, operation });
            results.failed++;
            
            if (!continueOnError) throw error;
          }
        });
        
        // Backoff delay between batches
        if (i + batchSize < operations.length && useBackoff) {
          const delay = this.config.get('operationDelay');
          Utilities.sleep(delay);
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful}/${results.total} successful`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Execute single operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
       } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    this.operationLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear cache and logs
   */
  clearCache() {
    if (this.config.get('useCacheService')) {
      this.cache.removeAll([]);
    } else {
      this.cache.clear();
    }
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - Optimized folder operations
 */
class FolderManager {
  /**
   * Find folder by path with caching
   * @param {string} path - Folder path (e.g., "Dept/Type/Level")
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const cacheKey = `folder_path_${path}`;
    const cached = DRIVE_MANAGER.getCachedData(cacheKey);
    
    if (cached) {
      console.log(`💾 Cache hit for folder path: ${path}`);
      return DriveApp.getFolderById(cached.id);
    }
    
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    
    // Cache the result
    DRIVE_MANAGER.setCachedData(cacheKey, { id: current.getId() });
    
    return current;
  }

  /**
   * Get folder statistics
   * @return {Object} Folder stats
   */
  getStats() {
    return {
      scanned: 0,
      found: 0,
      timestamp: new Date()
    };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

## 4. File Management

### FileManager Class - Advanced File Operations (Updated)

```javascript
/**
 * FileManager - Advanced file management with specialized operations
 * Provides high-level file operations with content analysis and processing
 * @class
 */
class FileManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
  }

  /**
   * Create Google Workspace document with content
   * @param {string} docType - Document type ('document', 'spreadsheet', 'presentation')
   * @param {string} title - Document title
   * @param {Object} options - Creation options
   * @return {Object} Created document
   */
  createWorkspaceDocument(docType, title, options = {}) {
    const {
      content = null,
      parentFolderId = null,
      template = null,
      sharing = null
    } = options;

    try {
      console.log(`📄 Creating ${docType}: ${title}`);
      
      let document;
      let file;
      
      switch (docType.toLowerCase()) {
        case 'document':
          document = DocumentApp.create(title);
          if (content) {
            const body = document.getBody();
            if (typeof content === 'string') {
              body.setText(content);
            } else if (content.elements) {
              this.populateDocumentContent(body, content.elements);
            }
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'spreadsheet':
          document = SpreadsheetApp.create(title);
          if (content && content.sheets) {
            this.populateSpreadsheetContent(document, content.sheets);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        case 'presentation':
          document = SlidesApp.create(title);
          if (content && content.slides) {
            this.populatePresentationContent(document, content.slides);
          }
          file = DriveApp.getFileById(document.getId());
          break;
          
        default:
          throw new Error(`Unsupported document type: ${docType}`);
      }
      
      // Move to specified folder
      if (parentFolderId) {
        this.driveManager.moveFile(file.getId(), parentFolderId);
      }
      
      // Apply sharing settings
      if (sharing) {
        // Use PermissionManager to apply sharing
        PERMISSION_MANAGER.shareResource(file.getId(), sharing);
      }
      
      console.log(`✅ ${docType} created: ${title} (${file.getId()})`);
      return {
        document: document,
        file: file,
        id: file.getId(),
        url: file.getUrl()
      };
      
    } catch (error) {
      console.error(`❌ Error creating ${docType}: ${error.message}`);
      throw error;
    }
  }

  /**
   * Upload file from URL with processing options
   * @param {string} url - Source URL
   * @param {string} fileName - Destination filename
   * @param {Object} options - Upload options
   * @return {GoogleAppsScript.Drive.File} Uploaded file
   */
  uploadFromUrl(url, fileName, options = {}) {
    const {
      parentFolderId = null,
      processContent = false,
      convertToGoogleFormat = false,
      maxRetries = 3
    } = options;

    let attempt = 0;
    while (attempt < maxRetries) {
      try {
        console.log(`🌐 Uploading from URL (attempt ${attempt + 1}): ${url}`);
        
        // Fetch content from URL
        const response = UrlFetchApp.fetch(url, {
          followRedirects: true,
          validateHttpsCertificates: true
        });
        
        if (response.getResponseCode() !== 200) {
          throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
        }
        
        const blob = response.getBlob().setName(fileName);
        
        // Detect and validate content type
        const contentType = response.getHeaders()['Content-Type'] || blob.getContentType();
        const validatedMimeType = this.validateAndCorrectMimeType(contentType, fileName);
        
        if (validatedMimeType !== contentType) {
          blob.setContentType(validatedMimeType);
        }
        
        // Create file
        const file = this.driveManager.createFile(fileName, blob, {
          parentFolderId: parentFolderId
        });
        
        // Process content if requested
        if (processContent) {
          this.queueContentProcessing(file.getId());
        }
        
        // Convert to Google format if requested
        if (convertToGoogleFormat) {
          const convertedFile = this.convertToGoogleFormat(file);
          if (convertedFile) {
            file.setTrashed(true);
            return convertedFile;
          }
        }
        
        console.log(`✅ File uploaded: ${fileName} (${file.getId()})`);
        return file;
        
      } catch (error) {
        attempt++;
        console.warn(`⚠️ Upload attempt ${attempt} failed: ${error.message}`);
        
        if (attempt >= maxRetries) {
          console.error(`❌ Upload failed after ${maxRetries} attempts`);
          throw error;
        }
        
        // Wait before retrying
        Utilities.sleep(1000 * attempt);
      }
    }
  }

  /**
   * Batch file operations with progress tracking
   * @param {Array<Object>} operations - Array of file operations
   * @param {Object} options - Batch options
   * @return {Object} Batch results
   */
  batchFileOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      onProgress = null,
      continueOnError = true
    } = options;

    const results = {
      total: operations.length,
      successful: 0,
      failed: 0,
      errors: [],
      results: []
    };

    try {
      console.log(`⚙️ Starting batch operations: ${operations.length} items`);
      
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        for (const operation of batch) {
          try {
            const result = this.executeFileOperation(operation);
            results.results.push({ success: true, result: result, operation: operation });
            results.successful++;
            
          } catch (error) {
            results.errors.push({ operation: operation, error: error.message });
            results.results.push({ success: false, error: error.message, operation: operation });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Batch operations completed: ${results.successful} successful, ${results.failed} failed`);
      return results;
      
    } catch (error) {
      console.error(`❌ Batch operations failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Analyze file content and extract metadata
   * @param {string} fileId - File ID to analyze
   * @return {Object} Content analysis results
   */
  analyzeFileContent(fileId) {
    try {
      console.log(`🔍 Analyzing file content: ${fileId}`);
      
      const file = this.driveManager.getFileById(fileId);
  const mimeType = file.getMimeType();
      
      const analysis = {
        fileId: fileId,
        fileName: file.getName(),
        mimeType: mimeType,
        size: file.getSize(),
        created: file.getDateCreated(),
        modified: file.getLastUpdated(),
        metadata: {},
        content: {},
        insights: []
      };
      
      // Analyze based on file type
      switch (mimeType) {
        case this.config.mimeTypes.DOCUMENT:
          analysis.content = this.analyzeGoogleDocument(fileId);
          break;
          
        case this.config.mimeTypes.SPREADSHEET:
          analysis.content = this.analyzeGoogleSpreadsheet(fileId);
          break;
          
        case this.config.mimeTypes.PRESENTATION:
          analysis.content = this.analyzeGooglePresentation(fileId);
          break;
          
        case this.config.mimeTypes.PDF:
          analysis.content = this.analyzePdfDocument(file);
          break;
          
        default:
          if (mimeType.startsWith('text/')) {
            analysis.content = this.analyzeTextFile(file);
          } else if (mimeType.startsWith('image/')) {
            analysis.content = this.analyzeImageFile(file);
          }
      }
      
      // Generate insights
      analysis.insights = this.generateContentInsights(analysis);
      
      console.log(`✅ Content analysis completed: ${file.getName()}`);
      return analysis;
      
    } catch (error) {
      console.error(`❌ Error analyzing file content: ${error.message}`);
      throw error;
    }
  }

  /**
   * Convert file to Google Workspace format
   * @param {GoogleAppsScript.Drive.File} file - File to convert
   * @return {GoogleAppsScript.Drive.File|null} Converted file or null if not convertible
   */
  convertToGoogleFormat(file) {
    try {
      const mimeType = file.getMimeType();
      const fileName = file.getName();
      console.log(`🔄 Converting to Google format: ${fileName}`);

      // Define supported source mime types and their Google targets
      const conversions = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': this.config.mimeTypes.DOCUMENT,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': this.config.mimeTypes.SPREADSHEET,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': this.config.mimeTypes.PRESENTATION,
        'application/pdf': this.config.mimeTypes.DOCUMENT
      };

      if (!conversions[mimeType]) {
        console.log(`ℹ️ No conversion available for: ${mimeType}`);
        return null;
      }

      // Requires Advanced Drive Service (Resources > Advanced Google services... > Drive API)
      if (typeof Drive !== 'undefined' && Drive.Files && Drive.Files.copy) {
        const resource = {
          title: fileName.replace(/\.[^/.]+$/, ''),
          mimeType: conversions[mimeType]
        };
        const converted = Drive.Files.copy(resource, file.getId(), { convert: true });
        const convertedFile = DriveApp.getFileById(converted.id);
        console.log(`✅ File converted: ${convertedFile.getName()} (${convertedFile.getId()})`);
        return convertedFile;
      } else {
        console.warn('⚠️ Advanced Drive Service not enabled; skipping conversion');
        return null;
      }
    } catch (error) {
      console.error(`❌ Error converting file: ${error.message}`);
      return null;
    }
  }

  /**
   * Execute individual file operation
   * @param {Object} operation - Operation definition
   * @return {*} Operation result
   * @private
   */
  executeFileOperation(operation) {
    const { type, params } = operation;
    
    switch (type) {
      case 'create':
        return this.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.copyFile(params.fileId, params.options);
      case 'move':
        return this.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.deleteFile(params.fileId, params.permanent);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Get/set cached data with CacheService or Map
   * @private
   */
  getCachedData(key) {
    if (this.config.get('useCacheService')) {
      const cached = this.cache.get(key);
      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (e) {
          return null;
        }
      }
    } else {
      const cached = this.cache.get(key);
      if (cached && Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        return cached.data;
      }
    }
    return null;
  }

  setCachedData(key, data) {
    if (this.config.get('useCacheService')) {
      this.cache.put(key, JSON.stringify(data), this.config.get('cacheExpiration') / 1000);
    } else {
      this.cache.set(key, { data, timestamp: Date.now() });
    }
  }

  clearCachedData(key) {
    if (this.config.get('useCacheService')) {
      this.cache.remove(key);
    } else {
      this.cache.delete(key);
    }
  }

  /**
   * Validate filename for security and compatibility
   * @param {string} fileName - Filename to validate
   * @private
   */
  validateFileName(fileName) {
    if (!fileName || typeof fileName !== 'string') {
      throw new Error('Filename must be a non-empty string');
    }
    
    if (fileName.length > 255) {
      throw new Error('Filename too long (max 255 characters)');
    }
    
    if (this.config.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(fileName)) {
        throw new Error('Filename contains unsafe characters');
      }
    }
  }

  /**
   * Log operation for tracking
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.operationLog.push(logEntry);
      
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
      }
      
      if (this.config.get('logToStackdriver')) {
        console.log(JSON.stringify(logEntry));
      }
    }
  }

  /**
   * Get operation statistics
   * @return {Object} Operation statistics
   */
  getOperationStats() {
    const stats = {
      totalOperations: this.operationLog.length,
      operationTypes: {},
      recentActivity: this.operationLog.slice(-10),
      quotaUsage: {
        requestCount: this.config.quotaTracker.requestCount,
        dailyCount: this.config.quotaTracker.dailyCount,
        percentUsed: Math.round((this.config.quotaTracker.dailyCount / this.config.get('quotaManagement.requestsPerDay')) * 100)
      }
    };
    
    }
    
    syncOperation.scheduledTrigger = trigger;
  }

  /**
   * Analyze folder contents for synchronization
   * @param {string} folderId - Folder ID to analyze
   * @param {Object} syncOperation - Sync operation
   * @return {Object} Folder analysis
   * @private
   */
  analyzeFolderContents(folderId, syncOperation) {
    const folder = this.driveManager.getFolderById(folderId);
    const analysis = {
      folder: folder,
      files: [],
      subfolders: [],
      totalSize: 0,
      lastModified: null
    };

    // Get files
    const files = this.driveManager.getFilesInFolder(folderId, {
      includeDetails: true,
      applyFilters: true,
      filters: syncOperation.fileFilters
    });

    files.forEach(file => {
      analysis.files.push({
        id: file.getId(),
        name: file.getName(),
        size: file.getSize(),
        modified: file.getLastUpdated(),
  mimeType: file.getMimeType(),
        checksum: this.calculateFileChecksum(file),
        path: this.getFilePath(file, folder)
      });
      
      analysis.totalSize += file.getSize();
      
      if (!analysis.lastModified || file.getLastUpdated() > analysis.lastModified) {
        analysis.lastModified = file.getLastUpdated();
      }
    });

    // Get subfolders if requested
    if (syncOperation.includeSubfolders) {
      const subfolders = folder.getFolders();
      while (subfolders.hasNext()) {
        const subfolder = subfolders.next();
        analysis.subfolders.push(this.analyzeFolderContents(subfolder.getId(), syncOperation));
      }
    }

    return analysis;
  }

  /**
   * Create synchronization plan
   * @param {Object} sourceAnalysis - Source folder analysis
   * @param {Object} targetAnalysis - Target folder analysis
   * @param {Object} syncOperation - Sync operation
   * @return {Object} Sync plan
   * @private
   */
  createSyncPlan(sourceAnalysis, targetAnalysis, syncOperation) {
    const plan = {
      operations: [],
      conflicts: [],
      summary: {
        toCreate: 0,
        toUpdate: 0,
        toDelete: 0,
        conflicts: 0
      }
    };

    // Create lookup maps
    const sourceMap = new Map();
    sourceAnalysis.files.forEach(file => {
      sourceMap.set(file.path, file);
    });

    const targetMap = new Map();
    targetAnalysis.files.forEach(file => {
      targetMap.set(file.path, file);
    });

    // Plan operations based on sync mode
    if (syncOperation.syncMode === 'unidirectional') {
      this.planUnidirectionalSync(sourceMap, targetMap, plan, syncOperation);
    } else {
      this.planBidirectionalSync(sourceMap, targetMap, plan, syncOperation);
    }

    return plan;
  }

  /**
   * Generate comprehensive sync analysis
   * @param {Object} syncResult - Sync result
   * @param {Object} syncOperation - Sync operation
   * @return {Object} Sync analysis
   * @private
   */
  generateSyncAnalysis(syncResult, syncOperation) {
    return {
      efficiency: this.calculateSyncEfficiency(syncResult),
      dataTransferred: this.calculateDataTransferred(syncResult),
      timeAnalysis: this.analyzeSyncTiming(syncResult),
      conflictAnalysis: this.analyzeConflicts(syncResult),
      recommendations: this.generateSyncRecommendations(syncResult, syncOperation)
    };
  }

  // Additional helper methods would be implemented here...
  
  calculateFileChecksum(file) {
    // Simplified checksum calculation
    return `${file.getName()}_${file.getSize()}_${file.getLastUpdated().getTime()}`;
  }

  getFilePath(file, rootFolder) {
    // Get relative path from root folder
    const parents = file.getParents();
    if (parents.hasNext()) {
      const parent = parents.next();
      if (parent.getId() === rootFolder.getId()) {
        return file.getName();
      }
      // Build path recursively
      return this.buildPath(file, rootFolder);
    }
    return file.getName();
  }

  /**
   * Get synchronization status
   * @param {string} syncId - Sync ID
   * @return {Object} Sync status
   */
  getSyncStatus(syncId) {
    const syncOp = this.syncOperations.get(syncId);
    if (!syncOp) {
      return { error: 'Sync operation not found' };
    }

    const history = this.syncHistory.get(syncId) || [];
    
    return {
      id: syncId,
      status: syncOp.status,
      lastSync: syncOp.lastSync,
      totalSyncs: history.length,
      lastResult: syncOp.lastResult,
      configuration: {
        syncMode: syncOp.syncMode,
        conflictResolution: syncOp.conflictResolution,
        realTime: syncOp.realTime,
        schedule: syncOp.schedule
      }
    };
  }

  /**
   * Stop synchronization
   * @param {string} syncId - Sync ID to stop
   * @return {boolean} Success status
   */
  stopSynchronization(syncId) {
    const syncOp = this.syncOperations.get(syncId);
    if (!syncOp) {
      return false;
    }

    // Remove triggers
    if (syncOp.realTimeTrigger) {
      ScriptApp.deleteTrigger(syncOp.realTimeTrigger);
    }
    if (syncOp.scheduledTrigger) {
      ScriptApp.deleteTrigger(syncOp.scheduledTrigger);
    }

    syncOp.status = 'stopped';
    console.log(`🛑 Synchronization stopped: ${syncId}`);
    return true;
  }
}

// Create global sync system instance
const FILE_SYNC_SYSTEM = new FileSynchronizationSystem();
```

### Project 3: Drive Analytics Dashboard

```javascript
/**
 * Drive Analytics Dashboard
 * Interactive dashboard for Drive insights and management
 * @class
 */
class DriveAnalyticsDashboard {
  constructor() {
    this.analytics = DRIVE_ANALYTICS;
    this.driveManager = DRIVE_MANAGER;
    this.dashboardSpreadsheet = null;
    this.charts = new Map();
  }

  /**
   * Create comprehensive analytics dashboard
   * @param {Object} dashboardConfig - Dashboard configuration
   * @return {Object} Dashboard creation result
   */
  createDashboard(dashboardConfig = {}) {
    try {
      const {
        title = 'Drive Analytics Dashboard',
        updateFrequency = 'daily',
        includeCharts = true,
        includeRecommendations = true,
        shareWithTeam = false
      } = dashboardConfig;

      console.log('📊 Creating Drive Analytics Dashboard...');

      // Create dashboard spreadsheet
      this.dashboardSpreadsheet = SpreadsheetApp.create(title);
      
      // Set up dashboard sheets
      this.setupDashboardSheets();
      
      // Generate initial data
      this.updateDashboard();
      
      // Set up automated updates
      if (updateFrequency !== 'manual') {
        this.setupDashboardUpdates(updateFrequency);
      }
      
      // Share with team if requested
      if (shareWithTeam) {
        this.shareDashboard();
      }

      const result = {
        dashboardUrl: this.dashboardSpreadsheet.getUrl(),
        spreadsheetId: this.dashboardSpreadsheet.getId(),
        updateFrequency: updateFrequency,
        created: new Date()
      };

      console.log(`✅ Dashboard created: ${result.dashboardUrl}`);
      return result;

    } catch (error) {
      console.error(`❌ Dashboard creation failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Update dashboard with latest data
   * @return {Object} Update result
   */
  updateDashboard() {
    try {
      console.log('🔄 Updating dashboard...');

      // Generate analytics report
      const report = this.analytics.generateAnalyticsReport({
        period: 'month',
        includeCharts: true,
        detailed: true
      });

      // Update each sheet
      this.updateOverviewSheet(report);
      this.updateStorageSheet(report);
      this.updateActivitySheet(report);
      this.updateSharingSheet(report);
      this.updateRecommendationsSheet(report);
      
      // Update charts
      this.updateCharts(report);
      
      // Update last refresh timestamp
      this.updateRefreshInfo();

      console.log('✅ Dashboard updated successfully');
      return { updated: new Date(), report: report };

    } catch (error) {
      console.error(`❌ Dashboard update failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Set up dashboard sheets
   * @private
   */
  setupDashboardSheets() {
    const sheets = [
      'Overview',
      'Storage Analysis',
      'Activity Trends',
      'Sharing Analysis',
      'Recommendations',
      'Charts',
      'Raw Data'
    ];

    // Rename default sheet to Overview
    const defaultSheet = this.dashboardSpreadsheet.getSheets()[0];
    defaultSheet.setName('Overview');

    // Create additional sheets
    sheets.slice(1).forEach(sheetName => {
      this.dashboardSpreadsheet.insertSheet(sheetName);
    });

    // Set up sheet headers and formatting
    this.formatDashboardSheets();
  }

  /**
   * Generate automated insights
   * @param {Object} report - Analytics report
   * @return {Array<Object>} Generated insights
   */
  generateAutomatedInsights(report) {
    const insights = [];

    // Storage insights
    if (report.data.storage.totalStorage > 10 * 1024 * 1024 * 1024) { // > 10GB
      insights.push({
        type: 'storage',
        severity: 'high',
        title: 'High Storage Usage',
        description: 'Your Drive is using significant storage space.',
        value: this.analytics.formatBytes(report.data.storage.totalStorage),
        recommendation: 'Consider archiving old files or cleaning up duplicates.'
      });
    }

    // Activity insights
    const recentActivity = report.data.activity.totalActivity;
    if (recentActivity < 10) {
      insights.push({
        type: 'activity',
        severity: 'low',
        title: 'Low Drive Activity',
        description: 'Drive activity has been lower than usual.',
        value: `${recentActivity} files modified`,
        recommendation: 'This might indicate reduced productivity or file consolidation.'
      });
    }

    // Sharing insights
    if (report.data.sharing.publicFiles > 0) {
      insights.push({
        type: 'security',
        severity: 'medium',
        title: 'Public Files Detected',
        description: 'Some files are publicly accessible.',
        value: `${report.data.sharing.publicFiles} public files`,
        recommendation: 'Review public file permissions for security.'
      });
    }

    // Duplicate insights
    if (report.data.detailed && report.data.detailed.duplicates.length > 5) {
      insights.push({
        type: 'efficiency',
        severity: 'medium',
        title: 'Multiple Duplicate Files',
        description: 'Several duplicate files are taking up space.',
        value: `${report.data.detailed.duplicates.length} duplicate groups`,
        recommendation: 'Clean up duplicates to save storage and improve organization.'
      });
    }

    return insights;
  }
}

/**
 * Automated dashboard update function
 */
function updateDashboardScheduled() {
  try {
    console.log('⏰ Running scheduled dashboard update...');
    
    // Assuming we have a global dashboard instance
    if (typeof ANALYTICS_DASHBOARD !== 'undefined') {
      ANALYTICS_DASHBOARD.updateDashboard();
    }
    
    console.log('✅ Scheduled dashboard update completed');
    
  } catch (error) {
    console.error('❌ Scheduled dashboard update failed:', error.message);
  }
}

// Create global dashboard instance
const ANALYTICS_DASHBOARD = new DriveAnalyticsDashboard();
```

---

## 11. Performance Optimization and Best Practices

### Performance Tips

```javascript
/**
 * Performance optimization utilities and best practices
 * @namespace
 */
const DrivePerformanceUtils = {

  /**
   * Optimize API calls using batch operations
   * @param {Array} operations - Operations to batch
   * @return {Array} Results
   */
  batchApiCalls: function(operations) {
    const results = [];
    const batchSize = 10; // Optimal batch size
    
    for (let i = 0; i < operations.length; i += batchSize) {
      const batch = operations.slice(i, i + batchSize);
      
      // Process batch
      batch.forEach(operation => {
        try {
          const result = operation.execute();
          results.push(result);
        } catch (error) {
          console.error(`Batch operation failed: ${error.message}`);
          results.push({ error: error.message });
        }
      });
      
      // Rate limiting delay
      if (i + batchSize < operations.length) {
        Utilities.sleep(100); // 100ms delay between batches
      }
    }
    
    return results;
  },

  /**
   * Implement smart caching for frequently accessed data
   * @param {string} cacheKey - Cache key
   * @param {Function} dataGenerator - Function to generate data if not cached
   * @param {number} ttl - Time to live in seconds
   * @return {*} Cached or generated data
   */
  smartCache: function(cacheKey, dataGenerator, ttl = 3600) {
    const cache = CacheService.getScriptCache();
    const cached = cache.get(cacheKey);
    
    if (cached) {
      return JSON.parse(cached);
    }
    
    const data = dataGenerator();
    cache.put(cacheKey, JSON.stringify(data), ttl);
    
    return data;
  },

  /**
   * Optimize folder iteration with early termination
   * @param {Folder} folder - Folder to iterate
   * @param {Function} condition - Condition to check
   * @param {Object} options - Iteration options
   * @return {Array} Matching items
   */
  optimizedFolderIteration: function(folder, condition, options = {}) {
    const {
      maxResults = 100,
      includeSubfolders = true,
      earlyTermination = true
    } = options;
    
    const results = [];
    let count = 0;
    
    // Iterate files
    const files = folder.getFiles();
    while (files.hasNext() && count < maxResults) {
      const file = files.next();
      
      if (condition(file)) {
        results.push(file);
        count++;
        
        if (earlyTermination && count >= maxResults) {
          break;
        }
      }
    }
    
    // Iterate subfolders if requested
    if (includeSubfolders && count < maxResults) {
      const subfolders = folder.getFolders();
      while (subfolders.hasNext() && count < maxResults) {
        const subfolder = subfolders.next();
        const subResults = this.optimizedFolderIteration(
          subfolder, 
          condition, 
          { ...options, maxResults: maxResults - count }
        );
        
        results.push(...subResults);
        count += subResults.length;
      }
    }
    
    return results;
  },

  /**
   * Memory-efficient file processing
   * @param {Array} files - Files to process
   * @param {Function} processor - Processing function
   * @param {Object} options - Processing options
   * @return {Array} Results
   */
  memoryEfficientProcessing: function(files, processor, options = {}) {
    const {
      chunkSize = 50,
      clearCacheAfterChunk = true
    } = options;
    
    const results = [];
    
    for (let i = 0; i < files.length; i += chunkSize) {
      const chunk = files.slice(i, i + chunkSize);
      
      // Process chunk
      chunk.forEach(file => {
        try {
          const result = processor(file);
          results.push(result);
        } catch (error) {
          console.error(`Processing failed for ${file.getName()}: ${error.message}`);
        }
      });
      
      // Clear cache to free memory
      if (clearCacheAfterChunk) {
        Utilities.sleep(10); // Allow garbage collection
      }
    }
    
    return results;
  },

  /**
   * Monitor and log performance metrics
   * @param {string} operationName - Operation name
   * @param {Function} operation - Operation to monitor
   * @return {*} Operation result
   */
  monitorPerformance: function(operationName, operation) {
    const startTime = new Date();
    const startMemory = this.getMemoryUsage();
    
    try {
      console.log(`⏱️ Starting operation: ${operationName}`);
      
      const result = operation();
      
      const endTime = new Date();
      const duration = endTime - startTime;
      const endMemory = this.getMemoryUsage();
      
      console.log(`✅ Operation completed: ${operationName}`);
      console.log(`   Duration: ${duration}ms`);
      console.log(`   Memory: ${startMemory}MB → ${endMemory}MB`);
      
      return result;
      
    } catch (error) {
      const endTime = new Date();
      const duration = endTime - startTime;
      
      console.error(`❌ Operation failed: ${operationName}`);
      console.error(`   Duration: ${duration}ms`);
      console.error(`   Error: ${error.message}`);
      
      throw error;
    }
  },

  /**
   * Get approximate memory usage
   * @return {number} Memory usage in MB
   */
  getMemoryUsage: function() {
    // Approximate memory usage (not exact in Apps Script)
    return Math.round(DriveApp.getStorageUsed() / (1024 * 1024));
  }
};

/**
 * Best practices for Drive automation
 * @namespace
 */
const DriveBestPractices = {

  /**
   * Error handling patterns
   */
  errorHandling: {
    
    /**
     * Robust error handling with retry logic
     * @param {Function} operation - Operation to execute
     * @param {Object} options - Error handling options
     * @return {*} Operation result
     */
    withRetry: function(operation, options = {}) {
      const {
        maxRetries = 3,
        initialDelay = 1000,
        backoffMultiplier = 2,
        retryableErrors = ['Service unavailable', 'Rate limit exceeded']
      } = options;
      
      let attempt = 0;
      let delay = initialDelay;
      
      while (attempt < maxRetries) {
        try {
          return operation();
        } catch (error) {
          attempt++;
          
          const isRetryable = retryableErrors.some(retryableError =>
            error.message.includes(retryableError)
          );
          
          if (attempt >= maxRetries || !isRetryable) {
            throw error;
          }
          
          console.warn(`⚠️ Attempt ${attempt} failed, retrying in ${delay}ms: ${error.message}`);
          Utilities.sleep(delay);
          delay *= backoffMultiplier;
        }
      }
    },

    /**
     * Graceful degradation pattern
     * @param {Function} primaryOperation - Primary operation
     * @param {Function} fallbackOperation - Fallback operation
     * @return {*} Operation result
     */
    withFallback: function(primaryOperation, fallbackOperation) {
      try {
        return primaryOperation();
      } catch (error) {
        console.warn(`⚠️ Primary operation failed, using fallback: ${error.message}`);
        return fallbackOperation();
      }
    }
  },

  /**
   * Security best practices
   */
  security: {
    
    /**
     * Validate file access permissions
     * @param {File} file - File to validate
     * @param {string} requiredAccess - Required access level
     * @return {boolean} Access validation result
     */
    validateFileAccess: function(file, requiredAccess = 'view') {
      try {
        const access = file.getSharingAccess();
        const permission = file.getSharingPermission();
        
        switch (requiredAccess) {
          case 'view':
            return true; // If we can get the file, we can view it
          case 'edit':
            return permission === DriveApp.Permission.EDIT;
          case 'comment':
            return permission === DriveApp.Permission.COMMENT || 
                   permission === DriveApp.Permission.EDIT;
          default:
            return false;
        }
      } catch (error) {
        console.error(`❌ File access validation failed: ${error.message}`);
        return false;
      }
    },

    /**
     * Sanitize file names for security
     * @param {string} fileName - Original file name
     * @return {string} Sanitized file name
     */
    sanitizeFileName: function(fileName) {
      // Remove potentially dangerous characters
      return fileName
        .replace(/[<>:"/\\|?*]/g, '_')
        .replace(/\.\./g, '_')
        .substring(0, 255); // Limit length
    },

    /**
     * Audit file sharing settings
     * @param {File} file - File to audit
     * @return {Object} Security audit result
     */
    auditFileSecurity: function(file) {
      const audit = {
        fileName: file.getName(),
        isPublic: false,
        hasExternalSharing: false,
        viewers: [],
        editors: [],
        securityScore: 100,
        recommendations: []
      };

      try {
        const access = file.getSharingAccess();
        const permission = file.getSharingPermission();

        // Check if file is public
        if (access === DriveApp.Access.ANYONE || 
            access === DriveApp.Access.ANYONE_WITH_LINK) {
          audit.isPublic = true;
          audit.securityScore -= 50;
          audit.recommendations.push('Consider restricting public access');
        }

        // Check for external sharing
        // This would require more detailed permission analysis
        
        return audit;
        
      } catch (error) {
        audit.error = error.message;
        return audit;
      }
    }
  },

  /**
   * Maintenance best practices
   */
  maintenance: {
    
    /**
     * Clean up old temporary files
     * @param {number} daysOld - Files older than this many days
     * @return {Object} Cleanup result
     */
    cleanupTempFiles: function(daysOld = 7) {
      try {
        console.log(`🧹 Cleaning up temporary files older than ${daysOld} days...`);
        
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() - daysOld);
        
        const tempFiles = DriveApp.searchFiles(
          `(title contains "temp" or title contains "tmp") and modifiedDate < "${cutoffDate.toISOString()}"`
        );
        
        let deleted = 0;
        while (tempFiles.hasNext()) {
          const file = tempFiles.next();
          try {
            file.setTrashed(true);
            deleted++;
          } catch (error) {
            console.warn(`⚠️ Could not delete temp file: ${file.getName()}`);
          }
        }
        
        console.log(`✅ Cleaned up ${deleted} temporary files`);
        return { deleted: deleted, cutoffDate: cutoffDate };
        
      } catch (error) {
        console.error(`❌ Cleanup failed: ${error.message}`);
        throw error;
      }
    },

    /**
     * Archive old files automatically
     * @param {Object} archiveConfig - Archive configuration
     * @return {Object} Archive result
     */
    autoArchiveFiles: function(archiveConfig) {
      const {
        sourceFolderId,
        archiveFolderId,
        daysOld = 365,
        fileTypes = [],
        preserveStructure = true
      } = archiveConfig;

      try {
        console.log(`📦 Auto-archiving files older than ${daysOld} days...`);
        
        const sourceFolder = DriveApp.getFolderById(sourceFolderId);
        const archiveFolder = DriveApp.getFolderById(archiveFolderId);
        
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() - daysOld);
        
        let archived = 0;
        let query = `modifiedDate < "${cutoffDate.toISOString()}"`;
        
        if (fileTypes.length > 0) {
          const typeQuery = fileTypes.map(type => `mimeType contains "${type}"`).join(' or ');
          query += ` and (${typeQuery})`;
        }
        
        const oldFiles = DriveApp.searchFiles(query);
        
        while (oldFiles.hasNext()) {
          const file = oldFiles.next();
          
          // Check if file is in source folder (or subfolders)
          if (this.isFileInFolder(file, sourceFolder)) {
            try {
              // Move to archive
              file.moveTo(archiveFolder);
              archived++;
              
              if (archived % 10 === 0) {
                console.log(`  Archived ${archived} files so far...`);
              }
              
            } catch (error) {
              console.warn(`⚠️ Could not archive file: ${file.getName()}`);
            }
          }
        }
        
        console.log(`✅ Archived ${archived} old files`);
        return { archived: archived, cutoffDate: cutoffDate };
        
      } catch (error) {
        console.error(`❌ Auto-archive failed: ${error.message}`);
        throw error;
      }
    },

    /**
     * Check if file is in folder hierarchy
     * @param {File} file - File to check
     * @param {Folder} targetFolder - Target folder
     * @return {boolean} Whether file is in folder
     * @private
     */
    isFileInFolder: function(file, targetFolder) {
      const parents = file.getParents();
      while (parents.hasNext()) {
        const parent = parents.next();
        if (parent.getId() === targetFolder.getId()) {
          return true;
        }
        // Could recursively check parent hierarchy
      }
      return false;
    }
  }
};
```

---

## 12. Testing Framework

```javascript
/**
 * Testing framework for Drive operations
 * @namespace
 */
const DriveTestFramework = {
  
  testResults: [],
  setupTeardownTasks: [],
  
  /**
   * Run comprehensive test suite
   * @return {Object} Test results
   */
  runTestSuite: function() {
    console.log('🧪 Running Drive automation test suite...');
    
    this.testResults = [];
    
    try {
      // Setup
      this.runSetup();
      
      // Core functionality tests
      this.testDriveManager();
      this.testFileManager();
      this.testFolderManager();
      this.testSearchFunctionality();
      this.testPermissionManager();
      this.testBatchProcessing();
      this.testAnalytics();
      
      // Integration tests
      this.testIntegrationScenarios();
      
      // Cleanup
      this.runTeardown();
      
    } catch (error) {
      console.error(`❌ Test suite failed: ${error.message}`);
    }
    
    // Generate test report
    const report = this.generateTestReport();
    console.log('✅ Test suite completed');
    
    return report;
  },

  /**
   * Test Drive Manager functionality
   * @private
   */
  testDriveManager: function() {
    console.log('Testing DriveManager...');
    
    // Test file creation
    this.runTest('DriveManager.createFile', () => {
      const file = DRIVE_MANAGER.createFile('Test Document.txt', 'Test content', { mimeType: 'text/plain' });
      this.assert(file !== null, 'File should be created');
      this.assert(file.getName() === 'Test Document.txt', 'File name should match');
      file.setTrashed(true);
      return file;
    });
    
    // Test folder creation
    this.runTest('DriveManager.createFolder', () => {
  const folder = DRIVE_MANAGER.createFolder('Test Folder');
      
      this.assert(folder !== null, 'Folder should be created');
      this.assert(folder.getName() === 'Test Folder', 'Folder name should match');
  folder.setTrashed(true);
      return folder;
    });
  },

  /**
   * Test File Manager functionality
   * @private
   */
  testFileManager: function() {
    console.log('Testing FileManager...');
    
    this.runTest('FileManager.createWorkspaceDocument', () => {
      const created = FILE_MANAGER.createWorkspaceDocument('document', 'Unit Test Doc');
      this.assert(created && created.id, 'Doc should be created');
      DriveApp.getFileById(created.id).setTrashed(true);
      return created;
    });
  },

  /**
   * Test Search functionality
   * @private
   */
  testSearchFunctionality: function() {
    console.log('Testing Search functionality...');
    
    this.runTest('DriveSearcher.advancedSearch', () => {
      const results = DRIVE_SEARCHER.advancedSearch({ name: { pattern: 'Test', exactMatch: false } }, { maxResults: 5 });
      this.assert(Array.isArray(results), 'Should return array');
      return results;
    });
  },

  /**
   * Run individual test
   * @param {string} testName - Test name
   * @param {Function} testFunction - Test function
   * @private
   */
  runTest: function(testName, testFunction) {
    const startTime = new Date();
    
    try {
      console.log(`  🔬 Running test: ${testName}`);
      
      const result = testFunction();
      
      const duration = new Date() - startTime;
      
      this.testResults.push({
        name: testName,
        status: 'passed',
        duration: duration,
        result: result
      });
      
      console.log(`  ✅ Test passed: ${testName} (${duration}ms)`);
      
    } catch (error) {
      const duration = new Date() - startTime;
      
      this.testResults.push({
        name: testName,
        status: 'failed',
        duration: duration,
        error: error.message
      });
      
      console.error(`  ❌ Test failed: ${testName} - ${error.message}`);
    }
  },

  /**
   * Assert condition
   * @param {boolean} condition - Condition to check
   * @param {string} message - Error message if condition fails
   */
  assert: function(condition, message) {
    if (!condition) {
      throw new Error(`Assertion failed: ${message}`);
    }
  },

  /**
   * Generate test report
   * @return {Object} Test report
   * @private
   */
  generateTestReport: function() {
    const passed = this.testResults.filter(r => r.status === 'passed').length;
    const failed = this.testResults.filter(r => r.status === 'failed').length;
    const totalDuration = this.testResults.reduce((sum, r) => sum + r.duration, 0);
    
    const report = {
      summary: {
        total: this.testResults.length,
        passed: passed,
        failed: failed,
        passRate: Math.round((passed / this.testResults.length) * 100),
        totalDuration: totalDuration
      },
      details: this.testResults,
      generated: new Date()
    };
    
    console.log(`📊 Test Summary: ${passed}/${this.testResults.length} passed (${report.summary.passRate}%)`);
    
    return report;
  },

  /**
   * Setup test environment
   * @private
   */
  runSetup: function() {
    console.log('🔧 Setting up test environment...');
    // Setup code here
  },

  /**
   * Teardown test environment
   * @private
   */
  runTeardown: function() {
    console.log('🧹 Cleaning up test environment...');
    // Cleanup code here
  }
};

/**
 * Quick test runner function
 */
function runDriveTests() {
  return DriveTestFramework.runTestSuite();
}
```

---

## 13. Conclusion and Next Steps

This comprehensive Google Drive automation tutorial provides a complete ecosystem for managing Google Drive programmatically. The class-based architecture offers:

### Key Features

- **Modular Design**: Each class handles specific functionality (files, folders, search, permissions, etc.)
- **Enterprise-Ready**: Robust error handling, logging, and performance optimization
- **Scalable**: Batch processing capabilities for large-scale operations
- **Intelligent**: Analytics and insights for data-driven decisions
- **Secure**: Built-in security best practices and permission management

### Architecture Benefits

- **Maintainable**: Clear separation of concerns and well-documented code
- **Extensible**: Easy to add new features and functionality
- **Testable**: Comprehensive testing framework included
- **Professional**: Production-ready with monitoring and error handling

### Next Steps

1. **Customize Configuration**: Modify `DriveConfig` for your specific needs
2. **Implement Workflows**: Use the examples as templates for your automation
3. **Monitor Performance**: Utilize the analytics dashboard for insights
4. **Scale Operations**: Leverage batch processing for large datasets
5. **Enhance Security**: Implement additional security measures as needed

### Advanced Topics to Explore

- Custom trigger implementations for real-time automation
- Integration with other Google Workspace services
- Machine learning integration for intelligent file classification
- Custom reporting and visualization solutions
- Multi-tenant architecture for organization-wide deployments

This tutorial provides the foundation for building sophisticated Google Drive automation solutions that can handle enterprise-scale requirements while maintaining code quality and security standards.

---

### Create Nested Folders Function

```javascript
/**
 * Creates nested folders structure in Google Drive
 * @param {string} folderPath - Path of folders separated by forward slashes (e.g., "Projects/2023/Q1")
 * @param {string} parentFolderId - ID of the root folder to create within (optional)
 * @return {GoogleAppsScript.Drive.Folder} Root created folder
 */
function createNestedFolders(folderPath, parentFolderId = null) {
  try {
    const folders = folderPath.split('/');
    let currentFolder;
    
    if (parentFolderId) {
      currentFolder = DriveApp.getFolderById(parentFolderId);
    } else {
      currentFolder = DriveApp.getRootFolder();
    }
    
    for (let i = 0; i < folders.length; i++) {
      const folderName = folders[i];
      
      try {
        // Try to get existing folder
        let subfolder = currentFolder.getFoldersByName(folderName).next();
        if (!subfolder) {
          subfolder = currentFolder.createFolder(folderName);
        }
        currentFolder = subfolder;
      } catch (e) {
        // If no folders found, create it
        const newFolder = currentFolder.createFolder(folderName);
        currentFolder = newFolder;
      }
    }
    
    return currentFolder;
  } catch (error) {
    console.error('Error creating nested folders:', error);
    throw new Error(`Failed to create nested folders: ${error.message}`);
  }
}
```

### Move File Between Folders

```javascript
/**
 * Moves a file from one folder to another
 * @param {string} fileId - ID of the file to move
 * @param {string} sourceFolderId - Source folder ID (optional, if not provided will find current)
 * @param {string} destinationFolderId - Destination folder ID (required)
 * @return {GoogleAppsScript.Drive.File} Moved file object
 */
function moveFile(fileId, sourceFolderId = null, destinationFolderId) {
  try {
    const file = DriveApp.getFileById(fileId);
    
    // If no source folder specified, find it automatically
    if (!sourceFolderId) {
      const parents = file.getParents();
      while (parents.hasNext()) {
        const parent = parents.next();
        sourceFolderId = parent.getId();
        break;
      }
    }
    
    // Remove from current folder and add to destination
    if (sourceFolderId && sourceFolderId !== destinationFolderId) {
      const sourceFolder = DriveApp.getFolderById(sourceFolderId);
      const destinationFolder = DriveApp.getFolderById(destinationFolderId);
      
      sourceFolder.removeFile(file);
      destinationFolder.addFile(file);
    }
    
    return file;
  } catch (error) {
    console.error('Error moving file:', error);
    throw new Error(`Failed to move file: ${error.message}`);
  }
}
```

### Get Folder Contents

```javascript
/**
 * Gets all files and folders in a specified folder with optional filtering
 * @param {string} folderId - ID of the folder to list contents
 * @param {boolean} includeSubfolders - Whether to include subfolder contents (optional, default: false)
 * @param {string} typeFilter - Filter by file type ('all', 'file', 'folder') (optional, default: 'all')
 * @return {Array<{id: string, name: string, type: string}>} Array of folder/file objects
 */
function getFolderContents(folderId, includeSubfolders = false, typeFilter = 'all') {
  try {
    const folder = DriveApp.getFolderById(folderId);
    const contents = [];
    
    // Get files in current folder
    const files = folder.getFiles();
    
    while (files.hasNext()) {
      const file = files.next();
      
      if (typeFilter === 'all' || typeFilter === 'file') {
        contents.push({
          id: file.getId(),
          name: file.getName(),
          type: 'file',
          size: file.getSize(),
          createdDate: file.getDateCreated()
        });
      }
    }
    
    // Get folders in current folder
    const folders = folder.getFolders();
    
    while (folders.hasNext()) {
      const subfolder = folders.next();
      
      if (typeFilter === 'all' || typeFilter === 'folder') {
        contents.push({
          id: subfolder.getId(),
          name: subfolder.getName(),
          type: 'folder',
          size: null,
          createdDate: subfolder.getDateCreated()
        });
      }
    }
    
    // Recursively get subfolders if requested
    if (includeSubfolders) {
      const subFolders = folder.getFolders();
      
      while (subFolders.hasNext()) {
        const subFolder = subFolders.next();
        const subContents = getFolderContents(subFolder.getId(), true, typeFilter);
        contents.push(...subContents);
      }
    }
    
    return contents;
  } catch (error) {
    console.error('Error getting folder contents:', error);
    throw new Error(`Failed to get folder contents: ${error.message}`);
  }
}
```

## Search and Query Functions

### Search Files by Name Pattern

```javascript
/**
 * Searches for files matching a name pattern in Drive
 * @param {string} pattern - Pattern to search for (supports wildcards)
 * @param {string} parentFolderId - Parent folder ID (optional)
 * @param {number} maxResults - Maximum number of results (optional, default: 50)
 * @return {Array<GoogleAppsScript.Drive.File>} Array of matching files
 */
function searchFilesByName(pattern, parentFolderId = null, maxResults = 50) {
  try {
  let query = `title contains '${pattern}' and trashed = false`;
    
    if (parentFolderId) {
      query += ` and '${parentFolderId}' in parents`;
    }
    
    const files = DriveApp.searchFiles(query);
    const results = [];
    
    while (files.hasNext() && results.length < maxResults) {
      results.push(files.next());
    }
    
    return results;
  } catch (error) {
    console.error('Error searching files:', error);
    throw new Error(`Failed to search files: ${error.message}`);
  }
}
```

### Search Files by MIME Type

```javascript
/**
 * Searches for files of a specific MIME type in Drive
 * @param {string} mimeType - MIME type to search for (e.g., 'application/vnd.google-apps.spreadsheet')
 * @param {string} parentFolderId - Parent folder ID (optional)
 * @param {number} maxResults - Maximum number of results (optional, default: 50)
 * @return {Array<GoogleAppsScript.Drive.File>} Array of matching files
 */
function searchFilesByMimeType(mimeType, parentFolderId = null, maxResults = 50) {
  try {
  let query = `mimeType = '${mimeType}' and trashed = false`;
    
    if (parentFolderId) {
      query += ` and '${parentFolderId}' in parents`;
    }
    
    const files = DriveApp.searchFiles(query);
    const results = [];
    
    while (files.hasNext() && results.length < maxResults) {
      results.push(files.next());
    }
    
    return results;
  } catch (error) {
    console.error('Error searching files by MIME type:', error);
    throw new Error(`Failed to search files: ${error.message}`);
  }
}
```

### Advanced Search Function

```javascript
/**
 * Performs advanced searches with multiple criteria
 * @param {Object} options - Search criteria object
 * @param {string} options.nameContains - Name pattern (optional)
 * @param {string} options.mimeType - MIME type filter (optional)
 * @param {string} options.parentFolderId - Parent folder ID (optional)
 * @param {number} options.maxResults - Maximum results (optional, default: 50)
 * @param {Date} options.createdAfter - Created after date (optional)
 * @return {Array<GoogleAppsScript.Drive.File>} Array of matching files
 */
function advancedSearch(options = {}) {
  try {
    const {
  nameContains = null,
      mimeType = null,
      parentFolderId = null,
      maxResults = 50,
      createdAfter = null
    } = options;
    
    let query = 'trashed = false';
    
    if (nameContains) {
  query += ` and title contains '${nameContains}'`;
    }
    
    if (mimeType) {
      query += ` and mimeType = '${mimeType}'`;
    }
    
    if (parentFolderId) {
      query += ` and '${parentFolderId}' in parents`;
    }
    
    if (createdAfter) {
  const dateStr = Utilities.formatDate(createdAfter, Session.getScriptTimeZone(), 'yyyy-MM-dd');
  query += ` and createdDate > '${dateStr}T00:00:00'`;
    }
    
    const files = DriveApp.searchFiles(query);
    const results = [];
    
    while (files.hasNext() && results.length < maxResults) {
      results.push(files.next());
    }
    
    return results;
  } catch (error) {
    console.error('Error in advanced search:', error);
    throw new Error(`Failed to perform advanced search: ${error.message}`);
  }
}
```

## File Sharing and Permissions

### Share File with Email

```javascript
/**
 * Shares a file with specified email address with optional permissions
 * @param {string} fileId - ID of the file to share
 * @param {string} emailAddress - Email address to share with
 * @param {string} role - Permission level ('reader', 'writer', 'commenter') (optional, default: 'reader')
 * @param {boolean} notify = Whether to send notification email (optional, default: true)
 * @return {boolean} Success status
 */
function shareFile(fileId, emailAddress, role = 'reader', notify = true) {
  try {
    const file = DriveApp.getFileById(fileId);
    
    // Set sharing permissions
    if (role === 'writer' || role === 'editor') {
      file.addEditor(emailAddress);
    } else {
      // Apps Script does not expose commenter add; fallback to viewer
      file.addViewer(emailAddress);
    }
    
    // Send notification if requested
    if (notify) {
      const subject = `File Access Granted: ${file.getName()}`;
      const body = `You have been granted ${role} access to the file "${file.getName()}".`;
      
      MailApp.sendEmail(emailAddress, subject, body);
    }
    
    return true;
  } catch (error) {
    console.error('Error sharing file:', error);
    throw new Error(`Failed to share file: ${error.message}`);
  }
}
```

### Set File Permissions

```javascript
/**
 * Sets permissions for a file using various methods
 * @param {string} fileId - ID of the file
 * @param {Object} permissions - Permission settings object
 * @return {boolean} Success status
 */
function setFilePermissions(fileId, permissions = {}) {
  try {
    const file = DriveApp.getFileById(fileId);
    
    // Set sharing options
    if (permissions.isPublic !== undefined) {
      if (permissions.isPublic) {
        file.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);
      } else {
        file.setSharing(DriveApp.Access.PRIVATE, DriveApp.Permission.NONE);
      }
    }
    
    // Set specific user permissions
    if (permissions.users && Array.isArray(permissions.users)) {
      permissions.users.forEach(user => {
        const { email, role } = user;
        shareFile(fileId, email, role || 'reader', false);
      });
    }
    
    return true;
  } catch (error) {
    console.error('Error setting file permissions:', error);
    throw new Error(`Failed to set file permissions: ${error.message}`);
  }
}
```

## Advanced Operations

### Batch File Operations

```javascript
/**
 * Performs batch operations on multiple files
 * @param {Array<string>} fileIds - Array of file IDs to operate on
 * @param {string} operation - Operation type ('delete', 'move', 'copy')
 * @param {Object} options - Additional options based on operation
 * @return {Array<Object>} Results of operations
 */
function batchFileOperations(fileIds, operation, options = {}) {
  try {
    const results = [];
    
    fileIds.forEach((fileId, index) => {
      try {
        let result;
        
        switch (operation) {
          case 'delete':
            DriveApp.getFileById(fileId).setTrashed(true);
            result = { id: fileId, status: 'deleted' };
            break;
            
          case 'move':
            if (options.destinationFolderId) {
              const file = DriveApp.getFileById(fileId);
              const sourceFolders = file.getParents();
              
              while (sourceFolders.hasNext()) {
                const sourceFolder = sourceFolders.next();
                if (sourceFolder.getId() !== options.destinationFolderId) {
                  sourceFolder.removeFile(file);
                  const destFolder = DriveApp.getFolderById(options.destinationFolderId);
                  destFolder.addFile(file);
                }
              }
              result = { id: fileId, status: 'moved' };
            } else {
              throw new Error('Destination folder ID required for move operation');
            }
            break;
            
          case 'copy':
            const file = DriveApp.getFileById(fileId);
            const copiedFile = file.makeCopy();
            
            if (options.newName) {
              copiedFile.setName(options.newName + `_${index}`);
            }
            
            result = { id: fileId, status: 'copied', newId: copiedFile.getId() };
            break;
            
          default:
            throw new Error(`Unknown operation: ${operation}`);
        }
        
        results.push(result);
      } catch (error) {
        results.push({ 
          id: fileId, 
          status: 'failed', 
          error: error.message 
        });
      }
    });
    
    return results;
  } catch (error) {
    console.error('Error in batch operations:', error);
    throw new Error(`Failed to perform batch operations: ${error.message}`);
  }
}
```

### File Size and Metadata Functions

```javascript
/**
 * Gets detailed information about a file
 * @param {string} fileId - ID of the file
 * @return {Object} File metadata object
 */
function getFileMetadata(fileId) {
  try {
    const file = DriveApp.getFileById(fileId);
    
    return {
      id: file.getId(),
      name: file.getName(),
      size: file.getSize(),
      type: file.getMimeType(),
      createdDate: file.getDateCreated(),
      lastModifiedDate: file.getLastUpdated(),
      owner: file.getOwner().getEmail(),
      owners: file.getOwners().map(owner => owner.getEmail()),
      sharingAccess: getSharingAccess(fileId),
      url: file.getUrl()
    };
  } catch (error) {
    console.error('Error getting file metadata:', error);
    throw new Error(`Failed to get file metadata: ${error.message}`);
  }
}

/**
 * Gets current sharing access level for a file
 * @param {string} fileId - ID of the file
 * @return {Object} Sharing information
 */
function getSharingAccess(fileId) {
  try {
    const file = DriveApp.getFileById(fileId);
    
    // This is a simplified version - full implementation would need more complex logic
    return {
      access: 'private', // Simplified for example
      sharedWith: []
    };
  } catch (error) {
    console.error('Error getting sharing access:', error);
    throw new Error(`Failed to get sharing access: ${error.message}`);
  }
}
```

### Cleanup Function

```javascript
/**
 * Cleans up old files based on age criteria
 * @param {number} daysOld - Minimum age in days for file deletion (optional, default: 30)
 * @param {string} folderId - Specific folder to clean (optional)
 * @param {boolean} deleteFolder - Whether to delete entire folder (optional, default: false)
 * @return {Array<string>} IDs of deleted files
 */
function cleanupOldFiles(daysOld = 30, folderId = null, deleteFolder = false) {
  try {
    const cutoffDate = new Date();
    cutoffDate.setDate(cutoffDate.getDate() - daysOld);
    
    let files;
    
    if (folderId) {
      const folder = DriveApp.getFolderById(folderId);
      files = folder.getFiles();
    } else {
      files = DriveApp.getFiles();
    }
    
    const deletedFileIds = [];
    
    while (files.hasNext()) {
      const file = files.next();
      
      // Check if file is older than cutoff date
      if (file.getLastUpdated() < cutoffDate) {
        if (!deleteFolder || !folderId) {
          file.setTrashed(true);
          deletedFileIds.push(file.getId());
        }
      }
    }
    
    return deletedFileIds;
  } catch (error) {
    console.error('Error in cleanup operation:', error);
    throw new Error(`Failed to clean up files: ${error.message}`);
  }
}
```

## Usage Examples

```javascript
// Example usage of the functions above:

function exampleUsage() {
  try {
    // Create a sample file
    const myFile = createFile('sample.txt', 'Hello World!', 'text/plain');
    
    // Create nested folders
    const folderStructure = createNestedFolders('Projects/2023/Q1');
    
    // Move the file to the new folder
    moveFile(myFile.getId(), null, folderStructure.getId());
    
    // Search for files by name pattern
    const foundFiles = searchFilesByName('sample', folderStructure.getId());
    
    // Share a file
    shareFile(myFile.getId(), 'user@example.com', 'writer');
    
    // Get metadata of the file
    const metadata = getFileMetadata(myFile.getId());
    console.log(metadata);
    
  } catch (error) {
    console.error('Example failed:', error);
  }
}

// Sample function to create a sample folder structure for testing
function createSampleFolder() {
  try {
    const parentFolder = DriveApp.createFolder('Test Folder');
    
    // Create subfolders
    const documentsFolder = parentFolder.createFolder('Documents');
    const spreadsheetsFolder = parentFolder.createFolder('Spreadsheets');
    
    // Create sample files
    createFile('test_document.txt', 'This is a test document.', 'text/plain', documentsFolder.getId());
    createSpreadsheet('sample_data.xlsx', spreadsheetsFolder.getId());
    
    console.log('Sample folder structure created successfully!');
  } catch (error) {
    console.error('Error creating sample folder:', error);
  }
}

function listAllFiles() {
  try {
    const files = DriveApp.getFiles();
    const fileList = [];
    
    while (files.hasNext()) {
      const file = files.next();
      fileList.push({
        name: file.getName(),
        id: file.getId(),
        size: file.getSize(),
        type: file.getMimeType()
      });
    }
    
    console.log('All Files:', JSON.stringify(fileList, null, 2));
    
  } catch (error) {
    console.error('Error listing files:', error);
  }
}
```

## Optimization Tips

1. **Use batch operations** when working with multiple files
2. **Cache results** of frequently used searches
3. **Implement proper error handling** and logging
4. **Use `Utilities.sleep()`** to avoid quota limits during large operations
5. **Consider using Drive API v3 directly** for more complex scenarios
