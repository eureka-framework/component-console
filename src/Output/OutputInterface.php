<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Console\Output;

/**
 * Wrapper for display on standard & error channel.
 *
 * @author Romain Cottard
 */
interface OutputInterface
{
    /**
     * Write the message on the stream output.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function write(string|\Stringable $message): void;

    /**
     * Write the message on the stream output with explicit new end line.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function writeln(string|\Stringable $message): void;
}
