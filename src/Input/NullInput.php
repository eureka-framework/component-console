<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Input;

/**
 * Stream Input.
 * Read input data from void.
 *
 * @author Romain Cottard
 */
class NullInput implements InputInterface
{
    /**
     * Read content from input.
     *
     * @param int $length
     * @param string $lineEnd
     * @return string|null
     */
    public function readLine(int $length = 1025, string $lineEnd = "\n"): ?string
    {
        return null;
    }

    /**
     * @param string $format
     * @return array<int, string|float|int|bool>
     */
    public function readFormat(string $format): array
    {
        return [];
    }

    public function readString(): string
    {
        return '';
    }

    public function readInt(): int
    {
        return 0;
    }

    public function readFloat(): float
    {
        return 0.0;
    }

    public function readBool(): bool
    {
        return false;
    }
}
