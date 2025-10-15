## Core FFmpeg Operations

### Basic Video Processing
- **Cutting videos

This comprehensive guide covers everything from basic FFmpeg commands to advanced video processing techniques.

## Table of Contents
1. [Introduction to FFmpeg](#introduction-to-ffmpeg)
2. [Basic Installation and Setup](#basic-installation-and-setup)
3. [Core FFmpeg Concepts](#core-ffmpeg-concepts)
4. [Video Processing Basics](#video-processing-basics)
5. [Audio Processing](#audio-processing)
6. [Format Conversion](#format-conversion)
7. [Advanced Video Manipulation](#advanced-video-manipulation)
8. [Streaming and Network Operations](#streaming-and-network-operations)
9. [Filtering and Effects](#filtering-and-effects)
10. [Performance Optimization](#performance-optimization)

## Introduction to FFmpeg

FFmpeg is a powerful multimedia framework that can decode, encode, transcode, mux, demux, stream, filter, and play almost any media format.

```bash
# Basic FFmpeg command structure
ffmpeg [global_options] [input_file_options] -i input_file [output_file_options] output_file
```

## Basic Installation and Setup

### On Ubuntu/Debian:
```bash
sudo apt update
sudo apt install ffmpeg ffprobe
```

### On macOS with Homebrew:
```bash
brew install ffmpeg
```

### On Windows:
Download from https://ffmpeg.org/download.html or use Chocolatey:
```powershell
choco install ffmpeg
```

## Core FFmpeg Concepts

### Understanding FFmpeg Options

```bash
# View all available options
ffmpeg -h

# View specific filter help
ffmpeg -h filter=scale

# List all formats
ffmpeg -formats

# List all codecs
ffmpeg -codecs

# Get detailed information about a file
ffprobe input.mp4
```

### Basic Command Structure Examples

```bash
# Simple conversion (no quality change)
ffmpeg -i input.mp4 output.avi

# Simple copy without re-encoding
ffmpeg -i input.mp4 -c copy output.mp4

# Show progress during processing
ffmpeg -i input.mp4 -progress progress.txt output.mp4
```

## Video Processing Basics

### Basic Video Information Extraction

```bash
# Get detailed information about a video file
ffprobe -v quiet -show_format -show_streams input.mp4

# Extract video stream info only
ffprobe -v quiet -show_streams -select_streams v:0 input.mp4

# Get frame rate and resolution
ffmpeg -i input.mp4 2>&1 | grep "Stream.*Video"

# Get duration of video
ffprobe -v error -show_entries format=duration -of default=nw=1 input.mp4
```

### Video Quality Control

```bash
# Convert with specific quality (CRF method)
ffmpeg -i input.mp4 -crf 23 output.mp4

# Set bitrate for video
ffmpeg -i input.mp4 -b:v 1000k output.mp4

# Combine bitrate and CRF (more control)
ffmpeg -i input.mp4 -b:v 1000k -crf 23 output.mp4

# Variable Bitrate (VBR) with quality
ffmpeg -i input.mp4 -qscale:v 2 output.mp4
```

### Video Resolution and Scaling

```bash
# Resize video to specific dimensions
ffmpeg -i input.mp4 -vf scale=1920:1080 output.mp4

# Scale to width only (maintain aspect ratio)
ffmpeg -i input.mp4 -vf scale=1280:-1 output.mp4

# Scale to height only (maintain aspect ratio)
ffmpeg -i input.mp4 -vf scale=-1:720 output.mp4

# Resize with specific quality
ffmpeg -i input.mp4 -vf "scale=1920:1080,eq=brightness=0.1" output.mp4

# Scale to percentage of original size
ffmpeg -i input.mp4 -vf scale=iw*0.5:-1 output.mp4
```

### Frame Rate Manipulation

```bash
# Change frame rate (from 25fps to 30fps)
ffmpeg -i input.mp4 -r 30 output.mp4

# Convert video to 60 fps using interpolation
ffmpeg -i input.mp4 -filter:v "minterpolate='mi_mode=mci:mc_mode=aobmc'" output.mp4

# Slow down video by half speed
ffmpeg -i input.mp4 -filter:v "setpts=2.0*PTS" slow_output.mp4

# Speed up video by 1.5x
ffmpeg -i input.mp4 -filter:v "setpts=0.667*PTS" fast_output.mp4
```

## Audio Processing

### Audio Extraction and Conversion

```bash
# Extract audio from video
ffmpeg -i input.mp4 audio.aac

# Extract audio in different format (MP3)
ffmpeg -i input.mp4 -vn -ar 44100 -ac 2 -ab 192k output.mp3

# Extract only the first channel
ffmpeg -i input.mp4 -vn -acodec copy output.aac

# Convert audio to WAV format with specific parameters
ffmpeg -i input.mp3 -acodec pcm_s16le output.wav
```

### Audio Volume Control

```bash
# Increase volume by 20%
ffmpeg -i input.mp4 -af "volume=1.2" output.mp4

# Decrease volume by 50%
ffmpeg -i input.mp4 -af "volume=0.5" output.mp4

# Normalize audio level
ffmpeg -i input.wav -af loudnorm=I=-16:TP=-1.5:LRA=11 output.wav

# Set specific volume (in dB)
ffmpeg -i input.mp4 -af "volume=3dB" output.mp4
```

### Audio Mixing and Processing

```bash
# Merge multiple audio streams
ffmpeg -i video.mp4 -i audio.wav -map 0:v -map 1:a output.mp4

# Add background music to video (with volume control)
ffmpeg -i input_video.mp4 -i background_music.mp3 \
       -filter_complex "[0:a][1:a]amerge=inputs=2[a]" \
       -map 0:v -map [a] output_with_music.mp4

# Create audio fade in/out
ffmpeg -i input.wav -af "afade=t=in:ss=0:duration=5,afade=t=out:st=10:duration=3" output.wav
```

## Format Conversion

### Common Format Conversions

```bash
# MP4 to AVI conversion
ffmpeg -i input.mp4 output.avi

# Convert to WebM format (for web use)
ffmpeg -i input.mp4 -c:v libvpx-vp9 -crf 30 -b:v 0 output.webm

# Convert to MOV format
ffmpeg -i input.mp4 output.mov

# Convert MP3 to WAV
ffmpeg -i input.mp3 output.wav

# Convert video to GIF (with frame rate)
ffmpeg -i input.mp4 -r 15 output.gif

# Create animated GIF from still images
ffmpeg -f image2 -i img%03d.jpg animation.gif
```

### Container Format Optimization

```bash
# Optimize MP4 for web streaming
ffmpeg -i input.mp4 -movflags +faststart output.mp4

# Convert to H.265 (HEVC) format
ffmpeg -i input.mp4 -c:v libx265 output.mp4

# Convert to H.264 with specific profile and level
ffmpeg -i input.mp4 -c:v libx264 -profile:v baseline \
       -level 3.0 -crf 18 output.mp4
```

## Advanced Video Manipulation

### Cropping and Padding

```bash
# Crop video (start at x=100, y=50, width=800, height=600)
ffmpeg -i input.mp4 -vf "crop=800:600:100:50" output.mp4

# Add padding to video
ffmpeg -i input.mp4 -vf "pad=width=1920:height=1080:x=0:y=0:color=black" output.mp4

# Crop and scale simultaneously
ffmpeg -i input.mp4 -vf "crop=640:360, scale=1280:720" output.mp4
```

### Video Rotation and Flip

```bash
# Rotate video 90 degrees clockwise
ffmpeg -i input.mp4 -vf "transpose=1" output.mp4

# Rotate 180 degrees
ffmpeg -i input.mp4 -vf "transpose=2,transpose=2" output.mp4

# Flip horizontally
ffmpeg -i input.mp4 -vf "hflip" output.mp4

# Flip vertically
ffmpeg -i input.mp4 -vf "vflip" output.mp4

# Rotate 90 degrees counter-clockwise and flip
ffmpeg -i input.mp4 -vf "transpose=2" output.mp4
```

### Multiple Video Processing Operations

```bash
# Chain multiple video operations together
ffmpeg -i input.mp4 \
       -vf "scale=1280:720,eq=brightness=0.1,crop=640:360" \
       -c:a copy output.mp4

# Add watermark (image overlay)
ffmpeg -i input.mp4 -i watermark.png \
       -filter_complex "[0:v][1:v]overlay=10:10" \
       output_with_watermark.mp4
```

## Time-based Operations

### Video Cutting and Concatenation

```bash
# Cut video from 30s to 60s
ffmpeg -i input.mp4 -ss 30 -to 60 -c copy cut_video.mp4

# Extract specific time range with re-encoding (for better quality)
ffmpeg -i input.mp4 -ss 30 -to 60 -c:v libx264 output.mp4

# Concatenate multiple videos
ffmpeg -f concat -safe 0 -i list.txt output.mp4

# Create list file for concatenation (create a text file with entries like):
echo "file 'video1.mp4'" > list.txt
echo "file 'video2.mp4'" >> list.txt
```

### Time-lapse Creation

```bash
# Create time-lapse video from images (at 30fps)
ffmpeg -r 1/5 -i img%03d.jpg -c:v libx264 timelapse.mp4

# Create time-lapse with specific frame rate
ffmpeg -f image2 -framerate 24 -i %04d.jpg output.mp4

# Adjust speed of existing video to create time-lapse effect
ffmpeg -i input.mp4 -vf "setpts=0.5*PTS" output_timelapse.mp4
```

## Preset and Quality Settings

### High Quality Encoding

```bash
# Encode with high quality (lossless)
ffmpeg -i input.mp4 -c:v libx264 -preset slow -crf 18 output.mp4

# Ultra-high quality H.265 encoding
ffmpeg -i input.mp4 -c:v libx265 -preset slow \
       -crf 18 output.mp4

# Encode with specific quality settings for web
ffmpeg -i input.mp4 -c:v libx264 -preset medium \
       -b:v 1000k -maxrate 1000k -bufsize 2000k output.mp4
```

### Batch Processing Examples

```bash
# Convert all MP4 files in directory to WebM
for file in *.mp4; do
    ffmpeg -i "$file" -c:v libvpx-vp9 -crf 30 "${file%.mp4}.webm"
done

# Process multiple videos with same parameters
ffmpeg -i input1.mp4 -c:v libx264 output1.mp4
ffmpeg -i input2.mp4 -c:v libx264 output2.mp4
```

## Practical Use Cases

### YouTube Optimization

```bash
# Create optimized video for YouTube (HD 1080p)
ffmpeg -i input.mp4 -c:v libx264 -profile:v baseline \
       -level 3.0 -crf 18 -preset slow \
       -c:a aac -b:a 192k output.mp4

# Create multiple resolutions for adaptive streaming
ffmpeg -i input.mp4 -c:v libx264 -preset medium \
       -b:v 5000k output_1080p.mp4
ffmpeg -i input.mp4 -c:v libx264 -preset medium \
       -b:v 3000k output_720p.mp4
```

### Audio Book Creation

```bash
# Create audio book from multiple audio files
ffmpeg -f concat -safe 0 -i audiobook_list.txt output.aac

# Add silence between tracks for better listening experience
ffmpeg -i input1.wav -af "silenceremove=start_periods=1:start_duration=0.2" \
       output_with_silence.wav
```

These examples demonstrate various advanced FFmpeg techniques that can be used for professional video and audio editing tasks, including quality optimization, format conversion, and complex processing operations. Each command can be modified based on specific requirements such as resolution, bitrates, codecs, and desired output formats.

