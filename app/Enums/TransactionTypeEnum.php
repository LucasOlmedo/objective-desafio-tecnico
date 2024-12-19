<?php

namespace App\Enums;

class TransactionTypeEnum
{
    const CREDIT = 'C';
    const DEBIT = 'D';
    const PIX = 'P';

    public const FEES = [
        self::CREDIT => 0.05,
        self::DEBIT => 0.03,
        self::PIX => 0,
    ];

    public static function getDescription(string $type): string
    {
        return match ($type) {
            self::CREDIT => 'Crédito - ' . self::FEES[$type] * 100 . '%',
            self::DEBIT => 'Débito - ' . self::FEES[$type] * 100 . '%',
            self::PIX => 'PIX - ' . self::FEES[$type] * 100 . '%',
        };
    }
}
