<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';     // yaratildi, to'lov kutilmoqda
    case Paid       = 'paid';        // to'landi
    case Processing = 'processing';  // yig'ilmoqda
    case Shipped    = 'shipped';     // yo'lda
    case Delivered  = 'delivered';   // yetkazildi
    case Cancelled  = 'cancelled';   // bekor qilindi

    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'Kutilmoqda',
            self::Paid       => "To'langan",
            self::Processing => "Yig'ilmoqda",
            self::Shipped    => "Yo'lda",
            self::Delivered  => 'Yetkazildi',
            self::Cancelled  => 'Bekor qilingan',
        };
    }

    /** Admin panel uchun rang */
    public function color(): string
    {
        return match ($this) {
            self::Pending    => 'yellow',
            self::Paid       => 'blue',
            self::Processing => 'indigo',
            self::Shipped    => 'purple',
            self::Delivered  => 'green',
            self::Cancelled  => 'red',
        };
    }

    /** Ruxsat etilgan keyingi statuslar */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending    => [self::Paid, self::Cancelled],
            self::Paid       => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped, self::Cancelled],
            self::Shipped    => [self::Delivered],
            self::Delivered, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    /** CancelOrderAction shu yerga tayanadi */
    public function canBeCancelled(): bool
    {
        return $this->canTransitionTo(self::Cancelled);
    }

    public function isFinal(): bool
    {
        return $this->allowedTransitions() === [];
    }
}
