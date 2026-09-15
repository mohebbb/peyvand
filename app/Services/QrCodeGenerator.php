<?php

namespace App\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGenerator
{
    /**
     * The disk & directory used to store uploaded logo images.
     * Configurable via config/qr-codes.php; the private "local" disk
     * suits single-server deployments.
     */
    public static function logoDisk(): string
    {
        return (string) config('qr-codes.disk', 'local');
    }

    public static function logoDirectory(): string
    {
        return (string) config('qr-codes.directory', 'qr-logos');
    }

    /**
     * @var list<string>
     */
    public const FORMATS = ['svg', 'png', 'jpg'];

    /**
     * @var list<string>
     */
    public const ERROR_CORRECTIONS = ['L', 'M', 'Q', 'H'];

    private const DEFAULTS = [
        'format' => 'svg',
        'size' => 300,
        'margin' => 2,
        'error_correction' => 'H',
        'foreground_color' => '#000000',
        'background_color' => '#ffffff',
        'transparent_background' => false,
        'logo_enabled' => false,
        'logo_path' => null,
        'logo_size_percent' => 20,
    ];

    /**
     * Generates a downloadable QR code for the given text.
     *
     * @param  array<string, mixed>  $settings
     */
    public function generate(string $text, array $settings): GeneratedQrCode
    {
        $settings = $this->normalize($settings);

        return match ($settings['format']) {
            'png' => $this->generateRaster($text, $settings, 'png'),
            'jpg' => $this->generateRaster($text, $settings, 'jpg'),
            default => $this->generateSvg($text, $settings),
        };
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    private function normalize(array $settings): array
    {
        $format = strtolower((string) ($settings['format'] ?? ''));

        if (! in_array($format, self::FORMATS, true)) {
            $format = 'svg';
        }

        $errorCorrection = strtoupper((string) ($settings['error_correction'] ?? ''));

        if (! in_array($errorCorrection, self::ERROR_CORRECTIONS, true)) {
            $errorCorrection = 'H';
        }

        return [
            ...self::DEFAULTS,
            ...$settings,
            'format' => $format,
            'size' => max(100, min(1000, (int) ($settings['size'] ?? self::DEFAULTS['size']))),
            'margin' => max(0, min(10, (int) ($settings['margin'] ?? self::DEFAULTS['margin']))),
            'error_correction' => $errorCorrection,
            'transparent_background' => (bool) ($settings['transparent_background'] ?? false),
            'logo_enabled' => (bool) ($settings['logo_enabled'] ?? false),
            'logo_path' => filled($settings['logo_path'] ?? null) ? (string) $settings['logo_path'] : null,
            'logo_size_percent' => max(10, min(30, (int) ($settings['logo_size_percent'] ?? self::DEFAULTS['logo_size_percent']))),
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function generateSvg(string $text, array $settings): GeneratedQrCode
    {
        [$red, $green, $blue] = $this->hexToRgb($settings['foreground_color']);
        [$bgRed, $bgGreen, $bgBlue] = $this->hexToRgb($settings['background_color']);

        $qrCode = QrCode::format('svg')
            ->size($settings['size'])
            ->margin($settings['margin'])
            ->errorCorrection($settings['error_correction'])
            ->color($red, $green, $blue);

        if ($settings['transparent_background']) {
            // An alpha of 0 renders the background rectangle with zero opacity.
            $qrCode->backgroundColor($bgRed, $bgGreen, $bgBlue, 0);
        } else {
            $qrCode->backgroundColor($bgRed, $bgGreen, $bgBlue);
        }

        $svg = (string) $qrCode->generate($text);

        if ($settings['logo_enabled']) {
            $svg = $this->embedSvgLogo($svg, $settings);
        }

        return new GeneratedQrCode($svg, 'image/svg+xml', 'svg');
    }

    /**
     * Renders PNG / JPG using GD (the Imagick backend of simple-qrcode is
     * unavailable on this environment).
     *
     * @param  array<string, mixed>  $settings
     */
    private function generateRaster(string $text, array $settings, string $format): GeneratedQrCode
    {
        [$red, $green, $blue] = $this->hexToRgb($settings['foreground_color']);
        $transparent = $settings['transparent_background'] && $format === 'png';

        $matrix = Encoder::encode($text, ErrorCorrectionLevel::{$settings['error_correction']}())->getMatrix();
        $count = $matrix->getWidth();
        $total = $count + (2 * $settings['margin']);
        $size = $settings['size'];
        $scale = (int) ceil($size / $total);
        $big = $total * $scale;

        $image = imagecreatetruecolor($big, $big);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $background = $transparent
            ? imagecolorallocatealpha($image, 0, 0, 0, 127)
            : imagecolorallocate($image, ...$this->hexToRgb($settings['background_color']));
        imagefilledrectangle($image, 0, 0, $big - 1, $big - 1, $background);

        imagealphablending($image, true);
        $foreground = imagecolorallocate($image, $red, $green, $blue);

        $rows = $matrix->getArray();
        $margin = $settings['margin'];

        for ($y = 0; $y < $count; $y++) {
            $row = $rows[$y];

            for ($x = 0; $x < $count; $x++) {
                if ($row[$x] !== 1) {
                    continue;
                }

                imagefilledrectangle(
                    $image,
                    ($x + $margin) * $scale,
                    ($y + $margin) * $scale,
                    (($x + $margin + 1) * $scale) - 1,
                    (($y + $margin + 1) * $scale) - 1,
                    $foreground,
                );
            }
        }

        if ($settings['logo_enabled']) {
            $this->pasteRasterLogo($image, $big, $settings);
        }

        if ($big !== $size) {
            $resized = imagecreatetruecolor($size, $size);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $size, $size, $big, $big);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();

        if ($format === 'png') {
            imagepng($image);
        } else {
            imagejpeg($image, null, 90);
        }

        $content = (string) ob_get_clean();
        imagedestroy($image);

        return new GeneratedQrCode(
            content: $content,
            contentType: $format === 'png' ? 'image/png' : 'image/jpeg',
            extension: $format,
        );
    }

    /**
     * Centers a white plate with the logo on top of the raster image.
     *
     * @param  array<string, mixed>  $settings
     */
    private function pasteRasterLogo(\GdImage $image, int $big, array $settings): void
    {
        $bytes = $this->logoBytes($settings['logo_path']);

        if ($bytes === null) {
            return;
        }

        $logo = @imagecreatefromstring($bytes);

        if ($logo === false) {
            return;
        }

        $box = (int) round($big * $settings['logo_size_percent'] / 100);
        $plate = min((int) ($big * 0.9), (int) round($box * 1.15));
        $center = (int) ($big / 2);
        $halfPlate = (int) ($plate / 2);

        imagefilledrectangle(
            $image,
            $center - $halfPlate,
            $center - $halfPlate,
            $center + $halfPlate,
            $center + $halfPlate,
            imagecolorallocate($image, 255, 255, 255),
        );

        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);
        $ratio = min($box / $logoWidth, $box / $logoHeight);
        $targetWidth = max(1, (int) round($logoWidth * $ratio));
        $targetHeight = max(1, (int) round($logoHeight * $ratio));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefilledrectangle($canvas, 0, 0, $targetWidth - 1, $targetHeight - 1, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $logo, 0, 0, 0, 0, $targetWidth, $targetHeight, $logoWidth, $logoHeight);

        imagealphablending($image, true);
        imagecopy(
            $image,
            $canvas,
            $center - (int) ($targetWidth / 2),
            $center - (int) ($targetHeight / 2),
            0,
            0,
            $targetWidth,
            $targetHeight,
        );

        imagedestroy($canvas);
        imagedestroy($logo);
    }

    /**
     * Embeds the logo as a data URI in the center of the SVG output.
     *
     * @param  array<string, mixed>  $settings
     */
    private function embedSvgLogo(string $svg, array $settings): string
    {
        $bytes = $this->logoBytes($settings['logo_path']);

        if ($bytes === null) {
            return $svg;
        }

        $logo = @imagecreatefromstring($bytes);

        if ($logo === false) {
            return $svg;
        }

        $mime = getimagesizefromstring($bytes)['mime'] ?? 'image/png';
        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);
        imagedestroy($logo);

        $size = $settings['size'];
        $box = $size * $settings['logo_size_percent'] / 100;
        $plate = min($size * 0.9, $box * 1.15);

        $ratio = min($box / $logoWidth, $box / $logoHeight);
        $width = round($logoWidth * $ratio, 2);
        $height = round($logoHeight * $ratio, 2);

        $plateX = round(($size - $plate) / 2, 2);
        $plateY = $plateX;
        $logoX = round(($size - $width) / 2, 2);
        $logoY = round(($size - $height) / 2, 2);
        $base64 = base64_encode($bytes);
        $rx = round($plate * 0.14, 2);

        $elements = sprintf(
            '<rect x="%s" y="%s" width="%s" height="%s" rx="%s" fill="#ffffff"/>'
                .'<image href="data:%s;base64,%s" x="%s" y="%s" width="%s" height="%s" preserveAspectRatio="xMidYMid meet" xlink:href="data:%s;base64,%s"/>',
            $plateX,
            $plateY,
            $plate,
            $plate,
            $rx,
            $mime,
            $base64,
            $logoX,
            $logoY,
            $width,
            $height,
            $mime,
            $base64,
        );

        $svg = str_replace(
            '<svg xmlns="http://www.w3.org/2000/svg"',
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"',
            $svg,
        );

        return str_replace('</svg>', $elements.'</svg>', $svg);
    }

    private function logoBytes(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk(self::logoDisk());

        if (! $disk->exists($path)) {
            return null;
        }

        return $disk->get($path);
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return [0, 0, 0];
        }

        return [
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        ];
    }
}
