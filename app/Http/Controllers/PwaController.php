<?php

namespace App\Http\Controllers;

use App\Support\Branding;
use App\Support\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Progressive web app: the manifest (showroom name and icons from the settings), the app
 * icons drawn from the uploaded logo, and the page shown when the network is down.
 * All public: the browser fetches them before anyone signs in.
 */
class PwaController extends Controller
{
    public const SIZES = [192, 512];

    private const THEME = '#14244f';     // brand-900

    private const BACKGROUND = '#f3f4f6'; // gray-100

    public function __construct(private readonly Branding $brand) {}

    public function manifest(): JsonResponse
    {
        $name = $this->brand->name();
        $version = $this->iconVersion();

        $icons = [];
        foreach (self::SIZES as $size) {
            foreach (['any', 'maskable'] as $purpose) {
                $icons[] = [
                    'src' => "/pwa/icon-{$size}-{$purpose}.png?v={$version}",
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => $purpose,
                ];
            }
        }

        return response()->json([
            'id' => '/',
            'name' => $name,
            'short_name' => Str::limit($name, 12, ''),
            'description' => __('app.auth.tagline'),
            'lang' => app()->getLocale(),
            'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            'start_url' => '/dashboard',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'any',
            'theme_color' => self::THEME,
            'background_color' => self::BACKGROUND,
            'icons' => $icons,
            'shortcuts' => [
                ['name' => __('quick.links.sale'), 'url' => '/sales/create'],
                ['name' => __('app.nav.vehicles'), 'url' => '/vehicles'],
                ['name' => __('quick.links.voucher'), 'url' => '/vouchers?new=1'],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json', 'Cache-Control' => 'no-cache'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * The uploaded logo centred on a white square, or a car on the brand colour when no logo
     * was uploaded. "maskable" keeps the artwork inside the safe zone (80%) for round masks.
     */
    public function icon(int $size, string $purpose): Response
    {
        abort_unless(in_array($size, self::SIZES, true) && in_array($purpose, ['any', 'maskable'], true), 404);

        $image = imagecreatetruecolor($size, $size);
        $logo = $this->logoImage();
        $padding = (int) round($size * ($purpose === 'maskable' ? 0.2 : 0.1));

        if ($logo !== null) {
            imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
            $box = $size - 2 * $padding;
            $scale = min($box / imagesx($logo), $box / imagesy($logo));
            $w = (int) round(imagesx($logo) * $scale);
            $h = (int) round(imagesy($logo) * $scale);
            imagecopyresampled($image, $logo, (int) (($size - $w) / 2), (int) (($size - $h) / 2), 0, 0, $w, $h, imagesx($logo), imagesy($logo));
        } else {
            $this->drawDefault($image, $size, $padding);
        }

        ob_start();
        imagepng($image);
        $png = (string) ob_get_clean();

        return response($png, 200, ['Content-Type' => 'image/png', 'Cache-Control' => 'public, max-age=86400']);
    }

    public function offline(): View
    {
        return view('pwa.offline', ['brand' => $this->brand]);
    }

    /** Changes when the logo changes, so installed apps pick up the new icon. */
    private function iconVersion(): string
    {
        return substr(md5((string) app(Settings::class)->get('company.logo')), 0, 8);
    }

    /** @return \GdImage|null */
    private function logoImage()
    {
        $path = app(Settings::class)->get('company.logo');
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $image = @imagecreatefromstring((string) Storage::disk('public')->get($path));

        return $image === false ? null : $image;
    }

    /** Brand gradient with a simple car silhouette. */
    private function drawDefault(\GdImage $image, int $size, int $padding): void
    {
        for ($y = 0; $y < $size; $y++) {
            $t = $y / $size;   // brand-700 → brand-900
            imageline($image, 0, $y, $size, $y, imagecolorallocate($image, (int) (29 - 9 * $t), (int) (58 - 22 * $t), (int) (145 - 66 * $t)));
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $u = ($size - 2 * $padding) / 10;     // drawing unit inside the safe zone
        $x0 = $padding;
        $y0 = (int) ($size / 2 - 1.5 * $u);

        imagefilledrectangle($image, (int) ($x0 + 0.5 * $u), (int) ($y0 + 1.6 * $u), (int) ($x0 + 9.5 * $u), (int) ($y0 + 3.4 * $u), $white);        // body
        imagefilledpolygon($image, [                                                                                                            // cabin
            (int) ($x0 + 2.4 * $u), (int) ($y0 + 1.7 * $u),
            (int) ($x0 + 3.6 * $u), (int) ($y0 + 0.2 * $u),
            (int) ($x0 + 6.6 * $u), (int) ($y0 + 0.2 * $u),
            (int) ($x0 + 7.9 * $u), (int) ($y0 + 1.7 * $u),
        ], $white);
        $wheel = imagecolorallocate($image, 20, 36, 79);
        foreach ([2.6, 7.4] as $cx) {
            imagefilledellipse($image, (int) ($x0 + $cx * $u), (int) ($y0 + 3.4 * $u), (int) (2.0 * $u), (int) (2.0 * $u), $wheel);
            imagefilledellipse($image, (int) ($x0 + $cx * $u), (int) ($y0 + 3.4 * $u), (int) (0.9 * $u), (int) (0.9 * $u), $white);
        }
    }
}
