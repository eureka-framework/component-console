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
class Bit24RGBColor implements Bit24Color
{
    private int $r;
    private int $g;
    private int $b;

    public function __construct(int $r, int $g, int $b)
    {
        if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255) {
            throw new \InvalidArgumentException('Color R,G,B must be in range [0, 255]!');
        }

        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public function getIndex(): int
    {
        return 0;
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public function rgb(): array
    {
        return [$this->r, $this->g, $this->b];
    }

    public function r(): int
    {
        return $this->r;
    }

    public function g(): int
    {
        return $this->g;
    }

    public function b(): int
    {
        return $this->b;
    }
}
