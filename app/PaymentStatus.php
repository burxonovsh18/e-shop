<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending   = 'pending';    // to'lov yaratildi, kutilmoqda
    case Paid      = 'paid';       // muvaffaqiyatli to'landi
    case Failed    = 'failed';     // xato yoki rad etildi
    case Cancelled = 'cancelled';  // to'lovdan oldin bekor qilindi
    case Refunded  = 'refunded';   // pul qaytarildi

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Kutilmoqda',
            self::Paid      => "To'langan",
            self::Failed    => 'Muvaffaqiyatsiz',
            self::Cancelled => 'Bekor qilingan',
            self::Refunded  => 'Qaytarilgan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending   => 'yellow',
            self::Paid      => 'green',
            self::Failed    => 'red',
            self::Cancelled => 'gray',
            self::Refunded  => 'orange',
        };
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Paid, self::Failed, self::Cancelled],
            self::Paid    => [self::Refunded],
            self::Failed, self::Cancelled, self::Refunded => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }
}
