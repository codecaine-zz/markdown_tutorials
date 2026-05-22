# datetime - Basic date and time types

Here are comprehensive and well-documented code examples for the Python standard library module `datetime`, focusing on its basic date and time types.

## Table of Contents

1. [Importing the datetime Module](#1-importing-the-datetime-module)
2. [Creating a Current Date and Time](#2-creating-a-current-date-and-time)
3. [Formatting Dates and Times](#3-formatting-dates-and-times)
4. [Creating a Specific Date](#4-creating-a-specific-date)
5. [Adding and Subtracting Time](#5-adding-and-subtracting-time)
6. [Parsing Strings into Dates](#6-parsing-strings-into-dates)
7. [Working with Time Zones](#7-working-with-time-zones)
8. [Comparing Dates and Times](#8-comparing-dates-and-times)
9. [Working with Time Periods](#9-working-with-time-periods)
10. [Creating Time Objects](#10-creating-time-objects)

### 1. Importing the `datetime` Module

The first step is to import the classes you need from the `datetime` module, which provides tools for manipulating dates and times.

```python
# Import the datetime class from the datetime module
from datetime import datetime
```

### 2. Creating a Current Date and Time

You can create a current date and time using the `now()` method of the `datetime` class.

```python
from datetime import datetime

# Get the current date and time
current_datetime = datetime.now()
print("Current Date and Time:", current_datetime)
```

### 3. Formatting Dates and Times

Dates and times can be formatted into readable strings using format codes passed to the `strftime()` method.

```python
from datetime import datetime

# Get the current date and time
current_datetime = datetime.now()

# Format the current date and time
formatted_date_time = current_datetime.strftime("%Y-%m-%d %H:%M:%S")
print("Formatted Date and Time:", formatted_date_time)
```

### 4. Creating a Specific Date

You can create a specific date and time by initializing a `datetime` object with `year`, `month`, and `day` arguments.

```python
from datetime import datetime

# Create a specific date
specific_date = datetime(2023, 10, 5)
print("Specific Date:", specific_date)
```

### 5. Adding and Subtracting Time

You can add or subtract periods of time from a `datetime` object using the `timedelta` class.

```python
from datetime import datetime, timedelta

# Get the current date and time
current_datetime = datetime.now()

# Create a timedelta of one day
one_day = timedelta(days=1)

# Add one day to the current date
future_date = current_datetime + one_day
print("Future Date:", future_date)
```

### 6. Parsing Strings into Dates

You can parse formatted date/time strings into actual `datetime` objects using the `strptime()` method.

```python
from datetime import datetime

# Parse a string into a datetime object
parsed_date_time = datetime.strptime("2023-10-05 14:30:00", "%Y-%m-%d %H:%M:%S")
print("Parsed Date and Time:", parsed_date_time)
```

### 7. Working with Time Zones

To work with time zones, you can use the `datetime.timezone` class to create timezone-aware datetime objects.

```python
from datetime import datetime, timezone

# Create a timezone object for UTC
utc_timezone = timezone.utc

# Get the current date and time in UTC
utc_datetime = datetime.now(utc_timezone)
print("Current Date and Time in UTC:", utc_datetime)
```

### 8. Comparing Dates and Times

`datetime` objects support standard comparison operators.

```python
from datetime import datetime

# Compare two dates
date1 = datetime(2023, 9, 5)
date2 = datetime(2023, 10, 5)

if date1 < date2:
    print("Date1 is before Date2")
elif date1 > date2:
    print("Date1 is after Date2")
else:
    print("Date1 and Date2 are the same")
```

### 9. Working with Time Periods

You can use the `timedelta` class to add or subtract arbitrary time spans, such as days or weeks.

```python
from datetime import datetime, timedelta

# Get the current date and time
current_datetime = datetime.now()

# Create a timedelta of 30 days (representing a month)
one_month = timedelta(days=30)

# Add 30 days to the current date
future_date_with_month = current_datetime + one_month
print("Future Date with Month:", future_date_with_month)
```

### 10. Creating Time Objects

You can represent just the time of day (without a date) using the `time` class.

```python
from datetime import time

# Create a specific time (14:30 / 2:30 PM)
specific_time = time(14, 30)
print("Specific Time:", specific_time)
```

### Conclusion

These examples cover the basic functionalities of the `datetime` module in Python. They demonstrate how to create, manipulate, and format dates and times, as well as work with time zones and compare dates and times. These examples are suitable for both beginner and advanced users and can be used in a variety of applications involving date and time handling.
