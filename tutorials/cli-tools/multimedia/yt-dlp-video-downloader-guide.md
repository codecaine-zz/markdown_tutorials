# yt-dlp with Homebrew: Practical Guide

## Table of Contents

1. [Overview](#overview)
2. [Install](#install)
3. [Basic Downloads](#basic-downloads)
4. [Format Selection](#format-selection)
5. [Playlists and Subtitles](#playlists-and-subtitles)
6. [Authentication](#authentication)
7. [Post-processing (FFmpeg)](#post-processing-ffmpeg)
8. [Config File and Cookies](#config-file-and-cookies)
9. [Troubleshooting](#troubleshooting)

## Overview

`yt-dlp` is a powerful video/audio downloader supporting many sites with frequent updates.

## Install

```bash
brew install yt-dlp ffmpeg
yt-dlp --version
```

## Basic Downloads

```bash
yt-dlp https://youtu.be/VIDEO_ID
# Output name template
yt-dlp -o '%(title)s.%(ext)s' URL
# Audio only (best)
yt-dlp -x --audio-format mp3 URL
```

## Format Selection

```bash
# List available formats
 yt-dlp -F URL
# Best video+audio merge
 yt-dlp -f 'bv*+ba/b' URL
# Limit resolution
 yt-dlp -f 'bv*[height<=1080]+ba/b[height<=1080]' URL
```

## Playlists and Subtitles

```bash
# Whole playlist
 yt-dlp -o '%(playlist_index)02d-%(title)s.%(ext)s' PLAYLIST_URL
# Subtitles
 yt-dlp --write-subs --sub-langs en --convert-subs srt URL
```

## Authentication

```bash
yt-dlp -u USER -p PASS URL
# Or use browser cookies
 yt-dlp --cookies-from-browser chrome URL
```

## Post-processing (FFmpeg)

```bash
# Convert to MP3 at 192kbps
 yt-dlp -x --audio-format mp3 --audio-quality 192K URL
# Embed thumbnail and metadata
 yt-dlp --embed-thumbnail --add-metadata URL
```

## Config File and Cookies

Create `~/.config/yt-dlp/config`:

```bash
mkdir -p ~/.config/yt-dlp
cat > ~/.config/yt-dlp/config << 'EOF'
# default options
-f bv*+ba/b
--merge-output-format mp4
--no-playlist
--restrict-filenames
--embed-thumbnail
--add-metadata
EOF
```

## Troubleshooting

- 403/DRM: some sites are protected; try cookies or supported extractors
- Rate limits: add `--limit-rate 2M` or `--sleep-requests 1`
- Merge errors: ensure `ffmpeg` is installed
