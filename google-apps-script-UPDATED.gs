/**
 * Google Apps Script - Updated Version with Folder Support
 * Deploy sebagai Web App dengan akses "Anyone"
 */

function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents);

    // Decode base64
    var fileBlob = Utilities.newBlob(
      Utilities.base64Decode(data.data),
      data.mimeType,
      data.fileName
    );

    // Tentukan folder tujuan
    var folder;
    if (data.folderId) {
      // Upload ke folder spesifik
      folder = DriveApp.getFolderById(data.folderId);
    } else {
      // Default folder (jika tidak ada folderId)
      folder = DriveApp.getRootFolder();
    }

    // Upload file
    var file = folder.createFile(fileBlob);

    return ContentService.createTextOutput(JSON.stringify({
      success: true,
      fileId: file.getId(),
      fileUrl: file.getUrl(),
      fileName: file.getName(),
      folderId: folder.getId(),
      folderName: folder.getName()
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      success: false,
      error: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

function doGet(e) {
  var action = e.parameter.action;

  if (action === 'list') {
    return listFilesInFolder(e.parameter.folderId);
  } else if (action === 'listNested') {
    // New: Get entire nested structure in ONE call (much faster)
    var depth = parseInt(e.parameter.depth) || 2;
    return listNestedStructure(e.parameter.folderId, depth);
  } else if (action === 'delete') {
    return deleteFile(e.parameter.id);
  }

  return ContentService.createTextOutput(JSON.stringify({
    success: false,
    error: 'Invalid action'
  })).setMimeType(ContentService.MimeType.JSON);
}

function listFilesInFolder(folderId) {
  try {
    if (!folderId) {
      throw new Error('Argumen tidak valid: folderId diperlukan');
    }

    var folder = DriveApp.getFolderById(folderId);
    var fileList = [];

    // Include subfolders first (needed for findSubfolderByName in PHP)
    var subfolders = folder.getFolders();
    while (subfolders.hasNext()) {
      var subfolder = subfolders.next();
      fileList.push({
        id: subfolder.getId(),
        name: subfolder.getName(),
        url: subfolder.getUrl(),
        mimeType: 'application/vnd.google-apps.folder',
        size: 0,
        createdDate: subfolder.getDateCreated().toISOString(),
        modifiedDate: subfolder.getLastUpdated().toISOString()
      });
    }

    // Include files
    var files = folder.getFiles();
    while (files.hasNext()) {
      var file = files.next();
      fileList.push({
        id: file.getId(),
        name: file.getName(),
        url: file.getUrl(),
        mimeType: file.getMimeType(),
        size: file.getSize(),
        createdDate: file.getDateCreated().toISOString(),
        modifiedDate: file.getLastUpdated().toISOString()
      });
    }

    return ContentService.createTextOutput(JSON.stringify({
      success: true,
      files: fileList,
      count: fileList.length,
      folderId: folderId,
      folderName: folder.getName()
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      success: false,
      error: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

function deleteFile(fileId) {
  try {
    if (!fileId) {
      throw new Error('Argumen tidak valid: fileId diperlukan');
    }

    var file = DriveApp.getFileById(fileId);
    file.setTrashed(true);

    return ContentService.createTextOutput(JSON.stringify({
      success: true,
      message: 'File berhasil dihapus',
      fileId: fileId
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      success: false,
      error: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * NEW: Get entire nested folder structure in a single API call
 * This dramatically reduces latency by eliminating multiple round-trips
 * 
 * @param {string} folderId - Root folder ID
 * @param {number} depth - How many levels deep to traverse (default 2)
 * @return {TextOutput} JSON response with nested structure
 */
function listNestedStructure(folderId, depth) {
  try {
    if (!folderId) {
      throw new Error('Argumen tidak valid: folderId diperlukan');
    }
    
    depth = depth || 2; // Default: Period -> Prodi -> Files
    var folder = DriveApp.getFolderById(folderId);
    var structure = getNestedFolderContents(folder, depth);
    
    return ContentService.createTextOutput(JSON.stringify({
      success: true,
      folderId: folderId,
      folderName: folder.getName(),
      structure: structure
    })).setMimeType(ContentService.MimeType.JSON);
    
  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      success: false,
      error: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * Recursively get folder contents
 * 
 * @param {Folder} folder - Google Drive Folder object
 * @param {number} depth - Remaining depth to traverse
 * @return {Array} Array of items with nested children for folders
 */
function getNestedFolderContents(folder, depth) {
  var items = [];
  
  // Get subfolders
  var subfolders = folder.getFolders();
  while (subfolders.hasNext()) {
    var subfolder = subfolders.next();
    var folderItem = {
      id: subfolder.getId(),
      name: subfolder.getName(),
      url: subfolder.getUrl(),
      mimeType: 'application/vnd.google-apps.folder',
      createdDate: subfolder.getDateCreated().toISOString(),
      modifiedDate: subfolder.getLastUpdated().toISOString()
    };
    
    // Recursively get children if depth allows
    if (depth > 0) {
      folderItem.children = getNestedFolderContents(subfolder, depth - 1);
    }
    
    items.push(folderItem);
  }
  
  // Get files
  var files = folder.getFiles();
  while (files.hasNext()) {
    var file = files.next();
    items.push({
      id: file.getId(),
      name: file.getName(),
      url: file.getUrl(),
      mimeType: file.getMimeType(),
      size: file.getSize(),
      createdDate: file.getDateCreated().toISOString(),
      modifiedDate: file.getLastUpdated().toISOString()
    });
  }
  
  return items;
}
