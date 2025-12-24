<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Terminal;

use Eureka\Component\Console\Output\Output;

/**
 * Terminal class to manage terminal info or clear it.
 *
 * @author Romain Cottard
 */
class Terminal
{
    /** @var string CSI Control Sequence Introducer */
    public const CSI = "\033[";

    public const IS_WINDOWS = (\DIRECTORY_SEPARATOR === '\\');

    public const IS_UNIX = (\DIRECTORY_SEPARATOR === '/');

    private Cursor $cursor;

    private int $width;
    private int $height;

    public function __construct(
        private readonly Output $output,
        private readonly Shell $shell = new Shell(),
    ) {
        $this->cursor = new Cursor($this->output);

        $this->init();
    }

    public function clear(): void
    {
        $this->cursor->clear();
    }

    public function cursor(): Cursor
    {
        return $this->cursor; // @codeCoverageIgnore
    }

    public function output(): Output
    {
        return $this->output; // @codeCoverageIgnore
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    private function init(): void
    {
        $width  = false !== \getenv('COLUMNS') ? (int) trim((string) \getenv('COLUMNS')) : null;
        $height = false !== \getenv('LINES') ? (int) trim((string) \getenv('LINES')) : null;

        if ($width !== null && $height !== null) {
            $this->width  = $width;
            $this->height = $height;
            return;
        }

        //~ Set default value, that could be overridden by stty
        $this->width  = 80;
        $this->height = 50;

        //~ No value found from env vars, try to get them from shell command
        $this->initFromStty();
    }

    private function initFromStty(): void
    {
        $stty = (string) $this->shell->exec('stty -a');
        if (
            \preg_match('`rows.(?<height>\d+);.columns.(?<width>\d+);`is', $stty, $matches) > 0
            || \preg_match('`;.(?<height>\d+).rows;.(?<width>\d+).columns`is', $stty, $matches) > 0
        ) {
            $this->width  = (int) $matches['width'];
            $this->height = (int) $matches['height'];
        }
    }
}
