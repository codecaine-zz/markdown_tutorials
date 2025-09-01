# Python ImageMagick Tutorial

## Table of Contents

1. [Introduction to ImageMagick and Python](#1-introduction-to-imagemagick-and-python)
2. [Installation and Setup](#2-installation-and-setup)
3. [Basic Image Operations](#3-basic-image-operations)
4. [Advanced Image Processing](#4-advanced-image-processing)
5. [Working with Multiple Images](#5-working-with-multiple-images)
6. [Image Manipulation Techniques](#6-image-manipulation-techniques)
7. [Color Management and Conversion](#7-color-management-and-conversion)
8. [Text Overlay and Annotations](#8-text-overlay-and-annotations)
9. [Batch Processing](#9-batch-processing)
10. [File Type Detection with python-magic](#8-file-type-detection-with-python-magic)
11. [Performance Optimization](#10-performance-optimization)
12. [Troubleshooting and Common Issues](#11-troubleshooting-and-common-issues)
13. [Complete Example Projects](#12-complete-example-projects)

---

## 1. Introduction to ImageMagick and Python

ImageMagick is a powerful open-source software suite for creating, editing, composing, or converting bitmap images. It can read and write over 200 major image formats including PNG, JPEG, GIF, TIFF, PDF, and many more.

### What is ImageMagick?

ImageMagick provides command-line tools (magick) that allow users to perform various operations on images such as:
- Resizing and cropping
- Color adjustments
- Filters and effects
- Text overlay
- Batch processing

### Why Use Python with ImageMagick?

While ImageMagick works natively from the command line, using it through Python offers several advantages:

1. **Automation**: Scripting capabilities for repetitive tasks
2. **Integration**: Seamlessly integrate image processing into larger applications
3. **Error Handling**: Proper exception handling in code
4. **Data Processing**: Work with image data programmatically

### Key Features of ImageMagick

- Support for 200+ file formats
- Rich collection of image manipulation tools
- Command-line interface and API access
- Cross-platform compatibility
- High-quality image processing algorithms

### What is python-magic?

**Important Note**: `python-magic` is a different library from ImageMagick's Python bindings. While ImageMagick focuses on image manipulation, `python-magic` is used for file type detection and MIME type identification.

**python-magic features:**
- Detect file types from file content (not just extensions)
- MIME type identification
- Works with any file type, not just images
- Useful for file validation and security
- Lightweight alternative for file type detection

**When to use python-magic:**
- File upload validation
- Content type detection
- Security checks before processing files
- Batch file classification
- Building file management tools

## 2. Installation and Setup

Before using ImageMagick with Python, you need to install both the ImageMagick software and a Python binding.

### Installing ImageMagick

#### Linux (Ubuntu/Debian):
```bash
sudo apt update
sudo apt install imagemagick


#### macOS:
```bash
# Using Homebrew
brew install imagemagick

# Using MacPorts
sudo port install ImageMagick


#### Windows:
1. Download from [ImageMagick website](https://imagemagick.org/script/download.php)
2. Run the installer and make sure to select "Add application directory to system PATH"

### Installing Python Bindings

There are several Python libraries that interface with ImageMagick:

#### Method 1: Wand (Recommended)

```bash
pip install Wand


Wand is a ctypes-based binding for ImageMagick, providing low-level access.

#### Method 2: python-magick

**Note**: The `python-magic` library is different from ImageMagick and is used for file type detection. It requires the system `libmagic` library.

```bash
pip install python-magic
```

**Important**: Before installing `python-magic`, you need to install the system `libmagic` library:

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install libmagic1 libmagic-dev
pip install python-magic
```

**macOS:**
```bash
# Using Homebrew
brew install libmagic
pip install python-magic

# Alternative using MacPorts
sudo port install file
pip install python-magic
```

**Windows:**
```bash
# Install python-magic-bin which includes libmagic
pip install python-magic-bin
# OR install manually from https://github.com/pidydx/libmagicwin64
```

#### Method 3: Using subprocess with command-line tools

For basic operations without dedicated Python bindings:

```python
import subprocess


### Verification Installation

Test your installation by running:

**For Wand (ImageMagick binding):**
```python
from wand.image import Image
print("ImageMagick version:", Image.magick_version)
```

**For python-magic (file type detection):**
```python
import magic

# Test basic functionality
test_content = b"Hello, World!"
file_type = magic.from_buffer(test_content)
print("File type detected:", file_type)

# Test MIME type detection
mime = magic.Magic(mime=True)
mime_type = mime.from_buffer(test_content)
print("MIME type:", mime_type)
```

If the python-magic test fails with "ImportError: failed to find libmagic", you need to install the system libmagic library as described above.


## 3. Basic Image Operations

Let's start with fundamental image operations using the Wand library.

### Loading Images

```python
from wand.image import Image
from wand.display import display

# Load an image from file
with Image(filename='input.jpg') as img:
    print(f"Image dimensions: {img.width} x {img.height}")
    print(f"Image format: {img.format}")

# Load from memory buffer (bytes)
with open('input.png', 'rb') as f:
    image_bytes = f.read()
with Image(blob=image_bytes) as img:
    # Process image
    pass


### Saving Images

```python
from wand.image import Image

# Save to file with specific format and quality
with Image(filename='input.jpg') as img:
    img.format = 'png'
    img.save(filename='output.png')

# Save with compression options
with Image(filename='input.jpg') as img:
    # Set JPEG quality (0-100)
    img.compression_quality = 85
    img.save(filename='compressed.jpg')


### Resizing Images

```python
from wand.image import Image

# Resize maintaining aspect ratio
with Image(filename='input.jpg') as img:
    img.resize(800, 600)  # Width x Height
    img.save(filename='resized.jpg')

# Resize to fit within bounds (maintains aspect ratio)
with Image(filename='input.jpg') as img:
    img.transform(resize='800x600>')
    img.save(filename='scaled.jpg')


### Cropping Images

```python
from wand.image import Image

# Crop image using coordinates (left, top, right, bottom)
with Image(filename='input.jpg') as img:
    # Crop from center with 400x300 dimensions
    img.crop(200, 150, width=400, height=300)
    img.save(filename='cropped.jpg')

# Center crop
with Image(filename='input.jpg') as img:
    # Calculate center coordinates for cropping
    crop_width = min(img.width, img.height) // 2
    left = (img.width - crop_width) // 2
    top = (img.height - crop_width) // 2
    
    img.crop(left, top, width=crop_width, height=crop_width)
    img.save(filename='center_cropped.jpg')


### Rotating and Flipping Images

```python
from wand.image import Image

# Rotate image by degrees
with Image(filename='input.jpg') as img:
    # Rotate 90 degrees clockwise
    img.rotate(90)
    img.save(filename='rotated_90.jpg')

# Flip horizontally or vertically
with Image(filename='input.jpg') as img:
    # Flip horizontally
    img.flop()
    img.save(filename='flipped_horizontal.jpg')
    
with Image(filename='input.jpg') as img:
    # Flip vertically
    img.flip()
    img.save(filename='flipped_vertical.jpg')

# Rotate with background color (for transparency)
with Image(filename='input.png') as img:
    img.rotate(45, background='white')
    img.save(filename='rotated_with_background.jpg')


### Basic Quality Adjustments

```python
from wand.image import Image

# Brightness adjustment
with Image(filename='input.jpg') as img:
    # Increase brightness by 20%
    img.brightness_contrast(brightness=20)
    img.save(filename='brightened.jpg')

# Contrast adjustment
with Image(filename='input.jpg') as img:
    # Increase contrast by 30%
    img.brightness_contrast(contrast=30)
    img.save(filename='contrasted.jpg')

# Gamma correction
with Image(filename='input.jpg') as img:
    img.gamma(2.2)  # Adjust gamma to make image brighter/darker
    img.save(filename='gamma_corrected.jpg')


## 4. Advanced Image Processing

### Filters and Effects

```python
from wand.image import Image

# Blur effects
with Image(filename='input.jpg') as img:
    # Gaussian blur with radius=5, sigma=3
    img.gaussian_blur(radius=5, sigma=3)
    img.save(filename='blurred.jpg')

# Motion blur (simulates motion)
with Image(filename='input.jpg') as img:
    img.motion_blur(radius=10, sigma=2)
    img.save(filename='motion_blur.jpg')

# Sharpening
with Image(filename='input.jpg') as img:
    # Unsharp mask with radius=1.5 and amount=1.5
    img.unsharp_mask(radius=1.5, sigma=1.5, amount=1.5)
    img.save(filename='sharpened.jpg')


### Morphological Operations

```python
from wand.image import Image

# Morphology operations (works on binary images)
with Image(filename='input.png') as img:
    # Erosion - reduces object size
    img.morphology('erode', 'disk:1x1+0+0')
    
    # Dilation - increases object size  
    img.morphology('dilate', 'disk:2x2+0+0')
    
    # Opening - erosion followed by dilation
    img.morphology('open', 'disk:3x3+0+0')
    
    img.save(filename='morphological.jpg')


### Image Enhancement Techniques

```python
from wand.image import Image

# Auto level adjustment (histogram equalization)
with Image(filename='input.jpg') as img:
    # Automatic histogram stretching
    img.auto_level()
    img.save(filename='auto_levelled.jpg')

# Contrast stretch to full range
with Image(filename='input.jpg') as img:
    # Stretch contrast using min/max values
    img.contrast_stretch(0)  # Use default black point
    img.save(filename='contrast_stretched.jpg')
    
# Adaptive histogram equalization  
with Image(filename='input.jpg') as img:
    # Apply equalize to enhance contrast
    img.equalize()
    img.save(filename='equalized.jpg')


### Noise Reduction

```python
from wand.image import Image

# Reduce noise using median filter
with Image(filename='input_noisy.jpg') as img:
    # Median filter with radius of 3 (3x3 kernel)
    img.median_filter(radius=3)
    img.save(filename='denoised_median.jpg')

# Denoise using blur filtering
with Image(filename='input_noisy.jpg') as img:
    # Blur filter with radius and sigma parameters
    img.blur(radius=2.0, sigma=1.0)
    img.save(filename='denoised_blur.jpg')

# Reduce speckle noise
with Image(filename='input_noisy.jpg') as img:
    # Remove small speckles with morphology
    img.morphology('open', 'disk:3x3+0+0')
    img.save(filename='speckle_reduced.jpg')


## 5. Color and Format Manipulation

### Color Space Transformations

```python
from wand.image import Image

# Convert to grayscale (luminosity method)
with Image(filename='input.jpg') as img:
    # Convert to grayscale using luminance weights
    img.colorspace = 'gray'
    img.save(filename='grayscale.jpg')

# Convert between color spaces
with Image(filename='input.jpg') as img:
    # Convert from RGB to CMYK
    img.colorspace = 'cmyk'
    img.save(filename='cmyk.jpg')
    
with Image(filename='input.jpg') as img:
    # Convert from RGB to HSL (Hue, Saturation, Lightness)
    img.colorspace = 'hsl'
    img.save(filename='hsl.jpg')

# Color separation
with Image(filename='input.jpg') as img:
    # Extract individual color channels
    # Clone the image for each channel
    red_channel = img.clone()
    red_channel.separate_channel('red')
    red_channel.save(filename='red_channel.jpg')
    
    green_channel = img.clone()
    green_channel.separate_channel('green')
    green_channel.save(filename='green_channel.jpg')
    
    blue_channel = img.clone()
    blue_channel.separate_channel('blue')
    blue_channel.save(filename='blue_channel.jpg')


### Color Adjustment and Correction

```python
from wand.image import Image

# Hue adjustment (color shift)
with Image(filename='input.jpg') as img:
    # Modulate hue (100% is normal, adjust by percentage)
    img.modulate(hue=150)  # Shift hue by 50%
    img.save(filename='hue_shifted.jpg')

# Saturation adjustment
with Image(filename='input.jpg') as img:
    # Modulate saturation (100% is normal, values > 100 increase saturation)
    img.modulate(saturation=150)
    img.save(filename='saturated.jpg')

# Lightness adjustment  
with Image(filename='input.jpg') as img:
    # Modulate brightness (100% is normal, higher increases brightness)
    img.modulate(brightness=120)
    img.save(filename='lightened.jpg')


### Format Conversion and Optimization

```python
from wand.image import Image

# Convert image format with quality settings
with Image(filename='input.jpg') as img:
    # Save as PNG (lossless)
    img.format = 'png'
    img.save(filename='output.png')

# JPEG compression optimization  
with Image(filename='input.jpg') as img:
    # Set compression quality to 85%
    img.compression_quality = 85
    img.save(filename='compressed.jpg')

# WebP format conversion (modern web image format)
with Image(filename='input.jpg') as img:
    # Convert to WebP with quality 90
    img.format = 'webp'
    img.compression_quality = 90
    img.save(filename='output.webp')


### Transparency and Alpha Channel Manipulation

```python
from wand.image import Image

# Handle transparency in PNG images  
with Image(filename='input.png') as img:
    # Remove alpha channel (make transparent areas opaque)
    img.alpha = 'off'
    img.save(filename='opaque.png')

# Extract alpha channel
with Image(filename='input.png') as img:
    # Create separate alpha mask using alpha channel information
    img.alpha_channel = 'extract'
    img.save(filename='alpha_channel.png')
    
# Set custom transparency
with Image(filename='input.jpg') as img:
    # Add transparent background with a specific color
    img.background_color = 'white'
    img.alpha = 'on'  # Enable transparency handling
    img.save(filename='transparent_background.jpg')


## 6. Text Overlay and Watermarking

### Adding Text to Images

```python
from wand.image import Image, Color
from wand.drawing import Drawing

# Simple text overlay using drawing context
def add_text_to_image(image_path, text, position=(10, 50), font_size=32):
    with Image(filename=image_path) as img:
        # Create a drawing context
        with Drawing() as draw:
            # Set font properties
            draw.font = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf'  # Path to font file
            draw.font_size = font_size
            draw.fill_color = Color('white')
            draw.stroke_color = Color('black')
            draw.stroke_width = 1
            
            # Add text at specified position
            draw.text(position[0], position[1], text)
            
            # Apply the drawing to the image
            draw(img)
            
        return img

# Usage example:
# result_image = add_text_to_image('input.jpg', 'Hello World!', (50, 100))


### Advanced Watermarking with Transparency

```python
from wand.image import Image, Color
from wand.drawing import Drawing

def create_watermark(image_path, watermark_text, opacity=0.3):
    with Image(filename=image_path) as img:
        # Create a new image for the watermark overlay
        watermark = Image(width=img.width, height=img.height)
        
        # Set up drawing context for watermark text
        with Drawing() as draw:
            draw.font = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf'
            draw.font_size = 48
            draw.fill_color = Color(f'rgba(255,255,255,{opacity*255})')
            
            # Position watermark at bottom center
            text_width = len(watermark_text) * 30  # Approximate width calculation
            x_pos = (img.width - text_width) // 2
            y_pos = img.height - 60
            
            draw.text(x_pos, y_pos, watermark_text)
            
            # Apply drawing to watermark image
            draw(watermark)
        
        # Composite the watermark onto original image
        img.composite(watermark, 0, 0)
        
    return img

# Usage:
# watermarked = create_watermark('input.jpg', '© My Company')


### Multiple Watermarks and Batch Processing

```python
import os
from wand.image import Image, Color
from wand.drawing import Drawing

```python
import os
from wand.image import Image, Color
from wand.drawing import Drawing

def batch_watermark(input_folder, output_folder, watermark_text):
    # Ensure output folder exists
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)
    
    # Process all images in input folder
    for filename in os.listdir(input_folder):
        if filename.lower().endswith(('.jpg', '.jpeg', '.png')):
            input_path = os.path.join(input_folder, filename)
            output_path = os.path.join(output_folder, f"watermarked_{filename}")
            
            with Image(filename=input_path) as img:
                # Add watermark text
                with Drawing() as draw:
                    draw.font_size = 24
                    draw.fill_color = Color('rgba(255,255,255,100)')
                    draw.text(10, img.height - 30, watermark_text)
                    
                    # Apply to image
                    draw(img)
                
                # Save with watermark
                img.save(filename=output_path)

# Usage:
# batch_watermark('images/', 'watermarked_images/', '© My Company')


## 7. Image Processing Pipelines and Automation

### Basic Image Resizing Pipeline

```python
from wand.image import Image
import os

class ImageProcessor:
    def __init__(self, input_folder, output_folder):
        self.input_folder = input_folder
        self.output_folder = output_folder
        
        # Create output folder if it doesn't exist
        if not os.path.exists(output_folder):
            os.makedirs(output_folder)
    
    def resize_image(self, image_path, width, height):
        """Resize image to specified dimensions"""
        with Image(filename=image_path) as img:
            img.resize(width, height)
            
            # Save resized image
            filename = os.path.basename(image_path)
            output_path = os.path.join(self.output_folder, f"resized_{filename}")
            
            img.save(filename=output_path)
    
    def process_batch(self, width=800, height=600):
        """Process all images in input folder"""
        for filename in os.listdir(self.input_folder):
            if filename.lower().endswith(('.jpg', '.jpeg', '.png')):
                input_path = os.path.join(self.input_folder, filename)
                self.resize_image(input_path, width, height)

# Usage:
# processor = ImageProcessor('input_images/', 'output_images/')
# processor.process_batch(width=1024, height=768)


### Advanced Processing Pipeline with Filters

```python
from wand.image import Image
import os

class AdvancedImageProcessor:
    def __init__(self, input_folder, output_folder):
        self.input_folder = input_folder
        self.output_folder = output_folder
        
        if not os.path.exists(output_folder):
            os.makedirs(output_folder)
    
    def apply_filters(self, image_path):
        """Apply multiple filters to an image"""
        with Image(filename=image_path) as img:
            # Apply different effects
            img.auto_orient()  # Fix orientation
            
            # Apply blur filter
            img.blur(radius=1.5, sigma=0.7)
            
            # Adjust brightness and contrast
            img.brightness_contrast(brightness=10, contrast=10)
            
            return img
    
    def process_with_filters(self):
        """Process all images with filters applied"""
        for filename in os.listdir(self.input_folder):
            if filename.lower().endswith(('.jpg', '.jpeg', '.png')):
                input_path = os.path.join(self.input_folder, filename)
                
                # Apply processing pipeline
                processed_img = self.apply_filters(input_path)
                
                output_path = os.path.join(self.output_folder, f"filtered_{filename}")
                processed_img.save(filename=output_path)

# Usage:
# processor = AdvancedImageProcessor('input_images/', 'output_images/')
# processor.process_with_filters()


### Configuration-Based Image Processing

```python
import json
from wand.image import Image
import os

class ConfigurableImageProcessor:
    def __init__(self, config_file):
        with open(config_file, 'r') as f:
            self.config = json.load(f)
    
    def process_images(self):
        """Process images based on configuration"""
        
        # Create output folders from config
        for folder_config in self.config['output_folders']:
            if not os.path.exists(folder_config['path']):
                os.makedirs(folder_config['path'])
        
        # Process each image
        for image_config in self.config['images']:
            input_path = image_config['input']
            
            with Image(filename=input_path) as img:
                # Apply transformations from config
                if 'resize' in image_config:
                    width, height = image_config['resize']
                    img.resize(width, height)
                
                if 'crop' in image_config:
                    x, y, width, height = image_config['crop']
                    img.crop(x, y, width, height)
                
                # Save to configured output folders
                for folder_config in self.config['output_folders']:
                    output_path = os.path.join(
                        folder_config['path'],
                        f"{folder_config['prefix']}{os.path.basename(input_path)}"
                    )
                    
                    img.save(filename=output_path)

# Example config.json:
"""
{
    "output_folders": [
        {"path": "./resized_images", "prefix": "resized_"},
        {"path": "./processed_images", "prefix": "proc_"}
    ],
    "images": [
        {
            "input": "./original.jpg",
            "resize": [800, 600],
            "crop": [100, 100, 400, 300]
        }
    ]
}
"""

# Usage:
# processor = ConfigurableImageProcessor('config.json')
# processor.process_images()


## 8. File Type Detection with python-magic

The `python-magic` library complements ImageMagick workflows by providing reliable file type detection before processing.

### Basic File Type Detection

```python
import magic
import os

def detect_file_type(file_path):
    """Detect file type using python-magic."""
    try:
        # Detect file type from file content
        file_type = magic.from_file(file_path)
        
        # Get MIME type
        mime = magic.Magic(mime=True)
        mime_type = mime.from_file(file_path)
        
        return {
            'description': file_type,
            'mime_type': mime_type
        }
    except Exception as e:
        return {'error': str(e)}

# Usage
file_info = detect_file_type('image.jpg')
print(f"File type: {file_info['description']}")
print(f"MIME type: {file_info['mime_type']}")
```

### Safe Image Processing Pipeline

```python
import magic
from wand.image import Image
import os

class SafeImageProcessor:
    def __init__(self):
        self.supported_images = [
            'image/jpeg', 'image/png', 'image/gif', 
            'image/bmp', 'image/tiff', 'image/webp'
        ]
    
    def is_safe_image(self, file_path):
        """Check if file is a safe image type."""
        try:
            mime = magic.Magic(mime=True)
            mime_type = mime.from_file(file_path)
            return mime_type in self.supported_images
        except:
            return False
    
    def process_safe_image(self, input_path, output_path, width=800, height=600):
        """Process image only if it's a safe type."""
        if not self.is_safe_image(input_path):
            print(f"Skipping {input_path}: Not a supported image type")
            return False
        
        try:
            with Image(filename=input_path) as img:
                img.resize(width, height)
                img.save(filename=output_path)
            return True
        except Exception as e:
            print(f"Error processing {input_path}: {e}")
            return False

# Usage
processor = SafeImageProcessor()
processor.process_safe_image('input.jpg', 'output.jpg')
```

### Batch File Classification

```python
import magic
import os
from collections import defaultdict

def classify_files_in_directory(directory_path):
    """Classify all files in a directory by type."""
    file_types = defaultdict(list)
    
    if not os.path.exists(directory_path):
        return file_types
    
    mime = magic.Magic(mime=True)
    
    for filename in os.listdir(directory_path):
        filepath = os.path.join(directory_path, filename)
        
        if os.path.isfile(filepath):
            try:
                mime_type = mime.from_file(filepath)
                file_types[mime_type].append(filename)
            except Exception as e:
                file_types['error'].append(f"{filename}: {e}")
    
    return dict(file_types)

# Usage
classification = classify_files_in_directory('./uploads')
for mime_type, files in classification.items():
    print(f"\n{mime_type}:")
    for file in files:
        print(f"  - {file}")
```

### Content-Based Security Validation

```python
import magic

def validate_upload_security(file_path, allowed_types=None):
    """Validate uploaded file based on actual content, not extension."""
    
    if allowed_types is None:
        allowed_types = [
            'image/jpeg', 'image/png', 'image/gif',
            'text/plain', 'application/pdf'
        ]
    
    try:
        # Check actual file content
        mime = magic.Magic(mime=True)
        actual_type = mime.from_file(file_path)
        
        # Get file description
        description = magic.from_file(file_path)
        
        validation_result = {
            'is_safe': actual_type in allowed_types,
            'detected_type': actual_type,
            'description': description,
            'filename': os.path.basename(file_path)
        }
        
        # Additional security checks
        if 'executable' in description.lower():
            validation_result['is_safe'] = False
            validation_result['warning'] = 'Executable file detected'
        
        if 'script' in description.lower():
            validation_result['is_safe'] = False
            validation_result['warning'] = 'Script file detected'
        
        return validation_result
        
    except Exception as e:
        return {
            'is_safe': False,
            'error': str(e),
            'filename': os.path.basename(file_path)
        }

# Usage
result = validate_upload_security('suspicious_file.jpg')
if result['is_safe']:
    print(f"File {result['filename']} is safe to process")
    # Proceed with ImageMagick processing
else:
    print(f"Security warning for {result['filename']}: {result.get('warning', 'Unknown issue')}")
```

## Summary

This comprehensive guide covers:

1. **Basic Image Operations**: Opening, saving, resizing
2. **Advanced Transformations**: Cropping, rotating, filters
3. **Color Manipulation**: Adjusting brightness/contrast, color space conversions
4. **Text and Watermarking**: Adding text overlays to images
5. **Batch Processing**: Automating operations on multiple images
6. **Pipelines and Automation**: Creating reusable image processing workflows
7. **File Type Detection**: Using python-magic for secure file validation

The examples demonstrate how to build robust image processing pipelines using both Wand and python-magic, from simple one-off operations to complex automated systems that can handle large volumes of images with proper security validation.

Key takeaways:

- Use Wand for high-quality image manipulation in Python
- Use python-magic for reliable file type detection and security validation
- Always validate file types before processing for security
- Implement proper error handling for file operations
- Structure code into reusable classes and functions
- Consider batch processing for efficiency
- Leverage configuration files for flexible workflows
- Combine ImageMagick processing with content-based file validation

This approach provides a solid foundation for building sophisticated and secure image processing applications with Python, Wand, and python-magic.
