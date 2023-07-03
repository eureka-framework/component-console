<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Color;

/**
 * 8 Bits Standard color
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
enum Bit8StandardColor: int implements Bit8Color
{
    case Black = 0;
    case Red = 1;
    case Green = 2;
    case Yellow = 3;
    case Blue = 4;
    case Magenta = 5;
    case Cyan = 6;
    case White = 7;

    public function getIndex(): int
    {
        return $this->value;
    }
}
