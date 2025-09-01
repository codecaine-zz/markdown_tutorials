# FFmpeg with Python: Complete Tutorial

FFmpeg is a powerful multimedia framework that can decode, encode, transcode, mux, demux, stream, filter, and play audio and video files. This tutorial covers how to use FFmpeg with Python through various libraries.

## Installation

### Installing FFmpeg

**macOS (using Homebrew):**

```bash
brew install ffmpeg
```

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install ffmpeg
```

**Windows:**
Download from [ffmpeg.org](https://ffmpeg.org/download.html) and add to PATH.

### Python Libraries

```bash
# FFmpeg-python - Pythonic interface to FFmpeg
pip install ffmpeg-python

# PyDub - High-level audio manipulation
pip install pydub

# MoviePy - Video editing library
pip install moviepy

# OpenCV - Computer vision and video processing
pip install opencv-python
```

### Dependency Verification

Before using any FFmpeg functionality, it's crucial to verify that all dependencies are properly installed:

```python
def check_dependencies():
    """Check if all required dependencies are available."""
    dependencies = {
        'ffmpeg_system': False,
        'ffmpeg_python': False,
        'pydub': False,
        'moviepy': False
    }
    
    # Check system FFmpeg
    try:
        result = subprocess.run(['ffmpeg', '-version'], 
                              capture_output=True, text=True, timeout=10)
        dependencies['ffmpeg_system'] = result.returncode == 0
    except (FileNotFoundError, subprocess.TimeoutExpired):
        dependencies['ffmpeg_system'] = False
    
    # Check Python packages
    try:
        import ffmpeg
        dependencies['ffmpeg_python'] = True
    except ImportError:
        dependencies['ffmpeg_python'] = False
    
    try:
        import pydub
        dependencies['pydub'] = True
    except ImportError:
        dependencies['pydub'] = False
    
    try:
        import moviepy
        dependencies['moviepy'] = True
    except ImportError:
        dependencies['moviepy'] = False
    
    return dependencies

def print_dependency_status():
    """Print status of all dependencies."""
    deps = check_dependencies()
    print("📋 Dependency Status:")
    print(f"  FFmpeg (system): {'✅' if deps['ffmpeg_system'] else '❌'}")
    print(f"  ffmpeg-python:   {'✅' if deps['ffmpeg_python'] else '❌'}")
    print(f"  PyDub:           {'✅' if deps['pydub'] else '❌'}")
    print(f"  MoviePy:         {'✅' if deps['moviepy'] else '❌'}")
    
    if not all(deps.values()):
        print("\n⚠️  Some dependencies are missing. Please install them before proceeding.")
    else:
        print("\n✅ All dependencies are installed!")

# Run dependency check
if __name__ == "__main__":
    print_dependency_status()
```

## 1. Using ffmpeg-python

### Basic Setup and Validation

```python
import ffmpeg
import os
import sys
import subprocess
from pathlib import Path

def validate_ffmpeg_installation():
    """Validate that FFmpeg is properly installed."""
    try:
        result = subprocess.run(['ffmpeg', '-version'], 
                              capture_output=True, text=True, timeout=10)
        if result.returncode == 0:
            print("✅ FFmpeg is installed and accessible")
            return True
        else:
            print("❌ FFmpeg not found in PATH")
            return False
    except (subprocess.TimeoutExpired, FileNotFoundError) as e:
        print(f"❌ Error checking FFmpeg: {e}")
        return False

def validate_input_file(file_path):
    """Validate input file exists and is accessible."""
    path = Path(file_path)
    if not path.exists():
        raise FileNotFoundError(f"Input file does not exist: {file_path}")
    if not path.is_file():
        raise ValueError(f"Path is not a file: {file_path}")
    if not os.access(file_path, os.R_OK):
        raise PermissionError(f"Cannot read file: {file_path}")
    return str(path.resolve())
```

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

## Troubleshooting Guide

### Common Issues and Solutions

#### 1. FFmpeg Not Found
**Error:** `FileNotFoundError: [Errno 2] No such file or directory: 'ffmpeg'`

**Solutions:**
- **macOS:** `brew install ffmpeg`
- **Ubuntu/Debian:** `sudo apt install ffmpeg`
- **Windows:** Download from [ffmpeg.org](https://ffmpeg.org/download.html) and add to PATH
- **Verify installation:** `ffmpeg -version`

#### 2. Permission Denied Errors
**Error:** `PermissionError: [Errno 13] Permission denied`

**Solutions:**
```python
# Check file permissions
import os
import stat

def fix_permissions(file_path):
    """Fix common permission issues."""
    try:
        # Make file readable
        os.chmod(file_path, stat.S_IRUSR | stat.S_IRGRP | stat.S_IROTH)
        print(f"✅ Fixed permissions for {file_path}")
    except PermissionError:
        print(f"❌ Cannot fix permissions for {file_path} - run as administrator")
```

#### 3. Codec Not Found
**Error:** `ffmpeg.Error: Unknown encoder 'libx264'`

**Solutions:**
- Install FFmpeg with codec support: `brew install ffmpeg --with-fdk-aac --with-x264`
- Use alternative codecs:
```python
# Fallback codec options
CODEC_FALLBACKS = {
    'video': ['libx264', 'h264', 'mpeg4'],
    'audio': ['aac', 'libmp3lame', 'pcm_s16le']
}
```

#### 4. Out of Memory Errors
**Error:** `MemoryError` or system becomes unresponsive

**Solutions:**
```python
def process_large_file(input_path, output_path):
    """Process large files safely."""
    # Check available memory
    import psutil
    available_memory = psutil.virtual_memory().available
    file_size = Path(input_path).stat().st_size
    
    if file_size > available_memory * 0.5:  # Use max 50% of available memory
        print("⚠️  Large file detected, processing in segments...")
        # Implement segmented processing
        return process_in_segments(input_path, output_path)
    else:
        return standard_process(input_path, output_path)
```

#### 5. Unsupported Format
**Error:** `ffmpeg.Error: Invalid data found when processing input`

**Solutions:**
```python
def detect_format(file_path):
    """Safely detect file format."""
    try:
        probe = ffmpeg.probe(file_path)
        return probe['format']['format_name']
    except:
        # Fallback to file extension
        return Path(file_path).suffix.lower().lstrip('.')

SUPPORTED_INPUT_FORMATS = ['mp4', 'avi', 'mov', 'mkv', 'wmv', 'flv', 'webm']
SUPPORTED_AUDIO_FORMATS = ['mp3', 'wav', 'aac', 'flac', 'ogg', 'm4a']
```

### Performance Optimization Tips

1. **Use appropriate CRF values:**
   - CRF 18-24: High quality (larger files)
   - CRF 25-30: Good quality (balanced)
   - CRF 31-40: Lower quality (smaller files)

2. **Choose the right preset:**
   - `ultrafast`: Fastest encoding, largest files
   - `fast`, `medium`: Balanced speed/quality
   - `slower`, `veryslow`: Best quality, slowest

3. **Monitor system resources:**
```python
import psutil

def monitor_processing():
    """Monitor system resources during processing."""
    cpu_percent = psutil.cpu_percent(interval=1)
    memory = psutil.virtual_memory()
    
    print(f"CPU: {cpu_percent:.1f}%")
    print(f"Memory: {memory.percent:.1f}% used")
    
    if cpu_percent > 90:
        print("⚠️  High CPU usage - consider reducing concurrent processes")
    if memory.percent > 85:
        print("⚠️  High memory usage - consider processing smaller files")
```

### Best Practices Summary

1. **Always validate inputs** before processing
2. **Use appropriate error handling** for different exception types
3. **Implement timeout mechanisms** for long-running operations
4. **Monitor system resources** during batch processing
5. **Provide clear feedback** to users about processing status
6. **Clean up temporary files** after processing
7. **Test with various file formats** and edge cases
8. **Use logging** for debugging and monitoring
9. **Implement graceful degradation** for missing codecs
10. **Document expected behavior** and limitations

## Conclusion

This tutorial provides a comprehensive foundation for working with FFmpeg in Python. The examples emphasize safety, error handling, and best practices to ensure reliable media processing applications.

Remember to:
- Test thoroughly with your specific use cases
- Handle edge cases gracefully
- Monitor performance and resource usage
- Keep dependencies updated
- Provide clear user feedback

For additional resources, consult the official documentation:
- [FFmpeg Documentation](https://ffmpeg.org/documentation.html)
- [ffmpeg-python Documentation](https://github.com/kkroening/ffmpeg-python)
- [PyDub Documentation](https://github.com/jiaaro/pydub)
- [MoviePy Documentation](https://zulko.github.io/moviepy/)
    
    args = parser.parse_args()
    
    if not args.command:
        parser.print_help()
        return 1
    
    processor = MediaProcessor()
    success = processor.run(args)
    
    return 0 if success else 1

if __name__ == '__main__':
    sys.exit(main())
```

## Security Considerations

1. **Input Validation**: Always validate file paths and parameters
2. **Path Traversal**: Use `Path.resolve()` and check for directory traversal attempts
3. **File Size Limits**: Implement reasonable file size limits
4. **Resource Limits**: Use timeouts and memory limits for processing
5. **Temporary Files**: Clean up temporary files properly
6. **Error Handling**: Never expose internal paths in error messages
7. **Permissions**: Check file permissions before processing
8. **Sandboxing**: Consider running FFmpeg in a restricted environment

## Best Practices

1. **Always use context managers** for file operations
2. **Implement proper error handling** and logging
3. **Use absolute paths** to avoid confusion
4. **Validate inputs** before processing
5. **Set reasonable timeouts** for long operations
6. **Clean up resources** properly (close clips, delete temp files)
7. **Use quality validation** to ensure output integrity
8. **Implement progress monitoring** for long operations

## Common Issues and Solutions

### Issue: "FFmpeg not found"

**Solution**: Install FFmpeg and ensure it's in PATH

### Issue: "Permission denied"

**Solution**: Check file permissions and output directory access

### Issue: "Codec not supported"

**Solution**: Install additional codec libraries or use alternative codecs

### Issue: "Out of memory"

**Solution**: Process files in chunks or reduce quality settings

### Issue: "File corrupted after processing"

**Solution**: Validate inputs and use quality control checks

This tutorial provides a comprehensive, secure foundation for working with FFmpeg in Python. Always test with sample files before processing important media files.
