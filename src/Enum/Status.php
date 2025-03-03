<?php

namespace App\Enum;

enum Status: string
{






    



    case REFUSE = 'Refusé';
    case EN_ATTENTE = 'En cours';
    case TERMINE = 'Traité et Terminé';

    public function label(): string
    {
        return match ($this) {
            self::REFUSE => 'Refusé',
            self::EN_ATTENTE => 'En cours',
            self::TERMINE => 'Traité et Terminé',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::REFUSE => 'bg-danger',  // Rouge pour refusé
            self::EN_ATTENTE => 'bg-warning text-dark',  // Jaune pour en cours
            self::TERMINE => 'bg-success',  // Vert pour terminé
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::REFUSE => 'bx-x-circle',  // Croix pour refusé
            self::EN_ATTENTE => 'bx-time',  // Horloge pour en attente
            self::TERMINE => 'bx-check-circle',  // Check pour terminé
        };
    }
}

