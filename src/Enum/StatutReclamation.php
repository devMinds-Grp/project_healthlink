<?php

namespace App\Enum;

enum StatutReclamation: string
{
    case EN_ATTENTE = 'en_attente';
    case TRAITEE = 'traitee';

    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::TRAITEE => 'Traitée',
        };
    }
}
