## Table of Contents

1. [Getting Started](#getting-started)
2. [File and Folder Operations](#file-and-folder-operations)
3. [File Management Functions](#file-management-functions)
4. [Folder Management Functions](#folder-management-functions)
5. [Search and Query Functions](#search-and-query-functions)
6. [File Sharing and Permissions](#file-sharing-and-permissions)
7. [Advanced Operations](#advanced-operations)

## Getting Started

First, create a new Google Apps Script project from script.google.com.

```javascript
// Include this at the top of your script
function onOpen() {
  const ui = SpreadsheetApp.getUi();
  ui.createMenu('Drive Tools')
    .addItem('Create Sample Folder', 'createSampleFolder')
    .addItem('List All Files', 'listAllFiles')
    .addToUi();
}
```

## File and Folder Operations

### Basic File Creation Function

```javascript
/**
 * Creates a new file in Google Drive with optional parameters
 * @param {string} fileName - Name of the file to create
 * @param {string} content - Content for the file (optional)
 * @param {string} mimeType - MIME type of the file (optional, default: text/plain)
 * @param {string} folderId - ID of parent folder (optional)
 * @return {GoogleAppsScript.Drive.File} Created file object
 */
function createFile(fileName, content = '', mimeType = 'text/plain', folderId = null) {
  try {
    const options = {
      name: fileName,
      mimeType: mimeType
    };
    
    if (folderId) {
      options.parents = [folderId];
    }
    
    const file = DriveApp.createFile(
      ContentService.createTextOutput(content).setMimeType(mimeType),
      fileName,
      folderId ? [folderId] : undefined
    );
    
    return file;
  } catch (error) {
    console.error('Error creating file:', error);
    throw new Error(`Failed to create file: ${error.message}`);
  }
}
```

### Basic Folder Creation Function

```javascript
/**
 * Creates a new folder in Google Drive with optional parameters
 * @param {string} folderName - Name of the folder to create
 * @param {string} parentFolderId - ID of parent folder (optional)
 * @return {GoogleAppsScript.Drive.Folder} Created folder object
 */
function createFolder(folderName, parentFolderId = null) {
  try {
    let folder;
    
    if (parentFolderId) {
      const parent = DriveApp.getFolderById(parentFolderId);
      folder = parent.createFolder(folderName);
    } else {
      folder = DriveApp.createFolder(folderName);
    }
    
    return folder;
  } catch (error) {
    console.error('Error creating folder:', error);
    throw new Error(`Failed to create folder: ${error.message}`);
  }
}
```

## File Management Functions

### Upload Spreadsheet with Optional Parameters

```javascript
/**
 * Creates a Google Sheet in Drive
 * @param {string} fileName - Name of the spreadsheet
 * @param {string} parentFolderId - ID of parent folder (optional)
 * @param {number} rows - Number of rows to initialize (optional, default: 1000)
 * @param {number} columns - Number of columns to initialize (optional, default: 26)
 * @return {GoogleAppsScript.Spreadsheet.Spreadsheet} Created spreadsheet
 */
function createSpreadsheet(fileName, parentFolderId = null, rows = 1000, columns = 26) {
  try {
    const options = {
      name: fileName,
      mimeType: MimeType.GOOGLE_SHEETS
    };
    
    if (parentFolderId) {
      options.parents = [parentFolderId];
    }
    
    const ss = SpreadsheetApp.create(fileName);
    
    // Optionally set up initial rows and columns
    const sheet = ss.getActiveSheet();
    sheet.getRange(1, 1, rows, columns).activate();
    
    return ss;
  } catch (error) {
    console.error('Error creating spreadsheet:', error);
    throw new Error(`Failed to create spreadsheet: ${error.message}`);
  }
}
```

### Upload Document with Content

```javascript
/**
 * Creates a Google Doc in Drive with content
 * @param {string} fileName - Name of the document
 * @param {string} content - Text content for the document (optional)
 * @param {string} parentFolderId - ID of parent folder (optional)
 * @return {GoogleAppsScript.Document.Document} Created document
 */
function createDocument(fileName, content = '', parentFolderId = null) {
  try {
    const doc = DocumentApp.create(fileName);
    
    if (content) {
      const body = doc.getBody();
      body.clear();
      body.setText(content);
    }
    
    // Move to specified folder if provided
    if (parentFolderId) {
      const file = DriveApp.getFileById(doc.getId());
      const parentFolder = DriveApp.getFolderById(parentFolderId);
      parentFolder.addFile(file);
      
      // Remove from root
      const root = DriveApp.getRootFolder();
      const filesInRoot = root.getFilesByName(fileName);
      
      if (filesInRoot.hasNext()) {
        const fileToRemove = filesInRoot.next();
        if (fileToRemove.getId() === file.getId()) {
          root.removeFile(fileToRemove);
        }
      }
    }
    
    return doc;
  } catch (error) {
    console.error('Error creating document:', error);
    throw new Error(`Failed to create document: ${error.message}`);
  }
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
    const newFile = sourceFile.makeCopy();
    
    // Rename if specified
    if (newName) {
      newFile.setName(newName);
    }
    
    // Move to destination folder if specified
    if (destinationFolderId) {
      const destinationFolder = DriveApp.getFolderById(destinationFolderId);
      const parentFolders = sourceFile.getParents();
      
      while (parentFolders.hasNext()) {
        const folder = parentFolders.next();
        folder.removeFile(sourceFile);
        destinationFolder.addFile(newFile);
      }
    }
    
    return newFile;
  } catch (error) {
    console.error('Error copying file:', error);
    throw new Error(`Failed to copy file: ${error.message}`);
  }
}
```

## Folder Management Functions

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
    let query = `name contains '${pattern}' and trashed = false`;
    
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
      query += ` and name contains '${nameContains}'`;
    }
    
    if (mimeType) {
      query += ` and mimeType = '${mimeType}'`;
    }
    
    if (parentFolderId) {
      query += ` and '${parentFolderId}' in parents`;
    }
    
    if (createdAfter) {
      const dateStr = Utilities.formatDate(createdAfter, Session.getScriptTimeZone(), 'yyyy-MM-dd');
      query += ` and createdTime > '${dateStr}T00:00:00'`;
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
    if (role === 'writer') {
      file.setSharingUserAccess(emailAddress, DriveApp.Access.WRITE);
    } else if (role === 'commenter') {
      file.setSharingUserAccess(emailAddress, DriveApp.Access.COMMENT);
    } else {
      file.setSharingUserAccess(emailAddress, DriveApp.Access.READ);
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
