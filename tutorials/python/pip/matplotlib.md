# Complete Python Matplotlib Tutorial

## Table of Contents

1. [Introduction to Matplotlib](#introduction-to-matplotlib)
2. [Basic Plotting Functions](#basic-plotting-functions)
3. [Customizing Plots](#customizing-plots)
4. [Multiple Subplots](#multiple-subplots)
5. [Advanced Plotting](#advanced-plotting)
6. [Saving and Exporting](#saving-and-exporting)
7. [Complete Example: Analysis Dashboard](#complete-example-analysis-dashboard)
8. [Working with Colors and Styles](#working-with-colors-and-styles)
9. [Interactive Features and Animation](#interactive-features-and-animation)
10. [Best Practices and Tips](#best-practices-and-tips)

---

## Introduction to Matplotlib

Matplotlib is a comprehensive Python library for creating static, animated, and interactive visualizations in Python.

```python
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd

# Basic setup - this will display plots inline in Jupyter notebooks
%matplotlib inline  # For Jupyter notebooks only

print("Matplotlib version:", plt.matplotlib.__version__)
```

## Basic Plotting Functions

### Line Plots

```python
# Simple line plot with matplotlib
x = [1, 2, 3, 4, 5]
y = [2, 4, 6, 8, 10]

plt.figure(figsize=(8, 6))  # Set figure size (width, height)
plt.plot(x, y)              # Create line plot
plt.title("Simple Line Plot")    # Add title
plt.xlabel("X-axis")             # Label x-axis
plt.ylabel("Y-axis")             # Label y-axis
plt.grid(True)                   # Add grid for better readability
plt.show()                       # Display the plot

# Line plot with more customization options
x = np.linspace(0, 10, 100)      # Create 100 points from 0 to 10
y = np.sin(x)                    # Calculate sine values

plt.figure(figsize=(10, 6))
plt.plot(x, y, color='blue', linewidth=2, linestyle='-', marker='o', markersize=3)
plt.title("Sine Wave with Markers")
plt.xlabel("X values")
plt.ylabel("Y values (sin)")
plt.grid(True, alpha=0.3)        # Grid with transparency
plt.show()
```

### Scatter Plots

```python
# Create random data for scatter plot
np.random.seed(42)
x = np.random.randn(100)
y = np.random.randn(100)

plt.figure(figsize=(8, 6))
plt.scatter(x, y, c='red', alpha=0.6)  # alpha controls transparency
plt.title("Scatter Plot")
plt.xlabel("X values")
plt.ylabel("Y values")
plt.grid(True)
plt.show()

# Scatter plot with color mapping based on third variable
z = np.random.randn(100)  # Third variable for coloring

plt.figure(figsize=(8, 6))
scatter = plt.scatter(x, y, c=z, cmap='viridis', alpha=0.7)
plt.title("Scatter Plot with Color Mapping")
plt.xlabel("X values")
plt.ylabel("Y values")
plt.colorbar(scatter)  # Add color bar
plt.grid(True)
plt.show()
```

### Bar Charts

```python
# Simple bar chart
categories = ['A', 'B', 'C', 'D']
values = [23, 45, 56, 78]

plt.figure(figsize=(8, 6))
bars = plt.bar(categories, values)
plt.title("Simple Bar Chart")
plt.xlabel("Categories")
plt.ylabel("Values")

# Add value labels on top of bars
for bar, value in zip(bars, values):
    plt.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.5,
             str(value), ha='center', va='bottom')

plt.show()

# Horizontal bar chart
plt.figure(figsize=(8, 6))
bars = plt.barh(categories, values)
plt.title("Horizontal Bar Chart")
plt.xlabel("Values")
plt.ylabel("Categories")

# Add value labels on the bars
for i, (bar, value) in enumerate(zip(bars, values)):
    plt.text(bar.get_width() + 0.5, bar.get_y() + bar.get_height()/2,
             str(value), ha='left', va='center')

plt.show()
```

### Histograms

```python
# Create sample data
data = np.random.normal(100, 15, 1000)  # Normal distribution with mean=100, std=15

plt.figure(figsize=(8, 6))
plt.hist(data, bins=30, color='skyblue', edgecolor='black', alpha=0.7)
plt.title("Histogram of Sample Data")
plt.xlabel("Value")
plt.ylabel("Frequency")
plt.grid(True, alpha=0.3)
plt.show()

# Multiple histograms
data1 = np.random.normal(100, 15, 1000)
data2 = np.random.normal(90, 20, 1000)

plt.figure(figsize=(8, 6))
plt.hist(data1, bins=30, alpha=0.7, label='Dataset 1', color='blue')
plt.hist(data2, bins=30, alpha=0.7, label='Dataset 2', color='red')
plt.title("Multiple Histograms")
plt.xlabel("Value")
plt.ylabel("Frequency")
plt.legend()
plt.grid(True, alpha=0.3)
plt.show()
```

## Customizing Plots

### Axis and Tick Customization

```python
x = np.linspace(0, 10, 100)
y = np.sin(x)

plt.figure(figsize=(12, 8))
plt.plot(x, y)

# Customize axes limits
plt.xlim(0, 10)      # Set x-axis limits
plt.ylim(-1.5, 1.5)  # Set y-axis limits

# Customize tick locations and labels
plt.xticks(np.arange(0, 11, 2))          # X ticks every 2 units
plt.yticks(np.arange(-1.5, 1.6, 0.5))    # Y ticks every 0.5 units

# Rotate tick labels
plt.tick_params(axis='x', rotation=45)
plt.title("Customized Axis and Tick Labels")
plt.xlabel("X values")
plt.ylabel("Y values (sin)")
plt.grid(True)
plt.show()
```

### Legend and Annotations

```python
x = np.linspace(0, 10, 100)

plt.figure(figsize=(10, 6))
plt.plot(x, np.sin(x), label='sin(x)', linewidth=2)
plt.plot(x, np.cos(x), label='cos(x)', linewidth=2)

# Add legend
plt.legend(loc='upper right')    # Position of legend

# Add annotations
plt.annotate('Maximum sin', xy=(np.pi/2, 1), xytext=(np.pi/2 + 1, 0.5),
             arrowprops=dict(arrowstyle='->', color='red'))

# Add text
plt.text(5, -0.8, 'This is a note about the plot', fontsize=12,
         bbox=dict(boxstyle='round,pad=0.3', facecolor='yellow', alpha=0.7))

plt.title("Plot with Legend and Annotations")
plt.xlabel("X values")
plt.ylabel("Y values")
plt.grid(True)
plt.show()
```

### Text Formatting

```python
x = np.linspace(0, 2*np.pi, 100)

plt.figure(figsize=(12, 8))
plt.plot(x, np.sin(x), linewidth=3, label='sin(x)')

# Different text formatting options
plt.title("Sine Wave with Formatted Text", fontsize=16, fontweight='bold')
plt.xlabel("X values (radians)", fontsize=14)
plt.ylabel("Y values", fontsize=14)

# Customize tick labels font size and properties
plt.tick_params(axis='both', which='major', labelsize=12)

# Add grid with custom style
plt.grid(True, linestyle='--', alpha=0.7)

# Add text with various formatting options
plt.text(np.pi, 0.5, 'π/2 point', fontsize=12,
         bbox=dict(boxstyle='round,pad=0.3', facecolor='lightblue'))
plt.text(3*np.pi/2, -0.5, '3π/2 point', fontsize=14,
         bbox=dict(boxstyle='square,pad=0.3', facecolor='lightgreen'))

plt.legend()
plt.show()
```

## Multiple Subplots

### Grid Layout of Subplots

```python
# Create a figure with multiple subplots in a grid
fig, axes = plt.subplots(2, 2, figsize=(12, 10))  # 2 rows, 2 columns
fig.suptitle("Multiple Subplots Example", fontsize=16)  # Overall title

# Generate data for each subplot
x = np.linspace(0, 10, 100)

# First subplot (top-left)
axes[0, 0].plot(x, np.sin(x))
axes[0, 0].set_title("Sine Wave")
axes[0, 0].grid(True)

# Second subplot (top-right)
axes[0, 1].plot(x, np.cos(x), 'r-')
axes[0, 1].set_title("Cosine Wave")
axes[0, 1].grid(True)

# Third subplot (bottom-left)
axes[1, 0].plot(x, np.tan(x), 'g-')
axes[1, 0].set_title("Tangent Wave")
axes[1, 0].set_ylim(-5, 5)    # Limit y-axis for better visualization
axes[1, 0].grid(True)

# Fourth subplot (bottom-right)
axes[1, 1].plot(x, np.exp(-x/10))
axes[1, 1].set_title("Exponential Decay")
axes[1, 1].grid(True)

plt.tight_layout()   # Adjust spacing between subplots
plt.show()
```

### Subplot with Shared Axes

```python
# Create subplots with shared axes
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(8, 6), sharex=True)  # Share x-axis

x = np.linspace(0, 10, 100)

# First subplot
ax1.plot(x, np.sin(x))
ax1.set_ylabel("sin(x)")
ax1.grid(True)
ax1.set_title("Shared X-axis Example")

# Second subplot
ax2.plot(x, np.cos(x), 'r-')
ax2.set_xlabel("X values")
ax2.set_ylabel("cos(x)")
ax2.grid(True)

plt.tight_layout()
plt.show()
```

## Advanced Plotting

### 3D Plots

```python
from mpl_toolkits.mplot3d import Axes3D

# Create a 3D plot
fig = plt.figure(figsize=(10, 8))
ax = fig.add_subplot(111, projection='3d')

# Generate data for 3D surface
x = np.linspace(-5, 5, 50)
y = np.linspace(-5, 5, 50)
X, Y = np.meshgrid(x, y)
Z = np.sin(np.sqrt(X**2 + Y**2))

# Create 3D surface plot
ax.plot_surface(X, Y, Z, cmap='viridis', alpha=0.8)

ax.set_xlabel('X axis')
ax.set_ylabel('Y axis')
ax.set_zlabel('Z axis')
ax.set_title('3D Surface Plot')

plt.show()
```

### Scatter Plots with Color Coding

```python
# Generate random data
np.random.seed(42)
x = np.random.randn(1000)
y = np.random.randn(1000)
colors = np.random.rand(1000)  # Random colors for each point

plt.figure(figsize=(8, 6))
scatter = plt.scatter(x, y, c=colors, cmap='plasma', alpha=0.6)

# Add colorbar
plt.colorbar(scatter)

plt.xlabel('X values')
plt.ylabel('Y values')
plt.title('Scatter Plot with Color Coding')
plt.grid(True)
plt.show()
```

### Violin Plots

```python
# Generate sample data for violin plots
np.random.seed(42)
data1 = np.random.normal(0, 1, 1000)      # Normal distribution
data2 = np.random.normal(2, 1.5, 1000)    # Different mean and std

plt.figure(figsize=(8, 6))
plt.violinplot([data1, data2], positions=[1, 2], showmeans=True)

plt.xticks([1, 2], ['Dataset 1', 'Dataset 2'])
plt.ylabel('Values')
plt.title('Violin Plot Comparison')
plt.grid(True, alpha=0.3)
plt.show()
```

## Saving and Exporting

```python
# Create a sample plot to save
x = np.linspace(0, 10, 100)
y = np.sin(x)

plt.figure(figsize=(8, 6))
plt.plot(x, y)
plt.title("Sample Plot for Export")
plt.xlabel("X values")
plt.ylabel("Y values")

# Save the plot in different formats
plt.savefig('sample_plot.png', dpi=300, bbox_inches='tight')    # PNG with high resolution
plt.savefig('sample_plot.pdf', bbox_inches='tight')              # PDF format

# Show the plot
plt.show()
```

## Complete Example: Analysis Dashboard

```python
# Create a comprehensive dashboard-style visualization
fig = plt.figure(figsize=(15, 12))

# Time series data
dates = pd.date_range('2023-01-01', periods=100, freq='D')
values = np.cumsum(np.random.randn(100)) + 50

# Main time series plot (subplot 1)
ax1 = plt.subplot(2, 2, 1)
ax1.plot(dates, values, linewidth=2)
ax1.set_title('Time Series Analysis')
ax1.set_xlabel('Date')
ax1.set_ylabel('Value')
ax1.grid(True)

# Histogram subplot (subplot 2)
ax2 = plt.subplot(2, 2, 2)
ax2.hist(values, bins=30, alpha=0.7, color='skyblue', edgecolor='black')
ax2.set_title('Distribution of Values')
ax2.set_xlabel('Value')
ax2.set_ylabel('Frequency')

# Scatter plot subplot (subplot 3)
np.random.seed(42)
x_scatter = np.random.randn(100)
y_scatter = x_scatter * 0.5 + np.random.randn(100) * 0.5
ax3 = plt.subplot(2, 2, 3)
scatter = ax3.scatter(x_scatter, y_scatter, c=values[:100], cmap='coolwarm', alpha=0.6)
ax3.set_title('Scatter Plot with Color Coding')
ax3.set_xlabel('X values')
ax3.set_ylabel('Y values')

# Add colorbar for scatter plot
plt.colorbar(scatter, ax=ax3)

# Box plot subplot (subplot 4)
ax4 = plt.subplot(2, 2, 4)
data_for_boxplot = [values[:50], values[50:]]
ax4.boxplot(data_for_boxplot, labels=['First Half', 'Second Half'])
ax4.set_title('Box Plot Comparison')
ax4.set_ylabel('Value')

plt.tight_layout()
plt.show()

# Save the dashboard
plt.savefig('analysis_dashboard.png', dpi=300, bbox_inches='tight')
```

This comprehensive Python plotting tutorial covers:

1. Basic line plots with multiple series
2. Bar charts and histograms
3. Scatter plots with color coding
4. Subplots and shared axes
5. 3D plotting capabilities
6. Advanced visualization techniques (violin plots, etc.)
7. Exporting and saving figures in various formats

The code is well-commented and provides a solid foundation for creating publication-quality visualizations in Python using matplotlib.

## Working with Colors and Styles

### Color Maps and Color Schemes

```python
# Using different colormaps
import matplotlib.pyplot as plt
import numpy as np

# Generate sample data
x = np.linspace(0, 10, 100)
y1 = np.sin(x)
y2 = np.cos(x)
y3 = np.tan(x/2)

# Create subplots to show different color schemes
fig, axes = plt.subplots(2, 2, figsize=(12, 10))

# Different line colors and styles
axes[0,0].plot(x, y1, color='red', linestyle='-', linewidth=2, label='sin(x)')
axes[0,0].plot(x, y2, color='blue', linestyle='--', linewidth=2, label='cos(x)')
axes[0,0].plot(x, y3, color='green', linestyle='-.', linewidth=2, label='tan(x/2)')
axes[0,0].set_title('Custom Colors and Line Styles')
axes[0,0].legend()
axes[0,0].grid(True, alpha=0.3)
axes[0,0].set_ylim(-2, 2)

# Using hex colors
colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7']
for i, color in enumerate(colors):
    axes[0,1].plot(x, np.sin(x + i*0.5), color=color, linewidth=3, label=f'Wave {i+1}')
axes[0,1].set_title('Hex Color Palette')
axes[0,1].legend()
axes[0,1].grid(True, alpha=0.3)

# Colormap example with scatter plot
np.random.seed(42)
n = 100
x_scatter = np.random.randn(n)
y_scatter = np.random.randn(n)
colors_data = np.random.randn(n)

scatter = axes[1,0].scatter(x_scatter, y_scatter, c=colors_data, cmap='viridis', 
                          s=50, alpha=0.7, edgecolors='black', linewidth=0.5)
axes[1,0].set_title('Scatter Plot with Colormap')
plt.colorbar(scatter, ax=axes[1,0])

# Heatmap with custom colormap
data_2d = np.random.randn(10, 10)
im = axes[1,1].imshow(data_2d, cmap='RdYlBu', aspect='auto')
axes[1,1].set_title('Heatmap with RdYlBu Colormap')
plt.colorbar(im, ax=axes[1,1])

plt.tight_layout()
plt.show()
```

### Style Sheets and Themes

```python
# Available matplotlib styles
print("Available styles:")
print(plt.style.available)

# Using different built-in styles
styles = ['default', 'seaborn-v0_8', 'ggplot', 'dark_background']

fig, axes = plt.subplots(2, 2, figsize=(15, 10))
axes = axes.flatten()

x = np.linspace(0, 10, 50)
y = np.sin(x)

for i, style in enumerate(styles):
    with plt.style.context(style):
        axes[i].plot(x, y, linewidth=3)
        axes[i].plot(x, np.cos(x), linewidth=3)
        axes[i].set_title(f'Style: {style}')
        axes[i].grid(True, alpha=0.3)
        axes[i].legend(['sin(x)', 'cos(x)'])

plt.tight_layout()
plt.show()

# Creating custom style
custom_style = {
    'axes.facecolor': '#f8f9fa',
    'axes.edgecolor': '#343a40',
    'axes.linewidth': 1.2,
    'axes.grid': True,
    'axes.grid.alpha': 0.4,
    'grid.linewidth': 0.8,
    'grid.color': '#adb5bd',
    'xtick.color': '#495057',
    'ytick.color': '#495057',
    'text.color': '#212529',
    'font.size': 11
}

plt.rcParams.update(custom_style)

plt.figure(figsize=(10, 6))
plt.plot(x, np.sin(x), linewidth=3, color='#e74c3c', label='sin(x)')
plt.plot(x, np.cos(x), linewidth=3, color='#3498db', label='cos(x)')
plt.title('Plot with Custom Style', fontsize=16, fontweight='bold')
plt.xlabel('X values', fontsize=12)
plt.ylabel('Y values', fontsize=12)
plt.legend(fontsize=12)
plt.show()

# Reset to default style
plt.rcParams.update(plt.rcParamsDefault)
```

## Interactive Features and Animation

### Adding Interactivity

```python
# Interactive plot with widgets (requires ipywidgets in Jupyter)
import matplotlib.pyplot as plt
from matplotlib.widgets import Slider, Button
import numpy as np

# Create figure and axis
fig, ax = plt.subplots(figsize=(10, 8))
plt.subplots_adjust(bottom=0.25)

# Initial parameters
t = np.arange(0.0, 1.0, 0.001)
a0 = 5
f0 = 3
delta_f = 5.0
s = a0 * np.sin(2 * np.pi * f0 * t)
l, = plt.plot(t, s, lw=2)

plt.axis([0, 1, -10, 10])

# Add sliders
axcolor = 'lightgoldenrodyellow'
axfreq = plt.axes([0.2, 0.1, 0.5, 0.03], facecolor=axcolor)
axamp = plt.axes([0.2, 0.15, 0.5, 0.03], facecolor=axcolor)

sfreq = Slider(axfreq, 'Freq', 0.1, 30.0, valinit=f0, valfmt='%0.1f Hz')
samp = Slider(axamp, 'Amp', 0.1, 10.0, valinit=a0, valfmt='%0.1f')

def update(val):
    amp = samp.val
    freq = sfreq.val
    l.set_ydata(amp*np.sin(2*np.pi*freq*t))
    fig.canvas.draw_idle()

sfreq.on_changed(update)
samp.on_changed(update)

# Add reset button
resetax = plt.axes([0.8, 0.025, 0.1, 0.04])
button = Button(resetax, 'Reset', color=axcolor, hovercolor='0.975')

def reset(event):
    sfreq.reset()
    samp.reset()
button.on_clicked(reset)

plt.show()
```

### Simple Animation

```python
import matplotlib.animation as animation

# Create animated sine wave
fig, ax = plt.subplots(figsize=(10, 6))

x = np.linspace(0, 2*np.pi, 100)
line, = ax.plot(x, np.sin(x))
ax.set_ylim(-2, 2)
ax.set_title('Animated Sine Wave')
ax.grid(True, alpha=0.3)

def animate(frame):
    line.set_ydata(np.sin(x + frame/10))
    return line,

# Create animation
ani = animation.FuncAnimation(fig, animate, frames=200, 
                            interval=50, blit=True, repeat=True)

# To save animation (uncomment):
# ani.save('sine_wave_animation.gif', writer='pillow', fps=20)

plt.show()
```

## Best Practices and Tips

### Performance Optimization

```python
# Efficient plotting for large datasets
import time

# Generate large dataset
n_points = 100000
x_large = np.random.randn(n_points)
y_large = np.random.randn(n_points)

# Method 1: Regular plotting (slower for large datasets)
start_time = time.time()
plt.figure(figsize=(8, 6))
plt.scatter(x_large, y_large, alpha=0.1, s=1)
plt.title('Large Dataset - Regular Scatter Plot')
regular_time = time.time() - start_time
plt.show()
print(f"Regular plotting time: {regular_time:.2f} seconds")

# Method 2: Using rasterization for better performance
start_time = time.time()
fig, ax = plt.subplots(figsize=(8, 6))
ax.scatter(x_large, y_large, alpha=0.1, s=1, rasterized=True)
ax.set_title('Large Dataset - Rasterized Scatter Plot')
rasterized_time = time.time() - start_time
plt.show()
print(f"Rasterized plotting time: {rasterized_time:.2f} seconds")

# Method 3: Using hexbin for very large datasets
plt.figure(figsize=(8, 6))
plt.hexbin(x_large, y_large, gridsize=50, cmap='Blues')
plt.colorbar(label='Count')
plt.title('Large Dataset - Hexbin Plot')
plt.show()
```

### Publication-Ready Plots

```python
# Configure matplotlib for publication quality
plt.style.use('default')  # Start with clean style

# Set publication parameters
pub_params = {
    'figure.figsize': (8, 6),
    'figure.dpi': 300,
    'savefig.dpi': 300,
    'font.size': 12,
    'axes.labelsize': 14,
    'axes.titlesize': 16,
    'xtick.labelsize': 11,
    'ytick.labelsize': 11,
    'legend.fontsize': 12,
    'lines.linewidth': 2,
    'lines.markersize': 8
}

plt.rcParams.update(pub_params)

# Create publication-ready plot
fig, ax = plt.subplots(figsize=(8, 6))

x = np.linspace(0, 10, 100)
y1 = np.exp(-x/3) * np.cos(2*np.pi*x)
y2 = np.exp(-x/5) * np.sin(2*np.pi*x)

ax.plot(x, y1, 'b-', label='Damped Cosine', linewidth=2)
ax.plot(x, y2, 'r--', label='Damped Sine', linewidth=2)

ax.set_xlabel('Time (s)')
ax.set_ylabel('Amplitude')
ax.set_title('Damped Oscillations')
ax.legend(frameon=True, fancybox=True, shadow=True)
ax.grid(True, alpha=0.3)

# Remove top and right spines for cleaner look
ax.spines['top'].set_visible(False)
ax.spines['right'].set_visible(False)

plt.tight_layout()

# Save in multiple formats
plt.savefig('publication_plot.png', dpi=300, bbox_inches='tight')
plt.savefig('publication_plot.pdf', bbox_inches='tight')  # Vector format
plt.savefig('publication_plot.svg', bbox_inches='tight')  # Vector format

plt.show()

# Reset to default parameters
plt.rcParams.update(plt.rcParamsDefault)
```

This enhanced matplotlib tutorial now includes:

1. Working with colors, colormaps, and custom styling
2. Interactive features with widgets and simple animations
3. Performance optimization techniques for large datasets
4. Best practices for creating publication-ready plots
5. Multiple export formats for different use cases

The tutorial provides comprehensive coverage of matplotlib's capabilities, from basic plotting to advanced customization and optimization techniques.

```python
# Complete working example with all imports needed
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from mpl_toolkits.mplot3d import Axes3D

# Example data creation
x = np.linspace(0, 10, 100)
y1 = np.sin(x)
y2 = np.cos(x)

# Basic line plot with multiple series
plt.figure(figsize=(8, 6))
plt.plot(x, y1, label='sin(x)', linewidth=2)
plt.plot(x, y2, label='cos(x)', linewidth=2)
plt.xlabel('X values')
plt.ylabel('Y values')
plt.title('Sine and Cosine Functions')
plt.legend()
plt.grid(True)

# Show the plot
plt.show()

# Save the plot (uncomment to save)
# plt.savefig('sine_cosine_plot.png', dpi=300, bbox_inches='tight')

print("Plot created successfully!")
```
