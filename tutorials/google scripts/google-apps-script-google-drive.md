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

This comprehensive tutorial provides a professional, class-based system for Google Drive automation using Google Apps Script. The tutorial is structured progressively from beginner to advanced levels, covering everything from basic file operations to enterprise-level Drive management systems.

### What You'll Learn

- **Professional Architecture**: Object-oriented design with specialized classes
- **Complete Drive Management**: File operations, folder management, and advanced search
- **Automation Workflows**: Automated file organization and synchronization
- **Security & Permissions**: Advanced sharing and access control
- **Performance Optimization**: Efficient batch processing and caching
- **Real-world Projects**: Complete implementation examples

### Prerequisites

- Google Account with Drive access
- Basic JavaScript knowledge
- Google Apps Script environment
- Understanding of Google Drive concepts

---

## 2. Configuration and Setup

### DriveConfig Class - Centralized Configuration Management

```javascript
/**
 * DriveConfig - Centralized configuration management for Drive operations
 * Provides consistent settings and preferences across all Drive classes
 * @class
 */
class DriveConfig {
  constructor() {
    this.settings = {
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
        'image/png'
      ],
      
      // Search and pagination
      defaultPageSize: 100,
      maxSearchResults: 1000,
      
      // Performance settings
      batchSize: 25,
      operationDelay: 100,
      retryAttempts: 3,
      cacheExpiration: 300000, // 5 minutes
      
      // Security settings
      validateFileTypes: true,
      scanForMalware: true,
      requireSafeNames: true,
      
      // Sharing settings
      defaultSharingRole: 'reader',
      allowExternalSharing: false,
      requireComment: false,
      
      // Logging and debugging
      enableLogging: true,
      logLevel: 'info', // error, warn, info, debug
      debugMode: false
    };
    
    this.mimeTypes = {
      // Google Workspace types
      DOCUMENT: 'application/vnd.google-apps.document',
      SPREADSHEET: 'application/vnd.google-apps.spreadsheet',
      PRESENTATION: 'application/vnd.google-apps.presentation',
      FORM: 'application/vnd.google-apps.form',
      DRAWING: 'application/vnd.google-apps.drawing',
      FOLDER: 'application/vnd.google-apps.folder',
      
      // Common file types
      PDF: 'application/pdf',
      TEXT: 'text/plain',
      HTML: 'text/html',
      CSV: 'text/csv',
      JSON: 'application/json',
      
      // Image types
      JPEG: 'image/jpeg',
      PNG: 'image/png',
      GIF: 'image/gif',
      
      // Office types
      WORD: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      EXCEL: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      POWERPOINT: 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    };
    
    this.folderStructure = {
      templates: 'Templates',
      archive: 'Archive',
      shared: 'Shared',
      temp: 'Temp',
      backup: 'Backup',
      reports: 'Reports'
    };
    
    console.log('🔧 DriveConfig initialized with default settings');
  }

  /**
   * Get configuration setting
   * @param {string} key - Setting key (supports dot notation)
   * @return {*} Setting value
   */
  get(key) {
    return key.split('.').reduce((obj, k) => obj && obj[k], this.settings);
  }

  /**
   * Set configuration setting
   * @param {string} key - Setting key (supports dot notation)
   * @param {*} value - Setting value
   */
  set(key, value) {
    const keys = key.split('.');
    const lastKey = keys.pop();
    const target = keys.reduce((obj, k) => obj[k] = obj[k] || {}, this.settings);
    target[lastKey] = value;
    
    console.log(`⚙️ Config updated: ${key} = ${value}`);
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
    this.__constructor__();
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

    // Check file size
    const fileSize = file.getSize();
    if (fileSize > this.get('maxFileSize')) {
      result.errors.push(`File size exceeds limit: ${fileSize} > ${this.get('maxFileSize')}`);
      result.isValid = false;
    }

    // Check MIME type
  const mimeType = file.getMimeType();
    if (this.get('validateFileTypes') && !this.get('allowedMimeTypes').includes(mimeType)) {
      result.errors.push(`File type not allowed: ${mimeType}`);
      result.isValid = false;
    }

    // Check filename safety
    if (this.get('requireSafeNames')) {
      const unsafeChars = /[<>:"|?*\x00-\x1f]/;
      if (unsafeChars.test(file.getName())) {
        result.errors.push('Filename contains unsafe characters');
        result.isValid = false;
      }
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
      'jpg': this.mimeTypes.JPEG,
      'jpeg': this.mimeTypes.JPEG,
      'png': this.mimeTypes.PNG,
      'gif': this.mimeTypes.GIF,
      'docx': this.mimeTypes.WORD,
      'xlsx': this.mimeTypes.EXCEL,
      'pptx': this.mimeTypes.POWERPOINT
    };
    
    return extensionMap[extension.toLowerCase()] || 'application/octet-stream';
  }

  /**
   * Export configuration as JSON
   * @return {string} Configuration JSON
   */
  export() {
    return JSON.stringify({
      settings: this.settings,
      timestamp: new Date(),
      version: '1.0'
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
 */
function enableDriveServices() {
  try {
    console.log('🔌 Enabling Google Drive services...');
    
    // Enable Advanced Google Services if needed
    // Note: This requires manual enabling in the Apps Script editor
    
    // Test Drive API access
    const testFile = DriveApp.getRootFolder();
    console.log(`✅ Drive API access confirmed: ${testFile.getName()}`);
    
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
        // Check if folder exists
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

#### Quick usage — DriveConfig

```javascript
function driveConfigExamples() {
  const cfg = new DriveConfig();
  // Read/update settings
  const pageSize = cfg.get('defaultPageSize');
  cfg.set('batchSize', 50);
  cfg.update({ operationDelay: 200, debugMode: true });

  // Export/import config
  const exported = cfg.export();
  cfg.import(exported);

  // Initialize Drive services and required folders
  enableDriveServices();
  console.log('Page size:', pageSize);
}
```

---

## 3. Basic Drive Operations

### DriveManager Class - Core Drive Operations

```javascript
/**
 * DriveManager - Core Google Drive operations and management
 * Provides fundamental Drive functionality with error handling and optimization
 * @class
 */
class DriveManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.operationLog = [];
    this.cache = new Map();
  }

  /**
   * Create a new file in Google Drive
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
      starred = false
    } = options;

    try {
      console.log(`📝 Creating file: ${fileName}`);
      
      this.validateFileName(fileName);
      
      let fileBlob;
      if (typeof content === 'string') {
        fileBlob = Utilities.newBlob(content, mimeType, fileName);
      } else {
        fileBlob = content;
      }
      
      // Create file in specified folder or root
      let file;
      if (parentFolderId) {
        const parentFolder = this.getFolderById(parentFolderId);
        file = parentFolder.createFile(fileBlob);
      } else {
        file = DriveApp.createFile(fileBlob);
      }
      
      // Set additional properties
      if (description) {
        file.setDescription(description);
      }
      
      if (starred) {
        file.setStarred(true);
      }
      
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
        mimeType: mimeType,
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
   * Create a new folder in Google Drive
   * @param {string} folderName - Name of the folder
   * @param {Object} options - Creation options
   * @return {GoogleAppsScript.Drive.Folder} Created folder
   */
  createFolder(folderName, options = {}) {
    // Support legacy call: createFolder(name, parentFolderId)
    if (typeof options === 'string') {
      options = { parentFolderId: options };
    }
    const {
      parentFolderId = null
    } = options;

    try {
      console.log(`📁 Creating folder: ${folderName}`);
      
      this.validateFileName(folderName);
      
      // Check if folder already exists in parent
      let parentFolder = parentFolderId ? this.getFolderById(parentFolderId) : DriveApp.getRootFolder();
      const existingFolders = parentFolder.getFoldersByName(folderName);
      
      if (existingFolders.hasNext()) {
        const existingFolder = existingFolders.next();
        console.log(`📁 Folder already exists: ${folderName} (${existingFolder.getId()})`);
        return existingFolder;
      }
      
      // Create new folder
      const folder = parentFolder.createFolder(folderName);
      
  // Note: DriveApp Folder does not support description/starred APIs
      
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
   * Get file by ID with caching
   * @param {string} fileId - File ID
   * @param {boolean} useCache - Whether to use cache
   * @return {GoogleAppsScript.Drive.File} File object
   */
  getFileById(fileId, useCache = true) {
    const cacheKey = `file_${fileId}`;
    
    if (useCache && this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        console.log(`💾 Cache hit for file: ${fileId}`);
        return cached.data;
      } else {
        this.cache.delete(cacheKey);
      }
    }

    try {
      console.log(`📄 Getting file: ${fileId}`);
      const file = DriveApp.getFileById(fileId);
      
      if (useCache) {
        this.cache.set(cacheKey, {
          data: file,
          timestamp: Date.now()
        });
      }
      
      return file;
      
    } catch (error) {
      console.error(`❌ Error getting file: ${error.message}`);
      throw new Error(`File not found or inaccessible: ${fileId}`);
    }
  }

  /**
   * Get folder by ID with caching
   * @param {string} folderId - Folder ID
   * @param {boolean} useCache - Whether to use cache
   * @return {GoogleAppsScript.Drive.Folder} Folder object
   */
  getFolderById(folderId, useCache = true) {
    const cacheKey = `folder_${folderId}`;
    
    if (useCache && this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
        console.log(`💾 Cache hit for folder: ${folderId}`);
        return cached.data;
      } else {
        this.cache.delete(cacheKey);
      }
    }

    try {
      console.log(`📁 Getting folder: ${folderId}`);
      const folder = DriveApp.getFolderById(folderId);
      
      if (useCache) {
        this.cache.set(cacheKey, {
          data: folder,
          timestamp: Date.now()
        });
      }
      
      return folder;
      
    } catch (error) {
      console.error(`❌ Error getting folder: ${error.message}`);
      throw new Error(`Folder not found or inaccessible: ${folderId}`);
    }
  }

  /**
   * Copy file to another location
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
      console.log(`📋 Copying file: ${fileId}`);
      
      const sourceFile = this.getFileById(fileId);
      const fileName = newName || `Copy of ${sourceFile.getName()}`;
      
      // Perform copy
      let copiedFile;
      if (parentFolderId) {
        const parentFolder = this.getFolderById(parentFolderId);
        copiedFile = sourceFile.makeCopy(fileName, parentFolder);
      } else {
        copiedFile = sourceFile.makeCopy(fileName);
      }
      
      // Set description if provided
      if (description) {
        copiedFile.setDescription(description);
      }
      
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
   * Move file to another folder
   * @param {string} fileId - File ID to move
   * @param {string} targetFolderId - Target folder ID
   * @return {GoogleAppsScript.Drive.File} Moved file
   */
  moveFile(fileId, targetFolderId) {
    try {
      console.log(`🚚 Moving file: ${fileId} to ${targetFolderId}`);
      
      const file = this.getFileById(fileId);
      const targetFolder = this.getFolderById(targetFolderId);
      const currentParents = file.getParents();
      
      // Add to new parent
      targetFolder.addFile(file);
      
      // Remove from current parents
      while (currentParents.hasNext()) {
        const parent = currentParents.next();
        parent.removeFile(file);
      }
      
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
   * Delete file (move to trash)
   * @param {string} fileId - File ID to delete
   * @param {boolean} permanent - Whether to permanently delete
   * @return {boolean} Success status
   */
  deleteFile(fileId, permanent = false) {
    try {
      console.log(`🗑️ Deleting file: ${fileId} (permanent: ${permanent})`);
      
      const file = this.getFileById(fileId);
      const fileName = file.getName();
      
      if (permanent) {
        // Note: Permanent deletion requires Drive API v3
        file.setTrashed(true);
        console.log('⚠️ File moved to trash (permanent deletion requires Drive API v3)');
      } else {
        file.setTrashed(true);
      }
      
      this.logOperation('file_deleted', {
        fileId: fileId,
        fileName: fileName,
        permanent: permanent
      });
      
      console.log(`✅ File deleted: ${fileName}`);
      return true;
      
    } catch (error) {
      console.error(`❌ Error deleting file: ${error.message}`);
      return false;
    }
  }

  /**
   * Restore file from trash
   * @param {string} fileId - File ID to restore
   * @return {GoogleAppsScript.Drive.File} Restored file
   */
  restoreFile(fileId) {
    try {
      console.log(`♻️ Restoring file: ${fileId}`);
      
      const file = this.getFileById(fileId);
      file.setTrashed(false);
      
      this.logOperation('file_restored', {
        fileId: fileId,
        fileName: file.getName()
      });
      
      console.log(`✅ File restored: ${file.getName()}`);
      return file;
      
    } catch (error) {
      console.error(`❌ Error restoring file: ${error.message}`);
      throw error;
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
    
    const reservedNames = ['CON', 'PRN', 'AUX', 'NUL', 'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9', 'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9'];
    if (reservedNames.includes(fileName.toUpperCase())) {
      throw new Error('Filename is a reserved system name');
    }
  }

  /**
   * Log operation for tracking and debugging
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
      
      // Keep log size manageable
      if (this.operationLog.length > 1000) {
        this.operationLog = this.operationLog.slice(-500);
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
      cacheSize: this.cache.size
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
    this.cache.clear();
    this.operationLog = [];
    console.log('🧹 Cache and logs cleared');
  }

  /**
   * List files in a folder
   * @param {string} folderId
   * @param {Object} options
   * @return {GoogleAppsScript.Drive.File[]} Array of File objects
   */
  getFilesInFolder(folderId, options = {}) {
    const files = [];
    const folder = this.getFolderById(folderId);
    const it = folder.getFiles();
    while (it.hasNext()) {
      const f = it.next();
      // Optional simple filters
      if (options.applyFilters && options.filters) {
        const mime = f.getMimeType();
        if (options.filters.mimeTypes && !options.filters.mimeTypes.includes(mime)) {
          continue;
        }
      }
      files.push(f);
    }
    return files;
  }
}

// Create global drive manager instance
const DRIVE_MANAGER = new DriveManager();

/**
 * FolderManager - minimal helpers used by examples/analytics
 */
class FolderManager {
  /**
   * Find a folder by path starting from root (e.g. "Dept/Type/Level")
   * @param {string} path
   * @return {GoogleAppsScript.Drive.Folder|null}
   */
  findFolder(path) {
    const parts = path.split('/').filter(Boolean);
    let current = DriveApp.getRootFolder();
    for (const name of parts) {
      const it = current.getFoldersByName(name);
      if (!it.hasNext()) return null;
      current = it.next();
    }
    return current;
  }

  /**
   * Basic stats placeholder
   */
  getStats() {
    return { scanned: 0, found: 0 };
  }
}

const FOLDER_MANAGER = new FolderManager();
```

#### Quick usage — DriveManager

```javascript
function driveManagerExamples() {
  // Create a folder
  const folder = DRIVE_MANAGER.createFolder('Project Docs');

  // Create a text file in the new folder
  const file = DRIVE_MANAGER.createFile(
    'readme.txt',
    'Hello Drive!\nThis file was created by DriveManager.',
    { parentFolderId: folder.getId(), mimeType: 'text/plain' }
  );

  // Move, delete (to trash), and restore
  const target = DRIVE_MANAGER.createFolder('Archive');
  DRIVE_MANAGER.moveFile(file.getId(), target.getId());
  DRIVE_MANAGER.deleteFile(file.getId());
  DRIVE_MANAGER.restoreFile(file.getId());

  // Stats
  const stats = DRIVE_MANAGER.getOperationStats();
  console.log(stats);
}
```

---

## 4. File Management

### FileManager Class - Advanced File Operations

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
        return this.driveManager.createFile(params.name, params.content, params.options);
      case 'copy':
        return this.driveManager.copyFile(params.fileId, params.options);
      case 'move':
        return this.driveManager.moveFile(params.fileId, params.targetFolderId);
      case 'delete':
        return this.driveManager.deleteFile(params.fileId, params.permanent);
      case 'convert':
        const file = this.driveManager.getFileById(params.fileId);
        return this.convertToGoogleFormat(file);
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Validate and correct MIME type based on file extension
   * @param {string} contentType - Original content type
   * @param {string} fileName - File name
   * @return {string} Validated MIME type
   * @private
   */
  validateAndCorrectMimeType(contentType, fileName) {
    const extension = fileName.split('.').pop()?.toLowerCase();
    if (!extension) return contentType;
    
    const correctMimeType = this.config.getMimeTypeByExtension(extension);
    
    // Return more specific MIME type if available
    if (correctMimeType !== 'application/octet-stream') {
      return correctMimeType;
    }
    
    return contentType;
  }

  /**
   * Analyze Google Document content
   * @param {string} documentId - Document ID
   * @return {Object} Document analysis
   * @private
   */
  analyzeGoogleDocument(documentId) {
    try {
      const doc = DocumentApp.openById(documentId);
      const body = doc.getBody();
      const text = body.getText();
      
      return {
        wordCount: text.split(/\s+/).filter(word => word.length > 0).length,
        characterCount: text.length,
        paragraphCount: body.getParagraphs().length,
        hasImages: body.getImages().length > 0,
        imageCount: body.getImages().length,
        hasLinks: body.getText().includes('http'),
        textPreview: text.substring(0, 200) + (text.length > 200 ? '...' : '')
      };
    } catch (error) {
      return { error: error.message };
    }
  }

  /**
   * Analyze Google Spreadsheet content
   * @param {string} spreadsheetId - Spreadsheet ID
   * @return {Object} Spreadsheet analysis
   * @private
   */
  analyzeGoogleSpreadsheet(spreadsheetId) {
    try {
      const ss = SpreadsheetApp.openById(spreadsheetId);
      const sheets = ss.getSheets();
      
      const analysis = {
        sheetCount: sheets.length,
        sheets: []
      };
      
      sheets.forEach(sheet => {
        const range = sheet.getDataRange();
        analysis.sheets.push({
          name: sheet.getName(),
          rows: range.getNumRows(),
          columns: range.getNumColumns(),
          hasData: range.getNumRows() > 1,
          isEmpty: range.getNumRows() === 0
        });
      });
      
      return analysis;
    } catch (error) {
      return { error: error.message };
    }
  }

  /**
   * Analyze Google Presentation content
   * @param {string} presentationId - Presentation ID
   * @return {Object} Presentation analysis
   * @private
   */
  analyzeGooglePresentation(presentationId) {
    try {
      const presentation = SlidesApp.openById(presentationId);
      const slides = presentation.getSlides();
      
      return {
        slideCount: slides.length,
        hasImages: slides.some(slide => slide.getImages().length > 0),
        hasShapes: slides.some(slide => slide.getShapes().length > 0),
        isEmpty: slides.length === 0
      };
    } catch (error) {
      return { error: error.message };
    }
  }

  /**
   * Analyze text file content
   * @param {GoogleAppsScript.Drive.File} file - Text file
   * @return {Object} Text analysis
   * @private
   */
  analyzeTextFile(file) {
    try {
      const content = file.getBlob().getDataAsString();
      
      return {
        characterCount: content.length,
        lineCount: content.split('\n').length,
        wordCount: content.split(/\s+/).filter(word => word.length > 0).length,
        encoding: 'UTF-8', // Assumption
        textPreview: content.substring(0, 200) + (content.length > 200 ? '...' : '')
      };
    } catch (error) {
      return { error: error.message };
    }
  }

  /**
   * Analyze image file
   * @param {GoogleAppsScript.Drive.File} file - Image file
   * @return {Object} Image analysis
   * @private
   */
  analyzeImageFile(file) {
    try {
      const blob = file.getBlob();
      
      return {
        format: blob.getContentType(),
        sizeBytes: blob.getBytes().length,
        name: blob.getName()
      };
    } catch (error) {
      return { error: error.message };
    }
  }

  /**
   * Generate content insights
   * @param {Object} analysis - Content analysis
   * @return {Array<string>} Insights array
   * @private
   */
  generateContentInsights(analysis) {
    const insights = [];
    
    // Size insights
    if (analysis.size > 10 * 1024 * 1024) {
      insights.push('Large file - consider compression or splitting');
    }
    
    // Content insights
    if (analysis.content.wordCount > 10000) {
      insights.push('Long document - consider creating table of contents');
    }
    
    if (analysis.content.hasImages) {
      insights.push('Contains images - good for visual content');
    }
    
    if (analysis.content.isEmpty) {
      insights.push('Empty or minimal content - may need review');
    }
    
    return insights;
  }

  /**
   * Queue file for content processing
   * @param {string} fileId - File ID to queue
   * @private
   */
  queueContentProcessing(fileId) {
    this.processingQueue.push({
      fileId: fileId,
      timestamp: new Date(),
      status: 'queued'
    });
    
    console.log(`📋 File queued for processing: ${fileId}`);
  }

  /**
   * Process queued files
   * @return {Array<Object>} Processing results
   */
  processQueue() {
    const results = [];
    
    while (this.processingQueue.length > 0) {
      const item = this.processingQueue.shift();
      
      try {
        console.log(`⚙️ Processing queued file: ${item.fileId}`);
        
        const analysis = this.analyzeFileContent(item.fileId);
        item.status = 'processed';
        item.result = analysis;
        results.push(item);
        
      } catch (error) {
        item.status = 'failed';
        item.error = error.message;
        results.push(item);
        console.error(`❌ Failed to process queued file: ${error.message}`);
      }
    }
    
    return results;
  }

  /**
   * Get file processing statistics
   * @return {Object} Processing statistics
   */
  getProcessingStats() {
    return {
      queueSize: this.processingQueue.length,
      pendingFiles: this.processingQueue.filter(item => item.status === 'queued').length,
      failedFiles: this.processingQueue.filter(item => item.status === 'failed').length
    };
  }
}

// Create global file manager instance
const FILE_MANAGER = new FileManager();
```

#### Quick usage — FileManager

```javascript
function fileManagerExamples() {
  // Create a Google Doc with starter content in a new folder
  const parent = DRIVE_MANAGER.createFolder('Contracts');
  const doc = FILE_MANAGER.createWorkspaceDocument('document', 'Service Agreement', {
    content: 'ACME Corp — Service Agreement',
    parentFolderId: parent.getId()
  });

  // Upload a file from URL and (optionally) convert it
  const uploaded = FILE_MANAGER.uploadFromUrl(
    'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
    'dummy.pdf',
    { parentFolderId: parent.getId(), convertToGoogleFormat: false }
  );

  // Copy the uploaded file into a subfolder
  const sub = DRIVE_MANAGER.createFolder('Copies', { parentFolderId: parent.getId() });
  const copy = DRIVE_MANAGER.copyFile(uploaded.getId(), { newName: 'dummy (copy).pdf', parentFolderId: sub.getId() });

  console.log({ docId: doc.id, uploadedId: uploaded.getId(), copyId: copy.getId() });
}
```

### Copy File Function

```javascript
/**
 * Copies a file in Google Drive with optional parameters
 * @param {string} fileId - ID of the file to copy
 * @param {string} newName - New name for copied file (optional)
 * @param {string} destinationFolderId - Destination folder ID (optional)
 * @return {GoogleAppsScript.Drive.File} Copied file object
 */
function copyFile(fileId, newName = null, destinationFolderId = null) {
  try {
    const sourceFile = DriveApp.getFileById(fileId);
    const newFile = sourceFile.makeCopy(newName || sourceFile.getName());

    // Move copy to destination folder if specified, and remove from other parents (like root)
    if (destinationFolderId) {
      const destinationFolder = DriveApp.getFolderById(destinationFolderId);
      destinationFolder.addFile(newFile);
      const parents = newFile.getParents();
      while (parents.hasNext()) {
        const p = parents.next();
        if (p.getId() !== destinationFolder.getId()) {
          p.removeFile(newFile);
        }
      }
    }

    return newFile;
  } catch (error) {
    console.error('Error copying file:', error);
    throw new Error(`Failed to copy file: ${error.message}`);
  }
}
```

#### Quick usage — copyFile (standalone)

```javascript
function copyFileExample() {
  // Make a copy of a file and move it to a destination
  const sourceId = 'REPLACE_WITH_FILE_ID';
  const destFolderId = 'REPLACE_WITH_FOLDER_ID';
  const copied = copyFile(sourceId, 'My Copied File', destFolderId);
  console.log('Copied file ID:', copied.getId());
}
```

---

## 6. Advanced Search and Query

```javascript

### DriveSearcher Class - Powerful Search and Query Engine

```javascript
/**
 * DriveSearcher - Advanced search and query capabilities for Google Drive
 * Provides powerful search functionality with caching and optimization
 * @class
 */
class DriveSearcher {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.searchCache = new Map();
    this.queryBuilder = new DriveQueryBuilder();
    this.searchHistory = [];
  }

  /**
   * Advanced search with multiple criteria
   * @param {Object} criteria - Search criteria
   * @param {Object} options - Search options
   * @return {Array<Object>} Search results
   */
  advancedSearch(criteria, options = {}) {
    const {
      maxResults = this.config.get('maxSearchResults'),
      useCache = true,
      includeContent = false,
      sortBy = 'relevance',
      groupBy = null
    } = options;

    try {
      console.log('🔍 Performing advanced search with criteria:', criteria);
      
      // Build search query
      const query = this.queryBuilder.build(criteria);
      console.log(`📝 Generated query: ${query}`);
      
      // Check cache
      const cacheKey = this.generateCacheKey(query, options);
      if (useCache && this.searchCache.has(cacheKey)) {
        const cached = this.searchCache.get(cacheKey);
        if (Date.now() - cached.timestamp < this.config.get('cacheExpiration')) {
          console.log('💾 Returning cached search results');
          return cached.results;
        } else {
          this.searchCache.delete(cacheKey);
        }
      }
      
      // Execute search
      const files = DriveApp.searchFiles(query);
      const results = [];
      let count = 0;
      
      while (files.hasNext() && count < maxResults) {
        const file = files.next();
        const result = this.buildFileResult(file, includeContent);
        results.push(result);
        count++;
      }
      
      // Sort results
      const sortedResults = this.sortResults(results, sortBy);
      
      // Group results if requested
      const finalResults = groupBy ? this.groupResults(sortedResults, groupBy) : sortedResults;
      
      // Cache results
      if (useCache) {
        this.searchCache.set(cacheKey, {
          results: finalResults,
          timestamp: Date.now(),
          query: query
        });
      }
      
      // Log search
      this.logSearch(query, finalResults.length, criteria);
      
      console.log(`✅ Search completed: ${finalResults.length} results found`);
      return finalResults;
      
    } catch (error) {
      console.error(`❌ Search failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Find files by name pattern
   * @param {string} pattern - Name pattern (supports wildcards)
   * @param {Object} options - Search options
   * @return {Array<Object>} Matching files
   */
  findByNamePattern(pattern, options = {}) {
    const {
      caseSensitive = false,
      exactMatch = false,
      includeExtension = true
    } = options;

    const criteria = {
      name: {
        pattern: pattern,
        caseSensitive: caseSensitive,
        exactMatch: exactMatch,
        includeExtension: includeExtension
      }
    };

    return this.advancedSearch(criteria, options);
  }

  /**
   * Find files by content
   * @param {string} searchText - Text to search for
   * @param {Object} options - Search options
   * @return {Array<Object>} Files containing the text
   */
  findByContent(searchText, options = {}) {
    const {
      fileTypes = ['document', 'spreadsheet', 'presentation'],
      exactPhrase = false
    } = options;

    const criteria = {
      content: {
        text: searchText,
        exactPhrase: exactPhrase
      },
      mimeType: fileTypes.map(type => this.config.mimeTypes[type.toUpperCase()]).filter(Boolean)
    };

    return this.advancedSearch(criteria, { ...options, includeContent: true });
  }

  /**
   * Find files by date range
   * @param {Object} dateRange - Date range criteria
   * @param {Object} options - Search options
   * @return {Array<Object>} Files in date range
   */
  findByDateRange(dateRange, options = {}) {
    const {
      dateField = 'modified' // created, modified, accessed
    } = options;

    const criteria = {
      [dateField]: dateRange
    };

    return this.advancedSearch(criteria, options);
  }

  /**
   * Find files by size range
   * @param {Object} sizeRange - Size range in bytes
   * @param {Object} options - Search options
   * @return {Array<Object>} Files in size range
   */
  findBySizeRange(sizeRange, options = {}) {
    const criteria = {
      size: sizeRange
    };

    return this.advancedSearch(criteria, options);
  }

  /**
   * Find duplicate files
   * @param {Object} criteria - Duplicate detection criteria
   * @param {Object} options - Search options
   * @return {Array<Array<Object>>} Groups of duplicate files
   */
  findDuplicates(criteria = {}, options = {}) {
    const {
      compareBy = ['name', 'size'], // name, size, checksum, content
      threshold = 0.8,
      includeVersions = false
    } = criteria;

    try {
      console.log('🔍 Finding duplicate files...');
      
      // Get all files first
      const allFiles = this.advancedSearch({}, { maxResults: 10000 });
      
      // Group potential duplicates
      const groups = new Map();
      
      for (const file of allFiles) {
        const key = this.generateDuplicateKey(file, compareBy);
        
        if (!groups.has(key)) {
          groups.set(key, []);
        }
        groups.get(key).push(file);
      }
      
      // Filter groups with more than one file
      const duplicates = [];
      for (const [key, files] of groups) {
        if (files.length > 1) {
          // Additional validation for similar files
          const validatedGroup = this.validateDuplicateGroup(files, compareBy, threshold);
          if (validatedGroup.length > 1) {
            duplicates.push(validatedGroup);
          }
        }
      }
      
      console.log(`✅ Found ${duplicates.length} duplicate groups`);
      return duplicates;
      
    } catch (error) {
      console.error(`❌ Error finding duplicates: ${error.message}`);
      throw error;
    }
  }

  /**
   * Find large files consuming storage
   * @param {Object} options - Search options
   * @return {Array<Object>} Large files sorted by size
   */
  findLargeFiles(options = {}) {
    const {
      minSize = 50 * 1024 * 1024, // 50MB default
      excludeGoogleFiles = true,
      includeDetails = true
    } = options;

    const criteria = {
      size: { min: minSize }
    };

    if (excludeGoogleFiles) {
      criteria.excludeMimeTypes = [
        this.config.mimeTypes.DOCUMENT,
        this.config.mimeTypes.SPREADSHEET,
        this.config.mimeTypes.PRESENTATION,
        this.config.mimeTypes.FORM,
        this.config.mimeTypes.DRAWING
      ];
    }

    const results = this.advancedSearch(criteria, { 
      sortBy: 'size', 
      includeContent: includeDetails,
      maxResults: 1000
    });

    // Add storage impact information
    return results.map(file => ({
      ...file,
      storageImpact: this.calculateStorageImpact(file.size),
      compressionPotential: this.estimateCompressionPotential(file)
    }));
  }

  /**
   * Find recently modified files
   * @param {Object} options - Search options
   * @return {Array<Object>} Recently modified files
   */
  findRecentlyModified(options = {}) {
    const {
      days = 7,
      includeCollaborators = false,
      fileTypes = null
    } = options;

    const cutoffDate = new Date();
    cutoffDate.setDate(cutoffDate.getDate() - days);

    const criteria = {
      modified: { after: cutoffDate }
    };

    if (fileTypes) {
      criteria.mimeType = fileTypes.map(type => this.config.mimeTypes[type.toUpperCase()]).filter(Boolean);
    }

    const results = this.advancedSearch(criteria, { 
      sortBy: 'modified', 
      includeContent: includeCollaborators 
    });

    return results.map(file => ({
      ...file,
      daysAgo: Math.floor((Date.now() - new Date(file.modified).getTime()) / (1000 * 60 * 60 * 24))
    }));
  }

  /**
   * Find orphaned files (files not in any folder)
   * @param {Object} options - Search options
   * @return {Array<Object>} Orphaned files
   */
  findOrphanedFiles(options = {}) {
    const {
      excludeShared = true,
      excludeStarred = false
    } = options;

    try {
      console.log('🔍 Finding orphaned files...');
      
  const allFiles = DriveApp.searchFiles("trashed = false and not 'root' in parents");
      const orphanedFiles = [];
      
      while (allFiles.hasNext()) {
        const file = allFiles.next();
  const parents = file.getParents();
  // Orphaned: no parents
  if (!parents.hasNext()) {
          const fileData = this.buildFileResult(file, false);
          
          // Apply filters
          if (excludeShared && fileData.shared) continue;
          if (excludeStarred && fileData.starred) continue;
          
          orphanedFiles.push({
            ...fileData,
            orphanReason: 'No parent folders'
          });
        }
      }
      
      console.log(`✅ Found ${orphanedFiles.length} orphaned files`);
      return orphanedFiles;
      
    } catch (error) {
      console.error(`❌ Error finding orphaned files: ${error.message}`);
      throw error;
    }
  }

  /**
   * Build file result object
   * @param {GoogleAppsScript.Drive.File} file - Drive file
   * @param {boolean} includeContent - Include content analysis
   * @return {Object} File result object
   * @private
   */
  buildFileResult(file, includeContent = false) {
    const result = {
      id: file.getId(),
      name: file.getName(),
  mimeType: file.getMimeType(),
      size: file.getSize(),
  created: file.getDateCreated(),
  modified: file.getLastUpdated(),
  url: file.getUrl(),
      starred: file.isStarred(),
      trashed: file.isTrashed(),
      shared: file.getSharingAccess() !== DriveApp.Access.PRIVATE,
      description: file.getDescription(),
      parents: []
    };

    // Get parent folders
    const parents = file.getParents();
    while (parents.hasNext()) {
      const parent = parents.next();
      result.parents.push({
        id: parent.getId(),
        name: parent.getName()
      });
    }

    // Include content analysis if requested
    if (includeContent && FILE_MANAGER) {
      try {
        const analysis = FILE_MANAGER.analyzeFileContent(file.getId());
        result.contentAnalysis = analysis.content;
        result.insights = analysis.insights;
      } catch (error) {
        result.contentAnalysisError = error.message;
      }
    }

    return result;
  }

  /**
   * Sort search results
   * @param {Array<Object>} results - Results to sort
   * @param {string} sortBy - Sort criteria
   * @return {Array<Object>} Sorted results
   * @private
   */
  sortResults(results, sortBy) {
    switch (sortBy) {
      case 'name':
        return results.sort((a, b) => a.name.localeCompare(b.name));
      case 'created':
        return results.sort((a, b) => new Date(b.created) - new Date(a.created));
      case 'modified':
        return results.sort((a, b) => new Date(b.modified) - new Date(a.modified));
      case 'size':
        return results.sort((a, b) => b.size - a.size);
      case 'type':
        return results.sort((a, b) => a.mimeType.localeCompare(b.mimeType));
      default: // relevance
        return results;
    }
  }

  /**
   * Group search results
   * @param {Array<Object>} results - Results to group
   * @param {string} groupBy - Group criteria
   * @return {Object} Grouped results
   * @private
   */
  groupResults(results, groupBy) {
    const groups = {};
    
    for (const result of results) {
      let key = 'Other';
      
      switch (groupBy) {
        case 'type':
          key = this.getMimeTypeCategory(result.mimeType);
          break;
        case 'size':
          if (result.size < 1024 * 1024) key = 'Small (< 1MB)';
          else if (result.size < 10 * 1024 * 1024) key = 'Medium (1-10MB)';
          else key = 'Large (> 10MB)';
          break;
        case 'date':
          const date = new Date(result.modified);
          key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
          break;
        case 'folder':
          key = result.parents.length > 0 ? result.parents[0].name : 'Root';
          break;
      }
      
      if (!groups[key]) {
        groups[key] = [];
      }
      groups[key].push(result);
    }
    
    return groups;
  }

  /**
   * Generate cache key for search
   * @param {string} query - Search query
   * @param {Object} options - Search options
   * @return {string} Cache key
   * @private
   */
  generateCacheKey(query, options) {
    const keyData = {
      query: query,
      maxResults: options.maxResults,
      includeContent: options.includeContent,
      sortBy: options.sortBy,
      groupBy: options.groupBy
    };
    
    return `search_${JSON.stringify(keyData)}`;
  }

  /**
   * Generate duplicate detection key
   * @param {Object} file - File object
   * @param {Array<string>} compareBy - Comparison criteria
   * @return {string} Duplicate key
   * @private
   */
  generateDuplicateKey(file, compareBy) {
    const keyParts = [];
    
    for (const criteria of compareBy) {
      switch (criteria) {
        case 'name':
          keyParts.push(file.name.toLowerCase());
          break;
        case 'size':
          keyParts.push(file.size.toString());
          break;
        case 'type':
          keyParts.push(file.mimeType);
          break;
      }
    }
    
    return keyParts.join('|');
  }

  /**
   * Validate duplicate group
   * @param {Array<Object>} files - Potential duplicates
   * @param {Array<string>} compareBy - Comparison criteria
   * @param {number} threshold - Similarity threshold
   * @return {Array<Object>} Validated duplicates
   * @private
   */
  validateDuplicateGroup(files, compareBy, threshold) {
    // For now, return the group as-is
    // In a more sophisticated implementation, you could add checksum comparison,
    // content similarity analysis, etc.
    return files;
  }

  /**
   * Calculate storage impact
   * @param {number} fileSize - File size in bytes
   * @return {Object} Storage impact information
   * @private
   */
  calculateStorageImpact(fileSize) {
    const mb = fileSize / (1024 * 1024);
    const gb = fileSize / (1024 * 1024 * 1024);
    
    return {
      bytes: fileSize,
      mb: Math.round(mb * 100) / 100,
      gb: Math.round(gb * 1000) / 1000,
      percentage: Math.round((fileSize / (15 * 1024 * 1024 * 1024)) * 10000) / 100 // Assuming 15GB quota
    };
  }

  /**
   * Estimate compression potential
   * @param {Object} file - File object
   * @return {Object} Compression estimate
   * @private
   */
  estimateCompressionPotential(file) {
    // Simple heuristics for compression potential
    const compressibleTypes = {
      'text/plain': 0.7,
      'application/json': 0.8,
      'text/html': 0.6,
      'application/pdf': 0.1,
      'image/png': 0.05,
      'image/jpeg': 0.02
    };
    
    const compressionRatio = compressibleTypes[file.mimeType] || 0.3;
    const potentialSavings = file.size * compressionRatio;
    
    return {
      compressionRatio: compressionRatio,
      potentialSavings: Math.round(potentialSavings),
      worthCompressing: potentialSavings > 1024 * 1024 // > 1MB savings
    };
  }

  /**
   * Get MIME type category
   * @param {string} mimeType - MIME type
   * @return {string} Category
   * @private
   */
  getMimeTypeCategory(mimeType) {
    if (mimeType.startsWith('image/')) return 'Images';
    if (mimeType.startsWith('video/')) return 'Videos';
    if (mimeType.startsWith('audio/')) return 'Audio';
    if (mimeType.includes('pdf')) return 'PDFs';
    if (mimeType.includes('document')) return 'Documents';
    if (mimeType.includes('spreadsheet')) return 'Spreadsheets';
    if (mimeType.includes('presentation')) return 'Presentations';
    if (mimeType.startsWith('text/')) return 'Text Files';
    return 'Other';
  }

  /**
   * Log search operation
   * @param {string} query - Search query
   * @param {number} resultCount - Number of results
   * @param {Object} criteria - Search criteria
   * @private
   */
  logSearch(query, resultCount, criteria) {
    const logEntry = {
      timestamp: new Date(),
      query: query,
      criteria: criteria,
      resultCount: resultCount,
      user: Session.getActiveUser().getEmail()
    };
    
    this.searchHistory.push(logEntry);
    
    // Keep history manageable
    if (this.searchHistory.length > 1000) {
      this.searchHistory = this.searchHistory.slice(-500);
    }
  }

  /**
   * Get search statistics
   * @return {Object} Search statistics
   */
  getSearchStats() {
    const stats = {
      totalSearches: this.searchHistory.length,
      cacheSize: this.searchCache.size,
      recentSearches: this.searchHistory.slice(-10),
      popularQueries: {}
    };
    
    // Count popular queries
    this.searchHistory.forEach(entry => {
      stats.popularQueries[entry.query] = (stats.popularQueries[entry.query] || 0) + 1;
    });
    
    return stats;
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
 * DriveQueryBuilder - Helper class for building Drive search queries
 * @class
 */
class DriveQueryBuilder {
  /**
   * Build Drive API query from criteria
   * @param {Object} criteria - Search criteria
   * @return {string} Drive API query string
   */
  build(criteria) {
    const queryParts = [];
    
    // Name criteria
    if (criteria.name) {
      const field = 'title';
      if (criteria.name.exactMatch) {
        queryParts.push(`${field} = '${this.escapeQuery(criteria.name.pattern)}'`);
      } else {
        queryParts.push(`${field} contains '${this.escapeQuery(criteria.name.pattern)}'`);
      }
    }
    
    // MIME type criteria
    if (criteria.mimeType) {
      if (Array.isArray(criteria.mimeType)) {
        const mimeQueries = criteria.mimeType.map(type => `mimeType = '${type}'`);
        queryParts.push(`(${mimeQueries.join(' or ')})`);
      } else {
        queryParts.push(`mimeType = '${criteria.mimeType}'`);
      }
    }
    
    // Exclude MIME types
    if (criteria.excludeMimeTypes) {
      const excludeQueries = criteria.excludeMimeTypes.map(type => `mimeType != '${type}'`);
      queryParts.push(`(${excludeQueries.join(' and ')})`);
    }
    
    // Size criteria
    if (criteria.size) {
      if (criteria.size.min) {
        queryParts.push(`size > ${criteria.size.min}`);
      }
      if (criteria.size.max) {
        queryParts.push(`size < ${criteria.size.max}`);
      }
    }
    
    // Date criteria
    if (criteria.created) {
      if (criteria.created.after) {
  queryParts.push(`createdDate > '${this.formatDate(criteria.created.after)}'`);
      }
      if (criteria.created.before) {
  queryParts.push(`createdDate < '${this.formatDate(criteria.created.before)}'`);
      }
    }
    
    if (criteria.modified) {
      if (criteria.modified.after) {
  queryParts.push(`modifiedDate > '${this.formatDate(criteria.modified.after)}'`);
      }
      if (criteria.modified.before) {
  queryParts.push(`modifiedDate < '${this.formatDate(criteria.modified.before)}'`);
      }
    }
    
    // Content criteria
    if (criteria.content) {
      if (criteria.content.exactPhrase) {
        queryParts.push(`fullText contains '"${this.escapeQuery(criteria.content.text)}"'`);
      } else {
        queryParts.push(`fullText contains '${this.escapeQuery(criteria.content.text)}'`);
      }
    }
    
    // Folder criteria
    if (criteria.parents) {
      if (Array.isArray(criteria.parents)) {
        const parentQueries = criteria.parents.map(parent => `'${parent}' in parents`);
        queryParts.push(`(${parentQueries.join(' or ')})`);
      } else {
        queryParts.push(`'${criteria.parents}' in parents`);
      }
    }
    
    // Status criteria
    if (criteria.trashed === false) {
      queryParts.push('trashed = false');
    } else if (criteria.trashed === true) {
      queryParts.push('trashed = true');
    }
    
    if (criteria.starred === true) {
      queryParts.push('starred = true');
    }
    
    // Default to non-trashed files if not specified
    if (criteria.trashed === undefined) {
      queryParts.push('trashed = false');
    }
    
    return queryParts.length > 0 ? queryParts.join(' and ') : '';
  }

  /**
   * Escape query string for Drive API
   * @param {string} query - Query to escape
   * @return {string} Escaped query
   * @private
   */
  escapeQuery(query) {
    return query.replace(/'/g, "\\'").replace(/\\/g, '\\\\');
  }

  /**
   * Format date for Drive API
   * @param {Date} date - Date to format
   * @return {string} Formatted date
   * @private
   */
  formatDate(date) {
  return date.toISOString();
  }
}

// Create global searcher instance used by analytics/tests
const DRIVE_SEARCHER = new DriveSearcher();

---

## 7. File Sharing and Permissions

#### Quick usage — DriveSearcher + DriveQueryBuilder

```javascript
function driveSearchExamples() {
  const searcher = new DriveSearcher();

  // Find recent PDFs in a specific folder (last 7 days)
  const results = searcher.advancedSearch({
    mimeType: 'application/pdf',
    parents: 'REPLACE_WITH_FOLDER_ID',
    modified: { after: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000) }
  }, { maxResults: 50, sortBy: 'modified' });

  console.log('Found PDFs:', results.length);
}
```

### PermissionManager Class - Advanced Sharing and Access Control

```javascript
/**
 * PermissionManager - Advanced file sharing and permission management
 * Provides comprehensive access control with security features
 * @class
 */
class PermissionManager {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.permissionLog = [];
  }

  /**
   * Share file or folder with advanced options
   * @param {string} fileId - File or folder ID
   * @param {Object} sharing - Sharing configuration
   * @return {Array<Object>} Sharing results
   */
  shareResource(fileId, sharing) {
    const {
      users = [],
      groups = [],
      domains = [],
      publicAccess = null,
      role = this.config.get('defaultSharingRole'),
      sendNotification = true,
      message = '',
      expirationDate = null,
      preventDownload = false,
      requireComment = this.config.get('requireComment')
    } = sharing;

    const results = [];

    try {
      console.log(`🔗 Sharing resource: ${fileId}`);
      
      const resource = this.getResource(fileId);
      
      // Validate sharing settings
      this.validateSharingConfig(sharing);
      
      // Share with individual users
      for (const userEmail of users) {
        try {
          const permission = this.shareWithUser(resource, userEmail, role, {
            sendNotification,
            message,
            expirationDate,
            preventDownload,
            requireComment
          });
          
          results.push({
            type: 'user',
            email: userEmail,
            role: role,
            success: true,
            permissionId: permission.getId()
          });
          
        } catch (error) {
          results.push({
            type: 'user',
            email: userEmail,
            role: role,
            success: false,
            error: error.message
          });
        }
      }
      
      // Share with groups
      for (const groupEmail of groups) {
        try {
          const permission = this.shareWithGroup(resource, groupEmail, role, {
            sendNotification,
            message
          });
          
          results.push({
            type: 'group',
            email: groupEmail,
            role: role,
            success: true,
            permissionId: permission.getId()
          });
          
        } catch (error) {
          results.push({
            type: 'group',
            email: groupEmail,
            role: role,
            success: false,
            error: error.message
          });
        }
      }
      
      // Share with domains
      for (const domain of domains) {
        try {
          const permission = this.shareWithDomain(resource, domain, role);
          
          results.push({
            type: 'domain',
            domain: domain,
            role: role,
            success: true,
            permissionId: permission.getId()
          });
          
        } catch (error) {
          results.push({
            type: 'domain',
            domain: domain,
            role: role,
            success: false,
            error: error.message
          });
        }
      }
      
      // Set public access
      if (publicAccess) {
        try {
          this.setPublicAccess(resource, publicAccess.access, publicAccess.role);
          
          results.push({
            type: 'public',
            access: publicAccess.access,
            role: publicAccess.role,
            success: true,
            url: resource.getUrl()
          });
          
        } catch (error) {
          results.push({
            type: 'public',
            access: publicAccess.access,
            role: publicAccess.role,
            success: false,
            error: error.message
          });
        }
      }
      
      // Log sharing operation
      this.logPermissionOperation('share', {
        fileId: fileId,
        fileName: resource.getName(),
        sharing: sharing,
        results: results
      });
      
      console.log(`✅ Sharing completed: ${results.filter(r => r.success).length}/${results.length} successful`);
      return results;
      
    } catch (error) {
      console.error(`❌ Error sharing resource: ${error.message}`);
      throw error;
    }
  }

  /**
   * Bulk share multiple files with same configuration
   * @param {Array<string>} fileIds - Array of file IDs
   * @param {Object} sharing - Sharing configuration
   * @param {Object} options - Bulk options
   * @return {Object} Bulk sharing results
   */
  bulkShare(fileIds, sharing, options = {}) {
    const {
      continueOnError = true,
      batchSize = this.config.get('batchSize'),
      onProgress = null
    } = options;

    const results = {
      total: fileIds.length,
      successful: 0,
      failed: 0,
      results: []
    };

    try {
      console.log(`🔗 Bulk sharing ${fileIds.length} files`);
      
      for (let i = 0; i < fileIds.length; i += batchSize) {
        const batch = fileIds.slice(i, i + batchSize);
        
        for (const fileId of batch) {
          try {
            const shareResults = this.shareResource(fileId, sharing);
            const successCount = shareResults.filter(r => r.success).length;
            
            results.results.push({
              fileId: fileId,
              success: successCount === shareResults.length,
              shareResults: shareResults
            });
            
            if (successCount === shareResults.length) {
              results.successful++;
            } else {
              results.failed++;
            }
            
          } catch (error) {
            results.results.push({
              fileId: fileId,
              success: false,
              error: error.message
            });
            results.failed++;
            
            if (!continueOnError) {
              throw error;
            }
          }
        }
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, fileIds.length);
          onProgress(progress, fileIds.length, results);
        }
        
        // Delay between batches
        if (i + batchSize < fileIds.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      console.log(`✅ Bulk sharing completed: ${results.successful}/${results.total} successful`);
      return results;
      
    } catch (error) {
      console.error(`❌ Bulk sharing failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get all permissions for a resource
   * @param {string} fileId - File or folder ID
   * @param {boolean} includeDetails - Include detailed permission info
   * @return {Array<Object>} Array of permissions
   */
  getResourcePermissions(fileId, includeDetails = false) {
    try {
      console.log(`🔍 Getting permissions for: ${fileId}`);
      
      const resource = this.getResource(fileId);
  const editors = resource.getEditors();
  const viewers = resource.getViewers();
      const permissions = [];
      
      // Add owner
      const owner = resource.getOwner();
      permissions.push({
        type: 'user',
        email: owner.getEmail(),
        name: owner.getName && owner.getName() || owner.getEmail(),
        role: 'owner'
      });
      
      // Add editors
    editors.forEach(user => {
        permissions.push({
          type: 'user',
          email: user.getEmail(),
      name: user.getEmail(),
      role: 'writer'
        });
      });
      
      // Add viewers
    viewers.forEach(user => {
        permissions.push({
          type: 'user',
          email: user.getEmail(),
      name: user.getEmail(),
      role: 'reader'
        });
      });
      
      // Add sharing access info
  const sharingAccess = resource.getSharingAccess();
  const sharingPermission = resource.getSharingPermission();
      
      if (sharingAccess !== DriveApp.Access.PRIVATE) {
        permissions.push({
          type: 'public',
          access: sharingAccess,
          role: sharingPermission,
          url: resource.getUrl()
        });
      }
      
      // Add detailed info if requested
      if (includeDetails) {
        permissions.forEach(permission => {
          permission.canShare = this.canUserShare(resource, permission.email);
          permission.canEdit = permission.role === 'writer' || permission.role === 'owner';
          permission.canComment = permission.role !== 'reader' || permission.role === 'commenter';
        });
      }
      
      console.log(`✅ Found ${permissions.length} permissions`);
      return permissions;
      
    } catch (error) {
      console.error(`❌ Error getting permissions: ${error.message}`);
      throw error;
    }
  }

  /**
   * Remove user or group access from resource
   * @param {string} fileId - File or folder ID
   * @param {string} email - User or group email
   * @return {boolean} Success status
   */
  removeAccess(fileId, email) {
    try {
      console.log(`🚫 Removing access for: ${email} from ${fileId}`);
      
      const resource = this.getResource(fileId);
      
      // Try to remove as editor first
      try {
        resource.removeEditor(email);
        console.log(`✅ Removed editor access: ${email}`);
        this.logPermissionOperation('remove_access', {
          fileId: fileId,
          email: email,
          role: 'writer'
        });
        return true;
      } catch (error) {
        // Not an editor, try viewer
      }
      
      // Try to remove as viewer
      try {
        resource.removeViewer(email);
        console.log(`✅ Removed viewer access: ${email}`);
        this.logPermissionOperation('remove_access', {
          fileId: fileId,
          email: email,
          role: 'reader'
        });
        return true;
      } catch (error) {
        console.warn(`⚠️ User ${email} not found in permissions`);
        return false;
      }
      
    } catch (error) {
      console.error(`❌ Error removing access: ${error.message}`);
      return false;
    }
  }

  /**
   * Change user's role for a resource
   * @param {string} fileId - File or folder ID
   * @param {string} email - User email
   * @param {string} newRole - New role (reader, writer, commenter)
   * @return {boolean} Success status
   */
  changeUserRole(fileId, email, newRole) {
    try {
      console.log(`🔄 Changing role for ${email} to ${newRole} on ${fileId}`);
      
      // Remove current access
      this.removeAccess(fileId, email);
      
      // Add with new role
      const resource = this.getResource(fileId);
      const permission = this.shareWithUser(resource, email, newRole);
      
      this.logPermissionOperation('change_role', {
        fileId: fileId,
        email: email,
        newRole: newRole,
        permissionId: permission.getId()
      });
      
      console.log(`✅ Role changed: ${email} -> ${newRole}`);
      return true;
      
    } catch (error) {
      console.error(`❌ Error changing role: ${error.message}`);
      return false;
    }
  }

  /**
   * Set public sharing for resource
   * @param {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} resource - Resource to share
   * @param {string} access - Access level (ANYONE, ANYONE_WITH_LINK, DOMAIN)
   * @param {string} role - Permission role (reader, writer, commenter)
   * @private
   */
  setPublicAccess(resource, access, role) {
    let driveAccess;
    let drivePermission;
    
    // Map access levels
    switch (access.toUpperCase()) {
      case 'ANYONE':
        driveAccess = DriveApp.Access.ANYONE;
        break;
      case 'ANYONE_WITH_LINK':
        driveAccess = DriveApp.Access.ANYONE_WITH_LINK;
        break;
      case 'DOMAIN':
        driveAccess = DriveApp.Access.DOMAIN;
        break;
      default:
        throw new Error(`Invalid access level: ${access}`);
    }
    
    // Map permission roles
    switch (role.toLowerCase()) {
      case 'reader':
        drivePermission = DriveApp.Permission.VIEW;
        break;
      case 'writer':
        drivePermission = DriveApp.Permission.EDIT;
        break;
      case 'commenter':
        drivePermission = DriveApp.Permission.COMMENT;
        break;
      default:
        throw new Error(`Invalid role: ${role}`);
    }
    
    // Check external sharing policy
    if (!this.config.get('allowExternalSharing') && 
        (driveAccess === DriveApp.Access.ANYONE || driveAccess === DriveApp.Access.ANYONE_WITH_LINK)) {
      throw new Error('External sharing is not allowed by policy');
    }
    
  resource.setSharing(driveAccess, drivePermission);
  }

  /**
   * Share with individual user
   * @param {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} resource - Resource to share
   * @param {string} email - User email
   * @param {string} role - Permission role
   * @param {Object} options - Sharing options
   * @return {GoogleAppsScript.Drive.Permission} Permission object
   * @private
   */
  shareWithUser(resource, email, role, options = {}) {
    const {
      sendNotification = true,
      message = '',
      expirationDate = null
    } = options;
    
    this.validateEmail(email);
    
    switch (role.toLowerCase()) {
      case 'reader':
      case 'viewer':
        resource.addViewer(email);
        if (!sendNotification) {
          // Note: Apps Script doesn't directly support suppressing notifications
          console.log('⚠️ Notification suppression not directly supported');
        }
        break;
        
      case 'writer':
      case 'editor':
        resource.addEditor(email);
        break;
        
      case 'commenter':
        // Note: Apps Script doesn't directly support commenter role
        // Fall back to viewer
        resource.addViewer(email);
        console.log('⚠️ Commenter role not directly supported, using viewer');
        break;
        
      default:
        throw new Error(`Invalid role: ${role}`);
    }
    
    // Return a mock permission object since Apps Script doesn't provide direct access
    return {
      getId: () => `perm_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
      getEmail: () => email,
      getRole: () => role
    };
  }

  /**
   * Share with group
   * @param {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} resource - Resource to share
   * @param {string} groupEmail - Group email
   * @param {string} role - Permission role
   * @param {Object} options - Sharing options
   * @return {GoogleAppsScript.Drive.Permission} Permission object
   * @private
   */
  shareWithGroup(resource, groupEmail, role, options = {}) {
    // Groups are treated similar to users in Apps Script
    return this.shareWithUser(resource, groupEmail, role, options);
  }

  /**
   * Share with domain
   * @param {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} resource - Resource to share
   * @param {string} domain - Domain name
   * @param {string} role - Permission role
   * @return {GoogleAppsScript.Drive.Permission} Permission object
   * @private
   */
  shareWithDomain(resource, domain, role) {
    // Set domain-wide sharing
    this.setPublicAccess(resource, 'DOMAIN', role);
    
    return {
      getId: () => `domain_perm_${Date.now()}`,
      getDomain: () => domain,
      getRole: () => role
    };
  }

  /**
   * Get resource (file or folder)
   * @param {string} resourceId - Resource ID
   * @return {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} Resource
   * @private
   */
  getResource(resourceId) {
    try {
      // Try as file first
      return DriveApp.getFileById(resourceId);
    } catch (error) {
      try {
        // Try as folder
        return DriveApp.getFolderById(resourceId);
      } catch (error2) {
        throw new Error(`Resource not found: ${resourceId}`);
      }
    }
  }

  /**
   * Validate sharing configuration
   * @param {Object} sharing - Sharing configuration
   * @private
   */
  validateSharingConfig(sharing) {
    // Check if external sharing is allowed
    const hasExternalUsers = sharing.users?.some(email => !this.isInternalEmail(email)) || false;
    const hasPublicAccess = sharing.publicAccess !== null;
    
    if (!this.config.get('allowExternalSharing') && (hasExternalUsers || hasPublicAccess)) {
      throw new Error('External sharing is not allowed by policy');
    }
    
    // Validate email addresses
    if (sharing.users) {
      sharing.users.forEach(email => this.validateEmail(email));
    }
    
    if (sharing.groups) {
      sharing.groups.forEach(email => this.validateEmail(email));
    }
  }

  /**
   * Validate email address format
   * @param {string} email - Email to validate
   * @private
   */
  validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      throw new Error(`Invalid email format: ${email}`);
    }
  }

  /**
   * Check if email is internal (same domain)
   * @param {string} email - Email to check
   * @return {boolean} Whether email is internal
   * @private
   */
  isInternalEmail(email) {
    const userDomain = Session.getActiveUser().getEmail().split('@')[1];
    const emailDomain = email.split('@')[1];
    return emailDomain === userDomain;
  }

  /**
   * Check if user can share a resource
   * @param {GoogleAppsScript.Drive.File|GoogleAppsScript.Drive.Folder} resource - Resource
   * @param {string} email - User email
   * @return {boolean} Whether user can share
   * @private
   */
  canUserShare(resource, email) {
    try {
      // Check if user is owner
      if (resource.getOwner().getEmail() === email) {
        return true;
      }
      
      // Check if user is editor (editors can usually share)
      const editors = resource.getEditors();
      return editors.some(editor => editor.getEmail() === email);
      
    } catch (error) {
      return false;
    }
  }

  /**
   * Log permission operation
   * @param {string} operation - Operation type
   * @param {Object} details - Operation details
   * @private
   */
  logPermissionOperation(operation, details) {
    if (this.config.get('enableLogging')) {
      const logEntry = {
        timestamp: new Date(),
        operation: operation,
        details: details,
        user: Session.getActiveUser().getEmail()
      };
      
      this.permissionLog.push(logEntry);
      
      // Keep log size manageable
      if (this.permissionLog.length > 1000) {
        this.permissionLog = this.permissionLog.slice(-500);
      }
    }
  }

  /**
   * Get sharing statistics
   * @return {Object} Sharing statistics
   */
  getSharingStats() {
    const stats = {
      totalOperations: this.permissionLog.length,
      operationTypes: {},
      recentActivity: this.permissionLog.slice(-10)
    };
    
    this.permissionLog.forEach(entry => {
      stats.operationTypes[entry.operation] = (stats.operationTypes[entry.operation] || 0) + 1;
    });
    
    return stats;
  }

  /**
   * Clear permission logs
   */
  clearLogs() {
    this.permissionLog = [];
    console.log('🧹 Permission logs cleared');
  }
}

// Create global permission manager instance
const PERMISSION_MANAGER = new PermissionManager();

---

## 8. Batch Processing

#### Quick usage — PermissionManager

```javascript
function permissionManagerExamples() {
  const folder = DRIVE_MANAGER.createFolder('Shared Assets');

  // Share with users and a group
  const res = PERMISSION_MANAGER.shareResource(folder.getId(), {
    users: ['user1@example.com', 'user2@example.com'],
    groups: ['team@example.com'],
    role: 'reader',
    sendNotification: false
  });
  console.log(res);
}
```

### DriveBatchProcessor Class - Efficient Bulk Operations

```javascript
/**
 * DriveBatchProcessor - Efficient batch processing for Drive operations
 * Handles large-scale operations with rate limiting and progress tracking
 * @class
 */
class DriveBatchProcessor {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.processingQueue = [];
    this.activeOperations = new Map();
  }

  /**
   * Batch file operations with comprehensive options
   * @param {Array<Object>} operations - Array of operations
   * @param {Object} options - Batch processing options
   * @return {Object} Batch processing results
   */
  processOperations(operations, options = {}) {
    const {
      batchSize = this.config.get('batchSize'),
      maxConcurrent = 3,
      retryAttempts = this.config.get('retryAttempts'),
      onProgress = null,
      onError = null,
      continueOnError = true,
      dryRun = false
    } = options;

    const operationId = `batch_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    
    const results = {
      operationId: operationId,
      total: operations.length,
      processed: 0,
      successful: 0,
      failed: 0,
      skipped: 0,
      results: [],
      startTime: new Date(),
      endTime: null,
      errors: []
    };

    try {
      console.log(`⚙️ Starting batch processing: ${operations.length} operations`);
      
      this.activeOperations.set(operationId, {
        status: 'running',
        results: results,
        startTime: new Date()
      });

      // Process in batches
      for (let i = 0; i < operations.length; i += batchSize) {
        const batch = operations.slice(i, i + batchSize);
        
        console.log(`📦 Processing batch ${Math.floor(i / batchSize) + 1}/${Math.ceil(operations.length / batchSize)}`);
        
        const batchResults = this.processBatch(batch, {
          retryAttempts,
          continueOnError,
          dryRun
        });
        
        // Update results
        results.results.push(...batchResults.results);
        results.processed += batchResults.processed;
        results.successful += batchResults.successful;
        results.failed += batchResults.failed;
        results.skipped += batchResults.skipped;
        results.errors.push(...batchResults.errors);
        
        // Progress callback
        if (onProgress) {
          const progress = Math.min(i + batchSize, operations.length);
          onProgress(progress, operations.length, results);
        }
        
        // Error callback
        if (onError && batchResults.errors.length > 0) {
          onError(batchResults.errors, results);
        }
        
        // Delay between batches to avoid rate limits
        if (i + batchSize < operations.length) {
          Utilities.sleep(this.config.get('operationDelay'));
        }
      }
      
      results.endTime = new Date();
      results.duration = results.endTime - results.startTime;
      
      this.activeOperations.get(operationId).status = 'completed';
      
      console.log(`✅ Batch processing completed: ${results.successful}/${results.total} successful`);
      return results;
      
    } catch (error) {
      results.endTime = new Date();
      results.duration = results.endTime - results.startTime;
      
      this.activeOperations.get(operationId).status = 'failed';
      
      console.error(`❌ Batch processing failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Bulk move files to target folder
   * @param {Array<string>} fileIds - File IDs to move
   * @param {string} targetFolderId - Target folder ID
   * @param {Object} options - Move options
   * @return {Object} Move results
   */
  bulkMoveFiles(fileIds, targetFolderId, options = {}) {
    const operations = fileIds.map(fileId => ({
      type: 'move',
      fileId: fileId,
      targetFolderId: targetFolderId
    }));

    return this.processOperations(operations, {
      ...options,
      operationType: 'move'
    });
  }

  /**
   * Bulk copy files to target folder
   * @param {Array<string>} fileIds - File IDs to copy
   * @param {string} targetFolderId - Target folder ID
   * @param {Object} options - Copy options
   * @return {Object} Copy results
   */
  bulkCopyFiles(fileIds, targetFolderId, options = {}) {
    const {
      namePrefix = 'Copy of ',
      preserveNames = false
    } = options;

    const operations = fileIds.map(fileId => ({
      type: 'copy',
      fileId: fileId,
      targetFolderId: targetFolderId,
      namePrefix: preserveNames ? '' : namePrefix
    }));

    return this.processOperations(operations, {
      ...options,
      operationType: 'copy'
    });
  }

  /**
   * Bulk delete files
   * @param {Array<string>} fileIds - File IDs to delete
   * @param {Object} options - Delete options
   * @return {Object} Delete results
   */
  bulkDeleteFiles(fileIds, options = {}) {
    const {
      permanent = false,
      confirmDelete = true
    } = options;

    if (confirmDelete) {
      console.log(`⚠️ About to delete ${fileIds.length} files (permanent: ${permanent})`);
      // In a real implementation, you might want to add user confirmation
    }

    const operations = fileIds.map(fileId => ({
      type: 'delete',
      fileId: fileId,
      permanent: permanent
    }));

    return this.processOperations(operations, {
      ...options,
      operationType: 'delete'
    });
  }

  /**
   * Bulk rename files
   * @param {Array<Object>} renameSpecs - Rename specifications
   * @param {Object} options - Rename options
   * @return {Object} Rename results
   */
  bulkRenameFiles(renameSpecs, options = {}) {
    const operations = renameSpecs.map(spec => ({
      type: 'rename',
      fileId: spec.fileId,
      newName: spec.newName
    }));

    return this.processOperations(operations, {
      ...options,
      operationType: 'rename'
    });
  }

  /**
   * Bulk apply permissions
   * @param {Array<string>} fileIds - File IDs
   * @param {Object} permissions - Permission configuration
   * @param {Object} options - Permission options
   * @return {Object} Permission results
   */
  bulkApplyPermissions(fileIds, permissions, options = {}) {
    const operations = fileIds.map(fileId => ({
      type: 'permissions',
      fileId: fileId,
      permissions: permissions
    }));

    return this.processOperations(operations, {
      ...options,
      operationType: 'permissions'
    });
  }

  /**
   * Process a single batch
   * @param {Array<Object>} batch - Batch operations
   * @param {Object} options - Processing options
   * @return {Object} Batch results
   * @private
   */
  processBatch(batch, options = {}) {
    const {
      retryAttempts = 3,
      continueOnError = true,
      dryRun = false
    } = options;

    const batchResults = {
      processed: 0,
      successful: 0,
      failed: 0,
      skipped: 0,
      results: [],
      errors: []
    };

    for (const operation of batch) {
      let attempt = 0;
      let success = false;
      
      while (attempt < retryAttempts && !success) {
        try {
          batchResults.processed++;
          
          if (dryRun) {
            // Dry run - just validate the operation
            this.validateOperation(operation);
            batchResults.results.push({
              operation: operation,
              success: true,
              dryRun: true,
              message: 'Validation successful'
            });
            batchResults.successful++;
            success = true;
          } else {
            // Execute the actual operation
            const result = this.executeOperation(operation);
            batchResults.results.push({
              operation: operation,
              success: true,
              result: result
            });
            batchResults.successful++;
            success = true;
          }
          
        } catch (error) {
          attempt++;
          
          if (attempt >= retryAttempts) {
            batchResults.failed++;
            batchResults.errors.push({
              operation: operation,
              error: error.message,
              attempts: attempt
            });
            batchResults.results.push({
              operation: operation,
              success: false,
              error: error.message,
              attempts: attempt
            });
            
            if (!continueOnError) {
              throw error;
            }
          } else {
            console.warn(`⚠️ Operation failed (attempt ${attempt}/${retryAttempts}): ${error.message}`);
            Utilities.sleep(1000 * attempt); // Exponential backoff
          }
        }
      }
    }

    return batchResults;
  }

  /**
   * Execute a single operation
   * @param {Object} operation - Operation to execute
   * @return {*} Operation result
   * @private
   */
  executeOperation(operation) {
    const { type } = operation;
    
    switch (type) {
      case 'move':
        return this.driveManager.moveFile(operation.fileId, operation.targetFolderId);
        
      case 'copy':
        const copyOptions = {
          newName: operation.namePrefix ? `${operation.namePrefix}${this.driveManager.getFileById(operation.fileId).getName()}` : null,
          parentFolderId: operation.targetFolderId
        };
        return this.driveManager.copyFile(operation.fileId, copyOptions);
        
      case 'delete':
        return this.driveManager.deleteFile(operation.fileId, operation.permanent);
        
      case 'rename':
        const file = this.driveManager.getFileById(operation.fileId);
        file.setName(operation.newName);
        return file;
        
      case 'permissions':
        return PERMISSION_MANAGER.shareResource(operation.fileId, operation.permissions);
        
      case 'convert':
        const sourceFile = this.driveManager.getFileById(operation.fileId);
        return FILE_MANAGER.convertToGoogleFormat(sourceFile);
        
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
  }

  /**
   * Validate operation before execution
   * @param {Object} operation - Operation to validate
   * @private
   */
  validateOperation(operation) {
    const { type } = operation;
    
    switch (type) {
      case 'move':
      case 'copy':
      case 'delete':
      case 'rename':
      case 'convert':
        if (!operation.fileId) {
          throw new Error('File ID is required');
        }
        // Verify file exists
        this.driveManager.getFileById(operation.fileId);
        break;
        
      case 'permissions':
        if (!operation.fileId || !operation.permissions) {
          throw new Error('File ID and permissions are required');
        }
        break;
        
      default:
        throw new Error(`Unknown operation type: ${type}`);
    }
    
    // Additional validations based on operation type
    if (operation.targetFolderId) {
      // Verify target folder exists
      this.driveManager.getFolderById(operation.targetFolderId);
    }
  }

  /**
   * Get batch operation status
   * @param {string} operationId - Operation ID
   * @return {Object} Operation status
   */
  getOperationStatus(operationId) {
    if (!this.activeOperations.has(operationId)) {
      return { error: 'Operation not found' };
    }
    
    const operation = this.activeOperations.get(operationId);
    return {
      operationId: operationId,
      status: operation.status,
      startTime: operation.startTime,
      results: operation.results
    };
  }

  /**
   * Cancel running operation
   * @param {string} operationId - Operation ID
   * @return {boolean} Success status
   */
  cancelOperation(operationId) {
    if (!this.activeOperations.has(operationId)) {
      return false;
    }
    
    const operation = this.activeOperations.get(operationId);
    if (operation.status === 'running') {
      operation.status = 'cancelled';
      console.log(`🛑 Operation cancelled: ${operationId}`);
      return true;
    }
    
    return false;
  }

  /**
   * Clean up completed operations
   * @param {number} olderThanHours - Clean operations older than specified hours
   */
  cleanupOperations(olderThanHours = 24) {
    const cutoffTime = new Date(Date.now() - (olderThanHours * 60 * 60 * 1000));
    const toDelete = [];
    
    for (const [operationId, operation] of this.activeOperations) {
      if (operation.startTime < cutoffTime && operation.status !== 'running') {
        toDelete.push(operationId);
      }
    }
    
    toDelete.forEach(operationId => {
      this.activeOperations.delete(operationId);
    });
    
    console.log(`🧹 Cleaned up ${toDelete.length} completed operations`);
  }

  /**
   * Get processing statistics
   * @return {Object} Processing statistics
   */
  getProcessingStats() {
    const stats = {
      activeOperations: 0,
      completedOperations: 0,
      failedOperations: 0,
      cancelledOperations: 0,
      totalOperations: this.activeOperations.size
    };
    
    for (const operation of this.activeOperations.values()) {
      switch (operation.status) {
        case 'running':
          stats.activeOperations++;
          break;
        case 'completed':
          stats.completedOperations++;
          break;
        case 'failed':
          stats.failedOperations++;
          break;
        case 'cancelled':
          stats.cancelledOperations++;
          break;
      }
    }
    
    return stats;
  }

  /**
   * Queue operation for later processing
   * @param {Object} operation - Operation to queue
   * @return {string} Queue ID
   */
  queueOperation(operation) {
    const queueId = `queue_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    
    this.processingQueue.push({
      id: queueId,
      operation: operation,
      queued: new Date(),
      status: 'queued'
    });
    
    console.log(`📋 Operation queued: ${queueId}`);
    return queueId;
  }

  /**
   * Process all queued operations
   * @param {Object} options - Processing options
   * @return {Object} Processing results
   */
  processQueue(options = {}) {
    if (this.processingQueue.length === 0) {
      console.log('📭 No operations in queue');
      return { message: 'No operations to process' };
    }
    
    const queuedOperations = this.processingQueue.map(item => item.operation);
    this.processingQueue = []; // Clear queue
    
    console.log(`⚙️ Processing ${queuedOperations.length} queued operations`);
    return this.processOperations(queuedOperations, options);
  }

  /**
   * Get queue status
   * @return {Object} Queue information
   */
  getQueueStatus() {
    return {
      queueSize: this.processingQueue.length,
      oldestItem: this.processingQueue.length > 0 ? this.processingQueue[0].queued : null,
      newestItem: this.processingQueue.length > 0 ? this.processingQueue[this.processingQueue.length - 1].queued : null
    };
  }
}

// Create global batch processor instance
const DRIVE_BATCH_PROCESSOR = new DriveBatchProcessor();

---

## 9. File Analytics and Monitoring

#### Quick usage — DriveBatchProcessor

```javascript
function driveBatchProcessorExamples() {
  // Prepare operations
  const f1 = DRIVE_MANAGER.createFile('a.txt', 'A');
  const f2 = DRIVE_MANAGER.createFile('b.txt', 'B');
  const archive = DRIVE_MANAGER.createFolder('Batch Archive');

  const ops = [
    { type: 'move', fileId: f1.getId(), targetFolderId: archive.getId() },
    { type: 'copy', fileId: f2.getId(), targetFolderId: archive.getId(), namePrefix: '' }
  ];

  const result = DRIVE_BATCH_PROCESSOR.processOperations(ops, { batchSize: 2 });
  console.log(result.summary || result);
}
```

### DriveAnalytics Class - Comprehensive Drive Analytics

```javascript
/**
 * DriveAnalytics - Comprehensive analytics and monitoring for Google Drive
 * Provides insights, reporting, and monitoring capabilities
 * @class
 */
class DriveAnalytics {
  constructor() {
    this.config = DRIVE_CONFIG;
    this.analyticsData = new Map();
    this.reportCache = new Map();
  }

  /**
   * Generate comprehensive Drive analytics report
   * @param {Object} options - Report options
   * @return {Object} Analytics report
   */
  generateAnalyticsReport(options = {}) {
    const {
      period = 'month', // week, month, quarter, year
      includeCharts = false,
      exportToSheets = false,
      detailed = true
    } = options;

    try {
      console.log(`📊 Generating analytics report for period: ${period}`);
      
      const reportId = `report_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      const startTime = new Date();
      
      // Collect analytics data
      const data = {
        overview: this.getOverviewStats(),
        storage: this.getStorageAnalytics(),
        fileTypes: this.getFileTypeDistribution(),
        activity: this.getActivityAnalytics(period),
        sharing: this.getSharingAnalytics(),
        performance: this.getPerformanceMetrics(),
        trends: this.getTrendAnalysis(period)
      };
      
      if (detailed) {
        data.detailed = {
          largestFiles: this.getLargestFiles(20),
          duplicates: this.findDuplicateFiles(),
          orphaned: this.getOrphanedFiles(),
          oldFiles: this.getOldFiles(365) // Files older than 1 year
        };
      }
      
      const report = {
        id: reportId,
        generated: startTime,
        period: period,
        data: data,
        summary: this.generateReportSummary(data),
        recommendations: this.generateRecommendations(data),
        charts: includeCharts ? this.generateCharts(data) : null
      };
      
      // Cache the report
      this.reportCache.set(reportId, {
        report: report,
        timestamp: Date.now()
      });
      
      // Export to Sheets if requested
      if (exportToSheets) {
        this.exportReportToSheets(report);
      }
      
      const duration = new Date() - startTime;
      console.log(`✅ Analytics report generated: ${reportId} (${duration}ms)`);
      
      return report;
      
    } catch (error) {
      console.error(`❌ Error generating analytics report: ${error.message}`);
      throw error;
    }
  }

  /**
   * Get overview statistics
   * @return {Object} Overview stats
   * @private
   */
  getOverviewStats() {
    console.log('📈 Collecting overview statistics...');
    
    const allFiles = DRIVE_SEARCHER.advancedSearch({}, { maxResults: 10000 });
    
    let totalSize = 0;
    let totalFiles = allFiles.length;
    let folders = 0;
    let sharedFiles = 0;
    let starredFiles = 0;
    
    allFiles.forEach(file => {
      totalSize += file.size || 0;
      if (file.mimeType === this.config.mimeTypes.FOLDER) {
        folders++;
      }
      if (file.shared) {
        sharedFiles++;
      }
      if (file.starred) {
        starredFiles++;
      }
    });
    
    return {
      totalFiles: totalFiles,
      totalFolders: folders,
      totalSize: totalSize,
      averageFileSize: totalFiles > 0 ? Math.round(totalSize / totalFiles) : 0,
      sharedFiles: sharedFiles,
      starredFiles: starredFiles,
      sharingPercentage: totalFiles > 0 ? Math.round((sharedFiles / totalFiles) * 100) : 0
    };
  }

  /**
   * Get storage analytics
   * @return {Object} Storage analytics
   * @private
   */
  getStorageAnalytics() {
    console.log('💾 Analyzing storage usage...');
    
    const largeFiles = DRIVE_SEARCHER.findLargeFiles({
      minSize: 1024 * 1024, // 1MB
      includeDetails: true
    });
    
    let totalStorage = 0;
    let googleFilesStorage = 0;
    let otherFilesStorage = 0;
    
    largeFiles.forEach(file => {
      totalStorage += file.size;
      
      if (file.mimeType.includes('google-apps')) {
        googleFilesStorage += file.size;
      } else {
        otherFilesStorage += file.size;
      }
    });
    
    // Storage by file type
    const storageByType = {};
    largeFiles.forEach(file => {
      const category = this.getFileCategory(file.mimeType);
      storageByType[category] = (storageByType[category] || 0) + file.size;
    });
    
    return {
      totalStorage: totalStorage,
      googleFilesStorage: googleFilesStorage,
      otherFilesStorage: otherFilesStorage,
      largeFilesCount: largeFiles.length,
      storageByType: storageByType,
      compressionPotential: this.calculateCompressionPotential(largeFiles)
    };
  }

  /**
   * Get file type distribution
   * @return {Object} File type distribution
   * @private
   */
  getFileTypeDistribution() {
    console.log('📊 Analyzing file type distribution...');
    
    const allFiles = DRIVE_SEARCHER.advancedSearch({}, { maxResults: 10000 });
    const distribution = {};
    
    allFiles.forEach(file => {
      const category = this.getFileCategory(file.mimeType);
      if (!distribution[category]) {
        distribution[category] = { count: 0, size: 0 };
      }
      distribution[category].count++;
      distribution[category].size += file.size || 0;
    });
    
    // Calculate percentages
    const totalFiles = allFiles.length;
    const totalSize = allFiles.reduce((sum, file) => sum + (file.size || 0), 0);
    
    Object.values(distribution).forEach(category => {
      category.filePercentage = Math.round((category.count / totalFiles) * 100);
      category.sizePercentage = totalSize > 0 ? Math.round((category.size / totalSize) * 100) : 0;
    });
    
    return distribution;
  }

  /**
   * Get activity analytics for specified period
   * @param {string} period - Period (week, month, etc.)
   * @return {Object} Activity analytics
   * @private
   */
  getActivityAnalytics(period) {
    console.log(`📅 Analyzing activity for period: ${period}`);
    
    const days = this.getPeriodDays(period);
    const cutoffDate = new Date();
    cutoffDate.setDate(cutoffDate.getDate() - days);
    
    const recentFiles = DRIVE_SEARCHER.findRecentlyModified({
      days: days,
      includeCollaborators: true
    });
    
    // Group by day
    const dailyActivity = {};
    recentFiles.forEach(file => {
      const day = file.modified.split('T')[0]; // Get date part
      if (!dailyActivity[day]) {
        dailyActivity[day] = { created: 0, modified: 0, files: [] };
      }
      
      if (new Date(file.created) >= cutoffDate) {
        dailyActivity[day].created++;
      }
      dailyActivity[day].modified++;
      dailyActivity[day].files.push(file.name);
    });
    
    return {
      period: period,
      totalActivity: recentFiles.length,
      dailyActivity: dailyActivity,
      averageDailyActivity: Math.round(recentFiles.length / days),
      mostActiveDay: this.findMostActiveDay(dailyActivity),
      fileTypes: this.getActivityByFileType(recentFiles)
    };
  }

  /**
   * Get sharing analytics
   * @return {Object} Sharing analytics
   * @private
   */
  getSharingAnalytics() {
    console.log('🔗 Analyzing sharing patterns...');
    
    const allFiles = DRIVE_SEARCHER.advancedSearch({}, { maxResults: 10000 });
    
    let totalShared = 0;
    let publicFiles = 0;
    let domainShared = 0;
    let userShared = 0;
    const sharingPatterns = {};
    
    allFiles.forEach(file => {
      if (file.shared) {
        totalShared++;
        
        // Categorize sharing type (simplified analysis)
        if (file.url && file.url.includes('sharing')) {
          publicFiles++;
        } else {
          userShared++;
        }
      }
      
      // Track sharing by file type
      const category = this.getFileCategory(file.mimeType);
      if (!sharingPatterns[category]) {
        sharingPatterns[category] = { total: 0, shared: 0 };
      }
      sharingPatterns[category].total++;
      if (file.shared) {
        sharingPatterns[category].shared++;
      }
    });
    
    // Calculate sharing percentages by category
    Object.values(sharingPatterns).forEach(pattern => {
      pattern.sharingPercentage = Math.round((pattern.shared / pattern.total) * 100);
    });
    
    return {
      totalShared: totalShared,
      sharingPercentage: Math.round((totalShared / allFiles.length) * 100),
      publicFiles: publicFiles,
      userShared: userShared,
      sharingPatterns: sharingPatterns,
      securityScore: this.calculateSharingSecurityScore(totalShared, publicFiles, allFiles.length)
    };
  }

  /**
   * Get performance metrics
   * @return {Object} Performance metrics
   * @private
   */
  getPerformanceMetrics() {
    console.log('⚡ Collecting performance metrics...');
    
    // Get stats from various managers
    const driveStats = DRIVE_MANAGER.getOperationStats();
    const searchStats = DRIVE_SEARCHER.getSearchStats();
    const batchStats = DRIVE_BATCH_PROCESSOR.getProcessingStats();
    const folderStats = FOLDER_MANAGER.getStats();
    
    return {
      driveOperations: driveStats,
      searchOperations: searchStats,
      batchOperations: batchStats,
      folderOperations: folderStats,
      cacheEfficiency: this.calculateCacheEfficiency(),
      averageResponseTime: this.calculateAverageResponseTime()
    };
  }

  /**
   * Get trend analysis
   * @param {string} period - Analysis period
   * @return {Object} Trend analysis
   * @private
   */
  getTrendAnalysis(period) {
    console.log(`📈 Analyzing trends for period: ${period}`);
    
    // This would typically analyze historical data
    // For now, provide basic trend indicators
    
    const recentFiles = DRIVE_SEARCHER.findRecentlyModified({
      days: this.getPeriodDays(period)
    });
    
    const fileCreationTrend = this.analyzeCreationTrend(recentFiles, period);
    const storageTrend = this.analyzeStorageTrend(recentFiles);
    const sharingTrend = this.analyzeSharingTrend(recentFiles);
    
    return {
      fileCreation: fileCreationTrend,
      storageGrowth: storageTrend,
      sharingActivity: sharingTrend,
      predictions: this.generateTrendPredictions(fileCreationTrend, storageTrend)
    };
  }

  /**
   * Generate report summary
   * @param {Object} data - Analytics data
   * @return {Object} Report summary
   * @private
   */
  generateReportSummary(data) {
    return {
      keyMetrics: {
        totalFiles: data.overview.totalFiles,
        totalStorage: this.formatBytes(data.overview.totalSize),
        sharingPercentage: data.overview.sharingPercentage,
        activityLevel: data.activity.totalActivity
      },
      highlights: [
        `${data.overview.totalFiles} total files using ${this.formatBytes(data.overview.totalSize)}`,
        `${data.sharing.sharingPercentage}% of files are shared`,
        `${data.activity.totalActivity} files modified in the last ${data.activity.period}`,
        `Top file type: ${this.getTopFileType(data.fileTypes)}`
      ],
      concerns: this.identifyConcerns(data)
    };
  }

  /**
   * Generate recommendations based on analytics
   * @param {Object} data - Analytics data
   * @return {Array<Object>} Recommendations
   * @private
   */
  generateRecommendations(data) {
    const recommendations = [];
    
    // Storage recommendations
    if (data.storage.totalStorage > 10 * 1024 * 1024 * 1024) { // > 10GB
      recommendations.push({
        type: 'storage',
        priority: 'high',
        title: 'Storage Optimization Needed',
        description: 'Your Drive is using significant storage. Consider archiving old files or using compression.',
        action: 'Review and archive files older than 1 year'
      });
    }
    
    // Duplicate file recommendations
    if (data.detailed && data.detailed.duplicates.length > 0) {
      recommendations.push({
        type: 'efficiency',
        priority: 'medium',
        title: 'Duplicate Files Detected',
        description: `Found ${data.detailed.duplicates.length} groups of duplicate files.`,
        action: 'Review and remove duplicate files to save storage'
      });
    }
    
    // Sharing security recommendations
    if (data.sharing.publicFiles > 0) {
      recommendations.push({
        type: 'security',
        priority: 'high',
        title: 'Public Files Found',
        description: `${data.sharing.publicFiles} files are publicly accessible.`,
        action: 'Review public file sharing and restrict if necessary'
      });
    }
    
    // Organization recommendations
    if (data.detailed && data.detailed.orphaned.length > 10) {
      recommendations.push({
        type: 'organization',
        priority: 'low',
        title: 'Improve File Organization',
        description: `${data.detailed.orphaned.length} files are not properly organized in folders.`,
        action: 'Create folder structure and organize files'
      });
    }
    
    return recommendations;
  }

  /**
   * Export report to Google Sheets
   * @param {Object} report - Report to export
   * @return {string} Sheet URL
   * @private
   */
  exportReportToSheets(report) {
    try {
      console.log('📊 Exporting report to Google Sheets...');
      
      const sheetName = `Drive Analytics - ${report.generated.toDateString()}`;
      const ss = SpreadsheetApp.create(sheetName);
      
      // Overview sheet
      const overviewSheet = ss.getActiveSheet();
      overviewSheet.setName('Overview');
      
      this.writeOverviewToSheet(overviewSheet, report.data.overview, report.summary);
      
      // Storage sheet
      const storageSheet = ss.insertSheet('Storage Analysis');
      this.writeStorageToSheet(storageSheet, report.data.storage);
      
      // File Types sheet
      const typesSheet = ss.insertSheet('File Types');
      this.writeFileTypesToSheet(typesSheet, report.data.fileTypes);
      
      // Recommendations sheet
      const recSheet = ss.insertSheet('Recommendations');
      this.writeRecommendationsToSheet(recSheet, report.recommendations);
      
      console.log(`✅ Report exported to Sheets: ${ss.getUrl()}`);
      return ss.getUrl();
      
    } catch (error) {
      console.error(`❌ Error exporting to Sheets: ${error.message}`);
      throw error;
    }
  }

  /**
   * Helper methods for analytics calculations
   */
  
  getFileCategory(mimeType) {
    if (mimeType.includes('document')) return 'Documents';
    if (mimeType.includes('spreadsheet')) return 'Spreadsheets';
    if (mimeType.includes('presentation')) return 'Presentations';
    if (mimeType.startsWith('image/')) return 'Images';
    if (mimeType.startsWith('video/')) return 'Videos';
    if (mimeType.startsWith('audio/')) return 'Audio';
    if (mimeType.includes('pdf')) return 'PDFs';
    if (mimeType === this.config.mimeTypes.FOLDER) return 'Folders';
    return 'Other';
  }

  getPeriodDays(period) {
    switch (period) {
      case 'week': return 7;
      case 'month': return 30;
      case 'quarter': return 90;
      case 'year': return 365;
      default: return 30;
    }
  }

  formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  calculateSharingSecurityScore(totalShared, publicFiles, totalFiles) {
    const sharingRatio = totalShared / totalFiles;
    const publicRatio = publicFiles / totalFiles;
    
    // Higher score = more secure (less sharing)
    let score = 100;
    score -= sharingRatio * 30; // Reduce score based on sharing
    score -= publicRatio * 50;  // Reduce more for public files
    
    return Math.max(0, Math.round(score));
  }

  /**
   * Get analytics history
   * @param {number} days - Number of days of history
   * @return {Array<Object>} Historical analytics
   */
  getAnalyticsHistory(days = 30) {
    // This would typically fetch from stored analytics data
    // For now, return empty array as placeholder
    return [];
  }

  /**
   * Schedule periodic analytics
   * @param {string} frequency - Schedule frequency (daily, weekly, monthly)
   * @param {Object} options - Analytics options
   */
  schedulePeriodicAnalytics(frequency, options = {}) {
    console.log(`⏰ Scheduling periodic analytics: ${frequency}`);
    
    // This would set up triggers for periodic report generation
    // Implementation depends on specific requirements
    
    // Example: Create a time-based trigger
    const trigger = ScriptApp.newTrigger('generateScheduledAnalytics')
      .timeBased()
      .everyDays(frequency === 'daily' ? 1 : frequency === 'weekly' ? 7 : 30)
      .create();
    
    console.log(`✅ Analytics scheduled with trigger: ${trigger.getUniqueId()}`);
  }

  /**
   * Clear analytics cache
   */
  clearCache() {
    this.analyticsData.clear();
    this.reportCache.clear();
    console.log('🧹 Analytics cache cleared');
  }
}

// Create global analytics instance
const DRIVE_ANALYTICS = new DriveAnalytics();

#### Quick usage — DriveAnalytics

```javascript
function driveAnalyticsExamples() {
  const report = DRIVE_ANALYTICS.generateAnalyticsReport({ period: 'month', includeCharts: false });
  console.log('Report summary:', report.summary);
}
```

```javascript
/**
 * Scheduled analytics function (called by triggers)
 */
function generateScheduledAnalytics() {
  try {
    console.log('⏰ Running scheduled analytics...');
    
    const report = DRIVE_ANALYTICS.generateAnalyticsReport({
      period: 'week',
      exportToSheets: true,
      detailed: true
    });
    
    console.log('✅ Scheduled analytics completed');
    return report;
    
  } catch (error) {
    console.error('❌ Scheduled analytics failed:', error.message);
  }
}
```

---

## 10. Complete Project Examples

### Project 1: Enterprise Document Management System

```javascript
/**
 * Enterprise Document Management System
 * Complete solution for managing documents with approval workflows
 * @class
 */
class DocumentManagementSystem {
  constructor(configSpreadsheetId) {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.batchProcessor = DRIVE_BATCH_PROCESSOR;
    this.analytics = DRIVE_ANALYTICS;
    this.configSheet = SpreadsheetApp.openById(configSpreadsheetId);
    this.settings = this.loadSettings();
  }

  /**
   * Initialize the document management system
   * @param {Object} setupOptions - System setup options
   * @return {Object} Initialization result
   */
  initialize(setupOptions = {}) {
    try {
      console.log('🚀 Initializing Document Management System...');
      
      const {
        rootFolderName = 'Document Management System',
        departments = ['HR', 'Finance', 'Marketing', 'Operations'],
        documentTypes = ['Policies', 'Procedures', 'Reports', 'Templates'],
        approvalLevels = ['Draft', 'Review', 'Approved', 'Published', 'Archived']
      } = setupOptions;

      // Create folder structure
      const rootFolder = this.driveManager.createFolder(rootFolderName);
      console.log(`📁 Created root folder: ${rootFolder.getName()}`);

      const folderStructure = {};

      // Create department folders
      departments.forEach(dept => {
        const deptFolder = this.driveManager.createFolder(dept, rootFolder.getId());
        folderStructure[dept] = { folder: deptFolder, types: {} };

        // Create document type folders within each department
        documentTypes.forEach(type => {
          const typeFolder = this.driveManager.createFolder(type, deptFolder.getId());
          folderStructure[dept].types[type] = { folder: typeFolder, levels: {} };

          // Create approval level folders
          approvalLevels.forEach(level => {
            const levelFolder = this.driveManager.createFolder(level, typeFolder.getId());
            folderStructure[dept].types[type].levels[level] = levelFolder;
          });
        });
      });

      // Create system folders
      const systemFolders = {
        templates: this.driveManager.createFolder('_Templates', rootFolder.getId()),
        archive: this.driveManager.createFolder('_Archive', rootFolder.getId()),
        reports: this.driveManager.createFolder('_Reports', rootFolder.getId()),
        workflows: this.driveManager.createFolder('_Workflows', rootFolder.getId())
      };

      // Initialize tracking spreadsheet
      const trackingSheet = this.createTrackingSpreadsheet(rootFolder.getId());

      // Set up automation triggers
      this.setupAutomationTriggers();

      const result = {
        rootFolder: rootFolder,
        folderStructure: folderStructure,
        systemFolders: systemFolders,
        trackingSheet: trackingSheet,
        initialized: new Date()
      };

      console.log('✅ Document Management System initialized successfully');
      return result;

    } catch (error) {
      console.error(`❌ Initialization failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Submit document for approval workflow
   * @param {string} fileId - Document file ID
   * @param {Object} metadata - Document metadata
   * @return {Object} Submission result
   */
  submitDocumentForApproval(fileId, metadata) {
    try {
      const {
        department,
        documentType,
        title,
        description,
        submitter,
        approvers = [],
        priority = 'normal',
        dueDate = null
      } = metadata;

      console.log(`📋 Submitting document for approval: ${title}`);

      // Move document to appropriate Draft folder
      const targetFolder = this.getApprovalFolder(department, documentType, 'Draft');
      this.driveManager.moveFile(fileId, targetFolder.getId());

      // Create approval record
      const approvalRecord = {
        documentId: fileId,
        title: title,
        department: department,
        documentType: documentType,
        submitter: submitter,
        submitDate: new Date(),
        currentLevel: 'Draft',
        approvers: approvers,
        priority: priority,
        dueDate: dueDate,
        status: 'pending',
        approvalHistory: [{
          level: 'Draft',
          action: 'submitted',
          user: submitter,
          date: new Date(),
          comments: 'Initial submission'
        }]
      };

      // Add to tracking spreadsheet
      this.addApprovalRecord(approvalRecord);

      // Send notifications
      this.sendApprovalNotifications(approvalRecord, 'submitted');

      console.log(`✅ Document submitted: ${approvalRecord.documentId}`);
      return approvalRecord;

    } catch (error) {
      console.error(`❌ Document submission failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Process approval action
   * @param {string} documentId - Document ID
   * @param {Object} approvalAction - Approval action details
   * @return {Object} Processing result
   */
  processApproval(documentId, approvalAction) {
    try {
      const {
        approver,
        action, // 'approve', 'reject', 'request_changes'
        comments = '',
        nextLevel = null
      } = approvalAction;

      console.log(`⚖️ Processing approval: ${action} by ${approver}`);

      // Get current approval record
      const record = this.getApprovalRecord(documentId);
      if (!record) {
        throw new Error('Approval record not found');
      }

      // Update approval history
      record.approvalHistory.push({
        level: record.currentLevel,
        action: action,
        user: approver,
        date: new Date(),
        comments: comments
      });

      let newLevel = record.currentLevel;

      if (action === 'approve') {
        // Determine next level
        const levels = ['Draft', 'Review', 'Approved', 'Published'];
        const currentIndex = levels.indexOf(record.currentLevel);
        
        if (currentIndex < levels.length - 1) {
          newLevel = nextLevel || levels[currentIndex + 1];
        }
      } else if (action === 'reject' || action === 'request_changes') {
        newLevel = 'Draft';
        record.status = action === 'reject' ? 'rejected' : 'changes_requested';
      }

      // Move document to appropriate folder
      if (newLevel !== record.currentLevel) {
        const targetFolder = this.getApprovalFolder(
          record.department, 
          record.documentType, 
          newLevel
        );
        this.driveManager.moveFile(documentId, targetFolder.getId());
      }

      // Update record
      record.currentLevel = newLevel;
      record.lastUpdate = new Date();
      
      if (newLevel === 'Published') {
        record.status = 'completed';
        record.publishDate = new Date();
      }

      // Update tracking spreadsheet
      this.updateApprovalRecord(record);

      // Send notifications
      this.sendApprovalNotifications(record, action);

      console.log(`✅ Approval processed: ${action} → ${newLevel}`);
      return record;

    } catch (error) {
      console.error(`❌ Approval processing failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Generate comprehensive management reports
   * @param {Object} reportOptions - Report configuration
   * @return {Object} Management reports
   */
  generateManagementReports(reportOptions = {}) {
    try {
      const {
        period = 'month',
        includeDepartmentBreakdown = true,
        includePerformanceMetrics = true,
        exportToSheets = false
      } = reportOptions;

      console.log('📊 Generating management reports...');

      const reports = {
        summary: this.generateSummaryReport(period),
        departmental: includeDepartmentBreakdown ? this.generateDepartmentalReport(period) : null,
        performance: includePerformanceMetrics ? this.generatePerformanceReport(period) : null,
        workflow: this.generateWorkflowReport(period)
      };

      if (exportToSheets) {
        reports.exportUrl = this.exportManagementReports(reports);
      }

      console.log('✅ Management reports generated');
      return reports;

    } catch (error) {
      console.error(`❌ Report generation failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Automated document lifecycle management
   * @param {Object} lifecycleOptions - Lifecycle options
   * @return {Object} Lifecycle management result
   */
  manageDocumentLifecycle(lifecycleOptions = {}) {
    try {
      const {
        archiveAfterDays = 365,
        deleteAfterDays = 2555, // 7 years
        notifyBeforeArchive = 30,
        includeUsageAnalytics = true
      } = lifecycleOptions;

      console.log('🔄 Managing document lifecycle...');

      const results = {
        reviewed: 0,
        archived: 0,
        deleted: 0,
        notifications: 0,
        errors: []
      };

      // Find documents for lifecycle actions
      const documents = this.getAllDocuments();
      
      documents.forEach(doc => {
        try {
          results.reviewed++;
          
          const daysSinceCreation = this.daysBetween(new Date(doc.created), new Date());
          const daysSinceModified = this.daysBetween(new Date(doc.modified), new Date());
          
          // Check for deletion
          if (daysSinceModified >= deleteAfterDays) {
            this.driveManager.deleteFile(doc.id, true); // Permanent delete
            results.deleted++;
            console.log(`🗑️ Deleted old document: ${doc.name}`);
          }
          // Check for archival
          else if (daysSinceModified >= archiveAfterDays) {
            this.moveToArchive(doc);
            results.archived++;
            console.log(`📦 Archived document: ${doc.name}`);
          }
          // Check for pre-archive notification
          else if (daysSinceModified >= (archiveAfterDays - notifyBeforeArchive)) {
            this.sendArchivalNotification(doc, archiveAfterDays - daysSinceModified);
            results.notifications++;
          }
          
        } catch (error) {
          results.errors.push({
            document: doc.name,
            error: error.message
          });
        }
      });

      console.log(`✅ Lifecycle management completed: ${results.archived} archived, ${results.deleted} deleted`);
      return results;

    } catch (error) {
      console.error(`❌ Lifecycle management failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Smart document organization
   * @param {Object} organizationOptions - Organization options
   * @return {Object} Organization result
   */
  organizeDocuments(organizationOptions = {}) {
    try {
      const {
        useAI = false,
        autoClassify = true,
        createBackups = true,
        dryRun = false
      } = organizationOptions;

      console.log('🎯 Organizing documents...');

      const results = {
        processed: 0,
        moved: 0,
        classified: 0,
        duplicates: 0,
        errors: []
      };

      // Get all unorganized documents
      const documents = this.getUnorganizedDocuments();

      // Process each document
      documents.forEach(doc => {
        try {
          results.processed++;
          
          if (autoClassify) {
            const classification = this.classifyDocument(doc, useAI);
            
            if (classification.confidence > 0.7) {
              const targetFolder = this.getApprovalFolder(
                classification.department,
                classification.documentType,
                'Draft'
              );
              
              if (!dryRun) {
                this.driveManager.moveFile(doc.id, targetFolder.getId());
                results.moved++;
              }
              
              results.classified++;
              console.log(`📂 Classified: ${doc.name} → ${classification.department}/${classification.documentType}`);
            }
          }
          
          // Check for duplicates
          const duplicates = this.findDocumentDuplicates(doc);
          if (duplicates.length > 0) {
            results.duplicates++;
            this.handleDuplicates(doc, duplicates, { createBackups, dryRun });
          }
          
        } catch (error) {
          results.errors.push({
            document: doc.name,
            error: error.message
          });
        }
      });

      console.log(`✅ Document organization completed: ${results.moved} moved, ${results.classified} classified`);
      return results;

    } catch (error) {
      console.error(`❌ Document organization failed: ${error.message}`);
      throw error;
    }
  }

  // Helper methods
  loadSettings() {
    try {
      const settingsSheet = this.configSheet.getSheetByName('Settings') || 
                           this.configSheet.insertSheet('Settings');
      // Load configuration settings
      return {};
    } catch (error) {
      console.warn('Using default settings');
      return {};
    }
  }

  getApprovalFolder(department, documentType, level) {
    // Implementation to get specific approval folder
    return FOLDER_MANAGER.findFolder(`${department}/${documentType}/${level}`);
  }

  createTrackingSpreadsheet(parentFolderId) {
    const ss = SpreadsheetApp.create('Document Tracking System');
    const file = DriveApp.getFileById(ss.getId());
    file.moveTo(DriveApp.getFolderById(parentFolderId));
    
    // Set up tracking sheets
    this.setupTrackingSheets(ss);
    
    return ss;
  }

  setupAutomationTriggers() {
    // Set up time-based triggers for automated tasks
    ScriptApp.newTrigger('automatedLifecycleManagement')
      .timeBased()
      .everyDays(1)
      .create();
  }

  daysBetween(date1, date2) {
    return Math.ceil(Math.abs(date2 - date1) / (1000 * 60 * 60 * 24));
  }
}

/**
 * Automated lifecycle management function (called by trigger)
 */
function automatedLifecycleManagement() {
  // This would be called by the automated trigger
  console.log('Running automated lifecycle management...');
}
```

### Project 2: Intelligent File Synchronization System

```javascript
/**
 * Intelligent File Synchronization System
 * Advanced synchronization between Drive locations with conflict resolution
 * @class
 */
class FileSynchronizationSystem {
  constructor(configOptions = {}) {
    this.config = DRIVE_CONFIG;
    this.driveManager = DRIVE_MANAGER;
    this.syncHistory = new Map();
    this.conflictLog = [];
    this.syncOperations = new Map();
  }

  /**
   * Set up synchronization between two folders
   * @param {Object} syncConfig - Synchronization configuration
   * @return {Object} Sync setup result
   */
  setupSynchronization(syncConfig) {
    try {
      const {
        sourceFolderId,
        targetFolderId,
        syncMode = 'bidirectional', // unidirectional, bidirectional
        conflictResolution = 'prompt', // overwrite, skip, prompt, merge
        includeSubfolders = true,
        fileFilters = {},
        schedule = null, // For scheduled sync
        realTime = false // For real-time sync
      } = syncConfig;

      const syncId = `sync_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

      console.log(`🔄 Setting up synchronization: ${syncId}`);

      // Validate folders
      const sourceFolder = this.driveManager.getFolderById(sourceFolderId);
      const targetFolder = this.driveManager.getFolderById(targetFolderId);

      const syncOperation = {
        id: syncId,
        sourceFolderId: sourceFolderId,
        targetFolderId: targetFolderId,
        sourceFolder: sourceFolder,
        targetFolder: targetFolder,
        syncMode: syncMode,
        conflictResolution: conflictResolution,
        includeSubfolders: includeSubfolders,
        fileFilters: fileFilters,
        schedule: schedule,
        realTime: realTime,
        created: new Date(),
        lastSync: null,
        status: 'active'
      };

      // Store sync configuration
      this.syncOperations.set(syncId, syncOperation);

      // Set up real-time monitoring if requested
      if (realTime) {
        this.setupRealTimeSync(syncOperation);
      }

      // Set up scheduled sync if requested
      if (schedule) {
        this.setupScheduledSync(syncOperation);
      }

      console.log(`✅ Synchronization setup completed: ${syncId}`);
      return syncOperation;

    } catch (error) {
      console.error(`❌ Sync setup failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Execute synchronization
   * @param {string} syncId - Synchronization ID
   * @param {Object} options - Sync options
   * @return {Object} Sync result
   */
  executeSynchronization(syncId, options = {}) {
    try {
      const {
        dryRun = false,
        verbose = false,
        includeAnalysis = true
      } = options;

      console.log(`🔄 Executing synchronization: ${syncId}`);

      const syncOp = this.syncOperations.get(syncId);
      if (!syncOp) {
        throw new Error(`Sync operation not found: ${syncId}`);
      }

      const syncResult = {
        syncId: syncId,
        startTime: new Date(),
        endTime: null,
        mode: syncOp.syncMode,
        dryRun: dryRun,
        sourceFiles: 0,
        targetFiles: 0,
        created: 0,
        updated: 0,
        deleted: 0,
        conflicts: 0,
        errors: 0,
        operations: [],
        conflictDetails: [],
        errorDetails: [],
        analysis: null
      };

      // Analyze folders
      const sourceAnalysis = this.analyzeFolderContents(syncOp.sourceFolderId, syncOp);
      const targetAnalysis = this.analyzeFolderContents(syncOp.targetFolderId, syncOp);

      syncResult.sourceFiles = sourceAnalysis.files.length;
      syncResult.targetFiles = targetAnalysis.files.length;

      // Compare and create sync plan
      const syncPlan = this.createSyncPlan(sourceAnalysis, targetAnalysis, syncOp);

      if (verbose) {
        console.log(`📋 Sync plan: ${syncPlan.operations.length} operations`);
      }

      // Execute sync operations
      for (const operation of syncPlan.operations) {
        try {
          const result = this.executeOperation(operation, dryRun, syncOp);
          syncResult.operations.push(result);

          // Update counters
          switch (operation.action) {
            case 'create':
              syncResult.created++;
              break;
            case 'update':
              syncResult.updated++;
              break;
            case 'delete':
              syncResult.deleted++;
              break;
            case 'conflict':
              syncResult.conflicts++;
              syncResult.conflictDetails.push(result);
              break;
          }

          if (verbose) {
            console.log(`  ✓ ${operation.action}: ${operation.file.name}`);
          }

        } catch (error) {
          syncResult.errors++;
          syncResult.errorDetails.push({
            operation: operation,
            error: error.message
          });

          if (verbose) {
            console.error(`  ✗ Error: ${operation.file.name} - ${error.message}`);
          }
        }
      }

      syncResult.endTime = new Date();
      syncResult.duration = syncResult.endTime - syncResult.startTime;

      // Update sync operation
      syncOp.lastSync = syncResult.endTime;
      syncOp.lastResult = syncResult;

      // Store sync history
      if (!this.syncHistory.has(syncId)) {
        this.syncHistory.set(syncId, []);
      }
      this.syncHistory.get(syncId).push(syncResult);

      // Generate analysis if requested
      if (includeAnalysis) {
        syncResult.analysis = this.generateSyncAnalysis(syncResult, syncOp);
      }

      console.log(`✅ Synchronization completed: ${syncResult.created} created, ${syncResult.updated} updated, ${syncResult.conflicts} conflicts`);
      return syncResult;

    } catch (error) {
      console.error(`❌ Synchronization failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Handle synchronization conflicts
   * @param {Object} conflict - Conflict details
   * @param {Object} syncOperation - Sync operation
   * @return {Object} Resolution result
   */
  handleConflict(conflict, syncOperation) {
    const {
      sourceFile,
      targetFile,
      conflictType,
      resolution = syncOperation.conflictResolution
    } = conflict;

    console.log(`⚔️ Handling conflict: ${conflictType} for ${sourceFile.name}`);

    switch (resolution) {
      case 'overwrite':
        return this.resolveByOverwrite(sourceFile, targetFile, syncOperation);
        
      case 'skip':
        return { action: 'skipped', reason: 'User chose to skip conflict' };
        
      case 'merge':
        return this.resolveByMerge(sourceFile, targetFile, syncOperation);
        
      case 'prompt':
        return this.promptForResolution(conflict, syncOperation);
        
      case 'newer':
        return this.resolveByNewer(sourceFile, targetFile, syncOperation);
        
      case 'larger':
        return this.resolveByLarger(sourceFile, targetFile, syncOperation);
        
      default:
        throw new Error(`Unknown conflict resolution: ${resolution}`);
    }
  }

  /**
   * Monitor folder changes in real-time
   * @param {Object} syncOperation - Sync operation to monitor
   */
  setupRealTimeSync(syncOperation) {
    console.log(`👁️ Setting up real-time monitoring for: ${syncOperation.id}`);
    
    // This would typically use webhooks or periodic checking
    // For Google Apps Script, we'll use time-based triggers
    
    const trigger = ScriptApp.newTrigger(`realTimeSync_${syncOperation.id}`)
      .timeBased()
      .everyMinutes(5) // Check every 5 minutes
      .create();
      
    syncOperation.realTimeTrigger = trigger;
  }

  /**
   * Set up scheduled synchronization
   * @param {Object} syncOperation - Sync operation
   */
  setupScheduledSync(syncOperation) {
    console.log(`⏰ Setting up scheduled sync: ${syncOperation.schedule}`);
    
    let trigger;
    
    switch (syncOperation.schedule) {
      case 'hourly':
        trigger = ScriptApp.newTrigger(`scheduledSync_${syncOperation.id}`)
          .timeBased()
          .everyHours(1)
          .create();
        break;
        
      case 'daily':
        trigger = ScriptApp.newTrigger(`scheduledSync_${syncOperation.id}`)
          .timeBased()
          .everyDays(1)
          .create();
        break;
        
      case 'weekly':
        trigger = ScriptApp.newTrigger(`scheduledSync_${syncOperation.id}`)
          .timeBased()
          .everyWeeks(1)
          .create();
        break;
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
