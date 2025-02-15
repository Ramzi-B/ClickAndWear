<?php

namespace App\Enum;

/**
 * Enum representing the gender of a product.
 * 
 */
enum GenderEnum: string
{
    case MEN = 'men';
    case WOMEN = 'women';
    case UNISEX = 'unisex';
    case KIDS = 'kids';
    case BABY = 'baby';
    case BOY = 'boy';
    case GIRL = 'girl';

    /**
     * Get the label associated with the gender.
     * 
     * @return string The label associated with the gender.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::MEN => 'Homme',
            self::WOMEN => 'Femme',
            self::UNISEX => 'Unisexe',
            self::KIDS => 'Enfant',
            self::BABY => 'Bebe',
            self::BOY => 'Garcon',
            self::GIRL => 'Fille',
        };
    }

    /**
     * Get the array of choices.
     * 
     * @return array<string, string>
     */
    public static function getChoices(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->getLabel(), self::cases())
        );
    }
}
