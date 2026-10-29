<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_only_active_products(): void
    {
        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
        ]);

        foreach (range(1, 3) as $number) {
            $product = $this->createProduct($category, "active-product-{$number}");

            $this->createVariant($product, "ACTIVE-{$number}");
        }

        $this->createProduct($category, 'inactive-product', false);

        $this->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_shows_product_by_slug(): void
    {
        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
        ]);
        $product = $this->createProduct($category, 'test-phone');
        $this->createVariant($product, 'TEST-PHONE');

        $this->getJson("/api/v1/products/{$product->slug}")
            ->assertOk()
            ->assertJsonPath('data.slug', $product->slug);
    }

    public function test_returns_404_for_inactive_product(): void
    {
        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
        ]);
        $product = $this->createProduct($category, 'inactive-phone', false);

        $this->getJson("/api/v1/products/{$product->slug}")
            ->assertNotFound();
    }

    private function createProduct(Category $category, string $slug, bool $isActive = true): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => str($slug)->replace('-', ' ')->title()->toString(),
            'slug' => $slug,
            'is_active' => $isActive,
        ]);
    }

    private function createVariant(Product $product, string $sku): ProductVariant
    {
        return $product->variants()->create([
            'sku' => $sku,
            'price' => 100000,
            'stock' => 5,
        ]);
    }
}
