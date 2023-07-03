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
 * 8 Bits RGB colors
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
class Bit8RGBColor implements Bit8Color
{
    private int $r;
    private int $g;
    private int $b;

    public function __construct(int $r, int $g, int $b)
    {
        if ($r < 0 || $r > 5 || $g < 0 || $g > 5 || $b < 0 || $b > 5) {
            throw new \InvalidArgumentException('Color R,G,B must be in range [0, 5]!');
        }

        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public function getIndex(): int
    {
        return 16 + (36 * $this->r) + (6 * $this->g) + $this->b;
    }
}
