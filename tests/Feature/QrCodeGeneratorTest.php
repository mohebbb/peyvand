<?php

namespace Tests\Feature;

use App\Models\QrCodeSetting;
use App\Models\ShortLink;
use App\Services\GeneratedQrCode;
use App\Services\QrCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_settings_singleton_is_created_with_sane_defaults(): void
    {
        $settings = QrCodeSetting::current();

        $this->assertSame('svg', $settings->format);
        $this->assertSame(300, $settings->size);
        $this->assertSame(2, $settings->margin);
        $this->assertSame('H', $settings->error_correction);
        $this->assertSame('#000000', $settings->foreground_color);
        $this->assertSame('#ffffff', $settings->background_color);
        $this->assertFalse($settings->transparent_background);
        $this->assertFalse($settings->logo_enabled);
        $this->assertSame(20, $settings->logo_size_percent);
    }

    public function test_current_always_returns_the_same_row(): void
    {
        QrCodeSetting::current()->update(['size' => 500]);

        $this->assertSame(500, QrCodeSetting::current()->size);
        $this->assertSame(1, QrCodeSetting::count());
    }

    public function test_it_generates_an_svg_with_custom_colors(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'svg',
            'size' => 250,
            'foreground_color' => '#ff0000',
            'background_color' => '#00ff00',
        ]);

        $this->assertInstanceOf(GeneratedQrCode::class, $qr);
        $this->assertSame('svg', $qr->extension);
        $this->assertSame('image/svg+xml', $qr->contentType);
        $this->assertStringContainsString('<svg xmlns="http://www.w3.org/2000/svg"', $qr->content);
        $this->assertStringContainsString('viewBox="0 0 250 250"', $qr->content);
        $this->assertStringContainsString('fill="#ff0000"', $qr->content);
        $this->assertStringContainsString('fill="#00ff00"', $qr->content);
    }

    public function test_it_generates_a_transparent_svg_background(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'svg',
            'transparent_background' => true,
        ]);

        // With alpha 0 the background rectangle is omitted entirely.
        $this->assertStringNotContainsString('fill="#ffffff"', $qr->content);
    }

    public function test_it_generates_a_png_with_the_requested_size_and_background(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'png',
            'size' => 500,
            'background_color' => '#ff8800',
        ]);

        $this->assertSame('png', $qr->extension);
        $this->assertSame('image/png', $qr->contentType);
        $this->assertSame("\x89PNG", substr($qr->content, 0, 4));

        $image = imagecreatefromstring($qr->content);

        $this->assertNotFalse($image);
        $this->assertSame(500, imagesx($image));
        $this->assertSame(500, imagesy($image));

        // The quiet zone corner must carry the background color.
        $corner = imagecolorat($image, 3, 3);
        $this->assertSame(0xFF, ($corner >> 16) & 0xFF);
        $this->assertSame(0x88, ($corner >> 8) & 0xFF);
        $this->assertSame(0x00, $corner & 0xFF);

        imagedestroy($image);
    }

    public function test_it_generates_a_transparent_png(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'png',
            'transparent_background' => true,
        ]);

        $image = imagecreatefromstring($qr->content);
        $this->assertNotFalse($image);

        $corner = imagecolorat($image, 2, 2);
        $this->assertSame(127, ($corner >> 24) & 0xFF);

        imagedestroy($image);
    }

    public function test_it_generates_a_jpg(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'jpg',
            'size' => 400,
        ]);

        $this->assertSame('jpg', $qr->extension);
        $this->assertSame('image/jpeg', $qr->contentType);
        $this->assertSame("\xFF\xD8", substr($qr->content, 0, 2));

        $image = imagecreatefromstring($qr->content);
        $this->assertNotFalse($image);
        $this->assertSame(400, imagesx($image));
        imagedestroy($image);
    }

    public function test_it_pastes_a_logo_into_png_and_svg(): void
    {
        Storage::fake(QrCodeGenerator::logoDisk());

        // Build a simple red 40x40 PNG to use as a logo.
        $logo = imagecreatetruecolor(40, 40);
        imagefilledrectangle($logo, 0, 0, 39, 39, imagecolorallocate($logo, 255, 0, 0));
        ob_start();
        imagepng($logo);
        $logoBytes = (string) ob_get_clean();
        imagedestroy($logo);

        Storage::disk(QrCodeGenerator::logoDisk())->put('qr-logos/test-logo.png', $logoBytes);

        $settings = [
            'format' => 'png',
            'size' => 300,
            'logo_enabled' => true,
            'logo_path' => 'qr-logos/test-logo.png',
            'logo_size_percent' => 25,
        ];

        $png = app(QrCodeGenerator::class)->generate('https://example.com/abc', $settings);
        $image = imagecreatefromstring($png->content);
        $this->assertNotFalse($image);

        // The exact center of the QR code must be covered by the logo (red).
        $center = imagecolorat($image, 150, 150);
        $this->assertSame(255, ($center >> 16) & 0xFF);
        $this->assertSame(0, ($center >> 8) & 0xFF);
        $this->assertSame(0, $center & 0xFF);
        imagedestroy($image);

        $svg = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            ...$settings,
            'format' => 'svg',
        ]);

        $this->assertStringContainsString('xmlns:xlink="http://www.w3.org/1999/xlink"', $svg->content);
        $this->assertStringContainsString('<rect x="106.88" y="106.88"', $svg->content);
        $this->assertStringContainsString('data:image/png;base64,', $svg->content);
    }

    public function test_a_missing_logo_file_is_ignored(): void
    {
        $png = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'png',
            'logo_enabled' => true,
            'logo_path' => 'qr-logos/does-not-exist.png',
        ]);

        $this->assertSame("\x89PNG", substr($png->content, 0, 4));
    }

    public function test_settings_out_of_range_are_clamped(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'png',
            'size' => 9999,
            'margin' => 100,
            'logo_size_percent' => 90,
        ]);

        $image = imagecreatefromstring($qr->content);
        $this->assertNotFalse($image);
        $this->assertSame(1000, imagesx($image));
        imagedestroy($image);
    }

    public function test_unknown_format_falls_back_to_svg(): void
    {
        $qr = app(QrCodeGenerator::class)->generate('https://example.com/abc', [
            'format' => 'bmp',
        ]);

        $this->assertSame('svg', $qr->extension);
    }

    public function test_apply_settings_persists_and_replaces_the_logo_file(): void
    {
        Storage::fake(QrCodeGenerator::logoDisk());
        Storage::disk(QrCodeGenerator::logoDisk())->put('qr-logos/old.png', 'old');
        Storage::disk(QrCodeGenerator::logoDisk())->put('qr-logos/new.png', 'new');

        $settings = QrCodeSetting::current();
        $settings->update(['logo_enabled' => true, 'logo_path' => 'qr-logos/old.png']);

        $settings->applySettings([
            'format' => 'png',
            'size' => 600,
            'margin' => 4,
            'error_correction' => 'Q',
            'foreground_color' => '#123456',
            'background_color' => '#654321',
            'transparent_background' => true,
            'logo_enabled' => true,
            'logo_path' => 'qr-logos/new.png',
            'logo_size_percent' => 30,
        ]);

        $settings->refresh();

        $this->assertSame('png', $settings->format);
        $this->assertSame(600, $settings->size);
        $this->assertSame('Q', $settings->error_correction);
        $this->assertSame('qr-logos/new.png', $settings->logo_path);
        Storage::disk(QrCodeGenerator::logoDisk())->assertMissing('qr-logos/old.png');
        Storage::disk(QrCodeGenerator::logoDisk())->assertExists('qr-logos/new.png');
    }

    public function test_quick_download_uses_the_saved_defaults(): void
    {
        QrCodeSetting::current()->update(['format' => 'png', 'size' => 450]);

        $link = ShortLink::create([
            'title' => 'Demo',
            'destination_url' => 'https://example.com',
        ]);

        $qr = app(QrCodeGenerator::class)->generate(
            $link->trackableUrl(),
            QrCodeSetting::current()->toGeneratorSettings(),
        );

        $this->assertSame('png', $qr->extension);
        $this->assertSame("\x89PNG", substr($qr->content, 0, 4));

        $image = imagecreatefromstring($qr->content);
        $this->assertNotFalse($image);
        $this->assertSame(450, imagesx($image));
        imagedestroy($image);
    }
}
