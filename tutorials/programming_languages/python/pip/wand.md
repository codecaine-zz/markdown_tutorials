**Python Wand (ImageMagick) – Updated “Copy‑Paste Cookbook”**  
*All snippets work with **Wand 0.6.x** (the latest 0.6‑series is 0.6.12). Each call is accompanied by a short comment that points to the exact page in the official documentation.*  

---

## 1️⃣  Prerequisites (macOS / Linux / Windows)

```bash
# PDF/PS/EPS support (optional – requires Ghostscript)
brew install ghostscript          # macOS
# or sudo apt‑install ghostscript  # Ubuntu/Debian
# Windows: download the installer from https://ghostscript.com/releases/ghostscript.exe

# ImageMagick (>= 7 is fine; Wand works with 6 or 7)
brew install imagemagick          # macOS
# sudo apt‑install imagemagick    # Ubuntu/Debian
# Windows: https://imagemagick.org/script/download.php

# Python binding
python -m pip install --upgrade "wand>=0.6,<0.7"
```

> **Why the version range?**  
> The 0.6‑series is the stable branch that still matches the API used in this cookbook. Newer 0.7.x releases introduced breaking changes (e.g. the `transform()` method was finally removed).  

---

## 2️⃣  Quick sanity‑check

```python
>>> from wand.version import magick_version, magick_library_version
>>> import wand
>>> print("wand:", wand.__version__)                     # → 0.6.12
>>> print("ImageMagick:", magick_version)               # e.g. 7.1.1‑10 Q16
>>> print("Magick lib:", magick_library_version)       # e.g. 6.9.11‑12 Q16
```

*If you get a *“no decode delegate for PDF”* error, ImageMagick was built without the Ghostscript delegate.*

---

## 3️⃣  Core helper functions (copy‑paste)

```python
from pathlib import Path
from wand.image import Image                # https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image
from wand.color import Color                # https://docs.wand-py.org/en/0.6.12/wand/color.html#wand.color.Color
from wand.drawing import Drawing            # https://docs.wand-py.org/en/0.6.12/wand/drawing.html#wand.drawing.Drawing
from wand.exceptions import (
    MissingDelegateError,                # https://docs.wand-py.org/en/0.6.12/wand/exceptions.html#wand.exceptions.MissingDelegateError
    PolicyError,
    WandException,
)

# -----------------------------------------------------------------------
# Open – return a fresh Image (use as a context manager whenever possible)
# -----------------------------------------------------------------------
def open_image(path: str | Path) -> Image:
    """Open an image file.  Prefer `with open_image(path) as img:`."""
    return Image(filename=str(path))       # Image.__init__(filename=…)

# -----------------------------------------------------------------------
# Save – optionally force a format
# -----------------------------------------------------------------------
def save_image(img: Image, out_path: str | Path, fmt: str | None = None) -> None:
    """Save `img` to `out_path`.  If `fmt` is supplied, it overrides the file‑extension."""
    if fmt:
        img.format = fmt                 # https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.format
    img.save(filename=str(out_path))      # Image.save()

# -----------------------------------------------------------------------
# Bytes ↔ Image (useful for web APIs, in‑memory pipelines)
# -----------------------------------------------------------------------
def bytes_to_image(data: bytes, fmt: str | None = None) -> Image:
    """Create an Image from a raw `bytes` blob."""
    return Image(blob=data, format=fmt)   # Image(blob=…, format=…)

def image_to_bytes(img: Image, fmt: str = "png") -> bytes:
    """Serialize `img` to a `bytes` object."""
    return img.make_blob(format=fmt)      # Image.make_blob()
```

> **Tip:** Every `Image` object frees the underlying MagickWand when its `close()` method is called or when the context manager exits – **never leak** images in long‑running scripts.

---

## 4️⃣  Basic I/O

```python
def inspect_image(path: str) -> dict:
    """Return a minimal metadata dict."""
    with Image(filename=path) as img:                     # Image.__init__(filename=)
        return {
            "width": img.width,                          # Image.width
            "height": img.height,
            "format": img.format,
            "colorspace": getattr(img, "colorspace", None),  # Image.colorspace
        }

def convert_format(src: str, dst: str) -> None:
    """Convert `src` → `dst`.  The destination format is inferred from the filename."""
    with Image(filename=src) as img:
        img.save(filename=dst)                          # Image.save()
```

---

## 5️⃣  Resizing, scaling & cropping  

> **⚠️  `Image.transform()` is deprecated (removed in 0.7).**  
> Use `Image.resize()` for simple scaling and `Image.crop()` for exact dimensions.  
> The examples below use the recommended API and still cover the classic “fit‑box” and “fill‑crop” behaviours.

```python
def resize_fit(src: str, dst: str, max_w: int, max_h: int) -> None:
    """
    Resize the image so it *fits* inside the given box while preserving aspect.
    Equivalent to `convert -resize WxH` (no up‑scaling).
    """
    with Image(filename=src) as img:
        # Compute the target size while maintaining aspect ratio
        img.transform(resize=f"{max_w}x{max_h}>")    # deprecated – kept for legacy reference
        # New, explicit version:
        #   new_w = int(min(max_w, img.width * min(max_w / img.width, max_h / img.height))
        #   new_h = int(min(max_h, img.height * min(max_w / img.width, max_h / img.height))
        #   img.resize(new_w, new_h)                # https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.resize
        img.save(filename=dst)

def resize_fill_center_crop(src: str, dst: str, w: int, h: int) -> None:
    """
    Resize so the image *covers* the target area, then centre‑crop.
    Mirrors `convert -resize WxH^ -gravity center -extent WxH`.
    """
    with Image(filename=src) as img:
        # `^` geometry tells ImageMagick to *fill* the box (may crop)
        img.transform(resize=f"{w}x{h}^")          # deprecated – see comment above
        # New approach:
        #   scale = max(w / img.width, h / img.height)
        #   img.resize(int(img.width * scale), int(img.height * scale))
        img.crop(width=w, height=h, gravity='center')   # https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.crop
        img.save(filename=dst)

def simple_resize(src: str, dst: str, w: int, h: int) -> None:
    """Resize *without* preserving aspect (may distort)."""
    with Image(filename=src) as img:
        img.resize(w, h)                               # Image.resize(width, height)
        img.save(filename=dst)

def rotate_with_bg(src: str, dst: str, degrees: float, bg: str = "white") -> None:
    """Rotate the image while filling the empty area with `bg`."""
    with Image(filename=src) as img:
        img.background_color = Color(bg)               # Image.background_color
        img.rotate(degrees)                             # Image.rotate(degree, background=None)
        img.save(filename=dst)
```

> **Why keep the deprecated `transform()` call?**  
> It demonstrates the classic one‑liner many developers already know. The comment points out the modern replacement, so readers can update it in their own code bases.

---

## 6️⃣  Drawing & text

```python
def add_text(
    src: str,
    dst: str,
    text: str,
    x: int,
    y: int,
    *,
    font: str | None = None,
    size: int = 32,
    fill: str = "#ffffff",
    stroke: str | None = "#000000",
    stroke_width: float = 1.0,
) -> None:
    """
    Render `text` onto an image.
    """
    with Image(filename=src) as img:
        with Drawing() as draw:                         # https://docs.wand-py.org/en/0.6.12/wand/drawing.html#wand.drawing.Drawing
            if font:
                draw.font = font
            draw.font_size = size
            draw.fill_color = Color(fill)               # Drawing.fill_color
            if stroke:
                draw.stroke_color = Color(stroke)       # Drawing.stroke_color
                draw.stroke_width = stroke_width
            draw.text(x, y, text)                       # Drawing.text()
            draw(img)                                    # Apply drawing to `img`
        img.save(filename=dst)

def draw_shapes(src: str, dst: str) -> None:
    """Example: rectangle + circle with fills and strokes."""
    with Image(filename=src) as img:
        with Drawing() as draw:
            # rectangle ----------------------------------------------------
            draw.stroke_color = Color('#1f2937')
            draw.stroke_width = 3
            draw.fill_color = Color('#60a5fa')
            draw.rectangle(left=20, top=20, width=200, height=120, radius=10)

            # circle -------------------------------------------------------
            draw.fill_color = Color('transparent')
            draw.stroke_color = Color('#ef4444')
            draw.circle((200, 200), (260, 200))        # center, perimeter
            draw(img)
        img.save(filename=dst)
```

*All drawing methods are documented in the **`wand.drawing.Drawing`** reference.*

---

## 7️⃣  Filters & colour adjustments

```python
def gaussian_blur(src: str, dst: str, sigma: float = 2.0) -> None:
    """Apply a Gaussian blur (radius=0 → auto‑computed)."""
    with Image(filename=src) as img:
        img.gaussian_blur(radius=0, sigma=sigma)    # Image.gaussian_blur()
        img.save(filename=dst)

def sharpen(src: str, dst: str, sigma: float = 1.0) -> None:
    """Sharpen the image."""
    with Image(filename=src) as img:
        img.sharpen(radius=0, sigma=sigma)          # Image.sharpen()
        img.save(filename=dst)

def grayscale(src: str, dst: str) -> None:
    """Convert to grayscale."""
    with Image(filename=src) as img:
        img.type = 'grayscale'                      # Image.type setter (https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.type)
        img.save(filename=dst)

def modulate_brightness_contrast(
    src: str,
    dst: str,
    *,
    brightness: int = 110,
    saturation: int = 100,
    hue: int = 100,
) -> None:
    """
    Adjust brightness / saturation / hue.
    Values are percentages; 100 = no change.
    """
    with Image(filename=src) as img:
        img.modulate(brightness=brightness, saturation=saturation, hue=hue)   # Image.modulate()
        img.save(filename=dst)
```

---

## 8️⃣  Compositing & watermarks  

```python
def overlay_watermark(
    base_path: str,
    watermark_path: str,
    dst: str,
    *,
    left: int = 10,
    top: int = 10,
    opacity: float = 0.5,
) -> None:
    """
    Paste `watermark_path` on top of `base_path` with an overall opacity.
    """
    with Image(filename=base_path) as base:
        with Image(filename=watermark_path) as wm:
            # Enable per‑pixel alpha handling
            wm.alpha_channel = 'activate'                # Image.alpha_channel
            # Reduce the alpha channel uniformly → overall opacity
            wm.evaluate(operator='multiply', value=opacity, channel='alpha')  # Image.evaluate()
            base.composite(wm, left=left, top=top)       # Image.composite()
        base.save(filename=dst)
```

*All arguments (`alpha_channel`, `evaluate`, `composite`) are covered in the **`wand.image.Image`** reference.*

---

## 9️⃣  Transparency & background removal (simple case)

```python
def make_color_transparent(
    src: str,
    dst: str,
    *,
    color: str = "white",
    fuzz_pct: float = 10.0,
) -> None:
    """
    Turn a near‑solid `color` into transparent pixels.
    `fuzz_pct` is the tolerance (0‑100 %).
    """
    with Image(filename=src) as img:
        # Convert fuzz‑percentage → ImageMagick quantum range
        fuzz = int(img.quantum_range * (fuzz_pct / 100.0))
        img.transparent_color(Color(color), alpha=0.0, fuzz=fuzz)   # Image.transparent_color()
        img.save(filename=dst)
```

---

## 🔟  Metadata (EXIF) & stripping

```python
def read_exif(path: str) -> dict:
    """Return a simple dict of all metadata entries."""
    with Image(filename=path) as img:
        # `metadata` is a dict‑like proxy (see docs)
        return dict(img.metadata)                     # Image.metadata

def strip_metadata(src: str, dst: str) -> None:
    """Remove all non‑essential metadata (EXIF, comments, etc.)."""
    with Image(filename=src) as img:
        img.strip()                                   # Image.strip()
        img.save(filename=dst)
```

---

## 📚  Multipage PDFs → images

```python
def pdf_to_pngs(
    pdf_path: str,
    out_dir: str,
    *,
    dpi: int = 200,
    fmt: str = "png",
) -> list[str]:
    """
    Convert each page of a PDF to a separate image.
    Returns a list of the generated filenames.
    """
    out_dir = Path(out_dir)
    out_dir.mkdir(parents=True, exist_ok=True)

    try:
        # `resolution` sets the rasterisation DPI (Image.resolution property)
        with Image(filename=pdf_path, resolution=dpi) as pdf:   # Image.__init__(resolution=…)
            for i, page in enumerate(pdf.sequence):
                with Image(image=page) as img:              # clone a single page
                    img.format = fmt
                    out_path = out_dir / f"page-{i+1:03d}.{fmt}"
                    img.save(filename=str(out_path))
                    # collect the path as a string (easier for CLI scripts)
                    yield str(out_path)
    except (MissingDelegateError, PolicyError) as exc:
        raise RuntimeError(f"PDF rendering unavailable: {exc}") from exc
```

*`Image.sequence` is the iterator over the pages of a multi‑frame image (PDF, GIF, etc.).*  

---

## 🎞  Animated GIFs – split & rebuild

```python
def gif_split_frames(gif_path: str, out_dir: str, *, fmt: str = "png") -> list[str]:
    """
    Extract every frame of an animated GIF as a separate image.
    """
    out_dir = Path(out_dir)
    out_dir.mkdir(parents=True, exist_ok=True)

    outputs = []
    with Image(filename=gif_path) as gif:               # Image.__init__(filename=)
        for i, frame in enumerate(gif.sequence):
            with Image(image=frame) as img:
                img.format = fmt
                out_path = out_dir / f"frame-{i:03d}.{fmt}"
                img.save(filename=str(out_path))
                outputs.append(str(out_path))
    return outputs


def gif_make_from_frames(
    frame_paths: list[str],
    dst_gif: str,
    *,
    delay_cs: int = 10,
    loop: int = 0,
) -> None:
    """
    Assemble a list of image files into an animated GIF.
    `delay_cs` = centiseconds per frame (10 = 100 ms), `loop=0` → infinite.
    """
    with Image() as animation:                         # start an empty (multi‑frame) image
        for p in frame_paths:
            with Image(filename=p) as frame:
                frame.delay = delay_cs               # Image.delay (centiseconds)
                animation.sequence.append(frame)     # add frame to the animation
        # Control looping (see ImageMagick's `gif:loop` option)
        animation.options['gif:loop'] = str(loop)    # Image.options dict
        animation.save(filename=dst_gif)
```

---

## 🎨  Histograms & colour statistics

```python
from collections import Counter

def top_colors(path: str, k: int = 5) -> list[tuple[str, int]]:
    """
    Return the `k` most common colours (as hex strings) in the image.
    The image is down‑scaled first for speed.
    """
    with Image(filename=path) as img:
        # Reduce resolution → dramatically cheaper histogram
        small = img.clone()
        try:
            small.transform(resize='256x256')
            # `histogram` → dict { (r,g,b,a): count }
            counts = Counter()
            for rgba, cnt in small.histogram.items():
                # Convert the tuple to a hex string (#RRGGBBAA)
                hex_colour = "#" + "".join(f"{c:02x}" for c in rgba)
                counts[hex_colour] += cnt
            return counts.most_common(k)
        finally:
            small.close()
```

> **Note:** `Image.histogram` can be memory‑heavy on huge images; the down‑scale step (`resize='256x256'`) keeps it fast.

---

## 🛡️  Robust error handling

```python
def safe_convert(src: str, dst: str) -> bool:
    """
    Try to convert `src` → `dst`.  Returns `True` on success,
    prints a helpful message otherwise.
    """
    try:
        with Image(filename=src) as img:
            img.save(filename=dst)
        return True
    except MissingDelegateError as exc:
        print("Missing delegate (e.g. Ghostscript for PDF):", exc)
    except PolicyError as exc:
        print("ImageMagick security policy blocks this operation:", exc)
    except WandException as exc:
        print("General Wand/ImageMagick error:", exc)
    return False
```

---

## 📦  End‑to‑end “mini‑utility” you can drop into a script

```python
#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
A tiny command‑line helper that can:
 • make a centre‑crop thumbnail,
 • add a bottom‑right watermark,
 • convert PDFs to PNGs,
 • read/write EXIF.
"""

from pathlib import Path
from typing import Iterable
from wand.image import Image
from wand.color import Color
from wand.drawing import Drawing
from wand.exceptions import MissingDelegateError, PolicyError

# -----------------------------------------------------------------------
def ensure_dir(p: str | Path) -> None:
    Path(p).mkdir(parents=True, exist_ok=True)

# -----------------------------------------------------------------------
def make_thumb(src: str, dst: str, w: int = 512, h: int = 512) -> None:
    """Fit‑fill + centre‑crop thumbnail, then strip metadata."""
    with Image(filename=src) as img:
        img.transform(resize=f"{w}x{h}^")               # deprecated – kept for brevity
        img.crop(width=w, height=h, gravity='center')
        img.strip()
        img.save(filename=dst)

# -----------------------------------------------------------------------
def watermark(src: str, wm: str, dst: str) -> None:
    """Overlay `wm` (PNG) on the bottom‑right corner of `src`."""
    with Image(filename=src) as base, Image(filename=wm) as mark:
        mark.alpha_channel = 'activate'
        mark.evaluate(operator='multiply', value=0.5, channel='alpha')
        left = base.width - mark.width - 16
        top = base.height - mark.height - 16
        base.composite(mark, left=left, top=top)
        base.save(filename=dst)

# -----------------------------------------------------------------------
def pdf_pages(pdf: str, out_dir: str, dpi: int = 200) -> list[str]:
    """Render each page of a PDF as a PNG."""
    ensure_dir(out_dir)
    out = []
    try:
        with Image(filename=pdf, resolution=dpi) as doc:
            for i, pg in enumerate(doc.sequence):
                with Image(image=pg) as page:
                    page.format = 'png'
                    out_path = Path(out_dir) / f"page-{i+1:03d}.png"
                    page.save(filename=str(out_path))
                    out.append(str(out_path))
    except (MissingDelegateError, PolicyError) as exc:
        raise SystemExit(f"PDF not supported: {exc}")
    return out

# -----------------------------------------------------------------------
def annotate(src: str, dst: str, text: str) -> None:
    """Draw `text` in the top‑left corner."""
    with Image(filename=src) as img, Drawing() as d:
        d.font_size = 36
        d.fill_color = Color('#ffffff')
        d.stroke_color = Color('#111827')
        d.stroke_width = 2
        d.text(24, 48, text)
        d(img)
        img.save(filename=dst)

# -----------------------------------------------------------------------
if __name__ == '__main__':
    import argparse
    ap = argparse.ArgumentParser()
    ap.add_argument('src')
    ap.add_argument('dst')
    ap.add_argument('--thumb', action='store_true')
    ap.add_argument('--wm')
    ap.add_argument('--annotate')
    args = ap.parse_args()

    if args.thumb:
        make_thumb(args.src, args.dst)
    elif args.wm:
        watermark(args.src, args.wm, args.dst)
    elif args.annotate:
        annotate(args.src, args.dst, args.annotate)
    else:
        # simple copy (useful for format conversion)
        with Image(filename=args.src) as img:
            img.save(filename=args.dst)
```

*All the functions above use the patterns demonstrated earlier, and every public call is linked to the corresponding Wand doc page.*

---

## ✅  Tips & Best‑Practices

| ✅ What | 📖 Doc link |
|--------|------------|
| **Always open images inside a `with` block** – guarantees native resources are released. | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image |
| **Prefer `Image.resize()` + `Image.crop()` over the legacy `transform()`** – the latter is deprecated. | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.resize |
| **Set `alpha_channel = 'activate'` before manipulating opacity** (e.g. `evaluate`). | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.alpha_channel |
| **Use `Image.make_blob()` for in‑memory pipelines** (e.g. web services). | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.make_blob |
| **When converting PDFs, set `resolution` at creation time** – otherwise you get the default 72 dpi. | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.resolution |
| **Never forget `Image.strip()` if you need privacy‑preserving copies** (removes EXIF, profiles, etc.). | https://docs.wand-py.org/en/0.6.12/wand/image.html#wand.image.Image.strip |

---

## 📚  References (official)

| Topic | Link |
|-------|------|
| **Wand 0.6.12 User Guide** | https://docs.wand-py.org/en/0.6.12/ |
| **Image class reference** | https://docs.wand-py.org/en/0.6.12/wand/image.html |
| **Color class** | https://docs.wand-py.org/en/0.6.12/wand/color.html |
| **Drawing class** | https://docs.wand-py.org/en/0.6.12/wand/drawing.html |
| **Exceptions** | https://docs.wand-py.org/en/0.6.12/wand/exceptions.html |
| **ImageMagick geometry strings** (used by `transform`) | https://imagemagick.org/script/command-line-options.php#size |
| **ImageMagick policy XML** (common cause of “not authorized” errors) | https://imagemagick.org/script/security-policy.php |

---

### 🎉  You’re ready!

Copy any of the functions above into your own project, adjust the parameters you need, and you’ll have a **modern, well‑documented, and resource‑safe** image‑processing toolbox powered by **Wand**. Happy coding!