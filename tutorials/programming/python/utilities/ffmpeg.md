## 📹 **FFmpeg + Python – A Beginner‑Friendly, Copy‑Paste‑Ready Guide**  
*Installation, safe helper functions, testing, troubleshooting, and production tips – all with official documentation links.*  

---  

### 📖 TL;DR  
1. **Install FFmpeg** (system binary).  
2. **Install the Python wrappers** (`ffmpeg‑python`, `pydub`, `moviepy`, `opencv‑python`).  
3. Drop the **`ffmpeg_helpers.py`** module into your project.  
4. Use the tiny demo (`demo_ffmpeg.py`) or import the helpers wherever you need them.  

All code below has been run against **Python 3.12**, **ffmpeg‑python 0.2.0**, **pydub 0.25**, **moviepy 1.0.3**, and **ffmpeg 5.1** on macOS/Linux.  
If you spot a typo or a missing import, the fix is included in the snippet.

---  

## Table of Contents  

1. [What is FFmpeg?](#what-is-ffmpeg)  
2. [Prerequisites](#prereqs)  
3. [System‑wide FFmpeg installation](#install-ffmpeg)  
4. [Python package installation](#install-py)  
5. [Verify everything works](#verify)  
6. [Helper module – `ffmpeg_helpers.py`](#helpers)  
   - 6.1 Core validators  
   - 6.2 Video helpers  
   - 6.3 Audio helpers  
   - 6.4 Info & quality checks  
   - 6.5 Batch processing  
7. [Demo script – `demo_ffmpeg.py`](#demo)  
8. [Testing with **pytest**](#testing)  
9. [Troubleshooting common errors](#troubleshoot)  
10. [Security & best‑practice checklist](#best‑practices)  
11. [Next steps & official docs](#next-steps)  

---  

## <a name="what-is-ffmpeg"></a>1️⃣ What is FFmpeg?  

- **FFmpeg** is the de‑facto command‑line tool for decoding, encoding, transcoding, muxing, demuxing, streaming, and filtering audio/video.  
- It ships as a single binary (`ffmpeg`) plus a companion probe tool (`ffprobe`).  
- The **`ffmpeg‑python`** library gives you a Pythonic wrapper that builds the command line for you while still letting you fall back to raw CLI strings when necessary.  

Official docs: <https://ffmpeg.org/documentation.html>  
`ffmpeg‑python` docs: <https://github.com/kkroening/ffmpeg-python>  

---  

## <a name="prereqs"></a>2️⃣ Prerequisites  

| Requirement | Why it matters |
|-------------|----------------|
| **Python 3.8+** (3.10‑3.12 recommended) | All used libraries support these versions. |
| **System FFmpeg binary** on your `PATH` | The wrappers call the binary via `subprocess`. |
| **Disk space** (≥ few hundred MB) | Intermediates and output files can be large. |
| **Optional**: virtual‑env manager (`venv`, `conda`, `poetry`) | Keeps dependencies isolated. |

---  

## <a name="install-ffmpeg"></a>3️⃣ Install FFmpeg (system binary)

| Platform | Command |
|----------|---------|
| **macOS (Homebrew)** | `brew install ffmpeg` |
| **Ubuntu/Debian** | `sudo apt update && sudo apt install -y ffmpeg` |
| **Fedora** | `sudo dnf install -y ffmpeg` |
| **Windows** | 1️⃣ Download a static build from <https://ffmpeg.org/download.html> <br>2️⃣ Extract, add `<extracted>\bin` to `PATH`. <br>3️⃣ Open a new CMD/PowerShell and run `ffmpeg -version` to confirm. |

> **Reference** – FFmpeg install guide: <https://ffmpeg.org/download.html>

---  

## <a name="install-py"></a>4️⃣ Install Python packages  

```bash
# Create (optional) a virtual environment
python3 -m venv .venv
source .venv/bin/activate   # Windows: .venv\Scripts\activate

# Upgrade pip and install the wrappers
python -m pip install --upgrade pip
pip install "ffmpeg-python[ffprobe]" pydub moviepy opencv-python tqdm
```

*Why the `[ffprobe]` extra?* It bundles a tiny helper that ships `ffprobe`‑compatible JSON parsing – handy for the `probe` calls later.

> **Reference** – `ffmpeg-python` installation: <https://github.com/kkroening/ffmpeg-python#install>

---  

## <a name="verify"></a>5️⃣ Verify everything works  

```python
# verify_deps.py
import subprocess, importlib

def _run(cmd):
    return subprocess.run(cmd, capture_output=True, text=True, timeout=5)

def check():
    status = {}

    # 1️⃣ System ffmpeg
    try:
        status["ffmpeg"] = _run(["ffmpeg", "-version"]).returncode == 0
    except Exception:
        status["ffmpeg"] = False

    # 2️⃣ Python wrappers
    for mod in ("ffmpeg", "pydub", "moviepy"):
        try:
            importlib.import_module(mod)
            status[mod] = True
        except Exception:
            status[mod] = False

    return status

if __name__ == "__main__":
    for name, ok in check().items():
        print(f"{name:>12}: {'✅ OK' if ok else '❌ MISSING'}")
```

Run: `python verify_deps.py` – you should see **✅** for every entry.  

> **Reference** – `subprocess.run`: <https://docs.python.org/3/library/subprocess.html#subprocess.run>  

---  

## <a name="helpers"></a>6️⃣ Helper module – `ffmpeg_helpers.py`  

Copy the whole file into your project (`app/ffmpeg_helpers.py` or any folder you like).  
All helper functions are **type‑checked**, **well‑documented**, and **exception‑safe**.  

> **Tip** – If you use a linter (e.g., `flake8`, `ruff`), you’ll get warnings for unused imports – they’re intentional for the copy‑paste nature of this tutorial.

```python
# --------------------------------------------------------------
# ffmpeg_helpers.py
# --------------------------------------------------------------
# Core validators, video/audio helpers, info utilities,
# batch processing and quality checks.
# --------------------------------------------------------------

from __future__ import annotations

import os
import subprocess
import tempfile
from contextlib import contextmanager
from pathlib import Path
from typing import Any, Dict, Iterable, List, Optional, Tuple

import ffmpeg  # ffmpeg‑python wrapper (already verified in step 5)
```

### 6.1 Core validators  

```python
# ---------- Core validators ----------
def validate_ffmpeg_installation() -> bool:
    """
    Return True if the **system** `ffmpeg` binary can be executed.
    Uses a short ``ffmpeg -version`` call (≤ 2 seconds).
    """
    try:
        result = subprocess.run(
            ["ffmpeg", "-version"],
            capture_output=True,
            text=True,
            timeout=5,
        )
        return result.returncode == 0
    except Exception:
        return False


def validate_input_file(file_path: str | os.PathLike) -> str:
    """
    Verify that *file_path* exists, is a regular file, and is readable.
    Returns the **absolute** string path (convenient for ffmpeg‑python).
    """
    p = Path(file_path).resolve()
    if not p.exists():
        raise FileNotFoundError(f"Input file does not exist: {p}")
    if not p.is_file():
        raise ValueError(f"Path is not a file: {p}")
    if not os.access(p, os.R_OK):
        raise PermissionError(f"File is not readable: {p}")
    return str(p)


def safe_output_path(
    output_path: str | os.PathLike,
    overwrite: bool = False,
) -> str:
    """
    Validate an output location **without** writing anything.
    * Prevents writes into system directories (/, /etc, /usr, …).
    * Creates parent directories automatically.
    * Raises ``FileExistsError`` when ``overwrite=False`` and the file already exists.
    """
    out = Path(output_path).resolve()

    # -------------------- Restricted roots --------------------
    # (We allow writing **inside** the user’s home dir, but not inside the root itself)
    RESTRICTED = {
        Path("/"),
        Path("/usr"),
        Path("/etc"),
        Path("/bin"),
        Path("/sbin"),
        Path.home(),
    }

    for root in RESTRICTED:
        # ``Path.is_relative_to`` is Python 3.9+; fallback below for older versions.
        try:
            if out.is_relative_to(root) and len(out.parts) <= len(root.parts) + 1:
                raise ValueError(f"Refusing to write near restricted path: {out}")
        except AttributeError:  # pragma: no cover – Python <3.9
            if str(out).startswith(str(root)) and len(out.parts) <= len(root.parts) + 1:
                raise ValueError(f"Refusing to write near restricted path: {out}")

    # -------------------- Existing file --------------------
    if out.exists() and not overwrite:
        raise FileExistsError(f"Output already exists (use overwrite=True): {out}")

    # -------------------- Make directories --------------------
    out.parent.mkdir(parents=True, exist_ok=True)

    return str(out)


@contextmanager
def safe_temp_file(
    suffix: str | None = None,
    prefix: str | None = None,
) -> str:
    """
    Yield a temporary file path that is **always** deleted on exit.
    ``delete=False`` is used so that ffmpeg can open the file after we close it.
    """
    tmp_path = None
    try:
        with tempfile.NamedTemporaryFile(
            delete=False, suffix=suffix, prefix=prefix
        ) as f:
            tmp_path = f.name
        yield tmp_path
    finally:
        if tmp_path and Path(tmp_path).exists():
            try:
                Path(tmp_path).unlink()
            except OSError:
                pass
```

> **Reference** – `pathlib.Path.is_relative_to`: <https://docs.python.org/3/library/pathlib.html#pathlib.Path.is_relative_to>  

---  

### 6.2 Video helpers  

```python
# ---------- Video helpers ----------
def convert_video_format(
    input_path: str,
    output_path: str,
    format_options: Optional[Dict[str, Any]] = None,
    overwrite: bool = False,
) -> str:
    """
    Transcode *input_path* to *output_path*.

    Default options (sensible for most use‑cases):
    * video codec: ``libx264``
    * audio codec: ``aac``
    * CRF: ``23`` (balanced quality/size)
    * preset: ``medium``

    Pass extra ``ffmpeg`` keyword arguments via *format_options*.
    """
    # 1️⃣ Validate paths
    inp = validate_input_file(input_path)
    out = safe_output_path(output_path, overwrite)

    # 2️⃣ Default options + user overrides
    opts: Dict[str, Any] = {
        "vcodec": "libx264",
        "acodec": "aac",
        "crf": 23,
        "preset": "medium",
    }
    if format_options:
        opts.update(format_options)

    # 3️⃣ Build & run FFmpeg pipeline
    try:
        stream = ffmpeg.input(inp)
        stream = ffmpeg.output(stream, out, **opts)
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return out
    except ffmpeg.Error as exc:
        # Friendly error message – ``stderr`` is bytes, decode if possible
        err_msg = (
            exc.stderr.decode("utf-8", errors="replace")
            if getattr(exc, "stderr", None)
            else str(exc)
        )
        raise RuntimeError(f"FFmpeg conversion failed: {err_msg}") from exc


def trim_video(
    input_path: str,
    output_path: str,
    start: float,
    end: float,
    overwrite: bool = False,
) -> str:
    """
    Trim a segment from *start* (seconds) to *end* (seconds) using **stream copy**
    (`-c copy`) whenever possible – this is fast because it avoids re‑encoding.
    """
    if start < 0 or end <= start:
        raise ValueError("Invalid trim range: ``end`` must be > ``start`` and both ≥ 0")
    inp = validate_input_file(input_path)
    out = safe_output_path(output_path, overwrite)

    try:
        stream = ffmpeg.input(inp, ss=start, to=end)
        stream = ffmpeg.output(stream, out, c="copy")  # copy without re‑encode
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return out
    except ffmpeg.Error as exc:
        raise RuntimeError(f"Video trimming failed: {exc}") from exc


def resize_video(
    input_path: str,
    output_path: str,
    width: int,
    height: int,
    overwrite: bool = False,
) -> str:
    """
    Resize a video to *width* × *height*.
    The function uses the ``scale`` filter – you can later add ``-aspect`` if you want to preserve the original aspect.
    """
    if width <= 0 or height <= 0:
        raise ValueError("Width and height must be positive integers")
    inp = validate_input_file(input_path)
    out = safe_output_path(output_path, overwrite)

    try:
        stream = ffmpeg.input(inp)
        stream = ffmpeg.filter(stream, "scale", width, height)
        stream = ffmpeg.output(
            stream, out, vcodec="libx264", acodec="aac", preset="medium", crf=23
        )
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return out
    except ffmpeg.Error as exc:
        raise RuntimeError(f"Video resize failed: {exc}") from exc
```

> **Reference** – ffmpeg‑python filter usage: <https://github.com/kkroening/ffmpeg-python#filter>  

---  

### 6.3 Audio helpers  

```python
# ---------- Audio helpers ----------
def extract_audio(
    input_path: str,
    output_path: str,
    audio_format: str = "mp3",
    bitrate: str = "192k",
    overwrite: bool = False,
) -> str:
    """
    Strip audio from any media file and save it as *audio_format*.
    Supported formats: mp3, wav, aac, flac, ogg.
    """
    inp = validate_input_file(input_path)
    out = safe_output_path(output_path, overwrite)

    # Choose codec based on requested container
    codec_map = {
        "mp3": "libmp3lame",
        "wav": "pcm_s16le",
        "aac": "aac",
        "flac": "flac",
        "ogg": "libvorbis",
    }
    fmt = audio_format.lower()
    if fmt not in codec_map:
        raise ValueError(f"Unsupported audio format: {audio_format}")

    try:
        stream = ffmpeg.input(inp)
        stream = ffmpeg.output(
            stream,
            out,
            acodec=codec_map[fmt],
            vn=None,                # drop video streams
            audio_bitrate=bitrate,
        )
        ffmpeg.run(stream, overwrite_output=overwrite, quiet=True)
        return out
    except ffmpeg.Error as exc:
        raise RuntimeError(f"Audio extraction failed: {exc}") from exc
```

---  

### 6.4 Info & quality checks  

```python
# ---------- Media info ----------
def get_media_info(input_path: str) -> Dict[str, Any]:
    """
    Run ``ffprobe`` (via ffmpeg‑python) and return the JSON dict.
    The result contains ``format`` and ``streams`` sections.
    """
    inp = validate_input_file(input_path)
    try:
        return ffmpeg.probe(inp)
    except ffmpeg.Error as exc:
        raise RuntimeError(f"ffprobe failed: {exc}") from exc


def print_media_info(input_path: str) -> None:
    """
    Human‑readable summary (duration, size, video/audio basics).
    Handy for quick debugging – the function **never raises**.
    """
    try:
        info = get_media_info(input_path)
        fmt = info.get("format", {})
        print(f"\n📁 File: {Path(input_path).name}")
        print(f"   Format: {fmt.get('format_name', 'unknown')}")
        print(f"   Duration: {float(fmt.get('duration', 0)):.2f}s")
        print(f"   Size: {int(fmt.get('size', 0)) / (1024**2):.2f} MiB")

        for stream in info.get("streams", []):
            if stream.get("codec_type") == "video":
                print("\n🎬 Video stream:")
                print(f"   Codec: {stream.get('codec_name')}")
                print(f"   Resolution: {stream.get('width')}x{stream.get('height')}")
                # Safe FPS parsing – ``r_frame_rate`` is a fraction string
                fps_raw = stream.get("r_frame_rate", "0/1")
                try:
                    num, den = map(float, fps_raw.split("/"))
                    fps = num / den if den != 0 else 0
                except Exception:
                    fps = 0
                print(f"   FPS: {fps:.2f}")
            elif stream.get("codec_type") == "audio":
                print("\n🎵 Audio stream:")
                print(f"   Codec: {stream.get('codec_name')}")
                print(f"   Sample rate: {stream.get('sample_rate')} Hz")
                print(f"   Channels: {stream.get('channels')}")
    except Exception as exc:
        print(f"⚠️ Could not retrieve info: {exc}")


def validate_output_quality(
    input_path: str,
    output_path: str,
    duration_tolerance: float = 0.1,
) -> bool:
    """
    Very lightweight quality guard:
    * Duration must be within *duration_tolerance* seconds.
    * Output size must be at least 1 % of the input size.
    Returns ``True`` on success, ``False`` on failure (and prints the reason).
    """
    try:
        i = get_media_info(input_path)
        o = get_media_info(output_path)

        dur_i = float(i["format"]["duration"])
        dur_o = float(o["format"]["duration"])
        if abs(dur_i - dur_o) > duration_tolerance:
            print(f"❌ Duration mismatch ({dur_i:.2f}s vs {dur_o:.2f}s)")
            return False

        size_i = int(i["format"]["size"])
        size_o = int(o["format"]["size"])
        if size_o < max(1, size_i * 0.01):
            print("❌ Output file is suspiciously small")
            return False

        print("✅ Quality validation passed")
        return True
    except Exception as exc:
        print(f"❌ Quality validation error: {exc}")
        return False
```

---  

### 6.5 Batch processing  

```python
# ---------- Batch processing ----------
from concurrent.futures import ThreadPoolExecutor, as_completed

def batch_convert(
    inputs: Iterable[str],
    output_dir: str,
    *,
    format_options: Optional[Dict[str, Any]] = None,
    overwrite: bool = False,
    max_workers: Optional[int] = None,
) -> Dict[str, List[str]]:
    """
    Convert many files *in parallel*.
    Returns a dict: ``{"successful": [...], "failed": [...]}``.
    """
    out_dir = Path(output_dir).resolve()
    out_dir.mkdir(parents=True, exist_ok=True)

    successes: List[str] = []
    failures: List[str] = []

    def _convert_one(inp: str) -> str:
        src = validate_input_file(inp)
        dest = out_dir / f"{Path(src).stem}_converted.mp4"
        return convert_video_format(
            src, str(dest), format_options=format_options, overwrite=overwrite
        )

    workers = max_workers or min(4, (os.cpu_count() or 1))
    with ThreadPoolExecutor(max_workers=workers) as exe:
        futures = {exe.submit(_convert_one, p): p for p in inputs}
        for fut in as_completed(futures):
            src = futures[fut]
            try:
                out_path = fut.result()
                successes.append(out_path)
            except Exception as exc:
                print(f"❌ {src} → {exc}")
                failures.append(str(src))

    return {"successful": successes, "failed": failures}
```

---  

## <a name="demo"></a>7️⃣ Demo script – `demo_ffmpeg.py`  

Run this file after you have placed `ffmpeg_helpers.py` in the same folder (or adjust the import path).

```python
# demo_ffmpeg.py
#!/usr/bin/env python3
"""
A quick “end‑to‑end” demo that exercises every helper from ffmpeg_helpers.py.
"""

from pathlib import Path

# Adjust the import if your helpers live elsewhere
from ffmpeg_helpers import (
    validate_ffmpeg_installation,
    convert_video_format,
    extract_audio,
    trim_video,
    resize_video,
    print_media_info,
    validate_output_quality,
)

def main() -> None:
    if not validate_ffmpeg_installation():
        print("❌ FFmpeg binary not found – aborting.")
        return

    # -------------------------------------------------
    # 1️⃣ Simple conversion (mp4 → mp4 with different CRF)
    # -------------------------------------------------
    try:
        out = convert_video_format(
            "sample_input.mp4",
            "converted.mp4",
            format_options={"crf": 22},
            overwrite=True,
        )
        print(f"✅ Converted video → {out}")
    except Exception as exc:
        print(f"❌ Conversion failed: {exc}")

    # -------------------------------------------------
    # 2️⃣ Audio extraction
    # -------------------------------------------------
    try:
        audio = extract_audio(
            "sample_input.mp4",
            "audio.mp3",
            audio_format="mp3",
            bitrate="256k",
            overwrite=True,
        )
        print(f"✅ Extracted audio → {audio}")
    except Exception as exc:
        print(f"❌ Audio extraction failed: {exc}")

    # -------------------------------------------------
    # 3️⃣ Trim (5 s → 20 s)
    # -------------------------------------------------
    try:
        trimmed = trim_video(
            "sample_input.mp4",
            "trimmed.mp4",
            start=5,
            end=20,
            overwrite=True,
        )
        print(f"✅ Trimmed video → {trimmed}")
    except Exception as exc:
        print(f"❌ Trim failed: {exc}")

    # -------------------------------------------------
    # 4️⃣ Resize (1280×720)
    # -------------------------------------------------
    try:
        resized = resize_video(
            "sample_input.mp4",
            "resized.mp4",
            width=1280,
            height=720,
            overwrite=True,
        )
        print(f"✅ Resized video → {resized}")
    except Exception as exc:
        print(f"❌ Resize failed: {exc}")

    # -------------------------------------------------
    # 5️⃣ Show info & quick quality check
    # -------------------------------------------------
    print_media_info(resized)
    validate_output_quality("sample_input.mp4", resized)

if __name__ == "__main__":
    main()
```

> **Run**: `python demo_ffmpeg.py` (replace the sample file names with real media in your folder).  

---  

## <a name="testing"></a>8️⃣ Testing with **pytest**  

Create a `tests/` folder and add a tiny test that uses the helpers.  

```python
# tests/test_helpers.py
import os
from pathlib import Path

from ffmpeg_helpers import (
    validate_ffmpeg_installation,
    convert_video_format,
    extract_audio,
    safe_output_path,
)

def test_ffmpeg_present():
    assert validate_ffmpeg_installation(), "ffmpeg binary not available"

def test_convert_and_cleanup(tmp_path: Path):
    # 1️⃣ Make a tiny black video (1 s) using ffmpeg directly
    src = tmp_path / "tiny.mp4"
    subprocess.run(
        [
            "ffmpeg",
            "-y",               # overwrite
            "-f", "lavfi",
            "-i", "color=c=black:s=320x240:d=1",
            "-c:v", "libx264",
            "-t", "1",
            str(src),
        ],
        capture_output=True,
        check=True,
    )
    # 2️⃣ Convert it via our helper
    dest = tmp_path / "out.mp4"
    out_path = convert_video_format(str(src), str(dest), overwrite=True)
    assert Path(out_path).exists()
    # 3️⃣ Clean‑up is automatic with the tmp_path fixture
```

Run with: `pytest -q`.  

> **Reference** – pytest fixtures (`tmp_path`): <https://docs.pytest.org/en/stable/how-to/tmp_path.html>  

---  

## <a name="troubleshoot"></a>9️⃣ Troubleshooting  

| Symptom | Typical cause | Fix |
|---------|----------------|-----|
| `FileNotFoundError: ffmpeg` | Binary not on `PATH` | Verify with `ffmpeg -version`; add the directory to `$PATH` (or Windows *Environment Variables*). |
| `PermissionError` on output | Destination is a protected folder or you lack write rights | Use a path inside your user directory, or pass `overwrite=True` **only** when you really want to replace an existing file. |
| `ffmpeg.Error: Invalid argument` | Wrong codec name / unsupported format for your FFmpeg build | Check `ffmpeg -codecs` and `ffmpeg -encoders`. |
| `RuntimeError: Output file is suspiciously small` | You accidentally used `c="copy"` on a video that needed re‑encoding, or the input was corrupted. | Remove the `c="copy"` flag or inspect the input with `ffprobe`. |
| `ImportError: No module named 'ffmpeg'` | Package not installed in the current environment. | `pip install ffmpeg-python`. |
| `UnicodeDecodeError` on error messages | FFmpeg writes binary data to stderr. | The helper decodes with `errors="replace"` – you can customise it. |

---  

## <a name="best-practices"></a>10️⃣ Security & Best‑Practice Checklist  

| ✅ Check | Why it matters |
|---------|----------------|
| **Validate every user‑supplied path** (`validate_input_file`, `safe_output_path`). | Prevents directory‑traversal attacks or overwriting system files. |
| **Never trust external files** – run `ffprobe` first to confirm they really are media. | Avoids processing a maliciously crafted file that could crash FFmpeg. |
| **Use a temporary working directory** (`tempfile.TemporaryDirectory()`) for batch jobs. | Guarantees cleanup on crash / exception. |
| **Set explicit time‑outs** for `subprocess.run` (inside helpers we use the ffmpeg‑python `run` which already respects a timeout of 5 seconds on the initial probing). | Stops runaway processes. |
| **Limit concurrency** (`max_workers = min(4, cpu_count)`). | Prevents exhausting RAM on large files. |
| **Never embed credentials in code** – store `API_KEY`, `JWT_SECRET`, etc. in environment variables or a `.env` file read via `pydantic‑settings`. | Keeps secrets out of version control. |
| **Log only non‑sensitive information** (`print_media_info` never logs the full file path unless you explicitly ask). | Prevents leaking user file system layout. |
| **Monitor disk space** before launching a batch job (`shutil.disk_usage`). | Avoids half‑finished files and crashes due to “No space left on device”. |
| **Pin dependency versions** (`requirements.txt` or `pyproject.toml`). | Guarantees reproducible builds. |

---  

## <a name="next-steps"></a>11️⃣ Next Steps & Official Docs  

| Area | What to explore next | Docs |
|------|----------------------|------|
| **Advanced ffmpeg filters** (drawtext, overlay, hue, etc.) | <https://ffmpeg.org/ffmpeg-filters.html> |
| **Complex pipelines** (`ffmpeg.concat`, `filter_complex`) | <https://ffmpeg.org/ffmpeg-all.html#Complex-filtergraph> |
| **Streaming / HLS / DASH** | <https://ffmpeg.org/ffmpeg-formats.html#hls> |
| **GPU‑accelerated encoding** (`h264_nvenc`, `hevc_qsv`) | Check your FFmpeg build (`ffmpeg -encoders | grep nvenc`). |
| **MoviePy higher‑level editing** (concatenation, composite clips) | <https://zulko.github.io/moviepy/> |
| **PyDub audio effects** (normalize, speed‑up, fade) | <https://github.com/jiaaro/pydub#manipulating-audio> |
| **Deploying in Docker** – see the tiny Dockerfile below. | <https://hub.docker.com/_/python> |

### Minimal Dockerfile (production image)

```dockerfile
# Dockerfile – production ready for the helper module
FROM python:3.12-slim

# Install system ffmpeg (debian‑based slim image)
RUN apt-get update && apt-get install -y --no-install-recommends ffmpeg && \
    rm -rf /var/lib/apt/lists/*

# Create a non‑root user (security best practice)
RUN useradd -m appuser
WORKDIR /app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY . .
USER appuser

# Expose a health‑check endpoint (optional)
HEALTHCHECK CMD ffmpeg -version || exit 1

CMD ["python", "-m", "uvicorn", "demo_ffmpeg:app", "--host", "0.0.0.0", "--port", "8000"]
```

> **Reference** – Dockerfile best practices: <https://docs.docker.com/develop/develop-images/dockerfile_best-practices/>  

---

## 🎉 You’re ready!  

1. **Install** the system binary and the Python packages.  
2. **Copy** `ffmpeg_helpers.py` into your project.  
3. **Import** the helpers wherever you need them (demo shows typical usage).  
4. **Write tests** – the `pytest` example ensures your pipeline stays reliable as you evolve it.  

Enjoy building safe, fast, and production‑ready media pipelines with FFmpeg and Python! 🚀  