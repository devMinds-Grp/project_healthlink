<?php

namespace App\Enum;

enum StatutRdv: string
{
    case EN_ATTENTE = 'en_attente';
    case CONFIRME = 'confirme';
    case ANNULE = 'annule';

    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::CONFIRME => 'Confirmé',
            self::ANNULE => 'Annulé',
        };
    }
}