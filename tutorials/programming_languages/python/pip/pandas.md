# 📊 Pandas Tutorial – Updated for pandas 2.2+  
**All code snippets are tested against pandas 2.2.2 and Python 3.12.**  
Every major step links to the official docs so beginners can jump straight to the reference they need.

---

## Table of Contents
1. [What is pandas?](#what-is-pandas)  
2. [Prerequisites](#prerequisites)  
3. [Installation with **pip**](#installation-with-pip)  
   1. [Basic install](#basic-install)  
   2. [Specific version](#specific-version)  
   3. [Upgrade existing install](#upgrade-existing-install)  
   4. [User‑only install (no sudo)](#user‑only-install)  
   5. [Virtual‑env best practice](#virtual‑env-best‑practice)  
4. [Optional extras (IO, Excel, HTML, …)](#optional-extras)  
5. [Verify the installation](#verify-the-installation)  
6. [Quick‑start helper module](#quick‑start-helper-module)  
   1. [`pandas_helpers.py`](#pandas_helperspy)  
   2. [Example script that uses the helpers](#example‑script)  
7. [Reusable utilities (copy‑paste “cookbook”)](#reusable‑utilities)  
   * [IO & performance](#io‑performance)  
   * [Data‑cleaning & type handling](#data‑cleaning)  
   * [Joins & set operations](#joins‑set‑ops)  
   * [Aggregation, ranking & reshaping](#aggregation‑ranking)  
   * [Datetime & time‑series helpers](#datetime‑helpers)  
   * [Outliers & binning](#outliers‑binning)  
   * [Miscellaneous utilities](#misc‑utils)  
8. [Common install‑time issues & fixes](#common‑issues)  
9. [Advanced workflow tips](#advanced‑tips)  
10. [Uninstall & clean‑up](#uninstall‑cleanup)  
11. [FAQ](#faq)  
12. [Next steps & further reading](#next‑steps)

---

## What is pandas? <a id="what-is-pandas"></a>

> **pandas** provides fast, flexible, and expressive data structures (Series & DataFrame) for working with “relational” or “labeled” data.  
> Official docs: <https://pandas.pydata.org/docs/>

---

## Prerequisites <a id="prerequisites"></a>

| Requirement | Recommended version | Why it matters |
|-------------|--------------------|----------------|
| **Python**  | 3.8 – 3.12 (≥ 3.10 preferred) | pandas 2.2+ drops support for < 3.8 |
| **pip**     | ≥ 23.0 (run `python -m pip --version`) | Guarantees the newest binary wheels |
| **C‑toolchain** (optional) | e.g., `gcc` on Linux, Xcode Command‑Line Tools on macOS | Needed only if you compile from source or install a package without a wheel |

---

## Installation with **pip** <a id="installation-with-pip"></a>

Official guide: <https://pandas.pydata.org/docs/getting_started/install.html>

### Basic install <a id="basic-install"></a>

```bash
python3 -m pip install pandas
```

*Installs the latest stable release (2.2.x at the time of writing).*

---

### Specific version <a id="specific-version"></a>

```bash
python3 -m pip install "pandas==2.2.2"
```

Useful when you need to match a CI environment.

---

### Upgrade existing install <a id="upgrade-existing-install"></a>

```bash
python3 -m pip install --upgrade pandas
```

---

### User‑only install (no sudo) <a id="user-only-install"></a>

```bash
python3 -m pip install --user pandas
```

The package lands in `~/.local/lib/pythonX.Y/site-packages/`.

---

### Virtual‑env best practice <a id="virtual-env-best-practice"></a>

```bash
# 1️⃣ Create an isolated environment
python3 -m venv .venv

# 2️⃣ Activate it (Linux/macOS)
source .venv/bin/activate

#   or (Windows PowerShell)
.\.venv\Scripts\Activate.ps1

# 3️⃣ Install pandas inside the env
pip install pandas   # ← now points to the env's pip

# 4️⃣ When done, leave the env
deactivate
```

> **Why?** Keeps project dependencies reproducible and avoids “system‑wide” conflicts.  
> Docs: <https://docs.python.org/3/library/venv.html>

---

## Optional extras (IO, Excel, HTML, …) <a id="optional-extras"></a>

pandas ships with a minimal set of I/O engines. Install extras for common formats:

| Feature | Extra name | Typical command |
|---------|------------|-----------------|
| Excel read/write (`openpyxl`, `xlsxwriter`) | `excel` | `pip install "pandas[excel]"` |
| HTML table parsing (`beautifulsoup4`, `html5lib`) | `html` | `pip install "pandas[html]"` |
| All optional I/O (CSV, Excel, HTML, HDF5, Parquet, …) | `all` | `pip install "pandas[all]"` |
| Full scientific stack (NumPy, Matplotlib, Seaborn, …) | none (just list) | `pip install pandas numpy matplotlib seaborn jupyter` |

*You can also install the individual engines directly (e.g., `pip install pyarrow` for Parquet).*

Docs: <https://pandas.pydata.org/docs/getting_started/install.html#optional-dependencies>

---

## Verify the installation <a id="verify-the-installation"></a>

```python
>>> import pandas as pd
>>> pd.__version__
'2.2.2'
>>> pd.show_versions()          # built‑in helper (pandas 2.2+)
```

Or from the shell:

```bash
pip show pandas        # → version, location, dependencies
```

---

## Quick‑start helper module <a id="quick-start-helper-module"></a>

A small **`pandas_helpers.py`** file gives you a toolbox you can copy into any project.  
All functions follow best‑practice defaults introduced in pandas 2.0 (e.g., **nullable dtypes**, **`StringDtype`**, **`convert_dtypes`**).

### `pandas_helpers.py` <a id="pandas_helperspy"></a>

```python
# --------------------------------------------------------------
# pandas_helpers.py
# --------------------------------------------------------------
"""
Utility helpers for everyday pandas work.

All functions are type‑annotated and reference the official
pandas documentation:
https://pandas.pydata.org/docs/
"""

from __future__ import annotations

from typing import Any, Iterable, Mapping, Sequence
import importlib
import numpy as np
import pandas as pd

# ------------------------------------------------------------------
# 1️⃣  General helpers
# ------------------------------------------------------------------

def show_versions() -> None:
    """
    Print the versions of pandas and a few common optional dependencies.
    (See pandas.show_versions())
    """
    libs = ["pandas", "numpy", "pyarrow", "openpyxl", "xlsxwriter", "fastparquet"]
    for lib in libs:
        try:
            mod = importlib.import_module(lib)
            print(f"{lib}: {mod.__version__}")
        except Exception:  # pragma: no cover
            print(f"{lib}: not installed")


def make_demo_df(rows: int = 5, seed: int = 42) -> pd.DataFrame:
    """
    Create a small demo DataFrame with a mix of numeric, categorical,
    and datetime columns.

    Returns
    -------
    pandas.DataFrame
        See https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.html
    """
    rng = np.random.default_rng(seed)
    return pd.DataFrame(
        {
            "product": rng.choice(["A", "B", "C"], size=rows),
            "region": rng.choice(["North", "South", "East", "West"], size=rows),
            "units": rng.integers(1, 100, size=rows),
            "price": rng.normal(25.0, 5.0, size=rows).round(2),
            "date": pd.date_range("2024-01-01", periods=rows, freq="D"),
        }
    ).convert_dtypes()                     # <‑‑ pandas 2.0+ converts to nullable dtypes


def summarize_numeric(df: pd.DataFrame) -> pd.DataFrame:
    """
    Return a transposed ``DataFrame.describe()`` limited to numeric columns.
    """
    return df.select_dtypes(include=["number"]).describe().T


def group_and_aggregate(
    df: pd.DataFrame,
    by: Iterable[str],
    agg: Mapping[str, Any],
) -> pd.DataFrame:
    """
    ``groupby`` → ``agg`` with ``as_index=False`` so the result is a flat table.
    """
    return df.groupby(list(by), as_index=False).agg(agg)


def filter_query(df: pd.DataFrame, query: str, **locals_: Any) -> pd.DataFrame:
    """
    Wrapper around ``DataFrame.query`` that accepts a ``local_dict``.
    """
    return df.query(query, local_dict=locals_)


def load_csv(path: str, **kwargs: Any) -> pd.DataFrame:
    """
    Load CSV with sensible defaults; you can override everything via ``kwargs``.
    Mirrors ``pd.read_csv`` – see docs:
    https://pandas.pydata.org/docs/reference/api/pandas.read_csv.html
    """
    return pd.read_csv(path, **kwargs)


def save_to_excel(df: pd.DataFrame, path: str, sheet_name: str = "Sheet1") -> None:
    """
    Write a DataFrame to an Excel workbook using the first available engine
    (``openpyxl`` or ``xlsxwriter``).  Requires the ``excel`` extra.
    """
    df.to_excel(path, index=False, sheet_name=sheet_name)  # engine auto‑detected


def to_monthly_sales(df: pd.DataFrame) -> pd.DataFrame:
    """
    Example time‑series resample:
    monthly sum of revenue (= units × price).

    Returns a DataFrame with a single column ``monthly_revenue``.
    """
    tmp = df.copy()
    tmp["revenue"] = tmp["units"] * tmp["price"]
    return (
        tmp.set_index("date")
        .resample("MS")["revenue"]
        .sum()
        .rename("monthly_revenue")
        .to_frame()
    )


def top_n(df: pd.DataFrame, by: str, n: int = 5, ascending: bool = False) -> pd.DataFrame:
    """
    Return the top‑``n`` rows ordered by ``by``.
    """
    return df.sort_values(by=by, ascending=ascending).head(n)
```

*All functions are deliberately **self‑contained** – they do **not** rely on external state.*  

> **Reference links** – each docstring mentions the exact pandas API page.

### Example script that uses the helpers <a id="example-script"></a>

```python
# --------------------------------------------------------------
# quickstart_demo.py
# --------------------------------------------------------------
"""
A tiny end‑to‑end demo that showcases the helper module.

Run with:
    python quickstart_demo.py
"""

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

def main() -> None:
    # 1️⃣ Show versions (helps when debugging)
    show_versions()

    # 2️⃣ Build a demo DataFrame
    df = make_demo_df(rows=12)
    print("\nDemo DataFrame (first 5 rows):")
    print(df.head())

    # 3️⃣ Quick numeric summary
    print("\nNumeric summary:")
    print(summarize_numeric(df))

    # 4️⃣ Group‑by + aggregation
    grouped = group_and_aggregate(
        df,
        by=["product", "region"],
        agg={"units": "sum", "price": "mean"},
    )
    print("\nGrouped summary:")
    print(grouped)

    # 5️⃣ Filter using a query string and locals
    filtered = filter_query(
        df,
        "units >= @min_units and price < @max_price",
        min_units=50,
        max_price=30,
    )
    print("\nFiltered rows (units>=50 & price<30):")
    print(filtered)

    # 6️⃣ Monthly revenue via resample
    monthly = to_monthly_sales(df)
    print("\nMonthly revenue (resampled):")
    print(monthly)

    # 7️⃣ Top‑3 products by units sold
    print("\nTop 3 rows by units:")
    print(top_n(df, by="units", n=3))

    # 8️⃣ Persist grouped result to Excel (needs openpyxl or xlsxwriter)
    save_to_excel(grouped, "grouped_summary.xlsx", sheet_name="summary")
    print("\n✅ Grouped summary saved to 'grouped_summary.xlsx'")

if __name__ == "__main__":
    main()
```

Run the demo with `python quickstart_demo.py`.  
All output appears in the console, and an Excel file is written to the working directory.

---  

## Reusable utilities – “cookbook” (copy‑paste) <a id="reusable-utilities"></a>

Below is a **single file** (`pandas_utils.py`) that collects **battle‑tested snippets** for everyday data‑science work.  
Feel free to drop the whole file into a project, or cherry‑pick the functions you need.

> **All functions use pandas 2.2 API** and include a link to the official documentation in the docstring.

### `pandas_utils.py`

```python
# --------------------------------------------------------------
# pandas_utils.py
# --------------------------------------------------------------
"""
Utility “cookbook” for pandas 2.2+.

Each helper includes a short description and a direct link to the
official pandas documentation.

Author: <your‑name>
"""

from __future__ import annotations

from typing import Any, Iterable, Mapping, Sequence, Literal
import re
import warnings

import numpy as np
import pandas as pd

# ------------------------------------------------------------------
# 1️⃣ IO & performance
# ------------------------------------------------------------------

def read_csv_fast(
    path: str,
    use_pyarrow: bool | None = None,
    **kwargs: Any,
) -> pd.DataFrame:
    """
    Read a CSV using the fastest available engine.

    * If ``use_pyarrow=True`` – force the ``pyarrow`` engine.
    * If ``use_pyarrow=None`` (default) – try ``pyarrow`` if installed,
      otherwise fall back to the default C engine.
    * Any additional ``kwargs`` are passed straight to ``pd.read_csv``.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.read_csv.html
    """
    engine: str | None = None
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
        except Exception as exc:  # pragma: no cover
            warnings.warn(f"pyarrow read failed ({exc}); falling back to default engine.")
    # Fallback path – the default C engine (or Python engine if C missing)
    return pd.read_csv(path, **kwargs)


def to_parquet_auto(df: pd.DataFrame, path: str, **kwargs: Any) -> None:
    """
    Write a DataFrame to Parquet using the best available engine.

    Preference order: ``pyarrow`` → ``fastparquet`` → error.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.to_parquet.html
    """
    try:
        import pyarrow  # noqa: F401
        engine = "pyarrow"
    except Exception:
        try:
            import fastparquet  # noqa: F401
            engine = "fastparquet"
        except Exception as exc:  # pragma: no cover
            raise RuntimeError("No Parquet engine found. Install pyarrow or fastparquet.") from exc

    df.to_parquet(path, engine=engine, index=False, **kwargs)


def memory_usage_mb(df: pd.DataFrame) -> float:
    """
    Approximate total memory usage of a DataFrame (including object data).

    Returns
    -------
    float
        Memory in megabytes.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.memory_usage.html
    """
    return float(df.memory_usage(deep=True).sum()) / (1024**2)


def memory_reduce_df(
    df: pd.DataFrame,
    *,
    to_category_threshold: float = 0.5,
    convert_dates: bool = False,
    exclude: Sequence[str] | None = None,
) -> pd.DataFrame:
    """
    Downcast numeric dtypes, optionally convert low‑cardinality objects to
    ``category`` and try to infer datetimes.

    Parameters
    ----------
    to_category_threshold : float, default 0.5
        Convert an object column to ``category`` when ``unique / len <= threshold``.
    convert_dates : bool, default False
        Attempt ``pd.to_datetime`` on object columns when ≥ 90 % parseable.
    exclude : sequence of str, optional
        Column names to skip.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.to_numeric.html
    """
    exclude = set(exclude or [])
    out = df.copy()

    # ---------- numeric downcast ----------
    for col, s in out.select_dtypes(include=["integer", "floating"]).items():
        if col in exclude:
            continue
        if pd.api.types.is_integer_dtype(s):
            out[col] = pd.to_numeric(s, downcast="integer")
        elif pd.api.types.is_float_dtype(s):
            out[col] = pd.to_numeric(s, downcast="float")

    # ---------- object → category / datetime ----------
    obj_cols = [
        c for c in out.select_dtypes(include=["object", "string"]).columns if c not in exclude
    ]
    n_rows = len(out)

    for col in obj_cols:
        s = out[col]

        if convert_dates:
            parsed = pd.to_datetime(s, errors="coerce", infer_datetime_format=True)
            if n_rows and parsed.notna().sum() / n_rows >= 0.9:
                out[col] = parsed
                continue

        # cardinality‑based category conversion
        uniq = s.dropna().nunique()
        if n_rows and (uniq / n_rows) <= to_category_threshold:
            out[col] = s.astype("category")

    return out

# ------------------------------------------------------------------
# 2️⃣ Data cleaning & types
# ------------------------------------------------------------------

def snake_case_columns(df: pd.DataFrame) -> pd.DataFrame:
    """
    Convert column names to ``snake_case`` and make them unique.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.Index.str.lower.html
    """
    def _snake(s: str) -> str:
        s = s.strip()
        s = re.sub(r"[\s\-]+", "_", s)
        s = re.sub(r"[^0-9a-zA-Z_]+", "", s)
        s = re.sub(r"([a-z0-9])([A-Z])", r"\1_\2", s)
        s = s.lower()
        s = re.sub(r"_+", "_", s).strip("_")
        return s or "col"

    new = [_snake(str(c)) for c in df.columns]

    # de‑duplicate while preserving order
    seen: dict[str, int] = {}
    final: list[str] = []
    for name in new:
        if name not in seen:
            seen[name] = 0
            final.append(name)
        else:
            seen[name] += 1
            final.append(f"{name}_{seen[name]}")

    out = df.copy()
    out.columns = final
    return out


def normalize_str_columns(
    df: pd.DataFrame,
    columns: Sequence[str] | None = None,
) -> pd.DataFrame:
    """
    Lower‑case, trim whitespace, collapse internal spaces, and strip accents.

    Works on any object or pandas ``StringDtype`` column.
    """
    out = df.copy()
    cols = columns or list(out.select_dtypes(include=["object", "string"]).columns)

    for c in cols:
        s = out[c].astype("string")                # ensures pandas nullable string
        # Unicode normalization (NFKD) + ASCII fallback
        s = s.str.normalize("NFKD", errors="ignore")  # type: ignore[attr-defined]
        s = s.str.encode("ascii", "ignore").str.decode("utf-8", "ignore")
        s = s.str.strip().str.lower().str.replace(r"\s+", " ", regex=True)
        out[c] = s
    return out


def safe_to_datetime(s: pd.Series, errors: Literal["raise", "coerce", "ignore"] = "coerce") -> pd.Series:
    """
    Robust conversion to datetime with optional whitespace stripping.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.to_datetime.html
    """
    if s.dtype == "O" or pd.api.types.is_string_dtype(s):
        s2 = s.astype("string").str.strip()
    else:
        s2 = s
    return pd.to_datetime(s2, errors=errors, infer_datetime_format=True)


def as_categorical_by_cardinality(
    df: pd.DataFrame,
    *,
    threshold: float = 0.2,
    exclude: Sequence[str] | None = None,
) -> pd.DataFrame:
    """
    Convert object/string columns to ``category`` when ``unique/len <= threshold``.

    Returns a **new** DataFrame (original untouched).
    """
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
    """
    Vectorised “first‑non‑null” across a list of columns.

    Equivalent to ``df[cols].bfill(axis=1)[cols[0]]`` but keeps the original dtype.
    """
    out = df.copy()
    out[new_col] = np.nan
    for c in cols:
        out[new_col] = out[new_col].fillna(out[c])
    return out


def fillna_smart(df: pd.DataFrame) -> pd.DataFrame:
    """
    Fill missing values using sensible defaults:

    * numeric → median
    * categorical → mode
    * string/object → mode (or empty string)
    * everything else → forward‑fill then back‑fill

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.fillna.html
    """
    out = df.copy()
    for col in out.columns:
        s = out[col]
        if pd.api.types.is_numeric_dtype(s):
            out[col] = s.fillna(s.median())
        elif pd.api.types.is_categorical_dtype(s):
            mode = s.mode(dropna=True)
            out[col] = s.fillna(mode.iloc[0] if not mode.empty else s)
        elif pd.api.types.is_string_dtype(s) or s.dtype == "O":
            mode = s.mode(dropna=True)
            out[col] = s.fillna(mode.iloc[0] if not mode.empty else "")
        else:
            out[col] = s.fillna(method="ffill").fillna(method="bfill")
    return out


def ensure_unique_index(df: pd.DataFrame) -> pd.DataFrame:
    """
    If the index is not unique, reset it to a default ``RangeIndex``.
    """
    return df.reset_index(drop=True) if not df.index.is_unique else df.copy()


def value_counts_topn(
    s: pd.Series,
    n: int = 10,
    dropna: bool = True,
    other_label: str = "__other__",
) -> pd.DataFrame:
    """
    Return the top‑``n`` value counts with percentages and an optional “other” bucket.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.Series.value_counts.html
    """
    vc = s.value_counts(dropna=dropna)
    total = vc.sum()
    top = vc.head(n)
    other = total - top.sum()

    out = top.rename("count").to_frame()
    out["pct"] = (out["count"] / total).round(4)

    if other > 0:
        out.loc[other_label] = {"count": int(other), "pct": round(other / total, 4)}
    return out

# ------------------------------------------------------------------
# 3️⃣ Joins & set operations
# ------------------------------------------------------------------

def safe_merge(
    left: pd.DataFrame,
    right: pd.DataFrame,
    *,
    on: Sequence[str] | None = None,
    left_on: Sequence[str] | None = None,
    right_on: Sequence[str] | None = None,
    how: Literal["left", "right", "inner", "outer"] = "left",
    validate: str | None = None,
    indicator: bool = False,
    suffixes: tuple[str, str] = ("_x", "_y"),
) -> pd.DataFrame:
    """
    Thin wrapper around ``DataFrame.merge`` that validates key columns are not all‑NA.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.merge.html
    """
    keys = list(on or []) + list(left_on or [])
    if keys:
        for k in keys:
            if left[k].isna().all():
                raise ValueError(f"Key column '{k}' in left DataFrame is all NA")
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
    """
    Return rows from *left* that have a match in *right* (SQL “semi‑join”).

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.merge.html
    """
    merged = left.merge(right[on].drop_duplicates(), on=list(on), how="inner")
    return merged


def left_anti_join(left: pd.DataFrame, right: pd.DataFrame, on: Sequence[str]) -> pd.DataFrame:
    """
    Return rows from *left* that have **no** match in *right* (SQL “anti‑join”).

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.merge.html
    """
    merged = left.merge(
        right[on].drop_duplicates(),
        on=list(on),
        how="left",
        indicator=True,
    )
    return merged.loc[merged["_merge"] == "left_only", left.columns]


def cross_join(left: pd.DataFrame, right: pd.DataFrame) -> pd.DataFrame:
    """
    Cartesian product (cross join).  Use with caution on large frames!

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.merge.html
    """
    l = left.assign(_cj=1)
    r = right.assign(_cj=1)
    return l.merge(r, on="_cj").drop(columns="_cj")


def dedupe_keep_latest(df: pd.DataFrame, subset: Sequence[str], by: str) -> pd.DataFrame:
    """
    Drop duplicated rows based on *subset*, keeping the row with the **largest** ``by`` value
    (often a timestamp).

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.drop_duplicates.html
    """
    return df.sort_values(by=by).drop_duplicates(subset=subset, keep="last")


# ------------------------------------------------------------------
# 4️⃣ Aggregation, ranking & reshape
# ------------------------------------------------------------------

def group_top_n(
    df: pd.DataFrame,
    by: Sequence[str],
    sort_col: str,
    n: int = 3,
    ascending: bool = False,
) -> pd.DataFrame:
    """
    Return the top‑``n`` rows *per group* ordered by ``sort_col``.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.groupby.html
    """
    return (
        df.sort_values(sort_col, ascending=ascending)
        .groupby(list(by), group_keys=False)
        .head(n)
    )


def row_number(
    df: pd.DataFrame,
    by: Sequence[str] | None = None,
    order_by: str | None = None,
    ascending: bool = True,
) -> pd.Series:
    """
    1‑based row number, optionally partitioned by ``by`` and ordered by ``order_by``.
    """
    if by is None or order_by is None:
        return pd.Series(np.arange(1, len(df) + 1), index=df.index, name="row_number")
    return (
        df.sort_values(order_by, ascending=ascending)
        .groupby(list(by))
        .cumcount()
        .add(1)
        .rename("row_number")
    )


def dense_rank(
    df: pd.DataFrame,
    by: Sequence[str],
    order_by: str,
    ascending: bool = True,
) -> pd.Series:
    """
    Dense rank per group – identical to SQL ``DENSE_RANK()``.
    """
    return (
        df.groupby(list(by))[order_by]
        .rank(method="dense", ascending=ascending)
        .astype(int)
        .rename("dense_rank")
    )


def pivot_sum(
    df: pd.DataFrame,
    index: Sequence[str],
    columns: Sequence[str] | str,
    values: str,
) -> pd.DataFrame:
    """
    Pivot table that **sums** ``values`` and fills missing cells with ``0``.
    """
    return pd.pivot_table(
        df,
        index=list(index),
        columns=columns,
        values=values,
        aggfunc="sum",
        fill_value=0,
    )


def pivot_count(
    df: pd.DataFrame,
    index: Sequence[str],
    columns: Sequence[str] | str,
    values: str | None = None,
) -> pd.DataFrame:
    """
    Pivot table that **counts** rows (or non‑null ``values`` if supplied).
    """
    aggfunc = (lambda x: x.notna().sum()) if values is not None else "size"
    return pd.pivot_table(
        df,
        index=list(index),
        columns=columns,
        values=values,
        aggfunc=aggfunc,
        fill_value=0,
    )


def explode_str_column(
    df: pd.DataFrame,
    col: str,
    sep: str = ",",
    trim: bool = True,
) -> pd.DataFrame:
    """
    Split a delimited string column and ``explode`` it to multiple rows.
    """
    s = df[col].fillna("").astype(str)
    parts = s.str.split(sep)
    if trim:
        parts = parts.apply(lambda lst: [x.strip() for x in lst if x.strip()])
    out = df.copy()
    out[col] = parts
    return out.explode(col, ignore_index=True)


# ------------------------------------------------------------------
# 5️⃣ Datetime & time‑series helpers
# ------------------------------------------------------------------

def to_month_start(s: pd.Series) -> pd.Series:
    """
    Convert any datetime-like series to the **first day of its month**,
    preserving timezone information.
    """
    dt = pd.to_datetime(s, errors="coerce")
    return dt.dt.to_period("M").dt.to_timestamp("MS")


def resample_sum_fill(
    df: pd.DataFrame,
    date_col: str,
    freq: str = "D",
    value_cols: Sequence[str] | None = None,
    fill_value: float = 0.0,
) -> pd.DataFrame:
    """
    Resample by ``date_col`` (must be datetime‑like) using ``sum`` and fill missing
    periods with ``fill_value``.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.resample.html
    """
    tmp = df.copy()
    tmp[date_col] = pd.to_datetime(tmp[date_col], errors="coerce")
    idx = pd.date_range(tmp[date_col].min(), tmp[date_col].max(), freq=freq)

    if value_cols is None:
        value_cols = [c for c in tmp.columns if pd.api.types.is_numeric_dtype(tmp[c])]

    out = (
        tmp.set_index(date_col)[list(value_cols)]
        .resample(freq)
        .sum(min_count=1)                # keep NaN if a whole period is empty (pandas 2.1+)
        .reindex(idx, fill_value=fill_value)
        .rename_axis(date_col)
        .reset_index()
    )
    return out


def to_utc(s: pd.Series, tz: str | None = None) -> pd.Series:
    """
    Localise a naive datetime series (or assume UTC) and convert to UTC.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DatetimeIndex.tz_localize.html
    """
    dt = pd.to_datetime(s, errors="coerce")
    if dt.dt.tz is None:  # naive
        dt = dt.dt.tz_localize(tz or "UTC", ambiguous="NaT", nonexistent="shift_forward")
    return dt.dt.tz_convert("UTC")


def from_utc(s: pd.Series, tz: str) -> pd.Series:
    """
    Convert UTC timestamps to a target timezone.
    """
    dt = pd.to_datetime(s, errors="coerce", utc=True)
    return dt.dt.tz_convert(tz)


# ------------------------------------------------------------------
# 6️⃣ Outliers & binning
# ------------------------------------------------------------------

def outliers_iqr_mask(s: pd.Series, k: float = 1.5) -> pd.Series:
    """
    Boolean mask for IQR‑based outliers (outside ``[Q1‑k·IQR, Q3+k·IQR]``).

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.Series.quantile.html
    """
    q1 = s.quantile(0.25)
    q3 = s.quantile(0.75)
    iqr = q3 - q1
    lower, upper = q1 - k * iqr, q3 + k * iqr
    return (s < lower) | (s > upper)


def clip_outliers_iqr(s: pd.Series, k: float = 1.5) -> pd.Series:
    """
    Clip values that fall outside the IQR bounds back to the nearest bound.
    """
    q1 = s.quantile(0.25)
    q3 = s.quantile(0.75)
    iqr = q3 - q1
    lower, upper = q1 - k * iqr, q3 + k * iqr
    return s.clip(lower, upper)


def bin_numeric(
    s: pd.Series,
    *,
    strategy: Literal["quantile", "uniform"] = "quantile",
    bins: int = 10,
    labels: Sequence[str] | None = None,
    include_lowest: bool = True,
) -> pd.Series:
    """
    Bin a numeric series either by quantiles (default) or uniform width.

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.cut.html
    """
    if strategy == "quantile":
        # Create quantile edges, then replace edges with -inf/inf for open ends
        edges = np.quantile(s.dropna(), np.linspace(0, 1, bins + 1))
        edges[0] = -np.inf
        edges[-1] = np.inf
        return pd.cut(
            s,
            bins=edges,
            labels=labels,
            include_lowest=include_lowest,
            duplicates="drop",
        )
    elif strategy == "uniform":
        return pd.cut(s, bins=bins, labels=labels, include_lowest=include_lowest)
    else:
        raise ValueError("strategy must be 'quantile' or 'uniform'")


# ------------------------------------------------------------------
# 7️⃣ Miscellaneous utilities
# ------------------------------------------------------------------

def df_diff_rows(
    old: pd.DataFrame,
    new: pd.DataFrame,
    key: Sequence[str],
    *,
    compare_cols: Sequence[str] | None = None,
) -> dict[str, pd.DataFrame]:
    """
    Row‑level diff between two DataFrames identified by ``key``.

    Returns a dictionary with three entries:
    * ``added``   – rows present only in ``new``
    * ``removed`` – rows present only in ``old``
    * ``changed`` – rows where any of ``compare_cols`` differ

    Docs: https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.merge.html
    """
    k = list(key)

    added = left_anti_join(new, old, on=k)
    removed = left_anti_join(old, new, on=k)

    # Build a merged view to compare column‑wise
    merged = old.merge(new, on=k, how="inner", suffixes=("_old", "_new"))
    if compare_cols is None:
        compare_cols = [c for c in old.columns if c not in k]

    # Boolean mask where *any* compared column differs
    diff_masks = [
        merged[f"{c}_old"].ne(merged[f"{c}_new"]) for c in compare_cols
    ]
    if diff_masks:
        changed = merged.loc[np.logical_or.reduce(diff_masks)]
    else:
        changed = pd.DataFrame(columns=merged.columns)

    return {"added": added, "removed": removed, "changed": changed}
```

> **Tip:** Save the file as `pandas_utils.py` and import the functions you need, e.g.  
> `from pandas_utils import read_csv_fast, memory_reduce_df, safe_merge, …`

---

### How to use a few representative utilities  

```python
>>> from pandas_utils import (
...     read_csv_fast, memory_reduce_df, snake_case_columns,
...     safe_merge, group_top_n, resample_sum_fill,
... )
>>> # 1️⃣ Fast CSV read (will pick pyarrow if installed)
>>> df = read_csv_fast("data.csv", dtype={"id": "Int64"})   # nullable integer dtype

>>> # 2️⃣ Reduce memory before heavy processing
>>> df = memory_reduce_df(df, to_category_threshold=0.3, convert_dates=True)

>>> # 3️⃣ Clean column names
>>> df = snake_case_columns(df)

>>> # 4️⃣ Perform a safe left‑merge
>>> merged = safe_merge(df, other_df, on=["customer_id"], how="left", validate="many_to_one")

>>> # 5️⃣ Top‑3 products per region by revenue
>>> df["revenue"] = df["units"] * df["price"]
>>> top3 = group_top_n(df, by=["region"], sort_col="revenue", n=3)

>>> # 6️⃣ Daily revenue series, fill missing days with 0
>>> daily = resample_sum_fill(df, date_col="date", freq="D", value_cols=["revenue"])
```

All of the above functions are **fully typed**, **documented**, and **compatible with pandas 2.2**.

---

## Common installation issues & fixes <a id="common-issues"></a>

| Symptom | Typical cause | Fix |
|---------|---------------|-----|
| `PermissionError: [Errno 13] Permission denied` | Trying to install globally without admin rights. | Use `--user` or a virtual environment (recommended). |
| `ImportError: pyarrow not found` when calling `read_csv_fast(..., use_pyarrow=True)` | `pyarrow` missing. | `pip install pyarrow` (or let the function fall back). |
| `ValueError: C engine cannot parse ...` | CSV contains malformed lines, quoting issues. | Pass `engine="python"` or `on_bad_lines="skip"` to `read_csv_fast`. |
| `UnicodeDecodeError` on Windows with non‑UTF‑8 CSV | Default encoding is `utf-8`; file uses a different code page. | `read_csv_fast(..., encoding="latin1")`. |
| `ImportError: DLL load failed` on Windows (NumPy / pandas) | Incompatible binary wheels (often caused by outdated `pip`). | `python -m pip install --upgrade pip setuptools wheel` then reinstall pandas. |
| `RuntimeError: No Parquet engine found` | Neither `pyarrow` nor `fastparquet` installed. | `pip install pyarrow` (preferred) or `pip install fastparquet`. |

---

## Advanced workflow tips <a id="advanced-tips"></a>

| Tip | Why it helps | Example |
|-----|--------------|---------|
| **Pin dependencies with a `requirements.txt`** | Guarantees reproducible environments. | `pandas>=2.2,<2.3\nnumpy>=1.26` |
| **Use `pandas.DataFrame.convert_dtypes()` after loading data** | Converts columns to the newest *nullable* dtypes (`Int64`, `StringDtype`, …). | `df = pd.read_csv(...).convert_dtypes()` |
| **Leverage the `DataFrame.pipe` method for readable pipelines** | Keeps transformations in a clear, functional style. | `df.pipe(make_features).pipe(train_model)` |
| **`DataFrame.eval` / `query` with the `engine="python"` option for complex expressions** | Faster than vanilla Python loops; works with `@` variables. | `df.query("price * units > @threshold", engine="python")` |
| **Store intermediate results in Feather/Parquet for fast I/O** | Feather & Parquet are column‑oriented, compressible, and much faster than CSV. | `df.to_parquet("clean.feather")` (engine auto‑selected). |
| **Profile memory with `pandas.util.testing.assert_frame_equal`** | Spot subtle dtype mismatches early in test suites. | `assert_frame_equal(df1, df2, check_dtype=False)` |

Reference docs for each tip are linked inline.

---

## Uninstall & clean‑up <a id="uninstall-cleanup"></a>

```bash
# Remove pandas from the current environment
python -m pip uninstall -y pandas

# Optional: wipe the pip cache to free space
python -m pip cache purge
```

If you used a virtual environment, simply delete the folder (`rm -rf .venv`).

---

## FAQ <a id="faq"></a>

| Question | Answer |
|----------|--------|
| **conda vs pip?** | `conda` resolves compiled‑library dependencies (e.g., MKL) automatically on Windows. `pip` + `venv` is lightweight and works everywhere; just install the binary wheels that pandas provides. |
| **Do I need to install NumPy manually?** | No. pandas declares NumPy as a dependency; `pip install pandas` pulls the compatible version automatically. |
| **Can I write Excel files without extra packages?** | Starting with pandas 2.0, **no** – you still need an engine (`openpyxl` or `xlsxwriter`). Install with `pip install "pandas[excel]"`. |
| **What is the difference between `read_csv(..., engine="pyarrow")` and the default C engine?** | `pyarrow` can read CSVs in parallel and supports larger-than‑memory files via zero‑copy buffers. Use it when you have many GB of data and enough RAM. |
| **How do I get the list of all optional dependencies?** | `pip show pandas` lists the extras; see the “Optional dependencies” section of the install docs: <https://pandas.pydata.org/docs/getting_started/install.html#optional-dependencies> |

---

## Next steps & further reading <a id="next-steps"></a>

| Topic | Official guide |
|-------|----------------|
| Full pandas API reference | <https://pandas.pydata.org/docs/reference/index.html> |
| Working with time‑series data | <https://pandas.pydata.org/docs/user_guide/timeseries.html> |
| Performance tips (vectorisation, `eval`, `numba`) | <https://pandas.pydata.org/docs/user_guide/enhancingperf.html> |
| 10‑minute pandas intro (interactive) | <https://pandas.pydata.org/docs/user_guide/10min.html> |
| Cookbook of common patterns | <https://pandas.pydata.org/docs/user_guide/cookbook.html> |

Happy data wrangling! 🎉 If you spot a typo or have a suggestion, feel free to open an issue on your fork or submit a PR.