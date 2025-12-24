<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Style;

use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Table\Align;

/**
 * Extended style for Cells in Table
 *
 * @author Romain Cottard
 */
class CellStyle extends Style
{
    private const DEFAULT_WIDTH = 10;
    private const DEFAULT_ALIGN = Align::Left;
    private const DEFAULT_PADDING_SPACE = true;

    public function __construct(
        Options $options = new Options(),
        private int $width = self::DEFAULT_WIDTH,
        private Align $align = self::DEFAULT_ALIGN,
        private bool $paddingSpace = self::DEFAULT_PADDING_SPACE,
    ) {
        parent::__construct($options);
    }

    public function inheritFrom(?CellStyle $inheritedStyle): CellStyle
    {
        if ($inheritedStyle === null) {
            return clone $this;
        }

        $style = clone $inheritedStyle;
        $overrideStyle = $this;

        if ($overrideStyle->fgColor !== null) {
            $style->fgColor = $overrideStyle->fgColor;
        }

        if ($overrideStyle->bgColor !== null) {
            $style->bgColor = $overrideStyle->bgColor;
        }

        if ($overrideStyle->bold !== false) {
            $style->bold = $overrideStyle->bold;
        }

        if ($overrideStyle->faint !== false) {
            $style->faint = $overrideStyle->faint;
        }

        if ($overrideStyle->italic !== false) {
            $style->italic = $overrideStyle->italic;
        }

        if ($overrideStyle->underline !== false) {
            $style->underline = $overrideStyle->underline;
        }

        if ($overrideStyle->blink !== false) {
            $style->blink = $overrideStyle->blink;
        }

        if ($overrideStyle->fastBlink !== false) {
            $style->fastBlink = $overrideStyle->fastBlink;
        }

        if ($overrideStyle->invert !== false) {
            $style->invert = $overrideStyle->invert;
        }

        if ($overrideStyle->strike !== false) {
            $style->strike = $overrideStyle->strike;
        }

        if ($overrideStyle->width !== self::DEFAULT_WIDTH) {
            $style->width = $overrideStyle->width;
        }

        if ($overrideStyle->align !== self::DEFAULT_ALIGN) {
            $style->align = $overrideStyle->align;
        }

        if ($overrideStyle->paddingSpace !== self::DEFAULT_PADDING_SPACE) {
            $style->paddingSpace = $overrideStyle->paddingSpace;
        }

        return $style;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getAlign(): Align
    {
        return $this->align;
    }

    public function hasPaddingSpace(): bool
    {
        return $this->paddingSpace;
    }
}
