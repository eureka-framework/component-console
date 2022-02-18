<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\IO\Out;
use Eureka\Component\Console\Style\Style;

/**
 * Class Table
 *
 * @author Romain Cottard
 */
class Table
{
    /** @var Row[] $rows */
    private array $rows;

    /** @var Column[] $columns */
    private array $columns;

    private int $borderRendering;

    /**
     * Table constructor.
     *
     * @param Column[] $columns
     */
    public function __construct(array $columns, bool $withHeader = true, int $borderRendering = BorderStyle::ASCII)
    {
        $this->columns         = $columns;
        $this->rows            = [];
        $this->borderRendering = $borderRendering;

        if ($withHeader) {
            $this->createHeader();
        }
    }

    /**
     * @param int $index
     * @return Column
     */
    public function getColumn(int $index): Column
    {
        return $this->columns[$index];
    }

    /**
     * @return $this
     */
    public function createHeader(): self
    {
        $cells = [];

        $this->addBar(BorderStyle::DOUBLE_TOP);
        foreach ($this->columns as $column) {
            $cells[] = new Cell($column->getName(), $column->getSize(), $column->getAlign());
        }

        $this->add(
            new Row(
                $cells,
                true,
                false,
                null,
                BorderStyle::SIMPLE_MIDDLE,
                new BorderStyle($this->borderRendering)
            )
        );
        $this->addBar(BorderStyle::DOUBLE_MIDDLE);

        return $this;
    }

    /**
     * @param Row $row
     * @return Table
     */
    public function add(Row $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param array<int,string> $data
     * @param bool $isHeader
     * @param Style|null $style
     * @return Table
     */
    public function addRow(array $data, bool $isHeader = false, Style $style = null): self
    {
        $cells = [];

        if ($isHeader) {
            $this->addBar(); // @codeCoverageIgnore
        }

        foreach ($data as $index => $value) {
            $column  = $this->getColumn($index);
            $cells[] = new Cell($value, $column->getSize(), $column->getAlign());
        }

        $this->add(
            new Row(
                $cells,
                $isHeader,
                false,
                $style,
                BorderStyle::SIMPLE_MIDDLE,
                new BorderStyle($this->borderRendering)
            )
        );

        if ($isHeader) {
            $this->addBar(); // @codeCoverageIgnore
        }

        return $this;
    }

    /**
     * @param array<int,string> $data
     * @param bool $isHeader
     * @param Style|null $style
     * @return Table
     */
    public function addRowSpan(array $data, bool $isHeader = false, Style $style = null): self
    {
        if ($isHeader) {
            $this->addBar(BorderStyle::DOUBLE_MIDDLE_SPAN_BOTTOM); // @codeCoverageIgnore
        }

        $size = count($this->columns) - 1;
        foreach ($this->columns as $column) {
            $size += $column->getSize();
        }

        $this->add(
            new Row(
                [new Cell(implode(' - ', $data), $size, Cell::ALIGN_CENTER)],
                $isHeader,
                false,
                $style,
                BorderStyle::SIMPLE_MIDDLE,
                new BorderStyle($this->borderRendering)
            )
        );

        if ($isHeader) {
            $this->addBar(BorderStyle::SIMPLE_MIDDLE_SPAN_TOP); // @codeCoverageIgnore
        }

        return $this;
    }

    public function addBar(int $borderType = BorderStyle::SIMPLE_MIDDLE): self
    {
        $borderStyle = new BorderStyle($this->borderRendering);

        if (
            in_array($borderType, [
            BorderStyle::SIMPLE_TOP,
            BorderStyle::SIMPLE_BOTTOM,
            BorderStyle::SIMPLE_MIDDLE,
            BorderStyle::SIMPLE_MIDDLE_SPAN_TOP,
            BorderStyle::SIMPLE_MIDDLE_SPAN_BOTTOM,
            BorderStyle::SIMPLE_MIDDLE_SPAN_BOTH,
            ])
        ) {
            $padString = $borderStyle->getHorizontal(BorderStyle::SIMPLE);
        } else {
            $padString = $borderStyle->getHorizontal(BorderStyle::DOUBLE);
        }

        $cells = [];
        foreach ($this->columns as $column) {
            $cells[] = new Cell(
                self::strPadUnicode('', $column->getSize(), $padString),
                $column->getSize(),
                Cell::ALIGN_CENTER,
                false
            );
        }

        $this->add(new Row($cells, false, true, null, $borderType, $borderStyle));

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->addBar(BorderStyle::DOUBLE_BOTTOM);

        $lines = [];
        foreach ($this->rows as $row) {
            $lines[] = (string) $row;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * @return Table
     * @codeCoverageIgnore
     */
    public function display(): self
    {
        Out::std($this->render());

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    public static function strPadUnicode(
        string $string,
        int $padLength,
        string $padString = ' ',
        int $dir = STR_PAD_RIGHT
    ): string {
        $stringLength    = mb_strlen($string);
        $padStringLength = mb_strlen($padString);
        if (!$stringLength && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
            $stringLength = 1;
        }
        if (!$padLength || !$padStringLength || $padLength <= $stringLength) {
            return $string;
        }

        $result = '';
        $repeat = (int) ceil($stringLength - $padStringLength + $padLength);
        if ($dir == STR_PAD_RIGHT) {
            $result = $string . str_repeat($padString, $repeat);
            $result = mb_substr($result, 0, $padLength);
        } elseif ($dir == STR_PAD_LEFT) {
            $result = str_repeat($padString, $repeat) . $string;
            $result = mb_substr($result, -$padLength);
        } elseif ($dir == STR_PAD_BOTH) {
            $length = ($padLength - $stringLength) / 2;
            $repeat = (int) ceil($length / $padStringLength);
            $result = mb_substr(str_repeat($padString, $repeat), 0, (int) floor($length))
                . $string
                . mb_substr(str_repeat($padString, $repeat), 0, (int) ceil($length));
        }

        return $result;
    }
}
