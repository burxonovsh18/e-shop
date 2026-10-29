<?php

namespace App\Enums;

use App\Services\Payment\ClickService;
use App\Services\Payment\PaymeService;
use App\Services\Payment\PaymentGateway;

enum PaymentProvider: string
{
    case Payme = 'payme';
    case Click = 'click';

    public function label(): string
    {
        return match ($this) {
            self::Payme => 'Payme',
            self::Click => 'Click',
        };
    }

    /** Tegishli gateway implementatsiyasini qaytaradi */
    public function gateway(): PaymentGateway
    {
        return match ($this) {
            self::Payme => app(PaymeService::class),
            self::Click => app(ClickService::class),
        };
    }

    /** Select/radio uchun: ['payme' => 'Payme', ...] */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $p) => [$p->value => $p->label()])
            ->all();
    }
}
