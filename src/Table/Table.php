<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\Style\CellStyle;

class Table
{
    /** @var Column[] $columns */
    private array $columns = [];

    /** @var Row[] $rows */
    private array $rows = [];

    /**
     * @param int|Column[] $columns
     */
    public function __construct(
        int|array $columns,
        private readonly Border $border = new Border(),
    ) {
        if (is_array($columns)) {
            $this->columns = $columns;
        } else {
            for ($i = 0; $i < $columns; $i++) {
                $this->columns[$i] = new Column();
            }
        }
    }

    public function addRow(Row $row): static
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param array<int, string|int|float|bool|null|Cell> $data
     * @return $this
     */
    public function newRow(array $data, bool $isHeader = false, CellStyle $style = new CellStyle()): static
    {
        $row = new Row(isHeader: $isHeader, style: $style);
        foreach ($data as $item) {
            if ($item instanceof Cell) {
                $row->addCell($item);
            } else {
                $row->newCell($item);
            }
        }

        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param Cell|string|int|float|bool|null $data
     * @param bool $isHeader
     * @param CellStyle $style
     * @return $this
     */
    public function newRowSpan(
        Cell|string|int|float|bool|null $data,
        bool $isHeader = false,
        CellStyle $style = new CellStyle(align: Align::Center),
    ): static {
        $row = new Row(isHeader: $isHeader, style: $style);

        $width = array_reduce(
            $this->columns,
            fn(int $width, Column $column) => $width + $column->getStyle()->getWidth(),
            count($this->columns) - 1,
        );

        if ($data instanceof Cell) {
            $style = (new CellStyle(width: $width))->inheritFrom($data->getStyle());
            $cell  = new Cell($data->getContent(), $style);
        } else {
            $cell = new Cell($data, (new CellStyle(width: $width)));
        }

        $row->addCell($cell);

        $this->rows[] = $row;

        return $this;
    }

    public function render(): string
    {
        $table   = [];

        $borderType = $this->getBorderType(null, $this->rows[0]);
        $table[]    = $this->renderBorder(Border::DOUBLE, $borderType);

        $isBarForPreviousRow = true;

        foreach ($this->rows as $index => $row) {
            if ($isBarForPreviousRow === false && $row->isHeader()) {
                $type = $this->getBorderType($this->rows[($index - 1)] ?? null, $row);
                $table[] = $this->renderBorder(Border::DOUBLE, $type);
            }

            $table[] = $row->render($this->columns, $this->border);
            $isBarForPreviousRow = false;

            if ($row->isHeader() && ($this->rows[($index + 1)] ?? null) !== null) {
                $type = $this->getBorderType($row, $this->rows[($index + 1)]);
                $table[] = $this->renderBorder(Border::DOUBLE, $type);
                $isBarForPreviousRow = true;
            }
        }

        /** @var Row $previous */
        $previous = end($this->rows);
        $type = $this->getBorderType($previous, null);
        $table[] = $this->renderBorder(Border::DOUBLE, $type);

        return implode(PHP_EOL, $table) . PHP_EOL;
    }

    private function getBorderType(Row|null $previous, Row|null $next): int
    {
        $isInner  = $next !== null && $previous !== null;

        return match (true) {
            $next !== null && $previous === null && !$next->isSpan() => Border::TYPE_TOP,
            $next !== null && $previous === null && $next->isSpan() => Border::TYPE_TOP_SPAN,
            $isInner && $previous->isSpan() && $next->isSpan() => Border::TYPE_INNER_SPAN_BOTH,
            $isInner && $previous->isSpan() && !$next->isSpan() => Border::TYPE_INNER_SPAN_TOP,
            $isInner && !$previous->isSpan() && $next->isSpan() => Border::TYPE_INNER_SPAN_BOTTOM,
            $next === null && $previous !== null && $previous->isSpan() => Border::TYPE_BOTTOM_SPAN,
            $next === null && $previous !== null && !$previous->isSpan() => Border::TYPE_BOTTOM,
            default => Border::TYPE_INNER,
        };
    }

    private function renderBorder(int $thickness, int $type): string
    {
        $char = $this->border->getCharLine($thickness);
        [$glue, $left, $right] = $this->border->getCharsInnerBar($thickness, $type);

        $lines = [];
        foreach ($this->columns as $column) {
            $lines[] = str_repeat($char, $column->getStyle()->getWidth());
        }

        return $left . implode($glue, $lines) . $right;
    }
}
