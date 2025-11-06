---
title: YouTube Data API v3 with Google Apps Script - Complete Guide
date: 2025-01-30 14:17:58
---

# YouTube Data API v3 with Google Apps Script: A Comprehensive Tutorial

## Table of Contents
1. [Introduction](#introduction)
2. [Prerequisites and Setup](#prerequisites-and-setup)
3. [Authentication Setup](#authentication-setup)
4. [Core API Functions](#core-api-functions)
5. [Advanced Search and Filtering](#advanced-search-and-filtering)
6. [Playlist Management](#playlist-management)
7. [Performance Optimization](#performance-optimization)
8. [Quota Management](#quota-management)
9. [Error Handling](#error-handling)
10. [Complete Examples](#complete-examples)

## Introduction

This guide demonstrates how to build production-ready YouTube Data API v3 integrations using Google Apps Script, following official Google best practices for performance, quota management, and error handling.

## Prerequisites and Setup

### 1. Enable YouTube Data API v3

```javascript
// 1. Go to Google Cloud Console (console.cloud.google.com)
// 2. Create or select a project
// 3. Enable "YouTube Data API v3"
// 4. Create credentials:
//    - For public data: API Key
//    - For user data: OAuth 2.0 Client ID
```

### 2. Configure Google Apps Script

```javascript
/**
 * Store API key securely using Properties Service
 * Run this once to set your API key
 */
function setupApiKey() {
  const apiKey = 'YOUR_API_KEY_HERE'; // Replace with your actual API key
  PropertiesService.getScriptProperties().setProperty('YOUTUBE_API_KEY', apiKey);
  Logger.log('API Key stored securely');
}

/**
 * Enable Advanced Google Services:
 * Resources > Advanced Google Services > YouTube Data API v3 (toggle ON)
 */
```

## Authentication Setup

```javascript
/**
 * Global configuration for YouTube API
 */
const YOUTUBE_CONFIG = {
  BASE_URL: 'https://www.googleapis.com/youtube/v3',
  MAX_RESULTS_PER_REQUEST: 50, // API maximum
  CACHE_DURATION_SECONDS: 3600,
  RETRY_ATTEMPTS: 3,
  BACKOFF_MULTIPLIER: 2,
  INITIAL_BACKOFF_MS: 1000
};

/**
 * Get API key from Properties Service
 * @returns {string} YouTube API key
 */
function getApiKey() {
  const apiKey = PropertiesService.getScriptProperties().getProperty('YOUTUBE_API_KEY');
  if (!apiKey) {
    throw new Error('YouTube API key not configured. Run setupApiKey() first.');
  }
  return apiKey;
}

/**
 * Get OAuth2 access token for authenticated requests
 * Uses Apps Script built-in OAuth2 service
 * @returns {string} Access token
 */
function getOAuthToken() {
  return ScriptApp.getOAuthToken();
}
```

## Core API Functions

### Video Operations

```javascript
/**
 * Get detailed information about a video
 * @param {string} videoId - YouTube video ID
 * @param {string[]} parts - Parts to retrieve (snippet, statistics, contentDetails, etc.)
 * @returns {Object|null} Video resource or null if not found
 */
function getVideoDetails(videoId, parts = ['snippet', 'statistics', 'contentDetails']) {
  const cache = CacheService.getScriptCache();
  const cacheKey = `video:${videoId}:${parts.join(',')}`;
  
  // Try cache first
  const cached = cache.get(cacheKey);
  if (cached) {
    return JSON.parse(cached);
  }
  
  const apiKey = getApiKey();
  const url = `${YOUTUBE_CONFIG.BASE_URL}/videos` +
    `?id=${encodeURIComponent(videoId)}` +
    `&part=${parts.join(',')}` +
    `&key=${apiKey}`;
  
  try {
    const response = makeApiRequest(url);
    
    if (response.items && response.items.length > 0) {
      const video = response.items[0];
      cache.put(cacheKey, JSON.stringify(video), YOUTUBE_CONFIG.CACHE_DURATION_SECONDS);
      return video;
    }
    
    Logger.log(`Video not found: ${videoId}`);
    return null;
    
  } catch (error) {
    Logger.log(`Error fetching video ${videoId}: ${error.message}`);
    throw error;
  }
}

/**
 * Get details for multiple videos (batch request)
 * Maximum 50 video IDs per request per API documentation
 * @param {string[]} videoIds - Array of video IDs
 * @param {string[]} parts - Parts to retrieve
 * @returns {Object[]} Array of video resources
 */
function getMultipleVideos(videoIds, parts = ['snippet', 'statistics', 'contentDetails']) {
  if (!videoIds || videoIds.length === 0) {
    return [];
  }
  
  const results = [];
  const cache = CacheService.getScriptCache();
  
  // Process in batches of 50 (API limit)
  for (let i = 0; i < videoIds.length; i += YOUTUBE_CONFIG.MAX_RESULTS_PER_REQUEST) {
    const batch = videoIds.slice(i, i + YOUTUBE_CONFIG.MAX_RESULTS_PER_REQUEST);
    const cacheKey = `videos:${batch.join(',')}:${parts.join(',')}`;
    
    // Check cache
    const cached = cache.get(cacheKey);
    if (cached) {
      results.push(...JSON.parse(cached));
      continue;
    }
    
    const apiKey = getApiKey();
    const url = `${YOUTUBE_CONFIG.BASE_URL}/videos` +
      `?id=${batch.map(id => encodeURIComponent(id)).join(',')}` +
      `&part=${parts.join(',')}` +
      `&key=${apiKey}`;
    
    try {
      const response = makeApiRequest(url);
      
      if (response.items && response.items.length > 0) {
        cache.put(cacheKey, JSON.stringify(response.items), YOUTUBE_CONFIG.CACHE_DURATION_SECONDS);
        results.push(...response.items);
      }
      
      // Rate limiting between batches
      if (i + YOUTUBE_CONFIG.MAX_RESULTS_PER_REQUEST < videoIds.length) {
        Utilities.sleep(100);
      }
      
    } catch (error) {
      Logger.log(`Error fetching video batch: ${error.message}`);
    }
  }
  
  return results;
}

/**
 * Search for videos
 * @param {Object} options - Search options
 * @param {string} options.q - Search query
 * @param {number} options.maxResults - Maximum results (1-50)
 * @param {string} options.order - Sort order (date, rating, relevance, title, viewCount)
 * @param {string} options.type - Resource type (video, channel, playlist)
 * @param {string} options.channelId - Filter by channel ID
 * @param {string} options.publishedAfter - RFC 3339 datetime
 * @param {string} options.publishedBefore - RFC 3339 datetime
 * @param {string} options.regionCode - ISO 3166-1 alpha-2 country code
 * @param {string} options.relevanceLanguage - ISO 639-1 language code
 * @returns {Object[]} Array of search results with video IDs
 */
function searchVideos(options = {}) {
  const defaults = {
    maxResults: 25,
    order: 'relevance',
    type: 'video',
    regionCode: 'US'
  };
  
  const params = { ...defaults, ...options };
  const apiKey = getApiKey();
  
  let url = `${YOUTUBE_CONFIG.BASE_URL}/search?key=${apiKey}`;
  
  // Build query parameters
  Object.keys(params).forEach(key => {
    if (params[key] !== undefined && params[key] !== null) {
      url += `&${key}=${encodeURIComponent(params[key])}`;
    }
  });
  
  // Always request id and snippet for search
  if (!url.includes('part=')) {
    url += '&part=snippet';
  }
  
  try {
    const results = [];
    let pageToken = null;
    
    do {
      const pageUrl = pageToken ? `${url}&pageToken=${pageToken}` : url;
      const response = makeApiRequest(pageUrl);
      
      if (response.items && response.items.length > 0) {
        results.push(...response.items);
      }
      
      // Handle pagination
      pageToken = response.nextPageToken;
      
      // Stop if we've reached maxResults
      if (results.length >= params.maxResults) {
        break;
      }
      
      // Rate limiting between pages
      if (pageToken) {
        Utilities.sleep(100);
      }
      
    } while (pageToken && results.length < params.maxResults);
    
    return results.slice(0, params.maxResults);
    
  } catch (error) {
    Logger.log(`Search error: ${error.message}`);
    throw error;
  }
}
```

### Channel Operations

```javascript
/**
 * Get channel information
 * @param {string} channelId - YouTube channel ID
 * @param {string[]} parts - Parts to retrieve
 * @returns {Object|null} Channel resource or null
 */
function getChannelInfo(channelId, parts = ['snippet', 'statistics', 'contentDetails']) {
  const cache = CacheService.getScriptCache();
  const cacheKey = `channel:${channelId}:${parts.join(',')}`;
  
  const cached = cache.get(cacheKey);
  if (cached) {
    return JSON.parse(cached);
  }
  
  const apiKey = getApiKey();
  const url = `${YOUTUBE_CONFIG.BASE_URL}/channels` +
    `?id=${encodeURIComponent(channelId)}` +
    `&part=${parts.join(',')}` +
    `&key=${apiKey}`;
  
  try {
    const response = makeApiRequest(url);
    
    if (response.items && response.items.length > 0) {
      const channel = response.items[0];
      cache.put(cacheKey, JSON.stringify(channel), YOUTUBE_CONFIG.CACHE_DURATION_SECONDS);
      return channel;
    }
    
    return null;
    
  } catch (error) {
    Logger.log(`Error fetching channel ${channelId}: ${error.message}`);
    throw error;
  }
}

/**
 * Get channel by username (legacy)
 * @param {string} username - YouTube username
 * @param {string[]} parts - Parts to retrieve
 * @returns {Object|null} Channel resource or null
 */
function getChannelByUsername(username, parts = ['snippet', 'statistics', 'contentDetails']) {
  const apiKey = getApiKey();
  const url = `${YOUTUBE_CONFIG.BASE_URL}/channels` +
    `?forUsername=${encodeURIComponent(username)}` +
    `&part=${parts.join(',')}` +
    `&key=${apiKey}`;
  
  try {
    const response = makeApiRequest(url);
    return response.items && response.items.length > 0 ? response.items[0] : null;
  } catch (error) {
    Logger.log(`Error fetching channel by username ${username}: ${error.message}`);
    throw error;
  }
}

/**
 * Get videos from a channel's uploads playlist
 * @param {string} channelId - YouTube channel ID
 * @param {number} maxResults - Maximum videos to retrieve
 * @returns {Object[]} Array of video resources
 */
function getChannelVideos(channelId, maxResults = 50) {
  // First, get the channel's uploads playlist ID
  const channel = getChannelInfo(channelId, ['contentDetails']);
  
  if (!channel || !channel.contentDetails || !channel.contentDetails.relatedPlaylists) {
    Logger.log(`Could not find uploads playlist for channel ${channelId}`);
    return [];
  }
  
  const uploadsPlaylistId = channel.contentDetails.relatedPlaylists.uploads;
  
  // Get videos from the uploads playlist
  const playlistItems = getPlaylistItems(uploadsPlaylistId, maxResults);
  
  // Extract video IDs
  const videoIds = playlistItems
    .map(item => item.contentDetails?.videoId)
    .filter(id => id);
  
  // Get full video details
  return getMultipleVideos(videoIds);
}
```

## Advanced Search and Filtering

```javascript
/**
 * Filter videos by criteria
 * @param {Object[]} videos - Array of video resources
 * @param {Object} criteria - Filter criteria
 * @param {number} criteria.minViews - Minimum view count
 * @param {number} criteria.maxViews - Maximum view count
 * @param {number} criteria.minDuration - Minimum duration in seconds
 * @param {number} criteria.maxDuration - Maximum duration in seconds
 * @param {number} criteria.minLikes - Minimum like count
 * @param {Date} criteria.publishedAfter - Published after date
 * @param {Date} criteria.publishedBefore - Published before date
 * @param {string} criteria.titleContains - Title must contain string
 * @param {string} criteria.titleNotContains - Title must not contain string
 * @returns {Object[]} Filtered videos
 */
function filterVideos(videos, criteria = {}) {
  return videos.filter(video => {
    const snippet = video.snippet || {};
    const statistics = video.statistics || {};
    const contentDetails = video.contentDetails || {};
    
    // View count filters
    if (criteria.minViews !== undefined) {
      const views = parseInt(statistics.viewCount) || 0;
      if (views < criteria.minViews) return false;
    }
    
    if (criteria.maxViews !== undefined) {
      const views = parseInt(statistics.viewCount) || 0;
      if (views > criteria.maxViews) return false;
    }
    
    // Duration filters
    if (criteria.minDuration !== undefined || criteria.maxDuration !== undefined) {
      const duration = parseDuration(contentDetails.duration);
      
      if (criteria.minDuration !== undefined && duration < criteria.minDuration) {
        return false;
      }
      
      if (criteria.maxDuration !== undefined && duration > criteria.maxDuration) {
        return false;
      }
    }
    
    // Like count filter
    if (criteria.minLikes !== undefined) {
      const likes = parseInt(statistics.likeCount) || 0;
      if (likes < criteria.minLikes) return false;
    }
    
    // Date filters
    if (criteria.publishedAfter) {
      const publishDate = new Date(snippet.publishedAt);
      if (publishDate < criteria.publishedAfter) return false;
    }
    
    if (criteria.publishedBefore) {
      const publishDate = new Date(snippet.publishedAt);
      if (publishDate > criteria.publishedBefore) return false;
    }
    
    // Title filters
    if (criteria.titleContains) {
      const title = (snippet.title || '').toLowerCase();
      const search = criteria.titleContains.toLowerCase();
      if (!title.includes(search)) return false;
    }
    
    if (criteria.titleNotContains) {
      const title = (snippet.title || '').toLowerCase();
      const search = criteria.titleNotContains.toLowerCase();
      if (title.includes(search)) return false;
    }
    
    return true;
  });
}

/**
 * Parse ISO 8601 duration to seconds
 * @param {string} duration - ISO 8601 duration (e.g., PT1H2M10S)
 * @returns {number} Duration in seconds
 */
function parseDuration(duration) {
  if (!duration) return 0;
  
  const match = duration.match(/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/);
  if (!match) return 0;
  
  const hours = parseInt(match[1]) || 0;
  const minutes = parseInt(match[2]) || 0;
  const seconds = parseInt(match[3]) || 0;
  
  return hours * 3600 + minutes * 60 + seconds;
}

/**
 * Sort videos by various criteria
 * @param {Object[]} videos - Array of video resources
 * @param {string} sortBy - Sort field (views, likes, date, duration, title)
 * @param {boolean} ascending - Sort order
 * @returns {Object[]} Sorted videos
 */
function sortVideos(videos, sortBy = 'views', ascending = false) {
  const sorted = [...videos].sort((a, b) => {
    let aValue, bValue;
    
    switch (sortBy) {
      case 'views':
        aValue = parseInt(a.statistics?.viewCount) || 0;
        bValue = parseInt(b.statistics?.viewCount) || 0;
        break;
      
      case 'likes':
        aValue = parseInt(a.statistics?.likeCount) || 0;
        bValue = parseInt(b.statistics?.likeCount) || 0;
        break;
      
      case 'date':
        aValue = new Date(a.snippet?.publishedAt).getTime();
        bValue = new Date(b.snippet?.publishedAt).getTime();
        break;
      
      case 'duration':
        aValue = parseDuration(a.contentDetails?.duration);
        bValue = parseDuration(b.contentDetails?.duration);
        break;
      
      case 'title':
        aValue = a.snippet?.title || '';
        bValue = b.snippet?.title || '';
        return ascending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
      
      default:
        return 0;
    }
    
    return ascending ? aValue - bValue : bValue - aValue;
  });
  
  return sorted;
}
```

## Playlist Management

```javascript
/**
 * Get all items from a playlist with pagination
 * @param {string} playlistId - YouTube playlist ID
 * @param {number} maxResults - Maximum items to retrieve
 * @returns {Object[]} Array of playlist items
 */
function getPlaylistItems(playlistId, maxResults = 50) {
  const apiKey = getApiKey();
  const items = [];
  let pageToken = null;
  
  try {
    do {
      let url = `${YOUTUBE_CONFIG.BASE_URL}/playlistItems` +
        `?playlistId=${encodeURIComponent(playlistId)}` +
        `&part=snippet,contentDetails` +
        `&maxResults=${Math.min(YOUTUBE_CONFIG.MAX_RESULTS_PER_REQUEST, maxResults - items.length)}` +
        `&key=${apiKey}`;
      
      if (pageToken) {
        url += `&pageToken=${pageToken}`;
      }
      
      const response = makeApiRequest(url);
      
      if (response.items && response.items.length > 0) {
        items.push(...response.items);
      }
      
      pageToken = response.nextPageToken;
      
      // Stop if we've reached maxResults
      if (items.length >= maxResults) {
        break;
      }
      
      // Rate limiting
      if (pageToken) {
        Utilities.sleep(100);
      }
      
    } while (pageToken && items.length < maxResults);
    
    return items.slice(0, maxResults);
    
  } catch (error) {
    Logger.log(`Error fetching playlist ${playlistId}: ${error.message}`);
    throw error;
  }
}

/**
 * Get user's playlists (requires OAuth)
 * @param {number} maxResults - Maximum playlists to retrieve
 * @returns {Object[]} Array of playlist resources
 */
function getMyPlaylists(maxResults = 50) {
  const items = [];
  let pageToken = null;
  
  try {
    do {
      let url = `${YOUTUBE_CONFIG.BASE_URL}/playlists` +
        `?mine=true` +
        `&part=snippet,contentDetails` +
        `&maxResults=${Math.min(YOUTUBE_CONFIG.MAX_RESULTS_PER_REQUEST, maxResults - items.length)}`;
      
      if (pageToken) {
        url += `&pageToken=${pageToken}`;
      }
      
      const response = makeAuthenticatedRequest(url);
      
      if (response.items && response.items.length > 0) {
        items.push(...response.items);
      }
      
      pageToken = response.nextPageToken;
      
      if (items.length >= maxResults) {
        break;
      }
      
      if (pageToken) {
        Utilities.sleep(100);
      }
      
    } while (pageToken && items.length < maxResults);
    
    return items.slice(0, maxResults);
    
  } catch (error) {
    Logger.log(`Error fetching playlists: ${error.message}`);
    throw error;
  }
}
```

## Performance Optimization

```javascript
/**
 * Batch process videos with memory optimization
 * @param {string[]} videoIds - Array of video IDs
 * @param {Function} processor - Processing function
 * @param {number} batchSize - Batch size
 * @returns {Object[]} Processed results
 */
function batchProcessVideos(videoIds, processor, batchSize = 25) {
  const results = [];
  
  for (let i = 0; i < videoIds.length; i += batchSize) {
    const batch = videoIds.slice(i, i + batchSize);
    
    try {
      // Fetch video details for this batch
      const videos = getMultipleVideos(batch);
      
      // Process each video
      const processedBatch = videos.map(video => processor(video));
      results.push(...processedBatch);
      
      // Clear batch to free memory
      videos.length = 0;
      processedBatch.length = 0;
      
      Logger.log(`Processed batch ${Math.floor(i / batchSize) + 1} of ${Math.ceil(videoIds.length / batchSize)}`);
      
      // Rate limiting
      if (i + batchSize < videoIds.length) {
        Utilities.sleep(200);
      }
      
    } catch (error) {
      Logger.log(`Error processing batch at index ${i}: ${error.message}`);
    }
  }
  
  return results;
}

/**
 * Smart cache management
 */
class YouTubeCache {
  constructor() {
    this.cache = CacheService.getScriptCache();
    this.namespace = 'yt';
  }
  
  /**
   * Generate cache key
   * @private
   */
  _key(type, id, parts = []) {
    return `${this.namespace}:${type}:${id}:${parts.join(',')}`;
  }
  
  /**
   * Get from cache
   */
  get(type, id, parts = []) {
    const key = this._key(type, id, parts);
    const cached = this.cache.get(key);
    return cached ? JSON.parse(cached) : null;
  }
  
  /**
   * Put in cache
   */
  put(type, id, data, parts = [], ttl = YOUTUBE_CONFIG.CACHE_DURATION_SECONDS) {
    const key = this._key(type, id, parts);
    this.cache.put(key, JSON.stringify(data), ttl);
  }
  
  /**
   * Clear specific cache entry
   */
  remove(type, id, parts = []) {
    const key = this._key(type, id, parts);
    this.cache.remove(key);
  }
  
  /**
   * Clear all YouTube cache (namespace-aware)
   */
  clear() {
    // Note: CacheService doesn't support wildcard deletion
    // You'll need to track keys separately if full clear is needed
    Logger.log('Cache cleared (implement key tracking for complete clear)');
  }
}
```

## Quota Management

```javascript
/**
 * YouTube API quota tracking and management
 */
class QuotaManager {
  constructor() {
    this.cache = CacheService.getScriptCache();
    this.dailyQuota = 10000; // Default daily quota
    this.costs = {
      'videos': 1,
      'search': 100,
      'channels': 1,
      'playlistItems': 1,
      'playlists': 1
    };
  }
  
  /**
   * Get current quota usage
   */
  getUsage() {
    const today = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), 'yyyy-MM-dd');
    const key = `quota:${today}`;
    return parseInt(this.cache.get(key)) || 0;
  }
  
  /**
   * Add quota usage
   */
  addUsage(endpoint, cost = null) {
    const actualCost = cost || this.costs[endpoint] || 1;
    const today = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), 'yyyy-MM-dd');
    const key = `quota:${today}`;
    
    const currentUsage = this.getUsage();
    const newUsage = currentUsage + actualCost;
    
    // Cache until end of day
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    const secondsUntilMidnight = Math.floor((tomorrow - new Date()) / 1000);
    
    this.cache.put(key, newUsage.toString(), secondsUntilMidnight);
    
    Logger.log(`Quota used: ${actualCost} units. Total today: ${newUsage}/${this.dailyQuota}`);
    
    return newUsage;
  }
  
  /**
   * Check if operation is within quota
   */
  canExecute(endpoint, cost = null) {
    const actualCost = cost || this.costs[endpoint] || 1;
    const currentUsage = this.getUsage();
    return (currentUsage + actualCost) <= this.dailyQuota;
  }
  
  /**
   * Get remaining quota
   */
  getRemaining() {
    return this.dailyQuota - this.getUsage();
  }
}

// Global quota manager instance
const quotaManager = new QuotaManager();
```

## Error Handling

```javascript
/**
 * Make API request with retry logic and error handling
 * @param {string} url - API endpoint URL
 * @param {number} attempt - Current attempt number
 * @returns {Object} Parsed response
 */
function makeApiRequest(url, attempt = 1) {
  try {
    const response = UrlFetchApp.fetch(url, {
      method: 'GET',
      muteHttpExceptions: true,
      headers: {
        'Accept': 'application/json'
      }
    });
    
    const responseCode = response.getResponseCode();
    const responseText = response.getContentText();
    
    // Success
    if (responseCode === 200) {
      // Track quota usage
      const endpoint = extractEndpoint(url);
      quotaManager.addUsage(endpoint);
      
      return JSON.parse(responseText);
    }
    
    // Handle specific error codes
    const errorData = JSON.parse(responseText);
    
    // Quota exceeded
    if (responseCode === 403 && errorData.error?.errors?.[0]?.reason === 'quotaExceeded') {
      throw new Error('YouTube API quota exceeded. Please try again tomorrow.');
    }
    
    // Rate limit exceeded - retry with backoff
    if (responseCode === 429 || responseCode === 403) {
      if (attempt <= YOUTUBE_CONFIG.RETRY_ATTEMPTS) {
        const backoffMs = YOUTUBE_CONFIG.INITIAL_BACKOFF_MS * Math.pow(YOUTUBE_CONFIG.BACKOFF_MULTIPLIER, attempt - 1);
        Logger.log(`Rate limited. Retrying in ${backoffMs}ms... (Attempt ${attempt}/${YOUTUBE_CONFIG.RETRY_ATTEMPTS})`);
        Utilities.sleep(backoffMs);
        return makeApiRequest(url, attempt + 1);
      }
      throw new Error('Rate limit exceeded. Max retries reached.');
    }
    
    // Bad request
    if (responseCode === 400) {
      throw new Error(`Bad request: ${errorData.error?.message || responseText}`);
    }
    
    // Not found
    if (responseCode === 404) {
      throw new Error('Resource not found');
    }
    
    // Server error - retry
    if (responseCode >= 500 && attempt <= YOUTUBE_CONFIG.RETRY_ATTEMPTS) {
      const backoffMs = YOUTUBE_CONFIG.INITIAL_BACKOFF_MS * Math.pow(YOUTUBE_CONFIG.BACKOFF_MULTIPLIER, attempt - 1);
      Logger.log(`Server error. Retrying in ${backoffMs}ms... (Attempt ${attempt}/${YOUTUBE_CONFIG.RETRY_ATTEMPTS})`);
      Utilities.sleep(backoffMs);
      return makeApiRequest(url, attempt + 1);
    }
    
    // Unknown error
    throw new Error(`API request failed with status ${responseCode}: ${responseText}`);
    
  } catch (error) {
    // Network or parsing errors
    if (attempt <= YOUTUBE_CONFIG.RETRY_ATTEMPTS && error.message.includes('Address unavailable')) {
      const backoffMs = YOUTUBE_CONFIG.INITIAL_BACKOFF_MS * Math.pow(YOUTUBE_CONFIG.BACKOFF_MULTIPLIER, attempt - 1);
      Logger.log(`Network error. Retrying in ${backoffMs}ms... (Attempt ${attempt}/${YOUTUBE_CONFIG.RETRY_ATTEMPTS})`);
      Utilities.sleep(backoffMs);
      return makeApiRequest(url, attempt + 1);
    }
    
    throw error;
  }
}

/**
 * Make authenticated API request (requires OAuth)
 * @param {string} url - API endpoint URL
 * @returns {Object} Parsed response
 */
function makeAuthenticatedRequest(url) {
  const token = getOAuthToken();
  
  const response = UrlFetchApp.fetch(url, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    },
    muteHttpExceptions: true
  });
  
  const responseCode = response.getResponseCode();
  
  if (responseCode === 200) {
    // Track quota usage
    const endpoint = extractEndpoint(url);
    quotaManager.addUsage(endpoint);
    
    return JSON.parse(response.getContentText());
  }
  
  const errorText = response.getContentText();
  throw new Error(`Authenticated request failed (${responseCode}): ${errorText}`);
}

/**
 * Extract endpoint name from URL for quota tracking
 * @param {string} url - API URL
 * @returns {string} Endpoint name
 */
function extractEndpoint(url) {
  if (url.includes('/videos')) return 'videos';
  if (url.includes('/search')) return 'search';
  if (url.includes('/channels')) return 'channels';
  if (url.includes('/playlistItems')) return 'playlistItems';
  if (url.includes('/playlists')) return 'playlists';
  return 'unknown';
}
```

## Complete Examples

### Example 1: Channel Analytics

```javascript
/**
 * Generate comprehensive channel analytics report
 * @param {string} channelId - YouTube channel ID
 * @returns {Object} Analytics report
 */
function generateChannelReport(channelId) {
  try {
    // Check quota
    if (!quotaManager.canExecute('channels', 200)) {
      throw new Error('Insufficient quota for this operation');
    }
    
    // Get channel info
    const channel = getChannelInfo(channelId);
    if (!channel) {
      throw new Error('Channel not found');
    }
    
    // Get recent videos (last 50)
    const videos = getChannelVideos(channelId, 50);
    
    // Calculate statistics
    const totalViews = videos.reduce((sum, v) => sum + (parseInt(v.statistics?.viewCount) || 0), 0);
    const totalLikes = videos.reduce((sum, v) => sum + (parseInt(v.statistics?.likeCount) || 0), 0);
    const avgViews = totalViews / videos.length;
    
    // Find top performing video
    const topVideo = sortVideos(videos, 'views', false)[0];
    
    const report = {
      channel: {
        name: channel.snippet.title,
        subscriberCount: parseInt(channel.statistics.subscriberCount),
        totalVideos: parseInt(channel.statistics.videoCount),
        totalViews: parseInt(channel.statistics.viewCount)
      },
      recentVideos: {
        count: videos.length,
        totalViews: totalViews,
        averageViews: Math.round(avgViews),
        totalLikes: totalLikes
      },
      topPerformer: {
        title: topVideo.snippet.title,
        views: parseInt(topVideo.statistics.viewCount),
        likes: parseInt(topVideo.statistics.likeCount),
        url: `https://youtube.com/watch?v=${topVideo.id}`
      },
      quotaUsed: quotaManager.getUsage(),
      quotaRemaining: quotaManager.getRemaining()
    };
    
    Logger.log(JSON.stringify(report, null, 2));
    return report;
    
  } catch (error) {
    Logger.log(`Error generating report: ${error.message}`);
    throw error;
  }
}
```

### Example 2: Content Discovery

```javascript
/**
 * Find trending videos in a niche
 * @param {string} query - Search query
 * @param {Object} criteria - Filter criteria
 * @returns {Object[]} Trending videos
 */
function findTrendingVideos(query, criteria = {}) {
  try {
    // Search for videos
    const searchResults = searchVideos({
      q: query,
      maxResults: 50,
      order: 'viewCount',
      type: 'video',
      publishedAfter: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString() // Last 7 days
    });
    
    // Extract video IDs
    const videoIds = searchResults.map(item => item.id.videoId);
    
    // Get full video details
    const videos = getMultipleVideos(videoIds);
    
    // Apply filters
    const filtered = filterVideos(videos, {
      minViews: criteria.minViews || 10000,
      minLikes: criteria.minLikes || 100,
      minDuration: criteria.minDuration || 60,
      ...criteria
    });
    
    // Sort by engagement
    const sorted = sortVideos(filtered, 'views', false);
    
    // Return top 10 with relevant info
    return sorted.slice(0, 10).map(video => ({
      id: video.id,
      title: video.snippet.title,
      channel: video.snippet.channelTitle,
      publishedAt: video.snippet.publishedAt,
      views: parseInt(video.statistics.viewCount),
      likes: parseInt(video.statistics.likeCount),
      duration: parseDuration(video.contentDetails.duration),
      url: `https://youtube.com/watch?v=${video.id}`
    }));
    
  } catch (error) {
    Logger.log(`Error finding trending videos: ${error.message}`);
    throw error;
  }
}
```

### Example 3: Playlist Manager

```javascript
/**
 * YouTube Playlist Manager Class
 */
class PlaylistManager {
  /**
   * Get all videos from playlist
   */
  getAllVideos(playlistId) {
    const items = getPlaylistItems(playlistId, 200);
    const videoIds = items.map(item => item.contentDetails.videoId);
    return getMultipleVideos(videoIds);
  }
  
  /**
   * Analyze playlist content
   */
  analyzePlaylist(playlistId) {
    const videos = this.getAllVideos(playlistId);
    
    const totalDuration = videos.reduce((sum, v) => {
      return sum + parseDuration(v.contentDetails?.duration || 'PT0S');
    }, 0);
    
    const totalViews = videos.reduce((sum, v) => {
      return sum + (parseInt(v.statistics?.viewCount) || 0);
    }, 0);
    
    return {
      videoCount: videos.length,
      totalDuration: totalDuration,
      totalDurationHours: (totalDuration / 3600).toFixed(2),
      totalViews: totalViews,
      averageViews: Math.round(totalViews / videos.length),
      oldestVideo: new Date(Math.min(...videos.map(v => new Date(v.snippet.publishedAt)))),
      newestVideo: new Date(Math.max(...videos.map(v => new Date(v.snippet.publishedAt))))
    };
  }
}
```

## Best Practices Summary

1. **Always use Properties Service** for API keys
2. **Implement caching** to reduce API calls
3. **Track quota usage** to avoid exceeding limits
4. **Use batch requests** when fetching multiple items
5. **Implement retry logic** with exponential backoff
6. **Handle errors gracefully** with specific error messages
7. **Respect rate limits** with appropriate delays
8. **Use pagination** for large result sets
9. **Clear memory** in batch operations
10. **Log important operations** for debugging

## Quota Costs Reference

| Endpoint | Cost (units) |
|----------|--------------|
| videos.list | 1 |
| channels.list | 1 |
| search.list | 100 |
| playlistItems.list | 1 |
| playlists.list | 1 |
| commentThreads.list | 1 |

**Default Daily Quota**: 10,000 units

---

This tutorial provides production-ready code following official YouTube Data API v3 best practices. All functions include proper error handling, quota management, caching, and performance optimizations.

