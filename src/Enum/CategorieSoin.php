<?php

namespace App\Enum;

enum CategorieSoin: string
{
    case INFIRMIER = 'infirmier';
    case KINE = 'kine';

    public function label(): string
    {
        return match ($this) {
            self::INFIRMIER => 'Infirmier',
            self::KINE => 'Kinésithérapeute',
        };
    }
}
