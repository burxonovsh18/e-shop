<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentProvider;

class Order extends Model
{
protected function casts(): array
{
    return [
        'status'           => OrderStatus::class,
        'payment_status'   => PaymentStatus::class,
        'payment_provider' => PaymentProvider::class,
    ];
}
}
