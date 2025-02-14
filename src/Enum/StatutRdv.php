<?php

namespace App\Enum;

enum StatutRdv: string
{
    case CONFIRME = 'confirme';
    case ANNULE = 'annule';

    public function label(): string
    {
        return match ($this) {
            self::CONFIRME => 'Confirmé',
            self::ANNULE => 'Annulé',
        };
    }
}
