# FFmpeg Multimedia Converter Guide

This comprehensive guide covers everything from basic FFmpeg commands and video/audio manipulation to hardware-accelerated transcoding, high-quality GIF generation, subtitle management, and batch processing scripts.

## Table of Contents
1. [Introduction to FFmpeg](#introduction-to-ffmpeg)
2. [Installation and Setup](#installation-and-setup)
3. [Basic Command Syntax & Core Concepts](#basic-command-syntax-core-concepts)
4. [Quick Copy-and-Paste Cheat Sheet](#quick-copy-and-paste-cheat-sheet)
5. [Video Processing & Compression](#video-processing-compression)
6. [High-Quality GIF Creation](#high-quality-gif-creation)
7. [Audio Processing & Manipulation](#audio-processing-manipulation)
8. [Subtitles Handling](#subtitles-handling)
9. [Hardware Acceleration](#hardware-acceleration)
10. [Video Editing & Filter Graphs](#video-editing-filter-graphs)
11. [Batch & Directory Processing](#batch-directory-processing)
12. [Web Streaming & Screen Capture](#web-streaming-screen-capture)

---

## Introduction to FFmpeg

FFmpeg is the industry-standard multimedia framework capable of decoding, encoding, transcoding, muxing, demuxing, streaming, filtering, and playing almost any audio or video format.

```bash
# Basic FFmpeg command structure
ffmpeg [global_options] [input_file_options] -i input.mp4 [output_file_options] output.mp4
```

---

## Installation and Setup

### macOS (Homebrew)
```bash
# Install ffmpeg with full library support
brew install ffmpeg
```

### Ubuntu / Debian
```bash
sudo apt update
sudo apt install ffmpeg ffprobe
```

### Windows (PowerShell / Chocolatey or Winget)
```powershell
# Using Winget
winget install FFmpeg.FFmpeg

# Or using Chocolatey
choco install ffmpeg
```

---

## Basic Command Syntax & Core Concepts

### Inspecting Media Metadata with `ffprobe`

```bash
# Display basic video format and stream info
ffprobe input.mp4

# Return concise format/codec info in JSON format
ffprobe -v quiet -print_format json -show_format -show_streams input.mp4

# Print exact duration in seconds
ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 input.mp4

# Check video resolution (width x height)
ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 input.mp4
```

---

## Quick Copy-and-Paste Cheat Sheet

Below are the most common tasks performed with FFmpeg ready for immediate copy and paste:

```bash
# 1. Fast trim video without re-encoding (30s duration starting at 01:15)
ffmpeg -ss 00:01:15 -i input.mp4 -to 00:01:45 -c copy cut_output.mp4

# 2. Compress video to target H.264 (CRF 23 = good balance of file size & quality)
ffmpeg -i input.mp4 -c:v libx264 -crf 23 -c:a aac -b:a 128k output.mp4

# 3. Extract MP3 audio from MP4 video
ffmpeg -i input.mp4 -vn -c:a libmp3lame -q:a 2 audio.mp3

# 4. Convert video to high-quality animated GIF
ffmpeg -i input.mp4 -vf "fps=15,scale=480:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" animation.gif

# 5. Extract a single full-resolution frame image at timestamp 00:02:30
ffmpeg -ss 00:02:30 -i input.mp4 -vframes 1 snapshot.jpg

# 6. Remove audio track from video (mute)
ffmpeg -i input.mp4 -an -c:v copy muted.mp4

# 7. Merge external audio track into video (replacing original audio)
ffmpeg -i input.mp4 -i new_audio.mp3 -c:v copy -c:a aac -map 0:v:0 -map 1:a:0 output.mp4

# 8. Burn subtitles (.srt) directly into video (hardsub)
ffmpeg -i input.mp4 -vf "subtitles=subtitles.srt" -c:a copy hardsubbed.mp4

# 9. Resize video to 1080p maintaining aspect ratio
ffmpeg -i input.mp4 -vf "scale=1920:-2" -c:a copy output_1080p.mp4

# 10. Combine audio track + still image to create YouTube video
ffmpeg -loop 1 -i cover.jpg -i podcast.mp3 -c:v libx264 -tune stillimage -c:a copy -shortest video.mp4
```

---

## Video Processing & Compression

### Constant Rate Factor (CRF) Quality Encoding

CRF sets constant quality where lower numbers mean higher quality and larger file size.
- **H.264 CRF scale**: 0 (lossless) to 51 (worst). Recommended range: **18–28** (default 23).
- **H.265 (HEVC) CRF scale**: Recommended range: **20–24** (default 28).
- **AV1 CRF scale**: Recommended range: **24–34**.

```bash
# H.264 high quality conversion (CRF 18)
ffmpeg -i input.mp4 -c:v libx264 -crf 18 -preset slow -c:a aac output.mp4

# Modern H.265 (HEVC) encoding for 50% smaller size at equal visual quality
ffmpeg -i input.mp4 -c:v libx265 -crf 24 -preset medium -c:a aac output_hevc.mp4

# Next-gen SVT-AV1 encoding (Open source standard)
ffmpeg -i input.mp4 -c:v libsvtav1 -crf 30 -preset 5 -c:a libopus output.mkv
```

### Video Scaling and Resolution Adjustment

> **Tip**: Use `-2` instead of `-1` for width or height when scaling. H.264/H.265 codecs require even pixel dimensions.

```bash
# Resize to 720p height, proportional width (even dimension safe)
ffmpeg -i input.mp4 -vf "scale=-2:720" output_720p.mp4

# Scale video to half size
ffmpeg -i input.mp4 -vf "scale=iw/2:ih/2" half_size.mp4

# Upscale or downscale to exact box while maintaining aspect ratio (letterbox black bars)
ffmpeg -i input.mp4 -vf "scale=1920:1080:force_original_aspect_ratio=decrease,pad=1920:1080:(ow-iw)/2:(oh-ih)/2" fitted_1080p.mp4
```

### Speeding Up or Slowing Down Video & Audio

```bash
# 2x Speed up (setpts for video, atempo for audio)
ffmpeg -i input.mp4 -filter_complex "[0:v]setpts=0.5*PTS[v];[0:a]atempo=2.0[a]" -map "[v]" -map "[a]" 2x_fast.mp4

# 0.5x Slow motion (double duration)
ffmpeg -i input.mp4 -filter_complex "[0:v]setpts=2.0*PTS[v];[0:a]atempo=0.5[a]" -map "[v]" -map "[a]" slow_mo.mp4
```

---

## High-Quality GIF Creation

Default GIF encoding produces low quality and large files. Utilizing a two-pass palette generator (`palettegen` / `paletteuse`) yields crisp colors and small file sizes:

```bash
# High-quality animated GIF from video snippet (FPS=15, Width=480px)
ffmpeg -ss 00:00:10 -to 00:00:15 -i input.mp4 \
  -vf "fps=15,scale=480:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" \
  output.gif

# High-quality GIF with custom color palette deduplication (for screen recordings)
ffmpeg -i screen_record.mp4 \
  -vf "fps=10,scale=800:-1:flags=lanczos,split[s0][s1];[s0]palettegen=stats_mode=diff[p];[s1][p]paletteuse=dither=bayer:bayer_scale=5" \
  demo.gif
```

---

## Audio Processing & Manipulation

### Volume Adjustment and Loudness Normalization

```bash
# Double audio volume (+6dB)
ffmpeg -i input.mp4 -af "volume=2.0" -c:v copy louder.mp4

# Decrease volume by 50%
ffmpeg -i input.mp4 -af "volume=0.5" -c:v copy quieter.mp4

# Normalize audio volume to EBU R128 standard (-16 LUFS, perfect for podcasts & YouTube)
ffmpeg -i input.mp3 -af loudnorm=I=-16:TP=-1.5:LRA=11 normalized.mp3
```

### Audio Track Operations & Delay Fixes

```bash
# Convert 5.1 Surround Sound down to Stereo (2-channel)
ffmpeg -i input_51.mp4 -c:v copy -ac 2 stereo_output.mp4

# Delay audio by 1.5 seconds relative to video (fix audio sync lag)
ffmpeg -i input.mp4 -itsoffset 1.5 -i input.mp4 -map 0:v -map 1:a -c copy fixed_sync.mp4

# Fade audio in (first 3 sec) and out (last 3 sec of 60s video)
ffmpeg -i input.wav -af "afade=t=in:ss=0:d=3,afade=t=out:st=57:d=3" faded.wav
```

---

## Subtitles Handling

### Soft Subtitles vs. Hard Subtitles

```bash
# Embed soft subtitles into MP4 container (can be toggled on/off in media player)
ffmpeg -i input.mp4 -i subtitles.srt -c copy -c:s mov_text output.mp4

# Embed soft subtitles into MKV container
ffmpeg -i input.mkv -i subtitles.srt -c copy -c:s srt output.mkv

# Burn subtitles permanently into video stream (Hardsub)
ffmpeg -i input.mp4 -vf "subtitles=subtitles.srt:force_style='FontSize=20,PrimaryColour=&H00FFFFFF'" -c:a copy hardsub.mp4

# Extract subtitle stream from video file to SRT
ffmpeg -i input.mkv -map 0:s:0 subtitles.srt
```

---

## Hardware Acceleration

Hardware acceleration offloads video encoding to GPU integrated units, processing videos up to 10x–20x faster.

### Apple Silicon (macOS M1/M2/M3/M4 VideoToolbox)
```bash
# Hardware accelerated H.264 encoding on Mac
ffmpeg -i input.mp4 -c:v h264_videotoolbox -b:v 5M -c:a aac mac_h264.mp4

# Hardware accelerated H.265 (HEVC) encoding on Mac
ffmpeg -i input.mp4 -c:v hevc_videotoolbox -b:v 3.5M -c:a aac mac_hevc.mp4
```

### NVIDIA GPUs (NVENC)
```bash
# H.264 encoding via NVENC
ffmpeg -i input.mp4 -c:v h264_nvenc -preset p4 -cq 23 -c:a aac nvenc_h264.mp4

# HEVC / H.265 encoding via NVENC
ffmpeg -i input.mp4 -c:v hevc_nvenc -preset p4 -cq 26 -c:a aac nvenc_hevc.mp4
```

### Intel QuickSync & Linux VAAPI
```bash
# Intel QuickSync (QSV)
ffmpeg -i input.mp4 -c:v h264_qsv -global_quality 23 qsv_output.mp4

# Linux VAAPI hardware encoding
ffmpeg -vaapi_device /dev/dri/renderD128 -i input.mp4 -vf 'format=nv12,hwupload' -c:v h264_vaapi output.mp4
```

---

## Video Editing & Filter Graphs

### Cropping and Watermarking

```bash
# Crop video: crop=width:height:x:y (800x600 box starting at X=100, Y=50)
ffmpeg -i input.mp4 -vf "crop=800:600:100:50" cropped.mp4

# Add image watermark to top-right corner with 10px margin
ffmpeg -i video.mp4 -i logo.png -filter_complex "[0:v][1:v]overlay=main_w-overlay_w-10:10" watermarked.mp4
```

### Side-by-Side & Grid Video Stacking

```bash
# Stack two videos side-by-side horizontally
ffmpeg -i video1.mp4 -i video2.mp4 -filter_complex hstack side_by_side.mp4

# Stack two videos vertically
ffmpeg -i top.mp4 -i bottom.mp4 -filter_complex vstack stacked.mp4

# Create 2x2 grid preview from 4 videos
ffmpeg -i 1.mp4 -i 2.mp4 -i 3.mp4 -i 4.mp4 \
  -filter_complex "[0:v][1:v]hstack[top];[2:v][3:v]hstack[bottom];[top][bottom]vstack" \
  grid_2x2.mp4
```

### Video Concatenation (Joining Videos)

To join multiple videos without re-encoding (all clips must have matching resolution, codecs, and framerate):

```bash
# 1. Create a file list text file
echo "file 'part1.mp4'" > filelist.txt
echo "file 'part2.mp4'" >> filelist.txt
echo "file 'part3.mp4'" >> filelist.txt

# 2. Run concat demuxer
ffmpeg -f concat -safe 0 -i filelist.txt -c copy joined_output.mp4
```

---

## Batch & Directory Processing

### Flat Directory Transcoding

```bash
# Convert all .mov files to .mp4 preserving filenames in current directory
for f in *.mov; do
  [ -f "$f" ] || continue
  ffmpeg -i "$f" -c:v libx264 -crf 22 -c:a aac "${f%.mov}.mp4"
done

# Extract audio from all MP4 videos into MP3
for v in *.mp4; do
  [ -f "$v" ] || continue
  ffmpeg -i "$v" -vn -c:a libmp3lame -q:a 2 "audio_${v%.*}.mp3"
done
```

### Recursive Subfolder Transcoding

```bash
#!/bin/bash
# Walk nested video folders, transcode to destination folder maintaining hierarchy

SRC_DIR="raw_library"
DEST_DIR="compressed_library"

find "$SRC_DIR" -type f \( -name "*.mp4" -o -name "*.mov" -o -name "*.mkv" \) | while read -r input_file; do
  rel_path="${input_file#$SRC_DIR/}"
  output_file="$DEST_DIR/${rel_path%.*}.mp4"

  mkdir -p "$(dirname "$output_file")"
  echo "Transcoding: $input_file -> $output_file"

  ffmpeg -y -i "$input_file" -c:v libx264 -crf 23 -c:a aac "$output_file"
done
```

### Parallel Batch Execution with `xargs` or `fd`

```bash
# Parallel processing across 4 CPU cores using xargs
find . -maxdepth 1 -name "*.mkv" -print0 | xargs -0 -P 4 -I {} sh -c '
  ffmpeg -y -i "$1" -c:v libx264 -crf 23 -c:a copy "${1%.*}.mp4"
' _ {}

# Fast parallel batch processing using fd
fd -e mov -x ffmpeg -y -i {} -vf "scale=1280:-2" -c:v libx264 -crf 24 -c:a copy {.}_720p.mp4
```

---

## Web Streaming & Screen Capture

### Web Optimization & HLS Playlist Generation

```bash
# Optimize MP4 for instant web video playback (moves metadata to start of file)
ffmpeg -i input.mp4 -c copy -movflags +faststart web_ready.mp4

# Create HTTP Live Streaming (HLS) m3u8 playlist and TS segments for Web Players
ffmpeg -i input.mp4 -c:v libx264 -c:a aac -hls_time 6 -hls_playlist_type vod stream.m3u8
```

### Desktop Screen Recording

```bash
# macOS Screen Capture (AVFoundation - Screen 1, Audio Device 0)
ffmpeg -f avfoundation -capture_cursor 1 -i "1:0" -c:v libx264 -pix_fmt yuv420p desktop_rec.mp4

# Linux Desktop Screen Recording (X11grab at 30 FPS)
ffmpeg -f x11grab -video_size 1920x1080 -framerate 30 -i :0.0 -c:v libx264 -preset ultrafast desktop_linux.mp4

# Windows Screen Recording (GDIGrab)
ffmpeg -f gdigrab -framerate 30 -i desktop -c:v libx264 -preset ultrafast desktop_win.mp4
```


