<?php

namespace App\Enum;

enum StatutRdv: string
{
<<<<<<< HEAD
    case EN_ATTENTE = 'en_attente';
=======
>>>>>>> master
    case CONFIRME = 'confirme';
    case ANNULE = 'annule';

    public function label(): string
    {
        return match ($this) {
<<<<<<< HEAD
            self::EN_ATTENTE => 'En attente',
=======
>>>>>>> master
            self::CONFIRME => 'Confirmé',
            self::ANNULE => 'Annulé',
        };
    }
}
<<<<<<< HEAD

=======
>>>>>>> master
