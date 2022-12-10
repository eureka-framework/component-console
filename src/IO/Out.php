<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Console\IO;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Style\Style;

/**
 * Wrapper for display on standard & error channel.
 *
 * @author Romain Cottard
 */
class Out
{
    /** @var bool $allowBuffering Enables/disables output buffering */
    private static bool $allowBuffering = false;

    /**
     * Indicates if output buffering should be enabled or not
     *
     * @param bool $allow
     */
    public static function allowBuffering(bool $allow): void
    {
        self::$allowBuffering = $allow;
    }

    /**
     * Display message on error output
     *
     * @param  string|Style $message
     * @param  string $endLine
     * @return void
     * @codeCoverageIgnore
     */
    public static function err($message, string $endLine = PHP_EOL)
    {
        if (!Argument::getInstance()->has('quiet')) {
            if (self::$allowBuffering) {
                echo $message . $endLine;
            } else {
                fwrite(STDERR, (string) $message . $endLine);
            }
        }
    }

    /**
     * Display message on standard output
     *
     * @param string|Style $message
     * @param string $endLine
     * @return void
     */
    public static function std($message, string $endLine = PHP_EOL)
    {
        if (!Argument::getInstance()->has('quiet')) {
            if (self::$allowBuffering) {
                echo $message . $endLine;
            } else {
                fwrite(STDOUT, (string) $message . $endLine); // @codeCoverageIgnore
            }
        }
    }

    /**
     * @return void
     * @codeCoverageIgnore
     */
    public static function clear(): void
    {
        fwrite(STDOUT, "\x1b[H\x1b[J");
    }
}
