<?php

namespace App\Enum;

enum TypePharmacie: string
{
    case PHARMACIE_DE_NUIT = 'pharmacie_de_nuit';
    case PHARMACIE_DE_JOUR = 'pharmacie_de_jour';
    case PHARMACIE_DE_GARDE = 'pharmacie_de_garde';

    public function label(): string
    {
        return match ($this) {
            self::PHARMACIE_DE_NUIT => 'Pharmacie de Nuit',
            self::PHARMACIE_DE_JOUR => 'Pharmacie de Jour',
            self::PHARMACIE_DE_GARDE => 'Pharmacie de Garde',
        };
    }
}
