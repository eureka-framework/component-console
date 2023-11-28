<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\CellStyle;

class Row
{
    /**
     * @param Cell[] $cells
     */
    public function __construct(
        private array $cells = [],
        private readonly bool $isHeader = false,
        private readonly CellStyle $style = new CellStyle()
    ) {}

    public function isHeader(): bool
    {
        return $this->isHeader;
    }

    public function isSpan(): bool
    {
        return count($this->cells) === 1;
    }

    public function addCell(Cell $cell): static
    {
        $this->cells[] = $cell;

        return $this;
    }

    public function newCell(string|int|float|bool|null $content): static
    {
        $cell = new Cell((string) $content);

        $this->cells[] = $cell;

        return $this;
    }

    /**
     * @param Column[] $columns
     * @param Border $border
     * @return string
     */
    public function render(array $columns, Border $border): string
    {
        $cells = [];
        foreach ($this->cells as $index => $cell) {
            $column = $columns[$index];
            $style = $this->style->inheritFrom($column->getStyle());

            $cells[] = $cell->render($style);
        }

        [$glue, $left, $right] = $border->getChars();

        return $left . implode($glue, $cells) . $right;
    }
}
