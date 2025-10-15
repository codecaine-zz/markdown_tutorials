# Pandas: Installation and Quickstart (pip)

## Table of Contents

1. [What is pandas?](#what-is-pandas)
2. [Prerequisites](#prerequisites)
3. [Install with pip](#install-with-pip)
   - [Basic install](#basic-install)
   - [Specific version](#specific-version)
   - [Upgrade existing](#upgrade-existing)
   - [User install (no sudo)](#user-install-no-sudo)
4. [Virtual environments (recommended)](#virtual-environments-recommended)
5. [Optional extras and IO support](#optional-extras-and-io-support)
6. [Verify installation](#verify-installation)
7. [Quickstart: reusable helper functions](#quickstart-reusable-helper-functions)
   - [Helper module](#helper-module)
   - [Using the helpers](#using-the-helpers)
8. [Optimized, reusable utility functions (copy-paste cookbook)](#optimized-reusable-utility-functions-copy-paste-cookbook)
   - [IO and performance](#io-and-performance)
   - [Data cleaning and types](#data-cleaning-and-types)
   - [Joins and set operations](#joins-and-set-operations)
   - [Aggregation, ranking, and reshape](#aggregation-ranking-and-reshape)
   - [Datetime and time series](#datetime-and-time-series)
   - [Outliers and binning](#outliers-and-binning)
   - [Misc utilities](#misc-utilities)
9. [Common issues and fixes](#common-issues-and-fixes)
10. [Advanced options](#advanced-options)
11. [Uninstall and cleanup](#uninstall-and-cleanup)
12. [FAQ](#faq)
13. [Next steps](#next-steps)

---

## What is pandas?

Pandas is a powerful Python library for data manipulation and analysis. It provides DataFrame and Series data structures for working with tabular and labeled data efficiently.

## Prerequisites

Make sure you have:

- Python 3.8+ (3.10–3.12 recommended)
- pip (Python package manager)

Tip (macOS): prefer python3 explicitly to avoid version confusion.

## Install with pip

### Basic install

```bash
python3 -m pip install pandas
```

### Specific version

```bash
python3 -m pip install "pandas==2.2.2"
```

### Upgrade existing

```bash
python3 -m pip install --upgrade pandas
```

### User install (no sudo)

```bash
python3 -m pip install --user pandas
```

## Virtual environments (recommended)

Keep project dependencies isolated with venv:

```bash
# create
python3 -m venv .venv

# activate (zsh/bash on macOS/Linux)
source .venv/bin/activate

# install inside the venv
pip install pandas
```

Deactivate later with `deactivate`.

## Optional extras and IO support

Install extras for common file formats and features:

```bash
# Excel read/write
pip install "pandas[excel]"  # pulls engines like openpyxl/xlsxwriter where available

# HTML table parsing
pip install "pandas[html]"

# All optional dependencies
pip install "pandas[all]"

# Popular DS stack
pip install pandas numpy matplotlib seaborn jupyter
```

## Verify installation

### In Python

```python
import pandas as pd
print(pd.__version__)
```

### Package metadata

```bash
pip show pandas
```

---

## Quickstart: reusable helper functions

The examples below wrap common tasks into small, reusable functions you can copy into a `pandas_helpers.py` module and import anywhere.

### Helper module

```python
# pandas_helpers.py
from __future__ import annotations

from typing import Any, Dict, Iterable, Mapping, Optional
import pandas as pd


def show_versions() -> None:
   """Print pandas and key dependency versions."""
   import importlib

   libs = ["pandas", "numpy", "pyarrow", "openpyxl", "xlsxwriter"]
   for lib in libs:
      try:
         mod = importlib.import_module(lib)
         ver = getattr(mod, "__version__", "unknown")
         print(f"{lib}: {ver}")
      except Exception:
         print(f"{lib}: not installed")


def make_demo_df(rows: int = 5, seed: int = 42) -> pd.DataFrame:
   """Create a small demo DataFrame with numeric and categorical columns."""
   import numpy as np

   rng = np.random.default_rng(seed)
   return pd.DataFrame(
      {
         "product": rng.choice(["A", "B", "C"], size=rows),
         "region": rng.choice(["North", "South", "East", "West"], size=rows),
         "units": rng.integers(1, 100, size=rows),
         "price": rng.normal(25.0, 5.0, size=rows).round(2),
         "date": pd.date_range("2024-01-01", periods=rows, freq="D"),
      }
   )


def summarize_numeric(df: pd.DataFrame) -> pd.DataFrame:
   """Return numerical summary (count, mean, std, min, quartiles, max)."""
   return df.select_dtypes("number").describe().T


def group_and_aggregate(
   df: pd.DataFrame,
   by: Iterable[str],
   agg: Mapping[str, Any],
) -> pd.DataFrame:
   """Group by columns and aggregate with a mapping, returning a flat index."""
   return df.groupby(list(by), as_index=False).agg(agg)


def filter_query(df: pd.DataFrame, query: str, **locals_: Any) -> pd.DataFrame:
   """Filter with pandas.query; pass locals to reference variables in the expression."""
   return df.query(query, local_dict=locals_)


def load_csv(path: str, **kwargs: Any) -> pd.DataFrame:
   """Load a CSV with sensible defaults you can override via kwargs."""
   return pd.read_csv(path, **kwargs)


def save_to_excel(df: pd.DataFrame, path: str, sheet_name: str = "Sheet1") -> None:
   """Save a DataFrame to Excel using an available engine (openpyxl/xlsxwriter)."""
   df.to_excel(path, index=False, sheet_name=sheet_name)


def to_monthly_sales(df: pd.DataFrame) -> pd.DataFrame:
   """Example time series resample: monthly sum of revenue (= units * price)."""
   tmp = df.copy()
   tmp["revenue"] = tmp["units"] * tmp["price"]
   return (
      tmp.set_index("date").resample("MS")["revenue"].sum().rename("monthly_revenue").to_frame()
   )


def top_n(df: pd.DataFrame, by: str, n: int = 5, ascending: bool = False) -> pd.DataFrame:
   """Return the top-N rows by a column."""
   return df.sort_values(by=by, ascending=ascending).head(n)
```

### Using the helpers

```python
# quickstart_demo.py
import pandas as pd
from pandas_helpers import (
   show_versions,
   make_demo_df,
   summarize_numeric,
   group_and_aggregate,
   filter_query,
   save_to_excel,
   to_monthly_sales,
   top_n,
)


def run_demo() -> None:
   show_versions()

   df = make_demo_df(rows=10)
   print("\nDemo DataFrame:\n", df.head(), sep="")

   print("\nNumeric summary:\n", summarize_numeric(df), sep="")

   grouped = group_and_aggregate(df, by=["product", "region"], agg={"units": "sum", "price": "mean"})
   print("\nGrouped summary:\n", grouped, sep="")

   filtered = filter_query(df, "units >= @min_units and price < @max_price", min_units=50, max_price=30)
   print("\nFiltered rows (units>=50 & price<30):\n", filtered, sep="")

   monthly = to_monthly_sales(df)
   print("\nMonthly revenue (resampled):\n", monthly, sep="")

   print("\nTop 3 by units:\n", top_n(df, by="units", n=3), sep="")

   # Save example output (requires openpyxl or xlsxwriter if writing .xlsx)
   save_to_excel(grouped, "grouped_summary.xlsx", sheet_name="summary")


if __name__ == "__main__":
   run_demo()
```

---

## Optimized, reusable utility functions (copy-paste cookbook)

Drop these into a `pandas_utils.py` module and import as needed. They favor vectorized operations and safe defaults.

```python
# pandas_utils.py
from __future__ import annotations

from typing import Any, Iterable, Mapping, Sequence
import re
import warnings

import numpy as np
import pandas as pd


# =========================
# IO and performance
# =========================

def read_csv_fast(path: str, use_pyarrow: bool | None = None, **kwargs: Any) -> pd.DataFrame:
   """Read CSV fast by preferring the pyarrow engine when available.

   - If use_pyarrow=True, will try engine="pyarrow" and fall back to C engine.
   - If use_pyarrow=None (default), auto-detect pyarrow availability.
   - You can pass typical read_csv kwargs (dtype, parse_dates, etc.).
   """
   engine = None
   if use_pyarrow is True:
      engine = "pyarrow"
   elif use_pyarrow is None:
      try:
         import pyarrow  # noqa: F401
         engine = "pyarrow"
      except Exception:
         engine = None

   if engine:
      try:
         return pd.read_csv(path, engine=engine, **kwargs)
      except Exception:
         warnings.warn("pyarrow engine failed; falling back to default C engine.")

   return pd.read_csv(path, **kwargs)


def to_parquet_auto(df: pd.DataFrame, path: str, **kwargs: Any) -> None:
   """Write parquet using the best available engine (pyarrow > fastparquet)."""
   engine = None
   try:
      import pyarrow  # noqa: F401
      engine = "pyarrow"
   except Exception:
      try:
         import fastparquet  # noqa: F401
         engine = "fastparquet"
      except Exception:
         raise RuntimeError("No parquet engine found. Install pyarrow or fastparquet.") from None
   df.to_parquet(path, engine=engine, index=False, **kwargs)


def memory_usage_mb(df: pd.DataFrame) -> float:
   """Approximate memory usage (MB) including object columns."""
   return float(df.memory_usage(deep=True).sum()) / (1024 ** 2)


def memory_reduce_df(
   df: pd.DataFrame,
   *,
   to_category_threshold: float = 0.5,
   convert_dates: bool = False,
   exclude: Sequence[str] | None = None,
) -> pd.DataFrame:
   """Downcast numeric dtypes and optionally convert low-cardinality objects to category.

   - to_category_threshold: ratio unique/len <= threshold -> convert object->category
   - convert_dates: attempt to parse object columns as datetime when >90% parseable
   - exclude: column names to skip
   """
   exclude = set(exclude or [])
   out = df.copy()

   # Downcast numbers
   for col, s in out.select_dtypes(include=["integer", "floating"]).items():
      if col in exclude:
         continue
      if pd.api.types.is_integer_dtype(s):
         out[col] = pd.to_numeric(s, downcast="integer")
      elif pd.api.types.is_float_dtype(s):
         out[col] = pd.to_numeric(s, downcast="float")

   # Objects -> category or datetime
   obj_cols = [c for c in out.select_dtypes(include=["object", "string"]).columns if c not in exclude]
   n = len(out)
   for col in obj_cols:
      s = out[col]
      # Try datetime conversion first if requested
      if convert_dates:
         parsed = pd.to_datetime(s, errors="coerce", infer_datetime_format=True)
         good = parsed.notna().sum()
         if n and good / n >= 0.9:
            out[col] = parsed
            continue
      # Category conversion based on cardinality
      uniq = s.dropna().nunique()
      if n and (uniq / n) <= to_category_threshold:
         out[col] = s.astype("category")

   return out


# =========================
# Data cleaning and types
# =========================

def snake_case_columns(df: pd.DataFrame) -> pd.DataFrame:
   """Return a copy with columns converted to snake_case and de-duplicated."""
   def _snake(s: str) -> str:
      s = s.strip()
      s = re.sub(r"[\s\-]+", "_", s)
      s = re.sub(r"[^0-9a-zA-Z_]+", "", s)
      s = re.sub(r"([a-z0-9])([A-Z])", r"\1_\2", s)
      s = s.lower()
      s = re.sub(r"_+", "_", s).strip("_")
      return s or "col"

   cols = [_snake(str(c)) for c in df.columns]
   # de-dup
   seen: dict[str, int] = {}
   new_cols: list[str] = []
   for c in cols:
      if c not in seen:
         seen[c] = 0
         new_cols.append(c)
      else:
         seen[c] += 1
         new_cols.append(f"{c}_{seen[c]}")
   out = df.copy()
   out.columns = new_cols
   return out


def normalize_str_columns(df: pd.DataFrame, columns: Sequence[str] | None = None) -> pd.DataFrame:
   """Strip, lower, collapse spaces; safe for NA. Useful before joins/grouping."""
   out = df.copy()
   cols = columns or list(out.select_dtypes(include=["object", "string"]).columns)
   for c in cols:
      s = out[c].astype("string")
      # Normalize unicode to NFKD where available
      if hasattr(s.str, "normalize"):
         s = s.str.normalize("NFKD", errors="ignore")  # type: ignore[attr-defined]
      s = s.str.encode("ascii", "ignore").str.decode("utf-8", errors="ignore")
      s = s.str.strip().str.lower().str.replace(r"\s+", " ", regex=True)
      out[c] = s
   return out


def safe_to_datetime(s: pd.Series, errors: str = "coerce") -> pd.Series:
   """Robust datetime parsing with common cleanups."""
   if s.dtype == "O" or pd.api.types.is_string_dtype(s):
      s2 = s.astype("string").str.strip()
   else:
      s2 = s
   return pd.to_datetime(s2, errors=errors, infer_datetime_format=True, utc=False)


def as_categorical_by_cardinality(
   df: pd.DataFrame, *, threshold: float = 0.2, exclude: Sequence[str] | None = None
) -> pd.DataFrame:
   """Convert object/string columns to category when unique ratio <= threshold."""
   out = df.copy()
   exclude = set(exclude or [])
   n = len(out)
   for col in out.select_dtypes(include=["object", "string"]).columns:
      if col in exclude:
         continue
      uniq = out[col].dropna().nunique()
      if n and (uniq / n) <= threshold:
         out[col] = out[col].astype("category")
   return out


def coalesce_cols(df: pd.DataFrame, cols: Sequence[str], new_col: str) -> pd.DataFrame:
   """Create new_col as first non-null among cols (vectorized)."""
   out = df.copy()
   out[new_col] = None
   for c in cols:
      out[new_col] = out[new_col].fillna(out[c])
   return out


def fillna_smart(df: pd.DataFrame) -> pd.DataFrame:
   """Fill NaNs using median for numeric, mode for categorical/object, and empty string for strings."""
   out = df.copy()
   for col in out.columns:
      s = out[col]
      if pd.api.types.is_numeric_dtype(s):
         out[col] = s.fillna(s.median())
      elif pd.api.types.is_categorical_dtype(s):
         if len(s.cat.categories) > 0:
            out[col] = s.fillna(s.mode(dropna=True).iloc[0])
         else:
            out[col] = s
      elif pd.api.types.is_string_dtype(s) or s.dtype == "O":
         mode = s.mode(dropna=True)
         out[col] = s.fillna(mode.iloc[0] if not mode.empty else "")
      else:
         out[col] = s.fillna(method="ffill").fillna(method="bfill")
   return out


def ensure_unique_index(df: pd.DataFrame) -> pd.DataFrame:
   """If index is not unique, reset it; else return original copy."""
   return df.reset_index(drop=True) if not df.index.is_unique else df.copy()


def value_counts_topn(
   s: pd.Series, n: int = 10, dropna: bool = True, other_label: str = "__other__"
) -> pd.DataFrame:
   """Top-N value counts with percent and optional 'other' bucket."""
   vc = s.value_counts(dropna=dropna)
   total = vc.sum()
   top = vc.head(n)
   other = total - top.sum()
   out = top.rename("count").to_frame()
   out["pct"] = (out["count"] / total).round(4)
   if other > 0:
      out.loc[other_label] = {"count": int(other), "pct": round(other / total, 4)}
   return out


# =========================
# Joins and set operations
# =========================

def safe_merge(
   left: pd.DataFrame,
   right: pd.DataFrame,
   *,
   on: Sequence[str] | None = None,
   left_on: Sequence[str] | None = None,
   right_on: Sequence[str] | None = None,
   how: str = "left",
   validate: str | None = None,
   indicator: bool = False,
   suffixes: tuple[str, str] = ("_x", "_y"),
) -> pd.DataFrame:
   """A thin wrapper around merge with shape sanity-checks.

   - validate: e.g., "one_to_one", "one_to_many", "many_to_one"
   - Raises if keys are all-null.
   """
   keys = list(on or []) + list(left_on or [])
   if keys:
      for k in keys:
         if left[k].isna().all():
            raise ValueError(f"Key column '{k}' in left is all-NA")
   return left.merge(
      right,
      on=on,
      left_on=left_on,
      right_on=right_on,
      how=how,
      validate=validate,
      indicator=indicator,
      suffixes=suffixes,
   )


def left_semi_join(left: pd.DataFrame, right: pd.DataFrame, on: Sequence[str]) -> pd.DataFrame:
   """Rows from left with matching keys in right (like SQL semi-join)."""
   merged = left.merge(right[on].drop_duplicates(), on=list(on), how="inner")
   return merged


def left_anti_join(left: pd.DataFrame, right: pd.DataFrame, on: Sequence[str]) -> pd.DataFrame:
   """Rows from left with no matching keys in right (anti-join)."""
   merged = left.merge(right[on].drop_duplicates(), on=list(on), how="left", indicator=True)
   return merged.loc[merged["_merge"] == "left_only", left.columns]


def cross_join(left: pd.DataFrame, right: pd.DataFrame) -> pd.DataFrame:
   """Cross join using a constant key (avoid if frames are large)."""
   l = left.assign(_cj=1)
   r = right.assign(_cj=1)
   out = l.merge(r, on="_cj").drop(columns="_cj")
   return out


def dedupe_keep_latest(df: pd.DataFrame, subset: Sequence[str], by: str) -> pd.DataFrame:
   """Drop duplicates by subset, keeping the row with the max 'by' value (e.g., timestamp)."""
   return df.sort_values(by=by).drop_duplicates(subset=subset, keep="last")


# =========================
# Aggregation, ranking, and reshape
# =========================

def group_top_n(df: pd.DataFrame, by: Sequence[str], sort_col: str, n: int = 3, ascending: bool = False) -> pd.DataFrame:
   """Top-N rows per group based on sort_col."""
   return (
      df.sort_values(sort_col, ascending=ascending)
      .groupby(list(by), group_keys=False)
      .head(n)
   )


def row_number(df: pd.DataFrame, by: Sequence[str] | None = None, order_by: str | None = None, ascending: bool = True) -> pd.Series:
   """1-based row number (optionally per group ordered by a column)."""
   if by is None or order_by is None:
      return pd.Series(np.arange(1, len(df) + 1), index=df.index, name="row_number")
   return (
      df.sort_values(order_by, ascending=ascending)
      .groupby(list(by))
      .cumcount()
      .add(1)
      .rename("row_number")
   )


def dense_rank(df: pd.DataFrame, by: Sequence[str], order_by: str, ascending: bool = True) -> pd.Series:
   """Dense rank per group like SQL DENSE_RANK()."""
   ranks = (
      df.groupby(list(by))[order_by]
      .rank(method="dense", ascending=ascending)
      .astype(int)
      .rename("dense_rank")
   )
   return ranks


def pivot_sum(df: pd.DataFrame, index: Sequence[str], columns: Sequence[str] | str, values: str) -> pd.DataFrame:
   """Pivot table summing values with zeros for missing combinations."""
   return pd.pivot_table(df, index=list(index), columns=columns, values=values, aggfunc="sum", fill_value=0)


def pivot_count(df: pd.DataFrame, index: Sequence[str], columns: Sequence[str] | str, values: str | None = None) -> pd.DataFrame:
   """Pivot table counting rows (or non-null 'values' if provided)."""
   agg = (lambda x: x.notna().sum()) if values is not None else "size"
   return pd.pivot_table(df, index=list(index), columns=columns, values=values, aggfunc=agg, fill_value=0)


def explode_str_column(df: pd.DataFrame, col: str, sep: str = ",", trim: bool = True) -> pd.DataFrame:
   """Split strings on sep and explode to rows; handles NaNs.

   Example: "a,b , c" -> ["a", "b", "c"] when trim=True.
   """
   s = df[col].fillna("").astype(str)
   parts = s.str.split(sep)
   if trim:
      parts = parts.apply(lambda xs: [x.strip() for x in xs if x.strip() != ""])  # vectorized-enough
   out = df.copy()
   out[col] = parts
   return out.explode(col, ignore_index=True)


# =========================
# Datetime and time series
# =========================

def to_month_start(s: pd.Series) -> pd.Series:
   """Normalize datetimes to first day of month (preserves tz-naive/aware)."""
   dt = pd.to_datetime(s, errors="coerce")
   return (dt.dt.to_period("M").dt.to_timestamp("MS")).astype(dt.dtype)


def resample_sum_fill(
   df: pd.DataFrame,
   date_col: str,
   freq: str = "D",
   value_cols: Sequence[str] | None = None,
   fill_value: float = 0.0,
) -> pd.DataFrame:
   """Resample by date_col with sum and fill missing periods with fill_value."""
   tmp = df.copy()
   tmp[date_col] = pd.to_datetime(tmp[date_col], errors="coerce")
   idx = pd.date_range(tmp[date_col].min(), tmp[date_col].max(), freq=freq)
   value_cols = list(value_cols or [c for c in tmp.columns if pd.api.types.is_numeric_dtype(tmp[c])])
   out = (
      tmp.set_index(date_col)[value_cols]
      .resample(freq)
      .sum(min_count=1)
      .reindex(idx)
      .fillna(fill_value)
      .rename_axis(date_col)
      .reset_index()
   )
   return out


def to_utc(s: pd.Series, tz: str | None = None) -> pd.Series:
   """Localize to tz (if naive) then convert to UTC."""
   dt = pd.to_datetime(s, errors="coerce")
   if dt.dt.tz is None:
      dt = dt.dt.tz_localize(tz or "UTC", nonexistent="shift_forward", ambiguous="NaT")
   return dt.dt.tz_convert("UTC")


def from_utc(s: pd.Series, tz: str) -> pd.Series:
   """Convert UTC datetimes to a target timezone."""
   dt = pd.to_datetime(s, errors="coerce", utc=True)
   return dt.dt.tz_convert(tz)


# =========================
# Outliers and binning
# =========================

def outliers_iqr_mask(s: pd.Series, k: float = 1.5) -> pd.Series:
   """Boolean mask for IQR-based outliers (outside [Q1 - k*IQR, Q3 + k*IQR])."""
   q1 = s.quantile(0.25)
   q3 = s.quantile(0.75)
   iqr = q3 - q1
   lower = q1 - k * iqr
   upper = q3 + k * iqr
   return (s < lower) | (s > upper)


def clip_outliers_iqr(s: pd.Series, k: float = 1.5) -> pd.Series:
   """Clip values outside IQR bounds back to the nearest bound."""
   q1 = s.quantile(0.25)
   q3 = s.quantile(0.75)
   iqr = q3 - q1
   lower = q1 - k * iqr
   upper = q3 + k * iqr
   return s.clip(lower, upper)


def bin_numeric(
   s: pd.Series,
   *,
   strategy: str = "quantile",  # or "uniform"
   bins: int = 10,
   labels: Sequence[str] | None = None,
   include_lowest: bool = True,
) -> pd.Series:
   """Bin numeric series by quantiles or uniform width."""
   if strategy == "quantile":
      q = np.linspace(0, 1, bins + 1)
      edges = s.quantile(q).values
      edges[0] = -np.inf
      edges[-1] = np.inf
      return pd.cut(s, bins=edges, labels=labels, include_lowest=include_lowest, duplicates="drop")
   elif strategy == "uniform":
      return pd.cut(s, bins=bins, labels=labels, include_lowest=include_lowest)
   else:
      raise ValueError("strategy must be 'quantile' or 'uniform'")


# =========================
# Misc utilities
# =========================

def df_diff_rows(
   old: pd.DataFrame,
   new: pd.DataFrame,
   key: Sequence[str],
   *,
   compare_cols: Sequence[str] | None = None,
) -> dict[str, pd.DataFrame]:
   """Row-level diff by keys.

   Returns dict with:
     - 'added': rows present in new, not in old
     - 'removed': rows present in old, not in new
     - 'changed': rows where compare_cols differ (defaults to non-key cols)
   """
   k = list(key)
   left_only = left_anti_join(old, new, on=k)
   right_only = left_anti_join(new, old, on=k)

   # changed
   merged = old.merge(new, on=k, how="inner", suffixes=("_old", "_new"))
   if compare_cols is None:
      compare_cols = [c for c in old.columns if c not in k]
   diffs = []
   for c in compare_cols:
      diffs.append(merged[f"{c}_old"].ne(merged[f"{c}_new"]))
   changed_mask = np.logical_or.reduce(diffs) if diffs else np.array([], dtype=bool)
   changed = merged.loc[changed_mask, :]
   return {"added": right_only, "removed": left_only, "changed": changed}
```

### IO and performance

- Prefer `read_csv_fast` and `to_parquet_auto` for speed and sane fallbacks.
- Use `memory_reduce_df` and `as_categorical_by_cardinality` to shrink memory.

### Data cleaning and types

- `snake_case_columns`, `normalize_str_columns`, `safe_to_datetime`, `fillna_smart`, `coalesce_cols`.

### Joins and set operations

- `safe_merge`, `left_semi_join`, `left_anti_join`, `cross_join`, `dedupe_keep_latest`.

### Aggregation, ranking, and reshape

- `group_top_n`, `row_number`, `dense_rank`, `pivot_sum`, `pivot_count`, `explode_str_column`.

### Datetime and time series

- `to_month_start`, `resample_sum_fill`, `to_utc`, `from_utc`.

### Outliers and binning

- `outliers_iqr_mask`, `clip_outliers_iqr`, `bin_numeric`.

### Misc utilities

- `memory_usage_mb`, `value_counts_topn`, `df_diff_rows`, `ensure_unique_index`.

---

## Common issues and fixes

### Permission denied

```text
PermissionError: [Errno 13] Permission denied
```

Fix:

```bash
python3 -m pip install --user pandas
# or use a virtual environment (recommended)
```

### Multiple Python versions

Install into a specific interpreter:

```bash
python3 -m pip install pandas
/path/to/python -m pip install pandas
```

### Outdated pip/setuptools/wheel

```bash
python3 -m pip install --upgrade pip setuptools wheel
python3 -m pip install --upgrade pandas
```

### Apple Silicon (arm64) notes

Modern wheels exist for arm64. If you run Rosetta Python by accident, prefer the system arm64 Python and reinstall inside a fresh venv.

---

## Advanced options

### Requirements file

Create `requirements.txt`:

```text
pandas>=2.2.0
numpy>=1.26.0
matplotlib>=3.8.0
```

Install all at once:

```bash
pip install -r requirements.txt
```

### Development install from source

```bash
git clone https://github.com/pandas-dev/pandas.git
cd pandas
pip install -e .
```

## Uninstall and cleanup

```bash
pip uninstall -y pandas
pip cache purge
```

## FAQ

- Should I use conda instead of pip? If you need curated scientific stacks or compiled libs on Windows, conda can be simpler. For most macOS/Linux workflows, pip + venv works well.
- Do I need NumPy? Yes, pandas depends on NumPy. pip handles this automatically.
- How do I write Excel files? Install an engine via extras (e.g., `pip install pandas[excel]`) or explicitly `pip install openpyxl xlsxwriter`.

## Next steps

- Official docs: [pandas.pydata.org/docs](https://pandas.pydata.org/docs/)
- Learn array ops with [NumPy](./numpy.md)
- Plot with [Matplotlib](./matplotlib.md) or Seaborn

This guide covered clean installation, verification, and a reusable helper pattern to speed up everyday pandas tasks.
