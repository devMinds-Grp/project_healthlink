<?php
namespace App\Enum;

enum TypeRdv: string
{
    case EN_LIGNE = 'en_ligne';
    case CABINET = 'presentielle';

    public function label(): string
    {
        return match ($this) {
            self::EN_LIGNE => 'En ligne',
            self::CABINET => 'presentielle',
        };
    }
}
