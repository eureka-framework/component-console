<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Input;

use Eureka\Component\Console\Exception\InvalidInputException;

/**
 * Stream Input.
 * Read input data from a stream.
 *
 * @author Romain Cottard
 */
class StreamInput implements InputInterface
{
    /** @var resource $stream */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidInputException('Invalid resource given!');
        }

        $this->stream = $stream;
    }

    /**
     * Read content from input.
     *
     * @param int<0, max> $length
     * @param string $lineEnd
     * @return string|null
     */
    public function readLine(int $length = 1025, string $lineEnd = "\n"): ?string
    {
        return rtrim((string) fread($this->stream, $length), $lineEnd);
    }

    /**
     * @param string $format
     * @return array<int, string|float|int|bool>
     */
    public function readFormat(string $format): array
    {
        return (array) fscanf($this->stream, $format);
    }

    public function readString(): string
    {
        fscanf($this->stream, '%s', $string);

        return (string) $string;
    }

    public function readInt(): int
    {
        fscanf($this->stream, '%d', $number);

        return (int) $number;
    }

    public function readFloat(): float
    {
        fscanf($this->stream, '%s', $number);

        $number = str_replace(',', '.', (string) $number);

        return (float) $number;
    }

    public function readBool(): bool
    {
        fscanf($this->stream, '%d', $number);

        return (bool) $number;
    }
}
