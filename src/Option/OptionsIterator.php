<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Option;

/**
 * Class ArgumentIterator used in Argument::parse
 *
 * @author Romain Cottard
 * @implements \Iterator<int,string>
 */
class OptionsIterator implements \Iterator
{
    /** @var int $index Current index */
    protected int $index = 0;

    /** @var array<string> $arguments List of arguments */
    protected array $arguments = [];

    /**
     * Class constructor
     *
     * @param array<string> $args array of arguments.
     */
    public function __construct(array $args)
    {
        $this->index     = 0;
        $this->arguments = $args;
    }

    /**
     * @return string
     */
    public function current(): string
    {
        return $this->arguments[$this->index];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->index;
    }

    /**
     * @return void
     */
    public function prev(): void
    {
        --$this->index;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->arguments[$this->index]);
    }
}
