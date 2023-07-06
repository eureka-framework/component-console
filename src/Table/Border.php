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
use Eureka\Component\Console\Style\Style;

/**
 * Extended style for Borders in Table
 *
 * @author Romain Cottard
 */
class Border
{
    public const TOP      = 1;
    public const BOTTOM   = 2;
    public const VERTICAL = 3;

    public const RIGHT      = 4;
    public const LEFT       = 8;
    public const HORIZONTAL = 12;

    public const INNER      = 15;

    public const DOUBLE = 32;
    public const SIMPLE = 64;
    public const MIXED  = 96;

    public const BASE   = 128;
    public const EXTENDED = 256;

    public const TYPE_TOP               = 1;
    public const TYPE_TOP_SPAN          = 2;
    public const TYPE_BOTTOM            = 3;
    public const TYPE_BOTTOM_SPAN       = 4;
    public const TYPE_INNER             = 5;
    public const TYPE_INNER_SPAN_TOP    = 6;
    public const TYPE_INNER_SPAN_BOTTOM = 7;
    public const TYPE_INNER_SPAN_BOTH   = 8;

    public const BORDERS = [
        self::EXTENDED + self::DOUBLE + self::TOP + self::LEFT                 => '╔',
        self::EXTENDED + self::DOUBLE + self::TOP + self::RIGHT                => '╗',
        self::EXTENDED + self::DOUBLE + self::HORIZONTAL                       => '═',
        self::EXTENDED + self::DOUBLE + self::VERTICAL                         => '║',
        self::EXTENDED + self::DOUBLE + self::BOTTOM + self::LEFT              => '╚',
        self::EXTENDED + self::DOUBLE + self::BOTTOM + self::RIGHT             => '╝',
        self::EXTENDED + self::DOUBLE + self::HORIZONTAL + self::TOP           => '╦',
        self::EXTENDED + self::DOUBLE + self::HORIZONTAL + self::BOTTOM        => '╩',
        self::EXTENDED + self::DOUBLE + self::TOP + self::BOTTOM + self::LEFT  => '╠',
        self::EXTENDED + self::DOUBLE + self::TOP + self::BOTTOM + self::RIGHT => '╣',
        self::EXTENDED + self::DOUBLE + self::INNER                            => '╬',

        self::EXTENDED + self::SIMPLE + self::TOP + self::LEFT                 => '┌',
        self::EXTENDED + self::SIMPLE + self::TOP + self::RIGHT                => '┐',
        self::EXTENDED + self::SIMPLE + self::HORIZONTAL                       => '─',
        self::EXTENDED + self::SIMPLE + self::VERTICAL                         => '│',
        self::EXTENDED + self::SIMPLE + self::BOTTOM + self::LEFT              => '└',
        self::EXTENDED + self::SIMPLE + self::BOTTOM + self::RIGHT             => '┘',
        self::EXTENDED + self::SIMPLE + self::HORIZONTAL + self::TOP           => '┬',
        self::EXTENDED + self::SIMPLE + self::HORIZONTAL + self::BOTTOM        => '┴',
        self::EXTENDED + self::SIMPLE + self::VERTICAL + self::LEFT            => '├',
        self::EXTENDED + self::SIMPLE + self::VERTICAL + self::RIGHT           => '┤',
        self::EXTENDED + self::SIMPLE + self::INNER                            => '┼',

        self::EXTENDED + self::MIXED + self::TOP + self::LEFT                  => '╒',
        self::EXTENDED + self::MIXED + self::TOP + self::RIGHT                 => '╕',
        self::EXTENDED + self::MIXED + self::BOTTOM + self::LEFT               => '╘',
        self::EXTENDED + self::MIXED + self::BOTTOM + self::RIGHT              => '╛',
        self::EXTENDED + self::MIXED + self::HORIZONTAL + self::TOP            => '╤',
        self::EXTENDED + self::MIXED + self::HORIZONTAL + self::BOTTOM         => '╧',
        self::EXTENDED + self::MIXED + self::VERTICAL + self::LEFT             => '╟',
        self::EXTENDED + self::MIXED + self::VERTICAL + self::RIGHT            => '╢',
        self::EXTENDED + self::MIXED + self::INNER                             => '╪',

        self::BASE + self::DOUBLE + self::TOP + self::LEFT                     => '+',
        self::BASE + self::DOUBLE + self::TOP + self::RIGHT                    => '+',
        self::BASE + self::DOUBLE + self::HORIZONTAL                           => '-',
        self::BASE + self::DOUBLE + self::VERTICAL                             => '|',
        self::BASE + self::DOUBLE + self::BOTTOM + self::LEFT                  => '+',
        self::BASE + self::DOUBLE + self::BOTTOM + self::RIGHT                 => '+',
        self::BASE + self::DOUBLE + self::HORIZONTAL + self::TOP               => '+',
        self::BASE + self::DOUBLE + self::HORIZONTAL + self::BOTTOM            => '+',
        self::BASE + self::DOUBLE + self::TOP + self::BOTTOM + self::LEFT      => '+',
        self::BASE + self::DOUBLE + self::TOP + self::BOTTOM + self::RIGHT     => '+',
        self::BASE + self::DOUBLE + self::INNER                                => '+',

        self::BASE + self::SIMPLE + self::TOP + self::LEFT                     => '+',
        self::BASE + self::SIMPLE + self::TOP + self::RIGHT                    => '+',
        self::BASE + self::SIMPLE + self::HORIZONTAL                           => '-',
        self::BASE + self::SIMPLE + self::VERTICAL                             => '|',
        self::BASE + self::SIMPLE + self::BOTTOM + self::LEFT                  => '+',
        self::BASE + self::SIMPLE + self::BOTTOM + self::RIGHT                 => '+',
        self::BASE + self::SIMPLE + self::HORIZONTAL + self::TOP               => '+',
        self::BASE + self::SIMPLE + self::HORIZONTAL + self::BOTTOM            => '+',
        self::BASE + self::SIMPLE + self::VERTICAL + self::LEFT                => '+',
        self::BASE + self::SIMPLE + self::VERTICAL + self::RIGHT               => '+',
        self::BASE + self::SIMPLE + self::INNER                                => '+',

        self::BASE + self::MIXED + self::TOP + self::LEFT                      => '+',
        self::BASE + self::MIXED + self::TOP + self::RIGHT                     => '+',
        self::BASE + self::MIXED + self::BOTTOM + self::LEFT                   => '+',
        self::BASE + self::MIXED + self::BOTTOM + self::RIGHT                  => '+',
        self::BASE + self::MIXED + self::HORIZONTAL + self::TOP                => '+',
        self::BASE + self::MIXED + self::HORIZONTAL + self::BOTTOM             => '+',
        self::BASE + self::MIXED + self::VERTICAL + self::LEFT                 => '+',
        self::BASE + self::MIXED + self::VERTICAL + self::RIGHT                => '+',
        self::BASE + self::MIXED + self::INNER                                 => '+',
    ];

    public function __construct(
        private readonly int $rendering = self::EXTENDED,
        private readonly int $thicknessOut = self::DOUBLE,
        private readonly Style $style = new Style(),
    ) {
    }

    /**
     * @return array<string>
     */
    public function getChars(): array
    {
        $glue  = $this->getVertical(self::SIMPLE);
        $left  = $this->getVertical($this->thicknessOut);
        $right = $this->getVertical($this->thicknessOut);

        return [
            $this->style->apply($glue),
            $this->style->apply($left),
            $this->style->apply($right),
        ];
    }

    public function getCharLine(int $thickness): string
    {
        $char = $this->getHorizontal($thickness);

        return $this->style->apply($char);
    }

    /**
     * @param int $thickness
     * @param int $type
     * @return array<string>
     */
    public function getCharsInnerBar(int $thickness, int $type): array
    {
        $horizontalThickness = $thickness;
        $verticalThickness   = $thickness === self::SIMPLE ? self::MIXED : self::DOUBLE;

        switch ($type) {
            case self::TYPE_TOP:
                $horizontalThickness = $thickness === self::SIMPLE ? self::SIMPLE : self::MIXED;
                $glue  = $this->getHorizontalTop($horizontalThickness);
                $left  = $this->getTopLeft($verticalThickness);
                $right = $this->getTopRight($verticalThickness);
                break;
            case self::TYPE_TOP_SPAN:
                $glue  = $this->getHorizontal($horizontalThickness);
                $left  = $this->getTopLeft($verticalThickness);
                $right = $this->getTopRight($verticalThickness);
                break;
            case self::TYPE_BOTTOM:
                $horizontalThickness = $thickness === self::SIMPLE ? self::SIMPLE : self::MIXED;
                $glue  = $this->getHorizontalBottom($horizontalThickness);
                $left  = $this->getBottomLeft($verticalThickness);
                $right = $this->getBottomRight($verticalThickness);
                break;
            case self::TYPE_BOTTOM_SPAN:
                $glue  = $this->getHorizontal($horizontalThickness);
                $left  = $this->getBottomLeft($verticalThickness);
                $right = $this->getBottomRight($verticalThickness);
                break;
            case self::TYPE_INNER:
                $horizontalThickness = $thickness === self::SIMPLE ? self::SIMPLE : self::MIXED;
                $glue  = $this->getInner($horizontalThickness);
                $left  = $this->getVerticalLeft($verticalThickness);
                $right = $this->getVerticalRight($verticalThickness);
                break;
            case self::TYPE_INNER_SPAN_TOP:
                $horizontalThickness = $thickness === self::SIMPLE ? self::SIMPLE : self::MIXED;
                $glue  = $this->getHorizontalTop($horizontalThickness);
                $left  = $this->getVerticalLeft($verticalThickness);
                $right = $this->getVerticalRight($verticalThickness);
                break;
            case self::TYPE_INNER_SPAN_BOTTOM:
                $horizontalThickness = $thickness === self::SIMPLE ? self::SIMPLE : self::MIXED;
                $glue  = $this->getHorizontalBottom($horizontalThickness);
                $left  = $this->getVerticalLeft($verticalThickness);
                $right = $this->getVerticalRight($verticalThickness);
                break;
            case self::TYPE_INNER_SPAN_BOTH:
                $glue  = $this->getHorizontal($horizontalThickness);
                $left  = $this->getVerticalLeft($verticalThickness);
                $right = $this->getVerticalRight($verticalThickness);
                break;
            default:
                $glue  = $this->getInner(self::SIMPLE);
                $left  = $this->getVerticalLeft(self::DOUBLE);
                $right = $this->getVerticalRight(self::DOUBLE);
        }

        return [
            $this->style->apply($glue),
            $this->style->apply($left),
            $this->style->apply($right),
        ];
    }

    public function getHorizontal(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::HORIZONTAL];
    }

    public function getHorizontalTop(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::HORIZONTAL + self::TOP];
    }

    public function getHorizontalBottom(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::HORIZONTAL + self::BOTTOM];
    }

    public function getVertical(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::VERTICAL];
    }

    public function getVerticalLeft(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::VERTICAL + self::LEFT];
    }

    public function getVerticalRight(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::VERTICAL + self::RIGHT];
    }

    public function getTopLeft(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::TOP + self::LEFT];
    }

    public function getTopRight(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::TOP + self::RIGHT];
    }

    public function getBottomLeft(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::BOTTOM + self::LEFT];
    }

    public function getBottomRight(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::BOTTOM + self::RIGHT];
    }

    public function getInner(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::INNER];
    }
}
