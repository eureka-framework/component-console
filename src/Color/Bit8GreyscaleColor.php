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
 * 8 Bits Greyscale color
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
class Bit8GreyscaleColor implements Bit8Color
{
    private int $intensity;

    public function __construct(int $intensity)
    {
        if ($intensity < 0 || $intensity > 23) {
            throw new \InvalidArgumentException('Greyscale intensity must be in range [0, 23]!');
        }

        $this->intensity = $intensity;
    }

    public function getIndex(): int
    {
        return 232 +  $this->intensity;
    }
}
