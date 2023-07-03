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

class Cell
{
    public function __construct(
        private readonly string|int|float|bool|null $content,
        private readonly CellStyle $style = new CellStyle()
    ) {
    }

    public function getStyle(): CellStyle
    {
        return $this->style;
    }

    public function getContent(): string|int|float|bool|null
    {
        return $this->content;
    }

    public function render(CellStyle $inheritedStyle, Options $options): string
    {
        $style = $this->style->inheritFrom($inheritedStyle);

        //~ Handle too long content for cell
        $length  = $style->getWidth() - ($style->hasPaddingSpace() ? 2 : 0);
        $content = (string) $this->content;

        if (mb_strlen($content) > $length) {
            $content = mb_substr($content, 0, $length - 1) . '…';
        }

        //~ Add padding space (if any) & align content in cell
        $content = $style->hasPaddingSpace() ? " $content " : $content;
        $content = str_pad($content, $style->getWidth(), ' ', $style->getAlign()->value);

        return $style->apply($content, $options);
    }
}
