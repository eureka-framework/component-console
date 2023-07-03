<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Output;

/**
 * Wrapper for display on standard & error channel.
 *
 * @author Romain Cottard
 */
class NullOutput implements Output
{
    /**
     * Write message in the void.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function write(string|\Stringable $message): void
    {
        //~ Nothing to do for Null Output
    }

    /**
     * Write message in the void.
     *
     * @param  string|\Stringable $message
     * @return void
     */
    public function writeln(string|\Stringable $message): void
    {
        //~ Nothing to do for Null Output
    }
}
