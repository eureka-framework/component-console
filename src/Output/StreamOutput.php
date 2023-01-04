<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Output;

use Eureka\Component\Console\Exception\InvalidOutputException;

/**
 * Wrapper for display on standard & error channel.
 *
 * @author Romain Cottard
 */
class StreamOutput implements OutputInterface
{
    /** @var resource $stream */
    private $stream;

    private bool $isQuiet;

    /**
     * @param resource $stream
     * @param bool $isQuiet
     */
    public function __construct($stream, bool $isQuiet)
    {
        if (!is_resource($stream)) {
            throw new InvalidOutputException('Invalid resource given!');
        }

        $this->stream  = $stream;
        $this->isQuiet = $isQuiet;
    }

    /**
     * Write the message on the stream output.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function write(string|\Stringable $message): void
    {
        if ($this->isQuiet) {
            return;
        }

        fwrite($this->stream, (string) $message);
    }

    /**
     * Write the message on the stream output with explicit new end line.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function writeln(string|\Stringable $message): void
    {
        $this->write($message . PHP_EOL);
    }
}
