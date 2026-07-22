# LAME MP3 Audio Encoder Guide

`LAME` (LAME Ain't an MP3 Encoder) is an open-source, high-performance audio encoder used to convert uncompressed PCM audio (`.wav`, `.aiff`, `.flac`) into high-quality MPEG Audio Layer III (`.mp3`) files.

---

## 📚 Table of Contents

1. [Overview & Encoding Modes](#overview--encoding-modes)
2. [Installation via Homebrew](#installation-via-homebrew)
3. [Variable Bitrate (VBR) Encoding](#variable-bitrate-vbr-encoding)
4. [Constant Bitrate (CBR) Encoding](#constant-bitrate-cbr-encoding)
5. [Average Bitrate (ABR) & Mono Voice Encoding](#average-bitrate-abr--mono-voice-encoding)
6. [ID3 Tagging & Album Metadata](#id3-tagging--album-metadata)
7. [Batch Directory Conversion Script](#batch-directory-conversion-script)
8. [Integration with FFmpeg](#integration-with-ffmpeg)
9. [Cheat Sheet Summary](#cheat-sheet-summary)

---

## 🔍 Overview & Encoding Modes

- **VBR (Variable Bitrate)**: Adjusts bitrate dynamically based on audio complexity. Provides the best quality-to-size ratio.
- **CBR (Constant Bitrate)**: Uses a fixed bitrate (e.g. 320 kbps) throughout the entire file. Best for legacy hardware compatibility.
- **ABR (Average Bitrate)**: Targets a specified average bitrate while allowing subtle variations.

---

## ⚙️ Installation via Homebrew

```bash
# Install LAME along with audio tools on macOS
brew install lame sox ffmpeg

# Verify installation
lame --version
```

---

## 🚀 Variable Bitrate (VBR) Encoding

VBR mode is specified using the `-V` flag, with quality levels ranging from `-V0` (highest quality, ~245 kbps) to `-V9` (lowest quality, ~65 kbps).

### 1. Maximum Quality (`-V 0`)
```bash
# Highest VBR quality (transparent audio copy)
lame -V 0 input.wav output_v0.mp3
```

### 2. Standard Recommended Quality (`-V 2`)
```bash
# Standard target quality (~190 kbps average)
lame -V 2 input.wav output_v2.mp3
```

---

## 🎛️ Constant Bitrate (CBR) Encoding

Use `-b` to force a fixed bitrate (64, 128, 192, 256, 320 kbps).

```bash
# Encode at maximum 320 kbps CBR
lame -b 320 input.wav output_320kbps.mp3

# Encode at 192 kbps CBR
lame -b 192 input.wav output_192kbps.mp3
```

---

## 🎙️ Average Bitrate (ABR) & Mono Voice Encoding

Optimize file size for podcasts, audiobooks, or voice recordings by downmixing to mono and targeting low bitrates.

```bash
# Mono voice encoding at 64 kbps ABR
lame -m m --abr 64 podcast_input.wav podcast_speech.mp3

# Downsample frequency rate to 22.05 kHz for smaller file size
lame -m m --abr 32 --resample 22.05 input.wav voice_compressed.mp3
```

---

## 🏷️ ID3 Tagging & Album Metadata

Inject ID3v2 tags (Artist, Title, Album, Year, Track, Genre) directly into the MP3 header during encoding.

```bash
lame -V 2 \
  --ta "The Beatles" \
  --tt "Hey Jude" \
  --al "Past Masters" \
  --ty "1968" \
  --tn "1/14" \
  --tg "Rock" \
  input.wav "01 - Hey Jude.mp3"
```

Metadata flags:
- `--ta`: Artist
- `--tt`: Track Title
- `--al`: Album Name
- `--ty`: Release Year
- `--tn`: Track Number
- `--tg`: Genre Name

---

## 🗂️ Batch Directory Conversion Script

Batch convert an entire directory of `.flac` or `.wav` files into high-quality `-V0` MP3 files.

```bash
#!/bin/bash
# Batch convert all .wav files in current directory to .mp3
mkdir -p mp3_output

for file in *.wav; do
  if [ -f "$file" ]; then
    base_name="${file%.*}"
    echo "Encoding $file..."
    lame -V 0 --add-id3v2 "$file" "mp3_output/${base_name}.mp3"
  fi
done

echo "Batch encoding completed!"
```

---

## 🎬 Integration with FFmpeg

Use `libmp3lame` codec inside FFmpeg commands for advanced audio filter pipelines:

```bash
# Convert FLAC to MP3 320kbps using LAME engine in FFmpeg
ffmpeg -i input.flac -c:a libmp3lame -b:a 320k output.mp3

# Normalize volume and convert to MP3 VBR 0
ffmpeg -i input.wav -af "loudnorm" -c:a libmp3lame -q:a 0 normalized.mp3
```

---

## 📋 Cheat Sheet Summary

| Task | Command |
| --- | --- |
| Max VBR Quality | `lame -V 0 input.wav output.mp3` |
| Recommended VBR | `lame -V 2 input.wav output.mp3` |
| 320 kbps CBR | `lame -b 320 input.wav output.mp3` |
| Podcast Mono | `lame -m m --abr 64 input.wav output.mp3` |
| ID3 Tagged MP3 | `lame -V 2 --ta "Artist" --tt "Title" input.wav output.mp3` |
| FFmpeg LAME | `ffmpeg -i input.wav -c:a libmp3lame -q:a 0 output.mp3` |