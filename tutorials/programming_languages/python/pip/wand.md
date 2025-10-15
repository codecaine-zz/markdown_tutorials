# Python Wand (ImageMagick) — Copy‑Paste Cookbook (v0.6.x)

This hands-on guide shows how to use Wand (the Python binding to ImageMagick) with small, reusable functions you can copy into your projects. It assumes Wand 0.6.x and a recent ImageMagick.

> Note: For full API details see the official docs: [Wand 0.6.12 User Guide](https://docs.wand-py.org/en/0.6.12/)

## Prerequisites (macOS)

- Install ImageMagick (with Ghostscript if you need PDF/PS/EPS):

```bash
# optional — only if you plan to process PDF/PS/EPS
brew install ghostscript

brew install imagemagick
```

- Install Wand in your Python environment:

```bash
pip install Wand
```

## Quick sanity check

```python
from wand.version import magick_version, magick_library_version
import wand

print("wand:", wand.__version__)
print("ImageMagick:", magick_version)
print("Magick lib:", magick_library_version)
```

If PDF handling fails, your ImageMagick may lack the Ghostscript delegate.

---

## Core patterns you’ll use a lot

```python
from pathlib import Path
from wand.image import Image
from wand.color import Color
from wand.drawing import Drawing
from wand.exceptions import MissingDelegateError, PolicyError, WandException


def open_image(path: str | Path) -> Image:
    """Open an image file and return a Wand Image handle.
    Use as a context manager when possible to auto-free resources.
    """
    return Image(filename=str(path))


def save_image(img: Image, out_path: str | Path, fmt: str | None = None) -> None:
    """Save the image. If fmt is given, enforce that format; otherwise infer from extension."""
    if fmt:
        img.format = fmt
    img.save(filename=str(out_path))


def bytes_to_image(data: bytes, fmt: str | None = None) -> Image:
    """Create an image from a bytes blob. Supply fmt for raw data without headers."""
    if fmt:
        return Image(blob=data, format=fmt)
    return Image(blob=data)


def image_to_bytes(img: Image, fmt: str = "png") -> bytes:
    """Serialize an image into bytes of the requested format."""
    return img.make_blob(format=fmt)
```

---

## I/O basics

```python
from wand.image import Image

def inspect_image(path: str) -> dict:
    """Return basic info: width, height, format, colorspace."""
    with Image(filename=path) as img:
        return {
            "width": img.width,
            "height": img.height,
            "format": img.format,
            "colorspace": getattr(img, "colorspace", None),
        }


def convert_format(src: str, dst: str) -> None:
    """Convert an image to another format by extension or explicit fmt."""
    with Image(filename=src) as img:
        img.save(filename=dst)  # extension decides format
```

---

## Resize, scale, and crop

```python
from wand.image import Image
from wand.color import Color

def resize_fit(src: str, dst: str, max_w: int, max_h: int) -> None:
    """Resize to fit within a box (preserves aspect)."""
    with Image(filename=src) as img:
        # Geometry string keeps aspect (like `convert -resize WxH`)
        img.transform(resize=f"{max_w}x{max_h}")
        img.save(filename=dst)


def resize_fill_center_crop(src: str, dst: str, w: int, h: int) -> None:
    """Fill and center-crop to exact WxH (useful for thumbnails)."""
    with Image(filename=src) as img:
        # `^` ensures image covers the box completely
        img.transform(resize=f"{w}x{h}^")
        # Center-crop to exact size
        img.crop(width=w, height=h, gravity='center')
        img.save(filename=dst)


def simple_resize(src: str, dst: str, w: int, h: int) -> None:
    """Resize to exact dimensions (may distort aspect)."""
    with Image(filename=src) as img:
        img.resize(w, h)
        img.save(filename=dst)


def rotate_with_bg(src: str, dst: str, degrees: float, bg: str = "white") -> None:
    with Image(filename=src) as img:
        img.background_color = Color(bg)
        img.rotate(degrees)
        img.save(filename=dst)
```

---

## Drawing and text

```python
from wand.image import Image
from wand.drawing import Drawing
from wand.color import Color

def add_text(src: str, dst: str, text: str, x: int, y: int,
             font: str | None = None, size: int = 32,
             fill: str = "#ffffff", stroke: str | None = "#000000",
             stroke_width: float = 1.0) -> None:
    with Image(filename=src) as img:
        with Drawing() as draw:
            if font:
                draw.font = font
            draw.font_size = size
            draw.fill_color = Color(fill)
            if stroke:
                draw.stroke_color = Color(stroke)
                draw.stroke_width = stroke_width
            draw.text(x, y, text)
            draw(img)
        img.save(filename=dst)


def draw_shapes(src: str, dst: str) -> None:
    """Example: rectangle and circle with fills and strokes."""
    with Image(filename=src) as img:
        with Drawing() as draw:
            draw.stroke_color = Color('#1f2937')
            draw.stroke_width = 3
            draw.fill_color = Color('#60a5fa')
            draw.rectangle(left=20, top=20, width=200, height=120, radius=10)

            draw.fill_color = Color('transparent')
            draw.stroke_color = Color('#ef4444')
            draw.circle((200, 200), (260, 200))  # center & perimeter points
            draw(img)
        img.save(filename=dst)
```

---

## Filters and adjustments

```python
from wand.image import Image

def gaussian_blur(src: str, dst: str, sigma: float = 2.0) -> None:
    with Image(filename=src) as img:
        img.gaussian_blur(radius=0, sigma=sigma)
        img.save(filename=dst)


def sharpen(src: str, dst: str, sigma: float = 1.0) -> None:
    with Image(filename=src) as img:
        img.sharpen(radius=0, sigma=sigma)
        img.save(filename=dst)


def grayscale(src: str, dst: str) -> None:
    with Image(filename=src) as img:
        img.type = 'grayscale'
        img.save(filename=dst)


def modulate_brightness_contrast(src: str, dst: str, brightness: int = 110,
                                 saturation: int = 100, hue: int = 100) -> None:
    """brightness/saturation/hue are percentages, 100 = no change."""
    with Image(filename=src) as img:
        img.modulate(brightness=brightness, saturation=saturation, hue=hue)
        img.save(filename=dst)
```

---

## Compositing and watermarks

```python
from wand.image import Image

def overlay_watermark(base_path: str, watermark_path: str, dst: str,
                      left: int = 10, top: int = 10, opacity: float = 0.5) -> None:
    """Overlay watermark PNG onto base image at (left, top) with opacity 0..1."""
    with Image(filename=base_path) as base:
        with Image(filename=watermark_path) as wm:
            # Apply global opacity to watermark
            wm.alpha_channel = 'activate'
            wm.evaluate(operator='multiply', value=opacity, channel='alpha')
            base.composite(wm, left=left, top=top)
        base.save(filename=dst)
```

---

## Transparency and background removal (simple case)

```python
from wand.image import Image
from wand.color import Color

def make_color_transparent(src: str, dst: str, color: str = 'white', fuzz_pct: float = 10.0) -> None:
    """Make a near-solid background color transparent. fuzz_pct ~ color tolerance (0..100)."""
    with Image(filename=src) as img:
        fuzz = int(img.quantum_range * (fuzz_pct / 100.0))
        img.transparent_color(Color(color), alpha=0.0, fuzz=fuzz)
        img.save(filename=dst)
```

---

## Metadata (EXIF) and stripping

```python
from wand.image import Image

def read_exif(path: str) -> dict:
    """Return a dict of EXIF/metadata keys. Keys look like 'exif:DateTimeOriginal'."""
    with Image(filename=path) as img:
        return dict(img.metadata)


def strip_metadata(src: str, dst: str) -> None:
    with Image(filename=src) as img:
        img.strip()
        img.save(filename=dst)
```

---

## Multipage PDFs to images

Requires ImageMagick built with Ghostscript. Use resolution to control quality/size.

```python
from pathlib import Path
from wand.image import Image
from wand.exceptions import MissingDelegateError, PolicyError

def pdf_to_pngs(pdf_path: str, out_dir: str, dpi: int = 200, fmt: str = 'png') -> list[str]:
    """Render each page of a PDF to separate images and return their paths."""
    out_paths: list[str] = []
    Path(out_dir).mkdir(parents=True, exist_ok=True)
    try:
        with Image(filename=pdf_path, resolution=dpi) as pdf:
            for i, page in enumerate(pdf.sequence):
                with Image(image=page) as img:
                    img.format = fmt
                    out_path = str(Path(out_dir) / f"page-{i + 1:03d}.{fmt}")
                    img.save(filename=out_path)
                    out_paths.append(out_path)
    except (MissingDelegateError, PolicyError) as e:
        # Missing Ghostscript delegate or policy restricts PDF
        raise RuntimeError(f"PDF rendering not available: {e}")
    return out_paths
```

---

## Animated GIFs

Split frames and build a new animation.

```python
from pathlib import Path
from wand.image import Image

def gif_split_frames(gif_path: str, out_dir: str, fmt: str = 'png') -> list[str]:
    Path(out_dir).mkdir(parents=True, exist_ok=True)
    outputs: list[str] = []
    with Image(filename=gif_path) as gif:
        for i, frame in enumerate(gif.sequence):
            with Image(image=frame) as img:
                img.format = fmt
                out_path = str(Path(out_dir) / f"frame-{i:03d}.{fmt}")
                img.save(filename=out_path)
                outputs.append(out_path)
    return outputs


def gif_make_from_frames(frame_paths: list[str], dst_gif: str, delay_cs: int = 10, loop: int = 0) -> None:
    """delay_cs = centiseconds per frame (10 = 100ms). loop=0 for infinite."""
    with Image() as animation:
        for p in frame_paths:
            with Image(filename=p) as frame:
                frame.delay = delay_cs
                animation.sequence.append(frame)
        # control looping via image property
        animation.options['gif:loop'] = str(loop)
        animation.save(filename=dst_gif)
```

---

## Histograms and basic color stats

```python
from collections import Counter
from wand.image import Image

def top_colors(path: str, k: int = 5) -> list[tuple[str, int]]:
    """Return top-k colors as hex and their counts (approx; can be heavy on large images)."""
    with Image(filename=path) as img:
        # Reduce to speed up
        small = img.clone()
        try:
            small.transform(resize='256x256')
            counts = Counter()
            for color, count in small.histogram.items():
                counts[color.string] += count
            return counts.most_common(k)
        finally:
            small.close()
```

---

## Robust error handling

```python
from wand.exceptions import MissingDelegateError, PolicyError, WandException
from wand.image import Image

def safe_convert(src: str, dst: str) -> bool:
    try:
        with Image(filename=src) as img:
            img.save(filename=dst)
        return True
    except MissingDelegateError as e:
        print("Delegate missing (e.g., Ghostscript for PDF):", e)
    except PolicyError as e:
        print("ImageMagick policy prevents operation:", e)
    except WandException as e:
        print("General Wand/ImageMagick error:", e)
    return False
```

---

## End-to-end mini utility (paste into a script)

```python
#!/usr/bin/env python3
from pathlib import Path
from typing import Iterable
from wand.image import Image
from wand.color import Color
from wand.drawing import Drawing
from wand.exceptions import MissingDelegateError, PolicyError


def ensure_dir(p: str | Path) -> None:
    Path(p).mkdir(parents=True, exist_ok=True)


def make_thumb(src: str, dst: str, w: int = 512, h: int = 512) -> None:
    with Image(filename=src) as img:
        img.transform(resize=f"{w}x{h}^")
        img.crop(width=w, height=h, gravity='center')
        img.strip()
        img.save(filename=dst)


def watermark(src: str, wm: str, dst: str) -> None:
    with Image(filename=src) as base, Image(filename=wm) as mark:
        mark.alpha_channel = 'activate'
        mark.evaluate(operator='multiply', value=0.5, channel='alpha')
        # bottom-right corner
        left = base.width - mark.width - 16
        top = base.height - mark.height - 16
        base.composite(mark, left=left, top=top)
        base.save(filename=dst)


def pdf_pages(pdf: str, out_dir: str, dpi: int = 200) -> list[str]:
    ensure_dir(out_dir)
    pages: list[str] = []
    try:
        with Image(filename=pdf, resolution=dpi) as doc:
            for i, page in enumerate(doc.sequence):
                with Image(image=page) as p:
                    p.format = 'png'
                    out = str(Path(out_dir) / f"page-{i+1:03d}.png")
                    p.save(filename=out)
                    pages.append(out)
    except (MissingDelegateError, PolicyError) as e:
        raise SystemExit(f"PDF not supported on this system: {e}")
    return pages


def annotate(src: str, dst: str, text: str) -> None:
    with Image(filename=src) as img, Drawing() as d:
        d.font_size = 36
        d.fill_color = Color('#ffffff')
        d.stroke_color = Color('#111827')
        d.stroke_width = 2
        d.text(24, 48, text)
        d(img)
        img.save(filename=dst)


if __name__ == "__main__":
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
        with Image(filename=args.src) as img:
            img.save(filename=args.dst)
```

---

## Tips

- Always use context managers (`with Image(...) as img:`) to free native resources.
- For big images, consider downscaling before heavy operations (histogram, blur).
- If something works in `magick` or `convert` CLI, there’s usually a 1:1 method or option in Wand; check the docs.
- For PDF/SVG/HEIC, support depends on how ImageMagick was built and which delegates are installed.

## Troubleshooting quickies

- “no decode delegate for this image format”: install the missing delegate (e.g., Ghostscript for PDF).
- “not authorized `PDF' @ error/constitute.c/ReadImage/412”: your ImageMagick policy.xml blocks PDFs. Adjust or use a sandbox.
- Color mismatch on macOS Preview vs browsers: ensure you embed or strip color profiles consistently.

---

## Try it (optional)

```bash
# Convert
python - <<'PY'
from wand.image import Image
with Image(filename='input.jpg') as img:
    img.save(filename='output.png')
print('done')
PY

# Thumbnail (center-crop)
python - <<'PY'
from wand.image import Image
with Image(filename='input.jpg') as img:
    img.transform(resize='512x512^')
    img.crop(width=512, height=512, gravity='center')
    img.save(filename='thumb.jpg')
print('done')
PY
```

---

That’s it—you now have a toolbox of Wand functions to drop into any script or project.

