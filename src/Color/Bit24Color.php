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
 * Handle color in terminal
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
interface Bit24Color extends Color
{
    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public function rgb(): array;

    public function r(): int;
    public function g(): int;
    public function b(): int;
}
