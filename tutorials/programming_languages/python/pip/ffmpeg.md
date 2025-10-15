# FFmpeg + Python: Installation and Safe Processing (pip)

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Install FFmpeg](#install-ffmpeg)
4. [Install Python packages](#install-python-packages)
5. [Verify dependencies](#verify-dependencies)
6. [Helper module (functions)](#helper-module-functions)
   - [Core validators](#core-validators)
   - [Video helpers](#video-helpers)
   - [Audio helpers](#audio-helpers)
   - [Info and quality checks](#info-and-quality-checks)
   - [Batch processing](#batch-processing)
7. [Using the helpers (demo)](#using-the-helpers-demo)
8. [Troubleshooting](#troubleshooting)
9. [Best practices and security](#best-practices-and-security)
10. [Next steps](#next-steps)

---

## Overview

FFmpeg is a powerful multimedia toolkit for decoding, encoding, transcoding, muxing, demuxing, streaming, filtering, and playing audio/video. This guide shows how to set it up and use it safely from Python with small, reusable functions.

## Prerequisites

- macOS, Linux, or Windows
- Python 3.8+ (3.10–3.12 recommended)
- Sufficient disk space for media outputs

## Install FFmpeg

### macOS (Homebrew)

```bash
brew install ffmpeg
```

### Ubuntu/Debian

```bash
sudo apt update
sudo apt install -y ffmpeg
```

### Windows

- Download from: [ffmpeg.org/download.html](https://ffmpeg.org/download.html)
- Extract and add the bin folder to PATH

## Install Python packages

```bash
python3 -m pip install --upgrade pip
python3 -m pip install ffmpeg-python pydub moviepy opencv-python
```

## Verify dependencies

```python
# deps_check.py
import subprocess

def check_dependencies():
    """Return a dict of dependency availability."""
    results = {
        "ffmpeg_system": False,
        "ffmpeg_python": False,
        "pydub": False,
        "moviepy": False,
    }

    try:
        out = subprocess.run(["ffmpeg", "-version"], capture_output=True, text=True, timeout=10)
        results["ffmpeg_system"] = out.returncode == 0
    except Exception:
        results["ffmpeg_system"] = False

    for mod in ("ffmpeg", "pydub", "moviepy"):
        try:
            __import__(mod)
            results[mod] = True
        except Exception:
            results[mod] = False

    return results

def print_dependency_status():
    deps = check_dependencies()
    print("Dependency status:")
    for k, v in deps.items():
        print(f"  {k}: {'OK' if v else 'MISSING'}")

if __name__ == "__main__":
    print_dependency_status()
```

---

## Helper module (functions)

Copy the snippets below into a file named `ffmpeg_helpers.py` and import the functions in your projects.

### Core validators

```python
# ffmpeg_helpers.py (core)
from __future__ import annotations

import os
import subprocess
from contextlib import contextmanager
from pathlib import Path
from typing import Any, Dict, Iterable, List, Optional


def validate_ffmpeg_installation() -> bool:
    """Return True if system ffmpeg is available."""
    try:
        res = subprocess.run(["ffmpeg", "-version"], capture_output=True, text=True, timeout=10)
        return res.returncode == 0
    except Exception:
        return False


def validate_input_file(file_path: str) -> str:
    """Ensure input path exists, is a file, and is readable; return absolute path."""
    p = Path(file_path)
    if not p.exists():
        raise FileNotFoundError(f"Input file does not exist: {file_path}")
    if not p.is_file():
        raise ValueError(f"Path is not a file: {file_path}")
    if not os.access(str(p), os.R_OK):
        raise PermissionError(f"Cannot read file: {file_path}")
    return str(p.resolve())


def safe_output_path(output_path: str, overwrite: bool = False) -> str:
    """Validate output path, create parent directories, and avoid dangerous locations."""
    out = Path(output_path).resolve()

    restricted = {Path("/"), Path("/usr"), Path("/etc"), Path("/bin"), Path("/sbin"), Path.home()}
    for r in restricted:
        try:
            if hasattr(out, "is_relative_to") and out.is_relative_to(r):
                # prevent writing directly in restricted dirs themselves
                if len(out.parts) <= len(r.parts) + 1:
                    raise ValueError(f"Refusing to write near restricted path: {out}")
        except Exception:
            # Python < 3.9 fallback: best-effort check via string prefix
            if str(out).startswith(str(r)) and len(out.parts) <= len(r.parts) + 1:
                raise ValueError(f"Refusing to write near restricted path: {out}")

    if out.exists() and not overwrite:
        raise FileExistsError(f"Output exists (use overwrite=True): {out}")

    out.parent.mkdir(parents=True, exist_ok=True)
    return str(out)


@contextmanager
def safe_temp_file(suffix: Optional[str] = None, prefix: Optional[str] = None):
    """Yield a temporary file path and clean it up afterwards."""
    import tempfile
    temp_file = None
    try:
        with tempfile.NamedTemporaryFile(delete=False, suffix=suffix, prefix=prefix) as f:
            temp_file = f.name
        yield temp_file
    finally:
        if temp_file and os.path.exists(temp_file):
            try:
                os.unlink(temp_file)
            except OSError:
                pass
```

### Video helpers

```python
# ffmpeg_helpers.py (video)
import ffmpeg


def convert_video_format(
    input_path: str,
    output_path: str,
    format_options: Optional[Dict[str, Any]] = None,
    overwrite: bool = False,
) -> str:
    """Convert video with sensible defaults and error handling."""
    input_path = validate_input_file(input_path)
    output_path = safe_output_path(output_path, overwrite)

    opts: Dict[str, Any] = {
        "vcodec": "libx264",
        "acodec": "aac",
        "crf": 23,
        "preset": "medium",
    }
    if format_options:
        opts.update(format_options)

    try:
        stream = ffmpeg.input(input_path)
        stream = ffmpeg.output(stream, output_path, **opts)
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return output_path
    except ffmpeg.Error as e:
        err = None
        if getattr(e, "stderr", None):
            try:
                err = e.stderr.decode("utf-8")
            except Exception:
                err = str(e.stderr)
        raise RuntimeError(f"FFmpeg conversion failed: {err or str(e)}")


def trim_video(input_path: str, output_path: str, start: float, end: float, overwrite: bool = False) -> str:
    """Trim a video between start and end seconds."""
    if start < 0 or end <= start:
        raise ValueError("Invalid trim range")
    input_path = validate_input_file(input_path)
    output_path = safe_output_path(output_path, overwrite)
    try:
        stream = ffmpeg.input(input_path, ss=start, to=end)
        stream = ffmpeg.output(stream, output_path, c="copy")  # fast trim if possible
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return output_path
    except ffmpeg.Error as e:
        raise RuntimeError(f"Video trimming failed: {e}")


def resize_video(input_path: str, output_path: str, width: int, height: int, overwrite: bool = False) -> str:
    """Resize video to exact dimensions (consider aspect beforehand)."""
    if width <= 0 or height <= 0:
        raise ValueError("Width and height must be positive")
    input_path = validate_input_file(input_path)
    output_path = safe_output_path(output_path, overwrite)
    try:
        stream = ffmpeg.input(input_path)
        stream = ffmpeg.filter(stream, "scale", width, height)
        stream = ffmpeg.output(stream, output_path, vcodec="libx264", acodec="aac")
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return output_path
    except ffmpeg.Error as e:
        raise RuntimeError(f"Video resize failed: {e}")
```

### Audio helpers

```python
# ffmpeg_helpers.py (audio)
import ffmpeg


def extract_audio(
    input_path: str,
    output_path: str,
    audio_format: str = "mp3",
    bitrate: str = "192k",
    overwrite: bool = False,
) -> str:
    """Extract audio from a video with codec selection."""
    input_path = validate_input_file(input_path)
    output_path = safe_output_path(output_path, overwrite)

    codecs = {
        "mp3": "libmp3lame",
        "wav": "pcm_s16le",
        "aac": "aac",
        "flac": "flac",
        "ogg": "libvorbis",
    }
    if audio_format.lower() not in codecs:
        raise ValueError(f"Unsupported audio format: {audio_format}")

    try:
        stream = ffmpeg.input(input_path)
        stream = ffmpeg.output(stream, output_path, acodec=codecs[audio_format.lower()], vn=None, audio_bitrate=bitrate)
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return output_path
    except ffmpeg.Error as e:
        raise RuntimeError(f"Audio extraction failed: {e}")
```

### Info and quality checks

```python
# ffmpeg_helpers.py (info)
import ffmpeg


def get_media_info(input_path: str) -> Dict[str, Any]:
    """Return ffprobe info with safe parsing for common fields."""
    input_path = validate_input_file(input_path)
    probe = ffmpeg.probe(input_path)
    return probe


def print_media_info(input_path: str) -> None:
    info = get_media_info(input_path)
    fmt = info.get("format", {})
    print(f"File: {Path(input_path).name}")
    print(f"Format: {fmt.get('format_name', 'unknown')}")
    try:
        dur = float(fmt.get("duration", 0))
        print(f"Duration: {dur:.2f} s")
    except Exception:
        print(f"Duration: {fmt.get('duration', 'unknown')}")
    try:
        size_mb = int(fmt.get("size", 0)) / 1024 / 1024
        print(f"Size: {size_mb:.2f} MB")
    except Exception:
        print(f"Size: {fmt.get('size', 'unknown')}")


def validate_output_quality(input_path: str, output_path: str, duration_tol: float = 0.1) -> bool:
    """Rough quality check: duration similarity and non-trivial size."""
    i, o = get_media_info(input_path), get_media_info(output_path)
    try:
        di = float(i["format"]["duration"])
        do = float(o["format"]["duration"])
        if abs(di - do) > duration_tol:
            raise ValueError(f"Duration mismatch: {abs(di - do):.2f}s")
        si = int(i["format"]["size"]) or 1
        so = int(o["format"]["size"]) or 0
        if so < si * 0.01:
            raise ValueError("Output suspiciously small")
        print("Quality validation: OK")
        return True
    except Exception as e:
        print(f"Quality validation failed: {e}")
        return False
```

### Batch processing

```python
# ffmpeg_helpers.py (batch)
from concurrent.futures import ThreadPoolExecutor, as_completed


def batch_convert(
    inputs: Iterable[str],
    output_dir: str,
    *,
    format_options: Optional[Dict[str, Any]] = None,
    overwrite: bool = False,
    max_workers: Optional[int] = None,
) -> Dict[str, Any]:
    """Convert many files in parallel, returning success/failure lists."""
    out_dir = Path(output_dir)
    out_dir.mkdir(parents=True, exist_ok=True)

    successes: List[str] = []
    failures: List[str] = []

    def _target(inp: str) -> Optional[str]:
        try:
            src = validate_input_file(inp)
            dest = out_dir / f"converted_{Path(src).stem}.mp4"
            return convert_video_format(src, str(dest), format_options, overwrite)
        except Exception:
            return None

    with ThreadPoolExecutor(max_workers=max_workers or min(4, (os.cpu_count() or 1))) as ex:
        futures = {ex.submit(_target, path): path for path in inputs}
        for fut in as_completed(futures):
            src = futures[fut]
            try:
                result = fut.result()
                if result:
                    successes.append(result)
                else:
                    failures.append(src)
            except Exception:
                failures.append(src)

    return {"successful": successes, "failed": failures}
```

---

## Using the helpers (demo)

```python
# demo_ffmpeg.py
from ffmpeg_helpers import (
    validate_ffmpeg_installation,
    convert_video_format,
    extract_audio,
    trim_video,
    resize_video,
    print_media_info,
    validate_output_quality,
)


def run_demo():
    if not validate_ffmpeg_installation():
        print("FFmpeg not found. Install it and ensure it is on PATH.")
        return

    # Convert
    out = convert_video_format("input.mp4", "out.mp4", {"crf": 22}, overwrite=True)
    print("Converted:", out)

    # Extract audio
    audio = extract_audio("input.mp4", "audio.mp3", audio_format="mp3", bitrate="192k", overwrite=True)
    print("Audio:", audio)

    # Trim and resize
    trimmed = trim_video("input.mp4", "trim.mp4", 5, 20, overwrite=True)
    print("Trimmed:", trimmed)

    resized = resize_video("input.mp4", "resized.mp4", 1280, 720, overwrite=True)
    print("Resized:", resized)

    # Info & quality
    print_media_info("resized.mp4")
    validate_output_quality("input.mp4", "resized.mp4")


if __name__ == "__main__":
    run_demo()
```

---

## Troubleshooting

### FFmpeg not found

```text
FileNotFoundError: [Errno 2] No such file or directory: 'ffmpeg'
```

- Ensure FFmpeg is installed and on PATH
- macOS: `brew install ffmpeg`
- Linux: `sudo apt install ffmpeg`
- Windows: install from official site and add to PATH

### Permission denied

```text
PermissionError: [Errno 13] Permission denied
```

- Check file permissions and output directory rights
- Choose a safe output directory and set `overwrite=True` if re-running

### Unknown encoder or codec not found

- Try a different codec or container; not all codecs are available on all builds
- Use defaults (`libx264` for video, `aac`/`libmp3lame` for audio) when unsure

### Out-of-memory or very slow processing

- Use faster presets (`preset=fast`), increase CRF, or process smaller resolutions
- Avoid unnecessary re-encoding; use stream copy (`c='copy'`) for trims when possible

---

## Best practices and security

1. Always validate inputs and sanitize output paths
2. Avoid writing to restricted/system directories
3. Use timeouts or chunking for very large files
4. Monitor disk space and clean temporary files
5. Provide clear error messages without leaking sensitive paths
6. Prefer immutable inputs; write new outputs instead of in-place

## Next steps

- FFmpeg docs: [ffmpeg.org/documentation.html](https://ffmpeg.org/documentation.html)
- ffmpeg-python: [github.com/kkroening/ffmpeg-python](https://github.com/kkroening/ffmpeg-python)
- Explore higher-level tooling like MoviePy when you need editing pipelines

This guide provides a safe, function-based toolkit you can drop into any project to work with FFmpeg from Python.


### Safe File Operations

```python
import tempfile
import shutil
from contextlib import contextmanager

@contextmanager
def safe_temp_file(suffix=None, prefix=None):
    """Context manager for safe temporary file handling."""
    temp_file = None
    try:
        with tempfile.NamedTemporaryFile(delete=False, suffix=suffix, prefix=prefix) as f:
            temp_file = f.name
        yield temp_file
    finally:
        if temp_file and os.path.exists(temp_file):
            try:
                os.unlink(temp_file)
            except OSError:
                pass

def safe_output_path(output_path, overwrite=False):
    """Ensure safe output path handling."""
    output = Path(output_path).resolve()
    
    # Prevent overwriting important system files
    restricted_paths = [
        Path("/"),
        Path("/usr"),
        Path("/etc"),
        Path("/bin"),
        Path("/sbin"),
        Path.home(),
    ]
    
    for restricted in restricted_paths:
        try:
            if output.is_relative_to(restricted) and len(output.parts) <= len(restricted.parts) + 1:
                raise ValueError(f"Cannot write to restricted path: {output}")
        except ValueError as e:
            # Re-raise our custom ValueError, but catch any other ValueError from is_relative_to
            if "Cannot write to restricted path" in str(e):
                raise
            # For Python < 3.9 compatibility where is_relative_to might not exist
            continue
    
    if output.exists() and not overwrite:
        raise FileExistsError(f"Output file exists (use overwrite=True): {output}")
    
    # Create parent directories safely
    try:
        output.parent.mkdir(parents=True, exist_ok=True)
    except (PermissionError, OSError) as e:
        raise PermissionError(f"Cannot create output directory: {e}")
    
    return str(output)
```

### Video Conversion

```python
def convert_video_format(input_path, output_path, format_options=None, overwrite=False):
    """
    Convert video from one format to another with error handling.
    
    Args:
        input_path (str): Path to input video file
        output_path (str): Path for output video file
        format_options (dict): Additional format options
        overwrite (bool): Whether to overwrite existing files
    """
    try:
        # Validate inputs
        input_path = validate_input_file(input_path)
        output_path = safe_output_path(output_path, overwrite)
        
        # Default safe format options
        default_options = {
            'vcodec': 'libx264',
            'acodec': 'aac',
            'crf': 23,  # Constant Rate Factor for quality
            'preset': 'medium'  # Encoding speed vs compression efficiency
        }
        
        if format_options:
            default_options.update(format_options)
        
        print(f"Converting: {Path(input_path).name} -> {Path(output_path).name}")
        
        # Build FFmpeg command
        stream = ffmpeg.input(input_path)
        stream = ffmpeg.output(stream, output_path, **default_options)
        
        # Run with error handling
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        
        print(f"✅ Conversion completed: {output_path}")
        return output_path
        
    except ffmpeg.Error as e:
        # Handle FFmpeg-specific errors
        error_msg = "Unknown FFmpeg error"
        if hasattr(e, 'stderr') and e.stderr:
            try:
                error_msg = e.stderr.decode('utf-8')
            except (AttributeError, UnicodeDecodeError):
                error_msg = str(e.stderr)
        raise RuntimeError(f"FFmpeg conversion failed: {error_msg}")
    except FileNotFoundError:
        raise RuntimeError("FFmpeg not found. Please ensure FFmpeg is installed and in PATH.")
    except PermissionError as e:
        raise RuntimeError(f"Permission denied: {str(e)}")
    except Exception as e:
        raise RuntimeError(f"Video conversion error: {str(e)}")

# Example usage
try:
    convert_video_format(
        "input_video.mp4",
        "output_video.avi",
        format_options={'vcodec': 'libx264', 'crf': 20}
    )
except Exception as e:
    print(f"Error: {e}")
```

### Audio Extraction

```python
def extract_audio(input_path, output_path, audio_format='mp3', overwrite=False):
    """
    Extract audio from video file safely.
    
    Args:
        input_path (str): Path to input video file
        output_path (str): Path for output audio file
        audio_format (str): Output audio format (mp3, wav, aac, flac)
        overwrite (bool): Whether to overwrite existing files
    """
    try:
        input_path = validate_input_file(input_path)
        output_path = safe_output_path(output_path, overwrite)
        
        # Validate audio format
        supported_formats = ['mp3', 'wav', 'aac', 'flac', 'ogg']
        if audio_format.lower() not in supported_formats:
            raise ValueError(f"Unsupported audio format. Use: {', '.join(supported_formats)}")
        
        # Audio codec mapping
        codec_map = {
            'mp3': 'libmp3lame',
            'wav': 'pcm_s16le',
            'aac': 'aac',
            'flac': 'flac',
            'ogg': 'libvorbis'
        }
        
        print(f"Extracting audio: {Path(input_path).name} -> {Path(output_path).name}")
        
        stream = ffmpeg.input(input_path)
        stream = ffmpeg.output(
            stream,
            output_path,
            acodec=codec_map[audio_format.lower()],
            vn=None,  # Disable video
            audio_bitrate='192k'
        )
        
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        
        print(f"✅ Audio extraction completed: {output_path}")
        return output_path
        
    except ffmpeg.Error as e:
        # Handle FFmpeg-specific errors
        error_msg = "Unknown FFmpeg error"
        if hasattr(e, 'stderr') and e.stderr:
            try:
                error_msg = e.stderr.decode('utf-8')
            except (AttributeError, UnicodeDecodeError):
                error_msg = str(e.stderr)
        raise RuntimeError(f"Audio extraction failed: {error_msg}")
    except FileNotFoundError:
        raise RuntimeError("FFmpeg not found. Please ensure FFmpeg is installed and in PATH.")
    except PermissionError as e:
        raise RuntimeError(f"Permission denied: {str(e)}")
    except Exception as e:
        raise RuntimeError(f"Audio extraction error: {str(e)}")
```

### Video Information

```python
def get_video_info(input_path):
    """
    Get comprehensive video file information safely.
    
    Args:
        input_path (str): Path to video file
        
    Returns:
        dict: Video information dictionary
    """
    try:
        input_path = validate_input_file(input_path)
        
        # Use ffprobe to get video information
        probe = ffmpeg.probe(input_path)
        
        video_info = {
            'filename': Path(input_path).name,
            'format': probe['format'],
            'streams': []
        }
        
        for stream in probe['streams']:
            stream_info = {
                'index': stream['index'],
                'codec_name': stream.get('codec_name', 'unknown'),
                'codec_type': stream.get('codec_type', 'unknown')
            }
            
            if stream['codec_type'] == 'video':
                # Safe FPS calculation from frame rate fraction
                frame_rate = stream.get('r_frame_rate', '0/1')
                try:
                    if '/' in frame_rate:
                        numerator, denominator = frame_rate.split('/')
                        fps = float(numerator) / float(denominator) if float(denominator) != 0 else 0
                    else:
                        fps = float(frame_rate)
                except (ValueError, ZeroDivisionError):
                    fps = 0
                
                # Safe duration parsing
                duration = stream.get('duration', '0')
                try:
                    duration_float = float(duration) if duration != 'N/A' else 0
                except (ValueError, TypeError):
                    duration_float = 0
                
                stream_info.update({
                    'width': stream.get('width', 0),
                    'height': stream.get('height', 0),
                    'duration': duration_float,
                    'fps': fps,
                    'bit_rate': stream.get('bit_rate', 'unknown')
                })
            elif stream['codec_type'] == 'audio':
                # Safe duration parsing for audio streams
                duration = stream.get('duration', '0')
                try:
                    duration_float = float(duration) if duration != 'N/A' else 0
                except (ValueError, TypeError):
                    duration_float = 0
                
                stream_info.update({
                    'sample_rate': stream.get('sample_rate', 'unknown'),
                    'channels': stream.get('channels', 'unknown'),
                    'duration': duration_float,
                    'bit_rate': stream.get('bit_rate', 'unknown')
                })
            
            video_info['streams'].append(stream_info)
        
        return video_info
        
    except ffmpeg.Error as e:
        # Handle FFmpeg-specific errors
        error_msg = "Unknown FFmpeg error"
        if hasattr(e, 'stderr') and e.stderr:
            try:
                error_msg = e.stderr.decode('utf-8')
            except (AttributeError, UnicodeDecodeError):
                error_msg = str(e.stderr)
        raise RuntimeError(f"Failed to get video info: {error_msg}")
    except FileNotFoundError:
        raise RuntimeError("FFmpeg not found. Please ensure FFmpeg is installed and in PATH.")
    except PermissionError as e:
        raise RuntimeError(f"Permission denied: {str(e)}")
    except Exception as e:
        raise RuntimeError(f"Video info error: {str(e)}")

# Example usage
def print_video_info(file_path):
    """Print formatted video information."""
    try:
        info = get_video_info(file_path)
        print(f"\n📹 Video Information: {info['filename']}")
        
        # Safe format info access
        format_info = info.get('format', {})
        print(f"Format: {format_info.get('format_name', 'unknown')}")
        
        # Safe duration parsing
        duration = format_info.get('duration', '0')
        try:
            duration_seconds = float(duration)
            print(f"Duration: {duration_seconds:.2f} seconds")
        except (ValueError, TypeError):
            print(f"Duration: {duration}")
        
        # Safe size parsing
        size = format_info.get('size', '0')
        try:
            size_bytes = int(size)
            size_mb = size_bytes / 1024 / 1024
            print(f"Size: {size_mb:.2f} MB")
        except (ValueError, TypeError):
            print(f"Size: {size}")
        
        for stream in info.get('streams', []):
            if stream['codec_type'] == 'video':
                print(f"\n🎬 Video Stream:")
                print(f"  Codec: {stream['codec_name']}")
                print(f"  Resolution: {stream['width']}x{stream['height']}")
                print(f"  FPS: {stream['fps']:.2f}")
            elif stream['codec_type'] == 'audio':
                print(f"\n🎵 Audio Stream:")
                print(f"  Codec: {stream['codec_name']}")
                print(f"  Sample Rate: {stream['sample_rate']} Hz")
                print(f"  Channels: {stream['channels']}")
                
    except Exception as e:
        print(f"Error getting video info: {e}")
```

## 2. Using PyDub for Audio Processing

```python
from pydub import AudioSegment
from pydub.effects import normalize, compress_dynamic_range
import io

class SafeAudioProcessor:
    """Safe audio processing with PyDub."""
    
    MAX_FILE_SIZE = 100 * 1024 * 1024  # 100MB limit
    SUPPORTED_FORMATS = ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a']
    
    @staticmethod
    def validate_audio_file(file_path):
        """Validate audio file before processing."""
        path = Path(file_path)
        if not path.exists():
            raise FileNotFoundError(f"Audio file not found: {file_path}")
        
        file_size = path.stat().st_size
        if file_size > SafeAudioProcessor.MAX_FILE_SIZE:
            raise ValueError(f"File too large: {file_size / 1024 / 1024:.1f}MB")
        
        return str(path.resolve())
    
    @staticmethod
    def load_audio(file_path):
        """Load audio file with error handling."""
        try:
            file_path = SafeAudioProcessor.validate_audio_file(file_path)
            audio = AudioSegment.from_file(file_path)
            print(f"✅ Loaded audio: {Path(file_path).name}")
            print(f"Duration: {len(audio) / 1000:.2f} seconds")
            print(f"Channels: {audio.channels}")
            print(f"Sample Rate: {audio.frame_rate} Hz")
            return audio
        except Exception as e:
            raise RuntimeError(f"Failed to load audio: {str(e)}")
    
    @staticmethod
    def convert_format(input_path, output_path, output_format, quality=None):
        """Convert audio format safely."""
        try:
            audio = SafeAudioProcessor.load_audio(input_path)
            output_path = safe_output_path(output_path, overwrite=True)
            
            if output_format.lower() not in SafeAudioProcessor.SUPPORTED_FORMATS:
                raise ValueError(f"Unsupported format: {output_format}")
            
            # Quality settings for different formats
            export_params = {}
            if output_format.lower() == 'mp3':
                export_params['bitrate'] = quality or '192k'
            elif output_format.lower() == 'wav':
                export_params['parameters'] = ['-acodec', 'pcm_s16le']
            
            audio.export(output_path, format=output_format.lower(), **export_params)
            print(f"✅ Converted to {output_format.upper()}: {output_path}")
            return output_path
            
        except Exception as e:
            raise RuntimeError(f"Audio conversion failed: {str(e)}")
    
    @staticmethod
    def trim_audio(input_path, output_path, start_ms, end_ms):
        """Trim audio safely with validation."""
        try:
            audio = SafeAudioProcessor.load_audio(input_path)
            output_path = safe_output_path(output_path, overwrite=True)
            
            # Validate trim parameters
            if start_ms < 0 or end_ms < 0:
                raise ValueError("Start and end times must be positive")
            if start_ms >= end_ms:
                raise ValueError("Start time must be before end time")
            if end_ms > len(audio):
                raise ValueError(f"End time exceeds audio duration ({len(audio)}ms)")
            
            trimmed = audio[start_ms:end_ms]
            trimmed.export(output_path, format="wav")
            
            print(f"✅ Trimmed audio: {start_ms/1000:.1f}s - {end_ms/1000:.1f}s")
            return output_path
            
        except Exception as e:
            raise RuntimeError(f"Audio trimming failed: {str(e)}")

# Example usage
def process_audio_example():
    """Example of safe audio processing."""
    try:
        processor = SafeAudioProcessor()
        
        # Load and convert audio
        processor.convert_format("input.wav", "output.mp3", "mp3", "256k")
        
        # Trim audio (5 seconds to 30 seconds)
        processor.trim_audio("input.wav", "trimmed.wav", 5000, 30000)
        
    except Exception as e:
        print(f"Audio processing error: {e}")
```

## 3. Using MoviePy for Video Editing

```python
from moviepy.editor import VideoFileClip, AudioFileClip, concatenate_videoclips
import moviepy.config as config

class SafeVideoEditor:
    """Safe video editing with MoviePy."""
    
    def __init__(self):
        # Configure MoviePy to use system FFmpeg
        if validate_ffmpeg_installation():
            config.FFMPEG_BINARY = 'ffmpeg'
    
    @staticmethod
    def load_video(file_path):
        """Load video with error handling."""
        try:
            file_path = validate_input_file(file_path)
            clip = VideoFileClip(file_path)
            print(f"✅ Loaded video: {Path(file_path).name}")
            print(f"Duration: {clip.duration:.2f} seconds")
            print(f"Size: {clip.size}")
            print(f"FPS: {clip.fps}")
            return clip
        except Exception as e:
            raise RuntimeError(f"Failed to load video: {str(e)}")
    
    @staticmethod
    def trim_video(input_path, output_path, start_time, end_time):
        """Trim video safely."""
        clip = None
        try:
            clip = SafeVideoEditor.load_video(input_path)
            output_path = safe_output_path(output_path, overwrite=True)
            
            # Validate trim parameters
            if start_time < 0 or end_time < 0:
                raise ValueError("Start and end times must be positive")
            if start_time >= end_time:
                raise ValueError("Start time must be before end time")
            if end_time > clip.duration:
                raise ValueError(f"End time exceeds video duration ({clip.duration:.2f}s)")
            
            trimmed = clip.subclip(start_time, end_time)
            
            # Use safe export settings
            trimmed.write_videofile(
                output_path,
                codec='libx264',
                audio_codec='aac',
                temp_audiofile='temp-audio.m4a',
                remove_temp=True,
                logger=None  # Suppress verbose output
            )
            
            print(f"✅ Video trimmed: {start_time}s - {end_time}s")
            return output_path
            
        except Exception as e:
            raise RuntimeError(f"Video trimming failed: {str(e)}")
        finally:
            if clip:
                clip.close()
    
    @staticmethod
    def resize_video(input_path, output_path, width, height):
        """Resize video with aspect ratio preservation option."""
        clip = None
        try:
            clip = SafeVideoEditor.load_video(input_path)
            output_path = safe_output_path(output_path, overwrite=True)
            
            # Validate dimensions
            if width <= 0 or height <= 0:
                raise ValueError("Width and height must be positive")
            if width > 7680 or height > 4320:  # 8K limit for modern systems
                raise ValueError("Resolution too high (max 8K)")
            
            resized = clip.resize((width, height))
            
            resized.write_videofile(
                output_path,
                codec='libx264',
                audio_codec='aac',
                logger=None
            )
            
            print(f"✅ Video resized to {width}x{height}")
            return output_path
            
        except Exception as e:
            raise RuntimeError(f"Video resizing failed: {str(e)}")
        finally:
            if clip:
                clip.close()

# Example usage with proper cleanup
def video_editing_example():
    """Example of safe video editing."""
    editor = SafeVideoEditor()
    
    try:
        # Trim video
        editor.trim_video("input.mp4", "trimmed.mp4", 10, 60)
        
        # Resize video
        editor.resize_video("input.mp4", "resized.mp4", 1280, 720)
        
    except Exception as e:
        print(f"Video editing error: {e}")
```

## 4. Advanced Features and Best Practices

### Batch Processing

```python
import concurrent.futures
import logging
from typing import List, Callable

class BatchProcessor:
    """Safe batch processing of media files."""
    
    def __init__(self, max_workers=None):
        self.max_workers = max_workers or min(4, os.cpu_count() or 1)
        
        # Setup logging
        logging.basicConfig(level=logging.INFO)
        self.logger = logging.getLogger(__name__)
    
    def process_files(self, file_paths: List[str], process_func: Callable, 
                     output_dir: str, **kwargs):
        """
        Process multiple files safely in parallel.
        
        Args:
            file_paths: List of input file paths
            process_func: Function to process each file
            output_dir: Directory for output files
            **kwargs: Additional arguments for process_func
        """
        output_dir = Path(output_dir)
        output_dir.mkdir(parents=True, exist_ok=True)
        
        results = []
        failed = []
        
        with concurrent.futures.ThreadPoolExecutor(max_workers=self.max_workers) as executor:
            future_to_file = {}
            
            for file_path in file_paths:
                try:
                    validate_input_file(file_path)
                    output_path = output_dir / f"processed_{Path(file_path).name}"
                    
                    future = executor.submit(
                        self._safe_process_wrapper, 
                        process_func, file_path, str(output_path), **kwargs
                    )
                    future_to_file[future] = file_path
                    
                except Exception as e:
                    self.logger.error(f"Failed to queue {file_path}: {e}")
                    failed.append(file_path)
            
            for future in concurrent.futures.as_completed(future_to_file):
                file_path = future_to_file[future]
                try:
                    result = future.result(timeout=300)  # 5-minute timeout
                    results.append(result)
                    self.logger.info(f"✅ Processed: {Path(file_path).name}")
                except Exception as e:
                    self.logger.error(f"❌ Failed to process {file_path}: {e}")
                    failed.append(file_path)
        
        return {
            'successful': results,
            'failed': failed,
            'total_processed': len(results),
            'total_failed': len(failed)
        }
    
    def _safe_process_wrapper(self, process_func, *args, **kwargs):
        """Wrapper to handle individual file processing safely."""
        try:
            return process_func(*args, **kwargs)
        except Exception as e:
            self.logger.error(f"Processing error: {str(e)}")
            raise
```

### Quality Control and Validation

```python
def validate_output_quality(input_path, output_path, tolerance=0.1):
    """
    Validate that output file meets quality expectations.
    
    Args:
        input_path: Original file path
        output_path: Processed file path
        tolerance: Acceptable difference in duration (seconds)
    """
    try:
        input_info = get_video_info(input_path)
        output_info = get_video_info(output_path)
        
        input_duration = float(input_info['format']['duration'])
        output_duration = float(output_info['format']['duration'])
        
        duration_diff = abs(input_duration - output_duration)
        
        if duration_diff > tolerance:
            raise ValueError(f"Duration mismatch: {duration_diff:.2f}s difference")
        
        # Check file size (output shouldn't be suspiciously small)
        input_size = int(input_info['format']['size'])
        output_size = int(output_info['format']['size'])
        
        if output_size < input_size * 0.01:  # Less than 1% of original
            raise ValueError("Output file suspiciously small")
        
        print("✅ Quality validation passed")
        return True
        
    except Exception as e:
        print(f"❌ Quality validation failed: {e}")
        return False
```

## 5. Complete Example Application

```python
#!/usr/bin/env python3
"""
FFmpeg Media Processor - A safe and comprehensive media processing tool.
"""

import argparse
import sys
from pathlib import Path

class MediaProcessor:
    """Main application class for media processing."""
    
    def __init__(self):
        if not validate_ffmpeg_installation():
            print("❌ FFmpeg is required but not found. Please install FFmpeg first.")
            sys.exit(1)
        
        self.batch_processor = BatchProcessor()
    
    def run(self, args):
        """Main entry point."""
        try:
            if args.command == 'convert':
                return self._convert_video(args)
            elif args.command == 'extract-audio':
                return self._extract_audio(args)
            elif args.command == 'info':
                return self._show_info(args)
            elif args.command == 'batch':
                return self._batch_process(args)
            else:
                print(f"Unknown command: {args.command}")
                return False
                
        except KeyboardInterrupt:
            print("\n⚠️  Operation cancelled by user")
            return False
        except Exception as e:
            print(f"❌ Error: {e}")
            return False
    
    def _convert_video(self, args):
        """Convert video format."""
        convert_video_format(
            args.input,
            args.output,
            format_options=args.format_options,
            overwrite=args.force
        )
        
        if args.validate:
            validate_output_quality(args.input, args.output)
        
        return True
    
    def _extract_audio(self, args):
        """Extract audio from video."""
        extract_audio(
            args.input,
            args.output,
            audio_format=args.format,
            overwrite=args.force
        )
        return True
    
    def _show_info(self, args):
        """Show media file information."""
        print_video_info(args.input)
        return True
    
    def _batch_process(self, args):
        """Process multiple files."""
        input_files = list(Path(args.input_dir).glob(f"*.{args.extension}"))
        
        if not input_files:
            print(f"No .{args.extension} files found in {args.input_dir}")
            return False
        
        print(f"Found {len(input_files)} files to process...")
        
        results = self.batch_processor.process_files(
            [str(f) for f in input_files],
            convert_video_format,
            args.output_dir,
            format_options={'vcodec': 'libx264', 'crf': 23}
        )
        
        print(f"\n📊 Batch processing completed:")
        print(f"✅ Successful: {results['total_processed']}")
        print(f"❌ Failed: {results['total_failed']}")
        
        return results['total_failed'] == 0

def main():
    """Command line interface."""
    parser = argparse.ArgumentParser(description='FFmpeg Media Processor')
    subparsers = parser.add_subparsers(dest='command', help='Available commands')
    
    # Convert command
    convert_parser = subparsers.add_parser('convert', help='Convert video format')
    convert_parser.add_argument('input', help='Input video file')
    convert_parser.add_argument('output', help='Output video file')
    convert_parser.add_argument('--force', action='store_true', help='Overwrite existing files')
    convert_parser.add_argument('--validate', action='store_true', help='Validate output quality')
    
    # Extract audio command
    audio_parser = subparsers.add_parser('extract-audio', help='Extract audio from video')
    audio_parser.add_argument('input', help='Input video file')
    audio_parser.add_argument('output', help='Output audio file')
    audio_parser.add_argument('--format', default='mp3', help='Audio format (mp3, wav, etc.)')
    audio_parser.add_argument('--force', action='store_true', help='Overwrite existing files')
    
    # Info command
    info_parser = subparsers.add_parser('info', help='Show media file information')
    info_parser.add_argument('input', help='Input media file')
    
    # Batch command
    batch_parser = subparsers.add_parser('batch', help='Batch process files')
    batch_parser.add_argument('input_dir', help='Input directory')
    batch_parser.add_argument('output_dir', help='Output directory')
    batch_parser.add_argument('--extension', default='mp4', help='File extension to process')
    
    args = parser.parse_args()
    
    if not args.command:
        parser.print_help()
        return 1
    
    processor = MediaProcessor()
    
    try:
        if args.command == 'convert':
            success = processor.convert_video(args.input, args.output, 
                                           overwrite=args.force, 
                                           validate_output=args.validate)
        elif args.command == 'extract-audio':
            success = processor.extract_audio(args.input, args.output, 
                                           args.format, overwrite=args.force)
        elif args.command == 'info':
            success = processor.show_info(args.input)
        elif args.command == 'batch':
            success = processor.batch_convert(args)
        else:
            print(f"Unknown command: {args.command}")
            return 1
        
        return 0 if success else 1
        
    except KeyboardInterrupt:
        print("\n🛑 Operation cancelled by user")
        return 1
    except Exception as e:
        print(f"💥 Unexpected error: {e}")
        return 1

if __name__ == "__main__":
    sys.exit(main())
```
