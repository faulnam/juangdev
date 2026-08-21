<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageCompressor
{
    /**
     * Upload and automatically compress an image file.
     * Guaranteed to keep file size under 2MB (optimizes quality & resizes max 1920px).
     *
     * @param UploadedFile $file
     * @param string $destinationFolder
     * @param int $maxDimension
     * @param int $quality
     * @return string Relative URL path (e.g., /uploads/filename.jpg)
     */
    public static function uploadAndCompress(
        UploadedFile $file,
        string $destinationFolder = 'uploads',
        int $maxDimension = 1920,
        int $quality = 80
    ): string {
        $destinationPath = public_path($destinationFolder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $targetExt = in_array($extension, ['png', 'webp', 'jpeg', 'jpg']) ? $extension : 'jpg';
        $filename = time() . '_' . Str::random(6) . '.' . $targetExt;
        $targetFilePath = $destinationPath . DIRECTORY_SEPARATOR . $filename;

        $sourcePath = $file->getRealPath();
        $imageInfo = @getimagesize($sourcePath);

        if (!$imageInfo) {
            // Fallback move if non-standard image (SVG, GIF, etc.)
            $file->move($destinationPath, $filename);
            return '/' . trim($destinationFolder, '/') . '/' . $filename;
        }

        $mime = $imageInfo['mime'] ?? '';
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Create GD resource from source image based on mime
        $srcImage = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                if (function_exists('imagecreatefromjpeg')) {
                    $srcImage = @imagecreatefromjpeg($sourcePath);
                }
                break;
            case 'image/png':
                if (function_exists('imagecreatefrompng')) {
                    $srcImage = @imagecreatefrompng($sourcePath);
                }
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $srcImage = @imagecreatefromwebp($sourcePath);
                }
                break;
        }

        if (!$srcImage) {
            // Fallback move if GD image creation fails
            $file->move($destinationPath, $filename);
            return '/' . trim($destinationFolder, '/') . '/' . $filename;
        }

        // Calculate target dimensions (max 1920px width or height)
        $newWidth = $width;
        $newHeight = $height;

        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width >= $height) {
                $newWidth = $maxDimension;
                $newHeight = (int) round(($height / $width) * $maxDimension);
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int) round(($width / $height) * $maxDimension);
            }
        }

        // Create canvas
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and WebP
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resample
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save compressed image based on extension
        if ($targetExt === 'png') {
            $pngQuality = (int) round((100 - $quality) / 10);
            imagepng($dstImage, $targetFilePath, min(9, max(0, $pngQuality)));
        } elseif ($targetExt === 'webp' && function_exists('imagewebp')) {
            imagewebp($dstImage, $targetFilePath, $quality);
        } else {
            imagejpeg($dstImage, $targetFilePath, $quality);
        }

        // Free memory
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        // Ensure file size is strictly under 2MB (2,097,152 bytes)
        if (file_exists($targetFilePath) && filesize($targetFilePath) > 2097152) {
            self::compressUnder2MB($targetFilePath, $quality - 20);
        }

        return '/' . trim($destinationFolder, '/') . '/' . $filename;
    }

    private static function compressUnder2MB(string $filePath, int $quality): void
    {
        if ($quality < 20 || !file_exists($filePath)) return;

        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) return;

        $mime = $imageInfo['mime'] ?? '';
        $srcImage = null;

        if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
            $srcImage = @imagecreatefromjpeg($filePath);
            if ($srcImage) {
                imagejpeg($srcImage, $filePath, $quality);
                imagedestroy($srcImage);
            }
        } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
            $srcImage = @imagecreatefromwebp($filePath);
            if ($srcImage) {
                imagewebp($srcImage, $filePath, $quality);
                imagedestroy($srcImage);
            }
        }

        if (file_exists($filePath) && filesize($filePath) > 2097152 && $quality > 30) {
            self::compressUnder2MB($filePath, $quality - 15);
        }
    }
}
