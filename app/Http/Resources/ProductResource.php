<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id'          => $this->id,
        'name'        => $this->name,
        'slug'        => $this->slug,
        'description' => $this->description,
        'image'       => $this->image,
        'is_featured' => $this->is_featured,
        'min_price'   => $this->min_price,
        'in_stock'    => $this->in_stock,
        'category'    => new CategoryResource($this->whenLoaded('category')),
        'brand'       => $this->whenLoaded('brand', fn () => [
            'id' => $this->brand->id,
            'name' => $this->brand->name,
        ]),
        'variants'    => ProductVariantResource::collection($this->whenLoaded('variants')),
    ];
}
}
