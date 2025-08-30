## Table of Contents

1. [Installation](#installation)
2. [Basic Commands](#basic-commands)
3. [Image Information](#image-information)
4. [Resizing and Scaling](#resizing-and-scaling)
5. [Cropping and Cropping Tools](#cropping-and-cropping-tools)
6. [Color Manipulation](#color-manipulation)
7. [Filters and Effects](#filters-and-effects)
8. [Text Overlay](#text-overlay)
9. [Batch Processing](#batch-processing)
10. [Advanced Techniques](#advanced-techniques)

## Installation

### Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install imagemagick
```

### Linux (CentOS/RHEL/Fedora)
```bash
sudo yum install ImageMagick
# or for newer versions:
sudo dnf install ImageMagick
```

### macOS
```bash
brew install imagemagick
```

### Windows
Download from: https://imagemagick.org/script/download.php

## Basic Commands

### Convert Images Format
```bash
# Convert JPG to PNG
convert input.jpg output.png

# Convert PNG to GIF
convert input.png output.gif

# Batch conversion (all files in directory)
convert *.jpg *.png output_%03d.png
```

### Image Properties and Information
```bash
# Get image information
identify input.jpg

# Get detailed info with size, format, color depth
identify -verbose input.jpg

# Get only dimensions
identify -format "%w x %h\n" input.jpg

# Convert to different formats with quality control
convert input.png -quality 90 output.jpg
```

## Image Information

### Basic Information Commands
```bash
# Display image properties
identify input.jpg

# Get specific information (width, height, format)
identify -format "Width: %w Height: %h Format: %m\n" input.jpg

# Show color depth and type
identify -verbose input.png | grep -E "(Depth|Type)"

# Check if file is valid image
file input.jpg

# Get image signature for verification
identify -format "%#" input.jpg
```

### Advanced Information Gathering
```bash
# Get detailed metadata (EXIF data)
identify -verbose input.jpg | grep -i exif

# Show histogram of color channels
histogram:input.jpg

# Check if images are identical
compare -metric AE input1.jpg input2.jpg null: 2>&1 | tail -n 1

# Compare two images and show differences
compare input1.png input2.png difference.png
```

## Resizing and Scaling

### Basic Resize Operations
```bash
# Resize to specific dimensions (maintains aspect ratio)
convert input.jpg -resize 800x600 output.jpg

# Scale proportionally to fit within bounds (max width/height)
convert input.jpg -resize 1920x1080\> output.jpg

# Scale down while maintaining aspect ratio
convert input.jpg -resize 50% output.jpg

# Force exact dimensions (may distort image)
convert input.jpg -resize 800x600! output.jpg

# Resize with maximum width constraint
convert input.jpg -resize 1200x\> output.jpg

# Resize to fit within specified area
convert input.jpg -resize 1920x1080^ output.jpg
```

### Advanced Scaling Techniques
```bash
# Upscale image using different methods
convert input.jpg -resize 200% output_upscaled.jpg

# Scale with specific interpolation method (lanczos is good for sharpening)
convert input.jpg -resize 800x600 -interpolate Lanczos output.jpg

# Fit within bounds, but never shrink smaller than original
convert input.jpg -resize 1920x1080\> output.jpg

# Resize to exact dimensions with aspect ratio preservation
convert input.jpg -resize 800x600^ -gravity center -extent 800x600 output.jpg

# Create thumbnail (small preview)
convert input.jpg -resize 150x150\> thumb.jpg

# Scale with percentage preserving aspect ratio
convert input.jpg -resize 75% output.jpg
```

## Cropping and Cropping Tools

### Basic Cropping
```bash
# Crop specific dimensions from top-left corner
convert input.jpg -crop 800x600+100+50 cropped.jpg

# Crop with percentages (from center)
convert input.jpg -crop 50%x50%+0+0 cropped_center.jpg

# Crop to exact width and height (centered)
convert input.jpg -resize 800x600^ -gravity center -crop 800x600+0+0 cropped_exact.jpg

# Crop using gravity
convert input.jpg -crop 400x300+150+150 cropped_gravity.jpg

# Center crop to exact dimensions
convert input.jpg -resize 800x600^ -gravity center -crop 800x600+0+0 centered_crop.jpg
```

### Advanced Cropping Techniques
```bash
# Crop with offset from edges
convert input.jpg -crop 450x350-25-25 cropped_offset.jpg

# Crop to specific ratio (16:9)
convert input.jpg -resize 1920x1080^ -gravity center -crop 1920x1080+0+0 output.jpg

# Create multiple crops from one image
convert input.jpg -crop 450x350 +repage crop_%d.jpg

# Crop using percent-based coordinates
convert input.jpg -crop 25%x25%+12.5%+12.5% cropped_percent.jpg

# Smart cropping (auto-crop)
convert input.jpg -trim output_trimmed.jpg

# Remove whitespace and crop to content area
convert input.png -trim +repage output_trimmed.png
```

## Color Manipulation

### Basic Color Adjustments
```bash
# Change brightness and contrast
convert input.jpg -brightness-contrast 20x15 output_bright.jpg

# Adjust gamma (darken/lighten)
convert input.jpg -gamma 1.5 output_gamma.jpg

# Increase saturation
convert input.jpg -modulate 100,150,100 output_saturated.jpg

# Desaturate image (grayscale)
convert input.jpg -colorspace Gray output_gray.jpg

# Convert to sepia tone
convert input.jpg -sepia-tone 80% output_sepia.jpg

# Adjust hue (rotate color wheel)
convert input.jpg -modulate 120,100,100 output_hue_shifted.jpg
```

### Advanced Color Operations
```bash
# Change image colorspace
convert input.jpg -colorspace RGB output_rgb.jpg
convert input.jpg -colorspace CMYK output_cmyk.jpg

# Invert colors (negative)
convert input.jpg -negate output_negative.jpg

# Apply color tinting with transparency
convert input.png \( +clone -fill red -tint 100 \) -composite output_tinted.jpg

# Adjust individual channels
convert input.jpg -channel R -separate channel_red.png

# Combine separated channels back
convert channel_red.png channel_green.png channel_blue.png +append combined_channels.jpg

# Convert to black and white with specific weights
convert input.jpg -colorspace Gray -define histogram:unique-colors=false output_bw_weighted.jpg

# Adjust color balance (red, green, blue)
convert input.jpg -channel R -level 10%,90% output_red_adjusted.jpg
```

## Filters and Effects

### Blur and Sharpen Operations
```bash
# Apply blur effect
convert input.jpg -blur 5x5 blurred.jpg

# Apply Gaussian blur with specific radius
convert input.jpg -define gaussian:sigma=3 gaussian_blur.jpg

# Sharpen image using unsharp masking
convert input.jpg -unsharp 0x1+1.5+0.05 sharpened.jpg

# Create motion blur effect
convert input.jpg -motion-blur 10x5+45 output_motion_blur.jpg

# Add noise to simulate film grain
convert input.jpg -noise 2 output_noise.jpg

# Apply edge detection (canny edge)
convert input.jpg -define morphology:iterations=1 \
-morphology EdgeIn Euclidean output_edges.jpg
```

### Special Effects and Filters
```bash
# Add shadow effect to image
convert input.png \( +clone -background black -shadow 80x5+5+5 \) +swap -background none -layers merge output_shadow.png

# Apply emboss effect
convert input.jpg -define convolve:scale=100% \
-convolve "2, 1, 0; 1, 1, -1; 0, -1, -2" embossed.jpg

# Create vignette (darken edges)
convert input.jpg \( +clone -fill black -tint 50 \) \
-overlay \( +clone -background black \
-size 64x64 radial-gradient: \) \
-composite output_vignette.jpg

# Add border to image
convert input.jpg -border 10 -bordercolor white output_bordered.jpg

# Apply swirl effect (rotational distortion)
convert input.jpg -swirl 30 output_swirl.jpg

# Create pixelation effect
convert input.jpg -scale 25% -scale 400% output_pixelated.jpg

# Add crosshatch pattern to image
convert input.png \( +clone \
-size 16x16 pattern:crosshatch \) \
-compose multiply -composite output_crosshatch.jpg
```

## Text and Overlay Operations

### Adding Text to Images
```bash
# Add simple text overlay
convert input.jpg -pointsize 24 -fill white -stroke black \
-stroke-width 1 -gravity center \
-annotate 0 "Hello World" output_text.jpg

# Add multi-line text with custom font
convert input.jpg -font Arial -pointsize 20 \
-fill white -stroke black -strokewidth 2 \
-gravity southwest \
-annotate +10+10 "Line 1\nLine 2\nLine 3" output_multiline_text.jpg

# Add text with background box
convert input.jpg \( label:"Hello World" -background rgba\(0,0,0,50%\)
-size 200x50 -gravity center \
-compose over -composite \) \
-compose over -composite output_text_box.jpg

# Rotate and add text
convert input.jpg -pointsize 36 \
-fill yellow -stroke black -strokewidth 1 \
-gravity center \
-annotate +180 "Rotated Text" output_rotated_text.jpg

# Add transparent text overlay with shadow
convert input.jpg \( label:"Sample Text" \
-background rgba\(0,0,0,50%\)
-size 300x60 -gravity center \
-compose over -composite \) \
-compose over -composite output_transparent_text.jpg
```

### Image Overlays and Compositing
```bash
# Overlay one image on another (simple merge)
convert input1.jpg input2.png -compose over -composite output_overlay.jpg

# Add watermark to image with transparency
convert input.jpg watermark.png \
-composite -alpha set output_watermark.jpg

# Create overlay with different blend modes
convert input1.jpg input2.png \
-compose multiply -composite output_multiply.jpg

# Overlay with offset position
convert input1.jpg \( +clone -fill red -tint 50 \) \
-compose over -geometry +10+10 -composite output_offset.jpg

# Add logo or icon to image
convert input.jpg logo.png \
-composite -gravity southeast output_logo_added.jpg

# Create a composite with multiple overlays
convert input1.jpg input2.png input3.png \
-compose over -layers merge output_composite.jpg
```

## Batch Processing Examples

### Simple Batch Operations
```bash
# Resize all jpg files in directory to 800x600
for file in *.jpg; do
    convert "$file" -resize 800x600 "resized_$file"
done

# Convert all images to grayscale and save with new extension
for file in *.{jpg,png,gif}; do
    convert "$file" -colorspace Gray "${file%.*}.bw"
done

# Apply watermark to all images
for file in *.jpg; do
    convert "$file" watermark.png \
    -composite "watermarked_$file"
done

# Create thumbnails from all images
for file in *.{jpg,png,gif}; do
    convert "$file" -thumbnail 100x100 "thumb_${file%.*}.jpg"
done

# Batch crop images to specific ratio (4:3)
for file in *.jpg; do
    convert "$file" -resize 800x600^ \
    -gravity center -crop 800x600+0+0 "cropped_${file%.*}.jpg"
done

# Batch adjust brightness and contrast
for file in *.jpg; do
    convert "$file" -brightness-contrast 15x15 \
    "brightened_${file%.*}.jpg"
done
```

### Advanced Batch Processing
```bash
#!/bin/bash
# Advanced batch processing script

mkdir -p processed_images

for image in *.jpg; do
    base_name="${image%.*}"
    
    # Create multiple variations of each image
    convert "$image" -resize 1200x800^ \
        -gravity center -crop 1200x800+0+0 \
        "processed_images/${base_name}_large.jpg"
        
    convert "$image" -resize 640x480 \
        "processed_images/${base_name}_medium.jpg"
        
    convert "$image" -resize 320x240 \
        "processed_images/${base_name}_small.jpg"
        
    # Add watermark to large image
    convert "processed_images/${base_name}_large.jpg" watermark.png \
        -composite "processed_images/${base_name}_watermarked.jpg"
done

echo "Batch processing complete!"
```

## Complete Examples

### Example 1: Image Preprocessing Pipeline
```bash
#!/bin/bash
# Image preprocessing pipeline script

input_dir="$1"
output_dir="$2"

if [ ! -d "$input_dir" ]; then
    echo "Input directory does not exist!"
    exit 1
fi

mkdir -p "$output_dir"

for file in "$input_dir"/*.{jpg,jpeg,png,gif}; do
    if [ -f "$file" ]; then
        filename=$(basename "$file")
        base_name="${filename%.*}"
        
        # Step 1: Resize to standard dimensions
        convert "$file" -resize 1920x1080^ \
            -gravity center -crop 1920x1080+0+0 \
            "$output_dir/${base_name}_standard.jpg"
            
        # Step 2: Apply sharpening and contrast enhancement
        convert "$output_dir/${base_name}_standard.jpg" \
            -sharpen 0x1 \
            -contrast-stretch 0 \
            "$output_dir/${base_name}_enhanced.jpg"
            
        # Step 3: Create thumbnail
        convert "$output_dir/${base_name}_standard.jpg" \
            -thumbnail 200x200 \
            "$output_dir/thumb_${base_name}.jpg"
    fi
done

echo "Preprocessing complete!"
```

### Example 2: Image Collage Creation
```bash
#!/bin/bash
# Create image collage from multiple images

images=("$@")
output_file="collage.jpg"

# Calculate canvas size based on number of images
num_images=${#images[@]}
canvas_width=$((300 * 2))  # 2 columns, 300px wide each
canvas_height=$((300 * (num_images / 2 + num_images % 2))) 

# Create blank canvas and composite images
convert -size ${canvas_width}x${canvas_height} xc:white \
    \( "${images[0]}" -resize 300x300 \) \
    -geometry +15+15 -composite \
    "$output_file"
    
echo "Collage created: $output_file"
```

### Example 3: Image Quality Enhancement
```bash
#!/bin/bash
# Advanced image quality enhancement script

enhance_image() {
    local input="$1"
    local output="$2"
    
    convert "$input" \
        -auto-level \
        -normalize \
        -unsharp 0x1+1.5+0.05 \
        -contrast-stretch 0 \
        "$output"
}

# Process all images in current directory
for file in *.{jpg,png,gif}; do
    if [ -f "$file" ]; then
        enhance_image "$file" "enhanced_${file}"
    fi
done

echo "Image enhancement complete!"
```

These examples demonstrate various ImageMagick operations including image manipulation, text overlay, batch processing, and workflow automation. Each example shows practical applications that can be adapted for different use cases.

```bash
# Simple command to resize an image
convert input.jpg -resize 800x600 output.jpg

# Command to add text to an image
convert input.jpg -pointsize 24 -fill white \
    -gravity center -annotate 0 "Hello World" \
    output.jpg

# Command to create a thumbnail
convert input.jpg -thumbnail 100x100 thumb.jpg

# Command to convert format
convert input.png output.jpg

# Command to combine images into collage
montage image1.jpg image2.jpg image3.jpg -geometry +4+4 collage.jpg
```

This comprehensive guide covers basic and advanced ImageMagick operations, from simple transformations to complex batch processing and automation tasks. The examples provide practical starting points for implementing these workflows in real-world scenarios.
```

