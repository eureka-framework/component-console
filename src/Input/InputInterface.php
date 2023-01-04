<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Console\Input;

/**
 * Read input data from somewhere.
 *
 * @author Romain Cottard
 */
interface InputInterface
{
    /**
     * Read content from input.
     *
     * @param int $length
     * @param string $lineEnd
     * @return string|null
     */
    public function readLine(int $length = 1025, string $lineEnd = "\n"): ?string;

    /**
     * @param string $format
     * @return array<int, string|float|int|bool>
     */
    public function readFormat(string $format): array;

    public function readString(): string;

    public function readInt(): int;

    public function readFloat(): float;

    public function readBool(): bool;
}
