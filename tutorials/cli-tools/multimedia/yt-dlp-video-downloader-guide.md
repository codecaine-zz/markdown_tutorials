# yt-dlp Video & Audio Downloader Guide

`yt-dlp` is a feature-rich, high-performance command-line audio and video downloader. A modern fork of `youtube-dl`, it supports thousands of sites, multi-threaded downloads, advanced format selection, subtitle extraction, and seamless FFmpeg post-processing.

---

## 📚 Table of Contents

1. [Overview & Prerequisites](#overview-prerequisites)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Basic Downloading & Output Templates](#basic-downloading-output-templates)
4. [Format Selection & Resolution Limits](#format-selection-resolution-limits)
5. [Audio Extraction & Transcoding](#audio-extraction-transcoding)
6. [Playlist & Channel Downloads](#playlist-channel-downloads)
7. [Subtitles, Thumbnails & Metadata](#subtitles-thumbnails-metadata)
8. [Browser Cookies & Authentication](#browser-cookies-authentication)
9. [Configuration File (`config`)](#configuration-file-config)
10. [Prevent Re-Downloading (Archive File)](#prevent-re-downloading-archive-file)
11. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Prerequisites

`yt-dlp` requires **FFmpeg** to merge separate video and audio streams (e.g. 1080p/4K video + high quality audio) and convert media formats.

```bash
# Verify FFmpeg presence
ffmpeg -version
```

---

## ⚙️ Installation via Homebrew

```bash
# Install yt-dlp along with FFmpeg dependency
brew install yt-dlp ffmpeg

# Verify installation and update to latest nightly build
yt-dlp --version
yt-dlp -U
```

---

## 🚀 Basic Downloading & Output Templates

### 1. Download Single Video
```bash
# Download best available combined format
yt-dlp "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
```

### 2. Custom Output File Naming (`-o`)
```bash
# Save into a specific folder with Title (Resolution).ext
yt-dlp -o "~/Downloads/%(title)s (%(height)sp).%(ext)s" "URL"

# Sanitize filenames (replace spaces and special characters)
yt-dlp --restrict-filenames -o "%(title)s.%(ext)s" "URL"
```

---

## 🎛️ Format Selection & Resolution Limits

### 1. Inspect Available Formats (`-F`)
```bash
# List available video and audio streams with IDs
yt-dlp -F "URL"
```

### 2. Download Specific Format Combination (`-f`)
```bash
# Download specific video ID + audio ID and merge into MP4
yt-dlp -f 137+140 --merge-output-format mp4 "URL"
```

### 3. Limit Maximum Resolution
```bash
# Max 1080p video + best audio
yt-dlp -f "bv*[height<=1080]+ba/b[height<=1080]" --merge-output-format mp4 "URL"

# Max 720p 60fps video
yt-dlp -f "bv*[height<=720][fps<=60]+ba/b" "URL"
```

---

## 🎵 Audio Extraction & Transcoding

Extract high-fidelity audio tracks without re-encoding, or transcode into MP3/AAC/FLAC using FFmpeg.

### 1. Best Quality Audio (No Re-encoding)
```bash
# Extract best audio stream in native format (m4a/opus)
yt-dlp -x "URL"
```

### 2. Transcode to MP3 with Metadata & Thumbnail
```bash
# Extract and transcode to 320kbps MP3
yt-dlp -x --audio-format mp3 --audio-quality 0 --embed-thumbnail --add-metadata "URL"
```

### 3. Convert to FLAC or WAV
```bash
# Lossless conversion
yt-dlp -x --audio-format flac "URL"
```

---

## 📺 Playlist & Channel Downloads

### 1. Download Entire Playlist with Indexing
```bash
# Save files as 01 - Title.mp4, 02 - Title.mp4
yt-dlp -o "%(playlist_title)s/%(playlist_index)02d - %(title)s.%(ext)s" "PLAYLIST_URL"
```

### 2. Download Range of Items from Playlist
```bash
# Download videos 1 to 5 from playlist
yt-dlp --playlist-start 1 --playlist-end 5 "PLAYLIST_URL"
```

---

## 📝 Subtitles, Thumbnails & Metadata

```bash
# Download English subtitles and convert to SRT format
yt-dlp --write-subs --sub-langs "en.*" --convert-subs srt "URL"

# Embed subtitles into video container (MKV/MP4)
yt-dlp --embed-subs --sub-langs "en" "URL"

# Embed video thumbnail as cover art
yt-dlp --embed-thumbnail --add-metadata "URL"
```

---

## 🔐 Browser Cookies & Authentication

Bypass age restrictions or download private content using browser cookies.

```bash
# Extract cookies from Safari
yt-dlp --cookies-from-browser safari "URL"

# Extract cookies from Chrome
yt-dlp --cookies-from-browser chrome "URL"

# Extract cookies from Firefox
yt-dlp --cookies-from-browser firefox "URL"
```

---

## ⚙️ Configuration File (`config`)

Avoid typing repetitive command line arguments by establishing a global configuration file at `~/.config/yt-dlp/config`.

```bash
mkdir -p ~/.config/yt-dlp
cat << 'EOF' > ~/.config/yt-dlp/config
# Global yt-dlp Configuration
-o ~/Downloads/%(title)s [%(id)s].%(ext)s
-f "bv*[height<=1080]+ba/b"
--merge-output-format mp4
--embed-thumbnail
--add-metadata
--restrict-filenames
--concurrent-fragments 4
EOF
```

---

## 🗄️ Prevent Re-Downloading (Archive File)

Keep track of downloaded video IDs to safely update channel mirrors without re-downloading existing content.

```bash
# Record downloaded IDs into download_history.txt
yt-dlp --download-archive ~/Downloads/download_history.txt "CHANNEL_URL"
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Download Video | `yt-dlp "URL"` |
| List Formats | `yt-dlp -F "URL"` |
| Download 1080p MP4 | `yt-dlp -f "bv*[height<=1080]+ba/b" --merge-output-format mp4 "URL"` |
| Extract MP3 Audio | `yt-dlp -x --audio-format mp3 --audio-quality 0 "URL"` |
| Embed Thumbnail/Meta | `yt-dlp --embed-thumbnail --add-metadata "URL"` |
| Extract Chrome Cookies| `yt-dlp --cookies-from-browser chrome "URL"` |
| Use Archive Record | `yt-dlp --download-archive archive.txt "URL"` |
