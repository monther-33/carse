<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Color;
use Illuminate\Database\Seeder;

/**
 * Starter lists for brands, models and colours. Safe to re-run.
 */
class ReferenceDataSeeder extends Seeder
{
    private const BRANDS = [
        'تويوتا' => ['كامري', 'كورولا', 'لاندكروزر', 'هايلكس', 'راف 4'],
        'هيونداي' => ['إلنترا', 'سوناتا', 'توسان', 'سانتافي', 'أكسنت'],
        'كيا' => ['سبورتاج', 'سيراتو', 'سورينتو', 'ريو', 'K5'],
        'نيسان' => ['صني', 'التيما', 'باترول', 'إكس تريل'],
        'ميتسوبيشي' => ['باجيرو', 'L200', 'لانسر'],
        'مرسيدس' => ['C-Class', 'E-Class', 'S-Class', 'GLE'],
        'شيفروليه' => ['كابتيفا', 'ماليبو', 'تاهو'],
        'فورد' => ['إكسبلورر', 'F-150', 'فيوجن'],
    ];

    private const COLORS = [
        'أبيض' => '#FFFFFF', 'أسود' => '#000000', 'فضي' => '#C0C0C0', 'رمادي' => '#808080',
        'أحمر' => '#C00000', 'أزرق' => '#1F4E99', 'بيج' => '#D8C8A8', 'بني' => '#6B4226',
    ];

    public function run(): void
    {
        foreach (self::BRANDS as $brandName => $models) {
            $brand = Brand::query()->firstOrCreate(['name' => $brandName]);

            foreach ($models as $model) {
                $brand->models()->firstOrCreate(['name' => $model]);
            }
        }

        foreach (self::COLORS as $name => $hex) {
            Color::query()->firstOrCreate(['name' => $name], ['hex' => $hex]);
        }
    }
}
