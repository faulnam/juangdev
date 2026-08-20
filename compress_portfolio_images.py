import os
import sys
from PIL import Image

TARGET_DIR = os.path.join(os.path.dirname(__file__), "public", "uploads")
MAX_SIZE_BYTES = 1.8 * 1024 * 1024  # 1.8 MB target (safety buffer under 2MB)
MAX_DIMENSION = 1920  # Max width or height in pixels

def get_size_mb(path):
    return os.path.getsize(path) / (1024 * 1024)

def compress_image(filepath):
    initial_size_mb = get_size_mb(filepath)
    if initial_size_mb <= 1.8:
        print(f"Skipping {os.path.basename(filepath)}: already {initial_size_mb:.2f} MB")
        return False

    try:
        with Image.open(filepath) as img:
            ext = os.path.splitext(filepath)[1].lower()
            orig_format = img.format or "PNG"
            width, height = img.size

            # Resize if dimensions are larger than MAX_DIMENSION
            if max(width, height) > MAX_DIMENSION:
                ratio = MAX_DIMENSION / float(max(width, height))
                new_width = int(width * ratio)
                new_height = int(height * ratio)
                img = img.resize((new_width, new_height), Image.Resampling.LANCZOS)

            # Compress depending on format
            if ext in ['.jpg', '.jpeg']:
                if img.mode in ('RGBA', 'P'):
                    img = img.convert('RGB')
                quality = 85
                img.save(filepath, format='JPEG', optimize=True, quality=quality)
                while get_size_mb(filepath) > 1.8 and quality > 30:
                    quality -= 10
                    img.save(filepath, format='JPEG', optimize=True, quality=quality)
            elif ext == '.png':
                # Convert to WebP or optimize PNG
                if img.mode == 'RGBA':
                    # Save optimized PNG with quantize/palette or resize
                    temp_img = img.quantize(colors=256, method=2)
                    temp_img.save(filepath, format='PNG', optimize=True)
                else:
                    img = img.convert('RGB')
                    img.save(filepath, format='JPEG', optimize=True, quality=85)
                    while get_size_mb(filepath) > 1.8 and quality > 30:
                        quality -= 10
                        img.save(filepath, format='JPEG', optimize=True, quality=quality)
            else:
                img.save(filepath, optimize=True)

        final_size_mb = get_size_mb(filepath)
        print(f"Compressed {os.path.basename(filepath)}: {initial_size_mb:.2f} MB -> {final_size_mb:.2f} MB")
        return True
    except Exception as e:
        print(f"Error compressing {os.path.basename(filepath)}: {e}")
        return False

def main():
    if not os.path.exists(TARGET_DIR):
        print(f"Directory non-existent: {TARGET_DIR}")
        return

    print("==================================================")
    print("   JUANGDEV PORTFOLIO IMAGE COMPRESSOR (Python)   ")
    print(f"   Target Dir: {TARGET_DIR}")
    print(f"   Max Size Target: < 2.0 MB")
    print("==================================================\n")

    files = [f for f in os.listdir(TARGET_DIR) if f.lower().endswith(('.png', '.jpg', '.jpeg', '.webp'))]
    if not files:
        print("No image files found in uploads directory.")
        return

    compressed_count = 0
    for filename in files:
        filepath = os.path.join(TARGET_DIR, filename)
        if compress_image(filepath):
            compressed_count += 1

    print("\n==================================================")
    print(f"Finished! Compressed {compressed_count} file(s). All images are now under 2 MB.")
    print("==================================================")

if __name__ == "__main__":
    main()
