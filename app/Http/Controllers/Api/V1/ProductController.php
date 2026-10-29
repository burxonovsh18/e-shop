<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category'  => ['nullable', 'string'],
            'brand_id'  => ['nullable', 'integer'],
            'search'    => ['nullable', 'string', 'max:100'],
            'sort'      => ['nullable', 'in:newest,price_asc,price_desc'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Product::active()->with(['variants' => fn ($q) => $q->where('is_active', true), 'brand']);

        if ($slug = $request->query('category')) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                // ota kategoriya tanlansa, bolalari ham kiradi
                $ids = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        match ($request->query('sort')) {
            'price_asc'  => $query->orderBy(
                \DB::table('product_variants')->selectRaw('MIN(price)')->whereColumn('product_id', 'products.id')
            ),
            'price_desc' => $query->orderByDesc(
                \DB::table('product_variants')->selectRaw('MIN(price)')->whereColumn('product_id', 'products.id')
            ),
            default      => $query->latest(),
        };

        return ProductResource::collection(
            $query->paginate($request->integer('per_page', 12))
        );
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['category', 'brand', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
