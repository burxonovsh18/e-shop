<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'telefonlar',
                'name' => 'Smartfon 128 GB',
                'slug' => 'demo-smartfon-128-gb',
                'description' => 'Kundalik foydalanish uchun 128 GB xotirali smartfon.',
                'sku' => 'DEMO-PHONE-128',
                'variant' => '128 GB',
                'price' => 2499000,
                'old_price' => 2799000,
                'stock' => 15,
            ],
            [
                'category' => 'noutbuklar',
                'name' => 'Noutbuk 15.6',
                'slug' => 'demo-noutbuk-15-6',
                'description' => 'Oqish va kundalik ishlar uchun noutbuk.',
                'sku' => 'DEMO-LAPTOP-156',
                'variant' => '8 GB / 256 GB',
                'price' => 5499000,
                'old_price' => null,
                'stock' => 10,
            ],
            [
                'category' => 'quloqchinlar',
                'name' => 'Simsiz quloqchin',
                'slug' => 'demo-simsiz-quloqchin',
                'description' => 'Musiqa va qo‘ng‘iroqlar uchun simsiz quloqchin.',
                'sku' => 'DEMO-HEADPHONES',
                'variant' => 'Qora',
                'price' => 399000,
                'old_price' => null,
                'stock' => 20,
            ],
            [
                'category' => 'erkaklar',
                'name' => 'Erkaklar futbolkasi',
                'slug' => 'demo-erkaklar-futbolkasi',
                'description' => 'Kundalik kiyish uchun erkaklar futbolkasi.',
                'sku' => 'DEMO-MENS-TEE',
                'variant' => 'M o‘lcham',
                'price' => 149000,
                'old_price' => null,
                'stock' => 25,
            ],
            [
                'category' => 'ayollar',
                'name' => 'Ayollar ko‘ylagi',
                'slug' => 'demo-ayollar-koylagi',
                'description' => 'Ayollar uchun kundalik ko‘ylak.',
                'sku' => 'DEMO-WOMENS-DRESS',
                'variant' => 'M o‘lcham',
                'price' => 349000,
                'old_price' => null,
                'stock' => 12,
            ],
            [
                'category' => 'bolalar',
                'name' => 'Bolalar kiyim to‘plami',
                'slug' => 'demo-bolalar-kiyim-toplami',
                'description' => 'Bolalar uchun qulay kiyim to‘plami.',
                'sku' => 'DEMO-KIDS-SET',
                'variant' => '4 yosh',
                'price' => 229000,
                'old_price' => null,
                'stock' => 14,
            ],
            [
                'category' => 'oshxona',
                'name' => 'Oshxona anjomlari to‘plami',
                'slug' => 'demo-oshxona-anjomlari',
                'description' => 'Kundalik foydalanish uchun oshxona anjomlari.',
                'sku' => 'DEMO-KITCHEN-SET',
                'variant' => 'To‘plam',
                'price' => 499000,
                'old_price' => null,
                'stock' => 9,
            ],
            [
                'category' => 'mebel',
                'name' => 'Yozuv stoli',
                'slug' => 'demo-yozuv-stoli',
                'description' => 'Uy yoki ofis uchun yozuv stoli.',
                'sku' => 'DEMO-DESK',
                'variant' => 'Oq',
                'price' => 1299000,
                'old_price' => null,
                'stock' => 6,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::query()
                ->where('slug', $productData['category'])
                ->firstOrFail();

            $product = Product::updateOrCreate(
                ['slug' => $productData['slug']],
                [
                    'category_id' => $category->id,
                    'brand_id' => null,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'image' => null,
                    'is_active' => true,
                    'is_featured' => false,
                ],
            );

            $product->variants()->updateOrCreate(
                ['sku' => $productData['sku']],
                [
                    'name' => $productData['variant'],
                    'attributes' => null,
                    'price' => $productData['price'],
                    'old_price' => $productData['old_price'],
                    'stock' => $productData['stock'],
                    'is_active' => true,
                ],
            );
        }
    }
}
