<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

/**
 * A4 Arabic PDF rendering with mPDF. Uses the bundled "XB Riyaz" Arabic font,
 * right-to-left layout and Arabic shaping.
 */
final class Pdf
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function render(string $view, array $data, bool $landscape = false): string
    {
        $tempDir = storage_path('app/mpdf');
        File::ensureDirectoryExists($tempDir);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $landscape ? 'A4-L' : 'A4',
            'tempDir' => $tempDir,
            'default_font' => 'xbriyaz',
            'directionality' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_top' => 12,
            'margin_bottom' => 16,
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_footer' => 6,
        ]);
        $mpdf->SetDirectionality('rtl');
        $mpdf->SetHTMLFooter('<div style="text-align:center;font-size:9pt;color:#777">{PAGENO} / {nbpg}</div>');
        $mpdf->WriteHTML(view($view, $data)->render());

        return $mpdf->Output('', 'S');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function inline(string $view, array $data, string $filename, bool $landscape = false): Response
    {
        return response(self::render($view, $data, $landscape), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
