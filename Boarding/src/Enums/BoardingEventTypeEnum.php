<?php

namespace Digipemad\Sia\Boarding\Enums;

enum BoardingEventTypeEnum: int
{
    case REGULAR = 1;
    case SPECIALIZED  = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::REGULAR => 'Kegiatan Umum',
            self::SPECIALIZED => 'Kegiatan Tertentu',
        };
    }
}
