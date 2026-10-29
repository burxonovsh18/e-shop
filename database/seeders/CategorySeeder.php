<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Elektronika' => ['Telefonlar', 'Noutbuklar', 'Quloqchinlar'],
            'Kiyim' => ['Erkaklar', 'Ayollar', 'Bolalar'],
            'Uy-ro\'zg\'or' => ['Oshxona', 'Mebel'],
        ];

        $order = 0;
        foreach ($tree as $parentName => $children) {
            $parentSlug = Str::slug($parentName);
            $parent = Category::updateOrCreate(
                ['slug' => $parentSlug],
                [
                    'name' => $parentName,
                    'parent_id' => null,
                    'sort_order' => $order++,
                    'is_active' => true,
                ],
            );

            foreach ($children as $i => $childName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'parent_id' => $parent->id,
                        'name' => $childName,
                        'sort_order' => $i,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
