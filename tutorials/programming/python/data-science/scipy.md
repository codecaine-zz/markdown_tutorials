# SciPy — Practical Tutorial (with Table of Contents)

A hands-on, copy‑pasteable guide to core SciPy subpackages for scientific computing. SciPy builds on NumPy arrays and provides algorithms for optimization, integration, interpolation, signal/image processing, statistics, sparse matrices, and more.

## Table of Contents

- [Overview](#overview)
- [Install](#install)
- [Quick Start](#quick-start)
- [Linear Algebra (scipy.linalg)](#linear-algebra-scipylinalg)
- [Optimization & Root Finding (scipy.optimize)](#optimization--root-finding-scipyoptimize)
- [Integration & ODEs (scipy.integrate)](#integration--odes-scipyintegrate)
- [Statistics (scipy.stats)](#statistics-scipystats)
- [Spatial algorithms (scipy.spatial)](#spatial-algorithms-scipyspatial)
- [Interpolation (scipy.interpolate)](#interpolation-scipyinterpolate)
- [Signal Processing (scipy.signal)](#signal-processing-scipysignal)
- [Fast Fourier Transforms (scipy.fft)](#fast-fourier-transforms-scipyfft)
- [Sparse Matrices (scipy.sparse)](#sparse-matrices-scipysparse)
- [n-D Image Processing (scipy.ndimage)](#n-d-image-processing-scipyndimage)
- [I/O utilities (scipy.io) and constants](#io-utilities-scipyio-and-constants)
- [Reusable utilities (copy-paste)](#reusable-utilities-copy-paste)
- [Performance Tips](#performance-tips)
- [Troubleshooting](#troubleshooting)
- [References](#references)

---

## Overview

SciPy extends NumPy with battle‑tested scientific routines. You’ll typically:

- Put your data in NumPy arrays (or pandas DataFrames that expose NumPy arrays).
- Call SciPy algorithms on those arrays.
- Visualize with matplotlib or seaborn.

## Install

See official guidance for platform specifics: [SciPy User Guide](https://docs.scipy.org/doc/scipy/tutorial/index.html#user-guide)

Quick install:

```bash
python -m pip install scipy numpy
```

On Apple Silicon/macOS, using a recent Python from python.org or conda/miniforge can avoid binary issues.

## Quick Start

```python
import numpy as np
from scipy import optimize, integrate

# Find root of f(x) = cos(x) - x
f = lambda x: np.cos(x) - x
root = optimize.root_scalar(f, bracket=[0, 1])
print('Root near ~0.739=', root.root)

# Numeric integration of sin from 0..pi
res, err = integrate.quad(np.sin, 0, np.pi)
print('Integral of sin 0..pi =', res, '+/-', err)
```

## Linear Algebra (scipy.linalg)

```python
import numpy as np
from scipy import linalg

A = np.array([[3., 2.], [1., 4.]])
b = np.array([7., 5.])

# Solve Ax = b
x = linalg.solve(A, b)
print('x =', x)

# Determinant, inverse, eigenvalues
print('det(A)=', linalg.det(A))
print('eig(A)=', linalg.eigvals(A))
print('inv(A)=\n', linalg.inv(A))

# Least squares (over/underdetermined)
B = np.vstack([A, [1., 1.]])
c = np.array([7., 5., 3.])
sol, *_ = linalg.lstsq(B, c)
print('least-squares sol =', sol)

# SVD
U, s, Vh = linalg.svd(A)
print('singular values =', s)
```

Notes: `numpy.linalg` provides many of these too. SciPy’s `linalg` adds more routines and BLAS/LAPACK acceleration options.

## Optimization & Root Finding (scipy.optimize)

```python
import numpy as np
from scipy import optimize

# Unconstrained minimization (BFGS)
f = lambda x: (x - 3)**2 + 10
res = optimize.minimize(lambda x: f(x[0]), x0=[0.0], method='BFGS')
print('min at ~3:', res.x, 'success:', res.success)

# Nonlinear least squares (curve fitting)
from scipy.optimize import curve_fit

def model(x, a, b):
    return a * np.exp(b * x)

xdata = np.linspace(0, 1, 20)
ydata = model(xdata, 2.0, 1.5) + 0.1 * np.random.default_rng(0).normal(size=xdata.size)

popt, pcov = curve_fit(model, xdata, ydata, p0=(1.0, 1.0))
print('fitted params:', popt)

# Root finding
g = lambda x: np.cos(x) - x
root = optimize.root_scalar(g, bracket=[0, 1], method='brentq')
print('root=', root.root)

# Bounded minimization (scalar)
res = optimize.minimize_scalar(lambda x: (x + 1)**2, bounds=(-5, 5), method='bounded')
print('bounded min near -1:', res.x)
```

## Integration & ODEs (scipy.integrate)

```python
import numpy as np
from scipy import integrate

# Single integral of sin(x) from 0..pi
val, err = integrate.quad(np.sin, 0, np.pi)
print('quad sin 0..pi=', val)

# Double integral: ∫y=0..1 ∫x=0..1 (x*y) dx dy
func = lambda y, x: x * y
val2, err2 = integrate.dblquad(func, 0, 1, lambda x: 0, lambda x: 1)
print('dblquad x*y over unit square =', val2)

# Solve ODE: y' = -2y, y(0)=1
f = lambda t, y: -2 * y
sol = integrate.solve_ivp(f, t_span=(0, 5), y0=[1.0], t_eval=np.linspace(0, 5, 50))
print('y(5)=', sol.y[0, -1])
```

## Statistics (scipy.stats)

```python
import numpy as np
from scipy import stats

rng = np.random.default_rng(42)

data = rng.normal(loc=5.0, scale=2.0, size=500)

# Summary and tests
print('mean=', data.mean(), 'std=', data.std(ddof=1))
print('Normality test (Shapiro):', stats.shapiro(data))

# One-sample t-test vs mu=5
print('ttest_1samp:', stats.ttest_1samp(data, popmean=5.0))

# Two-sample t-test
x = rng.normal(0, 1, size=200)
y = rng.normal(0.2, 1, size=220)
print('ttest_ind:', stats.ttest_ind(x, y, equal_var=False))

# Correlation
print('pearsonr:', stats.pearsonr(x, y))

# Distributions: pdf, cdf, sampling
rv = stats.norm(loc=0, scale=1)
print('cdf(1.96)=', rv.cdf(1.96))
samples = rv.rvs(size=5, random_state=rng)
print('samples=', samples)

# Parameter fitting
params = stats.lognorm.fit(np.abs(rng.normal(size=1000)))
print('fitted lognorm params:', params)
```

## Spatial algorithms (scipy.spatial)

```python
import numpy as np
from scipy.spatial import KDTree, distance_matrix, Delaunay

points = np.random.default_rng(0).random((10, 2))

# Nearest neighbors with KDTree
tree = KDTree(points)
dists, idxs = tree.query([0.5, 0.5], k=3)
print('3 nearest to center:', idxs, dists)

# Pairwise distances
print('distance matrix shape:', distance_matrix(points, points).shape)

# Triangulation (2D)
tri = Delaunay(points)
print('simplices:', tri.simplices[:5])
```

## Interpolation (scipy.interpolate)

```python
import numpy as np
from scipy import interpolate

x = np.linspace(0, 10, 11)
y = np.sin(x)

# 1D interpolation
f_lin = interpolate.interp1d(x, y, kind='linear')
print('f_lin(2.5)=', f_lin(2.5))

# Spline smoothing
spl = interpolate.UnivariateSpline(x, y, s=0.5)
print('spline(2.5)=', spl(2.5))

# Scattered data to grid (2D)
rng = np.random.default_rng(1)
xy = rng.random((100, 2))
z = np.sin(4 * xy[:, 0]) + np.cos(4 * xy[:, 1])
xi = yi = np.linspace(0, 1, 50)
XI, YI = np.meshgrid(xi, yi)
ZI = interpolate.griddata(points=xy, values=z, xi=(XI, YI), method='cubic')
print('griddata shape:', ZI.shape)
```

## Signal Processing (scipy.signal)

```python
import numpy as np
from scipy import signal

fs = 500  # Hz
T = 2.0   # seconds
t = np.linspace(0, T, int(fs*T), endpoint=False)

# Create signal: 5 Hz + 40 Hz + noise
x = np.sin(2*np.pi*5*t) + 0.5*np.sin(2*np.pi*40*t) + 0.3*np.random.default_rng(0).normal(size=t.size)

# Design a low-pass Butterworth filter at 10 Hz
b, a = signal.butter(N=4, Wn=10, btype='low', fs=fs)
y = signal.filtfilt(b, a, x)

# Find peaks
peaks, props = signal.find_peaks(y, height=0.8)
print('peaks found:', len(peaks))

# Spectrogram
f, tt, Sxx = signal.spectrogram(x, fs)
print('spectrogram shape:', Sxx.shape)
```

## Fast Fourier Transforms (scipy.fft)

```python
import numpy as np
from scipy import fft

fs = 1000
T = 1.0
N = int(fs*T)
t = np.linspace(0, T, N, endpoint=False)
x = np.sin(2*np.pi*50*t) + 0.5*np.sin(2*np.pi*120*t)

X = fft.rfft(x)
freqs = fft.rfftfreq(N, 1/fs)

# Dominant frequency
peak = freqs[np.argmax(np.abs(X))]
print('dominant freq:', peak)
```

## Sparse Matrices (scipy.sparse)

```python
import numpy as np
from scipy.sparse import csr_matrix, diags
from scipy.sparse.linalg import spsolve

# Build sparse matrix
rows = np.array([0, 0, 1, 2])
cols = np.array([0, 2, 1, 2])
vals = np.array([4., -1., 5., 2.])
A = csr_matrix((vals, (rows, cols)), shape=(3, 3))
b = np.array([3., 7., 4.])

x = spsolve(A, b)
print('sparse solve x=', x)

# Diagonal matrix and operations
D = diags([1., 2., 3.])
print('D @ x shape:', (D @ x).shape)
```

## n-D Image Processing (scipy.ndimage)

```python
import numpy as np
from scipy import ndimage as ndi

rng = np.random.default_rng(0)
image = rng.random((64, 64))

# Gaussian blur and Sobel edges
blur = ndi.gaussian_filter(image, sigma=1.5)
edges = np.hypot(ndi.sobel(blur, axis=0), ndi.sobel(blur, axis=1))
print('edges stats:', edges.min(), edges.max())

# Label connected components
binary = image > 0.8
labels, n = ndi.label(binary)
print('components:', n)

# Morphology: binary closing
closed = ndi.binary_closing(binary, structure=np.ones((3, 3)))
print('closed sum:', closed.sum())
```

## I/O utilities (scipy.io) and constants

```python
from scipy import io, constants
import numpy as np

# MATLAB .mat files
X = {'A': np.arange(6).reshape(2, 3)}
io.savemat('example.mat', X)
X2 = io.loadmat('example.mat')
print('keys in mat:', X2.keys())

# WAV audio files
from scipy.io import wavfile

sr = 16000
sig = (0.5*np.sin(2*np.pi*440*np.arange(sr)/sr)).astype('float32')
# Save float WAV (16-bit PCM requires int16)
wavfile.write('tone.wav', sr, sig)
rate, data = wavfile.read('tone.wav')
print('wav rate, shape:', rate, data.shape)

# Physical constants
print('speed of light (m/s)=', constants.c)
print('Planck constant=', constants.h)
```

## Reusable utilities (copy-paste)

Drop-in helpers with safe defaults and good performance. Each block is self-contained; copy only what you need.

### Signal processing helpers

```python
import numpy as np
from fractions import Fraction
from scipy import signal

def band_filter(x: np.ndarray, fs: float, low: float | None = None, high: float | None = None,
                order: int = 4, zero_phase: bool = True, axis: int = -1) -> np.ndarray:
    """Efficient Butterworth low/high/band-pass using SOS; pass low or high (Hz), or both for band.

    - Handles NaNs by linear interpolation along axis (keeps shape).
    - Uses filtfilt for zero-phase by default.
    """
    x = np.asarray(x)
    if low is None and high is None:
        return x
    # Interpolate NaNs for stable filtering
    def _interp_nans(arr):
        arr = np.moveaxis(arr, axis, -1)
        t = np.arange(arr.shape[-1])
        mask = ~np.isnan(arr)
        if not mask.all():
            for idx in np.ndindex(arr.shape[:-1]):
                m = mask[idx]
                if m.any():
                    arr[idx, ~m] = np.interp(t[~m], t[m], arr[idx, m])
                else:
                    arr[idx, :] = 0
        return np.moveaxis(arr, -1, axis)

    x = _interp_nans(x)

    if low is not None and high is not None:
        btype, Wn = 'bandpass', (low, high)
    elif low is not None:
        btype, Wn = 'highpass', low
    else:
        btype, Wn = 'lowpass', high

    sos = signal.butter(order, Wn=Wn, btype=btype, fs=fs, output='sos')
    return signal.sosfiltfilt(sos, x, axis=axis) if zero_phase else signal.sosfilt(sos, x, axis=axis)


def resample_to(x: np.ndarray, fs_from: float, fs_to: float, axis: int = -1,
                max_den: int = 1000, padtype: str | None = 'median') -> np.ndarray:
    """High-quality resampling via polyphase filtering. Chooses integer up/down automatically.

    Examples:
        y = resample_to(x, fs_from=48000, fs_to=16000)
    """
    ratio = Fraction(fs_to, fs_from).limit_denominator(max_den)
    up, down = ratio.numerator, ratio.denominator
    return signal.resample_poly(x, up, down, axis=axis, padtype=padtype)


def welch_psd(x: np.ndarray, fs: float, nperseg: int | None = None, noverlap: int | None = None,
              detrend: str = 'constant', average: str = 'median') -> tuple[np.ndarray, np.ndarray]:
    """Compute robust power spectral density with sensible defaults (Welch).
    Uses power-of-two segment length and median averaging to reduce outliers.
    """
    x = np.asarray(x)
    if nperseg is None:
        n = x.shape[-1]
        p2 = 1 << (int(n - 1).bit_length())  # next power of two >= n
        nperseg = int(min(8192, max(256, p2 // 8)))
    if noverlap is None:
        noverlap = nperseg // 2
    f, Pxx = signal.welch(x, fs=fs, nperseg=nperseg, noverlap=noverlap,
                          detrend=detrend, average=average, return_onesided=True)
    return f, Pxx


def single_sided_spectrum(x: np.ndarray, fs: float, window: str = 'hann', axis: int = -1) -> tuple[np.ndarray, np.ndarray]:
    """One-sided amplitude spectrum with window amplitude correction.

    Returns (freqs, amps), where amps have the same physical amplitude as the input signal.
    """
    x = np.asarray(x)
    N = x.shape[axis]
    w = signal.get_window(window, N, fftbins=True)
    # Move axis to the end, apply window, FFT, then move back
    x_moved = np.moveaxis(x, axis, -1)
    xw = x_moved * w
    X = np.fft.rfft(xw, axis=-1)
    freqs = np.fft.rfftfreq(N, d=1.0 / fs)
    # Coherent gain for amplitude correction
    cg = w.mean()
    amp = np.abs(X) / (N * cg)
    # Double non-DC, non-Nyquist bins for one-sided amplitude
    if N % 2 == 0:
        amp[..., 1:-1] *= 2
    else:
        amp[..., 1:] *= 2
    amp = np.moveaxis(amp, -1, axis)
    return freqs, amp
```

### Statistics & fitting

```python
import numpy as np
from dataclasses import dataclass
from scipy import stats, optimize

def robust_zscore(x: np.ndarray, axis: int | None = None, eps: float = 1e-12) -> np.ndarray:
    """Median/MAD-based z-scores, robust to outliers. NaNs preserved.
    z = (x - median) / (MAD * 1.4826)
    """
    med = np.nanmedian(x, axis=axis, keepdims=True)
    mad = stats.median_abs_deviation(x, axis=axis, scale='normal', nan_policy='omit', keepdims=True)
    return (x - med) / (mad + eps)


def ci_from_cov(popt: np.ndarray, pcov: np.ndarray, alpha: float = 0.05) -> tuple[np.ndarray, np.ndarray]:
    """Confidence intervals for parameters from covariance matrix (asymptotic normal)."""
    perr = np.sqrt(np.clip(np.diag(pcov), 0, np.inf))
    z = stats.norm.ppf(1 - alpha / 2)
    lo = popt - z * perr
    hi = popt + z * perr
    return lo, hi


@dataclass
class FitResult:
    popt: np.ndarray
    pcov: np.ndarray
    perr: np.ndarray
    sse: float
    aic: float
    bic: float


def stable_curve_fit(func, x, y, p0=None, bounds=(-np.inf, np.inf), sigma=None,
                     absolute_sigma: bool = False, maxfev: int = 10000) -> FitResult:
    """Curve fit wrapper with sane defaults + diagnostics (SSE, AIC, BIC).

    Example:
        def model(x, a, b): return a * np.exp(b*x)
        fr = stable_curve_fit(model, x, y, p0=(1, 0.1))
    """
    popt, pcov = optimize.curve_fit(func, x, y, p0=p0, bounds=bounds, sigma=sigma,
                                    absolute_sigma=absolute_sigma, maxfev=maxfev)
    yhat = func(x, *popt)
    resid = np.asarray(y) - np.asarray(yhat)
    sse = float(np.sum(resid**2))
    n = np.size(y)
    k = np.size(popt)
    aic = n * np.log(sse / max(n, 1)) + 2 * k
    bic = n * np.log(sse / max(n, 1)) + k * np.log(max(n, 1))
    perr = np.sqrt(np.clip(np.diag(pcov), 0, np.inf))
    return FitResult(popt=popt, pcov=pcov, perr=perr, sse=sse, aic=aic, bic=bic)
```

### Sparse linear algebra

```python
import numpy as np
from scipy.sparse import csc_matrix, issparse
from scipy.sparse.linalg import splu

class SparseSolver:
    """Factor-and-solve helper for repeated solves Ax=b with the same sparse A.

    Example:
        ss = SparseSolver(A)  # A is CSR/CSC
        x1 = ss.solve(b1)
        x2 = ss.solve(b2)
    """
    def __init__(self, A):
        if not issparse(A):
            raise TypeError('A must be a SciPy sparse matrix')
        Ac = A if A.getformat() == 'csc' else csc_matrix(A)
        self._lu = splu(Ac)

    def solve(self, b: np.ndarray) -> np.ndarray:
        return self._lu.solve(np.asarray(b))
```

### Interpolation & spatial

```python
import numpy as np
from scipy import interpolate
from scipy.spatial import KDTree

def scattered_to_grid(xy: np.ndarray, z: np.ndarray, grid_size: int = 200,
                      method: str = 'linear', fill_value: float | None = np.nan,
                      bbox: tuple[float, float, float, float] | None = None):
    """Interpolate scattered (x,y,z) onto a regular grid.

    Returns XI, YI, ZI. If method='linear'/'cubic' leaves NaNs, they're filled with 'nearest'.
    """
    xy = np.asarray(xy)
    if bbox is None:
        xmin, ymin = xy.min(axis=0)
        xmax, ymax = xy.max(axis=0)
    else:
        xmin, ymin, xmax, ymax = bbox
    xi = np.linspace(xmin, xmax, grid_size)
    yi = np.linspace(ymin, ymax, grid_size)
    XI, YI = np.meshgrid(xi, yi)
    ZI = interpolate.griddata(points=xy, values=z, xi=(XI, YI), method=method, fill_value=fill_value)
    if np.isnan(ZI).any():
        ZN = interpolate.griddata(points=xy, values=z, xi=(XI, YI), method='nearest')
        ZI = np.where(np.isnan(ZI), ZN, ZI)
    return XI, YI, ZI


class KNN:
    """KDTree wrapper for fast repeated k-NN queries."""
    def __init__(self, points: np.ndarray, leafsize: int = 40):
        self.tree = KDTree(np.asarray(points), leafsize=leafsize)

    def query(self, q: np.ndarray, k: int = 1):
        return self.tree.query(q, k=k)
```

### ND image utilities

```python
import numpy as np
from scipy import ndimage as ndi

def gaussian_sobel_edges(image: np.ndarray, sigma: float = 1.0) -> np.ndarray:
    """Gaussian blur then Sobel magnitude."""
    blur = ndi.gaussian_filter(image, sigma=sigma)
    gx = ndi.sobel(blur, axis=0)
    gy = ndi.sobel(blur, axis=1)
    return np.hypot(gx, gy)
```

### Optimization helpers

```python
import numpy as np
from scipy.optimize import linear_sum_assignment

def linear_assignment(cost: np.ndarray) -> tuple[np.ndarray, np.ndarray, float]:
    """Hungarian algorithm convenience wrapper.
    Returns (row_idx, col_idx, total_cost).
    """
    r, c = linear_sum_assignment(cost)
    return r, c, float(cost[r, c].sum())
```

### Integration utilities

```python
import numpy as np
from scipy.integrate import cumulative_trapezoid

def cumulative_integral(y: np.ndarray, x: np.ndarray | None = None, axis: int = -1) -> np.ndarray:
    """Cumulative integral with initial 0 using trapezoidal rule."""
    if x is None:
        x = np.arange(y.shape[axis])
    return cumulative_trapezoid(y, x, axis=axis, initial=0.0)
```

## Performance Tips

- Prefer vectorized NumPy operations; avoid Python loops in inner computations.
- Use appropriate dtypes (float32 vs float64) to balance precision and speed/memory.
- For large linear algebra, ensure a fast BLAS/LAPACK (conda often ships MKL or OpenBLAS).
- For repeated transforms/filters, precompute parameters (e.g., filter coefficients) and reuse.
- Consider `numba` or `numexpr` only when algorithmic vectorization isn’t feasible.

## Troubleshooting

- Import errors on macOS/Apple Silicon: install with a recent Python and SciPy wheel or use conda/miniforge.
- Slow operations: verify you’re using array operations, not Python loops; check BLAS backend.
- Solver failures or non‑convergence: scale your data, provide better initial guesses/bounds, or try a different algorithm.

## References

- User Guide: [https://docs.scipy.org/doc/scipy/tutorial/index.html#user-guide](https://docs.scipy.org/doc/scipy/tutorial/index.html#user-guide)
- API Reference: [https://docs.scipy.org/doc/scipy/reference/](https://docs.scipy.org/doc/scipy/reference/)
- SciPy Getting Started: [https://scipy.org/](https://scipy.org/)

---

Copy any section into a script or notebook and run. Mix with pandas/matplotlib for full workflows.
