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
 * 8 Bits high intensity colors
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
enum Bit8HighColor: int implements Bit8Color
{
    case Black = 8;
    case Red = 9;
    case Green = 10;
    case Yellow = 11;
    case Blue = 12;
    case Magenta = 13;
    case Cyan = 14;
    case White = 15;

    public function getIndex(): int
    {
        return $this->value;
    }
}
