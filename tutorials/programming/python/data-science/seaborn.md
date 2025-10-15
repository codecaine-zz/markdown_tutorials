# Seaborn — Practical Tutorial (with Table of Contents)

A hands-on, copy‑pasteable guide for data visualization with Seaborn. It builds on matplotlib and pandas to make statistical plots easier and prettier.

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start](#quick-start)
- [Load Sample Datasets](#load-sample-datasets)
- [Figure‑level vs. Axes‑level functions](#figurelevel-vs-axeslevel-functions)
- [Styling and Themes](#styling-and-themes)
- [Categorical Plots](#categorical-plots)
- [Distributions](#distributions)
- [Relational Plots](#relational-plots)
- [Regression / Trend Lines](#regression--trend-lines)
- [Matrix / Heatmaps (incl. correlation)](#matrix--heatmaps-incl-correlation)
- [Pairwise & Joint Relationships](#pairwise--joint-relationships)
- [Faceting with Grids](#faceting-with-grids)
- [Time Series](#time-series)
- [Error Bars & Confidence Intervals](#error-bars--confidence-intervals)
- [The Seaborn Objects Interface](#the-seaborn-objects-interface)
- [Customization & Matplotlib Interop](#customization--matplotlib-interop)
- [Color Palettes](#color-palettes)
- [Saving Figures](#saving-figures)
- [Missing Data](#missing-data)
- [Reusable utilities (copy-paste)](#reusable-utilities-copy-paste)
- [Performance Tips](#performance-tips)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

Seaborn streamlines statistical data visualization on top of matplotlib. It understands tidy DataFrames, provides smart defaults, and offers high‑level functions for common plot types.

## Install

Follow the official install guidance if you need specifics for your OS/env: [Seaborn Installing](https://seaborn.pydata.org/installing.html)

Quick installs:

```bash
python -m pip install seaborn
# optional but common deps
python -m pip install pandas matplotlib
```

## Quick Start

```python
import seaborn as sns
import pandas as pd
import matplotlib.pyplot as plt

# Sample data
penguins = sns.load_dataset('penguins')  # requires internet

# Basic scatter plot with automatic legend
sns.scatterplot(data=penguins, x='bill_length_mm', y='bill_depth_mm', hue='species')
plt.title('Penguins bill (mm)')
plt.tight_layout()
plt.show()
```

## Load Sample Datasets

Seaborn can fetch some datasets online:

```python
import seaborn as sns

df = sns.load_dataset('tips')
penguins = sns.load_dataset('penguins')
flights = sns.load_dataset('flights')
```

Prefer local CSV/Parquet for offline work:

```python
import pandas as pd

df = pd.read_csv('myfile.csv')
```

## Figure‑level vs. Axes‑level functions

- Axes‑level (e.g., `sns.scatterplot`, `sns.histplot`) draw on a specific matplotlib Axes. You can compose them on subplots.
- Figure‑level (e.g., `sns.relplot`, `sns.catplot`, `sns.displot`, `sns.lmplot`) create their own FacetGrid/figure and manage facets/legends.

```python
# Axes‑level
fig, ax = plt.subplots()
sns.histplot(data=df, x='total_bill', ax=ax)
ax.set_title('Histogram (Axes‑level)')

# Figure‑level
g = sns.displot(data=df, x='total_bill', col='time')
g.set_titles('{col_name}')
plt.show()
```

## Styling and Themes

```python
import seaborn as sns
sns.set_theme(style='whitegrid', context='talk')
# styles: white, dark, whitegrid, darkgrid, ticks
# contexts: paper, notebook, talk, poster
```

```python
# Fine‑tune rc params via set_theme
sns.set_theme(
    style='ticks',
    rc={'axes.spines.right': False, 'axes.spines.top': False}
)
```

## Categorical Plots

```python
import seaborn as sns
import matplotlib.pyplot as plt

df = sns.load_dataset('tips')

# Count of categories
sns.countplot(data=df, x='day', hue='sex')
plt.title('Counts by day and sex')
plt.show()

# Box/violin to compare distributions across categories
sns.boxplot(data=df, x='day', y='total_bill', hue='smoker')
plt.title('Total bill by day (box)')
plt.show()

sns.violinplot(data=df, x='day', y='total_bill', hue='smoker', split=True)
plt.title('Total bill by day (violin)')
plt.show()

# Strip/swarm for individual points
sns.swarmplot(data=df, x='day', y='total_bill', color='.25')
plt.title('Swarm plot')
plt.show()

# Barplot shows aggregate (mean by default) with CI
sns.barplot(data=df, x='day', y='tip', hue='sex', errorbar='sd')
plt.title('Average tip by day/sex (±SD)')
plt.show()
```

## Distributions

```python
import seaborn as sns
import matplotlib.pyplot as plt

df = sns.load_dataset('penguins').dropna()

# Histogram
sns.histplot(data=df, x='flipper_length_mm', bins=20, kde=True)
plt.title('Flipper length (hist + kde)')
plt.show()

# KDE
sns.kdeplot(data=df, x='flipper_length_mm', hue='species', fill=True, common_norm=False)
plt.title('KDE by species')
plt.show()

# ECDF
sns.ecdfplot(data=df, x='bill_length_mm', hue='species')
plt.title('ECDF of bill length')
plt.show()
```

## Relational Plots

```python
import seaborn as sns
import matplotlib.pyplot as plt

df = sns.load_dataset('penguins').dropna()

sns.scatterplot(data=df, x='bill_length_mm', y='bill_depth_mm', hue='species', style='sex', size='body_mass_g')
plt.title('Bill length vs depth')
plt.show()

# Lines over time or ordered x
fmri = sns.load_dataset('fmri')
sns.lineplot(data=fmri, x='timepoint', y='signal', hue='event', errorbar='se')
plt.title('fmri signal by timepoint')
plt.show()
```

## Regression / Trend Lines

```python
import seaborn as sns
import matplotlib.pyplot as plt

df = sns.load_dataset('tips')

# Axes‑level regplot
sns.regplot(data=df, x='total_bill', y='tip', scatter_kws={'alpha': 0.5})
plt.title('Tip vs total bill with linear fit')
plt.show()

# Figure‑level lmplot with faceting
sns.lmplot(data=df, x='total_bill', y='tip', col='time', hue='smoker')
plt.show()
```

## Matrix / Heatmaps (incl. correlation)

```python
import seaborn as sns
import matplotlib.pyplot as plt
import numpy as np

penguins = sns.load_dataset('penguins').dropna()

# Correlation heatmap
corr = penguins.select_dtypes(include=np.number).corr(numeric_only=True)
plt.figure(figsize=(6, 5))
sns.heatmap(corr, annot=True, cmap='vlag', center=0)
plt.title('Correlation (numeric columns)')
plt.tight_layout()
plt.show()

# General matrix heatmap
flights = sns.load_dataset('flights').pivot(index='month', columns='year', values='passengers')
plt.figure(figsize=(8, 6))
sns.heatmap(flights, cmap='mako', cbar_kws={'label': 'Passengers'})
plt.title('Flights passengers by month/year')
plt.tight_layout()
plt.show()
```

## Pairwise & Joint Relationships

```python
import seaborn as sns

penguins = sns.load_dataset('penguins').dropna()

# Pairplot
sns.pairplot(penguins, hue='species', corner=True)

# Jointplot (scatter + marginal hist)
sns.jointplot(data=penguins, x='bill_length_mm', y='bill_depth_mm', hue='species', kind='kde')
```

## Faceting with Grids

```python
import seaborn as sns
import matplotlib.pyplot as plt

penguins = sns.load_dataset('penguins').dropna()

g = sns.FacetGrid(penguins, col='species', hue='sex', height=3, aspect=1)
g.map_dataframe(sns.scatterplot, x='bill_length_mm', y='bill_depth_mm')
g.add_legend()
g.set_axis_labels('Bill length (mm)', 'Bill depth (mm)')
plt.show()

# Figure‑level shortcuts build on FacetGrid
sns.relplot(data=penguins, x='bill_length_mm', y='bill_depth_mm', col='species', hue='sex')
plt.show()
```

## Time Series

```python
import seaborn as sns
import pandas as pd
import matplotlib.pyplot as plt

flights = sns.load_dataset('flights')
flights['date'] = pd.to_datetime(flights['year'].astype(str) + '-' + flights['month'] + '-01')

sns.lineplot(data=flights, x='date', y='passengers')
plt.title('Passengers over time')
plt.tight_layout()
plt.show()
```

## Error Bars & Confidence Intervals

Many functions compute uncertainty intervals automatically. Control with `errorbar`:

```python
import seaborn as sns
import matplotlib.pyplot as plt

df = sns.load_dataset('tips')

sns.barplot(data=df, x='day', y='tip', estimator='mean', errorbar=('pi', 95))  # percentile interval
plt.title('Tip mean ± 95% PI')
plt.show()

sns.lineplot(data=df, x='size', y='tip', errorbar='sd')  # standard deviation
plt.title('Tip vs party size ± SD')
plt.show()
```

## The Seaborn Objects Interface

Seaborn also provides a grammar‑style API under `seaborn.objects` (v0.13+). It’s composable and flexible:

```python
import seaborn.objects as so
import seaborn as sns
import matplotlib.pyplot as plt

penguins = sns.load_dataset('penguins').dropna()

p = (
    so.Plot(penguins, x='bill_length_mm', y='bill_depth_mm', color='species')
      .add(so.Point(alpha=0.7))
      .add(so.Line(), so.PolyFit(order=1))  # regression line
      .label(title='Bills by species', x='Bill length (mm)', y='Bill depth (mm)')
)

p.show()  # or p.layout(size=(6, 4)).show()
```

## Customization & Matplotlib Interop

```python
import matplotlib.pyplot as plt
import seaborn as sns

ax = sns.scatterplot(data=sns.load_dataset('penguins').dropna(), x='bill_length_mm', y='bill_depth_mm', hue='species')
ax.set_xlabel('Bill length (mm)')
ax.set_ylabel('Bill depth (mm)')
ax.set_title('Customized labels')
ax.legend(title='Species', loc='best')
plt.tight_layout()
plt.show()
```

You can get the underlying `Figure/Axes` objects from axes‑level calls or via `g.fig`/`g.axes` from figure‑level `FacetGrid` returns.

## Color Palettes

```python
import seaborn as sns
import matplotlib.pyplot as plt

print(sns.color_palette().as_hex())
print(sns.palettes.SEABORN_PALETTES.keys())  # named palettes

# Use a palette
sns.set_palette('deep')  # or 'muted', 'pastel', 'dark', 'colorblind'

# Continuous colormap
sns.kdeplot(data=sns.load_dataset('penguins'), x='bill_length_mm', hue='species', fill=True, palette='viridis')
plt.show()
```

Create custom palettes:

```python
palette = sns.color_palette(['#264653', '#2a9d8f', '#e9c46a', '#f4a261', '#e76f51'])
sns.set_palette(palette)
```

## Saving Figures

```python
import seaborn as sns
import matplotlib.pyplot as plt

ax = sns.histplot(data=sns.load_dataset('tips'), x='total_bill')
ax.figure.savefig('hist_total_bill.png', dpi=200, bbox_inches='tight')
```

For figure‑level, use `g.fig.savefig('file.png', ...)`.

## Missing Data

```python
import seaborn as sns
import pandas as pd
import numpy as np

penguins = sns.load_dataset('penguins')

# Drop or impute
clean = penguins.dropna()  # simple option
# Or: penguins.fillna({'bill_length_mm': penguins['bill_length_mm'].median()})

sns.scatterplot(data=clean, x='bill_length_mm', y='bill_depth_mm')
```

## Reusable utilities (copy-paste)

Drop-in helpers to speed up common Seaborn workflows. Each block is self-contained; copy only what you need.

### Plot theming and saving

```python
from contextlib import contextmanager
import seaborn as sns
import matplotlib as mpl

@contextmanager
def themed(style: str = 'whitegrid', context: str = 'notebook', font_scale: float = 1.1,
           palette: str | list = 'deep', rc: dict | None = None):
    """Scoped plotting theme.
    Usage:
        with themed(style='ticks', context='talk', palette='colorblind'):
            sns.scatterplot(...)
    """
    rc = rc or {}
    with sns.axes_style(style), sns.plotting_context(context, font_scale=font_scale), sns.color_palette(palette), mpl.rc_context(rc):
        yield


def savefig(ax_or_fig, path: str, dpi: int = 200, transparent: bool = False,
            tight: bool = True, facecolor: str = 'white') -> None:
    """Save a Matplotlib figure with sane defaults (works for Seaborn axes- or figure-level).
    Examples:
        ax = sns.histplot(...); savefig(ax, 'hist.png')
    """
    import matplotlib.pyplot as plt  # noqa: F401, for backends
    fig = ax_or_fig.figure if hasattr(ax_or_fig, 'figure') else ax_or_fig
    fig.savefig(path, dpi=dpi, bbox_inches=('tight' if tight else None),
                facecolor=facecolor, transparent=transparent)
```

### Data preparation helpers

```python
import pandas as pd
import numpy as np

def order_by_stat(df: pd.DataFrame, by: str, y: str, stat: str | callable = 'mean', ascending: bool = True) -> list:
    """Return category order sorted by a statistic over y.
    stat: 'mean' | 'median' | 'count' | callable(series)->scalar
    """
    g = df.groupby(by)[y]
    if stat == 'mean': vals = g.mean()
    elif stat == 'median': vals = g.median()
    elif stat == 'count': vals = g.size()
    else: vals = g.apply(stat) if callable(stat) else g.mean()
    return list(vals.sort_values(ascending=ascending).index)


def sample_if_large(df: pd.DataFrame, max_rows: int = 100_000, random_state: int | None = 0) -> pd.DataFrame:
    """Downsample large DataFrames for faster plotting."""
    n = len(df)
    return df.sample(n=max_rows, random_state=random_state) if n > max_rows else df
```

### Axes annotations & legends

```python
import matplotlib.pyplot as plt

def add_value_labels(ax: plt.Axes, fmt: str = '{:.2f}', spacing: int = 3, rotation: int = 0) -> None:
    """Annotate bar/column charts with values.
    Works for vertical (bar) and horizontal (barh) automatically.
    """
    for p in ax.patches:
        # Determine orientation by comparing width/height magnitude
        if p.get_height() >= p.get_width():  # vertical bar
            val = p.get_height()
            x = p.get_x() + p.get_width() / 2
            y = p.get_y() + p.get_height()
            ax.annotate(fmt.format(val), (x, y), ha='center', va='bottom',
                        xytext=(0, spacing), textcoords='offset points', rotation=rotation)
        else:  # horizontal bar
            val = p.get_width()
            x = p.get_x() + p.get_width()
            y = p.get_y() + p.get_height() / 2
            ax.annotate(fmt.format(val), (x, y), ha='left', va='center',
                        xytext=(spacing, 0), textcoords='offset points', rotation=rotation)


def move_legend(ax: plt.Axes, loc: str = 'upper left', bbox_to_anchor: tuple = (1, 1), frameon: bool = False) -> plt.Axes:
    """Move legend outside the axes (right side by default)."""
    ax.legend(loc=loc, bbox_to_anchor=bbox_to_anchor, frameon=frameon)
    return ax
```

### FacetGrid helpers

```python
import seaborn as sns

def set_shared_limits(g: sns.axisgrid.FacetGrid, xlim: tuple | None = None, ylim: tuple | None = None) -> sns.axisgrid.FacetGrid:
    """Set same x/y limits across all facets."""
    for ax in g.axes.flat:
        if xlim is not None:
            ax.set_xlim(xlim)
        if ylim is not None:
            ax.set_ylim(ylim)
    return g


def facet_titles(g: sns.axisgrid.FacetGrid, template: str = '{col_name}', size: int = 11) -> sns.axisgrid.FacetGrid:
    """Uniform facet titles with template and font size."""
    g.set_titles(template)
    for ax in g.axes.flat:
        if ax.get_title():
            ax.set_title(ax.get_title(), fontsize=size)
    return g
```

### Correlation heatmap convenience

```python
import numpy as np
import pandas as pd
import seaborn as sns

def corr_heatmap(df: pd.DataFrame, cols: list[str] | None = None, method: str = 'pearson',
                 annot: bool = False, fmt: str = '.2f', cmap: str = 'vlag', center: float = 0,
                 mask_upper: bool = True, **heatmap_kws):
    """Compute numeric correlation and plot a neat heatmap.
    Returns Matplotlib Axes.
    """
    data = df[cols] if cols is not None else df.select_dtypes(include=np.number)
    corr = data.corr(method=method, numeric_only=True)
    mask = np.triu(np.ones_like(corr, dtype=bool)) if mask_upper else None
    ax = sns.heatmap(corr, mask=mask, annot=annot, fmt=fmt, cmap=cmap, center=center, **heatmap_kws)
    ax.set_title('Correlation matrix')
    return ax
```

### Color utilities

```python
from matplotlib.colors import to_rgb, to_hex
import colorsys

def adjust_lightness(color: str, amount: float = 1.0) -> str:
    """Lighten/darken a color. amount>1 -> lighter, <1 -> darker."""
    r, g, b = to_rgb(color)
    h, l, s = colorsys.rgb_to_hls(r, g, b)
    l = max(0, min(1, l * amount))
    r2, g2, b2 = colorsys.hls_to_rgb(h, l, s)
    return to_hex((r2, g2, b2))


def lighten(color: str, factor: float = 1.2) -> str:
    return adjust_lightness(color, factor)


def darken(color: str, factor: float = 0.8) -> str:
    return adjust_lightness(color, factor)
```

### Pre-aggregate with CIs for fast bar/line plots

```python
import numpy as np
import pandas as pd

def summarize_ci(df: pd.DataFrame, x: str, y: str, ci: float = 95.0, estimator: str | callable = 'mean') -> pd.DataFrame:
    """Aggregate y by x with normal-approx confidence intervals.
    Returns columns: [x, y, y_low, y_high, n].
    """
    grp = df.groupby(x)[y]
    n = grp.size()
    if estimator == 'mean':
        m = grp.mean()
    elif estimator == 'median':
        m = grp.median()
        # For median, SE estimate is rough; here use 1.253*sd/sqrt(n) as a quick approx
        sd = grp.std(ddof=1)
        se = 1.253 * sd / np.sqrt(n)
    else:
        m = grp.apply(estimator) if callable(estimator) else grp.mean()
        sd = grp.std(ddof=1)
        se = sd / np.sqrt(n)
    if estimator == 'mean':
        sd = grp.std(ddof=1)
        se = sd / np.sqrt(n)
    z = 1.96 if abs(ci - 95.0) < 1e-6 else (1.645 if abs(ci - 90.0) < 1e-6 else (2.576 if abs(ci - 99.0) < 1e-6 else 1.96))
    lo = m - z * se
    hi = m + z * se
    return pd.DataFrame({x: m.index, y: m.values, f'{y}_low': lo.values, f'{y}_high': hi.values, 'n': n.values})
```

## Performance Tips

- Sample large datasets for complex plots (e.g., 1e6 rows → sample 100k for scatter).
- Disable transparency for huge scatters (`alpha=1`) and avoid heavy edgecolors.
- Pre‑aggregate in pandas for bar/line plots instead of relying on internal grouping when data is huge.
- Use vector formats (SVG/PDF) for line art; raster (PNG) for very dense plots.

## Troubleshooting

- Empty/blank plots: ensure you call `plt.show()` (if running as a script) or are in an interactive environment.
- Mixed data types on x/y: convert to numeric with `pd.to_numeric(..., errors='coerce')`.
- Overlapping labels: rotate ticks (`plt.xticks(rotation=45)`) and use `plt.tight_layout()`.
- On macOS with high‑DPI displays, set a backend or adjust figure size/dpi if rendering appears small.

## References

- Official: [Seaborn Installing](https://seaborn.pydata.org/installing.html)
- API: [https://seaborn.pydata.org/api.html](https://seaborn.pydata.org/api.html)
- Tutorial: [https://seaborn.pydata.org/tutorial.html](https://seaborn.pydata.org/tutorial.html)

---

Copy a block, run it, and adapt to your dataset. Visualize with confidence.
