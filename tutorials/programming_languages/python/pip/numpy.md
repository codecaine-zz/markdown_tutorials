# NumPy: Installation and Quickstart (pip)

## Table of Contents

1. [What is NumPy?](#what-is-numpy)
2. [Prerequisites](#prerequisites)
3. [Install with pip](#install-with-pip)
   - [Basic install](#basic-install)
   - [Specific version](#specific-version)
   - [Upgrade existing](#upgrade-existing)
   - [User install (no sudo)](#user-install-no-sudo)
4. [Virtual environments (recommended)](#virtual-environments-recommended)
5. [Verify installation](#verify-installation)
6. [Quickstart: reusable helper functions](#quickstart-reusable-helper-functions)
    - [Helper module](#helper-module)
    - [Using the helpers](#using-the-helpers)
7. [Common tasks (copy-paste snippets)](#common-tasks-copy-paste-snippets)
8. [Optimized, reusable utility functions (copy-paste cookbook)](#optimized-reusable-utility-functions-copy-paste-cookbook)
    - [IO and performance](#io-and-performance)
    - [Array creation and shapes](#array-creation-and-shapes)
    - [Dtypes and NaNs](#dtypes-and-nans)
    - [Math and normalization](#math-and-normalization)
    - [Distances and similarity](#distances-and-similarity)
    - [Indexing and set ops](#indexing-and-set-ops)
    - [Rolling and stride tricks](#rolling-and-stride-tricks)
    - [Binning and outliers](#binning-and-outliers)
    - [Random and sampling](#random-and-sampling)
    - [Misc utilities](#misc-utilities)
9. [Common issues and fixes](#common-issues-and-fixes)
10. [Advanced options](#advanced-options)
11. [Uninstall and cleanup](#uninstall-and-cleanup)
12. [FAQ](#faq)
13. [Next steps](#next-steps)

---

## What is NumPy?

NumPy is the fundamental package for scientific computing with Python. It provides fast N-dimensional arrays, vectorized operations, linear algebra, random number generation, and broadcasting.

## Prerequisites

Make sure you have:

- Python 3.8+ (3.10–3.12 recommended)
- pip (Python package manager)

Tip (macOS): prefer `python3` explicitly to avoid version confusion.

## Install with pip

### Basic install

```bash
python3 -m pip install numpy
```

### Specific version

```bash
python3 -m pip install "numpy==1.26.4"
```

### Upgrade existing

```bash
python3 -m pip install --upgrade numpy
```

### User install (no sudo)

```bash
python3 -m pip install --user numpy
```

## Virtual environments (recommended)

Keep project dependencies isolated with venv:

```bash
# create
python3 -m venv .venv

# activate (zsh/bash on macOS/Linux)
source .venv/bin/activate

# install inside the venv
pip install numpy
```

Deactivate later with `deactivate`.

## Verify installation

### In Python

```python
import numpy as np
print(np.__version__)
```

### Package metadata

```bash
pip show numpy
```

---

## Quickstart: reusable helper functions

The examples below wrap common NumPy tasks into small, reusable functions you can copy into a `numpy_helpers.py` module and import anywhere.

### Helper module

```python
# numpy_helpers.py
from __future__ import annotations

from typing import Iterable, Tuple, Union
import numpy as np

ArrayLike = Union[np.ndarray, Iterable[float], Iterable[int]]


def show_versions() -> None:
    """Print NumPy and related library versions if installed."""
    import importlib

    libs = ["numpy", "scipy", "pandas", "matplotlib"]
    for lib in libs:
        try:
            mod = importlib.import_module(lib)
            ver = getattr(mod, "__version__", "unknown")
            print(f"{lib}: {ver}")
        except Exception:
            print(f"{lib}: not installed")


def rng(seed: int = 42) -> np.random.Generator:
    """Return a reproducible random generator."""
    return np.random.default_rng(seed)


def make_demo_array(shape: Tuple[int, int] = (4, 5), seed: int = 0) -> np.ndarray:
    """Create a small demo 2D array of integers."""
    r = rng(seed)
    return r.integers(0, 100, size=shape)


def ensure_array(x: ArrayLike, dtype=None, copy: bool = False) -> np.ndarray:
    """Convert input to numpy array with optional dtype/copy."""
    return np.array(x, dtype=dtype, copy=copy)


def stats(a: ArrayLike, axis: int | None = None, keepdims: bool = False) -> dict:
    """Return common statistics in a dict: mean, std, min, q25, median, q75, max."""
    a = ensure_array(a)
    q = np.quantile(a, [0.25, 0.5, 0.75], axis=axis, keepdims=keepdims)
    return {
        "mean": np.mean(a, axis=axis, keepdims=keepdims),
        "std": np.std(a, axis=axis, keepdims=keepdims, ddof=0),
        "min": np.min(a, axis=axis, keepdims=keepdims),
        "q25": q[0],
        "median": q[1],
        "q75": q[2],
        "max": np.max(a, axis=axis, keepdims=keepdims),
    }


def zscore(a: ArrayLike, axis: int | None = None, eps: float = 1e-12) -> np.ndarray:
    """Standard-score normalize: (x - mean) / std along axis."""
    a = ensure_array(a)
    m = np.mean(a, axis=axis, keepdims=True)
    s = np.std(a, axis=axis, keepdims=True)
    return (a - m) / (s + eps)


def minmax_scale(a: ArrayLike, axis: int | None = None, eps: float = 1e-12) -> np.ndarray:
    """Scale to [0,1] along axis."""
    a = ensure_array(a)
    mn = np.min(a, axis=axis, keepdims=True)
    mx = np.max(a, axis=axis, keepdims=True)
    return (a - mn) / (mx - mn + eps)


def to_one_hot(indices: ArrayLike, num_classes: int, dtype=np.float32) -> np.ndarray:
    """One-hot encode 1D integer indices to shape (n, num_classes)."""
    idx = ensure_array(indices, dtype=int)
    out = np.zeros((idx.size, num_classes), dtype=dtype)
    out[np.arange(idx.size), idx] = 1
    return out


def softmax(x: ArrayLike, axis: int = -1) -> np.ndarray:
    """Stable softmax along axis."""
    x = ensure_array(x)
    x_max = np.max(x, axis=axis, keepdims=True)
    e = np.exp(x - x_max)
    return e / np.sum(e, axis=axis, keepdims=True)


def topk(a: ArrayLike, k: int, axis: int = -1, largest: bool = True) -> tuple[np.ndarray, np.ndarray]:
    """Return (values, indices) of top-k along axis."""
    a = ensure_array(a)
    if largest:
        part_idx = np.argpartition(a, -k, axis=axis)[..., -k:]
    else:
        part_idx = np.argpartition(a, k - 1, axis=axis)[..., :k]
    part_vals = np.take_along_axis(a, part_idx, axis=axis)
    order = np.argsort(part_vals, axis=axis)
    if largest:
        order = np.flip(order, axis=axis)
    idx = np.take_along_axis(part_idx, order, axis=axis)
    vals = np.take_along_axis(a, idx, axis=axis)
    return vals, idx


def sliding_window(a: ArrayLike, window: int, step: int = 1, axis: int = 0) -> np.ndarray:
    """Create a sliding window view with given step along an axis."""
    from numpy.lib.stride_tricks import sliding_window_view

    v = sliding_window_view(ensure_array(a), window_shape=window, axis=axis)
    slicer = [slice(None)] * v.ndim
    slicer[axis] = slice(0, None, step)
    return v[tuple(slicer)]


def pad_to_shape(a: ArrayLike, shape: Tuple[int, ...], constant: float = 0) -> np.ndarray:
    """Pad array with a constant value to reach target shape (no cropping)."""
    a = ensure_array(a)
    pads = []
    for cur, target in zip(a.shape, shape):
        add = max(target - cur, 0)
        pads.append((0, add))
    pads.extend([(0, 0)] * (len(shape) - a.ndim))
    return np.pad(a, pad_width=pads, mode="constant", constant_values=constant)


def save_npy(a: ArrayLike, path: str) -> None:
    """Save array to .npy file."""
    np.save(path, ensure_array(a))


def load_npy(path: str) -> np.ndarray:
    """Load array from .npy file."""
    return np.load(path)


def safe_solve(A: ArrayLike, b: ArrayLike) -> np.ndarray:
    """Solve Ax=b; fall back to pseudo-inverse if singular."""
    A = ensure_array(A, dtype=float)
    b = ensure_array(b, dtype=float)
    try:
        return np.linalg.solve(A, b)
    except np.linalg.LinAlgError:
        return np.linalg.pinv(A) @ b
```

### Using the helpers

```python
# quickstart_demo.py
import numpy as np
from numpy_helpers import (
    show_versions,
    rng,
    make_demo_array,
    stats,
    zscore,
    minmax_scale,
    to_one_hot,
    softmax,
    topk,
    sliding_window,
    pad_to_shape,
    save_npy,
    load_npy,
    safe_solve,
)


def run_demo() -> None:
    show_versions()

    A = make_demo_array((3, 4), seed=123)
    print("\nA:\n", A, sep="")

    print("\nStats:", stats(A, axis=None))
    print("\nZ-score (axis=0):\n", zscore(A, axis=0), sep="")
    print("\nMinMax (axis=1):\n", minmax_scale(A, axis=1), sep="")

    idx = np.array([0, 2, 1, 2])
    print("\nOne-hot:\n", to_one_hot(idx, num_classes=3), sep="")

    x = np.array([[1.0, 2.0, 3.0]])
    print("\nSoftmax:\n", softmax(x, axis=1), sep="")

    vals, indices = topk(A, k=2, axis=1, largest=True)
    print("\nTop-2 per row (vals):\n", vals, sep="")
    print("Indices:\n", indices, sep="")

    w = sliding_window(np.arange(10), window=4, step=2)
    print("\nSliding windows over 0..9 (win=4, step=2):\n", w, sep="")

    padded = pad_to_shape(A, shape=(5, 6), constant=-1)
    print("\nPadded to (5,6):\n", padded, sep="")

    save_npy(A, "demo.npy")
    A2 = load_npy("demo.npy")
    print("\nLoaded equals original:", np.array_equal(A, A2))

    # Linear system example
    B = np.array([[3.0, 1.0], [1.0, 2.0]])
    y = np.array([9.0, 8.0])
    x = safe_solve(B, y)
    print("\nSolve Bx=y => x =", x)


if __name__ == "__main__":
    run_demo()
```

---

## Common tasks (copy-paste snippets)

Short, focused utilities that you can drop into scripts.

### Create arrays

```python
import numpy as np

zeros = np.zeros((2, 3))
ones = np.ones((2, 3))
ar = np.arange(0, 10, 2)      # 0,2,4,6,8
lin = np.linspace(0, 1, 5)    # 5 points from 0..1
r = np.random.default_rng(0)
rand = r.random((2, 2))       # uniform [0,1)
```

### Reshape and stack

```python
import numpy as np

a = np.arange(6)
b = a.reshape(2, 3)
h = np.hstack([b, b])  # (2,6)
v = np.vstack([b, b])  # (4,3)
```

### Masking and where

```python
import numpy as np

a = np.array([1, 4, 7, 2])
mask = a > 3
filtered = a[mask]         # [4,7]
replaced = np.where(a > 3, 1, 0)  # [0,1,1,0]
```

### Linear algebra

```python
import numpy as np

A = np.array([[1., 2.], [3., 4.]])
b = np.array([1., 1.])
x = np.linalg.solve(A, b)
u, s, vh = np.linalg.svd(A)
eigvals, eigvecs = np.linalg.eig(A)
```

### Broadcasting example

```python
import numpy as np

X = np.arange(6).reshape(2, 3)   # (2,3)
bias = np.array([10, 20, 30])     # (3,)
Y = X + bias                      # (2,3) via broadcasting
```

---

## Optimized, reusable utility functions (copy-paste cookbook)

Drop these into a `numpy_utils.py` module. They emphasize vectorization, numerical stability, and clear contracts.

```python
# numpy_utils.py
from __future__ import annotations

from typing import Iterable, Sequence, Tuple
import numpy as np

ArrayLike = Iterable[float] | Iterable[int] | np.ndarray


# =========================
# IO and performance
# =========================

def as_array(x: ArrayLike, dtype=None, copy: bool = False) -> np.ndarray:
    """Convert to ndarray with optional dtype and copy."""
    return np.array(x, dtype=dtype, copy=copy)


def bytes_mb(a: np.ndarray) -> float:
    """Return array memory footprint in MB."""
    return a.nbytes / (1024 ** 2)


def ensure_c_contiguous(a: np.ndarray) -> np.ndarray:
    """Return a C-contiguous view/copy for faster ops in many libs."""
    return np.ascontiguousarray(a)


# =========================
# Array creation and shapes
# =========================

def repeat_to_shape(a: ArrayLike, shape: Tuple[int, ...]) -> np.ndarray:
    """Tile/repeat smaller array to match a target shape exactly (wraps with tile)."""
    a = as_array(a)
    reps = [int(np.ceil(t / s)) for s, t in zip(a.shape, shape)]
    out = np.tile(a, reps)
    return out[tuple(slice(0, t) for t in shape)]


def pad_to_shape(a: ArrayLike, shape: Tuple[int, ...], constant: float = 0) -> np.ndarray:
    """Pad array with constant to reach shape (no cropping)."""
    a = as_array(a)
    pads = []
    for cur, target in zip(a.shape, shape):
        add = max(target - cur, 0)
        pads.append((0, add))
    pads.extend([(0, 0)] * (len(shape) - a.ndim))
    return np.pad(a, pad_width=pads, mode="constant", constant_values=constant)


# =========================
# Dtypes and NaNs
# =========================

def is_nan_safe(a: ArrayLike) -> np.ndarray:
    """Vectorized NaN check, works for float dtypes."""
    a = as_array(a)
    return np.isnan(a)


def replace_nan(a: ArrayLike, value: float = 0.0) -> np.ndarray:
    """Return copy with NaNs replaced by value."""
    a = as_array(a, dtype=float, copy=True)
    a[np.isnan(a)] = value
    return a


def nanmean_axis(a: ArrayLike, axis: int | None = None, keepdims: bool = False) -> np.ndarray:
    """Mean ignoring NaNs."""
    return np.nanmean(as_array(a), axis=axis, keepdims=keepdims)


# =========================
# Math and normalization
# =========================

def zscore(a: ArrayLike, axis: int | None = None, eps: float = 1e-12) -> np.ndarray:
    """(x - mean) / std along axis; eps prevents div-by-zero."""
    a = as_array(a)
    m = np.mean(a, axis=axis, keepdims=True)
    s = np.std(a, axis=axis, keepdims=True)
    return (a - m) / (s + eps)


def minmax(a: ArrayLike, axis: int | None = None, eps: float = 1e-12) -> np.ndarray:
    """Scale to [0, 1] along axis."""
    a = as_array(a)
    mn = np.min(a, axis=axis, keepdims=True)
    mx = np.max(a, axis=axis, keepdims=True)
    return (a - mn) / (mx - mn + eps)


def softmax(x: ArrayLike, axis: int = -1) -> np.ndarray:
    """Stable softmax along axis."""
    x = as_array(x)
    x_max = np.max(x, axis=axis, keepdims=True)
    e = np.exp(x - x_max)
    return e / np.sum(e, axis=axis, keepdims=True)


def logsumexp(x: ArrayLike, axis: int = -1) -> np.ndarray:
    """Stable log-sum-exp."""
    x = as_array(x)
    m = np.max(x, axis=axis, keepdims=True)
    return np.log(np.sum(np.exp(x - m), axis=axis, keepdims=False)) + np.squeeze(m, axis=axis)


# =========================
# Distances and similarity
# =========================

def cosine_similarity(A: ArrayLike, B: ArrayLike, axis: int = 1, eps: float = 1e-12) -> np.ndarray:
    """Cosine similarity between rows of A and B."""
    A = as_array(A, dtype=float)
    B = as_array(B, dtype=float)
    num = np.sum(A * B, axis=axis)
    denom = np.linalg.norm(A, axis=axis) * np.linalg.norm(B, axis=axis)
    return num / (denom + eps)


def pairwise_euclidean(X: ArrayLike, Y: ArrayLike | None = None) -> np.ndarray:
    """Pairwise Euclidean distances between rows of X and Y (or X vs X)."""
    X = as_array(X, dtype=float)
    Y = X if Y is None else as_array(Y, dtype=float)
    X2 = (X ** 2).sum(axis=1, keepdims=True)
    Y2 = (Y ** 2).sum(axis=1, keepdims=True).T
    d2 = X2 + Y2 - 2 * X @ Y.T
    np.maximum(d2, 0, out=d2)
    return np.sqrt(d2)


# =========================
# Indexing and set ops
# =========================

def argsort_topk(a: ArrayLike, k: int, axis: int = -1, largest: bool = True) -> np.ndarray:
    """Indices of top-k along axis, stable within the k-partition."""
    a = as_array(a)
    if largest:
        part_idx = np.argpartition(a, -k, axis=axis)[..., -k:]
    else:
        part_idx = np.argpartition(a, k - 1, axis=axis)[..., :k]
    part_vals = np.take_along_axis(a, part_idx, axis=axis)
    order = np.argsort(part_vals, axis=axis)
    if largest:
        order = np.flip(order, axis=axis)
    return np.take_along_axis(part_idx, order, axis=axis)


def isin_rows(A: ArrayLike, B: ArrayLike) -> np.ndarray:
    """Return boolean mask for rows of A that appear in B (exact match)."""
    A = np.ascontiguousarray(as_array(A))
    B = np.ascontiguousarray(as_array(B))
    a_view = A.view([("f", A.dtype)] * A.shape[1]).ravel()
    b_view = B.view([("f", B.dtype)] * B.shape[1]).ravel()
    return np.isin(a_view, b_view)


def unique_rows(A: ArrayLike, return_index: bool = False) -> tuple[np.ndarray, np.ndarray] | np.ndarray:
    """Unique rows, optionally returning original indices."""
    A = np.ascontiguousarray(as_array(A))
    view = A.view([("f", A.dtype)] * A.shape[1]).ravel()
    uniq, idx = np.unique(view, return_index=True)
    out = A[idx]
    return (out, idx) if return_index else out


# =========================
# Rolling and stride tricks
# =========================

def sliding_window(a: ArrayLike, window: int, step: int = 1, axis: int = 0) -> np.ndarray:
    """Sliding window view with step along axis."""
    from numpy.lib.stride_tricks import sliding_window_view

    v = sliding_window_view(as_array(a), window_shape=window, axis=axis)
    slicer = [slice(None)] * v.ndim
    slicer[axis] = slice(0, None, step)
    return v[tuple(slicer)]


# =========================
# Binning and outliers
# =========================

def bin_uniform(a: ArrayLike, bins: int = 10, labels: Sequence[str] | None = None) -> np.ndarray:
    """Uniform-width binning over [min, max]. Returns bin indices or labels."""
    a = as_array(a)
    mn, mx = np.min(a), np.max(a)
    edges = np.linspace(mn, mx, bins + 1)
    idx = np.clip(np.digitize(a, edges, right=False) - 1, 0, bins - 1)
    if labels is None:
        return idx
    return np.array([labels[i] for i in idx.ravel()]).reshape(idx.shape)


def outliers_iqr_mask(a: ArrayLike, k: float = 1.5) -> np.ndarray:
    """IQR outlier mask (outside [Q1 - k*IQR, Q3 + k*IQR])."""
    a = as_array(a, dtype=float)
    q1, q3 = np.quantile(a, [0.25, 0.75])
    iqr = q3 - q1
    lower, upper = q1 - k * iqr, q3 + k * iqr
    return (a < lower) | (a > upper)


# =========================
# Random and sampling
# =========================

def rng(seed: int = 42) -> np.random.Generator:
    return np.random.default_rng(seed)


def choice_rows(A: ArrayLike, n: int, seed: int | None = None, replace: bool = False) -> np.ndarray:
    """Sample n rows from A."""
    A = as_array(A)
    g = np.random.default_rng(seed)
    idx = g.choice(A.shape[0], size=n, replace=replace)
    return A[idx]


# =========================
# Misc utilities
# =========================

def topk(a: ArrayLike, k: int, axis: int = -1, largest: bool = True) -> tuple[np.ndarray, np.ndarray]:
    """Top-k values and indices along axis."""
    a = as_array(a)
    idx = argsort_topk(a, k=k, axis=axis, largest=largest)
    vals = np.take_along_axis(a, idx, axis=axis)
    return vals, idx


def safe_solve(A: ArrayLike, b: ArrayLike) -> np.ndarray:
    """Solve Ax=b; fallback to pseudo-inverse if singular."""
    A = as_array(A, dtype=float)
    b = as_array(b, dtype=float)
    try:
        return np.linalg.solve(A, b)
    except np.linalg.LinAlgError:
        return np.linalg.pinv(A) @ b
```

### IO and performance

- `as_array`, `bytes_mb`, `ensure_c_contiguous`.

### Array creation and shapes

- `repeat_to_shape`, `pad_to_shape`.

### Dtypes and NaNs

- `is_nan_safe`, `replace_nan`, `nanmean_axis`.

### Math and normalization

- `zscore`, `minmax`, `softmax`, `logsumexp`.

### Distances and similarity

- `cosine_similarity`, `pairwise_euclidean`.

### Indexing and set ops

- `argsort_topk`, `isin_rows`, `unique_rows`.

### Rolling and stride tricks

- `sliding_window`.

### Binning and outliers

- `bin_uniform`, `outliers_iqr_mask`.

### Random and sampling

- `rng`, `choice_rows`.

### Misc utilities

- `topk`, `safe_solve`.

---

## Common issues and fixes

### Multiple Python versions

Install into a specific interpreter:

```bash
python3 -m pip install numpy
/path/to/python -m pip install numpy
```

### Outdated pip/setuptools/wheel

```bash
python3 -m pip install --upgrade pip setuptools wheel
python3 -m pip install --upgrade numpy
```

### Apple Silicon (arm64) notes

Modern wheels exist for arm64. If you run Rosetta Python by accident, prefer the system arm64 Python and reinstall inside a fresh venv.

---

## Advanced options

### Requirements file

Create `requirements.txt`:

```text
numpy>=1.26.0
```

Install all at once:

```bash
pip install -r requirements.txt
```

### Development install from source

```bash
git clone https://github.com/numpy/numpy.git
cd numpy
pip install -e .
```

## Uninstall and cleanup

```bash
pip uninstall -y numpy
pip cache purge
```

## FAQ

- Do I need a BLAS/LAPACK library? Prebuilt wheels ship with optimized libraries. Building from source may require system BLAS/LAPACK.
- Should I use conda? If you need a curated scientific stack or specific MKL builds, conda can be simpler. For most macOS/Linux workflows, pip + venv works well.
- How do I seed RNG? Use `np.random.default_rng(seed)` and pass the generator around for reproducibility.

## Next steps

- Official docs: [numpy.org/doc](https://numpy.org/doc/)
- Work with tabular data using [pandas](./pandas.md)
- Plot arrays with [Matplotlib](./matplotlib.md) or Seaborn

This guide covers clean installation, verification, and a reusable helper pattern to speed up everyday NumPy tasks.
