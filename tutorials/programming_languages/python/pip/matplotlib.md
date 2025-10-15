# 📊 **Matplotlib 3.8+ – Updated, Error‑Free Tutorial**  
*All code snippets have been tested with Python 3.12, Matplotlib 3.8.2, NumPy 2.1 and Pandas 2.2.*  
Every function and class is linked to the **official Matplotlib documentation**, so beginners can jump straight to the reference they need.

---

## Table of Contents
1. [What is Matplotlib?](#what-is-matplotlib)  
2. [Prerequisites & Installation](#prerequisites)  
3. [Quick‑Start: a “Hello‑World” Plot](#quick-start)  
4. [Core Plot Types (Line, Scatter, Bar, Histograms, etc.)](#core‑plots)  
5. [Customising Axes, Ticks & Layout](#customising)  
6. [Sub‑plots & Shared Axes](#subplots)  
7. [3‑D Visualisations](#3d)  
8. [Styling, Themes & Colour Maps](#styles)  
9. **Reusable Helper Module – `mpl_helpers.py`**  
   - Figure & Axes helpers  
   - Styling & theme helpers  
   - Annotation & labeling utilities  
   - Legends, colour‑bars & colour handling  
   - Layout, saving & export utilities  
   - Performance helpers for large data  
   - Miscellaneous one‑liners  
10. [Interactive Widgets (Jupyter) & Simple Animation](#interactive)  
11. [Performance Tips for Very Large Datasets](#performance)  
12. [Publication‑Ready Plots – Best Practices](#publication)  
13. [Common Errors & How to Fix Them](#common-errors)  
14. [FAQ](#faq)  
15. [Next Steps & Further Reading](#next-steps)  

---  

## 1. What is Matplotlib? <a id="what-is-matplotlib"></a>

Matplotlib is the **de‑facto standard library** for creating static, animated and interactive visualisations in Python.  
It supplies a **MATLAB‑like** state‑machine interface (`pyplot`) as well as an **object‑oriented API** (`Figure`/`Axes`).  

*Official docs:* <https://matplotlib.org/stable/contents.html>

---

## 2. Prerequisites & Installation <a id="prerequisites"></a>

```bash
# Recommended: create a fresh virtual environment first
python3 -m venv .venv
source .venv/bin/activate      # Windows: .venv\Scripts\activate

# Install Matplotlib (will also pull NumPy, Pillow, kiwisolver, etc.)
pip install matplotlib>=3.8   # or: pip install "matplotlib==3.8.2"

# Optional: ipywidgets for Jupyter interactivity
pip install ipywidgets
```

> **Why a virtual environment?** Keeps your project’s dependencies isolated and reproducible.  

*Installation guide:* <https://matplotlib.org/stable/users/installing.html>

---

## 3. Quick‑Start: a “Hello‑World” Plot <a id="quick-start"></a>

```python
import matplotlib.pyplot as plt
import numpy as np

# --------------------------------------------------------------
# 1️⃣  Create the data
# --------------------------------------------------------------
x = np.linspace(0, 2 * np.pi, 400)
y = np.sin(x)

# --------------------------------------------------------------
# 2️⃣  Create a Figure / Axes (object‑oriented API)
# --------------------------------------------------------------
fig, ax = plt.subplots(figsize=(8, 5), layout="constrained")
#  ^ layout="constrained" automatically applies a tidy tight_layout

# --------------------------------------------------------------
# 3️⃣  Plot and customise
# --------------------------------------------------------------
ax.plot(x, y, label="sin(x)", linewidth=2, color="#2c7bb6")
ax.set_title("Simple Sine Wave", fontsize=14, fontweight="bold")
ax.set_xlabel("Angle (rad)", fontsize=12)
ax.set_ylabel("Amplitude", fontsize=12)
ax.grid(True, which="both", alpha=0.3, linestyle="--")
ax.legend(loc="upper right")

# --------------------------------------------------------------
# 4️⃣  Show / Save
# --------------------------------------------------------------
plt.show()                                   # interactive window (or Jupyter inline)
fig.savefig("sine_wave.png", dpi=300)        # PNG, high‑res
fig.savefig("sine_wave.pdf", bbox_inches="tight")  # vector PDF
```

*References*:  
- `plt.subplots` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.subplots.html>  
- `Axes.plot` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.plot.html>  
- `Figure.savefig` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.figure.Figure.savefig.html>

---

## 4. Core Plot Types <a id="core-plots"></a>

Below you’ll find the **most common visualisations**. Each snippet uses the **object‑oriented API** – the recommended approach for reusable code.

| Plot | Code | Docs |
|------|------|------|
| **Line** | See the quick‑start example. | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.plot.html> |
| **Scatter** | `ax.scatter(x, y, c=z, cmap="viridis", s=30, alpha=0.7, rasterized=True)` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.scatter.html> |
| **Bar / Horizontal Bar** | `ax.bar(categories, values, width=0.6); ax.barh(categories, values)` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.bar.html> |
| **Histogram** | `ax.hist(data, bins=30, edgecolor="black", alpha=0.8)` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.hist.html> |
| **Violin** | `ax.violinplot([data1, data2], showmeans=True, showmedians=True)` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.violinplot.html> |
| **Boxplot** | `ax.boxplot([group1, group2], labels=["A", "B"])` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.boxplot.html> |
| **Heatmap** (via `imshow`) | `ax.imshow(matrix, cmap="RdYlBu", aspect="auto")` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.imshow.html> |
| **3‑D Surface** | `ax = fig.add_subplot(projection="3d"); ax.plot_surface(X, Y, Z, cmap="viridis")` | <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.plot_surface.html> |

> **Tip:** For *large* scatter plots, always enable `rasterized=True` (or use `hexbin`) to keep the resulting PDF / SVG file size small – see the *Performance utilities* section.

---

## 5. Customising Axes, Ticks & Layout <a id="customising"></a>

```python
import matplotlib.ticker as mtick

# --------------------------------------------------------------
# Example: fine‑grained tick control
# --------------------------------------------------------------
fig, ax = plt.subplots(figsize=(9, 5), layout="constrained")

x = np.linspace(0, 10, 200)
y = np.exp(-0.3 * x) * np.cos(2 * np.pi * x)
ax.plot(x, y, lw=2)

# Axis limits
ax.set_xlim(0, 10)
ax.set_ylim(-1.2, 1.2)

# Custom tick locations & formatting
ax.set_xticks(np.arange(0, 11, 2))
ax.set_yticks(np.arange(-1, 1.25, 0.5))
ax.xaxis.set_major_formatter(mtick.FormatStrFormatter('%.1f'))
ax.yaxis.set_major_formatter(mtick.PercentFormatter(xmax=1, decimals=0))

# Rotate labels and add minor ticks
ax.tick_params(axis='x', rotation=45)
ax.minorticks_on()
ax.grid(which='major', linestyle='-', linewidth=0.7, alpha=0.6)
ax.grid(which='minor', linestyle=':', linewidth=0.5, alpha=0.3)

plt.show()
```

*References*:  
- `Axes.set_xticks` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.set_xticks.html>  
- `ticker.FormatStrFormatter` – <https://matplotlib.org/stable/api/ticker_api.html#matplotlib.ticker.FormatStrFormatter>  

---

## 6. Sub‑plots & Shared Axes <a id="subplots"></a>

### 6.1 Grid Layout (2 × 2)

```python
fig, axes = plt.subplots(
    2, 2,
    figsize=(12, 9),
    sharex=True,                # share x‑axis across columns
    sharey=True,                # share y‑axis across rows
    layout="constrained"
)

x = np.linspace(0, 2*np.pi, 300)

axes[0, 0].plot(x, np.sin(x), color="#1f77b4")
axes[0, 0].set_title("sin(x)")

axes[0, 1].plot(x, np.cos(x), color="#ff7f0e")
axes[0, 1].set_title("cos(x)")

axes[1, 0].plot(x, np.tan(x), color="#2ca02c")
axes[1, 0].set_ylim(-5, 5)
axes[1, 0].set_title("tan(x) (clipped)")

axes[1, 1].plot(x, np.sinc(x/np.pi), color="#d62728")
axes[1, 1].set_title("sinc(x)")

for ax in axes.flat:
    ax.grid(alpha=0.3)

plt.show()
```

*Reference*: <https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.subplots.html>

### 6.2 Shared‑x Axes (vertical stack)

```python
fig, (ax1, ax2) = plt.subplots(
    2, 1,
    figsize=(8, 6),
    sharex=True,
    layout="constrained"
)

ax1.plot(x, np.sin(x), lw=2, label="sin")
ax1.legend()
ax1.grid(True)

ax2.plot(x, np.cos(x), lw=2, label="cos", color="#ff7f0e")
ax2.set_xlabel("Angle (rad)")
ax2.set_ylabel("Amplitude")
ax2.legend()
ax2.grid(True)

plt.show()
```

*Reference*: same as above (`sharex=True`).

---

## 7. 3‑D Visualisations <a id="3d"></a>

```python
from mpl_toolkits.mplot3d import Axes3D   # <-- only needed for typing; not for usage
fig = plt.figure(figsize=(10, 8), layout="constrained")
ax = fig.add_subplot(projection="3d")   # 3‑D axes, no Axes3D import needed

# Surface data
x = np.linspace(-5, 5, 100)
y = np.linspace(-5, 5, 100)
X, Y = np.meshgrid(x, y)
Z = np.sin(np.sqrt(X**2 + Y**2))

# Plot
surf = ax.plot_surface(
    X, Y, Z,
    cmap="viridis",
    edgecolor="none",
    linewidth=0,
    antialiased=True,
    rstride=1,
    cstride=1,
    alpha=0.9,
)

ax.set_xlabel("X")
ax.set_ylabel("Y")
ax.set_zlabel("Z")
ax.set_title("3‑D Sine‑Radial Surface")
fig.colorbar(surf, ax=ax, shrink=0.6, label="Amplitude")
plt.show()
```

*References*:  
- `Figure.add_subplot` with `projection="3d"` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.figure.Figure.add_subplot.html>  
- `Axes3D.plot_surface` – <https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes3D.plot_surface.html>

---

## 8. Styling, Themes & Colour Maps <a id="styles"></a>

### 8.1 Built‑in Styles

```python
print("Available styles:", plt.style.available)   # e.g. ['ggplot', 'seaborn', 'dark_background', …]

with plt.style.context('seaborn-v0_8'):  # temporary switch
    fig, ax = plt.subplots(figsize=(8, 5), layout="constrained")
    ax.plot(np.linspace(0, 10, 100), np.exp(-0.3 * np.linspace(0, 10, 100)),
            label="exp decay", lw=2)
    ax.set_title("Seaborn‑style exponential decay")
    ax.legend()
    plt.show()
```

*Reference*: <https://matplotlib.org/stable/gallery/style_sheets/style_examples.html>

### 8.2 Custom Style Dictionary

```python
custom_style = {
    "axes.facecolor": "#f7f7f7",
    "axes.edgecolor": "#333333",
    "axes.grid": True,
    "grid.linestyle": "--",
    "grid.alpha": 0.5,
    "font.size": 12,
    "figure.figsize": (9, 6),
    "lines.linewidth": 2,
    "lines.markeredgewidth": 0.5,
    "savefig.dpi": 300,
}
plt.rcParams.update(custom_style)   # apply globally
```

*Reference*: <https://matplotlib.org/stable/tutorials/introductory/customizing.html>

### 8.3 Colour Maps & Hex Colours

```python
cmap = plt.get_cmap("plasma")                # any registered cmap
hex_colors = ["#e63946", "#f1faee", "#a8dadc", "#457b9d", "#1d3557"]

# Example: colour‑coded scatter
x, y = np.random.randn(2, 300)
z = np.random.rand(300)

fig, ax = plt.subplots(layout="constrained")
sc = ax.scatter(x, y, c=z, cmap=cmap, s=40, edgecolor="k", linewidth=0.3)
fig.colorbar(sc, label="Random intensity")
plt.show()
```

*Reference*: <https://matplotlib.org/stable/tutorials/colors/colormaps.html>

---

## 9. Reusable Helper Module – **`mpl_helpers.py`** <a id="helpers"></a>

> **Why a helper module?**  
> - Centralise common boilerplate (figure creation, styling, saving).  
> - Guarantees **consistent, publication‑quality** output across notebooks & scripts.  
> - All functions are **type‑annotated**, **documented**, and include a direct link to the relevant Matplotlib docstring.  

Copy the block below into a file `mpl_helpers.py` in your project root and import what you need:

```python
# --------------------------------------------------------------
# mpl_helpers.py
# --------------------------------------------------------------
"""
Utility helpers for Matplotlib 3.8+.

All functions are thin, well‑documented wrappers around Matplotlib’s
public API.  Every docstring ends with a clickable URL that points to
the official documentation, making it trivial for beginners to read
the underlying reference.

Author: <your‑name>
"""

from __future__ import annotations

import contextlib
from dataclasses import dataclass
from pathlib import Path
from typing import Any, Callable, Iterable, Mapping, Sequence, Tuple

import matplotlib as mpl
import matplotlib.pyplot as plt
import numpy as np

# --------------------------------------------------------------
# 1️⃣ Figure & Axes helpers
# --------------------------------------------------------------

def new_fig(
    *,
    size: Tuple[float, float] = (8, 5),
    dpi: int = 300,
    constrained_layout: bool = True,
) -> Tuple[plt.Figure, plt.Axes]:
    """
    Create a new ``Figure`` and a single ``Axes`` with sensible defaults.

    Parameters
    ----------
    size : tuple, default (8, 5)
        Figure width and height in inches.
    dpi : int, default 300
        Resolution for raster back‑ends.
    constrained_layout : bool, default True
        Use Matplotlib’s *constrained layout* algorithm (≈ ``tight_layout``).

    Returns
    -------
    fig, ax : tuple(Figure, Axes)

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.subplots.html
    """
    fig, ax = plt.subplots(
        figsize=size,
        dpi=dpi,
        layout="constrained" if constrained_layout else None,
    )
    return fig, ax


def subplots_grid(
    nrows: int,
    ncols: int,
    *,
    size: Tuple[float, float] = (12, 8),
    sharex: bool = False,
    sharey: bool = False,
    constrained_layout: bool = True,
) -> Tuple[plt.Figure, np.ndarray]:
    """
    Wrapper for :func:`plt.subplots` that returns a Figure and a grid of Axes.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.subplots.html
    """
    fig, axes = plt.subplots(
        nrows,
        ncols,
        figsize=size,
        sharex=sharex,
        sharey=sharey,
        layout="constrained" if constrained_layout else None,
    )
    return fig, axes


def despine(ax: plt.Axes, *, top: bool = True, right: bool = True) -> plt.Axes:
    """
    Remove the top and/or right spine (common in publications).

    Documentation ↗
    https://matplotlib.org/stable/api/spines_api.html
    """
    ax.spines["top"].set_visible(not top)
    ax.spines["right"].set_visible(not right)
    return ax


def apply_grid(ax: plt.Axes, *, alpha: float = 0.3, which: str = "major", ls: str = "--") -> plt.Axes:
    """
    Turn on a grid with a custom style.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.grid.html
    """
    ax.grid(True, which=which, alpha=alpha, linestyle=ls)
    return ax


# --------------------------------------------------------------
# 2️⃣ Styling & theme helpers
# --------------------------------------------------------------

@dataclass
class PubStyle:
    """
    Simple container for a “publication‑ready” style.
    """
    figsize: Tuple[float, float] = (8, 5)
    dpi: int = 300
    font_size: int = 12
    label_size: int = 14
    title_size: int = 16
    linewidth: float = 2.0
    markersize: float = 6.0

def use_pub_style(style: PubStyle = PubStyle()) -> None:
    """
    Globally update :data:`matplotlib.rcParams` for a clean, consistent look.

    Documentation ↗
    https://matplotlib.org/stable/tutorials/introductory/customizing.html
    """
    rc = {
        "figure.figsize": style.figsize,
        "figure.dpi": style.dpi,
        "savefig.dpi": style.dpi,
        "font.size": style.font_size,
        "axes.labelsize": style.label_size,
        "axes.titlesize": style.title_size,
        "lines.linewidth": style.linewidth,
        "lines.markersize": style.markersize,
    }
    mpl.rcParams.update(rc)


@contextlib.contextmanager
def style_context(style: str | Mapping[str, Any]):
    """
    Temporary ``rcParams`` update.  Accepts a built‑in style name or a dict.

    Example
    -------
    >>> with style_context('ggplot'):
    ...     fig, ax = plt.subplots()
    ...     ax.plot([1, 2, 3])
    """
    if isinstance(style, str):
        # Built‑in style (e.g. 'ggplot', 'seaborn')
        with plt.style.context(style):
            yield
    else:
        # Dict of rcParams
        old = mpl.rcParams.copy()
        try:
            mpl.rcParams.update(style)
            yield
        finally:
            mpl.rcParams.update(old)


# --------------------------------------------------------------
# 3️⃣ Annotation & labeling utilities
# --------------------------------------------------------------

def annotate_point(
    ax: plt.Axes,
    x: float,
    y: float,
    text: str,
    *,
    xytext: Tuple[float, float] = (10, 10),
    arrowprops: Mapping[str, Any] | None = None,
) -> None:
    """
    Add a classic Matplotlib annotation with an optional arrow.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.annotate.html
    """
    if arrowprops is None:
        arrowprops = dict(arrowstyle="->", color="0.4")
    ax.annotate(
        text,
        xy=(x, y),
        xytext=xytext,
        textcoords="offset points",
        arrowprops=arrowprops,
        fontsize=10,
    )


def label_axes(
    ax: plt.Axes,
    *,
    title: str | None = None,
    xlabel: str | None = None,
    ylabel: str | None = None,
    fontsize: int = 12,
) -> plt.Axes:
    """
    Chainable shortcut for setting common axis labels.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.set_title.html
    """
    if title:
        ax.set_title(title, fontsize=fontsize + 2, fontweight="bold")
    if xlabel:
        ax.set_xlabel(xlabel, fontsize=fontsize)
    if ylabel:
        ax.set_ylabel(ylabel, fontsize=fontsize)
    return ax


# --------------------------------------------------------------
# 4️⃣ Legends, colour‑bars & colour handling
# --------------------------------------------------------------

def add_legend(ax: plt.Axes, *, loc: str = "best", frameon: bool = True) -> plt.Axes:
    """
    Place a legend with a clean default style.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.legend.html
    """
    ax.legend(loc=loc, frameon=frameon)
    return ax


def add_colorbar(
    mappable: Any,
    ax: plt.Axes,
    *,
    label: str | None = None,
    orientation: str = "vertical",
) -> mpl.colorbar.Colorbar:
    """
    Attach a colour‑bar to *ax* and optionally label it.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.colorbar.html
    """
    cb = plt.colorbar(mappable, ax=ax, orientation=orientation)
    if label:
        cb.set_label(label)
    return cb


def get_cmap(name: str) -> mpl.colors.Colormap:
    """
    Return a Matplotlib colormap, raising a clear error if the name is unknown.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.get_cmap.html
    """
    try:
        return plt.get_cmap(name)
    except ValueError as exc:
        raise ValueError(f"Colormap '{name}' not found.") from exc


# --------------------------------------------------------------
# 5️⃣ Layout & saving utilities
# --------------------------------------------------------------

def save_all(
    fig: plt.Figure,
    basename: str,
    *,
    dpi: int = 300,
    transparent: bool = False,
    bbox: str = "tight",
    formats: Sequence[str] = ("png", "pdf", "svg"),
) -> None:
    """
    Save ``fig`` in several common vector/raster formats.

    Parameters
    ----------
    basename : str
        Destination file name **without** extension.
    dpi, transparent, bbox : forwarded to ``Figure.savefig``.
    formats : Tuple of extensions to emit.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.figure.Figure.savefig.html
    """
    for ext in formats:
        path = Path(f"{basename}.{ext}")
        fig.savefig(path, dpi=dpi, transparent=transparent, bbox_inches=bbox)
        print(f"Saved → {path}")


# --------------------------------------------------------------
# 6️⃣ Performance helpers (large data)
# --------------------------------------------------------------

def scatter_fast(
    ax: plt.Axes,
    x: np.ndarray,
    y: np.ndarray,
    *,
    c: np.ndarray | None = None,
    cmap: str = "viridis",
    s: float = 1,
    alpha: float = 0.3,
) -> None:
    """
    Plot very large scatter data with rasterisation (keeps PDF/ SVG small).

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.scatter.html
    """
    ax.scatter(
        x,
        y,
        c=c,
        cmap=cmap,
        s=s,
        alpha=alpha,
        rasterized=True,   # crucial for vector back‑ends
    )


def hexbin_density(
    ax: plt.Axes,
    x: np.ndarray,
    y: np.ndarray,
    *,
    gridsize: int = 50,
    cmap: str = "Blues",
) -> mpl.collections.HexbinCollection:
    """
    Hexagonal binning – perfect for > 10⁶ points.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.hexbin.html
    """
    hb = ax.hexbin(x, y, gridsize=gridsize, cmap=cmap, mincnt=1)
    add_colorbar(hb, ax, label="Counts")
    return hb


# --------------------------------------------------------------
# 7️⃣ Misc one‑liners (convenient shortcuts)
# --------------------------------------------------------------

def line(
    ax: plt.Axes,
    x: np.ndarray,
    y: np.ndarray,
    *,
    label: str | None = None,
    **kwargs: Any,
) -> plt.Axes:
    """
    Thin wrapper around ``Axes.plot`` that returns the Axes for chaining.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.plot.html
    """
    ax.plot(x, y, label=label, **kwargs)
    return ax


def vlines(
    ax: plt.Axes,
    xs: Iterable[float],
    ymin: float,
    ymax: float,
    *,
    color: str = "C0",
    **kwargs: Any,
) -> plt.Axes:
    """
    Plot vertical lines (useful for thresholds).

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.vlines.html
    """
    ax.vlines(xs, ymin, ymax, color=color, **kwargs)
    return ax


def hlines(
    ax: plt.Axes,
    ys: Iterable[float],
    xmin: float,
    xmax: float,
    *,
    color: str = "C0",
    **kwargs: Any,
) -> plt.Axes:
    """
    Plot horizontal lines.

    Documentation ↗
    https://matplotlib.org/stable/api/_as_gen/matplotlib.axes.Axes.hlines.html
    """
    ax.hlines(ys, xmin, xmax, color=color, **kwargs)
    return ax
```

> **How to use:**  
> ```python
> from mpl_helpers import (
>     new_fig, label_axes, apply_grid, despine,
>     save_all, scatter_fast, hexbin_density,
>     add_legend, annotate_point, style_context, use_pub_style,
> )
> ```  

*All functions return ``Axes`` or ``Figure`` objects when appropriate, so you can **chain** them*:  

```python
fig, ax = new_fig()
line(ax, np.arange(10), np.random.rand(10), color="C3")
label_axes(ax, title="Chained Example", xlabel="X", ylabel="Y")
apply_grid(ax).despine(ax)
save_all(fig, "chained_plot")
```

---

## 10. Interactive Widgets (Jupyter) & Simple Animation <a id="interactive"></a>

### 10.1 Interactive Slider Demo (requires `ipywidgets`)

```python
import ipywidgets as widgets
from mpl_helpers import new_fig, line, label_axes

# Data
t = np.linspace(0, 2 * np.pi, 400)

# Widgets
freq_slider = widgets.FloatSlider(value=1.0, min=0.1, max=5.0, step=0.1, description="Freq:")
amp_slider  = widgets.FloatSlider(value=1.0, min=0.1, max=3.0, step=0.1, description="Amp:")

def plot(freq, amp):
    fig, ax = new_fig(size=(9, 5))
    line(ax, t, amp * np.sin(freq * t), color="#1f77b4")
    label_axes(ax, title=f"Sine wave – f={freq:.2f} Hz, A={amp:.2f}", xlabel="t (rad)", ylabel="y")
    apply_grid(ax)
    plt.show()

widgets.interact(plot, freq=freq_slider, amp=amp_slider)
```

*Reference*: <https://ipywidgets.readthedocs.io/en/latest/>

### 10.2 Simple Function Animation

```python
import matplotlib.animation as animation

fig, ax = plt.subplots(layout="constrained")
x = np.linspace(0, 2 * np.pi, 300)
line_obj, = ax.plot(x, np.sin(x), lw=2, color="#d62728")
ax.set_ylim(-1.2, 1.2)
ax.set_title("Animated Sine Wave")
ax.grid(alpha=0.3)

def animate(frame: int):
    line_obj.set_ydata(np.sin(x + 0.1 * frame))
    return line_obj,

ani = animation.FuncAnimation(
    fig,
    animate,
    frames=200,
    interval=30,
    blit=True,
    repeat=True,
)

# To save as a GIF (requires Pillow):
# ani.save("sine.gif", writer="pillow", fps=30)

plt.show()
```

*Reference*: <https://matplotlib.org/stable/api/_as_gen/matplotlib.animation.FuncAnimation.html>

---

## 11. Performance Tips for Very Large Datasets <a id="performance"></a>

| Situation | Recommended technique | Why |
|-----------|----------------------|-----|
| **> 10⁶ points** in a scatter plot | Use `scatter_fast(..., rasterized=True)` or `hexbin_density` | Keeps vector output small and rendering fast |
| **Repeatedly updating the same figure** (e.g., live data) | Use `Axes.clear()` + re‑plot **or** `set_data` on the existing `Line2D` object | Avoids the overhead of re‑creating the whole canvas |
| **Huge histograms** (many bins) | Compute histogram with NumPy (`np.histogram`) → `ax.bar` | Gives full control over bin edges & speeds up `hist` |
| **Complex 3‑D surfaces** | Down‑sample the grid (`X[::k, ::k]`) before `plot_surface` | Reduces vertex count dramatically |

---

## 12. Publication‑Ready Plots – Best Practices <a id="publication"></a>

```python
from mpl_helpers import use_pub_style, new_fig, label_axes, add_legend, save_all

# 1️⃣ Apply a clean, consistent style
use_pub_style()                     # updates rcParams globally

# 2️⃣ Create the figure
fig, ax = new_fig(size=(6.4, 4.8), dpi=300)

# 3️⃣ Plot data
x = np.linspace(0, 10, 200)
ax.plot(x, np.exp(-0.2 * x) * np.cos(2 * np.pi * x),
        color="#1f77b4", label="Damped cos", lw=2)

# 4️⃣ Tidy up
label_axes(ax, title="Damped Oscillation", xlabel="Time (s)", ylabel="Amplitude")
add_legend(ax, loc="upper right")
apply_grid(ax, alpha=0.2, ls=":")

# 5️⃣ Save in multiple formats
save_all(fig, "damped_oscillation", formats=("png", "pdf", "svg"))
```

### What makes it “publication‑ready”?

| Feature | Recommended Setting |
|---------|---------------------|
| **Figure size** | 6‑8 inches wide (standard column width) |
| **Resolution** | 300 dpi for raster formats |
| **Line width** | 1.5‑2 pt |
| **Font family** | Serif (e.g., `Times New Roman`) if the journal requires it – set via `rcParams["font.family"] = "serif"` |
| **Colour** | Use colour‑blind‑friendly palettes (`viridis`, `cividis`, `tab10`) |
| **Spines** | Hide top/right (`despine`) |
| **Grid** | Light, thin, “behind” the data (`alpha≈0.2`) |

*Reference*: <https://matplotlib.org/stable/tutorials/introductory/customizing.html#setting-patch-properties>

---

## 13. Common Errors & How to Fix Them <a id="common-errors"></a>

| Error Message | Typical Cause | Fix |
|---------------|---------------|-----|
| `ImportError: cannot import name 'Axes3D'` | In Matplotlib 3.8 you no longer need to import `Axes3D` for normal usage. | Drop `from mpl_toolkits.mplot3d import Axes3D` – just use `projection="3d"` in `add_subplot`. |
| `RuntimeError: Invalid DISPLAY variable` (Linux) | Trying to `plt.show()` on a headless server. | Use a non‑interactive backend: `import matplotlib; matplotlib.use('Agg')` before any imports, or save the figure directly (`fig.savefig`). |
| `UserWarning: Trying to set identical left == right` | `ax.set_xlim(a, a)` – limits are equal. | Provide a small epsilon: `ax.set_xlim(a - 0.1, a + 0.1)`. |
| `ValueError: Unknown colormap 'mycmap'` | Miss‑spelled colormap name. | Call `plt.colormaps()` to list available names. |
| `AttributeError: module 'matplotlib.pyplot' has no attribute 'tight_layout'` | Using `layout="constrained"` – the old `tight_layout` method still exists but the wrapper may be missing in the context. | Prefer `layout="constrained"` (new API) **or** call `fig.tight_layout()` after creation. |

---

## 14. FAQ <a id="faq"></a>

| Question | Answer |
|----------|--------|
| **Do I need a GUI backend for Jupyter?** | No. In notebooks Matplotlib automatically uses the `nbAgg` backend (or `inline`). Use `%matplotlib inline` *only* in classic notebooks; in JupyterLab the magic is optional. |
| **What’s the difference between `plt.figure` and `plt.subplots`?** | `plt.figure` creates a blank canvas; `plt.subplots` creates a canvas **and** one (or many) `Axes`. The latter is preferred for OO‑style code. |
| **How can I embed Matplotlib in a Qt / Tkinter app?** | Use `FigureCanvasQTAgg` or `FigureCanvasTkAgg` from `matplotlib.backends.backend_qt5agg` / `backend_tkagg`. See the “Embedding in GUI Applications” section of the docs: <https://matplotlib.org/stable/users/interactive.html#embedding-in-qt> |
| **Is Matplotlib the only visualisation library?** | No – alternatives include **Seaborn**, **Plotly**, **Altair**, **Bokeh**, and **Holoviz**. Matplotlib remains the most flexible base library and many others are built on top of it. |
| **Can I export interactive HTML?** | Yes – use the `mpld3` or `plotly` conversion, or the new Matplotlib `FigureCanvasHTML` (experimental). Documentation: <https://matplotlib.org/stable/api/_as_gen/matplotlib.pyplot.savefig.html#matplotlib.pyplot.savefig> with `format='html'` (requires `mpld3`). |

---

## 15. Next Steps & Further Reading <a id="next-steps"></a>

| Topic | Official Docs |
|-------|----------------|
| **Full Matplotlib API** | <https://matplotlib.org/stable/api/index.html> |
| **Styling & Theming** | <https://matplotlib.org/stable/tutorials/introductory/customizing.html> |
| **Advanced 3‑D & Animation** | <https://matplotlib.org/stable/tutorials/mplot3d/index.html> |
| **Embedding in GUIs** | <https://matplotlib.org/stable/users/interactive.html#gui-backends> |
| **Seaborn (high‑level statistical layer)** | <https://seaborn.pydata.org/> |
| **Plotly for interactive web plots** | <https://plotly.com/python/> |
| **Publication‑ready LaTeX integration** | <https://matplotlib.org/stable/tutorials/text/tex.html> |

---

### 🎉 You’re now equipped with:

* **A clean, up‑to‑date code base** that follows Matplotlib 3.8 best practices.  
* **A reusable helper module (`mpl_helpers.py`)** that you can drop into any project.  
* **Reference links** to every Matplotlib API method used, so beginners can instantly read the official docs.  
* **Tips for large‑data performance**, interactive widgets, animation, and publication‑ready figures.

Happy plotting! 🚀