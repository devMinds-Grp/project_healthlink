<?php

namespace App\Enum;

enum ResultatDiagnostic: string
{
    case AFFECTE = 'affecte';
    case NON_AFFECTE = 'non_affecte';

    public function label(): string
    {
        return match ($this) {
            self::AFFECTE => 'Affecté',
            self::NON_AFFECTE => 'Non Affecté',
        };
    }
}
