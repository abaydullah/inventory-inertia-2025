<?php

namespace App\Lang;
enum Lang: String{
    case EN = 'en';
    case BN = 'bn';

    public function label(): string
    {
        return match ($this){
            self::EN => 'English',
            self::BN => 'Bengali'
        };
    }
}
