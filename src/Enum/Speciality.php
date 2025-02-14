<?php

namespace App\Enum;

enum Speciality: string
{
    case DENTISTE = 'dentiste';
    case CARDIOLOGUE = 'cardiologue';
    case GENERALISTE = 'generaliste';
    case PSYCHIATRE = 'psychiatre';

    public function label(): string
    {
        return match ($this) {
            self::DENTISTE => 'Dentiste',
            self::CARDIOLOGUE => 'Cardiologue',
            self::GENERALISTE => 'Médecin Généraliste',
            self::PSYCHIATRE => 'Psychiatre',
        };
    }
}
