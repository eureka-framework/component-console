<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\Style\OldStyle;

/**
 * Class Row
 *
 * @author Romain Cottard
 */
class Row
{
    /** @var Cell[] $cells */
    private array $cells;
    private bool $isBar;
    private int $barType;
    private ?OldStyle $style;
    private BorderStyle $borderStyle;

    /**
     * Row constructor.
     *
     * @param Cell[] $cells
     * @param bool $isHeader @deprecated
     * @param bool $isBar
     * @param OldStyle|null $style
     * @param int $barType
     * @param BorderStyle|null $borderStyle
     */
    public function __construct(  // @-phpstan-ignore-line - Ignore $isHeader unused parameter
        array $cells,
        bool $isHeader = false,
        bool $isBar = false,
        OldStyle $style = null,
        int $barType = BorderStyle::SIMPLE_MIDDLE,
        BorderStyle $borderStyle = null
    ) {
        $this->cells   = $cells;
        $this->isBar   = $isBar;
        $this->style   = $style;
        $this->barType = $barType;

        $this->borderStyle = $borderStyle ?? new BorderStyle(BorderStyle::ASCII);
    }

    public function render(): string
    {
        $cells = [];

        foreach ($this->cells as $cell) {
            $cells[] = (string) $cell;
        }

        [$glue, $left, $right] = $this->borderStyle->getChars($this->barType, $this->isBar);

        $line = $left . implode($glue, $cells) . $right;
        if ($this->style instanceof OldStyle) {
            $line = (string) $this->style->setText($line);
        }

        return $line;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
