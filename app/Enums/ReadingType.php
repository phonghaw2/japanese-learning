<?php

namespace App\Enums;

class ReadingType
{
    const NONE = 0;
    const ON = 1;
    const KUN = 2;

    /**
     * Get the label for the reading type.
     *
     * @param int $value
     * @return string
     */
    public static function label(int $value): string
    {
        switch ($value) {
            case self::ON:
                return 'ON';
            case self::KUN:
                return 'KUN';
            default:
                return '';
        }
    }

    /**
     * Get all available options as [value => label].
     *
     * @return array
     */
    public static function options(): array
    {
        return [
            self::ON => 'Âm on',
            self::KUN => 'Âm kun',
        ];
    }
}
