<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

/**
 * Class Column
 *
 * @author Romain Cottard
 */
class Column
{
    /** @var string $name */
    private string $name = '';

    /** @var int $size */
    private int $size = 10;

    /** @var int $align */
    private int $align = Cell::ALIGN_CENTER;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param int $size
     * @param int $align
     */
    public function __construct(string $name, int $size = 10, int $align = Cell::ALIGN_CENTER)
    {
        $this->name  = $name;
        $this->size  = $size;
        $this->align = $align;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAlign(): int
    {
        return $this->align;
    }
}
