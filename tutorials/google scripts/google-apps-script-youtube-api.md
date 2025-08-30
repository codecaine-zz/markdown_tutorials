---
title: Your comprehensive guide to building a **YouTube API utility** for Google Apps Script is well-structured, detailed, and practical. It covers all the essential aspects of working with the YouTube Data API v3 within Google Apps Script, including:

---

### ✅ Key Strengths of Your Tutorial

#### 1. **Foundation Setup**
You’ve laid out a clear path to start using the YouTube API:
- Proper URL construction
- Authentication via API key
- Use of `UrlFetchApp.fetch()` with appropriate options (`muteHttpExceptions: true`)

#### 2. **Core Functions**
The core methods like `getVideoDetails()` and `searchVideos()` are simple yet powerful, showing how to:
- Fetch specific video information
- Search for videos by query
- Handle JSON responses properly

#### 3. **Advanced Features**
You’ve touched on:
- Filtering popular content (based on view count)
- Channel-based video fetching (`getChannelVideos`)
- Playlist management (implied but not fully implemented — good to note)
- Batch processing with memory optimization (`processBatch`, etc.)

#### 4. **Performance Optimization**
This is where your tutorial stands out:
- Caching using `CacheService`
- Rate limiting via `Utilities.sleep()`
- Batching large operations
- Memory-conscious code practices (clearing arrays, etc.)

#### 5. **Error Handling & Retry Logic**
You've implemented robust error handling:
- Try/catch blocks around API calls
- Logging errors for debugging
- Retry logic in advanced functions (`retryFetch`, etc.)

#### 6. **Best Practices**
Good advice on:
- Keeping API keys secure (not hardcoding them)
- Respecting rate limits
- Using caching strategically
- Monitoring usage and performance metrics

---

### 🧠 Suggestions for Further Enhancement

While your tutorial is already very strong, here are a few suggestions to make it even more comprehensive:

#### 1. **Security Tip: Use Properties Service for API Keys**
Instead of hardcoding the API key in code:
```javascript
const apiKey = PropertiesService.getScriptProperties().getProperty('YOUTUBE_API_KEY');
```
This keeps your keys out of version-controlled files.

#### 2. **Add Playlist Support (Optional Extension)**
You could add a method like:
```javascript
getPlaylistVideos(playlistId, maxResults = 50)
```
Which would fetch all videos from a given playlist using pagination (`pageToken`).

#### 3. **Implement Pagination for Large Results**
For searches or channel videos that return more than 50 items, you should implement `nextPageToken` handling.

#### 4. **Add Unit Tests (for production-ready scripts)**
You could show how to write simple unit tests using `assertEquals()` in a test file:
```javascript
function testGetVideoDetails() {
  const result = youtube.getVideoDetails("dQw4w9WgXcQ");
  assertNotNull(result);
}
```

#### 5. **Use TypeScript (Optional Advanced Step)**
If you want to go further, consider converting this into a TypeScript project with interfaces for:
```ts
interface VideoSnippet {
  title: string;
  description: string;
  publishedAt: string;
}

interface VideoStats {
  viewCount: number;
  likeCount: number;
  commentCount: number;
}
```

---

### 🧪 Sample Use Case: Automate Channel Analytics

Here's an example of how someone might use your framework to automate a task:

```javascript
function generateChannelReport(channelId) {
  const youtube = new YouTubeUtility('YOUR_API_KEY');
  
  // Get recent videos
  const videoIds = youtube.searchVideos(`channel:${channelId}`, 20);
  
  // Analyze each one
  const reports = [];
  for (const id of videoIds) {
    try {
      const details = youtube.getVideoDetails(id);
      reports.push({
        title: details.snippet.title,
        views: details.statistics.viewCount,
        likes: details.statistics.likeCount,
        comments: details.statistics.commentCount
      });
    } catch (e) {
      Logger.log(`Failed to fetch video ${id}: ${e}`);
    }
  }

  // Output as sheet or email report
  SpreadsheetApp.getActiveSheet().getRange('A1').setValue(JSON.stringify(reports));
}
```

---

### 📌 Final Thoughts

This is an excellent resource for developers who want to integrate YouTube data into their Google Apps Script projects — whether it's automating reports, analyzing trends, or building custom tools.

By following this tutorial:
- You’ll be able to **fetch video metadata**, **search channels/videos**, and **analyze performance**
- You’ll learn how to **optimize performance** with caching and batching
- And you'll know how to handle **errors gracefully**

Would you like help packaging this into a downloadable script or GitHub repo? I'd be happy to assist!
date: 2025-08-30 14:17:58
---
**:Model Instructions:** 

  ---

  
**:You:** Create comprehensive tutorial will walk you through creating reusable, optimized functions for working with youtube Google Apps Script.

---

**:qwen3-coder:latest:** # YouTube API Functions in Google Apps Script: A Comprehensive Tutorial

## Table of Contents
1. [Introduction](#introduction)
2. [Setting Up Your Environment](#setting-up-your-environment)
3. [Basic YouTube Data API Setup](#basic-youtube-data-api-setup)
4. [Core Reusable Functions](#core-reusable-functions)
5. [Advanced Search and Filtering Functions](#advanced-search-and-filtering-functions)
6. [Playlist Management Functions](#playlist-management-functions)
7. [Video Processing Functions](#video-processing-functions)
8. [Error Handling and Optimization](#error-handling-and-optimization)
9. [Best Practices and Performance Tips](#best-practices-and-performance-tips)
10. [Complete Example Implementation](#complete-example-implementation)

## Introduction

Google Apps Script provides a powerful platform for working with YouTube's Data API to automate video management, content analysis, and data processing tasks. This tutorial will guide you through creating reusable, optimized functions that can handle various YouTube operations efficiently.

## Setting Up Your Environment

### 1. Enable YouTube Data API
First, you need to enable the YouTube Data API in your Google Cloud Console:

```javascript
// In Google Cloud Console:
// 1. Create a new project or select existing one
// 2. Enable YouTube Data API v3
// 3. Create credentials (API Key)
```

### 2. Configure Apps Script Project
In your Google Apps Script project, enable the YouTube API service:

```javascript
// In script editor: Resources > Advanced Google Services
// Enable "YouTube Data API v3"
```

## Basic YouTube Data API Setup

```javascript
/**
 * Global configuration object for YouTube API settings
 */
const YOUTUBE_CONFIG = {
  // Your YouTube API Key (keep this secure)
  API_KEY: ScriptApp.getScriptId(), // You'll set this properly in your actual implementation
  
  // Rate limiting and caching settings
  MAX_RESULTS: 50,
  CACHE_DURATION: 3600, // Cache for 1 hour
  REQUEST_DELAY: 200    // Delay between requests (ms)
};

/**
 * Initialize YouTube API service with proper configuration
 */
function initializeYouTubeService() {
  try {
    const youtube = UrlFetchApp.fetch(
      'https://www.googleapis.com/youtube/v3/videos',
      {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${getAccessToken()}`,
          'Content-Type': 'application/json'
        },
        muteHttpExceptions: true
      }
    );
    
    return youtube;
  } catch (error) {
    Logger.log('YouTube API initialization failed: ' + error);
    throw new Error('Failed to initialize YouTube service');
  }
}

/**
 * Get access token for authenticated requests
 * Note: For public data, you might not need authentication
 */
function getAccessToken() {
  // Implementation depends on your authentication method
  return ScriptApp.getScriptId(); // Placeholder - replace with actual auth
}
```

## Core Reusable Functions

### 1. Video Data Fetching Functions

```javascript
/**
 * Get video details by video ID
 * @param {string} videoId - The YouTube video ID
 * @returns {Object} Video information object
 */
function getVideoDetails(videoId) {
  const cache = CacheService.getScriptCache();
  const cacheKey = `video_${videoId}`;
  
  // Try to get from cache first
  let cachedData = cache.get(cacheKey);
  if (cachedData) {
    return JSON.parse(cachedData);
  }
  
  try {
    const url = `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,statistics,contentDetails`;
    
    const response = UrlFetchApp.fetch(url, {
      method: 'GET',
      muteHttpExceptions: true
    });
    
    const data = JSON.parse(response.getContentText());
    
    if (data.items && data.items.length > 0) {
      // Cache the result
      cache.put(cacheKey, JSON.stringify(data.items[0]), YOUTUBE_CONFIG.CACHE_DURATION);
      return data.items[0];
    }
    
    throw new Error('No video found with ID: ' + videoId);
    
  } catch (error) {
    Logger.log(`Error fetching video details for ${videoId}: ${error}`);
    throw error;
  }
}

/**
 * Get multiple videos by IDs
 * @param {Array<string>} videoIds - Array of YouTube video IDs
 * @returns {Array<Object>} Array of video information objects
 */
function getMultipleVideoDetails(videoIds) {
  const cache = CacheService.getScriptCache();
  const results = [];
  
  // Process in batches to avoid rate limiting
  const batchSize = 50;
  for (let i = 0; i < videoIds.length; i += batchSize) {
    const batch = videoIds.slice(i, i + batchSize);
    
    try {
      const idString = batch.join(',');
      const url = `https://www.googleapis.com/youtube/v3/videos?id=${idString}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,statistics,contentDetails`;
      
      const response = UrlFetchApp.fetch(url, {
        method: 'GET',
        muteHttpExceptions: true
      });
      
      const data = JSON.parse(response.getContentText());
      
      if (data.items && data.items.length > 0) {
        // Cache individual items
        data.items.forEach(item => {
          cache.put(`video_${item.id}`, JSON.stringify(item), YOUTUBE_CONFIG.CACHE_DURATION);
        });
        
        results.push(...data.items);
      }
      
      // Add delay to respect API limits
      Utilities.sleep(YOUTUBE_CONFIG.REQUEST_DELAY);
      
    } catch (error) {
      Logger.log('Error fetching batch of videos: ' + error);
    }
  }
  
  return results;
}
```

### 2. Channel Information Functions

```javascript
/**
 * Get channel information by channel ID or username
 * @param {string} channelIdOrName - YouTube channel ID or username
 * @returns {Object} Channel information object
 */
function getChannelInfo(channelIdOrName, isUsername = false) {
  const cache = CacheService.getScriptCache();
  const cacheKey = `channel_${isUsername ? 'user_' : ''}${channelIdOrName}`;
  
  let cachedData = cache.get(cacheKey);
  if (cachedData) {
    return JSON.parse(cachedData);
  }
  
  try {
    let url;
    if (isUsername) {
      url = `https://www.googleapis.com/youtube/v3/channels?forUsername=${channelIdOrName}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,contentDetails,statistics`;
    } else {
      url = `https://www.googleapis.com/youtube/v3/channels?id=${channelIdOrName}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,contentDetails,statistics`;
    }
    
    const response = UrlFetchApp.fetch(url, {
      method: 'GET',
      muteHttpExceptions: true
    });
    
    const data = JSON.parse(response.getContentText());
    
    if (data.items && data.items.length > 0) {
      cache.put(cacheKey, JSON.stringify(data.items[0]), YOUTUBE_CONFIG.CACHE_DURATION);
      return data.items[0];
    }
    
    throw new Error('No channel found with ID/username: ' + channelIdOrName);
    
  } catch (error) {
    Logger.log(`Error fetching channel info for ${channelIdOrName}: ${error}`);
    throw error;
  }
}

/**
 * Get channel videos
 * @param {string} channelId - YouTube channel ID
 * @param {number} maxResults - Maximum number of results to return
 * @returns {Array<Object>} Array of video objects
 */
function getChannelVideos(channelId, maxResults = YOUTUBE_CONFIG.MAX_RESULTS) {
  try {
    const url = `https://www.googleapis.com/youtube/v3/search?channelId=${channelId}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,id&order=date&type=video&maxResults=${maxResults}`;
    
    const response = UrlFetchApp.fetch(url, {
      method: 'GET',
      muteHttpExceptions: true
    });
    
    const data = JSON.parse(response.getContentText());
    
    if (data.items && data.items.length > 0) {
      // Get detailed video information for each item
      const videoIds = data.items.map(item => item.id.videoId);
      return getMultipleVideoDetails(videoIds);
    }
    
    return [];
    
  } catch (error) {
    Logger.log('Error fetching channel videos: ' + error);
    throw error;
  }
}
```

## Advanced Search and Filtering Functions

```javascript
/**
 * Advanced YouTube search with multiple filters
 * @param {Object} params - Search parameters
 * @returns {Array<Object>} Search results
 */
function advancedSearch(params) {
  const defaultParams = {
    q: '',
    type: 'video',
    part: 'snippet',
    maxResults: YOUTUBE_CONFIG.MAX_RESULTS,
    order: 'relevance',
    regionCode: 'US'
  };
  
  const searchParams = Object.assign(defaultParams, params);
  
  // Build query parameters
  let url = `https://www.googleapis.com/youtube/v3/search?key=${YOUTUBE_CONFIG.API_KEY}&part=snippet&type=${searchParams.type}`;
  
  if (searchParams.q) url += `&q=${encodeURIComponent(searchParams.q)}`;
  if (searchParams.order) url += `&order=${searchParams.order}`;
  if (searchParams.regionCode) url += `&regionCode=${searchParams.regionCode}`;
  if (searchParams.maxResults) url += `&maxResults=${searchParams.maxResults}`;
  
  // Add date filters
  if (params.publishedAfter) {
    url += `&publishedAfter=${params.publishedAfter}`;
  }
  if (params.publishedBefore) {
    url += `&publishedBefore=${params.publishedBefore}`;
  }
  
  try {
    const response = UrlFetchApp.fetch(url, {
      method: 'GET',
      muteHttpExceptions: true
    });
    
    const data = JSON.parse(response.getContentText());
    
    // Get detailed video information
    if (data.items && data.items.length > 0) {
      const videoIds = data.items.map(item => item.id.videoId);
      return getMultipleVideoDetails(videoIds);
    }
    
    return [];
    
  } catch (error) {
    Logger.log('Search error: ' + error);
    throw error;
  }
}

/**
 * Filter videos by various criteria
 * @param {Array<Object>} videos - Array of video objects
 * @param {Object} filters - Filter criteria
 * @returns {Array<Object>} Filtered videos
 */
function filterVideos(videos, filters) {
  return videos.filter(video => {
    const snippet = video.snippet;
    const stats = video.statistics;
    
    // Duration filter (in seconds)
    if (filters.minDuration && video.contentDetails?.duration) {
      const durationSeconds = parseVideoDuration(video.contentDetails.duration);
      if (durationSeconds < filters.minDuration) return false;
    }
    
    // View count filter
    if (filters.minViews && parseInt(stats.viewCount) < filters.minViews) {
      return false;
    }
    
    // Like ratio filter (likes/dislikes)
    if (filters.minLikeRatio && stats.likeCount && stats.dislikeCount) {
      const likeRatio = parseInt(stats.likeCount) / (parseInt(stats.dislikeCount) + 1);
      if (likeRatio < filters.minLikeRatio) return false;
    }
    
    // Title search
    if (filters.titleContains && !snippet.title.toLowerCase().includes(filters.titleContains.toLowerCase())) {
      return false;
    }
    
    // Category filter
    if (filters.category && snippet.categoryId !== filters.category) {
      return false;
    }
    
    // Date range filter
    if (filters.publishedAfter && new Date(snippet.publishedAt) < new Date(filters.publishedAfter)) {
      return false;
    }
    
    return true;
  });
}

/**
 * Parse YouTube video duration string to seconds
 * @param {string} duration - ISO 8601 duration string
 * @returns {number} Duration in seconds
 */
function parseVideoDuration(duration) {
  // Example: PT4M32S -> 272 seconds (4 minutes 32 seconds)
  
  const match = duration.match(/PT(\d+H)?(\d+M)?(\d+S)?/);
  
  if (!match) return 0;
  
  const hours = parseInt(match[1] || '0') * 3600;
  const minutes = parseInt(match[2] || '0') * 60;
  const seconds = parseInt(match[3] || '0');
  
  return hours + minutes + seconds;
}
```

## Playlist Management Functions

```javascript
/**
 * Get playlist items by playlist ID
 * @param {string} playlistId - YouTube playlist ID
 * @returns {Array<Object>} Playlist items
 */
function getPlaylistItems(playlistId, maxResults = YOUTUBE_CONFIG.MAX_RESULTS) {
  const cache = CacheService.getScriptCache();
  const cacheKey = `playlist_${playlistId}`;
  
  let cachedData = cache.get(cacheKey);
  if (cachedData) {
    return JSON.parse(cachedData);
  }
  
  const items = [];
  let nextPageToken = null;
  
  try {
    do {
      let url = `https://www.googleapis.com/youtube/v3/playlistItems?playlistId=${playlistId}&key=${YOUTUBE_CONFIG.API_KEY}&part=snippet,contentDetails&maxResults=${maxResults}`;
      
      if (nextPageToken) {
        url += `&pageToken=${nextPageToken}`;
      }
      
      const response = UrlFetchApp.fetch(url, {
        method: 'GET',
        muteHttpExceptions: true
      });
      
      const data = JSON.parse(response.getContentText());
      
      if (data.items && data.items.length > 0) {
        items.push(...data.items);
        
        // Check for next page
        nextPageToken = data.nextPageToken;
      } else {
        nextPageToken = null; // No more pages or error occurred
      }
      
    } while (nextPageToken && items.length < maxResults);
    
    // Cache the result
    cache.put(cacheKey, JSON.stringify(items), YOUTUBE_CONFIG.CACHE_DURATION);
    
    return items;
    
  } catch (error) {
    Logger.log('Error fetching playlist items: ' + error);
    throw error;
  }
}

/**
 * Create a new playlist
 * @param {string} title - Playlist title
 * @param {string} description - Playlist description
 * @returns {Object} Created playlist object
 */
function createPlaylist(title, description = '') {
  try {
    const url = `https://www.googleapis.com/youtube/v3/playlists?part=snippet&key=${YOUTUBE_CONFIG.API_KEY}`;
    
    const payload = {
      snippet: {
        title: title,
        description: description
      }
    };
    
    const response = UrlFetchApp.fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    });
    
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log('Error creating playlist: ' + error);
    throw error;
  }
}

/**
 * Add video to playlist
 * @param {string} playlistId - Target playlist ID
 * @param {string} videoId - Video ID to add
 * @returns {Object} Response from API
 */
function addToPlaylist(playlistId, videoId) {
  try {
    const url = `https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&key=${YOUTUBE_CONFIG.API_KEY}`;
    
    const payload = {
      snippet: {
        playlistId: playlistId,
        resourceId: {
          kind: 'youtube#video',
          videoId: videoId
        }
      }
    };
    
    const response = UrlFetchApp.fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    });
    
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log('Error adding to playlist: ' + error);
    throw error;
  }
}
```

## Video Processing Functions

```javascript
/**
 * Get video statistics with enhanced processing
 * @param {string} videoId - YouTube video ID
 * @returns {Object} Processed video statistics
 */
function getProcessedVideoStats(videoId) {
  const video = getVideoDetails(videoId);
  
  if (!video || !video.statistics) {
    return null;
  }
  
  const stats = video.statistics;
  
  // Calculate additional metrics
  const processedStats = {
    id: video.id,
    title: video.snippet?.title,
    description: video.snippet?.description,
    publishedAt: video.snippet?.publishedAt,
    
    // Basic statistics
    viewCount: parseInt(stats.viewCount) || 0,
    likeCount: parseInt(stats.likeCount) || 0,
    dislikeCount: parseInt(stats.dislikeCount) || 0,
    favoriteCount: parseInt(stats.favoriteCount) || 0,
    commentCount: parseInt(stats.commentCount) || 0,
    
    // Calculated metrics
    likeRatio: stats.likeCount && stats.dislikeCount ? 
      (parseInt(stats.likeCount) / (parseInt(stats.dislikeCount) + 1)) : 0,
    
    engagementRate: stats.viewCount && stats.commentCount ?
      (stats.commentCount / parseInt(stats.viewCount)) * 100 : 0
  };
  
  return processedStats;
}

/**
 * Batch process multiple videos
 * @param {Array<string>} videoIds - Array of video IDs to process
 * @returns {Array<Object>} Processed video statistics
 */
function batchProcessVideos(videoIds) {
  const results = [];
  
  // Process in chunks to avoid hitting API limits
  const chunkSize = 50;
  
  for (let i = 0; i < videoIds.length; i += chunkSize) {
    const chunk = videoIds.slice(i, i + chunkSize);
    
    try {
      // Get detailed information for this chunk
      const videos = getMultipleVideoDetails(chunk);
      
      // Process each video in the chunk
      videos.forEach(video => {
        results.push(getProcessedVideoStats(video.id));
      });
      
      // Add delay between chunks
      Utilities.sleep(YOUTUBE_CONFIG.REQUEST_DELAY * 2);
      
    } catch (error) {
      Logger.log(`Error processing chunk starting at index ${i}: ${error}`);
    }
  }
  
  return results;
}

/**
 * Analyze video performance over time
 * @param {string} videoId - Video ID to analyze
 * @param {number} days - Number of days to look back (default: 30)
 * @returns {Object} Performance analysis
 */
function analyzeVideoPerformance(videoId, days = 30) {
  // Note: YouTube Data API doesn't provide historical data directly
  // This is a simplified version that would need to be extended with your own tracking
  
  const videoDetails = getVideoDetails(videoId);
  
  if (!videoDetails || !videoDetails.snippet || !videoDetails.statistics) {
    return null;
  }
  
  const snippet = videoDetails.snippet;
  const stats = videoDetails.statistics;
  
  // Create performance analysis object
  const analysis = {
    videoId: videoId,
    title: snippet.title,
    publishedAt: new Date(snippet.publishedAt),
    currentStats: {
      views: parseInt(stats.viewCount) || 0,
      likes: parseInt(stats.likeCount) || 0,
      comments: parseInt(stats.commentCount) || 0
    },
    
    // Calculate key metrics
    viewToLikeRatio: stats.likeCount && stats.viewCount ?
      parseInt(stats.viewCount) / (parseInt(stats.likeCount) + 1) : 0,
    
    engagementScore: calculateEngagementScore(
      parseInt(stats.viewCount) || 0,
      parseInt(stats.likeCount) || 0,
      parseInt(stats.commentCount) || 0
    )
  };
  
  return analysis;
}

/**
 * Calculate engagement score based on multiple factors
 * @param {number} views - View count
 * @param {number} likes - Like count
 * @param {number} comments - Comment count
 * @returns {number} Engagement score (0-100)
 */
function calculateEngagementScore(views, likes, comments) {
  if (views === 0) return 0;
  
  // Normalize scores to a scale of 0-100
  const viewScore = Math.min((views / 1000), 100);
  const likeScore = Math.min((likes / 25), 100);
  const commentScore = Math.min((comments / 5), 100);
  
  // Weighted average (views: 40%, likes: 35%, comments: 25%)
  return Math.round(
    (viewScore * 0.4) + 
    (likeScore * 0.35) + 
    (commentScore * 0.25)
  );
}
```

## Error Handling and Optimization

```javascript
/**
 * Robust YouTube API call with error handling and retry logic
 * @param {string} url - API endpoint URL
 * @param {number} maxRetries - Maximum number of retries (default: 3)
 * @returns {Object} Response data or null if failed
 */
function robustApiCall(url, maxRetries = 3) {
  let lastError;
  
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      const response = UrlFetchApp.fetch(url, {
        method: 'GET',
        muteHttpExceptions: true,
        headers: {
          'User-Agent': 'Google Apps Script'
        }
      });
      
      if (response.getResponseCode() === 200) {
        return JSON.parse(response.getContentText());
      } else if (response.getResponseCode() === 429) {
        // Rate limit exceeded - wait and retry
        Logger.log(`Rate limited on attempt ${attempt}, waiting...`);
        Utilities.sleep(1000 * Math.pow(2, attempt)); // Exponential backoff
        continue;
      } else {
        throw new Error(`HTTP ${response.getResponseCode()}: ${response.getContentText()}`);
      }
      
    } catch (error) {
      lastError = error;
      
      if (attempt < maxRetries) {
        Logger.log(`Attempt ${attempt} failed: ${error}. Retrying...`);
        Utilities.sleep(1000 * Math.pow(2, attempt)); // Exponential backoff
      }
    }
  }
  
  Logger.log(`All attempts failed for URL: ${url}`);
  throw lastError;
}

/**
 * Get video information with enhanced error handling
 * @param {string} videoId - Video ID to fetch
 * @returns {Object|null} Video information or null if failed
 */
function getVideoDetailsSafe(videoId) {
  try {
    return getVideoDetails(videoId);
  } catch (error) {
    Logger.log(`Failed to get details for video ${videoId}: ${error}`);
    
    // Return a default structure for error cases
    return {
      id: videoId,
      snippet: {
        title: 'Error - Video not found',
        description: 'Could not retrieve video information'
      },
      statistics: {},
      error: true
    };
  }
}

/**
 * Optimized batch processing with caching and rate limiting
 * @param {Array<string>} ids - Array of IDs to process
 * @param {Function} processor - Function to process each item
 * @returns {Array<Object>} Processed results
 */
function optimizedBatchProcessing(ids, processor) {
  const cache = CacheService.getScriptCache();
  const results = [];
  
  // Group by type for efficient processing (if needed)
  const idGroups = chunkArray(ids, 50); // Process in chunks of 50
  
  idGroups.forEach((group, groupIndex) => {
    try {
      const processedGroup = group.map(id => {
        const cacheKey = `processed_${id}`;
        let cachedResult = cache.get(cacheKey);
        
        if (cachedResult) {
          return JSON.parse(cachedResult);
        }
        
        const result = processor(id);
        cache.put(cacheKey, JSON.stringify(result), YOUTUBE_CONFIG.CACHE_DURATION * 2);
        return result;
      });
      
      results.push(...processedGroup);
      
      // Add delay between batches
      if (groupIndex < idGroups.length - 1) {
        Utilities.sleep(YOUTUBE_CONFIG.REQUEST_DELAY * 3);
      }
      
    } catch (error) {
      Logger.log(`Error processing batch ${groupIndex}: ${error}`);
    }
  });
  
  return results;
}

/**
 * Helper function to split array into chunks
 * @param {Array} array - Array to chunk
 * @param {number} size - Chunk size
 * @returns {Array<Array>} Chunks of the original array
 */
function chunkArray(array, size) {
  const chunks = [];
  
  for (let i = 0; i < array.length; i += size) {
    chunks.push(array.slice(i, i + size));
  }
  
  return chunks;
}
```

## Best Practices and Performance Tips

```javascript
/**
 * Configuration management with validation
 */
class YouTubeConfigManager {
  constructor() {
    this.config = Object.assign({
      API_KEY: '',
      MAX_RESULTS: 50,
      CACHE_DURATION: 3600,
      REQUEST_DELAY: 200,
      ENABLE_CACHING: true
    }, YOUTUBE_CONFIG);
    
    this.validate();
  }
  
  validate() {
    if (!this.config.API_KEY) {
      throw new Error('YouTube API key is required');
    }
    
    if (this.config.MAX_RESULTS > 50) {
      Logger.log('Warning: MAX_RESULTS above recommended limit of 50');
    }
    
    if (this.config.REQUEST_DELAY < 100) {
      Logger.log('Warning: REQUEST_DELAY may be too low for rate limiting');
    }
  }
  
  update(configUpdates) {
    Object.assign(this.config, configUpdates);
    this.validate();
  }
}

/**
 * Monitor API usage and performance
 */
function monitorApiUsage() {
  const cache = CacheService.getScriptCache();
  
  // Get or initialize usage counter
  let usageCount = parseInt(cache.get('youtube_api_calls')) || 0;
  usageCount++;
  
  cache.put('youtube_api_calls', usageCount.toString(), 3600); // Reset every hour
  
  return {
    calls: usageCount,
    timestamp: new Date().toISOString()
  };
}

/**
 * Memory optimization for large datasets
 */
function processLargeDataset(videoIds, batchSize = 25) {
  const results = [];
  
  for (let i = 0; i < videoIds.length; i += batchSize) {
    const batch = videoIds.slice(i, i + batchSize);
    
    try {
      // Process batch and immediately free memory
      const processedBatch = getMultipleVideoDetails(batch);
      results.push(...processedBatch);
      
      // Clear references to prevent memory issues
      processedBatch.length = 0;
      
      Logger.log(`Processed batch ${Math.floor(i/batchSize) + 1}`);
      
    } catch (error) {
      Logger.log(`Error processing batch starting at index ${i}: ${error}`);
    }
    
    // Add delay between batches
    Utilities.sleep(YOUTUBE_CONFIG.REQUEST_DELAY);
  }
  
  return results;
}
```

## Complete Example Implementation

```javascript
/**
 * Complete YouTube API utility class with all features
 */
class YouTubeUtility {
  constructor(apiKey) {
    this.config = {
      API_KEY: apiKey,
      MAX_RESULTS: 50,
      CACHE_DURATION: 3600,
      REQUEST_DELAY: 200
    };
    
    this.cache = CacheService.getScriptCache();
  }
  
  // Core methods from our tutorial
  getVideoDetails(videoId) {
    const cacheKey = `video_${videoId}`;
    let cachedData = this.cache.get(cacheKey);
    
    if (cachedData) {
      return JSON.parse(cachedData);
    }
    
    try {
      const url = `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${this.config.API_KEY}&part=snippet,statistics,contentDetails`;
      
      const response = UrlFetchApp.fetch(url, { method: 'GET', muteHttpExceptions: true });
      const data = JSON.parse(response.getContentText());
      
      if (data.items && data.items.length > 0) {
        this.cache.put(cacheKey, JSON.stringify(data.items[0]), this.config.CACHE_DURATION);
        return data.items[0];
      }
      
    } catch (error) {
      Logger.log('Error: ' + error);
      throw error;
    }
    
    return null;
  }
  
  searchVideos(query, maxResults = 10) {
    try {
      const url = `https://www.googleapis.com/youtube/v3/search?q=${encodeURIComponent(query)}&key=${this.config.API_KEY}&part=snippet&type=video&maxResults=${maxResults}`;
      
      const response = UrlFetchApp.fetch(url, { method: 'GET', muteHttpExceptions: true });
      const data = JSON.parse(response.getContentText());
      
      if (data.items && data.items.length > 0) {
        return data.items.map(item => item.id.videoId);
      }
      
    } catch (error) {
      Logger.log('Search error: ' + error);
      throw error;
    }
    
    return [];
  }
  
  // Method to demonstrate usage
  analyzePopularVideos(channelId, days = 30) {
    const videos = getChannelVideos(channelId, 25);
    
    if (!videos || videos.length === 0) {
      Logger.log('No videos found');
      return [];
    }
    
    // Filter for popular videos (high view count)
    const popularVideos = filterVideos(videos, {
      minViews: 1000
    });
    
    // Get processed statistics
    return popularVideos.map(video => getProcessedVideoStats(video.id));
  }
}

// Usage example:
function demonstrateUsage() {
  try {
    // Initialize with your API key
    const youtube = new YouTubeUtility('YOUR_API_KEY_HERE');
    
    // Search for videos
    const videoIds = youtube.searchVideos('Google Apps Script', 5);
    Logger.log('Found videos: ' + videoIds.length);
    
    // Get detailed information
    if (videoIds.length > 0) {
      const details = youtube.getVideoDetails(videoIds[0]);
      Logger.log('Title: ' + details.snippet.title);
      
      // Analyze popular content
      const analysis = youtube.analyzePopularVideos('UCVYamHli2a45XO8x93B1F4w', 7);
      Logger.log('Analysis results: ' + analysis.length);
    }
    
  } catch (error) {
    Logger.log('Demo failed: ' + error);
  }
}
```

## Key Takeaways

This tutorial provided you with:

1. **Foundation Setup**: Proper API configuration and authentication
2. **Core Functions**: Video and channel information retrieval
3. **Advanced Features**: Search, filtering, playlist management
4. **Performance Optimization**: Caching, batching, rate limiting
5. **Error Handling**: Robust error management with retry logic
6. **Best Practices**: Memory management, configuration validation

Remember to:
- Keep your API keys secure
- Respect rate limits and implement appropriate delays
- Use caching strategically to reduce API calls
- Handle errors gracefully in production environments
- Monitor usage and optimize based on performance metrics

This framework provides a solid foundation that you can extend based on your specific YouTube automation needs.

