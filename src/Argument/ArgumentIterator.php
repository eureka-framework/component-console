<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Argument;

/**
 * Class ArgumentIterator used in Argument::parse
 *
 * @author Romain Cottard
 */
class ArgumentIterator implements \Iterator
{
    /** @var int $index Current index */
    protected int $index = 0;

    /** @var array $arguments List of arguments */
    protected array $arguments = array();

    /**
     * Class constructor
     *
     * @param array $args array of arguments.
     */
    public function __construct(array $args)
    {
        $this->index     = 0;
        $this->arguments = $args;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->arguments[$this->index];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * @return void
     */
    public function prev()
    {
        --$this->index;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->arguments[$this->index]);
    }
}
