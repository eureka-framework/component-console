<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\Style\Style;

/**
 * Class Row
 *
 * @author Romain Cottard
 */
class Row
{
    /** @var Cell[] $cells */
    private array $cells = [];

    /** @var bool  */
    private bool $isBar = false;

    /** @var bool  */
    private bool $isHeader = false;

    /** @var Style|null $style */
    private ?Style $style;

    /**
     * Row constructor.
     *
     * @param array $cells
     * @param bool $isHeader
     * @param bool $isBar
     * @param Style|null $style
     */
    public function __construct(array $cells, bool $isHeader = false, bool $isBar = false, Style $style = null)
    {
        $this->cells    = $cells;
        $this->isBar    = $isBar;
        $this->isHeader = $isHeader;
        $this->style    = $style;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $cells = [];

        foreach ($this->cells as $cell) {
            $cells[] = (string) $cell;
        }

        $glue = $this->isBar ? '+' : '|';

        $line = '|' . implode($glue, $cells) . '|';
        if ($this->style instanceof Style) {
            $line = (string) $this->style->setText($line);
        }

        return $line;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
