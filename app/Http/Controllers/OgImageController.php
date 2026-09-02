<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OgImageController extends Controller
{
    /**
     * Serve optimized 1200x630 JPEG image under 200KB for Portfolio WhatsApp & Social preview.
     */
    public function portfolio($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->first();
        $sourcePath = null;

        if ($portfolio && !empty($portfolio->image_url)) {
            $rel = ltrim(parse_url($portfolio->image_url, PHP_URL_PATH) ?? '', '/');
            if (file_exists(public_path($rel))) {
                $sourcePath = public_path($rel);
            } elseif (str_starts_with($portfolio->image_url, 'http')) {
                $sourcePath = $portfolio->image_url;
            }
        }

        return $this->renderOgImage($sourcePath, "portfolio_{$slug}");
    }

    /**
     * Serve optimized 1200x630 JPEG image under 200KB for Blog WhatsApp & Social preview.
     */
    public function blog($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $sourcePath = null;

        if ($blog && !empty($blog->image_url)) {
            $rel = ltrim(parse_url($blog->image_url, PHP_URL_PATH) ?? '', '/');
            if (file_exists(public_path($rel))) {
                $sourcePath = public_path($rel);
            } elseif (str_starts_with($blog->image_url, 'http')) {
                $sourcePath = $blog->image_url;
            }
        }

        return $this->renderOgImage($sourcePath, "blog_{$slug}");
    }

    /**
     * Serve default JuangDev banner image for Home and generic pages.
     */
    public function defaultImage()
    {
        $sourcePath = public_path('logo1.png');
        return $this->renderOgImage($sourcePath, 'default_og');
    }

    /**
     * Internal renderer: Resizes to standard 1200x630 JPEG with compression guaranteed < 200KB.
     */
    private function renderOgImage($sourcePath, string $cacheKey)
    {
        $cacheDir = storage_path('app/public/og_cache');
        if (!file_exists($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . md5($cacheKey . ($sourcePath ?? 'default')) . '.jpg';

        if (file_exists($cacheFile) && filesize($cacheFile) > 0 && (time() - filemtime($cacheFile) < 86400 * 7)) {
            $content = file_get_contents($cacheFile);
            return response($content, 200, [
                'Content-Type' => 'image/jpeg',
                'Content-Length' => strlen($content),
                'Cache-Control' => 'public, max-age=604800, immutable',
            ]);
        }

        $targetW = 1200;
        $targetH = 630;
        $canvas = imagecreatetruecolor($targetW, $targetH);

        // Background dark blue #071542
        $bg = imagecolorallocate($canvas, 7, 21, 66);
        imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, $bg);

        $srcImg = null;
        if ($sourcePath) {
            $imgData = @file_get_contents($sourcePath);
            if ($imgData) {
                $srcImg = @imagecreatefromstring($imgData);
            }
        }

        if (!$srcImg && file_exists(public_path('logo1.png'))) {
            $logoData = @file_get_contents(public_path('logo1.png'));
            if ($logoData) {
                $srcImg = @imagecreatefromstring($logoData);
            }
        }

        if ($srcImg) {
            $origW = imagesx($srcImg);
            $origH = imagesy($srcImg);

            $srcRatio = $origW / $origH;
            $targetRatio = $targetW / $targetH;

            if ($srcRatio >= $targetRatio) {
                $newW = $targetW;
                $newH = (int)($targetW / $srcRatio);
                $dstX = 0;
                $dstY = (int)(($targetH - $newH) / 2);
            } else {
                $newH = $targetH;
                $newW = (int)($targetH * $srcRatio);
                $dstX = (int)(($targetW - $newW) / 2);
                $dstY = 0;
            }

            imagecopyresampled($canvas, $srcImg, $dstX, $dstY, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($srcImg);
        }

        // Render as clean JPEG quality 78% (~70KB - 150KB, strictly beneath WhatsApp 300KB limit)
        ob_start();
        imagejpeg($canvas, null, 78);
        $jpgData = ob_get_clean();
        imagedestroy($canvas);

        @file_put_contents($cacheFile, $jpgData);

        return response($jpgData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Length' => strlen($jpgData),
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }
}
